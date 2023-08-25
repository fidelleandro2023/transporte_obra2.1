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
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <style type="text/css">
            @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
              }
            }
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
                   <a href="https://www.movistar.com.pe/" title="Movistar"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                    <h2>BOLSA PEP</h2>
                    <div class="card">		   				                    
                    
                        <div class="card-block"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>PROYECTO</label>
                                        <select id="selectProyTable" name="selectProyTable" class="select2" onchange="changeProyectTable()">
                                            <option value="">:::SELECCIONE PROYECTO:::</option>       
                                            <?php foreach($listaProy->result() as $row){ ?> 
                                                <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                             <?php }?>                              
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>
                                        <select id="selectSubProTable" name="selectSubProTable" class="select2">
                                            <option value="">:::SELECCIONE SUBPROYECTO:::</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2">
                                     <div id="contInputP1P" class="form-group">
                                        <label>PEP1</label>
                                        <input id="inputP1PTable" name="inputP1PTable" type="text" class="form-control input-mask" data-mask="P-0000-00-0000-00000" placeholder="P-0000-00-0000-00000">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                                <!-- 
                                <div class="col-sm-6 col-md-2">
                                     <div id="contInputP1P" class="form-group">
                                        <label>PEP2</label>
                                        <input id="inputP2PTable" name="inputP2PTable" type="text" class="form-control input-mask" data-mask="P-0000-00-0000-00000-000" placeholder="P-0000-00-0000-00000-000">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                                -->
                                 <div class="col-sm-6 col-md-2">
                                    <button style="margin-top: 25px;" class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">CONSULTAR</button>
                                 </div>
								<?php if($cantAddConfig){?>								 
									<div class="col-sm-12 col-md-12" style="margin-top: 25px; margin-left: auto;">
										<div class="form-group">       
											<button style="background-color: var(--verde_telefonica)" onclick="addNewPep1Pep2();" type="button" class="btn btn-success waves-effect">
											<i class="zmdi zmdi-plus-circle-o zmdi-hc-fw"></i>NUEVO PEP1 - PEP2</button>
										</div>
									 </div>
		                        <?php }?>
                            </div>
                            <div id="contTabla" class="table-responsive">
                                    <?php echo $tablaSiom; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section> 
            
            <!-- MODAL -->
            <div class="modal fade" id="modalAddPep1Pep2">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header" style="margin: auto;">
                            <h5 style="font-weight: bold;" class="modal-title pull-left">NUEVA BOLSA PEP</h5>
                        </div>
                        <div class="modal-body">
                            <form id="formAddPep1Pep2" method="post" class="form-horizontal"> 
                                <div class="row">
                                    <div class="col-sm-6 col-md-4">
                                         <div id="contInputP1P" class="form-group">
                                            <label>PEP1</label>
                                            <input id="inputP1P" name="inputP1P" type="text" class="form-control input-mask" data-mask="P-0000-00-0000-00000" placeholder="P-0000-00-0000-00000">
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="col-sm-6 col-md-4">
                                        <div id="contInputCorreP" class="form-group">
                                            <label>PEP2 - CORRELATIVO</label>
                                            <input id="inputCorreP" name="inputCorreP" type="text" class="form-control input-mask" data-mask="000" placeholder="000">
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                    -->
                                    <div class="col-sm-12 col-md-4">
                                        <div class="form-group">
                                            <label>PROYECTO</label>
                                            <select id="selectProy" name="selectProy" class="form-control select2" onchange="changueProyect()">
                                                <option value="">SELECCIONE PROYECTO</option>       
                                                <?php foreach($listaProy->result() as $row){ ?> 
                                                    <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                                 <?php }?>                              
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <div class="form-group">
                                            <label>FASE</label>
                                            <select id="selectFase" name="selectFase" class="select2 form-control">
                                                <option value="">SELECCIONE FASE</option>       
                                                <?php foreach($listafase->result() as $row){ 
														if($row->idFase == ID_FASE_2023 ||$row->idFase == ID_FASE_2022 ||$row->idFase == ID_FASE_2021 ||$row->idFase == ID_FASE_2020 || $row->idFase == ID_FASE_2019 || $row->idFase == ID_FASE_2018 || $row->idFase == ID_FASE_2017 ){?> 
                                                    <option value="<?php echo $row->idFase?>"><?php echo $row->faseDesc ?></option>
														<?php }
													}?>                                                  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        <div class="form-group">
                                            <label>SUBPROYECTO</label>
                                            <select id="selectSubproy" name="selectSubproy" class="form-control select2" multiple>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>FECHA DE PROGRAMACION</label>            
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                <div class="form-group">
                                                    <input id="inputFecProgramacion" name="inputFecProgramacion" type="text" class="form-control date-picker" placeholder="Pick a date">
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        <div class="form-group">
                                            <label>ESTACION</label>
                                            <select id="selectEstacion" name="selectEstacion" class="form-control select2" multiple>
                                               <?php                                                    
                                                    foreach($listaEstacion->result() as $row){                      
                                                ?> 
                                                <option value="<?php echo $row->idEstacion ?>"><?php echo utf8_decode($row->estacionDesc) ?></option>
                                                <?php }?>                                 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <div class="form-group">
                                            <label>TIPO PEP</label>
                                            <select id="selectTipoArea" name="selectTipoArea" class="select2 form-control">
                                                <option value="">SELECCIONE TIPO</option>    
                                                <option value="1">MAT</option>
                                                <option value="2">MO</option>
                                                <option value="3">MAT Y MO</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!--
                                    <div class="col-sm-3 col-md-3">
                                         <div id="contInputP1P" class="form-group">
                                            <label>DIAS MAT</label>
                                            <input id="diasMat" name="diasMat" type="text" class="form-control input-mask" data-mask="00" placeholder="00" onkeyup="recalFecMatYMo()">
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-md-3">
                                         <div id="contInputP1P" class="form-group">
                                            <label>DIAS MO</label>
                                            <input id="diasMo" name="diasMo" type="text" class="form-control input-mask" data-mask="00" placeholder="00" onkeyup="recalFecMatYMo()">
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-md-3">
                                         <div id="contInputP1P" class="form-group">
                                            <label>FECHA FIN MAT</label>
                                            <input id="fecFinMat" name="fecFinMat" type="text" class="form-control" disabled>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-md-3">
                                         <div id="contInputP1P" class="form-group">
                                            <label>FECHA FIN MO</label>
                                            <input id="fecFinMo" name="fecFinMo" type="text" class="form-control" disabled>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                    -->
                                    <div id="mensajeForm2"></div>  
                                    <div class="col-sm-12 col-md-12 form-group" style="text-align: right;">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button id="btnSave" type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>                        
                    </div>
                </div>
            </div>
        </main>
        
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
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/js/js_presupuesto/jsBolsaPep.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>