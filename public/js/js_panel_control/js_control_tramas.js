function getDetalle(btn) {
    var flgTipo     = btn.data('flg_tipo');
    var exito       = btn.data('exito');
    var intervalo_h = btn.data('intervalo_h');
    // if(flgTipo == null || flgTipo == '' || exito == '' || exito == null) {
    //     console.log("ENTRO11");
    //     return;
    // }

    $.ajax({
        type : 'POST',
        url  : 'getTablaDetalle',
        data : { flgTipo : flgTipo,
                 exito   : exito,
                 intervalo_h : intervalo_h }

    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            $('#tituloModal').html('<h3>DETALLE</h3>');
            $('#contTablaDetalle').html(data.tablaDetalle);
            initDataTable('#tbDetalleControl');
            modal('modal_detalle');
        } else {
            return;
        }
    });
}

function getDetalleSiom(btn) {
    var flgTipo     = btn.data('flg_tipo');
    var exito       = btn.data('exito');
    var intervalo_h = btn.data('intervalo_h');
    // if(flgTipo == null || flgTipo == '' || exito == '' || exito == null) {
    //     console.log("ENTRO11");
    //     return;
    // }

    $.ajax({
        type : 'POST',
        url  : 'getTablaDetalleTramaSiom',
        data : { flgTipo : flgTipo,
                 exito   : exito,
                 intervalo_h : intervalo_h }

    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            $('#tituloModalSiom').html('<h3>DETALLE SIOM</h3>');
            $('#contTablaDetalleSiom').html(data.tablaDetalle);
            initDataTable('#tbDetalleEnvioSiom');
            modal('modal_detalle_siom');
        } else {
            return;
        }
    });
}

function getDetalleTrans(btn) {
    console.log("ENTRO");
    var tabla   = btn.data('tabla');
    var tipo    = btn.data('tipo');
    var dia     = btn.data('dia');
    // if(flgTipo == null || flgTipo == '' || exito == '' || exito == null) {
    //     console.log("ENTRO11");
    //     return;
    // }

    $.ajax({
        type : 'POST',
        url  : 'getTablaDetalleTransferencia',
        data : { tabla : tabla,
                 tipo  : tipo,
                 dia   : dia }

    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            $('#tituloModalTrans').html('<h3>DETALLE TRANSFERENCIA</h3>');
            $('#contTablaDetalleTrans').html(data.tablaDetalle);
            initDataTable('#tbDetalleTrans');
            modal('modal_detalle_trans');
        } else {
            return;
        }
    });
}

function getDetalleTramaSirope(btn) {
	var estado      = btn.data('estado');
    var exito       = btn.data('exito');
    var intervalo_h = btn.data('intervalo_h');
	
	$.ajax({
        type : 'POST',
        url  : 'getDetalleTramaSirope',
        data : { estado : estado,
                 exito  : exito,
                 intervalo_h : intervalo_h }

    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            $('#tituloModalDetalleSirope').html('<h3>DETALLE SIROPE</h3>');
            $('#contTablaDetalleSirope').html(data.tablaDetalle);
            initDataTable('#tbDetalleSiropeDet');
            modal('modal_detalle_sirope');
        } else {
			return;
        }
    });
}

function openModalReenviarTrama(btn) {
	
    var itemplan 	= btn.data('itemplan');
   
    console.log('itemplan:'+itemplan);
	
	swal({
        title: 'Est&aacute; seguro de reenviar la Trama?',
        text: 'Asegurese de que la informacion sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, enviar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
		console.log('reenviar...');
		modal('modal_detalle_sirope');
		$.ajax({
	        type	:	'POST',
	    	'url'	:	'reSendSirope',
	    	data	:	{itemplan	:  itemplan},
	    	'async'	:	false
	  	})
		  .done(function(data) {  
			    data = JSON.parse(data);
		    	if(data.error == 0){		    		
		    		swal({
                        title: 'Se realizo el reenvio para el Itemplan: ' + itemplan + '',
                        text: 'Asegurese de validar la informacion!',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'OK!'
                    });
		    		
		    		
				}else if(data.error == 1){
					mostrarNotificacion('warning','No se pudo enviar la Trama', data.msj);
				}
	  	  })
	  	  .fail(function(jqXHR, textStatus, errorThrown) {
	  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
	  	  })
	  	  .always(function() {
	      	 
	  	});
		
	});	
}

function openModalReenviarTramaFecPrev(btn) {
	
    var itemplan 	= btn.data('itemplan');
   
    console.log('itemplan:'+itemplan);
	
	swal({
        title: 'Est&aacute; seguro de reenviar la Trama?',
        text: 'Asegurese de que la informacion sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, enviar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
		console.log('reenviar...');
		modal('modal_detalle_sirope');
		$.ajax({
	        type	:	'POST',
	    	'url'	:	'reSendSiropeFecPrev',
	    	data	:	{itemplan	:  itemplan},
	    	'async'	:	false
	  	})
		  .done(function(data) {  
			    data = JSON.parse(data);
		    	if(data.error == 0){		    		
		    		swal({
                        title: 'Se realizo el reenvio para el Itemplan: ' + itemplan + '',
                        text: 'Asegurese de validar la informacion!',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'OK!'
                    });
		    		
		    		
				}else if(data.error == 1){
					mostrarNotificacion('warning','No se pudo enviar la Trama', data.msj);
				}
	  	  })
	  	  .fail(function(jqXHR, textStatus, errorThrown) {
	  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
	  	  })
	  	  .always(function() {
	      	 
	  	});
		
	});	
}

function getDetalleTramaRpaSap(btn) {
	var estado      = btn.data('estado');
    var rango       = btn.data('rango');
    var fecha = btn.data('fecha');
	
	$.ajax({
        type : 'POST',
        url  : 'getDetRangoRpaSap',
        data : { estado : estado,
	        	rango  : rango,
	        	fecha : fecha }

    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#tituloModalDetalleRpaSap').html('<h3>DETALLE RPA SAP</h3>');
            $('#contTablaDetalleRpa').html(data.tablaDetalleRpa);
            initDataTable('#tbDetalleSapRpa');
            modal('modal_detalle_rpaSap');
        } else {
            return;
        }
    });
}

function getDetalleTramaRpaCoti(btn) {
	var estado = btn.data('estado');
    var rango  = btn.data('rango');
    var fecha  = btn.data('fecha');

	$.ajax({
        type : 'POST',
        url  : 'getDetalleRpaCotizacion',
        data : { estado : estado,
	        	 rango  : rango,
	        	 fecha  : fecha }

    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#tituloModalDetalleRpaCoti').html('<h3>DETALLE RPA COTIZACION</h3>');
            $('#contTablaDetalleRpaCoti').html(data.tablaDetalleRpaCoti);
            initDataTable('#tbDetalleCotiRpa');
            modal('modal_detalle_rpa_coti');
        } else {
            return;
        }
    });
}

	function getDetalleTramaRpaVr(btn) {
		var estado      = btn.data('estado');
		var rango       = btn.data('rango');
		var fecha = btn.data('fecha');
		
		$.ajax({
			type : 'POST',
			url  : 'getDetalleTramaRpaVr',
			data : { estado : estado,
					rango  : rango,
					fecha : fecha }

		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				$('#tituloModalDetalleRpaSap').html('<h3>DETALLE RPA VR</h3>');
				$('#contTablaDetalleRpa').html(data.tablaDetalleRpaVr);
				initDataTable('#tbDetalleSapRpa');
				modal('modal_detalle_rpaSap');
			} else {
				return;
			}
		});
	}

	function reenviarCotizacion(btn) {
		var codigo_coti = btn.data('codigo_coti');
		
		if(codigo_coti == null || codigo_coti == '') {
			return;
		}
		
		$.ajax({
			type : 'POST',
			url  : 'reenviarCotizacion',
			data : { codigo_coti : codigo_coti }

		}).done(function(data){
			data = JSON.parse(data);
			if(data.error == 0) {
				mostrarNotificacion('success','Correcto','Se envio la trama correctamente');
			} else {
				mostrarNotificacion('warning','Incorrecto',data.msj);
			}
		});
	}