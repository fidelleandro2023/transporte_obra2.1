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
                                    <h2>CARGA MASIVA DE ORDEN DE COMPRA</h2>
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	
                <div class="form-group col-sm-12" style="text-align: center;">				
				    <label style="color: red" class="control-label mb-10 text-left">Debe subir un archivo TXT separado por tabulaciones.</label>
					<label ><a style="color: blue"  href="download/modelos/modelo_carga_oc.xlsx" download="modelo_carga_orden_compra.xlsx">Descargar modelo de Carga Aqui!</a></label><br>
					<label style="color: red" class="control-label mb-10 text-left">Debera guardar el archivo Modelo .xlsx en el formato de Texto separado por tabulaciones.</label><br>
					<label style="color: red" class="control-label mb-10 text-left">Los Datos a procesar seran los colores:<input type="text" disabled="disabled" style="background: green; width: 35px;"><input type="text" disabled="disabled" style="background: orange; width: 35px;"></label>
				</div>
				 <div class="row">
                    
                    <div class="col-sm-12 col-md-3">
        		          <input id="fileTable" name="fileTable" type="file" class="file" data-show-preview="false">                    
                    </div>
                    <div class="col-sm-12 col-md-9" style="text-align: right;">
    				    <button id="preLoadFile" type="button" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Subir Informacion</span></button>
                    </div>
                </div>
			
                 <div class="form-group col-sm-12 table-responsive" style="    margin-top: 30px;">       
                	<table style="font-size: 10px" id="data-table2" class="table table-bordered">
                    	<thead class="thead-default">                        
                           <tr role="row">
                                <th colspan="1"></th> 
                                <th colspan="1">PTR</th> 
                                <th colspan="1">ORDEN DE COMPRA</th>                          
                                <th colspan="1"># CERTIFICACION</th>          
                                <th colspan="1">RESULTADO</th>                      
                            </tr>
                        </thead>  
                        <tfoot>
                            <tr>
                            	<th colspan="1"></th> 
                                <th colspan="1">PTR</th> 
                                <th colspan="1">ORDEN DE COMPRA</th>                          
                                <th colspan="1"># CERTIFICACION</th>    
                                <th colspan="1">RESULTADO</th>                                          
                            </tr>
                        </tfoot>                  
                    <tbody id="contBodyTable">
                    	
                     </tbody>
                	</table>
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
        <script type="text/javascript">

        var listaFileTemp = null;
        var listaFileValido = null;
        
        $('#fileTable').change(function(e){
        	
        	var comprobar = $('#fileTable').val().length;
    		
    		if(comprobar>0){

    			    var file = $('#fileTable').val()			
    			    console.log($('#fileTable').val().length);
    			    var ext = file.substring(file.lastIndexOf("."));
    			    console.log(ext);
    			    if(ext != ".txt")
    			    {
    			    	alert('Formato de archivo no v\u00E1lido. El formato correcto es .TXT Separado por Tabulaciones');
    			        return false;
    			    }
    			    else{
    			        
    			    	var input = document.getElementById('fileTable');
    		            var file = input.files[0];
    		            var form = new FormData();
    		            form.append('file', file);
    		            $.ajax({
    		                url : "upfoc",
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
    		         	    		console.log(listaFileValido);
    		         	    	}else if(data.error == 1){     				
    		         	    		alert(data.msj);
    		         			}
    		                    
    		                }
    		            });
    		            
    			    	
    			    }        	
    		}
        	
        });

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
        	
        }

        $('#preLoadFile').click(function(e){
            if(listaFileValido!=null && listaFileValido.length>0){
            	var jsonDataFile = listaFileValido;
            	//formData.append('jsonDataFile', JSON.stringify(jsonDataFile));  
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'saveDataOC',
         	    	data	:	{ jsonDataFile : JSON.stringify(jsonDataFile)},
         	    	'async'	:	false
         	    })
         	    .done(function(data){
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){
         	    		
         	    		listaFileTemp = null;
          	            listaFileValido = null;
            	        $('#contBodyTable').html('');    
            	        $('#fileTable').val("");  	
            	        mostrarNotificacion('success','Se actualizaron los datos correctamente!');
         	    		console.log('todo OK');
         			}else if(data.error == 1){
         				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
         			}
         		  });
     		  }else{
         		  alert('No hay datos validos para actualizar, ingrese otro archivo');
     		  }
     		  
        });
        

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>