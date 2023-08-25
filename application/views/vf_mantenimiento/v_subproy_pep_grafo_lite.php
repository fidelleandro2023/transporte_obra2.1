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
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        
        <style type="text/css">
           
            .select2-dropdown {
              z-index: 100000;
            }
 
        </style>  
        
        <link rel="stylesheet" href="<?php echo base_url();?>public/demo/css/demo.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css">
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
                   <a href="https://www.movistar.com.pe/" title="Entel Perú"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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

            <aside class="chat">
                <div class="chat__header">
                    <h2 class="chat__title">Chat <small>Currently 20 contacts online</small></h2>

                    <div class="chat__search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </div>

                <div class="listview listview--hover chat__buddies scrollbar-inner">
                    <a class="listview__item chat__available">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/7.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>hey, how are you doing.</p>
                        </div>
                    </a>

                    <a class="listview__item chat__available">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/5.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>hmm...</p>
                        </div>
                    </a>

                    <a class="listview__item chat__away">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/3.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>all good</p>
                        </div>
                    </a>

                    <a class="listview__item">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/8.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>morbi leo risus portaac consectetur vestibulum at eros.</p>
                        </div>
                    </a>

                    <a class="listview__item">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/6.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>fusce dapibus</p>
                        </div>
                    </a>

                    <a class="listview__item chat__busy">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/9.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>cras mattis consectetur purus sit amet fermentum.</p>
                        </div>
                    </a>
                </div>

                <a href="messages.html" class="btn btn--action btn--fixed btn-danger"><i class="zmdi zmdi-plus"></i></a>
            </aside>

            <section class="content content--full">
		                   <div class="content__inner">
                           <h2>MANTENIMIENTO SUBPROYECTO - PEP - GRAFO</h2>
		   				                    <div class="card">		   				                    
		   				                    
		   				                    <div class="card-block">
                        <div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                          
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#messages-3" role="tab">PEP 2 - GRAFOS</a>
                                </li>
                             
                            </ul>

                            <div class="tab-content">                                 
                                    <div class="tab-pane active fade show" id="messages-3" role="tabpanel">
                                        <div class="col-sm-6 col-md-4" style="TEXT-ALIGN: center;margin-top: 25px;    margin-left: auto;">
                                                    <div class="form-group">       
                                                        <button style="background-color: (--verde_telefonica)" onclick="uploadPep2Grafo();" type="button" class="btn btn-success waves-effect">
                                                        <i class="zmdi zmdi-upload zmdi-hc-fw"></i>CARGAR GRAFOS</button>
                                                         <!-- nuevo Miguel Rios 06062018-->
                                                        <button style="background-color: (--verde_telefonica)" onclick="deletePep2GrafoError();" type="button" class="btn btn-success waves-effect">
                                                        <i class="zmdi zmdi-delete zmdi-hc-fw"></i>ELIMINAR GRAFOS</button>
                                                        <!-- -->
                                                    </div>
    			                         </div>                                    
                                        <div id="contTablaGrafo" class="table-responsive">
                                                    <?php echo $tbPepGrafo?>
                                        </div>                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
		   				                    </div>
		   				                    
		   				                </div>

		   				                <footer class="footer hidden-xs-down">
		   				                    <p>Telefonica Del Peru.</p>

		   				                  
		                   </footer>
            </section>
            
        </main>
<!-- Small -->
        
       
                           
        
        
        <div class="modal fade" id="modalAddPep1Pep2">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">NUEVO PEP1 - PEP2</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddPep1Pep2" method="post" class="form-horizontal">
                        <div class="form-group" style="text-align: center;">
                            <label class="custom-control custom-radio">
                                <input id="radioS" value="S" type="radio" name="radioPep" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">S.00/0000-00</span>
                            </label>

                            <label class="custom-control custom-radio">
                                <input value="P" type="radio" name="radioPep" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">P-0000-00-0000-00000</span>
                            </label>
                        </div>
                         <div id="contInputP1S" class="form-group" style="display: none;">
                            <label>PEP1</label>
                            <input id="inputP1S" name="inputP1S" type="text" class="form-control input-mask" data-mask="s.00/0000-00" placeholder="s.00/0000-00">
                            <i class="form-group__bar"></i>
                        </div>
                        
                         <div id="contInputP1P" class="form-group" style="display: none;">
                            <label>PEP1</label>
                            <input id="inputP1P" name="inputP1P" type="text" class="form-control input-mask" data-mask="P-0000-00-0000-00000" placeholder="P-0000-00-0000-00000">
                            <i class="form-group__bar"></i>
                        </div>
                        <div id="contInputCorreS" class="form-group" style="display: none;">
                            <label>PEP2 - CORRELATIVO</label>
                            <input id="inputCorreS" name="inputCorreS" type="text" class="form-control input-mask" data-mask="00" placeholder="00">
                            <i class="form-group__bar"></i>
                        </div>
                        <div id="contInputCorreP" class="form-group" style="display: none;">
                            <label>PEP2 - CORRELATIVO</label>
                            <input id="inputCorreP" name="inputCorreP" type="text" class="form-control input-mask" data-mask="000" placeholder="000">
                            <i class="form-group__bar"></i>
                        </div>
                        <div id="mensajeForm2"></div>  
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                <button id="btnSave" type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div role="dialog" class="modal fade" id="modalAddPep2Grafo" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                     <div class="modal-header">
                            
                            <h4 style="margin: auto;    font-weight: bold;" class="modal-title">PEP2 - GRAFO</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                    
                    <div class="modal-body">
                   <div class="row">
                              
  
 
                                    <section>
                                     <label style="font-size: smaller;text-align: left;">- El archivo a importa debe estar en formato .txt (Archivo de Texto).</label><br>
                                     <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe contar con 2 columnas separados por Tabulaciones. "PEP2 - GRAFO"</label>
                                    <br><br> 
                                     <div id="contProgres2">
                            <div id="easy2" class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                <span id="valuePie2" class="easy-pie-chart__value">0</span>
                            </div>
                        
                        
                                    </div>
                                    <div id="contSubida2">
                                        <form id="subida2">
                                            <table style="margin: auto;">
                                            	<tr>
                                                	<td>  <input id="csv2" name="userfile" type="file" /></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="submit" value="Send File" /></td>
                                                </tr>
                                                
                                            </table><br>
                                            
                                        </form>    
                                    </div>
                                  
                                        <div class="form-group">
                                    <table style="margin: auto;">
                                        <tr>
                                            <td id="respuesta2"></td>
                                         </tr>
                                     </table>
                                                                    
    	                        </div>                         
	                           </section>	                        
	                         </div> 
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div role="dialog" class="modal fade" id="modalAddSisegoPep2Grafo" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                     <div class="modal-header">
                            
                            <h4 style="margin: auto;    font-weight: bold;" class="modal-title">SISEGO - PEP2 - GRAFO</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                    
                    <div class="modal-body">
                   <div class="row">
                              
  
 
                                    <section>
                                     <label style="font-size: smaller;text-align: left;">- El archivo a importa debe estar en formato .txt (Archivo de Texto).</label><br>
                                     <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe contar con 3 columnas separados por Tabulaciones. "SISEGO - PEP2 - GRAFO"</label>
                                    <br><br> 
                                     <div id="contProgres3">
                            <div id="easy3" class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                <span id="valuePie3" class="easy-pie-chart__value">0</span>
                            </div>
                        
                        
                                    </div>
                                    <div id="contSubida3">
                                        <form id="subida3">
                                            <table style="margin: auto;">
                                            	<tr>
                                                	<td>  <input id="csv3" name="userfile" type="file" /></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="submit" value="Send File" /></td>
                                                </tr>
                                                
                                            </table><br>
                                            
                                        </form>    
                                    </div>
                                  
                                        <div class="form-group">
                                    <table style="margin: auto;">
                                        <tr>
                                            <td id="respuesta3"></td>
                                         </tr>
                                     </table>
                                                                    
    	                        </div>                         
	                           </section>	                        
	                         </div> 
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div role="dialog" class="modal fade" id="modalAddItemPep2Grafo" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                     <div class="modal-header">
                            
                            <h4 style="margin: auto;    font-weight: bold;" class="modal-title">ITEMPLAN - PEP2 - GRAFO</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                    
                    <div class="modal-body">
                   <div class="row">
                              
  
 
                                    <section>
                                     <label style="font-size: smaller;text-align: left;">- El archivo a importa debe estar en formato .txt (Archivo de Texto).</label><br>
                                     <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe contar con 4 columnas separados por Tabulaciones. "ITEMPAN - AREA - PEP2 - GRAFO"</label>
                                    <br><br> 
                                     <div id="contProgres4">
                            <div id="easy4" class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                <span id="valuePie4" class="easy-pie-chart__value">0</span>
                            </div>
                        
                        
                                    </div>
                                    <div id="contSubida4">
                                        <form id="subida4">
                                            <table style="margin: auto;">
                                            	<tr>
                                                	<td>  <input id="csv4" name="userfile" type="file" /></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="submit" value="Send File" /></td>
                                                </tr>
                                                
                                            </table><br>
                                            
                                        </form>    
                                    </div>
                                  
                                        <div class="form-group">
                                    <table style="margin: auto;">
                                        <tr>
                                            <td id="respuesta4"></td>
                                         </tr>
                                     </table>
                                                                    
    	                        </div>                         
	                           </section>	                        
	                         </div> 
                    </div>
                    
                </div>
            </div>
        </div>
        
        
        <!-- MIGUEL RIOS 06062018-->
<div class="modal fade" id="modalEliminarPEPGrafo">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">ELIMINAR PEP2 GRAFO</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formDeletePEP2GRAFO" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>PEP2</label>
                                            <select id="selectPEP2" name="selectPEP2" class="select2 form-control" onchange="changueGrafoPep2();">
                                                    <option>&nbsp;</option>
                                                      <?php foreach($listaPep2->result() as $row){ ?> 
                                                        <option value="<?php echo $row->pep2 ?>"><?php echo $row->pep2 ?></option>
                                                         <?php }?>
                                                </select>
                                    </div>                                   
                                </div>
                                <div class="col-sm-6 col-md-6">
                                     <div class="form-group">
                                         <label>GRAFO</label>
                                                 <select id="selectGRAFO" name="selectGRAFO" class="select2 form-control">
                                                   
                                                </select>
                                    </div>
                                </div>
                            </div>
                        <div id="mensajeForm"></div>  
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                <button id="btnSave" type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                    </div>                    
                </div>
            </div>
        </div>

<div class="modal fade" id="modalAddMontoPEP">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">AGREGAR/MODIFICAR MONTO PEP</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddMontoPEP" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>FORMATO SAP FIJA: P-0000-00-0000-00000<BR>FORMATO SAP COAXIAL: S.00/0000-00<BR>PEP</label>

                                           <input type="text" class="form-control" name="addpep" id="addpep" style="border-bottom: 1px solid lightgrey">
                                           
                                    </div>                                  
                                </div>
                                <div class="col-sm-6 col-md-6">
                                     <div class="form-group">
                                        <label><BR><BR>MONTO</label>
                                            <input type="number" step="0.000001" min="0" class="form-control" name="addmonto" id="addmonto" style="border-bottom: 1px solid lightgrey">   
                                    </div>
                               </div>
                            </div>
                        <div id="mensajeForm"></div>  
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                <button id="btnSave" type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                    </div>                    
                </div>
            </div>
        </div>


        <!-- MIGUEL RI0S 06062018 -->
        
        
        <!-- ..vendors -->
        
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/moment/min/moment.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

   <!--  tables -->
		<script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
       
         <script src="<?php echo base_url();?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url();?>public/jquery.sparkline/jquery.sparkline.min.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>

        <!-- Demo -->
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>      
        
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>

        <script type="text/javascript">

        $(function(){
            var ERROR = 1;
        	$('#subida2').submit(function(){
        		
        		var comprobar = $('#csv2').val().length;
        		
        		if(comprobar>0){

        			    var file = $('#csv2').val()			
        			    console.log($('#csv2').val().length);
        			    var ext = file.substring(file.lastIndexOf("."));
        			    console.log(ext);
        			    if(ext != ".txt")
        			    {
        			    	alert('Formato de archivo no v\u00E1lido. El formato correcto es .TXT');
        			        return false;
        			    }
        			    else
        			    {
        			    	var formulario = $('#subida2');
        					
        					var archivos = new FormData();	
        					
        					var url = 'upPepGra';
        					
        						for (var i = 0; i < (formulario.find('input[type=file]').length); i++) {
        						
        		               	 archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        						 
        		      		 	}
        						//$('.easy-pie-chart').data('easyPieChart').update('5');
        						$('#easy2').data('easyPieChart').update('20');
        						$('#valuePie2').html(20);
        						$('#contSubida2').hide();
        						$('#contProgres2').show();
  					            
        					$.ajax({
        						
        						url: url,
        						
        						type: 'POST',
        						
        						contentType: false, 
        						
        		            	data: archivos,
        						
        		               	processData:false,
        						
        						success: function(data){
        							console.log(data);
        							data = JSON.parse(data);
        							if(data.error == 1){
        								$('#respuesta2').html('<label style="font-size: larger; padding-top: 20px;color:#968c07;">'+data.msj+'</label>');
        								return false;	
        							}else if(data.error == 0){
        								//$('.easy-pie-chart').data('easyPieChart').update('20');
        								$('#easy2').data('easyPieChart').update('60');
        					            $('#valuePie2').html(60);
        					            ERROR = 0;
        							} 

        							console.log("ERROR"+ERROR);
                					if(ERROR==0){
             						   $.ajax({
            							     type : 'POST',
            							     url : 'upPepGra2'
                 						   }).done(function(data){
                  							  console.log(data);
                    							data = JSON.parse(data);
                    							if(data.error == 1){
                    							    ERROR = 1;
                    								$('#respuesta2').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">'+data.msj+'</label>');
                    								return false;	
                    							}else if(data.error == 0){
                    								$('#respuesta2').html('<label style="font-size: larger; padding-top: 20px;color: #0d7d3f;">Se importaron los datos con Éxito!</label>');
                    								$('#easy2').data('easyPieChart').update('100');
                            					    $('#valuePie2').html(100);
                            						$('#contTablaGrafo').html(data.tbPepGrafo);
                            			            initDataTable('#data-table3');
                            					    ERROR = 0;
                    							} 
                    					})
                					}
        														
        							 
        						}
                					
        					})
        					
      					
        					
        					return false;
        			    	
        			    }
        			    
        		}else{
        			
        			alert('Selecciona un archivo txt para importar');
        			
        			return false;
        			
        		}
        	});

            $('#subida3').submit(function(){
        		
        		var comprobar = $('#csv3').val().length;
        		
        		if(comprobar>0){

        			    var file = $('#csv3').val()			
        			    console.log($('#csv3').val().length);
        			    var ext = file.substring(file.lastIndexOf("."));
        			    console.log(ext);
        			    if(ext != ".txt")
        			    {
        			    	alert('Formato de archivo no v\u00E1lido. El formato correcto es .TXT');
        			        return false;
        			    }
        			    else
        			    {
        			    	var formulario = $('#subida3');
        					
        					var archivos = new FormData();	
        					
        					var url = 'upSPGra';
        					
        						for (var i = 0; i < (formulario.find('input[type=file]').length); i++) {
        						
        		               	 archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        						 
        		      		 	}
        						//$('.easy-pie-chart').data('easyPieChart').update('5');
        						$('#easy3').data('easyPieChart').update('20');
        						$('#valuePie3').html(20);
        						$('#contSubida3').hide();
        						$('#contProgres3').show();
  					            
        					$.ajax({
        						
        						url: url,
        						
        						type: 'POST',
        						
        						contentType: false, 
        						
        		            	data: archivos,
        						
        		               	processData:false,
        						
        						success: function(data){
        							console.log(data);
        							data = JSON.parse(data);
        							if(data.error == 1){
        								$('#respuesta3').html('<label style="font-size: larger; padding-top: 20px;color:#968c07;">'+data.msj+'</label>');
        								return false;	
        							}else if(data.error == 0){
        								//$('.easy-pie-chart').data('easyPieChart').update('20');
        								$('#easy3').data('easyPieChart').update('60');
        					            $('#valuePie3').html(60);
        					            ERROR = 0;
        							} 

        							console.log("ERROR"+ERROR);
                					if(ERROR==0){
             						   $.ajax({
            							     type : 'POST',
            							     url : 'upSPGra2'
                 						   }).done(function(data){
                  							  console.log(data);
                    							data = JSON.parse(data);
                    							if(data.error == 1){
                    							    ERROR = 1;
                    								$('#respuesta3').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">'+data.msj+'</label>');
                    								return false;	
                    							}else if(data.error == 0){
                    								$('#respuesta3').html('<label style="font-size: larger; padding-top: 20px;color: #0d7d3f;">Se importaron los datos con Éxito!</label>');
                    								$('#easy3').data('easyPieChart').update('100');
                            					    $('#valuePie3').html(100);
                            			            $('#contTablaSisegoGrafo').html(data.tbSisegoPepGrafo);			    						
                            			    		initDataTable('#data-table5');
                            					    ERROR = 0;
                    							} 
                    					})
                					}
        														
        							 
        						}
                					
        					})
        					
      					
        					
        					return false;
        			    	
        			    }
        			    
        		}else{
        			
        			alert('Selecciona un archivo txt para importar');
        			
        			return false;
        			
        		}
        	});

            $('#subida4').submit(function(){
        		
        		var comprobar = $('#csv4').val().length;
        		
        		if(comprobar>0){

        			    var file = $('#csv4').val()			
        			    console.log($('#csv4').val().length);
        			    var ext = file.substring(file.lastIndexOf("."));
        			    console.log(ext);
        			    if(ext != ".txt")
        			    {
        			    	alert('Formato de archivo no v\u00E1lido. El formato correcto es .TXT');
        			        return false;
        			    }
        			    else
        			    {
        			    	var formulario = $('#subida4');
        					
        					var archivos = new FormData();	
        					
        					var url = 'upIPGra';
        					
        						for (var i = 0; i < (formulario.find('input[type=file]').length); i++) {
        						
        		               	 archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        						 
        		      		 	}
        						//$('.easy-pie-chart').data('easyPieChart').update('5');
        						$('#easy4').data('easyPieChart').update('20');
        						$('#valuePie4').html(20);
        						$('#contSubida4').hide();
        						$('#contProgres4').show();
  					            
        					$.ajax({
        						
        						url: url,
        						
        						type: 'POST',
        						
        						contentType: false, 
        						
        		            	data: archivos,
        						
        		               	processData:false,
        						
        						success: function(data){
        							console.log(data);
        							data = JSON.parse(data);
        							if(data.error == 1){
        								$('#respuesta4').html('<label style="font-size: larger; padding-top: 20px;color:#968c07;">'+data.msj+'</label>');
        								return false;	
        							}else if(data.error == 0){
        								//$('.easy-pie-chart').data('easyPieChart').update('20');
        								$('#easy4').data('easyPieChart').update('60');
        					            $('#valuePie4').html(60);
        					            ERROR = 0;
        							} 

        							console.log("ERROR"+ERROR);
                					if(ERROR==0){
             						   $.ajax({
            							     type : 'POST',
            							     url : 'upIPGra2'
                 						   }).done(function(data){
                  							  console.log(data);
                    							data = JSON.parse(data);
                    							if(data.error == 1){
                    							    ERROR = 1;
                    								$('#respuesta4').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">'+data.msj+'</label>');
                    								return false;	
                    							}else if(data.error == 0){
                    								$('#respuesta4').html('<label style="font-size: larger; padding-top: 20px;color: #0d7d3f;">Se importaron los datos con Éxito!</label>');
                    								$('#easy4').data('easyPieChart').update('100');
                            					    $('#valuePie4').html(100);
                            			            $('#contTablaItemGrafo').html(data.tbItemPepGrafo);			    						
                            			    		initDataTable('#data-table6');
                            					    ERROR = 0;
                    							} 
                    					})
                					}
        														
        							 
        						}
                					
        					})
        					
        					return false;
        			    	
        			    }
        			    
        		}else{
        			
        			alert('Selecciona un archivo txt para importar');
        			
        			return false;
        			
        		}
        	});
        });
        
        function uploadPep2Grafo(){
            $('#contProgres2').hide();
            $('#contSubida2').show();
            $('#respuesta2').html('');
            $('#modalAddPep2Grafo').modal('toggle');
        }

        function uploadSisegoPep2Grafo(){
            $('#contProgres3').hide();
            $('#contSubida3').show();
            $('#respuesta3').html('');
            $('#modalAddSisegoPep2Grafo').modal('toggle');
        }

        function uploapItemPep2Grafo(){
            $('#contProgres4').hide();
            $('#contSubida4').show();
            $('#respuesta4').html('');
            $('#modalAddItemPep2Grafo').modal('toggle');
        }
        
        $('input[type=radio][name=radioPep]').change(function() {
            console.log(this.value);
            if (this.value == 'S') {console.log('here');
            	$('#contInputP1S').show();
                $('#contInputCorreS').show();
                $('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputP1S', true);     
            	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputCorreS', true);              		
            	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputP1P', false);    	
            	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputCorreP', false);  
                    
                $('#contInputP1P').hide();
                $('#contInputCorreP').hide();
            }
            else if (this.value == 'P') {
            	$('#contInputP1P').show();
                $('#contInputCorreP').show();   		
            	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputP1P', true);    	
            	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputCorreP', true); 
            	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputP1S', false);      
            	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputCorreS', false); 
                $('#contInputP1S').hide();
                $('#contInputCorreS').hide();
            }
            $('#formAddPep1Pep2').data('bootstrapValidator').resetForm(true);
        });
       
        
        $(document).ready(function(){
      	    
        	initDataTable('#data-table2');
        	initDataTable('#data-table4');
            initDataTable('#data-table3');
            initDataTable('#data-table5');
            initDataTable('#data-table6');
           
            $('#formAddPep1Pep2')
        	.bootstrapValidator({
        	    container: '#mensajeForm2',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
        	    	radioPep: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar Tipo de PEP.</p>'
        	                }
        	             }
         	    	   },
      	    	  inputP1S: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar PEP1.</p>'
        	                },stringLength: {
        	                    min: 12,
        	                    message: '<p style="color:red">(*) El PEP debe tener 12 caracteres</p>'
        	                }
        	             }
         	    	   },
      	    	  inputP1P: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar PEP1.</p>'
    	                },stringLength: {
    	                	min: 20,
    	                    message: '<p style="color:red">(*) El PEP debe tener 20 caracteres</p>'
    	                }
    	             }
     	    	   },
      	    	  inputCorreS: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresa correlativo PEP2.</p>'
    	                },stringLength: {
    	                	min: 2,
    	                    message: '<p style="color:red">(*) El correlativo debe tener 2 caracteres</p>'
    	                }
    	             }
     	    	   },
      	    	  inputCorreP: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresa correlativo PEP2.</p>'
    	                },stringLength: {
    	                	min: 3,
    	                    message: '<p style="color:red">(*) El correlativo debe tener 3 caracteres </p>'
    	                }
    	             }
     	    	   } 
        	    }
        	}).on('success.form.bv', function(e) {
        		e.preventDefault();
        		
        		
        	    var $form    = $(e.target),
        	        formData = new FormData(),
        	        params   = $form.serializeArray(),
        	        bv       = $form.data('bootstrapValidator');	 
        	   
        		    $.each(params, function(i, val) {
        		        formData.append(val.name, val.value);
        		    });

        		    $.ajax({
    			        data: formData,
    			        url: "addP1P2",
    			        cache: false,
    		            contentType: false,
    		            processData: false,
    		            type: 'POST'
    			  	})
    				  .done(function(data) {  
    					    data = JSON.parse(data);
    				    	if(data.error == 0){
    				    		$('#contTablaPep1Pep2').html(data.tbPep1Pep2);			    					
    		       	    	    initDataTable('#data-table4');
       		       	    	    $('#modalAddPep1Pep2').modal('toggle');  
    				    		mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
    						}else if(data.error == 1){
    							console.log(data.error);
    						}
    			  	  })
    			  	  .fail(function(jqXHR, textStatus, errorThrown) {
    			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
    			  	  })
    			  	  .always(function() {
    			      	 
    			  	});
        		   
        	    
        	});

                        
        	$('#formAddSubPep')
        	.bootstrapValidator({
        	    container: '#mensajeForm',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
        	    	
    	            selectSubProy2: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar Sub Proyecto.</p>'
        	                }
        	            }
        	        },
        	        selectArea: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar Area.</p>'
        	                }
        	            }
        	        },	 	
        	        selectPep: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar PEP 1</p>'
        	                },
              	             callback: {
          	                    message: '<p style="color:red">(*) El subproyecto ya se encuentra registrado con la pep seleccionada</p>',
          	                    callback: function(value, validator) {
          	                    	var subPro = $('#selectSubProy2').val();
              	                    var area = $('#selectArea').val();
              	                    console.log('subPro:'+subPro);
              	                    if(subPro!=null && area!=null){
              	                    	result = existePepSub(subPro, area, value);
              	                        if(result == '1'){//Existe
              		                        return false;
              	                        }else{
              		                        return true;
              	                        }
              	                    }else{
                	                       return true;
                  	                    }
          	                    }
          	                }
        	            }
    	            }    
        	    }
        	}).on('success.form.bv', function(e) {
        		e.preventDefault();
        		
        		
        	    var $form    = $(e.target),
        	        formData = new FormData(),
        	        params   = $form.serializeArray(),
        	        bv       = $form.data('bootstrapValidator');	 
        	   
        		    $.each(params, function(i, val) {
        		        formData.append(val.name, val.value);
        		    });
        		    var subProy = $.trim($('#selectSubProy').val()); 
        		    formData.append('subProy', subProy);
        		    $.ajax({
    			        data: formData,
    			        url: "addSubPep",
    			        cache: false,
    		            contentType: false,
    		            processData: false,
    		            type: 'POST'
    			  	})
    				  .done(function(data) {  
    					    	data = JSON.parse(data);
    				    	if(data.error == 0){
    				    		console.log(data.error);    				    		
    				    		$('#contTabla').html(data.tablaSubProyPep);
    				    		console.log(data.tablaSubProyPep);    				
    		       	    	    initDataTable('#data-table');
    				    		$('#modalAddSubPep').modal('toggle');
    				    		mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
    						}else if(data.error == 1){
    							console.log(data.error);
    						}
    			  	  })
    			  	  .fail(function(jqXHR, textStatus, errorThrown) {
    			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
    			  	  })
    			  	  .always(function() {
    			      	 
    			  	});
        		   
        	    
        	});

        	

        });
        
        function addNewPep1Pep2(){         	            	
        	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputP1S', false);    		
        	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputP1P', false);    	
        	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputCorreP', false);  
        	$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputCorreS', false); 
        	$('#formAddPep1Pep2').data('bootstrapValidator').resetForm(true);
        	$('input[type=radio][name=radioPep]').prop( "checked", false );
        	$('#contInputP1S').hide();
            $('#contInputCorreS').hide();
            $('#contInputP1P').hide();
            $('#contInputCorreP').hide();
            $('#modalAddPep1Pep2').modal('toggle');        	
          }
        
        function deletesubPep(component){
        	swal({
                title: 'Está seguro de eliminar el Subproyecto - pep ?',
                text: 'Asegurese de validar la información seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){

            	var id_subpep = $(component).attr('data-id_ps');
           	    var subProy = $.trim($('#selectSubProy').val()); 
            	console.log("id_subpep:"+id_subpep);
            	
         	    $.ajax({
         	    	type	:	'POST',
         	    	url     : "delSubPep",
    			    data: {'id_subpep' : id_subpep,
    			           'subProy' :   subProy},
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data = JSON.parse(data);
					console.log(data);
			    	if(data.error == 0){
			    		$('#contTabla').html(data.tablaSubProyPep);
			    		console.log(data.tablaSubProyPep);    				
	       	    	    initDataTable('#data-table');
    					mostrarNotificacion('success','Registro',data.msj);	
    				
			    	}else if(data.error == 1){
				    	
						mostrarNotificacion('error','Error',data.msj);
					}
         		  })
         		  .fail(function(jqXHR, textStatus, errorThrown) {
         		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
         		  })
         		  .always(function() {
         	  	 
         		});
         	   
            });            

         
         }
        function revalidatePep(){
            if($('#selectPep').val()!=null){
          	  $('#formAddSubPep').bootstrapValidator('revalidateField', 'selectPep');   
            }         
         }

        
        function addNewSubPep(){
     	   $('#selectSubProy2').val('').trigger('change');
     	   $('#selectArea').val('').trigger('change');
     	   $('#selectPep').val('').trigger('change');
     	   $('#formAddSubPep').bootstrapValidator('resetForm', true);        	
     	   $('#modalAddSubPep').modal('toggle');        	
        }
        
     

        function filtrarTabla(){
     	    var subProy = $.trim($('#selectSubProy').val()); 
       	    $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'getPepData',
       	    	data	:	{subProy  :	subProy},
       	    	'async'	:	false
       	    })
       	    .done(function(data){
       	    	var data	=	JSON.parse(data);
       	    	if(data.error == 0){           	    	          	    	   
       	    		$('#contTabla').html(data.tablaSubProyPep)
       	    	    initDataTable('#data-table');
       	    		
       			}else if(data.error == 1){
       				
       				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
       			}
       		  });
        }
        
        function existePepSub(subpro, area, pep){
        	var result = $.ajax({
        		type : "POST",
        		'url' : 'valiPepsub',
        		data : {
        			'subpro' : subpro,  'pep' : pep , 'area' : area
        		},
        		'async' : false
        	}).responseText;
        	return result;
        }

        function deletePep1Pep2(component){
        	swal({
                title: 'Está seguro de eliminar Pep1 - Pep2 ?',
                text: 'Asegurese de validar la información seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){

            	var id_pp = $(component).attr('data-id_pp');
            	
         	    $.ajax({
         	    	type	:	'POST',
         	    	url     : "delPepPep",
    			    data: {'id_pp' : id_pp},
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data = JSON.parse(data);
					console.log(data);
			    	if(data.error == 0){
			    		$('#contTablaPep1Pep2').html(data.tbPep1Pep2);			    						
			    		initDataTable('#data-table4');
    					mostrarNotificacion('success','Registro',data.msj);	
    				
			    	}else if(data.error == 1){
				    	
						mostrarNotificacion('error','Error',data.msj);
					}
         		  })
         		  .fail(function(jqXHR, textStatus, errorThrown) {
         		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
         		  })
         		  .always(function() {
         	  	 
         		});
         	   
            });            

         
         }

    function reloadGrafos(){
        $('#contRefresh').hide();
        $('#contProgres').show();
        
        $('.easy-pie-chart').data('easyPieChart').update('20');
        $('#valuePie').html(20);   

          $.ajax({
     	    	type	:	'POST',
     	    	url     : "regra1"  			   
     	    })
     	    .done(function(data){             	    
     	    	var data = JSON.parse(data);
  				
  		    	if(data.error == 0){
   		    	   $('.easy-pie-chart').data('easyPieChart').update('50');
	  		        $('#valuePie').html(50);   
    		    	  $.ajax({
    	  	     	    	type	:	'POST',
    	  	     	    	url     : "regra2"  			   
    	  	     	    })
    	  	     	    .done(function(data){             	    
    	  	     	    	var data = JSON.parse(data);
    	  	  				
    	  	  		    	if(data.error == 0){ 	
           	  	  		    	$('.easy-pie-chart').data('easyPieChart').update('80');
         	 	  		        $('#valuePie').html(80); 

             	 	  		    $.ajax({
            	  	     	    	type	:	'POST',
            	  	     	    	url     : "reTables"  			   
            	  	     	    })
            	  	     	    .done(function(data){             	    
            	  	     	    	var data = JSON.parse(data);
            	  	  				
            	  	  		    	if(data.error == 0){        	  	  		    	
                   	  	  		    	$('.easy-pie-chart').data('easyPieChart').update('100');
                 	 	  		        $('#valuePie').html(100); 
                 	 	  		        
                 	 	  		        $('#respuesta').html('SE COMPLETO LA ACTUALIZACIÓN.');

                     	 	  		   
                     	 	  		    
                			    		$('#contTabla').html(data.tbPep1SubPro);
                	       	    	    initDataTable('#data-table');
                	       	    	   
                	       	    	    $('#contTablaPep1Pep2').html(data.tbPep1Pep2);			    						
                			    		initDataTable('#data-table4');
                			    		
                			    		$('#contTablaPresu').html(data.tbPep1Presu);
                			    		initDataTable('#data-table2');

                			    		$('#contTablaGrafo').html(data.tbPepGrafo);
                			            initDataTable('#data-table3');
                			            
                     	 	  		    mostrarNotificacion('success','UPDATE','Se completo la actualización!');	
            	  	  		    	}else if(data.error == 1){
            	  	  			    	
            	  	  					mostrarNotificacion('error','Error',data.msj);
            	  	  				}
            	  	     		  })
            	  	     		  .fail(function(jqXHR, textStatus, errorThrown) {
            	  	     		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
            	  	     		  })
            	  	     		  .always(function() {
            	  	     	  	 
            	  	     		});
         	 	  		       
    	  	  		    	}else if(data.error == 1){
    	  	  			    	
    	  	  					mostrarNotificacion('error','Error',data.msj);
    	  	  				}
    	  	     		  })
    	  	     		  .fail(function(jqXHR, textStatus, errorThrown) {
    	  	     		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
    	  	     		  })
    	  	     		  .always(function() {
    	  	     	  	 
    	  	     		});
  	  				
  		    	}else if(data.error == 1){
  			    	
  					mostrarNotificacion('error','Error',data.msj);
  				}
     		  })
     		  .fail(function(jqXHR, textStatus, errorThrown) {
     		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
     		  })
     		  .always(function() {
     	  	 
     		});
    }

    function  delSisePep2Grafo(component){
    	swal({
            title: 'Está seguro de eliminar Sisego - Pep2 - Grafo?',
            text: 'Asegurese de validar la información seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function(){

        	var id_pp = $(component).attr('data-id_pp');
        	
     	    $.ajax({
     	    	type	:	'POST',
     	    	url     : "delSP2G",
			    data: {'id_pp' : id_pp},
     	    	'async'	:	false
     	    })
     	    .done(function(data){             	    
     	    	var data = JSON.parse(data);
				console.log(data);
		    	if(data.error == 0){
		    		$('#contTablaSisegoGrafo').html(data.tbSisegoPepGrafo);			    						
		    		initDataTable('#data-table5');
					mostrarNotificacion('success','Registro',data.msj);	
				
		    	}else if(data.error == 1){
			    	
					mostrarNotificacion('error','Error',data.msj);
				}
     		  })
     		  .fail(function(jqXHR, textStatus, errorThrown) {
     		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
     		  })
     		  .always(function() {
     	  	 
     		});
     	   
        });            

     
     }

    function  delItemPep2Grafo(component){
    	swal({
            title: 'Está seguro de eliminar Itemplan - Pep2 - Grafo?',
            text: 'Asegurese de validar la información seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function(){

        	var id_pp = $(component).attr('data-id_pp');
        	
     	    $.ajax({
     	    	type	:	'POST',
     	    	url     : "delIP2G",
			    data: {'id_pp' : id_pp},
     	    	'async'	:	false
     	    })
     	    .done(function(data){             	    
     	    	var data = JSON.parse(data);
				console.log(data);
		    	if(data.error == 0){
		    		$('#contTablaItemGrafo').html(data.tbItemPepGrafo);			    						
		    		initDataTable('#data-table6');
					mostrarNotificacion('success','Registro',data.msj);	
				
		    	}else if(data.error == 1){
			    	
					mostrarNotificacion('error','Error',data.msj);
				}
     		  })
     		  .fail(function(jqXHR, textStatus, errorThrown) {
     		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
     		  })
     		  .always(function() {
     	  	 
     		});
     	   
        });            

     
     }
    
    
    
    /************* 12062018 miguel rios **************************************/
     function changueGrafoPep2(){
            var pep2 = $.trim($('#selectPEP2').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'getGrafoPep2',
                data    :   {pep2  : pep2},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){ 
                    
                    $('#selectGRAFO').html(data.listaGrafoPep2);
                   
                   
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }

 function deletePep2GrafoError(){
          
              $('#selectPEP2').val('').trigger('change');
              $('#selectGRAFO').val('').trigger('change');
              $('#formDeletePEP2GRAFO').bootstrapValidator('resetForm', true); 
              $('#modalEliminarPEPGrafo').modal('toggle'); //abrirl modal     
        }



function addMontoPEP(){
                $('#addpep').val('');
                $('#addmonto').val('');
                $('#formAddMontoPEP').bootstrapValidator('resetForm', true); 
                $('#modalAddMontoPEP').modal('toggle'); //abrirl modal     
        }


$('#formDeletePEP2GRAFO')
            .bootstrapValidator({
                container: '#mensajeForm',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    
                    selectPEP2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe una Pep2.</p>'
                            }
                        }
                    },
                    selectGRAFO: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un grafo.</p>'
                            }
                        }
                    }    
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();
                
                
                var $form    = $(e.target),
                    formData = new FormData(),
                    params   = $form.serializeArray(),
                    bv       = $form.data('bootstrapValidator');     
               
                    $.each(params, function(i, val) {
                        formData.append(val.name, val.value);
                    });
                    $.ajax({
                        data: formData,
                        url: "eliminaPep2Grafo",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                      .done(function(data) {  
                                data = JSON.parse(data);
                            if(data.error == 0){
                                console.log(data.error);
                                $('#modalEliminarPEPGrafo').modal('toggle');                             
                                $('#contTablaGrafo').html(data.tbPepGrafo);
                                 mostrarNotificacion('success','Operación éxitosa.', 'Se elimino correctamente el grafo. Actualice la informacion!');
                            }else if(data.error == 1){
                                console.log(data.error);
                            }
                      })
            });


    $('#formAddMontoPEP')
            .bootstrapValidator({
                container: '#mensajeForm',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    
                    addpep: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el indicador.</p>'
                            },
                            callback: {
                                message: '<p style="color:red">(*) Debe respetar el formato de la PEP que esta ingresando</p>',
                                callback: function(value, validator) {
                                   
                                            var dato=value.toUpperCase();
                                           if(dato.charAt(0)==="P"){
                                                if(value.length!=20){

                                                    return false;
                                                }else{
                                                    return true;
                                                }                                                
                                           }else{
                                                if(dato.charAt(0)==="S"){
                                                    if(value.length!=12){
                                                        return false;
                                                    }else{
                                                        return true;
                                                    }
                                                }else{
                                                    return false;
                                                }
                                           }
                                  }
                            }

                        }
                    },
                    addmonto: {
                        validators: {
                            callback: {
                                message: '<p style="color:red">(*) Debe ingresar un monto superior a 0 o vacio</p>',
                                callback: function(value, validator) {
                                    if(value==null || value.trim()=='' || value<=0){
                                        return false;
                                    }else{
                                        return true;
                                    }
                                   
                                }
                            }
                        }
                    }    
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();
                
                
                var $form    = $(e.target),
                    formData = new FormData(),
                    params   = $form.serializeArray(),
                    bv       = $form.data('bootstrapValidator');     
               
                    $.each(params, function(i, val) {
                        formData.append(val.name, val.value);
                    });
                    $.ajax({
                        data: formData,
                        url: "addupdatePEPMonto",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                      .done(function(data) {  
                                data = JSON.parse(data);
                            if(data.error == 0){
                                console.log(data.error);
                                $('#modalAddMontoPEP').modal('toggle');                             
                                $('#contTablaPresu').html(data.tbPep1Presu);
                                 mostrarNotificacion('success','Operación éxitosa.', data.msj+' el monto de la PEP.');
                            }else if(data.error == 1){
                                console.log(data.error);
                            }
                      })
            });
           

        
     /************************************************************************/
    
    
    
    
    
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>