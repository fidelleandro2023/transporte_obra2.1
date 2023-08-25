var marker = null;
var map = null;
var center = null;
var marcadores_cto_gbl   = null;
var info_markers_cto_gbl = null;
var icon_url_cto_gbl     = null;

var marcadores_mdf_gbl   = null;
var info_markers_mdf_gbl = null;
var icon_url_mdf_gbl     = null;

var marcadores_ebc_gbl   = null;
var info_markers_ebc_gbl = null;

var marcadores = null;
var infoWindowMarcadores = null; 

var marcadores_reservas_gbl = null;

var marcadores_cto_edif_gbl   = null;
var info_markers_cto_edif_gbl = null;
function llenarMarcadores() {
	infoWindowMarcadores = new google.maps.InfoWindow(); 
	// for( i = 0; i < marcadores_cto_gbl.length; i++ ) {
		// var position = new google.maps.LatLng(marcadores_cto_gbl[i].latitud, marcadores_cto_gbl[i].longitud);

		// marcadores = new google.maps.Marker({
			// icon: marcadores_cto_gbl[i].icon_cto,/* icon_url_cto_gbl, */
			// position: position,
			// map: map,
			// title: marcadores_cto_gbl[i].codigo
		// });
		 // // Add info window to marker    
		// google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
			
			// return function() {
				// infoWindowMarcadores.setContent(info_markers_cto_gbl[i][0]);
				// infoWindowMarcadores.open(map, marcadores);
			// }
		// })(marcadores, i));
				
    // }
    
    // for( i = 0; i < marcadores_reservas_gbl.length; i++ ) {
		// var position = new google.maps.LatLng(marcadores_reservas_gbl[i].latitud, marcadores_reservas_gbl[i].longitud);

		// marcadores = new google.maps.Marker({
			// icon: marcadores_reservas_gbl[i].icon_reserva,/* icon_url_cto_gbl, */
			// position: position,
			// map: map,
			// title: marcadores_reservas_gbl[i].codigo
		// });
		 // // Add info window to marker    
		// google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
			
			// return function() {
				// infoWindowMarcadores.setContent(info_markers_reserva_gbl[i][0]);
				// infoWindowMarcadores.open(map, marcadores);
			// }
		// })(marcadores, i));
				
	// }
	
	for( i = 0; i < marcadores_mdf_gbl.length; i++ ) {
		var position = new google.maps.LatLng(marcadores_mdf_gbl[i][2], marcadores_mdf_gbl[i][1]);

		marcadores = new google.maps.Marker({
			icon: icon_url_mdf_gbl,
			position: position,
			map: map,
			title: marcadores_mdf_gbl[i][0]
		});
		 // Add info window to marker    
		google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
			
			return function() {
				infoWindowMarcadores.setContent(info_markers_mdf_gbl[i][0]);
				infoWindowMarcadores.open(map, marcadores);
			}
		})(marcadores, i));
				
    }
    
    // for( i = 0; i < marcadores_ebc_gbl.length; i++ ) {
		// var position = new google.maps.LatLng(marcadores_ebc_gbl[i].latitud, marcadores_ebc_gbl[i].longitud);

		// marcadores = new google.maps.Marker({
			// icon: marcadores_ebc_gbl[i].icon_reserva,/* icon_url_cto_gbl, */
			// position: position,
			// map: map,
			// title: marcadores_ebc_gbl[i].codigo
		// });
		 // // Add info window to marker    
		// google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
			
			// return function() {
				// infoWindowMarcadores.setContent(info_markers_ebc_gbl[i][0]);
				// infoWindowMarcadores.open(map, marcadores);
			// }
		// })(marcadores, i));
				
	// }
	
	for( i = 0; i < marcadores_cto_edif_gbl.length; i++ ) {
		var position = new google.maps.LatLng(marcadores_cto_edif_gbl[i].latitud, marcadores_cto_edif_gbl[i].longitud);

		marcadores = new google.maps.Marker({
			icon: marcadores_cto_edif_gbl[i].icon_cto,/* icon_url_cto_gbl, */
			position: position,
			map: map,
			title: marcadores_cto_edif_gbl[i].codigo
		});
		 // Add info window to marker    
		google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
			
			return function() {
				infoWindowMarcadores.setContent(info_markers_cto_edif[i][0]);
				infoWindowMarcadores.open(map, marcadores);
			}
		})(marcadores, i));
				
	}
}

function init(){
	// marcadores_cto_gbl   = marcadores_cto;
	// info_markers_cto_gbl = info_markers_cto;
	// icon_url_cto_gbl     = global_icon_url_cto;
    
    // marcadores_reservas_gbl = marcadores_reserva;
	// info_markers_reserva_gbl = info_markers_reserva;

    
	marcadores_mdf_gbl   = marcadores_mdf;
	info_markers_mdf_gbl = info_markers_mdf;
    icon_url_mdf_gbl     = global_icon_url_mdf;
    
    // marcadores_ebc_gbl   = marcadores_ebc;
    // info_markers_ebc_gbl = info_markers_ebc;
	
	marcadores_cto_edif_gbl   = marcadores_cto_edif;
	info_markers_cto_edif_gbl = info_markers_cto_edif;
    var mapdivMap = document.getElementById("divMapCoordenadas");
    
    center = new google.maps.LatLng(-12.0431800, -77.0282400);
    var myOptions = {
        zoom: 5,
        center: center,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("divMapCoordenadas"), myOptions);            
    infoWindow = new google.maps.InfoWindow();
	geoposicionar(-12.0431800, -77.0282400);
	llenarMarcadores(); 
    //construye el buscador en el mapa y muestra el lugar
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                  
	map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
        });
        searchBox.addListener('places_changed', function() {
			var address = document.getElementById('pac-input').value;
			var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == 'OK') {
                    console.log('..-' + JSON.stringify(results[0].geometry.location));
                    // Posicionamos el marcador en las coordenadas obtenidas

                    // Centramos el mapa en las coordenadas obtenidas
                    // map.setCenter(marker.getPosition());
                    map.setCenter(results[0].geometry.location);
                    map.setZoom(16);


                    marker.setPosition(results[0].geometry.location);

                    var address = results[0]['formatted_address'];
                    openInfoWindowAddress(address, marker);

                    geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            llenarTextosByCoordenadas(results, marker.getPosition());
                            //console.log('searchDireccion:'+JSON.stringify(results));
                        }
                    });

                }
            })
        });
        //////////////////////////////////////////	
}

function geoposicionar(lati, longi){
    if(navigator.geolocation){
        console.log("obteniendo posición...");
        //navigator.geolocation.getCurrentPosition(centrarMapa);
		centrarMapa(lati, longi);
    }else{
        console.log('Tu navegador no soporta geolocalización');
    }   
}

function centrarMapa(lati, longi){
    map.setZoom(15);
    map.setCenter(new google.maps.LatLng(lati,longi));
        marker = new google.maps.Marker({
        position: new google.maps.LatLng(lati,longi),
        map: map,
        title:"Tu posición",
        draggable: true,
        animation: google.maps.Animation.DROP
    });
    
    var geocoder = new google.maps.Geocoder();
    
    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var pos = marker.getPosition();
            llenarTextosByCoordenadas(results,pos);
            var address=results[0]['formatted_address'];
            openInfoWindowAddress(address,marker);         							
    }
    });	

    

    google.maps.event.addListener(marker, 'dragend', function(){
        var pos = marker.getPosition();
        geocoder.geocode({'latLng': pos}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {                			
                    llenarTextosByCoordenadas(results,pos)
                    var address=results[0]['formatted_address'];
                    openInfoWindowAddress(address,marker);				
            }
        });		
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

            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {                    			
                        var pos = marker.getPosition();
                        llenarTextosByCoordenadas(results,pos)
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
                            llenarTextosByCoordenadas(results,pos)
                            var address=results[0]['formatted_address'];
                                openInfoWindowAddress(address,marker);				
                    }
                });
                    map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
            });
    }); 
}

var nodoGlobal       = null;
var idCentralGlobal  = null;
var latitudNodoGlb   = null;
var longitudNodoGlb  = null;
function llenarTextosByCoordenadas(results,pos){console.log("ENTRO");
    $.ajax({
        type : 'POST',
        url  : 'getDataInfoCotiCentral',
        data : { latitud  : pos.lat(),
                 longitud : pos.lng() }         
    }).done(function(data){
        data = JSON.parse(data);

        try{
            $('#inputDireccion1').val(results[0]['formatted_address']);
            $('#inputUbicacion2').val(results[0]['formatted_address']);
        }catch(err){
            $('#inputDireccion1').val('');
            $('#inputUbicacion2').val('');
        }
        $('#inputCoordX1').val(pos.lat());
        $('#inputCoordY1').val(pos.lng());
        $('#inputNombrePlan').val(data.dataCentral[0].tipoCentralDesc);
        $('#inputNodoKmz').val(data.dataCentral[0].codigo);
        nodoGlobal = data.dataCentral[0].codigo;
        idCentralGlobal = data.dataCentral[0].idCentral;
        
        latitudNodoGlb  = data.dataCentral[0].latitud;
        longitudNodoGlb = data.dataCentral[0].longitud;

        $('#selectCentral').val(data.dataCentral[0].idCentral).trigger('change');

    });
}

function openInfoWindowAddress(Addres,marker) {
    infoWindow.setContent([
    Addres
    ].join(''));
    infoWindow.open(map, marker);
}

var flg_log_robot_glb = null;
function getLogicaRobot() {
	if( $('#checkLogRobot').prop('checked') ) {
		flg_log_robot_glb = 1;
	} else {
		flg_log_robot_glb = 0;
	}
}

var mtcCotizacionGbl    = null;
var nodoCotizacionGbl   = null;
var requiereSeiaCotiGbl = null;
var facRedCotizacionGbl = null;
var ctoCotizacionGbl    = null;
var metTendCotiGbl      = null;
var costoMoGbl          = null;
var costoMatGbl         = null;
var costoDisenoGbl      = null;
var costoTotalGbl       = null;
var tipoDisenoGbl       = null;
var duracionCotiGbl     = null;
var metTendSubGbl       = null;
function getDataSimulacion() {
    latitud  = $('#inputCoordX1').val();
    longitud = $('#inputCoordY1').val();
    clasificacion = $('#clasificacion option:selected').val();
    tipo_cliente  = $('#tipo_cliente option:selected').val();
	idSubProyecto = $('#cmbSubSimu option:selected').val();
	
	if(clasificacion == null || clasificacion == '' || tipo_cliente == '' || tipo_cliente == null) {
		mostrarNotificacion('error', 'Debe seleccionar una clasificaci&oacute;n y tipo cliente');
		return;
	}
	
    if(latitud == null || latitud == '' || longitud == null || longitud == '' || tipo_cliente == null || 
       tipo_cliente == '' || clasificacion == '' || clasificacion == null || idCentralGlobal == null || idCentralGlobal == '' ||
       longitudNodoGlb == null || longitudNodoGlb == '' || latitudNodoGlb == null ||latitudNodoGlb == '') {
        return;
    }
	
	if(idSubProyecto == null || idSubProyecto == '') {
		mostrarNotificacion('error', 'Debe seleccionar un subproyecto');
		return;
	}
	
    var flg_kmz_arque = $('#kmz_arque option:selected').val();
    $.ajax({
        type : 'POST',
        url  : 'getDataSimulacion',
        data : { 
                    latitud       : latitud,
                    longitud      : longitud,
                    tipo_cliente  : tipo_cliente,
                    clasificacion : clasificacion,
                    idCentral     : idCentralGlobal,
                    latitudNodo   : latitudNodoGlb,
                    longitudNodo  : longitudNodoGlb,
                    flg_kmz_arque : flg_kmz_arque,
					idSubProyecto : idSubProyecto,
					flg_log_robot : flg_log_robot_glb
                }
    }).done(function(data){
        data = JSON.parse(data);
		
		if(data.error == 0) {
			if(data.fac_red == '' || data.fac_red == null) {
				$('#fac_red').val(nodoGlobal);
			} else{
				$('#fac_red').val(data.fac_red);
			}
			
		var pos_A = new google.maps.LatLng(latitud,longitud);
		var pos_B = new google.maps.LatLng(data.lat_ctoResEbcMdf,data.lon_ctoResEbcMdf);		
		var marcadorA = new google.maps.Marker({
				position: pos_A,
				map: map,
				title: 'Cliente',
				draggable: false
			});
 		 
		//array de puntos en nuestra línea
		var puntos_linea = [
			pos_A,
			pos_B
		];
		 
		//definimos la línea
		var linea = new google.maps.Polyline({
		  title : data.distancia,
		  path: puntos_linea,
		  geodesic: true,
		  strokeColor: '#FF0000',
		  strokeOpacity: 1.0,
		  strokeWeight: 2
		 });
		 
		//dibujamos la línea sobre el mapa
		linea.setMap(map);
		
		// google.maps.event.addListener(linea, 'click', function(e) {
			// labelMarker.setPosition(e.latLng)
			// myLabel.bindTo('position', labelMarker, 'position');
			// myLabel.set('text.latLng.toString());
			// myLabel.setMap(map);
		// });
			
			$('#nodoPrincipal').val(nodoGlobal);
			$('#distancia').val(data.distancia);
			$('#inputCto').val(data.cant_cto);
			$('#metTendAereo').val(Math.round(data.metrosTendidos));
			$('#costoMo').val(data.arrayCostos.mo_total);
			$('#costoMat').val(data.arrayCostos.mat_total);
			$('#costoDiseno').val(data.arrayCostos.diseno_total);
			$('#total').val(data.arrayCostos.total);
			$('#tipo_diseno').val(data.tipo_diseno);
			$('#duracion').val(data.duracion);
			$('#seia').val(data.seia);
			$('#mtc').val(data.mtc);
			$('#inc').val(data.inc);//console.log(data.arrayCostos.inc_total);
			$('#costoInc').val(data.arrayCostos.inc_total);
			$('#costoEia').val(data.arrayCostos.eia_total);
			$('#tipo').val(data.tipo);
			
			$('#metro_oc_input').val(data.arrayCostos.metro_oc);
			$('#crxa_input').val(data.arrayCostos.crxa);
			$('#crxc_input').val(data.arrayCostos.crxc);
			$('#postes_input').val(data.arrayCostos.postes);
			$('#eeccsimu').val(data.eecc);
			$('#h_disponible').val(data.hilo_disp);
			$('#costo_mo_edif').val(data.costo_mo_edif);
			$('#costo_mat_edif').val(data.costo_mat_edif);
			$('#costo_oc_edif').val(data.costo_oc_edif);
			//DATOS COTIZACION
			$('#nodoPrincipal2').val(nodoCotizacionGbl);
			$('#fac_red2').val(facRedCotizacionGbl);
			$('#inputCto2').val(ctoCotizacionGbl);
			$('#metTendAereo2').val(metTendCotiGbl);
			
			$('#costoMo2').val(costoMoGbl);
			$('#costoMat2').val(costoMatGbl);
			$('#costoDiseno2').val(costoDisenoGbl);
			
			$('#total2').val(costoTotalGbl);
			$('#tipo_diseno2').val(tipoDisenoGbl);
			$('#duracion2').val(duracionCotiGbl);
			$('#seia2').val(requiereSeiaCotiGbl);
			$('#mtc2').val(mtcCotizacionGbl);
			$('#metTendSub2').val(metTendSubGbl);
			
			$('#subtituloDistancia').html('<a>Distancia Lineal : '+data.distancia+'</a>');
			
			console.log(data.costo_mat_envio_sisego+"+"+data.costo_mo_envio_sisego);
			console.log(Number(data.costo_mat_envio_sisego)+Number(data.costo_mo_envio_sisego));
			modal('modalSimulacion');
		} else {
			mostrarNotificacion('error', data.msj);
		}
    });
}

function getDataByCodigoCotizacion() {
    var codigoCotizacion = $('#inputCotizacion').val();

    if(codigoCotizacion == null || codigoCotizacion == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getDataByCodigoCotizacion',
        data : { codigoCotizacion : codigoCotizacion }
    }).done(function(data){
        data = JSON.parse(data);
		console.log(data.data_array);
        $('#inputCoordX1').val(data.data_array.latitud);
        $('#inputCoordY1').val(data.data_array.longitud);
        var pos = { coords : { 
                                latitude :   {

                                             },
                                longitude :  {

                                             }
                             }  
                  };
        pos.coords.latitude = data.data_array.latitud;
        pos.coords.longitude = data.data_array.longitud;
		
        $('#clasificacion').val(data.data_array.clasificacion.toUpperCase()).trigger('change');
        $('#tipo_cliente').val(data.data_array.tipo_cliente).trigger('change');
        $('#inputNombrePlan').val(data.data_array.tipoCentralDesc);
        $('#inputNodo').val(data.data_array.codigo);
        $('#inputTipo').val(data.data_array.flg_principal);
		
		if(Object.keys(data.data_array).length > 0) { 
			facRedCotizacionGbl = data.data_array.facilidades_de_red;
			requiereSeiaCotiGbl = data.data_array.requiere_seia;
			mtcCotizacionGbl    = data.data_array.requiere_aprob_mml_mtc;
			ctoCotizacionGbl    = data.data_array.cant_cto;
			metTendCotiGbl      = data.data_array.metro_tendido_aereo;
			costoMoGbl          = data.data_array.costo_mano_obra;
			costoMatGbl         = data.data_array.costo_materiales;
			costoDisenoGbl      = data.data_array.costo_diseno;
			costoTotalGbl       = data.data_array.costo_total;
			tipoDisenoGbl       = data.data_array.tipo_diseno_desc;
			duracionCotiGbl     = data.data_array.duracion;
			metTendSubGbl       = data.data_array.metro_tendido_subterraneo;
			
			if(data.data_array.flg_principal == 'PRINCIPAL') {
				nodoCotizacionGbl = data.data_array.nodo_principal;
			} else {
				nodoCotizacionGbl = data.data_array.nodo_respaldo;
			}
		}

		
        centrarMapa(pos.coords.latitude, pos.coords.longitude);
    });
}

function getFiltrarCostoPaquetizadoSimu() {
	console.log("ENTOASDASDADS");
	var idSubProyecto  = $('#cmbSubCosto  option:selected').val();
	var idEmpresaColab = $('#cmbEmpCosto  option:selected').val();
	var idEstacion     = $('#cmbEstaCosto option:selected').val();
	var jefatura       = $('#cmbJefatura  option:selected').val();
	
	$.ajax({
		type : 'POST',
		url  : 'getFiltrarCostoPaquetizadoSimu',
		data : { 
				 idSubProyecto  : idSubProyecto,
                 idEmpresaColab : idEmpresaColab,
				 idEstacion     : idEstacion,
				 jefatura       : jefatura
				}
	}).done(function(data){
		data = JSON.parse(data);
		$('#contTablaCostoPqt').html(data.tablaCostoPaq);
		initDataTable('#data-table');
	});
}
var polygon = null;
function getSitiosArqueologicos() {
	var flgSitiosArq = $('#kmz_arque option:selected').val();
	
	if(flgSitiosArq == 1) {
		$.ajax({
			type : 'POST',
			url  : 'getSitiosArqueologicos'
		}).done(function(data){
			data = JSON.parse(data);
			var coords = data.arrayArqueo;
			var bounds = new google.maps.LatLngBounds();
			for (var i = 0; i < coords.length; i++) {
				var polygonCoords = [];
				for (var j = 0; j < coords[i].length; j++) {
				  var pt = new google.maps.LatLng(coords[i][j][1], coords[i][j][0])
				  bounds.extend(pt);
				  polygonCoords.push(pt);
				}
				// Construct the polygon.
				polygon = new google.maps.Polygon({
				  paths: polygonCoords,
				  strokeColor: '#FF0000',
				  strokeOpacity: 0.8,
				  strokeWeight: 2,
				  fillColor: '#FF0000',
				  fillOpacity: 0.35,
				  map: map
				});
			}
			map.fitBounds(bounds);
			
			// var bounds = new google.maps.LatLngBounds();
			// for (var i = 0; i < coordArrayViasMet.length; i++) {
				// var polygonCoords = [];
				// for (var j = 0; j < coordArrayViasMet[i].length; j++) {
					// console.log("LAT: "+coordArrayViasMet[i][j][1]);
				  // var pt = new google.maps.LatLng(coordArrayViasMet[i][j][1], coordArrayViasMet[i][j][0])
				  // bounds.extend(pt);
				  // polygonCoords.push(pt);
				// }
				// console.log(polygonCoords);
				// // Construct the polygon.
				// line = new google.maps.Polyline({
				  // paths: polygonCoords,
				  // strokeColor: '#FF0000',
				  // strokeOpacity: 0.8,
				  // strokeWeight: 2,
				  // fillColor: '#FF0000',
				  // fillOpacity: 0.35,
				  // map: map
				// });
			// }
			// map.fitBounds(bounds);
			console.log(data.arrayViasMetro);
			geojson = data.arrayViasMetro;
			map.data.addGeoJson(geojson);
			//line.setMap(map);
		});
		
		 // var verticesLinea = [
			// [ 41.59, -1.93 ],
			// [ 40.21, -2.10 ],
			// [ 39.24, -3.31 ],
			// [ 37.84, -3.03 ],
			// [ 36.91, -5.40 ]
		  // ];
		
		// var linea = new google.maps.Polyline({
			// paths: verticesLinea,
			// map: map,
			// strokeColor: '#FF0000',
			  // strokeOpacity: 0.8,
			  // strokeWeight: 2,
			  // fillColor: '#FF0000',
			  // fillOpacity: 0.35,
			  // map: map
		  // });

	} else {
		polygon.setMap(null);
	}
}

function getCtosNormalSimu() {
	$('#barra_prog').css('display', 'block');
	$('#barra_prog').css('width', '10%');
	$('#barra_prog').css('width', '50%');
	$.ajax({
        type : 'POST',
        url  : 'getCtosNormalSimu'      
    }).done(function(data){
		$('#barra_prog').css('display', 'block');
		data = JSON.parse(data);
		$('#barra_prog').css('width', '70%');
		for( i = 0; i < data.marcadores_cto.length; i++ ) {
			var position = new google.maps.LatLng(data.marcadores_cto[i].latitud, data.marcadores_cto[i].longitud);

			marcadores = new google.maps.Marker({
				icon: data.marcadores_cto[i].icon_cto,/* icon_url_cto_gbl, */
				position: position,
				map: map,
				title: data.marcadores_cto[i].codigo
			});
			 // Add info window to marker    
			google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
				
				return function() {
					infoWindowMarcadores.setContent(data.info_markers_cto[i][0]);
					infoWindowMarcadores.open(map, marcadores);
				}
			})(marcadores, i));
					
		}
		$('#barra_prog').css('width', '100%');
		$('#barra_prog').css('display', 'none');
	});
}

function getReservaSimu() {
	$.ajax({
        type : 'POST',
        url  : 'getReservaSimu'      
    }).done(function(data){
		data = JSON.parse(data);
		
		for( i = 0; i < data.marcaReserva.length; i++ ) {
			var position = new google.maps.LatLng(data.marcaReserva[i].latitud, data.marcaReserva[i].longitud);

			marcadores = new google.maps.Marker({
				icon: data.marcaReserva[i].icon_reserva,/* icon_url_cto_gbl, */
				position: position,
				map: map,
				title: data.marcaReserva[i].codigo
			});
			 // Add info window to marker    
			google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
				
				return function() {
					infoWindowMarcadores.setContent(data.infoMarcaReserva[i][0]);
					infoWindowMarcadores.open(map, marcadores);
				}
			})(marcadores, i));
					
		}
	});
}

function getEbcSimu() {
	$('#barra_prog').css('display', 'block');
	$('#barra_prog').css('width', '10%');
	$.ajax({
        type : 'POST',
        url  : 'getEbcSimu'      
    }).done(function(data){
		$('#barra_prog').css('width', '20%');
		data = JSON.parse(data);

		$('#barra_prog').css('width', '30%');
		for( i = 0; i < data.marcaEbc.length; i++ ) {
			
			var position = new google.maps.LatLng(data.marcaEbc[i].latitud, data.marcaEbc[i].longitud);

			marcadores = new google.maps.Marker({
				icon: data.marcaEbc[i].icon_reserva,/* icon_url_cto_gbl, */
				position: position,
				map: map,
				title: data.marcaEbc[i].codigo
			});
			 // Add info window to marker    
			google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
				
			return function() {
					infoWindowMarcadores.setContent(data.infoMarcaEbc[i][0]);
					infoWindowMarcadores.open(map, marcadores);
				}
			})(marcadores, i));
					
		}
		$('#barra_prog').css('width', '100%');
		$('#barra_prog').css('display', 'none');
	});
}


