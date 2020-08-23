@extends ('layouts.admin3')
@section('contenido')
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Ingresos <a href="{{URL::action('IngresoController@create')}}"><button class='btn btn-success'><span class='glyphicon glyphicon-plus'></span> Nuevo</button></a></h3>
        @include('compras.ingreso.buscar')
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            @include('custom.message')
            <table id="ingre" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Comprobante</th>
                    <th>Impuesto</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($ingresos as $ing)
                    <tr>
                        <td>{{ $ing->fecha_hora }}</td>
                        <td>{{ $ing->nombre }}</td>
                        <td>{{ $ing->tipo_comprobante . ': ' . $ing->serie_comprobante . '-' . $ing->num_comprobante }}</td>
                        <td>{{ $ing->impuesto }}</td>
                        <td>{{ $ing->total }}</td>
                        <td>{{ $ing->estado }}</td>
                        <td>
                        <a href="{{URL::action('IngresoController@show', $ing->idingreso)}}"><button class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-edit'></span></button></a>
                        <a href="" data-target="#modal-delete-{{$ing->idingreso}}" data-toggle="modal"><button class='btn btn-danger btn-sm'><i class='glyphicon glyphicon-trash'></i></button></a>
                        </td>
                    </tr>
                    @include('compras.ingreso.modal')
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- {{$ingresos->render()}} --}}
    </div>
</div>
@push('sciptsMain')
    <script>
        $(document).ready(function() {
           var dataTable = $('#ingre').dataTable({
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
                    "order": [[0, "desc"]],
                    "iDisplayLength" : 5,
           });
           $("#buscarTexto").keyup(function() {
               dataTable.fnFilter(this.value);
           });
       });
    </script>
    @endpush
@endsection
