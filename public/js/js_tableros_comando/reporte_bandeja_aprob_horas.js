  function openModalPieDetalle(component){
	  
	  var estado = $(component).attr('data-estado');           
      var rango  = $(component).attr('data-range');
     
 	    $.ajax({
 	    	type	:	'POST',
 	    	url     : "getDetRepBa",
		    data	: { 'estado'  :   estado,
	  			    	'rango'    	:   rango},
	       'async'	:	false
 	    })
 	    .done(function(data){             	    
 	    	var data = JSON.parse(data);   					
		    	if(data.error == 0){
		    		//$('#contTablaDetalle').html(data.tablaDetalleLog);
		    		console.log(data.dataPie);
		    		var datosPie = JSON.parse(data.dataPie);
		    		initGraphPie(datosPie, estado, rango);    		
		    		$('#divContenido').show();
		    		$('#contTablaDetalle').html('');
		    		//$('#modalPieBa').modal('toggle');      				
		    	}else if(data.error == 1){   				    	
					mostrarNotificacion('error','Error',data.msj);
				}
 		  })
 		  .fail(function(jqXHR, textStatus, errorThrown) {
 		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
 		  })
 		  .always(function() {
 	  	 
 		});  
  }
  
        function initGraphPie(datosPie, estado, rango){
        	$('#container').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'PROYECTOS'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Total:{point.y}</br>'
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
                                	  $.ajax({
                                          url:'getDetByProEstaRango',
                                          data:{'estado'	:	estado,
 	                                        	'rango'		:	rango,
                                         	    'proyecto': this.name},
                                          type:'post'
                                      }).done(function(data){
                                     	 var data	=	JSON.parse(data); 
                                          $('#contTablaDetalle').html(data.tablaBADet);
                         	    	     initDataTable('#data-table');
                                      });
                                  }
                              }
                      }
                }]
            });
        }
        
        $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Total 0 OS'
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
                    }                    
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: [{
                    name: 'Porcentaje',
                    y: 1.0753,
                    name: 'Porcentaje2',
                    y: 1.0753
                }]
            }]
        });