<div id="modalFormulario" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" id="sisego">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORMULARIO SISEGO</h4>
        <div v-if="flg_from == 2">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a data-toggle="tab" href="#formulario" @click="openTab(1)">Formulario</a></li>
                <li role="presentation"><a data-toggle="tab" href="#material" @click="openTab(2)">Material</a>
            </ul>
        </div>
      </div>
      <div class="modal-body">
        <div v-bind:class="{ 'tab-content' : (flg_from == 2) ? true : false}">
            <div id="formulario" v-bind:class="{ 'tab-pane fade in active' : (flg_from == 2) ? true : false }"> 
                <div class="form-group">
                        <label>&iquest;Con licencia?</label>
                        <select v-model="licenciaAfirm" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>          
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Seleccionar Tipo de obra</label>
                        <select id="cmbTipoObra" v-model="cmbTipoObra" class="form-control" v-on:change="getComboCodigo">
                            <option value="0">Seleccionar</option>
                            <option v-for="option in arrayComboTipo" v-bind:value="option.idtipo_obra" >{{ option.descripcion }}</option>
                        </select>
                    </div>
                    <div v-if="cmbTipoObra == 1">
                        <div class="row form-group">
                            <div class="col-sm-6 form-group">
                            <label>Nombre CTO/NAP</label>
                            <input type="text" class="form-control" v-model="nombreCtoNap">
                            </div>
                            <div class="col-sm-3 form-group">
                            <label># Troncal</label>
                            <input type="text" class="form-control" v-model="nroTroncal">
                            </div>
                            <div class="col-sm-3 form-group">
                                <label>Cant. hilos hab.</label>
                                <input type="number" class="form-control" v-model="cantidadHilosNap">
                                </div>
                                <div class="col-sm-3 form-group">
                                <label>Nodo</label>
                                <input type="text" class="form-control" v-model="nodo">
                            </div>
                             <div class="col-sm-3 form-group">
                                <label>Coordenada X</label>
                                <input id="coor_x" type="text" class="form-control">
                            </div>
                            <div class="col-sm-3 form-group">
                                <label>Coordenada Y</label>
                                <input id="coor_y" type="text" class="form-control">
                                <button class="btn-info" @click="openModalGeo">Abrir geolocalizacion</button>
                            </div>   
                            <div class="col-sm-3 form-group">
                                <label>Ubicaci&oacute;n</label>
                                <select id="cmbUbicacion" class="form-control" v-model="selectedUbica">
                                    <option value="0">Seleccionar</option>     
                                    <option value="1">Poste</option>   
                                    <option value="2">C&aacute;mara</option>   
                                    <option value="3">Edificio del cliente</option>   
                                    <option value="4">Centro Comercial</option>                       
                                </select>
                            </div>  
                        </div>
                        <div v-if="selectedUbica == 3">
                            <div class="col-sm-6 form-group">
                                <label>Piso(ubicaci&oacute;n)</label>
                                <input type="text" class="form-control" v-model="piso">
                            </div>
                        </div>
                        <div v-if="selectedUbica == 4">
                            <div class="col-sm-6 form-group">
                                <label>Zona(ubicaci&oacute;n)</label>
                                <input type="text" class="form-control" v-model="zona">
                            </div>                        
                        </div>
                    </div>

                        <div v-if="cmbTipoObra == 2">
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label>Cantidad de hilos</label>
                                    <input type="text" class="form-control" v-model="foOscuCantHilos">
                                </div>
                                <div class="col-sm-6 form-group">
                                    <label>Cantidad de Nodos</label>
                                    <input type="text" class="form-control" v-model="foOscuCantNodo">
                                </div>
                                <div class="row" v-if="foOscuCantNodo != ''">
                                    <div class="form-group" v-if="foOscuCantNodo > 0 " v-for="n in parseInt(foOscuCantNodo)">
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

                    <div v-if="cmbTipoObra==3">
                        <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Reubicaci&oacute;n de cable externo</label>
                            <input type="text" class="form-control" v-model="reuCableExterno">
                            <div v-if="reuCableExterno == null">
                                <a style="color:red">Reubicaci&oacute;n de cable externo</a>                                    
                            </div>     
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Reubicaci&oacute;n de cable interno</label>
                            <input type="text" class="form-control" v-model="reuCableInterno">
                            <div v-if="reuCableInterno == null">
                                <a style="color:red">Reubicaci&oacute;n de cable interno</a>                                    
                            </div>                      
                        </div>
                        </div>  
                    </div>

                    <div v-if="cmbTipoObra==4">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Cantidad de hilos</label>
                                <input type="text" class="form-control" v-model="foTradiCantHilo">
                                <div v-if="foTradiCantHilo == null">
                                    <a style="color:red">Ingresar cantidad hilos</a>                                    
                                </div>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Cantidad de hilos habilitados</label>
                                <input type="text" class="form-control" v-model="foTraCantHiloHab">
                                <div v-if="foTraCantHiloHab == null">
                                    <a style="color:red">Ingresar cantidad hilos habilitados</a>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="cmbTipoObra!=0">
                        <div class="row">
                            <div class="col-sm-2 form-group">
                                <label># ODF</label>
                                <input  v-model="nroODF" type="text" class="form-control">
                            </div>
                            <div class="col-sm-2 form-group">
                                <label>Sala</label>
                                <input v-model="sala"  type="text" class="form-control">
                            </div>                
                            <div class="col-sm-2 form-group">
                                <label>Piso</label>
                                <input v-model="pisoGlobal" type="text" class="form-control">
                            </div> 
                            <div class="col-sm-3 form-group">
                                <label>Bandeja Core</label>
                                <input v-model="bandeja" type="text" class="form-control">
                            </div>  
                            <div class="col-sm-2 form-group">
                                <label># Hilo</label>
                                <input v-model="nroHilo" type="text" class="form-control">
                            </div> 
                        </div>
                    </div>
            </div>
            <!-- <div v-if="flg_from == 2"> -->
                <div id="material" class="tab-pane fade" style="display:none">
                    <div v-for="row in arrayFichaTecnica">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>{{row.descripcion}}</label>
                            </div>
                            <div class="form-group col-md-3">
                                <input v-bind:id="'inputCantidadTrabajo'+row.id_ficha_tecnica_trabajo" type="text" value="0" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <select  class="form-control" v-bind:id="'selectTrabajo'+row.id_ficha_tecnica_trabajo">
                                    <option value="">Seleccionar</option>
                                    <option v-for="option in arrayCmbTipoFicha" v-bind:value="option.id_ficha_tecnica_tipo_trabajo">{{option.descripcion}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <textarea v-bind:id="'inputComentarioTrabajo'+row.id_ficha_tecnica_trabajo" class="form-control"></textarea>
                            </div>
                        </div>    
                    </div>
                    <div>
                        <label>Observaciones</label>
                        <textarea class="form-control" v-model="observacionGenerar"></textarea>
                    </div>
                </div>
            <!-- </div> -->
        </div>
      </div>
      <div class="modal-footer">
            <input id="btnRegistrarTrama" type="button" class="btn btn-success boton-acepto" value="Aceptar" @click="registrarTrama">        
            <!-- <input type="button" class="btn btn-default boton-acepto" value="Aceptar" @click="registrarFicha"> -->
      </div>
    </div>
  </div>
</div>


<div id="modalUbicacion" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content" id="sisego">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ubicaci&oacute;n</h4>
            </div>
            <div class="modal-body">
                <div style=" position: absolute;top: -20px;left: 35%;z-index: 5;background-color: #fff;padding: 5px;text-align: center;line-height: 25px;padding-left: 10px;">
                    <input type="text" id="search" onkeyup="searchDireccion()"> 
                </div>
                <div id="contenedor_mapa" style="height: 320px; position: relative; overflow: hidden;"></div>
            </div>
        </div>
    </div>
</div>        


                            