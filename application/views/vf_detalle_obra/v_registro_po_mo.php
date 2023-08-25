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
                   <a href="https://www.movistar.com.pe/" title="Entel Per�"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                            <h2>REGISTRO PO / ITEMPLAN: <?php echo $itemplan?> - ESTACION : <?php echo $estacionDesc?></h2>
                                   
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	
                <div class="form-group col-sm-12" style="text-align: center;">		<!--		
				    <label style="color: red" class="control-label mb-10 text-left">Debe subir un archivo TXT separado por tabulaciones.</label>
					<label ><a style="color: blue"  href="download/modelos/modelo_carga_oc.xlsx" download="modelo_carga_orden_compra.xlsx">Descargar modelo de Carga Aqui!</a></label><br>-->
					<label style="color: red" class="control-label mb-10 text-left">Descargue el archivo de partidas asociadas a la estacion  partidas.xlsx</label><br>
					<label style="color: red" class="control-label mb-10 text-left">Llenar la columna "CANTIDAD" del archivo partidas.xlsx y cargarlo para su proceso.</label>
				</div>
				 <div class="row">
                    <div class="form-group col-md-3" style="text-align: center;">                        
                        <input type="button" data-from="<?php echo $from?>" data-itm="<?php echo $itemplan?>" data-esta="<?php echo $idEstacion?>" id="btnExportFormato" value="1) Exportar Partidas" class="btn-success" onclick="exportPartidas(this)">
                    </div>
                    <div class="form-group col-md-3">
        		          <input id="fileTable" name="fileTable" type="file" class="file" data-show-preview="false">                    
                    </div>  
                    <div class="form-group col-md-2" style="text-align: right;">                        
                        <input type="button" data-from="<?php echo $from?>" data-itm="<?php echo $itemplan?>" data-esta="<?php echo $idEstacion?>" id="btnExportFormato" value="2) Procesar Partidas" class="btn-success" onclick="procesarFile(this)">
                    </div>  
                         
                    <div class="form-group col-md-4" style="text-align: center;">                        
                        <input type="button" data-from="<?php echo $from?>" data-itm="<?php echo $itemplan?>" data-esta="<?php echo $idEstacion?>" id="preLoadFile" value="3) Crear PO" class="btn-success">
                    </div>      
                </div>
			
                 <div class="form-group col-sm-12 table-responsive">       
                	<table style="font-size: 10px" id="data-table2" class="table table-bordered">
                    	<thead class="thead-default">                        
                           <tr role="row">
                                <th colspan="1"></th> 
                                <th colspan="1">CODIGO</th> 
                                <th colspan="1">DESCRIPCION</th>  
                                <th colspan="1">TIPO</th>                        
                                <th colspan="1">COSTO</th>          
                                <th colspan="1">BAREMO</th> 
                                <th colspan="1">CANTIDAD INGRESADA</th> 
                                <th colspan="1">TOTAL</th>
                                <th colspan="1">SITUACION</th>                      
                            </tr>
                        </thead>  
                        <tfoot>
                            <tr>
                            	<th colspan="1"></th> 
                                <th colspan="1">CODIGO</th> 
                                <th colspan="1">DESCRIPCION</th>    
                                <th colspan="1">TIPO</th>                        
                                <th colspan="1">COSTO</th>          
                                <th colspan="1">BAREMO</th> 
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
                        <label style="font-weight: bold;">TOTAL: </label>
                        <label style="font-weight: bold;" id="montoTotal">0.00</label>
                    </div> 
		   				                        </div>
		   				                    </div>
		   				                </div>

			                <footer class="footer hidden-xs-down">
			                    <p>Telef�nica Del Per�</p>   				                  
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
        <script type="text/javascript">
        var monto_final_po = 0;
        function exportPartidas(component){
        	var itemplan   = $(component).attr('data-itm'); 
        	var idEstacion = $(component).attr('data-esta');
            $.ajax({
            	type	:	'POST',
     	    	'url'	:	'exParMO',
     	    	data	:	{itemplan   : itemplan,
     	    	             idEstacion : idEstacion},
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
            console.log("ENTRO1");
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
    		            form.append('item', itemplan);
    		            form.append('idEstacion',idEstacion);
    		            console.log('Procesando...');
    		            
    		            $.ajax({
    		                url : "upFiPoMo",
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
    		         	    		listaFileTemp     =  infoFile;   
    		         	    		var fileValido    =  JSON.parse(data.jsonDataFIleValido);
    		         	    		listaFileValido   =  fileValido;  
    		         	    		monto_final_po    =  data.total_final;      
    		         	    		$('#montoTotal').html(formatearNumeroComas(monto_final_po));	    		 
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

        $('#preLoadFile').click(function(e){
            if(listaFileValido!=null && listaFileValido.length>0){
            	var itemplan   = $('#preLoadFile').attr('data-itm'); 
				var idEstacion = $('#preLoadFile').attr('data-esta');
            	var montoTotalMo = monto_final_po;
				
				if(idEstacion == null || idEstacion == '') {
					return;
				}
				if(itemplan == null || itemplan == '') {
					return;
				}
				
		
				swal({
					title: 'Est� seguro generar la PO?',
					text: 'Asegurese de que la informacion llenada sea la correta.',
					type: 'warning',
					showCancelButton: true,
					buttonsStyling: false,
					confirmButtonClass: 'btn btn-primary',
					confirmButtonText: 'Si, guardar los datos!',
					cancelButtonClass: 'btn btn-secondary',
					allowOutsideClick: false
				}).then(function(){
						var jsonDataFile = listaFileValido;
						console.log('creando po...');                            	
						var from = $('#preLoadFile').attr('data-from');
						
						console.log(itemplan+'-'+idEstacion);
						$.ajax({
							type	:	'POST',
							'url'	:	'saPoMo',
							data	:	{ jsonDataFile  :   JSON.stringify(jsonDataFile),
											itemplan    :   itemplan,
											idEstacion  :   idEstacion,
											from        :   from},
							'async'	:	false
						})
						.done(function(data){
							var data	=	JSON.parse(data);
							if(data.error == 0){
								swal({
									title: 'Se genero correctamente el PO: ' + data.codigoPO + '',
									text: 'Asegurese de validar la informacion!',
									type: 'success',
									buttonsStyling: false,
									confirmButtonClass: 'btn btn-primary',
									confirmButtonText: 'OK!'

								}).then(function () {
									window.location.href = "detallePI?item=" + itemplan + "&from=" + 2;
								});
								//listaFileTemp = null;
								//listaFileValido = null;
								//$('#contBodyTable').html('');
								//$('#fileTable').val("");
								// $('#contSubirInfo').hide();
								
							}else if(data.error == 1){
								mostrarNotificacion('error','Ocurrio un error!', data.msj);
							}
						  });
        
					});
                
     		  }else{
         		  alert('No hay datos validos para actualizar, ingrese otro archivo');
     		  }
     		  
        });
        

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>