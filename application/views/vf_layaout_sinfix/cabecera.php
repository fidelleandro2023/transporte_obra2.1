logo<style>
.form-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index:0;
}

.form-container {
  max-width: 200px;
  padding: 10px;
  background-color: var(--celeste_telefonica3);
}

</style>

<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="mobile-only-brand pull-left">
        
        <a id="toggle_nav_btn" class="toggle-left-nav-btn inline-block ml-20 pull-left" href="javascript:void(0);"><i class="zmdi zmdi-menu"></i></a>
        <a id="toggle_mobile_search" data-toggle="collapse" data-target="#search_form" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-search"></i></a>
        <a id="toggle_mobile_nav" class="mobile-only-view" href="javascript:void(0);"><i class="zmdi zmdi-more"></i></a>
        <div class="nav-header pull-left">
         
          
        </div>  
         <div class="logo-wrap" style="background-color: var(--verde_telefonica);">
           <a href="ejecucion?pagina=pendiente">
              <img class="brand-img" src="<?php echo base_url();?>public/img/logo/company_logo.png" alt="movistar" style="width: 30%;">
                
            </a>
             
          </div>
      </div>
      
      
    
      <div id="mobile_only_nav" class="mobile-only-nav pull-right">
        <ul class="nav navbar-right top-nav pull-right">
        <li class="dropdown auth-drp">
            <a href="#" class="dropdown-toggle pr-0" data-toggle="dropdown"><i class="fa fa-ellipsis-v" style="color:white; margin-right: 50px; font-size:1.65rem"></i></a>
            <ul class="dropdown-menu user-auth-dropdown" data-dropdown-in="flipInX" data-dropdown-out="flipOutX">
               <li class="sub-menu show-on-hover">
                <a href="extrac" class="dropdown-toggle pr-0 level-2-drp"><i class="fa fa-arrow-left text-success"></i> Modulo Gestion</a>
               </li>
              <li class="divider"></li>
               <li>
               	   <a onclick="openForm()"><span><i class="zmdi zmdi-key"></i> Cambio Clave</span></a>
               </li>
              <li>
                <a href="logOut"><i class="zmdi zmdi-power"></i><span>Cerrar Sesion</span></a>
              </li>
            </ul>
          </li>
        </ul>
      </div>  
    </nav>
    
    
    <div class="modal fade" id="myForm" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                   <h2>Cambio de clave</h2>
                </div>
                <div class="modal-body">
               
                	<div class="col-sm-12 col-md-12">
                	<label for="psw"><b>Nueva clave</b></label>
    				<input type="password" id="nuevopass" maxlength="12" onkeyup="muestra_seguridad_clave(this.value,'#msg1');">
                	
                	
                	 <label for="psw"><b>Confirmar clave</b></label>
    				<input type="password" id="confirmpass" maxlength="12" onkeyup="verificaPass(this.value);">
                	</div>
    				<div style="display:none"><input id="verificon" type="text"></div>
 					<p id="msgError3" style="color: red; display: none;"  
 					class="text-muted text-center"><small style="color: darkgoldenrod;">LAS CLAVES SON DISTINTAS</small></p> <p>
 					<div id="msg1" ></div></p>
                             <p id="msgError4" style="color: red; display: none;"  class="text-muted text-center">
                             <small style="color: darkgoldenrod;">EL NIVEL DE SEGURIDAD DE SU CLAVE DEBE SER DEL 100%</small></p>
                	
                	
			
					
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnCambiaPass" onclick="cambiaClave();"  class="btn btn-primary">Cambiar</button>
                    <button type="button"  class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div> 
    
   
 

     <script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
    <script>
    
    function openForm() {
    
   modal('myForm');
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
        
        function verificaPass(compara){
	  var newpass = $('#nuevopass').val();
                    
           if(newpass!=compara){
                $('#msgError3').show();
            }else{
            	 $('#msgError3').hide();
            }
          
	}   
	
	function cambiaClave(){
         
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
                        'url' : 'cambioPasswordI',
                        data: { newpass : newpass },
                        'async' : false
                    })
                    .done(function(data) {
                        if(data.flgNPass == 1){
                            $('#msgError3').show();
                            return false;
                        }else{
                        mostrarNotificacion('success','Operaci&oacute;n exitosa.', 'Se modific&oacute;n su clave de acceso correctamente!'); 
                        location.href="ejecucion?pagina=pendiente";
                        }
                    })
             }
            

        }
    </script>