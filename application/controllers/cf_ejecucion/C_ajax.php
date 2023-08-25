<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_ajax extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->model('mf_ejecucion/M_actualizar_porcentaje');
        $this->load->model('mf_ejecucion/M_obra_terminar');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
        if(@$_GET["pagina"]=="listar_proyecto"){
            echo $this->SubproyectoProyecto($_GET["id"]);
        }
        // if(@$_GET["pagina"]=="porcentaje"){
        //     echo $this->ActualizarPorcentaje();    
        // }
        //if(@$_GET["pagina"]=="porcentaje_zonal"){
          //  echo $this->ActualizarPorcentaje_zonal();    
        //}
        // if(@$_POST["pagina"]=="ejecutarporcentaje"){
        // $this->M_actualizar_porcentaje->ActualizarPorcentajeM($_POST["id"],$_POST["id_planobra_actividad"],$_POST["id_subactividad"],$_POST["select_cuadrilla"],$_POST["fporcentaje"],$_POST["conversacion"],'',time());            
        // }
        if(@$_POST["pagina"]=="ejecutarporcentajez"){
        $this->M_actualizar_porcentaje->ActualizarPorcentajeZ($_POST["id"],$_POST["id_planobra_actividad_z"],$_POST["id_estacion"],$_POST["select_cuadrilla"],$_POST["fporcentaje"],$_POST["conversacion"],'',time());            
        }
        if(@$_POST["pagina"]=="cambiarcuadrilla"){
        $this->M_actualizar_porcentaje->CambiarCuadrilla($_POST["param"],$_POST["id_usuario"],$_POST["titulo"]);    
        }
        if(@$_POST["pagina"]=="cambiarcuadrillaz"){
        $this->M_actualizar_porcentaje->CambiarCuadrillaz($_POST["param"],$_POST["id_usuario"],$_POST["titulo"]);    
        }
        if(@$_GET["pagina"]=="detalle_obra"){ 
            $this->Cuadrilla_agenda($_GET["id_planobra_actividad"],$this->session->userdata('idPersonaSession'),$_GET["fporcentaje"],$_GET["conversacion"],$_GET["coordenadas"]);
        }
        if(@$_GET["pagina"]=="upload"){
            $this->SubirImagen();
        }
        if(@$_POST["pagina"]=="obra_terminar"){
            $this->ObraTerminarImagen($_FILES['file'],$_POST["id"]);
        }
        if(@$_POST["pagina"]=="preliquidar"){
            $this->PreLiquidar($_POST["sid"]);
        }
        if(@$_POST["pagina"]=="liquidar"){
            $this->Liquidar($_POST["id"]);
        }
         }else{
             redirect('login','refresh');
        }
             
    }

public function SubproyectoProyecto($id){
    if($id){
        $subproyecto=$this->M_generales->ListarSubProyectoId($id);
    }else{
        $subproyecto=$this->M_generales->ListarSubProyecto();
    }   
$option="<option value='0'>Seleccionar SubProyecto</option>";
foreach ($subproyecto->result() as $row) {
    $option.='<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
        }
return $option;
}
public function PreLiquidar($id){
$this->M_obra_terminar->PreLiquidar($id);    
}    
public function Cuadrilla_agenda($id_planobra_actividad,$select_cuadrilla,$fporcentaje,$conversacion,$cordenadas){
    if(!$this->session->userdata("zonasSession")){
        $this->M_actualizar_porcentaje->ActualizarPorcentajeM('',$id_planobra_actividad,'',$select_cuadrilla,$fporcentaje,$conversacion,$cordenadas,$this->session->userdata('tiempo'));
    }else{
        $this->M_actualizar_porcentaje->ActualizarPorcentajeZ('',$id_planobra_actividad,'',$select_cuadrilla,$fporcentaje,$conversacion,$cordenadas,$this->session->userdata('tiempo'));
    }
    $agenda=$this->M_generales->AgendaImagenId($this->session->userdata('tiempo'));
    if($agenda){
        foreach($agenda->result() as $row){
            $image = 'uploads/sinfix/'.$row->valor;
            list($width, $height) = getimagesize($image);
            $fontSize = 20;
            $angle = 0;
            $text="x,y :".$cordenadas;
            $xPosition = 10; 
            $yPosition = 110; 
            $newImg = imagecreatefromjpeg($image);
            $font = 'public/fonts/gothic.ttf';
            $fontColor_red = imagecolorallocate($newImg, 255, 0, 0);
            imagettftext($newImg,$fontSize,$angle,$xPosition,$yPosition,$fontColor_red,$font,$text);
            imagejpeg($newImg,$image);
            imagedestroy($newImg);
        }
    }
}    
public function ActualizarPorcentaje(){    
    $html='    
            <div class="row table-responsive" style="background-color:#fff;margin-bottom:20px">
                <table class="table table-striped table-bordered nowrap jj" style="margin:25px">
                    <thead>
                        <tr>
                            <th>SubActividad</th>
                            <th>Cuadrilla</th>
                            <th>Contrata</th>
                            <th>Porcentaje</th>
                            <th>Comentarios</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                <tbody>';
                $usuario=$this->M_generales->idUsuarioItem($_GET["id"]);
                $i=0;

                foreach($this->M_generales->ListarActividadesEstacion($_GET["estacion"])->result() as $row) {
                    $i++;
                    if($isu){ 
                        $porcentaje=$this->M_generales->Porcentaje($isu["id_planobra_actividad"]);
                        if($porcentaje["valor"]>=0&&$porcentaje["valor"]<25){$porcentaje["valor"]=0;}
                        if($porcentaje["valor"]>=25&&$porcentaje["valor"]<50){$porcentaje["valor"]=25;}
                        if($porcentaje["valor"]>=50&&$porcentaje["valor"]<75){$porcentaje["valor"]=50;}
                        if($porcentaje["valor"]>=75&&$porcentaje["valor"]<100){$porcentaje["valor"]=75;}
                    if(!$porcentaje["valor"]){
                        $porcentaje["valor"]=0;
                    }
                    }else{
                    $generica=$this->M_generales->IdCuadrilaGenerica($usuario["empresaColabDesc"]);
                    $isu["id_usuario"]=$generica["id_usuario"];
                    $isu["id_planobra_actividad"]=0; 
                    $porcentaje["valor"]=0;
                    }
                    $html.='
                    <tr>
                    <td class="subactividad"><b>'.$row->snombre.'</b></td>
                    <td class="cuadrilla">
                    <select title="'.$i.'" name="select_cuadrilla" id="'.$row->id_subactividad."|".$_GET["id"].'" class="form-control js-example-basic-single cambiar_cuadrilla cuadrilla_'.$i.'">';
                    $listado=$this->M_generales->ListarCuadrillaEmpresa($usuario["idEmpresaColab"]);
                    foreach ($listado->result() as $lista) {    
                        $html.='<option ';
                        if($isu["id_usuario"]==$lista->id_usuario){$html.=' selected ';}
                        $html.='value="'.$lista->id_usuario.'">'.$lista->usuario.'</option>';
                    }
                    $html.='</select>    
                    </td>
                    <td><b>'.strtoupper($usuario["empresaColabDesc"]).'</b></td>
                    <td class="porcentaje">
                        <input type="hidden" class="id" name="id" value="'.$_GET["id"].'"> 
                        <input type="hidden" id="id_planobra_actividad_'.$i.'" name="id_planobra_actividad" value="'.$isu["id_planobra_actividad"].'">
                        <input type="hidden" id="select_cuadrilla_'.$i.'" name="select_cuadrilla" value="'.$isu["id_usuario"].'">   
                        <input type="hidden" id="id_subactividad_'.$i.'" name="id_subactividad_'.$i.'" value="'.$row->id_subactividad.'">
                        <select name="fporcentaje" id="fporcentaje_'.$i.'">';
                            if($this->session->userdata('idPerfilSession')==5) {
                                $cf=$porcentaje["valor"];
                            } else {
                                $cf=0;
                            }
                            for($f=$cf;$f<=100;$f=$f+25){
                                $html.='<option value="'.$f.'"';
                                            if($porcentaje["valor"]==$f){$html.=' selected ';}
                                            $html.='>'.$f.'%
                                        </option>';
                            }
                        $html.='</select>
                    </td>
                    <td>
                        <textarea id="conversacion_'.$i.'" name="conversacion"></textarea>
                    </td>
                    <td>
                        <input type="submit" value="Actualizar" class="btn btn-primary m-b-0 ejecutarp" id="'.$i.'" data-id_estacion="'.$_GET['estacion'].'" onclick="ejecutarPorcentaje($(this))">';
                            if($isu["id_planobra_actividad"]){
                                $html.=' <button class="btn  btn-info ver_detallec" id="'.$isu["id_planobra_actividad"].'">Ver Detalle</button>';
                            }
                    $html.='</td>
                    </tr>';
                }
            $html.='</tbody>
            </table>
        </div>';
    return $html;   
    }

public function SubirImagen(){    
    $allowed = array('jpeg', 'jpg', 'gif','zip');
    if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
        $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
    if(!in_array(strtolower($extension), $allowed)){
            echo '{"status":"error"}';
            exit;
        }
        $id=$this->M_generales->ultimo_registro("id_agenda_imagen","agenda_imagen")+1;
    if(move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/sinfix/'.$id.".jpg")){
        $image = 'uploads/sinfix/'.$id.".jpg";
        list($width, $height) = getimagesize($image);
        $fontSize = 30;
        $angle = 0;
        $text = date("Y-m-d H:i:s");
        $text1="Usuario : ".strtoupper ($this->session->userdata('idPersonaSession'));
        $xPosition = 10; 
        $yPosition = 30; 
        $newImg = imagecreatefromjpeg($image);
        $font = 'fonts/gothic.ttf';
        $fontColor_red = imagecolorallocate($newImg, 255, 0, 0);
        imagettftext($newImg,$fontSize,$angle,$xPosition,$yPosition,$fontColor_red,$font,$text);
        imagettftext($newImg,$fontSize,$angle,10,70,$fontColor_red,$font,$text1);
        imagejpeg($newImg,'uploads/sinfix/'.$id.".jpg");
        imagedestroy($newImg);
        $this->M_generales->IngresarAgendaImagen('',$this->session->userdata('tiempo'),$id.'.jpg',date("Y-m-d H:i:s"));   
        echo '{"status":"success"}';
        exit;
        }
    }
    echo '{"status":"error"}';
    exit;   
}
public function ObraTerminarImagen($archivo,$id){
$countfiles = count($archivo['name']);
for($i=0;$i<$countfiles;$i++){
$img=strtolower(str_replace("ñ", "n",str_replace(" ", "", time()."-".$archivo['name'][$i])));
if(move_uploaded_file($archivo['tmp_name'][$i],'uploads/sinfix/obra/'.$img)){
$this->M_obra_terminar->IngresarAgenda($id,$this->session->userdata("idPersonaSession"),$img,date("Y-m-d H:i:s"),3);
}
}
}
public function Liquidar($id){
$proyecto=$this->M_obra_terminar->Liquidar($id,date("Y-m-d H:i:s"));
$nzip=str_replace(" ", "", $proyecto["proyectoDesc"]);
$zip = new ZipArchive();    
$filename = "uploads/zip/".$id."-".$nzip.".zip";
if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
    exit("No se puede crear <$filename>\n");
}
$archivo=$this->M_obra_terminar->ObraTerminarId($id);
if($archivo){
foreach($archivo->result() as $row){
$zip->addFile("uploads/sinfix/obra/".$row->nombre,$row->nombre);
}
$zip->close();
}
?>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    parent.$.fancybox.close();parent.location.reload(); 
}); 
</script>
<?php
}
}





?>
