@extends('layouts.app')
@section('title')
    {{-- Proveedores --}}
@endsection
@section('breadcrums')
    {{-- {{ Breadcrumbs::render('action_medium_term') }} --}}
@endsection
@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header card-calendar">

                    <h4 class="card-title ">
                        {{$title??'Solicitudes de Modificiaciones'}}
                        <small class="float-sm-right">
                            {{-- <a href="{{url('amp_report_excel')}}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> </a>  --}}
                            {{-- <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#StorageModal" data-json="null" > Nuevo  <i class="fa fa-plus-circle"></i> </button> --}}
                        </small>
                    </h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-in"
                                role="tab" aria-controls="pills-in" aria-selected="true">Entradas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-out-tab" data-toggle="pill" href="#pills-out"
                                role="tab" aria-controls="pills-out" aria-selected="false">Salidas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-all-in-tab" data-toggle="pill" href="#pills-all-in"
                                role="tab" aria-controls="pills-all-in" aria-selected="false">Todas las Entradas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-all-out-tab" data-toggle="pill" href="#pills-all-out"
                                role="tab" aria-controls="pills-all-out" aria-selected="false">Todas las Salidas</a>
                        </li>

                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-in" role="tabpanel" aria-labelledby="pills-in-tab">
                            <table id="lista" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Nro Nota</th>
                                        <th>Tipo</th>
                                        <th>Descripcion</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($request_incomes as $index=> $item)
                                        <tr>
                                            <td>{{($index+1)}}</td>
                                            <td>{{$item->article_income->correlative}}</td>
                                            <td>{{$item->type}}</td>
                                            <td>{{$item->description}}</td>
                                            <td>
                                                    @switch($item->state)
                                                        @case('Aprobado')
                                                            <span class="badge badge-primary">{{$item->state}}</span>
                                                            @break
                                                        @case('Pendiente')
                                                            <span class="badge badge-warning">Pendiente Aprobacion</span>
                                                            @break
                                                        @case('Pendiente1')
                                                            <span class="badge badge-warning">Pendiente Aprobacion</span>
                                                            @break
                                                        @case('Pendiente2')
                                                            <span class="badge badge-warning">Pendiente Aprobacion</span>
                                                            @break
                                                        @case('Rechazado')
                                                            <span class="badge badge-danger">{{$item->state}}</span>
                                                            @break
                                                        @case('Pendiente Aprobacion')
                                                            <span class="badge badge-warning">{{$item->state}}</span>
                                                            @break
                                                    @endswitch
                                            </td>

                                            <td>
                                                @switch($item->state)
                                                        @case('Aprobado')
                                                            {{-- <span class="badge badge-primary">{{$item->state}}</span> --}}
                                                            @break
                                                        @case('Pendiente')
                                                             <a href="#" data-toggle="modal" data-target="#RequestChangeIncomeModal" data-json="{{$item}}" data-edited="true"><i class="fas fa-clipboard-check fa-lg"></i></a>
                                                            @break
                                                        @case('Pendiente1')
                                                             <a href="#" data-toggle="modal" data-target="#RequestChangeIncomeModal" data-json="{{$item}}" data-edited="true"><i class="fas fa-clipboard-check fa-lg"></i></a>
                                                            @break
                                                        @case('Pendiente2')
                                                             <a href="#" data-toggle="modal" data-target="#RequestChangeIncomeModal" data-json="{{$item}}" data-edited="true"><i class="fas fa-clipboard-check fa-lg"></i></a>
                                                            @break
                                                        @case('Rechazado')
                                                            <span class="badge badge-danger">{{$item->state}}</span>
                                                            @break
                                                        @case('Pendiente Aprobacion')
                                                             <a href="#" data-toggle="modal" data-target="#RequestChangeIncomeModal" data-json="{{$item}}" data-edited="true"><i class="fas fa-clipboard-check fa-lg"></i> </a>
                                                            @break
                                                    @endswitch

                                                  {{-- 0.00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000  CODIGO DE ISABEL NO TOCAR--}}
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                        <div class="tab-pane fade" id="pills-out" role="tabpanel" aria-labelledby="pills-out-tab">
                                <table id="lista_out" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Nro</th>
                                            <th>Nro Nota</th>
                                            <th>Tipo</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($request_outs as $index=> $item)
                                            <tr>
                                                <td>{{($index+1)}}</td>
                                                <td>{{$item->article_request->correlative}}</td>
                                                <td>{{$item->type}}</td>
                                                <td>{{$item->description}}</td>
                                                <td>
                                                        @switch($item->state)
                                                            @case('Aprobado')
                                                                <span class="badge badge-primary">{{$item->state}}</span>
                                                                @break
                                                            @case('Pendiente')
                                                                <span class="badge badge-info">{{$item->state}}</span>
                                                                @break
                                                            @case('Rechazado')
                                                                <span class="badge badge-danger">{{$item->state}}</span>
                                                                @break
                                                            @case('Pendiente Aprobacion')
                                                                <span class="badge badge-warning">Pendiente Aprobacion</span>
                                                                @break
                                                        @endswitch
                                                </td>

                                                <td>
                                                    @switch($item->state)
                                                        @case('Aprobado')
                                                            {{-- <span class="badge badge-primary">{{$item->state}}</span> --}}
                                                            @break
                                                        @case('Pendiente')
                                                             <a href="#" data-toggle="modal" data-target="#RequestChangeOutModal" data-json="{{$item}}" data-edited="true"><i class="fas fa-clipboard-check fa-lg"></i></a>
                                                            @break
                                                        @case('Rechazado')
                                                            <span class="badge badge-danger">{{$item->state}}</span>
                                                            @break
                                                        @case('Pendiente Aprobacion')
                                                             <a href="#" data-toggle="modal" data-target="#RequestChangeOutModal" data-json="{{$item}}" data-edited="true"><i class="fas fa-clipboard-check fa-lg"></i></a>
                                                            @break
                                                    @endswitch
                                                    <!--    <a href="#" data-toggle="modal" data-target="#RequestChangeOutModal" data-json="{{$item}}" data-edited="true"><i class="material-icons text-primary">remove_red_eye</i></a>-->
                                                        {{-- 0.00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000  CODIGO DE ISABEL NO TOCAR--}}
                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>
                        </div>
                        <div class="tab-pane fade" id="pills-all-in" role="tabpanel" aria-labelledby="pills-all-in-tab">
                                <table id="lista_all_in" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Nro Nota</th>
                                                <th>Tipo</th>
                                                <th>Descripcion</th>
                                                <th>Estado</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($request_all_in as $index=> $item)
                                                <tr>
                                                    <td>{{($index+1)}}</td>
                                                    <td>{{$item->article_income->correlative}}</td>
                                                    <td>{{$item->type}}</td>
                                                    <td>{{$item->description}}</td>
                                                    <td>
                                                            @switch($item->state)
                                                                @case('Aprobado')
                                                                    <span class="badge badge-primary">{{$item->state}}</span>
                                                                    @break
                                                                @case('Pendiente')
                                                                    <span class="badge badge-info">{{$item->state}}</span>
                                                                    @break
                                                                @case('Rechazado')
                                                                    <span class="badge badge-danger">{{$item->state}}</span>
                                                                    @break
                                                                @case('Pendiente Aprobacion')
                                                                    <span class="badge badge-warning">{{$item->state}}</span>
                                                                    @break
                                                            @endswitch
                                                    </td>

                                                    <td>
                                                            <a href="#" data-toggle="modal" data-target="#RequestChangeIncomeModal" data-json="{{$item}}" data-edited='false'><i class="material-icons text-primary">remove_red_eye</i></a>
                                                          {{-- 0.00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000  CODIGO DE ISABEL NO TOCAR--}}
                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                 </table>
                        </div>
                        <div class="tab-pane fade" id="pills-all-out" role="tabpanel" aria-labelledby="pills-all-out-tab">
                                <table id="lista_all_out" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nro</th>
                                                <th>Nro Nota</th>
                                                <th>Tipo</th>
                                                <th>Descripcion</th>
                                                <th>Estado</th>
                                                <th>Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($request_all_out as $index=> $item)
                                                <tr>
                                                    <td>{{($index+1)}}</td>
                                                    <td>{{$item->article_request->correlative}}</td>
                                                    <td>{{$item->type}}</td>
                                                    <td>{{$item->description}}</td>
                                                    <td>
                                                            @switch($item->state)
                                                                @case('Aprobado')
                                                                    <span class="badge badge-primary">{{$item->state}}</span>
                                                                    @break
                                                                @case('Pendiente')
                                                                    <span class="badge badge-info">{{$item->state}}</span>
                                                                    @break
                                                                @case('Rechazado')
                                                                    <span class="badge badge-danger">{{$item->state}}</span>
                                                                    @break
                                                                @case('Pendiente Aprobacion')
                                                                    <span class="badge badge-warning">{{$item->state}}</span>
                                                                    @break
                                                            @endswitch
                                                    </td>

                                                    <td>
                                                            <a href="#" data-toggle="modal" data-target="#RequestChangeOutModal" data-json="{{$item}}" data-edited='false'><i class="material-icons text-primary">remove_red_eye</i></a>
                                                          {{-- 0.00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000  CODIGO DE ISABEL NO TOCAR--}}
                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                 </table>
                        </div>

                    </div>

                            {{-- <div id='calendar'></div> --}}
                </div>
            </div>
        </div>

        {{-- aqui los modals --}}
        <change-income-edit url='{{url('income_first_confirmation')}}' csrf='{!! csrf_field('POST') !!}'></change-income-edit>

        <change-out-edit url='{{url('confirm_out')}}' csrf='{!! csrf_field('POST') !!}'> </change-out-edit>

    </div>

@endsection
<script>

    @section('script')

        //  $('#lista').DataTable({
        //     responsive: true,
        //     columnDefs: [
        //         { responsivePriority: 1, targets: 0 },
        //         { responsivePriority: 10002, targets: 2 },
        //         { responsivePriority: 2, targets: -1 }
        //     ],
        //     language: spanish_lang
        // });

        $('#lista_out').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 10002, targets: 2 },
                { responsivePriority: 2, targets: -1 }
            ],
            language: spanish_lang
        });

        $('#lista_all_in').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 10002, targets: 2 },
                { responsivePriority: 2, targets: -1 }
            ],
            language: spanish_lang
        });

        $('#lista_all_out').DataTable({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 10002, targets: 2 },
                { responsivePriority: 2, targets: -1 }
            ],
            language: spanish_lang
        });


        var classname = document.getElementsByClassName("deleted");
        // console.log(classname);
        function deleteItem(){

            var data = JSON.parse(this.getAttribute("data-json"));

            Swal.fire({
            title: 'Esta Seguro de Eliminar '+data.name+'?',
            text: "una vez eliminado no se podra revertir la accion!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, borrar!',
            cancelButtonText: 'No'
            }).then((result) => {
            if (result.value) {

                axios.delete(`storage/${data.id}`)
                    .then(response=>{
                        console.log(response);
                        location.reload();
                    })
                    .catch(error=>{
                        // handle error
                        Swal.fire(
                        'Error! contactese con soporte tecnico',
                        ''+error,
                        'error'
                        )
                        // console.log(error);
                    });


            }
            })

        }
        for (var i = 0; i < classname.length; i++) {
            classname[i].addEventListener('click', deleteItem, false);
        }
    @endsection
</script>
