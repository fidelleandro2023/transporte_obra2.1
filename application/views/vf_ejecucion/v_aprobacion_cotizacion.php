<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">

<style>
    .fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}
    
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    input[type=number] { -moz-appearance:textfield; }
</style>


<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row heading-bg">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h5 class="txt-dark">Aprobaci&oacute;n de cotizaciones</h5>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="#" class="active"><span>Aprobaci&oacute;n de cotizaciones</span></a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default card-view">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Filtros    <!-- <button type="button" id="btnAbrirModalRegEnt" class="btn btn-success"  onclick="abrirModalRegiCoti()"><i class="fa fa-plus-square" aria-hidden="true"> Cotizaci&oacute;n</i></button--></h6>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="table-wrap">
                                <div id="contTablaCotizaciones" class="table-responsive">
                                    <?php echo $tabla ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalAlertValidacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
        <div class="modal-header" style="background:red">
            <h5 class="modal-title" style="color:white" id="tituloModAprob">&#191;Est&aacute; seguro de realizar esta operaci&oacute;n?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <a id="txtAprobDesaprob"></a>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" id="btnAprobDesaprob">Aceptar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="modalDesaprobCoti" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
        <div class="modal-header" style="background:red">
            <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta operaci&oacute;n?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <a>Al aceptar, se rechazar&aacute; la cotizaci&oacute;n.</a>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-success" onclick="desaprobCotizacion()">Aceptar</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
</div> -->



<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url();?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url();?>public/dist/js/init.js"></script>


<!--  tables -->
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.flash.min.js"></script>


<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script> 

<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/export-table-data.js"></script>



<script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url(); ?>public/js/js_cotizaciones/js_cotizaciones.js"></script>
<script type="text/javascript">

    var idCotizacionGlob = null;
    var itemplanGlob = null;
    var flgAprobDesaprobGlob = null;

    function abrirModalAprobCoti(component,flgAprob){
        idCotizacionGlob = $(component).data("idcotizacion");
        itemplanGlob = $(component).data("itemplan");
        $("#btnAprobDesaprob").prop("onclick", null).off("click");
        $("#btnAprobDesaprob").on("click",aprobDesaprobCotizacion);
        if(flgAprob == 1){
            $("#txtAprobDesaprob").text("Al aceptar, se aprobara la cotizacion.");
            flgAprobDesaprobGlob = 1;
        }else if (flgAprob == 2){
            $("#txtAprobDesaprob").text("Al aceptar, se rechazara la cotizacion.");
            flgAprobDesaprobGlob = 2;
        }
        modal('modalAlertValidacion');
    }

    function aprobDesaprobCotizacion(){
        if(idCotizacionGlob != null && itemplanGlob != null){
            $.ajax({
            type: 'POST',
            url: 'aprobCoti',
            data: {
                idCotizacion: idCotizacionGlob,
                itemplan: itemplanGlob,
                flgAprob: flgAprobDesaprobGlob
            }
            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contTablaCotizaciones').html(data.tablaHTML);
                    paginarTabla('simpletable');
                    modal('modalAlertValidacion');
                    mostrarNotificacion('success', 'Success', data.msj);
                } else {
                    mostrarNotificacion('error', 'Error', data.msj);
                }
            });
        }
    }

    function desaprobarCotizacion(){
        console.log('entro desarpobar cotizacion');
    }

</script>