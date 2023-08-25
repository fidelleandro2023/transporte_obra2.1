function recogeInsertar(){
    var itemTitle          = $('#itemTitle').html();
    var arrayNamesInsert   = $( "input[name*='ptrInsert']" );
    var arrayPTRExistentes = $( "input[name*='ptrEdit']" );
    
    var arrayPTRExistentesTemp = new Array(); // Para limpiar
    var arrayInsert            = new Array(); // recoge lo colocado x usuario
    var arrayValidator         = new Array(); // para verificar si hay duplicados escritos en los inputs
    //var verificador = 0;
    var validador1 = 0;

    // PRIMERA VALIDACION
    for(a=0; a<arrayNamesInsert.length; a++){
        if(arrayNamesInsert[a].value != ''){
            for(b=0; b<arrayPTRExistentes.length; b++){
                if(arrayNamesInsert[a].value == arrayPTRExistentes[b].value){
                    validador1++;
                }
            }
        }else{
            
        }
    }

    if(validador1 == 0){
        for (y=0;y<arrayNamesInsert.length;y++){
            var rowInsert = '';
            if(arrayNamesInsert[y].value != ''){
                rowInsert = arrayNamesInsert[y].value + "/"+ arrayNamesInsert[y].dataset.item;
                arrayValidator.push(rowInsert);
                arrayInsert.push(arrayNamesInsert[y].value + "/"+ arrayNamesInsert[y].dataset.item + "/" + arrayNamesInsert[y].dataset.subproyectoestacion + "/" + arrayNamesInsert[y].dataset.area);
            }
        }
        if( arrayValidator.length == arrayValidator.unique().length){
            // console.log('no hay duplicados');
            var jsonInsert = JSON.stringify(arrayInsert);
            
            $.ajax({
                type    :   'POST',
                'url'   :   'ptrToInsert',
                data    :   {                             
                            jsonNamesInsert : jsonInsert},
                'async' :   false
            }).done(function(data) {
                location.reload();
                if(data.error == 0){           
                    console.log("itemPlan: "+arrayNamesInsert[y].dataset.item);
                }else if(data.error == 1){
                    mostrarNotificacion('error','Hubo un problema.');
                }
              });
        }else{
            alert('Usted no puede ingresar una misma PTR dos veces.');
        }
    }else{
        alert('Usted ha ingresado '+validador1+' PTR ya existente en este ITEMPLAN. Por favor vuelva a intentarlo.');
    }
}

function filtrarTabla() {
   
    var idEstacion = $.trim($('#idEstacion').val());
    var idTipoPlan = $.trim($('#idTipoPlanta').val());
    var jefatura   = $.trim($('#cmbJefatura').val());
    var idProyecto = $.trim($('#cmbProyecto').val()); 
    var subProy    = $.trim($('#cmbSubProy').val());
    var fecha      = $.trim($('#filtrarFecha').val());

    $.ajax({
        type: 'POST',
        'url': 'filBanEjec',
        data: {
	            idEstacion  : idEstacion,
	            idTipoPlan  : idTipoPlan,
	            jefatura    : jefatura,
	            idProyecto  : idProyecto,
	            subProy     : subProy,
	            fecha       : fecha
        },
        'async': false
    })
        .done(function (data) {
            var data = JSON.parse(data);
            if (data.error == 0) {
                $('#contTabla').html(data.tablaAsigGrafo)
                initDataTable('#data-table');
            } else if (data.error == 1) {
                mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
            }
        });
}

function filtrarSubProyecto() {
    var idProyecto = $('#cmbProyecto').val();

    $.ajax({
        type : 'POST',
        url  : 'filtrarSubProyectoEje',
        data : { idProyecto : idProyecto }
    }).done(function(data){
        var data = JSON.parse(data);
        $('#cmbSubProy').html(data.cmbSubProyecto);
        $.ajax({
            type : 'POST',
            url  : 'filBanEjec',
            data : { idProyecto : idProyecto }
        }).done(function(data){
            var data = JSON.parse(data); 
            if(data.error == 0){           	    	          	    	   
                $('#contTabla').html(data.tablaAsigGrafo)
                initDataTable('#data-table');
            }else if(data.error == 1) {
                mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
            }
        });
    });
}

var toog2=0;
var error=0;
Dropzone.autoDiscover = false;
$("#dropzone6").dropzone({
    url: "insertFileEjec",
    type: 'POST',
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 30,
    maxFilesize: 3,
    dictResponseError: "Ha ocurrido un error en el server",
    
    complete: function(file){
        if(file.status == "success"){
            error=0;
        }
    },
    removedfile: function(file, serverFileName){
        var name = file.name;
                   var element;
                   (element = file.previewElement) != null ? 
                   element.parentNode.removeChild(file.previewElement) : 
                   false;
                   toog2=toog2-1;		
    },
    init: function() {
        this.on("error", function(file, message) {
              alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no sera tomado en cuenta');
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
                error=1;
              // alert(message);
                this.removeFile(file); 
        });
            
     
        var submitButton = document.querySelector("#btnAddEvi");
        myDropzone = this; 
            
        submitButton.addEventListener("click", function() {		    	
        		myDropzone.processQueue(); 
           }
        );
      
       var concatEvi = '';
        // You might want to show the submit button only when 
        // files are dropped here:
        this.on("addedfile", function() {		    	
            toog2=toog2+1;	
          // Show submit button here and/or inform user to click it.
        });
        
        this.on('complete', function () {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
                if(error == 0){
                    console.log(this.getUploadingFiles());
                    // $('#edi-evidencias').modal('toggle');
                }	            
        
            }	        
        });
        
        this.on("queuecomplete", function (file) {
            if(error == 0){
            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		
            
            $.ajax({
                'url'   : 'comprimirFilesEjec',                 
                'async' : false,
                contentType: false,
                processData: false,
                type : 'POST'
              }).done(function(data){
            	  //console.log('222222222');
            	  //console.log(data.error);
            	
            	  data = JSON.parse(data);
            	  console.log(data);
                  if (data.error == 0) {
                	  
                       $('#contTabla').html(data.tablaAsigGrafo);			    					
                       initDataTable('#data-table');
                    
                      mostrarNotificacion('success', 'OperaciÃ³n Ã©xitosa.', 'Se registro correcamente!');
                  } else if (data.error == 1) {
                      console.log(data.error);
                  }
            	})
              
              
              this.removeAllFiles(true); 
              mostrarNotificacion('success','Archivo','Se subi&oacute; el archivo correctamente');
              //refreshTablaRuta();
            }
        });		

         this.on("success", function(file, responseText) {//Trae el ID De la imagen insertada
             concatEvi += responseText+'_';		        	
         });
        
      }
});

var componentGlob = null;
var jefaturaGob = null;
var cant_amplificadorGobal = null;
var cant_trobaGobal = null;
var inputGlobal = null;
/*********************nuevo 15082018***************************/
function abrirModalAsignarEntidades(component) {
    var itemplan = $(component).attr('data-item');
    var idEstacion = $(component).attr('data-id_estacion');
    $('#chbxExpediente').prop('checked', false);
    $('#chbxPlanoDiseno').prop('checked', false);

    componentGlob = component;
    arrayEntidades = [];

    $.ajax({
        type: 'POST',
        url: 'getInLic',
        data: {
            itemplan: itemplan,
            idEstacion: idEstacion
        }
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            $('#contAmplificadores').html(data.inputAmTro);
            inputGlobal = data.input;
            arrayEntidades = data.arrayIdEntidades;
            if (arrayEntidades.length > 0) {
                $('#tituloModalEnt').html('EDITAR ENTIDADES');
            } else {
                $('#tituloModalEnt').html('ASIGNAR ENTIDADES');
            }
            $('#formEntidades').html(data.htmlEntidades);
            if (data.jefatura != null) {
                jefaturaGob = data.jefatura;
                if (data.jefatura != 'LIMA') {
                    $('#idPanelPlanoDiseno').css('display', 'none');
                } else {
                    $('#idPanelPlanoDiseno').css('display', 'block');
                }
                modal('modalEditEntidadesEjec');
            }

        } else {
            alert('error Interno intentelo de nuevo.');
        }
    });


}

var arrayEntidades = [];

function agregarEntidades(idEntidad, disabled) {
    var cnt = 0;
    $.each(arrayEntidades, function (index, value) {
        if (value[0] == idEntidad) {
            arrayEntidades.splice(index, 1);
            cnt++;
            return false;
        }
    });

    if (cnt == 0) {
        arrayEntidades.splice(arrayEntidades.length, 0, [idEntidad, disabled]);
    }

   
}



function saveEntidades() {

   // if (arrayEntidades.length == 0) {
   //     mostrarNotificacion('error', 'Debe seleccionar almenos una entidad');
    //    return;
   // }
    modal('modalEditEntidadesEjec');
    aprobarDiseno(componentGlob);

}

function habilitarAceptar2() {
	console.log('here');
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
/*
 * comentado el 30.06.2019 czavalacas, modificacionde carga de archivos
function habilitarAceptar() {
     var checkExp = document.getElementById("chbxExpediente").checked;
    var checkPlanoDise = document.getElementById("chbxPlanoDiseno").checked;
    if (jefaturaGob == 'LIMA') {
        if (checkExp == true && checkPlanoDise == true) {
            $('#btnAceptarEnt').attr("disabled", false);
        } else {
            $('#btnAceptarEnt').attr("disabled", true);
        }
    } else {
        if (checkExp == true) {
            $('#btnAceptarEnt').attr("disabled", false);
        } else {
            $('#btnAceptarEnt').attr("disabled", true);
        }
    }
}
*/


