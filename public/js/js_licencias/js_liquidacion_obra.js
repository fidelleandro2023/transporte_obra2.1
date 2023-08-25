initTable('simpletable', 10);
$(".input-3").keyup(function () {
    if (this.value > 3) {
        $(this).css("color", "red");
    } else {
        $(this).css("color", "black");
    }
});
$(".input-7").keyup(function () {
    if (this.value < 3 || this.value > 7) {
        $(this).css("color", "red");
    } else {
        $(this).css("color", "black");
    }
});
$(".input-39").keyup(function () {
    if (this.value < 36 || this.value > 39) {
        $(this).css("color", "red");
    } else {
        $(this).css("color", "black");
    }
});
$(".input-42").keyup(function () {
    if (this.value < 40 || this.value > 42) {
        $(this).css("color", "red");
    } else {
        $(this).css("color", "black");
    }
});
$(".input-45").keyup(function () {
    if (this.value < 44 || this.value > 45) {
        $(this).css("color", "red");
    } else {
        $(this).css("color", "black");
    }
});
$(".input-32").keyup(function () {
    if (this.value > 32) {
        $(this).css("color", "red");
    } else {
        $(this).css("color", "black");
    }
});

function mostrarNotificacion(tipo, titulo, mensaje) {
    /*new PNotify({
        title: titulo,
        text: mensaje,
        type: tipo,
        delay: 2000,
        styling: 'bootstrap3'
        // buttons: {
         //    sticker: false
       //  }
    });
    */
    swal({
    	  title: titulo,
    	  text: mensaje,
    	  type: tipo
        	  });
}




function getSubProyecto() {

    var idProyecto = $('#proyecto').val();

    $.ajax({
        type: 'POST',
        url: 'getSubProyByProy',
        data: {
            idProyecto: idProyecto
        }
    }).done(function (data) {
        data = JSON.parse(data);
        $('#subProyecto').html(data.cmbSubproyecto);
    });
}

function filtrarTabla() {

    var idProyecto = $.trim($('#proyecto').val());
    var idSubProyecto = $.trim($('#subProyecto').val());
    var jefatura = $.trim($('#jefatura').val());
    var mesPrevEjec = $.trim($('#mesPrevEjec').val());
    var idFase = $.trim($('#fase').val());

    $.ajax({
        type: 'POST',
        'url': 'getIPEstLic',
        data: {
            idProyecto: idProyecto,
            idSubProyecto: idSubProyecto,
            jefatura: jefatura,
            mesPrevEjec: mesPrevEjec,
            idFase: idFase
        },
        'async': false
    }).done(function (data) {
        var data = JSON.parse(data);
        if (data.error == 0) {
            $('#contTabla').html(data.tablaIPLic)
            // initDataTable('#data-table');

        } else {
            mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
        }
    });

}

var itemplanGlob = null;
var idEstacionGlob = null;

function mostrarDetalle(component) {

    itemplanGlob = $(component).data('itemplan');
    idEstacionGlob = $(component).data('idestacion');

    $('#tituloModalEnt').text('LISTA DE ENTIDADES, IP: ' + itemplanGlob);

    $.ajax({
        type: 'POST',
        'url': 'getEntLicPreliqui2',
        data: {
            itemPlan: itemplanGlob,
            idEstacion: idEstacionGlob
        }
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {

            $('#contTablaEnt').html(data.tablaEntidades);
            modal('modalRegistrarEntidades');

        } else if (data.error == 1) {
            mostrarNotificacion('error', 'No hay detalles para ese itemPlan');
        }
    });

}

function abrirModalRegisEnt() {

    $.ajax({
        type: 'POST',
        url: 'getCmbEntLic',
        data: {
            itemplan: itemplanGlob,
            idEstacion: idEstacionGlob
        }
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {

            $('#idCmbEnt').html(data.htmlEntidades);
            modal('modalRegistrarEnt');
        } else {
            mostrarNotificacion('error', 'Hubo un error interno, íntentelo de nuevo.');
        }
    });
}

function registrarEntidades() {

    var idEntidad = $('#idCmbEnt').val();

    if (idEntidad != null && idEntidad != undefined && idEntidad != 0) {
        $.ajax({
            type: 'POST',
            url: 'regEntLic',
            data: {
                itemplan: itemplanGlob,
                idEstacion: idEstacionGlob,
                idEntidad: idEntidad
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {

                $('#contTablaEnt').html(data.tablaEntidades);

                mostrarNotificacion('success', data.msj);
                modal('modalRegistrarEnt');

                modal('modalRegistrarEntidades');
            } else {
                mostrarNotificacion('success', data.msj);
            }
        });
    }

}

function deleteIPEstDetLic(component) {
    idIpEntLicGlob = $(component).data('idipestlic');
    if (idIpEntLicGlob != null && idIpEntLicGlob != undefined) {
        $.ajax({
            type: 'POST',
            'url': 'deleteIPEstDetLic',
            data: {
                idItemPlanDet: idIpEntLicGlob
            },
            'async': false

        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {//elimino correctamente el registro
                modal('modalRegistrarEntidades');
                mostrarNotificacion('success', data.msj);
            } else {
                mostrarNotificacion('error', data.msj);
            }
        });
    }
}

var idIpEntLicGlob = null;
var flgTipoTransac = null;
var idEntidadGlob = null;
var idDitritoGlob = null;
var flgTipoLicGlob = null;


function abrirModalEvidencia(component) {
    idIpEntLicGlob = $(component).data('idipestlic');
    modal('modalSubirEvidencia');
}

function openModalLiqui(component) {
    idIpEntLicGlob = $(component).data('idipestlic');
    flgTipoTransac = $(component).data('flgtransac');
    idEntidadGlob = $(component).data('identidad');
    idDitritoGlob = $(component).data('distrito');
    flgTipoLicGlob = $(component).data('flgtipo');

    modal('modalAlertaValidacion');
}








var toog1 = 0;
var error1 = 0;
Dropzone.autoDiscover = false;
var myDropzone1 = null;
var dropZ = this;
var nombreFile = null;

var flgRefrescaTabla = 0;

$("#dzDetalleItem").dropzone({
    url: "updateIPLicPreliqui",
    addRemoveLinks: true,
    autoProcessQueue: false,
    acceptedFiles: "application/pdf",
    parallelUploads: 1,
    uploadMultiple: false,
    maxFilesize: 900,
    maxFiles: 1,
    dictResponseError: "Ha ocurrido un error en el server",
    success: function (file, response) {
        data = JSON.parse(response);
        if (data.error == 0) {
            modal('modalAlertaValidacion');
            modal('modalRegistrarEntidades');
            mostrarNotificacion('success', data.msj);
        } else {
            mostrarNotificacion('error', data.msj);
        }
    },
    complete: function (file) {
        if (file.status == "success") {
            error1 = 0;
            nombreFile = file.name;
            this.removeAllFiles(true);
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
            // alert(message);
            this.removeAllFiles(true);
        });

        // var submitButton = document.querySelector("#btnSaveIpEstDet")
        myDropzone1 = this;

        this.on("addedfile", function () {
            toog1 = toog1 + 1;
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {
            if (error1 == 0) {

            }
        });
        this.on('sending', function (file, xhr, formData) {
            var codExpedienteFina = $.trim($("#txtCodExpFina" + idIpEntLicGlob).val());
            var fechaFinal = $("#txtFechaFinal" + idIpEntLicGlob).val();
            formData.append('iditemplanEstaDet', idIpEntLicGlob);
            formData.append('codExpedienteFina', codExpedienteFina);
            formData.append('fechaFinal', fechaFinal);
            formData.append('flgTipoTransac', flgTipoTransac);
            formData.append('itemplan', itemplanGlob);
            formData.append('idEstacion', idEstacionGlob);
            formData.append('idEntidad', idEntidadGlob);
            formData.append('idDistrito', idDitritoGlob);
            formData.append('flgTipoLic', flgTipoLicGlob);
            
        });
    }
});


function liquidarLicencia() {

    if (idIpEntLicGlob == null || idIpEntLicGlob == undefined || idIpEntLicGlob == '') {
        mostrarNotificacion('error', 'Hubo un error al recibir los datos!!')
        return;
    } else {
        var codExpedienteFina = $.trim($("#txtCodExpFina" + idIpEntLicGlob).val());
        var fechaFinal = $("#txtFechaFinal" + idIpEntLicGlob).val();
        if (codExpedienteFina == null || codExpedienteFina == undefined || codExpedienteFina == '') {
            mostrarNotificacion('error', 'Debe ingresar un expediente de carta de finalizacion!!')
            return;
        }
        if (fechaFinal == null || fechaFinal == undefined || fechaFinal == '') {
            mostrarNotificacion('error', 'Debe ingresar una fecha final para guardar!!')
            return;
        }

        myDropzone1.processQueue();
    }
}

function descargarPDFEntidad(component) {
    var idIpEntLic = $(component).data('idipestlic');

    $.ajax({
        type: 'POST',
        'url': 'getRutaEviIPEstaDetPreliqui',
        data: {
            idItemPlanEstaDetalle: idIpEntLic
        },
        'async': false

    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            window.open(data.rutaImagen);
        } else if (data.error == 1) {
            mostrarNotificacion('error', 'No hay PDF para mostrar');
        }
    });


}
