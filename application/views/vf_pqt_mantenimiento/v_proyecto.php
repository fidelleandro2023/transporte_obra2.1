<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">


    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="public/img/iconos/iconfinder_movistar.png">

    <!-- Vendor styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css">
    </link>

    <style type="text/css">
        .select2-dropdown {
            z-index: 100000;
        }

        .h2 {
            color: #00A9E0;
        }

        div#fases-contain {
            width: 350px;
            margin: 10px 0;
        }

        div#fases-contain table {
            margin: 1em 0;
            border-collapse: collapse;
            width: 100%;
        }

        div#fases-contain table td,
        div#fases-contain table th {
            border: 1px solid #eee;
            padding: .6em 5px;
            text-align: left;
        }

        div#tablaFases {
            width: 350px;
            margin: 10px 0;
        }

        div#tablaFases table {
            margin: 1em 0;
            border-collapse: collapse;
            width: 100%;
        }

        div#tablaFases table td,
        div#tablaFases table th {
            border: 1px solid #eee;
            padding: .6em 5px;
            text-align: left;
        }

        div#divInfoSubProyectoFases {
            margin: 10px 0;
        }

        div#divInfoSubProyectoFases table {
            margin: 1em 0;
            border-collapse: collapse;
            width: 100%;
        }

        div#divInfoSubProyectoFases table td,
        div#divInfoSubProyectoFases table th {
            border: 1px solid #eee;
            padding: .6em 5px;
            text-align: left;
        }

        .scrollTabla {
            height: 400px;
            width: 800px;
            overflow: scroll;
        }

        .select2 {
            width: 100% !important;

        }
    </style>

    <link rel="stylesheet" href="<?php echo base_url(); ?>public/demo/css/demo.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css">
</head>

<div data-ma-theme="entel">
    <main class="main">
        <div class="page-loader">
            <div class="page-loader__spinner">
                <svg viewBox="25 25 50 50">
                    <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
                </svg>
            </div>
        </div>

        <header class="header">
            <div class="navigation-trigger" data-ma-action="aside-open" data-ma-target=".sidebar">
                <div class="navigation-trigger__inner">
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                    <i class="navigation-trigger__line"></i>
                </div>
            </div>

            <div class="header__logo hidden-sm-down" style="text-align: center;">
                <a href="https://www.movistar.com.pe/" title="Entel Perï¿½"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
            </div>
            <?php include('application/views/v_opciones.php'); ?>
        </header>

        <aside class="sidebar sidebar--hidden">
            <div class="scrollbar-inner">
                <div class="user">
                    <div class="user__info" data-toggle="dropdown">
                        <img class="user__img" src="<?php echo base_url(); ?>public/demo/img/profile-pics/8.jpg" alt="">
                        <div>
                            <div class="user__name"><?php echo $this->session->userdata('usernameSession') ?></div>
                            <div class="user__email"><?php echo $this->session->userdata('descPerfilSession') ?></div>
                        </div>
                    </div>


                </div>

                <ul class="navigation">
                    <?php echo $opciones ?>
                </ul>
            </div>
        </aside>


        <section class="content content--full">
            <div class="content__inner">
                <h2 style="color: #00A9E0">MANTENIMIENTO PROYECTO - SUBPROYECTO</h2>
                <div class="card">

                    <div class="card-block">
                        <div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#panelSubPro" role="tab">SUBPROYECTO</a>
                                </li>
                                <!--_____________________________________________________-->
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panelPro" role="tab">PROYECTO</a>
                                </li>
                                <!--_____________________________________________________-->
                                <li class="nav-item" style="display:none">
                                    <a class="nav-link" data-toggle="tab" href="#panelSubProActividad" role="tab">ACTIVIDAD
                                        POR SUBPROYECTO</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panelFirmaEmpresaColab" role="tab">FIMA EECC</a>
                                </li>
                            </ul>
                            <!--_____________________________________________________-->
                            <!--_____________________________________________________-->
                            <div class="tab-content">
                                <div class="tab-pane active fade show" id="panelSubPro" role="tabpanel">
                                    <div class="col-sm-6 col-md-4" style="TEXT-ALIGN: center;margin-top: 25px;    margin-left: auto;">
                                        <div class="form-group">
                                            <button style="background-color: var(--verde_telefonica)" onclick="addNewSubProyecto();" type="button" class="btn btn-success waves-effect">
                                                <i class="zmdi zmdi-plus-circle-o zmdi-hc-fw"></i>NUEVO SUB PROYECTO
                                            </button>
                                        </div>
                                    </div>

                                    <div id="contTablaSubProyecto" class="table-responsive">
                                        <?php echo $tbSubproyecto ?>
                                    </div>
                                </div>
                                <!--_____________________________________________________-->

                                <div class="tab-pane fade" id="panelPro" role="tabpanel">
                                    <div class="col-sm-6 col-md-4" style="TEXT-ALIGN: center;margin-top: 25px;    margin-left: auto;">
                                        <div class="form-group">
                                            <button style="background-color: var(--verde_telefonica)" onclick="addNewProyecto();" type="button" class="btn btn-success waves-effect">
                                                <i class="zmdi zmdi-plus-circle-o zmdi-hc-fw"></i>NUEVO PROYECTO
                                            </button>
                                        </div>
                                    </div>

                                    <div id="contTablaProyecto" class="table-responsive">
                                        <?php echo $tbProyecto ?>
                                    </div>
                                </div>
                                <!--__________________________ACTIVIDAD POR SUBPROYECTO___________________________-->

                                <div class="tab-pane fade" id="panelSubProActividad" role="tabpanel">
                                    <div class="row">
                                        <div class="form-group col-md-1">
                                            <label for="">SubProyecto:</label>
                                            <select id="selectSubProyecto" style="background-color: #2580d4; color: white;">
                                                <?php foreach ($listaSubProyecto->result() as $s) : ?>
                                                    <option value="<?php echo $s->idSubProyecto; ?>"><?php echo $s->subProyectoDesc; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-4" style="TEXT-ALIGN: center;margin-top: 25px;    margin-left: auto;">
                                            <div class="form-group">
                                                <button style="background-color: var(--verde_telefonica)" onclick="addNewActividad();" type="button" class="btn btn-success waves-effect">
                                                    <i class="zmdi zmdi-plus-circle-o zmdi-hc-fw"></i>NUEVO ACTIVIDAD
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="contTablaSubProyectoActividad" class="table-responsive">

                                    </div>
                                </div>
                                <!--_____________________________________________________-->

                                <!-- START FIRMA EECC -->
                                <div class="tab-pane fade" id="panelFirmaEmpresaColab" role="tabpanel">
                                    <div class="d-flex justify-content-end mb-3">
                                        <button onclick="openAddFirmaEmpresaColab();" class="btn btn-success waves-effect">
                                            <i class="zmdi zmdi-plus-circle-o zmdi-hc-fw"></i>NUEVO
                                        </button>
                                    </div>

                                    <div id="contTablaFirmaEmpresaColab" class="table-responsive">
                                        <?php echo isset($tablaFirmaEmpresaColab) ? $tablaFirmaEmpresaColab : null ?>
                                    </div>
                                </div>
                                <!-- END FIRMA EECC -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>
    <!-- Small -->

    <div class="modal fade" id="modalAddFirmaEmpresaColab">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left">AGREGAR</h5>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>USUARIO</label>
                                <select id="cboUsuarioFirmaEmpresaColab" class="select2" onchange="handlePrintUser(this)"></select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="d-flex">
                                <span class="badge badge-pill badge-primary">Nombre:</span>&nbsp;<span id="txtUsuarioFirma"></span>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="d-flex">
                                <span class="badge badge-pill badge-primary">EECC:</span>&nbsp;<span id="txtEmpresaColabFirma"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>TIPO PLANTA</label>
                                <select id="cboTipoPlantaFirmaEmpresaColab" class="select2">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($listaTipoPlanta->result() as $row) { ?>
                                        <option value="<?php echo $row->idTipoPlanta ?>"><?php echo $row->tipoPlantaDesc ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex align-items-center justify-content-end">
                            <label id="lblActivoFirmaEmpresaColab">Activo?</label>
                            <input type="checkbox" id="chkActivoFirmaEmpresaColab" checked>
                        </div>
                    </div>

                    <span class="text-primary mb-3"><strong>Gerencias</strong></span>
                    <div class="d-flex align-items-center">
                        <input type="checkbox" id="chkDespliegueFirma">
                        <label id="lblDespliegue">DESPLIEGUE ACCESO MOVIL, FIJO, REDES IP ENERGIA Y TRANSPORTE</label>
                    </div>
                </div>

                <div class="form-group" style="text-align: right;">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button id="guardarFirmaEmpresaCola" type="button" class="btn btn-primary" onclick="saveFirmaDig(this)">Guardar</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="modalAddProyecto">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left">NUEVO PROYECTO</h5>
                </div>
                <div class="modal-body">
                    <form id="formAddProyecto" method="post" class="form-horizontal">
                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                            <label>DESCRIPCION</label>
                            <input id="inputDescPro" name="inputDescPro" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <label>TIPO CENTRAL</label>
                            <select id="selectTipoCentral" name="selectTipoCentral" class="select2 form-control">
                                <option>&nbsp;</option>
                                <?php foreach ($listaTipoCentral->result() as $row) { ?>
                                    <option value="<?php echo $row->idTipoCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">TIPO LABEL</label>
                            <select id="selectTipoLabel" name="selectTipoLabel" class="select2 form-control">
                                <option>&nbsp;</option>
                                <?php foreach ($listaTipoLabel->result() as $row) { ?>
                                    <option value="<?php echo $row->idTipolabel ?>"><?php echo $row->tipoLabelDesc ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">GERENCIA</label>
                            <select id="selectGerenciaProyecto" name="selectGerenciaProyecto" class="select2 form-control">
                                <?php echo isset($cmbGerenciaFirmaDigital) ? $cmbGerenciaFirmaDigital : null ?>
                            </select>
                        </div>


                        <div id="mensajeForm"></div>
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                <button id="btnSave" type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditProyecto">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR PROYECTO</h5>
                </div>
                <div class="modal-body">
                    <form id="formEditProyecto" method="post" class="form-horizontal">
                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                            <label>DESCRIPCION</label>
                            <input id="inputDescPro2" name="inputDescPro2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                            <i class="form-group__bar"></i>
                        </div>
                        <div class="form-group">
                            <label>TIPO CENTRAL</label>
                            <select id="selectTipoCentral2" name="selectTipoCentral2" class="select2 form-control">
                                <option>&nbsp;</option>
                                <?php foreach ($listaTipoCentral->result() as $row) { ?>
                                    <option value="<?php echo $row->idTipoCentral ?>"><?php echo $row->tipoCentralDesc ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">TIPO LABEL</label>
                            <select id="selectTipoLabel2" name="selectTipoLabel2" class="select2 form-control">
                                <option>&nbsp;</option>
                                <?php foreach ($listaTipoLabel->result() as $row) { ?>
                                    <option value="<?php echo $row->idTipolabel ?>"><?php echo $row->tipoLabelDesc ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">GERENCIA</label>
                            <select id="selectGerenciaProyecto2" name="selectGerenciaProyecto2" class="select2 form-control">
                                <?php echo isset($cmbGerenciaFirmaDigital) ? $cmbGerenciaFirmaDigital : null ?>
                            </select>
                        </div>

                        <div id="mensajeForm2"></div>
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                <button id="btnEditPro" type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddSubProyecto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left">NUEVO SUB PROYECTO</h5>
                </div>
                <div class="modal-body">
                    <form id="formAddSubProyecto" method="post" class="form-horizontal">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>PROYECTO</label>
                                    <select id="selectProyecto2" name="selectProyecto2" class="select2 form-control" onchange="mostrarTipoSubProy('selectProyecto2','divTipoSubProy','selectTipoSubProy')">
                                        <option>&nbsp;</option>
                                        <?php foreach ($listaProyectos->result() as $row) { ?>
                                            <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div id="contInputCorreP" class="form-group has-feedback" style="">
                                    <label>SUB PROYECTO</label>
                                    <input id="inputDescSubPro" name="inputDescSubPro" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">TIPO PLANTA</label>
                                    <select id="selectTipoPlanta" name="selectTipoPlanta" class="select2 form-control">
                                        <option>&nbsp;</option>
                                        <?php foreach ($listaTipoPlanta->result() as $row) { ?>
                                            <option value="<?php echo $row->idTipoPlanta ?>"><?php echo $row->tipoPlantaDesc ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">TIEMPO</label>
                                    <select id="selectTiempo" name="selectTiempo" class="select2 form-control">
                                        <option>&nbsp;</option>
                                        <option value="30 days">30 days</option>
                                        <option value="60 days">60 days</option>
                                        <option value="90 days">90 days</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label id="lblOpex">OPEX ?</label>
                                    <input type="checkbox" id="cbxOPEX" name="cbxOPEX" style="text-align: center;" onclick="cambiarColorLabelCbxEvt('lblOpex','cbxOPEX')">
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label id="lblCODIGO">CODIGO SITIO ?</label>
                                    <input type="checkbox" id="cbxCODIGOSITIO" name="cbxCODIGOSITIO" style="text-align: center;" onclick="cambiarColorLabelCbxEvt('lblCODIGO','cbxCODIGOSITIO')">
                                </div>
                            </div>
							<div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">ESTACI&Oacute;N</label>
                                    <select id="selectAreas" name="selectAreas" class="select2 form-control" multiple>
                                        <option>&nbsp;</option>
                                        <?php foreach ($listaEstaciones->result() as $row) { ?>
                                            <option value="<?php echo $row->idEstacion ?>"><?php echo utf8_decode($row->estacionDesc) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        

                            <hr>
                            <div class="col-sm-12 col-md-12 mb-2" id="contTablaUsuariosTdpByFirma"></div>
                        </div>

                        <div id="mensajeForm3"></div>
                        <div class="form-group" style="text-align: right;">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                <button id="btnAddSubPro" type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- EDITAR SUB PROYECTO -->
    <div class="modal fade" id="modalEditSubProyecto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="margin: auto;">
                    <h5 style="font-weight: bold;" class="modal-title pull-left" id="tituloEditSubProyecto">EDITAR SUB PROYECTO </h5>
                </div>
                <div class="modal-body">
                    <form id="formEditSubProyecto" method="post" class="form-horizontal">
                        <div class="row">

                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>PROYECTO</label>
                                    <select id="selectProyecto3" name="selectProyecto3" class="select2 form-control" onchange="mostrarTipoSubProy('selectProyecto3','divTipoSubProy2','selectTipoSubProy2')">
                                        <option>&nbsp;</option>
                                        <?php foreach ($listaProyectos->result() as $row) { ?>
                                            <option value="<?php echo $row->idProyecto ?>"><?php echo $row->proyectoDesc ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div id="contInputCorreP" class="form-group has-feedback" style="">
                                    <label>SUB PROYECTO</label>
                                    <input id="inputDescSubPro2" name="inputDescSubPro2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
                                    <i class="form-group__bar"></i>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">TIPO PLANTA</label>
                                    <select id="selectTipoPlanta2" name="selectTipoPlanta2" class="select2 form-control">
                                        <option>&nbsp;</option>
                                        <?php foreach ($listaTipoPlanta->result() as $row) { ?>
                                            <option value="<?php echo $row->idTipoPlanta ?>"><?php echo $row->tipoPlantaDesc ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">TIEMPO</label>
                                    <select id="selectTiempo2" name="selectTiempo2" class="select2 form-control">
                                        <option>&nbsp;</option>
                                        <option value="30 days">30 days</option>
                                        <option value="60 days">60 days</option>
                                        <option value="90 days">90 days</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6" id="divTipoSubProy2">
                                <div class="form-group">
                                    <label class="control-label">Tipo de SubProyecto</label>
                                    <select id="selectTipoSubProy2" name="selectTipoSubProy2" class="select2 form-control">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label id="lblOpexEdit">OPEX ?</label>
                                <input type="checkbox" id="cbxOPEXedit" name="cbxOPEXedit" style="text-align: center;" onclick="cambiarColorLabelCbxEvt('lblOpexEdit','cbxOPEXedit')">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label id="lblCODIGOedit">CODIGO SITIO ?</label>
                                <input type="checkbox" id="cbxCODIGOSITIOedit" name="cbxCODIGOSITIOedit" style="text-align: center;" onclick="cambiarColorLabelCbxEvt('lblCODIGOedit','cbxCODIGOSITIOedit')">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label id="lblManualOpexCapex">SISEGO?</label>
                                <input type="checkbox" id="cbxRegManualOpexCapex" name="cbxRegManualOpexCapex" style="text-align: center;">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group" id="tablaFases" class="ui-widget">

                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 mb-2" id="contTablaEdicionUsuariosTdpByFirma"></div>
                </div>
                <div id="mensajeForm4"></div>
                <div class="form-group" style="text-align: right;">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button id="btnEditSubPro" type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- EDITAR FASES -->
<div class="modal fade" id="modalEditFase">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="margin: auto;">
                <h5 id="h5modalEditFase" style="font-weight: bold;" class="modal-title pull-left">EDITAR CANTIDAD PLANIFICADA FASE</h5>
            </div>
            <div class="modal-body">
                <form id="formEditFase" method="post" class="form-horizontal">
                    <div class="form-group has-feedback" style="">
                        <label>ACTUAL CANTIDAD PLANIFICADA:</label>
                        <input id="txtActualCantidad" name="txtActualCantidad" type="number" class="form-control" disabled="disabled">
                    </div>
                    <div class="form-group has-feedback" style="">
                        <label>NUEVA CANTIDAD PLANIFICADA:</label>
                        <input id="txtNuevaCantidad" name="txtNuevaCantidad" type="number" class="form-control">
                    </div>
                    <div id="mensajeForm11"></div>
                    <div class="form-group" style="text-align: right;">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                            <button id="btnEditarFase" type="submit" class="btn btn-primary">Editar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Nueva Actividad -->

<!--
<div class="modal fade" id="modalAddActividad">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="margin: auto;">
                <h5 style="font-weight: bold;" class="modal-title pull-left">NUEVA ACTIVIDAD</h5>
            </div>
            <div class="modal-body">
                <form id="formAddActividad" method="post" class="form-horizontal">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label">ACTIVIDAD</label>
                                <select id="selectActividades" name="selectActividades" class="select2 form-control" multiple>
                                    <option>&nbsp;</option>
                                    <?php foreach ($listaActividad->result() as $a) : ?>
                                    <option value="<?php echo $a->idActividad; ?>"><?php echo  utf8_decode($a->descripcion); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="mensajeForm4"></div>
                    <div class="form-group" style="text-align: right;">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                            <button id="btnAddActividad" type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>-->


<!-- EDITAR SUB PROYECTO Actividd-->
<div class="modal fade" id="modalEditActividad">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="margin: auto;">
                <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR SUB PROYECTO - PAQUETIZADO</h5>
            </div>
            <div class="modal-body">
                <form id="formEditSubProyectoAct" method="post" class="form-horizontal">
                    <div class="row">

                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>SUBPROYECTO</label>
                                <select id="selectSubProyecto2" name="selectSubProyecto2" class="select2 form-control">
                                    <option>&nbsp;</option>
                                    <?php foreach ($listaSubProyecto->result() as $row) { ?>
                                        <option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>ACTIVIDADES</label>
                                <select id="selectActividad" name="selectActividad" class="select2 form-control">
                                    <option>&nbsp;</option>
                                    <?php foreach ($listaActividad->result() as $row) { ?>
                                        <option value="<?php echo $row->idActividad ?>"><?php echo $row->descripcion ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="mensajeForm5"></div>
                    <div class="form-group" style="text-align: right;">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                            <button id="btnEditSubProAct" type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInfoSubproyectosPorProyecto">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 800px">
            <div class="modal-header" style="margin: auto;">
                <h5 style="font-weight: bold;" class="modal-title pull-left">SUBPROYECTO - FASES</h5>
            </div>
            <div class="modal-body scrollTabla" id="divInfoSubProyectoFases">
            </div>
            <div class="form-group" style="text-align: right;">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetallePlan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="margin: auto;">
                <h5 style="font-weight: bold;" class="modal-title pull-left">PLANIFICACI&Oacute;N</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>NOMBRE PLAN</label>
                    <input id="nomPlan" name="nomPlan" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label>MES</label>
                    <select id="selectMes" name="selectMes" class="select2 form-control">

                    </select>
                </div>
                <div class="form-group">
                    <label>CANTIDAD</label>
                    <input id="cantidadPlan" name="cantidadPlan" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <button id="btnSave" type="submit" class="btn btn-primary" onclick="insertPlanifica();">Guardar</button>
                </div>
                <div id="contTablaPlanifica" class="table-responsive">
                </div>
            </div>
            <div class="form-group" style="text-align: right;">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- ..vendors -->

<script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/tether/dist/js/tether.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/Waves/dist/waves.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

<script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/flot/jquery.flot.resize.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

<!--  tables -->
<script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/jszip/dist/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>

<script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
<script src="<?php echo base_url(); ?>public/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- App functions and actions -->
<script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

<!-- Demo -->
<script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>

<!--  -->
<script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
<script src="<?php echo base_url(); ?>public/js/Utils.js"></script>
<script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>

<script type="text/javascript">
    marcarCombo();

    function marcarCombo() {
        $("#selectFicha").val('');
    }

    $(document).ready(function() {

        initDataTable('#data-table2');
        initDataTable('#data-table4');
	});

        $('#formAddProyecto')
            .bootstrapValidator({
                container: '#mensajeForm',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    inputDescPro: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar un nombre de Proyecto.</p>'
                            },
                            callback: {
                                message: '<p style="color:red">(*) Existe una descripcion similar. Debe modificar a una nueva descripcion</p>',
                                callback: function(value, validator) {
                                    var result = existeDescripcionProyecto(value);
                                    if (result == '1') { //Existe
                                        return false;
                                    } else {
                                        return true;
                                    }
                                }
                            }
                        }
                    },
                    selectTipoCentral: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un tipo de Central.</p>'
                            }
                        }
                    },
                    selectTipoLabel: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un tipo Label.</p>'
                            }
                        }
                    },
                    selectGerenciaProyecto: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar la gerencia.</p>'
                            }
                        }
                    },
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();


                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');

                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });

                $.ajax({
                        data: formData,
                        url: "addPro",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTablaProyecto').html(data.tbProyecto);
                            initDataTable('#data-table4');
                            $('#modalAddProyecto').modal('toggle');
                            mostrarNotificacion('success', 'Operacion exitosa.', 'Se registro correcamente!');
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', 'Comunuquese con alguna persona a cargo :(');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });


            });


        $('#formEditProyecto')
            .bootstrapValidator({
                container: '#mensajeForm2',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    inputDescPro2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar un nombre de Proyecto.</p>'
                            },
                            callback: {
                                message: '<p style="color:red">(*) Existe una descripcion similar. Debe modificar a una nueva descripcion</p>',
                                callback: function(value, validator) {
                                    var result = existeDescripcionProyecto(value);
                                    if (result == '1') { //Existe
                                        return false;
                                    } else {
                                        return true;
                                    }
                                }
                            }
                        }
                    },
                    selectTipoCentral2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un tipo de Central.</p>'
                            }
                        }
                    },
                    selectTipoLabel2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un tipo Label.</p>'
                            }
                        }
                    },
                    selectGerenciaProyecto2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar la gerencia.</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();


                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');


                var idPro = $('#btnEditPro').attr('data-id');
                formData.append('id', idPro);

                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });

                $.ajax({
                        data: formData,
                        url: "updatePro",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTablaProyecto').html(data.tbProyecto);

                            initDataTable('#data-table4');
                            $('#modalEditProyecto').modal('toggle');
                            mostrarNotificacion('success', 'Operaciï¿½n Exitosa.', 'Se registro correcamente!');
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', 'Comunï¿½quese con alguna persona a cargo :(');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comunï¿½quese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });


            });


        $('#formAddSubProyecto')
            .bootstrapValidator({
                container: '#mensajeForm3',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    selectProyecto2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un Proyecto.</p>'
                            }
                        }
                    },
                    inputDescSubPro: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar un nombre de Sub Proyecto.</p>'
                            }
                        }
                    },
                    selectTiempo: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar tiempo</p>'
                            }
                        }
                    },
                    selectTipoPlanta: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un tipo de Planta.</p>'
                            }
                        }
                    },
					selectAreas: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar al menos una estacion.</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();

                // var rowCount = $('#tFases tr').length - 1;

                // if (rowCount == 0) {
                    // $('#btnAddSubPro').attr('disabled', false);
                    // alert("Debe de registrar al menos una fase");
                    // return false;
                // }

                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');

                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });
				
				var valores = $('#selectAreas').val();
                formData.append('estaciones', valores);
				
                var checkedOpex = $("#cbxOPEX:checked").val();
                var flgCheckOpex = 0;
                if (checkedOpex) {
                    flgCheckOpex = 1;
                }


                var checkedCodigoSitio = $("#cbxCODIGOSITIO:checked").val();
                var flgCheckCodigoSitio = 0;
                if (checkedCodigoSitio) {
                    flgCheckCodigoSitio = 1;
                }

                // formData.append('tFases', JSON.stringify(tableToJson(tFases)));
                formData.append('flgCheckOpex', flgCheckOpex);
                formData.append('flgCheckCodigoSitio', flgCheckCodigoSitio);

                // Validamos los usuarios de firma digital
                // if (arrUsuarioTdpByFirma.length == 0) {
                    // mostrarNotificacion('warning', '', 'Completar los usuarios de la firma digital');
                    // $('#btnAddSubPro').removeAttr('disabled');
                    // return;
                // }

                // let isFirma = false;
                // $.each(arrUsuarioTdpByFirma, (i, e) => {
                    // if (e['idUsuario'] == "" || e['idUsuario'] == null) {
                        // mostrarNotificacion('warning', '', 'Completar los usuarios de la firma digital');
                        // $('#btnAddSubPro').removeAttr('disabled');
                        // isFirma = true;
                        // return;
                    // }
                // });

                // if (isFirma) {
                    // return;
                // }

                formData.append("arrUsuarioTdpByFirma", JSON.stringify(arrUsuarioTdpByFirma));

                $.ajax({
                        data: formData,
                        url: "pqt_addSubPro",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTablaSubProyecto').html(data.tbSubproyecto);
                            initDataTable('#data-table');
                            $('#modalAddSubProyecto').modal('toggle');
                            mostrarNotificacion('success', 'Operacion exitosa.', 'Se registro correcamente!');
                            //$('#tFases tbody').empty();

                            arrUsuarioTdpByFirma = [];
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }
                    })
                // .fail(function (jqXHR, textStatus, errorThrown) {
                // mostrarNotificacion('error', 'Error', 'Comunï¿½quese con alguna persona a cargo2 :(');
                // })
                // .always(function () {

                // });


            });


        $('#formEditSubProyecto')
            .bootstrapValidator({
                container: '#mensajeForm4',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    selectProyecto3: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un Proyecto.</p>'
                            }
                        }
                    },
                    inputDescSubPro2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar un nombre de Sub Proyecto.</p>'
                            }
                        }
                    },
                    selectTiempo2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar tiempo</p>'
                            }
                        }
                    },
                    selectTipoPlanta2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un tipo de Planta.</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();


                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');

                var oldTipoPlanta = $('#btnEditSubPro').attr('data-oldTipoPlanta');
                formData.append('old_tplanta', oldTipoPlanta);

                var idPro = $('#btnEditSubPro').attr('data-id');
                formData.append('id', idPro);

                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });

                var checkedOpex = $("#cbxOPEXedit:checked").val();
                var checkedManualOpexCapex = $('#cbxRegManualOpexCapex:checked').val();

                var flgCheckOpex = 0;
                var flgCheckManualOpexCapex = null;
                if (checkedOpex) {
                    flgCheckOpex = 1;

                    if (checkedManualOpexCapex) {
                        flgCheckManualOpexCapex = 2;
                    }
                } else {
                    if (checkedManualOpexCapex) {
                        flgCheckManualOpexCapex = 1;
                    }
                }

                var checkedCodigoSitio = $("#cbxCODIGOSITIOedit:checked").val();
                var flgCheckCodigoSitio = 0;
                if (checkedCodigoSitio) {
                    flgCheckCodigoSitio = 2;
                }
                formData.append('flgCheckOpex', flgCheckOpex);
                formData.append('flgCheckCodigoSitio', flgCheckCodigoSitio);
                formData.append('flgCheckManualOpexCapex', flgCheckManualOpexCapex);

                // Validamos los usuarios de firma digital
                // if (arrUsuarioTdpByFirma.length == 0) {
                    // mostrarNotificacion('warning', '', 'Completar los usuarios de la firma digital');
                    // $('#btnAddSubPro').removeAttr('disabled');
                    // return;
                // }

                // let isFirma = false;
                // $.each(arrUsuarioTdpByFirma, (i, e) => {
                    // if (e['idUsuario'] == "" || e['idUsuario'] == null) {
                        // mostrarNotificacion('warning', '', 'Completar los usuarios de la firma digital');
                        // $('#btnAddSubPro').removeAttr('disabled');
                        // isFirma = true;
                        // return;
                    // }
                // });

                // if (isFirma) {
                    // return;
                // }

                formData.append("arrUsuarioTdpByFirma", JSON.stringify(arrUsuarioTdpByFirma));
				
				// var arrayFase = getDataTabla('tFases');
				// formData.append("arrayFases", JSON.stringify(arrayFase));
				

                $.ajax({
                        data: formData,
                        url: "pqt_updSp",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    }).done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTablaSubProyecto').html(data.tbSubproyecto);
                            initDataTable('#data-table');
                            $('#modalEditSubProyecto').modal('toggle');
                            mostrarNotificacion('success', 'Operacion Exitosa.', 'Se edito correctamente!');

                            arrUsuarioTdpByFirma = [];
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }

                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });


            });

        $("#selectSubProyecto").change(function() {
            var idSubProyecto = $(this).val();

            $.ajax({
                    data: {
                        'idSubProyecto': idSubProyecto
                    },
                    url: "getSubproActividades",
                    type: 'POST'
                })
                .done(function(data) {
                    data = JSON.parse(data);

                    $('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                    initDataTable('#data-table7');

                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error', 'Comunï¿½quese con alguna persona a cargo :(');
                });
        });

        $('#formAddActividad')
            .bootstrapValidator({
                container: '#mensajeForm4',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    selectActividades: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar una Actividad.</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();


                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');

                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });

                var idSubProyecto = $('#selectSubProyecto').val();
                formData.append('idSubProyecto', idSubProyecto);

                var valores = $('#selectActividades').val();
                console.log('val:' + valores);
                formData.append('actividades', valores);

                $.ajax({
                        data: formData,
                        url: "addActiviadSubproyecto",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                            initDataTable('#data-table7');
                            $('#modalAddActividad').modal('toggle');
                            mostrarNotificacion('success', 'Operacion exitosa.', 'Se registro correcamente!');
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', 'Comunï¿½quese con alguna persona a cargo :(');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comunï¿½quese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });
            });

        $('#formEditSubProyectoAct')
            .bootstrapValidator({
                container: '#mensajeForm5',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    selectSubProyecto2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar un SubProyecto.</p>'
                            }
                        }
                    },
                    selectActividad: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar una Actividad.</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();

				console.log("ETNRO123");
                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');


                var idProAct = $('#btnEditSubProAct').attr('data-id');
                formData.append('id', idProAct);

                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });

                $.ajax({
                        data: formData,
                        url: "updateProAct",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            $('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                            initDataTable('#data-table7');
                            $('#modalEditActividad').modal('toggle');
                            mostrarNotificacion('success', 'Operaciï¿½n ï¿½xitosa.', 'Se registro correcamente!');
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', 'Comunï¿½quese con alguna persona a cargo :(');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comunï¿½quese con alguna persona a cargo :(');
                    })
                    .always(function() {

                    });


            });

    

    function addNewProyecto() {
        $('#inputDescPro').val('');
        $('#selectTipoCentral').val('').trigger('change');
        $('#selectTipoLabel').val('').trigger('change');
        $('#formAddProyecto').bootstrapValidator('resetForm', true);
        $('#modalAddProyecto').modal('toggle');
    }

    function addNewSubProyecto() {
        $('#inputDescSubPro').val('');
        $('#selectProyecto2').val('').trigger('change');
        $('#selectTiempo').val('').trigger('change');
        $('#selectTipoPlanta').val('').trigger('change');
        $('#selectAreas').val('').trigger('change');
        $('#selectTipoFactorMedicion').val('').trigger('change');
        $('#selectFicha').val('').trigger('change');

        $('#cbxAprobacionAutomatica').prop('checked', false);
        $("#lblAprobacion").css("color", "#707070");

        $('#formAddSubProyecto').bootstrapValidator('resetForm', true);
        $.ajax({
            type: 'POST',
            url: 'getCmbComplejidad'
        }).done(function(data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                $('#selectComplejidad').html(data.cmbTipoComplejidad);
                $('#selectTipoSubProy').html(data.cmbTipoSubProyecto);
                $('#divTipoSubProy').css('display', 'none');
            } else {
                mostrarNotificacion('error', 'Error', 'Hubo un error al cargar el combo');
            }
        });

        arrUsuarioTdpByFirma = [];

        $.ajax({
            type: 'POST',
            url: 'getCmbUsuariosByFirma',
            data: {
                isTdp: 1
            }
        }).done(function(data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                let html = `
                    <h5 class="float-left">Firma digital</h5>
                    <table class="table table-bordered table-hover w-100">
                        <thead class="thead-default">
                            <tr>
                                <th>ROL</th>
                                <th>USUARIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>GERENTE</td>
                                <td>
                                    <select id="cmbUsuarioTdp1" class="select2 cmbUsuarioTdp" data-rol="1" onChange="handleChangeUsuarioTdp(this)">
                                        ${data.listaUsuarioByFirmaDigital}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>JEFE</td>
                                <td>
                                    <select id="cmbUsuarioTdp2" class="select2 cmbUsuarioTdp" data-rol="2" onChange="handleChangeUsuarioTdp(this)">
                                        ${data.listaUsuarioByFirmaDigital}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>SUPERVISOR</td>
                                <td>
                                    <select id="cmbUsuarioTdp4" class="select2 cmbUsuarioTdp" data-rol="4" onChange="handleChangeUsuarioTdp(this)">
                                        ${data.listaUsuarioByFirmaDigital}
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                `;

                $('#contTablaUsuariosTdpByFirma').html(html);
                $(".select2").select2();
                $('#modalAddSubProyecto').modal('toggle');
            } else {
                mostrarNotificacion('error', 'Error', 'Hubo un error al cargar el combo');
            }
        });

    }

    var arrUsuarioTdpByFirma = [];

    function handleChangeUsuarioTdp(element) {
        let value = $("#" + element.id).val();
        let {
            rol,
            idSubProyecto
        } = $(element).data();

        if (arrUsuarioTdpByFirma.length == 0) {
            arrUsuarioTdpByFirma = [{
                    idRol: 1,
                    idUsuario: "",
                    idSubProyecto: ""
                },
                {
                    idRol: 2,
                    idUsuario: "",
                    idSubProyecto: ""
                },
                {
                    idRol: 4,
                    idUsuario: "",
                    idSubProyecto: ""
                }
            ];
        }

        $.each(arrUsuarioTdpByFirma, (i, e) => {
            if (rol == e['idRol']) {
                e["idUsuario"] = value;
                return;
            }

            if (idSubProyecto != undefined) {
                e["idSubProyecto"] = idSubProyecto;
            }
        });
    }

    function addNewActividad() {
        $('#selectActividades').val('').trigger('change');
        $('#formAddActividad').bootstrapValidator('resetForm', true);
        $('#modalAddActividad').modal('toggle');
    }

    function editProyecto(component) {

        var idProyecto = $(component).attr('data-id_pro');

        $.ajax({
            type: 'POST',
            'url': 'getInfoPro',
            data: {
                idProyecto: idProyecto
            },
            'async': false
        }).done(function(data) {
            var data = JSON.parse(data);
            $('#formEditProyecto').bootstrapValidator('resetForm', true);
            $('#selectTipoCentral2').val(data.central).trigger('change');
            $('#selectTipoLabel2').val(data.label).trigger('change');
            $('#selectGerenciaProyecto2').val(data.idGerencia).trigger('change');
            $('#inputDescPro2').val(data.proyecto);
            $('#btnEditPro').attr('data-id', idProyecto);
            $('#modalEditProyecto').modal('toggle'); //abrirl modal
        })

    }

    function editSubProyecto(component) {

        var idSubProyecto = $(component).attr('data-id_spro');

        $.ajax({
            type: 'POST',
            'url': 'pqt_getInfSp',
            data: {
                idSubProyecto: idSubProyecto
            },
            'async': false
        }).done(function(data) {
            var data = JSON.parse(data);

            let infoUsuariosFirma = JSON.parse(data.infoUsuariosFirma);

            if (infoUsuariosFirma.length > 0) {
                let html = `
                            <label>Firma digital</label>
                            <table class="table table-bordered table-hover w-100">
                                <thead class="thead-default">
                                    <tr>
                                        <th>ROL</th>
                                        <th>USUARIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>GERENTE</td>
                                        <td>
                                            <select id="cmbUpdUsuarioTdp1" class="select2" data-rol="1" data-id-sub-proyecto="${idSubProyecto}" onChange="handleChangeUsuarioTdp(this)">
                                                ${data.listaUsuarioByFirmaDigital}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>JEFE</td>
                                        <td>
                                            <select id="cmbUpdUsuarioTdp2" class="select2" data-rol="2" data-id-sub-proyecto="${idSubProyecto}" onChange="handleChangeUsuarioTdp(this)">
                                                ${data.listaUsuarioByFirmaDigital}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>SUPERVISOR</td>
                                        <td>
                                            <select id="cmbUpdUsuarioTdp4" class="select2" data-rol="4" data-id-sub-proyecto="${idSubProyecto}" onChange="handleChangeUsuarioTdp(this)">
                                                ${data.listaUsuarioByFirmaDigital}
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        `;

                $('#contTablaEdicionUsuariosTdpByFirma').html(html);
                $(".select2").select2();

                $.each(infoUsuariosFirma, (i, e) => {

                    switch (parseInt(e['idRol'])) {
                        case 1:
                            $('#cmbUpdUsuarioTdp1').val(e['idUsuario']).trigger('change');
                            break;

                        case 2:
                            $('#cmbUpdUsuarioTdp2').val(e['idUsuario']).trigger('change');
                            break;

                        case 4:
                            $('#cmbUpdUsuarioTdp4').val(e['idUsuario']).trigger('change');
                            break;

                        default:
                            break;
                    }
                });
            } else {
				let html = `
                            <label>Firma digital</label>
                            <table class="table table-bordered table-hover w-100">
                                <thead class="thead-default">
                                    <tr>
                                        <th>ROL</th>
                                        <th>USUARIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>GERENTE</td>
                                        <td>
                                            <select id="cmbUpdUsuarioTdp1" class="select2" data-rol="1" data-id-sub-proyecto="${idSubProyecto}" onChange="handleChangeUsuarioTdp(this)">
                                                ${data.listaUsuarioByFirmaDigital}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>JEFE</td>
                                        <td>
                                            <select id="cmbUpdUsuarioTdp2" class="select2" data-rol="2" data-id-sub-proyecto="${idSubProyecto}" onChange="handleChangeUsuarioTdp(this)">
                                                ${data.listaUsuarioByFirmaDigital}
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>SUPERVISOR</td>
                                        <td>
                                            <select id="cmbUpdUsuarioTdp4" class="select2" data-rol="4" data-id-sub-proyecto="${idSubProyecto}" onChange="handleChangeUsuarioTdp(this)">
                                                ${data.listaUsuarioByFirmaDigital}
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        `;

                $('#contTablaEdicionUsuariosTdpByFirma').html(html);
                $(".select2").select2();
			}

            $('#formEditSubProyecto').bootstrapValidator('resetForm', true);
            $('#selectProyecto3').val(data.proyecto).trigger('change');
            $('#selectTipoPlanta2').val(data.tipoPlanta).trigger('change');
            $('#selectTiempo2').val(data.tiempo).trigger('change');
            $('#selectComplejidad2').html(data.cmbTipoComplejidad);
            $('#selectTipoSubProy2').html(data.cmbTipoSubProyecto);

            $('#selectTipoFactorMedicion2').val(data.tipoFactorMedicion).trigger('change');

            $('#divTipoSubProy2').css('display', 'none');

            $('#tablaFases').html(data.tFase);

            //aprobacion automatica
            var aprobacionAutomaticaOK = data.idAprobacionAutomatica;
            if (aprobacionAutomaticaOK == 1) {
                $('#cbxAprobacionAutomatica2').prop('checked', true);
                $("#lblAprobacion2").css("color", "green");
            } else {
                $('#cbxAprobacionAutomatica2').prop('checked', false);
                $("#lblAprobacion2").css("color", "#707070");
            }

            if (data.flg_opex == 2) {
                $('#cbxOPEXedit').prop('checked', true);
                $('#lblOpexEdit').css("color", "green");
            } else {
                $('#cbxOPEXedit').prop('checked', false);
                $("#lblOpexEdit").css("color", "#707070");
            }

            if (data.flg_reg_item_capex_opex == 1 || data.flg_reg_item_capex_opex == 2) {
                $('#lblManualOpexCapex').css("color", "green");
                $('#cbxRegManualOpexCapex').prop('checked', true);
            } else {
                $('#cbxRegManualOpexCapex').prop('checked', false);
                $("#lblManualOpexCapex").css("color", "#707070");
            }

            if (data.flg_codigounico == 2) {
                $('#cbxCODIGOSITIOedit').prop('checked', true);
                $('#lblCODIGOedit').css("color", "green");
            } else {
                $('#cbxCODIGOSITIOedit').prop('checked', false);
                $("#lblCODIGOedit").css("color", "#707070");
            }

            //Adjudicacion Automatica
            var adjudicacionAutomaticaOK = data.idAdjudicacionAutomatica;
            if (adjudicacionAutomaticaOK == 1) {
                $('#cbxAdjudicacionAutomatica2').prop('checked', true);
                $("#lblAdjudicacion2").css("color", "green");
            } else {
                $('#cbxAdjudicacionAutomatica2').prop('checked', false);
                $("#lblAdjudicacion2").css("color", "#707070");
            }

            //Flag de Paquetizado
            $("#tituloEditSubProyecto").html('EDITAR SUB PROYECTO' + (data.r_paquetizado_fg == 2 ? ' - PAQUETIZADO' : ''))

            var areas = data.areas.split(',');
            $('#selectAreas2').val(areas).trigger('change');
            $('#inputDescSubPro2').val(data.descripcion);
            $('#btnEditSubPro').attr('data-id', idSubProyecto);
            $('#btnEditSubPro').attr('data-oldSubpro', data.descripcion);
            $('#btnEditSubPro').attr('data-oldTipoPlanta', data.tipoPlanta);
            $('#modalEditSubProyecto').modal('toggle'); //abrirl modal
        })

    }


    function deletesubPep(component) {
        swal({
            title: 'Estï¿½ seguro de eliminar el Subproyecto - pep ?',
            text: 'Asegurese de validar la informaciï¿½n seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function() {

            var id_subpep = $(component).attr('data-id_ps');
            var subProy = $.trim($('#selectSubProy').val());
            console.log("id_subpep:" + id_subpep);

            $.ajax({
                    type: 'POST',
                    url: "delSubPep",
                    data: {
                        'id_subpep': id_subpep,
                        'subProy': subProy
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    console.log(data);
                    if (data.error == 0) {
                        $('#contTabla').html(data.tablaSubProyPep);
                        console.log(data.tablaSubProyPep);
                        initDataTable('#data-table');
                        mostrarNotificacion('success', 'Registro', data.msj);

                    } else if (data.error == 1) {

                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function() {

                });

        });


    }

    function editSubProyectoActividad(component) {

        var idSubProyectoAct = $(component).attr('data-id_sproact');

        $.ajax({
            type: 'POST',
            'url': 'getInfSpAct',
            data: {
                idSubProyectoAct: idSubProyectoAct
            },
            'async': false
        }).done(function(data) {
            var data = JSON.parse(data);
            $('#formEditSubProyectoAct').bootstrapValidator('resetForm', true);
            $('#selectSubProyecto2').val(data.idSubProyecto).trigger('change');
            $('#selectActividad').val(data.idActividad).trigger('change');
            $('#btnEditSubProAct').attr('data-id', idSubProyectoAct);
            $('#modalEditActividad').modal('toggle'); //abrirl modal
        })

    }

    function getInforSubProyectoPorProyecto(component) {

        var idProyecto = $(component).attr('data-id_pro');
        console.log('proyecto:' + idProyecto);
        $.ajax({
            type: 'POST',
            'url': 'pqt_getInfoSubproyectosPorProyecto',
            data: {
                idProyecto: idProyecto
            },
            'async': false
        }).done(function(data) {
            var data = JSON.parse(data);
            $('#divInfoSubProyectoFases').html(data.tablaFases);
            $('#modalInfoSubproyectosPorProyecto').modal('toggle'); //abrirl modal
        })

    }

    function revalidatePep() {
        if ($('#selectPep').val() != null) {
            $('#formAddSubPep').bootstrapValidator('revalidateField', 'selectPep');
        }
    }


    function addNewSubPep() {
        $('#selectSubProy2').val('').trigger('change');
        $('#selectArea').val('').trigger('change');
        $('#selectPep').val('').trigger('change');
        $('#formAddSubPep').bootstrapValidator('resetForm', true);
        $('#modalAddSubPep').modal('toggle');
    }


    function filtrarTabla() {
        var subProy = $.trim($('#selectSubProy').val());
        $.ajax({
                type: 'POST',
                'url': 'getPepData',
                data: {
                    subProy: subProy
                },
                'async': false
            })
            .done(function(data) {
                var data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contTabla').html(data.tablaSubProyPep)
                    initDataTable('#data-table');

                } else if (data.error == 1) {

                    mostrarNotificacion('error', 'Hubo problemas al filtrar los datos!');
                }
            });
    }

    function existePepSub(subpro, area, pep) {
        var result = $.ajax({
            type: "POST",
            'url': 'valiPepsub',
            data: {
                'subpro': subpro,
                'pep': pep,
                'area': area
            },
            'async': false
        }).responseText;
        return result;
    }

    function deletePep1Pep2(component) {
        swal({
            title: 'Estï¿½ seguro de eliminar Pep1 - Pep2 ?',
            text: 'Asegurese de validar la informaciï¿½n seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function() {

            var id_pp = $(component).attr('data-id_pp');

            $.ajax({
                    type: 'POST',
                    url: "delPepPep",
                    data: {
                        'id_pp': id_pp
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    console.log(data);
                    if (data.error == 0) {
                        $('#contTablaPep1Pep2').html(data.tbPep1Pep2);
                        initDataTable('#data-table4');
                        mostrarNotificacion('success', 'Registro', data.msj);

                    } else if (data.error == 1) {

                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function() {

                });

        });


    }


    function delSisePep2Grafo(component) {
        swal({
            title: 'Estï¿½ seguro de eliminar Sisego - Pep2 - Grafo?',
            text: 'Asegurese de validar la informaciï¿½n seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function() {

            var id_pp = $(component).attr('data-id_pp');

            $.ajax({
                    type: 'POST',
                    url: "delSP2G",
                    data: {
                        'id_pp': id_pp
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    console.log(data);
                    if (data.error == 0) {
                        $('#contTablaSisegoGrafo').html(data.tbSisegoPepGrafo);
                        initDataTable('#data-table5');
                        mostrarNotificacion('success', 'Registro', data.msj);

                    } else if (data.error == 1) {

                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function() {

                });

        });


    }

    function delItemPep2Grafo(component) {
        swal({
            title: 'Estï¿½ seguro de eliminar Itemplan - Pep2 - Grafo?',
            text: 'Asegurese de validar la informaciï¿½n seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function() {

            var id_pp = $(component).attr('data-id_pp');

            $.ajax({
                    type: 'POST',
                    url: "delIP2G",
                    data: {
                        'id_pp': id_pp
                    },
                    'async': false
                })
                .done(function(data) {
                    var data = JSON.parse(data);
                    console.log(data);
                    if (data.error == 0) {
                        $('#contTablaItemGrafo').html(data.tbItemPepGrafo);
                        initDataTable('#data-table6');
                        mostrarNotificacion('success', 'Registro', data.msj);

                    } else if (data.error == 1) {

                        mostrarNotificacion('error', 'Error', data.msj);
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function() {

                });

        });


    }

    function existeDescripcionProyecto(proyecto) {
        var result = $.ajax({
            type: "POST",
            'url': 'valNomProyecto',
            data: {
                'proyecto': proyecto
            },
            'async': false
        }).responseText;
        return result;
    }

    function mostrarTipoSubProy(selectProy, divTipoSubproy, selectTipoSubProy) {

        var idProyecto = $('#' + selectProy).val();

        if (idProyecto == 21) { // CV
            $('#' + divTipoSubproy).css('display', 'block');
        } else {
            $('#' + divTipoSubproy).css('display', 'none');
            $('#' + selectTipoSubProy).val(null).trigger('change');
        }

    }

    function cambiarColorLabelCbxEvt(inputId, cbx) {
        var checkedValue = $("#" + cbx + ":checked").val();

        if (checkedValue) {
            $("#" + inputId).css("color", "green");
        } else {
            $("#" + inputId).css("color", "#707070");
        }

    }


    function tableToJson(table) {
        var data = [];

        // first row needs to be headers
        var headers = [];
        for (var i = 0; i < table.rows[0].cells.length; i++) {
            headers[i] = table.rows[0].cells[i].innerHTML.toLowerCase().replace(/ /gi, '');
            console.log("headers[" + i + "]" + headers[i]);
        }

        // go through cells
        for (var i = 1; i < table.rows.length; i++) {

            var tableRow = table.rows[i];
            var rowData = {};

            for (var j = 0; j < 2; j++) {

                rowData[headers[j]] = tableRow.cells[j].innerHTML;
                console.log(rowData[headers[j]]);

            }

            data.push(rowData);
        }

        return data;
    }
	 
	function getDataTabla(idTable) {
		
		var table = document.getElementById(idTable);
        var data = [];
		
		var headers = [];
		for (var i = 0; i < table.rows[0].cells.length; i++) {
            headers[i] = table.rows[0].cells[i].innerHTML.toLowerCase().replace(/ /gi, '');
            //console.log("headers[" + i + "]" + headers[i]);
        }
		
		for (var i = 1; i < table.rows.length; i++) {

            var tableRow = table.rows[i];
            var rowData = {};
			
			console.log('tableRow:',tableRow);

            for (var j = 0; j < 2; j++) {
				console.log("headers[" + j + "]" + headers[j]);
				console.log(tableRow.cells[j].innerHTML);
				rowData[headers[j]] = tableRow.cells[j].innerHTML;
                //console.log(rowData[headers[j]]);

            }

            data.push(rowData);
        }
		
		let newArray = data.filter(function (el) {
			return (el.fase).length == 4;
		}
		);
		

        return newArray;
    }

	function prueba(){
		console.log('raaaaa');
		// var year = $("#tFases").find("tr:last").find("td:first-child").text();
		if (year == null || year == '') {
			//year = new Date().getFullYear();
			var fecha = new Date();
			var ano = fecha.getFullYear();
			year = ano;
		} else {
			year++;
		}
		
		$('#create-fase').attr('disabled', true);
	}
	
	var isNewLineToggled = false;

	
	function deleteFase(component) {
		$(component).closest('tr').remove();
		$('#create-fase').attr('disabled', false);
	}
	
	
	function rowClick(component) {
		isNewLineToggled = !isNewLineToggled;
		var col_index = $(component).index();
		//console.log('index ' + col_index);
	}

	function editarFase(component) {
		var html = $(component).closest('tr').find("td:nth-child(2)").text();
		console.log("html " + html);
		if (html == null || html == '') {
			//esta como input

			var dato = $(component).closest('tr').find("td:nth-child(2) input").val();

			if (dato == '' || dato == 0 || dato < 0) {
				alert('Debe ingresar un numero valido.');
				return false;
			}

			//console.log("dato " + dato);
			$(component).closest('tr').find("td:nth-child(2) input").empty();
			$(component).closest('tr').find("td:nth-child(2)").html(dato);
		} else {
			//esta estatico / se quiere modificar
			var input = $('<input type="text" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57"/>');
			input.val(html);
			$(component).closest('tr').find("td:nth-child(2)").html(input);
		}

	}
	

    function abrirUpdFase(component) {
        var idSubProyecto = $(component).attr('data-idSubProyecto');
        var fase = $(component).attr('data-fase');
        var cantItemPlan = $(component).attr('data-cantItemPlan');
        console.log('idSubProyecto:' + idSubProyecto);
        console.log('fase:' + fase);
        console.log('cantItemPlan:' + cantItemPlan);
        $("#h5modalEditFase").text("EDITAR CANTIDAD PLANIFICADA FASE " + fase);

        $('#btnEditarFase').attr('data-idSubProyecto', idSubProyecto);
        $('#btnEditarFase').attr('data-fase', fase);

        $("#txtNuevaCantidad").val("");
        $("#txtActualCantidad").val(cantItemPlan);
        $('#modalEditFase').modal('toggle');
    }

    $('#formEditFase')
        .bootstrapValidator({
            container: '#mensajeForm11',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            excluded: ':disabled',
            fields: {
                txtNuevaCantidad: {
                    validators: {
                        notEmpty: {
                            message: '<p style="color:red">(*) Debe digitar una cantidad.</p>'
                        }
                    }
                }
            }
        }).on('success.form.bv', function(e) {
            e.preventDefault();

            var $form = $(e.target),
                formData = new FormData(),
                params = $form.serializeArray(),
                bv = $form.data('bootstrapValidator');
            $.each(params, function(i, val) {
                formData.append(val.name, val.value);
            });
            formData.append('idSubProyecto', $('#btnEditarFase').attr('data-idSubProyecto'));
            formData.append('fase', $('#btnEditarFase').attr('data-fase'));

            $.ajax({
                    data: formData,
                    url: "pqt_upd_cant_fase_subp",
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST'
                })
                .done(function(data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#tablaFases').html(data.tFase);
                        $('#modalEditFase').modal('toggle');
                        mostrarNotificacion('success', 'Operacion exitosa.', 'Se registro correcamente!');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                })
                .always(function() {

                });

        });
</script>

<script type="text/javascript">
    $(function() {
        var isNewLineToggled = false;

        /*$("#create-fase").button().on("click", function() {
			console.log("ETNRO1");
            var year = $("#tFases").find("tr:last").find("td:first-child").text();
            if (year == null || year == '') {
                //year = new Date().getFullYear();
                year = 2021;
            } else {
                year++;
            }
            //console.log("year: " + year );

            $("#tFases tbody").append("<tr>" +
                "<td><label>" + year + "</label></td>" +
                "<td><input value='' onkeypress='return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57'/></td>" +
                "<td><input type='button' value='AGREGAR' class='add' /></td>" +
                "<td><input type='button' value='BORRAR' class='btnDelete' /></td>" +
                "</tr>");
            $('#create-fase').attr('disabled', true);
        });*/


    });
	
    var idSubProyectoPlaniGlb = null;
    var idFasePlaniGlb = null;
    var cantidadTotalPlanGlb = null;
    var cantidadPlanGlb = null;

    function openModalPlan(btn) {
        idFasePlaniGlb = btn.data('id_fase');
        idSubProyectoPlaniGlb = btn.data('id_subproyecto');
        cantidadPlanGlb = btn.data('cantidad_plan');
        $('#nomPlan').val('');
        $('#cantidadPlan').val('');
        $.ajax({
            type: 'POST',
            url: 'getDataPlanificacion',
            data: {
                idFase: idFasePlaniGlb,
                idSubProyecto: idSubProyectoPlaniGlb
            }
        }).done(function(data) {
            data = JSON.parse(data);
            $('#selectMes').html(data.cmbMes);
            $('#contTablaPlanifica').html(data.tbPlanifica);
            cantidadTotalPlanGlb = data.planTotal;
            console.log(cantidadTotalPlanGlb);
            modal('modalDetallePlan');
        });
    }

    function insertPlanifica() {
        var nomPlan = $('#nomPlan').val();
        var cantidad = $('#cantidadPlan').val();
        var idMes = $('#selectMes option:selected').val();

        if (nomPlan == null || nomPlan == '') {
            return;
        }
        if (idMes == null || idMes == '') {
            return;
        }
        if (cantidad == null || cantidad == '') {
            return;
        }

        cantidadTotal = Number(cantidadTotalPlanGlb) + Number(cantidad);
        console.log(cantidadTotal);
        if (cantidadTotal > cantidadPlanGlb) {
            mostrarNotificacion('error', 'Error', 'El total planificado es : ' + cantidadPlanGlb + ' No puede pasar esta cantidad.');
            return;
        }



        $.ajax({
            type: 'POST',
            url: 'insertPlanifica',
            data: {
                nomPlan: nomPlan,
                cantidad: cantidad,
                idMes: idMes,
                idFase: idFasePlaniGlb,
                idSubProyecto: idSubProyectoPlaniGlb
            }
        }).done(function(data) {
            data = JSON.parse(data);
            $('#contTablaPlanifica').html(data.tbPlanifica);
        });
    }
</script>

<script src="<?php echo base_url(); ?>public/js/js_pqt_mantenimiento/jsProyecto.js?v=<?php echo time(); ?>"></script>

</body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->

</html>