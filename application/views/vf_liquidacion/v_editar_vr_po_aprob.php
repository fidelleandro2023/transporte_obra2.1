<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
   
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta charset="UTF-8">
        <!-- Vendor styles -->
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
        <style>
            .select2-dropdown {
                z-index: 100000;
            }
            .fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}
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
                    <h2><?php echo $title ?></h2>
                    <div class="">    
                        <div class="card" style="">
                            <div class="card-block">
                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Itemplan</label>
                                            <input id="inputItemplan" type="text" class="form-control" maxlength="13" onkeyup="getComboPtr();">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>PTR</label>
                                            <select id="contCmbPtr" onchange="getVr();" class="select2 form-control">
                                               <option value="">Seleccionar Ptr</option>          
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>VR</label>
                                            <input id="contVr" type="text" class="form-control">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>EECC</label>
                                            <input id="contEmpresaColab" type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Jefatura</label>
                                            <input id="contJefatura" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Centro</label>
                                            <input id="contCentro" type="text" class="form-control" disabled>
                                        </div>                          
                                        <div class="form-group col-md-3">
                                            <label>Almacen</label>
                                            <input id="contAlmacen" type="text" class="form-control" disabled>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Estado Plan</label>
                                            <input id="contEstadoPlan" type="text" class="form-control" disabled>
                                        </div>

                                    </div>
                                    
             
                                    <div class="footer">
                                      <button class="btn-success col-md-1" onclick="updateVR()">Guardar</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>    
                </div>
            </section>


            
            
            <div class="modal fade" id="modalCodigoSolicitud" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <a>c&oacute;digo: </a>
                            <div id="codigo"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalKitMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">KIT MATERIAL</h5>
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                        <!-- <span aria-hidden="true">&times;</span>
                        </button> -->
                    </div>
                    <div class="modal-body">
                        <div id="contKitMaterial">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="insertKitMaterialSolicitud();">Aceptar</button>
                    </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
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

    <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
    <!-- Charts and maps-->
    <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/curved-line.js"></script>
    <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/line.js"></script>
    <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/chart-tooltips.js"></script>
    <script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>
    <script src="<?php echo base_url(); ?>public/demo/js/jqvmap.js"></script>

    <!-- App functions and actions -->
    <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

    <!--  -->
    <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time();?>"></script>    

    <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script>
    
<script>
    var idEmpresaColabGlobal = null;
    var idJefaturaGlobal     = null;
    var itemplanGlobal       = null;
    var vrGlobal             = null;

    function getComboPtr() {
        itemplanGlobal = $('#inputItemplan').val();

        if(itemplanGlobal.length != 13) {
            return;
        }

        $.ajax({
            type : 'POST',
            url  : 'getCmbPO',
            data : { itemplan : itemplanGlobal }
        }).done(function(data){
            data = JSON.parse(data);
            if(data.error == 0) {
                $('#contCmbPtr').html(data.cmbPtr);
                $('#contEmpresaColab').val(data.empresacolab);
                $('#contJefatura').val(data.jefatura);
                $('#contAlmacen').val(data.codAlmacen);
                $('#contCentro').val(data.codCentro);
                $('#contEstadoPlan').val(data.estadoPlan);

                idEmpresaColabGlobal = data.idEmpresaColab;
                idJefaturaGlobal     = data.idJefatura;
            } else {
                mostrarNotificacion('error', data.msj);
            }
        })
    }

    var ptr_estadoGlobal = null;
    var idEstacionGlobal = null;
    var flg_origenGlob = null;

    function getVr() {
        ptr_estadoGlobal = $('#contCmbPtr option:selected').val();
        idEstacionGlobal = $('#contCmbPtr option:selected').attr('data-id_estacion');
        flg_origenGlob = $('#contCmbPtr option:selected').attr('data-origen');

        if(ptr_estadoGlobal == '' || ptr_estadoGlobal == null) {
            return;
        }

        if(idEstacionGlobal == '' || idEstacionGlobal == null) {
            return;
        }

        $.ajax({
            type : 'POST',
            url  : 'getVRbyIPPO',
            data : { ptr        : ptr_estadoGlobal,
                    itemplan   : itemplanGlobal,
                    idEstacion : idEstacionGlobal }
        }).done(function(data){
            data = JSON.parse(data);
            if(data.error == 0) {
                $('#contVr').val(data.vr);
                vrGlobal = data.vr;
            } else {
                mostrarNotificacion('error', data.msj);
            }
        })
    }

    function updateVR(){

        var vr = $.trim($('#contVr').val());

        if (vr == null || vr == '' || vr == undefined) {
            mostrarNotificacion('error', 'Debe ingresar un vale de reserva!!');
            return;
        }
        swal({
            title: 'Esta seguro de realizar esta operacion??',
            text: 'Asegurese de validar la informacion!!',
            type: 'warning',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'OK!'

        }).then(function () {
            $.ajax({
                type: 'POST',
                'url': 'updateVRPO',
                data: {
                    itemplan: itemplanGlobal,
                    ptr: ptr_estadoGlobal,
                    idEstacion : idEstacionGlobal,
                    vr : vr,
                    flg_origen : flg_origenGlob
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    mostrarNotificacion('success', 'Se actualizo correctamente el vale reserva!!');
                } else {
                    mostrarNotificacion('error', data.msj);
                }
            });
        });

    }

</script>