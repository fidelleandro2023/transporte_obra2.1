/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {

    $('#inputMontoMO').numeric();
    $('#inputMontoMAT').numeric();
    $('#valeReserva').numeric();
//    var d = new Date();
//    var strDate = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
//    $('#inputcreacion').val(strDate);

});

function consultaItemfault() {
    var selectServicio = $('#selectServicio').val();
    var selectElementoServicio = $('#selectElementoServicio').val();
    var inputcreacion = $('#inputcreacion').val();
    var inputItemfaut = $('#inputItemfaut').val();
    var inputNombrePlan = $('#inputNombrePlan').val();
    var selectEstado = $('#selectEstado').val();
    var selectGerencia = $('#selectGerencia').val();
    var selectEvento = $('#selectEvento').val();
    var selectSubEvento = $('#selectSubEvento').val();

    if (selectServicio === '' && selectElementoServicio === '' && inputcreacion === '' && inputItemfaut === '' && inputNombrePlan === '' && selectEstado === '' && selectGerencia === '' && selectEvento === '' && selectSubEvento === '') {
        mostrarNotificacion('error', 'Debe de seleccionar al menos un filtro', 'error');
    } else {
        $.ajax({
            type: 'POST',
            url: 'ajaxBandeja',
            data: {selectServicio: selectServicio, selectElementoServicio: selectElementoServicio, inputcreacion: inputcreacion,
                inputItemfaut: inputItemfaut, inputNombrePlan: inputNombrePlan,
                selectEstado: selectEstado, selectGerencia: selectGerencia,
                selectEvento: selectEvento, selectSubEvento: selectSubEvento
            }
        }).done(function (data) {
            console.log(data);
            data = JSON.parse(data);
            console.log(data);
            if (data.error == 0) {
                $('#contTabla').html(data.tablaConsultaItemfault);
                initDataTable('#tbDataConsulta');
            } else {
                mostrarNotificacion('error', data.msj, 'error');
            }
        });
    }
}

function detalleSub(idSubProyecto, number, estado) {

    if (number == 0) {
        mostrarNotificacion('warning', '', 'No hay datos que mostrar');
    } else {
        $('#tbDetalleSla').empty();
        $.ajax({
            type: 'POST',
            url: 'ajaxReporteDetalleSub',
            data: {idSubProyecto: idSubProyecto, estado: estado}
        }).done(function (data) {
            data = JSON.parse(data);
            console.log(data.tablaDetalleSub);
            if (data.error == 0) {
                if (data.tablaDetalleSub) {
                    $('#btn_excel_detalle').attr('href', "ajaxExcelDetalle?" + "idSubProyecto=" + idSubProyecto + '&estado=' + estado);
                    $('#divTablaDetalleSub').html(data.tablaDetalleSub);
                    initDataTable('#tbDetalleSub');
                    $("#modalReporteSub").modal("show");
                } else {
                    mostrarNotificacion('warning', data.msj, 'No hay datos que mostrar');
                }
            } else {
                mostrarNotificacion('warning', data.msj, 'No hay datos que mostrar');
            }
        });
    }

}
function modificar_requerimiento(itemfault) {
//    alert(itemfault);$('#archivo_pdf').fileinput('reset');
    $('#inputMontoMO').val('');
    $('#inputMontoMAT').val('');
    $('#boton_multiuso').attr("onclick", 'agregarCotizacion(' + "'" + itemfault + "'" + ')');
    $('#modalExpediente').modal('show');
}

function agregarCotizacion(itemfault) {
    var inputMontoMO = $('#inputMontoMO').val();
    var inputMontoMAT = $('#inputMontoMAT').val();
    var archivo_pdf = $('#archivo_pdf')[0].files[0];

    if (inputMontoMO === '' || inputMontoMO === null) {
        swal('Mensaje', 'Ingrese monto de Mano de Obra', 'warning');
        $("#inputMontoMO").css("border", "1px solid red ");
        $("#inputMontoMO").css("border-radius", "10px");
        $("#inputMontoMO").focus();
        setTimeout(function () {
            $("#inputMontoMO").css("border", "");
        }, 3000);
        return false;
    }
    if (inputMontoMAT === '' || inputMontoMAT === null) {
        swal('Mensaje', 'Ingrese monto de Material', 'warning');
        $("#inputMontoMAT").css("border", "1px solid red");
        $("#inputMontoMAT").css("border-radius", "10px");
        $("#inputMontoMAT").focus();
        setTimeout(function () {
            $("#inputMontoMAT").css("border", "");
        }, 3000);
        return false;
    }
    if (archivo_pdf === '' || archivo_pdf === null) {
        swal('Mensaje', 'Selecciones un archivo', 'warning');
        $("#archivo_pdf").css("border", "1px solid red");
        $("#archivo_pdf").focus();
        return false;
    } else {
        var formData = new FormData();
        formData.append("inputMontoMO", inputMontoMO);
        formData.append("inputMontoMAT", inputMontoMAT);
        formData.append("archivo_pdf", archivo_pdf);
        formData.append("itemfault", itemfault);
        $.ajax({
            type: 'POST',
            url: 'ajaxActualizar',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            console.log(data);
            data = JSON.parse(data);
            if (data.error == 0) {
                $('#modalExpediente').modal('hide');
                notificacion('success', data.msj, ':D');
                consultaItemfault();
            } else {
                notificacion('error', data.msj, ':c');
            }
        });
    }
}

function changeServicio() {
    console.log($('#selectServicio').val());
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'ajaxServicioElemento',
        data: {idServicio: $('#selectServicio').val()},
        'async': false
    }).done(function (data) {
        $('#selectElementoServicio').empty();
        $('#selectElementoServicio').append(data);
    });
}


function changeEvento() {
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'ajaxSubEvento',
        data: {idEvento: $('#selectEvento').val()},
        'async': false
    }).done(function (data) {
        $('#selectSubEvento').empty();
        $('#selectSubEvento').append(data);
    });
}

function notificacion(tipo, titulo, mensaje) {
    swal({
        title: titulo,
        text: mensaje,
        type: tipo,
        backdrop: false,
        allowOutsideClick: false,
        allowEscapeKey: false
    });
}

function aprobar_itemfault(idItemfault, codigo_itemfault_po) {
    $('#modalExpediente').modal('show');
    $('#boton_vale_reserva').attr("onclick", 'aprobarMatItemfault("' + idItemfault + '","' + codigo_itemfault_po + '")');
}



function aprobarMatItemfault(idItemfault, codigo_itemfault_po) {

    console.log(idItemfault);
    console.log(codigo_itemfault_po);

    var valeReserva = $('#valeReserva').val();

    if (valeReserva === '' || valeReserva === null) {
        swal('Mensaje', 'Ingrese Vale de Reserva');
        $("#valeReserva").css("border", "1px solid red ");
        $("#valeReserva").css("border-radius", "10px");
        $("#valeReserva").focus();
        setTimeout(function () {
            $("#valeReserva").css("border", "");
        }, 3000);
        return false;
    } else {
        var dataString = 'itemfault=' + idItemfault
                + '&codigo_po=' + codigo_itemfault_po
                + '&vale_reserva=' + valeReserva;
        $.ajax({
            type: 'POST',
            dataType: "JSON",
            'url': 'aprobarMatItemfault',
            data: dataString,
            'async': false
        }).done(function (data) {
            if (data.error === '0') {
                $('#contTabla').html(data.tablaConsultaItemfault);
                $('#modalExpediente').modal('hide');
                notificacion('success', 'Mensaje', data.msj);
            } else {
                $('#modalExpediente').modal('hide');
                notificacion('success', 'Mensaje', data.msj);
            }
        }).fail(function (data) {
            notificacion('error', 'Mensaje', data.msj);
        });
    }

}

function cancelar_itemfault(idItemfault) {
    swal({
        title: 'Esta seguro de cancelar el estado?',
        text: "Asegurese de validar la informacion seleccionada!",
        type: 'question',
        showCancelButton: true,
//        confirmButtonColor: '#3085d6',
//        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, cancelar estado',
        cancelButtonText: 'No'
    }).then((result) => {
        console.log(result);
        if (result === true) {
            $.ajax({
                type: 'POST',
                dataType: "JSON",
                'url': 'ajaxAprobarDiseno',
                data: {idItemfault: idItemfault, idEstadoItemfault: 8},
                'async': false
            }).done(function (data) {
                consultaItemfault();
                console.log(data);
                notificacion('success', 'Preaprobado', ':D');
            });
        }
    });
}

var itemfaultGlbExcel = null;
var codigoPoGlbExcel = null;
function generarExcelMat(component) {
    var codigoPO = $(component).data('codigo_po');
    var itemfault = $(component).data('itemfault');
    var idEmpresaColab = $(component).data('eecc');
    console.log(codigoPO);
    console.log(itemfault);
    console.log(idEmpresaColab);

    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'getExcelMatItemfault',
        data: {itemfault: itemfault, codigoPO: codigoPO, idEmpresaColab: idEmpresaColab},
        'async': false
    })
            .done(function (data) {
                console.log('llego al done');
                console.log(data);
                console.log('paso el parseo');
                console.log(data);
                console.log(data.rutaExcel);
                if (data.error === "0") {
                    console.log(data.rutaExcel);
                    location.href = data.rutaExcel;
                }
            })
}

function generarRar(itemFault) {
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'generarRar',
        data: {itemFault: itemFault}
    }).done(function (data) {
        console.log(data);
        if (data.path == '1') {
            location.href = 'generarRar_download?' + 'itemFault=' + itemFault;
        } else {
            alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
        }

    });
}


function alertValidacion(tipo, titulo, mensaje) {
    swal({
        title: titulo,
        text: mensaje,
        type: tipo
    });
}