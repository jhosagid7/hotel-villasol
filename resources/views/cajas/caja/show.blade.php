@extends('layouts.admin3')

@section('contenido')



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
    <h3>Resumen de caja



    {{-- @include('cajas.caja.buscar') --}}
</div>
</div>

<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="table-responsive">
        @include('custom.message')

            <table id="ven"
                class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Fecha</th>
                        <th>Hora inicio</th>
                        <th>Hora cierre</th>
                        <th>Inicio Dolar</th>
                        <th>Inicio Peso</th>
                        <th>Inicio Bolivar</th>
                        <th>Cierre Dolar</th>
                        <th>Cierre Peso</th>
                        <th>Cierre Bolivar</th>
                        <th>Estado</th>
                        <th>Caja</th>
                    </tr>
                </thead>
                <tbody id="listarcaja">

                    <tr>
                        <td>{{ $caja->codigo }}</td>
                        <td>{{ $caja->created_at->diffForHumans() }}</td>
                        <td>{{ $caja->hora }}</td>
                        <td>{{ $caja->hora_cierre }}</td>
                        <td>{{ $caja->monto_dolar }}</td>
                        <td>{{ $caja->monto_peso }}</td>
                        <td>{{ $caja->monto_bolivar }}</td>
                        <td>{{ $caja->monto_dolar_cierre }}</td>
                        <td>{{ $caja->monto_peso_cierre }}</td>
                        <td>{{ $caja->monto_bolivar_cierre }}</td>
                        <td>{{ $caja->estado }}</td>
                        <td>{{ $caja->caja }}</td>
                </tr>



                </tbody>
            </table>

            @include('cajas.caja.caja')
    </div>
    {{-- {{$articulos->render()}} --}}
</div>
{{-- {!! '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+',3,33,array(1,1,1), true) . '" alt="barcode"   />'!!} --}}

</div>

{{-- fin de la cabecera de box --}}
</div>
<!-- /.box-body -->
<div class="box-footer">
    @if ($caja->estado === 'Abierta')
    <a href="" data-target="#modal-delete-{{$caja->id}}" data-toggle="modal"><button class='btn btn-danger'><i class='glyphicon glyphicon-trash'></i> Cerrar caja</button></a>
    <a class="btn btn-success" href="{{route('venta.create')}}">{{__('Ir a ventas')}}</a>
    @endif
    <a class="btn btn-warning" href="{{route('caja.index')}}">{{__('Ir a cajas')}}</a>
</div>
<!-- /.box-footer-->
</div>
<!-- /.box -->

@push('sciptsMain')
<script>
    $(document).ready(function() {
       var dataTable = $('#ven').dataTable({
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
