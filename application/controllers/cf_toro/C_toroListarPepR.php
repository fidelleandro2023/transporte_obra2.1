<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_toroListarPepR extends CI_Controller {

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
           
            $data["extra"]='<link href="'.base_url().'public/vendors/bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/><link href="'.base_url().'public/bower_components/notify/pnotify.custom.min.css" rel="stylesheet" type="text/css"/><link rel="stylesheet" href="'.base_url().'public/fancy/source/jquery.fancybox.css" type="text/css" media="screen"><link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.0/css/buttons.dataTables.min.css">';
            $data["pagina"]="reporte_toro";
            
            $proyecto = (isset($_GET['pro']) ? $_GET['pro'] : '');
           if($proyecto!=''){
			   $dataArrayTabla = $this->M_toro->FiltrarPepReporte($proyecto, '', '');
			   if(!$dataArrayTabla->result()) {
				   $flg_tipo = (isset($_GET['flg_tipo']) ? $_GET['flg_tipo'] : '');
				   $dataArrayTabla = $this->M_toro->getDetallePepBianual($flg_tipo);
			   }
                $data["tabla"]  = $this->tablaToroPepF($dataArrayTabla);                
                //$data["showFil"]= 0;
            }else{
                $data["tabla"]=$this->tablaToroPep();
               // $data["showFil"]= 1;
            }

            $data["filtrar_proyecto"]=$this->ListarProyecto($proyecto);
            $data["filtrar_subproyecto"]=$this->ListarSubProyecto($proyecto);
            $data["tipo_pep"]=$this->ListarTipoPep();  
            
            $permisos =  $this->session->userdata('permisosArbol');
            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_REPORTES_V, ID_PERMISO_HIJO_DETALLE_OBRA);
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_ADMINISTRATIVO_PRESUPUESTO, ID_PERMISO_HIJO_DETALLE_OBRA, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            
            $this->load->view('vf_layaout_feix/header',$data);
            $this->load->view('vf_layaout_feix/cabecera');
            $this->load->view('vf_layaout_feix/menu',$data);

            $this->load->view('vf_toro/v_ListarToroPepR');

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
    public function tablaToroPep(){
    $pep=$this->M_toro->ListarPepReporte();
     $html='
            <table id="tableReporte" style="font-size: x-small;" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
            <thead>
                      <tr class="table-primary" style="color:#fff;background-color:#2196F3">
                          <th>PEP</th>
                          <th>DETALLE</th>
                          <th>TIPO</th>
                          <th>SUBPROYECTO</th>                            
                          <th>PRESUPUESTO</th>
                          <th>REAL</th>
                          <th>COMPROM</th>
                          <th>PLANRES</th>
                          <th>DISPONIBLE</th> 
                          <th>DISP. PROY</th>
                          <th>% AVANCE</th>
                          <th>TORO</th>
                          <th>FECHA PROGRAMACION</th>
                      </tr>
           <tfoot>
                      <tr style="color:#fff;background-color:#2196F3">
                          <th>PEP</th>
                          <th>DETALLE</th>
                          <th>TIPO</th>
                          <th>SUBPROYECTO</th>                            
                          <th>PRESUPUESTO</th>
                          <th>REAL</th>
                          <th>COMPROM</th>
                          <th>PLANRES</th>
                          <th>DISPONIBLE</th> 
                          <th>DISP. PROY</th>
                          <th>% AVANCE</th>
                          <th>TORO</th>
                          <th>FECHA PROGRAMACION</th>           
                        </tr>
                    </tfoot>
            </thead>
            <tbody>';
    $i=0;            
    $total_monto_tmp    = 0;
    $tota_presupuesto   = 0;
    $tota_real          = 0;
    $tota_comprometido  = 0;
    $total_planresord   = 0;
    $total_disponible   = 0;
    foreach ($pep->result() as $row ) {
        $i++;
        $presupuesto  = str_replace('"', '',$row->presupuesto);
        $real         = str_replace('"', '',$row->real);
        $comprometido = str_replace('"', '',$row->comprometido);
        $panresord    = str_replace('"', '',$row->planresord);
        $disponible   = str_replace('"', '',$row->disponible);
        $porcentaje   = (($presupuesto>0) ? round((($row->monto_temporal*100)/str_replace(',', '',$presupuesto))) : 0);
        $html.='<tr '.(($row->dif_meses >= 3 && $porcentaje >= 70) ? 'style="color:red"' : '').'>    
                    <input type="hidden" id="id_pep_'.$i.'" value="'.$row->pep1.'">
                    <td><a data-toggle="popover" data-trigger="hover" title="'.$row->pep1.'" data-content="'.$row->comentario.'">'.$row->pep1.'</a></td>
                    <td>'.str_replace('"', '',$row->detalle).'</td>
                    <td>'.str_replace('"', '',$row->tnombre).'</td>
                    <td>'.str_replace('"', '',$row->subProyectoDesc).'</td>    
                    <td>'.$presupuesto.'</td>
                    <td>'.$real.'</td>
                    <td><a  data-pep1="PEP ' . $row->pep1 . '" onclick="getDetallePTRS(this,1)">' . $comprometido . '</a></td>
                    <td><a  data-pep1="PEP ' . $row->pep1 . '" onclick="getDetallePTRS(this,2)">' . $panresord . '</a></td>
                    <td>'.$disponible.'</td>
                    <td>'.number_format($row->monto_temporal, 2, '.', ',').'</td>
                    <td>'.$porcentaje.'%</td>    
                    <td>'.$row->id_toro.'</td>      
                    <td>'.$row->fecha_programacion.'</td>
                </tr>';  
        $tota_presupuesto   =   ($tota_presupuesto  +   str_replace(',', '',$presupuesto));
        $tota_real          =   ($tota_real         +   str_replace(',', '',$real));
        $tota_comprometido  =   ($tota_comprometido +   str_replace(',', '',$comprometido));
        $total_planresord   =   ($total_planresord  +   str_replace(',', '',$panresord));
        $total_disponible   =   ($total_disponible  +   str_replace(',', '',$disponible));
        $total_monto_tmp    =   ($total_monto_tmp   +   $row->monto_temporal);
    }
    
        $html.='<tr>
                    <td>TOTAL</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>'.number_format($tota_presupuesto, 2, '.', ',').'</td>
                    <td>'.number_format($tota_real, 2, '.', ',').'</td>
                    <td>'.number_format($tota_comprometido, 2, '.', ',').'</td>
                    <td>'.number_format($total_planresord, 2, '.', ',').'</td>
                    <td>'.number_format($total_disponible, 2, '.', ',').'</td>
                    <td>'.number_format($total_monto_tmp, 2, '.', ',').'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';
    $html.="</tbody></table>";    
     return $html;                            
    }
  
  public function tablaToroPepF($consulta){

    $pep=$consulta;
     $html='
            <table id="tableReporte" style="font-size: x-small;" class="table table-hover display  pb-30 table-striped table-bordered nowrap" >
            <thead>
                      <tr class="table-primary" style="color:#fff;background-color:#2196F3">
                          <th>PEP</th>
                          <th>DETALLE</th>
                          <th>TIPO</th>
                          <th>SUBPROYECTO</th>                            
                          <th>PRESUPUESTO</th>
                          <th>REAL</th>
                          <th>COMPROM</th>
                          <th>PLANRES</th>
                          <th>DISPONIBLE</th> 
                          <th>DISP. PROY</th>
                          <th>% AVANCE</th>
                          <th>TORO</th>
                          <th>FECHA PROGRAMACION</th>
                      </tr>
         <tfoot>
                      <tr style="color:#fff;background-color:#2196F3">
                          <th>PEP</th>
                          <th>DETALLE</th>
                          <th>TIPO</th>
                          <th>SUBPROYECTO</th>                            
                          <th>PRESUPUESTO</th>
                          <th>REAL</th>
                          <th>COMPROM</th>
                          <th>PLANRES</th>
                          <th>DISPONIBLE</th> 
                          <th>DISP. PROY</th>
                          <th>% AVANCE</th>
                          <th>TORO</th>
                          <th>FECHA PROGRAMACION</th>           
                        </tr>
                    </tfoot>
            </thead>
            </thead>
            <tbody>';
    $i=0;            
    $total_monto_tmp    = 0;
    $tota_presupuesto   = 0;
    $tota_real          = 0;
    $tota_comprometido  = 0;
    $total_planresord   = 0;
    $total_disponible   = 0;
    foreach ($pep->result() as $row ) {
        $i++;
        $presupuesto  = str_replace('"', '',$row->presupuesto);
        $real         = str_replace('"', '',$row->real);
        $comprometido = str_replace('"', '',$row->comprometido);
        $panresord    = str_replace('"', '',$row->planresord);
        $disponible   = str_replace('"', '',$row->disponible);
        $porcentaje   = (($presupuesto>0) ? round((($row->monto_temporal*100)/str_replace(',', '',$presupuesto))) : 0);
        $html.='<tr '.(($row->dif_meses >= 3 && $porcentaje >= 70) ? 'style="color:red"' : '').'>    
                    <input type="hidden" id="id_pep_'.$i.'" value="'.$row->pep1.'">
                    <td><a data-toggle="popover" data-trigger="hover" title="'.$row->pep1.'" data-content="'.$row->comentario.'">'.$row->pep1.'</a></td>
                    <td>'.str_replace('"', '',$row->detalle).'</td>
                    <td>'.str_replace('"', '',$row->tnombre).'</td>
                    <td>'.str_replace('"', '',$row->subProyectoDesc).'</td>    
                    <td>'.$presupuesto.'</td>
                    <td>'.$real.'</td>
                    <td><a  data-pep1="PEP ' . $row->pep1 . '" onclick="getDetallePTRS(this,1)">' . $comprometido . '</a></td>
                    <td><a  data-pep1="PEP ' . $row->pep1 . '" onclick="getDetallePTRS(this,2)">' . $panresord . '</a></td>
                    <td>'.$disponible.'</td>
                    <td>'.number_format($row->monto_temporal, 2, '.', ',').'</td>
                    <td>'.$porcentaje.'%</td>    
                    <td>'.$row->id_toro.'</td>      
                    <td>'.$row->fecha_programacion.'</td>
                </tr>';  
        
        $tota_presupuesto   =   ($tota_presupuesto  +   str_replace(',', '',$presupuesto));
        $tota_real          =   ($tota_real         +   str_replace(',', '',$real));
        $tota_comprometido  =   ($tota_comprometido +   str_replace(',', '',$comprometido));
		$total_planresord   =   ($total_planresord  +   (is_numeric(str_replace(',', '',$panresord)) ? str_replace(',', '',$panresord) : 0));
        $total_disponible   =   ($total_disponible  +   (is_numeric(str_replace(',', '',$disponible)) ? str_replace(',', '',$disponible) : 0));
        $total_monto_tmp    =   ($total_monto_tmp   +   $row->monto_temporal);
    }
    
        $html.='<tr>
                    <td>TOTAL</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>'.number_format($tota_presupuesto, 2, '.', ',').'</td>
                    <td>'.number_format($tota_real, 2, '.', ',').'</td>
                    <td>'.number_format($tota_comprometido, 2, '.', ',').'</td>
                    <td>'.number_format($total_planresord, 2, '.', ',').'</td>
                    <td>'.number_format($total_disponible, 2, '.', ',').'</td>
                    <td>'.number_format($total_monto_tmp, 2, '.', ',').'</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';
    $html.="</tbody></table>";    
     return $html;                      
    }
   public function ListarCategoriaToro($i,$val){
   
   $html='<select id="categoria_'.$i.'" class="form-control " name="categoria"><option>Seleccione CategorÃƒÆ’Ã‚Â­a</option>';
   $proyecto=$this->M_generales->ListarCategoriaToro();
   foreach($proyecto->result() as $row){
    $extra="";
    if($val==$row->id_categoria_toro){$extra="selected";}
   $html.='<option '.$extra.' value="'.$row->id_categoria_toro.'">'.$row->nombre.'</option>';     
    }
   $html.="</select>";
   return $html;  
   }
   public function ListarTipoToro($i,$val){
   
   $html='<select id="tipo_'.$i.'" class="form-control " name="tipo"><option>Seleccione Tipo</option>';
   $proyecto=$this->M_generales->ListarTipoToro();
   foreach($proyecto->result() as $row){
    $extra="";
    if($val==$row->id_tipo_toro){$extra="selected";}    
   $html.='<option '.$extra.' value="'.$row->id_tipo_toro.'">'.$row->nombre.'</option>';     
    }
   $html.="</select>";
   return $html;  
   }
   
  public function ListarProyecto($idProyecto){   
       $html='<select onchange="onchangeProyecto()" multiple id="filtrar_proyecto_r" class="form-control select2">';
       $proyecto=$this->M_toro->ListarProyecto();   
       foreach($proyecto->result() as $row){ 
           $html.='<option '.(($row->idProyecto==$idProyecto) ? 'selected' : '').' value="'.$row->idProyecto.'">'.$row->proyectoDesc.'</option>';        
        }
       $html.="</select>";
       return $html;  
   }
   
   public function ListarSubProyecto($idProyecto){   
       $html='<select onchange="filtrarTablaReporteR()" multiple id="filtrar_subproyecto_r" class="form-control select2" >';   
       if($idProyecto!=''){
           $res =    $this->M_toro->getAllSubProyectosByIdProyectos($idProyecto);
           foreach ($res->result() as $row ) {
               $html.='<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
           }
       }
       $html.='</select>';
       return $html;  
   }
   
   public function ListarTipoPep(){
   
   $html='<select onchange="filtrarTablaReporteR()" multiple class="form-control select2" id="filtrar_tipo_r">';
   $proyecto=$this->M_toro->ListarTipoPep();
   foreach($proyecto->result() as $row){
   $html.='<option value="'.$row->id_tipo_toro.'">'.$row->nombre.'</option>';     
    }
   $html.="</select>";
   return $html;  
   }
   
   public function getHTMLChoiceSubProyectos(){
       $data['error']    = EXIT_ERROR;
       $data['msj'] = null;
       try{            
           $proyecto    = $this->input->post('proy');            
           $html = "";
           if($proyecto !=  ''){
               $res =    $this->M_toro->getAllSubProyectosByIdProyectos($proyecto);
               foreach ($res->result() as $row ) {
                   $html.='<option value="'.$row->idSubProyecto.'">'.$row->subProyectoDesc.'</option>';
               }
           }
           $data["choice"] = $html;
           $data['error']  = EXIT_SUCCESS;
       }catch(Exception $e){
           $data['msj'] = $e->getMessage();
       }
       echo json_encode(array_map('utf8_encode', $data));
        
   }
   
   public function filtrarTabla(){
       $data['error']    = EXIT_ERROR;
       $data['msj'] = null;
       try{
   
           $proyecto    = $this->input->post('proy');
           $Subproyecto = $this->input->post('subProy');
           $tipo        = $this->input->post('tipo'); 
           
            $data["tabla"]  = $this->tablaToroPepF($this->M_toro->FiltrarPepReporte($proyecto, $Subproyecto, $tipo));
            $data['error']  = EXIT_SUCCESS;
       }catch(Exception $e){
           $data['msj'] = $e->getMessage();
       }
       echo json_encode(array_map('utf8_encode', $data));
   
   }
   
    public function getDetallePTRS()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {
            $pep1 = $this->input->post('pep1') ? $this->input->post('pep1') : null;
            $flgDetalle = $this->input->post('flgDetalle') ? $this->input->post('flgDetalle') : null;
            if ($pep1 != null && $flgDetalle != null) {
                if ($flgDetalle == 1) {
                    $data['tablaDetallePTRS'] = $this->makeHTLMTablaPTRDetalle($this->M_toro->getDetallePTRByPEB($pep1), 'COMPROMETIDO');
                }else{
                    $data['tablaDetallePTRS'] = $this->makeHTLMTablaPTRDetalle($this->M_toro->getDetallePTR2($pep1), 'PLANRES');
                }
                if ($data['tablaDetallePTRS'] != null) {
                    $data['error'] = EXIT_SUCCESS;
                } else {
                    $data['msj'] = "No hay PTRS para mostrar";
                }

            } else {
                $data['msj'] = "Hubo un error al traer el detalle!!";
            }

        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function makeHTLMTablaPTRDetalle($listaPTRS, $titulo)
    {
        $html = null;

        if (count($listaPTRS) > 0) {

            $html = '  <table style="font-size: 10px;" id="tabla_detalle" class="table table-bordered">
                        <thead class="thead-default">
                            <tr role="row">
                                <th style="text-align:center">ITEMPLAN</th>
                                <th style="text-align:center">PTR</th>
                                <th style="text-align:center">ESTADO</th>
                                <th style="text-align:center">' . $titulo . '</th>
                                <th style="text-align:center">VALOR MAT</th>
                                <th style="text-align:center">PROYECTO</th>
                                <th style="text-align:center">SUBPROYECTO</th>
                                <th style="text-align:center">PEP</th>
                                <th style="text-align:center">GRAFO</th>
                            </tr>
                        </thead>

                        <tbody>';

            foreach ($listaPTRS as $row) {

                $html .= ' <tr>
                            <td>' . $row->itemPlan . '</td>
                            <td>' . $row->ptr . '</td>
                            <td>' . $row->est_innova . '</td>
                            <td>' . ( $titulo == 'COMPROMETIDO' ? $row->comprometido : $row->real_planresord) . '</td>
                            <td>' . $row->valor_mat . '</td>
                            <td>' . $row->nombreProyecto . '</td>
                            <td>' . $row->subProyectoDesc . '</td>
                            <td>' . $row->pep2 . '</td>
                            <td>' . $row->grafo . '</td>
                           </tr>';
            }

            $html .= '</tbody>
          </table>';
        }

        return utf8_decode($html);
    }
   
   
    }