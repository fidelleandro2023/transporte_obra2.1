<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

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
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.css" />
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
                            <a class="dropdown-item" href="#">Logout;</a>
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
                    <h2>REGISTRO DE ITEMFAULT</h2> 
                    <div class="card">

                        <div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#detDefiInstru" role="tab" id="aDetGene">DATOS GENERALES</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#detInci" role="tab" id="aDetInci">DATOS T&Eacute;CNICOS</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane active fade show" id="detDefiInstru" role="tabpanel">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>RED DE SERVICIO</label>
                                                <select id="selectServicio" name="selectServicio" class="select2 form-control" onchange="changeServicio()">
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    foreach ($servicio as $row) {
                                                        ?> 
                                                        <option value="<?php echo $row->idServicio ?>"><?php echo $row->servicioDesc ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>ELEMENTO DE RED DE SERVICIO</label>
                                                <select id="selectElementoServicio" name="selectElementoServicio" class="select2 form-control" onchange="changeElemento()">
                                                    <option value="">&nbsp;</option>
                                                </select>
                                            </div>
                                        </div>    
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>IDENTIFICACI&Oacute;N DE LA RED DE SERVICIO</label>                                                
                                                <div class="input-group">
                                                    <input disabled="true" name="identificacion_b" id="identificacion_b" type="text" required class="form-control" placeholder="" style="border-bottom: 1px solid lightgrey">
                                                    <span class="input-group-addon">-</span>
                                                    <input name="identificacion" id="identificacion" type="text" required class="form-control" placeholder="" style="border-bottom: 1px solid lightgrey">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-8 col-md-8" style="width: 800px;height: 500px">
                                            <div class="form-group" id="divMapaCoordenadasXY" style="width: 800px;height: 500px">
                                                <label>UBICACION DE LAS COORDENADAS X Y</label>
                                                <!--<input type="hidden" id="itemplan">-->
                                                <input id="pac-input" class="controls" type="text" placeholder="Buscar">
                                                <div id="divMapCoordenadas"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-4" >
                                            <div class="form-group">
                                                <label>AREA/GERENCIA</label>
                                                <select id="selectGerencia" name="selectSubproy" class="select2 form-control">
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    foreach ($gerencia as $row) {
                                                        ?> 
                                                        <option value="<?php echo $row->idGerencia ?>"><?php echo $row->gerenciaDesc ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-sm">
                                                        <div class="form-group">
                                                            <label>JEFATURA</label>
                                                            <input id="inputJefatura" name="inputJefatura" type="text" class="form-control" disabled="disabled">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm">
                                                        <div class="form-group">
                                                            <label>ZONAL</label>
                                                            <select id="selectZonal" name="selectZonal" class="select2 form-control"  disabled="disabled">
                                                                <option value="">&nbsp;</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="div_remedy">
                                                <label>REMEDY</label>
                                                <input id="remedy" name="remedy" type="text" class="form-control input-mask" placeholder="" autocomplete="off" maxlength="400" style="border-bottom: 1px solid lightgrey">
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group" id="div_central">
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




                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-sm">
                                                        <div class="form-group">
                                                            <label>NOMBRE DE URA</label>
                                                            <input id="inputNombrePlan" name="inputNombrePlan" type="text" class="form-control">
                                                        </div>  
                                                    </div>
                                                    <div class="col-sm">
                                                        <div class="form-group">
                                                            <label>ITEMPLAN VINCULADO</label>
                                                            <input  id="itemplan" name="itemplan" type="text" class="form-control">
                                                        </div>  
                                                    </div>
                                                </div>
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
                                        <div class="col-sm-8 col-md-8">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label>EVENTO</label>
                                                        <select id="selectEvento" name="selectEvento" class="select2 form-control" onchange="changeEvento()">
                                                            <option value="">&nbsp;</option>
                                                            <?php
                                                            foreach ($evento as $row) {
                                                                ?> 
                                                                <option value="<?php echo $row->idEvento ?>"><?php echo $row->EventoDesc ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label>CORTE DE SERVICIO</label>
                                                        <select id="selectCorte" name="selectCorte" class="select2 form-control">
                                                            <option value="">&nbsp;</option>
                                                            <?php
                                                            foreach ($corte as $row) {
                                                                ?> 
                                                                <option value="<?php echo $row->idCorteServicio ?>"><?php echo $row->corteServicio ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label>SUB EVENTO</label>
                                                        <select id="selectSubEvento" name="selectSubEvento" class="select2 form-control" onchange="changeAveria()">
                                                            <option value="">&nbsp;</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="row" id="div_averia">
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label>FECHA DE ORIGEN DE AVER&Iacute;A</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                            <div class="form-group">
                                                                <input id="inputFechaAveria" name="inputFechaAveria" type="text" class="form-control date-picker" placeholder="Pick a date">
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="form-group">
                                                        <label>HORA DE ORIGEN DE AVER&Iacute;A</label>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                            <div class="form-group">
                                                                <input id="inputHoraAveria" name="inputHoraAveria" type="text" class="form-control time-picker" placeholder="Pick a date">
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--//-->
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label>EMPRESA COLABORADORA</label>
                                                <select id="selectEmpresaColab" name="selectEmpresaColab" class="select2 form-control" >
                                                    <option value="">&nbsp;</option>

                                                </select>
                                            </div>                                        
                                        </div>  

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>MONTO MO</label>                                                
                                                <select id="inputMontoMO" name="inputMontoMO" class="select2 form-control" onchange="changeModal()">
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    foreach ($monto_mo as $row) {
                                                        ?> 
                                                        <option value="<?php echo $row->codigo ?>"><?php echo $row->descripcion ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-4" id="div_monto_mat">
                                            <div class="form-group">
                                                <label>MONTO MAT</label>
                                                <input maxlength="7" id="inputMontoMAT" class="form-control" type="text" placeholder="">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="detInci" role="tabpanel">
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>URA INICIAL</label>
                                                <input id="inputUraInicial" name="inputCoordX" type="text" class="form-control">
<!--                                                <select id="inputUraInicial" name="inputUraInicial" class="select2 form-control">
                                                    <option value="">&nbsp;</option>
                                                </select>-->
                                            </div>                                        
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>URA FINAL</label>
                                                <input id="inputUraFinal" name="inputCoordX" type="text" class="form-control">
<!--                                                <select id="inputUraFinal" name="inputUraFinal" class="select2 form-control">
                                                    <option value="">&nbsp;</option>
                                                </select>-->
                                            </div>                                        
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>OBSERVACI&Oacute;N</label>
                                                <textarea id="textObservacion" name="textObservacion" class="form-control" ></textarea>
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>C&Oacute;DIGO DE ODF INICIAL</label>
                                                <input id="inputCodigoInicial" name="inputCoordX" type="text" class="form-control">
                                            </div>                                        
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>C&Oacute;DIGO DE ODF FINAL</label>
                                                <input id="inputCodigoFinal" name="inputCoordX" type="text" class="form-control">
                                            </div>                                        
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>REGISTROS FOTOGR&Aacute;FICOS</label>
                                                <input id="inputImagenes" name="inputImagenes[]" type="file" class="file" multiple accept="image/*"
                                                       data-show-upload="false" data-show-caption="true" data-msg-placeholder="Select {files} for upload...">
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>NRO DE BANDEJA INICIAL</label>
                                                <input id="inputBandejaInicial" name="inputCoordX" type="text" class="form-control">
                                            </div>                                        
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>NRO DE BANDEJA FINAL</label>
                                                <input id="inputBandejaFinal" name="inputCoordX" type="text" class="form-control">
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>NRO DE FIBRA INICIAL</label>
                                                <input id="inputFibraInicial" name="inputCoordX" type="text" class="form-control">
                                            </div>                                        
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>NRO DE FIBRA FINAL</label>
                                                <input id="inputFibraFinal" name="inputCoordX" type="text" class="form-control">
                                            </div>                                        
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>MEDIDAS DE POTENCIA INICIAL</label>
                                                <input id="inputPotenciaInicial" name="inputCoordX" type="text" class="form-control">
                                            </div>                                        
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>MEDIDAS DE POTENCIA FINAL</label>
                                                <input id="inputPotenciaFinal" name="inputCoordX" type="text" class="form-control">
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12 col-md-12" style="text-align: center;">
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">                                      
                                    <button onclick="saveItemfault()" class="btn btn-primary">Guardar Datos</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal fade" id="modalPxQ" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title text-center">Colocar los datos PxQ (Precio por Cantidad) </h1>
                            </div>
                            <br>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>Precio unitario (P)</label>
                                            <input maxlength="6" id="inputPrecioU" class="form-control" type="text" placeholder="">

                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>Cantidad (Q)</label>
                                            <input maxlength="6" id="inputCantidad" class="form-control" type="text" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label>Total MO (PxQ)</label>
                                            <input maxlength="6" id="inputTotal" disabled="true" class="form-control" type="text" placeholder="">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button id="boton_multiuso"  class="btn btn-info" data-dismiss="modal">CONFIRMAR</button>
                            </div>
                        </div>
                    </div>
                </div>


                <footer class="footer hidden-xs-down">
                    <p>Telefonica del Peru</p>
                </footer>
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
        <script src="<?php echo base_url(); ?>public/bower_components/numeric/jquery.numeric.min.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.js"></script>
        <!--CODIGO PARA EL FILE IMPUT--> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-input/fileinput.min.css">
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-input/fileinput.js"></script>
        <!--CODIGO PARA EL FILE IMPUT--> 
        <script type="text/javascript">
                                        var Coordenadas = <?php echo $jsonCoordenadas ?>;
                                        var global_coord_x = null;
                                        var global_coord_y = null;
                                        var goblal_icon_url_terminado = '<?php echo base_url(); ?>public/img/iconos/itemplan.png';
                                        var goblal_icon_url_pendiente = '<?php echo base_url(); ?>public/img/iconos/itemplan.png';
                                        var global_marcadores = <?php echo json_encode($marcadores) ?>;
                                        var global_info_marcadores = <?php echo json_encode($info_markers) ?>;
                                        console.log("ENTRO 111");
        </script>

        <!-- JS -->         
        <script src="<?php echo base_url(); ?>public/js/js_itemfault/registro.js?v=<?php echo time(); ?>"></script>

        <!-- google maps -->
        <script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&libraries=places"></script>

    </body>


</html>