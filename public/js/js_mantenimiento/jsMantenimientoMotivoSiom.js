function openModalInsert() {
    modal('modalIngresar');
}

function registrarMotivo() {
    var nomMotivo = $('#nomMotivo').val();
    var cmbTipo   = $('#cmbTipo').val();

    if(nomMotivo == null || nomMotivo == '' || cmbTipo == '' || cmbTipo == null) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'registrarMotivo',
        data : { 
                    nomMotivo : nomMotivo,
                    flgTipo : cmbTipo 
                }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            // $('#contTabla').html(data.tablaMotivoSiom);
            // modal('modalIngresar');
            // initDataTable('data-table');
            swal({
                title: 'Correcto',
                text:  'Se realizó la acción correctamente.',
                type:  'success',
                showCancelButton: false,                    	            
                allowOutsideClick: false
            }).then(function(){
                window.location.href = "getMantenimientoMotivoSiom";
            });
        } else {
            swal({
                title: 'USUARIO REALIZÓ UNA ACCIÓN INDEBIDA',
                text: data.msj,
                type: 'warning',
                // showCancelButton: true,
                buttonsStyling: false,

                allowOutsideClick: false
            })
        }
    });
}

var idMotivoMantenimientoGlb = null;
function openModalEditar(btn) {
    idMotivoMantenimientoGlb = btn.data('id_motivo_siom');

    $.ajax({
        type : 'POST',
        url  : 'getEditMotivoSiom',
        data : { idMotivoMantenimiento : idMotivoMantenimientoGlb }
    }).done(function(data){
        data = JSON.parse(data);
        console.log(data);
        var tipos = data.arrayData.tipo.split(',');
        var estado = data.arrayData.estado;
        var descripcion = data.arrayData.descripcion;
        modal('modalEditar');
        $('#nomMotivoEdit').val(descripcion);
        $('#cmbTipoEdit').val(tipos).trigger("change");
        $('#cmbStatusEdit').val(estado).trigger("change");

    });
}

function actualizarMotivoSiom() {
    var estado    = $('#cmbStatusEdit option:selected').val();
    var tipo      = $('#cmbTipoEdit').val();
    var nomMotivo = $('#nomMotivoEdit').val();

    if(estado == null || estado == '' || tipo == null || tipo == '' || nomMotivo == '' || nomMotivo == null) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'actualizarMotivoSiom',
        data : { 
                    idMotivoMantenimiento : idMotivoMantenimientoGlb,
                    estado    : estado,
                    tipo      : tipo,
                    nomMotivo : nomMotivo
                }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            swal({
                title: 'Correcto',
                text:  'Se realizó la acción correctamente.',
                type:  'success',
                showCancelButton: false,                    	            
                allowOutsideClick: false
            }).then(function(){
                window.location.href = "getMantenimientoMotivoSiom";
            });
        } else {
            titulo = 'USUARIO REALIZÓ UNA ACCIÓN INDEBIDA';
            msj    = data.msj;
            alerta(titulo, msj);
        }
    });
}

