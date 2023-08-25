
var idEmpresaColabGlobal = null;
var idJefaturaGlobal     = null;
var itemplanGlobal       = null;
var vrGlobal             = null;
function getComboPtr() {
    itemplanGlobal = $('#inputItemplan').val();

    if(itemplanGlobal.length != 13) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getComboPtr',
        data : { itemplan : itemplanGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contCmbPtr').html(data.cmbPtr);
            $('#contEmpresaColab').val(data.empresacolab);
            $('#contJefatura').val(data.jefatura);
            $('#contAlmacen').val(data.codAlmacen);
            $('#contCentro').val(data.codCentro);
    
            idEmpresaColabGlobal = data.idEmpresaColab;
            idJefaturaGlobal     = data.idJefatura;
        } else {
            mostrarNotificacion('error', data.msj);
        }
    })
}

var ptr_estadoGlobal         = null;
var countArrayTablaKitGlobal = null;
var cantidadIngresoAnteriorGlobal = null;
var idEstacionGlobal = null;
function getVr() {
    ptr_estadoGlobal = $('#contCmbPtr option:selected').val();
    idEstacionGlobal = $('#contCmbPtr option:selected').attr('data-id_estacion');

    if(ptr_estadoGlobal == '' || ptr_estadoGlobal == null) {
        return;
    }

    if(idEstacionGlobal == '' || idEstacionGlobal == null) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getVR',
        data : { ptr        : ptr_estadoGlobal,
                 itemplan   : itemplanGlobal,
                 idEstacion : idEstacionGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contVr').val(data.vr);
            
            var ptrYear = ptr_estadoGlobal.split('-');
          
            
            if(Number(ptrYear[0]) < 2019) {
                $('#cardKit').css('display', 'none');
                $('#subirExcel').css('display', 'block');
                $('#btnAceptarFormulario').attr('onclick', null);
            } else {
                $('#contTablaKit').html(data.tablaKit);
                $('#cardKit').css('display', 'block');
                $('#subirExcel').css('display', 'none');
                $('#btnAceptarFormulario').attr('onclick', 'insertSolicitudKit()');
            }
            
            vrGlobal = data.vr;
        } else {
            mostrarNotificacion('error', data.msj);
        }
    })
}

function openModalBloc() {
    modal('modalFormatoBloc');
}
var codigoVrGlobal = null;
var toog2=0;
//var error=0;
Dropzone.autoDiscover = false;
var errorAjax = null
var msj   = null;
$("#dropzone1").dropzone({
	url: "insertSap",
	addRemoveLinks: true,
	autoProcessQueue: false,
	parallelUploads: 200,
	maxFilesize: 800,
	acceptedFiles: ".txt",
	dictResponseError: "Ha ocurrido un error en el server",
	success: function(file, response) {
		data = JSON.parse(response);
		$('#contTablaBloc').html(data.tablaBloc);

		errorAjax = data.error;
		msj   = data.msj;

		if(errorAjax == 0) {
			codigoVrGlobal = data.codigo;
		} else {
			modal('modalFormatoBloc');
			mostrarNotificacion('error', 'Verificar', msj);
		}
		
		
	},
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
			  alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
			  return;
			//	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
				error=1;
			  // alert(message);
				this.removeFile(file); 
		});

		var submitButton = document.querySelector("#btnAceptarFormulario")
		var myDropzone = this; 
			
		submitButton.addEventListener("click", function() {	
			var ptr  = $('#contCmbPtr option:selected').val();
			
			if(ptr == null || ptr == '') {
				mostrarNotificacion('error', 'Verificar', 'debe seleccionar una ptr');
				return;
			}
			myDropzone.processQueue();
		   
		});

	   var concatEvi = '';
		// You might want to show the submit button only when 
		// files are dropped here:
		this.on("addedfile", function() {		    	
			toog2=toog2+1;	
		  // Show submit button here and/or inform user to click it.
		});
		
		this.on('complete', function () {
			if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
				if(error == 0){
					console.log(this.getUploadingFiles());
				}	            
			}	        
		});
	   
		this.on("queuecomplete", function (file) {
			var drop = this;
			if(errorAjax == 0) {
				$.ajax({
					type : 'POST',
					url  : 'getComboPtr',
					data : { itemplan : itemplanGlobal }
				}).done(function(data){
					data = JSON.parse(data);
					if(data.error == 0) {
						$('#contCmbPtr').html(data.cmbPtr);
						$('#contEmpresaColab').val(data.empresacolab);
						$('#contJefatura').val(data.jefatura);
						$('#contAlmacen').val(data.codAlmacen);
						$('#contCentro').val(data.codCentro);
						$('#contVr').val(null);
						idEmpresaColabGlobal = data.idEmpresaColab;
						idJefaturaGlobal     = data.idJefatura;
						
						modal('modalAlertaAceptacion');
						mostrarNotificacion('success','Registro realizado','c&oacute;digo: '+codigoVrGlobal);  
						drop.removeAllFiles(true);
					} else {
						mostrarNotificacion('error', data.msj);
					}
				});
			} else {
				mostrarNotificacion('error', 'error', msj);
			}
		});		

		this.on('sending', function(file, xhr, formData){
			var descripcion = $("#idDescripCoti").val();
			var ptr         = $('#contCmbPtr option:selected').val();

			var costo       = $("#idCostoCoti").val();
			var valeReserva = $('#contVr').val();
			formData.append('itemplan', itemplanGlobal);
			// formData.append('descripcion', (descripcion).trim());
			formData.append('idEmpresaColab', idEmpresaColabGlobal);
			formData.append('idJefaturaSap', idJefaturaGlobal);
			formData.append('ptr', ptr);
			formData.append('valeReserva', valeReserva);
			formData.append('vr', vrGlobal);
			formData.append('ptr_estado', ptr_estadoGlobal);
		});
	}
});

var flgGlbSoloDev = null;
function openModalAceptar() {
	/**czavalacas 21.02.2020*/
	var po = ptr_estadoGlobal.split('_')[0];
	console.log(po);
	var ptrYear = ptr_estadoGlobal.split('-');

	if(Number(ptrYear[0]) >= 2019) {
		var flgValid = null;
		arrayDataKitGlobal.forEach(function(data, key){
			if(data.cantidadInicio == 0) {
				data.cantidadInicio = '0';
			}

			if(data.cantidadInicio == null || data.cantidadInicio == '' || data.flg_tipo_solicitud == null || data.flg_tipo_solicitud == '') {
				flgValid = 1;
			}

			if(data.cantidadFin < 0) {
				flgValid = 2;
			}

			data.itemplan       = itemplanGlobal,
			data.ptr            = po,
			data.idJefaturaSap  = idJefaturaGlobal,
			data.idEmpresaColab = idEmpresaColabGlobal,
			data.vr             = vrGlobal  
		});

		if(flgValid == 1) {
			mostrarNotificacion('info','Falta ingresar datos 122', 'Verificar');  
			return;
		}
		
		if(flgValid == 2) {
			mostrarNotificacion('info','cantidad ingresada supera a la cantidad con la que cuenta el material', 'Verificar');  
			return;
		}
		
		jsonCreateSol = { origen       		: 3,
						  tipo_po_dato 		: 1, 
						  accion_dato  		: 2, 
						  codigo_po_dato    : po, 
						  itemplan_dato  	: itemplanGlobal, 
						  costoTotalPo_dato : costoTotalPoGlobal, 
						  data_json         : arrayDataKitGlobal,
						  idEstacion        : idEstacionGlobal };
		
		if(flgGlbSoloDev == 1) {//SI SOLO TIENE DEVOLUCION SE EJECUTA NORMAL.
			$('#btnAceptarFormulario').css('display', 'block');
			modal('modalAlertaAceptacion');
		} else {
			canCreateEditPOByCostoUnitario(jsonCreateSol, function() {//reactivado el 18.03.2020 validacion solo para sisegos czavalacas
				$('#btnAceptarFormulario').css('display', 'block');
				modal('modalAlertaAceptacion');
			}); 	
		}
	} else {
		modal('modalAlertaAceptacion');
	}
}

var jsonDataKitGlobal = {};
var arrayDataKitGlobal = [];
var cantidadQueda = 0;
var costoTotalxMat = 0;
var sumTotalGlobal = 0;
var idTipoSolitud = 0;
function getDataInsert(idMaterial, cont, costoMaterial, costo_total, flg_valid_estado_plan, flg_valid_porcentaje, flgSolicitud=null) { 
    var sumTotal = 0;
    idTipoSolitud = $('#cmbTipoSolicitud_'+cont+' option:selected').val();
    var cantidad = $('#inputCantidad_'+cont).val();
    var contador = 0;
	var send_rpa = 0;
	
    if(cantidad == null || cantidad == '') {
        return;
    }

    if(idTipoSolitud == null || idTipoSolitud == '') {
        return;
    }
	
    if(idMaterial == null || idMaterial == '') {
        $('#cmbTipoSolicitud_'+cont+' option:selected').val('');
        return;
    }

    cantidadIngresoAnteriorGlobal = $('#cantidadObra_'+cont).val();

    if(cantidadIngresoAnteriorGlobal == null || cantidadIngresoAnteriorGlobal == '' || cantidadIngresoAnteriorGlobal == undefined) {
        return;
    }

    if(idTipoSolitud == 4) { //DEVOLUCION
		send_rpa = 1;
        $('#inputCantidad_'+cont).prop("disabled", false);
        cantidadQueda = (Number(cantidadIngresoAnteriorGlobal) - Number(cantidad)).toFixed(2);
    } else {
		if(flg_valid_estado_plan == 1) {
            mostrarNotificacion('error', 'LA ESTACI&Oacute;N ESTA LIQUIDADA, SOLO PUEDE REGISTRAR UNA SOLICITUD DE ADICI&Oacute;N EN LAS POs QUE SE ENCUENTREN EN ESTADO “APROBADO”', '');
			return;
		} 
        if(flg_valid_porcentaje == 1) {
            mostrarNotificacion('error','La estaci&oacute;n no debe estar liquidada (100%), para generar una solicitud de vr, solo se permite tipo "DEVOLUCI&Oacute;N".','error');
			return;
		}
		
		if(idTipoSolitud == 1) {
			$('#inputCantidad_'+cont).prop("disabled", false);
			cantidadQueda = (Number(cantidadIngresoAnteriorGlobal) + Number(cantidad)).toFixed(2);
		}else if(idTipoSolitud == 2) {//ANULAR 
			$('#inputCantidad_'+cont).prop("disabled", true);
			cantidadQueda = 0;
			cantidad      = 0;
			$('#inputCantidad_'+cont).val(0);
		} else { // MODIFICAR
			$('#inputCantidad_'+cont).prop("disabled", false);
			cantidadQueda = Number(cantidad);
		}
	} 
    
    $('#cantidadIngreso_'+cont).val(cantidadQueda);
	flgGlbSoloDev = 1;
    arrayDataKitGlobal.forEach(function(data, key){
        if(data.material == idMaterial) {
            contador = 1;
            jsonDataKitGlobal.material           = idMaterial;
            jsonDataKitGlobal.flg_tipo_solicitud = idTipoSolitud;
            jsonDataKitGlobal.cantidadInicio     = cantidad;
            jsonDataKitGlobal.cantidadFin        = cantidadQueda;
            jsonDataKitGlobal.costoMaterial      = costoMaterial;
            jsonDataKitGlobal.flg_adicion        = flgSolicitud;
			jsonDataKitGlobal.send_rpa           = send_rpa;
            arrayDataKitGlobal.splice(key, 1, jsonDataKitGlobal);
            jsonDataKitGlobal = {};
        } 
    });

    if(contador == 0) {
        jsonDataKitGlobal.material           = idMaterial;
        jsonDataKitGlobal.flg_tipo_solicitud = idTipoSolitud;
        jsonDataKitGlobal.cantidadInicio     = cantidad;
        jsonDataKitGlobal.cantidadFin        = cantidadQueda;
        jsonDataKitGlobal.costoMaterial      = costoMaterial;
        jsonDataKitGlobal.flg_adicion        = flgSolicitud;
		jsonDataKitGlobal.send_rpa           = send_rpa;
        // arrayData.push(jsonData);
        arrayDataKitGlobal.splice(arrayDataKitGlobal.length, 0, jsonDataKitGlobal);
        jsonDataKitGlobal = {};
    }
    if(idTipoSolitud == 1 || idTipoSolitud == 5) {
        total(sumTotal);
    }
	getTotalPo(idTipoSolitud, costo_total, sumTotal, cantidad);
	console.log("COSTO PO1: "+costo_total);
    console.log("COSTO PO:"+costoTotalPoGlobal);
	console.log(arrayDataKitGlobal);
}

function getTotalPo(idTipoSolicitud, costoPo, sumTotal, cantidad) {
    costoTodo = 0;
	console.log(cantidadIngresoAnteriorGlobal);
    arrayDataKitGlobal.forEach(function(data, key){
        if(data.flg_tipo_solicitud == 5) {
			console.log("COSTOOOTODAsa: "+costoPo);
			flgGlbSoloDev = 0; // SI ES OTRO DISTINTO A DEVOLUCION CAMBIA SU FLG
            if(Number(cantidadIngresoAnteriorGlobal) > Number(data.cantidadInicio)) {
				console.log("COST1: "+costoPo);
                costoTotalxMat = ((Number(cantidadIngresoAnteriorGlobal) - Number(data.cantidadInicio)) * Number(data.costoMaterial)).toFixed(2);
                // sumTotalDev    = (Number(costoTotalxMat) + Number(costoMatDev)).toFixed(2);
                costoPo = (Number(costoPo) - Number(costoTotalxMat)).toFixed(2);
				console.log("COST2: "+costoPo);
            } else {
                costoTotalxMat = ((Number(data.cantidadInicio) - Number(cantidadIngresoAnteriorGlobal)) * Number(data.costoMaterial)).toFixed(2);
                // sumTotalDev    = (Number(costoTotalxMat) + Number(costoMatDev)).toFixed(2);
                costoPo = (Number(costoPo) + Number(costoTotalxMat)).toFixed(2);
            }
			console.log("COSTOOO: "+costoPo);
        } else {
            costoTotalxMat = (Number(data.cantidadInicio) * Number(data.costoMaterial)).toFixed(2);
            //sumTotalDev = (Number(costoTotalxMat) + Number(costoMatDev)).toFixed(2);

            if(data.flg_tipo_solicitud == 1) {
				flgGlbSoloDev = 0;
                console.log(costoPo+' + '+costoTotalxMat);
                costoPo = (Number(costoPo) + Number(costoTotalxMat)).toFixed(2);
            } else if(data.flg_tipo_solicitud == 4) {
                console.log(costoPo+' - '+costoTotalxMat);
                costoPo = (Number(costoPo) - Number(costoTotalxMat)).toFixed(2);
            }
			
			console.log("COSTO PO2: "+costoPo);
        }
    });

    costoTotalPoGlobal = costoPo;
    console.log(costoTotalPoGlobal);
    // if(idTipoSolicitud == 1) {
    //     costoTotalPoGlobal = (Number(costoPo) + Number(sumTotal)).toFixed(2);
    // } else if(idTipoSolicitud == 4) {
    //     costoTotalPoGlobal = (Number(costoPo) - Number(sumTotal)).toFixed(2);
    // }
}

function total(sumTotal) {
    arrayDataKitGlobal.forEach(function(data, key){
        console.log(data.flg_tipo_solicitud);
        if(data.flg_tipo_solicitud == 1 || data.flg_tipo_solicitud == 5)  {
            costoTotalxMat = (Number(data.cantidadInicio)* Number(data.costoMaterial)).toFixed(2);
            sumTotal       = (Number(costoTotalxMat) + Number(sumTotal)).toFixed(2);
        } 
    });
    $('#totalMat').html('total: '+sumTotal);
    sumTotalGlobal = sumTotal;
}

function insertSolicitudKit() {
    if(itemplanGlobal == null || itemplanGlobal == '') {
        return;
    }
    if(idJefaturaGlobal == null || idJefaturaGlobal == '') {
        return;
    }
    if(idEmpresaColabGlobal == null || idEmpresaColabGlobal == '') {
        return;
    }
    
    $('#btnAceptarFormulario').css('display', 'none');
    // if(vrGlobal == null || vrGlobal == '') {
    //     return;
    // }

    var po = ptr_estadoGlobal.split('_');
    var flgValid = null;

    if(arrayDataKitGlobal.length == 0) {
        mostrarNotificacion('info','Falta ingresar tipo y cantidad', 'informaci&oacute;n');
        return;
    }

    if(idTipoSolitud == 1) {
        if(1000 < sumTotalGlobal) {
            mostrarNotificacion('error','Total mayor a 1000s', 'El costo de adici&oacute;n debe ser menor a 1000');
            return;
        }
    }
    
    arrayDataKitGlobal.forEach(function(data, key){
        if(data.cantidadInicio == 0) {
            data.cantidadInicio = '0';
        }
        console.log(data.cantidadInicio);
        if(data.cantidadInicio == null || data.cantidadInicio == '' || data.flg_tipo_solicitud == null || data.flg_tipo_solicitud == '') {
            flgValid = 1;
        }

        if(data.cantidadFin < 0) {
            flgValid = 2;
        }

        data.itemplan       = itemplanGlobal,
        data.ptr            = po[0],
        data.idJefaturaSap  = idJefaturaGlobal,
        data.idEmpresaColab = idEmpresaColabGlobal,
        data.vr             = vrGlobal  
    });

    if(flgValid == 1) {
        mostrarNotificacion('info','Falta ingresar datos 122', 'informaci&oacute;n');  
        return;
    }
    
    if(flgValid == 2) {
        mostrarNotificacion('info','cantidad ingresada supera a la cantidad con la que cuenta el material', 'informaci&oacute;n');  
        return;
    }
    console.log(arrayDataKitGlobal);
    $.ajax({
        type : 'POST',
        url  : 'insertSolicitudKit',
        data : { arrayDataKitInsert : arrayDataKitGlobal }
    }).done(function(data) {
        data = JSON.parse(data);
        if(data.error == 0) {
            sumTotal = 0;
            arrayDataKitGlobal = [];
            $('#cardKit').css('display', 'none');
            modal('modalAlertaAceptacion');
            $('#codigo').html('<a>'+data.codigoSolicitud+'<a>');
            modal('modalCodigoSolicitud');
            mostrarNotificacion('success','Registro realizado, '+data.msj, 'correcto'); 
        } else {
            mostrarNotificacion('error', data.msj, 'incorrecto');
        }
    });
}

var idSubProyectoGlobal = null;
var poGlobal = null;
var arrayKitSelec = [];
var jsonKitSelec  = {};
function getKitMaterial() {
    arrayKitSelec = [];
    jsonKitSelec  = {};
    var po = ptr_estadoGlobal.split('_');
    idSubProyectoGlobal = $('#contCmbPtr option:selected').attr('data-id_subproyecto');
    poGlobal = po[0];
    if(poGlobal == '' || poGlobal == null) {
        return;
    }

    if(idEstacionGlobal == '' || idEstacionGlobal == null) {
        return;
    }

    if(itemplanGlobal == '' || itemplanGlobal == null) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getKitMaterial',
        data : { po         : poGlobal,
                 idEstacion : idEstacionGlobal,
                 itemplan   : itemplanGlobal }
    }).done(function(data) {
        data = JSON.parse(data);
        if(data.error == 0) {
            modal('modalKitMaterial');
            $('#contKitMaterial').html(data.tablaKitItemplan);
        } else {
            mostrarNotificacion('error', data.msj, 'incorrecto');
        }
    });
}

function getDataKit(idMaterial, cont) {
    var contador = 0;
    if(idMaterial == null || idMaterial == '') {
        return;
    }


    arrayKitSelec.forEach(function(data, key){
        if(!$('#checkit_'+cont).is(':checked')) {
            if(data.id_material == idMaterial) {
                contador = 1;
                arrayKitSelec.splice(key, 1);
            }
        }
    });
    
    if(contador == 0) {
        jsonKitSelec.id_material   = idMaterial;
        jsonKitSelec.idEstacion    = idEstacionGlobal;
        jsonKitSelec.idSubProyecto = idSubProyectoGlobal;
        jsonKitSelec.flg_solicitud = 1;
        arrayKitSelec.splice(arrayKitSelec.length, 0, jsonKitSelec);
        jsonKitSelec = {};
    }
}

function insertKitMaterialSolicitud() {
    if(poGlobal == null || poGlobal == '' || itemplanGlobal == null || itemplanGlobal == '' || idEstacionGlobal == '' || idEstacionGlobal == null) {
        return;
    }
    
    $.ajax({
        type : 'POST',
        url  : 'insertKitMaterialSolicitud',
        data : { arrayKitSelec : arrayKitSelec,
                 itemplan      : itemplanGlobal,
                 po            : poGlobal,
                 idEstacion    : idEstacionGlobal }
    }).done(function(data) {
        data = JSON.parse(data);
        if(data.error == 0) {
            arrayKitSelec = [];
            jsonKitSelec  = {};
            modal('modalKitMaterial');
            $('#contTablaKit').html(data.tablaKit);
            mostrarNotificacion('succes', 'se ingreso material para la solicitud', 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'incorrecto');
        }
    });
}