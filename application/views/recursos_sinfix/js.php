<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url();?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url();?>public/dist/js/init.js"></script>
<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>          
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?php echo base_url();?>public/js/sinfix.js?v=<?php echo time();?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&callback=init" async defer></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#contTablaPenTer').css('display', 'block');
  });

<?php
if($pagina=="detalle_obra"){
?>
$(document).ready(function(){$("body").on("click","#localizar",function(){
if(!$("#porcentaje").val()){$('#modal1').modal();return false;}
if(!$("#conversacion").val()){$('#modal2').modal();return false;}
if($("#porcentaje").val()<=0){$('#modal3').modal();return false;}
if(parseInt($("#porcentaje").val())>100){$('#modal4').modal();return false;}
if(parseInt($("#porcentaje").val())<parseInt($("#porcentaje_a").val())){$('#modal5').modal();return false;}

	if(navigator&&navigator.geolocation)navigator.geolocation.getCurrentPosition(geo_success,geo_error);else return error("Permitir GeoLocalizaci\u00f3n."),!1})});
function geo_success(a){$.get("ajax",{pagina:"detalle_obra",coordenadas:a.coords.latitude+","+a.coords.longitude,conversacion:$("#conversacion").val(),id_planobra_actividad:$("#id_planobra_actividad").val(),fporcentaje:$("#porcentaje").val(),id_sub_actividad:$("#id_sub_actividad").val()},function(a){window.location.href=""})}function geo_error(a){1==a.code?alert("El usuario no quiere mostrar su localizaci\u00f3n."):2==a.code?alert("La informaci\u00f3n es innacessible."):3==a.code?alert("La petici\u00f3n ha durado demasiado tiempo."):alert("Se ha producido un error inesperado.")};
<?php
}
if($pagina=="pendiente"){
?>
$(document).ready(function(){
$("body").on("click",".termi_obra",function(){	
$this=$(this);	
if(confirm("Desea Terminar el Itemplan : "+$this.parent().parent().find("td").eq(1).html().trim())==true){
$.post("ejecucion?pagina=terminar&id="+$this.parent().parent().find("td").eq(1).html().trim(),{},function(){window.location.reload()})
}
return false;
})
$("body").on("click",".truncar_obra",function(){	
$this=$(this);	
if(confirm("Desea Truncar el Itemplan : "+$this.parent().parent().find("td").eq(1).html().trim())==true){
$.post("ejecucion?pagina=truncar&id="+$this.parent().parent().find("td").eq(1).html().trim(),{},function(){window.location.reload()})
}
return false;
})

$("body").on("click",".regresar_trunca",function(){
    $this=$(this);	
    if(confirm("Desea Regresar a Obra el Itemplan : "+$this.parent().parent().find("td").eq(1).html().trim())==true) {
        $.post("ejecucion?pagina=regresar_truncar&id="+$this.parent().parent().find("td").eq(1).html().trim(),{},function()
        {
            window.location.reload();
        })
}
    return false;
})	

$("#proyecto").change(function(){
	$.get("ajax",{"pagina":"listar_proyecto",id:$(this).val()},function(e){$("#subproyecto").html(e)});
})
})
<?php	
}
if($pagina=="obra_terminar"){
?>
$("body").on("click","#preliquidar",function(){
id=$(this).attr("title");    
$.post("ajax",{pagina:"preliquidar",sid:id},function(){
parent.$.fancybox.close();parent.location.reload();     
})
}) 
<?php
	}
if($pagina=="creartoro"||$pagina=="editartoro"){
?>
$("#cantidad").keyup(function(){
$("#total").val(parseFloat($("#cantidad").val())*parseFloat($("#precio").val()));
})
$("#precio").keyup(function(){
$("#total").val(parseFloat($("#cantidad").val())*parseFloat($("#precio").val()));
})
<?php
}
?>
</script>

