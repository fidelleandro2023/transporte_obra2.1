
var toog2 = 0;
//var error=0;
Dropzone.autoDiscover = false;
var errorAjax = null
var msj = null;
var dropGlobal = null;

$("#dropzonePO").dropzone({
    url: "cargarArchivoPOPIN",
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 200,
    maxFilesize: 800,
    acceptedFiles: ".xls",
    dictResponseError: "Ha ocurrido un error en el server",
    success: function (file, response) {
        console.log('ptm:', response);
        data = JSON.parse(response);
        console.log('no llega aca');
        errorAjax = data.error;
        msj = data.msj;
        dropGlobal = this;
        if (errorAjax == 0) {
            console.log('entro xq no hay error')
            $('#contTablaError').html(data.tablaError);
            arrayMatGlob = data.arrayMat;
            countErrorGlob = data.countError;
            costoTotalMat = data.costoTotalGlob;
            // if (countErrorGlob == 0) {
            //     $('#btnRegisPO').prop('disabled', false);
            // } else {
            //     $('#btnRegisPO').prop('disabled', true);
            // }

        } else {
            // $('#contTablaBloc').html(data.tablaBloc);
            // initDataTable('#data-table');

            // $('#totalMat').text('Total de Materiales: ' + data.cantTotalMat);
            // $('#costoTotal').text('Costo Total: ' + data.cantTotalCosto);
            // if (data.flgSave == 0) {
            //     $('#btnGuardarPO').css('display', 'block');
            //     arrayMatGlob = data.arrayMateriales;
            //     cantidadTotalMat = data.cantTotalMat;
            // }
            // $('#btnCancelPO').css('display', 'block');
            // modal('modalFormatoBloc');
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

// var itemplanGlob = null;
var idProyectoGlob = null;
var idSubProyectEstaGlob = null;
var idSubProyectoGlob = null;
var idEmpresaColabGlob = null;
var idAreaGlob = null;
var arrayMatGlob = [];
var costoTotalMat = null;
var countErrorGlob = null;

// function openModalRegisPO(component) {

//     itemplanGlob = $(component).data('itemplan');
//     idSubProyectEstaGlob = $(component).data('idsubproest');
//     idAreaGlob = $(component).data('idarea');
//     var areaDesc = $(component).data('areadesc');

//     $.ajax({
//         type: 'POST',
//         url: 'getDetItemplan',
//         data: {
//             itemplan: itemplanGlob
//         }
//     }).done(function (data) {
//         data = JSON.parse(data);
//         if (data.error == 0) {
//             modal('modalRegistroPO');
//             $('#tituloModalRegisPO').text('Registro Individual PO');
//             idSubProyectoGlob = data.idSubProyecto;
//             idProyectoGlob = data.idProyecto;
//             idEmpresaColabGlob = data.idEmpresaColab;
//             $('#contProyecto').val(data.proyectoDesc);
//             $('#contSubProyecto').val(data.subProyectoDesc);
//             $('#contJefatura').val(data.jefatura);
//             $('#contEmpresacolab').val(data.empresaColabDesc);
//             $('#contCentral').val(data.centralDesc);
//             $('#contAreaDesc').val(areaDesc);
//         } else {
//             mostrarNotificacion('warning', 'Error', 'Hubo un error al seleccionar el area!!');
//         }
//     });

// }

function guardarPO() {
    if (arrayMatGlob.length > 0 && fromGlob != null && costoTotalMat != null) {
        var prueba = 0;
        if (fromGlob == 1) {
            prueba = 1
        } else if (fromGlob == 2) {
            prueba = 2;
        }
        $.ajax({
            type: 'POST',
            url: 'registPOPIN',
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
                    window.location.href = "detallePI?item=" + data.itemplan + "&from=" + fromGlob;
                });
            } else {
                mostrarNotificacion('warning', 'Error', data.msj);
            }
        });
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

function generarExcelPO() {
    $('#formGenerarMATPO').submit();
}

$('#formGenerarMATPO')
    .bootstrapValidator({
        container: '#mensajeForm',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        excluded: ':disabled',
        fields: {


        }
    }).on('success.form.bv', function (e) {
        e.preventDefault();

        var $form = $(e.target),
            formData = new FormData(),
            params = $form.serializeArray(),
            bv = $form.data('bootstrapValidator');

        $.each(params, function (i, val) {
            formData.append(val.name, val.value);
        });

        $.ajax({
            data: formData,
            url: "getExcelPOMat",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
        }).done(function (data) {
            console.log('llego al done');
            var data = JSON.parse(data);
            console.log('paso el parseo');
            if (data.error == 0) {
                location.href = data.rutaExcel;
            }else{
                mostrarNotificacion('warning', 'Aviso', data.msj);
            }
        })

    });

