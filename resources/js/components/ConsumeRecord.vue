<template>
    <div class="row">
        <div class="col-md-9">
            <legend>Record de Consumo </legend>
            <div class="row">
                <div class="input-group input-group-sm col-md-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">De: </span>
                    </div>
                    <input type="text" v-model="first_date" class="form-control datepicker" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                </div>
                <div class="input-group input-group-sm col-md-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Hasta: </span>
                    </div>
                    <input type="text" v-model="second_date" class="form-control datepicker" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                </div>
                <button class="btn btn-secondary" @click="graphicRecord()" > <i class="fa fa-chart-bar"></i> </button>
                <!-- <input type="text" placeholder="yyy/mm/dd" class="form-control datepicker col-md-4">
                <input type="text" placeholder="yyy/mm/dd" class="form-control datepicker col-md-4"> -->
            </div>
            <div class="row">
                <canvas id="consume_record_canvas"></canvas>
            </div>
        </div>
        <div class="col-md-3">

        </div>
    </div>
</template>
<script>
var chartRecord;
var ctx;
export default {
    data:()=>({
        config_record:
        {
          type: null,
          data: null,
          options: null,
        },
        first_date: moment().format('L'),
        second_date:moment().format('L'),
    }),
    mounted(){
        //no usar por interfiere con el dom virtual que usa vue js
        // $('.datepicker').datepicker({
        //     format: "yyyy/mm/dd",
        //     language: "es",
        //     autoclose: true,
        // }).datepicker();
        this.graphicRecord();

    },
    methods:
    {
        graphicRecord() {
            // const = getElementB
            axios.post('consume_record', {
                    first_date: this.first_date,
                    second_date: this.second_date
                })
                .then((response) => {
                    console.log(response.data);

                    let labels = response.data.labels;
                    let quantities = response.data.record_quantity;
                    let costs = response.data.record_cost;
                    let datasets = [{
                            label: 'Cantidad',
                            data: quantities,
                            backgroundColor: [
                                "#1a75ff",
                            ],
                            hoverBackgroundColor: [
                                "#1a75ff",
                            ],
                            borderWidth: 1
                        },
                        {
                            label: 'Costo',
                            data: costs,
                            backgroundColor: [

                                "#33cc33",
                            ],
                            hoverBackgroundColor: [

                                "#33cc33"
                            ],
                            borderWidth: 1
                        }
                    ];

                    //nota en este punto se adiciona  a mas de un dataset como objeto
                    const ctx = document.getElementById("consume_record_canvas");
                    // console.log(ctx);
                    this.config_activo = {
                        type: 'bar',
                        data: {
                            labels,
                            datasets
                        }
                    }
                    chartRecord = new Chart(ctx, this.config_activo);
                });

        }
    }
}
</script>
