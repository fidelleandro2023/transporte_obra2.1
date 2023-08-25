var idEmpresaColabGlobal = null;
var idJefaturaGlobal     = null;

function filtrarTabla() {
    idEmpresaColabGlobal = $('#cmbEmpresaColab option:selected').val();
    idJefaturaGlobal     = $('#cmbJefatura option:selected').val();


    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaBandejaReporteSisego',
        data : { idEmpresaColab : idEmpresaColabGlobal,
                 idJefatura     : idJefaturaGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        
        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandeja);
            initDataTable('#data-table');
        } else {
            return;
        }
        
    });
}

function openModalDisenoReporte(btn) {
    itemplan = btn.data('itemplan');
    $.ajax({
        type : 'POST',
        url  : 'openModalDisenoReporte',
        data : { itemplan : itemplan }
    }).done(function(data){
        data = JSON.parse(data);
        
        if(data.error == 0) {
            $('#titulo').html('<h3>Itemplan: '+itemplan+'</h3>');
            modal('modalDisenoReporte');
            $('#contTablaDiseno').html(data.tablaDiseno);
            // initDataTable('#data-table');
        } else {
            return;
        }
        
    });
}