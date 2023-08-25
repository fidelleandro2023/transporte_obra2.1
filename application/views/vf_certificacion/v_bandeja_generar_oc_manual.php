<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->

<head>
    <meta charset="utf-8">
    <meta name="description" content="Server Error">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="msapplication-tap-highlight" content="no">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/app.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/themes/cust-theme-3.css">
    <link id="mytheme" rel="stylesheet" media="screen, print" href="#">
    <link id="myskin" rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/skins/skin-master.css">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>public/hublean/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>public/hublean/img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="<?php echo base_url(); ?>public/hublean/img/favicon/safari-pinned-tab.svg" color="#605D7A">
    <link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/formplugins/select2/select2.bundle.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/formplugins/bootstrap-datepicker/bootstrap-datepicker.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/datagrid/datatables/datatables.bundle.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/loading/jquery.loading.min.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/notifications/sweetalert2/sweetalert2.bundle.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/notifications/toastr/toastr.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/app-general.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" media="screen, print" href="<?php echo base_url(); ?>public/hublean/css/porcentaje_carga.css?v=<?php echo time(); ?>">

</head>

<body class="mod-bg-1 mod-nav-link ">
    <script>
        /**
         *	This script should be placed right after the body tag for fast execution 
         *	Note: the script is written in pure javascript and does not depend on thirdparty library
         **/
        'use strict';

        var classHolder = document.getElementsByTagName("BODY")[0],
            /** 
             * Load from localstorage
             **/
            themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) : {},
            themeURL = themeSettings.themeURL || '',
            themeOptions = themeSettings.themeOptions || '';
        /** 
         * Load theme options
         **/
        if (themeSettings.themeOptions) {
            classHolder.className = themeSettings.themeOptions;
            console.log("%c✔ Theme settings loaded", "color: #148f32");
        } else {
            console.log("%c✔ Heads up! Theme settings is empty or does not exist, loading default settings...", "color: #ed1c24");
        }
        if (themeSettings.themeURL && !document.getElementById('mytheme')) {
            var cssfile = document.createElement('link');
            cssfile.id = 'mytheme';
            cssfile.rel = 'stylesheet';
            cssfile.href = themeURL;
            document.getElementsByTagName('head')[0].appendChild(cssfile);

        } else if (themeSettings.themeURL && document.getElementById('mytheme')) {
            document.getElementById('mytheme').href = themeSettings.themeURL;
        }
        /** 
         * Save to localstorage 
         **/
        var saveSettings = function() {
            themeSettings.themeOptions = String(classHolder.className).split(/[^\w-]+/).filter(function(item) {
                return /^(nav|header|footer|mod|display)-/i.test(item);
            }).join(' ');
            if (document.getElementById('mytheme')) {
                themeSettings.themeURL = document.getElementById('mytheme').getAttribute("href");
            };
            localStorage.setItem('themeSettings', JSON.stringify(themeSettings));
        }
        /** 
         * Reset settings
         **/
        var resetSettings = function() {
            localStorage.setItem("themeSettings", "");
        }
    </script>



		
    <!-- BEGIN Page Wrapper -->
    <div class="page-wrapper">
        <div class="page-inner">
            <!-- BEGIN Left Aside -->
            <aside class="page-sidebar">
                <!-- BEGIN PRIMARY NAVIGATION -->
                <nav id="js-primary-nav" class="primary-nav" role="navigation">
                    <div class="nav-filter">
                        <div class="position-relative">
                            <input type="text" id="nav_filter_input" placeholder="Filter menu" class="form-control" tabindex="0">
                            <a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off" data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
                                <i class="fal fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="info-card">
                        <img src="<?php echo RUTA_HUBLEAN_IMG; ?>logo-telefonica-vertical-blanco.png" class="profile-image rounded-circle">
                        <div class="info-card-text">
                            <a href="#" class="d-flex align-items-center text-white">
                                <span class="text-truncate text-truncate-sm d-inline-block">
                                    <?php echo $this->session->userdata('usernameSession') ?>
                                </span>
                            </a>
                            <span class="d-inline-block text-truncate text-truncate-sm"><?php echo $this->session->userdata('descPerfilSession') ?></span>
                        </div>
                        <img src="<?php echo RUTA_HUBLEAN_IMG; ?>card-backgrounds/cover-2-lg.png" class="cover" alt="cover">
                        <a href="#" onclick="return false;" class="pull-trigger-btn" data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar" data-focus="nav_filter_input">
                            <i class="fal fa-angle-down"></i>
                        </a>
                    </div>
                    <?php echo isset($opciones) ? $opciones : null; ?>
                    <div class="filter-message js-filter-message bg-success-600"></div>
                </nav>
                <!-- END PRIMARY NAVIGATION -->

            </aside>
            <!-- END Left Aside -->
            <div class="page-content-wrapper">
                <!-- BEGIN Page Header -->
                <header class="page-header" role="banner">
                    <!-- we need this logo when user switches to nav-function-top -->
                    <div class="page-logo">
                        <a href="#" class="page-logo-link press-scale-down d-flex align-items-center position-relative" data-toggle="modal" data-target="#modal-shortcut">
                            <img src="<?php echo RUTA_HUBLEAN_IMG; ?>logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
                            <span class="page-logo-text mr-1">SmartAdmin WebApp</span>
                            <span class="position-absolute text-white opacity-50 small pos-top pos-right mr-2 mt-n2"></span>
                            <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
                        </a>
                    </div>
                    <!-- DOC: nav menu layout change shortcut -->
                    <div class="hidden-md-down dropdown-icon-menu position-relative">
                        <a href="#" class="header-btn btn js-waves-off" data-action="toggle" data-class="nav-function-hidden" title="Hide Navigation">
                            <i class="ni ni-menu"></i>
                        </a>
                        <ul>
                            <li>
                                <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-minify" title="Minify Navigation">
                                    <i class="ni ni-minify-nav"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-fixed" title="Lock Navigation">
                                    <i class="ni ni-lock-nav"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- DOC: mobile button appears during mobile width -->
                    <div class="hidden-lg-up">
                        <a href="#" class="header-btn btn press-scale-down" data-action="toggle" data-class="mobile-nav-on">
                            <i class="ni ni-menu"></i>
                        </a>
                    </div>

                    <div class="ml-auto d-flex">
                        <!-- activate app search icon (mobile) 
                            <div class="hidden-sm-up">
                                <a href="#" class="header-icon" data-action="toggle" data-class="mobile-search-on" data-focus="search-field" title="Search">
                                    <i class="fal fa-search"></i>
                                </a>
                            </div>-->
                        <!-- app settings -->
                        <div class="hidden-md-down">
                            <a href="#" class="header-icon" data-toggle="modal" data-target=".js-modal-settings">
                                <i class="fal fa-cog"></i>
                            </a>
                        </div>
                        <div>
                            <?php echo $notificaciones ?>
                        </div>
                        <div>
                            <a href="#" class="header-icon" data-toggle="dropdown" title="My Apps">
                                <i class="fal fa-cube"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-animated w-auto h-auto">
                                <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center rounded-top">
                                    <h4 class="m-0 text-center color-white">
                                        Módulos Pangea
                                        <small class="mb-0 opacity-80">Seleccionar el módulo que quiere ingresar.</small>
                                    </h4>
                                </div>
                                <div class="custom-scroll h-100">
                                    <ul class="app-list">
                                        <!-- <?php echo $modulosTopFlotante; ?> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- app user menu -->
                        <div>
                            <a href="#" data-toggle="dropdown" title="Administrar Cuenta" class="header-icon d-flex align-items-center justify-content-center ml-2">
                                <img src="<?php echo RUTA_HUBLEAN_IMG; ?>telefonica.jpg" class="profile-image rounded-circle">
                                <!-- you can also add username next to the avatar with the codes below:
									<span class="ml-1 mr-1 text-truncate text-truncate-header hidden-xs-down">Me</span>
									<i class="ni ni-chevron-down hidden-xs-down"></i> -->
                            </a>
                            <div class="dropdown-menu dropdown-menu-animated dropdown-lg">
                                <div class="dropdown-header bg-trans-gradient d-flex flex-row py-4 rounded-top">
                                    <div class="d-flex flex-row align-items-center mt-1 mb-1 color-white">
                                        <span class="mr-2">
                                            <img src="<?php echo RUTA_HUBLEAN_IMG; ?>logo-telefonica-vertical-blanco.png" class="rounded-circle profile-image">
                                        </span>
                                        <div class="info-card-text">
                                            <div class="fs-lg text-truncate text-truncate-lg"><?php echo $this->session->userdata('usuario') ?></div>
                                            <span class="text-truncate text-truncate-md opacity-80"><?php echo $this->session->userdata('perfil') ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider m-0"></div>
                                <a href="#" class="dropdown-item" data-toggle="modal" data-target=".js-modal-settings">
                                    <span data-i18n="drpdwn.settings">Configuracion</span>
                                </a>
                                <a class="dropdown-item fw-500 pt-3 pb-3" href="page_login.html">
                                    <span data-i18n="drpdwn.page-logout">Administrar Cuenta</span>
                                </a>
                                <div class="dropdown-divider m-0"></div>
                                <a class="dropdown-item fw-500 pt-3 pb-3" href="logOut">
                                    <span data-i18n="drpdwn.page-logout">Cerrar Sesion</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- END Page Header -->
                <!-- BEGIN Page Content -->
                <!-- the #js-page-content id is needed for some plugins to initialize -->
                <main id="js-page-content" role="main" class="page-content">  
                    <ol class="breadcrumb page-breadcrumb">
                        <li class="breadcrumb-item"><a href="bienvenida">Bienvenido</a></li>
                        <li class="breadcrumb-item active">Validar Firma Digital</li>
                    </ol>
                    <div class="subheader"> <!-- col s12 m10 offset-m1 -->
                        <h1 class="subheader-title">
                            <i class='subheader-icon fal fa-table'></i>
                            SOLICITUD ORDEN DE COMPRA
                        </h1>
                    </div>
                              
                    <div class="row">
                        <div class="col-xl-12">
                            <div id="panel-1" class="panel">
                                <div class="panel-hdr form-group">
                                    <div class="panel-toolbar">
                                        <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                                        <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                                        <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button>
                                    </div>
                                </div>
                                <div class="panel-container show">
                                    <div class="panel-content">
                                        <div class="panel-tag">
                                            Podrá filtrar la tabla haciendo clic al botón en la parte inferior derecha, "filtrar".
                                        </div>
                                        <div class="justify-content-center nav-justified form-group" id="titleSistema">
                                            <h1 class="subheader-title text-center"><?php echo $tituloSistema; ?></h1>
                                        </div>
										
                                        <ul class="nav nav-tabs nav-tabs-clean  justify-content-center nav-justified" role="tablist">
                                            <li class="nav-item col-md-4"><a class="nav-link active justify-content-center" data-toggle="tab" href="#planobra"><i class="fal fa-file-check mr-1"></i>PLANOBRA / +DESPLIEGUE</a></li>
                                            <li class="nav-item col-md-4"><a class="nav-link justify-content-center" data-toggle="tab" href="#item_madre"><i class="fal fa-warehouse mr-1"></i>ITEMPLAN MADRE</a></li>
                                            <li class="nav-item col-md-4"><a class="nav-link justify-content-center" data-toggle="tab" href="#oim"><i class="fal fa-warehouse mr-1"></i>O&M</a></li>
											<li class="nav-item col-md-4"><a class="nav-link justify-content-center" data-toggle="tab" href="#inventario"><i class="fal fa-warehouse mr-1"></i>INVENTARIO</a></li>
                                        </ul>
                                        
                                        <div class="tab-content py-3">
                                            <div class="tab-pane fade show active" id="planobra" role="tabpanel">
                                                <div class="panel-content">
                                                    <div style="overflow-x: scroll !important;">
                                                        <div style="width:3000px !important;display:none;" id="contTablaIndividual">
                                                            <?php echo isset($tablaPdtActa) ? $tablaPdtActa : null; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="oim" role="tabpanel">
                                                <div class="panel-content">
                                                    <div style="overflow-x: scroll !important;">
                                                        <div style="width:3000px !important" id="contOiM">
                                                            <?php echo isset($tablaPdtActaOiM) ? $tablaPdtActaOiM : null; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
											<div class="tab-pane fade" id="item_madre" role="tabpanel">
                                                <div class="panel-content">
                                                    <div style="overflow-x: scroll !important;">
                                                        <div style="width:3000px !important" id="contTablaItemMadre">
                                                            <?php echo isset($tablaPdtActaItemMadre) ? $tablaPdtActaItemMadre : null; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="inventario" role="tabpanel">
                                                <div class="panel-content">
                                                    <div style="overflow-x: scroll !important;">
                                                        <div style="width:3000px !important" id="contTablaIventario">
                                                            <?php echo isset($tablaInventario) ? $tablaInventario : null; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- this overlay is activated only when mobile menu is triggered -->
                <div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div> <!-- END Page Content -->

                <!-- BEGIN Shortcuts -->
                <div class="modal fade modal-backdrop-transparent" id="modal-shortcut" tabindex="-1" role="dialog" aria-labelledby="modal-shortcut" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-top modal-transparent" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <ul class="app-list w-auto h-auto p-0 text-left">
                                    <li>
                                        <a href="intel_introduction.html" class="app-list-item text-white border-0 m-0">
                                            <div class="icon-stack">
                                                <i class="base base-7 icon-stack-3x opacity-100 color-primary-500 "></i>
                                                <i class="base base-7 icon-stack-2x opacity-100 color-primary-300 "></i>
                                                <i class="fal fa-home icon-stack-1x opacity-100 color-white"></i>
                                            </div>
                                            <span class="app-list-name">
                                                Home
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="page_inbox_general.html" class="app-list-item text-white border-0 m-0">
                                            <div class="icon-stack">
                                                <i class="base base-7 icon-stack-3x opacity-100 color-success-500 "></i>
                                                <i class="base base-7 icon-stack-2x opacity-100 color-success-300 "></i>
                                                <i class="ni ni-envelope icon-stack-1x text-white"></i>
                                            </div>
                                            <span class="app-list-name">
                                                Inbox
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="intel_introduction.html" class="app-list-item text-white border-0 m-0">
                                            <div class="icon-stack">
                                                <i class="base base-7 icon-stack-2x opacity-100 color-primary-300 "></i>
                                                <i class="fal fa-plus icon-stack-1x opacity-100 color-white"></i>
                                            </div>
                                            <span class="app-list-name">
                                                Add More
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Shortcuts -->
                <!-- BEGIN Color profile -->
                <!-- this area is hidden and will not be seen on screens or screen readers -->
            </div>
        </div>
    </div>
    <!-- END Page Wrapper -->
    
    <div class="modal fade" id="modalValidarEstacion">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 style="margin: auto" id="tittleCertificarHG" class="modal-title"></h3>
                </div>
                
                <div class="modal-body">
               
                    <div id="contTablaPdt">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>      
                </div>
            </div>
        </div>
    </div> 

    <div class="modal fade" id="mdlFiltrar" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center w-100 form-group">
                    <h4 class="m-0 text-center color-white" id="titModalFiltrar">
                        FILTRAR
                    </h4>
                    <button type="button" class="close text-white position-absolute pos-top pos-right p-2 m-1 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formFiltrar">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="txtItemplan">Itemplan</label>
                                <input type="text" class="form-control" id="txtItemplan" name="txtItemplan" placeholder="" data-inputmask="'mask': '99-9999999999'" maxlength="13" required>
                                <div class="valid-feedback">
                                    Correcto!
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="cmbSubProyecto">SubProyecto</label>
                                <select id="cmbSubProyecto" name="cmbSubProyecto" class="select2 form-control w-100">
                                    <?php echo $cmbSubProyecto; ?>
                                </select>
                                <div class="valid-feedback">
                                    Correcto!
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="cmbEstadoFirma">Estado Firma</label>
                                <select id="cmbEstadoFirma" name="cmbEstadoFirma" class="select2 form-control w-100">
                                    <?php echo $cmbEstadoFirma; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="cmbSistema">Sistema</label>
                                <select id="cmbSistema" name="cmbSistema" class="select2 form-control w-100">
                                    <?php echo $cmbSistema; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnFiltro" onclick="filtrarTabla();">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>    

    <!-- BEGIN Page Settings -->
    <div class="modal fade js-modal-settings modal-backdrop-transparent" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-right modal-md">
            <div class="modal-content">
                <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center w-100">
                    <h4 class="m-0 text-center color-white">
                        Opciones de Dise&ntilde;o
                        <small class="mb-0 opacity-80">Configuraci&oacute;n de interfaz de usuario</small>
                    </h4>
                    <button type="button" class="close text-white position-absolute pos-top pos-right p-2 m-1 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="settings-panel">
                        <div class="mt-4 d-table w-100 px-5">
                            <div class="d-table-cell align-middle">
                                <h5 class="p-0">
                                    Dise&ntilde;o de Aplicaci&oacute;n
                                </h5>
                            </div>
                        </div>
                        <div class="list" id="nfm">
                            <a href="#" onclick="return false;" class="btn btn-switch" data-action="toggle" data-class="nav-function-minify"></a>
                            <span class="onoffswitch-title">Minimizar Navegador</span>
                            <span class="onoffswitch-title-desc">Posicione el puntero en el navegador para ver las opciones</span>
                        </div>
                        <div class="list" id="nfh">
                            <a href="#" onclick="return false;" class="btn btn-switch" data-action="toggle" data-class="nav-function-hidden"></a>
                            <span class="onoffswitch-title">Ocultar Navegador</span>
                            <span class="onoffswitch-title-desc">Posicione el puntero en el borde para revelar el navegador</span>
                        </div>
                        <div class="list" id="nft">
                            <a href="#" onclick="return false;" class="btn btn-switch" data-action="toggle" data-class="nav-function-top"></a>
                            <span class="onoffswitch-title">Navegador Superior</span>
                            <span class="onoffswitch-title-desc">Colocar el navegador en la parte superior de la interfaz</span>
                        </div>
                        <div class="expanded">
                            <ul class="mb-3 mt-1">
                                <li>
                                    <div class="bg-fusion-50" data-action="toggle" data-class="mod-bg-1"></div>
                                </li>
                                <li>
                                    <div class="bg-warning-200" data-action="toggle" data-class="mod-bg-2"></div>
                                </li>
                                <li>
                                    <div class="bg-primary-200" data-action="toggle" data-class="mod-bg-3"></div>
                                </li>
                                <li>
                                    <div class="bg-success-300" data-action="toggle" data-class="mod-bg-4"></div>
                                </li>
                                <li>
                                    <div class="bg-white border" data-action="toggle" data-class="mod-bg-none"></div>
                                </li>
                            </ul>
                            <div class="list" id="mbgf">
                                <a href="#" onclick="return false;" class="btn btn-switch" data-action="toggle" data-class="mod-fixed-bg"></a>
                                <span class="onoffswitch-title">Fixed Background</span>
                            </div>
                        </div>
                        <div class="mt-4 d-table w-100 px-5">
                            <div class="d-table-cell align-middle">
                                <h5 class="p-0">
                                    Modificaciones Globales
                                </h5>
                            </div>
                        </div>
                        <div class="list" id="mdn">
                            <a href="#" onclick="return false;" class="btn btn-switch" data-action="toggle" data-class="mod-nav-dark"></a>
                            <span class="onoffswitch-title">Navegador Oscuro</span>
                            <span class="onoffswitch-title-desc">El navegador tendra un fondo oscuro</span>
                        </div>
                        <hr class="mb-0 mt-4">
                        <div class="mt-4 d-table w-100 pl-5 pr-3">
                            <div class="d-table-cell align-middle">
                                <h5 class="p-0">
                                    Tama&ntilde;o de fuente Global
                                </h5>
                            </div>
                        </div>
                        <div class="list mt-1">
                            <div class="btn-group btn-group-sm btn-group-toggle my-2" data-toggle="buttons">
                                <label class="btn btn-default btn-sm" data-action="toggle-swap" data-class="root-text-sm" data-target="html">
                                    <input type="radio" name="changeFrontSize"> SM
                                </label>
                                <label class="btn btn-default btn-sm" data-action="toggle-swap" data-class="root-text" data-target="html">
                                    <input type="radio" name="changeFrontSize" checked=""> MD
                                </label>
                                <label class="btn btn-default btn-sm" data-action="toggle-swap" data-class="root-text-lg" data-target="html">
                                    <input type="radio" name="changeFrontSize"> LG
                                </label>
                                <label class="btn btn-default btn-sm" data-action="toggle-swap" data-class="root-text-xl" data-target="html">
                                    <input type="radio" name="changeFrontSize"> XL
                                </label>
                            </div>
                            <span class="onoffswitch-title-desc d-block mb-0">Cambiar el tama&ntilde;o de la fuente del aplicativo (Se reiniciara con el refresh de la pagina)</span>
                        </div>
                        <hr class="mb-0 mt-4">
                        <div class="mt-4 d-table w-100 pl-5 pr-3">
                            <div class="d-table-cell align-middle">
                                <h5 class="p-0 pr-2 d-flex">
                                    Color de Aplicativo
                                </h5>
                            </div>
                        </div>
                        <div class="expanded theme-colors pl-5 pr-3">
                            <ul class="m-0">
                                <li>
                                    <a href="#" id="myapp-0" data-action="theme-update" data-themesave data-theme="" data-toggle="tooltip" data-placement="top" title="Wisteria (base css)" data-original-title="Wisteria (base css)"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-1" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-1.css" data-toggle="tooltip" data-placement="top" title="Tapestry" data-original-title="Tapestry"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-2" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-2.css" data-toggle="tooltip" data-placement="top" title="Atlantis" data-original-title="Atlantis"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-3" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-3.css" data-toggle="tooltip" data-placement="top" title="Indigo" data-original-title="Indigo"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-4" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-4.css" data-toggle="tooltip" data-placement="top" title="Dodger Blue" data-original-title="Dodger Blue"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-5" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-5.css" data-toggle="tooltip" data-placement="top" title="Tradewind" data-original-title="Tradewind"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-6" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-6.css" data-toggle="tooltip" data-placement="top" title="Cranberry" data-original-title="Cranberry"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-7" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-7.css" data-toggle="tooltip" data-placement="top" title="Oslo Gray" data-original-title="Oslo Gray"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-8" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-8.css" data-toggle="tooltip" data-placement="top" title="Chetwode Blue" data-original-title="Chetwode Blue"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-9" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-9.css" data-toggle="tooltip" data-placement="top" title="Apricot" data-original-title="Apricot"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-10" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-10.css" data-toggle="tooltip" data-placement="top" title="Blue Smoke" data-original-title="Blue Smoke"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-11" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-11.css" data-toggle="tooltip" data-placement="top" title="Green Smoke" data-original-title="Green Smoke"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-12" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-12.css" data-toggle="tooltip" data-placement="top" title="Wild Blue Yonder" data-original-title="Wild Blue Yonder"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-13" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-13.css" data-toggle="tooltip" data-placement="top" title="Emerald" data-original-title="Emerald"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-14" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-14.css" data-toggle="tooltip" data-placement="top" title="Supernova" data-original-title="Supernova"></a>
                                </li>
                                <li>
                                    <a href="#" id="myapp-15" data-action="theme-update" data-themesave data-theme="<?php echo RUTA_HUBLEAN_CSS; ?>themes/cust-theme-15.css" data-toggle="tooltip" data-placement="top" title="Hoki" data-original-title="Hoki"></a>
                                </li>
                            </ul>
                        </div>
                        <hr class="mb-0 mt-4">
                        <div class="mt-4 d-table w-100 pl-5 pr-3">
                            <div class="d-table-cell align-middle">
                                <h5 class="p-0 pr-2 d-flex">
                                    Temas de Aplicativo
                                </h5>
                            </div>
                        </div>
                        <div class="pl-5 pr-3 py-3">
                            <div class="row no-gutters">
                                <div class="col-4 pr-2 text-center">
                                    <div id="skin-default" data-action="toggle-replace" data-replaceclass="mod-skin-light mod-skin-dark" data-class="" data-toggle="tooltip" data-placement="top" title="" class="d-flex bg-white border border-primary rounded overflow-hidden text-success js-waves-on" data-original-title="Default Mode" style="height: 80px">
                                        <div class="bg-primary-600 bg-primary-gradient px-2 pt-0 border-right border-primary"></div>
                                        <div class="d-flex flex-column flex-1">
                                            <div class="bg-white border-bottom border-primary py-1"></div>
                                            <div class="bg-faded flex-1 pt-3 pb-3 px-2">
                                                <div class="py-3" style="background:url('<?php echo RUTA_HUBLEAN_IMG; ?>demo/s-1.png') top left no-repeat;background-size: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    Default
                                </div>
                                <div class="col-4 px-1 text-center">
                                    <div id="skin-light" data-action="toggle-replace" data-replaceclass="mod-skin-dark" data-class="mod-skin-light" data-toggle="tooltip" data-placement="top" title="" class="d-flex bg-white border border-secondary rounded overflow-hidden text-success js-waves-on" data-original-title="Light Mode" style="height: 80px">
                                        <div class="bg-white px-2 pt-0 border-right border-"></div>
                                        <div class="d-flex flex-column flex-1">
                                            <div class="bg-white border-bottom border- py-1"></div>
                                            <div class="bg-white flex-1 pt-3 pb-3 px-2">
                                                <div class="py-3" style="background:url('<?php echo RUTA_HUBLEAN_IMG; ?>demo/s-1.png') top left no-repeat;background-size: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    Light
                                </div>
                                <div class="col-4 pl-2 text-center">
                                    <div id="skin-dark" data-action="toggle-replace" data-replaceclass="mod-skin-light" data-class="mod-skin-dark" data-toggle="tooltip" data-placement="top" title="" class="d-flex bg-white border border-dark rounded overflow-hidden text-success js-waves-on" data-original-title="Dark Mode" style="height: 80px">
                                        <div class="bg-fusion-500 px-2 pt-0 border-right"></div>
                                        <div class="d-flex flex-column flex-1">
                                            <div class="bg-fusion-600 border-bottom py-1"></div>
                                            <div class="bg-fusion-300 flex-1 pt-3 pb-3 px-2">
                                                <div class="py-3 opacity-30" style="background:url('<?php echo RUTA_HUBLEAN_IMG; ?>demo/s-1.png') top left no-repeat;background-size: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    Dark
                                </div>
                            </div>
                        </div>
                        <hr class="mb-0 mt-4">
                        <div class="pl-5 pr-3 py-3 bg-faded">
                            <div class="row no-gutters">
                                <div class="col-12 pr-1">
                                    <a href="#" class="btn btn-outline-danger fw-500 btn-block" data-action="app-reset">Reiniciar Configuracion</a>
                                </div>
                            </div>
                        </div>
                    </div> <span id="saving"></span>
                </div>
            </div>
        </div>
    </div>

	<div class="modal fade" id="modalEvidenciaAws" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title">Evidencias</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
					<div id="contTablaEvidenciaAws">
					
					</div>
                </div>
            </div>
        </div>
    </div>



    <nav class="shortcut-menu d-none d-sm-block">
        <input type="checkbox" class="menu-open" name="menu-open" id="menu_open" />
        <label for="menu_open" class="menu-open-button ">
            <span class="app-shortcut-icon d-block"></span>
        </label>
        <a href="#" class="menu-item btn" data-toggle="tooltip" data-placement="left" title="Scroll Top">
            <i class="fal fa-arrow-up"></i>
        </a>
        <a href="#" class="menu-item btn" data-action="app-fullscreen" data-toggle="tooltip" data-placement="left" title="Full Screen">
            <i class="fal fa-expand"></i>
        </a>
        <a href="#" class="menu-item btn" data-action="app-print" data-toggle="tooltip" data-placement="left" title="Print page">
            <i class="fal fa-print"></i>
        </a>
        <a class="menu-item btn" data-toggle="tooltip" data-placement="left" title="Logout">
            <i class="fal fa-sign-out"></i>
        </a>
        <a class="menu-item btn" data-toggle="tooltip" data-placement="left" title="Filtrar" onclick="openModalFiltrar();">
            <i class="fal fa-filter"></i>
        </a>
    </nav>
    <!-- END Page Settings -->
    
    <script src="<?php echo base_url(); ?>public/hublean/js/vendors.bundle.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/app.bundle.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/formplugins/select2/select2.bundle.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/formplugins/inputmask/inputmask.bundle.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/formplugins/bootstrap-datepicker/bootstrap-datepicker.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/datagrid/datatables/datatables.bundle.js"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/datagrid/datatables/datatables.export.js"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/loading/jquery.loading.min.js"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/notifications/sweetalert2/sweetalert2.bundle.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
    <script src="<?php echo base_url(); ?>public/demo/js/other-charts.js"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/notifications/toastr/toastr.js"></script>
    <script src="<?php echo base_url(); ?>public/hublean/js/Utils.js?v=<?php echo time(); ?>"></script>

        <script>
            var idSistemaGlobal = 1;
            $(document).ready(function()
            {
                fnRenderizarDataTable('tbPdtActa');
				
                $('.dt-buttons').append('<button class="btn buttons-excel buttons-html5 btn-outline-info btn-sm mr-4 mb-1" tabindex="0" aria-controls="tbPdtActa" type="button" title="Generar Excel" onclick="validarMasivaFirmaAutomatica();"><span>Valida Masivo</span></button>');            
				console.log(111);
				$('#contTablaIndividual').css('display', 'block');
				console.log(222);
			});	

			function generarSolicitudOcManual(btn) {
				var itemplan = btn.data('itemplan');
				var pep1 = btn.data('pep1');
				console.log("pep1 : "+pep1);
				
				if(itemplan == '' || itemplan == null ) {
					return;
				}

				Swal.queue([{
					title: 'Est&aacute; seguro de generar la solicitud oc?',
					text: 'Asegurese de validar la Informacion.',
					icon: 'warning',
					showCancelButton: true,
					buttonsStyling: false,
					confirmButtonClass: 'btn btn-primary',
					html: '<div>' +
                        '	<label style="color:red">PEP1</label>' +
                        '</div>' +
                        '<div class="form-group">' +
                        '	<input type="text" value ="'+pep1+'" class="col-md-12 form-control" placeholder="pep1..." style="background:#F9F8CF" id="txtPep1">' +
                        '</div>',
					confirmButtonText: 'Si, generar solicitud oc!',
					cancelButtonClass: 'btn btn-secondary',
					allowOutsideClick: false,
					showLoaderOnConfirm: true,
					preConfirm: function preConfirm()
					{
						var pep1 = $('#txtPep1').val();
						
						if(pep1 == null || pep1 == '') {
							return;
						}
						
						$.ajax({
							type : 'POST',
							url  : 'generarSolicitudOcManual',
							data : { itemplan : itemplan,
									 pep1     : pep1 }
						}).done(function (data) {
							data = JSON.parse(data);
							console.log(data);
							if (data.error == 0) {
								swal.fire({
									title: 'Se valido la firma correctamente',
									text: 'Asegurese de validar la informacion!',
									icon: 'success',
									buttonsStyling: false,
									confirmButtonClass: 'btn btn-primary',
									confirmButtonText: 'OK!'
								}).then(function(){
									$('#contTablaIndividual').html(data.tabla_pdt_acta);
									fnRenderizarDataTable('tbPdtActa');
									// location.reload();
								});
							} else if (data.error == 1) {
								mostrarNotificacion('error', 'Error', data.msj);
							}
						}).fail(function (jqXHR, textStatus, errorThrown) {
							mostrarNotificacion('error', 'Error', 'Comuníquese con alguna persona a cargo :(');
						})
						.always(function () {
				
						});
					}
				}]).then(function(){
					
					
				});
			}			
        </script>
	</body>

	

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>
