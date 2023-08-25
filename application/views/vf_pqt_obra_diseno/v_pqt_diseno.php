<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet"
              href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet"
              href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet"
              href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.css"/>
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"/>
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <link rel="stylesheet"
              href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>

        <!-- Demo -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/demo/css/demo.css">

        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css">
        <style type="text/css">

            .select2-dropdown {
                z-index: 100000;
            }

            input[type=checkbox] + label {
                display: block;
                margin: 0.2em;
                cursor: pointer;
                padding: 0.2em;
            }

            input[type=checkbox] {
                display: none;
            }

            input[type=checkbox] + label:before {
                content: "\2714";
                border: 0.1em solid #000;
                border-radius: 0.2em;
                display: inline-block;
                width: 1.4em;
                height: 1.4em;
                padding-left: 0.2em;
                padding-bottom: 0.3em;
                margin-right: 0.2em;
                vertical-align: bottom;
                color: transparent;
                transition: .2s;
            }

            input[type=checkbox] + label:active:before {
                transform: scale(0);
            }

            input[type=checkbox]:checked + label:before {
                background-color: #2eb0ff;
                border-color: #2eb0ff;
                color: #fff;
            }

            input[type=checkbox]:disabled + label:before {
                transform: scale(1);
                border-color: #aaa;
            }

            input[type=checkbox]:checked:disabled + label:before {
                transform: scale(1);
                background-color: #bfb;
                border-color: #bfb;
            }

        </style>
    </head>

    <body data-ma-theme="entel">
        <main class="main">
            <section class="content content--full">

                <div class="content__inner">
                    <div class="card">

                        <div class="card-block">
                            <div id="contTabla" style="display:none" class="table-responsive">
                                <?php echo $tablaDiseno ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- -----------------------------------MODAL EDITAR FECHA Y EVIDENCIAS ---------------------- -->
                <div class="modal fade"id="modalEditEjec"  tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form id="formEditarAdju" method="post" class="form-horizontal">                       

                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <label id="descEsta" style="font-weight: bold;color: black;"></label>
                                            <div class="form-group col-12">
                                                <label>FECHA PREV. DE ATENCION</label>
                                                <input placeholder="::SELECCIONE FECHA::" id="idFechaPreAtencionCoax" name="idFechaPreAtencionCoax" type="text" class="form-control form-control-sm date-picker">

                                                <i class="form-group__bar"></i>
                                            </div>   

                                            <div class="col-12" id="divFiles">
                                                <div id="dropzone6" class="dropzone" >

                                                </div>
                                                <hr style="border:1;">
                                            </div>
                                        </div>
                                        <br><br>

                                        <div class="col-sm-12 col-md-12" id="mensajeForm"></div>  

                                        <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                                <button  type="submit" class="btn btn-primary" id="btnAddEvi">Aceptar</button>
                                            </div>
                                        </div> 

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>	
                <div class="modal fade" id="modalEditEntidadesEjec">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" style="margin: auto;font-weight: bold;">ENTIDADES</h3>    
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <?php if ($idProyecto == ID_PROYECTO_SISEGOS) { ?>
                                    <div class="" id="divFormularioSisego">
                                        <div class="tab-container tab-container--green form-group">
                                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab" href="#tab_princ" role="tab">DATOS PRINCIPALES</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab_met" role="tab">METRADOS</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab_ent" role="tab">ENTIDADES - LICENCIAS</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab_cost" role="tab">COSTOS</a>
                                                </li>                                           
                                            </ul>
                                            <form id="formRegDiseno" method="POST" class="form-horizontal">
                                                <div class="tab-content">
                                                    <div class="tab-pane active fade show" id="tab_princ" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-6">
                                                                <div class="form-group">
                                                                    <label>NODO PRINCIPAL</label>
                                                                    <select id="selectCentral" name="selectCentral" class="select2 form-control">
                                                                        <option value="">&nbsp;</option>
                                                                        <?php
                                                                        foreach ($listaTiCen->result() as $row) {
                                                                            ?> 
                                                                            <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                                        <?php } ?>

                                                                    </select>
                                                                    <div id="mensajeNodoPrincipal"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-md-6">
                                                                <div class="form-group">
                                                                    <label>NODO RESPALDO</label>
                                                                    <select id="selectCentral2" name="selectCentral2" class="select2 form-control">
                                                                        <option value="">&nbsp;</option>
                                                                        <?php
                                                                        foreach ($listaTiCen->result() as $row) {
                                                                            ?> 
                                                                            <option value="<?php echo $row->idCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                                                        <?php } ?>

                                                                    </select>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6 col-md-6">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>FACILIDADES DE RED</label>
                                                                    <input id="inputFacRed" name="inputFacRed" type="text" class="form-control" value="<?php echo isset($facilidades_de_red) ? $facilidades_de_red : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-6 col-md-6">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>CANTIDAD CTO</label>
                                                                    <input id="inputCantCTO" name="inputCantCTO" type="text" class="form-control" value="<?php echo isset($cant_cto) ? $cant_cto : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab_met" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-sm-4 col-md-4">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>METROS TENDIDO A&Eacute;REO</label>
                                                                    <input id="inputMetroTenAereo" name="inputMetroTenAereo" type="text" class="form-control" value="<?php echo isset($metro_tendido_aereo) ? $metro_tendido_aereo : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>METROS TENDIDO SUBTERRANEO</label>
                                                                    <input id="inputMetroTenSubt" name="inputMetroTenSubt" type="text" class="form-control" value="<?php echo isset($metro_tendido_subterraneo) ? $metro_tendido_subterraneo : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>METROS NUEVA CANALIZACI&Oacute;N</label>
                                                                    <input id="inputMetroCana" name="inputMetroCana" type="text" class="form-control" value="<?php echo isset($metro_nue_cana) ? $metro_nue_cana : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-4 col-md-4">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>CANT. CAMARAS NUEVAS</label>
                                                                    <input id="cantCamaNue" name="cantCamaNue" type="text" class="form-control" value="<?php echo isset($cant_camaras_nuevas) ? $cant_camaras_nuevas : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>CANT. POSTES NUEVOS</label>
                                                                    <input id="inputPostNue" name="inputPostNue" type="text" class="form-control" value="<?php echo isset($cant_postes_nuevos) ? $cant_postes_nuevos : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>CANT. POSTES DE APOYO</label>
                                                                    <input id="inputCantPostApo" name="inputCantPostApo" type="text" class="form-control" value="<?php echo isset($cant_postes_apoyo) ? $cant_postes_apoyo : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div>  
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-3 col-md-3">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>CANT. APERTURA DE C&Aacute;MARA</label>
                                                                    <input id="inputCantAperCamara" name="inputCantAperCamara" type="text" class="form-control" value="<?php echo isset($cant_apertura_camara) ? $cant_apertura_camara : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab_ent" role="tabpanel">
                                                        <div class="panel panel-default form-group container" id="panelExpedienteDiseno">
                                                            <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                                Ingreso de Datos
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="col-sm-12 col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                            <div class="form-group">
                                                                                <label>REQUIERE SEIA</label>
                                                                                <select id="selectRequeSeia" name="selectRequeSeia" class="select2 form-control" disabled>
                                                                                    <option value="">Seleccionar</option>
                                                                                    <option value="NO">NO</option>     
                                                                                    <option value="SI">SI</option>                                                    
                                                                                </select>
                                                                            </div>
                                                                        </div> 
                                                                        <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                            <div class="form-group">
                                                                                <label>REQUIERE APROBACION MML, MTC</label>
                                                                                <select id="selectRequeAproMmlMtc" name="selectRequeAproMmlMtc" class="select2 form-control" disabled>
                                                                                    <option value="">Seleccionar</option>  
                                                                                    <option value="NO">NO</option>     
                                                                                    <option value="SI">SI</option>                                                    
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                            <div class="form-group">
                                                                                <label>REQUIERE APROBACION INC(PMA)</label>
                                                                                <select id="selectRequeAprobINC" name="selectRequeAprobINC" class="select2 form-control" disabled>
                                                                                    <option value="">Seleccionar</option>   
                                                                                    <option value="NO">NO</option>     
                                                                                    <option value="SI">SI</option>                                                    
                                                                                </select>
                                                                            </div>
                                                                        </div>   
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                            <div class="form-group">
                                                                                <label>DURACI&Oacute;N (D&Iacute;AS)</label>
                                                                                <input id="inputDias" name="inputDias" class="form-control" style="background:#FEFAF9" value="<?php echo isset($duracion) ? $duracion : NULL; ?>" disabled>
                                                                                <i class="form-group__bar"></i>
                                                                                <div id="mensajeFileExp"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                            <div class="form-group">
                                                                                <label>TIPO DISE&Ntilde;O</label>
                                                                                <select id="cmbTipoDiseno" name="cmbTipoDiseno" class="select2 form-control">
                                                                                    <option value="">Seleccionar</option>
                                                                                    <?php
                                                                                    foreach ($arrayTipoDiseno AS $row) {
                                                                                        echo '<option value="' . $row['id_tipo_diseno'] . '">' . utf8_decode($row['descripcion']) . '</option>';
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                                <div id="mensajeTipoDiseno"></div>
                                                                            </div>    
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>            

                                                        <div class="panel panel-default form-group">
                                                            <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                                Asignar Entidades
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="col-sm-12 col-md-12 form-inline" id="formEntidades">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel panel-default form-group">
                                                            <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                                Datos para PO Autom&aacute;tico
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-2" id="contAmplificadores">
                                                                    </div>
                                                                </div>
                                                                <div id="mensajeAmplificador"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab_cost" role="tabpanel">
                                                        <div class="panel panel-default form-group container" id="panelExpedienteDiseno">
                                                            <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                                Ingreso de Costos
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="col-sm-12 col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-sm-3 col-md-4">
                                                                            <div class="form-group has-feedback" style="">
                                                                                <label>COSTO MATERIALES</label>
                                                                                <input onchange="getcalculos()" id="inputCostoMat" name="inputCostoMat" class="form-control" value="<?php echo isset($costo_materiales) ? $costo_materiales : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                                <i class="form-group__bar"></i>
                                                                                <div id="mensajeCostoMat"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 col-md-4">
                                                                            <div class="form-group has-feedback" style="">
                                                                                <label>COSTO MANO DE OBRA</label>
                                                                                <input onchange="getcalculos()" id="inputCostMo" name="inputCostMo" class="form-control" value="<?php echo isset($costo_mano_obra) ? $costo_mano_obra : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                                <i class="form-group__bar"></i>
                                                                                <div id="mensajeCostoMo"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 col-md-4">
                                                                            <div class="form-group has-feedback" style="">
                                                                                <label>COSTO DISE&Ntilde;O</label>
                                                                                <input onchange="getcalculos()" id="inputCostoDiseno" name="inputCostoDiseno" class="form-control" value="<?php echo isset($costo_diseno) ? $costo_diseno : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                                <i class="form-group__bar"></i>
                                                                                <div id="mensajeCostoDiseno"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-4 col-md-4">
                                                                            <div class="form-group has-feedback" style="">
                                                                                <label>COSTO EXP. SEIA,CIRA,PMEA S./</label>
                                                                                <select id="cmbMontoEIA" name="cmbMontoEIA" class="select2 form-control" onchange="getcalculos()">
                                                                                    <option value="">Seleccionar monto</option>
                                                                                    <option value="0">0</option>   
                                                                                    <option value="5000">5000</option>    
                                                                                    <option value="8000">8000</option>    
                                                                                    <option value="12000">12000</option>    
                                                                                    <option value="18000">18000</option>    
                                                                                    <option value="25000">25000</option>  
                                                                                    <option value="30000">30000</option>  
                                                                                    <option value="35000">35000</option>  
                                                                                </select>
                                                                                <i class="form-group__bar"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 col-md-4">
                                                                            <div class="form-group has-feedback" style="">
                                                                                <label>COSTO ADIC. ZONA RURAL S./</label>
                                                                                <input onchange="getcalculos()" id="inputCostoAdicZona" name="inputCostoAdicZona" class="form-control" value="<?php echo isset($costo_adicional_rural) ? $costo_adicional_rural : NULL; ?>"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                                <i class="form-group__bar"></i>
                                                                                <div id="mensajeCostoAdicional"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 col-md-4">
                                                                            <div class="form-group has-feedback" style="">
                                                                                <label>COSTO TOTAL S./</label>
                                                                                <input id="inputCostoTotal" name="inputCostoTotal" type="text" class="form-control" value="<?php echo isset($costo_total) ? $costo_total : NULL; ?>" disabled><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                                                <i class="form-group__bar"></i>
                                                                                <div id="mensajeCostoTotal"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>   

                                                        <div class="panel panel-default form-group container" id="panelExpedienteDiseno">
                                                            <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                                Expediente dise&ntilde;o: Archivo .rar (Archivo de metrados, planos, fotos y documentos)
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="col-sm-12 col-md-12 form-inline">
                                                                    <div class="col-12">
                                                                        <input id="fileExpedienteDiseno" name="fileExpedienteDiseno" type="file" accept=".zip,.rar" onchange="habilitarAceptar2()">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-12">
                                                                <div class="form-group has-feedback" style="">
                                                                    <label>COMENTARIO</label>
                                                                    <textarea id="textareaComentario" name="textareaComentario" class="form-control"></textarea>
                                                                    <i class="form-group__bar"></i>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-4">
                                                                <input type="checkbox" id="idOTActualizacion" class="custom-control-input" >
                                                                <label for="idOTActualizacion" >Requiere OT de Actualizacion?</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input id="hfSisegoFG" name="hfSisegoFG" value="" type="hidden"/>
                                                <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                                        <button  type="submit" id="btnAceptarEnt"  class="btn btn-primary" disabled>Aceptar</button>
                                                    </div>
                                                </div> 
                                            </form>           
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($idProyecto != ID_PROYECTO_SISEGOS) { ?>
                                    <div class="" id="divFormularioEntidades">
                                        <div class="tab-container tab-container--green form-group">
                                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#tab_princ" role="tab">ASIGNAR ENTIDADES</a>
                                                </li>                                         
                                            </ul>
                                            <form id="formRegDiseno" method="POST" class="form-horizontal">
                                                <div class="tab-content">
                                                    <div class="tab-pane active fade show" id="tab_princ" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-sm-12 col-md-12 form-inline" id="formEntidades">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="panel-heading" style="font-weight: bold;color: black;">Expediente dise&ntilde;o: Archivo .rar (Archivo de metrados, planos, fotos y documentos)</div>
                                                            <br>
                                                            <div class="panel-body">
                                                                <div class="col-sm-12 col-md-12 form-inline">
                                                                    <div class="col-12">
                                                                        <input id="fileExpedienteDiseno" name="fileExpedienteDiseno" type="file" accept=".zip,.rar" onchange="habilitarAceptar2()">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="panel-heading" style="font-weight: bold;color: black;">Datos para PO Autom&aacute;tico</div>
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-md-2" id="contAmplificadores">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <input type="checkbox" id="idOTActualizacion" class="custom-control-input" >
                                                                <label for="idOTActualizacion" >Requiere OT de Actualizacion?</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input id="hfSisegoFG" name="hfSisegoFG" value="" type="hidden"/>
                                                <div id="divFinDePartida" class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                                    <div class="col-sm-12">
                                                        <a style="color:white" onclick="updatePartidas()"  class="btn btn-primary btn-large">Esta partida no necesita licencia</a>
                                                    </div>
                                                </div> 
                                                <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                                        <button  type="submit" id="btnAceptarEnt"  class="btn btn-primary" disabled>Aceptar</button>
                                                    </div>
                                                </div> 
                                            </form>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalEditEntidadesInfo">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" style="margin: auto;font-weight: bold;">ENTIDADES</h3>    
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div id="infoCotizacionSisego">
                                    <h3 class="modal-title" style="margin: auto;font-weight: bold;">DATOS DE DISE&Ntilde;O NO EJECUTADOS</h3>    
                                </div>
                                <div class="" id="divFormularioSisegoInfo">
                                    <div class="tab-container tab-container--green form-group">
                                        <ul class="nav nav-tabs nav-fill" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#tab_princ_info" role="tab">DATOS PRINCIPALES</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab_met_info" role="tab">METRADOS</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab_ent_info" role="tab">ENTIDADES - LICENCIAS</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#tab_cost_info" role="tab">COSTOS</a>
                                            </li>                                           
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active fade show" id="tab_princ_info" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label>NODO PRINCIPAL</label>
                                                            <input id="infoNodoPrincipal" name="infoNodoPrincipal" type="text" class="form-control" disabled><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label>NODO RESPALDO</label>
                                                            <input id="infoNodoRespaldo" name="infoNodoRespaldo" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>FACILIDADES DE RED</label>
                                                            <input id="infoFacilidadesRed" name="infoFacilidadesRed" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>CANTIDAD CTO</label>
                                                            <input id="infoCantCTO" name="infoCantCTO" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab_met_info" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>METROS TENDIDO A&Eacute;REO</label>
                                                            <input id="infoMetroTenAereo" name="infoMetroTenAereo" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>METROS TENDIDO SUBTERRANEO</label>
                                                            <input id="infoMetroTenSubt" name="infoMetroTenSubt" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>METROS NUEVA CANALIZACI&Oacute;N</label>
                                                            <input id="infoMetroCana" name="infoMetroCana" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>CANT. CAMARAS NUEVAS</label>
                                                            <input id="cantCamaNue" name="cantCamaNue" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>CANT. POSTES NUEVOS</label>
                                                            <input id="infoPostNue" name="infoPostNue" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>CANT. POSTES DE APOYO</label>
                                                            <input id="infoCantPostApo" name="infoCantPostApo" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3 col-md-3">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>CANT. APERTURA DE C&Aacute;MARA</label>
                                                            <input id="infoCantAperCamara" name="infoCantAperCamara" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab_ent_info" role="tabpanel">
                                                <div class="panel panel-default form-group container" id="panelExpedienteDiseno">
                                                    <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                        Ingreso de Datos
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="row">
                                                                <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                    <div class="form-group">
                                                                        <label>REQUIERE SEIA</label>
                                                                        <input id="infoRequeSeia" name="infoRequeSeia" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    </div>
                                                                </div> 
                                                                <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                    <div class="form-group">
                                                                        <label>REQUIERE APROBACION MML, MTC</label>
                                                                        <input id="infoRequeAproMmlMtc" name="infoRequeAproMmlMtc" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                    <div class="form-group">
                                                                        <label>REQUIERE APROBACION INC(PMA)</label>
                                                                        <input id="infoRequeAprobINC" name="infoRequeAprobINC" disabled type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                    </div>
                                                                </div>   
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                    <div class="form-group">
                                                                        <label>DURACI&Oacute;N (D&Iacute;AS)</label>
                                                                        <input id="infoDias" name="infoDias" disabled class="form-control" style="background:#FEFAF9" disabled>
                                                                        <i class="form-group__bar"></i>
                                                                        <div id="mensajeFileExp"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 col-md-4" id="contKickoff">
                                                                    <div class="form-group">
                                                                        <label>TIPO DISE&Ntilde;O</label>
                                                                        <input id="infoTipoDiseno" name="infoTipoDiseno" disabled class="form-control" style="background:#FEFAF9" disabled>
                                                                        <div id="mensajeTipoDiseno"></div>
                                                                    </div>    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>            

                                                <div class="panel panel-default form-group">
                                                    <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                        Asignar Entidades
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-sm-12 col-md-12 form-inline" id="formEntidadesInfo">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-default form-group">
                                                    <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                        Datos para PO Autom&aacute;tico
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-2" id="contAmplificadores">
                                                            </div>
                                                        </div>
                                                        <div id="mensajeAmplificador"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab_cost_info" role="tabpanel">
                                                <div class="panel panel-default form-group container" id="panelExpedienteDiseno">
                                                    <div class="panel-heading form-group" style="font-weight: bold;color: black;">
                                                        Ingreso de Costos
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="row">
                                                                <div class="col-sm-3 col-md-4">
                                                                    <div class="form-group has-feedback" style="">
                                                                        <label>COSTO MATERIALES</label>
                                                                        <input onchange="getcalculos()" id="infoCostoMat" disabled name="infoCostoMat" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                        <i class="form-group__bar"></i>
                                                                        <div id="mensajeCostoMat"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 col-md-4">
                                                                    <div class="form-group has-feedback" style="">
                                                                        <label>COSTO MANO DE OBRA</label>
                                                                        <input onchange="getcalculos()" id="infoCostMo" disabled name="infoCostMo" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                        <i class="form-group__bar"></i>
                                                                        <div id="mensajeCostoMo"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 col-md-4">
                                                                    <div class="form-group has-feedback" style="">
                                                                        <label>COSTO DISE&Ntilde;O</label>
                                                                        <input onchange="getcalculos()" id="infoCostoDiseno" disabled name="infoCostoDiseno" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                        <i class="form-group__bar"></i>
                                                                        <div id="mensajeCostoDiseno"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-4 col-md-4">
                                                                    <div class="form-group has-feedback" style="">
                                                                        <label>COSTO EXP. SEIA,CIRA,PMEA S./</label>
                                                                        <input id="infoMontoEIA" name="infoMontoEIA" disabled class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                        <i class="form-group__bar"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 col-md-4">
                                                                    <div class="form-group has-feedback" style="">
                                                                        <label>COSTO ADIC. ZONA RURAL S./</label>
                                                                        <input onchange="getcalculos()" id="infoCostoAdicZona" disabled name="infoCostoAdicZona" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                                                        <i class="form-group__bar"></i>
                                                                        <div id="mensajeCostoAdicional"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-3 col-md-4">
                                                                    <div class="form-group has-feedback" style="">
                                                                        <label>COSTO TOTAL S./</label>
                                                                        <input id="infoCostoTotal" name="infoCostoTotal" disabled type="text" class="form-control" disabled><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                                                        <i class="form-group__bar"></i>
                                                                        <div id="mensajeCostoTotal"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="form-group has-feedback" style="">
                                                            <label>COMENTARIO</label>
                                                            <textarea id="infoareaComentario" disabled name="infoareaComentario" class="form-control"></textarea>
                                                            <i class="form-group__bar"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-4">
                                                        <input type="checkbox" id="idOTActualizacion" disabled class="custom-control-input" >
                                                        <label for="idOTActualizacion" >Requiere OT de Actualizacion?</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input id="hfSisegoFG" name="hfSisegoFG" value="" type="hidden"/>
                                        <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>         
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade bd-example-modal-sm" id="modalProgreso" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content" style="text-align: center;">
                            <div class="modal-header">
                                <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title"></h4>                       
                            </div>
                            <div class="modal-body">
                                <div id="contProgres">
                                    <div class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                        <span id="valuePie" class="easy-pie-chart__value">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </section>
            <?php
            //MODAL SISEGO
            echo include('application/views/vf_formulario/v_sisego.php');
            ?>
        </main>


        <!-- Javascript -->
        <!-- ..vendors -->
        <script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jqvmap/dist/jquery.vmap.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jqvmap/dist/maps/jquery.vmap.world.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/salvattore/dist/salvattore.min.js"></script>
        <script src="<?php echo base_url(); ?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/moment/min/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

        <!--  tables -->
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>

        <!--  -->
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <!-- <script src="<?php echo base_url(); ?>public/js/sinfix.js?v=<?php echo time(); ?>"></script> -->

        <!-- App functions and actions -->
        <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>public/fancy/source/jquery.fancybox.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>

        <!-- Demo -->
        <script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time(); ?>"></script>
        <script src="<?php echo base_url(); ?>public/js/js_pqt_obra_diseno/js_pqt_diseno.js?v=<?php echo time(); ?>"></script> 

        <script type="text/javascript">



                                                                            $('#selectCentral option[value="' +<?php echo "'" . $nodo_princ . "'" ?> + '"]').prop("selected", "selected").trigger("change");
                                                                            $('#selectCentral2 option[value="' +<?php echo "'" . $nodo_resp . "'" ?> + '"]').prop("selected", "selected").trigger("change");
                                                                            $('#selectRequeSeia option[value="' +<?php echo "'" . $requiere_seia . "'" ?> + '"]').prop("selected", "selected").trigger("change");
                                                                            $('#selectRequeAproMmlMtc option[value="' +<?php echo "'" . $requiere_aprob_mml_mtc . "'" ?> + '"]').prop("selected", "selected").trigger("change");
                                                                            $('#selectRequeAprobINC option[value="' +<?php echo "'" . $requiere_aprob_inc . "'" ?> + '"]').prop("selected", "selected").trigger("change");

                                                                            $('#cmbTipoDiseno option[value="' +<?php echo "'" . $id_tipo_diseno . "'" ?> + '"]').prop("selected", "selected").trigger("change");
                                                                            $('#cmbMontoEIA option[value="' +<?php echo "'" . $costo_expe_seia_cira_pam . "'" ?> + '"]').prop("selected", "selected").trigger("change");

                                                                            function editarAdjudicacion(component) {
                                                                                var itemplan = $(component).attr('data-itemplan');
                                                                                var idEstacion = $(component).attr('data-idEstacion');
                                                                                var estaDesc = $(component).attr('data-esta');
                                                                                var has_file = $(component).attr('data-has_file');

                                                                                $.ajax({
                                                                                    type: 'POST',
                                                                                    url: 'getInEjec',
                                                                                    data: {itemplan: itemplan,
                                                                                        idEstacion: idEstacion}
                                                                                }).done(function (data) {
                                                                                    data = JSON.parse(data);
                                                                                    if (data.error == 0) {
                                                                                        if (has_file == 0) {
                                                                                            $('#divFiles').show();
                                                                                        } else if (has_file == 1) {
                                                                                            $('#divFiles').hide();
                                                                                        }
                                                                                        $('#idFechaPreAtencionCoax').val('');
                                                                                        $('#formEditarAdju').bootstrapValidator('resetForm', true);
                                                                                        $('#tituloModal').html('ITEMPLAN ' + itemplan);
                                                                                        $('#descEsta').html(estaDesc);
                                                                                        $('#btnAddEvi').attr('data-idEsta', idEstacion);
                                                                                        $('#btnAddEvi').attr('data-item', itemplan);
                                                                                        $('#modalEditEjec').modal('toggle');
                                                                                    } else {
                                                                                        alert('error Interno intentelo de nuevo.');
                                                                                    }
                                                                                });
                                                                            }

                                                                            var itemPlanAnterior = null;
                                                                            $(document).ready(function () {
                                                                                $('#contTabla').css('display', 'block');
                                                                                $("body").on("click", ".ver_ptr", function () {
                                                                                    $("body").on("click", ".ver_ptr", function () {
                                                                                        $this = $(this);
                                                                                        var id = $(this).attr('data-idrow');
                                                                                        var idEstacion = $(this).attr('data-estacion');
                                                                                        $('#' + id).css('background-color', 'yellow');

                                                                                        if (itemPlanAnterior != null && itemPlanAnterior != id) {
                                                                                            $('#' + itemPlanAnterior).css('background-color', 'white');
                                                                                        }
                                                                                        itemPlanAnterior = id;
                                                                                        $.fancybox({
                                                                                            height: "100%", href: "pqt_detalleObra?item=" + $(this).text() + "&from=2&estacion=" + idEstacion, type: "iframe", width: "100%"
                                                                                        });
                                                                                        return!1
                                                                                    });
                                                                                });
                                                                            })

                                                                            $('#formEditarAdju')
                                                                                    .bootstrapValidator({
                                                                                        container: '#mensajeForm',
                                                                                        feedbackIcons: {
                                                                                            valid: 'glyphicon glyphicon-ok',
                                                                                            invalid: 'glyphicon glyphicon-remove',
                                                                                            validating: 'glyphicon glyphicon-refresh'
                                                                                        },
                                                                                        excluded: ':disabled',
                                                                                        fields: {

                                                                                        }
                                                                                    }).on('success.form.bv', function (e) {
                                                                                e.preventDefault();


                                                                                var $form = $(e.target),
                                                                                        formData = new FormData(),
                                                                                        params = $form.serializeArray(),
                                                                                        bv = $form.data('bootstrapValidator');

                                                                                $.each(params, function (i, val) {
                                                                                    formData.append(val.name, val.value);
                                                                                });

                                                                                var itemplan = $('#btnAddEvi').attr('data-item');
                                                                                formData.append('itemplan', itemplan);
                                                                                var idEsta = $('#btnAddEvi').attr('data-idEsta');
                                                                                formData.append('idEstacion', idEsta);

                                                                                var idEstacion = $.trim($('#idEstacion').val());
                                                                                var idTipoPlan = $.trim($('#idTipoPlanta').val());
                                                                                var jefatura = $.trim($('#cmbJefatura').val());
                                                                                var idProyecto = $.trim($('#cmbProyecto').val());
                                                                                var subProy = $.trim($('#cmbSubProy').val());
                                                                                var fecha = $.trim($('#filtrarFecha').val());

                                                                                formData.append('idEstacionFil', idEstacion);
                                                                                formData.append('idTipoPlan', idTipoPlan);
                                                                                formData.append('jefatura', jefatura);
                                                                                formData.append('idProyecto', idProyecto);
                                                                                formData.append('subProy', subProy);
                                                                                formData.append('fecha', fecha);

                                                                                $.ajax({
                                                                                    data: formData,
                                                                                    url: "editEjecuDi",
                                                                                    cache: false,
                                                                                    contentType: false,
                                                                                    processData: false,
                                                                                    type: 'POST'
                                                                                })
                                                                                        .done(function (data) {
                                                                                            data = JSON.parse(data);
                                                                                            if (data.error == 0) {
                                                                                                $('#contTabla').html(data.tablaAsigGrafo);
                                                                                                initDataTable('#data-table');

                                                                                                $('#modalEditEjec').modal('toggle');
                                                                                                mostrarNotificacion('success', 'Operacion exitosa.', 'Se registro correcamente!');
                                                                                            } else if (data.error == 1) {
                                                                                                console.log(data.error);
                                                                                            }
                                                                                        })
                                                                                        .fail(function (jqXHR, textStatus, errorThrown) {
                                                                                            mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                                                                                        })
                                                                                        .always(function () {

                                                                                        });


                                                                            });

                                                                            function revalidate() {
                                                                                var zoni = $('#selectZonificacion').val();
                                                                                if (zoni == '1') {
                                                                                    $('#divEECC').hide();
                                                                                    var validator = $('#formAdjudicaItem').data('bootstrapValidator');
                                                                                    validator.enableFieldValidators('selectEECCDiseno', false);
                                                                                } else if (zoni == '2') {
                                                                                    $('#divEECC').show();
                                                                                    var validator = $('#formAdjudicaItem').data('bootstrapValidator');
                                                                                    validator.enableFieldValidators('selectEECCDiseno', true);
                                                                                }

                                                                            }

                                                                            function ejecutarDiseno(component) {
                                                                                var itemplan = $(component).attr('data-item');
                                                                                $.ajax({
                                                                                    type: 'POST',
                                                                                    url: "getInfItem",
                                                                                    data: {item: itemplan},
                                                                                    'async': false
                                                                                })
                                                                                        .done(function (data) {
                                                                                            data = JSON.parse(data);
                                                                                            if (data.error == 0) {


                                                                                                $('#inputFecPrevEjec').val(data.fec_prev_eje);
                                                                                                $('#inputFecInicio').val(data.fec_inicio);

                                                                                                //$('#selectSubAdju').val(data.idSubProyecto).trigger('chosen:updated');
                                                                                                $('#selectSubAdju').val(data.subpro).trigger('change');

                                                                                                $('#tituloModal').html('ITEMPLAN: ' + itemplan);
                                                                                                $('#btnAdjudica').attr('data-item', itemplan);

                                                                                                $('#divEECC').hide();
                                                                                                var validator = $('#formAdjudicaItem').data('bootstrapValidator');
                                                                                                validator.enableFieldValidators('selectEECCDiseno', false);

                                                                                                $('#modalEjec').modal('toggle');
                                                                                            } else if (data.error == 1) {
                                                                                                console.log(data.error);
                                                                                            }
                                                                                        })


                                                                            }
        </script>
    </body>

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>