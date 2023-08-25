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
                            <h2>EDITAR / LIQUIDAR MO: <?php echo $estacionDesc?></h2>
                                   
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
                        <input type="button" data-po="<?php echo $codigo_po?>" data-from="<?php echo $from?>" data-itm="<?php echo $itemplan?>" data-esta="<?php echo $idEstacion?>" id="btnExportFormato" value="1) Exportar Partidas" class="btn-success" onclick="exportPartida(this)">
                    </div>
                    <div class="form-group col-md-3">
        		          <input id="fileTable" name="fileTable" type="file" class="file" data-show-preview="false">                    
                    </div>
                    <div class="form-group col-md-2" style="text-align: right;">                        
                        <input type="button" data-po="<?php echo $codigo_po?>" data-from="<?php echo $from?>" data-itm="<?php echo $itemplan?>" data-esta="<?php echo $idEstacion?>" id="btnExportFormato" value="2) Procesar Partidas" class="btn-success" onclick="procesarFile(this)">
                    </div>                           
                    <div class="form-group col-md-4" style="text-align: center;">                        
                        <input type="button" data-po="<?php echo $codigo_po?>" data-from="<?php echo $from?>" data-itm="<?php echo $itemplan?>" data-esta="<?php echo $idEstacion?>" id="preLoadFile" value="3) Editar PO" class="btn-success">
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
                                <th colspan="1">CANTIDAD INICIAL</th> 
                                <th colspan="1">CANTIDAD FINAL</th> 
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
                                <th colspan="1">CANTIDAD INICIAL</th> 
                                <th colspan="1">CANTIDAD FINAL</th> 
                                <th colspan="1">TOTAL</th>    
                                <th colspan="1">SITUACION</th>                                       
                            </tr>
                        </tfoot>                  
                    <tbody id="contBodyTable">
                    	<?php echo $contTableBasic?>
                     </tbody>
                	</table>
                </div>
                <div class="form-group col-md-11" style="text-align: right;">                        
                        <label style="font-weight: bold;">TOTAL: S/.</label>
                        <label style="font-weight: bold;" id="montoTotal"><?php echo number_format($costo_total,2,'.', ',')?></label>
                    </div> 
                    
                    <div id="contBtnLiquidar" class="form-group col-md-12" style="text-align: center;">                        
                        <input type="button" data-po="<?php echo $codigo_po?>" data-from="<?php echo $from?>" data-itm="<?php echo $itemplan?>" data-esta="<?php echo $idEstacion?>" id="btnExportFormato" value="LIQUIDAR PO" class="btn-success" onclick="liquidarPO(this)">
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
        <script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>">
		</script>
        <script type="text/javascript">
        var monto_final_po   =  <?php echo $costo_total?>;
		var monto_po   =  <?php echo $costo_total?>;
        var estadoPo   =  <?php echo $idEstadoPo?>;
		var fase       =  <?php echo $fase?>;
		var countPasadas =  <?php echo $countPasadas?>;
		var totalSolEx =  <?php echo isset($totalSolEx) ? $totalSolEx : 0; ?>;
        validaTeEstadoPo();        
        function validaTeEstadoPo(){
            if(estadoPo == <?php echo PO_LIQUIDADO?>){
         	   $('#contBtnLiquidar').hide();
            }
        }

        
        function liquidarPO(component){
            console.log('costo_total_mo:'+monto_final_po);
        	swal({
                title: 'Está seguro de liquidar la PO?',
                text: 'Asegurese de que la informacion llenada sea la correta.',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, guardar los datos!',
                cancelButtonClass: 'btn btn-secondary',
                allowOutsideClick: false
            }).then(function(){
            	var itemplan   = $(component).attr('data-itm'); 
            	var codigo_po  = $(component).attr('data-po');
                $.ajax({
                	type	:	'POST',
         	    	'url'	:	'liquidarPO',
         	    	data	:	{itemplan   : itemplan,     	    	             
          	    	             codigo_po  : codigo_po},
         	    	'async'	:	false
                }).done(function (data) {
                    var data = JSON.parse(data);
                    console.log('paso el parseo');
                    if (data.error == 0) {
                    	swal({
                            title: 'Se liquido correctamente el PO: ' + codigo_po + '',
                            text: 'Asegurese de validar la informacion!',
                            type: 'success',
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: 'OK!'

                        }).then(function () {
                            window.location.href = "detalleObra?item=" + itemplan + "&from=" + 2;
                        });
                    }else{
                        mostrarNotificacion('warning', 'Aviso', data.msj);
                    }
                })

            });       	

        }
        
        function exportPartida(component){
        	var itemplan   = $(component).attr('data-itm'); 
        	var idEstacion = $(component).attr('data-esta');
        	var codigo_po  = $(component).attr('data-po');
            $.ajax({
            	type	:	'POST',
     	    	'url'	:	'exParMOLi',
     	    	data	:	{itemplan   : itemplan,
     	    	             idEstacion : idEstacion,
      	    	             codigo_po  : codigo_po},
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
            
        	var itemplan   = $(component).attr('data-itm'); 
        	var idEstacion = $(component).attr('data-esta');
        	var codigoPo   = $(component).attr('data-po');
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
    		            form.append('codigoPo',codigoPo);
    		            console.log('Procesando...');
    		            
    		            $.ajax({
    		                url : "proLiPoMo",
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
    	  
    		         	    		$('#contBtnLiquidar').hide();
									console.log("TOTAL1: "+monto_po);
    		         	    		monto_final_po    =  data.total_final;
									console.log("TOTAL2: "+monto_final_po);
									console.log(listaFileValido);
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




        function removeTR(component){

        	var indice = $(component).attr('data-indice');
        	var indice_val = $(component).attr('data-indice_val');
            if(indice_val!=null){
            	monto_final_po = monto_final_po - listaFileValido[indice_val][7];            	
            	delete listaFileValido[indice_val];                   
            }           
        	$('#tr'+indice).remove();
        	//listaFileTemp.splice(indice, 1);
        	delete listaFileTemp[indice];
        	//$('#data-table').DataTable().row( $(component).parents('tr') ).remove().draw();
        	console.log(listaFileValido);
        	console.log('nuevo monto total:'+monto_final_po);
        	$('#montoTotal').html(formatearNumeroComas(monto_final_po));
        }

        $('#preLoadFile').click(function(e){
            if(listaFileValido!=null && listaFileValido.length>0){

            	var itemplan_val   = $('#preLoadFile').attr('data-itm'); 
            	var codigoPo_val   = $('#preLoadFile').attr('data-po');
				var idEstacion     = $('#preLoadFile').attr('data-esta');
            	var montoTotalMo = monto_final_po;
				
				if(idEstacion == null || idEstacion == '') {
					return;
				}
				if(itemplan_val == null || itemplan_val == '') {
					return;
				}
				
				// totalSolEx = (Number(totalSolEx)).toFixed(2);
				
                console.log("TOTAL: "+montoTotalMo);
                console.log("TOTAL SOLICITUD: "+totalSolEx);
				var flgExceso = 1;
				
				if(totalSolEx != null && totalSolEx != '' && totalSolEx != 0) {// SI POR SE VALIDO LA SOLICITUD TRAIGO EL TOTAL Y DEBE SER MAYOR, SI SE QUIERE VOLVER A EDITAR
					totalSolEx = parseFloat(totalSolEx)+20;
					console.log("TOTAL SOLICITUD2: "+totalSolEx);
					if(montoTotalMo < totalSolEx) {
						flgExceso = 0;
					} else {
						flgExceso = 2;
						var msjExceso = 'El exceso es mayor al monto validado por telefónica, ¿Requiere otra solicitud?';
					}
				}
				console.log("COUNT PASADOS"+countPasadas);
				console.log("monto PO: "+monto_po);
				if(flgExceso == 1 || flgExceso == 2) {
					if(countPasadas > 0) {
						if((monto_po + montoTotalMo * 0.02) < montoTotalMo) {
							var montoExcede = (Number(montoTotalMo) - Number(monto_po)).toFixed(2);
							let dataFile = new FormData();
							if(flgExceso == 1) {
								msjExceso = 'El nuevo costo excede al costo de la PO, desea enviar una solicitud?';
							}
							swal({
								title: msjExceso,
								//text: 'Debe esperar a que la solicitud se confirme para que los cambios sean efectuados.',
								html : '<div class="form-group">'+
											'<label style="color:red">SUBIR EVIDENCIA EXCESO</label>'+
											'<input type="file" name="archivo" id="archivoFile">'+
										'</div>'+
										'<div class="form-group">'+
											'<textarea class="col-md-12 form-control" placeholder="Ingresar Comentario..." style="height:80px;background:#F9F8CF" id="comentarioText"></textarea>'+
										'</div>',
								type: 'warning',
								showCancelButton: true,
								buttonsStyling: false,
								confirmButtonClass: 'btn btn-primary',
								confirmButtonText: 'Si, guardar los datos!',
								cancelButtonClass: 'btn btn-secondary',
								allowOutsideClick: false
							}).then(function(){
									var fileArchivo = $('#archivoFile')[0].files[0];
									var comentario = $('#comentarioText').val();
									console.log(comentario);
									var jsonDataFile = listaFileValido;
									
									var itemplan   = $('#preLoadFile').attr('data-itm'); 
									var codigoPo   = $('#preLoadFile').attr('data-po');
									
									dataFile.append('codigoPo', codigoPo);
									dataFile.append('comentario', comentario);
									dataFile.append('itemplan', itemplan);
									dataFile.append('idEstacion', idEstacion);
									dataFile.append('montoExcede', montoExcede);
									dataFile.append('montoPo', monto_po);
									dataFile.append('montoTotalMo', montoTotalMo);
									dataFile.append('jsonDataFile', JSON.stringify(jsonDataFile));
									dataFile.append('file', fileArchivo);
									// {   
										// jsonDataFile :   JSON.stringify(jsonDataFile),
										// itemplan     :   itemplan,
										// idEstacion   :   idEstacion,
										// codigoPo     :   codigoPo,
										// montoExcede  :   montoExcede,
										// montoPo      :   monto_po,
										// montoTotalMo :   montoTotalMo,
										// comentario   : 	 comentario
									// }
									$.ajax({
										type	:	'POST',
										'url'	:	'regSolicitudPreMo',
										data	:	dataFile,
										'async'	:	false,
										cache	: false,
										contentType: false,
										processData: false
									})
									.done(function(data){
										var data	=	JSON.parse(data);
										if(data.error == 0){
											swal({
												title: 'Se registró correctamente la solicitud: ' + data.codigoSolicitud + '',
												text: 'Asegurese de validar la informacion!',
												type: 'success',
												buttonsStyling: false,
												confirmButtonClass: 'btn btn-primary',
												confirmButtonText: 'OK!'
			
											}).then(function () {
												location.reload();
											});
											//listaFileTemp = null;
											//listaFileValido = null;
											//$('#contBodyTable').html('');
											//$('#fileTable').val("");
											// $('#contSubirInfo').hide();
											
										}else if(data.error == 1){
											mostrarNotificacion('warning','Informacion!', data.msj);
										}
									  });
			
							})
						} else {
							swal({
								title: 'Está seguro actualizar la PO?',
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
									var itemplan   = $('#preLoadFile').attr('data-itm'); 
									var idEstacion = $('#preLoadFile').attr('data-esta');
									var from       = $('#preLoadFile').attr('data-from');
									var codigoPo   = $('#preLoadFile').attr('data-po');
									console.log(itemplan+'-'+idEstacion);
									$.ajax({
										type	:	'POST',
										'url'	:	'saPoMoEdi',
										data	:	{ jsonDataFile  :   JSON.stringify(jsonDataFile),
														itemplan    :   itemplan,
														idEstacion  :   idEstacion,
														from        :   from,
														codigoPo    :   codigoPo},
										'async'	:	false
									})
									.done(function(data){
										var data	=	JSON.parse(data);
										if(data.error == 0){
											swal({
												title: 'Se actualizo correctamente la PO: ' + data.codigoPO + '',
												text: 'Asegurese de validar la informacion!',
												type: 'success',
												buttonsStyling: false,
												confirmButtonClass: 'btn btn-primary',
												confirmButtonText: 'OK!'
			
											}).then(function () {
												location.reload();
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
			
							})
						}
					} else {
						jsonCreateSol = { origen       		: 4,
										  tipo_po_dato 		: 2, 
										  accion_dato  		: 2, 
										  codigo_po_dato    : codigoPo_val, 
										  itemplan_dato  	: itemplan_val, 
										  costoTotalPo_dato : montoTotalMo, 
										  data_json         : listaFileValido,
										  idEstacion        : idEstacion };
						canCreateEditPOByCostoUnitario(jsonCreateSol, function() {//reactivado por czavala el 18.03.2020
							swal({
								title: 'Está seguro actualizar la PO?',
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
									var itemplan   = $('#preLoadFile').attr('data-itm'); 
									var idEstacion = $('#preLoadFile').attr('data-esta');
									var from       = $('#preLoadFile').attr('data-from');
									var codigoPo   = $('#preLoadFile').attr('data-po');
									console.log(itemplan+'-'+idEstacion);
									$.ajax({
										type	:	'POST',
										'url'	:	'saPoMoEdi',
										data	:	{ jsonDataFile  :   JSON.stringify(jsonDataFile),
														itemplan    :   itemplan,
														idEstacion  :   idEstacion,
														from        :   from,
														codigoPo    :   codigoPo},
										'async'	:	false
									})
									.done(function(data){
										var data	=	JSON.parse(data);
										if(data.error == 0){
											swal({
												title: 'Se actualizo correctamente la PO: ' + data.codigoPO + '',
												text: 'Asegurese de validar la informacion!',
												type: 'success',
												buttonsStyling: false,
												confirmButtonClass: 'btn btn-primary',
												confirmButtonText: 'OK!'
			
											}).then(function () {
												location.reload();
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
			
							})
						});//cierra validacion de costo unitario    
					}
				} else {

					swal({
						title: 'Está seguro actualizar la PO?',
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
							var itemplan   = $('#preLoadFile').attr('data-itm'); 
							var idEstacion = $('#preLoadFile').attr('data-esta');
							var from       = $('#preLoadFile').attr('data-from');
							var codigoPo   = $('#preLoadFile').attr('data-po');
							console.log(itemplan+'-'+idEstacion);
							$.ajax({
								type	:	'POST',
								'url'	:	'saPoMoEdi',
								data	:	{ jsonDataFile  :   JSON.stringify(jsonDataFile),
												itemplan    :   itemplan,
												idEstacion  :   idEstacion,
												from        :   from,
												codigoPo    :   codigoPo},
								'async'	:	false
							})
							.done(function(data){
								var data	=	JSON.parse(data);
								if(data.error == 0){
									swal({
										title: 'Se actualizo correctamente la PO: ' + data.codigoPO + '',
										text: 'Asegurese de validar la informacion!',
										type: 'success',
										buttonsStyling: false,
										confirmButtonClass: 'btn btn-primary',
										confirmButtonText: 'OK!'
	
									}).then(function () {
										location.reload();
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
	
					})
					
				}
            }else{
                alert('No hay datos validos para actualizar, ingrese otro archivo');
            }
     		  
        });
        

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>