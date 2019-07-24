<template>

    <!-- Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">{{title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <vue-bootstrap4-table :rows="histories" :columns="columns" :config="config">
                        <template slot="sort-asc-icon">
                            <i class="fa fa-sort-asc"></i>
                        </template>
                        <template slot="sort-desc-icon">
                            <i class="fa fa-sort-desc"></i>
                        </template>
                        <template slot="no-sort-icon">
                            <i class="fa fa-sort"></i>
                        </template>

                    </vue-bootstrap4-table>

                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> -->
            </div>
        </div>
    </div>
</template>
<script>
import VueBootstrap4Table from 'vue-bootstrap4-table';
export default {
    data:()=>({
        histories:[],
        type:'',
        title:'',
        rows: [],
        columns: [
            {
                label: "Fecha",
                name: "created_at",
                sort: true,
            },
            {
                label: "Proveedor",
                name: "provider.first_name",
                sort: true,
            },
            {
                label: "Numero",
                name: "number",
                sort: true,
            },
            {
                label: "Fecha de Ingreso",
                name: "date",
                sort: true,
            },

        ],
        config: {
			card_mode: false,
			checkbox_rows: false,
			rows_selectable: false,
			global_search:  {
					placeholder:  "Enter custom Search text",
					visibility: false,
					case_sensitive:  false
			},
			show_refresh_button:  false,
			show_reset_button:  false,
        },
    }),
    mounted()
    {
        console.log("iniciando componente de hisrtorial ");
         $('#historyModal').on('show.bs.modal',(event)=> {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var type = button.data('type') // Extract info from data-* attributes

            console.log(type);
            switch (type) {
                case "Orden de Compra":
                    this.title = "Historial de Orden de Compra";
                    this.search(type);
                    break;
                case "Numero de Contrato":
                    this.title = "Historial de Numero de Contrato";
                    this.search(type);
                    break;
                case "Fondos en Avance":
                    this.title = "Historial de Fondos en Avance";
                    this.search(type);
                    break;
                case "Reembolso":
                    this.title = "Historial de Reembolso";
                    this.search(type);
                    break;
            }

        })


    },
    methods:{
        search(type){
             axios.post(`income_history`,{type:type}).then(response=>{
                 console.log(response.data);
                 this.histories = response.data;
                    // this.form = response.data;
                    //console.log(this.form);
            });
        }
    }
}
</script>
