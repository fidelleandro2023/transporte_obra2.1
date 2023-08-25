<?php
class M_ManLog extends CI_Model{
    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct(){
        parent::__construct();

    }
    //function   getPtrConsulta($itemplan,$nombreproyecto,$nodo,$zonal,$proy,$subPry,$estado,$filtroPrevEjec,$tipoPlanta){
    function   getItemplanLog($itemplan,$ptr,$reg_fechaini,$reg_fechafin){

        if($reg_fechaini == ''){
            $reg_fechaini = '2000-01-01';
        }
        if($reg_fechafin == ''){
            $reg_fechafin = date("Y-m-d");
        }

        $Query = "  SELECT 
                            lg.itemplan,lg.id_log,lg.ptr,lg.tabla,lg.actividad,lg.fecha_registro,lg.fecha_modificacion,lg.id_usuario,
                            us.nombre, us.id_perfil,pf.desc_perfil,us.id_usuario_tipo,us.id_eecc,ut.nombre_usuario_tipo   
                        FROM
                            log_planobra lg
                        INNER JOIN
                            usuario us ON lg.id_usuario = us.id_usuario
                        left join
                            perfil pf on us.id_perfil = pf.id_perfil
                        left join 
                            usuario_tipo ut ON us.id_usuario_tipo = ut.id_usuario_tipo
                        where 
                          lg.itemplan like concat('%','".$itemplan."','%') 
                          and lg.ptr like concat('%','".$ptr."','%')    
                          and (date(lg.fecha_registro) between '".$reg_fechaini."' and '".$reg_fechafin."' )
                       " ;

//where lg.itemplan like concat('%','".$itemplan." ')

//        if($itemplan!=''){
//            $Query .= " where lg.itemplan ='".$itemplan."' ";
//        }

        $result = $this->db->query($Query,array());
        return $result;
    }

    function insertExpediente($comentario, $usuario){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            $this->db->trans_begin();
            //$this->db->query("INSERT INTO expediente (id_expediente, comentario, usuario, fecha_registro) VALUES (NULL, '".$comentario."', '".$usuario."', NOW())");
            $this->db->query("INSERT INTO expediente (id_expediente, comentario, usuario, fecha_registro) VALUES (NULL, '".$comentario."', '".$usuario."', NOW() )");
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $data ['error']= EXIT_SUCCESS;
            }else{
                $this->db->trans_rollback();
            }


        }catch(Exception $e){
            $data['msj']   = 'Error en la transaccion!';
            $this->db->trans_rollback();
        }
        return $data;
    }

    function insertPTR($ptr, $item, $fecsol,$subproyecto,$zonal,$eecc,$area){
        $data['error'] = EXIT_ERROR;
        $data['msj']   = null;
        try{
            //(SELECT SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE('14/12/2017 15:38', '%d/%m/%Y %H:%i:%s'), NOW())))
            $this->db->trans_begin();
            $this->db->query("INSERT INTO expediente_ptr (id_expediente, ptr, itemplan, tpo_espera, subproyecto, zonal, eecc, area) VALUES ((SELECT id_expediente FROM expediente ORDER BY id_expediente DESC LIMIT 1), '".$ptr."', '".$item."',  (SELECT SEC_TO_TIME(TIMESTAMPDIFF(SECOND, STR_TO_DATE('".$fecsol."', '%d/%m/%Y %H:%i:%s'), NOW()))), '".$subproyecto."', '".$zonal."','".$eecc."','".$area."' )");
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $data ['error']= EXIT_SUCCESS;
            }else{
                $this->db->trans_rollback();
            }


        }catch(Exception $e){
            $data['msj']   = 'Error en la transaccion!';
            $this->db->trans_rollback();
        }
        return $data;
    }

    function getAllZonalGroup($zonasRest){
        $Query = "SELECT SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zonalDesc FROM zonal GROUP BY SUBSTRING_INDEX( zonalDesc , ' ', 1 ) ORDER BY zonalDesc" ;
        $result = $this->db->query($Query,array());
        return $result;
    }
    function getAllZonalIndex($zonasRest){
        $Query = "SELECT idZonal, SUBSTRING_INDEX( zonalDesc , ' ', 1 ) as zona 
				FROM zonal " ;
        if($zonasRest!=''&&$zonasRest!=','){
            $Query .= " WHERE zonal.idzonal IN (".$zonasRest.")";
        }

        $Query .= "  GROUP BY (zona)";


        $result = $this->db->query($Query,array());
        return $result;
    }


}