<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">

<div class="row" style="background-color:#eaf1f0">
<div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">Situaci&oacute;n de ItemPlan : <?php echo $_GET["id"];?> / <?php echo $nombre_proyecto;?></h6>
                </div>
                <div class="clearfix"></div>
              </div>
<div id="porcen" class="table-responsive" data-scroll-index="1"></div>              
<div id="contEstaciones">
  <?php
  echo $estaciones;
  ?>
</div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Actualizaci&oacute;n de Porcentaje</h4>
      </div>
      <div class="modal-body">
        <p>Porcentaje Editado Correctamente</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default boton-acepto" data-dismiss="modal" onclick="aceptarPorcentaje();">Aceptar</button>
      </div>
    </div>
  </div>
</div>


  <div class="modal fade" id="modalSubirFoto" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="tituloModal"></h5>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
              <!-- <span aria-hidden="true">&times;</span>
              </button> -->
          </div>
          <div class="modal-body">
            <div id="buttonEvidencia" class="form-group"></div>        
            <div class="col-6">
              <div id="dropzone4" class="dropzone" >
                      
              </div>
              <hr style="border:1;">
              <!-- <button  id="btnAddNewIMGyPdf" class="btn btn-primary" style="background-color:#FFC107;float:right;margin-top:10px" name="btnAddNewIMG">Guardar</button> -->
              
                  <!-- <label>Subir Archivo</label> -->
                  <!-- <div id="dropzone4" class="dropzone" >
                      </div>
                          <hr style="border:1;">
                          <button onclick="cerrarModalEditEvi();" type="submit" id="btnAddNewIMGyPdf" class="btn btn-primary" style="background-color:#FFC107;float:right;margin-top:10px" name="btnAddNewIMG">Guardar</button>
                      <div id="contTablaEvi" style="padding-top: 60px;">
                  </div> -->
              </div>
            </div>
          <div class="modal-footer">
              <button type="button" id="btnAceptarSubirFoto"   class="btn btn-primary">Aceptar</button>
              <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          </div>
      </div>
  </div> 


<div class="modal fade" id="modalSeleccionarSerie" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">SELECCIONAR SERIE</h5>
      </div>    
          <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
          <!-- <span aria-hidden="true">&times;</span>
          </button> -->
          <div class="modal-body">
            <div class="col-6" id="cmbSerieMostrar">
              
            </div>
       
            <div class="modal-footer">
              <button type="button" id="btnIngresarSerie" onclick="ingresarSerieTroba();"   class="btn btn-primary">Aceptar</button>
              <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>  
      </div>
    </div>
  </div>
  
    <div id="obrap">
    <?php
        include('application/views/vf_formulario/v_obra_publica.php');    
    ?>
    </div>
    <?php
//MODAL SISEGO
    include('application/views/vf_formulario/v_sisego.php');
    ?>
<div id="modalFormCuadrilla" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">REGISTRO DE CUADRILLA</h4>
            </div> 
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label>{{objData.inputNombreCuadrilla.label}}</label>
                        <input type="text" class="form-control" v-model="objData.inputNombreCuadrilla.modelNombre">
                    </div>
                    <div class="form-group">
                        <label>{{objData.cmbZonal.label}}</label>
                        <select class="form-control" v-model="objData.cmbZonal.modelZonal">
                            <option value="0">seleccionar zonal</option>
                            <option v-for="row in arrayZonal" v-bind:value="row.idZonal">{{row.zona}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{objData.cmbEcc.label}}</label>
                        <select class="form-control" v-model="objData.cmbEcc.modelEcc">
                            <option value="0">seleccionar emp. colaboradora</option>
                            <option v-for="obj in arrayEcc" v-bind:value="obj.idEmpresaColab">{{obj.empresaColabDesc}}</option>
                        </select>
                    </div>
                    <!-- <div class="form-group">
                        <label>{{objData.cmbUsuario.label}}</label>
                        <select class="form-control" v-model="objData.cmbUsuario.modelUsuario">
                            <option value="0">seleccionar emp. colaboradora</option>
                            <option v-for="obj2 in arrayUsuarioCua" v-bind:value="obj2.id_usuario">{{obj2.nombre}}</option>
                        </select>
                        <a style="color:green">Se debe seleccionar el usuario que será la cuadrilla</a>
                    </div> -->
                </form>
            </div>
            <div class="modal-footer">
                <button  class="btn btn-default boton-acepto"  @click="registrarCuadrilla">Aceptar</button>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="modalSubirEvidencia" tabindex="-1" role="dialog" style="overflow-y: scroll;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal"></h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                    <!-- <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body">
                    <div id="buttonFotos" class="form-group"></div>
                    <div>
                        <h5>CTO en Postes(Fotos)</h5>
                        <div id="dropzone5" class="dropzone" >
                            
                        </div>
                        <hr style="border:1;">
                    </div>
                    <div>
                        <h5>Pruebas Reflectométricas(PDF)</h5>
                        <div id="dropzone6" class="dropzone" >
                            
                        </div>
                        <hr style="border:1;">
                    </div>
                    <div>
                        <h5>Perfil(PDF)</h5>
                        <div id="dropzone7" class="dropzone" >
                            
                        </div>
                        <hr style="border:1;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSubirEvidenciaUM" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal"></h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                    <!-- <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body">
                    <div>
                        <h5>Gabinete(Fotos)</h5>
                        <div id="dropzone8" class="dropzone" >
                            
                        </div>
                        <hr style="border:1;">
                    </div>
                    <div>
                        <h5>Pruebas Reflectométricas Cliente(PDF)</h5>
                        <div id="dropzone9" class="dropzone" >
                            
                        </div>
                        <hr style="border:1;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>  
  				                    
        <div class="modal fade" id="modalKitMaterial" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <img style="width: 100px; heigth:40px" src="<?php echo base_url();?>public/img/logo/tdp.png">
                      
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="forRegistrarKitMate" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row"> 
                           		<div class="form-group col-sm-6	 col-xs-12">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">DIRECCION</label>  
                                    <input style="height: 33px;" id="txtDireccion" name="txtDireccion" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-2 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">NUMERO</label>  
                                    <input style="height: 33px;" id="txtNumero" name="txtNumero" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-2 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left"># PISOS</label>  
                                    <input style="height: 33px;" id="txtPisos" name="txtPisos" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-2 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left"># DPTOS</label>  
                                    <input style="height: 33px;" id="txtDepartamentos" name="txtDepartamentos" type="text" class="form-control">                                                                      
                                </div>     

                                <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">TIPO TRABAJO</label>  
                                    <select style="height: 33px;width: 100%;" id="selectTipoTrabajo" name="selectTipoTrabajo" class="select2 selectForm">
                                             <option value="1">SUBTERRANEO</option>
                                             <option value="2">AEREO</option>
                                   	</select>
                                </div>
                                 <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CTO</label>  
                                    <select style="height: 33px;width: 100%;" id="selectInstala" name="selectInstala" class="select2 selectForm">                                             
                                             <option value="SI">SI</option>
                                             <option value="NO">NO</option>                                              
                                   	</select>                                          
                                </div>
                                 <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CAMARA</label>  
                                    <select style="height: 33px;width: 100%;" id="selectCamara" name="selectCamara" class="select2 selectForm">                                             
                                             <option value="SI">SI</option>
                                             <option value="NO">NO</option>                                              
                                   	</select>                                          
                                </div>
                           </div>
                          
                           <div class="row">                           	
                               	<div class="form-group col-sm-12 table-responsive">                             	
                                		<table style="color:black;font-size: 10px;font-weight: bold;margin-bottom: inherit;" id="tabla_trabajos" border="1">
                                            <thead>
                                                <tr>
                                                    <th style="width: 15%;" >CODIGO</th>
                                                    <th style="width: 49%;" >MATERIAL</th>                                                      
                                                    <th style="width: 10%;text-align: center;" >TOTAL</th>                                     
                                                </tr>
                                            </thead>
                                            <tbody id="bodyTable">
                                       		
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                               <div class="row">     
                                <div id="mensajeForm"></div>  
                                <div class="form-group" style="text-align: right;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                        <button id="btnRegKitMat" type="submit" class="btn btn-primary">Save changes</button>                                    
                                    </div>
                                </div>                            
                            </div>
                        </form>    
                    </div>
            	</div>
            </div>
    	</div>
    	<!-- --------------------------------------------------------FIN DEL MODAL 2------------------------------------------------------------------------ -->   			

        <div id="modalConsultaPTR" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">EDITAR PTR</h4>
                    </div>
                    <div class="modal-body">
                        <div id="contTablaPTR">
                        </div>
                    </div>
                </div>
            </div>
        </div>

 <div id="modalEditarPTR" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">EDITAR PTR</h4>
                    </div>
                    <div class="modal-body">
                        <!-- <div class="form-group">
                            <input type="text" class="form-control" />
                            <button class="btn btn-info">buscar</button>
                        </div> -->
                              
                        <div class="panel panel-primary">
                            <div class="panel-heading">Agregar Actividades</div>
                            <div class="panel-body">
                                <div id="contTablaActividad" class="table-responsive">
                                    
                                </div>
                            </div>    
                        </div>
                      
                        <div class="panel panel-primary">
                            <div class="panel-heading">PTRs Asociadas</div>
                                <div class="panel-body">
                                    <div id="contEditarPTR" class="table-responsive">
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <button id="btnActualizarPtr" type="button" class="btn btn-success boton-acepto"  onclick="actualizarPtr();">Aceptar</button>                                
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>

        <div id="modalAlerta" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="background:red">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="color:white">Alerta!</h4>
                        <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                    </div>
                    <div class="modal-body">
                        <a>Al aceptar, se borrarán las cantidades ingresadas.</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success boton-acepto" onclick="aceptarPorcentaje();">Aceptar</button>
                        <button type="button" class="btn btn-default boton-acepto" data-dismiss="modal">cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="modalAlertaInfo" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="background:red">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="color:white">Alerta!</h4>
                        <h5 class="modal-title" style="color:white">Informaci&oacute;n para subir evidencia</h5>
                    </div>
                    <div class="modal-body container-fluid">
                        <div class="col-md-12">
                            <a>Estimados usuarios se les comunica que se está restringiendo el tamaño de los archivos cargados como evidencia en todos los sub-proyectos:</a>
                        </div>
                        <div class="col-md-12">
                            <a>Foto: 2 como máximo (c/u debe pesar 500kb; formato: JPG)
                            Archivo Pruebas: 500kb (formato: PDF)
                            Perfil: 500kb (formato: PDF, Excel o Word)
                            Gracias por su apoyo y atención.</a>
                        </div>    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default boton-acepto" data-dismiss="modal">cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalAlertaDelete" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="background:red">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="color:white">Alerta!</h4>
                        <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                    </div>
                    <div class="modal-body">
                        <a>Al aceptar, se eliminar&aacute; el archivo o foto seleccionada.</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success boton-acepto" onclick="deleteArchivoFoto();">Aceptar</button>
                        <button type="button" class="btn btn-default boton-acepto" data-dismiss="modal">cancelar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- MODAL LIQUIDACION OC -->
        
        <div class="modal fade" id="modalLiqOC" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <img style="width: 100px; heigth:40px" src="<?php echo base_url();?>public/img/logo/tdp.png">
                      
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="forRegistrarLiqOC" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row">                           	
                               	<div class="form-group col-sm-12 table-responsive">                             	
                                		<table style="color:black;font-size: 10px;font-weight: bold;margin-bottom: inherit;" id="tabla_trabajos" border="1">
                                            <thead>
                                                <tr>
                                               <!-- <th style="width: 15%;" >NUM</th>   -->
                                                    <th style="width: 49%;" >PARTIDA</th>                                                      
                                                    <th style="width: 10%;text-align: center;" >PRECIO</th>      
                                                    <th style="width: 10%;text-align: center;" >CANTIDAD</th>   
                                                    <th style="width: 10%;text-align: center;" >TOTAL</th>                                  
                                                </tr>
                                            </thead>
                                            <tbody id="bodyTableLiq">
                                       		   
                                            </tbody>
                                        </table>
                                </div>                                
                            </div>
                               <div class="row">     
                                <div id="mensajeFormLiq"></div>  
                                <div class="form-group" style="text-align: right;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                        <button id="btnLiqOC" type="submit" class="btn btn-primary">Save changes</button>                                    
                                    </div>
                                </div>                            
                            </div>
                        </form>    
                    </div>
            	</div>
            </div>
    	</div>
    	<!-- ----------------------------------------- -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>

<script>
var screenInit = 1;//FROM PORCENTAJES
$(document).ready(function() {
	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });
	});
	function sum_valuues(id){
		var cantSub  = $('#inCantSubte'+id).val();
		var cantAreo = $('#inCantAreo'+id).val();
		$('#total'+id).val(parseFloat(cantSub) + parseFloat(cantAreo));
	}
</script>