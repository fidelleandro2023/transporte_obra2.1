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
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <style>
            @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
              }
            }
            .select2-dropdown {
                z-index: 100000;
            }
        </style>
    </head>

    <body data-ma-theme="entel">
        <main class="main">
            <section class="content content--full" style="MARGIN-TOP: -70px;">
           
                <div class="content__inner">
                    <h2>REGISTRO DE MATERIALES / ITEMPLAN: <?php echo $itemplan?> - ESTACION : <?php echo $estacionDesc?></h2>                       
                <div class="card">
                        
                    <div class="card-block">	
                        <?php if($existReg  ==  0){?>
    				 <div class="row">
                        <div class="form-group col-md-6" style="text-align: left;">                        
                            <label style="color: red" class="control-label mb-10 text-left">1) Descargue el archivo materiales.xls y llenar la columna "CANTIDAD" </label><br>
                            <input type="button" id="btnExportFormato" value="Exportar Materiales" class="btn-success" onclick="exportMateriales()"><br>
                        </div>
                        <div class="form-group col-md-6" style="text-align: left;">
                            <label style="color: red" class="control-label mb-10 text-left">2) Cargar el archivo materiales.xls y darle Click a "Procesar Materiales".</label><br>
            		        <input type="button" id="btnExportFormato" value="Procesar Materiales" class="btn-success" onclick="procesarFile(this)">
            		        <input id="fileTable" name="fileTable" type="file" class="file" data-show-preview="false">      					          		                           
                        </div>  
                        <div class="form-group col-md-6" style="text-align: left;">            
                            <label style="color: red" class="control-label mb-10 text-left">3) Subir Documento PDF (Evidencia).</label><br>            
                            <input accept="application/pdf" id="fileEvidencia" name="fileEvidencia" type="file" class="file" data-show-preview="false">                           
                        </div>  
                             
                        <div class="form-group col-md-6" style="text-align: left;">                        
                            <label style="color: red" class="control-label mb-10 text-left">4) De estar conforme, Click a "Registrar Materiales".</label><br>
                            <input type="button" data-itm="<?php echo $itemplan?>" data-esta="<?php echo $idEstacion?>" data-po="<?php echo $codigo_po?>" id="preLoadFile" value="Registrar Materiales" class="btn-success">
                        </div>      
                    </div>
    			
                     <div class="form-group col-sm-12 table-responsive">       
                    	<table style="font-size: 10px" id="data-table2" class="table table-bordered">
                        	<thead class="thead-default">                        
                               <tr role="row">
                                    <th colspan="1"></th> 
                                    <th colspan="1">TIPO MATERIAL</th>
                                    <th colspan="1">CODIGO</th> 
                                    <th colspan="1">DESCRIPCION</th>  
                                    <th colspan="1">COSTO</th>          
                                    <th colspan="1">CANTIDAD INGRESADA</th> 
                                    <th colspan="1">TOTAL</th>
                                    <th colspan="1">SITUACION</th>                      
                                </tr>
                            </thead>  
                            <tfoot>
                                <tr>
                                	<th colspan="1"></th>
                                	<th colspan="1">TIPO MATERIAL</th>  
                                    <th colspan="1">CODIGO</th> 
                                    <th colspan="1">DESCRIPCION</th>    
                                    <th colspan="1">COSTO</th>      
                                    <th colspan="1">CANTIDAD INGRESADA</th> 
                                    <th colspan="1">TOTAL</th>    
                                    <th colspan="1">SITUACION</th>                                       
                                </tr>
                            </tfoot>                  
                        <tbody id="contBodyTable">
                        	
                         </tbody>
                    	</table>
                    </div>
                            <div class="form-group col-md-11" style="text-align: right;">                        
                                <label style="font-weight: bold;">TOTAL: S/.</label>
                                <label style="font-weight: bold;" id="montoTotal">0.00</label>
                            </div>
                            <?php }else{ ?> 
                            <div class="row">
                            <br>
                                <div class="form-group col-md-12" style="text-align: center;">   
                                <br>
                                <label style="color: red; font-size: x-large; " class="control-label mb-10 text-left">La estacion ya cuenta con Materiales Registrados.</label>
                                </div>
                            </div>
                           <?php  } ?>
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

    			                <footer class="footer hidden-xs-down">
    			                    <p>Telefónica Del Perú</p>   				                  
    		                   </footer>
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
        
        <script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        
        <script type="text/javascript">

        function exportMateriales(){
            $.ajax({
            	type	:	'POST',
     	    	'url'	:	'exParMatPqt',
     	    	'async'	:	false
            }).done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    location.href = data.rutaExcel;
                }else{
                    mostrarNotificacion('warning', 'Aviso', data.msj);
                }
            })

        }
        
        var listaFileTemp = null;
        var listaFileValido = null;
        
        function procesarFile(component){
            
        	var itemplan   = $(component).attr('data-itm'); 
        	var idEstacion = $(component).attr('data-esta');
            var comprobar = $('#fileTable').val().length;
    		
    		if(comprobar>0){

    			    var file = $('#fileTable').val()			
    			    console.log($('#fileTable').val().length);
    			    var ext = file.substring(file.lastIndexOf("."));
    			    console.log(ext);
    			    if(ext != ".xls" && ext != ".xlsx")
    			    {
    			    	alert('Formato de archivo no v\u00E1lido. El formato correcto es XLS o XLSX');
    			        return false;
    			    }
    			    else{
    			        
    			    	var input = document.getElementById('fileTable');
    		            var file = input.files[0];
    		            var form = new FormData();
    		            form.append('file', file);
    		            console.log('Procesando...');
    		            
    		            $.ajax({
    		                url : "upFiPoMatPqt",
    		                type: "POST",
    		                cache: false,
    		                contentType: false,
    		                processData: false,
    		                data : form,
    		                success: function(response){
    		                    var data = JSON.parse(response);
    		                    if(data.error == 0){  
    		         	    		$('#contBodyTable').html(data.tablaData);      	
    		         	    		var infoFile = JSON.parse(data.jsonDataFIle);
    		         	    		listaFileTemp = infoFile;   
    		         	    		var fileValido =  	JSON.parse(data.jsonDataFIleValido);
    		         	    		listaFileValido   =   fileValido;         
    		         	    		$('#montoTotal').html(data.total_final);	 
    		         	        	$('#modal-parti-adic').css('overflow-y', 'scroll'); 
    		         	    		    		         	    		//console.log(listaFileValido);
    		         	    		//$('#contSubirInfo').show();
    		         	    	}else if(data.error == 1){     				
    		         	    		alert(data.msj);
    		         			}
    		                    
    		                }
    		            });
    		           
    			    	
    			    }        	
    		}else{
        		alert('Debe subir un archivo a procesar.');
    		}
        }




        function removeTR(component){

        	var indice = $(component).attr('data-indice');
        	var indice_val = $(component).attr('data-indice_val');
            if(indice_val!=null){            	
            	delete listaFileValido[indice_val];                   
            }           
        	$('#tr'+indice).remove();
        	//listaFileTemp.splice(indice, 1);
        	delete listaFileTemp[indice];
        	//$('#data-table').DataTable().row( $(component).parents('tr') ).remove().draw();
        	console.log(listaFileValido);
        }

        $('#preLoadFile').click(function(e){

                var input2File = document.getElementById('fileEvidencia');
                var file2 = input2File.files[0];
                
                if(listaFileValido==null || listaFileValido.length==0){
              	  alert('No hay datos validos para actualizar, ingrese otro archivo');
                  return false;
                }
                
                if(file2 == null){
                    alert('Debe Subir un Documento de Formato .PDF como Evidencia');
                	return false;
               	}
                /*
                //poner limite de subida...
                var nameFile = file2['name'];
                var ext = nameFile.substring(nameFile.lastIndexOf(".")).toUpperCase();
                console.log(ext);
                if(ext  !=  '.PDF'){
                    alert('El formato del documento de evidencia debe ser ".PDF"');
                	return false;
                }
                */   
               	
            	swal({
                    title: 'Está seguro registrar los Materiales?',
                    text: 'Asegurese de que la informacion llenada sea la correta.',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, guardar los datos!',
                    cancelButtonClass: 'btn btn-secondary',
                    allowOutsideClick: false
                }).then(function(){

                	console.log('registrando materiales...');

             	    var itemplan   = $('#preLoadFile').attr('data-itm'); 
                	var idEstacion = $('#preLoadFile').attr('data-esta');
                	var codigo_po  = $('#preLoadFile').attr('data-po');
                	var jsonDataFile = listaFileValido;
                    var form_data = new FormData();  
                    form_data.append('fileEvi', file2);
                    form_data.append('item', itemplan);
                    form_data.append('idEstacion', idEstacion);
                    form_data.append('codigo_po', codigo_po);
                    form_data.append('jsonDataFile', JSON.stringify(jsonDataFile));
                    $.ajax({
                        type: 'POST',
                        'url': 'saMatXEstaPqt',
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
                    }).done(function (data) {
                 	   
                       	var data = JSON.parse(data);
                    	if(data.error == 0){
                    		setTimeout(
                  				  function() 
                  				  {
                   					 $('#modalProgreso').modal('toggle');
                   					 swal({
                                         title: 'Se registraron correctamente los Materiales!',
                                         text: 'Asegurese de validar la informacion!',
                                         type: 'success',
                                         buttonsStyling: false,
                                         confirmButtonClass: 'btn btn-primary',
                                         confirmButtonText: 'OK!'
             
                                     }).then(function () {                            
                                     	console.log('done:'+itemplan);
                                     	//cargarTerminado(itemplan);
                                     	location.reload();
                                     });
                    		
              				  }, 5000);
                           

                            
                    	}else if(data.error == 1){                    		
                    		
                    		mostrarNotificacion('warning','Notificacion!', data.msj);
                    		setTimeout(
                  				  function() 
                  				  {
                    		$('#modalProgreso').modal('toggle');}, 2000);
	         			}
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                    	$('#modalProgreso').modal('toggle');
                        mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                    })
                 			
         		  });
     		  
        });
        
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>