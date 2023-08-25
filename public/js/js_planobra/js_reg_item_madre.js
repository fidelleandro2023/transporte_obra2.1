/* global google, Coordenadas, global_marcadores, global_info_marcadores, goblal_icon_url_pendiente, goblal_icon_url_terminado */

function filtrarSubProyecto() {
    $.ajax({
        type: 'GET',
        url: 'getSubProyectoItemMadre'
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            $('#cmbSubProyecto').html(data.cmbSubproyecto);
        } else {
            mostrarNotificacion('warning', 'Verificar', data.msj);
        }
    });
}

$("#cmbProyecto").change(function () {
    $("#selectPrioridad").val('0');
    $("#selectPrioridad").select2("val", "0");
});

$("#cmbSubProyecto").change(function () {
    $("#selectPrioridad").val('0');
    $("#selectPrioridad").select2("val", "0");
});


function regItemPlanMadre() {
    objReg = {};
    var idProyecto = 4;
    var idSubProyecto = $('#cmbSubProyecto option:selected').val();
    var inputCoordX = $('#inputCoordX').val();
    var inputCoordY = $('#inputCoordY').val();
    var selectEmpresaColab = $('#selectEmpresaColab option:selected').val();
    var textMonto = $('#textMonto').val();
    var selectPrioridad = $('#selectPrioridad option:selected').val();
    var fileuploadOP = $('#fileuploadOP')[0].files[0];
    var idpep = $('#idpep').val();
    var nomMadre = $('#textNombreMadre').val();

    //--- Datos new --//
    var txt_departamento = $('#txt_departamento').val();
    var txt_provincia = $('#txt_provincia').val();
    var txt_distrito = $('#txt_distrito').val();
    var fecRecepcion = $('#fecRecepcion').val();
    var inputNomCli = $('#inputNomCli').val();
    var inputNumCar = $('#inputNumCar').val();
    var selectAno = $('#selectAno').val();
    //--- Datos new --//

    if ($("#presupuesto_pep").val() == '1') {
        mostrarNotificacion('warning', 'Verificar', 'No se puede registrar CON PRIORIDAD, no cuenta con presupuesto');
        return false;
    }
    if (idSubProyecto == null || idSubProyecto == '') {
        mostrarNotificacion('warning', 'Verificar', 'Ingrese los datos necesarios');
        return false;
    }
    if (nomMadre == null || nomMadre == '') {
        mostrarNotificacion('warning', 'Verificar', 'Ingrese nombre');
        return false;
    }
    if (textMonto == null || textMonto == '') {
        mostrarNotificacion('warning', 'Verificar', 'Ingrese monto');
        return false;
    }
    if (selectPrioridad == null || selectPrioridad == '0') {
        mostrarNotificacion('warning', 'Verificar', 'Selecciones prioridad');
        return false;
    }
    $('#btnSaveAll').prop('disabled', true);
    var formData = new FormData();
    formData.append("nomMadre", nomMadre);
    formData.append("idProyecto", idProyecto);
    formData.append("idSubProyecto", idSubProyecto);
    formData.append("inputCoordX", inputCoordX);
    formData.append("inputCoordY", inputCoordY);
    formData.append("selectEmpresaColab", selectEmpresaColab);
    formData.append("textMonto", textMonto);
    formData.append("selectPrioridad", selectPrioridad);
    formData.append("fileuploadOP", fileuploadOP);
    formData.append("idpep", idpep);
    formData.append("txt_departamento", txt_departamento);
    formData.append("txt_provincia", txt_provincia);
    formData.append("txt_distrito", txt_distrito);
    formData.append("fecRecepcion", fecRecepcion);
    formData.append("inputNomCli", inputNomCli);
    formData.append("inputNumCar", inputNumCar);
    formData.append("selectAno", selectAno);


    $.ajax({
        type: 'POST',
        url: 'regItemPlanMadre',
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
                title: 'Se ha creado con &eacute;xito el ITEMPLAN MADRE : ' + data.itemplanM,
                text: 'Registro correcto',
                showConfirmButton: true,
                backdrop: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                $('#contTablaItems').html(data.tbItemMadre);
                initDataTable('#data-table');
                location.reload();
            });
        } else {
            $('#btnSaveAll').prop('disabled', false);
            mostrarNotificacion('warning', 'Verificar', data.msj);
        }
    });
}

function cambiar2() {
    var pdrs = document.getElementById('fileuploadOP').files[0].name;
    document.getElementById('infoOP').innerHTML = pdrs;
}



$("#selectPrioridad").change(function () {

    $("#presupuesto_pep").val(2);
    if ($(this).val() === '1') {
        if ($("#textMonto").val() === null || $("#textMonto").val() === '') {
            $(this).val('0');
            mostrarNotificacion('warning', 'Mensaje', 'Primero debe rellenar el Monto');
            return false;
        }
        if ($("#cmbSubProyecto").val() === null || $("#cmbSubProyecto").val() === '') {
            $(this).val('0');
            mostrarNotificacion('warning', 'Mensaje', 'Primero debe selecionar el subporyecto');
            return false;
        } else {
            verificar_pep($("#textMonto").val(), $("#cmbSubProyecto").val());
        }
    } else {
        $("#idpep").val('');
    }
});


function verificar_pep(textMonto, idSubProyecto) {
    $.ajax({
        type: 'POST',
        url: 'getPepItemplanMadre',
        data: {textMonto: textMonto, cmbSubProyecto: idSubProyecto}
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data);
        if (data.error) {
            if (data.pep) {
                $("#presupuesto_pep").val(2);
                $("#idpep").val(data.pep);
                mostrarNotificacion('success', 'PEP con prespuesto', 'PEP: ' + data.pep);
            } else {
                $("#idpep").val('');
                $("#presupuesto_pep").val(1);
                mostrarNotificacion('success', 'Mensaje', 'PEP sin prespuesto');
            }
        }
    });
}


$("#textMonto").keyup(function () {
    if ($("#selectPrioridad").val() === '1') {
        $("#selectPrioridad").select2("val", "0");
        $("#idpep").val('');
//        $('#selectPrioridad').val('').trigger('chosen:updated');
    } else {
    }
});



////////////////////////////////////////
/************METODOS GOOGLE MAP**************/
var marker = null;
var map = null;
var center = null;
var infoWindow = null;
var marcadores = null;
var infoWindowMarcadores = null;
//

function llenarMarcadores() {
    console.log(markers);
    infoWindowMarcadores = new google.maps.InfoWindow();
    for (i = 0; i < markers.length; i++) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);

        marcadores = new google.maps.Marker({
            icon: ((markers[i][3] == 4 || markers[i][3] == 9 || markers[i][3] == 6) ? goblal_icon_url_terminado : goblal_icon_url_pendiente),
            position: position,
            map: map,
            title: markers[i][0]
        });
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
            alert()
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

function llenarTextosByCoordenadas(results) {
    console.log(results.address_components);
    try {
        $('#txt_departamento').val(results[4].long_name.toUpperCase());
    } catch (err) {
        $('#txt_departamento').val('');
    }

    try {
        $('#txt_provincia').val(results[3].long_name.toUpperCase());
    } catch (err) {
        $('#txt_provincia').val('');
    }

    try {
        $('#txt_distrito').val(results[2].long_name.toUpperCase());
    } catch (err) {
        $('#txt_distrito').val('');
    }


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
    console.log("getBuscarArea.cod_nod: |" + cod_nod + "|");
    return cod_nod;
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

    $('#btnSaveAll').prop('disabled', false);
    filtrarSubProyecto();
    $('#textMonto').numeric();
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

    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);


    map.addListener('bounds_changed', function () {
        searchBox.setBounds(map.getBounds());
    });


    searchBox.addListener('places_changed', function () {
        var places = searchBox.getPlaces();
        console.log(places[0].geometry);
        console.log(places[0].geometry.location.lat());
        console.log(places[0].geometry.location.lng());
        if (places.length == 0) {
            return;
        }

        var bounds = new google.maps.LatLngBounds();
        places.forEach(function (place) {

            if (place.geometry.viewport) {
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
        llenarTextosByCoordenadas(places[0].address_components);
    });

    var geocoder = new google.maps.Geocoder();

    google.maps.event.addListener(map, 'click', function (event) {
        $('body').loading({
            message: 'Espere por favor...'
        });

        geocoder.geocode({'latLng': event.latLng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                console.log(results[0].address_components);
                llenarTextosByCoordenadas(results[0].address_components)
            }
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

function  modalItemplanHijos(itemplanMadre) {
    $('body').loading({
        message: 'Espere por favor...'
    });

    $.ajax({
        type: 'POST',
        'url': 'lstItemplanHijo',
        data: {itemplanMadre: itemplanMadre},
        'async': false
    }).done(function (data) {
        var data = JSON.parse(data);
        if (data.error == 0) {

            $('body').loading('destroy')
            $('#contTablaItemsHijos').html(data.tablaItemHijos);
            $("#modalItemplanHijos").modal('show');

            initDataTable('#data-table2');
        } else if (data.error == 1) {
            mostrarNotificacion('error', 'Hubo problemas al mostrar datos solicitados');
        }
    });


}