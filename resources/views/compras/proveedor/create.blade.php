@extends ('layouts.admin3')
@section('contenido')

    <div class="row">
        <div class="col-lg-6">
            <h3>Nuevo Proveedor</h3>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>


            <form action="{{ route('proveedor.store')}}" enctype="multipart/form-data" method="POST" autocomplete="off">

                @csrf
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{old('nombre')}}" placeholder="Nombre...">
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="tipo_documento">Tipo Documento</label>
                <select class="form-control" name="tipo_documento">
                    <option value="CI">CI.V-</option>
                    <option value="CI">CI.E-</option>
                    <option value="RIF">RIF</option>
                    <option value="PAS">PAS</option>
                </select>

            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="num_documento">Número de Documento</label>
                <input type="text" name="num_documento" class="form-control" value="{{old('num_documento')}}" placeholder="Número de Documento...">
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" class="form-control" value="{{old('direccion')}}" placeholder="Dirección...">
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" class="form-control" value="{{old('telefono')}}" placeholder="Teléfono...">
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" class="form-control" value="{{old('email')}}" placeholder="Email...">
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
        </div>
    </div>
            </form>


@endsection
