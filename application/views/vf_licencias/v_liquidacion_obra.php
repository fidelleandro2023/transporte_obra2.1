<link rel="stylesheet" href="<?php echo base_url(); ?>public/css/galeria_fotos.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css?v=<?php echo time(); ?>">


<style>
    fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    input[type=number] { -moz-appearance:textfield; }

    .modal-dialog {
        position: relative;
        width: auto;
        max-width: 600px;
        margin: 10px;
    }
    .modal-sm {
        max-width: 300px;
    }
    .modal-lg {
        max-width: 90%;
    }
    @media (min-width: 768px) {
        .modal-dialog {
            margin: 30px auto;
        }
    }
    @media (min-width: 320px) {
        .modal-sm {
            margin-right: auto;
            margin-left: auto;
        }
    }
    @media (min-width: 620px) {
        .modal-dialog {
            margin-right: auto;
            margin-left: auto;
        }
        .modal-lg {
            margin-right: 10px;
            margin-left: 10px;
        }
    }
    @media (min-width: 920px) {
        .modal-lg {
            margin-right: auto;
            margin-left: auto;
        }
    }
</style>


<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row heading-bg">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <ol class="breadcrumb">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="#" class=""><span>Finalizacion de Obra</span></a></li>
                <li><a href="#" class="active"><span><?php echo $pagina;?>s</span></a></li>
                </ol>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <h2>Finalizacion de Obra</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default card-view">
                    <form method="post" action="liquidacion_obra">
                    <input type="hidden" name="pagina" value="pendienteFiltro">

                        <!-- filtros-->
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mb-10">ItemPlan</label>
                                <input type="text" id="itemplan" name="itemplan" class="form-control" placeholder="ItemPlan" maxlength="13" value="<?php if(@$_POST["itemplan"]){ echo $_POST["itemplan"];}?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mb-10">Proyecto</label>
                                <select class="form-control select2" name="proyecto" id="proyecto"  onchange="getSubProyecto()">
                                    <!-- <option value="0" selected>Seleccionar Proyecto</option> -->
                                    <?php echo $proyecto; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mb-10">SubProyecto</label>
                                    <select class="form-control select2" name="subProyecto" id="subProyecto">
                                        <option value="0">Seleccionar SubProyecto</option>

                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mb-10">Jefatura</label>
                                    <select class="form-control select2" name="jefatura" id="jefatura">
                                        <?php echo $jefatura; ?>
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mb-10">Mes Prev. Ejec.</label>
                                    <select class="form-control select2" name="mesPrevEjec" id="mesPrevEjec">
                                        <option value="0" selected>Seleccionar Mes</option>
                                        <option value="1">ENERO</option>
                                        <option value="2">FEBREBRO</option>
                                        <option value="3">MARZO</option>
                                        <option value="4">ABRIL</option>
                                        <option value="5">MAYO</option>
                                        <option value="6">JUNIO</option>
                                        <option value="7">JULIO</option>
                                        <option value="8">AGOSTO</option>
                                        <option value="9">SETIEMPBRE</option>
                                        <option value="10">OCTUBRE</option>
                                        <option value="11">NOVIEMBRE</option>
                                        <option value="12">DICIEMBRE</option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label mb-10">Fase</label>
                                    <select class="form-control select2" name="fase" id="fase">
                                        <?php echo $fase; ?>
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3" style="margin-top: 25px;">
                            <button type="submit" class="btn btn-success mr-10">Filtrar</button>
                        </div>
                        <!-- filtros-->

                    </form>


                    <!-- div q contiene la tabla-->
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="table-wrap">
                                <div id="contTabla" class="table-responsive"  style="width:100%">
                                    <?php echo $tabla; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- div q contiene la tabla-->

                </div>
            </div>
        </div>
    </div>
</div>




<!-- MODAL DE REGISTRO DE LICENCIAS IP_EST_ENT_DET-->
<div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalRegistrarEntidades" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div id="content" class="modal-body">
                <form id="formRegistrarEntidad" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div id="contTablaEnt" class="form-group form-group--float col-sm-12 table-responsive">
                           
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- MODAL DE REGISTRO DE LICENCIAS IP_EST_ENT_DET-->


<!-- MODAL DONDE SUBE LA EVIDENCIA PARA EL IP_EST_ENTIDAD -->
<div class="modal fade" id="modalSubirEvidencia" tabindex="1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="tituloModal" style="font-weight: bold;">EL NOMBRE DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
        </div>
        <div class="modal-body">
        <div class="col-6">
            <div id="dzDetalleItem" class="dropzone" >

            </div>
            <hr style="border:1;">

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="btnAceptarSubirEvidencia" class="btn btn-success" data-dismiss="modal">Aceptar</button>
            <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>
<!-- MODAL DONDE SE SUBE LA EVIDENCIA PARA EL IP_EST_ENTIDAD-->


<div class="modal fade" id="modalAlertaValidacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
        <div class="modal-header" style="background:red">
            <h5 class="modal-title" style="color:white">&#191;Est&aacute; seguro de realizar esta acci&oacute;n?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <a>Al aceptar, se validar&aacute; y se dar&aacute; por liquidada la licencia.</a>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" onclick="liquidarLicencia()">Aceptar</button>
        </div>
        </div>
    </div>
</div>








<script src="<?php echo base_url(); ?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#contTabla').css('display', 'block');
    });
</script>