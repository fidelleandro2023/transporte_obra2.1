/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    $('#data-table').DataTable({
        autoWidth: false,
        responsive: false,
        aaSorting: [],
        lengthMenu: [[5, 10, 20, -1], ["5 Rows", "10 Rows", "20 Rows", "Everything"]],
        language: {searchPlaceholder: "Buscar Registro..."},
        dom: "Blfrtip",
        buttons: [{extend: "excelHtml5", title: "Export Data"},
            {extend: "csvHtml5", title: "Export Data"},
            {extend: "print", title: "Print"}],
        initComplete: function (a, b) {
            $(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')
        }
    });
    $('#contTablaMaterial').css('display', 'block');
});


$(document).ready(function () {
    $('#divresultado').hide();
    $('#div_grafico').hide();
    $('#boton_tabla').hide();
    var d = new Date();
    var strDate = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
    $('#bus_inicio').val(strDate);
    $('#bus_fin').val(strDate);
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,
        language: "es"
    });

});

function buscar_reporteSub() {
    var cmbEstado = $('#cmbEstado').val();
    $.ajax({
        type: 'POST',
        url: 'ajaxReporteSub',
        data: {cmbEstado: cmbEstado}
    }).done(function (data) {
        console.log(data.tablaSla);
        data = JSON.parse(data);
        console.log(data);
        if (data.error == 0) {
            $('#btn_excel').attr('href', "ajaxExcelSub?" + "cmbEstado=" + cmbEstado);
            $('#contTablaMaterial').html(data.tablaSub);
            initDataTable('#tbDataSub');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

function detalleSub(idSubProyecto, number, estado, dias) {

    if (number == 0) {
        mostrarNotificacion('warning', '', 'No hay datos que mostrar');
    } else {
        $('#tbDetalleSla').empty();
        $.ajax({
            type: 'POST',
            url: 'ajaxReporteDetalleSub',
            data: {idSubProyecto: idSubProyecto, estado: estado, dias: dias}
        }).done(function (data) {
            data = JSON.parse(data);
            console.log(data.tablaDetalleSub);
            if (data.error == 0) {
                if (data.tablaDetalleSub) {
                    $('#btn_excel_detalle').attr('href', "ajaxExcelDetalleSub?" + "idSubProyecto=" + idSubProyecto + '&number=' + number + '&estado=' + estado + '&dias=' + dias);
                    $('#divTablaDetalleSub').html(data.tablaDetalleSub);
                    initDataTable('#tbDetalleSub');
                    $("#modalReporteSub").modal("show");
                } else {
                    mostrarNotificacion('warning', data.msj, 'No hay datos que mostrar');
                }
            } else {
                mostrarNotificacion('warning', data.msj, 'No hay datos que mostrar');
            }
        });
    }

}

//function ajax_reporte(url) {
//
//}