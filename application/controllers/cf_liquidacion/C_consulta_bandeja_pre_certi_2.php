<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_consulta_bandeja_pre_certi_2 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_liquidacion/m_bandeja_pre_certifica');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        $ideecc  = $this->session->userdata("eeccSession");
        if ($logedUser != null) {
            $data['listaEECC'] = $this->m_utils->getAllEECC();
            $data['listaZonal'] = $this->m_utils->getAllZonal();
            $data['listaSubProy'] = $this->m_utils->getAllSubProyecto();
            $data['listafase'] = $this->m_utils->getAllFase();
            $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);
            $data['itemplanList'] = $this->makeHTMLOptionsChoice($this->m_bandeja_pre_certifica->getItemplanExpediente($ideecc));
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');

            #$result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_CERTIFICACION_MO, ID_PERMISO_HIJO_BANDEJA_CONSULTA_PRE_CERTIFICACION_II);
            $result = $this->lib_utils->getHTMLPermisos($permisos, 250, ID_PERMISO_HIJO_BANDEJA_CONSULTA_PRE_CERTIFICACION_II, ID_MODULO_ADMINISTRATIVO);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_liquidacion/v_consulta_bandeja_pre_certi_2', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function makeHTMLOptionsChoice($itemplanList)
    {
        $html = '';
        foreach ($itemplanList->result() as $row) {
            $html .= '<option value="' . $row->itemplan . '">' . $row->itemplan . '</option>';
        }
        return $html;
    }

    public function makeHTLMTablaBandejaAprobMo($listaPTR)
    {
        $html = '<table id="data-table" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>';
        //            <th style="width: 10px;"></th>
        //              <th style="width: 10px;"></th>
        $html .= '      <th>Item Plan</th>
                            <th>Indicador</th>
                            <th>Sub Proy</th>
                            <th>Zonal</th>
                            <th>EECC</th>
                            <th>Fase</th>
                            <th>Fec. Prevista</th>
                            <th>Estado</th>
                            <th>Situacion</th>

                        </tr>
                    </thead>

                    <tbody>';
        if ($listaPTR != null) {
            foreach ($listaPTR->result() as $row) {

                $html .= ' <tr>';
                //                <th>'.(($row->hasExpe=='1' && $row->hasDise=='0') ? '<a data-itemplan ="'.$row->itemPlan.'"  onclick="aprobarCertificado(this)"><img alt="Editar" height="20px" width="20px" src="public/img/iconos/circle-check-128.png"></a>' : '').'</th>
                //               <td>'.(($row->hasExpe=='1') ? '<img alt="Editar" height="20px" width="20px" src="public/img/iconos/expediente.png">' : '').'</td>
                $html .= '    <th style="color : blue"><a data-itm ="' . $row->itemPlan . '" onclick="getPTRSByItemplan(this)">' . $row->itemPlan . '</a></th>
                            <th>' . $row->indicador . '</th>
                            <th>' . $row->subProyectoDesc . '</th>
							<th>' . $row->zonalDesc . '</th>
							<th>' . $row->empresaColabDesc . '</th>
							<th>' . $row->fase_desc . '</th>
                            <th>' . $row->fechaPrevEjec . '</th>
                            <th>' . $row->estadoPlanDesc . '</th>
                            <th>' . $row->situacion . '</th>
			</tr>';
            }
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

    public function filtrarTabla()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $SubProy = $this->input->post('subProy');
            $eecc = $this->input->post('eecc');
            $zonal = $this->input->post('zonal');
            $itemPlan = $this->input->post('itemplanFil');
            $mesEjec = $this->input->post('mes');
            $expediente = $this->input->post('expediente');
            $idFase = $this->input->post('idFase');
            if ($SubProy == '' && $eecc == '' && $zonal == '' && $itemPlan == '' && $mesEjec == '' && $expediente == '' && $idFase == '') {
                $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo(null);

            } else {
                $data['tablaAsigGrafo'] = $this->makeHTLMTablaBandejaAprobMo($this->m_bandeja_pre_certifica->getBandejaPreMo($SubProy, $eecc, $zonal, $itemPlan, $mesEjec, $expediente,$idFase));

            }
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getPtrsByItemPlan()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        $data['cabecera'] = null;
        try {
            $itemplan = $this->input->post('itemplan');
            $listaEstaciones = $this->m_bandeja_pre_certifica->getEstacionPorcentajeByItemPlanWithDiseno($itemplan);
            $data['estacionesTab'] = $this->getHTMLTabsEstaciones($itemplan, $listaEstaciones);
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    public function getHTMLTabsEstaciones($itemplan, $listaEstaciones)
    {
        $validarFichatecnica = false;
        $infoITemplan = $this->m_utils->getInfoItemplan($itemplan);
        $fechaPreLiquidacion = $infoITemplan['fechaPreLiquidacion'];
        $fase = $infoITemplan['idFase'];
        if ($fechaPreLiquidacion != null) {
            if (date("Y-m-d", strtotime($fechaPreLiquidacion)) >= date("Y-m-d", strtotime('2018-07-07')) && $fase != ID_FASE_2017) {
                $validarFichatecnica = true;
            }
        } else {
            $validarFichatecnica = true;
        }
        $html = '<div class="tab-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">';
        $activa = 'active';
        foreach ($listaEstaciones->result() as $row) {
            $color = $this->getColorTabByPorcentaje($row->porcentaje);
            $html .= '<li class="nav-item">
                        <a  style="' . $color['color_font'] . '" class="nav-link ' . $activa . '" data-toggle="tab" href="#tab' . $row->idEstacion . '" role="tab">' . utf8_decode($row->estacionDesc) . '</a>
                      </li>';
            $activa = '';
        }
        $html .= '</ul>

                    <div class="tab-content">';
        $activa = 'active';
        foreach ($listaEstaciones->result() as $row) {
            $showBtnExpe = false;
            $btnNeedDJ = '';
            $addExpe = $this->m_bandeja_pre_certifica->haveActivo($itemplan, $row->idEstacion);
            if ($addExpe == '0' && $row->porcentaje == '100') { //validacion para evitar agregar expediente
                if (($row->idEstacion == ID_ESTACION_FO || $row->idEstacion == ID_ESTACION_COAXIAL) && $validarFichatecnica) {
                    $has_ficha = $this->m_bandeja_pre_certifica->haveFichaValidadaPorTDP($itemplan, $row->idEstacion);
                    if ($has_ficha >= 1) {
                        $showBtnExpe = true;
                    } else {
                        $btnNeedDJ = '<label style="color:red; font-weight: bold;">No cuenta con declaracion jurada validada.</label>';
                    }
                } else {
                    $showBtnExpe = true;
                }
            }
            $html .= '<div class="tab-pane ' . $activa . ' fade show" id="tab' . $row->idEstacion . '" role="tabpanel">
                                  <div class="row">';
            $html .= $this->makeHTLMTablaPtrByItemplan($this->m_bandeja_pre_certifica->getPtrByItemplan($itemplan, $row->idEstacion), $row->porcentaje, $row->certificado);
            $html .= '<br><br><br>
                    <div style="text-align: center;width: 100%;padding-top: 20px">
                        <label style="font-weight: bold;">EXPEDIENTE</label><br>
                         ' . $btnNeedDJ . '
                    </div>
               <div id="contBtnCerti' . $row->idEstacion . '" style="text-align: right; width: 95%;' . (($showBtnExpe) ? '' : 'display: none;') . '" class="tab-container">
            
               </div>
            <div style="width: 100%;" id="contTablaCerti' . $row->idEstacion . '">
            ' . $this->makeHTLMTablaCertificacionByITemplan($this->m_bandeja_pre_certifica->getCertificadoByItemPlan($itemplan, $row->idEstacion), $row->idEstacion) . '
            </div>';
            $html .= '</div>
                                </div>';
            $activa = '';
        }
        $html .= ' </div>
                    </div>';
        return $html;
    }

    public function getHTMLCollapseEstaciones($listaEstaciones)
    {
        $html = '<div class="card-block">
                    <div class="accordion" role="tablist">';

        foreach ($listaEstaciones->result() as $row) {
            $color = $this->getColorTabByPorcentaje($row->porcentaje);
            $html .= '<div class="card" style="padding-top: 5px;">
                    <div class="card-header" role="tab" style="' . $color['header'] . '">
                        <a style="text-align: center;' . $color['font'] . '" class="card-title" data-toggle="collapse" data-parent="#accordionExample" href="#collapse' . $row->idEstacion . '">' . $row->estacionDesc . ' ' . $row->porcentaje . '%</a>
                    </div>
                    <div style="' . $color['body'] . '" id="collapse' . $row->idEstacion . '" class="collapse">
                        <div class="card-block">
                            Contenido
                        </div>
                    </div>
                </div>';
        }
        $html .= '  </div>
                </div>';
        return $html;
    }

    public function getColorTabByPorcentaje($val)
    {
        $color = array();
        if ($val >= 0 && $val <= 49) {
            $color['header'] = 'background-color:red;';
            $color['body'] = 'background-color:#ff000040;';
            $color['font'] = 'color:white';
            $color['color_font'] = 'color:red';
        } else if ($val && $val <= 74) {
            $color['header'] = 'background-color:yellow';
            $color['body'] = 'background-color:#efef2373;';
            $color['font'] = 'color:black';
            $color['color_font'] = 'color:yellow';
        } else if ($val >= 75 && $val <= 99) {
            $color['header'] = 'background-color:orange';
            $color['body'] = 'background-color:#ffa5007a;';
            $color['font'] = 'color:black';
            $color['color_font'] = 'color:orange';
        } else if ($val == 100) {
            $color['header'] = 'background-color:green;';
            $color['body'] = 'background-color:#0080004f;';
            $color['font'] = 'color:white';
            $color['color_font'] = 'color:green';
        }
        return $color;
    }
    public function makeHTLMTablaPtrByItemplan($listaPTR, $porcentaje, $certificado)
    {

        $html = '<table id="data-table2" class="table table-bordered" style="font-size: smaller;">
                    <thead class="thead-default">
                        <tr>
                            <th>PTR</th>
                            <th>AREA</th>
                            <th>ESTADO</th>
                            <th>EECC</th>
                            <th>ZONAL</th>
                            <th>V. MO</th>
                            <th>V. MAT</th>
                            <th>VALE RESERVA</th>
                        </tr>
                    </thead>

                    <tbody>';

        foreach ($listaPTR->result() as $row) {

            $estadoFinal = '';
            if ($row->estado_wu == $row->estado_wud) {
                $estadoFinal = $row->estado_wud;
            } else {
                $estadoFinal = $row->estado_wu;
            }
            $html .= ' <tr ' . ((substr($estadoFinal, 0, 3) == '003' || substr($estadoFinal, 0, 3) == '001') ? '' : ' style = "background-color: gainsboro;"') . '>
							<td>' . $row->ptr . '</td>
							<td>' . $row->areaDesc . '</td>
							<td>' . $row->estado_wu . '</td>
							<th>' . $row->eecc . '</th>
                            <th>' . $row->jefatura . '</th>
                            <th>' . $row->valor_m_o . '</th>
                            <th>' . $row->valor_material . '</th>
                            <th>' . (($row->vr_wud != null) ? $row->vr_wud : $row->vr_wu) . '</th>
						</tr>';
        }
        $html .= '</tbody>
                </table>';
        return utf8_decode($html);
    }

    public function makeHTLMTablaCertificacionByITemplan($listaPTR, $idEstacion)
    {

        $html = '<table id="tabla-certi' . $idEstacion . '" class="table table-bordered" style="font-size: smaller;">
                    <thead class="thead-default">
                        <tr>
                            <th>FECHA</th>
                            <th>USUARIO</th>
                            <th>COMENTARIO</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>

                    <tbody>';

        foreach ($listaPTR->result() as $row) {

            $html .= '<tr ' . (($row->estado != 'ACTIVO') ? '' : ' style = "background-color: #33a2264a;"') . '>
						<td>' . $row->fecha . '</td>
                        <td>' . $row->usuario . '</td>
						<td>' . $row->comentario . '</td>
						<td>' . $row->estado_final . '</td>
					  </tr>';
        }
        $html .= '</tbody>
                </table>';

        return utf8_decode($html);
    }

}
