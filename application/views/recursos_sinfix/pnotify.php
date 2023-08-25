<script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$("body").on("click",".agregar_pep",function(){
	id=$(this).attr("id");
$.post("listar_pep",{pagina:"actualizarpep",id_pep:$("#id_pep_"+id).val(),idSubProyecto:$("#subproyecto_"+id).val(),id_tipo_toro:$("#tipo_"+id).val(),id_categoria_toro:$("#categoria_"+id).val()},function(){
	new PNotify({
    	    title: 'Registro Exitoso',
            text: 'Se Guardaron los cambios.',
    	    type:"success",
    	    delay: 2000,
    	    styling: 'bootstrap3'
    	   
    	});
});	
})

})	
</script>