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
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bootstrap-3.3.6-dist/css/bootstrap.min.css?v=<?php echo time(); ?>" />

    <!-- App styles -->

    <link rel="stylesheet" href="<?php echo base_url(); ?>public/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
    <style>
        .size {
            width: 111px;
        }

        .cuadrado {
            background-color: #afafaf;
            width: 20px;
            height: 3px;
            margin-top: 44px;
        }

        .c_culminado {
            border: 2px solid #afafaf;
            width: 90px;
            height: 90px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            background: #28a745;
            text-align: center;
        }

        .c_actual {
            border: 2px solid #afafaf;
            width: 90px;
            height: 90px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            background: #007bff;
            text-align: center;
        }

        .c_pendiente {
            border: 2px solid #afafaf;
            width: 90px;
            height: 90px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            background: #bfbfbf;
            text-align: center;
        }

        .c_suspendido {
            border: 2px solid #afafaf;
            width: 90px;
            height: 90px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            background: #e02a82;
            text-align: center;
        }

        .c_cancelado {
            border: 2px solid #afafaf;
            width: 90px;
            height: 90px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            background: rgba(248, 80, 50, 1);
            text-align: center;
        }

        .c_trunco {
            border: 2px solid #afafaf;
            width: 90px;
            height: 90px;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            background: rgba(241, 231, 103, 1);
            text-align: center;
        }

        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width: 1200px;
            }
        }

        .select2-dropdown {
            z-index: 10000 !important;
            width: 200px !important;
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
                <h2>CONSULTA +DESPLIEGUES</h2>
                <hr>
                <div class="card">
                    <div class="card-block">
                        <div class="row">

                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>ITEMPLAN</label>
                                    <input id="txtItemPlan" type="text" class="form-control input-mask" placeholder="ItemPlan" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>PROYECTO</label>

                                    <select id="selectProy" name="selectProy" class="select2" onchange="changueSubProyecto();">
                                        <option>&nbsp;</option>
                                        <?php
                                        foreach ($listaProyectos as $row) {
                                        ?>
                                            <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>SUB PROYECTO</label>

                                    <select id="selectSubProy" name="selectSubProy" class="select2">
                                        <option>&nbsp;</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>GESTOR</label>
                                    <input id="gestorObra" type="text" class="form-control input-mask" placeholder="Gestor del proyecto" autocomplete="off" maxlength="200" style="border-bottom: 1px solid lightgrey">
                                </div>
                                <button class="btn btn-success waves-effect" type="button" onclick="validacionBeforeFiltro()">CONSULTAR</button>
                            </div>
                            <div id="barraProgreso">

                            </div>

                            <div id="contTabla" class="table-responsive">
                                <?php echo $tablaAsigGrafo ?>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer hidden-xs-down">
                    <p> Material Admin Responsive. All rights reserved.</p>

                    <ul class="nav footer__nav">
                        <a class="nav-link" href="#">Homepage</a>

                        <a class="nav-link" href="#">Company</a>

                        <a class="nav-link" href="#">Support</a>

                        <a class="nav-link" href="#">News</a>

                        <a class="nav-link" href="#">Contacts</a>
                    </ul>
                </footer>
        </section>

        <div class="modal fade" id="modalEjec" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="formAdjudicaItem" method="post" class="form-horizontal">

                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>
                                        <select id="selectSubAdju" name="selectSubAdju" class="select2 form-control">
                                            <option>&nbsp;</option>
                                            <?php
                                            foreach ($listaSubProy->result() as $row) {
                                            ?>
                                                <option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">MDF</label>
                                        <select id="selectCentral" name="selectCentral" class="select2 form-control">
                                            <option>&nbsp;</option>
                                            <?php foreach ($listacentral->result() as $row) { ?>
                                                <option value="<?php echo $row->idCentral ?>"><?php echo utf8_decode($row->tipoCentralDesc . ' - ' . $row->codigo) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">EECC DISE&Ntilde;O</label>
                                        <select id="selectEECCDiseno" name="selectEECCDiseno" class="select2 form-control">
                                            <option>&nbsp;</option>
                                            <?php
                                            foreach ($listEECCDi->result() as $row) {
                                            ?>
                                                <option value="<?php echo $row->idEmpresaColab ?>"><?php echo utf8_decode($row->empresaColabDesc) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12" id="divCoaxial">
                                    <label style="font-weight: bold;color: black;">COAXIAL</label>
                                    <div class="form-group col-12">
                                        <label>FECHA PREV. DE ATENCION COAXIAL</label>
                                        <input placeholder="::SELECCIONE FECHA::" id="idFechaPreAtencionCoax" name="idFechaPreAtencionCoax" type="text" class="form-control form-control-sm date-picker">

                                        <i class="form-group__bar"></i>
                                    </div>

                                    <div class="col-12">
                                        <div id="dropzone4" class="dropzone">

                                        </div>
                                        <hr style="border:1;">
                                    </div>
                                </div>
                                <hr style="border:2;">
                                <div class="col-sm-12 col-md-12" id="divFO">
                                    <label style="font-weight: bold;color: black;">FO</label>

                                    <div class="form-group col-12">
                                        <label>FECHA PREV. DE ATENCION FO</label>
                                        <input placeholder="::SELECCIONE FECHA::" id="idFechaPreAtencionFo" name="idFechaPreAtencionFo" type="text" class="form-control form-control-sm date-picker">

                                        <i class="form-group__bar"></i>
                                    </div>

                                    <div class="col-12">
                                        <div id="dropzone5" class="dropzone">

                                        </div>
                                        <hr style="border:1;">
                                    </div>
                                </div>


                                <br><br>

                                <div class="col-sm-12 col-md-12" id="mensajeForm"></div>

                                <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" id="btnAdjudica">Aceptar</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>
    <!-- Small -->
    <div class="modal fade" id="modalDetenerPlanObra" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="tmDetenerPlanObra" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formDetenerPlanObra" method="post" class="form-horizontal" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Motivo</label>
                                    <select id="selectMotivo" name="selectMotivo" class="form-control">
                                        <option>&nbsp;</option>
                                        <?php
                                        foreach ($listaMotivos->result() as $row) {
                                        ?>
                                            <option value="<?php echo $row->idMotivo ?>"><?php echo $row->motivoDesc ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 col-md-12">
                                <label class="control-label">Comentario</label>
                                <textarea id="txtComentario" name="txtComentario" class="form-control" rows="8" cols=""></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-12">
                                <label class="control-label">Evidencia</label>
                                <input type="file" class="form-control" id="fEvidenciaDetener" name="fEvidenciaDetener" />
                            </div>
                            <br><br>
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="hfAccionDeDetener" name="hfAccionDeDetener" />
                            </div>
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="hfItemPlan" name="hfItemPlan" />
                            </div>
                            <div class="col-sm-12 col-md-12" id="mensajeForm1"></div>

                            <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="btnDetenerItemPlan">Aceptar</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reanudar Ini -->
    <div class="modal fade" id="modalReanudarPlanObra" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="tmReanudarPlanObra" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="formReanudarPlanObra" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-12">
                                <label class="control-label">Comentario</label>
                                <textarea id="txtComentarioR" name="txtComentarioR" class="form-control" rows="8" cols=""></textarea>
                            </div>
                            <div class="form-group col-sm-12 col-md-12">
                                <label class="control-label">Evidencia</label>
                                <input type="file" class="form-control" id="fEvidenciaReanudar" name="fEvidenciaReanudar" />
                            </div>
                            <br><br>
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="hfAccionDeReanudar" name="hfAccionDeReanudar" />
                            </div>
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="hfItemPlanR" name="hfItemPlanR" />
                            </div>
                            <div class="col-sm-12 col-md-12" id="mensajeForm2"></div>

                            <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Aceptar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--             Reanudar Fin -->
    <div class="modal fade" id="modalExpediente" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left">Registrar </h5>
                </div>
                <br>
                <div class="modal-body">
                    <h6>Usted ha seleccionado las siguientes PTR:</h6>
                    <div class="card text-center">

                        <div id="seleccionados"></div>

                    </div>
                    <div class="form-group">
                        <label>Ingrese comentario</label>
                        <input id="inputVR" type="text" class="form-control input-mask" placeholder="Comentario" autocomplete="off" maxlength="400" style="border-bottom: 1px solid lightgrey">
                        <i class="form-group__bar"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="botonConfirmar" type="button" onclick="asignarExpediente(this)" class="btn btn-info" data-dismiss="modal">CONFIRMAR</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Older IE warning message -->
    <!-- POPUP LOG-->
    <div class="modal fade" id="modal-large" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="titModalLogEstados" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="col-sm-12 col-md-12">
                        <div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#estadosIP" role="tab">LOG ESTADOS ITEMPLAN</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#expedienteIP" role="tab">LOG FIRMA DIGITAL</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active fade show col-md-12" id="estadosIP" role="tabpanel">
                            <div class="card" id="contCardLog">


                            </div>
                        </div>
                        <div class="tab-pane fade" id="expedienteIP" role="tabpanel">
                            <div class="tab-container">
                                <div id="contbExpediente" class="table-responsive">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-motcancel" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="margin: auto;    font-weight: bold;" class="modal-title">MOTIVO CANCELADO</h4>
                    <button type="button" class="close" onclick="closeMotivoCancelar();">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="card" id="contCardMotivoCancel">


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" onclick="closeMotivoCancelar();">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLicencia" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <!--  -->
                    <div class="form-group">
                        <a style="cursor:pointer" title="Agregar Entidad" onclick="openMdlAgregarEntidad($(this));"><i class="zmdi zmdi-hc-2x zmdi-account-add"></i>
                        </a>
                    </div>
                    <div class="card" id="contTablaLicencia">


                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-mottrunco" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="margin: auto;    font-weight: bold;" class="modal-title">MOTIVO TRUNCO</h4>
                    <button type="button" class="close" onclick="closeMotivoTrunco();">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="card" id="contCardMotivoTrunco">


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" onclick="closeMotivoTrunco();">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalDetalleVR" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="margin: auto;    font-weight: bold;" class="modal-title">DETALLE VR</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card" id="contCardDetalleVR">
                        <div id="contTablaDetVR" class="table-responsive">

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalParalizacion" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">PARALIZACI&Oacute;N</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select id="cmbParalizacionHtml" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Comentario</label>
                        <textarea class="form-control" id="comentarioParalizacion"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Evidencia</label>
                        <div id="dropzoneParalizacion" class="dropzone">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-success" id="btnEvidenciaParalizacion">Aceptar</button>
                </div>
            </div>
        </div>
    </div>


    <div id="modalAlerta" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" style="background:red">
                    <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                </div>
                <div class="modal-body">
                    <a>Al aceptar se revertir&aacute; la paralizaci&oacute;n</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success boton-acepto" onclick="aceptarRevertir();">Aceptar</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalSiom" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">SIOM</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div id="contTablaSiom">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalDatosSisegos" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">DATOS SISEGO</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div id="contInfoDataSisego">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDocumentosCV" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="margin: auto;    font-weight: bold;" class="modal-title">DOCUMENTOS CV</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="" id="contCardDetalleVR">
                        <div class="" id="cntMsj">
                            <table id="data-table" class="table table-bordered">
                                <thead class="thead-default">
                                    <tr>
                                        <th>TSS</th>
                                        <th>EXPEDIENTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-group col-md-6" id="contBtnTss"></div>
                                        </td>
                                        <td>
                                            <div class="form-group col-md-6" id="contBtnExpediente"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>'
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Comentario</label>
                        <textarea id="comentario" name="comentario" class="form-control input-mask" disabled></textarea>
                        <i class="form-group__bar"></i>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>



    <div class="modal fade" id="guardarAdjudicacion" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ã‚Â¿Desea Adjudicar?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAdjudica" onclick="adjudicar()" class="btn btn-primary">Adjudicar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLogOc" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">LOG SOLICITUD OC</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <!--  -->

                    <div class="card" id="contTablaLogOc">
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalVR" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-title" style="background:#0154a0;color:white;height:30px" align="center">
                    SELECCIONAR UNA OPCI&Oacute;N
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <button id="botonContinuar" type="button" onclick="asignarGrafoInterna(this, 1)" class="btn btn-success">
                                validar
                            </button>
                        </div>
                        <div class="form-group container-fluid">
                            <button id="botonRechazar" type="button" onclick="asignarGrafoInterna(this, 2)" class="btn btn-danger">
                                Rechazar
                            </button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPorcentaje" tabindex="-1" z-index="10" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">LIQUIDACION DE OBRA</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="contPorcentaje">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSubirEvidencia" tabindex="-1" z-index="10" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal"></h5>
                </div>
                <div class="modal-body">
                    <form id="formRegistrarEvidenciasSinSiom" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                        
                            <div class="form-group col-sm-12 col-xs-12">
                                <label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CARGAR EVIDENCIA (INFORME FOTOGRAFICO, GUIAS, FORMATO EXCEL CERT.) </label>
                                <input id="filePruebasRefleESinSiom" name="filePruebasRefleESinSiom" type="file" >
                            </div>
                     
                        <div align="center">
                            <div id="mensajeFormE"></div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                    <button id="btnRegEvidencias" type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modalConsultaPTR" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">EDITAR PO</h4>
                </div>
                <div class="modal-body">
                    <div id="contTablaPTR">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEditarPTR" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">EDITAR PO</h4>
                </div>
                <div class="modal-body">
                    <!-- <div class="form-group">
							<input type="text" class="form-control" />
							<button class="btn btn-info">buscar</button>
						</div> -->

                    <div class="panel panel-primary">
                        <div class="panel-heading" id="tituloActividades">Agregar Actividades</div>
                        <div class="panel-body">
                            <div id="contTablaActividad" class="table-responsive">

                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-heading">PTRs Asociadas</div>
                        <div class="panel-body">
                            <div id="contEditarPTR" class="table-responsive">
                            </div>
                        </div>
                        <div class="panel-footer row">
                            <div class="col-md-2">
                                <button id="btnActualizarPtr" type="button" class="btn btn-success boton-acepto" onclick="actualizarPtr();">Liquidar PO</button>
                            </div>
                            <!-- <div>
									<button id="btnActualizarPtr" type="button" style="background:red" class="btn btn-success boton-acepto"  onclick="getNoEditPo();">Confirmar no edici&oacute;n</button>
								</div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>


    <div class="modal fade" role="dialog" id="modalEvidencias" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="titModalEvidencias" style="margin: auto;font-weight: bold;" class="modal-title">Registrar Evidencias</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="content" class="modal-body">
                    <form id="formEvidencias" method="post" action="#" enctype="multipart/form-data" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                        <div style="">
                            <div class="col-sm-12 form-group" style="margin-top:16px">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 form-group">
                                        <label>CARGAR ARCHIVO 1(.xls,.xlsx)</label>
                                        <input type="file" class="form-control-file" name="archivo1" id="archivo1" accept="application/vnd.openxmlformats-offedocument.spreadsheetml.sheet., application/vnd.ms-excel,application/vnd.msexcel,application/excel">
                                    </div>
                                    <div class="col-sm-6 col-md-6 form-group">
                                        <label>CARGAR ARCHIVO 2(.pdf)</label>
                                        <input type="file" class="form-control-file" name="archivo2" id="archivo2" accept="application/pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnRegEvidenciaT" class="btn btn-success" onclick="registrarEvidencias()">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-fullscreen" id="modalComprobante" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center w-100 form-group">
                    <h4 class="m-0 text-center color-white">
                        Comprobante
                    </h4>
                    <button type="button" class="close text-white position-absolute pos-top pos-right p-2 m-1 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="contTablaComprobante" class="table-responsive">

                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnComprobante" type="button" class="btn btn-primary" onclick="registrarComprobante();">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAgregarEntidad" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md modal-dialog-right" role="document">
            <div class="modal-content">
                <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center w-100 form-group">
                    <h4 class="m-0 text-center color-white">
                        Agregar Entidad
                    </h4>
                    <button type="button" class="close text-white position-absolute pos-top pos-right p-2 m-1 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="contTablaEntidad">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnProcesar" type="button" class="btn btn-primary" onclick="registrarEntidad();">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRegistrarExpLic" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center w-100 form-group">
                    <h4 class="m-0 text-center color-white">
                        Licencia
                    </h4>
                    <button type="button" class="close text-white position-absolute pos-top pos-right p-2 m-1 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="formRegExpLic">
                        <label class="form-label" for="inputGroupFile01">Cargar Evidencia</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" id="archivo">
                                <label class="custom-file-label" for="archivo" id="lblarchivo"></label>
                            </div>
                            <div class="valid-feedback">
                                Correcto!
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btnProcesar" type="button" class="btn btn-primary" onclick="guardarExpedienteEntidad();">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Entidad Ambiental -->
    <div class="modal fade" id="modalEntidadAmbiental" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-center">
                        Entidad Ambiental
                    </h4>
                </div>

                <div class="modal-body">
                    <button class="btn btn-primary btn-sm mb-2" id="btnAgregarEntidadAmbiental" onclick="agregarEntidadAmbiental(this)">
                        <i class="zmdi zmdi-plus-square"></i> Agregar Entidad
                    </button>

                    <div id="contTablaEntidadAmbiental" class="table-responsive">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Adjuntar Evidencias Entidad Ambiental -->
    <div class="modal fade" id="modalEntidadAmbientalAdjuntos" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-center">
                        Adjuntar Evidencias
                    </h4>
                </div>

                <div class="modal-body">

                    <div class="text-right mb-2">
                        <button class="btn btn-danger btn-sm d-none" id="btnCerrarAgregarEvidencia" onclick="cerrarFormulario('evidencia')">X</button>
                        <button class="btn btn-primary btn-sm" id="btnAgregarEvidencia" data-action="0" onclick="showAgregarEvidencias(this)">
                            <i class="zmdi zmdi-plus-square"></i> Agregar
                        </button>
                    </div>

                    <div id="contEntidadAmbientalAdjuntar" class="mb-3 d-none">
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <label class="form-label">Tipo <span class="text-danger">*</span></label>
                                <select id="cboTipoEvidencia" class="custom-select"></select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Descripcion <span class="text-danger">*</span></label>
                                <input type="text" id="txtDescripcionEvidencia" class="form-control" placeholder="Descripcion" />
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" id="txtFechaIniEvidencia" class="form-control" />
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                                <input type="date" id="txtFechaFinEvidencia" class="form-control" />
                            </div>
                        </div>
                        <div class="mt-1 text-center">
                            <label class="form-label">Selecciona un archivo <span class="text-danger">*</span></label>
                            <input class='form-control form-control-sm' type="file" id="fileEvidencia">
                        </div>
                    </div>

                    <hr>

                    <div id="contEntidadAmbientalTabla" class="table-responsive"></div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Compromiso -->
    <div class="modal fade" id="modalCompromiso" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-center">
                        Compromiso
                    </h4>
                </div>

                <div class="modal-body">
                    <a id="btnAgregarCompromiso" style="cursor:pointer" title="Agregar Compromiso" onclick="openModalAgregarCompromiso(this);">
                        <i class="zmdi zmdi-hc-2x zmdi-plus"></i>
                    </a>

                    <div id='contTablaCompromisosAll' class="table-responsive"></div>

                    <div class="form-group">
                        <label>Plan de Participacion y Ciudadana</label>
                        <textarea class="form-control" id="txtParticipacion" placeholder="..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Medidas</label>
                        <textarea class="form-control" id="txtMedidas" placeholder="..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-info" id="btnGuardarCompromiso" onclick="guardarCompromiso(this)">Guardar</button>
                    <button class="btn btn-primary" id="btnFinalizarCompromiso" onclick="finalizarCompromiso(this)">Finalizar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Compromiso -->
    <div class="modal fade" id="modalAgregarCompromiso" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-center">
                        Agregar Compromiso
                    </h4>
                </div>

                <div class="modal-body">
                    <div id="contTablaCompromisos"></div>
                </div>
                <div class="modal-footer">
                    <button id="btnRegistrarCompromiso" type="button" class="btn btn-primary waves-effect" onclick="registrarCompromiso(this);">Aceptar</button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Comprobante -->
    <div class="modal fade" id="modalComprobanteEntidad" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-center">
                        Comprobante
                    </h4>
                </div>

                <div class="modal-body">

                    <div class="text-right mb-2">
                        <button class="btn btn-danger btn-sm d-none" id="btnCerrarAgregarComprobante" onclick="cerrarFormulario('comprobante')">X</button>
                        <button class="btn btn-primary btn-sm" id="btnAgregarComprobante" data-action="0" onclick="showAgregarComprobante(this)">
                            <i class="zmdi zmdi-plus-square"></i> Agregar
                        </button>
                    </div>

                    <div id="contComprobanteAgregar" class="mb-3 d-none">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <label class="form-label">Nro Comprobante <span class="text-danger">*</span></label>
                                <input type="text" id="txtNroComprobante" class="form-control" placeholder="Nro Comprobante" />
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" id="txtFechaEmiComprobante" class="form-control" />
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Monto <span class="text-danger">*</span></label>
                                <input type="number" id="txtMontoComprobante" class="form-control" />
                            </div>
                        </div>
                        <div class="mt-1 text-center">
                            <label class="form-label">Selecciona un archivo <span class="text-danger">*</span></label>
                            <input class='form-control form-control-sm' type="file" id="fileComprobante">
                        </div>
                    </div>

                    <hr>

                    <div id="contTablaComprobanteEntidad" class="table-responsive">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="modalEvidenciaAws" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">Evidencias</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<div id="contTablaEvidenciaAws">
					
					</div>
                </div>
            </div>
        </div>
    </div>

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

    <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>

    <script src="<?php echo base_url(); ?>public/jquery.numeric/jquery.numeric-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>public/fancy/source/jquery.fancybox.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

    <!--  -->
    <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo base_url(); ?>public/js/sinfix_pqt.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>public/fancy/source/jquery.fancybox.js"></script>
    <script type="text/javascript">
        /*
        if(!("autofocus" in document.createElement("input"))){
            document.getElementById("inputVR").focus();
        }*/

        function openModalDetLogVR(component) {
            var idSolVR = $(component).data('idsolvr');
            $.ajax({
                type: 'POST',
                url: 'getDetVR',
                data: {
                    idSolVR: idSolVR
                }
            }).done(function(data) {
                data = JSON.parse(data);
                $('#contTablaDetVR').html(data.tablaDetVR);
                modal('modalDetalleVR');

            });
        }


        function recogePTR() {

            console.log('entro en recogePTR...');

            //var arrayNamesptrExp = $( "input[name*='ptrExp']" );

            var arrayNamesptrExp = $("input[type=checkbox]:checked");
            var expediente = new Array();

            if (arrayNamesptrExp.length != 0) {
                console.log(arrayNamesptrExp.length);
                console.log(arrayNamesptrExp);

                for (i = 0; i < arrayNamesptrExp.length; i++) {
                    expediente.push(arrayNamesptrExp[i].dataset.ptr + "%" + arrayNamesptrExp[i].dataset.item + "%" + arrayNamesptrExp[i].dataset.fecsol + "%" + arrayNamesptrExp[i].dataset.subproyecto + "%" + arrayNamesptrExp[i].dataset.zonal + "%" + arrayNamesptrExp[i].dataset.eecc + "%" + arrayNamesptrExp[i].dataset.area);
                }
                console.log('expediente es ');
                console.log(expediente);

                mostrarModal(expediente);

            } else {
                alert('Debe seleccionar al menos 1 registro para continuar.');
            }


        }

        function mostrarModal(expediente) {
            console.log('entro en registaExpediente');
            var texto = '';
            var ptrModal = '';
            var itemModal = '';
            for (j = 0; j < expediente.length; j++) {
                //texto += '<label>'+expediente[j].replace('%', ' ')+'</label><br>';
                //ptrModal = expediente[j]

                var elem = expediente[j].split('%');
                ptrModal = elem[0];
                itemModal = elem[1];
                texto += '<label>' + ptrModal + '</label><br>';

            }
            var jsonExpediente = JSON.stringify(expediente);

            console.log('----------------------------');
            console.log(expediente);
            console.log(jsonExpediente);



            $('#seleccionados').html(texto);

            $('#botonConfirmar').attr('data-jsonptr', jsonExpediente);


            $('#modalExpediente').modal('toggle');

        }


        function asignarExpediente(component) {
            var vrLeng = $('#inputVR').val().length;

            if (vrLeng == 0) {
                alert('Usted no ha asignado un comentario de expediente.');
            } else {

                console.log('Asignar expediente');
                var jsonptr = $(component).attr('data-jsonptr');
                var comentario = $('#inputVR').val();
                console.log('=================');
                console.log(jsonptr);
                console.log(comentario);
                console.log('Ajax');



                $.ajax({
                    type: 'POST',
                    'url': 'asignarExpediente',
                    data: {
                        jsonptr: jsonptr,
                        comentario: comentario
                    },
                    'async': false
                }).done(function(data) {
                    console.log('voldio del ajax');

                    var data = JSON.parse(data);
                    console.log('++++++++++++++++++');

                    if (data.error == 0) {
                        console.log('en el if');
                        $('#modalExpediente').modal('toggle');
                        mostrarNotificacion('success', 'Registro exitoso.', data.msj);
                        //$('#contTabla').html(data.tablaAsigGrafo)
                        //initDataTable('#data-table');
                        filtrarTabla();
                    } else if (data.error == 1) {
                        console.log('en el else');

                        mostrarNotificacion('error', 'Error al dar expediente', data.msj);
                    }
                });

                console.log('se envio a ruta');

            }

        }

        function filtroTipoPlanta() {

        }

        function validacionBeforeFiltro() {
            var tipoplanta = $.trim($('#selectTipoPlanta').val());
            var proyecto = $.trim($('#selectProy').val());
            var subproyecto = $.trim($('#selectSubProy').val());
            var itemplan = $.trim($('#txtItemPlan').val());
            var nombre = $.trim($('#nombreproyecto').val());
            var fase = $.trim($('#selectFase').val());
            var itemMadre = $.trim($('#txtItemMadre').val());
            var gestorObra = $.trim($('#gestorObra').val());
            if (tipoplanta == "" && proyecto == "" && subproyecto == "" && itemplan == "" && nombre == "" && fase == "" && itemMadre == "" && gestorObra == "") {
                mostrarNotificacion('error', 'Seleccionar Filtros', 'Debe de seleccionar al menos un filtro!');
                return false;
            }

            filtrarTabla();
        }

        function filtrarTabla() {
            $('#barraProgreso').empty();
            //var itemplan = $.trim($('#itemplan').val());
            var erroItemPlan = '';
            var itemMadre = $.trim($('#txtItemMadre').val());
            var itemplan = $.trim($('#txtItemPlan').val());
            //validar item plan
            //mostrarNotificacion('error','Hubo problemas al filtrar los datos!');

            if (itemplan.length < 13 && itemplan.length >= 1)
                erroItemPlan = 'ItemPlan Invalido.'

            var tipoPlanta = $.trim($('#selectTipoPlanta').val());
            var nombreproyecto = $.trim($('#nombreproyecto').val());
            var proy = $.trim($('#selectProy').val());
            var subProy = $.trim($('#selectSubProy').val());
            var selectMesPrevEjec = $.trim($('#selectMesPrevEjec').val());

            var fechaDestinoDefault = '2018/12/31';
            var fechaDestino = '';
            var filtroPrevEjec = '';
            var idFase = $.trim($('#selectFase').val());

            var gestorObra = $.trim($('#gestorObra').val());
            //VALIDAR ITEMPLAN
            $.ajax({
                    type: 'POST',
                    'url': 'pqt_perteneceactpqt',
                    data: {
                        itemplan: itemplan,
                        nombreproyecto: nombreproyecto,
                        itemMadre: itemMadre
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        if (data.permitir >= 1) {

                            swal({
                                title: 'ItemPlan',
                                text: 'El itemplan ' + itemplan + ' no ha sido creado en el modulo paquetizado \n Deseas ir a la opcion gestion de obra?',
                                type: 'warning',
                                showCancelButton: true,
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: 'S&Iacute;',
                                cancelButtonClass: 'btn btn-secondary',
                                cancelButtonText: 'NO',
                                allowOutsideClick: false,
                                showCloseButton: true
                            }).then(() => {
                                window.location.href = '<?php echo base_url() ?>' + 'consulta';
                            }, (dismiss) => {})
                            return false;
                        } else {

                            if (erroItemPlan == '') {

                                $.ajax({
                                        type: 'POST',
                                        'url': 'pqt_getDataTableItem',
                                        data: {
                                            itemplan: itemplan,
                                            nombreproyecto: nombreproyecto,
                                            proy: proy,
                                            subProy: subProy,
                                            tipoPlanta: tipoPlanta,
                                            idFase: idFase,
                                            itemMadre: itemMadre,
                                            gestorObra: gestorObra
                                        },
                                        'async': false
                                    })
                                    .done(function(data) {
                                        var data = JSON.parse(data);
                                        if (data.error == 0) {
                                            $('#contTabla').html(data.tablaAsigGrafo)
                                            initDataTable('#data-table');

                                        } else if (data.error == 1) {

                                            mostrarNotificacion('error', data.msj);
                                        }
                                    });

                            } else {
                                mostrarNotificacion('error', 'ItemPlan', erroItemPlan);
                            }
                        }

                    } else if (data.error == 1) {

                        mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                    }
                });

        }




        /****************************Log*************************/
        function mostrarLog(component) {
            var itemplan = $(component).attr('data-idlog');
            $.ajax({
                    type: 'POST',
                    'url': 'pqt_mostrarLogIPConsulta',
                    data: {
                        itemplan: itemplan
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#titModalLogEstados').html('ITEMPLAN : ' + itemplan);
                        $('#contCardLog').html(data.listaLog);
						initDataTable('#tbLogEstado');
						$('#contbExpediente').html(data.tbExpediente);
                        initDataTable('#tb_firma_digital');
                        $('#modal-large').modal('toggle');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Error al principal', data.msj);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al mostrar log principal', errorThrown + '. Estado: ' + textStatus);
                });
        }

        function verMotivoCancelar(component) {
            var fecha = $(component).attr('data-fechaC');
            var itemplan = $(component).attr('data-itemC');


            $.ajax({
                    type: 'POST',
                    'url': 'getMotivoCancelConsulta',
                    data: {
                        itemplan: itemplan,
                        fecha: fecha
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contCardMotivoCancel').html(data.motivoCancel);
                        $('#modal-motcancel').modal('toggle');

                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Error ', data.msj);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al mostrar el log cancelar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function() {

                });


        }

        function closeMotivoCancelar() {
            $('#modal-motcancel').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');
        }


        function verMotivoTrunco(component) {
            var fecha = $(component).attr('data-fechaT');
            var itemplan = $(component).attr('data-itemT');

            $.ajax({
                    type: 'POST',
                    'url': 'getMotivoTruncoConsulta',
                    data: {
                        itemplan: itemplan,
                        fecha: fecha
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contCardMotivoTrunco').html(data.motivoTrunco);
                        $('#modal-mottrunco').modal('toggle');

                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Error ', data.msj);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error mostrar el log trunco', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function() {

                });

        }

        function closeMotivoTrunco() {
            $('#modal-mottrunco').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');
        }

        /********************************************************************************************************/


        function zipItemPlan(btn) {
            var itemPlan = btn.data('itemplan');
            if (itemPlan == null || itemPlan == '') {
                return;
            }
            console.log(itemPlan);
            $.ajax({
                type: 'POST',
                url: 'zipItemPlan',
                data: {
                    itemPlan: itemPlan
                }
            }).done(function(data) {
                try {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        var url = data.directorioZip;
                        if (url != null) {
                            window.open(url, 'Download');
                        } else {
                            alert('No tiene evidencias');
                        }
                        // mostrarNotificacion('success', 'descarga realizada', 'correcto');
                    } else {
                        // mostrarNotificacion('error', 'descarga no realizada', 'error');            
                        alert('error al descargar');
                    }
                } catch (err) {
                    alert(err.message);
                }
            });
        }

        function changueProyecto() {
            var tipoplanta = $.trim($('#selectTipoPlanta').val());
            $.ajax({
                    type: 'POST',
                    'url': 'getProyConsulta',
                    data: {
                        tipoplanta: tipoplanta
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {

                        $('#selectProy').html(data.listaProyectos);
                        $('#selectSubProy').html('');


                    } else if (data.error == 1) {

                        mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                    }
                });
        }

        function changueSubProyecto() {
            var proyecto = $.trim($('#selectProy').val());
            $.ajax({
                    type: 'POST',
                    'url': 'pqt_getSubProyConsulta',
                    data: {
                        proyecto: proyecto
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {

                        $('#selectSubProy').html(data.listaSubProy);


                    } else if (data.error == 1) {

                        mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                    }
                });
        }



        var origenGlobal = null;

        function openModalParalizacion(btn, origen) {
            var flgMotivoParalizacion = 1;
            itemplanParalizacion = btn.data('itemplan');
            $('#btnEvidenciaParalizacion').css('display', 'block');
            // console.log(drop.dropzone().maxFilesize);

            if (itemplanParalizacion == null || itemplanParalizacion == '') {
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'getCmbMotivo',
                data: {
                    flgTipo: flgMotivoParalizacion,
                    itemplan: itemplanParalizacion
                }
            }).done(function(data) {
                origenGlobal = origen;
                console.log(origenGlobal);
                data = JSON.parse(data);
                // console.log(dropzone.maxFilesize);
                var cmbMotivo = '<option value="">Seleccionar Motivo</option>';
                data.arrayMotivo.forEach(function(element) {
                    cmbMotivo += '<option value="' + element.idMotivo + '">' + element.motivoDesc + '</option>';
                });
                $('#cmbParalizacionHtml').html(cmbMotivo);
                //insertParalaizacion(itemplanParalizacion); 
                $('.dz-message').html('<span>Subir evidencia</span>');
                modal('modalParalizacion');
            });


        }
        /******************************************/

        var itemplanParalizacion = null;
        var idMotivo = null;
        var comentario = null;
        var toog2 = 0;

        function insertParalizacion() {
            idMotivo = $('#cmbParalizacionHtml option:selected').val();
            comentario = $('#comentarioParalizacion').val();
            motivo = $('#cmbParalizacionHtml option:selected').text();

            if (itemplanParalizacion == '' || itemplanParalizacion == null) {
                return;
            }

            if (idMotivo == '' || idMotivo == null || origenGlobal == '' || origenGlobal == null) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'insertParalizacion',
                data: {
                    idMotivo: idMotivo,
                    comentario: comentario,
                    motivo: motivo,
                    itemplan: itemplanParalizacion,
                    origen: origenGlobal
                }
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    mostrarNotificacion('success', "registro correcto", "correcto");
                    modal('modalParalizacion');
                    if ($('.dz-preview').html() == undefined) {
                        location.reload();
                    }
                } else {
                    mostrarNotificacion('error', data.msj, 'error');
                }
            });
        }

        Dropzone.autoDiscover = false;
        $("#dropzoneParalizacion").dropzone({
            url: "insertFileParalizacion",
            type: 'POST',
            addRemoveLinks: true,
            autoProcessQueue: false,
            parallelUploads: 30,
            maxFilesize: 3,
            // params: {
            //        itemplan : itemplanParalizacion
            //   },
            dictResponseError: "Ha ocurrido un error en el server",

            complete: function(file) {
                if (file.status == "success") {
                    error = 0;
                }
            },
            removedfile: function(file, serverFileName) {
                var name = file.name;
                var element;
                (element = file.previewElement) != null ?
                    element.parentNode.removeChild(file.previewElement) :
                    false;
                toog2 = toog2 - 1;
            },
            init: function() {
                this.on("error", function(file, message) {
                    alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no sera tomado en cuenta');
                    return;
                    //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser�1�7 tomado en cuenta');
                    error = 1;
                    // alert(message);
                    this.removeFile(file);
                });


                var submitButton = document.querySelector("#btnEvidenciaParalizacion");
                var myDropzone = this;

                var concatEvi = '';
                submitButton.addEventListener("click", function() {
                    $('#btnEvidenciaParalizacion').css('display', 'none');
                    insertParalizacion();
                    myDropzone.processQueue();
                });

                var concatEvi = '';
                this.on("addedfile", function() {
                    toog2 = toog2 + 1;
                });

                this.on('complete', function() {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        if (error == 0) {
                            console.log(this.getUploadingFiles());
                        }

                    }
                });

                this.on("queuecomplete", function(file) {
                    var last = concatEvi.substring(0, (concatEvi.length - 1));

                    if (error == 0) {
                        updateFileParalizacion();
                    }
                });
            }
        });

        function updateFileParalizacion() {
            $.ajax({
                type: 'POST',
                url: 'updateFileParalizacion',
                data: {
                    itemplan: itemplanParalizacion
                }
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    location.reload();
                } else {
                    mostrarNotificacion('error', data.msj, 'error');
                }
            });
        }

        var itemplanGlobal = null;

        function openModalAlert(btn) {
            itemplanGlobal = btn.data('itemplan');
            modal('modalAlerta');
        }

        function aceptarRevertir() {
            $.ajax({
                type: 'POST',
                url: 'revertirParalizacion',
                data: {
                    itemplan: itemplanGlobal
                }
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    mostrarNotificacion('success', "Se a revertido la paralizaci&oacute;n correctamente", "correcto");
                    location.reload();
                    modal('modalAlerta');
                } else {
                    mostrarNotificacion('error', data.msj, 'error');
                }
            });
        }

        var itemplanSiom = null;

        function openModalCodigoSion(btn) {
            itemplanSiom = btn.data('itemplan');

            if (itemplanSiom == null || itemplanSiom == '') {
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'getDataSiom',
                data: {
                    itemplan: itemplanSiom
                }
            }).done(function(data) {
                data = JSON.parse(data);

                if (data.error == 0) {
                    $('#contTablaSiom').html(data.tablaSiom);
                    modal('modalSiom');
                } else {
                    mostrarNotificacion('error', data.msj, 'error');
                }
            })
        }

        function openGant(component) {
            var itemplan = $(component).attr('data-itm');
            $.ajax({
                    type: 'POST',
                    'url': 'hasAdju',
                    data: {
                        itemplan: itemplan
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        if (data.hasAdju >= 1) {
                            window.open("<?php echo base_url() ?>detalleGant?item=" + itemplan);
                        } else if (data.hasAdju == 0) {
                            alert('Itemplan no cuenta con los datos Basicos (Adjudicacion) para graficar el Gant.');
                        }

                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Error ', data.msj);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error mostrar el log trunco', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function() {

                });

        }

        function getAnalisisEconomico(btn) {
            var itemplan = btn.data('itemplan');
            var a = document.createElement("a");
            a.target = "_blank";
            a.href = "getAnalisisEconomico?itemplan=" + itemplan;
            a.click();
        }

        function openModalDatosSisegos(btn) {
            var itemplan = btn.data('itemplan');

            if (itemplan == null || itemplan == '') {
                return;
            }

            $.ajax({
                type: 'POST',
                'url': 'getDataSisego',
                data: {
                    itemplan: itemplan
                },
                'async': false
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contInfoDataSisego').html(data.dataInfoSisego);
                    modal('modalDatosSisegos');
                } else {
                    return;
                }
            });
        }

        var idEstacionGlobal = null;

        $('#formAdjudicaItem')
            .bootstrapValidator({
                container: '#mensajeForm',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    selectSubAdju: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un Subproyecto.</p>'
                            }
                        }
                    },
                    selectCentral: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar MDF</p>'
                            }
                        }
                    },
                    selectEECCDiseno: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una EECC Dise&ntilde;o.</p>'
                            }
                        }
                    },
                    idFechaPreAtencionCoax: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una fecha Coaxial.</p>'
                            }
                        }
                    },
                    idFechaPreAtencionFo: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una fecha FO.</p>'
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
                //var radioCheck = $('input:radio[name=radioSelecFoCo]:checked').val();

                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });

                var idFechaPreAtencionCoax = $('#idFechaPreAtencionCoax').val();
                var idFechaPreAtencionFo = $('#idFechaPreAtencionFo').val();

                var itemplan = $('#btnAdjudica').attr('data-item');
                formData.append('itemplan', itemplan);
                formData.append('idEstacion', idEstacionGlobal);
                formData.append('idFechaPreAtencionCoax', idFechaPreAtencionCoax);
                formData.append('idFechaPreAtencionFo', idFechaPreAtencionFo);

                var subProy = $.trim($('#cmbSubProy').val());
                var eecc = $.trim($('#selectEECC').val());
                var zonal = $.trim($('#selectZonal').val());
                var itemplanFil = $.trim($('#selectItemPlan').val());
                var mes = $.trim($('#selectMesEjec').val());
                var expediente = $.trim($('#selectExpediente').val());
                var idEstacion = $.trim($('#idEstacion').val());
                var idTipoPlan = $.trim($('#idTipoPlanta').val());
                var jefatura = $.trim($('#cmbJefatura').val());
                var idProyecto = $.trim($('#cmbProyecto').val());

                formData.append('subProy', subProy);
                formData.append('eecc', eecc);
                formData.append('zonal', zonal);
                formData.append('itemplanFil', itemplanFil);
                formData.append('mes', mes);
                formData.append('expediente', expediente);
                formData.append('idEstacion', idEstacion);
                formData.append('idTipoPlan', idTipoPlan);
                formData.append('jefatura', jefatura);
                formData.append('idProyecto', idProyecto);

                $.ajax({
                        data: formData,
                        url: "adjuItem",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            modal('modalEjec');
                            $("#data-table > tbody").empty();
                            $('#barraProgreso').empty();
                            mostrarNotificacion('success', 'Operaci&oacute;n exitosa.', 'Se registr&oacute; correcamente!');
                        } else if (data.error == 1) {
                            console.log(data.error);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comun&iacute;quese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });
            });

        function verProgreso(component) {
            $('#barraProgreso').empty();
            var itemplan = $(component).attr('data-item');
            var estadoplan = $(component).attr('data-est-plan');
            console.log('itemplan ' + itemplan);
            console.log('estadoplan ' + estadoplan);
            $.ajax({
                    type: 'POST',
                    url: "pqt_getProgresoItemPlan",
                    data: {
                        itemplan: itemplan,
                        estadoplan: estadoplan
                    },
                    'async': false
                })
                .done(function(data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#barraProgreso').html(data.barraProgreso);
                    } else if (data.error == 1) {
                        $('#barraProgreso').empty();
                        console.log(data.error);
                    }
                })

        }

        function abrirModalDetener(component) {
            var itemplan = $(component).attr('data-item');
            var accion = $(component).attr('data-accion');

            $("#hfItemPlan").val('');
            $("#txtComentario").val('');
            $("#hfAccionDeDetener").val('');
            $("#fEvidenciaDetener").val(null);
            $('#selectMotivo').val('').trigger('chosen:updated');

            if (itemplan == null || itemplan == '' || accion == null || accion == '') {
                return;
            } else {

                $("#hfItemPlan").val(itemplan);
                $("#hfAccionDeDetener").val(accion);
                var titulo = "";
                if (accion == "suspender") {
                    titulo = "SUSPENDER ITEMPLAN: " + itemplan;
                } else if (accion == "cancelar") {
                    titulo = "CANCELAR ITEMPLAN: " + itemplan;
                } else if (accion == "truncar") {
                    titulo = "TRUNCAR ITEMPLAN: " + itemplan;
                }
                $("#tmDetenerPlanObra").text(titulo);
                modal('modalDetenerPlanObra');
            }
        }

        // File type validation
        $("#fEvidenciaDetener").change(function() {
            var file = this.files[0];
            var fileType = file.type;
            var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
            if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))) {
                mostrarNotificacion('error', 'Error', 'Solo se permite subir archivos PDF, DOC, JPG, JPEG, & PNG');
                $("#fEvidenciaDetener").val(null);
                return false;
            }
        });

        $('#formDetenerPlanObra')
            .bootstrapValidator({
                container: '#mensajeForm1',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    selectMotivo: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un Motivo.</p>'
                            }
                        }
                    },
                    txtComentario: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe de escribir un comentario</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();

                swal({
                    title: 'Est&aacute; seguro de proceder?',
                    text: 'Asegurese de que la informacion llenada sea la correta.',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, continuar!',
                    cancelButtonClass: 'btn btn-secondary',
                    allowOutsideClick: false
                }).then(function() {
                    $.ajax({
                            type: 'POST',
                            url: 'pqt_detenerItemplan',
                            data: new FormData(formDetenerPlanObra),
                            contentType: false,
                            cache: false,
                            processData: false
                        }).done(function(data) {
                            console.log(data);
                            dataA = JSON.parse(data);
                            if (dataA.error == 0) {
                                modal('modalDetenerPlanObra');
                                filtrarTabla();
                                mostrarNotificacion('success', 'Operaci&oacute;n exitosa.', 'Se registr&oacute; correcamente!');
                            } else if (dataA.error == 1) {
                                console.log(dataA.error);
                                mostrarNotificacion('error', dataA.msj, 'Verificar');
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            mostrarNotificacion('error', 'Error', 'Comun&iacute;quese con alguna persona a cargo :');
                        });

                    console.log('aceptado');
                }, function(dismiss) {
                    console.log('cancelado');
                    $('#formDetenerPlanObra').bootstrapValidator('revalidateField', 'selectMotivo');
                });
            });

        function showMessageIsObraPublica() {
            mostrarNotificacion('warning', "El itemplan esta en proceso de validacion", "Mensaje");
        }

        function abrirModalReanudar(component) {

            var itemplan = $(component).attr('data-item');
            var accion = $(component).attr('data-accion');

            $("#hfItemPlanR").val('');
            $("#txtComentarioR").val('');
            $("#hfAccionDeReanudar").val('');
            $("#fEvidenciaReanudar").val(null);

            if (itemplan == null || itemplan == '' || accion == null || accion == '') {
                return;
            } else {

                $("#hfItemPlanR").val(itemplan);
                $("#hfAccionDeReanudar").val(accion);
                var titulo = "";
                if (accion == "reanudar-suspendido") {
                    titulo = "REANUDAR ESTADO SUSPENDIDO ITEMPLAN: " + itemplan;
                } else if (accion == "reanudar-trunco") {
                    titulo = "REANUDAR ESTADO TRUNCO ITEMPLAN: " + itemplan;
                }
                $("#tmReanudarPlanObra").text(titulo);
                modal('modalReanudarPlanObra');
            }
        }

        $('#formReanudarPlanObra')
            .bootstrapValidator({
                container: '#mensajeForm2',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    txtComentarioR: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe de escribir un comentario</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();

                swal({
                    title: 'Est&aacute; seguro de proceder?',
                    text: 'Asegurese de que la informacion llenada sea la correta.',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, continuar!',
                    cancelButtonClass: 'btn btn-secondary',
                    allowOutsideClick: false
                }).then(function() {
                    $.ajax({
                            type: 'POST',
                            url: 'pqt_reanudarItemplan',
                            data: new FormData(formReanudarPlanObra),
                            contentType: false,
                            cache: false,
                            processData: false
                        }).done(function(data) {
                            console.log(data);
                            dataA = JSON.parse(data);
                            if (dataA.error == 0) {
                                modal('modalReanudarPlanObra');
                                filtrarTabla();
                                mostrarNotificacion('success', 'Operaci&oacute;n exitosa.', 'Se registr&oacute; correcamente!');
                            } else if (dataA.error == 1) {
                                console.log(dataA.error);
                                mostrarNotificacion('error', 'Error', 'Comun&iacute;quese con alguna persona a cargo :');
                            }
                        })
                        .fail(function(jqXHR, textStatus, errorThrown) {
                            mostrarNotificacion('error', 'Error', 'Comun&iacute;quese con alguna persona a cargo :');
                        });

                    console.log('aceptado');
                }, function(dismiss) {
                    console.log('cancelado');
                    $('#formReanudarPlanObra').bootstrapValidator('revalidateField', 'txtComentario');
                });
            });

        function openModalAlertaOc() {
            mostrarNotificacion('warning', 'INFO', 'SE REQUIERE LA ORDEN DE COMPRA PARA INGRESAR A ESTA OPCION.');
        }

        function openModalLogOc(btn) {
            var itemplan = btn.data('itemplan');
            console.log(itemplan);
            $.ajax({
                type: 'POST',
                url: "openModalLogOc",
                data: {
                    itemplan: itemplan
                }
            }).done(function(data) {
                data = JSON.parse(data);
                $('#contTablaLogOc').html(data.tbLogOc);
                modal('modalLogOc');
            });
        }

        function openModalAprobacion(component) {
            $('#inputVR').val('');

            var id_ptr = $(component).attr('data-ptr');
            var grafo = $(component).attr('data-grafo');
            var from = $(component).attr('data-from');
            var area = $(component).attr('data-area');
            var itmp = $(component).attr('data-itmpl')
            var tipo = $(component).attr('data-tipo')
            console.log("id_ptr : " + id_ptr);
            $('#botonContinuar').attr('data-ptr', id_ptr);
            $('#botonContinuar').attr('data-grafo', grafo);
            $('#botonContinuar').attr('data-from', from);
            $('#botonContinuar').attr('data-area', area);
            $('#botonContinuar').attr('data-itmpl', itmp);
            $('#botonContinuar').attr('data-tipo', tipo);

            $('#botonRechazar').attr('data-ptr', id_ptr);
            $('#botonRechazar').attr('data-grafo', grafo);
            $('#botonRechazar').attr('data-from', from);
            $('#botonRechazar').attr('data-area', area);
            $('#botonRechazar').attr('data-itmpl', itmp);
            $('#botonRechazar').attr('data-tipo', tipo);
            $('#modalVR').modal('toggle');
        }

        function asignarGrafoInterna(component, estado) {

            var vale_reserva = $('#inputVR').val();
            var id_ptr = $(component).attr('data-ptr');
            var grafo = $(component).attr('data-grafo');
            var from = $(component).attr('data-from');
            var areaDesc = $(component).attr('data-area');
            var itemPl = $(component).attr('data-itmpl');
            var tipo_po = $(component).attr('data-tipo');

            var subProy = $.trim($('#selectSubProy').val());
            var eecc = $.trim($('#selectEECC').val());
            var zonal = $.trim($('#selectZonal').val());
            var item = $.trim($('#selectHasItemPlan').val());
            var mes = $.trim($('#selectMesEjec').val());
            var area = $.trim($('#selectArea').val());
            var estado = estado;
            console.log("ptr: " + id_ptr);
            $.ajax({
                    type: 'POST',
                    'url': 'asigGrafoInterna',
                    data: {
                        id_ptr: id_ptr,
                        grafo: grafo,
                        from: from,
                        areaDesc: areaDesc,
                        itemPl: itemPl,
                        subProy: subProy,
                        eecc: eecc,
                        zonal: zonal,
                        item: item,
                        mes: mes,
                        area: area,
                        estado: estado,
                        vale_reserva: vale_reserva,
                        tipo_po: tipo_po
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);

                    console.log("Aqui");
                    console.log(data);
                    if (data.error == 0) {
                        swal({
                            type: 'success',
                            title: 'Operacion exitosa',
                            text: 'Registro correcto',
                            showConfirmButton: true,
                            backdrop: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            location.reload();
                        });
                        // $('#modalVR').modal('toggle');

                        // mostrarNotificacion('success', 'Operacion exitosa.', data.msj);
                        // $('#contTabla').html(data.tablaasigGrafoInterna);
                        // initDataTable('#data-table');
                    } else if (data.error == 1) {
                        mostrarNotificacion('warning', 'Accion Incorrecta', data.msj);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function() {

                });
        }

        var estacionDesc = null;
        var itemPlanGlobalPorcentaje = null;

        function openModalPorcentaje(btn) {
            itemPlanGlobalPorcentaje = btn.data('item_plan');

            $.ajax({
                type: 'POST',
                url: 'getDataEstacionesLiquidacion',
                data: {
                    itemplan: itemPlanGlobalPorcentaje
                }
            }).done(function(data) {
                data = JSON.parse(data);
                $this = $(this);
                // $.fancybox(data.htmlEstaciones, {
                //     type: "html",


                //             css : {
                //                 'background-color' : '#000',
                //                  width   : 100,
                //                  height  : 100
                //             }
                //         ,
                //         wrap : {

                //         },
                //         thumbs  : {
                //             width   : 100,
                //             height  : 100
                //         }

                // });
                $('#contPorcentaje').html(data.htmlEstaciones);
                modal('modalPorcentaje');
                // $(".fancybox-wrap").css('width',"100% !important"); 
                // $(".fancybox-wrap").fancybox({"width":900,"height":900});
            });
        }

        var itemplanEvidenciaGlobal = null;
        var idEstacionEvidenciaGlobal = null;

        function openModalEvidenciasSinSiom(component) {
            $('#filePruebasRefleESinSiom').val(null);
            // $('#filePerfilESinSiom').val(null);
            var itemplan = $(component).data('item_plan');
            var idEstacion = $(component).data('id_estacion');
            var estacion = $(component).data('estacion');
            var flg_edit_po = $(component).data('flg_edit_po');

            if (flg_edit_po_glb != 1) {
                if (flg_edit_po == null || flg_edit_po == 0) {
                    mostrarNotificacion('error', 'Debe editar la PO o debe validar de que no se editara');
                    return;
                }
            }

            itemplanEvidenciaGlobal = itemplan;
            idEstacionEvidenciaGlobal = idEstacion;
            $('#btnRegEvidencias').attr('data-itemplan', itemplan);
            $('#btnRegEvidencias').attr('data-id_estacion', idEstacion);
            $('#btnRegEvidencias').attr('data-desc_estacion', estacion);

            modal('modalSubirEvidencia');
        }

        var costo_total = 0;

        function calcularCostoFinal() {
            var count = $('#tablaPtr tbody tr').length;
            costoMoTotalFinalGlb = 0;
            costo_total = 0;
            for (var i = 1; i <= count; i++) {
                var cantidadFin = Number($('#cantidad_' + i).val());
                var precio = Number($('#precio_' + i).html());
                var baremo = Number($('#baremo_' + i).html());
                var total = Number($('#costoTotal_edit_' + i).html());

                console.log("cantidadFin: " + cantidadFin);
                if (!isNaN(cantidadFin)) {
                    if (cantidadFin == null || cantidadFin == '' || cantidadFin == 0) {
                        console.log("eNTRO11");
                        total = Number($('#costoTotal_edit_' + i).html());
                        console.log("eNTRO11");
                        console.log(total);
                        costoMoTotalFinalGlb = (Number(costoMoTotalFinalGlb) + Number(total)).toFixed(2);
                        console.log("COSTO MO: " + costoMoTotalFinalGlb);
                    } else {
                        costoMO = cantidadFin * precio * baremo;
                        costoMO = costoMO.toFixed(2);
                        console.log("eNTRO12");
                        costo_total = (Number(costo_total) + Number(total)).toFixed(2);
                        console.log("TOTAL1: " + Number(total));
                        console.log("TOTAL2: " + Number(costo_total));
                        console.log("TOTAL: " + costo_total);
                        costoMoTotalFinalGlb = (Number(costoMO) + Number(costoMoTotalFinalGlb)).toFixed(2);
                    }
                }
            }
            $('#costoTotalFinal').val(costoMoTotalFinalGlb);
        }

        var costoMA = null;
        var costoMO = null;
        var total = null;
        var cantidadFinal = null;
        var id_ptrxactividad_zonal = null;
        var arrayData = [];
        var arrayDataInsert = [];
        var tablaActividad = null;
        var idSubProyectoGlobalPtr = null;
        var idEstadoPlanGlobalEditPtr = null;
        var costoMoTotalFinalGlb = null;

        function openModalPTR(btn) {
            var itemplan = btn.data('itemplan');
            idSubProyectoGlobalPtr = btn.data('id_subproyecto');
            idEstadoPlanGlobalEditPtr = btn.data('id_estado_plan');
            console.log("ENTRO3");
            $.ajax({
                type: 'POST',
                url: 'getPoByItemplan',
                data: {
                    itemplan: itemplan,
                    idEstadoPlan: idEstadoPlanGlobalEditPtr
                },
            }).done(function(data) {
                costoMoTotalFinalGlb = 0;
                var data = JSON.parse(data);
                modal('modalConsultaPTR');
                $('#contTablaPTR').html(data.tablaConsultaPtr);
            });
        }

        var arrayExcesoGlb = [];
        var itemplanPTRGlobal = null;
        var ptrGlobal = null;
        var costoMoTotalInicialGlb = null;

        function openModalEditarPTR(btn) {
            $('#btnActualizarPtr').prop('disabled', false);
            ptrGlobal = btn.data('ptr');
            itemplanPTRGlobal = btn.data('itemplan');
            costoMoTotalInicialGlb = btn.data('costo_mo');

            $.ajax({
                type: 'POST',
                url: 'getDetallePoEdit',
                data: {
                    itemplan: itemplanPTRGlobal,
                    ptr: ptrGlobal,
                    idSubProyecto: idSubProyectoGlobalPtr
                },
            }).done(function(data) {
                var data = JSON.parse(data);
                $('#contEditarPTR').html(data.tablaEditarPtr);
                console.log(data.tablaEditarPtr);
                $('#contTablaActividad').html(data.tablaActividad);
                // $('#contTablaActividad').css('display', 'none');
                arrayExcesoGlb = [];
                arrayData = [];
                arrayDataInsert = [];
                inicializarTabla('tablaActividad');
                if (idEstadoPlanGlobalEditPtr == 4) {
                    //$('#contTablaActividad').css('display', 'none');
                    $('#tituloActividades').css('display', 'none');
                    $('#btnActualizarPtr').css('display', 'none');
                } else {
                    $('#tituloActividades').css('display', 'block');
                    //$('#contTablaActividad').css('display', 'block');
                    $('#btnActualizarPtr').css('display', 'block');
                }
                // inicializarTabla('tablaPtr');
                modal('modalEditarPTR');
            });
        }

        //CUANDO SE AGREGA UNA NUEVA PARTIDA.
        function addActividad(btn) {
            $(btn).closest('tr').css('display', 'none');
            var codigo = btn.data('codigo');
            var actividad = btn.data('descripcion');
            var baremo = btn.data('baremo');
            var costoKit = btn.data('costo_kit');
            var idActividad = btn.data('id_actividad');
            var contAnterior = parseInt($('#tablaPtr tbody tr:last-child').attr('id'));
            var cont = contAnterior + 1;
            var precio = $('#precio_' + contAnterior).html();

            var contador = 0;
            var count = $('#tablaPtr tbody tr').length;
            for (var i = 1; i <= count; i++) {
                // var cantidadFin  = Number($('#cantidad_'+i).val());
                // var precio       = Number($('#precio_'+i).html());
                // var baremo       = Number($('#baremo_'+i).html());
                var td = $('#' + i).children().eq(0);
                var codigoPartida = td.text();
                if (codigoPartida == codigo) {
                    contador++;
                    break;
                }
            }
            if (contador > 0) {
                console.log('existe la partida');
                mostrarNotificacion('warning', 'Ya existe la partida en el detalle!!', 'Aviso');
                return;
            }


            var html = '<tr id="' + cont + '">' +
                '<td>' + codigo + '</td>' +
                '<td>' + actividad + '</td>' +
                '<td id="precio_' + cont + '">' + costoKit + '</td>' +
                '<td id="baremo_' + cont + '">' + baremo + '</td>' +
                '<td style="background:#E9C603;color:white">0</td>' +
                '<td style="background:#E9C603;color:white"><input id="cantidad_' + cont + '" type="number" data-descripcion="' + actividad + '" data-id_actividad="' + idActividad + '" data-cont="' + cont + '" data-id_ptrxactividad_zonal="0" class="form-control" value="0" onchange="calculoCantidad($(this));"></td>' +
                '<td id="costoMO_' + cont + '">0</td>' +
                '<td id="costoTotal_edit_' + cont + '">0</td>' +
                '</tr>';
            var js = $('#tablaPtr tbody').append(html);
        }

        function calculoCantidad(btn) {
            var idActividad = btn.data('id_actividad');
            id_ptrxactividad_zonal = btn.data('id_ptrxactividad_zonal');
            var cont = btn.data('cont');
            var descripcionAct = btn.data('descripcion');
            var primerCantidaFinal = btn.data('cantidad_final'); //ES LA CANTIDAD FINAL QUE SE MUESTRA AL ABRIR EL MODAL

            var json = {};
            var jsonExceso = {};


            cantidadFinal = $('#cantidad_' + cont).val();
            costoMO = $('#costoMO_' + cont).html();
            var precio = $('#precio_' + cont).html();
            var baremo = $('#baremo_' + cont).html();
            total = $('#costoTotal_edit_' + cont).html();
            cantidadInicial = $('#cantidad_in_' + cont).val();

            cantidadFinal = Number(cantidadFinal);
            console.log("CANTIDAD FINAL: " + cantidadFinal);
            costoMA = 0;
            costoMO = cantidadFinal * precio * baremo;

            // var flgExceso = getFlgExceso(primerCantidaFinal, cantidadFinal, precio, baremo);


            if (isNaN(costoMO)) {
                costoMO = 0;
            }

            costoMO = costoMO.toFixed(2);

            $('#costoMO_' + cont).html(costoMO);

            costoMO = parseFloat(costoMO);
            total = costoMO + costoMA;

            $('#costoTotal_edit_' + cont).html(total);
            console.log(json);
            json.costo_mo = costoMO;
            json.total = total;
            json.idActividad = idActividad;
            json.cantidad_final = cantidadFinal;
            json.cantidad_inicial = cantidadInicial;
            json.codigo_po = ptrGlobal;
            json.itemplan = itemplanPTRGlobal;
            json.precio = precio;
            json.baremo = baremo;
            json.descripcion = descripcionAct;

            // console.log(jsonExceso);

            jsonExceso.costo_mo = costoMO;
            jsonExceso.total = total;
            jsonExceso.idActividad = idActividad;
            jsonExceso.cantidad_final = cantidadFinal;
            jsonExceso.cantidad_inicial = cantidadFinal;
            jsonExceso.codigo_po = ptrGlobal;
            jsonExceso.itemplan = itemplanPTRGlobal;
            jsonExceso.precio = precio;
            jsonExceso.baremo = baremo;
            jsonExceso.descripcion = descripcionAct;
            // console.log(jsonExceso); 

            if (id_ptrxactividad_zonal == 0 || id_ptrxactividad_zonal == '' || id_ptrxactividad_zonal == null) {
                console.log("insert as");
                json.id_ptr_x_actividades_x_zonal = '';
                json.cantidad_final = cantidadFinal;
                json.cantidad_inicial = 0;
                var contador = 0;
                arrayDataInsert.forEach(function(data, key) {
                    if (data.idActividad == idActividad) { //SI ENCUENTRO EL MISMO REEMPLAZO
                        console.log("SE REEMPLAZA");
                        contador = 1;
                        arrayDataInsert.splice(key, 1, json);
                    }
                });

                if (contador == 0) {
                    arrayDataInsert.splice(arrayDataInsert.length, 0, json);
                }
                var contadorExceso = 0;
                arrayExcesoGlb.forEach(function(data, key) {

                    if (data.idActividad == idActividad) {

                        jsonExceso.id_ptr_x_actividades_x_zonal = '';
                        contadorExceso = 1;
                        jsonExceso.cantidad_inicial = 0;
                        arrayExcesoGlb.splice(key, 1, jsonExceso);
                    }
                });
                console.log(jsonExceso);
                if (contadorExceso == 0) {
                    jsonExceso.id_ptr_x_actividades_x_zonal = '';
                    jsonExceso.cantidad_inicial = 0;
                    console.log(jsonExceso);
                    arrayExcesoGlb.splice(arrayExcesoGlb.length, 0, jsonExceso);
                    console.log(arrayExcesoGlb);
                }

                // arrayExcesoGlb.splice(arrayData.length, 0, jsonExceso);
                // arrayDataInsert.splice(arrayData.length, 0, json);
            } else {
                console.log("ENTRO EDIT");
                json.id_ptr_x_actividades_x_zonal = id_ptrxactividad_zonal;

                var contador1 = 0;
                arrayData.forEach(function(data, key) {
                    if (data.id_ptr_x_actividades_x_zonal == id_ptrxactividad_zonal) {
                        contador1 = 1;
                        arrayData.splice(key, 1, json);
                    }
                });

                if (contador1 == 0) {
                    arrayData.splice(arrayData.length, 0, json);
                }

                var contadorExceso2 = 0;
                arrayExcesoGlb.forEach(function(data, key) {
                    if (data.id_ptr_x_actividades_x_zonal == id_ptrxactividad_zonal) {
                        contadorExceso2 = 1;

                        jsonExceso.cantidad_inicial = cantidadInicial;
                        jsonExceso.id_ptr_x_actividades_x_zonal = id_ptrxactividad_zonal;
                        arrayExcesoGlb.splice(key, 1, jsonExceso);
                    }
                });

                if (contadorExceso2 == 0) {
                    jsonExceso.cantidad_inicial = cantidadInicial;
                    jsonExceso.id_ptr_x_actividades_x_zonal = id_ptrxactividad_zonal;
                    arrayExcesoGlb.splice(arrayExcesoGlb.length, 0, jsonExceso);
                }


                // arrayData.splice(arrayData.length, 0, json);
                // arrayExcesoGlb.splice(arrayData.length, 0, jsonExceso);
            }
            //console.log("CANTIDAD COSTO FIN---");
            calcularCostoFinal();
            //console.log(arrayDataInsert);

            // //Defino los totales de mis 2 columnas en 0
            // var total_col1 = 0;
            // var total_col2 = 0;
            // //Recorro todos los tr ubicados en el tbody
            // $('#tablaPtr tbody').find('tr').each(function (i, el) {

            // //Voy incrementando las variables segun la fila ( .eq(0) representa la fila 1 )     
            // total_col1 += parseFloat($(this).find('td').eq(0).text());
            // total_col2 += parseFloat($(this).find('td').eq(1).text());

            // });
            // //Muestro el resultado en el th correspondiente a la columna
            // $('#ejemplo tfoot tr th').eq(0).text("Total " + total_col1);
            // $('#ejemplo tfoot tr th').eq(1).text("Total " + total_col2);


            var total_final = 0;
            var i = 7;
            $('table#tablaPtr tbody td:nth-child(' + i + ')').each(function(index1) {
                var total_fila = parseFloat($(this).text());
                if (!isNaN(total_fila)) {
                    total_final = total_fila + total_final;
                }
                // else {
                // $('table#tablaPtr tbody td:nth-child(6)').each(function (index) {
                // if(index1 == index) {
                // var totalIn = parseFloat($(this).text());
                // console.log("cos: "+totalIn);
                // total_final = total_fila + totalIn;
                // }


                // }
            });
            //$('#costoTotalFinal').val(total_final.toFixed(2));
            // $('table#tablaPtr tfoot th:nth-child(' + i + ')').text("Total: " + total)
        }

        var flg_edit_po_glb = null;

        function actualizarPtr() {
            if (arrayData.length > 0 || arrayDataInsert.length > 0) {
                swal({
                    title: 'Est&aacute; seguro de proceder?',
                    text: 'Asegurese de que la informacion llenada sea la correcta.',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, continuar!',
                    cancelButtonClass: 'btn btn-secondary',
                    allowOutsideClick: false
                }).then(function() {
                    if (costo_total == 0) {
                        mostrarNotificacion('warning', 'Verificar', 'El costo total no puede ser 0, verificar.');
                        return;
                    }
                    $.ajax({
                        type: 'POST',
                        url: 'actualizarPo',
                        data: {
                            costoMA: costoMA,
                            costoMO: costoMO,
                            total: total,
                            itemplan: itemplanPTRGlobal,
                            ptr: ptrGlobal,
                            cantidadFinal: cantidadFinal,
                            arrayData: arrayData,
                            arrayDataInsert: arrayDataInsert,
                            idEstadoPlan: idEstadoPlanGlobalEditPtr,
                            costoFinalPO: costoMoTotalFinalGlb
                        }
                    }).done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#btnActualizarPtr').prop('disabled', true);
                            $('#contTablaPTR').html(data.tablaConsultaPtr);
                            arrayData = [];
                            arrayDataInsert = [];
                            flg_edit_po_glb = 1;
                            if (data.flg_preliquidado == 1) {
                                swal({
                                    title: 'Se actualizo y paso a preliquidado correctamente!',
                                    text: 'Asegurese de validar la informacion!',
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
                            } else {
                                mostrarNotificacion('success', 'Actualizac&oacute;n realizada con &eacute;xito', 'correcto');
                                modal('modalEditarPTR');
                            }
                        } else {
                            mostrarNotificacion('error', data.msj, 'error al ingresar data');
                            return;
                        }
                    });
                });
            } else {
                getNoEditPo();
            }
        }

        function getNoEditPo() {
            if (itemplanPTRGlobal == null || itemplanPTRGlobal == '') {
                return;
            }

            if (ptrGlobal == null || ptrGlobal == '') {
                return;
            }

            swal({
                title: 'Est&aacute seguro de que no editara la PO?',
                text: 'Asegurese de estar seguro antes de hacer la accion.',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, guardar los datos!',
                cancelButtonClass: 'btn btn-secondary',
                allowOutsideClick: false
            }).then(function() {


                $.ajax({
                    type: 'POST',
                    url: 'getNoEditPo',
                    data: {
                        itemplan: itemplanPTRGlobal,
                        codigo_po: ptrGlobal
                    }
                }).done(function(data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        flg_edit_po_glb = 1;
                        mostrarNotificacion('success', 'Se ha confirmado que la PO no sera editada', 'correcto');
                        modal('modalEditarPTR');
                    } else {
                        mostrarNotificacion('error', 'No se confirmo', 'Verificar');
                        return;
                    }
                });
            });
        }

        //SUBO EVIDENCIA
        $('#formRegistrarEvidenciasSinSiom')
            .bootstrapValidator({
                container: '#mensajeFormE',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    filePerfilESinSiomSinSiom: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe subir Docuemnto PRUEBAS REFLECTOMETRICAS.</p>'
                            }
                        }
                    }
                    // filePerfilESinSiom: {
                        // validators: {
                            // notEmpty: {
                                // message: '<p style="color:red">(*) Debe subir Docuemnto PERFIL.</p>'
                            // }
                        // }
                    // }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();

                console.log("itemPlanGlobalSubirFoto : " + itemplanEvidenciaGlobal);
                console.log("idEstacionGlobalFoto : " + idEstacionEvidenciaGlobal);
                if (itemplanEvidenciaGlobal == null || itemplanEvidenciaGlobal == '' || idEstacionEvidenciaGlobal == null || idEstacionEvidenciaGlobal == '') {
                    return;
                }

                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');

                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });

                formData.append('itemplan', itemplanEvidenciaGlobal);

                var idestacion = $('#btnRegEvidencias').attr('data-id_estacion');
                formData.append('idEstacion', idEstacionEvidenciaGlobal);

                var input1File = document.getElementById('filePruebasRefleESinSiom');
                var file1 = input1File.files[0];
                formData.append('filePruebas', file1);

                // var input2File = document.getElementById('filePerfilESinSiom');
                // var file2 = input2File.files[0];
                formData.append('filePerfil', null);

                swal({
                    title: 'Est&aacute seguro registrar las evidencias?',
                    text: 'Asegurese de que la informacion llenada sea la correta.',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, guardar los datos!',
                    cancelButtonClass: 'btn btn-secondary',
                    allowOutsideClick: false
                }).then(function() {
                    $.ajax({
                            data: formData,
                            url: "ingresarEvidenciaLiquiTransp",
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'POST'
                        })
                        .done(function(data) {
                            var data = JSON.parse(data);
                            if (data.error == 0) {
                                $("#btnModalSubirEvidencias").click();
                                swal({
                                    title: 'Se registro correctamente!',
                                    text: 'Asegurese de validar la informacion!',
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
                                mostrarNotificacion('error', 'Error, refresque la pagina y vuelva a intentarlo!');
                            }
                        });
                }, function(dismiss) {
                    // dismiss can be "cancel" | "close" | "outside"
                    $('#formRegistrarEvidencias').bootstrapValidator('resetForm', true);
                });
            });

        function openModalCargaEvidencia(component) {
            itemplanGlobal = $(component).data('itemplan');
            $('#titModalEvidencias').text('Registrar Evidencias, ItemPlan: ' + itemplanGlobal);
            $('#formEvidencias').trigger("reset");
            modal('modalEvidencias');
        }

        function registrarEvidencias() {

            var validaArchivo1 = $('#archivo1').val().length;
            var validaArchivo2 = $('#archivo2').val().length;

            if (validaArchivo1 == 0) {
                mostrarNotificacion('warning', 'Aviso', 'Debe cargar evidencia en excel!!');
                return;
            }
            if (validaArchivo2 == 0) {
                mostrarNotificacion('warning', 'Aviso', 'Debe cargar evidencia en pdf!!');
                return;
            }

            swal({
                title: 'Esta seguro registrar las evidencias del itemplan??',
                text: 'Asegurese de validar la informacion',
                type: 'warning',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-success',
                confirmButtonText: 'SI',
                showCancelButton: true,
                cancelButtonClass: 'btn btn-danger',
                cancelButtonText: 'NO'

            }).then(function() {

                var formData = new FormData();
                var file1 = $('#archivo1')[0].files[0];
                var file2 = $('#archivo2')[0].files[0];
                formData.append('file1', file1);
                formData.append('file2', file2);
                formData.append('itemplan', itemplanGlobal);

                $.ajax({
                    type: 'POST',
                    url: 'regEvidenciaIP',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: () => {
                        $('#btnRegEvidenciaT').attr("disabled", true);
                    }
                }).done(function(data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contTabla').html(data.tablaAsigGrafo)
                        initDataTable('#data-table');
                        mostrarNotificacion('success', 'Aviso', data.msj);
                    } else {
                        mostrarNotificacion('error', 'Aviso', data.msj);
                    }
                }).always(() => {
                    $('#btnRegEvidenciaT').removeAttr("disabled");
                    modal('modalEvidencias');
                });

            }).catch(swal.noop);
        }

        function updateRequiereLicencia(btn) {
            var itemplan = btn.data('itemplan');
            var flgLicencia = null;
            if (itemplan == null || itemplan == '') {
                return;
            }

            swal({
                title: 'Requiere Licencia?',
                text: 'Al aceptar, podra ingresar las entidades de licencia.',
                type: 'warning',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-success',
                confirmButtonText: 'SI',
                showCancelButton: true,
                cancelButtonClass: 'btn btn-danger',
                cancelButtonText: 'NO'

            }).then(function(result) {
                console.log(result);
                flgLicencia = 1;
                $.ajax({
                    type: 'POST',
                    url: 'updateRequiereLicencia',
                    data: {
                        itemplan: itemplan,
                        flgLicencia: flgLicencia
                    }
                }).done(function(data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contTabla').html(data.tablaAsigGrafo)
                        initDataTable('#data-table');
                        mostrarNotificacion('success', 'Aviso', data.msj);
                    } else {
                        mostrarNotificacion('error', 'Aviso', data.msj);
                    }
                });
            }, function(dismiss) {
                if (dismiss == 'cancel') {
                    flgLicencia = 2;
                    $.ajax({
                        type: 'POST',
                        url: 'updateRequiereLicencia',
                        data: {
                            itemplan: itemplan,
                            flgLicencia: flgLicencia
                        }
                    }).done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTabla').html(data.tablaAsigGrafo)
                            initDataTable('#data-table');
                            mostrarNotificacion('success', 'Aviso', data.msj);
                        } else {
                            mostrarNotificacion('error', 'Aviso', data.msj);
                        }
                    });
                }
            });
        }

        var itemplanGbl = null;

        function openModalLicencia(btn) {
            itemplanGbl = btn.data('itemplan');

            if (itemplanGbl == null || itemplanGbl == '') {
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'getTablaLicencia',
                data: {
                    itemplan: itemplanGbl
                }
            }).done(function(data) {
                data = JSON.parse(data);
                console.log(data);
                if (data.error == 0) {
                    $('#contTablaLicencia').html(data.tablaLicencia);
                    modal('modalLicencia');
                } else {
                    mostrarNotificacion('error', 'Aviso', data.msj);
                }
            });

        }

        var idEstacionGbl = null;
        var arrayDataEntidad = [];

        function openMdlAgregarEntidad(btn) {
            arrayDataEntidad = [];
            $.ajax({
                type: 'POST',
                url: 'getTablaEntidad',
                data: {
                    itemplan: null,
                    idEstacion: null
                }
            }).done(function(data) {
                data = JSON.parse(data)
                console.log(data);
                if (data.error == 0) {
                    $('#contTablaEntidad').html(data.tablaEntidad);
                    modal('modalAgregarEntidad');
                } else {
                    return;
                }
            });
        }

        function agregarEntidad(btn) {
            var idEntidad = btn.data('id_entidad');
            var cant = btn.data('cant');
            var jsonEntidad = {};

            if ($('#check_' + cant).prop('checked')) {
                jsonEntidad.idEntidad = idEntidad;
                jsonEntidad.itemplan = itemplanGbl;
                jsonEntidad.idEstacion = idEstacionGbl;

                arrayDataEntidad.splice(arrayDataEntidad.length, 0, jsonEntidad);
                flg_estado = 1;
            } else {
                arrayDataEntidad.forEach(function(data, key) {
                    if (data.idEntidad == idEntidad) {
                        arrayDataEntidad.splice(key, 1);
                    }
                });

            }
            console.log(arrayDataEntidad);
        }

        function registrarEntidad() {
            if (arrayDataEntidad.length == 0) {
                mostrarNotificacion('warning', 'Debe Agregar Entidades', 'Verificar');
                return;
            }

            if (itemplanGbl == null || itemplanGbl == '') {
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'registrarEntidad',
                data: {
                    arrayDataEntidad: arrayDataEntidad,
                    itemplan: itemplanGbl
                }
            }).done(function(data) {
                data = JSON.parse(data);
                console.log(data.tablaLicencia);
                if (data.error == 0) {
                    $('#contTablaLicencia').html(data.tablaLicencia);

                    // $('.date_picker').datepicker(
                    // {
                    // orientation: "bottom right",
                    // todayHighlight: true,
                    // format: 'dd-mm-yyyy'
                    // });
                    modal('modalAgregarEntidad');
                    mostrarNotificacion(1, 'success', data.msj, 'Correcto');
                } else {
                    mostrarNotificacion(1, 'error', data.msj, 'Incorrecto');
                }
            });
        }

        var jsonDataLicencia = {};
        var itemplanGlbExp = null;
        var idEstacionGlbExp = null;
        var idGlobExp = null;

        function openModalRegExp1(btn) {
            var idEntidad = btn.data('id_entidad');
            idEstacionGlbExp = btn.data('id_estacion');
            itemplanGlbExp = btn.data('itemplan');
            idGlobExp = btn.data('id');

            $('#archivo').val(null);
            $('#lblarchivo').text('');

            console.log("idEntidad : " + idEntidad);
            console.log("idGlobExp : " + idGlobExp);
            console.log("itemplanGbl : " + itemplanGbl);
            if (idEntidad == null || idEntidad == '' || idGlobExp == null || idGlobExp == '' || itemplanGbl == null || itemplanGbl == '') {
                console.log("ENTRO1");
                return;
            }

            var nroExpediente = $('#exp_' + itemplanGbl + '_' + idGlobExp).val();
            var idTipoEntidad = $('#cmbTipo_' + itemplanGbl + '_' + idGlobExp + ' option:selected').val();
            var idDistrito = $('#cmbDistrito_' + itemplanGbl + '_' + idGlobExp + ' option:selected').val();
            var fechaInicio = $('#fechaIn_' + itemplanGbl + '_' + idGlobExp).val();
            var fechaFin = $('#fechaFin_' + itemplanGbl + '_' + idGlobExp).val();
            console.log("idDistrito: " + idDistrito);

            if (nroExpediente == null || nroExpediente == '') {
                mostrarNotificacion('warning', 'Ingresar nro. Expediente', 'Verificar');
                return;
            }

            if (idTipoEntidad == null || idTipoEntidad == '') {
                mostrarNotificacion('warning', 'Seleccionar tipo de entidad.', 'Verificar');
                return;
            }

            if (idDistrito == null || idDistrito == '') {
                mostrarNotificacion('warning', 'Seleccionar Distrito.', 'Verificar');
                return;
            }

            if (fechaInicio == null || fechaInicio == '') {
                mostrarNotificacion('warning', 'Ingresar fecha Inicio.', 'Verificar');
                return;
            }

            if (fechaFin == null || fechaFin == '') {
                mostrarNotificacion('warning', 'Ingresar fecha fin.', 'Verificar');
                return;
            }
            // var nueva=fechaInicio.split(" ")[0].split("-").reverse().join("-");
            // fechaInicio = nueva+' 00:00:00';

            // var nueva=fechaFin.split(" ")[0].split("-").reverse().join("-");
            // fechaFin = nueva+' 00:00:00';

            jsonDataLicencia.id = idGlobExp;
            jsonDataLicencia.itemplan = itemplanGlbExp;
            jsonDataLicencia.idEntidad = idEntidad;
            jsonDataLicencia.nroExpediente = nroExpediente;
            jsonDataLicencia.idTipoEntidad = idTipoEntidad;
            jsonDataLicencia.idDistrito = idDistrito;
            jsonDataLicencia.fechaInicio = fechaInicio;
            jsonDataLicencia.fechaFin = fechaFin;

            modal('modalRegistrarExpLic');
        }

        function guardarExpedienteEntidad() {
            var comprobar = $('#archivo').val().length;
            if (comprobar == 0) {
                swal.fire('Verificar!', 'Debe subir un archivo a procesar!!', 'warning');
                return;
            }
            var file = $('#archivo').val()
            var ext = file.substring(file.lastIndexOf("."));

            var formData = new FormData();
            var files = $('#archivo')[0].files[0];
            formData.append('file', files);
            formData.append('jsonDataLicencia', JSON.stringify(jsonDataLicencia));

            swal({
                title: "Está seguro de cargar el achivo??",
                text: "Asegurese de validar la información!!",
                icon: 'question',
                confirmButtonText: "SI",
                showCancelButton: true,
                cancelButtonText: 'NO',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function preConfirm() {
                    return regExpLicenciaPromise(formData).then(function(data) {
                        return mostrarNotificacion('success', 'Se actualizó el expediente correctamente', 'correcto');
                    }).catch(function(e) {
                        return mostrarNotificacion('warning', e.msj, 'Verificar');
                    });
                }
            });
        }


        function regExpLicenciaPromise(formData) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    type: 'POST',
                    url: 'registrarExpLicencia',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false
                }).done(function(data) {
                    console.log(data);
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contTablaLicencia').html(data.tablaLicencia);


                        modal('modalRegistrarExpLic');
                        resolve(data);
                    } else {
                        reject(data);
                    }

                });
            });
        }

        var itemplanCompGlb = null;
        var idEntidadCompGlb = null;
        var idCompGlb = null;

        function openModalComprobante(btn) {
            idEntidadCompGlb = btn.data('id_entidad');
            idCompGlb = btn.data('id');
            estado = btn.data('estado');
            itemplanCompGlb = btn.data('itemplan');

            if (idEntidadCompGlb == null || idEntidadCompGlb == '' ||
                itemplanCompGlb == null || itemplanCompGlb == '' || idCompGlb == null || idCompGlb == '') {
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'getTablaComprobanteLic',
                data: {
                    idEntidad: idEntidadCompGlb,
                    itemplan: itemplanCompGlb,
                    id: idCompGlb
                }
            }).done(function(data) {
                data = JSON.parse(data);

                if (data.error == 0) {
                    if (estado == 2) {
                        $('#btnComprobante').css('display', 'none');
                    } else {
                        $('#btnComprobante').css('display', 'block');
                    }

                    $('#contTablaComprobante').html(data.tablaEntidadItemplanEstacion);

                    // $('#fechaEmision_'+idEstacionCompGlb+'_'+idCompGlb).datepicker(
                    // {
                    // orientation: "bottom right",
                    // todayHighlight: true,
                    // templates: controls,
                    // format: 'dd-mm-yyyy',
                    // language: 'es'
                    // });

                    modal('modalComprobante');
                } else {
                    return;
                }
            });
        }

        var objComprobante = {};

        function registrarComprobante() {
            var nroComp = $('#nro_comp_' + itemplanCompGlb + '_' + idCompGlb).val();
            var fechaEmision = $('#fechaEmision_' + itemplanCompGlb + '_' + idCompGlb).val();
            var montoComp = $('#monto_' + itemplanCompGlb + '_' + idCompGlb).val();

            // var nueva=fechaEmision.split(" ")[0].split("-").reverse().join("-");
            // fechaEmision = nueva+' 00:00:00';

            if (nroComp == null || nroComp == '') {
                mostrarNotificacion('warning', 'Debe Agregar nro comprobante', 'Verificar');
                return;
            }

            if (fechaEmision == null || fechaEmision == '') {
                mostrarNotificacion('warning', 'Debe Agregar fecha de emisión', 'Verificar');
                return;
            }

            if (montoComp == null || montoComp == '') {
                mostrarNotificacion('warning', 'Debe el monto', 'Verificar');
                return;
            }

            if (itemplanCompGlb == null || itemplanCompGlb == '' ||
                idEntidadCompGlb == null || idEntidadCompGlb == '' || idCompGlb == null || idCompGlb == '') {
                console.log("ENTRO11");
                return;
            }

            objComprobante.nroComprobante = nroComp;
            objComprobante.fechaEmisionComp = fechaEmision;
            objComprobante.montoComp = montoComp;
            objComprobante.itemplan = itemplanCompGlb;
            objComprobante.idEntidad = idEntidadCompGlb;
            objComprobante.id = idCompGlb;

            var comprobante = $('#archivo_comp').val().length;
            if (comprobante == 0) {
                mostrarNotificacion('warning', 'Debe subir un archivo a procesar!!', 'Verificar');
                return;
            }
            var file = $('#archivo_comp').val()
            var ext = file.substring(file.lastIndexOf("."));

            swal({
                icon: 'warning',
                title: 'Esta seguro del llenado de info??',
                text: 'Asegurese de validar la informacion!!',
                showConfirmButton: true,
                confirmButtonText: 'SI',
                showCancelButton: true,
                cancelButtonText: 'NO',
                allowOutsideClick: false
            }).then((result) => {

                var formData = new FormData();
                var files = $('#archivo_comp')[0].files[0];

                formData.append('file', files);
                formData.append('objComprobante', JSON.stringify(objComprobante));

                $.ajax({
                    type: 'POST',
                    url: 'registrarCompLicencia',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false
                }).done(function(data) {
                    var data = JSON.parse(data);
                    console.log(data);
                    if (data.error == 0) {
                        $('#contTablaLicencia').html(data.tablaLicencia);

                        // $('.date_picker').datepicker(
                        // {
                        // orientation: "bottom right",
                        // todayHighlight: true,
                        // templates: controls,
                        // format: 'dd-mm-yyyy'
                        // });
                        modal('modalComprobante');
                        mostrarNotificacion('success', 'Se actualizó el comprobante correctamente', 'Verificar');
                    } else {
                        mostrarNotificacion('warning', data.msj, 'Verificar');
                    }

                });


            });
        }

        function cerrarLicencia(btn) {
            var itemplan = btn.data('itemplan');

            if (itemplan == null || itemplan == '') {
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'cerrarLicencia',
                data: {
                    itemplan: itemplan
                }
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    modal('modalLicencia');
                    $('#contTabla').html(data.tablaAsigGrafo)
                    initDataTable('#data-table');
                    mostrarNotificacion('success', 'Aviso', 'Se cerro la licencia correctamente');
                } else {
                    mostrarNotificacion('error', 'Aviso', data.msj);
                }
            });
        }

        function eliminarEntidad(component) {
            var jsonData = $(component).data();
            var idEntidad = jsonData.id_entidad;
            itemplanGlbExp = jsonData.itemplan;
            idGlobExp = jsonData.id;
            var posEntidad = $(component).parent().parent().parent().index();
            var trEntidad = $(component).parent().parent().parent();
            console.log('posEntidad:', posEntidad);
            console.log('trEntidad:', trEntidad);
            if (idEntidad == null || idEntidad == '' || idGlobExp == null || idGlobExp == '') {
                return;
            }
            // tableEntidadGlob.fnDeleteRow(trEntidad);eliminar fila con datable
            // trEntidad.remove(); eliminar fila tabla normal
            var formData = new FormData();
            jsonDataLicencia.id = idGlobExp;
            jsonDataLicencia.itemplan = itemplanGlbExp;
            jsonDataLicencia.idEntidad = idEntidad;
            console.log(jsonDataLicencia);
            formData.append('jsonDataLicencia', JSON.stringify(jsonDataLicencia));
            swal({
                title: "Está seguro de eliminar la entidad??",
                text: "Asegurese de validar la información!!",
                icon: 'question',
                confirmButtonText: "SI",
                showCancelButton: true,
                cancelButtonText: 'NO',
                allowOutsideClick: false,
                showLoaderOnConfirm: false,
                preConfirm: function preConfirm() {
                    return eliminarEntidadPromise(formData).then(function(data) {
                        return mostrarNotificacion('success', 'Se eliminó la entidad correctamente', 'correcto');
                    }).catch(function(e) {
                        return mostrarNotificacion('warning', e.msj, 'Verificar');
                    });
                }
            });
        }

        function eliminarEntidadPromise(formData) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    type: 'POST',
                    url: 'eliminarEntidadLicencia',
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false
                }).done(function(data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contTablaLicencia').html(data.tablaLicencia);

                        resolve(data);
                    } else {
                        reject(data);
                    }

                });
            });
        }

		function abrirEvidenciaFotos(btn) {
            var itemplan = btn.data('itemplan');
            console.log("item: "+itemplan);
			$.ajax({
				type: 'POST',
				url: 'abrirEvidenciaFotos',
				data: {
					itemplan: itemplan
				}
			}).done(function(data){
				console.log(data);
				data = JSON.parse(data);
				console.log('data parseada:',data);
				$('#contTablaEvidenciaAws').html(data.tablaEvidenciaAws);
				modal('modalEvidenciaAws');
			});
        }

        //modalLicencia
    </script>

    <script src="<?php echo base_url(); ?>public/js/js_pqt_plan_obra/jsPqtEntidadAmbiental.js"></script>
</body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->

</html>