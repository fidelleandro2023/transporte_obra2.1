        
        $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Total '+totalPoGlobal+' PO'
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
                    }                    
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: dataGeneralPie,
                point:{
                    events:{
                              click: function (event) {    
                            	  
                            	  console.log(this.name);
                            	  $.ajax({
                                      url:'getFilProyPie',
                                      data:{'proyecto': this.name},
                                      type:'post'
                                  }).done(function(data){
                                 	 var data	=	JSON.parse(data); 
                                      $('#contTabla').html(data.tablaBADet);
                     	    	     //initDataTable('#data-table');
                                  });
                              }
                          }
                  }
            }]
        });
        
        
        function openModalPieDetallePtr(component){
      	  
      	   var estado = $(component).attr('data-estado');           
           var rango  = $(component).attr('data-range');
           var proyecto  = $(component).attr('data-proyecto');
       	    $.ajax({
       	    	type	:	'POST',
       	    	url     : "getFilTableBA",
      		    data	: { 'estado'  :   estado,
      	  			    	'rango'    	:   rango,
      	  			    	'proyecto'	:	proyecto},
      	       'async'	:	false
       	    })
       	    .done(function(data){             	    
       	    	var data = JSON.parse(data);   					
      		    	if(data.error == 0){
      		    		$('#contTablaSiom').html(data.tablaBAResu);
      		    		initDataTable('#data-table2');
      		    		$('#modalDetallePtr').modal('toggle');      				
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