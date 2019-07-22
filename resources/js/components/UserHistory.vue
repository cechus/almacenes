<template>
    <div class="row">
        <div class="col-md-12">

            <table id="historyTable" class="table  table-bordered" >
                <thead>
                    <tr class="bg-gray">
                        <th scope="col">#</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Articulo</th>
                        <th scope="col">Unidad</th>
                        <!-- <th scope="col" >Costo Unitario</th> -->
                        <th scope="col">Cantidad</th>
                        <!-- <th scope="col">Cantidad Aprob.</th> -->
                    </tr>
                </thead>
                <tbody>
                        <tr v-for="(item,index) in histories" :key="index">
                        <th scope="row">{{index+1}}</th>
                        <th scope="row">{{item.created_at}}</th>
                        <td>{{item.article.name}}</td>
                        <td>{{item.article.unit.name}}</td>
                        <td>{{item.quantity}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>
<script>
export default {
    props:['histories'],
    mounted(){
          $('#historyTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 10002, targets: 3 },
                    { responsivePriority: 3, targets: -1 }
                ],
                searching: false,
                language: spanish_lang
            });

    }
}
</script>
