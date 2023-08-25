<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="public/img/iconos/iconfinder_movistar.png">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/dropzone/downloads/css/dropzone.css" />
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        <style>
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
                height: 450px;    
                width: 800px;  
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
        <script>



        </script>
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
                    <a href="https://www.movistar.com.pe/" title="Movistar"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Movistar" style="width: 36%; margin-left: -51%"></a>
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

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">View Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </div>

                    <ul class="navigation">

                        <?php echo $opciones ?>
                    </ul>
                </div>
            </aside>


            <section class="content content--full">
                <div id='textMensaje'>

                </div>

                <div class="content__inner">
                    <h2>REGISTRO DE ITEMPLAN OBRAS P&Uacute;BLICAS</h2> 
                    <div class="card">			                        
                        <div class="card-block"> 
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>PROYECTO</label>
                                            <input type="text" class="form-control" value="OBRAS PUBLICAS" disabled/>
                                        </div>
                                    </div>    
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>SUBPROYECTO</label>
                                            <select id="selectSubproy" name="selectSubproy" class="select2 form-control" onchange="getItemplanMadre()">
                                                <?php echo isset($cmbSubProyecto) ? $cmbSubProyecto : null; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="contItemMadre" class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>ITEMPLAN MADRE</label>
                                                <select id="cmbItemMadre" class="select2 form-control" onchange="getItemplanMadreHeredero()">
                                                    <?php echo isset($cmbItemplanMadre) ? $cmbItemplanMadre : $cmbItemplanMadre; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-2 col-md-2">
                                         <div class="form-group">
                                               <label>PLANIFICACI&Oacute;N</label>
                                               <select id="selectPlan" name="selectPlan" class="select2 form-control" >
                                                    <option value="">&nbsp;</option>
                                               </select>
                                        </div>
                                    </div> -->
                                    <div class="col-sm-8 col-md-8" style="width: 800px;height: 500px">
                                        <div class="form-group" id="divMapaCoordenadasXY" style="width: 800px;height: 500px">
                                            <label>UBICACION DE LAS COORDENADAS X Y</label>
                                            <input id="pac-input" class="controls" type="text" placeholder="Buscar">
                                            <div id="divMapCoordenadas"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>CENTRAL</label>
                                            <select id="selectCentral" name="selectCentral" class="select2 form-control" onchange="changueCentral()" disabled="disabled">
                                                <option value="">&nbsp;</option>
                                                <?php
                                                foreach ($listaTiCen->result() as $row) {
                                                    ?> 
                                                    <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>JEFATURA</label>
                                            <input id="inputJefatura" name="inputJefatura" type="text" class="form-control" disabled="disabled">
                                        </div>
                                        <div class="form-group">
                                            <label>ZONAL</label>
                                            <select id="selectZonal" name="selectZonal" class="select2 form-control"  disabled="disabled">
                                                <option value="">&nbsp;</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>EMPRESA COLABORADORA</label>
                                            <select id="selectEmpresaColab" name="selectEmpresaColab" class="select2 form-control" disabled="disabled">
                                                <option value="">&nbsp;</option>
                                            </select>
                                        </div>
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>COORDENADAS X</label>
                                            <input id="inputCoordX" name="inputCoordX" type="text" class="form-control" onchange="changeXY()"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                            <i class="form-group__bar"></i>
                                        </div>
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>COORDENADAS Y</label>
                                            <input id="inputCoordY" name="inputCoordY" type="text" class="form-control" onchange="changeXY()"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                            <i class="form-group__bar"></i>
                                        </div>

                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>EMPRESA ELECTRICA</label>
                                            <select id="selectEmpresaEle" name="selectEmpresaEle" class="select2 form-control" >
                                                <option>&nbsp;</option>
                                                <?php
                                                foreach ($listaeelec->result() as $row) {
                                                    ?> 
                                                    <option value="<?php echo $row->idEmpresaElec ?>"><?php echo $row->empresaElecDesc ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>                                        
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>INDICADOR (C&Oacute;DIGO &Uacute;NICO)</label>
                                            <input readonly="true"  id="inputIndicador" name="inputIndicador" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>NOMBRE DEL PLAN</label>
                                            <input id="inputNombrePlan" name="inputNombrePlan" type="text" class="form-control">
                                        </div>                                        
                                    </div>
                                    <div class="col-sm-4 col-md-4" id="contFecIni">
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>FECHA DE INICIO</label>            
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                <div class="form-group">
                                                    <input id="inputFechaInicio" name="inputFechaInicio" type="text" class="form-control date-picker" placeholder="Pick a date" onchange="recalcular_fecha_prev_ejec()">
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4" id="contFecPrev">
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>FECHA PREV.EJECUCION</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                <div class="form-group">
                                                    <input id="inputFechaPrev" name="inputFechaPrev" type="text" class="form-control date-picker" placeholder="Pick a date">
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>
                                        </div>                                                
                                    </div>
                                    <div class="col-sm-4 col-md-4" id="contKickoff" style="display: none;">
                                        <div class="form-group">
                                            <label>KICKOFF</label>
                                            <select id="selectKickOff" name="selectKickOff" class="select2 form-control" onchange="validateCoti()">
                                                <option value="0">NO</option>     
                                                <option value="1">SI</option>                                                    
                                            </select>
                                        </div>
                                    </div>    
                                    


                                    <div>
                                        <br/>
                                    </div>
                                    <div>
                                        <br/>
                                    </div>

                                    <div style="display: none" class="col-sm-4 col-md-4" id="divFactorMedicion" style="display: none">
                                        <div class="form-group">
                                            <label id="lblFactorMedicion"></label>
                                            <input id="inputCantidadFactorMedicion" name="inputCantidadFactorMedicion" value="1" type="number" class="form-control">
                                            <input type="hidden" id="hfIdFactorMedicion" name="hfIdFactorMedicion" value="0" >
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-md-4" id="divCoaxial" style="display: none">
                                        <div class="form-group">
                                            <label>FECHA PREV. DE ATENCION COAXIAL</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                <input placeholder="Pick a date" id="idFechaPreAtencionCoax" name="idFechaPreAtencionCoax" type="text" class="form-control date-picker">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4" id="divFO" style="display: none">
                                        <div class="form-group">
                                            <label>FECHA PREV. DE ATENCION FO</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                <input placeholder="Pick a date" id="idFechaPreAtencionFo" name="idFechaPreAtencionFo" type="text" class="form-control date-picker">
                                            </div>
                                        </div>
                                    </div>

                                    <div style="display: none;text-align: center;" id="contUploadFileCoti" class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label style="font-size: large;" for="fileupload" class="subir">
                                                <i class="zmdi zmdi-upload"></i>
                                            </label>
                                            <input id="fileupload" name="fileupload" type="file" onchange='cambiar()' style='display: none;'>
                                            <div id="info">Seleccione un archivo</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>FASE</label>
                                            <select id="selectFase" name="selectFase" class="select2 form-control" onchange="validarFasePorProyecto()">
                                                <option>&nbsp;</option>
                                                <?php
                                                foreach ($listafase->result() as $row) {
                                                    ?> 
                                                    <!--
                                                    <?php if ($row->faseDesc == '2019') {//SOLO FASE 2019 A PARTIR DEL 20.12.2018?>
                                                                <option value="<?php echo $row->idFase ?>"><?php echo $row->faseDesc ?></option>
                                                    <?php } ?>
                                                    -->
                                                    <?php if ($row->faseDesc == '2020') {//PEDIDO DE CINTHUA 21.11.2019?>
                                                        <option value="<?php echo $row->idFase ?>" selected><?php echo $row->faseDesc ?></option>
                                                    <?php } ?>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div style="display: none" id="contItemMadre" class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>ITEMPLAN MADRE</label>
                                                <select id="cmbItemMadre" class="select2 form-control">
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                    <!--    CONTENIDO OBRAS PUBLICAS    --> 

                                    
                                    <!-- FIN DE CONTENIDO OBRAS PUBLICAS     -->
                                    <div class="col-sm-12 col-md-12" style="text-align: center;">
                                        <div id="mensajeForm"></div>
                                    </div>  
                                    <div class="col-sm-12 col-md-12" style="text-align: center;">
                                        <div class="form-group" style="text-align: right;">
                                            <div class="col-sm-12">                                      
                                                <button id="btnSave" class="btn btn-primary">Guardar Datos</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>





                <footer class="footer hidden-xs-down">
                    <p>Telefonica del Peru</p>
                </footer>

                <input type="hidden" id="hfAdjudicacionAutomatica" name="hfAdjudicacionAutomatica" value="0" >
                <input type="hidden" id="hfHasCoaxial" name="hfHasCoaxial" value="0" >
                <input type="hidden" id="hfHasFo" name="hfHasFo" value="0" >
                <input type="hidden" id="hfpaquetizado_fg" name="hfpaquetizado_fg" value="1" >    
            </section>
        </main>

        <!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>  

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


        <script src="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
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
<script src="<?php echo base_url(); ?>public/js/js_pqt_plan_obra/js_pqt_plan_obra.js?v=<?php echo time(); ?>"></script>
        <!-- google maps -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&libraries=places&callback=init"></script>

    </body>


</html>