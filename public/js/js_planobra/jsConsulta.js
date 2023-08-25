var itemplanGlobal = null;
function openPiloto(btn) {
	itemplanGlobal = btn.data('itemplan');
    var a = document.createElement("a");
    a.target = "_blank";
    a.href = "getProcesoPiloto?itemplan="+itemplanGlobal;
    a.click();
}

function actualizarCodigoSiom(btn, $cont) {
    var idEstacion = btn.data('id_estacion');
    var itemplan   = btn.data('itemplan');
    var codigoSiom = $('#inputCodigoSiom_'+$cont).val();
    if(itemplan == null || itemplan == '') {
        return;
    }

    if(codigoSiom == null || codigoSiom == '') {
        return;
    }

    if(idEstacion == null || idEstacion == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'actualizarCodigoSiom',
        data : { 
                 idEstacion : idEstacion,
                 itemplan   : itemplan,
                 codigoSiom : codigoSiom
                }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            modal('modalSiom');
            mostrarNotificacion('success', 'se actualiz&oacute; correctamente', 'actualizado');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

function getOpenModalDocumentosCV(btn) {
    var ubic_exped_cv = btn.data('ubic_exped_cv');
    var ubic_tss_cv   = btn.data('ubic_tss_cv');
    var comentario_cv = btn.data('comentario_cv');
    console.log(ubic_exped_cv);
    
    if(ubic_exped_cv == null || ubic_exped_cv == '') {
        $('#cntMsj').html('<h1>AUN NO SUBEN ARCHIVOS</h1>');
    } else {
        var btnTss = '<a href="'+ubic_tss_cv+'" target="_blank"><button class="btn btn-success">DESCARGAR TSS</button></a>';
        var btnExp = '<a href="'+ubic_exped_cv+'" target="_blank"><button class="btn btn-success">DESCARGAR EXPEDIENTE</button></a>';
        $('#comentario').val(comentario_cv);
        $('#contBtnTss').html(btnTss);
        $('#contBtnExpediente').html(btnExp);
    }
    modal('modalDocumentosCV');
}
