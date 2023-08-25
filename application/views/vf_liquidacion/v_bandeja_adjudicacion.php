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
                                    <h2 style="color: #333333d4;font-weight: 800;text-align: center;">BANDEJA DE ADJUDICACION</h2>
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	   				                         
                                                    <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>

                                        <select id="selectSubProy" name="selectSubProy" class="select2" onchange="filtrarTabla()" multiple>
                                        <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaSubProy->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->subProyectoDesc ?>"><?php echo $row->subProyectoDesc ?></option>
                                                 <?php }?>
                                           
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>EECC</label>

                                        <select id="selectEECC" name="selectEECC" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaEECC->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->empresaColabDesc ?>"><?php echo $row->empresaColabDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>ZONAL</label>

                                        <select id="selectZonal" name="selectZonal" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaZonal->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->zonalDesc ?>"><?php echo $row->zonalDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <!-- 
                                 <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>ITEMPLAN</label>

                                        <select id="selectItemPlan" name="selectItemPlan" class="select2" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                             
                                        </select>
                                    </div>
                                </div>
                                -->                           
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
                                <!-- 
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
		   				                    
		   				                    
        <div class="modal fade"id="modalEjec"  tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                   <div class="modal-body">
                    <form id="formAdjudicaItem" method="post" class="form-horizontal">                       
                       
                           <div class="row">
                           <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>

                                        <select id="selectSubAdju" name="selectSubAdju" class="select2 form-control">
                                        <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaSubProy->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
                                                 <?php }?>
                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="control-label">MDF</label>
                                <select id="selectCentral" name="selectCentral" class="select2 form-control">
                                    <option>&nbsp;</option>
                                    <?php foreach ($listacentral->result() as $row) { ?>
                                        <option value="<?php echo $row->idCentral ?>"><?php echo utf8_decode($row->tipoCentralDesc .' - '.$row->codigo) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                               
                               <div class="col-sm-6 col-md-6" id="divEECC">
                                    <div class="form-group">
                                         <label>EECC DISEÑO</label>
                                        <select id="selectEECCDiseno" name="selectEECCDiseno" class="select2 form-control">
                                            <option>&nbsp;</option>                                       
                                                  <?php                                                    
                                                    foreach($listEECCDi->result() as $row){                      
                                                ?> 
                                             <option value="<?php echo $row->idEmpresaColab ?>"><?php echo utf8_decode($row->empresaColabDesc) ?></option>
                                             <?php }?>
                                             
                                        </select>
                                    </div>
                                </div>                             
                                    
                                </div>
                                <br><br>
                                <div id="mensajeForm"></div>  
                                <div class="form-group" style="text-align: right;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                        <button id="btnAdjudica" type="submit" class="btn btn-primary">Adjudicar</button>
                                    </div>
                                </div>
                            
                    </form>
                    </div>
                  
                </div>
            </div>
        </div>
		   			
		   			
		   			
		
		   				                    
		   				                </div>

		   				             
            </section>
        </main>
        
        
        

       

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
        
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>  
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
        <script type="text/javascript">

        $('#formAdjudicaItem')
    	.bootstrapValidator({
    	    container: '#mensajeForm',
    	    feedbackIcons: {
    	        valid: 'glyphicon glyphicon-ok',
    	        invalid: 'glyphicon glyphicon-remove',
    	        validating: 'glyphicon glyphicon-refresh'
    	    },
    	    excluded: ':disabled',
    	    fields: {
    	    	selectSubAdju: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe seleccionar un Subproyecto.</p>'
    	                }
    	             }
     	    	   },
      	    	  selectCentral: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Seleccionar MDF</p>'
        	                }
        	             }
         	    	   },
          	    	  selectEECCDiseno: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar una EECC Diseño.</p>'
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
    		    
				var itemplan = $('#btnAdjudica').attr('data-item');
    	    	formData.append('itemplan', itemplan);
    	    	
    		    $.ajax({
			        data: formData,
			        url: "adjuItem",
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
   		       	    	    $('#modalEjec').modal('toggle');  
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
              
        function adjudicarDiseno(component){       	              	
        	var itemplan = $(component).attr('data-item');

        	
        	$.ajax({
        		type: 'POST',
		        url: "getInfItem",
		        data	:	{item	:   itemplan},
     	    	'async'	:	false
		  	})
			  .done(function(data) {  
				    data = JSON.parse(data);
			    	if(data.error == 0){
				    	
	           	 		$('#selectSubAdju').val(data.subpro).trigger('change');
		           	 	$('#selectEECCDiseno').val(data.empresacolab).trigger('change');
			           	$('#selectCentral').val(data.central).trigger('change');	           	 	
		           	 	
		           	 	$('#tituloModal').html('ITEMPLAN: '+ itemplan);
		                $('#btnAdjudica').attr('data-item',itemplan);    
		                
		                $('#modalEjec').modal('toggle');   
					}else if(data.error == 1){
						console.log(data.error);
					}
		  	  })
        	
        	    
        }

        function filtrarTabla(){
   	        var subProy = $.trim($('#selectSubProy').val()); 
         	 var eecc = $.trim($('#selectEECC').val()); 
         	 var zonal = $.trim($('#selectZonal').val()); 
          	var itemplan = $.trim($('#selectItemPlan').val()); 
           	var mes = $.trim($('#selectMesEjec').val());           
           	var expediente = $.trim($('#selectExpediente').val());    	
           	
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'filBanAdju',
     	    	data	:	{subProy  :	subProy,
             	    		eecc      : eecc,
             	    	    zonal     : zonal,
              	    	    itemplanFil : itemplan,
      	    	            mes : mes,
        	    	        expediente : expediente},
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
        /*
        function aprobarCertificado(component){

        	swal({
                title: 'Está seguro de aprobar el Itemplan con el certificado Actual?',
                text: 'Asegurese de validar la información seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, Aprobar el certificado!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){
            	var subProy = $.trim($('#selectSubProy').val()); 
             	var eecc = $.trim($('#selectEECC').val()); 
             	var zonal = $.trim($('#selectZonal').val()); 
              	var itemplanFil = $.trim($('#selectItemPlan').val()); 
               	var mes = $.trim($('#selectMesEjec').val()); 
               	var expediente = $.trim($('#selectExpediente').val()); 
               	
            	var itemplan = $(component).attr('data-itemplan');   
            	var estacion = $(component).attr('data-estacion');          	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'aprobCert2',
         	    	data	:	{itemplan :  itemplan,
                 	    	     subProy : subProy,
                    	    	 eecc : eecc,
                    	    	 zonal : zonal,
                    	    	 itemplanFil : itemplanFil,
                    	    	 mes : mes,
                    	    	 expediente : expediente,
                    	    	 estacion : estacion},
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){          	 
             	    	//$('#contTabla').html(data.tablaAsigGrafo);   
             	    	$('#contTablaCerti'+estacion).html(data.tabCerti); 
             	    	//initDataTable('#data-table5');	   
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
   */
       
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>