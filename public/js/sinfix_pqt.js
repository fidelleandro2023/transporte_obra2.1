var costoMA = null;
var costoMO = null;
var total   = null;
var cantidadFinal = null;
var id_ptrxactividad_zonal = null;
var arrayData = [];
var arrayDataInsert = [];

$('#modalFormulario')
.on('hide.bs.modal', function() {
    location.reload();
})
.on('hidden.bs.modal', function(){
location.reload();
});

$('#modalFormularioFuera')
.on('hide.bs.modal', function() {
    location.reload();
})
.on('hidden.bs.modal', function(){
location.reload();
});

$('#modalFormObrasPub')
.on('hide.bs.modal', function() {
    location.reload();
})
.on('hidden.bs.modal', function(){
location.reload();
});
var coordenada_x_global = null;
var coordenada_y_global = null;
function formSisego(jefatura, itemPlan, flg_from, indicador, descEmpresaColab, idEstacion, idEstadoPlan) {
var app = new Vue({
    el: '#sisego',
    data: {
      message          : 'Ingresar Datos',
      msjNombreArray   : 'Ingresar',
      cont             : 0,
      arrayComboTipo   : [],
      idEstadoPlan     : idEstadoPlan,
      selectedUbica    : 0,
      arrayCodigoSelec : [],
      arrayComboCodigo : [],
      cmbTipoObra      : 0,
      cmbCantidadNod   : 0,
      contador         : 0,
      nombreCtoNap     : null,
      nroTroncal       : null,
      cantidadHilosNap : null,
      nodo             : null,
      jefatura         : jefatura,
      itemPlan         : itemPlan,
      idEstacion       : idEstacion,
      nap_ubicacion    : null,
      nap_num_pisos    : null,
      nap_zona         : null,
      flg_from         : flg_from,
      coor_x           : null,
      coor_y           : null,
      piso             : null,
      zona             : null,
      foOscuCantHilos  : null,
      foOscuCantNodo   : 0,
      reuCableExterno  : null,
      reuCableInterno  : null,
      foTradiCantHilo  : null,
      foTraCantHiloHab : null,
      licenciaAfirm    : '',
      jsonValid        : any={ msj : null},
      arrayFichaTecnica: [],
      arrayCmbTipoFicha: [],
      eventClicRegistro : null,
      pisoGlobal        : null,
      sala              : null,
      nroODF            : null,
      bandeja           : null,
      nroHilo           : null,
      flgBoton          : 1,
      selecTipoFicha    : 3,
    //   jsonMaterial     : any = [{
    //       'nombre'      : null,
    //       'observacion' : null
    //   }],
      jsonMaterial : [],
      observacionGenerar : null
    },

    // watch: {
    //     push();
    // },
    methods:{
        getComboTipoObra:function() {
            var msj=this.msj+this.cont; 
            msj = '10';
            var vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getComboTipoObra'
            }).done(function(data) {
                data = JSON.parse(data);
                vue.arrayComboTipo  = data.cmbTipoObra;
                vue.arrayFichaTecnica = data.arrayFicha;
                vue.arrayCmbTipoFicha = data.arrayTipoFicha;
                // if (navigator.geolocation) {
                //     navigator.geolocation.getCurrentPosition(function(position) {
                //         x = position.coords.longitude;
                //         y = position.coords.latitude;

                //         if(x==null||x=='' && y==null||y=='') {
                //             mostrarNotificacion('error', 'Se debe aceptar la geolocalizaci&oacute;n.');
                //             return;
                //         } else {
                //             vue.coor_x = x;
                //             vue.coor_y = y;
                //         }
                //     },mostrarErrores);
                // } 

            });
        },
        
        getComboCodigo:function() {
            var vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getComboCodigo',
                data : { jefatura : vue.jefatura }
            }).done(function(data) {
                data = JSON.parse(data);
                vue.arrayComboCodigo = data.cmbCodigo;                    
            });
        },
        getCodigoObraArray:function(id) {
            // this.arrayCodigo.push(codigo);
            var valor = $('#cmb_'+id).val();
            var valor_anterior = null;
            var vue = this;
            var flg = null;
            if(this.arrayCodigoSelec.length != 0) {
                this.arrayCodigoSelec.forEach(function(element){
                    if(valor != element['value']) {
                        if(element['key'] == id) {
                            vue.contador++;
                            element['value'] = valor;
                            flg = null;
                        } else {
                            flg = (vue.contador == 0) ? 1 : null; 
                        } 
                    } else {
                        mostrarNotificacion('error','El nombre ya fue seleccionada');
                        return;
                    }
                });
                if(flg == 1) {
                    vue.arrayCodigoSelec.push({key   : id,
                                               value : valor});
                    flg = null;
                } 
            } else {
                vue.contador = 0;
                this.arrayCodigoSelec.push({key   : id,
                                            value : valor});
            }              
        },
        
        registrarTrama:function() {
            $('#btnRegistrarTrama').prop('disabled', true);
            var vue = this;
            var ubicacionText = $('#cmbUbicacion option:selected').text();
            var idCmbUbic     = $('#cmbUbicacion option:selected').val();

            vue.coor_x = $('#coor_x').val();
            vue.coor_y = $('#coor_y').val();
            var jsonMat= vue.registrarFicha();
            var idFichaTecnicaBase = 3;
            
            console.log(' vue.itemPlan ' + vue.itemPlan);
            console.log(' vue.flg_from ' + vue.flg_from);
            console.log(' vue.cmbTipoObra ' + vue.cmbTipoObra);
            console.log(' vue.nombreCtoNap ' + vue.nombreCtoNap);
            console.log(' vue.nroTroncal ' + vue.nroTroncal);
            console.log(' vue.cantidadHilosNap ' + vue.cantidadHilosNap);
            console.log(' vue.nodo ' + vue.nodo);
            console.log(' vue.coor_x ' + vue.coor_x);
            console.log(' vue.coor_y ' + vue.coor_y);
            console.log(' ubicacionText ' + ubicacionText);
            console.log(' vue.piso ' + vue.piso);
            console.log(' vue.zona ' + vue.zona);
            console.log(' idCmbUbic ' + idCmbUbic);
            console.log(' vue.foOscuCantHilos ' + vue.foOscuCantHilos);
            console.log(' vue.foOscuCantNodo ' + vue.foOscuCantNodo);
            console.log(' vue.reuCableExterno ' + vue.reuCableExterno);
            console.log(' vue.reuCableInterno ' + vue.reuCableInterno);
            console.log(' vue.foTradiCantHilo ' + vue.foTradiCantHilo);
            console.log(' vue.foTraCantHiloHab ' + vue.foTraCantHiloHab);
            console.log(' vue.licenciaAfirm ' + vue.licenciaAfirm);
            console.log(' JSON.stringify(vue.arrayCodigoSelec) ' + JSON.stringify(vue.arrayCodigoSelec));
            console.log(' indicador ' + indicador);
            console.log(' descEmpresaColab ' + descEmpresaColab);
            console.log(' vue.jefatura ' + vue.jefatura);
            console.log(' vue.idEstacion ' + vue.idEstacion);
            console.log(' vue.idEstadoPlan ' + vue.idEstadoPlan);
            console.log(' jsonMat ' + jsonMat);
            console.log(' vue.observacionGenerar ' + vue.observacionGenerar);
            console.log(' idFichaTecnicaBase ' + idFichaTecnicaBase);
            console.log(' vue.pisoGlobal ' + vue.pisoGlobal);
            console.log(' vue.sala ' + vue.sala);
            console.log(' vue.nroODF ' + vue.nroODF);
            console.log(' vue.bandeja ' + vue.bandeja);
            console.log(' vue.nroHilo ' + vue.nroHilo);
            
            console.log(' ubicacionArchivoFoto ' + ubicacionArchivoFoto);
            var iEvidencias = 0;
            $.ajax({
                type : 'POST',
                url  : 'getArrayFiles',
                data : { 'ubicacion' : ubicacionArchivoFoto }
            }).done(function(data){
				console.log(data);
                data = JSON.parse(data);
                Object.keys(data.arrayName).forEach(function(key){
                	console.log("iEvidencias " + iEvidencias);
                	iEvidencias = iEvidencias + 1;
                });
                console.log("iEvidencias Final " + iEvidencias);
                
                if(iEvidencias <= 1){
                	alert('Debe de registrar al menos 1 prueba de perfil y 1 prueba reflectonometrica');
                	$('#btnRegistrarTrama').prop('disabled', false);
                	return false;
                }
                
                $.ajax({
                    type : 'POST',
                    url  : 'pqt_saveSisegoPlanObra',
                    data : { itemplan                : vue.itemPlan,
                             from                    : vue.flg_from,
                             tipo_obra               : vue.cmbTipoObra,
                             nap_nombre              : vue.nombreCtoNap,
                             nap_num_troncal         : vue.nroTroncal,
                             nap_cant_hilos_habi     : vue.cantidadHilosNap,
                             nap_nodo                : vue.nodo,
                             nap_coord_x             : vue.coor_x,
                             nap_coord_y             : vue.coor_y,
                             nap_ubicacion           : ubicacionText,
                             nap_num_pisos           : vue.piso,
                             nap_zona                : vue.zona,
                             nap_idCmbUbi            : idCmbUbic,
                             fo_oscu_cant_hilos      : vue.foOscuCantHilos,
                             fo_oscu_cant_nodos      : vue.foOscuCantNodo,
                             trasla_re_cable_externo : vue.reuCableExterno,
                             trasla_re_cable_interno : vue.reuCableInterno,
                             fo_tra_cant_hilos       : vue.foTradiCantHilo,
                             fo_tra_cant_hilos_hab   : vue.foTraCantHiloHab,
                             licenciaAfirm           : vue.licenciaAfirm,
                             nodos                   : JSON.stringify(vue.arrayCodigoSelec),
                             indicador               : indicador,
                             descEmpresaColab        : descEmpresaColab,
                             jefatura                : vue.jefatura,
                             idEstacion              : vue.idEstacion,
                             idEstadoPlan            : vue.idEstadoPlan,
                             arrayJsonData           : jsonMat,
                             observacion             : vue.observacionGenerar,
                             idFichaTecnicaBase      : idFichaTecnicaBase,
                             pisoGlobal              : vue.pisoGlobal,
                             sala                    : vue.sala,
                             nroODF                  : vue.nroODF,
                             bandeja                 : vue.bandeja,
                             nroHilo                 : vue.nroHilo
                             }
                }).done(function(data){
                	console.log('Resultado... ' + data);
                    data = JSON.parse(data);
                    if(data.error == 0) {
                        mostrarNotificacion('success', 'Se registr&oacute; correctamente');
                        location.reload();
                    } else {
                        mostrarNotificacion('error', data.msj);
                        $('#btnRegistrarTrama').prop('disabled', false);
                    }
                    
                }).fail(function() {
                    alert( "error" );
                    $('#btnRegistrarTrama').prop('disabled', false);
                })
                
            });
            
        },

        openTab:function(flg) {
            this.flgBoton = flg; 
        },
        
        registrarFicha:function(){
            var array = [];
            var vue = this;
            this.arrayFichaTecnica.forEach(function(data){
                var cantidadTrabajo   = $('#inputCantidadTrabajo'+data.id_ficha_tecnica_trabajo).val();
                var select            = $('#selectTrabajo'+data.id_ficha_tecnica_trabajo+' option:selected').val();
                var comentarioTrabajo = $('#inputComentarioTrabajo'+data.id_ficha_tecnica_trabajo).val();
                
                if(select == '') {
                    select = vue.selecTipoFicha;
                }
                vue.jsonMaterial.push({ id_ficha_tecnica_trabajo      : data.id_ficha_tecnica_trabajo, 
                                        cantidad                      : cantidadTrabajo,
                                        id_ficha_tecnica_tipo_trabajo : select,
                                        observacion                   : comentarioTrabajo });
            });

            return vue.jsonMaterial;
            // $.ajax({
            //     type : 'POST',
            //     url  : 'registrarFichaSinfix',
            //     data : {
            //                 arrayJsonData      : vue.jsonMaterial,
            //                 itemPlan           : vue.itemPlan,
            //                 observacion        : vue.observacionGenerar,
            //                 idEstacion         : idEstacion,
            //                 idFichaTecnicaBase : idFichaTecnicaBase
            //            } 
            // }).done(function(data){
            //     data = JSON.parse(data);
            //     console.log(data.error);
            //     if(data.error == 0) {                        
            //         mostrarNotificacion('success', "registro correcto");
            //         modal('modalFormulario');
            //     } else {
            //         mostrarNotificacion('error', data.msj);
            //     }
            // });
        },
        
        openModalGeo:function() {
        	$('#btnModalUbicacion').click();
            //modal('modalUbicacion');
        }
    },
    mounted:function() {
        this.arrayCodigoSelec = [];
        this.getComboTipoObra();
    },
    updated: function () {
        // jsonMaterial.push()
		map.setZoom(16);
		map.setCenter(new google.maps.LatLng(-12.108368, -77.016398));
		marker = new google.maps.Marker({
						//position: new google.maps.LatLng(-12.108368, -77.016398),
						map: map,
						title:"Tu posición",
						draggable: true,
						animation: google.maps.Animation.DROP
					});

        google.maps.event.addListener(marker, 'dragend', function(){
           
            // if (status == google.maps.GeocoderStatus.OK) {    
                var pos = marker.getPosition();
                $('#coor_x').val(pos.lng());
                $('#coor_y').val(pos.lat());	
            // }
        
            map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
        }); 

        google.maps.event.addListener(map, 'click', function(event) {
                marker.setMap(null);
                
                marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
                title:"Tu posición",
                draggable: true,
                animation: google.maps.Animation.DROP
			});

            //   if (status == google.maps.GeocoderStatus.OK) {                                           
                var pos = marker.getPosition();
                this.coor_x = pos.lng();
                this.coor_y = pos.lat();
                $('#coor_x').val(pos.lng());
                $('#coor_y').val(pos.lat());
                // }

                // var pos = marker.getPosition();
                map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng())); 
                
              google.maps.event.addListener(marker, 'dragend', function(){
                    var pos = marker.getPosition();
                        this.coor_x = pos.lng();
                        this.coor_y = pos.lat();                        			
                        $('#coor_x').val(pos.lng());
                        $('#coor_y').val(pos.lat());				
                        map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
                });
        }); 
        
        /****formulario por fuera sisegos ******/
        
        $("#dropzone15").dropzone({
        	url: "insertFoto2",
        	addRemoveLinks: true,
        	autoProcessQueue: true,
        	parallelUploads: 200,
        	maxFilesize: 3,
        	acceptedFiles: ".pdf,.xml,.xlsx,.docx,.zip",
        	dictResponseError: "Ha ocurrido un error en el server",
        	success: function(file, response) {
        	    data = JSON.parse(response);
        	    if(data.error == 0) {
        	        var button = '';
        	        console.log('ubicacionArchivoFoto:'+ubicacionArchivoFoto);
        	        $.ajax({
        	            type : 'POST',
        	            url  : 'getArrayFiles',
        	            data : { 'ubicacion' : ubicacionArchivoFoto }
        	        }).done(function(data){
        	            data = JSON.parse(data);
        	            Object.keys(data.arrayName).forEach(function(key){
        	                button+='<button class="btn btn-success" data-nombre="'+data.arrayName[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto2($(this))">'+data.arrayName[key].replace("PR_", "").replace("PP_", "")+'</button>';
        	            });
        	            arrayNameGlobal = data.arrayName;
        	            $('#buttonFotosFuera').html(button);
        	        });
        	        
        	        // var count = Object.keys(arrayNameGlobal).length;
        	        // arrayNameGlobal[count+5] = file.name;
        	        // Object.keys(arrayNameGlobal).forEach(function(key){
        	        //     button+='<button class="btn btn-success" data-nombre="'+arrayNameGlobal[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto($(this))">'+arrayNameGlobal[key]+'</button>';
        	        // });
        	        // console.log(button);
        	        // $('#buttonFotos').html(button);
        	    }
        	},

        	complete: function(file){
        	    if(file.status == "success"){
        	        error=0;
        	    }
        	},
        	removedfile: function(file, serverFileName){
        	    var name = file.name;
        	    var element;
        	    (element = file.previewElement) != null ? 
        	    element.parentNode.removeChild(file.previewElement) : 
        	    false;
        	    toog2=toog2-1;		
        	},
        	init: function() {
        	    this.on("error", function(file, message) {
        	          swal({
                    	            title: 'Error en Archivo',
                    	            text: 'El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta',
                    	            type: 'error',
                    	            showCancelButton: false,                    	            
                    	            allowOutsideClick: false
                    	        }).then(function(){
                    	        	return;
                    	        });
        	          
        	          //alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
        	          return;
        	        //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
        	            error=1;
        	          // alert(message);
        	            this.removeFile(file); 
        	    });

        	    var submitButton = document.querySelector("#btnAceptarSubirEvidenciaPostes")
        	    myDropzone = this; 
        	    
        	   var concatEvi = '';
        	    // You might want to show the submit button only when 
        	    // files are dropped here:
        	    this.on("addedfile", function() {		    	
        	        toog2=toog2+1;	
        	      // Show submit button here and/or inform user to click it.
        	    });
        	    
        	    this.on('complete', function () {
        	        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
        	            if(error == 0){
        	                console.log(this.getUploadingFiles());
        	                // $('#edi-evidencias').modal('toggle');
        	            }	            
        	        }	        
        	    });
        	    var x=null;
        	    var y=null;       
        	    this.on("queuecomplete", function (file) {
        	    	console.log('ubicacionArchivoFoto1:'+ubicacionArchivoFoto);
        	        if(error == 0) {		
        	        	console.log('ubicacionArchivoFoto2:'+ubicacionArchivoFoto);
        	            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		
        	            if(flgDocGlobal == null || flgDocGlobal == '' || flgDocGlobal == undefined) {
        	                if (navigator.geolocation) {
        	                    navigator.geolocation.getCurrentPosition(function(position) {
        	                        x = position.coords.longitude;
        	                        y = position.coords.latitude;
        	                        if(x==null||x=='' && y==null||y=='') {
        	                            mostrarNotificacion('error', 'Se debe aceptar la geolocalización.');
        	                            return;
        	                        } else {
        	                            $.ajax({
        	                                type    : 'POST', 
        	                                'url'   : 'ingresarCoordenada',  
        	                                'data'  : { x            : x,
        	                                            y            : y,
        	                                            idEstacion   : idEstacionGlobalFoto},   
        	                                'async' : false
        	                            });
        	                        }
        	                    },mostrarErrores);
        	                } 
        	            }

        	            this.removeAllFiles(true); 
        	            /*
        	            if(flgDocGlobal != 2) {
        	                $.ajax({
        	                    type    : 'POST', 
        	                    'url'   : 'getEstacionesFoto',  
        	                    'data'  : { itemPlan   : itemPlanGlobalSubirFoto,
        	                                idEstacion : idEstacionGlobalFoto },   
        	                    'async' : false
        	                }).done(function(data){
        	                        data = JSON.parse(data);
        	                        if(data.error == 0) {
        	                            $('#contEstaciones').html(data.estaciones);
        	                            // location.reload();
        	                        } else {
        	                            mostrarNotificacion('error', 'error al enviar la trama', 'error');
        	                        }
        	                });
        	            } else {
        	                location.reload();
        	            }*/
        	            // location.reload();
        	            mostrarNotificacion('success','Archivo','Se subi&oacute; la foto correctamente');
        	            //refreshTablaRuta();
        	        }
        	    });		
        	    this.on('sending', function (file, xhr, formData) {
                   
                    formData.append('itemplan', itemPlanGlobalSubirFoto);
                    formData.append('descEstacion', 'FO');
                    formData.append('idEstacion', idEstacionGlobalFoto);
                    formData.append('tipoPrueba', 'PR_');
                });
        	    
        	  }
        	});
        
        $("#dropzone16").dropzone({
        	url: "insertFoto2",
        	addRemoveLinks: true,
        	autoProcessQueue: true,
        	parallelUploads: 200,
        	maxFilesize: 3,
        	acceptedFiles: ".pdf,.xml,.xlsx,.docx,.zip",
        	dictResponseError: "Ha ocurrido un error en el server",
        	success: function(file, response) {
        	    data = JSON.parse(response);
        	    if(data.error == 0) {
        	        var button = '';
        	        console.log('ubicacionArchivoFoto:'+ubicacionArchivoFoto);
        	        $.ajax({
        	            type : 'POST',
        	            url  : 'getArrayFiles',
        	            data : { 'ubicacion' : ubicacionArchivoFoto }
        	        }).done(function(data){
        	            data = JSON.parse(data);
        	            Object.keys(data.arrayName).forEach(function(key){
        	                button+='<button class="btn btn-success" data-nombre="'+data.arrayName[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto2($(this))">'+data.arrayName[key]+'</button>';
        	            });
        	            arrayNameGlobal = data.arrayName;
        	            $('#buttonFotosFuera').html(button);
        	        });
        	        
        	        // var count = Object.keys(arrayNameGlobal).length;
        	        // arrayNameGlobal[count+5] = file.name;
        	        // Object.keys(arrayNameGlobal).forEach(function(key){
        	        //     button+='<button class="btn btn-success" data-nombre="'+arrayNameGlobal[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto($(this))">'+arrayNameGlobal[key]+'</button>';
        	        // });
        	        // console.log(button);
        	        // $('#buttonFotos').html(button);
        	    }
        	},

        	complete: function(file){
        	    if(file.status == "success"){
        	        error=0;
        	    }
        	},
        	removedfile: function(file, serverFileName){
        	    var name = file.name;
        	    var element;
        	    (element = file.previewElement) != null ? 
        	    element.parentNode.removeChild(file.previewElement) : 
        	    false;
        	    toog2=toog2-1;		
        	},
        	init: function() {
        	    this.on("error", function(file, message) {
        	        swal({
                    	            title: 'Error en Archivo',
                    	            text: 'El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta',
                    	            type: 'error',
                    	            showCancelButton: false,                    	            
                    	            allowOutsideClick: false
                    	        }).then(function(){
                    	        	return;
                    	        });
        	          //alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
        	          return;
        	        //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
        	            error=1;
        	          // alert(message);
        	            this.removeFile(file); 
        	    });

        	    var submitButton = document.querySelector("#btnAceptarSubirEvidenciaPostes")
        	    myDropzone = this; 
        	    
        	   var concatEvi = '';
        	    // You might want to show the submit button only when 
        	    // files are dropped here:
        	    this.on("addedfile", function() {		    	
        	        toog2=toog2+1;	
        	      // Show submit button here and/or inform user to click it.
        	    });
        	    
        	    this.on('complete', function () {
        	        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
        	            if(error == 0){
        	                console.log(this.getUploadingFiles());
        	                // $('#edi-evidencias').modal('toggle');
        	            }	            
        	        }	        
        	    });
        	    var x=null;
        	    var y=null;       
        	    this.on("queuecomplete", function (file) {
        	    	console.log('ubicacionArchivoFoto1:'+ubicacionArchivoFoto);
        	        if(error == 0) {		
        	        	console.log('ubicacionArchivoFoto2:'+ubicacionArchivoFoto);
        	            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		
        	            if(flgDocGlobal == null || flgDocGlobal == '' || flgDocGlobal == undefined) {
        	                if (navigator.geolocation) {
        	                    navigator.geolocation.getCurrentPosition(function(position) {
        	                        x = position.coords.longitude;
        	                        y = position.coords.latitude;
        	                        if(x==null||x=='' && y==null||y=='') {
        	                            mostrarNotificacion('error', 'Se debe aceptar la geolocalización.');
        	                            return;
        	                        } else {
        	                            $.ajax({
        	                                type    : 'POST', 
        	                                'url'   : 'ingresarCoordenada',  
        	                                'data'  : { x            : x,
        	                                            y            : y,
        	                                            idEstacion   : idEstacionGlobalFoto},   
        	                                'async' : false
        	                            });
        	                        }
        	                    },mostrarErrores);
        	                } 
        	            }

        	            this.removeAllFiles(true); 
        	            /*
        	            if(flgDocGlobal != 2) {
        	                $.ajax({
        	                    type    : 'POST', 
        	                    'url'   : 'getEstacionesFoto',  
        	                    'data'  : { itemPlan   : itemPlanGlobalSubirFoto,
        	                                idEstacion : idEstacionGlobalFoto },   
        	                    'async' : false
        	                }).done(function(data){
        	                        data = JSON.parse(data);
        	                        if(data.error == 0) {
        	                            $('#contEstaciones').html(data.estaciones);
        	                            // location.reload();
        	                        } else {
        	                            mostrarNotificacion('error', 'error al enviar la trama', 'error');
        	                        }
        	                });
        	            } else {
        	                location.reload();
        	            }*/
        	            // location.reload();
        	            mostrarNotificacion('success','Archivo','Se subi&oacute; la foto correctamente');
        	            //refreshTablaRuta();
        	        }
        	    });		
        	    this.on('sending', function (file, xhr, formData) {
                   
                    formData.append('itemplan', itemPlanGlobalSubirFoto);
                    formData.append('descEstacion', 'FO');
                    formData.append('idEstacion', idEstacionGlobalFoto);
                    formData.append('tipoPrueba', 'PP_');
                });
        	    
        	  }
        	});
        
    }
  });
}

function init() {       
var mapdivMap = document.getElementById("contenedor_mapa");
center = new google.maps.LatLng(-12.0431800, -77.0282400);       
var myOptions = {
    zoom: 5,
    center: center,
    mapTypeId: google.maps.MapTypeId.ROADMAP
}
map = new google.maps.Map(document.getElementById("contenedor_mapa"), myOptions);            
infoWindow = new google.maps.InfoWindow();  

// if(global_coord_x!='' && global_coord_y!=''){
//     centrarMapaEdit(global_coord_y, global_coord_x);  
// }else{
    geoposicionar();    
// }
//llenarMarcadores();               
}

function geoposicionar(){        	
if(navigator.geolocation){
    //mostrarNotificacion('info', "obteniendo posición...");
    navigator.geolocation.getCurrentPosition(centrarMapa);
}else{
    mostrarNotificacion('error','Tu navegador no soporta geolocalización');
}
}

function centrarMapa(pos){
map.setZoom(16);
// console.log(':centrarMapa....'+pos.coords.latitude+'-'+pos.coords.longitude);
map.setCenter(new google.maps.LatLng(pos.coords.latitude,pos.coords.longitude));
    marker = new google.maps.Marker({
    position: new google.maps.LatLng(pos.coords.latitude,pos.coords.longitude),
    map: map,
    title:"Tu posición",
    draggable: true,
    animation: google.maps.Animation.DROP
  });

var geocoder = new google.maps.Geocoder();

geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
          var pos = marker.getPosition();
          coordenada_x_global = pos.lng();
          coordenada_y_global = pos.lat();
          var address=results[0]['formatted_address'];
         openInfoWindowAddress(address,marker);         							
   }
 });	            

google.maps.event.addListener(map, 'click', function(event) {
    console.log("SELECCIONO 2");
    
        marker.setMap(null);
        
        marker = new google.maps.Marker({
        position: event.latLng,
        map: map,
        title:"Tu posición",
        draggable: true,
        animation: google.maps.Animation.DROP
      });

        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
    console.log("SELECCIONO 3");
                                                    
                    var pos = marker.getPosition();
                    coordenada_x_global = pos.lng();
                    coordenada_y_global = pos.lat();
                    var address=results[0]['formatted_address'];
                        openInfoWindowAddress(address,marker);
             }
           });	
        var pos = marker.getPosition();
        map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng())); 
        
      google.maps.event.addListener(marker, 'dragend', function(){
            var pos = marker.getPosition();
            geocoder.geocode({'latLng': pos}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {                        			
                        coordenada_x_global = pos.lng();
                        coordenada_y_global = pos.lat();
                        var address=results[0]['formatted_address'];
                            openInfoWindowAddress(address,marker);				
                 }
             });
                map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
        });
}); 
}

function openInfoWindowAddress(Addres,marker) {
console.log('geo..');
 infoWindow.setContent([
     Addres
 ].join(''));
 infoWindow.open(map, marker);
}

var estacionDesc             = null;
var itemPlanGlobalPorcentaje = null;
function openModalPorcentaje(btn) {
    itemPlanGlobalPorcentaje = btn.data('item_plan');

    $.ajax({
        type : 'POST',
        url  : 'getDataEstacionesLiquidacion',
        data : { itemplan : itemPlanGlobalPorcentaje }
    }).done(function(data) {
        data = JSON.parse(data);
        $this=$(this);
        // $.fancybox(data.htmlEstaciones, {
        //     type: "html",


        //             css : {
        //                 'background-color' : '#000',
        //                  width   : 100,
        //                  height  : 100
        //             }
        //         ,
        //         wrap : {
                    
        //         },
        //         thumbs  : {
        //             width   : 100,
        //             height  : 100
        //         }
            
        // });
        $('#contPorcentaje').html(data.htmlEstaciones);
        modal('modalPorcentaje');
        // $(".fancybox-wrap").css('width',"100% !important"); 
        // $(".fancybox-wrap").fancybox({"width":900,"height":900});
    });
}
var idZonalGlobal             = null;
var porcentajeActualGlobal    = null;
var empcolabGlobalPorcentaje  = null;
var idProyectoGlobal          = null;
var idSerieTrobaGlobalEjecPor = null; 
var itemPlanFormPorcentaje    = null;
function openFormPorcentaje(btn) {
itemPlanFormPorcentaje    = btn.data('itemplan');
var descEstacion          = btn.data('desc_estacion');
var idEstacion            = btn.data('id_estacion');
var idZonal               = btn.data('id_zonal');
idSerieTrobaGlobalEjecPor = btn.data('id_serie_troba');
idProyectoGlobal          = btn.data('id_proyecto');

if(itemPlanFormPorcentaje == null ||itemPlanFormPorcentaje == '') {
    return;
} 

if(descEstacion == null || descEstacion == '') {
    return;
}

if(idEstacion == null || idEstacion == '') {
    return;
}

if(idZonal == null || idZonal == '') {
    return;
}

if(idProyectoGlobal == null || idProyectoGlobal == '') {
    return;
}

$.ajax({
    type : 'POST',
    url  : 'getFormPorcentaje',
    data : { 
                itemplan     : itemPlanFormPorcentaje,
                descEstacion : descEstacion,
                idEstacion   : idEstacion, 
                idZonal      : idZonal 
            }
}).done(function(data) {
    idZonalGlobal = idZonal;
    itemPlanFormPorcentaje = itemplan;

    data = JSON.parse(data);
    $("#porcen").html(data.formPorcentaje);
    porcentajeActualGlobal = data.porcentajeActual;
    empcolabGlobalPorcentaje = data.eccSession;    
});
}
var idActividadGlobalPorcentaje  = null;
var itemPlanPorcentaje           = null;
var idPlanobraActividadGlobalPor = null;
var conversacionGlobalPor        = null;
var porcentajeActividadGlobal    = null;
function ejecutarPorcentaje(btn) {  
    sid= btn.attr("id");
    var id = $(".id").val();
    var itemPlan    = btn.data('item_plan');
    var idActividad = btn.data('id_actividad');
    var idEstacion  = btn.data('id_estacion');
    var id_planobra_actividad = btn.data('id_planobra_actividad');
    var desEstacion = btn.data('desc_estacion');

    if(idEstacion == null || idEstacion == '' || sid == null || sid == ''|| itemPlan == '' || itemPlan == null) {
        return;
    }

    if(idZonalGlobal == null || idZonalGlobal == '' || idProyectoGlobal == null || idProyectoGlobal == '' || desEstacion == '' || desEstacion == null) {
        return;
    }
    var cmbCuadrilla  = $(".cuadrilla_"+sid).val();
    var cmbPorcentaje = $("#cmbPorcentaje"+sid).val();

    parseInt(empcolabGlobalPorcentaje);
    if(empcolabGlobalPorcentaje != 6 && empcolabGlobalPorcentaje != 0) {
        if(parseInt(porcentajeActualGlobal) > parseInt(cmbPorcentaje)) {
            alert("Solo el TDP puede bajar el porcentaje");
            return;
        }
    }

    if(cmbPorcentaje == null || cmbPorcentaje == '') {
        mostrarNotificacion('error', 'No se ingres&oacute; el porcentaje', 'seleccionar porcentaje -> clic en el lapiz');
        
        // alert("Debe seleccionar un porcentaje");
        return;
    }

    if(cmbCuadrilla == 0 || cmbCuadrilla == '') {
        mostrarNotificacion('error', 'No se ingres&oacute; cuadrilla', 'seleccionar cuadrilla -> clic en el lapiz')
        return;
    }

    if(!id_planobra_actividad) {
        id_planobra_actividad = null;
    }

    $.ajax({
        type : 'POST',
        data : { id                    : id,
                 id_planobra_actividad : id_planobra_actividad,
                 id_subactividad       : idActividad,
                 select_cuadrilla      : cmbCuadrilla,
                 fporcentaje           : $("#cmbPorcentaje"+sid).val(),
                 conversacion          : $("#conversacion_"+sid).val(),
                 idEstacion            : idEstacion,
                 idZonal               : idZonalGlobal,
                 itemPlan              : itemPlan,
                 idProyecto            : idProyectoGlobal,
                 desEstacion           : desEstacion,
                 idSerieActualBd       : idSerieTrobaGlobalEjecPor },
        url  : 'ejecutarPorcentaje'         
    }).done(function(data) {
        data = JSON.parse(data);
        $('#contEstaciones').html(data.estaciones);
        if(data.error == 0) {
            mostrarNotificacion('success','Se registr&oacute; correctamente');
        } else {
            mostrarNotificacion('error','error al registrar');
        }
        
        // $("#myModal").modal();
        console.log("idProyecto "+data.idProyecto);
        console.log("idEstacion "+idEstacion);
        console.log("porcentaje "+data.porcentaje);
        
        console.log("idEstadoPlan "+data.idEstadoPlan);
        console.log("fecha "+data.fecha);
        if(data.idProyecto == 3 && idEstacion == 5 && data.porcentaje == 100 && data.idEstadoPlan == 3) { 
            // modal('modalFormulario');
            //tramaSinfixSisego(itemPlan, data.fecha, data.indicador);
        } 
        // else {
        //     location.reload();
        // }
        
        itemPlanPorcentaje          = itemPlan;
        idActividadGlobalPorcentaje = idActividad;
        idPlanobraActividadGlobal   = id_planobra_actividad;
        porcentajeActividadGlobal   = cmbPorcentaje;
    });
}

function getEstaciones(itemPlan, urlPhp) {
    $.ajax({
        type : 'POST',
        data : { itemPlan : itemPlan },
        url  : urlPhp
    }).done(function(data){
        data = JSON.parse(data);
        $('#contEstaciones').html(data.estacionHtml);
    });
}

function aceptarPorcentaje() {
    location.reload();
}

// function tramaSinfixSisego(itemPlan, fechaEjec, indicador) {
//     console.log("ENTRO1111");
//     $.ajax({
//         type	:	'POST',
//         'url'	:	'https://gicsapps.com:8080/obras2/recibir_eje.php',
//         data	:	{  itemplan : itemPlan,
//                        fecha    : fechaEjec,
//                        sisego   : indicador },
//         'async'	:	false
//    }).done(function(data){         	    
//        try {
//             var data = JSON.parse(data);
//             console.log('return:'+JSON.stringify(data));    
            
//             if(data.error == 0){
//                 location.reload();
//                 console.log('ok:'+JSON.stringify(data));    
//             }else if(data.error == 1){     				
//                 console.log('error:'+JSON.stringify(data));
//             }
//        } catch(err) {
//             mostrarNotificacion('error',err.message);
//        }
//    });
// }

var idEstacionGlobalFoto    = null;
var flgDocGlobal            = null;
var itemPlanGlobalSubirFoto = null;
var arrayNameGlobal         = null;
var ubicacionArchivoFoto    = null;

var nomArchivoFotoGlobal = null;
var keyGlobal = null;
function openModalDeleteArchFoto(btn) {
console.log(ubicacionArchivoFoto);
nomArchivoFotoGlobal = btn.data('nombre');
keyGlobal            = btn.data('key_json');
$("#btnModalAlertaDelete").click();
//modal('modalAlertaDelete');
}

function deleteArchivoFoto() {

$.ajax({
    type : 'POST',
    url  : 'deleteArchivoFoto',
    data : { 'nombreArchivoFoto' : nomArchivoFotoGlobal,
             'ubicacion'         : ubicacionArchivoFoto }
}).done(function(data){
    data = JSON.parse(data);
    if(data.error == 0) {
        var button = '';
        delete arrayNameGlobal[keyGlobal];
    
        Object.keys(arrayNameGlobal).forEach(function(key){
            button+='<button class="btn btn-success" data-nombre="'+arrayNameGlobal[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto($(this))">'+arrayNameGlobal[key]+'</button>';
        });
        $('#buttonFotos').html(button);
        $('#buttonEvidencia').html(button);
        $("#btnModalAlertaDelete").click();
        //modal('modalAlertaDelete');
        mostrarNotificacion('success', 'correcto', 'Se elimino correctamente');
    } else {
        mostrarNotificacion('error','Error', data.msj);
    }
});
}

function openModalSubirAct(descEstacion, idEstacion, flg, descActividad) {
var descActividad = replaceAll(descActividad, ' ', '_');

$('#modalSubirFotoAct').modal({backdrop: 'static', keyboard: false});
$('#tituloModalAct').text('SUBIR EVIDENCIA POR ACTIVIDAD');      
}

function replaceAll(str, term, replacement) {
return str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
}
function escapeRegExp(string){
return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
}
var toog2=0;
//var error=0;
Dropzone.autoDiscover = false;
var itemplan = '';
var x = null;
var y = null;

function mostrarErrores(error) {
switch(error.code) 
{
    case error.PERMISSION_DENIED:
    //mostrarNotificacion('error', 'El usuario denego la petición de geolocalización.');
    break;
    case error.POSITION_UNAVAILABLE:
    mostrarNotificacion('error', 'Información de localización no disponible.');
    break;
    case error.TIMEOUT:
    mostrarNotificacion('error', 'La petición para obtener la ubicación del usuario expiró.');   
    posicionDiv.innerHTML="La petición para obtener la ubicación del usuario expiró."
    break;
    case error.UNKNOWN_ERROR:
    mostrarNotificacion('error', 'Error desconocido.');   
    posicionDiv.innerHTML="Error desconocido."
    break;
}
}

function openModalFotos(btn) {
var itemPlan = btn.data('item_plan');

if(itemPlan == null || itemPlan == '') {
    return;
}

$.ajax({
type : 'POST',
url  : 'getFotosEvi',
data : { itemPlan : itemPlan }
}).done(function(data) {
    data = JSON.parse(data);
    modal('modalGaleriaFotos');  
    $('#list-imageFO').html(data.listImageFO);   
    $('#list-imageCO').html(data.listImageCO); 
    $('#list-imageTRO').html(data.listImageINSTROBA);
});
}

var itemPlanGlobalSerieTroba = null;
var idEstacionGloSerieTroba  = null;
var descEstacionGlobal       = null;
//SERIE DE TROBA ACTUAL DE LA BD;
var idSerieTroBdGloActual    = null;
function openModalSeleccionarSerie(itemPlan, idEstacion, descEstacion, idSerieTrobaBdActual) {
$.ajax({
    type : 'POST',
    url  : 'openModalSeleccionarSerie'
}).done(function(data){
    data = JSON.parse(data);
    $('#cmbSerieMostrar').html(data.cmbSerieMostrar);
    itemPlanGlobalSerieTroba = itemPlan;
    idEstacionGloSerieTroba  = idEstacion;
    descEstacionGlobal       = descEstacion;
    idSerieTroBdGloActual    = idSerieTrobaBdActual;
    modal('modalSeleccionarSerie'); 
});
}

function ingresarSerieTroba() {
var idSerieTroba = $('#cmbSerieTroba option:selected').val();

if(idSerieTroba == null || idSerieTroba == ''){
    return;
}
if(idSerieTroBdGloActual == null || idSerieTroBdGloActual == '') {
    return;
}

$.ajax({
type : 'POST',
url  : 'ingresarSerieTroba',
data : { itemPlan           : itemPlanGlobalSerieTroba,
         idSerieTroba       : idSerieTroba,
         idEstacion         : idEstacionGloSerieTroba,
        //  descEstacion       : descEstacion,
         idSerieTroBdActual : idSerieTroBdGloActual }
}).done(function(data){
    data = JSON.parse(data);
    if(data.error == 0) {
        modal('modalSeleccionarSerie');
        $.ajax({
            type    : 'POST', 
            'url'   : 'getEstacionesFoto',  
            'data'  : { itemPlan   : itemPlanGlobalSerieTroba,
                        idEstacion : idEstacionGloSerieTroba },   
            'async' : false
        }).done(function(data){
            data = JSON.parse(data);
            if(data.error == 0){
                location.reload(); 
            }
        })
    } else {
           console.log("error al ingresar la serie");
        //mostrarNotificacion('error', 'error', 'error al ingresar serie');
    }        
});
}

var itemPlanGlobalPreLiquidacion = null;
var idSubProyectoGlobalPreliqui  = null;
function cambiarEstadoPreLiquidado(btn) {
itemPlanGlobalPreLiquidacion = btn.data('item_plan');
idSubProyectoGlobalPreliqui  = btn.data('id_sub_proyecto');

if(itemPlanGlobalPreLiquidacion == null || itemPlanGlobalPreLiquidacion == '') {
    return;
}

if(idSubProyectoGlobalPreliqui == null || idSubProyectoGlobalPreliqui == '') {
    return;
}
modal('modalConfirmacion');
}

function cambiarEstado() {
  if(itemPlanGlobalPreLiquidacion == null || itemPlanGlobalPreLiquidacion == '') {
      return;
  }

  if(idSubProyectoGlobalPreliqui == null || idSubProyectoGlobalPreliqui == '') {
    return;
}
  $.ajax({
    type : 'POST',
    url  : 'cambiarEstadoItemPlan', 
    data : { itemPlan      : itemPlanGlobalPreLiquidacion,
             idSubProyecto : idSubProyectoGlobalPreliqui }
  }).done(function(data){
      data = JSON.parse(data);
      if(data.error == 0) {
        location.reload();
        modal('modalConfirmacion'); 
      } else {
        mostrarNotificacion('error', 'error', 'error');
        return;
      }
  });
}

function terminarObra(btn) {
var itemPlan = btn.data('item_plan');
    $this=$(this);	
    if(confirm("Desea Terminar el Itemplan : "+itemPlan)==true){
    $.post("ejecucion?pagina=terminar&id="+itemPlan,{},function(){window.location.reload()})
    }
    return false;
}



// function verDetalle() {
//     parent.$.fancybox.close();
//     parent.location.href="detalle_obra?id_planobra_actividad="+$(this).attr("id");    
// }
//   function openModalPorcentaje(btn) {
//     itemPlanGlobal = btn.data('item_plan');
//     console.log(itemPlanGlobal);
//     $.ajax({
//         type : 'POST',
//         url  : 'getEstacionesHtml',
//         data : { itemPlan : itemPlanGlobal }
//     }).done(function(data) {
//         data = JSON.parse(data);
//         $('#modalPorcentaje').html(data.estaciones);
//         modal('modalPorcentaje');
//     });
//   }


// function verDetalle(btn) {
//         sid=$(this).attr("id");
//         var itemPlan    = btn.data('item_plan');
//         var idActividad = btn.data('id_actividad');
//         var idEstacion  = btn.data('id_estacion');
//         var id_planobra_actividad = btn.data('id_planobra_actividad');

//     $.post("ajax",
//         {
//             pagina                : "ejecutarporcentaje",
//             id                    : $(".id").val(),
//             id_planobra_actividad : $("#id_planobra_actividad_"+sid).val(),
//             id_subactividad       : $("#id_subactividad_"+sid).val(),
//             select_cuadrilla      : $(".cuadrilla_"+sid).val(),
//             fporcentaje           : $("#fporcentaje_"+sid).val(),
//             conversacion          : $("#conversacion_"+sid).val()
//         },function(){
//             $("#myModal").modal();
//         });
// }

function verDetalle(btn) {
mostrarNotificacion('info','Esta opci&oacute;n ser&aacute; habilitado en breve', 'espere por favor...');
return;
var idPlanObraActividad = btn.data('id_planobra_actividad');

var a = document.createElement("a");
a.target = "_blank";
//parent.$.fancybox.close();
// var idPlanObraActividad = $(this).attr("id");
if(idPlanObraActividad == null || idPlanObraActividad == '') {
    mostrarNotificacion('info', "Debe tener porcentaje para ingresar a esta opci&oacute;n", 'seleccionar porcentaje -> clic en el lapiz');
    return;
}
a.href = "detalle_obra?id_planobra_actividad="+idPlanObraActividad+"&itemPlan="+itemPlanFormPorcentaje;
a.click();
};

function openModalBandejaEjecucion(btn) {
var vue = this;
var jefatura   = btn.data('jefatura');
var itemPlan   = btn.data('item_plan');
var flg_from   = btn.data('flg_from');

if(flg_from == 2) {
    var idEstadoPlan     = btn.data('id_estado_plan');
    var idEstacion       = btn.data('id_estacion');
    var indicador        = btn.data('indicador');
    var descEmpresaColab = btn.data('desc_emp_colab');
    
    if(indicador == null && descEmpresaColab == null) {
        console.log("indicador  o desc. empresa colab Nulo (funcion openModalBandejaEjecucion())");
        return;
    }
    $('#material').css('display', '');
    formSisego(jefatura, itemPlan, flg_from, indicador, descEmpresaColab, idEstacion, idEstadoPlan);
} else {
    vue.isActiveTabParent = false;
    formSisego(jefatura, itemPlan, flg_from, null, null, null, null);
}
$('#modalFormulario').modal({backdrop: 'static', keyboard: false});
}

function zipItemPlanPqt(btn) {
var itemPlan = btn.data('item_plan');
var val = null;
if(itemPlan == null || itemPlan == '') {
    return;
}

$.ajax({
    type : 'POST',
    url  : 'zipItemPlanPqt',
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


function zipSinfixAnterior(btn) {
try {
    var itemPlan     = btn.data('item_plan');
    var descProyecto = btn.data('desc_proy');
    var ubicacion    = btn.data('ubicacion');
    if(itemPlan == null || itemPlan == '' || itemPlan == undefined) {
        return;
    }
    if(descProyecto == null || descProyecto == '') {
        return;
    }
    //var ubicacion = 'uploads/zip/'+itemPlan+'-'+descProyecto+'.zip';
    if(ubicacion != null) {
        window.open(ubicacion, 'Download');
    } else {
        alert('No tiene zip');
    }   
} catch(err) {
    alert(err.message);
}
}

function getConsultaPtr(btn) {
$this=btn;
var itemPlan = $this.data('item_plan');
var tipoPlanta = btn.data('tipo_planta');

// var id = $(this).attr('data-idrow');

// $('#'+id).css('background-color', 'yellow');

// if(itemPlanAnterior!=null && itemPlanAnterior!=id) {
//     $('#'+itemPlanAnterior).css('background-color', 'white');  
// }     
// itemPlanAnterior = id;
if(tipoPlanta == 2) {
    ubicacion = "detallePI?item="+itemPlan+"&from=1";
} else {
    ubicacion = "detalleObra?item="+itemPlan+"&from=null";
}
$.fancybox({ 
    height:"100%",href:ubicacion,type:"iframe",width:"100%"
});
return!1
}   

function openView(btn) {
var indicador = btn.data('indicador');
var grafo     = btn.data('grafo');
var itemplan  = btn.data('item_plan');

if(indicador == null || grafo == null || grafo == '') {
    alert("No tiene grafo");
    return;
}
//var url = 'http://200.48.131.32/obras2/general/estudio_sisego.php?sisego='+indicador+'&grafo='+grafo;
var url = 'http://200.48.131.32/obras2/general/estudio_itemplan.php?itemplan='+itemplan;
window.open(url, 'Download');
}

function regresarTrunco(btn) {
var itemPlan = btn.data('item_plan');
$this=$(this);	
if(confirm("Desea Regresar a Obra el Itemplan : "+itemPlan)==true) {
    $.post("ejecucion?pagina=regresar_truncar&id="+itemPlan,{},function()
    {
        window.location.reload();
    })
return false; 
}
}

function openModalCuadrilla() {
vueModalCuadrilla();
modal('modalFormCuadrilla');
}

function openModalMotivo() {
modal('modalMotivo');
}

function vueModalCuadrilla() {
var app = new Vue ({
    el: '#modalFormCuadrilla',
    data: {
        arrayZonal      : [],
        arrayEcc        : [],
        arraTablaCua    : [],
        arrayCuadrilla  : [],
        arrayUsuarioCua : [],
        titulo : 'MANTENIMIENTO DE CUADRILLA',
        objData : any = { 
                            inputNombreCuadrilla : {
                                                    label       : 'NOMBRE DE CUADRILLA',
                                                    modelNombre : null,
                                                    msjValid    : null
                                                   },
                            cmbZonal             : {
                                                    label      : 'ZONAL',
                                                    modelZonal : 0,
                                                    msjValid    : null
                                                   },
                            cmbEcc               : {
                                                    label      : 'EMPRESA COLABORADORA',
                                                    modelEcc   : 0,
                                                    msjValid   : null
                                                   },
                            cmbUsuario           : {
                                                    label        : 'USUARIO',
                                                    modelUsuario : 0,
                                                    msjValid     : null
                                                   }                                                       
                        }
    },
    methods:{
        getTablaCuadrilla:function() {
            vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getTablaCuadrilla'
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayCuadrilla = data.array_cuad;
            });
        },
        openModalForm:function() {
            modal('modalFormCuadrilla');
        },
        getCmb:function() {
            vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getCmbsCuadrillas'
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayZonal      = data.cmbZonal;
                vue.arrayEcc        = data.cmbEecc;
                vue.arrayUsuarioCua = data.cmbUsuCua;
            });
        }, 
        registrarCuadrilla:function() {
            vue = this;
            $.ajax({
                type : 'POST',
                url  : 'registrarCuadrilla',
                data : { 'nombCuadrilla' : vue.objData.inputNombreCuadrilla.modelNombre,
                         'idEecc'        : vue.objData.cmbEcc.modelEcc,
                         'idZonal'       : vue.objData.cmbZonal.modelZonal,
                         //'idUsuarioCuad' : vue.objData.cmbUsuario.modelUsuario 
                        }
            }).done(function(data){
                data = JSON.parse(data);
                try {
                    if(data.error==0) {
                        mostrarNotificacion('success', 'Cuadrilla registrada');
                        vue.arrayCuadrilla = data.array_cuad;
                        modal('modalFormCuadrilla');
                        location.reload();
                    } else {
                        mostrarNotificacion('error', data.msj);
                    }
                } catch(err) {
                    mostrarNotificacion('error', err.message);
                }
            });
        }
    }, 
    mounted: function() {
        this.getCmb();
        this.getTablaCuadrilla();
    }
});
}

function registrarKit(btn) {
var itemPlan = btn.data('itemplan');
var idSubPro = btn.data('idsubpro');
var accion	 = btn.data('accion');
 $.ajax({
            type : 'POST',
            url  : 'getContMateriales',
            data : {itemplan : itemPlan,
                    idSubPro : idSubPro,
                    accion	:	accion}                    
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0){
            $('#btnRegKitMat').attr('data-idSubPro',data.idsubPro);
            $('#btnRegKitMat').attr('data-itemplan',data.itemplan);     
            $('#btnRegKitMat').attr('data-accion',data.accion);     
            $('#bodyTable').html(data.htmlConTabla);
            soloDigitos('canclass');
            $('#txtDireccion').val(data.direccion);
            $('#txtNumero').val(data.numero);
            $('#txtPisos').val(data.pisos);
            $('#selectInstala').val((data.cto != '') ? data.cto : 'SI');
            $('#selectCamara').val((data.camara != '') ? data.camara : 'SI');
            $('#selectTipoTrabajo').val((data.tipoPartida != '') ? data.tipoPartida : '1');   
            $('#txtDepartamentos').val(data.dptos);                
            $('#modalKitMaterial').modal('toggle');
        }
    });
}

$('#forRegistrarKitMate')
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


var $form    = $(e.target),
    formData = new FormData(),
    params   = $form.serializeArray(),
    bv       = $form.data('bootstrapValidator');
    
    $.each(params, function(i, val) {
        formData.append(val.name, val.value);
    });
    var idSubPro	=	$('#btnRegKitMat').attr('data-idSubPro');
    formData.append('idSubPro', idSubPro);
    
    var itemplan	=	$('#btnRegKitMat').attr('data-itemplan');
    formData.append('itemplan', itemplan);
    
    var accion		=	$('#btnRegKitMat').attr('data-accion');
    formData.append('accion', accion);
    
    swal({
        title: 'Está seguro registrar los Materiales?',
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
                 location.reload();
             }else if(data.error == 1){     				
                 mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
             }
          });
    }, function(dismiss) {
        // dismiss can be "cancel" | "close" | "outside"
            $('#forRegistrarKitMate').bootstrapValidator('resetForm', true); 
    });
});


var itemPlanEstadoGlobal  = null;
var estadoPlanGlobal      = null;
var dataFechaGlobal       = null; 
var fechaPlamEstadoGlobal = null;           
function openModalTruncar(btn) {
itemPlanEstadoGlobal  = btn.data('itemplan');
estadoPlanGlobal      = btn.data('estado_plan');
dataFechaGlobal       = btn.data('data_fecha'); 
fechaPlamEstadoGlobal = btn.data('fecha');

if(itemPlanEstadoGlobal == null) {
    return;
}

if(estadoPlanGlobal == null) {
    return;
}

if(fechaPlamEstadoGlobal == null) {
    return;
}

var flgTipo = 2;
$.ajax({
    type : 'POST',
    url  : 'getCmbMotivo',
    data : { flgTipo : flgTipo }
}).done(function(data) {
    data = JSON.parse(data);

    var cmbMotivo ='<option value="">Seleccionar Motivo</option>';
    data.arrayMotivo.forEach(function(element){
        cmbMotivo+='<option value="'+element.idMotivo+'">'+element.motivoDesc+'</option>';
    });
    $('#cmbMotivoHtml').html(cmbMotivo); 
    modal('modalTrunca'); 
});
}

function cambiarEstadoObra() {
var comentario = $('#motivoTrunco').val();
var idMotivo   = $('#cmbMotivoHtml option:selected').val();

if(idMotivo == '' || idMotivo == null) {
    alert('Ingresar Motivo');
}

$.ajax({
    type : 'POST',
    url  : 'cambiarEstadoObra',
    data : { 'itemPlan'      : itemPlanEstadoGlobal,
             'estadoPlan'    : estadoPlanGlobal,
             dataFechaGlobal : fechaPlamEstadoGlobal,
             comentario      : comentario,
             idMotivo        : idMotivo }
}).done(function(data){
    location.reload();
}); 
}


function regresarTrunco(btn) {
var itemPlan = btn.data('itemplan');
$this=$(this);	
if(confirm("Desea Regresar a Obra el Itemplan : "+itemPlan)==true) {
    $.post("ejecucion?pagina=regresar_truncar&id="+itemPlan,{},function()
    {
        window.location.reload();
    })
}
return false;
}




function verMotivo(btn) {
var itemPlan = btn.data('itemplan');
$.ajax({
    type : 'POST',
    url  : 'https://gicsapps.com:8080/reportes/situacion.php',
    data : { 'itemplan' : itemPlan }
}).done(function(data){
    if(data.respuesta == 1) {
        $('#txtMotivo').val(data.mensaje);
        modal('verMotivo');
    } else {
        alert('No tiene itemplan registrado');
    }
});
}

function verMotivoParalizacion(btn) {
var itemPlan = btn.data('itemplan');
$.ajax({
        type : 'POST',
        url  : 'verMotivoParalizacion',
        data : { 'itemplan' : itemPlan }
}).done(function(data){
    data = JSON.parse(data);
    if(data.error == 0) {
        $('#txtMotivo').val(data.motivo);
        $('#txtUsuario').val(data.usuario);
        $('#txtFecha').val(data.fecha);
        $('#txtOrigen').val(data.origen);
        modal('verMotivo');
    } else {
        mostrarNotificacion('error', 'error', data.msj);
    }
});
}

//$('#modalUbicacion')
// .on('hide.bs.modal', function() {
//        $('#modalFormulario').css('overflow-y','scroll');
// })
//.on('hidden.bs.modal', function(){
//       $('#modalFormulario').css('overflow-y','scroll');
// })
//.on('show.bs.modal', function() {
 //  console.log('show');
//})
//.on('shown.bs.modal', function(){
//  console.log('shown' )
// });

//$('#modalFormulario')
// .on('hide.bs.modal', function() {
//    location.reload();
//});

function searchDireccion() {
address = $("#search").val();

if(address!=''){
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': address}, function(results, status){
		console.log(results);
        if (status == 'OK'){
     //	console.log('searchDireccion:'+JSON.stringify(results));
        // console.log('..-'+JSON.stringify(results[0].geometry.location));
         // Posicionamos el marcador en las coordenadas obtenidas
            marker.setPosition(results[0].geometry.location);
      // Centramos el mapa en las coordenadas obtenidas
            map.setCenter(marker.getPosition());
     /*	var pos = marker.getPosition();
         llenarTextosByCoordenadas(results,pos);*/
            var address=results[0]['formatted_address'];
            openInfoWindowAddress(address,marker);	
        //   geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        //        if (status == google.maps.GeocoderStatus.OK) { 
        //            llenarTextosByCoordenadas(results,marker.getPosition());                   			
        //            //console.log('searchDireccion:'+JSON.stringify(results));
        //     }
        //   });
            
        }
    })    
}    	 
}
function openModalFormObPub(btn) {
var itemplan   = btn.data('item_plan');
var idEstacion = btn.data('id_estacion');
formObraP(null, itemplan, 2, null, null, idEstacion);
$("#btnModalFormObrasPub").click();
//$('#modalFormObrasPub').modal({backdrop: 'static', keyboard: false});;
}

function formObraP(jefatura, itemPlan, flg_from, indicador, descEmpresaColab, idEstacion) {
var app = new Vue({
    el: '#obrap',
    data: {
      jsonFormObrasP : {
        idEstacion       : idEstacion, 
        itemplan         : itemPlan, 
        from             : flg_from,
        ptr              : null, 
        canalizacion_km  : null, 
        camaras_und      : null, 
        c_postes         : null, 
        ma_postes        : null,
        km_ducto         : null,
        km_tritubo       : null,
        km_par_cobre     : null,
        km_cable_coax    : null,
        km_fo            : null,
        observacion      : null,
        fecha_form       : null,
        usuario_registro : null,
        fecha_registro   : null 
      },
    },

    methods:{
        registrarFormObraPub:function() {
        	vue = this;
        	var sms = '';
        	var bolValido = true;
        	
        	var input1File = document.getElementById('filePruebasRefleEE');
            var file1 = input1File.files[0];

            var input2File = document.getElementById('filePerfilEE');
            var file2 = input2File.files[0];
            console.log("vue.observacion " + vue.jsonFormObrasP.observacion);
            if(vue.jsonFormObrasP.observacion == "" || vue.jsonFormObrasP.observacion == null){
            	sms += 'Debe de registrar una OBSERVACION.\n';
        		bolValido = false;
            }
            if(vue.jsonFormObrasP.canalizacion_km == "" || vue.jsonFormObrasP.canalizacion_km == null){
            	sms += 'Debe de registrar el valor de CANALIZACION.\n';
        		bolValido = false;
            }
            if(vue.jsonFormObrasP.camaras_und == "" || vue.jsonFormObrasP.camaras_und == null){
            	sms += 'Debe de registrar el valor de CAMARAS UND.\n';
        		bolValido = false;
            }
            if(vue.jsonFormObrasP.c_postes == "" || vue.jsonFormObrasP.c_postes == null){
            	sms += 'Debe de registrar el valor de C POSTES.\n';
        		bolValido = false;
            }
            if(vue.jsonFormObrasP.ma_postes == "" || vue.jsonFormObrasP.ma_postes == null){
            	sms += 'Debe de registrar el valor de MA POSTES UND.\n';
        		bolValido = false;
            }
            if(vue.jsonFormObrasP.km_ducto == "" || vue.jsonFormObrasP.km_ducto == null){
            	sms += 'Debe de registrar el valor de KM DUCTO UND.\n';
        		bolValido = false;
            }
            if(vue.jsonFormObrasP.km_tritubo == "" || vue.jsonFormObrasP.km_tritubo == null){
            	sms += 'Debe de registrar una evidencia KM TRIBUTO.\n';
        		bolValido = false;
            }
            if(vue.jsonFormObrasP.km_par_cobre == "" || vue.jsonFormObrasP.km_par_cobre == null){
            	sms += 'Debe de registrar una evidencia KM PAR COBRE.\n';
        		bolValido = false;
            }
            if(vue.jsonFormObrasP.km_cable_coax == "" || vue.jsonFormObrasP.km_cable_coax == null){
            	sms += 'Debe de registrar una evidencia KM CABLE COAX.\n';
        		bolValido = false;
            }
            if(vue.jsonFormObrasP.km_fo == "" || vue.jsonFormObrasP.km_fo == null){
            	sms += 'Debe de registrar una evidencia KM FO.\n';
        		bolValido = false;
            }
            
        	if(file1 == null){
        		sms += 'Debe de registrar una evidencia reflectometrica.\n';
        		bolValido = false;
        	}
        	
        	if(file2 == null){
        		sms += 'Debe de registrar una evidencia de perfil.';
        		bolValido = false;
        	}
        	
        	if(bolValido == false){
        		alert(sms);
        		return false;
        	}
            
        	var form_data2 = new FormData();  
            form_data2.append('pruebasReflectonometricas', file1);
            form_data2.append('pruebasPerfil', file2);
            form_data2.append('jsonFormObrasP', JSON.stringify(vue.jsonFormObrasP));
        	console.log('Ejecutando ajax... ' + JSON.stringify(vue.jsonFormObrasP));
            $.ajax({
                type : 'POST',
                url  : 'pqt_registrarFormObraPub',
                async : false,
                data : form_data2,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(data){
            	console.log(data);
                data = JSON.parse(data);
                if(data.error == 0) {
                    mostrarNotificacion('success', data.msj);
                    $("#btnModalFormObrasPub").click();
                    location.reload();
                    //modal('modalFormObrasPub');
                } else {
                    mostrarNotificacion('error', data.msj);
                }
            });
        }
    },
    mounted:function() {

    },
    updated: function () {

    }
  });
}

function getSubProyecto() {
   var idProyecto = $('#proyecto option:selected').val();  

   $.ajax({
        type : 'POST',
        url  : 'getSubProyectoFiltro',
        data : { 'idProyecto' : idProyecto }
   }).done(function(data){
        data = JSON.parse(data);
        $('#subproyecto').html(data.cmbProyecto);
   });
}

$('#modalUbicacion')
.on('hide.bs.modal', function() {
    $('#modalFormularioFuera').css('overflow-y','scroll');
})
.on('hidden.bs.modal', function(){
   $('#modalFormularioFuera').css('overflow-y','scroll');
})
.on('show.bs.modal', function() {
   console.log('show');
})
.on('shown.bs.modal', function(){
  console.log('shown' )
});

var tablaActividad = null;
var idSubProyectoGlobalPtr = null;
var idEstadoPlanGlobalEditPtr = null; 
function openModalPTR(btn) {
    var itemplan           = btn.data('itemplan');
    idSubProyectoGlobalPtr = btn.data('id_subproyecto');
    idEstadoPlanGlobalEditPtr = btn.data('id_estado_plan');
    
    $.ajax({
        type : 'POST',
        url  : 'getPtrByItemplan',
        data : { itemplan      : itemplan,
                 idEstadoPlan  : idEstadoPlanGlobalEditPtr },
    }).done(function(data){
        var data = JSON.parse(data);
        modal('modalConsultaPTR');
        $('#contTablaPTR').html(data.tablaConsultaPtr);
    });
}

// function openModalEditarPTR() {
//     var itemplan = btn.data('itemplan');
//     $.ajax({
//         type : 'POST',
//         url  : 'getPtrByItemplan',
//         data : { itemplan : itemplan },
//     }).done(function(data){
//         var data = JSON.parse(data);
//         modal('modalConsultaPTR');
//         $('#contTablaPTR').html(data.tablaConsultaPtr);
//     });
// }

var itemplanPTRGlobal = null;
var ptrGlobal = null;
function openModalEditarPTR(btn) {
    $('#btnActualizarPtr').prop('disabled', false);
    ptrGlobal         = btn.data('ptr');
    itemplanPTRGlobal = btn.data('itemplan');

    $.ajax({
        type : 'POST',
        url  : 'getPtrEditar',
        data : { itemplan      : itemplanPTRGlobal,
                 ptr           : ptrGlobal,
                 idSubProyecto : idSubProyectoGlobalPtr },
    }).done(function(data){
        var data = JSON.parse(data);
        $('#contEditarPTR').html(data.tablaEditarPtr);
        $('#contTablaActividad').html(data.tablaActividad);
        inicializarTabla('tablaActividad');
          if(idEstadoPlanGlobalEditPtr == 4) {
            $('#contTablaActividad').css('display', 'none');
            $('#tituloActividades').css('display', 'none');
            $('#btnActualizarPtr').css('display', 'none');            
        } else {
            $('#tituloActividades').css('display', 'block');
            $('#contTablaActividad').css('display', 'block');
            $('#btnActualizarPtr').css('display', 'block');     
        }
        // inicializarTabla('tablaPtr');
        modal('modalEditarPTR');
    });
}

function calculoCantidad(btn) {
    var idActividad        = btn.data('id_actividad');
    id_ptrxactividad_zonal = btn.data('id_ptrxactividad_zonal');
    var cont               = btn.data('cont');
    var descripcionAct     = btn.data('descripcion');

    var json = {};
    
    cantidadFinal  = $('#cantidad_'+cont).val();

    costoMA        = $('#costoMA_'+cont).html();
    costoMO        = $('#costoMO_'+cont).html();
    var precio     = $('#precio_'+cont).html();
    var baremo     = $('#baremo_'+cont).html();
    var precioKit  = $('#precioKit_'+cont).html();
    total          = $('#costoTotal_'+cont).html();

    precioKit = precioKit.replace(/\,/g,'');
    precioKit = parseFloat(precioKit);
    
    cantidadFinal  = parseFloat(cantidadFinal);

    costoMA = cantidadFinal * precioKit;
    costoMO = cantidadFinal * precio * baremo;
    
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
    if(screenInit == 1){
    	json.cantidad_eecc_tmp = cantidadFinal;
    }else if(screenInit == 2){
    	json.cantidad_tdp_tmp = cantidadFinal;
    }
    
    if(id_ptrxactividad_zonal == 0) {
        json.id_ptr_x_actividades_x_zonal = '';
        json.cantidad = 0;
        var contador1 = 0;
        arrayDataInsert.forEach(function(data){
            contador++;

            if(data.id_actividad == idActividad) {
                arrayData.splice(contador1-1, 1);
            }
        });
        arrayDataInsert.splice(arrayData.length, 0, json);
    } else {
        json.id_ptr_x_actividades_x_zonal = id_ptrxactividad_zonal;
   
            var contador = 0;
            arrayData.forEach(function(data){
                contador++;

                if(data.id_ptr_x_actividades_x_zonal == id_ptrxactividad_zonal) {
                    arrayData.splice(contador-1, 1);
                }
            });

        arrayData.splice(arrayData.length, 0, json);
    }
}

function actualizarPtr() {
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
        }
    });
}

function openAlert() {
    modal('modalAlerta');
}

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
                    '<td style="background:#E9C603;color:white"><input id="cantidad_'+cont+'" type="number" data-descripcion="'+actividad+'" data-id_actividad="'+idActividad+'" data-cont="'+cont+'" data-id_ptrxactividad_zonal="0" class="form-control" value="0" onkeyup="calculoCantidad($(this));"></td>'+
                    '<td id="costoMO_'+cont+'">0</td>'+
                    '<td id="precioKit_'+cont+'">'+costoKit+'</td>'+
                    '<td id="costoMA_'+cont+'">0</td>'+
                    '<td id="costoTotal_'+cont+'">0</td>'+
                '</tr>';
    var js =$('#tablaPtr tbody').append(html);
} 

function aceptarPorcentaje() {
    location.reload();
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

////////////////////////

Dropzone.autoDiscover = false;
var itemplan = '';
// var x = null;
// var y = null;

$("#dropzone5").dropzone({
url: "insertFoto",
addRemoveLinks: true,
autoProcessQueue: true,
parallelUploads: 200,
maxFilesize: 3,
acceptedFiles: ".jpeg,.jpg,.png,.gif,.zip",
dictResponseError: "Ha ocurrido un error en el server",
success: function(file, response) {
    data = JSON.parse(response);
    if(data.error == 0) {
        var button = '';
        $.ajax({
            type : 'POST',
            url  : 'getArrayFiles',
            data : { 'ubicacion' : ubicacionArchivoFoto }
        }).done(function(data){
            data = JSON.parse(data);
            Object.keys(data.arrayName).forEach(function(key){
                button+='<button class="btn btn-success" data-nombre="'+data.arrayName[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto($(this))">'+data.arrayName[key]+'</button>';
            });
            arrayNameGlobal = data.arrayName;
            $('#buttonFotos').html(button);
        });
        
        // var count = Object.keys(arrayNameGlobal).length;
        // arrayNameGlobal[count+5] = file.name;
        // Object.keys(arrayNameGlobal).forEach(function(key){
        //     button+='<button class="btn btn-success" data-nombre="'+arrayNameGlobal[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto($(this))">'+arrayNameGlobal[key]+'</button>';
        // });
        // console.log(button);
        // $('#buttonFotos').html(button);
    }
},

complete: function(file){
    if(file.status == "success"){
        error=0;
    }
},
removedfile: function(file, serverFileName){
    var name = file.name;
    var element;
    (element = file.previewElement) != null ? 
    element.parentNode.removeChild(file.previewElement) : 
    false;
    toog2=toog2-1;		
},
init: function() {
    this.on("error", function(file, message) {
          alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
          return;
        //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error=1;
          // alert(message);
            this.removeFile(file); 
    });

    var submitButton = document.querySelector("#btnAceptarSubirEvidenciaPostes")
    myDropzone = this; 
    
   var concatEvi = '';
    // You might want to show the submit button only when 
    // files are dropped here:
    this.on("addedfile", function() {		    	
        toog2=toog2+1;	
      // Show submit button here and/or inform user to click it.
    });
    
    this.on('complete', function () {
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
            if(error == 0){
                console.log(this.getUploadingFiles());
                // $('#edi-evidencias').modal('toggle');
            }	            
        }	        
    });
    var x=null;
    var y=null;       
    this.on("queuecomplete", function (file) {
        if(error == 0) {		    			    	
            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		
            if(flgDocGlobal == null || flgDocGlobal == '' || flgDocGlobal == undefined) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        x = position.coords.longitude;
                        y = position.coords.latitude;
                        if(x==null||x=='' && y==null||y=='') {
                            mostrarNotificacion('error', 'Se debe aceptar la geolocalización.');
                            return;
                        } else {
                            $.ajax({
                                type    : 'POST', 
                                'url'   : 'ingresarCoordenada',  
                                'data'  : { x            : x,
                                            y            : y,
                                            idEstacion   : idEstacionGlobalFoto},   
                                'async' : false
                            });
                        }
                    },mostrarErrores);
                } 
            }

            this.removeAllFiles(true); 
            if(flgDocGlobal != 2) {
                $.ajax({
                    type    : 'POST', 
                    'url'   : 'getEstacionesFoto',  
                    'data'  : { itemPlan   : itemPlanGlobalSubirFoto,
                                idEstacion : idEstacionGlobalFoto },   
                    'async' : false
                }).done(function(data){
                        data = JSON.parse(data);
                        if(data.error == 0) {
                            $('#contEstaciones').html(data.estaciones);
                            // location.reload();
                        } else {
                            mostrarNotificacion('error', 'error al enviar la trama', 'error');
                        }
                });
            } else {
                location.reload();
            }
            // location.reload();
            mostrarNotificacion('success','Archivo','Se subi&oacute; la foto correctamente');
            //refreshTablaRuta();
        }
    });		

     this.on("success", function(file, responseText) {//Trae el ID De la imagen insertada
         concatEvi += responseText+'_';		        	
     });
    
  }
});


$("#dropzone6").dropzone({
url: "insertFoto",
addRemoveLinks: true,
autoProcessQueue: true,
parallelUploads: 200,
maxFilesize: 3,
acceptedFiles: ".pdf,.xml,.xlsx,.docx,.zip",
dictResponseError: "Ha ocurrido un error en el server",
success: function(file, response) {
    data = JSON.parse(response);
    if(data.error == 0) {
        var button = '';
        $.ajax({
            type : 'POST',
            url  : 'getArrayFiles',
            data : { 'ubicacion' : ubicacionArchivoFoto }
        }).done(function(data){
            data = JSON.parse(data);
            Object.keys(data.arrayName).forEach(function(key){
                button+='<button class="btn btn-success" data-nombre="'+data.arrayName[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto($(this))">'+data.arrayName[key]+'</button>';
            });
            arrayNameGlobal = data.arrayName;
            $('#buttonFotos').html(button);
        });
    }
},

complete: function(file){
    if(file.status == "success"){
        error=0;
    }
},
removedfile: function(file, serverFileName){
    var name = file.name;
    var element;
    (element = file.previewElement) != null ? 
    element.parentNode.removeChild(file.previewElement) : 
    false;
    toog2=toog2-1;		
},
init: function() {
    this.on("error", function(file, message) {
          alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
          return;
        //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error=1;
          // alert(message);
            this.removeFile(file); 
    });

    var submitButton = document.querySelector("#btnAceptarSubirEvidenciaPruebasRef")
    myDropzone = this; 
        
   var concatEvi = '';
    // You might want to show the submit button only when 
    // files are dropped here:
    this.on("addedfile", function() {		    	
        toog2=toog2+1;	
      // Show submit button here and/or inform user to click it.
    });
    
    this.on('complete', function () {
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
            if(error == 0){
                console.log(this.getUploadingFiles());
                // $('#edi-evidencias').modal('toggle');
            }	            
        }	        
    });
    var x=null;
    var y=null;       
    this.on("queuecomplete", function (file) {
        if(error == 0) {		    			    	
            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		
            if(flgDocGlobal == null || flgDocGlobal == '' || flgDocGlobal == undefined) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        x = position.coords.longitude;
                        y = position.coords.latitude;
                        if(x==null||x=='' && y==null||y=='') {
                            mostrarNotificacion('error', 'Se debe aceptar la geolocalización.');
                            return;
                        } else {
                            console.log("x: "+x);     
                            $.ajax({
                                type    : 'POST', 
                                'url'   : 'ingresarCoordenada',  
                                'data'  : { x            : x,
                                            y            : y,
                                            idEstacion   : idEstacionGlobalFoto},   
                                'async' : false
                            });
                        }
                    },mostrarErrores);
                } 
            }

            this.removeAllFiles(true); 
            if(flgDocGlobal != 2) {
                $.ajax({
                    type    : 'POST', 
                    'url'   : 'getEstacionesFoto',  
                    'data'  : { itemPlan   : itemPlanGlobalSubirFoto,
                                idEstacion : idEstacionGlobalFoto },   
                    'async' : false
                }).done(function(data){
                        data = JSON.parse(data);
                        if(data.error == 0) {
                            // location.reload();
                            $('#contEstaciones').html(data.estaciones);
                        } else {
                            mostrarNotificacion('error', 'error al enviar la trama', 'error');
                        }
                });
            } else {
                location.reload();
            }
            // location.reload();
            mostrarNotificacion('success','Archivo','Se subi&oacute; la foto correctamente');
            //refreshTablaRuta();
        }
    });		

     this.on("success", function(file, responseText) {//Trae el ID De la imagen insertada
         concatEvi += responseText+'_';		        	
     });
    
  }
});



$("#dropzone7").dropzone({
url: "insertFoto",
addRemoveLinks: true,
autoProcessQueue: true,
parallelUploads: 200,
maxFilesize: 3,
acceptedFiles: ".pdf,.xml,.xlsx,.docx,.zip",
dictResponseError: "Ha ocurrido un error en el server",
success: function(file, response) {
    data = JSON.parse(response);
    if(data.error == 0) {
        var button = '';
        $.ajax({
            type : 'POST',
            url  : 'getArrayFiles',
            data : { 'ubicacion' : ubicacionArchivoFoto }
        }).done(function(data){
            data = JSON.parse(data);
            Object.keys(data.arrayName).forEach(function(key){
                button+='<button class="btn btn-success" data-nombre="'+data.arrayName[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto($(this))">'+data.arrayName[key]+'</button>';
            });
            arrayNameGlobal = data.arrayName;
            $('#buttonFotos').html(button);
        });
    }
},

complete: function(file){
    if(file.status == "success"){
        error=0;
    }
},
removedfile: function(file, serverFileName){
    var name = file.name;
    var element;
    (element = file.previewElement) != null ? 
    element.parentNode.removeChild(file.previewElement) : 
    false;
    toog2=toog2-1;		
},
init: function() {
    this.on("error", function(file, message) {
          alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
          return;
        //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error=1;
          // alert(message);
            this.removeFile(file); 
    });

    var submitButton = document.querySelector("#btnAceptarSubirEvidenciaPerfil")
    myDropzone = this; 

   var concatEvi = '';
    // You might want to show the submit button only when 
    // files are dropped here:
    this.on("addedfile", function() {		    	
        toog2=toog2+1;	
      // Show submit button here and/or inform user to click it.
    });
    
    this.on('complete', function () {
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
            if(error == 0){
                console.log(this.getUploadingFiles());
                // $('#edi-evidencias').modal('toggle');
            }	            
        }	        
    });
    var x=null;
    var y=null;       
    this.on("queuecomplete", function (file) {
        if(error == 0) {		    			    	
            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		
            if(flgDocGlobal == null || flgDocGlobal == '' || flgDocGlobal == undefined) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        x = position.coords.longitude;
                        y = position.coords.latitude;
                        if(x==null||x=='' && y==null||y=='') {
                            mostrarNotificacion('error', 'Se debe aceptar la geolocalización.');
                            return;
                        } else {
                            $.ajax({
                                type    : 'POST', 
                                'url'   : 'ingresarCoordenada',  
                                'data'  : { x            : x,
                                            y            : y,
                                            idEstacion   : idEstacionGlobalFoto},   
                                'async' : false
                            });
                        }
                    },mostrarErrores);
                } 
            }

            this.removeAllFiles(true); 
            if(flgDocGlobal != 2) {
                console.log("ENTRO TRAMA FOTO0");
                $.ajax({
                    type    : 'POST', 
                    'url'   : 'getEstacionesFoto',  
                    'data'  : { itemPlan   : itemPlanGlobalSubirFoto,
                                idEstacion : idEstacionGlobalFoto },   
                    'async' : false
                }).done(function(data){
                        data = JSON.parse(data);
                        if(data.error == 0) {
                            $('#contEstaciones').html(data.estaciones);
                            // location.reload();
                        } else {
                            mostrarNotificacion('error', 'error al enviar la trama', 'error');
                        }
                });
            } else {
                location.reload();
            }
            // location.reload();
            mostrarNotificacion('success','Archivo','Se subi&oacute; la foto correctamente');
            //refreshTablaRuta();
        }
    });		

     this.on("success", function(file, responseText) {//Trae el ID De la imagen insertada
         concatEvi += responseText+'_';		        	
     });
    
  }
});

$("#dropzone8").dropzone({
url: "insertFoto",
addRemoveLinks: true,
autoProcessQueue: true,
parallelUploads: 200,
maxFilesize: 3,
acceptedFiles: ".jpeg,.jpg,.png,.gif,.zip",
dictResponseError: "Ha ocurrido un error en el server",
// success: function(file, response) {
//     console.log(#);
// },

complete: function(file){
    if(file.status == "success"){
        error=0;
    }
},
removedfile: function(file, serverFileName){
    var name = file.name;
    var element;
    (element = file.previewElement) != null ? 
    element.parentNode.removeChild(file.previewElement) : 
    false;
    toog2=toog2-1;		
},
init: function() {
    this.on("error", function(file, message) {
          alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
          return;
        //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error=1;
          // alert(message);
            this.removeFile(file); 
    });

    var submitButton = document.querySelector("#btnAceptarSubirEvidenciaPostes")
    myDropzone = this; 
    
   var concatEvi = '';
    // You might want to show the submit button only when 
    // files are dropped here:
    this.on("addedfile", function() {		    	
        toog2=toog2+1;	
      // Show submit button here and/or inform user to click it.
    });
    
    this.on('complete', function () {
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
            if(error == 0){
                console.log(this.getUploadingFiles());
                // $('#edi-evidencias').modal('toggle');
            }	            
        }	        
    });
    var x=null;
    var y=null;       
    this.on("queuecomplete", function (file) {
        if(error == 0) {		    			    	
            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		
            if(flgDocGlobal == null || flgDocGlobal == '' || flgDocGlobal == undefined) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        x = position.coords.longitude;
                        y = position.coords.latitude;
                        if(x==null||x=='' && y==null||y=='') {
                            mostrarNotificacion('error', 'Se debe aceptar la geolocalización.');
                            return;
                        } else {
                            $.ajax({
                                type    : 'POST', 
                                'url'   : 'ingresarCoordenada',  
                                'data'  : { x            : x,
                                            y            : y,
                                            idEstacion   : idEstacionGlobalFoto},   
                                'async' : false
                            });
                        }
                    },mostrarErrores);
                } 
            }

            this.removeAllFiles(true); 
            if(flgDocGlobal != 2) {
                $.ajax({
                    type    : 'POST', 
                    'url'   : 'getEstacionesFoto',  
                    'data'  : { itemPlan   : itemPlanGlobalSubirFoto,
                                idEstacion : idEstacionGlobalFoto },   
                    'async' : false
                }).done(function(data){
                        data = JSON.parse(data);
                        if(data.error == 0) {
                            $('#contEstaciones').html(data.estaciones);
                            // location.reload();
                        } else {
                            mostrarNotificacion('error', 'error al enviar la trama', 'error');
                        }
                });
            } else {
                location.reload();
            }
            // location.reload();
            mostrarNotificacion('success','Archivo','Se subi&oacute; la foto correctamente');
            //refreshTablaRuta();
        }
    });		

     this.on("success", function(file, responseText) {//Trae el ID De la imagen insertada
         concatEvi += responseText+'_';		        	
     });
    
  }
});

$("#dropzone9").dropzone({
url: "insertFoto",
addRemoveLinks: true,
autoProcessQueue: true,
parallelUploads: 200,
maxFilesize: 3,
acceptedFiles: ".pdf,.xml,.xlsx,.docx,.zip",
dictResponseError: "Ha ocurrido un error en el server",
// success: function(file, response) {
//     console.log(#);
// },

complete: function(file){
    if(file.status == "success"){
        error=0;
    }
},
removedfile: function(file, serverFileName){
    var name = file.name;
    var element;
    (element = file.previewElement) != null ? 
    element.parentNode.removeChild(file.previewElement) : 
    false;
    toog2=toog2-1;		
},
init: function() {
    this.on("error", function(file, message) {
          alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
          return;
        //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error=1;
          // alert(message);
            this.removeFile(file); 
    });

    var submitButton = document.querySelector("#btnAceptarSubirEvidenciaPruebasRef")
    myDropzone = this; 
        
   var concatEvi = '';
    // You might want to show the submit button only when 
    // files are dropped here:
    this.on("addedfile", function() {		    	
        toog2=toog2+1;	
      // Show submit button here and/or inform user to click it.
    });
    
    this.on('complete', function () {
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
            if(error == 0){
                console.log(this.getUploadingFiles());
                // $('#edi-evidencias').modal('toggle');
            }	            
        }	        
    });
    var x=null;
    var y=null;       
    this.on("queuecomplete", function (file) {
        if(error == 0) {		    			    	
            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		
            if(flgDocGlobal == null || flgDocGlobal == '' || flgDocGlobal == undefined) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        x = position.coords.longitude;
                        y = position.coords.latitude;
                        if(x==null||x=='' && y==null||y=='') {
                            mostrarNotificacion('error', 'Se debe aceptar la geolocalización.');
                            return;
                        } else {
                            console.log("x: "+x);     
                            $.ajax({
                                type    : 'POST', 
                                'url'   : 'ingresarCoordenada',  
                                'data'  : { x            : x,
                                            y            : y,
                                            idEstacion   : idEstacionGlobalFoto},   
                                'async' : false
                            });
                        }
                    },mostrarErrores);
                } 
            }

            this.removeAllFiles(true); 
            if(flgDocGlobal != 2) {
                $.ajax({
                    type    : 'POST', 
                    'url'   : 'getEstacionesFoto',  
                    'data'  : { itemPlan   : itemPlanGlobalSubirFoto,
                                idEstacion : idEstacionGlobalFoto },   
                    'async' : false
                }).done(function(data){
                        data = JSON.parse(data);
                        if(data.error == 0) {
                            // location.reload();
                            $('#contEstaciones').html(data.estaciones);
                        } else {
                            mostrarNotificacion('error', 'error al enviar la trama', 'error');
                        }
                });
            } else {
                location.reload();
            }
            // location.reload();
            mostrarNotificacion('success','Archivo','Se subi&oacute; la foto correctamente');
            //refreshTablaRuta();
        }
    });		

     this.on("success", function(file, responseText) {//Trae el ID De la imagen insertada
         concatEvi += responseText+'_';		        	
     });
    
  }
});
function openVs(btn) {
    var itemplan = btn.data('item_plan');
    var a = document.createElement("a");
    a.target = "_blank";
    a.href = "infoSisego?item="+itemplan;
    a.click();
}
////////////////////////////////////////////////////////////

/////////////////////////11-09-2018/////////////////////////////////
function openModalEviLic(element){
var itemPlan =  element.data('item_plan');
var jefatura = element.data('jefatura');
$.ajax({
    type: 'POST',
    'url': 'getEviLicencias',
    data: {
        itemPlan: itemPlan,
        jefatura: jefatura
    },
    'async': false

}).done(function (data) {
    data = JSON.parse(data);
    if (data.error == 0) {
        $('#divTablaEntLic').html(data.tablaHtmlEnt);
        modal('modalEntLic');
    } else if (data.error == 1) {
        mostrarNotificacion('error', 'No hay licencias para ese itemPlan');
    }
});
}

function descargarPDFEntLic(element){
    var rutaPDF =  element.data('ruta_pdf');
    if (rutaPDF != null && rutaPDF != undefined) {
        window.open(rutaPDF);
    } else {
        mostrarNotificacion('error', 'No hay evidencia para mostrar');
    }
}

function openModalReembolso(component){
    var idIPEstDetlic = $(component).data('idipestlicdet');

    $.ajax({
                type: 'POST',
                url: 'getComproByIdIPEST',
                data: {
                    idIPEstDetlic: idIPEstDetlic,
                }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                $('#divTablaReembolso').html(data.tablaHTML);
                //initDataTable('#tablaReembolso');
                modal('modalReembolso');
            } else {
                mostrarNotificacion('warning', 'Aviso', 'No hay comprobantes para mostrar!!');
            }
        });
}

function descargarPDFReembolso(element){
    var rutaPDF =  element.data('ruta_pdf');
    if (rutaPDF != null && rutaPDF != undefined) {
        window.open(rutaPDF);
    } else {
        mostrarNotificacion('error', 'No hay evidencia para mostrar');
    }
}

function editarKitMaterialFomFT(btn) {
		var itemPlan = btn.data('itemplan');
		var idSubPro = btn.data('idsubpro');
		var accion	 = btn.data('accion');
		var idFicha	 = btn.data('id_ficha');
		console.log('editarKitMaterialFomFT');
		 $.ajax({
		            type : 'POST',
		            url  : 'getContMateriales',
		            data : {itemplan : itemPlan,
		                    idSubPro : idSubPro,
		                    accion	:	accion}                    
		    }).done(function(data){
		        data = JSON.parse(data);
		        if(data.error == 0){
		            $('#bodyTable').html(data.htmlConTabla);
		            soloDigitos('canclass');
		            $('#txtDireccion').val(data.direccion);
		            $('#txtNumero').val(data.numero);
		            $('#txtPisos').val(data.pisos);
		            $('#selectInstala').val((data.cto != '') ? data.cto : 'SI');
		            $('#selectCamara').val((data.camara != '') ? data.camara : 'SI');
		            $('#selectTipoTrabajo').val((data.tipoPartida != '') ? data.tipoPartida : '1');   
		            $('#txtDepartamentos').val(data.dptos);      
		            $('#textComentario').val('');   
		            $('#btnEditKitMat').attr('data-idSubPro',data.idsubPro);
		            $('#btnEditKitMat').attr('data-itemplan',data.itemplan);     
		            $('#btnEditKitMat').attr('data-accion',data.accion);    
		            $('#btnEditKitMat').attr('data-id_ficha',idFicha);  
		            $('#modalKitMaterial').modal('toggle');
		        }
		    });
		}
/***********NUEVO FORMULARIO SISEGOS********/
function openModalBandejaEjecucionFuera(btn) {
	var vue = this;
	var jefatura   = btn.data('jefatura');
	var itemPlan   = btn.data('item_plan');
	var flg_from   = btn.data('flg_from');

	if(flg_from == 2) {
	    var idEstadoPlan     = btn.data('id_estado_plan');
	    var idEstacion       = btn.data('id_estacion');
	    var indicador        = btn.data('indicador');
	    var descEmpresaColab = btn.data('desc_emp_colab');
	    
	    if(indicador == null && descEmpresaColab == null) {
	        console.log("indicador  o desc. empresa colab Nulo (funcion openModalBandejaEjecucion())");
	        return;
	    }
	    $('#material').css('display', '');
	    formSisego(jefatura, itemPlan, flg_from, indicador, descEmpresaColab, idEstacion, idEstadoPlan);
	} else {
	    vue.isActiveTabParent = false;
	    formSisego(jefatura, itemPlan, flg_from, null, null, null, null);
	}
	/*****************VARIABLES GLOBALES SISEGOS EVIDENCIAS**********************/
	
	ubicacionArchivoFoto = 'uploads/evidencia_fotos/'+itemPlan+'/FO';
	console.log('ubicacionArchivoFoto:'+ubicacionArchivoFoto);
    idEstacionGlobalFoto = idEstacion;
    estacionDesc         = 'FO';
    flgDocGlobal         = '';
    descActividad = '';
    var button = '';

    $.ajax({
        type    : 'POST', 
        'url'   : 'subirFoto',  
        'data'  : { estacionDesc  : estacionDesc,
                    flgArchivo    : flgDocGlobal,
                    descActividad : descActividad,
                    idEstacion    : idEstacionGlobalFoto,
                    itemplan      : itemPlan }
    }).done(function(data){
        data = JSON.parse(data);
        newData = JSON.parse(data.arrayName);

        if(newData != null) {
            Object.keys(newData).forEach(function(key){
                button+='<button class="btn btn-success" data-nombre="'+newData[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto2($(this))">'+newData[key].replace("PR_", "").replace("PP_", "")+'</button>';
            });
        }

        arrayNameGlobal = newData;
        $('#buttonFotosFuera').html(button);
        itemPlanGlobalSubirFoto = itemPlan;
       
        // modal('modalSubirFoto');        
    	});

	/***************************************/
    $("#btnModalFormularioFuera").click();
	//$('#modalFormularioFuera').modal({backdrop: 'static', keyboard: false});
}
function openModalDeleteArchFoto2(btn) {
	console.log(ubicacionArchivoFoto);
	nomArchivoFotoGlobal = btn.data('nombre');
	keyGlobal            = btn.data('key_json');
	$("#btnModalAlertaDelete").click();
	//modal('modalAlertaDelete');
	}

function deleteArchivoFoto2() {

	$.ajax({
	    type : 'POST',
	    url  : 'deleteArchivoFoto',
	    data : { 'nombreArchivoFoto' : nomArchivoFotoGlobal,
	             'ubicacion'         : ubicacionArchivoFoto }
	}).done(function(data){
	    data = JSON.parse(data);
	    if(data.error == 0) {
	        var button = '';
	        delete arrayNameGlobal[keyGlobal];
	    
	        Object.keys(arrayNameGlobal).forEach(function(key){
	            button+='<button class="btn btn-success" data-nombre="'+arrayNameGlobal[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto($(this))">'+arrayNameGlobal[key].replace("PR_", "").replace("PP_", "")+'</button>';
	        });
	        $('#buttonFotosFuera').html(button);
	        $('#buttonEvidencia').html(button);
	        $("#btnModalAlertaDelete").click();
	        //modal('modalAlertaDelete');
	        mostrarNotificacion('success', 'correcto', 'Se elimino correctamente');
	    } else {
	        mostrarNotificacion('error','Error', data.msj);
	    }
	});
}

function openModalSubirFoto(descripcion, idEstacion, flg, descActividad, itemPlan, idProyecto, ubicacion, idSubProyecto) {
    ubicacionArchivoFoto = ubicacion;
    //modal('modalAlertaInfo');
    idEstacionGlobalFoto = idEstacion;
    estacionDesc         = descripcion;
    flgDocGlobal         = flg;
    var button = '';
    // $.fancybox({ 
    //     height:"100%",type:"iframe",width:"100%"
    // });
    // return;
    if(flg == 2) {
        var descActividad    = replaceAll(descActividad, ' ', '_');    
    }
    $.ajax({
        type    : 'POST', 
        'url'   : 'subirFoto',  
        'data'  : { estacionDesc  : estacionDesc,
                    flgArchivo    : flgDocGlobal,
                    descActividad : descActividad,
                    idEstacion    : idEstacionGlobalFoto,
                    itemplan      : itemPlan }
    }).done(function(data){
        data = JSON.parse(data);
        newData = JSON.parse(data.arrayName);

        if(newData != null) {
            Object.keys(newData).forEach(function(key){
                button+='<button class="btn btn-success" data-nombre="'+newData[key]+'" data-key_json="'+key+'" onclick="openModalDeleteArchFoto($(this))">'+newData[key]+'</button>';
            });
        }

        arrayNameGlobal = newData;
        $('#buttonFotos').html(button);
        $('#buttonEvidencia').html(button);
        itemPlanGlobalSubirFoto = itemPlan;
        // if(flgDocGlobal == 1) {
        //     $('#tituloModal').text('SUBIR ARCHIVOS(OBLIGATORIO PARA LLEGAR AL 100%)');  
        // } else if(flgDocGlobal == 2) {
        //     $('#tituloModal').text('SUBIR FOTO DE ACTIVIDAD'); 
        // } else {
        //     $('#tituloModal').text('SUBIR FOTO DE EVIDENCIA(OBLIGATORIO PARA LLEGAR AL 100%)');              
        // }
        // if(estacionDesc == 'FO') {
        //    if(idProyecto == 3 || idSubProyecto == 6 || idSubProyecto == 101 || idSubProyecto == 156
        //        || idSubProyecto == 206 || idSubProyecto == 79 || idSubProyecto == 80 || idSubProyecto == 207
        //        ||  idSubProyecto == 219 || idSubProyecto == 220 || idSubProyecto == 221 || idSubProyecto == 171) {
        //         $('#modalSubirEvidencia').modal();  
        //     } else {
        //         $('#modalSubirFoto').modal({backdrop: 'static', keyboard: false}); 
        //     }          
        // } else if(idProyecto == 3 && estacionDesc == 'UM'){
        //     $('#modalSubirEvidenciaUM').modal();     
        // }else {
        //     $('#modalSubirFoto').modal({backdrop: 'static', keyboard: false});        
        // }
        modal('modalSubirEvidenciaSinSiom');
    });
}


function ingresarPorcentajeLiqui(btn) {
    itemplan   = btn.data('itemplan');
    idEstacion = btn.data('id_estacion');
    porcentaje = $('#cmbPorcentaje_'+idEstacion+' option:selected').val();
    if(itemplan == null || itemplan == '') {
        return;
    }

    if(idEstacion == null || idEstacion == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'ingresarPorcentajeLiqui',
        data : { itemplan   : itemplan,
                 idEstacion : idEstacion,
                 porcentaje : porcentaje }
    }).done(function(data) {
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#cont_porcentaje_estacion_'+idEstacion).html('<span style="font-size:18px" class="label label-primary capitalize-font inline-block ml-10">'+porcentaje+'%</span>');
            $('#contTabla').html(data.tablaLiquidacion);
        } else {
            mostrarNotificacion('error', data.msj, 'verificar');
        }
    });
}

$("#dropzone4").dropzone({
    url: "ingresarEvidenciaLiqui",
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 200,
    maxFilesize: 3,
    acceptedFiles: ".pdf,.xml,.xlsx,.docx,.zip",
    dictResponseError: "Ha ocurrido un error en el server",
    success: function(file, response) {
        // console.log(file.name);
        data = JSON.parse(response);

        if(data.error == 0) {
            $('#contMsjEvidencia_'+idEstacionGlobalFoto).css('display', 'none');
            mostrarNotificacion('success', 'Se cargo la evidencia correctamente.', 'Correcto');
            modal('modalSubirFoto');
        } else {
            mostrarNotificacion('error', data.msj, 'Verificar');
        }
    },

    complete: function(file){
        if(file.status == "success"){
            error=0;
        }
    },
    removedfile: function(file, serverFileName){
        var name = file.name;
        var element;
        (element = file.previewElement) != null ? 
        element.parentNode.removeChild(file.previewElement) : 
        false;
        toog2=toog2-1;		
    },
    init: function() {
        this.on("error", function(file, message) {
            alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
            return;
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
                error=1;
            // alert(message);
                this.removeFile(file); 
        });

        var submitButton = document.querySelector("#btnAceptarSubirFoto")
        var myDropzone = this; 
            
        submitButton.addEventListener("click", function () {
            myDropzone.processQueue();
        });

    var concatEvi = '';
        // You might want to show the submit button only when 
        // files are dropped here:
        this.on("addedfile", function() {		    	
            toog2=toog2+1;	
        // Show submit button here and/or inform user to click it.
        });
        
        this.on('complete', function () {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                if (error == 0) {
                    console.log(this.getUploadingFiles());
                }
            }
        });

        this.on("queuecomplete", function (file) {
            var drop = this;

        });


        this.on("success", function(file, responseText) {//Trae el ID De la imagen insertada
            concatEvi += responseText+'_';		        	
        });
        
        this.on('sending', function (file, xhr, formData) {
            // var vr = $.trim($('#contVR').val());
            formData.append('itemplan'  , itemPlanGlobalSubirFoto);
            formData.append('idEstacion', idEstacionGlobalFoto);
            // formData.append('idEmpresaColab', idEmpresaColabGlob);
            // formData.append('vr', vr);
        });
    }
});


//SUBO EVIDENCIA CUANDO NO TIENE SIOM
$('#formRegistrarEvidenciasSinSiom')
  .bootstrapValidator({
  container: '#mensajeFormE',
  feedbackIcons: {
      valid: 'glyphicon glyphicon-ok',
      invalid: 'glyphicon glyphicon-remove',
      validating: 'glyphicon glyphicon-refresh'
  },
  excluded: ':disabled',
  fields: {
      filePerfilESinSiomSinSiom: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe subir Docuemnto PRUEBAS REFLECTOMETRICAS.</p>'
              }
          }
      },
      filePerfilESinSiom: {
          validators: {
              notEmpty: {
                  message: '<p style="color:red">(*) Debe subir Docuemnto PERFIL.</p>'
              }
          }
      }
  }
  }).on('success.form.bv', function(e) {
  e.preventDefault();


    if(itemPlanGlobalSubirFoto == null || itemPlanGlobalSubirFoto == '' || idEstacionGlobalFoto == null || idEstacionGlobalFoto == '') {
        return;
    }

  var $form    = $(e.target),
      formData = new FormData(),
      params   = $form.serializeArray(),
      bv       = $form.data('bootstrapValidator');
      
      $.each(params, function(i, val) {
          formData.append(val.name, val.value);
      });

      formData.append('itemplan', itemPlanGlobalSubirFoto);

      var idestacion	=	$('#btnRegEvidencias').attr('data-id_estacion');
      formData.append('idEstacion', idEstacionGlobalFoto);  

      var input1File = document.getElementById('filePruebasRefleESinSiom');
      var file1 = input1File.files[0];
      formData.append('filePruebas', file1);

      var input2File = document.getElementById('filePerfilESinSiom');
      var file2 = input2File.files[0];
      formData.append('filePerfil', file2);
      
      swal({
          title: 'Est&aacute seguro registrar las evidencias?',
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
              url: "ingresarEvidenciaLiqui",
              cache: false,
              contentType: false,
              processData: false,
              type: 'POST'
            })
            .done(function(data) {
               var data	=	JSON.parse(data);
               if(data.error == 0){
                   $("#btnModalSubirEvidencias").click();
                   swal({
                	   title: 'Se registro correctamente!',
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
               }else if(data.error == 1){     				
                   mostrarNotificacion('error','Error, refresque la pagina y vuelva a intentarlo!');
               }
            });
      }, function(dismiss) {
          // dismiss can be "cancel" | "close" | "outside"
			$('#formRegistrarEvidencias').bootstrapValidator('resetForm', true); 
      });
  });

function liquidarSinSiom(btn) {
    var itemplan = btn.data('item_plan');

    if(itemplan == null || itemplan == '') {
        return;
    }

    swal({
        title: 'Esta seguro de liquidara la obra?',
        text: 'Asegurese de que la informacion llenada sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, guardar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function () {
        $.ajax({
            type : 'POST',
            url  : 'liquidarSinSiom',
            data : { itemplan : itemplan } 
        }).done(function(data){
            data = JSON.parse(data);

            if(data.error == 0) {
                mostrarNotificacion('success', 'se liquido correctamente', 'correcto');
                $('#contTabla').html(data.tablaLiquidacion);
            } else {
                mostrarNotificacion('error', data.msj, 'verificar');
            }
        });
    }, function (dismiss) {
        console.log('cancelado...');
    });
}


/////////////////////////////FERNANDO DE MIERDA

var arrayCount = [0,1,2];
		var arrayGlobMats = [];
		var itemGLOB = null;

		function abrirModalPOs(btn){
            var itemplan = btn.data('itemplan');
            var idEstacion = btn.data('id_estacion');
		$('#formRegUpdate').trigger("reset");

		var itemplan = itemplan;
		itemGLOB = itemplan;
		var idEstacion = idEstacion;

		$.ajax({
			type: 'POST',
			url: 'getPOsByEstacionPreliqui',
			data: {
				itemplan : itemplan,
				idEstacion: idEstacion
			},
			beforeSend: () => {
				$('#loadGif1').css("display", "block");
				$('#btnFO').attr("disabled", true);
			}
		}).done(function (data) {
			data = JSON.parse(data);
			if (data.error == 0){
				$("#contTabsPO").html(data.htmlCabeTabs);
				$("#contBodyTabsPO").html(data.htmlBodyTabs);
				arrayGlobMats = data.arrayGlobMat;
				arrayCount = data.arrayCount;
				console.log(arrayGlobMats);
				arrayCount.forEach(function(row) {
					initDataTable('#table_po_' + row);
				});
				// initDataTable('#data-table');

			}else {
				mostrarNotificacion('error', 'Aviso', data.msj);
			}
		}).always(() => {
			modal('modalPOs');
			$('#loadGif1').css("display", "none");
			$('#btnFO').removeAttr("disabled");
			$('.numerico').numeric({
				negative: false,
				altDecimal: ',', 
				decimal: '.'
			});
		});

		}

		var posicionGlob = 0;

		function onTab(component){
			posicionGlob = $(component).data('count');
			console.log(posicionGlob);
		}

		function changeMontoMaterial(component){

			var idMaterial = $(component).data('idmaterial');
			var posGlobal = $(component).data('contador');
			var pos = $(component).data('posicion');
			var cantidad_final = $(component).val();

			if(cantidad_final == null || cantidad_final == undefined || cantidad_final == '' ){
				cantidad_final = 0;
				$(component).val(0);
			}

			cantidad_final = parseFloat(cantidad_final);
			arrayGlobMats[posGlobal][pos]['cantidad_final'] = cantidad_final;
			console.log(arrayGlobMats);

		}

		function updateDetallePO(){

			var codigoPO = $('#tabPO'+posicionGlob).data('codigopo');

			if(arrayGlobMats[posicionGlob].length == 0){
				mostrarNotificacion('warning','Aviso','Debe ingresar alguna cantidad para guardar!!');
				return;
			}

			console.log('itemplan', itemGLOB);
			console.log('codigo_po', codigoPO);
			console.log('arrayMats', arrayGlobMats[posicionGlob]);
			console.log('posicion', posicionGlob);

			swal({
				title: 'Esta seguro de realizar esta operacion??',
				text: 'Asegurese de validar la informacion',
				type: 'warning',
				buttonsStyling: false,
				confirmButtonClass: 'btn btn-success',
				confirmButtonText: 'OK!'

			}).then(function () {

				$.ajax({
					type: 'POST',
					url: 'pqt_updateDetPO',
					data: {
						itemplan: itemGLOB,
						codigo_po : codigoPO,
						arrayMat: arrayGlobMats[posicionGlob],
						posicion : posicionGlob
					},
					beforeSend: () => {
						$('#btnUpdDet').attr("disabled",true);
					}
				}).done(function (data) {
					data = JSON.parse(data);
					if (data.error == 0){
						arrayGlobMats[posicionGlob] = data.arrayMat;
						$('#contTablaPO'+posicionGlob).html(data.tablaVR);
						initDataTable('#table_po_' + posicionGlob);
						mostrarNotificacion('success', 'Aviso', data.msj);
					} else {
						mostrarNotificacion('error', 'Aviso', data.msj);
					}
				}).always(() => {
					$('#btnUpdDet').removeAttr("disabled");
					$('.numerico').numeric({
						negative: false,
						altDecimal: ',', 
						decimal: '.'
					});
				});

			}).catch(swal.noop);

		}


/////////////////////// FIN FERNANDO DE MIERDA