<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">

        <link rel="stylesheet"
            href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet"
            href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet"
            href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css"> 
            
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bootstrap-3.3.6-dist/css/bootstrap.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bootstrap-calendar-master/css/calendar.min.css?v=<?php echo time();?>"/>
        <!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/fullscreen-select/bootstrap-fullscreen-select.css?v=<?php echo time();?>"/> -->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/datetimepicker/css/bootstrap-material-datetimepicker.css?v=<?php echo time();?>">
		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/b_select/css/bootstrap-select.min.css?v=<?php echo time();?>">
		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bTable/bootstrap-table.min.css?v=<?php echo time();?>" >
		<!--link rel="stylesheet" href="http://bootstrap-table.wenzhixin.net.cn/assets/css/docs.min.css"-->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/datetimepicker/css/bootstrap-material-datetimepicker.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/handsontable/handsontable.full.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/mdl/css/material.indigo.min.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/mdl/css/material.min.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/floating-button/src/mfb.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/toaster/toastr.css?v=<?php echo time();?>">
    	<!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/pace/pace_css.css?v=<?php echo time();?>"> -->
        <!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/paper-collapse/paper-collapse.min.css?v=<?php echo time();?>"> -->
        <!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/treegrid-5a0511e/css/jquery.treegrid.css?v=<?php echo time();?>"> -->
        <!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/rippleria-master/css/jquery.rippleria.min.css?v=<?php echo time();?>"> -->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/offline-master/themes/offline-theme-chrome.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/offline-master/themes/offline-language-spanish.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/node_modules/angular-material/angular-material.min.css?v=<?php echo time();?>">
        <!-- <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.8/angular-material.min.css"> -->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/fonts/material-icons.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/fonts/Material-icons-new.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/fonts/font-awesome.min.css?v=<?php echo time();?>">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        
       
        <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/menu.css">   -->
        <style>
            input[type=number] { -moz-appearance:textfield; }
        </style>      
    </head>

    <body data-ma-theme="entel">  
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
        <!-- <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
            <main class='mdl-layout__content'>
                <div>       
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect opacity" data-toggle="tooltip" data-original-title="Agendar" onclick="openModalAgendamiento();">
                        <i class="fa fa-calendar" ></i>
                    </button>
                </div>
            </main>    
        </div>         -->
        <!-- <header class="mdl-layout__header mdl-layout__header__return is-casting-shadow">
            <div aria-expanded="false" role="button" tabindex="0" class="mdl-layout__drawer-button">
                <i class="mdi mdi-menu"></i>
            </div>
        </header> -->
        <section class="content content--full">
			<div class="content__inner">
				<h2 style="color: #333333d4;font-weight: 800;text-align: center;">Agendamiento</h2>
				<div class="">    
					<div class="card" style="background:#CEF6CE">
						<div class="mdl-card mdl-calendar">
							<div class="mdl-card__title">
								<h2 class="mdl-card__title-text" id="fechaCalendar"></h2>
								<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect opacity" data-toggle="tooltip" data-original-title="Agendar" data-placement="bottom" style="cursor:pointer" onclick="openModalAgendamiento();">
									<i class="fa fa-calendar" ></i>
								</button>
							</div>
							<div class="mdl-card__supporting-text br-b p-r-5 p-l-5">
								<div id="calendar" class="m-b-10"></div>
							</div>
							<div class="mdl-card__menu">
								<button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" data-calendar-nav="prev">
									<i class="mdi mdi-keyboard_arrow_left"></i>
								</button>
								<button class="mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" data-calendar-nav="next">
									<i class="mdi mdi-keyboard_arrow_right"></i>
								</button>
								<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-nav="today">Hoy</button>
								<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-view="month">Mes</button>
								<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-view="week">Semana</button>
								<button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-calendar-view="day">D&iacute;a</button>
								<button class="mdl-button mdl-js-button mdl-button--icon" data-button-type="menu" id="more-calendar">
									<i class="mdi mdi-more_vert"></i>
								</button>
								<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="more-calendar">
									<li class="mdl-menu__item" data-calendar-nav="today">Hoy</li>
									<li class="mdl-menu__item" data-calendar-view="month">Mes</li>
									<li class="mdl-menu__item" data-calendar-view="week">Semana</li>
									<li class="mdl-menu__item" data-calendar-view="day">D&iacute;a</li>
								</ul>
							</div>
						</div>
					</div>
				</div>    
			</div>
		</section>
        
        <div class="modal fade backModal" id="modalAgendamiento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
             <div class="modal-dialog modal-lg">
                 <div class="modal-content">
                     <div class="container">
                         <div class="mdl-card__title">
                             <h2 class="mdl-card__title-text">Agendamiento</h2>
                             <div class="col-md-3">
                                <i class="zmdi zmdi-hc-2x zmdi-time" data-toggle="tooltip" data-original-title="Banda Horaria" style="cursor:pointer" onclick="openModalMatrizPanel();"></i>                              
                             </div>
                         </div>
                         <div class="mdl-card__supporting-text">
                            <div class="form-group col-md-12"> 
                                <div class="col-md-4">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input id="contItemplan" name="contItemplan" class="mdl-textfield__input" maxlength="13" onkeyup="getDataFormulario()" /> 
                                        <label class="mdl-textfield__label" for="contItemplan">Itemplan</label>   
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mdl-textfield mdl-js-textfield">
                                        <label>Estado</label>
                                        <input type="text" id="estadoPlan" class="mdl-textfield__input" disabled/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mdl-textfield mdl-js-textfield">
                                        <label class="">Jefatura</label>
                                        <input id="contJefatura" class="mdl-textfield__input" disabled/>  
                                    </div>      
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-4">
                                    <div class="mdl-textfield mdl-js-textfield">
                                        <label>EECC</label>
                                        <input id="contEmpresaColab" class="mdl-textfield__input" disabled/>          
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mdl-textfield mdl-js-textfield">
                                        <label>Nombre Proyecto</label>
                                        <input type="text" id="nomProyecto" class="mdl-textfield__input" disabled/>           
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mdl-textfield mdl-js-textfield">
                                        <label>Banda horaria</label>   
                                        <input id="bandaHoraFec"  class="mdl-textfield__input" maxlength="9" disabled/> 
                                        <!-- <i class="zmdi zmdi-time" style="cursor:pointer" onclick="openModalMatrizPanel();"></i> -->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>SubProyecto</label>
                                        <input type="text" id="subProyecto" class="mdl-textfield__input" disabled/>     
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input id="nomContacto1" name="contItemplan" class="mdl-textfield__input" maxlength="50" /> 
                                            <label class="mdl-textfield__label" for="contItemplan">Nombre Contacto 1</label>   
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input id="telefContacto1" name="contItemplan" class="mdl-textfield__input" maxlength="9" /> 
                                            <label class="mdl-textfield__label" for="contItemplan">Telefono Contacto 1</label>   
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input id="nomContacto2" name="contItemplan" class="mdl-textfield__input" maxlength="50"  /> 
                                            <label class="mdl-textfield__label" for="contItemplan">Nombre Contacto 2</label>   
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input id="telefContacto2" name="contItemplan" class="mdl-textfield__input" maxlength="9" /> 
                                            <label class="mdl-textfield__label" for="contItemplan">Telefono Contacto 2</label>   
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div> 
                         <div class="mdl-card__actions">
                             <button id="botonFR" class="mdl-button mdl-button--colored mdl-js-button" type="button" data-dismiss="modal">Cerrar</button>
                             <button id="buttonDescargarBimestre" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised accept" onclick="openModalAceptarAgenda()">Aceptar</button>
                         </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="modalAlertaAceptacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                <div class="modal-header" style="background:red;height:70px">
                    <h5 class="modal-title" style="color:white">&#191;EST&Aacute; SEGURO DE REALIZAR ESTA ACCI&Oacute;N?</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                    <!-- <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body">
                    <a>Al registrar podr&aacute; consultar y modificar en el calendario</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="agendar();">Aceptar</button>
                </div>
                </div>
            </div>
        </div> 

        <div class="modal fade backModal" id="modalAvances" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="container">
                       <div class="mdl-card__title" id="mdl-title">
    				       <h2 class="mdl-card__title-text" id="titleCard" data-toggle="tooltip"  data-placement="bottom">Agendamiento:</h2>
    					   <h3 class="mdl-card__title-text" id="fechaAgenda"></h3>
    				   </div>
					   <div  id="contAgendamientoDetalle" class="table-responsive">
					        
    				   </div>

                       <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>     


        <div class="modal fade backModal" id="modalMatrizAgendamiento" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="container">
                       <div class="mdl-card__title" id="mdl-title">
    				       <h2 class="mdl-card__title-text" id="titleCard" data-toggle="tooltip"  data-placement="bottom">Banda Horaria y Fecha:</h2>
    					   <h3 class="mdl-card__title-text" id="fechaAgenda"></h3>
    				   </div>
					   <div  id="contMatrizAgendamiento" class="table-responsive">
					        
    				   </div>

                       <div class="mdl-card__actions">
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>     
        </div>   
        <script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <!-- <script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->
        <script src="<?php echo base_url(); ?>public/js/libs/bootstrap/bootstrap.min.js?v=<?php echo time();?>"></script>
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
        <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time();?>"></script>    

        <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>

        <script src="<?php echo base_url(); ?>public/plugins/bootstrap-calendar-master/components/underscore/underscore-min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/bootstrap-calendar-master/js/language/es-ES.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/bootstrap-calendar-master/js/calendar.js?v=<?php echo time();?>"></script>
        

        <!-- <script src="<?php echo base_url(); ?>public/js/libs/jquery/jquery-1.11.2.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/js/libs/jquery/jquery-migrate-1.2.1.min.js?v=<?php echo time();?>"></script> -->
        
        <!-- <script src="<?php echo base_url(); ?>public/plugins/jquery-ui/js/jquery-ui.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/b_select/js/bootstrap-select.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/b_select/js/i18n/defaults-es_CL.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/floating-button/src/mfb.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/bootstrap-tooltip/bootstrap_tooltip.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/toaster/toastr.js?v=<?php echo time();?>"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/plugins/bTable/bootstrap-table.min.js?v=<?php echo time();?>"></script>
        
        
        <script src="<?php echo base_url(); ?>public/plugins/moment/moment.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/datetimepicker/js/bootstrap-material-datetimepicker.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/pace/pace.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/inputmask/jquery.inputmask.bundle.min.js?v=<?php echo time();?>"></script>
        
        <script src="<?php echo base_url(); ?>public/plugins/datetimepicker/js/bootstrap-material-datetimepicker.js?v=<?php echo time();?>"></script>

        <script src="<?php echo base_url(); ?>public/plugins/highcharts/js/highstock.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/highcharts/js/highcharts-more.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/highcharts/js/modules/exporting.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/highcharts/js/modules/heatmap.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/paper-collapse/paper-collapse.min.js?v=<?php echo time();?>"></script>

        
        
        <script src="<?php echo base_url(); ?>public/plugins/jquery-mask/jquery.mask.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        
        <script src="<?php echo base_url(); ?>public/plugins/fullscreen-select/bootstrap-fullscreen-select.js?v=<?php echo time();?>"></script>
           
        <!-- <script src="<?php echo base_url(); ?>public/plugins/node_modules/angular/angular.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/node_modules/angular-animate/angular-animate.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/node_modules/angular-aria/angular-aria.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/node_modules/angular-material/angular-material.min.js?v=<?php echo time();?>"></script>
         -->
        <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time();?>"></script> 
        <script src="<?php echo base_url(); ?>public/plugins/mdl/js/material.min.js?v=<?php echo time();?>"></script>
 
         <script src="<?php echo base_url();?>public/js/js_agendamiento/jsAgendamiento.js?v=<?php echo time();?>"></script>

         <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-animate.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-aria.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-messages.min.js"></script>

        <!-- Angular Material Library -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.8/angular-material.min.js"></script> -->
<script>



$(document).ready(function() {
    // modal('modalAgendamiento');
    getCalendar();
	//insertPlanobra();
	// arrayDataItemplan = [{'cmbSubProyecto': 97, 'accion' : 1, 'txt_ruc' : '1', 'txt_nombre_constru': '-','txt_nombre_proyecto' : 'MORALES DUAREZ165',  'selectEstadoEdi' : 'NUEVO', 'txt_pisos' : '[5-7]', 'txt_avance' : 10, 'selectFase':5, 'coor_x' : '-76.97917226', 'coor_y' : '-12.10948704','distrito' : 'SAN BORJA', 'txtOperador' : 'Catherine Castillo'}]
	// arrayDataItemplan.forEach(function(data, index){
		// //insertPlanobraCVArray(data);
	// });
	
	    // arrayData = [{"id":"138315","clasificacion":"Estudio especial Gris",
		// "tipo_requerimiento":"Fibra Optica","tipo_proyecto":"Industria y Globales","sisego":"2017-11-21037",
		// "segmento":"Empresas","cliente":"IMPERIO'S OPERADORES LOGISTICOS S.A.",
		// "descripcion":"validar facilidades de FO  para la alta nueva de los siguientes servicios:\r\n\r\n- 10M IP-VPN\r\n-10M INFOINTERNET",
		// "servicios":"[{\"nombre\":\"Principal\",\"servicio\":\"Ip vpn\",\"velocidad\":\"10 \\u00a0Mbps\",\"interfaz\":\"Fast Ethernet\",\"tipo\":\"Nuevo\",\"cantidad\":\"1\"},{\"nombre\":\"Principal\",\"servicio\":\"Infointernet\",\"velocidad\":\"10 \\u00a0Mbps\",\"interfaz\":\"Fast Ethernet\",\"tipo\":\"Nuevo\",\"cantidad\":\"1\"}]","acceso_cliente":"Aereo","tendido_externo":"Mixto","tipo_cliente":"Edificio Monocliente","nro_pisos":"1","departamento":"Piura","provincia":"Piura","distrito":"Piura","direccion":"   EX PREDIO COSCOMBA 02 S\/N Z.I.I ZONA INDUSTRIAL ","piso":"-","interior":"-","latitud":"-5.16273867","longitud":"-80.71993043999998","nombre_estudio":"Estudio FO Principal","flg_principal":"0","enlace":"Principal","tipo_enlace":"TDP - Cliente"
		// }];
		// arrayData.forEach(function(data, index) {
			// insertCotizacion(data);
		// });
		
	    // arrayData = [
						// {sisego : '2019-08-136060-0',fecha_envio : '2020-01-21',subProyectoDesc : 'NEGOCIO',nombreProyecto :'AMERICAN SYSTEM PERU CORPORATION S.A.C.',tipo_cliente : 'Premium',tipo_diseno: 'Cluster',coordX :'-15.3620404446024',coordY: '-75.16229623143', nombre_estudio : 'Estudio FO Principal', acceso_cliente : 'Aereo',tendido_externo : 'Mixto',tipo_sede : 'Edificio Monocliente',per : 'PER-002267649'},
						// {sisego : '2019-08-138059-0',fecha_envio : '2020-01-21',subProyectoDesc : 'NEGOCIO',nombreProyecto :'RESTAURANT LAS TERRAZAS E.I.R.L.',tipo_cliente : 'Premium',tipo_diseno: 'Creaci',coordX :'-15.3726555502374',coordY: '-75.1610983345321', nombre_estudio : 'Estudio FO Principal', acceso_cliente : 'Aereo',tendido_externo : 'Mixto',tipo_sede : 'Edificio Monocliente',per : 'PER-002276185'},
						// {sisego : '2019-10-145809-0',fecha_envio : '2020-01-21',subProyectoDesc : 'NEGOCIO',nombreProyecto :'SINDICATO UNICO DE TRABAJADORES DE CONSTRUCCION CIVIL DE HOMBRES Y MUJERES DE MAR',tipo_cliente : 'Premium',tipo_diseno: 'Creaci',coordX :'-15.359795',coordY: '-75.160774', nombre_estudio : 'Estudio FO Principal', acceso_cliente : 'Aereo',tendido_externo : 'Mixto',tipo_sede : 'Edificio Monocliente',per : 'PER-002338915'}

						

					    // { sisego : '2019-01-95266-0', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'ENGIE SERVICES PERU S.A.', tipo_cliente : 'Energía y Recursos Naturales', tipo_diseno : 'Red Tradicional', coordX : '-9.8975064', coordY: '-76.9398195', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Canalizado', tendido_externo : 'Canalizado 100%', tipo_sede : '', per : 'PER-000755315'},
						
						//{ sisego : '2019-01-92965-1', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'BANCO DE CREDITO DEL PERU', tipo_cliente : 'Banca', tipo_diseno : 'Red Tradicional', coordX : '', coordY: '', nombre_estudio :'Estudio FO Respaldo', acceso_cliente : 'Canalizado', tendido_externo : 'Canalizado 100%', tipo_sede : 'Edificio Monocliente', per : ''},
						
						// { sisego : '2019-10-145946-1', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'MI BANCO, BANCO DE LA MICROEMPRESA S.A.', tipo_cliente : 'Banca', tipo_diseno : '', coordX : '-9.13935275894687', coordY: '-77.74683194601533', nombre_estudio :'Estudio NAP', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Edificio Monocliente', per : ''}
					// { sisego : '2019-10-146617-0', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'MI BANCO, BANCO DE LA MICROEMPRESA S.A.', tipo_cliente : 'Banca', tipo_diseno : 'NAP', coordX : '-9.72176233412412', coordY: '-77.4565454095397', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Edificio Monocliente', per : 'PER-002270015'},
					// { sisego : '2019-07-128921-0', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'EMPRESAS COMERCIALES S A', tipo_cliente : '', tipo_diseno : 'Creaci', coordX : '-14.07119823', coordY: '-75.72829765', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Canalizado', tendido_externo : 'Mixto', tipo_sede : 'Centro Comercial', per : 'PER-002092232'},
					// { sisego : '2019-06-127396-0', fecha_envio : '2020-01-16', subProyectoDesc :'NEGOCIO', nombreProyecto: 'SMARTFIT PERU S.A.C.', tipo_cliente : 'Alto Valor', tipo_diseno : 'Creaci', coordX : '-14.072774', coordY: '-75.737545', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Centro Comercial', per : 'PER-002192877'},
						
						//{ sisego : '2019-03-107385-1', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'EMPRESAS COMERCIALES S A', tipo_cliente : 'Proyecto Empresas', tipo_diseno : 'Creaci', coordX : '', coordY: '', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Canalizado', tendido_externo : 'Mixto', tipo_sede : 'Centro Comercial', per : ''}
					
					// { sisego : '2019-09-143096-0', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'CONECTA RETAIL S.A.', tipo_cliente : 'Industria y Globales', tipo_diseno : 'Creaci', coordX : '-13.4175404', coordY: '-76.1347505', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Edificio Monocliente', per : 'PER-2014118985'},
					// { sisego : '2019-09-143570-0', fecha_envio : '2020-01-16', subProyectoDesc :'NEGOCIO', nombreProyecto: 'JZG ABOGADOS E.I.R.L.', tipo_cliente : 'Alto Valor', tipo_diseno : 'Cluster', coordX : '-13.416897', coordY: '-76.130752', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Aereo', tipo_sede : 'Edificio Monocliente', per : 'PER-002305646'},
					// { sisego : '2019-09-142798-0', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'NEXXO SERVICIOS COMERCIALES S.A.C.', tipo_cliente : 'Proyecto Empresas', tipo_diseno : 'Creaci', coordX : '-13.4136142', coordY: '-76.1484004', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Canalizado', tendido_externo : 'Mixto', tipo_sede : 'Centro Comercial', per : 'PER-002315857'},
					// { sisego : '2019-08-137885-0', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'MAPFRE PERU COMPAÑIA DE SEGUROS Y REASEGUROS S.A.', tipo_cliente : 'Migraci', tipo_diseno : 'Cluster', coordX : '-13.711881', coordY: '-76.180931', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Edificio Monocliente', per : 'PER-002113983'},
					// { sisego : '2019-08-136718-1', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'MINERA MILPO S.A.', tipo_cliente : 'Energ', tipo_diseno : 'NAP', coordX : '', coordY: '', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Edificio Monocliente', per : ''},
					// { sisego : '2019-09-143260-0', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'CONECTA RETAIL S.A.', tipo_cliente : 'Migraci', tipo_diseno : 'Cluster', coordX : '-13.7115989951949', coordY: '-76.2041833527612', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Edificio Monocliente', per : 'PER-002113983'},
					// { sisego : '2019-07-132195-0', fecha_envio : '2020-01-16', subProyectoDesc :'EMPRESAS', nombreProyecto: 'TOPSA PERÚ S.A.C', tipo_cliente : 'Migraci', tipo_diseno : 'NAP', coordX : '-13.075079', coordY: '-76.373402', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Edificio Monocliente', per : 'PER-002113983'},
					// { sisego : '2019-10-147157-0', fecha_envio : '2020-01-16', subProyectoDesc :'NEGOCIO', nombreProyecto: 'INVERSIONES CONSTRUCTORA DAPDUL S.A.C.', tipo_cliente : 'Premium', tipo_diseno : 'Cluster', coordX : '-14.050017', coordY: '-75.70014', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Edificio Monocliente', per : 'PER-002347428'},
					// { sisego : '2019-10-147794-0', fecha_envio : '2020-01-16', subProyectoDesc :'NEGOCIO', nombreProyecto: 'DOMINIONPERU SOLUCIONES Y SERVICIOS S.A.C.', tipo_cliente : 'Alto Valor', tipo_diseno : 'Creaci', coordX : '-13.4278583', coordY: '-76.1601848999999', nombre_estudio :'Estudio FO Principal', acceso_cliente : 'Aereo', tendido_externo : 'Mixto', tipo_sede : 'Edificio Monocliente', per : 'PER-002347545'}
					// ]

		// arrayData.forEach(function(data, index) {
			// insertPlanObraSisego(data.sisego, data.fecha_envio, data.subProyectoDesc, data.nombreProyecto, data.tipo_cliente, data.tipo_diseno, data.nombre_estudio,
                // data.acceso_cliente, data.tendido_externo, data.tipo_sede, data.per, data.coordX, data.coordY);
		// });
		// arrayDataParalizacion = [];
	// arrayDataParalizacion = [	{ itemplan :'19-0310900711', idMotivo : 11, motivo : 'SIN PRESUPUESTO_(PEP)', origen : 1, comentario : 'PEDIDO' },
								// { itemplan :'19-0310900872', idMotivo : 11, motivo : 'SIN PRESUPUESTO_(PEP)', origen : 1, comentario : 'PEDIDO' },
								// { itemplan :'19-0310900912', idMotivo : 11, motivo : 'SIN PRESUPUESTO_(PEP)', origen : 1, comentario : 'PEDIDO' },
								// { itemplan :'19-0310900913', idMotivo : 11, motivo : 'SIN PRESUPUESTO_(PEP)', origen : 1, comentario : 'PEDIDO' },
								// { itemplan :'19-0311100782', idMotivo : 11, motivo : 'SIN PRESUPUESTO_(PEP)', origen : 1, comentario : 'PEDIDO' },
								// { itemplan :'19-0311100791', idMotivo : 11, motivo : 'SIN PRESUPUESTO_(PEP)', origen : 1, comentario : 'PEDIDO' }];
		
		// arrayDataParalizacion.forEach(function(data, index) {
			// insertPlanObraSisegoParalizacion(data.itemplan, data.idMotivo, data.motivo, data.origen, data.comentario);
		// });
		// arrayDataDesParalizacion = [];
	
		// arrayDataDesParalizacion =  [ 
									// { itemplan : '19-0311100960'}
									// ]
		// arrayDataDesParalizacion.forEach(function(data, index) {
			// revertirParalizacion(data.itemplan);
		// });
		
		// arrayCancelacion = [];
		// arrayCancelacion = [
			// { itemplan : '19-0310900749'},
			// { itemplan : '19-0311100930'},
			// { itemplan : '19-0320100327'},
			// { itemplan : '19-0320100328'},
			// { itemplan : '19-0320500526'},
			// { itemplan : '19-0320500531'},
			// { itemplan : '20-0310900068'},
			// { itemplan : '20-0310900087'},
			// { itemplan : '20-0310900095'},
			// { itemplan : '20-0310900098'}];
		
		// arrayCancelacion.forEach(function(data, index) {
			// enviarCancelacionSisego(data.itemplan);
		// });
		// enviarCreaItemplanSisego();
		
		// restaurarRobotVr();
		
	    var arrayDataRobotCvAlcance = [];
		arrayDataRobotCvAlcance = [{ itemplan : 1, longitud : -77.0829464, latitud : -12.0784186},
{ itemplan : 2, longitud : -77.032645, latitud : -12.047742},
{ itemplan : 3, longitud : -76.94808, latitud : -12.089697},
{ itemplan : 4, longitud : -77.005649, latitud : -12.007008},
{ itemplan : 5, longitud : -76.974139, latitud : -12.165874},
{ itemplan : 6, longitud : -77.025286, latitud : -12.113617},
{ itemplan : 7, longitud : -77.101464, latitud : -12.056539},
{ itemplan : 8, longitud : -77.079697, latitud : -12.012079},
{ itemplan : 9, longitud : -77.077041, latitud : -11.994825},
{ itemplan : 10, longitud : -77.0581299, latitud : -12.0939105},
{ itemplan : 11, longitud : -76.9994067, latitud : -12.0261623},
{ itemplan : 12, longitud : -77.055769, latitud : -12.027425},
{ itemplan : 13, longitud : -76.923844, latitud : -12.0849793},
{ itemplan : 14, longitud : -76.983196, latitud : -12.132963},
{ itemplan : 15, longitud : -76.991313, latitud : -12.147773},
{ itemplan : 16, longitud : -76.972083, latitud : -12.154411},
{ itemplan : 17, longitud : -76.972377, latitud : -12.108434},
{ itemplan : 18, longitud : -79.843894, latitud : -6.771297},
{ itemplan : 19, longitud : -79.037439, latitud : -8.12949},
{ itemplan : 20, longitud : -71.53283, latitud : -16.426395},
{ itemplan : 21, longitud : -77.011067, latitud : -12.179931},
{ itemplan : 22, longitud : -77.0357568, latitud : -11.8874338},
{ itemplan : 23, longitud : -77.009828, latitud : -11.993092},
{ itemplan : 24, longitud : -77.003557, latitud : -12.086691},
{ itemplan : 25, longitud : -76.949735, latitud : -12.033174},
{ itemplan : 26, longitud : -77.086623, latitud : -12.078343},
{ itemplan : 27, longitud : -77.071259, latitud : -11.990904},
{ itemplan : 28, longitud : -77.023018, latitud : -12.10721},
{ itemplan : 29, longitud : -76.996041, latitud : -12.08957},
{ itemplan : 30, longitud : -76.973175, latitud : -12.105387},
{ itemplan : 31, longitud : -77.058562, latitud : -11.989383},
{ itemplan : 32, longitud : -76.999604, latitud : -12.197281},
{ itemplan : 33, longitud : -77.065741, latitud : -12.072557},
{ itemplan : 34, longitud : -77.049322, latitud : -12.055185},
{ itemplan : 35, longitud : -77.097459, latitud : -12.063505},
{ itemplan : 36, longitud : -76.957996, latitud : -12.069325},
{ itemplan : 37, longitud : -77.016872, latitud : -12.081504},
{ itemplan : 38, longitud : -77.023414, latitud : -12.112227},
{ itemplan : 39, longitud : -77.048514, latitud : -12.10937},
{ itemplan : 40, longitud : -77.03838, latitud : -12.108905},
{ itemplan : 41, longitud : -76.9826718, latitud : -12.0747264},
{ itemplan : 42, longitud : -77.012105, latitud : -12.003629},
{ itemplan : 43, longitud : -77.0814609, latitud : -11.9772229},
{ itemplan : 44, longitud : -77.002592, latitud : -11.976219},
{ itemplan : 45, longitud : -77.048512, latitud : -12.066427},
{ itemplan : 46, longitud : -76.999522, latitud : -12.182349},
{ itemplan : 47, longitud : -73.245509, latitud : -3.751888},
{ itemplan : 48, longitud : -77.0585293, latitud : -12.1028997},
{ itemplan : 49, longitud : -77.03227, latitud : -12.092288},
{ itemplan : 50, longitud : -77.0178589, latitud : -12.1335967},
{ itemplan : 51, longitud : -77.04868555, latitud : -12.05491248},
{ itemplan : 52, longitud : -77.0210856, latitud : -12.1030544},
{ itemplan : 53, longitud : -76.9438648, latitud : -12.067314},
{ itemplan : 54, longitud : -74.53007, latitud : -8.384858},
{ itemplan : 55, longitud : -77.064752, latitud : -12.0779734},
{ itemplan : 56, longitud : -79.835384, latitud : -6.775533},
{ itemplan : 57, longitud : -79.0326, latitud : -8.102581},
{ itemplan : 58, longitud : -77.041781, latitud : -11.918998},
{ itemplan : 59, longitud : -75.726314, latitud : -14.0772762},
{ itemplan : 60, longitud : -78.530134, latitud : -9.123474},
{ itemplan : 61, longitud : -77.0230383, latitud : -12.0801583},
{ itemplan : 62, longitud : -71.5297828, latitud : -16.4302557},
{ itemplan : 63, longitud : -79.036009, latitud : -8.121135},
{ itemplan : 64, longitud : -76.99706376, latitud : -12.0828781},
{ itemplan : 65, longitud : -79.8423515, latitud : -6.7808166},
{ itemplan : 66, longitud : -71.549529, latitud : -16.399746},
{ itemplan : 67, longitud : -77.0975286, latitud : -12.0662439},
{ itemplan : 68, longitud : -79.842359, latitud : -6.7726317},
{ itemplan : 69, longitud : -77.0002288, latitud : -12.1126241},
{ itemplan : 70, longitud : -76.9720012, latitud : -12.1075836},
{ itemplan : 71, longitud : -79.85305, latitud : -6.770331},
{ itemplan : 72, longitud : -77.0026803, latitud : -12.0038419},
{ itemplan : 73, longitud : -77.0293844, latitud : -12.1270264},
{ itemplan : 74, longitud : -76.99499309, latitud : -12.10067049},
{ itemplan : 75, longitud : -76.9910395, latitud : -12.1151522},
{ itemplan : 76, longitud : -77.0827711, latitud : -11.976461},
{ itemplan : 77, longitud : -80.631205, latitud : -5.183618}
];
									
							
	// arrayDataRobotCvAlcance.forEach(function(data, index) {
		// getDataSimulacionCv(data.latitud, data.longitud, data.itemplan);
	// });
	
	// arrayCoordenadas = [
// { item : 1, latitud : -9.84903, longitud : -75.014014},
// { item : 2, latitud : -5.737173, longitud : -77.503891},
// { item : 3, latitud : -12.9926888, longitud : -74.7237248},
// { item : 4, latitud : -5.2010043, longitud : -80.63585},
// { item : 5, latitud : -11.3272888, longitud : -74.532797},
// { item : 6, latitud : -6.541027, longitud : -80.013212},
// { item : 7, latitud : -6.8649762, longitud : -79.8170729},
// { item : 8, latitud : -12.0055544, longitud : -76.8441236},
// { item : 9, latitud : -8.3989957, longitud : -74.5668857},
// { item : 10, latitud : -16.2132382, longitud : -69.4595906},
// { item : 11, latitud : -3.5607307, longitud : -80.4329252},
// { item : 12, latitud : -15.6362914, longitud : -71.6019521},
// { item : 13, latitud : -7.182813, longitud : -78.488437},
// { item : 14, latitud : -9.139378, longitud : -77.745763},
// { item : 15, latitud : -6.763224, longitud : -79.861474},
// { item : 16, latitud : -9.077211, longitud : -78.570013},
// { item : 17, latitud : -5.058221, longitud : -78.335153},
// { item : 18, latitud : -13.533165, longitud : -73.677161},
// { item : 19, latitud : -16.391092, longitud : -71.54317},
// { item : 20, latitud : -13.544576, longitud : -71.887349},
// { item : 21, latitud : -9.139378, longitud : -77.745763},
// { item : 22, latitud : -12.111473, longitud : -76.819506},
// { item : 23, latitud : -11.880449, longitud : -77.069115},
// { item : 24, latitud : -11.998068, longitud : -77.117917},
// { item : 25, latitud : -12.014289, longitud : -77.10199},
// { item : 26, latitud : -11.864063, longitud : -77.073026},
// { item : 27, latitud : -3.558439, longitud : -80.452577},
// { item : 28, latitud : -12.073609, longitud : -77.008779},
// { item : 29, latitud : -6.763224, longitud : -79.861474},
// { item : 30, latitud : -12.075035, longitud : -76.979591},
// { item : 31, latitud : -12.132115, longitud : -77.020324},
// { item : 32, latitud : -12.0466375, longitud : -77.015851},
// { item : 33, latitud : -12.241897, longitud : -76.923847},
// { item : 34, latitud : -12.252521, longitud : -76.897907},
// { item : 35, latitud : -11.914854, longitud : -77.048097},
// { item : 36, latitud : -12.162533, longitud : -76.995596},
// { item : 37, latitud : -3.737213, longitud : -73.259439},
// { item : 38, latitud : -13.160898, longitud : -74.226517},
// { item : 39, latitud : -8.073734, longitud : -79.05433},
// { item : 40, latitud : -5.058221, longitud : -78.335153},
// { item : 41, latitud : -12.123441, longitud : -77.017226},
// { item : 42, latitud : -12.213063, longitud : -76.936681},
// { item : 43, latitud : -12.167673, longitud : -76.920404},
// { item : 44, latitud : -13.533165, longitud : -73.677161},
// { item : 45, latitud : -11.980197, longitud : -77.071752},
// { item : 46, latitud : -13.507572, longitud : -71.996849}
// ];

// arrayCoordenadas.forEach(function(data, index) {
		// getDataSimulacionCv(data.latitud, data.longitud, data.itemplan);
	// });
});

function getCalendar() {
    $.ajax({
        type : 'POST',
        url  : 'getAgendamientosCalendar'
    }).done(function(data){
        try {
            data = JSON.parse(data);
            var options = {
					events_source : data,
					language      : 'es-ES',
					tmpl_path     : "public/plugins/bootstrap-calendar-master/tmpls/",
					onAfterViewLoad : function(view) {
						$('#fechaCalendar').text(this.getTitle());
						$('button.mdl-button').removeClass('active');
                        $('button.mdl-button[data-calendar-view="' + view + '"]').addClass('active');
						// $('li.mdl-menu__item').removeClass('active');
						// $('li.mdl-menu__item[data-calendar-view="' + view + '"]').addClass('active');
					},
                    modal      : "#modalAvances",
                    // modal_type : "ajax",
					ruta_js_metodo : 'public/js/js_agendamiento/jsAgendamiento.js',
					funcion_name : 'getDetalleAgendamiento'
				};
                var calendar = $('#calendar').calendar(options);
				$('button.mdl-button[data-calendar-nav], li.mdl-menu__item[data-calendar-nav]').each(function() {
					var $this = $(this);
					$this.click(function() {
						calendar.navigate($this.data('calendar-nav'));
					});
				});
				$('button[data-calendar-view], li.mdl-menu__item[data-calendar-view]').each(function() {
					var $this = $(this);
					$this.click(function() {
						calendar.view($this.data('calendar-view'));
					});
                });
        } catch(err) {
            location.reload();
        }
    });     
}

function insertCotizacion(DATAJSON) {


		 $.ajax({
			type : 'POST',
			url  : 'createCotiza',
			data : DATAJSON	
		 }).done(function(data){
			 data = JSON.parse(data);
		     console.log(data);
	
		 });
}


function insertPlanobra() {
    var selectProy    = 5;
    var selectSubproy = 467;
    var selectCentral = 638;
    var selectZonal   = 11;
    var selectEmpresaColab = 1;
    var selectEmpresaEle = 1;
    var selectFase       = 5;
    var inputIndicador = null;
    var inputFechaInicio = '2020-01-07';
    var inputNombrePlan  = 'Inventario Sirope';

    $.ajax({
        type : 'POST',
        url  : 'addPlanobra',
        data : { selectProy    : selectProy,
                 selectSubproy : selectSubproy,
                 selectCentral : selectCentral,
                 selectZonal   : selectZonal,
                 selectEmpresaColab : selectEmpresaColab,
                 selectEmpresaEle : selectEmpresaEle,
                 selectFase : selectFase,
                 inputIndicador : inputIndicador,
                 inputFechaInicio : inputFechaInicio,
                 inputNombrePlan : inputNombrePlan }
    });
}

function insertPlanObraSisego(sisego, fechaEnvio, subProyectoDesc, nombreCliente, tipo_cliente, tipo_diseno, nombre_estudio,
                             acceso_cliente, tendido_externo, tipo_sede, per, coordX, coordY) {
		$.ajax({
			type : 'POST',
			url  : 'cisisego',
			data : { sisego    : sisego,
					 envio : fechaEnvio,
					 segmento   : subProyectoDesc,
					 cliente : nombreCliente,
					 tipo_cliente : tipo_cliente,
					 tipo_diseno : tipo_diseno,
					 nombre_estudio : nombre_estudio,
					 acceso_cliente : acceso_cliente,
					 tendido_externo : tendido_externo,
					 tipo_sede : tipo_sede ,
					 per : per,
					 latitud : coordX,
					 longitud : coordY}
		}).done(function(data){
			data = JSON.parse(data);
			
			if(data.error == 0) {
				console.log("SE CREO ITEMPLAN");
			}else if(data.error == 1){
				mostrarNotificacion('error',data.msj);
			}
		});
}

function insertPlanobraCVArray(DATAJSON) {
    $.ajax({
        type : 'POST',
        url  : 'registroCvNegocio',
        data : DATAJSON
    }).done(function(data){
		data = JSON.parse(data);
		
		if(data.error == 0) {
			console.log("SE CREO ITEMPLAN");
		}else if(data.error == 1){
			mostrarNotificacion('error',data.msj);
		}
	});
}

function insertPlanObraSisegoParalizacion(itemplan, idMotivo, motivo, origen, comentario) {
	console.log("ENTRO");
	$.ajax({
        type : 'POST',
        url  : 'insertParalizacion',
        data : { itemplan    : itemplan,
                 idMotivo : idMotivo,
                 motivo : motivo,
                 origen   : origen,
                 comentario : comentario}
    });
}

function revertirParalizacion(itemplan) {
	$.ajax({
		type : 'POST',
		url  : 'revertirParalizacion',
		data : { itemplan : itemplan }
	}).done(function(data){
		data = JSON.parse(data);
		console.log(data);
	});
}

function enviarCancelacionSisego(itemplan) {
	$.ajax({
		type : 'POST',
		url  : 'cancelarItemplan',
		data : { itemplan : itemplan,
                 idEstadoPlan :	1 }
	});
}

function enviarCreaItemplanSisego() {
	$.ajax({
		type: 'POST',
		url : 'createPlanObraFromSisegoForzarCreacion',
		data : {"id":"123768","cliente":"TELXIUS CABLE PERU S.A.C.","sisego":"2020-04-161496-0","pep":"6079904000","envio":"2020-05-13","segmento":"Mayoristas"
				,"eecc":"","jefatura":"PUNO","region":"REGION SUR","mdf":"PUDE","tipo_diseno":"Habilitacion","tipo_requerimiento":"Estudio Especial Gris","nombre_estudio":"Estudio FO Principal",
				"duracion":"15","acceso_cliente":"Aereo","tendido_externo":"Aereo","tipo_sede":"Edificio Monocliente","tipo_cliente":"Prestadoras","latitud":"-16.566102","longitud":"-69.0379679",
				"pep2":"6079904000","grafo":"30300206","tipogasto":"CAPEX","seco":"PE-0021-2104-0161","cuenta":"210402","sinfix":"CL-010805"}
	}).done(function(data){
		data = JSON.parse(data);
		console.log("ENTRO");
		console.log(data);
	});
}

function restaurarRobotVr() {
	$.ajax({
		type: 'POST',
		url : 'restaurarRobotVr'
	}).done(function(data){
		data = JSON.parse(data);
		// El JSON a enviar
		for(var i=0; data.arrayJson.length >= i ; i++) {console.log("ENRTOEOOET");
			var myjson = data.arrayJson[i];
			var ajax_request = new XMLHttpRequest();
			ajax_request.open( "POST", 'updateValeReserva', true );
			// Establecer la cabecera Content-Type apropiada
			ajax_request.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
			// Enviar la solicitud
			ajax_request.send( myjson );
		}
	});
}

function getDataSimulacionCv(latitud, longitud, itemplan) {
	$.ajax({
		type : 'POST',
		url  : 'getDataSimulacionCv',
		data :  {
				 latitud       : latitud,
                 longitud      : longitud,
				 idSubProyecto : 13,
				 idCentral     : null,
				 clasificacion : 'ESTUDIO ESPECIAL GRIS',
				 tipo_cliente  : 'EDIFICIO MONOCLIENTE',
				 flg_log_robot : 1
				}
	}).done(function(dataRobot){
		console.log(JSON.parse(dataRobot));
		$.ajax({
					type : 'POST',
					url  : 'insertDataSimuladorCv',
					data :  { 
							 itemplan  : itemplan,
							 dataRobot : dataRobot
							}
				}).done(function(data){
					data = JSON.parse(data);
					console.log(data);
					// El JSON a enviar
					
				});
	});
}
</script>

    </body>
</html>

















