var idAgendamientoGlobal = null;
var val = null;
function openModalConfirmarAgendamiento(btn) {
    idAgendamientoGlobal = btn.data('id_agendamiento');
    val = btn.data('val');
    modal('modalAlertaAceptacion');
}

function confirmarAgendamiento() {
    var fechaAgendamiento = $('#fecha_agendamiento_'+val).val();

    $.ajax({
        type : 'POST',
        url  : 'confirmarAgendamiento',
        data : { fechaAgendamiento : fechaAgendamiento, 
                 idAgendamiento    : idAgendamientoGlobal } 
    }).done(function(data) {
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTablaAgendamiento').html(data.tablaAgendamiento);
            mostrarNotificacion('success', 'se confirmo correctamente', 'correcto');
            initDataTable('#data-table');
            modal('modalAlertaAceptacion');
        } else {
            mostrarNotificacion('error', data.msj, 'comunicarse con el programador');
        }

    });
}

idAgendamientoCancelacionGlobal = null;
function openModalConfirmarCancelacion(btn) {
    idAgendamientoCancelacionGlobal = btn.data('id_agendamiento');
    
    if(idAgendamientoCancelacionGlobal == null) {
        return;
    }

    modal('modalAlertaCancelacion');
}

function confirmarCancelacion() {
  $.ajax({
        type : 'POST',
        url  : 'confirmarCancelacion',
        data : { idAgendamiento : idAgendamientoCancelacionGlobal } 
    }).done(function(data) {
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTablaAgendamiento').html(data.tablaAgendamiento);
            mostrarNotificacion('success', 'se cancel&oacute; correctamente', 'correcto');
            initDataTable('#data-table');
            modal('modalAlertaCancelacion');
        } else {
            mostrarNotificacion('error', data.msj, 'comunicarse con el programador');
        }

    });
}