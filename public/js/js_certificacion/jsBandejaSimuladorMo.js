function filtrarTablaHG() {
    var tipObra 	= $('#selectTipo option:selected').val();
    var sinDJ  		= $('#selectSDJ option:selected').val();  
    var conDJ  		= $('#selecCDJ option:selected').val(); 
    var conExpe 	= $('#selectCExpe option:selected').val();
    var validadas   = $('#selectValid option:selected').val();
    
    if(sinDJ == '' && conDJ == '' && conExpe	==	''){
    	alert('Seleccione almenos un Criterio de Busqueda');
    }else{
    	 $.ajax({
    	        type : 'POST',
    	        url  : 'filtrarTbSimu',
    	    	data : {tipObra     : tipObra,
    		        	sinDJ    	: sinDJ,
    		        	conDJ      	: conDJ,
    		        	conExpe		: conExpe,
    		        	validadas	: validadas} 
    	    }).done(function(data){
    	        data = JSON.parse(data);
    	        if(data.error == 0) {
    	            $('#contTabla').html(data.tablaBandejaHG);
    	            initDataTable('#data-table');
    	        } else {
    	            mostrarNotificacion('error','verificar', data.msj);
    	        }
    	    });
    }
   
}

/**ANTIGUO**/
$('#formCargaOC')
.bootstrapValidator({
    container: '#mensajeForm',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {
    	txtOrdenCompra: {
            validators: {
                notEmpty: {
                    message: '<p style="color:red">(*) Debe Ingresar Orden de Compra.</p>'
                }
            }
        },
        txtNroCertificacion: {
            validators: {
                notEmpty: {
                    message: '<p style="color:red">(*) Debe Ingresar Nro Certificacion.</p>'
                }
            }
        }        
    }
}).on('success.form.bv', function(e) {
	e.preventDefault();
		
	swal({
        title: 'Est&aacute; seguro de registrar la OC y Nro de Certificacion?',
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
	    
	    var id_hg = $('#btnAsignarOC').attr('data-idHg');
	    formData.append('id_hg', id_hg);
	   
	   
	    
	    $.ajax({
	        data: formData,
	        url: "certHojaGes",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
		  .done(function(data) {  
			    data = JSON.parse(data);
		    	if(data.error == 0){	
		    		$('#modalCertificarHG').modal('toggle');
		    		swal({
                        title: 'Se certifico la Hoja de Gestion',
                        text: 'Asegurese de validar la informacion!',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'OK!'
                    });
		    		
		    		filtrarTablaHG();
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

function getPtrByHojaGestion(component) {
    var hg 		= $(component).attr('data-hg');
    var idhg 	= $(component).attr('data-idhg');
    console.log('hg:'+hg);

    $.ajax({
        type : 'POST',
        url  : 'getPtrByHGestion',
        data : { idhg	:	idhg,
        	     hg 	: 	hg }
    }).done(function(data){
        data = JSON.parse(data);
        	
        if(data.error == 0) {
        	
        	$('#tittleCertificarHG').html('CERTIFICAR HG: '+hg);
            $('#contTablaSiom').html(data.tablaSiom);
            initDataTable('#data-table2');
            
        	if(data.estado==1){//RESERVADO
        		$('#contCertificacion').hide();
        	}else if(data.estado==2){
        		$('#formCargaOC').bootstrapValidator('resetForm', true);
                $('#txtOrdenCompra').val(data.oc);
                $('#txtCestaCa').val(data.cesta);
                $('#txtOrdenCompra').attr('disabled', false);
        		$('#txtNroCertificacion').attr('disabled', false);
                $('#btnAsignarOC').attr('data-idHg', idhg);
        		$('#contCertificacion').show();
        		$('#contBtnAsignarOC').show();
        	}else if(data.estado==3){
        		$('#contCertificacion').show();
        		$('#txtOrdenCompra').val(data.oc);
                $('#txtCestaCa').val(data.cesta);
                $('#txtNroCertificacion').val(data.nro_cert);
        		$('#txtOrdenCompra').attr('disabled', true);
        		$('#txtNroCertificacion').attr('disabled', true);
        		$('#contBtnAsignarOC').hide();
        	}else{
        		$('#contCertificacion').hide();
        	}
            
            modal('modalCertificarHG');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    })
}



$('#sendEnProcesoForm')
.bootstrapValidator({
    container: '#mensajeForm2',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {
    	
    	txtCesta: {
            validators: {
                notEmpty: {
                    message: '<p style="color:red">(*) Debe ingresar una Cesta.</p>'
                }
            }
        },
        txtOC: {
            
        }
    }
}).on('success.form.bv', function(e) {
	e.preventDefault();
		
	var hojaGestion = $('#btnSendEnProceso').attr('data-hgtxt');
	swal({
        title: 'Est&aacute; seguro de cambiar de estado a "EN PROCESO" la Hoja de Gestion '+hojaGestion+'?',
        text: 'Asegurese de que la informacion sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, enviar!',
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
	    
	    var id_hg = $('#btnSendEnProceso').attr('data-hg');
	    formData.append('id', id_hg);
	    
	    
	    $.ajax({
	        data: formData,
	        url: "updateHG",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
		  .done(function(data) {  
			    data = JSON.parse(data);
		    	if(data.error == 0){
		    		modal('modalEnProceso');		    		
		    		swal({
		    			title: 'Se Actualizo la Hoja de Gestion '+hojaGestion+'',
                        text: 'Asegurese de validar la informacion!',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'OK!'
                    });
		    		
		    		filtrarTablaHG();
				}else if(data.error == 1){
					console.log(data.error);
				}
	  	  })
	  	  .fail(function(jqXHR, textStatus, errorThrown) {
	  		mostrarNotificacion('error','Verificar','Comuníquese con alguna persona a cargo :(');
	  	  })
	  	  .always(function() {
	      	 
	  	});
	    
    }, function(dismiss) {
        console.log('cancelado');
        $('#sendEnProcesoForm').bootstrapValidator('revalidateField', 'txtCesta');
    	// dismiss can be "cancel" | "close" | "outside"
		//$('#sendEnProcesoForm').bootstrapValidator('resetForm', true); 
    });

})

function enProcesoHg(component){
	var hojaGestion	= $(component).attr('data-hg');
	var hojaGestiontxt	= $(component).attr('data-hgtxt');
	$('#tituloModalEnPro').html(hojaGestiontxt);
	$('#btnSendEnProceso').attr('data-hg', hojaGestion);
	$('#btnSendEnProceso').attr('data-hgtxt', hojaGestiontxt);
	$('#sendEnProcesoForm').bootstrapValidator('resetForm', true); 
	modal('modalEnProceso');	
       
}
/***antiguo***/
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
	            mostrarNotificacion('error', data.msj, 'Verificar');
	        }
	    })
}

