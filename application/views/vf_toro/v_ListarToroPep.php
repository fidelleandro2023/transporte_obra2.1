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
            <div class="panel-heading toolbar">
                
                  <div class="pull-left">
<div class="form-group form-group--select" style="float:left;width:400px">
                                        <div class="">
                                            SUBPROYECTO <?php echo $filtrar_subproyecto;?>
                                        </div>
                                    </div>

                  </div>
                  <div class="pull-left">
                                <button onclick="addNuevaPep()">nueva pep</button>

                  </div>
                </div> 
                
						
						
							<div class="panel-wrapper">
								<div class="panel-body">
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
			</div>
		</div>
	</div>
</section>
</main>
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        
<script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
<script type="text/javascript">

function filtrarTabla(){
	//console.log('change');
    var subProy = $.trim($('#subproyectoFiltro').val());   	
	console.log(subProy);
	    $.ajax({
	    	type	:	'POST',
	    	'url'	:	'pepToroFil',
	    	data	:	{id_subPro  :	subProy},
	    	'async'	:	false
	    })
	    .done(function(data){
	    	var data	=	JSON.parse(data);
	    	if(data.error == 0){           	    	          	    	   
	    		$(".table-responsive").html(data.tabla);
	    		$("#simpletable").DataTable({dom: 'Bfrtip',buttons:[{extend:'excelHtml5'}],pageLength:10,lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],language:{sProcessing:"Procesando...",sLengthMenu:"Mostrar _MENU_ registros",sZeroRecords:"No se encontraron resultados",sEmptyTable:"Ning\u00fan dato disponible en esta tabla",sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",sInfoFiltered:"(filtrado de un total de _MAX_ registros)",sInfoPostFix:"",
	    			sSearch:"Buscar:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Cargando...",oPaginate:{sFirst:"Primero",sLast:"\u00daltimo",sNext:"Siguiente",sPrevious:"Anterior"}}})	
			}else if(data.error == 1){
				
				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
			}
		  });
	}  
	


function addNuevaPep(){
	$.fancybox({    height:"100%",
        href:"nuevo_detalle_toro",
        type:"iframe",
        width:"75%"
		});
}

function  deletePepToro(component){
	swal({
        title: 'EstÃƒÂ¡ seguro de eliminar  Pep?',
        text: 'Asegurese de validar la informaciÃƒÂ³n seleccionada!',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonClass: 'btn btn-secondary'
    }).then(function(){

    	var id_pt  = $(component).attr('data-id_pt');
    	var id_td  = $(component).attr('data-id_td');
    	var idSub  = $(component).attr('data-idSub');
    	var pep  = $(component).attr('data-pep');
    	
    	
    	
 	    $.ajax({
 	    	type	:	'POST',
 	    	url     : "delPToro",
		    data: {'id_pt'    :   id_pt,
		    	   'id_td'    :   id_td,
		    	   'idSub'    :   idSub,
		    	    'pep'     :   pep},
 	    	'async'	:	false
 	    })
 	    .done(function(data){             	    
 	    	var data = JSON.parse(data);
			
	    	if(data.error == 0){
	    		location.reload();
			
	    	}else if(data.error == 1){
		    	
				alert('Error interno al intentar eliminar Pep, vuelva a intentarlo o comunniquese con el administrador.');
			}
 		  }) 	   
    });            

 
 }
</script>
</body>

</html>