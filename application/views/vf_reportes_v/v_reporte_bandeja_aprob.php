<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
        <META charset="UTF-8">

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
    </head>

    <body data-ma-theme="entel" >
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
                 <a href="https://www.movistar.com.pe/" title="Movistar"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Movistar" style="width: 36%; margin-left: -51%"></a>
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

                <a href="messages.html" class="btn btn--action btn--fixed btn-danger"><i class="zmdi zmdi-plus"></i></a>
            </aside>



            <!--//////////////////////////////////////////-->
            <!--/////////////COMIENZA A EDITAR////////////-->
            <!--//////////////////////////////////////////-->




            <section class="content content--full">
                <div class="content__inner">
		             <h2>REPORTE BANDEJA DE APROBACION</h2>


                    <div class="card" style="width: 72%; margin-left: 13%;">
                            <div class="card-block">
                                <div class="tab-container">

                                    <div class="tab-content">
                                        <div class="tab-pane active fade show" id="estacionArea" role="tabpanel">
                                                    <!-- addCentral -->
                                            <div id="contTablaEstacionArea" class="table-responsive table-small">

                                            </div>
                                        </div>
                                        <div class="tab-pane fade show" id="estacion" role="tabpanel">
                                                    <!-- addCentral -->
                                            <div id="contTablaEstacion" class="table-responsive table-small">

                                            </div>
                                        </div>


                                        <div class="row">
                                           <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label>PROYECTO</label>

                                                        <select id="selectProyecto" name="selectProyecto" class="select2" onchange="changueProyect()">
                                                             <option>&nbsp;</option>
                                                        <?php
                                                                    foreach($listaProy->result() as $row){
                                                                ?>
                                                                 <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                                                 <?php }?>
                                                        </select>
                                                    </div>
                                           </div>
                                                <div class="col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <label>SUB PROYECTO</label>

                                                        <select id="selectSubProy" name="selectSubProy" class="select2" onchange="filtrarTabla()" multiple >
                                                            <option>&nbsp;</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label>FASE</label>

                                                        <select id="selectFase" name="selectFase" class="select2" onchange="filtrarTabla()">
                                                           <option>&nbsp;</option>
                                                           <option value="2017">2017</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-2">
                                                    <div class="form-group">
                                                        <label>AREA</label>

                                                        <select id="selectArea" name="selectArea" class="select2" onchange="filtrarTabla()">
                                                          <option>&nbsp;</option>
                                                          <option value="MAT">MAT</option>
                                                          <option value="MO">MO</option>
                                                          <option value="DUO">DUO</option>
                                                          <option value="BLANCO">(en blanco)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                        </div>
                                                        <div id="contTabla" class="table-responsive">
                                                        <?php echo $tablaAsigGrafo?>
                                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </section>
        </main>

        <div class="modal fade" id="abrirModal"  tabindex="-1" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content" style=" width:  121%;">
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


        <div class="modal fade"id="modalDetaPorc"  tabindex="-1" data-backdrop="static">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModalPor" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                        <button type="button" class="close" onclick="closeDetPor();">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="editPorcentaje" method="post" class="form-horizontal">
                            <div class="row"  id="contChoice">

                            </div>
                            <div id="mensajeForm2"></div>
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-link waves-effect" onclick="closeDetPor();">Close</button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Older IE warning message -->
            <!--[if IE]>
                <div class="ie-warning">
                    <h1>Warning!!</h1>
                    <p>You are using an outdated version of Internet Explorer, please upgrade to any of the following web browsers to access this website.</p>

                    <div class="ie-warning__downloads">
                        <a href="http://www.google.com/chrome">
                            <img src="img/browsers/chrome.png" alt="">
                        </a>

                        <a href="https://www.mozilla.org/en-US/firefox/new">
                            <img src="img/browsers/firefox.png" alt="">
                        </a>

                        <a href="http://www.opera.com">
                            <img src="img/browsers/opera.png" alt="">
                        </a>

                        <a href="https://support.apple.com/downloads/safari">
                            <img src="img/browsers/safari.png" alt="">
                        </a>

                        <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">
                            <img src="img/browsers/edge.png" alt="">
                        </a>

                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="img/browsers/ie.png" alt="">
                        </a>
                    </div>
                    <p>Sorry for the inconvenience!</p>
                </div>
            <![endif]-->

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
        <script type="text/javascript">

        function changueProyect(){
        	var proyecto = $.trim($('#selectProyecto').val()); 
           	 $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getSubPro3',
     	    	data	:	{proyecto  : proyecto},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){       
    
      	    	    $('#selectSubProy').html(data.listaSubProy);
       	 			$('#selectSubProy').val('').trigger('chosen:updated');
           	 		filtrarTabla();
     	    	}else if(data.error == 1){
       				
       				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
       			}
            });
        }
       function filtrarTabla(){
     	     var subProy = $.trim($('#selectSubProy').val());
           var proyecto = $.trim($('#selectProyecto').val());
           var area = $.trim($('#selectArea').val());
           var fase = $.trim($('#selectFase').val());
           console.log('subProy:'+subProy);
           $.ajax({
               type	:	'POST',
               'url'	:	'getDatRba',
               data	:	{subProy  :	subProy,
                   proyecto  : proyecto,
                   area     : area,
                   fase : fase},
               'async'	:	false
           })
               .done(function(data){
                   var data	=	JSON.parse(data);
                   if(data.error == 0){
                       $('#contTabla').html(data.tablaAsigGrafo)
                       initDataTable('#data-table');

                   }else if(data.error == 1){

                       mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                   }
               });
        }








        // SIRVE PARA EL MODAL EMERGENTE  //
        function getDetalle(component){

            var area = $(component).attr('data-area');
            var subproyecto = $(component).attr('data-subp');
            var opcion = $(component).attr('data-opc');

            $.ajax({
                type	:	'POST',
                url     : "getDetBa",
                data: { 'area' : area,
                        'subproyecto' : subproyecto,
                        'opcion' : opcion},
                'async'	:	false
            })
                .done(function(data){

                    var data = JSON.parse(data);
                    if(data.error == 0){
                        $('#contTablaDetalle').html(data.tablaDetalleBA);
                        initDataTable('#data-table2');
                        $('#abrirModal').modal('toggle');
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