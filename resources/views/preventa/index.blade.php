@extends ('layouts.admin3')
@section('contenido')

<style type="text/css">
    .bootstrap-select { width: 400px !important; }
    </style>



        <div class="box">

            <div style="background-color: #e7eaeb" class="box-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <!-- Custom Tabs (Pulled to the right) -->
                        <div class="nav-tabs-custom">
                          <ul class="nav nav-tabs pull-right">




                            <li class="pull-left header"><i class="fa fa-th"></i> @isset($title)
                                {{$title}}
                                @else
                                {!!"Sistema"!!}
                            @endisset</li>
                          </ul>
                          <div style="background-color: #e7eaeb" class="tab-content">


                              {{-- <b>How to use: {{$level->id}}</b> --}}

                              @foreach ($habitaciones as $habitacion)
                              {{-- {{$habitacion->habitacion->status}} --}}
                                @if ($habitacion->habitacion->status == 'Ocupada')
                                    <a href="{{URL::action('ProcesoVentaController@show', $habitacion->id)}}"  class="small-box-footer">
                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <div class="info-box">


                                                    <span class="info-box-icon bg-teal-gradient"><i class="fa fa-bed text-blue"></i></span>



                                                <div class="tile-body">
                                                    <h4 style="text-align: center;"><i class="fa fa-bed text-red"></i><b> {{$habitacion->nombre_habitacion}}</b></h4>
                                                </div>

                                                <div class="info-box-content">
                                                    <span class="info-box-text ">Vender al Servicio {{$habitacion->tipo_habitacion}}</span>






                                                </div>
                                                <!-- /.info-box-content -->

                                            </div>
                                            <!-- /.info-box -->

                                        </div>
                                    </a>
                                @endif
                            <div class="modal fade bs-example-modal-xm" id="myModal<?php //echo $habitacion->id.''.$habitacion->level->id; ?>" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-info">
                                  <div class="modal-dialog">
                                    <div class="modal-content">

                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><span class="fa fa-spinner"></span> ESTÁ A PUNTO DE TERMINAR LA LIMPIEZA</h4>
                                      </div>
                                      <div class="modal-body" style="background-color:#fff !important;">

                                        <div class="row">
                                        <div class="col-md-offset-1 col-md-10">

                                          <div class="form-group">
                                            <div class="input-group">
                                              <span class="input-group-addon"> HABITACIÓN </span>
                                              <input type="text" class="form-control col-md-8" name="nombre" disabled value="<?php // echo $habitacion->nombre; ?>" required placeholder="Ingrese nombre">
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <div class="input-group">
                                              <span class="input-group-addon"> TIPO </span>
                                              <input type="text" class="form-control col-md-8" name="nombre" disabled value="<?php // echo $habitacion->cat->nombre; ?>" required placeholder="Ingrese nombre">
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <div class="input-group">
                                              <span class="input-group-addon"> DETALLES </span>
                                              <input type="text" class="form-control col-md-8" name="nombre" disabled value="<?php // echo $habitacion->cat->descripcion; ?>" required placeholder="Ingrese nombre">
                                            </div>
                                          </div>

                                          <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">CAMARERA</span>
                                                {{-- <select id="segr_name" name="segr_name" data-size="2" data-width="100%" class="selectpicker" multiple data-value="{{segr_name}}" title="Seleccione Grupo de Servicio"> --}}
                                                  {{-- <option id="0" value="0">0</option> --}}
                                                {{-- <select required  data-size="2" data-width="100%" palceholder="hola" data-id="{{$habitacion->cat->id}}" title="Seleccione Servicio" name="camarera_id" id="camarera_id" class="selvalLimpiesa form-control select2">
                                                    <option value="0"></option>
                                                    @foreach($users as $user)
                                                        @if ($user->roles[0]->name == 'Camarera')
                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                        @endif

                                                        @endforeach
                                                    </select> --}}

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="input-group">
                                              <span class="input-group-addon"> OBSERVACIÓN </span>
                                              <input type="text" class="form-control col-md-8 observacionLimpiesa" name="observacion" value="" required placeholder="Observación">
                                            </div>
                                          </div>

                                        </div>
                                        </div>

                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                                        <a href="index.php?view=limpieza&id=<?php// echo $habitacion->id; ?>" class="btn btn-outline">Finalizar limpieza</a>
                                      </div>

                                    </div>
                                    <!-- /.modal-content -->
                                  </div>
                                  <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                              </div>


                            <div class="modal fade bs-example-modal-xm refrescar" id="myModal2<?php// echo $habitacion->id; ?>" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-success">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{route('proceso')}}" method="post">
                                                @csrf
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title"><span class="fa fa-spinner"></span> SELECCIONE SERVICIO </h4>
                                            </div>
                                            <div class="modal-body" style="background-color:#fff !important;">

                                                <div class="row">
                                                    <div class="col-md-offset-1 col-md-10">

                                                    <div class="form-group">
                                                        <div class="input-group">
                                                        <span class="input-group-addon"> HABITACIÓN </span>
                                                        <input type="text" class="form-control col-md-8" name="nombre" disabled value="<?php // echo $habitacion->nombre; ?>"  placeholder="Ingrese nombre">
                                                        <input type="hidden" class="form-control col-md-8" name="habitacion_id"  value="<?php // echo $habitacion->id; ?>"  placeholder="Ingrese nombre">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="input-group">
                                                        <span class="input-group-addon"> TIPO </span>
                                                        <input type="text" class="form-control" name="nombre"  disabled value="<?php  // echo $habitacion->cat->nombre; ?>"  placeholder="Ingrese nombre">
                                                        {{-- <input type="hidden" class="form-control catid ver" name="cat_id" id="cat_id" data-id="{{$habitacion->cat->id}}" value="<?php echo $habitacion->cat->id; ?>"  placeholder="Ingrese nombre"> --}}
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="input-group">
                                                          <span class="input-group-addon"> DETALLES </span>
                                                          <input type="text" class="form-control col-md-8" name="nombre" disabled value="<?php // echo $habitacion->cat->descripcion; ?>"  placeholder="Ingrese nombre">
                                                        </div>
                                                      </div>

                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">SERVICIOS</span>
                                                            {{-- <select id="segr_name" name="segr_name" data-size="2" data-width="100%" class="selectpicker" multiple data-value="{{segr_name}}" title="Seleccione Grupo de Servicio"> --}}
                                                              {{-- <option id="0" value="0">0</option> --}}
                                                            {{-- <select   data-size="2" data-width="100%" palceholder="hola" data-id="{{$habitacion->cat->id}}" title="Seleccione Servicio" name="horario" id="horario" class="selval form-control select2">
                                                                <option value="0"></option>
                                                                @foreach($horarios as $horario)
                                                                        <option value="{{$horario->id}}">{{$horario->nombre}}</option>
                                                                    @endforeach
                                                                </select> --}}
                                                            {{-- <input class="text-black" id="tasaDolar" name="tasaDolar" value="{{$tasaDolarHabitacion->tasa}}" type="hidden"> --}}
                                                            {{-- <input class="text-black" id="tasaPeso" name="tasaPeso" value="{{$tasaPesoHabitacion->tasa}}" type="hidden"> --}}
                                                        </div>
                                                    </div>
                                                    <div class="text-black detalle" id="detalle">

                                                    </div>
                                                    {{-- <input type="hidden" class="form-control catid ver horario" name="horario_id" id="horario_id" data-id="{{$habitacion->cat->id}}" value=""  placeholder="Ingrese nombre"> --}}
                                                    {{-- <input type="hidden" class="form-control catid ver precio" name="precio_id" id="precio_id" data-id="{{$habitacion->cat->id}}" value=""  placeholder="Ingrese nombre"> --}}

                                                </div>
                                            </div>

                                    </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                                        <button class="btn btn-outline" type="submit"><i class='glyphicon glyphicon-search'></i> Procesar Servicio</button>
                                        {{-- <a href="{{URL::action('ResepcionController@show', $habitacion->id.'_'.$habitacion->cat->id)}}"> class="btn btn-outline">Procesar Servicio</a> --}}
                                      </div>
                                    </form>
                                    </div>
                                    <!-- /.modal-content -->
                                    </div>
                                  <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>








                              <div class="modal fade bs-example-modal-xm" id="myModal1<?php echo $habitacion->id; ?>" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-lg modal-danger">
                                  <div class="modal-dialog">
                                    <div class="modal-content">

                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title"><span class="fa fa-warning"></span> HABITACIÓN OCUPADA</h4>
                                      </div>

                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
                                        <a href="index.php?view=pre_salida" class="btn btn-outline">Ir a check out</a>
                                      </div>

                                    </div>
                                    <!-- /.modal-content -->
                                  </div>
                                  <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                              </div>

                              @endforeach
                            </div>
                            <!-- /.tab-pane -->


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

// focusMethod = function getFocus() {
//                 document.getElementById(".selval").focus();
//                 $(".selval").val('default');
//                 $(".selval").selectpicker("refresh");
//             }

    $(function() {
        $('.refrescar').on('click', function() {
            $(".selval").val('default');
                $(".selval").selectpicker("refresh");
            $('.detalle').hide('swing');

        });
    });

    // $(function() {
    //     $('.refrescarLimpiesa').on('click', function() {
    //         // alert('limpiesa');
    //         $(".selvalLimpiesa").val('default');
    //             $(".selvalLimpiesa").selectpicker("refresh");
    //         $('.observacionLimpiesa').val('');

    //     });
    // });

$('.detalle').hide();
    $(function() {
        $('.selval').on('change', function() {
            $('.detalle').hide("swing");
            let valor = this.value;
            let catid = $(this).attr('data-id');
            let texto = this.options[this.selectedIndex].text;

            // console.log('valor = '+valor+' catid= '+catid);

            $('.horario').val(valor);


            if ($.trim(valor != '')) {
                let tasaDolar = $('#tasaDolar').val();
                let tasaPeso = $('#tasaPeso').val();
                $.ajax({
                    type: 'get',
                    url: '{{ url ("precio") }}',
                    data: "cat_id=" + catid+"&horario_id=" + valor,
                    success: function (precio) {

                if (precio.length) {
                    $('.precio').val(precio[0].id);
                    // console.log(precio);
                    // console.log('precio = '+precio[0].precio+' precioBolivar = '+precio[0].precio*tasaDolar+' precioPeso = '+precio[0].precio*tasaPeso+''+'');

                    $('.detalle').html('<div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Dolar</span><h5 class="description-header">$'+formatMoney(precio[0].precio)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Pesos</span><h5 class="description-header">$'+formatMoney(precio[0].precio*tasaPeso)+'</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Bolivares</span><h5 class="description-header">Bs.'+formatMoney(precio[0].precio*tasaDolar)+'</h5></div></div>');







                    $('.detalle').show("swing");
                    // $('#origen').append("<option value='0'>Selecciones Producto a Descargar</option>");
                            // // alert(origens[0].nombre);
                            // for(var i = 0; i < origens.length; i++){
                            // $('#origen').append('<option value="'+ origens[i].nombre +'_'+origens[i].stock+'_'+origens[i].unidades+'_'+origens[i].vender_al+'_'+origens[i].id+'">'+ origens[i].nombre +'-'+ origens[i].codigo +'</option>');
                            // }
                        }
                    }
                });

            }

    });

//     $(function(){
//   $(".ver").click(function(){
//     var valor = $(this).attr('data-id')
//     alert(valor);
//     // $("#resultado").html(valor)
//   })
// })


});

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
                try {
                    decimalCount = Math.abs(decimalCount);
                    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                    const negativeSign = amount < 0 ? "-" : "";

                    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                    let j = (i.length > 3) ? i.length % 3 : 0;

                    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g,
                        "$1" +
                        thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
                } catch (e) {
                    console.log(e)
                }
            };
</script>
@endpush
@endsection
