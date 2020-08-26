@extends ('layouts.admin3')
@section('contenido')

    <div class="row">
        <div class="col-lg-6">
            <h3>Configurar tasa: {{$tasa->nombre}}</h3>
            @include('custom.message')
        </div>
    </div>

            {{-- {!! Form::model($articulo,['route'=>['articulo.update', $articulo->idarticulo], 'method'=>'PATCH', 'files'=>'true']) !!}
            {{ Form::token() }} --}}
    <form action="{{ route('tasa.update', $tasa->id)}}" enctype="multipart/form-data" method="POST" autocomplete="off" role="buscar">
    @csrf
    @method('PUT')
    <div class="row">
        
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input readonly type="text" name="nombre" required value="{{$tasa->nombre}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="tasa">Tasa</label>
                <input autofocus type="text" name="tasa" required value="{{$tasa->tasa}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="porcentaje_ganancia">Margen ganancia</label>
                <input type="text" name="porcentaje_ganancia" required value="{{$tasa->porcentaje_ganancia}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="">Estado</label>
                <select name="estado" id="estado" class="form-control">
                    
                    @if($tasa->estado)
                    <option value="{{$tasa->estado}}" selected>{{$tasa->estado}}</option>
                    <option value="Activo">Activo</option>
                    <option value="Cancealdo">Cancealdo</option>
                    <option value="Suspendido">Suspendido</option>
                    @else
                    <option value="Activo">Activo</option>
                    <option value="Cancealdo">Cancealdo</option>
                    <option value="Suspendido">Suspendido</option>
                    @endif
                    
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="created_at">Creado el:</label>
                <input readonly type="text" name="created_at" required value="{{$tasa->created_at}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="updated_at">Actualizado el:</label>
                <input readonly type="text" name="updated_at" required value="{{$tasa->updated_at}}" class="form-control">
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
        </div>
    </div>

@endsection
