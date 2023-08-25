<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">


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
<script src="<?php echo base_url(); ?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js?v=<?php echo time(); ?>"></script>

</head>
<body data-ma-theme="entel">
<main class="main">
    <section class="content content--full">
        <div class="content__inner">
        
                <div class="card">
                        <div class="card-block">
                                <div id="contTabla" class="table-responsive"  style="width:100%">
                                    <?php echo $tabla; ?>
                                </div>
                        </div>

                </div>
    </div>



<div class="modal fade" aria-labelledby="myLargeModalLabel" role="dialog" id="modalEntProv" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">LISTA DE ENTIDADES </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div>
                <a style="cursor:pointer; color: var(--verde_telefonica)" onclick="abrirModalRegisEnt()"><i class="zmdi zmdi-hc-2x zmdi-plus-circle">ENTIDADES</i></a>
            </div>
            <div id="content" class="modal-body">
                <form id="formEntCheqProv" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div id="cont_ent_prov" class="form-group form-group--float col-sm-12 table-responsive">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- MODAL DE REGISTRO DE LICENCIAS IP_EST_ENT_DET-->
<button type="button" class="btn btn-primary" id="btnclick" data-toggle="modal" data-target="#modalRegistrarEntidades" style="display: none;"></button>
<div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalRegistrarEntidades" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                
            </div>
            <div style="padding-left:60px">
                <a id="aAgregarMasEntidades" style="cursor:pointer; color: var(--verde_telefonica)" onclick="abrirModalRegisEnt()"><i class="zmdi zmdi-hc-2x zmdi-plus-circle">ENTIDADES</i></a>
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
<button type="button" class="btn btn-primary" id="btnmodalSubirEvidencia" data-toggle="modal" data-target="#modalSubirEvidencia" style="display: none;"></button>
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


<!-- MODAL DONDE REGISTRAMOS LA ENTIDADES-->
<button type="button" class="btn btn-primary" id="btnmodalRegistrarEnt" data-toggle="modal" data-target="#modalRegistrarEnt" style="display: none;"></button>
<div class="modal fade" id="modalRegistrarEnt"  tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title">A&Ntilde;ADIR ENTIDADES</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="font-weight: bold;color: black;">Asignar Entidades</div>
                            <div class="panel-body">
                                <div class="col-sm-12 col-md-12 form-inline" id="formEntidades">
                                    <div class="row">
                                        <div class="col-sm-12 form-group" id="contEntidades">
                                            <label for="idCmbEnt">Entidades: </label>
                                            <select id="idCmbEnt" name="idCmbEnt" class="select2 form-control">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><br>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnsaveEnt" type="button" class="btn btn-success" onclick="registrarEntidades()">ACEPTAR</button>
                <button id="btnClose" type="button" class="btn btn-success" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL DONDE REGISTRAMOS LA ENTIDADES-->

<!-- MODAL DONDE REGISTRAMOS COMPROBANTES-->
<button type="button" class="btn btn-primary" id="btnmodalComprobantes" data-toggle="modal" data-target="#modalComprobantes" style="display: none;"></button>
<div class="modal fade bs-example-modal-lg in" aria-labelledby="myLargeModalLabel" role="dialog" id="modalComprobantes" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">COMPROBANTE</h4>
            </div>
            <div id="content" class="modal-body">
                <form id="formRegistrarComprobante" method="post" style="padding-right: inherit;padding-left: inherit;padding-bottom: inherit;">
                    <div class="row">
                        <div id="contTablaCompro" class="form-group form-group--float col-sm-12 table-responsive">
                           
                        </div>
                    </div>
                    <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                        </div>
                    </div>
                </form>
            </div>
        
    </div>
</div>
</div>
<!-- MODAL DONDE REGISTRAMOS COMPROBANTES-->
<button type="button" class="btn btn-primary" id="btnmodalSubirFotoComprobante" data-toggle="modal" data-target="#modalSubirFotoComprobante" style="display: none;"></button>
<div class="modal fade" id="modalSubirFotoComprobante" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="tituloModalComproEvi" style="font-weight: bold;">EL NOMBRE DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
        </div>
        <div class="modal-body">
        <div class="col-6">
            <div id="dzDetalleComprobante" class="dropzone" >

            </div>
            <hr style="border:1;">

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="btnAceptarSubirFotoComprobante" class="btn btn-success" onclick="cerrarModalEviCompro()">Aceptar</button>
            <button id="btnClose" type="button" class="btn btn-secondary" onclick="cerrarModalEviCompro()">Close</button>
        </div>
        </div>
    </div>
</div>
</section>
</main>
</body>
</html>