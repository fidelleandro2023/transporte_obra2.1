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
                                    <h2>MANTENIMIENTO PERMISOS POR PERFIL</h2>
                                    
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	   				       
    		   				                        <div>
                                                        <a onclick="addPermisoPerfil()" style="background-color: (--verde_telefonica); color: white;" class="btn btn-primary" >AGREGAR ACCESO</a>
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
        
        <div class="modal fade" id="modalRegistrarPermisoPerfil">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">REGISTRAR PERMISO POR PERFIL</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddPermisoPerfil" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>PERFIL</label>
                                            <select id="selectPerfil" name="selectPerfil" class="select2 form-control">
                                                    <option>&nbsp;</option>
                                                      <?php foreach($listaPerfiles->result() as $row){ ?> 
                                                        <option value="<?php echo $row->id_perfil ?>"><?php echo $row->desc_perfil ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>                                   
                                </div>
                                <div class="col-sm-6 col-md-6">
                                     <div class="form-group">
                                         <label>PERMISO</label>
                                                <select id="selectPermiso" name="selectPermiso" class="select2 form-control">
                                                           <option>&nbsp;</option>
                                                      <?php foreach($listaPermisos->result() as $row){ ?> 
                                                        <option value="<?php echo $row->id_permiso ?>"><?php echo $row->descripcion ?></option>
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

       <div class="modal fade" id="modalEditarPermisoPerfil">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR PERMISO POR PERFIL</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formEditPermisoPerfil" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                         <label>PERFIL</label>
                                                <select id="selectPerfil2" name="selectPerfil2" class="select2 form-control">
                                                   
                                                    <option>&nbsp;</option>
                                                      <?php foreach($listaPerfiles->result() as $row){ ?> 
                                                        <option value="<?php echo $row->id_perfil ?>"><?php echo $row->desc_perfil ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>                                   
                                </div>
                                <div class="col-sm-6 col-md-6">
                                     <div class="form-group">
                                         <label>PERMISO</label>
                                                 <select id="selectPermiso2" name="selectPermiso2" class="select2 form-control">
                                                           <option>&nbsp;</option>
                                                      <?php foreach($listaPermisos->result() as $row){ ?> 
                                                        <option value="<?php echo $row->id_permiso ?>"><?php echo $row->descripcion ?></option>
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

        function editPermisoPerfil(component){
            
            var id = $(component).attr('data-id');
        
              $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getInfoPermisoPerfil',
     	    	data	:	{ id : id },
     	    	'async'	:	false
     	    }).done(function(data){
         	    var data = JSON.parse(data);            	    
         	               	   
          	    $('#formEditPermisoPerfil').bootstrapValidator('resetForm', true); 

                $('#selectPerfil2').val(data.perfil).trigger('change');
                $('#selectPermiso2').val(data.permiso).trigger('change');
               	$('#btnEdit').attr('data-id',id);				
				$('#modalEditarPermisoPerfil').modal('toggle'); //abrirl modal        	
     	    })
     	    
        }
        
            function addPermisoPerfil(){
                
                /*habilitacion campos de creacion*/
                $('#selectPerfil').val('').trigger('change');
           	    $('#selectPermiso').val('').trigger('change');
               
           	    $('#formAddPermisoPerfil').bootstrapValidator('resetForm', true); 
            	$('#modalRegistrarPermisoPerfil').modal('toggle'); //abrirl modal        	
            }


            function existePermisoPerfil(permiso,perfil){
                var result = $.ajax({
                    type : "POST",
                    'url' : 'validaPermisoPerfil',
                    data : {
                        'permiso' : permiso,
                        'perfil'  : perfil
                    },
                    'async' : false
                }).responseText;
                return result;
            }



            
            $('#formAddPermisoPerfil')
        	.bootstrapValidator({
        	    container: '#mensajeForm',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
                    selectPerfil: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un perfil.</p>'
                            },
                             callback: {
                                   message: '<p style="color:red">(*) El perfil ya cuenta con este permiso.</p>',
                                    callback: function(value, validator){
                                            var permiso = $('#selectPermiso').val();
                                            if (permiso!=null){
                                                var result = existePermisoPerfil(permiso,value);
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
                    },
                     selectPermiso: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar el permiso.</p>'
                            },
                             callback: {
                                   message: '<p style="color:red">(*) El permiso ya ha sido asignado a este perfil.</p>',
                                    callback: function(value, validator){
                                            var perfil = $('#selectPerfil').val();
                                            
                                            if(perfil!=null){
                                                var result = existePermisoPerfil(value,perfil);
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
        		    

        		    $.ajax({
    			        data: formData,
    			        url: "addPermisoPerfil",
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
    				    		$('#modalRegistrarPermisoPerfil').modal('toggle');
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
      
            
      
            $('#formEditPermisoPerfil')
        	.bootstrapValidator({
        	    container: '#mensajeForm2',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
                    selectPerfil2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un perfil.</p>'
                            },
                             callback: {
                                   message: '<p style="color:red">(*) El perfil ya cuenta con este permiso.</p>',
                                    callback: function(value, validator){
                                            var permiso = $('#selectPermiso2').val();
                                            if (permiso!=null){
                                                                                         
                                               var result = existePermisoPerfil(permiso,value);
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
                    },
                     selectPermiso2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un permiso.</p>'
                            },
                             callback: {
                                   message: '<p style="color:red">(*) El permiso ya ha sido asignado a este perfil.</p>',
                                    callback: function(value, validator){
                                            var perfil = $('#selectPerfil2').val();
                                            if(perfil!=null){
                                                                                         
                                                var result = existePermisoPerfil(value,perfil);
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

        	    var id_permiso_perfil = $('#btnEdit').attr('data-id');
        	    formData.append('id', id_permiso_perfil);
        	    
        		    $.each(params, function(i, val) {
        		        formData.append(val.name, val.value);
        		    });
        		    
        		    $.ajax({
    			        data: formData,
    			        url: "editPermisoPerfil",
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
    				    		$('#modalEditarPermisoPerfil').modal('toggle');
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




          function deletePermisoPerfil(component){

                 
                 var perfil = $(component).attr('data-perfil');
                 var permiso = $(component).attr('data-permiso');

            swal({
                title: 'Está seguro de eliminar el permiso '+permiso+' para el perfil '+perfil,
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
                    url     : "delPermisoPerfil",
                    data: {'id_permiso_perfil' : id
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