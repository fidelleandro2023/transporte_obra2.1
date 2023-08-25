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
                           <h2>LIQUIDACI&Oacute;N ADMINISTRATIVA</h2>
                           <hr>
		                    <div class="card">		   				                    
		                    
		                        <div class="card-block"> 
                                <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>TIPO PLANTA</label>

                                        <select id="selectTipoPlanta" name="selectTipoPlanta" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                          <?php                                                    
                                                        foreach($listaTipoPlanta->result() as $row){                      
                                                    ?> 
                                                     <option value="<?php echo $row->idTipoPlanta ?>"><?php echo $row->tipoPlantaDesc ?></option>
                                                     <?php }?>
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>PROYECTO</label>
    
                                            <select id="selectProy" name="selectProy" class="select2" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                            <?php                                                    
                                                        foreach($listaProyectos->result() as $row){                      
                                                    ?> 
                                                     <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                                     <?php }?>
                                               
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>SUB PROYECTO</label>
    
                                            <select id="selectSubProy" name="selectSubProy" class="select2" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                            <?php                                                    
                                                        foreach($listaSubProy->result() as $row){                      
                                                    ?> 
                                                     <option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
                                                     <?php }?>
                                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>ITEMPLAN</label>
                                            <input id="txtItemPlan" type="text" class="form-control input-mask" placeholder="ItemPlan" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                            <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">Buscar</button>
                                           
                                        </div>
                                    </div>
                                    
                                     <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>NOMBRE</label>
                                                <input id="nombreproyecto" type="text" class="form-control input-mask" placeholder="nombre del proyecto" autocomplete="off" maxlength="200" style="border-bottom: 1px solid lightgrey">
                                            <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">Buscar</button>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>MDF/NODO</label>
    
                                            <select id="nodo" name="nodo" class="select2" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                            <?php                                                    
                                                        foreach($listaNodos->result() as $row){                      
                                                    ?> 
                                                     <option value="<?php echo $row->idCentral ?>"><?php echo $row->codigo ?>-<?php echo $row->tipoCentralDesc ?></option>
                                                     <?php }?>
                                               
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>ZONAL</label>

                                            <select id="selectZonal" name="selectZonal" class="select2"  multiple onchange="filtrarTabla()">
                                                 <option>&nbsp;</option>
                                            <?php                                                    
                                                        foreach($listaZonal->result() as $row){                      
                                                    ?> 
                                                     <option value="<?php echo $row->idZonal ?>"><?php echo $row->zona ?></option>
                                                     <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>Estado</label>

                                            <select id="estado" name="estado" class="select2" onchange="filtrarTabla()">
                                                 <option>&nbsp;</option>
                                            <?php                                                    
                                                        foreach($listaEstados->result() as $row){                      
                                                    ?> 
                                                     <option value="<?php echo $row->idEstadoPlan ?>"><?php echo utf8_decode($row->estadoPlanDesc) ?></option>
                                                     <?php }?>
                                            </select>
                                        </div>
                                    </div>

                                    
                                    <!--
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>CON ITEM PLAN</label>

                                            <select id="selectHasItemPlan" name="selectHasItemPlan" class="select2" onchange="filtrarTabla()">
                                                 <option>&nbsp;</option>
                                            <option selected value="SI">SI</option>
                                            <option value="NO">NO</option>
                                            </select>
                                        </div>
                                    </div>-->
                                <!--<div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label>ESTADO</label>

                                        <select id="selectEstado" name="selectEstado" class="select2" >
                                             <option>&nbsp;</option>
                                        <option value="01">01</option>
                                        <option value="003">003</option>
                                        </select>
                                    </div>
                                </div>-->
                                    <!--
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label>MES PREVISTO EJECUCION</label>

                                            <select id="selectMesPrevEjec" name="selectMesPrevEjec" class="select2" onchange="filtrarTabla()">
                                                 <option>&nbsp;</option>
                                           <option value="ENE">ENERO</option>
                                           <option value="FEB">FEBRERO</option>
                                           <option value="MAR">MARZO</option>
                                           <option value="ABR">ABRIL</option>
                                           <option value="MAY">MAYO</option>
                                           <option value="JUN">JUNIO</option>
                                           <option value="JUL">JULIO</option>
                                           <option value="AGO">AGOSTO</option>
                                           <option value="SEP">SEPTIEMBRE</option>
                                           <option value="OCT">OCTUBRE</option>
                                           <option value="NOV">NOVIEMBRE</option>
                                           <option value="DIC">DICIEMBRE</option>
                                           
                                            </select>
                                        </div>
                                    </div>-->
                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <span>  FECHA PREVISTA DE EJECUCION</span>
                                            <label for="fechaInicio">Desde: <input type="date"  name="fechaInicio" id="fechaInicio" onchange="filtrarTabla()"></label>

                                            
                                            <hr>
                                            <label for="fechaFin">Hasta: <input type="date"  name="fechaFin" id="fechaFin" onchange="filtrarTabla()"></label>
                                            
                                        </div>
                                    </div>
                                
                            </div>
		   				                            <div id="contTabla" class="table-responsive">
								                            <?php echo $tablaAsigGrafo?>
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
                                                <input id="inputVR" type="text" class="form-control input-mask" placeholder="Comentario" autocomplete="off" maxlength="400" style="border-bottom: 1px solid lightgrey">
                                                <i class="form-group__bar"></i>
                                             </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="botonConfirmar" type="button" onclick="asignarExpediente(this)" class="btn btn-info" data-dismiss="modal">CONFIRMAR</button>
                                            <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
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
      
		function changeEstadoPlan(component){
			var idEstado    = $(component).attr('data-estado');
         	var itemPl      = $(component).attr('data-itmpl');
			var fg_paquetizado = $(component).attr('data-fg_paquetizado');
            var nextEstado = '';
			var msj = '';
			if(fg_paquetizado == 2) {
				if(idEstado == 2 ){
					msj = 'Desea Liquidar Administrativamente el itemplan '+itemPl+' a APROBACI&Oacute;N?';
				} else if(idEstado == 19) {
					msj = "Desea retroceder la licencia y enviar a APROBACI&Oacute;N?"
				} else if(idEstado == 20) {
					msj = 'Desea Liquidar Administrativamente el itemplan '+itemPl+' a OBRA?';
				}
			} else {
				if(idEstado == 2){
					nextEstado = 'DISE&Ntilde;O EJECUTADO';
				}else if(idEstado == 7){
					nextEstado = 'EN OBRA';
				}else if(idEstado == 11){
					nextEstado = 'EN OBRA';
				}
			}
         	
			swal({
                title: msj,
                text: 'Recuerde que esta accion salta el flujo normal del itemplan!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, confirmar!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){
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
            	$.ajax({
           	    	type	:	'POST',
           	    	'url'	:	'updatePlanDi',
           	    	data	:	{itemSelect : itemPl,
           	    				estadoSelect : idEstado,
           	    				itemplan : itemplan,
                                nombreproyecto : nombreproyecto,
                                nodo : nodo,
                                zonal     : zonal,
                                proy  :	proy,
                	    	    subProy  :    subProy,
             	    	        estado : estado,
                                filtroPrevEjec : filtroPrevEjec,
                                tipoPlanta : tipoPlanta},
           	    	'async'	:	false
           	    })
           	    .done(function(data){
           	    	var data	=	JSON.parse(data);
           	    	if(data.error == 0){
						mostrarNotificacion('success','Correcto!', 'Se hizo la liquidacion administrativa correctamente');						
           	    		$('#contTabla').html(data.tablaAsigGrafo)
           	    	    initDataTable('#data-table');
           	    		
           			}else if(data.error == 1){
           				
           				mostrarNotificacion('error','Hizo algo indebido!', data.msj);
           			}
           		  });
            });
			
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
            
            console.log('fechaInicio es: '+fechaInicio);
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

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>