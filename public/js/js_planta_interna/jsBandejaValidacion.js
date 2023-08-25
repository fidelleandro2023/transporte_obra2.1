var itemplanGlobal = null;
var tipoPlanta     = null;
function validarObra(btn) {
    itemplanGlobal = btn.data('itemplan');
    tipoPlanta     = btn.data('tipo_planta');
    var hasEvidencia  = btn.data('has_evidencia');
	
	modal('modalAlertaValidacion');
	
    // if(hasEvidencia === null || hasEvidencia === undefined || hasEvidencia === ''){
        // swal({
            // title: 'Esta seguro de realizar la siguiente accion?',
            // text: 'Para validar la obra debe aceptar y cargar las evidencias en el modulo de consulta!',
            // type: 'warning',
            // buttonsStyling: false,
            // confirmButtonClass: 'btn btn-primary',
            // confirmButtonText: 'OK!'//,
            // //allowOutsideClick: false
        // }).then(function(){
            // $.ajax({
                // type : 'POST',
                // url  : 'habilitarCargaEvi',
                // data : { itemplan : itemplanGlobal}
            // }).done(function(data){
                // // $('#contTabla').html(data.tablaValid);
                // $('#contTabla').html('');
                // initDataTable('#data-table');
            // });
        // }, function(dismiss) {

        // });

    // }else if(hasEvidencia == '0'){
        // mostrarNotificacion('warning', 'Debe cargar las evidencias en el modulo de consultas!!', 'Aviso');
    // }else{
        
    // }
}

function validarTerminarObra() {
    $.ajax({
        type : 'POST',
        url  : 'ejecValidacion',
        data : { itemplan   : itemplanGlobal,
                 tipoPlanta : tipoPlanta }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            modal('modalAlertaValidacion');
			swal({
				   title: 'Se valido correctamente!',
				   text: 'Asegurese de validar la informacion!',
				   type: 'success',
				   buttonsStyling: false,
				   confirmButtonClass: 'btn btn-primary',
				   confirmButtonText: 'OK!',
				   allowOutsideClick: false
			   }).then(function(){
				   location.reload();
			   }, function(dismiss) {
				   location.reload();
			   });
            // location.reload();
        } else {
            mostrarNotificacion('warning', data.msj, 'revisar');
        }
       
    });
}

function filtrarTablaValid() {
    var itemplan      = $('#filtrarItem').val();
    var idSubProyecto = $('#selectSubProy option:selected').val();
    console.log(idSubProyecto);
    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaValid',
        data :  {
                    itemplan      : itemplan, 
                    idSubProyecto : idSubProyecto
                }
    }).done(function(data){
        data = JSON.parse(data);
        $('#contTabla').html(data.tablaValid);
        initDataTable('#data-table');
    });
}
/////////////////////////////////////////////////////////////////////////// 
var costoMA = null;
var costoMO = null;
var total   = null;
var cantidadFinal = null;
var id_ptrxactividad_zonal = null;
var arrayData = [];
var arrayDataInsert = [];
var tablaActividad = null;
var idSubProyectoGlobalPtr = null;
var idEstadoPlanGlobalEditPtr = null; 
var costoMoTotalFinalGlb = null;
function openModalPTR(btn) {
    var itemplan           = btn.data('itemplan');
    idSubProyectoGlobalPtr = btn.data('id_subproyecto');
    idEstadoPlanGlobalEditPtr = btn.data('id_estado_plan');
    
    $.ajax({
        type : 'POST',
        url  : 'getPtrByItemplanForBanValid',
        data : { itemplan      : itemplan,
                 idEstadoPlan  : idEstadoPlanGlobalEditPtr },
    }).done(function(data){
        costoMoTotalFinalGlb = 0;
        var data = JSON.parse(data);
        modal('modalConsultaPTR');
        $('#contTablaPTR').html(data.tablaConsultaPtr);
    });
}

var arrayExcesoGlb = [];
var itemplanPTRGlobal = null;
var ptrGlobal = null;
var costoMoTotalInicialGlb = null;
function openModalEditarPTR(btn) {
    $('#btnActualizarPtr').prop('disabled', false);
    ptrGlobal         = btn.data('ptr');
    itemplanPTRGlobal = btn.data('itemplan');
    costoMoTotalInicialGlb = btn.data('costo_mo');
    
    console.log(costoMoTotalInicialGlb);

    $.ajax({
        type : 'POST',
        url  : 'getDetallePoEdit',
        data : { itemplan      : itemplanPTRGlobal,
                 ptr           : ptrGlobal,
                 idSubProyecto : idSubProyectoGlobalPtr },
    }).done(function(data){
        var data = JSON.parse(data);
        $('#contEditarPTR').html(data.tablaEditarPtr);
        // $('#contTablaActividad').html(data.tablaActividad);
        arrayExcesoGlb  = [];
        arrayData       = [];
        arrayDataInsert = [];
        inicializarTabla('tablaActividad');
          if(idEstadoPlanGlobalEditPtr == 4) {
            // $('#contTablaActividad').css('display', 'none');
            $('#tituloActividades').css('display', 'none');
            $('#btnActualizarPtr').css('display', 'none');            
        } else {
            $('#tituloActividades').css('display', 'block');
            // $('#contTablaActividad').css('display', 'block');
            $('#btnActualizarPtr').css('display', 'block');     
        }
        // inicializarTabla('tablaPtr');
        modal('modalEditarPTR');
    });
}

var costo_total = 0;
function calcularCostoFinal() {
    var count = $('#tablaPtr tr').length; 
    costoMoTotalFinalGlb = 0;
	costo_total = 0;
    for(var i=1; i<count; i++) {
        var cantidadFin  = Number($('#cantidad_'+i).val());
        var precio       = Number($('#precio_'+i).html());
        var baremo       = Number($('#baremo_'+i).html());
		var total        = Number($('#costoTotal_'+i).html());
        costoMO = cantidadFin * precio * baremo;
        costoMO = costoMO.toFixed(2);
		costo_total = (Number(costo_total)+Number(total)).toFixed(2);
		console.log(costo_total);
        costoMoTotalFinalGlb = (Number(costoMO)+Number(costoMoTotalFinalGlb)).toFixed(2);
		//console.log(costoMoTotalFinalGlb);
    }
}

function calculoCantidad(btn) {
    var idActividad        = btn.data('id_actividad');
    id_ptrxactividad_zonal = btn.data('id_ptrxactividad_zonal');
    var cont               = btn.data('cont');
    var descripcionAct     = btn.data('descripcion');
    var primerCantidaFinal = btn.data('cantidad_final');//ES LA CANTIDAD FINAL QUE SE MUESTRA AL ABRIR EL MODAL

    var json = {};
    var jsonExceso = {};

    
    cantidadFinal   = $('#cantidad_'+cont).val();
    costoMA         = $('#costoMA_'+cont).html();
    costoMO         = $('#costoMO_'+cont).html();
    var precio      = $('#precio_'+cont).html();
    var baremo      = $('#baremo_'+cont).html();
    var precioKit   = $('#precioKit_'+cont).html();
    total           = $('#costoTotal_'+cont).html();
    cantidadInicial = $('#cantidad_in_'+cont).val();
    
	precioKit = precioKit || '';
    precioKit = precioKit.replace(/\,/g,'');
    precioKit = Number(precioKit);
    
    cantidadFinal  = Number(cantidadFinal);
    console.log("CANTIDAD FINAL: "+cantidadFinal);
    costoMA = cantidadFinal * precioKit;
    costoMO = cantidadFinal * precio * baremo;

    // var flgExceso = getFlgExceso(primerCantidaFinal, cantidadFinal, precio, baremo);
    
    if(isNaN(costoMA)) {
        costoMA = 0;
    }

    if(isNaN(costoMO)) {
        costoMO = 0;
    }

    costoMO = costoMO.toFixed(2);
    costoMA = costoMA.toFixed(2);

    $('#costoMO_'+cont).html(costoMO);
    $('#costoMA_'+cont).html(costoMA);

    costoMO  = parseFloat(costoMO);
    costoMA  = parseFloat(costoMA);
    total = costoMO + costoMA; 

    $('#costoTotal_'+cont).html(total);
    console.log(json);
    json.costo_mat        = costoMA;
    json.costo_mo         = costoMO;
    json.total            = total;
    json.id_actividad     = idActividad;
    json.cantidad_final   = cantidadFinal;
    json.ptr              = ptrGlobal;
    json.itemplan         = itemplanPTRGlobal;
    json.precio           = precio;
    json.baremo           = baremo;
    json.descripcion      = descripcionAct;

    console.log(jsonExceso);

    jsonExceso.costo_mat        = costoMA;
    jsonExceso.costo_mo         = costoMO;
    jsonExceso.total            = total;
    jsonExceso.id_actividad     = idActividad;
    jsonExceso.cantidad_final   = cantidadFinal;
    jsonExceso.ptr              = ptrGlobal;
    jsonExceso.itemplan         = itemplanPTRGlobal;
    jsonExceso.precio           = precio;
    jsonExceso.baremo           = baremo;
    jsonExceso.descripcion      = descripcionAct;
    jsonExceso.costo_kit        = precioKit;
    console.log(jsonExceso);
    
    if(screenInit == 1){
    	json.cantidad_eecc_tmp = cantidadFinal;
    }else if(screenInit == 2){
    	json.cantidad_tdp_tmp = cantidadFinal;
    } 
    
    if(id_ptrxactividad_zonal == 0 || id_ptrxactividad_zonal == '' || id_ptrxactividad_zonal == null) {
        console.log("insert as");
        json.id_ptr_x_actividades_x_zonal = '';
        json.cantidad = 0;
        var contador = 0;
        arrayDataInsert.forEach(function(data, key){
            if(data.id_actividad == idActividad) {//SI ENCUENTRO EL MISMO REEMPLAZO
                contador = 1;
                arrayDataInsert.splice(key, 1, json);
            }
        });

        if(contador == 0) {
            arrayDataInsert.splice(arrayDataInsert.length, 0, json);
        }
        console.log(jsonExceso);
        var contadorExceso = 0;
        arrayExcesoGlb.forEach(function(data, key){
            if(data.id_actividad == idActividad) {
                jsonExceso.id_ptr_x_actividades_x_zonal = '';
                contadorExceso = 1;
                jsonExceso.cantidadInicial = 0;
                arrayExcesoGlb.splice(key, 1, jsonExceso);
            }
        });
        console.log(jsonExceso);
        if(contadorExceso == 0) {
            jsonExceso.id_ptr_x_actividades_x_zonal = '';
            jsonExceso.cantidadInicial = 0;
            console.log(jsonExceso);
            arrayExcesoGlb.splice(arrayExcesoGlb.length, 0, jsonExceso);
            console.log(arrayExcesoGlb);
        }

        // arrayExcesoGlb.splice(arrayData.length, 0, jsonExceso);
        // arrayDataInsert.splice(arrayData.length, 0, json);
    } else {console.log("ENTRO EDIT");
        json.id_ptr_x_actividades_x_zonal = id_ptrxactividad_zonal;
   
            var contador1 = 0;
            arrayData.forEach(function(data, key){
                if(data.id_ptr_x_actividades_x_zonal == id_ptrxactividad_zonal) {
                    contador1 = 1;
                    arrayData.splice(key, 1, json);
                }
            });

            if(contador1 == 0) {
                arrayData.splice(arrayData.length, 0, json);
            }
            
            var contadorExceso2 = 0;
            arrayExcesoGlb.forEach(function(data, key){
                if(data.id_ptr_x_actividades_x_zonal == id_ptrxactividad_zonal) {
                    contadorExceso2 = 1;
                   
                    jsonExceso.cantidadInicial = cantidadInicial;
                    jsonExceso.id_ptr_x_actividades_x_zonal = id_ptrxactividad_zonal;
                    arrayExcesoGlb.splice(key, 1, jsonExceso);
                }
            });
            
            if(contadorExceso2 == 0) {
                jsonExceso.cantidadInicial = cantidadInicial;
                jsonExceso.id_ptr_x_actividades_x_zonal = id_ptrxactividad_zonal;
                arrayExcesoGlb.splice(arrayExcesoGlb.length, 0, jsonExceso);
            }
       

        // arrayData.splice(arrayData.length, 0, json);
        // arrayExcesoGlb.splice(arrayData.length, 0, jsonExceso);
    }
	calcularCostoFinal();
	console.log(arrayExcesoGlb);
	console.log('arrayDataInsert:',arrayDataInsert);
}

function actualizarPtr() {
    console.log("COSTO TOTAL INICIAL: "+costoMoTotalInicialGlb);
    console.log("COSTO TOTAL FINAL: "+costoMoTotalFinalGlb);
	if(costo_total == 0) {
		mostrarNotificacion('warning', 'Verificar', 'El costo total no puede ser 0, verificar.');
		return;
	}
 	
    if(costoMoTotalInicialGlb > costo_total) {
        $.ajax({
            type : 'POST',
            url  : 'actualizarPTR',
            data : { costoMA       : costoMA,
                     costoMO       : costoMO,
                     total         : total,
                     itemplan      : itemplanPTRGlobal,
                     ptr           : ptrGlobal,
                     cantidadFinal : cantidadFinal,
                     arrayData     : JSON.stringify(arrayData),
                     arrayDataInsert : arrayDataInsert,
                     idEstadoPlan  : idEstadoPlanGlobalEditPtr}
        }).done(function(data){
            data = JSON.parse(data);
            if(data.error == 0) {
                $('#btnActualizarPtr').prop('disabled', true);
                $('#contTablaPTR').html(data.tablaConsultaPtr);
                arrayData = [];
                arrayDataInsert = [];
                mostrarNotificacion('success', 'Actualizac&oacute;n realizada con &eacute;xito', 'correcto');
                modal('modalEditarPTR');
            } else {
                mostrarNotificacion('error', data.msj, 'error al ingresar data');
				return;
            }
        });
    } else {
        mostrarNotificacion('warning', 'No se permite adicionar (aumentar el costo de la PO).', 'verificar');
        return;
        // jsonCreateSol = {   origen       		: 6,
        //                     tipo_po_dato 		: 2, 
        //                     accion_dato  		: 2, 
        //                     codigo_po_dato      : ptrGlobal, 
        //                     itemplan_dato  	    : itemplanPTRGlobal, 
        //                     costoTotalPo_dato   : costoMO, 
        //                     data_json           : arrayExcesoGlb,
        //                     idEstacion          : 11 };
       
        //     var costo_actual = costoMoTotalInicialGlb;
        //     var excedente 	 = costoMoTotalFinalGlb - costoMoTotalInicialGlb;
        //     var costo_final  = costoMoTotalFinalGlb;
        //     swal({
        //         // title: 'No se pudo procesar la Solicitud',
        //         // text: data.msj,
        //         html : '<div class="form-group"><a>SOLICITUD EXCESO</a></div>'+
        //                 '<div class="form-group">'+
        //                     '<label style="color:red">SUBIR EVIDENCIA EXCESO</label>'+
        //                     '<input type="file" name="archivo" id="archivoFile">'+
        //                 '</div>'+
        //                 '<div class="form-group">'+
        //                     '<textarea class="col-md-12 form-control" placeholder="Ingresar Comentario..." style="height:80px;background:#F9F8CF" id="comentarioText"></textarea>'+
        //                 '</div>',
        //         type: 'warning',
        //         showCancelButton: true,
        //         buttonsStyling: false,
        //         confirmButtonClass: 'btn btn-primary',
        //         confirmButtonText: 'Si, generar Solicitud!',
        //         cancelButtonClass: 'btn btn-secondary',
        //         allowOutsideClick: false
        //     }).then(function(){//falta codigo que genera la solicitud...
        //         let dataFile = new FormData();
        //         var comentario = $('#comentarioText').val();

        //         var fileArchivo = $('#archivoFile')[0].files[0];

        //         dataFile.append('origen', jsonCreateSol.origen);
        //         dataFile.append('itemplan', jsonCreateSol.itemplan_dato);
        //         dataFile.append('tipo_po', jsonCreateSol.tipo_po_dato);
        //         dataFile.append('costo_inicial', costo_actual);
        //         dataFile.append('exceso_solicitado', excedente);
        //         dataFile.append('costo_final', costo_final);
        //         dataFile.append('codigo_po', jsonCreateSol.codigo_po_dato);
        //         dataFile.append('comentario', comentario);
        //         dataFile.append('idEstacion', jsonCreateSol.idEstacion);
        //         dataFile.append('file', fileArchivo);
        //         dataFile.append('data_json', JSON.stringify(jsonCreateSol.data_json));

        //         $.ajax({
        //             data: dataFile,
        //             type: 'POST',
        //             url: 'genSolExce',
        //             cache: false,
        //             contentType: false,
        //             processData: false
        //         }).done(function (data) {
        //             data = JSON.parse(data);
        //             if (data.error == 0) {
        //                 swal({
        //                     title: 'Se realizo la Operacion!',
        //                     text: 'Asegurese de validar la informacion!',
        //                     type: 'success',
        //                     buttonsStyling: false,
        //                     confirmButtonClass: 'btn btn-primary',
        //                     confirmButtonText: 'OK!',
        //                     allowOutsideClick: false
        //                 }).then(function(){
        //                     window.close(); 
        //                 });	                                
        //             }else if(data.error == 1){
        //                 mostrarNotificacion('error', data.msj);
        //             }
                        
        //         });
        //     }, function(dismiss) {
        //         console.log('cancelar.');
        //     });
    }
}

function inicializarTabla(id) {
    $("#"+id).DataTable({
        dom: 'Bfrtip',
        buttons:[{extend:'excelHtml5'}],
        pageLength:3,
        lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],
        language :  {
                        sProcessing:"Procesando...",
                        sLengthMenu:"Mostrar _MENU_ registros",
                        sZeroRecords:"No se encontraron resultados",
                        sEmptyTable:"Ning\u00fan dato disponible en esta tabla",
                        sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",
                        sInfoFiltered:"(filtrado de un total de _MAX_ registros)",
                        sInfoPostFix:"",
                        sSearch:"Buscar:",
                        sUrl : "",
                        sInfoThousands  : ",",
                        sLoadingRecords : "Cargando...",
                        oPaginate: { sFirst    :"Primero",
                                    sLast     : "\u00daltimo",
                                    sNext     : "Siguiente",
                                    sPrevious : "Anterior"},
                                    oAria     : {
                                                    sSortAscending:": Activar para ordenar la columna de manera ascendente",
                                                    sSortDescending:": Activar para ordenar la columna de manera descendente"
                                                }
                    }
    });
}

//CUANDO SE AGREGA UNA NUEVA PARTIDA.
function addActividad(btn) {
    $(btn).closest('tr').css('display', 'none');
    var actividad    = btn.data('descripcion');
    var baremo       = btn.data('baremo');
    var costoKit     = btn.data('costo_kit');
    var idActividad  = btn.data('id_actividad');
    var contAnterior = parseInt($('#tablaPtr tbody tr:last-child').attr('id')); 
    var cont         = contAnterior + 1;
    var precio       = $('#precio_'+contAnterior).html();

    var html = '<tr id="'+cont+'">'+
                    '<td><a title="limpiar" onclick="openAlert();"><i class="fa fa-eraser fa-2" aria-hidden="true"></i></a></td>'+
                    '<td>'+actividad+'</td>'+
                    '<td id="precio_'+cont+'">'+precio+'</td>'+
                    '<td id="baremo_'+cont+'">'+baremo+'</td>'+
                    '<td style="background:#E9C603;color:white">0</td>'+
                    '<td style="background:#E9C603;color:white"><input id="cantidad_'+cont+'" type="number" data-descripcion="'+actividad+'" data-id_actividad="'+idActividad+'" data-cont="'+cont+'" data-id_ptrxactividad_zonal="0" class="form-control" value="0" onchange="calculoCantidad($(this));"></td>'+
                    '<td id="costoMO_'+cont+'">0</td>'+
                    '<td id="costoTotal_'+cont+'">0</td>'+
                '</tr>';
    var js =$('#tablaPtr tbody').append(html);
}

function zipItemPlan(btn) {
    var itemPlan = btn.data('item_plan');
    var val = null;
    if(itemPlan == null || itemPlan == '') {
        return;
    }
    
    $.ajax({
        type : 'POST',
        url  : 'zipItemPlan',
        data : { itemPlan : itemPlan }
    }).done(function(data){
        try {
            data = JSON.parse(data);
            if(data.error == 0) {
                var url= data.directorioZip; 
                if(url != null) {
                    val = window.open(url, 'Download');
                } else {
                    alert('No tiene evidencias');
                }   
                // mostrarNotificacion('success', 'descarga realizada', 'correcto');
            } else {
                // mostrarNotificacion('error', 'descarga no realizada', 'error');            
                alert('error al descargar');
            }
        } catch(err) {
            alert(err.message);
        }
    });
}

function rechazarItemplan(btn) {
	var itemplan = btn.data('itemplan');
	
	if(itemplan == null || itemplan == '') {
		return;
	}
	
	swal({
				title: 'Esta seguro de realizar esta operacion??',
				text: 'Asegurese de validar la informacion',
				type: 'warning',
				buttonsStyling: false,
				confirmButtonClass: 'btn btn-success',
				confirmButtonText: 'SI',
				showCancelButton: true,
				cancelButtonClass: 'btn btn-danger',
				cancelButtonText:'NO'

			}).then(function () {
				    $.ajax({
								type : 'POST',
								url  : 'rechazarItemplanValid',
								data : { itemplan : itemplan }
							}).done(function(data){
								try {
									data = JSON.parse(data);
									if(data.error == 0) {
										swal({
										   title: 'Se rechazo correctamente!',
										   text: 'Asegurese de validar la informacion!',
										   type: 'success',
										   buttonsStyling: false,
										   confirmButtonClass: 'btn btn-primary',
										   confirmButtonText: 'OK!',
										   allowOutsideClick: false
									   }).then(function(){
										   location.reload();
									   }, function(dismiss) {
										   location.reload();
									   });
									} else {
										mostrarNotificacion('error', data.msj, 'verificar');            
									}
								} catch(err) {
									alert(err.message);
								}
							});
			});
}