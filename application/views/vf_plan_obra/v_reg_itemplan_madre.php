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
            .size{
                width: 111px;
            }
            .subir{
                padding: 5px 10px;
                background: #f55d3e;
                color:#fff;
                border:0px solid #fff;    	     
                width: 40%;
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
                    <h2>REGISTRO ITEMPLAN MADRE</h2>
                    <hr>
                    <div class="card col-md-12 container"  align="center">		   				                           
                        <div class="card-block"> 
                            <div class="" align="center">
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>PROYECTO: </label>
                                        <input type="text" class="form-control" value="OBRAS PUBLICAS" disabled/>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>SUBPROYECTO: </label>
                                        <select id="cmbSubProyecto" class="select2">
                                            <option value="">Seleccionar SubProyecto</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>NOMBRE: </label>
                                        <input id="textNombreMadre" type="text" class="form-control" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>MONTO: </label>
                                        <input maxlength="6" id="textMonto" type="text" class="form-control" />

                                    </div>
                                    <div class="col-md-4 form-group">
                                        <input type="hidden" id="idpep">
                                        <input type="hidden" id="presupuesto_pep">
                                        <label>PRIORIDAD: </label>
                                        <select id="selectPrioridad" name="selectPrioridad" class="select2 form-control" >
                                            <option value="0">&nbsp;</option>
                                            <option value="1">SI</option>
                                            <option value="2">NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="form-group">
                                            <label style="font-size: large;" for="fileuploadOP" class="subir">
                                                <i class="zmdi zmdi-upload"></i>
                                            </label>
                                            <input id="fileuploadOP" name="fileuploadOP" type="file" onchange='cambiar2()' style='display: none;'>
                                            <div id="infoOP">Subir Carta (PDF) </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group">
                                        <div class="form-group" id="divMapaCoordenadasXY">
                                            <label>UBICACION DE LAS COORDENADAS X Y</label>
                                            <!--<input type="hidden" id="itemplan">-->
                                            <input id="pac-input" class="controls" type="text" placeholder="Buscar">
                                            <div id="divMapCoordenadas"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <div class="col-md-12 form-group">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <input type="hidden" id="selectCentral">
                                                <label>COORDENADAS X</label>
                                                <input disabled="true" id="inputCoordX" name="inputCoordX" type="text" class="form-control" onchange="changeXY()"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>COORDENADAS Y</label>
                                                <input disabled="true" id="inputCoordY" name="inputCoordY" type="text" class="form-control" onchange="changeXY()"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <div class="form-group">
                                                <label>EMPRESA COLABORADORA</label>
                                                <select id="selectEmpresaColab" disabled="true" name="selectEmpresaColab" class="select2 form-control" >
                                                    <option value="">&nbsp;</option>

                                                </select>
                                            </div> 
                                        </div>
                                    </div>

                                </div>
                                <div class="row">

                                </div>
                                <div id="contObrasPublicas" class="col-sm-12 col-md-12">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="form-group col-sm-4">
                                                    <label>DEPARTAMENTO</label>
                                                    <input id="txt_departamento" name="txt_departamento" type="text" class="form-control">                                                                      
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label>PROVINCIA</label>
                                                    <input id="txt_provincia" name="txt_provincia" type="text" class="form-control">                                                                      
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label>DISTRITO</label>
                                                    <input id="txt_distrito" name="txt_distrito" type="text" class="form-control">                                                                      
                                                </div>             

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
                                        </div>                            	
                                        <div style="margin-top: 15px;" class="col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label>FASE</label>
                                                        <select id="selectAno" name="selectAno" class="select2 form-control">
                                                            <option value="2020" selected="true">2020</option>                                                    
                                                        </select>
                                                    </div>
                                                </div>                                                     
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                <div class="col-md-3 form-group">
                                    <button class="btn btn-success" id="btnSaveAll" onclick="regItemPlanMadre();">Registrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </section>
        </main>

        <div id="modalAlerta" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="background:red">
                        <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                    </div>
                    <div class="modal-body">
                        <a>Al aceptar, se crear&aacute; la PO.</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success boton-acepto" onclick="generarPOGraf();">Aceptar</button>
                        <button type="button" class="btn btn-default boton-acepto" data-dismiss="modal">cancelar</button>
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
        <script type="text/javascript">
                            var Coordenadas = <?php echo $jsonCoordenadas ?>;
                            var global_coord_x = null;
                            var global_coord_y = null;
                            var goblal_icon_url_terminado = '<?php echo base_url(); ?>public/img/iconos/itemplan.png';
                            var goblal_icon_url_pendiente = '<?php echo base_url(); ?>public/img/iconos/itemplan.png';
                            var global_marcadores = null;
                            var global_info_marcadores = null;
                            console.log("ENTRO 111");
                            console.log(Coordenadas);
        </script>
        <script src="<?php echo base_url(); ?>public/js/js_planobra/js_reg_item_madre.js?v=<?php echo time(); ?>"></script>
        <script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&libraries=places"></script>  
    </body>

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>
