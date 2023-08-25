<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  
<!DOCTYPE html>
<html lang="en">


    <head>
        <title><?php echo NAME_WEB_PO;?></title>
        <meta charset="ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="<?php echo COLOR_BARRA?>">
        <link rel="icon" href="<?php echo IMG_MOVISTAR_CABECERA; ?>">

        <!-- plugins -->
        <link href="<?php echo RUTA_PLUGINS?>bootstrap/css/bootstrap.min.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
		<link href="<?php echo RUTA_PLUGINS;?>b_select/css/bootstrap-select.min.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
        <link href="<?php echo RUTA_PLUGINS?>mdl/css/material.indigo.min.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
        <link href="<?php echo RUTA_PLUGINS?>mdl/css/material.min.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
        <link href="<?php echo RUTA_FONTS?>roboto.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">       
        <link href="<?php echo RUTA_FONTS?>material-icons.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
        <link href="<?php echo RUTA_FONTS?>font-awesome.min.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
        <link href="<?php echo RUTA_PLUGINS;?>toaster/toastr.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">

        <!-- css -->
        <link href="<?php echo RUTA_CSS?>m-p.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
        <link href="<?php echo RUTA_CSS?>login.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
        <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/utils.css">  -->
    </head>

    <body data-ma-theme="entel">
        <div class="page-loader">
            <svg x="0" y="0" width="258" height="258">
                <g clip-path="url(#clip-path)">
                    <path class="tree" id="g" />
                </g>
    
                <clipPath id="clip-path">  
                    <path id="path" class="circle-mask"/>
                </clipPath>   
            </svg>
        </div>
        <div class="t_login">
            <div class="header-login"></div>
            <div class="container p-0">
                <div class="card"></div>
                <div class="card card-hover">
                    <h1 class="title">
                        <a href="http://www.movistar.com.pe/home" class="navigation__logo"> 
                            <svg id="logo-movistar-svg" x="0px" y="0px" width="52px" height="38px" viewBox="0 0 52 38" class="navigation__logo--svg"> 
                            <path id="logo-movistar" fill="#5bc500" d="M9.835,4.473c-2.424,0.038-6.898,1.233-8.942,9.58c-0.891,3.638-1.235,7.429-0.473,11.94c0.703,4.162,1.948,7.754,2.787,9.733c0.29,0.683,0.738,1.394,1.084,1.832c0.996,1.259,2.654,1.179,3.35,0.835c0.76-0.374,1.633-1.276,1.317-3.337c-0.153-0.996-0.593-2.452-0.841-3.263c-0.761-2.484-1.773-5.482-1.861-7.617c-0.119-2.857,1.008-3.23,1.756-3.396c1.258-0.277,2.312,1.104,3.314,2.835c1.195,2.066,3.245,5.728,4.916,8.522c1.509,2.524,4.293,5.227,8.765,5.042c4.56-0.19,7.92-1.93,9.651-7.405c1.295-4.097,2.178-7.159,3.6-10.294c1.633-3.605,3.813-5.535,5.648-4.946c1.704,0.548,2.129,2.213,2.15,4.661c0.018,2.166-0.233,4.554-0.427,6.308c-0.071,0.637-0.199,1.916-0.147,2.628c0.103,1.397,0.708,2.794,2.282,3.017c1.676,0.238,3.021-1.102,3.558-2.721c0.212-0.64,0.393-1.615,0.49-2.309c0.493-3.5,0.621-5.852,0.398-9.433c-0.259-4.188-1.078-8.005-2.508-11.31c-1.368-3.159-3.565-5.186-6.381-5.364c-3.119-0.197-6.698,1.872-8.575,5.886c-1.73,3.7-3.115,7.499-3.955,9.437c-0.851,1.967-2.103,3.179-4.027,3.382c-2.353,0.246-4.381-1.462-5.866-3.897c-1.295-2.123-3.86-6.165-5.233-7.523C14.375,6.021,12.902,4.425,9.835,4.473"></path> 
                            </svg> <span class="navigation__logo-text"> 
                            <svg id="logo-movistar-text-svg" x="0px" y="0px" width="136px" height="24px" viewBox="0 0 136 24" class="navigation__logo-text--svg"> 
                            <path id="logo-movistar-text"  fill="#5bc500" d="M118.512,4.743c-1.072-0.46-2.701-0.769-4.808-0.769h-0.257c-1.284,0-3.033,0.157-4.717,0.461c-0.285,0.052-0.396,0.197-0.396,0.524v2.403c0,0.3,0.154,0.403,0.495,0.354c1.478-0.222,3.002-0.377,4.173-0.377h0.167c1.658,0,2.998,0.152,3.806,0.696c0.803,0.541,1.201,1.346,1.201,3.096v0.518c-1.217-0.258-2.683-0.427-4.027-0.427h-0.356c-2.24,0-4.343,0.541-5.685,1.793c-1.03,0.962-1.709,2.412-1.709,4.263v0.157c0,3.725,2.572,6.021,7.797,6.021h0.93c2.053,0,4.004-0.611,5.18-1.581c1.35-1.117,1.873-2.769,1.873-5.411v-5.381C122.178,7.405,120.742,5.704,118.512,4.743zM118.176,16.372c0,1.409-0.046,2.238-0.557,2.825c-0.543,0.623-1.504,0.918-2.807,0.918h-0.633c-2.404,0-3.803-0.965-3.803-2.816c0-0.846,0.334-1.601,0.852-2.047c0.609-0.525,1.502-0.782,3.033-0.782h0.34c1.158,0,2.479,0.116,3.574,0.404V16.372zM134.898,3.973h-2.153c-3.095,0-5.511,1.072-6.546,3.317c-0.447,0.967-0.586,2.263-0.586,3.662V22.75c0,0.262,0.127,0.367,0.395,0.367h3.217c0.268,0,0.387-0.105,0.387-0.367v-9.869c0-1.394,0.029-2.409,0.101-3.036c0.183-1.635,1.177-2.405,3.489-2.405h1.712c0.276,0,0.397-0.142,0.397-0.408V4.38C135.311,4.108,135.177,3.973,134.898,3.973zM57.755,23.117h3.484c0.3,0,0.432-0.098,0.555-0.328c2.257-4.247,5.708-11.504,5.944-18.084c0.008-0.262-0.101-0.374-0.441-0.374l-3.246,0.001c-0.378,0-0.482,0.121-0.503,0.458c-0.15,2.398-0.659,5.182-1.397,7.668c-0.763,2.571-1.733,5.02-2.663,6.84c-0.93-1.82-1.901-4.269-2.663-6.84c-0.744-2.502-1.256-5.306-1.401-7.715c-0.022-0.342-0.206-0.411-0.538-0.411h-3.268c-0.346,0-0.442,0.161-0.416,0.512c0.458,6.447,2.463,11.455,5.942,17.959C57.265,23.032,57.45,23.117,57.755,23.117zM83.626,14.693c0.867,0.321,1.588,0.587,2.447,0.892c1.84,0.654,2.627,1.172,2.627,2.297c0,1.456-1.226,2.229-4.001,2.229h-0.102c-1.564,0-3.359-0.309-4.936-0.813c-0.326-0.104-0.505,0.02-0.505,0.347v2.399c0,0.301,0.051,0.484,0.389,0.601c1.505,0.52,3.582,0.811,5.277,0.811h0.129c4.929,0,7.603-2.191,7.603-5.738v-0.052c0-1.368-0.451-2.606-1.289-3.431c-0.822-0.809-2.072-1.436-4-2.152c-1.027-0.382-1.826-0.677-2.539-0.938c-1.603-0.585-2.052-1.135-2.052-1.959c0-1.361,1.335-1.895,3.483-1.895l0.164,0.001c1.24,0,2.74,0.178,4.562,0.509c0.337,0.062,0.528-0.013,0.528-0.36V4.976c0-0.324-0.088-0.415-0.422-0.486c-1.184-0.251-3.332-0.515-4.538-0.515h-0.29c-2.516,0-4.383,0.468-5.625,1.552c-1.012,0.88-1.688,2.212-1.688,3.803v0.114c0,1.474,0.512,2.663,1.387,3.472C81.055,13.672,82.157,14.149,83.626,14.693zM103.424,23.117c0.274,0,0.426-0.149,0.426-0.392v-2.679c0-0.241-0.151-0.391-0.426-0.391h-0.427c-1.276,0-2.062-0.309-2.541-0.813c-0.636-0.669-0.767-1.755-0.767-3.042V7.663h3.539c0.267,0,0.399-0.104,0.399-0.365V4.697c0-0.262-0.133-0.363-0.399-0.363h-3.539V0.962c0-0.262-0.133-0.367-0.4-0.367h-3.217c-0.268,0-0.391,0.105-0.391,0.367v15.057c0,2.621,0.52,4.293,1.672,5.438c1.082,1.073,2.771,1.66,5.217,1.66H103.424zM21.103,3.975h-0.971c-2.794,0-4.649,0.742-5.548,1.844h-0.021c-0.899-1.102-2.87-1.844-5.664-1.844H7.901c-3.15,0-5.731,1.096-6.768,3.341c-0.447,0.967-0.592,2.263-0.592,3.663v11.708c0,0.259,0.15,0.431,0.413,0.431h3.172c0.261,0,0.411-0.172,0.411-0.431l0.007-9.752c0-1.396,0.033-2.409,0.104-3.034C4.83,8.264,6.044,7.469,8.36,7.469h0.265c2.313,0,3.586,0.795,3.767,2.432c0.07,0.625,0.104,1.639,0.104,3.034v9.757c0,0.257,0.158,0.426,0.419,0.426h3.18c0.261,0,0.41-0.203,0.41-0.46l0.004-9.723c0-1.396,0.034-2.409,0.104-3.034c0.182-1.637,1.451-2.432,3.767-2.432h0.266c2.315,0,3.527,0.795,3.711,2.432c0.068,0.625,0.101,1.639,0.101,3.034v9.757c0,0.266,0.155,0.426,0.417,0.426h3.18c0.261,0,0.409-0.203,0.409-0.46V10.979c0-1.4-0.149-2.696-0.594-3.663C26.835,5.07,24.251,3.975,21.103,3.975zM48.444,18.898c0.387-1.278,0.603-2.748,0.603-5.182c0-2.432-0.216-3.907-0.603-5.182c-0.977-3.263-3.553-4.561-7.732-4.561h-0.498c-4.179,0-6.755,1.298-7.737,4.561c-0.383,1.274-0.6,2.75-0.6,5.182c0,2.434,0.217,3.903,0.6,5.182c0.982,3.262,3.558,4.562,7.737,4.562h0.498C44.891,23.46,47.467,22.16,48.444,18.898zM44.83,16.457c-0.311,3.045-1.848,3.521-4.317,3.521H40.41c-2.468,0-4.007-0.477-4.313-3.521c-0.098-0.92-0.174-1.681-0.174-2.74c0-1.061,0.076-1.82,0.174-2.741c0.306-3.046,1.845-3.521,4.313-3.521h0.103c2.469,0,4.006,0.476,4.317,3.521c0.097,0.921,0.172,1.681,0.172,2.741C45.002,14.776,44.927,15.537,44.83,16.457zM74.656,4.336h-3.217c-0.269,0-0.396,0.104-0.396,0.366l0.004,18.048c0,0.262,0.127,0.367,0.395,0.367h3.216c0.269,0,0.397-0.105,0.397-0.367L75.051,4.702C75.051,4.44,74.925,4.336,74.656,4.336z"></path> 
                            </svg> </span> 
                        </a>
                    </h1>
                    <div class="m-r-30 m-l-30"> 
                        <div class="input-container" id="user">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="cont_usuario">
                                <input class="mdl-textfield__input" type="text" id="username" autofocus="autofocus" value=""/>
                                <label class="mdl-textfield__label" for="username">Escribe tu usuario o correo</label>
                                <span class="mdl-textfield__error">Ups&#33; Tu usuario y/o contrase&ntilde;a son incorrectas.</span>
                            </div>
                        </div>
                        <div class="input-container" id="passw">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label " id="cont_clave" >
                                <input class="mdl-textfield__input" type="password" id="password" value="" />                            	                                                      
                                <label class="mdl-textfield__label" for="Password">Escribe tu contrase&ntilde;a  </label>
                                <span class="mdl-textfield__error">Ups&#33; Tu usuario y/o contrase&ntilde;a son incorrectas.</span>
                                <a id="showpas"  class="mdl-button mdl-js-button mdl-js-button-ripple-effect see-pass toogle-password"><i  class="mdi mdi-visibility_off text-rigth "></i></a>
                            </div>                        
                        </div>
						<!-- COMENTADO NO EXISTE OTRO FLUJO
                        <div class="mdl-card_actions" >
                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="Antiguo_Flujo">
                                <input type="radio" id="Antiguo_Flujo" class="mdl-radio__button" name="options" onchange="getFlujo(2);" checked/>
                                <span class="mdl-radio__label">Flujo</span>
                            </label>
 
                            <p id="msjNuevoFlujo" style="color: red; display: none;"  class="text-muted text-center"><small style="color: darkgoldenrod;">DEBE SELECCIONAR UN FLUJO</small></p>
                        </div>
						-->
                        <div class="mdl-card_actions" >
                            <button onclick="logear()" name="ingresar" value="Ingresar" id="btnLoginAdminPass"
                                class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised button-login" type="button">Ingresar
                            </button>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" id="cont_usuario">
                            <span class="mdl-textfield__error">Ups&#33; Tu usuario y/o contrase&ntilde;a son incorrectas.</span>
                        </div>
                        <!--<p id="msgErrorTemp" style="color: red;" class="text-muted text-center"><small style="font-size: medium;color: red;font-weight: bold;">EN MANTENIMIENTO - SOLO ACCESO AUTORIZADO</small></p>-->
                        <p id="msgError" style="color: red; display: none;"  class="text-muted text-center"><small style="color: darkgoldenrod;">USUARIO O CLAVE INCORRECTO O SIN AUTORIZACION</small></p>
                        <p id="msgError2" style="color: red; display: none;"  class="text-muted text-center"><small style="color: darkgoldenrod;">INGRESE USUARIO Y CONTRASE&Ntilde;A</small></p>
                        <p id="msgAviso"  style="color: red; display: none;"  class="text-muted text-center"><small style="color: darkgoldenrod;">SI TIENES PROBLEMAS CON EL INGRESO DE TU CONTRASE&Ntilde;A, RECUERDA:</small></p>
                        <p id="msgAviso1" style="color: red; display: none;"  class="text-muted text-left"><small style="color: darkgoldenrod;">1. Presionar la combinaci&oacute;n de teclas "CTRL+H"</small></p>
                        <p id="msgAviso2" style="color: red; display: none;"  class="text-muted text-left"><small style="color: darkgoldenrod;">2. Estando en la opci&oacute;n de historial dar click en "Borrar datos de navegaci&oacute;n"</small></p>
                        <p id="msgAviso3" style="color: red; display: none;"  class="text-muted text-left"><small style="color: darkgoldenrod;">3. Proceda a borrar los datos, una vez realizado vuelva a ingresar su contrase&ntilde;a</small></p>
                    </div>
                </div>
                <div class="card alt">
                    <div class="toggle"></div>
                    <h1 class="title">
                        Recuperar<br>contrase&ntilde;a
                        <div class="close"></div>
                    </h1>
                    <div class="alert alert-danger alert-dismissible m-20 m-b-0 m-t-0" role="alert" id="cont_error_google" style="position: relative; display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Espera!</strong> Error clave! 
                    </div>
                    <div class="input-container p-l-15 p-r-15">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="correo" id="correo" autofocus="autofocus"/>
                            <label class="mdl-textfield__label" for="Password">Escribe tu correo aqu&iacute;</label>
                        </div>
                    </div>
                    <div class="mdl-card_actions again p-l-15 p-r-15 p-t-30">
                        <button onclick="enviarCorreo()" name="reestablecer" value="Reestablecer"
                            class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised">Reestablecer
                        </button>
                    </div>
                </div>   
            </div>
        </div>
      
        

        <!-- Javascript -->
        <!-- Global site tag (gtag.js) - Google Analytics -->

        <!-- Vendors -->
		<script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-1.11.2.min.js?v=<?php echo time();?>"></script>
    	<script src="<?php echo RUTA_JS?>libs/jquery/jquery-migrate-1.2.1.min.js?v=<?php echo time();?>"></script>
    	<script src="<?php echo RUTA_PLUGINS?>mdl/js/material.min.js?v=<?php echo time();?>" defer></script>
    	<script src="<?php echo RUTA_PLUGINS?>bootstrap/js/bootstrap.min.js?v=<?php echo time();?>"/></script>
    	<script src="<?php echo RUTA_PLUGINS?>b_select/js/bootstrap-select.min.js?v=<?php echo time();?>"></script>	
    	<script src="<?php echo RUTA_JS?>libs/spin.js/spin.min.js?v=<?php echo time();?>"></script>
    	<script src="<?php echo RUTA_PLUGINS;?>toaster/toastr.js?v=<?php echo time();?>"></script>
    	<script src="<?php echo RUTA_JS?>libs/autosize/jquery.autosize.min.js?v=<?php echo time();?>"></script>


        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
        
        <script>
            $('.toggle').on('click', function() {
                $('.container').stop().addClass('active');
            });
            $('.close').on('click', function() {
                $('.container').stop().removeClass('active');
            }); 
            $(".toogle-password").click(function() {
                $(this).find('i').toggleClass("mdi-remove_red_eye mdi-visibility_off");
                var input = $(this).parent().find('.mdl-textfield__input');
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                }else {
                    input.attr("type", "password");
                }
            });
        $(document).ready(function() {
            $("#input").click(function () {
                $("#showpas").fadeIn();
            });
        });

        var valSinFix = null;
      
        
        $("#password").keypress(function(e) {
            if(e.which == 13) {
              logear();
            }
         });
         
         $("#confirmpass").keypress(function(e) {
            if(e.which == 13) {
              cambioPass();
            }
         });
        
        var flgPanelGlobal = 2;
        function getFlujo(flgFlujo) {
            flgPanelGlobal = flgFlujo;
        }

        function logear(){
     	   var user  = ($('#username').val()).trim();
    	   var passw = ($('#password').val()).trim();
           
           if(flgPanelGlobal == null || flgPanelGlobal == '') {
               $('#msjNuevoFlujo').show();
               return;
           }
           
           
       	    if(user != '' && user != null && passw != '' && passw != null){console.log(flgPanelGlobal);
       	    	$.ajax({
        		    type: "POST",
        		    'url' : 'Prelogear',
        		    data:   { 
                                user      : user,
                                passwrd   : passw
                            }
        		})
        		.done(function(data) {
        			var data = JSON.parse(data);
                    if(data.flgCP == -1){
                        $('#msgError').show();
                        $('#msgAviso').show();
                        $('#msgAviso1').show();
                        $('#msgAviso2').show();
                        $('#msgAviso3').show();
                    }else if(data.flgCP == 1){
                        $("#l-login").removeClass( "active");
                        $("#l-bypass").removeClass( "active" );
                        $("#l-bypass2").addClass( "active" );
                    }else{
                        ingresar();
                    }
        		});
       	    }else{
       	    	$('#msgError2').show();
       	    }    		
        }

        function muestra_seguridad_clave(clave,formulario){
            var seguridad=seguridad_clave(clave);
            
            
            if(seguridad>=0 && seguridad<=30){
                $(formulario).html('<p style="color:var(--rojo_telefonica)">Nivel de complejidad de clave: '+seguridad+' % </p>');
            }else if(seguridad>30 && seguridad<=40){
                $(formulario).html('<p style="color:var(--morado_telefonica)">Nivel de complejidad de clave: '+seguridad+' % </p>');
            }else if(seguridad>=40 && seguridad<=60){
                $(formulario).html('<p style="color:var(--rosado_telefonica)">Nivel de complejidad de clave: '+seguridad+' % </p>');
            }else if(seguridad>60 && seguridad<=90){
                $(formulario).html('<p style="color:var(--celeste_telefonica)">Nivel de complejidad de clave: '+seguridad+' % </p>');
            }else{
                $(formulario).html('<p style="color:var(--verde_telefonica)">Nivel de complejidad de clave: 100 % </p>');
            }

            $('#verificon').val(seguridad.toString());
                  
            
        }

        function ingresar(){
            var user  = $('#username').val();
                        var passw = $('#password').val();
                        $.ajax({
                                type: "POST",
                                'url' : 'logear',
                                data: { 
                                        user    : user ,
                                        passwrd : passw,
                                        flgPanel : flgPanelGlobal
                                      },
                                'async' : false
                            })
                            .done(function(data) {
                                var data = JSON.parse(data);
                 
                                if(data.flg == 1){
                                    $('#msgError').show();
                                    $('#msgAviso').show();
                                    $('#msgAviso1').show();
                                    $('#msgAviso2').show();
                                    $('#msgAviso3').show();

                                }else if(data.flg == 0){
           
                                    if(data.usuaSinfix==1){
                                        // $("#l-login").removeClass( "active" );
                                        //  $("#l-bypass2").removeClass( "active" );
                                        // $("#l-bypass").addClass( "active" );
                                        valSinFix = data.encode;                        
                                        location.href = data.url;
                                    }else if(data.usuaSinfix == 0){
										location.href = data.url;
                                        //location.reload();
                                    }
                                }
                            })
        }


	 function ingresarCamb(){
            var user  = $('#username').val();
                        var passw = $('#nuevopass').val();
                        $.ajax({
                                type: "POST",
                                'url' : 'logear',
                                data: { user    : user ,
                                        passwrd : passw},
                                'async' : false
                            })
                            .done(function(data) {
                                var data = JSON.parse(data);
                 
                                if(data.flg == 1){
                                    $('#msgError').show();
                                    $('#msgAviso').show();
                                    $('#msgAviso1').show();
                                    $('#msgAviso2').show();
                                    $('#msgAviso3').show();

                                }else if(data.flg == 0){
           
                                    if(data.usuaSinfix==1){
                                        $("#l-login").removeClass( "active" );
                                         $("#l-bypass2").removeClass( "active" );
                                        $("#l-bypass").addClass( "active" );
                                        valSinFix = data.encode;                        
                                        console.log('BYPASS:'+valSinFix);
                                    }else if(data.usuaSinfix == 0){
                                        console.log('reload');
                                        location.reload();
                                    }
                                }
                            })
        }


        function cambioPass(){
            var user  = $('#username').val();
            var dni = $('#dni').val();

             var newpass = $('#nuevopass').val();
             var confpass = $('#confirmpass').val();
             var verifica= $('#verificon').val();


             if (verifica<100){
                $('#msgError4').show();
                return false;
             }
             
             if(newpass!=confpass){
                $('#msgError3').show();
                return false;
             }else{
                $.ajax({
                        type: "POST",
                        'url' : 'cambioPassword',
                        data: { user    : user, 
                                dni   : dni,
                                newpass : newpass },
                        'async' : false
                    })
                    .done(function(data) {
                        if(data.flgNPass == 1){
                            $('#msgError3').show();
                        }else{
                        	ingresarCamb();
                        
                        /*
                            $('#username').val('');
                            $('#password').val('');
                            $('#dni').val('');
                            $('#newpass').val('');
                            $('#confirmpass').val('');
                            $("#l-bypass2").removeClass( "active" );
                            $("#l-bypass").removeClass( "active" );
                            $("#l-login").addClass( "active" );*/
                            
                            
                            
                        }
                    })
             }
            

        }

	function verificaPass(compara){
	  var newpass = $('#nuevopass').val();
                    
           if(newpass!=compara){
                $('#msgError3').show();
            }else{
            	 $('#msgError3').hide();
            }
          
	}


        function goSinFix(){
            //console.log('https://sin-fix.com/app/controlador/ingresar.php?redirect='+valSinFix);
        	window.location.replace('ejecucion?pagina=pendiente'); 
        }
        
        // function resizeContent(){
        //     var body				= $('body');
        //     var getBodyHeight 		= body.height();
        //     var getBodyWidth 		= body.width();
        //     var container			= $('.header-login ~ .container');
        //     var getContainerHeight 	= container.height();
        //     var getContainerWidth 	= container.width();
        //     var topContainer		= (getBodyHeight - getContainerHeight) / 2;
        //     var leftContainer		= (getBodyWidth - getContainerWidth) / 2;
            
        //     if ( topContainer <= 0 ){
        //         topContainer = 10;
        //     }
        //     if ( leftContainer <= 0 ){
        //         leftContainer = 0;
        //     }
        //     container.css({
        //         top 	: topContainer +8,
        //         left	: leftContainer -8
        //     });
        // }
    </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:21:49 GMT -->
</html>
