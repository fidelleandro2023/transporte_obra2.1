$(document).ready(function () {
    $('#inputCecoAdd').numeric();
    $('#inputCuentaAdd').numeric();
    $('#inputAreaFuncional').numeric();
    $('#inputMontoFinalAdd').numeric();
});


function consultaCuentaOpex() {
    var selectEvento = $('#selectEvento').val();
    var selectAno = $('#selectAno').val();
    console.log(selectEvento);
    console.log(selectAno);
    if (selectEvento === null && selectAno === null) {
        mostrarNotificacion('warning', 'Debe de seleccionar al menos un filtro', '');
    } else {
        $.ajax({
            type: 'POST',
            url: 'ajaxBuscarOpex',
            data: {selectEvento: selectEvento, selectAno: selectAno}
        }).done(function (data) {
            console.log(data.tablaSla);
            data = JSON.parse(data);
            console.log(data);
            if (data.error == 0) {
                $('#contTabla').html(data.tablaConsultaConfigOpex);
            } else {
                mostrarNotificacion('error', data.msj, 'error');
            }
        });
    }
}

function getDataAllOpex() {
    $.ajax({
        type: 'POST',
        url: 'ajaxGetAllOpex',
        data: {}
    }).done(function (data) {
        console.log(data.tablaSla);
        data = JSON.parse(data);
        console.log(data);
        if (data.error == 0) {
            $('#contTabla').html(data.tablaConsultaConfigOpex);
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

function agregarCuentaOpex() {
    limpiar();
    $('#modalAddOpex').modal('show');
    $('#boton_multiuso').attr("onclick", 'addCuentaOpex()');
}

function addCuentaOpex() {
    var inputCecoAdd = $('#inputCecoAdd').val();
    var inputCuentaAdd = $('#inputCuentaAdd').val();
    var inputAreaFuncional = $('#inputAreaFuncional').val();
    var inputMontoFinalAdd = $('#inputMontoFinalAdd').val();
    var selectEventoAdd = $('#selectEventoAdd').val();
    var inputDescAdd = $('#inputDescAdd').val();
    var selectAnhoAdd = $('#selectAnhoAdd').val();

    if (inputCecoAdd === '' || inputCecoAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputCecoAdd").css("border", "1px solid red ");
        $("#inputCecoAdd").css("border-radius", "10px");
        $("#inputCecoAdd").focus();
        setTimeout(function () {
            $("#inputCecoAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (inputCuentaAdd === '' || inputCuentaAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputCuentaAdd").css("border", "1px solid red ");
        $("#inputCuentaAdd").css("border-radius", "10px");
        $("#inputCuentaAdd").focus();
        setTimeout(function () {
            $("#inputCuentaAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (inputAreaFuncional === '' || inputAreaFuncional === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputAreaFuncional").css("border", "1px solid red ");
        $("#inputAreaFuncional").css("border-radius", "10px");
        $("#inputAreaFuncional").focus();
        setTimeout(function () {
            $("#inputAreaFuncional").css("border", "");
        }, 3000);
        return false;
    }

    if (inputMontoFinalAdd === '' || inputMontoFinalAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputMontoFinalAdd").css("border", "1px solid red ");
        $("#inputMontoFinalAdd").css("border-radius", "10px");
        $("#inputMontoFinalAdd").focus();
        setTimeout(function () {
            $("#inputMontoFinalAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (selectEventoAdd === '' || selectEventoAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#selectEventoAdd").css("border", "1px solid red ");
        $("#selectEventoAdd").css("border-radius", "10px");
        $("#selectEventoAdd").focus();
        setTimeout(function () {
            $("#selectEventoAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (inputDescAdd === '' || inputDescAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputDescAdd").css("border", "1px solid red ");
        $("#inputDescAdd").css("border-radius", "10px");
        $("#inputDescAdd").focus();
        setTimeout(function () {
            $("#inputDescAdd").css("border", "");
        }, 3000);
        return false;
    }
    if (selectAnhoAdd === '' || selectAnhoAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#selectAnhoAdd").css("border", "1px solid red ");
        $("#selectAnhoAdd").css("border-radius", "10px");
        $("#selectAnhoAdd").focus();
        setTimeout(function () {
            $("#inputDescAdd").css("border", "");
        }, 3000);
        return false;
    } else {
        var dataString = 'inputCecoAdd=' + inputCecoAdd
                + '&inputCuentaAdd=' + inputCuentaAdd
                + '&inputAreaFuncional=' + inputAreaFuncional
                + '&inputMontoFinalAdd=' + inputMontoFinalAdd
                + '&selectEventoAdd=' + selectEventoAdd
                + '&inputDescAdd=' + inputDescAdd
                + '&selectAnhoAdd=' + selectAnhoAdd;

        console.log(dataString);

        $.ajax({
            type: 'POST',
            url: 'ajaxSaveOpex',
            data: dataString
        }).done(function (data) {
            console.log(data.msj);
            data = JSON.parse(data);
            console.log(data.error == 0);
            if (data.error == 0) {
                mostrarNotificacion('success', 'Mensaje', data.msj);
                getDataAllOpex();
                $('#modalAddOpex').modal('hide');
            } else {
                mostrarNotificacion('error', 'Mensaje', data.msj);
            }
        });
    }
}

function editar_opex(idOpex) {
    $.ajax({
        type: 'POST',
        url: 'ajaxGetOpexId',
        dataType: "JSON",
        data: {idOpex: idOpex}
    }).done(function (data) {
        console.log('--- data ----');
        console.log(data);
        console.log('--- JSON.parse(data ----');
        var option = []
        data.eventoOpex.forEach(element => console.log(option.push(element.idEvento)));
        if (data.cuentaOpex) {
            $('#inputCecoAdd').val(data.cuentaOpex[0]['ceco']);
            $('#inputCuentaAdd').val(data.cuentaOpex[0]['cuenta']);
            $('#inputAreaFuncional').val(data.cuentaOpex[0]['areFuncional']);
            $('#inputMontoFinalAdd').val(data.cuentaOpex[0]['monto_final']);
            $('#selectEventoAdd').select2("val", option);
            $('#selectEventoAdd').val(option).trigger('change');
            $('#selectAnhoAdd').select2("val", (data.cuentaOpex[0]['anho']));
            $('#selectAnhoAdd').val((data.cuentaOpex[0]['anho'])).trigger('change');
            $('#inputDescAdd').val(data.cuentaOpex[0]['opexDesc']);
            $('#boton_multiuso').attr("onclick", 'updateCuentaOpex(' + idOpex + ')');
            $('#modalAddOpex').modal('show');
        } else {
            mostrarNotificacion('error', 'Mensaje', 'No se puede obtener informacion');
        }
    });
}

function updateCuentaOpex(idOpex) {
    var inputCecoAdd = $('#inputCecoAdd').val();
    var inputCuentaAdd = $('#inputCuentaAdd').val();
    var inputAreaFuncional = $('#inputAreaFuncional').val();
    var inputMontoFinalAdd = $('#inputMontoFinalAdd').val();
    var selectEventoAdd = $('#selectEventoAdd').val();
    var inputDescAdd = $('#inputDescAdd').val();
    var selectAnhoAdd = $('#selectAnhoAdd').val();

    if (inputCecoAdd === '' || inputCecoAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputCecoAdd").css("border", "1px solid red ");
        $("#inputCecoAdd").css("border-radius", "10px");
        $("#inputCecoAdd").focus();
        setTimeout(function () {
            $("#inputCecoAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (inputCuentaAdd === '' || inputCuentaAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputCuentaAdd").css("border", "1px solid red ");
        $("#inputCuentaAdd").css("border-radius", "10px");
        $("#inputCuentaAdd").focus();
        setTimeout(function () {
            $("#inputCuentaAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (inputAreaFuncional === '' || inputAreaFuncional === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputAreaFuncional").css("border", "1px solid red ");
        $("#inputAreaFuncional").css("border-radius", "10px");
        $("#inputAreaFuncional").focus();
        setTimeout(function () {
            $("#inputAreaFuncional").css("border", "");
        }, 3000);
        return false;
    }

    if (inputMontoFinalAdd === '' || inputMontoFinalAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputMontoFinalAdd").css("border", "1px solid red ");
        $("#inputMontoFinalAdd").css("border-radius", "10px");
        $("#inputMontoFinalAdd").focus();
        setTimeout(function () {
            $("#inputMontoFinalAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (selectEventoAdd === '' || selectEventoAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#selectEventoAdd").css("border", "1px solid red ");
        $("#selectEventoAdd").css("border-radius", "10px");
        $("#selectEventoAdd").focus();
        setTimeout(function () {
            $("#selectEventoAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (inputDescAdd === '' || inputDescAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputDescAdd").css("border", "1px solid red ");
        $("#inputDescAdd").css("border-radius", "10px");
        $("#inputDescAdd").focus();
        setTimeout(function () {
            $("#inputDescAdd").css("border", "");
        }, 3000);
        return false;
    }
    if (selectAnhoAdd === '' || selectAnhoAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#selectAnhoAdd").css("border", "1px solid red ");
        $("#selectAnhoAdd").css("border-radius", "10px");
        $("#selectAnhoAdd").focus();
        setTimeout(function () {
            $("#inputDescAdd").css("border", "");
        }, 3000);
        return false;
    } else {
        var dataString = 'inputCecoAdd=' + inputCecoAdd
                + '&inputCuentaAdd=' + inputCuentaAdd
                + '&inputAreaFuncional=' + inputAreaFuncional
                + '&inputMontoFinalAdd=' + inputMontoFinalAdd
                + '&selectEventoAdd=' + selectEventoAdd
                + '&inputDescAdd=' + inputDescAdd
                + '&idOpex=' + idOpex
                + '&selectAnhoAdd=' + selectAnhoAdd;

        console.log(dataString);

        $.ajax({
            type: 'POST',
            url: 'ajaxUpdateOpex',
            data: dataString
        }).done(function (data) {
            console.log(data.msj);
            data = JSON.parse(data);
            console.log(data.error == 0);
            if (data.error == 0) {
                mostrarNotificacion('success', 'Mensaje', data.msj);
                getDataAllOpex();
                $('#modalAddOpex').modal('hide');
            } else {
                mostrarNotificacion('error', 'Mensaje', data.msj);
            }
        });
    }
}

function eliminar_opex(idOpex) {
    swal({
        title: 'Mensaje',
        text: "Desea cancelar la cuenta OPEX?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result) {
            $.ajax({
                type: 'POST',
                url: 'ajaxDeleteOpex',
                data: {idOpex: idOpex}
            }).done(function (data) {
                console.log(data.msj);
                data = JSON.parse(data);
                console.log(data.error == 0);
                if (data.error == 0) {
                    mostrarNotificacion('success', 'Mensaje', 'OPEX cancelada');
                } else {
                    mostrarNotificacion('error', 'Mensaje', data.msj);
                }
            });
        }
    });
}

function limpiar() {
    $('#inputCecoAdd').val(null);
    $('#inputCuentaAdd').val(null);
    $('#inputAreaFuncional').val(null);
    $('#inputMontoFinalAdd').val(null);
    $('#selectEventoAdd').select2("val", "");
    $('#selectEventoAdd').val(null).trigger('change');
    $('#inputDescAdd').val(null);
    $('#inputDescAdd').val(null);
}

function historialOpex(idOpex) {
    $.ajax({
        url: 'historiaTrans',
        type: "POST",
        data: {idOpex: idOpex},
        dataType: "JSON",
        success: function (data)
        {
            console.log(data);
//            $('#timeline').html(data);
//            $('#modal_form_usuario').modal('show');
//            $('#cabecera').html("HISTORIAL DEL REQUERIMIENTO #" + id);
            if (data.error == 0) {
                $('#modal_form_usuario').modal('show');
                $('#contTablaDetalle').html(data.tablaDetalle);
            } else {
                mostrarNotificacion('error', data.msj, 'error');
            }
        }
    });
}

