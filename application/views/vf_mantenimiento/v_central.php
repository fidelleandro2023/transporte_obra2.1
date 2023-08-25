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
                                    <h2>MANTENIMIENTO CENTRAL</h2>
                                    
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	   				       
    		   				                        <div>
                                                        <a onclick="addCentral()" style="background-color: (--verde_telefonica); color: white;" class="btn btn-primary" >AGREGAR CENTRAL</a>
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
        
        <div class="modal fade" id="modalRegistrarCentral">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">REGISTRAR CENTRAL</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddCentral" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                         <label>TIPO CENTRAL</label>
                                                <select id="selectTipoCentral" name="selectTipoCentral" class="select2 form-control" ">
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idTipoCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>
                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>DESCRIPCION</label>
                                        <input id="inputDescripcion" name="inputDescripcion" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                        <i class="form-group__bar"></i>
                                    </div>
                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>CODIGO</label>
                                        <input id="inputCodigo" name="inputCodigo" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                        <i class="form-group__bar"></i>
                                    </div>
                                    <div class="form-group">
                                         <label class="control-label">ZONAL</label>
                                                <select id="selectZonal" name="selectZonal" class="select2 form-control">
                                                    <option>&nbsp;</option>
                                                    <?php                                                    
                                                            foreach($listazonas->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idZonal ?>"><?php echo $row->zonalDesc ?></option>
                                                         <?php }?>
                                                       
                                                </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                             <label class="control-label">EECC</label>
                                                    <select id="selectEECC" name="selectEECC" class="select2 form-control">
                                                        <option>&nbsp;</option>
                                                            <?php                                                    
                                                            foreach($listaeecc->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                         <?php }?>
                                                    </select>
                                        </div>
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <!-- <label>JEFATURA</label>
                                            <input id="inputJefatura" name="inputJefatura" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                            <i class="form-group__bar"></i> -->
                                            <label for="cmbJefatura1">JEFATURA </label>
                                            <select id="cmbJefatura1" name="cmbJefatura1" class="select2 form-control">
                                            </select>

                                        </div>
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>REGION</label>
                                            <input id="inputRegion" name="inputRegion" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                            <i class="form-group__bar"></i>
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

       <div class="modal fade" id="modalEditarCentral">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR CENTRAL</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formEditCentral" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                         <label>TIPO CENTRAL</label>
                                                <select id="selectTipoCentral2" name="selectTipoCentral2" class="select2 form-control" ">
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idTipoCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>
                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>DESCRIPCION</label>
                                        <input id="inputDescripcion2" name="inputDescripcion2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                        <i class="form-group__bar"></i>
                                    </div>
                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>CODIGO</label>
                                        <input id="inputCodigo2" name="inputCodigo2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                        <i class="form-group__bar"></i>
                                    </div>
                                    <div class="form-group">
                                         <label class="control-label">ZONAL</label>
                                                <select id="selectZonal2" name="selectZonal2" class="select2 form-control">
                                                    <option>&nbsp;</option>
                                                    <?php                                                    
                                                            foreach($listazonas->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idZonal ?>"><?php echo $row->zonalDesc ?></option>
                                                         <?php }?>
                                                       
                                                </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                             <label class="control-label">EECC</label>
                                                    <select id="selectEECC2" name="selectEECC2" class="select2 form-control">
                                                        <option>&nbsp;</option>
                                                            <?php                                                    
                                                            foreach($listaeecc->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                         <?php }?>
                                                    </select>
                                        </div>
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <!-- <label>JEFATURA</label>
                                            <input id="inputJefatura2" name="inputJefatura2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                            <i class="form-group__bar"></i> -->
                                            <label for="cmbJefatura">JEFATURA </label>
                                            <select id="cmbJefatura" name="cmbJefatura" class="select2 form-control">
                                            </select>
                                        </div>
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>REGION</label>
                                            <input id="inputRegion2" name="inputRegion2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                            <i class="form-group__bar"></i>
                                        </div>
                                </div>
                            </div>
                        <div id="mensajeForm2"></div>  
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                <button id="btnEdit" type="submit" class="btn btn-primary">Save changes</button>
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
        
        var codigoEdit = null;
        
        function editCentral(component){
            
            var id = $(component).attr('data-id_cen');
        
              $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getInfoCen',
     	    	data	:	{ id : id },
     	    	'async'	:	false
     	    }).done(function(data){
         	    var data = JSON.parse(data);            	    
         	     codigoEdit = data.codigo;
          	   
          	    $('#formEditCentral').bootstrapValidator('resetForm', true); 
          	     $('#selectTipoCentral2').val(data.tipoCentral).trigger('change');
    			 $('#inputDescripcion2').val(data.descripcion);
				 $('#inputCodigo2').val(codigoEdit);
				 $('#selectZonal2').val(data.zonal).trigger('change');
				 $('#selectEECC2').val(data.eecc).trigger('change');						
				//  $('#inputJefatura2').val(data.jefatura);
				 $('#inputRegion2').val(data.region);	
				 $('#btnEdit').attr('data-id',id);				
                 $('#modalEditarCentral').modal('toggle'); //abrirl modal 
                 $('#cmbJefatura').html(data.cmbJefatura);
                        	
     	    })
     	    
        }
        
            function existeCodigo(codigo){
            	var result = $.ajax({
            		type : "POST",
            		'url' : 'validCod',
            		data : {
            			'codigo' : codigo
            		},
            		'async' : false
            	}).responseText;
            	return result;
            }
            function existeCodigo2(codigo){
            	var result = $.ajax({
            		type : "POST",
            		'url' : 'validCod',
            		data : {
            			'codigo' : codigo
            		},
            		'async' : false
            	}).responseText;
            	return result;
            }
            function addCentral(){
                
                $('#selectTipoCentral').val('').trigger('change');
           	    $('#selectEECC').val('').trigger('change');
           	    $('#selectZonal').val('').trigger('change');              	 
                $('#formAddCentral').bootstrapValidator('resetForm', true); 
                $.ajax({
                    type: 'POST',
                    url: 'getCmbJefaturaReg'
                    
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $("#cmbJefatura1").html(data.cmbJefatura);
                        $('#modalRegistrarCentral').modal('toggle'); //abrir modal  

                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al traer la jefatura');
                    }
                });
            	    	
            }

            
            $('#formAddCentral')
        	.bootstrapValidator({
        	    container: '#mensajeForm',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
        	    	
        	    	selectTipoCentral: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar Tipo Central.</p>'
        	                }
        	            }
        	        },
        	        inputDescripcion: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar una descripcion.</p>'
        	                }
        	            }
        	        },	 	
        	        inputCodigo: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar un CODIGO</p>'
        	                },
              	             callback: {
                	               message: '<p style="color:red">(*) El Codigo ya se encuentra Registrado.</p>',
              	                    callback: function(value, validator){                  	                                
                  	                    	result = existeCodigo(value);
                  	                        if(result == '1'){//Existe
                  		                        return false;
                  	                        }else{
                  		                        return true;
                  	                        }                  	                    
              	                    }
          	                    }
          	                }
    	            },    	            
    	            selectZonal: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar una Zonal.</p>'
        	                }
        	            }
        	        },
        	        selectEECC: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar EECC.</p>'
        	                }
        	            }
        	        },
        	        cmbJefatura1: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar una Jefatura.</p>'
        	                }
        	            }
        	        },
        	        inputRegion: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar una Region.</p>'
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
    			        url: "addCentral",
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
    				    		$('#modalRegistrarCentral').modal('toggle');
    				    		mostrarNotificacion('success','Operaci�n �xitosa.', 'Se registro correcamente!');
    						}else if(data.error == 1){
    							mostrarNotificacion('error','Error','No se inserto la central');
    						}
    			  	  })
    			  	  .fail(function(jqXHR, textStatus, errorThrown) {
    			  		mostrarNotificacion('error','Error','Comun�quese con alguna persona a cargo :(');
    			  	  })
    			  	  .always(function() {
    			      	 
    			  	});
        		   
        	    
        	});
      
            
      
            $('#formEditCentral')
        	.bootstrapValidator({
        	    container: '#mensajeForm2',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
        	    	
        	    	selectTipoCentral2: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar Tipo Central.</p>'
        	                }
        	            }
        	        },
        	        inputDescripcion2: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar una descripcion.</p>'
        	                }
        	            }
        	        },	 	
        	        inputCodigo2: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar un CODIGO</p>'
        	                },
              	             callback: {
                	               message: '<p style="color:red">(*) El Codigo ya se encuentra Registrado.</p>',
              	                    callback: function(value, validator){
         	                    	
                	                    if(codigoEdit == value){
               	                    	             return true;	                   
                  	                    	
                	                  }else{
                	                    	result2 = existeCodigo2(value);
                  	                        if(result2 == '1'){//Existe
                  		                        return false;
                  	                        }else{
                  		                        return true;
                  	                        }
                	                    }
              	                    }
          	                    }
          	                }
    	            },    	            
    	            selectZonal2: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar una Zonal.</p>'
        	                }
        	            }
        	        },
        	        selectEECC2: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar EECC.</p>'
        	                }
        	            }
        	        },
        	        inputJefatura2: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar una Jefatura.</p>'
        	                }
        	            }
        	        },
        	        inputRegion2: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar una Region.</p>'
        	                }
        	            }
                    },
                    cmbJefatura: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar una Jefatura.</p>'
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

                var id = $('#btnEdit').attr('data-id');
                var descJefatura = $.trim($('#cmbJefatura option:selected').text());
                formData.append('id', id);
                formData.append('descJefatura', descJefatura);
        	    
        		    $.each(params, function(i, val) {
        		        formData.append(val.name, val.value);
        		    });
        		    
        		    $.ajax({
    			        data: formData,
    			        url: "editCentral",
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
    				    		$('#modalEditarCentral').modal('toggle');
    				    		mostrarNotificacion('success','Operaci�n �xitosa.', 'Se registro correcamente!');
    						}else if(data.error == 1){
    							mostrarNotificacion('error','Error','No se inserto la central');
    						}
    			  	  })
    			  	  .fail(function(jqXHR, textStatus, errorThrown) {
    			  		mostrarNotificacion('error','Error','Comun�quese con alguna persona a cargo :(');
    			  	  })
    			  	  .always(function() {
    			      	 
    			  	});
        		   
        	    
        	});
      
        </script>    
        
       
        
    </body>


</html>