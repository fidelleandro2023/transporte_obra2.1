function exportDetalleReporte(component){
            	var tipo       = $(component).attr('data-tipo'); 
            	var region     = $(component).attr('data-region'); 
                $.ajax({
                	type	:	'POST',
            	    	'url'	:	'makeExcelDetalle',
            	    	data	:	{tipo   : tipo,
          	    		             region :   region},
            	    	'async'	:	false
                }).done(function (data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        location.href = data.rutaExcel;
                    }else{
                        mostrarNotificacion('warning', 'Aviso', data.msj);
                    }
                })            
            }

            function getTerminados(){            
	        	 $.ajax({
				        url: "getDetTermSise",
			            type: 'POST'
				  	})
					  .done(function(data) {
	      	        var data	=	JSON.parse(data);
	      	    	if(data.error == 0){	      	    		
                         $('#contTablaTerminados').html(data.tablaDetItemplan);       
                         var datosPie = JSON.parse(data.dataPie);                  
                         initGraphPie(datosPie, data.totalObras);
                         modal('modalDetTerminados');
	      	    	}else if(data.error == 1){     				
	      				mostrarNotificacion('error','ERROR',data.msj);
	      			}
	  		  });  
            }
			
			function getTerminados2(){            
	        	 $.ajax({
				        url: "getDetTermSise",
			            type: 'POST'
				  	})
					  .done(function(data) {
	      	        var data	=	JSON.parse(data);
	      	    	if(data.error == 0){	      	    		
                         $('#contTablaTerminados2').html(data.tablaDetItemplan);       
                         var datosPie = JSON.parse(data.dataPie);                  
                         initGraphPie2(datosPie, data.totalObras);
                         modal('modalDetTerminados2');
	      	    	}else if(data.error == 1){     				
	      				mostrarNotificacion('error','ERROR',data.msj);
	      			}
	  		  });  
            }

            function initGraphPie(datosPie, totalObras){
            	$('#container').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'PENDIENTE DE CERTIFICACION:'+totalObras
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            },
                            size : '100%'
                        }
                    },
                    series: [{
                        name: 'Porcentaje',
                        colorByPoint: true,
                        data: datosPie,
                        point:{
                            events:{
                                      click: function (event) {
                                          console.log(this.name);
                                          var situacion     = this.name; 
                                          $.ajax({
                                          	type	:	'POST',
                                      	    	'url'	:	'makeExcelDetallePie',
                                      	    	data	:	{situacion   : situacion},
                                      	    	'async'	:	false
                                          }).done(function (data) {
                                              var data = JSON.parse(data);
                                              if (data.error == 0) {
                                                  location.href = data.rutaExcel;
                                              }else{
                                                  mostrarNotificacion('warning', 'Aviso', data.msj);
                                              }
                                          })      
                                      }
                                  }
                          }
                    }]
                });
            }
			
			function initGraphPie2(datosPie, totalObras){
            	$('#container2').highcharts({
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'PENDIENTE DE CERTIFICACION:'+totalObras
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            },
                            size : '100%'
                        }
                    },
                    series: [{
                        name: 'Porcentaje',
                        colorByPoint: true,
                        data: datosPie,
                        point:{
                            events:{
                                      click: function (event) {
                                          console.log(this.name);
                                          var situacion     = this.name; 
                                          $.ajax({
                                          	type	:	'POST',
                                      	    	'url'	:	'makeExcelDetallePie',
                                      	    	data	:	{situacion   : situacion},
                                      	    	'async'	:	false
                                          }).done(function (data) {
                                              var data = JSON.parse(data);
                                              if (data.error == 0) {
                                                  location.href = data.rutaExcel;
                                              }else{
                                                  mostrarNotificacion('warning', 'Aviso', data.msj);
                                              }
                                          })      
                                      }
                                  }
                          }
                    }]
                });
            }
			
            function exportDetalleReportePdtCert(component){
            	var tipo       = $(component).attr('data-tipo'); 
            	//var region     = $(component).attr('data-region'); 
                $.ajax({
                	type	:	'POST',
            	    	'url'	:	'makeExcelDetalleTerm',
            	    	data	:	{tipo   : tipo},
            	    	'async'	:	false
                }).done(function (data) {
                    var data = JSON.parse(data);
                    if (data.error == 0) {
                        location.href = data.rutaExcel;
                    }else{
                        mostrarNotificacion('warning', 'Aviso', data.msj);
                    }
                })            
            }
            