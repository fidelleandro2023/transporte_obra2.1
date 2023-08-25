
function consulta_pep() {
    var pep = $('#pep').val();
     $.ajax({
            type: 'POST',
            url: 'ajaxPepOrigenBusqueda',
            data: {pep: pep}
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                $('#contTabla').html(data.tablaConsultaConfigOpex);
               initDataTable('#data-table');
            } else {
                mostrarNotificacion('error', data.msj, 'error');
            }
        });
}

