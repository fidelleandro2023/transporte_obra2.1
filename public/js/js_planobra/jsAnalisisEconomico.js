var itemplanGlobal = null;
function getTablaAnalisisEconomico() {
    itemplanGlobal = $('#inputItemplan').val();

    if(itemplanGlobal.length != 13) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getTablaAnalisisEconByItemplan',
        data : { itemplan : itemplanGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabla').html(data.tablaAnalisisEconomico);
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error', data.msj);
        }
    })
}