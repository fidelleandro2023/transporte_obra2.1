var flgDropzone = 0;
var flgMosrarEvi = 0;
var idItemPlanEntDet = null;
var idComprobanteGlob = null;
var idAcotacionGlob = null;

new Vue({
    el: '#licenciaVue',
    data: () => {
        return {
            tablaHTML: [],
            tablaDetalle: [],
            tablaComprobantes: [],
            tablaAcotaciones: [],
            objDataInsert: [],
            objDataInsertCompro: any = {
                fecha_emision: null,
                desc_reembolso: null,
                monto: null,
                ruta_foto: null,
                estado_valida: null,
                idReembolso: null,
                flg_valida_evidencia: null,
                flg_preliqui_admin: null
            },
            flgMostrarTabla: null,
            flgMostrarTablaProv: null,
            idItemPlanEstaDetalleGlob: null,
            flgProvinciaGlob: null,
            flgMostrarTablaAcota: null,
            indiceItemPlanEsaDetalleGlob: null,
            objDataInsertAcota: any = {
                fecha_acotacion: null,
                desc_acotacion: null,
                monto: null,
                ruta_foto: null,
                estado_valida: null,
                idAcotacion: null
            },
            tablaEntProv: [],
            jsonUpdateEntProv: any = {
                fecha_fin: null,
                fecha_inicio: null,
                flg_acotacion_valida: null,
                flg_validado: null,
                iditemplan_estacion_licencia_det: null,
                nro_cheque: null,
                correo_usuario_valida: null
            },
            itemPlanGlob: null,
            idEstacionGlob: null,
            arrayDistritos: [],
            arrayProyectos: [],
            arraySubProyectos: [],
            arrayJefaturas: [],
            arrayEmpresasColab: [],
            arrayFase:[],
            jsonBusqueda: any = {
                idProyecto: 0,
                idSubProyecto: 0,
                jefatura: "",
                idEmpresaColab: 0,
                idFase: 0
            }
        }
    }, methods: {
        getDistritos: function () {
            vue = this;
            $.ajax({
                type: 'POST',
                url: 'getDistritos'
            }).done(function (data) {
                data = JSON.parse(data);
                vue.arrayDistritos = data.arrayDistritos;
            });
        },
        getProyectos: function () {
            vue = this;
            $.ajax({
                type: 'POST',
                url: 'getProyectos'
            }).done(function (data) {
                data = JSON.parse(data);
                vue.arrayProyectos = data.arrayProyectos;
            });

        },
        getSubProyecto: function () {
            vue = this;
            if (vue.idProyecto == 0) {
                vue.arraySubProyectos = [];
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'getSubProyectos',
                    data: {
                        idProyecto: vue.jsonBusqueda.idProyecto
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    vue.arraySubProyectos = data.arraySubProyectos;
                });

            }
        },
        getRegiones: function () {
            vue = this;
            $.ajax({
                type: 'POST',
                url: 'getRegiones'
            }).done(function (data) {
                data = JSON.parse(data);
                vue.arrayJefaturas = data.arrayJefaturas;
            });
        },
        getEmpresasColab: function () {
            vue = this;
            $.ajax({
                type: 'POST',
                url: 'getEmpresasColab'
            }).done(function (data) {
                data = JSON.parse(data);
                vue.arrayEmpresasColab = data.arrayEmpresasColab;
            });
        },
        getFase: function () {
            vue = this;
            $.ajax({
                type: 'POST',
                url: 'getFase'
            }).done(function (data) {
                data = JSON.parse(data);
                vue.arrayFase = data.arrayFase;
            });
        },
        getTablaItemPlanUsuario: function () {
            vue = this;
            $.ajax({
                type: 'POST',
                url: 'getTablaItemPlanUsuario'
            }).done(function (data) {
                data = JSON.parse(data);
                vue.tablaHTML = data.tablaItems;
            });
        },
        getTablaItemPlan: function () {
            vue = this;
            vue.tablaHTML = [];
            $.ajax({
                type: 'POST',
                url: 'getTablaItemPlan',
                data: {
                    arrayFiltros: vue.jsonBusqueda
                }
            }).done(function (data) {
                data = JSON.parse(data);
                vue.tablaHTML = data.tablaItems;
            });
        },
        mostrarDetalle: function (itemPlan, idEstacion, flgProvincia) {
            vue = this;

            vue.tablaDetalle = [];
            vue.objDataInsert = [];
            vue.flgProvinciaGlob = flgProvincia;
            vue.itemPlanGlob = itemPlan;
            vue.idEstacionGlob = idEstacion;

            document.getElementById("formRegistrarEntidad").reset();
            $.ajax({
                type: 'POST',
                'url': 'getENTS',
                data: {
                    itemPlan: itemPlan,
                    idEstacion: idEstacion
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaDetalle = data.tablaItemPlanDetalle;

                    vue.makeArrayInsertItemPlanDet();

                    if (flgProvincia == 1) {
                        modal('modalRegistrarEntidades');
                    } else {
                        modal('modalEntProv');
                    }

                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No hay detalles para ese itemPlan');
                }
            });

        },
        abrirModalEvidencia: function (idDetalle, flgTipo, flgValida, descReem, flgModalProv, index) {
            idItemPlanEntDet = idDetalle;

            if (descReem == '') {
                descReem = null;
            }

            $.ajax({
                type: 'POST',
                'url': 'crtData',
                data: {
                    idDetalle: idDetalle,
                    flgTipo: flgTipo,
                    idItemPlanEstaDetalle: vue.idItemPlanEstaDetalleGlob,
                    descReembolso: descReem
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 1) {
                    mostrarNotificacion('error', 'Hubo un error al crear la sesi&oacute;n');
                } else {
                    if (data.idComprobante != null && data.idComprobante != undefined) {
                        idComprobanteGlob = data.idComprobante;
                        if (flgValida == 1) {
                            vue.objDataInsertCompro.idReembolso = data.idComprobante;
                        } else if (flgValida == 2) {
                            vue.tablaComprobantes[index]['idReembolso'] = data.idComprobante;
                            vue.tablaComprobantes[index]['ruta_foto'] = 'asd';
                        }
                    }
                }
            });
            if (flgModalProv == null || flgModalProv == undefined) {
                if (flgTipo == 1) {
                    modal('modalSubirEvidencia');
                } else if (flgTipo == 2) {
                    modal('modalSubirFotoComprobante');
                }
            } else if (flgModalProv == 1) {
                modal('modalSubirEviProv');
            } else if (flgModalProv == 2) {
                modal('modalSubirEviComproProv');
            }

        },
        abrirModalComprobantes: function (idItemPlanEstaDetalle) {
            vue.tablaComprobantes = [];
            vue.idItemPlanEstaDetalleGlob = idItemPlanEstaDetalle;
            document.getElementById("formRegistrarComprobante").reset();
            vue.objDataInsertCompro = {
                fecha_emision: null,
                desc_reembolso: null,
                monto: null,
                ruta_foto: null,
                estado_valida: null,
                idReembolso: null,
                flg_valida_evidencia: null,
                flg_preliqui_admin: null
            };

            $.ajax({
                type: 'POST',
                'url': 'getComprobantes',
                data: {
                    idItemPlanEstaDetalle: idItemPlanEstaDetalle
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaComprobantes = data.arrayTablaComprobantes;
                    vue.tablaComprobantes.forEach(function (row) {
                        if (row['flg_valida_evidencia'] == 0 || row['flg_valida_evidencia'] == null) {
                            row['flg_valida_evidencia'] = false;
                        } else {
                            row['flg_valida_evidencia'] = true;
                        }
                        if (row['flg_preliqui_admin'] == 0 || row['flg_preliqui_admin'] == null) {
                            row['flg_preliqui_admin'] = false;
                        } else {
                            row['flg_preliqui_admin'] = true;
                        }
                    });
                    vue.flgMostrarTabla = 0;
                } else if (data.error == 1) {
                    vue.flgMostrarTabla = 1;
                }
            });

            modal('modalComprobantes');
        },
        updateComprobante: function (index) {

            jsonValida = { desc_reembolso: vue.tablaComprobantes[index]['desc_reembolso'], fecha_emision: vue.tablaComprobantes[index]['fecha_emision'], monto: vue.tablaComprobantes[index]['monto'] };

            if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '')))) {
                mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
                return;
            }

            $.ajax({
                type: 'POST',
                'url': 'updateComprobantePreliquidado',
                data: {
                    listaComprobante: vue.tablaComprobantes[index],
                    flgValidaEvi: (vue.tablaComprobantes[index]['flg_valida_evidencia'] == true ? 1 : 0)
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    var idItemPlanDet = vue.tablaComprobantes[index]['iditemplan_estacion_licencia_det'];
                    if (vue.tablaComprobantes[index]['flg_valida_evidencia'] == true) {
                        vue.tablaComprobantes[index]['estado_valida'] = 2;
                        $("#trEntidad" + idItemPlanDet).css("background", "#1affff");
                    } else {
                        vue.tablaComprobantes[index]['estado_valida'] = 1;
                    }
                    var indiceItemPlanDet = $('#txtDescEnt' + idItemPlanDet).attr('data-index');
                    vue.tablaDetalle[indiceItemPlanDet]["flg_validado"] = 2;
                    vue.objDataInsert[indiceItemPlanDet]['flg_validado'] = 2;
                    mostrarNotificacion('success', 'Se actualiz&oacute; correctamente');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No se pudo liquidar el comprobante');
                }
            });

        },
        saveComprobante: function () {
            vue = this;
            if (flgGuardarComprobante == 1) {

                jsonValida = { txtDescReembolso: $('#txtDescReembolso').val(), txtFechaEmiCompro: $('#txtFechaEmiCompro').val(), txtMontoCompro: $('#txtMontoCompro').val() };
                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '')))) {
                    mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    'url': 'saveComprobanteDetalle',
                    data: {
                        objInsertComprobante: vue.objDataInsertCompro,
                        idItemPlanEstaDetalle: vue.idItemPlanEstaDetalleGlob,
                        flgValidaEvi: (vue.objDataInsertCompro['flg_valida_evidencia'] == true ? 1 : 0)
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        var indiceItemPlanDet = $("#txtDescEnt" + vue.idItemPlanEstaDetalleGlob).attr("data-index");
                        vue.tablaDetalle[indiceItemPlanDet]["flg_validado"] = 2;
                        vue.objDataInsert[indiceItemPlanDet]["flg_validado"] = 2;
                        if (vue.objDataInsertCompro['flg_valida_evidencia'] == true) {
                            vue.objDataInsertCompro['estado_valida'] = 2;
                            $("#trEntidad" + vue.idItemPlanEstaDetalleGlob).css("background", "#1affff");
                        } else {
                            vue.objDataInsertCompro['estado_valida'] = 1;
                        }

                        modal('modalComprobantes');
                        flgGuardarComprobante = 0;

                        mostrarNotificacion('success', 'Se guard&oacute; correctamente');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'No se pudo guardar el comprobante');
                    }
                });
            } else {
                mostrarNotificacion('error', 'Debe subir la evidencia');
            }


        },
        saveComproAdministrativo: function () {
            vue = this;
            jsonValida = { txtDescReembolso: $('#txtDescReembolso').val(), txtFechaEmiCompro: $('#txtFechaEmiCompro').val(), txtMontoCompro: $('#txtMontoCompro').val() };
            if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '')))) {
                mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
                return;
            }

            $.ajax({
                type: 'POST',
                'url': 'saveComproAdministrativo',
                data: {
                    objInsertComprobante: vue.objDataInsertCompro,
                    idItemPlanEstaDetalle: vue.idItemPlanEstaDetalleGlob,
                    flg_preliqui_admin: (vue.objDataInsertCompro['flg_preliqui_admin'] == true ? 1 : 0),
                    idComprobante: idComprobanteGlob
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    var indiceItemPlanDet = $("#txtDescEnt" + vue.idItemPlanEstaDetalleGlob).attr("data-index");
                    vue.tablaDetalle[indiceItemPlanDet]["flg_validado"] = 2;
                    vue.objDataInsert[indiceItemPlanDet]["flg_validado"] = 2;
                    if (vue.objDataInsertCompro['flg_valida_evidencia'] == true) {
                        vue.objDataInsertCompro['estado_valida'] = 2;
                        $("#trEntidad" + vue.idItemPlanEstaDetalleGlob).css("background", "#1affff");
                    } else {
                        vue.objDataInsertCompro['estado_valida'] = 1;
                    }

                    modal('modalComprobantes');

                    mostrarNotificacion('success', 'Se guard&oacute; correctamente');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No se pudo guardar el comprobante');
                }
            });

        },
        liquidarDetalle: function (index) {
            vue = this;
            if (vue.objDataInsert[index]['flg_validado'] != 2 || vue.objDataInsert[index]['flg_validado'] != '2') {
                $.ajax({
                    type: 'POST',
                    'url': 'updateItemPLanEstaLicenDet',
                    data: {
                        listaItemPlanDetalle: vue.objDataInsert[index]
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        //vue.abrirModalComprobantes(idItemPlanEstaDetalle);
                        vue.tablaDetalle[index]['flg_validado'] = 1;
                        vue.objDataInsert[index]['flg_validado'] = 1;
                        mostrarNotificacion('success', 'Se guard&oacute; correctamente');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Hubo un error al guardar');
                    }
                });
            }
        },
        abrirModalImagenEnt: function (rutaImagen, idItemPlanEstaDetalle, index) {
            vue = this;
            $.ajax({
                type: 'POST',
                'url': 'getRutaEvidenciaItemPlanEsta',
                data: {
                    idItemPlanEstaDetalle: idItemPlanEstaDetalle
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    if (index != null) {
                        document.getElementById('evidenciaEnt').src = data.rutaImagen;
                        vue.tablaDetalle[index]['ruta_pdf'] = data.rutaImagen;
                        modal('modalEvidenciaEnt');
                    } else {
                        document.getElementById('evidenciaEntProv').src = data.rutaImagen;
                        vue.tablaEntProv['ruta_pdf'] = data.rutaImagen;
                        modal('modalEviEntProv');
                    }


                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No hay foto para mostrar');
                }
            });

        },
        abrirModalImagenCompro: function (idReembolso, index, flgListCompro, flgProv) {
            if (flgListCompro == 1) {
                vue = this;
                $.ajax({
                    type: 'POST',
                    'url': 'getRutaEvidenciaReembolso',
                    data: {
                        idReembolso: idReembolso
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        vue.tablaComprobantes[index]['ruta_foto'] = data.rutaImagen;
                        if (flgProv == null || flgProv == undefined) {
                            document.getElementById('evidenciaCompro').src = data.rutaImagen;
                        } else {
                            document.getElementById('evidenciaComproProv').src = data.rutaImagen;
                        }
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'No hay foto para mostrar');
                    }
                });
            }
            if (flgProv == null || flgProv == undefined) {
                modal('modalEvidenciaComprobante');
            } else {
                modal('modalEvidenciaComprobanteProv');
            }

        },
        abrirModalAcotacion: function (idItemPlanEstaDetalle, index) {
            vue.tablaAcotaciones = [];
            vue.idItemPlanEstaDetalleGlob = idItemPlanEstaDetalle;
            vue.indiceItemPlanEsaDetalleGlob = index;
            document.getElementById("formEntProv").reset();
            $('#btnSubirFotoAcota').prop('disabled', 'true');
            $('#btnVerEviAcota').css('display', 'none');
            $('#iconNoImgAcota').css('display', 'block');
            vue.objDataInsertAcota = {
                fecha_acotacion: null,
                desc_acotacion: null,
                monto: null,
                ruta_foto: null,
                estado_valida: null,
                idAcotacion: null
            }

            $.ajax({
                type: 'POST',
                'url': 'getAcotaciones',
                data: {
                    idItemPlanEstaDetalle: idItemPlanEstaDetalle
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaAcotaciones = data.arrayTablaAcotaciones;
                    vue.flgMostrarTablaAcota = 0;
                } else if (data.error == 1) {
                    vue.flgMostrarTablaAcota = 1;
                }
            });
            modal('modalAcotaciones');
        },
        abrirModalEviAcota: function (idAcotacion, descAcota, flgValida, index) {

            if ((descAcota == null || descAcota == undefined || descAcota == '')) {
                mostrarNotificacion('error', 'Debe ingresar un numero de Acotaci&oacute;n para poder subir la evidencia');
                return;
            }

            $.ajax({
                type: 'POST',
                'url': 'crtDataAcota',
                data: {
                    idAcotacion: idAcotacion,
                    descAcota: descAcota,
                    idItemPlanEstaDetalle: vue.idItemPlanEstaDetalleGlob,
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 1) {
                    mostrarNotificacion('error', 'Hubo un error al crear la sesion');
                } else {
                    if (data.idAcotacion != null && data.idAcotacion != undefined) {
                        idAcotacionGlob = data.idAcotacion;
                        if (flgValida == 1) {
                            vue.objDataInsertAcota.idAcotacion = data.idAcotacion;
                        } else if (flgValida == 2) {
                            vue.tablaAcotaciones[index]['idAcotacion'] = data.idAcotacion;
                        }
                    }
                }
            });
            modal('modalSubirEviAcota');

        },
        abrirModalImagenAcota: function (idAcotacion, index, flgListAcota) {
            if (flgListAcota == 1) {
                vue = this;
                $.ajax({
                    type: 'POST',
                    'url': 'getRutaEvidenciaAcotacion',
                    data: {
                        idAcotacion: idAcotacion
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        vue.tablaAcotaciones[index]['ruta_foto'] = data.rutaImagen;
                        document.getElementById('evidenciaAcota').src = data.rutaImagen;
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'No hay foto para mostrar');
                    }
                });
            }
            modal('modalEviAcotacion');
        },
        saveAcotacion: function () {
            vue = this;
            if (flgGuardarAcota == 1) {

                jsonValida = { txtDescAcota: $('#txtDescAcota').val(), txtfechaAcota: $('#txtfechaAcota').val(), txtMonto: $('#txtMonto').val() };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '')))) {
                    mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    'url': 'saveAcotacionDetalle',
                    data: {
                        objInsertAcota: vue.objDataInsertAcota,
                        idItemPlanEstaDetalle: vue.idItemPlanEstaDetalleGlob
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        mostrarNotificacion('success', 'Se guardo correctamente');
                        vue.tablaDetalle[vue.indiceItemPlanEsaDetalleGlob]["flg_acotacion_valida"] = 1;
                        modal('modalAcotaciones');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'No se pudo preliquidar la acotaci&oacute;n');
                    }
                });
            } else {
                mostrarNotificacion('error', 'Debe subir la evidencia');
            }

        },
        updateAcotacion: function (index) {
            vue = this;

            jsonValida = { desc_acotacion: vue.tablaAcotaciones[index]['desc_acotacion'], fecha_acotacion: vue.tablaAcotaciones[index]['fecha_acotacion'], monto: vue.tablaAcotaciones[index]['monto'] };

            if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '')))) {
                mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
                return;
            }

            $.ajax({
                type: 'POST',
                'url': 'updateAcotacionPreliquidado',
                data: {
                    listaAcotacion: vue.tablaAcotaciones[index]
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaDetalle[vue.indiceItemPlanEsaDetalleGlob]["flg_acotacion_valida"] = 1;
                    mostrarNotificacion('success', 'Se guard&oacute; correctamente');
                    modal('modalAcotaciones');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No se pudo preliquidar la acotaci&oacute;n');
                }
            });

        },
        abrirModalEntProvxLiquidar: function (index, idItemPlanEstaDetalle, nroCheque) {
            vue = this;
            $.ajax({
                type: 'POST',
                'url': 'updateItemPlanEstaDetByNroCheque',
                data: {
                    idItemPlanEstaDetalle: idItemPlanEstaDetalle,
                    nroCheque: nroCheque
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaEntProv = vue.tablaDetalle[index];
                    vue.jsonUpdateEntProv = vue.objDataInsert[index];
                    modal('modalEntxLiquiProv');
                } else if (data.error == 1) {

                    //mostrarNotificacion('error', 'No hay comprobantes para ese detalle');
                }
            });

        },
        liquidarDetalleProv: function () {
            vue = this;
            $.ajax({
                type: 'POST',
                'url': 'updateItemPLanEstaLicenDet',
                data: {
                    listaItemPlanDetalle: vue.jsonUpdateEntProv
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    // vue.abrirModalComproProv(idItemPlanEstaDetalle);
                    vue.tablaEntProv['flg_validado'] = 1;
                    vue.jsonUpdateEntProv['flg_validado'] = 1;
                    mostrarNotificacion('success', 'Se guard&oacute; correctamente');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Hubo un error al guardar');
                }
            });
        },
        abrirModalComproProv: function (idItemPlanEstaDetalle) {
            vue.tablaComprobantes = [];
            vue.idItemPlanEstaDetalleGlob = idItemPlanEstaDetalle;
            vue.objDataInsertCompro = {
                fecha_emision: null,
                desc_reembolso: null,
                monto: null,
                ruta_foto: null,
                estado_valida: null,
                idReembolso: null,
                flg_valida_evidencia: null,
                flg_preliqui_admin: null
            };

            $.ajax({
                type: 'POST',
                'url': 'getComprobantes',
                data: {
                    idItemPlanEstaDetalle: idItemPlanEstaDetalle
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaComprobantes = data.arrayTablaComprobantes;
                    vue.tablaComprobantes.forEach(function (row) {
                        if (row['flg_valida_evidencia'] == 0 || row['flg_valida_evidencia'] == null) {
                            row['flg_valida_evidencia'] = false;
                        } else {
                            row['flg_valida_evidencia'] = true;
                        }
                        if (row['flg_preliqui_admin'] == 0 || row['flg_preliqui_admin'] == null) {
                            row['flg_preliqui_admin'] = false;
                        } else {
                            row['flg_preliqui_admin'] = true;
                        }
                    });
                    vue.flgMostrarTablaProv = 0;
                } else if (data.error == 1) {
                    vue.flgMostrarTablaProv = 1;
                }
            });

            modal('modalComprobantesProv');
        },
        updateComprobanteProv: function (index) {

            jsonValida = { desc_reembolso: vue.tablaComprobantes[index]['desc_reembolso'], fecha_emision: vue.tablaComprobantes[index]['fecha_emision'], monto: vue.tablaComprobantes[index]['monto'] };

            if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '')))) {
                mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
                return;
            }

            $.ajax({
                type: 'POST',
                'url': 'updateComprobantePreliquidado',
                data: {
                    listaComprobante: vue.tablaComprobantes[index],
                    flgValidaEvi: (vue.tablaComprobantes[index]['flg_valida_evidencia'] == true ? 1 : 0)
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    var idItemPlanDet = vue.tablaComprobantes[index]['iditemplan_estacion_licencia_det'];
                    if (vue.tablaComprobantes[index]['flg_valida_evidencia'] == true) {
                        vue.tablaComprobantes[index]['estado_valida'] = 2;
                        $("#trEntidadProv" + idItemPlanDet).css("background", "#1affff");
                    } else {
                        vue.tablaComprobantes[index]['estado_valida'] = 1;
                    }
                    vue.tablaEntProv["flg_validado"] = 2;
                    vue.jsonUpdateEntProv["flg_validado"] = 2;
                    mostrarNotificacion('success', 'Se guard&oacute; correctamente');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No se pudo liquidar el comprobante');
                }
            });

        },
        saveComprobanteProv: function () {
            vue = this;
            if (flgGuardarComprobanteProv == 1) {

                jsonValida = { txtDescReembolsoProv: $('#txtDescReembolsoProv').val(), txtFechaEmiProv: $('#txtFechaEmiProv').val(), txtMontoProv: $('#txtMontoProv').val() };

                if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '')))) {
                    mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    'url': 'saveComprobanteDetalle',
                    data: {
                        objInsertComprobante: vue.objDataInsertCompro,
                        idItemPlanEstaDetalle: vue.idItemPlanEstaDetalleGlob,
                        flgValidaEvi: (vue.objDataInsertCompro['flg_valida_evidencia'] == true ? 1 : 0)
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        vue.tablaEntProv["flg_validado"] = 2;
                        vue.jsonUpdateEntProv["flg_validado"] = 2;
                        if (vue.objDataInsertCompro['flg_valida_evidencia'] == true) {
                            vue.objDataInsertCompro['estado_valida'] = 2;
                            $("#trEntidadProv").css("background", "#1affff");
                        } else {
                            vue.objDataInsertCompro['estado_valida'] = 1;
                        }
                        modal('modalComprobantesProv');
                        flgGuardarComprobanteProv = 0;
                        mostrarNotificacion('success', 'Se guard&oacute; correctamente');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'No se pudo guardar el comprobante');
                    }
                });
            } else {
                mostrarNotificacion('error', 'Debe subir la evidencia');
            }

        },
        saveComproAdministrativoProv: function () {
            vue = this;
            jsonValida = { txtDescReembolsoProv: $('#txtDescReembolsoProv').val(), txtFechaEmiProv: $('#txtFechaEmiProv').val(), txtMontoProv: $('#txtMontoProv').val() };
            if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '')))) {
                mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
                return;
            }

            $.ajax({
                type: 'POST',
                'url': 'saveComproAdministrativo',
                data: {
                    objInsertComprobante: vue.objDataInsertCompro,
                    idItemPlanEstaDetalle: vue.idItemPlanEstaDetalleGlob,
                    flg_preliqui_admin: (vue.objDataInsertCompro['flg_preliqui_admin'] == true ? 1 : 0),
                    idComprobante: idComprobanteGlob
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    var indiceItemPlanDet = $("#txtDescEnt" + vue.idItemPlanEstaDetalleGlob).attr("data-index");
                    vue.tablaDetalle[indiceItemPlanDet]["flg_validado"] = 2;
                    vue.objDataInsert[indiceItemPlanDet]["flg_validado"] = 2;
                    if (vue.objDataInsertCompro['flg_valida_evidencia'] == true) {
                        vue.objDataInsertCompro['estado_valida'] = 2;
                        $("#trEntidad" + vue.idItemPlanEstaDetalleGlob).css("background", "#1affff");
                    } else {
                        vue.objDataInsertCompro['estado_valida'] = 1;
                    }

                    modal('modalComprobantesProv');

                    mostrarNotificacion('success', 'Se guard&oacute; correctamente');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No se pudo guardar el comprobante');
                }
            });

        },
        preliqAdmin: function (idChbx) {
            vue = this;
            var options = { year: 'numeric', month: '2-digit', day: '2-digit' };
            flgPreliquiAdmin = document.getElementById(idChbx).checked;
            if (flgPreliquiAdmin == true) {
                vue.objDataInsertCompro.desc_reembolso = 'XXX';
                vue.objDataInsertCompro.fecha_emision = ((new Date()).toLocaleDateString('ja-JP', options)).split('/').join('-');
                vue.objDataInsertCompro.monto = 0;
            } else {
                vue.objDataInsertCompro.desc_reembolso = null;
                vue.objDataInsertCompro.fecha_emision = null;
                vue.objDataInsertCompro.monto = null;
            }

        },
        addComprobante: function () {
            vue = this;
            vue.tablaComprobantes.push({
                idReembolso: null,
                desc_reembolso: null,
                fecha_emision: null,
                monto: null,
                ruta_foto: null,
                estado_valida: null,
                iditemplan_estacion_licencia_det: vue.idItemPlanEstaDetalleGlob,
                flg_valida_evidencia: false
            });
        },
        deleteComprobante: function (index, idReembolso, idItemPlanDet) {
            vue = this;
            var flgEliminar = 0;
            if (idReembolso != null && idReembolso != undefined) {
                $.ajax({
                    type: 'POST',
                    'url': 'deleteComprobante',
                    data: {
                        idReembolso: idReembolso,
                        idItemPlanDet: idItemPlanDet
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {//elimino correctamente el registro
                        mostrarNotificacion('success', 'Se elimin&oacute; correctamente el comprobante');

                    } else if (data.error == 1) {
                        flgEliminar = 1;
                        mostrarNotificacion('error', 'No se pudo eliminar el comprobante');
                    }
                });
            }
            if (flgEliminar == 0) {
                vue.tablaComprobantes.splice(index, 1);
            }

        },
        descargarPDFCompro: function (idReembolso, index, flgRuta, flgProv) {
            if (flgRuta == 2) {
                $.ajax({
                    type: 'POST',
                    'url': 'getRutaEvidenciaReembolso',
                    data: {
                        idReembolso: idReembolso
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        vue.tablaComprobantes[index]['ruta_foto'] = data.rutaImagen;
                        window.open(data.rutaImagen);
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'No hay comprobante para mostrar');
                    }
                });

            } else if (flgRuta == 1) {
                if (flgProv == 0) {
                    if (idComprobanteGlob != null && idComprobanteGlob != undefined && nombreFile2 != null && nombreFile2 != undefined) {
                        window.open("uploads/licencias/evidencia_fotos/comprobantes/comprobante" + idComprobanteGlob + "/" + nombreFile2);
                    } else {
                        mostrarNotificacion('error', 'No hay comprobante para mostrar');
                    }
                } else {
                    if (idComprobanteGlob != null && idComprobanteGlob != undefined && nombreFile5 != null && nombreFile5 != undefined) {
                        window.open("uploads/licencias/evidencia_fotos/comprobantes/comprobante" + idComprobanteGlob + "/" + nombreFile5);
                    } else {
                        mostrarNotificacion('error', 'No hay comprobante para mostrar');
                    }
                }

            }
            //window.location.href = rutaImagen;
        },
        descargarPDFEntidad: function (idItemPlanDet, index, flgRuta, flgProv) {
            if (flgRuta == 2) {
                $.ajax({
                    type: 'POST',
                    'url': 'getRutaEvidenciaItemPlanEsta',
                    data: {
                        idItemPlanEstaDetalle: idItemPlanDet
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        vue.tablaDetalle[index]['ruta_pdf'] = data.rutaImagen;
                        window.open(data.rutaImagen);
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'No hay PDF para mostrar');
                    }
                });

            } else if (flgRuta == 1) {
                if (flgProv == 0) {
                    if (idItemPlanEntDet != null && idItemPlanEntDet != undefined && nombreFile != null && nombreFile != undefined) {
                        window.open("uploads/licencias/evidencia_fotos/itemPlanEstaDet" + idItemPlanEntDet + "/" + nombreFile);
                    } else {
                        mostrarNotificacion('error', 'No hay PDF para mostrar');
                    }
                } else {
                    if (idItemPlanEntDet != null && idItemPlanEntDet != undefined && nombreFile4 != null && nombreFile4 != undefined) {
                        window.open("uploads/licencias/evidencia_fotos/itemPlanEstaDet" + idItemPlanEntDet + "/" + nombreFile4);
                    } else {
                        mostrarNotificacion('error', 'No hay PDF para mostrar');
                    }
                }


            }
            //window.location.href = rutaImagen;
        },
        descargarPDFAcota: function (idAcotacion, index, flgRuta) {
            if (flgRuta == 2) {
                $.ajax({
                    type: 'POST',
                    'url': 'getRutaEvidenciaAcotacion',
                    data: {
                        idAcotacion: idAcotacion
                    },
                    'async': false

                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        vue.tablaAcotaciones[index]['ruta_foto'] = data.rutaImagen;
                        window.open(data.rutaImagen);
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'No hay acotaci&oacute;n para mostrar');
                    }
                });

            } else if (flgRuta == 1) {
                if (idAcotacionGlob != null && idAcotacionGlob != undefined && nombreFile3 != null && nombreFile3 != undefined) {
                    window.open("uploads/licencias/evidencia_fotos/acotaciones/acotacion" + idAcotacionGlob + "/" + nombreFile3);
                } else {
                    mostrarNotificacion('error', 'No hay acotaci&oacute;n para mostrar');
                }

            }
        },
        abrirModalRegisEnt: function () {
            arrayEntidades = [];
            $.ajax({
                type: 'POST',
                url: 'getEntidadesLic',
                data: {
                    itemplan: vue.itemPlanGlob,
                    idEstacion: vue.idEstacionGlob
                }
            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    // $('#formEntidades').html(data.htmlEntidades);
                    $('#idCmbEnt').html(data.htmlEntidades);
                    modal('modalRegistrarEnt');
                } else {
                    alert('error Interno intentelo de nuevo.');
                }
            });
        },
        registrarEntidades: function () {
            vue = this;
            var idEntidad = $('#idCmbEnt').val();
            if (idEntidad != null && idEntidad != undefined && idEntidad != 0) {
                $.ajax({
                    type: 'POST',
                    url: 'registrarEntidades',
                    data: {
                        itemplan: vue.itemPlanGlob,
                        idEstacion: vue.idEstacionGlob,
                        idEntidad : idEntidad
                        // arrayIdEntidades: arrayEntidades
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        vue.tablaDetalle = data.tablaItemPlanDetalle;
                        vue.objDataInsert = [];
                        vue.makeArrayInsertItemPlanDet();
                        mostrarNotificacion('success', data.msj);
                        modal('modalRegistrarEnt');
                    } else {
                        mostrarNotificacion('success', data.msj);
                    }
                });
            }

        },
        makeArrayInsertItemPlanDet: function () {
            vue.tablaDetalle.forEach(function (row) {
                vue.objDataInsert.push({
                    iditemplan_estacion_licencia_det: row.iditemplan_estacion_licencia_det,
                    fecha_inicio: row.fecha_inicio,
                    fecha_fin: row.fecha_fin,
                    flg_validado: row.flg_validado,
                    nro_cheque: row.nro_cheque,
                    flg_acotacion_valida: row.flg_acotacion_valida,
                    correo_usuario_valida: row.correo_usuario_valida,
                    idDistrito: row.idDistrito,
                    codigo_expediente: row.codigo_expediente,
                    flg_tipo: row.flg_tipo
                });
            });
        }
    },
    mounted: function () {
        this.getTablaItemPlanUsuario();
        this.getDistritos();
        this.getProyectos();
        this.getRegiones();
        this.getEmpresasColab();
        this.getFase();
    },
    updated: function () {
        if (!$.fn.dataTable.isDataTable('#tablaItemPlan')) {
            $("#tablaItemPlan").removeAttr('width').DataTable({
                dom: 'Bfrtip',
                buttons: [{ extend: 'excelHtml5' }],
                pageLength: 5,
                lengthMenu: [[30, 60, 100, -1], [30, 60, 100, "Todos"]],
                language: {
                    sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ning\u00fan dato disponible en esta tabla",
                    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sInfoPostFix: "",
                    sSearch: "Buscar:",
                    sUrl: "",
                    sInfoThousands: ",",
                    sLoadingRecords: "Cargando...",
                    oPaginate: {
                        sFirst: "Primero",
                        sLast: "\u00daltimo",
                        sNext: "Siguiente",
                        sPrevious: "Anterior"
                    },
                    oAria: {
                        sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                        sSortDescending: ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        }
    }
});

var toog2 = 0;
//var error=0;
Dropzone.autoDiscover = false;
var myDropzone2 = null;
var dropZ = this;
var nombreFile = null;

var flgRefrescaTabla = 0;

$("#dzDetalleItem").dropzone({
    url: "subirEvidenciaItemPlanDetalle",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 900,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",

    complete: function (file) {
        if (file.status == "success") {
            error = 0;
            nombreFile = file.name;
            this.removeAllFiles(true);
            modal('modalSubirEvidencia');
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
            this.removeAllFiles(true);
        });

        var submitButton = document.querySelector("#btnAceptarSubirEvidencia")
        myDropzone2 = this;

        submitButton.addEventListener("click", function () {
            myDropzone2.processQueue();
        });
        this.on("addedfile", function () {
            toog2 = toog2 + 1;
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {
            if (error == 0) {
                flgMosrarEvi = 1;
                $("#btnVerEviEnt" + idItemPlanEntDet).css("display", "block");
                $("#iconNoImgEvi" + idItemPlanEntDet).css("display", "none");
            }
        });
    }
});

var toog3 = 0;
var myDropzone3 = null;
var error3 = 1;
var flgGuardarComprobante = null;
var nombreFile2 = null;


$("#dzDetalleComprobante").dropzone({
    url: "subirFotoComprobanteDetalle",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 900,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",

    complete: function (file) {
        if (file.status == "success") {
            error3 = 0;
            nombreFile2 = file.name;
            this.removeAllFiles(true);
            modal('modalSubirFotoComprobante');
            flgRefrescaTabla = 1;
        }
    },
    removedfile: function (file, serverFileName) {
        var name = file.name;
        var element;
        (element = file.previewElement) != null ?
            element.parentNode.removeChild(file.previewElement) :
            false;
        toog3 = toog3 - 1;
    },
    init: function () {
        this.on("error", function (file, message) {
            alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
            return;
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error = 1;
            // alert(message);
            this.removeAllFiles(true);
        });

        var submitButton = document.querySelector("#btnAceptarSubirFotoComprobante")
        myDropzone3 = this;

        submitButton.addEventListener("click", function () {
            myDropzone3.processQueue();
        });
        this.on("addedfile", function () {
            toog3 = toog3 + 1;
            // Show submit button here and/or inform user to click it.
        });

        this.on("queuecomplete", function (file) {
            if (error3 == 0) {
                flgGuardarComprobante = 1;
                $('#btnSaveComprobante').attr("disabled", false);
                $("#btnVerEviCompro").css("display", "block");
                // $("#iconNoImgCompro").css("display", "none");
            }
        });
    }
});



var toog4 = 0;
var myDropzone4 = null;
var error4 = 1;
var flgGuardarAcota = null;
var nombreFile3 = null;


$("#dzDetalleAcota").dropzone({
    url: "subirFotoAcotacion",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 900,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",

    complete: function (file) {
        if (file.status == "success") {
            error4 = 0;
            nombreFile3 = file.name;
            this.removeAllFiles(true);
            modal('modalSubirEviAcota');
        }
    },
    removedfile: function (file, serverFileName) {
        var name = file.name;
        var element;
        (element = file.previewElement) != null ?
            element.parentNode.removeChild(file.previewElement) :
            false;
        toog4 = toog4 - 1;
    },
    init: function () {
        this.on("error", function (file, message) {
            alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
            return;
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error = 1;
            // alert(message);
            this.removeAllFiles(true);
        });

        var submitButton = document.querySelector("#btnAceptarSubirEviAcota")
        myDropzone4 = this;

        submitButton.addEventListener("click", function () {
            myDropzone4.processQueue();
        });
        this.on("addedfile", function () {
            toog4 = toog4 + 1;
            // Show submit button here and/or inform user to click it.
        });

        this.on("queuecomplete", function (file) {
            if (error4 == 0) {
                flgGuardarAcota = 1;
                $('#btnSaveAcota').attr("disabled", false);
                $("#btnVerEviAcota").css("display", "block");
                $("#iconNoImgAcota").css("display", "none");
            }
        });
    }
});


var toog5 = 0;
var myDropzone5 = null;
var error5 = 1;
//var flgGuardarAcota = null;
var nombreFile4 = null;


$("#dzEviProv").dropzone({
    url: "subirEvidenciaItemPlanDetalle",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 900,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",

    complete: function (file) {
        if (file.status == "success") {
            error5 = 0;
            nombreFile4 = file.name;
            this.removeAllFiles(true);
            modal('modalSubirEviProv');
        }
    },
    removedfile: function (file, serverFileName) {
        var name = file.name;
        var element;
        (element = file.previewElement) != null ?
            element.parentNode.removeChild(file.previewElement) :
            false;
        toog5 = toog5 - 1;
        //arrayfileEvidencia.splice(indexEvidencia, 1);
    },
    init: function () {
        this.on("error", function (file, message) {
            alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
            return;
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error = 1;
            // alert(message);
            this.removeAllFiles(true);
        });

        var submitButton = document.querySelector("#btnAceptarSubirEviProv")
        myDropzone5 = this;

        submitButton.addEventListener("click", function () {
            myDropzone5.processQueue();
        });
        this.on("addedfile", function () {
            toog5 = toog5 + 1;
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {
            if (error5 == 0) {
                document.getElementById('evidenciaEntProv').src = "uploads/licencias/evidencia_fotos/itemPlanEstaDet" + idItemPlanEntDet + "/" + nombreFile4;
                $("#btnVerEviEntProv" + idItemPlanEntDet).css("display", "block");
                // $("#iconNoImgEviProv" + idItemPlanEntDet).css("display", "none");
            }
        });
    }
});



var toog6 = 0;
var myDropzone6 = null;
var error6 = 1;
var flgGuardarComprobanteProv = null;
//var flgGuardarAcota = null;
var nombreFile5 = null;


$("#dzEviComproProv").dropzone({
    url: "subirFotoComprobanteDetalle",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 900,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",

    complete: function (file) {
        if (file.status == "success") {
            error6 = 0;
            nombreFile5 = file.name;
            this.removeAllFiles(true);
            modal('modalSubirEviComproProv');
        }
    },
    removedfile: function (file, serverFileName) {
        var name = file.name;
        var element;
        (element = file.previewElement) != null ?
            element.parentNode.removeChild(file.previewElement) :
            false;
        toog6 = toog6 - 1;
    },
    init: function () {
        this.on("error", function (file, message) {
            alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
            return;
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error = 1;
            // alert(message);
            this.removeAllFiles(true);
        });

        var submitButton = document.querySelector("#btnAceptarSubirEviComproProv")
        myDropzone6 = this;

        submitButton.addEventListener("click", function () {
            myDropzone6.processQueue();
        });
        this.on("addedfile", function () {
            toog6 = toog6 + 1;
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {
            if (error6 == 0) {
                flgGuardarComprobanteProv = 1;
                $('#btnSaveComprobanteProv').attr("disabled", false);
                // $("#btnVerEviComproProv").css("display", "block");
                // $("#iconNoImgComproProv").css("display", "none");
            }
        });
    }
});



function habilitaSubirFotoAcota() {
    var txtAcotacion = $('#txtDescAcota').val();
    if (txtAcotacion == null || txtAcotacion == '' || txtAcotacion == undefined) {
        $('#btnSubirFotoAcota').attr("disabled", true);
    } else {
        $('#btnSubirFotoAcota').attr("disabled", false);
    }
}



function habilitaSubirFoto(idTxtReembolso, idBtnSubirFoto) {

    var txtReembolso = $('#' + idTxtReembolso).val();
    if (txtReembolso == null || txtReembolso == '' || txtReembolso == undefined) {
        $('#' + idBtnSubirFoto).attr("disabled", true);
    } else {
        $('#' + idBtnSubirFoto).attr("disabled", false);
    }

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

}