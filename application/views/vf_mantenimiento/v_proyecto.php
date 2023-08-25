<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

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
    <!-- App styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/font-awesome/css/font-awesome.css">
    <link rel="stylesheet"
          href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>

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

    <link rel="stylesheet" href="<?php echo base_url(); ?>public/demo/css/demo.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css">
</head>

<div data-ma-theme="entel">
<main class="main">
    <div class="page-loader">
        <div class="page-loader__spinner">
            <svg viewBox="25 25 50 50">
                <circle cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
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
            <a href="https://www.movistar.com.pe/" title="Entel Per�"><img
                        src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
              
            </ul>
        </div>
    </aside>


    <section class="content content--full">
        <div class="content__inner">
            <h2>MANTENIMIENTO PROYECTO - SUBPROYECTO</h2>
            <div class="card">

                <div class="card-block">
                    <div class="tab-container">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#panelSubPro"
                                   role="tab">SUBPROYECTO</a>
                            </li>
                            <!--_____________________________________________________-->
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panelPro" role="tab">PROYECTO</a>
                            </li>
                            <!--_____________________________________________________-->
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#panelSubProActividad" role="tab">ACTIVIDAD
                                    POR SUBPROYECTO</a>
                            </li>
                        </ul>
                        <!--_____________________________________________________-->
                        <!--_____________________________________________________-->
                        <div class="tab-content">
                            <div class="tab-pane active fade show" id="panelSubPro" role="tabpanel">
                                <div class="col-sm-6 col-md-4"
                                     style="TEXT-ALIGN: center;margin-top: 25px;    margin-left: auto;">
                                    <div class="form-group">
                                        <button style="background-color: var(--verde_telefonica)" onclick="addNewSubProyecto();"
                                                type="button"
                                                class="btn btn-success waves-effect">
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
                                <div class="col-sm-6 col-md-4"
                                     style="TEXT-ALIGN: center;margin-top: 25px;    margin-left: auto;">
                                    <div class="form-group">
                                        <button style="background-color: var(--verde_telefonica)" onclick="addNewProyecto();"
                                                type="button" class="btn btn-success waves-effect">
                                            <i class="zmdi zmdi-plus-circle-o zmdi-hc-fw"></i>NUEVO PROYECTO
                                        </button>
                                    </div>
                                </div>

                                <div id="contTablaProyecto" class="table-responsive">
                                    <?php echo $tbProyecto ?>
                                </div>
                            </div>
                            <!--__________________________ACTIVIDAD POR SUBPROYECTO___________________________-->

                            <div class="tab-pane active fade show" id="panelSubProActividad" role="tabpanel">
                                <div class="row">
                                    <div class="form-group col-md-1">
                                        <label for="" >SubProyecto:</label>
                                        <select id="selectSubProyecto" style="background-color: #2580d4; color: white;">
                                            <?php foreach ($listaSubProyecto->result() as $s): ?>
                                                <option value="<?php echo $s->idSubProyecto; ?>"><?php echo $s->subProyectoDesc; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                          <div class="col-sm-6 col-md-4"
                                     style="TEXT-ALIGN: center;margin-top: 25px;    margin-left: auto;">
                                    <div class="form-group">
                                        <button style="background-color: var(--verde_telefonica)" onclick="addNewActividad();"
                                                type="button"
                                                class="btn btn-success waves-effect">
                                            <i class="zmdi zmdi-plus-circle-o zmdi-hc-fw"></i>NUEVO ACTIVIDAD
                                        </button>
                                    </div>
                                </div>
                                </div>
                          

                                <div id="contTablaSubProyectoActividad" class="table-responsive">

                                </div>
                            </div>
                            <!--_____________________________________________________-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

</main>
<!-- Small -->


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
                        <input id="inputDescPro" name="inputDescPro" type="text" class="form-control"><i
                                class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
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
                        <input id="inputDescPro2" name="inputDescPro2" type="text" class="form-control"><i
                                class="form-control-feedback" data-bv-icon-for="inputCorreP" style="display: none;"></i>
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
                                <input id="inputDescSubPro" name="inputDescSubPro" type="text" class="form-control"><i
                                        class="form-control-feedback" data-bv-icon-for="inputCorreP"
                                        style="display: none;"></i>
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
                                <label class="control-label">AREAS</label>
                                <select id="selectAreas" name="selectAreas" class="select2 form-control" multiple>
                                    <option>&nbsp;</option>
                                    <?php foreach ($listaAreas->result() as $row) { ?>
                                        <option value="<?php echo $row->idEstacionArea ?>"><?php echo utf8_decode($row->areaDesc) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <!-- <div class="col-12 form-group">
                                <input type="checkbox" id="chbxFichaTecnica" class="custom-control-input">
                                <label for="chbxFichaTecnica">Usar ficha t&eacute;cnica gen&eacute;rica</label>
                            </div>              -->
                            <div class="form-group">
                                <label class="control-label">DECLARACI&Oacute;N JURADA</label>
                                <select id="selectFicha" name="selectFicha" class="select2 form-control">
                                    <option>&nbsp;</option>
                                    <option value="">Seleccionar</option>
                                    <option value="1">DJ GEN&Eacute;RICA</option>
                                    <option value="2">DJ SISEGO Y M&Oacute;VIL</option>
                                    <option value="3">DJ OBRAS P&Uacute;BLICAS</option>
                                    <option value="4">SIN DJ</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="control-label">COMPLEJIDAD</label>
                                <select id="selectComplejidad" name="selectComplejidad" class="select2 form-control">
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6" id="divTipoSubProy" style="display:none;">
                            <div class="form-group">
                                <label class="control-label">Tipo de SubProyecto</label>
                                <select id="selectTipoSubProy" name="selectTipoSubProy" class="select2 form-control">
                                </select>
                            </div>
                        </div>
            

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
                <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR SUB PROYECTO</h5>
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
                                <input id="inputDescSubPro2" name="inputDescSubPro2" type="text" class="form-control"><i
                                        class="form-control-feedback" data-bv-icon-for="inputCorreP"
                                        style="display: none;"></i>
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
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="control-label">COMPLEJIDAD</label>
                                <select id="selectComplejidad2" name="selectComplejidad2" class="select2 form-control">
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

                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label">AREAS</label>
                                <select id="selectAreas2" name="selectAreas2" class="select2 form-control" multiple>
                                    <option>&nbsp;</option>
                                    <?php foreach ($listaAreas->result() as $row) { ?>
                                        <option value="<?php echo $row->idEstacionArea ?>"><?php echo utf8_decode($row->areaDesc) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
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

<!-- Nueva Actividad -->

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
                                    <?php foreach ($listaActividad->result() as $a): ?>
                                    <option value="<?php echo $a->idActividad; ?>"><?php echo  utf8_decode($a->descripcion); ?></option>
                                    <?php endforeach; ?>
                                </select>
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
</div>
</div>


<!-- EDITAR SUB PROYECTO Actividd-->
<div class="modal fade" id="modalEditActividad">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="margin: auto;">
                <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR SUB PROYECTO</h5>
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
    function marcarCombo(){
        $("#selectFicha").val('');
    }

    $(document).ready(function () {

        initDataTable('#data-table2');
        initDataTable('#data-table4');


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
                                    callback: function(value, validator){
                                            var result = existeDescripcionProyecto(value);
                                             if(result == '1'){//Existe
                                                return false;
                                             }else{
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
                    }
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

            $.ajax({
                data: formData,
                url: "addPro",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
                .done(function (data) {
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
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                })
                .always(function () {

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
                                    callback: function(value, validator){
                                            var result = existeDescripcionProyecto(value);
                                             if(result == '1'){//Existe
                                                return false;
                                             }else{
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
                    }
                }
            }).on('success.form.bv', function (e) {
            e.preventDefault();


            var $form = $(e.target),
                formData = new FormData(),
                params = $form.serializeArray(),
                bv = $form.data('bootstrapValidator');


            var idPro = $('#btnEditPro').attr('data-id');
            formData.append('id', idPro);

            $.each(params, function (i, val) {
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
                .done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contTablaProyecto').html(data.tbProyecto);
                        console.log(data.tbProyecto);
                        initDataTable('#data-table4');
                        $('#modalEditProyecto').modal('toggle');
                        mostrarNotificacion('success', 'Operaci�n �xitosa.', 'Se registro correcamente!');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                })
                .always(function () {

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
                                message: '<p style="color:red">(*) Debe Seleccionar al menos una area.</p>'
                            }
                        }
                    },
                    selectFicha : {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar el tipo de declaracion jurada.</p>'
                            }
                        }
                    }
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
            var valores = $('#selectAreas').val();
            formData.append('estaciones', valores);

            var checkFichaTec = $('#selectFicha option:selected').val();
            var idComplejidad = $('#selectComplejidad option:selected').val();
            var idTipoSubProyecto = $('#selectTipoSubProy option:selected').val();
            formData.append('checkFichaTec', checkFichaTec);
            formData.append('idComplejidad', idComplejidad);
            formData.append('idTipoSubProyecto', idTipoSubProyecto);

            $.ajax({
                data: formData,
                url: "addSubPro",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            })
                .done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contTablaSubProyecto').html(data.tbSubproyecto);
                        initDataTable('#data-table');
                        $('#modalAddSubProyecto').modal('toggle');
                        mostrarNotificacion('success', 'Operaci�n �xitosa.', 'Se registro correcamente!');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                })
                .always(function () {

                });


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
                    },
                    selectAreas2: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Seleccionar al menos una area.</p>'
                            }
                        }
                    }
                }
            }).on('success.form.bv', function (e) {
            e.preventDefault();


            var $form = $(e.target),
                formData = new FormData(),
                params = $form.serializeArray(),
                bv = $form.data('bootstrapValidator');


            var idPro = $('#btnEditSubPro').attr('data-id');
            formData.append('id', idPro);

            var oldSubPro = $('#btnEditSubPro').attr('data-oldSubpro');
            formData.append('oldSubPro', oldSubPro);

            $.each(params, function (i, val) {
                formData.append(val.name, val.value);
            });

            var valores = $('#selectAreas2').val();
            var idComplejidad = $('#selectComplejidad2').val();
            var idTipoSubProyecto = $('#selectTipoSubProy2 option:selected').val();
            formData.append('estaciones', valores);
            formData.append('idComplejidad', idComplejidad);
            formData.append('idTipoSubProyecto', idTipoSubProyecto);

            $.ajax({
                data: formData,
                url: "updSp",
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST'
            }).done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contTablaSubProyecto').html(data.tbSubproyecto);
                    initDataTable('#data-table');
                    $('#modalEditSubProyecto').modal('toggle');
                    mostrarNotificacion('success', 'Operacion Exitosa.', 'Se registro correctamente!');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                }

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
            })
            .always(function () {

            });


        });

        $("#selectSubProyecto").change(function () {
            var idSubProyecto = $(this).val();

            $.ajax({
                data: {'idSubProyecto': idSubProyecto},
                url: "getSubproActividades",
                type: 'POST'
            })
                .done(function (data) {
                    data = JSON.parse(data);

                    $('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                    initDataTable('#data-table7');

                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
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
            }).on('success.form.bv', function (e) {
            e.preventDefault();


            var $form = $(e.target),
                formData = new FormData(),
                params = $form.serializeArray(),
                bv = $form.data('bootstrapValidator');

            $.each(params, function (i, val) {
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
            .done(function (data) {
                data = JSON.parse(data);
                if (data.error == 0) {
                    $('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                    initDataTable('#data-table7');
                    $('#modalAddActividad').modal('toggle');
                    mostrarNotificacion('success', 'Operaci�n �xitosa.', 'Se registro correcamente!');
                } else if (data.error == 1) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
            })
            .always(function () {

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
            }).on('success.form.bv', function (e) {
            e.preventDefault();


            var $form = $(e.target),
                formData = new FormData(),
                params = $form.serializeArray(),
                bv = $form.data('bootstrapValidator');


            var idProAct = $('#btnEditSubProAct').attr('data-id');
            formData.append('id', idProAct);

            $.each(params, function (i, val) {
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
                .done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        $('#contTablaSubProyectoActividad').html(data.tbSubProyectoActividad);
                        initDataTable('#data-table7');
                        $('#modalEditActividad').modal('toggle');
                        mostrarNotificacion('success', 'Operaci�n �xitosa.', 'Se registro correcamente!');
                    } else if (data.error == 1) {
                        mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                })
                .always(function () {

                });


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
        $('#formAddSubProyecto').bootstrapValidator('resetForm', true);
        $.ajax({
            type: 'POST',
            url: 'getCmbComplejidad'
        }).done(function (data) {
            data = JSON.parse(data);
            if (data.error == 0) {
                $('#selectComplejidad').html(data.cmbTipoComplejidad);
                $('#selectTipoSubProy').html(data.cmbTipoSubProyecto);
                $('#divTipoSubProy').css('display','none');
                $('#modalAddSubProyecto').modal('toggle');
            } else {
                mostrarNotificacion('error', 'Error', 'Hubo un error al cargar el combo');
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
        console.log('proyecto:' + idProyecto);
        $.ajax({
            type: 'POST',
            'url': 'getInfoPro',
            data: {idProyecto: idProyecto},
            'async': false
        }).done(function (data) {
            var data = JSON.parse(data);
            $('#formEditProyecto').bootstrapValidator('resetForm', true);
            $('#selectTipoCentral2').val(data.central).trigger('change');
            $('#selectTipoLabel2').val(data.label).trigger('change');
            $('#inputDescPro2').val(data.proyecto);
            $('#btnEditPro').attr('data-id', idProyecto);
            $('#modalEditProyecto').modal('toggle'); //abrirl modal
        })

    }

    function editSubProyecto(component) {

        var idSubProyecto = $(component).attr('data-id_spro');

        $.ajax({
            type: 'POST',
            'url': 'getInfSp',
            data: {idSubProyecto: idSubProyecto},
            'async': false
        }).done(function (data) {
            var data = JSON.parse(data);
            $('#formEditSubProyecto').bootstrapValidator('resetForm', true);
            $('#selectProyecto3').val(data.proyecto).trigger('change');
            $('#selectTipoPlanta2').val(data.tipoPlanta).trigger('change');
            $('#selectTiempo2').val(data.tiempo).trigger('change');
            $('#selectComplejidad2').html(data.cmbTipoComplejidad);
            $('#selectTipoSubProy2').html(data.cmbTipoSubProyecto);
            $('#divTipoSubProy2').css('display','none');

            var areas = data.areas.split(',');
            $('#selectAreas2').val(areas).trigger('change');
            $('#inputDescSubPro2').val(data.descripcion);
            $('#btnEditSubPro').attr('data-id', idSubProyecto);
            $('#btnEditSubPro').attr('data-oldSubpro', data.descripcion);
            $('#modalEditSubProyecto').modal('toggle'); //abrirl modal
        })

    }


    function deletesubPep(component) {
        swal({
            title: 'Est� seguro de eliminar el Subproyecto - pep ?',
            text: 'Asegurese de validar la informaci�n seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function () {

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
                .done(function (data) {
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
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function () {

                });

        });


    }

    function editSubProyectoActividad(component) {

        var idSubProyectoAct = $(component).attr('data-id_sproact');

        $.ajax({
            type: 'POST',
            'url': 'getInfSpAct',
            data: {idSubProyectoAct: idSubProyectoAct},
            'async': false
        }).done(function (data) {
            var data = JSON.parse(data);
            $('#formEditSubProyectoAct').bootstrapValidator('resetForm', true);
            $('#selectSubProyecto2').val(data.idSubProyecto).trigger('change');
            $('#selectActividad').val(data.idActividad).trigger('change');
            $('#btnEditSubProAct').attr('data-id', idSubProyectoAct);
            $('#modalEditActividad').modal('toggle'); //abrirl modal
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
            data: {subProy: subProy},
            'async': false
        })
            .done(function (data) {
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
                'subpro': subpro, 'pep': pep, 'area': area
            },
            'async': false
        }).responseText;
        return result;
    }

    function deletePep1Pep2(component) {
        swal({
            title: 'Est� seguro de eliminar Pep1 - Pep2 ?',
            text: 'Asegurese de validar la informaci�n seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function () {

            var id_pp = $(component).attr('data-id_pp');

            $.ajax({
                type: 'POST',
                url: "delPepPep",
                data: {'id_pp': id_pp},
                'async': false
            })
                .done(function (data) {
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
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function () {

                });

        });


    }


    function delSisePep2Grafo(component) {
        swal({
            title: 'Est� seguro de eliminar Sisego - Pep2 - Grafo?',
            text: 'Asegurese de validar la informaci�n seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function () {

            var id_pp = $(component).attr('data-id_pp');

            $.ajax({
                type: 'POST',
                url: "delSP2G",
                data: {'id_pp': id_pp},
                'async': false
            })
                .done(function (data) {
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
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function () {

                });

        });


    }

    function delItemPep2Grafo(component) {
        swal({
            title: 'Est� seguro de eliminar Itemplan - Pep2 - Grafo?',
            text: 'Asegurese de validar la informaci�n seleccionada!',
            type: 'warning',
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonClass: 'btn btn-secondary'
        }).then(function () {

            var id_pp = $(component).attr('data-id_pp');

            $.ajax({
                type: 'POST',
                url: "delIP2G",
                data: {'id_pp': id_pp},
                'async': false
            })
                .done(function (data) {
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
                .fail(function (jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error', 'Error al insertar', errorThrown + '. Estado: ' + textStatus);
                })
                .always(function () {

                });

        });


    }
    
    function existeDescripcionProyecto(proyecto){
        var result = $.ajax({
            type : "POST",
            'url' : 'valNomProyecto',
            data : {
                'proyecto' : proyecto
            },
            'async' : false
        }).responseText;
        return result;
    }

    function mostrarTipoSubProy(selectProy,divTipoSubproy,selectTipoSubProy){

       var idProyecto = $('#'+selectProy).val();

       if(idProyecto == 21){// CV
            $('#'+divTipoSubproy).css('display','block');
       }else{
            $('#'+divTipoSubproy).css('display','none');
            $('#'+selectTipoSubProy).val(null).trigger('change');
       }

    }


</script>
</body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>