<style>.fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}</style>
<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">

<div class="page-wrapper">
<div class="container-fluid">
  <div class="row heading-lg">
          <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                   <ol class="breadcrumb">
              <li><a href="index.php">Inicio</a></li>
              <li><a href="#" class=""><span>Ejecuci&oacute;n de ItemPlan</span></a></li>
              <li><a href="#" class="active"><span><?php echo ($pagina == 'disenio_parcial') ?  'Dise&ntilde;o Parcial' :  $pagina;?></span></a></li>
            </ol>
                </div>
        
         <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2 ><?php echo ($pagina == 'disenio_parcial') ?  '' :  'PLAN OBRA : '; ?> BANDEJA GESTION OBRA</h2>
                    <br>
                </div>
       </div>
        
<div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default card-view">
<?php
if(@$_POST["pagina"]=="pendienteFiltro"||@$_GET["pagina"]=="pendiente" || @$_GET["pagina"]=="terminado_preliquidado"){
  $get = $_GET["pagina"];
?>

<form method="post" action="ejecucion?pagina=<?php echo $get?>">
  <input type="hidden" name="pagina" value="pendienteFiltro">
              <div class="col-md-3">
                                  <div class="form-group">
                                    <label class="control-label mb-10">ItemPlan</label>
                                    <input type="text" name="itemplan" class="form-control" placeholder="ItemPlan" value="<?php if(@$_POST["itemplan"]){ echo $_POST["itemplan"];}?>">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label class="control-label mb-10">Proyecto</label>
                                    <select onchange="getSubProyecto();" class="form-control select2" name="proyecto" id="proyecto">
                                      <option value="0">Seleccionar Proyecto</option>
                                      <?php echo $proyecto;?>
                                    </select>
                                  </div>
                                </div> 
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label class="control-label mb-10">SubProyecto</label>
                                    <select name="subproyecto" class="form-control select2" id="subproyecto">
                                      <option value="0">Seleccionar SubProyecto</option>
                                      <?php echo $subproyecto;?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label class="control-label mb-10">Fase</label>
                                    <select name="selectFase" class="form-control select2" id="selectFase">
                                      <option value="0">Seleccionar Fase</option>
                                      <?php echo $fase;?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label class="control-label mb-10">Indicador</label>
                                    <input name="indicador" type="text" class="form-control" placeholder="Indicador" value="<?php if(@$_POST["indicador"]){ echo $_POST["indicador"];}?>">
                                  </div>
                                </div> 
              <div class="col-md-offset-9 col-md-3">
                                      <button type="submit" class="btn btn-success  mr-10">Filtrar</button>
                                      
                                    </div></form><?php }?>       
              <div class="panel-wrapper collapse in">
                <div class="panel-body">
                  <div class="table-wrap">
                    <div class="table-responsive" style="width:100%">
                    <div id="contTablaPenTer" style="display:none">
                      <?php 
                      echo $tabla;
                      ?>
                      </div>
                    </div>
                  </div>
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
    include('application/views/vf_formulario/v_sisego_por_fuera.php');
    ?>
    
        <div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">??0??7Desea Pasar a pre-liquidado?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAdjudica" onclick="cambiarEstado();"  class="btn btn-primary">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                </div>
            </div>
        </div> 

        
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
            <div class="modal-content" >
              
            </div>
          </div>
        </div>


<div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalPorcentaje"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div id="modalContPorcentaje">
      </div>      
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalTrunca" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">MOTIVO</h3>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <select id="cmbMotivoHtml" class="form-control">   
          </select>
        </div>
        <div class="form-group">
          <textarea class="form-control" id="motivoTrunco"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button class="btn btn-success" onclick="cambiarEstadoObra()">Aceptar</button>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalParalizacion" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">PARALIZACI&Oacute;N</h3>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <select id="cmbParalizacionHtml" class="form-control" onchange="selectMotivo();">   
          </select>
        </div>
        <div class="form-group">
          <label>Comentario</label>
          <textarea class="form-control" id="comentarioParalizacion"></textarea>
        </div>
        <div class="form-group" id="evidenciaParalizacion" style="display:none">
          <label>Evidencia</label>
          <div id="dropzoneParalizacion" class="dropzone" >
                      
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button class="btn btn-success" id="btnEvidenciaParalizacion" style="display:none;" >Aceptar</button>
        <button class="btn btn-success" id="btnInsertarParalizacion"style="display:none;" onclick="insertParalizacion();">Aceptar</button>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="verMotivo" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Motivo Paralizaci&oacute;n</h3>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Usuario o Correo</label>
          <input class="form-control" id="txtUsuario" disabled>
        </div>
        <div class="form-group">
          <label>Motivo</label>
          <textarea class="form-control" id="txtMotivo" disabled></textarea>
        </div>
        <div class="form-group">
          <label>Fecha registro</label>
          <input class="form-control" id="txtFecha" disabled>
        </div>
        <div class="form-group">
          <label>Origen</label>
          <input class="form-control" id="txtOrigen" disabled>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!-- <button class="btn btn-success" onclick="cambiarEstadoObra()">Aceptar</button> -->
      </div>      
    </div>
  </div>
</div>


<!--11-09-2018-->
<div class="modal fade" aria-labelledby="myLargeModalLabel" role="dialog" id="modalEntLic" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">LISTA DE ENTIDADES </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="content" class="modal-body">
                <form id="formEnt" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div class="form-group form-group--float col-sm-12 table-responsive" id="divTablaEntLic">
                               
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!---->

<div id="modalInfo" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background:red">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color:white">Alerta!</h4>
                <h5 class="modal-title" style="color:white">Informaci&oacute;n </h5>
            </div>
            <div class="modal-body container-fluid">
                <div class="col-md-12 form-group">
                    <a>Estimados Usuarios,</a>
                </div>
                <div class="col-md-12 form-group">
                  <a>Se informa:</a>
                </div>
                <div class="col-md-12 form-group">
                    <a>Con la finalidad de agilizar la gesti&oacute;n se comunica que se han unificado la bandeja de Gesti&oacute;n de obras en la operaci&oacute;n a trav&eacute;s de la Opci&oacute;n "Bandeja Gesti&oacute;n Obra". 
                    </a>
                </div>
                <div class="col-md-12 form-group">
                  <a>Muchas gracias por su atenci&oacute;n.</a>
                </div>    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default boton-acepto" data-dismiss="modal">cerrar</button>
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
                <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de cancelar esta obra?</h5>
            </div>
            <div class="modal-body">
                <div class="form-group"> 
                    <select id="cmbMotivoCancelacion" name="responsable" class="select2 form-control">
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">OBSERVACION</label>
                    <input id="txtObservacion" type="text" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success boton-acepto" onclick="cancelarItemplan();">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" aria-labelledby="myLargeModalLabel" role="dialog" id="modalReembolso" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModalReem" style="margin: auto;font-weight: bold;" class="modal-title">LISTA DE COMPROBANTES </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="content" class="modal-body">
                <form id="formReem" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div class="form-group form-group--float col-sm-12 table-responsive" id="divTablaReembolso">
                               
                        </div>
                    </div>
                </form>
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
                        <button type="button" class="btn btn-success boton-acepto" onclick="deleteArchivoFoto2();">Aceptar</button>
                        <button type="button" class="btn btn-default boton-acepto" data-dismiss="modal">cancelar</button>
                    </div>
                </div>
            </div>
        </div>

 <!-- NUEVO modal de UM   -->
       
       <div class="modal fade" id="modalFormUM" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <img style="width: 100px; heigth:40px" src="<?php echo base_url();?>public/img/logo/tdp.png">
                      
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="forRegistrarFormUM" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row"> 
                           		<div class="form-group col-sm-3	 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">ITEMPLAN</label>  
                                    <input style="height: 33px;" disabled id="txtItemplan" name="txtItemplan" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CLIENTE</label>  
                                    <input style="height: 33px;" id="txtCliente" name="txtCliente" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-5 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">DIRECCION</label>  
                                    <input style="height: 33px;" id="txtDireccion" name="txtDireccion" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">FIBRAS EN CLIENTE</label>  
                                    <input style="height: 33px;" id="txtFibrasCliente" name="txtFibrasCliente" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">FECHA DE TERMINO</label>  
                                    <input style="height: 33px;" id="txtFecTermino" name="txtFecTermino" type="text" class="form-control date-picker">                                                                      
                                </div>  
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">NODO</label>  
                                    <input style="height: 33px;" id="txtNodo" name="txtNodo" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">UBICACION</label>  
                                    <input style="height: 33px;" id="txtUbicacion" name="txtUbicacion" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">NUM. ODF</label>  
                                    <input style="height: 33px;" id="txtNumODF" name="txtNumODF" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">BANDEJA DE CONECTORES</label>  
                                    <input style="height: 33px;" id="txtBanConectores" name="txtBanConectores" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">FIBRAS</label>  
                                    <input style="height: 33px;" id="txtFibras" name="txtFibras" type="text" class="form-control">                                                                      
                                </div>   
                                <div class="form-group col-sm-12 col-xs-12">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PRUEBAS REFLECTOMETRICAS(PDF)</label>
                                    <input id="filePruebasRefle" name="filePruebasRefle" type="file" accept="application/pdf">
                                </div>
								<div class="form-group col-sm-12 col-xs-12">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PRUEBAS REFLECTOMETRICAS(PDF)</label>
                                    <input id="filePruebasRefle2" name="filePruebasRefle2" type="file" accept="application/pdf">
                                </div>								
                                 <div class="form-group col-sm-12 col-xs-12">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PERFIL(PDF)</label>  
                                    <input id="filePerfil" name="filePerfil" type="file" accept="application/pdf">
                                </div>  
                              
                                
                                <!-- 
                                 <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CAMARA</label>  
                                    <select style="height: 33px;width: 100%;" id="selectCamara" name="selectCamara" class="select2 selectForm">                                             
                                             <option value="SI">SI</option>
                                             <option value="NO">NO</option>                                              
                                   	</select>                                          
                                </div>
                                -->
                           </div>
                               <div class="row">     
                                <div id="mensajeForm"></div>  
                                <div class="form-group" style="text-align: right;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                        <button id="btnRegFormUM" type="submit" class="btn btn-primary">Save changes</button>                                    
                                    </div>
                                </div>                            
                            </div>
                        </form>    
                    </div>
            	</div>
            </div>
    	</div>
    	
    	
    	
    	
<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url();?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/init.js"></script>
<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>          
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script> 
<script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
<script src="<?php echo base_url();?>public/js/sinfix.js?v=<?php echo time();?>"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&callback=init" async defer></script>

<script type="text/javascript">
$(document).ready(function(){
    //modal('modalInfo');
    $('#contTablaPenTer').css('display', 'block');
    
    $('#txtFecTermino').flatpickr({
    	defaultDate: "today"});  
  });

<?php
if($pagina=="detalle_obra"){
?>
$(document).ready(function(){$("body").on("click","#localizar",function(){
if(!$("#porcentaje").val()){$('#modal1').modal();return false;}
if(!$("#conversacion").val()){$('#modal2').modal();return false;}
if($("#porcentaje").val()<=0){$('#modal3').modal();return false;}
if(parseInt($("#porcentaje").val())>100){$('#modal4').modal();return false;}
if(parseInt($("#porcentaje").val())<parseInt($("#porcentaje_a").val())){$('#modal5').modal();return false;}

	if(navigator&&navigator.geolocation)navigator.geolocation.getCurrentPosition(geo_success,geo_error);else return error("Permitir GeoLocalizaci\u00f3n."),!1})});
function geo_success(a){$.get("ajax",{pagina:"detalle_obra",coordenadas:a.coords.latitude+","+a.coords.longitude,conversacion:$("#conversacion").val(),id_planobra_actividad:$("#id_planobra_actividad").val(),fporcentaje:$("#porcentaje").val(),id_sub_actividad:$("#id_sub_actividad").val()},function(a){window.location.href=""})}function geo_error(a){1==a.code?alert("El usuario no quiere mostrar su localizaci\u00f3n."):2==a.code?alert("La informaci\u00f3n es innacessible."):3==a.code?alert("La petici\u00f3n ha durado demasiado tiempo."):alert("Se ha producido un error inesperado.")};
<?php
}
if($pagina=="pendiente"){
?>
$(document).ready(function(){
$("body").on("click",".termi_obra",function(){	
$this=$(this);	
if(confirm("Desea Terminar el Itemplan : "+$this.parent().parent().find("td").eq(1).html().trim())==true){
$.post("ejecucion?pagina=terminar&id="+$this.parent().parent().find("td").eq(1).html().trim(),{},function(){window.location.reload()})
}
return false;
})
$("body").on("click",".truncar_obra",function(){	
$this=$(this);	
if(confirm("Desea Truncar el Itemplan : "+$this.parent().parent().find("td").eq(1).html().trim())==true){
$.post("ejecucion?pagina=truncar&id="+$this.parent().parent().find("td").eq(1).html().trim(),{},function(){window.location.reload()})
}
return false;
})

$("body").on("click",".regresar_trunca",function(){
    $this=$(this);	
    if(confirm("Desea Regresar a Obra el Itemplan : "+$this.parent().parent().find("td").eq(1).html().trim())==true) {
        $.post("ejecucion?pagina=regresar_truncar&id="+$this.parent().parent().find("td").eq(1).html().trim(),{},function()
        {
            window.location.reload();
        })
}
    return false;
})	

$("#proyecto").change(function(){
	$.get("ajax",{"pagina":"listar_proyecto",id:$(this).val()},function(e){$("#subproyecto").html(e)});
})
})
<?php	
}
if($pagina=="obra_terminar"){
?>
$("body").on("click","#preliquidar",function(){
id=$(this).attr("title");    
$.post("ajax",{pagina:"preliquidar",sid:id},function(){
parent.$.fancybox.close();parent.location.reload();     
})
}) 
<?php
	}
if($pagina=="creartoro"||$pagina=="editartoro"){
?>
$("#cantidad").keyup(function(){
$("#total").val(parseFloat($("#cantidad").val())*parseFloat($("#precio").val()));
})
$("#precio").keyup(function(){
$("#total").val(parseFloat($("#cantidad").val())*parseFloat($("#precio").val()));
})
<?php
}
?>












var itemplanParalizacion = null;
var idMotivo   = null;
var comentario = null;
var toog2=0;
function selectMotivo() {
    idMotivo   = $('#cmbParalizacionHtml option:selected').val();
    if(idMotivo == 27 || idMotivo == 12) {
        $('#evidenciaParalizacion').css('display', 'block');
        $('#btnEvidenciaParalizacion').css('display', 'inline-block');
        $('#btnInsertarParalizacion').css('display', 'none');
    } else {
        $('#evidenciaParalizacion').css('display', 'none');
        $('#btnEvidenciaParalizacion').css('display', 'none');
        $('#btnInsertarParalizacion').css('display', 'inline-block');
    }  
}

function insertParalizacion() {
    // idMotivo   = $('#cmbParalizacionHtml option:selected').val();
    comentario = $('#comentarioParalizacion').val(); 
    motivo     = $('#cmbParalizacionHtml option:selected').text();

    if(itemplanParalizacion == ''|| itemplanParalizacion == null) {
        return;
    }

    if(idMotivo == '' || idMotivo == null || origenGlobal == '' || origenGlobal == null) {
        console.log("motivo u origen NULL");
        return;
    }
    
    $.ajax({
        type : 'POST',
        url  : 'insertParalizacion',
        data : { idMotivo   : idMotivo, //27,12
                 comentario : comentario,
                 motivo     : motivo,
                 itemplan   : itemplanParalizacion,
                 origen     : origenGlobal }      
    }).done(function(data) {            
        data = JSON.parse(data);
        if(data.error == 0) {
            mostrarNotificacion('success', "registro correcto", "correcto");
            modal('modalParalizacion');
            console.log(idMotivo);
            if(idMotivo != 27 && idMotivo != 12) {
                location.reload();
            }
            // if($('.dz-preview').html() == undefined) {
                
            // }
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });     
}

Dropzone.autoDiscover = false;
$("#dropzoneParalizacion").dropzone({
    url              : "insertFileParalizacion",
    type             : 'POST',
    addRemoveLinks   : true,
    autoProcessQueue : false,
    parallelUploads  : 30,
    maxFilesize      : 3,
    acceptedFiles: ".pdf,.xml,.xlsx,.docx,.zip",
    // params: {
    //        itemplan : itemplanParalizacion
    //   },
    dictResponseError: "Ha ocurrido un error en el server",
    
    complete: function(file){
        if(file.status == "success"){
            error=0;
        }
    },
    removedfile: function(file, serverFileName){
        var name = file.name;
        var element;
        (element = file.previewElement) != null ? 
        element.parentNode.removeChild(file.previewElement) : 
        false;
        toog2=toog2-1;		
    },
    init: function() {
        this.on("error", function(file, message) {
                alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no sera tomado en cuenta');
                return;
                //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser?? tomado en cuenta');
                error=1;
                // alert(message);
                this.removeFile(file); 
        });
            
        
        var submitButton = document.querySelector("#btnEvidenciaParalizacion");
        var myDropzone = this; 
        
        var concatEvi = '';
        submitButton.addEventListener("click", function() {
            insertParalizacion();	 
            myDropzone.processQueue();            
        });
        
        var concatEvi = '';
        this.on("addedfile", function() {		    	
            toog2=toog2+1;	
        });
        
        this.on('complete', function () {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
                if(error == 0){
                    console.log(this.getUploadingFiles());
                }	            
        
            }	        
        });
        
        this.on("queuecomplete", function (file) {
            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		

            if(error == 0){
                updateFileParalizacion();
            }
        });	
        }
    });

    function updateFileParalizacion() {
        $.ajax({
            type : 'POST',
            url  : 'updateFileParalizacion',
            data : { itemplan   : itemplanParalizacion }       
        }).done(function(data) {            
            data = JSON.parse(data);
            if(data.error == 0){ 
                location.reload();
            } else {
                mostrarNotificacion('error', data.msj, 'error');
            }
        });
    }

var origenGlobal = null
function openModalParalizacion(btn, origen) { 
    var flgMotivoParalizacion = 1;
    itemplanParalizacion = btn.data('itemplan');
    // console.log(drop.dropzone().maxFilesize);

    if(itemplanParalizacion == null || itemplanParalizacion == '') {
        return;
    }
    
    $.ajax({
        type : 'POST',
        url  : 'getCmbMotivo',
        data : { flgTipo  : flgMotivoParalizacion,
                 itemplan : itemplanParalizacion }
    }).done(function(data) {
        data = JSON.parse(data);
        origenGlobal = origen;
        console.log(origenGlobal);
        // console.log(dropzone.maxFilesize);
        var cmbMotivo ='<option value="">Seleccionar Motivo</option>';
        data.arrayMotivo.forEach(function(element){
            cmbMotivo+='<option value="'+element.idMotivo+'">'+element.motivoDesc+'</option>';
        });
        $('#cmbParalizacionHtml').html(cmbMotivo);
        //insertParalaizacion(itemplanParalizacion); 
        $('.dz-message').html('<span>Subir evidencia</span>');
        modal('modalParalizacion');
    });
    
    
}


  var itemplanGlobal     = null;
  var idEstadoPlanGlobal = null;
  function openModalAlert(btn) {
      itemplanGlobal     = btn.data('itemplan');
      idEstadoPlanGlobal = btn.data('id_estadoplan');

      $.ajax({
          type : 'POST',
          url  : 'getMotivoCancelacion'
      }).done(function(data){
          data = JSON.parse(data);
          $('#cmbMotivoCancelacion').html(data.comboMotivo);
          modal('modalAlerta');
      });
  }


  function cancelarItemplan() {
      var idMotivo = $('#cmbMotivoCancelacion  option:selected').val();
      var motivoDesc = $('#cmbMotivoCancelacion option:selected').text();
      var comentario = $('#txtObservacion').val();

      $.ajax({
          type : 'POST',
          url  : 'cancelarItemplanPendiente',
          data : { itemplan     : itemplanGlobal,
                   idEstadoPlan : idEstadoPlanGlobal,
                   motivoDesc   : motivoDesc,
                   idMotivo     : idMotivo,
                   comentario   : comentario }
      }).done(function(data){
          data = JSON.parse(data);
          if(data.error == 0) {
              modal('modalAlerta');
              mostrarNotificacion('success', 'itemplan cancelado correctamente', 'correcto');
              window.location.reload();
              $('#contTablaSolicitud').html(data.tablaSolicitudCancelados);
          } else {
              mostrarNotificacion('error', data.msj, data.msj2);
          }
      });
  }
  
  /***NUEVO FORMULARIO UM 18.06.2019 CZAVALACAS***/
  function openFormUM(component) {
	  var itemplan = $(component).data('item_plan');
		console.log(itemplan);
		$('#btnRegFormUM').attr('data-itemplan', itemplan);
		$('#txtItemplan').val(itemplan);
		$('#modalFormUM').modal('toggle');
	}

  $('#modalFormUM')
  .on('hide.bs.modal', function() {
	  $('#forRegistrarFormUM').bootstrapValidator('resetForm', true); 
  })

  $('#forRegistrarFormUM')
  .bootstrapValidator({
  container: '#mensajeForm',
  feedbackIcons: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
  },
  excluded: ':disabled',
  fields: {
      txtCliente: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe ingresar el Cliente.</p>'
              }
          }
      },
      txtDireccion: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe ingresar Direccion.</p>'
              }
          }
      },
      txtFibrasCliente: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe ingresar Fibras en Cliente.</p>'
              }
          }
      },
      txtFecTermino: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe seleccionar una fecha de Termino.</p>'
              }
          }
      },
      txtNodo: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe ingresar el Nodo.</p>'
              }
          }
      },
      txtUbicacion: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe ingresar la Ubicacion.</p>'
              }
          }
      },
      txtNumODF: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe ingresar Numero de ODF.</p>'
              }
          }
      },
      txtBanConectores: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe ingresar Bandeja de Conectores.</p>'
              }
          }
      },
      txtFibras: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe ingresar Fibras.</p>'
              }
          }
      },
      filePruebasRefle: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe subir Docuemnto PRUEBAS REFLECTOMETRICAS.</p>'
              }
          }
      },
      filePerfil: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe subir Docuemnto PERFIL.</p>'
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
      
      $.each(params, function(i, val) {
          formData.append(val.name, val.value);
      });
      
      var itemplan	=	$('#btnRegFormUM').attr('data-itemplan');
      formData.append('itemplan', itemplan);

      var input1File = document.getElementById('filePruebasRefle');
      var file1 = input1File.files[0];
      formData.append('filePruebas', file1);

      var input2File = document.getElementById('filePerfil');
      var file2 = input2File.files[0];
      formData.append('filePerfil', file2);
      
	  var input3File = document.getElementById('filePruebasRefle2');
      var file3 = input3File.files[0];
      formData.append('filePruebas2', file3);
	  
      swal({
          title: 'Est&aacute seguro registrar la DJ de UM?',
          text: 'Asegurese de que la informacion llenada sea la correta.',
          type: 'warning',
          showCancelButton: true,
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: 'Si, guardar los datos!',
          cancelButtonClass: 'btn btn-secondary',
          allowOutsideClick: false
      }).then(function(){
          console.log('fuck you');
          
          $.ajax({
              data: formData,
              url: "saveFormUM",
              cache: false,
              contentType: false,
              processData: false,
              type: 'POST'
            })
            .done(function(data) {
               var data	=	JSON.parse(data);
               if(data.error == 0){   
                   console.log('YES!!');
                   $('#modalFormUM').modal('toggle');
                   swal({
                	   title: 'Se registro correctamente!',
                       text: 'Asegurese de validar la informacion!',
                       type: 'success',
                       buttonsStyling: false,
                       confirmButtonClass: 'btn btn-primary',
                       confirmButtonText: 'OK!',
                       allowOutsideClick: false
                   }).then(function(){
                	   location.reload();
                   }, function(dismiss) {
                	   location.reload();
                   });
               }else if(data.error == 1){     				
                   mostrarNotificacion('error','Error, refresque la pagina y vuelva a intentarlo!');
               }
            });
      }, function(dismiss) {
          // dismiss can be "cancel" | "close" | "outside"
              $('#forRegistrarFormUM').bootstrapValidator('resetForm', true); 
      });
  });
</script>




