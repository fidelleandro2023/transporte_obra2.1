function recogePTR(){
    //var arrayNamesptrExp = $( "input[name*='ptrExp']" );

    var arrayNamesptrExp = $("input[type=checkbox]:checked");
    var expediente = new Array();

    if(arrayNamesptrExp.length != 0){

        for(i=0;i<arrayNamesptrExp.length;i++){
            expediente.push(arrayNamesptrExp[i].dataset.ptr + "%" + arrayNamesptrExp[i].dataset.item + "%" + arrayNamesptrExp[i].dataset.fecsol + "%" + arrayNamesptrExp[i].dataset.subproyecto + "%" + arrayNamesptrExp[i].dataset.zonal + "%" + arrayNamesptrExp[i].dataset.eecc + "%" + arrayNamesptrExp[i].dataset.area);
        }

        mostrarModal(expediente);

    }else{
        alert('Debe seleccionar al menos 1 registro para continuar.');
    }

    
}

function mostrarModal(expediente){
    var texto = '';
    var ptrModal = '';
    var itemModal = '';
    for(j=0;j<expediente.length;j++){
           //texto += '<label>'+expediente[j].replace('%', ' ')+'</label><br>';
           //ptrModal = expediente[j]
          
           var elem = expediente[j].split('%'); 
           ptrModal = elem[0]; 
           itemModal = elem[1];
           texto += '<label>'+ptrModal+'</label><br>';

        }
    var jsonExpediente =JSON.stringify(expediente);

    $('#seleccionados').html(texto);

    $('#botonConfirmar').attr('data-jsonptr', jsonExpediente);


    $('#modalExpediente').modal('toggle');
      
}
      
          
function asignarExpediente(component){
    var vrLeng = $('#inputVR').val().length;
    
    if(vrLeng==0){
        alert('Usted no ha asignado un comentario de expediente.');
    }else{

       
        var jsonptr = $(component).attr('data-jsonptr');
        var comentario = $('#inputVR').val();


        

        $.ajax({
                type    :   'POST',
                'url'   :   'asignarExpediente',
                data    :   {  jsonptr : jsonptr,
                               comentario : comentario
                           },
                'async' :   false
            }).done(function(data){
                var data    =   JSON.parse(data);
                                                    
                if(data.error == 0){
                    $('#modalExpediente').modal('toggle');                         
                    mostrarNotificacion('success','Registro exitoso.',data.msj);
                    //$('#contTabla').html(data.tablaAsigGrafo)
                    //initDataTable('#data-table');
                    filtrarTabla();
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Error al dar expediente',data.msj);
                }
              });
    }
    
}


function filtrarTabla(){
    //var itemplan = $.trim($('#itemplan').val());
    var erroItemPlan = '';
    var itemplan = $.trim($('#txtItemPlan').val());
    //validar item plan
    //mostrarNotificacion('error','Hubo problemas al filtrar los datos!');

    if(itemplan.length < 13 && itemplan.length >= 1)
        erroItemPlan = 'ItemPlan Invalido.'

    var tipoPlanta = $.trim($('#selectTipoPlanta').val());
    var nombreproyecto = $.trim($('#nombreproyecto').val());
    var nodo = $.trim($('#nodo').val());
    var zonal = $.trim($('#selectZonal').val());
    var proy = $.trim($('#selectProy').val());
    var subProy = $.trim($('#selectSubProy').val());
    var estado = $.trim($('#estado').val());
    var selectMesPrevEjec = $.trim($('#selectMesPrevEjec').val());

    var fechaInicio0 = $('#fechaInicio').val();
    var fechaFin0 =  $('#fechaFin').val();

    var fechaInicio = fechaInicio0.replace(/-/g, '/');
    var fechaFin = fechaFin0.replace(/-/g, '/');
    
    var fechaDestinoDefault = '2018/12/31';
    var fechaDestino = '';
    var filtroPrevEjec = '';

    if(fechaFin0 == ''){
        fechaDestino = fechaDestinoDefault;
    }else{
        fechaDestino = fechaFin;
    }

    if( fechaInicio0 != '' ){
        filtroPrevEjec = " AND p.fechaPrevEjec BETWEEN '"+fechaInicio+"' AND '"+fechaDestino+"' ";
    }else{
        filtroPrevEjec = "";
    }

    if(erroItemPlan == ''){

       $.ajax({
           type	:	'POST',
           'url'	:	'getDataTableItem',
           data	:	{itemplan : itemplan,
                    nombreproyecto : nombreproyecto,
                    nodo : nodo,
                    zonal     : zonal,
                    proy  :	proy,
                    subProy  :    subProy,
                     estado : estado,
                    filtroPrevEjec : filtroPrevEjec,
                    tipoPlanta : tipoPlanta
                    //area : area
                   },
           'async'	:	false
       })
       .done(function(data){
           var data	=	JSON.parse(data);
           if(data.error == 0){           	    	          	    	   
               $('#contTabla').html(data.tablaAsigGrafo)
               initDataTable('#data-table');
               
           }else if(data.error == 1){
               
               mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
           }
         });

    }else{
        mostrarNotificacion('error','ItemPlan',erroItemPlan);
    }
    
    
}




 /****************************Log*************************/
function mostrarLog(component){
    var itemplan = $(component).attr('data-idlog');
  $.ajax({
        type    :   'POST',
        'url'   :   'mostrarLogIPConsulta',
        data    :   {itemplan   :   itemplan},
        'async' :   false
    })
    .done(function(data){
        var data    =   JSON.parse(data);
        if(data.error == 0){                    
            $('#tituloModal').html('ITEMPLAN : '+itemplan);
            $('#contCardLog').html(data.listaLog);
            $('#modal-large').modal('toggle');
        }else if(data.error == 1){                  
            mostrarNotificacion('error','Error al principal',data.msj);
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
         mostrarNotificacion('error','Error al mostrar log principal',errorThrown+ '. Estado: '+textStatus);
      });
}

function verMotivoCancelar(component){                
    var fecha = $(component).attr('data-fechaC');
    var itemplan = $(component).attr('data-itemC');

            
    $.ajax({
        type    :   'POST',
        'url'   :   'getMotivoCancelConsulta',
        data    :   {itemplan   :   itemplan,
                    fecha : fecha},
        'async' :   false
    })
    .done(function(data){
        var data    =   JSON.parse(data);
        if(data.error == 0){                    
            $('#contCardMotivoCancel').html(data.motivoCancel);
            $('#modal-motcancel').modal('toggle');  

        }else if(data.error == 1){                  
            mostrarNotificacion('error','Error ',data.msj);
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
         mostrarNotificacion('error','Error al mostrar el log cancelar',errorThrown+ '. Estado: '+textStatus);
      })
      .always(function() {
     
    });

        
}

function closeMotivoCancelar(){              
    $('#modal-motcancel').modal('toggle');
    $('#modal-large').css('overflow-y', 'scroll');          
}


function verMotivoTrunco(component){                
    var fecha = $(component).attr('data-fechaT');
    var itemplan = $(component).attr('data-itemT');
  
     $.ajax({
        type    :   'POST',
        'url'   :   'getMotivoTruncoConsulta',
        data    :   {itemplan   :   itemplan,
                    fecha : fecha},
        'async' :   false
    })
    .done(function(data){
        var data    =   JSON.parse(data);
        if(data.error == 0){                    
            $('#contCardMotivoTrunco').html(data.motivoTrunco);
            $('#modal-mottrunco').modal('toggle');  

        }else if(data.error == 1){                  
            mostrarNotificacion('error','Error ',data.msj);
        }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
         mostrarNotificacion('error','Error mostrar el log trunco',errorThrown+ '. Estado: '+textStatus);
      })
      .always(function() {
     
    });

}

function closeMotivoTrunco(){              
    $('#modal-mottrunco').modal('toggle');
    $('#modal-large').css('overflow-y', 'scroll');          
}

    /********************************************************************************************************/


function zipItemPlan(btn) {
var itemPlan = btn.data('itemplan');
if(itemPlan == null || itemPlan == '') {
return;
}

$.ajax({
type : 'POST',
url  : 'zipItemPlan',
data : { itemPlan : itemPlan }
}).done(function(data){
try {
    data = JSON.parse(data);
    if(data.error == 0) {
        var url= data.directorioZip; 
        if(url != null) {
            window.open(url, 'Download');
        } else {
            alert('No tiene evidencias');
        }   
        // mostrarNotificacion('success', 'descarga realizada', 'correcto');
    } else {
        // mostrarNotificacion('error', 'descarga no realizada', 'error');            
        alert('error al descargar');
    }
} catch(err) {
    alert(err.message);
}
});
}

function filtrarTablaCotizacion() {
    var subProy   = $.trim($('#selectSubProy').val());
    var eecc      = $.trim($('#selectEECC').val());
    var zonal     = $.trim($('#selectZonal').val());
    var item      = $.trim($('#selectHasItemPlan').val());
    var mes       = $.trim($('#selectMesEjec').val());
    var area      = $.trim($('#selectArea option:selected').val());
    var estado    = $.trim($('#selectEstado option:selected').val());
    var idUsuario = $.trim($('#selectUsuario option:selected').val());

    $.ajax({
        type: 'POST',
        'url': 'filtrarTablaCotizacion',
        data: {
            subProy   : subProy,
            eecc      : eecc,
            zonal     : zonal,
            item      : item,
            mes       : mes,
            area      : area,
            estado    : estado,
            idUsuario : idUsuario
        },
        'async': false
    })
        .done(function (data) {
            var data = JSON.parse(data);
            if (data.error == 0) {
                $('#contTablaCotizacion').html(data.tablaAsigGrafo)
                initDataTable('#data-table');

            } else if (data.error == 1) {

                mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
            }
        });
}