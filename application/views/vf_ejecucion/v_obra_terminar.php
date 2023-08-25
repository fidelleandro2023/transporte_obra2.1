<div class="row" style="background-color:#eaf1f0;height:580px">
    <div class="panel-heading">
        <div class="pull-left">
            <h5 class="m-b-10" style="font-size:20px">Terminar Ejecuci��n de Obra : <?php echo $_GET["id"];?> / <?php echo $obra["nombreProyecto"];?></h5>    
            <h6 class="sub-title">Ingrese los documentos necesarios de fin de Obra</h6>
        </div>
        <div class="clearfix">
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="form-layout form-layout-12">
                <?php echo $lista;?>
                <?php if($obra["idEstadoPlan"]==3||$obra["idEstadoPlan"]==9){?>
            
                <div class="row">
                    <input type="file" name="file" id="input_file" multiple="multiple" style="width:100%">
                </div>
            <div class="row">
                <label class="col-sm-10"></label>
                <div class="col-sm-12" style="float:right">
    
                <form id="formu" method="post" action="ajax">
                    <input type="hidden" name="id" value="<?php echo $_GET["id"];?>">
                    <input type="hidden" name="pagina" value="liquidar">
                    <?php
                    if($obra["idEstadoPlan"]==9){
                    ?>
                        <!-- <button type="submit" class="btn btn-primary m-b-0" id="terminar" style="float:right">Terminar Obra</button> -->
                        <!-- <button type="button" class="btn btn-danger m-b-0" id="preliquidar" title="<?php echo $_GET["id"];?>" style="margin-right:20px;float:right">Pre Liquidar</button> -->
                    <?php }?>
                </form>
            </div>
</div>
<?php }?>
</div>
</div>
</div>
</div>
