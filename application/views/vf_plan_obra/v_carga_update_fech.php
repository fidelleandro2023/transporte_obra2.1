<!DOCTYPE html>
<html lang="en">
    

<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/animate.css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.css">

        <link rel="stylesheet" href="<?php echo base_url();?>public/css/app.min.css">

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
             <h2>ACTUALIZACION DE FECHAS MASIVO</h2>
              <div class="card">
                
                
                        <div class="card-block">	   				                         
                                <div class="row">
                              
  
 
                                    <section>
                                     <label style="font-size: smaller;text-align: left;">El  <strong >DOCUMENTO</strong> a importar debe estar en formato .txt (Archivo de Texto).</label><br>
                                     <label style="font-size: smaller;text-align: left;">- La estructura del archivo debe contar con 3 columnas separados por Tabulaciones.</label><br>
                                     <label style="font-size: 14px;text-align: left; color: red"> El campo "FECHA" debe estar en el formato DD/MM/YYYY ejemplo: 01/12/2017.</label><br>
                                     <label><a href="uploads/dp/modelo/modeloFech.txt" download="modeloFech.txt">Descargar modelo de Carga .txt</a></label>
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
                                                	<td><input type="submit" value="Enviar Archivo" style="background-color: var(--verde_telefonica)"/></td>
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
                        <a class="nav-link" href="#">Inicio</a>

                        <a class="nav-link" href="#">Compa�ia</a>

                        <a class="nav-link" href="#">Soporte</a>

                        <a class="nav-link" href="#">Cont�ctanos</a>
                    </ul>
                </footer>
            </section>
        </main>

        
        <script src="<?php echo base_url();?>public/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>
        <script src="<?php echo base_url();?>public/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
        <script src="<?php echo base_url();?>public/jquery.sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url();?>public/js/app.min.js"></script>
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
        					
        					var url = 'updf1';
                            
        					
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
        								$('#contResult').show();
        								return false;	
        							}else if(data.error == 0){
        								$('.easy-pie-chart').data('easyPieChart').update('20');
                					            $('#valuePie').html(20);
                					            ERROR = 0;
        							} 

        							console.log("ERROR"+ERROR);
                					if(ERROR==0){

                                        $.ajax({
                            
                                            url: 'updf2',
                                            
                                            type: 'POST',
                                            
                                            contentType: false, 
                                            
                                            data: archivos,
                                            
                                            processData:false,
                                            
                                            success: function(data){
                                                        console.log(data);
                                                        data = JSON.parse(data);
                                                        if(data.error == 1){
                                                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color:#968c07;">'+data.msj+'</label>');
                                                            $('#contResult').show();
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
                                                                 url : 'updf3'
                                                               }).done(function(data){
                                                                    console.log('6..carga importDetallePlanMasivo En BD');
                                                                  console.log(data);
                                                                    data = JSON.parse(data);
                                                                    if(data.error == 1){
                                                                        ERROR = 1;
                                                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">'+data.msj+'</label>');
                                                                        $('#contResult').show();
                                                                        return false;   
                                                                    }else if(data.error == 0){
                                                                        $('.easy-pie-chart').data('easyPieChart').update('100');
                                                                        $('#valuePie').html(100);
                                                                        ERROR = 0;
                                        								$('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color:#968c07;">'+data.msj+'</label>');
                                                                        $('#contResult').show();
                                                                    } 
                                                                   
                                                                })
                                                        }                                                                            
                                                 
                                            }
                                                
                                        }) 

                                       


                                        
                					}
        														
        							 
        						}
                					
        					}) 
        					
      					
        					
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

</html>