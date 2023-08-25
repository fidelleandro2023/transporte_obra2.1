
var solicitudGlob = null;
var accionGlob = null;
var costoFinGlob = null;
//inicClass();
function inicClass() {
    $(".atenderSol").on('click', function (event) {
        var accion = $(this).attr('acc');
        var solicitud = $(this).attr('sol');
        var costoFinal = $(this).attr('cos');
        solicitudGlob = solicitud;
        accionGlob = accion;
        costoFinGlob = costoFinal;
        $('#inputComentario').val('');
        $('#costoFinal').val('');
        if (accion == 1) {
            $('#tituloValidacion').html('APROBAR SOLICITUD');
            $('#contCostoFinal').show();
            $('#costoFinal').val(costoFinal);
            soloDecimal('costoFinal');
        } else if (accion == 2) {
            $('#tituloValidacion').html('RECHAZAR SOLICITUD');
            $('#contCostoFinal').hide();
        }

        modal('modAtenderSolicitud');
        console.log('accion:' + accion + ', solicitud:' + solicitud);
    });
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
    if (accion == 1) {
        textoTipoAccion = 'APROBAR';
    } else if (accion == 2) {
        textoTipoAccion = 'RECHAZAR';
    }
    if (accion == 1 && costoFinal < costoFinIni) {
        mostrarNotificacion('error', 'Costo Final no valido, debe ingresar un costo mayor o igual al Solicitado: S/.' + formatearNumeroComas(costoFinIni));
        //alert('Costo Final no valido, debe ingresar un costo mayor o igual al Solicitado: S/.'+formatearNumeroComas(costoFinIni));
        return;
    }
    if (comentario == '') {
        mostrarNotificacion('error', 'Debe Ingresar un comentario');
        //alert('Debe Ingresar un comentario');
        return;
    }

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
            url: 'ajaxValidarExceso',
            data: {accion: accion,
                solicitud: solicitud,
                comentario: comentario,
                costoFinal: costoFinal,
                costoFinIni: costoFinIni}
        }).done(function (data) {
            var data = JSON.parse(data);
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
            } else if (data.error == 1) {
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
        url: 'ajaxTableData',
        data: {situacion: situacion,
            area: area,
            itemplan: itemplan}
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            $('#contTabla').html(data.tablaBandejaCP);
            initDataTable('#data-table');
            inicClass();
        } else {
            mostrarNotificacion('error', 'error', data.msj);
        }
    });
}