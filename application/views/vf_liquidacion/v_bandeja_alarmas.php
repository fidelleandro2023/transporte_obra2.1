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
                                    <h2>BANDEJA DE ALARMAS</h2>
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	
		   				                        <!--    				                         
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="form-group">
                                                                <label>EECC</label>
                        
                                                                <select id="selectEECC" name="selectEECC" class="select2" onchange="filtrarTabla()">
                                                                     <option>&nbsp;</option>
                                                                <?php                                                    
                                                                            foreach($listaEECC->result() as $row){                      
                                                                        ?> 
                                                                         <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                                         <?php }?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                    </div>-->
                                                     <div class="row">
                                                         <div class="col-sm-12 col-md-12">
                                                            <a class="btn" style="background-color: #28B463; color: white; padding: 10px" href="excelAla">Descargar Detalle</a>
                                                        </div>
                                                     </div>
		   				                            <div id="contTabla" class="table-responsive">
								                            <?php echo $tablaAsigGrafo?>
		                           </div>
		   				                        </div>
		   				                    </div>
		   				                </div>

			                <footer class="footer hidden-xs-down">
			                    <p>Telef�nica Del Per�</p>   				                  
		                   </footer>
            </section>
        </main>
    <div class="modal fade" id="modal_detalle"  tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            
                            <div class="tab-container">
                                <div id="contTablaDetalle" class="table-responsive">     
                                
                                </div>
                            </div>
                          
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

         function getDetalle(component){
          	
            var pep = $(component).attr('data-pep');           
            var estado = $(component).attr('data-estado');
            var subpro = $(component).attr('data-subp');
            var rango = $(component).attr('data-rango');
       	    $.ajax({
       	    	type	:	'POST',
       	    	url     : "getDetAlar",
  			    data: { 'pep'     :   pep,
      			    	'estado'  :   estado,
      			    	'subpro'  :   subpro,
      			    	'rango'   :   rango},
       	    			'async'	  :	  false
       	    })
       	    .done(function(data){             	    
       	    	var data = JSON.parse(data);   					
			    	if(data.error == 0){
				    	$('#tituloModal').html(data.pep);
			    		$('#contTablaDetalle').html(data.tablaAsigGrafo);			    						
			    		initDataTable('#tabla_detalle');
			    		$('#modal_detalle').modal('toggle');      				
			    	}else if(data.error == 1){   				    	
						mostrarNotificacion('error','Error',data.msj);
					}
       		  })
       		  .fail(function(jqXHR, textStatus, errorThrown) {
       		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
       		  })
       		  .always(function() {
       	  	 
       		});           	              
       }

        function filtrarTabla(){   	 
     	  var eecc = $.trim($('#selectEECC').val());        	
           	
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'filCerti',
     	    	data	:	{eecc      : eecc},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){           	    	          	    	   
     	    		$('#contTabla').html(data.tablaAsigGrafo)
     	    		/*$("#tabla_datos").DataTable({
         	           	dom: 'Bfrtip',
         	           	"aaSorting": [],
         	           	buttons:[{extend:'excelHtml5'}],
         	           	bPaginate: false,
         	           	        	language:{sProcessing:"Procesando...",sLengthMenu:"Mostrar _MENU_ registros",sZeroRecords:"No se encontraron resultados",sEmptyTable:"Ning\u00fan dato disponible en esta tabla",sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",sInfoFiltered:"(filtrado de un total de _MAX_ registros)",sInfoPostFix:"",
         	           	sSearch:"Buscar:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Cargando...",oPaginate:{sFirst:"Primero",sLast:"\u00daltimo",sNext:"Siguiente",sPrevious:"Anterior"}}})	
         	           */
         	           initDataTable('#data-table');
     	    		
     			}else if(data.error == 1){
     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
     		  });
      }

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>