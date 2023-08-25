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
                <ul class="top-nav">

                    <li class="hidden-xs-down">
                        <a href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="zmdi zmdi-power"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">

						                            <a href="logOut" class="dropdown-item">Cerrar Sesi&oacute;n</a>
                        </div>
                    </li>
                </ul>
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
            	 <div id='textMensaje'>
                   
                </div>
           
		                   <div class="content__inner">
                                    <h2>REGISTRO PLAN OBRA - TRANSPORTE</h2>                                    
		   				                    <div class="card">		   				                        
		   				                        <div class="card-block">
													
    		   				                        <div>
                                                        <a onclick="addPlanobra()" style="background-color: #0154a0; color: white;" class="btn btn-primary" >AGREGAR PLAN OBRA</a>
                                                    </div>              
													<!--
                                                   <p style="font-weight: bolder;    font-size: large;    text-align: center;">Plan de Obra 2019 concluida, coordinar con el administrador.</p>
                                                     -->                                              
		   				                        </div>
		   				                    </div>
		   				                </div>
			                <footer class="footer hidden-xs-down">
			                    <p>Telefonica del Peru.</p>
		                   </footer>
            </section>
        </main>

        <!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>
        
        <div class="modal fade" id="modalRegistrarPlanobra">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">REGISTRAR PLAN OBRA</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddPlanobra" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                         <label>PROYECTO</label>
                                                <select id="selectProy" name="selectProy" class="select2 form-control" onchange="changueProyect()" >
                                                    <option>&nbsp;</option>
                                                      <?php foreach($listaProy->result() as $row){ ?> 
                                                        <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>
                                   
                                   <div class="form-group">
                                         <label>CENTRAL</label>
                                                <select id="selectCentral" name="selectCentral" class="select2 form-control" onchange="changueCentral();changueEECC()">
                                                       <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>
                                  
                                   
                                    <div class="form-group">
                                         <label>EMPRESA COLABORADORA</label>
                                                <select id="selectEmpresaColab" name="selectEmpresaColab" class="select2 form-control">
                                                                                                                                                           
                                                </select>
                                    </div>
                                    


                                    <div class="form-group" style="display:none">
                                         <label>FASE</label>
                                                <input id="inputFase" name="inputFase" type="text" class="form-control" readonly="true">
                                               
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                     <div class="form-group">
                                         <label>SUBPROYECTO</label>
                                                <select id="selectSubproy" name="selectSubproy" class="select2 form-control">
                                                                                                        
                                                </select>
                                    </div>
                                      <div class="form-group">
                                         <label>ZONAL</label>
                                                <select id="selectZonal" name="selectZonal" class="select2 form-control" >
                                                                                                        
                                                </select>
                                    </div>

                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>LUGAR</label>
                                        <input id="inputIndicador" name="inputIndicador" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                        <i class="form-group__bar"></i>
                                    </div>


                                    
                                    
                                    <div class="form-group" style="display:none">
                                         <label>EMPRESA ELECTRICA</label>
                                                <select id="selectEmpresaEle" name="selectEmpresaEle" class="select2 form-control" >
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaeelec->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idEmpresaElec ?>"><?php echo $row->empresaElecDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>               
                                </div>


                                <div class="col-sm-12 col-md-12">

                                 <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>NOMBRE DEL PLAN</label>
                                        <input id="inputNombrePlan" name="inputNombrePlan" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                        <i class="form-group__bar"></i>
                                    </div>

                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>FECHA DE INICIO</label>
    
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                            <div class="form-group">
                                                <input id="inputFechaInicio" name="inputFechaInicio" type="text" class="form-control date-picker" placeholder="Pick a date" onchange="recalcular_fecha_prev_ejec()">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>ITEMPLAN PLANTA EXTERNA</label>
                                        <input id="inputItemPlanPE" name="inputItemPlanPE" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                        <i class="form-group__bar"></i>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>FECHA PREV.EJECUCION</label>
                                        <input id="inputFechaPrev" name="inputFechaPrev" type="text" class="form-control" readonly>
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
        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script type="text/javascript">

        /*actualizacion dinamica de combobox*/
        /*actualizacion de subproyecto a partir del proyecto*/
         var IDSUB = null;
         var itemP =null;

     
         $('#inputIndicador, #selectCentral').bind('keypress blur', function() {
        
    $('#inputNombrePlan').val($('#inputIndicador').val() + ' - ' +
                    $('#selectCentral option:selected').text() );
});


        function changueProyect(){
            var proyecto = $.trim($('#selectProy').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'getSubProPI',
                data    :   {proyecto  : proyecto},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){       
    
                    $('#selectSubproy').html(data.listaSubProy);
                    $('#selectSubproy').val('').trigger('chosen:updated');
                    
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }

        
        function recalcular_fecha_prev_ejec(){
            var subproy = $.trim($('#selectSubproy').val()); 
           
            if(subproy==undefined || subproy=='undefined' || subproy==''){
                $('#inputFechaPrev').val('');
                return false;
            }

            var inputFechaInicio = $.trim($('#inputFechaInicio').val()); 
            
            if(inputFechaInicio==undefined || inputFechaInicio=='undefined' || inputFechaInicio==''){
                $('#inputFechaPrev').val('');
                return false;
            }

            $.ajax({
                type    :   'POST',
                'url'   :   'getFechaSubproPI',
                data    :   { fecha  : inputFechaInicio,
                              subproyecto  : subproy
                            },
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){       
    
                 $('#inputFechaPrev').val(data.fechaCalculado);
                 $('#inputFase').val(data.anioFase);
                    
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al obtener la fecha de prevista!');
                }
            });
        }



        function changueCentral(){
            var central = $.trim($('#selectCentral').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'getZonalPI',
                data    :   {central  : central},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){ 
                    $('#inputNombrePlan').val('');      
                    $('#inputNombrePlan').val($('#selectCentral option:selected').text());
                    $('#selectZonal').html(data.listaZonal);
                    $('#selectZonal').val(data.idZonalSelec).trigger('chosen:updated');
                   
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }
     

        function changueEECC(){
            var central = $.trim($('#selectCentral').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'getEECCPI',
                data    :   {central  : central},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){       
    
                    $('#selectEmpresaColab').html(data.listaEECC);

                    $('#selectEmpresaColab').val(data.idEECCSelec).trigger('chosen:updated');
                    
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }


              

      
            function addPlanobra(){
                
                /*habilitacion campos de creacion*/
                $('#selectProy').val('').trigger('change');
           	    $('#selectSubproy').val('').trigger('change');
                $('#selectCentral').val('').trigger('change');
                $('#selectZonal').val('').trigger('change'); 
                $('#selectEmpresaColab').val('').trigger('change'); 
                $('#selectEmpresaEle').val('6').trigger('change');
                $('#inputFase').val('');
                $('#inputIndicador').val('');
                $('#inputFechaInicio').val('');
                $('#inputFechaPrev').val('');
                $('#inputNombrePlan').val('');
               
                $('#inputItemPlanPE').val('');
                
                /**/
           	    $('#formAddPlanobra').bootstrapValidator('resetForm', true); 
            	$('#modalRegistrarPlanobra').modal('toggle'); //abrirl modal        	
            }

            
            $('#formAddPlanobra')
        	.bootstrapValidator({
        	    container: '#mensajeForm',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
                    selectProy: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un proyecto.</p>'
                            }
                        }
                    },
                     selectSubproy: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un subproyecto.</p>'
                            }
                        }
                    },
                    selectCentral: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una central.</p>'
                            }
                        }
                    },
                    selectZonal: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una zonal.</p>'
                            }
                        }
                    },
                    selectEmpresaColab: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una empresa colaboradora.</p>'
                            }
                        }
                    },
                                       
          	    	inputNombrePlan: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el titulo del plan.</p>'
                            }
                        }
                    },
                    inputIndicador: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el indicador.</p>'
                            }
                        }
                    },
        	    	inputFechaInicio: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe ingresar la fecha de inicio del plan.</p>'
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
        	       
                    itemplanPE= $.trim($('#inputItemPlanPE').val()); 


                    if(itemplanPE!=""){
                        dato=existeItemPlan(itemplanPE);
                       
                        if (dato!=1){
                            $('#mensajeForm').html('<p style="color:red">(*) El itemplan ingresado no existe.</p>');
                            return false;
                        }

                    }

        		    $.each(params, function(i, val) {
        		        formData.append(val.name, val.value);
        		    });
        		    
        		    $.ajax({
    			        data: formData,
    			        url: "registroItemplanTransporte",
    			        cache: false,
    		            contentType: false,
    		            processData: false,
    		            type: 'POST'
    			  	})
    				  .done(function(data) {  
    					    	data = JSON.parse(data);
    				    	if(data.error == 0){    				    						    		
    				    		//$('#contTabla').html(data.listartabla);    				    					
    		       	    	    //initDataTable('#data-table');
    				    		$('#modalRegistrarPlanobra').modal('toggle');
                                var itemplan=data.itemplannuevo;
                                
                                $('#textMensaje').html(data.notify);


    				    		mostrarNotificacion('success','Operaci&oacute;n exitosa.', 'Se registro el nuevo plan de obra con n&uacute;mero '+itemplan+' correctamente!');
    						}else if(data.error == 1){
    							mostrarNotificacion('error','Error','No se inserto el Plan de obra');
    						}
    			  	  })
    			  	  .fail(function(jqXHR, textStatus, errorThrown) {
    			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
    			  	  })
    			  	  .always(function() {
    			      	 
    			  	});
        		   
        	    
        	});
      
                   
 function existeItemPlan(itemplan){
                var result = $.ajax({
                    type : "POST",
                    'url' : 'getItemPlanSearch',
                    data : {
                        'itemplanPE' : itemplan
                    },
                    'async' : false
                }).responseText;
           
                return result;
            }
      
        </script>    
        
       
        
    </body>


</html>