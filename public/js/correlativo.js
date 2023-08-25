
function initTableActividades(id_tabla) {
    $(id_tabla).DataTable({
        autoWidth: true,
        responsive: true,
        lengthMenu: [[5, 10, 15, -1], ["5 Filas", "10 Filas", "15 Filas", "Todo"]],
        language: {searchPlaceholder: "Buscar registros..."},
        dom: "Blfrtip",
        buttons: [{extend: "excelHtml5", title: "Exportar datos"},
            {extend: "csvHtml5", title: "Export Data"},
            {extend: "print", title: "Print"}],
        initComplete: function (a, b) {
            $(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')
        }
    });
}

function mostrarNotificacion(tipo,titulo,mensaje){
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