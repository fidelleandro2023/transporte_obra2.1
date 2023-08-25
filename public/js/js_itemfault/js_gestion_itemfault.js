function cargarDiseno(itemplan) {
    //$("#divModuloLoad").load("ItemfaultDiseno?itemplan=" + itemplan);
    $.ajax({
        type : 'POST',
        url  : 'getDataDisenoItemfault',
        data : { itemplan : itemplan }
    }).done(function(data){
        data = JSON.parse(data);

        $("#divModuloLoad").html(data.tablaDiseno);
    });
}

var itemPlanAnterior = null;
$("body").on("click",".ver_ptr",function(){
    $this=$(this);
    var id = $(this).attr('data-idrow');
    var idEstacion = $(this).attr('data-estacion');
    $('#'+id).css('background-color', 'yellow');

    if(itemPlanAnterior!=null && itemPlanAnterior!=id) {
        $('#'+itemPlanAnterior).css('background-color', 'white');  
    }     
    itemPlanAnterior = id;
    $.fancybox({
        height:"100%",href:"detalleItemfault?item="+$(this).text()+"&from=2&estacion="+idEstacion,type:"iframe",width:"100%"
    });
    return!1
});

var itemfaultGlobal  = null;
var idEstacionGlobal = null;
function abrirModalEjecutarDis(component) {
    itemfaultGlobal  = $(component).attr('data-item');
    idEstacionGlobal = $(component).attr('data-id_estacion');

    modal('modalEditEntidadesEjec');
}

function habilitarAceptar2() {
	var comprobar = $('#fileExpedienteDiseno').val().length;
    if (comprobar > 0) {
        var file = $('#fileExpedienteDiseno').val()
        var ext = file.substring(file.lastIndexOf("."));
        if (ext == ".zip" || ext == ".rar") {
        	var file_size = $('#fileExpedienteDiseno')[0].files[0].size;
        	if(file_size>52000000){//pesmo minimo 3mb
        		alert("Archivo no puede ser mayor a 50MB");   
        		$("#fileExpedienteDiseno").val(null);
        		$('#btnAceptarEnt').attr("disabled", true);
        		return false;
        	}else{
           	 	$('#btnAceptarEnt').attr("disabled", false);
        	}
        } else {
        	alert('Formato de archivo no valido. (Formatos Validos:.rar o .zip)');
        	$("#fileExpedienteDiseno").val(null);
        	$('#btnAceptarEnt').attr("disabled", true);
        	return false;
        }
    }else{
    	$('#btnAceptarEnt').attr("disabled", true);
    	alert('Debe subir un archivo valido.');
    	return false;
    }
}

$('#formDiseno').on('submit', function(e) {
    e.preventDefault();
    swal({
        title: 'Esta seguro de Ejecutar el Dise&#241o?',
        text: 'Asegurese de validar la informacion!',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, Ejecutar el Dise&#241o!',
        cancelButtonClass: 'btn btn-secondary'
    }).then(function () {            
        var input2File = document.getElementById('fileExpedienteDiseno');
        var comentario = $('#textareaComentario').val();
        var file2 = input2File.files[0];
                    
        var form_data2 = new FormData();  
        form_data2.append('archivoExpediente', file2);
        form_data2.append('itemfault', itemfaultGlobal);
        form_data2.append('idEstacion', idEstacionGlobal);
        form_data2.append('comentario', comentario);
        $.ajax({
            type: 'POST',
            url: 'ejecutarDisenoItemfault',
            async : false,
            data : form_data2,
            cache: false,
            contentType: false,
            processData: false
        }).done(function (data) {
            var data = JSON.parse(data);
            if (data.error == 0) {
                swal({
                    title: 'Ejecucion exitosa',
                    text: data.msj,
                    type: 'success',
                    showCancelButton: false,                    	            
                    allowOutsideClick: false
                }).then(function(){
                    window.top.close();parent.location.reload();
                });
            } else if (data.error == 1) {
                mostrarNotificacion('error', 'Error al liquidar el diseño!', data.msj);
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
        });
    });		
});

/**czavalacast **/
function cargarEnAprobacion(itemplan) {
    //$("#divModuloLoad").load("ItemfaultDiseno?itemplan=" + itemplan);
    $.ajax({
        type : 'POST',
        url  : 'getDataEnAprobacionItemfault',
        data : { itemplan : itemplan }
    }).done(function(data){
        data = JSON.parse(data);
        $("#divModuloLoad").html(data.tablaPreAprob);
    });
}

/**********************/
