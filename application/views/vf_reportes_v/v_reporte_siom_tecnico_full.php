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
        
        <!-- <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css">-->
        <style>
        @media (min-width: 768px) {
              .modal-xl {
                width: 90%;
               max-width:1200px;
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
                   <a href="https://www.movistar.com.pe/" title="Entel Perú"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
             	<div class="content__inner" style="/*max-width: 100%;*/">
             		<h2 >REPORTE SIOM NIVEL TECNICO 1</h2>
              		<div class="card">
              			<div class="card-block">	
              				<div class="row">
              				  <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>PROYECTO</label>
                                        <select id="selectProyecto" class="form-control select2" onchange="">
                                            <option value="">Seleccionar Proyecto</option>                    
                                            <?php foreach($listaProyectos->result() as $row ){?>
                                                <option value="<?php echo $row->idProyecto?>"><?php echo $row->proyectoDesc?></option>     
                                            <?php }?>                                     
                                        </select>
                                    </div>
                                </div>
              				   <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>JEFATURA</label>
                                        <select id="selectJefatura" class="form-control select2" onchange="">
                                            <option value="">Seleccionar Jefatura</option>                                             
                                            <?php foreach($listaJefaturas->result() as $row ){?>
                                                <option value="<?php echo $row->idJefatura?>"><?php echo $row->descripcion?></option>     
                                            <?php }?>                                           
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>EECC</label>
                                        <select id="selectEECC" class="form-control select2" onchange="">
                                            <option value="">Seleccionar EECC</option>                                             
                                            <?php foreach($listaEECC->result() as $row ){?>
                                                <option value="<?php echo $row->idEmpresaColab?>"><?php echo $row->empresaColabDesc?></option>     
                                            <?php }?>                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>TIPO TECNICO</label>
                                        <select id="selectTipoTecnico" class="form-control select2" onchange="">
                                            <option value="">Seleccionar Tipo</option>                                             
                                            <option value="ACELERACION">ACELERACION</option>  
                                            <option value="CONVENCIONAL">CONVENCIONAL</option>                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <label>ESTADO</label>
                                        <select id="selectEstado" class="form-control select2" onchange="">
                                            <option value="">Seleccionar Pendiente</option>                                             
                                              <option value="PENDIENTE">PENDIENTE</option>
                                              <option value="EJECUTADO">EJECUTADO</option >                                     
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3">
                                    <div class="form-group">                                                   
                                          <table>
                                            <tr>
                                                <td colspan=2>RANGO DE FECHA</td>
                                            <tr>
                                            <tr>
                                                <td> <input name="fechaInicio" id="fechaInicio" type="text" class="form-control date-picker" placeholder="Desde"></td>
                                                <td><input name="fechaFin" id="fechaFin" type="text" class="form-control date-picker" placeholder="Hasta"></td>
                                            </tr>
                                          </table>                                                   
                                    </div>                                 
                                </div>
                                <div class="col-sm-2 col-md-2">
                                    <button class="btn btn-success waves-effect" type="button" onclick="filtrarDatos()">CONSULTAR</button>
                                </div>
                              
                            </div>
                            
                            <div class="row"> 
              				      <div class="col-sm-12 col-md-12" style="border-style: double;">                                 	
                            		<div id="contenedor_mapa" style="height: 420px; position: relative; overflow: hidden;"></div>
                        		</div>   
              				</div>
              				<br>
                            <div class="row"> 
                                <div class="col-sm-12 col-md-12">
                                    <div id="contTabla" class="table-responsive">
                                        <table id="data-table2" class="table table-bordered">
                                            <thead class="thead-default">
                                                <tr>
                                                    <th>TECNICO</th>
                                                    <th>CREADO</th>
                                                    <th>ASIGNADO</th>
                                                    <th>EJECUTANDO</th>
                                                    <th>VALIDANDO</th>
                                                    <th>APROBADA</th>
                                                    <th>TOTAL</th>
                                                    <th>% ASIGNACION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                    <th>-</th>
                                                    <th>-</th>
                                                    <th>-</th>                            
                                                    <th>-</th>
                                                    <th>-</th>
                                                    <th>-</th>                            
                                                    <th>-</th>
                                                    <th>-</th>
                                                </tr> 
                                            </tbody>
                                            </table>
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
            
            <div class="modal fade" tabindex="-1" id="modalSiom" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h3 class="modal-title">RESUMEN ACTIVIDAD</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>CODIGO NODO</label>
                                <input disabled id="txtNodo" name="txtNodo" type="text" class="form-control">
                            </div>                                        
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>NOMBRE NODO</label>
                                <input disabled id="txtNombreNodo" name="txtNombreNodo" type="text" class="form-control">
                            </div>                                        
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>COORDENADA X</label>
                                <input disabled id="txtCoordX" name="txtCoordX" type="text" class="form-control">
                            </div>                                        
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>COORDENADA Y</label>
                                <input disabled id="txtCoordY" name="txtCoordY" type="text" class="form-control">
                            </div>                                        
                        </div>
                    </div>
                        <div id="contTablaSiom">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>      
                </div>
            </div>
        </div>
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
        
        <script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
        
        
        <script src="<?php echo base_url();?>public/highcharts/highcharts.js" charset="UTF-8"></script>
        <script src="<?php echo base_url();?>public/highcharts/modules/drilldown.js"></script>
        <script src="<?php echo base_url();?>public/highcharts/modules/data.js"></script>
        <script src="<?php echo base_url();?>public/highcharts/modules/exporting.js" charset="UTF-8"></script>
        
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        
        
        
        <!-- Demo -->
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
        <script type="text/javascript">
         	var goblal_icon_url_odf          = '<?php echo base_url();?>public/img/iconos/mapa_tecnicos/icono_mapa_tecnico_';
		</script>
  	    <script src="<?php echo base_url();?>public/js/js_reporte_gerente/reporte_siom_tecnico_full.js?v=<?php echo time();?>"></script>		
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&callback=init" async defer></script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
</html>