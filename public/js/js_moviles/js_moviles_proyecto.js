/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
//    getPep();
});


function addNuevaPep() {
    limpiar();
    $('#modalMoviles').modal('show');
    $('#boton_multiuso').attr("onclick", 'addMovilesPep()');
}


function getPep() {
    $.ajax({
        type: 'GET',
        url: 'ajaxGetPepProyecto'
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
