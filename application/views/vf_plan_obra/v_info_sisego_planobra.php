<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
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
                

                <div class="header__logo hidden-sm-down" style="text-align: center;">
                   <a href="" title=""><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
                </div>
                
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

                  
                </div>
            </aside>

            <aside class="chat">
                <div class="chat__header">
                    <h2 class="chat__title">Chat <small>Currently 20 contacts online</small></h2>

                    <div class="chat__search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </div>

                <div class="listview listview--hover chat__buddies scrollbar-inner">
                    <a class="listview__item chat__available">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/7.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>hey, how are you doing.</p>
                        </div>
                    </a>

                    <a class="listview__item chat__available">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/5.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>hmm...</p>
                        </div>
                    </a>

                    <a class="listview__item chat__away">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/3.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>all good</p>
                        </div>
                    </a>

                    <a class="listview__item">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/8.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>morbi leo risus portaac consectetur vestibulum at eros.</p>
                        </div>
                    </a>

                    <a class="listview__item">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/6.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>fusce dapibus</p>
                        </div>
                    </a>

                    <a class="listview__item chat__busy">
                        <img src="<?php echo base_url();?>public/demo/img/profile-pics/9.jpg" class="listview__img" alt="">

                        <div class="listview__content">
                            <div class="listview__heading">Jeannette Lawson</div>
                            <p>cras mattis consectetur purus sit amet fermentum.</p>
                        </div>
                    </a>
                </div>

                <a href="messages.html" class="btn btn--action btn--fixed btn-danger"><i class="zmdi zmdi-plus"></i></a>
            </aside>

            <section class="content content--full">
           
	                   <div class="content__inner">
                                <h2>ITEMPLAN <?php echo isset($itemplan) ? $itemplan : NULL; ?></h2><br>
								<h4>OC : <?php echo isset($orden_compra) ? $orden_compra : NULL; ?></h4>
								<h4>ESTADO OC : <?php echo isset($estado_oc) ? $estado_oc : NULL; ?></h4>
								<h4>PEP 1: <?php echo isset($pep1) ? $pep1 : NULL ?> (S/.<?php echo isset($estatus_pep) ? $estatus_pep : NULL; ?>)</h4><br>
   				             <div class="row">
   				             	<div class="col-sm-6">
   				             		<div class="card">
   				                        <h4 class="card-header" style="color: white;background-color: #0154a0;text-align: center;">DISE&#209;O</h4>
   				                        <div class="card-body card-padding" style="margin-left: 25px;"><br>
   				                       		<div id="div1">
   				                       		<?php 
   				                       		if (isset($idTipoObra_1)) {
   				                       		   
   				                       		    if($idTipoObra_1  == ID_TIPO_OBRA_CREACION_NAP){ ?>
           				                       		<b>TIPO OBRA</b> <label style="margin-left: 121px;">:<?php echo $tipo_obra_1?></label><br>
           				                       		<b>NOMBRE CTO/NAP</b> <label style="margin-left: 74px;">:<?php echo strtoupper($nap_nombre_1)?></label><br>
           				                       		<b># TRONCAL</b> <label style="margin-left: 118px;">:<?php echo $nap_num_troncal_1?></label><br>
           				                       		<b>CANTIDAD HILOS HABILITADOS</b> <label>:<?php echo $nap_cant_hilos_1?></label><br>
           				                       		<b>NODO</b> <label style="margin-left: 151px;">:<?php echo strtoupper($nap_nodo_1)?></label><br>
           				                       		<b>UBICACION</b> <label style="margin-left: 120px;">:<?php echo strtoupper($nap_ubicacion_1)?></label><br>
           				                       		<?php if(strtoupper($nap_ubicacion_1) == 'EDIFICIO DEL CLIENTE'){ ?>
           				                       			<b># PISO</b> <label style="margin-left: 147px;">:<?php echo $nap_num_piso_1?></label><br>
           				                       		<?php }?>
           				                       		<?php if(strtoupper($nap_ubicacion_1) == 'CENTRO COMERCIAL'){ ?>
           				                       			<b>ZONA</b> <label style="margin-left: 154px;">:<?php echo strtoupper($nap_zona_1)?></label><br>
           				                       		<?php }?>
           				                       		
           				                       		<b>UBICACION DE CTO/NAP</b><br> 
           				                       		<b>COORD X:</b> <label><?php echo $nap_coord_x_1?></label>  	           	
           				                       		&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;
           				                       		<b>Nro. ODF:</b> <label><?php echo $nro_odf?></label><br>
           				                       		<b>PISO:</b> <label><?php echo $piso_g?></label><br>
													<b>SALA:</b> <label><?php echo $sala?></label><br>
													<b>BANDEJA:</b> <label><?php echo $bandeja?></label><br>
													<b>Nro. HILO:</b> <label><?php echo $nro_hilo?></label><br>
       				                       		<?php }else if($idTipoObra_1  == ID_TIPO_OBRA_FO_OSCURA){?>
       				                       			<b>TIPO OBRA</b> <label style="margin-left: 91px;">:<?php echo $tipo_obra_1?></label><br>
           				                       		<b>CANTIDAD DE HILOS</b> <label style="margin-left: 36px;">:<?php echo $fo_oscu_cant_hilos_1?></label><br>
           				                       		<b>CANTIDAD NODOS</b> <label style="margin-left: 48px;">:<?php echo $fo_oscu_cant_nodos_1?></label><br>
													<b>Nro. ODF:</b> <label><?php echo $nro_odf?></label><br>
           				                       		<b>PISO:</b> <label><?php echo $piso_g?></label><br>
													<b>SALA:</b> <label><?php echo $sala?></label><br>
													<b>BANDEJA:</b> <label><?php echo $bandeja?></label><br>
													<b>Nro. HILO:</b> <label><?php echo $nro_hilo?></label><br>
													<?php foreach($nodos_1->result() as $row){?>
       				                       				<b>NODO:</b> <label style="margin-left: 118px;">:<?php echo $row->nodo?></label><br>       				                       			
       				                       			<?php }?>   				                       		
       				                       		<?php }else if($idTipoObra_1  == ID_TIPO_OBRA_TRASLADO){?>
       				                       			<b>TIPO OBRA</b> <label style="margin-left: 132px;">:<?php echo $tipo_obra_1?></label><br>
           				                       		<b>REUNICACION CABLE EXTERNO</b> <label style="margin-left: 10px;">:<?php echo $trasla_re_cable_externo_1?></label><br>
           				                       		<b>REUBICACION CABLE INTERNO</b> <label style="margin-left: 14px;">:<?php echo $trasla_re_cable_interno_1?></label><br>
													<b>Nro. ODF:</b> <label><?php echo $nro_odf?></label><br>
           				                       		<b>PISO:</b> <label><?php echo $piso_g?></label><br>
													<b>SALA:</b> <label><?php echo $sala?></label><br>
													<b>BANDEJA:</b> <label><?php echo $bandeja?></label><br>
													<b>Nro. HILO:</b> <label><?php echo $nro_hilo?></label><br>
       				                       		
       				                       		<?php }else if($idTipoObra_1  == ID_TIPO_OBRA_FO_TRADICIONAL){?>
       				                       			<b>TIPO OBRA</b> <label style="margin-left: 105px;">:<?php echo $tipo_obra_1?></label><br>
           				                       		<b>CANT. HILOS</b> <label style="margin-left: 95px;">:<?php echo $fo_tra_cant_hilos_1?></label><br>
           				                       		<b>CANT. HILOS HABILITADOS</b> <label style="margin-left: 10px;">:<?php echo $fo_tra_cant_hilos_hab_1?></label><br>
													<b>Nro. ODF:</b> <label><?php echo $nro_odf?></label><br>
           				                       		<b>PISO:</b> <label><?php echo $piso_g?></label><br>
													<b>SALA:</b> <label><?php echo $sala?></label><br>
													<b>BANDEJA:</b> <label><?php echo $bandeja?></label><br>
													<b>Nro. HILO:</b> <label><?php echo $nro_hilo?></label><br>
												<?php }else{?>
       				                       			<b style="margin-left: 192px;text-align: center;">NO SE ENCONTRO INFORMACION</b><br><br>
       				                       		<?php }
   				                       		    }else{
   				                       		        ?>
       				                       			<b style="margin-left: 192px;text-align: center;">NO SE ENCONTRO INFORMACION</b><br><br>
       				                       		<?php 
   				                       		    }?>
   				                       		</div>
   				                        </div>	   				                       
   				                    </div>
   				             	</div>
   				             	<div class="col-sm-6">
   				             		<div class="card">
   				                        <h4 class="card-header" style="color: white;background-color: #0154a0;text-align: center;">EJECUCION</h4>
   				                        <div class="card-body card-padding" style="margin-left: 25px;"><br>
   				                       		<div id="div2">
   				                       			<?php 
			                       			if (isset($idTipoObra_2)) {
   				                       		    if($idTipoObra_2  == ID_TIPO_OBRA_CREACION_NAP){?>
   				                       		    
           				                       		<b>TIPO OBRA</b> <label style="margin-left: 121px;">:<?php echo $tipo_obra_2?></label><br>
           				                       		<b>NOMBRE CTO/NAP</b> <label style="margin-left: 74px;">:<?php echo strtoupper($nap_nombre_2)?></label><br>
           				                       		<b># TRONCAL</b> <label style="margin-left: 118px;">:<?php echo $nap_num_troncal_2?></label><br>
           				                       		<b>CANTIDAD HILOS HABILITADOS</b> <label>:<?php echo $nap_cant_hilos_2?></label><br>
           				                       		<b>NODO</b> <label style="margin-left: 151px;">:<?php echo strtoupper($nap_nodo_2)?></label><br>
           				                       		<b>UBICACION</b> <label style="margin-left: 120px;">:<?php echo strtoupper($nap_ubicacion_2)?></label><br>
           				                       		<?php if(strtoupper($nap_ubicacion_2) == 'EDIFICIO DEL CLIENTE'){ ?>
           				                       			<b># PISO</b> <label style="margin-left: 147px;">:<?php echo $nap_num_piso_2?></label><br>
           				                       		<?php }?>
           				                       		<?php if(strtoupper($nap_ubicacion_2) == 'CENTRO COMERCIAL'){ ?>
           				                       			<b>ZONA</b> <label style="margin-left: 154px;">:<?php echo strtoupper($nap_zona_2)?></label><br>
           				                       		<?php }?>
           				                       		<b>UBICACION DE CTO/NAP</b><br>   
           				                       		<b>COORD X:</b> <label><?php echo $nap_coord_x_2?></label>  	           	
           				                       		&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;	&nbsp;
           				                       		<b>COORD Y:</b> <label><?php echo $nap_coord_y_2?></label><br>
													<b>Nro. ODF:</b> <label><?php echo $nro_odf?></label><br>
           				                       		<b>PISO:</b> <label><?php echo $piso_g?></label><br>
													<b>SALA:</b> <label><?php echo $sala?></label><br>
													<b>BANDEJA:</b> <label><?php echo $bandeja?></label><br>
													<b>Nro. HILO:</b> <label><?php echo $nro_hilo?></label><br>
													<br>
       				                       		<?php }else if($idTipoObra_2  == ID_TIPO_OBRA_FO_OSCURA){?>
       				                       			<b>TIPO OBRA</b> <label style="margin-left: 91px;">:<?php echo $tipo_obra_2?></label><br>
           				                       		<b>CANTIDAD DE HILOS</b> <label style="margin-left: 36px;">:<?php echo $fo_oscu_cant_hilos_2?></label><br>
           				                       		<b>CANTIDAD NODOS</b> <label style="margin-left: 48px;">:<?php echo $fo_oscu_cant_nodos_2?></label><br>
													<b>Nro. ODF:</b> <label><?php echo $nro_odf?></label><br>
           				                       		<b>PISO:</b> <label><?php echo $piso_g?></label><br>
													<b>SALA:</b> <label><?php echo $sala?></label><br>
													<b>BANDEJA:</b> <label><?php echo $bandeja?></label><br>
													<b>Nro. HILO:</b> <label><?php echo $nro_hilo?></label><br>
													<?php foreach($nodos_2->result() as $row){?>
       				                       				<b>NODO:</b> <label style="margin-left: 118px;">:<?php echo $row->nodo?></label><br>       				                       			
       				                       			<?php }?>
       				                       		<?php }else if($idTipoObra_2  == ID_TIPO_OBRA_TRASLADO){?>
       				                       			<b>TIPO OBRA</b> <label style="margin-left: 132px;">:<?php echo $tipo_obra_2?></label><br>
           				                       		<b>REUNICACION CABLE EXTERNO</b> <label style="margin-left: 10px;">:<?php echo $trasla_re_cable_externo_2?></label><br>
           				                       		<b>REUBICACION CABLE INTERNO</b> <label style="margin-left: 14px;">:<?php echo $trasla_re_cable_interno_2?></label><br>
													<b>Nro. ODF:</b> <label><?php echo $nro_odf?></label><br>
           				                       		<b>PISO:</b> <label><?php echo $piso_g?></label><br>
													<b>SALA:</b> <label><?php echo $sala?></label><br>
													<b>BANDEJA:</b> <label><?php echo $bandeja?></label><br>
													<b>Nro. HILO:</b> <label><?php echo $nro_hilo?></label><br>
       				                       		<?php }else if($idTipoObra_2  == ID_TIPO_OBRA_FO_TRADICIONAL){?>
       				                       			<b>TIPO OBRA</b> <label style="margin-left: 105px;">:<?php echo $tipo_obra_2?></label><br>
           				                       		<b>CANT. HILOS</b> <label style="margin-left: 95px;">:<?php echo $fo_tra_cant_hilos_2?></label><br>
           				                       		<b>CANT. HILOS HABILITADOS</b> <label style="margin-left: 10px;">:<?php echo $fo_tra_cant_hilos_hab_2?></label><br>
       				                       			<b>Nro. ODF:</b> <label><?php echo $nro_odf?></label><br>
           				                       		<b>PISO:</b> <label><?php echo $piso_g?></label><br>
													<b>SALA:</b> <label><?php echo $sala?></label><br>
													<b>BANDEJA:</b> <label><?php echo $bandeja?></label><br>
													<b>Nro. HILO:</b> <label><?php echo $nro_hilo?></label><br>
												<?php }else{?>
       				                       			<b style="margin-left: 192px;text-align: center;">NO SE ENCONTRO INFORMACION</b><br><br>
       				                       		<?php }
   				                       		    }else{
   				                       		        ?>
       				                       			<b style="margin-left: 192px;text-align: center;">NO SE ENCONTRO INFORMACION</b><br><br>
       				                       		<?php 
   				                       		    }?>
   				                       		</div>
   				                        </div>
   				                    </div>
   				             	</div>
   				             </div>
		                </div>                                            
		                <footer class="footer hidden-xs-down">
		                    <p>TELEFONICA DEL PERU</p>
	                   </footer>
            </section>
        </main>
        
        <div style="visibility:hidden;">
            <form action="generarExcelCrecVertical" method="POST" id="formGenerarExcel">
            </form>
        </div>
       

        <!-- Javascript -->
        <!-- ..vendors -->
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>

        <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/flot.curvedlines/curvedLines.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jqvmap/dist/jquery.vmap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jqvmap/dist/maps/jquery.vmap.world.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/salvattore/dist/salvattore.min.js"></script>
        <script src="<?php echo base_url();?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/moment/min/moment.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

   <!--  tables -->
		<script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <!-- Charts and maps-->
        <script src="<?php echo base_url();?>public/demo/js/flot-charts/curved-line.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/flot-charts/line.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/flot-charts/chart-tooltips.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/jqvmap.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script type="text/javascript">
        
         function testPTRAprobMatFO(){
        	$.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'https://gicsapps.com:8080/obras2/recibir_dis.php',
     	    	data	:	{   ptr   		: '2018-32154513',
     	    	                itemplan 	:'18-0320600130',
                 	    	    eecc     	: 'LARI',
                 	    	    jefatura    : 'ICA',
                 	    	    fecha 		: '2018-07-05',
     	    	                vr 			: '4114813',
     	    	                sisego		: '2018-06-49821'},
     	    	'async'	:	false
     	    })
     	    .done(function(data){             	    
     	    	//var data	=	JSON.parse(data);
     	    	console.log('return:'+JSON.stringify(data));    
     	    	/*
     	    	if(data.error == 0){
         	    	console.log('ok:'+JSON.stringify(data));    
     			}else if(data.error == 1){     				
     				console.log('error:'+JSON.stringify(data));
     			}*/
     		  }).fail(function(jqXHR, textStatus, errorThrown) {
       			
      			$.ajax({
          		    type: "POST",
          		    'url' : "saveLogSigo",
            		  data: { origen 		: 'BANDEJA DE APROBACIONT PTR FO - MAT',                  		    	
                  		    	sisego 		: '2018-06-49821',
                  		    	ptr   		: '2018-32154513',
     	    	                itemplan 	:'18-0320600130',
                 	    	    eecc     	: 'LARI',
                 	    	    jefatura    : 'ICA',                 	    	    
     	    	                vr 			: '4114813',
                  		    	motivo_error: 'FALLA DE CONEXCION',
                  		    	descripcion : 'OPERACION NO COMPLETADA, SE PERDIO LA CONEXCION CON SIGOPLUS',
                  		    	estado 		: '3'},
          		    'async' : false
          		})
      		 })
        }
              
              function testTerminoObra(){
        	$.ajax({
     	    	type	:	'POST',
     	    	'url'	:	'https://gicsapps.com:8080/obras2/recibir_eje.php',
     	    	data	:	{   
     	    	                itemplan :'18-0311101082',
                 	    	    fecha : '2018-06-29'},
     	    	'async'	:	false
     	    })
     	    .done(function(data){             	    
     	    	var data	=	JSON.parse(data);
     	    	console.log('return:'+JSON.stringify(data));    
     	    	/*
     	    	if(data.error == 0){
         	    	console.log('ok:'+JSON.stringify(data));    
     			}else if(data.error == 1){     				
     				console.log('error:'+JSON.stringify(data));
     			}*/
     		  })
        }
        
        function asignarGrafo(component){


        	swal({
                title: 'Est???? seguro de actualizar el estado a 01?',
                text: 'Asegurese de validar la informaci????n seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, actualizar estado!',
                cancelButtonClass: 'btn btn-secondary'
            }).then(function(){

            	var id_ptr = $(component).attr('data-ptr');
             	var grafo = $(component).attr('data-grafo');

             	var subProy = $.trim($('#selectSubProy').val()); 
             	var eecc = $.trim($('#selectEECC').val()); 
             	var zonal = $.trim($('#selectZonal').val()); 
             	var item = $.trim($('#selectHasItemPlan').val()); 
             	var mes = $.trim($('#selectMesEjec').val()); 
             	var area = $.trim($('#selectArea').val()); 
             	             	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'updtTo01',
         	    	data	:	{id_ptr	:	id_ptr,
          	    	             grafo : grafo,
            	    	           subProy : subProy,
            	    	           eecc : eecc,
            	    	           zonal : zonal,
            	    	           item : item,
            	    	           mes : mes,
            	    	           area : area},
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data	=	JSON.parse(data);
         	    	if(data.error == 0){
             	    	          	    	   
         	    		mostrarNotificacion('success','Operaci????n ????xitosa.',data.msj);
         	    		$('#contTabla').html(data.tablaAsigGrafo)
           	    	    initDataTable('#data-table');
         			}else if(data.error == 1){
         				
         				mostrarNotificacion('error','Error el asociar Grafo',data.msj);
         			}
         		  })
         		  .fail(function(jqXHR, textStatus, errorThrown) {
         		     mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
         		  })
         		  .always(function() {
         	  	 
         		});
         	   
            });            
          	 
        }

        function filtrarTabla(){
     	     var subProy = $.trim($('#selectSubProy').val()); 
           	 var eecc = $.trim($('#selectEECC').val()); 
           	 var zonal = $.trim($('#selectZonal').val()); 
            	var item = $.trim($('#selectHasItemPlan').val()); 
             	var mes = $.trim($('#selectMesEjec').val()); 
             	var area = $.trim($('#selectArea').val()); 
             	
       	    $.ajax({
       	    	type	:	'POST',
       	    	'url'	:	'getDataTablePre',
       	    	data	:	{subProy  :	subProy,
               	    		eecc      : eecc,
            	    	    zonal     : zonal,
         	    	           item : item,
        	    	           mes : mes,
        	    	           area : area},
       	    	'async'	:	false
       	    })
       	    .done(function(data){
       	    	var data	=	JSON.parse(data);
       	    	if(data.error == 0){           	    	          	    	   
       	    		$('#contTabla').html(data.tablaAsigGrafo)
       	    	    initDataTable('#data-table');
       	    		
       			}else if(data.error == 1){
       				
       				mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
       			}
       		  });
        }
            
        function recogePep(){
            console.log('ok');
            var pep1 = $.trim($('#pep1').val());
            var pep2 = $.trim($('#pep2').val());
            
            console.log(pep1);
            console.log(pep2);
            
            $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'getPep',
         	    	data	:	{pep1	:	pep1,
          	    	             pep2 : pep2
            	    	           },
         	    	'async'	:	false
         	    })
            
            
        }
            function getPepEdit(component){
                var pep1Edit = $(component).attr('data-pep1');
                var pep2Edit = $(component).attr('data-pep2');
                var id_relacion = $(component).attr('data-id_relacion');
                
                $('#pep1Edit').attr('data-pep1Edit',pep1Edit);
                
                
            }
        
        function generarExcelCrecVer() {
            $('#formGenerarExcel').submit();
        }
        </script>
        <script type="text/javascript">
        $(document).ready(function() {
            $('#refresh').click(function() {
                // Recargo la p????gina
                window.setTimeout('location.reload()', 700);
                alertify.success("Insertado Correctamente");
            });
        });
        </script>
        
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/bootstrap.min.css"/>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>