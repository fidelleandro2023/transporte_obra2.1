<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
    <head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>

        <style type="text/css">

            .select2-dropdown {
                z-index: 100000;
            }


            .subir{
                padding: 5px 10px;
                background: #f55d3e;
                color:#fff;
                border:0px solid #fff;    	     
                width: 40%;
                border-radius: 25px;
            }

            .subir:hover{
                color:#fff;
                background: #f7cb15;
            }


        </style>  

        <link rel="stylesheet" href="<?php echo base_url(); ?>public/demo/css/demo.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css">
    </head>

    <body data-ma-theme="entel">      
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
                    <a href="https://www.movistar.com.pe/" title="Entel Per�"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                    <h2>GESTION DE OBRA PUBLICA</h2>
                    <div class="card">		   				                    

                        <div class="card-block">
                            <?php if ($hasInfo == 1) { ?>

                                <div class="tab-container">
                                    <ul class="nav nav-tabs nav-fill" role="tablist">

                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#contenido1" role="tab">INFORMACION</a>
                                        </li>
                                        <?php if ($infoItem['has_kickoff'] == 1) { ?>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#contenido6" role="tab">KICK-OFF</a>
                                            </li> 
                                        <?php } ?>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#contenido2" role="tab">COTIZACION</a>
                                        </li>                                
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#contenido3" role="tab">RESPUESTA COTIZACION</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#contenido4" role="tab">DATOS DE FACTURA</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#contenido5" role="tab">EVALUACION ECONOMICA</a>
                                        </li>

                                    </ul>

                                    <div class="tab-content">                                 
                                        <div class="tab-pane active fade show" id="contenido1" role="tabpanel">
                                            <div class="row" id="infocontenido" style="text-align: left;">  
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;">ITEMPLAN</label>
                                                        <input id="contCodigoPO" type="text" class="form-control" value="<?php echo $infoItem['itemplan'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;">PROYECTO</label>
                                                        <input id="contEstadoPO" type="text" class="form-control" value="<?php echo $infoItem['proyectoDesc'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;">SUB PROYECTO</label>
                                                        <input id="contProyecto" type="text" class="form-control" value="<?php echo $infoItem['subproyectoDesc'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group has-feedback" style="">
                                                        <label style="font-weight: bold;">DEPARTAMENTO</label>
                                                        <input id="contSubProyecto" type="text" class="form-control" value="<?php echo $infoItem['departamento'] ?>" disabled="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group has-feedback" style="">
                                                        <label style="font-weight: bold;">PROVINCIA</label>
                                                        <input id="contVR" type="text" class="form-control" value="<?php echo $infoItem['provincia'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;">DISTRITO</label>
                                                        <input id="contAreaDesc" type="text" class="form-control" value="<?php echo $infoItem['distrito'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;">COORD X</label>
                                                        <input id="codAlmacen" type="text" class="form-control" value="<?php echo $infoItem['coordY'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;">COORD Y</label>
                                                        <input id="contoMontoTotal" type="text" class="form-control" value="<?php echo $infoItem['coordX'] ?>" disabled>
                                                    </div>
                                                </div> 
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;">FECHA DE RECEPCION</label>
                                                        <input id="contJefatura" type="text" class="form-control" value="<?php echo $infoItem['fecha_recepcion'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;">NOMBRE DEL CLIENTE</label>
                                                        <input id="contEmpresacolab" type="text" class="form-control" value="<?php echo $infoItem['nombre_cliente'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;">NUM. CARTA PEDIDO</label>
                                                        <input id="contCentral" type="text" class="form-control" value="<?php echo $infoItem['numero_carta_pedido'] ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group" style="text-align: center;">
                                                        <label style="font-weight: bold;">CARTA (FILE)</label><br>
                                                        <a id="pathCarta" href="<?php echo utf8_decode($infoItem['ruta_carta_pdf']) ?>" target="_blank"><img alt="Editar" height="35px" width="45px" src="public/img/iconos/pdf.png"></a>
                                                    </div>
                                                </div>     
                                                <div class="col-sm-12 col-md-12">
                                                    <form id="formUpdateCartaBasic" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                                        <div class="row">
                                                            <div style="text-align: center;" class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label style="font-size: large;" for="fileuploadCB" class="subir">
                                                                        <i class="zmdi zmdi-upload"></i>
                                                                    </label>
                                                                    <input accept="application/pdf" id="fileuploadCB" name="fileuploadCB" type="file" onchange='cambiarCB()' style='display: none;'>
                                                                    <div id="infoCB">Actualizar CARTA (PDF) </div>
                                                                </div>                                
                                                            </div> 


                                                            <div class="col-sm-3 col-md-3" style="text-align: left;">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">                                      
                                                                        <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-md-6" style="text-align: center;">
                                                                <div id="mensajeFormCB"></div>
                                                            </div>
                                                        </div>
                                                    </form> 
                                                </div>

                                                <div class="col-sm-12 col-md-12" style="border-style: double;">

                                                    <div id="contenedor_mapa" style="height: 420px; position: relative; overflow: hidden;"></div>
                                                </div>   
                                            </div>                                    
                                        </div>
                                        <div class="tab-pane fade show" id="contenido2" role="tabpanel"> 
                                            <div  class="row" id="contCotizacion" style="<?php echo (($has_ko == 1 && $estadoKO == 'PENDIENTE') ? 'display:none' : '' ) ?>"> 

                                                <div class="col-sm-12 col-md-12">
                                                    <div id="contTablaCotizacion" class="table-responsive">
                                                        <?php echo $tablaCotizacion ?>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-12" style="<?php echo (($infoItem['deposito_convenio'] == 'SI' && $infoItem['detraccion_convenio'] == 'SI') ? 'display:none' : '') ?>">   <br>
                                                    <h6 class="card-body__title" style="text-decoration: underline;text-align: left;font-weight: bold;color: #0154a0;">ACTUALIZAR CARTA COTIZACION</h6>                                                                 
                                                    <form id="formAddCartaCoti" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                                        <br>
                                                        <div class="row">

                                                            <div class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>DISE&Ncaron;O</label>
                                                                    <input value="<?php echo $totalDiseno ?>" readonly="true" maxlength="42" id="inputDise" name="inputDise" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>LICENCIA</label>
                                                                    <input value="<?php echo $totalLicencia['total'] ?>" readonly="true" maxlength="42" id="inputLicencia" name="inputLicencia" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>COSTO MANO DE OBRA</label>
                                                                    <input value="<?php echo $total['totalMo'] ?>" readonly="true" maxlength="42" id="inputMO" name="inputMO" type="text" class="form-control">
                                                                </div> 
                                                            </div>
                                                            <div class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>COSTO MATERIAL</label>
                                                                    <input value="<?php echo $total['totalMA'] ?>" readonly="true" maxlength="42" id="inputMA" name="inputMA" type="text" class="form-control">
                                                                </div> 
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>COSTO TOTAL</label>
                                                                    <input  readonly="true" maxlength="42" id="inputCT" name="inputCT" type="text" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">                                 
                                                            <div style="TEXT-ALIGN: left;" class="form-group col-sm-6 col-md-6">
                                                                <label style="font-weight: bold;">NUMERO CARTA COTIZACION</label>
                                                                <input maxlength="42" id="inputNumCartaCoti" name="inputNumCartaCoti" type="text" class="form-control">
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div style="text-align: center;" class="col-sm-6 col-md-6">
                                                                <div class="form-group">
                                                                    <label style="font-size: large;" for="fileuploadCR" class="subir">
                                                                        <i class="zmdi zmdi-upload"></i>
                                                                    </label>
                                                                    <input accept="application/pdf" id="fileuploadCR" name="fileuploadCR" type="file" onchange='cambiarCR()' style='display: none;'>
                                                                    <div id="infoCR">Subir Carta Cotizacion (PDF) </div>
                                                                </div>                                
                                                            </div> 

                                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                                <div id="mensajeFormCR"></div>
                                                            </div>  
                                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">                                      
                                                                        <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form> 

                                                </div>
                                            </div>                                                              
                                        </div>
                                        <div class="tab-pane fade show" id="contenido3" role="tabpanel">
                                            <div  class="row" id="contRespuCoti" style="<?php echo (($hastCartCotizacion == 1) ? '' : 'display:none') ?>"> 

                                                <div class="col-sm-12 col-md-12">
                                                    <div id="contTablaRespuCotizacion" class="table-responsive">
                                                        <?php echo $tablaRespuCotizacion ?>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-12" style="<?php echo (($infoItem['deposito_convenio'] == 'SI' && $infoItem['detraccion_convenio'] == 'SI') ? 'display:none' : '') ?>">   <br>
                                                    <h6 class="card-body__title" style="text-decoration: underline;text-align: left;font-weight: bold;color: #0154a0;">ACTUALIZAR CARTA RESPUESTA</h6>                                                                 
                                                    <form id="formAddCartaRespu" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                                        <div class="row">       
                                                            <div style="TEXT-ALIGN: left;" class="form-group col-sm-2 col-md-2">
                                                                <label style="font-weight: bold;">NUMERO CARTA COTIZACION</label>
                                                                <input id="numCartaCotiToRespu" name="numCartaCotiToRespu" disabled value="<?php echo $infoItem['numero_carta_cotizacion'] ?>" type="text" class="form-control">
                                                                <i class="form-group__bar"></i>
                                                            </div>                                                                             
                                                            <div style="TEXT-ALIGN: left;" class="form-group col-sm-2 col-md-2">
                                                                <label style="font-weight: bold;">NUMERO CARTA RESPUESTA</label>
                                                                <input maxlength="42" id="inputNumCartaRespu" name="inputNumCartaRespu" type="text" class="form-control">
                                                                <i class="form-group__bar"></i>
                                                            </div>                                                       
                                                            <div class="col-sm-2 col-md-2">
                                                                <div class="form-group">
                                                                    <label>CONVENIO</label>
                                                                    <select id="selectConvenio" name="selectConvenio" class="select2 form-control" onchange="changueConvenio()">
                                                                        <option value="SI">SI</option>
                                                                        <option value="NO">NO</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div id="contEstadoGestion" class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>ESTADO DE GESTION</label>
                                                                    <select id="selectEstadoGes" name="selectEstadoGes" class="select2 form-control">                                        <option value="EN GESTION">EN GESTION</option>
                                                                        <option value="SUSCRITO">SUSCRITO</option>
                                                                        <option value="OBSERVADO">OBSERVADO</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div style="text-align: center;" class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label style="font-size: large;" for="fileuploadCR2" class="subir">
                                                                        <i class="zmdi zmdi-upload"></i>
                                                                    </label>
                                                                    <input accept="application/pdf" id="fileuploadCR2" name="fileuploadCR2" type="file" onchange='cambiarCR2()' style='display: none;'>
                                                                    <div id="infoCR2">Subir Carta Respuesta (PDF) </div>
                                                                </div>                                
                                                            </div> 

                                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                                <div id="mensajeFormCR2"></div>
                                                            </div>  
                                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">                                      
                                                                        <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form> 

                                                </div>
                                            </div>                                    
                                        </div>
                                        <div class="tab-pane fade show" id="contenido4" role="tabpanel">
                                            <div  class="row" id="contDatosFactura" style="<?php echo (($hastCartRespuesta == 1) ? '' : 'display:none') ?>"> 

                                                <div class="col-sm-12 col-md-12">
                                                    <div id="contTablaDatosConvenio" class="table-responsive">
                                                        <?php echo $tablaDatosConvenio ?>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-12" style="<?php echo (($infoItem['deposito_convenio'] == 'SI' && $infoItem['detraccion_convenio'] == 'SI') ? 'display:none' : '') ?>">   <br>
                                                    <h6 class="card-body__title" style="text-decoration: underline;text-align: left;font-weight: bold;color: #0154a0;">ACTUALIZAR DATOS CONVENIO</h6>                                                                 
                                                    <form id="formAddDatosConvenio" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                                        <div class="row">    
                                                            <div style="TEXT-ALIGN: left;" class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>ESTADO FACTURA</label>
                                                                    <select id="selectEstaFactu" name="selectEstaFactu" onchange="changueEstadoFactura()" class="select2 form-control">
                                                                        <option value="SOLICITADO">SOLICITADO</option>
                                                                        <option value="EMITIDO">EMITIDO</option>
                                                                    </select>
                                                                </div>
                                                            </div>                             
                                                            <div style="TEXT-ALIGN: left;" class="form-group col-sm-3 col-md-3">
                                                                <label style="font-weight: bold;">FACTURA</label>
                                                                <input disabled id="inputFactura" name="inputFactura" type="text" class="form-control">
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div style="TEXT-ALIGN: left;" class="form-group col-sm-3 col-md-3">
                                                                <label style="font-weight: bold;">OP GRAVADA SIN IGV</label>
                                                                <input disabled id="opGraSinIGV" name="opGraSinIGV" type="text" class="form-control" onchange="getcalculos()">
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div style="TEXT-ALIGN: left;" class="form-group col-sm-3 col-md-3">
                                                                <label style="font-weight: bold;">IGV</label>
                                                                <input disabled id="igvConvenio" name="igvConvenio" type="text" class="form-control">
                                                                <i class="form-group__bar"></i>
                                                            </div> 
                                                            <div style="TEXT-ALIGN: left;" class="form-group col-sm-3 col-md-3">
                                                                <label style="font-weight: bold;">OP GRAVADA RED SIN IGV 17%</label>
                                                                <input disabled id="opGraRedSinIGV" name="opGraRedSinIGV" type="text" class="form-control">
                                                                <i class="form-group__bar"></i>
                                                            </div> 
                                                            <div style="TEXT-ALIGN: left;" class="form-group col-sm-3 col-md-3">
                                                                <label style="font-weight: bold;">IMPORTE TOTAL</label>
                                                                <input disabled id="inputTotalConve" name="inputTotalConve" type="text" class="form-control">
                                                                <i class="form-group__bar"></i>
                                                            </div>
                                                            <div style="TEXT-ALIGN: left;" class="form-group col-sm-3 col-md-3">
                                                                <label style="font-weight: bold;">COSTO DE LA OBRA</label>
                                                                <input disabled id="inputCostoConve" name="inputCostoConve" type="text" class="form-control">
                                                                <i class="form-group__bar"></i>
                                                            </div>   
                                                            <div style="TEXT-ALIGN: left;" class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>DEPOSITO</label>
                                                                    <select disabled id="selectDeposito" name="selectDeposito" class="select2 form-control">
                                                                        <option value="">:::SELECCIONAR:::</option>
                                                                        <option value="SI">SI</option>
                                                                        <option value="NO">NO</option>                                                                       
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div style="TEXT-ALIGN: left;" class="col-sm-3 col-md-3">
                                                                <div class="form-group">
                                                                    <label>DETRACCION U OTROS</label>
                                                                    <select disabled id="selectDetrac" name="selectDetrac" class="select2 form-control">
                                                                        <option value="">:::SELECCIONAR:::</option>
                                                                        <option value="SI">SI</option>
                                                                        <option value="NO">NO</option>                                                                       
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                                <div id="mensajeFormCR3"></div>
                                                            </div>  
                                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">                                      
                                                                        <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form> 

                                                </div>
                                            </div>                                      
                                        </div>
                                        <div class="tab-pane fade show" id="contenido5" role="tabpanel">
                                            <div  class="row" id="contEvaEconomica" style="<?php echo (($hastNumFactura == 1) ? '' : 'display:none') ?>"> 

                                                <div class="col-sm-12 col-md-12">
                                                    <div id="contTablaResuEvaEcono" class="table-responsive">
                                                        <?php echo $tablaResumenEvaEcono ?>
                                                    </div>
                                                </div>
                                            </div>                                 
                                        </div>  
                                        <div class="tab-pane fade show" id="contenido6" role="tabpanel">
                                            <div  class="row" id="contKickOff">                                            
                                                <div class="col-sm-12 col-md-12">
                                                    <div id="contTablaKickOff" class="table-responsive">
                                                        <?php echo $tablaKickOff ?>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-12" id="editKickOff" style="<?php echo (($has_ko == 1 && $estadoKO == 'EJECUTADO') ? 'display:none' : '' ) ?>">   <br>
                                                    <h6 class="card-body__title" style="text-decoration: underline;text-align: left;font-weight: bold;color: #0154a0;">EJECUTAR KICK-OFF</h6>                                                              
                                                    <form id="formAddKickOff" method="post" class="form-horizontal"  enctype="multipart/form-data"> 
                                                        <div class="row">
                                                            <div style="text-align: center;" class="col-sm-12 col-md-12">
                                                                <div class="form-group">
                                                                    <label style="font-size: large;width: 20%;" for="fileuploadKO" class="subir">
                                                                        <i class="zmdi zmdi-upload"></i>
                                                                    </label>
                                                                    <input accept="application/pdf" id="fileuploadKO" name="fileuploadKO" type="file" onchange='cambiarKO()' style='display: none;'>
                                                                    <div id="infoKO">Subir Ducumento Kick-Off (PDF) </div>
                                                                </div>                                
                                                            </div> 

                                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                                <div id="mensajeFormKO"></div>
                                                            </div>  
                                                            <div class="col-sm-12 col-md-12" style="text-align: center;">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">                                      
                                                                        <button type="submit" class="btn btn-primary">EJECUTAR KICK-OFF</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form> 

                                                </div>
                                            </div>                                 
                                        </div>                                  
                                    </div>


                                </div>
                            <?php } else { ?>
                                <p>El itemplan no fue encontrado o no cuenta con los permisos para acceder a la informaci�n.</p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                </div>

                <footer class="footer hidden-xs-down">
                    <p>Telefonica Del Peru.</p>


                </footer>
            </section>

        </main>
        <!-- Small -->

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

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&callback=init" async defer></script>
        <script type="text/javascript">
                                                                        //$('#inputCT').val($('#costoEstimado').val() + $('#total').val() + $('#totalMo').val() + $('#totalMA').val());
                                                                        var inputCT;
                                                                        var inputDise = $('#inputDise').val();
                                                                        var inputLicencia = $('#inputLicencia').val();
                                                                        var inputMO = $('#inputMO').val();
                                                                        var totalMA = $('#inputMA').val();

                                                                        inputDise = inputDise.replace(/,/g, "");
                                                                        inputLicencia = inputLicencia.replace(/,/g, "");
                                                                        inputMO = inputMO.replace(/,/g, "");
                                                                        totalMA = totalMA.replace(/,/g, "");

                                                                        inputCT = parseFloat(inputDise) + parseFloat(inputLicencia) + parseFloat(inputMO) + parseFloat(totalMA);

                                                                        $('#inputCT').val(number_format(inputCT, 2, '.', ','));

                                                                        function number_format(number, decimals, dec_point, thousands_sep) {
                                                                            number = number.toFixed(decimals);

                                                                            var nstr = number.toString();
                                                                            nstr += '';
                                                                            x = nstr.split('.');
                                                                            x1 = x[0];
                                                                            x2 = x.length > 1 ? dec_point + x[1] : '';
                                                                            var rgx = /(\d+)(\d{3})/;

                                                                            while (rgx.test(x1))
                                                                                x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

                                                                            return x1 + x2;
                                                                        }

                                                                        var itemGlob = <?php echo (($infoItem['itemplan'] != '') ? "'" . $infoItem['itemplan'] . "'" : "''") ?>;

                                                                        function    getcalculos() {
                                                                            var opSinIGV = $('#opGraSinIGV').val();
                                                                            var opGraRedSinIGV = Number(opSinIGV * 0.17);
                                                                            var inputCostoConve = Number(opSinIGV) - Number(opGraRedSinIGV);
                                                                            var igvConvenio = opSinIGV * 0.18;
                                                                            var inputTotalConve = Number(opSinIGV) + Number(igvConvenio);

                                                                            $('#opGraRedSinIGV').val(opGraRedSinIGV.toFixed(2));
                                                                            $('#inputCostoConve').val(inputCostoConve.toFixed(2));
                                                                            $('#igvConvenio').val(igvConvenio.toFixed(2));
                                                                            $('#inputTotalConve').val(inputTotalConve.toFixed(2));
                                                                        }

                                                                        /**************************************CARTA COTIZACION***********************************************/
                                                                        function cambiarCR() {
                                                                            var pdrs = document.getElementById('fileuploadCR').files[0].name;
                                                                            document.getElementById('infoCR').innerHTML = pdrs;
                                                                        }

                                                                        $('#formAddCartaCoti')
                                                                                .bootstrapValidator({
                                                                                    container: '#mensajeFormCR',
                                                                                    feedbackIcons: {
                                                                                        valid: 'glyphicon glyphicon-ok',
                                                                                        invalid: 'glyphicon glyphicon-remove',
                                                                                        validating: 'glyphicon glyphicon-refresh'
                                                                                    },
                                                                                    excluded: ':disabled',
                                                                                    fields: {
                                                                                        fileuploadCR: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe subir Carta Cotizacion(PDF).</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        inputNumCartaCoti: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Ingresar Numero Carta Cotizacion.</p>'
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }).on('success.form.bv', function (e) {
                                                                            e.preventDefault();

                                                                            if ($("#inputDise").val()	==	'' || $("#inputDise").val() == null) {
                                                                                mostrarNotificacion('warning', 'Mensaje', 'No tiene costo de Dise\u00f1o');
                                                                                return false;
                                                                            }
                                                                            if ($("#inputLicencia").val()	==	'' || $("#inputLicencia").val() == null) {
                                                                                mostrarNotificacion('warning', 'Mensaje', 'No tiene costo de Licencia');
                                                                                return false;
                                                                            } else {
                                                                                swal({
                                                                                    title: 'Est&aacute; seguro de actualizar Cotizacion?',
                                                                                    text: 'Asegurese de que la informacion llenada sea la correta.',
                                                                                    type: 'warning',
                                                                                    showCancelButton: true,
                                                                                    buttonsStyling: false,
                                                                                    confirmButtonClass: 'btn btn-primary',
                                                                                    confirmButtonText: 'Si, guardar los datos!',
                                                                                    cancelButtonClass: 'btn btn-secondary',
                                                                                    allowOutsideClick: false
                                                                                }).then(function () {


                                                                                    var $form = $(e.target),
                                                                                            formData = new FormData(),
                                                                                            params = $form.serializeArray(),
                                                                                            bv = $form.data('bootstrapValidator');

                                                                                    $.each(params, function (i, val) {
                                                                                        formData.append(val.name, val.value);
                                                                                    });

                                                                                    var input = document.getElementById('fileuploadCR');
                                                                                    var file = input.files[0];
                                                                                    //var form = new FormData();
                                                                                    formData.append('fileCR', file);

                                                                                    formData.append('item', itemGlob);

                                                                                    $.ajax({
                                                                                        data: formData,
                                                                                        url: "saveCROPObraPublica",
                                                                                        cache: false,
                                                                                        contentType: false,
                                                                                        processData: false,
                                                                                        type: 'POST'
                                                                                    })
                                                                                            .done(function (data) {
                                                                                                data = JSON.parse(data);
                                                                                                if (data.error == 0) {
                                                                                                    console.log('ok');

                                                                                                    swal({
                                                                                                        title: 'Se actualizo corecctamente la Carta Respuesta',
                                                                                                        text: itemGlob,
                                                                                                        type: 'success',
                                                                                                        showCancelButton: false,
                                                                                                        allowOutsideClick: false
                                                                                                    }).then(function () {
                                                                                                        $('#contTablaCotizacion').html(data.tablaCotizacion);
                                                                                                        $('#formAddCartaCoti').bootstrapValidator('resetForm', true);
                                                                                                        $('#numCartaCotiToRespu').val(data.cartaCoti);
                                                                                                        $('#contRespuCoti').show();

                                                                                                    });


                                                                                                } else if (data.error == 1) {
                                                                                                    mostrarNotificacion('error', 'Error', 'No se inserto el Plan de obra');
                                                                                                }
                                                                                            })
                                                                                            .fail(function (jqXHR, textStatus, errorThrown) {
                                                                                                mostrarNotificacion('error', 'Error', 'ComunÃƒÂ­quese con alguna persona a cargo :(');
                                                                                            })
                                                                                            .always(function () {

                                                                                            });


                                                                                }, function (dismiss) {
                                                                                    console.log('cancelado');
                                                                                    // dismiss can be "cancel" | "close" | "outside"
                                                                                    //$('#formAddCartaRespu').bootstrapValidator('revalidateField', 'selectCotizacion');
                                                                                    //$('#formAddCartaRespu').bootstrapValidator('resetForm', true); 
                                                                                });
                                                                            }
                                                                        });


                                                                        /*****************************************carta respuesta******************************************/
                                                                        function cambiarCR2() {
                                                                            var pdrs = document.getElementById('fileuploadCR2').files[0].name;
                                                                            document.getElementById('infoCR2').innerHTML = pdrs;
                                                                        }

                                                                        $('#formAddCartaRespu')
                                                                                .bootstrapValidator({
                                                                                    container: '#mensajeFormCR2',
                                                                                    feedbackIcons: {
                                                                                        valid: 'glyphicon glyphicon-ok',
                                                                                        invalid: 'glyphicon glyphicon-remove',
                                                                                        validating: 'glyphicon glyphicon-refresh'
                                                                                    },
                                                                                    excluded: ':disabled',
                                                                                    fields: {
                                                                                        fileuploadCR2: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe subir Carta Respuesta(PDF).</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        inputNumCartaRespu: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Ingresar Numero Carta Respuesta.</p>'
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }).on('success.form.bv', function (e) {
                                                                            e.preventDefault();

                                                                            swal({
                                                                                title: 'Est&aacute; seguro de actializar Carta Respuesta?',
                                                                                text: 'Asegurese de que la informacion llenada sea la correta.',
                                                                                type: 'warning',
                                                                                showCancelButton: true,
                                                                                buttonsStyling: false,
                                                                                confirmButtonClass: 'btn btn-primary',
                                                                                confirmButtonText: 'Si, guardar los datos!',
                                                                                cancelButtonClass: 'btn btn-secondary',
                                                                                allowOutsideClick: false
                                                                            }).then(function () {


                                                                                var $form = $(e.target),
                                                                                        formData = new FormData(),
                                                                                        params = $form.serializeArray(),
                                                                                        bv = $form.data('bootstrapValidator');

                                                                                $.each(params, function (i, val) {
                                                                                    formData.append(val.name, val.value);
                                                                                });

                                                                                var input = document.getElementById('fileuploadCR2');
                                                                                var file = input.files[0];
                                                                                //var form = new FormData();
                                                                                formData.append('fileCR2', file);
                                                                                formData.append('item', itemGlob);

                                                                                var numCarRes = $('#numCartaCotiToRespu').val();
                                                                                formData.append('numCarRe', numCarRes);
                                                                                $.ajax({
                                                                                    data: formData,
                                                                                    url: "saveCROP2ObraPublica",
                                                                                    cache: false,
                                                                                    contentType: false,
                                                                                    processData: false,
                                                                                    type: 'POST'
                                                                                })
                                                                                        .done(function (data) {
                                                                                            data = JSON.parse(data);
                                                                                            if (data.error == 0) {
                                                                                                console.log('ok');

                                                                                                swal({
                                                                                                    title: 'Se actualizo corecctamente la Carta Respuesta',
                                                                                                    text: itemGlob,
                                                                                                    type: 'success',
                                                                                                    showCancelButton: false,
                                                                                                    allowOutsideClick: false
                                                                                                }).then(function () {
                                                                                                    $('#contTablaRespuCotizacion').html(data.tablaRespuCotizacion);
                                                                                                    $('#formAddCartaRespu').bootstrapValidator('resetForm', true);
                                                                                                    $('#numCartaCotiToRespu').val(data.cartaCoti);
                                                                                                    var hasConvenio = $('#selectConvenio').val();
                                                                                                    var estadoConve = $('#selectEstadoGes').val();
                                                                                                    if ((hasConvenio == 'SI' && estadoConve == 'SI') || hasConvenio == 'NO') {
                                                                                                        $('#contDatosFactura').show();
                                                                                                    }


                                                                                                });


                                                                                            } else if (data.error == 1) {
                                                                                                mostrarNotificacion('error', 'Error', 'No se inserto el Plan de obra');
                                                                                            }
                                                                                        })
                                                                                        .fail(function (jqXHR, textStatus, errorThrown) {
                                                                                            mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                                                                                        })
                                                                                        .always(function () {

                                                                                        });


                                                                            }, function (dismiss) {
                                                                                console.log('cancelado');
                                                                                // dismiss can be "cancel" | "close" | "outside"
                                                                                //$('#formAddCartaRespu').bootstrapValidator('revalidateField', 'selectCotizacion');
                                                                                //$('#formAddCartaRespu').bootstrapValidator('resetForm', true); 
                                                                            });

                                                                        });

                                                                        /*****************************************DATOS CONVENIO******************************************/

                                                                        $('#formAddDatosConvenio')
                                                                                .bootstrapValidator({
                                                                                    container: '#mensajeFormCR3',
                                                                                    feedbackIcons: {
                                                                                        valid: 'glyphicon glyphicon-ok',
                                                                                        invalid: 'glyphicon glyphicon-remove',
                                                                                        validating: 'glyphicon glyphicon-refresh'
                                                                                    },
                                                                                    excluded: ':disabled',
                                                                                    fields: {
                                                                                        inputFactura: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Ingresar Numero Factura.</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        selectEstaFactu: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Seleccionar Estado Factura.</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        opGraSinIGV: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Ingresar OP Gravada Sin IGV.</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        opGraRedSinIGV: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Ingresar OP Gravada para el calculo OP Gravada Red sin IGV .</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        selectDeposito: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Seleccionar si cuenta con Deposito.</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        selectDetrac: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Seleccionar si cuenta con Detraccion u Otros.</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        inputCostoConve: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Ingresar OP Gravada para el calculo Costo de la Obra.</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        igvConvenio: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Ingresar OP Gravada para el calculo del IGV.</p>'
                                                                                                }
                                                                                            }
                                                                                        },
                                                                                        inputTotalConve: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe Ingresar OP Gravada para el calculo Total Importe.</p>'
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }).on('success.form.bv', function (e) {
                                                                            e.preventDefault();

                                                                            swal({
                                                                                title: 'Est&aacute; seguro de Actualizar Datos de Convenio',
                                                                                text: 'Asegurese de que la informacion llenada sea la correta.',
                                                                                type: 'warning',
                                                                                showCancelButton: true,
                                                                                buttonsStyling: false,
                                                                                confirmButtonClass: 'btn btn-primary',
                                                                                confirmButtonText: 'Si, guardar los datos!',
                                                                                cancelButtonClass: 'btn btn-secondary',
                                                                                allowOutsideClick: false
                                                                            }).then(function () {


                                                                                var $form = $(e.target),
                                                                                        formData = new FormData(),
                                                                                        params = $form.serializeArray(),
                                                                                        bv = $form.data('bootstrapValidator');

                                                                                $.each(params, function (i, val) {
                                                                                    formData.append(val.name, val.value);
                                                                                });

                                                                                formData.append('item', itemGlob);

                                                                                var opGraRedSinIGV = $('#opGraRedSinIGV').val();
                                                                                var inputCostoConve = $('#inputCostoConve').val();
                                                                                var igvConvenio = $('#igvConvenio').val();
                                                                                var inputTotalConve = $('#inputTotalConve').val();

                                                                                formData.append('opRedSinIGV', opGraRedSinIGV);
                                                                                formData.append('costoObra', inputCostoConve);
                                                                                formData.append('igvObra', igvConvenio);
                                                                                formData.append('importeTotal', inputTotalConve);

                                                                                $.ajax({
                                                                                    data: formData,
                                                                                    url: "saveCROP3ObraPublica",
                                                                                    cache: false,
                                                                                    contentType: false,
                                                                                    processData: false,
                                                                                    type: 'POST'
                                                                                })
                                                                                        .done(function (data) {
                                                                                            data = JSON.parse(data);
                                                                                            if (data.error == 0) {
                                                                                                console.log('ok');

                                                                                                swal({
                                                                                                    title: 'Se actualizo corecctamente Datos de Convenio',
                                                                                                    text: itemGlob,
                                                                                                    type: 'success',
                                                                                                    showCancelButton: false,
                                                                                                    allowOutsideClick: false
                                                                                                }).then(function () {
                                                                                                    $('#contTablaDatosConvenio').html(data.tablaDatosConvenio);
                                                                                                    $('#formAddDatosConvenio').bootstrapValidator('resetForm', true);
                                                                                                    $('#contEvaEconomica').show();
                                                                                                });


                                                                                            } else if (data.error == 1) {
                                                                                                mostrarNotificacion('error', 'Error', 'No se inserto el Plan de obra');
                                                                                            }
                                                                                        })
                                                                                        .fail(function (jqXHR, textStatus, errorThrown) {
                                                                                            mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                                                                                        })
                                                                                        .always(function () {

                                                                                        });


                                                                            }, function (dismiss) {
                                                                                console.log('cancelado');
                                                                                // dismiss can be "cancel" | "close" | "outside"
                                                                                //$('#formAddCartaRespu').bootstrapValidator('revalidateField', 'selectCotizacion');
                                                                                //$('#formAddCartaRespu').bootstrapValidator('resetForm', true); 
                                                                            });

                                                                        });

                                                                        /**************************************KICK OFF***********************************************/
                                                                        function cambiarKO() {
                                                                            var pdrs = document.getElementById('fileuploadKO').files[0].name;
                                                                            document.getElementById('infoKO').innerHTML = pdrs;
                                                                        }

                                                                        $('#formAddKickOff')
                                                                                .bootstrapValidator({
                                                                                    container: '#mensajeFormKO',
                                                                                    feedbackIcons: {
                                                                                        valid: 'glyphicon glyphicon-ok',
                                                                                        invalid: 'glyphicon glyphicon-remove',
                                                                                        validating: 'glyphicon glyphicon-refresh'
                                                                                    },
                                                                                    excluded: ':disabled',
                                                                                    fields: {
                                                                                        fileuploadKO: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe subir Documento Kick-Off(PDF).</p>'
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }).on('success.form.bv', function (e) {
                                                                            e.preventDefault();

                                                                            swal({
                                                                                title: 'Est&aacute; seguro de ejecutar Kick-off?',
                                                                                text: 'Asegurese de que la informacion llenada sea la correta.',
                                                                                type: 'warning',
                                                                                showCancelButton: true,
                                                                                buttonsStyling: false,
                                                                                confirmButtonClass: 'btn btn-primary',
                                                                                confirmButtonText: 'Si, guardar los datos!',
                                                                                cancelButtonClass: 'btn btn-secondary',
                                                                                allowOutsideClick: false
                                                                            }).then(function () {


                                                                                var $form = $(e.target),
                                                                                        formData = new FormData(),
                                                                                        params = $form.serializeArray(),
                                                                                        bv = $form.data('bootstrapValidator');

                                                                                $.each(params, function (i, val) {
                                                                                    formData.append(val.name, val.value);
                                                                                });

                                                                                var input = document.getElementById('fileuploadKO');
                                                                                var file = input.files[0];
                                                                                //var form = new FormData();
                                                                                formData.append('fileKO', file);

                                                                                formData.append('item', itemGlob);

                                                                                $.ajax({
                                                                                    data: formData,
                                                                                    url: "saveKOffObraPublica",
                                                                                    cache: false,
                                                                                    contentType: false,
                                                                                    processData: false,
                                                                                    type: 'POST'
                                                                                })
                                                                                        .done(function (data) {
                                                                                            data = JSON.parse(data);
                                                                                            if (data.error == 0) {
                                                                                                console.log('ok');

                                                                                                swal({
                                                                                                    title: 'Se actualizo ejecuto Kick-Off!',
                                                                                                    text: itemGlob,
                                                                                                    type: 'success',
                                                                                                    showCancelButton: false,
                                                                                                    allowOutsideClick: false
                                                                                                }).then(function () {
                                                                                                    $('#contTablaKickOff').html(data.tablaKickOff);
                                                                                                    $('#formAddKickOff').bootstrapValidator('resetForm', true);
                                                                                                    $('#contCotizacion').show();
                                                                                                    $('#editKickOff').hide();
                                                                                                });


                                                                                            } else if (data.error == 1) {
                                                                                                mostrarNotificacion('error', 'Error', 'No se inserto el Plan de obra');
                                                                                            }
                                                                                        })
                                                                                        .fail(function (jqXHR, textStatus, errorThrown) {
                                                                                            mostrarNotificacion('error', 'Error', 'ComunÃƒÂ­quese con alguna persona a cargo :(');
                                                                                        })
                                                                                        .always(function () {

                                                                                        });


                                                                            }, function (dismiss) {
                                                                                console.log('cancelado');
                                                                                // dismiss can be "cancel" | "close" | "outside"
                                                                                //$('#formAddCartaRespu').bootstrapValidator('revalidateField', 'selectCotizacion');
                                                                                //$('#formAddCartaRespu').bootstrapValidator('resetForm', true); 
                                                                            });

                                                                        });
                                                                        /*************************************GOOGLE MAPS****************************************/
                                                                        var global_coord_x = null;
                                                                        var global_coord_y = null;
<?php
if ($infoItem['coordX'] != '' && $infoItem['coordY'] != '') {
    ?>
                                                                            global_coord_x = <?php echo $infoItem['coordX'] ?>;
                                                                            global_coord_y = <?php echo $infoItem['coordY'] ?>;
<?php } ?>
                                                                        function centrarMapaEdit(latitude, longitude) {
                                                                            map.setZoom(16);
                                                                            console.log(':centrarMapa....' + latitude + '-' + longitude);
                                                                            map.setCenter(new google.maps.LatLng(latitude, longitude));
                                                                            marker = new google.maps.Marker({
                                                                                position: new google.maps.LatLng(latitude, longitude),
                                                                                map: map,
                                                                                title: "Tu posici�n",
                                                                                draggable: false,
                                                                                animation: google.maps.Animation.DROP
                                                                            });

                                                                            //ocultarMensaje();
                                                                            /*
                                                                             var geocoder = new google.maps.Geocoder();
                                                                             
                                                                             geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                                                                             if (status == google.maps.GeocoderStatus.OK) {
                                                                             var pos = marker.getPosition();
                                                                             results[0]['address_components'][1].long_name = global_direccion;
                                                                             results[0]['address_components'][0].long_name = global_numero;
                                                                             //llenarTextosByCoordenadas(results,pos);
                                                                             //console.log('...->>>>'+results[0]['address_components'][1].long_name);
                                                                             var address = results[0]['formatted_address'];
                                                                             openInfoWindowAddress(address,marker);
                                                                             }
                                                                             });*/
                                                                        }

                                                                        function openInfoWindowAddress(Addres, marker) {
                                                                            console.log('geo..');
                                                                            infoWindow.setContent([
                                                                                Addres
                                                                            ].join(''));
                                                                            infoWindow.open(map, marker);
                                                                        }

                                                                        function init() {
                                                                            var mapdivMap = document.getElementById("contenedor_mapa");
                                                                            center = new google.maps.LatLng(-12.0431800, -77.0282400);
                                                                            var myOptions = {
                                                                                zoom: 5,
                                                                                center: center,
                                                                                mapTypeId: google.maps.MapTypeId.ROADMAP
                                                                            }
                                                                            map = new google.maps.Map(document.getElementById("contenedor_mapa"), myOptions);
                                                                            infoWindow = new google.maps.InfoWindow();

                                                                            if (global_coord_x != null && global_coord_y != null) {
                                                                                centrarMapaEdit(global_coord_y, global_coord_x);
                                                                            } else {
                                                                                console.log('else');
                                                                                //geoposicionar();    
                                                                            }
                                                                            //llenarMarcadores();               
                                                                        }

                                                                        /********************************************************************************************/

                                                                        function changueEstadoFactura() {
                                                                            var estado = $('#selectEstaFactu').val();
                                                                            if (estado == 'EMITIDO') {
                                                                                $('#inputFactura').prop("disabled", false);
                                                                                $('#opGraSinIGV').prop("disabled", false);
                                                                                $('#selectDeposito').prop("disabled", false);
                                                                                $('#selectDetrac').prop("disabled", false);
                                                                                console.log('emitido');
                                                                            } else if (estado == 'SOLICITADO') {
                                                                                $('#inputFactura').prop("disabled", true);
                                                                                $('#opGraSinIGV').prop("disabled", true);
                                                                                $('#selectDeposito').prop("disabled", true);
                                                                                $('#selectDetrac').prop("disabled", true);
                                                                                console.log('solicitado');
                                                                            }
                                                                        }


                                                                        function changueConvenio() {
                                                                            var convenio = $('#selectConvenio').val();
                                                                            if (convenio == 'SI') {
                                                                                $('#selectEstadoGes').val('EN GESTION');
                                                                                $('#contEstadoGestion').show();
                                                                            } else if (convenio == 'NO') {//selectEstadoGes
                                                                                $('#selectEstadoGes').val('');
                                                                                $('#contEstadoGestion').hide();

                                                                            }
                                                                        }

                                                                        /********************************** update Carta Basic ***************************************/

                                                                        function cambiarCB() {
                                                                            var pdrs = document.getElementById('fileuploadCB').files[0].name;
                                                                            document.getElementById('infoCB').innerHTML = pdrs;
                                                                        }

                                                                        $('#formUpdateCartaBasic')
                                                                                .bootstrapValidator({
                                                                                    container: '#mensajeFormCB',
                                                                                    feedbackIcons: {
                                                                                        valid: 'glyphicon glyphicon-ok',
                                                                                        invalid: 'glyphicon glyphicon-remove',
                                                                                        validating: 'glyphicon glyphicon-refresh'
                                                                                    },
                                                                                    excluded: ':disabled',
                                                                                    fields: {
                                                                                        fileuploadCB: {
                                                                                            validators: {
                                                                                                notEmpty: {
                                                                                                    message: '<p style="color:red">(*) Debe subir Carta Para actualizar(PDF).</p>'
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }).on('success.form.bv', function (e) {
                                                                            e.preventDefault();

                                                                            swal({
                                                                                title: 'Est&aacute; seguro de actualizar la CARTA?',
                                                                                text: 'Asegurese de que la informacion llenada sea la correta.',
                                                                                type: 'warning',
                                                                                showCancelButton: true,
                                                                                buttonsStyling: false,
                                                                                confirmButtonClass: 'btn btn-primary',
                                                                                confirmButtonText: 'Si, guardar los datos!',
                                                                                cancelButtonClass: 'btn btn-secondary',
                                                                                allowOutsideClick: false
                                                                            }).then(function () {


                                                                                var $form = $(e.target),
                                                                                        formData = new FormData(),
                                                                                        params = $form.serializeArray(),
                                                                                        bv = $form.data('bootstrapValidator');

                                                                                $.each(params, function (i, val) {
                                                                                    formData.append(val.name, val.value);
                                                                                });

                                                                                var input = document.getElementById('fileuploadCB');
                                                                                var file = input.files[0];
                                                                                //var form = new FormData();
                                                                                formData.append('fileCB', file);

                                                                                formData.append('item', itemGlob);

                                                                                $.ajax({
                                                                                    data: formData,
                                                                                    url: "saveCBObraPublica",
                                                                                    cache: false,
                                                                                    contentType: false,
                                                                                    processData: false,
                                                                                    type: 'POST'
                                                                                })
                                                                                        .done(function (data) {
                                                                                            data = JSON.parse(data);
                                                                                            if (data.error == 0) {
                                                                                                console.log('ok');

                                                                                                swal({
                                                                                                    title: 'Se actualizo corecctamente la Carta',
                                                                                                    text: itemGlob,
                                                                                                    type: 'success',
                                                                                                    showCancelButton: false,
                                                                                                    allowOutsideClick: false
                                                                                                }).then(function () {
                                                                                                    $("#pathCarta").attr("href", data.newPathCarta);
                                                                                                    $('#formUpdateCartaBasic').bootstrapValidator('resetForm', true);
                                                                                                    /*
                                                                                                     $('#contTablaCotizacion').html(data.tablaCotizacion);
                                                                                                     $('#formAddCartaCoti').bootstrapValidator('resetForm', true); 
                                                                                                     $('#numCartaCotiToRespu').val(data.cartaCoti);
                                                                                                     $('#contRespuCoti').show();*/
                                                                                                    //actualiza path de pdfS
                                                                                                });


                                                                                            } else if (data.error == 1) {
                                                                                                mostrarNotificacion('error', 'Error', 'No se inserto el Plan de obra');
                                                                                            }
                                                                                        })
                                                                                        .fail(function (jqXHR, textStatus, errorThrown) {
                                                                                            mostrarNotificacion('error', 'Error', 'Comuniquese con alguna persona a cargo :(');
                                                                                        })
                                                                                        .always(function () {

                                                                                        });


                                                                            }, function (dismiss) {
                                                                                console.log('cancelado');
                                                                                // dismiss can be "cancel" | "close" | "outside"
                                                                                //$('#formAddCartaRespu').bootstrapValidator('revalidateField', 'selectCotizacion');
                                                                                //$('#formAddCartaRespu').bootstrapValidator('resetForm', true); 
                                                                            });

                                                                        });
        </script>
    </body>

    <!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>