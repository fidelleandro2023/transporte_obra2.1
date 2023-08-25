
<div class="row" style="min-height:482px">
<div class="col-sm-12>
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h6 class="panel-title txt-dark">Edicion PEP</h6>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="form-wrap">
                                            <form class="form-horizontal" method="post" action="editar_detalle_toro?pagina=geteditarpep">
                                                <input type="hidden" name="id_pep" value="<?php echo $_GET["id"];?>">
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="email_hr">PEP:</label>
                                                <div class="col-sm-10">
                                                    <?php echo $_GET["id"];?>
                                                </div>
                                                </div>
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Relaciion TORO:</label>
                                                <div class="col-sm-10"> 
                                                    <?php echo $toro;?>
                                                </div>
                                                </div>

                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Precio:</label>
                                                <div class="col-sm-10"> 
                                                    <input type="text" class="form-control" placeholder="Precio" name="precio" value="<?php echo $pep["precio"];?>">
                                                </div>
                                                </div>


                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Cantidad:</label>
                                                <div class="col-sm-10"> 
                                                    <input type="text" class="form-control" placeholder="Cantidad" name="cantidad" value="<?php echo $pep["cantidad"];?>">
                                                </div>
                                                </div>

                                                
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Comentario:</label>
                                                <div class="col-sm-10"> 
                                                    <input type="text" class="form-control" placeholder="Detalle" name="detalle" value="<?php echo $pep["detalle"];?>">
                                                </div>
                                                </div>
                                                
                                                
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">SubProyecto:</label>
                                                <div class="col-sm-10"> 
                                                    <?php echo $subproyecto;?>
                                                </div>
                                                </div>
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Area:</label>
                                                <div class="col-sm-10"> 
                                                    <?php echo $areas;?>
                                                </div>
                                                </div>
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Tipo:</label>
                                                <div class="col-sm-10"> 
                                                    <?php echo $tipo;?>
                                                </div>
                                                </div>
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Fecha de Programacion:</label>
                                                        <div class="col-sm-10"> 
                                                        <input id="fec_programacion" value="<?php echo $fec_programacion?>" name="fec_programacion" type="date" class="form-control form-control-sm  date-picker form-control--active">
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
                        <script type="text/javascript">

     function changueProyect(){
         var subProy = $.trim($('#subproyecto').val()); 
          $.ajax({
             type    :   'POST',
             'url'   :   'getAreasBySubPro',
             data    :   {subProy  : subProy},
             'async' :   false
         })
         .done(function(data){
             var data    =   JSON.parse(data);
             if(data.error == 0){  
                 //console.log(data.areas);     
     
                 $('#selectArea').html(data.areas);
                 $('#selectArea').select2();
             }else if(data.error == 1){
                 
                 mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
             }
         });
     }
          
     </script>
               