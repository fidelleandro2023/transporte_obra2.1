<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=shift_jis">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">

    <style type="text/css">
        .select2-dropdown {
            z-index: 100000;
        }
    </style>

</head>

<body data-ma-theme="entel">
    <main class="main">
        <div class="page-loader">
            <div class="page-loader__spinner">
                <svg viewBox="25 25 50 50">
                    <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                </svg>
            </div>
        </div>

        <header class="header">
            <div class="navigation-trigger" data-ma-action="aside-open" data-ma-target=".sidebar">
                <div class="navigation-trigger__inner">
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                </div>
            </div>

            <div class="header__logo hidden-sm-down" style="text-align: center;">
                <a href="https://www.movistar.com.pe/" title="MOVISTAR"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo MOVISTAR" style="width: 36%; margin-left: -51%"></a>
            </div>

            <?php include('application/views/v_opciones.php'); ?>
        </header>

        <aside class="sidebar sidebar--hidden">
            <div class="scrollbar-inner">
                <div class="user">
                    <div class="user__info" data-toggle="dropdown">
                        <img class="user__img" src="<?php echo base_url(); ?>public/demo/img/profile-pics/8.jpg" alt="">
                        <div>
                            <div class="user__name"><?php echo $this->session->userdata('usernameSession') ?></div>
                            <div class="user__email"><?php echo $this->session->userdata('descPerfilSession') ?></div>
                        </div>
                    </div>


                </div>

                <ul class="navigation">
                    <?php echo $opciones ?>
                </ul>
            </div>
        </aside>



        <section class="content content--full">
            <div class="content__inner">
                <h2>WORKFLOW DE OBSERVACIONES</h2>
                <hr>
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>ESTADO</label>
                                    <select id="selectFEstado" name="selectFEstado" class="select2">
                                        <option>&nbsp;</option>
                                        <?php foreach ($listaEstados->result() as $row) { ?>
                                            <option value="<?php echo $row->id_estado_incidente ?>"><?php echo $row->descripcion ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>CODIGO OBSERVACION</label>
                                    <input id="txtFCodigoIncidente" type="text" class="form-control input-mask" placeholder="Codigo Observacion" autocomplete="off" maxlength="15" style="border-bottom: 1px solid lightgrey">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>MODULO</label>

                                    <select id="selectFModulo" name="selectFModulo" class="select2">
                                        <option>&nbsp;</option>
                                        <?php foreach ($listaModulos->result() as $row) { ?>
                                            <option value="<?php echo $row->id_modulo ?>"><?php echo utf8_decode($row->descripcion) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <br>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">CONSULTAR</button>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>TIPO OBSERVACION</label>
                                    <select id="selectFTipoIncidente" name="selectFTipoIncidente" class="select2">
                                        <option>&nbsp;</option>
                                        <?php foreach ($listaTipoIncidentes->result() as $row) { ?>
                                            <option value="<?php echo $row->id_tipo_incidente ?>"><?php echo $row->descripcion ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($idUsuario != 267 && $idUsuario != 565) { ?>
                                <button class="btn btn-success waves-effect" type="button" onclick="openModalNuevoIncidente()">REGISTRAR</button>
                            <?php } ?>
                        </div>
                        <div id="divTabla" class="table-responsive">
                            <?php echo $tbIncidencias ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- REGISTRAR INCIDENTE -->
    <div class="modal fade" id="modalRegistrarIncidente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left">NUEVA OBSERVACION</h5>
                </div>
                <div class="modal-body">
                    <form id="formRegistrarIncidente" method="post" class="form-horizontal">
                        <div class="row">

                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>MODULO</label>
                                        <select id="selectModulo" name="selectModulo" class="select2 form-control" onchange="getTipoInc();">
                                            <option>&nbsp;</option>
                                            <?php foreach ($listaModulos->result() as $row) { ?>
                                                <option value="<?php echo $row->id_modulo ?>"><?php echo utf8_decode($row->descripcion) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>TIPO OBSERVACION</label>
                                    <select id="selectTipoIncidente" name="selectTipoIncidente" class="select2 form-control">
                                        <option>&nbsp;</option>
                                        <?php foreach ($listaTipoIncidentes->result() as $row) { ?>
                                            <option value="<?php echo $row->id_tipo_incidente ?>"><?php echo $row->descripcion ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>ITEMPLAN</label>
                                    <input type="text" id="txtItemplan" name="txtItemplan" class="form-control" maxlength="13">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>COMENTARIO</label>
                                    <textarea id="txtComentario" name="txtComentario" rows="5" cols="" class="form-control" onkeypress="return soloLetras(event)"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>ADJUNTO</label>
                                    <input type="file" id="fAdjunto" name="fAdjunto" class="form-control" onchange="validarSizeAdjuntoS()" />
                                </div>
                            </div>
                        </div>

                        <div id="mensajeForm4"></div>
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                <button id="btnRegistrarIncidente" type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRechazarIncidente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left">RECHAZAR OBSERVACION</h5>
                </div>
                <div class="modal-body">
                    <form id="formRechazarIncidente" method="post" class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>MOTIVO DE RECHAZO</label>
                                    <textarea id="txtMotivo" name="txtMotivo" rows="5" cols="" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div id="mensajeForm5"></div>
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                <button id="btnRechazarIncidente" type="submit" class="btn btn-primary">Rechazar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAprobarIncidente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left">APROBAR OBSERVACION</h5>
                </div>
                <div class="modal-body">
                    <form id="formAprobarIncidente" method="post" class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>PARA APROBAR LA OBSERVACION DEBE DE 'ACEPTAR'</label>
                                </div>
                            </div>
                        </div>
                        <div id="mensajeForm5"></div>
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                <button id="btnAprobarIncidente" type="submit" class="btn btn-primary">ACEPTAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CERRAR INCIDENTE -->
    <div class="modal fade" id="modalCerrarIncidente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left">CERRAR OBSERVACION</h5>
                </div>
                <div class="modal-body">
                    <form id="formCerrarIncidente" method="post" class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>COMENTARIO</label>
                                    <textarea id="txtComentarioFinal" name="txtComentarioFinal" rows="5" cols="" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>ADJUNTO</label>
                                    <input type="file" id="fAdjuntoFinal" name="fAdjuntoFinal" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div id="mensajeForm6"></div>
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                                <button id="btnCerrarIncidente" type="submit" class="btn btn-primary">CULMINAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL INFORMACION -->
    <div class="modal fade" id="modalInfoIncidente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 id="hIncidente" style="font-weight: bold;" class="modal-title pull-left">INFORMACION OBSERVACION</h5>
                    <br>
                    <h5 id="hEstado" style="font-weight: bold;" class="modal-title pull-left"></h5>
                </div>
                <div class="modal-body">
                    <form id="formInfoIncidente" method="post" class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>FECHA DE SOLICITUD</label>
                                    <input type="text" id="txtFechaSolicitada" name="txtFechaSolicitada" disabled="disabled" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>COMENTARIO</label>
                                    <textarea id="txtInfoComentario" name="txtComentarioFinal" disabled="disabled" cols="" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <button type="button" id="downloadAdjuntoSol" name="downloadAdjuntoSol" class="btn" onclick="obtAdjuntoSolicitante(this)">ADJUNTO SOLICITANTE</button>
                                </div>
                            </div>
                        </div>
                        <div id="divRechazado" class="row" style="display: none;">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>FECHA DE RECHAZO</label>
                                    <input type="text" id="txtFechaRechazo" name="txtFechaRechazo" disabled="disabled" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>RESPUESTA</label>
                                    <textarea id="txtInfoMotivoRechazo" name="txtInfoMotivoRechazo" disabled="disabled" rows="5" cols="" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div id="divAprobado" class="row" style="display: none;">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>FECHA DE APROBACION</label>
                                    <input type="text" id="txtFechaAprobacion" name="txtFechaAprobacion" disabled="disabled" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>FECHA DE ATENCION</label>
                                    <input type="text" id="txtFechaAtencion" name="txtFechaAtencion" disabled="disabled" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>RESPUESTA</label>
                                    <textarea id="txtInfoComentarioFinal" name="txtInfoComentarioFinal" disabled="disabled" rows="5" cols="" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <button type="button" id="downloadAdjuntoResp" name="downloadAdjuntoResp" class="btn" onclick="obtAdjuntoResponsable(this)">ADJUNTO DE RESPUESTA</button>
                                </div>
                            </div>
                        </div>
                        <div id="mensajeForm6"></div>
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEnviarSoporte">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left">ENVIO A SOPORTE</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>C&Oacute;DIGO INCIDENCIA</label>
                                <input type="text" id="txtCodigo" name="txtCodigo" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>COMENTARIO</label>
                                <textarea id="txtComentarioEnvio" name="txtComentarioEnvio" rows="5" cols="" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="text-align: right;">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                            <button id="btnEnviarSoporte" type="button" onclick="enviarSoporte();" class="btn btn-primary">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ..vendors -->
    <script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/tether/dist/js/tether.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>

    <script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.resize.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/salvattore/dist/salvattore.min.js"></script>
    <script src="<?php echo base_url(); ?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

    <!--  tables -->
    <script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jszip/dist/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

    <script src="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>


    <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
    <!-- Charts and maps-->
    <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/curved-line.js"></script>
    <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/line.js"></script>
    <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/chart-tooltips.js"></script>
    <script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>
    <script src="<?php echo base_url(); ?>public/demo/js/jqvmap.js"></script>

    <!-- App functions and actions -->
    <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

    <!--  -->
    <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>

    <script src="https://www.w3schools.com/lib/w3.js"></script>
    <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>
    <script src="<?php echo base_url(); ?>public/js/js_planobra/jsConsulta.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript">
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        function openModalNuevoIncidente() {

            $("#selectTipoIncidente").val("").trigger('change');
            $("#selectModulo").val("").trigger('change');

            $('#txtComentario').val('');
            $('#fAdjunto').val(null);
            $('#formRegistrarIncidente').bootstrapValidator('resetForm', true);
            $('#modalRegistrarIncidente').modal('toggle');
        }

        $('#formRegistrarIncidente')
            .bootstrapValidator({
                container: '#mensajeForm4',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    selectTipoIncidente: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un Tipo de Observacion.</p>'
                            }
                        }
                    },
                    selectModulo: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un Modulo.</p>'
                            }
                        }
                    },
                    txtComentario: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir un Comentario.</p>'
                            }
                        }
                    }
                    /*,
                                        fAdjunto: {
                                            validators: {
                                                notEmpty: {
                                                    message: '<p style="color:red">(*) Debe adjuntar un archivo.</p>'
                                                }
                                            }
                                        }*/
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();


                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');

                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });

                var input2File = document.getElementById('fAdjunto');
                var file2 = input2File.files[0];
                formData.append('fileAdjunto', file2);

                $.ajax({
                        data: formData,
                        url: "pqt_registrar_incidencias",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            //$('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                            //initDataTable('#data-table7');
                            $('#modalRegistrarIncidente').modal('toggle');
                            mostrarNotificacion('success', 'Operaci�n �xitosa.', 'Se registro correcamente!');
                            swal({
                                title: 'Se registro correctamente!',
                                text: 'Codigo de Incidente: ' + data.codigo_incidente,
                                type: 'success',
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: 'OK!',
                                allowOutsideClick: false
                            }).then(function() {
                                location.reload();
                            }, function(dismiss) {
                                location.reload();
                            });
                        } else if (data.error == 1) {
                            mostrarNotificacion('warning', 'Verificar', data.msj);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });
            });

        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        function openModalRechazoIncidente(component) {
            var codigo_incidente = $(component).data('codigo_incidente');

            $('#btnRechazarIncidente').attr('data-codigo_incidente', codigo_incidente);
            $('#modalRechazarIncidente').modal('toggle');
        }

        $('#formRechazarIncidente')
            .bootstrapValidator({
                container: '#mensajeForm5',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    txtMotivo: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir un Motivo.</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();


                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');

                $.each(params, function(i, val) {
                    console.log('name ' + val.name + ' value ' + val.value);
                    formData.append(val.name, val.value);
                });

                var codigo_incidente = $('#btnRechazarIncidente').attr('data-codigo_incidente');
                formData.append('codigo_incidente', codigo_incidente);

                $.ajax({
                        data: formData,
                        url: "pqt_rechazar_incidente",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            //$('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                            //initDataTable('#data-table7');
                            $('#modalRechazarIncidente').modal('toggle');
                            mostrarNotificacion('success', 'Operaci�n �xitosa.', 'Se registro correcamente!');
                            location.reload();

                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });
            });

        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        function openModalAprobarIncidente(component) {
            var codigo_incidente = $(component).data('codigo_incidente');

            $('#btnAprobarIncidente').attr('data-codigo_incidente', codigo_incidente);
            $('#modalAprobarIncidente').modal('toggle');
        }

        $('#formAprobarIncidente')
            .bootstrapValidator({
                container: '#mensajeForm5',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled'
            }).on('success.form.bv', function(e) {
                e.preventDefault();


                var formData = new FormData();

                var codigo_incidente = $('#btnAprobarIncidente').attr('data-codigo_incidente');
                formData.append('codigo_incidente', codigo_incidente);

                $.ajax({
                        data: formData,
                        url: "pqt_aprobar_incidente",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            //$('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                            //initDataTable('#data-table7');
                            $('#modalAprobarIncidente').modal('toggle');
                            mostrarNotificacion('success', 'Operaci�n �xitosa.', 'Se registro correcamente!');
                            location.reload();

                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });
            });


        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        function openModalCerrarIncidente(component) {
            var codigo_incidente = $(component).data('codigo_incidente');
            console.log(codigo_incidente);
            $('#btnCerrarIncidente').attr('data-codigo_incidente', codigo_incidente);
            $('#modalCerrarIncidente').modal('toggle');
        }

        $('#formCerrarIncidente')
            .bootstrapValidator({
                container: '#mensajeForm6',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    txtComentarioFinal: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir un Comentario.</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();


                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');

                $.each(params, function(i, val) {
                    console.log('name ' + val.name + ' value ' + val.value);
                    formData.append(val.name, val.value);
                });

                var input2File = document.getElementById('fAdjuntoFinal');
                var file2 = input2File.files[0];
                formData.append('fileAdjunto', file2);

                var codigo_incidente = $('#btnCerrarIncidente').attr('data-codigo_incidente');
                formData.append('codigo_incidente', codigo_incidente);

                $.ajax({
                        data: formData,
                        url: "pqt_cerrar_incidente",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            //$('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                            //initDataTable('#data-table7');
                            $('#modalCerrarIncidente').modal('toggle');
                            mostrarNotificacion('success', 'Operaci�n �xitosa.', 'Se registro correcamente!');
                            location.reload();

                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });
            });

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        function abrirModalInfo(component) {
            $("#divRechazado").hide();
            $("#divAprobado").hide();
            //LIMPIANDO VARIABLES
            $("#hIncidente").text("INFORMACION OBSERVACION ");
            $("#hEstado").text("");
            $("#txtInfoComentario").val('');
            $('#downloadAdjuntoSol').attr('data-codigo_incidente', null);
            $("#txtFechaRechazo").val('');
            $("#txtInfoMotivoRechazo").val('');
            $("#txtFechaAprobacion").val('');
            $("#txtFechaAtencion").val('');
            $("#txtInfoComentarioFinal").val('');
            $('#downloadAdjuntoResp').attr('data-codigo_incidente', null);

            var codigo_incidente = $(component).data('codigo_incidente');
            var aprobado_fg = $(component).data('aprobado_fg');
            var comentario_solicitante = $(component).data('comentario_solicitante');
            var fecha_solicitada = $(component).data('fecha_solicitada');
            var motivo_rechazo = $(component).data('motivo_rechazo');
            var fecha_aprobada = $(component).data('fecha_aprobada');
            var fecha_atendida = $(component).data('fecha_atendida');
            var comentario_responsable = $(component).data('comentario_responsable');
            var id_estado_incidente = $(component).data('id_estado_incidente');
            var estado = $(component).data('estado');
            var adjunto_resp = $(component).data('adjunto_resp');

            $("#hIncidente").text("INFORMACION OBSERVACION " + codigo_incidente);

            if (id_estado_incidente == 1 || id_estado_incidente == 2) {
                $("#hIncidente").css("color", "black");
            } else if (id_estado_incidente == 3) {
                $("#hIncidente").css("color", "orange");
            } else if (id_estado_incidente == 4 && aprobado_fg == '1') {
                $("#hIncidente").css("color", "green");
            } else if (id_estado_incidente == 4 && aprobado_fg == '0') {
                $("#hIncidente").css("color", "red");
            }

            //$("#hEstado").text(estado);
            $("#txtInfoComentario").val(comentario_solicitante);
            $('#downloadAdjuntoSol').attr('data-codigo_incidente', codigo_incidente);
            $("#txtFechaSolicitada").val(fecha_solicitada);
            console.log("aprobado_fg " + aprobado_fg);
            if (aprobado_fg == '1') {
                console.log("Entro aqui1 " + aprobado_fg);
                $("#divAprobado").show();
                $("#txtFechaAprobacion").val(fecha_aprobada);
                $("#txtFechaAtencion").val(fecha_atendida);
                $("#txtInfoComentarioFinal").val(comentario_responsable);
                if (id_estado_incidente == 4 && adjunto_resp != '') {
                    $('#downloadAdjuntoResp').prop('disabled', false);
                    $('#downloadAdjuntoResp').attr('data-codigo_incidente', codigo_incidente);
                } else {
                    $('#downloadAdjuntoResp').prop('disabled', true);
                    $('#downloadAdjuntoResp').attr('data-codigo_incidente', null);
                }
            } else if (aprobado_fg == '0') {
                console.log("Entro aqui2 " + aprobado_fg);
                $("#divRechazado").show();
                $("#txtFechaRechazo").val(fecha_atendida);
                $("#txtInfoMotivoRechazo").val(motivo_rechazo);
            } else {
                $("#divRechazado").hide();
                $("#divAprobado").hide();
            }
            $('#modalInfoIncidente').modal('toggle');
        }

        function obtAdjuntoSolicitante(component) {
            var formData = new FormData();
            var codigo_incidente = $('#downloadAdjuntoSol').attr('data-codigo_incidente');
            var tipo_solicitud = 1; //SOLICITANTE
            window.location = "pqt_descargar_adjunto?codigo_incidente=" + codigo_incidente + "&tipo_solicitud=" + tipo_solicitud + "";
        }

        function obtAdjuntoResponsable(component) {
            var formData = new FormData();
            var codigo_incidente = $('#downloadAdjuntoResp').attr('data-codigo_incidente');
            formData.append('codigo_incidente', codigo_incidente);
            var tipo_solicitud = 0; //RESPONSABLE
            window.location = "pqt_descargar_adjunto?codigo_incidente=" + codigo_incidente + "&tipo_solicitud=" + tipo_solicitud + "";
        }

        /////////////////////////////////
        function filtrarTabla() {
            var codigoIncidente = $.trim($('#txtFCodigoIncidente').val());
            var estado = $.trim($('#selectFEstado').val());
            var modulo = $.trim($('#selectFModulo').val());
            var tipoIncidente = $.trim($('#selectFTipoIncidente').val());

            $.ajax({
                    type: 'POST',
                    'url': 'pqt_filtrar_tabla_incidentes',
                    data: {
                        codigoIncidente: codigoIncidente,
                        estado: estado,
                        modulo: modulo,
                        tipoIncidente: tipoIncidente
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#divTabla').html(data.tbIncidencias);
                        initDataTable('#data-table');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                    }
                });
        }

        /////////////////////////////////////////////////////////////////////////////////////////////////////
        $('#fAdjunto').bind('change', function() {
            var pesoArchivo = this.files[0].size / 1024 / 1024; //en MB
            if (pesoArchivo > 3) {
                swal({
                    title: 'Archivo excede peso permitido!',
                    text: 'El archivo que intenta adjunta tiene un peso mayor a lo permitido que es 3MB',
                    type: 'warning',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'OK!',
                    allowOutsideClick: false
                }).then(function() {
                    $('#fAdjunto').val(null);
                    $('#formRegistrarIncidente').bootstrapValidator('resetForm', true);
                }, function(dismiss) {
                    $('#fAdjunto').val(null);
                    $('#formRegistrarIncidente').bootstrapValidator('resetForm', true);
                });
            }

        });

        var codigoIncidenciaEscalamiento = null;

        function openModalEscalamiento(component) {
            codigoIncidenciaEscalamiento = $(component).data('codigo_incidente');
            comentarioEnvio = $('#txtComentarioEnvio').val();
            $('#txtCodigo').val(codigoIncidenciaEscalamiento);
            modal('modalEnviarSoporte');
        }

        function enviarSoporte() {
            $.ajax({
                type: 'POST',
                url: 'enviarSoporte',
                data: {
                    codigo_incidente: codigoIncidenciaEscalamiento,
                    comentarioEnvio: comentarioEnvio
                }
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    mostrarNotificacion('success', 'exitosa.', 'Se envio correctamente!');
                    location.reload();
                } else if (data.error == 1) {
                    mostrarNotificacion('error', data.msj);
                }
            });
        }

        function getTipoInc() {
            var idModulo = $('#selectModulo option:selected').val();

            $.ajax({
                type: 'POST',
                url: 'getTipoInc',
                data: {
                    idModulo: idModulo
                }
            }).done(function(data) {
                data = JSON.parse(data);

                $('#selectTipoIncidente').html(data.cmbTipoInc);
            });
        }
    </script>
</body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->

</html>