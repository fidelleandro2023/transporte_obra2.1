var idEmpresaColabGlobal = null;
var idJefaturaGlobal     = null;
function filtrarTablaResumen() {
    idEmpresaColabGlobal = $('#cmbEmpresaColab option:selected').val();
    idJefaturaGlobal     = $('#cmbJefatura option:selected').val();

    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaResumen',
        data : { idEmpresaColab : idEmpresaColabGlobal,
                 idJefatura     : idJefaturaGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        
        if(data.error == 0) {
            $('#contTablaResumenPo').html(data.tablaResumenPO);
            initDataTable('#data-table');
        } else {
            return;
        }
        
    });
}

var flujo    = null;
var interval = null;
function getModalDetallePO(btn) {
    flujo    = btn.data('flujo');
    interval = btn.data('interval');
    
    $.ajax({
        type : 'POST',
        url  : 'getModalDetallePO',
        data : { flujo          : flujo,
                 interval       : interval,
                 idEmpresaColab : idEmpresaColabGlobal,
                 idJefatura     : idJefaturaGlobal }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            $('#contTablaDetalle').html(data.tablaDetallePO);
            initDataTable('#data-table2');
            modal('modal_detalle');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}