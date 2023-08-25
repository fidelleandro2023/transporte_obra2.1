<script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script>        
<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url();?>public/demo/js/flot-charts/chart-tooltips.js?v=<?php echo time();?>"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>  
<script src="<?php echo base_url();?>public/js/sinfix.js?v=<?php echo time();?>"></script>

<script type="text/javascript">
    $(document).ready(function(){
    <?php
    if($pagina_s=="porcentaje"){
        if(!$this->session->userdata("zonasSession")){
            $qpagina="porcentaje";
            $cuadrilla="cambiar_cuadrilla";
            $apagina="cambiarcuadrilla";
        }else{
            $qpagina="porcentaje_zonal";
            $cuadrilla="cambiar_cuadrilla_zonal";
            $apagina="cambiarcuadrillaz";
        }
    ?>
    $(".agregar_avance").click(function(){
        $('html, body').animate({ scrollTop: 0 }, 'fast');        
        $.get("ajax", {
            pagina:"<?php echo $qpagina;?>",
            id:"<?php echo $_GET["id"];?>",
            estacion:$(this).attr("id")},
                function(a){
                    $("#cent").html(a);
                }
            ) 
        return false; 
    })
    $("body").on("click",".ejecutarp",function(){
        sid=$(this).attr("id");
    $.post("ajax",{pagina:"ejecutarporcentaje",id:$(".id").val(),id_planobra_actividad:$("#id_planobra_actividad_"+sid).val(),id_subactividad:$("#id_subactividad_"+sid).val(),select_cuadrilla:$(".cuadrilla_"+sid).val(),fporcentaje:$("#fporcentaje_"+sid).val(),conversacion:$("#conversacion_"+sid).val()},function(){$("#myModal").modal();});
    });
    $("body").on("click",".ejecutarpz",function(){
        sid=$(this).attr("id");        
    $.post("ajax",{pagina:"ejecutarporcentajez",id:$(".id").val(),id_planobra_actividad_z:$("#id_planobra_actividad_z_"+sid).val(),id_estacion:$("#id_estacion_"+sid).val(),select_cuadrilla:$(".cuadrilla_"+sid).val(),fporcentaje:$("#fporcentaje_"+sid).val(),conversacion:$("#conversacion_"+sid).val()},function(){$("#myModal").modal();});          
    });
    $("body").on("click","#myModal",function(){location.reload();});
    // $("body").on("change",".<?php echo $cuadrilla;?>",function(){
    //     r=confirm("���Desea Cambiar Cuadrilla?");
    //     if(r==true){
    //         $.post("ajax", { 
    //                          pagina     : "<?php echo $apagina;?>",
    //                          param      : $(this).attr("id"),
    //                          id_usuario : $(this).val(),
    //                          titulo     : $(this).attr("title")
    //                         },
    //         function(r) { 
    //             a=r.split("-"); 
    //             $("#id_planobra_actividad_"+a[2]).val(a[0]);
    //         })    
    //     }
    // })
    <?php }?>
    })  
    </script>