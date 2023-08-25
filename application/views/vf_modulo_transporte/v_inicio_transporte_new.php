<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <link rel="icon" href="public/img/iconos/iconfinder_movistar.png">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="<?php echo base_url();?>public/css/css_bootstrap_4/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
         

    <style type="text/css">
        .dropdown-submenu {
        position: relative;
        }

        .dropdown-submenu>a:after {
        content: "\f0da";
        float: right;
        border: none;
        font-family: 'FontAwesome';
        }

        .dropdown-submenu>.dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: 0px;
        margin-left: 0px;
        }

        body {
        background: #4568DC;
        background: -webkit-linear-gradient(to right, #4568DC, #B06AB3);
        background: linear-gradient(to right, #4568DC, #B06AB3);
        min-height: 100vh;
        }

        code {
        color: #B06AB3;
        background: #fff;
        padding: 0.1rem 0.2rem;
        border-radius: 0.2rem;
        }

        @media (min-width: 991px) {
        .dropdown-menu {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        }
    </style>
    </head>

    <body data-ma-theme="entel">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
            <div class="container">
                <a href="getPanel" class="navbar-brand font-weight-bold">Ir Panel Principal</a>
                <button type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div id="navbarContent" class="collapse navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <?php echo $opciones; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- For demo purpose -->
        <section class="py-5 text-white">
        <div class="container py-4">
            <div class="row">
            <div class="col-lg-9 mx-auto text-center">
                <h1 class="display-4">Bienvenido al M&oacute;dulo de Transporte</h1>
                <p class="lead mb-0">Nuevo m&oacute;dulo de "plan de obras"</p>
                <p class="lead">en donde se administrar&aacute; el proceso de transporte.</p>
            </div>
            </div>
            <div class="row pt-5">
            <div class="col-lg-10 mx-auto">
                <p class="lead">Algunos de las opciones que podr&aacute; ver son:</p>
                <p class="lead">El registro de itemplan, el registro de cotizaci&oacute;n, aprobaci&oacute;n, certificaci&oacute;n, etc</p>
                <p class="lead"></p>
            </div>
            </div>
        </div>
        </section>        
       
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
    <script src="<?php echo base_url();?>public/js/js_bootstrap_4/jquery-3.3.1.slim.min.js"></script>    
    <script src="<?php echo base_url();?>public/js/js_bootstrap_4/bootstrap.bundle.min.js"></script>
    <script>
        $(function() {
        // ------------------------------------------------------- //
        // Multi Level dropdowns
        // ------------------------------------------------------ //
            $("ul.dropdown-menu [data-toggle='dropdown']").on("click", function(event) {
                console.log("ENTAOO");
                event.preventDefault();
                event.stopPropagation();

                $(this).siblings().toggleClass("show");


                if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
                }
                $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                $('.dropdown-submenu .show').removeClass("show");
                });

            });
        });
    </script>
</html>