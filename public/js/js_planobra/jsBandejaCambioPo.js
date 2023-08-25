
var itemplanGlobal   = null;
var idEstacionGlobal = null;
function getCmbEstacion() {
    itemplanGlobal = $('#inputItemplan').val();

    if(itemplanGlobal.length != 13) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getCmbEstacionCambioPo',
        data : { itemplan : itemplanGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        console.log(data.cmbEstacion);
        $('#cmbEstacion').html(data.cmbEstacion)
    });
}

function getCmbPo() {
    if(itemplanGlobal.length != 13) {
        return;
    }

    idEstacionGlobal = $('#cmbEstacion option:selected').val();

    $.ajax({
        type : 'POST',
        url  : 'getCmbCodigoPo',
        data : { itemplan   : itemplanGlobal,
                 idEstacion : idEstacionGlobal }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.cmbCodigoPo != '' && data.cmbCodigoPo != null) {
            $('#contCmbPo').css('display', 'block');
            $('#cmbPo').html(data.cmbCodigoPo);
        } else {
            $('#contCmbPo').css('display', 'none');
            $('#contCmbPo option:selected').val(" ");
        }
    });
}

function registrarData() {
    itemplan   = $('#inputItemplan').val();
    idEstacion = $('#cmbEstacion option:selected').val();
    codigo_po  = $('#cmbPo option:selected').val();

    $.ajax({
        type : 'POST',
        url  : 'registrarData',
        data : { itemplan   : itemplanGlobal,
                 idEstacion : idEstacion,
                 codigo_po  : codigo_po }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandeja);
            initDataTable('#data-table');
            mostrarNotificacion('success', 'se registro correctamente', 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'incorrecto');
        }
    });
}

var itemplanGlobal   = null;
var codigoPoGlobal   = null;
var idEstacionGlobal = null;
var inputGlobal      = null;
function openModalGenerarPO(btn) {
    itemplanGlobal   = btn.data('itemplan');
    codigoPoGlobal   = btn.data('codigo_po');
    idEstacionGlobal = btn.data('id_estacion');
	console.log("codigo_po: "+codigoPoGlobal);
    if(idEstacionGlobal == null || idEstacionGlobal == '' || codigoPoGlobal == null || codigoPoGlobal == '' || itemplanGlobal == null || itemplanGlobal == '') {
        return;
    }
	
    $.ajax({
        type : 'POST',
        url  : 'openModalGenerarPO',
        data : { itemplan          : itemplanGlobal,
                 idEstacion        : idEstacionGlobal,
				 codigo_po 		   : codigoPoGlobal	}
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            if(data.input != null) {
                inputGlobal = data.input;
                $('#contAmplificadores').html(data.inputAmTro);
            }
           
            $('#idCmbComplejidad').html(data.cmbComplejidad);
            modal('modalGenerarComplejidadPO');
        } else {
            return;
        }
    });
}

function generarPOComplejidadDiseno() {
    if(idEstacionGlobal == null || idEstacionGlobal == '' || codigoPoGlobal == null || codigoPoGlobal == '' || itemplanGlobal == null || itemplanGlobal == '') {
        console.log("idestacion: "+idEstacionGlobal);
        console.log("codigoPoGlobal: "+codigoPoGlobal);
        console.log("itemplanGlobal: "+itemplanGlobal);
        return;
    }

    var idTipoComplejidad = $('#idCmbComplejidad option:selected').val();    

    if(idTipoComplejidad == null || idTipoComplejidad == '') {
        console.log("ENTRO2");
        return;
    }

    var nro_amplificador = null;
    var nro_troba        = null
    if(inputGlobal == 1) {
        nro_amplificador = $('#cant_amplificador').val();
    } else if(inputGlobal == 2) {
        nro_troba = $('#cant_troba').val();
    }

    $.ajax({
        type : 'POST',
        url  : 'generarPOComplejidadDiseno',
        data : { itemplan   : itemplanGlobal,
                 codigo_po  : codigoPoGlobal,
                 idEstacion : idEstacionGlobal,
                 idTipoComplejidad : idTipoComplejidad,
                 nro_amplificador  : nro_amplificador,
                 nro_troba         : nro_troba }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            // mostrarNotificacion('success', 'Se gener&oacute; la PO correctamente.', 'confirmado');
            // modal('modalGenerarComplejidadPO');
            swal({
                title: 'Se genero correctamente la PO',
                text: 'Asegurese de validar la informaci&oacute;n!',
                type: 'success',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'OK!'
                
            }).then(function(){
                location.reload();
            });
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
        
    });
}