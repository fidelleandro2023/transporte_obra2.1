<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/dropzone/downloads/css/dropzone.css" />
        
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        
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

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">View Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </div>

                    <ul class="navigation">

                                 <?php echo $opciones?>
                    </ul>
                </div>
            </aside>

               <section class="content content--full">
           
                           <div class="content__inner">
                                    <h2>VALIDAR COTIZACION</h2>
                                            <div class="card">
                                                
                                                <div class="card-block">                                             
                                                    <div class="row">

                            </div>
                                                    <div id="contTabla" class="table-responsive">
                                                            <?php echo $tablaAsigGrafo?>
                                   </div>
                                                </div>
                                            </div>
                                            
                                         
              
        </div>
                            
                                                                                
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

        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
         <script src="<?php echo base_url();?>public/dropzone/downloads/dropzone.min.js"></script>
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
       
        <script type="text/javascript">
       
/*        function filtrarTabla(){
            var subProy = $.trim($('#selectSubProy').val()); 
             var eecc = $.trim($('#selectEECC').val());             
            var itemplan = $.trim($('#selectItemPlan').val());  
            
            $.ajax({
                type    :   'POST',
                'url'   :   'getItemPlanEdit2',
                data    :   {subProy  : subProy,
                            eecc      : eecc,                         
                            itemplanFil : itemplan},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){                                           
                    $('#contTabla').html(data.tablaAsigGrafo)
                    initDataTable('#data-table');
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
              });
        }        
        */

        function aceptarCotizacion(component){
           
        	swal({
                title: 'Está seguro de Aprobar la Cotizacion?',
                text: 'Recuerde que una vez aceptada la cotizacion no podra revertir!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, aceptar!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){
            	
            	var itemplan = $(component).attr('data-itemplan');             	
             	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'validCoti',
         	    	data	:   { itemplan : itemplan,
         	    	              accion : 1},// 1 == aprobar
         	    	'async'	:	false
         	    })
         	    .done(function(data){
         	    	var data	=	JSON.parse(data);                 	    	
         	    	if(data.error == 0){
         	    	    if(data.tipo_mensaje == 1){
         	            	mostrarNotificacionHTML('warning', 'La obra no cuenta con prespuesto!.', data.html);         	    	 	    
         	    	    }else{             	    	    
    					   $('#contTabla').html(data.tablaAsigGrafo);
               	    	   initDataTable('#data-table');              	    	 
                   	       mostrarNotificacion('success','Operación éxitosa.',data.msj); 
         	    	    }
         			}else if(data.error == 1){
         				
         				mostrarNotificacion('warning','No se pudo aprobar la cotizacion!',data.msj);
         			}
         		  })
         		  .fail(function(jqXHR, textStatus, errorThrown) {
          			 mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);                  			 
         		  })
         		  .always(function() {
         	  	 
         		 });
            });         
                         
        }

        function rechazarCotizacion(component){
            
        	swal({
                title: 'Está seguro de Cancelar la Cotizacion?',
                text: 'Recuerde que luego tendra que esperar una nueva cotizacion!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, rechazar!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){

            	
            	var itemplan = $(component).attr('data-itemplan');             	
             	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'validCoti',
         	    	data	:   { itemplan : itemplan,
         	    	                accion : 2},// 2 == rechazar         	    		
         	    	'async'	:	false
         	    })
         	    .done(function(data){
         	    	var data	=	JSON.parse(data);                 	    	
         	    	if(data.error == 0){
         	    	
					   $('#contTabla').html(data.tablaAsigGrafo);
           	    	   initDataTable('#data-table');
              	    	 
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

        function devolverCotizacion(component){
            
        	swal({
                title: 'Está seguro de Devolver la Cotizacion?',
                text: 'Recuerde que luego tendra que esperar una nueva cotizacion!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, rechazar!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){

            	
            	var itemplan = $(component).attr('data-itemplan');             	
             	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'validCoti',
         	    	data	:   { itemplan : itemplan,
         	    	                accion : 3},// 2 == devolver         	    		
         	    	'async'	:	false
         	    })
         	    .done(function(data){
         	    	var data	=	JSON.parse(data);                 	    	
         	    	if(data.error == 0){
         	    	
					   $('#contTabla').html(data.tablaAsigGrafo);
           	    	   initDataTable('#data-table');
              	    	 
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
        </script>
    </body>
</html>