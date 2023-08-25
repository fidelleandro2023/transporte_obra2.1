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
            <section class="content content--full" style="MARGIN-TOP: -70px;">
                <div class="content__inner">
                    <div class="card">
                            <?php echo $contenidoBlock?>
                    </div>
                </div>
                </div>
            </section>
            
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
            
             <div class="modal fade" id="modalRechazado"  tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 style="margin: auto" class="modal-title">Solicitud Rechazada</h3>
                        </div>
                        
                        <div class="modal-body">                       
                            <div id="contTablaRechazo">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>      
                    </div>
                </div>
            </div> 
            
              <!-- moda de carga de evidencias... -->
             <div class="modal fade bd-example-modal-sm" id="modalProgreso" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
              <div class="modal-dialog modal-sm">
                <div class="modal-content" style="text-align: center;background-color: #00000070;border-radius: 30%;">
                    <div class="modal-header">
                        <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title"></h4>                       
                    </div>
                    <div class="modal-body">
                        <div id="contProgres">
                               <div class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="rgb(255, 255, 255)" data-bar-color="rgb(255, 255, 255)">
                                    <span id="valuePie" class="easy-pie-chart__value">0</span>
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">                       
                        <h3 style="color: rgb(255, 255, 255);padding-right: 35px;">Procesando...</h3>                          
                    </div>
                </div>
              </div>
            </div>
        </main>
        
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
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script type="text/javascript">
        
            var itemplan = <?php echo $item?>;
            $('.gpoMo').click(function(e){
                   var id_estacion = $(this).attr('data-idEs');
                   var item_sa = itemplan;
                   var codigo_po   = $(this).attr('data-po');
            	   console.log(itemplan+' - '+id_estacion);
                   
                	swal({
                        title: 'Está seguro generar la PO?',
                        text: 'Asegurese de que la informacion llenada sea la correta.',
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
                     	    	'url'	:	'testCreatePoMOPqt',
                     	    	data	:	{ itemplan    :   item_sa,
                      	    	              idEstacion  :   id_estacion,
                        	    	            codigo_po : codigo_po},
                     	    	'async'	:	false
                     	    })
                     	    .done(function(data){
                   	    	   console.log('ok.....');
                         	  
                     	    	var data	=	JSON.parse(data);
                     	    	if(data.error == 0){
                     	    		swal({
                                        title: 'Se genero correctamente el PO: ' + data.codigoPO + '',
                                        text: 'Asegurese de validar la informacion!',
                                        type: 'success',
                                        buttonsStyling: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: 'OK!'
    
                                    }).then(function () {
                                       // window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
                                    	cargarTerminado(item_sa);
                                    });
                     	    		
                     			}else if(data.error == 1){
                     				mostrarNotificacion('error','Ocurrio un error!', data.msj);
                     			}
                     		  });
    
                    })
         		 
         		  
            });

            $('.sendVali').click(function(e){
                var id_estacion = $(this).attr('data-idEs');
                var codigo_po   = $(this).attr('data-po');
                var item_sa = itemplan;

                var input2File = document.getElementById('fileEvidencia');
                var file2 = input2File.files[0];                
                
                if(file2 == null){
                	mostrarNotificacion('warning','Debe adjuntar el Expediente');
                	return false;
               	}
                
             	swal({
                     title: 'Está seguro de enviar a validar la propuesta?',
                     text: 'Asegurese de que la informacion llenada sea la correta.',
                     type: 'warning',
                     showCancelButton: true,
                     buttonsStyling: false,
                     confirmButtonClass: 'btn btn-primary',
                     confirmButtonText: 'Si, guardar los datos!',
                     cancelButtonClass: 'btn btn-secondary',
                     allowOutsideClick: false
                 }).then(function(){
                     console.log('ok');
                        var costo_total_pqt = $('#tot_pqt_'+id_estacion).attr('data-cost');
                        var costo_total_adic = $('#tot_ad_'+id_estacion).attr('data-cost');
                        var costo_total = Number(costo_total_pqt) + Number(costo_total_adic);

                        var form_data = new FormData();  
                        form_data.append('fileEvi', file2);
                        form_data.append('itemplan', item_sa);
                        form_data.append('idEstacion', id_estacion);
                        form_data.append('costo_total', costo_total);
                        form_data.append('costo_inicial', costo_total_pqt);
                        form_data.append('costo_adicional', costo_total_adic);
                        form_data.append('codigo_po', codigo_po);

                  	    $.ajax({
                  	    	type	:	'POST',
                  	    	'url'	:	'sendValidatePartAdic',                  	    	
                      	    	data: form_data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                xhr: function() {
                                    $('.easy-pie-chart').data('easyPieChart').update('0');
                                	$('#valuePie').html('0');
                                	$('#modalProgreso').modal('toggle');
                                    var xhr = $.ajaxSettings.xhr();
                                    xhr.upload.onprogress = function(e) {
                                        var progreso = Math.floor(e.loaded / e.total *100);                                
                                        console.log(progreso);
                                        $('.easy-pie-chart').data('easyPieChart').update(progreso);
                                        $('#valuePie').html(progreso);
                                    };
                                    return xhr;
                                }  
                      	    }).done(function(data){
                	    	   console.log('ok.....');
                      	  
                  	    	var data	=	JSON.parse(data);
                  	    	if(data.error == 0){
                  	    		setTimeout(
                      				  function() 
                      				  {
                    					$('#modalProgreso').modal('toggle');
                          	    		swal({
                                             title: 'Se envio a validacion las Partidas.',
                                             text: 'Asegurese de validar la informacion!',
                                             type: 'success',
                                             buttonsStyling: false,
                                             confirmButtonClass: 'btn btn-primary',
                                             confirmButtonText: 'OK!'
         
                                         }).then(function () {
                                             //window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
                                        	 cargarTerminado(item_sa);
                                         });
                       				 }, 5000);
                  			}else if(data.error == 1){
                  				mostrarNotificacion('error','Ocurrio un error!', data.msj);
                  			}
                  		  });
 
                 })
            });
            
            function cargarPartidasAdic(itemplan, idEstacion){
                $("#divModuloLoad").load("poMoAdic?itm="+itemplan+"&idEs="+idEstacion);
            }

            function cargarRegMateriales(itemplan, idEstacion){
                $("#divModuloLoad").load("regMatEsta?ite="+itemplan+"&est="+idEstacion);
            }

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

            $('.getRechazado').click(function(e){
            	var item       = $(this).attr('data-item');
            	var idEstacion = $(this).attr('data-esta');

                $.ajax({
                    type : 'POST',
                    url  : 'getInfoRech',
                    data : { itemplan   : item,
                 	         idEstacion : idEstacion}
                }).done(function(data){
                    data = JSON.parse(data);
                    if(data.error == 0) { 
                        $('#contTablaRechazo').html(data.tablaRechazado);//reutilizamos el modal
                        modal('modalRechazado');//reutilizamos el modal
                    } else {
                        mostrarNotificacion('error', data.msj, 'error');
                    }
                })
            });

            $('.sendCap').click(function(e){
                //var id_estacion = $(this).attr('data-idEs');
                //var item_sa = itemplan;
         	   //console.log(itemplan+' - '+id_estacion);
                
             	swal({
                     title: 'Está seguro de Generar Ticket CAP?',
                    // text: 'Asegurese de que la informacion llenada sea la correta.',
                     html : '<div class="form-group"><a>Ingrese un omentario</a></div><div><textarea maxlength="512" class="col-md-12 form-control" placeholder="Ingresar Comentario..." style="height:80px;background:#F9F8CF" id="comentarioText"></textarea></div>',
                     type: 'warning',
                     showCancelButton: true,
                     buttonsStyling: false,
                     confirmButtonClass: 'btn btn-primary',
                     confirmButtonText: 'Si, guardar los datos!',
                     cancelButtonClass: 'btn btn-secondary',
                     allowOutsideClick: false
                 }).then(function(){
                 })
            })
			
			$('.sendValiRuta').click(function(e){
                var id_estacion = $(this).attr('data-idEs');
                var costo_total = $(this).attr('data-costo_total');
                //var codigo_po   = $(this).attr('data-po');
                var item_sa = itemplan;

                var input2File = document.getElementById('fileEvidencia');
                var file2 = input2File.files[0];                
                
                if(file2 == null){
                	mostrarNotificacion('warning','Debe adjuntar el Expediente');
                	return false;
               	}
                
             	swal({
                     title: 'Está seguro de enviar a validar la propuesta?',
                     text: 'Asegurese de que la informacion llenada sea la correta.',
                     type: 'warning',
                     showCancelButton: true,
                     buttonsStyling: false,
                     confirmButtonClass: 'btn btn-primary',
                     confirmButtonText: 'Si, guardar los datos!',
                     cancelButtonClass: 'btn btn-secondary',
                     allowOutsideClick: false
                 }).then(function(){
                     console.log('ok');
                        var costo_total_pqt = $('#costo_total_ruta'+id_estacion).attr('data-cost');

                        var form_data = new FormData();  
                        form_data.append('fileEvi', file2);
                        form_data.append('itemplan', item_sa);
                        form_data.append('idEstacion', id_estacion);
                        form_data.append('costo_total', costo_total);

                  	    $.ajax({
                  	    	type	:	'POST',
                  	    	'url'	:	'sendValidateRutas',                  	    	
                      	    	data: form_data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                xhr: function() {
                                    $('.easy-pie-chart').data('easyPieChart').update('0');
                                	$('#valuePie').html('0');
                                	$('#modalProgreso').modal('toggle');
                                    var xhr = $.ajaxSettings.xhr();
                                    xhr.upload.onprogress = function(e) {
                                        var progreso = Math.floor(e.loaded / e.total *100);                                
                                        console.log(progreso);
                                        $('.easy-pie-chart').data('easyPieChart').update(progreso);
                                        $('#valuePie').html(progreso);
                                    };
                                    return xhr;
                                }  
                      	    }).done(function(data){
                	    	   console.log('ok.....');
                      	  
                  	    	var data	=	JSON.parse(data);
                  	    	if(data.error == 0){
                  	    		setTimeout(
                      				  function() 
                      				  {
                    					$('#modalProgreso').modal('toggle');
                          	    		swal({
                                             title: 'Se envio a validacion las Partidas.',
                                             text: 'Asegurese de validar la informacion!',
                                             type: 'success',
                                             buttonsStyling: false,
                                             confirmButtonClass: 'btn btn-primary',
                                             confirmButtonText: 'OK!'
         
                                         }).then(function () {
                                             //window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
                                        	 cargarTerminado(item_sa);
                                         });
                       				 }, 5000);
                  			}else if(data.error == 1){
                  				mostrarNotificacion('error','Ocurrio un error!', data.msj);
                  			}
                  		  });
 
                 })
            });
			
			$('.sendValiNoPqt').click(function(e){
                var costo_total = $(this).attr('data-costo_total');
                //var codigo_po   = $(this).attr('data-po');
                var item_sa = itemplan;

                var input2File = document.getElementById('fileEvidencia');
                var file2 = input2File.files[0];                
                
                if(file2 == null){
                	mostrarNotificacion('warning','Debe adjuntar el Expediente');
                	return false;
               	}
                
             	swal({
                     title: 'Está seguro de enviar a validar la propuesta?',
                     text: 'Asegurese de que la informacion llenada sea la correta.',
                     type: 'warning',
                     showCancelButton: true,
                     buttonsStyling: false,
                     confirmButtonClass: 'btn btn-primary',
                     confirmButtonText: 'Si, guardar los datos!',
                     cancelButtonClass: 'btn btn-secondary',
                     allowOutsideClick: false
                 }).then(function(){
                     console.log('ok');
                        //var costo_total_pqt = $('#costo_total_obra').attr('data-cost');

                        var form_data = new FormData();  
                        form_data.append('fileEvi', file2);
                        form_data.append('itemplan', item_sa);
                        form_data.append('costo_total', costo_total);

                  	    $.ajax({
                  	    	type	:	'POST',
                  	    	'url'	:	'sendValidateNoPqt',                  	    	
                      	    	data: form_data,
                                cache: false,
                                contentType: false,
                                processData: false,
                                xhr: function() {
                                    $('.easy-pie-chart').data('easyPieChart').update('0');
                                	$('#valuePie').html('0');
                                	$('#modalProgreso').modal('toggle');
                                    var xhr = $.ajaxSettings.xhr();
                                    xhr.upload.onprogress = function(e) {
                                        var progreso = Math.floor(e.loaded / e.total *100);                                
                                        console.log(progreso);
                                        $('.easy-pie-chart').data('easyPieChart').update(progreso);
                                        $('#valuePie').html(progreso);
                                    };
                                    return xhr;
                                }  
                      	    }).done(function(data){
                	    	   console.log('ok.....');
                      	  
                  	    	var data	=	JSON.parse(data);
                  	    	if(data.error == 0){
                  	    		setTimeout(
                      				  function() 
                      				  {
                    					$('#modalProgreso').modal('toggle');
                          	    		swal({
                                             title: 'Se envio a validacion las Partidas.',
                                             text: 'Asegurese de validar la informacion!',
                                             type: 'success',
                                             buttonsStyling: false,
                                             confirmButtonClass: 'btn btn-primary',
                                             confirmButtonText: 'OK!'
         
                                         }).then(function () {
                                             //window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
                                        	 cargarTerminado(item_sa);
                                         });
                       				 }, 5000);
                  			}else if(data.error == 1){
                  				mostrarNotificacion('error','Ocurrio un error!', data.msj);
                  			}
                  		  });
 
                 })
            });
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>