
function filtrarTablaSiom() {
    var idEmpresaColab = $('#cmbEcc option:selected').val();
    var idSubProyecto  = $('#cmbSubProyecto option:selected').val();    
    var jefatura       = $('#cmbJefatura option:selected').val();
    var noEnviado      = $('#cmbNoEnviado option:selected').val();

    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaCancelacionSiom',
        data : { idEmpresaColab   : idEmpresaColab,
                 idSubProyecto    : idSubProyecto,
                 jefatura         : jefatura,
                 noEnviado		  :	noEnviado } 
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandejaSiom);
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error','error', data.msj);
        }
    });
}