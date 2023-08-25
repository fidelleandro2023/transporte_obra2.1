<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
    <head><meta http-equiv="Content-Type" content="text/html; charset=shift_jis">


        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.css" />

        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>

        <style>
            .content__inner:not(.content__inner--sm) {
                max-width: 100% !important;
            }
            .select2-dropdown{
                z-index:9001;
            }
            .size{
                width: 111px;
            }
            .subir{
                padding: 5px 10px;
                background: #f55d3e;
                color:#fff;
                border:0px solid #fff;    	     
                width: 100%;
                border-radius: 25px;
            }

            .subir:hover{
                color:#fff;
                background: #f7cb15;
            }
            #divMapCoordenadas{
                height: 250px;    
                width: 600px;  
            }

            #pac-input {
                background-color: #fff;
                font-family: Roboto;
                font-size: 15px;
                font-weight: 300;
                margin-left: 12px;
                padding: 0 11px 0 13px;
                text-overflow: ellipsis;
                width: 400px;
            }
            @media (min-width: 768px) {
                .modal-xl {
                    width: 90%;
                    max-width:1200px;
                }
            }
            .custom-radio, .custom-checkbox {
                clip: rect(1px 1px 1px 1px);
                clip: rect(1px, 1px, 1px, 1px);
                position: absolute;
            }

            /*
             * Dejar espacio a la 'label' para posicionar el checkbox hecho con pseudoelementos
             */
            .custom-radio + label, .custom-checkbox + label {
                position: relative;
                padding-left: 16px;
            }
            /*
             * El pseudoelemento que emulará el input
             */
            .custom-radio + label:before, .custom-checkbox + label:before {
                content: "";
                display: inline-block;
                -moz-box-sizing: border-box;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
                font-weight: bold;
                font-size: 10px;
                width: 13px;
                height: 13px;
                line-height: 11px;
                text-align: center;
                position: absolute;
                left: 0;
                top: 50%;
                margin-top: -6.5px;
                background: white;
                background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #ffffff), color-stop(100%, #dddddd));
                background-image: -webkit-linear-gradient(#ffffff, #dddddd);
                background-image: -moz-linear-gradient(#ffffff, #dddddd);
                background-image: -o-linear-gradient(#ffffff, #dddddd);
                background-image: linear-gradient(#ffffff, #dddddd);
                zoom: 1;
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#dddddd);
                -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#dddddd')";
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                -ms-border-radius: 3px;
                -o-border-radius: 3px;
                border-radius: 3px;
                border: 1px solid #aaa;
            }
            /*
             * Fondo para cuando se pasa el ratón por encima
             */
            .custom-radio + label:hover:before, .custom-checkbox + label:hover:before {
                background: #fafafa;
            }

            /*
             * Fondo para cuando se está haciendo click
             * Con filtros para ie9
             */
            .custom-radio + label:active:before, .custom-checkbox + label:active:before {
                background: #f2f2f2;
                background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #dddddd), color-stop(100%, #ffffff));
                background-image: -webkit-linear-gradient(#dddddd, #ffffff);
                background-image: -moz-linear-gradient(#dddddd, #ffffff);
                background-image: -o-linear-gradient(#dddddd, #ffffff);
                background-image: linear-gradient(#dddddd, #ffffff);
                zoom: 1;
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#dddddd, endColorstr=#ffffff);
                -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#dddddd', endColorstr='#ffffff')";
            }

            /*
             * Redondear el botón "radio"
             * Sobreescribimos el border-radius: 3px general
             */
            .custom-radio + label:before {
                -webkit-border-radius: 50%;
                -moz-border-radius: 50%;
                -ms-border-radius: 50%;
                -o-border-radius: 50%;
                border-radius: 50%;
            }
            /*
             * Mostrar un punto cuando está seleccionado el "radio"
             * Usamos box-shadow para simular un fondo gris, mientras que dejamos un pequeño 
             * espacio para el punto negro (#444), que es el fondo
             */
            .custom-radio:checked + label:before {
                background: #444;
                -webkit-box-shadow: 0 0 0 3px #eeeeee inset;
                -moz-box-shadow: 0 0 0 3px #eeeeee inset;
                box-shadow: 0 0 0 3px #eeeeee inset;
            }

            /*
             * Estilos focus para la gente que navega con el teclado, etc
             */
            .custom-radio:focus + label:before,
            .custom-checkbox:focus + label:before {
                outline: 1px dotted;
            }

            /* Mostrar la "X" cuando está chequeada (sólo el checkbox).
             * Podríamos usar una fuente de iconos para mostrar un tic
             */
            .custom-checkbox:checked + label:before {
                content: "X";
            }


            /*
             * Sólo para IE 6, 7 y 8 (no soportado)
             */
            @media \0screen\,screen\9 {
                .custom-radio,
                .custom-checkbox {
                    clip: auto;
                    position: static;
                }

                .custom-radio + label,
                .custom-checkbox + label {
                    padding-left: 0;
                }

                .custom-radio + label:before,
                .custom-checkbox + label:before {
                    display: none;
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
                    <a href="https://www.movistar.com.pe/" title="MOVISTAR"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo MOVISTAR" style="width: 36%; margin-left: -51%"></a>
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

            <section class="content content--full">
                <div class="content__inner">
                    <h2>BANDEJA DE ITEMPLAN HIJOS OBSERVADOS</h2>
                    <hr>

                    <div class="card col-md-12 container"  align="center">

                        <div class="card-block"> 
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>ITEMPLAN MADRE</label>
                                        <input maxlength="15" id="inputItemplanMadre" class="form-control" type="text" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <br>
                                        <button class="btn btn-success waves-effect" onclick="busqItemplanMadre()">CONSULTAR</button>
                                    </div> 
                                </div>
                                <!--                                <div class="col-sm-4 col-md-4">
                                                                    <div class="form-group">
                                                                        <br>
                                                                        <a class="btn btn-success" href="getItemplanMadre" target="_blank">Nuevo Itemplan Madre</a>
                                                                    </div> 
                                                                </div>-->

                            </div>
                            <div id="contTablaItems" class="table-responsive">
                                <?php echo isset($tablaItemMadre) ? $tablaItemMadre : null; ?>
                            </div>
                        </div>
                    </div>
                </div>    
            </section>
        </main>

        <div class="modal fade" id="modalItemplanHijos"  tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 style="margin: auto" class="modal-title" id="titel_hijos"><b>Detalle Itemplan Hijos</b></h2>
                    </div>
                    <div class="modal-body">
                        <div id="contTablaItemsHijos" class="table-responsive">
                            <?php echo isset($tablaItemHijos) ? $tablaItemHijos : null; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" data-dismiss="modal">Cerrar</button>
                    </div>      
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEditarConPrioridad"  tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 style="margin: auto" class="modal-title">Editar carta de Itemplan Madre</h3>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 form-group" align="center">


                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label>FEC. RECEPCION</label>
                                    <input id="fecRecepcion" name="fecRecepcion" type="text" class="form-control date-picker">                                         
                                </div>

                                <div class="form-group col-sm-4">
                                    <label>NOMBRE CLIENTE</label>
                                    <input id="inputNomCli" name="inputNomCli" type="text" class="form-control">                                                                      
                                </div> 
                                <div class="form-group col-sm-4">
                                    <label>NUMERO DE CARTA</label>
                                    <input id="inputNumCar" name="inputNumCar"  type="text" class="form-control">                                                                      
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-8">
                                    <div class="form-group">
                                        <div class="col-md-12 form-group">
                                            <label>NOMBRE DE ITEMPLA MADRE </label>
                                            <input id="textNombreMadre" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <input type="hidden" id="itemplanMadre" >
                                        <input type="hidden" id="carta_pdf" >
                                        <input type="hidden" id="idSubProyecto" >
                                        <label style="font-size: large;" for="fileuploadOP" class="subir">
                                            <i class="zmdi zmdi-upload"></i>
                                        </label>
                                        <input id="fileuploadOP" name="fileuploadOP" onchange='cambiar2()' type="file" style='display: none;'>
                                        <div class="text-center" id="infoOP">Actualizar Carta (PDF) </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="NoPrioritario">
                                <div class="col-md-8 form-group">
                                    <label>MONTO: </label>
                                    <input maxlength="9" id="textMonto" type="text" class="form-control" />

                                </div>
                                <div class="col-md-4 form-group">
                                    <input type="hidden" id="idpep">
                                    <input type="hidden" id="presupuesto_pep">
                                    <label>PRIORIDAD: </label>
                                    <select style="z-index: 1" id="selectPrioridad" name="selectPrioridad" class="select2 form-control" >
                                        <option value="0">&nbsp;</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" id="btnUpdateCP">Actualizar</button>
                        <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>      
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalVali" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-center" id="title_vali"></h1>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Comentario</label>
                                    <textarea class="col-sm-12 col-md-12" id="textComentario" name="textComentario"  rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="boton_multiuso"  class="btn btn-success">Aceptar</button>
                        <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

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

        <script src="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>


        <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <!-- Charts and maps-->
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/curved-line.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/line.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/chart-tooltips.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/jqvmap.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/numeric/jquery.numeric.min.js"></script>


        <!--  -->
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>

        <script src="https://www.w3schools.com/lib/w3.js"></script>

        <script src="<?php echo base_url(); ?>public/js/js_itempla_madre/js_bandeja_observacion.js?v=<?php echo time(); ?>"></script> 
    </body>

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>
