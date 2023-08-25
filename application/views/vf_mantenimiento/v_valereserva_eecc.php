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
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        
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
                  <a href="https://www.movistar.com.pe/" title="Movistar"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Movistar" style="width: 36%; margin-left: -51%"></a>
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
                                    <h2>MANTENIMIENTO VALE DE RESERVA - EECC</h2>
                                    
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	   				       
    		   				                        <div>
                                                        <a onclick="addPermisoPerfil()" style="background-color: #0154a0; color: white;" class="btn btn-primary" >AGREGAR REGISTRO</a>
                                                    </div>              
                                                    <div id="contTabla" class="table-responsive">
								                            <?php echo $listartabla ?>
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

        <!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>
        
        <div class="modal fade" id="modalRegistrarValeReservaEECC">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">REGISTRAR VALE RESERVA EECC</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddValeReservaEECC" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>VALE DE RESERVA</label>
                                            <input id="valereserva" name="valereserva" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i> 
                                    </div>                                   
                                </div>
                                <div class="col-sm-6 col-md-6">
                                     <div class="form-group">
                                         <label>EECC</label>
                                                <label>EECC</label>
                                                <select id="selecteecc" name="selecteecc" class="select2 form-control">
                                                           <option>&nbsp;</option>
                                                      <?php foreach($listaEECCVR->result() as $row){ ?> 
                                                        <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                         <?php }?>                                             
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

       <div class="modal fade" id="modalEditarValeReservaEECC">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR VALE DE RESERVA EECC</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formEditValeReservaEECC" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                         <label>VALE DE RESERVA</label>
                                            <input id="valereserva2" name="valereserva2" type="text" class="form-control" readonly><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                
                                    </div>                                   
                                </div>
                                <div class="col-sm-6 col-md-6">
                                     <div class="form-group">
                                         <label>EECC</label>
                                                 <select id="selecteecc2" name="selecteecc2" class="select2 form-control">
                                                           <option>&nbsp;</option>
                                                      <?php foreach($listaEECCVR->result() as $row){ ?> 
                                                        <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                         <?php }?>                                             
                                                </select>
                                    </div>
                                                                        

                                </div>
                                
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
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script type="text/javascript">

        /*actualizacion dinamica de combobox*/
        /*actualizacion de subproyecto a partir del proyecto*/

        function editValeReservaEECC(component){
            
            var id = $(component).attr('data-id');
        
              $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getInfoVREECC',
     	    	data	:	{ id : id },
     	    	'async'	:	false
     	    }).done(function(data){
         	    var data = JSON.parse(data);            	    
         	               	   
          	    $('#formEditValeReservaEECC').bootstrapValidator('resetForm', true); 

                $('#valereserva2').val(data.codigovr);
                $('#selecteecc2').val(data.eecc).trigger('change');
               	$('#btnEdit').attr('data-id',id);				
				$('#modalEditarValeReservaEECC').modal('toggle'); //abrirl modal        	
     	    })
     	    
        }
        
            function addPermisoPerfil(){
                
                /*habilitacion campos de creacion*/
                $('#valereserva').val('');
           	    $('#selecteecc').val('').trigger('change');
               
           	    $('#formAddValeReservaEECC').bootstrapValidator('resetForm', true); 
            	$('#modalRegistrarValeReservaEECC').modal('toggle'); //abrirl modal        	
            }


            function existeVR(valereserva){
                var result = $.ajax({
                    type : "POST",
                    'url' : 'validaVREECC',
                    data : {
                        'valereserva' : valereserva
                    },
                    'async' : false
                }).responseText;
                return result;
            }



            
            $('#formAddValeReservaEECC')
        	.bootstrapValidator({
        	    container: '#mensajeForm',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
                    valereserva: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el vale de reserva.</p>'
                            },
                             callback: {
                                   message: '<p style="color:red">(*) El vale de reserva ya existe.</p>',
                                    callback: function(value, validator){
                                            var result = existeVR(value);
                                                if(result == '1'){//Existe
                                                    return false;
                                                }else{
                                                    return true;
                                                }                                
                                    }
                             }
                        }
                    },
                     selecteecc: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar la empresa colaobradora.</p>'
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
    			        url: "addVREECC",
    			        cache: false,
    		            contentType: false,
    		            processData: false,
    		            type: 'POST'
    			  	})
    				  .done(function(data) {  
    					    	data = JSON.parse(data);
    				    	if(data.error == 0){    				    						    		
    				    		$('#contTabla').html(data.listartabla);    				    					
    		       	    	    initDataTable('#data-table');
    				    		$('#modalRegistrarValeReservaEECC').modal('toggle');
    				    		mostrarNotificacion('success','Operación exitosa.', 'Se registro correcamente!');
    						}else if(data.error == 1){
    							mostrarNotificacion('error','Error','No se inserto el permiso por perfil');
    						}
    			  	  })
    			  	  .fail(function(jqXHR, textStatus, errorThrown) {
    			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
    			  	  })
    			  	  .always(function() {
    			      	 
    			  	});
        		   
        	    
        	});
      
            
      
            $('#formEditValeReservaEECC')
        	.bootstrapValidator({
        	    container: '#mensajeForm2',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
                     selecteecc2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un permiso.</p>'
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

        	    var idvalereserva = $('#btnEdit').attr('data-id');
        	    formData.append('id', idvalereserva);
        	    
        		    $.each(params, function(i, val) {
        		        formData.append(val.name, val.value);
        		    });
        		    
        		    $.ajax({
    			        data: formData,
    			        url: "editVREECC",
    			        cache: false,
    		            contentType: false,
    		            processData: false,
    		            type: 'POST'
    			  	})
    				  .done(function(data) {  
    					    	data = JSON.parse(data);
    				    	if(data.error == 0){    				    						    		
    				    		$('#contTabla').html(data.listartabla);    				    					
    		       	    	    initDataTable('#data-table');
    				    		$('#modalEditarValeReservaEECC').modal('toggle');
    				    		mostrarNotificacion('success','Operación exitosa.', 'Se registro correcamente!');
    						}else if(data.error == 1){
    							mostrarNotificacion('error','Error','No se modificó el permiso por perfil');
    						}
    			  	  })
    			  	  .fail(function(jqXHR, textStatus, errorThrown) {
    			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
    			  	  })
    			  	  .always(function() {
    			      	 
    			  	});
           	});




          function deleteValeReservaEECC(component){
            swal({
                title: 'Está seguro de eliminar el registro seleccionado',
                text: 'Asegurese de validar la información seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){

                var id = $(component).attr('data-id');
            
                
                $.ajax({
                    type    :   'POST',
                    url     : "delVREECC",
                    data: {'id' : id
                                },
                    'async' :   false
                })
                .done(function(data){                   
                    var data = JSON.parse(data);
                    console.log(data);
                    if(data.error == 0){
                        $('#contTabla').html(data.listartabla);                                         
                        initDataTable('#data-table');                 
                        
                        mostrarNotificacion('success','Permiso Eliminado',data.msj); 
                    
                    }else if(data.error == 1){
                        $('#contTabla').html(data.listartabla);                                         
                         initDataTable('#data-table');
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

      
        </script>    
        
       
        
    </body>


</html>