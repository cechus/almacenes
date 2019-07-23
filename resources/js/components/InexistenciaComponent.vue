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
                    <button class="btn btn-secondary"> <i class="fa fa-print"></i> </button>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <iframe :src="url" class="col-md-12" frameborder="0"></iframe>
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
                if(response.data.finded == false)
                {
                    this.articles.push({name:this.article_search});
                    // this.article_search ="";
                    console.log("add XD");
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
    }
}
</script>
