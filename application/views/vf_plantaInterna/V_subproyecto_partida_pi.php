<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
         <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/select2/dist/css/select2.min.css">
        <!-- App styles -->
         <!-- Large -->
        <style type="text/css">
            .select2-dropdown{
                z-index:9001;
            }
        </style>
       
                <link rel="stylesheet" href="<?php echo base_url();?>public/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.css"></link>
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
                   <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                                    <h2>MANTENIMIENTO SUBPROYECTO PARTIDAS PI</h2>
                                    
		   				                    <div class="card">
                                                
                                                <div class="card-block">                           
                                                    <div>
                                                        <a onclick="addPartida()" style="background-color: var(--verde_telefonica); color: white;" class="btn btn-primary" >AGREGAR PARTIDA</a>
                                                    </div>              
                                                    <div id="contTabla" class="table-responsive">
                                                            <?php echo $listartabla ?>
                                                    </div>
                                                </div>
                                            </div>
		   				                </div>
                                            
                                            
                                            
                                            
                                            
			                <footer class="footer hidden-xs-down">
			                    <p>Â© Material Admin Responsive. All rights reserved.</p>

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

       
        
        <div class="modal fade" id="modalRegistrarSubproPartida">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">REGISTRAR PARTIDA</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formAddSubproPartida" method="post" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>CODIGO</label>
                                            <input id="inputCodigoPartida" name="inputCodigoPartida" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>BAREMO</label>
                                            <input id="inputBaremo" name="inputBaremo" type="number" step="0.01" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                            <i class="form-group__bar"></i>
                                    </div>
                                    </div>                      
                                </div>
                                
                                 <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                         <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>PARTIDA</label>
                                            <input id="inputDescripcion" name="inputDescripcion" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>
                                 </div>
                                
                                
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                         <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>KIT DE MATERIALES</label>
                                            <input id="inputKitMaterial" name="inputKitMaterial" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>                     
                                </div>
                                <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>COSTO MATERIAL</label>
                                            <input id="inputCostoMaterial" name="inputCostoMaterial" type="number" step="0.01" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                            <i class="form-group__bar"></i>
                                    </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                         <label>SUBPROYECTO</label>

                                             <select id="selectSubproy" name="selectSubproy[]" class="select2" multiple required>
                                                <option>&nbsp;</option>
                                                <?php foreach($listasubproyecto->result() as $row){ ?> 
                                                 <option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
                                                    <?php }?> 
                                             </select>
                                    </div>
                                </div>
                                
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

        <div class="modal fade" id="modalEditarSubproPartida">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="margin: auto;">
                        <h5 style="font-weight: bold;" class="modal-title pull-left">EDITAR PARTIDA</h5>
                    </div>
                    <div class="modal-body">
                    <form id="formEditSubproPartida" method="post" class="form-horizontal"> 
                          <div class="row">

                                <div class="col-sm-12 col-md-12">
                                        <div class="form-group">                    
                                            <input id="inputidPartida" name="inputidPartida" type="hidden" class="form-control">
                                         </div>                            
                                        <div class="form-group">
                                             <div id="contInputCorreP" class="form-group has-feedback" style="">
                                                <label >PARTIDA</label>
                                                <input id="inputDescripcion2" name="inputDescripcion2" type="text" class="form-control">

                                                <div style="display:none">
                                                     <select id="selectSubproyAux" name="selectSubproyAux[]" class="select2 form-control" multiple required>
                                                    <option>&nbsp;</option>
                                                    <?php foreach($listasubproyecto->result() as $row){ ?> 
                                                     <option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
                                                        <?php }?>                                          
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                              
                            
                                                                     
                                    <div class="form-group">
                                         <div id="contInputCorreP" class="form-group has-feedback" style="">
                                            <label>KIT DE MATERIALES</label>
                                            <input id="inputKitMaterial2" name="inputKitMaterial2" type="text" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP"></i>
                                            <i class="form-group__bar"></i>
                                        </div>
                                    </div>

                                   
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>BAREMO</label>
                                            <input id="inputBaremo2" name="inputBaremo2" type="number" step="0.01" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                            <i class="form-group__bar"></i>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                     <div id="contInputCorreP" class="form-group has-feedback" style="">
                                        <label>COSTO MATERIAL</label>
                                            <input id="inputCostoMaterial2" name="inputCostoMaterial2" type="number" step="0.01" class="form-control"><i class="form-control-feedback" data-bv-icon-for="inputCorreP" ></i>
                                            <i class="form-group__bar"></i>
                                    </div>
                                    </div>
                                </div>
                                 <div class="col-sm-12 col-md-12">
                                 <div class="form-group">
                                         <label>SUBPROYECTO</label>
                                            <select id="selectSubproy2" name="selectSubproy2[]" class="select2 form-control" multiple required>
                                                <option>&nbsp;</option>
                                                <?php foreach($listasubproyecto->result() as $row){ ?> 
                                                 <option value="<?php echo $row->idSubProyecto ?>"><?php echo $row->subProyectoDesc ?></option>
                                                    <?php }?>                                          
                                                </select>
                                    </div>
                                    </div>
                                
                                
                                
                            </div>  
                        <div id="mensajeForm2"></div>  
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

       
       

        <!-- Javascript -->
        <!-- ..vendors -->

        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>

        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

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
        

        <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
        <!-- Charts and maps-->
        <script src="<?php echo base_url();?>public/demo/js/flot-charts/curved-line.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/flot-charts/line.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/flot-charts/chart-tooltips.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
        <script src="<?php echo base_url();?>public/demo/js/jqvmap.js"></script>
        
        <!-- App functions and actions -->
        
        
        
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>

        <script type="text/javascript">


            function existeCodigoPartida(codigo){
                var result = $.ajax({
                    type : "POST",
                    'url' : 'valcodPartida',
                    data : {
                        'codigo' : codigo
                    },
                    'async' : false
                }).responseText;
                return result;
            }

            function existeDescripcionPartida(partida){
                var result = $.ajax({
                    type : "POST",
                    'url' : 'valNomPartida',
                    data : {
                        'partida' : partida
                    },
                    'async' : false
                }).responseText;
                return result;
            }


            

        function editPartida(component){
            
            var id = $(component).attr('data-id');
        
              $.ajax({
                type    :   'POST',
                'url'   :   'getInfoPartida',
                data    :   { id : id },
                'async' :   false
            }).done(function(data){
                var data = JSON.parse(data);                    
                               
                $('#formEditSubproPartida').bootstrapValidator('resetForm', true); 

                 var subproy = data.subproyecto.split(',');

                $('#inputDescripcion2').val(data.descripcion);
                $('#inputKitMaterial2').val(data.kitmaterial);
                $('#selectSubproy2').val(subproy).trigger('change');
                $('#selectSubproyAux').val(subproy).trigger('change');


                $('#inputBaremo2').val(data.baremo);
                $('#inputCostoMaterial2').val(data.costomaterial);
                 $('#inputidPartida').val(id);
                   $('#mensajeForm2').html('');              
                $('#btnEdit').attr('data-id',id);               
                $('#modalEditarSubproPartida').modal('toggle'); //abrirl modal          
            })
            
        }
        
        function addPartida(){
                
                /*habilitacion campos de creacion*/
                 $('#mensajeForm').html('');
                $('#inputCodigoPartida').val('');
                $('#inputDescripcion').val('');
                $('#inputKitMaterial').val('');
                $('#inputBaremo').val('');
                $('#inputCostoMaterial').val('');
                 $('#selectSubproy').val('').trigger('change');
                              
                $('#formAddSubproPartida').bootstrapValidator('resetForm', true); 
                $('#modalRegistrarSubproPartida').modal('toggle'); //abrirl modal           
        }
            
        
        $('#formAddSubproPartida')
            .bootstrapValidator({
                container: '#mensajeForm',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    inputCodigoPartida: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar un codigo de partida.</p>'
                            },
                             callback: {
                                   message: '<p style="color:red">(*) El código de partida ya existe.</p>',
                                    callback: function(value, validator){
                                            var result = existeCodigoPartida(value);
                                            if(result == '1'){//Existe
                                                return false;
                                            }else{
                                                return true;
                                            }                                 
                                    }
                             }
                        }
                    },
                     inputDescripcion: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar una descripcion.</p>'
                            }
                        }
                    },
                    selectSubproy: {
                        validators: {
                            notEmpty: {
                               message: '<p style="color:red">(*) Debe sleccionar el(los) subproyectos.</p>'
                            }
                        }
                    },

                    inputBaremo: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el baremo.</p>'
                            }
                        }
                    },

                    inputCostoMaterial: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe ingresar el monto de los materiales.</p>'
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
               
                    $('#mensajeForm').html('');

                    var dato=$('#inputDescripcion').val();
                    var result = existeDescripcionPartida(dato);
                    if(result == '1'){
                         $('#mensajeForm').html('<p style="color:red">(*) Existe un registro con la misma descripcion  .</p>');
                         return false;
                    }    
                
               
                    $.each(params, function(i, val) {
                        formData.append(val.name, val.value);
                    });

                    swal({
                        title: 'Est&aacute; seguro de registrar la partida?',
                        text: 'Asegurese de validar la informacion seleccionada!',
                        type: 'warning',
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'Aceptar',
                        cancelButtonClass: 'btn btn-secondary',
                        cancelButtonText: 'Cancelar'
                    }).then(function(){

                         $.ajax({
                            data: formData,
                            url: "addSubProyPartida",
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'POST'
                        })
                          .done(function(data) {  
                                    data = JSON.parse(data);
                                if(data.error == 0){                                                            
                                    $('#contTabla').html(data.listartabla);                                         
                                    initDataTable('#data-table');
                                    $('#modalRegistrarSubproPartida').modal('toggle');
                                    mostrarNotificacion('success','Operación exitosa.', 'Se registro correcamente!');
                                }else if(data.error == 1){
                                    mostrarNotificacion('error','Error','No se inserto el permiso por perfil');
                                }
                          })
                          .fail(function(jqXHR, textStatus, errorThrown) {
                            mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
                          })
                          .always(function() {
                             
                        });

                    }); 
            });




$('#formEditSubproPartida').on('success.form.bv', function(e) {

                     e.preventDefault();             
                
                var $form    = $(e.target),
                    formData = new FormData(),
                    params   = $form.serializeArray(),
                    bv       = $form.data('bootstrapValidator');     

                var id_partida = $('#btnEdit').attr('data-id');
                formData.append('id', id_partida);
                
                    $.each(params, function(i, val) {
                        formData.append(val.name, val.value);
                    });

                     $('#mensajeForm2').html('');
                     
                    var descrip=$('#inputDescripcion2').val();
                    var baremo=$('#inputBaremo2').val();
                    var costom=$('#inputCostoMaterial2').val();
                                     
                    if (descrip.trim()==""){
                  	 $('#mensajeForm2').html('<p style="color:red">(*) Debe ingresar el nombre de la partida.</p>');
                            return false;
                    } 
                                                            
                    if (baremo.trim()==""){
                  	 $('#mensajeForm2').html('<p style="color:red">(*) Debe ingresar el baremo.</p>');
                            return false;
                    } 
                                        
                    if (costom.trim()==""){
                  	 $('#mensajeForm2').html('<p style="color:red">(*) Debe ingresar el costo del material.</p>');
                            return false;
                    } 
                    
                    var subproyaux = $('#selectSubproyAux').val();
                    var nuevosubproy = $('#selectSubproy2').val();
	  

                   for (i = 0; i < subproyaux.length; i++) { 
                        var idsubac=subproyaux[i];
                        var idnuevosub=nuevosubproy.indexOf(idsubac);
                        if(idnuevosub<0){
                            $('#mensajeForm2').html('<p style="color:red">(*) Ud. no puede eliminar subproyectos ya registrados.</p>');
                            return false;

                        }
                    }



                swal({
                    title: 'Est&aacute; seguro de editar la partida?',
                    text: 'Asegurese de validar la informacion seleccionada!',
                    type: 'warning',
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonText: 'Aceptar',
                    cancelButtonClass: 'btn btn-secondary',
                    cancelButtonText: 'Cancelar'
                }).then(function(){

                    
                    
                    $.ajax({
                        data: formData,
                        url: "editSubProyPartida",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                      .done(function(data) {  
                                data = JSON.parse(data);
                            if(data.error == 0){                                                            
                                $('#contTabla').html(data.listartabla);                                         
                                initDataTable('#data-table');
                                $('#modalEditarSubproPartida').modal('toggle');
                                mostrarNotificacion('success','Operación exitosa.', 'Se registro correcamente!');
                            }else if(data.error == 1){
                                mostrarNotificacion('error','Error','No se modificó el permiso por perfil');
                            }
                      })
                      .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error','Error','Comuníquese con alguna persona a cargo :(');
                      })
                      .always(function() {
                         
                    });

                }); 
            });

      
        function desactivar(component){

           

        	swal({
                title: 'Est&aacute; seguro de desactivar?',
                text: 'Asegurese de validar la informacion seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-danger',
                confirmButtonText: 'Si, desactivar!',
                cancelButtonClass: 'btn btn-secondary',
                cancelButtonText: 'Cancelar'
            }).then(function(){

            	var id = $(component).attr('data-id');
                
                console.log(id);
             	             	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'updatedesacPart',
         	    	data	:	{id	:	id},
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data	=	JSON.parse(data);
                    location.reload();
         		  }) ;
            });   
        }
            
        function activar(component){

          

        	swal({
                title: 'Est&aacute; seguro de activar?',
                text: 'Asegurese de validar la informacion seleccionada!',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: 'Si, activar!',
                cancelButtonClass: 'btn btn-secondary',
                cancelButtonText: 'Cancelar'
            }).then(function(){

            	var id = $(component).attr('data-id');
                console.log(id);
             	             	
         	    $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'updateactivPart',
         	    	data	:	{id	:	id},
         	    	'async'	:	false
         	    })
         	    .done(function(data){             	    
         	    	var data	=	JSON.parse(data);
                    location.reload();
         		  }) ;
            });    
        }

 
        </script>
        
        
       
    </body>


</html>