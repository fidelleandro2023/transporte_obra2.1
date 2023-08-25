function filtrarTablaHG() {  
    var pep1    = $('#txtPep1').val();
	//alert('Funcionalidad Deshabilitada Temporalmente');
    $.ajax({
        type : 'POST',
        url  : 'filDPS',
        data : {pep1     : pep1} 
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandejaHG);
            $('#montoDispoProy').html('MONTO PROYECTADO: S/.'+data.dispoProy);
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error','error', data.msj);
        }
    });
}
