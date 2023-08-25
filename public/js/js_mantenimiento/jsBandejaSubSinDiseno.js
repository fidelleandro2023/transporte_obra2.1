var arraySelectSubGlobal = [];
var jsonSelectSubGlobal  = {};
function getDataInsert(idSubProyecto,cont) {
    var contador = 0;

    if(idSubProyecto == null || idSubProyecto == '') {
        return;
    }


    arraySelectSubGlobal.forEach(function(data, key){
        if(!$('#checkSubProyecto_'+cont).is(':checked')) {
            if(data.idSubProyecto == idSubProyecto) {
                contador = 1;
                arraySelectSubGlobal.splice(key, 1);
            }
        }
    });
    
    if(contador == 0) {
        jsonSelectSubGlobal.idSubProyecto = idSubProyecto;
        jsonSelectSubGlobal.idEstadoPlan  = 3;
        jsonSelectSubGlobal.flgActivo     = 1;
        // arrayData.push(jsonData);
        arraySelectSubGlobal.splice(arraySelectSubGlobal.length, 0, jsonSelectSubGlobal);
        jsonSelectSubGlobal = {};
    }
}

function insertSubProyecto() {
    if(arraySelectSubGlobal.length == 0) {
        return;
    }
    
    $.ajax({
        type : 'POST',
        url  : 'insertSubProyecto',
        data : { arraySelectSubProyecto : arraySelectSubGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            arraySelectSubGlobal = [];
            $('#contTablaSubProyecto').html(data.tablaSubProyecto);
            initTabla('tbSubProyecto'); 
            $('#contTablaSinDiseno').html(data.tablaSinDiseno);
            initDataTable('#data-table');
            mostrarNotificacion('success','ingreso correcto', 'correcto');
        } else {
            mostrarNotificacion('error',data.msj, 'incorrecto');
        }
    });
}

var idSubProyectoDelete = null;
function openModalEliminarMat(btn) {
    idSubProyectoDelete = btn.data('id_subproyecto');

    modal('modalAlertaEliminar');
}

function deleteSubProyecto() {
    if(idSubProyectoDelete == null || idSubProyectoDelete == '') {
        return;
    }
    
    $.ajax({
        type : 'POST',
        url  : 'deleteSubProyecto',
        data : { idSubProyecto : idSubProyectoDelete }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            arraySelectSubGlobal = [];
            $('#contTablaSubProyecto').html(data.tablaSubProyecto);
            initTabla('tbSubProyecto'); 
            $('#contTablaSinDiseno').html(data.tablaSinDiseno);
            initDataTable('#data-table');
            modal('modalAlertaEliminar');        
            mostrarNotificacion('success','se elimin&oacute; correctamente', 'correcto');
        } else {
            mostrarNotificacion('error',data.msj, 'incorrecto');
        }
    });
}

function initTabla(id) {
    $(document).ready(function () {
        $('#'+id).DataTable({
            autoWidth:false,
            responsive:false,
                        aaSorting: [],
            lengthMenu:[[5,10,20,-1],["5 Rows","10 Rows","20 Rows","Everything"]],
            language:{searchPlaceholder:"Buscar Material..."},
            dom:"Blfrtip",
            buttons:[{extend:"excelHtml5",title:"Export Data"},
                    {extend:"csvHtml5",title:"Export Data"},
                    {extend:"print",title:"Print"}],
            initComplete:function(a,b){
                $(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')
                $(this).append('<td></td><td><div class="form-control container" align="center"><button onclick="insertSubProyecto();" class="btn btn-success">ACEPTAR</button></div></td><td></td>');
                }
            });		
        
    });
}