<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
        

        <!-- Vendor styles -->
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
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css?v=<?php echo time();?>">
        <style>
            .size{
                width: 111px;
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
                           <h2>CONSULTA</h2>
                           <hr>
		                    <div class="card">		   				                    
		                    
		                        <div class="card-block"> 
                                <div class="row">
                                    
                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>TIPO PLANTA</label>
                                                    <select id="selectTipoPlanta" name="selectTipoPlanta" class="select2" onchange="changueProyecto();">
                                                         <option>&nbsp;</option>
                                                      <?php                                                    
                                                                    foreach($listaTipoPlanta->result() as $row){                      
                                                                ?> 
                                                                 <option value="<?php echo $row->idTipoPlanta ?>"><?php echo $row->tipoPlantaDesc ?></option>
                                                                 <?php }?>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>FASE</label>
                                                    <select id="selectFase" name="selectFase" class="select2 form-control" >
                                                        <option>&nbsp;</option>
                                                            <?php                                                    
                                                                foreach($listafase->result() as $row){                      
                                                            ?> 
                                                                <option value="<?php echo $row->idFase ?>"><?php echo $row->faseDesc ?></option>
                                                                <?php }?>
                                                            
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>ITEMPLAN</label>
                                                    <input id="txtItemPlan" type="text" class="form-control input-mask" placeholder="ItemPlan" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                                   
                                                   
                                                </div>
                                                <div class="form-group">
                                                    <label>MDF/NODO</label>

                                                    <select id="nodo" name="nodo" class="select2" >
                                                    <option>&nbsp;</option>
                                                    <?php                                                    
                                                                foreach($listaNodos->result() as $row){                      
                                                            ?> 
                                                             <option value="<?php echo $row->idCentral ?>"><?php echo $row->codigo ?>-<?php echo $row->tipoCentralDesc ?></option>
                                                             <?php }?>
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>PROYECTO</label>

                                                    <select id="selectProy" name="selectProy" class="select2" onchange="changueSubProyecto();">
                                                        <option>&nbsp;</option>
                                                       
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>NOMBRE</label>
                                                        <input id="nombreproyecto" type="text" class="form-control input-mask" placeholder="nombre del proyecto" autocomplete="off" maxlength="200" style="border-bottom: 1px solid lightgrey">
												</div>
                                                
                                                <div class="form-group">
                                                    <label>ZONAL</label>

                                                    <select id="selectZonal" name="selectZonal" class="select2"  multiple >
                                                         <option>&nbsp;</option>
                                                    <?php                       if($listaZonal->result()) {           
                                            foreach($listaZonal->result() as $row){                      
                                                            ?> 
                                                             <option value="<?php echo $row->idZonal ?>"><?php echo $row->zonalDesc ?></option>
                                                             <?php } 
                                                             }?>
                                                    </select>
                                                </div>
                                                <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">CONSULTAR</button>
                                            </div>
                                             <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>SUB PROYECTO</label>

                                                    <select id="selectSubProy" name="selectSubProy" class="select2" >
                                                        <option>&nbsp;</option>
                                                       
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>ESTADO</label>

                                                    <select id="estado" name="estado" class="select2" >
                                                         <option>&nbsp;</option>
                                                    <?php                                                    
                                                                foreach($listaEstados->result() as $row){                      
                                                            ?> 
                                                             <option value="<?php echo $row->idEstadoPlan ?>"><?php echo utf8_decode($row->estadoPlanDesc) ?></option>
                                                             <?php }?>
                                                    </select>
                                                </div>
                                                                               
                                                <div class="form-group">
                                                   
                                                      <table>
                                                        <tr><td colspan=2>FECHA PREVISTA DE EJECUCION<tr>
                                                        <tr><td> <input name="fechaInicio" id="fechaInicio" type="text" class="form-control date-picker" placeholder="Desde"></td><td><input name="fechaFin" id="fechaFin" type="text" class="form-control date-picker" placeholder="Hasta"></td></tr>
                                                      </table>
                                                   
                                                    
                                                    
                                                </div>


                                                
                                            </div>
                            
                            
		   				                            <div id="contTabla" class="table-responsive">
								                            <?php echo $tablaAsigGrafo?>
		                           </div>
		   				                        </div>
		   				                    </div>
		   				                </div>

		   				                <footer class="footer hidden-xs-down">
		   				                    <p>ﾃつｩ Material Admin Responsive. All rights reserved.</p>

		   				                    <ul class="nav footer__nav">
		   				                        <a class="nav-link" href="#">Homepage</a>

		   				                        <a class="nav-link" href="#">Company</a>

		   				                        <a class="nav-link" href="#">Support</a>

		   				                        <a class="nav-link" href="#">News</a>

		   				                        <a class="nav-link" href="#">Contacts</a>
		   				                    </ul>
		                   </footer>
            </section>
            
        </main>
<!-- Small -->
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
        <div class="modal fade"id="modal-large"  tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                                        
                          <!--  -->
                        
                      <div class="card" id="contCardLog">
                   
                
                      </div>
                        
                    </div>
                  
                </div>
            </div>
        </div>


        <div class="modal fade"id="modal-motcancel"  tabindex="-1">
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



         <div class="modal fade"id="modal-mottrunco"  tabindex="-1">
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


        <div class="modal fade" id="modalDetalleVR"  tabindex="-1">
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
                    <div id="dropzoneParalizacion" class="dropzone" >
                                
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
        
				<div class="modal fade" id="modalDocumentosCV"  tabindex="-1">
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
													<td><div class="form-group col-md-6" id="contBtnTss"></div></td>
													<td><div class="form-group col-md-6" id="contBtnExpediente"></div></td>
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
				
				 <div class="modal fade"id="modalLogOc"  tabindex="-1">
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
            </div>
        </div>
        <!-- POPUP LOG-->

        <!-- Javascript -->
        <!-- ..vendors -->
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
        <script src="<?php echo base_url();?>public/js/js_planobra/jsConsulta.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        
         <script src="https://www.w3schools.com/lib/w3.js"></script>
        <script type="text/javascript">
            /*
        if(!("autofocus" in document.createElement("input"))){
            document.getElementById("inputVR").focus();
        }*/

        function openModalDetLogVR(component){
            var idSolVR = $(component).data('idsolvr');
            $.ajax({
                    type: 'POST',
                    url: 'getDetVR',
                    data: {
                        idSolVR: idSolVR
                    }
            }).done(function (data) {
                data = JSON.parse(data);
                $('#contTablaDetVR').html(data.tablaDetVR);
                modal('modalDetalleVR');

            });
        }
        

        function recogePTR(){

            console.log('entro en recogePTR...');

            //var arrayNamesptrExp = $( "input[name*='ptrExp']" );

            var arrayNamesptrExp = $("input[type=checkbox]:checked");
            var expediente = new Array();

            if(arrayNamesptrExp.length != 0){
                console.log(arrayNamesptrExp.length);
                console.log(arrayNamesptrExp);

                for(i=0;i<arrayNamesptrExp.length;i++){
                    expediente.push(arrayNamesptrExp[i].dataset.ptr + "%" + arrayNamesptrExp[i].dataset.item + "%" + arrayNamesptrExp[i].dataset.fecsol + "%" + arrayNamesptrExp[i].dataset.subproyecto + "%" + arrayNamesptrExp[i].dataset.zonal + "%" + arrayNamesptrExp[i].dataset.eecc + "%" + arrayNamesptrExp[i].dataset.area);
                }
                console.log('expediente es ');
                console.log(expediente);

                mostrarModal(expediente);

            }else{
                alert('Debe seleccionar al menos 1 registro para continuar.');
            }

        	
        }

        function mostrarModal(expediente){
            console.log('entro en registaExpediente');
            var texto = '';
            var ptrModal = '';
            var itemModal = '';
            for(j=0;j<expediente.length;j++){
                   //texto += '<label>'+expediente[j].replace('%', ' ')+'</label><br>';
                   //ptrModal = expediente[j]
                  
                   var elem = expediente[j].split('%'); 
                   ptrModal = elem[0]; 
                   itemModal = elem[1];
                   texto += '<label>'+ptrModal+'</label><br>';

                }
            var jsonExpediente =JSON.stringify(expediente);

            console.log('----------------------------');
            console.log(expediente);
            console.log(jsonExpediente);


            
            $('#seleccionados').html(texto);

            $('#botonConfirmar').attr('data-jsonptr', jsonExpediente);


            $('#modalExpediente').modal('toggle');
              
        }
              
          		
        function asignarExpediente(component){
            var vrLeng = $('#inputVR').val().length;
            
            if(vrLeng==0){
                alert('Usted no ha asignado un comentario de expediente.');
            }else{

                console.log('Asignar expediente');
                var jsonptr = $(component).attr('data-jsonptr');
                var comentario = $('#inputVR').val();
                console.log('=================');
                console.log(jsonptr);
                console.log(comentario);
                console.log('Ajax');

                

                $.ajax({
                        type    :   'POST',
                        'url'   :   'asignarExpediente',
                        data    :   {  jsonptr : jsonptr,
                                       comentario : comentario
                                   },
                        'async' :   false
                    }).done(function(data){
                        console.log('voldio del ajax');

                        var data    =   JSON.parse(data);
                        console.log('++++++++++++++++++');
                                                            
                        if(data.error == 0){
                            console.log('en el if');
                            $('#modalExpediente').modal('toggle');                         
                            mostrarNotificacion('success','Registro exitoso.',data.msj);
                            //$('#contTabla').html(data.tablaAsigGrafo)
                            //initDataTable('#data-table');
                            filtrarTabla();
                        }else if(data.error == 1){
                            console.log('en el else');
                            
                            mostrarNotificacion('error','Error al dar expediente',data.msj);
                        }
                      });

                console.log('se envio a ruta');
                       
            }
            
        }
		function filtroTipoPlanta(){

		}
		
        function filtrarTabla(){
            //var itemplan = $.trim($('#itemplan').val());
            var erroItemPlan = '';
            var itemplan = $.trim($('#txtItemPlan').val());
            //validar item plan
            //mostrarNotificacion('error','Hubo problemas al filtrar los datos!');

            if(itemplan.length < 13 && itemplan.length >= 1)
                erroItemPlan = 'ItemPlan Invalido.'

            var tipoPlanta = $.trim($('#selectTipoPlanta').val());
            var nombreproyecto = $.trim($('#nombreproyecto').val());
            var nodo = $.trim($('#nodo').val());
            var zonal = $.trim($('#selectZonal').val());
            var proy = $.trim($('#selectProy').val());
            var subProy = $.trim($('#selectSubProy').val());
            var estado = $.trim($('#estado').val());
            var selectMesPrevEjec = $.trim($('#selectMesPrevEjec').val());

            var fechaInicio0 = $('#fechaInicio').val();
            var fechaFin0 =  $('#fechaFin').val();

            var fechaInicio = fechaInicio0.replace(/-/g, '/');
            var fechaFin = fechaFin0.replace(/-/g, '/');
            
            var fechaDestinoDefault = '2018/12/31';
            var fechaDestino = '';
            var filtroPrevEjec = '';
            var idFase = $.trim($('#selectFase').val());
            
            console.log('fechaInicio es: '+fechaInicio);
            if(fechaFin0 == ''){
                //console.log('fecha fin esta vacia');
                //console.log('fecha destino sera: '+fechaDestinoDefault);
                fechaDestino = fechaDestinoDefault;
            }else{
                //console.log('fechaFin (destino) es: '+fechaFin);
                fechaDestino = fechaFin;
            }

            if( fechaInicio0 != '' ){
                filtroPrevEjec = " AND p.fechaPrevEjec BETWEEN '"+fechaInicio+"' AND '"+fechaDestino+"' ";
            }else{
                filtroPrevEjec = "";
            }

            var filtrar = false;            
            if(tipoPlanta !=''  && proy !=''  &&  subProy != '' && idFase != ''){
            	filtrar = true;
            }else if(erroItemPlan == '' && itemplan != ''){
            	filtrar = true;
            }else if(nombreproyecto != ''){
            	filtrar = true;
            }   
                
                
            if(filtrar){
				console.log(nombreproyecto);
				if(itemplan != ''){
					$.ajax({
						type	:	'POST',
						'url'	:	'pqt_perteneceactpqt',
						data	:	{itemplan : itemplan,
						             nombreproyecto : nombreproyecto},
						'async'	:	false
					})
					.done(function(data){
						var data	=	JSON.parse(data);
						if(data.error == 0){     	    	          	    	   
							if(data.permitir==0){
								
								swal({
									title: 'ItemPlan',
									text: 'El itemplan '+itemplan+' pertenece al modulo paquetizado \n Deseas ir a la opcion gestion de obra?',
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
                                    window.location.href = '<?php echo base_url()?>'+ 'pqt_consulta';
                                }, (dismiss) => {
                                });
								return false;
							}else{
								$.ajax({
									type	:	'POST',
									'url'	:	'getDataTableItem',
									data	:	{itemplan : itemplan,
												nombreproyecto : nombreproyecto,
												nodo : nodo,
												zonal     : zonal,
												proy  :	proy,
												subProy  :    subProy,
												estado : estado,
												filtroPrevEjec : filtroPrevEjec,
												tipoPlanta : tipoPlanta,
												idFase : idFase
												//area : area
											   },
									'async'	:	false
								})
								.done(function(data){
									var data	=	JSON.parse(data);
									if(data.error == 0){           	    	          	    	   
										$('#contTabla').html(data.tablaAsigGrafo)
										initDataTable('#data-table');
										
									}else if(data.error == 1){
										
										mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
									}
								  });
								  
								  
								  }
							
						}else if(data.error == 1){
							
							mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
						}
					  });
				  
			}else{//filtrar todo
				console.log("VIEJO");
				$.ajax({
									type	:	'POST',
									'url'	:	'getDataTableItem',
									data	:	{itemplan : itemplan,
												nombreproyecto : nombreproyecto,
												nodo : nodo,
												zonal     : zonal,
												proy  :	proy,
												subProy  :    subProy,
												estado : estado,
												filtroPrevEjec : filtroPrevEjec,
												tipoPlanta : tipoPlanta,
												idFase : idFase
												//area : area
											   },
									'async'	:	false
								})
								.done(function(data){
									var data	=	JSON.parse(data);
									if(data.error == 0){           	    	          	    	   
										$('#contTabla').html(data.tablaAsigGrafo)
										initDataTable('#data-table');
										
									}else if(data.error == 1){
										
										mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
									}
								  });
			}
				/**fin filtrar **/
            }else{
                //mostrarNotificacion('error',''ItemPlan',erroItemPlan');
                 mostrarNotificacion('error','ItemPlan','Debe seleccionar Filtros de Busqueda');
            }
            
            
        }
        
        
        
        
         /****************************Log*************************/
function mostrarLog(component){
            var itemplan = $(component).attr('data-idlog');
          $.ajax({
                type    :   'POST',
                'url'   :   'mostrarLogIPConsulta',
                data    :   {itemplan   :   itemplan},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){                    
                    $('#tituloModal').html('ITEMPLAN : '+itemplan);
                    $('#contCardLog').html(data.listaLog);
                    $('#modal-large').modal('toggle');
                }else if(data.error == 1){                  
                    mostrarNotificacion('error','Error al principal',data.msj);
                }
              })
              .fail(function(jqXHR, textStatus, errorThrown) {
                 mostrarNotificacion('error','Error al mostrar log principal',errorThrown+ '. Estado: '+textStatus);
              });
        }

function verMotivoCancelar(component){                
            var fecha = $(component).attr('data-fechaC');
            var itemplan = $(component).attr('data-itemC');

                    
            $.ajax({
                type    :   'POST',
                'url'   :   'getMotivoCancelConsulta',
                data    :   {itemplan   :   itemplan,
                            fecha : fecha},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){                    
                    $('#contCardMotivoCancel').html(data.motivoCancel);
                    $('#modal-motcancel').modal('toggle');  

                }else if(data.error == 1){                  
                    mostrarNotificacion('error','Error ',data.msj);
                }
              })
              .fail(function(jqXHR, textStatus, errorThrown) {
                 mostrarNotificacion('error','Error al mostrar el log cancelar',errorThrown+ '. Estado: '+textStatus);
              })
              .always(function() {
             
            });

                
        }

function closeMotivoCancelar(){              
            $('#modal-motcancel').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');          
        }


function verMotivoTrunco(component){                
            var fecha = $(component).attr('data-fechaT');
            var itemplan = $(component).attr('data-itemT');
          
             $.ajax({
                type    :   'POST',
                'url'   :   'getMotivoTruncoConsulta',
                data    :   {itemplan   :   itemplan,
                            fecha : fecha},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){                    
                    $('#contCardMotivoTrunco').html(data.motivoTrunco);
                    $('#modal-mottrunco').modal('toggle');  

                }else if(data.error == 1){                  
                    mostrarNotificacion('error','Error ',data.msj);
                }
              })
              .fail(function(jqXHR, textStatus, errorThrown) {
                 mostrarNotificacion('error','Error mostrar el log trunco',errorThrown+ '. Estado: '+textStatus);
              })
              .always(function() {
             
            });
     
        }

function closeMotivoTrunco(){              
            $('#modal-mottrunco').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');          
        }

            /********************************************************************************************************/
        
        
        function zipItemPlan(btn) {
    var itemPlan = btn.data('itemplan');
    if(itemPlan == null || itemPlan == '') {
        return;
    }
console.log(itemPlan);
    $.ajax({
        type : 'POST',
        url  : 'zipItemPlan',
        data : { itemPlan : itemPlan }
    }).done(function(data){
        try {
            data = JSON.parse(data);
            if(data.error == 0) {
                var url= data.directorioZip; 
                if(url != null) {
                    window.open(url, 'Download');
                } else {
                    alert('No tiene evidencias');
                }   
                // mostrarNotificacion('success', 'descarga realizada', 'correcto');
            } else {
                // mostrarNotificacion('error', 'descarga no realizada', 'error');            
                alert('error al descargar');
            }
        } catch(err) {
            alert(err.message);
        }
    });
}
        
        function changueProyecto(){
            var tipoplanta = $.trim($('#selectTipoPlanta').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'getProyConsulta',
                data    :   {tipoplanta  : tipoplanta},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){ 
                                           
                    $('#selectProy').html(data.listaProyectos);
                     $('#selectSubProy').html('');
                   
                   
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }

        function changueSubProyecto(){
            var proyecto = $.trim($('#selectProy').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'getSubProyConsulta',
                data    :   {proyecto  : proyecto},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){ 
                                           
                    $('#selectSubProy').html(data.listaSubProy);
                   
                   
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }
        
        
           
    var origenGlobal = null;
    function openModalParalizacion(btn, origen) { 
        var flgMotivoParalizacion = 1;
        itemplanParalizacion = btn.data('itemplan');
        $('#btnEvidenciaParalizacion').css('display', 'block');
        // console.log(drop.dropzone().maxFilesize);

        if(itemplanParalizacion == null || itemplanParalizacion == '') {
            return;
        }
        
        $.ajax({
            type : 'POST',
            url  : 'getCmbMotivo',
            data : { flgTipo  : flgMotivoParalizacion,
                     itemplan : itemplanParalizacion }
        }).done(function(data) {
            origenGlobal = origen;
            console.log(origenGlobal);
            data = JSON.parse(data);
            // console.log(dropzone.maxFilesize);
            var cmbMotivo ='<option value="">Seleccionar Motivo</option>';
            data.arrayMotivo.forEach(function(element){
                cmbMotivo+='<option value="'+element.idMotivo+'">'+element.motivoDesc+'</option>';
            });
            $('#cmbParalizacionHtml').html(cmbMotivo);
            //insertParalaizacion(itemplanParalizacion); 
            $('.dz-message').html('<span>Subir evidencia</span>');
            modal('modalParalizacion');
        });
        
        
    }
    /******************************************/

    var itemplanParalizacion = null;
    var idMotivo   = null;
    var comentario = null;
    var toog2=0;

    function insertParalizacion() {
        idMotivo   = $('#cmbParalizacionHtml option:selected').val();
        comentario = $('#comentarioParalizacion').val(); 
        motivo     = $('#cmbParalizacionHtml option:selected').text();

        if(itemplanParalizacion == ''|| itemplanParalizacion == null) {
            return;
        }
        
        if(idMotivo == '' || idMotivo == null || origenGlobal == '' || origenGlobal == null) {
            return;
        }
        
        $.ajax({
            type : 'POST',
            url  : 'insertParalizacion',
            data : { idMotivo   : idMotivo,
                    comentario : comentario,
                    motivo     : motivo,
                    itemplan   : itemplanParalizacion,
                    origen     : origenGlobal }      
        }).done(function(data) {            
            data = JSON.parse(data);
            if(data.error == 0) {
                mostrarNotificacion('success', "registro correcto", "correcto");
                modal('modalParalizacion');
                if($('.dz-preview').html() == undefined) {
                    location.reload();
                }
            } else {
                mostrarNotificacion('error', data.msj, 'error');
            }
        });     
    }

    Dropzone.autoDiscover = false;
    $("#dropzoneParalizacion").dropzone({
        url              : "insertFileParalizacion",
        type             : 'POST',
        addRemoveLinks   : true,
        autoProcessQueue : false,
        parallelUploads  : 30,
        maxFilesize      : 3,
        // params: {
        //        itemplan : itemplanParalizacion
        //   },
        dictResponseError: "Ha ocurrido un error en el server",
        
        complete: function(file){
            if(file.status == "success"){
                error=0;
            }
        },
        removedfile: function(file, serverFileName){
            var name = file.name;
            var element;
            (element = file.previewElement) != null ? 
            element.parentNode.removeChild(file.previewElement) : 
            false;
            toog2=toog2-1;		
        },
        init: function() {
            this.on("error", function(file, message) {
                    alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no sera tomado en cuenta');
                    return;
                    //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser�1�7 tomado en cuenta');
                    error=1;
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
                toog2=toog2+1;	
            });
            
            this.on('complete', function () {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
                    if(error == 0){
                        console.log(this.getUploadingFiles());
                    }	            
            
                }	        
            });
            
            this.on("queuecomplete", function (file) {
                var last = concatEvi.substring(0,(concatEvi.length - 1));	   		

                if(error == 0){
                    updateFileParalizacion();
                }
            });	
            }
        });

        function updateFileParalizacion() {
            $.ajax({
                type : 'POST',
                url  : 'updateFileParalizacion',
                data : { itemplan   : itemplanParalizacion }       
            }).done(function(data) {            
                data = JSON.parse(data);
                if(data.error == 0){ 
                    location.reload();
                } else {
                    mostrarNotificacion('error', data.msj, 'error');
                }
            });
        }
        
        var itemplanGlobal     = null;
        function openModalAlert(btn) {
            itemplanGlobal     = btn.data('itemplan');
            modal('modalAlerta');
        }
        
        function aceptarRevertir() {
            $.ajax({
                type : 'POST',
                url  : 'revertirParalizacion',
                data : { itemplan : itemplanGlobal } 
            }).done(function(data) {
                data = JSON.parse(data);
                if(data.error == 0){ 
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

            if(itemplanSiom == null || itemplanSiom == '') {
                return;
            }

            $.ajax({
                type : 'POST',
                url  : 'getDataSiom',
                data : { itemplan : itemplanSiom }
            }).done(function(data){
                data = JSON.parse(data);

                if(data.error == 0) { 
                    $('#contTablaSiom').html(data.tablaSiom);
                    modal('modalSiom');
                } else {
                    mostrarNotificacion('error', data.msj, 'error');
                }
            })
        }
        
        function  openGant(component){         
            var itemplan = $(component).attr('data-itm');          
             $.ajax({
                type    :   'POST',
                'url'   :   'hasAdju',
                data    :   {itemplan   :   itemplan},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){                    
                   if(data.hasAdju  >=  1){                	  
                       window.open("<?php echo base_url()?>detalleGant?item="+itemplan);
                   }else if(data.hasAdju  ==  0){
                       alert('Itemplan no cuenta con los datos Basicos (Adjudicacion) para graficar el Gant.');
                   }

                }else if(data.error == 1){                  
                    mostrarNotificacion('error','Error ',data.msj);
                }
              })
              .fail(function(jqXHR, textStatus, errorThrown) {
                 mostrarNotificacion('error','Error mostrar el log trunco',errorThrown+ '. Estado: '+textStatus);
              })
              .always(function() {
             
            });
             
        }
        
        function getAnalisisEconomico(btn) {
            var itemplan = btn.data('itemplan');
            var a = document.createElement("a");
            a.target = "_blank";
            a.href = "getAnalisisEconomico?itemplan="+itemplan;
            a.click();
        }
        
        function openModalDatosSisegos(btn) {
            var itemplan = btn.data('itemplan');

            if(itemplan == null || itemplan == '') {
                return;
            }
            
            $.ajax({
                type    :   'POST',
                'url'   :   'getDataSisego',
                data    :   {itemplan   :   itemplan},
                'async' :   false
            }).done(function(data){
                data = JSON.parse(data);
                if(data.error == 0) {
                    $('#contInfoDataSisego').html(data.dataInfoSisego);
                    modal('modalDatosSisegos');
                } else {
                    return;
                }
            });
        }
		
		function openModalLogOc(btn) {
			var itemplan = btn.data('itemplan');
			console.log(itemplan);
			$.ajax({
				type    : 'POST',
				url     : "openModalLogOc",
				data	: { itemplan : itemplan }
			}).done(function(data){
				data = JSON.parse(data);
				$('#contTablaLogOc').html(data.tbLogOc);
				modal('modalLogOc');
			});
		}
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>