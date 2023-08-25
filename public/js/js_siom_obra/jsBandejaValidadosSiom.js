
function filtrarTablaSiom() {
    var idEmpresaColab = $('#cmbEcc option:selected').val();
    var idSubProyecto  = $('#cmbSubProyecto option:selected').val();    
    var jefatura       = $('#cmbJefatura option:selected').val();
    var validado       = $('#selectValidado option:selected').val();
    var sirope         = $('#selectSirope option:selected').val();
    var situacion      = $('#selectSituacion option:selected').val();
    
    $.ajax({
        type : 'POST',
        url  : 'filTablaValidadosSiom',
        data : { idEmpresaColab   : idEmpresaColab,
                 idSubProyecto    : idSubProyecto,
                 jefatura         : jefatura,
                 validado		  :	validado,
                 sirope			  : sirope,
                 situacion		  : situacion} 
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandejaSiom);
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error','error', data.msj);
        }
    });
}

function modalObservar(component) {
	var idSiom 		= $(component).attr('data-id_siom');
	/*
	var itemplan 	= $(component).attr('data-itemplan');
	var idEstacion 	= $(component).attr('data-id_estacion'); 
	*/
	        	$('#btnSaveObs').attr('data-id_siom',idSiom);
	    		//$('#btnSaveSendTrama2').attr('data-itemplan',itemplan);	    		
	        	$('#idComentario').val('');
	        	$('#selectMotivo').val('').trigger('change');
	    		$('#modalObservar').modal('toggle');
	      
	       
}


$('#formSendObserOS')
.bootstrapValidator({
    container: '#mensajeForm',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {
    	
    	selectMotivo: {
            validators: {
                notEmpty: {
                    message: '<p style="color:red">(*) Debe seleccionar un Motivo.</p>'
                }
            }
        },
        idComentario: {
            validators: {
                notEmpty: {
                    message: '<p style="color:red">(*) Debe Ingresar un comentario.</p>'
                }
            }
        }
    }
}).on('success.form.bv', function(e) {
	e.preventDefault();
		
	swal({
        title: 'Est&aacute; seguro de Observar la OS?',
        text: 'Asegurese de que la informacion sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, enviar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
    	
    var $form    = $(e.target),
        formData = new FormData(),
        params   = $form.serializeArray(),
        bv       = $form.data('bootstrapValidator');	 
   
	    $.each(params, function(i, val) {
	        formData.append(val.name, val.value);
	    });
	    
	    var $id_siom = $('#btnSaveObs').attr('data-id_siom');
	    formData.append('codigoSiom', $id_siom);
	    
	    /**filtros para frescar tabla**/
	    var idEmpresaColab = $('#cmbEcc option:selected').val();
	    var idSubProyecto  = $('#cmbSubProyecto option:selected').val();    
	    var jefatura       = $('#cmbJefatura option:selected').val();
	    var validado       = $('#selectValidado option:selected').val();
	    var sirope         = $('#selectSirope option:selected').val();	    
	    var situacion      = $('#selectSituacion option:selected').val();
	    
	    
	    formData.append('idEmpresaColab', idEmpresaColab);
	    formData.append('idSubProyecto', idSubProyecto);
	    formData.append('jefatura', jefatura);
	    formData.append('validado', validado);
	    formData.append('sirope', sirope);  
	    formData.append('situacion', situacion);
	    /*********************************/
	    
	    $.ajax({
	        data: formData,
	        url: "saveObservaSiom",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
		  .done(function(data) {  
			    data = JSON.parse(data);
		    	if(data.error == 0){
		    		 $('#contTabla').html(data.tablaBandejaSiom);
		             initDataTable('#data-table');
		    		 $('#modalObservar').modal('toggle');		    		
		    		//filtrarTablaSiom();
				}else if(data.error == 1){
					console.log(data.error);
				}
	  	  })
	  	  .fail(function(jqXHR, textStatus, errorThrown) {
	  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
	  	  })
	  	  .always(function() {
	      	 
	  	});
	  	}, function(dismiss) {
        console.log('cancelado');
    	// dismiss can be "cancel" | "close" | "outside"
        //$('#formSendObserOS').bootstrapValidator('revalidateField', 'selectMotivo');
		//$('#formSendObserOS').bootstrapValidator('resetForm', true); 
        
    });
});


function modalDesObservar(component) {
	var idSiom 		= $(component).attr('data-id_siom');
	/*
	var itemplan 	= $(component).attr('data-itemplan');
	var idEstacion 	= $(component).attr('data-id_estacion'); 
	*/
	        	$('#btnSaveDesObs').attr('data-id_siom',idSiom);
	    		//$('#btnSaveSendTrama2').attr('data-itemplan',itemplan);	    		
	        	$('#idComentarioDes').val('');
	        	//$('#selectMotivo').val('').trigger('change');
	    		$('#modalDesObservar').modal('toggle');
	      
	       
}


$('#formSendDesObserOS')
.bootstrapValidator({
    container: '#mensajeForm2',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {
    	idComentarioDes: {
            validators: {
                notEmpty: {
                    message: '<p style="color:red">(*) Debe Ingresar un comentario.</p>'
                }
            }
        }
    }
}).on('success.form.bv', function(e) {
	e.preventDefault();
		
	swal({
        title: 'Est&aacute; seguro de Liberar la OS?',
        text: 'Asegurese de que la informacion sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, enviar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
    	
    var $form    = $(e.target),
        formData = new FormData(),
        params   = $form.serializeArray(),
        bv       = $form.data('bootstrapValidator');	 
   
	    $.each(params, function(i, val) {
	        formData.append(val.name, val.value);
	    });
	    
	    var $id_siom = $('#btnSaveDesObs').attr('data-id_siom');
	    formData.append('codigoSiom', $id_siom);
	    
	    /**filtros para frescar tabla**/
	    var idEmpresaColab = $('#cmbEcc option:selected').val();
	    var idSubProyecto  = $('#cmbSubProyecto option:selected').val();    
	    var jefatura       = $('#cmbJefatura option:selected').val();
	    var validado       = $('#selectValidado option:selected').val();
	    var sirope         = $('#selectSirope option:selected').val()	    
	    var situacion      = $('#selectSituacion option:selected').val();
	    
	    formData.append('idEmpresaColab', idEmpresaColab);
	    formData.append('idSubProyecto', idSubProyecto);
	    formData.append('jefatura', jefatura);
	    formData.append('validado', validado);
	    formData.append('sirope', sirope);    
	    formData.append('situacion', situacion);
	    /*********************************/
	    
	    $.ajax({
	        data: formData,
	        url: "saveDesObservaSiom",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
		  .done(function(data) {  
			    data = JSON.parse(data);
		    	if(data.error == 0){
		    		 $('#contTabla').html(data.tablaBandejaSiom);
		             initDataTable('#data-table');
		    		 $('#modalDesObservar').modal('toggle');		    		
		    		//filtrarTablaSiom();
				}else if(data.error == 1){
					console.log(data.error);
				}
	  	  })
	  	  .fail(function(jqXHR, textStatus, errorThrown) {
	  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
	  	  })
	  	  .always(function() {
	      	 
	  	});
	  	}, function(dismiss) {
        console.log('cancelado');
    	// dismiss can be "cancel" | "close" | "outside"
        //$('#formSendObserOS').bootstrapValidator('revalidateField', 'selectMotivo');
		//$('#formSendObserOS').bootstrapValidator('resetForm', true); 
        
    });
});

function mostrarLogSiom(component) {
   
	var idSiom = $(component).attr('data-id_siom');
	console.log('idSiom:'+idSiom);

    $.ajax({
        type : 'POST',
        url  : 'getLogObserSiom',
        data : { id_siom : idSiom }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) { 
            $('#contTablaSiom').html(data.tablaSiom);
            modal('modalSiomLog');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    })
}