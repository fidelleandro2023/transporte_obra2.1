<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url();?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url();?>public/dist/js/init.js"></script>
<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script> 





<script src="<?php echo base_url();?>public/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/pdfmake/build/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/export-table-data.js"></script>

<script type="text/javascript">

    function initTable(table_name, num){
    	  $("#"+table_name).DataTable({drawCallback: function() {	
    	    // $('.select2').select2();
    	  },
    	  dom: 'Bfrtip',
    	  buttons:[{extend:'excelHtml5'}],
    	  pageLength:num,
    	  lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],
    	  language:{sProcessing:"Procesando...",
    	  sLengthMenu:"Mostrar _MENU_ registros",
    	  sZeroRecords:"No se encontraron resultados",
    	  sEmptyTable:"Ning\u00fan dato disponible en esta tabla",
    	  sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    	  sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",
    	  sInfoFiltered:"(filtrado de un total de _MAX_ registros)",
    	  sInfoPostFix:"",
    	  sSearch:"Buscar:",
    	  sUrl:"",
    	  sInfoThousands:",",
    	  sLoadingRecords:"Cargando...",
    	  oPaginate:{sFirst:"Primero",sLast:"\u00daltimo",sNext:"Siguiente",sPrevious:"Anterior"},
    	  oAria:{sSortAscending:": Activar para ordenar la columna de manera ascendente",
    	  sSortDescending:": Activar para ordenar la columna de manera descendente"}}})
    }

</script> 


<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
<script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time(); ?>"></script>


<script src="<?php echo base_url();?>public/js/jasny-bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
<script src="<?php echo base_url();?>public/js/js_licencias/js_bandeja_licencias.js?v=<?php echo time(); ?>"></script>