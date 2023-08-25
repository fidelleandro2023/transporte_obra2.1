<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>

<style>
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


<html lang="en">

    <ul class="top-nav">                    
        <li class="dropdown">
            <a href="" data-toggle="dropdown" aria-expanded="false"><i class="zmdi zmdi-more-vert"></i></a>
        
            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(-171px, 36px, 0px); left: 100%;">
                <a href="http://www.plandeobras.com/obra2.0/c_panel" 
                    class="dropdown-item"><span><i class="zmdi zmdi-home zmdi-hc-fw"></i> <span>Ir a Panel</span></span></a>'
                <a onclick="openForm()" class="dropdown-item"><span><i class="zmdi zmdi-key"></i> Cambio Clave</span></a>
                <a onclick="cerrarSesion()" class="dropdown-item"><span><i class="zmdi zmdi-power zmdi-hc-fw"></i> Cerrar Sesion</span></a>
            </div>
        </li>
    </ul>
    
    <div class="form-popup" id="myForm">
        <form class="form-container">
            <h2>Cambio de clave</h2>
            <div class="col-md-12 col-sm-12 col-12">
    
                <label for="psw"><b>Nueva clave</b></label><br>
                <input type="password" id="nuevopass" maxlength="12" onkeyup="muestra_seguridad_clave(this.value,'#msg1');">
        
                <label for="psw"><b>Confirmar clave</b></label>
                <input type="password" id="confirmpass" maxlength="12" onkeyup="verificaPass(this.value);">
                <div style="display:none"><input id="verificon" type="text"></div>

                <p id="msgError3" style="color: red; display: none;"  class="text-muted text-center">
                    <small style="color: darkgoldenrod;">LAS CLAVES SON DISTINTAS</small>
                </p> 
                <p><div id="msg1" ></div></p>
                <p id="msgError4" style="color: red; display: none;"  class="text-muted text-center">
                    <small style="color: darkgoldenrod;">EL NIVEL DE SEGURIDAD DE SU CLAVE DEBE SER DEL 100%</small>
                </p>

                <button onclick="cambiaClave()" style="color:var(--blanco_telefonica)"  class="btn btn-primary">Cambiar</button>
                <button class="btn cancel" onclick="closeForm()">Cerrar</button>
            </div>
        </form>
    </div> 

    <script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
    <script>
    
    function openForm() {
        $('#myForm').show();
    }

    function closeForm() {
        $('#myForm').hide();
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
                    }
            })
        }
    }
	
	function cerrarSesion() {
		$.ajax({
				type: "POST",
				'url' : 'logOut'
			})
			.done(function(data) {
				window.location.href = 'http://www.plandeobras.com/obra2.0';
			})
	}
    </script>
    
    
    
    
</html>

