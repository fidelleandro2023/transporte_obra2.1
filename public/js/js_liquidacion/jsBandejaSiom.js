var itemplanGlobal   = null;
var idEstacionGlobal = null;

function openModalCodigoSiom(btn) {
    $('#valida').html(null);
    itemplanGlobal   = btn.data('itemplan');
    idEstacionGlobal = btn.data('id_estacion');
    modal('modalCodigoSiom');
}

function asignarCodigoSiom() {
    var codigoSiom = $('#inputCodigoSiom').val();
    var remedy     = $('#inputRemedy').val();

    if(codigoSiom == null || codigoSiom == '') {
        $('#valida').html('<a style="color:red">Ingresar c&oacute;digo siom</a>')
        return;
    } else {
        $('#valida').html(null);
    }

    //if(remedy == null || remedy == '') {
       // $('#validaRemedy').html('<a style="color:red">Ingresar Remedy</a>')
        //return;
    //} else {
       // $('#validaRemedy').html(null);
    //}

    if(itemplanGlobal == null || itemplanGlobal == '' || idEstacionGlobal == null || idEstacionGlobal == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'asignarCodigoSiom',
        data : { itemplan   : itemplanGlobal,
                 idEstacion : idEstacionGlobal,
                 codigoSiom : codigoSiom,
                 remedy     : remedy } 
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            mostrarNotificacion('success','ingreso correcto', 'correcto');
            $('#contTabla').html(data.tablaBandejaSiom);
            initDataTable('#data-table');
            modal('modalCodigoSiom');
        } else {
            mostrarNotificacion('error','No se ingreso', data.msj);
        }
    });
}

function filtrarTablaSiom() {
    var idEmpresaColab = $('#cmbEcc option:selected').val();
    var idSubProyecto  = $('#cmbSubProyecto option:selected').val();    
    var jefatura       = $('#cmbJefatura option:selected').val();
    var noEnviado      = $('#cmbNoEnviado option:selected').val();
	var itemplan       = $('#txtItemplan').val();
	
    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaSiom',
        data : { idEmpresaColab   : idEmpresaColab,
                 idSubProyecto    : idSubProyecto,
                 jefatura         : jefatura,
                 noEnviado		  :	noEnviado,
                 itemplan		  : itemplan } 
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

var itemplanGlobalDelete   = null;
var idEstacionGlobalDelete = null;
function openModalAlerta(btn) {
    itemplanGlobalDelete   = btn.data('itemplan');
    idEstacionGlobalDelete = btn.data('id_estacion');
    modal('modalAlertaAceptacion');
}

function deleteRegistroSiom() {
    $.ajax({
        type : 'POST',
        url  : 'deleteRegistroSiom',
        data : { itemplan   : itemplanGlobalDelete,
                 idEstacion : idEstacionGlobalDelete }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            mostrarNotificacion('success','Se elimin&oacute; correctamente', 'correcto');
            $('#contTabla').html(data.tablaBandejaSiom);
            initDataTable('#data-table');
            modal('modalAlertaAceptacion');
        } else {
            mostrarNotificacion('error','No se elimin&oacute;', data.msj);
        }
    });
}

function openModalReenviarTrama(btn) {
    var idSiomObra 	= btn.data('id_siom');
    var itemplan 	= btn.data('itemplan');
    var ptr		 	= btn.data('ptr');
    var idEstacion 	= btn.data('id_estacion');
    var estacionDesc= btn.data('estacion_desc');
    console.log('faq');
    $.ajax({
	    	type	:	'POST',
	    	'url'	:	'getNodSiom',
	    	data	:	{id_siom_obra	:  idSiomObra},
	    	'async'	:	false
	    })
	    .done(function(data){
	    	var data	=	JSON.parse(data);
	    	if(data.error == 0){   
	    		$('#btnSaveSendTrama').attr('data-id_siom',idSiomObra);
	    		$('#btnSaveSendTrama').attr('data-itemplan',itemplan);
	    		$('#btnSaveSendTrama').attr('data-ptr', ptr);
	    		$('#btnSaveSendTrama').attr('data-idEstacion',idEstacion);
	    		$('#btnSaveSendTrama').attr('data-estacionDesc', estacionDesc);
	    		$('#txt_mdf').val(data.codigoCentralObra);
	    		$('#selectMDF').html(data.listaNodos);
                $('#selectMDF').val(data.idCentralObra).trigger('chosen:updated');
	    		$('#modalReenviarTrama').modal('toggle'); 
	    	}else if(data.error == 1){     				
			mostrarNotificacion('error','Error el asociar Grafo',data.msj);
		}
	  })
	  .fail(function(jqXHR, textStatus, errorThrown) {
	     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
	  })
	  .always(function() {
  	 
	});
}

$('#formReenviarTrama')
.bootstrapValidator({
    container: '#mensajeForm',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {
    	
    	selectMDF: {
            validators: {
                notEmpty: {
                    message: '<p style="color:red">(*) Debe seleccionar MDF de Reenvio.</p>'
                }
            }
        }
    }
}).on('success.form.bv', function(e) {
	e.preventDefault();
		
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
    	
    var $form    = $(e.target),
        formData = new FormData(),
        params   = $form.serializeArray(),
        bv       = $form.data('bootstrapValidator');	 
   
	    $.each(params, function(i, val) {
	        formData.append(val.name, val.value);
	    });
	    
	    var $id_siom = $('#btnSaveSendTrama').attr('data-id_siom');
	    formData.append('id_siom_obra', $id_siom);
	    var $itemplan = $('#btnSaveSendTrama').attr('data-itemplan');
	    formData.append('itemplan', $itemplan);
	    var $ptr 	= $('#btnSaveSendTrama').attr('data-ptr');
	    formData.append('ptr', $ptr);
	    var $idEstacion 	= $('#btnSaveSendTrama').attr('data-idEstacion');
	    formData.append('idEstacion', $idEstacion);
	    var $estacionDesc 	= $('#btnSaveSendTrama').attr('data-estacionDesc');
	    formData.append('estacionDesc', $estacionDesc);
	    $('#modalReenviarTrama').modal('toggle');
	    
	    $.ajax({
	        data: formData,
	        url: "reSendSiom",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
		  .done(function(data) {  
			    data = JSON.parse(data);
		    	if(data.error == 0){		    		
		    		swal({
                        title: 'Se genero correctamente la OS: ' + data.codigo_siom + '',
                        text: 'Asegurese de validar la informacion!',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'OK!'
                    });
		    		
		    		filtrarTablaSiom();
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
        $('#formReenviarTrama').bootstrapValidator('revalidateField', 'selectMDF');
		//$('#formAddPlanobra').bootstrapValidator('resetForm', true); 
    });
});

function mostrarLogSiom(component) {
    var idSiom = $(component).attr('data-id_siom');

   console.log('idSiom:'+idSiom);

    $.ajax({
        type : 'POST',
        url  : 'getLogBanSiom',
        data : { id_siom : idSiom }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) { 
            $('#contTablaSiom').html(data.tablaSiom);
            modal('modalSiom');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    })
}
/*
function nuevoEnvioSiom(component) {
	var idSiom = $(component).attr('data-id_siom');

	   console.log('idSiom:'+idSiom);
	   swal({
	       title: 'Est&aacute; seguro de generar una nueva OS?',
	       text: 'Se generar una nueva OS con el mismo Itemplan - Codigo PO - Estacion.',
	       type: 'warning',
	       showCancelButton: true,
	       buttonsStyling: false,
	       confirmButtonClass: 'btn btn-primary',
	       confirmButtonText: 'Si, generar nueva OS!',
	       cancelButtonClass: 'btn btn-secondary',
	       allowOutsideClick: false
	   }).then(function(){
		   $.ajax({
		        type : 'POST',
		        url  : 'newSiomOS',
		        data : { id_siom : idSiom }
		    }).done(function(data){
		        data = JSON.parse(data);
		        if(data.error == 0) {
		        	swal({
                        title: 'Se genero correctamente la OS: ' + data.codigo_siom + '',
                        text: 'Asegurese de validar la informacion!',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'OK!'
                    })
		    		filtrarTablaSiom();	
		        } else {
		            mostrarNotificacion('error', data.msj, 'error');
		        }
		    })
	   }, function(dismiss) {
	       console.log('cancelado');
	   	// dismiss can be "cancel" | "close" | "outside"
	       $('#formReenviarTrama').bootstrapValidator('revalidateField', 'selectMDF');
			//$('#formAddPlanobra').bootstrapValidator('resetForm', true); 
	   });
}
*/

function nuevoEnvioSiom(component) {
	var idSiom 		= $(component).attr('data-id_siom');
	var itemplan 	= $(component).attr('data-itemplan');
	var idEstacion 	= $(component).attr('data-id_estacion'); 
	 $.ajax({
	        type : 'POST',
	        url  : 'getEstaSiom',
	        data : { itemplan : itemplan }
	    }).done(function(data){
	        data = JSON.parse(data);
	        if(data.error == 0) {
	        	$('#btnSaveSendTrama2').attr('data-id_siom',idSiom);
	    		$('#btnSaveSendTrama2').attr('data-itemplan',itemplan);
	    		
	        	$('#selectEsta').html(data.listaEstacion);
                $('#selectEsta').val(idEstacion).trigger('chosen:updated');
	    		$('#modalNuevoEnvioEstacion').modal('toggle');
	        } else {
	            mostrarNotificacion('error', data.msj, 'error');
	        }
	    })
}

$('#formReenviarTrama2')
.bootstrapValidator({
    container: '#mensajeForm',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {
    	
    	selectEsta: {
            validators: {
                notEmpty: {
                    message: '<p style="color:red">(*) Debe seleccionar Estacion de Reenvio.</p>'
                }
            }
        }
    }
}).on('success.form.bv', function(e) {
	e.preventDefault();
		
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
    	
    var $form    = $(e.target),
        formData = new FormData(),
        params   = $form.serializeArray(),
        bv       = $form.data('bootstrapValidator');	 
   
	    $.each(params, function(i, val) {
	        formData.append(val.name, val.value);
	    });
	    
	    var id_siom = $('#btnSaveSendTrama2').attr('data-id_siom');
	    formData.append('id_siom', id_siom);
	    var itemplan = $('#btnSaveSendTrama2').attr('data-itemplan');
	    formData.append('itemplan', itemplan);
	    var id_estacion = $('#selectEsta').val();
	    formData.append('id_estacion', id_estacion);
	   /* var estacionDesc = $('#selectEsta').text();
	    formData.append('estacionDesc', estacionDesc);*/
	    
	    
	    $('#modalNuevoEnvioEstacion').modal('toggle');
	    $.ajax({
	        data: formData,
	        url: "newSiomOS",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
		  .done(function(data) {  
			    data = JSON.parse(data);
		    	if(data.error == 0){		    		
		    		swal({
                        title: 'Se genero correctamente la OS: ' + data.codigo_siom + '',
                        text: 'Asegurese de validar la informacion!',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'OK!'
                    });
		    		
		    		filtrarTablaSiom();
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
        $('#formReenviarTrama').bootstrapValidator('revalidateField', 'selectMDF');
		//$('#formAddPlanobra').bootstrapValidator('resetForm', true); 
    });
});