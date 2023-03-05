@extends ('layouts.admin3')
@section('contenido')
    <style type="text/css">
        .bootstrap-select {
            width: 400px !important;
        }
    </style>



    <div class="box">

        <div style="background-color: #e7eaeb" class="box-body">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @include('custom.message')
                <!-- Custom Tabs (Pulled to the right) -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs pull-right">




                        <li class="pull-left header"><i class="fa fa-th"></i> @isset($title)
                                {{ $title }}
                            @else
                                {!! 'Sistema' !!}
                            @endisset
                        </li>
                    </ul>

                    <div style="background-color: #e7eaeb" class="tab-content">


                        {{-- <b>How to use: {{$level->id}}</b> --}}
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($servicios as $servicio)
                            {{-- {{$habitacion->nombre}} --}}
                            {{-- {{$servicio}} --}}
                            @if ($servicio->habitacion->status == 'Ocupada')
                                <a href="{{ URL::action('CheckoutController@show', $servicio->id) }}"
                                    data-target="#myModal{{ $servicio->habitacion_id }}" class="small-box-footer">
                                    <div class="col-md-3 col-sm-6 col-xs-12">

                                        <div class="info-box">
                                            {{-- @if ($habitacion->status == 'Disponible')
                                        <span class="info-box-icon bg-green"><i class="fa fa-hotel"></i></span>
                                    @elseif ($habitacion->status == 'Ocupada') --}}
                                            @php

                                                if ($servicio->modo_pago == 'Contado') {
                                                    $color = '';
                                                }
                                                if ($servicio->modo_pago == 'Cortesía') {
                                                    $color = 'bg-orange';
                                                }

                                                if ($servicio->modo_pago == 'Crédito') {
                                                    $color = 'bg-green';
                                                }
                                            @endphp
                                            <span id="bg_{{ $i }}" class="info-box-icon bg-blue-gradient"><i
                                                    class="fa fa-bed fa-circle {{ $color ?? '' }}"></i></span>
                                            {{-- @elseif ($habitacion->status == 'Limpieza')
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-bed"></i></span>
                                    @elseif ($habitacion->status == 'Finalizando')
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-bed"></i></span>
                                        @elseif ($habitacion->status == 'En reparacion')
                                        <span class="info-box-icon bg-default"><i class="fa fa-bed"></i></span>
                                    @endif --}}

                                            <div class="tile-body">
                                                <h4 style="text-align: center;"><i class="fa fa-bed"></i><b>
                                                        {{ $servicio->nombre_habitacion }}</b></h4>
                                            </div>

                                            <div class="info-box-content">
                                                <span class="info-box-text">Cerrar Servicio
                                                    {{ $servicio->tipo_habitacion }}</span>

                                                {{-- <span class="info-box-text">Cerrar <i class="fa fa-spinner"></i></span> --}}

                                                <span id="bg_{{ $i }}"
                                                    class="info-box-text contador">{{ $servicio->fecha_salida . ' ' . $servicio->hora_salida }}</span>
                                                @php
                                                    $i++;
                                                @endphp
                                            </div>

                                            <!-- /.info-box-content -->

                                        </div>
                                        <!-- /.info-box -->

                                    </div>
                                </a>
                            @endif
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
                            url: '{{ url('precio') }}',
                            data: "cat_id=" + catid + "&horario_id=" + valor,
                            success: function(precio) {

                                if (precio.length) {
                                    $('.precio').val(precio[0].id);
                                    // console.log(precio);
                                    // console.log('precio = '+precio[0].precio+' precioBolivar = '+precio[0].precio*tasaDolar+' precioPeso = '+precio[0].precio*tasaPeso+''+'');

                                    $('.detalle').html(
                                        '<div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Dolar</span><h5 class="description-header">$' +
                                        formatMoney(precio[0].precio) +
                                        '</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Pesos</span><h5 class="description-header">$' +
                                        formatMoney(precio[0].precio * tasaPeso) +
                                        '</h5></div></div><div class="col-sm-4 col-xs-6"><div class="description-block border-right"><span class="description-percentage text-green"><i class="fa fa-caret-up"></i> Bolivares</span><h5 class="description-header">Bs.' +
                                        formatMoney(precio[0].precio * tasaDolar) +
                                        '</h5></div></div>');







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






            window.onload = function() {
                // Obtener todos los contenedores por clase
                let spans = document.querySelectorAll('.contador');

                // Recorrer contenedores para crear contador
                let bg = 0;
                spans.forEach(function(item) {

                    console.log(bg);
                    // Obtener fecha y hora
                    let cuando = new Date(item.innerText);
                    // let expirado =item.innerText;
                    // var cuando = new Date('01/04/2021 4:41 PM');



                    // console.log(cuando);
                    // Crear intervalo cada segundo y enviando contenedor y fecha
                    let timer = setInterval(cuenta, 1000, item, cuando, bg);
                    bg = bg + 1;
                });
            }

            function cuenta(item, cuando, bg) {
                // console.log(bg);
                // Fecha y hora actual
                let ahora = new Date();
                // Obtener diferencia en segundos
                var distance = cuando - ahora;

                if (distance < 0) {

                    clearInterval(timer);
                    item.innerHTML = 'EXPIRED!';
                    item.classList.add("text-red");
                    // console.log(item);


                    let expirado = item.innerText;
                    // var cuando = new Date('01/04/2021 4:41 PM');
                    if (expirado == 'EXPIRED!') {
                        // console.log('si');
                        $('#bg_' + bg).removeClass('bg-blue-gradient');
                        $('#bg_' + bg).removeClass('bg-yellow-gradient');
                        $('#bg_' + bg).addClass('bg-red-gradient');
                    }
                    return;
                }



                var _second = 1000;
                var _minute = _second * 60;
                var _hour = _minute * 60;
                var _day = _hour * 24;
                var timer;


                var days = Math.floor(distance / _day);
                var hours = Math.floor((distance % _day) / _hour);
                var minutes = Math.floor((distance % _hour) / _minute);
                var seconds = Math.floor((distance % _minute) / _second);


                item.innerText = ' D = ' + days;
                item.innerText += ', H = ' + hours + ':';
                item.innerText += minutes + ':';
                item.innerText += seconds;


                if (days <= 0 && hours <= 0 && minutes <= 30 && seconds <= 00) {
                    console.log('Falta ' + minutes);
                    $('#bg_' + bg).removeClass('bg-blue-gradient');
                    $('#bg_' + bg).addClass('bg-yellow-gradient');


                }

            }
        </script>
    @endpush
@endsection
