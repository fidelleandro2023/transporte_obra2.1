<?php
class M_registro_itemfault_po extends CI_Model{
	//http://www.codeigniter.com/userguide3/database/results.html
	function __construct(){
		parent::__construct();
		
	}
	 
	function   getPartidasByItemfault($itemfault, $idEstacion){
	    $Query = '    SELECT p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo 
                        FROM itemfault i,
                             partidas  p,
                             preciario pr,
                             servicio_elemento se,
                             precio_diseno pd,
                             partida_servicio_elem_estacion pe
                       WHERE i.idEmpresaColab  = pr.idEmpresaColab
                         AND i.idServicioElemento = se.idServicioElemento
                         AND i.idZonal        = pr.idzonal
                         AND p.idPrecioDiseno = pr.idPrecioDiseno
                         AND p.idPrecioDiseno = pd.idPrecioDiseno
                         AND pe.idServicioElemento = i.idServicioElemento
                         AND pe.idPartida          = p.codigo
                         AND pr.idEstacion         = 5 -- POR AHORA TOMAMOS EL COSTO DE FO
                         AND pe.idEstacion         = ?
                         AND p.estado 			   = 1
                         AND i.itemfault 		   = ?';
	
	    $result = $this->db->query($Query,array($idEstacion, $itemfault));
	    return $result->result();
	}
	
	function getInfoPartidasByCod($itemfault, $idEstacion, $codigo){
	    $Query = '    SELECT p.idActividad, p.codigo, p.descripcion, pd.descPrecio, p.baremo, pr.costo 
                        FROM itemfault i,
                             partidas  p,
                             preciario pr,
                             servicio_elemento se,
                             precio_diseno pd,
                             partida_servicio_elem_estacion pe
                       WHERE i.idEmpresaColab  = pr.idEmpresaColab
                         AND i.idServicioElemento = se.idServicioElemento
                         AND i.idZonal        = pr.idzonal
                         AND p.idPrecioDiseno = pr.idPrecioDiseno
                         AND p.idPrecioDiseno = pd.idPrecioDiseno
                         AND pe.idServicioElemento = i.idServicioElemento
                         AND pe.idPartida          = p.codigo
                         AND pr.idEstacion         = 5 -- POR AHORA TOMAMOS EL COSTO DE FO
                         AND pe.idEstacion         = ?
                         AND p.estado 			   = 1
                         AND i.itemfault 		   = ?
						 AND p.codigo              = ?';
	
	    $result = $this->db->query($Query,array($idEstacion, $itemfault, $codigo));
	    return $result->row_array();
	}
}