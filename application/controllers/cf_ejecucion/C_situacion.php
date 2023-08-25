<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_situacion extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_situacion');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            if(@$_POST["id"]){
                 $this->M_situacion->GuardarSituacion($_POST["id"],$_POST["id_actividad"],$_POST["id_subactividad_estado"],$_POST["id_actividad"],$_POST["observacion"]);
?>
                <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
                <script type="text/javascript">
                $(document).ready(function(){
                parent.$.fancybox.close();parent.location.reload(); 
                }); 
                </script>
<?php        
            exit; 
            }
            if($this->M_situacion->getUltimoEstado($_GET["id"])->result()){
            foreach($this->M_situacion->getUltimoEstado($_GET["id"])->result() as $row){
            $data["valor"]=$row->id_subactividad_estado;    
            }                      
            }else{
            $data["valor"]=5;    
            }

            $data["option"]=$this->listar_situacion($data["valor"]);
            $data["pagina"]="situacion";
            $data["extra"]="";
            $data["existe"]="";
            if($this->M_situacion->getSituaciones($_GET["id"])->num_rows()!=0){
            $data["existe"]=$this->listar_situaciones($this->M_situacion->getSituaciones($_GET["id"]));
            }
            $this->load->view('vf_layaout_sinfix/header',$data);
            
            $this->load->view('vf_ejecucion/v_situacion',$data);

            $this->load->view('vf_layaout_sinfix/footer');
            $this->load->view('recursos_sinfix/js');
            
         }else{
             redirect('login','refresh');
        }
             
    }
    public function listar_situacion($valor){
        $html='';
        $extra="";
        foreach($this->M_situacion->getListarSituacion()->result() as $row){
            if($row->id_subactividad_estado==$valor){ $extra= "selected='selected'";}else{$extra="";}
        $html.='<option '.$extra.' value="'.$row->id_subactividad_estado.'">'.$row->nombre.'</option>';
        }
        return $html;
    }
    public function listar_situaciones($arr){
        $html='<table id="j" class="table table-striped table-bordered nowrap">
                <thead>
                <tr>
                <th>Situacion</th>
                <th>Comentario</th>
                <th>Usuario</th>
                <th>Fecha</th>
                </tr>
                </thead>
                <tbody>';
        foreach ($arr->result() as $row ) {

        $html.='<tr>
                <td>'.$row->nombre.'</td>    
                <td>'.$row->comentario.'</td>
                <td>'.$row->usuario.'</td>
                <td>'.$row->fechab.'</td>
                </tr>';   
        }
        $html.='</tbody>
               </table> ';
               return $html;
 
    }
    
    
}