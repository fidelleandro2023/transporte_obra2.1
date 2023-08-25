<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

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
                <a href="https://www.movistar.com.pe/" title="Entel PerÃº"><img src="<?php echo base_url(); ?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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


            <div class="content__inner">
                <h2>MANTENIMIENTO USUARIO</h2>

                <div class="card">

                    <div class="card-block">
                        <div id="contTabla" class="table-responsive">
                            <?php echo $listartabla ?>
                        </div>
                    </div>
                </div>
            </div>





            <footer class="footer hidden-xs-down">
                <p>Telefónica Del Perú</p>
            </footer>
        </section>
    </main>

    <!-- Large -->
    <style type="text/css">
        .select2-dropdown {
            z-index: 9001;
        }
    </style>

    <div class="modal fade" id="modal-large" tabindex="">
        <div class="modal-dialog modal-slg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left">MODAL EDITAR</h5>
                </div>
                <div class="modal-body">
                    Edite los campos del usuario seleccionado.

                    <div class="card">
                        <div class="card-block">
                            <form id="formActualizarUsuario" method="POST" enctype="multipart/form-data">

                                <!-- Nav pills -->
                                <ul class="nav nav-pills nav-justified">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#mtn1">Datos Personales</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#mtn2">Perfil y Accesos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#mtn3">Datos de Validacion y Certificacion</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane container active" id="mtn1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nombre">Nombres:</label>
                                                    <input type="text" class="form-control" name="nombres" id="nombres" required style="border-bottom: 1px solid lightgrey">
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="paterno">Apellido Paterno:</label>
                                                    <input type="text" class="form-control" name="paterno" id="paterno" required style="border-bottom: 1px solid lightgrey">
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="materno">Apellido Materno:</label>
                                                    <input type="text" class="form-control" name="materno" id="materno" required style="border-bottom: 1px solid lightgrey">
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email:</label>
                                                    <input type="email" class="form-control" name="email" id="email" style="border-bottom: 1px solid lightgrey">
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
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="user">USUARIO:</label>
                                                    <input type="text" class="form-control" name="user" id="user" required style="border-bottom: 1px solid lightgrey">
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pass">Ingrese la nueva contrase&ntilde;a</label>
                                                    <input class="form-control" name="pass" id="pass" style="border-bottom: 1px solid lightgrey" maxlength="12">
                                                    <i class="form-group__bar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="cmbRestriccion">Ingresar Permisos:</label>


                                            <select id="cmbRestriccion" name="cmbRestriccion[]" class="select2" multiple>
                                                <option value="">&nbsp;</option>

                                                <?php
                                                foreach ($listaRestricciones as $row) {
                                                ?>
                                                    <option value="<?php echo $row->id_tipo_restriccion ?>"><?php echo $row->restriccionDesc ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="tab-pane container fade" id="mtn2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">

                                                    <input type="checkbox" class="form-check-input" name="accesoSinFix" id="accesoSinFix" style="border-bottom: 1px solid lightgrey"> <label for="accesoSINFIX">Acceso a SINFIX </label>

                                                </div>
                                            </div>
                                        </div>
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
                                    <div class="tab-pane container fade" id="mtn3">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="zonas">Proyecto:</label>
                                                <select id="cmbProyectoEdit" name="cmbProyectoEdit[]" class="select2" multiple>
                                                    <option value="">Seleccionar Proyecto</option>
                                                    <?php
                                                    foreach ($listaProy as $row) {
                                                    ?>
                                                        <option value="<?php echo $row->idProyecto ?>"><?php echo utf8_decode($row->proyectoDesc) ?></option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="zonas">Jefatura:</label>
                                                <select id="cmbJefatura" name="cmbJefatura[]" class="select2" multiple>
                                                    <option value="">Seleccionar Jefatura</option>
                                                    <?php
                                                    foreach ($listaJefatura as $row) {
                                                    ?>
                                                        <option value="<?php echo $row->idJefatura ?>"><?php echo utf8_decode($row->descripcion) ?></option>
                                                    <?php } ?>

                                                </select>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label for="zonas">Nivel:</label>


                                                <select id="cmbNivel" name="cmbNivel[]" class="select2" multiple>
                                                    <option value=""></option>
                                                    <option value="1">Nivel 1</option>
                                                    <option value="2">Nivel 2</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <h4>Agregar firma digital <span>.jpg .png</span></h4>
                                                <div class="row mt-3 align-items-center">
                                                    <div class="col-md-6 text-center">
                                                        <input class="mt-2" type="file" id="fileFirma" accept="image/jpg, image/jpeg, image/png">
                                                    </div>
                                                    <div class="col-md-6 text-center" id="contImagenFirma">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="mensajeForm" class="text-center"></div>
                                <button class="btn btn-primary waves-effect" id="refresh">Guardar Datos</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



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
    <script src="<?php echo base_url(); ?>public/bower_components/bootstrap-validator/bootstrapValidator.min.js"></script>

    <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript">
        $('#formActualizarUsuario')
            .bootstrapValidator({
                container: '#mensajeForm',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                excluded: ':disabled',
                fields: {
                    nombres: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir el nombre.</p>'
                            }
                        }
                    },
                    user: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir el usuario.</p>'
                            }
                        }
                    },
                    paterno: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir el Apellido Paterno.</p>'
                            }
                        }
                    },
                    materno: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir el Apellido Materno.</p>'
                            }
                        }
                    },
                    dni: {
                        validators: {
                            notEmpty: {
                                message: '<p style="color:red">(*) Debe escribir el Dni.</p>'
                            }
                        }
                    },
                }
            }).on('success.form.bv', function(e) {
                e.preventDefault();

                var $form = $(e.target),
                    formData = new FormData(),
                    params = $form.serializeArray(),
                    bv = $form.data('bootstrapValidator');

                $.each(params, function(i, val) {
                    console.log('name ' + val.name + ' value ' + val.value);
                    formData.append(val.name, val.value);
                });

                let fileFirma = null;
                if ($("#fileFirma")[0].files[0]) {
                    fileFirma = $("#fileFirma")[0].files[0];
                } else {
                    fileFirma = $('#firmaUsuario').data('firma-usuario') || '';
                }

                formData.append('fileFirma', fileFirma);

                $.ajax({
                        data: formData,
                        url: "actualizarUsuario",
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST'
                    })
                    .done(function(data) {
                        data = JSON.parse(data);

                        if (data.error == 0) {
                            mostrarNotificacion('success', 'Operacion exitosa.', data.msj);
                            window.location.reload();
                        } else if (data.error == 1) {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        mostrarNotificacion('error', 'Error', 'Comun�quese con alguna persona a cargo :(');
                    });
            });


        function editar(component) {

            var id = $(component).attr('data-id');
            $.ajax({
                type: 'POST',
                'url': 'getInfoUsua',
                data: {
                    id: id
                },
                'async': false
            }).done(function(data) {
                var data = JSON.parse(data);

                let imgFirma = data.firma && `
                    <div>
                        <a style="position: absolute; right:0%" class="btn btn-danger" data-id="${id}" onclick="eliminarFirmaUsuario(this)">X</a>
                        <img id="firmaUsuario" class="img-fluid" src="public/img/${data.firma}" width="300" height="150" data-firma-usuario="${data.firma}" />
                    </div>
                `;
                
                $('#contImagenFirma').html(imgFirma);
                $('#mensajeForm').html('');

                $('#nombres').val(data.nombres);
                $('#dni').val(data.dni);
                $('#paterno').val(data.apePaterno);
                $('#materno').val(data.apeMaterno);
                $('#email').val(data.email);
                $('#user').val(data.usuario);

                /**miguel rios 11062018***/
                if (data.accesoSINF != "") {
                    $('#accesoSinFix').prop("checked", true);
                } else {
                    $('#accesoSinFix').prop("checked", false);
                }
                /***/

                var perfiles = data.perfil.split(',');
                $('#perfil').val(perfiles).trigger('change');

                var empresa = data.empresa.split(',');
                $('#empresa').val(empresa).trigger('change');

                var zonas = data.zonas.split(',');
                $('#zonas').val(zonas).trigger('change');

                console.log(data.arrayNivelValidacion);
                var niveles = data.arrayNivelValidacion.split(',');
                $('#cmbNivel').val(niveles).trigger('change');

                var jefaturas = data.arrayJefatura.split(',');
                $('#cmbJefatura').val(jefaturas).trigger('change');

                var idProyecto = data.arrayIdProyecto.split(',');;
                $('#cmbProyectoEdit').val(idProyecto).trigger('change');

                var restricciones = data.restricciones.split(',');
                $('#cmbRestriccion').val(restricciones).trigger('change');
            })

        }

        function eliminarFirmaUsuario(element) {
            let {id} = $(element).data();

            console.log(id);
        }

        function recogeUsuario() {
            console.log('ok');
            var nombres = $.trim($('#nombres').val());
            var paterno = $.trim($('#paterno').val());
            var materno = $.trim($('#materno').val());
            var dni = $.trim($('#dni').val());
            var email = $.trim($('#email').val());
            var pass = $.trim($('#pass').val());

            console.log(nombres);
            console.log(paterno);
            console.log(materno);
            console.log(dni);
            console.log(email);
            console.log(pass);

            $.ajax({
                type: 'POST',
                'url': 'getusuario',
                data: {
                    nombres: nombres,
                    paterno: paterno,
                    materno: materno,
                    dni: dni,
                    email: email,
                    pass: pass
                },
                'async': false
            })


        }
    </script>

    <script>
        function desactivar(component) {

            //console.log('desactivar');

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
            }).then(function() {

                var id = $(component).attr('data-id');

                //console.log(id);

                $.ajax({
                        type: 'POST',
                        'url': 'updatedesac',
                        data: {
                            id: id
                        },
                        'async': false
                    })
                    .done(function(data) {
                        var data = JSON.parse(data);
                        location.reload();
                    });
            });
        }

        function activar(component) {

            //console.log('activar');

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
            }).then(function() {

                var id = $(component).attr('data-id');
                //console.log(id);

                $.ajax({
                        type: 'POST',
                        'url': 'updateactiv',
                        data: {
                            id: id
                        },
                        'async': false
                    })
                    .done(function(data) {
                        var data = JSON.parse(data);
                        location.reload();
                    });
            });
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