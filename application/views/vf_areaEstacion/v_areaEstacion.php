<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
       <meta charset="UTF-8">
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
                   <a href="https://www.movistar.com.pe/" title="MOVISTAR Perú"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo MOVISTAR" style="width: 36%; margin-left: -51%"></a>
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
                                    <h2>MANTENIMIENTO ESTACION</h2>
                                    
		   				                    <div class="card">		   				                       
		   				                        <div class="card-block">	
		   				                         <div class="tab-container">
                                                    <ul class="nav nav-tabs nav-fill" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab" href="#estacionArea" role="tab">Estacion & Area</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" href="#estacion" role="tab">Estacion</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" href="#area" role="tab">Area</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content">
                                                          <div class="tab-pane active fade show" id="estacionArea" role="tabpanel">
                                                              <div>
                                                                    <a onclick="AddEstacionArea()" style="background-color: (--verde_telefonica); color: white;" class="btn btn-primary" >AGREGAR ESTACION AREA</a>
                                                                </div>          <!-- addCentral -->    
            		   				                            <div id="contTablaEstacionArea" class="table-responsive table-small">
            								                            <?php echo $listartablaEstacionArea ?>
            	                                                </div>
                                                             </div>
                                                        <div class="tab-pane fade show" id="estacion" role="tabpanel">
                                                          <div>
                                                                <a onclick="AddEstacion()" style="background-color: (--verde_telefonica); color: white;" class="btn btn-primary" >AGREGAR ESTACION</a>
                                                            </div>          <!-- addCentral -->    
        		   				                            <div id="contTablaEstacion" class="table-responsive table-small">
        								                            <?php echo $listartablaEstacion ?>
        	                                                </div>
                                                         </div>
                                                         <div class="tab-pane fade show" id="area" role="tabpanel">
                                                          <div>
                                                                <a onclick="AddArea()" style="background-color: (--verde_telefonica); color: white;" class="btn btn-primary" >AGREGAR AREA</a>
                                                            </div>          <!-- addCentral -->    
        		   				                            <div id="contTablaArea" class="table-responsive table-small">
        								                            <?php echo $listartablaArea ?>
        	                                                </div>
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

        <!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>
        
        <div class="modal fade" id="modalRegistrarEstacion">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">REGISTRAR ESTACION</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddEstacion" method="post" class="form-horizontal">  <!--formAddCentral -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                	<div class="form-group">
                                         <label>Estacion</label>
                                         <input id="inputEstacion" name="inputEstacion" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
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
        
        
           <div class="modal fade" id="modalRegistrarArea">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">REGISTRAR Area</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddArea" method="post" class="form-horizontal">  <!--formAddCentral -->
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                	<div class="form-group">
                                         <label>Area</label>
                                         <input id="inputArea" name="inputArea" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                        <i class="form-group__bar"></i>
                                    </div>                                      
                                </div>
                                <div class="col-sm-6 col-md-6">
                                	<div class="form-group">
                                         <label>Tipo Area</label>
                                         <select id="selectTipoArea" name="selectTipoArea" class="select2 form-control" >
                                            <option>&nbsp;</option>
                                              <?php                                                    
                                                foreach($selectTipoArea->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->tipoArea ?>"><?php echo $row->tipoArea ?></option>
                                                 <?php }?>
                                             
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <div id="mensajeFormArea"></div>  
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

        <div class="modal fade" id="modalRegistrarEstacionArea">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">REGISTRAR Area</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddEstacionArea" method="post" class="form-horizontal">  <!--formAddCentral -->
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                	<div class="form-group">
                                         <label>Estacion</label>
                                         <select id="selectEstacion" name="selectEstacion" class="select2 form-control" ">
                                            <option>&nbsp;</option>
                                              <?php                                                    
                                                foreach($selectEstacion->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->idEstacion ?>"><?php echo $row->estacionDesc ?></option>
                                                 <?php }?>
                                             
                                        </select>
                                    </div>                                   
                                </div>
                                <div class="col-sm-6 col-md-6">
                                	<div class="form-group">
                                         <label>Area</label>
                                         <select id="selectArea" name="selectArea" class="select2 form-control" ">
                                            <option>&nbsp;</option>
                                              <?php                                                    
                                                foreach($selectArea->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->idArea ?>"><?php echo $row->areaDesc ?></option>
                                                 <?php }?>
                                             
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <div id="mensajeFormEstacionArea"></div>  
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



    <!--EDITAR ESTACION-->

       <div class="modal fade" id="modalEditarEstacion"> <!-- el id="modalEditarCentral"-->
           <div class="modal-dialog modal-sm">
               <div class="modal-content">
                        <div class="modal-header" style="margin: auto;">
                            <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR ESTACION</h5>
                        </div>
                   <div class="modal-body">
                        <form id="formEditEstacion" method="post" class="form-horizontal"> <!-- formEditCentral -->
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">


                                            <div id="contInputEstacion" class="form-group has-feedback" style="">
                                                <label>ESTACION</label>
                                                <input id="inputEstacion2" name="inputEstacion2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="InputEstacion" style="display: none;"></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                    </div>
                                </div>
                            <div id="mensajeFormEstacion"></div>
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button id="btnEditEstacion" type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
                   </div>
               </div>
           </div>
       </div>


        <!--EDITAR AREA-->

        <div class="modal fade" id="modalEditarAREA"> <!-- el id="modalEditarCentral"-->
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR AREA</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formEditArea" method="post" class="form-horizontal"> <!-- formEditCentral -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12">


                                        <div id="contInputArea" class="form-group has-feedback" style="">
                                            <label>AREA</label>
                                            <input id="inputArea2" name="inputArea2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="InputArea" style="display: none;"></i>
                                            <i class="form-group__bar"></i>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">TIPO AREA</label>
                                            <select id="selectTipoArea2" name="selectTipoArea2" class="select2 form-control">
                                                <option>&nbsp;</option>
                                                <?php
                                                foreach($valorModalTipoArea->result() as $row){
                                                    ?>
                                                    <option value="<?php echo $row->tipoArea ?>"><?php echo $row->tipoArea ?></option>
                                                <?php }?>

                                            </select>
                                        </div>

                                </div>
                            </div>
                            <div id="mensajeFormArea"></div>
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                    <button id="btnEditArea" type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--EDITAR ESTACIONAREA-->



        <div class="modal fade" id="modalEditarEstacionArea"> <!-- el id="modalEditarCentral"-->
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR ESTACION AREA</h5>
                    </div>
                    <div class="modal-body">
                        <form id="formEditEstacionAREA" method="post" class="form-horizontal"> <!-- formEditCentral -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12">

                                    <div class="form-group">
                                        <label class="control-label">ESTACION</label>
                                        <select id="selectEstacion3" name="selectEstacion3" class="select2 form-control">
                                            <option>&nbsp;</option>
                                            <?php
                                             foreach($valorModalEstacion->result() as $row){
                                                ?>
                                                <option value="<?php echo $row->idEstacion ?>"><?php echo $row->estacionDesc ?></option>
                                            <?php }?>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">AREA</label>
                                        <select id="selectArea3" name="selectArea3" class="select2 form-control">
                                            <option>&nbsp;</option>
                                            <?php
                                            foreach($valorModalArea->result() as $row){
                                                ?>
                                                <option value="<?php echo $row->idArea ?>"><?php echo $row->areaDesc ?></option>
                                            <?php }?>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="mensajeFormEstacionArea"></div>
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                    <button id="btnEditEstacionArea" type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Javascript -->
        <!-- vendors -->
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>        
     
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
     
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>


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
        $("document").ready(function(){   // SIRVE PARA PONER LA BARRA DE BUSCADOR
        	initDataTable('#data-table2');
        	initDataTable('#data-table3');

       	 $('#formAddEstacion') //COMIENZA AGREGAR ESTACION
      	.bootstrapValidator({
      	    container: '#mensajeForm',
      	    feedbackIcons: {
      	        valid: 'glyphicon glyphicon-ok',
      	        invalid: 'glyphicon glyphicon-remove',
      	        validating: 'glyphicon glyphicon-refresh'
      	    },
      	    excluded: ':disabled',
      	    fields: {       	    	
      	    	
      	    	inputEstacion: {
      	            validators: {
      	                notEmpty: {
      	                    message: '<p style="color:red">(*) Debe Ingresar una Estacion.</p>'
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
  			        url: "AddEstacion", //addCentral
  			        cache: false,
  		            contentType: false,
  		            processData: false,
  		            type: 'POST'
  			  	})
  				  .done(function(data) {  
  					    	data = JSON.parse(data);
  				    	if(data.error == 0){    				    						    		
  				    		$('#contTablaEstacion').html(data.listartablaEstacion);    				    					
  		       	    	    initDataTable('#data-table2');
  				    		$('#modalRegistrarEstacion').modal('toggle');
  				    		mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
  						}else if(data.error == 1){
  							mostrarNotificacion('error','Error','No se inserto la estacion');
  						}
  			  	  })
  			  	  .fail(function(jqXHR, textStatus, errorThrown) {
  			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
  			  	  })
  			  	  .always(function() {
  			      	 
  			  	});
      		   
      	    
      	});
        	/* Insertar Area  */

        	$('#formAddArea') //formAddCentral
          	.bootstrapValidator({
          	    container: '#mensajeFormArea',
          	    feedbackIcons: {
          	        valid: 'glyphicon glyphicon-ok',
          	        invalid: 'glyphicon glyphicon-remove',
          	        validating: 'glyphicon glyphicon-refresh'
          	    },
          	    excluded: ':disabled',
          	    fields: {       	    	
          	    	
          	    	inputArea: {
          	            validators: {
          	                notEmpty: {
          	                    message: '<p style="color:red">(*) Debe Ingresar una Area.</p>'
          	                },
              	             callback: {
                	               message: '<p style="color:red">(*) El Codigo ya se encuentra Registrado.</p>',
              	                    callback: function(value, validator){                  	                                
                  	                    	result = existeCodigo2(value);
                  	                        if(result == '1'){//Existe
                  		                        return false;
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
      			        url: "AddArea", //addCentral
      			        cache: false,
      		            contentType: false,
      		            processData: false,
      		            type: 'POST'
      			  	})
      				  .done(function(data) {  
      					    	data = JSON.parse(data);
      				    	if(data.error == 0){    				    						    		
      				    		$('#contTablaArea').html(data.listartablaArea);    				    					
      		       	    	    initDataTable('#data-table3');
      				    		$('#modalRegistrarArea').modal('toggle');
      				    		mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
      						}else if(data.error == 1){
      							mostrarNotificacion('error','Error','No se inserto la estacion');
      						}
      			  	  })
      			  	  .fail(function(jqXHR, textStatus, errorThrown) {
      			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
      			  	  })
      			  	  .always(function() {
      			      	 
      			  	});
          		   
          	    
          	});



        	/* Insertar Estacion Area  */

        	$('#formAddEstacionArea') //
          	.bootstrapValidator({
          	    container: '#mensajeFormEstacionArea',
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
          		    
          		    $.ajax({
      			        data: formData,
      			        url: "AddEstacionArea", //addCentral
      			        cache: false,
      		            contentType: false,
      		            processData: false,
      		            type: 'POST'
      			  	})
      				  .done(function(data) {  
      					    	data = JSON.parse(data);
      				    	if(data.error == 0){    				    						    		
      				    		$('#contTablaEstacionArea').html(data.listartablaEstacionArea);    				    					
      		       	    	    initDataTable('#data-table');
      				    		$('#modalRegistrarEstacionArea').modal('toggle');
      				    		mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
      						}else if(data.error == 1){
      							mostrarNotificacion('error','Error','No se inserto la estacion area');
      						}
      			  	  })
      			  	  .fail(function(jqXHR, textStatus, errorThrown) {
      			  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
      			  	  })
      			  	  .always(function() {
      			      	 
      			  	});
          		   
          	    
          	});
        });





        function existeCodigo(codigo){
            var result = $.ajax({
                type : "POST",
                'url' : 'validCodestacion',
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
                'url' : 'validCodArea',
                data : {
                    'codigo' : codigo
                },
                'async' : false
            }).responseText;
            return result;
        }
        function AddEstacion(){ //addCentral

            $('#formAddEstacion').bootstrapValidator('resetForm', true);   //formAddCentral
            $('#modalRegistrarEstacion').modal('toggle'); //abrirl modal
        }

        function AddEstacionArea(){ //addCentral

            $('#formAddEstacionArea').bootstrapValidator('resetForm', true);   //formAddCentral
            $('#modalRegistrarEstacionArea').modal('toggle'); //abrirl modal
        }

        function AddArea(){ //addCentral

            $('#formAddArea').bootstrapValidator('resetForm', true);   //formAddCentral
            $('#modalRegistrarArea').modal('toggle'); //abrirl modal
        }










        //EDITAR ESTACION
        ///////////////////////////////
        // /////////////////////////////
        ///////////////////////////////
        
         var codigoEdit = null;

            function editEstacion(component){ // el edit q va aca es el edit del boton

                var id = $(component).attr('data-id_estacion'); // ESTA MANDANDO COMPONENTE

                  $.ajax({
                    type	:	'POST',
                    'url'	:	'getInfoEsta',//getInfoCen
                    data	:	{ id : id },
                    'async'	:	false
                }).done(function(data){
                    var data = JSON.parse(data);
                     $('#formEditEstacion').bootstrapValidator('resetForm', true); //formEditCentral
                     $('#inputEstacion2').val(data.estacion);
                     $('#btnEditEstacion').attr('data-id',id);
                     $('#modalEditarEstacion').modal('toggle'); //abrirl modal
                })

            }

        $('#formEditEstacion')// formEditCentral
            .bootstrapValidator({
                container: '#mensajeFormEstacion',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {

                    inputEstacion: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar un estacion</p>'
                            },
                            callback: {
                                message: '<p style="color:red">(*) La Estacion ya se encuentra Registrado.</p>',
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
                }
            }).on('success.form.bv', function(e) {
            e.preventDefault();

            var $form    = $(e.target),
                formData = new FormData(),
                params   = $form.serializeArray(),
                bv       = $form.data('bootstrapValidator');

            var id = $('#btnEditEstacion').attr('data-id');
            formData.append('id', id);

            $.each(params, function(i, val) {
                formData.append(val.name, val.value);
            });

            $.ajax({
                data: formData,
                url: "editEstacion", //editCentral
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
                .done(function(data) {
                    data = JSON.parse(data);
                    if(data.error == 0){
                        $('#contTablaEstacion').html(data.listartablaEstacion);
                        initDataTable('#data-table2');
                        $('#modalEditarEstacion').modal('toggle');
                        mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
                    }else if(data.error == 1){
                        mostrarNotificacion('error','Error','No se inserto la Estacion');
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
                })
                .always(function() {

                });


        });


      ////////////// TERMINA EDITAR ESTACION











        //EDITAR AREA
        ///////////////////////////////
        ///////////////////////////////
        ///////////////////////////////


        var codigoEdita = null;

        function editArea(component){ // el edit q va aca es el edit del boton

            var id = $(component).attr('data-id_Area'); // ESTA MANDANDO COMPONENTE

            $.ajax({
                type	:	'POST',
                'url'	:	'getInfoArea',//getInfoCen
                data	:	{ id : id },
                'async'	:	false
            }).done(function(data){
                var data = JSON.parse(data);
                codigoEdit = data.codigo;

                $('#formEditArea').bootstrapValidator('resetForm', true); //formEditCentral
                $('#inputArea2').val(data.area);
                $('#selectTipoArea2').val(data.tipoArea).trigger('change');
                $('#btnEditArea').attr('data-id',id);
                $('#modalEditarAREA').modal('toggle'); //abrirl modal
            })

        }


        $('#formEditArea')// formEditCentral
            .bootstrapValidator({
                container: '#mensajeFormArea',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {

                    inputArea: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar un estacion</p>'
                            },
                            callback: {
                                message: '<p style="color:red">(*) La Estacion ya se encuentra Registrado.</p>',
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
                    selectTipoArea: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar Tipo Area.</p>'
                            }
                        }
                    },

                }
            }).on('success.form.bv', function(e) {
            e.preventDefault();

            var $form    = $(e.target),
                formData = new FormData(),
                params   = $form.serializeArray(),
                bv       = $form.data('bootstrapValidator');

            var id = $('#btnEditArea').attr('data-id');
            formData.append('id', id);

            $.each(params, function(i, val) {
                formData.append(val.name, val.value);
            });

            $.ajax({
                data: formData,
                url: "editArea", //editCentral
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
                .done(function(data) {
                    data = JSON.parse(data);
                    if(data.error == 0){
                        $('#contTablaArea').html(data.listarTablaArea);
                        initDataTable('#data-table3');
                        $('#modalEditarAREA').modal('toggle');
                        mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
                    }else if(data.error == 1){
                        mostrarNotificacion('error','Error','No se inserto la central');
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
                })
                .always(function() {

                });


        });




        ////////////// TERMINA EDITAR AREA  PARTE 1

        //EDITAR ESTACIONAREA
        //////////////////////////////////////////////////////////////
        ///////////////////////////////
        ///////////////////////////////



        var codigoEdit = null;

        function editEstacionArea(component){ // el edit q va aca es el edit del boton

            var id = $(component).attr('data-id_estacionArea'); // ESTA MANDANDO COMPONENTE

            $.ajax({
                type	:	'POST',
                'url'	:	'getInfoEstacionArea',//getInfoCen
                data	:	{ id : id },
                'async'	:	false
            }).done(function(data){
                var data = JSON.parse(data);
                codigoEdit = data.codigo;

                $('#formEditEstacionArea').bootstrapValidator('resetForm', true); //formEditCentral
                $('#selectEstacion3').val(data.idEstacion).trigger("change");
                $('#selectArea3').val(data.idArea).trigger("change");
                $('#btnEditEstacionArea').attr('data-id',id);
                $('#modalEditarEstacionArea').modal('toggle'); //abrirl modal
            })

        }

        $('#formEditEstacionAREA')// formEditCentral
            .bootstrapValidator({
                container: '#mensajeFormEstacionArea',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {

                    selectEstacion3: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar Tipo Estacion.</p>'
                            }
                        }
                    },
                    selectArea3: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar Tipo Area.</p>'
                            }
                        }
                    },
                }
            }).on('success.form.bv', function(e) {
            e.preventDefault();

            var $form    = $(e.target),
                formData = new FormData(),
                params   = $form.serializeArray(),
                bv       = $form.data('bootstrapValidator');

            var id = $('#btnEditEstacionArea').attr('data-id');
            formData.append('id', id);

            $.each(params, function(i, val) {
                formData.append(val.name, val.value);
            });

            $.ajax({
                data: formData,
                url: "editEstacionArea", //editCentral
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
                .done(function(data) {
                    data = JSON.parse(data);
                    if(data.error == 0){
                        $('#contTablaEstacionArea').html(data.listarTablaEstacionArea);
                        initDataTable('#data-table');
                        $('#modalEditarEstacionArea').modal('toggle');
                        mostrarNotificacion('success','Operación éxitosa.', 'Se registro correcamente!');
                    }else if(data.error == 1){
                        mostrarNotificacion('error','Error','No se inserto la central');
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
                })
                .always(function() {

                });


        });



        ////////////// TERMINA EDITAR ESTACIONAREA  PARTE 1




           
      
            
      

     </script>
    </body>


</html>