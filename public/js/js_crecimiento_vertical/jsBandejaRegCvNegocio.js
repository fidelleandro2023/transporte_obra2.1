		var idCentralGlobal = null;
		var codigoMdfGlobal = null;
		var idZonalGlobal   = null;
		function revalidateContactos(){
			console.log('.................');
			$('#formRegistrarCV').bootstrapValidator('revalidateField', 'txt_nombre_constru');  
			$('#formRegistrarCV').bootstrapValidator('revalidateField', 'txt_contacto1');
			$('#formRegistrarCV').bootstrapValidator('revalidateField', 'txt_telefono11');
			$('#formRegistrarCV').bootstrapValidator('revalidateField', 'txt_telefeono12');
			$('#formRegistrarCV').bootstrapValidator('revalidateField', 'email1');
		}
		
        function existConstrutora(ruc){
        	var result = $.ajax({
        		type : "POST",
        		'url' : 'exiCons',
        		data : {
        			'ruc' : ruc
        		},
        		'async' : false
        	}).responseText;
        	return result;
        }
        
        var edit_ruc = 0;
       
        $('#formRegistrarCV')
    	.bootstrapValidator({
    	    container: '#mensajeForm',
    	    feedbackIcons: {
    	        valid: 'glyphicon glyphicon-ok',
    	        invalid: 'glyphicon glyphicon-remove',
    	        validating: 'glyphicon glyphicon-refresh'
    	    },
    	    excluded: ':disabled',
    	    fields: {/*
    	    	selectSubPro: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe seleccionar un Sub Proyecto.</p>'
    	                }
    	             }
     	    	   },*/
     	    	  txt_nombre_proyecto: {
      	            validators: {
      	                notEmpty: {
      	                    message: '<p style="color:red">(*) Debe Ingresar un Nombre de Proyecto.</p>'
      	                }
      	             }
       	    	   },/*
  	    	  	selectTipoUrb: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe seleccionar Tipo Urb.</p>'
    	                }
    	             }
     	    	   },      	 
  	    	  	txt_NombreUrb: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar nombre Urb.</p>'
    	                }
    	             }
     	    	   }, 
      	    	 selectTipoVia: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe seleccionar Tipo Via.</p>'
        	                }
        	             }
         	    	   }, 
  	    	  	txt_direccion: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar una dirección.</p>'
        	                }
        	             }
         	    	   }, 
      	    	  txt_numero: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar un Número de dirección.</p>'
        	                }
        	             }
         	    	   }, 
      	    	  txt_manzana: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar una Manzana.</p>'
        	                }
        	             }
         	    	   },
      	    	  txt_lote: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar un Lote.</p>'
        	                }
        	             }
         	    	   },
      	    	  txt_nombre_proyecto: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar un Nombre de Proyecto.</p>'
        	                }
        	             }
         	    	   },
      	    	  txt_blocks: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar un Número de blocks.</p>'
        	                }
        	             }
         	    	   },   
      	    	  txt_pisos: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar un Número de pisos.</p>'
        	                }
        	             }
         	    	   }, 
      	    	  txt_departamentos: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar un Número de departamentos.</p>'
        	                }
        	             }
         	    	   }, 
*/ 	    	   	  
    	    	txt_ruc: {    	          
    	    		validators: {   
    	    		stringLength: {
    	                    min: 11,
    	                    max: 11,
    	                    message: '<p style="color:red">(*)El R.U.C. debe tener 11 caracteres</p>'
    	                },
        	             callback: {
    	            	 	message: '<p style="color:red">(*) El subproyecto ya se encuentra registrado con la pep seleccionada</p>',
      	                    callback: function(value, validator) {      	                    
          	                    if(value.length == 11){
          	                    	var data = JSON.parse(existConstrutora(value));
          	                    	if(data.error == 0){
              	                    	if(data.hasInfo	== 1){
              	                    		if(value != global_ruc_constructora){
              	                    			$('#txt_nombre_constru').val(data.nombre);
                      	                    	$('#txt_contacto1').val(data.contacto_1);
              	                    			$('#txt_telefono11').val(data.telefono_1_1);
      	                    					$('#txt_telefeono12').val(data.telefono_2_1);
                    							$('#email1').val(data.email_1);
            									$('#txt_contacto2').val(data.contacto_2);
                      	                    	$('#txt_telefono21').val(data.telefono_1_2);
              	                    			$('#txt_telefono22').val(data.telefono_2_2);
      	                    					$('#txt_email2').val(data.email_2);
      	                    					edit_ruc = 1;
              	                    		}
              	                    		if(value == global_ruc_constructora && edit_ruc == 1){
              	                    			$('#txt_nombre_constru').val(data.nombre);
                      	                    	$('#txt_contacto1').val(data.contacto_1);
              	                    			$('#txt_telefono11').val(data.telefono_1_1);
      	                    					$('#txt_telefeono12').val(data.telefono_2_1);
                    							$('#email1').val(data.email_1);
            									$('#txt_contacto2').val(data.contacto_2);
                      	                    	$('#txt_telefono21').val(data.telefono_1_2);
              	                    			$('#txt_telefono22').val(data.telefono_2_2);
      	                    					$('#txt_email2').val(data.email_2);
              	                    		}console.log('11111111');
  	                    					$('#btnRegFicha').attr('data-cons', 0);
  	                    					//revalidateContactos();
              	                    	}else if(data.hasInfo	== 0){console.log('4');
              	                    		$('#txt_nombre_constru').val('');
              	                    		$('#txt_contacto1').val('');
          	                    			$('#txt_telefono11').val('');
  	                    					$('#txt_telefeono12').val('');
                							$('#email1').val('');
        									$('#txt_contacto2').val('');
                  	                    	$('#txt_telefono21').val('');
          	                    			$('#txt_telefono22').val('');
  	                    					$('#txt_email2').val('');
  	                    					$('#btnRegFicha').attr('data-cons', 1);
  	                    					edit_ruc = 1;
  	                    					//revalidateContactos();  	                    					
              	                    	}
          	                    	}
          	                    	return true; 
          	                    }else {
          	                    	return true; 
          	                    }
          	                    
    	                   	            	                    
      	                    }
    	                }
    	    		}
	    		},
	           /*
	            txt_nombre_constru: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar nombre constructora.</p>'
    	                }
    	             }
     	    	   },
      	    	  txt_contacto1: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar nombre del contacto 1.</p>'
    	                }
    	             }
     	    	   },
      	    	  txt_telefono11: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar telefono 1 del contacto 1.</p>'
    	                }
    	             }
     	    	   },
      	    	  txt_telefeono12: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar telefono 2 del contacto 1.</p>'
    	                }
    	             }
     	    	   },
      	    	  email1: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Ingresar email del contacto 1.</p>'
        	                }
        	             }
         	    	   },*/
	    		selectEECC : {
      	    		 validators: {
      	                notEmpty: {
      	                    message: '<p style="color:red">(*) Debe seleccionar una EECC.</p>'
      	                }
      	             }
					},
				// selectPlan: {
				// 	validators: {
				// 	   notEmpty: {
				// 		   message: '<p style="color:red">(*) Debe seleccionar un plan.</p>'
				// 	   }
				// 	}
				//  },	
      	    	 fileupload: {
                     validators: {
                         notEmpty: {
                             message: '<p style="color:red">(*) Debe subir evidencia del avance de porcentaje.</p>'
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
    		    
    		    formData.append('departamento', $('#txt_departamento').val());
    		    formData.append('provincia', $('#txt_provincia').val());
    		    formData.append('coor_x', $('#txt_coord_x').val());
				formData.append('coor_y', $('#txt_coord_y').val());
				formData.append('distrito', $('#txt_distrito').val());
				/////////
				formData.append('idSubProyecto' , $('#cmbSubProyecto option:selected').val());
				formData.append('idEmpresaColab', $('#selectEECC option:selected').val());
				formData.append('idCentral'		, idCentralGlobal);
				formData.append('idZonal'		, idZonalGlobal);
				formData.append('idFase'        , $('#selectFase option:selected').val());
				formData.append('idTipoSubProyecto', $('#cmbTipoSubProyecto option:selected').val());
				///////////
    		    var accion = $('#btnRegFicha').attr('data-cons');
    		    formData.append('accion', accion);
    		    // var itemplan = $('#btnRegFicha').attr('data-item');
    		    // formData.append('itemplan', itemplan);
    		    
    		    // var input = document.getElementById('fileupload');
	            // var file = input.files[0];
	            
	            // formData.append('file', file);
    		    
	            // console.log(por_actu);
	            // var porcentaje = por_actu; 
	            // formData.append('per_actu', porcentaje);
	            
	            // formData.append('tipo_save', 1);//guardar y enviar
    		    $.ajax({
			        data: formData,
			        url: "registroCvNegocio",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				.done(function(data) {  
					var data	=	JSON.parse(data);
					if(data.error == 0){console.log(data.itemplan);   
						swal({
							title: 'Se genero correctamente el itemplan: '+data.itemplan,
							text: 'Asegurese de validar la información!',
							type: 'success',
							buttonsStyling: false,
							confirmButtonClass: 'btn btn-primary',
							confirmButtonText: 'OK!'
							
						}).then(function(){
							location.reload();
						});
						//location.reload();
					}else if(data.error == 1){
						mostrarNotificacion('error',data.msj);
					}
				});
    	});

		
        /*******************************************************************
			FUNCIONES PROPIAS DE LA LIBRERIA GOOGLE MAPS
        ********************************************************************/
    
        var markers = global_marcadores;
        var infoWindowContent = global_info_marcadores;
        
        var markers_2017 = global_marcadores_2017;
        var infoWindowContent_2017 = global_info_marcadores_2017;
        
        var markers_odf				=	global_marcadores_odf;
        var infoWindowContent_odf 	=	global_info_marcadores_odf;
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
       	 infoWindowMarcadores = new google.maps.InfoWindow(); 
		    for( i = 0; i < markers.length; i++ ) {
		        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
		      
		        marcadores = new google.maps.Marker({
			        icon: ((markers[i][3] == 4 || markers[i][3] == 9 || markers[i][3] == 6) ? goblal_icon_url_terminado : goblal_icon_url_pendiente),
		            position: position,
		            map: map,
		            title: markers[i][0]
		        });
				
			     // Add info window to marker    
		        google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
		            return function() {
		            	infoWindowMarcadores.setContent(infoWindowContent[i][0]);
		            	infoWindowMarcadores.open(map, marcadores);
		            }
		        })(marcadores, i));
				        
		    }
		    
		    for( i = 0; i < markers_2017.length; i++ ) {
		        var position = new google.maps.LatLng(markers_2017[i][1], markers_2017[i][2]);
		      
		        marcadores = new google.maps.Marker({
			        icon:goblal_icon_url_2017,
		            position: position,
		            map: map,
		            title: markers_2017[i][0]
		        });
				
			     // Add info window to marker    
		        google.maps.event.addListener(marcadores, 'click', (function(marcadores, i) {
		            return function() {
		            	infoWindowMarcadores.setContent(infoWindowContent_2017[i][0]);
		            	infoWindowMarcadores.open(map, marcadores);
		            }
		        })(marcadores, i));
				        
		    }
		    
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
		            	infoWindowMarcadores.setContent(infoWindowContent_odf[i][0]);
		            	infoWindowMarcadores.open(map, marcadores);
		            }
		        })(marcadores, i));
				        
		    }
		    
		}
		
        function init(){ 	
            var mapdivMap = document.getElementById("contenedor_mapa");
            center = new google.maps.LatLng(-12.0431800, -77.0282400);       
            var myOptions = {
                zoom: 5,
                center: center,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById("contenedor_mapa"), myOptions);            
            infoWindow = new google.maps.InfoWindow();  
            
            if(global_coord_x!=null && global_coord_y!=null){
            	centrarMapaEdit(global_coord_y, global_coord_x);  
            }else{
				centrarMapaEdit(-12.0431800, -77.0282400);//temporal de ahi comentar descomentar el geopcicionar
            }
            llenarMarcadores();               
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
            
            ocultarMensaje();
            var geocoder = new google.maps.Geocoder();
            
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
         		 if (status == google.maps.GeocoderStatus.OK) {
          			var pos = marker.getPosition();
          			llenarTextosByCoordenadas(results,pos)
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
	                        			llenarTextosByCoordenadas(results,marker.getPosition());                   			
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
               			llenarTextosByCoordenadas(results,marker.getPosition());                   			
               			//console.log('searchDireccion:'+JSON.stringify(results));
              		 }
 		 		});  			    
       	 
        }

        /***************************************************
		METODO PARA LLENAR CAMPOS POR LAS COORDENADAS
    ****************************************************/

    function llenarTextosByCoordenadas(results,pos){
    	//console.log('llenar:'+results[0]['address_components'][1].long_name);
    	
    	/*
    	console.log('results 0:'+JSON.stringify(results[0]));
    	console.log('results 1:'+JSON.stringify(results[1]));
    	console.log('results 2:'+JSON.stringify(results[2]));
    	console.log('results 3:'+JSON.stringify(results[3]));
    	console.log('results 4:'+JSON.stringify(results[4]));
    	console.log('results 5:'+JSON.stringify(results[5]));
    	console.log('results 6:'+JSON.stringify(results[6]));
    	console.log('results 7:'+JSON.stringify(results[7]));
    	console.log('results 8:'+JSON.stringify(results[8]));
		*/
		
		$.ajax({
            type : 'POST',
            url  : 'getDataCentral',
            data : { latitud  : pos.lat(),
                     longitud : pos.lng() }         
        }).done(function(data){
            data = JSON.parse(data);

			try{
				$('#txt_departamento').val(results[1]['address_components'][4].long_name.toUpperCase());
			}catch(err){
				$('#txt_departamento').val('');
			}
			
			try{
				$('#txt_provincia').val(results[1]['address_components'][3].long_name.toUpperCase());
			}catch(err){
				$('#txt_provincia').val('');
			}
			
			try{
				$('#txt_distrito').val(results[1]['address_components'][2].long_name.toUpperCase());
			}catch(err){
				$('#txt_distrito').val('');
			}

			try{
				$('#txt_numero').val(results[0]['address_components'][0].long_name.toUpperCase());
			}catch(err){
				$('#txt_numero').val('0');
			}
			
			try{
				$('#txt_direccion').val(results[0]['formatted_address']);
			}catch(err){
				$('#txt_direccion').val('NO ENCONTRADA');
			}
			$('#txt_coord_x').val(pos.lng());
			$('#txt_coord_y').val(pos.lat());
			console.log(data.dataCentral);

			idCentralGlobal = data.dataCentral[0].idCentral;
			codigoMdfGlobal = data.dataCentral[0].codigo;
			idZonalGlobal   = data.dataCentral[0].idZonal;
			console.log(codigoMdfGlobal);
			$('#txt_mdf').val(codigoMdfGlobal);
			getEmpresaColabByMdf();
            // $('#inputNombrePlan').val(data.dataCentral.tipoCentralDesc);
            // $('#selectCentral').val(data.dataCentral.idCentral).trigger('change');

        });

    	
    	
      	/*
    	console.log('Direccion formateada:'+JSON.stringify(results[0]['formatted_address']));
    	console.log('Distrito:'+JSON.stringify(results[1]['address_components'][2].long_name));
    	console.log('Provincia:'+JSON.stringify(results[1]['address_components'][3].long_name));
    	console.log('Departamento:'+JSON.stringify(results[1]['address_components'][4].long_name));   	
    	*/
    	//$('#txt_departamento').val(results[1]['address_components'][4].long_name.toUpperCase());
    	//$('#txt_provincia').val(results[1]['address_components'][3].long_name.toUpperCase());
    	//$('#txt_distrito').val(results[1]['address_components'][2].long_name.toUpperCase());

		//$('#txt_direccion').val(results[0]['formatted_address']);
		//$('#txt_numero').val(results[0]['address_components'][0].long_name.toUpperCase());
		
    	/*
    	console.log('results 0:'+JSON.stringify(results[0]['address_components']));
    	console.log('results 1:'+JSON.stringify(results[1]['address_components']));
    	console.log('results 2:'+JSON.stringify(results[2]['address_components']));
    	
		console.log('Direccion 0 1:'+JSON.stringify(results[0]['address_components'][1].long_name));
		console.log('Distrito: 0 2'+JSON.stringify(results[2]['address_components'][0].long_name));
		console.log('Provincia 0 3:'+JSON.stringify(results[0]['address_components'][3].long_name));
		console.log('Departamento 0 4:'+JSON.stringify(results[0]['address_components'][4].long_name));
		console.log('Departamento 2 2:'+JSON.stringify(results[2]['address_components'][2].long_name));


		console.log('Numero:'+JSON.stringify(results[0]['address_components'][0].long_name));
		console.log('Direccion:'+JSON.stringify(results[0]['address_components'][1].long_name));
		console.log('Distrito:'+JSON.stringify(results[2]['address_components'][0].long_name));
		console.log('Provincia:'+JSON.stringify(results[0]['address_components'][3].long_name));
		console.log('Departamento:'+JSON.stringify(results[0]['address_components'][4].long_name));
		*/
     }
        
    function centrarMapaEdit(latitude, longitude){
        map.setZoom(16);
        console.log(':centrarMapa....'+latitude+'-'+longitude);
        map.setCenter(new google.maps.LatLng(latitude, longitude));
        marker = new google.maps.Marker({
	        position: new google.maps.LatLng(latitude, longitude),
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
      			results[0]['address_components'][1].long_name = global_direccion;
      			results[0]['address_components'][0].long_name = global_numero;
      			llenarTextosByCoordenadas(results,pos);
      			//console.log('...->>>>'+results[0]['address_components'][1].long_name);
      			var address = results[0]['formatted_address'];
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
    
    function cambiar(){
        var pdrs = document.getElementById('fileupload').files[0].name;
        document.getElementById('info').innerHTML = pdrs;
    }
    
    // function validatePercent(){
    // 	var porcentaje = por_actu; 
    // 	var selecPerce = $.trim($('#txt_avance').val());
    // 	if(selecPerce	!=	porcentaje){
    // 		$('#contUploadFileCoti').show();
    //    		 var validator = $('#formRegistrarCV').data('bootstrapValidator');
    //          validator.enableFieldValidators('fileupload', true);
    //     }else{
    //     	$('#contUploadFileCoti').hide();
    //        	 var validator = $('#formRegistrarCV').data('bootstrapValidator');
    //          validator.enableFieldValidators('fileupload', false);
    //     }
    // }
    
    var validator = $('#formRegistrarCV').data('bootstrapValidator');
    validator.enableFieldValidators('fileupload', false); 


    function getDetalleLog(component){
      	
        var item = $(component).attr('data-item');           
        var mes = $(component).attr('data-mes');
       
   	    $.ajax({
   	    	type	:	'POST',
   	    	url     : "getDetLogCv",
			    data: { 'itemplan'  :   item,
	  			    	'mes'    	:   mes},
	   	    			'async'	  	:	  false
   	    })
   	    .done(function(data){             	    
   	    	var data = JSON.parse(data);   					
		    	if(data.error == 0){
			    	$('#tituloModal').html(data.itemplan);
		    		$('#contTablaDetalle').html(data.tablaDetalleLog);			    						
		    		//initDataTable('#data-table2');		    		
		    		$('#modal_detalle').modal('toggle');      				
		    	}else if(data.error == 1){   				    	
					mostrarNotificacion('error','Error',data.msj);
				}
   		  })
   		  .fail(function(jqXHR, textStatus, errorThrown) {
   		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
   		  })
   		  .always(function() {
   	  	 
   		});           	              
   }

   	function getSubProyectoByTipo() {
		var idTipoSub = $('#cmbTipoSubProyecto option:selected').val();
		if(idCentralGlobal == null || idCentralGlobal == '') {
			return;
		}

         $.ajax({
            type    :   'POST',
            'url'   :   'getSubProyectoByTipo',
            data    :   { 	
							idTipoSub : idTipoSub
						},
            'async' :   false
        })
        .done(function(data){
            var data    =   JSON.parse(data);
            if(data.error == 0){       

                $('#cmbSubProyecto').html(data.cmbTipoSub);
                $('#selectEECC').val('').trigger('chosen:updated');
                
            }else if(data.error == 1){
                
                mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
            }
        });
   	}
    
    function getEmpresaColabByMdf(){
		var idSubProyecto = $('#cmbSubProyecto option:selected').val();
		var idTipoSub     = $('#cmbTipoSubProyecto option:selected').val();
		var idFase        = $('#selectFase option:selected').val();
		if(idCentralGlobal == null || idCentralGlobal == '') {
			return;
		}

		if(idTipoSub == null || idTipoSub == '') {
			return;
		}

		if(idFase == null || idFase == '') {
			return;
		}

         $.ajax({
            type    :   'POST',
            'url'   :   'getEmpresaColabByMdf',
            data    :   { 	
							flg_paquetizado : flgPaquetizadoGlb,
							idCentral       : idCentralGlobal,
							idSubProyecto   : idSubProyecto,
							idTipoSub       : idTipoSub,
							idFase          : idFase

						},
            'async' :   false
        })
        .done(function(data){
            var data    =   JSON.parse(data);
            if(data.error == 0){       

                $('#selectEECC').html(data.listaEecc);
                $('#selectEECC').val('').trigger('chosen:updated');
            }else if(data.error == 1){
                mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
            }
        });
	}
	
    