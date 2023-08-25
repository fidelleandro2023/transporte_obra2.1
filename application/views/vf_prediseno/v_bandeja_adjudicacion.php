<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
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
    <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>public/plugins/bTable/bootstrap-table.min.css?v=<?php echo time();?>" >	
        
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃ¯Â¿Â½"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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

            <aside class="chat">
                <div class="chat__header">
                    <h2 class="chat__title">Chat <small>Currently 20 contacts online</small></h2>

                    <div class="chat__search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </div>

                <div class="listview listview--hover chat__buddies scrollbar-inner">
                    <a class="listview__item chat__available">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/7.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>hey, how are you doing.</p>
                        </div>
                    </a>

                    <a class="listview__item chat__available">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/5.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>hmm...</p>
                        </div>
                    </a>

                    <a class="listview__item chat__away">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/3.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>all good</p>
                        </div>
                    </a>

                    <a class="listview__item">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/8.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>morbi leo risus portaac consectetur vestibulum at eros.</p>
                        </div>
                    </a>

                    <a class="listview__item">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/6.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>fusce dapibus</p>
                        </div>
                    </a>

                    <a class="listview__item chat__busy">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/9.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>cras mattis consectetur purus sit amet fermentum.</p>
                        </div>
                    </a>
                </div>

                <a href="messages.html" class="btn btn--action btn--fixed btn-danger"><i class="zmdi zmdi-plus"></i></a>
            </aside>

            <section class="content content--full">
           
		                   <div class="content__inner">
                                    <h2>BANDEJA DE ADJUDICACION</h2>
		   				                    <div class="card">
		   				                        
		   				                        <div class="card-block">	   				                         
                                                    <div class="row">

                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>PROYECTO</label>
                                            <?php echo !isset($cmbProyecto) ? null : $cmbProyecto;?>                                                                                      
                                        </div>
                                    </div>                                  
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>SUB PROYECTO</label>
                                            <select id="cmbSubProy" class="select2" onchange="filtrarTabla()" multiple>
                                                <option value="">Seleccionar<option>  
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
                                        <label>JEFATURA</label>
                                        <?php echo !isset($cmbJefatura) ? null : $cmbJefatura;?>               
                                    </div>
                                </div>
                                <!-- 
                                 <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>ITEMPLAN</label>

                                        <select id="selectItemPlan" name="selectItemPlan" class="select2" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                             
                                        </select>
                                    </div>
                                </div>
                                                          
                                <div class="col-sm-6 col-md-4">                               
                                    <div class="form-group">
                                        <label>ESTACI&Oacute;N</label>
                                      <?php  // echo !isset($cmbEstacion) ? null : $cmbEstacion?>                      
                                    </div>
                                </div>
                                 <div class="col-sm-6 col-md-4">                               
                                    <div class="form-group">
                                        <label>TIPO DE PLANTA</label>
                                        <?php //echo !isset($cmbPlanta) ? null : $cmbPlanta?>                      
                                    </div>
                                </div> -->
                                <!-- 
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>EXPEDIENTE</label>

                                        <select id="selectExpediente" name="selectExpediente" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                       <option value="SI">SI</option>
                                       <option value="NO">NO</option>
                                    
                                        </select>
                                    </div>
                                </div>
                                -->
                            </div>
                                <div id="contTabla" style="display:none" class="table-responsive">
                                    <?php echo !isset($tablaAsigGrafo) ? null: $tablaAsigGrafo;?>                        
                                </div>
                            </div>
                        </div>
		   				                    
		   				                    
        <div class="modal fade"id="modalEjec"  tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                         <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                   <div class="modal-body">
                    <form id="formAdjudicaItem" method="post" class="form-horizontal">                       
                       
                           <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>
                                        <select id="selectSubAdju" name="selectSubAdju" class="select2 form-control">
                                            <option>&nbsp;</option>
                                            <?php                                                    
                                                foreach($listaSubProy->result() as $row){                      
                                            ?> 
                                            <option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">MDF</label>
                                        <select id="selectCentral" name="selectCentral" class="select2 form-control">
                                            <option>&nbsp;</option>
                                            <?php foreach ($listacentral->result() as $row) { ?>
                                                <option value="<?php echo $row->idCentral ?>"><?php echo utf8_decode($row->tipoCentralDesc .' - '.$row->codigo) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div> 
                        		<div class="col-sm-4 col-md-4">
                        			<div class="form-group">
                                    	<label class="control-label">EECC DISE&Ntilde;O</label>
                                    	<select id="selectEECCDiseno" name="selectEECCDiseno" class="select2 form-control">
                                        	<option>&nbsp;</option>                                       
                                            <?php                                                    
                                                foreach($listEECCDi->result() as $row){                      
                                            ?> 
                                            	<option value="<?php echo $row->idEmpresaColab ?>"><?php echo utf8_decode($row->empresaColabDesc) ?></option>
                                            <?php }?>      
                                    	</select>                              
                            		</div>
                            	</div>
                            	
                            	<div class="col-sm-12 col-md-12" id="divCoaxial">
                            			<label style="font-weight: bold;color: black;">COAXIAL</label>
                                        <div class="form-group col-12">
                                          <label>FECHA PREV. DE ATENCION COAXIAL</label>
                                                <input placeholder="::SELECCIONE FECHA::" id="idFechaPreAtencionCoax" name="idFechaPreAtencionCoax" type="text" class="form-control form-control-sm date-picker">
                                               
                                                <i class="form-group__bar"></i>
                                            </div>   
                               
                                		<div class="col-12">
                                            <div id="dropzone4" class="dropzone" >
                                                    
                                            </div>
                                        	<hr style="border:1;">
                                        </div>
                                </div>
                        		<hr style="border:2;">
                        		<div class="col-sm-12 col-md-12" id="divFO">
                        				<label style="font-weight: bold;color: black;">FO</label>                                     
                                        
                                         <div class="form-group col-12">
                                          <label>FECHA PREV. DE ATENCION FO</label>
                                                <input placeholder="::SELECCIONE FECHA::" id="idFechaPreAtencionFo" name="idFechaPreAtencionFo" type="text" class="form-control form-control-sm date-picker">
                                               
                                                <i class="form-group__bar"></i>
                                            </div>   
                               
                                		<div class="col-12">
                                            <div id="dropzone5" class="dropzone" >
                                                    
                                            </div>
                                        	<hr style="border:1;">
                                        </div>
                                </div>
                        
                        
                            <br><br>
                           
                            <div class="col-sm-12 col-md-12" id="mensajeForm"></div>  
                            
                            <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button  type="submit" class="btn btn-primary" id="btnAdjudica">Aceptar</button>
                                </div>
                            </div> 
                            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>			                    
		</div>

		   				             
            </section>
        </main>


        <div class="modal fade" id="guardarAdjudicacion" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ã‚Â¿Desea Adjudicar?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAdjudica" onclick="adjudicar()"  class="btn btn-primary">Adjudicar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        <script src="<?php echo base_url();?>public/plugins/bTable/bootstrap-table.min.js?v=<?php echo time();?>"></script>        
   		<script src="<?php echo base_url();?>public/plugins/bTable/bootstrap-table-es-MX.js?v=<?php echo time();?>"></script>                                       

		<script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
                
        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script>        
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        
        
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>  
        <script src="<?php echo base_url();?>public/js/jsBandejaAdjudicacion.js?v=<?php echo time();?>"></script>  
        
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
        <script type="text/javascript">
        var idEstacionGlobal = null;
        $(document).ready(function(){
            $('#contTabla').css('display', 'block');
        });                                            
                                   
                           /*                         
        function adjudicar() {
            var radioCheck = $('input:radio[name=radioSelecFoCo]:checked').val();
        }
*/

        $('#formAdjudicaItem')
    	.bootstrapValidator({
    	    container: '#mensajeForm',
    	    feedbackIcons: {
    	        valid: 'glyphicon glyphicon-ok',
    	        invalid: 'glyphicon glyphicon-remove',
    	        validating: 'glyphicon glyphicon-refresh'
    	    },
    	    excluded: ':disabled',
    	    fields: {
    	    	selectSubAdju: {
    	            validators: {
    	                notEmpty: {
    	                    message: '<p style="color:red">(*) Debe seleccionar un Subproyecto.</p>'
    	                }
    	             }
     	    	   },
      	    	  selectCentral: {
        	            validators: {
        	                notEmpty: {
        	                    message: '<p style="color:red">(*) Debe Seleccionar MDF</p>'
        	                }
        	             }
         	    	   },
                    selectEECCDiseno: {
                    validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una EECC Dise&ntilde;o.</p>'
                            }
                        }
                    },
                    idFechaPreAtencionCoax: {
                    validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar una fecha Coaxial.</p>'
                            }
                        }
                    }  ,
                    idFechaPreAtencionFo: {
                        validators: {
                                notEmpty: {
                                    message: '<p style="color:red">(*) Debe seleccionar una fecha FO.</p>'
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
            //var radioCheck = $('input:radio[name=radioSelecFoCo]:checked').val();
            
            $.each(params, function(i, val) {
                formData.append(val.name, val.value);
            });
            
            var idFechaPreAtencionCoax = $('#idFechaPreAtencionCoax').val();
            var idFechaPreAtencionFo = $('#idFechaPreAtencionFo').val();
           
            var itemplan = $('#btnAdjudica').attr('data-item');
            formData.append('itemplan', itemplan);
            formData.append('idEstacion', idEstacionGlobal);
            formData.append('idFechaPreAtencionCoax', idFechaPreAtencionCoax);
            formData.append('idFechaPreAtencionFo', idFechaPreAtencionFo);
            
            var subProy    = $.trim($('#cmbSubProy').val()); 
            var eecc       = $.trim($('#selectEECC').val()); 
            var zonal      = $.trim($('#selectZonal').val()); 
            var itemplanFil   = $.trim($('#selectItemPlan').val()); 
            var mes        = $.trim($('#selectMesEjec').val());           
            var expediente = $.trim($('#selectExpediente').val());
            var idEstacion = $.trim($('#idEstacion').val());
            var idTipoPlan = $.trim($('#idTipoPlanta').val());
            var jefatura   = $.trim($('#cmbJefatura').val());
            var idProyecto = $.trim($('#cmbProyecto').val());

            formData.append('subProy', subProy);
            formData.append('eecc', eecc);
            formData.append('zonal', zonal);
            formData.append('itemplanFil', itemplanFil);
            formData.append('mes', mes);
            formData.append('expediente', expediente);
            formData.append('idEstacion', idEstacion);
            formData.append('idTipoPlan', idTipoPlan);
            formData.append('jefatura', jefatura);
            formData.append('idProyecto', idProyecto);
            
            $.ajax({
                data: formData,
                url: "adjuItem",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
                .done(function(data) {  
                    data = JSON.parse(data);
                    if(data.error == 0){
                        $('#contTabla').html(data.tablaAsigGrafo);			    					
                        initDataTable('#data-table');
                        modal('modalEjec');  
                        mostrarNotificacion('success','Operaci&oacute;n exitosa.', 'Se registr&oacute; correcamente!');
                    }else if(data.error == 1){
                        console.log(data.error);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error','Error','Comun&iacute;quese con alguna persona a cargo :(');
                })
                .always(function() {
                    
            });	 
    	});       
        </script>
    </body>
</html>