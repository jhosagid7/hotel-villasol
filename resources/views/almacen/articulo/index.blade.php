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
        <h3>Listado de Artículos <a href="{{URL::action('ArticuloController@create')}}"><button class='btn btn-success'><span class='glyphicon glyphicon-plus'></span> Nuevo</button></a></h3>
        @include('almacen.articulo.buscar')
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            @include('custom.message')
            <table id="arti" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Id</th>
                    <th>Categoría</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Stock</th>
                    <th>Descripción</th>
                    <th>imagen</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($articulos as $art)
                    <tr>
                        <td>{{ $art->idarticulo }}</td>
                        <td>{{ $art->categoria }}</td>
                        <td>{{ $art->nombre }}</td>
                        <td><div>

                        </div>
                        <div >
                            {!! DNS1D :: getBarcodeHTML ( $art->codigo , 'UPCE' ) !!}
                            {{-- {!! DNS1D::getBarcodeHTML($art->codigo, 'PHARMA2T')!!} --}}
                            {{ $art->codigo }}
                        </div></td>
                        <td>{{ $art->stock }}</td>
                        <td>{{ $art->descripcion }}</td>
                        <td>
                            <img src="{{asset('imagenes/articulos/'.$art->imagen)}}" alt="{{ $art->nombre }}" height="50px" width="50px" class="img-circle">
                        </td>
                        <td>{{ $art->estado }}</td>
                        <td>
                        <a href="{{URL::action('ArticuloController@edit', $art->idarticulo)}}"><button class='btn btn-info btn-sm'><span class='glyphicon glyphicon-edit'></span></button></a>
                        <a href="" data-target="#modal-delete-{{$art->idarticulo}}" data-toggle="modal"><button class='btn btn-danger btn-sm'><i class='glyphicon glyphicon-trash'></i></button></a>
                        </td>
                    </tr>
                    @include('almacen.articulo.modal')
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- {{$articulos->render()}} --}}
    </div>
    {{-- {!! '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+',3,33,array(1,1,1), true) . '" alt="barcode"   />'!!} --}}

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
           var dataTable = $('#arti').dataTable({
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
           });
           $("#buscarTexto").keyup(function() {
               dataTable.fnFilter(this.value);
           });
       });
    </script>
    @endpush

@endsection
