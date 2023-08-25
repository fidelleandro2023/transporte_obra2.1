
/* global swal */

var solicitudGlob = null;
var accionGlob = null;
var costoFinGlob = null;
//inicClass();
// function inicClass() {
    // $(".atenderSol").on('click', function (event) {

    // });
// }

var origenAprobGlb = null;
var idEstacionAprobGlb = null;
var costoPoGlb = null;
function openModalAtender(btn) {
	var accion 	   = btn.data('acc');
	var solicitud  = btn.data('sol');
	var costoFinal = btn.data('cos');
	idEstacionAprobGlb = btn.data('id_estacion');
	
	origenAprobGlb = btn.data('origen');
	solicitudGlob = solicitud;
	accionGlob = accion;
	costoFinGlob = costoFinal;
	$('#inputComentario').val('');
	$('#costoFinal').val('');
	console.log("ACCION: "+accion);
	if (accion == 1) {
		costoPoGlb    = btn.data('costo_po');

		$('#tituloValidacion').html('APROBAR SOLICITUD');
		$('#contCostoFinal').show();
		$('#costoFinal').val(costoFinal);
		soloDecimal('costoFinal');
	} else if (accion == 2) {
		costoPoGlb    = btn.data('costo_po');
		$('#tituloValidacion').html('RECHAZAR SOLICITUD');
		$('#contCostoFinal').hide();
	}

	modal('modAtenderSolicitud');
	console.log('accion:' + accion + ', solicitud:' + solicitud);
}


$(".attSol").on('click', function (event) {
    var accion = accionGlob;
    var solicitud = solicitudGlob;
    var costoFinIni = Number(costoFinGlob);
    var comentario = $('#inputComentario').val();
    var costoFinal = Number($('#costoFinal').val());
    var textoTipoAccion = '';
    console.log('costoFinIni:' + costoFinIni);
    console.log('costoFinal:' + costoFinal);
    if (accion === 1) {
        textoTipoAccion = 'APROBAR';
		
		if(origenAprobGlb != 6) {
			if(costoPoGlb == null || costoPoGlb == '' || costoPoGlb == 0) {
				return;
			}
		}
    } else if (accion === 2) {
        textoTipoAccion = 'RECHAZAR';
    }
    if (accion === 1 && costoFinal < costoFinIni) {
        mostrarNotificacion('error', 'Costo Final no valido, debe ingresar un costo mayor o igual al Solicitado: S/.' + formatearNumeroComas(costoFinIni));
        //alert('Costo Final no valido, debe ingresar un costo mayor o igual al Solicitado: S/.'+formatearNumeroComas(costoFinIni));
        return;
    }
    if (comentario === '') {
        mostrarNotificacion('error', 'Debe Ingresar un comentario');
        //alert('Debe Ingresar un comentario');
        return;
    }
	
	// if(origenAprobGlb == null || origenAprobGlb == '') {
		// console.log("NO HAY ORIGEN");
		// return;
	// }

    swal({
        title: 'Esta seguro de ' + textoTipoAccion + ' la solicitud?',
        text: 'Asegurese de que la informacion llenada sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, guardar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function () {
        $.ajax({
            type: 'POST',
            url: 'validSolCP',
            data: {	accion      : accion,
					solicitud   : solicitud,
					comentario  : comentario,
					costoFinal  : costoFinal,
					costoFinIni : costoFinIni,
					origen      : origenAprobGlb,
					idEstacion  : idEstacionAprobGlb,
				    costoPo  	: costoPoGlb	}
        }).done(function (data) {
            var data = JSON.parse(data);
			console.log(data);
            if (data.error == 0) {
                modal('modAtenderSolicitud');
                swal({
                    title: 'Se realizo la Operacion!',
                    text: 'Asegurese de validar la informacion!',
                    type: 'success',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'OK!'
                });
                filtrarTablaCP();
            } else if (data.error ==1) {
                mostrarNotificacion('error', data.msj);
            }
        });
    }, function (dismiss) {
        console.log('cancelado...');
    });
});

function filtrarTablaCP() {
    console.log('filtrar tabla.....');

    var situacion = $('#cmbSituacion option:selected').val();
    var area = $('#cmbArea option:selected').val();
    var itemplan = $('#txtItemplan').val();

    $.ajax({
        type: 'POST',
        url: 'filtrarTbConPre',
        data: {situacion: situacion,
            area: area,
            itemplan: itemplan,
            idBandeja: '1'}
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            $('#contTabla').html(data.tablaBandejaCP);
            initDataTable('#data-table');
            // inicClass();
        } else {
            mostrarNotificacion('error', 'error', data.msj);
        }
    });
}

function openMdlDetalleExceso(btn) {
	var id_solicitud = btn.data('id_solicitud');
	var origen       = btn.data('origen');
	
	if(id_solicitud == null || id_solicitud == '') {
		return;
	}
	
	if(origen == null || origen == '') {
		return;
	}
	
	$.ajax({
		type : 'POST',
		url  : 'openMdlDetalleExceso',
	    data : { id_solicitud : id_solicitud,
                 origen       : origen		}
	}).done(function(data){
		data = JSON.parse(data);
		
		if(data.error == 0) {
			
			$('#contTablaSolicitud').html(data.tablaDetalleSolicitud);
			initDataTable('#tbDetalleSolicitud');
			
			$('#txtComentario').html(data.htmlComentario);
			
			modal('modDetalleSolicitud');
			// $('#contTablaPo').html(data.tablaDetallePo);
			// initDataTable('#tbDetallePo');
		} else {
			return;
		}
	});
}