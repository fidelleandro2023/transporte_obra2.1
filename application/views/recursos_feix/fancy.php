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
<?php
}
if($pagina=="toropep"){
?>
$("body").on("click",".editar_pep",function(){
	$this=$(this);
  //  console.log('val:'+$this.parent().parent().find("td").eq(1).html().trim());
	$.fancybox({height:"100%",href:"editar_detalle_toro?id="+$this.parent().parent().find("td").eq(1).html().trim(),type:"iframe",width:"75%"});return!1});
<?php	
}
?>

</script>