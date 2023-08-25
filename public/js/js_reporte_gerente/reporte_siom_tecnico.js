
				
        /*******************************************************************
			FUNCIONES PROPIAS DE LA LIBRERIA GOOGLE MAPS
        ********************************************************************/
        
        /***************************/
        var center
        var map = null;
        var infoWindow = null; 
        var marker = null;
        var marcadores = null;
        var infoWindowMarcadores = null; 
        /***********************************************
            Control del redimensionamiento de la ventana
        ***********************************************/

        window.onresize = function(){
           // document.getElementById("contenedor_mapa").style.height = (window.innerHeight) + "px";           
        }

        /***********************************************
            Función de inicio.
            Creo el mapa, y lo centro en las coordenadas de España
        ***********************************************/
        function llenarMarcadores(){
			// Place each marker on the map  
		   for( i = 0; i < markers_odf.length; i++ ) {
		        var position = new google.maps.LatLng(markers_odf[i][1], markers_odf[i][2]);
		      
		        marcadores = new google.maps.Marker({
			        icon:goblal_icon_url_odf,
		            position: position,
		            map: map,
		            title: markers_odf[i][0]
		        });
				
			     // Add info window to marker    
		        google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {		        	
		            return function() {
		            	 /*var empl_codigo = markers_odf[i][0];
		            	  
		            	 $.ajax({
		            	        type : 'POST',
		            	        url  : 'getInfnodSiom',
		            	        data : { empl_codigo   : empl_codigo} 
		            	    }).done(function(data){
		            	        data = JSON.parse(data);
		            	        if(data.error == 0) {
		            	        	$('#contTablaSiom').html(data.tbTenicos);
		            	        	var data2 = JSON.parse(data.nodoInfo);
		            	        	$('#txtNodo').val(data2.empl_nemonico);
		            	        	$('#txtNombreNodo').val(data2.empl_nombre);
		            	        	$('#txtCoordX').val(data2.empl_coord_x);
		            	        	$('#txtCoordY').val(data2.empl_coord_y);
		            	            
		            	            //initDataTable('#data-table');
		            	            modal('modalSiom');
		            	        } else {
		            	            mostrarNotificacion('error','No se ingreso', data.msj);
		            	        }
		            	    });	
		            	    */
		            }		           
		        })(marcadores, i));
				        
		    }
		}
		
        function init(){
            var mapdivMap = document.getElementById("contenedor_mapa");
            //mapdivMap.style.width = '100%';
            //mapdivMap.style.height = (window.innerHeight) + "px";
           // mapdivMap.style.height = '100%';
            center = new google.maps.LatLng(-12.0431800, -77.0282400);//coordenadas lima
           // center = new google.maps.LatLng(-8.3791504,  -74.5538712);//al centro las coordenadas de pucallpara para que cuadre el mapa
            var myOptions = {
                zoom: 11,
                center: center,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById("contenedor_mapa"), myOptions);            
            //infoWindow = new google.maps.InfoWindow();  
            //geoposicionar();    
            //llenarMarcadores();               
        }        
    	
        /***********************************************
            En esta función se hace la solicitud de geolocalización y el primer
            control para ver si el navegador soporta el servicio
        ***********************************************/

        function geoposicionar(){
            if(navigator.geolocation){
                mostrarMensaje("obteniendo posición...");
                navigator.geolocation.getCurrentPosition(centrarMapa);
            }else{
                mostrarMensaje('Tu navegador no soporta geolocalización');
            }   
        }

        /***********************************************
            Control de errores en caso de que la llamada
            navigator.geolocation.getCurrentPosition(centrarMapa,errorPosicionar);
            termine generando un error
        ***********************************************/

        function errorPosicionar(error) {
            switch(error.code)  
            {  
                case error.TIMEOUT:  
                    mostrarMensaje('Request timeout');  
                break;  
                case error.POSITION_UNAVAILABLE:  
                    mostrarMensaje('Tu posición no está disponible');  
                break;  
                case error.PERMISSION_DENIED:  
                    mostrarMensaje('Tu navegador ha bloqueado la solicitud de geolocalización');  
                break;  
                case error.UNKNOWN_ERROR:  
                    mostrarMensaje('Error desconocido');  
                break;  
            }  
        }

        /***********************************************
            Esta función se ejecuta si la llamada a  navigator.geolocation.getCurrentPosition
            tiene éxito. La latitud y la longitud vienen dentro del objeto coords. 
        ***********************************************/
		
        
        function centrarMapa(pos){
            map.setZoom(5);
            /*
            console.log('centrarMapa....'+pos.coords.latitude+'-'+pos.coords.longitude);
            map.setCenter(new google.maps.LatLng(pos.coords.latitude,pos.coords.longitude));
                marker = new google.maps.Marker({
                position: new google.maps.LatLng(pos.coords.latitude,pos.coords.longitude),
                map: map,
                title:"Tu posición",
                draggable: true,
                animation: google.maps.Animation.DROP
              });
            
            ocultarMensaje();
            var geocoder = new google.maps.Geocoder();
            
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
         		 if (status == google.maps.GeocoderStatus.OK) {
          			var pos = marker.getPosition();
          			//llenarTextosByCoordenadas(results,pos)
          			var address=results[0]['formatted_address'];
         			openInfoWindowAddress(address,marker);         							
  			 }
		 	});	

            
           
            google.maps.event.addListener(marker, 'dragend', function(){
            	var pos = marker.getPosition();
            	geocoder.geocode({'latLng': pos}, function(results, status) {
               		 if (status == google.maps.GeocoderStatus.OK) {                			
                  			//llenarTextosByCoordenadas(results,pos)
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
                      			//llenarTextosByCoordenadas(results,pos)
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
                          			//llenarTextosByCoordenadas(results,pos)
                        			var address=results[0]['formatted_address'];
                       			 	openInfoWindowAddress(address,marker);				
                			 }
        			 	});
          			  	map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
            		});
            }); */
        }
        
        function openInfoWindowAddress(Addres,marker) {
           console.log('geo..');
            infoWindow.setContent([
            	Addres
            ].join(''));
            infoWindow.open(map, marker);
        }
        
        function openInfoWindow(marker) {
            var markerLatLng = marker.getPosition();
            infoWindow.setContent([
                '&lt;b&gt;La posicion del marcador es:&lt;/b&gt;&lt;br/&gt;',
                markerLatLng.lat(),
                ', ',
                markerLatLng.lng(),
                '&lt;br/&gt;&lt;br/&gt;Arr&amp;aacute;strame y haz click para actualizar la posici&amp;oacute;n.'
            ].join(''));
            infoWindow.open(map, marker);
        }
         

        /***********************************************
            Gestión de mensajes
        ***********************************************/

        function mostrarMensaje(str){
            $('#texto').html(str);
            $('#capa_mensajes').css({"visibility":"visible"});
        }

        function ocultarMensaje(){
           $('#capa_mensajes').css({"visibility":"hidden"}); 
        }
		/***********************************************************************************************************************************/
       /*************************************************
        Buscar ubicacion por direccion o coordenadas
        *************************************************/
       function searchDireccion(){
       	 	 address = document.getElementById('search').value;
        	 if(address!=''){
        		 if(isCoordenada(address)){
        			 buscarPorCoordenadas(address);
        		 }else{//ES DIRECCION
        			 console.log('address:'+address);
	        		 var geocoder = new google.maps.Geocoder();
	            	geocoder.geocode({ 'address': address}, function(results, status){
	       			   if (status == 'OK'){
	          			      				  
	       				//console.log('searchDireccion:'+JSON.stringify(results));
	           			  console.log('..-'+JSON.stringify(results[0].geometry.location));
	          			// Posicionamos el marcador en las coordenadas obtenidas
	       				 
	       				// Centramos el mapa en las coordenadas obtenidas
	       				  // map.setCenter(marker.getPosition());
	       				map.setCenter(results[0].geometry.location);
	       				marker.setPosition(results[0].geometry.location);
	          			/*	var pos = marker.getPosition();
	          				llenarTextosByCoordenadas(results,pos);	*/
	          				var address	=	results[0]['formatted_address'];
	           			 	openInfoWindowAddress(address,marker);	
	
	              			 geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
	                       		 if (status == google.maps.GeocoderStatus.OK) { 
	                        			//llenarTextosByCoordenadas(results,marker.getPosition());                   			
	                        			//console.log('searchDireccion:'+JSON.stringify(results));
	                			 }
	          		 		});
	            		 		
	    				 }
	   			 	})   
        		 }
        	 }  	 
        }
        
        function isCoordenada(cadena){
        	var str = cadena;
            var res = str.split(',');
            
            if(res.length == 2){
            	var x = res[0].trim();
                var y = res[1].trim();
            	
                var valid_x = (x.match(/^-?\d+(?:\.\d+)?$/));
                var valid_y = (y.match(/^-?\d+(?:\.\d+)?$/));
                
                if(valid_x){
                	if(valid_y){
                		return true;
                	}else{
                		return false;
                	}
                }else{
                	return false;
                }            	
            }else{
            	return false;
            }
                       
        }
        
        function buscarPorCoordenadas(cadena){        	
	        	var str = cadena;
	            var res = str.split(',');
	            var x = res[0].trim();
                var y = res[1].trim();
                
      			map.setCenter(new google.maps.LatLng(x, y));   
  				marker.setPosition(new google.maps.LatLng(x, y)); 				
          			 	
  			 	var geocoder = new google.maps.Geocoder();
  			 	geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
              		 if (status == google.maps.GeocoderStatus.OK) { 
              			var address	=	results[0]['formatted_address'];
          			 	openInfoWindowAddress(address,marker);	
               			//llenarTextosByCoordenadas(results,marker.getPosition());                   			
               			//console.log('searchDireccion:'+JSON.stringify(results));
              		 }
 		 		});  			    
       	 
        }
        
        $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Total 0 OS'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }                    
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: [{
                    name: 'Porcentaje',
                    y: 1.0753,
                    name: 'Porcentaje2',
                    y: 1.0753
                }]
            }]
        });
        
        function initGraphPie(datosPie, totalOS){
        	$('#container').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Total '+totalOS+' OS'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        },
                        size : '100%'
                    }
                },
                series: [{
                    name: 'Porcentaje',
                    colorByPoint: true,
                    data: datosPie,
                    point:{
                        events:{
                                  click: function (event) {
                                	  
                                	  console.log(this.name);
                                	  	var proyecto = $('#selectProyecto').val();
	                                  	var jefatura = $('#selectJefatura').val();
	                                  	var eecc 	 = $('#selectEECC').val();
	                                  	var estado 		= $('#selectEstado').val();
	                                  	var fecInicio   = $('#fechaInicio').val();
	                                	var fecFin      = $('#fechaFin').val();
                                        $.ajax({
                                         url:'getMarksTec',
                                         data:{	'id_proyecto' : proyecto,
	                                        	'jefatura'	:	jefatura,
	                                        	'eecc'		:	eecc,
                                        	    'tecnico_asig': this.name,
                                        	    estado		:	estado,
                                        	    fecInicio	:	fecInicio,
                                        	    fecFin	:	fecFin},
                                         type:'post'
                                     }).done(function(data){
                                    	 var data	=	JSON.parse(data);                                         
                                         
                                         var marcadores	=	JSON.parse(data.markers);
                                         init();
                                         llenarMarcadoresObras(marcadores);
                                         //$('#').html(data.tablaDatoSiom);
                                         $('#contTabla').html(data.tablaDatoSiom)
                        	    	     initDataTable('#data-table');
                                     });
                                  }
                              }
                      }
                }]
            });
        }
        
        function filtrarDatos(){
        	
        	var proyecto 	= $('#selectProyecto').val();
        	var jefatura 	= $('#selectJefatura').val();
        	var eecc 	 	= $('#selectEECC').val();
        	var tipoTec 	= $('#selectTipoTecnico').val();    
        	var estado 		= $('#selectEstado').val(); 
        	
        	var fecInicio   = $('#fechaInicio').val();
        	var fecFin      = $('#fechaFin').val();
        	
        	if(proyecto!= '' && jefatura != '' && eecc != ''){
        		
        		if(estado != '' && (fecInicio=='' || fecFin == '')){
        			alert('El Filtro estado requiere de un rango de fechas.');
        			return;
        		}
        		
        		if(estado == '' && (fecInicio !='' || fecFin != '')){
        			alert('El filtro rango de fechas requiere de un filtro Estado.');
        			return;
        		}
        		
	        	 $.ajax({//poner la data a enviar
				        url: "drwaPieByFil",
				        data : {proyecto 	: proyecto,
					        	jefatura	: jefatura,
					        	eecc		: eecc,
					        	tipoTec		: tipoTec,
					        	estado 	    : estado,
					        	fecInicio	: fecInicio,
					        	fecFin		: fecFin},
			            type: 'POST'
				  	})
					  .done(function(data) {
	      	        var data	=	JSON.parse(data);
	      	    	if(data.error == 0){
	      	    		console.log('OK');
	      	    		var datosPie = JSON.parse(data.dataPie);
	          	    	console.log(datosPie);
	          	    	initGraphPie(datosPie, data.totalOS);
	      	    	}else if(data.error == 1){     				
	      				mostrarNotificacion('error','ERROR',data.msj);
	      			}
	  		  });
	        	 
        	}else{
        		alert('Filtros Basicos "PROYECTO - JEFATURA - EECC"');
        	}
        }
        
        function llenarMarcadoresObras(markers_obras){
			// Place each marker on the map  
        	console.log(markers_obras);
        	/*console.log(markers_obras.length);
        	console.log(JSON.parse(markers_obras).length);*/
		   for( i = 0; i < markers_obras.length; i++ ) {
			   console.log(markers_obras[i][1]);
		        var position = new google.maps.LatLng(markers_obras[i][1], markers_obras[i][2]);
		      
		        marcadores = new google.maps.Marker({
			        icon:goblal_icon_url_odf,
		            position: position,
		            map: map,
		            title: markers_obras[i][0]
		        });
				
			     // Add info window to marker    
		        google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {		        	
		            return function() {
		            	 /*var empl_codigo = markers_odf[i][0];
		            	  
		            	 $.ajax({
		            	        type : 'POST',
		            	        url  : 'getInfnodSiom',
		            	        data : { empl_codigo   : empl_codigo} 
		            	    }).done(function(data){
		            	        data = JSON.parse(data);
		            	        if(data.error == 0) {
		            	        	$('#contTablaSiom').html(data.tbTenicos);
		            	        	var data2 = JSON.parse(data.nodoInfo);
		            	        	$('#txtNodo').val(data2.empl_nemonico);
		            	        	$('#txtNombreNodo').val(data2.empl_nombre);
		            	        	$('#txtCoordX').val(data2.empl_coord_x);
		            	        	$('#txtCoordY').val(data2.empl_coord_y);
		            	            
		            	            //initDataTable('#data-table');
		            	            modal('modalSiom');
		            	        } else {
		            	            mostrarNotificacion('error','No se ingreso', data.msj);
		            	        }
		            	    });	
		            	    */
		            }		           
		        })(marcadores, i));
				        
		    }
		}