<form method="post" action="editar_toro?pagina=editar">
<div class="row" style="min-height:482px">
<div class="col-sm-12>
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h6 class="panel-title txt-dark">Edición TORO</h6>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="form-wrap">
                                            <form class="form-horizontal">
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="email_hr">Código:</label>
                                                <div class="col-sm-10">
                                                    <input disabled="disabled" type="text" class="form-control"  placeholder="Código" name="id_toro_referencia" value="<?php echo $toro["id_toro"];?>">
                                                    <input type="hidden" name="id_toro" value="<?php echo $toro["id_toro"];?>">
                                                </div>
                                                </div>
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">AE:</label>
                                                <div class="col-sm-10"> 
                                                    <input type="text" class="form-control" placeholder="AE" name="ae" value="<?php echo trim($toro["ae"]);?>">
                                                </div>
                                                </div>
                                                
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Proyecto:</label>
                                                <div class="col-sm-10"> 
                                                    <?php echo $proyecto;?>
                                                </div>
                                                </div>
                                                
                                                
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Monto:</label>
                                                <div class="col-sm-10"> 
                                                    <input  name="monto" type="text" class="form-control" value="<?php echo number_format((float)$toro["monto"]);?>">
                                                </div>
                                                </div>
                                                
                                                <div class="form-group" style="height:42px"> 
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                      <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Editar</span></button>
                                                    </div>
                                                </div>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
</form>                   