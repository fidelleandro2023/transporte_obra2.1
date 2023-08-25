function openModalAgendamiento() {
        
    $('#contJefatura').val(null);
    $('#fechaAgendamiento').val(null); 
    $('#contItemplan').val(null);
    $('#contCmbBandaHoraria').html(null);
    $('#contEmpresaColab').val(null);
    $('#bandaHoraFec').val(null);
    $('#nomContacto1').val(null);
    $('#telefContacto1').val(null);
    $('#nomContacto2').val(null);
    $('#telefContacto2').val(null);
    $('#estadoPlan').val(null);
    $('#nomProyecto').val(null);
    $('#subProyecto').val(null);
    modal('modalAgendamiento');
}

var itemplanGlobal       = null;
var idEmpresaColabGlobal = null;
var fechaAgendamientoGlobal = null;
var jefaturaGlobal = null;
function getDataFormulario() {
    fechaAgendamientoGlobal = $('#fechaAgendamiento').val();
    // itemplanGlobal = $('#cmbItemplan option:selected').val();
    itemplanGlobal = $('#contItemplan').val();

    if(itemplanGlobal.length != 13) {
        return;
    }

    // if(fechaAgendamientoGlobal == null || fechaAgendamientoGlobal == '') {
    //     return;
    // }

    $.ajax({
        type : 'POST',
        url  : 'getDataFormulario',
        data : { itemplan          : itemplanGlobal,
                 fechaAgendamiento : fechaAgendamientoGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            if(data.cmbBandaHoraria.length == 0) {
                mostrarNotificacion('info', 'No tiene banda horaria', 'se debe registrar la banda horaria');
            }
            idEmpresaColabGlobal = data.idEmpresaColab;
            jefaturaGlobal       = data.jefatura;
            $('#contEmpresaColab').val(data.empresacolab);
            $('#contJefatura').val(data.jefatura);  
            $('#nomProyecto').val(data.nombreProyecto);
            $('#subProyecto').val(data.subProyectoDesc);
            $('#estadoPlan').val(data.estadoPlanDesc);
            var cmbBandaHoraria = '<option value="">Seleccionar banda horaria</option>';;
            data.cmbBandaHoraria.forEach(function(element){
                if(element.countItemplanAgendamiento == 0) {
                    cmbBandaHoraria+='<option value="'+element.idBandaHoraria+'">'+element.horaInFin+'</option>';                        
                }
            });
            // $('#contMatrizAgendamiento').html(data.tablaMatrizAgendamiento);
            $('#contCmbBandaHoraria').html(cmbBandaHoraria);   
        } else {
            mostrarNotificacion('error', data.msj);
        }
    })
}

function openModalAceptarAgenda() {
    modal('modalAlertaAceptacion');
}


var $_fechaCalenAsistGlobal = null;
function getDetalleAgendamiento(modalId, aTag, events_sources) {
    var fecha = null;
    $.each(events_sources, function(idx, value) {
        if(value.id == aTag.data('event-id')) {
            fecha = value.start;
            return false;
        }
    });
    getDetalleAgendamientoByFecha(fecha, modalId);
}

function getDetalleAgendamientoByFecha(fecha, modalId) {
    if(fecha == null || fecha == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getDetalleAgendamientoByFecha',
        data : { fecha : fecha }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#fechaAgenda').text(data.fechaAgen);
            $('#contAgendamientoDetalle').html(data.tablaDetalleAgenda);
            initDataTable('#tbDetalleAgenda');            
        } else {
            mostrarNotificacion('error', data.msj, 'comunicarse con el programador');
        }
    });
}

var idBandaHorariaGlobal = null;
var bandaHorariaGlobal   = null;
function openModalMatrizPanel() {
    if(idEmpresaColabGlobal == null || jefaturaGlobal == null) {
        mostrarNotificacion('error', 'Ingresar Itemplan', 'debe registrar el itemplan en el campo que le corresponde');
        return;
   }

    $.ajax({
        type : 'POST',
        url  : 'getPanelMatrizAgendamiento',
        data : { idEmpresaColab : idEmpresaColabGlobal,
                 jefatura       : jefaturaGlobal }
    }).done(function(data){
        data = JSON.parse(data);

        $('#contMatrizAgendamiento').html(data.tablaMatrizAgendamiento);
        $('#'+idBandaHorariaGlobal+'_'+fechaAgendamientoGlobal).parent('th').css("background-color",  "#81F7D8");
        modal('modalMatrizAgendamiento');
    });
}

function agregarBandaHoraria(btn) {
    var idColorBlancoAnterior = null;
    if(idBandaHorariaGlobal != null) {
        idColorBlancoAnterior = idBandaHorariaGlobal+'_'+fechaAgendamientoGlobal; 
    }
    
    idBandaHorariaGlobal    = btn.data('id_banda_horaria');
    fechaAgendamientoGlobal = btn.data('fecha_agendamiento');
    bandaHorariaGlobal      = btn.data('banda_horaria');

    $('#'+idBandaHorariaGlobal+'_'+fechaAgendamientoGlobal).parent('th').css("background-color",  "#81F7D8");

    $('#'+idColorBlancoAnterior).parent('th').css("background-color",  "white");
    
    $('#bandaHoraFec').val(bandaHorariaGlobal);
    modal('modalMatrizAgendamiento');
}

function agendar() {
    var jefatura          = $('#contJefatura').val();
     
    // var idBandaHoraria    = $('#contCmbBandaHoraria').val();
    var itemplan          = $('#contItemplan').val();

    var nomContacto1   = $('#nomContacto1').val();
    var telefContacto1 = $('#telefContacto1').val();
    var nomContacto2   = $('#nomContacto2').val();
    var telefContacto2 = $('#telefContacto2').val();
    if(jefatura == null || jefatura == '' || fechaAgendamientoGlobal == null || fechaAgendamientoGlobal == '' || 
        idBandaHorariaGlobal == '' || idBandaHorariaGlobal == null || itemplan == null || itemplan == '') {
        return;
    }

    if(itemplan.length < 13) {
        mostrarNotificacion('error', 'itemplan incorrecto', 'itemplan tiene 13 caracteres');
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'ingresarAgendamiento',
        data : { idEmpresaColab    : idEmpresaColabGlobal,
                 jefatura          : jefatura,
                 fechaAgendamiento : fechaAgendamientoGlobal,
                 idBandaHoraria    : idBandaHorariaGlobal,
                 itemplan          : itemplan,
                 nomContacto1      : nomContacto1,
                 telefContacto1    : telefContacto1,
                 nomContacto2      : nomContacto2 ,
                 telefContacto2    : telefContacto2 }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            getCalendar();
            getDataFormulario();
            
            modal('modalAlertaAceptacion');
            // modal('modalAgendamiento');
            $('#contMatrizAgendamiento').html(data.tablaMatrizAgendamiento);
            mostrarNotificacion('success', 'Registro realizado', 'codigo agendamiento: '+data.codigo);
            $('#nomContacto1').val(null);
            $('#telefContacto1').val(null);
            $('#nomContacto2').val(null);
            $('#telefContacto2').val(null);
            $('#bandaHoraFec').val(null);
        } else {
            mostrarNotificacion('error', data.msj, data.submsj);
        }
    });
}
