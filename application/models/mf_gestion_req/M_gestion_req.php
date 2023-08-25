<?php
class M_gestion_req extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
    
    
    function insertNuevoReq($tiporeq,$accion, $tablarela,$observaciones,$solicitante,$uploadfile){

        $data['error']    = EXIT_ERROR;
        $data['msj']      = null;
        $data['cabecera'] = null;
        try{

            $data = array(
             'tiporeq' => $tiporeq,
             'accion' => $accion,
             'tablatiporeq' => $tablarela,
             'observaciones' => $observaciones,
             'solicitante' => $solicitante,
             'fechacreacion' => date("Y-m-d H:i:s"),
             'estado' => 1,
             'rutaanexo' => $uploadfile
             );
             
            
             $this->db->insert('solicitud_req',$data);
            
        }catch(Exception $e){
            $data['msj'] = $e->getMessage();
        }
        //echo json_encode(array_map('utf8_encode', $data));
        
    }

    function getListaTipoReq(){
        $Query = " SELECT *
                    FROM tipo_req" ;
        $result = $this->db->query($Query,array());   
        return $result;
    }


    function getTablaRefAccion($tiporeq){
         $Query = " SELECT tablaaccion 
                    FROM tipo_req
                    where tiporeq=".$tiporeq ;
        $result = $this->db->query($Query,array());
        $dato =$result->row()->tablaaccion;
        return $dato ;
    }


    function getTablaAccion($tiporeq){
         $Query = " SELECT idtablaaccion, descriptablaaccion, tablaaccion 
                    FROM tipo_req
                    where tiporeq=".$tiporeq ;
        $result = $this->db->query($Query,array());   
        return $result;
    }

    function getDatosCargaAccion($id,$descripcion,$tabla){

         $Query = " SELECT ".$id." as id,".$descripcion." as descripcion 
                    FROM ".$tabla ;

        $result = $this->db->query($Query,array());   
        return $result;
    }

    /*******************************************bandeja pendiente, ejecucion y atencion*******************************/

    function getListaReqPSegunEstado($estado){
         $Query = " SELECT solreq.idsolicitud, 
                           tipreq.tiporeqdescrip,
                           (case when solreq.tiporeq=1 then (SELECT subProyectoDesc FROM subproyecto WHERE idSubProyecto=solreq.accion)
                                 when solreq.tiporeq=2 then (SELECT estadoPlanDesc FROM estadoplan WHERE idEstadoPlan=solreq.accion)
                                 else (SELECT descripotroReq FROM listaotros_req WHERE idlotroreq=solreq.accion) end) as accion,
                            user.usuario as solicitante,
                            estreq.descripcion,
                            solreq.observaciones,
                            solreq.fechacreacion,
                            solreq.fecharecepcion,
                            solreq.usuarioatencion as usuario_en_atencion,
                            solreq.fechaatencion,
                            solreq.rutaanexo

                       FROM solicitud_req solreq,
                            tipo_req tipreq ,
                            usuario user, 
                            estadogestion_req estreq
                      WHERE solreq.tiporeq=tipreq.tiporeq 
                        and user.id_usuario=solreq.solicitante
                        and estreq.idestado=solreq.estado
                        and solreq.estado=".$estado;
        $result = $this->db->query($Query,array());   
        return $result;

    }

    function recibirReq($idreq,$operador){
            try{
            
            $this->db->trans_begin();
                
            $dataUpdate = array(
               
                "usuarioatencion" => $operador,
                "fecharecepcion" => date("Y-m-d H:i:s"),
                'estado' => 2

            );


            $this->db->where('idsolicitud', $idreq);
            $this->db->update('solicitud_req', $dataUpdate);

            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el plan de obra');
            }else{
                $this->db->trans_commit();

                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se modifico correctamente!';
            }
            
             
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;

        
    }


    function atenderReq($idreq){
            try{
            
            $this->db->trans_begin();
                
            $dataUpdate = array(
               
                "fechaatencion" => date("Y-m-d H:i:s"),
                'estado' => 3

            );


            $this->db->where('idsolicitud', $idreq);
            $this->db->update('solicitud_req', $dataUpdate);

            if($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                throw new Exception('Error al insertar el plan de obra');
            }else{
                $this->db->trans_commit();

                $data['error']    = EXIT_SUCCESS;
                $data['msj']      = 'Se modifico correctamente!';
            }
            
             
        }catch(Exception $e){
            $data['msj']   = $e->getMessage();
            $this->db->trans_rollback();
        }
        return $data;

        
    }
    

	/***********************************************************************************************************/
}