@extends ('layouts.admin3')
@section('contenido')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    @isset($title)
                        {{ $title }}
                    @else
                        {!! 'Sistema' !!}
                    @endisset
                </h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip"
                        title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                {{-- cabecera de box --}}

                <div class="row">
                    <div class="col-lg-12">
                        <h3>Editar Precio del servicio: {{ $precio->horario->nombre }} y la categoría:
                            {{ $precio->cat->nombre }}</h3>
                        @include('custom.message')



                        <form action="{{ route('precio.update', $precio->id) }}" enctype="multipart/form-data"
                            method="POST" autocomplete="off" role="buscar">
                            @csrf
                            @method('PUT')

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label for="">Horario</label>
                                    <select required name="horario_id" id="horario_id" class="form-control select2">
                                        @foreach ($horarios as $horario)
                                            @if ($horario->id == $precio->horario_id)
                                                <option value="{{ $horario->id }}" selected>{{ $horario->nombre }}</option>
                                            @else
                                                <option value="{{ $horario->id }}">{{ $horario->nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Categoría</label>
                                    <select required name="cat_id" id="cat_id" class="form-control select2">
                                        @foreach ($categorias as $categori)
                                            @if ($categori->id == $precio->cat_id)
                                                <option value="{{ $categori->id }}" selected>{{ $categori->nombre }}
                                                </option>
                                            @else
                                                <option value="{{ $categori->id }}">{{ $categori->nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nombre">Precio</label>
                                    <input required type="text" name="precio"
                                        class="form-control form-control-sm mayuscula" value="{{ $precio->precio }}"
                                        placeholder="Precio...">
                                </div>
                            </div>




                            <div class="form-group col-lg-12">
                                <button class="btn btn-primary" type="submit">Guardar</button>
                                <a class="btn btn-danger" href="{{ route('precio.index') }}">{{ __('Back') }}</a>
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
