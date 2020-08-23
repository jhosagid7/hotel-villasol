@extends ('layouts.admin3')
@section('contenido')
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
        <h3>Listado de Clientes <a href="{{URL::action('ClienteController@create')}}"><button class='btn btn-success'><span class='glyphicon glyphicon-plus'></span> Nuevo</button></a></h3>
        @include('ventas.persona.buscar')
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
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Tipo Doc</th>
                    <th>Número Doc</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Opciones</th>
                </thead>
                <tbody>
                    @foreach ($personas as $per)
                    <tr>
                        <td>{{ $per->idpersona }}</td>
                        <td>{{ $per->nombre }}</td>
                        <td>{{ $per->tipo_documento }}</td>
                        <td>{{ $per->num_documento }}</td>
                        <td>{{ $per->direccion }}</td>
                        <td>{{ $per->telefono }}</td>
                        <td>{{ $per->email }}</td>
                        <td>
                        <a href="{{URL::action('ClienteController@edit', $per->idpersona)}}"><button class='btn btn-info'><span class='glyphicon glyphicon-edit'></span></button></a>
                        <a href="" data-target="#modal-delete-{{$per->idpersona}}" data-toggle="modal"><button class='btn btn-danger'><i class='glyphicon glyphicon-trash'></i></button></a>
                        </td>
                    </tr>
                    @include('ventas.persona.modal')
                    @endforeach
                </tbody>
            </table>   
        </div>
        {{$personas->render()}}
    </div>
</div>
@endsection