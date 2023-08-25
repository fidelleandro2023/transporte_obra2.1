<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
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
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">

         <style type="text/css">
           
            .select2-dropdown {
              z-index: 100000;
            }
.modal-dialog {
  position: relative;
  width: auto;
  max-width: 600px;
  margin: 10px;
}
.modal-sm {
  max-width: 300px;
}
.modal-lg {
  max-width: 90%;
}
@media (min-width: 768px) {
  .modal-dialog {
    margin: 30px auto;
  }
}
@media (min-width: 320px) {
  .modal-sm {
    margin-right: auto;
    margin-left: auto;
  }
}
@media (min-width: 620px) {
  .modal-dialog {
    margin-right: auto;
    margin-left: auto;
  }
  .modal-lg {
    margin-right: 10px;
    margin-left: 10px;
  }
}
@media (min-width: 920px) {
  .modal-lg {
    margin-right: auto;
    margin-left: auto;
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
                   <a href="https://www.movistar.com.pe/" title="Entel Per�0‡1�0†2"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                                    <h2>TERMINO DE FICHA TECNICA</h2>
		   				                    <div class="card">		   				                        
			                        <div class="card-block">	   				                         
                                        <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>
                                        <select id="selectSubProy" name="selectSubProy" class="select2"  multiple>
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

                                        <select id="selectEECC" name="selectEECC" class="select2" >
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

                                        <select id="selectZonal" name="selectZonal" class="select2" >
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
                                -->
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>SITUACION</label>

                                        <select id="selectSituacion" name="selectSituacion" class="select2" >
                                           <option>&nbsp;</option>
                                           <option value="1">VALIDADO</option>
                                           <option value="0">PENDIENTE</option>
                                        </select>
                                    </div>
                                                                            <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">CONSULTAR</button>

                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>ITEMPLAN</label>
                                        <input id="txtItemPlan" type="text" class="form-control input-mask" placeholder="ItemPlan" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                    </div>
                                </div>
                            </div>
                            <div id="contTabla" class="table-responsive">
	                            <?php echo $tablaAsigGrafo?>
                           </div>
		   				                        </div>
		   				                    </div>		   				                    
		
<!-- -------------------------------------------------------------inicio modal 2------------------------------------------------------------------- -->   				                    
        <div class="modal fade" id="modalEvaluarFicha" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <img style="width: 100px; heigth:40px" src="<?php echo base_url();?>public/img/logo/tdp.png">
                       	 <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">CHECK LIST DE TRABAJOS EN PLANTA EXTERNA</h4>
                       
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div  class="modal-body">
                          <form id="formAuditarFicha" method="post" class="form-horizontal">
                          <div id="contFichaEval">
                          
                          </div>
                          </form>
                    </div>
            </div>
        </div>
	</div>
<!-- --------------------------------------------------------FIN DEL MODAL 2------------------------------------------------------------------------ -->   				                    
	
            </section>
        </main>
        
        
                <div class="modal fade bd-example-modal-lg" id="modalGaleriaFotos" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">EVIDENCIA</h5>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
              <!-- <span aria-hidden="true">&times;</span>
              </button> -->
          </div>
          <div class="modal-body">
                   <div class="container">
                       <h5>FIBRA &Oacute;PTICA<h5>
                        <ul id="list-imageFO" class="list-image">
                        </u>
                                  
                   </div>
                   <div class="container">
                    <h5>FIBRA COAXIAL<h5>
                        <ul id="list-imageCO" class="list-image">
                        </u>    
                    </div>
                   <div class="container">
                    <h5>INS. TROBA<h5>
                        <ul id="list-imageTRO" class="list-image">
                        </u>    
                    </div>
                   <!-- <div class="container">
                        <ul class="list-image">
                        </u>             
                   </div>                -->
            </div>
          <div class="modal-footer">
              <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
       
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/autosize/dist/autosize.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script> 
        <script src="<?php echo base_url();?>public/js/sinfix.js?v=<?php echo time();?>"></script>   
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>        
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        
        
        
        <script type="text/javascript">
        $('#formAuditarFicha')
    	.bootstrapValidator({
    	    container: '#mensajeForm',
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
    		    
				var itemplan = $('#btnAuditarFicha').attr('data-item');
				formData.append('itemplan', itemplan);
    		    var ficha	 = $('#btnAuditarFicha').attr('data-fic');
    	    	formData.append('idFicha', ficha);
                var type     = $('#btnAuditarFicha').attr('data-type');
    	    	formData.append('type', type); 
                if(type == 5) {
                    var observacion_audi = $('#observacion_audi').val();
                    formData.append('observacion_audi', observacion_audi); 
                    var idFichaAudi      = $('#selectTrabajoAuditor option:selected').val();                    
                    formData.append('selectTrabajoAuditor', idFichaAudi); 

                    $.ajax({
                        data: formData,
                        url: "saveAudiOBP",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {  
                        var data	=	JSON.parse(data);
                        if(data.error == 0){   
                            $('#contTabla').html(data.tablaAsigGrafo)
                            initDataTable('#data-table');   
                            console.log('output');          	    	
                            $('#modalEvaluarFicha').modal('toggle');             	    	
                            mostrarNotificacion('success','Operaci&oacute;n Exitosa.', 'Se registro correcamente!');
                        }else if(data.error == 1){     				
                            mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                        }
                    });
                } else {
                    $.ajax({
			        data: formData,
			        url: "saveAudi",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				  .done(function(data) {  
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){   
         	    		$('#contTabla').html(data.tablaAsigGrafo)
         	    	    initDataTable('#data-table');   
         	    	    console.log('output');          	    	
         	    		$('#modalEvaluarFicha').modal('toggle');             	    	
         	    		mostrarNotificacion('success','Operaci&oacute;n Exitosa.', 'Se registro correcamente!');
         	    	}else if(data.error == 1){     				
         				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
         			}
     		    });
            }
    	});
    	
        function filtrarTabla(){
   	        var subProy = $.trim($('#selectSubProy').val()); 
         	var eecc = $.trim($('#selectEECC').val()); 
         	var zonal = $.trim($('#selectZonal').val()); 
          	var situacion = $.trim($('#selectSituacion').val()); 
           	var mes = $.trim($('#selectMesEjec').val());  ;  
           	var itemplan = $.trim($('#txtItemPlan').val());
           	
           	if(subProy=='' && eecc == '' &&  zonal == '' && situacion == '' && mes == '' && itemplan == ''){
           	    alert('Debe realizar almenos 1 Filtro.');
           	}else{
           	    if(situacion == '0' && subProy=='' && eecc == '' &&  zonal == '' && mes == '' && itemplan == ''){
           	    	alert('Debe realizar un filtro adicional.');
           	    }else{
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'filtTermF',
     	    	data	:	{subProy  :	subProy,
             	    		eecc      : eecc,
             	    	    zonal     : zonal,
              	    	    situacion : situacion,
      	    	            mes : mes,
     	    	            itemplan: itemplan
     	    	},
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
           	}
      	}
     
        function viewFichaEval(component){
           // console.log('getFichaToEval');
			var itemplan = $(component).attr('data-itm');
			var type     = $(component).attr('data-type');
			
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'viewTFE',
     	    	data	:	{itemplan : itemplan,
     	    	            type     : type},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){           	    	          	    	   
     	    		$('#contFichaEval').html(data.dataHTML);
     	    	  	$('#modalEvaluarFicha').modal('toggle');
     			}else if(data.error == 1){
     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
     		  });
      	}

        function viewFichaEvalCIOSI(component){
            // console.log('getFichaToEval');
 			var itemplan = $(component).attr('data-itm');
 			var type     = $(component).attr('data-type');
 			
      	    $.ajax({
      	    	type	:	'POST',
      	    	'url'	:	'viewTFESI',
      	    	data	:	{itemplan : itemplan,
      	    	            type     : type},
      	    	'async'	:	false
      	    })
      	    .done(function(data){
      	    	var data	=	JSON.parse(data);
      	    	if(data.error == 0){           	    	          	    	   
      	    		$('#contFichaEval').html(data.dataHTML);
      	    	  	$('#modalEvaluarFicha').modal('toggle');
      			}else if(data.error == 1){
      				
      				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
      			}
      		  });
       	}
       	
       	function openModalOBP(component){
            var itemplan = $(component).attr('data-itm');
            var type     = $(component).attr('data-type');
            
            $.ajax({
                type	:	'POST',
                'url'	:	'openModalOBP',
                data	:	{itemplan : itemplan,
                             type     : type},
                'async'	:	false
            })
            .done(function(data){
                var data	=	JSON.parse(data);
                if(data.error == 0){           	    	          	    	   
                    $('#contFichaEval').html(data.dataHTML);
                    $('#modalEvaluarFicha').modal('toggle');
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
                });
       	}

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>