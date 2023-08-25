<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/galeria_fotos.css?v=<?php echo time(); ?>">
<style>.fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}</style>
<style>
.modal-dialog {
  position: relative;
  width: auto;
  max-width: 600px;
  margin: 10px;
}
.modal-sm {
  max-width: 300px;
}
.modal-lg {
  max-width: 90%;
}
@media (min-width: 768px) {
  .modal-dialog {
    margin: 30px auto;
  }
}
@media (min-width: 320px) {
  .modal-sm {
    margin-right: auto;
    margin-left: auto;
  }
}
@media (min-width: 620px) {
  .modal-dialog {
    margin-right: auto;
    margin-left: auto;
  }
  .modal-lg {
    margin-right: 10px;
    margin-left: 10px;
  }
}
@media (min-width: 920px) {
  .modal-lg {
    margin-right: auto;
    margin-left: auto;
  }
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] { -moz-appearance:textfield; }

</style>

<div id="appVue">

    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default card-view">

                        <div class="row heading-bg">
                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                <h5 class="txt-dark">Cierre Licencias<span class="txt-info"></span></h5>
                            </div>
                            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                                <ol class="breadcrumb">
                                    <li><a href="index.php">Inicio</a></li>
                                    <li><a href="#" class=""><span>Licencias Preliquidadas</span></a></li>
                                </ol>
                            </div>
                        </div>

                        <div class="panel-wrapper collapse in">
                            <div class="panel-body">
                                <div class="table-wrap">
                                    <div id="contTabla" class="table-responsive">
                                        <table id="tablaItemPlan" class="table table-bordered" >
                                            <thead class="thead-default">
                                                <tr>
                                                    <th>Acci&oacute;n</th>
                                                    <th>Item Plan</th>
                                                    <th>Proyecto</th>
                                                    <th>Sub Proy</th>
                                                    <th>Zonal</th>
                                                    <th>EECC</th>
                                                    <th>Estaci&oacute;n</th>
                                                    <th># Ent Liqui / # Ent Preliqui</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="background: #e6faff; align : center" v-for="(item,index) in tablaLicPreliqui">
                                                    <th>
                                                        <button type="button" class="btn btn-success" @click="getItemPlanEstaDet(item.itemPlan,item.idEstacion)"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                    </th>
                                                    <th>{{item.itemPlan}}</th>
                                                    <th>{{item.proyectoDesc}}</th>
                                                    <th>{{item.subProyectoDesc}}</th>
                                                    <th>{{item.zonalDesc}}</th>
                                                    <th>{{item.empresaColabDesc}}</th>
                                                    <th>{{item.estacionDesc}}</th>
                                                    <th>{{item.cant_liqui+' / '+item.cant_preliqui}}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalPreliquiEntidades" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="tituloModalEntPreliqui" style="margin: auto;font-weight: bold;" class="modal-title">LISTA DE ENTIDADES </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="content" class="modal-body">
                    <form id="formRegistrarEntidad" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                        <div class="row">
                            <div class="form-group form-group--float col-sm-12 table-responsive">
                                <table style="font-size: 10px" id="tablaEntPrliqui" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">ENTIDAD</th>
                                            <th style="width: 10%">DISTRITO</th>
                                            <th style="width: 10%">ESTADO</th>
                                            <th style="width: 10%">FECHA PRELIQUIDACI&Oacute;N</th>
                                            <th style="width: 10%">PDF FINALIZACI&Oacute;N</th>
                                            <th style="width: 10%">VER</th>
                                            <th style="width: 15%">ACCI&Oacute;N</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in tablaItemPlanLicDet" v-bind:id="'trEntidad'+item.iditemplan_estacion_licencia_det"  v-bind:style="{ background: [ item.flg_validado == 2 ? '#1affff' : '#e6e600' ] }">
                                            <td v-bind:id="'txtDescEnt'+item.iditemplan_estacion_licencia_det" v-bind:data-index="index" style="font-weight: bold;">{{item.desc_entidad}}</td>
                                            <td v-bind:id="'txtDistrito'+item.iditemplan_estacion_licencia_det" v-bind:data-index="index" style="font-weight: bold;">{{item.distritoDesc}}</td>
                                            <td>
                                                {{item.flg_validado == 2 ? 'PRELIQUIDADO' : (item.flg_validado == 3 ? 'LIQUIDADO' : 'PENDIENTE') }}
                                            </td>
                                            <td>
                                                <input type="date" v-bind:id="'txtFechaPreliqui'+index" class="custom-control-input"  v-model="item.fecha_preliqui" disabled>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success"  @click="abrirModalEvidencia(item.iditemplan_estacion_licencia_det)" v-bind:disabled="item.flg_validado == 3 ? true : false">Subir</button>
                                            </td>
                                            <td>
                                                <button type="button" v-bind:id="'btnVerEviEnt'+item.iditemplan_estacion_licencia_det" class="btn btn-success" @click="descargarPDFEviPreliqui(item.iditemplan_estacion_licencia_det,index)"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success mr-10"  @click="openModalLiqui(item.iditemplan_estacion_licencia_det,index)"
                                                v-bind:disabled="item.flg_validado == 3 ? true : false">LIQUIDAR</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSubirEviLicPreliqui" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titModalEvi" style="font-weight: bold;">EL NOMBRE DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
                </div>
                <div class="modal-body">
                    <div class="col-6">
                        <div id="dzEviLicPreliqui" class="dropzone" >
                        </div>
                        <hr style="border:1;">
                    </div>
                 </div>
                <div class="modal-footer">
                    <button type="button" id="btnAceptarSubirEvi" class="btn btn-success">Aceptar</button>
                    <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAlertaValidacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background:red">
                <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a>Al aceptar, se validar&aacute; y se dar&aacute; por liquidada la licencia.</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" @click="liquidarLicencia()">Aceptar</button>
            </div>
            </div>
        </div>
    </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<script src="<?php echo base_url(); ?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url(); ?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url(); ?>public/dist/js/init.js"></script>
<script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time(); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url(); ?>public/demo/js/flot-charts/chart-tooltips.js?v=<?php echo time(); ?>"></script>



<script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendors/bower_components/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

<script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time(); ?>"></script>


<script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables.net-buttons/js/buttons.flash.min.js"></script>


<script src="<?php echo base_url(); ?>public/vendors/bower_components/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendors/bower_components/pdfmake/build/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>public/dist/js/export-table-data.js"></script>

<script src="<?php echo base_url(); ?>public/js/js_licencias/js_licencias_preliquidadas.js"></script>