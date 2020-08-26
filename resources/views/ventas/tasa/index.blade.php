@extends ('layouts.admin3')
@section('contenido')
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Tasas</h3>
        @include('ventas.tasa.buscar')
    </div>
</div>

<div class="row">
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
                    <th>Estado</th>
                    <th>Opciones</th>
                    <th>Estado de la Caja</th>
                </thead>
                <tbody>
                    @foreach ($tasas as $tasa)
                    <tr>
                        <td>{{ $tasa->id }}</td>
                        <td>{{ $tasa->nombre }}</td>
                        <td>{{ $tasa->tasa }}</td>
                        <td>{{ $tasa->porcentaje_ganancia }}</td>
                        <td>{{ $tasa->estado }}</td>
                        <td>{{ $tasa->caja }}</td>
                        <td>
                        <a href="{{URL::action('TasaController@edit', $tasa->id)}}"><button class='btn btn-info btn-xs'><span class='glyphicon glyphicon-edit'></span></button></a>
                        </td>
                    </tr>
                    @include('ventas.tasa.modal')
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- {{$tasas->render()}} --}}
    </div>
</div>

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
