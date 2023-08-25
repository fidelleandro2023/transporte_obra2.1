
var idEmpresaColabGlobal = null;
var idJefaturaGlobal = null;
var itemplanGlobal = null;
var vrGlobal = null;

function getComboPtr() {
    itemplanGlobal = $('#cmbItemplan option:selected').val();

    $.ajax({
        type: 'POST',
        url: 'getComboPtr',
        data: { itemplan: itemplanGlobal }
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            $('#contCmbPtr').html(data.cmbPtr);
            $('#contEmpresaColab').val(data.empresacolab);
            $('#contJefatura').val(data.jefatura);
            $('#contAlmacen').val(data.codAlmacen);
            $('#contCentro').val(data.codCentro);

            idEmpresaColabGlobal = data.idEmpresaColab;
            idJefaturaGlobal = data.idJefatura;
        } else {
            mostrarNotificacion('error', data.msj);
        }
    })
}

var ptr_estadoGlobal = null;
function getVr() {
    ptr_estadoGlobal = $('#contCmbPtr option:selected').val();

    $.ajax({
        type: 'POST',
        url: 'getVR',
        data: { ptr: ptr_estadoGlobal }
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            $('#contVr').val(data.vr);
            vrGlobal = data.vr;
        } else {
            mostrarNotificacion('error', data.msj);
        }
    })
}

function openModalBloc() {
    modal('modalFormatoBloc');
}



var codigoVrGlobal = null;
var toog2 = 0;
//var error=0;
Dropzone.autoDiscover = false;
var errorAjax = null
var msj = null;

$("#dropzone1").dropzone({
    url: "insertArchivoMasivo",
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 200,
    maxFilesize: 800,
    acceptedFiles: ".txt",
    dictResponseError: "Ha ocurrido un error en el server",
    success: function (file, response) {

        data = JSON.parse(response);
        $('#contTablaBloc').html(data.tablaBloc);
        initDataTable('#data-table');
        modal('modalFormatoBloc');

        errorAjax = data.error;
        msj = data.msj;

        if (errorAjax == 0) {
            modal('modalAlertaAceptacion');
            mostrarNotificacion('success', 'Operacion existosa', 'Se actualizo correctamente!!');
        }else{
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
            if (errorAjax == 0) {

                drop.removeAllFiles(true);
               
            } else {
                mostrarNotificacion('error', 'Error', msj);
            }
        });

        this.on('sending', function (file, xhr, formData) {
            //por aca mando la data al controllador, en un form
        });
    }
});


function openModalAceptar() {
    modal('modalAlertaAceptacion');
}