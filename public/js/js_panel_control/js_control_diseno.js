function generarPOControl(btn) {
    var itemplan   = btn.data('itemplan');
    var idEstacion = btn.data('id_estacion');
    
    if(itemplan == null || itemplan == '' || idEstacion == '' || idEstacion == null) {
        return;
    }

    swal({
        title: 'Est&aacute; seguro de ejecutar los cambios?',
        text: 'Asegurese:  Que tenga PO MAT o MO, EL subproyecto tenga las partidas de alta complejidad o media, que el subproyecto sea de media o alta complejidad.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, Generar PO!',
        cancelButtonClass: 'btn btn-secondary',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false,
        showCloseButton: true
    }).then(function(){
        $.ajax({
            type : 'POST',
            url  : 'generarPOControl',
            data : { itemplan   : itemplan,
                     idEstacion : idEstacion }
        }).done(function(data){
            data = JSON.parse(data);

            if(data.error == 0) {
                mostrarNotificacion('success', 'se gener&oacute; la PO', 'correcto');
                $('#contTablaSinPo').html(data.tablaSinPO);
                initDataTable('#tbSinPO');
            } else {
                mostrarNotificacion('error',data.msj, 'no se gener&oacute;');
            }
        });
    });
}