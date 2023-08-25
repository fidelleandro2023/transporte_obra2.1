/* global Coordenadas */




function updateNumCarta() {
    var inputNomCli = $('#inputNomCli').val();
    var inputNumCar = $('#inputNumCar').val();
    var selectAno = $('#selectAno').val();
    $('#inputNumCartaFin').val(selectAno + '-' + inputNumCar + '-' + inputNomCli);
}
/*  var validator = $('#formAddPlanobra').data('bootstrapValidator');
 validator.enableFieldValidators('fileupload', false); 
 */

function getItemplanMadreHeredero() {
    $('#inputIndicador').val($('#cmbItemMadre option:selected').text());
}
var flgAgrega = null;

function getItemplanMadre() {
    //validarFasePorProyecto();
    var descSupProyecto = $('#selectSubproy option:selected').text();
    var idSupProyecto = $('#selectSubproy option:selected').val();

    //INICIO -- UBICAR EL TIPO DE FACTOR DE MEDICION
    $.ajax({
        type: 'POST',
        'url': 'getItemplanMadreFactorMed',
        data: {idSupProyecto: idSupProyecto},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);

                if (data.error == 0) {
                    $('#cmbItemMadre').html(data.cmbItemMadre);
                    $('#inputCantidadFactorMedicion').val('');

                    if (data.idFactorMedicion > 0) {
                        $('#lblFactorMedicion').text("FACTOR DE MEDICION: " + data.descFactorMedicion);
                        $('#hfIdFactorMedicion').val(data.idFactorMedicion);
                        $('#divFactorMedicion').hide();
                    } else {
                        $('#lblFactorMedicion').text("");
                        $('#hfIdFactorMedicion').val("0");
                        $('#divFactorMedicion').hide();
                    }

                } else if (data.error == 1) {

                    mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                }
            });
    //

    var arreglo = descSupProyecto.split(" ");
    var flgMarcar = 0;

    arreglo.forEach(item => {
        if (item == '2017') {
            flgMarcar = 1;
        }
    });

    /*if(flgMarcar == 1){
     if(flgAgrega ==  1){
     $("#selectFase").append(new Option("2017", "4"));
     }
     $('#selectFase').val(4);
     $('#selectFase').change();
     $('#selectFase option:not(:selected)').attr('disabled',true);
     
     }else{
     $('#selectFase').val(null);
     $("#selectFase option[value='4']").remove();
     $('#selectFase').change();
     $('#selectFase option:not(:selected)').attr('disabled',false);
     }*/
    flgAgrega = 1;

    //MOSTRAR REGISTROS DE ADJUDICACION AUTOMATICA
    $.ajax({
        type: 'POST',
        'url': 'pqt_getInfoSubProCoaxFo',
        data: {idSupProyecto: idSupProyecto},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);

                if (data.error == 0) {
                    console.log('has_coaxial               : ' + data.has_coaxial);
                    console.log('has_fo                    : ' + data.has_fo);
                    console.log('adjudicacionAutomatica_fg : ' + data.adjudicacionAutomatica_fg);
                    console.log('paquetizado_fg            : ' + data.paquetizado_fg);

                    $('#hfAdjudicacionAutomatica').val(data.adjudicacionAutomatica_fg);
                    $('#hfHasCoaxial').val(data.has_coaxial);
                    $('#hfHasFo').val(data.has_fo);
                    $('#hfpaquetizado_fg').val(data.paquetizado_fg);

                    if (data.adjudicacionAutomatica_fg == 1) {
                        $('#idFechaPreAtencionCoax').val('');
                        $('#idFechaPreAtencionFo').val('');

                        if (data.has_fo == 1) {
                            $('#divFO').show();
                        } else {
                            $('#divFO').hide();
                        }

                        if (data.has_coaxial == 1) {
                            $('#divCoaxial').show();
                        } else {
                            $('#divCoaxial').hide();
                        }
                    } else {
                        $('#divCoaxial').hide();
                        $('#divFO').hide();
                        $('#idFechaPreAtencionCoax').val('');
                        $('#idFechaPreAtencionFo').val('');
                    }

                } else if (data.error == 1) {
                    $('#divCoaxial').hide();
                    $('#divFO').hide();
                    $('#idFechaPreAtencionCoax').val('');
                    $('#idFechaPreAtencionFo').val('');
                    mostrarNotificacion('error', 'Hubo problemas al obtener los datos!');
                }
            });
}


function cambiar() {
    var pdrs = document.getElementById('fileupload').files[0].name;
    document.getElementById('info').innerHTML = pdrs;
}

function cambiar2() {
    var pdrs = document.getElementById('fileuploadOP').files[0].name;
    document.getElementById('infoOP').innerHTML = pdrs;
}
/*actualizacion dinamica de combobox*/
/*actualizacion de subproyecto a partir del proyecto*/
var IDSUB = null;
var itemP = null;


$('#inputIndicador, #selectCentral').bind('keypress blur', function () {
    $('#inputNombrePlan').val($('#inputIndicador').val() + ' - ' + $('#selectCentral option:selected').text());
});

function recalcular_fecha_prev_ejec() {

    var subproy = $.trim($('#selectSubproy').val());

    if (subproy == undefined || subproy == 'undefined' || subproy == '') {
        $('#inputFechaPrev').val('');
        return false;
    }

    var inputFechaInicio = $.trim($('#inputFechaInicio').val());

    if (inputFechaInicio == undefined || inputFechaInicio == 'undefined' || inputFechaInicio == '') {
        $('#inputFechaPrev').val('');
        return false;
    }

    $.ajax({
        type: 'POST',
        'url': 'pqt_getFechaSubproOP',
        data: {fecha: inputFechaInicio,
            subproyecto: subproy
        },
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {

                    $('#inputFechaPrev').val(data.fechaCalculado);

                } else if (data.error == 1) {

                    mostrarNotificacion('error', 'Hubo problemas al obtener la fecha de prevista!');
                }
            });




}

function changueCentral() {
    var central = $.trim($('#selectCentral').val());
    $.ajax({
        type: 'POST',
        'url': 'pqt_getZonalPO',
        data: {central: central},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    $('#inputNombrePlan').val('');
                    $('#inputNombrePlan').val($('#selectCentral option:selected').text());
                    $('#selectZonal').html(data.listaZonal);
                    $('#selectZonal').val(data.idZonalSelec).trigger('chosen:updated');
                    $('#selectEmpresaColab').html(data.listaEECC);
                    $('#selectEmpresaColab').val(data.idEECCSelec).trigger('chosen:updated');

                    $('#inputJefatura').val(data.jefatura);


                } else if (data.error == 1) {

                    mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                }
            });
}


function changueEECC() {
    var central = $.trim($('#selectCentral').val());
    $.ajax({
        type: 'POST',
        'url': 'pqt_getEECCPO',
        data: {central: central},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {

                    $('#selectEmpresaColab').html(data.listaEECC);

                    $('#selectEmpresaColab').val(data.idEECCSelec).trigger('chosen:updated');

                } else if (data.error == 1) {

                    mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                }
            });
}


$("#btnSave").click(function () {

    var formData = new FormData();

    var selectProy = 4;
    var selectSubproy = $('#selectSubproy').val();
    var selectCentral = $('#selectCentral').val();
    var selectZonal = $('#selectZonal').val();
    var selectEmpresaColab = $('#selectEmpresaColab').val();
    var selectEmpresaEle = $('#selectEmpresaEle').val();
    var selectFase = $('#selectFase').val();
    var inputIndicador = $('#inputIndicador').val();
    var fecRecepcion = $('#fecRecepcion').val();
    var inputFechaInicio = $('#inputFechaInicio').val();
    var inputNombrePlan = $('#inputNombrePlan').val();
    var inputCoordX = $('#inputCoordX').val();
    var inputCoordY = $('#inputCoordY').val();
    var selectCotizacion = $('#selectCotizacion').val();
    var hfIdFactorMedicion = $('#hfIdFactorMedicion').val();
    var inputCantidadFactorMedicion = $('#inputCantidadFactorMedicion').val();
    var hfAdjudicacionAutomatica = $('#hfAdjudicacionAutomatica').val();
    var hfHasCoaxial = $('#hfHasCoaxial').val();
    var hfpaquetizado_fg = $('#hfpaquetizado_fg').val();
    var itemplanMadre = $('#cmbItemMadre option:selected').val();

    formData.append('selectProy', selectProy);
    formData.append('selectSubproy', selectSubproy);
    formData.append('selectCentral', selectCentral);
    formData.append('selectZonal', selectZonal);
    formData.append('selectEmpresaColab', selectEmpresaColab);
    formData.append('selectEmpresaEle', selectEmpresaEle);
    formData.append('selectFase', selectFase);
    formData.append('inputIndicador', inputIndicador);
    formData.append('fecRecepcion', fecRecepcion);
    formData.append('inputFechaInicio', inputFechaInicio);
    formData.append('inputNombrePlan', inputNombrePlan);
    formData.append('inputCoordX', inputCoordX);
    formData.append('inputCoordY', inputCoordY);
    formData.append('selectCotizacion', selectCotizacion);
    formData.append('hfIdFactorMedicion', hfIdFactorMedicion);
    formData.append('inputCantidadFactorMedicion', inputCantidadFactorMedicion);
    formData.append('hfAdjudicacionAutomatica', hfAdjudicacionAutomatica);
    formData.append('hfHasCoaxial', hfHasCoaxial);
    formData.append('hfpaquetizado_fg', hfpaquetizado_fg);
    formData.append('itemplanMadre', itemplanMadre);


    // var codPlan = $('#selectPlan option:selected').val();
    // formData.append('cod_planificacion', codPlan);

    $.ajax({
        type: 'POST',
        url: 'createPlanobraOP',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data.error);
        if (data.error == 0) {
            swal({
                type: 'success',
                title: 'Se ha creado con &eacute;xito el ITEMPLAN Itemplan : ' + data.itemplannuevo,
                text: 'Registro correcto',
                showConfirmButton: true,
                backdrop: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
//                $('#contTablaItems').html(data.tbItemMadre);
//                initDataTable('#data-table');
                location.reload();
            });
        } else {
            mostrarNotificacion('warning', 'Verificar', data.msj);
        }
    });
});





function validateCoti() {
    var hasCoti = $.trim($('#selectCotizacion').val());
    console.log(hasCoti);
    if (hasCoti == '1') {
        $('#contUploadFileCoti').show();
    } else {
        $('#contUploadFileCoti').hide();
    }
}




/************METODOS GOOGLE MAP**************/
var marker = null;
var map = null;
var center = null;

function init() {
    infoWindow = new google.maps.InfoWindow();
}

function searchDireccion() {
    address = document.getElementById('search').value;
    if (address != '') {
        if (isCoordenada(address)) {
            buscarPorCoordenadas(address);
        } else {//ES DIRECCION
            console.log('address:' + address);
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
        }
    }
}

function isCoordenada(cadena) {
    var str = cadena;
    var res = str.split(',');

    if (res.length == 2) {
        var x = res[0].trim();
        var y = res[1].trim();

        var valid_x = (x.match(/^-?\d+(?:\.\d+)?$/));
        var valid_y = (y.match(/^-?\d+(?:\.\d+)?$/));

        if (valid_x) {
            if (valid_y) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }

}

function buscarPorCoordenadas(cadena) {
    var str = cadena;
    var res = str.split(',');
    var x = res[0].trim();
    var y = res[1].trim();

    map.setCenter(new google.maps.LatLng(x, y));
    map.setZoom(16);
    marker.setPosition(new google.maps.LatLng(x, y));

    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var address = results[0]['formatted_address'];
            openInfoWindowAddress(address, marker);
            llenarTextosByCoordenadas(results, marker.getPosition());
            //console.log('searchDireccion:'+JSON.stringify(results));
        }
    });

}

function openInfoWindowAddress(Addres, marker) {
    console.log('geo..');
    infoWindow.setContent([
        Addres
    ].join(''));
    infoWindow.open(map, marker);
}

/***************************************************
 METODO PARA LLENAR CAMPOS POR LAS COORDENADAS
 ****************************************************/

function llenarTextosByCoordenadas(results, pos) {
    console.log(results[1]['address_components'][4].long_name.toUpperCase());
    try {
        $('#txt_departamento').val(results[1]['address_components'][4].long_name.toUpperCase());
    } catch (err) {
        $('#txt_departamento').val('');
    }

    try {
        $('#txt_provincia').val(results[1]['address_components'][3].long_name.toUpperCase());
    } catch (err) {
        $('#txt_provincia').val('');
    }

    try {
        $('#txt_distrito').val(results[1]['address_components'][2].long_name.toUpperCase());
    } catch (err) {
        $('#txt_distrito').val('');
    }
    /*
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
     */
    $('#inputCoordX').val(pos.lng());
    $('#inputCoordY').val(pos.lat());

}

function changeXY() {
    var myLat = document.getElementById("inputCoordX").value;
    var myLong = document.getElementById("inputCoordY").value;

    if (!isNaN(myLat) && !isNaN(myLong) && myLat != '' && myLong != '') {
        $("#divMapaCoordenadasXY").show();

        var coordes = new google.maps.LatLng(myLat, myLong);

        var mapOptions = {
            center: coordes,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }

        var mapCoor = new google.maps.Map(document.getElementById("divMapCoordenadas"), mapOptions);
        mapCoor.setZoom(15);
        var marker = new google.maps.Marker({map: mapCoor, position: coordes});

        myLatLng = [$("#inputCoordY").val(), $("#inputCoordX").val()];

        var codigoCentral = getBuscarArea(myLatLng);
        console.log(codigoCentral);
        var formData = new FormData();

        formData.append('codigoCentral', codigoCentral);
        formData.append('latitud', $("#inputCoordX").val());
        formData.append('longitud', $("#inputCoordY").val());

        $.ajax({
            data: formData,
            url: "pqt_obtCentralPorCodigo",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                $('#selectCentral').val(data.idCentral).trigger('change');
                changueCentral();
                console.log(data.idCentral);
            } else if (data.error == 1) {
                mostrarNotificacion('error', 'Error', 'No se inserto el Plan de obra:' + data.msj);
            }
        });


        // 		if(codigoCentral != null){
        // 			var formData = new FormData();
        //             formData.append('codigoCentral', codigoCentral);
        // 			formData.append('latitud' , myLat);
        // 			formData.append('longitud', myLong);
        //             $.ajax({
        // 		        data: formData,
        // 		        url: "pqt_obtCentralPorCodigo",
        // 		        cache: false,
        // 	            contentType: false,
        // 	            processData: false,
        // 	            type: 'POST'
        // 		  	})
        // 			  .done(function(data) {  
        // 				    	data = JSON.parse(data);
        // 			    	if(data.error == 0){

        //                           $('#selectCentral').val(data.idCentral).trigger('change');
        // 	                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
        //                           changueCentral();
        //                           console.log("ENTRO1");
        //                           console.log(data.idCentral);
        // 					}else if(data.error == 1){
        // 						mostrarNotificacion('error','Error','No se inserto el Plan de obra:'+data.msj);
        // 					}
        // 		  	  })
        // 		  	  .fail(function(jqXHR, textStatus, errorThrown) {
        // 		  		mostrarNotificacion('error','Error','Comuniquese con alguna persona a cargo :');
        // 		  	  })
        // 		  	  .always(function() {

        // 		  	});
        // 		}else{console.log("ENTRO2222");
        // 			$('#selectCentral').val('').trigger('change');
        //                 $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
        //                 changueCentral();
        //   			alert("No hay codigo para el area seleccionada");
        // 		}

    }

}

function getBuscarArea(arrayCordenadas) {
    var i_selec = null;
    for (i = 0; i < JSON_COORDENADAS['features'].length; i++) {
        result = matBuscarArea(arrayCordenadas, JSON_COORDENADAS['features'][i]['geometry']['coordinates'][0][0]);
        if (result == true) {
            i_selec = i;
            break;
        }
    }

    if (i_selec != null) {
        cod_nod = JSON_COORDENADAS['features'][i_selec]['properties']['MDF'];
    } else {
        cod_nod = null;
    }
    console.log("getBuscarArea.cod_nod: |" + cod_nod.trim() + "|");
    return cod_nod.trim();
}

function matBuscarArea(point, vs) {
    var x = point[0], y = point[1];
    var inside = false;
    for (var i = 0, j = vs.length - 1; i < vs.length; j = i++) {
        var xi = vs[i][0], yi = vs[i][1];
        var xj = vs[j][0], yj = vs[j][1];
        var intersect = ((yi > y) != (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
        if (intersect)
            inside = !inside;
    }
    return inside;
}

var JSON_COORDENADAS = Coordenadas;
$(document).ready(function () {
    console.log("ready!");
    var geocoder = new google.maps.Geocoder();
    var map;
    var latitude = -12.0965634; // YOUR LATITUDE VALUE
    var longitude = -77.0276785; // YOUR LONGITUDE VALUE


    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;
        });
    }

    var myLatLng = {
        lat: latitude, lng: longitude};

    map = new google.maps.Map(document.getElementById('divMapCoordenadas'), {
        center: myLatLng,
        zoom: 14
    });

// Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

// Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function () {
        searchBox.setBounds(map.getBounds());
    });

// Listen for the event fired when the user selects a prediction and retrieve
// more details for that place.
    searchBox.addListener('places_changed', function () {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

// For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function (place) {

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });

// Update lat/long value of div when anywhere in the map is clicked    
    google.maps.event.addListener(map, 'click', function (event) {
        myLatLng = [event.latLng.lng(), event.latLng.lat()];

        $("#inputCoordX").val(event.latLng.lat());
        $("#inputCoordY").val(event.latLng.lng());

        console.log('Ejecutar getBuscarArea...');
        var codigoCentral = getBuscarArea(myLatLng);
        console.log("COD_CENTRAL: " + codigoCentral);

        if (codigoCentral != null) {
            var formData = new FormData();
            formData.append('codigoCentral', codigoCentral);
            formData.append('latitud', event.latLng.lat());
            formData.append('longitud', event.latLng.lng());
            $.ajax({
                data: formData,
                url: "pqt_obtCentralPorCodigo",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
                    .done(function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {

                            $('#selectCentral').val(data.idCentral).trigger('change');
                            changueCentral();
                            console.log(data.idCentral);
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', 'No se inserto el Plan de obra:' + data.msj);
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :');
                    })
                    .always(function () {

                    });
        } else {
            $('#selectCentral').val('').trigger('change');
            changueCentral();
            alert("No hay codigo para el area seleccionada");
        }

    });

    var marker;

// Create new marker on double click event on the map
    google.maps.event.addListener(map, 'click', function (event) {
        if (marker) {
            marker.setPosition(event.latLng);
        } else {
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
                title: event.latLng.lat() + ', ' + event.latLng.lng(),
                draggable: true,
                animation: google.maps.Animation.DROP
            });
        }

        geocoder.geocode({
            'latLng': marker.getPosition()}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var pos = marker.getPosition();
                llenarTextosByCoordenadas(results, pos)
                var address = results[0]['formatted_address'];
                openInfoWindowAddress(address, marker);
            }
        });
        var pos = marker.getPosition();
        map.setCenter(new google.maps.LatLng(pos.lat(), pos.lng()));

        google.maps.event.addListener(marker, 'dragend', function () {
            var pos = marker.getPosition();
            geocoder.geocode({'latLng': pos}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    llenarTextosByCoordenadas(results, pos)
                    var address = results[0]['formatted_address'];
                    openInfoWindowAddress(address, marker);
                }
            });
            map.setCenter(new google.maps.LatLng(pos.lat(), pos.lng()));
        });

    });
});
///////////////////////////////////////////////////////////////////////////////////////////////////
function validarFasePorProyecto() {
    var fase = $("#selectFase option:selected").text();
    var idFase = $("#selectFase option:selected").val();
    var idProyecto = $("#selectProy").val();
    var idSubProyecto = $("#selectSubproy").val();
    console.log("fase          " + fase);
    console.log("idProyecto    " + idProyecto);
    console.log("idSubProyecto " + idSubProyecto);

    if (fase != "" && idProyecto != "" && idSubProyecto != "") {
        var formData = new FormData();
        formData.append('fase', fase);
        formData.append('idProyecto', idProyecto);
        formData.append('idSubProyecto', idSubProyecto);
        formData.append('idFase', idFase);
        $.ajax({
            data: formData,
            url: "pqt_permitirCrearItemPlan",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
        })
                .done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        if (data.configuracion_fg == 1) {

                            if (data.permitir_continuar_fg == 1) {
                                swal({
                                    title: "ATENCION!!",
                                    text: "Supero el limite de creacion de itemplan para el SUB!",
                                    type: "warning"
                                });
                                $("#btnSave").attr("disabled", true);
                                $('#selectSubproy').val('');
                                $('#selectSubproy').change();
                            } else {
                                $("#btnSave").attr("disabled", false);
                            }
                        } else {
                            $("#btnSave").attr("disabled", false);
                        }
                    } else if (data.error == 1) {
                        swal({
                            title: "Error",
                            text: 'No se inserto el Plan de obra:' + data.msj,
                            type: "error"
                        });
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    swal({
                        title: "Error",
                        text: 'Comuniquese con alguna persona a cargo :',
                        type: "error"
                    });
                })
                .always(function () {

                });

    }

}