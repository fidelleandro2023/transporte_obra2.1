<script src="<?php echo base_url();?>public/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/jszip/dist/jszip.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/pdfmake/build/pdfmake.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/pdfmake/build/vfs_fonts.js"></script>
  
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="<?php echo base_url();?>public/dist/js/export-table-data.js"></script>
<?php
if($pagina!="toropep"){
?>
<script type="text/javascript">
$("#simpletable").removeAttr('width').DataTable({dom: 'Bfrtip',buttons:[{extend:'excelHtml5'}],pageLength:10,lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],language:{sProcessing:"Procesando...",sLengthMenu:"Mostrar _MENU_ registros",sZeroRecords:"No se encontraron resultados",sEmptyTable:"Ning\u00fan dato disponible en esta tabla",sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",sInfoFiltered:"(filtrado de un total de _MAX_ registros)",sInfoPostFix:"",
sSearch:"Buscar:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Cargando...",oPaginate:{sFirst:"Primero",sLast:"\u00daltimo",sNext:"Siguiente",sPrevious:"Anterior"},oAria:{sSortAscending:": Activar para ordenar la columna de manera ascendente",sSortDescending:": Activar para ordenar la columna de manera descendente"}}}).$("a[data-toggle=popover]").popover({html: true,
    content: function(){
return "<a href='obra_devolver.php?id="+$(this).parent().parent().find("td").eq(1).html().trim()+"' class='devolver'>- Devolver</a><br><a href='obra_cancelar?id="+$(this).parent().parent().find("td").eq(1).html().trim()+"' class='cancelar'>- Cancelar</a>";}}).click(function(e) {e.preventDefault();}).$("a[data-toggle=tooltip]").tooltip().hover(function(e) {e.preventDefault();});
</script>  
<?php }
if($pagina=="toropep"){
?>
<script type="text/javascript">
$("#simpletable").DataTable({dom: 'Bfrtip',buttons:[{extend:'excelHtml5'}],pageLength:10,lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],language:{sProcessing:"Procesando...",sLengthMenu:"Mostrar _MENU_ registros",sZeroRecords:"No se encontraron resultados",sEmptyTable:"Ning\u00fan dato disponible en esta tabla",sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",sInfoFiltered:"(filtrado de un total de _MAX_ registros)",sInfoPostFix:"",
sSearch:"Buscar:",sUrl:"",sInfoThousands:",",sLoadingRecords:"Cargando...",oPaginate:{sFirst:"Primero",sLast:"\u00daltimo",sNext:"Siguiente",sPrevious:"Anterior"},oAria:{sSortAscending:": Activar para ordenar la columna de manera ascendente",sSortDescending:": Activar para ordenar la columna de manera descendente"}}}).$("a[data-toggle=popover]").popover({html: true,
    content: function(){
return "<a href='obra_devolver.php?id="+$(this).parent().parent().find("td").eq(1).html().trim()+"' class='devolver'>- Devolver</a><br><a href='obra_cancelar?id="+$(this).parent().parent().find("td").eq(1).html().trim()+"' class='cancelar'>- Cancelar</a>";}}).click(function(e) {e.preventDefault();}).$("a[data-toggle=tooltip]").tooltip().hover(function(e) {e.preventDefault();});
</script>  
<?php }
?>