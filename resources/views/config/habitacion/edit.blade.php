@extends ('layouts.admin3')
@section('contenido')



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
            <h3>Editar Habitación: {{$habitacione->nombre}}</h3>
            @include('custom.message')



            <form action="{{ route('habitacion.update', $habitacione->id)}}" enctype="multipart/form-data" method="POST" autocomplete="off" role="buscar">
                @csrf
                @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input required type="text" name="nombre" class="form-control form-control-sm mayuscula" value="{{ $habitacione->nombre }}" placeholder="Nombre...">
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label for="">Categoría</label>
                    <select required name="cat_id" id="cat_id" class="form-control select2">
                        @foreach($categorias as $categori)
                        @if($categori->id==$habitacione->cat_id)
                        <option value="{{$categori->id}}" selected>{{$categori->nombre}}</option>
                        @else
                        <option value="{{$categori->id}}">{{$categori->nombre}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                    <label for="">Niveles</label>
                    <select required name="level_id" id="level_id" class="form-control select2">
                        @foreach($levels as $level)
                        @if($level->id==$habitacione->level_id)
                        <option value="{{$level->id}}" selected>{{$level->nombre}}</option>
                        @else
                        <option value="{{$level->id}}">{{$level->nombre}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-primary" type="submit">Guardar</button>
                <a class="btn btn-danger" href="{{route('habitacion.index')}}">{{__('Back')}}</a>
            </div>
            </form>

        </div>
    </div>

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
