<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">

        <!-- Demo -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/demo/css/demo.css">
        
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css">
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
                <h2>ACTUALIZAR REPORTES OFF LINE</h2>
              <div class="card">
                
                
                        <div class="card-block">	   				                         
                                <div class="row">
                              
                          <div class="col-sm-12 col-md-12" style="text-align: center;">
  
 
                                    <section>
                                     <label style="font-size: smaller;text-align: center;">-La actualizacion de Reportes OFF LINE de la WEB PO es un proceso que requiere de un tiempo estimado de 20 a 30 minutos por favor espere y no cierre la ventana hasta que aparezca el aviso de confirmacion, de demorar mas de 30 minutos comunicarse con Soporte. Evite realizar acciones en la web PO durante la actualizacion de los Archivos.</label><br>
                                    <br><br>
                                    
<div id="contProgres">
                            <div class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                <span id="valuePie" class="easy-pie-chart__value">0</span>
                            </div>

                        </div>
                                     <form id="subida">
                                        <table style="margin: auto;">
                                            <tr>
                                                <td><button id="btnGenerarArchivos" class="btn btn-success waves-effect"  style="background-color: var(--verde_telefonica);" type="button" onclick="generarArchivosCSV()">Actualizar Reportes</button></td>
                                            </tr>
                                            <tr>
                                            	<td id="respuesta"></td>
                                            </tr>
                                        </table>
                                    </form>                               
	                         </section>
	                         </div>
	                        </div>
	                        
	                    </div>
                        
                    </div>
                   
                </div>
                
            </section>
        </main>

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
        <!-- Vendors -->
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <script src="<?php echo base_url();?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url();?>public/jquery.sparkline/jquery.sparkline.min.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <!-- Demo -->
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time();?>"></script>  

        <script type="text/javascript">
        
        
        var errorGlob = 1;

$(function () {
    $('#subida').submit(function () {
        var comprobar = $('#csv').val().length;
        if (comprobar > 0) {
            var file = $('#csv').val()
            var ext = file.substring(file.lastIndexOf("."));
            if (ext != ".txt") {
                mostrarNotificacion('error', 'Error', 'Formato de archivo no v\u00E1lido. El formato correcto es .TXT');
                return false;
            } else {
                var formulario = $('#subida');
                var archivos = new FormData();
                var url = 'up1';
                for (var i = 0; i < (formulario.find('input[type=file]').length); i++) {
                    archivos.append((formulario.find('input[type="file"]:eq(' + i + ')').attr("name")), ((formulario.find('input[type="file"]:eq(' + i + ')')[0]).files[0]));
                }
                $('.easy-pie-chart').data('easyPieChart').update('5');
                $('#valuePie').html(5);
                console.log('va entrar al ajax: '+url);
                $.ajax({
                    url: url,
                    type: 'POST',
                    contentType: false,
                    data: archivos,
                    processData: false,
                    success: function (data) {
                        data = JSON.parse(data);
                        console.log(data);
                        if (data.error == 0) {
                            console.log('termino correctamente el upload1');
                            $('.easy-pie-chart').data('easyPieChart').update('20');
                            $('#valuePie').html(20);
                            errorGlob = data.error;
                            if (errorGlob == 0) {
                                $.ajax({
                                    type: 'POST',
                                    url: 'up2'
                                }).done(function (data) {
                                    data = JSON.parse(data);
                                    if (data.error == 0) {
                                        console.log('termino correctamente el upload2');
                                        $('.easy-pie-chart').data('easyPieChart').update('30');
                                        $('#valuePie').html(30);
                                        errorGlob = data.error;
                                        if (errorGlob == 0) {
                                            $.ajax({
                                                type: 'POST',
                                                url: 'up3'
                                            }).done(function (data) {
                                                data = JSON.parse(data);
                                                if (data.error == 0) {
                                                    console.log('termino correctamente el upload3');
                                                    $('.easy-pie-chart').data('easyPieChart').update('40');
                                                    $('#valuePie').html(40);
                                                    errorGlob = data.error;
                                                    if (errorGlob == 0) {
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'up4'
                                                        }).done(function (data) {
                                                            data = JSON.parse(data);
                                                            if (data.error == 0) {
                                                                console.log('termino correctamente el upload4');
                                                                $('.easy-pie-chart').data('easyPieChart').update('50');
                                                                $('#valuePie').html(50);
                                                                errorGlob = data.error;
                                                                if (errorGlob == 0) {
                                                                    $.ajax({
                                                                        type: 'POST',
                                                                        url: 'up5'
                                                                    }).done(function (data) {
                                                                        data = JSON.parse(data);
                                                                        if (data.error == 0) {
                                                                            console.log('termino correctamente el upload5');
                                                                            $('.easy-pie-chart').data('easyPieChart').update('60');
                                                                            $('#valuePie').html(60);
                                                                            errorGlob = data.error;
                                                                            if (errorGlob == 0) {
                                                                                $.ajax({
                                                                                    type: 'POST',
                                                                                    url: 'up8'
                                                                                }).done(function (data) {
                                                                                    data = JSON.parse(data);
                                                                                    if (data.error == 0) {
                                                                                        console.log('termino correctamente el upload8');
                                                                                        $('.easy-pie-chart').data('easyPieChart').update('70');
                                                                                        $('#valuePie').html(70);
                                                                                        errorGlob = data.error;
                                                                                        if (errorGlob == 0) {
                                                                                            $.ajax({
                                                                                                type: 'POST',
                                                                                                url: 'up6'
                                                                                            }).done(function (data) {
                                                                                                data = JSON.parse(data);
                                                                                                if (data.error == 0) {
                                                                                                    console.log('termino correctamente el upload6');
                                                                                                    $('.easy-pie-chart').data('easyPieChart').update('80');
                                                                                                    $('#valuePie').html(80);
                                                                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Termino con Existo la actualizacion de datos...</label>');
                                                                                                    $('#btnIniTransfeWU').css('display', 'none');
                                                                                                    $('#btnGenerarArchivos').css('display', 'block');
                                                                                                } else {
                                                                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                                    return false;
                                                                                                }

                                                                                            });
                                                                                        } else {
                                                                                            mostrarNotificacion('error', 'Error', data.msj);
                                                                                        }
                                                                                    } else {
                                                                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                        return false;
                                                                                    }

                                                                                });
                                                                            } else {
                                                                                mostrarNotificacion('error', 'Error', data.msj)
                                                                            }

                                                                        } else {
                                                                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                            return false;
                                                                        }

                                                                    });
                                                                } else {
                                                                    mostrarNotificacion('error', 'Error', data.msj)
                                                                }

                                                            } else {
                                                                $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                return false;
                                                            }

                                                        });
                                                    } else {
                                                        mostrarNotificacion('error', 'Error', data.msj);
                                                    }

                                                } else {
                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                    return false;
                                                }

                                            });
                                        } else {
                                            mostrarNotificacion('error', 'Error', data.msj);
                                        }
                                    } else {
                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                        return false;
                                    }
                                });
                            }
                        } else {
                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                            return false;
                        }
                    }
                })

                return false;
            }
        } else {
            mostrarNotificacion('error', 'Error', 'Selecciona un archivo txt para importar!!');
            return false;
        }
    });

});


        
function generarArchivosCSV() {
    console.log('entro al boton para generar archivos CSV');
    $('.easy-pie-chart').data('easyPieChart').update('85');
    $('#valuePie').html(85);
    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Empezo la generacion de los archivos...</label>');

    $.ajax({
        type: 'POST',
        url: 'up7'
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            console.log('termino correctamente el upload7');
            $('.easy-pie-chart').data('easyPieChart').update('90');
            $('#valuePie').html(90);
            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Se termino la carga de archivos...</label>');
            errorGlob = data.error;
                 if (errorGlob == 0) {
                     $.ajax({
                         type: 'POST',
                         url: 'up9'
                     }).done(function (data) {
                         data = JSON.parse(data);
                         if (data.error == 0) {
                             console.log('termino correctamente el up9');
                             $('.easy-pie-chart').data('easyPieChart').update('95');
                             $('#valuePie').html(95);
                             $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Generando ultimo archivo Detalleplan...</label>');
                             errorGlob = data.error;
                             if (errorGlob == 0) {
                                 $.ajax({
                                     type: 'POST',
                                     url: 'up10'
                                 }).done(function (data) {
                                     data = JSON.parse(data);
                                     if (data.error == 0) {
                                         console.log('termino correctamente el up10');
                                         $('.easy-pie-chart').data('easyPieChart').update('97');
                                         $('#valuePie').html(96);
                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Se termino la carga de archivos...</label>');
                                         errorGlob = data.error;
                                         if (errorGlob == 0) {
                                        	 $.ajax({
                                                 type: 'POST',
                                                 url: 'up20'
                                             }).done(function (data) {
                                                 data = JSON.parse(data);
                                                 if (data.error == 0) {
                                                     console.log('termino correctamente el up20');
                                                     $('.easy-pie-chart').data('easyPieChart').update('98');
                                                     $('#valuePie').html(97);
                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Se termino la carga de archivos...</label>');
                                                     errorGlob = data.error;
                                                    if (errorGlob == 0) {
                                                    	 $.ajax({
                                                             type: 'POST',
                                                             url: 'cargaMo'
                                                         }).done(function (data) {
                                                             console.log('aqui1');
                                                             data = JSON.parse(data);
                                                             if (data.error == 0) {
                                                                 $('.easy-pie-chart').data('easyPieChart').update('99');
                                                                 $('#valuePie').html(98);
                                                                $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Se termino la carga de archivos...</label>');
                                                                 errorGlob = data.error;
                                                                 if (errorGlob == 0) {
                                                                	 $.ajax({
                                                                         type: 'POST',
                                                                         url: 'cargaMat'
                                                                     }).done(function (data) {
                                                                    	 console.log('aqui antes sisego pep');
                                                                         data = JSON.parse(data);
                                                                         if (data.error == 0) {
                                                                             $('.easy-pie-chart').data('easyPieChart').update('100');
                                                                             $('#valuePie').html(99);
                                                                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Se termino la carga de archivos...</label>');
                                                                             errorGlob = data.error;
                                                                             if (errorGlob == 0) {
																				 $.ajax({
																						 type: 'POST',
																						 url: 'repDiagPEPSisego'
																					 }).done(function (data) {
																						 console.log('despues sisego pep');
																						 data = JSON.parse(data);
																						 if (data.error == 0) {
																							 $('.easy-pie-chart').data('easyPieChart').update('100');
																							 $('#valuePie').html(100);
																							$('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Se termino la carga de archivos...</label>');
																							 errorGlob = data.error;
																							 if (errorGlob == 0) {
																								 swal({
																									 title: 'Se realizo correctamente la transferencia de WU!!',
																									 text: 'Operacion existora!',
																									 type: 'success',
																									 buttonsStyling: false,
																									 confirmButtonClass: 'btn btn-primary',
																									 confirmButtonText: 'OK!'
																								}).then(function () {
																									 location.reload();
																								 });
																							 } else {
																								 mostrarNotificacion('error', 'Error', data.msj);
																							 }
																					  } else {
																							 $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
																							 return;
																						 }
																					 });
                                                                             } else {
                                                                                 mostrarNotificacion('error', 'Error', data.msj);
                                                                             }
                                                                      } else {
                                                                             $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                             return;
                                                                         }

                                                                     });
                                                                 } else {
                                                                     mostrarNotificacion('error', 'Error', data.msj);
                                                                 }
                                                          } else {
                                                                 $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                 return;
                                                             }

                                                         });
                                                     } else {
                                                         mostrarNotificacion('error', 'Error', data.msj);
                                                     }
                                              } else {
                                                     $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                     return;
                                                 }

                                             });
                                         } else {
                                             mostrarNotificacion('error', 'Error', data.msj);
                                         }
                                  } else {
                                         $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                         return;
                                     }

                                 });
                             } else {
                                 mostrarNotificacion('error', 'Error', data.msj);
                             }

                         } else {
                             $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                             return;
                         }
                     });

                 } else {
                     mostrarNotificacion('error', 'Error', data.msj);
                 }
            } else {
                $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                return;
            }
    });
}
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
</html>