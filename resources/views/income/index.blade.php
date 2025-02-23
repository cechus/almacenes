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
                        Ingresos {{Auth::user()->getStorage()->name}}
                        <small class="float-sm-right">
                            <a href="{{url('income/create')}}" class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i> Nuevo Ingreso </a>
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-clock"></i> Historial
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#historyModal" data-type="Orden de Compra">Orden de Compra</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#historyModal" data-type="Numero de Contrato">Numero de Contrato</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#historyModal" data-type="Fondos en Avance">Fondos en Avance</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#historyModal" data-type="Reembolso">Reembolso</a>
                                </div>
                            </div>
                        </small>
                    </h4>
                </div>
                <div class="card-body">

                    <table id="lista" class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Cod. Ingreso</th>
                                <th>Acta RyC</th>
                                <th>Factura</th>
                                <th>Fecha Ingreso</th>
                                <th>Persona</th>
                                <th>Proveedor</th>
                                <th>Canti. Articulos</th>
                                <th>Costo Total</th>
                                <th>Modificaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incomes as $item)
                            <tr>
                                <td>{{$count++}}</td>
                                <td><a href="#"  class="badge badge-primary" data-toggle="modal" data-target="#modalPdf" data-url="{{url('income_note/'.$item->id)}}">{{$item->correlative}}</a>
                                </td>
                                <td><a href="#"  class="badge badge-primary" data-toggle="modal" data-target="#modalPdf" data-url="{{url('ryc_note/'.$item->id)}}">{{$item->correlative}}</a>
                                </td>

                                <td>
                                    @if($item->path_invoice)
                                    <a href="#" data-toggle="modal" data-target="#modalPdf" data-url="{{url('storage/'.substr($item->path_invoice,7))}}" > <i class="fa fa-file-invoice-dollar text-secondary"></i> </a>
                                    @else
                                    <a href="#" data-toggle="modal" data-target="#invoice-modal" data-target="#invoice-modal" data-id="{{$item->id}}"><i class="fa fa-plus-circle"></i></span>
                                    </a>
                                        @endif
                                 </td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->employee->getFullName()}}</td>
                                <td>{{$item->provider->name}}</td>
                                <td>{{$item->getTotalQuantity()}}</td>
                                <td>{{$item->total_cost}}</td>

                            <td> <a href="{{url('create_change_income/'.$item->id)}}"> <i class="fa fa-file-signature text-secondary" ></i> </a> </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        {{-- aqui los modals --}}
        {{-- <modal name="invoice-modal" class="p-sm" height="auto"> --}}
        <div class="modal fade" id="invoice-modal" tabindex="-1" role="dialog" aria-labelledby="modalPdfTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Editar Informacion de la factura</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form enctype="multipart/form-data" method="post" action="/income_invoice_info" id="income-invoice-info">
                        @csrf
            
                            <input type="hidden" name="income_id" id="income-id">
                            <div class="col-md-12" >
                                <div class="col-md-3">
                                    <label class="control-label">Factura</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="file" required name="path_invoice">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="text-center m-sm">
                                    {{-- <button class="btn btn-danger" type="button">
                                    <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
                                    <span class="bold">Cancelar</span>
                                    </button> --}}
                                    <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-check-circle"></i>&nbsp;Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- <article-component url='{{url('article')}}' csrf='{!! csrf_field('POST') !!}' ></article-component> --}}


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
                <iframe src="" width="100%" height="100%" frameborder="0" allowtransparency="true"></iframe>
            </div>
            </div>
        </div>
    </div>

    <income-history ></income-history>

@endsection
<script>

    @section('script')

        var url_flash = @json(session('url'));
        console.log('printer flash')
        console.log(url_flash);
        if(url_flash)
        {
            if(url_flash.length >0)
            {
                $('#modalPdf .modal-body iframe').attr('src', url_flash)
                $('#modalPdf').modal('show')
            }
        }

        $('#modalPdf').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var url = button.data('url') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            console.log(url);
            var modal = $(this)
            modal.find('.modal-title').text('' )
            modal.find('.modal-body iframe').attr('src', url)
        })
        $('#invoice-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var incomeId = button.data('id') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            console.log(incomeId);
            // var modal = $(this)
            // modal.find('.modal-title').text('' )
            // modal.find('.modal-body iframe').attr('src', url)
            $('#income-id').val(incomeId);
        })

        var classname = document.getElementsByClassName("deleted");
        // console.log(classname);
        function deleteItem(){

            var data = JSON.parse(this.getAttribute("data-json"));

            Swal.fire({
            title: 'Esta Seguro de Inactivar '+data.name+'?',
            text: "una vez inactivo no se podra utilizar el articulo!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
            }).then((result) => {
            if (result.value) {

                axios.delete(`article/${data.id}`)
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
