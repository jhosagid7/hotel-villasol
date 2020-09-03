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
            <h3>Configuracion de tasa y margenes de ganancia</h3>
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


            <form action="{{ route('tasa.store')}}" enctype="multipart/form-data" method="POST" autocomplete="off">
            @csrf
                <div class="row">
                    <table id="tas" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Tasa</th>
                            <th>Margen ganancia</th>
                            <th>Creado el</th>
                            <th>Actualizado el</th>
                        </thead>
                        <tbody>
                            @foreach ($tasas as $tasa)
                            <tr>
                                <td>{{ $tasa->id }}</td><input name="id[]" type="hidden" class="form-control" id="id" value="{{ old('id',  $tasa->id)}}" placeholder="{{__('id')}}...">
                                <td>{{ $tasa->nombre }}</td>
                                <td><input name="tasa[]" type="text" class="form-control" id="name" value="{{ old('tasa', $tasa->tasa)}}" placeholder="{{__('Tasa')}}..."></td>
                                <td><input name="porcentaje[]" type="text" class="form-control" id="name" value="{{ old('porcentaje', $tasa->porcentaje_ganancia)}}" placeholder="{{__('Porcentaje ganancia')}}..."></td>
                                <td>{{ $tasa->created_at }}</td>
                                <td>{{ $tasa->updated_at }}</td>

                            </tr>
                            {{-- @include('ventas.tasa.modal') --}}
                            @endforeach
                        </tbody>
                    </table>
                    <input name="url" type="hidden" value="{{URL::previous()}}">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a class="btn btn-danger" href="{{ url()->previous() }}">{{__('Regresar')}}</a>
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
