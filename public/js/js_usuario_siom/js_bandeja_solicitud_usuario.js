var idSolicitudGlobal  = null;
var estadoGlobal       = null;
var flgTipoSolicitudGl = null;
function openModalUsuario(btn) {
	estadoGlobal       = btn.data('estado');
    idSolicitudGlobal  = btn.data('id_solicitud');
    flgTipoSolicitudGl = btn.data('flg_tipo_solicitud');
	modal('modalUsuario');
}

function ingresarUsuario() {
	if(estadoGlobal == '' || estadoGlobal == null) {
        return;
    }

    if(flgTipoSolicitudGl == '' || flgTipoSolicitudGl == null) {
        return;
    }

    if(idSolicitudGlobal == null || idSolicitudGlobal == '') {
        return;
    }
	
	var usuario = $('#inputUsuario').val();
	var clave1 = $('#inputClave').val();
	var clave2 = $('#inputClave2').val();
	
	if(clave1 == null || clave1 == '') {
		$('#msjValidClave').html('<p style="color:red">(*) Debe campo obligatorio.</p>');
		return;
	}
	
	if(clave2 == null || clave2 == '') {
		$('#msjValidClave').html('<p style="color:red">(*) Debe campo obligatorio.</p>');
		return;
	}
	
	if(usuario == null || usuario == '') {
		$('#msjValidUsuario').html('<p style="color:red">(*) Debe campo obligatorio.</p>');
		return;
	}
	
	if(clave1 != clave2) {
		$('#msjValidClave2').html('<p style="color:red">(*) Debe tener la misma clave.</p>');
		$('#msjValidClave').html('<p style="color:red">(*) Debe tener la misma clave.</p>');
		return;
	}
	
	swal({
        title: 'Est&aacute; seguro de realizar esta acción?',
        text: 'Asegurese de que la informacion llenada sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, guardar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
        $.ajax({
            type: 'POST',
            url: "ingresarUsuario",
            data: { idSolicitud : idSolicitudGlobal,
                    estado      : estadoGlobal,
                    flg_tipo_solicitud : flgTipoSolicitudGl,
					usuario     : usuario, 
					clave       : clave1 }
          })
          .done(function(data) {  
                data = JSON.parse(data);
                console.log(data.error);
                if(data.error == 0){
                    swal({
                        title: 'Correcto',
                        text:  'Se realizó la acción correctamente.',
                        type:  'success',
                        showCancelButton: false,                    	            
                        allowOutsideClick: false
                    }).then(function(){
                        window.location.href = "getBandejaSolicitudU";
                    });
                }else if(data.error == 1){
                    swal({
                            title: 'USUARIO REALIZÓ UNA ACCIÓN INDEBIDA',
                            text: data.msj,
                            type: 'warning',
                            buttonsStyling: false,
                            allowOutsideClick: false
                        })
                }
            });
       

    }, function(dismiss) {
        console.log('cancelado');
    });
}

function aprobCanSolicitud(btn) {
    var estado      = btn.data('estado');
    var idSolicitud = btn.data('id_solicitud');
    var flg_tipo_solicitud = btn.data('flg_tipo_solicitud');

    if(estado == '' || estado == null) {
        return;
    }

    if(flg_tipo_solicitud == '' || flg_tipo_solicitud == null) {
        return;
    }

    if(idSolicitud == null || idSolicitud == '') {
        return;
    }
	
	aprobarCancelar(estado, idSolicitud, flg_tipo_solicitud);
}

var estadoRechazGlb    = null;
var idSolicitudRechGlb = null;
var flgTipoSolRechGlb  = null;
var observRechazoGlb   = null;
function openModalRechazar(btn) {
    estadoRechazGlb    = btn.data('estado');
    idSolicitudRechGlb = btn.data('id_solicitud');
    flgTipoSolRechGlb  = btn.data('flg_tipo_solicitud');
    modal('modalRechazo');
}

function rechazarSolicitud() {
    observRechazoGlb   = $('#observacionRechazo').val();
    if(estadoRechazGlb == '' || estadoRechazGlb == null) {
        return;
    }

    if(flgTipoSolRechGlb == '' || flgTipoSolRechGlb == null) {
        return;
    }

    if(idSolicitudRechGlb == null || idSolicitudRechGlb == '') {
        return;
    }
    aprobarCancelar(estadoRechazGlb, idSolicitudRechGlb, flgTipoSolRechGlb);
}

function aprobarCancelar(estado, idSolicitud, flg_tipo_solicitud) {
	if(observRechazoGlb) {
		observRechazoGlb = observRechazoGlb.trim();
	}
	
	    swal({
        title: 'Est&aacute; seguro de realizar esta acción?',
        text: 'Asegurese de que la informacion llenada sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, guardar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
        $.ajax({
            type: 'POST',
            url: "aprobCanSolicitud",
            data: { idSolicitud : idSolicitud,
                    estado      : estado,
                    flg_tipo_solicitud : flg_tipo_solicitud,
                    observRechazo : observRechazoGlb }
          })
          .done(function(data) {  
                data = JSON.parse(data);
                console.log(data.error);
                if(data.error == 0){
                    swal({
                        title: 'Correcto',
                        text:  'Se realizó la acción correctamente.',
                        type:  'success',
                        showCancelButton: false,                    	            
                        allowOutsideClick: false
                    }).then(function(){
                        window.location.href = "getBandejaSolicitudU";
                    });
                }else if(data.error == 1){
                    swal({
                            title: 'USUARIO REALIZÓ UNA ACCIÓN INDEBIDA',
                            text: data.msj,
                            type: 'warning',
                            buttonsStyling: false,
                            allowOutsideClick: false
                        })
                }
            });
       

    }, function(dismiss) {
        console.log('cancelado');
    });
}

function filtrarTablaSolicitudUsuario() {
    var estado         = $('#cmbSituacion option:selected').val();
    var dni            = $('#dni').val();
    var idEmpresaColab = $('#cmbEmpresaColab option:selected').val();
    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaSolicitudUsuario',
        data : { estado         : estado,
                 dni            : dni,
                 idEmpresaColab : idEmpresaColab }
    }).done(function(data){
        data = JSON.parse(data);

        // if(data.error == 0) {
            $('#contTabla').html(data.tabla);
            initDataTable('#data-table');
        // } else {
        //     return;
        // }
    });
}