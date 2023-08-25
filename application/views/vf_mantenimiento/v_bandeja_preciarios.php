<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <style>.fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-usd,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}</style>
        <style>
            .select2-dropdown {
                z-index: 100000;
            }  
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                           <h2>MANTENIMIENTO DE PRECIARIOS 2020</h2>
                           <hr>
		                    <div class="card">		   				                    
		                    
                                <div class="card-block"> 
                                    <div class="row">

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>EECC</label>

                                                <select id="selecteecc" name="selecteecc" class="select2">
                                                    <option>&nbsp;</option>
                                                    <?php foreach($listaEECC->result() as $row){ ?> 
                                                        <option value="<?php echo trim($row->idEmpresaColab) ?>"><?php echo $row->empresaColabDesc ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>ZONAL</label>

                                                <select id="selectZonal" name="selectZonal" class="select2" multiple>
                                                    <option>&nbsp;</option>
                                                    <?php foreach($listaZonal->result() as $row){ ?> 
                                                        <option value="<?php echo trim($row->idZonal) ?>"><?php echo $row->zonalDesc ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>ESTACION</label>

                                                <select id="selectEstacion" name="selectEstacion" class="select2" multiple>
                                                    <option>&nbsp;</option>
                                                    <?php foreach($listaEstaciones as $row){ ?> 
                                                        <option value="<?php echo trim($row->idEstacion) ?>"><?php echo $row->estacionDesc ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <label>TIPO PRECIO</label>

                                                <select id="selectTipoPrecio" name="selectTipoPrecio" class="select2" multiple>
                                                    <option>&nbsp;</option>
                                                    <?php foreach($listaTipoPrecio as $row){ ?> 
                                                        <option value="<?php echo trim($row->idPrecioDiseno) ?>"><?php echo $row->descPrecio ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-4">
                                            <div class="form-group">
                                                <br><br>
                                                <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">Buscar</button>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>REGISTRAR PRECIARIO</label><br>
                                                <button class="btn btn-success waves-effect" type="button" onclick="openModalRegPreciario()">Nuevo Preciario</button>
                                            </div>
                                        </div>
                                
                                    </div>
		   				            <div id="contTabla" class="table-responsive">
								       <?php echo $tablaPreciario ?>
		                           </div>
		   				        </div>
		   				    </div>
		   				    </div>

		   				                <footer class="footer hidden-xs-down">
		   				                    <p>Â© Material Admin Responsive. All rights reserved.</p>

		   				                    <ul class="nav footer__nav">
		   				                        <a class="nav-link" href="#">Homepage</a>

		   				                        <a class="nav-link" href="#">Company</a>

		   				                        <a class="nav-link" href="#">Support</a>

		   				                        <a class="nav-link" href="#">News</a>

		   				                        <a class="nav-link" href="#">Contacts</a>
		   				                    </ul>
		                   </footer>
            </section>
            
        </main>
<!-- Small -->

                            <div class="modal fade" role="dialog" id="modalRegPreciario" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REGISTRO DE PRECIARIO</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formRegPreciario" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="selecteecc1">EECC: </label>
                                                        <select id="selecteecc1" name="selecteecc1" class="select2 form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="selectZonal1">ZONAL: </label>
                                                        <select id="selectZonal1" name="selectZonal1" class="select2 form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="selectTipoPrecio1">Tipo Precio: </label>
                                                        <select id="selectTipoPrecio1" name="selectTipoPrecio1" class="select2 form-control">
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="costo">COSTO: </label>
                                                        <input id="costo" placeholder="Ingrese costo.." type="number" class="form-control">
                                                    </div>
                                                    <div class="col-sm-12 form-group">
                                                        <label for="selectEstacion1">ESTACION: </label>
                                                        <select id="selectEstacion1" name="selectEstacion1" class="select2 form-control" multiple>
                                                        </select>
                                                    </div>
                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSaveMat" class="btn btn-success" onclick="savePreciario()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="modal fade" role="dialog" id="modalEditPreciario" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModalEdit" style="margin: auto;font-weight: bold;" class="modal-title">EDITAR PRECIARIO</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formEditPreciario" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                <div class="col-sm-6 form-group">
                                                        <label for="selecteecc2">EECC: </label>
                                                        <select id="selecteecc2" name="selecteecc2" class="select2 form-control" disabled>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="selectZonal2">ZONAL: </label>
                                                        <select id="selectZonal2" name="selectZonal2" class="select2 form-control" disabled>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="selectTipoPrecio2">Tipo Precio: </label>
                                                        <select id="selectTipoPrecio2" name="selectTipoPrecio2" class="select2 form-control" disabled>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="costo2">COSTO: </label>
                                                        <input id="costo2" placeholder="Ingrese costo.." type="number" class="form-control">
                                                    </div>
                                                    <div class="col-sm-12 form-group">
                                                        <label for="selectEstacion2">ESTACION: </label>
                                                        <select id="selectEstacion2" name="selectEstacion1" class="select2 form-control" disabled>
                                                        </select>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contEdit">
                                            <button type="button" id="btnSaveEdit" class="btn btn-success" onclick="updatePreciario()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


        <!-- Older IE warning message -->
        

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
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script type="text/javascript">

                var idEECCGlob = null;
                var idZonalGlob = null;
                var idTipoPrecioGlob = null;
                var idEstacionGlob = null;

            function openModalRegPreciario(){
                $.ajax({
                    type: 'POST',
                    url: 'getCombosPreciario'
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#selecteecc1').html(data.cmbEECC); 
                        $('#selectZonal1').html(data.cmbZonal); 
                        $('#selectEstacion1').html(data.cmbEstacion); 
                        $('#selectTipoPrecio1').html(data.cmbTipoPrecio);
                        modal('modalRegPreciario');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al cargar el combo');
                    }
                });
            }


            function openEditPreciario(component){
                idEECCGlob = $(component).data('ideecc');
                idZonalGlob = $(component).data('idzonal');
                idTipoPrecioGlob = $(component).data('idprecdiseno');
                idEstacionGlob = $(component).data('idestacion');

                console.log('idEECCGlob:',idEECCGlob);
                console.log('idZonalGlob:',idZonalGlob);
                console.log('idTipoPrecioGlob:',idTipoPrecioGlob);
                console.log('idEstacionGlob:',idEstacionGlob);

                $.ajax({
                    type: 'POST',
                    url: 'getDetPreciario',
                    data: {
                        idEECC : idEECCGlob,
                        idZonal : idZonalGlob,
                        idEstacion : idEstacionGlob,
                        idTipoPrecio : idTipoPrecioGlob
                    }
                    
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#selecteecc2').html(data.cmbEECC); 
                        $('#selectZonal2').html(data.cmbZonal); 
                        $('#selectEstacion2').html(data.cmbEstacion); 
                        $('#selectTipoPrecio2').html(data.cmbTipoPrecio);
                        $('#costo2').val(data.costo);
                        modal('modalEditPreciario');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al traer la partida');
                    }
                });

            }

            function savePreciario(){

                var idEECC = $.trim($("#selecteecc1").val());
                var idZonal = $.trim($("#selectZonal1").val());
                var idEstacion = $.trim($("#selectEstacion1").val());
                var idTipoPrecio = $.trim($("#selectTipoPrecio1").val());
                var costo = $.trim($("#costo").val());

                jsonValida = { idEECC: idEECC, idZonal: idZonal, idEstacion: idEstacion, idTipoPrecio : idTipoPrecio, costo : costo};

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'regPreciario',
                    data: {
                        idEECC : idEECC,
                        idZonal : idZonal,
                        idEstacion : idEstacion,
                        idTipoPrecio : idTipoPrecio,
                        costo : costo
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        $('#contTabla').html(data.tablaPreciario);
                        initDataTable('#data-table');
                        modal('modalRegPreciario');
                        mostrarNotificacion('success', 'Success', data.msj);
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });

            }

            function updatePreciario(){
                var costo = $.trim($("#costo2").val());

                jsonValida = {costo : costo};

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar el costo para poder actualizar!!');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'updatePreciario',
                    data: {
                        idEECC : idEECCGlob,
                        idZonal : idZonalGlob,
                        idEstacion : idEstacionGlob,
                        idTipoPrecio : idTipoPrecioGlob,
                        costo : costo
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        $('#contTabla').html(data.tablaPreciario);
                        initDataTable('#data-table');
                        modal('modalEditPreciario');
                        mostrarNotificacion('success', 'Success', data.msj);
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });
                

            }

            function filtrarTabla(){

                var idEECC = $.trim($('#selecteecc').val()); 
                var idZonal = $.trim($('#selectZonal').val()); 
                var idEstacion = $.trim($('#selectEstacion').val()); 
                var tipoPrecio = $.trim($('#selectTipoPrecio').val()); 

                console.log('idEECC:',idEECC);
                console.log('idZonal:',idZonal);
                console.log('idEstacion:',idEstacion);
                console.log('tipoPrecio:',tipoPrecio);

                $.ajax({
                    type	:	'POST',
                    'url'	:	'getPreciarioByFiltros',
                    data	:	{ idEECC : idEECC,
                                  idZonal : idZonal,
                                  idEstacion : idEstacion,
                                  idPrecioDiseno : tipoPrecio
                                }
                }).done(function(data){
                    var data = JSON.parse(data);
                    if(data.error == 0){
                        $('#contTabla').html(data.tablaPreciario);
                        initDataTable('#data-table');
                    }else{
                        mostrarNotificacion('error','Hubo problemas al filtrar los datos!!');
                    }
                });

            }

            function openModalConfiDelete(component){
                idGlob = $(component).data('id');
                var proyecto = $.trim($('#selectProy').val()); 
                var estacion = $.trim($('#selectEstacion').val()); 
                var partida = $.trim($('#selectPartida').val()); 
                swal({
                    title: 'Esta seguro de realizar la siguiente Operacion??',
                    text: 'Asegurese de validar la informacion!!',
                    type: 'warning',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'OK!'

                }).then(function () {
                    if(idGlob != null){
                        $.ajax({
                            type: 'POST',
                            url: 'deleteProyEstPart',
                            data: {
                                id : idGlob
                            }
                        }).done(function (data) {
                            data = JSON.parse(data);
                            if (data.error == 0){
                                $('#contTabla').html(data.tablaProyEstPart);
                                initDataTable('#data-table');
                                mostrarNotificacion('success', 'Success', 'Se elimino correctamente!!');
                            } else {
                                mostrarNotificacion('error', 'Error', data.msj);
                            }
                        });
                    }
                });
            }


        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>