	initTable('simpletable',10);        
	//initTable('tabla_trabajos',10);
	$( ".input-3" ).keyup(function() {
    	if(this.value > 3){
   		 	$(this).css("color","red");
    	}else{
    		$(this).css("color","black");
    	}
  	});
    $( ".input-7" ).keyup(function() {
    	if(this.value < 3 || this.value > 7){
   		 	$(this).css("color","red");
    	}else{
    		$(this).css("color","black");
    	}
  	});
    $( ".input-39" ).keyup(function() {
    	if(this.value < 36 || this.value > 39){
   		 	$(this).css("color","red");
    	}else{
    		$(this).css("color","black");
    	}
  	});
    $( ".input-42" ).keyup(function() {
    	if(this.value < 40 || this.value > 42){
   		 	$(this).css("color","red");
    	}else{
    		$(this).css("color","black");
    	}
  	});
    $( ".input-45" ).keyup(function() {
    	if(this.value < 44 || this.value > 45){
   		 	$(this).css("color","red");
    	}else{
    		$(this).css("color","black");
    	}
  	});
    $( ".input-32" ).keyup(function() {
    	if(this.value > 32){
   		 	$(this).css("color","red");
    	}else{
    		$(this).css("color","black");
    	}
  	});
	function mostrarNotificacion(tipo,titulo,mensaje){
    	/*new PNotify({
        title: titulo,
        text: mensaje,
        type: tipo,
        delay: 2000,
        styling: 'bootstrap3'
        // buttons: {
         //    sticker: false
       //  }
    });
    */
    swal({
    	  title: titulo,
    	  text: mensaje,
    	  type: tipo
        	  });
    }
		
	function openModalRregistrarFichaFoSisego(component){
		document.getElementById("formRegistrarFichaFoSisego").reset();
		$('.selectForm').val('3').trigger('change');//POR DEFENTE 1 = NUEVO 	
    	var itemplan = $(component).attr('data-itm');    
 	    $.ajax({
 	    	type	:	'POST',
 	    	'url'	:	'getIFTS',
 	    	data	:	{itemplan : itemplan},
 	    	'async'	:	false
 	    })
 	    .done(function(data){
 	    	var data	=	JSON.parse(data);
 	    	if(data.error == 0){
 	    		console.log(data.itemplan+'-'+data.coordX+'-'+data.troba);
     	    	$('#txtItemplan3').val(data.itemplan);
     	    	$('#txtSubProyecto3').val(data.subpro);
     	    	$('#txtNodo3').val(data.nodo);
     	    	$('#txtFechaInicio3').val(data.fec_inicio);
     	    	$('#txtFechaFin3').val(data.fec_fin);
     	    	$('#txtTroba3').val(data.troba);
     	    	$('#txtNombreCuadrilla3').val(data.nombreCuadri);
     	    	$('#txtEECC3').val(data.eecc);
     	    	$('#txtCoorX3').val(data.coordX);    
     	    	$('#txtCoorY3').val(data.coordY);
     	    	$('#txtSerieTroba3').val(data.serie);
     	    	$('#btnRegFicha3').attr('data-type',1);//1=nuevo
     	    	$('#btnRegFicha3').attr('data-item',itemplan);
     	    	
     	    	//listaFileTemp = null;
     	    	//$('#contBodyTable').html('');
     	    	
 	    		$('#modalRegistrarFichaSisegos').modal('toggle');
 			}else if(data.error == 1){
 				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
 			}
 		  });
    }	
	
	function openModalRregistrarFichaFO(component){
		document.getElementById("formRegistrarFichaFO").reset();
		$('.selectForm').val('3').trigger('change');//POR DEFENTE 1 = NUEVO 	
    	var itemplan = $(component).attr('data-itm');    
 	    $.ajax({
 	    	type	:	'POST',
 	    	'url'	:	'getIFTS',
 	    	data	:	{itemplan : itemplan},
 	    	'async'	:	false
 	    })
 	    .done(function(data){
 	    	var data	=	JSON.parse(data);
 	    	if(data.error == 0){
 	    		console.log(data.itemplan+'-'+data.coordX+'-'+data.troba);
     	    	$('#txtItemplan2').val(data.itemplan);
     	    	$('#txtSubProyecto2').val(data.subpro);
     	    	$('#txtNodo2').val(data.nodo);
     	    	$('#txtFechaInicio2').val(data.fec_inicio);
     	    	$('#txtFechaFin2').val(data.fec_fin);
     	    	$('#txtTroba2').val(data.troba);
     	    	$('#txtNombreCuadrilla2').val(data.nombreCuadri);
     	    	$('#txtEECC2').val(data.eecc);
     	    	$('#txtCoorX2').val(data.coordX);    
     	    	$('#txtCoorY2').val(data.coordY);
     	    	$('#txtSerieTroba2').val(data.serie);
     	    	$('#btnRegFicha2').attr('data-type',1);//1=nuevo
     	    	$('#btnRegFicha2').attr('data-item',itemplan);
     	    	
     	    	listaFileTemp = null;
     	    	$('#contBodyTable').html('');
     	    	
 	    		$('#modalRegistrarFichaFOFTTH').modal('toggle');
 			}else if(data.error == 1){
 				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
 			}
 		  });
    }
	
    function openModalRregistrarFicha(component){
    	document.getElementById("formRegistrarFicha").reset();
		$('.selectForm').val('3').trigger('change');//POR DEFENTE 1 = NUEVO 	
    	var itemplan = $(component).attr('data-itm');    
 	    $.ajax({
 	    	type	:	'POST',
 	    	'url'	:	'getIFTS',
 	    	data	:	{itemplan : itemplan},
 	    	'async'	:	false
 	    })
 	    .done(function(data){
 	    	var data	=	JSON.parse(data);
 	    	if(data.error == 0){   
 	    		console.log(data.itemplan+'-'+data.coordX+'-'+data.troba);
     	    	$('#txtItemplan').val(data.itemplan);
     	    	$('#txtSubProyecto').val(data.subpro);
     	    	$('#txtNodo').val(data.nodo);
     	    	$('#txtFechaInicio').val(data.fec_inicio);
     	    	$('#txtFechaFin').val(data.fec_fin);
     	    	$('#txtTroba').val(data.troba);
     	    	$('#txtNombreCuadrilla').val(data.nombreCuadri);
     	    	$('#txtEECC').val(data.eecc);    
     	    	$('#txtCoorX').val(data.coordX);    
     	    	$('#txtCoorY').val(data.coordY);
     	    	$('#txtSerieTroba').val(data.serie);
     	    	$('#btnRegFicha').attr('data-type',1);//1=nuevo
     	    	$('#btnRegFicha').attr('data-item',itemplan);
 	    		$('#modalRegistrarFicha').modal('toggle');
 			}else if(data.error == 1){				
 				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
 			}
 		  });    	
    }

    function openModalEditarFicha(component){
    	document.getElementById("formRegistrarFicha").reset();
    	$('.selectForm').val('3').trigger('change');//POR DEFENTE 1 = NUEVO
    	var itemplan = $(component).attr('data-itm');    
 	    $.ajax({
 	    	type	:	'POST',
 	    	'url'	:	'gfte',
 	    	data	:	{itemplan : itemplan},
 	    	'async'	:	false
 	    })
 	    .done(function(data){
 	    	var data	=	JSON.parse(data);
 	    	if(data.error == 0){   
 	    		//DATA ITEMPLAN
	 	    		$('#txtItemplan').val(data.itemplan);
	     	    	$('#txtSubProyecto').val(data.subpro);
	     	    	$('#txtNodo').val(data.nodo);
	     	    	$('#txtFechaInicio').val(data.fec_inicio);
	     	    	$('#txtFechaFin').val(data.fec_fin);
	     	    	$('#txtTroba').val(data.troba);
	     	    	$('#txtNombreCuadrilla').val(data.nombreCuadri);
	     	    	$('#txtEECC').val(data.eecc);    
	     	    	$('#txtCoorX').val(data.coordX);    
	     	    	$('#txtCoorY').val(data.coordY);
	     	    	$('#txtSerieTroba').val(data.serie);
	     	    	
	     	    // DATA FICHA TECNICA
	     	    	
	     	    	$('#txtNombreJefeCuadrilla').val(data.jefe_c_nombre);	     	    	
	     	    	$('#txtCodigo').val(data.jefe_c_codigo);
	     	    	$('#txtCelular').val(data.jefe_c_celular);
	     	    	$('#inputObservacion').val(data.observacion);
	     	    	$('#inputObservacionAdicional').val(data.observacion_adicional);	     	    	
	     	    	
 	    		var trabajo = JSON.parse(data.dataTrabajo); 	    	
 	    		for(var i=0;i<trabajo.length;i++){
 	    			$('#inputCantidadTrabajo'+trabajo[i].id_ficha_tecnica_trabajo).val(trabajo[i].cantidad);
 	    			$('#selectTrabajo'+trabajo[i].id_ficha_tecnica_trabajo).val(trabajo[i].id_ficha_tecnica_tipo_trabajo).trigger('change');
 	    			$('#inputComentarioTrabajo'+trabajo[i].id_ficha_tecnica_trabajo).val(trabajo[i].observacion); 	    			
 	    		}
 	    		
 	    		var niveles = JSON.parse(data.dataNiveles); 	    	
 	    		for(var i=0;i<niveles.length;i++){
 	    			$('#opt1_'+niveles[i].id_ficha_tecnica_nivel_calibra).val(niveles[i].opt_recep);
 	    			$('#opt2_'+niveles[i].id_ficha_tecnica_nivel_calibra).val(niveles[i].opt_tx);
 	    			$('#ch30_'+niveles[i].id_ficha_tecnica_nivel_calibra).val(niveles[i].ch_30); 	    		
 	    			$('#ch75_'+niveles[i].id_ficha_tecnica_nivel_calibra).val(niveles[i].ch_75); 	   
 	    			$('#ch113_'+niveles[i].id_ficha_tecnica_nivel_calibra).val(niveles[i].ch_113); 	   
 	    			$('#snr_'+niveles[i].id_ficha_tecnica_nivel_calibra).val(niveles[i].snr_ruido); 	 	    	    			
 	    		}
 	    		$('#btnRegFicha').attr('data-type',2);//2=rechazo
 	    		$('#btnRegFicha').attr('data-item',itemplan);
 	    		$('#modalRegistrarFicha').modal('toggle');
 			}else if(data.error == 1){
 				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
 			}
 		  }); 		 
    }
    
    function openModalEditarFichaFO(component){
    	document.getElementById("formRegistrarFichaFO").reset();
    	$('.selectForm').val('3').trigger('change');//POR DEFENTE 1 = NUEVO
    	var itemplan = $(component).attr('data-itm');    
 	    $.ajax({
 	    	type	:	'POST',
 	    	'url'	:	'gfteFO',
 	    	data	:	{itemplan : itemplan},
 	    	'async'	:	false
 	    })
 	    .done(function(data){
 	    	var data	=	JSON.parse(data);
 	    	if(data.error == 0){   
 	    		//DATA ITEMPLAN
	 	    		$('#txtItemplan2').val(data.itemplan);
	     	    	$('#txtSubProyecto2').val(data.subpro);
	     	    	$('#txtNodo2').val(data.nodo);
	     	    	$('#txtFechaInicio2').val(data.fec_inicio);
	     	    	$('#txtFechaFin2').val(data.fec_fin);
	     	    	$('#txtTroba2').val(data.troba);
	     	    	$('#txtNombreCuadrilla2').val(data.nombreCuadri);
	     	    	$('#txtEECC2').val(data.eecc);    
	     	    	$('#txtCoorX2').val(data.coordX);    
	     	    	$('#txtCoorY2').val(data.coordY);
	     	    	$('#txtSerieTroba2').val(data.serie);
	     	    	
	     	    // DATA FICHA TECNICA
	     	    	
	     	    	$('#txtNombreJefeCuadrilla2').val(data.jefe_c_nombre);	     	    	
	     	    	$('#txtCodigo2').val(data.jefe_c_codigo);
	     	    	$('#txtCelular2').val(data.jefe_c_celular);
	     	    	$('#inputObservacion2').val(data.observacion);
	     	    	$('#inputObservacionAdicional2').val(data.observacion_adicional);	     	    	
	     	    	
 	    		var trabajo = JSON.parse(data.dataTrabajo); 	    	
 	    		for(var i=0;i<trabajo.length;i++){
 	    			$('#inputCantidadTrabajo'+trabajo[i].id_ficha_tecnica_trabajo).val(trabajo[i].cantidad);
 	    			$('#selectTrabajo'+trabajo[i].id_ficha_tecnica_trabajo).val(trabajo[i].id_ficha_tecnica_tipo_trabajo).trigger('change');
 	    			$('#inputComentarioTrabajo'+trabajo[i].id_ficha_tecnica_trabajo).val(trabajo[i].observacion); 	    			
 	    		}    		
 	    		console.log('js:'+data.htmlTablas);
 	    		$('#contBodyTable').html(data.htmlTablas);
 	    		var infoFile = JSON.parse(data.jsonDataFIle);
 	    		listaFileTemp = infoFile;
 	    		$('#btnRegFicha2').attr('data-type',2);//2=rechazo
 	    		$('#btnRegFicha2').attr('data-item',itemplan);
 	    		$('#modalRegistrarFichaFOFTTH').modal('toggle');
 			}else if(data.error == 1){
 				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
 			}
 		  }); 		 
    }
    
    $('#formRegistrarFicha')
	.bootstrapValidator({
	    container: '#mensajeForm',
	    feedbackIcons: {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
	    excluded: ':disabled',
	    fields: {
	    	txtNombreJefeCuadrilla: {
	            validators: {
	                notEmpty: {
	                    message: '<p style="color:red">(*) Debe Ingresar Nombre Jefe Cuadrilla.</p>'
	                }
	             }
 	    	   },
  	    	  txtCodigo: {
	            validators: {
	                notEmpty: {
	                    message: '<p style="color:red">(*) Debe Ingresar el Codigo del Jefe Cuadrilla.</p>'
	                }
	             }
 	    	   },
 	    	  txtCelular: {
  	            validators: {
  	                notEmpty: {
  	                    message: '<p style="color:red">(*) Debe Ingresar el Numero de Celular.</p>'
  	                }
  	             }
   	    	   }
	    }
	}).on('success.form.bv', function(e) {
		e.preventDefault();
		
		
	    var $form    = $(e.target),
	        formData = new FormData(),
	        params   = $form.serializeArray(),
	        bv       = $form.data('bootstrapValidator');
			
		    $.each(params, function(i, val) {
		        formData.append(val.name, val.value);
		    });
		    
		    var tipo = $('#btnRegFicha').attr('data-type');
		    formData.append('type', tipo);
			var itemplan = $('#btnRegFicha').attr('data-item');
	    	formData.append('itemplan', itemplan);
	    	var coordenada_x = $('#txtCoorX').val();
	    	console.log('coordenada_x:'+coordenada_x);
	    	formData.append('coorX', coordenada_x);
	    	var coordenada_y = $('#txtCoorY').val();
	    	formData.append('coorY', coordenada_y);
	    	
	    	swal({
	            title: 'Est&aacute; seguro registrar la ficha t&eacute;cnica?',
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
	 		        data: formData,
	 		        url: "saveFT2",
	 		        cache: false,
	 	            contentType: false,
	 	            processData: false,
	 	            type: 'POST'
	 		  	})
	 			  .done(function(data) {  
	      	    	var data	=	JSON.parse(data);
	      	    	if(data.error == 0){   
	      	    		$('#contTabla').html(data.tablaAsigGrafo)
	      	    		initTable('simpletable',10);              	    	
	      	    		$('#modalRegistrarFicha').modal('toggle');             	    	
	      	    		mostrarNotificacion('success','Operaci&oacute;n  &eacute;xitosa.', 'Se registro correcamente!');
	      	    	}else if(data.error == 1){     				
	      				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
	      			}
	  		  });
	        }, function(dismiss) {
	        	// dismiss can be "cancel" | "close" | "outside"
	        		$('#formRegistrarFicha').bootstrapValidator('resetForm', true); 
	        });
		    
	});

    $(document).ready(function() {
  	  $(window).keydown(function(event){
  	    if(event.keyCode == 13) {
  	      event.preventDefault();
  	      return false;
  	    }
  	  });
  	});
    
    function viewFichaEval(component){
			var itemplan = $(component).attr('data-itm');
			
  	    $.ajax({
  	    	type	:	'POST',
  	    	'url'	:	'viewFE',
  	    	data	:	{itemplan : itemplan},
  	    	'async'	:	false
  	    })
  	    .done(function(data){
  	    	var data	=	JSON.parse(data);
  	    	if(data.error == 0){           	    	          	    	   
  	    		$('#contFichaEval').html(data.dataHTML);
  	    	  	$('#modalEvaluarFicha').modal('toggle');
  			}else if(data.error == 1){
  				
  				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
  			}
  		  });
   	}
    
    function viewFichaEvalFO(component){
			var itemplan = $(component).attr('data-itm');
			
  	    $.ajax({
  	    	type	:	'POST',
  	    	'url'	:	'viewFEFO',
  	    	data	:	{itemplan : itemplan},
  	    	'async'	:	false
  	    })
  	    .done(function(data){
  	    	var data	=	JSON.parse(data);
  	    	if(data.error == 0){           	    	          	    	   
  	    		$('#contFichaEval').html(data.dataHTML);
  	    	  	$('#modalEvaluarFicha').modal('toggle');
  			}else if(data.error == 1){
  				
  				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
  			}
  		  });
   	}
   	
   	 function viewFichaEvalSI(component){
		var itemplan = $(component).attr('data-itm');
		
	    $.ajax({
	    	type	:	'POST',
	    	'url'	:	'viewFESI',
	    	data	:	{itemplan : itemplan},
	    	'async'	:	false
	    })
	    .done(function(data){
	    	var data	=	JSON.parse(data);
	    	if(data.error == 0){           	    	          	    	   
	    		$('#contFichaEval').html(data.dataHTML);
	    	  	$('#modalEvaluarFicha').modal('toggle');
			}else if(data.error == 1){
				
				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
			}
		  });
	}
	
	function viewFichaEvalOBP(component) {
		var itemplan = $(component).attr('data-itm');
		$.ajax({
	    	type	:	'GET',
	    	'url'	:	'makePDFOBP',
			data	:	{ itm : itemplan,
			              flg : 1 },
	    	'async'	:	false
	    })
	    .done(function(data){
	    	var data =	JSON.parse(data);    	    	          	    	   
			$('#contFichaEval').html(data.dataHTML);
			$('#modalEvaluarFicha').modal('toggle');
		});
	}

	function validarFicOBP(Component) {
		var accion      = $(Component).attr('data-acc');
		var ficha       = $(Component).attr('data-fic');
		var itemplan    = $(Component).attr('data-item');
		var observacion = $('#observacionOP').val();

		$.ajax({
			type : 'POST',
			url  : 'saveValidacionFichaOBP',
			data : { estado      : accion,
					 ficha       : ficha,
					 itemplan    : itemplan,
					 observacion : observacion }
		}).done(function(data){
			var data = JSON.parse(data);
			if(data.error == 0){           	    	          
				$('#contTabla').html(data.tablaAsigGrafo)
				initDataTable('#data-table'); 	    	   
				$('#modalEvaluarFicha').modal('toggle');
				mostrarNotificacion('success','Operaci&oacute;n Exitosa.', 'Se registro correcamente!');
			}else if(data.error == 1){     				
				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
			}
		});
	}
    
    ///////////////////////////////////////////////////////FICHA FO//////////////////////////////////////////////////////////////
    
    $('#formRegistrarFichaFO')
	.bootstrapValidator({
	    container: '#mensajeForm',
	    feedbackIcons: {
	        valid: 'glyphicon glyphicon-ok',
	        invalid: 'glyphicon glyphicon-remove',
	        validating: 'glyphicon glyphicon-refresh'
	    },
	    excluded: ':disabled',
	    fields: {
	    	txtNombreJefeCuadrilla2: {
	            validators: {
	                notEmpty: {
	                    message: '<p style="color:red">(*) Debe Ingresar Nombre Jefe Cuadrilla.</p>'
	                }
	             }
 	    	   },
  	    	  txtCodigo2: {
	            validators: {
	                notEmpty: {
	                    message: '<p style="color:red">(*) Debe Ingresar el Codigo del Jefe Cuadrilla.</p>'
	                }
	             }
 	    	   },
 	    	  txtCelular2: {
  	            validators: {
  	                notEmpty: {
  	                    message: '<p style="color:red">(*) Debe Ingresar el Numero de Celular.</p>'
  	                }
  	             }
   	    	   }
	    }
	}).on('success.form.bv', function(e) {
		e.preventDefault();
		
		
	    var $form    = $(e.target),
	        formData = new FormData(),
	        params   = $form.serializeArray(),
	        bv       = $form.data('bootstrapValidator');
			
		    $.each(params, function(i, val) {
		        formData.append(val.name, val.value);
		    });
		    
		    var tipo = $('#btnRegFicha2').attr('data-type');
		    formData.append('type', tipo);
			var itemplan = $('#btnRegFicha2').attr('data-item');
	    	formData.append('itemplan', itemplan);
	    	var coordenada_x = $('#txtCoorX2').val();
	    	console.log('coordenada_x:'+coordenada_x);
	    	formData.append('coorX', coordenada_x);
	    	var coordenada_y = $('#txtCoorY2').val();
	    	formData.append('coorY', coordenada_y);
	    	var jsonDataFile = listaFileTemp;
	    	formData.append('jsonDataFile', JSON.stringify(jsonDataFile));
		    
		    
		    
		    swal({
	            title: 'Est&aacute; seguro registrar la ficha t&eacute;cnica?',
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
			        data: formData,
			        url: "saveFTFO",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				  .done(function(data) {  
	     	    	var data	=	JSON.parse(data);
	     	    	if(data.error == 0){   
	     	    		$('#contTabla').html(data.tablaAsigGrafo)
	     	    		initTable('simpletable',10);              	    	
	     	    		$('#modalRegistrarFichaFOFTTH').modal('toggle');             	    	
	     	    		mostrarNotificacion('success','Operaci&oacute;n  &eacute;xitosa.', 'Se registro correcamente!');
	     	    	}else if(data.error == 1){     				
	     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
	     			}
	 		  });
	        	
	        }, function(dismiss) {
	        	// dismiss can be "cancel" | "close" | "outside"
	        		$('#formRegistrarFichaFO').bootstrapValidator('resetForm', true); 
	        });
		    
		    
	});  
    
    var listaFileTemp = null;
    
    
        $('#preLoadFile').click(function(e){
        	
        	var comprobar = $('#fileTable').val().length;
    		
    		if(comprobar>0){

    			    var file = $('#fileTable').val()			
    			    console.log($('#fileTable').val().length);
    			    var ext = file.substring(file.lastIndexOf("."));
    			    console.log(ext);
    			    if(ext != ".txt")
    			    {
    			    	alert('Formato de archivo no v\u00E1lido. El formato correcto es .TXT Separado por Tabulaciones');
    			        return false;
    			    }
    			    else{
    			    	var input = document.getElementById('fileTable');
    		            var file = input.files[0];
    		            var form = new FormData();
    		            form.append('file', file);
    		            $.ajax({
    		                url : "upfre",
    		                type: "POST",
    		                cache: false,
    		                contentType: false,
    		                processData: false,
    		                data : form,
    		                success: function(response){
    		                    var data = JSON.parse(response);
    		                    if(data.error == 0){  
    		         	    		$('#contBodyTable').html(data.tablaData);      	
    		         	    		var infoFile = JSON.parse(data.jsonDataFIle);
    		         	    		listaFileTemp = infoFile;    		         	    		
    		         	    	}else if(data.error == 1){     				
    		         	    		alert(data.msj);
    		         			}
    		                    
    		                }
    		            });
    			    	
    			    }        	
    		}
        	
        });
        
        $('#formRegistrarFichaFoSisego')
    	.bootstrapValidator({
    	    container: '#mensajeForm3',
    	    feedbackIcons: {
    	        valid: 'glyphicon glyphicon-ok',
    	        invalid: 'glyphicon glyphicon-remove',
    	        validating: 'glyphicon glyphicon-refresh'
    	    },
    	    excluded: ':disabled',
    	    fields: {
    	    	txtNombreJefeCuadrilla3: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar Nombre Jefe Cuadrilla.</p>'
    	                }
    	             }
     	    	   },
      	    	  txtCodigo3: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar el Codigo del Jefe Cuadrilla.</p>'
    	                }
    	             }
     	    	   },
     	    	  txtCelular3: {
      	            validators: {
      	                notEmpty: {
      	                    message: '<p style="color:red">(*) Debe Ingresar el Numero de Celular.</p>'
      	                }
      	             }
       	    	   }
    	    }
    	}).on('success.form.bv', function(e) {
    		e.preventDefault();
    		
    		
    	    var $form    = $(e.target),
    	        formData = new FormData(),
    	        params   = $form.serializeArray(),
    	        bv       = $form.data('bootstrapValidator');
    			
    		    $.each(params, function(i, val) {
    		        formData.append(val.name, val.value);
    		    });
    		    
    		    var tipo = $('#btnRegFicha3').attr('data-type');
    		    formData.append('type', tipo);
    			var itemplan = $('#btnRegFicha3').attr('data-item');
    	    	formData.append('itemplan', itemplan);
    	    	var coordenada_x = $('#txtCoorX3').val();
    	    	console.log('coordenada_x:'+coordenada_x);
    	    	formData.append('coorX', coordenada_x);
    	    	var coordenada_y = $('#txtCoorY3').val();
    	    	formData.append('coorY', coordenada_y);
    		    $.ajax({
    		        data: formData,
    		        url: "saveFT3",
    		        cache: false,
    	            contentType: false,
    	            processData: false,
    	            type: 'POST'
    		  	})
    			  .done(function(data) {  
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){   
         	    		$('#contTabla').html(data.tablaAsigGrafo)
         	    		initTable('simpletable',10);              	    	
         	    		$('#modalRegistrarFichaSisegos').modal('toggle');           	    	
         	    		mostrarNotificacion('success','Operaci&oacute;n  &eacute;xitosa.', 'Se registro correcamente!');
         	    	}else if(data.error == 1){     				
         				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
         			}
     		  });
    	});
    
        function removeTR(component){
        	var indice = $(component).attr('data-indice');
        	console.log('info1:'+listaFileTemp);
        	$('#tr'+indice).remove();
        	listaFileTemp.splice(indice, 1);
        	console.log('info2:'+listaFileTemp);
        }
        
        console.log('init');
        
    	$('#forEditarKitMate')
    	.bootstrapValidator({
    	container: '#mensajeForm',
    	feedbackIcons: {
    	    valid: 'glyphicon glyphicon-ok',
    	    invalid: 'glyphicon glyphicon-remove',
    	    validating: 'glyphicon glyphicon-refresh'
    	},
    	excluded: ':disabled',
    	fields: {  
    	}
    	}).on('success.form.bv', function(e) {
    	e.preventDefault();

    	console.log('forEditarKitMate');
    	var $form    = $(e.target),
    	    formData = new FormData(),
    	    params   = $form.serializeArray(),
    	    bv       = $form.data('bootstrapValidator');
    	    
    	    $.each(params, function(i, val) {
    	        formData.append(val.name, val.value);
    	    });
    	    var idSubPro	=	$('#btnEditKitMat').attr('data-idSubPro');
    	    formData.append('idSubPro', idSubPro);
    	    
    	    var itemplan	=	$('#btnEditKitMat').attr('data-itemplan');
    	    formData.append('itemplan', itemplan);
    	    
    	    var accion		=	$('#btnEditKitMat').attr('data-accion');
    	    formData.append('accion', accion);
    	    
    	    var idFicha		=	$('#btnEditKitMat').attr('data-id_ficha');
    	    formData.append('idFicha', idFicha);
    	    swal({
    	        title: 'Esta seguro registrar los Materiales?',
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
    	            data: formData,
    	            url: "saveKitMate",
    	            cache: false,
    	            contentType: false,
    	            processData: false,
    	            type: 'POST'
    	          })
    	          .done(function(data) {
    	             var data	=	JSON.parse(data);
    	             if(data.error == 0){  
    	            	 console.log('actuaizar ficha tecnica.');
    	            	 $.ajax({
    	     	           type	:	'POST',
    	     	 	    	'url'	:	'reactFT',
    	     	 	    	data	:	{	itemplan	:	itemplan,
    	     	 	    					idFicha 	:	idFicha},
    	     	 	    	'async'	:	false
    	     	          })
    	     	          .done(function(data) {
    	     	             var data	=	JSON.parse(data);
    	     	             if(data.error == 0){  
    	     	            	 console.log('ficha reactivada!.');
    	     	                 location.reload();
    	     	             }else if(data.error == 1){     				
    	     	                 mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
    	     	             }
    	     	          });
    	             }else if(data.error == 1){     				
    	                 mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
    	             }
    	          });
    	    }, function(dismiss) {
    	        // dismiss can be "cancel" | "close" | "outside"
    	            $('#forEditarKitMate').bootstrapValidator('resetForm', true); 
    	    });
    	});

