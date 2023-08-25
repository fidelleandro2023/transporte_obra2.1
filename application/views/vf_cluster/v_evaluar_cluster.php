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
                    <h2>EVALUAR CLUSTER - <?php echo $codClust?></h2>                        
	                    <div class="card">			                        
	                        <div class="card-block">
                                <form id="formAddPlanobra" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                    <div class="row">
                                         <div class="col-sm-3 col-md-3">
                                             <div class="form-group">
                                                <label>NODO PRINCIPAL</label>
                                                <select id="selectCentral" name="selectCentral" class="select2 form-control">
                                                       <option value="">&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                            </div>
                                         </div> 
                                         <!-- 
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label>ZONAL</label>
                                                    <select id="selectZonal" name="selectZonal" class="select2 form-control" >
                                                        <option value="">&nbsp;</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label>EMPRESA COLABORADORA</label>
                                                <select id="selectEmpresaColab" name="selectEmpresaColab" class="select2 form-control">
                                                    <option value="">&nbsp;</option>
                                                </select>
                                            </div>
                                        </div>                           
                                        <div class="col-sm-3 col-md-3">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>COORDENADAS X</label>
                                                <input id="inputCoordX" name="inputCoordX" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>COORDENADAS Y</label>
                                                <input id="inputCoordY" name="inputCoordY" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        -->
                                          <div class="col-sm-3 col-md-3">
                                             <div class="form-group">
                                                <label>NODO RESPALDO</label>
                                                <select id="selectCentral2" name="selectCentral2" class="select2 form-control">
                                                       <option value="">&nbsp;</option>
                                                      <?php                                                    
                                                            foreach($listaTiCen->result() as $row){                      
                                                        ?> 
                                                         <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                         <?php }?>
                                                     
                                                </select>
                                            </div>
                                         </div> 
                                         
                                         <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>FACILIDADES DE RED</label>
                                                <input id="inputFacRed" name="inputFacRed" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>CANTIDAD CTO</label>
                                                <input id="inputCantCTO" name="inputCantCTO" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>METROS TENDIDO</label>
                                                <input id="inputMetroTen" name="inputMetroTen" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>METROS CANALIZACION</label>
                                                <input id="inputMetroCana" name="inputMetroCana" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>CANT. CAMARAS NUEVAS</label>
                                                <input id="cantCamaNue" name="cantCamaNue" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>  
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>CANT. POSTES NUEVOS</label>
                                                <input id="inputPostNue" name="inputPostNue" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>  
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>CANT. POSTES EE APOYO</label>
                                                <input id="inputCantPostApo" name="inputCantPostApo" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>  
                                      
                                        <div class="col-sm-3 col-md-3" id="contKickoff">
                                            <div class="form-group">
                                                <label>REQUIERE SEIA</label>
                                                <select id="selectRequeSeia" name="selectRequeSeia" class="select2 form-control">
                                                    <option value="NO">NO</option>     
                                                    <option value="SI">SI</option>                                                    
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-sm-3 col-md-3" id="contKickoff">
                                            <div class="form-group">
                                                <label>REQUIERE APROBACION MML, MTC</label>
                                                <select id="selectRequeAproMmlMtc" name="selectRequeAproMmlMtc" class="select2 form-control">
                                                    <option value="NO">NO</option>     
                                                    <option value="SI">SI</option>                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3" id="contKickoff">
                                            <div class="form-group">
                                                <label>REQUIERE APROBACION INC(PMA)</label>
                                                <select id="selectRequeAprobINC" name="selectRequeAprobINC" class="select2 form-control">
                                                    <option value="NO">NO</option>     
                                                    <option value="SI">SI</option>                                                    
                                                </select>
                                            </div>
                                        </div>   
                                       
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO MATERIALES</label>
                                                <input onchange="getcalculos()" id="inputCostoMat" name="inputCostoMat" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO MANO DE OBRA</label>
                                                <input onchange="getcalculos()" id="inputCostMo" name="inputCostMo" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO DISEÑO</label>
                                                <input onchange="getcalculos()" id="inputCostoDiseno" name="inputCostoDiseno" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO EXPEDIENTE SEIA,CIRA,PMEA S./</label>
                                                <input onchange="getcalculos()" id="inputCostoExpe" name="inputCostoExpe" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO ADICIONALES ZONA RURAL S./</label>
                                                <input onchange="getcalculos()" id="inputCostoAdicZona" name="inputCostoAdicZona" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group has-feedback" style="">
                                                <label>COSTO TOTAL S./</label>
                                                <input disabled id="inputCostoTotal" name="inputCostoTotal" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>  
                                <!-- FIN DE CONTENIDO OBRAS PUBLICAS     -->
                         <div class="col-sm-12 col-md-12" style="text-align: center;">
                            <div id="mensajeForm"></div>
                         </div>  
                         <div class="col-sm-12 col-md-12" style="text-align: center;">
                            <div class="form-group" style="text-align: center;">
                                <div class="col-sm-12">                                      
                                    <button data-cod="<?php echo $codClust?>" id="btnSave" type="submit" class="btn btn-primary">ENVIAR COTIZACION</button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </form>
                    <!-- <button onclick="testUpdate()" class="btn btn-primary">TEST</button>   -->
                    <div id="contTabla" class="table-responsive">
                                                    <?php echo $tablaHijos?>
                           </div>
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
        
       
        <script type="text/javascript">
        /*
        function testUpdate(){
        	var central = $.trim($('#selectCentral').val()); 
            $.ajax({
               type    :   'POST',
               'url'   :   'clusterUpdate',
               data    :   {codigo  : 'CL-000001',
           	                estado : 2},
               'async' :   false
           })
           .done(function(data){
               var data    =   JSON.parse(data);
               if(data.error == 0){ 
                   console.log(data);
                 }else if(data.error == 1){                   
                   mostrarNotificacion('error',data.msj);
               }
           });
        }
*/

        
        soloDecimal('inputCostoMat');
        soloDecimal('inputCostMo');
        soloDecimal('inputCostoDiseno');
        soloDecimal('inputCostoExpe');
        soloDecimal('inputCostoAdicZona');
           
        function    getcalculos(){       	    
        	var costoMat   = $('#inputCostoMat').val();
        	var costoMo    = $('#inputCostMo').val();
        	var costoDise  = $('#inputCostoDiseno').val();
        	var costoExpe  = $('#inputCostoExpe').val();
        	var costoAdic  = $('#inputCostoAdicZona').val();        	
        	var inputCostoTotal = Number(costoMat)+Number(costoMo)+Number(costoDise)+Number(costoExpe)+Number(costoAdic);        	
        	$('#inputCostoTotal').val(inputCostoTotal.toFixed(2));            
        }
        /*
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
                   // $('#inputNombrePlan').val('');      
                   // $('#inputNombrePlan').val($('#selectCentral option:selected').text());
                    $('#selectZonal').html(data.listaZonal);
                    $('#selectZonal').val(data.idZonalSelec).trigger('chosen:updated');
                    $('#selectEmpresaColab').html(data.listaEECC);
                    $('#selectEmpresaColab').val(data.idEECCSelec).trigger('chosen:updated');
                    
                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectZonal');
                    $('#formAddPlanobra').bootstrapValidator('revalidateField', 'selectEmpresaColab');
                    //$('#formAddPlanobra').bootstrapValidator('revalidateField', 'inputNombrePlan');
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
            });
        }
        */
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
                        	       
    	    }
    	}).on('success.form.bv', function(e) {
    		e.preventDefault();       		

    		swal({
	            title: 'Est&aacute; seguro de enviar la Cotizacion?',
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
    	        var    cod_cluster   = $('#btnSave').attr('data-cod');
    	        formData.append('cod_cluster', cod_cluster);
    		    $.each(params, function(i, val) {
    		        formData.append(val.name, val.value);
    		    });
	            
    		    $.ajax({
			        data: formData,
			        url: "saveCotiClus",
			        cache: false,
		            contentType: false,
		            processData: false,
		            type: 'POST'
			  	})
				  .done(function(data) {  
					    	data = JSON.parse(data);
				    	if(data.error == 0){
                            var codigo = data.codigo;                     
                                swal({
                    	            title: 'Se envio corecctamente la Cotizacion',
                    	            text: codigo,
                    	            type: 'success',
                    	            showCancelButton: false,                    	            
                    	            allowOutsideClick: false
                    	        }).then(function(){
                    	        	window.location.href = "cotclus";
                    	        });
						}else if(data.error == 1){
							mostrarNotificacion('error','Error','No se inserto la cotizacion:'+data.msj);
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
        </script> 
        
       
        
    </body>


</html>