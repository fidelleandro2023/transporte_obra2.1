<?php

class M_reporte_sub extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function obtenerSub($cmbEstado) {

        if ($cmbEstado == 1) {
            $sql = "SELECT 
                            sub.*,
                            SUM(sub.cinco+sub.seis+sub.once+sub.veinte) as total
                            FROM view_sub_1 sub
                            GROUP BY
                            sub.proyectoDesc;;
";
        } else {
            $sql = "SELECT 
                            sub.*,
                            SUM(sub.cinco+sub.seis+sub.once+sub.veinte) as total
                            FROM view_sub_2 sub
                            GROUP BY
                            sub.proyectoDesc";
        }
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function obtenerDetalleSub($idSubProyecto, $estado, $dias) {

        if ($estado == '1') {
            if ($dias === '1') {
                $sqlDias = " WHERE pro.idProyecto='$idSubProyecto' AND sol.estado='$estado' AND (workdaydiff(sol.fecha_creacion,now())-1)<5";
            } else if ($dias === '2') {
                $sqlDias = " WHERE pro.idProyecto='$idSubProyecto' AND sol.estado='$estado' AND (workdaydiff(sol.fecha_creacion,now())-1)>5 AND (workdaydiff(sol.fecha_creacion,now())-1)<11";
            } else if ($dias === '3') {
                $sqlDias = " WHERE pro.idProyecto='$idSubProyecto' AND sol.estado='$estado' AND (workdaydiff(sol.fecha_creacion,now())-1)>10 AND (workdaydiff(sol.fecha_creacion,now())-1)<21";
            } else if ($dias === '4') {
                $sqlDias = " WHERE pro.idProyecto='$idSubProyecto' AND sol.estado='$estado' AND (workdaydiff(sol.fecha_creacion,now())-1)>20";
            } else {
                $sqlDias = " WHERE sol.estado='$estado'";
            }
            $sql = "SELECT 
                sol.codigo_solicitud,
                sol.idSubProyecto,
                sub.subProyectoDesc,
                emp.empresaColabDesc,
                sol.plan,
                sol.pep1,
                sol.pep2,
                DATE(sol.fecha_creacion) fecha_creacion,
                CURDATE() AS fecha_actual,
		pro.idProyecto,
		pro.proyectoDesc,
                sol.estado,
		(SELECT DATEDIFF(NOW(), sol.fecha_creacion)) as dias_,
		workdaydiff(`sol`.`fecha_creacion`,NOW())-1 as dias
                FROM 
                solicitud_orden_compra sol
                INNER JOIN empresacolab emp on emp.idEmpresaColab = sol.idEmpresaColab
                INNER JOIN subproyecto sub on sub.idSubProyecto = sol.idSubProyecto
		INNER JOIN proyecto pro on pro.idProyecto = sub.idProyecto " . $sqlDias;
        } else {
            if ($dias === '1') {
                $sqlDias = " WHERE pro.idProyecto='$idSubProyecto' AND sol.estado='$estado' AND (workdaydiff(sol.fecha_creacion,sol.fecha_valida)-1)<5";
            } else if ($dias === '2') {
                $sqlDias = " WHERE pro.idProyecto='$idSubProyecto' AND sol.estado='$estado' AND (workdaydiff(sol.fecha_creacion,sol.fecha_valida)-1)>5 AND (workdaydiff(sol.fecha_creacion,sol.fecha_valida)-1)<11";
            } else if ($dias === '3') {
                $sqlDias = " WHERE pro.idProyecto='$idSubProyecto' AND sol.estado='$estado' AND (workdaydiff(sol.fecha_creacion,sol.fecha_valida)-1)>10 AND (workdaydiff(sol.fecha_creacion,sol.fecha_valida)-1)<21";
            } else if ($dias === '4') {
                $sqlDias = " WHERE pro.idProyecto='$idSubProyecto' AND sol.estado='$estado' AND (workdaydiff(sol.fecha_creacion,sol.fecha_valida)-1)>20";
            } else {
                $sqlDias = " WHERE sol.estado='$estado'";
            }
            $sql = "SELECT 
                sol.codigo_solicitud,
                sol.idSubProyecto,
                sub.subProyectoDesc,
                emp.empresaColabDesc,
                sol.plan,
                sol.pep1,
                sol.pep2,
                DATE(sol.fecha_creacion) AS fecha_creacion,
                DATE(sol.fecha_valida) AS fecha_actual, 
		pro.idProyecto,
		pro.proyectoDesc,
                sol.estado,
		(SELECT DATEDIFF(sol.fecha_valida, sol.fecha_creacion)) as dias_,                
                workdaydiff(`sol`.`fecha_creacion`,`sol`.`fecha_valida`)-1 as dias
                FROM 
                solicitud_orden_compra sol
                INNER JOIN empresacolab emp on emp.idEmpresaColab = sol.idEmpresaColab
                INNER JOIN subproyecto sub on sub.idSubProyecto = sol.idSubProyecto
		INNER JOIN proyecto pro on pro.idProyecto = sub.idProyecto" . $sqlDias;
        }

        $result = $this->db->query($sql);
        return $result->result();
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

