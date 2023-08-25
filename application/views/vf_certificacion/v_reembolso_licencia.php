<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/dropzone/downloads/css/dropzone.css" />
        
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        
         <style type="text/css">
           
            .select2-dropdown {
              z-index: 100000;
            }

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
                   <a href="https://www.movistar.com.pe/" title="MOVISTAR"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo MOVISTAR" style="width: 36%; margin-left: -51%"></a>
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

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">View Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </div>

                    <ul class="navigation">

                                 <?php echo $opciones?>
                    </ul>
                </div>
            </aside>

             <section class="content content--full">           
                <div class="content__inner">
                    <h2>REEMBOLSO LICENCIA</h2>
                    <div class="card">                            
                        <div class="card-block">
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>ESTADO</label>
                                        <select id="selectEstado" name="selectEstado" class="select2">
                                            <option value="">Seleccionar Tipo</option>
                                            <?php                                                    
                                                foreach($arrayEstadoFiltro as $row){                      
                                            ?> 
                                                <option value="<?php echo $row['id_estado_reembolso'] ?>"><?php echo $row['estadoDesc'] ?></option>
                                            <?php }?>										
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>ITEMPLAN</label>
                                        <input id="txtItemplan" type="text" class="form-control input-mask" placeholder="Itemplan" autocomplete="off" maxlength="16" style="border-bottom: 1px solid lightgrey">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <button style="margin-top: 30px;" class="btn btn-success waves-effect" type="button" onclick="filtrarBandejaReembolsoLicencia();">CONSULTAR</button>
                                </div>  
                            </div>                                       
                            <div id="contTabla" class="table-responsive">
                                <?php echo $tablaAsigGrafo?>
                            </div>
                        </div>
                    </div>
                 </div>
               <footer class="footer hidden-xs-down">
                    <p>Telefonica del Peru</p>                                           
               </footer>
    
				<div class="modal fade" id="modalDataEntidades">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header" style="margin: auto;">
                                <h5 style="font-weight: bold;" class="modal-title pull-left">ENTIDADES</h5>
                            </div>
                            <div class="modal-body">
                                <div id="contDataEntidad" class="table-responsive">
								</div>
                            </div>
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button id="btnSave" class="btn btn-primary" onclick="validarEntidadReembolso();">Guardar</button>
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>                  
                        </div>
                    </div>
                </div>

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

        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
         <script src="<?php echo base_url();?>public/dropzone/downloads/dropzone.min.js"></script>
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url();?>public/js/js_certificacion/jsReembolsoLicencia.js?v=<?php echo time();?>"></script>
    </body>
</html>