@extends ('layouts.admin3')
@section('contenido')

<!-- Default box -->
<!-- Content Header (Page header) -->
    {{-- <section class="content-header">
      <h1>
        <!--Blank page-->
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section> --}}

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
          <h3 class="box-title">@isset($title)
              {{$title}}
              @else
              {!!"Sistema"!!}
          @endisset</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                      title="Collapse">
                <i class="fa fa-minus"></i></button>
              <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
        {{-- cabecera de box --}}
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        {{-- <h3>Listado de Categorias <a href="" data-target="#modal-nuevo" data-toggle="modal"><button class='btn btn-success'><span class='glyphicon glyphicon-plus'></span>Nuevo</button></a></h3> --}}
        <h3>Listado de Artículos <a href="{{URL::action('CategoriaController@create')}}"><button class='btn btn-success btn-sm'><span class='glyphicon glyphicon-plus'></span> Nuevo</button></a></h3>
        @include('almacen.categoria.buscar')
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            @include('custom.message')
            <table id="cate" class="table table-striped table-bordered table-condensed table-hover table-ms">
                <thead>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($categorias as $cat)
                    <tr>
                        <td>{{ $cat->idcategoria }}</td>
                        <td>{{ $cat->nombre }}</td>
                        <td>{{ $cat->descripcion }}</td>
                        <td>
                        <a href="{{URL::action('CategoriaController@edit', $cat->idcategoria)}}"><button class='btn btn-info btn-sm'><span class='glyphicon glyphicon-edit'></span></button></a>
                        <a href="" data-target="#modal-delete-{{$cat->idcategoria}}" data-toggle="modal"><button class='btn btn-danger btn-sm'><i class='glyphicon glyphicon-trash'></i></button></a>
                        </td>
                    </tr>
                    @include('almacen.categoria.modal')
                    @endforeach
                    {{-- @include('almacen.categoria.nuevo_modal') --}}
                </tbody>
            </table>
        </div>
        {{-- {{$categorias->render()}} --}}
    </div>
</div>

{{-- fin de la cabecera de box --}}
</div>
<!-- /.box-body -->
<div class="box-footer">
  {{-- Footer --}}
</div>
<!-- /.box-footer-->
</div>
<!-- /.box -->
@push('sciptsMain')
    <script>
        $(document).ready(function() {
           var dataTable = $('#cate').dataTable({
            "language": {
                        "info": "_TOTAL_ registros",
                        "search": "Buscar",
                        "paginate": {
                            "next": "Siguiente",
                            "previous": "Anterior",
                        },
                        "lengthMenu": 'Mostrar <select >'+
                                    '<option value="5">5</option>'+
                                    '<option value="10">10</option>'+
                                    '<option value="-1">Todos</option>'+
                                    '</select> registros',
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "emptyTable": "No hay datos",
                        "zeroRecords": "No hay coincidencias",
                        "infoEmpty": "",
                        "infoFiltered": ""
                    },
                    "iDisplayLength" : 5,
                    "order": [[0, "desc"]],
           });
           $("#buscarTexto").keyup(function() {
               dataTable.fnFilter(this.value);
           });
       });
    </script>
    @endpush

@endsection
