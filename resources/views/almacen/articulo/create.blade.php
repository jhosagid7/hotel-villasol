@extends ('layouts.admin3') @section('contenido')
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
    <div class="col-lg-6">
        <h3>Nuevo Articulo</h3>
        @include('custom.message')
    </div>
</div>
<form action="{{ route('articulo.store')}}" enctype="multipart/form-data" method="POST" autocomplete="off">

@csrf

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" required value="{{old('nombre')}}" class="form-control" placeholder="Nombre...">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="">Categoría</label>
            <select name="idcategoria" id="idcategoria" class="form-control">
                    @foreach($categorias as $cat)
                        <option value="{{$cat->idcategoria}}">{{$cat->nombre}}</option>
                    @endforeach
                </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="codigo">Código</label>
            <input type="text" name="codigo" required value="{{old('codigo')}}" class="form-control" placeholder="Codigo de articulo...">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="text" name="stock" required value="{{old('stock')}}" class="form-control" placeholder="Stock de articulo...">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" required value="{{old('descripcion')}}" class="form-control" placeholder="Descripción del articulo...">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="imagen">Imagen</label>
            <input type="file" name="imagen" class="form-control" accept="image/*">
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Guardar</button>
            <a class="btn btn-danger" href="{{route('articulo.index')}}">{{__('Back')}}</a>
        </div>
    </div>
</div>

</form>

{{-- fin de la cabecera de box --}}
</div>
<!-- /.box-body -->
<div class="box-footer">
  {{-- Footer --}}
</div>
<!-- /.box-footer-->
</div>
<!-- /.box -->
 @endsection
