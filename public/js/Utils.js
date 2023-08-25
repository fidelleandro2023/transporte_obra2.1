function initDataTable(id_tabla) {
    $(id_tabla).DataTable({
        autoWidth: false,
        responsive: false,
        aaSorting: [],
        lengthMenu: [[15, 30, 45, -1], ["15 Rows", "30 Rows", "45 Rows", "Everything"]],
        language: {searchPlaceholder: "Search for records..."},
        dom: "Blfrtip",
        buttons: [{extend: "excelHtml5", title: "Export Data"},
            {extend: "csvHtml5", title: "Export Data"},
            {extend: "print", title: "Print"}],
        initComplete: function (a, b) {
            $(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')
        }
    });
}

function initDataTableSinfix(id_tabla) {
    $(id_tabla).DataTable({
        dom: 'Bfrtip',
        buttons: [{extend: 'excelHtml5'}],
        pageLength: 3,
        lengthMenu: [[30, 60, 100, -1], [30, 60, 100, "Todos"]],
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ning\u00fan dato disponible en esta tabla",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sInfoPostFix: "",
            sSearch: "Buscar:",
            sUrl: "",
            sInfoThousands: ",",
            sLoadingRecords: "Cargando...",
            oPaginate: {sFirst: "Primero",
                sLast: "\u00daltimo",
                sNext: "Siguiente",
                sPrevious: "Anterior"},
            oAria: {
                sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                sSortDescending: ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
}

function mostrarNotificacion(tipo, titulo, mensaje) {
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
	if(tipo == 'error' || tipo == 'Error') {
		tipo = 'warning';
	}
	 
    swal({
        title: titulo,
        text: mensaje,
        type: tipo
    });
}

function modal(idModal) {
    $('#' + idModal).modal('toggle');
}

function soloDigitos(clase) {
    //console.log('.......soloDigitos.......:'+"."+clase);
    $("." + clase).keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                                (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

}

/**comentado porque generaba problemas por el nombre de la funcion se coloco la funcion en las vistas de planta interna : V_bandeja_certificacion y V_bandeja_reporte. 10.07.2019 czavalacas
 function filtrarTabla(funcionControlador) {
 var fechaIn  = $('#fechaIn').val();
 var fechaFin = $('#fechaFin').val();		
 var idEecc   = $('#cmbEec option:selected').val();
 
 $.ajax({
 type : 'POST',
 url  : funcionControlador,
 data :  {
 'fechaIn'  : fechaIn,
 'fechaFin' : fechaFin,
 'idEecc'   : idEecc
 }
 }).done(function(data){
 data = JSON.parse(data);
 $('#contTablaCertificacion').html(data.tablaBandejaCertificacion);	
 initDataTable('#data-table');   
 });
 }
 **/

function seguridad_clave(clave) {
    var seguridad = 0;
    if (clave.length != 0) {
        if (tiene_numeros(clave) && tiene_letras(clave)) {
            seguridad += 30;
        }
        if (tiene_minusculas(clave) && tiene_mayusculas(clave)) {
            seguridad += 30;
        }
        if (clave.length >= 4 && clave.length <= 5) {
            seguridad += 10;
        } else {
            if (clave.length >= 6 && clave.length <= 8) {
                seguridad += 30;
            } else {
                if (clave.length > 8) {
                    seguridad += 50;
                }
            }
        }
    }
    return seguridad
}

function tiene_numeros(texto) {
    var numeros = "0123456789";

    for (i = 0; i < texto.length; i++) {
        if (numeros.indexOf(texto.charAt(i), 0) != -1) {
            return 1;
        }
    }
    return 0;
}

function tiene_letras(texto) {
    texto = texto.toLowerCase();
    var letras = "abcdefghyjklmnñopqrstuvwxyz";

    for (i = 0; i < texto.length; i++) {
        if (letras.indexOf(texto.charAt(i), 0) != -1) {
            return 1;
        }
    }
    return 0;
}

function tiene_minusculas(texto) {
    var letras = "abcdefghyjklmnñopqrstuvwxyz";

    for (i = 0; i < texto.length; i++) {
        if (letras.indexOf(texto.charAt(i), 0) != -1) {
            return 1;
        }
    }
    return 0;
}

function tiene_mayusculas(texto) {
    var letras_mayusculas = "ABCDEFGHYJKLMNÑOPQRSTUVWXYZ";
    for (i = 0; i < texto.length; i++) {
        if (letras_mayusculas.indexOf(texto.charAt(i), 0) != -1) {
            return 1;
        }
    }
    return 0;
}

function soloDecimal(idComponente) {

    $("input[id*='" + idComponente + "']").keydown(function (event) {


        if (event.shiftKey == true) {
            event.preventDefault();
        }

        if ((event.keyCode >= 48 && event.keyCode <= 57) ||
                (event.keyCode >= 96 && event.keyCode <= 105) ||
                event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
                event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

        } else {
            event.preventDefault();
        }

        if ($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
            event.preventDefault();
        //if a decimal has been added, disable the "."-button

    });
}

function openModalNoticias(btn) {
    // modal('modalPDF');
    var url_noticia = btn.data('direccion');
    console.log(url_noticia);
    var htmlDataNoticia = "<div class='pdf'><object id='objPDF' data='" + url_noticia + "'></object></div>";
    $('#doc_declaracion').html(htmlDataNoticia);
}

function canCreateEditPOByCostoUnitario(jsonCreateSol, callback){
		let dataFile = new FormData();
		 $.ajax({
	            type: 'POST',
	            url: 'regPoByCU',
	            data: {
	            	origen		: jsonCreateSol.origen,//1= CREACION PO MAT, 2 = CREACION PO MO, 3 = GESTION VR MAT, 4 = LIQUIDACION MO
					tipo_po 	: jsonCreateSol.tipo_po_dato,//1 = MATERIAL; 2 = MO
	            	accion  	: jsonCreateSol.accion_dato,//1 = NUEVA PO; 2 = EDITAR PO
	            	codigo_po 	: jsonCreateSol.codigo_po_dato,//NUEVA PO = NULL, EDITAR PO = 'CODIGO_PO'
	                itemplan	: jsonCreateSol.itemplan_dato,//ITEMPLAN
	                costoTotalPo: jsonCreateSol.costoTotalPo_dato //COSTO TOTAL DE LA PO
	            }
	        }).done(function (data) {
	        	data = JSON.parse(data);
	        	if (data.error == 0) {
	        		callback();
	        	}else if(data.error == 1){
	        		if(data.canGenSoli == 0){
	        			var costo_actual = data.costo_actual;
	        			var excedente 	 = data.excedente;
	        			var costo_final  = data.costo_final;

	        			swal({
	            	        title: 'No se pudo procesar la Solicitud',
	            	        // text: data.msj,
							html : '<div class="form-group"><a>'+data.msj+'</a></div>'+
									'<div class="form-group">'+
										'<label style="color:red">SUBIR EVIDENCIA EXCESO</label>'+
										'<input type="file" name="archivo" id="archivoFile">'+
									'</div>'+
									'<div class="form-group">'+
										'<textarea class="col-md-12 form-control" placeholder="Ingresar Comentario..." style="height:80px;background:#F9F8CF" id="comentarioText"></textarea>'+
									'</div>',
	            	        type: 'warning',
	            	        showCancelButton: true,
	            	        buttonsStyling: false,
	            	        confirmButtonClass: 'btn btn-primary',
	            	        confirmButtonText: 'Si, generar Solicitud!',
	            	        cancelButtonClass: 'btn btn-secondary',
	            	        allowOutsideClick: false
	            	    }).then(function(){//falta codigo que genera la solicitud...
							var comentario = $('#comentarioText').val();

							var fileArchivo = $('#archivoFile')[0].files[0];

							dataFile.append('origen', jsonCreateSol.origen);
							dataFile.append('itemplan', jsonCreateSol.itemplan_dato);
							dataFile.append('tipo_po', jsonCreateSol.tipo_po_dato);
							dataFile.append('costo_inicial', costo_actual);
							dataFile.append('exceso_solicitado', excedente);
							dataFile.append('costo_final', costo_final);
							dataFile.append('codigo_po', jsonCreateSol.codigo_po_dato);
							dataFile.append('comentario', comentario);
							dataFile.append('idEstacion', jsonCreateSol.idEstacion);
							dataFile.append('file', fileArchivo);
							dataFile.append('data_json', JSON.stringify(jsonCreateSol.data_json));

							// for(let [name, value] of dataFile) {
							  // alert(`${name} = ${value}`); // key1=value1, then key2=value2
							// }
							           // data: {
	            	            	// origen		    : jsonCreateSol.origen,//1= CREACION PO MAT, 2 = CREACION PO MO, 3 = GESTION VR MAT, 4 = LIQUIDACION MO
									// itemplan 		: jsonCreateSol.itemplan_dato,//ITEMPLAN
	            	            	// tipo_po  		: jsonCreateSol.tipo_po_dato,//1 = MAT, 2 = MO
	            	            	// costo_inicial 	: costo_actual,//
	            	            	// exceso_solicitado : excedente,//
	            	            	// costo_final		: costo_final,//
									// codigo_po 		: jsonCreateSol.codigo_po_dato,
									// comentario      : comentario,
									// idEstacion      : jsonCreateSol.idEstacion,
									// fileArchivo     : fileArchivo,
									// data_json       : JSON.stringify(jsonCreateSol.data_json)	// DETALLE 
	            	            // }

	            	        $.ajax({
								data: dataFile,
	            	            type: 'POST',
	            	            url: 'genSolExce',
								cache: false,
								contentType: false,
								processData: false
	            	        }).done(function (data) {
	            	        	data = JSON.parse(data);
	            	        	if (data.error == 0) {
	            	        		swal({
	                                    title: 'Se realizo la Operacion!',
	                                    text: 'Asegurese de validar la informacion!',
	                                    type: 'success',
	                                    buttonsStyling: false,
	                                    confirmButtonClass: 'btn btn-primary',
	                                    confirmButtonText: 'OK!',
	                                    allowOutsideClick: false
	                                }).then(function(){
	                                	window.close(); 
	                                });	                                
	            	        	}else if(data.error == 1){
	            	        		mostrarNotificacion('error', data.msj);
	            	        	}
	            	        	 
	            	        });
	            	    }, function(dismiss) {
	            	       console.log('cancelar.');
	            	    });
	        		}else{
	        			swal("Mensaje Informativo", data.msj , "warning");
	        		}        		
	        	}
	        });
	}
	
	function formatearNumeroComas(numeroFormat){
    	var format_monto_final =  Number(numeroFormat).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return format_monto_final;
    }
	
	/*********nuevo CZAVALA	29.04.2020*******/
	function showMessageIsObraCerrada(id_estado_plan){
		var estadoplan = '';
		if(id_estado_plan	==	5){
			estadoplan = 'CERRADO';
		}else if(id_estado_plan	==	6){
			estadoplan = 'CANCELADO';
		}
		swal({
			title: 'ACCION BLOQUEADA!',
			text: 'La obra se encuentra en estado "'+estadoplan+'", las gestiones han sido finalizadas.',
			type: 'warning',
			buttonsStyling: false,
			confirmButtonClass: 'btn btn-primary',
			confirmButtonText: 'OK!',
			allowOutsideClick: false
		}).then(function(){
			window.close(); 
		}); 	
	}
	
	 function deleteOtActualizacion(component){
     	var itemplan = $(component).attr('data-itemplan');
     	swal({
             title: 'Est&aacute; seguro de eliminar la OT de Actualizacion?',
             text: 'Asegurese de validar la Informacion.',
             type: 'warning',        		
             showCancelButton: true,
             buttonsStyling: false,
             confirmButtonClass: 'btn btn-primary',
             confirmButtonText: 'Si, eliminar OT de Actualizacion!',
             cancelButtonClass: 'btn btn-secondary',
             allowOutsideClick: false
         }).then(function(){
         	$.ajax({
                 type: 'POST',
                 url: 'delOTAC',
                 data: {
                 	itemplan: itemplan
                 }
             }).done(function (data) {
                 data = JSON.parse(data);
                 if(data.error == 0){
                 	swal({
							title: 'Se Elimino la OT ' + itemplan + 'AC',
							text: 'Asegurese de validar la informacion!',
							type: 'success',
							buttonsStyling: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: 'OK!'
						}).then(function () {
							filtrarTabla();
						});                        
                 }else if(data.error == 1){
                 	mostrarNotificacion('warning','No se pudo Elimnar la OT AC',data.msj);
                 }
             });
         })
     }
	 
	 function mostrarNotificacionHTML(tipo, titulo, mensaje) {
			 
		    swal({
		        title: titulo,
		        html: mensaje,
		        type: tipo
		    });
		}
		
	function soloLetras(e) {
		var key = e.keyCode || e.which,
		tecla = String.fromCharCode(key).toLowerCase(),
		letras = " áéíóúabcdefghijklmnñopqrstuvwxyz,;1234567890",
		especiales = [8, 37, 39, 46],
		tecla_especial = false;

		for (var i in especiales) {
			if (key == especiales[i]) {
				tecla_especial = true;
				break;
			}
		}

		if (letras.indexOf(tecla) == -1 && !tecla_especial) {
			return false;
		}
	}
	