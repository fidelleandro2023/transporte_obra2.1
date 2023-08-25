/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function openModalDetLogVR(component) {
    var idSolVR = $(component).data('idsolvr');
    $.ajax({
        type: 'POST',
        url: 'getDetVR',
        data: {
            idSolVR: idSolVR
        }
    }).done(function (data) {
        data = JSON.parse(data);
        $('#contTablaDetVR').html(data.tablaDetVR);
        modal('modalDetalleVR');

    });
}


function recogePTR() {

    console.log('entro en recogePTR...');

    //var arrayNamesptrExp = $( "input[name*='ptrExp']" );

    var arrayNamesptrExp = $("input[type=checkbox]:checked");
    var expediente = new Array();

    if (arrayNamesptrExp.length != 0) {
        console.log(arrayNamesptrExp.length);
        console.log(arrayNamesptrExp);

        for (i = 0; i < arrayNamesptrExp.length; i++) {
            expediente.push(arrayNamesptrExp[i].dataset.ptr + "%" + arrayNamesptrExp[i].dataset.item + "%" + arrayNamesptrExp[i].dataset.fecsol + "%" + arrayNamesptrExp[i].dataset.subproyecto + "%" + arrayNamesptrExp[i].dataset.zonal + "%" + arrayNamesptrExp[i].dataset.eecc + "%" + arrayNamesptrExp[i].dataset.area);
        }
        console.log('expediente es ');
        console.log(expediente);

        mostrarModal(expediente);

    } else {
        alert('Debe seleccionar al menos 1 registro para continuar.');
    }


}

function mostrarModal(expediente) {
    console.log('entro en registaExpediente');
    var texto = '';
    var ptrModal = '';
    var itemModal = '';
    for (j = 0; j < expediente.length; j++) {
        //texto += '<label>'+expediente[j].replace('%', ' ')+'</label><br>';
        //ptrModal = expediente[j]

        var elem = expediente[j].split('%');
        ptrModal = elem[0];
        itemModal = elem[1];
        texto += '<label>' + ptrModal + '</label><br>';

    }
    var jsonExpediente = JSON.stringify(expediente);

    console.log('----------------------------');
    console.log(expediente);
    console.log(jsonExpediente);



    $('#seleccionados').html(texto);

    $('#botonConfirmar').attr('data-jsonptr', jsonExpediente);


    $('#modalExpediente').modal('toggle');

}


function asignarExpediente(component) {
    var vrLeng = $('#inputVR').val().length;

    if (vrLeng == 0) {
        alert('Usted no ha asignado un comentario de expediente.');
    } else {

        console.log('Asignar expediente');
        var jsonptr = $(component).attr('data-jsonptr');
        var comentario = $('#inputVR').val();
        console.log('=================');
        console.log(jsonptr);
        console.log(comentario);
        console.log('Ajax');



        $.ajax({
            type: 'POST',
            'url': 'asignarExpediente',
            data: {jsonptr: jsonptr,
                comentario: comentario
            },
            'async': false
        }).done(function (data) {
            console.log('voldio del ajax');

            var data = JSON.parse(data);
            console.log('++++++++++++++++++');

            if (data.error == 0) {
                console.log('en el if');
                $('#modalExpediente').modal('toggle');
                mostrarNotificacion('success', 'Registro exitoso.', data.msj);
                //$('#contTabla').html(data.tablaAsigGrafo)
                //initDataTable('#data-table');
                filtrarTabla();
            } else if (data.error == 1) {
                console.log('en el else');

                mostrarNotificacion('error', 'Error al dar expediente', data.msj);
            }
        });

        console.log('se envio a ruta');

    }

}
function filtroTipoPlanta() {

}

function filtrarTabla() {
    //var itemplan = $.trim($('#itemplan').val());
    var erroItemPlan = '';
    var itemplan = $.trim($('#txtItemPlan').val());
    //validar item plan
    //mostrarNotificacion('error','Hubo problemas al filtrar los datos!');

    if (itemplan.length < 13 && itemplan.length >= 1)
        erroItemPlan = 'ItemPlan Invalido.'

    var tipoPlanta = $.trim($('#selectTipoPlanta').val());
    var nombreproyecto = $.trim($('#nombreproyecto').val());
    var nodo = $.trim($('#nodo').val());
    var zonal = $.trim($('#selectZonal').val());
    var proy = $.trim($('#selectProy').val());
    var subProy = $.trim($('#selectSubProy').val());
    var estado = $.trim($('#estado').val());
    var selectMesPrevEjec = $.trim($('#selectMesPrevEjec').val());

    var fechaInicio0 = $('#fechaInicio').val();
    var fechaFin0 = $('#fechaFin').val();

    var fechaInicio = fechaInicio0.replace(/-/g, '/');
    var fechaFin = fechaFin0.replace(/-/g, '/');

    var fechaDestinoDefault = '2018/12/31';
    var fechaDestino = '';
    var filtroPrevEjec = '';
    var idFase = $.trim($('#selectFase').val());

    console.log('fechaInicio es: ' + fechaInicio);
    if (fechaFin0 == '') {
        //console.log('fecha fin esta vacia');
        //console.log('fecha destino sera: '+fechaDestinoDefault);
        fechaDestino = fechaDestinoDefault;
    } else {
        //console.log('fechaFin (destino) es: '+fechaFin);
        fechaDestino = fechaFin;
    }

    if (fechaInicio0 != '') {
        filtroPrevEjec = " AND p.fechaPrevEjec BETWEEN '" + fechaInicio + "' AND '" + fechaDestino + "' ";
    } else {
        filtroPrevEjec = "";
    }

    var filtrar = false;
    if (tipoPlanta != '' && proy != '' && subProy != '' && idFase != '') {
        filtrar = true;
    } else if (erroItemPlan == '' && itemplan != '') {
        filtrar = true;
    } else if (nombreproyecto != '') {
        filtrar = true;
    }


    if (filtrar) {
        console.log(nombreproyecto);
        if (itemplan != '') {
            $.ajax({
                type: 'POST',
                'url': 'pqt_perteneceactpqt',
                data: {itemplan: itemplan,
                    nombreproyecto: nombreproyecto},
                'async': false
            })
                    .done(function (data) {
                        var data = JSON.parse(data);
                        console.log(data);
                        if (data.error == 0) {
                            $.ajax({
                                type: 'POST',
                                'url': 'ajaxGetConsulta',
                                data: {itemplan: itemplan,
                                    nombreproyecto: nombreproyecto,
                                    nodo: nodo,
                                    zonal: zonal,
                                    proy: proy,
                                    subProy: subProy,
                                    estado: estado,
                                    filtroPrevEjec: filtroPrevEjec,
                                    tipoPlanta: tipoPlanta,
                                    idFase: idFase,
                                    permitir: data.permitir
                                            //area : area
                                },
                                'async': false
                            })
                                    .done(function (data) {
                                        var data = JSON.parse(data);
                                        if (data.error == 0) {
                                            $('#contTabla').html(data.tablaAsigGrafo)
                                            initDataTable('#data-table');

                                        } else if (data.error == 1) {

                                            mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                                        }
                                    });

                        } else if (data.error == 1) {

                            mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                        }
                    });

        } else {//filtrar todo
            console.log("VIEJO");
            $.ajax({
                type: 'POST',
                'url': 'ajaxGetConsulta',
                data: {itemplan: itemplan,
                    nombreproyecto: nombreproyecto,
                    nodo: nodo,
                    zonal: zonal,
                    proy: proy,
                    subProy: subProy,
                    estado: estado,
                    filtroPrevEjec: filtroPrevEjec,
                    tipoPlanta: tipoPlanta,
                    idFase: idFase
                            //area : area
                },
                'async': false
            })
                    .done(function (data) {
                        var data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTabla').html(data.tablaAsigGrafo)
                            initDataTable('#data-table');

                        } else if (data.error == 1) {

                            mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                        }
                    });
        }
        /**fin filtrar **/
    } else {
        //mostrarNotificacion('error',''ItemPlan',erroItemPlan');
        mostrarNotificacion('error', 'ItemPlan', 'Debe seleccionar Filtros de Busqueda');
    }


}




/****************************Log*************************/
function mostrarLog(component) {
    var itemplan = $(component).attr('data-idlog');
    $.ajax({
        type: 'POST',
        'url': 'mostrarLogIPConsulta',
        data: {itemplan: itemplan},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    $('#tituloModal').html('ITEMPLAN : ' + itemplan);
                    $('#contCardLog').html(data.listaLog);
                    $('#modal-large').modal('toggle');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error al principal', data.msj);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error al mostrar log principal', errorThrown + '. Estado: ' + textStatus);
            });
}

function verMotivoCancelar(component) {
    var fecha = $(component).attr('data-fechaC');
    var itemplan = $(component).attr('data-itemC');


    $.ajax({
        type: 'POST',
        'url': 'getMotivoCancelConsulta',
        data: {itemplan: itemplan,
            fecha: fecha},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contCardMotivoCancel').html(data.motivoCancel);
                    $('#modal-motcancel').modal('toggle');

                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error ', data.msj);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error al mostrar el log cancelar', errorThrown + '. Estado: ' + textStatus);
            })
            .always(function () {

            });


}

function closeMotivoCancelar() {
    $('#modal-motcancel').modal('toggle');
    $('#modal-large').css('overflow-y', 'scroll');
}


function verMotivoTrunco(component) {
    var fecha = $(component).attr('data-fechaT');
    var itemplan = $(component).attr('data-itemT');

    $.ajax({
        type: 'POST',
        'url': 'getMotivoTruncoConsulta',
        data: {itemplan: itemplan,
            fecha: fecha},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contCardMotivoTrunco').html(data.motivoTrunco);
                    $('#modal-mottrunco').modal('toggle');

                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error ', data.msj);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error mostrar el log trunco', errorThrown + '. Estado: ' + textStatus);
            })
            .always(function () {

            });

}

function closeMotivoTrunco() {
    $('#modal-mottrunco').modal('toggle');
    $('#modal-large').css('overflow-y', 'scroll');
}

/********************************************************************************************************/


function zipItemPlan(btn) {
    var itemPlan = btn.data('itemplan');
    if (itemPlan == null || itemPlan == '') {
        return;
    }
    console.log(itemPlan);
    $.ajax({
        type: 'POST',
        url: 'zipItemPlan',
        data: {itemPlan: itemPlan}
    }).done(function (data) {
        try {
            data = JSON.parse(data);
            if (data.error == 0) {
                var url = data.directorioZip;
                if (url != null) {
                    window.open(url, 'Download');
                } else {
                    alert('No tiene evidencias');
                }
                // mostrarNotificacion('success', 'descarga realizada', 'correcto');
            } else {
                // mostrarNotificacion('error', 'descarga no realizada', 'error');            
                alert('error al descargar');
            }
        } catch (err) {
            alert(err.message);
        }
    });
}

function changueProyecto() {
    var tipoplanta = $.trim($('#selectTipoPlanta').val());
    $.ajax({
        type: 'POST',
        'url': 'getProyConsulta',
        data: {tipoplanta: tipoplanta},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {

                    $('#selectProy').html(data.listaProyectos);
                    $('#selectSubProy').html('');


                } else if (data.error == 1) {

                    mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                }
            });
}

function changueSubProyecto() {
    var proyecto = $.trim($('#selectProy').val());
    $.ajax({
        type: 'POST',
        'url': 'getSubProyConsulta',
        data: {proyecto: proyecto},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {

                    $('#selectSubProy').html(data.listaSubProy);


                } else if (data.error == 1) {

                    mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                }
            });
}



var origenGlobal = null;
function openModalParalizacion(btn, origen) {
    var flgMotivoParalizacion = 1;
    itemplanParalizacion = btn.data('itemplan');
    $('#btnEvidenciaParalizacion').css('display', 'block');
    // console.log(drop.dropzone().maxFilesize);

    if (itemplanParalizacion == null || itemplanParalizacion == '') {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'getCmbMotivo',
        data: {flgTipo: flgMotivoParalizacion,
            itemplan: itemplanParalizacion}
    }).done(function (data) {
        origenGlobal = origen;
        console.log(origenGlobal);
        data = JSON.parse(data);
        // console.log(dropzone.maxFilesize);
        var cmbMotivo = '<option value="">Seleccionar Motivo</option>';
        data.arrayMotivo.forEach(function (element) {
            cmbMotivo += '<option value="' + element.idMotivo + '">' + element.motivoDesc + '</option>';
        });
        $('#cmbParalizacionHtml').html(cmbMotivo);
        //insertParalaizacion(itemplanParalizacion); 
        $('.dz-message').html('<span>Subir evidencia</span>');
        modal('modalParalizacion');
    });


}
/******************************************/

var itemplanParalizacion = null;
var idMotivo = null;
var comentario = null;
var toog2 = 0;

function insertParalizacion() {
    idMotivo = $('#cmbParalizacionHtml option:selected').val();
    comentario = $('#comentarioParalizacion').val();
    motivo = $('#cmbParalizacionHtml option:selected').text();

    if (itemplanParalizacion == '' || itemplanParalizacion == null) {
        return;
    }

    if (idMotivo == '' || idMotivo == null || origenGlobal == '' || origenGlobal == null) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'insertParalizacion',
        data: {idMotivo: idMotivo,
            comentario: comentario,
            motivo: motivo,
            itemplan: itemplanParalizacion,
            origen: origenGlobal}
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            mostrarNotificacion('success', "registro correcto", "correcto");
            modal('modalParalizacion');
            if ($('.dz-preview').html() == undefined) {
                location.reload();
            }
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

Dropzone.autoDiscover = false;
$("#dropzoneParalizacion").dropzone({
    url: "insertFileParalizacion",
    type: 'POST',
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 30,
    maxFilesize: 3,
    // params: {
    //        itemplan : itemplanParalizacion
    //   },
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
            return;
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser�1�7 tomado en cuenta');
            error = 1;
            // alert(message);
            this.removeFile(file);
        });


        var submitButton = document.querySelector("#btnEvidenciaParalizacion");
        var myDropzone = this;

        var concatEvi = '';
        submitButton.addEventListener("click", function () {
            $('#btnEvidenciaParalizacion').css('display', 'none');
            insertParalizacion();
            myDropzone.processQueue();
        });

        var concatEvi = '';
        this.on("addedfile", function () {
            toog2 = toog2 + 1;
        });

        this.on('complete', function () {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                if (error == 0) {
                    console.log(this.getUploadingFiles());
                }

            }
        });

        this.on("queuecomplete", function (file) {
            var last = concatEvi.substring(0, (concatEvi.length - 1));

            if (error == 0) {
                updateFileParalizacion();
            }
        });
    }
});

function updateFileParalizacion() {
    $.ajax({
        type: 'POST',
        url: 'updateFileParalizacion',
        data: {itemplan: itemplanParalizacion}
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            location.reload();
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

var itemplanGlobal = null;
function openModalAlert(btn) {
    itemplanGlobal = btn.data('itemplan');
    modal('modalAlerta');
}

function aceptarRevertir() {
    $.ajax({
        type: 'POST',
        url: 'revertirParalizacion',
        data: {itemplan: itemplanGlobal}
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            mostrarNotificacion('success', "Se a revertido la paralizaci&oacute;n correctamente", "correcto");
            location.reload();
            modal('modalAlerta');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

var itemplanSiom = null;
function openModalCodigoSion(btn) {
    itemplanSiom = btn.data('itemplan');

    if (itemplanSiom == null || itemplanSiom == '') {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'getDataSiom',
        data: {itemplan: itemplanSiom}
    }).done(function (data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            $('#contTablaSiom').html(data.tablaSiom);
            modal('modalSiom');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    })
}

function  openGant(component) {
    var itemplan = $(component).attr('data-itm');
    $.ajax({
        type: 'POST',
        'url': 'hasAdju',
        data: {itemplan: itemplan},
        'async': false
    })
            .done(function (data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    if (data.hasAdju >= 1) {
                        window.open("<?php echo base_url()?>detalleGant?item=" + itemplan);
                    } else if (data.hasAdju == 0) {
                        alert('Itemplan no cuenta con los datos Basicos (Adjudicacion) para graficar el Gant.');
                    }

                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error ', data.msj);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error mostrar el log trunco', errorThrown + '. Estado: ' + textStatus);
            })
            .always(function () {

            });

}

function getAnalisisEconomico(btn) {
    var itemplan = btn.data('itemplan');
    var a = document.createElement("a");
    a.target = "_blank";
    a.href = "getAnalisisEconomico?itemplan=" + itemplan;
    a.click();
}

function openModalDatosSisegos(btn) {
    var itemplan = btn.data('itemplan');

    if (itemplan == null || itemplan == '') {
        return;
    }

    $.ajax({
        type: 'POST',
        'url': 'getDataSisego',
        data: {itemplan: itemplan},
        'async': false
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            $('#contInfoDataSisego').html(data.dataInfoSisego);
            modal('modalDatosSisegos');
        } else {
            return;
        }
    });
}

function liquidacion(itemPlan) {
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'liquidacion',
        data: {itemPlan: itemPlan}
    }).done(function (data) {
        console.log(data);
        if (data.path == '1') {
            location.href = 'liquidacion_download?' + 'itemPlan=' + itemPlan;
        } else {
            alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
        }

    });
}

function disenho(itemPlan) {
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'disenho',
        data: {itemPlan: itemPlan}
    }).done(function (data) {
        console.log(data);
        if (data.path == '1') {
            location.href = 'disenho_download?' + 'itemPlan=' + itemPlan;
        } else {
            alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
        }

    });
}

function licencias(itemPlan) {
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'licencias',
        data: {itemPlan: itemPlan}
    }).done(function (data) {
        console.log(data);
        if (data.path == '1') {
            location.href = 'licencias_download?' + 'itemPlan=' + itemPlan;
        } else {
            alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
        }

    });
}

function cotizacion(itemPlan) {
    $.ajax({
        type: 'POST',
        dataType: "JSON",
        'url': 'cotizacion',
        data: {itemPlan: itemPlan}
    }).done(function (data) {
        console.log(data);
        if (data.path == '1') {
            location.href = 'cotizacion_download?' + 'itemPlan=' + itemPlan;
        } else {
            alertValidacion('warning', 'Mensaje', 'Sin datos para descargar');
        }

    });
}

function alertValidacion(tipo, titulo, mensaje) {
    swal({
        title: titulo,
        text: mensaje,
        type: tipo
    });
}

