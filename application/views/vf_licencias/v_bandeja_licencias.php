<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/galeria_fotos.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
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

<div id="licenciaVue">

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row heading-bg">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Gesti&oacute;n de licencias<span class="txt-info"></span></h5>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="#" class=""><span>Registro licencias</span></a></li>
                <!-- <li><a href="#" class="active"><span></span></a></li> -->
                </ol>
          </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default card-view">
                <div class="panel-heading" style="margin-bottom:20px">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Filtros</h6>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- <form method="post"> -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label mb-10">Proyecto</label>
                            <select class="form-control select2" name="proyecto" id="proyecto" v-model="jsonBusqueda.idProyecto" @change="getSubProyecto()"> 
                                <option value="0" selected>Seleccionar Proyecto</option>
                                <option v-for="item in arrayProyectos" v-bind:value="item.idProyecto">{{item.proyectoDesc}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label mb-10">SubProyecto</label>
                                <select class="form-control select2" name="SubProyecto" id="subProyecto" v-model="jsonBusqueda.idSubProyecto"> 
                                    <option value="0">Seleccionar SubProyecto</option>
                                    <option v-for="item in arraySubProyectos" v-bind:value="item.idSubProyecto">{{item.subProyectoDesc}}</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label mb-10">Jefatura</label>
                                <select class="form-control select2" name="jefatura" id="jefatura" v-model="jsonBusqueda.jefatura"> 
                                    <option value="">Seleccionar Jefatura</option>
                                    <option v-for="item in arrayJefaturas" v-bind:value="item.jefatura">{{item.jefatura}}</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label mb-10">Empresa Colaboradora</label>
                                <select class="form-control select2" name="empresaColab" id="empresaColab" v-model="jsonBusqueda.idEmpresaColab"> 
                                    <option value="0">Seleccionar Empresa</option>
                                    <option v-for="item in arrayEmpresasColab" v-bind:value="item.idEmpresaColab">{{item.empresaColabDesc}}</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label mb-10">Fase</label>
                                <select class="form-control select2" name="fase" id="fase" v-model="jsonBusqueda.idFase"> 
                                    <option value="0">Seleccionar Fase</option>
                                    <option v-for="item in arrayFase" v-bind:value="item.idFase">{{item.faseDesc}}</option>
                                </select>
                        </div>
                    </div>

                    <div class="col-md-offset-9 col-md-3">
                        <button type="submit" class="btn btn-success mr-10" @click="getTablaItemPlan()">Filtrar</button>
                    </div>
                   <!-- </form> -->

                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="table-wrap">
                                <div id="contTabla" class="table-responsive" style="width:100%">
                                    <table id="tablaItemPlan" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>Acci&oacute;n</th>
                                                <th>Item Plan</th>
                                                <th>Estacion</th>
                                                <th>Indicador</th>
                                                <th>Sub Proy</th>
                                                <th>Zonal</th>
                                                <th>EECC</th>
                                                <th>Fec. Prevista</th>
                                                <th>Estado Plan</th>
                                                <th># Entidades / # Gestionadas </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background: #e6faff;" v-for="item in tablaHTML">
                                                <th>
                                                <!-- <button type="button" class="btn btn-success  mr-10" @click="mostrarDetalle(item.itemPlan,item.idEstacion, item.flg_provincia)">Ver Detalle</button> -->
                                                <button type="button" class="btn btn-success" @click="mostrarDetalle(item.itemPlan,item.idEstacion, item.flg_provincia)"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                </th>
                                                <th>{{item.itemPlan}}</th>
                                                <th>{{item.estacionDesc}}</th>
                                                <th>{{item.indicador}}</th>
                                                <th>{{item.subProyectoDesc}}</th>
                                                <th>{{item.zonalDesc}}</th>
                                                <th>{{item.empresaColabDesc}}</th>
                                                <th>{{item.fechaPrevEjec}}</th>
                                                <th>{{item.estadoPlanDesc}}</th>
                                                <th>{{item.total_entidades+'/'+item.cant_ent_gestionada}}</th>
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

<div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalRegistrarEntidades" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">LISTA DE ENTIDADES </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <button type="button" id="btnAbrirModalRegEnt" class="btn btn-success"  @click="abrirModalRegisEnt()"><i class="fa fa-plus-square" aria-hidden="true"> Entidades</i></button>
            </div>
            <div id="content" class="modal-body">
                <form id="formRegistrarEntidad" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div class="form-group form-group--float col-sm-12 table-responsive">
                            <table style="font-size: 10px" id="tabla_entidades" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <!-- <th style="width: 15%; display: none">LIQUIDADO</th> -->
                                        <th style="width: 5%"></th>
                                        <th style="width: 10%">ENTIDAD</th>
                                        <th style="width: 8%; text-align: center">EXPEDIENTE</th>
                                        <th style="width: 25%; text-align: center">TIPO</th>
                                        <th style="width: 10%">SUBIR/ VER EVIDENCIA</th>
                                        <th style="width: 25%; text-align: center">DISTRITO</th>
                                        <th style="width: 5%; text-align: center">FEC. INICIO</th>
                                        <th style="width: 5%; text-align: center">FEC. FIN</th>
                                        <th style="width: 5%">ACCI&Oacute;N</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in tablaDetalle" v-bind:id="'trEntidad'+item.iditemplan_estacion_licencia_det"  v-bind:style="{ background: [ item.flg_validado == 1 ? 'lightgreen' : (item.flg_validado == 2 ? '#ebebe0' : '') ] }">
                                        <!-- <td style="display: none">
                                            <input style="margin-left: 30%;" type='checkbox' v-bind:data-index="index" v-bind:id="'chkbx'+item.iditemplan_estacion_licencia_det" class="custom-control-input" disabled="true" v-model="objDataInsert[index].flg_validado">
                                        </td> -->
                                        <td style="text-align: center">
                                            <a @click="abrirModalComprobantes(item.iditemplan_estacion_licencia_det)"
                                            v-bind:style="{ display: [ objDataInsert[index].fecha_inicio == null || objDataInsert[index].fecha_inicio == '' || objDataInsert[index].fecha_fin== null || objDataInsert[index].fecha_fin == '' ? 'none' : 'block'], color: 'var(--verde_telefonica)' , cursor: 'pointer' }"
                                            ><i class="zmdi zmdi-hc-2x zmdi-money"></i></a>

                                            <a v-bind:style="{ display: [ objDataInsert[index].fecha_inicio == null || objDataInsert[index].fecha_inicio == '' || objDataInsert[index].fecha_fin== null || objDataInsert[index].fecha_fin == '' ? 'block' : 'none'], color: '#9c9c63'}"
                                            ><i class="zmdi zmdi-hc-2x zmdi-money"></i></a>
                                        </td>

                                        <td v-bind:id="'txtDescEnt'+item.iditemplan_estacion_licencia_det" v-bind:data-index="index" style="font-weight: bold;">{{item.desc_entidad}}</td>
                                        <td>
                                            <input type="text" style="width: 80px" v-bind:id="'txtCodExp'+index" maxlength="10" class="custom-control-input"  v-model="objDataInsert[index].codigo_expediente" v-bind:disabled="item.flg_validado == 2 ? true : false">
                                        </td>
                                        <td>
                                            <select class="form-control select2" v-bind:id="'tipoLic'+index"  v-model="objDataInsert[index].flg_tipo" v-bind:disabled="item.flg_validado == 2 ? true : false"> 
                                                <option value="0">Seleccionar Tipo</option>
                                                <option value="1">COMUNICATIVA</option>
                                                <option value="2">LICENCIA</option>
                                            </select>
                                        </td>

                                        <td style="text-align: center">
                                            <div class="row">
                                            <div class="col-sm-6 col-md-5">
                                            <a v-bind:style="{ display: [  item.flg_validado == 2 ? 'none' : 'block'], color: 'var(--verde_telefonica)' , cursor: 'pointer' }" @click="abrirModalEvidencia(item.iditemplan_estacion_licencia_det,1,null,null)"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>
                                            <a v-bind:style="{ display: [  item.flg_validado == 2 ? 'block' : 'none'], color: '#9c9c63' }"><i class="zmdi zmdi-hc-2x zmdi-upload"></i></a>
                                            <!-- <button type="button" class="btn btn-success mr-10"  @click="abrirModalEvidencia(item.iditemplan_estacion_licencia_det,1,null,null)" v-bind:disabled="item.flg_validado == 2 ? true : false">Subir</button> -->
                                            </div>
                                            <div class="col-sm-6 col-md-5">
                                            <!-- <button type="button" v-bind:id="'btnVerEviEnt'+item.iditemplan_estacion_licencia_det" class="btn btn-primary waves-effect mr-10" @click="abrirModalImagenEnt(item.ruta_pdf,item.iditemplan_estacion_licencia_det, index)" v-bind:style="{ display: [ item.ruta_pdf != null ? 'block' : 'none'] }"><i class="fa fa-picture-o" aria-hidden="true" ></i></button> -->
                                            <a style="color:var(--verde_telefonica);cursor:pointer" v-bind:id="'btnVerEviEnt'+item.iditemplan_estacion_licencia_det" @click="descargarPDFEntidad(item.iditemplan_estacion_licencia_det,index,2,0)"><i class="zmdi zmdi-hc-2x zmdi-collection-pdf"></i></a>
                                            <!-- <i class="fa fa-ban" v-bind:id="'iconNoImgEvi'+item.iditemplan_estacion_licencia_det" aria-hidden="true"  v-bind:style="{ display: [ item.ruta_pdf != null ? 'none' : 'block'] }" ></i> -->
                                            </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-control select2" v-bind:id="'distEnt'+index" v-model="objDataInsert[index].idDistrito" v-bind:disabled="item.flg_validado == 2 ? true : false" v-bind:style="{ display: [ item.flg_combo == 1  ? 'block' : 'none'] }"> 
                                                <option value="0">Seleccionar Distrito</option>
                                                <option v-for="item in arrayDistritos" v-bind:value="item.idDistrito">{{item.distritoDesc}}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" style="width: 100px" v-bind:id="'txtFechaIni'+index" class="custom-control-input"  v-model="objDataInsert[index].fecha_inicio" v-bind:disabled="item.flg_validado == 2 ? true : false">
                                        </td>
                                        <td>
                                            <input type="date" style="width: 100px" v-bind:id="'txtFechaFin'+index" class="custom-control-input"  v-model="objDataInsert[index].fecha_fin" v-bind:disabled="item.flg_validado == 2 ? true : false">
                                        </td>
                                        <td style="text-align: center">
                                            <a v-bind:style="{ display: [ objDataInsert[index].fecha_inicio == null || objDataInsert[index].fecha_inicio == '' || objDataInsert[index].fecha_fin== null || objDataInsert[index].fecha_fin == '' || item.flg_validado == 2  ? 'none' : 'block'], color: 'var(--verde_telefonica)' , cursor: 'pointer'}"
                                               @click="liquidarDetalle(index)"><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>
                                            <a v-bind:style="{ display: [ objDataInsert[index].fecha_inicio == null || objDataInsert[index].fecha_inicio == '' || objDataInsert[index].fecha_fin== null || objDataInsert[index].fecha_fin == '' || item.flg_validado == 2  ? 'block' : 'none'], color: '#9c9c63'}"
                                            ><i class="zmdi zmdi-hc-2x zmdi-floppy"></i></a>
                                            <!-- <button type="button" class="btn btn-success mr-10"  @click="liquidarDetalle(index)"
                                             v-bind:disabled="objDataInsert[index].fecha_inicio == null || objDataInsert[index].fecha_inicio == '' || objDataInsert[index].fecha_fin== null || objDataInsert[index].fecha_fin == '' || item.flg_validado == 2 ? true : false"><i class="fa fa-floppy-o" aria-hidden="true"></i></button> -->
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


  <div class="modal fade" id="modalSubirEvidencia" tabindex="1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="tituloModal" style="font-weight: bold;">EL NOMBRE DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
          </div>
          <div class="modal-body">
            <div class="col-6">
              <div id="dzDetalleItem" class="dropzone" >

              </div>
              <hr style="border:1;">

              </div>
            </div>
          <div class="modal-footer">
              <button type="button" id="btnAceptarSubirEvidencia" class="btn btn-success">Aceptar</button>
              <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </div>
      </div>
  </div>

  <div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalComprobantes" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModalComprobantes" style="margin: auto;font-weight: bold;" class="modal-title">{{flgMostrarTabla == 0 ? 'LISTA DE COMPROBANTES' : 'REGISTRAR COMPROBANTE'}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!-- <button type="button" id="addComprobante" class="btn btn-success" v-if="flgMostrarTabla == 0"  @click="addComprobante()"><i class="fa fa-plus-square" aria-hidden="true"> Comprobantes</i></button> -->
            </div>
            <div id="content" class="modal-body">
                <form id="formRegistrarComprobante" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div class="form-group form-group--float col-sm-12 table-responsive">
                            <table style="font-size: 10px" id="tabla_comprobantes" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 10%" ># COMPROBANTE</th>
                                        <th style="width: 15%" >FECHA DE EMISI&Oacute;N</th>
                                        <th style="width: 10%" >MONTO(S/)</th>
                                        <th style="width: 10%" >COMPROBANTE</th>
                                        <th style="width: 10%" >VER</th>
                                        <th style="width: 10%" >ESTADO</th>
                                        <th style="width: 10%" >VALIDA COMPROBANTE</th>
                                        <th style="width: 10%" >PRELIQUI ADMINISTRATIVA</th>
                                        <th style="width: 15%" >ACCI&Oacute;N</th>
                                    </tr>
                                </thead>
                                <tbody v-if="flgMostrarTabla == 0">
                                    <tr v-for="(item, index) in tablaComprobantes">
                                        <td>
                                            <input type='text' class="custom-control-input"  v-model="item.desc_reembolso" v-bind:disabled="item.estado_valida == 2 ? true : false">
                                        </td>
                                        <td>
                                            <input type='date' v-bind:id="'txtFechaEmi'+index" class="custom-control-input"  v-model="item.fecha_emision" v-bind:disabled="item.estado_valida == 2 ? true : false">
                                        </td>
                                        <td>
                                            <input type='number' v-bind:id="'txtMondo'+index" class="custom-control-input" v-model="item.monto" v-bind:disabled="item.estado_valida == 2 ? true : false">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success" @click="abrirModalEvidencia(item.idReembolso,2,2,item.desc_reembolso,null,index)" v-bind:disabled="item.fecha_emision == null || item.fecha_emision == '' || item.monto == null || item.monto == '' || item.desc_reembolso == null || item.desc_reembolso == '' || item.estado_valida == 2 ? true : false">Subir</button>
                                        </td>
                                        <td>
                                            <!-- <button type="button" v-bind:id="'btnVerEviCompro'+item.idReembolso" class="btn btn-primary waves-effect mr-10" @click="abrirModalImagenCompro(item.idReembolso, index, 1)"><i class="fa fa-picture-o" aria-hidden="true" ></i></button> -->
                                            <button type="button" v-bind:id="'btnVerEviCompro'+item.idReembolso" class="btn btn-success" @click="descargarPDFCompro(item.idReembolso,index,2,0)"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                                            <!-- <i class="fa fa-ban" v-bind:id="'iconNoImgEvi'+item.idReembolso" aria-hidden="true"  v-bind:style="{ display: [ item.ruta_foto != null ? 'none' : 'block'] }" ></i> -->
                                        </td>
                                        <td>
                                            {{item.estado_valida == 1 ? 'ATENDIDO' : (item.estado_valida == 2 ? 'PRELIQUIDADO' : 'PENDIENTE') }}
                                        </td>
                                        <td>
                                            <input style="margin-left: 30%;" type="checkbox" v-bind:data-index="index" class="custom-control-input" v-model="item.flg_valida_evidencia" v-bind:disabled="item.estado_valida == 2 ? true : false" v-bind:style="{ display: [ item.flg_preliqui_admin == true ? 'none' : 'block'] }">
                                        </td>
                                        <td>
                                            <input style="margin-left: 30%;" type="checkbox" v-bind:data-index="index" class="custom-control-input" v-model="item.flg_preliqui_admin" v-bind:disabled="item.estado_valida == 2 ? true : false" v-bind:style="{ display: [ item.flg_valida_evidencia == true ? 'none' : 'block'] }">
                                        </td>
                                        <td>
                                            <div class="row" v-bind:style="{ display: [ item.estado_valida == 2 ? 'none' : 'block'] }">
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-success" @click="updateComprobante(index)" v-bind:disabled="item.idReembolso ? false : true" v-bind:style="{ display: [ item.flg_valida_evidencia == true ? 'block' : 'none'] }"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-success" @click="deleteComprobante(index, item.idReembolso, item.iditemplan_estacion_licencia_det)"  v-bind:style="{ display: [ (item.flg_valida_evidencia == null || item.flg_valida_evidencia == false || item.flg_valida_evidencia == undefined) &&  item.flg_valida_evidencia != 1 ? 'block' : 'none'] }"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>

                                <tbody v-else="flgMostrarTabla">
                                    <tr>
                                        <td>
                                            <input type="text" id="txtDescReembolso" class="custom-control-input"  v-model="objDataInsertCompro.desc_reembolso" v-bind:disabled="objDataInsertCompro.flg_preliqui_admin == true ? true : false">
                                        </td>
                                        <td>
                                            <input type="date" id="txtFechaEmiCompro" class="custom-control-input"  v-model="objDataInsertCompro.fecha_emision" v-bind:disabled="objDataInsertCompro.flg_preliqui_admin == true ? true : false">
                                        </td>
                                        <td>
                                            <input type="number" id="txtMontoCompro" class="custom-control-input" v-model="objDataInsertCompro.monto" v-bind:disabled="objDataInsertCompro.flg_preliqui_admin == true ? true : false">
                                        </td>
                                        <td>
                                            <button type="button" id="btnSubirFoto" class="btn btn-success mr-10" @click="abrirModalEvidencia(objDataInsertCompro.idReembolso,2,1,objDataInsertCompro.desc_reembolso,null)"
                                             v-bind:disabled="objDataInsertCompro.desc_reembolso == null || objDataInsertCompro.desc_reembolso == '' || objDataInsertCompro.fecha_emision == null || objDataInsertCompro.fecha_emision == ''  || objDataInsertCompro.monto == null || objDataInsertCompro.monto == ''  ? true : false"  v-bind:style="{ display: [ objDataInsertCompro.flg_preliqui_admin == true ? 'none' : 'block'] }">Subir</button>
                                        </td>
                                        <td>
                                            <!-- <button type="button" id="btnVerEviCompro" class="btn btn-primary waves-effect mr-10" @click="abrirModalImagenCompro()" style="display: none"> <i class="fa fa-picture-o" aria-hidden="true"></i></button> -->
                                            <button type="button" id="btnVerEviCompro" class="btn btn-success" @click="descargarPDFCompro(objDataInsertCompro.idReembolso,null,1,0)" v-bind:style="{ display: [ objDataInsertCompro.flg_preliqui_admin == true ? 'none' : 'block'] }"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                                            <!-- <i class="fa fa-ban" id="iconNoImgCompro" aria-hidden="true" style="display : block"></i> -->
                                        </td>
                                        <td>
                                            {{objDataInsertCompro.estado_valida == 1 ? 'ATENDIDO' : (objDataInsertCompro.estado_valida == 2 ? 'PRELIQUIDADO' : 'PENDIENTE') }}
                                        </td>
                                        <td>
                                            <input style="margin-left: 30%;" type="checkbox" class="custom-control-input" v-model="objDataInsertCompro.flg_valida_evidencia" v-bind:style="{ display: [ objDataInsertCompro.flg_preliqui_admin == true ? 'none' : 'block'] }">
                                        </td>
                                        <td>
                                            <input style="margin-left: 30%;" id="chkxPreLiquiAd" type="checkbox" class="custom-control-input" v-model="objDataInsertCompro.flg_preliqui_admin" @change="preliqAdmin('chkxPreLiquiAd')" v-bind:style="{ display: [ objDataInsertCompro.flg_valida_evidencia == true ? 'none' : 'block'] }">
                                        </td>
                                        <td>
                                            <button type="button" id="btnSaveComprobante" class="btn btn-success"  @click="saveComprobante()" disabled v-bind:style="{ display: [ objDataInsertCompro.flg_valida_evidencia == true && (objDataInsertCompro.flg_preliqui_admin == false || objDataInsertCompro.flg_preliqui_admin == null) ? 'block' : 'none'] }">Guardar</button>
                                            <button type="button" class="btn btn-success" @click="saveComproAdministrativo()"  v-bind:style="{ display: [ (objDataInsertCompro.flg_valida_evidencia == false || objDataInsertCompro.flg_valida_evidencia == null)  && objDataInsertCompro.flg_preliqui_admin == true ? 'block' : 'none'] }">Guardar</button>
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

<div class="modal fade" id="modalSubirFotoComprobante" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="tituloModalComprobante" style="font-weight: bold;">EL NOMBRE DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
          </div>
          <div class="modal-body">
            <div class="col-6">
              <div id="dzDetalleComprobante" class="dropzone" >

              </div>
              <hr style="border:1;">

              </div>
            </div>
          <div class="modal-footer">
              <button type="button" id="btnAceptarSubirFotoComprobante" class="btn btn-success">Aceptar</button>
              <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </div>
      </div>
  </div>

    <div class="modal fade" id="modalEvidenciaEnt" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloEvidencia">EVIDENCIA</h5>
                </div>
                <div class="modal-body">
                    <img src="" class="img-rounded" alt="Cinque Terre" width="304" height="236"  id="evidenciaEnt"/>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAceptarEvi" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    <button id="btnCloseEvi" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEvidenciaComprobante" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloEvidencia">EVIDENCIA</h5>
                </div>
                <div class="modal-body">
                    <img src="" class="img-rounded" alt="Cinque Terre" width="304" height="236"  id="evidenciaCompro"/>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAceptarEvi" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    <button id="btnCloseEvi" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" aria-labelledby="myLargeModalLabel" role="dialog" id="modalEntProv" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">LISTA DE ENTIDADES </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <button type="button" id="btnAbrirModalRegEntProv" class="btn btn-success" @click="abrirModalRegisEnt()"><i class="fa fa-plus-square" aria-hidden="true"> Entidades</i></button>
                </div>
                <div id="content" class="modal-body">
                    <form id="formEntCheqProv" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                        <div class="row">
                            <div class="form-group form-group--float col-sm-12 table-responsive">
                                <table style="font-size: 10px" id="tabla_entidades" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%" >ENTIDAD</th>
                                            <th style="width: 10%;" id="thCheque"># CHEQUE</th>
                                            <th style="width: 10%" >ACOTACI&Oacute;N</th>
                                            <th style="width: 10%" >LIQUIDAR</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in tablaDetalle">
                                            <td v-bind:id="'txtDescEnt'+index">
                                                {{item.desc_entidad}}
                                            </td>
                                            <td >
                                                <input type='text' v-bind:id="'txtNroCheque'+index" class="custom-control-input"  v-model="objDataInsert[index].nro_cheque" v-bind:style="{ display: [ item.flg_acotacion_valida == null || item.flg_acotacion_valida == 0 ? 'none' : 'block'] }">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success mr-10"  @click="abrirModalAcotacion(item.iditemplan_estacion_licencia_det,index)">Acotaci&oacute;n</button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success mr-10" @click="abrirModalEntProvxLiquidar(index,item.iditemplan_estacion_licencia_det,objDataInsert[index].nro_cheque)" v-bind:disabled="objDataInsert[index].nro_cheque == null  || objDataInsert[index].nro_cheque == ''  ? true : false" >Liquidar</button>
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

      <div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalAcotaciones" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="tituloModalAcotaciones" style="margin: auto;font-weight: bold;" class="modal-title">{{flgMostrarTablaAcota == 0 ? 'LISTA DE ACOTACIONES' : 'REGISTRO DE ACOTACI&Oacute;N'}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div id="content" class="modal-body">
                    <form id="formRegistrarAcotacion" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                        <div class="row">
                            <div class="form-group form-group--float col-sm-12 table-responsive">
                                <table style="font-size: 10px" id="tablaAcotaciones" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%" ># ACOTACI&Oacute;N</th>
                                            <th style="width: 15%" >FECHA DE ACOTACI&Oacute;N</th>
                                            <th style="width: 15%" >MONTO</th>
                                            <th style="width: 15%" >ACOTACI&Oacute;N</th>
                                            <th style="width: 10%" >VER</th>
                                            <th style="width: 15%" >ESTADO</th>
                                            <th style="width: 15%" >ACCI&Oacute;N</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="flgMostrarTablaAcota == 0">
                                        <tr v-for="(item, index) in tablaAcotaciones">
                                            <td>
                                                <input type='text' v-bind:id="'txtDescAcota'+index"  class="custom-control-input"  v-model="item.desc_acotacion">
                                            </td>
                                            <td>
                                                <input type='date' v-bind:id="'txtFechaAcota'+index" class="custom-control-input"  v-model="item.fecha_acotacion">
                                            </td>
                                            <td>
                                                <input type='number' v-bind:id="'txtMondo'+index" class="custom-control-input" v-model="item.monto">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success" @click="abrirModalEviAcota(item.idAcotacion,item.desc_acotacion,2,index)" v-bind:disabled="item.fecha_acotacion == null || item.fecha_acotacion == '' || item.monto == null || item.monto == '' || item.desc_acotacion == null || item.desc_acotacion == '' ? true : false">Subir</button>
                                            </td>
                                            <td>
                                                <button type="button" v-bind:id="'btnVerEviCompro'+item.idAcotacion" class="btn btn-success" @click="descargarPDFAcota(item.idAcotacion, index, 2)" v-bind:style="{ display: [ item.ruta_foto != null ? 'block' : 'none'] }"><i class="fa fa-file-pdf-o" aria-hidden="true" ></i></button>
                                                <i class="fa fa-ban" v-bind:id="'iconNoImgEvi'+item.idAcotacion" aria-hidden="true"  v-bind:style="{ display: [ item.ruta_foto != null ? 'none' : 'block'] }" ></i>
                                            </td>
                                            <td>
                                                {{item.estado_valida == 1 ? 'ATENDIDO' : 'PENDIENTE'}}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success" @click="updateAcotacion(index)">Guardar</button>
                                            </td>
                                        </tr>
                                    </tbody>

                                    <tbody v-else="flgMostrarTablaAcota">
                                        <tr>
                                            <td>
                                                <input type='text' id="txtDescAcota" class="custom-control-input"  v-model="objDataInsertAcota.desc_acotacion" onkeyup="habilitaSubirFotoAcota()">
                                            </td>
                                            <td>
                                                <input type='date' id="txtfechaAcota" class="custom-control-input"  v-model="objDataInsertAcota.fecha_acotacion">
                                            </td>
                                            <td>
                                                <input type='number' id="txtMonto" class="custom-control-input" v-model="objDataInsertAcota.monto">
                                            </td>
                                            <td>
                                                <button type="button" id="btnSubirFotoAcota" class="btn btn-success" @click="abrirModalEviAcota(objDataInsertAcota.idAcotacion,objDataInsertAcota.desc_acotacion,1,null)" disabled>Subir</button>
                                            </td>
                                            <td>
                                                <!-- <button type="button" id="btnVerEviAcota" class="btn btn-primary waves-effect mr-10" @click="abrirModalImagenAcota(null,null,0)" style="display: none"> <i class="fa fa-picture-o" aria-hidden="true"></i></button> -->
                                                <button type="button" id="btnVerEviAcota" class="btn btn-success" @click="descargarPDFAcota(objDataInsertAcota.idAcotacion,null,1)" style="display: none"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                                                <i class="fa fa-ban" id="iconNoImgAcota" aria-hidden="true" style="display : block"></i>
                                            </td>
                                            <td>
                                                {{objDataInsertAcota.estado_valida == 1 ? 'ATENDIDO' : 'PENDIENTE'}}
                                            </td>
                                            <td>
                                                <button type="button" id="btnSaveAcota" class="btn btn-success"  @click="saveAcotacion()" disabled>Guardar</button>
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

     <div class="modal fade" id="modalSubirEviAcota" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="tituloModalAcota" style="font-weight: bold;">EL NOMBRE DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
          </div>
          <div class="modal-body">
            <div class="col-6">
              <div id="dzDetalleAcota" class="dropzone" >

              </div>
              <hr style="border:1;">

              </div>
            </div>
          <div class="modal-footer">
              <button type="button" id="btnAceptarSubirEviAcota" class="btn btn-success">Aceptar</button>
              <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </div>
      </div>
  </div>

    <div class="modal fade" id="modalEviAcotacion" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloEvidenciaAcota">EVIDENCIA</h5>
                </div>
                <div class="modal-body">
                    <img src="" class="img-rounded" alt="Cinque Terre" width="304" height="236"  id="evidenciaAcota"/>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAceptarEviAcota" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    <button id="btnCloseEviAcota" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalEntxLiquiProv" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModalEntProv" style="margin: auto;font-weight: bold;" class="modal-title">ENTIDAD PRELIQUIDADA </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="content" class="modal-body">
                <form id="formEntProv" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div class="form-group form-group--float col-sm-12 table-responsive">
                            <table style="font-size: 10px" id="tablaEntProv" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <!-- <th style="width: 15%; display: none">LIQUIDADO</th> -->
                                        <th style="width: 10%">ENTIDAD</th>
                                        <th style="width: 10%">EVIDENCIA</th>
                                        <th style="width: 5%">VER</th>
                                        <th style="width: 25%">DISTRITO</th>
                                        <th style="width: 10%">FECHA DE INICIO</th>
                                        <th style="width: 10%">FECHA DE FIN</th>
                                        <th style="width: 10%">ACCI&Oacute;N</th>
                                        <th style="width: 10%">REEMBOLSO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="trEntidadProv" v-bind:style="{ background: [ tablaEntProv.flg_validado == 1 ? 'lightgreen' : (tablaEntProv.flg_validado == 2 ? '#1affff' : '') ] }">
                                        <!-- <td style="display: none">
                                            <input style="margin-left: 30%;" type='checkbox' v-bind:id="'chbx'+tablaEntProv.iditemplan_estacion_licencia_det" class="custom-control-input" disabled="true" v-model="jsonUpdateEntProv.flg_validado">
                                        </td> -->
                                        <td style="font-weight: bold;">{{tablaEntProv.desc_entidad}}</td>
                                        <td>
                                            <button type="button" class="btn btn-success"  @click="abrirModalEvidencia(tablaEntProv.iditemplan_estacion_licencia_det,1,null,null,1)">Subir</button>
                                        </td>
                                        <td>
                                            <button type="button" v-bind:id="'btnVerEviEntProv'+tablaEntProv.iditemplan_estacion_licencia_det" class="btn btn-success" @click="descargarPDFEntidad(tablaEntProv.iditemplan_estacion_licencia_det,null, 1, 1)"><i class="fa fa-file-pdf-o" aria-hidden="true" ></i></button>
                                            <!-- <i class="fa fa-ban" v-bind:id="'iconNoImgEviProv'+tablaEntProv.iditemplan_estacion_licencia_det" aria-hidden="true"  v-bind:style="{ display: [ tablaEntProv.ruta_pdf  ? 'none' : 'block'] }"></i> -->
                                        </td>
                                        <td>
                                            <select class="form-control select2" name="distritoProv" id="distritoProv" v-model="jsonUpdateEntProv.idDistrito"> 
                                                <option value="0">Seleccionar Distrito</option>
                                                <option v-for="item in arrayDistritos" v-bind:value="item.idDistrito">{{item.distritoDesc}}</option>
                                            </select>
                                            <!-- <input type="email" v-bind:id="'txtEmail'+tablaEntProv.iditemplan_estacion_licencia_det" class="custom-control-input"  v-model="jsonUpdateEntProv.correo_usuario_valida" style="width: 200px"> -->
                                        </td>
                                        <td>
                                            <input type="date" v-bind:id="'txtFechaIni'+tablaEntProv.iditemplan_estacion_licencia_det" class="custom-control-input"  v-model="jsonUpdateEntProv.fecha_inicio">
                                        </td>
                                        <td>
                                            <input type="date" v-bind:id="'txtFechaFin'+tablaEntProv.iditemplan_estacion_licencia_det" class="custom-control-input"  v-model="jsonUpdateEntProv.fecha_fin">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success mr-10"  @click="liquidarDetalleProv()"
                                            v-bind:disabled="jsonUpdateEntProv.fecha_inicio == null || jsonUpdateEntProv.fecha_inicio == '' || jsonUpdateEntProv.fecha_fin== null || jsonUpdateEntProv.fecha_fin == '' ? true : false"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success mr-10" @click="abrirModalComproProv(tablaEntProv.iditemplan_estacion_licencia_det)"
                                            v-bind:disabled="jsonUpdateEntProv.fecha_inicio == null || jsonUpdateEntProv.fecha_inicio == '' || jsonUpdateEntProv.fecha_fin== null || jsonUpdateEntProv.fecha_fin == '' ? true : false"><i class="fa fa-usd" aria-hidden="true"></i><</button>
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

  <div class="modal fade" id="modalSubirEviProv" tabindex="1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="tituloModalProv" style="font-weight: bold;">EL NOMBRE DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
          </div>
          <div class="modal-body">
            <div class="col-6">
              <div id="dzEviProv" class="dropzone" >

              </div>
              <hr style="border:1;">

              </div>
            </div>
          <div class="modal-footer">
              <button type="button" id="btnAceptarSubirEviProv" class="btn btn-success">Aceptar</button>
              <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </div>
      </div>
  </div>


     <div class="modal fade" id="modalEviEntProv" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituEviProv">EVIDENCIA</h5>
                </div>
                <div class="modal-body">
                    <img src="" class="img-rounded" alt="Cinque Terre" width="304" height="236"  id="evidenciaEntProv"/>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAceptarEviProv" class="btn btn-success" data-dismiss="modal">Aceptar</button>
                    <button id="btnCloseEviProv" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


      <div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalComprobantesProv" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModalComprobantesProv" style="margin: auto;font-weight: bold;" class="modal-title">{{flgMostrarTablaProv == 0 ? 'LISTA DE COMPROBANTES' : 'REGISTRAR COMPROBANTE'}}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!-- <button type="button" id="addComprobanteProv" class="btn btn-success" v-if="flgMostrarTablaProv == 0"  @click="addComprobante()"><i class="fa fa-plus-square" aria-hidden="true"> Comprobantes</i></button> -->
            </div>
            <div id="content" class="modal-body">
                <form id="formRegistrarComprobanteProv" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div class="form-group form-group--float col-sm-12 table-responsive">
                            <table style="font-size: 10px" id="tabla_comprobantesProv" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 10%" ># COMPROBANTE</th>
                                        <th style="width: 15%" >FECHA DE EMISI&Oacute;N</th>
                                        <th style="width: 10%" >MONTO(S/)</th>
                                        <th style="width: 10%" >COMPROBANTE</th>
                                        <th style="width: 10%" >VER</th>
                                        <th style="width: 10%" >ESTADO</th>
                                        <th style="width: 10%" >VALIDA COMPROBANTE</th>
                                        <th style="width: 10%" >PRELIQUI ADMINISTRATIVA</th>
                                        <th style="width: 15%" >ACCI&Oacute;N</th>
                                    </tr>
                                </thead>
                                <tbody v-if="flgMostrarTablaProv == 0">
                                    <tr v-for="(item, index) in tablaComprobantes">
                                        <td>
                                            <input type="text"  v-bind:id="'txtDescReembolsoProv'+index" class="custom-control-input"  v-model="item.desc_reembolso" v-bind:disabled="item.estado_valida == 2 ? true : false">
                                        </td>
                                        <td>
                                            <input type="date" v-bind:id="'txtFechaEmiProv'+index" class="custom-control-input"  v-model="item.fecha_emision" v-bind:disabled="item.estado_valida == 2 ? true : false">
                                        </td>
                                        <td>
                                            <input type="number" v-bind:id="'txtMondoProv'+index" class="custom-control-input" v-model="item.monto" v-bind:disabled="item.estado_valida == 2 ? true : false">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success" @click="abrirModalEvidencia(item.idReembolso,2,2,item.desc_reembolso,2,index)" v-bind:disabled="item.fecha_emision == null || item.fecha_emision == '' || item.monto == null || item.monto == '' || item.desc_reembolso == null || item.desc_reembolso == '' || item.estado_valida == 2 ? true : false">Subir</button>
                                        </td>
                                        <td>
                                            <button type="button" v-bind:id="'btnVerEviComproProv'+item.idReembolso" class="btn btn-success" @click="descargarPDFCompro(item.idReembolso, index, 2, 1)"><i class="fa fa-file-pdf-o" aria-hidden="true" ></i></button>
                                            <!-- <i class="fa fa-ban" v-bind:id="'iconNoImgEviProv'+item.idReembolso" aria-hidden="true"  v-bind:style="{ display: [ item.ruta_foto != null ? 'none' : 'block'] }" ></i> -->
                                        </td>
                                        <td>
                                            {{item.estado_valida == 1 ? 'ATENDIDO' : (item.estado_valida == 2 ? 'PRELIQUIDADO' : 'PENDIENTE') }}
                                        </td>
                                        <td>
                                            <input style="margin-left: 30%;" type="checkbox" v-bind:data-index="index" class="custom-control-input" v-model="item.flg_valida_evidencia" v-bind:disabled="item.estado_valida == 2 ? true : false" v-bind:style="{ display: [ item.flg_preliqui_admin == true ? 'none' : 'block'] }">
                                        </td>
                                        <td>
                                            <input style="margin-left: 30%;" type="checkbox" v-bind:data-index="index" class="custom-control-input" v-model="item.flg_preliqui_admin" v-bind:disabled="item.estado_valida == 2 ? true : false" v-bind:style="{ display: [ item.flg_valida_evidencia == true ? 'none' : 'block'] }">
                                        </td>
                                        <td>
                                            <!-- <button type="button" class="btn btn-primary waves-effect mr-10" @click="updateComprobanteProv(index)">Guardar</button> -->
                                            <div class="row" v-bind:style="{ display: [ item.estado_valida == 2 ? 'none' : 'block'] }">
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-success" @click="updateComprobanteProv(index)" v-bind:disabled="item.idReembolso ? false : true" v-bind:style="{ display: [ item.flg_valida_evidencia == true ? 'block' : 'none'] }"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-success" @click="deleteComprobante(index, item.idReembolso, item.iditemplan_estacion_licencia_det)"  v-bind:style="{ display: [ (item.flg_valida_evidencia == null || item.flg_valida_evidencia == false || item.flg_valida_evidencia == undefined) &&  item.flg_valida_evidencia != 1 ? 'block' : 'none'] }"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>

                                <tbody v-else="flgMostrarTablaProv">
                                    <tr>
                                        <td>
                                            <input type='text' id="txtDescReembolsoProv" class="custom-control-input"  v-model="objDataInsertCompro.desc_reembolso" v-bind:disabled="objDataInsertCompro.flg_preliqui_admin == true ? true : false">
                                        </td>
                                        <td>
                                            <input type='date' id="txtFechaEmiProv" class="custom-control-input"  v-model="objDataInsertCompro.fecha_emision" v-bind:disabled="objDataInsertCompro.flg_preliqui_admin == true ? true : false">
                                        </td>
                                        <td>
                                            <input type='number' id="txtMontoProv" class="custom-control-input" v-model="objDataInsertCompro.monto" v-bind:disabled="objDataInsertCompro.flg_preliqui_admin == true ? true : false">
                                        </td>
                                        <td>                                                                           
                                            <button type="button" id="btnSubirFotoProv" class="btn btn-success" @click="abrirModalEvidencia(objDataInsertCompro.idReembolso,2,1,objDataInsertCompro.desc_reembolso,2)" 
                                            v-bind:disabled="objDataInsertCompro.desc_reembolso == null || objDataInsertCompro.desc_reembolso == '' || objDataInsertCompro.fecha_emision == null || objDataInsertCompro.fecha_emision == ''  || objDataInsertCompro.monto == null || objDataInsertCompro.monto == ''  ? true : false"  v-bind:style="{ display: [ objDataInsertCompro.flg_preliqui_admin == true ? 'none' : 'block'] }">Subir</button>
                                        </td>
                                        <td>
                                            <button type="button" id="btnVerEviComproProv" class="btn btn-success" @click="descargarPDFCompro(objDataInsertCompro.idReembolso,null,1,1)" v-bind:style="{ display: [ objDataInsertCompro.flg_preliqui_admin == true ? 'none' : 'block'] }"> <i class="fa fa-picture-o" aria-hidden="true"></i></button>
                                            <!-- <i class="fa fa-ban" id="iconNoImgComproProv" aria-hidden="true" style="display : block"></i> -->
                                        </td>
                                        <td>
                                            {{objDataInsertCompro.estado_valida == 1 ? 'ATENDIDO' : (objDataInsertCompro.estado_valida == 2 ? 'PRELIQUIDADO' : 'PENDIENTE') }}
                                        </td>
                                        <td>
                                            <input style="margin-left: 30%;" type="checkbox" class="custom-control-input" v-model="objDataInsertCompro.flg_valida_evidencia" v-bind:style="{ display: [ objDataInsertCompro.flg_preliqui_admin == true ? 'none' : 'block'] }">
                                        </td>
                                        <td>
                                            <input style="margin-left: 30%;" id="chkxPreLiquiAdProv" type="checkbox" class="custom-control-input" v-model="objDataInsertCompro.flg_preliqui_admin" @change="preliqAdmin('chkxPreLiquiAdProv')" v-bind:style="{ display: [ objDataInsertCompro.flg_valida_evidencia == true ? 'none' : 'block'] }">
                                        </td>
                                        <td>
                                            <button type="button" id="btnSaveComprobanteProv" class="btn btn-success"  @click="saveComprobanteProv()" disabled v-bind:style="{ display: [ objDataInsertCompro.flg_valida_evidencia == true && (objDataInsertCompro.flg_preliqui_admin == false || objDataInsertCompro.flg_preliqui_admin == null) ? 'block' : 'none'] }">Guardar</button>
                                            <button type="button" class="btn btn-success" @click="saveComproAdministrativoProv()"  v-bind:style="{ display: [ (objDataInsertCompro.flg_valida_evidencia == false || objDataInsertCompro.flg_valida_evidencia == null)  && objDataInsertCompro.flg_preliqui_admin == true ? 'block' : 'none'] }">Guardar</button>
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


 <div class="modal fade" id="modalSubirEviComproProv" tabindex="1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="tituloModalComproProv" style="font-weight: bold;">EL NOMBRE DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
          </div>
          <div class="modal-body">
            <div class="col-6">
              <div id="dzEviComproProv" class="dropzone" >

              </div>
              <hr style="border:1;">

              </div>
            </div>
          <div class="modal-footer">
              <button type="button" id="btnAceptarSubirEviComproProv" class="btn btn-success">Aceptar</button>
              <button id="btnCloseComproProv" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </div>
      </div>
  </div>

   <div class="modal fade" id="modalEvidenciaComprobanteProv" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloEvidenciaComproProv">EVIDENCIA</h5>
                </div>
                <div class="modal-body">
                    <img src="" class="img-rounded" alt="Cinque Terre" width="304" height="236"  id="evidenciaComproProv"/>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAceptarEviComproProv" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    <button id="btnCloseEvi" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


     <div class="modal fade" id="modalRegistrarEnt"  tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title">A&Ntilde;ADIR ENTIDADES</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="font-weight: bold;color: black;">Asignar Entidades</div>
                                    <div class="panel-body">
                                        <div class="col-sm-12 col-md-12 form-inline" id="formEntidades">
                                            <div class="row">
                                                <div class="col-sm-12 form-group" id="contEntidades">
                                                    <label for="idCmbEnt">Entidades: </label>
                                                    <select id="idCmbEnt" name="idCmbEnt" class="select2 form-control">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                            </div>
                            <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button  type="submit" id="btnAceptarEnt"  class="btn btn-success" @click="registrarEntidades()">Aceptar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


</div>

<!-- <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script> -->

<script src="<?php echo base_url(); ?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url(); ?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url(); ?>public/dist/js/init.js"></script>
<script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time(); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url(); ?>public/demo/js/flot-charts/chart-tooltips.js?v=<?php echo time(); ?>"></script>


<script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time(); ?>"></script>



<script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="<?php echo base_url(); ?>public/vendors/bower_components/jszip/dist/jszip.min.js"></script>
  <script src="<?php echo base_url(); ?>public/vendors/bower_components/pdfmake/build/pdfmake.min.js"></script>
  <script src="<?php echo base_url(); ?>public/vendors/bower_components/pdfmake/build/vfs_fonts.js"></script>
  <script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="<?php echo base_url(); ?>public/vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="<?php echo base_url(); ?>public/dist/js/export-table-data.js"></script>

<script src="<?php echo base_url(); ?>public/js/js_licencias/js_registro_itemPlan_estacion.js"></script>
