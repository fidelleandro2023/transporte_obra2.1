$('#formNuevoUsuario')
    	.bootstrapValidator({
    	    //container: '#mensajeForm',
    	    feedbackIcons: {
    	        valid      : 'glyphicon glyphicon-ok',
    	        invalid    : 'glyphicon glyphicon-remove',
    	        validating : 'glyphicon glyphicon-refresh'
    	    },
    	    excluded: ':disabled',
    	    fields: {
                cmbContrato :       {
                                        validators: {
                                                        container : '#msjValidContrato',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                cmbEmpresaColab :   {
                                        validators: {
                                                        container : '#msjValidEmpresa',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                inputNombreU :  {
                                        validators: {
                                                        container : '#msjValidNombre',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },	   
                cmbPerfil   :   {
                                        validators: {
                                                        container : '#msjValidPerfil',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                inputDni :            {
                                        validators: {
                                                        container : '#msjValidDni',
                                                        notEmpty:   {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }         
                                                    }    
                                    },
                inputCorreo :    {
                                        validators: {
                                                        container : '#msjValidCorreo',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    },
                                                        emailAddress: {
                                                                        message: '<p style="color:red">Ingresar su correo de forma correcta</p>'
                                                                      }
                                                    }
                                    },
                inputTelfMov:              {
                                        validators: {
                                                        container : '#msjValidTelf',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                inputImei   :   {
                                        validators: {
                                                        container : '#msjValidImei',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                cmbZona       :   {
                                        validators: {
                                                        container : '#msjValidZona',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                cmbTipoDoc    :    {
                                        validators: {
                                                        container : '#msjTipoDoc',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    }                       								
    	    }
    	}).on('success.form.bv', function(e) {
    		e.preventDefault();
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
    	        var $form = $(e.target),
    	        formData  = new FormData(),
    	        params    = $form.serializeArray(),
    	        bv        = $form.data('bootstrapValidator');	 

    		    $.each(params, function(i, val) {
    		        formData.append(val.name, val.value);
                });
                
				var idContrato = $('#cmbContrato option:selected').val();
				
				if(idContrato != 3) {
					$('#msjValidContrato').html('<p style="color:red">(*) No se esta trajando con este contrato.</p>');
					return;
				} else {
					$( "#btnSave" ).prop( "disabled", true);
					$('#msjValidContrato').html(null);
				}
                
                var arrayPerfil = $('#cmbPerfil option:selected').val();
                var arrayZona   = $('#cmbZona option:selected').val();

                if(arrayPerfil == '' || arrayPerfil == null) {
                    $('#msjValidPerfil').html('<p style="color:red">(*) campo obligatorio.</p>');
                    return;
                }

                if(arrayZona == '' || arrayZona == null) {
                    $('#msjValidZona').html('<p style="color:red">(*) campo obligatorio.</p>');
                    return;
                }
				
				var cel = $('#inputTelfMov').val();
				if(cel.length != 9) {
					$('#msjValidTelf').html('<p style="color:red">(*) debe tener 9 dígitos.</p>');
					return;
				} else {
					$('#msjValidTelf').html(null);
				}
                
                var tipoDoc = $('#cmbTipoDoc option:selected').val();
                var dni = $('#inputDni').val();
                if(tipoDoc == 1) {// SI ES DNI
                    if(dni.length != 8) {
                        $('#msjValidDni').html('<p style="color:red">(*) debe tener 8 dígitos.</p>');
                        return;
                    } else {
                        $('#msjValidDni').html(null);
                    }
                } else if(tipoDoc == 2) { // SI ES CE
                    if(dni.length != 9) {
                        $('#msjValidDni').html('<p style="color:red">(*) debe tener 9 dígitos.</p>');
                        return;
                    } else {
                        $('#msjValidDni').html(null);
                    }
                }

				
    		    $.ajax({
			        data: formData,
			        url: "ingresarSolicitud",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				  .done(function(data) {  
					    data = JSON.parse(data);
					    console.log(data.error);
				    	if(data.error == 0){
                            swal({
                                title: 'Envio Efectuado',
                                text: 'Se envió la solicitud correctamente.',
                                type: 'success',
                                showCancelButton: false,                    	            
                                allowOutsideClick: false
                            }).then(function(){
                                window.location.href = "getFormSolicitudSiom";
                            });
						}else if(data.error == 1){
                            swal({
                                    title: 'USUARIO REALIZÓ UNA ACCIÓN INDEBIDA',
                                    text: data.msj,
                                    type: 'warning',
                                    // showCancelButton: true,
                                    buttonsStyling: false,
              
                                    allowOutsideClick: false
                                })
							// mostrarNotificacion('error','Incorrecto',data.msj);
						}
			  	  });
    		   

	        }, function(dismiss) {
    	        $( "#btnSave" ).prop( "disabled", false);
	        });
        });
        
        function getDataModificacion() {
            var dni = $('#inputDniM').val();
            
            if(dni == null) {
                return;
            }

            if(dni.length != 8) {
                return;
            }

            $.ajax({
                url  : 'getDataModificacion',
                type : 'POST',
                data : { dni : dni }
            }).done(function(data){
                data = JSON.parse(data);
                if(data.error == 0) {
                    console.log(data.dataMod);
                    var perfiles = data.dataMod.array_perfil.split(',')
                    var zonas    = data.dataMod.array_zona.split(',')
            
                    $('#inputNombreUM').val(data.dataMod.nombre);
                    $('#inputCorreoM').val(data.dataMod.email);
                    $('#inputTelfMovM').val(data.dataMod.telefono);
                    $('#inputUsuarioM').val(data.dataMod.usuario);
                    $('#inputImeiM').val(data.dataMod.imei);
                    $('#cmbContratoM option[value="'+data.dataMod.idContrato+'"]').prop("selected", "selected").trigger("change");
                    $('#cmbEmpresaColabM option[value="'+data.dataMod.idEmpresaColab+'"]').prop("selected", "selected").trigger("change");
                    $('#cmbSituacion option[value="'+data.dataMod.estado+'"]').prop("selected", "selected").trigger("change");
                    
                    var estado = $('#cmbSituacion option:selected').val();
                    if(estado == 1) {
                        $('#cmbSituacion').prop( "disabled", true);
                    }

                    // $('#cmbPerfilM option[value="'+perfiles+'"]').prop("selected", "selected").trigger("change");
                    $('#cmbPerfilM').val(perfiles).trigger('change');		
                    $('#cmbZonaM').val(zonas).trigger('change');
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

        $('#formModificarUsuario')
    	.bootstrapValidator({
    	    //container: '#mensajeForm',
    	    feedbackIcons: {
    	        valid      : 'glyphicon glyphicon-ok',
    	        invalid    : 'glyphicon glyphicon-remove',
    	        validating : 'glyphicon glyphicon-refresh'
    	    },
    	    excluded: ':disabled',
    	    fields: {
                cmbContratoM :       {
                                        validators: {
                                                        container : '#msjValidContrato',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                cmbEmpresaColabM :   {
                                        validators: {
                                                        container : '#msjValidEmpresa',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                inputNombreUM :  {
                                        validators: {
                                                        container : '#msjValidNombre',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },	   
                cmbPerfilM   :   {
                                        validators: {
                                                        container : '#msjValidPerfil',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                inputDniM :            {
                                        validators: {
                                                        container : '#msjValidDni',
                                                        notEmpty:   {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }       
                                                    }    
                                    },
                inputCorreoM :    {
                                        validators: {
                                                        container : '#msjValidCorreo',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    },
                                                        emailAddress: {
                                                                        message: '<p style="color:red">Ingresar su correo de forma correcta</p>'
                                                                      }
                                                    }
                                    },
                inputTelfMovM :              {
                                        validators: {
                                                        container : '#msjValidTelf',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                inputImeiM   :   {
                                        validators: {
                                                        container : '#msjValidImei',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                cmbZonaM       :   {
                                        validators: {
                                                        container : '#msjValidZona',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    },
                cmbTipoSolicitud :  {
                                        validators: {
                                                        container : '#msjValidNuevaClave2',
                                                        notEmpty:  {
                                                                        message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                    }
                                                    }
                                    }                                								
    	    }
    	}).on('success.form.bv', function(e) {
    		e.preventDefault();
    		swal({
	            title: 'Est&aacute; seguro de enviar la Cotizacion?',
	            text: 'Asegurese de que la informacion llenada sea la correta.',
	            type: 'warning',
	            showCancelButton: true,
	            buttonsStyling: false,
	            confirmButtonClass: 'btn btn-primary',
	            confirmButtonText: 'Si, guardar los datos!',
	            cancelButtonClass: 'btn btn-secondary',
	            allowOutsideClick: false
	        }).then(function(){
    	        var $form = $(e.target),
    	        formData  = new FormData(),
    	        params    = $form.serializeArray(),
    	        bv        = $form.data('bootstrapValidator');	 

    		    $.each(params, function(i, val) {
    		        formData.append(val.name, val.value);
                });
                
				var idContrato = $('#cmbContratoM option:selected').val();
				
				if(idContrato != 3) {
					$('#msjValidContratoM').html('<p style="color:red">(*) No se esta trajando con este contrato.</p>');
					return;
				} else {
					$( "#btnGuardarModif" ).prop( "disabled", true);
					$('#msjValidContratoM').html(null);
				}
                
                var arrayPerfil = $('#cmbPerfilM option:selected').val();
                var arrayZona   = $('#cmbZonaM option:selected').val();

                
                if(arrayPerfil == '' || arrayPerfil == null) {
                    $('#msjValidPerfilM').html('<p style="color:red">(*) campo obligatorio.</p>');
                    return;
                }
				
				var cel = $('#inputTelfMovM').val();
				if(cel.length != 9) {
					$('#msjValidTelfM').html('<p style="color:red">(*) debe tener 9 dígitos.</p>');
					return;
				} else {
					$('#msjValidTelfM').html(null);
				}
				
				var dni = $('#inputDniM').val();
				if(dni.length != 8) {
					$('#msjValidDniM').html('<p style="color:red">(*) debe tener 8 dígitos.</p>');
					return;
				} else {
					$('#msjValidDniM').html(null);
				}
				
                if(arrayZona == '' || arrayZona == null) {
                    $('#msjValidZonaM').html('<p style="color:red">(*) campo obligatorio.</p>');
                    return;
                }

                var arrayPerfil = $('#cmbPerfilM').val();
                formData.append('arrayPerfil', arrayPerfil);

                var idContrato = $('#cmbContratoM').val();
                formData.append('idContrato', idContrato);

                var idEmpresaColab = $('#cmbEmpresaColabM').val();
                formData.append('idEmpresaColab', idEmpresaColab);

                var nombre = $('#inputNombreUM').val();
                formData.append('nombre', nombre);

                var imei = $('#inputImeiM').val();
                formData.append('imei', imei);

                var estado = $('#cmbSituacion option:selected').val();
                formData.append('estado', estado);
				
    		    $.ajax({
			        data: formData,
			        url: "ingresoSolicitudModificacion",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				  .done(function(data) {  
					    data = JSON.parse(data);
					    console.log(data.error);
				    	if(data.error == 0){
                            swal({
                                title: 'Envio Efectuado',
                                text: 'Se envió la solicitud correctamente.',
                                type: 'success',
                                showCancelButton: false,                    	            
                                allowOutsideClick: false
                            }).then(function(){
                                window.location.href = "getFormSolicitudSiom";
                            });
						}else if(data.error == 1){
                            swal({
                                    title: 'USUARIO REALIZÓ UNA ACCIÓN INDEBIDA',
                                    text: data.msj,
                                    type: 'warning',
                                    // showCancelButton: true,
                                    buttonsStyling: false,
              
                                    allowOutsideClick: false
                                })
							// mostrarNotificacion('error','Incorrecto',data.msj);
						}
			  	  });
    		   

	        }, function(dismiss) {
    	        $( "#btnGuardarModif" ).prop( "disabled", false);
	        });
    	});