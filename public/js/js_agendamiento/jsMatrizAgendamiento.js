var idEmpresaColabGlobal = null;
var jefaturaGlobal       = null;
function openModalCuotasAgenda(btn) {
    idEmpresaColabGlobal = btn.data('id_empresacolab');
    jefaturaGlobal       = btn.data('jefatura');

    $.ajax({
        type : 'POST',
        url  : 'getElementModalCuotas',
        data : { idEmpresaColab : idEmpresaColabGlobal, 
                 jefatura       : jefaturaGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#cantidad').val(null);
            var cmbBandaHoraria ='<option value="">Seleccionar Banda Horaria</option>';
            data.cmbBandaHoraria.forEach(function(element){
                cmbBandaHoraria+='<option value="'+element.idBandaHoraria+'">'+element.horaInFin+'</option>';
            });
            $('#contCmbBandaHoraria').html(cmbBandaHoraria); 
            modal('modalBandaHoraria');
        } else {
            return;
        }
    });
    // $('#horaInicio').val(btn.data('hora_inicio'));
    // $('#horaFin').val(btn.data('hora_fin'));
    // $('#cantidad').val(btn.data('cantidad'));
    // modal('modalAgendamiento');
}

function registrarCuotas() {
    // var horaInicio = $('#horaInicio').val();
    // var horaFin    = $('#horaFin').val();
    var cantidad       = $('#cantidad').val();
    var idBandaHoraria = $('#contCmbBandaHoraria option:selected').val();
    
    if(cantidad == '') {
        mostrarNotificacion('error', 'Ingresar cantidad', 'no se registr&oacute;');
        return;
    }

    if(idBandaHoraria == '') {
        mostrarNotificacion('error', 'Seleccionar banda horaria', 'no se registr&oacute;');
        return;
    }

    // if(horaInicio > horaFin) {
    //     mostrarNotificacion('error', 'La hora inicio debe ser menor que la hora fin', 'no se registr&oacute;');
    //     return;
    // }

    $.ajax({
        type : 'POST',
        data : { idEmpresaColab : idEmpresaColabGlobal,
                 jefatura       : jefaturaGlobal,
                 idBandaHoraria : idBandaHoraria,
                 cantidad       : cantidad },
        url  : 'registrarCuotas'
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            modal('modalBandaHoraria');
            $('#contTablaMatriz').html(data.tablaMatriz);
            initDataTable('#data-table');
            mostrarNotificacion('success', 'Registro Correcto', 'confirmado');
        } else {
            mostrarNotificacion('error', 'No se registro', 'error');
        }
    });
}

var idEmpresaColabEditGlobal = null;
var jefaturaEditGlobal       = null;
var arrayJsonEditGlobal      = null;
function editarCuotasAgendamiento(btn) { 
    idEmpresaColabEditGlobal = btn.data('id_empresacolab');
    jefaturaEditGlobal       = btn.data('jefatura');    

    if(jefaturaEditGlobal == null || jefaturaEditGlobal == '') {
        return;
    }

    if(idEmpresaColabEditGlobal == null || idEmpresaColabEditGlobal == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'openModalEditarCuotas',
        data : { jefatura       : jefaturaEditGlobal,
                 idEmpresaColab : idEmpresaColabEditGlobal }
    }).done(function(data){
        
        // $("#contEditar tr").find('td:eq(5) textarea').each(function() {});
        data = JSON.parse(data);
        arrayJsonEditGlobal = data.arrayJsonEdit;
        //console.log(data.arrayJsonEdit);
        $('#contEditar').html(data.htmlElementEdit);
        //console.log($("#contEditar div"));
        modal('modaEditarCuotas');
    });  
}


var arrayEditJson = [];
function getDataEditar(btn) { 
    var idCuotaAgenda = btn.data('id_cuota_agenda');
    var cont          = btn.data('val');
    var jsonEdit = {};
    var cuota = $('#cantidad_'+cont).val();
    var idBandaHoraria = $('#cmbBandaHoraria_'+cont+' option:selected').val();

    var contador = 0;
    arrayEditJson.forEach(function(data, key){
        if(data.idCuotaAgenda == idCuotaAgenda) {
            contador = 1;
            jsonEdit.idCuotaAgenda  = idCuotaAgenda;
            jsonEdit.cantidad       = cuota;
            jsonEdit.idBandaHoraria = idBandaHoraria;
            // arrayData.push(jsonData);
            arrayEditJson.splice(key, 1, jsonEdit);
            jsonEdit = {};
        }
    });


    if(contador == 0) {
        jsonEdit.idCuotaAgenda  = idCuotaAgenda;
        jsonEdit.cantidad       = cuota;
        jsonEdit.idBandaHoraria = idBandaHoraria;
        // arrayData.push(jsonData);
        arrayEditJson.splice(arrayEditJson.length, 0, jsonEdit);
        jsonEdit = {};
    }
}

function actualizarCuotas() {
    var cont=0;
    jsonEdit = {};

    $.ajax({
        type : 'POST',
        url  : 'actualizarCuotas',
        data : { arrayData : arrayEditJson }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            mostrarNotificacion('success','ingreso correcto', 'correcto');
            modal('modaEditarCuotas');
            $('#contTablaMatriz').html(data.tablaMatriz);
        } else {
            mostrarNotificacion('error','No se ingreso', 'incorrecto');
        }
    });
}

