<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_ejecucion_cuadrilla extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_ejecucion/M_ejecucion_cuadrilla');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>';
            $data["pagina"]="pendiente";
            $data["tabla"]=$this->TablaPendientes();
            $this->load->view('vf_layaout_sinfix/header',$data);
            $this->load->view('vf_layaout_sinfix/cabecera');
            $this->load->view('vf_layaout_sinfix/menu');

            $this->load->view('vf_ejecucion/v_ejecucion_cuadrilla');

            $this->load->view('vf_layaout_sinfix/footer');
            $this->load->view('recursos_sinfix/js');
            $this->load->view('recursos_sinfix/datatable',$data);
           
         }else{
             redirect('login','refresh');
        }
             
    }
    public function TablaPendientes(){
     $html="";
     foreach($this->M_ejecucion_cuadrilla->getListarPendientes($this->session->userdata('idPersonaSession'))->result() as $row){
        if($row->fechaPrevEjec){
             $fasig=explode("-",$row->fechaPrevEjec);
             $rfecha=$fasig[2]."/".$fasig[1]."/".$fasig[0];
        }else{
            $rfecha="";
        }

        if($row->fechaInicio){    
            $nuevafecha = strtotime ( '+30 day' , strtotime ( $row->fechaInicio ) ) ;
            $nuevafecha = date ( 'd-m-Y' , $nuevafecha );    
        }else{
            $nuevafecha = "";    
        }

         $puntitos="";   
         if(strlen($row->nombreProyecto)>40){
             $puntitos="...";   
         }
         $html.='<tr>
                 <td style="text-align:center">
                 
                 <a data-toggle="tooltip" data-trigger="hover" data-placement="top"  data-original-title="Situación" href="#" ><i class="fa fa-pencil"></i></a>
                 </td>
                 <td>'.$row->itemPlan.'</td> 
                 <td>'.$row->subProyectoDesc.'</td>                
                 <td>'.$row->indicador.'</td>
                 <td>'.$row->tipoCentralDesc.'</td>
                 <td>'.$rfecha.'</td>
                 <td>'.$nuevafecha.'</td>
                 <td>'.$row->fechaInicio.'</td>

                 </tr>';
     }    
     return $html;
    }
    
}