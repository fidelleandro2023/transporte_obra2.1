<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_movilesDetalle extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mf_movilesPep/M_movilesPepDetalle');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index() {

        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
//            $data['tabla'] = $this->consultaTablaPep();

            $proyecto = (isset($_GET['pro']) ? $_GET['pro'] : '');
            if ($proyecto != '') {
                $dataArrayTabla = $this->M_movilesPepDetalle->FiltrarPepReporte($proyecto, '', '');
                if (!$dataArrayTabla->result()) {
                    $flg_tipo = (isset($_GET['flg_tipo']) ? $_GET['flg_tipo'] : '');
                    $dataArrayTabla = $this->M_movilesPepDetalle->getDetallePepBianual($flg_tipo);
                }
                $data["tabla"] = $this->tablaToroPepF($dataArrayTabla);
                //$data["showFil"]= 0;
            } else {
                $data["tabla"] = $this->tablaToroPep();
                // $data["showFil"]= 1;
            }

            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTION_MANTENIMIENTO, ID_PERMISO_HIJO_REGISTRO_OPEX, ID_MODULO_GESTION_MANTENIMIENTO, ID_PERMISO_PADRE_MODULO_OPEX);
            $data['opciones'] = $result['html'];
            $this->load->view('vf_movilesPep/v_movilesPep_proyecto', $data);
        } else {
            redirect('login', 'refresh');
        }
    }

    public function tablaToroPep() {
        $pep = $this->M_movilesPepDetalle->getMovilesPep();

        $html = '
            <table id="data-table" class="table table-bordered" style="width:100%">
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
            <tbody id="tb_body">';

        $i = 0;
        $total_monto_tmp = 0;
        $tota_presupuesto = 0;
        $tota_real = 0;
        $tota_comprometido = 0;
        $total_planresord = 0;
        $total_disponible = 0;
        foreach ($pep->result() as $row) {
            $i++;
            $presupuesto = str_replace('"', '', $row->presupuesto);
            $real = str_replace('"', '', $row->real);
            $comprometido = str_replace('"', '', $row->comprometido);
            $panresord = str_replace('"', '', $row->planresord);
            $disponible = str_replace('"', '', $row->disponible);
            $porcentaje = (($presupuesto > 0) ? round((($row->monto_temporal * 100) / str_replace(',', '', $presupuesto))) : 0);
            $html .= '<tr ' . (($row->dif_meses >= 3 && $porcentaje >= 70) ? 'style="color:red"' : '') . '>    
                    <input type="hidden" id="id_pep_' . $i . '" value="' . $row->pep1 . '">
                    <td><a data-toggle="popover" data-trigger="hover" title="' . $row->pep1 . '" data-content="' . '$row->comentario '. '">' . $row->pep1 . '</a></td>
                    <td>' . str_replace('"', '', $row->detalle) . '</td>
                    <td>' . str_replace('"', '', $row->tnombre) . '</td>
                    <td>' . str_replace('"', '', $row->subProyectoDesc) . '</td>    
                    <td>' . $presupuesto . '</td>
                    <td>' . $real . '</td>
                    <td><a  data-pep1="PEP ' . $row->pep1 . '" onclick="getDetallePTRS(this,1)">' . $comprometido . '</a></td>
                    <td><a  data-pep1="PEP ' . $row->pep1 . '" onclick="getDetallePTRS(this,2)">' . $panresord . '</a></td>
                    <td>' . $disponible . '</td>
                    <td>' . number_format($row->monto_temporal, 2, '.', ',') . '</td>
                    <td>' . $porcentaje . '%</td>    
                    <td>' . $row->id_toro . '</td>      
                    <td>' . $row->fecha_registro . '</td>
                </tr>';
            $tota_presupuesto = ($tota_presupuesto + str_replace(',', '', $presupuesto));
            $tota_real = ($tota_real + str_replace(',', '', $real));
            $tota_comprometido = ($tota_comprometido + str_replace(',', '', $comprometido));
            $total_planresord = ($total_planresord + str_replace(',', '', $panresord));
            $total_disponible = ($total_disponible + str_replace(',', '', $disponible));
            $total_monto_tmp = ($total_monto_tmp + $row->monto_temporal);
        }

        $html .= '<tr>
                    <td>TOTAL</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>' . number_format($tota_presupuesto, 2, '.', ',') . '</td>
                    <td>' . number_format($tota_real, 2, '.', ',') . '</td>
                    <td>' . number_format($tota_comprometido, 2, '.', ',') . '</td>
                    <td>' . number_format($total_planresord, 2, '.', ',') . '</td>
                    <td>' . number_format($total_disponible, 2, '.', ',') . '</td>
                    <td>' . number_format($total_monto_tmp, 2, '.', ',') . '</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';
        $html .= "</tbody></table>";
        return utf8_decode($html);
    }

    public function tablaToroPepF($consulta) {

        $pep = $consulta;
        $html = '
            <table  id="data-table" class="table table-bordered" style="width:100%">
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
        $i = 0;
        $total_monto_tmp = 0;
        $tota_presupuesto = 0;
        $tota_real = 0;
        $tota_comprometido = 0;
        $total_planresord = 0;
        $total_disponible = 0;
        foreach ($pep->result() as $row) {
            $i++;
            $presupuesto = str_replace('"', '', $row->presupuesto);
            $real = str_replace('"', '', $row->real);
            $comprometido = str_replace('"', '', $row->comprometido);
            $panresord = str_replace('"', '', $row->planresord);
            $disponible = str_replace('"', '', $row->disponible);
            $porcentaje = (($presupuesto > 0) ? round((($row->monto_temporal * 100) / str_replace(',', '', $presupuesto))) : 0);
            $html .= '<tr ' . (($row->dif_meses >= 3 && $porcentaje >= 70) ? 'style="color:red"' : '') . '>    
                    <input type="hidden" id="id_pep_' . $i . '" value="' . $row->pep1 . '">
                    <td><a data-toggle="popover" data-trigger="hover" title="' . $row->pep1 . '" data-content="' . '$row->comentario' . '">' . $row->pep1 . '</a></td>
                    <td>' . str_replace('"', '', $row->detalle) . '</td>
                    <td>' . str_replace('"', '', $row->tnombre) . '</td>
                    <td>' . str_replace('"', '', $row->subProyectoDesc) . '</td>    
                    <td>' . $presupuesto . '</td>
                    <td>' . $real . '</td>
                    <td><a  data-pep1="PEP ' . $row->pep1 . '" onclick="getDetallePTRS(this,1)">' . $comprometido . '</a></td>
                    <td><a  data-pep1="PEP ' . $row->pep1 . '" onclick="getDetallePTRS(this,2)">' . $panresord . '</a></td>
                    <td>' . $disponible . '</td>
                    <td>' . number_format($row->monto_temporal, 2, '.', ',') . '</td>
                    <td>' . $porcentaje . '%</td>    
                    <td>' . 'TORO'. '</td>      
                    <td>' . $row->fecha_registro . '</td>
                </tr>';

            $tota_presupuesto = ($tota_presupuesto + str_replace(',', '', $presupuesto));
            $tota_real = ($tota_real + str_replace(',', '', $real));
            $tota_comprometido = ($tota_comprometido + str_replace(',', '', $comprometido));
            $total_planresord = ($total_planresord + (is_numeric(str_replace(',', '', $panresord)) ? str_replace(',', '', $panresord) : 0));
            $total_disponible = ($total_disponible + (is_numeric(str_replace(',', '', $disponible)) ? str_replace(',', '', $disponible) : 0));
            $total_monto_tmp = ($total_monto_tmp + $row->monto_temporal);
        }

        $html .= '<tr>
                    <td>TOTAL</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>' . number_format($tota_presupuesto, 2, '.', ',') . '</td>
                    <td>' . number_format($tota_real, 2, '.', ',') . '</td>
                    <td>' . number_format($tota_comprometido, 2, '.', ',') . '</td>
                    <td>' . number_format($total_planresord, 2, '.', ',') . '</td>
                    <td>' . number_format($total_disponible, 2, '.', ',') . '</td>
                    <td>' . number_format($total_monto_tmp, 2, '.', ',') . '</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';
        $html .= "</tbody></table>";
        return utf8_decode($html);
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

}
