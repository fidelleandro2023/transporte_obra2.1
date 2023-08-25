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
                           <h2>CONSULTA DE EVIDENCIAS</h2>
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
        <script src="<?php echo base_url();?>public/js/js_consultaZip/consultaZip.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        
         <script src="https://www.w3schools.com/lib/w3.js"></script>
        <script type="text/javascript">
            /*
        if(!("autofocus" in document.createElement("input"))){
            document.getElementById("inputVR").focus();
        }*/

        
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>