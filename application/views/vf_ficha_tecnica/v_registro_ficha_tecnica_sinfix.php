<link rel="stylesheet" href="<?php echo base_url();?>public/css/galeria_fotos.css?v=<?php echo time();?>">
<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css?v=<?php echo time();?>">
<style>.fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}</style>
<style>
.modal-dialog {
  position: relative;
  width: auto;
  max-width: 600px;
  margin: 10px;
}
.modal-sm {
  max-width: 300px;
}
.modal-lg {
  max-width: 90%;
}
@media (min-width: 768px) {
  .modal-dialog {
    margin: 30px auto;
  }
}
@media (min-width: 320px) {
  .modal-sm {
    margin-right: auto;
    margin-left: auto;
  }
}
@media (min-width: 620px) {
  .modal-dialog {
    margin-right: auto;
    margin-left: auto;
  }
  .modal-lg {
    margin-right: 10px;
    margin-left: 10px;
  }
}
@media (min-width: 920px) {
  .modal-lg {
    margin-right: auto;
    margin-left: auto;
  }
}    
</style>
<div class="page-wrapper">
<div class="container-fluid">
         <div class="row heading-lg">
            
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <ol class="breadcrumb">
	              <li><a href="index.php">Inicio</a></li>
	              <li><a href="#" class=""><span>Registro Ficha Tecnica</span></a></li>
	              <li><a href="#" class="active"><span><?php echo $pagina;?>s</span></a></li>
	            </ol>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                    <!--<h3 ><?php echo strtoupper($pagina);?></h3>-->
                    <h2 >REGISTRO FICHA TECNICA</h2>
                </div>
<div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default card-view">              
            
                <form method="post" action="rftec2">
                            <input type="hidden" name="pagina" value="pendienteFiltro">
                            <div class="col-md-3">
                                          <div class="form-group">
                                            <label class="control-label mb-10">ItemPlan</label>
                                            <input type="text" name="itemplan" class="form-control" placeholder="ItemPlan" value="<?php if(@$_POST["itemplan"]){ echo $_POST["itemplan"];}?>">
                                          </div>
                                        </div>
                                        <div class="col-md-3">
                                          <div class="form-group">
                                            <label class="control-label mb-10">Proyecto</label>
                                            <select onchange="getSubProyecto();" class="form-control select2" name="proyecto" id="proyecto">
                                              <option value="0">Seleccionar Proyecto</option>
                                              <?php echo $proyecto;?>
                                            </select>
                                          </div>
                                        </div> 
                                        <div class="col-md-3">
                                          <div class="form-group">
                                            <label class="control-label mb-10">SubProyecto</label>
                                            <select name="subproyecto" class="form-control select2" id="subproyecto">
                                              <option value="0">Seleccionar SubProyecto</option>
                                              <?php echo $subproyecto;?>
                                            </select>
                                          </div>
                                        </div>
                                        <!-- 
                                        <div class="col-md-3">
                                          <div class="form-group">
                                            <label class="control-label mb-10">Fase</label>
                                            <select name="selectFase" class="form-control select2" id="selectFase">
                                              <option value="0">Seleccionar Fase</option>
                                              <?php echo $fase;?>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-3">
                                          <div class="form-group">
                                            <label class="control-label mb-10">Indicador</label>
                                            <input name="indicador" type="text" class="form-control" placeholder="Indicador" value="<?php if(@$_POST["indicador"]){ echo $_POST["indicador"];}?>">
                                          </div>
                                        </div> -->
                                <div class="col-md-3" style="margin-top: 25px;">
                                    <button type="submit" class="btn btn-success  mr-10">Filtrar</button>                                              
                                </div>
                        </form>
                    
                    <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                           <div class="table-wrap">
                        <div style="width:100%" id="contTabla" class="table-responsive">
    						<?php echo $tabla;?>
                        </div>
                      </div>         
                    </div>
                </div>              
            </div>    
          </div>
        </div>
        
	<div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalRegistrarFicha" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">CHECK LIST DE TRABAJOS EN PLANTA EXTERNA</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <div id="content" class="modal-body">
                       <form id="formRegistrarFicha" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row">
                           	
                               	
                                 <div class="form-group col-sm-3 col-xs-12 ">
                                 	<label class="control-label mb-10 text-left">SUB PROYECTO</label>  
                                    <input disabled id="txtSubProyecto" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                               		<label class="control-label mb-10 text-left">ITEMPLAN</label>
                                    <input disabled id="txtItemplan" type="text" class="form-control">
                                </div>
                                 <div class="form-group col-sm-2 col-xs-6">
                                 	<label class="control-label mb-10 text-left">NODO</label>        
                                    <input disabled id="txtNodo" type="text" class="form-control">                                                               
                                </div>
                            	 <div class="form-group col-sm-2 col-xs-6">
                            	  	<label class="control-label mb-10 text-left">FECHA INICIO</label>
                                    <input disabled id="txtFechaInicio" type="text" class="form-control">                                   
                            	</div>                            	
                            	<div class="form-group col-sm-2 col-xs-6">                            	 
                            	 	<label class="control-label mb-10 text-left">FECHA FIN</label>
                                    <input disabled id="txtFechaFin" type="text" class="form-control">
                                </div>
                            	
                            	<!-- <div class="form-group col-sm-3 col-xs-8">
                            		<label class="control-label mb-10 text-left">NOMBRE CUADRILLA</label>
                                    <input disabled id="txtNombreCuadrilla" type="text" class="form-control">
                                </div>-->
                            	<div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 	<h6 class="card-body__title" style="text-decoration: underline;">JEFE DE CUADRILLA</h6>
                            	</div>
                            	<div class="form-group col-sm-3">
                            		<label class="control-label mb-10 text-left" style="color:red">NOMBRE(*)</label>
                                    <input id="txtNombreJefeCuadrilla" name="txtNombreJefeCuadrilla" type="text" class="form-control">
                                    
                            	</div>
                            	
                            	<div class="form-group col-sm-3">
                            		<label class="control-label mb-10 text-left" style="color:red">CODIGO(*)</label>
                                    <input id="txtCodigo" name="txtCodigo" type="text" class="form-control">
                                    
                                  
                            	</div>
                            	
                            	<div class="form-group col-sm-3">
                            	
                            		<label class="control-label mb-10 text-left">EECC</label>
                                    <input disabled id="txtEECC" type="text" class="form-control form-control-sm">
                                    
                                    
                            	</div>
                            	<div class="form-group col-sm-3">
                            		<label class="control-label mb-10 text-left" style="color:red">CELULAR(*)</label>
                                    <input id="txtCelular" name="txtCelular" type="text" class="form-control form-control-sm">                                    
                            	</div>
                            	
                            	<!-- ---------------------------------------INFORMACION DE TRABAJOS REALIZADOS----------------------------------- -->
                            	<div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 <h6 class="card-body__title" style="text-decoration: underline;">1) INFORMACION DE TRABAJOS REALIZADOS</h6>
                            	</div>
                           
                        		<div class="form-group col-sm-3  col-xs-6">
                        			<label class="control-label mb-10 text-left">COORDENADA X</label>
                                    <input disabled name="txtCoorX" id="txtCoorX" type="text" class="form-control form-control-sm">
                                    
                            	</div>
                            	<div class="form-group col-sm-3  col-xs-6">
                            		<label class="control-label mb-10 text-left">COORDENADA Y</label>     
                                    <input disabled id="txtCoorY" name="txtCoorY" type="text" class="form-control form-control-sm">
                              	</div>
                              	<div class="form-group col-sm-3 col-xs-6">
                            		<label class="control-label mb-10 text-left">TROBA</label>
                                    <input disabled id="txtTroba" type="text" class="form-control">
                                </div>
                              	<div class="form-group col-sm-3  col-xs-6">
                            		<label class="control-label mb-10 text-left">SERIE TROBA</label>     
                                    <input disabled id="txtSerieTroba" name="txtSerieTroba" type="text" class="form-control form-control-sm">
                              	</div>
                              		<div class="form-group form-group--float col-sm-12 table-responsive">                             	
                                		<table style="font-size: 10px" id="tabla_trabajos" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th style="width: 15%" ></th>
                                                    <th style="width: 15%" >CANTIDAD</th>
                                                    <th style="width: 25%" >TIPO</th>                            
                                                    <th style="width: 45%" >OBSERVACIONES</th>                                             
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th style="width: 15%" ></th>
                                                    <th style="width: 15%" >CANTIDAD</th>
                                                    <th style="width: 25%" >TIPO</th>                            
                                                    <th style="width: 45%" >OBSERVACIONES</th>                            
                                                </tr>
                                            </tfoot>    
                                            <tbody>
                                     		<?php
                                                foreach($listaTrabajos->result() as $row){
                                            ?>
                                          	<tr>
                                              <th><?php echo $row->descripcion?></th>
                                              <th><input id="inputCantidadTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" name="inputCantidadTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" type="text" class="form-control form-control-sm"></th>
                                              <th>  <select id="selectTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" name="selectTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" class="select2 selectForm">
                                                                 <option>&nbsp;</option>
                                                           <?php echo $optionsTipoTra?>
                                                           </select>
                                              </th>
                                              <th><input id="inputComentarioTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" name="inputComentarioTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" type="text" class="form-control form-control-sm"></th>
                                          	</tr>           
                                                                    
                                            <?php }?>
                                               
                                        	
                                         </tbody>
                                    	</table>
                                </div>
                                
                                <div class="form-group col-sm-12">
                                 	<div class="form-group form-control-sm">
                                      	<label>OBSERVACIONES</label>
                                        <textarea id="inputObservacion" name="inputObservacion" class="form-control textarea-autosize" placeholder="Escriba aqui..."></textarea>
                                        <i class="form-group__bar"></i>
                                    </div>
                            	</div>
                            	
           <!-- ----------------------------------------------------------------------------------------------------------------->
                            	
                            <!-- ------------------------NIVELES DE CALIBRACION----------------------------------- -->
                            
                            <div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 <h6 class="card-body__title" style="text-decoration: underline;">2) NIVELES DE CALIBRACION</h6>
                            	</div>
                            	                  
                 <div class="form-group col-sm-12 table-responsive">       
                	<table style="font-size: 10px" id="data-table" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    	<thead class="thead-default">
                           <tr role="row">                           
                                <th style="text-align: center;    WIDTH: 20%;" colspan="1"></th>
                                <th style="text-align: center;" colspan="2">POT. OPT</th>
    	                        <th style="TEXT-ALIGN: center;" colspan="1">CH 30</th>
                              	<th style="TEXT-ALIGN: center;" colspan="1">CH 75</th>
                              	<th style="TEXT-ALIGN: center;" colspan="1">CH 113</th>
                              	<th style="TEXT-ALIGN: center;" colspan="1">SNR - RUIDO</th>                                                                                       
                           </tr>
                       <tr role="row">                           
                           
                            <th colspan="1"></th> 
                            <th colspan="1">0 - 3 DB</th>                          
                            <th colspan="1">3 - 7 DB</th>
	                        <th colspan="1">36 - 39 DB</th>
                            <th colspan="1">40 - 42 DB</th>
	                        <th colspan="1">44 - 45 DB</th>
                            <th colspan="1"> > 32 DB</th>      
                                        
                        </tr>
                    </thead>  
                    <tfoot>
                        <tr>
                            <th colspan="1"></th> 
                            <th colspan="1">0 - 3 DB</th>                          
                            <th colspan="1">3 - 7 DB</th>
	                        <th colspan="1">36 - 39 DB</th>
                            <th colspan="1">40 - 42 DB</th>
	                        <th colspan="1">44 - 45 DB</th>
                            <th colspan="1"> > 32 DB</th>                            
                        </tr>
                    </tfoot>                  
                    <tbody>
                    
                    <?php                                                    
                            foreach($listaNivelesCali->result() as $row){                      
                    ?>
                      <tr>
                         <th><?php echo $row->descripcion?></th>
                         <?php echo (($row->id_ficha_tecnica_nivel_calibra != 1)? '<th style="background: #969696;" </th>' : '<th><input '.(($row->id_ficha_tecnica_nivel_calibra != 1)? "disabled" : "").' id="opt1_'.$row->id_ficha_tecnica_nivel_calibra.'" name="opt1_'.$row->id_ficha_tecnica_nivel_calibra.'" type="text" class="form-control form-control-sm input-3"> </th>')?> 
                         <th><input id="opt2_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="opt2_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-7"></th>
                         <th><input id="ch30_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="ch30_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-39"></th>
                     	 <th><input id="ch75_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="ch75_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-42"></th>
                         <th><input id="ch113_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="ch113_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm  input-45"></th>
                         <th><input id="snr_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="snr_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-32"></th>
                      </tr>          
                    <?php }?>
                     </tbody>
                	</table>
                </div>
                
                                 <div class="form-group col-sm-12">
                                 	<div class="form-group form-control-sm">
                                      	<label>OBSERVACIONES</label>
                                        <textarea id="inputObservacionAdicional" name="inputObservacionAdicional" class="form-control textarea-autosize" placeholder="Escriba aqui..."></textarea>
                                        <i class="form-group__bar"></i>
                                    </div>
                            	</div>
                            	<!-- ------------------------------------------------------------------------------- -->
                             </div>
                            <div id="mensajeForm"></div>  
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button id="btnRegFicha" type="submit" class="btn btn-primary">Save changes</button>
                                    
                                </div>
                            </div>
                        </form>     
                	</div>                	
            	</div>            	
        	</div>
    	</div>
    	<!-- ---------------------------------------------------------------------------------------------------- ----------------------------------------------------------- -->
    	
    	<!-- -------------------------------------------------------------inicio modal 2------------------------------------------------------------------- -->   				                    
        <div class="modal fade" id="modalEvaluarFicha" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <img style="width: 100px; heigth:40px" src="<?php echo base_url();?>public/img/logo/tdp.png">
                      
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div id="contFichaEval" class="modal-body">
                        
                    </div>
            </div>
        </div>
	</div>
<!-- --------------------------------------------------------FIN DEL MODAL 2------------------------------------------------------------------------ -->   				                    
	
		
<div class="modal fade bd-example-modal-lg" id="modalGaleriaFotos" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">EVIDENCIA</h5>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
              <!-- <span aria-hidden="true">&times;</span>
              </button> -->
          </div>
          <div class="modal-body modal-galeria">
                   <div class="container">
                       <h5>FIBRA &Oacute;PTICA<h5>
                        <ul id="list-imageFO" class="list-image">
                        </u>
                                  
                   </div>
                   <div class="container">
                    <h5>FIBRA COAXIAL<h5>
                        <ul id="list-imageCO" class="list-image">
                        </u>    
                    </div>
                   <div class="container">
                    <h5>INS. TROBA<h5>
                        <ul id="list-imageTRO" class="list-image">
                        </u>    
                    </div>
                   <!-- <div class="container">
                        <ul class="list-image">
                        </u>             
                   </div>                -->
            </div>
          <div class="modal-footer">
              <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
          </div>
      </div>
  </div> 


<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Â¿Desea pasar a pre-liquidado?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAdjudica" onclick="cambiarEstado();"  class="btn btn-primary">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
                </div>
            </div>
        </div>
        
        <!-- ---------------------------------------------------------------------------------------------------------------------------------- -->
        <div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalRegistrarFichaFOFTTH" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">CHECK LIST DE TRABAJOS EN PLANTA EXTERNA</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <div id="content" class="modal-body">
                       <form id="formRegistrarFichaFO" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row">
                           	
                               	
                                 <div class="form-group col-sm-3 col-xs-12 ">
                                 	<label class="control-label mb-10 text-left">SUB PROYECTO</label>  
                                    <input disabled id="txtSubProyecto2" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                               		<label class="control-label mb-10 text-left">ITEMPLAN</label>
                                    <input disabled id="txtItemplan2" type="text" class="form-control">
                                </div>
                                 <div class="form-group col-sm-2 col-xs-6">
                                 	<label class="control-label mb-10 text-left">NODO</label>        
                                    <input disabled id="txtNodo2" type="text" class="form-control">                                                               
                                </div>
                            	 <div class="form-group col-sm-2 col-xs-6">
                            	  	<label class="control-label mb-10 text-left">FECHA INICIO</label>
                                    <input disabled id="txtFechaInicio2" type="text" class="form-control">                                   
                            	</div>                            	
                            	<div class="form-group col-sm-2 col-xs-6">                            	 
                            	 	<label class="control-label mb-10 text-left">FECHA FIN</label>
                                    <input disabled id="txtFechaFin2" type="text" class="form-control">
                                </div>
                            	
                            	<!-- <div class="form-group col-sm-3 col-xs-8">
                            		<label class="control-label mb-10 text-left">NOMBRE CUADRILLA</label>
                                    <input disabled id="txtNombreCuadrilla" type="text" class="form-control">
                                </div>-->
                            	<div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 	<h6 class="card-body__title" style="text-decoration: underline;">JEFE DE CUADRILLA</h6>
                            	</div>
                            	<div class="form-group col-sm-3">
                            		<label class="control-label mb-10 text-left" style="color:red">NOMBRE(*)</label>
                                    <input id="txtNombreJefeCuadrilla2" name="txtNombreJefeCuadrilla2" type="text" class="form-control">
                                    
                            	</div>
                            	
                            	<div class="form-group col-sm-3">
                            		<label class="control-label mb-10 text-left" style="color:red">CODIGO(*)</label>
                                    <input id="txtCodigo2" name="txtCodigo2" type="text" class="form-control">
                                    
                                  
                            	</div>
                            	
                            	<div class="form-group col-sm-3">
                            	
                            		<label class="control-label mb-10 text-left">EECC</label>
                                    <input disabled id="txtEECC2" type="text" class="form-control form-control-sm">
                                    
                                    
                            	</div>
                            	<div class="form-group col-sm-3">
                            		<label class="control-label mb-10 text-left" style="color:red">CELULAR(*)</label>
                                    <input id="txtCelular2" name="txtCelular2" type="text" class="form-control form-control-sm">                                    
                            	</div>
                            	
                            	<!-- ---------------------------------------INFORMACION DE TRABAJOS REALIZADOS----------------------------------- -->
                            	<div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 <h6 class="card-body__title" style="text-decoration: underline;">1) INFORMACION DE TRABAJOS REALIZADOS</h6>
                            	</div>
                           
                        		<div class="form-group col-sm-3  col-xs-6">
                        			<label class="control-label mb-10 text-left">COORDENADA X</label>
                                    <input disabled name="txtCoorX2" id="txtCoorX2" type="text" class="form-control form-control-sm">
                                    
                            	</div>
                            	<div class="form-group col-sm-3  col-xs-6">
                            		<label class="control-label mb-10 text-left">COORDENADA Y</label>     
                                    <input disabled id="txtCoorY2" name="txtCoorY2" type="text" class="form-control form-control-sm">
                              	</div>
                              	<div class="form-group col-sm-3 col-xs-6">
                            		<label class="control-label mb-10 text-left">TROBA</label>
                                    <input disabled id="txtTroba2" type="text" class="form-control">
                                </div>
                              	<div class="form-group col-sm-3  col-xs-6">
                            		<label class="control-label mb-10 text-left">SERIE TROBA</label>     
                                    <input disabled id="txtSerieTroba2" name="txtSerieTroba2" type="text" class="form-control form-control-sm">
                              	</div>
                              		<div class="form-group form-group--float col-sm-12 table-responsive">                             	
                                		<table style="font-size: 10px" id="tabla_trabajos" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th style="width: 15%" ></th>
                                                    <th style="width: 15%" >CANTIDAD</th>
                                                    <th style="width: 25%" >TIPO</th>                            
                                                    <th style="width: 45%" >OBSERVACIONES</th>                                             
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th style="width: 15%" ></th>
                                                    <th style="width: 15%" >CANTIDAD</th>
                                                    <th style="width: 25%" >TIPO</th>                            
                                                    <th style="width: 45%" >OBSERVACIONES</th>                            
                                                </tr>
                                            </tfoot>    
                                            <tbody>
                                     		<?php
                                     		     foreach($listaTrabajosFtth->result() as $row){
                                            ?>
                                          	<tr>
                                              <th><?php echo $row->descripcion?></th>
                                              <th><input id="inputCantidadTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" name="inputCantidadTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" type="text" class="form-control form-control-sm"></th>
                                              <th>  <select id="selectTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" name="selectTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" class="select2 selectForm">
                                                                 <option>&nbsp;</option>
                                                           <?php echo $optionsTipoTra?>
                                                           </select>
                                              </th>
                                              <th><input id="inputComentarioTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" name="inputComentarioTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" type="text" class="form-control form-control-sm"></th>
                                          	</tr>           
                                                                    
                                            <?php }?>
                                               
                                        	
                                         </tbody>
                                    	</table>
                                </div>
                                
                                <div class="form-group col-sm-12">
                                 	<div class="form-group form-control-sm">
                                      	<label>OBSERVACIONES</label>
                                        <textarea id="inputObservacion2" name="inputObservacion2" class="form-control textarea-autosize" placeholder="Escriba aqui..."></textarea>
                                        <i class="form-group__bar"></i>
                                    </div>
                            	</div>
                            	
           <!-- ----------------------------------------------------------------------------------------------------------------->
                            	
                            <!-- ------------------------NIVELES DE CALIBRACION----------------------------------- -->
                            
                            <div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 <h6 class="card-body__title" style="text-decoration: underline;">2)Medidas Reflectometricas: Se realiza en el 100% de CTOs (en 01 solo borne) / Atenuacion Max: 23db // ORL Max: >40 db</h6>
                        	</div>
                	      
				<div class="form-group col-sm-12">
				<label style="color: red" class="control-label mb-10 text-left">Debe subir un archivo TXT separado por tabulaciones.</label>
					<label ><a style="color: blue"  href="download/modelos/Modelo_Medidas_Reflectometricas.xlsx" download="modelo_med_reflec.xlsx">Descargar modelo de Carga .txt Aqui!</a></label>
					<div class="fileinput fileinput-new input-group" data-provides="fileinput">
						<div class="form-control" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
						<span class="input-group-addon fileupload btn btn-info btn-anim btn-file"><i class="fa fa-upload"></i> <span class="fileinput-new btn-text">Select file</span> <span class="fileinput-exists btn-text">Change</span>
						<input type="file" id="fileTable" name="fileTable">
						</span> <a href="#" class="input-group-addon btn btn-danger btn-anim fileinput-exists" data-dismiss="fileinput"><i class="fa fa-trash"></i><span class="btn-text"> Remove</span></a> 
					</div>
				</div>
        		
        		<div class="form-group col-sm-12">
    				<button id="preLoadFile" type="button" class="btn btn-success btn-anim"><i class="icon-rocket"></i><span class="btn-text">Subir Informacion</span></button>
    			</div>
        		
                 <div class="form-group col-sm-12 table-responsive">       
                	<table style="font-size: 10px" id="data-table" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    	<thead class="thead-default">
                           <tr role="row">                           
                                <th style="text-align: center;" colspan="9"></th>
                                <th style="text-align: center;" colspan="2">1310 nm</th>
    	                        <th style="TEXT-ALIGN: center;" colspan="2">1550 nm</th>
                              	                                                                                   
                           </tr>
                           <tr role="row">      
                            	<th colspan="1"></th> 
                                <th colspan="1">NODO</th> 
                                <th colspan="1">ODF</th>                          
                                <th colspan="1">CABLE PRIM</th>
    	                        <th colspan="1"># FIBRA</th>
                                <th colspan="1">DIVICAU</th>
    	                        <th colspan="1">DIVISOR</th>
                                <th colspan="1">CTO</th>      
                                <th colspan="1">DISTANCIA (KM)</th>
    	                        <th colspan="1">Atten. Total(db) >23db</th>
                                <th colspan="1">Reflectancia(ORL) >40 db</th>               
                                <th colspan="1">Atten. Total(db) >23db</th>
                                <th colspan="1">Reflectancia(ORL) >40 db</th> 
                            </tr>
                        </thead>  
                        <tfoot>
                            <tr>
                            	<th colspan="1"></th> 
                                <th colspan="1">NODO</th> 
                                <th colspan="1">ODF</th>                          
                                <th colspan="1">CABLE PRIM</th>
    	                        <th colspan="1"># FIBRA</th>
                                <th colspan="1">DIVICAU</th>
    	                        <th colspan="1">DIVISOR</th>
                                <th colspan="1">CTO</th>      
                                <th colspan="1">DISTANCIA (KM)</th>
    	                        <th colspan="1">Atten. Total(db) >23db</th>
                                <th colspan="1">Reflectancia(ORL) >40 db</th>               
                                <th colspan="1">Atten. Total(db) >23db</th>
                                <th colspan="1">Reflectancia(ORL) >40 db</th>                          
                            </tr>
                        </tfoot>                  
                    <tbody id="contBodyTable">
                    	
                     </tbody>
                	</table>
                </div>
                
                                 <div class="form-group col-sm-12">
                                 	<div class="form-group form-control-sm">
                                      	<label>OBSERVACIONES</label>
                                        <textarea id="inputObservacionAdicional2" name="inputObservacionAdicional2" class="form-control textarea-autosize" placeholder="Escriba aqui..."></textarea>
                                        <i class="form-group__bar"></i>
                                    </div>
                            	</div>
                            	<!-- ------------------------------------------------------------------------------- -->
                             </div>
                            <div id="mensajeForm"></div>  
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button id="btnRegFicha2" type="submit" class="btn btn-primary">Save changes</button>
                                    
                                </div>
                            </div>
                        </form>     
                	</div>                	
            	</div>            	
        	</div>
    	</div>
    	<!-- ---------------------------------------------------------------------------------------------------- ----------------------------------------------------------- -->
    	
    	 <!-- ------------------------------------------------MODAL FICHA SISEGOS//SMALLCELL//EBC---------------------------------------------------------------------------------- -->
        <div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalRegistrarFichaSisegos" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">CHECK LIST DE TRABAJOS EN PLANTA EXTERNA SISEGOS</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <div id="content" class="modal-body">
                       <form id="formRegistrarFichaFoSisego" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row">
                           	
                               	
                                 <div class="form-group col-sm-3 col-xs-12 ">
                                 	<label class="control-label mb-10 text-left">SUB PROYECTO</label>  
                                    <input disabled id="txtSubProyecto3" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-3 col-xs-6">
                               		<label class="control-label mb-10 text-left">ITEMPLAN</label>
                                    <input disabled id="txtItemplan3" type="text" class="form-control">
                                </div>
                                 <div class="form-group col-sm-2 col-xs-6">
                                 	<label class="control-label mb-10 text-left">NODO</label>        
                                    <input disabled id="txtNodo3" type="text" class="form-control">                                                               
                                </div>
                            	 <div class="form-group col-sm-2 col-xs-6">
                            	  	<label class="control-label mb-10 text-left">FECHA INICIO</label>
                                    <input disabled id="txtFechaInicio3" type="text" class="form-control">                                   
                            	</div>                            	
                            	<div class="form-group col-sm-2 col-xs-6">                            	 
                            	 	<label class="control-label mb-10 text-left">FECHA FIN</label>
                                    <input disabled id="txtFechaFin3" type="text" class="form-control">
                                </div>
                            	
                            	<!-- <div class="form-group col-sm-3 col-xs-8">
                            		<label class="control-label mb-10 text-left">NOMBRE CUADRILLA</label>
                                    <input disabled id="txtNombreCuadrilla" type="text" class="form-control">
                                </div>-->
                            	<div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 	<h6 class="card-body__title" style="text-decoration: underline;">JEFE DE CUADRILLA</h6>
                            	</div>
                            	<div class="form-group col-sm-3">
                            		<label class="control-label mb-10 text-left" style="color:red">NOMBRE(*)</label>
                                    <input id="txtNombreJefeCuadrilla3" name="txtNombreJefeCuadrilla3" type="text" class="form-control">
                                    
                            	</div>
                            	
                            	<div class="form-group col-sm-3">
                            		<label class="control-label mb-10 text-left" style="color:red">CODIGO(*)</label>
                                    <input id="txtCodigo3" name="txtCodigo3" type="text" class="form-control">
                                    
                                  
                            	</div>
                            	
                            	<div class="form-group col-sm-3">
                            	
                            		<label class="control-label mb-10 text-left">EECC</label>
                                    <input disabled id="txtEECC3" type="text" class="form-control form-control-sm">
                                    
                                    
                            	</div>
                            	<div class="form-group col-sm-3">
                            		<label class="control-label mb-10 text-left" style="color:red">CELULAR(*)</label>
                                    <input id="txtCelular3" name="txtCelular3" type="text" class="form-control form-control-sm">                                    
                            	</div>
                            	
                            	<!-- ---------------------------------------INFORMACION DE TRABAJOS REALIZADOS----------------------------------- -->
                            	<div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 <h6 class="card-body__title" style="text-decoration: underline;">1) INFORMACION DE TRABAJOS REALIZADOS</h6>
                            	</div>
                           
                        		<div class="form-group col-sm-3  col-xs-6">
                        			<label class="control-label mb-10 text-left">COORDENADA X</label>
                                    <input disabled name="txtCoorX3" id="txtCoorX3" type="text" class="form-control form-control-sm">
                                    
                            	</div>
                            	<div class="form-group col-sm-3  col-xs-6">
                            		<label class="control-label mb-10 text-left">COORDENADA Y</label>     
                                    <input disabled id="txtCoorY3" name="txtCoorY3" type="text" class="form-control form-control-sm">
                              	</div>
                              	<div class="form-group col-sm-3 col-xs-6">
                            		<label class="control-label mb-10 text-left">TROBA</label>
                                    <input disabled id="txtTroba3" type="text" class="form-control">
                                </div>
                              	<div class="form-group col-sm-3  col-xs-6">
                            		<label class="control-label mb-10 text-left">SERIE TROBA</label>     
                                    <input disabled id="txtSerieTroba3" name="txtSerieTroba3" type="text" class="form-control form-control-sm">
                              	</div>
                              		<div class="form-group form-group--float col-sm-12 table-responsive">                             	
                                		<table style="font-size: 10px" id="tabla_trabajos" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th style="width: 15%" ></th>
                                                    <th style="width: 15%" >CANTIDAD</th>
                                                    <th style="width: 25%" >TIPO</th>                            
                                                    <th style="width: 45%" >OBSERVACIONES</th>                                             
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th style="width: 15%" ></th>
                                                    <th style="width: 15%" >CANTIDAD</th>
                                                    <th style="width: 25%" >TIPO</th>                            
                                                    <th style="width: 45%" >OBSERVACIONES</th>                            
                                                </tr>
                                            </tfoot>    
                                            <tbody>
                                     		<?php
                                     		     foreach($listaTrabajosSisegos->result() as $row){
                                            ?>
                                          	<tr>
                                              <th><?php echo $row->descripcion?></th>
                                              <th><input id="inputCantidadTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" name="inputCantidadTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" type="text" class="form-control form-control-sm"></th>
                                              <th>  <select id="selectTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" name="selectTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" class="select2 selectForm">
                                                                 <option>&nbsp;</option>
                                                           <?php echo $optionsTipoTra?>
                                                           </select>
                                              </th>
                                              <th><input id="inputComentarioTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" name="inputComentarioTrabajo<?php echo $row->id_ficha_tecnica_trabajo?>" type="text" class="form-control form-control-sm"></th>
                                          	</tr>           
                                                                    
                                            <?php }?>
                                               
                                        	
                                         </tbody>
                                    	</table>
                                </div>
                                
                                <div class="form-group col-sm-12">
                                 	<div class="form-group form-control-sm">
                                      	<label>OBSERVACIONES</label>
                                        <textarea id="inputObservacion3" name="inputObservacion3" class="form-control textarea-autosize" placeholder="Escriba aqui..."></textarea>
                                        <i class="form-group__bar"></i>
                                    </div>
                            	</div>
                            	
           <!-- ----------------------------------------------------------------------------------------------------------------->
                            	
                <!-- ------------------------ NIVELES REFLECTOMETRICAS ----------------------------------- -->
                
                <div class="form-group col-sm-12" style="margin-bottom: 5px;">
                	 <h6 class="card-body__title" style="text-decoration: underline;">2)Medidas Reflectometricas End To End:</h6>
            	</div>
               
                 <div class="form-group col-sm-12 table-responsive">       
                	<table style="font-size: 10px" id="data-table" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    	<thead class="thead-default">
                           <!-- 
                          <tr role="row">
                                <th style="text-align: center;" colspan="5"></th>
                                <th style="text-align: center;" colspan="2">EQUIPO OTDR: AXS110</th>                           	                                                                                   
                           </tr>
                           <tr role="row">
                                <th style="text-align: center;" colspan="5"></th>
                                <th style="text-align: center;" colspan="1">ANCHO PULSO:30</th> 
                                <th style="text-align: center;" colspan="1">N/HELIX:1.01</th>                      	                                                                                   
                           </tr>
                           <tr role="row">
                                <th style="text-align: center;" colspan="5"></th>
                                <th style="text-align: center;" colspan="1">TIEMPO: REAL</th>    
                                 <th style="text-align: center;" colspan="1">VENTANA: 1550</th>                          	                                                                                   
                           </tr>
                           -->
                           <tr role="row">
                            	<th colspan="1"></th> 
                                <th colspan="1">CABLE</th> 
                                <th colspan="1">URA</th>                          
                                <th colspan="1">ASIGNACION ORIGEN</th>
    	                        <th colspan="1">ASIGNACION EXTREMO</th>
                                <th colspan="1">DISTANCIA OPTICA(KM)</th>
    	                        <th colspan="1">ATT TOTAL(dB)</th>                                
                            </tr>
                        </thead>
                                   
                    	<tbody>
                    		 <tr>
                                 <th>1</th>
                                 <th><input id="inputCable1" 	name="inputCable1" 		type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputUra1" 		name="inputUra1" 		type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAsigOri1" 	name="inputAsigOri1" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAsigExt1" 	name="inputAsigExt1" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputDistOpti1"	name="inputDistOpti1" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAttTotal1"	name="inputAttTotal1" 	type="text" class="form-control form-control-sm input-7"></th>
                             </tr>
                             <tr>
                                 <th>2</th>
                                 <th><input id="inputCable2" 	name="inputCable2" 		type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputUra2" 		name="inputUra2" 		type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAsigOri2" 	name="inputAsigOri2" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAsigExt2" 	name="inputAsigExt2" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputDistOpti2"	name="inputDistOpti2" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAttTotal2"	name="inputAttTotal2" 	type="text" class="form-control form-control-sm input-7"></th>
                             </tr>
                             <tr>
                                 <th>3</th>
                                 <th><input id="inputCable3" 	name="inputCable3" 		type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputUra3" 		name="inputUra3" 		type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAsigOri3" 	name="inputAsigOri3" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAsigExt3" 	name="inputAsigExt3" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputDistOpti3"	name="inputDistOpti3" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAttTotal3"	name="inputAttTotal3" 	type="text" class="form-control form-control-sm input-7"></th>
                             </tr>
                     	</tbody>
                	</table>
                </div>
                
                <div class="form-group col-sm-12" style="margin-bottom: 5px;">
                	 <h6 class="card-body__title" style="text-decoration: underline;">3)Medidas de Potencia : Atenuacion Max : >-11db en CTO / >-12db Cliente Sisego/Small Cell / EBC:</h6>
            	</div>
               
                 <div class="form-group col-sm-12 table-responsive">       
                	<table style="font-size: 10px" id="data-table" class="table table-hover display  pb-30 table-striped table-bordered nowrap">
                    	<thead class="thead-default">
                           <!-- 
                          <tr role="row">
                                <th style="text-align: center;" colspan="5"></th>
                                <th style="text-align: center;" colspan="2">EQUIPO OTDR: AXS110</th>                           	                                                                                   
                           </tr>
                           <tr role="row">
                                <th style="text-align: center;" colspan="5"></th>
                                <th style="text-align: center;" colspan="1">ANCHO PULSO:30</th> 
                                <th style="text-align: center;" colspan="1">N/HELIX:1.01</th>                      	                                                                                   
                           </tr> -->
                           <tr role="row">
                           		<th style="text-align: center;" colspan="1"></th>    
                                <th style="text-align: center;" colspan="1">Equipo Origen</th>
                                <th style="text-align: center;" colspan="1">URA</th>    
                                <th style="text-align: center;" colspan="1">Long. FO /Ura - CTO</th> 
                                <th style="text-align: center;" colspan="2">CTO / NAP</th> 
                                <th style="text-align: center;" colspan="1">Long. FO Acomet.</th> 
                                <th style="text-align: center;" colspan="1">CLIENTE</th> 
                           </tr>
                          
                           <tr role="row">
                            	<th colspan="1"></th> 
                                <th colspan="1">PUERTO ORIGEN</th> 
                                <th colspan="1">INPUT (DB)</th>                          
                                <th colspan="1">APROX. (KM)</th>
    	                        <th colspan="1">NÂ° CTO CUENTA</th>
    	                        <th colspan="1">OUTPUT(dB)</th>   
                                <th colspan="1">APROX. (KM)</th>
    	                        <th colspan="1">OUTPUT (dB)</th>                            
                            </tr>
                        </thead>
                                   
                    	<tbody>
                    		 <tr>
                                 <th>1</th>
                                 <th><input id="inputPuerto1" 		name="inputPuerto1" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputDbUra1" 		name="inputDbUra1" 		type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputCtoAprox1" 	name="inputCtoAprox1" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputNumCto1" 		name="inputNumCto1" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputOutDbCto1"		name="inputOutDbCto1" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAcomeAprox1"	name="inputAcomeAprox1" type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputOutDbCli1"		name="inputOutDbCli1" 	type="text" class="form-control form-control-sm input-7"></th>
                      		 </tr>
                             <tr>
                                 <th>2</th>
                                 <th><input id="inputPuerto2" 		name="inputPuerto2" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputDbUra2" 		name="inputDbUra2" 		type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputCtoAprox2" 	name="inputCtoAprox2" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputNumCto2" 		name="inputNumCto2" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputOutDbCto2"		name="inputOutDbCto2" 	type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputAcomeAprox2"	name="inputAcomeAprox2" type="text" class="form-control form-control-sm input-7"></th>
                      		     <th><input id="inputOutDbCli2"		name="inputOutDbCli2" 	type="text" class="form-control form-control-sm input-7"></th>
                      		 </tr>
                     	</tbody>
                	</table>
                </div>
                                 <div class="form-group col-sm-12">
                                 	<div class="form-group form-control-sm">
                                      	<label>OBSERVACIONES</label>
                                        <textarea id="inputObservacionAdicional3" name="inputObservacionAdicional3" class="form-control textarea-autosize" placeholder="Escriba aqui..."></textarea>
                                        <i class="form-group__bar"></i>
                                    </div>
                            	</div>
                            	<!-- ------------------------------------------------------------------------------- -->
                             </div>
                            <div id="mensajeForm3"></div>  
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button id="btnRegFicha3" type="submit" class="btn btn-primary">Save changes</button>
                                    
                                </div>
                            </div>
                        </form>     
                	</div>                	
            	</div>            	
        	</div>
    	</div>
    	<!-- ---------------------------------------------------------------------------------------------------- ----------------------------------------------------------- -->
    		<!-- ---------------------------------------------------------------------------------------------------- ----------------------------------------------------------- -->
    	
  <!-- EDITAR KIT DE MATERIALES -->
  
  <div class="modal fade" id="modalKitMaterial" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                    <img style="width: 100px; heigth:40px" src="<?php echo base_url();?>public/img/logo/tdp.png">
                      
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">ESTE MODAL
                        <form id="forEditarKitMate" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row"> 
                           		<div class="form-group col-sm-6	 col-xs-12">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">DIRECCION</label>  
                                    <input style="height: 33px;" id="txtDireccion" name="txtDireccion" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-2 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">NUMERO</label>  
                                    <input style="height: 33px;" id="txtNumero" name="txtNumero" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-2 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left"># PISOS</label>  
                                    <input style="height: 33px;" id="txtPisos" name="txtPisos" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-2 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left"># DPTOS</label>  
                                    <input style="height: 33px;" id="txtDepartamentos" name="txtDepartamentos" type="text" class="form-control">                                                                      
                                </div>     

                                <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">TIPO TRABAJO</label>  
                                    <select style="height: 33px;width: 100%;" id="selectTipoTrabajo" name="selectTipoTrabajo" class="select2 selectForm">
                                             <option value="1">SUBTERRANEO</option>
                                             <option value="2">AEREO</option>
                                   	</select>
                                </div>
                                 <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CTO</label>  
                                    <select style="height: 33px;width: 100%;" id="selectInstala" name="selectInstala" class="select2 selectForm">                                             
                                             <option value="SI">SI</option>
                                             <option value="NO">NO</option>                                              
                                   	</select>                                          
                                </div>
                                 <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CAMARA</label>  
                                    <select style="height: 33px;width: 100%;" id="selectCamara" name="selectCamara" class="select2 selectForm">                                             
                                             <option value="SI">SI</option>
                                             <option value="NO">NO</option>                                              
                                   	</select>                                          
                                </div>
                           </div>
                          
                           <div class="row">                           	
                               	<div class="form-group col-sm-12 table-responsive">                             	
                                		<table style="color:black;font-size: 10px;font-weight: bold;margin-bottom: inherit;" id="tabla_trabajos" border="1">
                                            <thead>
                                                <tr>
                                                    <th style="width: 15%;" >CODIGO</th>
                                                    <th style="width: 49%;" >MATERIAL</th>                                                      
                                                    <th style="width: 10%;text-align: center;" >TOTAL</th>                                     
                                                </tr>
                                            </thead>
                                            <tbody id="bodyTable">
                                       		
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                               <div class="row">     
                                <div id="mensajeForm"></div>  
                                <div class="form-group" style="text-align: right;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                        <button id="btnEditKitMat" type="submit" class="btn btn-primary">Save changes</button>                                    
                                    </div>
                                </div>                            
                            </div>
                        </form>    
                    </div>
            	</div>
            </div>
    	</div>
<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>    	
<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>          
<script src="<?php echo base_url();?>public/js/sinfix.js?v=<?php echo time();?>"></script>
<script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js?v=<?php echo time();?>"></script>

        <script type="text/javascript">
        $(document).ready(function(){
            $('#contTabla').css('display', 'block');
        });
</script>