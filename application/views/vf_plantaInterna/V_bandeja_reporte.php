<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
   
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta charset="UTF-8">
        <!-- Vendor styles -->
        <link rel="stylesheet"
            href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet"
            href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet"
            href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
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
                <a href="https://www.movistar.com.pe/" title="Movistar"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Movistar" style="width: 36%; margin-left: -51%"></a>
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
                    <h2><?php echo $title ?></h2>
                    <div class="card">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Fecha In.</label>
                                    <input id="fechaIn" type="date" class="form-control" onchange="filtrarTabla('filtrarReporte');">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Fecha Fin.</label>
                                    <input id="fechaFin" type="date" class="form-control" onchange="filtrarTabla('filtrarReporte');">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>EECC.</label>
                                    <select id="cmbEec" class="select2 form-control" onchange="filtrarTabla('filtrarReporte');">
                                        <option value="0">Seleccionar</option>
                                        <?php 
                                            foreach ($listaEECC->result() as $row) {?>
                                                <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                           <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div id="contTablaCertificacion" class="table-responsive" style="display:none">
                                <?php echo $tablaBandejaReporte ?>
                            </div>
                     
                    </div>
                </div>
            </section>

            <div id="modalConsultaPTR" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">EDITAR PTR</h4>
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
                            <h4 class="modal-title">EDITAR PTR</h4>
                        </div>
                        <div class="modal-body">
                            <!-- <div class="form-group">
                                <input type="text" class="form-control" />
                                <button class="btn btn-info">buscar</button>
                            </div> -->
                                
                            <div class="panel panel-primary">
                                <div class="panel-heading">Agregar Actividades</div>
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
                                    <div class="panel-footer">
                                        <button id="btnActualizarPtr" type="button" class="btn btn-success boton-acepto"  onclick="actualizarPtr();">Aceptar</button>                                
                                    </div>
                                </div>    
                            </div>
                        </div>
                        <div class="modal-footer">
                            
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalAlerta" class="modal fade" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header" style="background:red">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" style="color:white">Alerta!</h4>
                            <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                        </div>
                        <div class="modal-body">
                            <a>Al aceptar, se borrarán las cantidades ingresadas.</a>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success boton-acepto" onclick="aceptarPorcentaje();">Aceptar</button>
                            <button type="button" class="btn btn-default boton-acepto" data-dismiss="modal">cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="exampleModalCenter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background:red">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="exampleModalLongTitle" style="color:white">Alerta!</h4>
                            <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                        </div>
                        <div class="modal-body">
                            <a>Al aceptar, se validará y se dará por terminada la obra.</a>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success boton-acepto" onclick="aceptarPorcentaje();">Aceptar</button>
                            <button type="button" class="btn btn-default boton-acepto" data-dismiss="modal">cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalAlertaValidacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                    <div class="modal-header" style="background:red">
                        <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a>Al aceptar, se validará y se dará por terminada la obra.</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="validarTerminarObra();">Aceptar</button>
                    </div>
                    </div>
                </div>
            </div>
        </main>
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
        <script src="<?php echo base_url();?>public/plugins/bTable/bootstrap-table.min.js?v=<?php echo time();?>"></script>        
   		<script src="<?php echo base_url();?>public/plugins/bTable/bootstrap-table-es-MX.js?v=<?php echo time();?>"></script>                                       

		<script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
                
        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script>        
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        
        
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>  
                
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>

<script type="text/javascript">

    function filtrarTabla(funcionControlador) {
		var fechaIn  = $('#fechaIn').val();
		var fechaFin = $('#fechaFin').val();		
		var idEecc   = $('#cmbEec option:selected').val();
		
		$.ajax({
			type : 'POST',
			url  : funcionControlador,
			data :  {
						'fechaIn'  : fechaIn,
						'fechaFin' : fechaFin,
						'idEecc'   : idEecc
					}
		}).done(function(data){
			data = JSON.parse(data);
			$('#contTablaCertificacion').html(data.tablaBandejaCertificacion);	
			initDataTable('#data-table');   
		});
	}
	
    $(document).ready(function(){
        //initDataTable('#tbCertificacion');
        // $('[data-toggle="tooltip"]').tooltip(); 
        // $('[data-toggle="popover"]').popover();
        $('#contTablaCertificacion').css('display', 'block');	
        });		
</script>