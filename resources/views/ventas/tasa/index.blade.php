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
        <h3>Listado de Tasas</h3>
        @include('ventas.tasa.buscar')
    </div>
</div>

<div class="row">
    @include('custom.message')
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
            @endif
            <table id="tas" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Tasa</th>
                    <th>Margen ganancia</th>
                    <th>Creado el</th>
                    <th>Actualizado el</th>


                </thead>
                <tbody>
                    @foreach ($tasas as $tasa)
                    <tr>
                        <td>{{ $tasa->id }}</td>
                        <td>{{ $tasa->nombre }}</td>
                        <td>{{ $tasa->tasa }}</td>
                        <td>{{ $tasa->porcentaje_ganancia }}</td>
                        <td>{{ $tasa->created_at }}</td>
                        <td>{{ $tasa->updated_at }}</td>

                    </tr>
                    {{-- @include('ventas.tasa.modal') --}}
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- {{$tasas->render()}} --}}
    </div>
</div>


{{-- fin de la cabecera de box --}}
</div>
<!-- /.box-body -->
<div class="box-footer">
    <a href="{{URL::action('TasaController@create')}}"><button class='btn btn-success'><span class='glyphicon glyphicon-plus'></span> Actualizar</button></a></h3>
    <a class="btn btn-danger" href="{{ url()->previous() }}">{{__('Regresar')}}</a>
</div>
<!-- /.box-footer-->
</div>
<!-- /.box -->
@push('sciptsMain')
    <script>
        $(document).ready(function() {
           var dataTable = $('#tas').dataTable({
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
                    "iDisplayLength" : 6,
                    // "order": [[0, "desc"]],
           });
           $("#buscarTexto").keyup(function() {
               dataTable.fnFilter(this.value);
           });
       });
    </script>
    @endpush
@endsection
