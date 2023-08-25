
// function seleccionarTodo() {
//     //$('.checkboxFirma').prop('checked', true);
//     // initDataTable('#data-table');
//     // $('#data-table tr').each(function(data){
//     //     console.log(data);
//     // });
//     var data = null;
//     var table = $('#data-table').DataTable();
//     DOMparser = new DOMParser();
//     console.log(table);
//     var cont=0;
//     table.data().each(function(data){
//         // data = d;
//         var html = data[0];
//         DOMdata   = DOMparser.parseFromString(html, "text/html");
//         // console.log($('#descripcion_'+cont).val());
//         console.log($(DOMdata.body).children()[0]);
//     //    $(DOMdata.body).children().each(function(data){
//     //         console.log(data[0]);
//     //     });

//         $('.checkboxFirma_'+cont).prop('checked', true);
//         // console.log($(DOMdata.body).child()[input[type='checkbox']]);
//         cont++;
//     });
//     // console.log($(this).html(data));
//     // $('input[type=checkbox]').prop('checked'); 
//     // $("input:checked").each(function() {
 
//     //     data['services[]'].push($(this).val()); 
//     //     });
// }

function seleccionarTodo(checktoggle=true) {
    var oTable = $('#data-table').DataTable();
    $('#selectall').click(function () {

        var checkall =$('.main_table').find(':checkbox').attr('checked','checked');
        $.uniform.update(checkall);
     });
}

$(function () {
    var oTable = $('#datatable').dataTable();
    $('#selectall').click(function () {

       var checkall =$('.main_table').find(':checkbox').attr('checked','checked');
    //    $.uniform.update(checkall);
       console.log($.uniform);
    });
});



var arraySelectAprob = [];
var jsonSelectZonalGlobal  = {};
function getDataInsert(btn,cont) {
    var contador = 0;
    var itemplan  = btn.data('itemplan');
    var codigo_po = btn.data('codigo_po');

    if(itemplan == null || itemplan == '' || codigo_po == '' || codigo_po == null) {
        return;
    }


    arraySelectAprob.forEach(function(data, key){
        if(!$('#checkboxFirma_'+cont).is(':checked')) {
            if(data.itemplan == itemplan && data.codigo_po == codigo_po) {
                contador = 1;
                arraySelectAprob.splice(key, 1);
            }
        }
    });
    
    if(contador == 0) {
        jsonSelectZonalGlobal.itemplan  = itemplan;
        jsonSelectZonalGlobal.codigo_po = codigo_po;

        // arrayData.push(jsonData);
        arraySelectAprob.splice(arraySelectAprob.length, 0, jsonSelectZonalGlobal);
        jsonSelectZonalGlobal = {};
    }
}

var poGlobal = null;
var itemplanGlobal = null;
function openModalValidarFirmaDigital() {
    if(arraySelectAprob.length == 0) {
        mostrarNotificacion('error', 'debe seleccionar', 'error');
        return;
    }
    
    modal('modalAlertaValidacion');
}

function validarFirmaDigital() {
    $.ajax({
        type : 'POST',
        url  : 'validarFirmaDigital',
        data : { arraySelectAprob : arraySelectAprob }
    }).done(function(data){
        data = JSON.parse(data);

        if(data.error == 0) {
            modal('modalAlertaValidacion');
            $('#contTablaFirmaDigital').html(data.tablaFirmaDigital);
            initDataTable('#data-table');
            mostrarNotificacion('success', 'se ingres&oacute; correctamente', 'confirmado');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

var idEmpresaColabGlobal = null;
var idJefaturaGlobal     = null;
var fechaInicio          = null;
var fechaFin             = null;
function filtrarTablaFirmaDigital() {
    idEmpresaColabGlobal = $('#cmbEmpresaColab option:selected').val();
    idJefaturaGlobal     = $('#cmbJefatura option:selected').val();
    fechaInicio          = $('#fechaInicio').val();
    fechaFin             = $('#fechaFin').val();

    console.log(fechaFin);
    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaFirmaDigital',
        data : { idEmpresaColab : idEmpresaColabGlobal,
                 idJefatura     : idJefaturaGlobal,
                 fechaInicio    : fechaInicio,
                 fechaFin       : fechaFin }
    }).done(function(data){
        data = JSON.parse(data);
        
        if(data.error == 0) {
            //$('#contenido').css('display', 'block');
            $('#contTablaFirmaDigital').html(data.tablaFirmaDigital);
            initDataTable('#data-table');
        } else {
            return;
        }
        
    });
}
