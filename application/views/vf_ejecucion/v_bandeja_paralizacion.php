
<main id="consult">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row heading-lg">
               
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <ol class="breadcrumb">
                    <li><a href="index.php">Inicio</a></li>
                    <!-- <li><a href="#" class=""><span>Registro Ficha Tecnica</span></a></li> -->
                    <li><a href="#" class="active"><span><?php echo $pagina;?></span></a></li>
                    <li><a href="#" class="active"><span>Paralizaciones</span></a></li>
                    </ol>
                </div>
            </div>
            
             <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2 ><?php echo strtoupper($pagina);?></h2>
                </div>
        </div>
        
        <div id="formulario">
            <div class="panel panel-default card-view">
                <div class="panel-body">
                    <div class="table-wrap">
                        <div id="contTablaParalizacion" class="table-responsive" style="width:100%">
                            <?php echo $tablaParalizados ?>
                        </div>
                    </div>
                </div>        
            </div>
        </div>

        <div id="modalAlerta" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header" style="background:red">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="color:white">Alerta!</h4>
                        <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
                    </div>
                    <div class="modal-body">
                        <a>Seleccione una de las opciones.</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success boton-acepto" onclick="aceptarRevertir();">Aceptar</button>
                        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>   
</main>

<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url();?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url();?>public/dist/js/init.js"></script>

<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
  
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?php echo base_url();?>public/js/js_consultas/consultarFormulario.js?v=<?php echo time();?>"></script>  
<script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js?v=<?php echo time();?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/fancy/source/jquery.fancybox.js"></script>

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
    $(document).ready(function(){
        $("#simpletable").DataTable({
            dom: 'Bfrtip',
            buttons:[{extend:'excelHtml5'}],
            pageLength: 10,
            lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],
            language :  {
                            sProcessing:"Procesando...",
                            sLengthMenu:"Mostrar _MENU_ registros",
                            sZeroRecords:"No se encontraron resultados",
                            sEmptyTable:"Ning\u00fan dato disponible en esta tabla",
                            sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                            sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",
                            sInfoFiltered:"(filtrado de un total de _MAX_ registros)",
                            sInfoPostFix:"",
                            sSearch:"Buscar:",
                            sUrl : "",
                            sInfoThousands  : ",",
                            sLoadingRecords : "Cargando...",
                            oPaginate: { sFirst    :"Primero",
                                        sLast     : "\u00daltimo",
                                        sNext     : "Siguiente",
                                        sPrevious : "Anterior"},
                                        oAria     : {
                                                        sSortAscending:": Activar para ordenar la columna de manera ascendente",
                                                        sSortDescending:": Activar para ordenar la columna de manera descendente"
                                                    }
                        }
        });
    });
    var itemplanGlobal     = null;
    function openModalAlert(btn) {
        itemplanGlobal     = btn.data('itemplan');
        modal('modalAlerta');
    }

    function aceptarRevertir() {
        $.ajax({
            type : 'POST',
            url  : 'revertirParalizacion',
            data : { itemplan : itemplanGlobal } 
        }).done(function(data) {
            data = JSON.parse(data);
            if(data.error == 0){ 
                mostrarNotificacion('success', "Se a revertido la paralizaci&oacute;n correctamente", "correcto");
                location.reload();
                modal('modalParalizacion');
            } else {
                mostrarNotificacion('error', data.msj, 'error');
            }
        });
    }
  </script>  