<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 */
class C_bandeja_req extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->output->set_header('Content-Type: text/html; charset=ISO-8859-1');
        $this->load->model('mf_gestion_req/m_gestion_req');
        $this->load->model('mf_utils/m_utils');
        $this->load->library('lib_utils');
        $this->load->helper('url');
    }
    
	public function index()
	{  	   
	    $logedUser = $this->session->userdata('usernameSession');
	    if($logedUser != null){

                $data['listarpendientes'] = $this->makeHTLMTablaBandejaGestionReq($this->m_gestion_req->getListaReqPSegunEstado(1),1);
                $data['listarenatencion'] = $this->makeHTLMTablaBandejaGestionReq($this->m_gestion_req->getListaReqPSegunEstado(2),2);
                $data['listaratendidos'] = $this->makeHTLMTablaBandejaGestionReq($this->m_gestion_req->getListaReqPSegunEstado(3),3);

               
               $data['nombreUsuario'] =  $this->session->userdata('usernameSession');	
               $data['perfilUsuario'] =  $this->session->userdata('descPerfilSession');	               
        	   $permisos =  $this->session->userdata('permisosArbol');
        	   $result = $this->lib_utils->getHTMLPermisos($permisos, ID_PERMISO_PADRE_GESTIOREQ, ID_PERMISO_HIJO_BANDEJA_GESTIOREQ);
        	   $data['opciones'] = $result['html'];
        	   if($result['hasPermiso'] == true){
        	       $this->load->view('vf_gestion_req/v_bandeja_atencion_req',$data);
        	   }else{
        	       redirect('login','refresh');
	           }
	   }else{
	       redirect('login','refresh');
	   }
    }
   

    public function makeHTLMTablaBandejaGestionReq($listDatos,$indicador){
        $listaJson = json_encode($listDatos->result());
       

        $comiListaJson = "'".$listaJson."'";
        
        $html = '
            <script>

                function bajar(){
                   
                    var listaJS = '.$comiListaJson.';
                       
    
                    $.ajax({
                        async:true,
                        type:"POST",
                        dataType:"html",
                        contentType:"application/x-www-form-urlencoded",//application/x-www-form-urlencoded
                        url:"excelObras",
                        data:{listaJS  : listaJS                            
                                        },
                       
                        success:function(data){
                         
                            var opResult = JSON.parse(data);
                                  var $a=$("<a>");
                                  $a.attr("href",opResult.data);
                                
                                  $("body").append($a);
                                  $a.attr("download","SeguimientoPO.xls");
                                  $a[0].click();
                                  $a.remove();
                              }
                          });
    
                }
                </script>
                  <table id='."data-table".$indicador.' class="table table-bordered" style="font-size: 10px;">
                    <thead class="thead-default">
                      
                       <tr role="row">                           
                            <th colspan="1"></th>
                            <th colspan="1">TIPO REQUERIMIENTO</th>
                            <th colspan="1">NUEVO VALOR</th>                            
                            
                            <th colspan="1">SOLICITANTE</th>                          
                            <th colspan="1">ESTADO</th>
                            <th colspan="1">OBSERVACIONES</th>
                        
                            <th colspan="1">FECHA CREACION REQ</th>
                            <th colspan="1">FECHA RECEPCION REQ</th> 
                            <th colspan="1">RESPONSABLE ATENCION</th>
                            <th colspan="1">FECHA ATENCION REQ</th>     
                                                     
                            <th colspan="1">ANEXO</th>      
                                        
                        </tr>
                    </thead>                    
                    <tbody>';
                                                                                                                                       
                foreach($listDatos->result() as $row){ 

                    if($indicador==1){
                        $boton="<button class='btn btn-warning' data-toggle='modal'  data-id='".$row->idsolicitud."' data-target='#modal-large' onclick='reqporAtender(this)'>Por Atender</button>";      
                    }elseif($indicador==2){
                        $boton="<button class='btn btn-success' data-toggle='modal'  data-id='".$row->idsolicitud."' data-target='#modal-large' onclick='reqAtencion(this)'>Dar por Atendido</button>";      
                    }else{
                        $boton="";
                    }

                    if($row->rutaanexo!=null){
                        $anexo="<a href='uploads/gestion_req/".$row->rutaanexo."' style='font-size:40px;' class='zmdi zmdi-file-text zmdi-hc-fw'></a>Anexo";
                    }else{
                        $anexo="NO SE INGRESO ANEXO";
                    }


                   $html .=' <tr>
                                <td>'.$boton.'</td>
                                <td>'.$row->tiporeqdescrip.'</td>
                                <td>'.$row->accion.'</td>
                                <td>'.$row->solicitante.'</td>
                                <td>'.$row->descripcion.'</td>
                                <td>'.$row->observaciones.'</td>
                                <td>'.$row->fechacreacion.'</td>
                                <td>'.$row->fecharecepcion.'</td>
                                <td>'.$row->usuario_en_atencion.'</td>
                                <td>'.$row->fechaatencion.'</td>
                                <td>'.$anexo.'</td>
                                
                            </tr>';
                 }
             $html .='</tbody>
                </table>';
                    
        return $html;
    }



    
    public function recepcionarSoliReq(){
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
             // Datos personales   
            $idreq = $this->input->post('id_Req');
            $operador=$this->session->userdata('usernameSession');

            $data =$this->m_gestion_req->recibirReq($idreq,$operador);
           
             if($data['error']==EXIT_ERROR){
                throw new Exception('Error al editar permiso por perfil');
            }
             $data['listarpendientes'] = $this->makeHTLMTablaBandejaGestionReq($this->m_gestion_req->getListaReqPSegunEstado(1),1);
             $data['listarenatencion'] = $this->makeHTLMTablaBandejaGestionReq($this->m_gestion_req->getListaReqPSegunEstado(2),2);
             $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }

        echo json_encode(array_map('utf8_encode', $data));
    }


 public function atenderSoliReq(){
            
        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{
             // Datos personales   
            $idreq = $this->input->post('id_Req');
            $data =$this->m_gestion_req->atenderReq($idreq);
           
            if($data['error']==EXIT_ERROR){
                throw new Exception('Error al editar permiso por perfil');
            }
             $data['listarenatencion'] = $this->makeHTLMTablaBandejaGestionReq($this->m_gestion_req->getListaReqPSegunEstado(2),2);
             $data['listaratendidos'] = $this->makeHTLMTablaBandejaGestionReq($this->m_gestion_req->getListaReqPSegunEstado(3),3);
             $data['error']    = EXIT_SUCCESS;
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }

        echo json_encode(array_map('utf8_encode', $data));
    }
    
}