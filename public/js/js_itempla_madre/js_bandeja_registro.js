

function  modalItemplanHijos(itemplanMadre) {
    $('body').loading({
        message: 'Espere por favor...'
    });

    $.ajax({
        type: 'POST',
        'url': 'lstItemplanHijo',
        data: {itemplanMadre: itemplanMadre},
        'async': false
    }).done(function (data) {
        var data = JSON.parse(data);
        if (data.error == 0) {

            $('body').loading('destroy')
            $('#contTablaItemsHijos').html(data.tablaItemHijos);
            $("#modalItemplanHijos").modal('show');

            initDataTable('#data-table2');
        } else if (data.error == 1) {
            mostrarNotificacion('error', 'Hubo problemas al mostrar datos solicitados');
        }
    });
}

function  modalConPrioridad(itemplanMadre) {
    $('#NoPrioritario').hide();

    //
    $.ajax({
        type: 'POST',
        'url': 'getIP',
        data: {itemplanMadre: itemplanMadre},
    }).done(function (data) {
        var data = JSON.parse(data);
        if (data.error == 0) {
            console.log(data.datos[0]);
            $("#idSubProyecto").val(data.datos[0].idSubProyecto);
            $("#fecRecepcion").val(data.datos[0].fecha_registro);
            $("#inputNomCli").val(data.datos[0].nombre_cliente);
            $("#inputNumCar").val(data.datos[0].numero_carta);
            $("#textNombreMadre").val(data.datos[0].nombre);
            $("#carta_pdf").val(data.datos[0].carta_pdf);
            
            $("#carta").val(data.datos[0].carta_pdf);
            $('#btnUpdateCP').prop('disabled', false);
            $("#itemplanMadre").val(itemplanMadre);
            $("#fileuploadOP").val('');
            $('#infoOP').html('Actualizar Carta (PDF)');
            $("#modalEditarConPrioridad").modal('show');
            //
            $("#btnUpdateCP").attr("onclick", "saveConPrioridad()");
        } else if (data.error == 1) {
            mostrarNotificacion('error', 'Hubo problemas al mostrar datos solicitados');
        }
    });
}

function  modalSinPrioridad(itemplanMadre) {
    $('#NoPrioritario').show();

    $('#textMonto').numeric();
    //
    $.ajax({
        type: 'POST',
        'url': 'getIP',
        data: {itemplanMadre: itemplanMadre},
    }).done(function (data) {
        var data = JSON.parse(data);
        if (data.error == 0) {
            $("#idSubProyecto").val(data.datos[0].idSubProyecto);
            console.log(data.datos[0]);
            $("#fecRecepcion").val(data.datos[0].fecha_registro);
            $("#inputNomCli").val(data.datos[0].nombre_cliente);
            $("#inputNumCar").val(data.datos[0].numero_carta);
            $("#textNombreMadre").val(data.datos[0].nombre);
            $("#carta_pdf").val(data.datos[0].carta_pdf);
            $("#carta").val(data.datos[0].carta_pdf);
            //
            $("#textMonto").val(data.datos[0].costoEstimado.replace(",", ""));
            $("#selectPrioridad").select2("val", data.datos[0].idPrioridad);
            //
            $('#btnUpdateCP').prop('disabled', false);
            $("#itemplanMadre").val(itemplanMadre);
            $("#fileuploadOP").val('');
            $('#infoOP').html('Actualizar Carta (PDF)');
            $("#modalEditarConPrioridad").modal('show');
            //
            $("#btnUpdateCP").attr("onclick", "saveSinPrioridad()");
        } else if (data.error == 1) {
            mostrarNotificacion('error', 'Hubo problemas al mostrar datos solicitados');
        }
    });
}

function cambiar2() {
    var pdrs = document.getElementById('fileuploadOP').files[0].name;
    $("#carta_pdf").val('');
    $('#infoOP').html(pdrs);
    $("#carta").val(pdrs);
}

function saveConPrioridad() {
    console.log($("#carta_pdf").val());

    var archivo_pdf = $('#fileuploadOP')[0].files[0];
    var fecRecepcion = $("#fecRecepcion").val();
    var inputNomCli = $("#inputNomCli").val();
    var inputNumCar = $("#inputNumCar").val();
    var textNombreMadre = $("#textNombreMadre").val();
    var itemplanMadre = $("#itemplanMadre").val();
    var carta_pdf = $("#carta_pdf").val();

    if ($("#carta").val() == '' || $("#carta").val() == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Seleccione un archivo PDF, este Itemplan Madre esta sin PDF', 'warning');
        return false;
    }
    if (fecRecepcion == '' || fecRecepcion == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Seleccione una fecha de recepcion', 'warning');
        return false;
    }
    if (inputNomCli == '' || inputNomCli == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Ingrese nombre de cliente', 'warning');
        return false;
    }
    if (inputNumCar == '' || inputNumCar == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Ingrese numero de carta', 'warning');
        return false;
    }
    if (textNombreMadre == '' || textNombreMadre == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Ingrese nombre de Itemplan Madre', 'warning');
        return false;
    } else {
        $('#btnUpdateCP').prop('disabled', true);
        var formData = new FormData();
        formData.append("fileuploadOP", archivo_pdf);
        formData.append("itemplanMadre", itemplanMadre);
        formData.append("fecRecepcion", fecRecepcion);
        formData.append("inputNomCli", inputNomCli);
        formData.append("inputNumCar", inputNumCar);
        formData.append("textNombreMadre", textNombreMadre);
        formData.append("carta_pdf", carta_pdf);
        $.ajax({
            type: 'POST',
            url: 'updateCprio',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            console.log(data);
            data = JSON.parse(data);
            if (data.error == 0) {
                $("#modalEditarConPrioridad").modal('hide');
                $('#btnUpdateCP').prop('disabled', false);
                swal('Mensaje', data.msj, 'success');
                location.reload();
            } else {
                $('#btnUpdateCP').prop('disabled', false);
                swal('Mensaje', data.msj, 'warning');
            }
        });
    }
}

function saveSinPrioridad() {
    var archivo_pdf = $('#fileuploadOP')[0].files[0];
    var fecRecepcion = $("#fecRecepcion").val();
    var inputNomCli = $("#inputNomCli").val();
    var inputNumCar = $("#inputNumCar").val();
    var textNombreMadre = $("#textNombreMadre").val();
    var itemplanMadre = $("#itemplanMadre").val();
    var carta_pdf = $("#carta_pdf").val();
    var textMonto = $("#textMonto").val();
    var selectPrioridad = $("#selectPrioridad").val();
    var idpep = $("#idpep").val();

    if ($("#carta").val() == '' || $("#carta").val() == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Seleccione un archivo PDF, este Itemplan Madre esta sin PDF', 'warning');
        return false;
    }
    if ($("#presupuesto_pep").val() == '1') {
        $('#btnUpdateCP').prop('disabled', false);
        mostrarNotificacion('warning', 'Verificar', 'No se puede registrar CON PRIORIDAD, no cuenta con presupuesto');
        return false;
    }
    if (fecRecepcion == '' || fecRecepcion == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Seleccione una fecha de recepcion', 'warning');
        return false;
    }
    if (inputNomCli == '' || inputNomCli == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Ingrese nombre de cliente', 'warning');
        return false;
    }
    if (inputNumCar == '' || inputNumCar == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Ingrese numero de carta', 'warning');
        return false;
    }
    if (textNombreMadre == '' || textNombreMadre == null) {
        $('#btnUpdateCP').prop('disabled', false);
        swal('Mensaje', 'Ingrese nombre de Itemplan Madre', 'warning');
        return false;
    } else {
        $('#btnUpdateCP').prop('disabled', true);
        var formData = new FormData();
        formData.append("fileuploadOP", archivo_pdf);
        formData.append("itemplanMadre", itemplanMadre);
        formData.append("fecRecepcion", fecRecepcion);
        formData.append("inputNomCli", inputNomCli);
        formData.append("inputNumCar", inputNumCar);
        formData.append("textNombreMadre", textNombreMadre);
        formData.append("carta_pdf", carta_pdf);
        formData.append("textMonto", textMonto);
        formData.append("selectPrioridad", selectPrioridad);
        formData.append("idpep", idpep);
        $.ajax({
            type: 'POST',
            url: 'updateSprio',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            console.log(data);
            data = JSON.parse(data);
            if (data.error == 0) {
                $("#modalEditarConPrioridad").modal('hide');
                $('#btnUpdateCP').prop('disabled', false);
                swal('Mensaje', data.msj, 'success');
                location.reload();
            } else {
                $('#btnUpdateCP').prop('disabled', false);
                swal('Mensaje', data.msj, 'warning');
            }
        });
    }
}

function  busqItemplanMadre() {
    $.ajax({
        type: 'POST',
        'url': 'busqItemplaMadre',
        data: {itemplanMadre: $("#inputItemplanMadre").val()},
    }).done(function (data) {
        var data = JSON.parse(data);
        if (data.error == 0) {
            $('#contTablaItems').html(data.tablaItemMadre);
            initDataTable('#data-table');
//            initDataTable('#data-table');
        } else if (data.error == 1) {
            mostrarNotificacion('error', 'Hubo problemas al mostrar datos solicitados');
        }
    });
}

$("#textMonto").keyup(function () {
    if ($("#selectPrioridad").val() === '1') {
        $("#selectPrioridad").select2("val", "0");
        $("#idpep").val('');
//        $('#selectPrioridad').val('').trigger('chosen:updated');
    } else {
    }
});

$("#selectPrioridad").change(function () {

    $("#presupuesto_pep").val(2);
    if ($(this).val() === '1') {
        if ($("#textMonto").val() === null || $("#textMonto").val() === '') {
            $(this).val('0');
            mostrarNotificacion('warning', 'Mensaje', 'Primero debe rellenar el Monto');
            return false;
        }
        if ($("#idSubProyecto").val() === null || $("#idSubProyecto").val() === '') {
            $(this).val('0');
            mostrarNotificacion('warning', 'Mensaje', 'Primero debe selecionar el subproyecto');
            return false;
        } else {
            verificar_pep($("#textMonto").val(), $("#idSubProyecto").val());
        }
    } else {
        $("#idpep").val('');
    }
});

function verificar_pep(textMonto, idSubProyecto) {
    $.ajax({
        type: 'POST',
        url: 'getPepItemplanMadre',
        data: {textMonto: textMonto, cmbSubProyecto: idSubProyecto}
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data);
        if (data.error) {
            if (data.pep) {
                $("#presupuesto_pep").val(2);
                $("#idpep").val(data.pep);
                mostrarNotificacion('success', 'PEP con prespuesto', 'PEP: ' + data.pep);
            } else {
                $("#idpep").val('');
                $("#presupuesto_pep").val(1);
//                $("#selectPrioridad").select2("val", "1");
                mostrarNotificacion('success', 'Mensaje', 'PEP sin prespuesto');
            }
        }
    });
}
