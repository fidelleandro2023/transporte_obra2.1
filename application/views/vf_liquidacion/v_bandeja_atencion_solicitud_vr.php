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
        <style type="text/css">
            @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
              }
            }
        </style>
    </head>

    <body data-ma-theme="entel">
    <main class="main">
        <div class="page-loader">
            <div class="page-loader__spinner">
                <svg viewBox="25 25 50 50">
                    <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
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
                <div class="card">

                    <div class="card-block">
                        <div class="row">
                                <div class="col-md-3">
                                    <select id="cmbJefatura" class="form-control select2" >
                                        <option value="">Seleccionar Jefatura</option>
                                        <?php foreach($cmbJefatura as $row) {
                                            echo '<option value="'.$row->idJefatura.'">'.$row->descripcion.'</option>';
                                         }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="cmbEcc" class="form-control select2">
                                        <option value="">Seleccionar ECC</option>
                                        <?php 
                                            foreach ($listaEECC->result() as $row) {?>
                                                <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                            <?php }?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="selectFase" class="form-control select2">
                                        <option value="">Seleccionar FASE</option>
                                        <?php                                                    
                                            foreach($listafase->result() as $row){                      
                                        ?> 
                                            <option value="<?php echo $row->idFase ?>"><?php echo $row->faseDesc ?></option>
                                            <?php }?>
                                    </select>
                                </div> 
                                <div class="col-md-3">
                                    <select id="cmbTipoSolicitud" class="form-control select2">
                                        <option value="">Seleccionar Tipo de Solicitud</option>
                                        <?php foreach($cmbTipoSolicitud as $row) {
                                            echo '<option value="'.$row->idTipoSolicitud.'">'.$row->descripcion.'</option>';
                                         }?>
                                    </select>
                                </div>
                            </div><br>
                            <div class="row">                                
                                <div class="col-md-3">
                                    <select id="cmbTipoAtencion" class="form-control select2">
                                      <option value="">Seleccionar Estado Atencion</option>
                                      <option value="1">Atencion Parcial</option>
                                      <option value="2">Atencion Pendiente</option>
                                      <option value="3">Atencion Rechazada</option>
                                      <option value="4">Atencion Total</option>
                                    </select>
                                </div> 
                                <div class="col-md-3">
                                    <input id="txtItemPlan" type="text" class="form-control input-mask" placeholder="ItemPlan" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-success waves-effect" type="button" onclick="filtrarBandejaSolicitudVr();">CONSULTAR</button>
                                </div>                           
                            </div>
                        
                        <!-- 
                        <div class="row">
                            <div class="row col-md-4">
                                <div class="form-group">
                                    <label>Atenci&oacute;n Total</label>
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="text" class="form-control" style="background:#8CE857;height:20px;width:30px" disabled>                            
                                </div>
                                
                            </div>
                            <div class="row col-md-4">
                                <div class="form-group">
                                    <label>Atenci&oacute;n Rechazada</label>
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="text" class="form-control" style="background:#F6A5A5;height:20px;width:30px" disabled>                                                                
                                </div>
                            </div>
                            <div class="row col-md-4">
                                <div class="form-group">
                                    <label>Atenci&oacute;n Parcial</label>
                                </div>
                                <div class="form-group col-md-2">
                                    <input type="text" class="form-control" style="background:#FFE033;height:20px;width:30px" disabled>                                                                
                                </div>
                            </div>
                        </div>
                        -->
                             <div class="row">
                            <div id="contTabla" class="table-responsive">
                                <?php echo $tablaBandejaSolicitud ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </main>

    <div class="modal fade" id="modalCheck" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-title" style="background:#0154a0;color:white;height:50px;font-size:25px" align="center">
                    CONSULTA                       
                </div>
                <div class="modal-body">
                <div id="titulo"></div>
                <div id="contCheck" class="table-responsive">
                </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn" data-dismiss="modal">Cerrar</button>
                    </div> 
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
    
    <script>
    function getMaterialModal(btn) {
        var itemplan    = btn.data('itemplan');
        var ptr         = btn.data('ptr');
        var codigo	    = btn.data('codigo');
        var vr	      	= btn.data('vr');
        $.ajax({
            type : 'POST',
            url  : 'getMaterialModal',
            data : { itemplan : itemplan,
                     ptr      : ptr,
                     codigo   : codigo,
                     vr       : vr}
        }).done(function(data) { 
            data = JSON.parse(data);
            $('#contCheck').html(data.tablaCheck);
            $('#titulo').html('<h3 style="color:red">Itemplan: '+itemplan+'</h3>');
            modal('modalCheck');
            initDataTable('#consultaModal');
        })
    }

    function filtrarBandejaSolicitudVr() {
    var idJefatura      = $('#cmbJefatura option:selected').val();
    var idEmpresaColab  = $('#cmbEcc option:selected').val();
    var idTipoSolicitud = $('#cmbTipoSolicitud option:selected').val();
    var idFase          = $('#selectFase option:selected').val();
    var tipoAtencion    = $('#cmbTipoAtencion option:selected').val();
    var itemplan	    = $('#txtItemPlan').val();
    $.ajax({
        type : 'POST',
        url  : 'filtrarBandejaConsultaSolicitudVr',
        data : { idJefatura      : idJefatura,
                 idEmpresaColab  : idEmpresaColab,
                 idTipoSolicitud : idTipoSolicitud,
                 idFase          : idFase,
                 tipoAtencion    : tipoAtencion,
                 itemplan        : itemplan}
    }).done(function(data) { 
        data = JSON.parse(data);
        $('#contTabla').html(data.tablaBandejaSolicitud);
        initDataTable('#data-table');    
    })
}
    </script>