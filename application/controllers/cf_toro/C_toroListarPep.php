<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toroListarPep extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('mf_toro/M_toro');
        $this->load->library('lib_utils');
        $this->load->model('mf_ejecucion/M_generales');
        $this->load->helper('url');
    }
    
    public function index(){
        $logedUser = $this->session->userdata('usernameSession');
        if($logedUser != null){
          if(@$_POST["pagina"]=="actualizarpep"){
            $this->M_toro->ActualizarPepR($_POST["id_pep"],$_POST["idSubProyecto"],$_POST["id_tipo_toro"],$_POST["id_categoria_toro"]);
            exit;
          }
            $data["extra"]='<link href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"><link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.0/css/buttons.dataTables.min.css">
                <link href="'.base_url().'public/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/><link href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"><link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.0/css/buttons.dataTables.min.css">';
            $data["pagina"]="toropep";
            $data["tabla"]=$this->tablaToroPep(null);
           // $data["filtrar_proyecto"]=$this->ListarProyecto();
            $data["filtrar_subproyecto"]=$this->ListarSubProyecto();
            //$data["tipo_pep"]=$this->ListarTipoPep();
            
            $permisos =  $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA);
            
            $data['opciones'] = $result['html'];
            
            $this->load->view('vf_layaout_feix/header',$data);
            $this->load->view('vf_layaout_feix/cabecera');
            $this->load->view('vf_layaout_feix/menu',$data);

            $this->load->view('vf_toro/v_ListarToroPep');

            $this->load->view('vf_layaout_sinfix/footer');
            $this->load->view('recursos_feix/js');
            $this->load->view('recursos_feix/fancy',$data);
            $this->load->view('recursos_sinfix/select2');
            $this->load->view('recursos_sinfix/datatable',$data);
            
            $this->load->view('recursos_sinfix/pnotify');
         }else{
             redirect('login','refresh');
        }
             
    }    
    public function tablaToroPep($filtro){
    $pep=$this->M_toro->ListarPepR($filtro);
     $html='
            <table id="simpletable" style="font-size: x-small;" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
                <thead>
                  <tr class="table-primary" style="color:#fff;background-color:#2196F3">
                      <th></th>
                      <th>PEP</th>
                      <th>DETALLE</th>
                      <th>TIPO</th>
                      <th>SUBPROYECTO</th>                             
                      <th>PRESUPUESTO</th>
                      <th>REAL</th>
                      <th>COMPROM</th>
                      <th>PLANRES</th>
                      <th>DISPONIBLE</th>
					  <th>DISP. PROYECTADO</th>
                      <th>TORO</th>      
                      <th>FECHA PROGRAMACION</th>				  					  
                  </tr>                                                                       
                </thead>
            <tbody>';
    $i=0;            
    foreach ($pep->result() as $row ) {
        $i++;
        /*
    $subproyecto=$this->ListarSubProyecto($i,$row->idSubProyecto);
    $tipo=$this->ListarTipoToro($i,$row->id_tipo_toro);
    $categoria=$this->ListarCategoriaToro($i,$row->id_categoria_toro);     
    */   
    $html.='
    <form method="post" action="listar_pep?pagina=actualizarpep">

    <tr>    
    <input type="hidden" id="id_pep_'.$i.'" value="'.$row->pep1.'">
    <td>
        '.(($row->reg_tmp == 2) ?'<a data-id_pt="'.$row->id_peptoro.'" data-id_td="'.$row->id_toro_detalle.'" onclick="deletePepToro(this)"><img alt="Eliminar" height="20px" width="20px" src="public/img/iconos/delete.png"></a>' : '').
        '<a class="editar_pep" href="#"><i class="zmdi zmdi-edit zmdi-hc-fw"></i></a></td>
    <td>'.$row->pep1.'</td>
    <td>'.str_replace('"', '',$row->detalle).'</td>
    <td>'.str_replace('"', '',$row->tnombre).'</td>
    <td>'.str_replace('"', '',$row->subProyectoDesc).'</td>    
    <td>'.str_replace('"', '',$row->presupuesto).'</td>
    <td>'.str_replace('"', '',$row->real).'</td>
    <td>'.str_replace('"', '',$row->comprometido).'</td>
    <td>'.str_replace('"', '',$row->planresord).'</td>
    <td>'.str_replace('"', '',$row->disponible).'</td>   
	<td>'.$row->monto_temporal.'</td>	
    <td>'.$row->id_toro.'</td>
    <td>'.$row->fecha_programacion.'</td>	
    </tr>
    </form>
    ';                
                }
    $html.="</tbody></table>";    
     return $html;                            
    }
    
  public function ListarSubProyecto(){   
       $html        =   '<select id="subproyectoFiltro" class="form-control select2" name="subproyectoFiltro" onchange="filtrarTabla()">';
       $proyecto    =   $this->M_generales->ListarSubProyecto(null);
       $html.='<option value="">::SELECCIONAR</option>';
       foreach($proyecto->result() as $row){
            
            $html.='<option  value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';     
       }
       $html.="</select>";
       return $html;  
   }
   
   public function ListarCategoriaToro($i,$val){   
       $html        =   '<select id="categoria_'.$i.'" class="form-control " name="categoria"><option>Seleccione CategorÃƒÂ­a</option>';
       $proyecto    =   $this->M_generales->ListarCategoriaToro();
       foreach($proyecto->result() as $row){
            $extra="";
            if($val ==  $row->id_categoria_toro){
                $extra="selected";
            }
            $html.='<option '.$extra.' value="'.$row->id_categoria_toro.'">'.$row->nombre.'</option>';     
        }
       $html    .="</select>";
       return $html;  
   }
   
   public function ListarTipoToro($i,$val){   
       $html        =   '<select id="tipo_'.$i.'" class="form-control " name="tipo"><option>Seleccione Tipo</option>';
       $proyecto    =   $this->M_generales->ListarTipoToro();
       foreach($proyecto->result() as $row){
            $extra  =   "";
            if($val ==  $row->id_tipo_toro){
                $extra="selected";
            }    
            $html.='<option '.$extra.' value="'.$row->id_tipo_toro.'">'.$row->nombre.'</option>';     
        }
       $html.="</select>";
       return $html;  
   }
   
   public function deleteToroTemp(){
       $data['error']    = EXIT_ERROR;
       $data['msj'] = null;
       try{
       
           $id_pt = $this->input->post('id_pt');
           $id_td = $this->input->post('id_td');
           $data = $this->M_toro->deletePepTemp($id_td, $id_pt);
          // $data['tbPep1Pep2'] = $this->makeHTLMTablaPep1Pep2($this->m_subproy_pep_grafo->getPep1Pep2());
       
       }catch(Exception $e){
           $data['msj'] = $e->getMessage();
       }
        
       echo json_encode(array_map('utf8_encode', $data));
   
   }
   
   public function filtrarTabla(){
       $data['error']    = EXIT_ERROR;
       $data['msj'] = null;
       try{
            
           $idSubProyecto = $this->input->post('id_subPro');
           $data["tabla"]=$this->tablaToroPep($idSubProyecto);    
           $data['error']    = EXIT_SUCCESS;
       }catch(Exception $e){
           $data['msj'] = $e->getMessage();
       }   
       echo json_encode(array_map('utf8_encode', $data));
        
   }
}