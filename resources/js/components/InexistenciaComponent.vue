<template>
    <div class="row">
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-8">
                    <!-- <label for="">Articulo</label> -->
                    <input type="text" class="form-control" @keyup.enter="search()" v-model="article_search" >
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary" @click="search()"> <i class="fa fa-search"></i>  Buscar</button>
                </div>
            </div>
            <br>
            <div class="row" >
                <div class="col-md-8">

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(article,index) in articles" :key="index">{{article.name}}  <i class="fa fa-trash text-danger" @click="deleteItem()"></i> </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <!-- <button class="btn btn-secondary"> <i class="fa fa-file-import"></i> </button> -->
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <iframe :src="getUrl" class="col-md-12" frameborder="0" height="400px"></iframe>
        </div>
    </div>
</template>
<script>
export default {
    props:['url'],
    data:()=>({
        articles:[],
        article_search:""
    }),
    mounted(){
        console.log(this.url);

    },
    methods:
    {
        search()
        {
            axios.post('article_search', {
                article_name: this.article_search,

            })
            .then( (response)=> {

                console.log(response.data.finded);
                if(this.article_search != "")
                {
                    if(response.data.finded == false)
                    {
                        this.articles.push({name: this.article_search.trim() });
                        this.article_search ="";
                        // console.log("add XD");
                    }else{
                        toastr.info("el producto "+this.article_search+" ha sido registrado con anterioridad", 'Alerta' );
                    }
                }else{
                    toastr.info("Debe escribir un producto a buscar", 'Alerta' );
                }
                // if(!response.data.finded){
                //     this.articles.push({name:this.article_search});
                //     this.article_search ="";
                // }
            })
            .catch(function (error) {
                console.log(error);
            });


        },
        deleteItem(index)
        {
            this.articles.splice(index,1);
        },
    },
    computed:{
        getUrl(){
            let url = this.url+'/article_inexistencia_report?articles='+encodeURIComponent(JSON.stringify(this.articles))
            return url;
        }
    }
}
</script>
