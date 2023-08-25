var arrayDataCheck = [];
var arrayJsonDetalle = [];
var itemplanGlobal   = null;
function openModalEntidadesReembolso(btn) {
    itemplanGlobal = btn.data('itemplan');
    
    if(itemplanGlobal == '' || itemplanGlobal == null) {
        return;
    }
    
    $.ajax({
        type : 'POST',
        url  : 'openModalEntidadesReembolso',
        data : { itemplan : itemplanGlobal }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            $('#contDataEntidad').html(data.tablaDetalleReembolso);
            arrayJsonDetalle = JSON.parse(data.dataJsonDetalle);
            initDataTable('#table_detalle');
            modal('modalDataEntidades');
            arrayDataCheck = [];
        } else {
            mostrarNotificacion('success', data.msj);
        }
        console.log(arrayJsonDetalle);
    });
}

function setDataCheck(btn) {
    var id_licencia_entidad  = btn.data('id_licencia_entidad');
    var idEntidad = btn.data('id_entidad');
    var ruta_reembolso = '';
    var nro_reembolso = '';
    for(var i = 0; i < arrayJsonDetalle.length; i++){
        if(arrayJsonDetalle[i]['id_licencia_entidad'] == id_licencia_entidad){
            ruta_reembolso = arrayJsonDetalle[i]['ruta_foto'];
            nro_reembolso = arrayJsonDetalle[i]['nro_reembolso'];
            break;
        }
    }
    insertJsonDataDetalle(id_licencia_entidad,nro_reembolso,ruta_reembolso);
}

function insertJsonDataDetalle(id_licencia_entidad,nro_reembolso,ruta_reembolso) {
    console.log("id_licencia_entidad: "+id_licencia_entidad);
    var json_data = {};

    var monto = parseFloat($('#text_monto_fin_'+id_licencia_entidad).val());

    if(monto == null || monto == '' || monto == 0) {
        mostrarNotificacion('error', 'Ingresar Monto correctamente', 'Verificar');
        return;
    }

    if($('#checkBox_'+id_licencia_entidad).is(':checked')) {
        json_data.flg_valida_reembolso = 1;			
        if(arrayDataCheck.length > 0) {
            i = 0;
            flg_updt_monto = 0;
            arrayDataCheck.forEach(function(data){
                if(data.id_licencia_entidad == id_licencia_entidad) {
                    data.monto = monto;
                    flg_updt_monto = 1;
                }
                i++;
            });

            if(flg_updt_monto == 0) {
                json_data.id_licencia_entidad = id_licencia_entidad;
                json_data.monto = monto;
                json_data.nro_reembolso = nro_reembolso;
                json_data.ruta_reembolso = ruta_reembolso;
                arrayDataCheck.push(json_data);
            }
        } else {
            json_data.id_licencia_entidad = id_licencia_entidad;
            json_data.monto = monto;
            json_data.nro_reembolso = nro_reembolso;
            json_data.ruta_reembolso = ruta_reembolso;
            arrayDataCheck.push(json_data);
        }

    } else {
        i = 0;
        arrayDataCheck.forEach(function(data){
            if(data.id_licencia_entidad == id_licencia_entidad) {
                arrayDataCheck.splice(i, 1);
            }
            i++;
        });
    }
    console.log(arrayDataCheck);
}

function setCheckAll() {
    if($('#checkBoxAll').is(':checked')) {
        arrayJsonDetalle.forEach(function(data){
            $('#checkBox_'+data.id_licencia_entidad).prop('checked', true);
            insertJsonDataDetalle(data.id_licencia_entidad,data.nro_reembolso,data.ruta_foto);      
        });
    } else {
        arrayJsonDetalle.forEach(function(data){
            $('#checkBox_'+data.id_licencia_entidad).prop('checked', false);
            arrayDataCheck = [];       
        });
    }
}

function validarEntidadReembolso() {
    var flg_valida_monto = 0;
    arrayDataCheck.forEach(function(data){
        if(isNaN(data.monto) || data.monto == null || data.monto == undefined || data.monto == '') {
           flg_valida_monto = 1;
        }
    });

    if(flg_valida_monto == 1) {
        mostrarNotificacion('error', 'Verificar los montos, hay montos mal ingresados.', 'Verificar');
        return;
    }

    if(itemplanGlobal == '' || itemplanGlobal == null) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'validarEntidadReembolso',
        data : { arrayDataJson : JSON.stringify(arrayDataCheck),
                 itemplan      : itemplanGlobal 
        },
        beforeSend: () => {
            $('#btnSave').attr("disabled", true);
        }
    }).done(function(data){
        data = JSON.parse(data);
        console.log(data);
        if(data.error == 0) {
            $('#contTabla').html(data.tablaEntidad);
            initDataTable('#data-table');
            mostrarNotificacion('success', data.msj, 'correcto');
        } else {
            mostrarNotificacion('error', data.msj);
        }
    }).always(() => {
        $('#btnSave').removeAttr("disabled");
        modal('modalDataEntidades');
    });
}

function filtrarBandejaReembolsoLicencia() {
    var estado  = $('#selectEstado option:selected').val(); 
    var item    = $('#txtItemplan').val();

    $.ajax({
        type : 'POST',
        url  : 'filtrarBandejaReembolsoLicencia',
        data : {item     : item,
	        	estado   : estado} 
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandejaReembolso);
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error','error', data.msj);
        }
    });
}