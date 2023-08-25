<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <meta charset="UTF-8">
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

            <h2 class="text-center">DETALLE ITEMPLAN: <strong
                        id="itemTitle"><?php echo $item ?></strong></h2>
            <br>
      

            <!-- 
            <div class="actions">

                <button class="btn btn-info" data-toggle="modal" data-target="#modal-editar">Editar fila</button>
                
                <?php if($countPtr == 0) { ?>
                    <button class="btn btn-info" data-toggle="modal" data-target="#modal-agregar">Agregar fila</button>

                 <?php } ?>

            </div>-->

            <!-- Modal Editar fila -->
            <div class="modal fade" id="modal-editar" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="text-center" >Editar fila</h5>
                        </div>
                        <div class="modal-body" style="background-color: aliceblue">
                            <div class="row" id="listaEstacionesEdit">

                                <?php echo $listaEstacionesEdit ?>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button onclick="recogeEditarPi()" data-dismiss="modal" class="btn btn-link"
                                    data-dismiss="modal" id="refresh" style="background-color: #32c787">Confirmar
                            </button>
                            <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin modal-->

            <!-- Modal Agregar fila -->
            <div class="modal fade" id="modal-agregar" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class=" text-center">Agregar</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">

                                <?php echo $listaEstacionesInsertPI ?>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button onclick="recogeInsertaPi()" type="submit" class="btn btn-link" data-dismiss="modal"
                                    id="refresh2" style="background-color: #32c787">Confirmar
                            </button>
                            <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin Modal agregar-->

            <!-- Modal info -->
            <div class="modal fade" id="modal-info" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title pull-left">Informaci&oacute;n PO</h5>
                        </div>
						
                        <div class="modal-body">
							<div class="col-sm-12 col-md-12">
								<div class="tab-container">
									<ul class="nav nav-tabs nav-fill" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#detallePO" role="tab">PO</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#partidas" role="tab">PARTIDAS</a>
										</li>
									</ul>
								</div>
								<div class="tab-content">
									<div class="tab-pane active fade show" id="detallePO" role="tabpanel">
										<div class="row" id="infocontenido">                                            
											<!-- info -->   
										</div>
									</div>
									<div class="tab-pane fade" id="partidas" role="tabpanel">
										<div class="tab-container">
											<div id="contTablaPartidas" class="table-responsive">
											</div>
										</div>
									</div>
								</div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal" style="background-color: dodgerblue;">Ok
                            </button>
                            <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>

                        </div>
                    </div>
                </div>
        </div>
        <!-- Fin Modal Info-->
        
        <div class="modal fade" id="modal-infoMAT" tabindex="-1">
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
                                <div class="row" id="infocontenidoMAT">                                            
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
                                   <!-- <div id="contTablaPartidas" class="table-responsive">
                                    </div> -->
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
    
        <div class="col-sm-6 col-md-12">
            <div class="form-group">
                <table id="datatable2" style="margin: 0 auto;">
                    <tbody>
                    <tr style="text-align: center;">
                        <td>No Aprobado</td>
                        <td>Aprobado</td>
                        <td>Pendiente de validacion</td>
                        <td>Liquidado</td>
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

        <div class="row" id="itemEstaciones">


            <?php echo $listaEstaciones ?>


        </div>


        </div>


</section>
</main>


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
<script type="text/javascript">


    $(document).ready(function () {



    });





    function createPTRPI(component) {
        var itemplan = $(component).attr('data-item'); // ESTA MANDANDO COMPONENTE
        var idSub = $(component).attr('data-subproyectoestacion'); // ESTA MANDANDO COMPONENTE

        window.location.href = "<?php echo base_url() . 'plantaInterna?item=';?>" + itemplan + "&&idSub=" + idSub;
    }

    function modificarPTRPI(component) {
        var itemplan = $(component).attr('data-item'); // ESTA MANDANDO COMPONENTE
        var ptr = $(component).attr('data-ptr'); // ESTA MANDANDO COMPONENTE

        window.location.href = "<?php echo base_url() . 'editPtrPI?item=';?>" + itemplan + "&&ptr=" + ptr;
    }

    function asignarGrafo(component) {


        swal({
            title: 'EstÃ¡ seguro de asociar el grafo?',
            text: 'Recuerde que luego tendra que asignar el grafo en SAP!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, asignar grafo!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function () {

            var id_ptr = $(component).attr('data-ptr');
            var grafo = $(component).attr('data-grafo');

            var subProy = $.trim($('#selectSubProy').val());
            var eecc = $.trim($('#selectEECC').val());
            var zonal = $.trim($('#selectZonal').val());
            var item = $.trim($('#selectHasItemPlan').val());
            var mes = $.trim($('#selectMesEjec').val());
            var area = $.trim($('#selectArea').val());
            var estado = $.trim($('#selectEstado').val());

            $.ajax({
                type: 'POST',
                'url': 'asigGrafo',
                data: {
                    id_ptr: id_ptr,
                    grafo: grafo,
                    subProy: subProy,
                    eecc: eecc,
                    zonal: zonal,
                    item: item,
                    mes: mes,
                    area: area,
                    estado: estado
                },
                'async': false
            })
                .done(function (data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {

                        mostrarNotificacion('success', 'OperaciÃ³n Ã©xitosa.', data.msj);
                        $('#contTabla').html(data.tablaAsigGrafo)
                        initDataTable('#data-table');
                    } else if (data.error == 1) {

                        mostrarNotificacion('error', 'Error el asociar Grafo', data.msj);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function () {

                });

        });

    }

    function filtrarTabla() {
        var subProy = $.trim($('#selectSubProy').val());
        var eecc = $.trim($('#selectEECC').val());
        var zonal = $.trim($('#selectZonal').val());
        var mes = $.trim($('#selectMesEjec').val());
        $.ajax({
            type: 'POST',
            'url': 'getTableData',
            data: {
                subProy: subProy,
                eecc: eecc,
                zonal: zonal,
                mes: mes
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

    Array.prototype.unique = function (a) {
        return function () {
            return this.filter(a)
        }
    }(function (a, b, c) {
        return c.indexOf(a, b + 1) < 0
    });

    /*
  $('#modal-editar').keyup(function(e) {
      if(e.keyCode == 13) {
          console.log('Ha presionado ENTER');
          recogeEditarPi();
          $("#modal-editar").modal("hide");
      }
  });*/

    function recogeEditarPi() {
        console.log('entro en recogeEditarPi...');
        var itemTitle = $('#itemTitle').html();
        var arrayNamesEdit = $("input[name*='ptrEdit']");
        var arrayTemp = new Array();
        var arrayTempNulls = new Array();
        var arrayEdit = new Array();
        var cambios = 0;

        for (m = 0; m < arrayNamesEdit.length; m++) {

            arrayTemp.push(arrayNamesEdit[m].value + "/" + arrayNamesEdit[m].dataset.item);
            if (arrayNamesEdit[m].value != arrayNamesEdit[m].defaultValue) {
                cambios++;
                console.log(arrayNamesEdit[m].defaultValue + "," + arrayNamesEdit[m].value + "," + arrayNamesEdit[m].dataset.item + "," + arrayNamesEdit[m].dataset.idsubproyestacion);
            }
        }

        if (cambios == 0) {
            console.log('No hay cambios por realizar.');
        } else {
            console.log('Se haran ' + cambios + ' cambio en el sistema.');

            /*if( arrayTemp.length == arrayTemp.unique().length){
                console.log('todo ok, no hay duplicados');*/

            for (x = 0; x < arrayNamesEdit.length; x++) {

                //arrayTempNulls
                /*if(arrayNamesEdit[x].value == ''){
                    arrayTempNulls.push(arrayNamesEdit[x].defaultValue);
                }*/

                if (arrayNamesEdit[x].defaultValue != arrayNamesEdit[x].value) {
                    arrayEdit.push(arrayNamesEdit[x].defaultValue + "/" + arrayNamesEdit[x].value + "/" + arrayNamesEdit[x].dataset.item + "/" + arrayNamesEdit[x].dataset.idsubproyestacion);
                    console.log(arrayNamesEdit[x].defaultValue + "/" + arrayNamesEdit[x].value + "/" + arrayNamesEdit[x].dataset.item + "/" + arrayNamesEdit[x].dataset.idsubproyestacion);
                }
            }
            /*
            if( Object.keys(arrayTempNulls).length != 0 ){
                alert('Desea borrar esta PTR?');
                console.log('continuacion');

            }*/


            console.log(' el array x enviar a ajax es ============');
            console.log(arrayEdit);

            var jsonEdit = JSON.stringify(arrayEdit);
            $.ajax({
                type: 'POST',
                'url': 'ptrToEditqs',
                data: {
                    jsonNamesEdit: jsonEdit,
                    itemTitle: itemTitle
                },
                'async': false
            }).done(function (data) {
                console.log('volvio del ajax');
                //location.reload();
                var data = JSON.parse(data);
                if (data.error == 0) {
                    console.log('en el data error 0');
                    $('#itemEstaciones').html(data.listaEstaciones);
                    $('#listaEstacionesEdit').html(data.listaEstacionesEdit);
                    mostrarNotificacion('success', 'Se realizaron los cambios correctamente.', '');

                    // AQUI RECIBIR EL ITEMPLAN DE PHP
                    //id = itemEstaciones

                } else if (data.error == 1) {

                    mostrarNotificacion('error', 'Hubo un problema.');
                }
            });
            /*}else{
                alert('Usted no puede registrar duplicados, por favor vuelva a intentarlo.');
            }*/

        }

    }

    function recogeInsertaPi() {
        console.log('entro en recogeInsertaPi...');
        var itemTitle = $('#itemTitle').html();
        var arrayNamesInsert = $("input[name*='ptrInsert']");
        var arrayPTRExistentes = $("input[name*='ptrEdit']");

        var arrayPTRExistentesTemp = new Array(); // Para limpiar
        var arrayInsert = new Array(); // recoge lo colocado x usuario
        var arrayValidator = new Array(); // para verificar si hay duplicados escritos en los inputs
        //var verificador = 0;
        var validador1 = 0;

        // PRIMERA VALIDACION
        for (a = 0; a < arrayNamesInsert.length; a++) {
            if (arrayNamesInsert[a].value != '') {
                console.log('ptr insertado ' + arrayNamesInsert[a].value);

                for (b = 0; b < arrayPTRExistentes.length; b++) {

                    console.log('ptr existente ' + arrayPTRExistentes[b].value);

                    if (arrayNamesInsert[a].value == arrayPTRExistentes[b].value) {
                        validador1++;
                    }
                }
            } else {
                //console.log('input vacio');
            }
        }

        if (validador1 == 0) {
            console.log('Se debe continuar validando...');

            for (y = 0; y < arrayNamesInsert.length; y++) {
                var rowInsert = '';
                if (arrayNamesInsert[y].value != '') {
                    rowInsert = arrayNamesInsert[y].value + "/" + arrayNamesInsert[y].dataset.item;
                    arrayValidator.push(rowInsert);
                    arrayInsert.push(arrayNamesInsert[y].value + "/" + arrayNamesInsert[y].dataset.item + "/" + arrayNamesInsert[y].dataset.subproyectoestacion + "/" + arrayNamesInsert[y].dataset.area);
                }
            }
            if (arrayValidator.length == arrayValidator.unique().length) {
                console.log('no hay duplicados');

                var jsonInsert = JSON.stringify(arrayInsert);

                $.ajax({
                    type: 'POST',
                    'url': 'ptrToInsert12',
                    data: {
                        jsonNamesInsert: jsonInsert
                    },
                    'async': false
                }).done(function (data) {
                    console.log('volvio del ajax');
                    //console.log(data);
                    location.reload();
                    if (data.error == 0) {

                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Hubo un problema.');
                    }
                });
            } else {
                alert('Usted no puede ingresar una misma PTR dos veces.');
            }


            console.log('array a enviar al ajax es');
            console.log(arrayInsert);


        } else {
            alert('Usted ha ingresado ' + validador1 + ' PTR ya existente en este ITEMPLAN. Por favor vuelva a intentarlo.');
        }
    }


    function getPtr(component) {
        //   console.log('en ptr');
        //  console.log(component);
        var ptr = $(component).attr('data-ptr');

        $.ajax({
            type: 'POST',
            'url': 'ptrInfo12',
            data: {
                ptr: ptr
            },
            'async': false
        }).done(function (data) {
            var data = JSON.parse(data);
            $('#infocontenido').html(data.prueba);
			$('#contTablaPartidas').html(data.htmlPartidas);
            modal('modal-info');
        });

    }

    function getPtrInfoMat(component) {
        
        var poGlob = $(component).attr('data-ptr');
        var itemplanGlob = $(component).attr('data-itemplan');
        var idEstacionGlob = $(component).attr('data-estacion');
        var idSubProyectoGlob = $(component).attr('data-idsubproy');
        var areaDesc = $(component).attr('data-areadesc');
        

        $.ajax({
            type: 'POST',
            'url': 'ptrInfo',
            data: {
                ptr: poGlob,
                itemplan: itemplanGlob,
                idEstacion: idEstacionGlob,
                areaDesc: areaDesc,
                idSubProyecto: idSubProyectoGlob
            },
            'async': false
        }).done(function (data) {
            var data = JSON.parse(data);
            if (data.error == 0) {
                $('#infocontenidoMAT').html(data.prueba);
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
                modal('modal-infoMAT');
            } else {
                mostrarNotificacion('error', 'Error', data.msj);
            }
        });

    }
</script>

</body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>