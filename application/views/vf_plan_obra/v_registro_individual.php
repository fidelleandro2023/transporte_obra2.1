<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


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
                                         <div class="col-sm-4 col-md-4">
                                             <div class="form-group">
                                                <label>CENTRAL</label>
                                                <select id="selectCentral" name="selectCentral" class="select2 form-control" onchange="changueCentral()">
                                                       <option value="">&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                            </div>
                                         </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>ZONAL</label>
                                                    <select id="selectZonal" name="selectZonal" class="select2 form-control" >
                                                        <option value="">&nbsp;</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <label>EMPRESA COLABORADORA</label>
                                                <select id="selectEmpresaColab" name="selectEmpresaColab" class="select2 form-control">
                                                    <option value="">&nbsp;</option>
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
                                                <label>FASE</label>
                                                <select id="selectFase" name="selectFase" class="select2 form-control" >
                                                    <option>&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listafase->result() as $row){                      
                                                        ?> 
                                                         <?php if($row->faseDesc == '2019'){//SOLO FASE 2019 A PARTIR DEL 20.12.2018?>
                                                         <option value="<?php echo $row->idFase ?>"><?php echo $row->faseDesc ?></option>
                                                         <?php }?>
                                                         <?php }?>
                                                     
                                                </select>
                                            </div>
                                        </div>
                                        <!-- 
                                        <div class="col-sm-4 col-md-4">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>CANTIDAD OBRA</label>
                                                <input id="inputCantObra" name="inputCantObra" type="text" class="form-control"><i 
                                                class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div> 
                                        </div>
                                        -->
                                        <div class="col-sm-4 col-md-4">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>COORDENADAS X</label>
                                                <input id="inputCoordX" name="inputCoordX" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>COORDENADAS Y</label>
                                                <input id="inputCoordY" name="inputCoordY" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <!-- 
                                        <div class="col-sm-4 col-md-4">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>UIP</label>
                                                <input id="inputUIP" name="inputUIP" type="number" min="0" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        -->
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
                                                          
                                                 <div class="col-sm-3 col-md-3">
                                           			 <div class="row">
                                           					<div class="form-group col-sm-12">
                                                           	    <label>DEPARTAMENTO</label>
                                                                <input id="txt_departamento" name="txt_departamento" type="text" class="form-control">                                                                      
                                                            </div>
                                                            <div class="form-group col-sm-12">
                                                           	    <label>PROVINCIA</label>
                                                                <input id="txt_provincia" name="txt_provincia" type="text" class="form-control">                                                                      
                                                            </div>
                                                            <div class="form-group col-sm-12">
                                                           	    <label>DISTRITO</label>
                                                                <input id="txt_distrito" name="txt_distrito" type="text" class="form-control">                                                                      
                                                            </div>             
                                                           	                                                                  
                                                            <div class="form-group col-sm-12">
                                                                <label>FEC. RECEPCION</label>
                                                                <input id="fecRecepcion" name="fecRecepcion" type="text" class="form-control date-picker">                                         
                                                            </div>
                                                            
                                                            <div class="form-group col-sm-12">
                                                           	    <label>NOMBRE CLIENTE</label>
                                                                <input id="inputNomCli" name="inputNomCli" type="text" class="form-control" onchange="updateNumCarta()">                                                                      
                                                            </div>  
                                                       </div>
                                            	</div>                            	
                              				
                                             	<div class="col-sm-9 col-md-9" style="border-style: double;">
                                                 	<div style=" position: absolute;top: -20px;left: 35%;z-index: 5;background-color: #fff;padding: 5px;text-align: center;line-height: 25px;padding-left: 10px;">
                                              		 	<input type="text" id="search"> <input type="button" value="Buscar Direccion" onClick="searchDireccion()">
                                              		</div>
                                            		<div id="contenedor_mapa" style="height: 420px; position: relative; overflow: hidden;"></div>
                                        		</div>
                                                		
                                               <div style="margin-top: 15px;" class="col-sm-12 col-md-12">
                                           			 <div class="row">
                                           					           
                                                             <div class="col-sm-3 col-md-3">
                                                                   	    <label>NUMERO DE CARTA</label>
                                                                        <input id="inputNumCar" name="inputNumCar" onchange="updateNumCarta()" type="text" class="form-control">                                                                      
                                                              </div>
                                                              <div class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>AÑO</label>
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
                    </form>
                    </div>
                </div>
            </div>
                                            
                                            
                                            
                                            
                                            
			                <footer class="footer hidden-xs-down">
			                    <p>Telefonica del Peru</p>
		                   </footer>
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
        
        <!-- google maps -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&callback=init" async defer></script>
        
        <script type="text/javascript">
        

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
            
            var descSupProyecto = $('#selectSubproy option:selected').text();
            var arreglo = descSupProyecto.split(" ");
            var flgMarcar = 0;

            arreglo.forEach(item => {
                if(item == '2017'){
                    flgMarcar = 1;
                }
            });

            if(flgMarcar == 1){
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
            }
            flgAgrega = 1;
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
            var proyecto = $.trim($('#selectProy').val()); 
             $.ajax({
                type    :   'POST',
                'url'   :   'getSubProPO',
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
                    }else{
                    	$('#contItemMadre').hide();
                    	$('#inputItemMadre').val('');
                    }

                    if(proyecto ==  4){//OBRAS PUBLICAS
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
                        $('#contFecIni').hide();
                        $('#contFecPrev').hide();
                        $('#contCotizacion').hide();
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
                'url'   :   'getFechaSubproOP',
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
                'url'   :   'getZonalPO',
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
                'url'   :   'getEECCPO',
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
                    },/*
        	    	inputFechaInicio: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe ingresar la fecha de inicio del plan.</p>'
        	                }
        	            }
        	        },*/
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
                    }        	       
        	    }
        	}).on('success.form.bv', function(e) {
        		e.preventDefault();       		

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
		            
        		    $.ajax({
    			        data: formData,
    			        url: "addPlanobra",
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
                        	            title: 'Se genero corecctamente el Itemplan',
                        	            text: itemplan,
                        	            type: 'success',
                        	            showCancelButton: false,                    	            
                        	            allowOutsideClick: false
                        	        }).then(function(){
                            	        location.reload();
                        	        });
    						}else if(data.error == 1){
    							mostrarNotificacion('error','Error','No se inserto el Plan de obra:'+data.msj);
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

            
            /************METODOS GOOGLE MAP**************/
            var marker = null;
            var map = null;
            var center = null;
            
            function init(){
                
                var mapdivMap = document.getElementById("contenedor_mapa");                
                center = new google.maps.LatLng(-12.0431800, -77.0282400);
                var myOptions = {
                    zoom: 5,
                    center: center,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                map = new google.maps.Map(document.getElementById("contenedor_mapa"), myOptions);
                infoWindow = new google.maps.InfoWindow();
                marker = new google.maps.Marker({
                    map: map,
                    title:"Tu posicion",
                    draggable: true,
                    animation: google.maps.Animation.DROP
                  });

                var geocoder = new google.maps.Geocoder();
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

                google.maps.event.addListener(map, 'click', function(event) {
            		marker.setMap(null);
            		
            	    marker = new google.maps.Marker({
                    position: event.latLng,
                    map: map,
                    title:"Tu posiciÃ³n",
                    draggable: true,
                    animation: google.maps.Animation.DROP
                  });

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
       	    /*
          	console.log('results 0:'+JSON.stringify(results[0]));
          	console.log('results 1:'+JSON.stringify(results[1]));
          	console.log('results 2:'+JSON.stringify(results[2]));
          	console.log('results 3:'+JSON.stringify(results[3]));
          	console.log('results 4:'+JSON.stringify(results[4]));
          	console.log('results 5:'+JSON.stringify(results[5]));
          	console.log('results 6:'+JSON.stringify(results[6]));
          	console.log('results 7:'+JSON.stringify(results[7]));
          	console.log('results 8:'+JSON.stringify(results[8]));
          	//CHIMBOTE
          	console.log('results 11:'+results[1]['address_components'][1].long_name.toUpperCase());
          	console.log('results 22:'+results[1]['address_components'][2].long_name.toUpperCase());
            //DISTRITO
          	console.log('results 33:'+results[1]['address_components'][3].long_name.toUpperCase());

          	console.log('results 44:'+results[0]['address_components'][1].long_name.toUpperCase());
          	console.log('results 55:'+results[4]['address_components'][1].long_name.toUpperCase());
          	console.log('results 66:'+results[6]['address_components'][0].long_name.toUpperCase());
          	*/
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
         
        //  function prueba(){
        //     $.ajax({
        //         type: 'POST',
        //         url: 'pruebaInsertIP',
        //         data: {
        //             id  : 123,
        //             sisego : '2018-12-86812',
        //             envio: '15-03-2019',
        //             mdf : 'WA',
        //             segmento: 'EMPRESAS',
        //             cliente: 'COMERCIALIZ.YDISTRIBUIDORAJIMENEZSAC'
        //         }
        //     }).done(function (data) {
        //         data = JSON.parse(data);
        //         if(data.error == 0){       
        //             mostrarNotificacion('success',data.msj);
        //         }else if(data.error == 1){
                    
        //             mostrarNotificacion('error',data.msj);
        //         }
        //     });
        // }
        
        </script> 
        
       
        
    </body>


</html>