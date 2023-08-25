<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo NAME_WEB_PO; ?></title>
        <meta charset="ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=paquetizado1.0, minimum-scale=1.0">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="<?php echo COLOR_BARRA_ANDROID_SIST_AVA ?>">
        <link rel="icon" href="<?php echo IMG_MOVISTAR_CABECERA; ?>">

        <link rel="shortcut icon" type="image/png" href="<?php //echo FAVICON_SIST_AV; ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>bootstrap-3.3.6-dist/css/bootstrap.min.css?v=<?php echo time(); ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>b_select/css/bootstrap-select.min.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>bTable/bootstrap-table.min.css?v=<?php echo time(); ?>" >
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>floating-button/src/mfb.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>toaster/toastr.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>pace/pace_css.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>bootstraptour/css/bootstrap-tour.min.css?v=<?php echo time(); ?>"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>bootstraptour/css/bootstrap-tour-standalone.min.css?v=<?php echo time(); ?>"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>mdl/css/material.indigo.min.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>mdl/css/material.min.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>rippleria-master/css/jquery.rippleria.min.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>offline-master/themes/offline-theme-chrome.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_PLUGINS ?>offline-master/themes/offline-language-spanish.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS ?>mdl-card-style.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS ?>roboto.css?v=<?php echo time(); ?>"/>
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS ?>material-icons.css?v=<?php echo time(); ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_FONTS ?>Material-icons-new.css?v=<?php echo time(); ?>" />
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS ?>menu.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS ?>m-p.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS ?>logic/main.css?v=<?php echo time(); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo RUTA_CSS ?>logic/mural.css?v=<?php echo time(); ?>">
        <!-- <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons"> -->
        <style>
            .mdl-layout-title button{
                display: none;
            }

            /*PDF UTILES*/
            .pdf {
                width: 100%;
                height: 700px;
                max-width: 800px;
                margin:auto;
            }

            object {
                height: 100%;
                left: 0;
                top:0;
                width: 100%;
            }
        </style>

    </head>
    <body>
        <div class='mdl-layout mdl-js-layout mdl-layout--fixed-header'>
            <?php echo $menu ?>
            <main class='mdl-layout__content'>
                <section class="mdl-layout__tab-panel p-0 is-active" id="tab-1">

                </section>


                <!-- Vista para  Padres -->
                <section class="mdl-layout__tab-panel p-0" id="tab-2">
                    <a href="http://www.movistar.com.pe/home" target="_blank">
                        <div class="mdl-header-init" title="Movistar" style="text-align:center;"></div>
                    </a>
                    <div class="mdl-content-cards" id="sortable">
                        <?php
                        if (isset($tb)) {
                            echo $tb;
                        } else {
                            ?>

                            
                             <?php if(!$this->session->userdata('isPerfilModMant')){ ?>
                            <div class="mdl-card mdl-app_content" id="card-main-1" title="Nuevo Modelo">
                                <div class="mdl-card__supporting-text mdl-card__front inhr_overflow data-rippleria data-rippleria-duration" onclick="getRoute('transporte');">
                                    <img src="<?php echo RUTA_IMG ?>iconsSistem/transporte_3.jpg">
                                    <h1></h1>
                                    <div class="mdl-app_text">
                                        <label>Transporte</label>
                                        <i class="mdi mdi-'.$app_icon.'"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mdl-card mdl-app_content" id="card-main-1" title="cap">
                                <div class="mdl-card__supporting-text mdl-card__front inhr_overflow data-rippleria data-rippleria-duration" onclick="getRoute('modcap');">
                                    <img src="<?php echo RUTA_IMG ?>iconsSistem/cap.jpg">
                                    <h1></h1>
                                    <div class="mdl-app_text">
                                        <label>CAP</label>
                                        <i class="mdi mdi-'.$app_icon.'"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mdl-card mdl-app_content" id="card-main-1" title="Administrativo">
                                <div class="mdl-card__supporting-text mdl-card__front inhr_overflow data-rippleria data-rippleria-duration" onclick="getRoute('modAdministrativo');">
                                    <img src="<?php echo RUTA_IMG ?>iconsSistem/diseno.jpg">
                                    <h1></h1>
                                    <div class="mdl-app_text">
                                        <label>Administrativo</label>
                                        <i class="mdi mdi-'.$app_icon.'"></i>
                                    </div>
                                </div>
                            </div>
                            <?php  } ?>
                           
                            <?php
                        }
                        ?>

                    </div>
                </section>

                <section class="mdl-layout__tab-panel" id="<?php //echo MURAL_PUBLICO;?>">
                    <div class="mdl-content-cards">
<?php //echo $publicacionesPublicas; ?>
                    </div>
                </section>
                <section class="mdl-layout__tab-panel p-0" id="tab-4">
                    <div class="mdl-grid">
                        <div class="mdl-cell mdl-cell--3-col">

                            <div class="mdl-card__title">
                                <h1 class="mdl-card__title-text">WORKFLOW</h1>
                            </div>
                            <div class="mdl-card__supporting-text">
                                <video class="mdl-cell mdl-cell--12-col" controls>
                                    <source src="<?php echo RUTA_VIDEO . 'workflow_obs.mp4'; ?>" type="video/mp4">
                                </video>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <!-- <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Read More</a> -->
                                <!-- <div class="mdl-layout-spacer"></div>
                                <button class="mdl-button mdl-button--icon mdl-button--colored"><i class="material-icons">favorite</i></button>
                                <button class="mdl-button mdl-button--icon mdl-button--colored"><i class="material-icons">share</i></button> -->
                            </div>
                        </div>
                        <div class="mdl-cell mdl-cell--3-col">

                            <div class="mdl-card__title">
                                <h1 class="mdl-card__title-text">CONSULTA PRESUPUESTO</h1>
                            </div>
                            <div class="mdl-card__supporting-text">
                                <video class="mdl-cell mdl-cell--12-col" controls>
                                    <source src="<?php echo RUTA_VIDEO . 'consulta_ip_con_presupuesto.mp4'; ?>" type="video/mp4">
                                </video>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <!-- <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Read More</a> -->
                                <!-- <div class="mdl-layout-spacer"></div>
                                <button class="mdl-button mdl-button--icon mdl-button--colored"><i class="material-icons">favorite</i></button>
                                <button class="mdl-button mdl-button--icon mdl-button--colored"><i class="material-icons">share</i></button> -->
                            </div>
                        </div>                
                        <div class="mdl-cell mdl-cell--3-col">

                            <div class="mdl-card__title">
                                <h1 class="mdl-card__title-text">REGISTRO SOLICITUD CAP</h1>
                            </div>
                            <div class="mdl-card__supporting-text">
                                <video class="mdl-cell mdl-cell--12-col" controls>
                                    <source src="<?php echo RUTA_VIDEO . 'proceso_cap.mp4'; ?>" type="video/mp4">
                                </video>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <!-- <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Read More</a> -->
                                <!-- <div class="mdl-layout-spacer"></div>
                                <button class="mdl-button mdl-button--icon mdl-button--colored"><i class="material-icons">favorite</i></button>
                                <button class="mdl-button mdl-button--icon mdl-button--colored"><i class="material-icons">share</i></button> -->
                            </div>
                        </div>
						<div class="mdl-cell mdl-cell--3-col">

                            <div class="mdl-card__title">
                                <h1 class="mdl-card__title-text">GENERACI&Oacute;N ORDEN COMPRA (17/07/2020)</h1>
                            </div>
                            <div class="mdl-card__supporting-text">
                                <video class="mdl-cell mdl-cell--12-col" controls>
                                    <source src="<?php echo RUTA_VIDEO . 'generacion_orden_compra.mp4'; ?>" type="video/mp4">
                                </video>
                            </div>
							<div class="mdl-card__actions mdl-card--border">
                                <!-- <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Read More</a> -->
                                <!-- <div class="mdl-layout-spacer"></div>
                                <button class="mdl-button mdl-button--icon mdl-button--colored"><i class="material-icons">favorite</i></button>
                                <button class="mdl-button mdl-button--icon mdl-button--colored"><i class="material-icons">share</i></button> -->
                            </div>
                        </div>
                    </div>

                </section>
				
				<section class="mdl-layout__tab-panel p-0" id="tab-5">
					 
						<div class="carga-modulo" id="divModuloLoad">
						
						</div>
					
                </section>
            </main>
        </div>

        <div class="modal fade backModal" id="modalRanking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">                		 
                    <div class="mdl-card">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Enviar Correo</h2>
                        </div>
                        <div class="mdl-card__supporting-text p-rl-0">
                            <div class="row-fluid">
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only p-b-0">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input " type="text" id="link">
                                        <label class="mdl-textfield__label" for="link">Asunto</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea rows="4" class="mdl-textfield__input form-control" id="leonel"></textarea>  
                                        <label class="mdl-textfield__label" for="contenido">Contenido</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mdl-card__actions">        			                     
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>           			                     
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" >Aceptar</button>    			                     
                        </div>
                    </div>
                </div>   
            </div>
        </div>


        <div class="modal fade" id="modalPDF" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card">
                        <div class="mdl-card__supporting-text p-0 br-b">
                            <img class="col-md-12 col-sm-12" src="<?php echo RUTA_NOTICIAS . 'info_vale_reserva_terminado.png'; ?>">
                        </div>
                        <div class="mdl-card__menu">
                            <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                <i class="mdi mdi-close" style="background:white"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade backModal" id="modalNuevaNoticia"
             tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="mdl-card mdl-card-fab" style="border-radius:5px;">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">Escriba su nueva publicaci&oacute;n</h2>   						        
                        </div>    			
                        <div class="mdl-card__supporting-text p-rl-0">
                            <div class="row-fluid p-0 m-0">  
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="inputNoticia">
                                        <input class="mdl-textfield__input" type="text" id="inputNoticiaDesc" name="inputNoticiaDesc" onchange="" onkeyup="">        
                                        <label class="mdl-textfield__label" for="inputNoticiaDesc">Nombre de la publicaci&oacute;n</label>                            
                                    </div>        			    
                                </div>                                
                                <div class="col-sm-12 mdl-input-group mdl-input-group__only">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <textarea class="mdl-textfield__input" type="text" id="contenidoPublicacion" name="contenidoPublicacion" rows="6" onchange=""></textarea>        
                                        <label class="mdl-textfield__label" for="observacionesColaboradorCrear">Contenido</label> 
                                        <span class="mdl-textfield__limit" for="observacionesColaboradorCrear" data-limit="100"></span>     
                                        <span class="mdl-textfield__error"></span>                        
                                    </div>
                                </div>     					    
                            </div>
                        </div>
                        <div class="mdl-card__actions">  
                            <button class="mdl-button mdl-js-button p-r-0 p-l-0 p-b-0 " style="border-radius:50%;height:36px;width:36px;min-width:0px;" onclick="elegirImagenNoticia()"><i class="mdi mdi-photo"></i></button>      			                     
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect " data-dismiss="modal">Cerrar</button>           			                     
                            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised" onclick="publicarNoticia()">Publicar</button>    			                     
                        </div>
                    </div>
                </div>
            </div>
        </div>

		 <div class="modal fade" id="modalDetTerminados" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="titulo" style="margin: auto;font-weight: bold;" class="modal-title">Obras Terminadas</h4>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                         </button>
                    </div>                             
                    <div class="modal-body">                                               
                        <div class="row"> 
                            <div id="contTablaTerminados" class="table-responsive">
                            </div>
                         </div>
                          <div class="row"> 
                            <div class="col-sm-12 col-md-12">
                                <div id="container"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>           



        <script src="<?php echo RUTA_JS ?>libs/jquery/jquery-1.11.2.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_JS ?>libs/jquery-ui/jquery-ui.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_JS ?>libs/jquery/jquery-migrate-1.2.1.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_JS ?>libs/autosize/jquery.autosize.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>b_select/js/bootstrap-select.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>b_select/js/i18n/defaults-es_CL.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>floating-button/src/mfb.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>toaster/toastr.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>pace/pace.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>rippleria-master/js/jquery.rippleria.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>mdl/js/material.min.js?v=<?php echo time(); ?>" defer></script>
        <script src="<?php echo RUTA_PLUGINS ?>bootstrap/js/bootstrap.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>bootstrapTour/js/bootstrap-tour.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>bootstrapTour/js/bootstrap-tour-standalone.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>urlLive/jquery.urlive.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>offline-master/offline.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>bTable/bootstrap-table.min.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>bTable/bootstrap-table-es-MX.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_PLUGINS ?>plax/plax.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_JS ?>Utils.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_JS ?>jslogic/jsmural.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo RUTA_JS ?>jslogic/jsmain.js?v=<?php echo time(); ?>"></script>
		<!--<script src="<?php echo base_url();?>public/highcharts/highcharts.js" charset="UTF-8"></script>-->
		
				<script type="text/javascript">

							$(document).ready(function () {
								$('[data-toggle="tooltip"]').tooltip();
								$(LAYOUT_CONTENT).addClass('is-closed');		
								//$("#divModuloLoad").load("repSisegosV");
							});

							var current_height = null;
							// setTimeout(function(){
							// 	current_height = $('.mdl-card.mdl-app_content.ui-sortable-handle').height();
							// },1000);

							//  scrollMenuOcultar();
							function getRoute(ruta) {
								if (ruta == 'transporte') {
									location.href = 'http://www.plandeobras.com/obra2.1/getModuloTransporte?nombre='+
									'<?php echo $this->session->userdata('usernameSession');?>'+'&perfil='+'<?php echo $this->session->userdata('idPerfilSession');?>'
									+'&id_usuario='+'<?php echo $this->session->userdata('idPersonaSession');?>'+'&permisos='+'<?php echo $this->session->userdata('permisoMovilSession');?>';
								} else {
									location.href = ruta;
								}
							}			
						
						</script>
        <script type="text/javascript">
		
		
                                // $('.doodle').plaxify();
                                //$.plax.enable();
                                var RUTA_IMG = '<?php echo RUTA_IMG; ?>';
                                var abvr = 0;
								// modal('modalPDF'); MODAL NOTICIA
                                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
                                    $('#btnShowCalendar').css('display', 'inherit');
                                    $('.selectButton').selectpicker('mobile');
                                    abvr = 1;
                                    $('.mdl-card.mdl-dashboard.mdl-birthday .card-calendar').addClass('d-calendar');
                                } else {
                                    $('#btnShowCalendar').css('display', 'none');
                                    $('.selectButton').selectpicker();
                                    abvr = 0;
                                }
                                $("#filtroMain .breadcrumb li:NTH-CHILD(2)").text($("#cmbYearAulas").parent().find(".btn-default").attr("title"));
                                var tipoPrograma = '<?php echo isset($tipoPrograma) ? $tipoPrograma : null ?>';
                                changeIconBirthday();
                                activeAulaByEstudiante();
                                initLimitInputs('observacionPuntuacion');

                                showFabDashboard();
                                IconResponsive();
                                //	showTabMain($("a[href='"+localStorage.getItem('hrefLast')+"']"));
                                $(document).ready(function () {
                                    setTimeout(function () {
                                        $('#navBar').css({
                                            '-webkit-transform': 'translateX(-310px) !important',
                                            'transform': 'translateX(-310px) !important'
                                        });
                                    }, 200);
                                    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
                                        $('.selectButton').selectpicker('mobile');
                                    } else {
                                        $('.selectButton').selectpicker();
                                    }
                                })
                                $('#cmnbAcademiciNotas').css('display', 'block');
                                $('#lectivoNotasChart').css('display', 'block');
                                $('#flgDeudaLectivoNotas').css('display', 'none');
                                if (<?php echo isset($flg_deuda) ? $flg_deuda : 0; ?> > 0) {
                                    $('#cmnbAcademiciNotas').css('display', 'none');
                                    $('#lectivoNotasChart').css('display', 'none');
                                    $('#flgDeudaLectivoNotas').css('display', 'block');
                                }

                                setTimeout(function () {
                                    animar(1000);
                                }, 500);
                                $(function () {
                                    $("#sortable").sortable();
                                    $("#sortable").disableSelection();
                                });

                                $('.mdl-ranking .mdl-card').click(function () {
                                    var idRanking = $(this).attr('id');
                                    $('.mdl-ranking .mdl-card').removeClass('mdl-flipped');
                                    $('#' + idRanking).toggleClass('mdl-flipped');
                                }).mouseleave(function () {
                                    $('.mdl-ranking .mdl-card').removeClass('mdl-flipped');
                                });

                                moreText('comentario');


                                $(document).ready(function () {
                                    $(this).scrollTop(0);
                                });

                                $(window).scroll(function () {
                                    if ($(document).height() <= $(window).scrollTop() + $(window).height()) {
                                        loadmore();
                                    }
                                });

                                (function ($) {
                                    $.fn.clickToggle = function (func1, func2) {
                                        var funcs = [func1, func2];
                                        this.data('toggleclicked', 0);
                                        this.click(function () {
                                            var data = $(this).data();
                                            var tc = data.toggleclicked;
                                            $.proxy(funcs[tc], this)();
                                            data.toggleclicked = (tc + 1) % 2;
                                        });
                                        return this;
                                    };
                                }(jQuery));

                                var lastScrollTop = 0;
                                $(window).scroll(function (event) {
                                    var st = $(this).scrollTop();
                                    if (st > lastScrollTop) {//OCULTAR
                                        $("#menu").fadeOut();
                                    } else {
                                        if (st + $(window).height() < $(document).height()) {//MOSTRAR
                                            $("#menu").fadeIn();

                                        }
                                    }
                                    lastScrollTop = st;
                                });
                                var eve = 0;
                                function mostrarComentarios(id) {
                                    if (eve == 0)
                                    {
                                        $('#' + id).fadeIn();
                                        eve = 1;
                                    } else if (eve == 1)
                                    {
                                        $('#' + id).fadeOut();
                                        eve = 0;
                                    }
                                }

                                function darLike(corazon, idPubli) {
                                    $.ajax({
                                        data: {id: idPubli},
                                        url: 'c_mural/like',
                                        type: 'POST',
                                        async: false
                                    })
                                            .done(function (data) {
                                                if (!corazon.hasClass('active')) {
                                                    corazon.addClass('active');
                                                    corazon.parent().find('span.span-like').html(parseInt(corazon.parent().find('span.span-like').html()) + 1);
                                                } else {
                                                    corazon.removeClass('active');
                                                    corazon.parent().find('span.span-like').html(parseInt(corazon.parent().find('span.span-like').html()) - 1);
                                                }
                                            });
                                }
                                $(window).ready(function () {
                                    $('.mdl-layout__drawer').addClass('is-visible');
                                });

                                function moreText(clase) {
                                    var showChar = 100000000000;
                                    var ellipsestext = "...";
                                    var moretext = "more";
                                    var lesstext = "less";

                                    $('.' + clase).each(function () {
                                        var content = $(this).html();
                                        var id = $(this).attr('id');
                                        if (content.length > showChar) {

                                            var c = content.substr(0, showChar);
                                            var h = content.substr(showChar - 1, content.length - showChar);

                                            var html = c + '<span class="moreelipses">' + ellipsestext + '</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="seeMoreText(' + id + ')" class="morelink">' + moretext + '</a></span>';

                                            $(this).html(html);
                                        }
                                    });
                                }

                                function  seeMoreText(id) {
                                    if ($('#comentario' + id).find(this).hasClass("less")) {
                                        $('#comentario' + id).find(this).removeClass("less");
                                        $('#comentario' + id).find(this).html("more");
                                    } else {
                                        $('#comentario' + id).find(this).addClass("less");
                                        $('#comentario' + id).find(this).html("less");
                                    }
                                    $('#comentario' + id).find(this).parent().prev().toggle();
                                    $('#comentario' + id).find(this).prev().toggle();
                                    return false;
                                }
                                /*function showFab(tab){
                                 $.ajax({
                                 data  : {tab : tab},
                                 url   : 'c_main/createFab',
                                 type  : 'POST',
                                 async : false
                                 })
                                 .done(function(data){
                                 $('#menu').html(data);
                                 });
                                 
                                 
                                 }*/
								 
								 function cargarReporteSisego(){
									 $("#divModuloLoad").load("repSisegosV?from=1");
								 }
        </script>
        
    </body>
</html>