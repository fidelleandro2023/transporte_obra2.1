<!DOCTYPE html>
<html lang="en" ng-app="qwer">
    <head>
        <meta charset="ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">

        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bootstrap-3.3.6-dist/css/bootstrap.min.css?v=<?php echo time();?>" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css">

        <!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bootstrap-3.3.6-dist/css/bootstrap.css?v=<?php echo time();?>"> -->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/bootstrap-calendar-master/css/calendar.min.css?v=<?php echo time();?>"/>
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/datetimepicker/css/bootstrap-material-datetimepicker.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/b_select/css/bootstrap-select.min.css?v=<?php echo time();?>">
        <!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/handsontable/handsontable.full.min.css"> -->
        <!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/mdl/css/material.indigo.min.css?v=<?php echo time();?>"> -->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/mdl/css/material.min.css?v=<?php echo time();?>">
        <!-- <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/floating-button/src/mfb.css?v=<?php echo time();?>"> -->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/offline-master/themes/offline-theme-chrome.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/offline-master/themes/offline-language-spanish.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/node_modules/angular-material/angular-material.min.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/fonts/material-icons.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/fonts/Material-icons-new.css?v=<?php echo time();?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/fonts/font-awesome.min.css?v=<?php echo time();?>">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>public/plugins/wizard/css/wizard.css?v=<?php echo time();?>" >
        <style>
            .select2-dropdown {
                z-index: 100000;
            }
            .fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }
            input[type=number] { -moz-appearance:textfield; }
        </style>
	</head>
	<body data-ma-theme="entel">	
        <main class="main">
            <div class="page-loader">
                <svg x="0" y="0" width="258" height="258">
                    <g clip-path="url(#clip-path)">
                        <path class="tree" id="g" />
                    </g>
        
                    <clipPath id="clip-path">  
                        <path id="path" class="circle-mask"/>
                    </clipPath>   
                </svg>
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
                <a href="https://www.movistar.com.pe/" title="Movistar"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Movistar" style="width: 36%; margin-left: -51%"></a>
                </div>

                <?php include('application/views/v_opciones.php'); ?>
            </header>
            <aside class="sidebar sidebar--hidden">
                <div class="scrollbar-inner">
                    <div class="user">
                        <div class="user__info" data-toggle="dropdown">
                            <img class="user__img" src="<?php echo base_url();?>public/demo/img/profile-pics/8.jpg" alt="">
                            <div>
                                <div class="user__name"><?php echo $this->session->userdata('usernameSession')?></div>
                                <div class="user__email"><?php echo $this->session->userdata('descPerfilSession')?></div>
                            </div>
                        </div>
                    </div>
                    <ul class="navigation">
					    <?php echo $opciones?>
                    </ul>
                </div>
            </aside>

            <section class="content content--full">
                <div class="content__inner">
                    <h3>ACELERACI&Oacute;N SISEGO</h3>
                    <h3>Itemplan : <?= $itemplan ?></h3>
                    <div class="card">
                        <div class="card-block">
                            <div class="mdl-wizard"  id="cont_form_colab"> 
                            <div class="form-wizard form-wizard-horizontal m-b-15 form-group" id="rootwizard1">
                                    <div class="form-wizard-nav">
                                        <div class="progress container">
                                            <div class="progress-bar progress-bar-primary" Id ="progressBar"></div>
                                        </div>
                                        <ul class="nav nav-justified nav-pills">
                                            <li class="active tab1 wizard-aux-class" id="li1">
                                                <a data-toggle="tab" aria-expanded="true" href="#tab1" id="step1" onclick="nextStep(1)">
                                                    <span class="step form-group"></span>
                                                    <span id="tileAsigFacil" class="title">Asignaci&oacute;n Facilidades <br><?= isset($dataPiloto['duracionFluidUno']) ? $dataPiloto['duracionFluidUno'] : '00:00:00' ?></span>
                                                </a>
                                            </li>
                                            <li class ="tab2 wizard-aux-class" style="" id="li2">
                                                <a data-toggle="tab" aria-expanded="false" href="#tab2" class ="my-link-par" id="step2" onclick="nextStep(2)">
                                                    <span class="step"></span>
                                                    <span class="title">Agenda Replanteo</span>
                                                </a>
                                            </li>
                                            <li class ="tab3 wizard-aux-class" id="li3">
                                                <a data-toggle="tab" aria-expanded="false" href="#tab3" class ="my-link-par" id="step3" onclick="nextStep(3)">
                                                    <span class="step form-group"></span>
                                                    <span id="tileReplanteo" class="title">Replanteo <br><?= isset($dataPiloto['duracionFluidTres']) ? $dataPiloto['duracionFluidTres'] : '00:00:00' ?></span>
                                                </a>
                                            </li>
                                            <li class="tab4 wizard-aux-class" id="li4">
                                                <a data-toggle="tab" aria-expanded="true" href="#tab4" id="step4" onclick="nextStep(4)">
                                                    <span class="step form-group"></span>
                                                    <span id="titleElabFuit" class="title">Elaboraci&oacute;n FUIT <br><?= isset($dataPiloto['duracionFluidCuatro']) ? $dataPiloto['duracionFluidCuatro'] : '00:00:00' ?></span>
                                                </a>
                                            </li>
                                            <li class ="tab5 wizard-aux-class" id="li5">
                                                <a data-toggle="tab" aria-expanded="false" href="#tab5" class ="my-link-par" id="step5" onclick="nextStep(5)">
                                                    <span class="step form-group"></span>
                                                    <span id="titleEntFuit" class="title" >Entrega FUIT <br><?= isset($dataPiloto['duracionFluidCinco']) ? $dataPiloto['duracionFluidCinco'] : '00:00:00' ?></span>
                                                </a>
                                            </li>
                                            <li class ="tab6 wizard-aux-class" id="li6" style="">
                                                <a data-toggle="tab" aria-expanded="false" href="#tab6" class ="my-link-par" id="step6" onclick="nextStep(6)">
                                                    <span class="step"></span>
                                                    <span class="title">Agenda - Instalaci&oacute;n</span>
                                                </a>
                                            </li>
                                            <li class ="tab7 wizard-aux-class" id="li7">
                                                <a data-toggle="tab" aria-expanded="false" href="#tab7" class ="my-link-par" id="step7" onclick="nextStep(7)">
                                                    <span class="step form-group"></span>
                                                    <span id="titleInsPex" class="title">Instalaci&oacute;n PEX <br><?= isset($dataPiloto['duracionFluidSiete']) ? $dataPiloto['duracionFluidSiete'] : '00:00:00' ?></span>
                                                </a>
                                            </li>
                                            <li class ="tab8 wizard-aux-class" id="li8">
                                                <a data-toggle="tab" aria-expanded="false" href="#tab8" class ="my-link-par" id="step8" onclick="nextStep(8)">
                                                    <span class="step"></span>
                                                    <span class="title">Material</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane pane-par tabb1 active container" id="tab1" > 
                                        <div class="form-group col-md-6">
                                            <label>Motivo</label>
                                            <select id="cmbMotivoAsigFacilidad" class="select2">
                                                <?= isset($comboMotivo) ? $comboMotivo : null ?>
                                            </select>
                                            <div id="validaMotivoUno"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Placas: </label>
                                            <select id="idCmbPlaca" name="idCmbEnt" class="select2 form-control col-md-12">
                                                <option value="">Seleccionar Placa</option>
                                                <?= isset($cmbPlacas) ? $cmbPlacas : NULL; ?>
                                            </select>
                                            <div id="validaCmbPlaca"></div>
                                        </div> 
                                        <div class="form-group col-md-12">
                                            <label>Comentario</label>
                                            <textarea id="comentarioFluiUno" class="form-control"><?= isset($dataPiloto['comentario_asig_facil']) ? $dataPiloto['comentario_asig_facil'] : null ?></textarea>
                                            <div id="validaComentario"></div>
                                        </div>
                                        <div>
                                            <button id="btnFluidUno" class="btn btn-success" onclick="registrarFluidUno();">Aceptar</button>
                                        </div>
                                        <div id="contTablaBitacoraAsigFacilidad">
                                            <?= isset($tablaBitacoraAsigFacilidad) ? $tablaBitacoraAsigFacilidad : null ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane pane-par tabb2" id="tab2">
                                        <!-- <div class="container form-group">
                                            <div class="form-group col-md-6">
                                                <label>Fecha Cita</label>
                                                <input id="fechaCitaFluiDos" type="date" class="form-control" value="<?= isset($dataPiloto['fecha_cita_agen_replanteo']) ? $dataPiloto['fecha_cita_agen_replanteo'] : null ?>" />
                                                <div id="fechaCitaDos"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Comentario</label>
                                                <textarea id="comentarioFluiDos" class="form-control"><?= isset($dataPiloto['comentario_agen_replanteo']) ? $dataPiloto['comentario_agen_replanteo'] : null ?></textarea>
                                                <div id="validaComentarioDos"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <button id="btnFluidDos" class="btn btn-success" onclick="registrarFluidDos();">Aceptar</button>
                                        </div> -->
                                        <div>
                                            <div class="container">
                                                <!-- <div class="tab-container">
                                                    <ul class="nav nav-tabs nav-fill" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab"  onclick="getTablaItemplan($(this));" role="tab">consulta</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab"  onclick="getTablaItemplan($(this));" role="tab">calendario</a>                                                        
                                                        </li>
                                                    </u>    
                                                </div>
                                                <div class="tab-content">
                                                </div>   -->
                                                <div class="form-group">
                                                    <h4 style="margin: auto;font-weight: bold;" class="modal-title">SE REQUIERE MOTIVO AGENDA</h4>
                                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect opacity" data-toggle="tooltip" data-original-title="Agendar" data-placement="bottom" style="cursor:pointer" onclick="openAgendamiento(1);">
                                                        <i class="fa fa-calendar" ></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label>Comentario</label>
                                                    <textarea id="comentarioFluiDos" class="form-control"><?= isset($dataPiloto['comentario_agen_replanteo']) ? $dataPiloto['comentario_agen_replanteo'] : null ?></textarea>
                                                    <div id="validaComentarioDos"></div>
                                                </div>    
                                                <div>
                                                    <button id="btnFluidDos" class="btn btn-success" onclick="registrarFluidDos();">Aceptar</button>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="tab-pane pane-par tabb3" id="tab3">
                                        <div class="container">
                                            <div class="form-group col-md-6">
                                                <label>Motivo</label>
                                                <select id="cmbMotivoReplanteo" class="select2">
                                                    <?= isset($comboMotivo) ? $comboMotivo : null ?>
                                                </select>
                                                <div id="validaMotivoTres"></div>
                                            </div>    
                                            <div class="form-group col-md-6">
                                                <label>Comentario</label>
                                                <textarea id="comentarioFluiTres" class="form-control"></textarea>
                                                <div id="validaComentarioTres"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button id="btnFluidTres" class="btn btn-success" onclick="registrarFluidTres();">Aceptar</button>
                                        </div>
                                        <div id="contTablaBitacoraReplanteo">
                                            <?= isset($tablaBitacoraReplanteo) ? $tablaBitacoraReplanteo : null ?>
                                        </div>
                                    </div>
                                    <div class="tab-pane pane-par tabb4" id="tab4">
                                        <form method="post" id="import_form" enctype="multipart/form-data">
                                            <div class="container">
                                                <table  class="table table-bordered">
                                                    <thead>
                                                        <th>Motivo</th>
                                                        <th>Comentario</th>
                                                        <th>Subir Archivo</th>
                                                        <th>Descargar Archivo</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="form-group col-md-12">
                                                                    <label>Motivo</label>
                                                                    <select id="cmbMotivoElabFuit" class="select2">
                                                                        <?= isset($comboMotivo) ? $comboMotivo : null ?>
                                                                    </select>
                                                                    <div id="validaMotivoCuatro"></div>
                                                                </div>       
                                                            </td>
                                                            <td>
                                                                <div class="form-group col-md-12">
                                                                    <textArea id="comentarioFluiCuatro" class="form-control"><?= isset($dataPiloto['comentario_elaboracion_fuit']) ? $dataPiloto['comentario_elaboracion_fuit'] : null ?></textArea>
                                                                    <div id="validaComentarioCuatro"></div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group col-md-6">
                                                                    <input type="file" name="file" id="file" accept=".pdf"/>
                                                                </div>
                                                                <div id="validaFileFluidCuatro"></div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group col-md-12">
                                                                    <button class="btn btn-success" onclick="descargarArchivoFuit('<?= isset($dataPiloto['ubic_archivo_elaboracion_fuit']) ? utf8_decode($dataPiloto['ubic_archivo_elaboracion_fuit']) : null ?>')">Descargar</button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table> 
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" id="btnFluidCuatro" class="btn btn-success">Aceptar</button>
                                            </div>
                                            <div id="contTablaBitacoraElabFuit">
                                                <?= isset($tablaBitacoraElaboracionFuit) ? $tablaBitacoraElaboracionFuit : null ?>
                                            </div>
                                        </form>
                                    </div> 
                                    <div class="tab-pane pane-par tabb5" id="tab5">
                                        <div class="container">
                                            <div class="form-group col-md-6">
                                                <label>Motivo</label>
                                                <select id="cmbMotivoEntFuit" class="select2">
                                                    <?= isset($comboMotivo) ? $comboMotivo : null; ?>
                                                </select>
                                                <div id="validaMotivoCinco"></div>
                                            </div>       
                                            <div class="form-group col-md-6">
                                                <label>Comentario</label>
                                                <textarea id="comentarioFluiCinco" class="form-control"><?= isset($dataPiloto['comentario_entrega_fuit']) ? $dataPiloto['comentario_entrega_fuit'] : null ?></textarea>
                                                <div id="validaComentarioCinco">
                                                </div>
                                            </div>
                                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">LISTA DE ENTIDADES </h4>
                                            <div class="text-left form-group">
                                                <button type="button" id="btnAbrirModalRegEnt" class="btn btn-success"  onclick="abrirModalRegisEnt()">Entidades</button>
                                            </div>
                                            <div class="form-group" id="contTablaEnt">
                                                <?= isset($tablaEntidades) ? $tablaEntidades : null; ?>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-success" id="btnFluidCinco" onclick="registrarFluidCinco();">Aceptar</button>
                                            </div>
                                            <div id="contTablaBitacoraEntregaFuit">
                                                <?= isset($tablaBitacoraEntregaFuit) ? $tablaBitacoraEntregaFuit : null; ?>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="tab-pane pane-par tabb3 form-group" id="tab6">
                                        <div class="container">
                                            <div class="form-group">
                                                <h4 style="margin: auto;font-weight: bold;" class="modal-title">SE REQUIERE MOTIVO AGENDA</h4>
                                                <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect opacity" data-toggle="tooltip" data-original-title="Agendar" data-placement="bottom" style="cursor:pointer" onclick="openAgendamiento(2);">
                                                    <i class="fa fa-calendar" ></i>
                                                </button>
                                            </div>
                                            <div class="col-md-11 form-group">
                                                <label>Comentario</label>
                                                <textarea id="comentarioFluidSeis" class="form-control"><?= isset($dataPiloto['comentario_agen_instalacion']) ? $dataPiloto['comentario_agen_instalacion'] : null ?></textarea>
                                                <div id="validaComentarioSeis"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <button id="btnFluidSeis" class="btn btn-success" onclick="registrarFluidSeis();">Aceptar</button>
                                        </div>
                                    </div> 
                                    <div class="tab-pane pane-par tabb3" id="tab7">
                                        <div class="container">
                                            <div class="form-group col-md-6">
                                                <label>Motivo</label>
                                                <select id="cmbMotivInsPex" class="select2">
                                                    <?= isset($comboMotivo) ? $comboMotivo : null; ?>
                                                </select>
                                                <div id="validaMotivoSiete"></div>
                                            </div>      
                                            <div class="form-group col-md-6">
                                                <label>Comentario</label>
                                                <textarea id="comentarioFluiSiete" class="form-control"><?= isset($dataPiloto['comentario_instalacion_pex']) ? $dataPiloto['comentario_instalacion_pex'] : null ?></textarea>
                                                <div id="validaComentarioSiete"></div>
                                            </div>  
                                        </div>
                                        <div class="form-group">
                                            <button id="btnFluidSiete" class="btn btn-success" onclick="registrarFluidSiete();">Aceptar</button>
                                        </div>
                                        <div id="contTablaBitacoraInstalacionPex">
                                            <?= isset($tablaBitacoraInsPex) ? $tablaBitacoraInsPex : null ?>
                                        </div>
                                    </div>  
                                    <div class="tab-pane pane-par tabb8" id="tab8">
                                        <div class="container">
                                            <div class="form-group">
                                                <h5 class="card-title" id="totalMat"></h5>   
                                            </div>
                                              <?= isset($tablaKitMaterial) ? $tablaKitMaterial : NULL; ?>
                                        </div>    
                                        <div>
                                            <button id="btnFluidOcho" class="btn btn-success" onclick="insertInfoPOPiloto();">Aceptar</button>
                                        </div>
                                    </div>  
                                </div>	              
                            </div>
                            <div class="mdl-card__actions">
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalRegistrarEnt"  tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title">A&Ntilde;ADIR ENTIDADES</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="panel-group col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" style="font-weight: bold;color: black;">Asignar Entidades</div>
                                            <div class="panel-body">
                                                <div class="col-sm-12 col-md-12 form-inline" id="formEntidades">
                                                    <div class="row">
                                                        <div class="col-sm-12 form-group" id="contEntidades">
                                                            <label>Entidades: </label>
                                                            <select id="idCmbEnt" name="idCmbEnt" class="select2 form-control col-md-12">

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><br>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-12" style="text-align: right;">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button  type="submit" id="btnAceptarEnt"  class="btn btn-success" onclick="registrarEntidades()">Aceptar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalSubirEvidencia" tabindex="1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tituloModal" style="font-weight: bold;">EL NOMBRE DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
                        </div>
                        <div class="modal-body">
                            <div>
                            <div id="dzDetalleItem" class="dropzone" >

                            </div>
                            <hr style="border:1;">

                            </div>
                            </div>
                        <div class="modal-footer">
                            <button id="btnClose" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>
                
                <!-- MODAL DONDE REGISTRAMOS COMPROBANTES-->
                <div class="modal fade"  role="dialog" id="modalComprobantes" data-backdrop="static" data-keyboard="false" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">COMPROBANTE</h4>
                            </div>    
                            <div id="content" class="modal-body">
                                <form id="formRegistrarComprobante" method="post">
                                    <div id="contTablaCompro">
                                    
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
                <div class="modal fade" id="modalSubirFotoComprobante" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tituloModalComproEvi" style="font-weight: bold;">EL NO DEL ARCHIVO NO DEBE TENER CARACTERES ESPECIALES</h5>
                        </div>
                        <div class="modal-body">
                        <div class="col-6">
                            <div id="dzDetalleComprobante" class="dropzone" >

                            </div>
                            <hr style="border:1;">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button  type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade backModal" id="modalAvances" tabindex="-1" role="dialog" aria-labelledby="simpleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="container">
                            <div class="mdl-card__title" id="mdl-title">
                                <h2 class="mdl-card__title-text" id="titleCard" data-toggle="tooltip"  data-placement="bottom">Agendamiento:</h2>
                                <h3 class="mdl-card__title-text" id="fechaAgenda"></h3>
                            </div>
                            <div  id="contAgendamientoDetalle" class="table-responsive">
                                    
                            </div>

                            <div class="mdl-card__actions">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>     
                </div>   
            </section>   
        </main>
        
        <script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/tether/dist/js/tether.min.js"></script>
        
        <script src="<?php echo base_url(); ?>public/js/libs/bootstrap/bootstrap.min.js?v=<?php echo time();?>"></script>
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
        <script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/salvattore/dist/salvattore.min.js"></script>
        <script src="<?php echo base_url(); ?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/moment/min/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

        <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time(); ?>"></script>
            
        <!--  tables -->
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/curved-line.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/line.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/flot-charts/chart-tooltips.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url(); ?>public/demo/js/jqvmap.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

        <!--  -->
        <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>

        <script src="<?php echo base_url(); ?>public/plugins/wizard/js/jquery.bootstrap.wizard.min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time();?>"></script>   
        <script src="<?php echo base_url();?>public/js/js_planobra/jsProcesoPiloto.js?v=<?php echo time();?>"></script> 

        <script src="<?php echo base_url(); ?>public/plugins/bootstrap-calendar-master/components/underscore/underscore-min.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/bootstrap-calendar-master/js/language/es-ES.js?v=<?php echo time();?>"></script>
        <script src="<?php echo base_url(); ?>public/plugins/bootstrap-calendar-master/js/calendar.js?v=<?php echo time();?>"></script>
        
        <script>
            init();
    
$(document).ready(function() {
    // modal('modalAgendamiento');
    getCalendar();
});

function getCalendar() {
    $.ajax({
        type : 'POST',
        url  : 'getAgendaCalendarProcPiloto'
    }).done(function(data){
        try {
            data = JSON.parse(data);
            var options = {
					events_source : data,
					language      : 'es-ES',
					tmpl_path     : "public/plugins/bootstrap-calendar-master/tmpls/",
					onAfterViewLoad : function(view) {
						$('#fechaCalendar').text(this.getTitle());
						$('button.mdl-button').removeClass('active');
                        $('button.mdl-button[data-calendar-view="' + view + '"]').addClass('active');
						// $('li.mdl-menu__item').removeClass('active');
						// $('li.mdl-menu__item[data-calendar-view="' + view + '"]').addClass('active');
					},
                    modal      : "#modalAvances",
                    // modal_type : "ajax",
					ruta_js_metodo : 'public/js/js_planobra/jsProcesoPiloto.js',
					funcion_name : 'getDetalleAgendamiento'
				};
                var calendar = $('#calendar').calendar(options);
				$('button.mdl-button[data-calendar-nav], li.mdl-menu__item[data-calendar-nav]').each(function() {
					var $this = $(this);
					$this.click(function() {
						calendar.navigate($this.data('calendar-nav'));
					});
				});
				$('button[data-calendar-view], li.mdl-menu__item[data-calendar-view]').each(function() {
					var $this = $(this);
					$this.click(function() {
						calendar.view($this.data('calendar-view'));
					});
                });
        } catch(err) {
            location.reload();
        }
    });     
}

        </script> 
    </body>
</html>