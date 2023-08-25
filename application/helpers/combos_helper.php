<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('__buildComboProyecto')) {
    function __buildComboProyecto()
    {
        $CI = &get_instance();
        $arrayProyecto = $CI->m_utils->getProyectoCmb();
        $cmb = null;
        foreach ($arrayProyecto as $row) {
            $cmb .= '<option value="' . $row->idProyecto . '">' . utf8_decode($row->proyectoDesc) . '</option>';
        }
        return __cmbHTML($cmb, 'cmbProyecto', 'filtrarSubProyecto()', 'select2');
    }
}

if (!function_exists('__buildSubProyecto')) {
    function __buildSubProyecto($idProyecto, $idTipoPlanta, $flg)
    {
        $CI = &get_instance();
        $arraySubProyecto = $CI->m_utils->getSubProyectoCmb($idProyecto, $idTipoPlanta);
        $cmb = null;
        $cmb .= '<option value="">Seleccionar<option>';
        foreach ($arraySubProyecto as $row) {
            $cmb .= '<option value="' . $row->idSubProyecto . '">' . utf8_decode($row->subProyectoDesc) . '</option>';
        }

        if ($flg == 1) {
            return $cmb;
        } else {
            return __cmbHTML($cmb, 'cmbSubProyecto', 'filtrarTabla()', 'select2');
        }
    }
}

if (!function_exists('__buildComboEstacion')) {
    function __buildComboEstacion($flg)
    {
        $CI = &get_instance();
        $arrayEstacion = $CI->m_utils->getEstacionCmb($flg);
        $cmb = null;
        foreach ($arrayEstacion as $row) {
            $cmb .= '<option value="' . $row->idEstacion . '">' . utf8_decode($row->estacionDesc) . '</option>';
        }
        return __cmbHTML($cmb, 'idEstacion', 'filtrarTabla()', 'select2');
    }
}

if (!function_exists('__cmbHTML')) {
    function __cmbHTML($html, $id, $onchange, $class, $nombre = null)
    {
        $cmbHtml = '<select id="' . $id . '" class="' . $class . '" onchange="' . $onchange . '">
                        <option value="">Seleccionar ' . $nombre . '</option>
                        ' . $html . '
                    </select>';
        return $cmbHtml;
    }
}

if (!function_exists('__buildComboPlanta')) {
    function __buildComboPlanta()
    {
        $CI = &get_instance();
        $arrayPlanta = $CI->m_utils->getPlantaCmb();
        $cmb = null;
        foreach ($arrayPlanta as $row) {
            $cmb .= '<option value="' . $row->idTipoPlanta . '">' . utf8_decode($row->tipoPlantaDesc) . '</option>';
        }
        return __cmbHTML($cmb, 'idTipoPlanta', 'filtrarTabla()', 'select2');
    }
}

if (!function_exists('__buildComboJefatura')) {
    function __buildComboJefatura()
    {
        $CI = &get_instance();
        $arrayJefatura = $CI->m_utils->getJefaturaCmb();
        $cmb = null;
        foreach ($arrayJefatura as $row) {
            $cmb .= '<option value="' . $row->jefatura . '">' . utf8_decode($row->jefatura) . '</option>';
        }
        return __cmbHTML($cmb, 'cmbJefatura', 'filtrarTabla()', 'select2');
    }
}

if (!function_exists('__buildComboSerie')) {
    function __buildComboSerie()
    {
        $CI = &get_instance();
        $arraySerie = $CI->m_utils->getSerieCmb();
        $cmb = null;
        foreach ($arraySerie as $row) {
            $cmb .= '<option value="' . utf8_decode($row->idSerieTroba) . '">' . $row->serie . '</option>';
        }
        return __cmbHTML($cmb, 'cmbSerieTroba', NULL, 'form-control');
    }
}

if (!function_exists('__buildComboPorcentaje')) {
    function __buildComboPorcentaje($cont, $porcentaje)
    {
        $CI = &get_instance();
        $arrayPorcentaje = $CI->m_utils->getPorcentajeCmb();
        $cmb = null;
        foreach ($arrayPorcentaje as $row) {
            $cmb .= '<option ';
            if ($porcentaje == $row->porcentaje) {
                $cmb .= 'selected ';
            }
            $cmb .= 'value="' . $row->porcentaje . '">' . $row->porcentaje . '%</option>';
        }
        return __cmbHTML($cmb, 'cmbPorcentaje' . $cont, NULL, 'form-control');
    }
}

if (!function_exists('__buildComboTipoSolicitud')) {
    function __buildComboTipoSolicitud($id, $onchange)
    {
        $CI = &get_instance();
        $arrayTipooSolicitud = $CI->m_utils->getTipoSolicitud();
        $cmb = null;
        foreach ($arrayTipooSolicitud as $row) {
            $cmb .= '<option ';
            // if($porcentaje == $row->idTipoSolicitud) {
            //     $cmb.='selected ';
            // }
            $cmb .= 'value="' . $row->idTipoSolicitud . '">' . $row->descripcion . '</option>';
        }
        return __cmbHTML($cmb, $id, $onchange, 'form-control');
    }
}

if (!function_exists('__buildComboTipoSolicitudReg')) {
    function __buildComboTipoSolicitudReg($id, $onchange, $flg, $cantidad)
    {
        $CI = &get_instance();
        $arrayTipooSolicitud = $CI->m_utils->getCmbTipoSolicitudRegistro($cantidad);
        $cmb = null;
        foreach ($arrayTipooSolicitud as $row) {
            $cmb .= '<option ';
            // if($porcentaje == $row->idTipoSolicitud) {
            //     $cmb.='selected ';
            // }
            $cmb .= 'value="' . $row->idTipoSolicitud . '">' . $row->descripcion . '</option>';
        }
        return __cmbHTML($cmb, $id, $onchange, 'form-control');
    }
}

if (!function_exists('__buildComboZonal')) {
    function __buildComboZonal($id, $onchange)
    {
        $CI = &get_instance();
        $arrayZonal = $CI->m_utils->getAllZonal();
        $cmb = null;
        foreach ($arrayZonal->result() as $row) {
            $cmb .= '<option value="' . $row->idZonal . '">' . $row->zonalDesc . '</option>';
        }
        return __cmbHTML($cmb, $id, $onchange, 'form-control', 'zonal');
    }
}

if (!function_exists('__buildComboEcc')) {
    function __buildComboEcc($id, $onchange, $idEmpresaColabSession, $idEmpresaColabFiltro)
    {
        $CI = &get_instance();
        $arrayEcc = $CI->m_utils->getECCbyidEmpresaSession($idEmpresaColabSession, $idEmpresaColabFiltro);
        $cmb = null;
        $selec = null;
        foreach ($arrayEcc->result() as $row) {
            if ($row->idEmpresaColab == $idEmpresaColabFiltro) {
                $selec = 'selected';
            }
            $cmb .= '<option value="' . $row->idEmpresaColab . '" ' . $selec . '>' . $row->empresaColabDesc . '</option>';
        }
        return __cmbHTML($cmb, $id, $onchange, 'form-control', 'ecc');
    }
}

if (!function_exists('__buildComboEstacionAll')) {
    function __buildComboEstacionAll($id, $onchange, $idEstacionFiltro)
    {
        $CI = &get_instance();
        $arrayEstacion = $CI->m_utils->getEstacionCmb(null, $idEstacionFiltro);
        $cmb = null;
        $selec = null;
        foreach ($arrayEstacion as $row) {
            if ($row->idEstacion == $idEstacionFiltro) {
                $selec = 'selected';
            }
            $cmb .= '<option value="' . $row->idEstacion . '" ' . $selec . '>' . utf8_decode($row->estacionDesc) . '</option>';
        }
        return __cmbHTML($cmb, $id, $onchange, 'form-control', 'estacion');
    }
}

if (!function_exists('__buildComboTipoCostoAll')) {
    function __buildComboTipoCostoAll($id, $onchange, $idPrecioFiltro)
    {
        $CI = &get_instance();
        $arrayEstacion = $CI->m_utils->getAllTipoCosto($idPrecioFiltro);
        $cmb = null;
        $selec = null;
        foreach ($arrayEstacion as $row) {
            if ($row['idPrecioDiseno'] == $idPrecioFiltro) {
                $selec = 'selected';
            }
            $cmb .= '<option value="' . $row['idPrecioDiseno'] . '" ' . $selec . '>' . utf8_decode($row['descPrecio']) . '</option>';
        }
        return __cmbHTML($cmb, $id, $onchange, 'form-control', 'tipo costo');
    }
}

if (!function_exists('__buildComboTipoCostoByZonalEmpresa')) {
    function __buildComboTipoCostoByZonalEmpresa($id, $onchange, $idEmpresaColab, $idZonal)
    {
        $CI = &get_instance();
        $arrayTipoCosto = $CI->m_utils->getTipoCostoByZonalAndEmpresa($idEmpresaColab, $idZonal);
        $cmb = null;
        foreach ($arrayTipoCosto as $row) {
            $cmb .= '<option value="' . $row['idTipoCosto'] . '">' . utf8_decode($row['descripcion']) . '</option>';
        }
        return __cmbHTML($cmb, $id, $onchange, 'form-control', 'tipo costo');
    }
}

if (!function_exists('__buildComboAuto')) {
    function __buildComboAuto($itemplan)
    {
        $CI = &get_instance();
        $arrayPlacas = $CI->m_utils->getAllAutoByItemplan($itemplan);
        $cmb = null;
        $selected = '';
        foreach ($arrayPlacas as $row) {
            if ($row['flgPlaca'] == 1) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $cmb .= '<option value="' . $row['placa'] . '" ' . $selected . '>' . utf8_decode($row['placa']) . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboSubProyectoCV')) {
    function __buildComboSubProyectoCV($arraySub, $itemplan)
    {
        $CI = &get_instance();
        $arraySub = $CI->m_utils->getSubProyectoById($arraySub, null, $itemplan);
        $cmb = null;
        $selected = '';
        //$cmb .= '<option value="">Seleccionar</option>';
        foreach ($arraySub as $row) {
            if ($row['flgSubSelected'] == 1) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $cmb .= '<option value="' . $row['idSubProyecto'] . '" ' . $selected . '>' . $row['subproyectoDescDos'] . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboFase')) {
    function __buildComboFase()
    {
        $CI = &get_instance();
        $arrayFase = $CI->m_utils->getArrayFase();
        $cmb = null;
        $selected = '';
        //$cmb .= '<option value="">Seleccionar</option>';
        foreach ($arrayFase as $row) {
            if ($row['anio']) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $cmb .= '<option value="' . $row['idFase'] . '" ' . $selected . '>' . $row['faseDesc'] . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboComplejidad')) {
    function __buildComboComplejidad($codigo_po)
    {
        $CI = &get_instance();
        //$arrayFase = $CI->m_utils->getComplejidad($itemplan);
        $arrayData = $CI->m_utils->getComplejidadByCodigoPo($codigo_po);
        $cmb = null;
        $selected = '';
        //$cmb .= '<option value="">Seleccionar</option>';
        foreach ($arrayData as $row) {
            if ($row['flgSelected']) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $cmb .= '<option value="' . $row['idTipoComplejidad'] . '" ' . $selected . '>' . $row['complejidadDesc'] . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboEstacionByItemplan')) {
    function __buildComboEstacionByItemplan($itemplan, $idEstacion, $flgCambioPo = null)
    {
        $CI = &get_instance();
        $arrayEstacion = $CI->m_utils->getEstacionByItemplanCmb($itemplan, $idEstacion, $flgCambioPo);
        $cmb = '<option value="">Seleccionar</option>';
        foreach ($arrayEstacion as $row) {
            $cmb .= '<option value="' . $row['idEstacion'] . '">' . utf8_decode($row['estacionDesc']) . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildCmbCodigoPoByEstacionItemplan')) {
    function __buildCmbCodigoPoByEstacionItemplan($itemplan, $idEstacion)
    {
        $CI = &get_instance();
        $arrayEstacion = array();
        $arrayEstacion = $CI->m_utils->getCodigoPoByEstacionItemplan($itemplan, $idEstacion);

        if (count($arrayEstacion) > 0) {
            $cmb = '<option value="">Seleccionar</option>';
            foreach ($arrayEstacion as $row) {
                $cmb .= '<option value="' . $row['codigo_po'] . '">' . $row['codigo_po'] . '</option>';
            }
        } else {
            $cmb = null;
        }
        return $cmb;
    }
}

if (!function_exists('__buildCmbMdf')) {
    function __buildCmbMdf()
    {
        $CI = &get_instance();
        $arrayCentral = array();
        $arrayCentral = $CI->m_utils->getAllCentral();

        if (count($arrayCentral->result()) > 0) {
            $cmb = '<option value="">Seleccionar</option>';
            foreach ($arrayCentral->result() as $row) {
                $cmb .= '<option value="' . $row->idCentral . '">' . utf8_decode($row->tipoCentralDesc) . '</option>';
            }
        } else {
            $cmb = null;
        }
        return $cmb;
    }
}

if (!function_exists('__buildCmbMotivo')) {
    function __buildCmbMotivo($flgTipo, $flgSisego = null)
    {
        $CI = &get_instance();

        $array = array();
        // EL $flgSisego SIRVE PARA INDICARME CUANDO NECESITO EL CAMPO idSisego y se va a concatenar en el combo.
        $array = $CI->m_utils->getMotivoAll($flgTipo);

        if (count($array) > 0) {
            $cmb = '<option value="">Seleccionar</option>';
            foreach ($array as $row) {
                if ($flgSisego == 1) {
                    $cmb .= '<option value="' . $row->idMotivo . '|' . $row->idSisego . '">' . utf8_decode($row->motivoDesc) . '</option>';
                } else {
                    $cmb .= '<option value="' . $row->idMotivo . '">' . utf8_decode($row->motivoDesc) . '</option>';
                }
            }
        } else {
            $cmb = null;
        }
        return $cmb;
    }
}

if (!function_exists('__buildCmbContratosAll')) {
    function __buildCmbContratosAll()
    {
        $CI = &get_instance();

        $array = array();
        // EL $flgSisego SIRVE PARA INDICARME CUANDO NECESITO EL CAMPO idSisego y se va a concatenar en el combo.
        $array = $CI->m_utils->getContratosAll();

        if (count($array) > 0) {
            $cmb = '<option value="">Seleccionar</option>';
            foreach ($array as $row) {
                $cmb .= '<option value="' . $row['id_contratos'] . '">' . utf8_decode($row['nombre']) . '</option>';
            }
        } else {
            $cmb = null;
        }
        return $cmb;
    }
}

if (!function_exists('__buildCmbEmpresaColab')) {
    function __buildCmbEmpresaColab($flg_solicitud_usua_siom = null, $id_contrato = null)
    {
        $CI = &get_instance();

        $array = array();
        // EL $flgSisego SIRVE PARA INDICARME CUANDO NECESITO EL CAMPO idSisego y se va a concatenar en el combo.
        $array = $CI->m_utils->getAllEmpresaColab($flg_solicitud_usua_siom, $id_contrato);

        if (count($array) > 0) {
            $cmb = '<option value="">Seleccionar</option>';
            foreach ($array as $row) {
                $cmb .= '<option value="' . $row['idEmpresaColab'] . '">' . utf8_decode($row['empresaColabDesc']) . '</option>';
            }
        } else {
            $cmb = null;
        }
        return $cmb;
    }
}

if (!function_exists('__buildCmbZona')) {
    function __buildCmbZona()
    {
        $CI = &get_instance();

        $array = array();
        // EL $flgSisego SIRVE PARA INDICARME CUANDO NECESITO EL CAMPO idSisego y se va a concatenar en el combo.
        $arrayZonal = $CI->m_utils->getZona(FLG_CONTRATO_ZONA_USUARIO_SIOM);
        $cmb = null;
        foreach ($arrayZonal as $row) {
            $cmb .= '<option value="' . $row['id_zona'] . '">' . $row['nombre'] . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildCmbPerfil')) {
    function __buildCmbPerfil($flg_solicitud_usua_siom = null)
    {
        $CI = &get_instance();

        $array = array();

        $array = $CI->m_utils->getPerfilAll($flg_solicitud_usua_siom);

        if (count($array) > 0) {
            $cmb = '<option value="">Seleccionar</option>';
            foreach ($array as $row) {
                $cmb .= '<option value="' . $row['id_perfil'] . '">' . utf8_decode($row['desc_perfil']) . '</option>';
            }
        } else {
            $cmb = null;
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboTipoSolicitudRpa')) {
    function __buildComboTipoSolicitudRpa($id, $onchange, $flgAnioActual, $countPepBianual)
    {
        $CI = &get_instance();
        $arrayTipoSolicitud = $CI->m_utils->getTipoSolicitud();
        $cmb = null;
        foreach ($arrayTipoSolicitud as $row) {
            if ($flgAnioActual == 1) {
                $cmb .= '<option ';
                $cmb .= 'value="' . $row->idTipoSolicitud . '">' . $row->descripcion . '</option>';
            } else {

                if ($countPepBianual > 0) {
                    $cmb .= '<option ';
                    $cmb .= 'value="' . $row->idTipoSolicitud . '">' . $row->descripcion . '</option>';
                } else {
                    if ($row->idTipoSolicitud == 4) {
                        $cmb .= '<option ';
                        $cmb .= 'value="' . $row->idTipoSolicitud . '">' . $row->descripcion . '</option>';
                    }
                }
            }
        }
        return __cmbHTML($cmb, $id, $onchange, 'form-control');
    }
}

if (!function_exists('__buildCmbPlanificacionItem')) {
    function __buildCmbPlanificacionItem($idSubProyecto, $idFase, $id_plan_mes = null)
    {
        $CI = &get_instance();

        $array = array();

        $array = $CI->m_utils->getDataPlanificacionItem($idSubProyecto, $idFase);

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($array as $row) {
            $selected = null;

            if ($id_plan_mes == $row['id_plan']) {
                $selected = 'selected';
            }
            $cmb .= '<option value="' . $row['id_plan'] . '" ' . $selected . '>' . utf8_decode($row['nombre_plan']) . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildCmbMes')) {
    function __buildCmbMes()
    {
        $CI = &get_instance();

        $array = array();

        $array = $CI->m_utils->getMesAll();

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($array as $row) {
            $cmb .= '<option value="' . $row['id_mes'] . '">' . utf8_decode($row['nombre']) . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildCmbFase')) {
    function __buildCmbFase()
    {
        $CI = &get_instance();

        $array = array();

        $array = $CI->m_utils->getArrayFase();

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($array as $row) {
            $selected = null;
            if ($row['anio']) {
                $selected = 'selected';
            }
            $cmb .= '<option value="' . $row['idFase'] . '" ' . $selected . '>' . utf8_decode($row['faseDesc']) . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildCmbItemplanMadre')) {
    function __buildCmbItemplanMadre($idProyecto, $idSubProyecto)
    {
        $CI = &get_instance();

        $array = array();

        $array = $CI->m_utils->getDataItemplanMadre($idProyecto, $idSubProyecto);

        $cmb = '<option value="">Seleccionar Itemplan Madre</option>';
        foreach ($array as $row) {
            $cmb .= '<option value="' . $row['itemplan_m'] . '">' . $row['itemplan_m'] . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildComboEstacionNewAll')) {
    function __buildComboEstacionNewAll()
    {
        $CI = &get_instance();
        $arrayEstacion = $CI->m_utils->getEstacionCmb(1);
        $cmb = null;
        $selec = null;
        $cmb = '<option value="">Seleccionar</option>';
        foreach ($arrayEstacion as $row) {
            $cmb .= '<option value="' . $row->idEstacion . '" ' . $selec . '>' . utf8_decode($row->estacionDesc) . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboTipoIncidente')) {
    function __buildComboTipoIncidente($id_modulo)
    {
        $CI = &get_instance();
        $arrayTipos = $CI->m_utils->getTipoIncidentes($id_modulo);
        $cmb = null;
        $selec = null;
        $cmb = '<option value="">Seleccionar</option>';
        foreach ($arrayTipos as $row) {
            $cmb .= '<option value="' . $row['id_tipo_incidente'] . '" ' . $selec . '>' . utf8_decode($row['descripcion']) . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboEBCs')) {
    function __buildComboEBCs($departamento)
    {
        $CI = &get_instance();
        $arrayEbc = $CI->m_utils->getEbcByDistrito($departamento);
        $cmb = null;

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($arrayEbc as $row) {
            $cmb .= '<option value="' . $row['codigo'] . '">' . utf8_decode($row['nom_estacion']) . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboContrato')) {
    function __buildComboContrato($estado)
    {
        $CI = &get_instance();
        $arrayEbc = $CI->m_utils->getContratoAll($estado);
        $cmb = null;

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($arrayEbc as $row) {
            $cmb .= '<option value="' . $row['id_contrato'] . '">' . utf8_decode($row['nombre']) . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboConfigOpex')) {
    function __buildComboConfigOpex($estado)
    {
        $CI = &get_instance();
        $arrayData = $CI->m_utils->getDataConfigOpex($estado);
        $cmb = null;

        $cmb = '<option value="">Seleccionar</option>';
        $cont = 1;
        foreach ($arrayData as $row) {
            $cmb .= '<option value="' . $cont . '">' . utf8_decode($row['descripcion_cuenta']) . '</option>';
            $cont++;
        }
        return $cmb;
    }
}

if (!function_exists('__buildComboContratoPadre')) {
    function __buildComboContratoPadre($idSubProyecto)
    {
        $CI = &get_instance();
        //$arrayList = $CI->m_utils->getContratoPadre($idSubProyecto);
        $arrayList = $CI->m_utils->getContratoPadre_Liberado($idSubProyecto);
        $cmb = null;

        $cmb = '<option value="">Seleccionar Contrato</option>';
        foreach ($arrayList as $row) {
            $cmb .= '<option value="' . $row['id_contrato_padre'] . '">' . utf8_decode($row['nombre']) . '</option>';
        }
        return $cmb;
    }
}

if (!function_exists('__buildCmbEECC')) {
    function __buildCmbEECC($idContratoPadre = null)
    {
        $CI = &get_instance();

        $array = array();
        // EL $flgSisego SIRVE PARA INDICARME CUANDO NECESITO EL CAMPO idSisego y se va a concatenar en el combo.
        $array = $CI->m_utils->getAllEmpresaColabByIdContratoPadre($idContratoPadre);

        if (count($array) > 0) {
            $cmb = '<option value="">Seleccionar</option>';
            foreach ($array as $row) {
                $cmb .= '<option value="' . $row['idEmpresaColab'] . '">' . utf8_decode($row['empresaColabDesc']) . '</option>';
            }
        } else {
            $cmb = null;
        }
        return $cmb;
    }
}

if (!function_exists('__buildCmbTipoEntidad')) {
    function __buildCmbTipoEntidad($idTipoEntidad = null, $idEntidad = null)
    {
        $CI = &get_instance();
        $arrayDataTipoEntidad = $CI->m_utils->getTipoEntidadAll($idEntidad);
        $html = null;
        $html = '<option value="">Seleccionar Tipo</option>';
        foreach ($arrayDataTipoEntidad as $row) {
            $selected = ($row['id_tipo_entidad'] == $idTipoEntidad) ? 'selected' : null;
            $html .= '<option value="' . $row['id_tipo_entidad'] . '" ' . $selected . ' >' . $row['tipoEntidadDesc'] . '</option>';
        }
        return $html;
    }
}

if (!function_exists('__buildCmbDistrito')) {
    function __buildCmbDistrito($idDistrito = null)
    {
        $CI = &get_instance();
        $arrayDataDistrito = $CI->m_utils->getDistritoAll();
        $html = null;
        $html = '<option value="">Seleccionar Tipo</option>';
        foreach ($arrayDataDistrito as $row) {
            $selected = ($row['idDistrito'] == $idDistrito) ? 'selected' : null;
            $html .= '<option value="' . $row['idDistrito'] . '" ' . $selected . ' >' . $row['distritoDesc'] . '</option>';
        }
        return $html;
    }
}

if (!function_exists('__buildComboEntidades')) {
    function __buildComboEntidades($itemplan, $idEstacion, $idEntidad = null)
    {
        $CI = &get_instance();
        $listaMotivo = $CI->m_utils->getEntidades($itemplan, $idEstacion);
        $html = null;
        // $html .= '<option value="">NO REQUIERE LICENCIA</option>';
        $hasEntidad = null;
        // $html .= '<option value="" '.($hasEntidad).'>NO REQUIERE LICENCIA</option>';
        $html2 = null;
        foreach ($listaMotivo as $row) {
            $selected = ($row->idEntidad == $idEntidad) ? 'selected' : null;
            if ($row->marcado == 1) {
                $disabled = 'disabled';
                $hasEntidad = true;
            } else {
                $disabled = null;
            }
            $html2 .= '<option value="' . $row->idEntidad . '" ' . $selected . ' ' . $disabled . ' >' . $row->entidadDesc . '</option>';
        }

        $html .= '<option value="" ' . ($hasEntidad ? 'disabled' : null) . '>NO REQUIERE LICENCIA</option>' . $html2;

        return $html;
    }
}

if (!function_exists('__buildCmbMotivo')) {
    function __buildCmbMotivo($flgTipo, $flgSisego = null)
    {
        $CI = &get_instance();

        $array = array();
        // EL $flgSisego SIRVE PARA INDICARME CUANDO NECESITO EL CAMPO idSisego y se va a concatenar en el combo.
        $array = $CI->m_utils->getMotivoAll($flgTipo);

        if (count($array) > 0) {
            $cmb = '<option value="">Seleccionar</option>';
            foreach ($array as $row) {
                if ($flgSisego == 1) {
                    $cmb .= '<option value="' . $row->idMotivo . '|' . $row->idSisego . '">' . utf8_decode($row->motivoDesc) . '</option>';
                } else {
                    $cmb .= '<option value="' . $row->idMotivo . '">' . utf8_decode($row->motivoDesc) . '</option>';
                }
            }
        } else {
            $cmb = null;
        }
        return $cmb;
    }
}


if (!function_exists('__buildCmbEntidadEstado')) {
    function __buildCmbEntidadEstado($idEntidadEstado = null)
    {
        $CI = &get_instance();
        $arrEntidadEstado = $CI->m_utils->getEntidadEstadoAll();

        $html = '<option value="">Seleccionar Estado</option>';
        foreach ($arrEntidadEstado as $row) {
            $html .= '<option value="' . $row['idEntidadEstado'] . '" ' . ($row['idEntidadEstado'] == $idEntidadEstado ? 'selected' : '') . ' >' . $row['entidadEstadoDesc'] . '</option>';
        }
        return $html;
    }
}

if (!function_exists('__buildCmbCompromisoEstado')) {
    function __buildCmbCompromisoEstado($idCompromisoEstado = null)
    {
        $CI = &get_instance();

        $array = [];
        $array = $CI->m_utils->getCompromisoEstadoAll();

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($array as $row) {
            $cmb .= '<option value="' . $row['idEstadoCompromiso'] . '" ' . ($row['idEstadoCompromiso'] == $idCompromisoEstado ? 'selected' : '') . ' >' . $row['compromisoEstadoDesc'] . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildCmbEntidadTipoEvidenciaAll')) {
    function __buildCmbEntidadTipoEvidenciaAll($idEntidadTipoEvidencia = null)
    {
        $CI = &get_instance();

        $array = [];
        $array = $CI->m_utils->getEntidadTipoEvidenciaAll();

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($array as $row) {
            $cmb .= '<option value="' . $row['idEntidadTipoEvidencia'] . '" ' . ($row['idEntidadTipoEvidencia'] == $idEntidadTipoEvidencia ? 'selected' : '') . ' >' . $row['descripcionTipoEvidencia'] . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildCmbCompromisoUsuario')) {
    function __buildCmbCompromisoUsuario($idCompromisoUsuario = null)
    {
        $CI = &get_instance();

        $array = [];
        $array = $CI->m_utils->getCompromisoUsuarioAll();

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($array as $row) {
            $cmb .= '<option value="' . $row['idUsuarioCompromiso'] . '" ' . ($row['idUsuarioCompromiso'] == $idCompromisoUsuario ? 'selected' : '') . ' >' . $row['nombreUsuarioCompromiso'] . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildCmbGerenciaFirmaDigital')) {
    function __buildCmbGerenciaFirmaDigital($idGerencia)
    {
        $CI = &get_instance();

        $array = [];
        $array = $CI->m_utils->getGerenciaFirmaDigitalAll();

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($array as $row) {
            $cmb .= '<option value="' . $row['idGerencia'] . '" ' . ($row['idGerencia'] == $idGerencia ? 'selected' : '') . ' >' . $row['gerenciaDesc'] . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildCmbUsuarioByFirmaDigital')) {
    function __buildCmbUsuarioByFirmaDigital($id_usuario, $isTdp)
    {
        $CI = &get_instance();

        $array = [];
        $array = $CI->m_utils->getUsuarioByFirmaDigital($isTdp);

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($array as $row) {
            $cmb .= '<option value="' . $row['id_usuario'] . '" ' . ($row['id_usuario'] == $id_usuario ? 'selected' : '') . ' >' . $row['nombre'] . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildCmbTipoPlanta')) {
    function __buildCmbTipoPlanta($idTipoPlanta)
    {
        $CI = &get_instance();

        $array = [];
        $array = $CI->m_utils->getAllTipoPlantal()->result_array();

        $cmb = '<option value="">Seleccionar</option>';
        foreach ($array as $row) {
            $cmb .= '<option value="' . $row['idTipoPlanta'] . '" ' . ($row['idTipoPlanta'] == $idTipoPlanta ? 'selected' : '') . ' >' . $row['tipoPlantaDesc'] . '</option>';
        }

        return $cmb;
    }
}

if (!function_exists('__buildCmbZona_1_2_3')) {
    function __buildCmbZona_1_2_3()
    {
        $CI = &get_instance();

        $array = array();
        // EL $flgSisego SIRVE PARA INDICARME CUANDO NECESITO EL CAMPO idSisego y se va a concatenar en el combo.
        $arrayZonal = $CI->m_utils->getZona(2);
        $cmb = '<option value="">Seleccionar</option>';
        foreach ($arrayZonal as $row) {
            $cmb .= '<option value="' . $row['id_zona'] . '">' . $row['nombre'] . '</option>';
        }
        return $cmb;
    }
}

    if (!function_exists('__buildComboPromotor')) {
        function __buildComboPromotor($idSubProyecto, $estado)
        {
            $CI = &get_instance();
            $arrayData = $CI->m_utils->getPromotorAllSubProyecto($estado, $idSubProyecto);
            $cmb = null;

            $cmb = '<option value="">Seleccionar</option>';
            $selected = null;
            foreach ($arrayData as $row) {
                if ($row['flg_selected'] == 1) {
                    $selected = 'selected ';
                } else {
                    $selected = null;
                }

                $cmb .= '<option value="' . $row['id_promotor'] . '" ' . $selected . '>' . utf8_decode($row['nom_promotor']) . '</option>';
            }
            return $cmb;
        }
    }
	
	if (!function_exists('__buildEstadoFirmaAll')) {
		function __buildEstadoFirmaAll($estado)
		{
			$CI = &get_instance();
			$arrayData = $CI->m_utils->getEstadoFirmaAll($estado);
			$cmb = null;
			$cmb .= '<option value="">Seleccionar<option>';
			foreach ($arrayData as $row) {
				$cmb .= '<option value="' . $row['idEstadoFirma'] . '">' . $row['estadoFirmaDesc'] . '</option>';
			}

			return $cmb;
		}
	}