function setQuinceDias() {
    idTipoDiseno = $('#cmbTipoDiseno option:selected').val();
    
    if(idTipoDiseno == 4 || idTipoDiseno == 8) {
        $('#inputDias').val(15);
    } else {
        getDiasMatriz();
    }
	
	if(idTipoDiseno == 8 || idTipoDiseno == 4 || idTipoDiseno == 9) {
		$('#inputCostMo').prop('disabled', false);
		$('#inputCostMo').val(0);
	} else {
		var metTenAereo = $('#inputMetroTenAereo').val();
		var metTenSubt  = $('#inputMetroTenSubt').val();
		var totalMetros = Number(metTenAereo)+Number(metTenSubt);
		console.log("totalMetros: "+totalMetros);
		logicaMayorCincoMil(totalMetros);
	}
	
	getcalculos();
}

function getcalculos(){
    var costoMat   = $('#inputCostoMat').val();
    var costoMo    = $('#inputCostMo').val();
    // var costoDise  = $('#inputCostoDiseno').val();
    var costoExpe  = $('#cmbMontoEIA option:selected').val();
    var costoAdic  = $('#inputCostoAdicZona').val();
    var costoOc    = $('#inputCostoOc').val();

    var inputCostoTotal = Number(costoMat)+Number(costoMo)+Number(costoExpe)+Number(costoAdic)+Number(costoOc);        	
    $('#inputCostoTotal').val(inputCostoTotal.toFixed(2));            
}

function openModalArchivos() {
    $('#formFiles').bootstrapValidator('resetForm', true); 
    modal('modalDataArchivo');
}

function getDataSeiaMtc(flgNodo=null) {console.log("123112");
    var metTenAereo = $('#inputMetroTenAereo').val();
    var metTenSubt  = $('#inputMetroTenSubt').val();

    if(flgPrincipalGlobal == 0) {
        var idCentral = $('#selectCentral  option:selected').val();
        if(flgNodo == 1) {
            getEbcByDistritoByDistrito();
        }
    } else if(flgPrincipalGlobal == 1) {
        var idCentral = $('#selectCentral2  option:selected').val();
        if(flgNodo == 1) {
            getEbcByDistritoByDistrito();
        }
    }
    
    var totalMetros = Number(metTenAereo)+Number(metTenSubt);
    logicaMayorCincoMil(totalMetros);
    if(totalMetros == null || idCentral == null || idCentral == '') {
        return;
    }    

    $.ajax({
        type : 'POST',
        url  : 'getDataSeiaMtc',
        data : { totalMetros : totalMetros,
                 idCentral   : idCentral } 
    }).done(function(data){
        data = JSON.parse(data);
        // $('#selectRequeSeia').val(data.seia).trigger("change");
        // $('#selectRequeAproMmlMtc').val(data.mtc).trigger("change");
        console.log("seia: "+data.seia);
        console.log("mtc: "+data.mtc);
        $('#selectRequeSeia option[value="'+data.seia+'"]').prop("selected", "selected").trigger("change");
        $('#selectRequeAproMmlMtc').val(data.mtc);
        //$('#selectRequeAproMmlMtc option[value="'+data.mtc+'"]').prop("selected", "selected").trigger("change");
        
    });
}

	function logicaMayorCincoMil(totalMetros) {
		if(totalMetros > 5000) {// SI ES MAYOR A 5000
			$('#inputCostMo').prop('disabled', false);
			$('#inputCostMo').val(0);
		} else {
			$('#inputCostMo').prop('disabled', true);
			$('#inputCostMo').val(costoPqtGlobal);
		}
	}

function getDiasMatriz() {
    var seia = $('#selectRequeSeia option:selected').val();
    var mtc  = $('#selectRequeAproMmlMtc').val();
    var inc  = $('#selectRequeAprobINC option:selected').val();
    var metTenAereo = $('#inputMetroTenAereo').val();
    var metTenSubt  = $('#inputMetroTenSubt').val();
    var metOc       = $('#inputMetroCana').val();
    if(flgPrincipalGlobal == 0) {
        var idCentral = $('#selectCentral  option:selected').val();
    } else if(flgPrincipalGlobal == 1) {
        var idCentral = $('#selectCentral2  option:selected').val()
    }

    if(metTenSubt == null || idCentral == '' || idCentral == null || metTenSubt == '' || seia == null || seia == '' || mtc == '' || mtc == null || 
       inc == null || inc == '' || metTenAereo == null || metTenAereo == '') {console.log("eNTRO1");
        $('#inputDias').val("");
        return; 
    }
	console.log(inc);
	console.log(metOc);
	if(inc == 'SI' && Number(metOc) > 0) {
		$('#inputCostoAdicZona').val(25000);
	} else {
		$('#inputCostoAdicZona').val(0);
	}
    
    var totalMetros = Number(metTenAereo)+Number(metTenSubt);
    $.ajax({
        type : 'POST',
        url  : 'getDiasMatriz',
        data : { seia : seia,
                 mtc  : mtc,
                 inc  : inc,
                 totalMetros : totalMetros,
                 idCentral   : idCentral } 
    }).done(function(data){
        data = JSON.parse(data);
        getcalculos();
        idTipoDiseno = $('#cmbTipoDiseno option:selected').val();
        if(idTipoDiseno == 2 && totalMetros == 0) {
            $('#inputDias').val(15);
        } else {
            $('#inputDias').val(data.dia);
        }
    });
}

$('#formAddPlanobra')
.bootstrapValidator({
    //container: '#mensajeForm',
    feedbackIcons: {
        valid      : 'glyphicon glyphicon-ok',
        invalid    : 'glyphicon glyphicon-remove',
        validating : 'glyphicon glyphicon-refresh'
    },
    excluded: ':disabled',
    fields: {
        inputCostMo :       {
                                validators: {
                                                container : '#mensajeCostoMo',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) Debe Tener costo Mo.</p>'
                                                            }
                                            }
                            },
        inputCostoMat :     {
                                validators: {
                                                container : '#mensajeCostoMat',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) Debe Tener costo Mat.</p>'
                                                            }
                                            }
                            },

        inputCostoTotal :   {
                                validators: {
                                                container : '#mensajeCostoTotal',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) Debe Tener costo total.</p>'
                                                            }
                                            }
                            },
        perfil :            {
                                validators: {
                                                container : '#mensajePerfil',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) Debe subir el archivo.</p>'
                                                            }
                                            }
                            },
        sisegoCotizado :    {
                                validators: {
                                                container : '#mensajeCotizacion',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) Debe subir el archivo.</p>'
                                                            }
                                            }
                            },
        rutas:              {
                                validators: {
                                                container : '#mensajeRutas',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) Debe subir el archivo.</p>'
                                                            }
                                            }
                            },
        cmbTipoDiseno   :   {
                                validators: {
                                                container : '#mensajeTipoDiseno',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) Debe Seleccionar.</p>'
                                                            }
                                            }
                            },
        inputDias       :   {
                                validators: {
                                                container : '#mensajeInputDias',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                            }
                                            }
                            }, 
        inputMetroTenAereo  :   {
                                validators: {
                                                container : '#mensajeInputDias',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                            }
                                            }
                            },
        inputMetroTenSubt   :   {
                                validators: {
                                                container : '#mensajeInputDias',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                            }
                                            }
                            },
        selectCentral   :   {
                                validators: {
                                                container : '#mensajeNodoPrincipal',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                            }
                                            }
                            },
        selectRequeAprobINC :   {
                                    validators: {
                                                    container : '#mensajeInputDias',
                                                    notEmpty:  {
                                                                    message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                }
                                                }
                                },
        selectRequeAproMmlMtc :  {
                                    validators: {
                                                    container : '#mensajeMtc',
                                                    notEmpty:  {
                                                                    message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                                }
                                                }
                                },
        selecElegirEbc : 	{
                                validators: {
                                                container : '#mensajeOptionEbc',
                                                notEmpty:  {
                                                                message: '<p style="color:red">(*) campo obligatorio.</p>'
                                                            }
                                            }
                            }							
    }
}).on('success.form.bv', function(e) {
    e.preventDefault();       		

    swal({
        title: 'Est&aacute; seguro de enviar la Cotizacion?',
        text: 'Asegurese de que la informacion llenada sea la correta.',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, guardar los datos!',
        cancelButtonClass: 'btn btn-secondary',
        allowOutsideClick: false
    }).then(function(){
        var $form = $(e.target),
        formData  = new FormData(),
        params    = $form.serializeArray(),
        bv        = $form.data('bootstrapValidator');	 
        var codigo = $('#btnSave').attr('data-cod');
        formData.append('codigo', codigo);
        $.each(params, function(i, val) {
            formData.append(val.name, val.value);
        });
        
        var input = document.getElementById('perfil');
        var filePerfil = input.files[0];
        
        formData.append('filePerfil', filePerfil);
        formData.append('nodoPrincipal', $('#selectCentral  option:selected').text());
        formData.append('nodoRespaldo' , $('#selectCentral2 option:selected').text());
        formData.append('flgPrincipal' , flgPrincipalGlobal)
        formData.append('costoEIA'     , $('#cmbMontoEIA option:selected').val());
        formData.append('costoAdicZon' , $('#inputCostoAdicZona').val());

        formData.append('reqSia'     , $('#selectRequeSeia option:selected').val());
        formData.append('reqMtc'     , $('#selectRequeAproMmlMtc').val());
        formData.append('reqInc'     , $('#selectRequeAprobINC option:selected').val());
        formData.append('codEbc'     , $('#cmbEbc option:selected').val());
        formData.append('flg_ebc'    , $('#selecElegirEbc option:selected').val());
        formData.append('costoOc'    , $('#inputCostoOc').val());
        
        formData.append('costoMoPqt' , $('#inputCostMo').val());
        //formData.append('duracion'     , $('#cmbDuracion option:selected').val());
        formData.append('duracion', $('#inputDias').val());
        var input2 = document.getElementById('sisegoCotizado');
        var fileSisegoCot = input2.files[0];
        
        formData.append('fileSisego', fileSisegoCot);

        var input3 = document.getElementById('rutas');
        var fileRutas = input3.files[0];

        formData.append('fileRutas', fileRutas);
        
        var mtc = $('#selectRequeAproMmlMtc').val();
        if(mtc == null || mtc == '') {
            return;
        }
        var dias = $('#inputDias').val();
        
        if(dias == null || dias == '') {
            $('#mensajeInputDias').html('<p style="color:red">(*) campo obligatorio.</p>');
            return;
        }
        
        $.ajax({
            data: formData,
            url: "sendCotizacionIndividual",
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
          })
          .done(function(data) {  
                data = JSON.parse(data);
                console.log(data.error);
                if(data.error == 0){
                    var codigo = data.codigo;                     
                        swal({
                            title: 'Se envio corecctamente la Cotizacion',
                            text: codigo,
                            type: 'success',
                            showCancelButton: false,                    	            
                            allowOutsideClick: false
                        }).then(function(){
                            window.location.href = "getBandejaCotizacionIndividual";
                        });
                }else if(data.error == 1){
                    mostrarNotificacion('error','Verificar', data.msj);
                }
            });
       

    }, function(dismiss) {
        console.log('cancelado');
        // dismiss can be "cancel" | "close" | "outside"
        $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCotizacion');
        //$('#formAddPlanobra').bootstrapValidator('resetForm', true); 
    });


        
});


function getEbcByDistritoByDistrito() {
    var option = $('#selecElegirEbc option:selected').val();
    if(flgPrincipalGlobal == 0) {
        var idCentral = $('#selectCentral  option:selected').val();
    } else if(flgPrincipalGlobal == 1) {
        var idCentral = $('#selectCentral2  option:selected').val()
    }

    if(idCentral == null || idCentral == '') {
        return;
    }

    if(option == 1) {
        $.ajax({
            type : 'POST',
            url  : 'getEbcByDistritoByDistrito',
            data :  {
                        idCentral : idCentral
                    }
        }).done(function(data){
            data = JSON.parse(data);
            $('#cmbEbc').html(data.cmbEbc);
            $('#contEbcs').css('display', 'block');
            $('#contFacRed').css('display', 'none');
        });
    } else {
        $('#contFacRed').css('display', 'block');
        $('#contEbcs').css('display', 'none');
    }
}