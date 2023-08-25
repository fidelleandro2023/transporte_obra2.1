var itemplanGlobal = null;
function openPiloto(btn) {
	itemplanGlobal = btn.data('itemplan');

	if(itemplanGlobal == '' || itemplanGlobal == null) {
		return;
	}
	$('#subItemplan').html('<h2>'+itemplanGlobal+'</h2>');
	$.ajax({
		type : 'POST',
		url  : 'getDataFluidUno',
		data : { itemplan : itemplanGlobal}
	}).done(function(data){
		data = JSON.parse(data);
		console.log(data.error);
		if(data.error == 0) {
			var a = document.createElement("a");
			a.target = "_blank";
			a.href = "getProcesoPiloto";
			a.click();
			$('#comentarioFluiUno').val(data.comentarioFluidUno);
		} else {
			mostrarNotificacion('error', data.msj, 'error');
		}
	});
}

var fechaRegistroItemplan = null;
var fechaReplanteo       = null;
var fechaElaboracionFuit = null;
var fechaEntregaFuit     = null;
var fechaInsPex          = null;
var jsonDuracion = {};
function init() {
	var urlParams = new URLSearchParams(window.location.search);
	itemplanGlobal = urlParams.get('itemplan');
    initWizard('rootwizardEdit1');
    
    $.ajax({
		type : 'POST',
		url  : 'getDataPiloto',
		data : { itemplan	    : itemplanGlobal }
	}).done(function(data){
		data = JSON.parse(data);
        
        // jsonDuracion.duracionFluidTres = obj.duracionFluidTres;
		if(data.error == 0) { 
			var obj = data.objPiloto;
            var orden = 1;

			if(obj != null) {
                if(obj.comentario_asig_facil) {
                    $('#btnFluidUno').css('display', 'none');
                    if(obj.orden != 2) {
                        orden = 2+parseInt(obj.orden);  
                    }                    
                } else if(obj.fecha_creacion) {
                    fechaRegistroItemplan = new Date(obj.fecha_creacion).getTime();
                }

                if(obj.comentario_agen_replanteo) {
                    // $('#btnFluidDos').css('display', 'none');
                    orden = 1+parseInt(obj.orden);
                }

                if(obj.comentario_replanteo) {
                    if(obj.orden != 2) {
                        $('#btnFluidTres').css('display', 'none');
                        orden = 1+parseInt(obj.orden); 
                    } else {
                        orden = obj.orden;
                    }  
                   
                } else if(obj.fecha_registro_asig_facil){
                    fechaReplanteo = new Date(obj.fecha_registro_asig_facil).getTime();
                }

                // if(obj.countMotivoEjec > 0) { console.log(3);
                //     $('#btnFluidTres').css('display', 'none');
                //     orden = 1+parseInt(obj.orden);
                // }

                if(obj.comentario_elaboracion_fuit) {
                    $('#btnFluidCuatro').css('display', 'none');
                    orden = 1+parseInt(obj.orden);
                } else if(obj.fecha_reg_replanteo) {
                    fechaElaboracionFuit = new Date(obj.fecha_reg_replanteo ).getTime();
                }

                if(obj.comentario_entrega_fuit) {
                    $('#btnFluidCinco').css('display', 'none');
                    orden = 2+parseInt(obj.orden);
                } else if(obj.fecha_reg_elaboracion_fuit) {
                    fechaEntregaFuit = new Date(obj.fecha_reg_elaboracion_fuit ).getTime();
                }

                // if(obj.comentario_agen_instalacion || obj.id_motivo_replanteo != 53) {//SI TIENE MOTIVO AGENDA NO APARECERÁ EL BOTÓN
                //     $('#btnFluidSeis').css('display', 'none');  
                //     //orden = 1+parseInt(obj.orden);
                // }

                if(obj.comentario_instalacion_pex) {
                    $('#btnFluidSiete').css('display', 'none');
                    orden = 1+parseInt(obj.orden);
                } else if(obj.fecha_reg_entrega_fuit) {
                    fechaInsPex = new Date(obj.fecha_reg_entrega_fuit ).getTime();
                }
            }
            nextStep(orden);
		} else {
			mostrarNotificacion('error', data.msj, 'error');
		}
	});
}

var timerUno = setInterval(function() {
    var now = new Date().getTime();
    if(fechaRegistroItemplan != null) {
        var timeAsigFacil = now - fechaRegistroItemplan;
        timeAsigFacil = calculoDiaHoraMinSec(timeAsigFacil);

        $('#tileAsigFacil').html('Asignaci&oacute;n Facilidades <br>'+timeAsigFacil);  
    }
    }, 1000);

var timerTres = setInterval(function() {
    var now = new Date().getTime();
    if(fechaReplanteo != null) {
        var timeFechaReplanteo = now - fechaReplanteo;
        timeReplanteo = calculoDiaHoraMinSec(timeFechaReplanteo);

        $('#tileReplanteo').html('Remplanteo <br>'+timeReplanteo);  
    }
    }, 1000);

var timerCuatro = setInterval(function() {
    var now = new Date().getTime();

    if(fechaElaboracionFuit != null) {
      var timeFechaElabFuit  = now - fechaElaboracionFuit;
      timeElbFuit   = calculoDiaHoraMinSec(timeFechaElabFuit);
  
      $('#titleElabFuit').html('Elaboraci&oacute;n FUIT <br>'+timeElbFuit);  
    }
  }, 1000);
  

var timerCinco = setInterval(function() {
    var now = new Date().getTime();
    
    if(fechaEntregaFuit != null) {
        var timeFechaEntregaFuit  = now - fechaEntregaFuit;
        timeEntFuit   = calculoDiaHoraMinSec(timeFechaEntregaFuit);

        $('#titleEntFuit').html('Entrega FUIT <br>'+timeEntFuit);  
    }
}, 1000);

var timerSiete = setInterval(function() {
    var now = new Date().getTime();
    
    if(fechaInsPex != null) {
        var timeFechaInsPex  = now - fechaInsPex;
        timeInsPex   = calculoDiaHoraMinSec(timeFechaInsPex);

        $('#titleInsPex').html('Instalaci&oacute;n PEX <br>'+timeInsPex);  
    }
}, 1000);
  
function calculoDiaHoraMinSec(distacia) {
    var days    = Math.floor(distacia / (1000 * 60 * 60 * 24));
    var hours   = Math.floor((distacia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distacia % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distacia % (1000 * 60)) / 1000);

    return hours+':'+minutes+':'+seconds;
}

function initWizard(id){
	$('#'+id).bootstrapWizard({
		onTabShow: function(tab, navigation, index) {
			verifyWizarComplete(navigation, $('#'+id), index, true);
		}
	});
}

function nextStep(step) {
    $('.wizard-aux-class.active').removeClass('active');
    $('.tab-pane.pane-par').removeClass('active');
	if(step == 1){
		ajaxFluid('getDataPiloto', '0%', 12);
		// $("#progressBar").css("width","0%");
	} else if(step == 2) {
		ajaxFluid('getDataPiloto', '14.29%', 13);
	} else if(step == 3) {
		ajaxFluid('getDataPiloto', '28.58%', 14);
	} else if(step == 4) {
		ajaxFluid('getDataPiloto', '42.87%', 15);
	} else if(step == 5) {
		ajaxFluid('getDataPiloto', '57.16%', 16);
	} else if(step == 6) {
		ajaxFluid('getDataPiloto', '72.45%', 17);
	} else if(step == 7) {
		ajaxFluid('getDataPiloto', '85.74%', 18);
	} else if(step == 8) {
		ajaxFluid('getDataPiloto', '100%', 19);
	}
	$('#li'+step).addClass('active');
	$('#tab'+step).addClass('active');
	stepGlobal = step;
}

function ajaxFluid(urlFluid, porcentaje, idConfigPiloto) {
    $("#progressBar").css("width", porcentaje);
}
var placaGlobal = null;
function registrarFluidUno() {
	var comentarioFluidUno    = $('#comentarioFluiUno').val();
    var idMotivoAsigFacilidad = $('#cmbMotivoAsigFacilidad option:selected').val();
    placaGlobal               = $('#idCmbPlaca option:selected').val();
    //DURACION
    var title = $('#tileAsigFacil').html();
    var duracion = title.split('<br>');

    if(idMotivoAsigFacilidad == null || idMotivoAsigFacilidad == '') {
        $('#validaMotivoUno').html('<a style="color:red">Debe seleccionar motivo</a>');
		return;
    } else {
        $('#validaMotivoUno').html(null);
    }

	if(comentarioFluidUno == null || comentarioFluidUno == '') {
		$('#validaComentario').html('<a style="color:red">Debe ingresar el comentario</a>');
		return;
	} else {
		$('#validaComentario').html(null);
    }
    
	if(itemplanGlobal == null || itemplanGlobal == '' || duracion[1] == null || duracion[1] == '') {
		return;
    }
    
    if(idMotivoAsigFacilidad == 48) {
        if(placaGlobal == null || placaGlobal == '') {
            $('#validaCmbPlaca').html('<a style="color:red">Debe seleccionar la placa</a>');
            return;
        } else {
            $('#validaCmbPlaca').html(null);
        }
    }
	
	$.ajax({
		type : 'POST',
		url  : 'registrarFluidUno',
		data : { comentarioFluidUno    : comentarioFluidUno,
                 itemplan              : itemplanGlobal,
                 idMotivoAsigFacilidad : idMotivoAsigFacilidad,
                 placa                 : placaGlobal,
                 duracion              : duracion[1] }
	}).done(function(data) {
		data = JSON.parse(data);
		if(data.error == 0) {
            mostrarNotificacion('success', 'se ingreso los datos correctamente', 'ingresado');
            if(idMotivoAsigFacilidad == 48) {
                fechaReplanteo = new Date().getTime();
                $('#btnFluidUno').css('display', 'none');
                clearInterval(timerUno);
                nextStep(3);
            }
            $('#contTablaBitacoraAsigFacilidad').html(data.tablaBitacora);
            initDataTable('#data-table');
		} else {
			mostrarNotificacion('error', data.msj, 'error');
		}
	
	});
}

function registrarFluidDos() {
	// var fechaCitaFluidDos = $('#fechaCitaFluiDos').val();
	var comentarioFluidDos = $('#comentarioFluiDos').val();

	if(comentarioFluidDos == null || comentarioFluidDos == '') {
		$('#validaComentarioDos').html('<a style="color:red">Debe ingresar el comentario</a>');
		return;
	} else {
		$('#validaComentarioDos').html(null);
	}

	// if(fechaCitaFluidDos == null || fechaCitaFluidDos == '') {
	// 	$('#fechaCitaDos').html('<a style="color:red">Debe ingresar la fecha</a>');
	// 	return;
	// } else {
	// 	$('#fechaCitaDos').html(null);
	// }

	if(itemplanGlobal == null || itemplanGlobal == '') {
		return;
	}

	$.ajax({
		type : 'POST',
		url  : 'registrarFluidDos',
		data : { comentarioFluidDos : comentarioFluidDos,
				 itemplan           : itemplanGlobal }
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
            mostrarNotificacion('success', 'se ingreso los datos correctamente', 'ingresado');
            // $('#btnFluidDos').css('display', 'none');
			nextStep(3);
		} else {
			mostrarNotificacion('error', data.msj, 'error');
		}
	});
}

function registrarFluidTres() {
	var idMotivoReplanteo   = $('#cmbMotivoReplanteo option:selected').val();
    var comentarioFluidTres = $('#comentarioFluiTres').val();
    //DURACION
    var title = $('#tileReplanteo').html();
    var duracion = title.split('<br>');

	$.ajax({
		type : 'POST',
		url  : 'registrarFluidTres',
		data : { comentarioFluidTres : comentarioFluidTres,
				 itemplan           : itemplanGlobal,
                 idMotivoReplanteo  : idMotivoReplanteo,
                 duracionReplanteo  : duracion[1] }
	}).done(function(data){
        data = JSON.parse(data);
       
		if(data.error == 0) {
            mostrarNotificacion('success', 'se ingreso los datos correctamente', 'ingresado');
            if(idMotivoReplanteo == 53) {//SI ES AGENDA
                nextStep(2);
            } 
            
            if(idMotivoReplanteo == 48) {
                
                $('#btnFluidTres').css('display', 'none');
                nextStep(4);
                fechaElaboracionFuit = new Date().getTime();
                clearInterval(timerTres);
            }
            $('#contTablaBitacoraReplanteo').html(data.tablaBitacora);
            // initDataTable('#data-table');
		} else {
			mostrarNotificacion('error', data.msj, 'error');
		}
	});
}

// function registrarFluidCuatro() {
// 	var comentarioFluidCuatro = $('#comentarioFluiCuatro').val();

	// if(comentarioFluidCuatro == null || comentarioFluidCuatro == '') {
	// 	$('#validaComentarioTres').html('<a style="color:red">Debe ingresar el comentario</a>');
	// 	return;
	// } else {
	// 	$('#validaComentarioTres').html(null);
	// }

// 	if(itemplanGlobal == null || itemplanGlobal == '') {
// 		return;
// 	}


// }
//FLUID 4
$('#import_form').on('submit', function(event){
    data = new FormData();
    
    var idMotivoElabFuit     = $('#cmbMotivoElabFuit option:selected').val();
    var comentarioFluidCuatro = $('#comentarioFluiCuatro').val();
    var file = $('#file')[0].files[0];

    //DURACION
    var title = $('#titleElabFuit').html();
    var duracion = title.split('<br>');

	if(comentarioFluidCuatro == null || comentarioFluidCuatro == '') {
        $('#validaComentarioCuatro').html('<a style="color:red">Debe ingresar el comentario</a>');
        event.preventDefault();
		return;
	} else {
		$('#validaComentarioCuatro').html(null);
    }
    
    if(idMotivoElabFuit == null || idMotivoElabFuit == '') {
        $('#validaMotivoCuatro').html('<a style="color:red">Debe seleccionar el motivo</a>');
        event.preventDefault();
		return;
    } else {
		$('#validaMotivoCuatro').html(null);
    }

    if(idMotivoElabFuit == 48) {//SI SE EJECUTA EL PROCESO
        if(file == null || file == '') {
            $('#validaFileFluidCuatro').html('<a style="color:red">ingresar archivo</a>');
            event.preventDefault();
            return;
        } else {
            $('#validaFileFluidCuatro').html(null);
        }
    }

	if(itemplanGlobal == null || itemplanGlobal == '') {
		return;
    }

    data.append('file'                 , $('#file')[0].files[0]);
    data.append('itemplan'             , itemplanGlobal);
    data.append('comentarioFluidCuatro', comentarioFluidCuatro);
    data.append('idMotivoElabFuit'     , idMotivoElabFuit);
    data.append('duracionElabFuit'     , duracion[1])

    event.preventDefault();
	$.ajax({
        url  : 'registrarFluidCuatro',
		type : 'POST',
        data : data,
        contentType : false,
        cache       : false,
        processData : false
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
            mostrarNotificacion('success', 'se ingreso los datos correctamente', 'ingresado');
            if(idMotivoElabFuit == 48){
                clearInterval(timerCuatro);
                $('#btnFluidCuatro').css('display', 'none');
                nextStep(5);
                fechaEntregaFuit = new Date().getTime();
            }
            $('#contTablaBitacoraElabFuit').html(data.tablaBitacora);
            // initDataTable('#data-table');
		} else {
			mostrarNotificacion('error', data.msj, 'error');
        }
	});
});

function registrarFluidCinco() {
	var comentarioFluidCinco = $('#comentarioFluiCinco').val();
    var idMotivoEntFuit      = $('#cmbMotivoEntFuit option:selected').val();
    //DURACION
    var title = $('#titleEntFuit').html();
    var duracion = title.split('<br>');

	if(comentarioFluidCinco == null || comentarioFluidCinco == '') {
		$('#validaComentarioCinco').html('<a style="color:red">Debe ingresar el comentario</a>');
		return;
	} else {
		$('#validaComentarioCinco').html(null);
	}

    if(idMotivoEntFuit == null) {
        $('#validaMotivoCinco').html('<a style="color:red">Debe seleccionar motivo</a>');
    } else {
        $('#validaMotivoCinco').html(null);
    }

	if(itemplanGlobal == null || itemplanGlobal == '' || duracion[1] == null || duracion[1] == '') {
		return;
	}

	$.ajax({
		type : 'POST',
		url  : 'registrarFluidCinco',
		data : { comentarioFluidCinco : comentarioFluidCinco,
                 itemplan             : itemplanGlobal,
                 idMotivoEntFuit      : idMotivoEntFuit,
                 duracion             : duracion[1] }
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
            mostrarNotificacion('success', 'se ingreso los datos correctamente', 'ingresado');
            if(idMotivoEntFuit == 48){
                clearInterval(timerCinco);
                $('#btnFluidCinco').css('display', 'none');
                nextStep(7);
                fechaInsPex = new Date().getTime();//INICIAR EL TIMER SIETE
            }
            $('#contTablaBitacoraEntregaFuit').html(data.tablaBitacora);
		} else {
			mostrarNotificacion('error', data.msj, 'error');
		}
	});
}

function registrarFluidSeis() {
	var comentarioFluidSeis = $('#comentarioFluidSeis').val();
    var fechaCitaFluidSeis  = $('#fechaCitaFluiSeis').val();

	if(comentarioFluidSeis == null || comentarioFluidSeis == '') {
		$('#validaComentarioSeis').html('<a style="color:red">Debe ingresar el comentario</a>');
		return;
	} else {
		$('#validaComentarioSeis').html(null);
    }
    
	// if(fechaCitaFluidSeis == null || fechaCitaFluidSeis == '') {
	// 	$('#validaFechaCitaSeis').html('<a style="color:red">Debe ingresar la fecha</a>');
	// 	return;
	// } else {
	// 	$('#validaFechaCitaSeis').html(null);
	// }


	if(itemplanGlobal == null || itemplanGlobal == '') {
		return;
	}

	$.ajax({
		type : 'POST',
		url  : 'registrarFluidSeis',
        data : { comentarioFluidSeis : comentarioFluidSeis,
				 itemplan            : itemplanGlobal }
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
            mostrarNotificacion('success', 'se ingreso los datos correctamente', 'ingresado');
            $('#btnFluidSeis').css('display', 'none');
			nextStep(7);
		} else {
			mostrarNotificacion('error', data.msj, 'error');
		}
	});
}

function registrarFluidSiete() {
	var comentarioFluidSiete = $('#comentarioFluiSiete').val();
    var idMotivoInsPex       = $('#cmbMotivInsPex option:selected').val();
    //DURACION
    var title = $('#titleInsPex').html();
    var duracion = title.split('<br>');

    if(idMotivoInsPex == null || idMotivoInsPex == '') {
        $('#validaMotivoSiete').html('<a style="color:red">Debe seleccionar un motivo</a>');
        return;
    } else {
        $('#validaMotivoSiete').html(null);
    }
    
	if(comentarioFluidSiete == null || comentarioFluidSiete == '') {
		$('#validaComentarioSiete').html('<a style="color:red">Debe ingresar el comentario</a>');
		return;
	} else {
		$('#validaComentarioSiete').html(null);
    }

    if(idMotivoInsPex == 48) {
      
    }

	if(itemplanGlobal == null || itemplanGlobal == '') {
		return;
	}

	$.ajax({
		type : 'POST',
		url  : 'registrarFluidSiete',
		data : { comentarioFluidSiete : comentarioFluidSiete,
                 itemplan             : itemplanGlobal,
                 idMotivoInsPex       : idMotivoInsPex,
                 duracion             : duracion[1] }
	}).done(function(data){
		data = JSON.parse(data);
		if(data.error == 0) {
            if(idMotivoInsPex == 53) {
                nextStep(6);
            }
            mostrarNotificacion('success', 'se ingreso los datos correctamente', 'ingresado');
            if(idMotivoInsPex == 48) {
                clearInterval(timerSiete);
                $('#btnFluidSiete').css('display', 'none');
                nextStep(8);
                $('#idCmbValeReserva').html(data.cmbValeReserva);
            } 
            $('#contTablaBitacoraInstalacionPex').html(data.tablaBitacora);
		} else {
			mostrarNotificacion('error', data.msj, 'error');
		}
	});
}


function abrirModalRegisEnt() {

    $.ajax({
        type: 'POST',
        url: 'getCmbEntLic',
        data: {
            itemplan   : itemplanGlobal,
            idEstacion : 5
        }
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {

            $('#idCmbEnt').html(data.htmlEntidades);
            modal('modalRegistrarEnt');
        } else {
            mostrarNotificacion('error', 'Hubo un error interno, íntentelo de nuevo.');
        }
    });
}

function registrarEntidades() {

    var idEntidad = $('#idCmbEnt option:selected').val();
    console.log(idEntidad);
    if(itemplanGlobal == null || itemplanGlobal == '') {
        return;
    }

    if (idEntidad != null && idEntidad != undefined && idEntidad != 0) {
        $.ajax({
            type: 'POST',
            url: 'registrarEntidadesPiloto',
            data: {
                itemplan  : itemplanGlobal,
                idEntidad : idEntidad
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                console.log("ENTRO");
                $('#contTablaEnt').html(data.tablaEntidades);

                mostrarNotificacion('success', data.msj);
                modal('modalRegistrarEnt');

                // modal('modalRegistrarEntidades');
            } else {
                mostrarNotificacion('success', data.msj);
            }
        });
    }

}

var idIpEntLicGlob = null;
function abrirModalComprobantes(component) {
	idIpEntLicGlob = $(component).data('idipestlic');

    if (idIpEntLicGlob != null) {
        $.ajax({
            type: 'POST',
            'url': 'getModalComprobante',
            data: {
                idItemPlanEstaDetalle: idIpEntLicGlob
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                $('#contTablaCompro').html(data.tablaComprobantes);
                modal('modalComprobantes');
            } else {
                mostrarNotificacion('error', data.msj);
            }
        });
    }
    myDropzone2.removeAllFiles(true);
}


function abrirModalEvidencia(component, flgTipo, flgValida) {
    console.log(flgTipo);
    if (flgTipo == 1) {
        idIpEntLicGlob = $(component).data('idipestlic');
        modal('modalSubirEvidencia');
    } else if (flgTipo == 2) {
        modal('modalSubirFotoComprobante');
    } else {

    }
}

function validaCompro(component, idReembolso) {

    var chkxPreLiquiAd = 'chkxPreLiquiAd';

    if (idReembolso != null) {
        chkxPreLiquiAd = chkxPreLiquiAd + idReembolso;
    }

    if ($(component).is(':checked')) {
        $('#' + chkxPreLiquiAd).css('display', 'none');
    } else {
        $('#' + chkxPreLiquiAd).css('display', 'block');
    }
}

var toog1 = 0;
var error1 = 0;
Dropzone.autoDiscover = false;
var myDropzone1 = null;
var dropZ = this;
var nombreFile = null;

var flgRefrescaTabla = 0;

$("#dzDetalleItem").dropzone({
    url: "subirEviIPEstDet",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 900,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",
    success: function (file, response) {
        data = JSON.parse(response);
        if (data.error == 0) {
            modal('modalRegistrarEntidades');
            mostrarNotificacion('success', data.msj);
        } else {
            mostrarNotificacion('error', data.msj);
        }
    },
    complete: function (file) {
        if (file.status == "success") {
            error1 = 0;
            nombreFile = file.name;
            this.removeAllFiles(true);
        }
    },
    removedfile: function (file, serverFileName) {
        var name = file.name;
        var element;
        (element = file.previewElement) != null ?
            element.parentNode.removeChild(file.previewElement) :
            false;
        toog1 = toog1 - 1;
    },
    init: function () {
        this.on("error", function (file, message) {
            alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
            return;
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error1 = 1;
            // alert(message);
            this.removeAllFiles(true);
        });

        // var submitButton = document.querySelector("#btnSaveIpEstDet")
        myDropzone1 = this;

        this.on("addedfile", function () {
            toog1 = toog1 + 1;
            // modal('modalSubirEvidencia');
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {
            if (error1 == 0) {

            }
        });
        this.on('sending', function (file, xhr, formData) {
            var codExpediente = $.trim($("#txtCodExp" + idIpEntLicGlob).val());
            var flgTipoLic    = $("#tipoLic" + idIpEntLicGlob).val();
            var distrito      = $("#distEnt" + idIpEntLicGlob).val();// puede ser nulo
            var fecIniLic 	  = $("#txtFechaIni" + idIpEntLicGlob).val();
			var fecFinLic 	  = $("#txtFechaFin" + idIpEntLicGlob).val();
			
            formData.append('iditemplanEstaDet', idIpEntLicGlob);
            formData.append('codExpediente', codExpediente);
            formData.append('flgTipoLic', flgTipoLic);
            formData.append('distrito', distrito);
            formData.append('fechaInicio', fecIniLic);
            formData.append('fechaFin', fecFinLic);
        });
    }
});

function liquidarDetalle(component, index) {
    idIpEntLicGlob = $(component).data('idipestlic');
    if (idIpEntLicGlob == null || idIpEntLicGlob == undefined || idIpEntLicGlob == '') {
        mostrarNotificacion('error', 'Hubo un error al recibir los datos!!')
        return;
    } else {
        var codExpediente = $.trim($("#txtCodExp" + idIpEntLicGlob).val());
        var flgTipoLic = $("#tipoLic" + idIpEntLicGlob).val();
        var distrito = $("#distEnt" + idIpEntLicGlob).val();// puede ser nulo
        var fecIniLic = $("#txtFechaIni" + idIpEntLicGlob).val();
        var fecFinLic = $("#txtFechaFin" + idIpEntLicGlob).val();

        if(flgTipoLic == 0){
            flgTipoLic = null;
        }

        jsonValida = { codExpediente: codExpediente, flgTipoLic: flgTipoLic, fecIniLic: fecIniLic, fecFinLic: fecFinLic };
        if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
            mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
            return;
        } else {
            swal({
                title: 'Desea guardar los cambios??',
                text: 'Asegurese de validar la informacion!!',
                type: 'warning',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'OK!'

            }).then(function () {
                myDropzone1.processQueue();
            });
        }
    }
}

var toog2 = 0;
var error2 = 0;
var myDropzone2 = null;
var nombreFile2 = null;

var flgRefrescaTabla = 0;

$("#dzDetalleComprobante").dropzone({
    url: "saveUpdateCompro",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 900,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",
    success: function (file, response) {
        data = JSON.parse(response);
        if (data.error == 0) {
            modal('modalComprobantes');
            modal('modalRegistrarEntidades');
            mostrarNotificacion('success', data.msj);
        } else {
            mostrarNotificacion('error', data.msj);
        }
    },
    complete: function (file) {
        if (file.status == "success") {
            error2 = 0;
            nombreFile2 = file.name;
            this.removeAllFiles(true);
        }
    },
    removedfile: function (file, serverFileName) {
        var name = file.name;
        var element;
        (element = file.previewElement) != null ?
            element.parentNode.removeChild(file.previewElement) :
            false;
        toog2 = toog2 - 1;
    },
    init: function () {
        this.on("error", function (file, message) {
            alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
            return;
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error2 = 1;
            // alert(message);
            this.removeAllFiles(true);
        });

        // var submitButton = document.querySelector("#btnSaveIpEstDet")
        myDropzone2 = this;

        this.on("addedfile", function () {
            toog2 = toog2 + 1;
            // modal('modalSubirEvidencia');
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {
            if (error2 == 0) {

            }
        });
        this.on('sending', function (file, xhr, formData) {


            var txtReembolso = 'txtDescCompro';
            var txtFechaEmiCompro = 'txtFechaEmiCompro';
            var txtMontoCompro = 'txtMontoCompro';
            var chxValidaCompro = 'chkValidaCompro';
            var chkxPreLiquiAd = 'chkxPreLiquiAd';

            if (idReembolsoGlob != null) {
                txtReembolso = txtReembolso + idReembolsoGlob;
                txtFechaEmiCompro = txtFechaEmiCompro + idReembolsoGlob;
                txtMontoCompro = txtMontoCompro + idReembolsoGlob;
                chxValidaCompro = chxValidaCompro + idReembolsoGlob;
                chkxPreLiquiAd = chkxPreLiquiAd + idReembolsoGlob;
            }

            var desc_reembolso = $.trim($('#' + txtReembolso).val());
            var fecha_emision = $('#' + txtFechaEmiCompro).val();
            var monto = $('#' + txtMontoCompro).val();
            var flgValidaCompro = null;
            var flgPreliqui = null;

            var flgPreliquiAdmin = document.getElementById(chkxPreLiquiAd).checked;
            var flgValida = document.getElementById(chxValidaCompro).checked;

            if (flgPreliquiAdmin == true) {
                flgPreliqui = '1';
            } else {
                flgPreliqui = '0';
            }
            if (flgValida == true) {
                flgValidaCompro = '1';
            } else {
                flgValidaCompro = '0';
            }

            formData.append('iditemplanEstaDet', idIpEntLicGlob);
            formData.append('idReembolso', idReembolsoGlob);
            formData.append('desc_reembolso', desc_reembolso);
            formData.append('fecha_emision', fecha_emision);
            formData.append('monto', monto);
            formData.append('flgPreliqui', flgPreliqui);
            formData.append('flgValidaCompro', flgValidaCompro);
            formData.append('flgTipoTransacGlob', flgTipoTransacGlob);
        });
    }
});

function descargarPDFEntidad(component, flgRuta, flgProv) {
    var idIpEntLic = $(component).data('idipestlic');
    if (flgRuta == 2) {
        $.ajax({
            type: 'POST',
            'url': 'getRutaEviIPEstaDet',
            data: {
                idItemPlanEstaDetalle: idIpEntLic
            },
            'async': false

        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                window.open(data.rutaImagen);
            } else if (data.error == 1) {
                mostrarNotificacion('error', 'No hay PDF para mostrar');
            }
        });

    }

}

function deleteIPEstDetLic(component) {
    idIpEntLicGlob = $(component).data('idipestlic');
    var itemplan = $(component).data('itemplan');
    var idEstacion = $(component).data('idestacion');
    if (idIpEntLicGlob != null && idIpEntLicGlob != undefined) {
        
        swal({
                title: 'Esta seguro de eliminar el comprobante??',
                text: 'Asegurese de validar la informacion!!',
                type: 'warning',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'OK!'

            }).then(function () {
                $.ajax({
                    type: 'POST',
                    'url': 'deleteIPEstDetLic',
                    data: {
                        idItemPlanDet: idIpEntLicGlob,
                        itemplan: itemplan,
                        idEstacion : idEstacion
                    },
                    'async': false
        
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {//elimino correctamente el registro
                        modal('modalRegistrarEntidades');
                        mostrarNotificacion('success', data.msj);
                    } else {
                        mostrarNotificacion('error', data.msj);
                    }
                });
            });
    }
}

var idReembolsoGlob = null;
var flgTipoTransacGlob = null;
var rutaComproGlob = null;

function saveComprobante(component, flgTipoTransac) {
    var idReembolso = null;
    flgTipoTransacGlob = flgTipoTransac;

    if (flgTipoTransac == 2) {
        idReembolso = $(component).data('idreembolso');
        rutaComproGlob = $(component).data('rutapdf');
        idReembolsoGlob = idReembolso;
    }


    if (idIpEntLicGlob == null || idIpEntLicGlob == undefined || idIpEntLicGlob == '') {
        mostrarNotificacion('error', 'Hubo un error al recibir los datos!!')
        return;
    } else {

        var txtReembolso = 'txtDescCompro';
        var txtFechaEmiCompro = 'txtFechaEmiCompro';
        var txtMontoCompro = 'txtMontoCompro';
        var chxValidaCompro = 'chkValidaCompro';
        var chkxPreLiquiAd = 'chkxPreLiquiAd';

        if (idReembolso != null) {
            txtReembolso = txtReembolso + idReembolso;
            txtFechaEmiCompro = txtFechaEmiCompro + idReembolso;
            txtMontoCompro = txtMontoCompro + idReembolso;
            chxValidaCompro = chxValidaCompro + idReembolso;
            chkxPreLiquiAd = chkxPreLiquiAd + idReembolso;
        }

        var desc_reembolso = $('#' + txtReembolso).val();
        var fecha_emision = $('#' + txtFechaEmiCompro).val();
        var monto = $('#' + txtMontoCompro).val();

        jsonValida = { desc_reembolso: desc_reembolso, fecha_emision: fecha_emision, monto: monto };
        if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
            mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
            return;
        } else {
            if (rutaComproGlob != null && rutaComproGlob != undefined && rutaComproGlob != 0 && rutaComproGlob != '') {
                var flgValidaCompro = null;
                var flgPreliqui = null;

                var flgPreliquiAdmin = document.getElementById(chkxPreLiquiAd).checked;
                var flgValida = document.getElementById(chxValidaCompro).checked;

                if (flgPreliquiAdmin == true) {
                    flgPreliqui = '1';
                } else {
                    flgPreliqui = '0';
                }
                if (flgValida == true) {
                    flgValidaCompro = '1';
                } else {
                    flgValidaCompro = '0';
                }
                $.ajax({
                    type: 'POST',
                    'url': 'updateComproV2',
                    data: {
                        iditemplanEstaDet: idIpEntLicGlob,
                        idReembolso: idReembolsoGlob,
                        desc_reembolso: desc_reembolso,
                        fecha_emision: fecha_emision,
                        monto: monto,
                        flgPreliqui: flgPreliqui,
                        flgValidaCompro: flgValidaCompro,
                        flgTipoTransacGlob: flgTipoTransacGlob
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        modal('modalComprobantes');
                        modal('modalRegistrarEntidades');
                        mostrarNotificacion('success', data.msj);
                    } else {
                        mostrarNotificacion('error', data.msj);
                    }
                });

            } else {
                myDropzone2.processQueue();
            }
            rutaComproGlob = null

        }


    }

}

function descargarPDFCompro(component, flgRuta, flgProv) {
    idReembolsoGlob = $(component).data('idreembolso');
    if (flgRuta == 2) {
        $.ajax({
            type: 'POST',
            'url': 'getRutaEviReembolso',
            data: {
                idReembolso: idReembolsoGlob
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                window.open(data.rutaImagen);
            } else {
                mostrarNotificacion('error', 'No hay comprobante para mostrar');
            }
        });
    }

}

function desactivaBtnCompro(idIPEstaLic){
    var idIpEntLic = idIPEstaLic;

    if (idIpEntLic == null || idIpEntLic == undefined || idIpEntLic == '') {
        mostrarNotificacion('error', 'Hubo un error al recibir los datos!!')
        return;
    } else {
        var flgTipoLic = $("#tipoLic" + idIpEntLic).val();
        if(flgTipoLic == 1){
            $("#btnComprobante"+idIpEntLic).css("display","none");
        }else{
            $("#btnComprobante"+idIpEntLic).css("display","block");
        }
    }
}

function preliqAdmin(component, idReembolso) {

    var options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    var txtReembolso = 'txtDescReembolso';
    var txtFechaEmiCompro = 'txtFechaEmiCompro';
    var txtMontoCompro = 'txtMontoCompro';
    var chxValidaCompro = 'chkValidaCompro';

    if (idReembolso != null) {
        txtReembolso = txtReembolso + idReembolso;
        txtFechaEmiCompro = txtFechaEmiCompro + idReembolso;
        txtMontoCompro = txtMontoCompro + idReembolso;
        chxValidaCompro = chxValidaCompro + idReembolso;
    }

    if ($(component).is(':checked')) {
        $('#' + txtReembolso).val('XXX');
        $('#' + txtFechaEmiCompro).val(((new Date()).toLocaleDateString('ja-JP', options)).split('/').join('-'));
        $('#' + txtMontoCompro).val(0);
        $('#' + txtReembolso).attr('disabled', true);
        $('#' + txtFechaEmiCompro).attr('disabled', true);
        $('#' + txtMontoCompro).attr('disabled', true);
        $('#' + chxValidaCompro).css('display', 'none');
    } else {
        $('#' + txtReembolso).val(null);
        $('#' + txtFechaEmiCompro).val(null);
        $('#' + txtMontoCompro).val(null);
        $('#' + txtReembolso).attr('disabled', false);
        $('#' + txtFechaEmiCompro).attr('disabled', false);
        $('#' + txtMontoCompro).attr('disabled', false);
        $('#' + chxValidaCompro).css('display', 'block');
    }

}

function cerrarModalEviCompro() {
    modal('modalSubirFotoComprobante');
}

function descargarArchivoFuit(ubicArchivo) {
    if(ubicArchivo == null || ubicArchivo == '') {
        mostrarNotificacion('error', 'no se encuentra archivo');
        return;
    }
    window.open(ubicArchivo);
}

var jsonDataKitGlobal = {};
var arrayDataKitGlobal = [];
var costoTotalxMat = 0;
var sumTotalGlobal = 0;
function getDataInsert(idMaterial, cont, costoMaterial) {
    var sumTotal = 0;
    var cantidad = $('#inputCantidad_'+cont).val();
    var contador = 0;

    // cantidadIngresoAnteriorGlobal = $('#cantidadObra_'+cont).val();

    // if(cantidadIngresoAnteriorGlobal == null || cantidadIngresoAnteriorGlobal == '') {
    //     return;
    // }
    $('#inputTotal_'+cont).val((Number(cantidad)* Number(costoMaterial)).toFixed(2));

    arrayDataKitGlobal.forEach(function(data, key){
        if(data.codigo_material == idMaterial) {
            contador = 1;
            jsonDataKitGlobal.codigo_material  = idMaterial;
            jsonDataKitGlobal.cantidad_ingreso = cantidad;
            jsonDataKitGlobal.cantidad_final   = cantidad;
            jsonDataKitGlobal.costoMaterial    = costoMaterial;
            arrayDataKitGlobal.splice(key, 1, jsonDataKitGlobal);
            jsonDataKitGlobal = {};
        } 
    });

    if(contador == 0) {
        jsonDataKitGlobal.codigo_material  = idMaterial;
        jsonDataKitGlobal.cantidad_ingreso = cantidad;
        jsonDataKitGlobal.cantidad_final   = cantidad;
        jsonDataKitGlobal.costoMaterial    = costoMaterial;
        arrayDataKitGlobal.splice(arrayDataKitGlobal.length, 0, jsonDataKitGlobal);
        jsonDataKitGlobal = {};
    }
    total(sumTotal);
}

function total(sumTotal) {
    arrayDataKitGlobal.forEach(function(data, key){
        costoTotalxMat = (Number(data.cantidad_ingreso)* Number(data.costoMaterial)).toFixed(2);
        sumTotal       = (Number(costoTotalxMat) + Number(sumTotal)).toFixed(2);
    });
    $('#totalMat').html('TOTAL: '+sumTotal);
    sumTotalGlobal = sumTotal;
}

function insertInfoPOPiloto() {
    if(itemplanGlobal == null || itemplanGlobal == '') {
        return;
    }

    $.ajax({
        url  : 'insertInfoPOPiloto',
        type : 'POST',
        data : { 
                    totalPo         : sumTotalGlobal,
                    arrayDetalleKit : arrayDataKitGlobal,
                    itemplan        : itemplanGlobal
                }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            mostrarNotificacion('success', 'Se ingreso correctamente', 'confirmado');
            $('#btnFluidOcho').css('display', 'none');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

function openAgendamiento(flgTipo) {
    if(placaGlobal == '' || placaGlobal == null) {
        placaGlobal = $('#idCmbPlaca option:selected').val();
    }
    var a = document.createElement("a");
    a.target = "_blank";
    a.href = "getAgendamientoProcesoPiloto?flgTipo="+flgTipo+"&placa="+placaGlobal+"&itemplan="+itemplanGlobal;
    a.click();
}
