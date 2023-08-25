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
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
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
                   <a href="https://www.movistar.com.pe/" title="Entel Perú"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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

            <aside class="chat">
                <div class="chat__header">
                    <h2 class="chat__title">Chat <small>Currently 20 contacts online</small></h2>

                    <div class="chat__search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </div>

                <div class="listview listview--hover chat__buddies scrollbar-inner">
                    <a class="listview__item chat__available">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/7.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>hey, how are you doing.</p>
                        </div>
                    </a>

                    <a class="listview__item chat__available">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/5.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>hmm...</p>
                        </div>
                    </a>

                    <a class="listview__item chat__away">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/3.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>all good</p>
                        </div>
                    </a>

                    <a class="listview__item">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/8.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>morbi leo risus portaac consectetur vestibulum at eros.</p>
                        </div>
                    </a>

                    <a class="listview__item">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/6.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>fusce dapibus</p>
                        </div>
                    </a>

                    <a class="listview__item chat__busy">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/9.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>cras mattis consectetur purus sit amet fermentum.</p>
                        </div>
                    </a>
                </div>

                <a href="messages.html" class="btn btn--action btn--fixed btn-danger"><i class="zmdi zmdi-plus"></i></a>
            </aside>

            <section class="content content--full">
		                   <div class="content__inner">
                           <h2>BANDEJA DE APROBACI&Oacute;N</h2>
		   				                    <div class="card">		   				                    
		   				                    
		   				                        <div class="card-block"> 
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-4">
                                                            <div class="form-group">
                                                                <label>SUB PROYECTO</label>
                        
                                                                <select id="selectSubProy" name="selectSubProy" class="select2" onchange="filtrarTabla()" multiple>
                                                                <option>&nbsp;</option>
                                                                <?php                                                    
                                                                            foreach($listaSubProy->result() as $row){                      
                                                                        ?> 
                                                                         <option value="<?php echo $row->subProyectoDesc ?>"><?php echo $row->subProyectoDesc ?></option>
                                                                         <?php }?>
                                                                   
                                                                </select>
                                                            </div>
                                                        </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>EECC</label>

                                        <select id="selectEECC" name="selectEECC" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaEECC->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->empresaColabDesc ?>"><?php echo $row->empresaColabDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>FASE</label>
                                        <select id="selectFase" name="selectFase" class="select2 form-control" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                                <?php                                                    
                                                    foreach($listafase->result() as $row){                      
                                                ?> 
                                                    <option value="<?php echo $row->idFase ?>"><?php echo $row->faseDesc ?></option>
                                                    <?php }?>
                                                
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>ZONAL</label>

                                        <select id="selectZonal" name="selectZonal" class="select2" onchange="filtrarTabla()" multiple>
                                             <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaZonal->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->zonalDesc ?>"><?php echo $row->zonalDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label>CON ITEM PLAN</label>

                                        <select id="selectHasItemPlan" name="selectHasItemPlan" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                        <option selected value="SI">SI</option>
                                        <option value="NO">NO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label>ESTADO</label>

                                        <select id="selectEstado" name="selectEstado" class="select2" onchange="filtrarTabla()" multiple>
                                             <option>&nbsp;</option>
                                        <option value="01">01</option>
                                       <!-- <option value="001">001</option>-->
                                        <option value="003">003</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label>MES PREVISTO EJECUCION</label>

                                        <select id="selectMesEjec" name="selectMesEjec" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                       <option value="01">ENERO</option>
                                       <option value="02">FEBRERO</option>
                                       <option value="03">MARZO</option>
                                       <option value="04">ABRIL</option>
                                       <option value="05">MAYO</option>
                                       <option value="06">JUNIO</option>
                                       <option value="07">JULIO</option>
                                       <option value="08">AGOSTO</option>
                                       <option value="09">SEPTIEMBRE</option>
                                       <option value="10">OCTUBRE</option>
                                       <option value="11">NOVIEMBRE</option>
                                       <option value="12">DICIEMBRE</option>
                                       
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-1">
                                    <div class="form-group">
                                        <label>A&Ntilde;O</label>

                                        <select id="selectAno" name="selectAno" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                               <option value="2016">2016</option>
                                               <option value="2017">2017</option>
                                               <option value="2018">2018</option>
                                               <option value="2019">2019</option>                                       
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>AREA</label>

                                        <select id="selectArea" name="selectArea" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                             <option value="MAT" >MAT</option>
                                             <option value="MO" >MO</option>
                                        </select>
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
		   				                    <p>© Material Admin Responsive. All rights reserved.</p>

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
                            <div class="modal fade" id="modalVR" tabindex="-1">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        
                                        <div class="modal-body">
                                           <div class="form-group">
                                                <label>Ingrese vale de reserva</label>
                                                <input id="inputVR" type="text" class="form-control input-mask" data-mask="0000000" placeholder="ejm: 0000000" autocomplete="off" maxlength="7">
                                                <i class="form-group__bar"></i>
                                             </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="botonContinuar" type="button" onclick="asignarGrafo(this)" class="btn btn-link">Continuar</button>
                                            <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
         <div style="visibility:hidden;">
                <form  method="POST" id="formGenerarExcelMat">
                    <input id="itemplan" type="text" class="form-control form-control-sm form-control--active">
                    <input id="codigopo" type="text" class="form-control form-control-sm form-control--active">
                    <input id="idEECC" type="text" class="form-control form-control-sm form-control--active">   
                </form>
            </div>
        <!-- Older IE warning message -->
            <!--[if IE]>
                <div class="ie-warning">
                    <h1>Warning!!</h1>
                    <p>You are using an outdated version of Internet Explorer, please upgrade to any of the following web browsers to access this website.</p>

                    <div class="ie-warning__downloads">
                        <a href="http://www.google.com/chrome">
                            <img src="img/browsers/chrome.png" alt="">
                        </a>

                        <a href="https://www.mozilla.org/en-US/firefox/new">
                            <img src="img/browsers/firefox.png" alt="">
                        </a>

                        <a href="http://www.opera.com">
                            <img src="img/browsers/opera.png" alt="">
                        </a>

                        <a href="https://support.apple.com/downloads/safari">
                            <img src="img/browsers/safari.png" alt="">
                        </a>

                        <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">
                            <img src="img/browsers/edge.png" alt="">
                        </a>

                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="img/browsers/ie.png" alt="">
                        </a>
                    </div>
                    <p>Sorry for the inconvenience!</p>
                </div>
            <![endif]-->

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
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script type="text/javascript">
        
        function generarExcelMat(component){
            var codigoPO = $(component).data('ptr');
            var itemplan = $(component).data('itemplan');
            var idEmpresaColab = $(component).data('eecc');
            $('#itemplan').val(itemplan);
            $('#codigopo').val(codigoPO);
            $('#idEECC').val(idEmpresaColab);
            
            $('#formGenerarExcelMat').submit();

            // $.ajax({
       	    // 	type	:	'POST',
       	    // 	'url'	:	'getExcelPOMatAprob',
            //        data	:	{ codigoPO : codigoPO,
            //                   itemplan : itemplan,
            //                   idEmpresaColab : idEmpresaColab},
       	    // 	'async'	:	false
       	    // }).done(function(data){
               
       		// });
        }


        $('#formGenerarExcelMat')
        .bootstrapValidator({
            container: '#mensajeForm',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            excluded: ':disabled',
            fields: {
             

            }
        }).on('success.form.bv', function (e) {
        e.preventDefault();


        var $form = $(e.target),
        formData = new FormData(),
        params = $form.serializeArray(),
        bv = $form.data('bootstrapValidator');
		
        $.each(params, function (i, val) {
            formData.append(val.name, val.value);
        });


        var itemplan = $('#itemplan').val();
        var codigoPO = $('#codigopo').val();
        var idEmpresaColab = $('#idEECC').val();
        console.log('llego al metodo');

        formData.append('itemplan', itemplan);
        formData.append('codigoPO', codigoPO);
        formData.append('idEmpresaColab', idEmpresaColab);
        
        $.ajax({
            data: formData,
            url: "getExcelPOMatAprob",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
        })
            .done(function (data) {
                console.log('llego al done');
                var data = JSON.parse(data);
                console.log('paso el parseo');
                if(data.error == 0){
                    location.href = data.rutaExcel;
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {

            })
            .always(function () {

            });


    });
        
        
        function addValeReserva(component){
        	$('#inputVR').val('');
        	
        	var id_ptr = $(component).attr('data-ptr');
         	var grafo = $(component).attr('data-grafo');
         	var from = $(component).attr('data-from');
         	var area = $(component).attr('data-area');
         	var itmp = $(component).attr('data-itmpl')
         	var origen = $(component).attr('data-origen');
         	var tipo_area = $(component).attr('data-tipo_area');
         	
         	$('#botonContinuar').attr('data-ptr',id_ptr);
         	$('#botonContinuar').attr('data-grafo',grafo);
         	$('#botonContinuar').attr('data-from',from); 
         	$('#botonContinuar').attr('data-area',area);       	
         	$('#botonContinuar').attr('data-itmpl',itmp);   
         	$('#botonContinuar').attr('data-origen',origen); 
         	$('#botonContinuar').attr('data-tipo_area',tipo_area);
         	
            $('#modalVR').modal('toggle');
        }
              
          		
         function asignarGrafo(component){
            var vrLeng = $('#inputVR').val().length;
            if(vrLeng!=7){
                alert('El vale de reserva debe tener 7 dígitos.');
                }else if(vrLeng==7){
                	
                	swal({
                        title: 'Está seguro de asociar el grafo?',
                        text: 'Recuerde que luego tendra que asignar el grafo en SAP!',
                        type: 'warning',
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'Si, asignar grafo!',
                        cancelButtonClass: 'btn btn-secondary'
                    }).then(function(){

                    	var vale_reserva = $('#inputVR').val();
                    	var id_ptr = $(component).attr('data-ptr');
                     	var grafo = $(component).attr('data-grafo');
                     	var from = $(component).attr('data-from');
                     	var areaDesc = $(component).attr('data-area');
                     	var itemPl = $(component).attr('data-itmpl');
                     	var origen = $(component).attr('data-origen');
                     	var tipo_area = $(component).attr('data-tipo_area');
                     	
                     	var subProy = $.trim($('#selectSubProy').val()); 
                     	var eecc = $.trim($('#selectEECC').val()); 
                     	var zonal = $.trim($('#selectZonal').val()); 
                     	var item = $.trim($('#selectHasItemPlan').val()); 
                     	var mes = $.trim($('#selectMesEjec').val()); 
                     	var area = $.trim($('#selectArea').val()); 
                     	var estado = $.trim($('#selectEstado').val());
                     	var ano = $.trim($('#selectAno').val());
                     	
                 	    $.ajax({
                 	    	type	:	'POST',
                 	    	'url'	:	'asigGrafo',
                 	    	data	:   {  id_ptr :	id_ptr,
                      	    	           grafo : grafo,
                      	    	           from: from,
                        	    	       areaDesc : areaDesc,
                        	    	       itemPl : itemPl,
                    	    	           subProy : subProy,
                    	    	           eecc : eecc,
                    	    	           zonal : zonal,
                    	    	           item : item,
                    	    	           mes : mes,
                    	    	           area : area,
                    	    	           estado : estado,
                    	    	           vale_reserva : vale_reserva,
                    	    	           ano : ano,
                    	    	           origen : origen,
                    	    	           tipo_area : tipo_area},
                 	    	'async'	:	false
                 	    })
                 	    .done(function(data){
                 	    	var data	=	JSON.parse(data);                 	    	
                 	    	if(data.error == 0){
                 	    		var tabla = data.tablaAsigGrafo;
								$('#contTabla').html(data.tablaAsigGrafo);
                   	    	    initDataTable('#data-table');
                      	    	$('#modalVR').modal('toggle'); 
                       	    	mostrarNotificacion('success','Operación éxitosa.',data.msj); 
                 			}else if(data.error == 1){
                 				
                 				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
                 			}
                 		  })
                 		  .fail(function(jqXHR, textStatus, errorThrown) {
                  			 mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);                  			 
                 		  })
                 		  .always(function() {
                 	  	 
                 		});
                 	   
                    });         
                }            
        }

        function filtrarTabla(){
     	     var subProy = $.trim($('#selectSubProy').val()); 
           	 var eecc = $.trim($('#selectEECC').val()); 
           	 var zonal = $.trim($('#selectZonal').val()); 
            	var item = $.trim($('#selectHasItemPlan').val()); 
             	var mes = $.trim($('#selectMesEjec').val()); 
             	var area = $.trim($('#selectArea').val()); 
             	var estado = $.trim($('#selectEstado').val());
             	var ano = $.trim($('#selectAno').val());
             	var idFase = $.trim($('#selectFase').val());
             	
       	    $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'getDataTable',
       	    	data	:	{subProy  :	subProy,
               	    		eecc      : eecc,
        	    	    	zonal     : zonal,
     	    	           item : item,
    	    	           mes : mes,
    	    	           area : area,
    	    	           estado : estado,
    	    	           ano : ano,
       	    	           idFase : idFase
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
        }

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>