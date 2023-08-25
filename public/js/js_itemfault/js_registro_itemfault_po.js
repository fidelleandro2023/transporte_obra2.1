var arrayMatGlob = [];
var costoTotalMat = null;
var countErrorGlob = null;
var listaFileTemp = null;
var listaFileValido = null;
function generarExcelPO() {
    $.ajax({
        url: urlData,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST'
    }).done(function (data) {
        var data = JSON.parse(data);
        console.log('paso el parseo');
        if (data.error == 0) {
            location.href = data.rutaExcel;
        } else {
            mostrarNotificacion('warning', 'Aviso', data.msj);
        }
    })
}

var toog2 = 0;
//var error=0;
Dropzone.autoDiscover = false;
var errorAjax = null
var msj = null;
var dropGlobal = null;

$("#dropzonePO").dropzone({
    url: urlDataCargar,
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 200,
    maxFilesize: 800,
    acceptedFiles: ".xls",
    dictResponseError: "Ha ocurrido un error en el server",
    success: function (file, response) {
        data = JSON.parse(response);
        errorAjax = data.error;
        msj = data.msj;
        dropGlobal = this;
        if (errorAjax == 0) {
            console.log('entro xq no hay error')
            $('#contTablaError').html(data.tablaError);
            //MO 
            var infoFile = JSON.parse(data.jsonDataFIle);
            listaFileTemp = infoFile;
            var fileValido = JSON.parse(data.jsonDataFIleValido);
            listaFileValido = fileValido;
            //MAT
            arrayMatGlob = data.arrayMat;
            countErrorGlob = data.countError;
            costoTotalMat = data.costoTotalGlob;
        } else {
            mostrarNotificacion('error', 'error', msj);
        }


    },
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
            alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
            return;
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error = 1;
            // alert(message);
            this.removeFile(file);
        });

        var submitButton = document.querySelector("#btnAceptarFormulario")
        var myDropzone = this;

        submitButton.addEventListener("click", function () {
            // var vr = $.trim($('#contVR').val());

            // if (vr == null || vr == '') {
            //     mostrarNotificacion('error', 'error', 'debe ingresar un vr');
            //     return;
            // }
            myDropzone.processQueue();

        });

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
                }
            }
        });

        this.on("queuecomplete", function (file) {
            var drop = this;

        });

        this.on('sending', function (file, xhr, formData) {
            // var vr = $.trim($('#contVR').val());
            // formData.append('itemplan', itemplanGlob);
            // formData.append('idSubProyectoEstacion', idSubProyectEstaGlob);
            // formData.append('idEmpresaColab', idEmpresaColabGlob);
            // formData.append('vr', vr);
        });
    }
});


function openModalAceptar() {
    modal('modalAlertaAceptacion');
}

function guardarPO() {
    if (tipoPoGlob == 'MAT') {
        if (arrayMatGlob.length > 0 && fromGlob != null && costoTotalMat != null) {
            var prueba = 0;
            if (fromGlob == 1) {
                prueba = 1
            } else if (fromGlob == 2) {
                prueba = 2;
            }
            $.ajax({
                type: 'POST',
                url: 'registPOIteamfault',
                data: {
                    arrayMateriales: arrayMatGlob,
                    costoTotalMat: costoTotalMat,
                    prueba: prueba
                }
            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {

                    arrayMatGlob = [];
                    countErrorGlob = null;
                    $('#contTablaError').html(null);
                    // $('#btnRegisPO').prop('disabled', true);
                    dropGlobal.removeAllFiles(true);
                    swal({
                        title: 'Se genero correctamente el PO: ' + data.codidoPO + '',
                        text: 'Asegurese de validar la informacion!',
                        type: 'success',
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'OK!'

                    }).then(function () {
                        // location.reload();
                        window.location.href = "detalleItemfault?item=" + data.itemfault + "&from=" + fromGlob;
                    });
                } else if (data.error == 1) {
                    if (data.montoSuperado == 1) {
                        swal({
                            title: 'No se pudo procesar la Solicitud',
                            text: 'No se puede procesar la solicitud dedibo a que el Costo programado \n\
                                               para Materiales es de s/.' + parseFloat(data.montoMo).toFixed(2) + ', siendo el Costo de la PO a \n\
                                               procesar de s./' + parseFloat(data.costoTotalPOMO).toFixed(2) + ' y esta generando un Exceso de s./' + parseFloat(data.costoTotalPOMO - data.montoMo).toFixed(2) + ' \xBFDesea generar una solicitud \n\
                                               de Ampliacion de Costo de Obra por s/.' + parseFloat(data.costoTotalPOMO - data.montoMo).toFixed(2) + '?',
                            type: 'warning',
                            showCancelButton: true,
                            buttonsStyling: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: 'Si, generar la solicitud',
                            cancelButtonClass: 'btn btn-danger',
                            cancelButtonText: 'Cancelar'
                        }).then(function () {
                            var dataString = 'itemfault=' + data.itemfault
                                    + '&tipo_po=' + 2
                                    + '&costo_inicial=' + parseFloat(data.montoMo).toFixed(2)
                                    + '&exceso_solicitado=' + parseFloat(data.costoTotalPOMO - data.montoMo).toFixed(2)
                                    + '&costo_final=' + parseFloat(data.costoTotalPOMO).toFixed(2);
//                            console.log(JSON.stringify(jsonDataFile));
                            $.ajax({
                                type: 'POST',
                                dataType: "JSON",
                                'url': 'ajaxCreateExceso',
                                data: dataString,
                                'async': false
                            }).done(function (dato) {
                                console.log(dato);
                                if (dato.error == 0) {
                                    swal({
                                        title: 'Mensaje',
                                        text: dato.msj,
                                        type: 'success',
                                        buttonsStyling: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: 'OK!'
                                    }).then(function () {
                                        // location.reload();
                                        window.location.href = "detalleItemfault?item=" + data.itemfault + "&from=" + fromGlob;
                                    });

                                } else {
                                    mostrarNotificacion('warning', 'Verificar!', dato.msj);
                                }
                            });
                        });
                    } else {
                        mostrarNotificacion('warning', 'Verificar!', data.msj);
                    }
                }
            });
        }
    } else if (tipoPoGlob == 'MO') {
        if (listaFileValido != null && listaFileValido.length > 0) {
            swal({
                title: 'Estas seguro generar la PO?',
                text: 'Asegurese de que la informacion llenada sea la correta.',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, guardar los datos!',
                cancelButtonClass: 'btn btn-secondary',
                allowOutsideClick: false
            }).then(function () {
                var jsonDataFile = listaFileValido;
                console.log(jsonDataFile);
                // var from = $('#preLoadFile').attr('data-from');

                $.ajax({
                    type: 'POST',
                    'url': 'registPOMoIteamfault',
                    data: {jsonDataFile: JSON.stringify(jsonDataFile)},
                    'async': false
                })
                        .done(function (data) {
                            var data = JSON.parse(data);
                            if (data.error == 0) {
                                swal({
                                    title: 'Se genero correctamente el PO: ' + data.codidoPO + '',
                                    text: 'Asegurese de validar la informacion!',
                                    type: 'success',
                                    buttonsStyling: false,
                                    confirmButtonClass: 'btn btn-primary',
                                    confirmButtonText: 'OK!'

                                }).then(function () {
                                    window.location.href = "detalleItemfault?item=" + data.itemfault + "&from=" + fromGlob;
                                });
                                //listaFileTemp = null;
                                //listaFileValido = null;
                                //$('#contBodyTable').html('');
                                //$('#fileTable').val("");
                                // $('#contSubirInfo').hide();

                            } else if (data.error == 1) {
                                if (data.montoSuperado == 1) {
                                    swal({
                                        title: 'No se pudo procesar la Solicitud',
                                        text: 'No se puede procesar la solicitud dedibo a que el Costo programado \n\
                                               para Mano de Obra es de s/.' + parseFloat(data.montoMo).toFixed(2) + ', siendo el Costo de la PO a \n\
                                               procesar de s./' + parseFloat(data.costoTotalPOMO).toFixed(2) + ' y esta generando un Exceso de s./' + parseFloat(data.costoTotalPOMO - data.montoMo).toFixed(2) + ' \xBFDesea generar una solicitud \n\
                                               de Ampliacion de Costo de Obra por s/.' + parseFloat(data.costoTotalPOMO - data.montoMo).toFixed(2) + '?',
                                        type: 'warning',
                                        showCancelButton: true,
                                        buttonsStyling: false,
                                        confirmButtonClass: 'btn btn-primary',
                                        confirmButtonText: 'Si, generar la solicitud',
                                        cancelButtonClass: 'btn btn-danger',
                                        cancelButtonText: 'Cancelar'
                                    }).then(function () {
                                        var dataString = 'itemfault=' + data.itemfault
                                                + '&tipo_po=' + 1
                                                + '&costo_inicial=' + parseFloat(data.montoMo).toFixed(2)
                                                + '&exceso_solicitado=' + parseFloat(data.costoTotalPOMO - data.montoMo).toFixed(2)
                                                + '&costo_final=' + parseFloat(data.costoTotalPOMO).toFixed(2);
                                        console.log(JSON.stringify(jsonDataFile));
                                        $.ajax({
                                            type: 'POST',
                                            dataType: "JSON",
                                            'url': 'ajaxCreateExceso',
                                            data: dataString,
                                            'async': false
                                        }).done(function (dato) {
                                            console.log(dato);
                                            if (dato.error == 0) {
                                                swal({
                                                    title: 'Mensaje',
                                                    text: dato.msj,
                                                    type: 'success',
                                                    buttonsStyling: false,
                                                    confirmButtonClass: 'btn btn-primary',
                                                    confirmButtonText: 'OK!'
                                                }).then(function () {
                                                    // location.reload();
                                                    window.location.href = "detalleItemfault?item=" + data.itemfault + "&from=" + fromGlob;
                                                });
                                            } else {
                                                mostrarNotificacion('warning', 'Verificar!', dato.msj);
                                            }
                                        });
                                    });
                                } else {
                                    mostrarNotificacion('warning', 'Verificar!', data.msj);
                                }
                            }
                        });

            })
        } else {
            alert('No hay datos validos para actualizar, ingrese otro archivo');
        }
    }
}

function deleteMatErroneo(component) {
    var posicion = $(component).data('posicion');

    if (posicion != null && posicion != undefined && arrayMatGlob.length > 0) {

        arrayMatGlob.splice(posicion, 1);

        $.ajax({
            type: 'POST',
            url: 'deleteMatErroneo',
            data: {
                posicion: posicion,
                arrayMateriales: arrayMatGlob
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                $('#contTablaError').html(data.tablaError);
                arrayMatGlob = data.arrayMat;
                countErrorGlob = data.countError;
                // if (countErrorGlob == 0) {
                //     $('#btnRegisPO').prop('disabled', false);
                // } else {
                //     $('#btnRegisPO').prop('disabled', true);
                // }
                mostrarNotificacion('success', 'Operacion Exitosa', 'Se elimino correctamente!!');
            } else {
                mostrarNotificacion('warning', 'Error', data.msj);
            }
        });
    }
}


function removeTR(component) {

    var indice = $(component).attr('data-indice');
    var indice_val = $(component).attr('data-indice_val');
    if (indice_val != null) {
        delete listaFileValido[indice_val];
    }
    $('#tr' + indice).remove();
    //listaFileTemp.splice(indice, 1);
    delete listaFileTemp[indice];
    //$('#data-table').DataTable().row( $(component).parents('tr') ).remove().draw();
    console.log(listaFileValido);
}




