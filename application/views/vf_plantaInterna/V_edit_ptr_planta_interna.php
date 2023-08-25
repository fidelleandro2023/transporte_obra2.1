<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">

    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
</head>

<body data-ma-theme="entel" >
<main class="main">
    <div class="page-loader">
        <div class="page-loader__spinner">
            <svg viewBox="25 25 50 50">
                <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <header class="header" >
        <div class="navigation-trigger" data-ma-action="aside-open" data-ma-target=".sidebar">
            <div class="navigation-trigger__inner">
                <i class="navigation-trigger__line"></i>
                <i class="navigation-trigger__line"></i>
                <i class="navigation-trigger__line"></i>
            </div>
        </div>
        <div class="header__logo hidden-sm-down" style="text-align: center;">
            <a href="http://www.movistar.com.pe/" title="movistar Peru"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
    <style type="text/css">
        .select2-dropdown{
            z-index:9001;
        }
    </style>




    <!--desde aca se edita -->



    <section class="content content--full">
        <div class="content__inner" >

            <h2 class="text-center">EDITAR PTR: <strong id="itemTitle"><?php echo $ptr?></strong></h2>

            <div class="card-header" style="background-color: white">
                <div class="card-block">
                    <div class="table-responsive">
                        <?php  echo $tablaActividades
                        ?>
                    </div>

                    <div class="table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered">
                            <thead>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">Partida</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">Precio</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">Baremo</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center; max-width: 100px">Cantidad</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">Costo MO</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">Precio kit</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">Costo MAT</th>
                            <th style="font-weight: bolder; color: white; background-color: #0154a0; text-align: center">Total</th>
                            <th style="background-color: #0154a0;"></th>
                            </thead>
                            <tbody id="tBodyActividades">
                                    <?php   echo $bodyTablaActividades?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <button id="btnSave" data-item="<?php  echo $itemplan?>" data-flg_rechazado = "<?php echo $flg_rechazado?>" data-ptr="<?php echo $ptr?>" type="button" class="btn btn-success" onclick="savePTR(this)">Guardar</button>


                        </div>
                        <div class="col-md-3">
                           <strong>TOTAL:S/.</strong> <label id="montoTotalGeneral"><?php echo $monto_total_ptr ?></label>
                        </div>

                    </div>
                </div>


            </div>










        </div>
    </section>



</main>
<!-- Large -->

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
<script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
<!-- Charts and maps-->
<script src="<?php echo base_url();?>public/demo/js/flot-charts/curved-line.js"></script>
<script src="<?php echo base_url();?>public/demo/js/flot-charts/line.js"></script>
<script src="<?php echo base_url();?>public/demo/js/flot-charts/chart-tooltips.js"></script>
<script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
<script src="<?php echo base_url();?>public/demo/js/jqvmap.js"></script>
<!-- App functions and actions -->
<script src="<?php echo base_url();?>public/js/app.min.js"></script>
<!--  -->
<script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url();?>public/js/correlativo.js"></script>

<script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
</body>

<script>
    var ptrGlobal  = $('#itemTitle').html();
    var montoTotal = <?php echo $monto_total_ptr ?>;
    var itemplanGlobal = "<?php echo strval($itemplan) ?>";
    var montoTotalMoInicial = <?php echo $monto_total_mo ?>;
    var montoTotalMOFin = 0;
    var jsonExceso = {};
    actividades = <?php echo json_encode($actividadesTable) ?>;
    $(document).ready(function () {
        console.log('array:'+JSON.stringify(actividades));
        initTableActividades('#idTablaActividades');
        $('#montoTotalGeneral').html(montoTotal);

        var arrayKeys = Object.keys(actividades);
        console.log()
        // jsonExceso = actividades;
        arrayKeys.forEach(function(i){
            jsonExceso[i] = {};
            jsonExceso[i].costo_mat        = actividades[i].costoMAT;
            jsonExceso[i].costo_mo         = actividades[i].costoMO;
            jsonExceso[i].total            = actividades[i].total;
            jsonExceso[i].id_actividad     = actividades[i].idActividad;
            jsonExceso[i].cantidadInicial  = actividades[i].cantidad;
            jsonExceso[i].id_ptr_x_actividades_x_zonal = actividades[i].id_ptr_x_actividades_x_zonal;
            jsonExceso[i].cantidad_final   = actividades[i].cantidad;
            jsonExceso[i].ptr              = ptrGlobal;
            jsonExceso[i].itemplan         = itemplanGlobal;
            jsonExceso[i].precio          = actividades[i].costo;
            jsonExceso[i].baremo           = actividades[i].baremo;
            jsonExceso[i].descripcion      = actividades[i].descripcion;
            jsonExceso[i].costo_kit        = actividades[i].costo_material;
        });

        console.log(jsonExceso);
    });

    function addActividad(actividad) {
        if ($("#actividad"+actividad.idActividad).length === 0)
        {

            var tr = "<tr id='actividad" + actividad.idActividad + "'>";
            tr += "<td>" + actividad.descripcion + "</td>";
            tr += "<td id='costo" + actividad.idActividad + "'>" + actividad.costo+ "</td>";
            tr += "<td id=\"baremo" + actividad.idActividad + "\">" + actividad.baremo + "</td>";
            tr += "<td style='max-width: 100px'><input type='text' class='form-control' id='cantidad" + actividad.idActividad + "' onkeyup='calculaTotal(" + actividad.idActividad + ")' style=' border-style: ridge; border-width: 4px; text-align: center'></td>";
            tr += "<td id='totalBaremo" + actividad.idActividad + "'></td>";
            tr += "<td id='precioKit" + actividad.idActividad + "'>" + actividad.costo_material+ "</td>";
            tr += "<td id='totalMaterial" + actividad.idActividad + "'></td>";
            tr += "<td id='total" + actividad.idActividad + "'></td>";
            tr += "<td><img src='/obra2.1/public/img/iconos/delete.png' style='cursor: pointer;' width='20px' onclick='addActividad("+ JSON.stringify(actividad) +")'></td>";
            tr += "</tr>";

            $("#tBodyActividades").append(tr);
            actividades[actividad.idActividad] = actividad;
            console.log(actividades);
        }else{

            var lastValMO =  $("#totalBaremo"+actividad.idActividad).html();
            var lastValMat =  $("#totalMaterial"+actividad.idActividad).html();
            montoTotal = (Number(montoTotal) - (Number(lastValMO) + Number(lastValMat))).toFixed(2);
            $('#montoTotalGeneral').html(montoTotal);
            actividades[actividad.idActividad] = null;
            $('#checkBoxActividad'+actividad.idActividad).prop('checked', false);
            $("#actividad"+actividad.idActividad).remove();
            montoTotalMOFin = (Number(montoTotalMOFin)-Number(lastValMO)).toFixed(2);
            console.log("COSTO MO :"+montoTotalMOFin);
        }
    }

    function calculaTotal(id_actividad) {
        var lastValMO =  $("#totalBaremo"+id_actividad).html();
        var lastValMat =  $("#totalMaterial"+id_actividad).html();
        montoTotal = (Number(montoTotal) - (Number(lastValMO) + Number(lastValMat))).toFixed(2);
        $("#totalBaremo" + id_actividad).text('0.00');
        $("#totalMaterial" + id_actividad).text('0.00');
        $("#total" + id_actividad).text('S/.0.00');
        $('#montoTotalGeneral').html('0.00');
        var cantidad = $("#cantidad" + id_actividad).val();

        if(!isLetter(cantidad) && cantidad.length >= 1) {
            var baremo = $("#baremo" + id_actividad).text();
            var precioKit = $("#precioKit" + id_actividad).text();
            var costo = $("#costo" + id_actividad).text();
            var totalMO = cantidad * Number(baremo) * Number(costo);
            totalMO = Number(totalMO).toFixed(2);

            montoTotalMOFin = totalMO;
            montoTotalMOFin = (Number(montoTotalMOFin)+Number(montoTotalMoInicial)).toFixed(2);
            var totalMAT = cantidad * Number(precioKit)
            totalMAT = Number(totalMAT).toFixed(2);
            console.log("ASDA: "+id_actividad);
            var totalFinal = (Number(totalMO) + Number(totalMAT)).toFixed(2);
            actividades[id_actividad].total = totalFinal;
            actividades[id_actividad].costoMO = totalMO;
            actividades[id_actividad].costoMAT = totalMAT;
            actividades[id_actividad].cantidad = cantidad;
            actividades[id_actividad].precioKit = precioKit;

            console.log("IDPtRACTI: "+actividades[id_actividad].id_ptr_x_actividades_x_zonal);
            $("#totalBaremo" + id_actividad).text(totalMO);
            $("#totalMaterial" + id_actividad).text(totalMAT);

            $("#total" + id_actividad).text('S/.' + totalFinal);

            montoTotal = (Number(montoTotal) + Number(totalFinal)).toFixed(2);
            console.log("TOTAL MO: "+montoTotalMOFin);
            $('#montoTotalGeneral').html(montoTotal);
            console.log(actividades);

            var arrayKeys = Object.keys(jsonExceso);
            var cont = 0;
            arrayKeys.forEach(function(i){
                if(i == id_actividad) {
                    cont = 1;
                    jsonExceso[i].costo_mat        = totalMAT;
                    jsonExceso[i].costo_mo         = totalMO;
                    jsonExceso[i].total            = totalFinal;
                    jsonExceso[i].cantidad_final   = cantidad;
                }

                // jsonExceso.cantidad_final   = cantidadFinal;
                // jsonExceso.ptr              = ptrGlobal;
                // jsonExceso.itemplan         = itemplanPTRGlobal;
                // jsonExceso.precio           = precio;
                // jsonExceso.baremo           = baremo;
                // jsonExceso.descripcion      = descripcionAct;
                // jsonExceso.costo_kit        = precioKit;
            });

            if(cont == 0) {
                jsonExceso[id_actividad] = {};
                jsonExceso[id_actividad].costo_mat        = actividades[id_actividad].costoMAT;
                jsonExceso[id_actividad].costo_mo         = actividades[id_actividad].costoMO;
                jsonExceso[id_actividad].total            = actividades[id_actividad].total;
                jsonExceso[id_actividad].id_actividad     = actividades[id_actividad].idActividad;
                jsonExceso[id_actividad].cantidadInicial  = actividades[id_actividad].cantidad;
                jsonExceso[id_actividad].id_ptr_x_actividades_x_zonal = null;
                jsonExceso[id_actividad].cantidad_final   = actividades[id_actividad].cantidad;
                jsonExceso[id_actividad].ptr              = ptrGlobal;
                jsonExceso[id_actividad].itemplan         = itemplanGlobal;
                jsonExceso[id_actividad].precio           = actividades[id_actividad].costo;
                jsonExceso[id_actividad].baremo           = actividades[id_actividad].baremo;
                jsonExceso[id_actividad].descripcion      = actividades[id_actividad].descripcion;
                jsonExceso[id_actividad].costo_kit        = actividades[id_actividad].costo_material;
            }

            console.log(jsonExceso);
        }
    }

    function isLetter(str) {
        return str.match(/[a-z]/i);
    }

    function savePTR(component){
        var jsonExcesoFinal = {};
        montoTotalMOFin = 0;
        var arrayExceso = [];
        var arrayKeys = Object.keys(jsonExceso);
        var cont = 0;
        arrayKeys.forEach(function(i){
            jsonExcesoFinal.costo_mat      = jsonExceso[i].costo_mat;
            jsonExcesoFinal.costo_mo       = jsonExceso[i].costo_mo;
            jsonExcesoFinal.total          = jsonExceso[i].total;
            jsonExcesoFinal.cantidad_final = jsonExceso[i].cantidad_final;
            jsonExcesoFinal.cantidadInicial = jsonExceso[i].cantidadInicial;
            jsonExcesoFinal.id_ptr_x_actividades_x_zonal = jsonExceso[i].id_ptr_x_actividades_x_zonal;
            jsonExcesoFinal.ptr             = ptrGlobal;
            jsonExcesoFinal.itemplan        = itemplanGlobal;
            jsonExcesoFinal.id_actividad    = jsonExceso[i].id_actividad;
            jsonExcesoFinal.precio          = jsonExceso[i].precio;
            jsonExcesoFinal.baremo          = jsonExceso[i].baremo;
            jsonExcesoFinal.descripcion     = jsonExceso[i].descripcion;
            jsonExcesoFinal.costo_kit       = jsonExceso[i].costo_kit;
            
            arrayExceso.splice(arrayExceso.length, 0, jsonExcesoFinal);
            
            montoTotalMOFin = Number(jsonExcesoFinal.total) + Number(montoTotalMOFin); //SACO EL MONTO FINAL
            jsonExcesoFinal = {};
        });

        // if(montoTotalMOFin > montoTotalMoInicial) {
        if(montoTotal > montoTotalMoInicial) {
            jsonCreateSol = {   origen       		: 6,
                                tipo_po_dato 		: 2, 
                                accion_dato  		: 2, 
                                codigo_po_dato      : ptrGlobal, 
                                itemplan_dato  	    : itemplanGlobal,
                                data_json           : arrayExceso,
                                idEstacion          : 24 };
       
            var costo_actual = montoTotalMoInicial;
            // var excedente 	 = montoTotalMOFin - montoTotalMoInicial;
            var excedente 	 = montoTotal - montoTotalMoInicial;
            // var costo_final  = montoTotalMOFin;
            var costo_final  = montoTotal;
            swal({
                // title: 'No se pudo procesar la Solicitud',
                // text: data.msj,
                html : '<div class="form-group"><a>SOLICITUD EXCESO</a></div>'+
                        '<div class="form-group">'+
                            '<label style="color:red">SUBIR EVIDENCIA EXCESO</label>'+
                            '<input type="file" name="archivo" id="archivoFile">'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<textarea class="col-md-12 form-control" placeholder="Ingresar Comentario..." style="height:80px;background:#F9F8CF" id="comentarioText"></textarea>'+
                        '</div>',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, generar Solicitud!',
                cancelButtonClass: 'btn btn-secondary',
                allowOutsideClick: false
            }).then(function(){//falta codigo que genera la solicitud...
                let dataFile = new FormData();
                var comentario = $('#comentarioText').val();

                var fileArchivo = $('#archivoFile')[0].files[0];

                dataFile.append('origen', jsonCreateSol.origen);
                dataFile.append('itemplan', jsonCreateSol.itemplan_dato);
                dataFile.append('tipo_po', jsonCreateSol.tipo_po_dato);
                dataFile.append('costo_inicial', costo_actual);
                dataFile.append('exceso_solicitado', excedente);
                dataFile.append('costo_final', costo_final);
                dataFile.append('codigo_po', jsonCreateSol.codigo_po_dato);
                dataFile.append('comentario', comentario);
                dataFile.append('idEstacion', jsonCreateSol.idEstacion);
                dataFile.append('file', fileArchivo);
                dataFile.append('data_json', JSON.stringify(jsonCreateSol.data_json));

                $.ajax({
                    data: dataFile,
                    type: 'POST',
                    url: 'genSolExce',
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        swal({
                            title: 'Se realizo la Operacion!',
                            text: 'Asegurese de validar la informacion!',
                            type: 'success',
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: 'OK!',
                            allowOutsideClick: false
                        }).then(function(){
                            window.close(); 
                        });	                                
                    }else if(data.error == 1){
                        mostrarNotificacion('error', data.msj);
                    }
                        
                });
            }, function(dismiss) {
                console.log('cancelar.');
            });
        } else {
            var itemplan      = $(component).attr('data-item');
            var ptr           = $(component).attr('data-ptr'); 
            var flg_rechazado = $(component).attr('data-flg_rechazado')
            $.ajax({
                type	:	'POST',
                url     : "updatePTRPI",
                data: { 'actividades'   : actividades,
                        'itemplan'      : itemplan,
                        'ptr'           : ptr,
                        'flg_rechazado' : flg_rechazado},
                'async'	:	false
            })
            .done(function(data){
                var data = JSON.parse(data);
                console.log(data);
                if(data.error == 0){  //
                    //location.reload();
                    if(flg_rechazado == 1) {
                        location.href = "bandejaRechazados";
                    } else {
                        location.href = "detallePI?item="+itemplan;
                    }
                }else if(data.error == 1){

                    mostrarNotificacion('error','Error',data.msj);
                }

            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
            })
            .always(function() {

            });
        }
    }

</script>
</html>