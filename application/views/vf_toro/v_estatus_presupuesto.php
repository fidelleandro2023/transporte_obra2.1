<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
<style type="text/css">
.select2 {
	width: 350px !important
}

.dt-buttons {
	display: block !important;
	margin-right: 20px;
}

.table td, .table th {
	padding: 0.8rem 1.5rem !important;
}

th, td {
	white-space: nowrap;
}
</style>
<section class="content content--full">
	<div class="content__inner">
		<div class="card" style="width: 105%">
			<div class="card-block">
				<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-default card-view">         
						
						
							<div class="panel-wrapper">
								<div class="panel-body">
								    <div class="pull-left">
<div class="form-group form-group--select" style="float:left;width:400px">
                                        <div class="">
                                            FASE <br>
                                            <select id="faseFiltro" class="form-control select2" name="faseFiltro" onchange="changeFase()">
											<option selected value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                            </select>
                                        </div>
                                    </div>

                  </div>
									<div class="table-wrap">
										
										<div class="table-responsive">

                        <?php echo $tabla;?>

                    </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
				</div>
		</div>
	</div>
</section>
</main>
<script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script type="text/javascript">

   
 
redraw();


function drawLineChart(cate, serie){

	Highcharts.chart('container', {
	    chart: {
	        type: 'line'
	    },
	    title: {
	        text: 'Tendencia Presupuesto'
	    },
	    subtitle: {
	        text: ''
	    },
	    xAxis: {
	        categories: cate
	    },
	    yAxis: {
	        title: {
	            text: 'DISPONIBLE EN %'
	        }
	    },
	    plotOptions: {
	        line: {
	            dataLabels: {
	                enabled: true,
	                format: '{y}%'
	            },
	            enableMouseTracking: false
	        }
	    },
	    series: serie
	});
}

function redraw(){
	
    $.ajax({
        url : 'drawLine',
        type : 'post'
    }).done(function(data){ 
    	var myObj = JSON.parse(data);
    	//console.log(myObj);
    	drawLineChart(myObj.categorias, myObj.serie);
    });
}

function changeFase(){
	console.log(fase);
	var fase = $('#faseFiltro').val();	
	$.ajax({
    	type	:	'POST',
    	'url'	:	'filtrarFasePre',
    	data	:	{fase  :	fase},
    	'async'	:	false
    })
    .done(function(data){
    	var data	=	JSON.parse(data);
    	if(data.error == 0){           	    	

        	//tabla          	    	   
    		$(".table-responsive").html(data.tabla);
    		$("#tablaEstatus").DataTable(   				
    	    		{ "ordering": false,
        	    		dom: 'Bfrtip',buttons:[{extend:'excelHtml5'}],
        	    		pageLength:10,lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],
        	    		language:{sProcessing:"Procesando...",
            	    		sLengthMenu:"Mostrar _MENU_ registros",
            	    		sZeroRecords:"No se encontraron resultados",
            	    		sEmptyTable:"Ning\u00fan dato disponible en esta tabla",
            	    		sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            	    		sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",
            	    		sInfoFiltered:"(filtrado de un total de _MAX_ registros)",
            	    		sInfoPostFix:"",
    			sSearch:"Buscar:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Cargando...",
    			oPaginate:{sFirst:"Primero",sLast:"\u00daltimo",sNext:"Siguiente",sPrevious:"Anterior"}}})	


    			$.ajax({
    		        url : 'drawLineFil',
    		        type : 'post',
    		        data	:	{fase  :	fase}
    		    }).done(function(data){ 
    		    	var myObj = JSON.parse(data);
    		    	drawLineChart(myObj.categorias, myObj.serie);
    		    });
    		
		}else if(data.error == 1){
			
			mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
		}
	  });
	
	
}

</script>


</body>

</html>