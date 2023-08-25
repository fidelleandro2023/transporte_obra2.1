<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
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
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"/>
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
    <link rel="stylesheet"
          href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>

           <!-- Demo -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/demo/css/demo.css">
        
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css">
    <style type="text/css">

        .select2-dropdown {
            z-index: 100000;
        }

        input[type=checkbox] + label {
          display: block;
          margin: 0.2em;
          cursor: pointer;
          padding: 0.2em;
        }

        input[type=checkbox] {
          display: none;
        }

        input[type=checkbox] + label:before {
          content: "\2714";
          border: 0.1em solid #000;
          border-radius: 0.2em;
          display: inline-block;
          width: 1.4em;
          height: 1.4em;
          padding-left: 0.2em;
          padding-bottom: 0.3em;
          margin-right: 0.2em;
          vertical-align: bottom;
          color: transparent;
          transition: .2s;
        }

        input[type=checkbox] + label:active:before {
          transform: scale(0);
        }

        input[type=checkbox]:checked + label:before {
          background-color: #2eb0ff;
          border-color: #2eb0ff;
          color: #fff;
        }

        input[type=checkbox]:disabled + label:before {
          transform: scale(1);
          border-color: #aaa;
        }

        input[type=checkbox]:checked:disabled + label:before {
          transform: scale(1);
          background-color: #bfb;
          border-color: #bfb;
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
            <a href="https://www.movistar.com.pe/" title="Entel PerÃƒÂ¯Ã‚Â¿Ã‚Â½"><img
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
            <h2>BANDEJA DE EJECUCION</h2>
            <div class="card">

                <div class="card-block">
                    <div class="row">

                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>PROYECTO</label>
                                <?php echo !isset($cmbProyecto) ? null : $cmbProyecto;?>                                                                                      
                            </div>
                        </div>  
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>SUB PROYECTO</label>
                                <select id="cmbSubProy" name="cmbSubProy" class="select2" onchange="filtrarTabla()">
                                    <option value="">Seleccionar</option>
                                </select>                                             
                            </div>
                        </div> 	
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>JEFATURA</label>
                                <?php echo !isset($cmbJefatura) ? null : $cmbJefatura;?>               
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">                               
                            <div class="form-group">
                                <label>ESTACI&Oacute;N</label>
                                <?php echo !isset($cmbEstacion) ? null : $cmbEstacion?>                      
                            </div>
                        </div> 
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label>FECHA</label>
                                <input id="filtrarFecha" type="date" class="form-control" onchange="filtrarTabla()"/>
                            </div>
                        </div>                     
                    </div>
                    <div id="contTabla" style="display:none" class="table-responsive">
                        <?php echo $tablaAsigGrafo ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- -----------------------------------MODAL EDITAR FECHA Y EVIDENCIAS ---------------------- -->
        <div class="modal fade"id="modalEditEjec"  tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                   <div class="modal-body">
                    <form id="formEditarAdju" method="post" class="form-horizontal">                       
                       
                           <div class="row">
                            	<div class="col-sm-12 col-md-12">
                            			<label id="descEsta" style="font-weight: bold;color: black;"></label>
                                        <div class="form-group col-12">
                                          <label>FECHA PREV. DE ATENCION</label>
                                                <input placeholder="::SELECCIONE FECHA::" id="idFechaPreAtencionCoax" name="idFechaPreAtencionCoax" type="text" class="form-control form-control-sm date-picker">
                                               
                                                <i class="form-group__bar"></i>
                                            </div>   
                               
                                		<div class="col-12" id="divFiles">
                                            <div id="dropzone6" class="dropzone" >
                                                    
                                            </div>
                                        	<hr style="border:1;">
                                        </div>
                                </div>
                            <br><br>
                           
                            <div class="col-sm-12 col-md-12" id="mensajeForm"></div>  
                            
                            <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button  type="submit" class="btn btn-primary" id="btnAddEvi">Aceptar</button>
                                </div>
                            </div> 
                            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>	
            <!-- nuevo 15082018-->
            <div class="modal fade" id="modalEditEntidadesEjec"  tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title">APROBAR DISE&Ntilde;O</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="font-weight: bold;color: black;">Asignar Entidades</div>
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12 form-inline" id="formEntidades">
                                        </div>
                                    </div>
                                </div><br><!-- 
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="font-weight: bold;color: black;">Declaro expediente puesto en servidor/entregado en zonal</div>
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12 form-inline" id="formExpediente">
                                            <div class="col-12">
                                                <input type="checkbox" id="chbxExpediente" class="custom-control-input" onchange="habilitarAceptar()">
                                                <label for="chbxExpediente" >Aceptar expediente</label>
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="panel panel-default" id="idPanelPlanoDiseno">
                                    <div class="panel-heading" style="font-weight: bold;color: black;">Declaro que el plano de dise&ntilde;o fue cargado en SIROPE</div>
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12 form-inline" id="formPlanoDiseno">
                                            <div class="col-12">
                                                <input type="checkbox" id="chbxPlanoDiseno" class="custom-control-input" onchange="habilitarAceptar()">
                                                <label for="chbxPlanoDiseno" >Aceptar plano de dise&ntilde;o</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->
                                <div class="panel panel-default" id="panelExpedienteDiseno">
                                    <div class="panel-heading" style="font-weight: bold;color: black;">Expediente diseño: Archivo .rar (Archivo de metrados, planos, fotos y documentos)</div>
                                    <br>
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12 form-inline">
                                            <div class="col-12">
                                                <input id="fileExpedienteDiseno" name="fileExpedienteDiseno" type="file" accept=".zip,.rar" onchange="habilitarAceptar2()">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                 <div class="panel panel-default">
                                    <div class="panel-heading" style="font-weight: bold;color: black;">Datos para PO Autom&aacute;tico</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-2" id="contAmplificadores">
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                            </div>
                            </div>


                            <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button  type="submit" id="btnAceptarEnt"  class="btn btn-primary" onclick="saveEntidades()" disabled>Aceptar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            
            <div class="modal fade bd-example-modal-sm" id="modalProgreso" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
              <div class="modal-dialog modal-sm">
                <div class="modal-content" style="text-align: center;">
                <div class="modal-header">
                        <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title"></h4>                       
                    </div>
                    <div class="modal-body">
                        <div id="contProgres">
                               <div class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                    <span id="valuePie" class="easy-pie-chart__value">0</span>
                                </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            
           
    </section>
    <?php
    //MODAL SISEGO
        echo include('application/views/vf_formulario/v_sisego.php');
    ?>
</main>


<!-- Javascript -->
<!-- ..vendors -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
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
<script src="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>

<!--  -->
<script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url();?>public/js/sinfix.js?v=<?php echo time();?>"></script>
<script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time() ?>"></script>
<script src="<?php echo base_url(); ?>public/js/jsBandejaEjecucion.js?v=<?php echo time();?>"></script>  
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&callback=init" async defer></script>

<!-- App functions and actions -->
<script src="<?php echo base_url(); ?>public/js/app.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>public/fancy/source/jquery.fancybox.js"></script>

<script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>

 <!-- Demo -->
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time();?>"></script>  

<script type="text/javascript">

//$('.easy-pie-chart').data('easyPieChart').update('5');
//$('#valuePie').html(5);
function editarAdjudicacion(component){
	var itemplan = $(component).attr('data-itemplan');
	var idEstacion = $(component).attr('data-idEstacion');
	var estaDesc = $(component).attr('data-esta');
	var has_file = $(component).attr('data-has_file');
	
	 $.ajax({
         type : 'POST',
         url  : 'getInEjec',
         data : { itemplan   : itemplan,
       	          idEstacion : idEstacion }
     }).done(function(data){
         data = JSON.parse(data);
         if (data.error == 0) {
        	if(has_file==0){
        	    $('#divFiles').show();
        	}else if(has_file==1){
        		$('#divFiles').hide();
        	}
        	$('#idFechaPreAtencionCoax').val('');
        	$('#formEditarAdju').bootstrapValidator('resetForm', true);  
        	$('#tituloModal').html('ITEMPLAN '+itemplan);
        	$('#descEsta').html(estaDesc);
        	$('#btnAddEvi').attr('data-idEsta', idEstacion);
        	$('#btnAddEvi').attr('data-item', itemplan);
        	$('#modalEditEjec').modal('toggle');
         }else{
             alert('error Interno intentelo de nuevo.');
         }
     });
}
                                        
var itemPlanAnterior = null;
$(document).ready(function(){
    $('#contTabla').css('display', 'block');
    $("body").on("click", ".ver_ptr", function () {
        $("body").on("click",".ver_ptr",function(){
            $this=$(this);
            var id = $(this).attr('data-idrow');
            var idEstacion = $(this).attr('data-estacion');
            $('#'+id).css('background-color', 'yellow');

            if(itemPlanAnterior!=null && itemPlanAnterior!=id) {
                $('#'+itemPlanAnterior).css('background-color', 'white');  
            }     
            itemPlanAnterior = id;
            $.fancybox({
                height:"100%",href:"detalleObra?item="+$(this).text()+"&from=2&estacion="+idEstacion,type:"iframe",width:"100%"
            });
            return!1
        });
    });
})

    $('#formEditarAdju')
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

        var itemplan = $('#btnAddEvi').attr('data-item');
        formData.append('itemplan', itemplan);
        var idEsta   = $('#btnAddEvi').attr('data-idEsta');
        formData.append('idEstacion', idEsta);

        var idEstacion = $.trim($('#idEstacion').val());
        var idTipoPlan = $.trim($('#idTipoPlanta').val());
        var jefatura   = $.trim($('#cmbJefatura').val());
        var idProyecto = $.trim($('#cmbProyecto').val()); 
        var subProy    = $.trim($('#cmbSubProy').val());
        var fecha      = $.trim($('#filtrarFecha').val());

        formData.append('idEstacionFil', idEstacion);
        formData.append('idTipoPlan', idTipoPlan);
        formData.append('jefatura', jefatura);
        formData.append('idProyecto', idProyecto);
        formData.append('subProy', subProy);
        formData.append('fecha', fecha);
        
        $.ajax({
            data: formData,
            url: "editEjecuDi",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
        })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contTabla').html(data.tablaAsigGrafo);			    					
                    initDataTable('#data-table');
                   
                	$('#modalEditEjec').modal('toggle');
                    mostrarNotificacion('success', 'Operacion exitosa.', 'Se registro correcamente!');
                } else if (data.error == 1) {
                    console.log(data.error);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'ComunÃƒÆ’Ã‚Â­quese con alguna persona a cargo :(');
            })
            .always(function () {

            });


    });

    function revalidate() {
        var zoni = $('#selectZonificacion').val();
        if (zoni == '1') {
            $('#divEECC').hide();
            var validator = $('#formAdjudicaItem').data('bootstrapValidator');
            validator.enableFieldValidators('selectEECCDiseno', false);
        } else if (zoni == '2') {
            $('#divEECC').show();
            var validator = $('#formAdjudicaItem').data('bootstrapValidator');
            validator.enableFieldValidators('selectEECCDiseno', true);
        }

    }

    function ejecutarDiseno(component) {    	
        var itemplan = $(component).attr('data-item');
        $.ajax({
            type: 'POST',
            url: "getInfItem",
            data: {item: itemplan},
            'async': false
        })
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {


                    $('#inputFecPrevEjec').val(data.fec_prev_eje);
                    $('#inputFecInicio').val(data.fec_inicio);

                    //$('#selectSubAdju').val(data.idSubProyecto).trigger('chosen:updated');
                    $('#selectSubAdju').val(data.subpro).trigger('change');

                    $('#tituloModal').html('ITEMPLAN: ' + itemplan);
                    $('#btnAdjudica').attr('data-item', itemplan);

                    $('#divEECC').hide();
                    var validator = $('#formAdjudicaItem').data('bootstrapValidator');
                    validator.enableFieldValidators('selectEECCDiseno', false);

                    $('#modalEjec').modal('toggle');
                } else if (data.error == 1) {
                    console.log(data.error);
                }
            })


    }


    
    
    /********************nuevo 15082018*********************/

    function aprobarDiseno(component) {
        var itemplan   = $(component).attr('data-item');
        var idEstacion = $(component).attr('data-id_estacion');
        /* comentado pero harcodeado en la parte inferior..30.06.2019 czavalacas
        var flgExpediente = document.getElementById("chbxExpediente").checked;
        var flgDisenoSirope = document.getElementById("chbxPlanoDiseno").checked;
        */
        var id = itemplan+""+idEstacion;

        $('#'+id).css('background-color', 'yellow');

        if(itemPlanAnterior!=null && itemPlanAnterior!=id) {
            $('#'+itemPlanAnterior).css('background-color', 'white');
        }
        itemPlanAnterior = id;

        if(itemplan == null || itemplan == '') {
            return;
        }
        /**archivo expediente disneo 30.06.2019 czavala**/
        var flgExpediente = true;//al quitar los check hardcodeamos true para no afectar el flujo
    	var flgDisenoSirope = true;//al quitar los check hardcodeamos true para no afectar el flujo  
        var input2File = document.getElementById('fileExpedienteDiseno');
        var file2 = input2File.files[0];
        //console.log(file2);
        /************************************************/
        $.ajax({
            type : 'POST',
            url  : 'validarAprobarDiseno',
            data : { itemplan   : itemplan,
                     idEstacion : idEstacion }
        }).done(function(data){
            data = JSON.parse(data);
            if (data.error == 0) {
                console.log('entro');

                swal({
                    title: 'Esta seguro de Ejecutar el Dise&#241o?',
                    text: 'Asegurese de validar la informacion seleccionada!',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, Ejecutar el Dise&#241o!',
                    cancelButtonClass: 'btn btn-secondary'
                }).then(function () {
             	   //swal.disableConfirmButton();
                	var idEstacionFil = $.trim($('#idEstacion').val());
                    var idTipoPlan = $.trim($('#idTipoPlanta').val());
                    var jefatura   = $.trim($('#cmbJefatura').val());
                    var idProyecto = $.trim($('#cmbProyecto').val());
                    var subProy    = $.trim($('#cmbSubProy').val());
                    var fecha      = $.trim($('#filtrarFecha').val());
                    
                    if(inputGlobal != null) {
                        if(inputGlobal == 1) {
                            cant_amplificadorGobal = $.trim($('#cant_amplificador').val());
                        	//cant_amplificadorGobal = 1;//hahrcodeamos 1 para que no afecte el flujo cambio de reemplazo por archivo czavalacas 30.06.2019
                            if(cant_amplificadorGobal == null || cant_amplificadorGobal == '') {
                                mostrarNotificacion('error', 'Debe ingresar el nro. de amplificador', 'Datos para PO Autom&aacute;tico');
                                return;
                            }
                        } else {
                            cant_trobaGobal = $.trim($('#cant_troba').val());
                        	//cant_trobaGobal = 1;//hahrcodeamos 1 para que no afecte el flujo cambio de reemplazo por archivo czavalacas 30.06.2019
                            if(cant_trobaGobal == null || cant_trobaGobal == '') {
                                mostrarNotificacion('error', 'Debe ingresar el nro. de troba', 'Datos para PO Autom&aacute;tico');
                                return;
                            }
                        }
                    }
                    console.log(file2);
                    //agregamos el archivo al envio
                    /*modificamos el ajax para que reciba files
                    **/
                    /******/
                    var form_data = new FormData();  
                    form_data.append('archivoExpediente', file2);
                    form_data.append('idEstacionFil', idEstacionFil);
                    form_data.append('idTipoPlan', idTipoPlan);
                    form_data.append('jefatura', jefatura);
                    form_data.append('idProyecto', idProyecto);
                    form_data.append('fecha', fecha);
                    form_data.append('subProy', subProy);
                    form_data.append('item', itemplan);
                    form_data.append('idEstacion', idEstacion);
                    form_data.append('cantTroba', cant_trobaGobal);
                    form_data.append('cantAmplificador', cant_amplificadorGobal);
                    form_data.append('flgExpediente', (flgExpediente == true ? 1 : 0));
                    form_data.append('arrayIdEntidades', arrayEntidades);
                    form_data.append('flgDisenoSirope', (flgDisenoSirope == true ? 1 : 0));
                           
                    /*
                    idEstacionFil: idEstacionFil,
                	idTipoPlan	: idTipoPlan,
                	jefatura	: jefatura,
                	idProyecto	: idProyecto,
                	fecha		: fecha,
                	subProy		: subProy,
                    item		: itemplan,
                    idEstacion 	: idEstacion,
                    cantTroba   : cant_trobaGobal,
                    cantAmplificador : cant_amplificadorGobal,
                    flgExpediente : (flgExpediente == true ? 1 : 0),
                    arrayIdEntidades : arrayEntidades,
                    flgDisenoSirope: (flgDisenoSirope == true ? 1 : 0)
                    */
                    /******/
                    $.ajax({
                        type: 'POST',
                        'url': 'ejecDiseno',
                        data: form_data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        xhr: function() {
                            $('.easy-pie-chart').data('easyPieChart').update('0');
                        	$('#valuePie').html('0');
                        	$('#modalProgreso').modal('toggle');
                            var xhr = $.ajaxSettings.xhr();
                            xhr.upload.onprogress = function(e) {
                                var progreso = Math.floor(e.loaded / e.total *100) + '%';                                
                                console.log(progreso);
                                $('.easy-pie-chart').data('easyPieChart').update(progreso);
                                $('#valuePie').html(progreso);
                            };
                            return xhr;
                        }
                    }).done(function (data) {
                        //console.log(data);
                        var data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTabla').html(data.tablaAsigGrafo);
                            initDataTable('#data-table');
                            mostrarNotificacion('success', 'Operaci&#243n exitosa.', data.msj);
                            /**/
                            arrayEntidades = [];
                            $('#modalProgreso').modal('toggle');
                        } else if (data.error == 1) {
                        	$('#modalProgreso').modal('toggle');
                            mostrarNotificacion('error', 'Error al liquidar el diseÃƒÂ±o!', data.msj);
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                    	$('#modalProgreso').modal('toggle');
                        mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                    })
                    .always(function () {
                    });
                });




            } else {
                mostrarNotificacion('error', 'NO SE PUEDE APROBAR', data.msj);
            }
        });
    }
    /******************************************/

    function progress(e){
    	console.log('progress');
        if(e.lengthComputable){
            var max = e.total;
            var current = e.loaded;

            var Percentage = (current * 100)/max;
            console.log(Percentage);


            if(Percentage >= 100)
            {
               // process completed  
            }
        }  
     }
</script>
</body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>