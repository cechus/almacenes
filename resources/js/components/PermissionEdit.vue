<template>
   <div >
		<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form id='formProvider' method="post" :action="url" >

                    <div class="modal-content">
                        <div v-html='csrf'></div>
						<input type="text" name="id" :value="form.id" v-if="form.id" hidden>
                        <div class="modal-header laravel-modal-bg">
                            <h5 class="modal-title" >{{title}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
							<!-- <input type="text" name="action_short_term_id" v-model="action_short_term.id" hidden> -->
                            <!-- <legend>Datos de Menu</legend> -->
							<div class="row">
                                <div class="form-group col-md-8">
                                    <label for="lbname">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" v-model="form.name" placeholder="Nombre del acceso" v-validate="'required'">
                                    <div class="invalid-feedback">{{ errors.first("name") }}</div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="lbphone">Icono</label>  <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank"> <i class="fa fa-question-circle"></i> </a>
                                    <input type="text" class="form-control" id="icon" name="icon" v-model="form.icon" placeholder="example:fa fa-edit" v-validate="'required'">
                                    <div class="invalid-feedback">{{ errors.first("icon") }}</div>
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="lbaddress">Ruta Laravel</label>
                                    <input type="text" class="form-control" id="route" name="route" v-model="form.route" placeholder="example: users" v-validate="'required'">
                                    <div class="invalid-feedback">{{ errors.first("route") }}</div>
                                </div>
                                <div class="form-group  col-md-4">
                                    <input type="text" name="type" v-if="form.type" :value="form.type.name" hidden>
                                    <label for="Articulo">Tipo</label>
                                    <multiselect
                                        v-model="form.type"
                                        :options="types"
                                        id="Tipo"
                                        placeholder="Seleccionar Tipo"
                                        select-label="Seleccionar"
                                        deselect-label="Remover"
                                        selected-label="Seleccionado"
                                        label="name"
                                        track-by="name" >
                                    </multiselect>
                                    <div class="invalid-feedback">{{ errors.first("Tipo") }}</div>
                                </div>
                                <div v-if="form.type">

                                    <div class="form-group  col-md-9" v-if="form.type.name=='SubMenu'" >
                                        <input type="text" name="menu_id" v-if="form.menu" :value="form.menu.id" hidden>
                                        <label for="Articulo">Menu</label>
                                        <multiselect
                                            v-model="form.menu"
                                            :options="menus"
                                            id="menu"
                                            placeholder="Seleccionar Menu"
                                            select-label="Seleccionar"
                                            deselect-label="Remover"
                                            selected-label="Seleccionado"
                                            label="label"
                                            track-by="label" >
                                        </multiselect>
                                        <div class="invalid-feedback">{{ errors.first("Menu") }}</div>
                                    </div>
                                </div>
							</div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" >Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>
<script>
export default {
    props:['url','csrf'],
    data:()=>({
        form:{},
        title:'',
        types:[{name:"Menu"},{name:"SubMenu"},{name:"Link"}],
        menus:[]
    }),
    mounted() {
        console.log('Componente permission  iniciado')


        $('#permissionModal').on('show.bs.modal',(event)=> {
            var button = $(event.relatedTarget) // Button that triggered the modal
            let permission = button.data('json') // Extract info from data-* attributes
            // console.log(permission);
            this.title ='Nuevo Acceso ';
            if(permission)
            {
                this.title='Editar '+permission.name;

                axios.get(`get_permission/${permission.id}`).then(response=>{
                        this.form = response.data.permission;
                        console.log(response.data);
                });

                // this.form = permission;
            }else
            {
                this.form={};

            }
            axios.get(`get_menus`).then(response=>
            {
                    this.menus = response.data;
                    console.log(response.data);
            });
            console.log(permission);
        })
	},
    methods:{
        validateBeforeSubmitx() {
            this.$validator.validateAll().then((result) => {
                if (result) {
                let form = document.getElementById("permissionModal");

                    form.submit();
                    return;
                }
                toastr.error('Debe completar la informacion correctamente')
            });
        },

    },


}
</script>
