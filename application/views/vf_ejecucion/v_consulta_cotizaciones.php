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
                <h5 class="txt-dark">Consulta de cotizaciones</h5>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="#" class="active"><span>Consulta de cotizaciones</span></a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default card-view">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark"></h6>
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