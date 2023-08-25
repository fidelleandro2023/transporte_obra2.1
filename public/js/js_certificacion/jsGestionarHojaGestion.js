var listaFileValido = null;

function removeTRreservado(component){
	var ptr = $(component).attr('data-ptr');
	var id_hg = $(component).attr('data-id_hg');
	var hg_txt = $(component).attr('data-hg_txt');
	swal({
        title: 'Est&aacute; seguro de eliminar la PO de la Hoja de Gestion?',
        text: 'La PO quedara libre.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, enviar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
		  $.ajax({
		    	type	:	'POST',
		    	'url'	:	'delPtrFrHg',
		    	data	:	{ ptr : ptr,
	    					id_hg : id_hg,
							hg_txt : hg_txt},
		    	'async'	:	false
		    }).done(function(data) {  
			    data = JSON.parse(data);
		    	if(data.error == 0){	    		
		    		console.log('se borro');
		    		$('#data-table2').DataTable().row( $(component).parents('tr') ).remove().draw();
				}else if(data.error == 1){
					 mostrarNotificacion('error', 'Verificar', data.msj);
				}
		  })
		  .fail(function(jqXHR, textStatus, errorThrown) {
			mostrarNotificacion('error','Verificar','Comuníquese con alguna persona a cargo :(');
		  })
		  .always(function() {
	    	 
		});
    });
}


function removeTR(component){

        	var indice = $(component).attr('data-indice');
        	//var indice_val = $(component).attr('data-indice_val');
        	 
            if(indice!=null){            	
            	delete listaFileValido[indice];                   
            }           
            
        	//$('#tr'+indice).remove();
        	//listaFileTemp.splice(indice, 1);
        	/*delete listaFileTemp[indice];*/
        	$('#data-table2').DataTable().row( $(component).parents('tr') ).remove().draw();
        	console.log(listaFileValido);
}

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
    	txtCestaCa: {
            validators: {
                notEmpty: {
                    message: '<p style="color:red">(*) Debe Ingresar una Cesta.</p>'
                }
            }
        },
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
        text: 'Todas las PO pasaran a estado Certificado, Asegurese de que la informacion sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, enviar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
    	/*
    var $form    = $(e.target),
        formData = new FormData(),
        params   = $form.serializeArray(),
        bv       = $form.data('bootstrapValidator');	 
   
	    $.each(params, function(i, val) {
	        formData.append(val.name, val.value);
	    });
	    */
	    var id_hg = $('#btnAsignarOC').attr('data-idHg');
	    //formData.append('id_hg', id_hg);
	   
	    var jsonDataFile = listaFileValido;
    	var txtCestaCa = $('#txtCestaCa').val();
    	var txtOrdenCompra = $('#txtOrdenCompra').val();
    	var txtNroCertificacion = $('#txtNroCertificacion').val();
 	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'certHojaGes',
         	    	data	:	{ jsonDataFile : JSON.stringify(jsonDataFile),
         	    					id_hg : id_hg,
         	    					txtCestaCa : txtCestaCa,
         	    					txtOrdenCompra : txtOrdenCompra,
         	    					txtNroCertificacion : txtNroCertificacion},
         	    	'async'	:	false
         	    }).done(function(data) {  
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
    					 mostrarNotificacion('error', 'Verificar', data.msj);
    				}
    	  	  })
    	  	  .fail(function(jqXHR, textStatus, errorThrown) {
    	  		mostrarNotificacion('error','Verificar','Comuníquese con alguna persona a cargo :(');
    	  	  })
    	  	  .always(function() {
    	      	 
    	  	});
	    
    	/*
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
	      	 
	  	});*/
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
        	listaFileValido = null;
        	var fileValido =  	JSON.parse(data.jsonDataFIleValido);
        	listaFileValido   =   fileValido;         	  
    		console.log(listaFileValido);
        	
        	
        	$('#tittleCertificarHG').html('CERTIFICAR HG: '+hg);
            $('#contTablaSiom').html(data.tablaSiom);
            initDataTable('#data-table2');
			
			$('#conTablaLog').html(data.tablaLog);
			initDataTable('#tabla_log');
            
        	if(data.estado==1 || data.estado== 2){//RESERVADO
        		$('#formCargaOC').hide();
        	}else if(data.estado==3){
        		$('#formCargaOC').bootstrapValidator('resetForm', true);
                $('#txtOrdenCompra').val(data.oc);
                $('#txtCestaCa').val(data.cesta);
                $('#txtOrdenCompra').attr('disabled', false);
        		$('#txtNroCertificacion').attr('disabled', false);
                $('#btnAsignarOC').attr('data-idHg', idhg);
        		$('#contCertificacion').show();
        		$('#contBtnAsignarOC').show();
        	}else if(data.estado==4){
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
            mostrarNotificacion('error', data.msj, 'Verificar');
        }
    })
}

function filtrarTablaHG() {
    var tipObra 	= $('#selectTipo option:selected').val();
    var eecc  		= $('#selectEecc option:selected').val();  
    var estado  	= $('#selectEstado option:selected').val(); 
    var hojaGestion = $('#txtHojaGestion').val();
    var codigoPo    = $('#txtCodigoPo').val();
    var cesta		= $('#txtCesta').val();
    
    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaHG',
        data : {tipObra     : tipObra,
	        	eecc    	: eecc,
	        	estado      : estado,
	        	hojaGestion	: hojaGestion,
	        	codigoPo	: codigoPo,
	        	cesta		: cesta} 
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandejaHG);
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error','Verificar', data.msj);
        }
    });
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
					mostrarNotificacion('error', 'Verificar', data.msj);
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