<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/dropzone/downloads/css/dropzone.css" />
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css?v=<?php echo time();?>">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        <style>      
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

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">View Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </div>

                    <ul class="navigation">

						         <?php echo $opciones?>
                    </ul>
                </div>
            </aside>

         
            <section class="content content--full">           
                <div class="content__inner">
                    <h2>SOLICITUD USUARIO SIOM</h2>                                   
                    <div class="card">			                        
                        <div class="card-block">
                            <div class="tab-container tab-container--green">
                                <ul class="nav nav-tabs nav-fill" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tab_nuevo_usuario" role="tab">NUEVO USUARIO</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab_modif_anul" role="tab">MODIFICACI&Oacute;N / BAJA DE USUARIO</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active fade show" id="tab_nuevo_usuario" role="tabpanel">
                                        <form id="formNuevoUsuario" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                            <div class="row">
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>CONTRATO</label>
                                                        <select id="cmbContrato" name="cmbContrato" class="select2 form-control">
                                                        <?php echo isset($cmbContratos) ? $cmbContratos : NULL;?>
                                                        </select>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidContrato"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>EMPRESA</label>
                                                        <select id="cmbEmpresaColab" name="cmbEmpresaColab" class="select2 form-control">
                                                        <?php echo isset($cmbEmpresaColab) ? $cmbEmpresaColab : NULL;?>
                                                        </select>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidEmpresa"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>NOMBRE</label>
                                                        <input id="inputNombreU" name="inputNombreU" type="text" class="form-control" ><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidNombre"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>PERFIL</label>
                                                        <select id="cmbPerfil" name="cmbPerfil[]" class="select2 form-control" multiple>
                                                        <?php echo isset($cmbPerfil) ? $cmbPerfil : NULL;?>
                                                        </select>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidPerfil"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback">
                                                        <label>TIPO DOCUMENTO</label>
                                                        <select id="cmbTipoDoc" name="cmbTipoDoc" class="select2 form-control">
                                                            <option value="">Seleccionar</option>
                                                            <option value="1">DNI</option>
                                                            <option value="2">CE</option>
                                                        </select>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjTipoDoc"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>DNI/CE</label>
                                                        <input id="inputDni" name="inputDni" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidDni"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>CORREO ELECTR&Oacute;NICO</label>
                                                        <input id="inputCorreo" name="inputCorreo" type="text" placeholder="Ej.: usuario@servidor.com" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidCorreo"></div>
                                                    </div>
                                                </div>  
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>TEL&Eacute;FONO M&Oacute;VIL</label>
                                                        <input id="inputTelfMov" name="inputTelfMov" type="text" class="form-control" maxlength="9"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidTelf"></div>
                                                    </div>
                                                </div>  
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>IMEI</label>
                                                        <input id="inputImei" name="inputImei" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidImei"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>ZONA ALCANCE / RESPOSABILIDAD</label>
                                                        <select id="cmbZona" name="cmbZona[]" class="select2 form-control" multiple>
                                                        <?php echo isset($cmbZona) ? $cmbZona : NULL;?>
                                                        </select>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidZona"></div>
                                                    </div>
                                                </div>
                                                  
                                                <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                    <div class="form-group" style="text-align: center;">
                                                        <div class="col-sm-12">                                      
                                                            <button id="btnSave" type="submit" class="btn btn-primary">Aceptar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="tab_modif_anul" role="tabpanel">
                                        <p style="color:red;">Se debe seleccionar el tipo de solicitud e ingresar el DNI, para que se muestren los campos a modificar o dar de baja.</p>
                                        <form id="formModificarUsuario" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                            <div class="row">
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>DNI/CE</label>
                                                        <input id="inputDniM" name="inputDniM" type="text" class="form-control" onkeyup="getDataModificacion();"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidDniM"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <label>TIPO SOLICITUD</label>
                                                    <select id="cmbTipoSolicitud" name="cmbTipoSolicitud" class="select2 form-control">
                                                        <option value="">&nbsp;</option>
                                                        <option value="2">MODIFICACI&Oacute;N</option>
                                                        <option value="3">DAR DE BAJA</option>                                            
                                                    </select>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>CONTRATO</label>
                                                        <select id="cmbContratoM" name="cmbContratoM" class="select2 form-control" disabled>
                                                        <?php echo isset($cmbContratos) ? $cmbContratos : NULL;?>
                                                        </select>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidContratoM"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>EMPRESA</label>
                                                        <select id="cmbEmpresaColabM" name="cmbEmpresaColabM" class="select2 form-control">
                                                        <?php echo isset($cmbEmpresaColab) ? $cmbEmpresaColab : NULL;?>
                                                        </select>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidEmpresa"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>NOMBRE</label>
                                                        <input id="inputNombreUM" name="inputNombreUM" type="text" class="form-control" disabled><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidNombre"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>PERFIL</label>
                                                        <select id="cmbPerfilM" name="cmbPerfilM[]" class="select2 form-control" multiple>
                                                        <?php echo isset($cmbPerfil) ? $cmbPerfil : NULL;?>
                                                        </select>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidPerfilM"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>CORREO ELECTR&Oacute;NICO</label>
                                                        <input id="inputCorreoM" name="inputCorreoM" type="text" placeholder="Ej.: usuario@servidor.com" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidCorreo"></div>
                                                    </div>
                                                </div>  
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>TEL&Eacute;FONO M&Oacute;VIL</label>
                                                        <input id="inputTelfMovM" name="inputTelfMovM" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidTelfM"></div>
                                                    </div>
                                                </div>  
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>IMEI</label>
                                                        <input id="inputImeiM" name="inputImeiM" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidImei"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group has-feedback" style="">
                                                        <label>ZONA ALCANCE / RESPOSABILIDAD</label>
                                                        <select id="cmbZonaM" name="cmbZonaM[]" class="select2 form-control" multiple>
                                                        <?php echo isset($cmbZona) ? $cmbZona : NULL;?>
                                                        </select>
                                                        <i class="form-group__bar"></i>
                                                        <div id="msjValidZonaM"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <label>SITUACI&Oacute;N</label>
                                                    <select id="cmbSituacion" name="cmbSituacion" class="select2 form-control">
                                                        <option value="">&nbsp;</option>
                                                        <option value="1">ACTIVO</option>
                                                        <option value="0">DESACTIVADO</option>                                            
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 col-md-12">
                                                    <label>OBSERVACI&Oacute;N</label>
                                                    <textarea id="inputComentario" name="inputComentario" class="form-control"></textarea>
                                                </div>
                                                <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                    <div class="form-group" style="text-align: center;">
                                                        <div class="col-sm-12">                                      
                                                            <button id="btnGuardarModif" type="submit" class="btn btn-primary">Aceptar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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

        <!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>  
       
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

        
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
              
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>        
        <script src="<?php echo base_url();?>public/js/js_usuario_siom/js_form_solicitud_usuario.js?v=<?php echo time();?>"></script>
    </body>
</html>