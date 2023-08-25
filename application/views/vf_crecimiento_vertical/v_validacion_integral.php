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
                    <h2>VALIDACION CV INTEGRAL</h2>
                    <div class="card">		   				                    
                    
                        <div class="card-block"> 
                            <div class="row">
                               <!-- 
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>PEP 1</label>
                                        <input id="txtPep1" type="text" class="form-control input-mask" placeholder="Pep 1" autocomplete="off" maxlength="20" style="border-bottom: 1px solid lightgrey">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <button style="margin-top: 30px;" class="btn btn-success waves-effect" type="button" onclick="filtrarTablaHG();">CONSULTAR</button>
                                </div>  
                                 
								<div class="col-sm-12 col-md-12">
                                    <a class="btn waves-effect" style="background-color: #28B463; color: white; padding: 10px" href="excelHojaGes">Descargar Detalle</a>
                                </div> 
                               
                                
                                
                                <div class="col-sm-6 col-md-7">
                                        <label style="margin-top: 60px;color: red;font-weight: bolder;" id="montoDispoProy">MONTO PROYECTADO: S/.0</label>
                                </div> --> 
                            </div>
                            <div id="contTabla" class="table-responsive">
                                    <?php echo $tablaData?>
                            </div>
                        </div>
                    </div>
                </div>

            </section> 
        </main>
        
        <div class="modal fade"id="modal_edit_partidas"  data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                        <button type="button" class="close" onclick="closeDetItems();">&times;</button>
                    </div>
                    <div class="modal-body">
                      <div class="card" id="contDetPartidas">
                   
                
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
        <script type="text/javascript">


        $('.valPartidas').click(function(e){
        	var itemplan = $(this).attr('data-itm');console.log('itemplan2:'+itemplan);
                swal({
                    title: 'Est� seguro de validar la Obra?',
                    text: 'Se generara una solicitud de Edicion y de Certificacion por el Monto Final de la Obra',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, guardar los datos!',
                    cancelButtonClass: 'btn btn-secondary',
                    allowOutsideClick: false
                }).then(function(){
                	$.ajax({
                        type : 'POST',
                        url  : 'valObraInte',
                        data : { itemplan   : itemplan}
                    }).done(function(data){
                         var data	=	JSON.parse(data);
                         if(data.error == 0){                                
                        	 swal({
                                 title: 'Se realizo la operacion con exito!',
                                 text: 'Se actualizaron los datos!',
                                 type: 'success',
                                 buttonsStyling: false,
                                 confirmButtonClass: 'btn btn-primary',
                                 confirmButtonText: 'OK!'
        
                             }).then(function () {                            	
                            	 location.reload();                                   	 
                            });                           	  
                           
                         }else if(data.error == 1){    				
                             mostrarNotificacion('warning',data.msj);
                         }
                      });
                }, function(dismiss) {
                    // dismiss can be "cancel" | "close" | "outside"
                        $('#formEditMoPo').bootstrapValidator('resetForm', true); 
                });
        });

        
            $('.editPartidas').click(function(e){
            	var itemplan = $(this).attr('data-itm');
            	
        	  $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'getDetEditPartCvInte',
       	    	data	:	{itemplan	:   itemplan},
       	    	'async'	:	false
       	    })
       	    .done(function(data){
       	    	var data	=	JSON.parse(data);
       	    	if(data.error == 0){         	    	
           	    	$('#tituloModal').html('ITEMPLAN : '+itemplan);         	    
                  	$('#contDetPartidas').html(data.htmlDetPartidas);                      		   	
                  		var listaManoObras = JSON.parse(data.valiPartidas);              	
                  		initFormEditPartidas();                	
                      	$.each( listaManoObras, function( key, value ) {
  							soloDecimal(value['name']);//nuevo 21.10.2019 zavalacas
                      		$('#formEditPartidas').bootstrapValidator('addField', value['name'], {
                                  validators: {                               
                              		callback: {
                  	                    message: '<p style="color:red">(*)Ingrese un numero Valido dentro del rango permitido entre 0 y '+value["max"]+'</p>',
                  	                    callback: function(value2, validator) {
                      	                    console.log('value33:'+value2);
                      	                    if(value2 != '' ){												
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
               	    $('#modal_edit_partidas').modal('toggle'); 
               	    
               	    /************************/
       	    	}else if(data.error == 1){     				
          	    	 mostrarNotificacion('warning', data.msj);
      			}
      		  })
      		  .fail(function(jqXHR, textStatus, errorThrown) {
      		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
      		  })
      		  .always(function() {
      	  	 
      		});
            });

            function closeDetItems(){       	      
                $('#modal_edit_partidas').modal('toggle');
                //$('#modal-large').css('overflow-y', 'scroll');          
            }

            function initFormEditPartidas(){
            	$('#formEditPartidas')
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
                        
                        var itemplan = $('#btnRegPartidas').attr('data-itemplan');
                        formData.append('itemplan', itemplan);
                        
                        swal({
                            title: 'Est� seguro de actualizar la informacion de las Partidas?',
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
                                url: "updatEPartInte",
                                cache: false,
                                contentType: false,
                                processData: false,
                                type: 'POST'
                              })
                              .done(function(data) {
                                 var data	=	JSON.parse(data);
                                 if(data.error == 0){                                
                                	 swal({
                                         title: 'Se realizo la operacion con exito!',
                                         text: 'Se actualizaron las partidas correspondientes!',
                                         type: 'success',
                                         buttonsStyling: false,
                                         confirmButtonClass: 'btn btn-primary',
                                         confirmButtonText: 'OK!'
    
                                     }).then(function () {
                                    	 $('#modal_edit_partidas').modal('toggle');
                                    	 /*$('#contTabla').html(data.tablaData);
                                    	 initDataTable('#data-table'); */
                                    	 location.reload();                                   	 
                                    });                           	  
                                   
                                 }else if(data.error == 1){    				
                                     mostrarNotificacion('warning', data.msj);
                                 }
                              });
                        }, function(dismiss) {
                            // dismiss can be "cancel" | "close" | "outside"
                                $('#formEditMoPo').bootstrapValidator('resetForm', true); 
                        });
                    });
                }
            
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>