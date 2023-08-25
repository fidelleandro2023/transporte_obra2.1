/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    getPep();
});


function addNuevaPep() {
    limpiar();
    $('#modalMoviles').modal('show');
    $('#boton_multiuso').attr("onclick", 'addMovilesPep()');
}

function addMovilesPep() {
    var pep2 = $('#pep2').val();
    var selecIdSubProyecto = $('#selecIdSubProyecto').val();
    var selecIdPromotor = $('#selecIdPromotor').val();
    var selecIdTipo = $('#selecIdTipo').val();

    if (pep2 === '' || pep2 === null) {
        swal('Mensaje', 'Ingrese PEP', 'warning');
        $("#pep2").css("border", "1px solid red ");
        $("#pep2").css("border-radius", "10px");
        $("#pep2").focus();
        setTimeout(function () {
            $("#pep2").css("border", "");
        }, 3000);
        return false;
    }
    if (selecIdSubProyecto === '' || selecIdSubProyecto === null) {
        swal('Mensaje', 'Ingrese Subproyecto', 'warning');
        $("#selecIdSubProyecto").css("border", "1px solid red ");
        $("#selecIdSubProyecto").css("border-radius", "10px");
        $("#selecIdSubProyecto").focus();
        setTimeout(function () {
            $("#selecIdSubProyecto").css("border", "");
        }, 3000);
        return false;
    }
    if (selecIdPromotor === '' || selecIdPromotor === null) {
        swal('Mensaje', 'Ingrese Promotor', 'warning');
        $("#selecIdPromotor").css("border", "1px solid red ");
        $("#selecIdPromotor").css("border-radius", "10px");
        $("#selecIdPromotor").focus();
        setTimeout(function () {
            $("#selecIdPromotor").css("border", "");
        }, 3000);
        return false;
    }
    if (selecIdTipo === '' || selecIdTipo === null) {
        swal('Mensaje', 'Ingrese Tipo', 'warning');
        $("#selecIdTipo").css("border", "1px solid red ");
        $("#selecIdTipo").css("border-radius", "10px");
        $("#selecIdTipo").focus();
        setTimeout(function () {
            $("#selecIdTipo").css("border", "");
        }, 3000);
        return false;
    } else {
        var dataString = 'pep2=' + pep2
                + '&selecIdSubProyecto=' + selecIdSubProyecto
                + '&selecIdPromotor=' + selecIdPromotor
                + '&selecIdTipo=' + selecIdTipo;

        console.log(dataString);

        $.ajax({
            type: 'POST',
            url: 'ajaxSavePep',
            data: dataString
        }).done(function (data) {
            console.log(data.msj);
            data = JSON.parse(data);
            console.log(data.error == 0);
            if (data.error == 0) {
                mostrarNotificacion('success', 'Mensaje', data.msj);
                $('#modalMoviles').modal('hide');
                getPep();
                initDataTable('#tbPep');
            } else {
                mostrarNotificacion('error', 'Mensaje', data.msj);
            }
        });
    }
}

function getPep() {
    var busSubPro = $('#busSubPro').val();
    var busPep = $('#busPep').val();
    var dataString = 'busSubPro=' + busSubPro
            + '&busPep=' + busPep;
    console.log(dataString);
    $.ajax({
        type: 'POST',
        url: 'ajaxGetPep',
        data: dataString
    }).done(function (data) {
        console.log(data);
        data = JSON.parse(data);
        console.log(data.error == 0);
        if (data.error == 0) {
            $('#contTabla').html(data.tabla)
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error', 'Mensaje', data.msj);
        }
    });
}

function limpiar() {
    $('#pep2').val('');
    $('#selecIdSubProyecto').val(null).trigger('change');
    $('#selecIdPromotor').val(null).trigger('change');
    $('#selecIdTipo').val(null).trigger('change');
    $("#pep2").css("border", "");
    $("#selecIdSubProyecto").css("border", "");
    $("#selecIdPromotor").css("border", "");
    $("#selecIdTipo").css("border", "");
}
