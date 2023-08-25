$('#import_form').on('submit', function(event){
    $('#contTabtablas').html(null);
    $('.easy-pie-tab2').data('easyPieChart').update('40');
    $('#valuePieTab2').html(40);
    event.preventDefault();
    $.ajax({
        url         : 'cargaMat',
        method      : 'POST'
    }).done(function(data){
        $('.easy-pie-tab2').data('easyPieChart').update('70');
        $('#valuePieTab2').html(70);
        data = JSON.parse(data);
        console.log(data);
        if(data.error == 0) {
            $.ajax({
                url         : "cargaMo",
                method      : "POST"
            }).done(function(data){
                data = JSON.parse(data);
                if(data.error == 0) {
                    $('.easy-pie-tab2').data('easyPieChart').update('100');
                    $('#valuePieTab2').html(100);
                    
                    $('#contTabs').html(data.tablaTabsTmp);
                    mostrarNotificacion('success', 'Se gener&oacute; el archivo de forma correcta', 'comprobarlo en el extractor');
                } else {
                    mostrarNotificacion('error', data.msj, 'error');
                }
            });
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
});