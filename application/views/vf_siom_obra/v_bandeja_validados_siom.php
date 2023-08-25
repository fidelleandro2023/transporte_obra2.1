<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css?v=<?php echo time();?>"></link>
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                    <h2>BANDEJA VALIDADOS SIOM</h2>
                    <div class="card">		   				                    
                    
                        <div class="card-block"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>

                                        <select id="cmbSubProyecto" name="" class="select2">
                                        <option value="">Seleccionar Subproyecto</option>
                                        <?php      
                                                                                      
                                                foreach($listaSubProy->result() as $row){                      
                                                ?> 
                                                <option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
                                                <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>EECC</label>
                                        <select id="cmbEcc" name="selectEECC" class="select2">
                                        <option value="">Seleccionar Ecc</option>
                                        <?php                                                    
                                                    foreach($listaEECC->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>
                                

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>JEFATURA</label>
                                        <select id="cmbJefatura" class="form-control select2">
                                            <option value="">Seleccionar Jefatura</option>
                                            <?php foreach($cmbJefatura as $row) {
                                                echo '<option value="'.$row->jefatura.'">'.$row->jefatura.'</option>';
                                            }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>ESTADO SIOM</label>
                                        <select id="selectValidado" class="form-control select2"">                                                                                      
                                             <option selected value="VALIDANDO">VALIDANDO</option>
                                             <option value="APROBADA">APROBADA</option>
                                             <option value="OTROS">OTROS</option>                                          
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>SIROPE 04</label>
                                        <select id="selectSirope" class="form-control select2">
                                             <option selected value="SI">SI</option>
                                             <option value="NO">NO</option>                                         
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>SITUACION</label>
                                        <select id="selectSituacion" class="form-control select2"">      
                                             <option selected value="">::TODOS::</option>                                                                                  
                                             <option value="PENDIENTE">PENDIENTE</option>
                                             <option value="OBSERVADO">OBSERVADO</option>                                       
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <button class="btn btn-success waves-effect" type="button" onclick="filtrarTablaSiom()">CONSULTAR</button>
                                </div>
                            </div>
                            <div id="contTabla" class="table-responsive">
                                    <?php echo $tablaSiom?>
                            </div>
                        </div>
                    </div>
                </div>

            </section> 
        </main>
        
        <div class="modal fade" id="modalObservar">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">OBSERVAR OS</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="formSendObserOS" method="post" class="form-horizontal">
                            
                            <div class="form-group col-sm-12">
                               <label style="font-weight: bold;color: var(--azul_telefonica);">MOTIVO OBSERVACION</label>
                                <select id="selectMotivo" name="selectMotivo" class="select2">
                                     	<option value="">.:Seleccionar:.</option>  
                                     	<?php foreach($listaMotivoObs as $row) {
                                                echo '<option value="'.$row->id.'">'.$row->descripcion.'</option>';
                                            }?>                                                 
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                               <label style="font-weight: bold;color: var(--azul_telefonica);">COMENTARIO</label>
                                <textarea rows="4" cols="30" maxlength="500" id="idComentario" name="idComentario"></textarea> 
                            </div>
                            <div id="mensajeForm"></div>  
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                                    <button id="btnSaveObs" type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ----------------------------------------------------------------------- -->
     <div class="modal fade" id="modalDesObservar">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">LEVANTAR OS</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="formSendDesObserOS" method="post" class="form-horizontal">
                            <!--
                            <div class="form-group col-sm-12">
                               <label style="font-weight: bold;color: var(--azul_telefonica);">MOTIVO OBSERVACION</label>
                                <select id="selectMotivo" name="selectMotivo" class="select2">
                                     	<option value="">.:Seleccionar:.</option>  
                                     	<option value="1">MOTIVO 1</option> 
                                     	<option value="2">MOTIVO 2</option>                                                   
                                </select>
                            </div>-->
                            <div class="form-group col-sm-12">
                               <label style="font-weight: bold;color: var(--azul_telefonica);">COMENTARIO</label>
                                <textarea rows="4" cols="30" maxlength="500" id="idComentarioDes" name="idComentarioDes"></textarea> 
                            </div>
                            <div id="mensajeForm2"></div>  
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                                    <button id="btnSaveDesObs" type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" tabindex="-1" id="modalSiomLog" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">LOG DE OBSERVACIONES</h3>
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
        <script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/js/js_siom_obra/jsBandejaValidadosSiom.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>