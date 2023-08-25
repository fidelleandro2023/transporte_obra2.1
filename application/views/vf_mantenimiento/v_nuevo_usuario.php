<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

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
                <a href="https://www.movistar.com.pe/" title="Entel Perú"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/7.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>hey, how are you doing.</p>
                    </div>
                </a>

                <a class="listview__item chat__available">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/5.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>hmm...</p>
                    </div>
                </a>

                <a class="listview__item chat__away">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/3.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>all good</p>
                    </div>
                </a>

                <a class="listview__item">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/8.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>morbi leo risus portaac consectetur vestibulum at eros.</p>
                    </div>
                </a>

                <a class="listview__item">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/6.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>fusce dapibus</p>
                    </div>
                </a>

                <a class="listview__item chat__busy">
                    <img src="<?php echo base_url(); ?>public/demo/img/profile-pics/9.jpg" class="listview__img" alt="">

                    <div class="listview__content">
                        <div class="listview__heading">Jeannette Lawson</div>
                        <p>cras mattis consectetur purus sit amet fermentum.</p>
                    </div>
                </a>
            </div>

            <a href="messages.html" class="btn btn--action btn--fixed btn-danger"><i class="zmdi zmdi-plus"></i></a>
        </aside>

        <section class="content content--full">
            <div>
                <?php echo $notify ?>
            </div>
            <div>
                <a href="mUsuario" class="btn btn-primary">VOLVER</a>
            </div>



            <div class="content__inner">
                <h2>NUEVO USUARIO</h2>

                <div class="card">


                    <div class="card-block">

                        <form action="enviarDatosUsuario" method="POST" enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Datos Personales</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="nombre">Nombres:</label>
                                                <input type="text" class="form-control" name="nombres" id="nombres" required style="border-bottom: 1px solid lightgrey">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="paterno">Apellido Paterno:</label>
                                                <input type="text" class="form-control" name="paterno" id="paterno" required style="border-bottom: 1px solid lightgrey">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="materno">Apellido Materno:</label>
                                                <input type="text" class="form-control" name="materno" id="materno" style="border-bottom: 1px solid lightgrey">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="dni">DNI:</label>
                                                <input type="text" class="form-control" name="dni" id="dni" required style="border-bottom: 1px solid lightgrey">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">Email:</label>
                                                <input type="email" class="form-control" name="email" id="email" style="border-bottom: 1px solid lightgrey">
                                                <i class="form-group__bar"></i>
                                            </div>
                                        </div>


                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <h4>Perfil y Accesos</h4>
                                    <hr>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="perfil">Seleccione Perfil:</label>


                                                <select id="perfil" name="perfil[]" class="select2" multiple required>
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    foreach ($listaperfiles->result() as $row) {
                                                    ?>
                                                        <option value="<?php echo $row->id_perfil ?>"><?php echo $row->desc_perfil ?></option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="empresa">Seleccione Empresa:</label>


                                                <select id="empresa" name="empresa" class="select2">
                                                    <option value="">&nbsp;</option>
                                                    <?php
                                                    foreach ($listaeecc->result() as $row) {
                                                    ?>
                                                        <option value="<?php echo $row->idEmpresaColab ?>"><?php echo $row->empresaColabDesc ?></option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="zonas">Seleccione Zonas:</label>


                                                <select id="zonas" name="zonas[]" class="select2" multiple required>
                                                    <option value="">&nbsp;</option>
                                                    <option value="">TODAS</option>
                                                    <?php
                                                    foreach ($listazonas->result() as $row) {
                                                    ?>
                                                        <option value="<?php echo $row->idZonal ?>"><?php echo $row->zonalDesc ?></option>
                                                    <?php } ?>

                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="checkbox" class="form-check-input" name="accesoSinFix" id="accesoSinFix" style="border-bottom: 1px solid lightgrey"> <label for="accesoSINFIX">Acceso a SINFIX </label>
                                            </div>
                                        </div>
                                    </div>




                                </div>

                                <div class="col-md-12 text-center">
                                    <h4>Agregar firma digital <span>.jpg .png</span></h4>
                                    <input class="mt-2" type="file" id="fileFirma" name="fileFirma" accept="image/jpg, image/jpeg, image/png">
                                </div>
                            </div>

                            <button class="btn btn-primary waves-effect" id="refresh">Registrar</button>

                        </form>

                    </div>
                </div>
            </div>



            <footer class="footer hidden-xs-down">
                <p>© Material Admin Responsive. All rights reserved.</p>

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





    <!-- Javascript -->
    <!-- ..vendors -->
    <script src="<?php echo base_url(); ?>public/bower_components/jquery/dist/jquery.min.js"></script>
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

    <!--  tables -->
    <script src="<?php echo base_url(); ?>public/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/jszip/dist/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

    <script src="<?php echo base_url(); ?>public/bower_components/dropzone/dist/min/dropzone.min.js"></script>
    <!-- Charts and maps-->
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
    <script src="<?php echo base_url(); ?>public/js/Utils.js"></script>
    <script type="text/javascript">
        function recogeUsuario() {
            console.log('ok..');

            var nombres = $.trim($('#nombres').val());
            var paterno = $.trim($('#paterno').val());
            var materno = $.trim($('#materno').val());
            var dni = $.trim($('#dni').val());
            var email = $.trim($('#email').val());

            console.log(nombres);
            console.log(paterno);
            console.log(materno);
            console.log(dni);
            console.log(email);
            /*
            $.ajax({
         	    	type	:	'POST',
         	    	'url'	:	'getusuario',
         	    	data	:	{nombres	:	nombres,
          	    	             paterno : paterno,
                                 materno :   materno,
                                 dni	:	dni,
          	    	             email : email,
                                 pass :   pass
            	    	           },
         	    	'async'	:	false
         	    })*/


        }
    </script>

    <!--<script type="text/javascript">
        $(document).ready(function() {
        $('#refresh').click(function() {
            alertify.success("Insertado Correctamente");
            window.setTimeout('location.reload()', 700);
        });
        });
        </script>-->

    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/bootstrap.min.css" />
</body>


</html>