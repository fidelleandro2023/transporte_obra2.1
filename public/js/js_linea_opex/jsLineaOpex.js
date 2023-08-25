

$('#formAddPep1Pep2')
.bootstrapValidator({
    container: '#mensajeForm2',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {	
	  selectProy: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe Seleccionar un Proyecto.</p>'
            }
         }
	   },
	   selectSubproy: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe Seleccionar un Sub Proyecto.</p>'
            }
         }
	   },
	   selectFase: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe Seleccionar una Fase.</p>'
            }
         }
	   },
	   selectEstacion: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe Seleccionar una Estacion.</p>'
            }
         }
	   },
	  inputP1P: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe Ingresar PEP1.</p>'
            },stringLength: {
            	min: 20,
                message: '<p style="color:red">(*) El PEP debe tener 20 caracteres</p>'
            }
         }
	   },
	   selectTipoArea: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe Seleccionar un tipo PEP.</p>'
            }
         }
	   },
	   inputFecProgramacion: {
	        validators: {
	            notEmpty: {
	                message: '<p style="color:red">(*) Debe Ingresar una Fecha de Programacion.</p>'
	            }
	         }
		   }
    }
}).on('success.form.bv', function(e) {
	e.preventDefault();
	console.log('OK');
	
    var $form    = $(e.target),
        formData = new FormData(),
        params   = $form.serializeArray(),
        bv       = $form.data('bootstrapValidator');	 
   
	    $.each(params, function(i, val) {
	        formData.append(val.name, val.value);
	    });


	    
	    var subProyectos = $('#selectSubproy').val();
	    formData.append('selectSubproyMulti', subProyectos);
	    
	    var fase = $('#selectFase').val();
	    console.log('fase:'+fase);
	    formData.append('selectFaseMulti', fase);
	    
	    var estacion = $('#selectEstacion').val();
	    formData.append('selectEstacionMulti', estacion);


		var pep2_revision_check=$('#id_pep2').is(':checked');

		var pep2_dato=$('#dato_pep2').val();
		var pe2;

		if(pep2_revision_check){

			
			pep2=pep2_dato;
		}else{
			
			pep2="";

		}
		

		formData.append('pep2', pep2);
		


		
	    
	    $.ajax({
	        data: formData,
	        url: "savePepBol",
	        cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
	  	})
		  .done(function(data) {
			    data = JSON.parse(data);
		    	if(data.error == 0){
		    		swal({
     	                title: 'Se registraron Correctamente los Datos!',
     	                text: 'Se registro la PEP 1',
     	                type: 'success',
     	                buttonsStyling: false,
     	                confirmButtonClass: 'btn btn-primary',
     	                confirmButtonText: 'OK!'
     	                
     	            }).then(function(){
     	            	location.reload();
     	            });
				}else if(data.error == 1){
					console.log(data.error);
				}
	  	  })
	  	  .fail(function(jqXHR, textStatus, errorThrown) {
	  		mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
	  	  })
	  	  .always(function() {
	      	 
	  	});
		  
		  
	    
});

function addNewPep1Pep2(){         	            	
	//$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputP1P', false);    	
	//$('#formAddPep1Pep2').data('bootstrapValidator').enableFieldValidators('inputCorreP', false);  
	$('#formAddPep1Pep2').data('bootstrapValidator').resetForm(true);
	$("#diasMat").val('60');//por defecto 60
	$("#diasMo").val('90');//por defecto 90
	$('#selectFase').val('5').trigger('change');
	console.log('addNewPep1Pep2:'+$('#selectFase').val());
    $('#modalAddPep1Pep2').modal('toggle');        	
  }


function changueProyect(){
    var proyecto = $.trim($('#selectProy').val()); 
     $.ajax({
        type    :   'POST',
        'url'   :   'getSubProBolsa',
        data    :   {proyecto  : proyecto},
        'async' :   false
    })
    .done(function(data){
        var data    =   JSON.parse(data);
        if(data.error == 0){
        	$('#selectSubproy').html(data.listaSubProy);
            $('#selectSubproy').val('').trigger('chosen:updated');           
        }else if(data.error == 1){            
            mostrarNotificacion('error','Hubo problemas al filtrar SubProyectos!');
        }
    });
}

function changeProyectTable(){
    var proyecto = $.trim($('#selectProyTable').val()); 
     $.ajax({
        type    :   'POST',
        'url'   :   'getSubProBolsa',
        data    :   {proyecto  : proyecto},
        'async' :   false
    })
    .done(function(data){
        var data    =   JSON.parse(data);
        if(data.error == 0){
        	$('#selectSubProTable').html('<option value="">:::SELECCIONE SUBPROYECTO:::</option>'+data.listaSubProy);
            $('#selectSubProTable').val('').trigger('chosen:updated');           
        }else if(data.error == 1){            
            mostrarNotificacion('error','Hubo problemas al filtrar SubProyectos!');
        }
    });
}

function filtrarTabla(){

	var proyecto = $('#selectProyTable').val();
	var subpro 	 = $('#selectSubProTable').val();
	var pep1 	 = $('#inputP1PTable').val();
	var pep2 	 = $('#inputP2PTable').val();
	if(proyecto ==	'' && subpro == '' && pep1 == '' && pep2 == ''){
		alert('Debe realizar un filtro de busqueda.');
	}else{
		$.ajax({
		    	type	:	'POST',
		    	'url'	:	'filtroPepBolsa',
		    	data	:	{proyecto : proyecto,
				    		subpro : subpro,
				    		pep1 : pep1,
				    		pep2 :	pep2
	                   },
		    	'async'	:	false
		    })
		    .done(function(data){
		    	var data	=	JSON.parse(data);
		    	if(data.error == 0){           	    	          	    	   
		    		$('#contTabla').html(data.tablaBolsaPep);
		    	    initDataTable('#data-table');		    		
				}else if(data.error == 1){					
					mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
				}
			  });
	}
}


function filtrarTablaMargen(){

	var proyecto = $('#selectProyTable').val();
	var subpro 	 = $('#selectSubProTable').val();
	var pep1 	 = $('#inputP1PTable').val();
	var pep2 	 = $('#inputP2PTable').val();
	if(proyecto ==	'' && subpro == '' && pep1 == '' && pep2 == ''){
		alert('Debe realizar un filtro de busqueda.');
	}else{
		$.ajax({
		    	type	:	'POST',
		    	'url'	:	'filtroMargen',
		    	data	:	{proyecto : proyecto,
				    		subpro : subpro,
				    		pep1 : pep1,
				    		pep2 :	pep2
	                   },
		    	'async'	:	false
		    })
		    .done(function(data){
		    	var data	=	JSON.parse(data);
		    	if(data.error == 0){           	    	          	    	   
		    		$('#contTabla').html(data.tablaBolsaPep);
		    	    initDataTable('#data-table');		    		
				}else if(data.error == 1){					
					mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
				}
			  });
	}
}

function init(){
	/*
	$('#selectProy').val('3').trigger('change');
	console.log('init:'+$('#selectFase').val());
	changueProyect();
	*/
}

init();
/*
function recalFecMatYMo(){
	var dateVar = $('#inputFecProgramacion').val();
	var dsplit = dateVar.split("-");
	
	
	var daysMat = parseInt($("#diasMat").val(), 10);
	if(isNaN(daysMat)){
		daysMat = 0;
	}	
	
	dMat	=	new Date(dsplit[0],dsplit[1]-1,dsplit[2]);
	dMat.setDate(dMat.getDate() + daysMat);	
	 $('#fecFinMat').flatpickr({
     	defaultDate: dMat});
	
	 var daysMo = parseInt($("#diasMo").val(), 10);
		if(isNaN(daysMo)){
			daysMo = 0;
		}
	 dMo	=	new Date(dsplit[0],dsplit[1]-1,dsplit[2]);
	 dMo.setDate(dMo.getDate() + daysMo);	
	 $('#fecFinMo').flatpickr({
     	defaultDate: dMo});	 
}
*/


function tipoMargenPep(component){
	var idPepBolsa = $(component).attr('data-id');

	var pep = $(component).attr('data-pep');

	var tipo = $(component).attr('data-tipo');
	

	if(tipo=='SIN TIPO'){

	var myArrayOfThings = [
		{ id: 'CORRELATIVO', name: 'CORRELATIVO' },
		{ id: 'FIJO', name: 'FIJO' }
	];

	var options = {};
	$.map(myArrayOfThings,
		function(o) {
			options[o.id] = o.name;
		});

   swal({
	   title: 'Ingrese un TIPO',
	   input: 'select',
       inputOptions: options,
	   type: 'warning',
	   showCancelButton: true,
	   buttonsStyling: false,
	   confirmButtonClass: 'btn btn-primary',
	   confirmButtonText: 'Asignar',
	   cancelButtonClass: 'btn btn-secondary',
	   allowOutsideClick: false
   }).then(function(result){
	   console.log('idPepBolsa:'+idPepBolsa);
	   console.log('input:'+result);


	   
	   $.ajax({
		   type : 'POST',
		   url  : 'tipomargenPep',
		   data : { idPepBolsa : idPepBolsa,tipo:result ,pep:pep}
	   }).done(function(data){
		   data = JSON.parse(data);
		   if(data.error == 0) { 
			   swal({
				   title: 'Se asigno el tipo de PEP',
				   text: 'Asegurese de validar la informacion!',
				   type: 'success',
				   buttonsStyling: false,
				   confirmButtonClass: 'btn btn-primary',
				   confirmButtonText: 'OK!'
			   }).then(function(){
				   filtrarTablaMargen();
			   });
		   } else {
			   mostrarNotificacion('error', data.msj, 'error');
		   }
	   })
	   
	   



	   
   });
  }else{
	swal({
		title: 'Validacion',
		text: 'Ya existe un tipo',
		type: 'warning',
		buttonsStyling: false,
		confirmButtonClass: 'btn btn-primary',
		confirmButtonText: 'OK!'
	}).then(function(){
		
	});
  }
}


function agregarCuentaOpex() {
    $("#inputCecoAdd").prop("disabled", false);
    $("#inputCuentaAdd").prop("disabled", false);
    $("#inputAreaFuncional").prop("disabled", false);
    //limpiar();
    $('#modalAddOpex').modal('show');
    $('#boton_multiuso').attr("onclick", 'addCuentaOpex()');
}

function agregarLineaOpex() {
    $("#inputCecoAdd").prop("disabled", false);
    $("#inputCuentaAdd").prop("disabled", false);
    $("#inputAreaFuncional").prop("disabled", false);
    //limpiar();
    $('#modalAddLineaOpex').modal('show');
    $('#boton_multiuso').attr("onclick", 'addLineaOpex()');
}

function modificarPresupuestoTransferencia() {
	

    
    $('#modalTransferenciaRecursos').modal('show');
	

	 
	
    $('#boton_multiuso').attr("onclick", 'addLineaOpexTransferencia()');
}

function agregarLineaOpexSubproyectos() {
    $("#inputCecoAdd").prop("disabled", false);
    $("#inputCuentaAdd").prop("disabled", false);
    $("#inputAreaFuncional").prop("disabled", false);
    //limpiar();
    $('#modalAddLineaSubproyectoOpex').modal('show');
    $('#boton_multiuso').attr("onclick", 'addLineaOpexSubproyecto()');
}



function addLineaOpexSubproyecto() {
    var inputCombinatoria = $('#inputCombinatoria').val();
    var inputLinea = $('#inputLinea').val();
    var selectProy = $('#selectProy').val();
    
    if (inputCombinatoria === '' || inputCombinatoria === null) {
        swal('Mensaje', 'Ingrese Presupuesto', 'warning');
        $("#inputCombinatoria").css("border", "1px solid red ");
        $("#inputCombinatoria").css("border-radius", "10px");
        $("#inputCombinatoria").focus();
        setTimeout(function () {
            $("#inputCombinatoria").css("border", "");
        }, 3000);
        return false;
    }

    if (inputLinea === '' || inputLinea === null) {
        swal('Mensaje', 'Ingrese Linea', 'warning');
        $("#inputLinea").css("border", "1px solid red ");
        $("#inputLinea").css("border-radius", "10px");
        $("#inputLinea").focus();
        setTimeout(function () {
            $("#inputLinea").css("border", "");
        }, 3000);
        return false;
    }

    if (selectProy === '' || selectProy === null) {
        swal('Mensaje', 'Ingrese Fase', 'warning');
        $("#selectProy").css("border", "1px solid red ");
        $("#selectProy").css("border-radius", "10px");
        $("#selectProy").focus();
        setTimeout(function () {
            $("#selectProy").css("border", "");
        }, 3000);
        return false;
    }
	 else {
        var dataString = 'inputCombinatoria=' + inputCombinatoria
                + '&inputLinea=' + inputLinea
                + '&selectProy=' + selectProy;

        console.log(dataString);

        $.ajax({
            type: 'POST',
            url: 'ajaxSaveOpexLineaSubproyecto',
            data: dataString
        }).done(function (data) {
            console.log(data.msj);
            data = JSON.parse(data);
            console.log(data.error == 0);
            if (data.error == 0) {
                
                mostrarNotificacion('success', 'Mensaje', data.msj);
                $('#modalAddOpex').modal('hide');
				location.reload()
            } else {
                mostrarNotificacion('error', 'Mensaje', data.msj);
            }
        });
    }
}


function addLineaOpexTransferencia() {
	
	
	
	 
	
    var inputPresupuesto = $('#inputPresupuestocambio').val();
    var inputLinearecepcion = $('#inputLinearecepcion').val();
    var inputLineaenvio = $('#inputLineaenvio').val();
	
	
	
	var inputenvioproyectado =$('#inputLineaenvio').find('option:selected').attr('data-presupuesto_envio_proyectado');
	var inputenviopresupuesto =$('#inputLineaenvio').find('option:selected').attr('data-presupuesto_envio_presupuesto');
	var inputenvioreal=$('#inputLineaenvio').find('option:selected').attr('data-presupuesto_envio_real');
	
	var inputrecepcionproyectado = $('#inputLinearecepcion').find('option:selected').attr('data-presupuesto_recepcion_proyectado');
	var inputrecepcionpresupuesto = $('#inputLinearecepcion').find('option:selected').attr('data-presupuesto_recepcion_presupuesto');
	var inputrecepcionreal = $('#inputLinearecepcion').find('option:selected').attr('data-presupuesto_recepcion_real');
	
	
	
	
	
    
    if (inputPresupuesto === '' || inputPresupuesto === null) {
        swal('Mensaje', 'Ingrese Presupuesto', 'warning');
        $("#inputPresupuesto").css("border", "1px solid red ");
        $("#inputPresupuesto").css("border-radius", "10px");
        $("#inputPresupuesto").focus();
        setTimeout(function () {
            $("#inputPresupuesto").css("border", "");
        }, 3000);
        return false;
    }

    if (inputLinearecepcion === '' || inputLinearecepcion === null) {
        swal('Mensaje', 'Ingrese Linea', 'warning');
        $("#inputLinearecepcion").css("border", "1px solid red ");
        $("#inputLinearecepcion").css("border-radius", "10px");
        $("#inputLinearecepcion").focus();
        setTimeout(function () {
            $("#inputLinearecepcion").css("border", "");
        }, 3000);
        return false;
    }

    if (inputLineaenvio === '' || inputLineaenvio === null) {
        swal('Mensaje', 'Ingrese Fase', 'warning');
        $("#inputLineaenvio").css("border", "1px solid red ");
        $("#inputLineaenvio").css("border-radius", "10px");
        $("#inputLineaenvio").focus();
        setTimeout(function () {
            $("#inputLineaenvio").css("border", "");
        }, 3000);
        return false;
    }
	 else {
        var dataString = 'inputPresupuesto=' + inputPresupuesto
                + '&inputLinearecepcion=' + inputLinearecepcion
                + '&inputLineaenvio=' + inputLineaenvio
				+ '&inputenvioproyectado=' + inputenvioproyectado
				+ '&inputenviopresupuesto=' + inputenviopresupuesto
				+ '&inputenvioreal=' + inputenvioreal
				+ '&inputrecepcionproyectado=' + inputrecepcionproyectado
				+ '&inputrecepcionpresupuesto=' + inputrecepcionpresupuesto
			    + '&inputrecepcionreal=' + inputrecepcionreal;

        console.log(dataString);
		
		
		
        $.ajax({
            type: 'POST',
            url: 'saveTableOpexPresupuestotransferencia',
            data: dataString
        }).done(function (data) {
            console.log(data.msj);
            data = JSON.parse(data);
            console.log(data.error == 0);
            if (data.error == 0) {
                
                mostrarNotificacion('success', 'Mensaje', data.msj);
                $('#modalAddLineaOpex').modal('hide');
				location.reload();
            } else {
                mostrarNotificacion('error', 'Mensaje', data.msj);
            }
        });
		
		
		
    }
}

function addLineaOpex() {
    var inputPresupuesto = $('#inputPresupuesto').val();
    var inputLinea = $('#inputLinea').val();
    var inputFase = $('#inputFase').val();
    
    if (inputPresupuesto === '' || inputPresupuesto === null) {
        swal('Mensaje', 'Ingrese Presupuesto', 'warning');
        $("#inputPresupuesto").css("border", "1px solid red ");
        $("#inputPresupuesto").css("border-radius", "10px");
        $("#inputPresupuesto").focus();
        setTimeout(function () {
            $("#inputPresupuesto").css("border", "");
        }, 3000);
        return false;
    }

    if (inputLinea === '' || inputLinea === null) {
        swal('Mensaje', 'Ingrese Linea', 'warning');
        $("#inputLinea").css("border", "1px solid red ");
        $("#inputLinea").css("border-radius", "10px");
        $("#inputLinea").focus();
        setTimeout(function () {
            $("#inputLinea").css("border", "");
        }, 3000);
        return false;
    }

    if (inputFase === '' || inputFase === null) {
        swal('Mensaje', 'Ingrese Fase', 'warning');
        $("#inputFase").css("border", "1px solid red ");
        $("#inputFase").css("border-radius", "10px");
        $("#inputFase").focus();
        setTimeout(function () {
            $("#inputFase").css("border", "");
        }, 3000);
        return false;
    }
	 else {
        var dataString = 'inputPresupuesto=' + inputPresupuesto
                + '&inputLinea=' + inputLinea
                + '&inputFase=' + inputFase;

        console.log(dataString);

        $.ajax({
            type: 'POST',
            url: 'ajaxSaveOpexLinea',
            data: dataString
        }).done(function (data) {
            console.log(data.msj);
            data = JSON.parse(data);
            console.log(data.error == 0);
            if (data.error == 0) {
                
                mostrarNotificacion('success', 'Mensaje', data.msj);
                $('#modalAddLineaOpex').modal('hide');
				location.reload();
            } else {
                mostrarNotificacion('error', 'Mensaje', data.msj);
            }
        });
    }
}

function addCuentaOpex() {
    var inputCecoAdd = $('#inputCecoAdd').val();
    var inputCuentaAdd = $('#inputCuentaAdd').val();
    var inputAreaFuncional = $('#inputAreaFuncional').val();
    
    if (inputCecoAdd === '' || inputCecoAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputCecoAdd").css("border", "1px solid red ");
        $("#inputCecoAdd").css("border-radius", "10px");
        $("#inputCecoAdd").focus();
        setTimeout(function () {
            $("#inputCecoAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (inputCuentaAdd === '' || inputCuentaAdd === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputCuentaAdd").css("border", "1px solid red ");
        $("#inputCuentaAdd").css("border-radius", "10px");
        $("#inputCuentaAdd").focus();
        setTimeout(function () {
            $("#inputCuentaAdd").css("border", "");
        }, 3000);
        return false;
    }

    if (inputAreaFuncional === '' || inputAreaFuncional === null) {
        swal('Mensaje', 'Ingrese cuenta ceco', 'warning');
        $("#inputAreaFuncional").css("border", "1px solid red ");
        $("#inputAreaFuncional").css("border-radius", "10px");
        $("#inputAreaFuncional").focus();
        setTimeout(function () {
            $("#inputAreaFuncional").css("border", "");
        }, 3000);
        return false;
    }
	 else {
        var dataString = 'inputCecoAdd=' + inputCecoAdd
                + '&inputCuentaAdd=' + inputCuentaAdd
                + '&inputAreaFuncional=' + inputAreaFuncional;

        console.log(dataString);

        $.ajax({
            type: 'POST',
            url: 'ajaxSaveOpexCombinatoria',
            data: dataString
        }).done(function (data) {
            console.log(data.msj);
            data = JSON.parse(data);
            console.log(data.error == 0);
            if (data.error == 0) {
                
                mostrarNotificacion('success', 'Mensaje', data.msj);
                $('#modalAddOpex').modal('hide');
				location.reload()
            } else {
                mostrarNotificacion('error', 'Mensaje', data.msj);
            }
        });
    }
}


function modificarPresupuesto(component){
	var identi = $(component).attr('data-id');
	var presu = $(component).attr('data-presupuesto');
	var disponible = $(component).attr('data-disponible');
	var real = $(component).attr('data-real');
	
	//var inputPresupuestoanterior = $(component).attr('data-presupuesto');
   swal({
	   title: 'MODIFICAR PRESUPUESTO',
	   input: 'text',
	   inputPlaceholder: 'insertar presupuesto',
	   type: 'warning',
	   showCancelButton: true,
	   buttonsStyling: false,
	   confirmButtonClass: 'btn btn-primary',
	   confirmButtonText: 'Asignar',
	   cancelButtonClass: 'btn btn-secondary',
	   allowOutsideClick: false
   }).then(function(result){
	 

     console.log(result);
	 console.log(identi);
	   
	   $.ajax({
		   type : 'POST',
		   url  : 'modificarpresupuestoLineaOpex',
		   data : { inputPresupuesto:result,id:identi,presu:presu,disponible:disponible,real:real }
	   }).done(function(data){
		   data = JSON.parse(data);
		   console.log(data);
		   if(data.error == 0) { 
			   swal({
				   title: 'Se creo actualizo el presupuesto',
				   text: 'Asegurese de validar la informacion!',
				   type: 'success',
				   buttonsStyling: false,
				   confirmButtonClass: 'btn btn-primary',
				   confirmButtonText: 'OK!'
			   }).then(function(){
				   //filtrarTablaMargen();
				   location.reload()
			   });
		   } else {
			   mostrarNotificacion('error', data.msj, 'error');
		   }
	   })
	   



	   
   });
}



function addMargenPep(component){
	var idPepBolsa = $(component).attr('data-id');
   swal({
	   title: 'AGREGAR UNA LINEA OPEX',
	   input: 'text',
	   inputPlaceholder: 'insertar linea opex',
	   type: 'warning',
	   showCancelButton: true,
	   buttonsStyling: false,
	   confirmButtonClass: 'btn btn-primary',
	   confirmButtonText: 'Asignar',
	   cancelButtonClass: 'btn btn-secondary',
	   allowOutsideClick: false
   }).then(function(result){
	 


	   
	   $.ajax({
		   type : 'POST',
		   url  : 'addLineaOpex',
		   data : { lineaOpex:result }
	   }).done(function(data){
		   data = JSON.parse(data);
		   console.log(data);
		   if(data.error == 0) { 
			   swal({
				   title: 'Se creo correctamente la Linea Opex',
				   text: 'Asegurese de validar la informacion!',
				   type: 'success',
				   buttonsStyling: false,
				   confirmButtonClass: 'btn btn-primary',
				   confirmButtonText: 'OK!'
			   }).then(function(){
				  // filtrarTablaMargen();
				  location.reload()
			   });
		   } else {
			   mostrarNotificacion('error', data.msj, 'error');
		   }
	   })
	   



	   
   });
}

function deletPepBolsa(component){
	 var idPepBolsa = $(component).attr('data-id');
	swal({
        title: 'Est&aacute; seguro de eliminar la configuracion Pep - Bolsa?',
        text: 'Asegurese de que la informacion sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
    	console.log('idPepBolsa:'+idPepBolsa);
    	$.ajax({
            type : 'POST',
            url  : 'delPepBolsa',
            data : { idPepBolsa : idPepBolsa }
        }).done(function(data){
            data = JSON.parse(data);
            if(data.error == 0) { 
            	swal({
                    title: 'Se elimino correctamente la Configuracion!',
                    text: 'Asegurese de validar la informacion!',
                    type: 'success',
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'OK!'
                }).then(function(){
                	filtrarTabla();
                });
            } else {
                mostrarNotificacion('error', data.msj, 'error');
            }
        })
    	
    });
}
