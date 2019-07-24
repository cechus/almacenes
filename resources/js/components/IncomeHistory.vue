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
export default {
    data:()=>({
        histories:[],
        type:'',
        title:''
    }),
    mounted()
    {
        console.log("iniciando componente de hisrtorial ");
         $('#historyModal').on('show.bs.modal',(event)=> {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var type = button.data('type') // Extract info from data-* attributes

            console.log(type);
            switch (type) {
                case "orden_de_compra":
                    this.title = "Historial de Orden de Compra";

                    break;
                case "numero_de_contrato":
                    this.title = "Historial de Numero de Contrato";
                    break;
                case "fondos_en_avance":
                    this.title = "Historial de Orden de Compra";
                    break;
                case "reembolso":
                    this.title = "Historial de Orden de Compra";
                    break;

            }
            // this.title ='Nueva Articulo ';
            // if(article)
            // {
            //     this.title='Editar '+article.name;

            //     axios.get(`article/${article.id}`).then(response=>{
            //             this.form = response.data.article;
            //             console.log(this.form);
            //     });

            //     // this.form = article;
            // }else
            // {
            //     this.form={};

            // }
            // console.log(article);

        })
    }
}
</script>
