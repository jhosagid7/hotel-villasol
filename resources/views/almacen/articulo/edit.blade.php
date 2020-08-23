@extends ('layouts.admin3')
@section('contenido')

    <div class="row">
        <div class="col-lg-6">
            <h3>Editar el Articulo: {{$articulo->nombre}}</h3>
            @include('custom.message')
        </div>
    </div>

            {{-- {!! Form::model($articulo,['route'=>['articulo.update', $articulo->idarticulo], 'method'=>'PATCH', 'files'=>'true']) !!}
            {{ Form::token() }} --}}
    <form action="{{ route('articulo.update', $articulo->idarticulo)}}" enctype="multipart/form-data" method="POST" autocomplete="off" role="buscar">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required value="{{$articulo->nombre}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="">Categoría</label>
                <select name="idcategoria" id="idcategoria" class="form-control">
                    @foreach($categorias as $cat)
                    @if($cat->idcategoria==$articulo->idcategoria)
                    <option value="{{$cat->idcategoria}}" selected>{{$cat->nombre}}</option>
                    @else
                    <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" name="codigo" required value="{{$articulo->codigo}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="text" name="stock" required value="{{$articulo->stock}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" name="descripcion" required value="{{$articulo->descripcion}}" class="form-control">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" name="imagen" class="form-control">
            @if(($articulo->imagen) !="")
                <img src="{{asset('imagenes/articulos/'.$articulo->imagen)}}" alt="{{$articulo->nombre}}" height="100px" width="100px">
            @endif
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
