<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="public/img/iconos/iconfinder_movistar.png">
        
        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/dropzone/downloads/css/dropzone.css" />
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
          <style>
                .subir{
                padding: 5px 10px;
                background: #f55d3e;
                color:#fff;
                border:0px solid #fff;    	     
                width: 40%;
                border-radius: 25px;
            }
             
            .subir:hover{
                color:#fff;
                background: #f7cb15;
            }
            
            #divMapCoordenadas{
            	height: 450px;    
                width: 800px;  
            }
            
            #pac-input {
                    background-color: #fff;
                    font-family: Roboto;
                    font-size: 15px;
                    font-weight: 300;
                    margin-left: 12px;
                    padding: 0 11px 0 13px;
                    text-overflow: ellipsis;
                    width: 400px;
                  }
        </style>
        <script>


        
        </script>
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
                   <a href="https://www.movistar.com.pe/" title="Movistar"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Movistar" style="width: 36%; margin-left: -51%"></a>
                </div>
 <?php include('application/views/v_opciones.php'); ?>
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

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">View Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </div>

                    <ul class="navigation">

						         <?php echo $opciones?>
                    </ul>
                </div>
            </aside>

         
            <section class="content content--full">
            	<div id='textMensaje'>
                   
                </div>
           
               <div class="content__inner">
                    <h2>REGISTRO DE ITEMPLAN</h2> 
	                    <div class="card">			                        
	                        <div class="card-block">
                                <form id="formAddPlanobra" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>FASE</label>
                                                <select id="selectFase" name="selectFase" class="select2 form-control" onchange="validarFasePorProyecto()">
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listafase->result() as $row){                      
                                                        ?> 
														<!--
                                                         <?php if($row->faseDesc == '2019'){//SOLO FASE 2019 A PARTIR DEL 20.12.2018?>
															<option value="<?php echo $row->idFase ?>"><?php echo $row->faseDesc ?></option>
                                                         <?php }?>
														 -->
														 <?php if($row->faseDesc == '2020'){//PEDIDO DE CINTHUA 21.11.2019?>
															<option value="<?php echo $row->idFase ?>" selected><?php echo $row->faseDesc ?></option>
                                                         <?php }?>
                                                         <?php }?>
                                                     
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                 <label>PROYECTO</label>
                                                 <select id="selectProy" name="selectProy" class="select2 form-control" onchange="changueProyect()" >
                                                    <option>&nbsp;</option>
                                                      <?php foreach($listaProy->result() as $row){ ?> 
                                                        <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                                         <?php }?>
                                                     
                                                   </select>
                                            </div>
                                        </div>    
                                        <div class="col-sm-4 col-md-4">
                                             <div class="form-group">
                                                   <label>SUBPROYECTO</label>
                                                   <select id="selectSubproy" name="selectSubproy" class="select2 form-control" onchange="marcarFase()">
                                                        <option value="">&nbsp;</option>
                                                   </select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-2 col-md-2">
                                             <div class="form-group">
                                                   <label>PLANIFICACI&Oacute;N</label>
                                                   <select id="selectPlan" name="selectPlan" class="select2 form-control" >
                                                        <option value="">&nbsp;</option>
                                                   </select>
                                            </div>
                                        </div> -->
                                        <div class="col-sm-8 col-md-8" style="width: 800px;height: 500px">
                                            <div class="form-group" id="divMapaCoordenadasXY" style="width: 800px;height: 500px">
                                                <label>UBICACION DE LAS COORDENADAS X Y</label>
                                                <input id="pac-input" class="controls" type="text" placeholder="Buscar">
                                                <div id="divMapCoordenadas"></div>
                                            </div>
                                        </div>
                                         <div class="col-sm-4 col-md-4">
                                             <div class="form-group">
                                                <label>CENTRAL</label>
                                                <select id="selectCentral" name="selectCentral" class="select2 form-control" onchange="changueCentral()" disabled="disabled">
                                                       <option value="">&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>JEFATURA</label>
                                                <input id="inputJefatura" name="inputJefatura" type="text" class="form-control" disabled="disabled">
                                            </div>
                                            <div class="form-group">
                                                <label>ZONAL</label>
                                                    <select id="selectZonal" name="selectZonal" class="select2 form-control"  disabled="disabled">
                                                        <option value="">&nbsp;</option>
                                                    </select>
                                            </div>
                                            <div class="form-group">
                                                <label>EMPRESA COLABORADORA</label>
                                                <select id="selectEmpresaColab" name="selectEmpresaColab" class="select2 form-control" disabled="disabled">
                                                    <option value="">&nbsp;</option>
                                                </select>
                                            </div>
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>COORDENADAS X</label>
                                                <input id="inputCoordX" name="inputCoordX" type="text" class="form-control" onchange="changeXY()"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>COORDENADAS Y</label>
                                                <input id="inputCoordY" name="inputCoordY" type="text" class="form-control" onchange="changeXY()"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            
                                         </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>EMPRESA ELECTRICA</label>
                                                <select id="selectEmpresaEle" name="selectEmpresaEle" class="select2 form-control" >
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaeelec->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idEmpresaElec ?>"><?php echo $row->empresaElecDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                            </div>                                        
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                             <div class="form-group">
                                                <label>INDICADOR (C&Oacute;DIGO &Uacute;NICO)</label>
                                                <input id="inputIndicador" name="inputIndicador" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>NOMBRE DEL PLAN</label>
                                                <input id="inputNombrePlan" name="inputNombrePlan" type="text" class="form-control">
                                            </div>                                        
                                        </div>
                                        <div class="col-sm-4 col-md-4" id="contFecIni">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>FECHA DE INICIO</label>            
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                    <div class="form-group">
                                                        <input id="inputFechaInicio" name="inputFechaInicio" type="text" class="form-control date-picker" placeholder="Pick a date" onchange="recalcular_fecha_prev_ejec()">
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-4" id="contFecPrev">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>FECHA PREV.EJECUCION</label>
                                                <input id="inputFechaPrev" name="inputFechaPrev" type="text" class="form-control" readonly>
                                            </div>                                                
                                        </div>
                                         <div class="col-sm-4 col-md-4" id="contKickoff" style="display: none;">
                                            <div class="form-group">
                                                <label>KICKOFF</label>
                                                <select id="selectKickOff" name="selectKickOff" class="select2 form-control" onchange="validateCoti()">
                                                    <option value="0">NO</option>     
                                                    <option value="1">SI</option>                                                    
                                                </select>
                                            </div>
                                        </div>    
                                        <div class="col-sm-4 col-md-4" id="contCotizacion">
                                            <div class="form-group">
                                                <label>REQUIERE COTIZACION</label>
                                                <select id="selectCotizacion" name="selectCotizacion" class="select2 form-control" onchange="validateCoti()">
                                                    <option value="0">NO</option>
                                                    <option value="1">SI</option>                                                    
                                                </select>
                                            </div>
                                        </div>
                                        
                                        
                                        <div>
                                            <br/>
                                        </div>
                                        <div>
                                            <br/>
                                        </div>
                                        
                                        <div class="col-sm-4 col-md-4" id="divFactorMedicion" style="display: none">
                                            <div class="form-group">
                                                <label id="lblFactorMedicion"></label>
                                                <input id="inputCantidadFactorMedicion" name="inputCantidadFactorMedicion" type="number" class="form-control">
                                                <input type="hidden" id="hfIdFactorMedicion" name="hfIdFactorMedicion" value="0" >
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-4 col-md-4" id="divCoaxial" style="display: none">
                                            <div class="form-group">
                                                <label>FECHA PREV. DE ATENCION COAXIAL</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                    <input placeholder="Pick a date" id="idFechaPreAtencionCoax" name="idFechaPreAtencionCoax" type="text" class="form-control date-picker">
                                                </div>
                                            </div>
                                        </div>
                                		<div class="col-sm-4 col-md-4" id="divFO" style="display: none">
                                		    <div class="form-group">
                                                <label>FECHA PREV. DE ATENCION FO</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                    <input placeholder="Pick a date" id="idFechaPreAtencionFo" name="idFechaPreAtencionFo" type="text" class="form-control date-picker">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div style="display: none;text-align: center;" id="contUploadFileCoti" class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                            <label style="font-size: large;" for="fileupload" class="subir">
                                                <i class="zmdi zmdi-upload"></i>
                                            </label>
                                            <input id="fileupload" name="fileupload" type="file" onchange='cambiar()' style='display: none;'>
                                            <div id="info">Seleccione un archivo</div>
                                            </div>
                                        </div>
                                        
                                        <div style="display: none" id="contItemMadre" class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>ITEMPLAN MADRE</label>
                                                    <input maxlength="20" id="inputItemMadre" name="inputItemMadre" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <!--    CONTENIDO OBRAS PUBLICAS    --> 
                                        
                                        <div id="contObrasPublicas" style="display: none" class="col-sm-12 col-md-12">
                                            <div class="row">
                                                 <div class="col-sm-12 col-md-12">
                                                    <h6 class="card-body__title" style="text-decoration: underline;text-align: left;font-weight: bold;color: #0154a0;">DATOS ADICIONALES OBRAS PUBLICAS</h6>
                                                 </div>
                                                          
                                                 <div class="col-sm-12 col-md-12">
                                           			 <div class="row">
                                           					<div class="form-group col-sm-4">
                                                           	    <label>DEPARTAMENTO</label>
                                                                <input id="txt_departamento" name="txt_departamento" type="text" class="form-control">                                                                      
                                                            </div>
                                                            <div class="form-group col-sm-4">
                                                           	    <label>PROVINCIA</label>
                                                                <input id="txt_provincia" name="txt_provincia" type="text" class="form-control">                                                                      
                                                            </div>
                                                            <div class="form-group col-sm-4">
                                                           	    <label>DISTRITO</label>
                                                                <input id="txt_distrito" name="txt_distrito" type="text" class="form-control">                                                                      
                                                            </div>             
                                                           	                                                                  
                                                            <div class="form-group col-sm-4">
                                                                <label>FEC. RECEPCION</label>
                                                                <input id="fecRecepcion" name="fecRecepcion" type="text" class="form-control date-picker">                                         
                                                            </div>
                                                            
                                                            <div class="form-group col-sm-4">
                                                           	    <label>NOMBRE CLIENTE</label>
                                                                <input id="inputNomCli" name="inputNomCli" type="text" class="form-control" onchange="updateNumCarta()">                                                                      
                                                            </div> 
                                                            <div class="form-group col-sm-4">
                                                                <label>NUMERO DE CARTA</label>
                                                                <input id="inputNumCar" name="inputNumCar" onchange="updateNumCarta()" type="text" class="form-control">                                                                      
                                                            </div>
                                                       </div>
                                            	</div>                            	
                              				
                                             	<!-- <div class="col-sm-9 col-md-9" style="border-style: double;">
                                                 	<div style=" position: absolute;top: -20px;left: 35%;z-index: 5;background-color: #fff;padding: 5px;text-align: center;line-height: 25px;padding-left: 10px;">
                                              		 	<input type="text" id="search"> <input type="button" value="Buscar Direccion" onClick="searchDireccion()">
                                              		</div>
                                            		<div id="contenedor_mapa" style="height: 420px; position: relative; overflow: hidden;"></div>
                                        		</div> -->
                                                		
                                               <div style="margin-top: 15px;" class="col-sm-12 col-md-12">
                                                    <div class="row">
                                                        <div class="form-group col-sm-4">
                                                            <label>COSTO DISE&Ntilde;O</label>
                                                            <input id="inputDiseno" name="inputDiseno" type="text" class="form-control">                                                                      
                                                        </div>
                                                        <div class="col-sm-3 col-md-3">
                                                            <div class="form-group">
                                                                <label>A&Ntilde;O</label>
                                                                <select id="selectAno" name="selectAno" class="select2 form-control" onchange="updateNumCarta()">
                                                                    <option value="2018">2018</option>     
                                                                    <option value="2019">2019</option>                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-md-3">
                                                            <label>NUMERO DE CARTA PEDIDO ENTIDAD</label>
                                                            <input value="---" disabled id="inputNumCartaFin" name="inputNumCartaFin" type="text" class="form-control">                                                                      
                                                        </div>                       
                                                        <div style="text-align: center;" id="contUploadFileCoti" class="col-sm-3 col-md-3">
                                                            <div class="form-group">
                                                            <label style="font-size: large;" for="fileuploadOP" class="subir">
                                                                <i class="zmdi zmdi-upload"></i>
                                                            </label>
                                                            <input id="fileuploadOP" name="fileuploadOP" type="file" onchange='cambiar2()' style='display: none;'>
                                                            <div id="infoOP">Subir Carta (PDF) </div>
                                                            </div>
                                                        </div>                                                      
                                                    </div>
                                        	   </div>  
                                        </div>
                                	</div>
                                <!-- FIN DE CONTENIDO OBRAS PUBLICAS     -->
                         <div class="col-sm-12 col-md-12" style="text-align: center;">
                            <div id="mensajeForm"></div>
                         </div>  
                         <div class="col-sm-12 col-md-12" style="text-align: center;">
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">                                      
                                    <button id="btnSave" type="submit" class="btn btn-primary">Guardar Datos</button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
                                            
                                            
                                            
                                            
                                            
			                <footer class="footer hidden-xs-down">
			                    <p>Telefonica del Peru</p>
		                   </footer>
		                   
		                   <input type="hidden" id="hfAdjudicacionAutomatica" name="hfAdjudicacionAutomatica" value="0" >
		                   <input type="hidden" id="hfHasCoaxial" name="hfHasCoaxial" value="0" >
		                   <input type="hidden" id="hfHasFo" name="hfHasFo" value="0" >
		                   <input type="hidden" id="hfpaquetizado_fg" name="hfpaquetizado_fg" value="1" >
		                   
            </section>
        </main>

        <!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>  
       
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

        
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
              
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
        <script type="text/javascript">
        $( document ).ready(function() {
            /*
            console.log('ffff');
        	$.ajax({
                type    :   'POST',
                'url'   :   'cisisego',
                data    :   {sisego : '2019-10-00011',
                	envio : '2019-10-11',
                	mdf : 'SB',
                	segmento : 'EMPRESAS',
                	cliente : 'CLARO',
                	tipo_cliente : ''
                    },
                'async' :   false
            });
            */
      	});
        

        
        function updateNumCarta(){
        	var    inputNomCli    = $('#inputNomCli').val();
        	var    inputNumCar    = $('#inputNumCar').val();
        	var    selectAno      = $('#selectAno').val();
            $('#inputNumCartaFin').val(selectAno+'-'+inputNumCar+'-'+inputNomCli);
        }
      /*  var validator = $('#formAddPlanobra').data('bootstrapValidator');
        validator.enableFieldValidators('fileupload', false); 
*/
        var flgAgrega = null;
        
        function marcarFase(){
        	validarFasePorProyecto();
            var descSupProyecto = $('#selectSubproy option:selected').text();
            var idSupProyecto = $('#selectSubproy option:selected').val();

            //INICIO -- UBICAR EL TIPO DE FACTOR DE MEDICION
            $.ajax({
                type    :   'POST',
                'url'   :   'pqt_getFactorMedicion',
                data    :   {idSupProyecto  : idSupProyecto},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                
                if(data.error == 0){ 
                    $('#inputCantidadFactorMedicion').val('');

                    if(data.idFactorMedicion > 0){
                    	$('#lblFactorMedicion').text("FACTOR DE MEDICION: " + data.descFactorMedicion);
                        $('#hfIdFactorMedicion').val(data.idFactorMedicion);
                        validator.enableFieldValidators('inputCantidadFactorMedicion', true);
                        $('#divFactorMedicion').show();
                    }else{
                    	$('#lblFactorMedicion').text("");
                        $('#hfIdFactorMedicion').val("0");
                    	$('#divFactorMedicion').hide();
                    	validator.enableFieldValidators('inputCantidadFactorMedicion', false);
                    }
                    
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
            //
            
            var arreglo = descSupProyecto.split(" ");
            var flgMarcar = 0;

            arreglo.forEach(item => {
                if(item == '2017'){
                    flgMarcar = 1;
                }
            });

            /*if(flgMarcar == 1){
                if(flgAgrega ==  1){
                    $("#selectFase").append(new Option("2017", "4"));
                }
                $('#selectFase').val(4);
                $('#selectFase').change();
                $('#selectFase option:not(:selected)').attr('disabled',true);

            }else{
                $('#selectFase').val(null);
                $("#selectFase option[value='4']").remove();
                $('#selectFase').change();
                $('#selectFase option:not(:selected)').attr('disabled',false);
            }*/
            flgAgrega = 1;

            //MOSTRAR REGISTROS DE ADJUDICACION AUTOMATICA
            $.ajax({
                type    :   'POST',
                'url'   :   'pqt_getInfoSubProCoaxFo',
                data    :   {idSupProyecto  : idSupProyecto},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                
                if(data.error == 0){ 
                    console.log('has_coaxial               : ' + data.has_coaxial);
                    console.log('has_fo                    : ' + data.has_fo);
                    console.log('adjudicacionAutomatica_fg : ' + data.adjudicacionAutomatica_fg);
                    console.log('paquetizado_fg            : ' + data.paquetizado_fg);

                    $('#hfAdjudicacionAutomatica').val(data.adjudicacionAutomatica_fg);
                    $('#hfHasCoaxial').val(data.has_coaxial);
                    $('#hfHasFo').val(data.has_fo);
                    $('#hfpaquetizado_fg').val(data.paquetizado_fg);

                    if(data.adjudicacionAutomatica_fg == 1){
                    	$('#idFechaPreAtencionCoax').val('');
                        $('#idFechaPreAtencionFo').val('');
                        
                    	if(data.has_fo == 1){
                    		$('#divFO').show();
                        }else{
                    		$('#divFO').hide();
                        }

                    	if(data.has_coaxial == 1){
                    		$('#divCoaxial').show();
                        }else{
                        	$('#divCoaxial').hide();
                        }
                    }else{
                    	$('#divCoaxial').hide();
                    	$('#divFO').hide();
                    	$('#idFechaPreAtencionCoax').val('');
                        $('#idFechaPreAtencionFo').val('');
                    }
                    
                }else if(data.error == 1){
                	$('#divCoaxial').hide();
                	$('#divFO').hide();
                	$('#idFechaPreAtencionCoax').val('');
                    $('#idFechaPreAtencionFo').val('');
                    mostrarNotificacion('error','Hubo problemas al obtener los datos!');
                }
            });
        }
        
        
        function cambiar(){
            var pdrs = document.getElementById('fileupload').files[0].name;
            document.getElementById('info').innerHTML = pdrs;
        }

        function cambiar2(){
            var pdrs = document.getElementById('fileuploadOP').files[0].name;
            document.getElementById('infoOP').innerHTML = pdrs;
        }                                               
        /*actualizacion dinamica de combobox*/
        /*actualizacion de subproyecto a partir del proyecto*/
         var IDSUB = null;
         var itemP =null;

     
         $('#inputIndicador, #selectCentral').bind('keypress blur', function() {
        
    $('#inputNombrePlan').val($('#inputIndicador').val() + ' - ' +
                    $('#selectCentral option:selected').text() );
});


        function changueProyect(){

        	validarFasePorProyecto();
            
        	$('#lblFactorMedicion').text("");
        	$('#inputCantidadFactorMedicion').val("");
            $('#hfIdFactorMedicion').val("0");
        	$('#divFactorMedicion').hide();
        	validator.enableFieldValidators('inputCantidadFactorMedicion', false);
            
            var proyecto = $.trim($('#selectProy').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'pqt_getSubProPO',
                data    :   {proyecto  : proyecto},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){       
    
                    $('#selectSubproy').html(data.listaSubProy);
                    $('#selectSubproy').val('').trigger('chosen:updated');
                    if(proyecto ==  1){//HFC
                        $('#contItemMadre').show();
                    } else if(proyecto ==  4){//OBRAS PUBLICAS
                 	   $('#fecRecepcion').flatpickr({
                        	defaultDate: "today"});                	    
                	    var validator = $('#formAddPlanobra').data('bootstrapValidator');
                        validator.enableFieldValidators('fileuploadOP', true);
                        validator.enableFieldValidators('inputNomCli', true);
                        validator.enableFieldValidators('inputNumCar', true);
                        validator.enableFieldValidators('selectAno', true);  
                        validator.enableFieldValidators('txt_departamento', true);
                        validator.enableFieldValidators('txt_provincia', true);
                        validator.enableFieldValidators('txt_distrito', true);
                                             
                        $('#contObrasPublicas').show();
                        $('#contKickoff').show();
                        $('#contFecIni').show();
                        $('#contFecPrev').hide();
                        $('#contCotizacion').hide();
						$('#contItemMadre').show();
                    }else if(proyecto ==  8 || proyecto ==  43){//transporte o proyectos varios
						$('#selectCotizacion').val('1').trigger('change');
						$('#selectCotizacion').val('1').trigger('chosen:updated');
						$("#selectCotizacion").attr("disabled", true);						
					}else{
                    	var validator = $('#formAddPlanobra').data('bootstrapValidator');
                        validator.enableFieldValidators('fileuploadOP', false);
                        validator.enableFieldValidators('inputNomCli', false);
                        validator.enableFieldValidators('inputNumCar', false);
                        validator.enableFieldValidators('selectAno', false); 
                        validator.enableFieldValidators('txt_departamento', false);
                        validator.enableFieldValidators('txt_provincia', false);
                        validator.enableFieldValidators('txt_distrito', false);                       
                    	$('#contObrasPublicas').hide();
                    	$('#contKickoff').hide();
                    	$('#contFecIni').show();
                        $('#contFecPrev').show();
                        $('#contCotizacion').show();
						$('#contItemMadre').hide();
                    	$('#inputItemMadre').val('');
						$('#selectCotizacion').val(0).trigger('change');
						$("#selectCotizacion").attr("disabled", false);	
                    }
                   
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });			
        }
        
        function recalcular_fecha_prev_ejec(){
       	
            var subproy = $.trim($('#selectSubproy').val()); 
           
            if(subproy==undefined || subproy=='undefined' || subproy==''){
                $('#inputFechaPrev').val('');
                return false;
            }
            
            var inputFechaInicio = $.trim($('#inputFechaInicio').val()); 
            
            if(inputFechaInicio==undefined || inputFechaInicio=='undefined' || inputFechaInicio==''){
                $('#inputFechaPrev').val('');
                return false;
            }
            
            $.ajax({
                type    :   'POST',
                'url'   :   'pqt_getFechaSubproOP',
                data    :   { fecha  : inputFechaInicio,
                              subproyecto  : subproy
                            },
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){       
                  
                 $('#inputFechaPrev').val(data.fechaCalculado);
                    
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al obtener la fecha de prevista!');
                }
            });




        }

        function changueCentral(){
            var central = $.trim($('#selectCentral').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'pqt_getZonalPO',
                data    :   {central  : central},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){ 
                    $('#inputNombrePlan').val('');      
                    $('#inputNombrePlan').val($('#selectCentral option:selected').text());
                    $('#selectZonal').html(data.listaZonal);
                    $('#selectZonal').val(data.idZonalSelec).trigger('chosen:updated');
                    $('#selectEmpresaColab').html(data.listaEECC);
                    $('#selectEmpresaColab').val(data.idEECCSelec).trigger('chosen:updated');
                    
                    $('#inputJefatura').val(data.jefatura);
                    
                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectZonal');
                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectEmpresaColab');
                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'inputNombrePlan');
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }
     

        function changueEECC(){
            var central = $.trim($('#selectCentral').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'pqt_getEECCPO',
                data    :   {central  : central},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){       
    
                    $('#selectEmpresaColab').html(data.listaEECC);

                    $('#selectEmpresaColab').val(data.idEECCSelec).trigger('chosen:updated');
                    
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }


              

      
            function addPlanobra(){
                
                /*habilitacion campos de creacion*/
                $('#selectProy').val('').trigger('change');
           	    $('#selectSubproy').val('').trigger('change');
                $('#selectCentral').val('').trigger('change');
                $('#selectZonal').val('').trigger('change'); 
                $('#selectEmpresaColab').val('').trigger('change'); 
                $('#selectEmpresaEle').val('').trigger('change');
                $('#selectFase').val('').trigger('change');
                $('#inputIndicador').val('');
                $('#inputCantObra').val('');
                $('#inputFechaInicio').val('');
                $('#inputFechaPrev').val('');
                $('#inputNombrePlan').val('');
                $('#inputUIP').val('');
                $('#inputCoordX').val('');
                $('#inputCoordY').val('');
                /**/
           	    $('#formAddPlanobra').bootstrapValidator('resetForm', true); 
            	$('#modalRegistrarPlanobra').modal('toggle'); //abrirl modal        	
            }

            
            $('#formAddPlanobra')
        	.bootstrapValidator({
        	    container: '#mensajeForm',
        	    feedbackIcons: {
        	        valid: 'glyphicon glyphicon-ok',
        	        invalid: 'glyphicon glyphicon-remove',
        	        validating: 'glyphicon glyphicon-refresh'
        	    },
        	    excluded: ':disabled',
        	    fields: {
                    selectProy: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un proyecto.</p>'
                            }
                        }
                    },
                     selectSubproy: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un subproyecto.</p>'
                            }
                        }
                    },
                    selectCentral: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una central.</p>'
                            }
                        }
                    },
                    selectZonal: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una zonal.</p>'
                            }
                        }
                    },
                    selectEmpresaColab: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una empresa colaboradora.</p>'
                            }
                        }
                    },
                    selectEmpresaEle: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una empresa electrica.</p>'
                            }
                        }
                    },
                    selectFase: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar la fase.</p>'
                            }
                        }
                    },
          	    	inputNombrePlan: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el titulo del plan.</p>'
                            }
                        }
                    },
                    inputIndicador: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el indicador.</p>'
                            }
                        }
                    },
        	    	inputFechaInicio: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe ingresar la fecha de inicio del plan.</p>'
        	                }
        	            }
        	        },
        	        selectCotizacion: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar si tiene Cotizacion o no.</p>'
                            }
                        }
                    },
                    fileupload: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe subir el archivo para la Cotizacion.</p>'
                            }
                        }
                    },
                    fileuploadOP: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe subir Carta de Obra Publicas(PDF).</p>'
                            }
                        }
                    },
                    inputNomCli: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar nombre de CLiente Obra Publica.</p>'
                            }
                        }
                    },
                    inputNumCar: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar Numero de Carta Obra Publica.</p>'
                            }
                        }
                    },
                    selectAno: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar AÃ±o Obra Publica.</p>'
                            }
                        }
                    },
                    txt_departamento: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar un Departamento.</p>'
                            }
                        }
                    }
                    ,
                    txt_provincia: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar una Provincia.</p>'
                            }
                        }
                    }
                    ,
                    txt_distrito: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar un Distrito.</p>'
                            }
                        }
                    },
                    inputCantidadFactorMedicion:{
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar la Cantidad del Factor de Medici</p>'
                            }
                        }
                    }
        	    }
        	}).on('success.form.bv', function(e) {
        		e.preventDefault();       		
                $idProyecto = $.trim($('#selectProy').val());

                if($idProyecto != 4) {
                    var fecha_inci = $('#inputFechaInicio').val();

                    if(fecha_inci == null || fecha_inci == '') {
                        mostrarNotificacion('warning','info','Debe ingresar la fecha de inicio');
                        return;
                    }
                }

        		swal({
    	            title: 'Est&aacute; seguro crear el itemplan?',
    	            text: 'Asegurese de que la informacion llenada sea la correta.',
    	            type: 'warning',
    	            showCancelButton: true,
    	            buttonsStyling: false,
    	            confirmButtonClass: 'btn btn-primary',
    	            confirmButtonText: 'Si, guardar los datos!',
    	            cancelButtonClass: 'btn btn-secondary',
    	            allowOutsideClick: false
    	        }).then(function(){

        	        
        	    var $form    = $(e.target),
        	        formData = new FormData(),
        	        params   = $form.serializeArray(),
        	        bv       = $form.data('bootstrapValidator');	 
        	   
        		    $.each(params, function(i, val) {
        		    	console.log(val.name +' '+ val.value);
        		        formData.append(val.name, val.value);
        		    });

        		    var input = document.getElementById('fileupload');
		            var file = input.files[0];
		            //var form = new FormData();
		            formData.append('file', file);

		            var input2 = document.getElementById('fileuploadOP');
		            var file2 = input2.files[0];
		            //var form = new FormData();
		            formData.append('fileOP', file2);

		            var  cartaFinValue = $('#inputNumCartaFin').val();
		            formData.append('numCartaFin', cartaFinValue);
		            
		            var  fecRecepcion = $('#fecRecepcion').val();
		            formData.append('fecRecepcionOP', fecRecepcion);

		            var  zonal = $('#selectZonal').val();
		            formData.append('selectZonal', zonal);

		            var  empresaColab = $('#selectEmpresaColab').val();
		            formData.append('selectEmpresaColab', empresaColab);

		            var  central = $('#selectCentral').val();
		            formData.append('selectCentral', central);

		            var  hfAdjudicacionAutomatica = $('#hfAdjudicacionAutomatica').val();
		            formData.append('hfAdjudicacionAutomatica', hfAdjudicacionAutomatica);
		            
		            var  hfHasCoaxial = $('#hfHasCoaxial').val();
		            formData.append('hfHasCoaxial', hfHasCoaxial);
		            
		            var  hfHasFo = $('#hfHasFo').val();
		            formData.append('hfHasFo', hfHasFo);
		            
		            var  hfpaquetizado_fg = $('#hfpaquetizado_fg').val();
		            formData.append('hfpaquetizado_fg', hfpaquetizado_fg);

		            var  idFechaPreAtencionFo = $('#idFechaPreAtencionFo').val();
		            formData.append('idFechaPreAtencionFo', idFechaPreAtencionFo);

		            var  idFechaPreAtencionCoax = $('#idFechaPreAtencionCoax').val();
		            formData.append('idFechaPreAtencionCoax', idFechaPreAtencionCoax);

                    // var codPlan = $('#selectPlan option:selected').val();
                    // formData.append('cod_planificacion', codPlan);
		            
        		    $.ajax({
    			        data: formData,
    			        url: "pqt_addPlanobra",
    			        cache: false,
    		            contentType: false,
    		            processData: false,
    		            type: 'POST'
    			  	})
    				  .done(function(data) {  
    					    	data = JSON.parse(data);
    				    	if(data.error == 0){
                                var itemplan = data.itemplannuevo;                     
                                    swal({
                        	            title: 'Se genero correctamente el Itemplan',
                        	            text: itemplan,
                        	            type: 'success',
                        	            showCancelButton: false,                    	            
                        	            allowOutsideClick: false
                        	        }).then(function(){
                            	        location.reload();
                        	        });
    						}else if(data.error == 1){
    							mostrarNotificacion('warning','Alerta','No se proceso la solicitud:'+data.msj);
    						}
    			  	  })
    			  	  .fail(function(jqXHR, textStatus, errorThrown) {
    			  		mostrarNotificacion('error','Error','Comuniquese con alguna persona a cargo :(');
    			  	  })
    			  	  .always(function() {
    			      	 
    			  	});
        		   

    	        }, function(dismiss) {
        	        console.log('cancelado');
    	        	// dismiss can be "cancel" | "close" | "outside"
        	        $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCotizacion');
	        		//$('#formAddPlanobra').bootstrapValidator('resetForm', true); 
    	        });


            	    
        	});
      
                   
            function validateCoti(){
            	var hasCoti = $.trim($('#selectCotizacion').val()); 
            	console.log(hasCoti);
            	if(hasCoti ==  '1'){
            		$('#contUploadFileCoti').show();
               		 var validator = $('#formAddPlanobra').data('bootstrapValidator');
                     validator.enableFieldValidators('fileupload', true);
                     validator.enableFieldValidators('selectCentral', false); 
                     validator.enableFieldValidators('selectZonal', false); 
                     validator.enableFieldValidators('selectEmpresaColab', false); 
                }else{
                	$('#contUploadFileCoti').hide();
                   	 var validator = $('#formAddPlanobra').data('bootstrapValidator');
                     validator.enableFieldValidators('fileupload', false);
                     validator.enableFieldValidators('selectCentral', true); 
                     validator.enableFieldValidators('selectZonal', true); 
                     validator.enableFieldValidators('selectEmpresaColab', true); 
                }            
            }

            
            var validator = $('#formAddPlanobra').data('bootstrapValidator');
            validator.enableFieldValidators('fileupload', false); 
            validator.enableFieldValidators('fileuploadOP', false); 
            validator.enableFieldValidators('inputNomCli', false);
            validator.enableFieldValidators('inputNumCar', false);
            validator.enableFieldValidators('selectAno', false);
            validator.enableFieldValidators('txt_departamento', false);
            validator.enableFieldValidators('txt_provincia', false);
            validator.enableFieldValidators('txt_distrito', false);
            validator.enableFieldValidators('inputCantidadFactorMedicion', false);  

            
            /************METODOS GOOGLE MAP**************/
            var marker = null;
            var map = null;
            var center = null;
            
            function init(){
                
                // var mapdivMap = document.getElementById("contenedor_mapa");                
                // center = new google.maps.LatLng(-12.0431800, -77.0282400);
                // var myOptions = {
                //     zoom: 5,
                //     center: center,
                //     mapTypeId: google.maps.MapTypeId.ROADMAP
                // }
                // map = new google.maps.Map(document.getElementById("contenedor_mapa"), myOptions);
                infoWindow = new google.maps.InfoWindow();
                // marker = new google.maps.Marker({
                //     map: map,
                //     title:"Tu posicion",
                //     draggable: true,
                //     animation: google.maps.Animation.DROP
                //   });

                // var geocoder = new google.maps.Geocoder();
                // google.maps.event.addListener(marker, 'dragend', function(){
                // 	var pos = marker.getPosition();
                // 	geocoder.geocode({'latLng': pos}, function(results, status) {
                //    		 if (status == google.maps.GeocoderStatus.OK) {                			
                //       			llenarTextosByCoordenadas(results,pos)
                //     			var address=results[0]['formatted_address'];
                //    			 	openInfoWindowAddress(address,marker);				
            	// 		 }
    			//  	});		
      			//   	map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
        		// });

            //     google.maps.event.addListener(map, 'click', function(event) {
            // 		marker.setMap(null);
            		
            // 	    marker = new google.maps.Marker({
            //         position: event.latLng,
            //         map: map,
            //         title:"Tu posiciÃ³n",
            //         draggable: true,
            //         animation: google.maps.Animation.DROP
            //       });

            // 	    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            //        		 if (status == google.maps.GeocoderStatus.OK) {                    			
            //         			var pos = marker.getPosition();
            //           			llenarTextosByCoordenadas(results,pos)
            //         			var address=results[0]['formatted_address'];
            //        			 	openInfoWindowAddress(address,marker);
            // 			 }
      		//  		});	
            // 	    var pos = marker.getPosition();
            // 	    map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng())); 
            	    
            //       google.maps.event.addListener(marker, 'dragend', function(){
            //         	var pos = marker.getPosition();
            //         	geocoder.geocode({'latLng': pos}, function(results, status) {
            //            		 if (status == google.maps.GeocoderStatus.OK) {                        			
            //               			llenarTextosByCoordenadas(results,pos)
            //             			var address=results[0]['formatted_address'];
            //            			 	openInfoWindowAddress(address,marker);				
            //     			 }
        	// 		 	});
          	// 		  	map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
            // 		});
            // }); 
            }

            function searchDireccion(){
         	 	 address = document.getElementById('search').value;
          	 if(address!=''){
          		 if(isCoordenada(address)){
          			 buscarPorCoordenadas(address);
          		 }else{//ES DIRECCION
          			 console.log('address:'+address);
  	        		 var geocoder = new google.maps.Geocoder();
  	            	geocoder.geocode({ 'address': address}, function(results, status){
  	       			   if (status == 'OK'){
  	           			  console.log('..-'+JSON.stringify(results[0].geometry.location));
  	          			// Posicionamos el marcador en las coordenadas obtenidas
  	       				 
  	       				// Centramos el mapa en las coordenadas obtenidas
  	       				// map.setCenter(marker.getPosition());
  	       				map.setCenter(results[0].geometry.location);
  	  	       			map.setZoom(16);
    	       			 

  	       				marker.setPosition(results[0].geometry.location);
  	       				
  	          				var address	=	results[0]['formatted_address'];
  	           			 	openInfoWindowAddress(address,marker);	
  	
  	              			 geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
  	                       		 if (status == google.maps.GeocoderStatus.OK) { 
  	                        			llenarTextosByCoordenadas(results,marker.getPosition());                   			
  	                        			//console.log('searchDireccion:'+JSON.stringify(results));
  	                			 }
  	          		 		});
  	            		 		
  	    				 }
  	   			 	})   
          		 }
          	 }  	 
          }

            function isCoordenada(cadena){
            	var str = cadena;
                var res = str.split(',');
                
                if(res.length == 2){
                	var x = res[0].trim();
                    var y = res[1].trim();
                	
                    var valid_x = (x.match(/^-?\d+(?:\.\d+)?$/));
                    var valid_y = (y.match(/^-?\d+(?:\.\d+)?$/));
                    
                    if(valid_x){
                    	if(valid_y){
                    		return true;
                    	}else{
                    		return false;
                    	}
                    }else{
                    	return false;
                    }            	
                }else{
                	return false;
                }
                           
            }

            function buscarPorCoordenadas(cadena){
	        	var str = cadena;
	            var res = str.split(',');
	            var x = res[0].trim();
                var y = res[1].trim();
                
      			map.setCenter(new google.maps.LatLng(x, y));   
      			map.setZoom(16);
  				marker.setPosition(new google.maps.LatLng(x, y)); 				
          			 	
  			 	var geocoder = new google.maps.Geocoder();
  			 	geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
              		 if (status == google.maps.GeocoderStatus.OK) { 
              			var address	=	results[0]['formatted_address'];
          			 	openInfoWindowAddress(address,marker);	
               			llenarTextosByCoordenadas(results,marker.getPosition());                   			
               			//console.log('searchDireccion:'+JSON.stringify(results));
              		 }
 		 		});  			    
       	 
  			}

            function openInfoWindowAddress(Addres,marker) {
                console.log('geo..');
                 infoWindow.setContent([
                 	Addres
                 ].join(''));
                 infoWindow.open(map, marker);
             }

            /***************************************************
    		METODO PARA LLENAR CAMPOS POR LAS COORDENADAS
        ****************************************************/

         function llenarTextosByCoordenadas(results,pos){
             console.log(results[1]['address_components'][4].long_name.toUpperCase());
        	try{
        		$('#txt_departamento').val(results[1]['address_components'][4].long_name.toUpperCase());
        	}catch(err){
        		$('#txt_departamento').val('');
        	}
        	
        	try{
        		$('#txt_provincia').val(results[1]['address_components'][3].long_name.toUpperCase());
        	}catch(err){
        		$('#txt_provincia').val('');
        	}
        	
        	try{
        		$('#txt_distrito').val(results[1]['address_components'][2].long_name.toUpperCase());
        	}catch(err){
        		$('#txt_distrito').val('');
        	}
        	/*
        	try{
        		$('#txt_numero').val(results[0]['address_components'][0].long_name.toUpperCase());
        	}catch(err){
        		$('#txt_numero').val('0');
        	}
        	
        	try{
        		$('#txt_direccion').val(results[0]['formatted_address']);
        	}catch(err){
        		$('#txt_direccion').val('NO ENCONTRADA');
        	}        	
        	*/
        	$('#inputCoordX').val(pos.lng());
        	$('#inputCoordY').val(pos.lat());
    	
         }

         function changeXY(){
        	 var myLat = document.getElementById("inputCoordX").value;
        	 var myLong = document.getElementById("inputCoordY").value;
        	 
        	 if(!isNaN(myLat) && !isNaN(myLong) && myLat!= '' && myLong!= ''){
            	 $("#divMapaCoordenadasXY").show();
            	 
        		 var coordes = new google.maps.LatLng(myLat, myLong);
            	 
            	 var mapOptions = {
            		center: coordes,
            		mapTypeId: google.maps.MapTypeId.ROADMAP
            	 }
            	 
            	 var mapCoor = new google.maps.Map(document.getElementById("divMapCoordenadas"), mapOptions);
            	 mapCoor.setZoom(15);
            	 var marker = new google.maps.Marker({map:mapCoor, position:coordes});

            	 myLatLng = [$("#inputCoordY").val(),$("#inputCoordX").val()];

				var codigoCentral = getBuscarArea(myLatLng);
  				console.log(codigoCentral);
				var formData = new FormData();
				
				formData.append('codigoCentral', codigoCentral);
				formData.append('latitud' , $("#inputCoordX").val());
				formData.append('longitud', $("#inputCoordY").val());
				
				$.ajax({
					data: formData,
					url: "pqt_obtCentralPorCodigo",
					cache: false,
					contentType: false,
					processData: false,
					type: 'POST'
				}).done(function(data) {  
					data = JSON.parse(data);
					if(data.error == 0){
						$('#selectCentral').val(data.idCentral).trigger('change');
						$('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
						changueCentral();
						console.log(data.idCentral);
					}else if(data.error == 1){
						mostrarNotificacion('error','Error','No se inserto el Plan de obra:'+data.msj);
					}
				});
  	

  		// 		if(codigoCentral != null){
  		// 			var formData = new FormData();
  		//             formData.append('codigoCentral', codigoCentral);
		// 			formData.append('latitud' , myLat);
		// 			formData.append('longitud', myLong);
  		//             $.ajax({
      	// 		        data: formData,
      	// 		        url: "pqt_obtCentralPorCodigo",
      	// 		        cache: false,
      	// 	            contentType: false,
      	// 	            processData: false,
      	// 	            type: 'POST'
      	// 		  	})
      	// 			  .done(function(data) {  
      	// 				    	data = JSON.parse(data);
      	// 			    	if(data.error == 0){
      				    		
        //                           $('#selectCentral').val(data.idCentral).trigger('change');
      	// 	                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
        //                           changueCentral();
        //                           console.log("ENTRO1");
        //                           console.log(data.idCentral);
      	// 					}else if(data.error == 1){
      	// 						mostrarNotificacion('error','Error','No se inserto el Plan de obra:'+data.msj);
      	// 					}
      	// 		  	  })
      	// 		  	  .fail(function(jqXHR, textStatus, errorThrown) {
      	// 		  		mostrarNotificacion('error','Error','Comuniquese con alguna persona a cargo :');
      	// 		  	  })
      	// 		  	  .always(function() {
      			      	 
      	// 		  	});
      	// 		}else{console.log("ENTRO2222");
      	// 			$('#selectCentral').val('').trigger('change');
 	    //                 $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
 	    //                 changueCentral();
        //   			alert("No hay codigo para el area seleccionada");
      	// 		}
            	 
            }
        	 
        }

         function getBuscarArea(arrayCordenadas) {
     		var i_selec = null;
     		for(i=0; i < JSON_COORDENADAS['features'].length; i++) {
     			result = matBuscarArea(arrayCordenadas, JSON_COORDENADAS['features'][i]['geometry']['coordinates'][0][0]);
     			if(result == true) {
     				i_selec = i;
     				break;
     			}
     		}

     		if(i_selec != null) {
     			cod_nod = JSON_COORDENADAS['features'][i_selec]['properties']['MDF'];
     		} else {
     			cod_nod = null;
     		}
     		console.log("getBuscarArea.cod_nod: |" + cod_nod.trim() + "|");
     		return cod_nod.trim();
     	}

     	function matBuscarArea(point, vs) {
     		var x = point[0], y = point[1];
     		var inside = false;
     		for (var i = 0, j = vs.length - 1; i < vs.length; j = i++) {
     			var xi = vs[i][0], yi = vs[i][1];var xj = vs[j][0], yj = vs[j][1];
     			var intersect = ((yi > y) != (yj > y))&& (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
     			if (intersect) inside = !inside;
     		}
     		return inside;
     	}

      	var JSON_COORDENADAS   =  <?php echo $jsonCoordenadas?>;
         $( document ).ready(function() {
     	   console.log( "ready!" );
      	  var geocoder = new google.maps.Geocoder();
     	  var map;
          	 var latitude = -12.0965634; // YOUR LATITUDE VALUE
             var longitude = -77.0276785; // YOUR LONGITUDE VALUE


             if (navigator.geolocation) {
                 navigator.geolocation.getCurrentPosition(function(position) {
                	 latitude = position.coords.latitude;
                	 longitude = position.coords.longitude;
                 });
             }
             
             var myLatLng = {lat: latitude, lng: longitude};
             
             map = new google.maps.Map(document.getElementById('divMapCoordenadas'), {
               center: myLatLng,
               zoom: 14
             });

             // Create the search box and link it to the UI element.
             var input = document.getElementById('pac-input');
             var searchBox = new google.maps.places.SearchBox(input);
             map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

             // Bias the SearchBox results towards current map's viewport.
             map.addListener('bounds_changed', function() {
               searchBox.setBounds(map.getBounds());
             });

             // Listen for the event fired when the user selects a prediction and retrieve
             // more details for that place.
             searchBox.addListener('places_changed', function() {
               var places = searchBox.getPlaces();

               if (places.length == 0) {
                 return;
               }

               // For each place, get the icon, name and location.
               var bounds = new google.maps.LatLngBounds();
               places.forEach(function(place) {
                 
                 if (place.geometry.viewport) {
                   // Only geocodes have viewport.
                   bounds.union(place.geometry.viewport);
                 } else {
                   bounds.extend(place.geometry.location);
                 }
               });
               map.fitBounds(bounds);
             });
             
             // Update lat/long value of div when anywhere in the map is clicked    
             google.maps.event.addListener(map,'click',function(event) {
    				myLatLng = [event.latLng.lng(),event.latLng.lat()];

    				$("#inputCoordX").val(event.latLng.lat());
    				$("#inputCoordY").val(event.latLng.lng());

    				console.log('Ejecutar getBuscarArea...');
    				var codigoCentral = getBuscarArea(myLatLng);
    				console.log("COD_CENTRAL: "+codigoCentral);

    				if(codigoCentral != null){
    					var formData = new FormData();
    		            formData.append('codigoCentral', codigoCentral);
						formData.append('latitud' , event.latLng.lat());
						formData.append('longitud', event.latLng.lng());
    		            $.ajax({
        			        data: formData,
        			        url: "pqt_obtCentralPorCodigo",
        			        cache: false,
        		            contentType: false,
        		            processData: false,
        		            type: 'POST'
        			  	})
        				  .done(function(data) {  
        					    	data = JSON.parse(data);
        				    	if(data.error == 0){
        				    		
                                    $('#selectCentral').val(data.idCentral).trigger('change');
        		                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
                                    changueCentral();
                                    console.log(data.idCentral);
        						}else if(data.error == 1){
        							mostrarNotificacion('error','Error','No se inserto el Plan de obra:'+data.msj);
        						}
        			  	  })
        			  	  .fail(function(jqXHR, textStatus, errorThrown) {
        			  		mostrarNotificacion('error','Error','Comuniquese con alguna persona a cargo :');
        			  	  })
        			  	  .always(function() {
        			      	 
        			  	});
        			}else{
        				$('#selectCentral').val('').trigger('change');
	                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectCentral');
	                    changueCentral();
            			alert("No hay codigo para el area seleccionada");
        			}
    				
             });
             
             var marker;
             
             // Create new marker on double click event on the map
            google.maps.event.addListener(map,'click',function(event) {
                if ( marker ) {
                    marker.setPosition(event.latLng);
                }else{
                    marker = new google.maps.Marker({
                        position: event.latLng, 
                        map: map, 
                        title: event.latLng.lat()+', '+event.latLng.lng(),
                        draggable: true,
                        animation: google.maps.Animation.DROP
                    });
                }
                
                geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {                    			
                    			var pos = marker.getPosition();
                      			llenarTextosByCoordenadas(results,pos)
                    			var address=results[0]['formatted_address'];
                   			 	openInfoWindowAddress(address,marker);
            			 }
      		 		});	
            	    var pos = marker.getPosition();
            	    map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng())); 
            	    
                google.maps.event.addListener(marker, 'dragend', function(){
                    var pos = marker.getPosition();
                    geocoder.geocode({'latLng': pos}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {                        			
                                llenarTextosByCoordenadas(results,pos)
                                var address=results[0]['formatted_address'];
                                openInfoWindowAddress(address,marker);				
                            }
                    });
                    map.setCenter(new google.maps.LatLng(pos.lat(),pos.lng()));        	
                });
    				      
            });
     	});
///////////////////////////////////////////////////////////////////////////////////////////////////
        function validarFasePorProyecto(){
            var fase = $("#selectFase option:selected").text();
            var idFase = $("#selectFase option:selected").val();
            var idProyecto = $("#selectProy").val();
            var idSubProyecto = $("#selectSubproy").val();
            console.log("fase          " + fase);
            console.log("idProyecto    " + idProyecto);
            console.log("idSubProyecto " + idSubProyecto);

            if(fase != "" && idProyecto != "" && idSubProyecto != ""){
            	var formData = new FormData();
	            formData.append('fase', fase);
	            formData.append('idProyecto', idProyecto);
	            formData.append('idSubProyecto', idSubProyecto);
                formData.append('idFase', idFase);
	            $.ajax({
			        data: formData,
			        url: "pqt_permitirCrearItemPlan",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				  .done(function(data) {  
					    	data = JSON.parse(data);
				    	if(data.error == 0){
				    		if(data.configuracion_fg == 1){
                                
					    		if(data.permitir_continuar_fg == 1){
					    			swal({
					                	  title: "ATENCION!!",
					                	  text: "Supero el limite de creacion de itemplan para el SUB!",
					                	  type: "warning"
						                	  });
					    			$("#btnSave").attr("disabled", true);
					    			$('#selectSubproy').val('');
					                $('#selectSubproy').change();
					    		}else{
					    			$("#btnSave").attr("disabled", false);
					    		}
				    		}else{
				    			$("#btnSave").attr("disabled", false);
				    		}
						}else if(data.error == 1){
							swal({
				              	  title: "Error",
				              	  text: 'No se inserto el Plan de obra:'+data.msj,
				              	  type: "error"
				              	});
						}
			  	  })
			  	  .fail(function(jqXHR, textStatus, errorThrown) {
			  		swal({
		              	  title: "Error",
		              	  text: 'Comuniquese con alguna persona a cargo :',
		              	  type: "error"
		              	});
			  	  })
			  	  .always(function() {
			      	 
			  	});
            	
            }
            
        }
        </script> 
        
       <!-- google maps -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&libraries=places&callback=init"></script>
        
    </body>


</html>