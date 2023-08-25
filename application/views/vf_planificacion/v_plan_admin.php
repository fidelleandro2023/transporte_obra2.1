<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
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
        </main>

        <header class="header">
            <div class="navigation-trigger" data-ma-action="aside-open" data-ma-target=".sidebar">
                <div class="navigation-trigger__inner">
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                </div>
            </div>

            <div class="header__logo hidden-sm-down" style="text-align: center;">
                <a href="https://www.movistar.com.pe/" title="MOVISTAR"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo MOVISTAR" style="width: 36%; margin-left: -51%"></a>
            </div>

                <?php include('application/views/v_opciones.php'); ?>
        </header>
        <aside class="sidebar sidebar--hidden">
            <div class="scrollbar-inner">
                <div class="user">
                    <div class="user__info" data-toggle="dropdown">
                        <img class="user__img" src="<?php echo base_url();?>public/demo/img/profile-pics/8.jpg" alt="">
                        <div>
                            <div class="user__name"><?php echo $this->session->userdata('usernameSession')?></div>
                            <div class="user__email"><?php echo $this->session->userdata('descPerfilSession')?></div>
                        </div>
                    </div>
                </div>
                <ul class="navigation">
                    <?php echo $opciones?>
                </ul>
            </div>
        </aside>
        <section class="content content--full">
            <div class="content__inner">
                <h2>PLANIFICACI&Oacute;N</h2>
                <div class="card">		   				                    		                    
                    <div class="card-block">
                        <div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#registro" role="tab">REGISTRO</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#asigna" role="tab">ASIGNACI&Oacute;N</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active fade show" id="registro" role="tabpanel">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>
                                            Fase
                                        </label>
                                        <select id="cmbFase" name="cmbFase" class="select2 form-control" disabled>
                                            <?php echo ($cmbFase) ? $cmbFase : null; ?>    
                                        </select>
                                    </div>                             
                                    <div class="form-group col-md-4">
                                        <label>
                                            Subproyecto
                                        </label>
                                        <select id="cmbSub" name="cmbSub" class="select2 form-control" onchange="getDataCuotas();">
                                            <?php echo ($cmbSubProyecto) ? $cmbSubProyecto : null; ?>  
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>
                                            Cantidad Cuotas
                                        </label>
                                        <input id="cantidadCuotas" class="form-control" />
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>
                                            Nombre Plan
                                        </label>
                                        <input id="nomPlan" class="form-control" />
                                    </div>                             
                                    <div class="form-group col-md-4">
                                        <label>
                                            Cantidad Planificaci&oacute;n
                                        </label>
                                        <input id="cantidadPlan" class="form-control" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>
                                            Mes 
                                        </label>
                                        <select id="cmbMes" name="cmbMes" class="select2 form-control" onchange="">
                                            <?php echo ($cmbMes) ? $cmbMes : null; ?>
                                        </select>
                                    </div>    
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-success col-md-2" onclick="regPlanificacion();">Aceptar</button>
                                </div>
                                <div id="contTablaPlanifica" class="table-responsive">                 
                                </div>
                            </div>
                            <div class="tab-pane fade" id="asigna" role="tabpanel">
                                <div class="tab-container">
                                    <div class="form-group col-md-4">
                                        <select id="cmbPlan" name="cmbPlan" class="select2 form-control" onchange="getObras();">
                                            <?php echo ($cmbPlan) ? $cmbPlan : null; ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div>
                                            <button class="btn btn-success" onclick="openModalItemplan();">Asignar</button>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-success" onclick="generarOcPlan();">Generar OC</button>
                                        </div>
                                    </div>
                                    <div id="contTablaObra" class="table-responsive">
                                         <?php echo isset($tablaItemsPlani) ? $tablaItemsPlani : null ?>
                                    </div>
                                </div>
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
            <div class="modal fade"id="modalAsigItem"  tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">ASIGNAR ITEMPLANS</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <button class="btn btn-success col-md-2" onclick="asignarItemPlani();">Aceptar</button>
                            <div id="contTablaItem">
                                 <?php echo isset($tablaItems) ? $tablaItems : null ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
			<div class="modal fade"id="modalEditPlan"  tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">EDITAR PLAN</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="contTablaItem">
                                <div class="form-group">
                                    <label>Nombre Plan</label>
                                    <input id="edit_nom_plan" class="form-control"  />
                                </div>
                                <div class="form-group">
                                    <label>Cantidad Plan</label>
                                    <input id="edit_cantidad" class="form-control"  />
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-success" onclick="actualizarPlanAsig();">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>

    <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.resize.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/salvattore/dist/salvattore.min.js"></script>
    <script src="<?php echo base_url();?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

<!--  tables -->
    <script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
    <!-- Charts and maps-->
    <script src="<?php echo base_url();?>public/demo/js/flot-charts/curved-line.js"></script>
    <script src="<?php echo base_url();?>public/demo/js/flot-charts/line.js"></script>
    <script src="<?php echo base_url();?>public/demo/js/flot-charts/chart-tooltips.js"></script>
    <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
    <script src="<?php echo base_url();?>public/demo/js/jqvmap.js"></script>
    
    <!-- App functions and actions -->
    <script src="<?php echo base_url();?>public/js/app.min.js"></script>
    
    <!--  -->
    <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url();?>public/js/Utils.js"></script>
    <script src="<?php echo base_url();?>public/js/js_planificacion/js_plan_admin.js?v=<?php echo time();?>"></script>
    <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    
    <!-- Js de la Bandeja de Adjudicacion -->
    <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
    
    <script src="https://www.w3schools.com/lib/w3.js"></script>
</html>