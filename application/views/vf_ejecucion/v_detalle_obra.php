<style>#drop,#upload{border-radius:3px}#drop,td{text-align:center}#drop,#u_image li p{font-size:16px;font-weight:700}#u_image li canvas,#u_image li p,#u_image li span{position:absolute}#u_image li{height:84px}#drop{margin-bottom:30px;text-transform:uppercase;color:#7f858a}#u_image{list-style-type:none}#drop a{color:#000;border-radius:2px;cursor:pointer;display:inline-block}#drop input,#u_image li input{display:none}#upload ul{list-style:none;margin:0 -30px;border-top:1px solid #2b2e31;border-bottom:1px solid #3d4043}#u_image li{background-color:#333639;background-image:-webkit-linear-gradient(top,#333639,#303335);background-image:-moz-linear-gradient(top,#333639,#303335);background-image:linear-gradient(top,#333639,#303335);border-top:1px solid #3d4043;border-bottom:1px solid #2b2e31;padding:40px;height:52px;position:relative}ul li p{width:144px;overflow:hidden;white-space:nowrap;color:#EEE;top:20px;left:100px}#u_image li i{font-weight:400;font-style:normal;color:#7f7f7f;display:block}#u_image li canvas{top:15px;left:32px}#u_image li span{width:15px;height:12px;background:url(../img/icons.png) no-repeat;top:34px;right:33px;cursor:pointer}#u_image li.working span{height:16px;background-position:0 -12px}#u_image li.error p{color:red}.img-fluid{width:calc(100%/3.5);margin: 5px;}
</style>
<?php
$desc="Actividad";
if($this->session->userdata("zonasSession")){
//$act["id_subactividad"]=$act["idEstacion"];
$desc="Estación";
}
?>
<div class="page-wrapper" style="min-height: 405px;">
<div class="container-fluid">
<br>
          
          <div class="row">
            <div class="col-md-8">
              <div class="panel panel-default card-view">
                <div class="panel-heading">
                  <div class="pull-left">
                    <h6 class="panel-title txt-dark">FECHA MCI : <span style="font-size:16px" class="label label-danger"><?php echo $nuevafechap;?></span></h6>
                  </div>
                  <div class="pull-right">
                    <h6 class="panel-title txt-dark">FECHA INICIO : <span style="font-size:16px" class="label label-primary"><?php echo $nuevafecha;?></span></h6>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-sm-12 col-xs-12">
                        <div class="form-wrap">
                         <div class="card-block user-box">
 
<div class="p-b-20">
<div class="row">
<div class="col-sm-12">
  <?php echo $listaragenda;?>
</div>  
<div class="col-sm-4 pt-20">
<b class="panel-title">Porcentaje Total:</b>
<select id="porcentaje" name="porcentaje" style="float:right">
<?php

for($i=$porcentaje;$i<=100;$i+=25){
?>
<option value="<?php echo $i;?>"><?php echo $i;?> %</option>
<?php  
}
?>  
</select>
 </div>
 <div class="col-sm-3">

</div>
 <div class="col-sm-6 pt-20">
<span class="pull-right"><b class="form-control-label text-info panel-title"> Porcentaje Actual: <?php echo $porcentaje;?>%</b>
</span>
</div>
</div>


</div> 
<?php
if($porcentaje!=100){
?>
<div class="media">

<form class="media-left" id="upload" method="post" action="ajax?pagina=upload" enctype="multipart/form-data">
                  <input type="hidden" name="id_sub_actividad" id="id_sub_actividad" value="<?php echo $act["id_subactividad"];?>">
                  <input type="hidden" name="coordenadas" value="" id="coordenadas">
                  <input type="hidden" id="id_planobra_actividad" name="id_planobra_actividad" value="<?php echo $_GET["id_planobra_actividad"];?>">
                  <input type="hidden" id="porcentaje_a" value="<?php echo $porcentaje;?>">

                  <div id="drop">
                  <a><i class="fa fa-camera-retro" style="font-size:30px"></i></a><input type="file" name="upl" multiple="">
                  
                  </div> 
</form>
<div class="media-body">

<textarea class="f-13 form-control msg-send" rows="3" cols="10" required="" placeholder="Ingrese Observaciones del cliente ..." id="conversacion"></textarea>
<div class="text-right mt-20"><input type="button" id="localizar" value="Enviar" class="btn btn-primary m-b-0"></div>

</div>
</div>
<?php }?>
<div class="row">
                <div class="col-sm-12 col-xs-12 mg-t-40 mg-sm-t-10">
                  <ul id="u_image" style="padding-top:10px"></ul>
                </div></div>
</div> 
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
            <div class="panel panel-default border-panel card-view">
              <div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">Datos de Obra</h6>
                </div>
                
                <div class="clearfix"></div>
              </div>
              <div class="panel-wrapper task-panel collapse in">
                <div class="panel-body row pa-0">
                  <div class="list-group mb-0">
                    <a href="#" class="list-group-item">
                      <span class="badge transparent-badge badge-info capitalize-font"><?php echo $act["itemPlan"];?></span>
                      <i class="fa fa-angle-right pull-left"></i><p class="pull-left">ItemPlan</p>
                      <div class="clearfix"></div>
                    </a>
                    <a href="#" class="list-group-item">
                      <span class="badge transparent-badge badge-info  capitalize-font"><?php echo $act["nombreProyecto"];?></span>
                      <i class="fa fa-angle-right pull-left"></i><p class=" pull-left">Proyecto</p>
                      <div class="clearfix"></div>
                    </a>
                    <a href="#" class="list-group-item">
                      <span class="badge transparent-badge badge-info  capitalize-font"><?php echo $act["actividad"];?> </span>
                      <i class="fa fa-angle-right pull-left"></i><p class=" pull-left"><?php echo $desc;?></p>
                      <div class="clearfix"></div>
                    </a>
                     <a href="#" class="list-group-item">
                      <span class="badge transparent-badge badge-info  capitalize-font"><?php echo $visita;?> </span>
                      <i class="fa fa-angle-right pull-left"></i><p class=" pull-left">Visitas</p>
                      <div class="clearfix"></div>
                    </a>                          
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
            <div class="col-md-4">
              <div class="panel panel-default card-view">
                <div class="panel-heading">
                  <div class="pull-left">
                    <h6 class="panel-title txt-dark">Fotos</h6>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-sm-12 col-xs-12">
                        <div class="form-wrap">
                          <?php echo $imagenes;?>
                        </div>
                      </div>  
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
         
        
        </div>
        
        <!-- Footer -->
       
      
      </div>
      <div class="modal" id="modal1"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Fallo el Ingreso</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body"><p>Ingrese Porcentaje de avance de la Obra</p></div><div class="modal-footer"><button id="modal-btn-aceptar" type="button" class="btn btn-secondary mobtn" data-dismiss="modal">Aceptar</button></div></div></div></div>
<div class="modal" id="modal2"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Fallo el Ingreso</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body"><p>Ingrese Observación de la Obra</p></div><div class="modal-footer"><button id="modal-btn-aceptar" type="button" class="btn btn-secondary mobtn" data-dismiss="modal">Aceptar</button></div></div></div></div>
<div class="modal" id="modal3"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Fallo el Ingreso</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body"><p>Seleccione Porcentaje Mayor a Cero</p></div><div class="modal-footer"><button id="modal-btn-aceptar" type="button" class="btn btn-secondary mobtn" data-dismiss="modal">Aceptar</button></div></div></div></div>
<div class="modal" id="modal4"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Fallo el Ingreso</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body"><p>Porcentaje Actual no puede exceder al 100%</p></div><div class="modal-footer"><button id="modal-btn-aceptar" type="button" class="btn btn-secondary mobtn" data-dismiss="modal">Aceptar</button></div></div></div></div>
<div class="modal" id="modal5"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Fallo el Ingreso</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div><div class="modal-body"><p>Ingrese un Porcentaje total de obra mayor a 0%</p></div><div class="modal-footer"><button id="modal-btn-aceptar" type="button" class="btn btn-secondary mobtn" data-dismiss="modal">Aceptar</button></div></div></div></div>