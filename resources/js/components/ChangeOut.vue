<template>
    <div class="row">
        <div class="col-md-10">

            <div class="card">
                <form id='formChange' method="post" :action="url" @submit.prevent="validateBeforeSubmit">
                    <div v-html='csrf'></div>
                <div class="card-body">
                    <h5 class="card-title">Solicitud de Cambio en Salida</h5>
                    <div class="row">
                        <div class="form-group  col-md-3">
                            <input type="text" name="article_request_id" :value="requestout.id" hidden>
                            <input type="text" name="type" v-if="form.type" :value="form.type.name" hidden>
                            <label for="tipo">Tipo</label>
                            <multiselect
                                v-model="form.type"
                                :options="types"
                                id="tipo"
                                placeholder="Seleccionar Tipo"
                                select-label="Seleccionar"
                                deselect-label="Remover"
                                selected-label="Seleccionado"
                                label="name"
                                track-by="name"
                                @input="mostrar()"
                                >
                            </multiselect>
                            <!-- <div v-if="sw"> -->
                             <!--  <label><input type="checkbox" id="articulo" v-model="articulo"> Articulo</label>
                              <label><input type="checkbox" id="cantidad" v-model="cantidad" @input="mostrarcant()"> Cantidad</label>
                              <br> -->
                            <!-- </div> -->
                            <div class="invalid-feedback">{{ errors.first("type") }}</div>
                        </div>
                        <div class="form-group  col-md-3" v-if="sw">
                            <input type="text" name="change" v-if="form.changes" :value="form.changes.name" hidden>
                            <br>
                            <multiselect
                                v-model="form.changes"
                                :options="changes"
                                placeholder="Seleccionar una opcion"
                                select-label="Seleccionar"
                                deselect-label="Remover"
                                selected-label="Seleccionado"
                                label="name"
                                track-by="name"
                                @input="change()"
                                >
                            </multiselect>
                            <div class="invalid-feedback">{{ errors.first("type") }}</div>
                        </div>
                        <div class="form-group  col-md-3" v-if="button"><br>
                             <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#newItemModal">Adicionar Item</button>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="lbobservation">Descripcion</label>
                            <input type="text" class="form-control" id="observation" name="observation" v-model="form.observation" placeholder="Detalle el motivo de la Solicitud" v-validate="'required'">
                            <div class="invalid-feedback">{{ errors.first("observation") }}</div>
                        </div>
                        <input type="text" name="request_out_items" :value="JSON.stringify(items)" hidden>
                        <div class="col-md-6">
                            Numero de Salida: {{requestout.correlative}} <br>
                            Fecha de Salida: {{requestout.created_at}} <br>
                            <!-- <button type="button" class="btn btn-secondary"  data-toggle="modal" data-target="#newItemModal">Adicionar Item</button> -->
                        </div>
                        <br>
                        <div class="col-md-12">
                                <table class="table">
                                     <thead>
                                       <!--  <th></th> -->
                                        <th scope="col">Nro</th>
                                        <th scope="col">Articulos</th>
                                        <th scope="col">Unidad</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col"  v-if="articulo">Nuevo Articulo </th>
                                        <th scope="col" v-if="cantidad">Nueva Cantidad</th>

                                     </thead>
                                    <tbody>
                                        <tr v-for="(item,index) in items" :key="index" >
                                            <!-- <td><input type="checkbox" id="itemart" v-model="item.itemart" @input="mostraritem()"></td> -->
                                            <td>{{index+1}}</td>
                                            <td>{{item.article.name}}</td>
                                            <td>{{item.article.unit.name}}</td>
                                            <td>{{item.quantity}}</td>
                                            <td v-if="articulo">
                                                <multiselect
                                                    v-model="item.new_article"
                                                    :options="articlesFilter(item)"

                                                    placeholder="Seleccionar Articulo"
                                                    select-label="Seleccionar"
                                                    deselect-label="Remover"
                                                    selected-label="Seleccionado"
                                                    label="name"
                                                    track-by="name"
                                                    >
                                                </multiselect>
                                            </td>
                                            <td v-if="cantidad">
                                                <input type="text" class="form-control" value="0" v-model="item.new_quantity" v-decimal  :disabled="isDeleted()">
                                            </td>
                                            <!-- <td v-if="itemart">
                                                <input type="text" class="form-control" value="0" v-model="row.new_quantity" :disabled="isDeleted()">
                                            </td>
 -->
                                        </tr>
                                    </tbody>
                                </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger">Cancelar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- aqui los modals -->
        <div class="modal fade" id="newItemModal" tabindex="-1" role="dialog" aria-labelledby="newItemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newItemModalLabel">Adicionando Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                              <div class="form-group  col-md-12">
                                <input type="text" name="provider_id" v-if="item.article" :value="item.article.id" hidden>
                                <label for="Articulo">Articulo</label>
                                <multiselect
                                    v-model="item.article"
                                    :options="articlesNotInItems"
                                    id="Articulo"
                                    placeholder="Seleccionar Articulo"
                                    select-label="Seleccionar"
                                    deselect-label="Remover"
                                    selected-label="Seleccionado"
                                    label="name"
                                    track-by="name" >
                                </multiselect>
                                <div class="invalid-feedback">{{ errors.first("Articulo") }}</div>
                            </div>
                            <div class="form-group  col-md-12" >
                                <label for="quantity">Cantidad</label>
                                    <input type="text" class="form-control" v-decimal v-model="item.new_quantity">
                                <div class="invalid-feedback">{{ errors.first("quantity") }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="addItem()">Adicionar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { constants } from 'crypto';
export default {
     props:['url','csrf','requestout','stocks'],
    data:()=>({
        form:{},
        changes:[{id:1,name:'Articulo'},{id:2,name:'Cantidad'},{id:3,name:'Adicionar Item'}],
        types:[{id:1,name:'Eliminacion'},{id:2,name:'Modificacion'}],
        items:[],
        item:{},
        articles:[],
        checkedNames:[],
        sw:false,
        articulo:false,
        cantidad:false,
        button:false,
        itemart:false,
    }),
    mounted() {
        // console.log(this.income);
        console.log(this.requestout);
        this.items = this.requestout.article_request_items;
        this.stocks.forEach(item => {
            this.articles.push(item.article);
        });
        console.log(this.items);
        // this.articles = JSON.parse(this.stocks);
        console.log(this.stocks);

        // axios.get('../list_units')
        //      .then(response => {
        //          this.units = response.data;
        //          console.log(response.data)
        //      });

    },

    computed:{
        articlesNotInItems() {
            let items = this.items.map(i => i.article_id);
            return this.articles.filter(a =>{
                return ! items.includes(a.id)
            })
        }
    },
    methods:{
        articlesFilter(item) {
            let items = this.items.map(i => i.article_id);
            return this.articles.filter(a => {
                return a.id != item.article_id && ! items.includes(a.id)
            })
        },
        isValid() {
            if (this.cantidad){
                return ! this.items.some(i => {
                    let stock = this.stocks.find(s => i.article_id == s.article_id )
                    return parseFloat(stock.quantity) < parseFloat(i.new_quantity)
                })
            }
        },
        validateBeforeSubmit() {
            this.$validator.validateAll().then((result) => {
                if (result && this.isValid() ) {
                let form = document.getElementById("formChange");
                    console.log(form);
                    form.submit();
                    return;
                }
                toastr.error('Debe completar la informacion correctamente')
            });
        },
        addItem(){
            // console.log(this.income);

            this.item.article_request_id = this.requestout.id;
            this.item.article_id = this.item.article.id;
            this.item.created_at = this.requestout.created_at;
            this.item.deleted_at = this.requestout.deleted_at;
            this.item.updated_at = this.requestout.updated_at;
            this.item.quantity= this.item.new_quantity;
            this.item.id = 0;
            console.log(this.item);
            let stock = this.stocks.find(s => s.article_id == this.item.article_id);
            if (parseFloat(stock.quantity) >= parseFloat(this.item.quantity) && !this.items.map(i => i.article_id).includes(this.item.article_id) ) {
                this.items.push(this.item);
            }
            console.log('aqui deberia adicionar el item');
        },
            mostrar() {
               // var tipo = document.getElementById('types').value;
               console.log('tipooo',this.form.type.id);
               let tipo = this.form.type.id;
                 if(tipo==2){
                  this.sw=true;
                  }else
                  {
                    this.sw=false;
                    // item.new_cost = 0;
                    // item.new_quantity = 0;
                  }
            },
             mostraritem() {
               console.log('tipooo',this.itemart);
               let most = this.itemart;
                 if(most==false){
                  this.itemart=true;
                  this.cantidad=false
                  }else
                  {
                    this.itemart=false;
                  }
            },
             mostrarcost() {
               // var tipo = document.getElementById('types').value;
               console.log('cost',this.costo);
               let mostcost = this.costo;
               if(mostcost==false){
                 this.items.forEach(item => {
                        item.new_quantity =0
                        item.new_cost =0
                        return item;
                    });
               }
            },

            change() {
               // var tipo = document.getElementById('types').value;
               if (!this.form.changes) {
                   return ;
               }
               this.items = this.items.filter(i => i.id != 0);
               console.log('modddd',this.form.changes.id);
               let combo = this.form.changes.id;
               // console.log('ESTE ES ITEM',this.articles);
                 if(combo==1){
                  this.articulo=true;
                  this.cantidad=false;
                  this.button=false;

                  console.log('art',this.articulo);
                  let mostart = this.cantidad;
                  console.log('artttq??',mostart)
                       if(mostart==false){
                         this.items.forEach(item => {
                            // this.id=null;
                                item.new_quantity =0
                               // item.new_cost =0
                                // item.arti=null
                                // console.log()
                                return item;
                            });
                       }
                  }if(combo==2)
                  {
                    this.articulo=false;
                    this.button=false;
                    this.cantidad=true;
                    console.log('cant',this.cantidad);
                    let mostcant = this.articulo;
                       if(mostcant==false){
                         this.items.forEach(item => {
                                item.new_quantity =0
                               // item.new_cost =0
                                return item;
                            });
                       }
                  }if(combo==3)
                  {
                    this.cantidad=false;
                    this.articulo=false;
                    this.button=true;
                    console.log('cant',this.cantidad);
                    let mostcant = this.costo;
                       if(mostcant==false){
                         this.items.forEach(item => {
                                item.new_quantity =0
                                //item.new_cost =0
                                return item;
                            });
                       }
                  }
            },
         mostrarcant() {
               // var tipo = document.getElementById('types').value;
               console.log('cant',this.cantidad);
               let mostcant = this.cantidad;
               if(mostcant==false){
                 this.items.forEach(item => {
                        item.new_quantity =0
                        item.new_cost =0
                        return item;
                    });
               }
            },
        isDeleted()
        {
            let deleted = false;
            // console.log(this.form.type);
            if(this.form.type)
            {
                if(this.form.type.name=="Eliminacion"){
                    deleted = true;
                    this.items.forEach(item => {
                        item.new_quantity =0
                        return item;
                    });
                }else
                {
                        // this.items.forEach(item => {
                        //     item.new_cost = item.cost;
                        //     return item;
                        // });
                }
            }
            return deleted;
        }

    },
}
</script>
