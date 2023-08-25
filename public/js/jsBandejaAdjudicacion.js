function filtrarTabla() {
    var subProy    = $.trim($('#cmbSubProy').val()); 
    var eecc       = $.trim($('#selectEECC').val()); 
    var zonal      = $.trim($('#selectZonal').val()); 
    var itemplan   = $.trim($('#selectItemPlan').val()); 
    var mes        = $.trim($('#selectMesEjec').val());           
    var expediente = $.trim($('#selectExpediente').val());
    var idEstacion = $.trim($('#idEstacion').val());
    var idTipoPlan = $.trim($('#idTipoPlanta').val());
    var jefatura   = $.trim($('#cmbJefatura').val());
    var idProyecto = $.trim($('#cmbProyecto').val());

    $.ajax({
         type	:	'POST',
         'url'	:	'filBanAdju',
         data	:	{ subProy     : subProy,
                      eecc        : eecc,
                      zonal       : zonal,
                      itemplanFil : itemplan,
                      mes         : mes,
                      expediente  : expediente,
                      idEstacion  : idEstacion,
                      idTipoPlan  : idTipoPlan,
                      jefatura    : jefatura,
                      idProyecto  : idProyecto
                     }
     })
     .done(function(data){
         var data = JSON.parse(data);
         if(data.error == 0){           	    	          	    	   
             $('#contTabla').html(data.tablaAsigGrafo)
             initDataTable('#data-table');
         }else if(data.error == 1) {
             mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
         }
       });
  } 

  var itemPlanAnterior   = null;
  var descEstacionGlobal = null;
  function adjudicarDiseno(component){
    var itemplan       = $(component).attr('data-item');
    var has_coax = $(component).attr('data-has_coax');
    var has_fo = $(component).attr('data-has_fo');

    var id = itemplan+""+idEstacionGlobal;
        
    $('#'+id).css('background-color', 'yellow');

    if(itemPlanAnterior!=null && itemPlanAnterior!=id) {
        $('#'+itemPlanAnterior).css('background-color', 'white');  
    } 
    itemPlanAnterior = id;
    $.ajax({
        type    : 'POST',
        url     : "getInfItem",
        data	:	{ item         : itemplan,
			        	has_coax : has_coax,
			        	has_fo   : has_fo },
        'async'	:	false
      })
      .done(function(data) {  
            data = JSON.parse(data);
            if(data.error == 0){
				$('#formAdjudicaItem').bootstrapValidator('resetForm', true); 
                $('#selectSubAdju').val(data.subpro).trigger('change');
                $('#selectEECCDiseno').val(data.empresacolab).trigger('change');
                $('#selectCentral').val(data.central).trigger('change');           	 	    
                $('#tituloModal').html('ITEMPLAN: '+ itemplan);
                $('#btnAdjudica').attr('data-item',itemplan);
                $('#selectUno').prop('checked', true);
				
				$('#formAdjudicaItem').bootstrapValidator('revalidateField', 'selectSubAdju');
                $('#formAdjudicaItem').bootstrapValidator('revalidateField', 'selectEECCDiseno');
                $('#formAdjudicaItem').bootstrapValidator('revalidateField', 'selectCentral');
                if(has_coax == 0){
                	$('#divCoaxial').hide();
                	$('#formAdjudicaItem').data('bootstrapValidator').enableFieldValidators('idFechaPreAtencionCoax', false);    	
                }else if(has_coax == 1){
					$("#idFechaPreAtencionCoax").flatpickr({                		
                		 minDate: data.fec_inicio
                	});
                	$('#divCoaxial').show();
                	$('#formAdjudicaItem').data('bootstrapValidator').enableFieldValidators('idFechaPreAtencionCoax', true);    		
                }
                if(has_fo == 0){
                	$('#divFO').hide();
                	$('#formAdjudicaItem').data('bootstrapValidator').enableFieldValidators('idFechaPreAtencionFo', false);    	

                }else if(has_fo == 1){
					$("#idFechaPreAtencionFo").flatpickr({
                		minDate: data.fec_inicio
                	});
                	$('#divFO').show();
                	$('#formAdjudicaItem').data('bootstrapValidator').enableFieldValidators('idFechaPreAtencionFo', true);    	

                }
                
                $('#modalEjec').modal('toggle');   
            }else if(data.error == 1){
                console.log(data.error);
            }
        })      
}


var toog2=0;
var error=0;
Dropzone.autoDiscover = false;
var itemplan = '';
var myDropzone1 = null;
var myDropzone2 = null;
$("#dropzone4").dropzone({
    url: "insertFile",
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
        
        myDropzone1 = this;
       /* 
        var submitButton = document.querySelector("#btnAdjudica")
        myDropzone = this; 
            
        submitButton.addEventListener("click", function() {		    	
        		myDropzone.processQueue(); 
           }
        );
       */
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
                'url'   : 'comprimirFiles', 
                data :  { from : 1},
                'async' : false,
                type : 'POST'
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

var toog3=0;
var error2=0;
$("#dropzone5").dropzone({
    url: "insertFile2",
    type: 'POST',
    addRemoveLinks: true,
    autoProcessQueue: false,
    parallelUploads: 30,
    maxFilesize: 3,
    dictResponseError: "Ha ocurrido un error en el server",
    
    complete: function(file){
        if(file.status == "success"){
        	error2=0;
        }
    },
    removedfile: function(file, serverFileName){
        var name = file.name;
                   var element;
                   (element = file.previewElement) != null ? 
                   element.parentNode.removeChild(file.previewElement) : 
                   false;
                   toog3=toog3-1;		
    },
    init: function() {
        this.on("error", function(file, message) {
              alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no será tomado en cuenta');
            //	mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
              error2=1;
              // alert(message);
                this.removeFile(file); 
        });
        
        
        myDropzone2 = this;
        /*
        var submitButton = document.querySelector("#btnAdjudica")
        myDropzone = this; 
            
        submitButton.addEventListener("click", function() {		    	
        		myDropzone.processQueue(); 
           }
        );
       */
       var concatEvi = '';
        // You might want to show the submit button only when 
        // files are dropped here:
        this.on("addedfile", function() {		    	
        	toog3=toog3+1;	
          // Show submit button here and/or inform user to click it.
        });
        
        this.on('complete', function () {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {	            	
                if(error2 == 0){
                    console.log(this.getUploadingFiles());
                    // $('#edi-evidencias').modal('toggle');
                }	            
        
            }	        
        });
        
        this.on("queuecomplete", function (file) {
            if(error2 == 0){		    			    	
            var last = concatEvi.substring(0,(concatEvi.length - 1));	   		
            
            $.ajax({
                'url'   : 'comprimirFiles',   
                data :  { from : 2},
                'async' : false,
                type : 'POST'
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


var submitButton = document.querySelector("#btnAdjudica");    
submitButton.addEventListener("click", function() {    	
	myDropzone1.processQueue();
	myDropzone2.processQueue();
   });

function filtrarSubProyecto() {
    var idProyecto = $('#cmbProyecto').val();

    $.ajax({
        type : 'POST',
        url  : 'filtrarSubProyecto',
        data : { idProyecto : idProyecto }
    }).done(function(data){
        var data = JSON.parse(data);
        $('#cmbSubProy').html(data.cmbSubProyecto);
        $.ajax({
            type : 'POST',
            url  : 'filBanAdju',
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
