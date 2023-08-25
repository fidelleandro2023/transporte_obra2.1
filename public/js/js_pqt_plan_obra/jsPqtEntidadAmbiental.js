function openModalEntidadAmbiental(element) {
    let { itemplan, flgInstrumentoAmbiental } = $(element).data();

    switch (flgInstrumentoAmbiental) {
        case "":
            swal({
                title: "Requiere Entidad INSTRUMENTO AMBIENTAL?",
                text: "Al aceptar, podra ingresar los compromisos.",
                type: "warning",
                buttonsStyling: false,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: "SI",
                showCancelButton: true,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: "NO",
            }).then(function(result) {
                if (result) {
                    $.ajax({
                        type: "POST",
                        url: "registrarEntidadInstrumentoAmbiental",
                        data: {
                            idEntidad: 11,
                            itemplan: itemplan,
                        },
                    }).done(function(data) {
                        data = JSON.parse(data);

                        if (data.error == 0) {
                            $("#contTabla").html(data.tablaAsigGrafo);
                            initDataTable("#data-table");

                            $("#btnAgregarEntidadAmbiental").removeClass("d-none");
                            $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
                            modal("modalEntidadAmbiental");

                            $(".select2").select2();

                            mostrarNotificacion("success", "Aviso", data.msj);
                        } else {
                            mostrarNotificacion("error", "Incorrecto", data.msj);
                        }
                    });
                }
            });
            break;

        case 1:
            $.ajax({
                type: "POST",
                url: "getTablaEntidadInsutrumentoAmbiental",
                data: { itemplan },
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $("#btnAgregarEntidadAmbiental").removeClass("d-none");
                    $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
                    modal("modalEntidadAmbiental");

                    $(".select2").select2();
                } else {
                    mostrarNotificacion("error", "Aviso", data.msj);
                }
            });
            break;

        case 2:
            $.ajax({
                type: "POST",
                url: "getTablaEntidadInsutrumentoAmbiental",
                data: { itemplan },
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $("#btnAgregarEntidadAmbiental").addClass("d-none");
                    $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
                    modal("modalEntidadAmbiental");

                    $(".select2").select2();
                } else {
                    mostrarNotificacion("error", "Aviso", data.msj);
                }
            });
            break;

        default:
            break;
    }

    $("#btnAgregarEntidadAmbiental").data("itemplan", itemplan);
}

function agregarEntidadAmbiental(element) {
    let { itemplan } = $(element).data();

    swal({
        title: "Requiere Entidad INSTRUMENTO AMBIENTAL?",
        text: "",
        type: "warning",
        buttonsStyling: false,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: "SI",
        showCancelButton: true,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: "NO",
    }).then(function(result) {
        if (result) {
            $.ajax({
                type: "POST",
                url: "registrarEntidadInstrumentoAmbiental",
                data: {
                    idEntidad: 11,
                    itemplan: itemplan,
                },
            }).done(function(data) {
                data = JSON.parse(data);

                if (data.error == 0) {
                    $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
                    $(".select2").select2();

                    mostrarNotificacion("success", "Aviso", data.msj);
                } else {
                    mostrarNotificacion("error", "Incorrecto", data.msj);
                }
            });
        }
    });
}

function openModalAdjuntarEvidencias(element) {
    let { itemplan, idEntidad, id, idEntidadEstado } = $(element).data();

    if (idEntidadEstado == 2) {
        $("#btnAgregarEvidencia").addClass("d-none");
    } else {
        $("#btnAgregarEvidencia").removeClass("d-none");
    }

    $("#btnAgregarEvidencia").data({
        itemplan,
        "id-entidad": idEntidad,
        id,
    });

    $.ajax({
        type: "POST",
        url: "getTablaEntidadEvidencia",
        data: { id, itemplan },
    }).done(function(data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            $("#cboTipoEvidencia").html(data.cmbEntidadTipoEvidenciaAll);
            $("#contEntidadAmbientalTabla").html(data.tablaEntidadEvidencia);
            initDataTable("#tbEntidadEvidencia");

            $("#cboTipoEvidencia").val("");
            $("#txtDescripcionEvidencia").val("");
            $("#txtFechaIniEvidencia").val("");
            $("#txtFechaFinEvidencia").val("");
            $("#fileEvidencia").val("");

            modal("modalEntidadAmbientalAdjuntos");
        } else {
            mostrarNotificacion("error", "Aviso", data.msj);
        }
    });
}

function showAgregarEvidencias(element) {
    let { id, itemplan, action } = $(element).data();

    if (action == 1) {
        let tipoEvidencia = $("#cboTipoEvidencia").val();
        let descripcionEvidencia = $("#txtDescripcionEvidencia").val();
        let fechaIniEvidencia = $("#txtFechaIniEvidencia").val();
        let fechaFinEvidencia = $("#txtFechaFinEvidencia").val();
        let fileEvidencia = $("#fileEvidencia")[0].files[0];

        if (
            tipoEvidencia == "" ||
            descripcionEvidencia == "" ||
            fechaIniEvidencia == "" ||
            fechaFinEvidencia == "" ||
            fileEvidencia == undefined
        ) {
            mostrarNotificacion(
                "warning",
                "Ingresar los campos obligatorios (*)",
                "Incorrecto"
            );
            return;
        }

        let formData = new FormData();
        formData.append("tipoEvidencia", tipoEvidencia);
        formData.append("descripcionEvidencia", descripcionEvidencia);
        formData.append("fechaIniEvidencia", fechaIniEvidencia);
        formData.append("fechaFinEvidencia", fechaFinEvidencia);
        formData.append("fileEvidencia", fileEvidencia);
        formData.append("id", id);
        formData.append("itemplan", itemplan);

        $.ajax({
            data: formData,
            url: "registrarEntidadEvidencia",
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
        }).done(function(data) {
            data = JSON.parse(data);

            if (data.error == 0) {
                $("#cboTipoEvidencia").val("");
                $("#txtDescripcionEvidencia").val("");
                $("#txtFechaIniEvidencia").val("");
                $("#txtFechaFinEvidencia").val("");
                $("#fileEvidencia").val("");

                $("#contEntidadAmbientalTabla").html(data.tablaEntidadEvidencia);
                initDataTable("#tbEntidadEvidencia");

                mostrarNotificacion("success", "Aviso", data.msj);
            } else if (data.error == 1) {
                mostrarNotificacion("error", "Incorrecto", data.msj);
            }
        });
    }

    if ($("#contEntidadAmbientalAdjuntar").hasClass("d-none")) {
        $("#contEntidadAmbientalAdjuntar").slideDown("slow", function() {
            $(this).removeClass("d-none");
            $("#btnCerrarAgregarEvidencia").removeClass("d-none");
        });

        $("#btnAgregarEvidencia").html('<i class="zmdi zmdi-check"></i>');
        $("#btnAgregarEvidencia").data("action", 1);
    } else {
        $("#contEntidadAmbientalAdjuntar").slideUp("slow", function() {
            $(this).addClass("d-none");
            $("#btnCerrarAgregarEvidencia").addClass("d-none");
        });

        $("#btnAgregarEvidencia").html(
            '<i class="zmdi zmdi-plus-square"></i> Agregar'
        );
        $("#btnAgregarEvidencia").data("action", 0);
    }
}

function descargarEntidadEvidenciaAll(element) {
    let { itemplan } = $(element).data();

    $.ajax({
        type: "POST",
        url: "descargarEntidadEvidenciaAll",
        data: {
            itemplan,
        },
    }).done(function(data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            let url = data.directorioZip;
            if (url != null) {
                window.open(url, "Download");
            } else {
                mostrarNotificacion("error", "Incorrecto", "No tiene evidencias");
            }
        } else {
            mostrarNotificacion("error", "Incorrecto", "error al descargar");
        }
    });
}

function eliminarEvidencia(element) {
    let { id, idEvidencia, ruta, itemplan } = $(element).data();

    swal({
        type: "warning",
        title: "Esta seguro de eliminar?",
        text: "Asegurese de validar la informacion!!",
        showConfirmButton: true,
        confirmButtonText: "SI",
        showCancelButton: true,
        cancelButtonText: "NO",
        allowOutsideClick: false,
    }).then((result) => {
        if (result) {
            let formData = new FormData();
            formData.append("id", id);
            formData.append("idEvidencia", idEvidencia);
            formData.append("ruta", ruta);
            formData.append("itemplan", itemplan);

            $.ajax({
                type: "POST",
                url: "eliminarEvidencia",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $("#contEntidadAmbientalTabla").html(data.tablaEntidadEvidencia);
                    initDataTable("#tbEntidadEvidencia");

                    mostrarNotificacion("success", "Aviso", "Se eliminó correctamente");
                } else {
                    mostrarNotificacion("error", data.msj, "Incorrecto");
                }
            });
        }
    });
}

function cerrarFormulario(type) {
    switch (type) {
        case "comprobante":
            $("#contComprobanteAgregar").addClass("d-none");

            $("#txtNroComprobante").val("");
            $("#txtFechaEmiComprobante").val("");
            $("#txtMontoComprobante").val("");
            $("#fileComprobante").val("");

            $("#btnCerrarAgregarComprobante").addClass("d-none");
            $("#btnAgregarComprobante").html(
                '<i class="zmdi zmdi-plus-square"></i> Agregar'
            );
            $("#btnAgregarComprobante").data("action", 0);

            break;

        case "evidencia":
            $("#contEntidadAmbientalAdjuntar").addClass("d-none");

            $("#cboTipoEvidencia").val("");
            $("#txtDescripcionEvidencia").val("");
            $("#txtFechaIniEvidencia").val("");
            $("#txtFechaFinEvidencia").val("");
            $("#fileEvidencia").val("");

            $("#btnCerrarAgregarEvidencia").addClass("d-none");
            $("#btnAgregarEvidencia").html(
                '<i class="zmdi zmdi-plus-square"></i> Agregar'
            );
            $("#btnAgregarEvidencia").data("action", 0);

            break;

        default:
            break;
    }
}

function optionUpdCompromiso(element) {
    let { arrEntidad, id } = $(element).data();
    let select = $("#" + element.id + " option:selected").val();
    let indice = arrEntidad.indexOf(parseInt(select));

    if (indice !== -1) {
        $(`#btnCompromiso${id}`).removeClass("d-none");
        $(`#btnCompromiso${id}`).data("id-tipo", select);
    } else {
        $(`#btnCompromiso${id}`).addClass("d-none");
    }
}

var arrayFinalCompromisos = [];

function openModalCompromiso(element) {
    let { itemplan, id, idTipo, plan, medidas, estadoFinal } = $(element).data();

    $("#txtParticipacion").val(plan);
    $("#txtMedidas").val(medidas);

    if (estadoFinal == 2) {
        $("#txtParticipacion").attr("disabled", true);
        $("#txtMedidas").attr("disabled", true);
        $("#btnGuardarCompromiso").addClass("d-none");
        $("#btnFinalizarCompromiso").addClass("d-none");
        $("#btnAgregarCompromiso").addClass("d-none");
    } else {
        $("#txtParticipacion").attr("disabled", false);
        $("#txtMedidas").attr("disabled", false);
        $("#btnGuardarCompromiso").removeClass("d-none");
        $("#btnFinalizarCompromiso").removeClass("d-none");
        $("#btnAgregarCompromiso").removeClass("d-none");
    }

    $.ajax({
        type: "POST",
        url: "getTablaCompromisoEntidad",
        data: {
            id,
        },
    }).done(function(data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            $("#contTablaCompromisosAll").html(data.contTablaCompromisosAll);
            $("#btnAgregarCompromiso").data({
                id,
                "id-tipo": idTipo,
                itemplan,
            });

            $("#btnGuardarCompromiso").data({
                id,
                "id-tipo": idTipo,
                itemplan,
            });

            $("#btnFinalizarCompromiso").data({
                id,
                "id-tipo": idTipo,
                itemplan,
            });

            $("#btnFinalizarCompromiso").data("id", id);
            $(".select2").select2();
            modal("modalCompromiso");

            arrayFinalCompromisos = data.dataCompromisosAll;
        }
    });
}

function openModalAgregarCompromiso(element) {
    let { id, idTipo, itemplan } = $(element).data();

    $.ajax({
        type: "POST",
        url: "getTablaCompromiso",
        data: {
            id,
        },
    }).done(function(data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            if (data.count == 0) {
                mostrarNotificacion(
                    "error",
                    "No contiene compromisos para agregar.",
                    "Incorrecto"
                );
                return;
            }

            arrayDataCompromiso = [];
            $("#contTablaCompromisos").html(data.tablaCompromiso);
            $("#btnRegistrarCompromiso").data({
                id,
                "id-tipo": idTipo,
                itemplan,
            });
            modal("modalAgregarCompromiso");
        }
    });
}

var arrayDataCompromiso = [];

function agregarCompromiso(element) {
    let { idCompromiso, cant, id } = $(element).data();
    let jsonCompromiso = {};

    if ($("#check_compromiso" + cant).prop("checked")) {
        jsonCompromiso.idEntidadEstacion = id;
        jsonCompromiso.idCompromiso = idCompromiso;
        jsonCompromiso.idEstadoCompromiso = 1;

        arrayDataCompromiso.splice(arrayDataCompromiso.length, 0, jsonCompromiso);
    } else {
        arrayDataCompromiso.forEach(function(data, key) {
            if (data.idCompromiso == idCompromiso) {
                arrayDataCompromiso.splice(key, 1);
            }
        });
    }
}

function registrarCompromiso(element) {
    if (arrayDataCompromiso.length == 0) {
        mostrarNotificacion("warning", "Debe Agregar Compromiso", "Verificar");
        return;
    }

    let { id, idTipo, itemplan } = $(element).data();

    $.ajax({
        type: "POST",
        url: "registrarCompromiso",
        data: {
            arrayDataCompromiso,
            id,
            idTipo,
            itemplan,
        },
    }).done(function(data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            $("#contTablaCompromisosAll").html(data.tablaCompromisosAll);
            $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
            $(".select2").select2();

            arrayDataCompromiso = [];
            arrayFinalCompromisos = data.dataCompromisosAll;
            modal("modalAgregarCompromiso");
        } else {
            mostrarNotificacion("error", data.msj, "Incorrecto");
        }
    });
}

function eliminarCompromiso(element) {
    let { id, idCompromisoEntidad, ruta } = $(element).data();

    swal({
        type: "warning",
        title: "Esta seguro de finalizar?",
        text: "Asegurese de validar la informacion!!",
        showConfirmButton: true,
        confirmButtonText: "SI",
        showCancelButton: true,
        cancelButtonText: "NO",
        allowOutsideClick: false,
    }).then((result) => {
        if (result) {
            let formData = new FormData();
            formData.append("id", id);
            formData.append("idCompromisoEntidad", idCompromisoEntidad);
            formData.append("ruta", ruta);

            $.ajax({
                type: "POST",
                url: "eliminarCompromiso",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $("#contTablaCompromisosAll").html(data.tablaCompromisosAll);
                    arrayFinalCompromisos = data.dataCompromisosAll;
                } else {
                    mostrarNotificacion("error", data.msj, "Incorrecto");
                }
            });
        }
    });
}

function updCompromiso(element) {
    let { input, idCompromiso, id } = $(element).data();

    let value =
        input != 5 ? $("#" + element.id).val() : $("#" + element.id)[0].files[0];

    arrayFinalCompromisos.forEach((item) => {
        if (item["idCompromiso"] == idCompromiso) {
            switch (input) {
                case 1:
                    item["fechaInicioCompromiso"] = value;
                    break;
                case 2:
                    item["fechaFinCompromiso"] = value;
                    break;

                case 3:
                    item["idEstadoCompromiso"] = value;
                    break;

                case 4:
                    item["idUsuarioCompromiso"] = value;
                    break;

                case 5:
                    item["fileCompromiso"] = value;
                    break;

                default:
                    break;
            }
        }
    });
}

function guardarCompromiso(element) {
    let { id, itemplan, idTipo } = $(element).data();
    let txtParticipacion = $("#txtParticipacion").val();
    let txtMedidas = $("#txtMedidas").val();

    $.each(arrayFinalCompromisos, (i, e) => {
        let formData = new FormData();
        formData.append("idCompromisoEntidad", e["idCompromisoEntidad"]);
        formData.append("idEntidadEstacion", e["idEntidadEstacion"]);
        formData.append("idCompromiso", e["idCompromiso"]);
        formData.append("fechaInicioCompromiso", e["fechaInicioCompromiso"]);
        formData.append("fechaFinCompromiso", e["fechaFinCompromiso"]);
        formData.append("idEstadoCompromiso", e["idEstadoCompromiso"]);
        formData.append("idUsuarioCompromiso", e["idUsuarioCompromiso"]);
        formData.append(
            "fileCompromiso",
            e["fileCompromiso"] == null ? "" : e["fileCompromiso"]
        );
        formData.append("itemplan", itemplan);

        $.ajax({
            type: "POST",
            url: "updateCompromisoEntidad",
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
        });
    });

    let formData1 = new FormData();
    formData1.append("id", id);
    formData1.append("idTipo", idTipo);
    formData1.append("itemplan", itemplan);
    formData1.append("txtParticipacion", txtParticipacion);
    formData1.append("txtMedidas", txtMedidas);
    formData1.append("estadoFinalCompromiso", 1);

    $.ajax({
        type: "POST",
        url: "updateLicencia",
        data: formData1,
        contentType: false,
        processData: false,
        cache: false,
    }).done(function(data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            $("#contTablaCompromisosAll").html(data.tablaCompromisosAll);
            $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
            modal("modalCompromiso");
            $(".select2").select2();

            arrayFinalCompromisos = data.dataCompromisosAll;
        } else {
            return;
        }
    });
}

function finalizarCompromiso(element) {
    let checkEstado = true;
    let checkFechaInicio = true;
    let checkFechaFin = true;
    let checkIdUsuarioCompromiso = true;
    let checkFileCompromiso = true;

    arrayFinalCompromisos.forEach((item) => {
        if (parseInt(item["idEstadoCompromiso"]) !== 3) {
            checkEstado = false;
            return;
        }

        if (
            item["fechaInicioCompromiso"] === null ||
            item["fechaInicioCompromiso"] === ""
        ) {
            checkFechaInicio = false;
            return;
        }

        if (
            item["fechaFinCompromiso"] === null ||
            item["fechaFinCompromiso"] === ""
        ) {
            checkFechaFin = false;
            return;
        }

        if (
            item["idUsuarioCompromiso"] === null ||
            item["idUsuarioCompromiso"] === ""
        ) {
            checkIdUsuarioCompromiso = false;
            return;
        }

        if (item["fileCompromiso"] === null || item["fileCompromiso"] === "") {
            checkFileCompromiso = false;
            return;
        }
    });

    if (!checkEstado) {
        mostrarNotificacion(
            "warning",
            "Para finalizar los compromisos deben estar en estado TERMINADO",
            "Verificar"
        );
        return;
    }

    if (!checkFechaInicio || !checkFechaFin) {
        mostrarNotificacion(
            "warning",
            "Para finalizar los compromisos deben contener las fechas",
            "Verificar"
        );
        return;
    }

    if (!checkIdUsuarioCompromiso) {
        mostrarNotificacion(
            "warning",
            "Para finalizar los compromisos deben tener un usuario asignado",
            "Verificar"
        );
        return;
    }

    if (!checkFileCompromiso) {
        mostrarNotificacion(
            "warning",
            "Para finalizar los compromisos deben contener un file",
            "Verificar"
        );
        return;
    }

    let txtParticipacion = $("#txtParticipacion").val();
    let txtMedidas = $("#txtMedidas").val();

    if (txtParticipacion == "" || txtMedidas == "") {
        mostrarNotificacion(
            "warning",
            "Ingresar los textos de la participacion y medidas",
            "Verificar"
        );
        return;
    }

    swal({
        type: "warning",
        title: "Esta seguro de finalizar el compromiso?",
        text: "Asegurese de validar la informacion!!, ya no podra volver a modificarlo",
        showConfirmButton: true,
        confirmButtonText: "SI",
        showCancelButton: true,
        cancelButtonText: "NO",
        allowOutsideClick: false,
    }).then((result) => {
        if (result) {
            let { id, itemplan, idTipo } = $(element).data();

            $.each(arrayFinalCompromisos, (i, e) => {
                let formData = new FormData();
                formData.append("idCompromisoEntidad", e["idCompromisoEntidad"]);
                formData.append("idEntidadEstacion", e["idEntidadEstacion"]);
                formData.append("idCompromiso", e["idCompromiso"]);
                formData.append("fechaInicioCompromiso", e["fechaInicioCompromiso"]);
                formData.append("fechaFinCompromiso", e["fechaFinCompromiso"]);
                formData.append("idEstadoCompromiso", e["idEstadoCompromiso"]);
                formData.append("idUsuarioCompromiso", e["idUsuarioCompromiso"]);
                formData.append("fileCompromiso", e["fileCompromiso"]);
                formData.append("itemplan", itemplan);

                $.ajax({
                    type: "POST",
                    url: "updateCompromisoEntidad",
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                });
            });

            let formData1 = new FormData();
            formData1.append("id", id);
            formData1.append("idTipo", idTipo);
            formData1.append("itemplan", itemplan);
            formData1.append("txtParticipacion", txtParticipacion);
            formData1.append("txtMedidas", txtMedidas);
            formData1.append("estadoFinalCompromiso", 2);

            $.ajax({
                type: "POST",
                url: "updateLicencia",
                data: formData1,
                contentType: false,
                processData: false,
                cache: false,
            }).done(function(data) {
                data = JSON.parse(data);

                if (data.error == 0) {
                    $("#contTablaCompromisosAll").html(data.tablaCompromisosAll);
                    $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
                    modal("modalCompromiso");
                    $(".select2").select2();

                    arrayFinalCompromisos = data.dataCompromisosAll;
                } else {
                    return;
                }
            });
        }
    });
}

function generarExpediente(element) {
    let { id, itemplan } = $(element).data();

    let nroExpediente = $(`#exp_${itemplan}_${id}`).val();
    let cboTipo = $(`#cmbTipo_${itemplan}_${id}`).val();
    let cboDistrito = $(`#cmbDistrito_${itemplan}_${id}`).val();
    let fechaIni = $(`#fechaIn_${itemplan}_${id}`).val();
    let fechaFin = $(`#fechaFin_${itemplan}_${id}`).val();

    if (nroExpediente == "") {
        mostrarNotificacion(
            "warning",
            "Completar numero de expediente",
            "Verificar"
        );
        return;
    }

    if (cboTipo == "") {
        mostrarNotificacion("warning", "Completar el Tipo", "Verificar");
        return;
    }

    if (cboDistrito == "") {
        mostrarNotificacion("warning", "Completar el Distrito", "Verificar");
        return;
    }

    if (fechaIni == "") {
        mostrarNotificacion("warning", "Completar la fecha inicio", "Verificar");
        return;
    }

    if (fechaFin == "") {
        mostrarNotificacion("warning", "Completar la fecha fin", "Verificar");
        return;
    }

    swal({
        type: "warning",
        title: "Esta seguro de generar el expediente?",
        text: "Asegurese de validar la informacion!!",
        showConfirmButton: true,
        confirmButtonText: "SI",
        showCancelButton: true,
        cancelButtonText: "NO",
        allowOutsideClick: false,
    }).then((result) => {
        if (result) {
            let formData = new FormData();
            formData.append("id", id);
            formData.append("nroExpediente", nroExpediente);
            formData.append("cboTipo", cboTipo);
            formData.append("cboDistrito", cboDistrito);
            formData.append("fechaIni", fechaIni);
            formData.append("fechaFin", fechaFin);
            formData.append("itemplan", itemplan);

            $.ajax({
                type: "POST",
                url: "generarExpediente",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
            }).done(function(data) {
                data = JSON.parse(data);

                if (data.error == 0) {
                    $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
                    $(".select2").select2();

                    mostrarNotificacion("success", "Aviso", data.msj);
                } else {
                    return;
                }
            });
        }
    });
}

function updEntidadEstado(element) {
    swal({
        type: "warning",
        title: "Esta seguro de actualizar el estado?",
        text: "Asegurese de validar la informacion!!",
        showConfirmButton: true,
        confirmButtonText: "SI",
        showCancelButton: true,
        cancelButtonText: "NO",
        allowOutsideClick: false,
    }).then((result) => {
        if (result) {
            let { id, itemplan } = $(element).data();

            let idEntidadEstado = $(`#${element.id} option:selected`).val();

            let formData = new FormData();
            formData.append("id", id);
            formData.append("idEntidadEstado", idEntidadEstado);
            formData.append("itemplan", itemplan);

            $.ajax({
                type: "POST",
                url: "updEntidadEstado",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
            }).done(function(data) {
                data = JSON.parse(data);

                if (data.error == 0) {
                    $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
                    $(".select2").select2();
                } else {
                    return;
                }
            });
        }
    });
}

function finalizarEntidadAmbiental(element) {
    let { itemplan } = $(element).data();

    swal({
        type: "warning",
        title: "Esta seguro de finalizar?",
        text: "Asegurese de validar la informacion!!",
        showConfirmButton: true,
        confirmButtonText: "SI",
        showCancelButton: true,
        cancelButtonText: "NO",
        allowOutsideClick: false,
    }).then((result) => {
        if (result) {
            $.ajax({
                type: "POST",
                url: "finalizarEntidadInstrumentoAmbiental",
                data: {
                    itemplan: itemplan,
                },
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    modal("modalEntidadAmbiental");
                    $("#contTabla").html(data.tablaAsigGrafo);
                    initDataTable("#data-table");
                    mostrarNotificacion(
                        "success",
                        "Aviso",
                        "Se cerro la licencia correctamente"
                    );
                } else {
                    mostrarNotificacion("error", "Aviso", data.msj);
                }
            });
        }
    });
}

function eliminarEntidadAmbiental(element) {
    let { itemplan, id } = $(element).data();

    swal({
        type: "warning",
        title: "Esta seguro de eliminar?",
        text: "Asegurese de validar la informacion!!",
        showConfirmButton: true,
        confirmButtonText: "SI",
        showCancelButton: true,
        cancelButtonText: "NO",
        allowOutsideClick: false,
    }).then((result) => {
        if (result) {
            let formData = new FormData();
            formData.append("id", id);
            formData.append("itemplan", itemplan);

            $.ajax({
                type: "POST",
                url: "eliminarEntidadAmbiental",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    modal("modalEntidadAmbiental");
                    $("#contTabla").html(data.tablaAsigGrafo);
                    initDataTable("#data-table");
                    mostrarNotificacion("success", "Aviso", "Se eliminó correctamente");
                } else {
                    mostrarNotificacion("error", data.msj, "Incorrecto");
                }
            });
        }
    });
}

// Comprobante
function openModalComprobanteEntidad(element) {
    let { itemplan, id, idEntidadEstado, estado } = $(element).data();

    if (estado != 1 && estado != 2) {
        mostrarNotificacion(
            "warning",
            "Aviso",
            "Pendiente de generar el expediente."
        );
        return;
    }

    if (idEntidadEstado == 2) {
        $("#btnAgregarComprobante").addClass("d-none");
    } else {
        $("#btnAgregarComprobante").removeClass("d-none");
    }

    $("#btnAgregarComprobante").data({
        itemplan,
        id,
    });

    $.ajax({
        type: "POST",
        url: "getTablaComprobanteEntidad",
        data: {
            id,
        },
    }).done(function(data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            $("#contTablaComprobanteEntidad").html(data.tablaComprobanteEntidad);

            $("#txtNroComprobante").val("");
            $("#txtFechaEmiComprobante").val("");
            $("#txtMontoComprobante").val("");
            $("#fileComprobante").val("");

            initDataTable("#tbComprobantes");
            modal("modalComprobanteEntidad");
        } else {
            return;
        }
    });
}

function showAgregarComprobante(element) {
    let { id, itemplan, action } = $(element).data();

    if (action == 1) {
        let nroComprobante = $("#txtNroComprobante").val();
        let fechaEmiComprobante = $("#txtFechaEmiComprobante").val();
        let montoComprobante = $("#txtMontoComprobante").val();
        let fileComprobante = $("#fileComprobante")[0].files[0];

        if (
            nroComprobante == "" ||
            fechaEmiComprobante == "" ||
            montoComprobante == "" ||
            fileComprobante == undefined
        ) {
            mostrarNotificacion(
                "warning",
                "Ingresar los campos obligatorios (*)",
                "Incorrecto"
            );
            return;
        }

        let formData = new FormData();
        formData.append("nroComprobante", nroComprobante);
        formData.append("fechaEmiComprobante", fechaEmiComprobante);
        formData.append("montoComprobante", montoComprobante);
        formData.append("fileComprobante", fileComprobante);
        formData.append("id", id);
        formData.append("itemplan", itemplan);

        $.ajax({
            data: formData,
            url: "registrarComproEntidadAmbiental",
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
        }).done(function(data) {
            data = JSON.parse(data);

            if (data.error == 0) {
                $("#txtNroComprobante").val("");
                $("#txtFechaEmiComprobante").val("");
                $("#txtMontoComprobante").val("");
                $("#fileComprobante").val("");

                $("#contTablaComprobanteEntidad").html(data.tablaComprobanteEntidad);
                $("#contTablaEntidadAmbiental").html(data.tablaLicencia);
                $(".select2").select2();
                initDataTable("#tbComprobantes");

                mostrarNotificacion("success", "Aviso", data.msj);
            } else if (data.error == 1) {
                mostrarNotificacion("error", "Incorrecto", data.msj);
            }
        });
    }

    if ($("#contComprobanteAgregar").hasClass("d-none")) {
        $("#contComprobanteAgregar").slideDown("slow", function() {
            $(this).removeClass("d-none");
            $("#btnCerrarAgregarComprobante").removeClass("d-none");
        });

        $("#btnAgregarComprobante").html('<i class="zmdi zmdi-check"></i>');
        $("#btnAgregarComprobante").data("action", 1);
    } else {
        $("#contComprobanteAgregar").slideUp("slow", function() {
            $(this).addClass("d-none");
            $("#btnCerrarAgregarComprobante").addClass("d-none");
        });

        $("#btnAgregarComprobante").html(
            '<i class="zmdi zmdi-plus-square"></i> Agregar'
        );
        $("#btnAgregarComprobante").data("action", 0);
    }
}

function eliminarComprobante(element) {
    let { id, idEntidadEstacion, ruta } = $(element).data();

    swal({
        type: "warning",
        title: "Esta seguro de eliminar?",
        text: "Asegurese de validar la informacion!!",
        showConfirmButton: true,
        confirmButtonText: "SI",
        showCancelButton: true,
        cancelButtonText: "NO",
        allowOutsideClick: false,
    }).then((result) => {
        if (result) {
            let formData = new FormData();
            formData.append("id", id);
            formData.append("idEntidadEstacion", idEntidadEstacion);
            formData.append("ruta", ruta);

            $.ajax({
                type: "POST",
                url: "eliminarComprobante",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $("#contTablaComprobanteEntidad").html(data.tablaComprobanteEntidad);
                    initDataTable("#tbComprobantes");

                    mostrarNotificacion("success", "Aviso", "Se eliminó correctamente");
                } else {
                    mostrarNotificacion("error", data.msj, "Incorrecto");
                }
            });
        }
    });
}