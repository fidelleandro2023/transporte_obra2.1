<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=windows-1252">
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
	
	<section class="content--full">

			<div class="card">
				<div class="card-block">
					<div class="row">
					    <form id="formAdjudicaItem" method="post" class="form-horizontal">
                           <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>SUB PROYECTO</label>
                                        <select id="selectSubAdju" name="selectSubAdju" class="select2 form-control" disabled="disabled">
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
                                        <select id="selectCentral" name="selectCentral" class="select2 form-control" disabled="disabled">
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
                                    	<select id="selectEECCDiseno" name="selectEECCDiseno" class="select2 form-control" disabled="disabled">
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
                                    <button  type="submit" class="btn btn-primary" id="btnAdjudica">Aceptar</button>
                                </div>
                            </div> 
                            
                            </div>
                        </form>
					</div>
				</div>
			</div>
	</section>
	</main>


	<div class="modal fade" id="guardarAdjudicacion" tabindex="-1"
		role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Ã‚Â¿Desea Adjudicar?</h5>
					<button type="button" class="close" data-dismiss="modal"
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" id="btnAdjudica" onclick="adjudicar()"
						class="btn btn-primary">Adjudicar</button>
					<button type="button" class="btn btn-secondary"
						data-dismiss="modal">Close</button>
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
	console.log("DOSCCCC");
        var idEstacionGlobal = null;
        $(document).ready(function(){
			console.log("ENTRO VER");
        	var itemplan       = <?php echo '"'.$itemplan.'"'?>;
            var has_coax = <?php echo $has_coax;?>;
            var has_fo = <?php echo $has_fo;?>;

            console.log("itemplan " + itemplan);
            console.log("has_coax " + has_coax);
            console.log("has_fo   " + has_fo);
            
            var id = itemplan+""+idEstacionGlobal;
                
            $('#'+id).css('background-color', 'yellow');

            if(itemPlanAnterior!=null && itemPlanAnterior!=id) {
                $('#'+itemPlanAnterior).css('background-color', 'white');  
            } 
            itemPlanAnterior = id;
            $.ajax({
                type    : 'POST',
                url     : "pqt_getInfItem",
                data	:	{ item         : itemplan,
        			        	has_coax : has_coax,
        			        	has_fo   : has_fo },
                'async'	:	false
              })
              .done(function(data) {  
                    data = JSON.parse(data);
                    if(data.error == 0){
						console.log("has_fo: "+has_fo+" has_coax: "+has_coax);
                        $('#selectSubAdju').val(data.subpro).trigger('change');
                        $('#selectEECCDiseno').val(data.empresacolab).trigger('change');
                        $('#selectCentral').val(data.central).trigger('change');
                        $('#selectUno').prop('checked', true);
                        if(has_coax == 0){
                        	$('#divCoaxial').hide();
                        	$('#formAdjudicaItem').data('bootstrapValidator').enableFieldValidators('idFechaPreAtencionCoax', false);    	
                        }else if(has_coax == 1){
                        	$('#divCoaxial').show();
                        	$('#formAdjudicaItem').data('bootstrapValidator').enableFieldValidators('idFechaPreAtencionCoax', true);    		
                        }
                        if(has_fo == 0){
                        	$('#divFO').hide();
                        	$('#formAdjudicaItem').data('bootstrapValidator').enableFieldValidators('idFechaPreAtencionFo', false);    	

                        }else if(has_fo == 1){
                        	$('#divFO').show();
                        	$('#formAdjudicaItem').data('bootstrapValidator').enableFieldValidators('idFechaPreAtencionFo', true);    	

                        }
                    }else if(data.error == 1){
                        console.log(data.error);
                    }
                })
            
        });

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
           
            var itemplan = <?php echo '"'.$itemplan.'"'?>;
            formData.append('itemplan', itemplan);
            formData.append('idEstacion', idEstacionGlobal);
            formData.append('idFechaPreAtencionCoax', idFechaPreAtencionCoax);
            formData.append('idFechaPreAtencionFo', idFechaPreAtencionFo);
            
            var selectSubAdju = $.trim($('#selectSubAdju').val());
            var selectCentral = $.trim($('#selectCentral').val());
            var selectEECCDiseno = $.trim($('#selectEECCDiseno').val());            
            formData.append('selectSubAdju', selectSubAdju);
            formData.append('selectCentral', selectCentral);
            formData.append('selectEECCDiseno', selectEECCDiseno);
            
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
                url: "pqt_adjuItem",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
                .done(function(data) {  
                    data = JSON.parse(data);
                    if(data.error == 0){
                        swal({
            	            title: 'Ejecucion exitosa',
            	            text: 'Se registr&oacute; correcamente',
            	            type: 'success',
            	            showCancelButton: false,                    	            
            	            allowOutsideClick: false
            	        }).then(function(){
            	        	window.top.close();parent.location.reload();
            	        });
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