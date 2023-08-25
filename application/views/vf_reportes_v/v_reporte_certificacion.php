<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <style>.fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-usd,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}</style>
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
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }

            input[type=number] { -moz-appearance:textfield; }
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
                </div>
 <?php include 'application/views/v_opciones.php';?>
            </header>

            <aside class="sidebar sidebar--hidden">
                <div class="scrollbar-inner">
                    <div class="user">
                        <div class="user__info" data-toggle="dropdown">
                            <img class="user__img" src="<?php echo base_url(); ?>public/demo/img/profile-pics/8.jpg" alt="">
                            <div>
                                <div class="user__name"><?php echo $this->session->userdata('usernameSession') ?></div>
                                <div class="user__email"><?php echo $this->session->userdata('descPerfilSession') ?></div>
                            </div>
                        </div>


                    </div>

                    <ul class="navigation">
                    <?php echo $opciones ?>
                    </ul>
                </div>
            </aside>



            <section class="content content--full">
		                   <div class="content__inner">
                               <h2>REPORTE CERTIFICACION</h2>
                               <hr>

                               <div class="card">
                                    <div class="card-block">

                                        <div class="row">
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label>PROYECTO</label>

                                                    <select id="selectProy" name="selectProy" class="select2">
                                                        <option>&nbsp;</option>
                                                        <?php foreach($listaProy->result() as $row){ ?> 
                                                            <option value="<?php echo trim($row->proyectoDesc) ?>"><?php echo $row->proyectoDesc ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label>JEFATURA</label>

                                                    <select id="selectJefatura" name="selectJefatura" class="select2">
                                                        <option>&nbsp;</option>
                                                        <?php foreach($listaJefatura as $row){ ?> 
                                                            <option value="<?php echo $row->jefatura ?>"><?php echo $row->jefatura ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label>EE.CC.</label>

                                                    <select id="selectEECC" name="selectEECC" class="select2">
                                                        <option>&nbsp;</option>
                                                        <?php foreach($listaEECC as $row){ ?> 
                                                            <option value="<?php echo $row->desc_empresacolab_ptr ?>"><?php echo $row->desc_empresacolab_ptr ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <label>FASE</label>

                                                    <select id="selectFase" name="selectFase" class="select2">
                                                        <option>&nbsp;</option>
                                                        <?php foreach($listafase->result() as $row){ ?> 
                                                            <option value="<?php echo $row->idFase ?>"><?php echo $row->faseDesc ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-group">
                                                    <br><br>
                                                    <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="col-sm-12 col-md-12">
                                            <div class="tab-pane active fade show" id="reporte1" role="tabpanel">
                                                <div id="contTablaCount" class="table-responsive">
                                                    <?php echo $tablaReporte ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

		   				    </div>

		   				                <footer class="footer hidden-xs-down">
                                            <p>Telef&oacute;nica del Per&uacute;</p>

                                           
                           </footer>
            </section>

        </main>


<!-- Small -->
<div class="modal fade" id="modal_detalle"  tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">DETALLE DE PTRs</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                
                <div class="tab-container">
                    <div id="contTablaDetItemPlan" class="table-responsive">     
                    
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</div>


<div class="modal fade" id="modal_reporte2"  tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">DETALLE</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                
                <div class="tab-container">
                    <div id="contReporte2" class="table-responsive">     
                    
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</div>







        <!-- Older IE warning message -->


        <!-- Javascript -->
        <!-- ..vendors -->
        <script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jqvmap/dist/jquery.vmap.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jqvmap/dist/maps/jquery.vmap.world.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/salvattore/dist/salvattore.min.js"></script>
        <script src="<?php echo base_url(); ?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/moment/min/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

   <!--  tables -->
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url(); ?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <!-- Charts and maps-->
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/curved-line.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/line.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/chart-tooltips.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/jqvmap.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

        <!--  -->
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script type="text/javascript">

            //   initDataTable('#reporte2');

            function getDetalleItemPlans(component){

                var stringIPS  = $(component).data('arryip');
                var stringPTRS = $(component).data('arryptr');  
                var flgCertificado = $(component).data('flgcertificado');     
                $.ajax({
                        type: 'POST',
                        url: 'getDetPTRCertCV',
                        data: {
                            stringIPS : stringIPS,
                            stringPTRS: stringPTRS,
                            flgCertificado : flgCertificado
                        }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contTablaDetItemPlan').html(data.tablaDetItemplan);
                        initDataTable('#tabla_detalle');
                        modal('modal_detalle');
                    } else {
                        mostrarNotificacion('warning', 'Error', 'Hubo un error al traer el detalle');
                    }
                });
            }

            function mostrarDetalle(){
                var proyecto = $.trim($('#selectProy').val()); 
                var jefatura = $.trim($('#selectJefatura').val()); 
           	    var eecc = $.trim($('#selectEECC').val()); 
                 var idFase = $.trim($('#selectFase').val());
                 
                $.ajax({
                        type: 'POST',
                        url: 'getReport2CertMO',
                        data: { proyecto : proyecto,
                                jefatura : jefatura,
                                eecc : eecc,
                                idFase : idFase }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contReporte2').html(data.tablaReporte2);
                        initDataTable('#reporte2');
                        modal('modal_reporte2');
                    } else {
                        mostrarNotificacion('warning', 'Error', 'Hubo un error al traer el detalle');
                    }
                })
            }




            

            function filtrarTabla(){

                var proyecto = $.trim($('#selectProy').val()); 
                var jefatura = $.trim($('#selectJefatura').val()); 
           	    var eecc = $.trim($('#selectEECC').val()); 
             	var idFase = $.trim($('#selectFase').val());

                $.ajax({
                    type	:	'POST',
                    'url'	:	'getReportCertByFiltros',
                    data	:	{ proyecto : proyecto,
                                  jefatura : jefatura,
                                  eecc : eecc,
                                  idFase : idFase
                                },
                    'async'	:	false
                })
                .done(function(data){
                    var data	=	JSON.parse(data);
                    if(data.error == 0){
                        $('#contTablaCount').html(data.tablaReporte1);
                        //console.log(data.tablaReporte1);
                        initDataTable('#data-table');

                    }else{
                        mostrarNotificacion('error','Hubo problemas al filtrar los datos!!');
                    }
                });


            }

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>