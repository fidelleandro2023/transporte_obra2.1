

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
