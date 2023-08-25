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
            

            <section class="content content--full">
           
		                   <div class="content__inner">
                            <h2>CAMBIO EECC - SUBPROYECTO MASIVO</h2>
                            
            <div class="card">
		   				                        
            <div class="card-block">	
                <div class="form-group col-sm-12" style="text-align: center;">		<!--		
				    <label style="color: red" class="control-label mb-10 text-left">Debe subir un archivo TXT separado por tabulaciones.</label>
					<label ><a style="color: blue"  href="download/modelos/modelo_carga_oc.xlsx" download="modelo_carga_orden_compra.xlsx">Descargar modelo de Carga Aqui!</a></label><br>-->
					<label style="color: red" class="control-label mb-10 text-left">Descargue el Formato de cambio_estado.xlsx</label><br>
					<label style="color: red" class="control-label mb-10 text-left">Llenar la columna "ITEMPLAN","EECC","SUBPROYECTO (OPCIONAL)" del archivo cambio_estado.xlsx y cargarlo para su proceso.</label>
				</div>
				 <div class="row">
				    
                    <div class="form-group col-md-3" style="text-align: center;">                        
                        <input type="button"id="btnExportFormato" value="1) Exportar Formato" class="btn-success" onclick="exportPartidas(this)">
                    </div>
                    
                    <div class="form-group col-md-3">
        		          <input id="fileTable" name="fileTable" type="file" class="file" data-show-preview="false">                    
                    </div>  
                    <div class="form-group col-md-2" style="text-align: right;">                        
                        <input type="button" id="btnExportFormato" value="2) Procesar Obras" class="btn-success" onclick="procesarFile(this)">
                    </div>  
                         
                    <div class="form-group col-md-4" style="text-align: center;">                        
                        <input type="button" id="preLoadFile" value="3) Actualizar" class="btn-success">
                    </div>  
                    <!-- 
                    <div class="form-group col-md-2" style="text-align: right">
                        <label  style="color: red;    font-weight: bolder;">Subir PDF OC</label>
                    </div>
                    <div class="form-group col-md-10">
        		          <input id="fileEvidencia" name="fileEvidencia" type="file" class="file" data-show-preview="false">                    
                    </div>  -->
                       
                </div>
			     <div id="contTabla" class="table-responsive">
                	   <?php echo $tablaSolOC?>
                </div>
             
		   				                        </div>
		   				                    </div>
		   				                </div>

			                <footer class="footer hidden-xs-down">
			                    <p>Telefónica Del Perú</p>   				                  
		                   </footer>
            </section>
            
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
        <!-- Javascript -->
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
        <script type="text/javascript">
        var monto_final_po = 0;
        function exportPartidas(component){
        	//var solicitud   = $(component).attr('data-itm'); 
            $.ajax({
            	type	:	'POST',
     	    	'url'	:	'exChangeEecc',
     	    	'async'	:	false
            }).done(function (data) {
                console.log('llego al done');
                var data = JSON.parse(data);
                console.log('paso el parseo');
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
    		                url : "upChangueEecc",
    		                type: "POST",
    		                cache: false,
    		                contentType: false,
    		                processData: false,
    		                data : form,
    		                success: function(response){
    		                    var data = JSON.parse(response);
    		                    if(data.error == 0){  
    		         	    		$('#contTabla').html(data.tablaData);  
    		         	    		initDataTable('#data-table');
    		         	    		var fileValido    =  JSON.parse(data.jsonDataFIle);
    		         	    		listaFileValido   =  fileValido;
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

        function removeTR(component){//falta remover el monto a editar cuando borras una partida...

        	var indice = $(component).attr('data-indice');
        	var indice_val = $(component).attr('data-indice_val');
        	//var montoTotalMo = monto_final_po;
            if(indice_val!=null){            	
                //console.log(listaFileValido[indice_val][7]);
                monto_final_po = monto_final_po - listaFileValido[indice_val][7];
            	delete listaFileValido[indice_val];                   
            }           
        	$('#tr'+indice).remove();
        	//listaFileTemp.splice(indice, 1);
        	delete listaFileTemp[indice];
        	//$('#data-table').DataTable().row( $(component).parents('tr') ).remove().draw();
        	//console.log(listaFileValido);
        	//console.log('nuevo monto total:'+monto_final_po);
        	$('#montoTotal').html(formatearNumeroComas(monto_final_po));
        }


        /**falta aqui**/

        $('#preLoadFile').click(function(e){

            if(listaFileValido!=null && listaFileValido.length>0){
            	//console.log('listaFileValido:'+listaFileValido);
            	swal({
                    title: 'Está seguro actualizar las obras?',
                    text: 'Asegurese de que la informacion sea la correta.',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, guardar los datos!',
                    cancelButtonClass: 'btn btn-secondary',
                    allowOutsideClick: false
                }).then(function(){
                    	var jsonDataFile = listaFileValido;
                    	var form_data = new FormData();                 
                        form_data.append('jsonDataFile', JSON.stringify(jsonDataFile));       
                        $.ajax({
                            type: 'POST',
                            'url': 'saChangueEecc',
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
                     	    //
                           	var data = JSON.parse(data);
                        	if(data.error == 0){
                        		setTimeout(
                      				  function() 
                      				  {
                        					$('#modalProgreso').modal('toggle');
                                    		swal({
                                                title: 'Se genero correctamente el ',
                                                text: 'Asegurese de validar la informacion!',
                                                type: 'success',
                                                buttonsStyling: false,
                                                confirmButtonClass: 'btn btn-primary',
                                                confirmButtonText: 'OK!'
            
                                            }).then(function () {
                                                window.location.href = "changeecmas";
                                            });
                        		
                  				  }, 5000);
                        	}else if(data.error == 1){     				
    	         	    		alert(data.msj);
    	         			}
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                        	$('#modalProgreso').modal('toggle');
                            mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                        })
                })

     		  }else{
         		  alert('No hay datos validos para actualizar, ingrese otro archivo');
     		  }
     		  
        });
        
        function backMenu(){        	
        	window.location.href = 'upMasiCreOcMen';
        }
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>