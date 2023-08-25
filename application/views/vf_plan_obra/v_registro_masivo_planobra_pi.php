<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
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
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.css" />
        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css?v=<?php echo time();?>">
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css?v=<?php echo time();?>">
        
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
                   <a href="https://www.movistar.com.pe/" title="Entel Per�"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
                    <h2><?php echo $title ?></h2>
                    <br>
                    <div class="card">
                        <div class="card-block">
							<div class="form-group col-sm-12" style="text-align: center;">
								<label style="color: red" class="control-label mb-10 text-left">El archivo debe tener el formato indicado para realizar la carga masiva (.xlsx, .xls)</label><br>
                                <label style="color: red" class="control-label mb-10 text-left">Llenar la columnas de amarillo en Fmt_Reg_Masivo_Obra_Pin.xls y cargarlo para su proceso.</label>
							</div>
							<div class="row">
								<div class="form-group col-md-3" style="text-align: center;">                        
									<input type="button" id="btnExportFormato" value="1) Exportar Formato" class="btn-success" onclick="exportarFmtCargaMasivoIP(this)" style="cursor:pointer;">
								</div>
								<div class="form-group col-md-3">
									<input id="fileTable" name="fileTable" type="file" class="file" data-show-preview="false">                    
								</div> 
								<div class="form-group col-md-2" style="text-align: right;">                        
									<input type="button" id="btnExportFormato" value="2) Procesar Archivo" class="btn-success" onclick="procesarFile(this)" style="cursor:pointer;">
								</div> 
								<div class="form-group col-md-4" style="text-align: center;">                        
									<input type="button" id="loadFileData" value="3) Cargar Archivo" class="btn-success" style="cursor:pointer;">
								</div>  

							</div><br>
							<h5 id="tituTbObs" style="color:red; display: none;">Cantidad de registros a subir: 100</h5>
							<div id="contTabla" class="table-responsive" style="display: block;">
								<?php echo $tbObservacion?>
							</div>
                        </div>
                    </div>
                    
                </div>
            </section>

            
        <!-- Javascript -->
        </main>


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
        <script src="<?php echo base_url(); ?>public/bower_components/loading/jquery.loading.min.js"></script>
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>        
        <!--  -->
        <script src="<?php echo base_url();?>public/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
        <script type="text/javascript">


            function exportarFmtCargaMasivoIP(component){
                $.ajax({
                        type: 'POST',
                        url: 'exportFmtMasivoObraPin',
                        data: {},
                        beforeSend: () => {
                            $('body').loading({
                                message: 'Espere por favor...'
                            });
                        }
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0){
                        if(data.archivo != null && data.archivo != undefined){
                            var $a = $("<a>");
                            $a.attr("href",data.archivo);
                            $("body").append($a);
                            $a.attr("download",data.nombreArchivo);
                            $a[0].click();
                            $a.remove();
                        }
                    } else {
                        swal.fire('Aviso!', data.msj, 'error');
                    }
                }).always(() => {
                    $('body').loading('destroy');
                });
            }

            // File type validation
			$("#fileTable").change(function() {
				var file = this.files[0];
				var fileType = (file !== undefined ? file.type : null);
				var match = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-offedocument.spreadsheetml.sheet.'];
                if(file !== undefined){
                    if(!((fileType == match[0]) || (fileType == match[1]))){
                        mostrarNotificacion('error','Error','Solo se permite subir archivos .xls, .xlsx');
                        $("#fileTable").val(null);
                        return false;
                    }
                }
			});

            var arrayDataGlob = [];
            function procesarFile(component){
                var comprobar = $('#fileTable').val().length;
                if(comprobar == 0){
                    mostrarNotificacion('warning','Error','Debe subir un archivo a procesar!!');
                    return;
                }
               
                swal({
                    title: 'Está seguro de procesar el archivo??',
                    text: 'Asegurese de seleccionar un archivo de tipo (.xls,.xlsx)',
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
                    var files = $('#fileTable')[0].files[0];
                    formData.append('file', files);
                    $.ajax({
                        type  :	'POST',
                        url   :	'procesarFileRegMasivoObraPin',
                        data  :	formData,
                        contentType: false,
                        processData: false,
                        cache: false,
                        beforeSend: () => {
                            $('body').loading({
                                message: 'Espere por favor...'
                            });
                        }
                        
                    }).done(function(data){
                        var data = JSON.parse(data);
                        if(data.error == 0){
                            console.log('entro al error 0')
                            arrayDataGlob = JSON.parse(data.jsonDataFile);
                            $('#tituTbObs').text(data.titulo);
                            $('#contTabla').html(data.tbReporte);
                            $('#tituTbObs').css('display', 'block');
                            $('#contTabla').css('display', 'block');
                            initDataTable('#data-table');
                            mostrarNotificacion('success','Aviso',data.msj);
                        }else{
                            mostrarNotificacion('error','Aviso',data.msj);
                        }
                        
                    }).always(() => {
                        $('body').loading('destroy');
                    });
                    
                }).catch(swal.noop);
            }
        


            $('#loadFileData').click(function(e){
                if(arrayDataGlob.length > 0 && arrayDataGlob != null && arrayDataGlob != undefined){

                    var comprobar = $('#fileTable').val().length;
                    if(comprobar == 0){
                        swal.fire('Verificar!','Debe subir un archivo a procesar!!','warning');
                        return;
                    }
                    var file = $('#fileTable').val()			
                    var ext = file.substring(file.lastIndexOf("."));

                    if(ext != ".xls" && ext != ".xlsx"){
                        mostrarNotificacion('warning','Error','Formato de archivo inválido!!',);
                        return;
                    }
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
                        var files = $('#fileTable')[0].files[0];
                        formData.append('file', files);
                        formData.append('arrayDataFile', JSON.stringify(arrayDataGlob));

                        $.ajax({
                            type  :	'POST',
                            url   :	'cargaMasivaObraPin',
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
                            if(data.error == 0){
                                $('#contTabla').html(data.tbObservacion);
                                initDataTable('#data-table');
                                modal('modalProgreso');
                                swal({
                                    title: 'Aviso',
                                    text: data.msj,
                                    type: 'success',
                                    showConfirmButton: true,
                                    allowOutsideClick: false
                                }).then(function () {
                                    $('#tituTbObs').css('display', 'none');
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

                }else{
                    mostrarNotificacion('warning','Verificar!','No hay datos válidos para cargar!!');
                    return;
                }
            });

            function backMenu(){        	
                window.location.href = 'upMasiOcMen';
            }


        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/hidden-sidebar.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:16:44 GMT -->
</html>
