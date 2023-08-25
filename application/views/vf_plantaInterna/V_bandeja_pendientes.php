<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
            <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img
                        src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel"
                        style="width: 36%; margin-left: -51%"></a>
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
            <h2>BANDEJA PTR INTERNA PENDIENTE</h2>
            <hr>
            <div class="card">

                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>TIPO PLANTA</label>

                                <select id="selectTipoPlanta" name="selectTipoPlanta" class="select2"
                                        onchange="filtrarTabla()">
                                    <option>&nbsp;</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>PROYECTO</label>

                                <select id="selectProy" name="selectProy" class="select2" onchange="filtrarTabla()">
                                    <option>&nbsp;</option>
                                    <

                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>SUB PROYECTO</label>

                                <select id="selectSubProy" name="selectSubProy" class="select2"
                                        onchange="filtrarTabla()">
                                    <option>&nbsp;</option>
										<?php 
											foreach ($listaSubProy->result() as $row) {?>
												<option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
										   <?php }?>

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label>ITEMPLAN</label>

                                <select id="itemplan" name="itemplan" class="select2" onchange="filtrarTabla()">
                                    <option>&nbsp;</option>


                                </select>
                            </div>
                        </div>


                    </div>


                    <div id="contTabla" class="table-responsive">
                        <?php echo $tablaPrincipal ?>
                    </div>
                </div>
            </div>
        </div>


    </section>

</main>
<!-- Small -->
<div class="modal fade" id="modalExpediente" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Registrar </h5>
            </div>
            <br>
            <div class="modal-body">
                <h6>Usted ha seleccionado las siguientes PTR:</h6>
                <div class="card text-center">

                    <div id="seleccionados"></div>

                </div>
                <div class="form-group">
                    <label>Ingrese comentario</label>
                    <input id="inputVR" type="text" class="form-control input-mask" placeholder="Comentario"
                           autocomplete="off" maxlength="400" style="border-bottom: 1px solid lightgrey">
                    <i class="form-group__bar"></i>
                </div>
            </div>
            <div class="modal-footer">
                <button id="botonConfirmar" type="button" onclick="asignarExpediente(this)" class="btn btn-info"
                        data-dismiss="modal">CONFIRMAR
                </button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<!-- Older IE warning message -->


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
    /*
if(!("autofocus" in document.createElement("input"))){
    document.getElementById("inputVR").focus();
}*/


    function recogePTR() {

        console.log('entro en recogePTR...');

        //var arrayNamesptrExp = $( "input[name*='ptrExp']" );

        var arrayNamesptrExp = $("input[type=checkbox]:checked");
        var expediente = new Array();

        if (arrayNamesptrExp.length != 0) {
            console.log(arrayNamesptrExp.length);
            console.log(arrayNamesptrExp);

            for (i = 0; i < arrayNamesptrExp.length; i++) {
                expediente.push(arrayNamesptrExp[i].dataset.ptr + "%" + arrayNamesptrExp[i].dataset.item + "%" + arrayNamesptrExp[i].dataset.fecsol + "%" + arrayNamesptrExp[i].dataset.subproyecto + "%" + arrayNamesptrExp[i].dataset.zonal + "%" + arrayNamesptrExp[i].dataset.eecc + "%" + arrayNamesptrExp[i].dataset.area);
            }
            console.log('expediente es ');
            console.log(expediente);

            mostrarModal(expediente);

        } else {
            alert('Debe seleccionar al menos 1 registro para continuar.');
        }


    }

    function mostrarModal(expediente) {
        console.log('entro en registaExpediente');
        var texto = '';
        var ptrModal = '';
        var itemModal = '';
        for (j = 0; j < expediente.length; j++) {
            //texto += '<label>'+expediente[j].replace('%', ' ')+'</label><br>';
            //ptrModal = expediente[j]

            var elem = expediente[j].split('%');
            ptrModal = elem[0];
            itemModal = elem[1];
            texto += '<label>' + ptrModal + '</label><br>';

        }
        var jsonExpediente = JSON.stringify(expediente);

        console.log('----------------------------');
        console.log(expediente);
        console.log(jsonExpediente);


        $('#seleccionados').html(texto);

        $('#botonConfirmar').attr('data-jsonptr', jsonExpediente);


        $('#modalExpediente').modal('toggle');

    }


    function asignarExpediente(component) {
        var vrLeng = $('#inputVR').val().length;

        if (vrLeng == 0) {
            alert('Usted no ha asignado un comentario de expediente.');
        } else {

            console.log('Asignar expediente');
            var jsonptr = $(component).attr('data-jsonptr');
            var comentario = $('#inputVR').val();
            console.log('=================');
            console.log(jsonptr);
            console.log(comentario);
            console.log('Ajax');


            $.ajax({
                type: 'POST',
                'url': 'asignarExpediente',
                data: {
                    jsonptr: jsonptr,
                    comentario: comentario
                },
                'async': false
            }).done(function (data) {
                console.log('voldio del ajax');

                var data = JSON.parse(data);
                console.log('++++++++++++++++++');

                if (data.error == 0) {
                    console.log('en el if');
                    $('#modalExpediente').modal('toggle');
                    mostrarNotificacion('success', 'Registro exitoso.', data.msj);
                    //$('#contTabla').html(data.tablaAsigGrafo)
                    //initDataTable('#data-table');
                    filtrarTabla();
                } else if (data.error == 1) {
                    console.log('en el else');

                    mostrarNotificacion('error', 'Error al dar expediente', data.msj);
                }
            });

            console.log('se envio a ruta');

        }

    }

    function filtroTipoPlanta() {

    }

    function filtrarTabla() {
        var itemplan = $.trim($('#itemplan').val());
        var tipoPlanta = $.trim($('#selectTipoPlanta').val());
        var nombreproyecto = $.trim($('#nombreproyecto').val());
        var nodo = $.trim($('#nodo').val());
        var zonal = $.trim($('#selectZonal').val());
        var proy = $.trim($('#selectProy').val());
        var subProy = $.trim($('#selectSubProy').val());
        var estado = $.trim($('#estado').val());
        var selectMesPrevEjec = $.trim($('#selectMesPrevEjec').val());

        var fechaInicio0 = $('#fechaInicio').val();
        var fechaFin0 = $('#fechaFin').val();

        var fechaInicio = fechaInicio0.replace(/-/g, '/');
        var fechaFin = fechaFin0.replace(/-/g, '/');

        var fechaDestinoDefault = '2018/12/31';
        var fechaDestino = '';
        var filtroPrevEjec = '';

        console.log('fechaInicio es: ' + fechaInicio);
        if (fechaFin0 == '') {
            //console.log('fecha fin esta vacia');
            //console.log('fecha destino sera: '+fechaDestinoDefault);
            fechaDestino = fechaDestinoDefault;
        } else {
            //console.log('fechaFin (destino) es: '+fechaFin);
            fechaDestino = fechaFin;
        }

        if (fechaInicio0 != '') {
            filtroPrevEjec = " AND p.fechaPrevEjec BETWEEN '" + fechaInicio + "' AND '" + fechaDestino + "' ";
        } else {
            filtroPrevEjec = "";
        }

        $.ajax({
            type: 'POST',
            'url': 'getDataTableItem',
            data: {
                itemplan: itemplan,
                nombreproyecto: nombreproyecto,
                nodo: nodo,
                zonal: zonal,
                proy: proy,
                subProy: subProy,
                estado: estado,
                filtroPrevEjec: filtroPrevEjec,
                tipoPlanta: tipoPlanta
                //area : area
            },
            'async': false
        })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contTabla').html(data.tablaAsigGrafo)
                    initDataTable('#data-table');

                } else if (data.error == 1) {

                    mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                }
            });


    }

</script>
</body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>