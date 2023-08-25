<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>public/dropzone/downloads/css/dropzone.css" />
        
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
        
         <style type="text/css">
           
            .select2-dropdown {
              z-index: 100000;
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
                   <a href="https://www.movistar.com.pe/" title="MOVISTAR"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo MOVISTAR" style="width: 36%; margin-left: -51%"></a>
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

                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">View Profile</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <a class="dropdown-item" href="#">Logout</a>
                        </div>
                    </div>

                    <ul class="navigation">

                                 <?php echo $opciones?>
                    </ul>
                </div>
            </aside>

               <section class="content content--full">
           
                   <div class="content__inner">
                        <h2>BANDEJA SIN FECHA DE INICIO</h2>
                            <div class="card">
                                        
                                <div class="card-block">                                             
                                            <div class="row">

                    </div>
                                            <div id="contTabla" class="table-responsive">
                                                    <?php echo $tablaAsigGrafo?>
                           </div>
                                        </div>
                                    </div>
                                            
                  <!-- ----------------------------------------------------------------------------------- -->
                <div class="modal fade" id="edi-evidencias"  tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 id="tituloModalEvi" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                   <div class="col-sm-12 col-md-12">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>FECHA DE INICIO</label>            
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                                    <div class="form-group">
                                                        <input id="inputFechaInicio" name="inputFechaInicio" type="text" class="form-control date-picker" placeholder="Pick a date" onchange="recalcular_fecha_prev_ejec()">
                                                        <i class="form-group__bar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label>FECHA PREV.EJECUCION</label>
                                                <input id="inputFechaPrev" name="inputFechaPrev" type="text" class="form-control" readonly>
                                            </div>                                                
                                        </div>
                                </div>                              
                                 <button onclick="saveFecInicio();" type="submit" id="btnAddNewIMGyPdf" class="btn btn-primary" style="background-color:#FFC107;float:right;margin-top:10px" name="btnAddNewIMG">Guardar</button>
                            </div>                            
                        </div>                      
                    </div>
                </div>                  
        </div>
                            
                                            
                                        </div>

                                        <footer class="footer hidden-xs-down">
                                            <p>Telefonica del Peru</p>

                                           
                           </footer>
            </section>
        </main>
        
        
        

        <!-- Older IE warning message -->
            <!--[if IE]>
                <div class="ie-warning">
                    <h1>Warning!!</h1>
                    <p>You are using an outdated version of Internet Explorer, please upgrade to any of the following web browsers to access this website.</p>

                    <div class="ie-warning__downloads">
                        <a href="http://www.google.com/chrome">
                            <img src="img/browsers/chrome.png" alt="">
                        </a>

                        <a href="https://www.mozilla.org/en-US/firefox/new">
                            <img src="img/browsers/firefox.png" alt="">
                        </a>

                        <a href="http://www.opera.com">
                            <img src="img/browsers/opera.png" alt="">
                        </a>

                        <a href="https://support.apple.com/downloads/safari">
                            <img src="img/browsers/safari.png" alt="">
                        </a>

                        <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">
                            <img src="img/browsers/edge.png" alt="">
                        </a>

                        <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                            <img src="img/browsers/ie.png" alt="">
                        </a>
                    </div>
                    <p>Sorry for the inconvenience!</p>
                </div>
            <![endif]-->

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

        <script src="<?php echo base_url();?>public/bower_components/flatpickr/dist/flatpickr.min.js"></script>
        
        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
         <script src="<?php echo base_url();?>public/dropzone/downloads/dropzone.min.js"></script>
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>
        
       
        <script type="text/javascript">
            
            function openEditFechaInicio(component){
            	$('#inputFechaInicio').val('');
                $('#inputFechaPrev').val('');
            	var itemplan = $(component).attr('data-item');
            	var subProy  = $(component).attr('data-subp');
         	    $('#tituloModalEvi').html('Itemplan '+itemplan);
            	$('#btnAddNewIMGyPdf').attr('item', itemplan);
            	$('#btnAddNewIMGyPdf').attr('idSub', subProy);
         	    $('#edi-evidencias').modal('toggle');   
            }

            function recalcular_fecha_prev_ejec(){
               	
                var subproy = $.trim($('#btnAddNewIMGyPdf').attr('idSub')); 
               
                if(subproy==undefined || subproy=='undefined' || subproy==''){
                    $('#inputFechaPrev').val('');
                    return false;
                }
                
                var inputFechaInicio = $.trim($('#inputFechaInicio').val()); 
                
                if(inputFechaInicio==undefined || inputFechaInicio=='undefined' || inputFechaInicio==''){
                    $('#inputFechaPrev').val('');
                    return false;
                }
                
                $.ajax({
                    type    :   'POST',
                    'url'   :   'getFechaSubproOP',
                    data    :   { fecha  : inputFechaInicio,
                                  subproyecto  : subproy
                                },
                    'async' :   false
                })
                .done(function(data){
                    var data    =   JSON.parse(data);
                    if(data.error == 0){                      
                     $('#inputFechaPrev').val(data.fechaCalculado);                        
                    }else if(data.error == 1){
                        
                        mostrarNotificacion('error','Hubo problemas al obtener la fecha de prevista!');
                    }
                });
            }

            function saveFecInicio(){
                try{
                	var inputFechaInicio = $.trim($('#inputFechaInicio').val()); 
             	    var subproy = $.trim($('#btnAddNewIMGyPdf').attr('idSub')); 
              	    var itemplan = $.trim($('#btnAddNewIMGyPdf').attr('item')); 
              	    var fecPrevista = $.trim($('#inputFechaPrev').val());
                	if(subproy==undefined || subproy=='undefined' || subproy==''){
                        $('#inputFechaPrev').val('');
                        throw new Error('Sub Proyecto No Configurado, Comuniquese con el administrador');
                    }
                    if(inputFechaInicio==undefined || inputFechaInicio=='undefined' || inputFechaInicio==''){
                        $('#inputFechaPrev').val('');
                        throw new Error('Ingrese una fecha de inicio valida.');
                    }

                	swal({
                        title: 'Esta seguro de actualizar la fecha de Inicio de Obra?',
                        text: 'Recuerde que el sistema hara un calculo de la Fecha Prevista de Ejecion!',
                        type: 'warning',
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'Si, enviar!',
                        cancelButtonClass: 'btn btn-secondary'
                    }).then(function(){                        
                        $.ajax({
                            type    :   'POST',
                            'url'   :   'updFecIni',
                            data    :   {item   : itemplan,
                                         fecIni : inputFechaInicio,
                                         fecPre : fecPrevista},
                            'async' :   false
                        })
                        .done(function(data){
                            var data    =   JSON.parse(data);
                            if(data.error == 0){   
                                $('#contTabla').html(data.tablaAsigGrafo);
                                initDataTable('#data-table');
                      	        $('#edi-evidencias').modal('toggle');  
                      	    	mostrarNotificacion('success','Operacion Exitosa.',data.msj); 
                            }else if(data.error == 1){                            
                                mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                            }
                        });                   
                    });
                    
                }catch(err){
                    alert(err);
                }
            }
        </script>
    </body>
</html>