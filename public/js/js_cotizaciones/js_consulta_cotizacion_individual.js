function filtrarTablaConsultaCotizacion() {
    sisego      = $('#sisego').val();
    codigo_form = $('#codigo_form').val();
    var idSubPro    = $.trim($('#selectSubProyecto').val());
    var idSituacion = $.trim($('#selectSituacion').val());
    var idEmpresaColab = $('#cmbEmpresaColab option:selected').val();
    var idJefatura     = $('#cmbJefatura option:selected').val();
    var flgBandConf    = $('#cmbSituacionConf option:selected').val();
	var itemplan       = $('#itemplanFiltro').val();
	
	console.log("ITEMPLAN: "+itemplan);
    $.ajax({
        type : 'POST',
        url  : 'filtrarTablaConsultaCotizacion',
        data : { sisego         : sisego,
                 codigo_form    : codigo_form,
                 idSubPro       : idSubPro,
                 idSituacion    : idSituacion,
                 idEmpresaColab : idEmpresaColab,
                 idJefatura     : idJefatura,
                 flgBandConf    : flgBandConf,
				 itemplan		: itemplan		}
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

function openModalDatosSisegos(btn) {
    var codigo_cot = btn.data('codigo_cotizacion');

    if(codigo_cot == null || codigo_cot == '') {
        return;
    }
    
    $.ajax({
        type    :   'POST',
        'url'   :   'getDataDetalleCotizacionSisego',
        data    :   { codigo_cot : codigo_cot },
        'async' :   false
    }).done(function(data){
        data = JSON.parse(data);
        if(data.error == 0) {
            $('#contInfoDataSisego').html(data.dataInfoSisego);
            modal('modalDatosSisegos');
        } else {
            return;
        }
    });
}

function getOpenModalDocFormCoti(btn) {
    var ubic_perfil = btn.data('ubic_perfil');
    var ubic_sisego = btn.data('ubic_sisego');
    var ubic_rutas  = btn.data('ubic_rutas');
    console.log(ubic_perfil);
    
    if(ubic_perfil == null || ubic_perfil == '') {
        $('#cntMsj').html('<h1>AUN NO SUBEN ARCHIVOS</h1>');
    } else {
        var btnPerfil = '<a href="'+ubic_perfil+'" target="_blank"><button class="btn btn-success">DESCARGAR PERFIL</button></a>';
        var btnSisego = '<a href="'+ubic_sisego+'" target="_blank"><button class="btn btn-success">DESCARGAR SISEGO</button></a>';
        var btnRutas  = '<a href="'+ubic_rutas+'" target="_blank"><button class="btn btn-success">DESCARGAR RUTAS</button></a>';
        
        $('#contPerfil').html(btnPerfil);
        $('#contSisego').html(btnSisego);
        $('#contRutas').html(btnRutas);
    }
    modal('modalDocumentosFom');
}

    function zipArchivosForm(btn) {
        var ubic_perfil = btn.data('ubic_perfil');
        var codigo_cot = btn.data('codigo_cotizacion');

        if(codigo_cot == null || codigo_cot == '') {
            return;
        }
    
        $.ajax({
            type : 'POST',
            url  : 'zipArchivosForm',
            data : { codigo_cot  : codigo_cot}
        }).done(function(data){
        
                data = JSON.parse(data);
                if(data.error == 0) {
                    var url= data.directorioZip; 
                    console.log(data.directorioZip);
                    if(url != null) {
                        window.open(url, 'Download');
                    } else {
                        alert('error');
                    }   
                    // mostrarNotificacion('success', 'descarga realizada', 'correcto');
                } else {
                    // mostrarNotificacion('error', 'descarga no realizada', 'error');            
                    alert('error al descargar');
                }

        });
    }

    function getLogCotizacionInd(btn) {
        var codigoCluster = btn.data('codigo_cotizacion');

        if(codigoCluster == '' || codigoCluster == null) {
            return;
        }

        $.ajax({
            type : 'POST',
            url  : 'getLogCotizacionInd',
            data : { codigo_cluster : codigoCluster }
        }).done(function(data){
            console.log(data);
            data = JSON.parse(data);
            if(data.error == 0){
                $('#contTablaLog').html(data.tablaLog);
                modal('modalLogCotizacion');
            } else {
                return;
            }
        });
    }
	
	var codigoClusterGlbCosto = null;
	var costoTotalCotizacionGlb = null;
	function getEditarCostosCotizacion(btn) {
		codigoClusterGlbCosto = btn.data('codigo_cotizacion');

        if(codigoClusterGlbCosto == '' || codigoClusterGlbCosto == null) {
            return;
        }

        $.ajax({
            type : 'POST',
            url  : 'getEditarCostosCotizacion',
            data : { codigo_cluster : codigoClusterGlbCosto }
        }).done(function(data){
            data = JSON.parse(data);
			console.log(data.error);
            if(data.error == 0){
				//MATERIALES
				$('#costoMat').val(data.dataCoti.costo_materiales);
				$('#costoMatEdif').val(data.dataCoti.costo_mat_edif);
				$('#costoOcEdif').val(data.dataCoti.costo_oc_edif);
				
				//MANO DE OBRA
				var costoOc = (data.dataCoti.costo_oc) ? 0 : data.dataCoti.costo_oc;
				
				$('#costoMo').val(data.dataCoti.costo_mano_obra);
				$('#costoMoDiseno').val(data.dataCoti.costo_diseno);
				$('#costoEia').val(data.dataCoti.costo_expe_seia_cira_pam);
				$('#costoAdicRural').val(data.dataCoti.costo_adicional_rural);
				$('#costoOc').val(costoOc);
				
				$('#totalCoti').html("TOTAL: "+data.dataCoti.costo_total);
				
				costoTotalCotizacionGlb = data.dataCoti.costo_total;
				calcularCostototal();
                modal('modalEditarCostos');
            } else {
                return;
            }
        });
	}
	
	var costoMoGlb = null;
	function calcularCostototal() {
		var costoMat     = $('#costoMat').val();
		var costoMatEdif = $('#costoMatEdif').val();
		var costoMatOc   = $('#costoOcEdif').val();
		
		var costoMo     = $('#costoMo').val();
		var costoDiseno = $('#costoMoDiseno').val();
		var costoEia    = $('#costoEia').val();
		var costoRural  = $('#costoAdicRural').val();
		var costoOcMO   = $('#costoOc').val();
		
		var costoTotalMo  = Number(costoMo)+Number(costoDiseno)+Number(costoEia)+Number(costoRural)+Number(costoOcMO);
		var costoTotalMat = Number(costoMat)+Number(costoMatEdif)+Number(costoMatOc);
		
		$('#totalCalculo').val(costoTotalMo+costoTotalMat);
	}
	
	function actualizarCostosCotiPo() {
		swal({
			title: 'Est&aacute; seguro de actualizar los costos?',
			text: 'Asegurese de que el total sea el mismo al de la cotizaci&oacute;n.',
			type: 'warning',
			showCancelButton: true,
			buttonsStyling: false,
			confirmButtonClass: 'btn btn-primary',
			confirmButtonText: 'Si, actualizar!',
			cancelButtonClass: 'btn btn-secondary',
			allowOutsideClick: false
		}).then(function(){
			if(codigoClusterGlbCosto == '' || codigoClusterGlbCosto == null) {
				return;
			}
			
			var costoMat     = $('#costoMat').val();
			var costoMatEdif = $('#costoMatEdif').val();
			var costoMatOc   = $('#costoOcEdif').val();
			
			var costoMo     = $('#costoMo').val();
			var costoDiseno = $('#costoMoDiseno').val();
			var costoEia    = $('#costoEia').val();
			var costoRural  = $('#costoAdicRural').val();
			var costoOcMO   = $('#costoOc').val();
			
			var costoTotalMo  = Number(costoMo)+Number(costoDiseno)+Number(costoEia)+Number(costoRural)+Number(costoOcMO);
			var costoTotalMat = Number(costoMat)+Number(costoMatEdif)+Number(costoMatOc);
			
			var costo_total   = costoTotalMo+costoTotalMat;
			
			if(Number(costoTotalCotizacionGlb) != Number(costo_total)) {
				mostrarNotificacion('warning', 'La suma total debe ser igual al costo total de la cotizacion.');
				return;
			}
			
			$.ajax({
				type : 'POST',
				url  : 'actualizarCostosCotiPo',
				data : { codigo_cluster : codigoClusterGlbCosto,
						 costoMat       : costoMat,
						 costoMatEdif   : costoMatEdif,
						 costoMatOc     : costoMatOc,
						 costoMo        : costoMo,
						 costoDiseno    : costoDiseno,
						 costoEia       : costoEia,
						 costoRural     : costoRural,
						 costoOcMO      : costoOcMO,
						 costoTotalMo   : costoTotalMo,
						 costoTotalMat  : costoTotalMat }
			}).done(function(data){
				data = JSON.parse(data);
				
				if(data.error == 0) {
					modal('modalEditarCostos');
					swal({
                            title: 'Se ingresaron los costos correctamente',
                            text: codigoClusterGlbCosto,
                            type: 'success',
                            showCancelButton: false,                    	            
                            allowOutsideClick: false
                        }).then(function(){
                            window.location.href = "getConsultaCotizacionInd";
                        });
				} else {
					mostrarNotificacion('warning', data.msj);
				}
			});
		}, function(dismiss) {
			console.log('cancelado');
			// dismiss can be "cancel" | "close" | "outside"
			$('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCotizacion');
			//$('#formAddPlanobra').bootstrapValidator('resetForm', true); 
		});
	}