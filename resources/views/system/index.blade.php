@extends('layouts.app')
@section('title')
    {{-- Proveedores --}}
@endsection
@section('breadcrums')
    {{-- {{ Breadcrumbs::render('action_medium_term') }} --}}
@endsection
@section('content')

    <div class="row justify-content-center">


        <div class="col-md-6">
            <div class="card">

                <div class="card-header card-calendar">

                    <h4 class="card-title ">

                        Roles
                        <small class="float-sm-right">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#RoleModal" data-json="null" > Nuevo  <i class="fa fa-plus-circle"></i> </button>
                        </small>
                    </h4>
                </div>
                <div class="card-body">

                    <table id="listaRole" class="table table-hover table-bordered" >
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                {{-- <th>Sistema</th> --}}
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                {{-- <td>
                                    @foreach ($item->getPermissionNames() as $name)
                                        <span class="badge badge-primary">{{$name}}</span>
                                    @endforeach
                                </td> --}}
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#RoleModal" data-json="{{$item}}"><i class="material-icons text-primary">edit</i></a>

                                    <a href="#"> <i class="material-icons text-danger enabled" data-json='{{$item}}'>delete</i></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">

                <div class="card-header card-calendar">

                    <h4 class="card-title ">
                        Accesos
                        <small class="float-sm-right">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#permissionModal" data-json="null" > Nuevo  <i class="fa fa-plus-circle"></i> </button>
                        </small>
                    </h4>
                </div>
                <div class="card-body">

                    <table class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Icono</th>
                                <th>Ruta</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td> <i class="{{$item->sub_menu?$item->sub_menu->icon:''}}"></i></td>
                                <td> {{$item->sub_menu?$item->sub_menu->route:''}}</td>


                                <td>
                                    <a href="#" data-toggle="modal" data-target="#permissionModal" data-json="{{$item}}"><i class="material-icons text-primary">edit</i></a>
                                    <a href="#"> <i class="material-icons text-danger enabled" data-json='{{$item}}'>delete</i></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">

                <div class="card-header card-calendar">

                    <h4 class="card-title ">
                        Menus
                        <small class="float-sm-right">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#menuModal" data-json="null" > Nuevo  <i class="fa fa-plus-circle"></i> </button>
                        </small>
                    </h4>
                </div>
                <div class="card-body">

                    <table class="table table-hover table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Icono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $item)
                            <tr>
                                <td>{{$item->label}}</td>
                                <td> <i class="{{$item->icon}}" ></i> </td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#menuModal" data-json="{{$item}}"><i class="material-icons text-primary">edit</i></a>
                                    <a href="#"> <i class="material-icons text-danger enabled" data-json='{{$item}}'>delete</i></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5"></div>


    </div>
    {{-- importar componentes aqui --}}
    <role-edit url='{{url('store_role')}}' csrf='{!! csrf_field('POST') !!}' ></role-edit>
    <permission-edit url='{{url('store_permission')}}' csrf='{!! csrf_field('POST') !!}' ></permission-edit>
        {{-- <role-edit url='{{url('article')}}' csrf='{!! csrf_field('POST') !!}' :permissions = "{{$permissions}}" ></role-edit> --}}
        {{-- aqui los modals --}}
        <form method="post" action="{{url('store_system')}}" method="POST" >
            {{csrf_field('POST')}}
            <div class="modal fade" id="systemModal" tabindex="-1" role="dialog" aria-labelledby="systemModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="systemModalLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Nombre del Sistema:</label>
                                <input type="text" class="form-control" id="name" name="name">
                                <input type="text" class="form-control" id="id" name="id" hidden>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form method="post" action="{{url('store_menu')}}" method="POST" >
            {{csrf_field('POST')}}
            <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="menuModalLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Nombre del Menu:</label>
                                <input type="text" class="form-control" id="label" name="label">
                                <input type="text" class="form-control" id="id" name="id" hidden>
                            </div>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Icono:</label> <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank"> <i class="fa fa-question-circle"></i> </a>
                                <input type="text" class="form-control" id="icon" name="icon">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

@endsection
<script>

    @section('script')

        $('#systemModal').on('show.bs.modal', function (event) {
                var button=$(event.relatedTarget) // Button that triggered the modal
                var data=button.data('json') // Extract info from data-* attributes
                console.log(data);
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                var modal=$(this)
                    modal.find('.modal-title').text('Editando '+ data.name)
                    modal.find('.modal-body #name').val(data.name)
                    modal.find('.modal-body #id').val(data.id)
            }

        );
        $('#menuModal').on('show.bs.modal', function (event) {
                var button=$(event.relatedTarget) // Button that triggered the modal
                var data=button.data('json') // Extract info from data-* attributes
                console.log(data);
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                if(data){

                    var modal=$(this)
                        modal.find('.modal-title').text('Editando '+ data.label)
                        modal.find('.modal-body #label').val(data.label)
                        modal.find('.modal-body #icon').val(data.icon)
                        modal.find('.modal-body #id').val(data.id)
                }
            }

        );

        $('#listaRole').DataTable({
                language: spanish_lang
        });


    @endsection
</script>
