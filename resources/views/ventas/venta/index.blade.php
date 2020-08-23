@extends ('layouts.admin3')
@section('contenido')
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Ventas <a href="{{URL::action('VentaController@create')}}"><button class='btn btn-success btn-sm'><span class='glyphicon glyphicon-plus'></span> Nuevo</button></a></h3>
        @include('ventas.venta.buscar')
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            @include('custom.message')
            <table id="ven" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Comprobante</th>
                    {{-- <th>Impuesto</th> --}}
                    <th>Total Venta</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($ventas as $venta)
                    <tr>
                        <td>{{ $venta->fecha_hora }}</td>
                        <td>{{ $venta->nombre }}</td>
                        <td>{{ $venta->tipo_comprobante . ': ' . $venta->serie_comprobante . '-' . $venta->num_comprobante }}</td>
                        {{-- <td>{{ $venta->impuesto }}</td> --}}
                        <td>{{ $venta->total_venta }}</td>
                        <td>{{ $venta->estado }}</td>
                        <td>
                        <a href="{{URL::action('VentaController@show', $venta->idventa)}}"><button class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-edit'></span></button></a>
                        <a href="" data-target="#modal-delete-{{$venta->idventa}}" data-toggle="modal"><button class='btn btn-danger btn-sm'><i class='glyphicon glyphicon-trash'></i></button></a>
                        </td>
                    </tr>
                    @include('ventas.venta.modal')
                    @endforeach
                </tbody>
            </table>
        </div>
        {{$ventas->render()}}
    </div>
</div>

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
                    "order": [[0, "desc"]],
           });
           $("#buscarTexto").keyup(function() {
               dataTable.fnFilter(this.value);
           });
       });
    </script>
    @endpush
@endsection
