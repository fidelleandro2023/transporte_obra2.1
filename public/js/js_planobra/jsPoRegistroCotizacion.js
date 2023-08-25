var itemplanGlobal = null;
function getTablaPartidasCotizacion() {
    itemplanGlobal = $('#textItemplan').val();
    
    $.ajax({
        type : 'POST',
        url  : 'getTablaPartidasCotizacion',
        data : { itemplan : itemplanGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTablaPartidas').html(data.tbPartidasGraf);
            initTabla('tbPartidasGraf');
        } else {
            return;
        }
    });
}

var arraySelectPartida = [];
var jsonSelectPartidaGlobal  = {};
var baremo = null;
var costo  = null;
function getDataInsert(idPartida, cont, btn) {
    baremo = btn.data('baremo');
    costo  = btn.data('costo');
    
    var contador = 0;
    var cantidad = $('#cantidad_'+cont).val();
    if(idPartida == '' || idPartida == null) {
        return;
    }
    
    var total = (Number(baremo)*Number(costo)*Number(cantidad)).toFixed(2);
    total = formatNumber(total);
    $('#total_'+cont).val(total);

    arraySelectPartida.forEach(function(data, key){
        // if(!$('#checkZonal_'+cont).is(':checked')) {
            if(data.idPartida == idPartida) {
                data.cantidad = cantidad;
                contador = 1;
            }
        // }
    });
    
    if(contador == 0) {
        jsonSelectPartidaGlobal.idPartida = idPartida;
        jsonSelectPartidaGlobal.cantidad  = cantidad;
        // arrayData.push(jsonData);
        arraySelectPartida.splice(arraySelectPartida.length, 0, jsonSelectPartidaGlobal);
        jsonSelectPartidaGlobal = {};
    }
}

function openModalAlerta() {
    modal('modalAlerta');
}

function generarPOCotizacion() {
    $.ajax({
        type : 'POST',
        url  : 'generarPOCotizacion',
        data : { arrayPartidas : arraySelectPartida,
                 itemplan      : itemplanGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            modal('modalAlerta');
            mostrarNotificacion('success', 'Se ingreso correctamente', 'ingreso');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
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
                $(this).append('<td></td><td></td><td></td><td><div class="form-control container" align="center"><button onclick="openModalAlerta();" class="btn btn-success">ACEPTAR</button></div></td><td></td><td></td>');
                }
            });		
        
    });
}