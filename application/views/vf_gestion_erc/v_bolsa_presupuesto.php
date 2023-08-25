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
                           <h2>BOLSA PRESUPUESTO</h2>
                           <hr>
		                    <div class="card">		   				                    
		                    
                                <div class="card-block"> 
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>REGISTRAR BOLSA</label><br>
                                                <button class="btn btn-success waves-effect" type="button" onclick="openModalRegiBolsa()">Nueva Bolsa</button>
                                                
                                            </div>
                                        </div>
                                
                                    </div>
		   				            <div id="contTabla" class="table-responsive">
								        <?php echo $tablaBolsaPresupuesto?>
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

                            <div class="modal fade" role="dialog" id="modalRegisBolsaPresupuesto" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REGISTRO DE CUENTA </h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formRegistrarEntidad" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <label for="idDescripCoti">Nombre de la Cuenta: </label>
                                                        <input id="descripCuenta" placeholder="Ingrese nombre.." type="text" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-sm-12 form-group" id="contDescrip">
                                                        <label for="idResponsable">Responsable: </label>
                                                        <select id="idResponsable" name="responsable" class="select2 form-control">
                                                            <!-- <option value="">Seleccionar Responsable</option>
                                                            <option value="1">Juan Perez</option>
                                                            <option value="2">Carlos Cuya</option>
                                                            <option value="3">Owen Saravia</option> -->
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4 form-group">
                                                        <label for="comment">Monto(S/) :</label>
                                                        <input type="number" class="form-control" id="montoBolsa">
                                                    </div>
                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSaveBolsa" class="btn btn-success" onclick="saveBolsaPresupuesto()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="modal fade" role="dialog" id="modalUpdateMonto" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">AUMENTAR MONTO</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formUpdate" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-12 form-group">
                                                        <label for="comment">Monto(S/):</label>
                                                        <input type="number" class="form-control" placeholder="Ingrese monto..." id="idNewMonto">
                                                    </div>
                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <button type="button" id="btnSaveBolsa" class="btn btn-primary" onclick="updateMontoBolsaPresu()">Guardar</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="modal fade" role="dialog" id="modalTransacciones" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">TRANSACCIONES</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div id="content" class="modal-body">
                                            <form id="formTransacc" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                                                <div class="row">
                                                    <div class="col-sm-12 tab-container">
                                                        <div id="contTablaDetTransa" class="table-responsive">
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer" id="contGuardar">
                                            <!-- <button type="button" id="btnSaveBolsa" class="btn btn-primary" onclick="updateMontoBolsaPresu()">Guardar</button> -->
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

            var idBolsaGlob = null;
            var montoInicial = null;
            var montoStock = null;
            var nroCuentaGlob = null;

            function openModalRegiBolsa(){
                $("#descripCuenta").val(null);
                $("#montoBolsa").val(null);

                $.ajax({
                    type: 'POST',
                    url: 'getResponsables'
                    
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#idResponsable').html(data.comboResponsables);
                    } else {
                        mostrarNotificacion('error', 'Error', 'Hubo un error al traer los responsables');
                    }
                });

                modal('modalRegisBolsaPresupuesto');
            }

            function saveBolsaPresupuesto(){
                var descripCuenta = ($("#descripCuenta").val()).trim();
                var idResponsable = $("#idResponsable").val();
                var monto = $("#montoBolsa").val();

                jsonValida = { descripCuenta: descripCuenta, idResponsable: idResponsable, monto: monto };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
                    mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'regBolsa',
                    data: {
                        descripCuenta: descripCuenta,
                        idResponsable: idResponsable,
                        monto: monto
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        $('#contTabla').html(data.tbBolsaPresupuesto);
                        initDataTable('#data-table');
                        modal('modalRegisBolsaPresupuesto');
                        mostrarNotificacion('success', 'Success', data.msj);
                    } else {
                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                });

            }

            function addMonto(component){
                idBolsaGlob = $(component).data('idbolsa');
                montoInicial = $(component).data('montoinicial');
                montoStock = $(component).data('montostock');
                var desc_cuenta = $(component).data('desc_cuenta');
                nroCuentaGlob =  $(component).data('nro_cuenta');

                $("#idNewMonto").val(null);
                modal('modalUpdateMonto');
            }

            function updateMontoBolsaPresu(){
                var newMonto = $("#idNewMonto").val();
                if(newMonto != null && newMonto != '' && newMonto != undefined && nroCuentaGlob != null){
                    $.ajax({
                    type: 'POST',
                    url: 'updateBolsa',
                    data: {
                        idBolsa: idBolsaGlob,
                        nroCuenta: nroCuentaGlob,
                        monto: newMonto,
                        montoInicial: montoInicial,
                        montoStock: montoStock
                    }
                    }).done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0){
                            $('#contTabla').html(data.tbBolsaPresupuesto);
                            initDataTable('#data-table');
                            modal('modalUpdateMonto');
                            mostrarNotificacion('success', 'Success', 'Se actualiz&oacute; correctamente el monto!!');
                        } else {
                            mostrarNotificacion('error', 'Error', 'Hubo un error al actualizar el monto');
                        }
                    });
                }else{
                    mostrarNotificacion('error', 'Error', 'Dede ingresar un monto');
                }
            }

		
        function filtrarTabla(){
            //var itemplan = $.trim($('#itemplan').val());
            var erroItemPlan = '';
            var itemplan = $.trim($('#txtItemPlan').val());
            //validar item plan
            //mostrarNotificacion('error','Hubo problemas al filtrar los datos!');

            if(itemplan.length < 13 && itemplan.length >= 1)
                erroItemPlan = 'ItemPlan Invalido.'

            var tipoPlanta = $.trim($('#selectTipoPlanta').val());
            var nombreproyecto = $.trim($('#nombreproyecto').val());
            var nodo = $.trim($('#nodo').val());
            var zonal = $.trim($('#selectZonal').val());
            var proy = $.trim($('#selectProy').val());
            var subProy = $.trim($('#selectSubProy').val());
            var estado = $.trim($('#estado').val());
            var selectMesPrevEjec = $.trim($('#selectMesPrevEjec').val());

            var fechaInicio0 = $('#fechaInicio').val();
            var fechaFin0 =  $('#fechaFin').val();

            var fechaInicio = fechaInicio0.replace(/-/g, '/');
            var fechaFin = fechaFin0.replace(/-/g, '/');
            
            var fechaDestinoDefault = '2018/12/31';
            var fechaDestino = '';
            var filtroPrevEjec = '';
            
            if(fechaFin0 == ''){
                //console.log('fecha fin esta vacia');
                //console.log('fecha destino sera: '+fechaDestinoDefault);
                fechaDestino = fechaDestinoDefault;
            }else{
                //console.log('fechaFin (destino) es: '+fechaFin);
                fechaDestino = fechaFin;
            }

            if( fechaInicio0 != '' ){
                filtroPrevEjec = " AND p.fechaPrevEjec BETWEEN '"+fechaInicio+"' AND '"+fechaDestino+"' ";
            }else{
                filtroPrevEjec = "";
            }

            if(erroItemPlan == ''){

       	    $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'liquidisefil',
       	    	data	:	{itemplan : itemplan,
                            nombreproyecto : nombreproyecto,
                            nodo : nodo,
                            zonal     : zonal,
                            proy  :	proy,
            	    	    subProy  :    subProy,
         	    	        estado : estado,
                            filtroPrevEjec : filtroPrevEjec,
                            tipoPlanta : tipoPlanta
        	    	        //area : area
                           },
       	    	'async'	:	false
       	    })
       	    .done(function(data){
       	    	var data	=	JSON.parse(data);
       	    	if(data.error == 0){           	    	          	    	   
       	    		$('#contTabla').html(data.tablaAsigGrafo)
       	    	    initDataTable('#data-table');
       	    		
       			}else if(data.error == 1){
       				
       				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
       			}
       		  });

            }else{
                mostrarNotificacion('error','ItemPlan',erroItemPlan);
            }
            
            
        }

        function abrirModalTransacc(component){
            idBolsaGlob = $(component).data('idbolsa');
            var nroCuenta = $(component).data('nro_cuenta');
            $.ajax({
                type: 'POST',
                url: 'getTransa',
                data: {
                    nroCuenta: nroCuenta
                }
            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contTablaDetTransa').html(data.tablaTransacc);
                    initDataTable('#tabla_detalle');
                    modal('modalTransacciones'); 
                } else {
                    mostrarNotificacion('error', 'Error', 'No hay trasacciones para mostrar!!');
                }
            });
        }


        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>