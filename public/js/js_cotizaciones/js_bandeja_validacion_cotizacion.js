var codigoCotizacionGlob = null;
var costoMoTotalGlob     = null;
var costoMatGlob         = null;
var costoDisenoGlob      = null;
var nodo                 = null;
var idTipoDisenoGlob     = null;
var duracionGlob         = null;
var cod_ebc_global       = null;
function openModalAlertaConfirmacion(btn) {
    codigoCotizacionGlob = btn.data('codigo_cluster');
    costoMoTotalGlob     = btn.data('costo_total_mo');
    nodo                 = btn.data('nodo');
    costoMatGlob         = btn.data('costo_mat');
    idTipoDisenoGlob     = btn.data('id_tipo_diseno');
    duracionGlob         = btn.data('duracion');
    costoDisenoGlob      = btn.data('costo_diseno');
	cod_ebc_global       = btn.data('cod_ebc');

    modal('modalAlertaConfirmacion');
}

var codigoCotizacionRechGlob = null
function openModalAlertaRechazar(btn) {
    codigoCotizacionRechGlob = btn.data('codigo_cluster');
    modal('modalAlertaRechazar');
}

function openModalDatosSisegos(btn) {
    var codigo_cot = btn.data('codigo_cotizacion');

    if(codigo_cot == null || codigo_cot == '') {
        return;
    }
    
    $.ajax({
        type    :   'POST',
        'url'   :   'getDataDetalleCotizacionSisego',
        data    :   { codigo_cot : codigo_cot },
        'async' :   false
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contInfoDataSisego').html(data.dataInfoSisego);
            modal('modalDatosSisegos');
        } else {
            return;
        }
    });
}

function validarEnviarCotizacion() {
    
    $.ajax({
        type : 'POST',
        url  : 'validarEnviarCotizacion',
        data : { 'codigo_cotizacion' : codigoCotizacionGlob,
                 'costo_mo_total'    : costoMoTotalGlob,
                 'nodo'              : nodo,
                 'costo_mat'         : costoMatGlob,
                 'idTipoDiseno'      : idTipoDisenoGlob,
                 'duracion'          : duracionGlob,
                 'costo_diseno'      : costoDisenoGlob,
				 'cod_ebc'           : cod_ebc_global }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandeja);
            initDataTable('#data-table');
            modal('modalAlertaConfirmacion');
            mostrarNotificacion('success', 'Se ingreso correctamente', 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'incorrecto');
        }
    });
}

function rechazarCotizacion() {
    var observacion = $('#observacionText').val();
    $.ajax({
        type : 'POST',
        url  : 'rechazarCotizacion',
        data : { 'codigo_cotizacion' : codigoCotizacionRechGlob,
                 'observacion'       : observacion }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandeja);
            initDataTable('#data-table');
            modal('modalAlertaRechazar');
            mostrarNotificacion('success', 'Se rechaz&oacute; correctamente', 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'incorrecto');
        }
    });
}

    function zipArchivosForm(btn) {
        var codigo_cot  = btn.data('codigo_cotizacion');
 
        if(codigo_cot == null || codigo_cot == '') {
            return;
        }
    
        $.ajax({
            type : 'POST',
            url  : 'zipArchivosForm',
            data : { codigo_cot  : codigo_cot}
        }).done(function(data){
        
                data = JSON.parse(data);
                if(data.error == 0) {
                    var url= data.directorioZip; 
                    console.log(data.directorioZip);
                    if(url != null) {
                        window.open(url, 'Download');
                    } else {
                        alert('error');
                    }   
                    // mostrarNotificacion('success', 'descarga realizada', 'correcto');
                } else {
                    // mostrarNotificacion('error', 'descarga no realizada', 'error');            
                    alert('error al descargar');
                }

        });
    }