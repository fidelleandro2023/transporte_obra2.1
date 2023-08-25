var jsonInsert = {};
function openModalRegistro() {
    $.ajax({
        type : 'POST',
        url  : 'openModalRegistro'
    }).done(function(data){
        data = JSON.parse(data);
        $('#validaFecha').html(null);
        $('#validaEmpresaColab').html(null);
        $('#validaJefatura').html(null);
        $('#fecha').val(null);
        
        $('#contCmbEcc').html(data.cmbEmpresaColab);
        $('#contJefatura').html(data.cmbJefatura);
        $('#contSubProyecto').html(data.cmbSubProyecto);
        $('#btnModalReAc').attr('onclick', 'registrarSwitch()');
        modal('modalEditarSwitch');
    });
}

function registrarSwitch() {
    var fecha          = $('#fecha').val();
    var idEmpresaColab = $('#contCmbEcc option:selected').val();
    var jefatura       = $('#contJefatura option:selected').val();
    var idSubProyecto  = $('#contSubProyecto option:selected').val();

    $('#validaFecha').html(null);
    $('#validaEmpresaColab').html(null);
    $('#validaJefatura').html(null);
    $('#validaSubProyecto').html(null);

    if(idSubProyecto == null || idSubProyecto == '') {
        $('#validaSubProyecto').html('<a style="color:red">Seleccionar SubProyecto</a>')        
        return;
    }

    if(idEmpresaColab == null || idEmpresaColab == '') {
        $('#validaEmpresaColab').html('<a style="color:red">Seleccionar empresa colaboradora</a>')
        return;
    }

    if(jefatura == null || jefatura == '') {
        $('#validaJefatura').html('<a style="color:red">Seleccionar Jefatura</a>')        
        return;
    }

    if(fecha == null || fecha == '') {
        $('#validaFecha').html('<a style="color:red">Ingresar Fecha</a>')                
        return;
    }
    jsonInsert.jefatura       = jefatura;
    jsonInsert.fecha          = fecha;
    jsonInsert.idEmpresaColab = idEmpresaColab;
    jsonInsert.idSubProyecto  = idSubProyecto;

    $.ajax({
        type : 'POST',
        url  : 'registrarSwitch',
        data : { jsonInsert   : JSON.stringify(jsonInsert) }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            jsonInsert = {};
            mostrarNotificacion('success', "Registro correcto", "correcto");
            $('#contTabla').html(data.tablaSwitch);
            initDataTable('#data-table');
            modal('modalEditarSwitch');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }        
    });
}

idSwitchGlobal = null;
jsonUpdate = {};
function openModalEditar(btn) {
    var idEmpresaColab = btn.data('id_empresacolab');
    var jefatura       = btn.data('jefatura');
    var fecha          = btn.data('fecha');
    idSwitchGlobal     = btn.data('id_switch');
    var idSubProyecto  = btn.data('id_sub_proyecto');

    if(idSwitchGlobal == '' || idSwitchGlobal == null) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getDataEditar',
        data : { idEmpresaColab : idEmpresaColab,
                 jefatura       : jefatura,
                 idSubProyecto  : idSubProyecto }
    }).done(function(data){
        data = JSON.parse(data);
        $('#validaFecha').html(null);
        $('#validaEmpresaColab').html(null);
        $('#validaJefatura').html(null);
    
        $('#contCmbEcc').html(data.cmbEmpresaColab);
        $('#contJefatura').html(data.cmbJefatura);
        $('#contSubProyecto').html(data.cmbSubProyecto);
        $('#btnModalReAc').attr('onclick', 'actualizarSwitch()');
        $('#fecha').val(fecha);
        modal('modalEditarSwitch');
    });
}

function actualizarSwitch() {
    var fecha          = $('#fecha').val();
    var idEmpresaColab = $('#contCmbEcc option:selected').val();
    var jefatura       = $('#contJefatura option:selected').val();
    var idSubProyecto  = $('#contSubProyecto option:selected').val();

    $('#validaFecha').html(null);
    $('#validaEmpresaColab').html(null);
    $('#validaJefatura').html(null);
    $('#validaSubProyecto').html(null);

    if(idSubProyecto == null || idSubProyecto == '') {
        $('#validaSubProyecto').html('<a style="color:red">Seleccionar SubProyecto</a>')        
        return;
    }


    if(idEmpresaColab == null || idEmpresaColab == '') {
        $('#validaEmpresaColab').html('<a style="color:red">Seleccionar empresa colaboradora</a>')
        return;
    }

    if(jefatura == null || jefatura == '') {
        $('#validaJefatura').html('<a style="color:red">Seleccionar Jefatura</a>')        
        return;
    }

    if(fecha == null || fecha == '') {
        $('#validaFecha').html('<a style="color:red">Ingresar Fecha</a>')                
        return;
    }
    jsonUpdate.jefatura       = jefatura;
    jsonUpdate.fecha          = fecha;
    jsonUpdate.idEmpresaColab = idEmpresaColab;
    jsonUpdate.idSubProyecto  = idSubProyecto;

    $.ajax({
        type : 'POST',
        url  : 'actualizarSwitch',
        data : { jsonUpdate   : JSON.stringify(jsonUpdate),
                 idSwitchSiom : idSwitchGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            jsonUpdate = {};
            mostrarNotificacion('success', "Actualizaci&oacute;n correcta", "correcto");
            $('#contTabla').html(data.tablaSwitch);
            initDataTable('#data-table');
            modal('modalEditarSwitch');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }        
    });
}

function filtrarTablaSwitchSiom() {
    var idEmpresaColab = $('#cmbEccFiltro option:selected').val();
    var jefatura       = $('#cmbJefaturaFiltro option:selected').val();
    var idSubProyecto  = $('#cmbSubProyectoFiltro option:selected').val();

    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaSwitchSiom',
        data : { idEmpresaColab : idEmpresaColab,
                 jefatura       : jefatura,
                 idSubProyecto  : idSubProyecto }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabla').html(data.tablaSwitch);
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }        
    });
}