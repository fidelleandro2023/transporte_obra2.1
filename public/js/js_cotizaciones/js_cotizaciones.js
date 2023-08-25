var itemplanGlob = null;

if (!$.fn.dataTable.isDataTable('#simpletable')) {
    // $("#simpletable").removeAttr('width').DataTable({
    //     dom: 'Bfrtip',
    //     buttons: [{ extend: 'excelHtml5' }],
    //     pageLength: 5,
    //     lengthMenu: [[30, 60, 100, -1], [30, 60, 100, "Todos"]],
    //     language: {
    //         sProcessing: "Procesando...",
    //         sLengthMenu: "Mostrar _MENU_ registros",
    //         sZeroRecords: "No se encontraron resultados",
    //         sEmptyTable: "Ning\u00fan dato disponible en esta tabla",
    //         sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    //         sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
    //         sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
    //         sInfoPostFix: "",
    //         sSearch: "Buscar:",
    //         sUrl: "",
    //         sInfoThousands: ",",
    //         sLoadingRecords: "Cargando...",
    //         oPaginate: {
    //             sFirst: "Primero",
    //             sLast: "\u00daltimo",
    //             sNext: "Siguiente",
    //             sPrevious: "Anterior"
    //         },
    //         oAria: {
    //             sSortAscending: ": Activar para ordenar la columna de manera ascendente",
    //             sSortDescending: ": Activar para ordenar la columna de manera descendente"
    //         }
    //     }
    // });
    $('#simpletable').DataTable({
        autoWidth: false,
        responsive: false,
        aaSorting: [],
        lengthMenu: [[5, 10, 30, -1], [5, 10, 30, "Todos"]],
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ning\u00fan dato disponible en esta tabla",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sInfoPostFix: "",
            searchPlaceholder: "Buscar...",
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
        },
        dom: "Blfrtip",
        buttons: [{ extend: "excelHtml5", title: "Export Data" },
        { extend: "csvHtml5", title: "Export Data" }]
    });
}

function paginarTabla(idTabla) {
    $('#' + idTabla).DataTable({
        autoWidth: false,
        responsive: false,
        aaSorting: [],
        lengthMenu: [[5, 10, 30, -1], [5, 10, 30, "Todos"]],
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ning\u00fan dato disponible en esta tabla",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sInfoPostFix: "",
            searchPlaceholder: "Buscar...",
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
        },
        dom: "Blfrtip",
        buttons: [{ extend: "excelHtml5", title: "Export Data" },
        { extend: "csvHtml5", title: "Export Data" }]
    });
}


function abrirModalRegiCoti() {
    $(".dz-default").text("Coloque su evidencia aqu\u00ED");
    $("#itemplan").val(null);
    $('#contTablaDetItemPlan').css("display", "none");
    $('#contDropzone').css("display", "none");
    $('#contDescrip').css("display", "none");
    $('#contCosto').css("display", "none");
    $('#contResponsable').css("display","none");
    $('#contGuardar').css("display", "none");

    $.ajax({
        type: 'POST',
        url: 'getResponsables'

    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            $('#idResponsable').html(data.comboResponsables);
        } else {
            mostrarNotificacion('error', 'Error', 'Hubo un error al traer los responsables');
        }
    });

    modal('modalRegisCotizacion');
    console.log('entro al metodo');
}

function validaBusqueda() {
    var itemplan = $("#itemplan").val();

    if (itemplan.length == 13) {
        $("#idBtnSearch").attr("disabled", false);
    } else {
        $("#idBtnSearch").attr("disabled", true);
    }
}

function searchItemPlan() {
    var itemplan = $("#itemplan").val();
    if (itemplan != null && itemplan != '' && itemplan != undefined) {
        $.ajax({
            type: 'POST',
            url: 'searchItemPlan',
            data: {
                itemplan: itemplan
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                itemplanGlob = data.itemplan;
                $('#contTablaDetItemPlan').css("display", "block");
                $('#contTablaDetItemPlan').html(data.tablaDetItemPlan);
                $('#contDropzone').css("display", "block");
                $('#contDescrip').css("display", "block");
                $('#contCosto').css("display", "block");               
                $('#contResponsable').css("display","block");
                $('#contGuardar').css("display", "block");
                console.log(itemplanGlob);
            } else {
                mostrarNotificacion('error', 'Error', 'No se encontr&oacute; el itemplan');
            }
        });
    }
}



var toog1 = 0;
var error1 = 0;
Dropzone.autoDiscover = false;
var myDropzone1 = null;
var dropZ = this;
var nombreFile1 = null;


$("#dzEviCotizacion").dropzone({
    url: "uploadEviCotizacion",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 800,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",

    success: function (file, response) {
        data = JSON.parse(response);
        if (data.error == 0) {

            nombreFile1 = file.name;
            this.removeAllFiles(true);
            $("#contTablaCotizaciones").html(data.tablaHTML);
            paginarTabla('simpletable');
            mostrarNotificacion('success', 'Success', "Se registr&oacute; correctamente!!");
            modal('modalRegisCotizacion');
        } else {
            mostrarNotificacion('error', 'Error', data.msj);
        }
    },

    complete: function (file) {
        if (file.status == "success") {
            // error1 = 0;
            console.log('entro al complete');
            // nombreFile1 = file.name;
            // this.removeAllFiles(true);

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
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
            error1 = 1;
            this.removeAllFiles(true);
        });

        var submitButton = document.querySelector("#btnAceptarSubirEviCotizacion")
        myDropzone1 = this;

        submitButton.addEventListener("click", function () {
            //saveCotizacion();
            myDropzone1.processQueue();
        });
        this.on("addedfile", function () {
            toog1 = toog1 + 1;
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {

        });
        this.on('sending', function (file, xhr, formData) {
            var descripcion = $("#idDescripCoti").val();
            var costo = $("#idCostoCoti").val();
            var idResponsable = $("idResponsable").val();
            formData.append('itemplan', itemplanGlob);
            formData.append('descripcion', (descripcion).trim());
            formData.append('costo', costo);
            formData.append('idResponsable', idResponsable);
        });
    }
});



function saveCotizacion() {
    var descripcion = $("#idDescripCoti").val();
    var costo = $("#idCostoCoti").val();

    // jsonValida = { descripcion: descripcion, costo: costo, itemplan: itemplanGlob };
    // if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
    //     mostrarNotificacion('error', 'Error','Debe llenar todos los campos para guardar');
    //     return;
    // }

    $.ajax({
        type: 'POST',
        url: 'regCotizacion',
        data: {
            itemplan: itemplanGlob,
            descripcion: descripcion,
            costo: costo
        }
    }).done(function (data) {
        data = JSON.parse(data);
        console.log(data);
        if (data.error == 0) {
            //$('#formEntidades').html(data.htmlEntidades);
            myDropzone1.processQueue();
            //modal('modalRegisCotizacion');
            //mostrarNotificacion('success', 'Success', 'Se guard&oacute; correctamente la cotizaci&oacute;n!!');
        } else {
            //mostrarNotificacion('error', 'Error', 'No se pudo guardar la cotizaci&oacute;n');
        }
    });
}


function verEviLiqui(component) {
    var rutaPDF = $(component).data("rutapdf");
    window.open(rutaPDF);
}
