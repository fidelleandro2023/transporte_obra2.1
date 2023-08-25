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

<body data-ma-theme="entel">
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

            <h2 class="text-center">CREACION PTR DEL ITEMPLAN: <strong id="itemTitle"><?php echo $itemplan?></strong></h2>

            <div class="card-header" style="background-color: white">
                <div class="card-block">
                    <div class="table-responsive">
                        <?php  echo $tablaActividades
                        ?>
                    </div>

                    <div class="table-responsive" style="margin-top: 12px;">
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
                            <tbody id="tBodyActividades" >
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <button class="btn btn-success" onclick="confirmar()">Guardar</button>
                        </div>
                        <div class="col-md-3">
                           <strong>TOTAL:S/.</strong> <label id="montoTotalGeneral"></label>
                        </div>

                    </div>
                </div>


            </div>


        </div>
    </section>



</main>
<!-- Large -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <p>&#191;Est&aacute; seguro que desea guardar?</p>
            </div>
            <div class="modal-body">
                <a>Al aceptar, se ingresar&aacute; la cotizaci&oacute;n y se enviar&aacute; la ptr a la bandeja de aprobaci&oacute;n.</a>
            </div>
            <div class="modal-footer">
                <button id="btnSave" data-item="<?php  echo $itemplan?>" data-idSub="<?php echo $idSubProyectoEstacion?>" type="button" class="btn btn-success" onclick="savePTR(this)">Guardar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
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
    var montoTotal = '0.00';
    actividades = [];
	var cont = 0;
    $(document).ready(function () {
        initTableActividades('#idTablaActividades');
        $('#montoTotalGeneral').html(montoTotal);
    });

    function addActividad(actividad) {
		cont++;
        if ($("#actividad"+actividad.idActividad).length === 0)
        {
            var tr = "<tr id='actividad" + actividad.idActividad + "'>";
            tr += "<td>" + actividad.descripcion + "</td>";
            tr += "<td id='costo" + actividad.idActividad + "'>" + actividad.costo+ "</td>";
            tr += "<td id=\"baremo" + actividad.idActividad + "\">" + actividad.baremo + "</td>";
            tr += "<td style='max-width: 100px'><input type='number' class='form-control' id='cantidad" + actividad.idActividad + "' onkeyup='calculaTotal(" + actividad.idActividad + ")' style=' border-style: ridge; border-width: 4px; text-align: center'></td>";
            tr += "<td id='totalBaremo" + actividad.idActividad + "'></td>";
            tr += "<td id='precioKit" + actividad.idActividad + "'>" + actividad.costo_material+ "</td>";
            tr += "<td id='totalMaterial" + actividad.idActividad + "'></td>";
            tr += "<td id='total" + actividad.idActividad + "'></td>";
            tr += "<td><img src='/obra2.1/public/img/iconos/delete.png' style='cursor: pointer;' width='20px' onclick='addActividad("+ JSON.stringify(actividad) +")'></td>";
            tr += "</tr>";

            $("#tBodyActividades").append(tr);
            //actividades[cont] = actividad;
			actividades.push(actividad);
        }else{

            var lastValMO =  $("#totalBaremo"+actividad.idActividad).html();
            var lastValMat =  $("#totalMaterial"+actividad.idActividad).html();
            montoTotal = (Number(montoTotal) - (Number(lastValMO) + Number(lastValMat))).toFixed(2);
            $('#montoTotalGeneral').html(montoTotal);
			
			actividades.forEach(function(data, key){
				if(actividad.idActividad == data.idActividad) {
					actividades.splice(key, 1);
				}
			});
            
            $('#checkBoxActividad'+actividad.idActividad).prop('checked', false);
            $("#actividad"+actividad.idActividad).remove();
        }
		console.log(actividades);
    }

    function calculaTotal(id_actividad_x_zonal) {
        var lastValMO =  $("#totalBaremo"+id_actividad_x_zonal).html();
        var lastValMat =  $("#totalMaterial"+id_actividad_x_zonal).html();
        montoTotal = (Number(montoTotal) - (Number(lastValMO) + Number(lastValMat))).toFixed(2);
        $("#totalBaremo" + id_actividad_x_zonal).text('0.00');
        $("#totalMaterial" + id_actividad_x_zonal).text('0.00');
        $("#total" + id_actividad_x_zonal).text('S/.0.00');
        $('#montoTotalGeneral').html('0.00');
        var cantidad = $("#cantidad" + id_actividad_x_zonal).val();

        if(!isLetter(cantidad) && cantidad.length >= 1) {
            var baremo = $("#baremo" + id_actividad_x_zonal).text();
            var precioKit = $("#precioKit" + id_actividad_x_zonal).text().replace(',','');
            var costo = $("#costo" + id_actividad_x_zonal).text();
            var totalMO = cantidad * Number(baremo) * Number(costo);
            totalMO = Number(totalMO).toFixed(2);


            var totalMAT = cantidad * Number(precioKit);
            totalMAT = Number(totalMAT).toFixed(2);

            var totalFinal = (Number(totalMO) + Number(totalMAT)).toFixed(2);
			
			actividades.forEach(function(data, key){
				if(id_actividad_x_zonal == data.idActividad) {
					data.total = totalFinal;
					data.costoMO = totalMO;
					data.costoMAT = totalMAT;
					data.cantidad = cantidad;
					data.precioKit = precioKit;
				}
			});
            // actividades[id_actividad_x_zonal].total = totalFinal;
            // actividades[id_actividad_x_zonal].costoMO = totalMO;
            // actividades[id_actividad_x_zonal].costoMAT = totalMAT;
            // actividades[id_actividad_x_zonal].cantidad = cantidad;
            // actividades[id_actividad_x_zonal].precioKit = precioKit;
            $("#totalBaremo" + id_actividad_x_zonal).text(totalMO);
            $("#totalMaterial" + id_actividad_x_zonal).text(totalMAT);

            $("#total" + id_actividad_x_zonal).text('S/.' + totalFinal);

            montoTotal = (Number(montoTotal) + Number(totalFinal)).toFixed(2);
            $('#montoTotalGeneral').html(montoTotal);
        }
    }

    function isLetter(str) {
        return str.match(/[a-z]/i);
    }

    function savePTR(component){
        $("#btnSave").prop('onclick', null);
        var itemplan = $(component).attr('data-item'); // ESTA MANDANDO COMPONENTE
        var idSub = $(component).attr('data-idSub'); // ESTA MANDANDO COMPONENTE
		
		console.log(actividades);

		if(actividades.length == 0) {
			return;
		}
		
        $.ajax({
            type	:	'POST',
            url     : "savePTRPI",
            data: {'actividades' : actividades,
                'itemplan'  : itemplan,
                'idSubEsta' : idSub },
            'async'	:	false
        })
            .done(function(data){
                var data = JSON.parse(data);
                console.log(data);
                if(data.error == 0){  //
                    //location.reload();
                    location.href = "detallePI?item="+itemplan;
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

    function confirmar() {
        $("#modalConfirmacion").modal('toggle');
    }

</script>
</html>