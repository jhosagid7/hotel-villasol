@extends ('layouts.admin3')
@section('contenido')





    <!-- Main content -->
    {{-- <section class="content">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
            <li><a data-toggle="tab" href="#menu1">Menu 1</a></li>
            <li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
          </ul>

          <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
              <h3>HOME</h3>
              <p>Some content.</p>
            </div>
            <div id="menu1" class="tab-pane fade">
              <h3>Menu 1</h3>
              <p>Some content in menu 1.</p>
            </div>
            <div id="menu2" class="tab-pane fade">
              <h3>Menu 2</h3>
              <p>Some content in menu 2.</p>
            </div>
          </div> --}}

        <!-- Default box -->
        <div class="box">
            {{-- <div class="box-header with-border  no-print">
                <h3 class="box-title">
                    @isset($title)
                        {{$title}}
                    @else
                        {!!"Sistema"!!}
                    @endisset
                </h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div> --}}
            <div style="background-color: #e7eaeb" class="box-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!-- Custom Tabs (Pulled to the right) -->
                    <div class="nav-tabs-custom">
                      <ul class="nav nav-tabs pull-right">
                          @php

                            $cont = 1;

                          @endphp
                          @foreach ($levels as $level)
                          {{-- @php


                            if( $cont == $level->id){
                                $active = 'active';
                            }else{
                                $active = false;
                            }
                        @endphp --}}


                          <li class=""><a href="#tab_{{ $level->id ?? '' }}" data-toggle="tab" aria-expanded="true">{{ $level->nombre ?? '' }}</a></li>
                          @endforeach

                        <li class="dropdown">
                          <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                            Acción <span class="caret"></span>
                          </a>
                          <ul class="dropdown-menu">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
                            <li role="presentation" class="divider"></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                          </ul>
                        </li>
                        <li class="pull-left header"><i class="fa fa-th"></i>@isset($title)
                            {{$title}}
                        @else
                            {!!"Recepción"!!}
                        @endisset</li>
                      </ul>
                      <div style="background-color: #e7eaeb" class="tab-content">
                        @php

                        $cont = 1;
                        $actual = 0;
                      @endphp
                        {{-- @foreach ($levels as $level)
                        @php


                            if( $cont == $level->id){
                                $active = 'active';
                            }else{
                                $active = false;
                            }
                            $actual = 0;
                        @endphp --}}
                        <div class="tab-pane " id="tab_{{ $level->id ?? '' }}">


                            <div class="row">


                                @foreach ($habitaciones as $habitacion)
                                {{-- {{$habitacion->level}} --}}
                                @if ($habitacion->level_id !== $actual)
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        @if ($habitacion->status == 'Disponible')
                                        <span class="info-box-icon bg-green"><i class="fa fa-bed"></i></span>
                                            @elseif ($habitacion->status == 'Ocupada')
                                            <span class="info-box-icon bg-red"><i class="fa fa-bed"></i></span>
                                            @elseif ($habitacion->status == 'Limpieza')
                                            <span class="info-box-icon bg-aqua"><i class="fa fa-bed"></i></span>
                                            @elseif ($habitacion->status == 'Reparaciones')
                                            <span class="info-box-icon bg-yellow"><i class="fa fa-bed"></i></span>
                                            @endif

                                        <div class="tile-body" style="padding: 1px;">
                                        <h4 style="text-align: center;"><i class="fa fa-bed"></i> {{$habitacion->nombre}}</h4>
                                        </div>

                                        <div class="info-box-content">
                                        <span class="info-box-text">{{$habitacion->cat->nombre}}</span>

                                            @if ($habitacion->status == 'Disponible')
                                                <span class="info-box-number text-success">{{$habitacion->status}}</span>
                                            @elseif ($habitacion->status == 'Ocupada')
                                                <span class="info-box-number text-danger">{{$habitacion->status}}</span>
                                            @elseif ($habitacion->status == 'Limpieza')
                                                <span class="info-box-number text-primary">{{$habitacion->status}}</span>
                                            @elseif ($habitacion->status == 'Reparaciones')
                                                <span class="info-box-number text-warning">{{$habitacion->status}}</span>
                                            @endif

                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>

                                <!-- /.col -->
                                @endif



                                @endforeach

                                <!-- /.col -->
                              </div>
                        <!-- /.tab-pane -->
                        {{-- @endforeach --}}

                        <!-- /.tab-pane -->
                      </div>
                      <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                  </div>
            </div>
        </div>

    </section>
  <!-- /.content -->
  <div class="clearfix"></div>
@push('sciptsMain')
<script>

    $(document).ready(function() {
       var dataTable = $('#ven').dataTable({
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
                "iDisplayLength" : 5,
       });
       $("#buscarTexto").keyup(function() {
           dataTable.fnFilter(this.value);
       });
   });
</script>

<script language="javascript">

    function imprimirContenido(el){
        // $('#guion').show();
        var restaurarPagina = document.body.innerHTML;
        // var urlPagina = window.location.href;

//        alert(urlPagina);
        // $('#headerPagina').show();
        // $('#firmaPagina').show();
        var imprimircontenido = document.getElementById(el).innerHTML;
        document.body.innerHTML = imprimircontenido;
        window.print();
        // $('#headerPagina').hide();
        // $('#firmaPagina').hide();
        document.body.innerHTML = restaurarPagina;
        // $('#guion').show();
        // window.location= urlPagina;

    }
//     $( document ).ready( function() {
// $("#print_button1").click(function(){
//     alert('entro');
//             var mode = 'iframe'; // popup
//             var close = mode == "popup";
//             var options = { mode : mode, popClose : close};
//             $("div.contePrint").printArea( options );
//         });
// });
</script>
@endpush
@endsection
