<template>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                   Solicitud de Articulos {{storage.name}}
                </div>
                <div class="card-body">
                    <vue-bootstrap4-table :rows="rows" :columns="columns" :config="config">
                        <template slot="sort-asc-icon">
                            <i class="fa fa-sort-asc"></i>
                        </template>
                        <template slot="sort-desc-icon">
                            <i class="fa fa-sort-desc"></i>
                        </template>
                        <template slot="no-sort-icon">
                            <i class="fa fa-sort"></i>
                        </template>

                        <template slot="quantity" slot-scope="props">
                            <input class='form-control' v-model="props.row.quantity"  @keyup.enter="addIncome(props.row)" v-decimal>
                        </template>
                        <!-- <template slot="cost" slot-scope="props">
                            <input class='form-control' v-model="props.row.cost" >
                        </template> -->



                        <template slot="option" slot-scope="props">
                        <button class="btn btn-info" @click="addIncome(props.row)" title="agregar" :disabled="inIncomes(props.row)"><i class='fa fa-cart-plus'></i></button>
                        <!-- <button class="btn btn-info" @click="addIncome(props.row)"><i class='fa fa-cart-plus'></i></button> -->


                            <!-- <v-icon @click="getDetail(props)" data-toggle="modal" data-target="#taskModalDetail"
                                small>
                                remove_red_eye
                            </v-icon>
                            <v-icon @click="edit(props)" data-toggle="modal" data-target="#taskModalExecuted"
                                small>
                                edit
                            </v-icon> -->
                        </template>
                    </vue-bootstrap4-table>
                </div>
            </div>

        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <!-- <i class="fa fa-shopping-cart"></i> -->

                     Articulos Seleccionados
                      <small class="float-sm-right">
                           <button class="btn btn-success" data-toggle="modal" data-target="#registerModal" ><i class="fa fa-shopping-cart"></i> Solicitar  </button>
                           <button class="btn btn-default" ><i class="fa fa-ban"></i> Cancelar  </button>
                        </small>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Articulo</th>
                            <th scope="col">Unidad</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in incomes" :key="index">
                                <th scope="row">{{index+1}}</th>
                                <td>{{item.article.name}}</td>
                                <td>{{item.article.unit.name}}</td>
                                <td style="text-align:right">{{item.quantity}}</td>
                                <td><i class="fa fa-trash text-danger pointer" @click="deleteIncome(index)"></i> </td>
                            </tr>
                            <tr >
                                <td colspan="3" class="text-right " > <strong>TOTAL:</strong> </td>
                                <td>{{getTotalQuantity}}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form enctype="multipart/form-data" id='formCategory' method="post" :action="url" @submit.prevent="validateBeforeSubmit">
                    <div v-html='csrf'></div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Registro de Solicitud</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <legend>Datos de Ingreso</legend> -->

                        <!-- <div class="row">
                            <div class="form-group  col-md-8">
                                <label for="tipo">Solicitante:</label>
                                    <input type="text" name="person" class="form-control" :value="person" disabled >
                                <div class="invalid-feedback">{{ errors.first("tipo") }}</div>
                            </div>
                        </div> -->
                         <!-- <input type="text" name="article_request_id" :value="request.id " hidden> -->
                        <input type="text" name="articles" :value="JSON.stringify(incomes)" hidden>
                        <h5>Detalle de Solicitud</h5>

                        <div class="row">
                            <table class="table  table-bordered">
                                <thead>
                                    <tr class="bg-gray">
                                        <th scope="col">#</th>
                                        <th scope="col">Articulo</th>
                                        <th scope="col">Unidad</th>
                                        <th scope="col">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <tr v-for="(item,index) in incomes" :key="index">
                                        <th scope="row">{{index+1}}</th>
                                        <td>{{item.article.name}}</td>
                                        <td>{{item.article.unit.name}}</td>
                                        <td>{{item.quantity}}</td>
                                    </tr>
                                    <tr >
                                        <td colspan="3" class="text-right bg-gray" > <strong>TOTAL:</strong> </td>
                                        <td>{{getTotalQuantity}}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info"  @click="vistaprevia()" :disabled="! this.incomes.length > 0 ">Vista Previa</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" :disabled="! this.incomes.length > 0">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
        </div>

        <div class="modal fade" id="modalPdf" tabindex="-1" role="dialog" aria-labelledby="modalPdfTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPdfTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe src="" width="100%" height="600px" frameborder="0" allowtransparency="true"></iframe>
                </div>
                </div>
            </div>
        </div>

    </div> <!-- end row -->
</template>
<script>
import { parseMoney } from '../helper'
import VueBootstrap4Table from 'vue-bootstrap4-table';
export default {
    props:['articles','url','csrf','storage', 'request', 'gerencia'],
    data: ()=>({
        form:{},
        title:'',
        rows: [],
        incomes: [],
        types:[{name: 'Ingreso'},{name:'Traspaso'},{name:'Reingreso'}],
        columns: [

            {
                label: "Nombre",
                name: "name",
                filter: {
                    type: "simple",
                    placeholder: "nombre"
                },
                sort: true,
            },
            {
                label: "Categoria",
                name: "category.name",
                filter: {
                    type: "simple",
                    placeholder: "categoria"
                },
                sort: true,
            },
            {
                label: "Unidad",
                name: "unit.name",
                filter: {
                    type: "simple",
                    placeholder: "unidad"
                },
                sort: true,
            },

            {
                label: "Cantidad",
                name: "quantity",
            },

            {
                label: "Opcion",
                name: "option",
                sort: false,
            }
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
        hasFile: false,

    }),
    mounted() {
        this.rows = this.articles;
        console.log(this.articles);
        console.log('persona',this.gerencia);
    },
    methods: {
        inIncomes(item){
            return this.incomes.map(x => x.article).map(x => x.article_id).includes(item.article_id);
        },
        addIncome(item){
            if(this.inIncomes(item)){
                toastr.error("item ya agregado a la solicitud");
                return;
            }
            // console.log('articulossss',item.quantity);
            // console.log('stock', this.articles);
            //console.log('art', item.article_id);
             let art= item.article_id;
             let cant= item.quantity;
             let cantstock = 0;

            //return cost;
              if(parseMoney(item.quantity)>0)
              {
                // alert('es mayor a cero');
                // this.incomes.push({article:item,quantity:item.quantity,cost:item.cost});
                // item.quantity = '';
                // item.cost ='';

                this.articles.forEach(stock => {
                 //console.log('art',stock.article_id);
                 // console.log('cantst',stock.quantity_stock);
                     if(art==stock.article_id)
                     {
                         cantstock = stock.quantity_stock;
                        //console.log('cantidad',cantstock);
                     }
                     // if(art==stock.article_id)
                     // {
                     //    if(item.quantity<parseInt(cantstock))
                     //    {
                     //        this.incomes.push({article:item,quantity:item.quantity,cost:item.cost});
                     //        item.quantity = '';
                     //    }
                     //    else
                     //    {
                     //        alert('Supera el stock el articulo:',stock.name);
                     //    }
                     // }
                });


                //this.articles.forEach(stock => {
                    console.log('cantidad',cantstock);
                    console.log(item.quantity);
                    if(parseMoney(item.quantity)<=parseInt(cantstock))
                    {
                         this.incomes.push({article:item,quantity:item.quantity,cost:item.cost});
                         item.quantity = '';
                        // this.articles.forEach(stock => {
                        //     if(stock.article_id=stock.article_id)
                        //     {
                        //         console.log('cantiiiii',stock.quantity_stock);
                        //        this.incomes.push({article:stock,quantity:item.quantity,cost:item.cost});
                        //   //  item.quantity = '';
                        //     }

                        // });
                    }
                    else
                    {
                        Swal.fire(
                            'Supero el Stock !',
                            '',
                            'warning'
                        )
                    }
              //  });
             }
             else
             {
                 Swal.fire(
                    'La cantidad y costo unitario debe ser mayor a 0 !',
                    '',
                    'warning'
                )
                // alert('La cantidad, no debe ser vacio y debe ser mayor a 0!!!');
              }
        },
        deleteIncome(index){

            this.incomes.splice(index,1);
        },
        onFileChange(e) {
            var files = e.target.files || e.dataTransfer.files;
            !files.length?this.hasFile=false:this.hasFile = true;
            // console.log(this.hasFile);
        },
        validateBeforeSubmit() {
            this.$validator.validateAll().then((result) => {
                if (result && this.incomes.length > 0) {
                let form = document.getElementById("formCategory");

                    form.submit();
                    return;
                }
                toastr.error('Debe completar la informacion correctamente')
            });
        },
         vistaprevia(){
            if(!this.incomes.length > 0 ){
                toastr.error('Debe completar la informacion correctamente')
                return;
            }
             console.log('ingreso de datos salida',this.incomes);
             let parameters = this.incomes;

            let url='/reporte_vista_RequestNote?funcionario='+encodeURIComponent(this.request.prs_nombres)+'&gerencia='+encodeURIComponent(this.gerencia)+'&solicitud='+encodeURIComponent(JSON.stringify(this.incomes));

              console.log('del url',url);
            $('#modalPdf .modal-body iframe').attr('src', url);
            $('#modalPdf').modal('show');
        },
    },
    computed:{

        getTotalQuantity(){
            let quantity= 0;
            this.incomes.forEach(item => {
                // this.quantity += parseInt(item.quantity)
                quantity += parseMoney(item.quantity)
                // console.log(item.quantity);
            });
            return quantity;
        },
    },
    components: {
        VueBootstrap4Table
    }
}
</script>
