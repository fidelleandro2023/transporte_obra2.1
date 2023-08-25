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
                                    <h2>REGISTRAR COTIZACION</h2>
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
                <div class="modal fade" id="edi-evidencias"  tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="tituloModalEvi" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">                                        
                         
                            <div class="row">
                                <!-- 
                                <div class="col-sm-1 col-md-1">
                                    <div id="contTablaEvi" style="padding-top: 30px;"></div>                                    
                                </div>
                                -->
                                 <div class="col-sm-3 col-md-3">
                                    <div id="contTablaEvi" style="padding-top: 30px;"></div>
                                     <div class="form-group">
                                        <label>MONTO MO</label>
                                        <input style="border-style: groove;border-width: 1px;" id="inputMonto" name="inputMonto" type="number" class="form-control">
                                    </div>
                                </div>
                                 <div class="col-sm-3 col-md-3">
                                    <div id="contTablaEvi" style="padding-top: 30px;"></div>
                                     <div class="form-group">
                                        <label>MONTO MAT</label>
                                        <input style="border-style: groove;border-width: 1px;" id="inputMontoMat" name="inputMontoMat" type="number" class="form-control">
                                    </div>
                                </div>
                                <!-- 
                                <div class="col-sm-2 col-md-2">
                                    <div id="contTablaEvi" style="padding-top: 30px;"></div>                                    
                                </div>
                                -->
                                <div class="col-sm-6 col-md-6">  
                                    <div id="contTablaEvi" ></div>
                                     <div id="dropzone4" class="dropzone" style="padding-top: 30%;">
                                     
                                     </div>
                                </div>
                            </div>                                 
                                 <button onclick="cerrarModalEditEvi();" type="submit" id="btnAddNewIMGyPdf" class="btn btn-primary" style="background-color:#FFC107;float:right;margin-top:10px" name="btnAddNewIMG">Guardar</button>
                                 <!-- <div id="contTablaEvi" style="padding-top: 60px;"></div>-->
                             
                        </div>                            
                        </div>
                      
                    </div>
                </div>    
                  
                <div class="modal fade" id="modalSelectEECC" >
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title pull-left">Seleccione Central</h5>
                            </div>
                            <div class="modal-body">
                                 <div class="col-sm-12 col-md-12">
                                             <div class="form-group">
                                                <label>CENTRAL</label>
                                                <select id="selectCentral" name="selectCentral" class="select2 form-control" onchange="changueCentral()">
                                                       <option value="0">&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                            </div>
                                         </div>
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label>ZONAL</label>
                                                    <select id="selectZonal" name="selectZonal" class="select2 form-control" >
                                                        <option value="">&nbsp;</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label>EMPRESA COLABORADORA</label>
                                                <select id="selectEmpresaColab" name="selectEmpresaColab" class="select2 form-control">
                                                    <option value="">&nbsp;</option>
                                                </select>
                                            </div>
                                        </div>
                            </div>
                            <div class="modal-footer">
                                <button id="btnSaveCentral" type="button" class="btn btn-link waves-effect">Guardar</button>
                                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Cancelar</button>
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
        var toog2=0;
        var error=0;
        var empty_monto=0;
        var empty_monto_mat=0;
        Dropzone.autoDiscover = false;
        var itemplan = '';
        $("#dropzone4").dropzone({
            url: "insertEviCoti",
            addRemoveLinks: true,
            autoProcessQueue: false,
            parallelUploads: 30,
            maxFilesize: 3,
            maxFiles: 1,
            acceptedFiles: "application/pdf",
            dictResponseError: "Ha ocurrido un error en el server",
            
            complete: function(file){
                if(file.status == "success"){
            //SUBIO LA IMAGEN
                    error=0;
                }
            },
            removedfile: function(file, serverFileName){
                var name = file.name;
                            var element;
                            (element = file.previewElement) != null ? 
                            element.parentNode.removeChild(file.previewElement) : 
                            false;
                            toog2=toog2-1;      
                            $("#dropzone4").css("padding-top", "30%");      
            },
            init: function() {            	
                this.on("error", function(file, message) {
                    console.log(message);
                      alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no se tomara en cuenta');
                    //  mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no serÃ¯Â¿Â½ tomado en cuenta');
                        error=1;
                      // alert(message);
                        this.removeFile(file); 
                });
                
                var submitButton = document.querySelector("#btnAddNewIMGyPdf")
                    myDropzone = this; // closure            
                //evento submit subimos todo
                submitButton.addEventListener("click", function() {   
             	        var monto     = $('#inputMonto').val();
                    	var monto_mat = $('#inputMontoMat').val();
                    	if(monto   ==  ''){   
                    		empty_monto = 0; 
                        	alert('Ingrese monto MO.');                        
                    	}else if(monto_mat ==  ''){
                    		empty_monto = 0; 
                        	alert('Ingrese monto MAT.');   
                	    }else{                        
                  		    myDropzone.processQueue(); 
                    	}
               //    if(pick!='' && fec!='' && hora!='' && part!=''){
                        
                //   }              
                    // Tell Dropzone to process all queued files.
                   });
               
               var concatEvi = '';
                // You might want to show the submit button only when 
                // files are dropped here:
                this.on("addedfile", function() {         
                	$("#dropzone4").css("padding-top", "0%");      
                    toog2=toog2+1;  
                  // Show submit button here and/or inform user to click it.
                });
                
                this.on('complete', function () {
                	console.log('put monto 1');
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {                  
                        if(error == 0){console.log('complete!');
                            $('#edi-evidencias').modal('toggle');
                        }               
                
                    }           
                });
                
                this.on("queuecomplete", function (file) {
                	console.log('put monto 2');
                    if(error == 0){              
                        this.removeAllFiles(true); 
                        mostrarNotificacion('success','Registro','Se Registro Correctamente');                    
                    }
                });     

                 this.on("success", function(file, responseText) {//Trae el ID De la imagen insertada
                	 console.log('put monto 3');
                	 var data    =   JSON.parse(responseText);
                     if(data.error == 0){                                           
                         $('#contTabla').html(data.tablaAsigGrafo)
                         initDataTable('#data-table');
                     }else if(data.error == 1){                         
                         mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                     }                	              
                 });

                 this.on('sending', function(file, xhr, formData){ 
                	 
                     var monto = $('#inputMonto').val();
                     formData.append('monto_mo', monto);
                     var monto_mat = $('#inputMontoMat').val();
                     formData.append('monto_mat', monto_mat);
                     var itemplan = $('#btnAddNewIMGyPdf').attr('item');
                     formData.append('itemplan', itemplan);
                 });
              }
            
        });

            function cerrarModalEditEvi(){            
            	console.log('Close Modal'); 
                if(toog2    ==  0){console.log('no tiene evidencia!');
                    if(empty_monto == 1 && empty_monto_mat == 1){
                        console.log('tiene monto!');
                        
                        var monto = $('#inputMonto').val();
                        var monto_mat = $('#inputMontoMat').val();
                        var itemplan = $('#btnAddNewIMGyPdf').attr('item');
                        
                        $.ajax({
                 	    	type	:	'POST',
                 	    	'url'	:	'updMonto',
                 	    	data	:   { itemplan : itemplan,
                 	    	              monto  :   monto,
                  	    	             monto_mat : monto_mat},
                 	    	'async'	:	false
                 	    })
                 	    .done(function(data){
                 	    	var data	=	JSON.parse(data);                 	    	
                 	    	if(data.error == 0){                 	    	
        					   $('#contTabla').html(data.tablaAsigGrafo);
                   	    	   initDataTable('#data-table');
                      	    	$('#edi-evidencias').modal('toggle');
                       	    	mostrarNotificacion('success','OperaciÃ³n Ã©xitosa.',data.msj); 
                 			}else if(data.error == 1){
                 				
                 				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
                 			}
                 		  })
                 		  .fail(function(jqXHR, textStatus, errorThrown) {
                  			 mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);                  			 
                 		  })
                 		  .always(function() {
                 	  	 
                 		});
                        
                    	
                    }else{
                    	console.log('no tiene monto!');
                    }
                }                    
             
            }
            
        function openUploadFile(component){        
        	var itemplan = $(component).attr('data-itemplan');
     	    $('#tituloModalEvi').html('Itemplan '+itemplan);
        	$('#btnAddNewIMGyPdf').attr('item', itemplan);
        	empty_monto = 0;
        	$('#inputMonto').val('');
        	empty_monto_mat = 0;
        	$('#inputMontoMat').val('');
     	    $('#edi-evidencias').modal('toggle');       
        }
            
  
       
        function closeCertificado(){              
            $('#modal-cert').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');          
        }      
        
        var encodeRoute = null;

        function enviarCotizacion(component){
        	var idCentral = $(component).attr('data-idCen');
        	var itemplan = $(component).attr('data-itemplan');  
        	if(idCentral!=''){
        		sentCotiToValida(itemplan);
          	}else{
          		$('#selectZonal').html('<option value="">&nbsp;</option>');
        		$('#selectZonal').val("").trigger('chosen:updated');
        		$('#selectEmpresaColab').html('<option value="">&nbsp;</option>');
        		$('#selectEmpresaColab').val("").trigger('chosen:updated');
        		$('#selectCentral').val("0").trigger('change');
        		$('#btnSaveCentral').attr('data-itemplan', itemplan);
        		$('#btnSaveCentral').attr('data-from', 1);
            	$('#modalSelectEECC').modal('toggle');
            	//sentCotiToValida(itemplan);
          	} 
        }

        function sentCotiToValida(item){
            var itemplan = item;
        	swal({
                title: 'EstÃ¡ seguro de enviar la Cotizacion?',
                text: 'Recuerde que luego tendra que esperar la aprobacion de esta!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, enviar!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){
             	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'sendCoti',
         	    	data	:   { itemplan : itemplan},
         	    	'async'	:	false
         	    })
         	    .done(function(data){
         	    	var data	=	JSON.parse(data);                 	    	
         	    	if(data.error == 0){
         	    	
					   $('#contTabla').html(data.tablaAsigGrafo);
           	    	   initDataTable('#data-table');
              	    	 
               	    	mostrarNotificacion('success','OperaciÃ³n Ã©xitosa.',data.msj); 
         			}else if(data.error == 1){
         				
         				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
         			}
         		  })
         		  .fail(function(jqXHR, textStatus, errorThrown) {
          			 mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);                  			 
         		  })
         		  .always(function() {
         	  	 
         		});
         	   
            });
            
        }

        function    sendToEECC(component){
        	var idCentral = $(component).attr('data-idCen'); 
        	var itemplan  = $(component).attr('data-itemplan');   
         	if(idCentral!=''){
        		 enviarCotiToEecc(itemplan);            	
        	}else{
        		$('#selectZonal').html('<option value="">&nbsp;</option>');
        		$('#selectZonal').val("").trigger('chosen:updated');        		
        		$('#selectEmpresaColab').html('<option value="">&nbsp;</option>');
        		$('#selectEmpresaColab').val("").trigger('chosen:updated');
        		$('#selectCentral').val("0").trigger('change');
        		$('#btnSaveCentral').attr('data-itemplan', itemplan);
        		$('#btnSaveCentral').attr('data-from', 2);      	
            	$('#modalSelectEECC').modal('toggle');            	
        	}
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
                  //  $('#inputNombrePlan').val('');      
                  //  $('#inputNombrePlan').val($('#selectCentral option:selected').text());
                    $('#selectZonal').html(data.listaZonal);
                    $('#selectZonal').val(data.idZonalSelec).trigger('chosen:updated');
                    $('#selectEmpresaColab').html(data.listaEECC);
                    $('#selectEmpresaColab').val(data.idEECCSelec).trigger('chosen:updated');
                    //$('#selectCentral').select2({ width: '100%' });                    
                 //   $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectZonal');
                //    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectEmpresaColab');
                 //   $('#formAddPlanobra').bootstrapValidator('revalidateField', 'inputNombrePlan');
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }

           $('#btnSaveCentral').click(function(){
       	        var idCentral   = $('#selectCentral').val();
       	        var idZonal     = $('#selectZonal').val();
       	        var idEECC      = $('#selectEmpresaColab').val();   
       	        var itemplan    = $(this).attr('data-itemplan');
          	    var from    = $(this).attr('data-from');
       	        if(idCentral == '0'){
          	         alert('Debe seleccionar una Central');
       	   	    }else{
             	   	  $.ajax({
                          type    :   'POST',
                          'url'   :   'updCentral',
                          data    :   { idCentral : idCentral,
                      	                idZonal   : idZonal,
                        	            idEECC    : idEECC,
                        	            itemplan  : itemplan },
                          'async' :   false
                      })
                      .done(function(data){
                          var data    =   JSON.parse(data);
                          if(data.error == 0){ 
                        	   $('#contTabla').html(data.tablaAsigGrafo);
                 	    	   initDataTable('#data-table');
                               $('#modalSelectEECC').modal('toggle');
                               if(from == '1'){
                            	   sentCotiToValida(itemplan);
                               }else if(from == '2'){
                            	   enviarCotiToEecc(itemplan);
                               }
                               
                          }else if(data.error == 1){                              
                              mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                              $('#modalSelectEECC').modal('toggle');
                          }
                      });
           	   	    
             	   	  
              	     
           	   	}
           });



           function enviarCotiToEecc(itemplan){
               var item = itemplan;             
              	swal({
                      title: 'EstÃ¡ seguro de enviar la Cotizacion a la contrata?',
                      text: 'Recuerde que luego tendra que esperar que la contrata carge la informacion!',
                      type: 'warning',
                      showCancelButton: true,
                      buttonsStyling: false,
                      confirmButtonClass: 'btn btn-primary',
                      confirmButtonText: 'Si, enviar!',
                      cancelButtonClass: 'btn btn-secondary'
                  }).then(function(){
                                         	
               	    $.ajax({
               	    	type	:	'POST',
               	    	'url'	:	'sendToEC',
               	    	data	:   { itemplan : item},
               	    	'async'	:	false
               	    })
               	    .done(function(data){
               	    	var data	=	JSON.parse(data);                 	    	
               	    	if(data.error == 0){
                  	    	$('#contTabla').html(data.tablaAsigGrafo);
                 	    	initDataTable('#data-table');
      					   	mostrarNotificacion('success','OperaciÃ³n Ã©xitosa.',data.msj); 
               			}else if(data.error == 1){
               				
               				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
               			}
               		  })
               		  .fail(function(jqXHR, textStatus, errorThrown) {
                			 mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);                  			 
               		  })
               		  .always(function() {
               	  	 
               		});
               	   
                  });
                               
              }

           $( "#inputMonto" ).change(function() {
        	   console.log($(this).val());
        	   if($(this).val()!=''){
        		   empty_monto = 1;
        	   }
        	 });

           $( "#inputMontoMat" ).change(function() {
        	   console.log($(this).val());
        	   if($(this).val()!=''){
        		   empty_monto_mat = 1;
        	   }
        	 });
        </script>
    </body>
</html>