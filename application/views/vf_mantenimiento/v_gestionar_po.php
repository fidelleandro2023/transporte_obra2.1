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
                                    <h2>GESTIONAR PO</h2>
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	   				                         
                                                    <div class="row">
                                <div class="col-sm-6 col-md-6">
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
                                
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>NOMBRE PROYECTO</label>

                                        <select id="selectSubProy" name="selectSubProy" class="select2" onchange="filtrarTabla()" >
                                        <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($itemplanList->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->nombreProyecto ?>"><?php echo $row->nombreProyecto ?></option>
                                                 <?php }?>
                                           
                                        </select>
                                    </div>
                                </div>
                                <!-- 
                              
    
                              
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
                                -->
                            </div>
		   				                            <div id="contTabla" class="table-responsive">
								                            <?php echo $tablaAsigGrafo?>
		                           </div>
		   				                        </div>
		   				                    </div>
		   				                    
		   				                    
        <div class="modal fade"id="edi-Item"  tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">                                        
                           <form id="formEditPlan" method="post" class="form-horizontal">
                               <div class="row">
                               	<div class="col-sm-6 col-md-6">
                                       <div class="form-group">
                                       <label>PROYECTO</label>    
                                            <input type="text" class="form-control" id="inputProyecto" placeholder="Input Default" readonly="">
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                   	<div class="col-sm-6 col-md-6">
                                   			 
                                           <div class="form-group">
                                           <label>SUB PROYECTO</label>   
                                                <input type="text" class="form-control" id="inputSubProyecto" placeholder="Input Default" readonly="">
                                                <i class="form-group__bar"></i>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                   			 
                                           <div class="form-group">
                                           <label>FECHA INICIO</label>   
                                                <input type="text" class="form-control" id="inputFecInicio" placeholder="Input Default" readonly="">
                                                <i class="form-group__bar"></i>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                   			 
                                           <div class="form-group">
                                           <label>FECHA PREVISTA EJEC</label>   
                                                <input type="text" class="form-control" id="inputFecPrev" placeholder="Input Default" readonly="">
                                                <i class="form-group__bar"></i>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                   			 
                                           <div class="form-group">
                                           <label>EMPRESA COLAB.</label>   
                                                <input type="text" class="form-control" id="inputEECC" placeholder="Input Default" readonly="">
                                                <i class="form-group__bar"></i>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                     	<div class="form-group">
                                                    <label>ESTADO</label>        
                                                    <select id="selectEstaItem" name="selectEstaItem" class="select2  form-control" onchange="getFecToEdit()">
                                                        <option>&nbsp;</option>    
                                                                                                 
                                                    </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                     	<div class="form-group">
                                                    <label>ADELANTO</label>        
                                                    <select id="selectAdelanto" name="selectAdelanto" class="select2  form-control" >
                                                        <option value="0">NO</option> 
                                                        <option value="1">SI</option>                       
                                                    </select>
                                        </div> 
                                    </div>
                                    
                                    <div class="col-sm-6" style="display: none;" id="contFecEjec">
                                        <label>Fecha Ejecución</label>
    
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                            <div class="form-group">
                                                <input id="inputFecEjec" name="inputFecEjec" type="text" class="form-control date-picker" placeholder="Pick a date">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                	</div>
                                	<div class="col-sm-6" style="display: none;" id="contFecTerm">
                                        <label>Fecha Término</label>
    
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                            <div class="form-group">
                                                <input id="inputFecTerm" name="inputFecTerm" type="text" class="form-control date-picker" placeholder="Pick a date">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                	</div>
                                </div>
                                <div id="mensajeForm"></div>  
                                <div class="form-group" style="text-align: right;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                        <button id="btnEditItem" type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-container">
                            <div id="conTablaHisItem" class="table-responsive">     
                            
                            </div>
                        </div>
                        <br>                        
                       
                        
                    </div>
                  
                </div>
            </div>
            
            <div class="modal fade" id="edi-porcentajes"  tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="tituloModalPor" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">                                        
                              <form id="editPorcentaje" method="post" class="form-horizontal">
                                   <div class="row"  id="contChoice">
                              
                                    </div>
                                    <div id="goDetalle" style="display: none;">
                                        <a onclick="goSinFix();" style="color:blue; font-weight: bold">REGISTRO DETALLADO</a>
                                    </div>
                                    <div id="mensajeForm2"></div>  
                                    <div class="form-group" style="text-align: right;">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button id="btnEditPorcent" type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                        </div>                            
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
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
        <script type="text/javascript">
        function getFecToEdit(){
        	var idEsta = $.trim($('#selectEstaItem').val()); 
        	console.log('estado:'+idEsta);
        	if(idEsta==4){
            	$('#contFecEjec').show();
            	$('#contFecTerm').hide();
            	var validator = $('#formEditPlan').data('bootstrapValidator');
        		validator.enableFieldValidators('inputFecEjec', true); 
        		validator.enableFieldValidators('inputFecTerm', false);
        	}else if(idEsta==6){
        		$('#contFecTerm').show();
        		$('#contFecEjec').hide();
        		var validator = $('#formEditPlan').data('bootstrapValidator');
        		validator.enableFieldValidators('inputFecEjec', false); 
        		validator.enableFieldValidators('inputFecTerm', true);
        	}else{
        		$('#contFecEjec').hide();
        		$('#contFecTerm').hide();
        		var validator = $('#formEditPlan').data('bootstrapValidator');
        		validator.enableFieldValidators('inputFecEjec', false); 
        		validator.enableFieldValidators('inputFecTerm', false);
        	}
        }

        $('#formEditPlan')
    	.bootstrapValidator({
    	    container: '#mensajeForm',
    	    feedbackIcons: {
    	        valid: 'glyphicon glyphicon-ok',
    	        invalid: 'glyphicon glyphicon-remove',
    	        validating: 'glyphicon glyphicon-refresh'
    	    },
    	    excluded: ':disabled',
    	    fields: {
    	    	selectEstaItem: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe seleccionar un Estado Plan.</p>'
    	                }
    	             }
     	    	   },
      	    	  selectAdelanto: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar un Adelanto</p>'
        	                }
        	             }
         	    	   },
          	    	  inputFecEjec: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar Fecha de Ejecución</p>'
        	                },
        	                date: {
                                format: 'YYYY-MM-DD',
                                message: '<p style="color:red">(*) Fecha no válida</p>'
                            }
        	             }
         	    	   } ,
          	    	  inputFecTerm: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar Fecha de Término</p>'
        	                },
        	                date: {
                                format: 'YYYY-MM-DD',
                                message: '<p style="color:red">(*) Fecha no válida</p>'
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
    		    
				var itemplan = $('#btnEditItem').attr('data-item');
    	    	formData.append('itemplan', itemplan);
    	    	
    		    $.ajax({
			        data: formData,
			        url: "cestplan",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				  .done(function(data) {  
					    data = JSON.parse(data);
				    	if(data.error == 0){
				    		$('#contTabla').html(data.tablaAsigGrafo);			    					
		       	    	    initDataTable('#data-table');
   		       	    	    $('#edi-Item').modal('toggle');  
				    		mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
						}else if(data.error == 1){
							console.log(data.error);
						}
			  	  })
			  	  .fail(function(jqXHR, textStatus, errorThrown) {
			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
			  	  })
			  	  .always(function() {
			      	 
			  	});
    		   
    	    
    	});
        function closeCertificado(){       	      
            $('#modal-cert').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');          
        }
        
        function editEstado(component){
        	var itemplan = $(component).attr('data-itemplan');
        	$.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getInfoItem',
     	    	data	:	{itemplan	:   itemplan},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){
         	    	$('#tituloModal').html('ITEMPLAN : '+itemplan);         	    	
         	    	$('#selectAdelanto').val(data.hasAdelanto).trigger('change');
         	    	$('#inputProyecto').val(data.nombreProyecto);
         	    	$('#inputSubProyecto').val(data.subProyectoDesc);
         	    	$('#inputFecInicio').val(data.fechaInicio);
         	    	$('#inputFecPrev').val(data.fechaPrevEjec);
         	    	$('#inputEECC').val(data.empresaColabDesc);
         	    	$('#btnEditItem').attr('data-item',itemplan);          	    	 
         	    	$('#selectEstaItem').html(data.estadosList);
       	 			$('#selectEstaItem').val('').trigger('chosen:updated');	 
           	 		$('#selectEstaItem').val(data.idEstadoPlan).trigger('change');
               	 	$('#inputFecEjec').val(data.fechaEjec);
                  	$('#inputFecTerm').val(data.fechaCan);
               	 	
             	   	$('#edi-Item').modal('toggle');
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
        
        var encodeRoute = null;
        
        function editPorcentaje(component){        
        	
        	var itemplan = $(component).attr('data-itemplan');
        	var jefatura = $(component).attr('data-jefatura');
        	$.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getEsPor',
     	    	data	:	{itemplan	:   itemplan},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){
         	    	$('#tituloModalPor').html('ITEMPLAN : '+itemplan); 
         	    	$('#contChoice').html(data.htmlEstaciones); 
         	    	var array = JSON.parse(data.listaEstaPor);            	    	    	    	
        	    	$.each(array, function(i, item) {
        	    		
        	    		$('#selectEstacion'+array[i].idEstacion).val('').trigger('chosen:updated');	 
               	 		$('#selectEstacion'+array[i].idEstacion).val(array[i].porcentaje).trigger('change');
                   	    $('#selectEstacion'+array[i].idEstacion).select2({ width: '100%' });
                   	 	//$('#selectEstacion'+array[i].idEstacion).select2();
        	    		
        	    	});   	 	
        	    	$('#btnEditPorcent').attr('data-item',itemplan); 
        	    	$('#editPorcentaje').bootstrapValidator('resetForm', true);  
        	    	if(jefatura=='LIMA'){
            	    	$('#goDetalle').show();
            	    	encodeRoute = data.encode;
            	    	console.log(encodeRoute);
        	    	}else{
        	    		$('#goDetalle').hide();
        	    	}  
             	   	$('#edi-porcentajes').modal('toggle');
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

        function goSinFix(){
            console.log('https://sin-fix.com/app/controlador/ingresar.php?redirect='+encodeRoute);
            window.open('https://sin-fix.com/app/controlador/ingresar.php?redirect='+encodeRoute, '_blank');
        	//window.location.replace('https://sin-fix.com/app/controlador/ingresar.php?redirect='+encodeRoute); 
        }
        
        $('#editPorcentaje')
    	.bootstrapValidator({
    	    container: '#mensajeForm2',
    	    feedbackIcons: {
    	        valid: 'glyphicon glyphicon-ok',
    	        invalid: 'glyphicon glyphicon-remove',
    	        validating: 'glyphicon glyphicon-refresh'
    	    },
    	    excluded: ':disabled'
    	}).on('success.form.bv', function(e) {
    		e.preventDefault();
    		
    		
    	    var $form    = $(e.target),
    	        formData = new FormData(),
    	        params   = $form.serializeArray(),
    	        bv       = $form.data('bootstrapValidator');	 
				
    		    $.each(params, function(i, val) {
    		        formData.append(val.name, val.value);
    		    });
    		    
				var itemplan = $('#btnEditPorcent').attr('data-item');
    	    	formData.append('itemplan', itemplan);
    	    	
    		    $.ajax({
			        data: formData,
			        url: "savePorc",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				  .done(function(data) {					 
					    data = JSON.parse(data);
    			    	if(data.error == 0){				    		
        	       	    	$('#edi-porcentajes').modal('toggle');  
        		    		mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
    					}else if(data.error == 1){
    						mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
    					}
			  	  })
			  	  .fail(function(jqXHR, textStatus, errorThrown) {
			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
			  	  })
			  	  .always(function() {
			      	 
			  	});
    		   
    	    
    	});
        
        function filtrarTabla(){
   	        var subProy = $.trim($('#selectSubProy').val()); 
         	 var eecc = $.trim($('#selectEECC').val());          	
          	var itemplan = $.trim($('#selectItemPlan').val()); 	
           	
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getItemPlanEdit',
     	    	data	:	{subProy  :	subProy,
             	    		eecc      : eecc,             	    	  
              	    	    itemplanFil : itemplan},
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
           
       
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>