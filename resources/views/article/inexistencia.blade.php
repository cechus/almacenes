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
                        Generar Formulario de Inexistencia
                        <small class="float-sm-right">
                            {{-- <a href="{{url('amp_report_excel')}}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> </a>  --}}
                            {{-- <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ArticleModal" data-json="null" > Nuevo  <i class="fa fa-plus-circle"></i> </button>
                            <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#ArticleModal" > Inexistencia <i class="fa fa-search"></i> </button> --}}
                        </small>
                    </h4>
                </div>
                <div class="card-body">

                <inexistencia-component url="{{url('/')}}"></inexistencia-component>
                </div>
            </div>
        </div>
    </div>

@endsection
