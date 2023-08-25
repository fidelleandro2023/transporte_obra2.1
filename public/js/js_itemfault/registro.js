/* global google, Coordenadas, global_marcadores, global_info_marcadores, goblal_icon_url_pendiente, goblal_icon_url_terminado */

$(document).ready(function () {
    $('#div_central').hide();
    $('#div_averia').hide();
    $('#div_remedy').hide();
    $("#aDetInci").prop("disabled", true)
    $('#inputPrecioU').numeric();
    $('#inputCantidad').numeric();
    $('#inputTotal').numeric();
    init();
});

function changeModal() {
    console.log($('#inputMontoMO').val());
    if ($('#inputMontoMO').val() === '') {
        $('#inputPrecioU').val('');
        $('#inputCantidad').val('');
        $('#inputTotal').val('');
    } else {
        $('#inputPrecioU').val();
        $('#inputCantidad').val();
        $('#inputTotal').val();
        $('#modalPxQ').modal('show');
    }
}

$("#inputPrecioU").keyup(function (event) {
    if ($("#inputCantidad").val() === null) {

    } else {
        $("#inputTotal").val($("#inputCantidad").val() * $("#inputPrecioU").val());
    }
});

$("#inputCantidad").keyup(function (event) {
    if ($("#inputPrecioU").val() === null) {

    } else {
        $("#inputTotal").val($("#inputCantidad").val() * $("#inputPrecioU").val());
    }
});

//////////// CODIGO NUEVO ////////////

function changeServicio() {
    console.log($('#selectServicio').val());
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'ajaxServicioElemento',
        data: {idServicio: $('#selectServicio').val()},
        'async': false
    }).done(function (data) {
        $('#selectElementoServicio').empty();
        $('#selectElementoServicio').append(data);
    });
}

//selectEvento
$("#selectEvento").change(function () {
    console.log($(this).val());
    if ($(this).val() === '1') {
        $("#div_monto_mat").show();
    } else {
        $('#inputMontoMAT').val('');
        $("#div_monto_mat").hide();
    }
});


function changeElemento() {
    $('#identificacion_b').val($('select[name="selectElementoServicio"] option:selected').text());
    console.log($('select[name="selectElementoServicio"] option:selected').text());
}

function changeEvento() {
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'ajaxSubEvento',
        data: {idEvento: $('#selectEvento').val()},
        'async': false
    }).done(function (data) {
        corte($('#selectEvento').val());
        $('#selectSubEvento').empty();
        $('#selectSubEvento').append(data);
    });
}

function corte(id) {
    console.log(id);
    if (id === '1') {
        $('#selectCorte').prop('disabled', false);
        console.log('1');
    } else {
        console.log('2');
        $('#selectCorte').prop('disabled', true);
        $("#selectCorte").select2("val", "2");
    }
}

function changeAveria() {
    console.log($('#selectSubEvento').val());
    if ($('#selectSubEvento').val() === '1') {
        $('#div_averia').show();
        $('#div_remedy').show();

        $("#aDetInci").prop("disabled", false)
    } else {
        $('#div_averia').hide();
        $('#div_remedy').hide();

        $("#aDetInci").prop("disabled", true)
    }
}

function saveItemfault() {
    //*** DATOS GENERALES ***//
    var selectServicio = $('#selectServicio').val();
    var selectElementoServicio = $('#selectElementoServicio').val();
    var identificacion = $('#identificacion_b').val() + ' - ' + $.trim($('#identificacion').val());
    var selectGerencia = $('#selectGerencia').val();
    var inputNombrePlan = $.trim($('#inputNombrePlan').val());
    var selectEvento = $('#selectEvento').val();
    var selectCorte = $('#selectCorte').val();
    var selectSubEvento = $('#selectSubEvento').val();
    var selectEmpresaColab = $('#selectEmpresaColab').val();
    var selectZonal = $('#selectZonal').val();
    var inputCoordX = $('#inputCoordX').val();
    var inputCoordY = $('#inputCoordY').val();
    var itemplan = $('#itemplan').val();
    var selectCentral = $('#selectCentral').val();
    var inputMontoMO = $('#inputTotal').val();
    var inputMontoMAT = $('#inputMontoMAT').val();
    //******** AVERIA ********//
    var remedy = $.trim($('#remedy').val());
    var inputFechaAveria = $('#inputFechaAveria').val();
    var inputHoraAveria = $('#inputHoraAveria').val();
    //*** DATOS TECNICOS ***//
    var inputUraInicial = $('#inputUraInicial').val();
    var inputUraFinal = $('#inputUraFinal').val();
    var textObservacion = $.trim($('#textObservacion').val());
    var inputCodigoInicial = $.trim($('#inputCodigoInicial').val());
    var inputCodigoFinal = $.trim($('#inputCodigoFinal').val());
    var inputImagenes = $.trim($('#inputImagenes').val());
    var inputBandejaInicial = $.trim($('#inputBandejaInicial').val());
    var inputBandejaFinal = $.trim($('#inputBandejaFinal').val());
    var inputFibraInicial = $.trim($('#inputFibraInicial').val());
    var inputFibraFinal = $.trim($('#inputFibraFinal').val());
    var inputPotenciaInicial = $.trim($('#inputPotenciaInicial').val());
    var inputPotenciaFinal = $.trim($('#inputPotenciaFinal').val());
    //*** DATOS DEL MODAL ***//
    var inputPrecioU = $.trim($('#inputPrecioU').val());
    var inputCantidad = $.trim($('#inputCantidad').val());
    var inputTotal = $.trim($('#inputTotal').val());


    if (selectServicio == "" || selectServicio == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese RED DE SERVICIO');
        $("#selectServicio").focus();
        return false;
    }
    if (selectElementoServicio == "" || selectElementoServicio == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese ELEMENTO DE RED DE SERVICIO');
        $("#selectElementoServicio").focus();
        return false;
    }
    if (identificacion == "" || identificacion == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese IDENTIFICACION DE LA RED DE SERVICIO');
        $("#identificacion").focus();
        return false;
    }
    if (selectGerencia == "" || selectGerencia == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese AREA/GERENCIA');
        $("#selectServicio").focus();
        return false;
    }
    if (inputNombrePlan == "" || inputNombrePlan == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese NOMBRE DE URA');
        $("#inputNombrePlan").focus();
        return false;
    }
    if (selectEvento == "" || selectEvento == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese EVENTO');
        $("#selectEvento").focus();
        return false;
    }
    if (selectCorte == "" || selectCorte == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese CORTE DE SERVICIO');
        $("#selectCorte").focus();
        return false;
    }
    if (selectSubEvento == "" || selectSubEvento == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese SUB EVENTO');
        $("#selectSubEvento").focus();
        return false;
    }
    if (selectSubEvento == '1' && remedy == "" || remedy == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese REMEDY');
        $("#remedy").focus();
        return false;
    }
//    if (selectSubEvento == '1' && inputFechaAveria == "" || inputFechaAveria == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese FECHA DE ORIGEN DE AVERIA');
//        $("#inputFechaAveria").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputHoraAveria == "" || inputHoraAveria == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese HORA DE ORIGEN DE AVERIA');
//        $("#inputHoraAveria").focus();
//        return false;
//    }
    if (selectEmpresaColab == "" || selectEmpresaColab == null) {
        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese EMPRESA COLABORADORA');
        $("#selectEmpresaColab").focus();
        return false;
    }

    if (inputMontoMO === '' || inputMontoMO === null) {
        swal('Mensaje', 'Ingrese monto de Mano de Obra', 'warning');
        $("#inputMontoMO").css("border", "1px solid red ");
        $("#inputMontoMO").css("border-radius", "10px");
        $("#inputMontoMO").focus();
        setTimeout(function () {
            $("#inputMontoMO").css("border", "");
        }, 3000);
        return false;
    }
    if ($("#selectEvento").val() === '1' && inputMontoMAT === '' || inputMontoMAT === null) {
        swal('Mensaje', 'Ingrese monto de Material', 'warning');
        $("#inputMontoMAT").css("border", "1px solid red");
        $("#inputMontoMAT").css("border-radius", "10px");
        $("#inputMontoMAT").focus();
        setTimeout(function () {
            $("#inputMontoMAT").css("border", "");
        }, 3000);
        return false;
    }
    //Datos tecnico 
//    if (selectSubEvento == '1' && inputUraInicial == "" || inputUraInicial == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese URA INICIAL');
//        $("#inputUraInicial").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && selectSubEvento == '1' && inputUraFinal == "" || inputUraFinal == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese URA FINAL');
//        $("#inputUraFinal").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && selectSubEvento == '1' && textObservacion == "" || textObservacion == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese OBSERVACIÓN');
//        $("#textObservacion").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputCodigoInicial == "" || inputCodigoInicial == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese CODIGO DE ODF INICIAL');
//        $("#inputCodigoInicial").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputCodigoFinal == "" || inputCodigoFinal == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese CODIGO DE ODF FINAL');
//        $("#inputCodigoFinal").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputImagenes == "" || inputImagenes == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese REGISTROS FOTOGRAFICOS');
//        $("#inputImagenes").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputBandejaInicial == "" || inputBandejaInicial == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese NRO DE BANDEJA INICIAL');
//        $("#inputBandejaInicial").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputBandejaFinal == "" || inputBandejaFinal == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese NRO DE BANDEJA FINAL');
//        $("#inputBandejaFinal").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputFibraInicial == "" || inputFibraInicial == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese NRO DE FIBRA INICIAL');
//        $("#inputFibraInicial").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputFibraFinal == "" || inputFibraFinal == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese NRO DE FIBRA FINAL');
//        $("#inputFibraFinal").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputPotenciaInicial == "" || inputPotenciaInicial == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese MEDIDAS DE POTENCIA INICIAL');
//        $("#inputPotenciaInicial").focus();
//        return false;
//    }
//    if (selectSubEvento == '1' && inputPotenciaFinal == "" || inputPotenciaFinal == null) {
//        alertValidacion('warning', 'VALIDACI&Oacute;N', 'Ingrese MEDIDAS DE POTENCIA FINAL');
//        $("#inputPotenciaFinal").focus();
//        return false;
//    } 
    else {
        console.log('Todo es bien');
        var formData = new FormData();
        formData.append("selectServicio", selectServicio);
        formData.append("selectElementoServicio", selectElementoServicio);
        formData.append("identificacion", identificacion);
        formData.append("selectGerencia", selectGerencia);
        formData.append("inputNombrePlan", inputNombrePlan);
        formData.append("selectEvento", selectEvento);
        formData.append("selectCorte", selectCorte);
        formData.append("selectSubEvento", selectSubEvento);
        formData.append("selectEmpresaColab", selectEmpresaColab);
        formData.append("remedy", remedy);
        formData.append("inputFechaAveria", inputFechaAveria);
        formData.append("inputHoraAveria", inputHoraAveria);
        formData.append("inputUraInicial", inputUraInicial);
        formData.append("inputUraFinal", inputUraFinal);
        formData.append("textObservacion", textObservacion);
        formData.append("inputCodigoInicial", inputCodigoInicial);
        formData.append("inputCodigoFinal", inputCodigoFinal);
        formData.append("inputBandejaInicial", inputBandejaInicial);
        formData.append("inputBandejaFinal", inputBandejaFinal);
        formData.append("inputFibraInicial", inputFibraInicial);
        formData.append("inputFibraFinal", inputFibraFinal);
        formData.append("inputPotenciaInicial", inputPotenciaInicial);
        formData.append("inputPotenciaFinal", inputPotenciaFinal);
        formData.append("inputImagenes", inputImagenes);
        formData.append("selectZonal", selectZonal);
        formData.append("inputCoordX", inputCoordX);
        formData.append("inputCoordY", inputCoordY);
        formData.append("itemplan", itemplan);
        formData.append("selectCentral", selectCentral);
        formData.append("inputMontoMO", inputMontoMO);
        formData.append("inputMontoMAT", inputMontoMAT);
        formData.append("inputPrecioU", inputPrecioU);
        formData.append("inputCantidad", inputCantidad);
        formData.append("inputTotal", inputTotal);

        swal({
            title: 'Mensaje',
            text: "Est\xE1 seguro de crear un ITEMFAULT, dar un click en SI para Continuar con la Creaci\xf3n o  NO para Cancelar",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, crear ITEMFAULT',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: 'ajaxSave',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function (data) {
                    data = JSON.parse(data);
                    console.log(data);
                    if (data.error == 0) {
                        swal({
                            type: 'success',
                            title: 'Se ha creado con &eacute;xito el ITEMFAULT ' + data.itemfaultnuevo,
                            text: 'Si usted desea ver el registro de Itemfault, lo puede hacer ingresando a la Bandeja de Cotizaci\xf3n.',
                            showConfirmButton: true,
                            backdrop: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        mostrarNotificacion('error', data.msj, ':D');
                    }
                });
            }
        });
    }

}



function alertValidacion(tipo, titulo, mensaje) {
    swal({
        title: titulo,
        text: mensaje,
        type: tipo
    });
}

////////////////////////////////////////
/************METODOS GOOGLE MAP**************/
var marker = null;
var map = null;
var center = null;
var infoWindow = null;
var marcadores = null;
var infoWindowMarcadores = null;
//
var markers = global_marcadores;
var infoWindowContent = global_info_marcadores;

function init() {

//    var mapdivMap = document.getElementById("contenedor_mapa");
//    center = new google.maps.LatLng(-12.0431800, -77.0282400);
//    var myOptions = {
//        zoom: 5,
//        center: center,
//        mapTypeId: google.maps.MapTypeId.ROADMAP
//    }
//    map = new google.maps.Map(document.getElementById("contenedor_mapa"), myOptions);
//    infoWindow = new google.maps.InfoWindow();
//    marker = new google.maps.Marker({
//        map: map,
//        title: "Tu posicion",
//        draggable: true,
//        animation: google.maps.Animation.DROP
//    });
//
//    var geocoder = new google.maps.Geocoder();
//    google.maps.event.addListener(marker, 'dragend', function () {
//        var pos = marker.getPosition();
//        geocoder.geocode({'latLng': pos}, function (results, status) {
//            if (status == google.maps.GeocoderStatus.OK) {
//                llenarTextosByCoordenadas(results, pos)
//                var address = results[0]['formatted_address'];
//                openInfoWindowAddress(address, marker);
//            }
//        });
//        map.setCenter(new google.maps.LatLng(pos.lat(), pos.lng()));
//    });
//
//    google.maps.event.addListener(map, 'click', function (event) {
//        marker.setMap(null);
//
//        marker = new google.maps.Marker({
//            position: event.latLng,
//            map: map,
//            title: "Tu posiciÃ³n",
//            draggable: true,
//            animation: google.maps.Animation.DROP
//        });
//
//        geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
//            if (status == google.maps.GeocoderStatus.OK) {
//                var pos = marker.getPosition();
//                llenarTextosByCoordenadas(results, pos)
//                var address = results[0]['formatted_address'];
//                openInfoWindowAddress(address, marker);
//            }
//        });
//        var pos = marker.getPosition();
//        map.setCenter(new google.maps.LatLng(pos.lat(), pos.lng()));
//
//        google.maps.event.addListener(marker, 'dragend', function () {
//            var pos = marker.getPosition();
//            geocoder.geocode({'latLng': pos}, function (results, status) {
//                if (status == google.maps.GeocoderStatus.OK) {
//                    llenarTextosByCoordenadas(results, pos)
//                    var address = results[0]['formatted_address'];
//                    openInfoWindowAddress(address, marker);
//                }
//            });
//            map.setCenter(new google.maps.LatLng(pos.lat(), pos.lng()));
//           
//        });
//    });


}

function llenarMarcadores() {
    console.log(markers);
    // Place each marker on the map  
    // Place each marker on the map  
    infoWindowMarcadores = new google.maps.InfoWindow();
    for (i = 0; i < markers.length; i++) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);

        marcadores = new google.maps.Marker({
            icon: ((markers[i][3] == 4 || markers[i][3] == 9 || markers[i][3] == 6) ? goblal_icon_url_terminado : goblal_icon_url_pendiente),
            position: position,
            map: map,
            title: markers[i][0]
        });

        // Add info window to marker    
        google.maps.addMarker(marcadores);
        google.maps.event.addListener(marcadores, 'click', (function (marcadores, i) {
            return function () {
                infoWindowMarcadores.setContent(infoWindowContent[i][0]);
                infoWindowMarcadores.open(map, marcadores);
            };
        })(marcadores, i));
    }

}

function searchDireccion() {
    console.log('searchDireccion');
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
    console.log('isCoordenada');
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
    console.log('buscarPorCoordenadas');
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

//***********************************************//
// METODO PARA LLENAR CAMPOS POR LAS COORDENADAS //
//***********************************************//

function llenarTextosByCoordenadas(results, pos) {

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

        if (codigoCentral != null) {
            var formData = new FormData();
            formData.append('codigoCentral', codigoCentral);
            formData.append('latitud', myLat);
            formData.append('longitud', myLong);
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
                            $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
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
            $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
            changueCentral();
            alert("No hay codigo para el area seleccionada");
        }

    }

}

function vincularItemplan(itmeplan) {
    $('#itemplan').val(itmeplan);
}

function changueCentral() {
    var central = $.trim($('#selectCentral').val());
    $.ajax({
        type: 'POST',
        'url': 'pqt_getZonalPO',
        data: {central: central},
        'async': false
    }).done(function (data) {
        var data = JSON.parse(data);
        if (data.error == 0) {
            console.log(data);
            $('#inputNombrePlan').val('');
            $('#inputNombrePlan').val($('#selectCentral option:selected').text());
            $('#selectZonal').html(data.listaZonal);
            $('#selectZonal').val(data.idZonalSelec).trigger('chosen:updated');
            $('#selectEmpresaColab').html(data.listaEECC);
            $('#selectEmpresaColab').val(data.idEmpresaColab).trigger('chosen:updated');
            $('#inputJefatura').val(data.jefatura);
            $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectZonal');
            $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectEmpresaColab');
            $('#formAddPlanobra').bootstrapValidator('revalidateField', 'inputNombrePlan');
        } else if (data.error == 1) {
            mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
        }
    });
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
    $('#inputMontoMO').numeric();
    $('#inputMontoMAT').numeric();
    console.log("ready!");
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
    infoWindow = new google.maps.InfoWindow();
//    llenarMarcadores();

    infoWindowMarcadores = new google.maps.InfoWindow();
    for (i = 0; i < markers.length; i++) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);

        marcadores = new google.maps.Marker({
            icon: ((markers[i][3] == 4 || markers[i][3] == 9 || markers[i][3] == 6) ? goblal_icon_url_terminado : goblal_icon_url_pendiente),
            position: position,
            map: map,
            title: markers[i][0]
        });

        // Add info window to marker    
        marcadores.setMap(map);

        google.maps.event.addListener(marcadores, 'click', (function (marcadores, i) {
            return function () {
                infoWindowMarcadores.setContent(infoWindowContent[i][0]);
                infoWindowMarcadores.open(map, marcadores);
            };
        })(marcadores, i));

//       marker = new google.maps.Marker({
//            position: myLatLng,
//            map: map,
//            title: latitude + ', ' + longitude
//        });
//        marker.setMap(map);
    }
    //
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
        console.log(places[0].geometry);
        console.log(places[0].geometry.location.lat());
        console.log(places[0].geometry.location.lng());
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
        var ptn = new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng());
        marker = new google.maps.Marker({
            position: ptn,
            map: map,
            title: places[0].geometry.location.lat() + ', ' + places[0].geometry.location.lng()
        });
        marker.setMap(map);
        click_marker(places[0].geometry.location.lat(), places[0].geometry.location.lng());
    });

// Update lat/long value of div when anywhere in the map is clicked    
    google.maps.event.addListener(map, 'click', function (event) {
        $('body').loading({
            message: 'Espere por favor...'
        });
        myLatLng = [event.latLng.lng(), event.latLng.lat()];

        $("#inputCoordX").val(event.latLng.lat());
        $("#inputCoordY").val(event.latLng.lng());

        console.log('Ejecutar getBuscarArea...');
        console.log(myLatLng);
        var codigoCentral = getBuscarArea(myLatLng);
        console.log(codigoCentral);

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
                        $('body').loading('destroy')
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#selectCentral').val(data.idCentral).trigger('change');
                            $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
                            changueCentral();
                            console.log(data.idCentral);
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', 'No se inserto el Plan de obra:' + data.msj);
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {

                        $('body').loading('destroy')
                        mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :');
                    })
                    .always(function () {

                    });
        } else {

            $('body').loading('destroy')
            $('#selectCentral').val('').trigger('change');
            $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
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
                title: event.latLng.lat() + ', ' + event.latLng.lng()
            });
        }


    });
});


function click_marker(lat, lng) {
    $('body').loading({
        message: 'Espere por favor...'
    });
    var LatLng = [lng, lat];

    $("#inputCoordX").val(lat);
    $("#inputCoordY").val(lng);

    console.log('Ejecutar getBuscarArea...');
    console.log(LatLng);
    var codigoCentral = getBuscarArea(LatLng);
    console.log(codigoCentral);

    if (codigoCentral != null) {
        var formData = new FormData();
        formData.append('codigoCentral', codigoCentral);
        formData.append('latitud', lat);
        formData.append('longitud', lng);
        $.ajax({
            data: formData,
            url: "pqt_obtCentralPorCodigo",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
        })
                .done(function (data) {
                    $('body').loading('destroy')
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#selectCentral').val(data.idCentral).trigger('change');
                        $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
                        changueCentral();
                        console.log(data.idCentral);
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Error', 'No se inserto el Plan de obra:' + data.msj);
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {

                    $('body').loading('destroy')
                    mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :');
                })
                .always(function () {

                });
    } else {

        $('body').loading('destroy')
        $('#selectCentral').val('').trigger('change');
        $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
        changueCentral();
        alert("No hay codigo para el area seleccionada");
    }

}

