<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">

        <link rel="stylesheet" href="<?php echo base_url();?>public/css/galeria_fotos.css?v=<?php echo time();?>">

         <style type="text/css">
           
            .select2-dropdown {
              z-index: 100000;
            }
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
    </head>

    <body data-ma-theme="entel">    
        <main class="main">
            <div class="page-loader">
                <div class="page-loader__spinner">
                    <svg viewBox="25 25 50 50">
                        <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                    </svg>
                </div>
            </div>

            <header class="header">
                <div class="navigation-trigger" data-ma-action="aside-open" data-ma-target=".sidebar">
                    <div class="navigation-trigger__inner">
                        <i class="navigation-trigger__line"></i>
                        <i class="navigation-trigger__line"></i>
                        <i class="navigation-trigger__line"></i>
                    </div>
                </div>

                <div class="header__logo hidden-sm-down" style="text-align: center;">
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃƒÂº"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
                </div>

                <ul class="top-nav">


           



                    <li class="hidden-xs-down">
                        <a href="#" data-toggle="dropdown" aria-expanded="false">
                            <i class="zmdi zmdi-power"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">

						                            <a href="logOut" class="dropdown-item">Cerrar SesiÃƒÂ³n</a>
                        </div>
                    </li>
                </ul>
            </header>

            <aside class="sidebar sidebar--hidden">
                <div class="scrollbar-inner">
                    <div class="user">
                        <div class="user__info" data-toggle="dropdown">
                            <img class="user__img" src="<?php echo base_url();?>public/demo/img/profile-pics/8.jpg" alt="">
                            <div>
                                <div class="user__name"><?php echo $this->session->userdata('usernameSession')?></div>
                                <div class="user__email"><?php echo $this->session->userdata('descPerfilSession')?></div>
                            </div>
                        </div>

                        
                    </div>

                    <ul class="navigation">

						         <?php echo $opciones?>
                    </ul>
                </div>
            </aside>

            
            <section class="content content--full">
           
		                   <div class="content__inner">
                                    <h2>REGISTRO DE FICHA TECNICA</h2>
		   				                    <div class="card">		   				                        
			                        <div class="card-block">	   				                         
                                        <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>
                                        <select id="selectSubProy" name="selectSubProy" class="select2" onchange="filtrarTabla()" multiple>
                                        <option>&nbsp;</option>
                                        <?php 
                                                foreach($listaSubProy->result() as $row){
                                            ?>
                                             <option value="<?php echo $row->subProyectoDesc ?>"><?php echo $row->subProyectoDesc ?></option>
                                        <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>EECC</label>

                                        <select id="selectEECC" name="selectEECC" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaEECC->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->empresaColabDesc ?>"><?php echo $row->empresaColabDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>ZONAL</label>

                                        <select id="selectZonal" name="selectZonal" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($listaZonal->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->zonalDesc ?>"><?php echo $row->zonalDesc ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>
                                
                    
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>MES PREVISTO EJECUCION</label>

                                        <select id="selectMesEjec" name="selectMesEjec" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                       <option value="ENE">ENERO</option>
                                       <option value="FEB">FEBRERO</option>
                                       <option value="MAR">MARZO</option>
                                       <option value="ABR">ABRIL</option>
                                       <option value="MAY">MAYO</option>
                                       <option value="JUN">JUNIO</option>
                                       <option value="JUL">JULIO</option>
                                       <option value="AGO">AGOSTO</option>
                                       <option value="SEP">SEPTIEMBRE</option>
                                       <option value="OCT">OCTUBRE</option>
                                       <option value="NOV">NOVIEMBRE</option>
                                       <option value="DIC">DICIEMBRE</option>
                                       
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>SITUACION</label>

                                       <select id="selectSituacion" name="selectSituacion" class="select2" onchange="filtrarTabla()">
                                           <option>&nbsp;</option>
                                           <option value="1">APROBADO</option>
                                           <option value="2">RECHAZADO</option>
                                           <option value="0">PENDIENTE</option>                                    
                                        </select>            
                                    </div>
                                </div>
                            </div>
                            <div id="contTabla" class="table-responsive">
	                            <?php echo $tablaAsigGrafo?>
                           </div>
		   				                        </div>
		   				                    </div>
		   				                    
		<!-- -------------------------------------------------------------------------------------------------------------------------------- -->   				                    
        <div class="modal fade" id="modalRegistrarFicha" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">CHECK LIST DE TRABAJOS EN PLANTA EXTERNA</h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div id="content" class="modal-body">
                       <form id="formRegistrarFicha" method="post" class="form-horizontal">
                           <div class="row">
                           	
                               	<div class="form-group form-group--float col-sm-3">
                                    <input style="font-weight: bold;color: black;" disabled id="txtItemplan" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;    font-weight: bold;">ITEMPLAN</label>
                                    <i class="form-group__bar"></i>
                                </div>
                                 <div class="form-group form-group--float col-sm-6">
                                    <input style="font-weight: bold;color: black;" disabled id="txtSubProyecto" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;    font-weight: bold;">SUB PROYECTO</label>
                                    <i class="form-group__bar"></i>
                                </div>
                                 <div class="form-group form-group--float col-sm-3">
                                    <input style="font-weight: bold;color: black;" disabled id="txtNodo" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;    font-weight: bold;">NODO</label>
                                    <i class="form-group__bar"></i>
                                </div>
                                 
                            	
                            	 <div class="form-group form-group--float col-sm-2">
                                    <input style="font-weight: bold;color: black;" disabled id="txtFechaInicio" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;    font-weight: bold;">FECHA INICIO</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                            	
                            	 <div class="form-group form-group--float col-sm-2">
                                    <input style="font-weight: bold;color: black;" disabled id="txtFechaFin" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;    font-weight: bold;">FECHA FIN</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                            	<div class="form-group form-group--float col-sm-2">
                                    <input style="font-weight: bold;color: black;" disabled id="txtTroba" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;    font-weight: bold;">TROBA</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                            	<div class="form-group form-group--float col-sm-6">
                                    <input style="font-weight: bold;color: black;" disabled id="txtNombreCuadrilla" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;    font-weight: bold;">NOMBRE CUADRILLA</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                            	<div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 <h6 class="card-body__title" style="text-decoration: underline;">JEFE DE CUADRILLA</h6>
                            	</div>
                            	<div class="form-group form-group--float col-sm-3">
                                    <input id="txtNombreJefeCuadrilla" name="txtNombreJefeCuadrilla" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;color:red">NOMBRE(*)</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                            	
                            	<div class="form-group form-group--float col-sm-3">
                                    <input id="txtCodigo" name="txtCodigo" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;color:red">CODIGO(*)</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                            	
                            	<div class="form-group form-group--float col-sm-3">
                                    <input style="font-weight: bold;color: black;" disabled id="txtEECC" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;">EECC</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                            	<div class="form-group form-group--float col-sm-3">
                                    <input id="txtCelular" name="txtCelular" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;color:red">CELULAR(*)</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                	<!-- ---------------------------------------INFORMACION DE TRABAJOS REALIZADOS----------------------------------- -->
                            	<div class="form-group col-sm-12" style="margin-bottom: 5px;">
                            	 <h6 class="card-body__title" style="text-decoration: underline;">1) INFORMACION DE TRABAJOS REALIZADOS</h6>
                            	</div>                    
                        		<div class="form-group form-group--float col-sm-2">
                                    <input style="font-weight: bold;color: black;" name="txtCoorX" id="txtCoorX" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;">COORDENADA X</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                            	<div class="form-group form-group--float col-sm-2">
                                    <input style="font-weight: bold;color: black;" id="txtCoorY" name="txtCoorY" type="text" class="form-control form-control-sm form-control--active">
                                    <label style="left: auto;">COORDENADA Y</label>
                                    <i class="form-group__bar"></i>
                            	</div>
                         <div class="form-group form-group--float col-sm-12">       
                            	
            		<table style="width: 100%; font-size: 10px" id="data-table" class="table table-bordered">
                        <thead class="thead-default">
                            <tr>
                                <th style="width: 15%" ></th>
                                <th style="width: 15%" >CANTIDAD</th>
                                <th style="width: 25%" >TIPO</th>                            
                                <th style="width: 45%" >OBSERVACIONES</th>                                             
                            </tr>
                        </thead>
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
                            	                  
                         <div class="form-group form-group--float col-sm-12">       
                            	<table style="width: 100%; font-size: 10px" id="data-table" class="table table-bordered">
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
                    <tbody>
                    
                    <?php                                                    
                            foreach($listaNivelesCali->result() as $row){                      
                    ?>
                      <tr>
                         <th><?php echo $row->descripcion?></th>
                         <th <?php echo (($row->id_ficha_tecnica_nivel_calibra != 1)? 'style="
    background: #969696;"' : '')?> ><input <?php echo (($row->id_ficha_tecnica_nivel_calibra != 1)? 'disabled' : '')?> id="opt1_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="opt1_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-mask input-3" data-mask="000"></th>
                         <th><input id="opt2_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="opt2_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-mask input-7" data-mask="000"></th>
                         <th><input id="ch30_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="ch30_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-mask input-39" data-mask="000"></th>
                     	 <th><input id="ch75_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="ch75_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-mask input-42" data-mask="000"></th>
                         <th><input id="ch113_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="ch113_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-mask input-45" data-mask="000"></th>
                         <th><input id="snr_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" name="snr_<?php echo $row->id_ficha_tecnica_nivel_calibra?>" type="text" class="form-control form-control-sm input-mask input-32" data-mask="000"></th>
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
<!-- --------------------------------------------------------FIN DEL MODAL 1------------------------------------------------------------------------ -->   				                    

<!-- -------------------------------------------------------------inicio modal 2------------------------------------------------------------------- -->   				                    
        <div class="modal fade" id="modalEvaluarFicha" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <img style="width: 100px; heigth:40px" src="<?php echo base_url();?>public/img/logo/tdp.png">
                                               <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">CHECK LIST DE TRABAJOS EN PLANTA EXTERNA</h4>
                       
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div id="contFichaEval" class="modal-body">
                        
                    </div>
            </div>
        </div>
	</div>
<!-- --------------------------------------------------------FIN DEL MODAL 2------------------------------------------------------------------------ -->   				                    
	
            </section>
        </main>
        
        
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

        <!-- Javascript -->
        <!-- ..vendors -->
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>

        <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jqvmap/dist/jquery.vmap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jqvmap/dist/maps/jquery.vmap.world.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/salvattore/dist/salvattore.min.js"></script>
        <script src="<?php echo base_url();?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/moment/min/moment.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

   <!--  tables -->
		<script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>        
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
       
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/autosize/dist/autosize.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script> 
        <script src="<?php echo base_url();?>public/js/sinfix.js?v=<?php echo time();?>"></script>   
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>        
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        
        
        
        <script type="text/javascript">
        $( ".input-3" ).keyup(function() {
        	if(this.value > 3){
       		 $(this).css("color","red");
            	}
      	});
        $( ".input-7" ).keyup(function() {
        	if(this.value > 7){
       		 $(this).css("color","red");
            	}
      	});
        $( ".input-39" ).keyup(function() {
        	if(this.value > 39){
       		 $(this).css("color","red");
            	}
      	});
        $( ".input-42" ).keyup(function() {
        	if(this.value > 42){
       		 $(this).css("color","red");
            	}
      	});
        $( ".input-45" ).keyup(function() {
        	if(this.value > 45){
       		 $(this).css("color","red");
            	}
      	});
        $( ".input-32" ).keyup(function() {
        	if(this.value > 32){
       		 $(this).css("color","red");
            	}
      	});
        $('#formRegistrarFicha')
    	.bootstrapValidator({
    	    container: '#mensajeForm',
    	    feedbackIcons: {
    	        valid: 'glyphicon glyphicon-ok',
    	        invalid: 'glyphicon glyphicon-remove',
    	        validating: 'glyphicon glyphicon-refresh'
    	    },
    	    excluded: ':disabled',
    	    fields: {
    	    	txtNombreJefeCuadrilla: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar Nombre Jefe Cuadrilla.</p>'
    	                }
    	             }
     	    	   },
      	    	  txtCodigo: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe Ingresar el Codigo del Jefe Cuadrilla.</p>'
    	                }
    	             }
     	    	   }
    	    }
    	}).on('success.form.bv', function(e) {
    		e.preventDefault();
    		
    		
    	    var $form    = $(e.target),
    	        formData = new FormData(),
    	        params   = $form.serializeArray(),
    	        bv       = $form.data('bootstrapValidator');
				
    		    $.each(params, function(i, val) {
    		        formData.append(val.name, val.value);
    		    });
    		    
				var itemplan = $('#btnRegFicha').attr('data-item');
    	    	formData.append('itemplan', itemplan);


    		    $.ajax({
			        data: formData,
			        url: "saveFT",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				  .done(function(data) {  
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){   
         	    		$('#contTabla').html(data.tablaAsigGrafo)
         	    	    initDataTable('#data-table');             	    	
         	    		$('#modalRegistrarFicha').modal('toggle');             	    	
         	    		mostrarNotificacion('success','OperaciÃƒÂ³n ÃƒÂ©xitosa.', 'Se registro correcamente!');
         	    	}else if(data.error == 1){     				
         				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
         			}
     		  });
    	});
    	
		function openModalRregistrarFicha(component){
			document.getElementById("formRegistrarFicha").reset();
			$('.selectForm').val('3').trigger('change');//POR DEFENTE 1 = NUEVO 			
			var itemplan = $(component).attr('data-itm');

     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getInfIte',
     	    	data	:	{itemplan : itemplan},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){   
         	    	$('#txtItemplan').val(data.itemplan);
         	    	$('#txtSubProyecto').val(data.subpro);
         	    	$('#txtNodo').val(data.nodo);
         	    	$('#txtFechaInicio').val(data.fec_inicio);
         	    	$('#txtFechaFin').val(data.fec_fin);
         	    	$('#txtTroba').val(data.troba);
         	    	$('#txtNombreCuadrilla').val(data.nombreCuadri);
         	    	$('#txtEECC').val(data.eecc);    
         	    	$('#btnRegFicha').attr('data-item',itemplan);
     	    		$('#modalRegistrarFicha').modal('toggle')
     			}else if(data.error == 1){     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
     		  });
		}

		function registrarFicha(component){
			document.getElementById("formRegistrarFicha").reset();
			var itemplan = $(component).attr('data-itm');

     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getInfIte',
     	    	data	:	{itemplan : itemplan},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){   
         	    	$('#txtItemplan').val(data.itemplan);
         	    	$('#txtSubProyecto').val(data.subpro);
         	    	$('#txtNodo').val(data.nodo);
         	    	$('#txtFechaInicio').val(data.fec_inicio);
         	    	$('#txtFechaFin').val(data.fec_fin);
         	    	$('#txtTroba').val(data.troba);
         	    	$('#txtNombreCuadrilla').val(data.nombreCuadri);
         	    	$('#txtEECC').val(data.eecc);    	          	    	   
     	    		
     	    		$('#modalRegistrarFicha').modal('toggle')
     			}else if(data.error == 1){
     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
     		  });
		}
		
        function filtrarTabla(){
   	        var subProy = $.trim($('#selectSubProy').val()); 
         	 var eecc = $.trim($('#selectEECC').val()); 
         	 var zonal = $.trim($('#selectZonal').val()); 
          	var situacion = $.trim($('#selectSituacion').val()); 
           	var mes = $.trim($('#selectMesEjec').val());  
           	
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'getBandejaFT',
     	    	data	:	{subProy  :	subProy,
             	    		eecc      : eecc,
             	    	    zonal     : zonal,
              	    	    situacion : situacion,
      	    	            mes : mes},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){           	    	          	    	   
     	    		$('#contTabla').html(data.tablaAsigGrafo)
     	    	    initDataTable('#data-table');
     			}else if(data.error == 1){
     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
     		  });
      	}

        function getFichaToEval(component){
           // console.log('getFichaToEval');
			var itemplan = $(component).attr('data-itm');
			
     	    $.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'evalFT',
     	    	data	:	{itemplan : itemplan},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){           	    	          	    	   
     	    		$('#contFichaEval').html(data.dataHTML);
     	    	  	$('#modalEvaluarFicha').modal('toggle');
     			}else if(data.error == 1){
     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
     		  });
      	}

        function getFichaToEvalFO(component){
            // console.log('getFichaToEval');
 			var itemplan = $(component).attr('data-itm');
 			
      	    $.ajax({
      	    	type	:	'POST',
      	    	'url'	:	'evalFTFO',
      	    	data	:	{itemplan : itemplan},
      	    	'async'	:	false
      	    })
      	    .done(function(data){
      	    	var data	=	JSON.parse(data);
      	    	if(data.error == 0){           	    	          	    	   
      	    		$('#contFichaEval').html(data.dataHTML);
      	    	  	$('#modalEvaluarFicha').modal('toggle');
      			}else if(data.error == 1){
      				
      				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
      			}
      		  });
       	}
       	
    function viewFichaEval(component){
			var itemplan = $(component).attr('data-itm');
			
  	    $.ajax({
  	    	type	:	'POST',
  	    	'url'	:	'viewFE',
  	    	data	:	{itemplan : itemplan},
  	    	'async'	:	false
  	    })
  	    .done(function(data){
  	    	var data	=	JSON.parse(data);
  	    	if(data.error == 0){           	    	          	    	   
  	    		$('#contFichaEval').html(data.dataHTML);
  	    	  	$('#modalEvaluarFicha').modal('toggle');
  			}else if(data.error == 1){
  				
  				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
  			}
  		  });
   	}
    
    function viewFichaEvalFO(component){
			var itemplan = $(component).attr('data-itm');
			
  	    $.ajax({
  	    	type	:	'POST',
  	    	'url'	:	'viewFEFO',
  	    	data	:	{itemplan : itemplan},
  	    	'async'	:	false
  	    })
  	    .done(function(data){
  	    	var data	=	JSON.parse(data);
  	    	if(data.error == 0){           	    	          	    	   
  	    		$('#contFichaEval').html(data.dataHTML);
  	    	  	$('#modalEvaluarFicha').modal('toggle');
  			}else if(data.error == 1){
  				
  				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
  			}
  		  });
   	}

    function viewFichaEvalSI(component){
		var itemplan = $(component).attr('data-itm');
		
	    $.ajax({
	    	type	:	'POST',
	    	'url'	:	'viewFESI',
	    	data	:	{itemplan : itemplan},
	    	'async'	:	false
	    })
	    .done(function(data){
	    	var data	=	JSON.parse(data);
	    	if(data.error == 0){           	    	          	    	   
	    		$('#contFichaEval').html(data.dataHTML);
	    	  	$('#modalEvaluarFicha').modal('toggle');
			}else if(data.error == 1){
				
				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
			}
		  });
	}
   	
        function validarFic(Component){
        	var accion = $(Component).attr('data-acc');
        	var ficha = $(Component).attr('data-fic');
        	var itemplan = $(Component).attr('data-item');
        	var arrayTrabajos = new Array();
        	var arrayNiveles = new Array();
        	
        	$("input[name='checkTrabajos']").each( function () {
        		if($(this).is(':checked')){
           			var idtt = $(this).val();
              		var jsonArg1 = new Object();
              		jsonArg1.id_ficha_tecnica_x_tipo_trabajo = idtt;
                    jsonArg1.flg_validado = '1';
                    jsonArg1.comentario_vali = $('#inputComentarioTrabajo'+idtt).val();
                    arrayTrabajos.push(jsonArg1);
        		}
        	})
			var jsonArray = JSON.stringify(arrayTrabajos);
			
        	$("input[name='checkNiveles']").each( function () {
        		if($(this).is(':checked')){
       			 	var idtn = $(this).val();
          			 var jsonArg1 = new Object();
            		  jsonArg1.id_ficha_tecnica_x_nivel_calibra = idtn;
                      jsonArg1.flg_validado = '1';
                      jsonArg1.comentario_vali = $('#inputComentarioNivel'+idtn).val();
                      arrayNiveles.push(jsonArg1);
        		}     
        	})
			
			var jsonArray2 = JSON.stringify(arrayNiveles);
			
			$.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'saveVali',
     	    	data	:	{checksTrabajos : jsonArray,
     	    				 checksNiveles  : jsonArray2,
         	    		     estado 		: accion,
          	    		     ficha          : ficha,
            	    		 itemplan		: itemplan},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){           	    	          
     	    		$('#contTabla').html(data.tablaAsigGrafo)
     	    	    initDataTable('#data-table'); 	    	   
     	    		$('#modalEvaluarFicha').modal('toggle');
     	    		mostrarNotificacion('success','OperaciÃƒÂ³n ÃƒÂ©xitosa.', 'Se registro correcamente!');
     			}else if(data.error == 1){     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
     		  });
        	        	
           
        }

        function getFichaToEvalSI(component){
            // console.log('getFichaToEval');
 			var itemplan = $(component).attr('data-itm');
 			
      	    $.ajax({
      	    	type	:	'POST',
      	    	'url'	:	'evalFTSI',
      	    	data	:	{itemplan : itemplan},
      	    	'async'	:	false
      	    })
      	    .done(function(data){
      	    	var data	=	JSON.parse(data);
      	    	if(data.error == 0){           	    	          	    	   
      	    		$('#contFichaEval').html(data.dataHTML);
      	    	  	$('#modalEvaluarFicha').modal('toggle');
      			}else if(data.error == 1){
      				
      				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
      			}
      		  });
       	}
        
        function viewFichaEvalOBP(component) {
            var itemplan = $(component).attr('data-itm');
            $.ajax({
                type	:	'GET',
                'url'	:	'makePDFOBP',
                data	:	{ itm : itemplan,
                            flg : 1 },
                'async'	:	false
            })
            .done(function(data){
                var data =	JSON.parse(data);    	    	          	    	   
                $('#contFichaEval').html(data.dataHTML);
                $('#modalEvaluarFicha').modal('toggle');
            });
        }

        function validarFicOBP(Component) {
            var accion      = $(Component).attr('data-acc');
            var ficha       = $(Component).attr('data-fic');
            var itemplan    = $(Component).attr('data-item');
            var observacion = $('#observacionOP').val();

            $.ajax({
                type : 'POST',
                url  : 'saveValidacionFichaOBP',
                data : { estado      : accion,
                        ficha       : ficha,
                        itemplan    : itemplan,
                        observacion : observacion }
            }).done(function(data){
                var data = JSON.parse(data);
                if(data.error == 0){           	    	          
                    $('#contTabla').html(data.tablaAsigGrafo)
                    initDataTable('#data-table'); 	    	   
                    $('#modalEvaluarFicha').modal('toggle');
                    mostrarNotificacion('success','Operaci&oacute;n Exitosa.', 'Se registro correcamente!');
                }else if(data.error == 1){     				
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>