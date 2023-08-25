var itemplanGlobal = null;
function openModalArchivo(btn) {
    itemplanGlobal = btn.data('itemplan');
    $('#titulo').html('<h3>Itemplan: '+itemplanGlobal+'</h3>');
    $('#formFiles').bootstrapValidator('resetForm', true); 
    modal('modalDataArchivo');
}


$('#formFiles').bootstrapValidator({
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {
        tss: {
            validators: {
                container : '#mensajeTss',
                notEmpty: {
                    message: '<p style="color:red">(*) Debe ingresar TSS.</p>'
                }
            }
        },
        expediente: {
            validators: {
                container : '#mensajeExped',
                notEmpty: {
                    message: '<p style="color:red">(*) Debe ingresar el expediente.</p>'
                }
            }
        },
        comentario:{}     	       
    }
}).on('success.form.bv', function(e) {
    e.preventDefault();       		

    swal({
        title: 'Est&aacute; seguro de realizar esta acci&oacute;n?',
        text: 'Asegurese de que la informacion llenada sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, guardar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){

        
    var $form    = $(e.target),
        formData = new FormData(),
        params   = $form.serializeArray(),
        bv       = $form.data('bootstrapValidator');	 
   
        $.each(params, function(i, val) {
            formData.append(val.name, val.value);
        });

        formData.append('itemplan', itemplanGlobal);

        var input = document.getElementById('tss');
        var fileTss = input.files[0];

        formData.append('fileTss', fileTss);

        var input2 = document.getElementById('expediente');
        var fileExped = input2.files[0];

        formData.append('fileExped', fileExped);

        
        $.ajax({
            data : formData,
            url  : "ingresarArchivosDisenoCv",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
          })
          .done(function(data) {  
                    data = JSON.parse(data);
                if(data.error == 0){
                    var itemplan = data.itemplannuevo;                     
                        swal({
                            title: 'Se ingres&oacute; correctamente',
                            text: itemplan,
                            type: 'success',
                            showCancelButton: false,                    	            
                            allowOutsideClick: false
                        }).then(function(){
                            location.reload();
                        });
                }else if(data.error == 1){
                    mostrarNotificacion('error','Error','No se inserto el Plan de obra');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
              mostrarNotificacion('error','Error','ComunÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â­quese con alguna persona a cargo :(');
            })
            .always(function() {
               
          });
       

    }, function(dismiss) {
        console.log('cancelado');
        // dismiss can be "cancel" | "close" | "outside"
        $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCotizacion');
        //$('#formAddPlanobra').bootstrapValidator('resetForm', true); 
    });


        
});



var idEmpresaColabGlobal = null;
var idJefaturaGlobal     = null;

function filtrarTabla() {
    idEmpresaColabGlobal = $('#cmbEmpresaColab option:selected').val();
    idJefaturaGlobal     = $('#cmbJefatura option:selected').val();


    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaBandejaDisenoCv',
        data : { idEmpresaColab : idEmpresaColabGlobal,
                 idJefatura     : idJefaturaGlobal }
    }).done(function(data){
        data = JSON.parse(data);
        
        if(data.error == 0) {
            $('#contTabla').html(data.tablaBandeja);
            initDataTable('#data-table');
        } else {
            return;
        }
        
    });
}