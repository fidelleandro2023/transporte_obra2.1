<div class="row" style="min-height:482px">
<div class="col-sm-12>
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h6 class="panel-title txt-dark">Registrar PEP</h6>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="form-wrap">
                                            <form class="form-horizontal" method="post" onsubmit="return mySubmitFunction(event)" action="nuevo_detalle_toro?pagina=addNewPep">                                               
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="email_hr">PEP:</label>
                                                <div class="col-sm-10">
                                                    <input required="required"  id="pep2" name="pep2" type="text" class="form-control  input-mask" data-mask="P-0000-00-0000-00000" placeholder="P-0000-00-0000-00000" maxlength="20">
                                              
                                                </div>
                                                </div>
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Relacion TORO:</label>
                                                <div class="col-sm-10"> 
                                                    <?php echo $toro;?>
                                                </div>
                                                </div>

                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Precio:</label>
                                                <div class="col-sm-10"> 
                                                    <input type="text" class="form-control" placeholder="Precio" name="precio" >
                                                </div>
                                                </div>


                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Cantidad:</label>
                                                <div class="col-sm-10"> 
                                                    <input type="text" class="form-control" placeholder="Cantidad" name="cantidad" >
                                                </div>
                                                </div>

                                                
                                                <div class="form-group" style="height:42px">
                                                    <label class="control-label mb-8 col-sm-2" for="pwd_hr">Comentario:</label>
                                                <div class="col-sm-10"> 
                                                    <input type="text" class="form-control" placeholder="Detalle" name="detalle" >
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
                                                        <input id="fec_programacion" name="fec_programacion" type="date" class="form-control form-control-sm  date-picker form-control--active">
                                                      </div>
                                             
                                                </div>
                                                <div class="form-group" style="height:42px"> 
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                      <button type="submit" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Guardar</span></button>
                                                    </div>
                                                </div>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
     <script type="text/javascript">
     
function mySubmitFunction(e) {
    	 var pep2 = $.trim($('#pep2').val()); 
         $.ajax({
            type    :   'POST',
            'url'   :   'valExistPep',
            data    :   {pep2  : pep2},
            'async' :   false
        })
        .done(function(data){
            var data    =   JSON.parse(data);
            if(data.error == 0){  
                if(data.exist>=1){
                    $('#msjExit').html('Pep ya se encuentra registrado!');
                	e.preventDefault();
                }else{
               	 return true;
                }               
            }else if(data.error == 1){                
                mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                e.preventDefault();
            }
        });
      
   	 
   	}
   	
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