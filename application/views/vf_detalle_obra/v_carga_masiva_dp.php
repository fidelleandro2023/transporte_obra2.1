<!DOCTYPE html>
<html lang="en">
    
<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Vendor styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">

        <!-- App styles -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">

        <!-- Demo -->
        <link rel="stylesheet" href="<?php echo base_url();?>public/demo/css/demo.css">
        
        <link rel="stylesheet" href="<?php echo base_url();?>public/css/utils.css">
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
                   <a href="https://www.movistar.com.pe/" title="Entel Perú"><img src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="Logo Entel" style="width: 36%; margin-left: -51%"></a>
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
             <h2 style="color: #333333d4;font-weight: 800;text-align: center;">CARGA MASIVA DETALLE PLAN</h2>
              <div class="card">
                
                
                        <div class="card-block">	   				                         
                                <div class="row">
                              
  
 
                                    <section>
                                     <label style="font-size: smaller;text-align: left;">- El archivo a importar debe estar en formato .txt (Archivo de Texto).</label><br>
                                     <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe contar con 3 columnas separados por Tabulaciones.</label><br>
                                     <label style="font-size: 14px;text-align: left; color: red">- El archivo debe contar con un m&aacuteximo de 40 PTR's por cada carga. De caso contrario el sistema solo reconocer&aacute; los primeros 40 registros.</label><br>
                                     <label><a href="uploads/dp/modelo/modelo.txt" download="modelo.txt">Descargar Modelo de Carga .txt</a></label>
                                    <br><br>
                                     <div id="contProgres">
                            <div class="easy-pie-chart" data-percent="0" data-size="100" data-track-color="#eee" data-bar-color="#32c787">
                                <span id="valuePie" class="easy-pie-chart__value">0</span>
                            </div>
                        
                        
                                    </div>
                                    <div id="contSubida">
                                        <form id="subida">
                                            <table style="margin: auto;">
                                            	<tr>
                                                	<td>  <input id="csv" name="userfile" type="file" /></td>
                                                </tr>
                                                <tr>
                                                	<td><input type="submit" value="Send File" /></td>
                                                </tr>
                                                
                                            </table><br>
                                            
                                        </form>    
                                    </div>
                                  
                                                                
	                           </section>	                        
	                         </div> 
	                         <div id="contResult" style="display: none" class="row">
	                         <div class="col-sm-12">
	                        
                                <div class="form-group">
                                <table style="margin: auto;">
                                    <tr>
                                        <td id="respuesta"></td>
                                     </tr>
                                 </table>
                                    <table style="margin: auto;">                              
                                        <tr>
                                            <td id="numSuc"></td>
                                            <td id="numErr"></td>
                                        </tr>
                                        <!--
                                    	<tr>
                                        	<td><a href="uploads/po/success.csv" style="color: white;" class="btn btn-success btn--icon-text waves-effect" download><i class="zmdi zmdi-download"></i>IMPORTADOS</a></td>
                                        	<td>  <a href="uploads/po/error.csv" style="color: white;" class="btn btn-danger btn--icon-text waves-effect" download><i class="zmdi zmdi-download"></i>NO IMPORTADOS</a></td>
                                    	</tr>-->
                                	</table>                                    
    	                        </div>
	                        </div>
	                        </div>
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
        <!-- Vendors -->
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <script src="<?php echo base_url();?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url();?>public/jquery.sparkline/jquery.sparkline.min.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>

        <!-- Demo -->
        <script src="<?php echo base_url();?>public/demo/js/other-charts.js"></script>
        <script type="text/javascript">
        $(function(){
            var ERROR = 1;
        	$('#subida').submit(function(){
        		
        		var comprobar = $('#csv').val().length;
        		
        		if(comprobar>0){

        			    var file = $('#csv').val()			
        			    console.log($('#csv').val().length);
        			    var ext = file.substring(file.lastIndexOf("."));
        			    console.log(ext);
        			    if(ext != ".txt")
        			    {
                            console.log('1..');
        			    	alert('Formato de archivo no v\u00E1lido. El formato correcto es .TXT');
        			        return false;
        			    }
        			    else
        			    {
                            console.log('2..');
        			    	var formulario = $('#subida');
        					
        					var archivos = new FormData();	
        					
        					var url = 'updp1';
                            //..
        					
        						for (var i = 0; i < (formulario.find('input[type=file]').length); i++) {
                                    console.log('3..'+i);
        						
        		               	 archivos.append((formulario.find('input[type="file"]:eq('+i+')').attr("name")),((formulario.find('input[type="file"]:eq('+i+')')[0]).files[0]));
        						 
        		      		 	}
        						$('.easy-pie-chart').data('easyPieChart').update('5');
        						$('#contSubida').hide();
  					            $('#valuePie').html(5);
                                console.log('4.......');
        					$.ajax({
        						
        						url: url,
        						
        						type: 'POST',
        						
        						contentType: false, 
        						
        		            	data: archivos,
        						
        		               	processData:false,
        						
        						success: function(data){
        							console.log(data);
        							data = JSON.parse(data);
        							if(data.error == 1){
        								$('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color:#968c07;">'+data.msj+'</label>');
        								return false;	
        							}else if(data.error == 0){
        								$('.easy-pie-chart').data('easyPieChart').update('20');
                					            $('#valuePie').html(20);
                					            ERROR = 0;
        							} 

        							console.log("ERROR"+ERROR);
                					if(ERROR==0){

                                        //::
                                        $.ajax({
                            
                                            url: 'updp2',
                                            
                                            type: 'POST',
                                            
                                            contentType: false, 
                                            
                                            data: archivos,
                                            
                                            processData:false,
                                            
                                            success: function(data){
                                                        console.log(data);
                                                        data = JSON.parse(data);
                                                        if(data.error == 1){
                                                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color:#968c07;">'+data.msj+'</label>');
                                                            return false;   
                                                        }else if(data.error == 0){
                                                            $('.easy-pie-chart').data('easyPieChart').update('50');
                                                                    $('#valuePie').html(50);
                                                                    ERROR = 0;
                                                        }

                                                        console.log("ERROR"+ERROR);
                                                        if(ERROR==0){
                                                            console.log('...5...fue al loadDataImportDetalleObra ok');

                                                    
                                                            $.ajax({
                                                                 type : 'POST',
                                                                 url : 'updp3'
                                                               }).done(function(data){
                                                                    console.log('6..carga importDetallePlanMasivo En BD');
                                                                  console.log(data);
                                                                    data = JSON.parse(data);
                                                                    if(data.error == 1){
                                                                        ERROR = 1;
                                                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">'+data.msj+'</label>');
                                                                        return false;   
                                                                    }else if(data.error == 0){
                                                                        $('.easy-pie-chart').data('easyPieChart').update('90');
                                                                        $('#valuePie').html(90);
                                                                        ERROR = 0;
                                                                    } 

                                                                    if(ERROR==0){
                                                                        
                                                                        //..
                                                                       $.ajax({
                                                                             type : 'POST',
                                                                             url : 'updp4'
                                                                           }).done(function(data){
                                                                                console.log('7...Cargado loadWebUniDetalle para Aprobacion..');
                                                                              console.log(data);
                                                                                data = JSON.parse(data);
                                                                                if(data.error == 1){
                                                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">'+data.msj+'</label>');
                                                                                    return false;   
                                                                                }else if(data.error == 0){
                                                                                    $('.easy-pie-chart').data('easyPieChart').update('100');
                                                                                    $('#valuePie').html(100);
                                                                                    ERROR = 0;
                                                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #0d7d3f;">Se ha completado el proceso correctamente.</label>');
                                                                                    //$('#numSuc').html('<label style="font-size: larger; padding-top: 20px;color: #0d7d3f;">'+data.numsuc+' Correctos</label>');
                                                                                    //$('#numErr').html('<label style="font-size: larger; padding-top: 20px;color: #a21212;">'+data.numerr+' Incorrectos</label>');
                                                                                    $('#contResult').show();
                                                                                } 

                                                                           })
                                                                           //..
                                                                     }

                                                                })
                                                        }//fin error
                                                                            
                                                 
                                            }// fin success
                                                
                                        }) // fin ajax

                                        //::


                                        
                					}//fin error
        														
        							 
        						}// fin success
                					
        					}) // fin ajax
        					
      					
        					
        					return false;
        			    	
        			    }
        			    
        		}else{
        			
        			alert('Selecciona un archivo txt para importar');
        			
        			return false;
        			
        		}
        	});
        });
  
        
        </script>
    </body>

<!-- Mirrored from byrushan.com/projects/material-admin/app/2.0/jquery/bs4/other-charts.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Jul 2017 17:18:58 GMT -->
</html>