var solicitudGlob = null;
var flgValidaGlob = null;
var codigoPoGlob  = null;
var costoTotalFinGlob = null;
var itemplanGlb = null;
//inicClass();

function openModalValidarSolicitud(btn) {
    solicitudGlob = btn.data('codigo_solicitud');
    flgValidaGlob = btn.data('flg_valida');
    codigoPoGlob = btn.data('codigo_po');
    costoTotalFinGlob =  btn.data('costo_final');
	itemplanGlb		  =  btn.data('itemplan');
    $('#inputComentario').val('');
	console.log(costoTotalFinGlob);
    if(flgValidaGlob	==	1){
        $('#tituloValidacion').html('APROBAR SOLICITUD');
        $('#contCostoFinal').show();
        soloDecimal('costoFinal');
    }else if(flgValidaGlob	==	2){
        $('#tituloValidacion').html('RECHAZAR SOLICITUD');
        $('#contCostoFinal').hide();
    }
	
	if(costoTotalFinGlob == null) {
		return;
	}

    modal('modAtenderSolicitud');
}

function validarSolicitud() {
    var comentario = $('#inputComentario').val();

    var textoTipoAccion = (flgValidaGlob == 1) ? 'APROBAR' : 'RECHAZAR';
	
	if(itemplanGlb == null || itemplanGlb == '') {
		return;
	}
	
	if(solicitudGlob == null || solicitudGlob == '' || codigoPoGlob == null ||codigoPoGlob == '') {
		return;
	}

    swal({
        title: 'Esta seguro de '+textoTipoAccion+' la solicitud?',
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
            type : 'POST',
            url  : 'validarSolicitud',
            data : { 	flgValida    : flgValidaGlob,
                        codSolicitud : solicitudGlob,
                        comentario   : comentario,
                        codigoPo     : codigoPoGlob,
                        costoTotal   : costoTotalFinGlob,
						itemplan     : itemplanGlb }
            }).done(function(data) {
             var data	=	JSON.parse(data);
             if(data.error == 0){
                 modal('modAtenderSolicitud');
                 swal({
                    title: 'Se realizo la Operacion!',
                    text: 'Asegurese de validar la informacion!',
                    type: 'success',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'OK!'
                }).then(function () {
                    location.reload();
                });

             }else if(data.error == 1){     				
                 mostrarNotificacion('error', data.msj);
             }
          });
    }, function(dismiss) {
        console.log('cancelado...');
    });
}

function filtrarTablaPre() {
    var flgEstado 	= $('#cmbSituacion option:selected').val();
    var codigoSoli	= $('#txtCodSoli').val();
    var itemplan	= $('#txtItemplan').val();
    
    console.log("ENTRO: "+flgEstado);
    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaPre',
        data : {flgEstado   : flgEstado,
                codigoSoli  : codigoSoli,
	        	itemplan    : itemplan} 
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandejaPresupuesto);
            initDataTable('#data-table');
            // inicClass();
        } else {
            mostrarNotificacion('error','error', data.msj);
        }
    });
}

function openModalDetSol(btn) {
	var codigo_po = btn.data('codigo_po');
	var codigo_solicitud = btn.data('codigo_solicitud');
	
	if(codigo_po == null || codigo_po == '') {
		return;
	}
	
	if(codigo_solicitud == null || codigo_solicitud == '') {
		return;
	}
	
	$.ajax({
		type : 'POST',
		url  : 'openMdlDetSolPendPago',
	    data : { codigo_po : codigo_po,
                 codigo_solicitud : codigo_solicitud }
	}).done(function(data){
		data = JSON.parse(data);
		
		if(data.error == 0) {
			modal('modDetalleSolicitud');
			$('#txtComentario').html(data.htmlComentario);
			$('#contTablaSolicitud').html(data.tablaDetalleSolicitud);
			initDataTable('#tbDetalleSolicitud');
			
			// $('#contTablaPo').html(data.tablaDetallePo);
			// initDataTable('#tbDetallePo');
		} else {
			return;
		}
	});
}