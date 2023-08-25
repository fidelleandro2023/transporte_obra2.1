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
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css"> 
        <style>
        @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
              }
            }
input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }
            input[type=number] { -moz-appearance:textfield; }
        </style>
    </head>

    <body data-ma-theme="entel">    
        <main class="main">
            <div class="page-loader">
                <svg x="0" y="0" width="258" height="258">
                    <g clip-path="url(#clip-path)">
                        <path class="tree" id="g" />
                    </g>
        
                    <clipPath id="clip-path">  
                        <path id="path" class="circle-mask"/>
                    </clipPath>   
                </svg>
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
                                    <h2>BANDEJA DE PRE - CERTIFICACIÓN</h2>
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	   				                         
                                                    <div class="row">
                                    
                                     <?php                                                    
                                                   if(!$isEECC){                      
                                                ?>        
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
                                        <label>FASE</label>
                                        <select id="selectFase" name="selectFase" class="select2 form-control" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                                <?php                                                    
                                                    foreach($listafase->result() as $row){                      
                                                ?> 
                                                    <option value="<?php echo $row->idFase ?>"><?php echo $row->faseDesc ?></option>
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
                                <?php                                                    
                                                   }                
                                                ?>      
                                 <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>ITEMPLAN</label>

                                        <select id="selectItemPlan" name="selectItemPlan" class="select2" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                                <?php                                                    
                                                    echo $itemplanList ?>
                                        </select>
                                    </div>
                                </div>
                                  
                                  <?php                                                    
                                                   if(!$isEECC){                      
                                                ?>                              
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
                                        <label>SITUACION</label>

                                        <select id="selectExpediente" name="selectExpediente" class="select2" onchange="filtrarTabla()">
                                           <option>&nbsp;</option>
                                           <option value="PENDIENTE">PENDIENTE</option>
                                           <option value="PRE-LIQUIDADO">PRE-LIQUIDADO</option>
                                           <option value="LIQUIDADO">LIQUIDADO</option>                                    
                                        </select>
                                    </div>
                                </div>
                                <?php                                                    
                                                   }                   
                                                ?>      
                                
                            </div>
		   				                            <div id="contTabla" class="table-responsive">
								                            <?php echo $tablaAsigGrafo?>
		                           </div>
		   				                        </div>
		   				                    </div>
		   				                    
		   				                    
        <div class="modal fade"id="modal-large"  tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                                        
                          <!--  -->
                        
                      <div class="card" id="contCardEstaciones">
                   
                
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
                                        <input disabled id="fecha" type="text" class="form-control date-picker" placeholder="Pick a date">
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
        
	   	<div class="modal fade"id="modal-det-item"  data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal2" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                        <button type="button" class="close" onclick="closeDetItems();">&times;</button>
                    </div>
                    <div class="modal-body">
                      <div class="card" id="contDetMatPartidas">
                   
                
                  	  </div>                        
                    </div>
                  
                </div>
            </div>
        </div>			                    
		   				                </div>

		   				                <footer class="footer hidden-xs-down">
		   				                    <p>Telefonica Del Peru</p>		   				                
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
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>  
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        <script type="text/javascript">

        
        function addExpedienteEstacion(component){       	      
        	var estacion = $(component).attr('data-idesta');
        	var itemplan = $(component).attr('data-item');
            $('#btnSaveCert').attr('data-itemplan',itemplan); 
            $('#btnSaveCert').attr('data-estacion',estacion);   
            $('#fecha').flatpickr({
            	defaultDate: "today"});
            $('#modal-cert').modal('toggle');       
        }

        function closeCertificado(){       	      
            $('#modal-cert').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');          
        }
        
        function getPTRSByItemplan(component){
        	var itemplan = $(component).attr('data-itm');
      	  $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getPtrByItm2',
     	    	data	:	{itemplan	:   itemplan},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){         	    	
         	    	$('#tituloModal').html('ITEMPLAN : '+itemplan);
         	    	$('#conTablaCerti').html(data.tabCerti);
         	    	//$('#conTablaPTR').html(data.tabPtrItm);    
        	    	 $('#data-table2').dataTable({
          	            /* Disable initial sort */
          	            "aaSorting": []
          	        });            	
                	//initDataTable('#data-table2');
               
                	//nuevo

                	$('#contCardEstaciones').html(data.estacionesTab);
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
           	var idFase = $.trim($('#selectFase').val());
           	
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getDataTableExpe2',
     	    	data	:	{subProy  :	subProy,
             	    		eecc      : eecc,
             	    	    zonal     : zonal,
              	    	    itemplanFil : itemplan,
      	    	            mes : mes,
        	    	        expediente : expediente,
     	    	            idFase : idFase
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
            	var estacion = $(component).attr('data-estacion');   

            	console.log('estacion:'+estacion)           	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'cancelCert2',
         	    	data	:	{id	:	id,
                 	    	     itemplan :  itemplan,
                  	    	     subProy : subProy,
                    	    	 eecc : eecc,
                    	    	 zonal : zonal,
                    	    	 itemplanFil : itemplanFil,
                    	    	 mes : mes,
                    	    	 expediente: expediente,
                    	    	 estacion : estacion },
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){          	    	   
         	    		mostrarNotificacion('success','Operación éxitosa.',data.msj);
         	    		$("#contBtnCerti"+estacion).show();       
         	    		$('#contTablaCerti'+estacion).html(data.tabCerti);   
         	    		    	   
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
        	var estacion = $(component).attr('data-estacion');
        	var fecha = $('#fecha').val();
        	var comentario = $('#comentario').val();

        	var subProy = $.trim($('#selectSubProy').val()); 
         	var eecc = $.trim($('#selectEECC').val()); 
         	var zonal = $.trim($('#selectZonal').val()); 
          	var itemplanFil = $.trim($('#selectItemPlan').val()); 
           	var mes = $.trim($('#selectMesEjec').val()); 
           	var expediente = $.trim($('#selectExpediente').val()); 
           	
        	if(fecha != ''){

        		$.ajax({
           	    	type	:	'POST',
           	    	'url'	:	'saveCerti2',
           	    	data	:	{itemplan	:   itemplan,
                   	    		 fecha : fecha,
                   	    		 comentario : comentario,
                  	    	     subProy : subProy,
                    	    	 eecc : eecc,
                    	    	 zonal : zonal,
                    	    	 itemplanFil : itemplanFil,
                    	    	 mes : mes,
                    	    	 expediente : expediente,
                    	    	 estacion : estacion},
           	    	'async'	:	false
           	    })
           	    .done(function(data){
           	    	var data	=	JSON.parse(data);
           	    	if(data.error == 0){
               	    	//console.log('tabla:'+data.tabCerti);contTablaCerti2
           	    		$('#contTablaCerti'+estacion).html(data.tabCerti); 
           	    		$('#contBtnCerti'+estacion).hide();  		
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
            	var estacion = $(component).attr('data-estacion');          	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'aprobCert2',
         	    	data	:	{itemplan :  itemplan,
                 	    	     subProy : subProy,
                    	    	 eecc : eecc,
                    	    	 zonal : zonal,
                    	    	 itemplanFil : itemplanFil,
                    	    	 mes : mes,
                    	    	 expediente : expediente,
                    	    	 estacion : estacion},
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){          	 
             	    	//$('#contTabla').html(data.tablaAsigGrafo);   
             	    	$('#contTablaCerti'+estacion).html(data.tabCerti); 
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
       	    	'url'	:	'chqPtr2',
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
       	    	'url'	:	'chqPtr2',
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

        function getDetItemEstacion(component){ 
             
            var itemplan = $(component).attr('data-itm');
            var idEstacion = $(component).attr('data-idEsta');
      	  $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getDetMatPar',
     	    	data	:	{itemplan	:   itemplan,
     	    		idEsta  : idEstacion},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){         	    	
         	    	$('#tituloModal2').html('ITEMPLAN : '+itemplan);         	    
                	$('#contDetMatPartidas').html(data.htmlDetParMat);  
                	
             	       // var listaManoObras = JSON.parse(data.valiMo);              	
                	
                		               	
                		console.log('despues antes'); 
                		   	
                    	//var listaMaterialesVali = JSON.parse(data.valiMate); 
                		var listaManoObras = JSON.parse(data.valiMo);              	
                    	initFormMo();                	
                    	$.each( listaManoObras, function( key, value ) {
                    		//console.log('nameMaterial:'+value['name']);
							soloDecimal(value['name']);//nuevo 21.10.2019 zavalacas
                    		$('#formEditMoPo').bootstrapValidator('addField', value['name'], {
                                validators: {
                                /*	integer : {
                                        message : '<p style="color:red">(*)Por favor ingresa un valor numerico entero permitido</p>'
                                    },*/
                            		callback: {
                	                    message: '<p style="color:red">(*)Ingrese un numero Valido dentro del rango permitido entre 0 y '+value["max"]+'</p>',
                	                    callback: function(value2, validator) { 
                    	                    console.log('value33:'+value2);  
                    	                    if(value2 != '' ){
												//console.log('value2:'+parseFloat(value2));
												//console.log('value3:'+parseFloat(value["max"]));
												//nuevo 21.10.2019 zavalacas se campo parseInt por parseFloat
                    	                    	if(parseFloat(value2) >= 0 && parseFloat(value2) <= parseFloat(value["max"])){//console.log('value:'+value["max"]);
                    	                    		return true;
                    	                    	}else{
                    	                    		return false;
                    	                    	}
                    	                    }else{
												console.log('trueeee');  
                    	                    	return true;
                    	                    }   	
                	                    	
                	                    }
                	                }    	                
                                }
                            });
                            
                  		});


                    	var listaMaterialesVali = JSON.parse(data.valiMate);              	
                    	initFormMat();              	
                    	$.each( listaMaterialesVali, function( key, value ) {
                    		//console.log('nameMaterial:'+value['name']);
                    		$('#formEditPtrMat').bootstrapValidator('addField', value['name'], {
                                validators: {
                                	integer : {
                                        message : '<p style="color:red">(*)Por favor ingresa un valor numerico entero permitido</p>'
                                    },
                            		callback: {
                	                    message: '<p style="color:red">(*)Ingrese un numero Valido dentro del rango permitido entre 0 y '+value["max"]+'</p>',
                	                    callback: function(value1, validator) { 
                    	                    //console.log('value:'+value2);  
                    	                    if(value1 != '' ){
                    	                    	if(value1 >= 0 && value1 <= parseInt(value["max"])){//console.log('value:'+value["max"]);
                    	                    		return true;
                    	                    	}else{
                    	                    		return false;
                    	                    	}
                    	                    }else{
                    	                    	return true;
                    	                    }   	
                	                    	
                	                    }
                	                }    	                
                                }
                            });
                            
                  		});
             	    $('#modal-det-item').modal('toggle'); 
             	    
             	    /************************/
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

        function closeDetItems(){       	      
            $('#modal-det-item').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');          
        }

        
        

        function initFormMo(){
        	$('#formEditMoPo')
                .bootstrapValidator({
                container: '#mensajeFormMo',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {                 	
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
                    
                    var itemplan = $('#btnRegMat').attr('data-itemplan');
                    var idEstacion = $('#btnRegMat').attr('data-idEstacion');
                    formData.append('itemplan', itemplan);
                    formData.append('idEstacion', idEstacion);
                    
                    swal({
                        title: 'Está seguro de actualizar la informacion de las Partidas?',
                        text: 'Asegurese de que la informacion llenada sea la correta.',
                        type: 'warning',
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'Si, guardar los datos!',
                        cancelButtonClass: 'btn btn-secondary',
                        allowOutsideClick: false
                    }).then(function(){
                        $.ajax({
                            data: formData,
                            url: "upMOParti",
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'POST'
                          })
                          .done(function(data) {
                             var data	=	JSON.parse(data);
                             if(data.error == 0){
                                 /*
                            	 var lista_ok = JSON.parse(data.soli_success);
                            	 $.each( lista_ok, function( key, value ) {
                                	 console.log(value['codigo_po']+'-'+value['numero_soli']);
                            	 });
                            	 */
                            	 swal({
                                     title: 'Se realizo la operacion con exito!',
                                     text: 'Se actualizaron las partidas correspondientes!',
                                     type: 'success',
                                     buttonsStyling: false,
                                     confirmButtonClass: 'btn btn-primary',
                                     confirmButtonText: 'OK!'

                                 }).then(function () {
                                	 $('#modal-det-item').modal('toggle');
                                	 $('#modal-large').modal('toggle');
                                });                           	  
                               
                             }else if(data.error == 1){    				
                                 mostrarNotificacion('error',data.msj);
                             }
                          });
                    }, function(dismiss) {
                        // dismiss can be "cancel" | "close" | "outside"
                            $('#formEditMoPo').bootstrapValidator('resetForm', true); 
                    });
                });
            }

        function initFormMat(){
        	$('#formEditPtrMat')
                .bootstrapValidator({
                container: '#mensajeFormMat',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {                 	
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

                    var itemplan = $('#btnRegMo').attr('data-itemplan');
                    var idEstacion = $('#btnRegMo').attr('data-idEstacion');
                    formData.append('itemplan', itemplan);
                    formData.append('idEstacion', idEstacion);
                    
                    swal({
                        title: 'Está seguro de actualizar la informacion de Materiales?',
                        text: 'Asegurese de que la informacion llenada sea la correta.',
                        type: 'warning',
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'Si, guardar los datos!',
                        cancelButtonClass: 'btn btn-secondary',
                        allowOutsideClick: false
                    }).then(function(){
                        $.ajax({
                            data: formData,
                            url: "upMateri",
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'POST'
                          })
                          .done(function(data) {
                             var data	=	JSON.parse(data);
                             if(data.error == 0){
                                 var codigo_html = '<p><strong>Solicitudes Generadas</strong></p><table style="width: 100%;"><tbody><tr><td style="text-align: center;width: 50%;">codigo po</td><td style="text-align: center;width: 50%;">numero de solicitud</td></tr>';  
                                 
                            	 var lista_ok = JSON.parse(data.soli_success);
                            	 $.each( lista_ok, function( key, value ) {
                                	 codigo_html = codigo_html+'<tr><td>'+value['codigo_po']+'</td><td>'+value['numero_soli']+'</td></tr> ';
                                	 //console.log(value['codigo_po']+'-'+value['numero_soli']);
                            	 });
                            	 codigo_html = codigo_html+'</tbody></table>';

                            	 
                        	   var lista_error = JSON.parse(data.soli_error);
                        	   if(lista_error.lenght >= 1){
                        		   codigo_html = codigo_html+'<p><strong>Solicitudes Denegadas</strong></p><table style="width: 100%;"><tbody><tr><td style="text-align: center;width: 50%;">codigo po</td><td style="text-align: center;width: 50%;">motivo</td></tr>';
                            	 $.each( lista_error, function( key, value ) {
                                	 codigo_html = codigo_html+'<tr><td>'+value+'</td><td>SIN VR</td></tr> ';
                                	 //console.log(value['codigo_po']+'-'+value['numero_soli']);
                            	 });
                            	 codigo_html = codigo_html+'</tbody></table>';
                        	   }
                            	 swal({
                                     title: 'Se realizo la operacion con exito!',
                                     html: codigo_html,
                                     type: 'success',
                                     buttonsStyling: false,
                                     confirmButtonClass: 'btn btn-primary',
                                     confirmButtonText: 'OK!',
                                     allowOutsideClick: false

                                 }).then(function () {
                                	 $('#modal-det-item').modal('toggle');
                                	 $('#modal-large').modal('toggle');
                                });  
                             }else if(data.error == 1){    				
                                 mostrarNotificacion('error',data.msj);
                             }
                          });
                    }, function(dismiss) {
                        // dismiss can be "cancel" | "close" | "outside"
                            $('#formEditPtrMat').bootstrapValidator('resetForm', true); 
                    });
                });
            }
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>