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
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        
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
                                    <h2 style="color: #333333d4;font-weight: 800;text-align: center;">BANDEJA DE PRE - CERTIFICACIÓN</h2>
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	   				                         
                                                    <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>

                                        <select id="selectSubProy" name="selectSubProy" class="select2" onchange="filtrarTabla()" multiple>
                                        <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaSubProy->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->subProyectoDesc ?>"><?php echo $row->subProyectoDesc ?></option>
                                                 <?php }?>
                                           
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>EECC</label>

                                        <select id="selectEECC" name="selectEECC" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaEECC->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->empresaColabDesc ?>"><?php echo $row->empresaColabDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>ZONAL</label>

                                        <select id="selectZonal" name="selectZonal" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaZonal->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->zonalDesc ?>"><?php echo $row->zonalDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>
                                
                                 <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>ITEMPLAN</label>

                                        <select id="selectItemPlan" name="selectItemPlan" class="select2" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                              <?php                                                    
                                                    foreach($itemplanList->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->itemPlan ?>"><?php echo $row->itemPlan ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>
                                                           
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>MES PREVISTO EJECUCION</label>

                                        <select id="selectMesEjec" name="selectMesEjec" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                       <option value="ENE">ENERO</option>
                                       <option value="FEB">FEBRERO</option>
                                       <option value="MAR">MARZO</option>
                                       <option value="ABR">ABRIL</option>
                                       <option value="MAY">MAYO</option>
                                       <option value="JUN">JUNIO</option>
                                       <option value="JUL">JULIO</option>
                                       <option value="AGO">AGOSTO</option>
                                       <option value="SEP">SEPTIEMBRE</option>
                                       <option value="OCT">OCTUBRE</option>
                                       <option value="NOV">NOVIEMBRE</option>
                                       <option value="DIC">DICIEMBRE</option>
                                       
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>EXPEDIENTE</label>

                                        <select id="selectExpediente" name="selectExpediente" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                       <option value="SI">SI</option>
                                       <option value="NO">NO</option>
                                    
                                        </select>
                                    </div>
                                </div>
                            </div>
		   				                            <div id="contTabla" class="table-responsive">
								                            <?php echo $tablaAsigGrafo?>
		                           </div>
		   				                        </div>
		   				                    </div>
		   				                    
		   				                    
        <div class="modal fade"id="modal-large"  tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                                        
                        
                        <div class="tab-container">
                            <div id="conTablaPTR" class="table-responsive">     
                            
                            </div>
                        </div>
                        <br>
                        <div id="contBtnCerti" style="text-align: right;display: none;" class="tab-container">
                        <button style="color: white;background-color: #204382;" class="btn btn-secondary waves-effect" data-toggle="modal" data-target="#modal-cert">Expediente</button>
                        </div>   
                        <div class="tab-container">
                            <div id="conTablaCerti" class="table-responsive">     
                            
                            </div>
                        </div>
                        
                    </div>
                  
                </div>
            </div>
        </div>
		   			
		   			<div class="modal fade"id="modal-cert"  tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 style="margin: auto;    font-weight: bold;" class="modal-title">EXPEDIENTE</h4>
                         <button type="button" class="close" onclick="closeCertificado();">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="formAddPep1Pep2" method="post" class="form-horizontal">
                            <div class="form-group">
                               <label>Fecha</label>

                                <div class="input-group">
                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                    <div class="form-group">
                                        <input id="fecha" type="text" class="form-control date-picker" placeholder="Pick a date">
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Comentario</label>
                                <input id="comentario" name="inputCorreS" type="text" class="form-control">
                                <i class="form-group__bar"></i>
                            </div>                        
                         </form>
                        
                    </div>
                      <div class="modal-footer">
                                            <button id="btnSaveCert" style="BACKGROUND-COLOR: #183469; COLOR: WHITE;" onclick="saveCertificado(this);" type="button" class="btn btn-link waves-effect">Guardar</button>
                                            <button type="button" class="btn btn-link waves-effect" onclick="closeCertificado();">Close</button>
                                        </div>
                </div>
            </div>
        </div>
		   				                    
		   				                </div>

		   				                <footer class="footer hidden-xs-down">
		   				                    <p>© Material Admin Responsive. All rights reserved.</p>

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
        
        
        

        <!-- Older IE warning message -->
            <!--[if IE]>
                <div class="ie-warning">
                    <h1>Warning!!</h1>
                    <p>You are using an outdated version of Internet Explorer, please upgrade to any of the following web browsers to access this website.</p>

                    <div class="ie-warning__downloads">
                        <a href="http://www.google.com/chrome">
                            <img src="img/browsers/chrome.png" alt="">
                        </a>

                        <a href="https://www.mozilla.org/en-US/firefox/new">
                            <img src="img/browsers/firefox.png" alt="">
                        </a>

                        <a href="http://www.opera.com">
                            <img src="img/browsers/opera.png" alt="">
                        </a>

                        <a href="https://support.apple.com/downloads/safari">
                            <img src="img/browsers/safari.png" alt="">
                        </a>

                        <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">
                            <img src="img/browsers/edge.png" alt="">
                        </a>

                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="img/browsers/ie.png" alt="">
                        </a>
                    </div>
                    <p>Sorry for the inconvenience!</p>
                </div>
            <![endif]-->

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

        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>        
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script type="text/javascript">

        function closeCertificado(){       	      
            $('#modal-cert').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');          
        }
        
        function getPTRSByItemplan(component){
        	var itemplan = $(component).attr('data-itm');
      	  $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getPtrByItm',
     	    	data	:	{itemplan	:   itemplan},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){         	    	
         	    	$('#tituloModal').html('ITEMPLAN : '+itemplan);
         	    	$('#conTablaCerti').html(data.tabCerti);
         	    	$('#conTablaPTR').html(data.tabPtrItm);    
        	    	 $('#data-table2').dataTable({
          	            /* Disable initial sort */
          	            "aaSorting": []
          	        });            	
                	//initDataTable('#data-table2');
                	if(data.hasActivo == 1){
                		$("#contBtnCerti").hide();
                	}else if(data.hasActivo == 0){
                		$("#contBtnCerti").show();
                	}
                	$('#btnSaveCert').attr('data-itemplan',itemplan);
                	$('#modal-large').modal('toggle');
     	    	}else if(data.error == 1){     				
     				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
     			}
     		  })
     		  .fail(function(jqXHR, textStatus, errorThrown) {
     		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
     		  })
     		  .always(function() {
     	  	 
     		});
        }
        

        function filtrarTabla(){
   	        var subProy = $.trim($('#selectSubProy').val()); 
         	 var eecc = $.trim($('#selectEECC').val()); 
         	 var zonal = $.trim($('#selectZonal').val()); 
          	var itemplan = $.trim($('#selectItemPlan').val()); 
           	var mes = $.trim($('#selectMesEjec').val());           
           	var expediente = $.trim($('#selectExpediente').val());    	
           	
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getDataTableExpe',
     	    	data	:	{subProy  :	subProy,
             	    		eecc      : eecc,
             	    	    zonal     : zonal,
              	    	    itemplanFil : itemplan,
      	    	            mes : mes,
        	    	        expediente : expediente},
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
        
        function cancelCertificado(component){

        	swal({
                title: 'Desea devolver el expediente?',
                text: 'Asegurese de validar la información seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, devolver el expediente!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){

               	var subProy = $.trim($('#selectSubProy').val()); 
             	var eecc = $.trim($('#selectEECC').val()); 
             	var zonal = $.trim($('#selectZonal').val()); 
              	var itemplanFil = $.trim($('#selectItemPlan').val()); 
               	var mes = $.trim($('#selectMesEjec').val());   
               	var expediente = $.trim($('#selectExpediente').val()); 
               	
            	var id = $(component).attr('data-id');    
            	var itemplan = $(component).attr('data-itemplan');            	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'cancelCert',
         	    	data	:	{id	:	id,
                 	    	     itemplan :  itemplan,
                  	    	     subProy : subProy,
                    	    	 eecc : eecc,
                    	    	 zonal : zonal,
                    	    	 itemplanFil : itemplanFil,
                    	    	 mes : mes,
                    	    	 expediente: expediente },
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){          	    	   
         	    		mostrarNotificacion('success','Operación éxitosa.',data.msj);
         	    		$("#contBtnCerti").show();       
         	    		$('#conTablaCerti').html(data.tabCerti);   
         	    		$('#contTabla').html(data.tablaAsigGrafo);   
             	    	initDataTable('#data-table');	 
         	    			    	   
         			}else if(data.error == 1){         				
         				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
         			}
         		  })
         		  .fail(function(jqXHR, textStatus, errorThrown) {
         		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
         		  })
         		  .always(function() {
         	  	 
         		});
         	   
            });            
          	 
        }

        function saveCertificado(component){
        	var itemplan = $(component).attr('data-itemplan');
        	var fecha = $('#fecha').val();
        	var comentario = $('#comentario').val();

        	var subProy = $.trim($('#selectSubProy').val()); 
         	var eecc = $.trim($('#selectEECC').val()); 
         	var zonal = $.trim($('#selectZonal').val()); 
          	var itemplanFil = $.trim($('#selectItemPlan').val()); 
           	var mes = $.trim($('#selectMesEjec').val()); 
           	var expediente = $.trim($('#selectExpediente').val()); 
           	
        	if(fecha != '' && comentario != ''){

        		$.ajax({
           	    	type	:	'POST',
           	    	'url'	:	'saveCerti',
           	    	data	:	{itemplan	:   itemplan,
                   	    		fecha : fecha,
                   	    		comentario : comentario,
                  	    	     subProy : subProy,
                    	    	 eecc : eecc,
                    	    	 zonal : zonal,
                    	    	 itemplanFil : itemplanFil,
                    	    	 mes : mes,
                    	    	 expediente : expediente},
           	    	'async'	:	false
           	    })
           	    .done(function(data){
           	    	var data	=	JSON.parse(data);
           	    	if(data.error == 0){
           	    		$('#conTablaCerti').html(data.tabCerti);
           	    		$('#contTabla').html(data.tablaAsigGrafo);   
             	    	initDataTable('#data-table');	
           	    		$("#contBtnCerti").hide();           	    		
           	    		$('#modal-cert').modal('toggle');
           	    		$('#modal-large').css('overflow-y', 'scroll');
           	    		mostrarNotificacion('success','Operación éxitosa.',data.msj);
           	    	}else if(data.error == 1){
         				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
         			}
         		  })
         		  .fail(function(jqXHR, textStatus, errorThrown) {
         		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
         		  })
         		  .always(function() {
         	  	 
         		});
               	 
             }else{
         	    alert('Ingrese Fecha y Comentario Válido.');
                 }
        }
        
        function aprobarCertificado(component){


        	swal({
                title: 'Está seguro de aprobar el Itemplan con el certificado Actual?',
                text: 'Asegurese de validar la información seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, Aprobar el certificado!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){
            	var subProy = $.trim($('#selectSubProy').val()); 
             	var eecc = $.trim($('#selectEECC').val()); 
             	var zonal = $.trim($('#selectZonal').val()); 
              	var itemplanFil = $.trim($('#selectItemPlan').val()); 
               	var mes = $.trim($('#selectMesEjec').val()); 
               	var expediente = $.trim($('#selectExpediente').val()); 
               	
            	var itemplan = $(component).attr('data-itemplan');            	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'aprobCert',
         	    	data	:	{itemplan :  itemplan,
                 	    	     subProy : subProy,
                    	    	 eecc : eecc,
                    	    	 zonal : zonal,
                    	    	 itemplanFil : itemplanFil,
                    	    	 mes : mes,
                    	    	 expediente : expediente},
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){          	 
             	    	$('#contTabla').html(data.tablaAsigGrafo);   
             	    	//initDataTable('#data-table5');	   
         	    		mostrarNotificacion('success','Operación éxitosa.',data.msj);
         	        	    	   
         			}else if(data.error == 1){         				
         				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
         			}
         		  })
         		  .fail(function(jqXHR, textStatus, errorThrown) {
         		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
         		  })
         		  .always(function() {
         	  	 
         		});
         	   
            });            
          	 
        }
        
        function chequed(component){
       	 if( $(component).is(':checked') ){

      		 var dato = $(component).val();
       		 $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'chqPtr',
       	    	data	:	{dato   : dato,
                  	    	 accion : '1'},
       	    	'async'	:	false
       	    })
       	    .done(function(data){
       	    	var data	=	JSON.parse(data);
       	    	if(data.error == 0){          	            	    	
       	    		mostrarNotificacion('success','Operación éxitosa.',data.msj);       	        	    	   
       			}else if(data.error == 1){         				
       				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
       			}
       		  })
         } else {

        	 var dato = $(component).val();
       		 $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'chqPtr',
       	    	data	:	{dato   : dato,
                  	    	 accion : '2'},
       	    	'async'	:	false
       	    })
       	    .done(function(data){
       	    	var data	=	JSON.parse(data);
       	    	if(data.error == 0){          	            	    	
       	    		mostrarNotificacion('success','Operación éxitosa.',data.msj);       	        	    	   
       			}else if(data.error == 1){         				
       				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
       			}
       		  })
             
         }
        }
       
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>