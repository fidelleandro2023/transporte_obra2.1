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
        'url': 'filtrarTablaCotizacionTransp',
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