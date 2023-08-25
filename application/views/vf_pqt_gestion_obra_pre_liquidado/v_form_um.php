<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=windows-1252">

</head>

<body>
    <main class="main">
        <section class="content content--full">
            <h2>Pre Liquidacion - DJ de UM</h2>
            <div id="modalFormUM">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="forRegistrarFormUM" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row"> 
                           		<div class="form-group col-sm-3	 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">ITEMPLAN</label>  
                                    <input style="height: 33px;" disabled id="txtItemplan" name="txtItemplan" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CLIENTE</label>  
                                    <input style="height: 33px;" id="txtCliente" name="txtCliente" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-5 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">DIRECCION</label>  
                                    <input style="height: 33px;" id="txtDireccion" name="txtDireccion" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">FIBRAS EN CLIENTE</label>  
                                    <input style="height: 33px;" id="txtFibrasCliente" name="txtFibrasCliente" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">FECHA DE TERMINO</label>  
                                    <input style="height: 33px;" id="txtFecTermino" name="txtFecTermino" type="text" class="form-control date-picker">                                                                      
                                </div>  
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">NODO</label>  
                                    <input style="height: 33px;" id="txtNodo" name="txtNodo" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">UBICACION</label>  
                                    <input style="height: 33px;" id="txtUbicacion" name="txtUbicacion" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">NUM. ODF</label>  
                                    <input style="height: 33px;" id="txtNumODF" name="txtNumODF" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">BANDEJA DE CONECTORES</label>  
                                    <input style="height: 33px;" id="txtBanConectores" name="txtBanConectores" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">FIBRAS</label>  
                                    <input style="height: 33px;" id="txtFibras" name="txtFibras" type="text" class="form-control">                                                                      
                                </div>   
                                <div class="form-group col-sm-12 col-xs-12">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PRUEBAS REFLECTOMETRICAS(PDF)</label>
                                    <input id="filePruebasRefle" name="filePruebasRefle" type="file" accept="application/pdf">
                                </div>  
                                 <div class="form-group col-sm-12 col-xs-12">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">PERFIL(PDF)</label>  
                                    <input id="filePerfil" name="filePerfil" type="file" accept="application/pdf">
                                </div>
                           </div>
                               <div class="row">     
                                <div id="mensajeForm"></div>  
                                <div class="form-group" style="text-align: right;">
                                    <div class="col-sm-12">
                                        <button id="btnRegFormUM" type="submit" class="btn btn-primary">Guardar los cambios</button>                                    
                                    </div>
                                </div>                            
                            </div>
                        </form>    
                    </div>
            	</div>
            </div>
    	</div>
        </section>
    </main>
<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url();?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/init.js"></script>
<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script> 
<script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
    <script type="text/javascript">
$(function() {
	 var itemplan = <?php echo '"'.$itemPlan.'"' ?>;
	 console.log(itemplan);
     $('#btnRegFormUM').attr('data-itemplan', itemplan);
     $('#txtItemplan').val(itemplan);
});
                                            
$('#forRegistrarFormUM')
.bootstrapValidator({
container: '#mensajeForm',
feedbackIcons: {
    valid: 'glyphicon glyphicon-ok',
    invalid: 'glyphicon glyphicon-remove',
    validating: 'glyphicon glyphicon-refresh'
},
excluded: ':disabled',
fields: {
    txtCliente: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe ingresar el Cliente.</p>'
            }
        }
    },
    txtDireccion: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe ingresar Direccion.</p>'
            }
        }
    },
    txtFibrasCliente: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe ingresar Fibras en Cliente.</p>'
            }
        }
    },
    txtFecTermino: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe seleccionar una fecha de Termino.</p>'
            }
        }
    },
    txtNodo: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe ingresar el Nodo.</p>'
            }
        }
    },
    txtUbicacion: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe ingresar la Ubicacion.</p>'
            }
        }
    },
    txtNumODF: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe ingresar Numero de ODF.</p>'
            }
        }
    },
    txtBanConectores: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe ingresar Bandeja de Conectores.</p>'
            }
        }
    },
    txtFibras: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe ingresar Fibras.</p>'
            }
        }
    },
    filePruebasRefle: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe subir Docuemnto PRUEBAS REFLECTOMETRICAS.</p>'
            }
        }
    },
    filePerfil: {
        validators: {
            notEmpty: {
                message: '<p style="color:red">(*) Debe subir Docuemnto PERFIL.</p>'
            }
        }
    }
}
}).on('success.form.bv', function(e) {
e.preventDefault();


var $form    = $(e.target),
    formData = new FormData(),
    params   = $form.serializeArray(),
    bv       = $form.data('bootstrapValidator');
    
    $.each(params, function(i, val) {
        formData.append(val.name, val.value);
    });
    
    var itemplan	=	$('#btnRegFormUM').attr('data-itemplan');
    formData.append('itemplan', itemplan);

    var input1File = document.getElementById('filePruebasRefle');
    var file1 = input1File.files[0];
    formData.append('filePruebas', file1);

    var input2File = document.getElementById('filePerfil');
    var file2 = input2File.files[0];
    formData.append('filePerfil', file2);
    
    swal({
        title: 'Est&aacute seguro registrar la DJ de UM?',
        text: 'Asegurese de que la informacion llenada sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, guardar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
        console.log('fuck you');
        
        $.ajax({
            data: formData,
            url: "saveFormUM",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
          })
          .done(function(data) {
             var data	=	JSON.parse(data);
             if(data.error == 0){   
                 console.log('YES!!');
                 $('#modalFormUM').modal('toggle');
                 swal({
              	   title: 'Se registro correctamente!',
                     text: 'Asegurese de validar la informacion!',
                     type: 'success',
                     buttonsStyling: false,
                     confirmButtonClass: 'btn btn-primary',
                     confirmButtonText: 'OK!',
                     allowOutsideClick: false
                 }).then(function(){
                	 window.top.close();
                 }, function(dismiss) {
              	   location.reload();
                 });
             }else if(data.error == 1){     				
                 mostrarNotificacion('error','Error, refresque la pagina y vuelva a intentarlo!');
             }
          });
    }, function(dismiss) {
        // dismiss can be "cancel" | "close" | "outside"
            $('#forRegistrarFormUM').bootstrapValidator('resetForm', true); 
    });
});
</script>
</body>
</html>