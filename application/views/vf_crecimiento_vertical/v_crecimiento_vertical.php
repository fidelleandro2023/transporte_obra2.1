<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">        
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
		        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
		
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">

        <!-- Demo -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/demo/css/demo.css">
        
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css">
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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

                        
                    </div>

                    <ul class="navigation">
                    <?php echo $opciones?>
                    </ul>
                </div>
            </aside>

            
            
            <section class="content content--full">
             	<div class="content__inner">
             		<h2 >BUSQUEDA CRECIMIENTO VERTICAL</h2>
              		<div class="card">
              			<div class="card-block">	
              				<div class="row">
                  				<div class="col-md-12">
                  				 <div class="tab-container tab-container--green">
                                        <ul class="nav nav-tabs nav-fill" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#home-4" role="tab">1) UBICACION</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#profile-4" role="tab">2) DATOS DEL EDIFICIO</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#messages-4" role="tab">3) DATOS DE LA CONSTRUCTORA</a>
                                            </li>                                            
                                        </ul>
                                 <form id="formRegistrarCV" method="post" class="form-horizontal"> 
                                        <div class="tab-content">
                                             
                                                <div class="tab-pane active fade show" id="home-4" role="tabpanel">
                                           			 <h6 class="card-body__title" style="text-decoration: underline;text-align: left;font-weight: bold;color: #0154a0;">REGISTRO DE UBICACIÓN</h6>
                                                
                                                     <div class="row"> 
                                                        <div class="col-sm-3 col-md-3">
                                                   			 <div class="row">
                                                   					<div class="form-group form-group--float col-sm-12">
                                                                        <input disabled style="font-weight: bold;color: black;" id="txt_departamento" type="text" class="form-control form-control-sm form-control--active">
                                                                        <label style="font-weight: bold;color: black;">DEPARTAMENTO</label>
                                                                        <i class="form-group__bar"></i>
                                                                    </div>
                                                                    <div class="form-group form-group--float col-sm-12">
                                                                        <input disabled style="font-weight: bold;color: black;" id="txt_provincia" type="text" class="form-control form-control-sm form-control--active">
                                                                        <label style="font-weight: bold;color: black;">PROVINCIA</label>
                                                                        <i class="form-group__bar"></i>
                                                                    </div>
                                                                    <div class="form-group form-group--float col-sm-12">
                                                                        <input disabled style="font-weight: bold;color: black;" id="txt_distrito" type="text" class="form-control form-control-sm form-control--active">
                                                                        <label style="font-weight: bold;color: black;">DISTRITO</label>
                                                                        <i class="form-group__bar"></i>
                                                                    </div>              
                                                                   	<div class="form-group form-group--float col-sm-6">
                                                                        <input disabled style="font-weight: bold;color: black;" id="txt_coord_x" name="txt_coord_x" type="text" class="form-control form-control-sm form-control--active">
                                                                        <label style="font-weight: bold;color: black;">COORDENADA X</label>
                                                                        <i class="form-group__bar"></i>
                                                                    </div>
                                                                    <div class="form-group form-group--float col-sm-6">
                                                                        <input disabled style="font-weight: bold;color: black;" id="txt_coord_y" name="txt_coord_y" type="text" class="form-control form-control-sm form-control--active">
                                                                        <label style="font-weight: bold;color: black;">COORDENADA Y</label>
                                                                        <i class="form-group__bar"></i>
                                                                    </div>
                                                               </div>
                                                    	</div>                            	
                              				
                                                     	<div class="col-sm-9 col-md-9" style="border-style: double;">
                                                         	<div style=" position: absolute;top: -20px;left: 35%;z-index: 5;background-color: #fff;padding: 5px;text-align: center;line-height: 25px;padding-left: 10px;">
                                                      		 	<input type="text" id="search"> <input type="button" value="Buscar Dirección" onClick="searchDireccion()">
                                                      		</div>
                                                    		<div id="contenedor_mapa" style="height: 420px; position: relative; overflow: hidden;"></div>
                                                		</div>
                                                    </div>                                                
                                                </div>
                                                
                                                <div class="tab-pane fade" id="profile-4" role="tabpanel">
                                                    <h6 class="card-body__title" style="text-decoration: underline;text-align: left;font-weight: bold;color: #0154a0;">DATOS DEL EDIFICIO</h6>
                                            		<div class="row">                                        		
                                                            <div class="form-group col-sm-3">
                                                               <label style="font-weight: bold;color: black;">MDF</label>
                                                                <select id="seletContrata" name="seletContrata" class="select2">
                                                                     	<option value="">.:Seleccionar:.</option>
                                                                     	<?php foreach($listaNodos->result() as $row){?>
                                                                        	<option value="<?php echo $row->idCentral?>"><?php echo $row->tipoCentralDesc?></option>                                                        
                                                                        <?php }?>
                                                                </select>
                                                            </div>
                                                            
                                                           <div class="form-group col-sm-3">
                                                               <label style="font-weight: bold;color: black;">TIPO URB / CCHH</label>
                                                                <select id="selectTipoUrb" name="selectTipoUrb" class="select2">
                                                                     	<option value="">.:Seleccionar:.</option>
                                                                        <option value="URB.">URB.</option>
                                                                        <option value="CCHH.">CCHH.</option>
                                                                </select>
                                                             
                                                            </div>
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_NombreUrb" name="txt_NombreUrb" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">NOMBRE URB / CCHH</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                      		<div class="form-group col-sm-3">
                                                               <label style="font-weight: bold;color: black;">TIPO VIA</label>
                                                                <select id="selectTipoVia" name="selectTipoVia" class="select2">
                                                                     	<option value="">.:Seleccionar:.</option>
                                                                        <option value="CA.">CA.</option>
                                                                        <option value="AV.">AV.</option>
                                                                        <option value="JR.">JR.</option>
                                                                        <option value="ALAM.">ALAM.</option>
                                                                </select>                                             
                                                            </div>
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_direccion" name="txt_direccion" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">DIRECCIÓN</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_numero" name="txt_numero" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">NÚMERO</label>
                                                                <i class="form-group__bar"></i>
                                                            </div> 
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_manzana" name="txt_manzana" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">MANZANA</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_lote" name="txt_lote" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">LOTE</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                          <!-- ------------------------------------------------------------------------------------------ -->
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_nombre_proyecto" name="txt_nombre_proyecto" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">NOMBRE DEL PROYECTO</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_blocks" name="txt_blocks" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">BLOCKS</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_pisos" name="txt_pisos" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">PISOS</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_departamentos" name="txt_departamentos" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">DEPARTAMENTOS</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;;" id="txt_dep_habitados" name="txt_dep_habitados" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">DEPARTAMENTOS HABITADOS</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div class="form-group col-sm-3">
                                                               <label style="font-weight: bold;color: black;">ESTADO DEL EDIFICIO</label>
                                                                <select id="selectEstadoEdi" name="selectEstadoEdi" class="select2">
                                                                     	<option value="">.:Seleccionar:.</option>
                                                                        <option value="NUEVO">NUEVO</option>
                                                                        <option value="ANTIGUO">ANTIGUO</option>
                                                                </select>                                             
                                                            </div>
                                                            <div class="form-group col-sm-3">
                                                               <label style="font-weight: bold;color: black;">% AVANCE</label>
                                                                <select id="txt_avance" name="txt_avance" class="select2">
                                                                     	<option value="">.:Seleccionar:.</option>
                                                                        <option value="0">0 %</option>
                                                                        <option value="10">10 %</option>
                                                                        <option value="20">20 %</option>
                                                                        <option value="30">30 %</option>
                                                                        <option value="40">40 %</option>
                                                                        <option value="50">50 %</option>
                                                                        <option value="60">60 %</option>
                                                                        <option value="70">70 %</option>
                                                                        <option value="80">80 %</option>
                                                                        <option value="90">90 %</option>
                                                                        <option value="100">100 %</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;" id="txt_fec_termino" name="txt_fec_termino" type="text" class="form-control form-control-sm  date-picker form-control--active">
                                                                <label style="font-weight: bold;color: black;">FECHA TERMINO CONSTRUCCIÓN</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>                    
                                                                                    
                                                            <div class="form-group col-sm-3">
                                                               <label style="font-weight: bold;color: black;">PRIORIDAD</label>
                                                                <select id="selectPrioridad" name="selectPrioridad" class="select2">
                                                                     	<option value="NO">NO</option>
                                                                        <option value="SI">SI</option>
                                                                </select>
                                                            </div>
                                                            
                                                          	<div class="form-group col-sm-3">
                                                               <label style="font-weight: bold;color: black;">COMPETENCIA</label>
                                                                <select id="selectCompetencia" name="selectCompetencia" class="select2">
                                                                     	<option value="">.:Seleccionar:.</option>
                                                                        <option value="CLARO">CLARO</option>
                                                                        <option value="WIN">WIN</option>
                                                                        <option value="DIRECTV">DIRECTV</option>
                                                                </select>                                             
                                                            </div>
                                        <div class="form-group col-sm-3">
                                                               <label style="font-weight: bold;color: black;">FASE</label>
                                                                <select id="selectFase" name="selectFase" class="select2">
                                                                     	<option value="">.:Seleccionar:.</option>
                                                                        <option value="2">2018</option>
                                                                        <option value="5">2019</option>
                                                                </select>                                             
                                                            </div>
                                                          	  <div class="form-group form-group--float col-sm-3">
                                                                <input maxlength="250" style="border-bottom-color: #838e83;;" id="txtOperador" name="txtOperador" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">OPERADOR</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                           <!-- ------------------------------------------------------------------------------------------ -->
                                                     </div>
                                                </div>
                                                <div class="tab-pane fade" id="messages-4" role="tabpanel">
                                                    <h6 class="card-body__title" style="text-decoration: underline;text-align: left;font-weight: bold;color: #0154a0;">DATOS DE LA CONSTRUCTURA</h6>
                                                	<div class="row">
                                                			<div class="form-group form-group--float col-sm-4">
                                                                <input style="border-bottom-color: #838e83;" id="txt_ruc" name="txt_ruc" type="text" class="form-control form-control-sm form-control--active  input-mask" data-mask="00000000000">
                                                                <label style="font-weight: bold;color: black;">RUC</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                             <div class="form-group form-group--float col-sm-8">
                                                                <input style="border-bottom-color: #838e83;" id="txt_nombre_constru" name="txt_nombre_constru" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">NOMBRE CONSTRUCTORA</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                            				 <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;" id="txt_contacto1" name="txt_contacto1" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">CONTACTO 1</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                             <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;" id="txt_telefono11" name="txt_telefono11" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">TELEFONO 1/2</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                             <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;" id="txt_telefeono12" name="txt_telefeono12" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">TELEFONO 2/2</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                             <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;" id="email1" name="email1" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">EMAIL 1</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                             <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;" id="txt_contacto2" name="txt_contacto2" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">CONTACTO 2</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                             <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;" id="txt_telefono21" name="txt_telefono21" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">TELEFONO 1/2</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                             <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;" id="txt_telefono22" name="txt_telefono22" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">TELEFONO 2/2</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                             <div class="form-group form-group--float col-sm-3">
                                                                <input style="border-bottom-color: #838e83;" id="txt_email2" name="txt_email2" type="text" class="form-control form-control-sm form-control--active">
                                                                <label style="font-weight: bold;color: black;">EMAIL 2</label>
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div class="form-group col-sm-12">                                             	
                                                              	<label style="font-weight: bold;color: black;">OBSERVACION FINAL</label>
                                                                <textarea style="border-bottom-color: #838e83;" id="inputObservacion" name="inputObservacion" class="form-control textarea-autosize" placeholder="Escriba aqui..."></textarea>
                                                                <i class="form-group__bar"></i>                                                
                                    				        </div>
                                                         
                                                    </div>
                                                       <div id="mensajeForm"></div>  
                                                    <div class="form-group" style="text-align: right;">
                                                        <div class="col-sm-12">
                                                           
                                                            <button id="btnRegFicha" type="submit" class="btn btn-primary">Guardar Datos</button>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>                                        
                                         </form>  
                                    </div>
                  				</div>
              				</div>
              				
              				
              				
              					
              					
                            	                      	
                    	</div>	
                    </div>
                   
                </div>        
                
                <footer class="footer hidden-xs-down">
                                            <p>Telefonica del Peru</p>

                                           
                           </footer>      
            </section>
        </main>

        

        <!-- Javascript -->
        <!-- Vendors -->
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/autosize/dist/autosize.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>

        <!-- Demo -->
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
        <script type="text/javascript">
           var goblal_icon_url_terminado      = '<?php echo base_url();?>public/img/iconos/edi_term.png';
            var goblal_icon_url_pendiente      = '<?php echo base_url();?>public/img/iconos/edi_pendiente.png';
    		var global_marcadores =  <?php echo json_encode($marcadores)?>;
    		var global_info_marcadores = <?php echo json_encode($info_markers)?>;
    		
    		var goblal_icon_url_2017          = '<?php echo base_url();?>public/img/iconos/edificio3.png';
    		var global_marcadores_2017        =  <?php echo json_encode($marcadores_2017)?>;
    		var global_info_marcadores_2017   =  <?php echo json_encode($info_markers_2017)?>;

    		var goblal_icon_url_odf          = '<?php echo base_url();?>public/img/iconos/cto.png';
    		var global_marcadores_odf        =  <?php echo json_encode($marcadores_odf)?>;
    		var global_info_marcadores_odf   =  <?php echo json_encode($info_markers_odf)?>;
		</script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&callback=init" async defer></script>
  	    <script src="<?php echo base_url();?>public/js/js_crecimiento_vertical/crecimiento_vertical.js?v=<?php echo time();?>"></script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
</html>