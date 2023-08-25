var idProyectoGlobal = null;
var idEstacionGlobal = null;
function openModalDetalleTab1(btn) {
    idProyectoGlobal = btn.data('id_proyecto');
    idEstacionGlobal = btn.data('id_estacion');

    if(idProyectoGlobal == null || idProyectoGlobal == '' || idEstacionGlobal == null || idEstacionGlobal == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getTablaDetalleTab1',
        data : { idProyecto : idProyectoGlobal,
                 idEstacion : idEstacionGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        modal('modalDetalleTab1');
        $('#contTablaDetalleTab1').html(data.tablaDetalleTab1);
        initDataTable('#tabla_detalle_1');
    });
}