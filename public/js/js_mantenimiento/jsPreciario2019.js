var idEmpresaColabGlobal = null;
var idZonalGlobal        = null;
var idTipoCostoGlobal    = null;
var idEstacionGlobal       = null;

var empresacolabTextGlobal = null;
var zonalDescGlobal        = null;
var tipoCostoDescGlobal    = null;
var estacionDescGlobal     = null;
function getPreciarioTb() {
    idZonalGlobal        = $('#cmbZonal option:selected').val();
    idEmpresaColabGlobal = $('#cmbEcc option:selected').val();
    idTipoCostoGlobal    = $('#cmbTipoCosto option:selected').val();
    idEstacionGlobal     = $('#cmbEstacion option:selected').val();



    if(idEmpresaColabGlobal == '' || idEmpresaColabGlobal == null) {
        return;
    }
    
    subTitulo();

    // if(idEmpresaColabGlobal == '' && idTipoCostoGlobal == '' && idEstacion == '') {
    //     $('#textZonalEcc').text(empresacolabTextGlobal+" - "+zonalDescGlobal);
    // }
    
    $.ajax({
        type : 'POST',
        url  : 'getPreciarioTb2019',
        data : { idZonal        : idZonalGlobal,
                 idEmpresaColab : idEmpresaColabGlobal,
                 idTipoCosto    : idTipoCostoGlobal,
                 idEstacion     : idEstacionGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('.section').css('display', 'block');
            modal('modalFiltroInicial');
            $('#contTablaPreciario').html(data.tablaPreciario);
            $('#contTablaZonal').html(data.tablaZonal);
            inicializarTb('tbPreciario');
            inicializarTb('tbZonal');
            if(idZonalGlobal != '') {
                $('#btnZonal').css('display', 'none');
            }

            if(idTipoCostoGlobal != '') {
                $('#btnTipoCosto').css('display', 'none');
            }
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

var idZonal        = null;
var idEmpresaColab = null;
var idTipoCosto    = null;
var idEstacion     = null;
function openModalCosto(btn) {
    idZonal = btn.data('id_zonal');

    $.ajax({
        type : 'POST',
        url  : 'getCmbsPreciario',
        data : { idZonalfiltro        : idZonalGlobal,
                 idEmpresaColabFiltro : idEmpresaColabGlobal,
                 idTipoCostoFiltro    : idTipoCostoGlobal,
                 idEstacionFiltro     : idEstacionGlobal
                }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contCmbEcc').html(data.cmbEcc);
            $('#contCmbEstacion').html(data.cmbEstacion);
            $('#contCmbTipoCosto').html(data.cmbTipoCosto);
        } else {
            return;
        }
    });
   
    modal('modalCosto');
}

function insertZonal() {
    var costo = $('#inputCosto').val();

    if(costo == '' || costo == null || idZonal == null || idZonal == '') {
        return;
    }

    idEmpresaColab = $('#cmbEccIngresa option:selected').val();
    idTipoCosto = $('#cmbTipoCostoIngresa option:selected').val();
    idEstacion = $('#cmbEstacionIngresa option:selected').val();

    if(idEmpresaColab == '' || idEmpresaColab == null) {
        return;
    }

    if(idTipoCosto == '' || idTipoCosto == null) {
        return; 
    }
    
    if(idEstacion == '' || idEstacion == null) {
        return;        
    }
  
   
    $.ajax({
        type : 'POST',
        url  : 'insertZonal',
        data : { idZonal              : idZonal,
                 idZonalfiltro        : idZonalGlobal,
                 idEmpresaColab       : idEmpresaColab,
                 idEmpresaColabFiltro : idEmpresaColabGlobal,
                 idTipoCosto          : idTipoCosto,
                 idTipoCostoFiltro    : idTipoCostoGlobal,
                 costo                : costo,
                 idEstacion           : idEstacion,
                 idEstacionFiltro     : idEstacionGlobal,
                 }
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contTablaPreciario').html(data.tablaPreciario);
            inicializarTb('tbPreciario');
            modal('modalCosto');
            mostrarNotificacion('success', 'Se ingreso', 'correcto');
            //initDataTable('#data-table');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
}

var arraySelectZonalGlobal = [];
var jsonSelectZonalGlobal  = {};
function getDataInsert(idZonal,cont) {
    var contador = 0;

    if(idZonal == null || idZonal == '' || idEmpresaColabGlobal == '' || idEmpresaColabGlobal == null || idTipoCostoGlobal == '' || idTipoCostoGlobal == null) {
        return;
    }


    arraySelectZonalGlobal.forEach(function(data, key){
        if(!$('#checkZonal_'+cont).is(':checked')) {
            if(data.idZonal == idZonal) {
                contador = 1;
                arraySelectZonalGlobal.splice(key, 1);
            }
        }
    });
    
    if(contador == 0) {
        jsonSelectZonalGlobal.idZonal        = idZonal;
        jsonSelectZonalGlobal.idEmpresaColab = idEmpresaColabGlobal;
        jsonSelectZonalGlobal.idEstacion     = idEstacionGlobal;
        jsonSelectZonalGlobal.idTipoCosto    = idTipoCostoGlobal;
        // arrayData.push(jsonData);
        arraySelectZonalGlobal.splice(arraySelectZonalGlobal.length, 0, jsonSelectZonalGlobal);
        jsonSelectZonalGlobal = {};
    }
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

function data() {
    jsonSelectData.zonalDesc;
    jsonSelectData.empresacolabTextGlobal;
    arrayTablaSelect.splice(arrayTablaSelect.length, 0, jsonSelectData);
    // jsonDataPartidasGlobal = {};
    tabla='<tabla class="table table-bordered">'+
                '<thead class="thead-default">'+
                    '<tr>'+
                        '<th>Nro.</th>'+
                        '<th>ZONAL</th>'+
                        '<th>ACCI&Oacute;N</th>'+             
                    '</tr>'+
                '</thead>'+
            '</tabla>'+
            '<tbody>'+
            '</tbody>';
    console.log(tabla);        
}

function subTitulo() {
    empresacolabTextGlobal = $('#cmbEcc option:selected').text();
    zonalDescGlobal        = $('#cmbZonal option:selected').text();
    tipoCostoDescGlobal    = $('#cmbTipoCosto option:selected').text();
    estacionDescGlobal     = $('#cmbEstacion option:selected').text(); 
    $('#textZonalEcc').text(empresacolabTextGlobal+" - "+zonalDescGlobal+" - "+tipoCostoDescGlobal+" - "+estacionDescGlobal);

    if(idTipoCostoGlobal == '' && idEstacionGlobal == '' && idZonalGlobal == '') {
        $('#textZonalEcc').text(empresacolabTextGlobal);
   }

   if(idZonalGlobal == ''  && idTipoCostoGlobal !=  '' && idEstacionGlobal !=  '') {
       $('#textZonalEcc').text(empresacolabTextGlobal+" - "+tipoCostoDescGlobal+" - "+estacionDescGlobal);
   }

   if(idZonalGlobal != ''  && idTipoCostoGlobal ==  '' && idEstacionGlobal !=  '') {
        $('#textZonalEcc').text(empresacolabTextGlobal+" - "+zonalDescGlobal+" - "+estacionDescGlobal);
    }

    if(idZonalGlobal != ''  && idTipoCostoGlobal !=  '' && idEstacionGlobal ==  '') {
        $('#textZonalEcc').text(empresacolabTextGlobal+" - "+zonalDescGlobal+" - "+tipoCostoDescGlobal);
    }

    if(idTipoCostoGlobal == '' && idEstacionGlobal != '' && idZonalGlobal == '') {
        $('#textZonalEcc').text(empresacolabTextGlobal+" - "+estacionDescGlobal);
   }

   if(idTipoCostoGlobal != '' && idEstacionGlobal == '' && idZonalGlobal == '') {
        $('#textZonalEcc').text(empresacolabTextGlobal+" - "+tipoCostoDescGlobal);
    }

    if(idTipoCostoGlobal == '' && idEstacionGlobal == '' && idZonalGlobal != '') {
        $('#textZonalEcc').text(empresacolabTextGlobal+" - "+zonalDescGlobal);
    }
}