function getCmbEstacion() {
    var idSubProyecto    = $('#cmbSubProyecto option:selected').val();

    
    if(idSubProyecto == null || idSubProyecto == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getCmbEstacion',
        data : { idSubProyecto    : idSubProyecto }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#cmbEstacion').html(data.cmbEstacion);
            //initDataTable('#data-table');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

var idMaterialGlobal = null;
function openModalValorPorcentualCant(check) {
    idMaterialGlobal = check.data('id_material');
    modal('modalCantValorPorcentual');
}

var idSubProyectoGlobal = null;
var idEstacionGlobal    = null;
function getKitMateriales() {
    idSubProyectoGlobal = $('#cmbSubProyecto option:selected').val();
    idEstacionGlobal    = $('#cmbEstacion option:selected').val();

    if(idSubProyectoGlobal == null || idSubProyectoGlobal == '') {
        return;
    }

    if(idEstacionGlobal == null || idEstacionGlobal == '') {
        return;
    }
 
    $.ajax({
        type : 'POST',
        url  : 'getKitMateriales',
        data : { idSubProyecto : idSubProyectoGlobal,
                 idEstacion    : idEstacionGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTablaKit').html(data.tablaKit);
            //$('#contTablaMaterial').html(data.tablaMaterial);
            //inicializarTb('tbKitMaterial');
            $('#contTablaKit').css('display', 'block');
            $('#contTablaMaterial').css('display', 'block');
            initDataTable('#data-table');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}


function insertMaterial(check) {
    var idSubProyecto    = $('#cmbSubProyecto option:selected').val();
    var cantidadKit      = $('#cantidadKit').val();
    var factorPorcentual = $('#factorPorcentual').val();

    if(idSubProyecto == null || idSubProyecto == '' || cantidadKit == null || cantidadKit == '' || idEstacionGlobal == null || idEstacionGlobal == '') {
        return;
    }
    
    if(idMaterialGlobal == null || idMaterialGlobal == '' || factorPorcentual == null || factorPorcentual == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'insertMaterial',
        data : { idSubProyecto    : idSubProyecto,
                 idMaterial       : idMaterialGlobal,
                 cantidadKit      : cantidadKit,
                 factorPorcentual : factorPorcentual,
                 idEstacion       : idEstacionGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTablaKit').html(data.tablaKit);
            modal('modalCantValorPorcentual');
            mostrarNotificacion('success', 'Se ingreso el material a este itemplan', 'correcto');
            //initDataTable('#data-table');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

var idMaterialDeleteGlobal = null;
function openModalEliminarMat(btn) {
    idMaterialDeleteGlobal = btn.data('id_material');
    modal('modalAlertaEliminar');
}

function eliminarMaterial() {
    if(idMaterialDeleteGlobal == '' || idMaterialDeleteGlobal == null) {
        return;
    }

    if(idSubProyectoGlobal == '' || idSubProyectoGlobal == null) {
        return;
    }

    if(idEstacionGlobal == '' || idEstacionGlobal == null) {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'eliminarMaterial',
        data : { idSubProyecto : idSubProyectoGlobal,
                 idMaterial    : idMaterialDeleteGlobal,
                 idEstacion    : idEstacionGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTablaKit').html(data.tablaKit);
            //$('#contTablaMaterial').html(data.tablaMaterial);
            modal('modalAlertaEliminar');
            mostrarNotificacion('success', 'Se elmino el material de este kit', 'correcto');
            //inicializarTb('tbKitMaterial');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

function inicializarTb(tb) {
    $('#'+tb).DataTable({
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
            }
        });		
}

function subirArchivo() {
    $('.easy-pie-tab2').data('easyPieChart').update('0');
    $('#valuePieTab2').html(0);
}

$('#import_form').on('submit', function(event){
    $('.easy-pie-tab2').data('easyPieChart').update('40');
    $('#valuePieTab2').html(40);

    var input = document.getElementById('fileExcelKit');
    idSubProyectoGlobal = $('#cmbSubProyecto option:selected').val();
    idEstacionGlobal    = $('#cmbEstacion option:selected').val();

    var form = new FormData();

    if(idSubProyectoGlobal == '' || idSubProyectoGlobal == null) {
        mostrarNotificacion('error', 'Debe seleccionar SubProyecto');
        return;
    }

    if(idEstacionGlobal == '' || idEstacionGlobal == null) {
        mostrarNotificacion('error', 'Debe seleccionar estaci&oacute;n');
        return;
    }

    form.append('idSubProyecto', idSubProyectoGlobal);
    form.append('file'         , input.files[0]);
    form.append('idEstacion'   , idEstacionGlobal);
    event.preventDefault();
    
    $.ajax({
        url         : 'insertTbkitMaterialMasivo',
        method      : 'POST',
        data        : form,
        contentType : false,
        cache       : false,
        processData : false
    }).done(function(data){
        $('.easy-pie-tab2').data('easyPieChart').update('70');
        $('#valuePieTab2').html(70);
        data = JSON.parse(data);

        if(data.error == 0) {
			$('.easy-pie-tab2').data('easyPieChart').update('100');
            $('#valuePieTab2').html(100);
            $('#contTablaKit').html(data.tablaKitMat);
            $('#contTablaKit').css('display', 'block');
            mostrarNotificacion('succes', data.msj, 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
});