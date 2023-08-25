<style type="text/css">
.link_activo{
color: var(--verde_telefonica);  
}  
</style>
<div class="fixed-sidebar-left">
      <ul class="nav navbar-nav side-nav nicescroll-bar">
       
        <li class="navigation-header">
        <br>
        </li>
<?php
if($this->session->userdata('idPerfilSession')==12){
?>
        <li>
          <a class="active" href="ejecuci??1??70??1??71??1??70??1??76n_cuadrilla"><div class="pull-left"><i class="zmdi zmdi-home mr-20"></i><span class="right-nav-text">Inicio</span></div><div class="clearfix"></div></a>
        </li>
<?php }
if($this->session->userdata('idPerfilSession')!=12){
?>
	<li>
      	<a href="javascript:void(0);" data-toggle="collapse" data-target="#dashboard_dr" <?php if(in_array(@$_GET["pagina"],array("pendiente","preliquidada","liquidada"))){?> class="active" <?php }?>><div class="pull-left"><i class="zmdi zmdi-format-align-left"></i><span class="right-nav-text">Plan Obra</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
      	<ul id="dashboard_dr" class="collapse collapse-level-1 <?php if(in_array(@$_GET["pagina"],array("pendiente","preliquidada","liquidada","listar_trunca"))){?>in<?php }?>">
            <li>
            	<a href="ejecucion?pagina=pendiente" <?php if(in_array(@$_GET["pagina"],array("pendiente"))){?>class="active-page link_activo"<?php }?>>Bandeja Gesti&oacute;n Obra</a>
            </li>
          
            <?php if($this->session->userdata('eeccSession')==6 || $this->session->userdata('eeccSession')== 0) { ?>
            <li>
            	<a href="bandejaParalizacion" <?php if(in_array(@$_GET["pagina"],array("bandejaParalizacion"))){?>class="active-page link_activo"<?php }?>>Bandeja Paralizaci&oacuten</a>
            </li>
            <?php } ?>
        </ul>
    <li>
      	<a href="javascript:void(0);" data-toggle="collapse" data-target="#dashboard_dr2" <?php if(in_array(@$_GET["pagina"],array("regFichaTec"))){?> 
      	class="active" <?php }?>><div class="pull-left"><i class="zmdi zmdi-key"></i><span class="right-nav-text">Cierre de Obra</span></div><div class="pull-right"><i class="zmdi zmdi-caret-down"></i></div><div class="clearfix"></div></a>
      	<ul id="dashboard_dr2" class="collapse collapse-level-1 <?php if(in_array(@$_GET["pagina"],array("regFichaTec"))){?>in<?php }?>">
            <li>
            	<a href="rftec2" <?php if(in_array(@$_GET["pagina"],array("regFichaTec"))){?>class="active-page link_activo"<?php }?>>Ficha Tecnica</a>
            </li>
        </ul>
    </li>
    <li>
      	<a href="javascript:void(0);" data-toggle="collapse" data-target="#consultas" <?php if(in_array(@$_GET["pagina"],array("consultar"))){?> class="active" <?php }?>>
          <div class="pull-left">
             <i class="zmdi zmdi-border-color"></i>
            <span class="right-nav-text">Modificaci&oacuten de DJ</span>
          </div>
          <div class="pull-right">
            <i class="zmdi zmdi-caret-down"></i>
          </div>
          <div class="clearfix"></div>
        </a>
      	<ul id="consultas" class="collapse collapse-level-1 <?php if(in_array(@$_GET["pagina"],array("consultar"))){?>in<?php }?>">
            <li>
            	<a href="consultas" <?php if(in_array(@$_GET["pagina"],array("consultar"))){?>class="active-page link_activo"<?php }?>>Formulario Sisegos</a>
            </li>
            <li>
            	<a href="consultasObp" <?php if(in_array(@$_GET["pagina"],array("consultar"))){?>class="active-page link_activo"<?php }?>>Formulario Obras P&uacute;blicas</a>
            </li>
        </ul>
    </li>
    
    <!--nuevo-->
    
    <li>
      	<a href="javascript:void(0);" data-toggle="collapse" data-target="#licencias" <?php if(in_array(@$_GET["pagina"],array("licencias"))){?> class="active" <?php }?>>
          <div class="pull-left">
          <i class="zmdi zmdi-file-text"></i>
            <span class="right-nav-text">Licencias</span>
          </div>
          <div class="pull-right">
            <i class="zmdi zmdi-caret-down"></i>
          </div>
          <div class="clearfix"></div>
        </a>
      	<ul id="licencias" class="collapse collapse-level-1 <?php if(in_array(@$_GET["pagina"],array("licencias"))){?>in<?php }?>">
            <li>
            	<a href="bandeja_licencias" <?php if(in_array(@$_GET["pagina"],array("licencias"))){?>class="active-page link_activo"<?php }?>>Gesti&oacute;n de licencias</a>
            </li>
            <li>
            	<a href="consulta_expediente" <?php if(in_array(@$_GET["pagina"],array("licencias"))){?>class="active-page link_activo"<?php }?>>Consulta por Expediente</a>
            </li>
            <!--11-09-2018-->
             <li>
            	<a href="liquidacion_obra" <?php if(in_array(@$_GET["pagina"],array("licencias"))){?>class="active-page link_activo"<?php }?>>Finalizacion de obra</a>
            </li>
            <!---->
            
        </ul>
    </li>
    
    
    <li>
      	<a href="javascript:void(0);" data-toggle="collapse" data-target="#cotizaciones" <?php if(in_array(@$_GET["pagina"],array("cotizaciones"))){?> class="active" <?php }?>>        <div class="pull-left">
            <i class="zmdi zmdi-money-box"></i>
            <span class="right-nav-text">Cotizaciones</span>
          </div>
          <div class="pull-right">
            <i class="zmdi zmdi-caret-down"></i>
          </div>
          <div class="clearfix"></div>
        </a>
      	<ul id="cotizaciones" class="collapse collapse-level-1 <?php if(in_array(@$_GET["pagina"],array("cotizaciones"))){?>in<?php }?>">
            <li>
            	<a href="cotizaciones" <?php if(in_array(@$_GET["pagina"],array("cotizaciones"))){?>class="active-page link_activo"<?php }?>>Cotizaci&oacute;n  Adic.</a>
            </li>
            <li>
            	<a href="aprobacionCotizacion" <?php if(in_array(@$_GET["pagina"],array("cotizaciones"))){?>class="active-page link_activo"<?php }?>>Aprobaci&oacute;n de cotizaciones</a>
            </li>
            <li>
            	<a href="consultaCotizacion" <?php if(in_array(@$_GET["pagina"],array("cotizaciones"))){?>class="active-page link_activo"<?php }?>>Consulta de cotizaciones</a>
            </li>
        </ul>
    </li>
    
    <!---->
<?php }
    if($this->session->userdata('eeccSession')==6 || $this->session->userdata('eeccSession')== 0) {
    ?>

    <?php
    }
    ?>      
      </ul>
    </div>
<div class="right-sidebar-backdrop"></div>