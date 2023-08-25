var idPlanGlb = null;
function getObras() {
    console.log("ENGRESO");
    idPlanGlb = $('#cmbPlan option:selected').val();

    if (idPlanGlb == null || idPlanGlb == '') {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'getObrasPlanificacion',
        data: {id_plan: idPlanGlb}
    }).done(function (data) {
        data = JSON.parse(data)
        $('#contTablaObra').html(data.tablaItemsPlani);
        initDataTable('#data-table');
    });
}

function openModalItemplan() {
    if (idPlanGlb == null || idPlanGlb == '') {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'getItemplanAllBySubPlan',
        data: {id_plan: idPlanGlb}
    }).done(function (data) {
        data = JSON.parse(data);
        arrayData = []
        $('#contTablaItem').html(data.tablaItems);
        initDataTable('#data-table2');
        modal('modalAsigItem');
    });
}

var jsonData = {};
var arrayData = [];
function checkListItem(btn) {
    var itemplan = btn.data('itemplan');
    var cont = btn.data('cont');
    var contador = 0;
    jsonData = {};

    arrayData.forEach(function (data, key) {
        if (data.itemplan == itemplan) {
            contador = 1;
            // jsonData.itemplan = itemplan;
            // jsonData.id_plan  = idPlanGlb;
            // arrayData.push();
            arrayData.splice(key, 1);
            // jsonData = {};
        }
    });

    if (contador == 0) {
        jsonData.itemplan = itemplan;
        jsonData.id_plan = idPlanGlb;
        // arrayData.push(jsonData);
        arrayData.splice(arrayData.length, 0, jsonData);
    }

    console.log(arrayData);
}

function asignarItemPlani() {
    if (arrayData.length == 0) {
        mostrarNotificacion('warning', 'Seleccionar Itemplan', 'verificar');
        return;
    }

    if (idPlanGlb == null || idPlanGlb == '') {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'asignarItemPlani',
        data: {arrayData: arrayData,
            id_plan: idPlanGlb}
    }).done(function (data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            $('#contTablaObra').html(data.tablaItemsPlani);
            initDataTable('#data-table');

            $('#contTablaItem').html(data.tablaItems);
            initDataTable('#data-table2');

            mostrarNotificacion('success', 'Se asginaron los itemplans correctamente', 'Verificar');
        } else {
            mostrarNotificacion('warning', data.msj, 'verificar');
        }
    });
}

var idFaseGlbReg = null;
var idSubGlbReg = null;
function getDataCuotas() {
    idFaseGlbReg = $('#cmbFase option:selected').val();
    faseDesc = $('#cmbFase option:selected').text();
    idSubGlbReg = $('#cmbSub option:selected').val();

    if (faseDesc == '' || faseDesc == null || idSubGlbReg == '' || idSubGlbReg == null) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'getDataCuotasPlan',
        data: {idSubProyecto: idSubGlbReg,
            faseDesc: faseDesc,
            idFase: idFaseGlbReg}
    }).done(function (data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            if (data.cantidadCuotas == null || data.cantidadCuotas == '') {
                mostrarNotificacion('warning', 'No cuenta con cuotas configuradas.', 'verificar');
                return;
            }

            $('#cantidadCuotas').val(data.cantidadCuotas);
            $('#contTablaPlanifica').html(data.tbPlanifica);
            initDataTableActual('#data-table4');
        } else {
            mostrarNotificacion('warning', data.msj, 'verificar');
        }
    });
}

function regPlanificacion() {
    var nomPlan = $('#nomPlan').val();
    var cantidad = Number($('#cantidadPlan').val());
    var idMes = $('#cmbMes option:selected').val();
    var cantidadCuotas = Number($('#cantidadCuotas').val());
    var idSubProyecto = $('#cmbSub option:selected').val();

    if (nomPlan == null || nomPlan == '') {
        mostrarNotificacion('warning', 'Ingresar el nombre del plan.', 'verificar');
        return;
    }
    if (idMes == null || idMes == '') {
        mostrarNotificacion('warning', 'Seleccionar mes.', 'verificar');
        return;
    }
    if (cantidad == null || cantidad == '') {
        mostrarNotificacion('warning', 'Ingresar cantidad de plan.', 'verificar');
        return;
    }

    if (idSubProyecto == null || idSubProyecto == '') {
        mostrarNotificacion('warning', 'Seleccionar subproyecto.', 'verificar');
        return;
    }

    if (idFaseGlbReg == null || idFaseGlbReg == '') {
        mostrarNotificacion('warning', 'Seleccionar fase.', 'verificar');
        return;
    }

    if (cantidadCuotas == null || cantidadCuotas == '') {
        mostrarNotificacion('warning', 'No tiene cuotas, se debe configurar.', 'verificar');
        return;
    }
    console.log("CUO: " + cantidadCuotas);
    console.log("CANT: " + cantidad);
    if (cantidadCuotas < cantidad) {
        mostrarNotificacion('warning', 'La cantidad de plan debe ser menor a la cantidad de cuotas.', 'verificar');
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'insertPlanifica',
        data: {
            nomPlan: nomPlan,
            cantidad: cantidad,
            idMes: idMes,
            idFase: idFaseGlbReg,
            idSubProyecto: idSubProyecto,
            cantidadCuotas: cantidadCuotas
        }
    }).done(function (data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            mostrarNotificacion('success', 'Se registro el plan correctamente.', 'verificar');
            $('#contTablaPlanifica').html(data.tbPlanifica);
        } else {
            mostrarNotificacion('warning', data.msj, 'verificar');
        }
    });
}

function generarOcPlan() {
    if (idPlanGlb == null || idPlanGlb == '') {
        return;
    }

    swal({
        title: 'Esta seguro de generar las solicitud OC?',
        text: 'Para poder generar debe llegar al tope de la cantidad planificada!',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, generar',
        cancelButtonClass: 'btn btn-secondary'
    }).then(function () {
        $.ajax({
            type: 'POST',
            url: 'generarOcByPlan',
            data: {
                id_plan: idPlanGlb
            }
        }).done(function (data) {
            data = JSON.parse(data);

            if (data.error == 0) {
                mostrarNotificacion('success', 'Se registro la solicitud correctamente.', 'verificar');
                // $('#contTablaPlanifica').html(data.tbPlanifica);
            } else {
                mostrarNotificacion('warning', data.msj, 'verificar');
            }
        });
    });
}

var cantidad_plan_gbl = null;
var nom_plan_gbl = null;
var id_plan_gbl
function openModalEditPlan(btn) {
    cantidad_plan_gbl = btn.data('cantidad_plan');
    nom_plan_gbl = btn.data('nom_plan');
    id_plan_gbl = btn.data('id_plan');

    $('#edit_nom_plan').val(nom_plan_gbl);
    $('#edit_cantidad').val(cantidad_plan_gbl);

    modal('modalEditPlan');
}

function actualizarPlanAsig() {
    var nomPlan = $('#edit_nom_plan').val();
    var cantPlan = $('#edit_cantidad').val();

    if (idFaseGlbReg == '' || idFaseGlbReg == null || idSubGlbReg == '' || idSubGlbReg == null) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'actualizarPlanAsig',
        data: {
            id_plan: id_plan_gbl,
            cantidad_plan: cantPlan,
            nom_plan: nomPlan,
            idFase: idFaseGlbReg,
            idSubProyecto: idSubGlbReg
        }
    }).done(function (data) {
        data = JSON.parse(data);

        if (data.error == 0) {
            mostrarNotificacion('success', 'Se actualizo el plan correctamente.', 'verificar');
            $('#contTablaPlanifica').html(data.tbPlanifica);
        } else {
            mostrarNotificacion('warning', data.msj, 'verificar');
        }
    });
}

function initDataTableActual(id_tabla) {
    $(id_tabla).DataTable({
        autoWidth: false,
        responsive: false,
        aaSorting: [],
        lengthMenu: [[15, 30, 45, -1], ["15 Rows", "30 Rows", "45 Rows", "Everything"]],
        language: {searchPlaceholder: "Search for records..."},
        dom: "Blfrtip",
        buttons: [{extend: "excelHtml5", title: "Export Data"},
            {extend: "csvHtml5", title: "Export Data"},
            {extend: "print", title: "Print"}],
        initComplete: function (a, b) {
            $(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')
        }
    });
}