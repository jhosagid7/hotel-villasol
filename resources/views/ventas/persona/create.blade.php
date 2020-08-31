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
        <div class="col-lg-6">
            <h3>Nuevo Cliente</h3>
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

            {!! Form::open(array('route' => 'cliente.store','method'=>'POST', 'autocomplete'=>'off' )) !!}
            {{ Form::token() }}

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
                <small id="emailHelp" class="form-text text-muted">Debes Colocar tu Número de Teléfono.</small>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" aria-describedby="emailHelp" value="{{old('email')}}" placeholder="Direccion de email">
            <small id="emailHelp" class="form-text text-muted">Debes colocar un formato de email valido.</small>
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <button class="btn btn-danger" type="reset">Cancelar</button>
            </div>
        </div>
    </div>
            {!! Form::close() !!}

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
