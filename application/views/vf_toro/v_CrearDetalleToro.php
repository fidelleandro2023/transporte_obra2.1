<form method="post" action="crear_detalle_toro?pagina=creardetalle">
<div class="row" style="min-height:482px">
<div class="col-sm-12>
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h6 class="panel-title txt-dark">Creación TORO</h6>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="form-wrap">
                                            <form class="form-horizontal">
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="email_hr">TORO:</label>
                                                <div class="col-sm-10">
                                                    <?php echo $_GET["id"];?>
                                                    <input type="hidden" name="id_toro" value="<?php echo $_GET["id"];?>">
                                                </div>
                                                </div>
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="email_hr">PEP:</label>
                                                <div class="col-sm-10">
                                                    <?php echo $pep;?>
                                                </div>
                                                </div>
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Sub Proyecto:</label>
                                                <div class="col-sm-10"> 
                                                    <?php echo $subproyecto;?>
                                                </div>
                                                </div>
                                                
                                                <div class="form-group" style="height:42px"> 
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                      <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Agregar</span></button>
                                                    </div>
                                                </div>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
</form>                   