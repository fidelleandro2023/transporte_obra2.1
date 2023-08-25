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
		<!-- App styles -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/font-awesome/css/font-awesome.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
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
                    <h2>LINEA OPEX POR SUBPROYECTO</h2>
                    <div class="card">		   				                    
                    
                        <div class="card-block" > 
							<div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <br>
                                        <button class="btn btn-success waves-effect" onclick="agregarLineaOpexSubproyectos()">NUEVA CONFIGURACION</button>
                                    </div> 
                                </div>
                            </div>
                            <div class="row" style="display:none">
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
                                 <div class="col-sm-6 col-md-2">
                                    <button style="margin-top: 25px;" class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">CONSULTAR</button>
                                 </div>
								<?php if($cantAddConfig){?>								 
									<!--<div class="col-sm-12 col-md-12" style="margin-top: 25px; margin-left: auto;">
										<div class="form-group">       
											<button style="background-color: var(--verde_telefonica)" onclick="addNewPep1Pep2();" type="button" class="btn btn-success waves-effect">
											<i class="zmdi zmdi-plus-circle-o zmdi-hc-fw"></i>NUEVO PEP1 - PEP2</button>
										</div>
									 </div>-->
		                        <?php }?>
                            </div>
                            <div id="contTabla" class="table-responsive">
                                    <?php echo $tablaSiom; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section> 



		<div class="modal fade" id="modalAddLineaSubproyectoOpex">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-center">NUEVA CONFIGURACION DE LINEA OPEX POR SUBPROYECTO</h1>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>LINEA X FASE</label>
                                    <select id="inputLinea" name="inputLinea" class="select2"  required>
                                        <option value="">&nbsp;</option>
                                        <?php
                                        foreach ($lineaxfase as $row) {
                                            ?> 
                                            <option value="<?php echo $row->idlineaopex_fase; ?>"><?php echo "LINEA OPEX: ".$row->descripcion." / FASE: ". $row->faseDesc; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>COMBINATORIA</label>
                                    <select id="inputCombinatoria" name="inputCombinatoria" class="select2"  required>
                                        <option value="">&nbsp;</option>
                                        <?php
                                        foreach ($combinatoria as $row) {
                                            ?> 
                                            <option value="<?php echo $row->idcombinatoria ?>"><?php echo "CECO: ".$row->ceco." / CUENTA: ". $row->cuenta." / AREA FUNCIONAL:  ". $row->areafuncional ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
								<div class="form-group">
                                    <label>Subproyecto</label>
                                    <select id="selectProy" name="selectProy" class="form-control select2" >
										<option value="">SELECCIONE SUBPROYECTO</option>       
										<?php foreach($listaSubProy->result() as $row){ ?> 
											<option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
										 <?php }?>                              
									</select>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button id="boton_multiuso"  class="btn btn-info">CONFIRMAR</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
            
            <!-- MODAL -->
            
        </main>
        
		<script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/tether/dist/js/tether.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

		<script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.resize.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/moment/min/moment.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

		<!--  tables -->
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/jszip/dist/jszip.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>

		<script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
		<script src="<?php echo base_url(); ?>public/jquery.sparkline/jquery.sparkline.min.js"></script>

		<!-- App functions and actions -->
		<script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

		<!-- Demo -->
		<script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>

		<!--  -->
		<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
		<script src="<?php echo base_url(); ?>public/js/Utils.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
		<script src="<?php echo base_url();?>public/js/js_linea_opex/jsLineaOpex.js?v=<?php echo time();?>"></script>


        <script>
			function pep2_automatico(){
				var checkBox = document.getElementById("id_pep2");

				if (checkBox.checked == true){
	 
					$('#ctn_pep2').css('display', 'inherit');
				}else{
					$('#ctn_pep2').css('display', 'none');

				}


			 }

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>