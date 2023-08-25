<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=windows-1252">

</head>

<body data-ma-theme="entel">
	<main class="main">
	
	<section class="content--full">

			<h2>Pre Liquidacion - Formulario Obras Publicas</h2>
			<div id="modalFormObrasPub" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content" >
      <div class="modal-body">
        <div class="container-fluid">
          <div class="form-group col-sm-6">
            <label>Itemplan</label>
            <input id="inputItemplan" type="text" class="form-control" v-model="jsonFormObrasP.itemplan" disabled/>
          </div>
          <div class="form-group">
            <label>Observaciones</label>
            <textarea class="form-control" v-model="jsonFormObrasP.observacion"></textarea>
          </div>
        </div>
        
        <div class="panel-group">
          <div class="panel panel-success">
            <!-- <div class="panel-heading"></div> -->
            <div class="panel-body">
              <div class="table-responsive panel">
                <table class="table">
                  <thead>
                    <th>Canalizaci&oacute;n KM</th>
                    <th>C&aacute;maras Und</th>
                    <th>C(postes)</th>
                    <th>Ma(postes)</th>
                    <th>Km-Ducto</th>
                    <th>Km Tritubo</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.canalizacion_km"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.camaras_und" style="width:120%"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.c_postes"  style="width:120%"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.ma_postes" style="width:120%"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_ducto" style="width:120%"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_tritubo" style="width:120%"/>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>  
            </div>
          </div>
        </div>

        <div class="panel-group">
          <div class="panel panel-success">
            <!-- <div class="panel-heading"></div> -->
            <div class="panel-body">
              <div class="table-responsive panel">
                <table class="table">
                  <thead>
                    <th>KM-Par Cobre</th>
                    <th>KM-Cable Coax</th>
                    <th>KM-FO FO</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_par_cobre"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_cable_coax"/>
                      </td>
                      <td>
                        <input type="text" class="form-control" v-model="jsonFormObrasP.km_fo"/>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>  
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <input type="button" class="btn btn-success boton-acepto" value="Aceptar" @click="registrarFormObraPub">        
      </div>
    </div>
  </div>
</div>
	</section>
	</main>
	
<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url();?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/init.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script> 
<script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>

	<script type="text/javascript">
    	$(function() {
    		var itemplan   = <?php echo '"'.$itemPlan.'"' ?>;
    		var idEstacion = <?php echo '"'.$idEstacion.'"' ?>;
    		formObraP(null, itemplan, 2, null, null, idEstacion);
    	});

    	function formObraP(jefatura, itemPlan, flg_from, indicador, descEmpresaColab, idEstacion) {
    		var app = new Vue({
    		    el: '#modalFormObrasPub',
    		    data: {
    		      jsonFormObrasP : {
    		        idEstacion       : idEstacion, 
    		        itemplan         : itemPlan, 
    		        from             : flg_from,
    		        ptr              : null, 
    		        canalizacion_km  : null, 
    		        camaras_und      : null, 
    		        c_postes         : null, 
    		        ma_postes        : null,
    		        km_ducto         : null,
    		        km_tritubo       : null,
    		        km_par_cobre     : null,
    		        km_cable_coax    : null,
    		        km_fo            : null,
    		        observacion      : null,
    		        fecha_form       : null,
    		        usuario_registro : null,
    		        fecha_registro   : null 
    		      },
    		    },

    		    methods:{
    		        registrarFormObraPub:function() {
    		            vue = this;
    		            $.ajax({
    		                type : 'POST',
    		                url  : 'pqt_registrarFormObraPub',
    		                data : { jsonFormObrasP : vue.jsonFormObrasP }
    		            }).done(function(data){
    		                data = JSON.parse(data);
    		                if(data.error == 0) {
    		                    mostrarNotificacion('success', data.msj);
    		                    window.top.close();
    		                } else {
    		                    mostrarNotificacion('error', data.msj);
    		                }
    		            });
    		        }
    		    }
    		  });
    		}
    </script>
</body>
</html>