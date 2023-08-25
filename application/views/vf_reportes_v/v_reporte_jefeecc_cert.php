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
        <link rel="stylesheet" href="<?php echo base_url();?>public/tree/jquery.treetable.theme.default.css" />
        
        <style>
/* Center the loader */
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 150px;
  height: 150px;
  margin: -75px 0 0 -75px;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Add animation to "page content" */
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 } 
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
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
		                   
		                   <h2>REPORTE CERTIFICACION 
                           </h2>
			                    <div class="card">
			                        <div class="card-block">	   				                         
                                    <div class="row">
                                   <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>PROYECTO</label>

                                        <select id="selectProyecto" name="selectProyecto" class="select2" onchange="changueProyect()">
                                             <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaProy->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->proyecto ?>"><?php echo $row->proyecto ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>

                                        <select id="selectSubProy" name="selectSubProy" class="select2" >
                                            <option>&nbsp;</option>
                                                          
                                        </select>
                                    </div>
                                </div>

                                 <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>JEFATURA</label>

                                        <select id="selectJefatura" name="selectJefatura" class="select2" >
                                                <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaJefatu->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->jefatura ?>"><?php echo $row->jefatura ?></option>
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
                                                    foreach($listaEECC->result()  as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->eeccplanobra ?>"><?php echo $row->eeccplanobra ?></option>
                                                 <?php }?>

                                              
                                                                       
                                        </select>
                                </div>
                                </div>


                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>A&Ntilde;O</label>

                                        <select id="selectAnio" name="selectAnio" class="select2" >
                                           <option>&nbsp;</option>

                                              <?php                                                    
                                                    foreach($Anio->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->anioanterior ?>"><?php echo $row->anioanterior ?></option>
                                                  <option value="<?php echo $row->anioactual ?>"><?php echo $row->anioactual ?></option>
                                                 <?php }?>
                                                                       
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>MES</label>

                                        <select id="selectMes" name="selectMes" class="select2" multiple="true" >
                                             <option>&nbsp;</option>
                                       <option value="1">ENERO</option>
                                       <option value="2">FEBRERO</option>
                                       <option value="3">MARZO</option>
                                       <option value="4">ABRIL</option>
                                       <option value="5">MAYO</option>
                                       <option value="6">JUNIO</option>
                                       <option value="7">JULIO</option>
                                       <option value="8">AGOSTO</option>
                                       <option value="9">SEPTIEMBRE</option>
                                       <option value="10">OCTUBRE</option>
                                       <option value="11">NOVIEMBRE</option>
                                       <option value="12">DICIEMBRE</option>
                                       
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-sm-12 col-md-2">
                                    <div class="form-group">
                                         <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div id="loader"></div>
		   				                            <div id="contTabla" class="table-responsive">
								                            <?php echo $tablaRepJefEECC?>
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
        
              
            <div class="modal fade" id="modal-large"  tabindex="-1">
                <div class="modal-dialog modal-sm">
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
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        <script src="<?php echo base_url();?>public/tree/jquery.treetable.js"></script>
        <script type="text/javascript">

$(document).ready(function(){
   hideLoader();
});


function showLoader() {
  $("#loader").show();
}

function hideLoader(){
  $("#loader").hide();
}




        function changueProyect(){
        	var proyecto = $.trim($('#selectProyecto').val()); 
           	 $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getSubProyRepJEECC',
     	    	data	:	{proyecto  : proyecto},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){       
    
      	    	    $('#selectSubProy').html(data.listaSubProy);
       	 			$('#selectSubProy').val('').trigger('chosen:updated');
           	 		
     	    	}else if(data.error == 1){
       				
       				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
       			}
            });
        }

       function filtrarTabla(){
     	     var subProy = $.trim($('#selectSubProy').val()); 
           	 var proyecto = $.trim($('#selectProyecto').val());
             var mes = $.trim($('#selectMes').val());
             var anio = $.trim($('#selectAnio').val()); 

              var jefatura = $.trim($('#selectJefatura').val());
             var eecc = $.trim($('#selectEECC').val()); 
             showLoader();
             
       	    $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'getIPCNoC',
       	    	data	:	{subProy  :	subProy,
        	    	        proyecto  : proyecto,
                            mes : mes,
                            anio : anio,
                            jefatura : jefatura,
                            eecc : eecc},
       	    	'async'	:	false
       	    })
       	    .done(function(data){
       	    	var data	=	JSON.parse(data);
       	    	if(data.error == 0){  
       	    	    hideLoader();
       	    		$('#contTabla').html(data.tablaRepJefEECC)
       	    	    initDataTable('#data-table');
       	    		
       			}else if(data.error == 1){
       				
       				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
       			}
       		  });
        }
   


        function getDetalle(component){
            
                var anio = $(component).attr('data-anio');
                var mes = $(component).attr('data-mes');
                var jefatura = $(component).attr('data-jef');
                var eeccip = $(component).attr('data-eeccip');
                var fcert = $(component).attr('data-fcert');
                
                $.ajax({
                  type  : 'POST',
                  url     : "getDetCertIP",
                data: { 'anio' : anio,
                      'mes' : mes,
                      'jefatura' : jefatura,
                      'eeccip' : eeccip,
                      'fcert' : fcert},
                  'async' : false
                })
                .done(function(data){                   
                  var data = JSON.parse(data);            
              if(data.error == 0){
                $('#contTablaDetalle').html(data.tablaDetalleItem);                     
               
                  $('#modal-large').modal('toggle');              
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

            
       
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>