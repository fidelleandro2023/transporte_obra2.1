<script type="text/javascript" src="<?php echo base_url();?>public/fancy/source/jquery.fancybox.js"></script>
<script type="text/javascript">
<?php
if($pagina=="pendiente"){
?>
$("body").on("click",".ver_ptr",function(){$this=$(this);$.fancybox({height:"100%",href:"detalleObra?item="+$this.parent().parent().find("td").eq(1).html().trim(),type:"iframe",width:"100%"});return!1});
$("body").on("click",".situacion",function(){$this=$(this);$.fancybox({height:"100%",href:"situacion?id="+$this.parent().parent().find("td").eq(1).html().trim(),type:"iframe",width:"100%"});return!1});
$("body").on("click",".asignar",function(){$this=$(this);$.fancybox({height:"100%",href:"porcentaje?id="+$this.parent().parent().find("td").eq(1).html().trim(),type:"iframe",width:"100%"});return!1});    
$("body").on("click",".terminar",function(){$this=$(this);$.fancybox({height:"100%",href:"obra_terminar?id="+$this.parent().parent().find("td").eq(1).html().trim(),type:"iframe",width:"75%"});return!1});    

<?php	
}
if($pagina=="detalle_obra"){
?>
$(document).ready(function(){$(".mapa").fancybox({width:"100%",height:"100%",autoScale:!1,transitionIn:"none",transitionOut:"none",type:"iframe"})});
<?php }
if($pagina=="toro"){
?>
$("body").on("click",".crear_toro",function(){$this=$(this);$.fancybox({height:"100%",href:"crear_toro",type:"iframe",width:"75%"});return!1});
$("body").on("click",".editar_toro",function(){$this=$(this);$.fancybox({height:"100%",href:"editar_toro?id="+$this.parent().parent().find("td").eq(1).html().trim(),type:"iframe",width:"75%"});return!1}); 

/***************************27-06-2018*********************************/
$("body").on("click",".eliminar_toro",function(){
if(confirm("¿Desea Eliminar El toro? "+$(this).parent().parent().find("td").eq(1).html().trim())){
$.post("listar_toro?pagina=eliminar_toro&id="+$(this).parent().parent().find("td").eq(1).html().trim(),{},function(a){$("#"+a).remove();});
}
return false;
})
/****************************************************************/





<?php
}
if($pagina=="detalle_toro"){
?>
$("body").on("click",".crear_detalle_toro",function(){$this=$(this);$.fancybox({height:"100%",href:"crear_detalle_toro?id=<?php echo $_GET["id"]?>",type:"iframe",width:"75%"});return!1}); 
<?php	
}
?>

</script>