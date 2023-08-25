<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toroDetalle extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_toro/M_toro');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data["extra"]='<link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen">';
            
            $data["pagina"]="detalle_toro";
            $data["tabla"]=$this->tablaToro($_GET["id"]);
            $data["dinero"]=$this->M_toro->toroId($_GET["id"]);
            $data["suma"]=$this->M_toro->SumaDetalle($_GET["id"]);
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA);
               $data['opciones'] = $result['html'];
            
            $this->load->view('vf_layaout_feix/header',$data);
            $this->load->view('vf_layaout_feix/cabecera');
            $this->load->view('vf_layaout_feix/menu',$data);

            $this->load->view('vf_toro/v_DetalleToro',$data);

            $this->load->view('vf_layaout_sinfix/footer');
            $this->load->view('recursos_feix/js');
            $this->load->view('recursos_sinfix/datatable',$data);
            $this->load->view('recursos_sinfix/fancy',$data);
            
         }else{
             redirect('login','refresh');
        }
             
    }
    public function tablaToro($id){
        $detalle=$this->M_toro->ToroDetalleId($id);
        $html='
            <table id="simpletable" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
            <thead>
                          <tr class="table-primary">
                          <th>Acción</th>
                          <th>TORO</th>
                          <th>PEP</th>
                          <th>Proyecto</th>
                          <th>SubProyecto</th>
                          <th>Valor</th>  
                          <th>Usuario</th>                          
                          <th>F. Creación</th>
                          </tr>
            </thead>
            <tbody>';
            $monto_total=0;
        foreach ($detalle->result() as $row) {
            $monto_total=$monto_total+$row->monto_inicial;
         $html.='
         <tr>
         <td></td>
         <td>'.$row->id_toro.'</td>
         <td>'.$row->id_pep.'</td>
         <td>'.$row->proyectoDesc.'</td>
         <td>'.$row->subProyectoDesc.'</td>
         <td>'.number_format($row->monto_inicial,2,".",",").'</td>
         <td>'.$row->nombre.'</td>
         <td>'.$row->fecha.'</td>
         </tr>
         ';       
            }
       $html.="
       </tbody>
       <tfoot>
       <td>Total Consumido</td>
       <td></td>
       <td></td>
       <td></td>
       <td></td>
       <td  style='color:#ec3305'>".number_format($monto_total,2,".",",")."</td>
       <td></td>
       <td></td>
       </tfoot>
       </table>";    
     return $html;         
    }
    
    }