<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class C_carga_po extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_extractor/m_extractor');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
        $this->load->library('excel'); 
    }

    public function index()
    {
        $logedUser = $this->session->userdata('usernameSession');
        if ($logedUser != null) {
            $data['nombreUsuario'] = $this->session->userdata('usernameSession');
            $data['perfilUsuario'] = $this->session->userdata('descPerfilSession');
            $permisos = $this->session->userdata('permisosArbol');
            $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_TRANFERENCIAS, ID_PERMISO_HIJO_TRANFERENCIA_WU);
            $data['opciones'] = $result['html'];
            if ($result['hasPermiso'] == true) {
                $this->load->view('vf_carga_offline/v_carga_po', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }

    }

    function cargaMat() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if($this->session->userdata('idPersonaSession')) {
                $idUsuario = $this->session->userdata('idPersonaSession');
            } else {
                throw new Exception('se cerr&oacute; su sesi&oacute;n, refrescar la p&aacute;gina.');   
            }
            $detalleplan = $this->m_extractor->getDetallePropuestaObraMaterial();
            if(count($detalleplan->result()) > 0) {
    
                            $file = fopen(PATH_FILE_UPLOAD_MAT, "w");
                fputcsv($file, explode('\t',"ITEMPLAN"."\t".
                                            "CODIGO PO"."\t".
                                            "CODIGO MATERIAL"."\t".
                                            "DESCRIPCION MATERIAL"."\t".
                                            "CANTIDAD INGRESADA"."\t".
                                            "CANTIDAD FINAL"."\t".
                                            "AREA"));
                foreach ($detalleplan->result() as $row){                    
                    fputcsv($file, explode('\t', utf8_decode($row->itemplan."\t".
                                                             $row->codigo_po."\t". 
                                                             $row->codigo_material."\t". 
                                                             $row->descrip_material."\t".
                                                             $row->cantidad_ingreso."\t".
                                                             $row->cantidad_final."\t".
                                                             $row->area)));
                }
    
                fclose($file);
            } else {
                throw new Exception('no hay data en la carga');
            }
            $data['error'] = EXIT_SUCCESS;
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function cargaMo() {
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try {
            if($this->session->userdata('idPersonaSession')) {
                $idUsuario = $this->session->userdata('idPersonaSession');
            } else {
                throw new Exception('se cerr&oacute; su sesi&oacute;n, refrescar la p&aacute;gina.');   
            }
            $arrayMO = $this->m_extractor->getExtracMO();
            if(count($arrayMO) > 0) {
                $file = fopen(PATH_FILE_UPLOAD_MO, "w");
                fputcsv($file, explode('\t',"ITEMPLAN"."\t".
                                            "ESTADO ITEMPLAN"."\t".
                                            "PO"."\t".
                                            "ESTADO PO"."\t".
                                            "AREA"."\t".
                                            "CODIGO PARTIDA"."\t".
                                            "PARTIDA"."\t".
                                            "TIPO PRECIO"."\t".
                                            "BAREMO"."\t".
                                            "CANTIDAD"."\t".
                                            "COSTO"."\t".
                                            "TOTAL"));
                foreach ($arrayMO as $row){                    
                    fputcsv($file, explode('\t',  utf8_decode($row->itemplan."\t".
                    $row->estadoPlanDesc."\t".
                                                                $row->codigo_po."\t".
                                                                $row->po_estado."\t".
                                                                $row->area."\t".
                                                                $row->codigo_partida."\t".
                                                                $row->partidaDesc."\t".
                                                                $row->descPrecio."\t".
                                                                $row->baremo."\t".
                                                                $row->cantidad_final."\t".
                                                                $row->costo."\t".
                                                                $row->monto_final
                                                        )
                                            )
                            );
    
                }
    
                fclose($file);
                $arrayData = array('flg_carga'  => 1,
                                    'idUsuario'  => $idUsuario,
                                    'fecha'      => $this->fechaActual(),
                                    'comentario' => 'carga de la po MO/MAT');
                $data = $this->m_extractor->insert_log_carga($arrayData);
            } else {
                throw new Exception('no hay data en la carga');
            }
        } catch(Exception $e) {
            $data['msj'] = $e->getMessage();
        }
        echo json_encode(array_map('utf8_encode', $data));
    }

    function fechaActual() {
        $zonahoraria = date_default_timezone_get();
        ini_set('date.timezone','America/Lima');
        setlocale(LC_TIME, "es_ES","esp");
        $hoy = strftime("%Y-%m-%d %H:%M:%S");
        return $hoy;
    }
}