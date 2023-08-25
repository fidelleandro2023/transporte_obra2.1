<link rel="stylesheet" href="<?php echo base_url();?>public/css/galeria_fotos.css?v=<?php echo time();?>">
<style>.fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}</style>


<main id="consult">
    <div class="page-wrapper">
        <div class="container-fluid">
            
             <div class="row heading-lg">
            
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                     <ol class="breadcrumb">
                    <li><a href="index.php">Inicio</a></li>
                    <!-- <li><a href="#" class=""><span>Registro Ficha Tecnica</span></a></li> -->
                    <li><a href="#" class="active"><span><?php echo $pagina;?></span></a></li>
                    <li><a href="#" class="active"><span>Consultar Formulario</span></a></li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2 ><?php echo strtoupper($pagina);?></h2>
                </div>

    
            
            
        </div>
        
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a data-toggle="tab" href="#formulario">Formulario</a></li>
                <li role="presentation"><a data-toggle="tab" href="#material">Material</a>
            </ul>
        </div>
        <div class="tab-content">
            <div id="formulario" class="tab-pane fade in active">
                <div class="panel panel-default card-view">
                    <div class="panel-body">
                        <div class="row form-group">
                            <div class="col-sm-2">
                                <label>No pasan 12 horas<label>
                                <input type="text" style="background:green;width:35px" disabled>
                            </div>
                            <div class="col-md-2">
                                <label>Pasaron 12 horas<label>
                                <input type="text" style="background:red;width:35px" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Solo podr&aacute; editar el material durante 24 horas.<label>
                            </div>
                        </div>
                        <div class="table-wrap">
                            <div class="table-responsive" style="width:100%">
                                <table id="simpletable" class="container-fluid table table-hover display  pb-30 table-striped table-bordered nowrap">
                                    <thead>
                                        <th>Itemplan</th>
                                        <th>Fase</th>
                                        <th>Tipo de Obra</th>
                                        <th>Fecha de Registro</th>
                                        <th>Usuario Registro</th>
                                        <th>Empresa Colaboradora</th>
                                        <th>Jefatura</th>
                                        <th>Licencia</th>
                                        <th>Acci&oacute;n</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="row in arrayFormulario" v-bind:style="{background:row.color}">
                                            <td>{{row.itemplan}}</td>
                                            <td>{{row.faseDesc}}</td>
                                            <td>{{row.tipo_obra}}</td>
                                            <td>{{row.fec_registro}}</td>
                                            <td>{{row.usuario}}</td>
                                            <td>{{row.empresacolabDesc}}</td>
                                            <td>{{row.jefatura}}</td>
                                            <td>{{row.licencia}}</td>
                                            <td>
                                                <a style="cursor:pointer"><i class="fa fa-pencil"></i></a>
                                                <a style="cursor:pointer" @click="openDetalleForm(row.itemplan, row.idTipo_obra)"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>        
                </div>
            </div>
            
            <div id="material" class="tab-pane fade">
                <div class="panel panel-default card-view">
                    <div class="panel-body">
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>No pasan 12 horas<label>
                                <input type="text" style="background:green;width:35px" disabled>
                            </div>
                            <div class="col-md-2">
                                <label>Pasaron 12 horas<label>
                                <input type="text" style="background:red;width:35px" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Solo podr&aacute; editar el material durante 24 horas.<label>
                            </div>
                        </div>

                        <div class="table-wrap">
                            <div class="table-responsive" style="width:100%">
                                <table id="simpletable2" class="container-fluid table table-hover display  pb-30 table-striped table-bordered nowrap">
                                    <thead>
                                        <th>Itemplan</th>
                                        <th>Fase</th>
                                        <th>Cuadrilla</th>
                                        <th>C&oacute;digo Cuadrilla</th>
                                        <th>Tiempo transcurrido(Hr:Min:Sec)</th>
                                        <th>Fecha Registro</th>
                                        <th>Observaci&oacute;n</th>
                                        <th>Acci&oacute;n</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="row in arrayMaterial" v-bind:style="{background:row.color}">
                                            <td>{{row.itemplan}}</td>
                                            <td>{{row.faseDesc}}</td>
                                            <td>{{row.jefe_c_nombre}}</td>
                                            <td>{{row.jefe_c_codigo}}</td>
                                            <td>{{row.tiempoTranscurrido}}</td>
                                            <td>{{row.fecha_registro}}</td>
                                            <td>{{row.observacion}}</td>
                                            <td>
                                                <a style="cursor:pointer" @click="openModalEditarMaterial(row.itemplan, row.id_ficha_tecnica)"><i class="fa fa-pencil"></i></a>
                                                <a style="cursor:pointer" @click="openmodalEditarMaterialDetalle(row.id_ficha_tecnica)"><i class="fa fa-eye"></i></a>
                                            </td>
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
    <div id="modalDetalle" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content container" id="sisego">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Detalle</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                        <thead v-if="idTipo_obra == 1">
                            <th>Itemplan</th>
                            <th>Nombre</th>
                            <th>N&uacute;mero Troncal</th>
                            <th>Cant. Hilos Habilitados</th>
                            <th>Nodo</th>
                            <th>Coordenada X</th>
                            <th>Coordenada Y</th> 
                            <th>Ubicaci&oacute;n</th>
                            <th>Acci&oacute;n</th>                            
                        </thead>
                        <thead v-if="idTipo_obra == 2">
                            <th>Itemplan</th>
                            <th>Cant. Hilos</th>
                            <th>Cant. Nodos</th>
                            <th>Nodos</th>
                            <th>Acci&oacute;n</th>                        
                        </thead>
                        <thead v-if="idTipo_obra == 3">
                            <th>Itemplan</th>
                            <th>Reubicaci&oacute;n de cable externo</th>
                            <th>Reubicaci&oacute;n de cable interno</th>
                            <th>Acci&oacute;n</th>                          
                        </thead>
                        <thead v-if="idTipo_obra == 4">
                            <th>Itemplan</th>
                            <th>Cantidad de Hilos</th>
                            <th>Cantidad de Hilos Habilitados</th> 
                            <th>Acci&oacute;n</th>                     
                        </thead>
                        <tbody>
                            <tr v-for="row in arrayDetalle" v-if="row.idTipo_obra == 1">
                                <td>{{row.itemplan}}</td>
                                <td>{{row.nap_nombre}}</td>
                                <td>{{row.nap_num_troncal}}</td>
                                <td>{{row.nap_cant_hilos_habi}}</td>
                                <td>{{row.nap_nodo}}</td>
                                <td>{{row.nap_coord_x}}</td>
                                <td>{{row.nap_coord_y}}</td>
                                <td>{{row.nap_ubicacion}}</td>
                                <td>
                                    <a style="cursor:pointer" @click="openModalEditar(row)"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>
                            <tr v-for="row in arrayDetalle" v-if="row.idTipo_obra == 2">
                                <td>{{row.itemplan}}</td>
                                <td>{{row.fo_oscu_cant_hilos}}</td>
                                <td>{{row.fo_oscu_cant_nodos}}</td>
                                <td>{{row.cod_nodos}}</td>
                                <td>
                                    <a style="cursor:pointer" @click="openModalEditar(row)"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>
                            <tr v-for="row in arrayDetalle" v-if="row.idTipo_obra == 3">
                                <td>{{row.itemplan}}</td>
                                <td>{{row.trasla_re_cable_externo}}</td>
                                <td>{{row.trasla_re_cable_interno}}</td>
                                <td>
                                    <a style="cursor:pointer" @click="openModalEditar(row)"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr> 
                            <tr v-for="row in arrayDetalle" v-if="row.idTipo_obra == 4">
                                <td>{{row.itemplan}}</td>
                                <td>{{row.fo_tra_cant_hilos}}</td>
                                <td>{{row.fo_tra_cant_hilos_hab}}</td>
                                <td>
                                    <a style="cursor:pointer" @click="openModalEditar(row)"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>                                
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEditar" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content container-fluid" id="sisego">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar</h4>
                </div>
                <div class="modal-body">
                    <div v-if="jsonDetalle.idTipo_obra==1">
                        <div class="form-group col-md-6">
                            <label>Nombre</label>
                            <input type="text" class="form-control" v-model="jsonDetalle.nap_nombre"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>N&uacute;mero Troncal</label>
                            <input type="text" class="form-control" v-model="jsonDetalle.nap_num_troncal"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Cant. hilos habilitados</label>
                            <input type="text" class="form-control" v-model="jsonDetalle.nap_cant_hilos_habi"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Nodo</label>
                            <input type="text" class="form-control" v-model="jsonDetalle.nap_nodo"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Coordenada x</label>
                            <input type="text" class="form-control" v-model="jsonDetalle.nap_coord_x" disabled/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Coordenada y</label>
                            <input type="text" class="form-control" v-model="jsonDetalle.nap_coord_y" disabled/>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Ubicaci&oacute;n</label>
                            <select id="cmbUbicacion" class="form-control" v-model="jsonDetalle.nap_ubicacion">
                                <option value="0">Seleccionar</option>     
                                <option value="Poste">Poste</option>   
                                <option value="C&aacute;mara">C&aacute;mara</option>   
                                <option value="Edificio del cliente">Edificio del cliente</option>   
                                <option value="Centro Comercial">Centro Comercial</option>                       
                            </select>
                        </div>
                        <div v-if="jsonDetalle.nap_ubicacion == 'Edificio del cliente'">
                            <div class="col-sm-6 form-group">
                                <label>Piso</label>
                                <input type="text" class="form-control" v-model="jsonDetalle.nap_num_pisos">
                            </div>
                        </div>
                        <div v-if="jsonDetalle.nap_ubicacion == 'Centro Comercial'">
                            <div class="col-sm-6 form-group">
                                <label>Zona</label>
                                <input type="text" class="form-control" v-model="jsonDetalle.nap_zona">
                            </div>                        
                        </div>  
                    </div>
                    <div v-if="jsonDetalle.idTipo_obra==2">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Cantidad de hilos</label>
                                <input type="text" class="form-control" v-model="jsonDetalle.fo_oscu_cant_hilos">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Cantidad de Nodos</label>
                                <input type="text" class="form-control" v-model="cantNodos">
                            </div>
                            <div class="row" v-if="cantNodos != ''">
                                <div class="form-group" v-if="cantNodos > 0 " v-for="n in parseInt(cantNodos)">
                                    <div class="col-sm-3 form-group">
                                        <label>Nombre{{- n}}</label>
                                        <!-- <input class="form-control" v-bind:id="'input_'+n"> -->
                                            <select  class="form-control" v-bind:id="'cmb_'+n" v-on:change="getCodigoObraArray(parseInt(n))">
                                                <option value="0">Seleccionar</option>
                                                <option v-for="option in arrayComboCodigo" v-bind:value="option.codigo" >{{option.codigo}}</option>
                                            </select>
                                            <!-- <div v-if="reuCableExterno == null">
                                                <a style="color:red">Seleccionar por lo menos un nombre.</a>                                    
                                            </div>   -->
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div v-if="jsonDetalle.idTipo_obra==4">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Cantidad de hilos</label>
                                <input type="text" class="form-control" v-model="jsonDetalle.fo_tra_cant_hilos">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Cantidad de hilos habilitados</label>
                                <input type="text" class="form-control" v-model="jsonDetalle.fo_tra_cant_hilos_hab">
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="actualizarDetalle();"  class="btn btn-primary">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEditarMaterial" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content container-fluid" id="sisego">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" v-model="arrayEditarMaterial.itemplan" disabled/>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" v-model="arrayEditarMaterial.cuadrilla" disabled/>
                    </div>
                    <div class="form-group">
                        <textarea v-model="arrayEditarMaterial.observacion" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="actualizarMaterial();"  class="btn btn-primary">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEditarMaterialDetalle" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content container-fluid" id="sisego">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar</h4>
                </div>
                <div class="modal-body">
                <div v-for="row in arrayDetalleMaterial">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>{{row.suministro}}</label>
                        </div>
                        <div class="form-group col-md-2">
                            <input v-bind:id="'inputCantidadTrabajo'+row.id_ficha_tecnica_trabajo" v-model="row.cantidad" type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
                            <select  class="form-control" v-model="row.id_ficha_tecnica_tipo_trabajo" v-bind:id="'selectTrabajo'+row.id_ficha_tecnica_trabajo" >
                                <option value="">Seleccionar</option>
                                <option v-for="option in arrayCmbTipoFicha" v-bind:value="option.id_ficha_tecnica_tipo_trabajo">{{option.descripcion}}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <textarea v-bind:id="'inputComentarioTrabajo'+row.id_ficha_tecnica_trabajo" v-model="row.observacion" class="form-control"></textarea>
                        </div>
                    </div>    
                </div>
                <div class="modal-footer">
                    <button type="button" @click="actualizarMaterialDetalle();"  class="btn btn-primary">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>                      
</main>

<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url();?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url();?>public/dist/js/init.js"></script>

<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
  
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?php echo base_url();?>public/js/js_consultas/consultarFormulario.js?v=<?php echo time();?>"></script>  
<script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js?v=<?php echo time();?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/fancy/source/jquery.fancybox.js"></script>

<script src="<?php echo base_url();?>public/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/jszip/dist/jszip.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/pdfmake/build/pdfmake.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/pdfmake/build/vfs_fonts.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="<?php echo base_url();?>public/dist/js/export-table-data.js"></script>

  <script type="text/javascript">

</script>  