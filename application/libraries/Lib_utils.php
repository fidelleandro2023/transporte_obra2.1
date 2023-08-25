<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Lib_utils {

  
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
    function simple_encrypt($text, $clave){
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $clave, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }
    
    function simple_decrypt($text, $clave){
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $clave, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
    
    
    function validFecha($fecha){
        $test_arr  = explode('/', $fecha);
        if (count($test_arr) == 3) {
            if (checkdate($test_arr[1], $test_arr[0], $test_arr[2])) {//MES / DIA / YEAR
                return null;
            } else {
                return 'Fecha inválida';
            }
        } else {
            return 'Fecha inválida';
        }
    }
    
    function array_equal($a, $b) {
        return (is_array($a) && is_array($b) && array_diff($a, $b) === array_diff($b, $a));
    }
    
    //VALIDACIONES
    //MIN Y MAX
    function validLength($data,$min,$max){
        $lenght = strlen($data);
        $bool = false;
        if($min != null && $max != null){
            if($lenght >= $min && $lenght <= $max){
                $bool = true;
            }
        }else if($min != null){
            if($lenght >= $min){
                $bool = true;
            }
        }else if($max != null){
            if($lenght <= $max){
                $bool = true;
            }
        }
        return $bool;
    }
    
    // DATA :: DD/MM/YYYY
    function validateDate($date, $format = 'd/m/Y'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    function enviarEmail($correoDestino,$asunto,$body){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
    	try{
    		$CI =& get_instance();
    		$CI->load->library('email');    		
    		$configGmail = array('protocol'  => PROTOCOL,
                				 'smtp_host' => SMTP_HOST,
                				 'smtp_port' => SMTP_PORT,
                				 'smtp_user' => CORREO_BASE,
                				 'smtp_pass' => PASSWORD_BASE,
                				 'mailtype'  => MAILTYPE,
                				 'charset'   => 'utf-8',
                				 'newline'   => "\r\n",
                				 'starttls'  => TRUE);
    		$CI->email->initialize($configGmail);
    		$CI->email->from(CORREO_BASE);
    		$CI->email->to($correoDestino);
    		$CI->email->subject($asunto);
    		$CI->email->message($body);
    		if ($CI->email->send()) {
    			$data['error'] = EXIT_SUCCESS;
    		}else {
    		    $err = print_r($CI->email->print_debugger(), TRUE);
    		    log_message('error','err: '.$err);
    		    throw new Exception($err);
    		}
    	}catch(Exception $e){
    		$data['msj'] = $e->getMessage();
    	}
    	return $data;
    }
    
    function bodyMensajeResetearClave($nomComple,$usuario,$password){
    	$body	=	'<h2>Tu contraseña ha cambiado!</h2>
						<p>Hola '.$nomComple.'!</p>
						<p>Se proseguió a cambiar su clave para iniciar sesion en Plataforma Comunidades de Aprendizajes: </p>
						<ul>
						<li><h4>Usuario: '.$usuario.'</h4> </li>
						<li><h4>Clave: '.$password.'</h4></li>
						</ul>
						<p><h4>Ingresa a <a href="'.IP_URL_NATURA.'">Plataforma Comunidades de Aprendizajes</a> para iniciar sesión.</p>
						<img src="'.IP_URL_NATURA.FOTO_COMUNIDADES.'" height="60" width="300">';
    
    	return $body;
    }
    function getNombreUsuario($user){
        $nombres = preg_split("/[\s,]+/", $user['nombres']);
        $nombre  = $nombres[0];
        return $nombre;
    }
    public function makeHTMLToEMail($nombre){
        $html = '<p><img src="http://rockstartemplate.com/design/Blue_Simple_background.jpg" alt=""style="width:100%; height: 100px" /></p>
                <p style="text-align: center;">&nbsp;<span style="color: #3366ff;">Estimado'.$nombre.'bienvenido</span> a <strong><span style="color: #008000;">ELEARNING BMT.</span></strong></p>
                <p style="text-align: center;">Este correo electr&oacute;nico es utilizado para verificar que la direcci&oacute;n de correo que proporcion&oacute; es real.</p>
                <p style="text-align: center;">Para acceder al sistema usted debe utilizar los siguientes datos:</p>
                <div style="text-align: center;">Usuario:</div>
                <div><hr />
                <div style="text-align: center;">usuarioEBF2C7F29A0F</div>
                <hr /></div>
    
                <div>&nbsp;</div>
                <div>
                <div style="text-align: center;">Password:</div>
                <div><hr />
                <div style="text-align: center;">passwordEBF2C7F29A0F</div>
                <hr /></div>
                <div style="text-align: center;">Para acceder al Sistema de cllic en el siguiente enlace</div>
                <div style="text-align: center;">&nbsp;</div>
                <div style="text-align: center;"><a style="text-decoration: none; background: #1ab394; color: #fff; padding: 5px;" title="Elearning" href="localhost/elearning" target="_blank">Ir a Sistema ELEARNING</a></div>
                <div style="text-align: center;">&nbsp;</div>
                </div>
                <p style="text-align: center;"><span style="color: #999999; background-color: #ffffff;">&nbsp;BMT ELEARNING&nbsp;&copy; 2017 Lima, Per&uacute;.</span></p>
                <blockquote>
                <p><span style="color: #999999;"><em>Si recibi&oacute; este correo electr&oacute;nico pero no se registr&oacute; en NeoBux significa que alguien se registr&oacute; utilizando esta direcci&oacute;n de correo electr&oacute;nico. Si no se registr&oacute;, simplemente ignore este correo.</em></span></p>
                </blockquote>';
        return $html;
    }
    
    function getDecimalNumber($numero){// xxx,xxx,xxx.xx
        return number_format($numero,3,'.',",");
    }
    
    function getDecimalNumber2($numero){// xxx,xxx,xxx.xx
        return number_format($numero,2,'.',",");
    }
    /**
     * primer nivel:
     */
  
    function getHTMLPermisos($array, $idPadre, $idPermiso, $idModulo = ID_MODULO_GESTION_OBRA){
        $data = array();
        $data['hasPermiso'] = false;
        $html = '';
        foreach ($array as $row){
            /*log_message('error', $row['idPadre'].' - '.$row['fg_modulo']);*/
            if($row['fg_modulo'] == $idModulo){
                if($row['visible_fg'] == 1){
                    if($row['idPadre'] == $idPadre){
                        $html .= '<li class="navigation__sub navigation__sub--active navigation__sub--toggled">';
                    }else{
                        $html .= '<li class="navigation__sub">';
                    }
                    $html .= '  <a>
                        <i class="'.$row['icono'].'"></i>'
                                                .utf8_decode($row['nombrePadre']).'
                        </a>
                        <ul>';
                    foreach($row['permisos'] as $permiso){
                    
                    
                        //if($permiso['id_permiso'] != 29){
                         
                    
                        if($permiso['id_permiso']==$idPermiso){//valida si cuenta permiso para esa vista
                            $data['hasPermiso'] = true;
                            $html .= '<li class="navigation__active">';
                        }else{
                            $html .= '<li>';
                        }
                        $html .= '<a href="'.$permiso['route'].'">'.utf8_decode($permiso['nombreHijo']).'</a></li>';
                    
                    
                        //}
                    
                    
                    }
                    $html .= ' </ul>
                       </li>';
                }else{

                    foreach($row['permisos'] as $permiso){
                        if($permiso['id_permiso']==$idPermiso){//valida si cuenta permiso para esa vista
                            $data['hasPermiso'] = true;
                        }
                    }
                }
                
            }
        }
        $data['html'] = $html;
        return $data;
    }
    
	function getHTMLPermisosV2($array, $idPadre, $idPermiso, $idModulo = 7){
        $data = array();
        $data['hasPermiso'] = false;
        $html = '';
        foreach ($array as $row){
            /*log_message('error', $row['idPadre'].' - '.$row['fg_modulo']);*/
            if($row['fg_modulo'] == $idModulo){
                if($row['visible_fg'] == 1){
                    if($row['idPadre'] == $idPadre){
                        $html .= '<li class="nav-item dropdown">';
                    }else{
                        $html .= '<li class="nav-item dropdown">';
                    }
                    $html .= '  <a id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" style="cursor:pointer" aria-expanded="false" class="nav-link dropdown-toggle">'
                                    .utf8_decode($row['nombrePadre']).'
                                </a>
                                <ul aria-labelledby="dropdownMenu1" class="dropdown-menu border-0 shadow">';
                                    foreach($row['permisos'] as $permiso){
                                    
                                    
                                        //if($permiso['id_permiso'] != 29){
                                        
                                    
                                        if($permiso['id_permiso']==$idPermiso){//valida si cuenta permiso para esa vista
                                            $data['hasPermiso'] = true;
                                            $html .= '<li class="navigation__active">';
                                        }else{
                                            $html .= '<li>';
                                        }
                                        $html .= '<a href="'.$permiso['route'].'" class="dropdown-item">'.utf8_decode($permiso['nombreHijo']).'</a></li>';
                                    
                                    
                                        //}
                                    
                                    
                                    }
                    $html .= ' </ul>
                       </li>';
                }else{

                    foreach($row['permisos'] as $permiso){
                        if($permiso['id_permiso']==$idPermiso){//valida si cuenta permiso para esa vista
                            $data['hasPermiso'] = true;
                        }
                    }
                }
                
            }
        }
        $data['html'] = $html;
        return $data;
    }
	
    public function removeEnterYTabs($texto){
        return str_replace(PHP_EOL,' ',trim(preg_replace('/[ ]{2,}|[\t]/',' ',$texto)));
    }
}