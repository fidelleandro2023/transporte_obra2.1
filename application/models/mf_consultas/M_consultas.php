<?php
class M_consultas extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
    }

    function actualizarDetalleForm($itemPlan, $origen, $tipo_obra, $jsonDetalle) {
        $this->db->where('itemplan' , $itemPlan);
        $this->db->where('origen'   , $origen);
        $this->db->where('tipo_obra', $tipo_obra);
        $this->db->update('sisego_planobra', $jsonDetalle);

        if($this->db->affected_rows() == 0) {
          return 0;
          } else {
            return 1;
        }
    }

    function getDataMaterial($itemplan, $idFichaTecnica) {
      $idUsuario = $this->session->userdata('idPersonaSession');
      $idEcc     = $this->session->userdata('eeccSession');
      $sql="SELECT ft.id_ficha_tecnica,
                   ft.itemplan, 
                   ft.jefe_c_nombre, 
                   ft.jefe_c_codigo, 
                   ft.jefe_c_celular, 
                   ft.observacion, 
                   ft.fecha_registro, 
                   ft.usuario_registro,
                   ft.coordenada_x,
                   ft.coordenada_y,
                   ft.id_estacion,
                   TIMEDIFF(now(), ft.fecha_registro) tiempoTranscurrido,
                   (SELECT e.estacionDesc 
                      FROM estacion e
                     WHERE e.idEstacion = id_estacion)estacionDesc,
                    CASE WHEN  NOW() < ft.fecha_registro + INTERVAL 12 HOUR THEN '#8CE857'
                         ELSE '#F6A5A5' END as color,
                    f.faseDesc
              FROM ficha_tecnica ft,
                   planobra po,
                   fase f
              WHERE ft.itemplan = po.itemplan
                AND po.idFase = f.idFase
                AND ft.flg_activo = 1
                AND ft.itemplan = COALESCE(?, ft.itemplan)
                AND NOW() < ft.fecha_registro + INTERVAL 24 HOUR
                AND ft.id_ficha_tecnica = COALESCE(?, ft.id_ficha_tecnica)
                AND CASE WHEN ".$idEcc." =0 or ".$idEcc."=6 THEN              
                ft.usuario_registro = ft.usuario_registro
                         ELSE ft.usuario_registro = ".$idUsuario." END";
      $result = $this->db->query($sql, array($itemplan, $idFichaTecnica));
      return $result->result();
    }

    function getMaterialDetalle($idFichaTecnina) {
      $sql="SELECT ftt.descripcion as suministro, 
                    fttt.descripcion as tipo,
                    fti.cantidad,
                    fti.observacion,
                    fti.id_ficha_tecnica,
                    fti.id_ficha_tecnica_tipo_trabajo,
                    fti.id_ficha_tecnica_trabajo
              FROM ficha_tecnica_tipo_trabajo fttt,
                   ficha_tecnica_trabajo ftt,
                   ficha_tecnica_x_tipo_trabajo fti
             WHERE fti.id_ficha_tecnica = '".$idFichaTecnina."'
               AND fti.id_ficha_tecnica_trabajo       = ftt.id_ficha_tecnica_trabajo
               AND fttt.id_ficha_tecnica_tipo_trabajo = fti.id_ficha_tecnica_tipo_trabajo";
      $result = $this->db->query($sql);
      return $result->result();
    }

    function getIdFichaTecnicaXTipoTrabajo($idFichaTecnica, $id_ficha_tecnica_trabajo) {
      $sql = "SELECT id_ficha_tecnica_x_tipo_trabajo
                FROM ficha_tecnica_x_tipo_trabajo
               WHERE id_ficha_tecnica_trabajo = ".$id_ficha_tecnica_trabajo."
                 AND id_ficha_tecnica = ".$idFichaTecnica;
      $result= $this->db->query($sql);  
      return $result->row_array()['id_ficha_tecnica_x_tipo_trabajo'];         
    }

    function updateFichaTecnica($idFichaTecnica, $arrayJson) {
      // $this->db->where('id_ficha_tecnica', $idFichaTecnica);
      $this->db->update_batch('ficha_tecnica_x_tipo_trabajo', $arrayJson, 'id_ficha_tecnica_x_tipo_trabajo');  
      if($this->db->affected_rows() == 0) {
        return 0;
        } else {
          return 1;
      }
    }
    
    function getTablaConsultaObraPub() {
      $sql = "SELECT fop.id,
                     fop.itemplan, 
                     fop.ptr,
                     fop.canalizacion_km,
                     fop.camaras_und,
                     fop.c_postes,
                     fop.ma_postes,
                     fop.km_ducto,
                     fop.km_tritubo,
                     fop.km_par_cobre,
                     fop.km_cable_coax,
                     fop.km_fo,
                     fop.observacion,
                     DATE(fop.fecha_form)fecha_form,
                     fop.usuario_registro,
                     fop.fecha_registro,
                     fop.idEstacion,
                     f.faseDesc
                FROM form_obra_publica fop,
                     planobra po,
                     fase f
               WHERE fop.itemplan = po.itemplan
                 AND po.idFase = f.idFase";
      $result = $this->db->query($sql);  
      return $result->result();         
    }

    function getTablaConsultByItemplanEstacion($itemplan, $idEstacion) {
      $sql = "SELECT id,
                     itemplan, 
                     ptr,
                     canalizacion_km,
                     camaras_und,
                     c_postes,
                     ma_postes,
                     km_ducto,
                     km_tritubo,
                     km_par_cobre,
                     km_cable_coax,
                     km_fo,
                     observacion,
                     date(fecha_form)fecha_form,
                     usuario_registro,
                     fecha_registro,
                     idEstacion
                FROM form_obra_publica
               WHERE itemplan   = '".$itemplan."'";
                 //AND idEstacion = ".$idEstacion;
      $result = $this->db->query($sql);  
      return $result->row_array();         
    }

    function updateData($arrayData) {
      $this->db->where('id', $arrayData['id']);
      $this->db->update('form_obra_publica', $arrayData);
    }
}