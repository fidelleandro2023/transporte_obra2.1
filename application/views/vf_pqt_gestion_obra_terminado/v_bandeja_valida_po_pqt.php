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
            
        @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
              }
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃƒÂº"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                    <h2>BANDEJA VALIDACION NUEVO MODELO</h2>
                    <div class="card">		   				                    
                    
                        <div class="card-block"> 
                            <div class="row">
                                 <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>EECC</label>
                                        <select id="selectEecc" name="selectEecc" class="select2">
                                        <option value="">Seleccionar Ecc</option>
                                        <?php                                                    
                                                    foreach($listaEECC->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>ITEMPLAN</label>
                                        <input id="txtItemplan" type="text" class="form-control input-mask" placeholder="Itemplan" autocomplete="off" maxlength="16" style="border-bottom: 1px solid lightgrey">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <button style="margin-top: 30px;" class="btn btn-success waves-effect" type="button" onclick="filtrarTabla();">CONSULTAR</button>
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
          <!-- 
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
        -->
        <div class="modal fade" id="modalValidarEstacion">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 style="margin: auto" id="tittleCertificarHG" class="modal-title"></h3>
                </div>
                
                <div class="modal-body">
               
                    <div id="contTablaPdt">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>      
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalVerMateriales"  tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 style="margin: auto" class="modal-title">Materiales</h3>
                    </div>
                    
                    <div class="modal-body">                       
                        <div id="contTablaMateriales">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    </body>
        
        <script type="text/javascript">

        function filtrarTabla() {
            var itemplan = $('#txtItemplan').val();
            var eecc   = $('#selectEecc option:selected').val(); 
            
            $.ajax({
                type : 'POST',
                url  : 'filTabValPqtAnc',
                data : { itemplan   : itemplan,
             	         eecc   :   eecc} 
            }).done(function(data){
                data = JSON.parse(data);
                if(data.error == 0) {
                    $('#contTabla').html(data.tablaBandeja);
                    initDataTable('#data-table');
                } else {
                    mostrarNotificacion('error','error', data.msj);
                }
            });
        }

        function viewDetallePartidas(component) {            
        	var item       = $(component).attr('data-item');
        	var idEstacion = $(component).attr('data-esta');
			var idSol      = $(component).attr('data-idSol');
            $.ajax({
                type : 'POST',
                url  : 'getContPartPndtVal',
                data : { itemplan   : item,
             	         idEstacion : idEstacion,
              	         idSol    :   idSol}
            }).done(function(data){
                data = JSON.parse(data);
                if(data.error == 0) { 
                    $('#contTablaPdt').html(data.tablaPdt);
                    eventValNivel1();
                    eventValNivel2();
                    initRejectSol();
                    valdetMat();
                    modal('modalValidarEstacion');
                } else {
                    mostrarNotificacion('error', data.msj, 'error');
                }
            })
        }

        function eventValNivel1(){
            $('.valNi1').click(function(e){
                var id_estacion = $(this).attr('data-idEs');
                var item_sa     =  $(this).attr('data-item');
                var idSoli      = $(this).attr('data-idSol');
         	   console.log(' idSoli- '+idSoli);
                
             	swal({
                     title: 'Está seguro de validar las partidas?',
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
                  	    	type	:	'POST',
                  	    	'url'	:	'validarNivel1',
                  	    	data	:	{ itemplan          :   item_sa,
                   	    	              idEstacion        :   id_estacion,
                      	    	           idSoli           :   idSoli},
                  	    	'async'	:	false
                  	    })
                  	    .done(function(data){
                  	    	var data	=	JSON.parse(data);
                  	    	if(data.error == 0){
                  	    		swal({
                                     title: 'Se valido correctamente las Partidas.',
                                     text: 'Asegurese de validar la informacion!',
                                     type: 'success',
                                     buttonsStyling: false,
                                     confirmButtonClass: 'btn btn-primary',
                                     confirmButtonText: 'OK!'
    
                                 }).then(function () {
                                     //window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
                                	 location.reload();
                                 });
                  	    		
                  			}else if(data.error == 1){
                  				mostrarNotificacion('error','Ocurrio un error!', data.msj);
                  			}
                  		  });
    
                 })
            });
        }

        function eventValNivel2(){
                $('.gpoMo').click(function(e){
                	var id_estacion = $(this).attr('data-idEs');
                    var item_sa     =  $(this).attr('data-item');
                    
                 	swal({
                         title: 'Está seguro aprobar la propuesta?',
                         text: 'Asegurese de que la informacion sea la correta.',
                         type: 'warning',
                         showCancelButton: true,
                         buttonsStyling: false,
                         confirmButtonClass: 'btn btn-primary',
                         confirmButtonText: 'Si, guardar los datos!',
                         cancelButtonClass: 'btn btn-secondary',
                         allowOutsideClick: false
                     }).then(function(){
                         console.log('ok');
                         
                      	    $.ajax({
                      	    	type	:	'POST',
                      	    	'url'	:	'valProNiv2',
                      	    	data	:	{ itemplan    :   item_sa,
                       	    	              idEstacion  :   id_estacion},
                      	    	'async'	:	false
                      	    })
                      	    .done(function(data){
                    	    	   console.log('ok.....');
                          	  
                      	    	var data	=	JSON.parse(data);
                      	    	if(data.error == 0){
                      	    		swal({
                                         title: 'Se aprobo La propuesta',
                                         text: 'Asegurese de validar la informacion!',
                                         type: 'success',
                                         buttonsStyling: false,
                                         confirmButtonClass: 'btn btn-primary',
                                         confirmButtonText: 'OK!'
        
                                     }).then(function () {
                                        // window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
                                    	 location.reload();
                                     });
                      	    		
                      			}else if(data.error == 1){
                      				mostrarNotificacion('error','Ocurrio un error!', data.msj);
                      			}
                      		  });
                     }) 
             });
			 
			 $('.valNi2Ruta').click(function(e){
                	var id_estacion = $(this).attr('data-idEs');
                    var item_sa     =  $(this).attr('data-item');
                    
                 	swal({
                         title: 'EstÃ¡ seguro aprobar la propuesta?',
                         text: 'Asegurese de que la informacion sea la correta.',
                         type: 'warning',
                         showCancelButton: true,
                         buttonsStyling: false,
                         confirmButtonClass: 'btn btn-primary',
                         confirmButtonText: 'Si, guardar los datos!',
                         cancelButtonClass: 'btn btn-secondary',
                         allowOutsideClick: false
                     }).then(function(){
                         console.log('ok');
                         
                      	    $.ajax({
                      	    	type	:	'POST',
                      	    	'url'	:	'valProNiv2Ruta',
                      	    	data	:	{ itemplan    :   item_sa,
                       	    	              idEstacion  :   id_estacion},
                      	    	'async'	:	false
                      	    })
                      	    .done(function(data){
                    	    	   console.log('ok.....');
                          	  
                      	    	var data	=	JSON.parse(data);
                      	    	if(data.error == 0){
                      	    		swal({
                                         title: 'Se aprobo La propuesta',
                                         text: 'Asegurese de validar la informacion!',
                                         type: 'success',
                                         buttonsStyling: false,
                                         confirmButtonClass: 'btn btn-primary',
                                         confirmButtonText: 'OK!'
        
                                     }).then(function () {
                                        // window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
                                    	 location.reload();
                                     });
                      	    		
                      			}else if(data.error == 1){
                      				mostrarNotificacion('error','Ocurrio un error!', data.msj);
                      			}
                      		  });
                     }) 
             });
			 
			 $('.valNi2NoPqt').click(function(e){
				 console.log('fuck..');
	          	var id_estacion = $(this).attr('data-idEs');
	              var item_sa     =  $(this).attr('data-item');
	              
	           	swal({
	                   title: 'Esta seguro aprobar la propuesta?',
	                   text: 'Asegurese de que la informacion sea la correta.',
	                   type: 'warning',
	                   showCancelButton: true,
	                   buttonsStyling: false,
	                   confirmButtonClass: 'btn btn-primary',
	                   confirmButtonText: 'Si, guardar los datos!',
	                   cancelButtonClass: 'btn btn-secondary',
	                   allowOutsideClick: false
	               }).then(function(){
	                   console.log('ok');
	                   
	                	    $.ajax({
	                	    	type	:	'POST',
	                	    	'url'	:	'valProNiv2NoPqt',
	                	    	data	:	{ itemplan    :   item_sa,
	                 	    	              idEstacion  :   id_estacion},
	                	    	'async'	:	false
	                	    })
	                	    .done(function(data){
	              	    	   console.log('ok.....');
	                    	  
	                	    	var data	=	JSON.parse(data);
	                	    	if(data.error == 0){
	                	    		swal({
	                                   title: 'Se aprobo La propuesta',
	                                   text: 'Asegurese de validar la informacion!',
	                                   type: 'success',
	                                   buttonsStyling: false,
	                                   confirmButtonClass: 'btn btn-primary',
	                                   confirmButtonText: 'OK!'
	  
	                               }).then(function () {
	                                  // window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
	                              	 location.reload();
	                               });
	                	    		
	                			}else if(data.error == 1){
	                				mostrarNotificacion('error','Ocurrio un error!', data.msj);
	                			}
	                		  });
	               }) 
	       });
        }

        function valdetMat(){
            $('.getMateEsta').click(function(e){
            	var item       = $(this).attr('data-item');
            	var idEstacion = $(this).attr('data-esta');
    
                $.ajax({
                    type : 'POST',
                    url  : 'getMatpqt',
                    data : { itemplan   : item,
                 	         idEstacion : idEstacion}
                }).done(function(data){
                    data = JSON.parse(data);
                    if(data.error == 0) { 
                        $('#contTablaMateriales').html(data.tablaMateriales);
                        modal('modalVerMateriales');
                    } else {
                        mostrarNotificacion('error', data.msj, 'error');
                    }
                })
            });
        }
        
        function initRejectSol(){
            
            $('.rejectSol').click(function(e){
            	var item       = $(this).attr('data-item');
            	var idEstacion = $(this).attr('data-ides');
            	var idSoli     = $(this).attr('data-idSol');
            	var from       = $(this).attr('data-from');//1=validacion 1, 2 = validacion 2
            	swal({
                    title: 'Está seguro rechazar la Solicitud?',
                    //text: 'Asegurese de que la informacion sea la correta.',
                    html : '<div class="form-group"><a>Ingrese comentario de Rechazo</a></div><div><textarea maxlength="512" class="col-md-12 form-control" placeholder="Ingresar Comentario..." style="height:80px;background:#F9F8CF" id="comentarioText"></textarea></div>',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, guardar los datos!',
                    cancelButtonClass: 'btn btn-secondary',
                    allowOutsideClick: false
                }).then(function(){
                	var comentario = $('#comentarioText').val();
                	if(comentario==null || comentario==''){
                		swal({
                            title: 'Alerta!',
                            text: 'Debe Ingresar un comentario!',
                            type: 'warning',
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: 'OK!'
 
                        })        
               		 return false;     		 
                	}

                	console.log('continua....');
                        $.ajax({
                            type : 'POST',
                            url  : 'rejectSolAdPqt',
                            data : { itemplan   : item,
                         	         idEstacion : idEstacion,
                          	         idSoli     : idSoli,
                            	     from       : from,
                            	     comentario : comentario}
                        }).done(function(data){
                       	 console.log('ok.....');                    	  
                	    	var data	=	JSON.parse(data);
                	    	if(data.error == 0){
                	    		swal({
                                   title: 'Se rechazo la Solicitud!',
                                   text: 'Asegurese de validar la informacion!',
                                   type: 'success',
                                   buttonsStyling: false,
                                   confirmButtonClass: 'btn btn-primary',
                                   confirmButtonText: 'OK!'
        
                               }).then(function () {
                                  // window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
                              	 location.reload();
                               });
                	    		
                			}else if(data.error == 1){
                				mostrarNotificacion('error','Ocurrio un error!', data.msj);
                			}                			
                        })
                });
            });
        }

        /**nuevo modificacion cantidades **/
        function getPTRSByItemplan(component){
        	var itemplan = $(component).attr('data-item');
      	  $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getPtrByItmPqt',
     	    	data	:	{itemplan	:   itemplan},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){ 
        	    	 $('#contTablaPdt').html(data.estacionesTab);
                     modal('modalValidarEstacion');
     	    	}else if(data.error == 1){     				
     				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
     			}
     		  })
     		  .fail(function(jqXHR, textStatus, errorThrown) {
     		     mostrarNotificacion('error','error interno');
     		  })
     		  .always(function() {
     	  	 
     		});
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
                            url: "upMateriPqt",
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
                                 mostrarNotificacion('warning',data.msj);
                             }
                          });
                    }, function(dismiss) {
                        // dismiss can be "cancel" | "close" | "outside"
                            $('#formEditPtrMat').bootstrapValidator('resetForm', true); 
                    });
                });
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
                                 mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                             }
                          });
                    }, function(dismiss) {
                        // dismiss can be "cancel" | "close" | "outside"
                            $('#formEditMoPo').bootstrapValidator('resetForm', true); 
                    });
                });
            }

        function closeDetItems(){       	      
            $('#modal-det-item').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');          
        }


        function liquidacion(itemPlan) {
            $.ajax({
                type: 'POST',
                dataType: "JSON",
                'url': 'liquidacion',
                data: {itemPlan: itemPlan}
            }).done(function (data) {
                console.log(data);
                if (data.path == '1') {
                    location.href = 'liquidacion_download?' + 'itemPlan=' + itemPlan;
                } else {
                    alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
                }

            });
        }

        function disenho(itemPlan) {
            $.ajax({
                type: 'POST',
                dataType: "JSON",
                'url': 'disenho',
                data: {itemPlan: itemPlan}
            }).done(function (data) {
                console.log(data);
                if (data.path == '1') {
                    location.href = 'disenho_download?' + 'itemPlan=' + itemPlan;
                } else {
                    alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
                }

            });
        }

        function licencias(itemPlan) {
            $.ajax({
                type: 'POST',
                dataType: "JSON",
                'url': 'licencias',
                data: {itemPlan: itemPlan}
            }).done(function (data) {
                console.log(data);
                if (data.path == '1') {
                    location.href = 'licencias_download?' + 'itemPlan=' + itemPlan;
                } else {
                    alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
                }

            });
        }

        function cotizacion(itemPlan) {
            $.ajax({
                type: 'POST',
                dataType: "JSON",
                'url': 'cotizacion',
                data: {itemPlan: itemPlan}
            }).done(function (data) {
                console.log(data);
                if (data.path == '1') {
                    location.href = 'cotizacion_download?' + 'itemPlan=' + itemPlan;
                } else {
                    alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
                }

            });
        }

        function expedienteLiqui(itemplan, idEstacion) {
			console.log('here');
            $.ajax({
                type: 'POST',
                dataType: "JSON",
                'url': 'getExpeLiqui',
                data: {itemplan: itemplan,
                	idEstacion :   idEstacion}
            }).done(function (data) {
                console.log('herer2');
                if (data.path == '1') {
                    location.href = data.ruta;
                } else {
                    alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
                }

            });
        }

        function alertValidacion(tipo, titulo, mensaje) {
            swal({
                title: titulo,
                text: mensaje,
                type: tipo
            });
        }
        </script>
<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>