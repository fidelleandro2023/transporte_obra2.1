<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

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
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
                </div>
 <?php include 'application/views/v_opciones.php';?>
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
                               <h2 >EDITAR SUBPROYECTO CV</h2>
                               <hr>

                               <div class="card">
                                    <div class="card-block">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <label>ITEMPLAN</label>
                                                    <input id="txtItemPlan" type="text" class="form-control input-mask" placeholder="ItemPlan" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                                    <!-- <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">Buscar</button> -->
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-2">
                                                <div class="form-group">
                                                    <br><br>
                                                    <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">Buscar</button>
                                                </div>
                                            </div>

                                        </div>

                                        <div id="contTabla" class="table-responsive">
                                            <?php echo $tablaItemplan ?>
                                        </div>
                                    </div>
                                </div>

		   				    </div>

		   				                <footer class="footer hidden-xs-down">
                                            <p>Telef&oacute;nica del Per&uacute;</p>

                                           
                           </footer>
            </section>

        </main>


<!-- Small -->
<div class="modal fade" id="modalDetItemplan">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="margin: auto;">
                <h5 style="font-weight: bold;" class="modal-title pull-left">MODIFICAR SUBPROYECTO PLAN OBRA NRO  : <label id="nroitemplan" style="font-weight: bold;"></label></h5>
            </div>
            <div class="modal-body">
                <form id="formPlanobra" method="post" class="form-horizontal"> 
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>SUBPROYECTO ANTERIOR</label>
                                <input id="subproyecAnterior" name="subproyecAnterior" type="text" class="form-control" readonly=true>
                                <input id="inputIDEstado" name="inputIDEstado" type="hidden" class="form-control">
                                <input id="inputItemPlan" name="inputItemPlan" type="hidden" class="form-control">
                            </div>                            
                         </div>
                                
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>NUEVO SUBPROYECTO</label>
                                    <input id="newSubproyect" name="newSubproyect" type="text" class="form-control" readonly=true>
                            </div>
                        </div>
                    </div>

                    <div id="mensajeForm"></div>

                    <div class="form-group" style="text-align: right;">
                        <div class="col-sm-12">
                            <button id="btnSave" type="button" class="btn btn-primary" onclick="abrirModalValidacion()">Guardar</button>
                            <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                        </div>
                        </div>
                </form>
            </div>                    
        </div>
    </div>
</div>




<div class="modal fade" id="modalAlertValidacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
        <div class="modal-header" style="background:red">
            <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta operaci&oacute;n?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <a>Al aceptar, se cambiar&aacute; el subproyecto del itemplan solicitado.</a>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="updateSubProyecto()">Aceptar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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

            var itemplanGlob = null;
            var idSubProyectGlob = null;
            var idEmpresaColabGlob = null;
            var idEmpresaColabCVGLob = null;
            var idEstadoPlanGlob = null
            var flgModal = null;

            function openModalDetItemplan(component){

                itemplanGlob = $(component).data('itemplan');
                idSubProyectGlob = $(component).data('idsuproyect');
                idEmpresaColabGlob = $(component).data('ideecc');
                idEmpresaColabCVGlob = $(component).data('ideeccv');
                idEstadoPlanGlob = $(component).data('estado');

                var descSuprproyectAnt = $(component).data('subproyec');
                var newSuproyect = '';
                
                $.ajax({
                        type: 'POST',
                        url: 'verificaPTRCV',
                        data: {
                            itemplan: itemplanGlob,
                            idSubProyecto: idSubProyectGlob,
                            idEstadoPlan : idEstadoPlanGlob
                        }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {

                        $('#nroitemplan').text(itemplanGlob);
                        $('#subproyecAnterior').val(descSuprproyectAnt);

                        if(idSubProyectGlob == 96){//BUCLE RESIDENCIAL
                            newSuproyect = 'CRECIMIENTO VERTICAL INTEGRAL';
                        }else if (idSubProyectGlob == 97){// INTEGRAL RESIDENCIAL
                            newSuproyect = 'CRECIMIENTO VERTICAL BUCLE';
                        }else if(idSubProyectGlob == 99){// BUCLE  NEGOCIO I
                            newSuproyect = 'CRECIMIENTO VERTICAL INTEGRAL NEGOCIO I - INTEGRAL';
                        }else if(idSubProyectGlob == 98){// INTEGRAL  NEGOCIO I
                            newSuproyect = 'CRECIMIENTO VERTICAL INTEGRAL NEGOCIO I - BUCLE';
                        }else if(idSubProyectGlob == 395){// BUCLE  NEGOCIO II
                            newSuproyect = 'CRECIMIENTO VERTICAL INTEGRAL NEGOCIO II - INTEGRAL';
                        }else if(idSubProyectGlob == 396){// INTEGRAL  NEGOCIO II
                            newSuproyect = 'CRECIMIENTO VERTICAL INTEGRAL NEGOCIO II - BUCLE';
                        }
                        $('#newSubproyect').val(newSuproyect);

                        if(data.msjAviso != null){
                            flgModal = 0;
                            swal({
                                title: data.msjAviso,
                                text: 'Asegurese de validar la informacion seleccionada!!',
                                type: 'warning',
                                showCancelButton: true,
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: 'Si, cambiar subproyecto!',
                                cancelButtonClass: 'btn btn-secondary'
                            }).then(function(){
                                updateSubProyecto();
                            });
                        }else{
                            console.log('flgModal: ',flgModal);
                            flgModal = 1;
                            modal('modalDetItemplan');
                        }
                        
                    } else {
                        mostrarNotificacion('warning', 'Error', data.msj);
                    }
                });

                
            }

            function abrirModalValidacion(){
                modal('modalAlertValidacion');
            }

            function updateSubProyecto(){

                if( itemplanGlob != null && idSubProyectGlob != null && idEmpresaColabGlob != null && idEmpresaColabCVGlob != null && idEstadoPlanGlob != null){
                    $.ajax({
                            type: 'POST',
                            url: 'updateSubproCV',
                            data: {
                                itemplan: itemplanGlob,
                                idSubProyecto: idSubProyectGlob,
                                idEmpresaColab: idEmpresaColabGlob,
                                idEmpresaColabCV : idEmpresaColabCVGlob,
                                idEstadoPlan : idEstadoPlanGlob
                            }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTabla').html(data.tablaItemplan);
                            initDataTable('#data-table');
                            console.log('flgModal: ',flgModal);
                            if(flgModal == 1){
                                modal('modalAlertValidacion');
                                modal('modalDetItemplan'); 
                            }
                            mostrarNotificacion('success', 'Operacion Exitosa', 'Se actualizo correctamente!');
                        } else {
                            mostrarNotificacion('warning', 'Error', 'Hubo un error al actualizar el subproyecto!!');
                        }
                        flgModal = null;
                    });
                }
            }


            

            function filtrarTabla(){

                var erroItemPlan = '';
                var itemplan = $.trim($('#txtItemPlan').val());


                if(itemplan.length > 13 || (itemplan.length >= 1 && itemplan.length < 12)){
                    erroItemPlan = 'ItemPlan Invalido.'
                }


                if(erroItemPlan == ''){

                    $.ajax({
                        type	:	'POST',
                        'url'	:	'getItemplanCV',
                        data	:	{ itemplan : itemplan
                                    },
                        'async'	:	false
                    })
                    .done(function(data){
                        var data	=	JSON.parse(data);
                        if(data.error == 0){
                            $('#contTabla').html(data.tablaItemplan);
                            initDataTable('#data-table');

                        }else if(data.error == 1){

                            mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                        }
                    });

                }else{
                    if(erroItemPlan != '' && erroNroRetiro == ''){
                        mostrarNotificacion('error','ItemPlan',erroItemPlan);
                    }else{
                        mostrarNotificacion('error','Error','Itemplan invalido!!');
                    }

                }


            }

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>