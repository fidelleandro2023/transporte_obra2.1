function openModalRegistrarBandaHoraria() {
    $('#validacion').html(null);
    $('#idHoraInicio').val(null);
    $('#idHoraFin').val(null);
    modal('modalRegistrarBandaHoraria');
}

function registrarBandaHoraria() {
    var horaInicio =  $('#idHoraInicio').val();
    var horaFin    = $('#idHoraFin').val();
    
    if(horaInicio == '' || horaInicio == null || horaFin == '' || horaFin == null) {
        return;
    }

    if(horaInicio > horaFin) {
        $('#validacion').html('<a style="color:red">La hora de Inicio no debe ser mayor a la hora Final</a>');
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'registrarBandaHoraria',
        data : { horaInicio : horaInicio,
                 horaFin    : horaFin }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            modal('modalRegistrarBandaHoraria');
            $('#contTablaBandaHoraria').html(data.tablaBandaHoraria); 
            mostrarNotificacion('success', 'Se registr&oacute; correctamente', 'correcto');
            initDataTable('#data-table');       
        } else {
            mostrarNotificacion('error', data.msj, 'no ingreso banda horaria');
        }
    });
}

var idBandaHorariaGlobal = null;
function openModalAlertaEliminarBandaHoraria(btn) {
    idBandaHorariaGlobal = btn.data('id_banda_horaria');
    modal('modalAlertaAceptacion');
}

function eliminarBandaHoraria() {
    if(idBandaHorariaGlobal ==  null || idBandaHorariaGlobal == '') { 
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'eliminarBandaHoraria',
        data : { idBandaHoraria : idBandaHorariaGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) { 
            modal('modalAlertaAceptacion');
            mostrarNotificacion('success', 'Se Elimin&oacute; correctamente', 'correcto');
            $('#contTablaBandaHoraria').html(data.tablaBandaHoraria); 
            initDataTable('#data-table');       
        } else {
            mostrarNotificacion('error', data.msj, 'no elimin&oacute; banda horaria');
        }
    });
}