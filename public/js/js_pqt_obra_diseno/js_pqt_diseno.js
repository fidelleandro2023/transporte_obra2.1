function recogeInsertar() {
    var itemTitle = $('#itemTitle').html();
    var arrayNamesInsert = $("input[name*='ptrInsert']");
    var arrayPTRExistentes = $("input[name*='ptrEdit']");

    var arrayPTRExistentesTemp = new Array(); // Para limpiar
    var arrayInsert = new Array(); // recoge lo colocado x usuario
    var arrayValidator = new Array(); // para verificar si hay duplicados escritos en los inputs
    //var verificador = 0;
    var validador1 = 0;

    // PRIMERA VALIDACION
    for (a = 0; a < arrayNamesInsert.length; a++) {
        if (arrayNamesInsert[a].value != '') {
            for (b = 0; b < arrayPTRExistentes.length; b++) {
                if (arrayNamesInsert[a].value == arrayPTRExistentes[b].value) {
                    validador1++;
                }
            }
        } else {

        }
    }

    if (validador1 == 0) {
        for (y = 0; y < arrayNamesInsert.length; y++) {
            var rowInsert = '';
            if (arrayNamesInsert[y].value != '') {
                rowInsert = arrayNamesInsert[y].value + "/" + arrayNamesInsert[y].dataset.item;
                arrayValidator.push(rowInsert);
                arrayInsert.push(arrayNamesInsert[y].value + "/" + arrayNamesInsert[y].dataset.item + "/" + arrayNamesInsert[y].dataset.subproyectoestacion + "/" + arrayNamesInsert[y].dataset.area);
            }
        }
        if (arrayValidator.length == arrayValidator.unique().length) {
            // console.log('no hay duplicados');
            var jsonInsert = JSON.stringify(arrayInsert);

            $.ajax({
                type: 'POST',
                'url': 'ptrToInsert',
                data: {
                    jsonNamesInsert: jsonInsert},
                'async': false
            }).done(function (data) {
                location.reload();
                if (data.error == 0) {
                    console.log("itemPlan: " + arrayNamesInsert[y].dataset.item);
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Hubo un problema.');
                }
            });
        } else {
            alert('Usted no puede ingresar una misma PTR dos veces.');
        }
    } else {
        alert('Usted ha ingresado ' + validador1 + ' PTR ya existente en este ITEMPLAN. Por favor vuelva a intentarlo.');
    }
}


var toog2 = 0;
var error = 0;
Dropzone.autoDiscover = false;
$("#dropzone6").dropzone({
    url: "insertFileEjec",
    type: 'POST',
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 30,
    maxFilesize: 3,
    dictResponseError: "Ha ocurrido un error en el server",

    complete: function (file) {
        if (file.status == "success") {
            error = 0;
        }
    },
    removedfile: function (file, serverFileName) {
        var name = file.name;
        var element;
        (element = file.previewElement) != null ?
                element.parentNode.removeChild(file.previewElement) :
                false;
        toog2 = toog2 - 1;
    },
    init: function () {
        this.on("error", function (file, message) {
            alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no sera tomado en cuenta');
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error = 1;
            // alert(message);
            this.removeFile(file);
        });


        var submitButton = document.querySelector("#btnAddEvi");
        myDropzone = this;

        submitButton.addEventListener("click", function () {
            myDropzone.processQueue();
        }
        );

        var concatEvi = '';
        // You might want to show the submit button only when 
        // files are dropped here:
        this.on("addedfile", function () {
            toog2 = toog2 + 1;
            // Show submit button here and/or inform user to click it.
        });

        this.on('complete', function () {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                if (error == 0) {
                    console.log(this.getUploadingFiles());
                    // $('#edi-evidencias').modal('toggle');
                }

            }
        });

        this.on("queuecomplete", function (file) {
            if (error == 0) {
                var last = concatEvi.substring(0, (concatEvi.length - 1));

                $.ajax({
                    'url': 'comprimirFilesEjec',
                    'async': false,
                    contentType: false,
                    processData: false,
                    type: 'POST'
                }).done(function (data) {
                    //console.log('222222222');
                    //console.log(data.error);

                    data = JSON.parse(data);
                    console.log(data);
                    if (data.error == 0) {

                        $('#contTabla').html(data.tablaAsigGrafo);
                        initDataTable('#data-table');

                        mostrarNotificacion('success', 'OperaciÃ³n Ã©xitosa.', 'Se registro correcamente!');
                    } else if (data.error == 1) {
                        console.log(data.error);
                    }
                })


                this.removeAllFiles(true);
                mostrarNotificacion('success', 'Archivo', 'Se subi&oacute; el archivo correctamente');
                //refreshTablaRuta();
            }
        });

        this.on("success", function (file, responseText) {//Trae el ID De la imagen insertada
            concatEvi += responseText + '_';
        });

    }
});

var componentGlob = null;
var jefaturaGob = null;
var cant_amplificadorGobal = null;
var cant_trobaGobal = null;
var inputGlobal = null;

var itemplanGlobal = null;
var idEstacionGlobal = null;
/*******MODIFICADO 11.12.2019 - czavalacas - Opcion Sin Licencia*******/
function abrirModalAsignarEntidades(component) {
    $("#divFinDePartida").hide();
    itemplanGlobal = $(component).attr('data-item');
    idEstacionGlobal = $(component).attr('data-id_estacion');
    hfSisegoFG = $(component).attr('data-sisegofg');
    $('#chbxExpediente').prop('checked', false);
    $('#chbxPlanoDiseno').prop('checked', false);
    $('#hfSisegoFG').val(hfSisegoFG);
    console.log("hfSisegoFG : " + hfSisegoFG);

    componentGlob = component;
    arrayEntidades = [];

    $.ajax({
        type: 'POST',
        url: 'getInLicPqt',
        data: {
            itemplan: itemplanGlobal,
            idEstacion: idEstacionGlobal
        }
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            //count_partida(itemplanGlobal);
            $('#contAmplificadores').html(data.inputAmTro);
            inputGlobal = data.input;
            arrayEntidades = data.arrayIdEntidades;
            if (arrayEntidades.length > 0) {
                $('#tituloModalEnt').html('EDITAR ENTIDADES');
            } else {
                $('#tituloModalEnt').html('ASIGNAR ENTIDADES');
            }
            $('#formEntidades').html(data.htmlEntidades);

            if (data.jefatura != null) {
                jefaturaGob = data.jefatura;
                if (data.jefatura != 'LIMA') {
                    $('#idPanelPlanoDiseno').css('display', 'none');
                } else {
                    $('#idPanelPlanoDiseno').css('display', 'block');
                }

                modal('modalEditEntidadesEjec');
            }

        } else {
            alert('error Interno intentelo de nuevo.');
        }
    });
}


function count_partida(itemplan) {

    $.ajax({
        type: 'POST',
        url: 'getCountPartida',
        data: {
            itemplan: itemplan
        }
    }).done(function (data) {
        data = JSON.parse(data);
        console.log('IVAAAAAAAAAN');
        console.log(data.info);
        if (parseInt(data.info) == 1) {
            $("#divFinDePartida").show();
        }

    });

}

function updatePartidas(itemplan, estacion, idProyecto) {
    console.log(itemplan);
    swal({
        title: 'Mensaje',
        text: 'Esta estación no se requiere.',
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        cancelButtonText: 'Cancelar'
    }).then(function () {
        $.ajax({
            type: 'POST',
            url: 'updatePartidaOP',
            data: {
                itemplan: itemplan, estacion: estacion, idProyecto: idProyecto
            }
        }).done(function (data) {
            data = JSON.parse(data);
            console.log('JOEL');
            console.log(data.error);
            if (data.error == 0) {
                swal({
                    title: 'Ejecucion exitosa',
                    text: data.msj,
                    type: 'success',
                    showCancelButton: false,
                    allowOutsideClick: false
                }).then(function () {
                    window.top.close();
                    parent.location.reload();
                });
            } else if (data.error == 1) {
                mostrarNotificacion('error', 'Error al liquidar el diseño!', data.msj);
            }
        });
    });
}

var arrayEntidades = [];

function agregarEntidades(idEntidad, disabled) {
    var cnt = 0;
    $.each(arrayEntidades, function (index, value) {
        if (value[0] == idEntidad) {
            arrayEntidades.splice(index, 1);
            cnt++;
            return false;
        }
    });

    if (cnt == 0) {
        arrayEntidades.splice(arrayEntidades.length, 0, [idEntidad, disabled]);
    }
    $('#btnAceptarEnt').attr("disabled", false);

}



function saveEntidades() {
    //modal('modalEditEntidadesEjec');
    //aprobarDiseno(componentGlob);
}

function habilitarAceptar2() {
    var comprobar = $('#fileExpedienteDiseno').val().length;
    if (comprobar > 0) {
        var file = $('#fileExpedienteDiseno').val()
        var ext = file.substring(file.lastIndexOf("."));
        if (ext == ".zip" || ext == ".rar") {
            var file_size = $('#fileExpedienteDiseno')[0].files[0].size;
            if (file_size > 52000000) {//pesmo minimo 3mb
                alert("Archivo no puede ser mayor a 50MB");
                $("#fileExpedienteDiseno").val(null);
                $('#btnAceptarEnt').attr("disabled", true);
                return false;
            } else {
                $('#btnAceptarEnt').attr("disabled", false);
            }
        } else {
            alert('Formato de archivo no valido. (Formatos Validos:.rar o .zip)');
            $("#fileExpedienteDiseno").val(null);
            $('#btnAceptarEnt').attr("disabled", true);
            return false;
        }
    } else {
        $('#btnAceptarEnt').attr("disabled", true);
        alert('Debe subir un archivo valido.');
        return false;
    }
}

$('#formRegDiseno')
        .bootstrapValidator({
            //container: '#mensajeForm',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            excluded: ':disabled',
            fields: {
                selectCentral: {
                    validators: {
                        container: '#mensajeNodoPrincipal',
                        notEmpty: {
                            message: '<p style="color:red">(*) campo obligatorio.</p>'
                        }
                    }
                },
                fileExpedienteDiseno: {
                    validators: {
                        container: '#mensajeFileExp',
                        notEmpty: {
                            message: '<p style="color:red">(*) Debe subir el archivo.</p>'
                        }
                    }
                }
                // inputNombrePlan: {
                //     validators: {
                //         container   : '#mensajeNombrePlan',
                //         notEmpty    :   {
                //                             message: '<p style="color:red">(*) Debe ingresar nombre.</p>'
                //                         }
                //     }

            }
        }).on('success.form.bv', function (e) {
    e.preventDefault();
    if (arrayEntidades.length > 0) {
        $.ajax({
            type: 'POST',
            url: 'validarAprobarDiseno',
            data: {itemplan: itemplanGlobal,
                idEstacion: idEstacionGlobal}
        }).done(function (data) {
            data = JSON.parse(data);
            //console.log('arrayEntidades:'+arrayEntidades.length);
            if (data.error == 0) {
                swal({
                    title: 'Esta seguro de Ejecutar el Dise&#241o?',
                    text: 'Asegurese de validar la informacion seleccionada!',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    confirmButtonText: 'Si, Ejecutar el Dise&#241o!',
                    cancelButtonClass: 'btn btn-secondary'
                }).then(function () {
                    // var form_data = new FormData();  
                    // form_data.append('archivoExpediente', file2);
                    // form_data.append('item', itemplan);
                    // form_data.append('idEstacion', idEstacion);
                    // form_data.append('cantTroba', cant_trobaGobal);
                    // form_data.append('cantAmplificador', cant_amplificadorGobal);
                    // form_data.append('flgExpediente', (flgExpediente == true ? 1 : 0));
                    // form_data.append('arrayIdEntidades', arrayEntidades);
                    // form_data.append('flgDisenoSirope', (flgDisenoSirope == true ? 1 : 0));

                    // formData.append('nodoPrincipal', $('#selectCentral  option:selected').text());
                    // formData.append('nodoRespaldo' , $('#selectCentral2 option:selected').text());

                    if (inputGlobal != null) {
                        if (inputGlobal == 1) {
                            //cant_amplificadorGobal = $.trim($('#cant_amplificador').val());
                            cant_amplificadorGobal = 1;//hahrcodeamos 1 para que no afecte el flujo cambio de reemplazo por archivo czavalacas 30.06.2019
                            if (cant_amplificadorGobal == null || cant_amplificadorGobal == '') {
                                mostrarNotificacion('error', 'Debe ingresar el nro. de amplificador', 'Datos para PO Autom&aacute;tico');
                                return;
                            }
                        } else {
                            //cant_trobaGobal = $.trim($('#cant_troba').val());
                            cant_trobaGobal = 1;//hahrcodeamos 1 para que no afecte el flujo cambio de reemplazo por archivo czavalacas 30.06.2019
                            if (cant_trobaGobal == null || cant_trobaGobal == '') {
                                mostrarNotificacion('error', 'Debe ingresar el nro. de troba', 'Datos para PO Autom&aacute;tico');
                                return;
                            }
                        }
                    }

                    var input2File = document.getElementById('fileExpedienteDiseno');
                    var file2 = input2File.files[0];
                    itemplan = itemplanGlobal;
                    idEstacion = idEstacionGlobal;
                    var flgExpediente = true;//al quitar los check hardcodeamos true para no afectar el flujo
                    var flgDisenoSirope = true;//al quitar los check hardcodeamos true para no afectar el flujo
                    dataJson = {};

                    //dataJson.archivoExpediente         = file2;
                    dataJson.itemplan = itemplan;
                    dataJson.idEstacion = idEstacion;
                    dataJson.cantTroba = cant_trobaGobal;
                    dataJson.cantAmplificador = cant_amplificadorGobal;
                    dataJson.flgExpediente = (flgExpediente == true ? 1 : 0);
                    dataJson.flgDisenoSirope = (flgDisenoSirope == true ? 1 : 0);

                    dataJson.nodo_principal = $('#selectCentral  option:selected').text();
                    dataJson.nodo_respaldo = $('#selectCentral2 option:selected').text();
                    dataJson.facilidades_de_red = $('#inputFacRed').val();
                    dataJson.cant_cto = $('#inputCantCTO').val();

                    dataJson.metro_tendido_aereo = $('#inputMetroTenAereo').val();
                    dataJson.metro_tendido_subterraneo = $('#inputMetroTenSubt').val();
                    dataJson.metors_canalizacion = $('#inputMetroCana').val();
                    dataJson.cant_camaras_nuevas = $('#cantCamaNue').val();
                    dataJson.cant_postes_nuevos = $('#inputPostNue').val();
                    dataJson.cant_postes_apoyo = $('#inputCantPostApo').val();
                    dataJson.cant_apertura_camara = $('#inputCantAperCamara').val();

                    dataJson.requiere_seia = $('#selectRequeSeia option:selected').val();
                    dataJson.requiere_aprob_mml_mtc = $('#selectRequeAproMmlMtc option:selected').val();
                    dataJson.requiere_aprob_inc = $('#selectRequeAprobINC option:selected').val();
                    dataJson.duracion = $('#inputDias').val();
                    dataJson.id_tipo_diseno = $('#cmbTipoDiseno option:selected').val();

                    dataJson.arrayIdEntidades = arrayEntidades;

                    dataJson.costo_materiales = $('#inputCostoMat').val();
                    dataJson.costo_mano_obra = $('#inputCostMo').val();
                    ;
                    dataJson.costo_diseno = $('#inputCostoDiseno').val();
                    dataJson.costo_expe_seia_cira_pam = $('#cmbMontoEIA option:selected').val();
                    dataJson.costo_adicional_rural = $('#inputCostoAdicZona').val();
                    dataJson.costo_total = $('#inputCostoTotal').val();
                    dataJson.comentario = $('#textareaComentario').val();
                    dataJson.lengthaArrayIdEntidades = arrayEntidades.length;
                    dataJson.hfSisegoFG = $('#hfSisegoFG').val();

                    var otActualizacion = 0;
                    if ($('#idOTActualizacion').is(':checked')) {
                        otActualizacion = 1;
                    }
                    dataJson.otActualizacion = otActualizacion;

                    var form_data2 = new FormData();
                    form_data2.append('archivoExpediente', file2);
                    form_data2.append('dataJson', JSON.stringify(dataJson));
                    //form_data2.append('a', '');

                    console.log('La data es:' + JSON.stringify(dataJson));
                    $.ajax({
                        type: 'POST',
                        url: 'ejecutarPqtDiseno',
                        async: false,
                        data: form_data2,
                        cache: false,
                        contentType: false,
                        processData: false
                    }).done(function (data) {
                        console.log('Paso Done ' + data);
                        var data = JSON.parse(data);
                        if (data.error == 0) {
                            swal({
                                title: 'Ejecucion exitosa',
                                text: data.msj,
                                type: 'success',
                                showCancelButton: false,
                                allowOutsideClick: false
                            }).then(function () {
                                window.top.close();
                                parent.location.reload();
                            });
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error al liquidar el diseño!', data.msj);
                        }
                    })
                            .fail(function (jqXHR, textStatus, errorThrown) {
                                console.log('Fallo');
                                mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                            });
                    /*$.ajax({
                     type: 'POST',
                     'url': 'ejecutarPqtDiseno',
                     data: form_data2,
                     cache: false,
                     contentType: false,
                     processData: false
                     }).done(function (data) {
                     console.log('Paso Done');
                     var data = JSON.parse(data);
                     if (data.error == 0) {
                     mostrarNotificacion('success', 'Operaci&#243n exitosa.', data.msj);
                     arrayEntidades = [];
                     } else if (data.error == 1) {
                     mostrarNotificacion('error', 'Error al liquidar el diseño!', data.msj);
                     }
                     })
                     .fail(function (jqXHR, textStatus, errorThrown) {
                     console.log('Fallo');
                     mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                     })
                     .always(function () {
                     console.log('Always');
                     });*/
                });




            } else {
                mostrarNotificacion('error', 'NO SE PUEDE APROBAR', data.msj);
            }

        });
    } else {
        mostrarNotificacion('error', 'ALERTA!', 'Debe Seleccionar Al menos una Entidad');
    }
});

// function aprobarDiseno(component) {
//     var itemplan   = $(component).attr('data-item');
//     var idEstacion = $(component).attr('data-id_estacion');
//     /* comentado pero harcodeado en la parte inferior..30.06.2019 czavalacas
//     var flgExpediente = document.getElementById("chbxExpediente").checked;
//     var flgDisenoSirope = document.getElementById("chbxPlanoDiseno").checked;
//     */
//     var id = itemplan+""+idEstacion;

//     $('#'+id).css('background-color', 'yellow');

//     if(itemPlanAnterior!=null && itemPlanAnterior!=id) {
//         $('#'+itemPlanAnterior).css('background-color', 'white');
//     }
//     itemPlanAnterior = id;

//     if(itemplan == null || itemplan == '') {
//         return;
//     }
//     /**archivo expediente disneo 30.06.2019 czavala**/
//     var flgExpediente = true;//al quitar los check hardcodeamos true para no afectar el flujo
//     var flgDisenoSirope = true;//al quitar los check hardcodeamos true para no afectar el flujo  
//     var input2File = document.getElementById('fileExpedienteDiseno');
//     var file2 = input2File.files[0];
//     /************************************************/

//     if(inputGlobal != null) {
//         if(inputGlobal == 1) {
//             cant_amplificadorGobal = $.trim($('#cant_amplificador').val());
//             if(cant_amplificadorGobal == null || cant_amplificadorGobal == '') {
//                 $('#mensajeAmplificador').html('<p style="color:red">(*) Debe ingresar Nro. Amplificadores.</p>');
//                 //mostrarNotificacion('error', 'Debe ingresar el nro. de amplificador', 'Datos para PO Autom&aacute;tico');
//                 return;
//             }
//         } else {
//             cant_trobaGobal = $.trim($('#cant_troba').val());
//             if(cant_trobaGobal == null || cant_trobaGobal == '') {
//                 $('#mensajeAmplificador').html('<p style="color:red">(*) Debe ingresar Nro. Troba.</p>');
//                 // mostrarNotificacion('error', 'Debe ingresar el nro. de troba', 'Datos para PO Autom&aacute;tico');
//                 return;
//             }
//         }
//     }

//     $.ajax({
//         type : 'POST',
//         url  : 'validarAprobarDiseno',
//         data : { itemplan   : itemplan,
//                  idEstacion : idEstacion }
//     }).done(function(data){
//         data = JSON.parse(data);
//         if (data.error == 0) {
//             swal({
//                 title: 'Esta seguro de Ejecutar el Dise&#241o?',
//                 text: 'Asegurese de validar la informacion seleccionada!',
//                 type: 'warning',
//                 showCancelButton: true,
//                 buttonsStyling: false,
//                 confirmButtonClass: 'btn btn-primary',
//                 confirmButtonText: 'Si, Ejecutar el Dise&#241o!',
//                 cancelButtonClass: 'btn btn-secondary'
//             }).then(function () {
//                 var form_data = new FormData();  
//                 form_data.append('archivoExpediente', file2);
//                 form_data.append('item', itemplan);
//                 form_data.append('idEstacion', idEstacion);
//                 form_data.append('cantTroba', cant_trobaGobal);
//                 form_data.append('cantAmplificador', cant_amplificadorGobal);
//                 form_data.append('flgExpediente', (flgExpediente == true ? 1 : 0));
//                 form_data.append('arrayIdEntidades', arrayEntidades);
//                 form_data.append('flgDisenoSirope', (flgDisenoSirope == true ? 1 : 0));

//                 $.ajax({
//                     type: 'POST',
//                     'url': 'ejecDiseno',
//                     data: form_data,
//                     cache: false,
//                     contentType: false,
//                     processData: false,
//                     xhr: function() {
//                         $('.easy-pie-chart').data('easyPieChart').update('0');
//                         $('#valuePie').html('0');
//                         $('#modalProgreso').modal('toggle');
//                         var xhr = $.ajaxSettings.xhr();
//                         xhr.upload.onprogress = function(e) {
//                             var progreso = Math.floor(e.loaded / e.total *100) + '%';                                
//                             console.log(progreso);
//                             $('.easy-pie-chart').data('easyPieChart').update(progreso);
//                             $('#valuePie').html(progreso);
//                         };
//                         return xhr;
//                     }
//                 }).done(function (data) {
//                     //console.log(data);
//                     var data = JSON.parse(data);
//                     if (data.error == 0) {
//                         $('#contTabla').html(data.tablaAsigGrafo);
//                         initDataTable('#data-table');
//                         mostrarNotificacion('success', 'Operaci&#243n exitosa.', data.msj);
//                         /**/
//                         arrayEntidades = [];
//                         $('#modalProgreso').modal('toggle');
//                     } else if (data.error == 1) {
//                         $('#modalProgreso').modal('toggle');
//                         mostrarNotificacion('error', 'Error al liquidar el diseÃƒÂ±o!', data.msj);
//                     }
//                 })
//                 .fail(function (jqXHR, textStatus, errorThrown) {
//                     $('#modalProgreso').modal('toggle');
//                     mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
//                 })
//                 .always(function () {
//                 });
//             });




//         } else {
//             mostrarNotificacion('error', 'NO SE PUEDE APROBAR', data.msj);
//         }
//     });
// }
/******************************************/

function progress(e) {
    console.log('progress');
    if (e.lengthComputable) {
        var max = e.total;
        var current = e.loaded;

        var Percentage = (current * 100) / max;
        console.log(Percentage);


        if (Percentage >= 100)
        {
            // process completed  
        }
    }
}

function getcalculos() {
    var costoMat = $('#inputCostoMat').val();
    var costoMo = $('#inputCostMo').val();
    var costoDise = $('#inputCostoDiseno').val();
    var costoExpe = $('#cmbMontoEIA option:selected').val();
    var costoAdic = $('#inputCostoAdicZona').val();
    var inputCostoTotal = Number(costoMat) + Number(costoMo) + Number(costoDise) + Number(costoExpe) + Number(costoAdic);
    $('#inputCostoTotal').val(inputCostoTotal.toFixed(2));
}

function openModalDetailDiseno(element) {
    var itemplan = element.data('itemplan');
    var idestacion = element.data('idestacion');
    var has_cotizacion = element.data('has_cotizacion');
    if (has_cotizacion == 0) {
        $('#infoCotizacionSisego').css('display', 'block');
        $('#divFormularioSisegoInfo').css('display', 'none');
        modal('modalEditEntidadesInfo');
    } else {
        $('#infoCotizacionSisego').css('display', 'none');
        $('#divFormularioSisegoInfo').css('display', 'block');
        $.ajax({
            type: 'POST',
            url: 'pqt_getDisenoEjecutado',
            data: {itemplan: itemplan,
                idestacion: idestacion}
        })
                .done(function (data) {
                    data = JSON.parse(data);
                    var infoDiseno = data.info;
                    $('#infoNodoPrincipal').val(infoDiseno.nodo_principal);
                    $('#infoNodoRespaldo').val(infoDiseno.nodo_respaldo);
                    $('#infoFacilidadesRed').val(infoDiseno.facilidades_de_red);
                    $('#infoCantCTO').val(infoDiseno.cant_cto);
                    $('#infoMetroTenAereo').val(infoDiseno.metro_tendido_aereo);
                    $('#infoMetroTenSubt').val(infoDiseno.metro_tendido_subterraneo);
                    $('#infoMetroCana').val(infoDiseno.metors_canalizacion);
                    $('#cantCamaNue').val(infoDiseno.cant_camaras_nuevas);
                    $('#infoPostNue').val(infoDiseno.cant_postes_nuevos);
                    $('#infoCantPostApo').val(infoDiseno.cant_postes_apoyo);
                    $('#infoCantAperCamara').val(infoDiseno.cant_apertura_camara);
                    $('#infoRequeSeia').val(infoDiseno.requiere_seia);
                    $('#infoRequeAproMmlMtc').val(infoDiseno.requiere_aprob_mml_mtc);
                    $('#infoRequeAprobINC').val(infoDiseno.requiere_aprob_inc);
                    $('#infoDias').val(infoDiseno.duracion);
                    $('#infoTipoDiseno').val(infoDiseno.desc_tipo_diseno);
                    $('#infoCostoMat').val(infoDiseno.costo_materiales);
                    $('#infoCostMo').val(infoDiseno.costo_mano_obra);
                    $('#infoCostoDiseno').val(infoDiseno.costo_diseno);
                    $('#infoMontoEIA').val(infoDiseno.costo_expe_seia_cira_pam);
                    $('#infoCostoAdicZona').val(infoDiseno.costo_adicional_rural);
                    $('#infoCostoTotal').val(infoDiseno.costo_total);
                    $('#infoareaComentario').val(infoDiseno.comentario);
                    $('#formEntidadesInfo').html(data.entidadHTML);
                    modal('modalEditEntidadesInfo');
                });
    }
}
