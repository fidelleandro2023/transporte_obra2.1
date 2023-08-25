<?php

class M_reporte_sisego extends CI_Model {

    //http://www.codeigniter.com/userguide3/database/results.html
    function __construct() {
        parent::__construct();
    }

    function sin_presupuesto_para() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto 
        WHERE
	pro.idProyecto = 3 
	AND po.solicitud_oc IS NULL 
	AND po.idEstadoPlan IN (2,7,19,20,3,8)
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function sin_presupuesto_no_para() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto 
        WHERE
	pro.idProyecto = 3 
	AND po.solicitud_oc IS NULL 
	AND po.idEstadoPlan IN (2,7,19,20,3,8)
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }
    
    function pendiente() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto 
        WHERE
	pro.idProyecto = 3 
	AND po.estado_sol_oc = 'PENDIENTE'
	AND po.idEstadoPlan IN (2,7,19,20,3,8)
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function pendiente_lima() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc,
	central.jefatura
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto
	LEFT JOIN pqt_central central on central.idCentral = po.idCentral
        WHERE
	pro.idProyecto = 3 
	AND po.estado_sol_oc = 'PENDIENTE' 
	AND po.idFase = 6 
	AND central.jefatura = 'LIMA'
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function pendiente_no_lima() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc,
	central.jefatura
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto
	LEFT JOIN pqt_central central on central.idCentral = po.idCentral
        WHERE
	pro.idProyecto = 3 
	AND po.estado_sol_oc = 'PENDIENTE' 
	AND po.idFase = 6 
	AND NOT central.jefatura = 'LIMA'
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function ip_trunco() {
        $Query = "SELECT
	es.estadoPlanDesc,
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto
	LEFT JOIN estadoplan es on es.idEstadoPlan = po.idEstadoPlan
        WHERE
	pro.idProyecto = 3 AND
	po.fechaTrunca LIKE '2020%'
	AND po.idEstadoPlan = 10
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function ip_cerrado() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto 
        WHERE
	pro.idProyecto = 3 AND 
	po.idEstadoPlan = 6 and
	po.idFase = 6
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function ip_certificado() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto 
        WHERE
	pro.idProyecto = 3 AND 
	po.idEstadoPlan = 23 and
	po.idFase = 6
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function ip_en_certificacion() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto 
        WHERE
	pro.idProyecto = 3 AND 
	po.idEstadoPlan = 22 and
	po.idFase = 6
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function ip_en_verificacion() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto 
        WHERE
	pro.idProyecto = 3 AND 
	po.idEstadoPlan = 21 and
	po.idFase = 6
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function ip_pre_liquidado() {
        $Query = "SELECT
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto 
        WHERE
	pro.idProyecto = 3 AND 
	po.idEstadoPlan = 9 and
	po.idFase = 6
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

    function ip_terminado() {
        $Query = "SELECT
	es.estadoPlanDesc,
	po.*,
	pro.idProyecto,
	pro.proyectoDesc 
        FROM
	planobra po
	LEFT JOIN subproyecto sub ON sub.idSubProyecto = po.idSubProyecto
	LEFT JOIN proyecto pro ON pro.idProyecto = sub.idProyecto
	LEFT JOIN estadoplan es on es.idEstadoPlan = po.idEstadoPlan
        WHERE
	pro.idProyecto = 3 
	AND po.idEstadoPlan = 4
        GROUP BY
	po.itemPlan;";
        $result = $this->db->query($Query, array());
        if ($result->row() != null) {
            return $result->result();
        } else {
            return null;
        }
    }

}
