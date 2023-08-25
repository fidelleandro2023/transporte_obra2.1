<button type="button" class="btn btn-primary" id="btnModalFormObrasPub" data-toggle="modal" data-target="#modalFormObrasPub" style="display: none;"></button>
<div id="modalFormObrasPub" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FORMULARIO OBRAS P&Uacute;BLICAS</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="form-group col-sm-6">
            <label>Itemplan</label>
            <input id="inputItemplan" type="text" class="form-control" v-model="jsonFormObrasP.itemplan" disabled/>
          </div>
          <!-- <div class="form-group col-sm-6">
            <label>PTR</label>
            <input type="text" class="form-control" v-model="jsonFormObrasP.ptr"/>
          </div> -->
          <div class="form-group col-sm-6">
            <label>Observaciones</label>
            <textarea class="form-control" v-model="jsonFormObrasP.observacion"></textarea>
          </div>
          <!-- <div class="form-group col-sm-6">
            <label>Fecha</label>
            <input type="date" class="form-control" v-model="jsonFormObrasP.fecha_form"/>
          </div>  -->
        </div>
        
        <div class="panel-group">
          <div class="panel panel-success">
            <!-- <div class="panel-heading"></div> -->
            <div class="panel-body">
              <div class="table-responsive panel">
                <table class="table">
                  <thead>
                    <th>Canalizaci&oacute;n KM</th>
                    <th>C&aacute;maras Und</th>
                    <th>C(postes)</th>
                    <th>Ma(postes)</th>
                    <th>Km-Ducto</th>
                    <th>Km Tritubo</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.canalizacion_km"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.camaras_und" style="width:120%"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.c_postes"  style="width:120%"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.ma_postes" style="width:120%"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_ducto" style="width:120%"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_tritubo" style="width:120%"/>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>  
            </div>
          </div>
        </div>

        <div class="panel-group">
          <div class="panel panel-success">
            <!-- <div class="panel-heading"></div> -->
            <div class="panel-body">
              <div class="table-responsive panel">
                <table class="table">
                  <thead>
                    <th>KM-Par Cobre</th>
                    <th>KM-Cable Coax</th>
                    <th>KM-FO FO</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_par_cobre"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_cable_coax"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_fo"/>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>  
            </div>
          </div>
        </div>
        <div class="panel-group">
            <div class="panel panel-success">
                <div class="panel-body">
                    <div class="table-responsive panel">
                        <table class="table">
                            <tr>
                                <td>
                                    <label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PRUEBAS REFLECTOMETRICAS(PDF)</label>
                                    <input id="filePruebasRefleEE" name="filePruebasRefleEE" type="file" accept="application/pdf">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PERFIL(PDF)</label>  
                                    <input id="filePerfilEE" name="filePerfilEE" type="file" accept="application/pdf">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
          <input type="button" class="btn btn-success boton-acepto" value="Aceptar" @click="registrarFormObraPub">        
      </div>
    </div>
  </div>
</div>


