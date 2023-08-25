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
                           <h2>MANTENIMIENTO DE MATERIAL</h2>
                           <hr>
		                    <div class="card">		   				                    
		                    
                                <div class="card-block"> 
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>REGISTRAR MATERIAL</label><br>
                                                <button class="btn btn-success waves-effect" type="button" onclick="openModalRegMaterial()">Nuevo Material</button>
                                            </div>
                                        </div>
                                
                                    </div>
		   				            <div id="contTabla" class="table-responsive">
								        <?php echo $tablaMaterial?>
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

                            <div class="modal fade" role="dialog" id="modalRegMaterial" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REGISTRO DE MATERIAL </h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formRegMat" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descripCod">CODIGO: </label>
                                                        <input id="descripCod" placeholder="Ingrese codigo.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descripMat">MATERIAL: </label>
                                                        <input id="descripMat" placeholder="Ingrese descripcion.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="costoMat">COSTO(S/): </label>
                                                        <input id="costoMat" placeholder="Ingrese costo.." type="number" class="form-control">
                                                    </div>

                                                    <div class="col-sm-6 form-group" id="contEstadoMat">
                                                        <label for="idEstadoMat">ESTADOS: </label>
                                                        <select id="idEstadoMat" name="idEstadoMat" class="select2 form-control">
                                                            <option value="">Seleccionar Estado</option>
                                                            <option value="1">Activo</option>
                                                            <option value="2">Inactivo</option>
                                                            <option value="3">Phase Out</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group" id="contTipoMat">
                                                        <label for="idTipoMat">TIPOS: </label>
                                                        <select id="idTipoMat" name="idTipoMat" class="select2 form-control">
                                                            <option value="">Seleccionar Tipo</option>
                                                            <option value="1">BUCLE</option>
                                                            <option value="2">NO BUCLE</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descUDM">UNIDAD DE MEDIDA: </label>
                                                        <select id="descUDM" name="descUDM" class="select2 form-control">
                                                        </select>
                                                    </div>

                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSaveMat" class="btn btn-success" onclick="saveMaterial()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="modal fade" role="dialog" id="modalEditMaterial" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModalEdit" style="margin: auto;font-weight: bold;" class="modal-title">EDITAR MATERIAL </h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formEditMat" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descCodEdit">CODIGO: </label>
                                                        <input id="descCodEdit" placeholder="Ingrese codigo.." type="text" class="form-control" disabled>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descMatEdit">MATERIAL: </label>
                                                        <input id="descMatEdit" placeholder="Ingrese descripcion.." type="text" class="form-control">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="costoMatEdit">COSTO(S/): </label>
                                                        <input id="costoMatEdit" placeholder="Ingrese costo.." type="number" class="form-control">
                                                    </div>

                                                    <div class="col-sm-6 form-group" id="contEstadoMat">
                                                        <label for="idEstadoMatEdit">ESTADOS: </label>
                                                        <select id="idEstadoMatEdit" name="idEstadoMatEdit" class="select2 form-control">
                                                            <option value="">Seleccionar Estado</option>
                                                            <option value="1">Activo</option>
                                                            <option value="2">Inactivo</option>
                                                            <option value="3">Phase Out</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group" id="contTipoMat">
                                                        <label for="idTipoMatEdit">TIPOS: </label>
                                                        <select id="idTipoMatEdit" name="idTipoMatEdit" class="select2 form-control">
                                                            <option value="">Seleccionar Tipo</option>
                                                            <option value="1">BUCLE</option>
                                                            <option value="2">NO BUCLE</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label for="descUDMEdit">UNIDAD DE MEDIDA: </label>
                                                        <select id="descUDMEdit" name="descUDMEdit" class="select2 form-control">

                                                        </select>
                                                    </div>

                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contEdit">
                                            <button type="button" id="btnSaveEdit" class="btn btn-success" onclick="updateMaterial()">Guardar</button>
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

            var idMaterialGlob = null;

            function openModalRegMaterial(){
                $("#descripCod").val(null);
                $("#descripMat").val(null);
                $("#costoMat").val(null);
                $("#idEstadoMat").val(null);
                $('#idEstadoMat').change();
                $("#idTipoMat").val(null);
                $('#idTipoMat').change();

                $.ajax({
                    type: 'POST',
                    url: 'getCmbUdm'
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#descUDM').html(data.cmbUDM);
                        modal('modalRegMaterial');
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al cargar el combo');
                    }
                });
            }


            function openEditMaterial(component){
                idMaterialGlob = $(component).data('idmaterial');
                $.ajax({
                    type: 'POST',
                    url: 'getDetMatEdit',
                    data: { codigoMaterial : idMaterialGlob}
                    
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#descCodEdit').val(data.codMat);
                        $('#descMatEdit').val(data.descrip_material);
                        $('#costoMatEdit').val(data.costoMaterial);
                        $('#idEstadoMatEdit').val(data.flg_estado);
                        $('#idEstadoMatEdit').change();
                        $('#idTipoMatEdit').val(data.tipoMat);
                        $('#idTipoMatEdit').change();
                        $('#descUDMEdit').html(data.cmbUDM);
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al traer los responsables');
                    }
                });

                modal('modalEditMaterial');
            }

            function saveMaterial(){
                var codigoMaterial = $.trim($("#descripCod").val());
                var descripMat = $.trim($("#descripMat").val());
                var costoMat = $.trim($("#costoMat").val());
                var estadoMaterial = $.trim($("#idEstadoMat").val());
                var tipoMaterial = $.trim($("#idTipoMat").val());
                var unidadMedida = $.trim($("#descUDM").val());
                var desc_udm = $.trim($('#descUDM option:selected').text());

                jsonValida = { codigoMaterial: codigoMaterial, descripMat: descripMat, costoMat: costoMat, estadoMaterial: estadoMaterial, tipoMaterial: tipoMaterial, unidadMedida: unidadMedida, desc_udm : desc_udm };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'regMaterial',
                    data: {
                        codigoMaterial : codigoMaterial,
                        descripMat : descripMat,
                        costoMat : costoMat,
                        estadoMaterial : estadoMaterial,
                        tipoMaterial : tipoMaterial,
                        unidadMedida : unidadMedida,
                        descUDM : desc_udm
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        $('#contTabla').html(data.tbMateriales);
                        initDataTable('#data-table');
                        modal('modalRegMaterial');
                        mostrarNotificacion('success', 'Success', data.msj);
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });

            }

            function updateMaterial(){
                var descripMat = $.trim($("#descMatEdit").val());
                var costoMat = $.trim($("#costoMatEdit").val());
                var estadoMaterial = $.trim($("#idEstadoMatEdit").val());
                var tipoMaterial = $.trim($("#idTipoMatEdit").val());
                var unidadMedida = $.trim($("#descUDMEdit").val());
                var desc_udm = $.trim($('#descUDMEdit option:selected').text());

                jsonValida = {descripMat: descripMat, costoMat: costoMat, estadoMaterial: estadoMaterial, tipoMaterial: tipoMaterial, unidadMedida: unidadMedida, desc_udm : desc_udm };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                if(idMaterialGlob != null){
                    $.ajax({
                        type: 'POST',
                        url: 'updateMaterial',
                        data: {
                            codigoMaterial : idMaterialGlob,
                            descripMat : descripMat,
                            costoMat : costoMat,
                            estadoMaterial : estadoMaterial,
                            tipoMaterial : tipoMaterial,
                            unidadMedida : unidadMedida,
                            descUDM : desc_udm
                        }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0){
                            $('#contTabla').html(data.tbMateriales);
                            initDataTable('#data-table');
                            modal('modalEditMaterial');
                            mostrarNotificacion('success', 'Success', data.msj);
                        } else {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }
                    });
                }

            }

            // function deleteSubProy(component){

            //     var idSubProyecto = $(component).data('idsubproy');

            //     if(idSubProyecto != null){
            //         $.ajax({
            //             type: 'POST',
            //             url: 'deleteSubProyConfigAutoAprobPO',
            //             data: {
            //                 idSubProyecto: idSubProyecto
            //             }
            //         }).done(function (data) {
            //             data = JSON.parse(data);
            //             if (data.error == 0){
            //                 $('#contTabla').html(data.tbSubProy);
            //                 initDataTable('#data-table');
            //                 mostrarNotificacion('success', 'Success', data.msj);
            //             } else {
            //                 mostrarNotificacion('error', 'Error', data.msj);
            //             }
            //         });
            //     }else{
            //         mostrarNotificacion('error', 'Error', 'No se capturo el Subproyecto a eliminar!!');
            //     }
            // }

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>