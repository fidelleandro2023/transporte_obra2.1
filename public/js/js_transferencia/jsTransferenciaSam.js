var arrayPlanPOGlobal      = null;
var arrayDetallePlanGlobal = null;
var arrayDetallePOGlobal   = null;
function subirArchivo() {
    $('#contResultTab2Error').html(null);
    $('.easy-pie-tab2').data('easyPieChart').update('0');
    $('#valuePieTab2').html(0);
}

$('#import_form').on('submit', function(event){
    $('#contTabtablas').html(null);
    $('.easy-pie-tab2').data('easyPieChart').update('40');
    $('#valuePieTab2').html(40);
    event.preventDefault();
    $.ajax({
        url         : "insertTransferenciaSam",
        method      : "POST",
        data        : new FormData(this),
        contentType : false,
        cache       : false,
        processData : false
    }).done(function(data){
        $('.easy-pie-tab2').data('easyPieChart').update('70');
        $('#valuePieTab2').html(70);
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#fileExcelTab2').val('');
            $('.easy-pie-tab2').data('easyPieChart').update('100');
            $('#valuePieTab2').html(100);
            mostrarNotificacion('success', 'Se subio el archivo de manera correcta', 'ingreso correcto');
        } else {
            // $('#contResultTab2Correcto').html(data.tablaExcelCorrecto);
            // $('#contResultTab2Error').html(data.tablaExcelError);
            // arrayPlanPOGlobal      = data.arrayPlanPO;
            // arrayDetallePlanGlobal = data.arrayDetallePlan;
            // arrayDetallePOGlobal   = data.arrayDetallePO;
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
});
