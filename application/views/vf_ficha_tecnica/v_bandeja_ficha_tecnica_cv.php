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
        
         <style type="text/css">
           
            .select2-dropdown {
              z-index: 100000;
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
                   <a href="https://www.movistar.com.pe/" title="MOVISTAR"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo MOVISTAR" style="width: 36%; margin-left: -51%"></a>
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
           
                   <div class="content__inner">
                        <h2>FICHA TECNICA CRECIMIENTO VERTICAL INTEGRAL</h2>
                            <div class="card">
                                        
                                <div class="card-block">                                             
                                            <div class="row">

                    </div>
                                            <div id="contTabla" class="table-responsive">
                                                    <?php echo $tablaAsigGrafo?>
                           </div>
                                        </div>
                                    </div>
                                            
                  <!-- ----------------------------------------------------------------------------------- -->
                <div class="modal fade" id="modalKitMaterial" data-backdrop="static" data-keyboard="false" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <img style="width: 100px; heigth:40px" src="<?php echo base_url();?>public/img/logo/tdp.png">
                      
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="forRegistrarKitMate" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                           <div class="row"> 
                           		<div class="form-group col-sm-6	 col-xs-12">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">DIRECCION</label>  
                                    <input readonly="readonly" style="height: 33px;" id="txtDireccion" name="txtDireccion" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-2 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">NUMERO</label>  
                                    <input readonly="readonly" style="height: 33px;" id="txtNumero" name="txtNumero" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-2 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left"># PISOS</label>  
                                    <input readonly="readonly" style="height: 33px;" id="txtPisos" name="txtPisos" type="text" class="form-control">                                                                      
                                </div>
                                <div class="form-group col-sm-2 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left"># DPTOS</label>  
                                    <input readonly="readonly" style="height: 33px;" id="txtDepartamentos" name="txtDepartamentos" type="text" class="form-control">                                                                      
                                </div>     

                                <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">TIPO TRABAJO</label>  
                                    <select disabled style="height: 33px;width: 100%;" id="selectTipoTrabajo" name="selectTipoTrabajo" class="select2 selectForm">
                                             <option value="1">SUBTERRANEO</option>
                                             <option value="2">AEREO</option>
                                   	</select>
                                </div>
                                 <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CTO</label>  
                                    <select disabled style="height: 33px;width: 100%;" id="selectInstala" name="selectInstala" class="select2 selectForm">                                             
                                             <option value="SI">SI</option>
                                             <option value="NO">NO</option>                                              
                                   	</select>                                          
                                </div>
                                 <div class="form-group col-sm-4 col-xs-6">
                                 	<label style="font-weight: bold;font-size: x-small;" class="control-label mb-10 text-left">CAMARA</label>  
                                    <select disabled style="height: 33px;width: 100%;" id="selectCamara" name="selectCamara" class="select2 selectForm">                                             
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
                              <div class="form-group" style="text-align: right;width: 100%;">
                              <div class="col-sm-12">
                              <div class="form-group">
                                <textarea id="textComentario" maxlength="250" class="form-control" rows="3" placeholder="COMENTARIO...."></textarea>
                                <i class="form-group__bar"></i>
                                </div>
                              </div>
                                <div class="col-sm-12">
                                    <button id="btnRechazar" data-acc="2" onclick="validarFic(this)" type="button" class="btn btn-danger">RECHAZAR</button>
                                    <button id="btnAprobar" data-acc="1" onclick="validarFic(this)" type="button" class="btn btn-primary">APROBAR</button>
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
        
        
        

        <!-- Older IE warning message -->
            <!--[if IE]>
                <div class="ie-warning">
                    <h1>Warning!!</h1>
                    <p>You are using an outdated version of Internet Explorer, please upgrade to any of the following web browsers to access this website.</p>

                    <div class="ie-warning__downloads">
                        <a href="http://www.google.com/chrome">
                            <img src="img/browsers/chrome.png" alt="">
                        </a>

                        <a href="https://www.mozilla.org/en-US/firefox/new">
                            <img src="img/browsers/firefox.png" alt="">
                        </a>

                        <a href="http://www.opera.com">
                            <img src="img/browsers/opera.png" alt="">
                        </a>

                        <a href="https://support.apple.com/downloads/safari">
                            <img src="img/browsers/safari.png" alt="">
                        </a>

                        <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">
                            <img src="img/browsers/edge.png" alt="">
                        </a>

                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="img/browsers/ie.png" alt="">
                        </a>
                    </div>
                    <p>Sorry for the inconvenience!</p>
                </div>
            <![endif]-->

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
         <script src="<?php echo base_url();?>public/dropzone/downloads/dropzone.min.js"></script>
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
       
        <script type="text/javascript">
            
        function registrarKit(btn) {
        	var itemPlan = btn.data('itemplan');
        	var idSubPro = btn.data('idsubpro');
        	var accion	 = btn.data('accion');
        	var idFicha	 = btn.data('id_ficha');
        	
        	 $.ajax({
        	            type : 'POST',
        	            url  : 'getContMateriales',
        	            data : {itemplan : itemPlan,
        	                    idSubPro : idSubPro,
        	                    accion	:	accion}                    
        	    }).done(function(data){
        	        data = JSON.parse(data);
        	        if(data.error == 0){
        	            $('#bodyTable').html(data.htmlConTabla);
        	            soloDigitos('canclass');
        	            $('#txtDireccion').val(data.direccion);
        	            $('#txtNumero').val(data.numero);
        	            $('#txtPisos').val(data.pisos);
        	            $('#selectInstala').val((data.cto != '') ? data.cto : 'SI').trigger('change');
        	            $('#selectCamara').val((data.camara != '') ? data.camara : 'SI').trigger('change');	
        	            $('#selectTipoTrabajo').val((data.tipoPartida != '') ? data.tipoPartida : '1').trigger('change');   
        	            $('#txtDepartamentos').val(data.dptos);      
        	            $('#textComentario').val('');   
        	            $('#btnAprobar').attr('data-fic', idFicha);
        	            $('#btnRechazar').attr('data-fic',idFicha);     
        	            $('#btnAprobar').attr('data-item', itemPlan);
        	            $('#btnRechazar').attr('data-item', itemPlan);           
        	            $('#modalKitMaterial').modal('toggle');
        	        }
        	    });
        	}

        function validarFic(Component){
        	var accion     = $(Component).attr('data-acc');
        	var ficha      = $(Component).attr('data-fic');
        	var itemplan   = $(Component).attr('data-item');
        	var comentario = $('#textComentario').val();
        	$.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'valFTCV',
     	    	data	:	{accion 		: accion,
          	    		     ficha          : ficha,
            	    		 itemplan		: itemplan,
            	    		 comentario     : comentario},
     	    	'async'	:	false
     	    })
     	    .done(function(data){
     	    	var data	=	JSON.parse(data);
     	    	if(data.error == 0){          
     	    		$('#contTabla').html(data.tablaAsigGrafo);
     	    	    initDataTable('#data-table'); 	    	   
     	    		$('#modalKitMaterial').modal('toggle');
     	    		mostrarNotificacion('success','Operacion Exitosa.', 'Se registro correcamente!');
     			}else if(data.error == 1){     				
     				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
     			}
     		  });
        }
        
        function zipItemPlan(btn) {
        	var itemPlan = btn.data('item_plan');
        	console.log(itemPlan);
        	var val = null;
        	if(itemPlan == null || itemPlan == '') {
        	    return;
        	}

        	$.ajax({
        	    type : 'POST',
        	    url  : 'zipItemPlan',
        	    data : { itemPlan : itemPlan }
        	}).done(function(data){
        	    try {
            	    console.log(data);
        	        data = JSON.parse(data);
        	        if(data.error == 0) {
        	            var url= data.directorioZip; 
        	            if(url != null) {
        	                val = window.open(url, 'Download');
        	            } else {
        	                alert('No tiene evidencias');
        	            }   
        	            // mostrarNotificacion('success', 'descarga realizada', 'correcto');
        	        } else {
        	            // mostrarNotificacion('error', 'descarga no realizada', 'error');            
        	            alert('error al descargar');
        	        }
        	    } catch(err) {
        	        alert(err.message);
        	    }
        	});
        	}
        </script>
    </body>
</html>