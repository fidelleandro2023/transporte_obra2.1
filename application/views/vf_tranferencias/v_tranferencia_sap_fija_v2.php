<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
                
        
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">

        <!-- Demo -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/demo/css/demo.css">
        
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css">
        
            <link href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css" rel="stylesheet" type="text/css"></link>
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
            
            <section class="content content--full">
             <div class="content__inner">
             <h2>TRANFERENCIA SAP FIJA V2.0</h2>
              <div class="card">
                
                
            <div class="card-block">	   				                         
                    <div class="row">
                        <div class="col-sm-12 col-md-12" style="text-align: center;">
                                    <section>
                                     <label style="font-size: smaller;text-align: left;">- El archivo a importa debe estar en formato .txt (Archivo de Texto).</label><br>
                                     <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe contar con 17 columnas separados por Tabulaciones.</label>
                                    <br><br>
                                     <div id="contProgres">
                            <div class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                <span id="valuePie" class="easy-pie-chart__value">0</span>
                            </div>
                        
                        
                                    </div>
                                    <div id="contSubida">
                                        <form id="subida">
                                            <table style="margin: auto;">
                                            	<tr>
                                                	<td>  <input id="csv" name="userfile" type="file" /></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="submit" value="Procesar Archivo"  style="background-color: var(--verde_telefonica);"/></td>
                                                </tr>
                                                
                                            </table><br>
                                            
                                        </form>    
                                    </div>
                                  
                                                                
	                           </section>	  
	                           
	                           </div>                      
	                         </div> 
	                         <div id="contResult" class="row">
    	                         <div class="col-sm-12">    	                        
                                    <div class="form-group">
                                    <table style="margin: auto;">
                                        <tr>
                                            <td id="respuesta"></td>
                                         </tr>
                                     </table>                                                                   
        	                        </div>
    	                        </div>
	                        </div>
							<div id="contTablaEvaluaPep">
								<?php echo isset($tablaEvaluaPep) ? $tablaEvaluaPep : null; ?>
							</div>
	                    </div>

               
                    
                        
                 
                       
                    </div>
                   
                </div>

            </section>
        </main>

        <!-- Javascript -->
        <!-- Vendors -->
        
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <script src="<?php echo base_url();?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url();?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>

		<script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        <!-- Demo -->
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>        
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script type="text/javascript">

        	$('#subida').submit(function(e){
       		 e.preventDefault();
      		
        		var comprobar = $('#csv').val().length;
        		
        		if(comprobar>0){

        			    var file = $('#csv').val()			
        			    console.log($('#csv').val().length);
        			    var ext = file.substring(file.lastIndexOf("."));
        			    console.log(ext);
        			    if(ext != ".txt")
        			    {
        			    	alert('Formato de archivo no v\u00E1lido. El formato correcto es .TXT');
        			        return false;
        			    }
        			    else
        			    {
        			    	var formulario = $('#subida');        					
        					var archivos = new FormData();        					
        					var url = 'upsf1v2';        					
        						for (var i = 0; i < (formulario.find('input[type=file]').length); i++) {        						
        		               	 archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));        						 
        		      		 	}
        						$('.easy-pie-chart').data('easyPieChart').update('5');
        						$('#contSubida').hide();
  					            $('#valuePie').html(5);
        					$.ajax({
        						url: url,        						
        						type: 'POST',        						
        						contentType: false,     						
        		            	data: archivos, 						
        		               	processData:false,					
        						success: function(data){
        							data = JSON.parse(data);
        							if(data.error == 1){
        								$('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color:#968c07;">'+data.msj+'</label>');
        							    $('#contResult').show();
        								return false;
        							}else if(data.error == 0){
        								$('.easy-pie-chart').data('easyPieChart').update('20');
        					            $('#valuePie').html(20);

        					            $.ajax({
              							     type : 'POST',
              							     url : 'upsf2v2'
                   						   }).done(function(data){
                      							data = JSON.parse(data);
												console.log(data);
                      							if(data.error == 1){
                      								$('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">'+data.msj+'</label>');
                    								$('#contResult').show();
                      								return false;	
                      							}else if(data.error == 0){
                      							    $('.easy-pie-chart').data('easyPieChart').update('60');
                      							    $('#valuePie').html(60);
                      							    
                      					            $.ajax({
                        							     type : 'POST',
                        							     url : 'upPFV2'
                             						   }).done(function(data){
                                							data = JSON.parse(data);
                                							if(data.error == 1){
                                								$('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">'+data.msj+'</label>');
                                							    $('#contResult').show();
                                								return false;	
                                							}else if(data.error == 0){
                                							    $('.easy-pie-chart').data('easyPieChart').update('100');
                                							    $('#valuePie').html(100);
                                							    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #0d7d3f;">Se importaron los datos con Éxito!</label>');
                                							    mostrarNotificacion('success', 'Exito', 'Se actualizo correctamente la Informacion!');
																
																// $.ajax({
																	// type : 'POST',
																	// url  : 'procesarSisego'																	
																// });
															}                 							     
                             						   });
                      							 }                 							     
                   						   });       					            
        							}
        						}
        					})
        					return false;
        			    }
        		}else{
        			mostrarNotificacion('warning', 'Alerta', 'Selecciona un archivo txt para importar');
        			return false;
        			
        		}
        	});
  
        
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
</html>