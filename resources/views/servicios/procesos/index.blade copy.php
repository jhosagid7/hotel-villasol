@extends ('layouts.admin3')
@section('contenido')

<?php
date_default_timezone_set('America/Lima');
     $hoy = date("Y-m-d");
   $hora = date("H:i:s");
?>
<style type="text/css">
.table > tbody > tr > td{
    padding: 0px !important;
}
.input-group {
    position: relative;
    display: table;
    border-collapse: separate;
    width: 100%;
}


</style>
@section('styles')
{{-- <link rel="stylesheet" href="{{asset('dist/css/jquery-ui.css')}}"> --}}



@endsection





        <div class="box">

            <div style="background-color: #e7eaeb" class="box-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                        <!-- Custom Tabs (Pulled to the right) -->
                        <div class="nav-tabs-custom">
                          <ul class="nav nav-tabs pull-right">




                            <li class="pull-left header"><i class="fa fa-hotel"></i> @isset($title)
                                {{$title}}
                                @else
                                {!!"PROCESAR HABITACIÓN"!!}
                            @endisset</li>
                          </ul>
                          <div style="background-color: #e7eaeb" class="tab-content">
                            <div class="row">
                            {{-- <section class="content-header">
                                <h1 >
                                  <span class="fa fa-hotel"></span> PROCESAR HABITACIÓN
                                  <small>Avance</small>
                                </h1>
                                <ol class="breadcrumb">
                                  <li><a href="index.php?view=reserva"><i class="fa fa-home"></i> Inicio</a></li>
                                  <li><a href="#">Recepción</a></li>
                                  <li class="active">Procesar</li>
                                </ol>
                          </section> --}}
                          </div>

                          <div class="row">
                          <section class="content">

                            @if (isset($habitacion->id))

                                          @if ($habitacion)
                                            {{-- si hay habitacion --}}

                                  <div class="box box-default">
                                      <div class="box-header with-border">
                                        <h3 class="box-title">Datos de la habitación</h3>
                                      </div>
                                      <!-- /.box-header -->
                                      <div class="box-body">
                                        <div class="table-responsive">
                                          <table class="table no-margin">

                                            <tbody style="padding: 0px;">
                                            <tr style="padding: 0px;">
                                              <td><h4 class="text-primary" style="margin-top: 0px !important;">Nombre:</h4></td>
                                              <td>{{$habitacion->nombre}}</td>
                                              <td><h4 class="text-primary" style="margin-top: 0px !important;">Tipo:</h4></td>
                                              <td>
                                                <div class="sparkbar" data-color="#00a65a" data-height="20">{{$habitacion->cat->nombre}}</div>
                                              </td>
                                            </tr>
                                            <tr style="padding: 0px;">
                                              <td><h4 class="text-primary" style="margin-top: 0px !important;">Detalles:</h4></td>
                                              <td>{{$habitacion->cat->descripcion}}</td>
                                              <td><h4 class="text-primary" style="margin-top: 0px !important;">Estado:</h4></td>
                                              <td>
                                                <div class="sparkbar" data-color="#f39c12" data-height="20"><span class="label label-success">DISPONIBLE</span></div>
                                              </td>
                                            </tr>

                                            </tbody>
                                          </table>

                                        </div>
                                        <!-- /.table-responsive -->
                                      </div>

                                    </div>
                                    <!-- /.box -->


                          <form class="form-horizontal" method="post" id="addproduct" action="index.php?view=addproceso" role="form">
                                  <div class="box box-default">

                                      <div class="box-body">
                                        <div class="table-responsive">

                                          <div class="col-md-6">
                                          <table class="table no-margin">
                                            <tr>
                                              <th colspan="4" style="text-align: center;">DATOS DEL CLIENTE</th>
                                            </tr>
                                            <tbody style="padding: 0px;">

                                            <tr style="padding: 0px;">


                                    <td colspan="2">

                                        <!-- Date dd/mm/yyyy -->
                                        <div class="form-group">
                                          <label>Seleccione Cliente:</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-globe"></i>
                                            </div>
                                            <select name="buscarCliente" id="buscarCliente" class="form-control selectpicker"
                                            data-live-search="true">
                                            <option value="0">Ingrese cliente para buscar</option>
                                            @foreach ($clientes as $cliente)
                                                <option value="{{ $cliente->id }}_{{ $cliente->nombre }}_{{ $cliente->num_documento }}_{{ $cliente->direccion }}">{{ $cliente->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-addon">
                                            <i class="fa fa-search-plus"></i>
                                          </div>
                                          </div>
                                          <!-- /.input group -->
                                        </div>

                                        {{-- <div class="form-group">
                                          <label>Tipo de Documento:</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-globe"></i>
                                            </div>
                                            <select class="form-control" name="tipo_documento">
                                              <option value="1">C.I.</option>
                                              <option value="2">PASAPORTE</option>
                                              <option value="3">R.U.T</option>
                                            </select>
                                          </div>
                                          <!-- /.input group -->
                                        </div> --}}

                                        <div class="form-group">
                                          <label>Documento:</label>

                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa  fa-arrow-circle-o-right"></i>
                                            </div>
                                            <input type="text" class="form-control" name="num_documento" id="num_documento" required="required" placeholder="Ingrese número de documento">
                                            <input type="hidden" id="id">
                                            {{-- <div class="input-group-addon">
                                                <i class="fa fa-search-plus"></i>
                                              </div> --}}
                                          </div>
                                          <!-- /.input group -->
                                        </div>

                                        <div class="form-group">
                                          <label>Nombres:</label>

                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-user-secret"></i>
                                            </div>
                                            <input type="text" class="form-control" name="nombre" id="nombre"  required placeholder="Ingrese nombres" >
                                          </div>
                                          <!-- /.input group -->
                                        </div>

                                        <div class="form-group">
                                          <label>Dirección:</label>

                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-map-marker"></i>
                                            </div>
                                            <input type="text" class="form-control" name="direccion" id="direccion"  placeholder="Ingrese direccion (No es obligatorio)"  data-mask>
                                          </div>
                                          <!-- /.input group -->
                                        </div>



                          </td>

                                            </tr>




                                            </tbody>
                                          </table>
                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-5">
                                           <table class="table no-margin">
                                           <thead>
                                            <tr>
                                              <th colspan="4" style="text-align: center;">DATOS DEL ALOJAMIENTO</th>
                                            </tr>
                                            </thead>
                                            <tbody style="padding: 0px;">

                                                              <tr style="padding: 0px;">


                                <td colspan="3">

                                        <!-- Date dd/mm/yyyy -->
                                        <div class="form-group">
                                          <label>Servicio:</label>

                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-globe"></i>
                                            </div>
                                        <input type="text" class="form-control" name="horario" id="horario" placeholder="Ingrese Servicio" value="{{$horario->nombre}}">
                                          </div>
                                          <!-- /.input group -->
                                        </div>




                                          <div class="form-group">
                                              <label>Fecha y hora de entrada:</label>

                                              <div class="input-group">
                                                  <div class="input-group-addon">
                                                      <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="date" class="form-control" name="fecha_entrada" value="<?php echo $hoy; ?>"  data-mask>
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input type="time" class="form-control" name="hora_entrada" value="<?php echo $hora; ?>"  data-mask>
                                        </div>
                                        <!-- /.input group -->
                                    </div>

                                    <div class="form-group">
                                        <label>Fecha y hora de salida:</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                              <input type="date" class="form-control" name="fecha_entrada" value="<?php echo $hoy; ?>"  data-mask>
                                              <div class="input-group-addon">
                                                  <i class="fa fa-clock-o"></i>
                                                </div>
                                                <input type="time" class="form-control" name="hora_salida" id="hora_salida" value="{{$horario->hasta}}"  data-mask>
                                            </div>
                                            <!-- /.input group -->
                                        </div>


                                        <div class="form-group">

                                            <label>Precio:</label>
                                            <div class="input-group">
                                            <div class="col-sm-4 col-xs-6">
                                                <div class="description-block border-right">
                                                    <span class="description-percentage text-primary"><i class="fa fa-caret-up"></i> Dolar</span>
                                                    <h5 class="description-header">${{floatval($precio->precio)}}</h5>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-6">
                                                <div class="description-block border-right">
                                                    <span class="description-percentage text-primary"><i class="fa fa-caret-up"></i> Pesos</span>
                                                    <h5 class="description-header">${{number_format($precio->precio*$tasaPesoHabitacion->tasa,2,',','.')}}</h5>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-6">
                                                <div class="description-block border-right">
                                                    <span class="description-percentage text-primary"><i class="fa fa-caret-up"></i> Bolivares</span>
                                                    <h5 class="description-header">Bs.{{number_format($precio->precio*$tasaDolarHabitacion->tasa,2,',','.')}}</h5>
                                                </div>
                                            </div>
                                            </div>
                                            <label>Forma de pago:</label>
                                            <div class="input-group">

                                                <div class="container-fluit small-box">
                                                    <div class="row">
                                                        <div
                                                            class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12 small">
                                                            <a href="#" data-toggle="modal" data-target="#dolar"  class="btn btn-xs btn-primary btn-block col-lg-pull-2 small">Dolar</a>

                                                        </div>
                                                        <div
                                                            class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12">
                                                            <button id='bt_addP' type='button'
                                                                class='btn btn-xs btn-primary btn-block col-lg-pull-2 small'>Peso</button>
                                                        </div>
                                                        <div
                                                            class="panel-group col-lg-3 col-sm-3 col-md-3 col-xs-12 small">
                                                            <button id='bt_addTP' type='button'
                                                                class='btn btn-xs btn-primary btn-block col-lg-pull-2 small'>Punto/Trans</button>
                                                        </div>
                                                        <div
                                                            class="panel-group col-lg-2 col-sm-2 col-md-2 col-xs-12 small">
                                                            <button id='bt_addM' type='button'
                                                                class='btn btn-xs btn-primary btn-block col-lg-pull-2 small'>Mixto</button>
                                                        </div>
                                                        <div
                                                            class="panel-group col-lg-3 col-sm-3 col-md-3 col-xs-12 small">
                                                            <button id='bt_addE' type='button'
                                                                class='btn btn-xs btn-primary btn-block col-lg-pull-2 small'>Efectivo</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- /.input group -->
                                        </div>

                                           <div class="box-footer">
                                          <a href="index.php?view=recepcion" class="btn btn-danger">Cancelar</a>
                                          <input type="hidden" name="id_habitacion" value="<?php echo $habitacion->id; ?>">
                                          <button type="submit" class="btn btn-success pull-right">Registrar ingreso</button>
                                        </div>

                          </td>

                                            </tr>



                                            </tbody>
                                          </table>
                                        </div>

                                        </div>
                                        <!-- /.table-responsive -->
                                      </div>

                                    </div>

                            </form>
                                    <!-- /.box -->

                                    @else
                                     <h4 class='alert alert-success'>NO EXISTE ESTA HABITACIÓN</h4>

                                      @endif


                               @else
                               <h4 class='alert alert-success'>NO SE SELECCIONÓ HABITACIÓN</h4>
                                @endif


                          </section>

                          </div>

                            <!-- /.tab-pane -->
                          </div>
                          <!-- /.tab-content -->
                        </div>
                        <!-- nav-tabs-custom -->

                </div>
        </div>
        </div>
        <div id="countdown2"></div>
        <div class="countdown"></div>

        <div id="resultado">

        </div>
        <div id="minuto">

        </div>
        <div id="segundo">

        </div>
    </section>
    <div class="modal fade bs-example-modal-xm refrescar" id="dolar" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-primary">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{route('proceso')}}" method="post">
                        @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><span class="fa fa-spinner"></span> PROCESAR PAGO EN DOLAR </h4>
                    </div>
                    <div class="modal-body" style="background-color:#fff !important;">

                        <div class="row">
                            <div class="col-md-offset-1 col-md-10">

                              <h1 class="text-black">Costo del Servicio: $ {{floatval($precio->precio)}}</h1>

                              <div class="box text-black">
                                <div class="box-header">
                                    <h3 class="box-title"><strong>Pagado: &nbsp;&nbsp;&nbsp;</strong>  <strong class="text-primary" id="totold"> 0.00</strong></h3>
                                    <input name="TotalDolar" id="TotalDolar" type="hidden" class="form-control"  value="{{floatval($precio->precio)}}">
                                    <input name="TotalDolarReal" id="TotalDolarReal" type="hidden" class="form-control"  value="{{floatval($precio->precio)}}">
                                    <div id="vueltosContainer" class="box-tools pull-right">
                                        <h3 class="box-title "><strong>Vueltos: &nbsp;&nbsp;&nbsp;</strong>  <strong class="text-danger" id="vueltos"> 0.00</strong></h3>
                                      </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <div class="table-responsive">
                                  <table class="table table-condensed">
                                    <tbody>
                                        <tr>

                                            <th>Denominacion</th>
                                            <th>Cantidad</th>
                                            <th>Valor</th>
                                        </tr>

                                        @php
                                        $i = 0;
                                    @endphp
                                        @foreach ($denominacion_dolar as $denod)
                                    <tr>

                                      <td>{{ $denod->denominacion }}</td>
                                      <td><div class="col-sm-6 col-xl">
                                        <input name="dcantidad[{{$i}}]" id="dcantidad_{{$i}}" type="number" size="5px" class="form-control enteros input-sm" value="">
                                        </div>
                                            <input name="DsubTotald[{{$i}}]" id="DsubTotald_{{$i}}" type="hidden" class="form-control"  value="">
                                      </td>
                                      <td>{{ $denod->valor }}</td>
                                      <input name="dvalor[{{$i}}]"  id="dvalor_{{$i}}" type="hidden" class="form-control" value="{{ $denod->valor }}">
                                        <input name="ddenominacion[{{$i}}]"  id="ddenominacion_{{$i}}" type="hidden" class="form-control" value="{{ $denod->denominacion }}">
                                        <input name="dtipo[{{$i}}]"  id="dtipo_{{$i}}" type="hidden" class="form-control" value="{{ $denod->tipo }}">
                                    </tr>
                                    @php
                                    $i++;
                                    @endphp
                                  @endforeach


                                  </tbody></table></div>
                                </div>
                                <!-- /.box-body -->
                              </div>





                            <div class="text-black detalle" id="detalle">

                            </div>
                            <input type="hidden" class="form-control catid ver horario" name="horario_id" id="horario_id" data-id="{{$habitacion->cat->id}}" value=""  placeholder="Ingrese nombre">
                            <input type="hidden" class="form-control catid ver precio" name="precio_id" id="precio_id" data-id="{{$habitacion->cat->id}}" value=""  placeholder="Ingrese nombre">

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
  <!-- /.content -->
  <div class="clearfix"></div>
@push('sciptsMain')
<script src="{{asset('dist/js/moment.min.js')}}"></script>
<script>

    var importe=prompt("Indica una cantidad: ");

    document.write("<p>El cambio de la cantidad "+importe+"</p>");

    // indicamos todas las monedas posibles
    var monedas=Array(100, 50, 20, 10, 5, 2, 1);

    // creamos un array con la misma cantidad de monedas
    // Este array contendra las monedas a devolver
    var cambio=Array(0,0,0,0,0,0,0);

    // Recorremos todas las monedas
    for(var i=0; i<monedas.length; i++)
    {

        // Si el importe actual, es superior a la moneda
        if(importe>=monedas[i])
        {

            // obtenemos cantidad de monedas
            cambio[i]=parseInt(importe/monedas[i]);

            // actualizamos el valor del importe que nos queda por didivir
            importe=(importe-(cambio[i]*monedas[i])).toFixed(2);
        }
    }

    // Bucle para mostrar el resultado
    for(i=0; i<monedas.length; i++)
    {
        if(cambio[i]>0)
        {
            if(monedas[i]>=1)
                console.log("Hay: "+cambio[i]+" billetes de: "+monedas[i]+" &dolar;<br>");
            else
                console.log("Hay: "+cambio[i]+" monedas de: "+monedas[i]+" &dolar;<br>");
        }
    }

    </script>
<script>



moment.locale('es');
// console.log(moment.locale()); // en
    const hoy = moment();
console.log(moment().format('MMMM Do YYYY, h:mm:ss a'));

$("#buscarCliente").change(showValues);

function showValues() {
                // alert('show');
                datosArticulo = document.getElementById('buscarCliente').value.split('_');
                // $("#jprecio_venta").val(datosArticulo[2]);
                $("#cliente_id").val(datosArticulo[0]);
                $("#nombre").val(datosArticulo[1]);
                $("#num_documento").val(datosArticulo[2]);
                $("#direccion").val(datosArticulo[3]);

            }



    //         var duration = moment.duration({
    //         'minutes': 1,
    //         'seconds': 5

    //         });

    // var timestamp = new Date(0, 0, 0, 2, 10, 30);
    // var interval = 1;
    // var timer = setInterval(function() {
    //   timestamp = new Date(timestamp.getTime() + interval * 1000);

    //   duration = moment.duration(duration.asSeconds() - interval, 'seconds');
    //   var min = duration.minutes();
    //   var sec = duration.seconds();

    //   sec -= 1;
    //   if (min < 0) return clearInterval(timer);
    //   if (min < 10 && min.length != 2) min = '0' + min;
    //   if (sec < 0 && min != 0) {
    //     min -= 1;
    //     sec = 59;
    //   } else if (sec < 10 && sec.length != 2) sec = '0' + sec;

    //   if(min == 1 && sec == 0){
    //     alert('hola');
    //   }

    //   $('.countdown').text(min + ':' + sec);
    //   if (min == 0 && sec == 0)

    //     clearInterval(timer);


    // }, 1000);

    // if (min < 4) alert('Faltan '+ min);






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

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////



    //////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
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

{{-- <script type="text/javascript">
    var final = moment("2020-11-19 09:00:00 am");
    setInterval(function() {
        var inicio = moment();
        var duracion = final.diff(inicio);
        var intervalo = moment(duracion);
        var mes = intervalo.month()+1;
        var diaDelMes = intervalo.date();
        var hora = intervalo.hour();
        var minuto = intervalo.minute();
        var segundo = intervalo.second();
        var resultado = (intervalo.format("MM/DD HH:mm:ss"));
        $("#resultado").html(mes + " Meses " + diaDelMes + " Dias " + hora + " Horas " + minuto + " Minutos " + segundo + " Segundos");
        $("#minuto").html(minuto);
        $("#segundo").html(segundo);
    }, 1000);

</script> --}}

<script>
    var end = new Date('11/19/2020 7:08 AM');

        var _second = 1000;
        var _minute = _second * 60;
        var _hour = _minute * 60;
        var _day = _hour * 24;
        var timer;


        function showRemaining() {
            var now = new Date();
            var distance = end - now;

            if (distance < 0) {

                clearInterval(timer);
                document.getElementById('countdown2').innerHTML = 'EXPIRED!';

                return;
            }


            var days = Math.floor(distance / _day);
            var hours = Math.floor((distance % _day) / _hour);
            var minutes = Math.floor((distance % _hour) / _minute);
            var seconds = Math.floor((distance % _minute) / _second);


            document.getElementById('countdown2').innerHTML = days + ' dias, ';
            document.getElementById('countdown2').innerHTML += hours + ' horas, ';
            document.getElementById('countdown2').innerHTML += minutes + ' minutos y ';
            document.getElementById('countdown2').innerHTML += seconds + ' segundos';

            if (minutes == 1 && seconds == 0) {


console.log('Falta '+minutes);

// return;
}

        timer = setInterval(showRemaining, 1000);
        }



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Comienso de funciones para multiplicar valor por candiad y crea un subtotal para dolares
    $(document).ready(function() {
        $("#vueltosContainer").hide();

      $("#dcantidad_0").change(function() {
          Dcantidad = $("#dcantidad_0").val();
          Dvalor = $("#dvalor_0").val();
          DsubTotald = Dcantidad*Dvalor;
          $("#DsubTotald_0").val(DsubTotald);
          SupTotalToTotal()
      });

      $("#dcantidad_1").change(function() {
          Dcantidad1 = $("#dcantidad_1").val();
          Dvalor1 = $("#dvalor_1").val();
          DsubTotald1 = Dcantidad1*Dvalor1;
          $("#DsubTotald_1").val(DsubTotald1);
          SupTotalToTotal()
      });

      $("#dcantidad_2").change(function() {
          Dcantidad2 = $("#dcantidad_2").val();
          Dvalor2 = $("#dvalor_2").val();
          DsubTotald2 = Dcantidad2*Dvalor2;
          $("#DsubTotald_2").val(DsubTotald2);
          SupTotalToTotal()
      });

      $("#dcantidad_3").change(function() {
          Dcantidad3 = $("#dcantidad_3").val();
          Dvalor3 = $("#dvalor_3").val();
          DsubTotald3 = Dcantidad3*Dvalor3;
          $("#DsubTotald_3").val(DsubTotald3);
          SupTotalToTotal()
      });

      $("#dcantidad_4").change(function() {
          Dcantidad4 = $("#dcantidad_4").val();
          Dvalor4 = $("#dvalor_4").val();
          DsubTotald4 = Dcantidad4*Dvalor4;
          $("#DsubTotald_4").val(DsubTotald4);
          SupTotalToTotal()
      });

      $("#dcantidad_5").change(function() {
          Dcantidad5 = $("#dcantidad_5").val();
          Dvalor5 = $("#dvalor_5").val();
          DsubTotald5 = Dcantidad5*Dvalor5;
          $("#DsubTotald_5").val(DsubTotald5);
          SupTotalToTotal()
      });

      $("#dcantidad_6").change(function() {
          Dcantidad6 = $("#dcantidad_6").val();
          Dvalor6 = $("#dvalor_6").val();
          DsubTotald6 = Dcantidad6*Dvalor6;
          $("#DsubTotald_6").val(DsubTotald6);
          SupTotalToTotal()
      });
    //Fin de funciones para multiplicar valor por candiad y crea un subtotal para dolares
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Comienso de funcion para sumar los subtotales de dolares
      function SupTotalToTotal(){
        DsubTotald = $("#DsubTotald_0").val();
        DsubTotald_1 = $("#DsubTotald_1").val();
        DsubTotald_2 = $("#DsubTotald_2").val();
        DsubTotald_3 = $("#DsubTotald_3").val();
        DsubTotald_4 = $("#DsubTotald_4").val();
        DsubTotald_5 = $("#DsubTotald_5").val();
        DsubTotald_6 = $("#DsubTotald_6").val();
        let TotalDolar = $("#TotalDolar").val();
        var TotalDolarReal = $("#TotalDolarReal").val();

        totalsuma = circumference(DsubTotald)+circumference(DsubTotald_1)+circumference(DsubTotald_2)+circumference(DsubTotald_3)+circumference(DsubTotald_4)+circumference(DsubTotald_5)+circumference(DsubTotald_6);
        restaTotal =  TotalDolarReal - totalsuma;
        $("#total_dolar").val(totalsuma.toFixed(2));
        $("#totold").html(formatMoney(totalsuma, 2, ',', '.'));
        $("#dtotal").html(formatMoney(totalsuma, 2, ',', '.'));
        $("#TotalDolar").val(restaTotal);

        if (restaTotal < 0) {
        $("#vueltos").html(restaTotal);
        $("#vueltosContainer").show();

        } else {
            $("#vueltosContainer").hide();
            $("#vueltos").html(0);
        }


      };

    });
    //Fin de funcion para sumar los subtotales de dolares
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Comienso de funcion formatear o parcear los datos a flotantes (Decimal)
    function circumference(r) {
            if (Number.isNaN(Number.parseFloat(r))) {
            return 0;
            }
            return parseFloat(r);
        }
    //Fin de funcion formatiar o parcear los datos a flotantes (Decimal)
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    </script>
@endpush
@endsection
