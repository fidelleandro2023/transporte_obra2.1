new Vue({
    el: '#appVue',
    data: () => {
        return {
            tablaLicPreliqui: [],
            tablaItemPlanLicDet: [],
            idItemPlanDetGlob: null,
            indiceGlob: null,
        }
    },
    methods: {
        getTablaItemPlanPreliqui: function () {
            vue = this;
            $.ajax({
                type: 'POST',
                url: 'getItemPlanPreLiqui'
            }).done(function (data) {
                data = JSON.parse(data);
                vue.tablaLicPreliqui = data.tablaItemPlanPreliqui;
            });
        },
        getItemPlanEstaDet: function (itemPlan,idEstacion) {
            vue = this;
            tablaItemPlanLicDet = [];
            $.ajax({
                type: 'POST',
                'url': 'getEntLicPreliqui',
                data: {
                    itemPlan: itemPlan,
                    idEstacion: idEstacion
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaItemPlanLicDet = data.tablaItemPlanDetalle;
                     modal('modalPreliquiEntidades');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No hay detalles para ese itemPlan');
                }
            });

            
        },
        abrirModalEvidencia: function (idItemPlanEstaLic) {
            $.ajax({
                type: 'POST',
                'url': 'crtSesionEviLic',
                data: {
                    idItemPlanEstaLic: idItemPlanEstaLic
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 1) {
                    mostrarNotificacion('error', 'Hubo un error al crear la sesi&oacute;n');
                } else {
                    modal('modalSubirEviLicPreliqui');
                }
            });
        },
        descargarPDFEviPreliqui: function (idItemPlanDet, index) {
            $.ajax({
                type: 'POST',
                'url': 'getRutaEviLicPreliqui',
                data: {
                    idItemPlanEstaDetalle: idItemPlanDet
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaLicPreliqui[index]['ruta_pdf_finalizacion'] = data.rutaImagen;
                    window.open(data.rutaImagen);
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No hay pdf de finalizaci&oacute;n para mostrar');
                }
            });

        },/*
        liquidarLicencia: function (idItemPlanDet, index) {
            vue = this;
            $.ajax({
                type: 'POST',
                'url': 'updateItemPLanLicPreliqui',
                data: {
                    idItemPlanEstaDetalle: idItemPlanDet
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaItemPlanLicDet[index]['flg_validado'] = 3;
                    mostrarNotificacion('success', 'Se liquid&oacute; correctamente');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No se pudo liquidar el itemPlan');
                }
            });
        }*/
        openModalLiqui: function (idItemPlanDet, index) {
            vue = this;
            vue.idItemPlanDetGlob = idItemPlanDet;
            vue.indiceGlob = index;
            modal('modalAlertaValidacion');
        },
        liquidarLicencia: function () {
            vue = this;
            $.ajax({
                type: 'POST',
                'url': 'updateItemPLanLicPreliqui',
                data: {
                    idItemPlanEstaDetalle: vue.idItemPlanDetGlob
                },
                'async': false

            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    vue.tablaItemPlanLicDet[vue.indiceGlob]['flg_validado'] = 3;
                    modal('modalAlertaValidacion');
                    mostrarNotificacion('success', 'Se liquid&oacute; correctamente');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'No se pudo liquidar el itemPlan');
                }
            });
        }


    },
    mounted: function () {
        vue = this;
        vue.getTablaItemPlanPreliqui();
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



var toog1 = 0;
Dropzone.autoDiscover = false;
var myDropzone1 = null;
var dropZ = this;
var error1 = 1;

$("#dzEviLicPreliqui").dropzone({
    url: "subirEviLicPreliqui",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 800,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",

    complete: function (file) {
        if (file.status == "success") {
            error1 = 0;
            nombreFile = file.name;
            this.removeAllFiles(true);
            modal('modalSubirEviLicPreliqui');
        }
    },
    removedfile: function (file, serverFileName) {
        var name = file.name;
        var element;
        (element = file.previewElement) != null ?
            element.parentNode.removeChild(file.previewElement) :
            false;
        toog1 = toog1 - 1;
    },
    init: function () {
        this.on("error", function (file, message) {
            alert('El archivo ' + file.name + ' no tiene el formato correcto o el peso mayor a lo permitido, no ser&aacute; tomado en cuenta');
            return;
            error1 = 1;
            this.removeAllFiles(true);
        });

        var submitButton = document.querySelector("#btnAceptarSubirEvi")
        myDropzone1 = this;

        submitButton.addEventListener("click", function () {
            myDropzone1.processQueue();
        });
        this.on("addedfile", function () {
            toog1 = toog1 + 1;
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {
            if (error1 == 0) {
                //
            }
        });
    }
});