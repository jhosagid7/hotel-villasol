@extends('layouts.admin3')

@section('contenido')
    <section class="content-header">
        <h1>
            Actualizaciones del Sistema
            <small>Gestión de versiones y mejoras continuas</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
            <li><a href="#">Sistema</a></li>
            <li class="active">Actualizaciones</li>
        </ol>
    </section>

    <section class="content">
        @include('custom.message')

        <div class="row">
            {{-- Columna izquierda: Estado Actual --}}
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-server"></i> Versión Instalada Actualmente</h3>
                    </div>
                    <div class="box-body">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Commit Actual</b> <a class="pull-right"><span class="label label-primary" style="font-size: 13px;">{{ $currentCommit }}</span></a>
                            </li>
                            <li class="list-group-item">
                                <b>Rama Activa</b> <a class="pull-right"><code>{{ $currentBranch }}</code></a>
                            </li>
                            <li class="list-group-item">
                                <b>Canal de Actualización</b> <a class="pull-right"><span class="badge bg-green">origin/{{ $targetBranch }}</span></a>
                            </li>
                            <li class="list-group-item">
                                <b>Última Modificación</b> <a class="pull-right text-muted">{{ $commitDate }}</a>
                            </li>
                        </ul>

                        <div class="well well-sm" style="margin-top: 15px; margin-bottom: 15px;">
                            <strong><i class="fa fa-commenting-o"></i> Último Cambio:</strong>
                            <p class="text-muted" style="margin-top: 5px; margin-bottom: 0;">{{ $commitMessage }}</p>
                        </div>

                        <button id="btnVerificar" class="btn btn-primary btn-block btn-flat">
                            <i id="iconVerificar" class="fa fa-refresh"></i> <span id="textVerificar">Buscar Actualizaciones en GitHub</span>
                        </button>
                    </div>
                </div>

                {{-- Historial de Actualizaciones --}}
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-history"></i> Historial Reciente de Actualizaciones</h3>
                    </div>
                    <div class="box-body" style="max-height: 250px; overflow-y: auto;">
                        @if(count($history) > 0)
                            <ul class="todo-list">
                                @foreach($history as $item)
                                    <li>
                                        <small class="label label-info"><i class="fa fa-check"></i></small>
                                        <span class="text" style="font-size: 12px;">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted text-center" style="margin: 20px 0;">No hay registros de actualizaciones previas.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Columna derecha: Novedades y Acción de Actualizar --}}
            <div class="col-md-7">
                {{-- Estado inicial o resultado de búsqueda --}}
                <div id="boxResultado" class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-cloud-download"></i> Novedades y Descargas Disponibles</h3>
                    </div>
                    <div class="box-body">
                        {{-- Estado inicial --}}
                        <div id="estadoInicial" class="text-center" style="padding: 40px 20px;">
                            <i class="fa fa-github text-muted" style="font-size: 60px;"></i>
                            <h4 style="margin-top: 15px; color: #555;">Presiona <strong>"Buscar Actualizaciones"</strong> para consultar el repositorio.</h4>
                            <p class="text-muted">El sistema verificará si hay nuevas funciones, mejoras o correcciones en la rama <code>{{ $targetBranch }}</code>.</p>
                        </div>

                        {{-- Cargando --}}
                        <div id="estadoCargando" class="text-center" style="display: none; padding: 40px 20px;">
                            <i class="fa fa-circle-o-notch fa-spin text-primary" style="font-size: 50px;"></i>
                            <h4 style="margin-top: 15px;">Conectando con GitHub...</h4>
                            <p class="text-muted">Por favor espere un momento.</p>
                        </div>

                        {{-- Al día --}}
                        <div id="estadoAlDia" class="text-center" style="display: none; padding: 30px 20px;">
                            <i class="fa fa-check-circle text-green" style="font-size: 60px;"></i>
                            <h3 class="text-green" style="margin-top: 15px; font-weight: bold;">¡El sistema está completamente al día!</h3>
                            <p class="text-muted">Tienes instalada la versión más reciente disponible en GitHub.</p>
                        </div>

                        {{-- Actualizaciones pendientes --}}
                        <div id="estadoConActualizaciones" style="display: none;">
                            <div class="callout callout-warning">
                                <h4><i class="fa fa-bell"></i> ¡Nuevas Actualizaciones Disponibles!</h4>
                                <p id="txtResumenPendientes">Hay cambios listos para ser instalados en el sistema.</p>
                            </div>

                            <h4><i class="fa fa-list-ul"></i> Lista de Cambios y Mejoras:</h4>
                            <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th style="width: 90px;">Commit</th>
                                            <th>Descripción de la Mejora</th>
                                            <th style="width: 130px;">Fecha</th>
                                            <th style="width: 120px;">Autor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaPendientes">
                                        {{-- Llenado dinámico vía JS --}}
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center" style="margin-top: 25px;">
                                <button id="btnIniciarActualizacion" class="btn btn-success btn-lg btn-flat">
                                    <i class="fa fa-download"></i> <strong>Actualizar Sistema Ahora</strong>
                                </button>
                                <p class="text-muted" style="margin-top: 8px; font-size: 12px;">
                                    <i class="fa fa-shield"></i> Se creará un respaldo de seguridad de la base de datos automáticamente antes de aplicar.
                                </p>
                            </div>
                        </div>

                        {{-- Error --}}
                        <div id="estadoError" style="display: none; padding: 20px;">
                            <div class="callout callout-danger">
                                <h4><i class="fa fa-exclamation-triangle"></i> No se pudo verificar la actualización</h4>
                                <p id="txtMensajeError"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal de Confirmación y Proceso de Actualización --}}
    <div class="modal fade" id="modalActualizar" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title"><i class="fa fa-cloud-download"></i> Actualización de Software en Curso</h4>
                </div>
                <div class="modal-body">
                    {{-- Pantalla de confirmación previa --}}
                    <div id="modalConfirmacion">
                        <div class="alert alert-info">
                            <h4><i class="icon fa fa-info-circle"></i> ¿Deseas aplicar la actualización ahora?</h4>
                            El proceso ejecutará los siguientes pasos de forma automática:
                            <ol style="margin-top: 8px;">
                                <li><strong>Respaldo de Base de Datos:</strong> Crea una copia en <code>storage/app/backups/</code>.</li>
                                <li><strong>Descarga de Archivos:</strong> Obtiene la versión más reciente de GitHub.</li>
                                <li><strong>Migraciones de BD:</strong> Aplica cambios necesarios en la estructura de datos.</li>
                                <li><strong>Limpieza de Caché:</strong> Renueva vistas, rutas y configuraciones del sistema.</li>
                            </ol>
                        </div>
                        <p class="text-danger">
                            <i class="fa fa-warning"></i> <strong>Importante:</strong> Durante los segundos que dura la actualización, no cierre esta ventana ni apague el equipo.
                        </p>
                    </div>

                    {{-- Pantalla de progreso y consola en vivo --}}
                    <div id="modalProgreso" style="display: none;">
                        <h4 id="txtPasoActual" class="text-primary"><i class="fa fa-spinner fa-spin"></i> Iniciando actualización...</h4>
                        <div class="progress active progress-striped" style="height: 25px; margin-bottom: 20px;">
                            <div id="barraProgreso" class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" style="width: 10%; font-weight: bold; line-height: 25px;">
                                10%
                            </div>
                        </div>

                        <h5><i class="fa fa-terminal"></i> Registro de Operaciones:</h5>
                        <div id="consolaPasos" style="background: #1e1e1e; color: #a9b7c6; font-family: monospace; font-size: 13px; padding: 15px; border-radius: 4px; height: 180px; overflow-y: auto;">
                            {{-- Llenado dinámico --}}
                        </div>
                    </div>

                    {{-- Pantalla de Éxito --}}
                    <div id="modalExito" style="display: none; text-align: center; padding: 20px;">
                        <i class="fa fa-check-circle text-green" style="font-size: 65px;"></i>
                        <h3 class="text-green" style="font-weight: bold; margin-top: 15px;">¡Actualización Completada Exitosamente!</h3>
                        <p id="txtDetalleExito" style="font-size: 16px;"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="footerConfirmacion">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnConfirmarActualizar" class="btn btn-success"><i class="fa fa-check"></i> Sí, Actualizar Ahora</button>
                    </div>
                    <div id="footerExito" style="display: none;">
                        <button type="button" id="btnRecargar" class="btn btn-primary btn-block btn-lg"><i class="fa fa-refresh"></i> Recargar Sistema</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var checkUrl = "{{ route('sistema.update.check') }}";
    var applyUrl = "{{ route('sistema.update.apply') }}";
    var csrfToken = "{{ csrf_token() }}";

    // Función para cambiar estados visuales
    function mostrarEstado(estado) {
        $('#estadoInicial').hide();
        $('#estadoCargando').hide();
        $('#estadoAlDia').hide();
        $('#estadoConActualizaciones').hide();
        $('#estadoError').hide();

        if (estado === 'cargando') $('#estadoCargando').show();
        if (estado === 'aldia') $('#estadoAlDia').show();
        if (estado === 'actualizaciones') $('#estadoConActualizaciones').show();
        if (estado === 'error') $('#estadoError').show();
    }

    // Botón Verificar Actualizaciones
    $('#btnVerificar').on('click', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var $icon = $('#iconVerificar');
        var $text = $('#textVerificar');

        $btn.attr('disabled', true);
        $icon.addClass('fa-spin');
        $text.text('Consultando GitHub...');
        mostrarEstado('cargando');

        $.ajax({
            url: checkUrl,
            type: 'POST',
            data: { _token: csrfToken },
            dataType: 'json',
            timeout: 60000,
            success: function(res) {
                $btn.attr('disabled', false);
                $icon.removeClass('fa-spin');
                $text.text('Buscar Actualizaciones en GitHub');

                if (!res.success && res.message && res.message.indexOf('Error') !== -1) {
                    $('#txtMensajeError').text(res.message);
                    mostrarEstado('error');
                    return;
                }

                if (res.has_updates && res.commits_behind > 0) {
                    $('#txtResumenPendientes').text('Existen ' + res.commits_behind + ' cambio(s) pendiente(s) por incorporar al sistema.');
                    var html = '';
                    $.each(res.pending_commits, function(idx, c) {
                        html += '<tr>' +
                            '<td><span class="label label-info">' + c.hash + '</span></td>' +
                            '<td><strong>' + c.subject + '</strong></td>' +
                            '<td><small class="text-muted">' + c.date + '</small></td>' +
                            '<td><span class="badge bg-gray">' + c.author + '</span></td>' +
                            '</tr>';
                    });
                    $('#tablaPendientes').html(html);
                    mostrarEstado('actualizaciones');
                } else {
                    mostrarEstado('aldia');
                }
            },
            error: function(xhr, status, error) {
                $btn.attr('disabled', false);
                $icon.removeClass('fa-spin');
                $text.text('Buscar Actualizaciones en GitHub');
                $('#txtMensajeError').text('Error de comunicación con el servidor: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error));
                mostrarEstado('error');
            }
        });
    });

    // Abrir Modal de Confirmación
    $('#btnIniciarActualizacion').on('click', function(e) {
        e.preventDefault();
        $('#modalConfirmacion').show();
        $('#modalProgreso').hide();
        $('#modalExito').hide();
        $('#footerConfirmacion').show();
        $('#footerExito').hide();
        $('#consolaPasos').empty();
        $('#barraProgreso').css('width', '10%').text('10%');
        $('#modalActualizar').modal('show');
    });

    // Confirmar y Ejecutar Actualización
    $('#btnConfirmarActualizar').on('click', function(e) {
        e.preventDefault();
        $('#modalConfirmacion').hide();
        $('#footerConfirmacion').hide();
        $('#modalProgreso').show();

        function agregarLog(texto, color) {
            color = color || '#a9b7c6';
            var hora = new Date().toLocaleTimeString();
            $('#consolaPasos').append('<div style="color: ' + color + ';">[' + hora + '] ' + texto + '</div>');
            var d = $('#consolaPasos');
            d.scrollTop(d.prop("scrollHeight"));
        }

        agregarLog('Iniciando proceso de actualización del sistema...', '#569cd6');
        agregarLog('Paso 1/5: Generando respaldo preventivo de base de datos...');
        $('#barraProgreso').css('width', '25%').text('25%');

        setTimeout(function() {
            agregarLog('Paso 2/5: Resguardando cambios locales mediante git stash...');
            $('#barraProgreso').css('width', '45%').text('45%');
        }, 1200);

        setTimeout(function() {
            agregarLog('Paso 3/5: Descargando archivos actualizados desde GitHub...');
            $('#barraProgreso').css('width', '65%').text('65%');
        }, 2500);

        $.ajax({
            url: applyUrl,
            type: 'POST',
            data: { _token: csrfToken },
            dataType: 'json',
            timeout: 180000,
            success: function(res) {
                if (res.success) {
                    $('#barraProgreso').css('width', '85%').text('85%');
                    agregarLog('Paso 4/5: Verificando y aplicando migraciones de base de datos...');
                    agregarLog('Paso 5/5: Limpiando cachés de Laravel (vistas, rutas, configuración)...');

                    if (res.steps && res.steps.length > 0) {
                        $.each(res.steps, function(i, s) {
                            var color = s.status === 'OK' ? '#6a9955' : '#ce9178';
                            agregarLog('✓ ' + s.step + ': ' + s.detail, color);
                        });
                    }

                    $('#barraProgreso').css('width', '100%').removeClass('progress-bar-primary').addClass('progress-bar-success').text('100%');
                    agregarLog('✓ ' + res.message, '#4ec9b0');

                    setTimeout(function() {
                        $('#modalProgreso').hide();
                        $('#txtDetalleExito').text(res.message);
                        $('#modalExito').show();
                        $('#footerExito').show();
                    }, 1500);
                } else {
                    agregarLog('ERROR: ' + res.message, '#f44747');
                    $('#barraProgreso').removeClass('progress-bar-primary').addClass('progress-bar-danger');
                    alert('Error en la actualización: ' + res.message);
                }
            },
            error: function(xhr, status, error) {
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : error;
                agregarLog('ERROR CRÍTICO: ' + msg, '#f44747');
                $('#barraProgreso').removeClass('progress-bar-primary').addClass('progress-bar-danger');
                alert('Ocurrió un error al procesar la actualización: ' + msg);
            }
        });
    });

    // Recargar página después de actualizar
    $('#btnRecargar').on('click', function() {
        window.location.reload();
    });
});
</script>
@endsection
