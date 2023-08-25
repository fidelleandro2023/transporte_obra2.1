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
                                    <h2>GESTIONAR PO II</h2>
                                            <div class="card">
                                                
                                                <div class="card-block">                                             
                                                    <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>ITEMPLAN</label>

                                        <select id="selectItemPlan" name="selectItemPlan" class="select2" onchange="filtrarTabla()">
                                            <option>&nbsp;</option>
                                              <?php                                                    
                                                    foreach($itemplanList->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->itemPlan ?>"><?php echo $row->itemPlan ?></option>
                                                 <?php }?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>NOMBRE PROYECTO</label>

                                        <select id="selectSubProy" name="selectSubProy" class="select2" onchange="filtrarTabla()" >
                                        <option>&nbsp;</option>
                                        <?php                                                    
                                                    foreach($itemplanList->result() as $row){                      
                                                ?> 
                                                 <option value="<?php echo $row->nombreProyecto ?>"><?php echo $row->nombreProyecto ?></option>
                                                 <?php }?>
                                           
                                        </select>
                                    </div>
                                </div>
                                <!-- 
                              
    
                              
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>MES PREVISTO EJECUCION</label>

                                        <select id="selectMesEjec" name="selectMesEjec" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                       <option value="ENE">ENERO</option>
                                       <option value="FEB">FEBRERO</option>
                                       <option value="MAR">MARZO</option>
                                       <option value="ABR">ABRIL</option>
                                       <option value="MAY">MAYO</option>
                                       <option value="JUN">JUNIO</option>
                                       <option value="JUL">JULIO</option>
                                       <option value="AGO">AGOSTO</option>
                                       <option value="SEP">SEPTIEMBRE</option>
                                       <option value="OCT">OCTUBRE</option>
                                       <option value="NOV">NOVIEMBRE</option>
                                       <option value="DIC">DICIEMBRE</option>
                                       
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>EXPEDIENTE</label>

                                        <select id="selectExpediente" name="selectExpediente" class="select2" onchange="filtrarTabla()">
                                             <option>&nbsp;</option>
                                       <option value="SI">SI</option>
                                       <option value="NO">NO</option>
                                    
                                        </select>
                                    </div>
                                </div>
                                -->
                            </div>
                                                    <div id="contTabla" class="table-responsive">
                                                            <?php echo $tablaAsigGrafo?>
                                   </div>
                                                </div>
                                            </div>
                                            
                                            
        <div class="modal fade"id="edi-Item"  tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="tituloModal" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">                                        
                           <form id="formEditPlan" method="post" class="form-horizontal">
                               <div class="row">
                                <div class="col-sm-6 col-md-6">
                                       <div class="form-group">
                                       <label>PROYECTO</label>    
                                            <input type="text" class="form-control" id="inputProyecto" placeholder="Input Default" readonly="">
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                             
                                           <div class="form-group">
                                           <label>SUB PROYECTO</label>   
                                                <input type="text" class="form-control" id="inputSubProyecto" placeholder="Input Default" readonly="">
                                                <i class="form-group__bar"></i>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                             
                                           <div class="form-group">
                                           <label>FECHA INICIO</label>   
                                                <input type="text" class="form-control" id="inputFecInicio" placeholder="Input Default" readonly="">
                                                <i class="form-group__bar"></i>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                             
                                           <div class="form-group">
                                           <label>FECHA PREVISTA EJEC</label>   
                                                <input type="text" class="form-control" id="inputFecPrev" placeholder="Input Default" readonly="">
                                                <i class="form-group__bar"></i>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                             
                                           <div class="form-group">
                                           <label>EMPRESA COLAB.</label>   
                                                <input type="text" class="form-control" id="inputEECC" placeholder="Input Default" readonly="">
                                                <i class="form-group__bar"></i>
                                            </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6" id="select_feix" style="display:none">
                                        <div class="form-group">
                                            <label>ESTADO</label>        
                                            <select id="selectEstaItem" name="selectEstaItem" class="select2  form-control" onchange="getFecToEdit()">
                                                <option>&nbsp;</option>                                        
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6" id="select_cancelar" style="display:none">
                                        <div class="form-group">
                                            <label>MOTIVO CANCELAR</label>        
                                            <select id="motivo_cancelar" name="rmotivo" class="select2  form-control rmotivo">
                                                <option selected="" value="0">Seleccione Moitivo</option>
                                                <option value="1">Causa Cliente</option>    
                                                <option value="2">Pedido del Negocio</option>
                                                <option value="3">Sin Presupuesto</option>                                                 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6" id="select_truncar" style="display:none">
                                        <div class="form-group">
                                                    <label>MOTIVO TRUNCAR</label>        
                                                    <select id="motivo_truncar" name="rmotivo" class="select2  form-control rmotivo">
                                                        <option selected="" value="0">Seleccione Moitivo</option>
                                                        <option value="1">Causa Cliente</option>    
                                                        <option value="2">Pedido del Negocio</option> <option value="3">Ca�do por Licencias</option>
                                                        <option value="4">Oposici�n de Vecinos</option>
                                                        <option value="5">Energ�a</option>
                                                        <option value="6">Local no habilitado</option>
                                                        <option value="7">Sitio Ca�do</option>
                                                        <option value="8">Sin Gabinete</option>
                                                        <option value="9">Pendiente Permiso</option>
                                                        <option value="10">Contingente</option> 
                                                                                                 
                                                    </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="form-group">
                                                    <label>ADELANTO</label>        
                                                    <select id="selectAdelanto" name="selectAdelanto" class="select2  form-control" >
                                                        <option value="0">NO</option> 
                                                        <option value="1">SI</option>                       
                                                    </select>
                                        </div> 
                                    </div>
                                    
                                    <div class="col-sm-6" style="display: none;" id="contFecEjec">
                                        <label>Fecha Ejecuci�n</label>
    
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                            <div class="form-group">
                                                <input id="inputFecEjec" name="inputFecEjec" type="text" class="form-control date-picker" placeholder="Pick a date">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6" style="display: none;" id="contFecTerm">
                                        <label>Fecha T�rmino</label>
    
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                                            <div class="form-group">
                                                <input id="inputFecTerm" name="inputFecTerm" type="text" class="form-control date-picker" placeholder="Pick a date">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12" style="display: none;" id="motivoe">
                                        <label>Comentario</label>
    
                                        <div class="input-group">
                                            
                                            <div class="form-group">
                                             <textarea class="form-control" row="8" id="motivoet" name="motivoet"></textarea>   
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div id="mensajeForm"></div>  
                                <div class="form-group" style="text-align: right;">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                                        <button id="btnEditItem" type="submit" class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-container">
                            <div id="conTablaHisItem" class="table-responsive">     
                            
                            </div>
                        </div>
                        <br>                        
                       
                        
                    </div>
                  
                </div>
            </div>
             
            <div class="modal fade" id="edi-porcentajes"  tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="tituloModalPor" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">                                        
                              <form id="editPorcentaje" method="post" class="form-horizontal">
                                   <div class="row"  id="contChoice">
                              
                                    </div>
                                    <div id="goDetalle" style="display: none;">
                                        <a onclick="goSinFix();" style="color:blue; font-weight: bold">REGISTRO DETALLADO</a>
                                    </div>
                                    <div id="mensajeForm2"></div>  
                                    <div class="form-group" style="text-align: right;">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button id="btnEditPorcent" type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                        </div>                            
                        </div>
                      
                    </div>
                </div>
                
                <!-- ----------------------------------------------------------------------------------- -->
                <div class="modal fade" id="edi-evidencias"  tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="tituloModalEvi" style="margin: auto;font-weight: bold;" class="modal-title"></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body" style="padding-top: 60px;">                                        
                          
                                 
                                 <div id="dropzone4" class="dropzone" >
                                 
                                 </div>
                                 <hr style="border:1;">
                                 <button onclick="cerrarModalEditEvi();" type="submit" id="btnAddNewIMGyPdf" class="btn btn-primary" style="background-color:#FFC107;float:right;margin-top:10px" name="btnAddNewIMG">Guardar</button>
                                 <div id="contTablaEvi" style="padding-top: 60px;"></div>
                             
                        </div>                            
                        </div>
                      
                    </div>
                </div>
        </div>
                            
                                            
                                        </div>

                                        <footer class="footer hidden-xs-down">
                                            <p>� Material Admin Responsive. All rights reserved.</p>

                                            <ul class="nav footer__nav">
                                                <a class="nav-link" href="#">Homepage</a>

                                                <a class="nav-link" href="#">Company</a>

                                                <a class="nav-link" href="#">Support</a>

                                                <a class="nav-link" href="#">News</a>

                                                <a class="nav-link" href="#">Contacts</a>
                                            </ul>
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
        var toog2=0;
        var error=0;
        Dropzone.autoDiscover = false;
        var itemplan = '';
        $("#dropzone4").dropzone({
            url: "insertEvi",
            addRemoveLinks: true,
            autoProcessQueue: false,
            parallelUploads: 30,
            maxFilesize: 3,
            dictResponseError: "Ha ocurrido un error en el server",
            
            complete: function(file){
                if(file.status == "success"){
            //SUBIO LA IMAGEN
                    error=0;
                }
            },
            removedfile: function(file, serverFileName){
                var name = file.name;
                            var element;
                            (element = file.previewElement) != null ? 
                            element.parentNode.removeChild(file.previewElement) : 
                            false;
                            toog2=toog2-1;      
            },
            init: function() {
                
                this.on("error", function(file, message) {
                    console.log(message);
                      alert('El archivo '+file.name+' no tiene el formato correcto o el peso mayor a lo permitido, no ser� tomado en cuenta');
                    //  mostrarNotificacion('error','Error','El archivo '+file.name+' no tiene el formato correcto, no ser� tomado en cuenta');
                        error=1;
                      // alert(message);
                        this.removeFile(file); 
                });
                
                var submitButton = document.querySelector("#btnAddNewIMGyPdf")
                    myDropzone = this; // closure            
                //evento submit subimos todo
                submitButton.addEventListener("click", function() {             
               //    if(pick!='' && fec!='' && hora!='' && part!=''){
                         myDropzone.processQueue(); 
                //   }              
                    // Tell Dropzone to process all queued files.
                   });
               
               var concatEvi = '';
                // You might want to show the submit button only when 
                // files are dropped here:
                this.on("addedfile", function() {               
                    toog2=toog2+1;  
                  // Show submit button here and/or inform user to click it.
                });
                
                this.on('complete', function () {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {                  
                        if(error == 0){
                            $('#edi-evidencias').modal('toggle');
                        }               
                
                    }           
                });
                
                this.on("queuecomplete", function (file) {
                    if(error == 0){                             
                    var last = concatEvi.substring(0, (concatEvi.length - 1) );         
                    
                    $.ajax({
                        'url' : 'zipEvi',                      
                        'async' : false
                    })
                    
                    this.removeAllFiles(true); 
                    mostrarNotificacion('success','Registro','Se Regitr� Correctamente');
                    //refreshTablaRuta();
                    }
                });     

                 this.on("success", function(file, responseText) {//Trae el ID De la imagen insertada
                     concatEvi += responseText+'_';                 
                 });
                
              }
            
        });

            function cerrarModalEditEvi(){
                if(toog2    ==  0){
                    $('#edi-evidencias').modal('toggle');
                }
                
            }
       
        function openUploadFile(component){        
            
            var itemplan = $(component).attr('data-itemplan');
            var jefatura = $(component).attr('data-jefatura');
            $.ajax({
            
                    type    :   'POST',
                    url     : "putItemplan",
                    data : {'itemplan': itemplan},                 
                    'async' : false
            })
            .done(function(data){                   
                var data = JSON.parse(data);
                $('#contTablaEvi').html(data.dablaEvidencias);
                $('#tituloModalEvi').html('Itemplan '+itemplan);
                $('#edi-evidencias').modal('toggle');
              })
              .fail(function(jqXHR, textStatus, errorThrown) {
                 mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
              })
              .always(function() {
             
            });
       
        }
            
        function addField(component){           
            
            var id_estacion = $(component).attr('data-id_esta');
            var estacion = $(component).attr('data-estacion');
            var val_original = $(component).attr('data-value');
            var valor_nuevo = $(component).val();
             console.log('addfield:'+val_original+'-'+valor_nuevo);
            if(val_original!=valor_nuevo){
             console.log('diferente..');
            $('#editPorcentaje').bootstrapValidator('addField', 'selectCuadrilla'+id_estacion, {
                validators: {
                    notEmpty: {
                     message: '<p style="color:red">(*) Seleccione Cuadrilla para la estacion '+estacion+'</p>'
                    }
                }
            })
            }else{
                $('#editPorcentaje').bootstrapValidator('removeField', 'selectCuadrilla'+id_estacion);
            }
        }
        
        function getFecToEdit(){
            var idEsta = $.trim($('#selectEstaItem').val());
            
            if($("#selectEstaItem").val()==6||$("#selectEstaItem").val()==10){
            if($("#selectEstaItem").val()==6){$("#select_cancelar").css("display","block")}    
            if($("#selectEstaItem").val()==10){$("#select_truncar").css("display","block")}    
            
            $("#btnEditItem").click(function(){
                if($("#selectEstaItem").val()==6){
                    if($("#motivo_cancelar").val()==0 || $("#motivo_cancelar").val()==null){
                        $("#mensajeForm").append('<small class="help-block" id="msjCancelar" data-bv-validator="notEmpty" data-bv-for="m_cancelar" data-bv-result="INVALID" style=""><p style="color:red">(*) Ingrese Motivo para Cancelar</p></small>')    
                        return false;
                    }else{
                        $("#msjCancelar").css("display","none");        
                    }
                }    
                if($("#selectEstaItem").val()==10){
                    $("#msjCancelar").css("display","none"); 
                if($("#motivo_truncar").val()==0){
                $("#mensajeForm").append('<small class="help-block" data-bv-validator="notEmpty" data-bv-for="m_truncar" data-bv-result="INVALID" style=""><p style="color:red">(*) Ingrese Motivo para Truncar</p></small>')    
                }else{
                $("[data-bv-for=m_truncar]").css("display","none");    
                }
                
                if($("#motivoet").val()==''){                        
                    $("#mensajeForm").append('<small id="comentarioValid" class="help-block" data-bv-validator="notEmpty" data-bv-for="inputMotTerm" data-bv-result="INVALID" style=""><p style="color:red">(*) Debe Ingresar Comentario</p></small>')                      
                            return false;
                        }else{
                            $('#comentarioValid').html(null);
                            $("[data-bv-for=inputMotTerm]").css("display","none");
                        }
                }    

                if($("#selectEstaItem").val()==3){
                    $("[data-bv-for=inputMotTerm]").css("display","none");
                    $("#msjCancelar").css("display","none"); 
                }
                    })
            }else{
            $("#select_cancelar").css("display","none");   
            $("#select_truncar").css("display","none");
            $("#motivoe").css("display","none") ;     
            }
            if(idEsta==4){
                $("#msjCancelar").css("display","none"); 
                $('#contFecEjec').show();
                $('#contFecTerm').hide();
                var validator = $('#formEditPlan').data('bootstrapValidator');
                validator.enableFieldValidators('inputFecEjec', true); 
                validator.enableFieldValidators('inputFecTerm', false);
            }else if(idEsta==6||idEsta==10){
                $("#motivoe").css("display","block") ;
                $('#contFecTerm').show();
                $('#contFecEjec').hide();
                var validator = $('#formEditPlan').data('bootstrapValidator');
                validator.enableFieldValidators('inputFecEjec', false); 
                validator.enableFieldValidators('inputFecTerm', true);
                //vlaidator.enableFieldValidators('motivo_cancelar', false);
            }else{
                $("#msjCancelar").css("display","none"); 
                $('#contFecEjec').hide();
                $('#contFecTerm').hide();
                var validator = $('#formEditPlan').data('bootstrapValidator');
                validator.enableFieldValidators('inputFecEjec', false); 
                validator.enableFieldValidators('inputFecTerm', false);
            }
        }

        $('#formEditPlan')
        .bootstrapValidator({
            container: '#mensajeForm',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            excluded: ':disabled',
            fields: {
                selectEstaItem: {
                    validators: {
                        notEmpty: {
                            message: '<p style="color:red">(*) Debe seleccionar un Estado Plan.</p>'
                        }
                     }
                   },
                  selectAdelanto: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe seleccionar un Adelanto</p>'
                            }
                         }
                       },
                                               
                      inputFecEjec: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar Fecha de Ejecuci�n</p>'
                            },
                            date: {
                                format: 'YYYY-MM-DD',
                                message: '<p style="color:red">(*) Fecha no v�lida</p>'
                            }
                         }
                       } ,
                      inputFecTerm: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe Ingresar Fecha de T�rmino</p>'
                            },
                            date: {
                                format: 'YYYY-MM-DD',
                                message: '<p style="color:red">(*) Fecha no v�lida</p>'
                            }
                         }
                   }
            }
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            
            
            var $form    = $(e.target),
                formData = new FormData(),
                params   = $form.serializeArray(),
                bv       = $form.data('bootstrapValidator');     
                
                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });
                
                var itemplan = $('#btnEditItem').attr('data-item');
                formData.append('itemplan', itemplan);
                
                $.ajax({
                    data: formData,
                    url: "cestplan2",
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST'
                })
                  .done(function(data) {  
                        data = JSON.parse(data);
                        if(data.error == 0){
                            $('#contTabla').html(data.tablaAsigGrafo);                                  
                            initDataTable('#data-table');
                            $('#edi-Item').modal('toggle');  
                            mostrarNotificacion('success','Operaci�n �xitosa.', 'Se registro correcamente!');
                        }else if(data.error == 1){
                            console.log(data.error);
                        }
                  })
                  .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error','Error','Comun�quese con alguna persona a cargo :(');
                  })
                  .always(function() {
                     
                });
               
            
        });
        function closeCertificado(){              
            $('#modal-cert').modal('toggle');
            $('#modal-large').css('overflow-y', 'scroll');          
        }
        
        function editEstado(component){
            var itemplan = $(component).attr('data-itemplan');
            $.ajax({
                type    :   'POST',
                'url'   :   'getInfoItem2',
                data    :   {itemplan   :   itemplan},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){                    
                    $('#tituloModal').html('ITEMPLAN : '+itemplan);                     
                    $('#selectAdelanto').val(data.hasAdelanto).trigger('change');
                    $('#inputProyecto').val(data.nombreProyecto);
                    $('#inputSubProyecto').val(data.subProyectoDesc);
                    $('#inputFecInicio').val(data.fechaInicio);
                    $('#inputFecPrev').val(data.fechaPrevEjec);
                    $('#inputEECC').val(data.empresaColabDesc);
                    $('#btnEditItem').attr('data-item',itemplan);                    
                    $('#selectEstaItem').html(data.estadosList);
                    $('#selectEstaItem').val('').trigger('chosen:updated');  
                    $('#selectEstaItem').val(data.idEstadoPlan).trigger('change');
                    $('#inputFecEjec').val(data.fechaEjec);
                    $('#inputFecTerm').val(data.fechaCan);
                    $('#motivoet').val(data.motivo);
                    $('.rmotivo').val(data.idmotivo).trigger('change');

                if(data.idEstadoPlan==6){
                    $("#contFecTerm label").html("Fecha de Cancelaci�n");
                    $( "select" ).prop( "disabled", true );
                    $("textarea").prop( "disabled", true );
                    $("input").prop( "disabled", true );
                    $( "#motivo_cancelar" ).prop( "disabled", false );
                    $( "#motivo_truncar" ).prop( "disabled", false );
                }
                if(data.idEstadoPlan==10){
                    $("#contFecTerm label").html("Fecha Trunca");
                    //$("select").prop( "disabled", true );
                    $("textarea").prop( "disabled", true );
                    $("input").prop( "disabled", true );
                    $( "#motivo_cancelar" ).prop( "disabled", false );
                    $( "#motivo_truncar" ).prop( "disabled", false );
                }

                    if(data.idEstadoPlan==1||data.idEstadoPlan==2||data.idEstadoPlan==3||data.idEstadoPlan==7||data.idEstadoPlan==8||data.idEstadoPlan==10){
                    $('#select_feix').css("display","block");
                    $("#motivoe").css("display","none") ;    
                    }else{
                    $('#select_feix').css("display","none");
                    $("#motivoe").css("display","block");

                    }
                    
                    $('#edi-Item').modal('toggle');
                }else if(data.error == 1){                  
                    mostrarNotificacion('error','Error el asociar Grafo',data.msj);
                }
              })
              .fail(function(jqXHR, textStatus, errorThrown) {
                 mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
              })
              .always(function() {
             
            });
        
        }
        
        var encodeRoute = null;
        
        function editPorcentaje(component){        
            
            var itemplan = $(component).attr('data-itemplan');
            var jefatura = $(component).attr('data-jefatura');
            $.ajax({
                type    :   'POST',
                'url'   :   'getEsPor2',
                data    :   {itemplan   :   itemplan},
                'async' :   false
            })
            .done(function(data){
                
                var data    =   JSON.parse(data);
                if(data.error == 0){
                    $('#tituloModalPor').html('ITEMPLAN : '+itemplan); 
                    $('#contChoice').html(data.htmlEstaciones); 
                    var array = JSON.parse(data.listaEstaPor);                                      
                    $.each(array, function(i, item) {
                        
                        $('#selectEstacion'+array[i].idEstacion).val('').trigger('chosen:updated');  
                        $('#selectEstacion'+array[i].idEstacion).val(array[i].porcentaje).trigger('change');
                        $('#selectEstacion'+array[i].idEstacion).select2({ width: '100%' });
                        $('#selectCuadrilla'+array[i].idEstacion).val('').trigger('chosen:updated');    
                        $('#selectCuadrilla'+array[i].idEstacion).val('').trigger('change');
                        $('#selectCuadrilla'+array[i].idEstacion).select2({ width: '100%' });
                         
                        
                    });         
                    $('#btnEditPorcent').attr('data-item',itemplan); 
                    $('#editPorcentaje').bootstrapValidator('resetForm', true);  
                    if(jefatura=='LIMA'){
                        $('#goDetalle').show();
                        encodeRoute = data.encode;
                        console.log(encodeRoute);
                    }else{
                        $('#goDetalle').hide();
                    }  
                    $('#edi-porcentajes').modal('toggle');
                }else if(data.error == 1){                  
                    mostrarNotificacion('error','Error el asociar Grafo',data.msj);
                }
              })
              .fail(function(jqXHR, textStatus, errorThrown) {
                 mostrarNotificacion('error','Error al insertar',errorThrown+ '. Estado: '+textStatus);
              })
              .always(function() {
             
            });
       
        }

        function goSinFix(){
            console.log('https://sin-fix.com/app/controlador/ingresar.php?redirect='+encodeRoute);
            window.open('https://sin-fix.com/app/controlador/ingresar.php?redirect='+encodeRoute, '_blank');
            //window.location.replace('https://sin-fix.com/app/controlador/ingresar.php?redirect='+encodeRoute); 
        }
        
        $('#editPorcentaje')
        .bootstrapValidator({
            container: '#mensajeForm2',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            excluded: ':disabled'
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            
            
            var $form    = $(e.target),
                formData = new FormData(),
                params   = $form.serializeArray(),
                bv       = $form.data('bootstrapValidator');     
                
                $.each(params, function(i, val) {
                    formData.append(val.name, val.value);
                });
                
                var itemplan = $('#btnEditPorcent').attr('data-item');
                formData.append('itemplan', itemplan);
                
                $.ajax({
                    data: formData,
                    url: "savePorc2",
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST'
                })
                  .done(function(data) {                     
                        data = JSON.parse(data);
                        if(data.error == 0){                            
                            $('#edi-porcentajes').modal('toggle');  
                            mostrarNotificacion('success','Operaci�n �xitosa.', 'Se registro correcamente!');
                        }else if(data.error == 1){
                            mostrarNotificacion('error','Error','Comun�quese con alguna persona a cargo :(');
                        }
                  })
                  .fail(function(jqXHR, textStatus, errorThrown) {
                    mostrarNotificacion('error','Error','Comun�quese con alguna persona a cargo :(');
                  })
                  .always(function() {
                     
                });
               
            
        });
        
        function filtrarTabla(){
            var subProy = $.trim($('#selectSubProy').val()); 
             var eecc = $.trim($('#selectEECC').val());             
            var itemplan = $.trim($('#selectItemPlan').val());  
            
            $.ajax({
                type    :   'POST',
                'url'   :   'getItemPlanEdit2',
                data    :   {subProy  : subProy,
                            eecc      : eecc,                         
                            itemplanFil : itemplan},
                'async' :   false
            })
            .done(function(data){
                var data    =   JSON.parse(data);
                if(data.error == 0){                                           
                    $('#contTabla').html(data.tablaAsigGrafo)
                    initDataTable('#data-table');
                }else if(data.error == 1){
                    
                    mostrarNotificacion('error','Hubo problemas al filtrar los datos!');
                }
              });
        }        
        </script>
    </body>
</html>