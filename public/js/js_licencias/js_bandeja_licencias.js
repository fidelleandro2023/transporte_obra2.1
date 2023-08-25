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

var flgProvinciaGlob = null;
var itemplanGlob = null;
var idEstacionGlob = null;

function mostrarDetalle(component) {

    itemplanGlob = $(component).data('itemplan');
    flgProvinciaGlob = $(component).data('flgprovincia');
    idEstacionGlob = $(component).data('idestacion');
    
    $('#tituloModalEnt').text('LISTA DE ENTIDADES  IP: ' + itemplanGlob);

    $.ajax({
        type: 'POST',
        'url': 'getEntLic',
        data: {
            itemPlan: itemplanGlob,
            idEstacion: idEstacionGlob,
            flgProvincia: flgProvinciaGlob
        }
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {

            // if (flgProvinciaGlob == 1) {
                $('#contTablaEnt').html(data.tablaEntidades);
                modal('modalRegistrarEntidades');
            // } else {
            //     $('#tabla_ent_prov').html(data.tablaEntidades);
            //     modal('modalEntProv');
            // }

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
    var itemplan = $(component).data('itemplan');
    var idEstacion = $(component).data('idestacion');
    
    if (idIpEntLicGlob != null && idIpEntLicGlob != undefined) {

        swal({
            title: 'Esta seguro de eliminar la entidad??',
            text: 'Asegurese de validar la informacion!!',
            type: 'warning',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'OK!'

        }).then(function () {
            $.ajax({
                type: 'POST',
                'url': 'deleteIPEstDetLic',
                data: {
                    idItemPlanDet: idIpEntLicGlob,
                    itemplan: itemplanGlob,
                    idEstacion : idEstacionGlob
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
        });
    }
}

var idIpEntLicGlob = null;

function abrirModalEvidencia(component, flgTipo, flgValida) {
    if (flgTipo == 1) {
        idIpEntLicGlob = $(component).data('idipestlic');
        modal('modalSubirEvidencia');
    } else if (flgTipo == 2) {
        modal('modalSubirFotoComprobante');
    } else {

    }

}

function cerrarModalEviCompro() {
    modal('modalSubirFotoComprobante');
}


var toog1 = 0;
var error1 = 0;
Dropzone.autoDiscover = false;
var myDropzone1 = null;
var dropZ = this;
var nombreFile = null;

var flgRefrescaTabla = 0;

$("#dzDetalleItem").dropzone({
    url: "subirEviIPEstDet",
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
            // modal('modalSubirEvidencia');
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {
            if (error1 == 0) {

            }
        });
        this.on('sending', function (file, xhr, formData) {
            var codExpediente = $.trim($("#txtCodExp" + idIpEntLicGlob).val());
            var flgTipoLic = $("#tipoLic" + idIpEntLicGlob).val();
            var distrito = $("#distEnt" + idIpEntLicGlob).val();// puede ser nulo
            var fecIniLic = $("#txtFechaIni" + idIpEntLicGlob).val();
            var fecFinLic = $("#txtFechaFin" + idIpEntLicGlob).val();
            var hasCompro = $("#idSelectHasCompro" + idIpEntLicGlob).val();
            
            formData.append('iditemplanEstaDet', idIpEntLicGlob);
            formData.append('codExpediente', codExpediente);
            formData.append('flgTipoLic', flgTipoLic);
            formData.append('distrito', distrito);
            formData.append('fechaInicio', fecIniLic);
            formData.append('fechaFin', fecFinLic);
            formData.append('reqComprobante', hasCompro);
			console.log('mandando');
        });
    }
});


function liquidarDetalle(component, index) {
    idIpEntLicGlob = $(component).data('idipestlic');
    if (idIpEntLicGlob == null || idIpEntLicGlob == undefined || idIpEntLicGlob == '') {
        mostrarNotificacion('error', 'Hubo un error al recibir los datos!!')
        return;
    } else {
        var codExpediente = $.trim($("#txtCodExp" + idIpEntLicGlob).val());
        var flgTipoLic = $("#tipoLic" + idIpEntLicGlob).val();
        var distrito = $("#distEnt" + idIpEntLicGlob).val();// puede ser nulo
        var fecIniLic = $("#txtFechaIni" + idIpEntLicGlob).val();
        var fecFinLic = $("#txtFechaFin" + idIpEntLicGlob).val();

        if(flgTipoLic == 0){
            flgTipoLic = null;
        }

        jsonValida = { codExpediente: codExpediente, flgTipoLic: flgTipoLic, fecIniLic: fecIniLic, fecFinLic: fecFinLic };
        if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
            mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
            return;
        } else {
            swal({
                title: 'Desea guardar los cambios??',
                text: 'Asegurese de validar la informacion!!',
                type: 'warning',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'OK!'

            }).then(function () {
                myDropzone1.processQueue();
            });
        }


    }
}

function descargarPDFEntidad(component, flgRuta, flgProv) {
    var idIpEntLic = $(component).data('idipestlic');
    if (flgRuta == 2) {
        $.ajax({
            type: 'POST',
            'url': 'getRutaEviIPEstaDet',
            data: {
                idItemPlanEstaDetalle: idIpEntLic
            },
            'async': false

        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                // vue.tablaDetalle[index]['ruta_pdf'] = data.rutaImagen;
                window.open(data.rutaImagen);
            } else if (data.error == 1) {
                mostrarNotificacion('error', 'No hay PDF para mostrar');
            }
        });

    }

}

function abrirModalComprobantes(component) {
    idIpEntLicGlob = $(component).data('idipestlic');
    if (idIpEntLicGlob != null) {
        $.ajax({
            type: 'POST',
            'url': 'getComprobantesLic',
            data: {
                idItemPlanEstaDetalle: idIpEntLicGlob
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                $('#contTablaCompro').html(data.tablaComprobantes);
                modal('modalComprobantes');
            } else {
                mostrarNotificacion('error', data.msj);
            }
        });
    }
    myDropzone2.removeAllFiles(true);
}

function preliqAdmin(component, idReembolso) {

    var options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    var txtReembolso = 'txtDescReembolso';
    var txtFechaEmiCompro = 'txtFechaEmiCompro';
    var txtMontoCompro = 'txtMontoCompro';
    var chxValidaCompro = 'chkValidaCompro';

    if (idReembolso != null) {
        txtReembolso = txtReembolso + idReembolso;
        txtFechaEmiCompro = txtFechaEmiCompro + idReembolso;
        txtMontoCompro = txtMontoCompro + idReembolso;
        chxValidaCompro = chxValidaCompro + idReembolso;
    }

    if ($(component).is(':checked')) {
        $('#' + txtReembolso).val('XXX');
        $('#' + txtFechaEmiCompro).val(((new Date()).toLocaleDateString('ja-JP', options)).split('/').join('-'));
        $('#' + txtMontoCompro).val(0);
        $('#' + txtReembolso).attr('disabled', true);
        $('#' + txtFechaEmiCompro).attr('disabled', true);
        $('#' + txtMontoCompro).attr('disabled', true);
        $('#' + chxValidaCompro).css('display', 'none');
    } else {
        $('#' + txtReembolso).val(null);
        $('#' + txtFechaEmiCompro).val(null);
        $('#' + txtMontoCompro).val(null);
        $('#' + txtReembolso).attr('disabled', false);
        $('#' + txtFechaEmiCompro).attr('disabled', false);
        $('#' + txtMontoCompro).attr('disabled', false);
        $('#' + chxValidaCompro).css('display', 'block');
    }

}

function validaCompro(component, idReembolso) {

    var chkxPreLiquiAd = 'chkxPreLiquiAd';

    if (idReembolso != null) {
        chkxPreLiquiAd = chkxPreLiquiAd + idReembolso;
    }

    if ($(component).is(':checked')) {
        $('#' + chkxPreLiquiAd).css('display', 'none');
    } else {
        $('#' + chkxPreLiquiAd).css('display', 'block');
    }
}


var toog2 = 0;
var error2 = 0;
var myDropzone2 = null;
var nombreFile2 = null;

var flgRefrescaTabla = 0;

$("#dzDetalleComprobante").dropzone({
    url: "saveUpdateCompro",
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
            modal('modalComprobantes');
            modal('modalRegistrarEntidades');
            mostrarNotificacion('success', data.msj);
        } else {
            mostrarNotificacion('error', data.msj);
        }
    },
    complete: function (file) {
        if (file.status == "success") {
            error2 = 0;
            nombreFile2 = file.name;
            this.removeAllFiles(true);
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
            error2 = 1;
            // alert(message);
            this.removeAllFiles(true);
        });

        // var submitButton = document.querySelector("#btnSaveIpEstDet")
        myDropzone2 = this;

        this.on("addedfile", function () {
            toog2 = toog2 + 1;
            // modal('modalSubirEvidencia');
            // Show submit button here and/or inform user to click it.
        });
        this.on("queuecomplete", function (file) {
            if (error2 == 0) {

            }
        });
        this.on('sending', function (file, xhr, formData) {


            var txtReembolso = 'txtDescCompro';
            var txtFechaEmiCompro = 'txtFechaEmiCompro';
            var txtMontoCompro = 'txtMontoCompro';
            var chxValidaCompro = 'chkValidaCompro';
            var chkxPreLiquiAd = 'chkxPreLiquiAd';

            if (idReembolsoGlob != null) {
                txtReembolso = txtReembolso + idReembolsoGlob;
                txtFechaEmiCompro = txtFechaEmiCompro + idReembolsoGlob;
                txtMontoCompro = txtMontoCompro + idReembolsoGlob;
                chxValidaCompro = chxValidaCompro + idReembolsoGlob;
                chkxPreLiquiAd = chkxPreLiquiAd + idReembolsoGlob;
            }

            var desc_reembolso = $.trim($('#' + txtReembolso).val());
            var fecha_emision = $('#' + txtFechaEmiCompro).val();
            var monto = $('#' + txtMontoCompro).val();
            var flgValidaCompro = null;
            var flgPreliqui = null;

            var flgPreliquiAdmin = document.getElementById(chkxPreLiquiAd).checked;
            var flgValida = document.getElementById(chxValidaCompro).checked;

            if (flgPreliquiAdmin == true) {
                flgPreliqui = '1';
            } else {
                flgPreliqui = '0';
            }
            if (flgValida == true) {
                flgValidaCompro = '1';
            } else {
                flgValidaCompro = '0';
            }
            console.log('flgValidaCompro:',flgValidaCompro);

            formData.append('iditemplanEstaDet', idIpEntLicGlob);
            formData.append('idReembolso', idReembolsoGlob);
            formData.append('desc_reembolso', desc_reembolso);
            formData.append('fecha_emision', fecha_emision);
            formData.append('monto', monto);
            formData.append('flgPreliqui', flgPreliqui);
            formData.append('flgValidaCompro', flgValidaCompro);
            formData.append('flgTipoTransacGlob', flgTipoTransacGlob);
        });
    }
});

var idReembolsoGlob = null;
var flgTipoTransacGlob = null;
var rutaComproGlob = null;

function saveComprobante(component, flgTipoTransac) {
    var idReembolso = null;
    flgTipoTransacGlob = flgTipoTransac;

    if (flgTipoTransac == 2) {
        idReembolso = $(component).data('idreembolso');
        rutaComproGlob = $(component).data('rutapdf');
        idReembolsoGlob = idReembolso;
    }


    if (idIpEntLicGlob == null || idIpEntLicGlob == undefined || idIpEntLicGlob == '') {
        mostrarNotificacion('error', 'Hubo un error al recibir los datos!!')
        return;
    } else {

        var txtReembolso = 'txtDescCompro';
        var txtFechaEmiCompro = 'txtFechaEmiCompro';
        var txtMontoCompro = 'txtMontoCompro';
        var chxValidaCompro = 'chkValidaCompro';
        var chkxPreLiquiAd = 'chkxPreLiquiAd';

        if (idReembolso != null) {
            txtReembolso = txtReembolso + idReembolso;
            txtFechaEmiCompro = txtFechaEmiCompro + idReembolso;
            txtMontoCompro = txtMontoCompro + idReembolso;
            chxValidaCompro = chxValidaCompro + idReembolso;
            chkxPreLiquiAd = chkxPreLiquiAd + idReembolso;
        }

        var desc_reembolso = $('#' + txtReembolso).val();
        var fecha_emision = $('#' + txtFechaEmiCompro).val();
        var monto = $('#' + txtMontoCompro).val();

        console.log('desc_reembolso:',desc_reembolso);
        console.log('fecha_emision:',fecha_emision);
        console.log('monto:',monto);

        jsonValida = { desc_reembolso: desc_reembolso, fecha_emision: fecha_emision, monto: monto };
        if (Object.keys(jsonValida).some(key => ((jsonValida[key] == null || jsonValida[key] == '' || jsonValida[key] == undefined)))) {
            mostrarNotificacion('error', 'Debe llenar todos los campos para guardar');
            return;
        } else {
            if(rutaComproGlob != null && rutaComproGlob != undefined && rutaComproGlob != 0 && rutaComproGlob != ''){
                var flgValidaCompro = null;
                var flgPreliqui = null;
    
                var flgPreliquiAdmin = document.getElementById(chkxPreLiquiAd).checked;
                var flgValida = document.getElementById(chxValidaCompro).checked;
    
                if (flgPreliquiAdmin == true) {
                    flgPreliqui = '1';
                } else {
                    flgPreliqui = '0';
                }
                if (flgValida == true) {
                    flgValidaCompro = '1';
                } else {
                    flgValidaCompro = '0';
                }
                $.ajax({
                    type: 'POST',
                    'url': 'updateComproV2',
                    data: {
                        iditemplanEstaDet: idIpEntLicGlob,
                        idReembolso: idReembolsoGlob,
                        desc_reembolso: desc_reembolso,
                        fecha_emision: fecha_emision,
                        monto: monto,
                        flgPreliqui: flgPreliqui,
                        flgValidaCompro: flgValidaCompro,
                        flgTipoTransacGlob: flgTipoTransacGlob
                    }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        modal('modalComprobantes');
                        modal('modalRegistrarEntidades');
                        mostrarNotificacion('success', data.msj);
                    } else {
                        mostrarNotificacion('error', data.msj);
                    }
                });
                
            }else{
                myDropzone2.processQueue();
            }
            rutaComproGlob = null
            
        }


    }

}


function descargarPDFCompro(component, flgRuta, flgProv) {
    idReembolsoGlob = $(component).data('idreembolso');
    if (flgRuta == 2) {
        $.ajax({
            type: 'POST',
            'url': 'getRutaEviReembolso',
            data: {
                idReembolso: idReembolsoGlob
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                window.open(data.rutaImagen);
            } else {
                mostrarNotificacion('error', 'No hay comprobante para mostrar');
            }
        });
    }

}

function desactivaBtnCompro(idIPEstaLic){
    var idIpEntLic = idIPEstaLic;

    if (idIpEntLic == null || idIpEntLic == undefined || idIpEntLic == '') {
        mostrarNotificacion('error', 'Hubo un error al recibir los datos!!')
        return;
    } else {
        var flgTipoLic = $("#tipoLic" + idIpEntLic).val();
        if(flgTipoLic == 1 || flgTipoLic == 3){
        	console.log(flgTipoLic);
            $("#btnComprobante"+idIpEntLic).css("display","none");
            $('#hasCompro'+idIPEstaLic).html('');
        }else if(flgTipoLic == 4){console.log(idIpEntLic);
        	$("#btnComprobante"+idIpEntLic).css("display","none");
        	var selectCont = '<select class="form-control select2" id="idSelectHasCompro'+idIpEntLic+'">'
					            +'<option value="1" selected>SI</option>'
					            +'<option value="2">NO</option>'
					            +'</select>';
        	$('#hasCompro'+idIPEstaLic).html(selectCont);        	
        }else{
            $("#btnComprobante"+idIpEntLic).css("display","block");
            $('#hasCompro'+idIPEstaLic).html('');
        }
    }
}