<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">        
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
		
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">

        <!-- Demo -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/demo/css/demo.css">
        
        <!-- <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css">-->
        <style>
        @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
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
             	<div class="content__inner" style="/*max-width: 100%;*/">
             		<h2 >TABLERO DE COMANDO - BANDEJA APROBACION POR HORAS</h2>
              		<div class="card">
              			<div class="card-block">	
              				
                            <div class="row"> 
                                
                                <div class="col-sm-12 col-md-12">
                                    <div id="contTabla" class="table-responsive">
                                        <?php echo $tablaReporteBA?>
                                    </div>
                              </div>
                                     		 
              				</div>    
              				<br>
              				<div class="row" id="divContenido">
                  				<div class="col-sm-12 col-md-12">
                                    <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                                </div>  
                                <div class="col-sm-12 col-md-12">
                                    <div id="contTablaDetalle" class="table-responsive">
                                        
                                        <table id="data-table" class="table table-bordered">
                                            <thead class="thead-default">
                                                <tr>
                                                    <th>PROYECTO</th>
                                                    <th>ITEMPLAN</th>
                                                    <th>PO</th>
                                                    <th>FEC.PRE PRE APROBACION</th>
                                                    <th>DIF. HORAS</th>
                                                    <th>ESTADO</th>
                                                </tr> 
                                            </thead>
                                            <tbody>
                                            <tr>
                                                    <th>-</th>
                                                    <th>-</th>
                                                    <th>-</th>                            
                                                    <th>-</th>
                                                    <th>-</th>
                                                    <th>-</th>
                                                </tr> 
                                            </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>            				        	
                    	</div>	
                    </div>
                   
                </div>        
                
                <footer class="footer hidden-xs-down">
                                            <p>Telefonica del Peru</p>

                                           
                           </footer>      
            </section>
            
                    
             <!--           
            <div class="modal fade"  id="modalPieBa" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h3 class="modal-title">DETALLE POR PROYECTO</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                    <div class="row">
                       <div class="col-sm-12 col-md-12">
                                <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                        </div>                       
                    </div>
                        <div id="contTablaSiom">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>      
                </div>
            </div>
        </div>
        -->
        </main>

        

        <!-- Javascript -->
        <!-- Vendors -->
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/autosize/dist/autosize.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        
        <script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
        
        
        <script src="<?php echo base_url();?>public/highcharts/highcharts.js?v=<?php echo time();?>" charset="UTF-8"></script>
        <script src="<?php echo base_url();?>public/highcharts/modules/drilldown.js"></script>
        <script src="<?php echo base_url();?>public/highcharts/modules/data.js"></script>
        <script src="<?php echo base_url();?>public/highcharts/modules/exporting.js" charset="UTF-8"></script>
        
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        
        
        
        <!-- Demo -->
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>     
  	    <script src="<?php echo base_url();?>public/js/js_tableros_comando/reporte_bandeja_aprob_horas.js?v=<?php echo time();?>"></script>		
   
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
</html>