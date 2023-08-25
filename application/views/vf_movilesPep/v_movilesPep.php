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
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css?v=<?php echo time(); ?>">
        <style>
            .size{
                width: 111px;
            }
            .select2-dropdown{
                z-index:9001;
            }
            .container {
                padding-right: 15px;
                padding-left: 15px;
                width: 1500px;
                max-width: 100%;
            }

            .content__inner:not(.content__inner--sm) {
                max-width: 100% !important;
            }

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
                    <h2>PEP MOVILES</h2>
                    <hr>
                    <div class="card">	   				                    
                        <div class="card-block">
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>SUBPROYECTO</label>
                                        <select id="busSubPro" name="selectEvento" class="select2 form-control">
                                            <option value="">&nbsp;</option>
                                            <?php
                                            foreach ($evento as $row) {
                                                ?> 
                                                <option value="<?php echo $row->idMovilesSubproyecto ?>"><?php echo $row->movilesSuproyectoDesc ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>PEP</label>
                                        <div class="col-sm-10">
                                                <input id="busPep" type="text" class="form-control input-mask" data-mask="P-0000-00-0000-00000" placeholder="P-0000-00-0000-00000" maxlength="20">

                                            </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <br>
                                        <button class="btn btn-success waves-effect" onclick="getPep()">CONSULTAR</button>
                                    </div> 
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <br>
                                        <button class="btn btn-success waves-effect" onclick="addNuevaPep()">REGISTRAR PEP</button>
                                    </div> 
                                </div>
                            </div>
                            <div id="contTabla" class="table-responsive">
                                <?php echo isset($tabla) ? $tabla : null ?>

                            </div>
                        </div>
                    </div>
            </section>

        </main>
        <!-- Small -->

        <div class="modal fade" id="modalMoviles" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title text-center">NUEVA PEP MOVIL</h1>
                    </div>
                    <br>
                    <div class="modal-body">
                        <div class="form-wrap">
                            <form class="form-horizontal">                                               
                                <div class="row">
                                    <div class="col-sm-4 col-md-6">
                                        <div class="form-group" style="height:42px">
                                            <label>PEP:</label>
                                            <div class="col-sm-10">
                                                <input id="pep2" name="pep2" type="text" class="form-control input-mask" data-mask="P-0000-00-0000-00000" placeholder="P-0000-00-0000-00000" maxlength="20">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-6">
                                        <div class="form-group" style="height:42px">
                                            <label>Subproyecto:</label>
                                            <select id="selecIdSubProyecto" name="selecIdSubProyecto" class="select2 form-control">
                                                <option value="">&nbsp;</option>
                                                <?php
                                                foreach ($evento as $row) {
                                                    ?> 
                                                    <option value="<?php echo $row->idMovilesSubproyecto ?>"><?php echo $row->movilesSuproyectoDesc ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4 col-md-6">
                                        <div class="form-group" style="height:42px">
                                            <label>Promotor:</label>
                                            <select id="selecIdPromotor" name="selecIdPromotor" class="select2 form-control">
                                                <option value="">&nbsp;</option>
                                                <option value=1"">Ejemplo 01</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-sm-4 col-md-6">
                                        <div class="form-group" style="height:42px">
                                            <label>Tipo:</label>
                                            <select id="selecIdTipo" name="selecIdTipo" class="select2 form-control">
                                                <option value="">&nbsp;</option>
                                                <?php
                                                foreach ($tipo as $row) {
                                                    ?> 
                                                    <option value="<?php echo $row->idTipoPep ?>"><?php echo $row->tipoPep ?></option>
                                                <?php } ?>
                                            </select>
                                        </div> 
                                    </div>
                                </div>





                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="boton_multiuso"  class="btn btn-info">GUARDAR</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="modal_form_usuario"  tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 style="margin: auto" id="tittleCertificarHG" class="modal-title">HISTORIAL TRANSACCIONES</h3>
                    </div>
                    <div class="modal-body">
                        <div id="contTablaDetalle" style="display:block" class="table-responsive form-group col-md-12">
                            <?php echo isset($tablaDetalle) ? $tablaDetalle : null ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>      
                </div>
            </div>
        </div>
        <!-- Older IE warning message -->

        <!-- POPUP LOG-->

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
        <!--CODIGO PARA EL FILE IMPUT--> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-input/fileinput.min.css">
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-input/fileinput.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/numeric/jquery.numeric.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>
        <script src="<?php echo base_url(); ?>public/js/js_moviles/js_moviles.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script src="https://www.w3schools.com/lib/w3.js"></script>
<!--        <script type="text/javascript">

                                function filtrarTabla() {
                                    //console.log('change');
                                    var subProy = $.trim($('#subproyectoFiltro').val());
                                    console.log(subProy);
                                    $.ajax({
                                        type: 'POST',
                                        'url': 'pepToroFil',
                                        data: {id_subPro: subProy},
                                        'async': false
                                    })
                                            .done(function (data) {
                                                var data = JSON.parse(data);
                                                if (data.error == 0) {
                                                    $(".table-responsive").html(data.tabla);
                                                    $("#simpletable").DataTable({dom: 'Bfrtip', buttons: [{extend: 'excelHtml5'}], pageLength: 10, lengthMenu: [[30, 60, 100, -1], [30, 60, 100, "Todos"]], language: {sProcessing: "Procesando...", sLengthMenu: "Mostrar _MENU_ registros", sZeroRecords: "No se encontraron resultados", sEmptyTable: "Ning\u00fan dato disponible en esta tabla", sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros", sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros", sInfoFiltered: "(filtrado de un total de _MAX_ registros)", sInfoPostFix: "",
                                                            sSearch: "Buscar:", sUrl: "", sInfoThousands: ",", sLoadingRecords: "Cargando...", oPaginate: {sFirst: "Primero", sLast: "\u00daltimo", sNext: "Siguiente", sPrevious: "Anterior"}}})
                                                } else if (data.error == 1) {

                                                    mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                                                }
                                            });
                                }



                                function addNuevaPep() {
                                    $('#modalAddOpex').modal('show');
                                }

                                function  deletePepToro(component) {
                                    swal({
                                        title: 'EstÃƒÂ¡ seguro de eliminar  Pep?',
                                        text: 'Asegurese de validar la informaciÃƒÂ³n seleccionada!',
                                        type: 'warning',
                                        showCancelButton: true,
                                        buttonsStyling: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: 'Si, eliminar!',
                                        cancelButtonClass: 'btn btn-secondary'
                                    }).then(function () {

                                        var id_pt = $(component).attr('data-id_pt');
                                        var id_td = $(component).attr('data-id_td');
                                        var idSub = $(component).attr('data-idSub');
                                        var pep = $(component).attr('data-pep');



                                        $.ajax({
                                            type: 'POST',
                                            url: "delPToro",
                                            data: {'id_pt': id_pt,
                                                'id_td': id_td,
                                                'idSub': idSub,
                                                'pep': pep},
                                            'async': false
                                        })
                                                .done(function (data) {
                                                    var data = JSON.parse(data);

                                                    if (data.error == 0) {
                                                        location.reload();

                                                    } else if (data.error == 1) {

                                                        alert('Error interno al intentar eliminar Pep, vuelva a intentarlo o comunniquese con el administrador.');
                                                    }
                                                })
                                    });


                                }
        </script>-->
    </body>

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>