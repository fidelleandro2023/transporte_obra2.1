<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/fancy/source/jquery.fancybox.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bootstrap-3.3.6-dist/css/bootstrap.min.css?v=<?php echo time();?>" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link href="<?php echo base_url();?>public/dist/css/style_pqt.css" rel="stylesheet" type="text/css">
        <style>
            /* .fancybox-overlay{
                z-index: 2;
            }

            .fancybox-wrap{
                z-index: 2;
            } */

            @media (min-width: 768px) {
                .modal-xl {
                width: 90%;
                max-width:1200px;
                }
            }
        </style>
    </head>
<body>
	<main class="main">
	<section class="content content--full">
		<div class="content__inner">
			<div class="card">
				<div class="card-block">
					<div class="row" id="contTabla">
					   <!-- Tabla de OS de SIOM -->
					   <?php echo $htmlTabla ?>
					</div>
					 <div id="obrap">
                        <?php
                            include('application/views/vf_pqt_gestion_obra_pre_liquidado/v_obra_publica.php');    
                        ?>
                        </div>
                        <?php
                    //MODAL SISEGO
                        include('application/views/vf_pqt_gestion_obra_pre_liquidado/v_sisego_por_fuera.php');
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
                        
                        <button type="button" class="btn btn-primary" id="btnModalAlertaDelete" data-toggle="modal" data-target="#modalAlertaDelete" style="display: none;"></button>
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
       <button type="button" class="btn btn-primary" id="btnModalFormUM" data-toggle="modal" data-target="#modalFormUM" style="display: none;"></button>
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
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PERFIL(PDF)</label>  
                                    <input id="filePerfil" name="filePerfil" type="file" accept="application/pdf">
                                </div> 
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
                       
       <button type="button" class="btn btn-primary" id="btnModalSubirEvidencias" data-toggle="modal" data-target="#modalSubirEvidencias" style="display: none;"></button>
       <div class="modal fade" id="modalSubirEvidencias" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div align="right" style="padding-right: 15px;padding-top: 10px;"><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                    <div class="modal-header">
                        
                        <div>
                            <h5 id="headerSubirEvidencia">SUBIR EVIDENCIAS</h5>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form id="formRegistrarEvidencias" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row">
                                <div class="form-group col-sm-6 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PRUEBAS REFLECTOMETRICAS(PDF)</label>
                                    <input id="filePruebasRefleE" name="filePruebasRefleE" type="file" accept="application/pdf">
                                </div>  
                                 <div class="form-group col-sm-6 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PERFIL(PDF)</label>  
                                    <input id="filePerfilE" name="filePerfilE" type="file" accept="application/pdf">
                                </div>
                           </div>
                               <div align="center">     
                                <div id="mensajeFormE"></div>  
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                        <button id="btnRegEvidencias" type="submit" class="btn btn-primary">Save changes</button>                                    
                                    </div>
                                </div>                            
                            </div>
                        </form>    
                    </div>
            	</div>
            </div>
    	</div> 
				</div>
			</div>
		</div>
        
        <div class="modal fade" id="modalSubirFoto" tabindex="-1" z-index="10" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModal"></h5>
                    </div>
                    <div class="modal-body">
                        <div id="buttonEvidencia" class="form-group">
                        </div>        
                        <div class="col-md-12">
                            <div id="dropzone4" class="dropzone" >      
                            </div>
                            <hr style="border:1;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnAceptarSubirFoto"   class="btn btn-primary">Aceptar</button>
                        <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalPorcentaje" tabindex="-1" z-index="10" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModal">PORCENTAJE</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="contPorcentaje">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalSubirEvidenciaSinSiom" tabindex="-1" z-index="10" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModal"></h5>
                    </div>
                    <div class="modal-body">
                        <form id="formRegistrarEvidenciasSinSiom" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row">
                                <div class="form-group col-sm-6 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PRUEBAS REFLECTOMETRICAS(PDF)</label>
                                    <input id="filePruebasRefleESinSiom" name="filePruebasRefleESinSiom" type="file" accept="application/pdf">
                                </div>  
                                 <div class="form-group col-sm-6 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PERFIL(PDF)</label>  
                                    <input id="filePerfilESinSiom" name="filePerfilESinSiom" type="file" accept="application/pdf">
                                </div>
                           </div>
                               <div align="center">     
                                <div id="mensajeFormE"></div>  
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                        <button id="btnRegEvidencias" type="submit" class="btn btn-primary">Save changes</button>                                    
                                    </div>
                                </div>                            
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" role="dialog" id="modalPOs" data-backdrop="static" data-keyboard="false" tabindex="-1" style="overflow: scroll;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="titModalPO" style="margin: auto;font-weight: bold;" class="modal-title">POs de materiales </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist" id="contTabsPO">
                                
                            </ul>
                        </div><br><br>
                        <div class="tab-content" id="contBodyTabsPO">
                            

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="button" onclick="updateDetallePO()" id="btnUpdDet">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
	</section>
	</main>
	
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
<script src="<?php echo base_url(); ?>public/jquery.numeric/jquery.numeric-min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script> 
<script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>     
<script src="<?php echo base_url();?>public/js/sinfix_pqt.js?v=<?php echo time();?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/fancy/source/jquery.fancybox.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&callback=init" async defer></script>

<script type="text/javascript">
     
$(document).ready(function(){
    $('#txtFecTermino').flatpickr({
    	defaultDate: "today"});  
  });

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


  function openModalEvidencias(component) {
	  
	  $('#filePruebasRefleE').val(null);
	  $('#filePerfilE').val(null);
	  var itemplan = $(component).data('item_plan');
	  var idEstacion = $(component).data('id_estacion');
	  var estacion = $(component).data('estacion');
	  console.log(itemplan + ' ' + idEstacion + ' ' + estacion);
	  
	  $('#btnRegEvidencias').attr('data-itemplan', itemplan);
	  $('#btnRegEvidencias').attr('data-id_estacion', idEstacion);
	  $('#btnRegEvidencias').attr('data-desc_estacion', estacion);
	  
	  $('#headerSubirEvidencia').text('SUBIR EVIDENCIAS ' + estacion);
	  $("#btnModalSubirEvidencias").click();
	}

  $('#modalSubirEvidencias')
  .on('hide.bs.modal', function() {
	  $('#formRegistrarEvidencias').bootstrapValidator('resetForm', true); 
  })
  
  /***NUEVO FORMULARIO UM 18.06.2019 CZAVALACAS***/
  function openFormUM(component) {
	  var itemplan = $(component).data('item_plan');
		console.log(itemplan);
		$('#btnRegFormUM').attr('data-itemplan', itemplan);
		$('#txtItemplan').val(itemplan);
		$("#btnModalFormUM").click();
		//$('#modalFormUM').modal('toggle');
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
              url: "pqt_saveFormUM",
              cache: false,
              contentType: false,
              processData: false,
              type: 'POST'
            })
            .done(function(data) {
               var data	=	JSON.parse(data);
               if(data.error == 0){   
                   console.log('YES!!');
                   $("#btnModalFormUM").click();
                   //$('#modalFormUM').modal('toggle');
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

  $('#formRegistrarEvidencias')
  .bootstrapValidator({
  container: '#mensajeFormE',
  feedbackIcons: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
  },
  excluded: ':disabled',
  fields: {
      filePruebasRefleE: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe subir Docuemnto PRUEBAS REFLECTOMETRICAS.</p>'
              }
          }
      },
      filePerfilE: {
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
      
      var itemplan	=	$('#btnRegEvidencias').attr('data-itemplan');
      formData.append('itemplan', itemplan);

      var idestacion	=	$('#btnRegEvidencias').attr('data-id_estacion');
      formData.append('idEstacion', idestacion);

      var descestacion	=	$('#btnRegEvidencias').attr('data-desc_estacion');
      formData.append('descEstacion', descestacion);
      

      var input1File = document.getElementById('filePruebasRefleE');
      var file1 = input1File.files[0];
      formData.append('filePruebas', file1);

      var input2File = document.getElementById('filePerfilE');
      var file2 = input2File.files[0];
      formData.append('filePerfil', file2);
      
      swal({
          title: 'Est&aacute seguro registrar las evidencias?',
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
              url: "pqt_registrar_evidencias",
              cache: false,
              contentType: false,
              processData: false,
              type: 'POST'
            })
            .done(function(data) {
               var data	=	JSON.parse(data);
               if(data.error == 0){
                   $("#btnModalSubirEvidencias").click();
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
			$('#formRegistrarEvidencias').bootstrapValidator('resetForm', true); 
      });
  });

  function zipItemPlan(btn) {
	    var itemPlan = btn.data('itemplan');
	    var estacionDesc = btn.data('estaciondesc');
	    console.log("itemPlan=" + itemPlan);
	    console.log("estacionDesc=" + estacionDesc);
	    if(itemPlan == null || itemPlan == '') {
	        return;
	    }
	    $.ajax({
	        type : 'POST',
	        url  : 'pqt_get_evidencias',
	        data : { itemPlan : itemPlan, estacionDesc : estacionDesc }
	    }).done(function(data){
	        try {
	            data = JSON.parse(data);
	            if(data.error == 0) {
	                var url= data.directorioZip; 
	                if(url != null) {
	                    window.open(url, 'Download');
	                } else {
	                    alert('No tiene evidencias');
	                }   
	                // mostrarNotificacion('success', 'descarga realizada', 'correcto');
	            } else {
	                // mostrarNotificacion('error', 'descarga no realizada', 'error');            
	                alert('error al descargar');
	            }
	        } catch(err) {
	            alert(err.message);
	        }
	    });
	}

</script>


</body>
</html>
