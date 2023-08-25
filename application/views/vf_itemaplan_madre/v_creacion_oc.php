<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
    <head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css?v=<?php echo time(); ?>"></link>
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <style type="text/css">

            .select2-dropdown {
                z-index: 100000;
            }

            @media (min-width: 768px) {
                .modal-xl {
                    width: 90%;
                    max-width:1200px;
                }
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
                    <a href="https://www.movistar.com.pe/" title="Entel PerÃƒÂº"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                    <h2>GESTI&Oacute;N ORDEN DE COMPRA DE ITEMPLAN MADRE</h2>
                    <div class="card">		   				                    

                        <div class="card-block"> 
                            <div class="row">
                                <!-- 
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>EECC</label>
                                        <select id="selectEecc" name="selectEecc" class="select2">
                                        <option value="">Seleccionar Ecc</option>
                                <?php
                                foreach ($listaEECC->result() as $row) {
                                    ?> 
                                                     <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>PROMOTOR</label>
                                        <select id="selectTipo" name="selectTipo" class="select2">
                                            <option value="">Seleccionar Promotor</option>
                                            <option value="1">EMPRESAS</option>
                                            <option value="2">RED</option>
                                        </select>
                                    </div>
                                </div>
                                -->
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>ESTADO</label>
                                        <select id="selectEstado" name="selectEstado" class="select2">
                                            <option value="">Seleccionar Tipo</option>
                                            <option value="1">PENDIENTE</option>
                                            <option value="2">ATENDIDO</option>     
                                            <option value="3">CANCELADO</option> 											
                                        </select>
                                    </div>
                                </div>
                                <!-- 
                                 <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>CESTA</label>
                                        <input id="txtCesta" type="text" class="form-control input-mask" placeholder="Cesta" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                    </div>
                                </div>
                                -->
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>SOLICITUD</label>
                                        <input id="txtSolicitud" type="text" class="form-control input-mask" placeholder="Solicitud" autocomplete="off" maxlength="20" style="border-bottom: 1px solid lightgrey">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>ITEMPLAN</label>
                                        <input id="txtItemplan" type="text" class="form-control input-mask" placeholder="Itemplan" autocomplete="off" maxlength="16" style="border-bottom: 1px solid lightgrey">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <button style="margin-top: 30px;" class="btn btn-success waves-effect" type="button" onclick="filtrarTablaHG();">CONSULTAR</button>
                                </div>  
                                <!-- 
                                                                <div class="col-sm-12 col-md-12">
                                    <a class="btn waves-effect" style="background-color: #28B463; color: white; padding: 10px" href="excelHojaGes">Descargar Detalle</a>
                                </div> 
                                -->
                            </div>
                            <div id="contTabla" class="table-responsive">
                                <?php echo $tablaSiom ?>
                            </div>
                        </div>
                    </div>
                </div>

            </section> 
        </main>




        <div class="modal fade" id="modalCertificarHG"  tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 style="margin: auto" id="tittleCertificarHG" class="modal-title"></h3>
                    </div>

                    <div class="modal-body">               
                        <div id="contTablaSiom">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>      
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEnProceso">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModalEnPro" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="sendEnProcesoForm" method="post" class="form-horizontal">
                            <div class="form-group col-sm-12">
                                <label>CESTA</label>
                                <input maxlength="16" id="txtCesta" name="txtCesta" type="text" class="form-control input-mask" autocomplete="off">
                            </div>
                            <div class="form-group col-sm-12">
                                <label>ORDEN DE COMPRA</label>
                                <input maxlength="16" id="txtOC" name="txtOC" type="text" class="form-control input-mask" autocomplete="off">
                            </div>
                            <div id="mensajeForm2"></div>  
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                                    <button id="btnSendEnProceso" type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ANTIGUO -->
        <div class="modal fade" id="modalCodigoSiom" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Ingrese c&oacute;digo SIOM (si es m&aacute;s de uno: ej. 564,1332,134)</label>
                            <input id="inputCodigoSiom" type="text" class="form-control input-mask" autocomplete="off">
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <label>Ingrese Remedy</label>
                            <input id="inputRemedy" type="text" class="form-control input-mask" autocomplete="off">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="botonContinuar" type="button" onclick="asignarCodigoSiom();" class="btn btn-link">Aceptar</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalAlertaAceptacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background:red;height:70px">
                        <h5 class="modal-title" style="color:white">&#191;EST&Aacute; SEGURO DE REALIZAR ESTA ACCI&Oacute;N?</h5>
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                        <!-- <span aria-hidden="true">&times;</span>
                        </button> -->
                    </div>
                    <div class="modal-body">
                        <a>Al aceptar, se eliminar&aacute; el registro.</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="deleteRegistroSiom();">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalReenviarTrama">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REENVIAR TRAMA</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="formReenviarTrama" method="post" class="form-horizontal">
                            <div class="form-group form-group--float col-sm-12">
                                <input disabled style="border-bottom-color: #838e83;" id="txt_mdf" name="txt_mdf" type="text" class="form-control form-control-sm form-control--active">
                                <label>MDF ACTUAL</label>
                                <i class="form-group__bar"></i>
                            </div>
                            <div class="form-group col-sm-12">
                                <label >MDF DE REENVIO</label>
                                <select id="selectMDF" name="selectMDF" class="select2">
                                    <option value="">.:Seleccionar:.</option>                                                    
                                </select>
                            </div>
                            <div id="mensajeForm"></div>  
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                                    <button id="btnSaveSendTrama" type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalNuevoEnvioEstacion">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REENVIAR TRAMA</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="formReenviarTrama2" method="post" class="form-horizontal">                            
                            <div class="form-group col-sm-12">
                                <label>ESTACION</label>
                                <select id="selectEsta" name="selectEsta" class="select2">
                                    <option value="">.:Seleccionar:.</option>                                                    
                                </select>
                            </div>
                            <div id="mensajeForm"></div>  
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                                    <button id="btnSaveSendTrama2" type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade"id="modalCertifica"  tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 style="margin: auto; font-weight: bold;" class="modal-title">CERTIFICACI&Oacute;N</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Ingresar C&oacute;digo</label>

                            <div class="input-group">
                                <div class="form-group">
                                    <input id="codCertificacion" class="form-control" type="text">
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button id="btnSaveCert" style="BACKGROUND-COLOR: #183469; COLOR: WHITE;" onclick="certificarSolicitudOc();" type="button" class="btn btn-link waves-effect">Guardar</button>
                        <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cerrar</button>
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
        <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo base_url(); ?>public/js/js_itempla_madre/jsCreacionOC.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    </body>

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>