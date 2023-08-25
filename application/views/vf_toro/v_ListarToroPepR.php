<style type="text/css">

.dt-buttons{display: block!important;margin-right: 20px;}
.table td, .table th {
    padding: 0.8rem 1.5rem!important;}

th, td {
    white-space: nowrap;
}

@media (min-width: 768px) {
    .modal-xl {
    width: 90%;
    max-width:1200px;
    }
}
</style>
<section class="content content--full">

<div class="card">                                 
<div class="card-block">                      
<div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default card-view">
            
          
<div class="panel-heading toolbar">
                  <div class="pull-left">
<div class="form-group form-group--select" style="float:left;width:400px">
                                        <div class="">
                                            PROYECTO <?php echo $filtrar_proyecto;?>
                                        </div>
                                    </div>                                    
                  </div>
                  <div class="pull-left">
<div class="form-group form-group--select" style="float:left;width:400px">
                                        <div class="">
                                            SUBPROYECTO <?php echo $filtrar_subproyecto;?>
                                        </div>
                                    </div>

                  </div>
                  <div class="pull-left">
<div class="form-group form-group--select" style="float:left;width:400px">
                                        <div class="">
                                            TIPO  PEP <?php echo $tipo_pep;?>
                                        </div>
                                    </div>
                                                                        
                  </div>
                  
                  
                  <div class="clearfix"></div>
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
</div></div>             

                                  
                     


            </section>
        </main>
        
        <div class="modal fade" id="modal_detalle" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">Detalle PTRS</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="tab-container">
                            <div id="contTablaDetPTR" class="table-responsive">     
                            
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script>
        function initTablaReporte(){
        	$("#tableReporte").DataTable({
        		dom: 'Bfrtip',
        		"aaSorting": [],
        		buttons:[{extend:'excelHtml5'}],
        		bPaginate: false,
        		        	language:{sProcessing:"Procesando...",sLengthMenu:"Mostrar _MENU_ registros",sZeroRecords:"No se encontraron resultados",sEmptyTable:"Ning\u00fan dato disponible en esta tabla",sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",sInfoFiltered:"(filtrado de un total de _MAX_ registros)",sInfoPostFix:"",
        		sSearch:"Buscar:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Cargando...",oPaginate:{sFirst:"Primero",sLast:"\u00daltimo",sNext:"Siguiente",sPrevious:"Anterior"}}})	

        	
        }   

        function onchangeProyecto(){
        	var proyecto = $.trim($('#filtrar_proyecto_r').val()); 
         	 $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'changeProy',
       	    	data	:	{proy  : proyecto},
       	    	'async'	:	false
       	    })
   	    .done(function(data){
   	    	var data	=	JSON.parse(data);
   	    	if(data.error == 0){
   	    		$("#filtrar_subproyecto_r").html(data.choice);
    			$('#filtrar_subproyecto_r').val('').trigger('chosen:updated');        	 
    			filtrarTablaReporteR();
   	    	}else if(data.error == 1){
     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
          });

       }
        
        function filtrarTablaReporteR(){        	
        	var proYec = $.trim($('#filtrar_proyecto_r').val());   
        	var subProy = $.trim($('#filtrar_subproyecto_r').val());   
            var tipo = $.trim($('#filtrar_tipo_r').val());                 	 	
        	
        	    $.ajax({
        	    	type	:	'POST',
        	    	'url'	:	'filTabla',
        	    	data	:	{  proy    :  proYec ,
                  	    		  subProy  :	subProy,
                    	    		tipo   :    tipo},
        	    	'async'	:	false
        	    })
        	    .done(function(data){
        	    	var data	=	JSON.parse(data);
        	    	if(data.error == 0){                   	    		   			   
        	    		$(".table-responsive").html(data.tabla);
        	    		initTablaReporte();
        	    		$('[data-toggle="popover"]').popover();
            	    }else if(data.error == 1){
        				
        				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
        			}
        		  });
        }
        
        function getDetallePTRS(component,flgDetalle){
            var idPEB1 = $(component).data('pep1');
            $.ajax({
        	    	type  :	'POST',
        	    	'url' :	'getDetPTRS',
        	    	data  :	{ pep1 : idPEB1,
                              flgDetalle : flgDetalle},
        	    	'async'	:	false
        	    })
        	    .done(function(data){
        	    	var data = JSON.parse(data);
                    console.log(data);
        	    	if(data.error == 0){
                        $('#contTablaDetPTR').html(data.tablaDetallePTRS);	    						
                        initDataTable('#tabla_detalle');
                        modal('modal_detalle');     				
                    }else if(data.error == 1){		    	
                        mostrarNotificacion('error','Aviso',data.msj);
                    }
        		  });
        }
     </script>
    </body>

</html>