<div class="page-wrapper" style="margin-left:0px;padding-top:20px;">
<div class="container-fluid">
<div class="panel panel-default card-view">


<div class="panel-heading">
                <div class="pull-left">
                  <h6 class="panel-title txt-dark">Situación de ItemPlan : <?php echo $_GET["id"];?></h6>
                </div>
                <div class="clearfix"></div>
              </div>
<div class="panel-wrapper collapse in">
<div class="panel-body">                
<div class="row">
<form method="post" action="situacion">  
<input type="hidden" name="id_actividad" value="1">  
<input type="hidden" name="id" value="<?php echo $_GET["id"];?>">
<div class="col-sm-10 col-lg-10">
<select class="form-control" name="id_subactividad_estado">
<?php
echo $option;
?>
</select>  
</div>
<div class="col-sm-10 col-lg-10">
<div class="input-group" style="width:100%">
<textarea rows="6" id="observacion" name="observacion" class="form-control" style="margin-top:20px"></textarea>
</div>
</div>
<div class="col-sm-3 col-lg-3" style="margin-top:20px">
<button type="submit" class="btn btn-primary m-b-0" id="enviar">Enviar</button>
</div>    
</form>
</div>
<div class="row" style="margin-top:20px">
<?php
echo $existe;
?>
</div>  
</div>
</div>
</div>
