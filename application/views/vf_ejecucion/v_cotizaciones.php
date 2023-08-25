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
                <h5 class="txt-dark">Gesti&oacute;n de cotizaciones</h5>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="#" class="active"><span>Gesti&oacute;n de cotizaciones</span></a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default card-view">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h6 class="panel-title txt-dark">Nueva   <button type="button" id="btnAbrirModalRegEnt" class="btn btn-success"  onclick="abrirModalRegiCoti()"><i class="fa fa-plus-square" aria-hidden="true"> Cotizaci&oacute;n</i></button></h6>
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



<div class="modal fade" role="dialog" id="modalRegisCotizacion" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">REGISTRO DE COTIZACI&Oacute;N </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="content" class="modal-body">
                <form id="formRegistrarEntidad" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <div class="input-group">
                                <input id="itemplan" type="search" class="form-control" placeholder="Ingrese el ItemPlan" maxlength="13" onkeyup="validaBusqueda()">
                                <span class="input-group-btn"><button id="idBtnSearch" type="button" class="btn btn-success" onclick="searchItemPlan()" disabled><i class="fa fa-search" aria-hidden="true"></i></button></span>
                            </div> 
                        </div>
                        <div class="col-sm-6 form-group">
                        <label style="margin: auto;font-weight: bold; color: black">Debe buscar itemplan "en obra".</label>
                        </div>
                        <!-- <div class="col-sm-2 form-group">
                            <button id="idBtnSearch" type="button" class="btn btn-success">Subir</button>
                        </div>
                        <div class="col-sm-2 form-group">
                            <button id="idBtnSearch" type="button" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
                        </div> -->
                        <div class="col-sm-12 tab-container">
                            <div id="contTablaDetItemPlan" class="table-responsive" style="display: none">     
                                
                            </div>
                        </div>
                        <div class="col-sm-12 form-group" id="contDescrip" style="display: none">
                            <label for="idDescripCoti">Descripci&oacute;n:</label>
                            <textarea class="form-control" rows="3" id="idDescripCoti"></textarea>
                        </div>
                        <div class="col-sm-4 form-group" id="contCosto" style="display: none">
                            <label for="comment">Costo:</label>
                            <input type="number" class="form-control" id="idCostoCoti">
                        </div>
                        <div class="col-sm-8 form-group" id="contResponsable" style="display: none">
                            <label for="idResponsable">Responsable: </label>
                            <select id="idResponsable" name="responsable" class="select2 form-control">
                            </select>
                        </div>
                        <div class="col-sm-12" id="contDropzone" style="display: none">
                        <label>SUBIR EVIDENCIA:</label>
                            <div id="dzEviCotizacion" class="dropzone" >

                            </div>
                            <hr style="border:1;">

                        </div>
                        <!-- <div class="col-sm-6 form-group">
                        <label></label>
                        <button type="button" class="btn btn-success" >Buscar</button>
                        </div> -->
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="contGuardar">
                <button type="button" id="btnAceptarSubirEviCotizacion" class="btn btn-success">Guardar</button>
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
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