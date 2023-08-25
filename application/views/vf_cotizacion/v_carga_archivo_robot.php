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
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css"> 
    </head>

    <body data-ma-theme="entel">
        <main class="main">
            <div class="page-loader">
                <svg x="0" y="0" width="258" height="258">
                    <g clip-path="url(#clip-path)">
                        <path class="tree" id="g" />
                    </g>
        
                    <clipPath id="clip-path">  
                        <path id="path" class="circle-mask"/>
                    </clipPath>   
                </svg>
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
                    <h2>CARGAR ARCHIVO ROBOT V2</h2>
                    <div class="container" align="center">  
                        <div class="card">		 				                    
                            <div class="card-block">                                 
                                <div class="tab-container">
                                    <ul class="nav nav-tabs nav-fill" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#masivo_cto" role="tab">CARGA CTO</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#masivo_reserva" role="tab">CARGA RESERVAS</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#masivo_ebc" role="tab">CARGA EBC</a>
                                        </li>
										 <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#masivo_cto_edif" role="tab">CARGA CTOS EDIF.</a>
                                        </li>  
                                    </ul>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade active fade show" id="masivo_cto" role="tabpanel">
                                        <div>
                                            <h5><u>CTO</u></h5>
                                        </div>
                                        <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe  estar en formato .xls o .xlsx(Excel).</label><br>
                                        <label style="font-size: smaller;text-align: left;">- Puede descargar un ejemplo de la estructura.</label><a href="download/modelos/ejemplo_cto_coti.xlsx" >Aqu&iacute;</a><br>
                                        <label style="font-size: smaller;text-align: left;">- hacer clic en el bot&oacute;n aceptar y esperar a que llegue al 100%.</label><br>
                                        
                                        <div id="contProgres">
                                            <div class="easy-pie-chart easy-pie-tab2" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                                <span id="valuePieTab2" class="easy-pie-chart__value">0</span>
                                            </div>
                                        </div>
                                        
                                        <form method="post" id="import_form_cto" enctype="multipart/form-data">
                                            <table style="margin: auto;">
                                                <tr>
                                                    <td><input type="file" name="file" id="fileExcelCto" required accept=".xls, .xlsx" onchange="subirArchivo();"/></td>
                                                    <img src="" width="200"  />
                                                </tr>
                                                <tr>
                                                    <td><input type="submit" name="import" value="Aceptar" style="background-color: var(--verde_telefonica)" /></td>
                                                </tr>
                                            </table><br>
                                        </form>    
                                        <div id="contTablaCto" style="display:none" class="table-responsive">
                                            
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="masivo_reserva" role="tabpanel">
                                        <div>
                                            <h5><u>RESERVAS</u></h5>
                                        </div>
                                        <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe  estar en formato .xls o .xlsx(Excel).</label><br>
                                        <label style="font-size: smaller;text-align: left;">- Puede descargar un ejemplo de la estructura.</label><a href="download/modelos/ejemplo_reserva_coti.xlsx" >Aqu&iacute;</a><br>
                                        <label style="font-size: smaller;text-align: left;">- hacer clic en el bot&oacute;n aceptar y esperar a que llegue al 100%.</label><br>
                                        
                                        <div id="contProgres">
                                            <div class="easy-pie-chart easy-pie-tabR" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                                <span id="valuePieTabR" class="easy-pie-chart__value">0</span>
                                            </div>
                                        </div>
                                        <!-- <div id="contSubida"> -->
                                            <form method="post" id="import_form_reservas" enctype="multipart/form-data">
                                                <table style="margin: auto;">
                                                    <tr>
                                                        <td><input type="file" name="file" id="fileExcelReservas" required accept=".xls, .xlsx" onchange="subirArchivo();"/></td>
                                                        <img src="" width="200"  />
                                                    </tr>
                                                    <tr>
                                                        <td><input type="submit" name="import" value="Aceptar" style="background-color: var(--verde_telefonica)" /></td>
                                                    </tr>
                                                </table><br>
                                            </form>    
                                        <!-- </div>         -->
                                    </div>
                                    <div class="tab-pane fade" id="masivo_ebc" role="tabpanel">
                                        <div>
                                            <h5><u>ESTACI&Oacute;N BASE CELULAR</u></h5>
                                        </div>
                                        <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe  estar en formato .xls o .xlsx(Excel).</label><br>
                                        <label style="font-size: smaller;text-align: left;">- Puede descargar un ejemplo de la estructura.</label><a href="download/modelos/ejemplo_ebc.xlsx" >Aqu&iacute;</a><br>
                                        <label style="font-size: smaller;text-align: left;">- hacer clic en el bot&oacute;n aceptar y esperar a que llegue al 100%.</label><br>
                                        
                                        <div id="contProgres">
                                            <div class="easy-pie-chart easy-pie-tab4" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                                <span id="valuePieTabEbc" class="easy-pie-chart__value">0</span>
                                            </div>
                                        </div>
                                        <!-- <div id="contSubida"> -->
                                            <form method="post" id="import_form_ebc" enctype="multipart/form-data">
                                                <table style="margin: auto;">
                                                    <tr>
                                                        <td><input type="file" name="file" id="fileExcelEbc" required accept=".xls, .xlsx" onchange="subirArchivo();"/></td>
                                                        <img src="" width="200"  />
                                                    </tr>
                                                    <tr>
                                                        <td><input type="submit" name="import" value="Aceptar" style="background-color: var(--verde_telefonica)" /></td>
                                                    </tr>
                                                </table><br>
                                            </form>    
                                        <!-- </div>         -->
                                    </div>
									<div class="tab-pane fade fade show" id="masivo_cto_edif" role="tabpanel">
                                        <div>
                                            <h5><u>CTO EDIFICIOS</u></h5>
                                        </div>
                                        <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe  estar en formato .xls o .xlsx(Excel).</label><br>
                                        <label style="font-size: smaller;text-align: left;">- Puede descargar un ejemplo de la estructura.</label><a href="download/modelos/ejemplo_cto_edif.xlsx" >Aqu&iacute;</a><br>
                                        <label style="font-size: smaller;text-align: left;">- hacer clic en el bot&oacute;n aceptar y esperar a que llegue al 100%.</label><br>
                                        
                                        <div id="contProgres">
                                            <div class="easy-pie-chart easy-pie-tab3" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                                <span id="valuePieTab3" class="easy-pie-chart__value">0</span>
                                            </div>
                                        </div>
                                        
                                        <form method="post" id="import_form_cto_edif" enctype="multipart/form-data">
                                            <table style="margin: auto;">
                                                <tr>
                                                    <td><input type="file" name="file" id="fileExcelCtoEdif" required accept=".xls, .xlsx" onchange="subirArchivo();"/></td>
                                                    <img src="" width="200"  />
                                                </tr>
                                                <tr>
                                                    <td><input type="submit" name="import" value="Aceptar" style="background-color: var(--verde_telefonica)" /></td>
                                                </tr>
                                            </table><br>
                                        </form>    
                                        <div id="contTablaCtoEdif" style="display:none" class="table-responsive">
                                            
                                        </div>
                                    </div>
                                </div>               
                            </div>
                        </div>
                    </div>
                </div>

            </section> 
        </main>

        <div class="modal fade" id="modalCantValorPorcentual" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Cantidad Kit</label>
                            <input type="text" id="cantidadKit" class="form-control" />
                            <span id="validaCantidadKit"></span>   
                        </div>
                        <div class="form-group">
                            <label>Factor Porcentual</label>
                            <input type="text" id="factorPorcentual" class="form-control" />
                            <span id="validaFactorPorcentual"></span>   
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" onclick="insertMaterial();">Aceptar</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalAlertaEliminar" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="background:red">
                        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                        <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                    </div>
                    <div class="modal-body">
                        <a>Al realizar esta acci&oacute;n se eliminar&aacute; este material del kit.</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success boton-acepto" onclick="eliminarMaterial();">Aceptar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

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
        <script src="<?php echo base_url();?>public/js/js_cotizaciones/js_carga_archivo_robot.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>


<script>
    $(document).ready(function () {
		$('#tbKitMaterial').DataTable({
			autoWidth:false,
			responsive:false,
                        aaSorting: [],
			lengthMenu:[[5,10,20,-1],["5 Rows","10 Rows","20 Rows","Everything"]],
	        language:{searchPlaceholder:"Buscar Material..."},
	        dom:"Blfrtip",
	        buttons:[{extend:"excelHtml5",title:"Export Data"},
	                 {extend:"csvHtml5",title:"Export Data"},
	                 {extend:"print",title:"Print"}],
	        initComplete:function(a,b){
	        	$(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')
	        	}
	        });		
	    $('#contTablaMaterial').css('display', 'block');
    });
</script>