<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_bandeja_presupuesto_oc_capex_eecc extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=UTF-8');
        $this->load->model('mf_certificacion/m_bandeja_presupuesto_oc_capex_eecc');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->library('excel');
        $this->load->helper('url');
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] =  $this->session->userdata('usernameSession');
            $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');
            $permisos =  $this->session->userdata('permisosArbolTransporte');
            $result = $this->lib_utils->getHTMLPermisos($permisos, 54, 336, ID_MODULO_PAQUETIZADO);
            $data['opciones'] = $result['html'];
            $data['title'] = 'Bandeja Regularización Presupuesto OC CAPEX';

            $data['tbReporte'] = $this->makeHTLMTablaConsulta('');


            // if($result['hasPermiso'] == true){
            $this->load->view('vf_certificacion/v_bandeja_presupuesto_oc_capex_eecc', $data);
            // }else{
            // 	redirect('login','refresh');
            // }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function makeHTLMTablaConsulta($listaReporte)
    {
        $html = '
                <table id="data-table" class="table table-bordered table-sm">
                    <thead class="thead-default">
                        <tr>
							<th style="text-align: center, vertical-align: middle;" colspan="1">ITEMPLAN</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">PROYECTO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">SUBPROYECTO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">EE.CC.</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">SOLICITUD</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">TRANSACCION</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">ESTADO SOLICITUD</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">MOTIVO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">COMENTARIO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">S/. COSTO TOTAL</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">S/. ULTIMO COSTO</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">S/. COSTO REQUE.</th>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">S/. MONTO DISPO. SAP</th>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">PEP 1</th>
							<th style="text-align: center, vertical-align: middle;" colspan="1">PEP 2</th>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">CESTA</th>
                            <th style="text-align: center, vertical-align: middle;" colspan="1">ORDEN DE COMPRA</th>
                        </tr>
                    </thead>

                    <tbody>';

        $count = 1;
        $htmlBody = '';

        $ctnLista = 0;

        if ($listaReporte != '') {
            $ctnLista = count($listaReporte);
            foreach ($listaReporte as $row) {
                $btnAccionLiberar = null;
				$btnCancelaSol    = null;
				

                $htmlBody .= '
                        <tr>
                            <td style="text-align: center;">' . $row->itemplan . '</td>
							<td style="text-align: center;">' . $row->proyectoDesc . '</td>
                            <td style="text-align: center;">' . $row->subProyectoDesc . '</td>
                            <td style="text-align: center;">' . $row->empresaColabDesc . '</td>
                            <td style="text-align: center;">' . $row->codigo_solicitud . '</td>
                            <td style="text-align: center;">' . $row->tipo_pc . '</td>
                            <td style="text-align: center;">' . $row->estado_solicitud . '</td>
							<td style="text-align: center;">' . $row->motivo . '</td>
							<td style="text-align: center;">' . $row->comentarioQuiebre . '</td>
                            <td style="text-align: center;">' . number_format($row->costo, 2) . '</td>
							<td style="text-align: center;">' . number_format($row->ultimo_costo_oc, 2) . '</td>
							<td style="text-align: center;">' . number_format(($row->costo - $row->ultimo_costo_oc), 2) . '</td>							
                            <td style="text-align: center; background-color: #beff00;">' . (isset($row->monto_temporal) ? number_format($row->monto_temporal, 2)  : $row->monto_temporal) . '</td>
                            <td style="text-align: center;">' . $row->pep1 . '</td>
                            <td style="text-align: center;">' . $row->pep2 . '</td>
                            <td style="text-align: center;">' . $row->cesta . '</td>
                            <td style="text-align: center;">' . $row->orden_compra . '</td>
                        </tr>
                        ';
                $count++;
            }

            $html .= $htmlBody . '</tbody>
                </table>';
        } else {
            $html .= '</tbody>
                </table>';
        }

        return $html;
    }

    public function filtrarTabla()
    {
        $data['error'] = EXIT_ERROR;
        $data['msj'] = null;
        try {

            $data['tbReporte'] = $this->makeHTLMTablaConsulta($this->m_bandeja_presupuesto_oc_capex_eecc->getBandejaSolPdtPresupuesto());
            $data['error'] = EXIT_SUCCESS;
        } catch (Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode($data);
    }

    public function fechaActual()
    {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone', 'America/Lima');
        setlocale(LC_TIME, "es_ES", "esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }

}
