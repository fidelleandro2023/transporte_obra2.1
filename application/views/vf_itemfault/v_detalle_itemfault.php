<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <style>
            @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
              }
            }
            .select2-dropdown {
                z-index: 100000;
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
                <h2 class="text-center">DETALLE ITEMFAULT <strong id="itemTitle"><?php echo $item?></strong></h2>
                    <?php if($estadoItemplan == ID_ESTADO_TERMINADO) { ?>
                        <h1 class="text-center" style="color:red">(Itemplan Terminado)</h1>
                        <br>
                    <?php
                          }
                          if($from == '1'){
                            if( $estadoItemplan == ID_ESTADO_PLAN_EN_OBRA||  $estadoItemplan == ID_ESTADO_TRUNCO ||  $estadoItemplan == ID_ESTADO_DISENIO_EJECUTADO
                                   || $estadoItemplan == ID_ESTADO_PRE_LIQUIDADO || $estadoItemplan == ID_ESTADO_TERMINADO || $estadoItemplan ==  ID_ESTADO_DISENIO_PARCIAL){
                    ?>


                    <?php   }
                          }else if($from == '2'){
                            if( $estadoItemplan == ID_ESTADO_PRE_DISENIO||  $estadoItemplan == ID_ESTADO_DISENIO||  $estadoItemplan == ID_ESTADO_DISENIO_EJECUTADO){

                            }
                          }
                    ?>
                <br>
                <h4 class="text-center" style="color:red">Para el registro de PO de material debe hacer click en el area correspondiente. Ejm:"MAT_FO".<strong></strong></h4>
                <br>

                <div class="col-sm-6 col-md-12">
                    <div class="form-group">
                        <table id="datatable2" style="margin: 0 auto;">
                            <tbody>
                                <tr style="text-align: center;">
                                    <td>No Aprobado</td>
                                    <td>Aprobado</td>
                                    <td>Liquidado</td>
                                    <td>Validado</td>
                                    <td>Certificado</td>
                                    <td>Cancelado</td>
                                    <td>No tiene detalle</td>
                                </tr>
                                <tr height="5px">
                                    <td style="background-color:#FF0000" width="14.28%"></td>
                                    <td style="background-color:#1CDDC5" width="14.28%"></td>
                                    <td style="background-color:#78E900" width="14.28%"></td>
                                    <td style="background-color:#767680" width="14.28%"></td>
                                    <td style="background-color:#F7FA07" width="14.28%"></td>
                                    <td style="background-color: steelblue" width="14.28%"></td>
                                    <td style="background-color:#FFFFFF" width="14.28%"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row" id="itemEstaciones" >
                    <?php echo $listaEstaciones?>
                </div>
                
                <div id="contTabla" class="table-responsive" style="display:none">
                    <table id="data-table" class="table table-bordered">
                        <thead class="thead-default">
                            <tr>
                                <th style="text-align: center">#</th>
                                <th style="text-align: center">CODIGO</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center">asd</td>
                                <td style="text-align: center">asd</td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
        </section>
    </main>



    <div class="modal fade" id="modal-info" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left">Informaci&oacute;n</h5>
                </div>

                <div class="modal-body">
                    <div class="col-sm-12 col-md-12">
                        <div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#detallePO" role="tab">DETALLE PO</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#detalleLOG" role="tab">LOG PO</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#valereserva" role="tab">VALE RESERVA</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#partidas" role="tab">PARTIDAS</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#presupuesto" role="tab">PRESUPUESTO</a>
                                </li>
                            </ul>
                        </div><!-- fin tab container -->
                        <div class="tab-content">
                            <div class="tab-pane active fade show" id="detallePO" role="tabpanel">
                                <div class="row" id="infocontenido">                                            
                                    <!-- info -->   
                                </div>
                            </div>
                            <div class="tab-pane fade" id="detalleLOG" role="tabpanel">
                                <div class="tab-container">
                                    <div id="conTablaLog" class="table-responsive">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="valereserva" role="tabpanel">
                                <div class="tab-container">
                                    <div id="contTablaVR" class="table-responsive">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="partidas" role="tabpanel">
                                <div class="tab-container">
                                    <div id="contTablaPartidas" class="table-responsive">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="presupuesto" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="text-center" style="background-color: var(--celeste_telefonica); color: white; padding: 2px">SAP</h6>
                                        <div class="tab-container">
                                            <div id="contablaPresu" class="table-responsive"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- fin tab content -->
                    </div>
                </div><!-- fin de moda body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Ok</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                </div>
            </div>              
        </div>
    </div>
    

    <div class="modal fade" id="modalMotivoPrecancelacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ingrese el motivo de la Pre Cancelacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-group">
                            <label class="control-label">MOTIVO</label>
                            <select id="idSelectMotivo" name="responsable" class="select2 form-control">
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-12">
                        <div class="form-group">
                            <label class="control-label">OBSERVACION</label>
                            <input id="txtObservacion" type="text" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="preCancelarPO()">Aceptar</button>
            </div>
            </div>
        </div>
    </div>


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
    <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>

    <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>             

    <script type="text/javascript">
       
         //asdasdsd

        var poGlob = null;
        var itemfaultGlob = null;
        var idEstacionGlob = null;
        var idSubProyEstaGlob = null;
        var idSubProyectoGlob = null;

        function poDetalleItemfault(component) {console.log("ENTROOO!!!!!!!!");
            poGlob = $(component).attr('data-ptr');
            itemfaultGlob = $(component).attr('data-item');
            idEstacionGlob = $(component).attr('data-estacion');
      
            var idArea = $(component).attr('data-id_area');
            
			console.log(itemfaultGlob);
            $.ajax({
                type: 'POST',
                'url': 'poDetalleItemfault',
                data: {
                    codigo_po : poGlob,
                    itemfault : itemfaultGlob,
                    idEstacion: idEstacionGlob,
                    idArea    : idArea 
                },
                'async': false
            }).done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    $('#infocontenido').html(data.prueba);
                    initDataTable('#tabla_diseno_auto');
	
                    $('#conTablaLog').html(data.tablaLOG);
                    initDataTable('#tabla_log');

                    $('#contablaPresu').html(data.tablaPresu);
                    initDataTable('#table-presupuesto');

                    $('#contTablaPartidas').html(data.tablaPartidas);
                    initDataTable('#tabla_partidas');

                    $('#contTablaVR').html(data.tablaVR);
                    initDataTable('#tbValeReserva');
                    idSubProyEstaGlob = data.idSubProEsta;
                    modal('modal-info');
                } else {
                    mostrarNotificacion('error', 'Error', data.msj);
                }
            });

        }

        function openModalMotivoPreCancelacion() {

            if (poGlob != null && itemfaultGlob != null && idEstacionGlob != null && idSubProyEstaGlob != null) {
                $.ajax({
                    type: 'POST',
                    'url': 'getCmbMotPreCancela',
                    data: {
                        codigoPO: poGlob,
                        itemplan: itemfaultGlob,
                        idEstacion: idEstacionGlob,
                        idSubProyEsta: idSubProyEstaGlob
                    },
                    'async': false
                }).done(function (data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        if (data.flgAccion == 1) {
                            mostrarNotificacion('success', 'Operacion Exitosa', 'Se elimino correctamente la PO!!');
                            modal('modal-info');
                            location.reload();
                        } else if (data.flgAccion == 2) {
                            mostrarNotificacion('success', 'Operacion Exitosa', 'Se cancelo correctamente la PO!!');
                            modal('modal-info');
                            location.reload();
                        } else if (data.flgAccion == 3) {
                            $('#idSelectMotivo').html(data.comboMotivo);
                            modal('modalMotivoPrecancelacion');
                        }

                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }

                });
            }
        }

        function preCancelarPO() {
            var motivo = $.trim($('#idSelectMotivo').val());
            var observacion = $.trim($('#txtObservacion').val());

            if (itemfaultGlob != null && poGlob != null && idEstacionGlob != null) {

                if (motivo == null || motivo == '' || motivo == undefined || observacion == null || observacion == '' || observacion == undefined) {
                    mostrarNotificacion('error', 'Error', 'Debe ingresar el motivo y observacion!!');
                    return;
                }
                swal({
                    title: 'Está seguro de Pre cancelar el PO?',
                    text: 'Recuerde que esta PO pasara a la bandeja de cancelacion!',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, pre cancelar PO!',
                    cancelButtonClass: 'btn btn-secondary',
                }).then(function () {
                    $.ajax({
                        type: 'POST',
                        'url': 'preCancelPO',
                        data: {
                            itemplan: itemfaultGlob,
                            codigoPO: poGlob,
                            idEstacion: idEstacionGlob,
                            motivo: motivo,
                            observacion: observacion
                        },
                        'async': false
                    }).done(function (data) {
                        var data = JSON.parse(data);
                        if (data.error == 0) {
                            modal('modal-info');
                            modal('modalMotivoPrecancelacion');
                            mostrarNotificacion('success', 'Operacion Exitosa', data.msj);
                            location.reload();
                        } else {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }

                    });
                });
            }
        }
        //asdasdasd


                          
    </script>


</body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>