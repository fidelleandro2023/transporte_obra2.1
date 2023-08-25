<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

   
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta charset="UTF-8">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>PLANOBRA | MOVISTAR</title>

        <link rel="icon" type="image/png" href="<?php echo IMG_MOVISTAR_CABECERA; ?>" />


        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/dropzone/dist/dropzone.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/select2/dist/css/select2.min.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.css" />
        <!-- App styles -->

        <link rel="stylesheet" href="<?php echo base_url(); ?>public/fonts/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css?v=<?php echo time();?>">
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css?v=<?php echo time();?>">

        
        <style>
            .select2-dropdown {
                z-index: 100000;
            }

            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
            }
            input[type=number] { -moz-appearance:textfield; }

   
            @media (min-width: 768px) {
			  .modal-xl {
                width: 90%;
               max-width:1200px;
              }
            }

			.modal-backdrop	{
				opacity:0.5 !important;
			}

			.btn:not(.btn-secondary):not([class*=btn-outline-]):not(.btn-link) {/*para quitar los border del group button*/
                box-shadow: 0 0 0 0 rgba(0,0,0,.12) !important;
            }
			.sizeIcon {
				font-size: 1.5em;
			}
            p {
				margin-bottom: -0.5rem !important;
			}

            #listaDeArchivos li{
                margin-bottom: 15px;
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
                <a href="https://www.movistar.com.pe/" title="MOSVISTAR PERU"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
                </div>

                <?php include('application/views/v_opciones.php'); ?>
            </header>
            <aside class="sidebar sidebar--hidden">
                <div class="scrollbar-inner">
                    <div class="user">
                        <div class="user__info" data-toggle="dropdown">
                            <img class="user__img" src="<?php echo base_url(); ?>public/demo/img/profile-pics/8.jpg" alt="">
                            <div>
								<div class="user__name"><?php echo $this->session->userdata('usernameSession')?></div>
                                <div class="user__email"><?php echo $this->session->userdata('descPerfilSession')?></div>
                            </div>
                        </div>
                    </div>

                    <ul class="navigation">
                        <?php echo isset($opciones) ? $opciones : null ?>
                    </ul>
                </div>
            </aside>


            <section class="content content--full">
                <div class="content__inner">
                    <h2><?php echo $title ?></h2>
                    <!--div class="col-sm-12 col-md-12" style="text-align: right;">
                        <button class="btn btn-success waves-effect" type="button" onclick="backMenu()" style="border-radius: 30px;">VOLVER AL MENÚ</button>                     
                    </div--> 
                    <div class="card">
                        <div class="card-block">

							<div class="form-group" style="border: solid 1px;" >

								<div class="row" style="margin-top: 40px; margin-left: 30px; margin-right: 30px;">
									<!-- <div class="col-sm-2 col-md-2">
										<div class="form-group">
											<label>Ingrese ItemPlan</label>
											<input type="text" id="txtItemplan" class="form-control" maxlength="13" value="<?php echo isset($itemplan) ? $itemplan : null ?>">
										</div>
									</div> -->
                                    <div class="col-sm-12 col-md-12">
										<div class="form-group">
                                        <input name="fileTable[]" type="file" class="file" data-show-preview="false" accept=".zip,.rar" multiple>
										</div>
									</div> 
                                    

								</div>

								<!-- <div class="row" style="margin-left: 30px; margin-right: 30px;">
									<div class="col-sm-12 col-md-12 form-group" style="text-align: center;">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button class="btn btn-success" style="margin-right: 20px;" type="button" onclick="validarCarga()" id="btnBuscar">1) Validar</button>
                                        </div>
									</div>
								</div> -->

										
							</div>

							<div id="contCardMat" style="border: solid 1px; padding: 2.25rem; display: block;">
                                <div class="card" style="border: 1px solid rgba(0,0,0,.125);">
                                    <div class="card-header" style="padding: .75rem 1.25rem; margin-bottom: 0; text-align: center; background-color: rgba(0,0,0,.03); border-bottom: 1px solid rgba(0,0,0,.125);">
                                        ARCHIVOS SELECCIONADOS
                                    </div>
                                    <div class="card-body" style="padding: 1.25rem;">
                                        <div id="cont_tb_evaluacion">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    
                                                    <ul id="listaDeArchivos">
                                                    </ul>                   
                                                </div> 
                                                <!-- <div class="form-group col-md-4" style="text-align: center;">                        
                                                    <input type="button" id="loadFileData" value="2) Cargar Archivo" class="btn-success" style="cursor:pointer;">
                                                </div>   -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" style="margin-left: 30px; margin-right: 30px; display:block;" id="contBtnLiqui">
                                    <div class="col-sm-12 col-md-12 form-group" style="text-align: center;">
                                        <div class="btn-group" role="group" aria-label="Basic example">                        
                                            <input type="button" id="loadFileData" value="2) Cargar Archivo" class="btn-success" style="cursor:pointer;">
                                        </div>
                                    </div>
								</div>

                            </div>
							
                        </div>
                    </div>
                    
                    

                </div>
            </section>

			
         
        </main>


    </body>

    <div class="modal bd-example-modal-sm" id="modalProgreso" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="text-align: center;background-color: #00000070;border-radius: 30%;">
                <div class="modal-header">
                    <h4 id="tituloModalEnt" style="margin: auto;font-weight: bold;" class="modal-title"></h4>                       
                </div>
                <div class="modal-body">
                    <div id="contProgres">
                        <div class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="rgb(255, 255, 255)" data-bar-color="rgb(255, 255, 255)">
                            <span id="valuePie" class="easy-pie-chart__value">0</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">                       
                    <h3 style="color: rgb(255, 255, 255);padding-right: 35px;">Procesando...</h3>                          
                </div>
            </div>
        </div>
    </div>


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

    <script src="<?php echo base_url();?>public/bower_components/dropzone/dist/min/dropzone.min.js?v=<?php echo time();?>"></script>

    <!-- Charts and maps-->
    <script src="<?php echo base_url();?>public/demo/js/flot-charts/curved-line.js"></script>
    <script src="<?php echo base_url();?>public/demo/js/flot-charts/line.js"></script>
    <script src="<?php echo base_url();?>public/demo/js/flot-charts/chart-tooltips.js"></script>
    <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
    <script src="<?php echo base_url();?>public/demo/js/jqvmap.js"></script>

    <script src="<?php echo base_url(); ?>public/bower_components/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    <script src="<?php echo base_url(); ?>public/jquery.numeric/jquery.numeric-min.js"></script>
	<script src="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/app.min.js"></script>

    <!--  -->
    <script src="<?php echo base_url(); ?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/notify/pnotify.custom.min.js"></script>
    <script src="<?php echo base_url(); ?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url(); ?>public/js/Utils.js?v=<?php echo time();?>"></script>    


    <script>

        //filtrarTabla();

		$('.numerico').numeric({
            negative: false,
			altDecimal: ',', 
			decimal: '.'
		});


        var inputFile = $("[name='fileTable[]']");
        var listaDeArchivos = $("#listaDeArchivos");
        var archivosParaSubir = [];

        function actualizarListaDeArchivos(arrayIndiceError) {
            let listaHtml = archivosParaSubir.map(function(item, index) {

                let lista = `<li>
                                ${item.name} 
                                <button data-index="${index}" class="file-list-eliminar">Eliminar</button>
                            </li>`;

                if(arrayIndiceError != null && arrayIndiceError != undefined){
                    console.log(arrayIndiceError.includes(index));
                    if(arrayIndiceError.includes(index)){
                        lista = `<li style="color:red;">
                                    ${item.name} 
                                    <button data-index="${index}" class="file-list-eliminar">Eliminar</button>
                                </li>`;
                    }
                }
                return lista;
            });
            listaDeArchivos.html(listaHtml);
        }

        inputFile.on('change', function(e) {
            let files = e.target.files;
            
            //if(files.length == 0) return;
            
            files = Array.from(files);
            archivosParaSubir = files;
            actualizarListaDeArchivos();
            //$(this).val('');
        });

        $(document).on("click", ".file-list-eliminar", function() {
            let index = $(this).data('index');
            archivosParaSubir.splice(index, 1);
            var prueba = $("[name='fileTable[]']")[0].files;
            var fileBuffer = new DataTransfer();
            for (let i = 0; i < prueba.length; i++) {
                if (index !== i)
                fileBuffer.items.add(prueba[i]);
                //console.log(prueba[i]);
            }
            $("[name='fileTable[]']")[0].files = fileBuffer.files;
            actualizarListaDeArchivos();
        });
		

        var jsonInfo = {};
        var jsonValida = {};
        var puedeLiquidarGlob = false;

		function validarCarga(){
			
            //$('#contBtnLiqui').css('display','none');
			var itemplan = $.trim($('#txtItemplan').val());
            $('#fileTable').val(null);

            $.ajax({
                type  :	'POST',
                url   :	'validarCargaItemplanForRegEvidencia',
                data  :	{ 
					itemplan : itemplan
				},
				beforeSend: () => {
					$('body').loading({
                        message: 'Espere por favor...'
                    });
					$('#btnBuscar').attr("disabled", true);
                    //$('#btnLiqui').attr("disabled", true);
				}
            }).done(function(data){
				var data = JSON.parse(data);
                console.log(data);
                if(data.error == 0){
                    $('#loadFileData').data('itemplan', itemplan);
                    if(data.canLiqui){
                        $('#contCardMat').css('display','block');
                        $("#loadFileData").prop("disabled", false);
                    }else{
                        $('#contCardMat').css('display','none');
                        $("#loadFileData").prop("disabled", true);
                    }
                    puedeLiquidarGlob = data.canLiqui;

                }else{
                    $("#loadFileData").prop("onclick", null).off("click");
                    $('#contCardMat').css('display','none');
                    mostrarNotificacion('error','Aviso',data.msj);   
				}

            }).always(() => {
				$('body').loading('destroy');
				$('#btnBuscar').removeAttr("disabled");
                //$('#btnLiqui').removeAttr("disabled");
			});
            
		}

        $('#loadFileData').click(function(e){
            //var comprobar = $('#fileTable').val().length;
            // if(comprobar == 0){
            //     mostrarNotificacion('warning','Aviso','Debe subir un archivo a procesar!!');
            //     return;
            // }

            // var file = $('#fileTable').val()			
            // var ext = file.substring(file.lastIndexOf("."));
            
            swal({
                title: 'Está seguro de cargar el achivo y actualizar los datos??',
                text: 'Asegurese de validar la información!!',
                type: 'warning',
                showConfirmButton: true,
                confirmButtonClass: 'btn btn-success',
                confirmButtonText: 'SI',
                showCancelButton: true,
                cancelButtonClass: 'btn btn-danger',
                cancelButtonText: 'NO',
                allowOutsideClick: false
            }).then(function () {
                var formData = new FormData();
                var archivos = $("[name='fileTable[]']")[0].files;
                console.log(archivos[0]);
                for (let i = 0; i < archivos.length; i++) {
                    formData.append("files[]", archivos[i]);
                }
                console.log('entro aca');

                $.ajax({
                    type  :	'POST',
                    url   :	'cargarEvidenciaLiquiPINMasivoMasDespliegue',
                    data  :	formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    xhr: function() {
                        $('.easy-pie-chart').data('easyPieChart').update('0');
                        $('#valuePie').html('0');
                        modal('modalProgreso');
                        var xhr = $.ajaxSettings.xhr();
                        xhr.upload.onprogress = function(e) {
                            var progreso = Math.floor(e.loaded / e.total * 100);                                
                            $('.easy-pie-chart').data('easyPieChart').update(progreso);
                            $('#valuePie').html(progreso);
                        };
                        return xhr;
                    }
                }).done(function(data){
                    var data = JSON.parse(data);
					console.log(data);
                    var arrayIndiceError = JSON.parse(data.array_indice_error);
                    actualizarListaDeArchivos(arrayIndiceError);
                    if(data.error == 0){
                        modal('modalProgreso');
                        swal({
                            title: 'Aviso',
                            text: data.msj,
                            type: 'success',
                            showConfirmButton: true,
                            allowOutsideClick: false
                        }).then(function () {
                            //location.reload();
                        });
                    }else{
                        modal('modalProgreso');
                        mostrarNotificacion('error','Error',data.msj);
                        return;
                    }
                    
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    modal('modalProgreso');
                    mostrarNotificacion('error','Error',errorThrown + '. Estado: ' + textStatus);
                    return;
                })
            }).catch(swal.noop);
        });
		
		function backMenu(){        	
			window.location.href = 'regularizarEvidenciaItemplan';
		}


    </script>
