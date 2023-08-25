<style type="text/css">
.dt-buttons{
display: block!important;
margin-right: 20px;  
}  
</style>
<section class="content content--full">
<div class="content__inner">

<div class="card">                                 
<div class="card-block">                      
<div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default card-view">
<div class="panel-heading toolbar">
                  <div class="pull-left">
<button class="btn  btn-primary crear_toro">+ Agregar TORO</button>                   
                  </div>
                  <div class="actions">
<form method="post" action="listar_toro?pagina=carga_masiva" enctype="multipart/form-data"><input type="file" name="masivo"> <button style="background-color:#8bc34a;float:right" type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Carga Masiva</span></button></form>
<!--<form method="post" action="listar_toro?pagina=carga_masiva_toro" enctype="multipart/form-data"><input type="file" name="masivo"> <button style="background-color:#8bc34a;float:right" type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Carga Masiva</span></button></form>-->

                  </div>
                  
                  <div class="clearfix"></div>
                </div>              

<div class="panel-heading toolbar">
                  <div class="pull-left">
<div class="form-group form-group--select" style="float:left;width:400px">
                                        <div class="select">
                                            <?php echo $filtrar_subproyecto;?>
                                        </div>
                                    </div>                                    
                  </div>
                  <div class="actions">
Buscar : <input type="text" name="buscar" class="form-group" style="margin-right:20px" id="input_buscar"> <button style="float:right" type="submit" class="btn btn-warning btn-anim" id="boton_buscar"><i class="icon-rocket"></i><span class="btn-text">BUSCAR</span></button>
                  </div>
                  
                  <div class="clearfix"></div>
                </div> 

              <div class="panel-wrapper">
                <div class="panel-body">
                  <div class="table-wrap">
                    <div class="table-responsive">

<?php echo $tabla;?>

                    </div>
                  </div>
                </div>
              </div>
            </div>  
          </div>
        </div></div></div>                          
<?php
if(@$_GET["pagina"]=="carga_masiva"){
  $r=explode("|", $resultado);
?>
<div class="modal fade" id="modalRegistrarEstacion">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">RESULTADOS</h5>
                    </div>
                    <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
<?php
echo $r[2]."<br>".$r[1]."<br>".$r[0];
?>                                 
                                </div>
                            </div>
                        
                    </div>                    
                </div>
            </div>
        </div>
<?php }?>

                                  
                      </div>


            </section>
        </main>
 
    </body>

</html>