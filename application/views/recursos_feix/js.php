<script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
<script src="<?php echo base_url();?>public/js/app.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
<?php
if(@$_GET["pagina"]=="carga_masiva"){
?>
$("#modalRegistrarEstacion").modal();
<?php
}
if($pagina=="toro"){
?>
	$("#filtrar_proyecto").change(function(){
		$(".table-responsive").html("");
		$.post("listar_toro",{pagina:"filtrar_proyecto",idProyecto:$(this).val()},function(r){
			$(".table-responsive").html(r);
			$("#simpletable").treetable({ expandable: true });
		})
	})
	$("#boton_buscar").click(function(){
		$(".table-responsive").html("");
		$.post("listar_toro",{pagina:"buscar_proyecto",id_toro:$("#input_buscar").val()},function(r){
			$(".table-responsive").html(r);
			$("#simpletable").treetable({ expandable: true });
		})
	})
<?php	
}
if($pagina=="reporte_toro"){
?>

   
	/*
 $("body").on("change","#filtrar_proyecto",function(){
		if($(this).val()!=''){
    		$.post("reporte_toro",{
        		pagina:"filtrar_proyecto",
        		id:$(this).val()
        		},function(a){
    			//$("#filtrar_subproyecto").html("");
    			$("#filtrar_subproyecto").append(a);
    		})
    		
    		$.post("reporte_toro",{
        		pagina:"filtrar_proyecto_tabla",
        		id: $(this).val(),
        		tipo: $("#filtrar_tipo").val()
        		},function(a){
    			//$(".table-responsive").html("");
    			$(".table-responsive").html(a);
    			initTablaReporte();
    		})
    		
		}else{
			if($("#filtrar_tipo").val()==""){
    			$("#filtrar_subproyecto").html("");
    			$.post("reporte_toro",{pagina:"filtrar_proyecto_tabla",id:""},
	    			function(a){
        			//$(".table-responsive").html("");
        			$(".table-responsive").html(a);
        			initTablaReporte();
    		    })	
			}else{
    			$.post("reporte_toro",{
        			pagina:"filtrar_tipo_tabla",
        			id:$("#filtrar_tipo").val(),
        			idProyecto:"",
        			idSubProyecto:""},
        			function(a){
        			//$(".table-responsive").html("");
        			$(".table-responsive").html(a);
        			initTablaReporte();
    			})
			}

		}
	})
	
	$("body").on("change","#filtrar_subproyecto",function(){
		if($(this).val()!=''){
		
		$.post("reporte_toro",{pagina:"filtrar_subproyecto_tabla",id:$(this).val()},function(a){
			$(".table-responsive").html("");
			$(".table-responsive").html(a);
			initTablaReporte();
		})
		}else{
			if($("#filtrar_proyecto").val()!=""){
				$.post("reporte_toro",{pagina:"filtrar_proyecto_tabla",id:$("#filtrar_proyecto").val()},function(a){
			$(".table-responsive").html("");
			$(".table-responsive").html(a);
			initTablaReporte();
		})
			}else{
			$.post("reporte_toro",{pagina:"filtrar_proyecto_tabla",id:""},function(a){
			$(".table-responsive").html("");
			$(".table-responsive").html(a);
			initTablaReporte();
		})	
			}
		}
	})
	
	$("body").on("change","#filtrar_tipo",function(){
		
		if($(this).val()!=""){
        
        		if($("#filtrar_proyecto").val()==""&&$("#filtrar_subproyecto").val()==""){
        		$.post("reporte_toro",{pagina:"filtrar_tipo_tabla",id:$(this).val(),idProyecto:"",idSubProyecto:""},function(a){
        			$(".table-responsive").html("");
        			$(".table-responsive").html(a);
        			initTablaReporte();
        		})
                }
                if($("#filtrar_proyecto").val()!=""&&$("#filtrar_subproyecto").val()==""){
                
                		$.post("reporte_toro",{pagina:"filtrar_tipo_tabla",id:$(this).val(),idProyecto:$("#filtrar_proyecto").val(),idSubProyecto:""},function(a){
                			$(".table-responsive").html("");
                			$(".table-responsive").html(a);
                			initTablaReporte();
                		})
                }
                if($("#filtrar_proyecto").val()==""&&$("#filtrar_subproyecto").val()!=""){
                		$.post("reporte_toro",{pagina:"filtrar_tipo_tabla",id:$(this).val(),idProyecto:"",idSubProyecto:$("#filtrar_subproyecto").val()},function(a){
                			$(".table-responsive").html("");
                			$(".table-responsive").html(a);
                			initTablaReporte();
                		})
                	}
        }else{console.log('weee ');
        	if($("#filtrar_proyecto").val()==""&&$("#filtrar_subproyecto").val()==""){
        		$.post("reporte_toro",{pagina:"filtrar_proyecto_tabla",id:""},function(a){
        			//$(".table-responsive").html("");
        			$(".table-responsive").html(a);console.log('1');
        			initTablaReporte();
        		})
        	}
        	if($("#filtrar_subproyecto").val()!=""){
                $.post("reporte_toro",{pagina:"filtrar_subproyecto_tabla",id:$("#filtrar_subproyecto").val()},function(a){
                			//$(".table-responsive").html("");
                			$(".table-responsive").html(a);console.log('2');
                			initTablaReporte();
        		})	
            }else{
                if($("#filtrar_proyecto").val()!=""){
                		$.post("reporte_toro",{pagina:"filtrar_proyecto_tabla",id:$("#filtrar_proyecto").val()},function(a){
                			//$(".table-responsive").html("");
                			$(".table-responsive").html(a);console.log('3');
                			initTablaReporte();
                		})}
            
            }
        }
	})
	*/
function init_datatable(){
$("#simpletable").DataTable({dom: 'Bfrtip',"aaSorting": [],buttons:[{extend:'excelHtml5'}],pageLength:5,lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],language:{sProcessing:"Procesando...",sLengthMenu:"Mostrar _MENU_ registros",sZeroRecords:"No se encontraron resultados",sEmptyTable:"Ning\u00fan dato disponible en esta tabla",sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",sInfoFiltered:"(filtrado de un total de _MAX_ registros)",sInfoPostFix:"",
sSearch:"Buscar:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Cargando...",oPaginate:{sFirst:"Primero",sLast:"\u00daltimo",sNext:"Siguiente",sPrevious:"Anterior"},oAria:{sSortAscending:": Activar para ordenar la columna de manera ascendente",sSortDescending:": Activar para ordenar la columna de manera descendente"}}})	
}
	

initTablaReporte();
<?php		
}
if($pagina=="estatus"){
?>	
$("#tablaEstatus").DataTable({
	dom: 'Bfrtip',
	"aaSorting": [],
	buttons:[{extend:'excelHtml5'}],
	bPaginate: false,
	        	language:{sProcessing:"Procesando...",sLengthMenu:"Mostrar _MENU_ registros",sZeroRecords:"No se encontraron resultados",sEmptyTable:"Ning\u00fan dato disponible en esta tabla",sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",sInfoFiltered:"(filtrado de un total de _MAX_ registros)",sInfoPostFix:"",
	sSearch:"Buscar:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Cargando...",oPaginate:{sFirst:"Primero",sLast:"\u00daltimo",sNext:"Siguiente",sPrevious:"Anterior"}}})	

<?php		
}
?>	
}) 
</script>