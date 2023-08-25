<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
		<!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">

        <!-- Demo -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/demo/css/demo.css">
        
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css">
        <style>
                .subir{
                padding: 5px 10px;
                background: #5ec501;
                color:#fff;
                border:0px solid #fff;    	     
                width: 50%;
                border-radius: 25px;
            }
             
            .subir:hover{
                color:#fff;
                background: #5ec501;
            }
        </style>
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
                   <a href="https://www.movistar.com.pe/" title="Entel Perú"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
             		<h2>BANDEJA GESTI&Oacute;N CV: <?php echo $item?></h2>
              		<div class="card">
              			<div class="card-block">	
              				<div class="row">
                  				<div class="col-md-12">
                  				 <div class="tab-container tab-container--green">
                                        <ul class="nav nav-tabs nav-fill" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#home-4" role="tab">UBICACI&Oacute;N</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#profile-4" role="tab">DATOS DEL EDIFICIO</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#messages-4" role="tab">DATOS DE CONTACTO</a>
                                            </li>
                                        <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#messages-5" role="tab">LOG DE MOVIMIENTOS</a>
                                            </li>                                               
                                        </ul>
      					<form id="formRegistrarCV" method="post" class="form-horizontal">  
      						<div class="tab-content">
      						    <div class="tab-pane active fade show" id="home-4" role="tabpanel">
              						
          						<div class="row">                         
                           			<div class="col-sm-3 col-md-3">  
                               			<div class="row">
                           					<div class="form-group form-group--float col-sm-12">
                                                <input disabled style="font-weight: bold;color: black;" id="txt_departamento" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: black;">DEPARTAMENTO</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group form-group--float col-sm-12">
                                                <input disabled style="font-weight: bold;color: black;" id="txt_provincia" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: black;">PROVINCIA</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group form-group--float col-sm-12">
                                                <input disabled style="font-weight: bold;color: black;" id="txt_distrito" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: black;">DISTRITO</label>
                                                <i class="form-group__bar"></i>
                                            </div>              
                                           	<div class="form-group form-group--float col-sm-6">
                                                <input value="<?php echo $coordenada_x?>" disabled style="font-weight: bold;color: black;" id="txt_coord_x" name="txt_coord_x" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: black;">COORDENADA X</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group form-group--float col-sm-6">
                                                <input value="<?php echo $coordenada_y?>" disabled style="font-weight: bold;color: black;" id="txt_coord_y" name="txt_coord_y" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: black;">COORDENADA Y</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                       </div>
                                	</div>                            	
              				
                                 	<div class="col-sm-9 col-md-9" style="border-style: double;">
                                     	<div style=" position: absolute;top: -20px;left: 35%;z-index: 5;background-color: #fff;padding: 5px;text-align: center;line-height: 25px;padding-left: 10px;">
                                  		 	<input type="text" id="search"> <input type="button" value="Buscar Direcci�n" onClick="searchDireccion()">
                                  		</div>
                                    		<div id="contenedor_mapa" style="height: 420px; position: relative; overflow: hidden;"></div>
                            		</div>
                            </div>
                		</div>
                		
                		  <div class="tab-pane fade" id="profile-4" role="tabpanel">
                            		<div class="row">
                                        <div class="form-group col-sm-3">
                                            <label style="font-weight: bold;color: var(--azul_telefonica);">MDF</label>
                                            <input style="border-bottom-color: #838e83;" id="txt_mdf" name="txt_mdf" type="text" class="form-control form-control-sm form-control--active" disabled>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label style="font-weight: bold;color: var(--azul_telefonica);">TIPO SUBPROYECTO</label>
                                            <select id="cmbTipoSubProyecto" name="cmbTipoSubProyecto" class="select2" onchange="getSubProyectoByTipo();" disabled>
                                                    <option value="">.:Seleccionar:.</option>
                                                    <option value="1">BUCLE</option>
                                                    <option value="2">INTEGRAL</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label style="font-weight: bold;color: var(--azul_telefonica);">SUBPROYECTO</label>
                                            <select id="cmbSubProyecto" name="cmbSubProyecto" class="select2" <?php echo ($id_estado_plan == 1 || $id_estado_plan == 2 || $id_estado_plan == 8) ?  NULL : 'disabled'; ?>>
                                                    <option value="">.:Seleccionar:.</option>
                                                   <?= $cmbSubProyecto ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-2 col-md-2">
                                             <div class="form-group">
                                                   <label style="font-weight: bold;color: var(--azul_telefonica);">PLAFINIFACI&Oacute;N</label>
                                                   <select id="selectPlan" name="selectPlan" class="select2 form-control" disabled>
                                                        <?php echo ($cmbPlanificacion) ? $cmbPlanificacion : null ?>
                                                   </select>
                                            </div>
                                        </div>                 		    
                                        <!-- <div class="form-group col-sm-3">
                                            <label style="font-weight: bold;color: var(--azul_telefonica);">DISTRITO</label>
                                            <select id="selectDistrito" name="selectDistrito" class="select2" onchange="changueDistrito()"  <?php echo ($id_estado_plan == 1 || $id_estado_plan == 2 || $id_estado_plan == 8) ?  NULL : 'disabled'; ?>>
                                                    <option value="">.:Seleccionar:.</option>
                                                    <?php foreach($listaDistritos as $row){?>
                                                        <option value="<?php echo $row->distrito?>"><?php echo $row->distrito?></option>                                                        
                                                    <?php }?>
                                            </select>
                                        </div> -->
                                        <div class="form-group col-sm-3">
                                            <label style="font-weight: bold;color: var(--azul_telefonica);">EECC</label>
                                            <select id="selectEECC" name="selectEECC" class="select2" <?php echo ($id_estado_plan == 1 || $id_estado_plan == 2 || $id_estado_plan == 8) ?  NULL : 'disabled'; ?>>
                                                <?php echo ($cmbEmpresacolab) ? $cmbEmpresacolab : null ?>                                                   
                                            </select>
                                        </div>
                                            <div class="form-group col-sm-3">
                                               <label style="font-weight: bold;color: var(--azul_telefonica);">TIPO PROYECTO</label>
                                                <select id="selectTipoProy" name="selectTipoProy" class="select2">
                                                     	<option value="">.:Seleccionar:.</option>  
                                                     	<option value="HFC">HFC</option>
                                                     	<option value="FTTH">FTTH</option>                                                  
                                                </select>
                                            </div>
                                           <div class="form-group col-sm-3">
                                               <label style="font-weight: bold;color: var(--azul_telefonica);">TIPO URB / CCHH</label>
                                                <select id="selectTipoUrb" name="selectTipoUrb" class="select2">
                                                     	<option value="">.:Seleccionar:.</option>
                                                        <option value="URB.">URB.</option>
                                                        <option value="CCHH.">CCHH.</option>
                                                </select>                                             
                                            </div>
                                            
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $nombre_urb_cchh?>" id="txt_NombreUrb" name="txt_NombreUrb" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">NOMBRE URB / CCHH</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                      		<div class="form-group col-sm-3">
                                               <label style="font-weight: bold;color: var(--azul_telefonica);">TIPO VIA</label>
                                                <select id="selectTipoVia" name="selectTipoVia" class="select2">
                                                     	<option value="">.:Seleccionar:.</option>
                                                        <option value="CA.">CA.</option>
                                                        <option value="AV.">AV.</option>
                                                        <option value="JR.">JR.</option>
                                                        <option value="ALAM.">ALAM.</option>
                                                </select>                                             
                                            </div>
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" id="txt_direccion" name="txt_direccion" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">DIRECCI&Oacute;N</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" id="txt_numero" name="txt_numero" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">N&Uacute;MERO</label>
                                                <i class="form-group__bar"></i>
                                            </div> 
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $manzana?>" id="txt_manzana" name="txt_manzana" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">MANZANA</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $lote?>" id="txt_lote" name="txt_lote" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">LOTE</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                          <!-- ------------------------------------------------------------------------------------------ -->
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $nombreProyecto?>" id="txt_nombre_proyecto" name="txt_nombre_proyecto" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">NOMBRE DEL PROYECTO</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $blocks?>" id="txt_blocks" name="txt_blocks" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">BLOCKS</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $pisos?>" id="txt_pisos" name="txt_pisos" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">PISOS</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $depa?>" id="txt_departamentos" name="txt_departamentos" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">DEPARTAMENTOS</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $depa_habitados?>" id="txt_dep_habitados" name="txt_dep_habitados" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">DEPARTAMENTOS HABITADOS</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group col-sm-3">
                                               <label style="font-weight: bold;color: var(--azul_telefonica);">ESTADO DEL EDIFICIO</label>
                                                <select id="selectEstadoEdi" name="selectEstadoEdi" class="select2">
                                                     	<option value="">.:Seleccionar:.</option>
                                                        <option value="NUEVO">NUEVO</option>
                                                        <option value="ANTIGUO">ANTIGUO</option>
                                                </select>                                             
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">% AVANCE</label>
                                                <select id="txt_avance" name="txt_avance" class="select2" onchange="validatePercent()">                                                     	
                                                 	<?php echo $opcionesAvance?>                                                      
                                                </select>   
                                            </div>    
                                        <div style="display: none;text-align: center;" id="contUploadFileCoti" class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label style="font-size: large;" for="fileupload" class="subir">
                                                    <a><i class="zmdi zmdi-upload"></i></a>
                                                </label>
                                                <input id="fileupload" name="fileupload" type="file" onchange='cambiar()' multiple accept='image/*,application/pdf' style='display: none;'>
                                                <div id="info">Seleccione un archivo</div>
                                            </div>
                                        </div>                               
                                            <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $fec_termino_constru?>" id="txt_fec_termino" name="txt_fec_termino" type="text" class="form-control form-control-sm  date-picker form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">FECHA TERMINO CONSTRUCCI&Oacute;N</label>
                                                <i class="form-group__bar"></i>
                                            </div>  
                                            <div class="form-group col-sm-3">
                                               <label style="font-weight: bold;color: var(--azul_telefonica);">PRIORIDAD</label>
                                                <select id="selectPrioridad" name="selectPrioridad" class="select2">
                                                     	<option value="NO">NO</option>
                                                        <option value="SI">SI</option>
                                                </select>
                                            </div>                                     
                                            <div class="form-group col-sm-3">
                                               <label style="font-weight: bold;color: var(--azul_telefonica);">COMPETENCIA</label>
                                                <select id="selectCompetencia" name="selectCompetencia" class="select2">
                                                     	<option value="">.:Seleccionar:.</option>
                                                        <option value="CLARO">CLARO</option>
                                                        <option value="WIN">WIN</option>
                                                        <option value="DIRECTV">DIRECTV</option>
                                                </select>                                             
                                            </div>
                                            <div class="form-group form-group--float col-sm-3">
                                                <input maxlength="250" style="border-bottom-color: #838e83;" value="<?php echo $operador?>" id="txtOperador" name="txtOperador" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">OPERADOR</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                           <!-- ------------------------------------------------------------------------------------------ -->
                                     </div>                                     
                                 </div>
                                <div class="tab-pane fade" id="messages-4" role="tabpanel">
                                	<div class="row">
                                			<div class="form-group form-group--float col-sm-4">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $ruc_constructora?>" id="txt_ruc" name="txt_ruc" type="text" class="form-control form-control-sm form-control--active  input-mask" data-mask="00000000000">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">RUC</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                             <div class="form-group form-group--float col-sm-8">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $nombre_constructora?>" id="txt_nombre_constru" name="txt_nombre_constru" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">NOMBRE CONSTRUCTORA</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                            				 <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $contacto_1?>" id="txt_contacto1" name="txt_contacto1" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">CONTACTO 1</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                             <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $telefono_1_1?>" id="txt_telefono11" name="txt_telefono11" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">TEL&Eacute;FONO 1/2</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                             <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $telefono_1_2?>" id="txt_telefeono12" name="txt_telefeono12" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">TELEFONO 2/2</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                             <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $email_1?>" id="email1" name="email1" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">EMAIL 1</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                             <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $contacto_2?>" id="txt_contacto2" name="txt_contacto2" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">CONTACTO 2</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                             <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $telefono_2_1?>" id="txt_telefono21" name="txt_telefono21" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">TEL&Eacute;FONO 1/2</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                             <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $telefeono_2_2?>" id="txt_telefono22" name="txt_telefono22" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">TEL&Eacute;FONO 2/2</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                             <div class="form-group form-group--float col-sm-3">
                                                <input style="border-bottom-color: #838e83;" value="<?php echo $email_2?>" id="txt_email2" name="txt_email2" type="text" class="form-control form-control-sm form-control--active">
                                                <label style="font-weight: bold;color: var(--azul_telefonica);">EMAIL 2</label>
                                                <i class="form-group__bar"></i>
                                            </div>
                                            <div class="form-group col-sm-12">                                             	
                                              	<label style="font-weight: bold;color: var(--azul_telefonica);">OBSERVACIONES</label>
                                                <textarea style="border-bottom-color: #838e83;" id="inputObservacion" name="inputObservacion" class="form-control textarea-autosize" placeholder="Escriba aqui..."><?php echo $observaciones?></textarea>
                                                <i class="form-group__bar"></i>                                                
                            				</div>
                                    </div>
                                    <div id="mensajeForm"></div>  
                            <div class="form-group" style="text-align: right;">
                                <div class="col-sm-12">
                                   
                                    <button data-item="<?php echo $item?>" id="btnRegFicha" type="submit" class="btn btn-primary">Save changes</button>
                                   
                                </div>
                            </div>
                        </div>
                         <div class="tab-pane fade" id="messages-5" role="tabpanel">
                                	<div class="row">
                                			<div id="contTabla" class="table-responsive" style="width: 80%; padding-left: 20%">
			                                     <?php echo $tablaLogMoviemientos?>
		                                  </div>
                                    </div>                               
                        </div>                           
                    </div> 
            	</form>                        	
                    	</div>	
                    </div>
                   
                </div>   
                
                <div class="modal fade" id="modal_detalle"  tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">                           
                            <div class="tab-container">
                                <div id="contTablaDetalle" class="table-responsive">     
                                
                                </div>
                            </div>                          
                        </div>
                      
                    </div>
                </div>
            </div>           
            </section>
        </main>

        

        <!-- Javascript -->
        <!-- Vendors -->
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/autosize/dist/autosize.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        
         <!--  tables -->
        <script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>

        <!-- Demo -->
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
        
        <script>
            var global_coord_x = null;            
    		var global_coord_y = null;
			
			<?php 			
			     if($coordenada_x !=  '' && $coordenada_y !=  ''){
			?>
    			global_coord_x = <?php echo $coordenada_x?>;
    			global_coord_y = <?php echo $coordenada_y?>;
			<?php }?>
    		var global_direccion = <?php echo $direccion?>;
    		var global_numero = <?php echo $numero?>;
    		var global_ruc_constructora = <?php echo $ruc_constructora?>;
    		
    		var goblal_icon_url_terminado      = '<?php echo base_url();?>public/img/iconos/edi_term.png';
            var goblal_icon_url_pendiente      = '<?php echo base_url();?>public/img/iconos/edi_pendiente.png';
    		var global_marcadores         =  <?php echo json_encode($marcadores)?>;
    		var global_info_marcadores    =  <?php echo json_encode($info_markers)?>;

    		var goblal_icon_url_2017          = '<?php echo base_url();?>public/img/iconos/edificio3.png';
    		var global_marcadores_2017        =  <?php echo json_encode($marcadores_2017)?>;
    		var global_info_marcadores_2017   =  <?php echo json_encode($info_markers_2017)?>;

    		var goblal_icon_url_odf          = '<?php echo base_url();?>public/img/iconos/cto.png';
    		var global_marcadores_odf        =  <?php echo json_encode($marcadores_odf)?>;
    		var global_info_marcadores_odf   =  <?php echo json_encode($info_markers_odf)?>;
			
			var itemplanGlobal 				 = <?php echo $item ?>;
			var flgPaquetizadoGlb			 = <?php echo $flg_paquetizado ?>;

        </script>
  	    <script src="<?php echo base_url();?>public/js/Utils.js"></script>
  	    <script src="<?php echo base_url();?>public/js/js_crecimiento_vertical/edit_crecimiento_vertical_negocio.js?v=<?php echo time();?>"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3a1icJt1Zeq9YFBtwp1rZKb2biTJeE4I&callback=init" async defer></script>
  	    <script>
        $('#selectSubPro').val(<?php echo "'".$idSubProyecto."'"?>).trigger('change');
		//$('#selectSubPro').val(<?php echo "'".$idSubProyecto."'"?>).trigger('change');
		$('#selectTipoUrb').val(<?php echo "'".$tipo_urb_cchh."'"?>).trigger('change');
		$('#selectTipoVia').val(<?php echo "'".$tipo_via."'"?>).trigger('change');
		$('#selectEstadoEdi').val(<?php echo "'".$estado_edificio."'"?>).trigger('change');
		$('#selectDistrito').val(<?php echo "'".$distritoCentral."'"?>).trigger('change');
		$('#selectCompetencia').val(<?php echo "'".$competencia."'"?>).trigger('change');
		$('#selectPrioridad').val(<?php echo "'".$prioridad."'"?>).trigger('change');
        $('#selectTipoProy').val(<?php echo "'".$tipo_subpro."'"?>).trigger('change');
        $('#cmbTipoSubProyecto').val(<?php echo "'".$idTipoSubProyecto."'"?>).trigger('change');

		var countExp = <?php echo (($countExp >  0) ? 1 : 0)?>;

		if(countExp == 1) {
			$('#txt_departamentos').prop('disabled', true);
		} else {
			$('#txt_departamentos').prop('disabled', false);
		}
		var por_actu = <?php echo (($porcentaje_actual ==  null) ? '0' : $porcentaje_actual)?>;
  	    </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
</html>