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
                                    <h2 style="text-align: center;">BANDEJA GESTI&Oacute;N CV</h2>
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	   				                         
                                                    <div class="row">
                                                        <div class="col-sm-3 col-md-3">
                                                        <div class="form-group">
                                                            <label>ITEMPLAN</label>
                                                            <input id="txtItemplan" type="text" class="form-control input-mask" placeholder="Itemplan" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                                        </div>
                                                    </div> 
                                                    <div class="col-sm-3 col-md-3">
                                                        <div class="form-group">
                                                            <label>NOMBRE DEL PROYECTO</label>
                                                            <input id="txtNomPro" type="text" class="form-control input-mask" placeholder="Nombre Proyecto" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                                        </div>
                                                    </div> 
                                                     <div class="col-md-3">
                                                        <div class="form-group has-feedback">
                                                            <label>ESTADO PLAN</label>
                                                            <select id="selectEstadoPlan" class="form-control select2">
                                                                <option value="">&nbsp;</option>
                                                                <?php foreach($listaEstadoPlan->result() as $row){ ?> 
                                                                                <option value="<?php echo $row->idEstadoPlan ?>"><?php echo utf8_decode($row->estadoPlanDesc) ?></option>
                                                                                 <?php }?>
                                                                
                                                            </select>
                                                        </div>
                                                    </div> 
                                                    <div class="col-sm-3 col-md-3">
                                                        <div class="form-group">
                                                            <label>OPERADOR</label>
                                                            <input id="txtOperador" type="text" class="form-control input-mask" placeholder="Operador" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                                        </div>
                                                    </div>                               
                                                    <div class="col-md-3">
                                                        <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla();">CONSULTAR</button>
                                                    </div>  
                                            </div>
	   				                            <div id="contTabla" class="table-responsive">
								                            <?php echo $tablaAsigGrafo?>
		                           </div>
		   				                        </div>
		   				                    </div>
		   				                </div>

			                <footer class="footer hidden-xs-down">
			                    <p>Telefonica del Peru.</p>		   				                   
		                   </footer>
            </section>
            
            <div class="modal fade" id="modalCancelPlan" tabindex="-1">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        
                                        <div class="modal-body">
                                           <div class="form-group">
                                                <div class="col-sm-12" id="select_cancelar">
                                                    <div class="form-group">
                                                        <label>MOTIVO CANCELAR</label>        
                                                            <select id="motivoCancelar" class="select2  form-control rmotivo">
                                                                <option selected="" value="0">Seleccione Moitivo</option>
                                                                 <?php                                                    
                                                    foreach($listaMotivos as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->idMotivo ?>"><?php echo utf8_decode($row->motivoDesc) ?></option>
                                                 <?php }?>                                                
                                                            </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12" id="motivoe">
                                                    <label>Comentario</label>                
                                                    <div class="input-group">
                                                        
                                                        <div class="form-group">
                                                         <textarea class="form-control" row="8" id="comentCancelar"></textarea>   
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="botonContinuar" onclick="confirmCancelarPlanCV(this)" type="button" class="btn btn-link">Confirmar</button>
                                            <button type="button" class="btn btn-link" data-dismiss="modal">Salir</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        <script type="text/javascript">
        
        
                
        function aprobItemPlan(component){

        	var itemplan = $(component).attr('data-itemplan');
        	swal({
                title: 'Está seguro de aprobar el Itemplan '+itemplan+'?',
                text: 'Asegurese de validar la información seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, aprobar Itemplan!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){

            	$.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'aprobCV',
         	    	data	:	{itemplan	:	itemplan},
         	    	'async'	:	false
         	    })
         	    .done(function(data){
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){             	    	          	    	   
         	    		mostrarNotificacion('success','Operación éxitosa.',data.msj);         	    		
         	    		$('#contTabla').html(data.tablaAsigGrafo)
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

        function filtrarTabla(){
     	     var subProy = $.trim($('#selectSubProy').val()); 
           	 var eecc = $.trim($('#selectEECC').val()); 
           	 var zonal = $.trim($('#selectZonal').val()); 
            	var item = $.trim($('#selectHasItemPlan').val()); 
             	var mes = $.trim($('#selectMesEjec').val()); 
             	var area = $.trim($('#selectArea').val()); 
             	var estado = $.trim($('#selectEstado').val()); 
             	
       	    $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'getDataTablePre',
       	    	data	:	{subProy  :	subProy,
               	    		eecc      : eecc,
            	    	    zonal     : zonal,
         	    	           item : item,
        	    	           mes : mes,
        	    	           area : area,
        	    	           estado : estado},
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
        
       function cancelItemplan(component){
        	var itemplan = $(component).attr('data-itemplan');
        	$('#motivoCancelar').val('0').trigger('change');
        	$('#comentCancelar').val('');
        	$('#botonContinuar').attr('data-item',itemplan);
        	$('#modalCancelPlan').modal('toggle');
        }

       
       function confirmCancelarPlanCV(component){
    	   var motivo =  $('#motivoCancelar').val();
    	   var comentario = $('#comentCancelar').val();
    	   if(comentario != '' && motivo != '0'){
    		   var itemplan = $(component).attr('data-item');
            	swal({
                    title: 'Está seguro de CANCELAR el Itemplan '+itemplan+'?',
                    text: 'Asegurese de validar la información seleccionada!',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, Cancelar Itemplan!',
                    cancelButtonClass: 'btn btn-secondary'
                }).then(function(){
                   var itemplanFil    = $.trim($('#txtItemplan').val()); 
               	   var nomProy     = $.trim($('#txtNomPro').val());
               	   var estadoPlan  = $.trim($('#selectEstadoPlan').val());
               	   var operador    = $.trim($('#txtOperador').val());
               	   
                	$.ajax({
             	    	type	:	'POST',
             	    	'url'	:	'cancelCV',
             	    	data	:	{'itemplan'	 :   itemplan,
             	    	             'coment'    :   comentario,
                         	    	 'motivo'    :   motivo,
                          	    	  itemplanFil  : itemplanFil,
           	    	                  nomProy   : nomProy,
            	    	              estadoPlan : estadoPlan,
              	    	              operador : operador},
             	    	'async'	:	false
             	    })
             	    .done(function(data){
             	    	var data	=	JSON.parse(data);
             	    	if(data.error == 0){             	    	          	    	   
             	    		mostrarNotificacion('success','Operación éxitosa.',data.msj);         	    		
             	    		$('#contTabla').html(data.tablaCVResidencial);
               	    	    initDataTable('#data-table');
                  	    	$('#modalCancelPlan').modal('toggle');
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

        	   }else{
        		   alert('Debe Seleccionar un Motivo y Llenar un comentario para poder cancelar el Itemplan Seleccionado.');
       		    }
    	   
       }

       /***nuevo filtro 25.04.2019*******/
       function filtrarTabla(){
     	   var itemplan    = $.trim($('#txtItemplan').val()); 
     	   var nomProy     = $.trim($('#txtNomPro').val());
     	   var estadoPlan  = $.trim($('#selectEstadoPlan').val());
     	   var operador    = $.trim($('#txtOperador').val());
      	  
           	
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'filTabResi',
     	    	data	:	{itemplan  : itemplan,
     	    	             nomProy   : nomProy,
      	    	             estadoPlan : estadoPlan,
        	    	           operador : operador},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){           	    	          	    	   
     	    		$('#contTabla').html(data.tablaCVResidencial);
     	    	    initDataTable('#data-table');     	    		
     			}else if(data.error == 1){     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
     		  });
      }
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>