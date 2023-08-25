function filtrarTablaTramaSisego() {
    var tipoTramaSisego = $('#selectTipoTrama option:selected').val();
    var flg_exito =  $('#selectExito option:selected').val();
    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaTramaSisego',
        data : { tipoTramaSisego : tipoTramaSisego,  
                 flg_exito       : flg_exito }
    }).done(function(data){
        data = JSON.parse(data);
        
        if(data.error == 0) {
            $('#contTabla').html(data.tablaTramaSisego);
            initDataTable('#data-table');
        } else {
            return;
        }
        
    });
}