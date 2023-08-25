var arrayCheckGlobal = [];
var itemplanGlobal 	= null;
var ptrGlobal      	= null;
var codigo      	= null;
var vr		      	= null;
var jsonData = {};
var arrayData = [];
function openModalCheck(btn) {
    itemplanGlobal = btn.data('itemplan');
    ptrGlobal      = btn.data('ptr');
    codigo	       = btn.data('codigo');
    vr	      	   = btn.data('vr');
    arrayCheckGlobal = [];
    arrayData        = []
    $.ajax({
        type : 'POST',
        url  : 'getModalCheck',
        data : { itemplan : itemplanGlobal,
                 ptr      : ptrGlobal,
                 codigo	  :	codigo,
                 vr		  :	vr}
    }).done(function(data) {
        data = JSON.parse(data);
        $('#contCheck').html(data.tablaCheck);
        $('#titulo').html('<h3 style="color:red">Itemplan: '+itemplanGlobal+'</h3>');
        modal('modalCheck');
        initDataTable('#checkModal');
    })
}
// var json = {};
// function setCheckArray(btn) {
//     var idSolicitudValeReserva = btn.data('id_solicitud_vale');
//     var idTextArea             = btn.data('id_textarea');

//     var contador = 0;


//     //json.idSolicitudVale = idSolicitudValeReserva;

//     arrayCheckGlobal.forEach(function(data, key){
//         if(data == idSolicitudValeReserva) {
//             contador = 1;
//             arrayCheckGlobal.splice(key, 1);
//         }
//     });
//     if(contador == 0) {
//         arrayCheckGlobal.splice(arrayCheckGlobal.length, 0, idSolicitudValeReserva);
//     }
//     console.log(arrayCheckGlobal);
// }

function openModalAlertaSeleccionMaterial() {
    modal('modalAlertaAceptacion');
}


function getData(btn, check) {
    var idVal = btn.data('id_textarea');
    var id    = btn.data('id_solicitud_vale');
    var cont  = btn.data('cont');
    var descripcion = $('#'+idVal).val();

    var flg_adicion = btn.data('flg_adicion');
    var id_material = btn.data('id_material');
    var cantidad_fin = btn.data('cantidad_fin');

    flg_adicion = (flg_adicion) ? flg_adicion : null;
    id_material = (id_material) ? id_material : null;
    //cantidad_fin = (cantidad_fin) ? cantidad_fin : null;

    var contador = 0;
    var flg_estado = '';
    check = (check != null) ? 1 : '';
    if(check == 1) {
        if( $('#check_'+cont).prop('checked') ) {
            flg_estado = 1;
        } else {
            flg_estado = 0;
        }
    }

    arrayData.forEach(function(data, key){
        if(data.idSolicitudValeReserva == id) {
            contador = 1;
            jsonData.idSolicitudValeReserva = id;
            jsonData.comentario             = descripcion;
            jsonData.flg_estado             = flg_estado;
            jsonData.flg_adicion            = flg_adicion;
            jsonData.id_material            = id_material;
            jsonData.cantidad_fin           = cantidad_fin;
            // arrayData.push();
            arrayData.splice(key, 1, jsonData);
            jsonData = {};

 
        } 
    });
    // if(descripcion != null) {
    //     id    = $(this).attr('data-id_solicitud_vale');
    //     jsonData.idSolicitudValeReserva = id;
    //     jsonData.comentario             = descripcion;
    //     jsonData.flg_estado             = '';
    //     arrayData.push(jsonData);
    //     jsonData = {};
    // }

    if(contador == 0) {
        jsonData.idSolicitudValeReserva = id;
        jsonData.comentario             = descripcion;
        jsonData.flg_estado             = flg_estado;
        jsonData.flg_adicion            = flg_adicion;
        jsonData.id_material            = id_material;
        jsonData.cantidad_fin           = cantidad_fin;
        // arrayData.push(jsonData);
        arrayData.splice(arrayData.length, 0, jsonData);
        jsonData = {};
    }
    console.log(arrayData);
}

function ingresarFlgDevolucion() {console.log("ENTRO");
    $.ajax({
        type : 'POST',
        url  : 'ingresarFlgDevolucion',
        data : { arrayData : arrayData,
                 itemplan  : itemplanGlobal,
                 ptr       : ptrGlobal }
    }).done(function(data) {
        data = JSON.parse(data);
        if(data.error == 0) {
            mostrarNotificacion('success','ingreso correcto', 'correcto');
            modal('modalAlertaAceptacion');
            modal('modalCheck');
            $('#contTabla').html(data.tablaBandejaSolicitud);
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error', data.msj, 'incorrecto');
        }
    })
}

function setDescripcion(btn) {
    var idSolicitudValeReserva = btn.data('id_solicitud_vale');
    var idTextArea             = btn.data('id_textarea');

    var descripcion = $('#'+idTextArea).val();
}

function filtrarBandejaSolicitudVr() {
    var idJefatura      = $('#cmbJefatura option:selected').val();
    var idEmpresaColab  = $('#cmbEcc option:selected').val();
    var idTipoSolicitud = $('#cmbTipoSolicitud option:selected').val();
    var idFase          = $('#selectFase option:selected').val();
    var tipoAtencion    = $('#cmbTipoAtencion option:selected').val();
    var itemplan	    = $('#txtItemPlan').val();
    $.ajax({
        type : 'POST',
        url  : 'filtrarBandejaSolicitudVr',
        data : { idJefatura      : idJefatura,
                 idEmpresaColab  : idEmpresaColab,
                 idTipoSolicitud : idTipoSolicitud,
                 idFase          : idFase,
                 tipoAtencion	 : tipoAtencion,
                 itemplan		 : itemplan}
    }).done(function(data) {
        data = JSON.parse(data);
        $('#contTabla').html(data.tablaBandejaSolicitud);
        initDataTable('#data-table');
    })
}

var codMaterialRpaGlb = null;
var materialGlb = null;
function openModalAlertRpa(btn) {
    materialGlb = btn.data('material');
    codMaterialRpaGlb = btn.data('codigo_solicitud');
    console.log(codMaterialRpaGlb);
    console.log(materialGlb);
    modal('modalAlertaRpa');
}

function actualizarFlagRpa() {
    $.ajax({
        type : 'POST',
        url  : 'actualizarFlagRpa',
        data : { codigo_solicitud : codMaterialRpaGlb,
                 material         : materialGlb }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            modal('modalAlertaRpa');
            $('#contCheck').html(data.tablaCheck);
            mostrarNotificacion('success', 'se asigno el material: '+codMaterialRpaGlb+ "para el robot", 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'incorrecto');
        }
        
    });
}