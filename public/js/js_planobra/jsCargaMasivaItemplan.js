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
        url         : "insertTbTemporal",
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
            
            $('#contTabs').html(data.tablaTabsTmp);
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

function insertarPO() {
    $.ajax({
        url         : "insertarPO",
        method      : "POST",
        data        : { arrayPlanPO      : arrayPlanPOGlobal,
                        arrayDetallePlan : arrayDetallePlanGlobal,
                        arrayDetallePO   : arrayDetallePOGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            mostrarNotificacion('success', 'Se subieron las correctas', 'ingreso correcto');
            $('#contResultTab2Correcto').html(null);
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

var itemplanGlobal     = null;
var idEstacionGlobal = null;
var estacionDescGlobal = null;
function getTablaItemplan(tab) {
    itemplanGlobal     = tab.data('itemplan');
    idEstacionGlobal   = tab.data('id_estacion');
    estacionDescGlobal = tab.data('estacion_desc');

    if(itemplanGlobal == null ||  itemplanGlobal == '') {
        return;
    }

    if(estacionDescGlobal == null ||  estacionDescGlobal == '') {
        return;
    }

    if(idEstacionGlobal == null ||  idEstacionGlobal == '') {
        return;
    }

    $.ajax({
        url         : "getTablaTabs",
        method      : "POST",
        data        : { itemplan     : itemplanGlobal,
                        idEstacion   : idEstacionGlobal,
                        estacionDesc : estacionDescGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabtablas').html(data.tablasTabs);
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

function openModalAlerta() {
    modal('modalAlerta');
}

function insertPODetallePlan() {
    if(itemplanGlobal == null ||  itemplanGlobal == '') {
        return;
    }

    if(idEstacionGlobal == null ||  idEstacionGlobal == '') {
        return;
    }

    if(estacionDescGlobal == null ||  estacionDescGlobal == '') {
        return;
    }

    $.ajax({
        url         : "insertPODetallePlan",
        method      : "POST",
        data        : { itemplan     : itemplanGlobal,
                        idEstacion   : idEstacionGlobal,
                        estacionDesc : estacionDescGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabtablas').html(data.tablasTabs);
            $('#contTabs').html(data.tablaTabsTmp);
            modal('modalAlerta');
            mostrarNotificacion('success', data.msj, 'confirmado');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

function openModalAlertaMasivo() {
    modal('modalAlertaMasivo');
}

function generarMasivoPO() {
    $.ajax({
        url         : "generarMasivoPO",
        method      : "POST"
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabtablas').html(null);
            // $('#contTabtablas').html(data.tablasTabs);
            $('#contTabs').html(data.tablaTabsTmp);
            console.log(data.tablaPO);
            $('#contTablaPO').html(data.tablaPO);
            modal('modalTablaPO');
            modal('modalAlertaMasivo');
            mostrarNotificacion('success', 'Se registro correctamente', 'confirmado');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}