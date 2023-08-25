<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
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
                           <h2 >EDITAR PLAN OBRA FASE</h2>
                           <hr>
                            <div class="card">                                          
                            
                                <div class="card-block"> 
                                <div class="row">
                                <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>TIPO PLANTA</label>
                                                    <select id="selectTipoPlanta" name="selectTipoPlanta" class="select2" onchange="changueProyecto();">
                                                         <option>&nbsp;</option>
                                                      <?php                                                    
                                                                    foreach($listaTipoPlanta->result() as $row){                      
                                                                ?> 
                                                                 <option value="<?php echo $row->idTipoPlanta ?>"><?php echo $row->tipoPlantaDesc ?></option>
                                                                 <?php }?>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>ITEMPLAN</label>
                                                    <input id="txtItemPlan" type="text" class="form-control input-mask" placeholder="ItemPlan" autocomplete="off" maxlength="13" style="border-bottom: 1px solid lightgrey">
                                                   
                                                   
                                                </div>
                                                <div class="form-group">
                                                    <label>MDF/NODO</label>

                                                    <select id="nodo" name="nodo" class="select2" >
                                                    <option>&nbsp;</option>
                                                    <?php                                                    
                                                                foreach($listaNodos->result() as $row){                      
                                                            ?> 
                                                             <option value="<?php echo $row->idCentral ?>"><?php echo $row->codigo ?>-<?php echo $row->tipoCentralDesc ?></option>
                                                             <?php }?>
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>PROYECTO</label>

                                                    <select id="selectProy" name="selectProy" class="select2" onchange="changueSubProyecto();">
                                                        <option>&nbsp;</option>
                                                       
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>NOMBRE</label>
                                                        <input id="nombreproyecto" type="text" class="form-control input-mask" placeholder="nombre del proyecto" autocomplete="off" maxlength="200" style="border-bottom: 1px solid lightgrey">
                                                    
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>ZONAL</label>

                                                    <select id="selectZonal" name="selectZonal" class="select2"  multiple >
                                                         <option>&nbsp;</option>
                                                    <?php                                                    
                                                                foreach($listaZonal->result() as $row){                      
                                                            ?> 
                                                             <option value="<?php echo $row->idZonal ?>"><?php echo $row->zona ?></option>
                                                             <?php }?>
                                                    </select>
                                                </div>
                                                <button class="btn btn-success waves-effect" type="button" onclick="filtrarTabla()">CONSULTAR</button>
                                            </div>
                                             <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label>SUB PROYECTO</label>

                                                    <select id="selectSubProy" name="selectSubProy" class="select2" >
                                                        <option>&nbsp;</option>
                                                       
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>ESTADO</label>

                                                    <select id="estado" name="estado" class="select2" >
                                                         <option>&nbsp;</option>
                                                    <?php                                                    
                                                                foreach($listaEstados->result() as $row){                      
                                                            ?> 
                                                             <option value="<?php echo $row->idEstadoPlan ?>"><?php echo $row->estadoPlanDesc ?></option>
                                                             <?php }?>
                                                    </select>
                                                </div>
                                                                               
                                                <div class="form-group">
                                                   
                                                      <table>
                                                        <tr><td colspan=2>FECHA PREVISTA DE EJECUCION<tr>
                                                        <tr><td> <input name="fechaInicio" id="fechaInicio" type="text" class="form-control date-picker" placeholder="Desde"></td><td><input name="fechaFin" id="fechaFin" type="text" class="form-control date-picker" placeholder="Hasta"></td></tr>
                                                      </table>
                                                   
                                                    
                                                    
                                                </div>


                                                
                                            </div>
                                                    <div id="contTabla" class="table-responsive">
                                                            <?php echo $tablaEditItemplan?>
                                   </div>
                                                </div>
                                            </div>
                                        </div>

                                         <footer class="footer hidden-xs-down">
			                    <p>Telefónica Del Perú</p>   				                  
		                   </footer>
            </section>
            
        </main>
<!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>
            <!---->
<div class="modal fade" id="modalUpdatePlanobra">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">MODIFICAR PLAN OBRA</h5>
                        <input id="inputSubpro" name="inputSubpro" type="hidden" class="form-control">
                    </div>
                    <div class="modal-body">
                    <form id="formUpdatePlanobra" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    
                                     <div class="form-group">
                                         <label>MDF/NODO</label>
                                                <select id="selectCentral" name="selectCentral" class="select2 form-control" onchange="changueCentral();changueEECC();">
                                                       <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>


                                    <div class="form-group">
                                         <label>EMPRESA COLABORADORA</label>
                                                <input id="inputEECCEdit" name="inputEECCEdit" type="text" class="form-control" readonly="true">
                                               
                                    </div>

                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>INDICADOR</label>
                                        <input id="inputIndicador" name="inputIndicador" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                        <i class="form-group__bar"></i>
                                    </div>

                                    

                                    <div class="form-group">
                                         <label>FASE</label>
                                                <select id="selectFase" name="selectFase" class="select2 form-control">
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                         foreach($listafase->result() as $row){     
																if($row->idFase == ID_FASE_2020){
                                                        ?> 
                                                         <option value="<?php echo $row->idFase ?>"><?php echo $row->faseDesc ?></option>
														<?php	} 
														 }?>
                                                     
                                                </select>
                                    </div>

                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>COORDENADAS X</label>
                                        <input id="inputCoordX" name="inputCoordX" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                        <i class="form-group__bar"></i>
                                    </div>

                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>UIP</label>
                                        <input id="inputUIP" name="inputUIP" type="number" min="0" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                        <i class="form-group__bar"></i>
                                    </div>

                                 
                                  </div>
                                <div class="col-sm-6 col-md-6">
                                   
                                    <div class="form-group">
                                         <label>ZONAL</label>
                                          <select id="selectZonalEdit" name="selectZonalEdit" class="select2 form-control" >
                                                                                                        
                                                </select>
                                         
                                            
                                    </div>

                                     <div class="form-group">
                                        <label>EMPRESA COLABORADORA DISEÑO</label>
                                     
                                         <select id="selectEECCDISEdit" name="selectEECCDISEdit" class="select2 form-control" >
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaEECC->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>



                                               
                                    </div>

                                     <input id="inputItemPlanEdit" name="inputItemPlanEdit" type="hidden" class="form-control">
                                    <input id="inputNombrePlanAux" name="inputNombrePlanAux" type="hidden" class="form-control">
                                    <input id="inputEmpresaColabN" name="inputEmpresaColabN" type="hidden" class="form-control">

                                    
                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>NOMBRE DEL PLAN</label>
                                        <input id="inputNombrePlan" name="inputNombrePlan" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                        <i class="form-group__bar"></i>
                                    </div>


                                    <div class="form-group">
                                         <label>EMPRESA ELECTRICA</label>
                                                <select id="selectEmpresaEle" name="selectEmpresaEle" class="select2 form-control" >
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaeelec->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idEmpresaElec ?>"><?php echo $row->empresaElecDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                    </div>
                                    
                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>COORDENADAS Y</label>
                                        <input id="inputCoordY" name="inputCoordY" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                        <i class="form-group__bar"></i>
                                    </div>
                                    

                                    <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>CANTIDAD OBRA</label>
                                        <input id="inputCantObra" name="inputCantObra" type="text" class="form-control"><i 
                                        class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                        <i class="form-group__bar"></i>
                                    </div> 
                               
                                </div>
                            </div>
                        <div id="mensajeForm"></div>  
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                                <button id="btnSave" type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                    </div>                    
                </div>
            </div>
        </div>


        
<!---->
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
        
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>

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
         <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script type="text/javascript">
                    
            var indicadorant=null;
            var zonalnuevo=null;
            var itemplanGlob = null;
            var idSubProyectoGlob = null;

     
         $('#inputIndicador').bind('keypress blur', function() {
        
    $('#inputNombrePlan').val($('#inputIndicador').val() + ' - ' +
                    $('#inputNombrePlanAux').val() );
});
          		
         		
         
         function editItemPlan(component){
                
                console.log('entro al metodo que habre el modal');
                  var id = $(component).attr('data-id');
                  idSubProyectoGlob = $(component).attr('data-idsubpro');
                  console.log('idSubProyectoGlob: ',idSubProyectoGlob);
                  itemplanGlob = $(component).data('id');
                  $('#inputSubpro').val(idSubProyectoGlob);
       
              $.ajax({
                type    :   'POST',
                'url'   :   'getInfoItemPlanEditlite',
                data    :   { id : id },
                'async' :   false
            }).done(function(data){
                var data = JSON.parse(data);                    
                               
                $('#formUpdatePlanobra').bootstrapValidator('resetForm', true); 

                $('#inputIndicador').val(data.indicador);
                $('#selectFase').val(data.idfase).trigger('change');
                $('#inputCoordX').val(data.coordX);
                $('#inputCoordY').val(data.coordY);
                $('#inputNombrePlanAux').val(data.nombreRealProyecto);
                $('#inputNombrePlan').val(data.nombreproyecto);
                $('#selectEmpresaEle').val(data.idEmpresaElec).trigger('change');
                $('#inputCantObra').val(data.cantidadTroba);
                $('#inputUIP').val(data.uip);
                $('#inputItemPlanEdit').val(id);

                $('#selectCentral').val(data.idCentral).trigger('change');
                $('#selectZonalEdit').val(data.idZonal).trigger('change');

                $('#inputEECCEdit').val(data.empresaColabDesc);
                $('#selectEECCDISEdit').val(data.idEmpresaColabDiseno).trigger('change');

                indicadorant=data.indicador;

                $('#btnEdit').attr('data-id',id);               
                $('#modalUpdatePlanobra').modal('toggle');          
            })

              

       
            };

            
$('#formUpdatePlanobra').bootstrapValidator({
                container: '#mensajeForm',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                   
                    selectEmpresaEle: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una empresa electrica.</p>'
                            }
                        }
                    },
                    selectFase: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar la fase.</p>'
                            }
                        }
                    },
                    inputNombrePlan: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el titulo del plan.</p>'
                            }
                        }
                    },
                    inputIndicador: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el indicador.</p>'
                            }
                        }
                    },
                    selectCentral: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una central .</p>'
                            }
                        }
                    },

                selectEECCDISEdit: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una empresa colaboradora diseño .</p>'
                            }
                        }
                    }
                    
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();             
                
                var $form    = $(e.target),
                    formData = new FormData(),
                    params   = $form.serializeArray(),
                    bv       = $form.data('bootstrapValidator');  

                    $('#mensajeForm').html('');
                    $zonalnuevo=$('#selectZonalEdit').val(); 

                    

                    if($zonalnuevo=='' || $zonalnuevo==0 || $zonalnuevo==null){
                        $('#mensajeForm').html('<p style="color:red">(*) Debe seleccionar una zonal .</p>');
                        return false;
                    }

                    var flagIndica=null;
                   
                    var itemplan =   $('#inputItemPlanEdit').val();
                    formData.append('id', itemplan);

                    var idEmpresaColab = $('#inputEmpresaColabN').val();
                    formData.append('idEmpresaColab', idEmpresaColab);

                    var nuevoindica=$('#inputIndicador').val();

                    if (nuevoindica!=indicadorant){
                        flagIndica=1;
                    }else{
                        flagIndica=0;
                    }
                    formData.append('flagCIndica', flagIndica);

                    $.each(params, function(i, val) {
                        formData.append(val.name, val.value);
                    });
                    
                    $.ajax({
                        data: formData,
                        url: "editPlanobralite",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                      .done(function(data) {  
                                data = JSON.parse(data);
                            if(data.error == 0){                                                            
                                //$('#contTabla').html(data.listartabla);                                           
                                //initDataTable('#data-table');
                                $('#modalUpdatePlanobra').modal('toggle');
                                var itemplan=data.itemplanmodificado;
                                
                                mostrarNotificacion('success','Operaci&oacute;n exitosa.', 'Se ha  editado el plan obra con el n&uacute;mero '+itemplan+'.');
                                filtrarTabla();
                            }else if(data.error == 1){
                                mostrarNotificacion('error','Error','No se modifico el Plan de obra');
                            }
                      })
                      .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
                      })
                      .always(function() {
                         
                    });
                   
                
            });



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
            
            var anio = (new Date).getFullYear();

            var fechaDestinoDefault = anio+'/12/31';
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
          
       	    $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'getConsultaEditItemPlan',
       	    	data	:	{itemplan : itemplan,
                            nombreproyecto : nombreproyecto,
                            nodo : nodo,
                            zonal : zonal,
                            proy  :	proy,
            	    	    subProy  :    subProy,
         	    	        estado : estado,
                            filtroPrevEjec : filtroPrevEjec,
                            tipoPlanta : tipoPlanta
                           },
       	    	'async'	:	false
       	    })
       	    .done(function(data){
       	    	var data	=	JSON.parse(data);
       	    	if(data.error == 0){           	    	          	    	   
       	    		$('#contTabla').html(data.tablaEditItemplan)
       	    	    initDataTable('#data-table');
       	    		
       			}else if(data.error == 1){
       				
       				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
       			}
       		  });
            
            
        }


           function changueCentral(){
            var central = $.trim($('#selectCentral').val()); 

             $.ajax({
                type    :   'POST',
                'url'   :   'getHTMLZonalEditlite',
                data    :   {central  : central
                },
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){ 
                 
                    $('#selectZonalEdit').html(data.listaZonal);
                    $('#selectZonalEdit').val(data.idZonalSelec).trigger('chosen:updated');

                    $zonalnuevo=data.idZonalSelec;

                   
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }  

        function changueEECC(){
            var central = $.trim($('#selectCentral').val());
            var subpro =  $('#inputSubpro').val();
            console.log('subproyecto: ', subpro);

             $.ajax({
                type    :   'POST',
                'url'   :   'getHTMLEECCEditlite',
                data    :   {central  : central,
                             idsuproc: subpro},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){      

                    $('#inputEECCEdit').val(data.EECCselect);
                    $('#selectEECCDISEdit').val(data.EECCidselect).trigger('change');
                    $('#inputEmpresaColabN').val(data.EECCidselect);


                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }
        
        function changueProyecto(){
            var tipoplanta = $.trim($('#selectTipoPlanta').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'getProyEditIp',
                data    :   {tipoplanta  : tipoplanta},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){ 
                                           
                    $('#selectProy').html(data.listaProyectos);
                     $('#selectSubProy').html('');
                   
                   
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }

        function changueSubProyecto(){
            var proyecto = $.trim($('#selectProy').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'getSubProyEditIp',
                data    :   {proyecto  : proyecto},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){ 
                                           
                    $('#selectSubProy').html(data.listaSubProy);
                   
                   
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }
        
        
        

        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>