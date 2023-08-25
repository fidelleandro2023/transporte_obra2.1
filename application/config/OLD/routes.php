<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_metho
*/
$route['default_controller'] = 'C_login';
$route['validarFileUsuario'] = 'C_validarFile';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['testIndiAprosisego'] = 'cf_liquidacion/C_liquidacion/testTramaSigo';

$route['liqui'] = 'cf_liquidacion/C_liquidacion';
$route['asigGrafo'] = 'cf_liquidacion/C_liquidacion/asignarGrafo';
$route['getDataTable'] = 'cf_liquidacion/C_liquidacion/filtrarTabla';
$route['preAprob'] = 'cf_liquidacion/C_bandeja_pre_aprob';
$route['updtTo01'] = 'cf_liquidacion/C_bandeja_pre_aprob/updateTo01';
$route['getDataTablePre'] = 'cf_liquidacion/C_bandeja_pre_aprob/filtrarTabla';
$route['itemptr'] = 'cf_reportes_v/C_itemplan_ptr';
$route['getTableData'] = 'cf_reportes_v/C_itemplan_ptr/filtrarTabla';

/*********************19102018******************************/
$route['Prelogear'] = 'c_login/Prelogear';
$route['cambioPassword'] = 'c_login/cambioPassword';
$route['cambioPasswordI'] = 'c_login/cambioPasswordI';
/*******************************************************/

$route['logear'] = 'c_login/logear';
$route['logOut'] = 'c_login/logOut';
$route['loginTmp'] = 'c_login/logearTemp';
$route['login'] = 'c_login';

$route['segpdo'] = 'cf_reportes_v/C_seguimiento_pdo';
$route['getDatSeg'] = 'cf_reportes_v/C_seguimiento_pdo/filtrarTabla';
$route['tranwu'] = 'cf_tranferencias/C_tranferencia_wu';
$route['up1'] = 'cf_tranferencias/C_tranferencia_wu/upload1';
$route['up2'] = 'cf_tranferencias/C_tranferencia_wu/upload2';
$route['up3'] = 'cf_tranferencias/C_tranferencia_wu/upload3';
$route['up4'] = 'cf_tranferencias/C_tranferencia_wu/upload4';
$route['up5'] = 'cf_tranferencias/C_tranferencia_wu/upload5';
$route['up6'] = 'cf_tranferencias/C_tranferencia_wu/upload6';
$route['up7'] = 'cf_tranferencias/C_tranferencia_wu/upload7';
$route['up8'] = 'cf_tranferencias/C_tranferencia_wu/upload8';
$route['up9'] = 'cf_tranferencias/C_tranferencia_wu/upload9';
$route['up10'] = 'cf_tranferencias/C_tranferencia_wu/upload10';
$route['up20'] = 'cf_tranferencias/C_tranferencia_wu/uploadPO2_0';

$route['getSubPro'] = 'cf_reportes_v/C_seguimiento_pdo/getHTMLChoiceSubProy';

$route['pocar'] = 'cf_plan_obra/C_carga_masiva_po';
$route['uppo1'] = 'cf_plan_obra/C_carga_masiva_po/uploadPo';
$route['uppo2'] = 'cf_plan_obra/C_carga_masiva_po/uploadPo2';
$route['uppo3'] = 'cf_plan_obra/C_carga_masiva_po/uploadPo3';

$route['detalleObra'] = 'cf_detalle_obra/C_detalle_obra';
$route['ptrToEdit'] = 'cf_detalle_obra/C_detalle_obra/recogeEditar';
$route['ptrToInsert'] = 'cf_detalle_obra/C_detalle_obra/recogeInsertar';
$route['ptrInfo'] = 'cf_detalle_obra/C_detalle_obra/infoWeb';

$route['excelObras'] = 'cf_reportes_v/C_obras_excel';

$route['itemMO'] = 'cf_reportes_v/C_itemplan_ptr_mo';
$route['getTableData_mo'] = 'cf_reportes_v/C_itemplan_ptr_mo/filtrarTabla';

$route['expediente'] = 'cf_expediente/C_expediente2';
$route['getDataTableExp'] = 'cf_expediente/C_expediente2/filtrarTabla';
$route['asignarExpediente'] = 'cf_expediente/C_expediente2/asignarExpediente';

$route['extrac'] = 'cf_extractor/C_extractor';
$route['exceldetalle'] = 'cf_extractor/C_extractor/generar_excelD';
$route['excelplan'] = 'cf_extractor/C_extractor/generar_excelP';

// Mantenimientos
$route['mUsuario'] = 'cf_mantenimiento/C_usuario';
$route['mNuevoUsuario'] = 'cf_mantenimiento/C_nuevo_usuario';

$route['updatedesac'] = 'cf_mantenimiento/C_usuario/updatedesac';
$route['updateactiv'] = 'cf_mantenimiento/C_usuario/updateactiv';
$route['enviarDatosUsuario'] = 'cf_mantenimiento/C_nuevo_usuario/getUsuario';

// carga masiva detalleplan
$route['dpcarga'] = 'cf_detalle_obra/C_carga_masiva_dp';
$route['updp1'] = 'cf_detalle_obra/C_carga_masiva_dp/uploadDP';
$route['updp2'] = 'cf_detalle_obra/C_carga_masiva_dp/uploadDP2';
$route['updp3'] = 'cf_detalle_obra/C_carga_masiva_dp/uploadDP3';
$route['updp4'] = 'cf_detalle_obra/C_carga_masiva_dp/uploadDP4';

// Predise�����o
$route['prediseno'] = 'cf_pre_diseno/C_prediseno';
$route['getDataTablePreDise'] = 'cf_pre_diseno/C_prediseno/filtrarTabla';
$route['adjudicar'] = 'cf_pre_diseno/C_prediseno/adjudicar';


//mantenimiento grafos
$route['mspg'] = 'cf_mantenimiento/C_subproy_pep_grafo';
$route['getPepData'] = 'cf_mantenimiento/C_subproy_pep_grafo/filtrarTabla';
$route['addSubPep'] = 'cf_mantenimiento/C_subproy_pep_grafo/addSubProPep';
$route['valiPepsub'] = 'cf_mantenimiento/C_subproy_pep_grafo/existeSubPepArea';
$route['delSubPep'] = 'cf_mantenimiento/C_subproy_pep_grafo/delSubProPep';
$route['delPepPep'] = 'cf_mantenimiento/C_subproy_pep_grafo/delPep1Pep2';
$route['addP1P2'] = 'cf_mantenimiento/C_subproy_pep_grafo/addPep1Pep2';
$route['regra1'] = 'cf_mantenimiento/C_subproy_pep_grafo/refreshGrafo1';
$route['regra2'] = 'cf_mantenimiento/C_subproy_pep_grafo/refreshGrafo2';
$route['regra3'] = 'cf_mantenimiento/C_subproy_pep_grafo/refreshGrafo3';
$route['regra4'] = 'cf_mantenimiento/C_subproy_pep_grafo/refreshGrafo4';
$route['reTables'] = 'cf_mantenimiento/C_subproy_pep_grafo/reloadTables';
$route['upPepGra'] = 'cf_mantenimiento/C_subproy_pep_grafo/uploadPep2Grafo';
$route['upPepGra2'] = 'cf_mantenimiento/C_subproy_pep_grafo/uploadPep2Grafo2';
$route['delSP2G'] = 'cf_mantenimiento/C_subproy_pep_grafo/delSisegoPepGrafo';
$route['upSPGra'] = 'cf_mantenimiento/C_subproy_pep_grafo/uploadSisegoPep2Grafo';
$route['upSPGra2'] = 'cf_mantenimiento/C_subproy_pep_grafo/uploadSisegoPep2Grafo2';
$route['upIPGra'] = 'cf_mantenimiento/C_subproy_pep_grafo/uploadItemPep2Grafo';
$route['upIPGra2'] = 'cf_mantenimiento/C_subproy_pep_grafo/uploadItemPep2Grafo2';
$route['delIP2G'] = 'cf_mantenimiento/C_subproy_pep_grafo/delItemPepGrafo';
$route['updatePep1Monto'] = 'cf_mantenimiento/C_subproy_pep_grafo/updatePep1Monto';
// Consulta ITEMPLAN
$route['consulta'] = 'cf_plan_obra/C_consulta';
$route['getDataTableItem'] = 'cf_plan_obra/C_consulta/filtrarTabla';

//SAP
$route['sapfi'] = 'cf_tranferencias/C_tranferencia_sap_fija';
$route['upsf1'] = 'cf_tranferencias/C_tranferencia_sap_fija/uploadSj1';
$route['upsf2'] = 'cf_tranferencias/C_tranferencia_sap_fija/uploadSj2';
$route['sapco'] = 'cf_tranferencias/C_tranferencia_sap_coaxial';
$route['upsc1'] = 'cf_tranferencias/C_tranferencia_sap_coaxial/uploadSc1';
$route['upsc2'] = 'cf_tranferencias/C_tranferencia_sap_coaxial/uploadSc2';
//
$route['preAproMo'] = 'cf_liquidacion/C_bandeja_pre_aprob_mo';
$route['getPtrByItm'] = 'cf_liquidacion/C_bandeja_pre_aprob_mo/getPtrsByItemPlan';

$route['CertificacionExtractor'] 	= 'cf_liquidacion/C_extractor';

$route['cancelCert'] = 'cf_liquidacion/C_bandeja_pre_aprob_mo/cancelCertificado';
$route['saveCerti'] = 'cf_liquidacion/C_bandeja_pre_aprob_mo/saveCertificado';
$route['aprobCert'] = 'cf_liquidacion/C_bandeja_pre_aprob_mo/aprobCertiFicado';

$route['getDataTableExpe'] = 'cf_liquidacion/C_bandeja_pre_aprob_mo/filtrarTabla';
$route['chqPtr'] = 'cf_liquidacion/C_bandeja_pre_aprob_mo/checkPtrItem';


// SEGUIMIENTO EC
$route['segec']          = 'cf_reportes_v/C_seguimiento_ec';
$route['getSubProEC']    = 'cf_reportes_v/C_seguimiento_ec/getHTMLChoiceSubProy';
$route['getDataEC']      = 'cf_reportes_v/C_seguimiento_ec/filtrarTabla';
$route['excelDetalleEC'] = 'cf_reportes_v/C_obras_ec_excel';//excelDetalleEC

//
$route['conPreCerti']       = 'cf_liquidacion/C_bandeja_pre_aprob_mo_consulta';
$route['cGetPtrByItm']      = 'cf_liquidacion/C_bandeja_pre_aprob_mo_consulta/getPtrsByItemPlan';
$route['cGetDataTableExpe'] = 'cf_liquidacion/C_bandeja_pre_aprob_mo_consulta/filtrarTabla';

$route['segpdo2']    = 'cf_reportes_v/C_seguimiento_pdo_2';
$route['getDetItem'] = 'cf_reportes_v/C_seguimiento_pdo_2/getDetalle';
$route['getDatSeg2'] = 'cf_reportes_v/C_seguimiento_pdo_2/filtrarTabla';
$route['getSubPro2'] = 'cf_reportes_v/C_seguimiento_pdo_2/getHTMLChoiceSubProy';

$route['segpdo3']     = 'cf_reportes_v/C_seguimiento_pdo_3';
$route['getDetItem3'] = 'cf_reportes_v/C_seguimiento_pdo_3/getDetalle';
$route['getDatSeg3']  = 'cf_reportes_v/C_seguimiento_pdo_3/filtrarTabla';
$route['getSubPro3']  = 'cf_reportes_v/C_seguimiento_pdo_3/getHTMLChoiceSubProy';

$route['getInfoUsua']       = 'cf_mantenimiento/C_usuario/getInforUsuarioById';
$route['actualizarUsuario'] = 'cf_mantenimiento/C_usuario/updateUser';

$route['mcentral']    = 'cf_mantenimiento/C_central';
$route['validCod']    = 'cf_mantenimiento/C_central/existeCodigoCentral';
$route['addCentral']  = 'cf_mantenimiento/C_central/createCentral';
$route['getInfoCen']  = 'cf_mantenimiento/C_central/getInfoCentral';
$route['editCentral'] = 'cf_mantenimiento/C_central/editarCentral';

$route['updatefech'] = 'cf_plan_obra/C_carga_update_fech';
$route['updf1']      = 'cf_plan_obra/C_carga_update_fech/uploadFech';
$route['updf2']      = 'cf_plan_obra/C_carga_update_fech/uploadFech2';
$route['updf3']      = 'cf_plan_obra/C_carga_update_fech/uploadFech3';

$route['getItemPlanEdit'] = 'cf_mantenimiento/C_gestionar_po/filtrarTabla';
$route['getInfoItem'] 	  = 'cf_mantenimiento/C_gestionar_po/getInfoItemPlan';
$route['cestplan']        = 'cf_mantenimiento/C_gestionar_po/changueEstadoPlan';
$route['gestpo']    	  = 'cf_mantenimiento/C_gestionar_po';
$route['getEsPor']  	  = 'cf_mantenimiento/C_gestionar_po/getPorcentajeEstacion';
$route['savePorc']  	  = 'cf_mantenimiento/C_gestionar_po/savePorcentajeEstacion';
$route['getDetPor'] 	  = 'cf_reportes_v/C_seguimiento_pdo_3/getPorcentajeEstacion';

$route['rebapro']   = 'cf_reportes_v/C_reporte_bandeja_aprob';
$route['getDatRba'] = 'cf_reportes_v/C_reporte_bandeja_aprob/filtrarTabla';
$route['getDetBa']  = 'cf_reportes_v/C_reporte_bandeja_aprob/getDetalleBA';

//mantenimiento proyecto
$route['mproyecto']  = 'cf_mantenimiento/C_proyecto';
$route['addPro']     = 'cf_mantenimiento/C_proyecto/addProyecto';
$route['getInfoPro'] = 'cf_mantenimiento/C_proyecto/getInfoProyecto';
$route['updatePro']  = 'cf_mantenimiento/C_proyecto/updateProyecto';
$route['addSubPro']  = 'cf_mantenimiento/C_proyecto/addSubProyecto';
$route['getCmbComplejidad'] = 'cf_mantenimiento/C_proyecto/getComboComplejidad';

$route['segava']     = 'cf_reportes_v/C_seguimiento_avance';
$route['getDatSegA'] = 'cf_reportes_v/C_seguimiento_avance/filtrarTabla';
$route['preAprobdi'] = 'cf_liquidacion/C_bandeja_pre_aprob_diseno';
$route['updtTo01di'] = 'cf_liquidacion/C_bandeja_pre_aprob_diseno/updateTo01';
$route['getDataTablePredi'] = 'cf_liquidacion/C_bandeja_pre_aprob_diseno/filtrarTabla';

$route['preCerDi']           = 'cf_liquidacion/C_bandeja_pre_certifica_diseno';
$route['getPtrByItmdi']      = 'cf_liquidacion/C_bandeja_pre_certifica_diseno/getPtrsByItemPlan';
$route['getDataTableExpedi'] = 'cf_liquidacion/C_bandeja_pre_certifica_diseno/filtrarTabla';
$route['chqPtrdi']           = 'cf_liquidacion/C_bandeja_pre_certifica_diseno/checkPtrItem';

$route['generarExcelDetallePlan'] = 'cf_tranferencias/C_tranferencia_wu/crearCSVDetallePlan';
$route['generarExcelDetallePlan2'] = 'cf_tranferencias/C_tranferencia_wu/crearCSVDetallePlan2';


// liquidador masivo cristobal

$route['changeEjec'] = 'cf_plan_obra/C_changeEjec';
$route['updaliqu1']  = 'cf_plan_obra/C_changeEjec/uploadliqui';
$route['updaliqu2']  = 'cf_plan_obra/C_changeEjec/uploadliqui1';
$route['updaliqu3']  = 'cf_plan_obra/C_changeEjec/uploadliqui2';


// ROUTE ESTACION ///  AREA

$route['EstacionArea']     = 'cf_areaEstacion/C_areaEstacion';
$route['validCodestacion'] = 'cf_areaEstacion/C_areaEstacion/existeCodigoEstacion'; //validCod
$route['validCodArea']     = 'cf_areaEstacion/C_areaEstacion/existeCodigoArea'; //validCod
$route['AddEstacion'] 	   = 'cf_areaEstacion/C_areaEstacion/createEstacion';//addCentral
$route['AddArea'] 	   = 'cf_areaEstacion/C_areaEstacion/createArea';//addCentral
$route['AddEstacionArea']  = 'cf_areaEstacion/C_areaEstacion/createEstacionArea';//addCentral
$route['editEstacion'] 	   = 'cf_areaEstacion/C_areaEstacion/editEstacion';//editCentral
$route['editEstacionArea'] = 'cf_areaEstacion/C_areaEstacion/editEstacionArea';//editCentral
$route['editArea'] 	   = 'cf_areaEstacion/C_areaEstacion/editArea';//editCentral


    //INFORMACION DEL EDITAR CRISTOBAL
$route['getInfoEsta']         = 'cf_areaEstacion/C_areaEstacion/getInfoEstacion';//getInfoCen
$route['getInfoArea']         = 'cf_areaEstacion/C_areaEstacion/getInfoArea';//getInfoCen
$route['getInfoEstacionArea'] = 'cf_areaEstacion/C_areaEstacion/getInfoEstacionArea';//getInfoCen


//subproyecto
$route['getInfSp'] 		 = 'cf_mantenimiento/C_proyecto/getInfoSubProyecto';
$route['updSp'] 	         = 'cf_mantenimiento/C_proyecto/updateSubProyecto';
$route['getSubproActividades']   = 'cf_mantenimiento/C_proyecto/getSubproActividades';
$route['addActiviadSubproyecto'] = 'cf_mantenimiento/C_proyecto/addActiviadSubproyecto';
$route['getInfSpAct']            = 'cf_mantenimiento/C_proyecto/getInfoSubProyectoActividad';
$route['updateProAct']           = 'cf_mantenimiento/C_proyecto/updateProyectoActividad';



 //////////////
$route['getItemPlanEdit2'] = 'cf_mantenimiento/C_gestionar_po_2/filtrarTabla';
$route['getInfoItem2']     = 'cf_mantenimiento/C_gestionar_po_2/getInfoItemPlan';
$route['cestplan2']        = 'cf_mantenimiento/C_gestionar_po_2/changueEstadoPlan';
$route['gestpo2']          = 'cf_mantenimiento/C_gestionar_po_2';
$route['getEsPor2']        = 'cf_mantenimiento/C_gestionar_po_2/getPorcentajeEstacion';
$route['savePorc2']        = 'cf_mantenimiento/C_gestionar_po_2/savePorcentajeEstacion';

//////////////////////////////////////////
$route['insertEvi']   = 'cf_mantenimiento/C_gestionar_po_2/insertEvidenciaByItemplan';
$route['zipEvi']      = 'cf_mantenimiento/C_gestionar_po_2/zipTempFiles';
$route['putItemplan'] = 'cf_mantenimiento/C_gestionar_po_2/saveItemplan'; 
 
//////////////// PLANTA INTERNA CRISTOBAL/////////////////////////////////

$route['plantaInterna']  = 'cf_plantaInterna/C_plantaInterna';
$route['getActividades'] = 'cf_plantaInterna/C_plantaInterna/getActividades';
$route['savePTRPI']      = 'cf_plantaInterna/C_plantaInterna/guardarPTR';

// INCIO DETALLE PLAN PLANTA INTERNA  //
$route['detallePI']     = 'cf_plantaInterna/C_detalle_planta_interna';
$route['ptrToEditqs']   = 'cf_plantaInterna/C_detalle_planta_interna/recogeEditarPi';
$route['ptrToInsert12'] = 'cf_plantaInterna/C_detalle_planta_interna/recogeInsertaPi';
$route['ptrInfo12']     = 'cf_plantaInterna/C_detalle_planta_interna/infoWebPi';

//BANDEJA DE APROBACION.  //
$route['aprobInterna']        = 'cf_plantaInterna/C_aprobacion_interna';
$route['asigGrafoInterna']    = 'cf_plantaInterna/C_aprobacion_interna/asignarGrafoInterna';
$route['getDataTableInterna'] = 'cf_plantaInterna/C_aprobacion_interna/filtrarTablaInterna';

//EDITAR PLANTA INTERNA
$route['editPtrPI']   = 'cf_plantaInterna/C_edit_ptr_planta_interna';
$route['updatePTRPI'] = 'cf_plantaInterna/C_edit_ptr_planta_interna/guardarPTR';

// BANDEJA DE PENDIENTE y REFORMULADAS
$route['bandPendientes']   = 'cf_plantaInterna/C_bandeja_pendientes';
$route['bandReformuladas'] = 'cf_plantaInterna/C_bandeja_reformuladas';


/////////////////////////////////////////////////////////////////////// PRE APROB 2
$route['preAproMo2'] 	    = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2';
$route['getPtrByItm2'] 	    = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/getPtrsByItemPlan';
$route['cancelCert2']	    = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/cancelCertificado';
$route['saveCerti2'] 	    = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/saveCertificado';
$route['aprobCert2']        = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/aprobCertiFicado';
$route['getDataTableExpe2'] = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/filtrarTabla';
$route['chqPtr2']           = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/checkPtrItem';
/////////////////////////////////////////////////////////////////////// FIN PRE APROB 2

$route['saveLogSinfix']  	= 'cf_liquidacion/C_liquidacion/saveLogConexionSinfix';
$route['generarCSVDetallePlan'] = 'cf_tranferencias/C_tranferencia_wu/crearCSVDetallePlan';
//$route['preAproMoCon']   	= 'cf_liquidacion/C_bandeja_pre_aprob_mo_2_consulta';
$route['getPtrByItmCon'] 	= 'cf_liquidacion/C_bandeja_pre_aprob_mo_2_consulta/getPtrsByItemPlan';


///////////13-11-2018/////////////////////////////////////////////////////
$route['preAproMoCon']  = 'cf_liquidacion/C_consulta_bandeja_pre_certi_2';
$route['getDatTableExpe2'] = 'cf_liquidacion/C_consulta_bandeja_pre_certi_2/filtrarTabla';
$route['getPtrByItemplan2'] = 'cf_liquidacion/C_consulta_bandeja_pre_certi_2/getPtrsByItemPlan';


/*********************rutas MIGUEL 01052018********************************************/

//  ROUTE REGISTRO INDIVIDUAL PLAN OBRA
$route['regindpo']    = 'cf_plan_obra/C_planobra';
$route['addPlanobra'] = 'cf_plan_obra/C_planobra/createPlanobra';
$route['getInfoPlan'] = 'cf_plan_obra/C_planobra/getInfoPlan';
$route['getSubProPO'] = 'cf_plan_obra/C_planobra/getHTMLChoiceSubProy';
$route['getZonalPO']  = 'cf_plan_obra/C_planobra/getHTMLChoiceZonal';
$route['getEECCPO']   = 'cf_plan_obra/C_planobra/getHTMLChoiceEECC';
/*************miguel rios 09052018******/
$route['getFechaSubproOP'] = 'cf_plan_obra/C_planobra/getFechaPreEjecuCalculo';

$route['pruebaInsertIP'] = 'cf_plan_obra/C_planobra/createPlanObraFromSisego';


//  ROUTE REGISTRO PERMISO POR PERFIL
$route['mpermiperfil'] 	       = 'cf_mantenimiento/C_permisos_perfil';
$route['addPermisoPerfil']     = 'cf_mantenimiento/C_permisos_perfil/createPermisoPerfil';
$route['editPermisoPerfil']    = 'cf_mantenimiento/C_permisos_perfil/editPermisoPerfil';
$route['getInfoPermisoPerfil'] = 'cf_mantenimiento/C_permisos_perfil/getInfoPermisoPerfil';
$route['delPermisoPerfil']    = 'cf_mantenimiento/C_permisos_perfil/delPermisoPerfil';
$route['validaPermisoPerfil']    = 'cf_mantenimiento/C_permisos_perfil/validaPermisoPerfil';





/*******************************MIGUEL RIOS 05052018*******************************/
// ROUTE BANDEJA ITEMPLAN PTR PRIMERA APORBACION MAT_FO Y MAT_COAX 
$route['iplanptrfaprob']    = 'cf_reportes_v/C_reporte_iplan_ptr_primera_aprob';
$route['getItemPTRSubPro']  = 'cf_reportes_v/C_reporte_iplan_ptr_primera_aprob/getHTMLChoiceSubProy';
$route['getItemPTRFiltroT'] = 'cf_reportes_v/C_reporte_iplan_ptr_primera_aprob/filtrarTabla';


//SINFIX!//
$route["ejecucion"]	      = "cf_ejecucion/C_pendientes";
$route["situacion"]           = "cf_ejecucion/C_situacion";
$route["porcentaje"]	      = "cf_ejecucion/C_porcentaje";
$route["ajax"]		      = "cf_ejecucion/C_ajax";
$route["ejecucion_cuadrilla"] = "cf_ejecucion/C_ejecucion_cuadrilla";
$route["detalle_obra"]        = "cf_ejecucion/C_detalle_obra";
$route["agenda_mapa"]         = "cf_ejecucion/C_agenda_mapa";
$route["obra_terminar"]       = "cf_ejecucion/C_obra_terminar";

///////////////////////////////////////////////////////////////
$route['rftec']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica';
$route['getBandejaFT'] = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/filtrarTabla';
$route['getInfIte']    = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/getInfoItemFichaTecnica';
$route['saveFT']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/registrarFichaTecnica';
$route['makePDF']      = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/makePDF';

////////////////////CF_PRE_DISENO///////////////////////////
$route['comprimirFiles'] = 'cf_pre_diseno/C_bandeja_adjudicacion/comprimirFiles';
$route['insertFile']     = 'cf_pre_diseno/C_bandeja_adjudicacion/insertFile';
$route['insertFile2']     = 'cf_pre_diseno/C_bandeja_adjudicacion/insertFile2';
$route['bAdju']          = 'cf_pre_diseno/C_bandeja_adjudicacion';
$route['filBanAdju'] 	 = 'cf_pre_diseno/C_bandeja_adjudicacion/filtrarTabla';
$route['getInfItem'] 	 = 'cf_pre_diseno/C_bandeja_adjudicacion/getInfoByItemplan';
$route['adjuItem'] 	 = 'cf_pre_diseno/C_bandeja_adjudicacion/adjudicarItemplan';
$route['bEjec'] 	 = 'cf_pre_diseno/C_bandeja_ejecucion';
$route['filBanEjec']     = 'cf_pre_diseno/C_bandeja_ejecucion/filtrarTabla';
$route['ejecDiseno']     = 'cf_pre_diseno/C_bandeja_ejecucion/ejecutarDiseno';

////////////////////TORO///////////////////////////

//TORO//

$route["listar_toro"]="cf_toro/C_toro";
$route["crear_toro"]="cf_toro/C_toroCrear";
$route["editar_toro"]="cf_toro/C_toroEditar";
$route["detalle_toro"]="cf_toro/C_toroDetalle";
$route["crear_detalle_toro"]="cf_toro/C_toroDetalleCrear";
$route["listar_pep"]="cf_toro/C_toroListarPep";
$route["extractor_toro"]="cf_toro/C_toroExtractor";
$route["editar_detalle_toro"]="cf_toro/C_toroEditarPep";
$route["nuevo_detalle_toro"]="cf_toro/C_toroNuevaPep";
$route["reporte_toro"]="cf_toro/C_toroListarPepR";
$route["delPToro"]="cf_toro/C_toroListarPep/DeleteToroTemp";
$route["pepToroFil"]="cf_toro/C_toroListarPep/filtrarTabla";

$route['filTabla']  = "cf_toro/C_toroListarPepR/filtrarTabla";
$route['changeProy']  = "cf_toro/C_toroListarPepR/getHTMLChoiceSubProyectos";

$route['estatusP']  = "cf_toro/C_estatus_presupuesto";

///////////////////
$route['validarAprobarDiseno'] = 'cf_pre_diseno/C_bandeja_ejecucion/validarAprobarDiseno';
$route['filtrarSubProyecto']    = 'cf_pre_diseno/C_bandeja_adjudicacion/filtrarSubProyecto';
$route['filtrarSubProyectoEje'] = 'cf_pre_diseno/C_bandeja_ejecucion/filtrarSubProyecto';



///////////CRECIMIENTO VERTICAL////////22.05.2018 CZAVALACAS
$route['precv'] = 'cf_crecimiento_vertical/C_crecimiento_vertical';
$route['saveCV']    = 'cf_crecimiento_vertical/C_crecimiento_vertical/saveItemCV';
$route['bacv']      = 'cf_crecimiento_vertical/C_bandeja_aprob_cv';
$route['exiCons']   = 'cf_crecimiento_vertical/C_crecimiento_vertical/existeConstructora';
$route['aprobCV']   = 'cf_crecimiento_vertical/C_bandeja_aprob_cv/aprobarCV';

$route['rftec2']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix';
$route['saveFT2']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/registrarFichaTecnica';
$route['evalFT']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/getFichaToEvaluacion';
$route['saveVali']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/saveValidacionFicha';
$route['viewFE']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/viewFichaEvaluacion';
$route['getIFTS']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/getInfoItemFichaTecnica';

/////////////////////////////////////////////
$route["insertFoto"]         = "cf_ejecucion/C_porcentaje/insertFoto"; 
$route["subirFoto"]          = "cf_ejecucion/C_porcentaje/subirFoto"; 
$route["ingresarCoordenada"] = "cf_ejecucion/C_porcentaje/ingresarCoordenada";
$route['getFotosEvi']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/getFotosEvi';
$route["ingresarSerieTroba"]        = "cf_ejecucion/C_porcentaje/ingresarSerieTroba"; 
$route["openModalSeleccionarSerie"] = "cf_ejecucion/C_porcentaje/openModalSeleccionarSerie";  

$route["cambiarEstadoItemPlan"] = "cf_ejecucion/C_pendientes/cambiarEstadoItemPlan";
$route['getComboTipoObra'] = 'cf_plan_obra/C_planobra/getComboTipoObra';
$route['getComboCodigo'] = 'cf_plan_obra/C_planobra/getComboCodigo';
/////////////////////////////////// edit crecimiento vertical

$route['editCV']       = 'cf_crecimiento_vertical/C_edit_crecimiento_vertical';
$route['updateCV']    = 'cf_crecimiento_vertical/C_edit_crecimiento_vertical/saveItemCV';

////////////////////////////////// edit cv

$route['banEditCV']       = 'cf_crecimiento_vertical/C_bandeja_edit_cv';
$route['filTabResi'] = 'cf_crecimiento_vertical/C_bandeja_edit_cv/filtrarTabla';

///////////////////////////////////////////////////
$route['gfte']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/getFichaToEdit';



/**************************MIGUEL RIOS VALE DE RESERVA 31052018 **************************/
/******************************correccion***************************************/
$route['VRupWU'] = 'cf_valereserva/C_valereserva';
$route['dwnFileVR'] = 'cf_valereserva/C_valereserva/dwnFileVR';
$route['uploadVR'] = 'cf_valereserva/C_valereserva/uploadVR';
$route['upWUExtrVR'] = 'cf_valereserva/C_valereserva/upWUExtrVR';
/******************************carga***************************************/
$route['VRLoad'] = 'cf_valereserva/C_valereserva_load';
$route['dwnFileVRSAP'] = 'cf_valereserva/C_valereserva_load/dwnFileVRSAP';
$route['uploadVRSAP'] = 'cf_valereserva/C_valereserva_load/uploadVRSAP';
/****************************MIGUEL RIOS BUENDIA 11062018********************/
$route['creaRepVRWUMAT'] = 'cf_valereserva/C_valereserva_load/creaRepVRWUMAT';
/******************************reporte***************************************/
$route['VRReporte'] = 'cf_valereserva/C_valereserva_reporte';
$route['getVRWUMAT'] = 'cf_valereserva/C_valereserva_reporte/filtrarTabla';

/**************************************************************************************************************/

/////////////////////////////CZAVALACAS 6.6.2018 /////////////////////
$route['banTerFT']        = 'cf_ficha_tecnica/C_termino_ficha_tecnica';
$route['filtTermF']        = 'cf_ficha_tecnica/C_termino_ficha_tecnica/filtrarTabla';
$route['viewTFE']        = 'cf_ficha_tecnica/C_termino_ficha_tecnica/viewFichaEvaluacion';
$route['saveAudi']       = 'cf_ficha_tecnica/C_termino_ficha_tecnica/saveEvaluacionAudi';
$route['makePDFA']       = 'cf_ficha_tecnica/C_termino_ficha_tecnica/makePDFTermino';
/////////////////////////////////////////////////////////////////////////////// ft2//////////////////
$route['makePDFE']        = 'cf_ficha_tecnica/C_termino_ficha_tecnica/makePDFEvaluacion';
$route['saveFTFO']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/registrarFichaTecnicaFO';
$route['upfre']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/uploadFileReflectometricas';
$route['makePDFFO']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/makePDFFO';
$route['viewFEFO']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/viewFichaEvaluacionFO';
$route['evalFTFO']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/getFichaToEvaluacionFO';
$route['gfteFO']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/getFichaToEditFO';
$route['viewFESI']        = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/viewFichaEvaluacionSI';
/****************************************************************************/
$route['generarExcelCrecVertical'] = 'cf_extractor/C_extractor/generarExcelCrecVertical';

/************************eliminacion pep2 grafo 12062018**********************/
$route['getGrafoPep2'] = 'cf_mantenimiento/C_subproy_pep_grafo/getHTMLGrafoPep2';
$route['eliminaPep2Grafo'] = 'cf_mantenimiento/C_subproy_pep_grafo/eliminaPep2Grafo';
/************************agregar/modificar pep y monto 12062018***************/
$route['addupdatePEPMonto'] = 'cf_mantenimiento/C_subproy_pep_grafo/addupdatePEPMonto';

/********************************************************************************************/
$route['insertFileEjec']     = 'cf_pre_diseno/C_bandeja_ejecucion/insertFileEjec';
$route['comprimirFilesEjec'] = 'cf_pre_diseno/C_bandeja_ejecucion/comprimirFilesEjec';
$route['editEjecuDi']        = 'cf_pre_diseno/C_bandeja_ejecucion/editEjecuDi';
$route['getInEjec']          = 'cf_pre_diseno/C_bandeja_ejecucion/getInfoByItemplanEjec';


/***nuevo 15082018**/
$route['getInLic']  = 'cf_pre_diseno/C_bandeja_ejecucion/getInfoByItemplanLicencia';
$route['getInLicPqt']  = 'cf_pre_diseno/C_bandeja_ejecucion/getInfoByItemplanLicenciaPqt';
$route['getEntidadesLic'] = 'cf_ejecucion/C_licencias/getEntidadesLicencia';


$route["ejecutarPorcentaje"] = "cf_ejecucion/C_porcentaje/ejecutarPorcentaje";
$route["getEstacionesHtml"] = "cf_ejecucion/C_pendientes/getEstacionesHtml";

$route["getFormPorcentaje"] = "cf_ejecucion/C_porcentaje/getFormPorcentaje";
$route["getEstacionesFoto"] = "cf_ejecucion/C_porcentaje/getEstacionesFoto";

/******************MIGUEL RIOS 26062018************************/
$route['excelplanD'] = 'cf_extractor/C_extractor/generar_excelPDiseno';


/***********CONEXION SISEGOS**************/
$route['cisisego'] = 'cf_plan_obra/C_planobra/createPlanObraFromSisego';
$route['insipep']  = 'cf_mantenimiento/C_subproy_pep_grafo/insertSisePep2GrafoFromSisego';
$route['saveLogSigo']  = 'cf_liquidacion/C_liquidacion/saveLogConexionSigoPlus';
/********************************************/
$route['liquidise']    = 'cf_pre_diseno/C_consulta_diseno';
$route['liquidisefil']    = 'cf_pre_diseno/C_consulta_diseno/filtrarTabla'; 
$route['updatePlanDi']    = 'cf_pre_diseno/C_consulta_diseno/updateEstadoPlanDisenio'; 


$route['infoSisego'] = "cf_plan_obra/C_info_sisego_planobra";

/****************************EDITRA PLAN OBRA lite****************/
$route['editPOlite'] = 'cf_plan_obra/C_editar_planobra';
$route['getInfoItemPlanEditlite'] = 'cf_plan_obra/C_editar_planobra/getInfoItemPlanEditlite';
$route['editPlanobralite'] = 'cf_plan_obra/C_editar_planobra/editPlanobralite';
$route['getConsultaEditItemPlan'] = 'cf_plan_obra/C_editar_planobra/filtrarTabla';
$route['getHTMLZonalEditlite']  = 'cf_plan_obra/C_editar_planobra/getHTMLZonalEditlite';
$route['getHTMLEECCEditlite']  = 'cf_plan_obra/C_editar_planobra/getHTMLEECCEditlite';

$route['editPOlite2'] = 'cf_plan_obra/C_editar_planobra_fase';

$route['saveSisegoPlanObra'] = 'cf_plan_obra/C_planobra/saveSisegoPlanObra';

$route['zipItemPlan'] = "cf_ejecucion/C_pendientes/zipItemPlan";

$route['sendSisegoTrama3'] = "cf_mantenimiento/C_subproy_pep_grafo/sendVRTosisego";

$route['mCentral']           = 'cf_mantenimiento/C_adm_cuadrilla';
$route['getCmbsCuadrillas']  = 'cf_mantenimiento/C_adm_cuadrilla/getCmbsCuadrillas';
$route['registrarCuadrilla'] = 'cf_mantenimiento/C_adm_cuadrilla/registrarCuadrilla';
$route['getTablaCuadrilla']  = 'cf_mantenimiento/C_adm_cuadrilla/getTablaCuadrilla';


$route['indexReporteSinfix']    = 'cf_reporte_gerente/C_reporte_sinfix';
$route['getTablaReporte']    = 'cf_reporte_gerente/C_reporte_sinfix/getTablaReporte';


/******************16072018**********************/
$route['generarExcelPTRNoAprob'] = 'cf_extractor/C_extractor/generarExcelPTRNoAprob';



/******VISUALIZAR LOG BD 19072018**************************************/
$route['ManLog'] = 'cf_mantenimiento/C_manLog';
$route['getDataTableItemPlanLog'] = 'cf_mantenimiento/C_manLog/getDataTableItemPlanLog';


$route["getContMateriales"]   = "cf_ejecucion/C_porcentaje/makeHTMLToKitMateriales"; 
$route["saveKitMate"]         = "cf_ejecucion/C_porcentaje/saveKitDeMaterial";



/******gestion de requerimientos 23072018************************************/
$route['nsolgesreq'] = 'cf_gestion_req/C_nueva_solictudreq';
$route['bandejaSopReq'] = 'cf_gestion_req/C_bandeja_req';


$route['getHTMLLoadAccion'] = 'cf_gestion_req/C_nueva_solictudreq/getHTMLLoadAccion';
$route['enviarSolicitudReq'] = 'cf_gestion_req/C_nueva_solictudreq/enviarSolicitudReq';
$route['recepcionarSoliReq'] = 'cf_gestion_req/C_bandeja_req/recepcionarSoliReq';
$route['atenderSoliReq'] = 'cf_gestion_req/C_bandeja_req/atenderSoliReq';

$route['getDetalleDataEmpreColab'] = 'cf_reporte_gerente/C_reporte_sinfix/getDetalleDataEmpreColab';

$route['consultas'] = 'cf_consultas/C_consultas';
$route['getConsultaFormulario'] = 'cf_consultas/C_consultas/getConsultaFormulario';

$route['getDetalleFormulario'] = 'cf_consultas/C_consultas/getDetalleFormulario';

$route['cambiarEstadoObra']  = 'cf_ejecucion/C_pendientes/cambiarEstadoObra';


/****************************************************************************/
/************************************************************************************************/
$route['saveFT3']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/registrarFichaTecnicaSisego';
$route['makePDFSI']     = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/makePDFSI';
$route['evalFTSI']      = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/getFichaToEvaluacionSisego';


/******************************************************************************/
$route["repFicha"]      = "cf_reportes_v/C_seguimiento_ficha_tecnica";
$route['filSegFic']     = "cf_reportes_v/C_seguimiento_ficha_tecnica/filtrarTabla";


$route['actualizarDetalleForm'] = 'cf_consultas/C_consultas/actualizarDetalleForm';
$route['getArrayComboCodigo'] = 'cf_consultas/C_consultas/getArrayComboCodigo';


$route["registrarFichaSinfix"] = 'cf_ejecucion/C_porcentaje/registrarFichaSinfix';

$route['getDataMaterial']     = 'cf_consultas/C_consultas/getDataMaterial';
$route['getMaterialDetalle']  = 'cf_consultas/C_consultas/getMaterialDetalle';
$route['getDataMaterialRadioButton'] = 'cf_consultas/C_consultas/getDataMaterialRadioButton';
$route['updateFichaTecnica']         = 'cf_consultas/C_consultas/updateFichaTecnica';

/**********  ROUTE REGISTRO INDIVIDUAL PLAN OBRA - PLANTA INTERNA  ****/
$route['regindPI']    = 'cf_plan_obra/C_planobra_pi';
$route['addPlanobraPI'] = 'cf_plan_obra/C_planobra_pi/createPlanobraPI';
$route['getSubProPI'] = 'cf_plan_obra/C_planobra_pi/getHTMLChoiceSubProyPI';
$route['getZonalPI']  = 'cf_plan_obra/C_planobra_pi/getHTMLChoiceZonalPI';
$route['getEECCPI']   = 'cf_plan_obra/C_planobra_pi/getHTMLChoiceEECCPI';
$route['getFechaSubproPI'] = 'cf_plan_obra/C_planobra_pi/getFechaPreEjecuCalculoPI';
$route['getItemPlanSearch']    = 'cf_plan_obra/C_planobra_pi/getItemPlanSearch';


/*******************log en consulta*****************************/

$route['mostrarLogIPConsulta'] = 'cf_plan_obra/C_consulta/mostrarLogItemPlanConsulta';
$route['getMotivoCancelConsulta'] = 'cf_plan_obra/C_consulta/getMotivoCancelConsulta';
$route['getMotivoTruncoConsulta'] = 'cf_plan_obra/C_consulta/getMotivoTruncoConsulta';




$route['getCmbMotivo']  = 'cf_ejecucion/C_pendientes/getCmbMotivo';

$route['makePDFCV']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/makePDFCV';

$route["registrarFormObraPub"] = "cf_ejecucion/C_porcentaje/registrarFormObraPub";

$route['consultasObp']  = 'cf_consultas/C_consulta_obras_publicas';
$route['getDataObp']    = 'cf_consultas/C_consulta_obras_publicas/getDataObp';

$route['getDataUpdate']  = 'cf_consultas/C_consulta_obras_publicas/getDataUpdate';

$route['updateData']  = 'cf_consultas/C_consulta_obras_publicas/updateData';
$route['getSubProyectoFiltro'] = "cf_ejecucion/C_pendientes/getSubProyectoFiltro";

$route['removeZip']            = "cf_ejecucion/C_pendientes/removeZip";

/*************************ayuda******************************/
$route['helpCartilla']    = 'cf_help/C_help';

/**********************************************************************/
$route['makePDFESI']     = 'cf_ficha_tecnica/C_termino_ficha_tecnica/makePDFEvaluacionCIOSI';
$route['viewTFESI']      = 'cf_ficha_tecnica/C_termino_ficha_tecnica/viewFichaEvaluacionSI';
$route['makePDFASI']      = 'cf_ficha_tecnica/C_termino_ficha_tecnica/makePDFTerminoSI';




/****************************************************************************/
/************************************************************************************************/

/*
$route['saveFT3']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/registrarFichaTecnicaSisego';
$route['makePDFSI']     = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/makePDFSI';*/


$route['licencias'] = 'cf_ejecucion/C_licencias';
$route['getTablaItemPlan'] = 'cf_ejecucion/C_licencias/getTablaItemPlan';
$route['getENTS'] = 'cf_ejecucion/C_licencias/getInfoEntidades';
$route['subirEvidenciaItemPlanDetalle'] = 'cf_ejecucion/C_licencias/subirEvidenciaItemPlanDetalle';
$route['subirFotoComprobanteDetalle']  = 'cf_ejecucion/C_licencias/subirFotoComprobanteDetalle';
$route['getComprobantes'] = 'cf_ejecucion/C_licencias/getComprobantesxItemPlanDetalle';
$route['saveComprobanteDetalle'] = 'cf_ejecucion/C_licencias/saveComprobanteDetalle';
$route['updateItemPLanEstaLicenDet'] = 'cf_ejecucion/C_licencias/updateItemPlanEstacionLicenciaDetalle';
$route['crtData'] = 'cf_ejecucion/C_licencias/setUserDataEvidencia';
$route['updateComprobantePreliquidado'] = 'cf_ejecucion/C_licencias/updateComprobantePreliquidado';
$route['insertEntidadxItemPlanEstacion'] = 'cf_pre_diseno/C_bandeja_ejecucion/insertEntidadxItemPlanEstacion';

$route['makePDFOBP']       = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/makePDFOBP';

$route['saveValidacionFichaOBP'] = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica/saveValidacionFichaOBP';

$route['openModalOBP']  = 'cf_ficha_tecnica/C_termino_ficha_tecnica/openModalOBP';

$route['saveAudiOBP']  = 'cf_ficha_tecnica/C_termino_ficha_tecnica/saveAudiOBP';
$route['openPdfCIO']  = 'cf_ficha_tecnica/C_termino_ficha_tecnica/openPdfCIO';

$route['excelCio'] = 'cf_extractor/C_extractor/crearCSVDetallePlanCIO';

/////////////////////////////////28082018//////////////////////////////////
$route['saveAcotacionDetalle'] = 'cf_ejecucion/C_licencias/saveAcotacionDetalle';
$route['updateItemPlanEstaDetByNroCheque'] = 'cf_ejecucion/C_licencias/updateItemPlanEstaDetByNroCheque';
$route['crtDataAcota'] = 'cf_ejecucion/C_licencias/setUserDataEvidenciaAcota';

$route['updateAcotacionPreliquidado'] = 'cf_ejecucion/C_licencias/updateAcotacionPreliquidado';

$route['getRutaEvidenciaItemPlanEsta'] = 'cf_ejecucion/C_licencias/getRutaEvidenciaItemPlanEsta';
$route['getRutaEvidenciaReembolso'] = 'cf_ejecucion/C_licencias/getRutaEvidenciaReembolso';
$route['getRutaEvidenciaAcotacion'] = 'cf_ejecucion/C_licencias/getRutaEvidenciaAcotacion';
$route['getAcotaciones'] = 'cf_ejecucion/C_licencias/getAcotacionesxItemPlanDetalle';
$route['subirFotoAcotaDetalle'] = 'cf_ejecucion/C_licencias/subirFotoAcotaDetalle';
$route['subirFotoAcotacion']  = 'cf_ejecucion/C_licencias/subirFotoAcotaDetalle';
$route['deleteComprobante'] = 'cf_ejecucion/C_licencias/deleteComprobante';
$route['registrarEntidades'] = 'cf_ejecucion/C_licencias/registrarEntidades';





/*******************************MODIFICACION ESTADO po*********************************/
$route['ediPOEstado']    = 'cf_plan_obra/C_editar_estado_planobra';
$route['getConsultaEditEstadoPO']    = 'cf_plan_obra/C_editar_estado_planobra/filtrarTabla';
$route['getInfoItemPlanEditEstado']    = 'cf_plan_obra/C_editar_estado_planobra/getInfoItemPlanEditEstado';
$route['editPlanobraEstado']    = 'cf_plan_obra/C_editar_estado_planobra/editPlanobraEstado';
$route['detalleGant'] = 'cf_detalle_obra/C_detalle_gant';

$route['getCotizacionPtr'] = 'cf_plantaInterna/C_aprobacion_interna/getCotizacionPtr';

$route['saveTask'] = 'cf_detalle_obra/C_detalle_gant/saveTareaGant';
$route['deleteTask'] = 'cf_detalle_obra/C_detalle_gant/deleteTareaGant';
$route['updateTask'] = 'cf_detalle_obra/C_detalle_gant/updateTareaGant';

$route['addLinkTask'] = 'cf_detalle_obra/C_detalle_gant/saveLinkGant';
$route['delLinkTask'] = 'cf_detalle_obra/C_detalle_gant/deleteLinkGant';

$route['bandejaRechazados'] = 'cf_plantaInterna/C_bandeja_rechazadas';

$route["getPtrByItemplan"] = "cf_ejecucion/C_porcentaje/getPtrByItemplan";

$route["getPtrEditar"] = "cf_ejecucion/C_porcentaje/getPtrEditar";

/**************************************************************************/
$route['preCertiCV']   	= 'cf_crecimiento_vertical/C_bandeja_certificacion_cv';
$route['filtrarTab']   	= 'cf_crecimiento_vertical/C_bandeja_certificacion_cv/filtrarTabla';
$route['preCertiCVDet']   = 'cf_crecimiento_vertical/C_bandeja_certificacion_cv_deta';
$route['filtrarTabDet']   = 'cf_crecimiento_vertical/C_bandeja_certificacion_cv_deta/filtrarTabla';

/*************************************26082018*******************/
$route['repCert2']    = 'cf_reportes_v/C_reporte_jefeecc_cert';
$route['getSubProyRepJEECC']    = 'cf_reportes_v/C_reporte_jefeecc_cert/getHTMLChoiceSubProy';
$route['getIPCNoC']    = 'cf_reportes_v/C_reporte_jefeecc_cert/filtrarTabla';
$route['getDetCertIP']    = 'cf_reportes_v/C_reporte_jefeecc_cert/getDetalle';


$route["bandejaValidacion"] = "cf_plantaInterna/C_bandeja_validacion";

$route["bandejaCotizacion"] = "cf_plantaInterna/C_bandeja_cotizacion";

/*****************************************************************************/
$route['repoCerti']    = 'cf_crecimiento_vertical/C_reporte_certificacion_cv';
$route['filtraRepo']    = 'cf_crecimiento_vertical/C_reporte_certificacion_cv/filtrarReporte';
$route['makePDFRCV']      = 'cf_crecimiento_vertical/C_reporte_certificacion_cv/makePDFReporteCerti';

$route['makePDFRICV']      = 'cf_crecimiento_vertical/C_bandeja_certificacion_cv/makePDFCertiItem';

$route['mspglite'] = 'cf_mantenimiento/C_subproy_pep_grafo_lite';


$route["filtrarTablaCotizacion"] = "cf_plantaInterna/C_bandeja_cotizacion/filtrarTablaCotizacion";

$route['filtrarTablaRechazadas'] = 'cf_plantaInterna/C_bandeja_rechazadas/filtrarTablaRechazadas';

$route['drawLine']   = 'cf_toro/C_estatus_presupuesto/makeDataToChartLine';

$route['bandejaCertificacion'] = 'cf_plantaInterna/C_bandeja_certificacion';

$route['filtrarCertificacion'] = 'cf_plantaInterna/C_bandeja_certificacion/filtrarCertificacion';

$route['bandejaCancelacion'] = "cf_ejecucion/C_bandeja_cancelacion";
$route['tramaSolicitudCancelacion']   = "cf_ejecucion/C_bandeja_cancelacion/tramaSolicitudCancelacion";

$route['cancelarItemplan']   = "cf_ejecucion/C_bandeja_cancelacion/cancelarItemplan";

$route['truncarItemplan']   = "cf_ejecucion/C_bandeja_cancelacion/truncarItemplan";

$route['bandejaParalizacion']   = "cf_ejecucion/C_bandeja_paralizacion";

$route["insertParalizacion"]	 = "cf_ejecucion/C_bandeja_paralizacion/insertParalizacion";
$route["insertFileParalizacion"] = "cf_ejecucion/C_pendientes/insertFileParalizacion";

$route["revertirParalizacion"]	 = "cf_ejecucion/C_bandeja_paralizacion/revertirParalizacion";

$route["updateFileParalizacion"]	 = "cf_ejecucion/C_pendientes/updateFileParalizacion";

$route["insertTramaParalizacion"]	 = "cf_ejecucion/C_pendientes/insertTramaParalizacion";
///////////////////////11-09-2018////////////////////////////////////////////


$route['reporteVR']       = 'cf_crecimiento_vertical/C_bandeja_vr';
$route['getReportVRByFilt'] = 'cf_crecimiento_vertical/C_bandeja_VR/getReportVRByFiltros';


$route['getProyectos'] = 'cf_ejecucion/C_licencias/getProyectos';
$route['getSubProyectos'] = 'cf_ejecucion/C_licencias/getSubProyectos';
$route['getRegiones'] = 'cf_ejecucion/C_licencias/getRegiones';
$route['getEmpresasColab'] = 'cf_ejecucion/C_licencias/getEmpresasColab';
$route['getFase'] = 'cf_ejecucion/C_licencias/getFase';
$route['liquidacion_licencias'] = 'cf_ejecucion/C_liquidacion_licencias';
$route['getItemPlanPreLiqui'] = 'cf_ejecucion/C_liquidacion_licencias/getItemPlanPreLiqui';

$route['crtSesionEviLic'] = 'cf_ejecucion/C_liquidacion_licencias/setIdItemPlanEvidencia';
$route['subirEviLicPreliqui'] = 'cf_ejecucion/C_liquidacion_licencias/subirEviLicPreliqui';
$route['getRutaEviLicPreliqui'] = 'cf_ejecucion/C_liquidacion_licencias/getRutaEviLicPreliqui';
$route['updateItemPLanLicPreliqui'] = 'cf_ejecucion/C_liquidacion_licencias/updateItemPLanLicPreliqui';

$route['getEviLicencias'] = 'cf_ejecucion/C_pendientes/getEviLicencias';
$route['getDistritos'] = 'cf_ejecucion/C_licencias/getDistritos';


////////////////////14-09-2018//////////////////////////////////////////
$route['saveComproAdministrativo'] = 'cf_ejecucion/C_licencias/saveComproAdministrativo';
$route['getEntLicPreliqui'] = 'cf_ejecucion/C_liquidacion_licencias/getEntLicPreliqui';
$route['uploadRepVREECCIPMat'] = 'cf_valereserva/C_valereserva_load/uploadRepVREECCIPMat';
$route['makeCSVMat']   	= 'cf_crecimiento_vertical/C_bandeja_certificacion_cv/crearCSVMaterialesCV';


//////////////// 17-09-2018///////////////////////////////////////////
$route['getTablaItemPlanUsuario'] = 'cf_ejecucion/C_licencias/getTablaItemPlanUsuario';

$route['getDetItemsPlan'] = 'cf_crecimiento_vertical/C_bandeja_vr/getDetItemsPlan';

///////////////////////18-09-2018///////////
$route['banCerMO']   = 'cf_liquidacion/C_bandeja_certificacion';
$route['getDetMO']   = 'cf_liquidacion/C_bandeja_certificacion/getTableDetallePTRS';

$route['excelCMO'] = 'cf_liquidacion/C_bandeja_certificacion/makeCSVCertificacionMO';
$route['filCerti'] = 'cf_liquidacion/C_bandeja_certificacion/filtrarTabla';

$route['banAlarm']      = 'cf_liquidacion/C_bandeja_alarmas';
$route['getDetAlar']    = 'cf_liquidacion/C_bandeja_alarmas/getTableDetalleAlarPTRS';
$route['excelAla']      = 'cf_liquidacion/C_bandeja_alarmas/makeCSVCertificacionAlarmMO';
$route['liqHoGes'] = 'cf_liquidacion/C_bandeja_certificacion/liquidarHojaGes';


////////////////////////////////24092018/////////////////////////////////

////////////////////subproyecto partida/////////////////////////////


$route['regActSubPI'] = 'cf_plantaInterna/C_subproyecto_partida_pi';
$route['addSubProyPartida'] = 'cf_plantaInterna/C_subproyecto_partida_pi/ingresarPartida';
$route['editSubProyPartida'] = 'cf_plantaInterna/C_subproyecto_partida_pi/updatePartida';
$route['getInfoPartida'] = 'cf_plantaInterna/C_subproyecto_partida_pi/getInfoPartida';
$route['updatedesacPart'] = 'cf_plantaInterna/C_subproyecto_partida_pi/upddescActPI';
$route['updateactivPart'] = 'cf_plantaInterna/C_subproyecto_partida_pi/updactActPI';
$route['valcodPartida'] = 'cf_plantaInterna/C_subproyecto_partida_pi/existeCodigoPartida';
$route['valNomPartida'] = 'cf_plantaInterna/C_subproyecto_partida_pi/existeNombrePartida';


/////////////////////vale de reserva eecc/////////////////////////////////

 
$route['regVREECC'] = 'cf_mantenimiento/C_valereserva_eecc';
$route['getInfoVREECC'] = 'cf_mantenimiento/C_valereserva_eecc/getInfoValeReservaEECC';
$route['validaVREECC'] = 'cf_mantenimiento/C_valereserva_eecc/validaValeReserva';
$route['addVREECC'] = 'cf_mantenimiento/C_valereserva_eecc/createValeReservaEECC';
$route['delVREECC'] = 'cf_mantenimiento/C_valereserva_eecc/delValeReservaEECC';
$route['editVREECC'] = 'cf_mantenimiento/C_valereserva_eecc/editValeReservaEECC';

$route['indexReporteCV']      = 'cf_reporte_gerente/C_reporte_cv';

$route['getTablaReporteCv']    = 'cf_reporte_gerente/C_reporte_cv/getTablaReporteCv';

//COTIZACION//
$route['reCoti']         = 'cf_cotizacion/C_registrar_cotizacion';
$route['insertEviCoti']  = 'cf_cotizacion/C_registrar_cotizacion/insertEvidenciaByItemplan';
$route['putItemCoti']    = 'cf_cotizacion/C_registrar_cotizacion/saveItemplan';
$route['sendCoti']       = 'cf_cotizacion/C_registrar_cotizacion/enviarCotizacion';
$route['updCentral']     = 'cf_cotizacion/C_registrar_cotizacion/actualizarCentral';
$route['sendToEC']     = 'cf_cotizacion/C_registrar_cotizacion/sendToEECC';
$route['updMonto']     = 'cf_cotizacion/C_registrar_cotizacion/updateMontoCoti';

$route['valCoti']        = 'cf_cotizacion/C_validar_cotizacion';
$route['validCoti']      = 'cf_cotizacion/C_validar_cotizacion/validarCotizacion';


//GESTION ERC

$route['bolsaPresupuesto'] = 'cf_gestionar_erc/C_bolsa_presupuesto';
$route['regBolsa'] = 'cf_gestionar_erc/C_bolsa_presupuesto/registrarBolsaPresupuesto';
$route['updateBolsa'] = 'cf_gestionar_erc/C_bolsa_presupuesto/updateBolsaPresupuesto';

$route['solicitudRetiro'] = 'cf_gestionar_erc/C_solicitud_retiro';
$route['getSoliRetByFiLt'] = 'cf_gestionar_erc/C_solicitud_retiro/filtrarTabla';
$route['searchBolsaPresupuesto'] = 'cf_gestionar_erc/C_solicitud_retiro/searchBolsaPresupuesto';
$route['getItemPlanSoli'] = 'cf_gestionar_erc/C_solicitud_retiro/searchItemPlan';
$route['regSoliRetiro'] = 'cf_gestionar_erc/C_solicitud_retiro/registrarSolicitudRetiro';
$route['apruebaSoliRetiro'] = 'cf_gestionar_erc/C_aprobacion_solicitud_retiro';
$route['updateSoliRetiro'] = 'cf_gestionar_erc/C_aprobacion_solicitud_retiro/updateSolicitudRetiro';
$route['getAllBolsaPresu'] = 'cf_gestionar_erc/C_solicitud_retiro/getAllBolsaPresupuesto';
$route['getResponsables'] = 'cf_gestionar_erc/C_bolsa_presupuesto/getResponsables';
$route['getAprobSoliRetByFiLt'] = 'cf_gestionar_erc/C_aprobacion_solicitud_retiro/filtrarTabla';


$route['liquiRetiro'] = 'cf_gestionar_erc/C_liquidacion_retiro';
$route['getLiquiRetByFilt'] = 'cf_gestionar_erc/C_liquidacion_retiro/filtrarTabla';
$route['liquiRetTemp'] = 'cf_gestionar_erc/C_liquidacion_retiro/setUserDataEvidLiquiRetiro';
$route['regiLiquiRetiro'] = 'cf_gestionar_erc/C_liquidacion_retiro/registrarLiquiRetiro';
$route['uploadEviLiquiRet'] = 'cf_gestionar_erc/C_liquidacion_retiro/uploadEviLiquiRet';
$route['desaprobSoliRetiro'] = 'cf_gestionar_erc/C_aprobacion_solicitud_retiro/desaprobSoliRetiro';
$route['validacionRetiro'] = 'cf_gestionar_erc/C_validacion_retiro';
$route['updateRetBolsa'] = 'cf_gestionar_erc/C_validacion_retiro/updateRetiroBolsa';

$route['getTransa'] = 'cf_gestionar_erc/C_bolsa_presupuesto/getTransacciones';



$route["deleteArchivoFoto"]	= "cf_ejecucion/C_porcentaje/deleteArchivoFoto";
$route["getArrayFiles"]	    = "cf_ejecucion/C_porcentaje/getArrayFiles";


// verificar nombre de proyecto
$route['valNomProyecto']  = 'cf_mantenimiento/C_proyecto/validaNombreSubProyecto';


/////////////EXTRACTOR INTERNO//////////////////////////
$route['extractorInterno'] = 'cf_extractor/C_size_file_planobra';
$route['sizefile']         = 'cf_extractor/C_size_file_planobra/ObtenerPesoRegistros';


///////////////////////////REPORTE 01102018///////////////////////////
$route['repCVJefEECC']         = 'cf_reporte_gerente/C_reporte_cv_jefeecc';
$route['getIPCVRep']  = 'cf_reporte_gerente/C_reporte_cv_jefeecc/filtrarTabla';
$route['getDetCVJefEECC']  = 'cf_reporte_gerente/C_reporte_cv_jefeecc/getDetalle';

$route['getIPCVRepOnLine']    = 'cf_reporte_gerente/C_reporte_cv/getDataCVJefEECC';
$route['getDetCVJEECCOnline']  = 'cf_reporte_gerente/C_reporte_cv/detJefEECCCVOnline';
$route['getDetDataPOCVOnline']  = 'cf_reporte_gerente/C_reporte_cv/getDataPlanObraCVOnline';


$route["verMotivoParalizacion"]	= "cf_ejecucion/C_pendientes/verMotivoParalizacion";

//descertificacion
$route['desCert']         = 'cf_liquidacion/C_bandeja_descertificacion';
$route['unlockPtr']         = 'cf_liquidacion/C_bandeja_descertificacion/liberarPtrs';

$route['estaCerti']         = 'cf_liquidacion/C_estatus_certificacion';
$route['filCerEc']          = 'cf_liquidacion/C_estatus_certificacion/filtrarTabla';
$route['getDetEstCertMO']   = 'cf_liquidacion/C_estatus_certificacion/getTableDetalleAlarPTRS';



////////////////////////////// consulta 03102018////////////////////////////////
$route['getProyConsulta']  = 'cf_plan_obra/C_consulta/getHTMLProyectoConsulta';
$route['getSubProyConsulta']  = 'cf_plan_obra/C_consulta/getHTMLSubProyectoConsulta';

$route['getProyEditIp']  = 'cf_plan_obra/C_editar_planobra/getHTMLProyectoConsulta';
$route['getSubProyEditIp']  = 'cf_plan_obra/C_editar_planobra/getHTMLSubProyectoConsulta';

$route['getProyEditEstIp']  = 'cf_plan_obra/C_editar_estado_planobra/getHTMLProyectoConsulta';
$route['getSubProyEditEstIp']  = 'cf_plan_obra/C_editar_estado_planobra/getHTMLSubProyectoConsulta';

$route['uploadOc']         = 'cf_liquidacion/C_carga_orden_compra';
$route['upfoc']       = 'cf_liquidacion/C_carga_orden_compra/uploadFileOC';
$route['saveDataOC']       = 'cf_liquidacion/C_carga_orden_compra/saveOC';


$route['getSolicitudVR'] = 'cf_liquidacion/C_solicitud_Vr';

$route['getComboPtr'] = 'cf_liquidacion/C_solicitud_Vr/getComboPtr';

$route['insertSap'] = 'cf_liquidacion/C_solicitud_Vr/insertSap';

$route['getBandejaSolicitudVr'] = 'cf_liquidacion/C_bandeja_solicitud_vr';

$route['getModalCheck'] = 'cf_liquidacion/C_bandeja_solicitud_vr/getModalCheck';

$route['ingresarFlgDevolucion'] = 'cf_liquidacion/C_bandeja_solicitud_vr/ingresarFlgDevolucion';

$route['getVR'] = 'cf_liquidacion/C_solicitud_Vr/getVr';

$route['getBandejaAtencionSolicitud'] = 'cf_liquidacion/C_bandeja_atencion_solicitud_vr';

$route['getMaterialModal'] = 'cf_liquidacion/C_bandeja_atencion_solicitud_vr/getMaterialModal';


$route['getMatrizAgendamiento'] = 'cf_agendamiento/C_matriz_agendamiento';


$route['getElementModalCuotas'] = 'cf_agendamiento/C_matriz_agendamiento/getElementModalCuotas';
$route['openModalEditarCuotas'] = 'cf_agendamiento/C_matriz_agendamiento/openModalEditarCuotas';

$route['getAgendamiento'] = 'cf_agendamiento/C_agendamiento';

$route['getDataFormulario'] = 'cf_agendamiento/C_agendamiento/getDataFormulario';


$route['ingresarAgendamiento'] = 'cf_agendamiento/C_agendamiento/ingresarAgendamiento';

$route['getAgendamientosCalendar'] = 'cf_agendamiento/C_agendamiento/getAgendamientosCalendar';

$route['getDetalleAgendamientoByFecha'] = 'cf_agendamiento/C_agendamiento/getDetalleAgendamientoByFecha';

$route['getConfirmarAgendamiento'] = 'cf_agendamiento/C_confirmar_agendamiento';

$route['confirmarAgendamiento'] = 'cf_agendamiento/C_confirmar_agendamiento/confirmarAgendamiento';

$route['registrarCuotas'] = 'cf_agendamiento/C_matriz_agendamiento/registrarCuotas';


$route['getMantenimientoBandaHoraria'] = 'cf_agendamiento/C_mantenimiento_banda_horaria';

$route['registrarBandaHoraria'] = 'cf_agendamiento/C_mantenimiento_banda_horaria/registrarBandaHoraria';

$route['eliminarBandaHoraria'] = 'cf_agendamiento/C_mantenimiento_banda_horaria/eliminarBandaHoraria';


$route['getPanelMatrizAgendamiento'] = 'cf_agendamiento/C_agendamiento/getPanelMatrizAgendamiento';


$route['filtrarBandejaSolicitudVr'] = 'cf_liquidacion/C_bandeja_solicitud_vr/filtrarBandejaSolicitudVr';

$route['filtrarBandejaConsultaSolicitudVr'] = 'cf_liquidacion/C_bandeja_atencion_solicitud_vr/filtrarBandejaConsultaSolicitudVr';

//COTIZACION SINFIX

$route['cotizaciones'] = 'cf_ejecucion/C_cotizaciones';
$route['searchItemPlan'] = 'cf_ejecucion/C_cotizaciones/searchItemPlan';
$route['uploadEviCotizacion'] = 'cf_ejecucion/C_cotizaciones/uploadEviCotizacion';
$route['regCotizacion'] = 'cf_ejecucion/C_cotizaciones/registrarCotizacion';
$route['aprobacionCotizacion'] = 'cf_ejecucion/C_aprobacion_cotizacion';
$route['aprobCoti'] = 'cf_ejecucion/C_aprobacion_cotizacion/updateCotizacion';
$route['consultaCotizacion'] = 'cf_ejecucion/C_consulta_cotizaciones';

$route['solicitudVR'] = 'cf_crecimiento_vertical/C_solicitud_vr';

$route['getDetPTRS'] = 'cf_toro/C_toroListarPepR/getDetallePTRS';

$route['getDetLogCv']   = 'cf_crecimiento_vertical/C_edit_crecimiento_vertical/getDatalleLogMovimientos';
//***SIROPR***//
$route['upSirope']      = 'cf_sirope/C_carga_sirope';
$route['upfSi']         = 'cf_sirope/C_carga_sirope/uploadFileOC';
$route['saveDataSI']    = 'cf_sirope/C_carga_sirope/saveSI';

$route['downAOM']    = 'cf_extractor/C_extractor/crearCSVMovilesAvanceOperativa';


////////////////////////////////EDIT SUBPROYECTO CV//////////////////////////
$route['editSubproCV']   = 'cf_crecimiento_vertical/C_edit_subproyecto_cv';
$route['getItemplanCV']   = 'cf_crecimiento_vertical/C_edit_subproyecto_cv/filtrarTabla';
$route['updateSubproCV'] = 'cf_crecimiento_vertical/C_edit_subproyecto_cv/updateSubProyecto';
$route['editSubProyectMasivo'] = 'cf_crecimiento_vertical/C_edit_subproyecto_masivo';
$route['insertArchivoMasivo'] = 'cf_crecimiento_vertical/C_edit_subproyecto_masivo/insertSap';

$route['bandeAlarmCV'] = 'cf_crecimiento_vertical/C_bandeja_alarma_cv';

////////////////////////////////////////////////////////////////////////////

$route['verificaPTRCV'] = 'cf_crecimiento_vertical/C_edit_subproyecto_cv/verificaPTRCVByItemplan';

$route['makeSiEx']    = 'cf_sirope/C_carga_sirope/generarExcelErrores';

/***************************REPORTE BA-VR*******************************/
$route['repBaVr']    = 'cf_reportes_v/C_reporte_ba_vr';
$route['filBaVr']    = 'cf_reportes_v/C_reporte_ba_vr/filtrarTabla';

/**cancelarItemplan CV**/
$route['cancelCV']    = 'cf_crecimiento_vertical/C_bandeja_edit_cv/cancelarItemplanCV';


$route['getReportePI']  = 'cf_plantaInterna/C_bandeja_reporte';

$route['filtrarReporte']  = 'cf_plantaInterna/C_bandeja_reporte/filtrarReporte';
$route['confirmarCancelacion'] = 'cf_agendamiento/C_confirmar_agendamiento/confirmarCancelacion';

$route['cancelarItemplanPendiente']   = "cf_ejecucion/C_pendientes/cancelarItemplanPendiente";

$route['makeCVCerti'] = 'cf_tranferencias/C_tranferencia_wu/crearCVSCertificacion';
$route['makeVROff'] = 'cf_tranferencias/C_tranferencia_wu/crearCSVItemValeReserva';



$route['uploadPOPEXT1'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT1';
$route['uploadPOPEXT2'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT2';
$route['uploadPOPEXT3'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT3';
$route['uploadPOPEXT4'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT4';
$route['uploadPOPEXT5'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT5';
$route['uploadPOPEXT6'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT6';
$route['uploadPOPEXT7'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT7';
$route['uploadPOPEXT8'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT8';
$route['uploadPOPEXT9'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT9';
$route['uploadPOPEXT10'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT10';
$route['uploadPOPEXT11'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPEXT11';


$route['uploadPOPINT'] = 'cf_tranferencias/C_tranferencia_wu/uploadPOPINT';



$route['actualizarCuotas'] = 'cf_agendamiento/C_matriz_agendamiento/actualizarCuotas';

$route['getDetVR'] = 'cf_plan_obra/C_consulta/getDetalleVRById';

$route['getRepCVByEECC'] = 'cf_crecimiento_vertical/C_bandeja_alarma_cv/getReporteCVByEECC';

$route['getDetIPSCV']   	= 'cf_crecimiento_vertical/C_bandeja_alarma_cv/getDetItemsPlan';


$route['getComproByIdIPEST'] = 'cf_ejecucion/C_pendientes/getReembolsoByIdIPEstDet';


$route['getBandejaSiom']    = 'cf_liquidacion/C_bandeja_siom';
$route['asignarCodigoSiom'] = 'cf_liquidacion/C_bandeja_siom/asignarCodigoSiom';

$route['filtrarTablaSiom'] = 'cf_liquidacion/C_bandeja_siom/filtrarTablaSiom';

$route['getDataSiom'] = 'cf_plan_obra/C_consulta/getDataSiom';

$route['getSwitchSiom']   = 'cf_liquidacion/C_switch_siom';

$route['getDataEditar']   = 'cf_liquidacion/C_switch_siom/getDataEditar';

$route['actualizarSwitch']   = 'cf_liquidacion/C_switch_siom/actualizarSwitch';

$route['openModalRegistro']   = 'cf_liquidacion/C_switch_siom/openModalRegistro';

$route['registrarSwitch']   = 'cf_liquidacion/C_switch_siom/registrarSwitch';

$route['filtrarTablaSwitchSiom']   = 'cf_liquidacion/C_switch_siom/filtrarTablaSwitchSiom';

$route['hasAdju'] = 'cf_plan_obra/C_consulta/hasDisenoAdjudicado';

/*REPORTE CERTIFICACION MO CV*/

$route['repCertMO'] = 'cf_reportes_v/C_reporte_certificacion';
$route['getDetPTRCertCV'] = 'cf_reportes_v/C_reporte_certificacion/getDetPTRsCertCV';
$route['getReportCertByFiltros'] = 'cf_reportes_v/C_reporte_certificacion/filtrarTabla';
$route['getReport2CertMO'] = 'cf_reportes_v/C_reporte_certificacion/getReport2CertMO';
/*REPORTE CERTIFICACION MO CV*/


$route["getAreasBySubPro"]="cf_toro/C_toroNuevaPep/filtrarSubProyecto";

$route['filtrarFasePre']   = 'cf_toro/C_estatus_presupuesto/filtrarFase';
$route['drawLineFil']   = 'cf_toro/C_estatus_presupuesto/filtrarFaseGrafico';

$route['poPrueba1'] = 'cf_tranferencias/C_tranferencia_wu/generar_excelP1';
$route['poPrueba2'] = 'cf_tranferencias/C_tranferencia_wu/generar_excelP2';

$route['getCargaMasivaItemplan'] = 'cf_plan_obra/C_carga_masiva_itemplan';

$route['insertTbTemporal']          = 'cf_plan_obra/C_carga_masiva_itemplan/insertTbTemporal';
$route['getTablaExcelPO']        = 'cf_plan_obra/C_carga_masiva_itemplan/getTablaExcelPO';
$route['insertarPO']             = 'cf_plan_obra/C_carga_masiva_itemplan/insertarPO';
$route['getTablaTabs']           = 'cf_plan_obra/C_carga_masiva_itemplan/getTablaTabs';
$route['insertPODetallePlan']    = 'cf_plan_obra/C_carga_masiva_itemplan/insertPODetallePlan';

$route['getKitPlantaExterna'] = 'cf_plan_obra/C_kit_planta_externa';
$route['getKitMateriales']    = 'cf_plan_obra/C_kit_planta_externa/getKitMateriales';
$route['insertMaterial']      = 'cf_plan_obra/C_kit_planta_externa/insertMaterial';
$route['eliminarMaterial']    = 'cf_plan_obra/C_kit_planta_externa/eliminarMaterial';

$route['generarMasivoPO']    = 'cf_plan_obra/C_carga_masiva_itemplan/generarMasivoPO';


$route['regIndiPO'] = 'cf_detalle_obra/C_registro_individual_po';
$route['cargarArchivoPO'] = 'cf_detalle_obra/C_registro_individual_po/cargarArchivoPO';

$route['registPO'] = 'cf_detalle_obra/C_registro_individual_po/registPO';
$route['deleteMatErroneo'] = 'cf_detalle_obra/C_registro_individual_po/deleteMatErroneo';
$route['getExcelPOMat'] = 'cf_detalle_obra/C_registro_individual_po/getExcelPOMat';

$route['getExcelPOMatAprob'] = 'cf_liquidacion/C_liquidacion/getExcelPOMatAprob';

$route['preCancelPO'] = 'cf_detalle_obra/C_detalle_obra/preCancelarPO';
$route['cancelPO'] = 'cf_liquidacion/C_bandeja_cancelacion_po';
$route['getFiltPOPreCance'] = 'cf_liquidacion/C_bandeja_cancelacion_po/filtrarTabla';
$route['cancelarPO'] = 'cf_liquidacion/C_bandeja_cancelacion_po/cancelarPO';

$route['getCmbMotPreCancela'] = 'cf_detalle_obra/C_detalle_obra/getComboMotivoPreCancela'; 

$route['getCmbEstacion'] = 'cf_plan_obra/C_kit_planta_externa/getCmbEstacion';
$route['csvDetPoMat'] = 'cf_extractor/C_extractor/crearCsvDetPoMaterial';

$route['insertSolicitudKit'] = 'cf_liquidacion/C_solicitud_Vr/insertSolicitudKit';

$route['getAnalisisEconomico'] = 'cf_plan_obra/C_analisis_economico';

$route['getTablaAnalisisEconByItemplan'] = 'cf_plan_obra/C_analisis_economico/getTablaAnalisisEconByItemplan';
$route['sendSigoPO'] = 'C_utils/envioManualTramaSisegosPOMateriales';

$route['generar_excelCvKit'] = 'cf_extractor/C_extractor/generar_excelCvKit';
$route['generar_excelCvSiom'] = 'cf_extractor/C_extractor/generar_excelCvSiom';

$route['manSubProyAutoAProb']  = 'cf_mantenimiento/C_bandeja_subproy_autoaprob';
$route['getSubProyNoConfigPO']  = 'cf_mantenimiento/C_bandeja_subproy_autoaprob/getSubProyectos';
$route['regSubProyPO']  = 'cf_mantenimiento/C_bandeja_subproy_autoaprob/registrarSubProyAutoAprobPO';
$route['deleteSubProyConfigAutoAprobPO']  = 'cf_mantenimiento/C_bandeja_subproy_autoaprob/deleteSubProyConfigAutoAprobPO';


$route['mantMaterial']  = 'cf_mantenimiento/C_bandeja_material';
$route['regMaterial']  = 'cf_mantenimiento/C_bandeja_material/registrarMaterial';
$route['getDetMatEdit']  = 'cf_mantenimiento/C_bandeja_material/getDetalleMaterial';
$route['getCmbUdm']  = 'cf_mantenimiento/C_bandeja_material/getComboUDM';
$route['updateMaterial']  = 'cf_mantenimiento/C_bandeja_material/updateMaterial';



/** MODULO GESTION DE OBRAS PUBLICAS**////

$route['gestionOP'] = 'cf_plan_obra/C_gestion_obra_publica';
$route['saveCROP'] = 'cf_plan_obra/C_gestion_obra_publica/saveCartaCotizacion';
$route['saveCROP2'] = 'cf_plan_obra/C_gestion_obra_publica/saveCartaRespuesta';
$route['saveCROP3'] = 'cf_plan_obra/C_gestion_obra_publica/saveDatosConvenio';
$route['saveKOff'] = 'cf_plan_obra/C_gestion_obra_publica/ejecutarKickOff';
$route['saveCB'] = 'cf_plan_obra/C_gestion_obra_publica/saveCartaBasica';

/**********************************************/
$route['banNoFI'] = 'cf_plan_obra/C_bandeja_sin_fecha_inicio';
$route['updFecIni'] = 'cf_plan_obra/C_bandeja_sin_fecha_inicio/updateFecIniItemplan';

$route['probTramaPara'] = 'cf_gestionar_erc/C_bolsa_presupuesto/probarTramaParalizacion';

/********************CV ft********************************/
$route['banFTCV'] = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_cv';
$route['valFTCV'] = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_cv/evaluarFichatecnicaCV';
$route['reactFT'] = 'cf_ficha_tecnica/C_bandeja_ficha_tecnica_sinfix/reactivarFichaTecnicaCV';

$route['ejecValidacion'] = 'cf_plantaInterna/C_bandeja_validacion/ejecValidacion';

$route['getPreciario'] = 'cf_mantenimiento/C_preciario';

$route['getPreciarioTb'] = 'cf_mantenimiento/C_preciario/getPreciarioTb';

$route['insertZonal'] = 'cf_mantenimiento/C_preciario/insertZonal';

$route['getCmbsPreciario'] = 'cf_mantenimiento/C_preciario/getCmbsPreciario';


/********************LICENCIAS V2********************************/
$route['bandeja_licencias'] = 'cf_ejecucion/C_bandeja_licencias';
$route['getSubProyByProy'] = 'cf_ejecucion/C_bandeja_licencias/getSubProyectos';
$route['getEntLic'] = 'cf_ejecucion/C_bandeja_licencias/getInfoEntidades';
$route['getCmbEntLic'] = 'cf_ejecucion/C_bandeja_licencias/getEntidadesLicencia';
$route['regEntLic'] = 'cf_ejecucion/C_bandeja_licencias/registrarEntidades';
$route['subirEviIPEstDet'] = 'cf_ejecucion/C_bandeja_licencias/updateIPEstDet';
$route['getRutaEviIPEstaDet'] = 'cf_ejecucion/C_bandeja_licencias/getRutaEvidenciaItemPlanEsta';
$route['getComprobantesLic'] = 'cf_ejecucion/C_bandeja_licencias/getComprobantes';
$route['saveUpdateCompro'] = 'cf_ejecucion/C_bandeja_licencias/saveUpdateComprobante';
$route['getRutaEviReembolso'] = 'cf_ejecucion/C_bandeja_licencias/getRutaEvidenciaReembolso';
$route['deleteIPEstDetLic'] = 'cf_ejecucion/C_bandeja_licencias/deleteIPEstDetLic';

$route['liquidacion_obra'] = 'cf_ejecucion/C_liquidacion_obra';
$route['getEntLicPreliqui2'] = 'cf_ejecucion/C_liquidacion_obra/getInfoEntidades';
$route['updateIPLicPreliqui'] = 'cf_ejecucion/C_liquidacion_obra/updateIPEstDet';
$route['getRutaEviIPEstaDetPreliqui'] = 'cf_ejecucion/C_liquidacion_obra/getRutaEvidenciaItemPlanEsta';
/********************LICENCIAS V2********************************/

/*************** REGISTRO PO MO ************************************/
$route['rePoMo']  = 'cf_detalle_obra/C_registro_po_mo';
$route['exParMO'] = 'cf_detalle_obra/C_registro_po_mo/getExcelPartidasMO';
$route['upFiPoMo'] = 'cf_detalle_obra/C_registro_po_mo/uploadPOMO';
$route['saPoMo'] = 'cf_detalle_obra/C_registro_po_mo/savePoMo';

/***************************** EDITAR / LIQUIDAR MO *******************************/
$route['liquiMo']    = 'cf_liquidacion_mo/C_liquidar_mo';
$route['exParMOLi']  = 'cf_liquidacion_mo/C_liquidar_mo/getExcelPartidasMO';
$route['proLiPoMo']  = 'cf_liquidacion_mo/C_liquidar_mo/uploadPOMO';
$route['saPoMoEdi']  = 'cf_liquidacion_mo/C_liquidar_mo/savePoMo';
$route['liquidarPO'] = 'cf_liquidacion_mo/C_liquidar_mo/liquidarPOMO';


/********************REPORTE EXTRACTOR LICENCIAS********************************/
$route['makeCSVLic'] = 'cf_extractor/C_extractor/generarExcelLic';

//MANTENIMIENTO ENTIDAD
$route['mantEntidad'] = 'cf_mantenimiento/C_bandeja_entidad';
$route['regEntidad']  = 'cf_mantenimiento/C_bandeja_entidad/registrarEntidad';
$route['getDetEntEdit']  = 'cf_mantenimiento/C_bandeja_entidad/getDetalleEntidad';
$route['updateEntidad']  = 'cf_mantenimiento/C_bandeja_entidad/updateEntidad';
$route['deleteEntidad']  = 'cf_mantenimiento/C_bandeja_entidad/deleteEntidad';

//MANTENIMIETO PARTIDAS
$route['mantPartidas'] = 'cf_mantenimiento/C_bandeja_partidas';
$route['getCmbPrecDiseno'] = 'cf_mantenimiento/C_bandeja_partidas/getComboPrecDiseno';
$route['regPartida'] = 'cf_mantenimiento/C_bandeja_partidas/registrarPartida';
$route['getDetPartidaEdit']  = 'cf_mantenimiento/C_bandeja_partidas/getDetallePartida';
$route['updatePartida']  = 'cf_mantenimiento/C_bandeja_partidas/updatePartida';
$route['getCmbProyEstPart'] = 'cf_mantenimiento/C_bandeja_partidas/getCombos';
$route['regProyEstPartByPart'] = 'cf_mantenimiento/C_bandeja_partidas/registrarProyEstPart';

//MANTENIMIENTO PROY-ESTA-PARTIDA
$route['mantProyEstPart'] = 'cf_mantenimiento/C_bandeja_proy_est_partida';
$route['getProyEstPartByFiltros'] = 'cf_mantenimiento/C_bandeja_proy_est_partida/filtrarTabla';
$route['getCombosProyEstPart'] = 'cf_mantenimiento/C_bandeja_proy_est_partida/getCombos';
$route['regProyEstPart'] = 'cf_mantenimiento/C_bandeja_proy_est_partida/registrarProyEstPart';
$route['getDetProEstPart'] = 'cf_mantenimiento/C_bandeja_proy_est_partida/getDetProEstPart';
$route['updateProyEstPart'] = 'cf_mantenimiento/C_bandeja_proy_est_partida/updateProyEstPart';
$route['deleteProyEstPart'] = 'cf_mantenimiento/C_bandeja_proy_est_partida/deleteProyEstPart';
$route['getEstByPartProy'] = 'cf_mantenimiento/C_bandeja_proy_est_partida/getEstacionesByPartProy';

/*REPORTE CERTIFICACION MO CV 2*/
$route['repCertMO2'] = 'cf_reportes_v/C_reporte_certificacion_presupuesto';
$route['getDetPTRCertCV2'] = 'cf_reportes_v/C_reporte_certificacion_presupuesto/getDetPTRsCertCV';
$route['getReportCertByFiltros2'] = 'cf_reportes_v/C_reporte_certificacion_presupuesto/filtrarTabla';
$route['getReport2CertMO2'] = 'cf_reportes_v/C_reporte_certificacion_presupuesto/getReport2CertMO';
/*REPORTE CERTIFICACION MO CV 3*/
$route['repCertMO3']        = 'cf_reportes_v/C_reporte_certificacion_presu_subpro';
$route['getDetPTRCertCV3']  = 'cf_reportes_v/C_reporte_certificacion_presu_subpro/getDetPTRsCertCV';
$route['getReportCertByFiltros3'] = 'cf_reportes_v/C_reporte_certificacion_presu_subpro/filtrarTabla';
$route['getReport2CertMO3'] = 'cf_reportes_v/C_reporte_certificacion_presu_subpro/getReport2CertMO';

/***********/
$route['getDetMatPar'] 	    = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/getMaterialesPartidasByItem';
$route['upMOParti'] 	    = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/updateMoPartidas';
$route['upMateri'] 	    = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/updateMateriales';

$route['valExistPep'] 	    = "cf_toro/C_toroNuevaPep/validateExistePep";


//MANTENIMIENTO PRECIARIOS
$route['mantPreciario'] = 'cf_mantenimiento/C_bandeja_preciarios';
$route['getPreciarioByFiltros'] = 'cf_mantenimiento/C_bandeja_preciarios/filtrarTabla';
$route['getCombosPreciario'] = 'cf_mantenimiento/C_bandeja_preciarios/getCombos';
$route['regPreciario'] = 'cf_mantenimiento/C_bandeja_preciarios/registrarPreciario';
$route['getDetPreciario'] = 'cf_mantenimiento/C_bandeja_preciarios/getDetPreciario';
$route['updatePreciario'] = 'cf_mantenimiento/C_bandeja_preciarios/updatePreciario';

$route['getKitMaterial'] = 'cf_liquidacion/C_solicitud_Vr/getKitMaterial';

$route['insertKitMaterialSolicitud'] = 'cf_liquidacion/C_solicitud_Vr/insertKitMaterialSolicitud';

$route['generar_excelCvMO'] = 'cf_extractor/C_extractor/generar_excelCvMO';


//REGISTRO MASIVO LICENCIAS
$route['mantLicMasivo'] = 'cf_detalle_obra/c_registro_masivo_lic';
$route['cargarArchivoLic'] = 'cf_detalle_obra/c_registro_masivo_lic/cargarArchivoLic';

$route['getSubProyectoSinDiseno']  = 'cf_mantenimiento/C_bandeja_sub_sin_diseno';
$route['insertSubProyecto']  = 'cf_mantenimiento/C_bandeja_sub_sin_diseno/insertSubProyecto';
$route['deleteSubProyecto']  = 'cf_mantenimiento/C_bandeja_sub_sin_diseno/deleteSubProyecto';
$route['getCargaOfflinePO'] = 'cf_carga_offline/C_carga_po';
$route['cargaMo'] = 'cf_carga_offline/C_carga_po/cargaMo';
$route['cargaMat'] = 'cf_carga_offline/C_carga_po/cargaMat';
$route['getMotivoCancelacion'] = 'cf_ejecucion/c_pendientes/getMotivoCancelacion';
$route['getDataSisego'] = 'cf_plan_obra/C_consulta/getDataSisego';
$route['getPoRegistroGrafico'] = 'cf_plan_obra/C_po_registro_grafico';
$route['getTablaPartidasGrafico'] = 'cf_plan_obra/C_po_registro_grafico/getTablaPartidasGrafico';
$route['generarPOGraf'] = 'cf_plan_obra/C_po_registro_grafico/generarPOGraf';
$route['makeCSVDetalleLic'] = 'cf_extractor/C_extractor/generarExcelDetalleLic';
$route['consulta_expediente'] = 'cf_ejecucion/C_consulta_expediente';

//COTIZACION CLUSTER SISEGO//
/**cluster sisego**/
$route['clusterSigo'] = 'cf_plan_obra/C_planobra/createClusteFromSisego';
$route['cotclus']       = 'cf_cluster/C_bandeja_cluster';
$route['filCluster']    = 'cf_cluster/C_bandeja_cluster/filtrarBandejaCluster';
$route['evaClus']       = 'cf_cluster/C_evaluar_cluster';
$route['saveCotiClus']  = 'cf_cluster/C_evaluar_cluster/sendCotizacionCluster';
$route['clusterUpdate'] = 'cf_plan_obra/C_planobra/aprobarCancelarCluster';



$route['repOPxSubproy']    = 'cf_reportes_v/C_reporte_obras_publicas';
$route['getReportOPByFiltros']    = 'cf_reportes_v/C_reporte_obras_publicas/filtrarTabla';


/***************************/
$route['createObraSAM'] = 'cf_plan_obra/C_planobra/tramaCrearSAM';


//MANTENIMIENTO ALMACEN
$route['mantAlmacen'] = 'cf_mantenimiento/C_bandeja_almacen';
$route['getCmbRegAlma'] = 'cf_mantenimiento/C_bandeja_almacen/getCombosRegAlmacen';
$route['regAlmacen'] = 'cf_mantenimiento/C_bandeja_almacen/registrarAlmacen';
$route['getDetAlmacen'] = 'cf_mantenimiento/C_bandeja_almacen/getDetalleAlmacen';
$route['updateAlmacen'] = 'cf_mantenimiento/C_bandeja_almacen/updateAlmacen';


$route['getTransferenciaSiom'] = 'cf_tranferencias/C_transferencia_siom';

$route['insertDetalleSiom'] = 'cf_tranferencias/C_transferencia_siom/insertDetalleSiom';


$route['updateComproV2'] = 'cf_ejecucion/C_bandeja_licencias/updateComprobanteV2';

$route['regDiCoti'] = 'cf_detalle_obra/C_registro_po_mo/registrarPoDisenoManual';

$route['actualizarCodigoSiom'] = 'cf_plan_obra/C_consulta/actualizarCodigoSiom';

/***********CRECIMIENTO VERTICAL NEGOCIO 2***************/
$route['editCV2']       = 'cf_crecimiento_vertical/C_edit_crecimiento_vertical_negocio';
$route['banEditCV2']    = 'cf_crecimiento_vertical/C_bandeja_edit_cv_negocio';
$route['getEeccDis']    = 'cf_crecimiento_vertical/C_edit_crecimiento_vertical_negocio/getHTMLChoiceEeccByDistrito';
$route['updateCV2']     = 'cf_crecimiento_vertical/C_edit_crecimiento_vertical_negocio/saveItemCV';
$route['filTabNego'] = 'cf_crecimiento_vertical/C_bandeja_edit_cv_negocio/filtrarTabla';


$route['getProcesoPiloto'] = 'cf_plan_obra/C_proceso_piloto';

$route['getDataPiloto'] = 'cf_plan_obra/C_proceso_piloto/getDataPiloto';

$route['registrarFluidUno'] = 'cf_plan_obra/C_proceso_piloto/registrarFluidUno';

$route['registrarFluidDos'] = 'cf_plan_obra/C_proceso_piloto/registrarFluidDos';

$route['registrarFluidTres'] = 'cf_plan_obra/C_proceso_piloto/registrarFluidTres';

$route['getCmbEntidades'] = 'cf_plan_obra/C_proceso_piloto/getCmbEntidades';

$route['registrarEntidadesPiloto'] = 'cf_plan_obra/C_proceso_piloto/registrarEntidadesPiloto';

$route['registrarFluidCuatro'] = 'cf_plan_obra/C_proceso_piloto/registrarFluidCuatro';

$route['registrarFluidCinco'] = 'cf_plan_obra/C_proceso_piloto/registrarFluidCinco';

$route['registrarFluidSeis'] = 'cf_plan_obra/C_proceso_piloto/registrarFluidSeis';

$route['registrarFluidSiete'] = 'cf_plan_obra/C_proceso_piloto/registrarFluidSiete';

$route['getModalComprobante'] = 'cf_plan_obra/C_proceso_piloto/getModalComprobante';

$route['reporteCV'] = 'cf_reportes_v/C_reporte_crecimiento_vertical';
$route['getReportCVByFiltros'] = 'cf_reportes_v/C_reporte_crecimiento_vertical/filtrarTabla';
$route['getDetIPCVxJefa'] = 'cf_reportes_v/C_reporte_crecimiento_vertical/getDetalleIP';

$route['getBandejaResumenPo']   = 'cf_liquidacion/C_bandeja_resumen_po';

$route['getModalDetallePO']   = 'cf_liquidacion/C_bandeja_resumen_po/getModalDetallePO';

$route['getBandejaFirmaDigitalMO']   = 'cf_liquidacion/C_bandeja_firma_digital_mo';

$route['validarFirmaDigital'] = 'cf_liquidacion/C_bandeja_firma_digital_mo/validarFirmaDigital';

//PLACAS
$route['mantPlacas'] = 'cf_mantenimiento/C_bandeja_placas';
$route['regPlaca'] = 'cf_mantenimiento/C_bandeja_placas/registrarPlacas';
$route['getDetPlacaEdit'] = 'cf_mantenimiento/C_bandeja_placas/getDetallePlaca';
$route['updatePlaca'] = 'cf_mantenimiento/C_bandeja_placas/updatePlaca';

$route['filtrarTablaResumen'] = 'cf_liquidacion/C_bandeja_resumen_po/filtrarTablaResumen';



//PLACAS X VR
$route['mantPlacaxVR'] = 'cf_mantenimiento/C_placa_x_valereserva';
$route['getComboPlaca'] = 'cf_mantenimiento/C_placa_x_valereserva/getComboPlaca';
$route['regPlacaxVR'] = 'cf_mantenimiento/C_placa_x_valereserva/registrarPlacaxVR';
$route['getDetPlacaxVREdit'] = 'cf_mantenimiento/C_placa_x_valereserva/getDetallePlacaxVR';
$route['updatePlacaxVR'] = 'cf_mantenimiento/C_placa_x_valereserva/updatePlacaxVR';

/****************************CONEXIO SIOM*****************************/
$route['cerrarOs'] = 'cf_servicios/C_integracion_siom/cerrarOSFromSiom';
$route['cambiarEstadoOs'] = 'cf_servicios/C_integracion_siom/cambiarEstadoOsFromSiom';

$route['createCotiza'] = 'cf_servicios/C_integracion_sisego_web/crearCotizacionIndividualV2';

$route['createCotizaPrueba'] = 'cf_servicios/C_integracion_sisego_web/crearCotizacionIndividual';


$route['filtrarTablaFirmaDigital'] = 'cf_liquidacion/C_bandeja_firma_digital_mo/filtrarTablaFirmaDigital';


$route['editVRPO'] = 'cf_plan_obra/C_editar_vr_po_aprob';
$route['getCmbPO'] = 'cf_plan_obra/C_editar_vr_po_aprob/getComboPtr';
$route['getVRbyIPPO'] = 'cf_plan_obra/C_editar_vr_po_aprob/getVr';
$route['updateVRPO'] = 'cf_plan_obra/C_editar_vr_po_aprob/updateVRPO';


$route['getCmbJefaturaReg'] = 'cf_mantenimiento/C_central/getCmbJefaturaReg';
$route['updateOSTree'] 		= 'cf_servicios/C_integracion_siom/testUpdateOSFromSIOMTREE';


//JEFATURA
$route['mantJefatura'] = 'cf_mantenimiento/C_bandeja_jefatura';
$route['regJefatura'] = 'cf_mantenimiento/C_bandeja_jefatura/registrarJefatura';
$route['getDetJefaturaEdit'] = 'cf_mantenimiento/C_bandeja_jefatura/getDetalleJefatura';
$route['updateJefatura'] = 'cf_mantenimiento/C_bandeja_jefatura/updateJefatura';

$route['getPoRegistroCotizacion'] = 'cf_plan_obra/C_po_registro_cotizacion';

$route['getTablaPartidasCotizacion'] = 'cf_plan_obra/C_po_registro_cotizacion/getTablaPartidasCotizacion';

$route['generarPOCotizacion'] = 'cf_plan_obra/C_po_registro_cotizacion/generarPOCotizacion';

$route['insertTbkitMaterialMasivo'] = 'cf_plan_obra/C_kit_planta_externa/insertTbkitMaterialMasivo';




//MANTENIMIENTO PARTIDAS X SUBPROYECTO PIN
$route['mantPartSubProy'] = 'cf_mantenimiento/C_bandeja_partida_subproyecto';
$route['getPartSubProyByFiltros'] = 'cf_mantenimiento/C_bandeja_partida_subproyecto/filtrarTabla';
$route['getCombosPartSubProy'] = 'cf_mantenimiento/C_bandeja_partida_subproyecto/getCombos';
$route['getSubProysbyPartida'] = 'cf_mantenimiento/C_bandeja_partida_subproyecto/getSubProyectosByPart';
$route['regPartSubProy'] = 'cf_mantenimiento/C_bandeja_partida_subproyecto/registrarPartidaSubProy';
$route['getDetPartSubProy'] = 'cf_mantenimiento/C_bandeja_partida_subproyecto/getDetPartSubProy';
$route['updatePartSubProy'] = 'cf_mantenimiento/C_bandeja_partida_subproyecto/updatePartSubProy';
$route['insertPartidasMasivaPin'] = 'cf_mantenimiento/C_bandeja_partida_subproyecto/insertPartidasMasivaPin';

$route['makeCSVReporteCoti'] = 'cf_extractor/C_extractor/generarExcelCotizacion';

$route['getBandejaCotizacionIndividual'] = 'cf_cotizacion/C_bandeja_cotizacion_individual';
$route['getFormCotizacionIndividual']    = 'cf_cotizacion/C_form_cotizacion_individual';

$route['sendCotizacionIndividual']    = 'cf_cotizacion/C_form_cotizacion_individual/sendCotizacionIndividual';

/**********PARA DOMINIOM********/
$route['makeFilesDominiom'] = 'cf_servicios/C_integracion_dominion/MakeFilesDominion';


$route['getBandejaRegCvNegocio'] = 'cf_crecimiento_vertical/C_bandeja_reg_cv_negocio';

$route['registroCvNegocio'] = 'cf_crecimiento_vertical/C_bandeja_reg_cv_negocio/registroCvNegocio';


$route['getBandejaDisenoCv'] = 'cf_crecimiento_vertical/C_bandeja_diseno_cv';

$route['ingresarArchivosDisenoCv'] = 'cf_crecimiento_vertical/C_bandeja_diseno_cv/ingresarArchivosDisenoCv';

$route['filtrarTablaBandejaDisenoCv'] = 'cf_crecimiento_vertical/C_bandeja_diseno_cv/filtrarTablaBandejaDisenoCv';

/****************/
$route['getNodSiom']    = 'cf_liquidacion/C_bandeja_siom/getNodoAndEmplazamienos';
$route['reSendSiom']    = 'cf_liquidacion/C_liquidacion/reenviarTramaSiom';

$route['getReporteSisego']    = 'cf_reporte_gerente/C_reporte_sisego';

$route['filtrarTablaBandejaReporteSisego'] = 'cf_reporte_gerente/C_reporte_sisego/filtrarTablaBandejaReporteSisego';

$route['openModalDisenoReporte'] = 'cf_reporte_gerente/C_reporte_sisego/openModalDisenoReporte';

$route['generar_excelCvSisego'] = 'cf_extractor/C_extractor/generar_excelCvSisego';
$route['getExcelAvanceCV'] = 'cf_extractor/C_extractor/generar_excelCvAvanceCV';
$route['getExcelCertiCV'] = 'cf_extractor/C_extractor/generar_excelCertificacionCV';
$route['getExcelPOvalidadosCV'] = 'cf_extractor/C_extractor/generar_excelPOvalidadosCV';
$route['genOCOnline'] = 'cf_extractor/C_extractor/generarReporteOCOnline';
$route['getExcelItemplanMadreCV'] = 'cf_extractor/C_extractor/generar_excelItemplanMadreCV';


$route['filtrarCotizacionInd'] = 'cf_cotizacion/C_bandeja_cotizacion_individual/filtrarCotizacionInd';

$route['getLogBanSiom'] = 'cf_liquidacion/C_bandeja_siom/getDataSiom';
$route['newSiomOS'] = 'cf_liquidacion/C_liquidacion/nuevaOSTramaSiom';

$route['openModalGenerarPO'] = 'cf_plan_obra/C_bandeja_cambio_po/openModalGenerarPO';
$route['generarPOComplejidadDiseno'] = 'cf_plan_obra/C_bandeja_cambio_po/generarPOComplejidadDiseno';
$route['getBandejaCambioPo']     = 'cf_plan_obra/C_bandeja_cambio_po';
$route['getCmbEstacionCambioPo'] = 'cf_plan_obra/C_bandeja_cambio_po/getCmbEstacionCambioPo';
$route['getCmbCodigoPo']         = 'cf_plan_obra/C_bandeja_cambio_po/getCmbCodigoPo';
$route['registrarData']          = 'cf_plan_obra/C_bandeja_cambio_po/registrarData';

$route['getTransferenciaSam'] = 'cf_tranferencias/C_transferencia_sam';
$route['insertTransferenciaSam'] = 'cf_tranferencias/C_transferencia_sam/insertTransferenciaSam';


$route['forcedUM']    = 'cf_liquidacion/C_liquidacion/nuevaUMForced';

$route['testSiomPer']    = 'cf_liquidacion/C_liquidacion/enviarTramaIndividualPerzonalizada';

$route['getConsultaCotizacionInd']       = 'cf_cotizacion/C_consulta_cotizacion_individual';
$route['filtrarTablaConsultaCotizacion'] = 'cf_cotizacion/C_consulta_cotizacion_individual/filtrarTablaConsultaCotizacion';
$route['zipArchivosForm']                = 'cf_cotizacion/C_consulta_cotizacion_individual/zipArchivosForm';


$route['senPeroSiom']    = 'cf_liquidacion/C_liquidacion/enviarTramasByItemplanPTR';
$route["insertFoto2"]         = "cf_ejecucion/C_porcentaje/insertFotoFuera";
$route['getDataDetalleCotizacionSisego'] = 'cf_cotizacion/C_consulta_cotizacion_individual/getDataDetalleCotizacionSisego';
$route['getEstaSiom']    = 'cf_liquidacion/C_bandeja_siom/getEstacionesToSendSiom';
$route["saveFormUM"] = "cf_ejecucion/C_pendientes/registrarFormularioUM";

$route['generar_excelCotizacionSisego'] = 'cf_extractor/C_extractor/generar_excelCotizacionSisego';


$route['getBandejaValidCotizacion'] = 'cf_cotizacion/C_bandeja_validacion_cotizacion';


$route['validarEnviarCotizacion'] = 'cf_cotizacion/C_bandeja_validacion_cotizacion/validarEnviarCotizacion';
$route['filtrarValidCotizacion'] = 'cf_cotizacion/C_bandeja_validacion_cotizacion/filtrarValidCotizacion';

$route['rechazarCotizacion'] = 'cf_cotizacion/C_bandeja_validacion_cotizacion/rechazarCotizacion';

$route['getEeccByDistrito'] = 'cf_cotizacion/C_bandeja_cotizacion_individual/getEeccByDistrito';

$route['updateEmpresaColab'] = 'cf_cotizacion/C_bandeja_cotizacion_individual/updateEmpresaColab';

$route["makeFileActivaciones"] = "cf_tranferencias/C_tranferencia_wu/generarReporteActivaciones";

/**************reporte siom 2*****************************/
$route["repSiomTec"]         = "cf_reportes_v/C_reporte_siom_tecnico";
$route["drwaPieByFil"] = "cf_reportes_v/C_reporte_siom_tecnico/drawPieTecnicos";
$route["getMarksTec"] = "cf_reportes_v/C_reporte_siom_tecnico/getMarcadoresByProyectoTecmocp";
/*******************************************/
/**************reporte siom 2*****************************/
$route["repSiomTecFll"]         = "cf_reportes_v/C_reporte_siom_tecnico_full";
$route["getMarksFil"]         = "cf_reportes_v/C_reporte_siom_tecnico_full/getMarcadoresByFiltros";
/**************reporte siom 1*****************************/

$route['getMdfCotizacionInd'] = 'cf_cotizacion/C_bandeja_cotizacion_individual/getMdfCotizacionInd';

$route['getLogCotizacionInd'] = 'cf_cotizacion/C_consulta_cotizacion_individual/getLogCotizacionInd';

/**********tableros de comando**********/
$route["repBaXHoras"]         = "cf_tableros_comando/c_reporte_bandeja_aprob_horas";
$route["getDetRepBa"]         = "cf_tableros_comando/c_reporte_bandeja_aprob_horas/drawPieBADet";
$route["getDetByProEstaRango"]         = "cf_tableros_comando/c_reporte_bandeja_aprob_horas/getTableByFilRepBa";

/************ tablero de comando nivel proyecto *************/
$route["repBaXProy"]         = "cf_tableros_comando/C_reporte_bandeja_aprob_proyecto";
$route["getFilProyPie"]         = "cf_tableros_comando/C_reporte_bandeja_aprob_proyecto/getTableByFilRepBaProy";
$route["getFilTableBA"]         = "cf_tableros_comando/C_reporte_bandeja_aprob_proyecto/getDetallePtrsPendPorProyecto";
$route['getMotivoRechazoCotizacion'] = 'cf_cotizacion/C_bandeja_cotizacion_individual/getMotivoRechazoCotizacion';
$route['rechazarCotizacionSisego'] = 'cf_cotizacion/C_bandeja_cotizacion_individual/rechazarCotizacionSisego';

/***BANDEJA CANCELACION SIOM**/
$route['getBanCanSiom']    = 'cf_liquidacion/C_bandeja_cancelacion_siom';
$route['filtrarTablaCancelacionSiom'] = 'cf_liquidacion/C_bandeja_cancelacion_siom/filtrarTablaSiom';

$route['testSiropeWS']    = 'cf_servicios/C_integracion_sirope';
$route['downSiPar']    = 'cf_extractor/C_extractor/crearCSVSisegoParalziados';

$route['getControlDiseno'] = 'cf_panel_control/C_control_diseno';
$route['generarPOControl'] = 'cf_panel_control/C_control_diseno/generarPOControl';

/****registro po mat pin 22.07.2019****/
$route['regPinPO'] = 'cf_plantaInterna/C_registro_individual_po_mat_pin';
$route['cargarArchivoPOPIN'] = 'cf_plantaInterna/C_registro_individual_po_mat_pin/cargarArchivoPO';
$route['registPOPIN'] = 'cf_plantaInterna/C_registro_individual_po_mat_pin/registPO';
$route['getControlLicencia'] = 'cf_panel_control/C_control_licencia';
$route['generarPOLicGestion'] = 'cf_panel_control/C_control_licencia/generarPOLicGestion';
$route['getControlTramas'] = 'cf_panel_control/C_control_tramas';
$route['getTablaDetalle'] = 'cf_panel_control/C_control_tramas/getTablaDetalle';
$route['getTablaDetalleTramaSiom'] = 'cf_panel_control/C_control_tramas/getTablaDetalleTramaSiom';
$route['generar_excelLicFinalizacion'] = 'cf_extractor/C_extractor/generar_excelLicFinalizacion';
$route['insertReporte'] = 'cf_tranferencias/C_tranferencia_wu/insertReporte';
$route['getTablaDetalleTransferencia'] = 'cf_panel_control/C_control_tramas/getTablaDetalleTransferencia';
$route['getDetalleTramaSirope']        = 'cf_panel_control/C_control_tramas/getDetalleTramaSirope';
/******************** modificacion INTEGRAL  LIQUIDACION OC 13.08.2019 **************************/
$route["makeHtmlLiq"]	      = "cf_ejecucion/C_porcentaje/makeHTMLToLiqOC";
$route["saveLiquiOC"]	      = "cf_ejecucion/C_porcentaje/saveLiquidacionOC";


//Paquetizado
$route['paquetizado'] = 'cf_paquetizado/C_paquetizado';

//mantenimiento proyecto
$route['pqt_mproyecto']  = 'cf_pqt_mantenimiento/C_proyecto';
$route['pqt_addPro']     = 'cf_pqt_mantenimiento/C_proyecto/addProyecto';
$route['pqt_getInfoPro'] = 'cf_pqt_mantenimiento/C_proyecto/getInfoProyecto';
$route['pqt_updatePro']  = 'cf_pqt_mantenimiento/C_proyecto/updateProyecto';
$route['pqt_addSubPro']  = 'cf_pqt_mantenimiento/C_proyecto/addSubProyecto';
$route['pqt_updSp'] = 'cf_pqt_mantenimiento/C_proyecto/updateSubProyecto';
$route['pqt_getInfSp'] = 'cf_pqt_mantenimiento/C_proyecto/getInfoSubProyecto';
$route['pqt_getCmbComplejidad'] = 'cf_pqt_mantenimiento/C_proyecto/getComboComplejidad';


$route['pqt_mcentral']    = 'cf_pqt_mantenimiento/C_central';
$route['pqt_validCod']    = 'cf_pqt_mantenimiento/C_central/existeCodigoCentral';
$route['pqt_addCentral']  = 'cf_pqt_mantenimiento/C_central/createCentral';
$route['pqt_getInfoCen']  = 'cf_pqt_mantenimiento/C_central/getInfoCentral';
$route['pqt_editCentral'] = 'cf_pqt_mantenimiento/C_central/editarCentral';
$route['pqt_getCmbJefaturaReg'] = 'cf_pqt_mantenimiento/C_central/getCmbJefaturaReg';

/**********  ROUTE REGISTRO INDIVIDUAL PLAN OBRA - PLANTA INTERNA  ****/
$route['pqt_regindPI']    = 'cf_pqt_plan_obra/C_planobra_pi';
$route['pqt_addPlanobraPI'] = 'cf_pqt_plan_obra/C_planobra_pi/createPlanobraPI';
$route['pqt_getSubProPI'] = 'cf_pqt_plan_obra/C_planobra_pi/getHTMLChoiceSubProyPI';
$route['pqt_getZonalPI']  = 'cf_pqt_plan_obra/C_planobra_pi/getHTMLChoiceZonalPI';
$route['pqt_getEECCPI']   = 'cf_pqt_plan_obra/C_planobra_pi/getHTMLChoiceEECCPI';
$route['pqt_getFechaSubproPI'] = 'cf_pqt_plan_obra/C_planobra_pi/getFechaPreEjecuCalculoPI';
$route['pqt_getItemPlanSearch']    = 'cf_pqt_plan_obra/C_planobra_pi/getItemPlanSearch';

//  ROUTE REGISTRO INDIVIDUAL PLAN OBRA
$route['pqt_regindpo']    = 'cf_pqt_plan_obra/C_planobra';
$route['pqt_addPlanobra'] = 'cf_pqt_plan_obra/C_planobra/createPlanobra';
$route['pqt_getInfoPlan'] = 'cf_pqt_plan_obra/C_planobra/getInfoPlan';
$route['pqt_getSubProPO'] = 'cf_pqt_plan_obra/C_planobra/getHTMLChoiceSubProy';
$route['pqt_getZonalPO']  = 'cf_pqt_plan_obra/C_planobra/getHTMLChoiceZonal';
$route['pqt_getEECCPO']   = 'cf_pqt_plan_obra/C_planobra/getHTMLChoiceEECC';
$route['pqt_getFactorMedicion']   = 'cf_pqt_plan_obra/C_planobra/getFactorDeMedicion';
$route['pqt_getFechaSubproOP'] = 'cf_pqt_plan_obra/C_planobra/getFechaPreEjecuCalculo';

// Consulta ITEMPLAN PAQUETIZADO
$route['pqt_consulta'] = 'cf_pqt_plan_obra/C_consulta';
$route['pqt_getDataTableItem'] = 'cf_pqt_plan_obra/C_consulta/filtrarTabla';
$route['pqt_getProyConsulta']  = 'cf_pqt_plan_obra/C_consulta/getHTMLProyectoConsulta';
$route['pqt_getSubProyConsulta']  = 'cf_pqt_plan_obra/C_consulta/getHTMLSubProyectoConsulta';

$route['pqt_detalleObra'] = 'cf_pqt_detalle_obra/C_detalle_obra';

$route['pqt_obtAreaPorCoordenadas']    = 'cf_pqt_plan_obra/C_planobra/getCentralPorCoordenadas';
$route['pqt_obtCentralPorCodigo']    = 'cf_pqt_plan_obra/C_planobra/obtCentralPorCodigo';

$route['getIntegracionSisego'] = 'cf_servicios/C_integracion_sisego_web';

$route['filtrarTablaValid']  = 'cf_plantaInterna/C_bandeja_validacion/filtrarTablaValid';


/**** INTEGRACION RPA BANDEJA APROBACION ****/
$route['getPoMaterial'] = 'cf_servicios/C_integracion_rpa_bandeja_aprobacion/getPoMaterialtoRPA';
$route['updatePoMaterial'] = 'cf_servicios/C_integracion_rpa_bandeja_aprobacion/cambiarEstadoPOMaterial';

/*******INTEGRACION RPA VALE RESERVA *******************************/
$route['updateValeReserva'] = 'cf_servicios/C_integracion_rpa_bandeja_aprobacion/cambiarEstadoVRFromWSRpaSap';



$route['getDataCentral']     = 'cf_crecimiento_vertical/C_bandeja_reg_cv_negocio/getDataCentral';


//OBTENER INFORMACION DE LAS FASES POR PROYECTO GS
$route['pqt_getInfoSubproyectosPorProyecto'] = 'cf_pqt_mantenimiento/C_proyecto/getInfoProyectoFasesPorSubProyecto';

////////////////////CF_PRE_DISENO PAQUETIZADP GS///////////////////////////
$route['pqt_comprimirFiles'] = 'cf_pqt_pre_diseno/C_bandeja_adjudicacion/comprimirFiles';
$route['pqt_insertFile']     = 'cf_pqt_pre_diseno/C_bandeja_adjudicacion/insertFile';
$route['pqt_insertFile2']     = 'cf_pqt_pre_diseno/C_bandeja_adjudicacion/insertFile2';
$route['pqt_bAdju']          = 'cf_pqt_pre_diseno/C_bandeja_adjudicacion';
$route['pqt_filBanAdju'] 	 = 'cf_pqt_pre_diseno/C_bandeja_adjudicacion/filtrarTabla';
$route['pqt_getInfItem'] 	 = 'cf_pqt_pre_diseno/C_bandeja_adjudicacion/getInfoByItemplan';
$route['pqt_adjuItem'] 	 = 'cf_pqt_pre_diseno/C_bandeja_adjudicacion/adjudicarItemplan';
$route['pqt_bEjec'] 	 = 'cf_pqt_pre_diseno/C_bandeja_ejecucion';
$route['pqt_filBanEjec']     = 'cf_pqt_pre_diseno/C_bandeja_ejecucion/filtrarTabla';
$route['pqt_ejecDiseno']     = 'cf_pqt_pre_diseno/C_bandeja_ejecucion/ejecutarDiseno';

////////////////////VALIDAR GS//////////////////////////////////
$route['pqt_getInfoSubProCoaxFo']     = 'cf_pqt_plan_obra/C_planobra/getInfoSubProCoaxFo';

///BARRA DE PROGRESO DEL ITEMPLAN//////
$route['pqt_getProgresoItemPlan']     = 'cf_pqt_plan_obra/C_consulta/getProgresoItemPlan';
///GESTION OBRA//////
$route['pqt_gestionarObra']     = 'cf_pqt_plan_obra/C_gestionobra';
$route['obtFormularioPreDiseno']     = 'cf_pqt_gestion_obra_pre_diseno/C_pre_diseno';
$route['obtFormularioDiseno']     = 'cf_pqt_obra_diseno/C_pqt_diseno';
$route['obtFormularioEnLicencia']     = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias';
$route['obtFormularioEnAprobacion']     = 'cf_pqt_gestion_obra_en_aprobacion/C_en_aprobacion';
$route['obtFormularioEnObra']     = 'cf_pqt_gestion_obra_en_obra/C_bandeja_siom';
$route['obtFormularioPreLiquidado']     = 'cf_pqt_gestion_obra_pre_liquidado/C_pre_liquidacion';
$route['obtFormularioEnValidacion']     = 'cf_pqt_gestion_obra_en_validacion/C_en_validacion';
$route['obtFormularioTerminado']     = 'cf_pqt_gestion_obra_terminado/C_terminado_v2';
$route['obtFormularioEnCertificacion']     = 'cf_pqt_gestion_obra_en_certificacion/C_en_certificacion';
$route['obtFormularioCertificado']     = 'cf_pqt_gestion_obra_certificado/obtFormularioCertificado';

$route['getDiasMatriz'] = 'cf_cotizacion/C_form_cotizacion_individual/getDiasMatriz';

$route['getDataSeiaMtc'] = 'cf_cotizacion/C_form_cotizacion_individual/getDataSeiaMtc';

//AGREGADO GUSTAVO SEDANO 17-09-2019
$route['pqt_getEntLic'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/getInfoEntidades';
$route['pqt_getCmbEntLic'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/getEntidadesLicencia';
$route['pqt_getComprobantesLic'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/getComprobantes';
$route['pqt_updateComproV2'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/updateComprobanteV2';
$route['pqt_regEntLic'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/registrarEntidades';
$route['pqt_deleteIPEstDetLic'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/deleteIPEstDetLic';
$route['pqt_subirEviIPEstDet'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/updateIPEstDet';
$route['pqt_getRutaEviIPEstaDet'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/getRutaEvidenciaItemPlanEsta';
$route['pqt_saveUpdateCompro'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/saveUpdateComprobante';
$route['pqt_getRutaEviReembolso'] = 'cf_pqt_gestion_obra_en_licencia/C_bandeja_licencias/getRutaEvidenciaReembolso';


$route['pqt_detenerItemplan'] = 'cf_pqt_plan_obra/C_consulta/detenerItemplan';
$route['pqt_reanudarItemplan'] = 'cf_pqt_plan_obra/C_consulta/reanudarItemplan';

$route['ejecutarPqtDiseno']     = 'cf_pqt_obra_diseno/C_pqt_diseno/ejecutarPqtDiseno';
$route['pqt_getDisenoEjecutado'] = 'cf_pqt_obra_diseno/C_pqt_diseno/getDisenoEjecutado';

/********************** BANDEJA DE VALIDADOS SIOM ****************************/
$route['banValSiom']    = 'cf_siom_obra/C_bandeja_validados_siom';
$route['filTablaValidadosSiom']    = 'cf_siom_obra/C_bandeja_validados_siom/filtrarTablaSiom';
$route['saveObservaSiom']           = 'cf_siom_obra/C_bandeja_validados_siom/saveObservacionSiom';
$route['saveDesObservaSiom']        = 'cf_siom_obra/C_bandeja_validados_siom/saveDesObservacionSiom';
$route['getLogObserSiom']           = 'cf_siom_obra/C_bandeja_validados_siom/getDataSiom';

//GUSTAVO SEDANO 2019 09 17
$route['pqt_saveSisegoPlanObra'] = 'cf_pqt_gestion_obra_pre_liquidado/C_pre_liquidacion/saveSisegoPlanObra';
$route["pqt_registrarFormObraPub"] = "cf_pqt_gestion_obra_pre_liquidado/C_pre_liquidacion/registrarFormObraPub";
$route["pqt_saveFormUM"] = "cf_pqt_gestion_obra_pre_liquidado/C_pre_liquidacion/registrarFormularioUM";

//GUSTAVO SEDANO 2019 09 26
$route["pqt_registrar_evidencias"] = "cf_pqt_gestion_obra_pre_liquidado/C_pre_liquidacion/registrarEvidencias";
$route['pqt_mostrarLogIPConsulta'] = 'cf_pqt_plan_obra/C_consulta/mostrarLogItemPlanConsulta';
$route['pqt_permitirCrearItemPlan'] = 'cf_pqt_plan_obra/C_planobra/permitirFasesCantItemplan';

$route['pqt_perteneceactpqt'] = 'cf_pqt_plan_obra/C_consulta/validarItemplanPerteneceAPaquetizado';


$route['getPanel'] = 'c_panel';

$route['getFormSolicitudSiom'] = 'cf_usuario_siom/c_form_solicitud_usuario';

$route['ingresarSolicitud'] = 'cf_usuario_siom/c_form_solicitud_usuario/ingresarSolicitud';

$route['getDataModificacion'] = 'cf_usuario_siom/c_form_solicitud_usuario/getDataModificacion';

$route['getBandejaSolicitudU'] = 'cf_usuario_siom/c_bandeja_solicitud_usuario';

$route['aprobCanSolicitud'] = 'cf_usuario_siom/c_bandeja_solicitud_usuario/aprobCanSolicitud';

$route['filtrarTablaSolicitudUsuario'] = 'cf_usuario_siom/c_bandeja_solicitud_usuario/filtrarTablaSolicitudUsuario';

$route['ingresoSolicitudModificacion'] = 'cf_usuario_siom/c_form_solicitud_usuario/ingresoSolicitudModificacion';

$route['getConsultaSolUsuario'] = 'cf_usuario_siom/c_consulta_solicitud_usuario';

$route['ingresarUsuario'] = 'cf_usuario_siom/c_bandeja_solicitud_usuario/ingresarUsuario';

$route['pqt_gestion_incidencias'] = 'cf_incidencias/C_gestion_incidencias';
$route['pqt_registrar_incidencias'] = 'cf_incidencias/C_gestion_incidencias/registrarIncidente';
$route['pqt_aprobar_incidente'] = 'cf_incidencias/C_gestion_incidencias/aprobarIncidente';
$route['pqt_rechazar_incidente'] = 'cf_incidencias/C_gestion_incidencias/rechazarIncidente';
$route['pqt_cerrar_incidente'] = 'cf_incidencias/C_gestion_incidencias/cerrarIncidente';
$route['pqt_descargar_adjunto'] = 'cf_incidencias/C_gestion_incidencias/descargarAdjunto';
$route['pqt_filtrar_tabla_incidentes'] = 'cf_incidencias/C_gestion_incidencias/filtrarTablaIncidentes';

$route['pqt_gestion_tipo_incidencias'] = 'cf_incidencias/C_gestion_tipo_incidente';
$route['pqt_registrar_tipo_incidente'] = 'cf_incidencias/C_gestion_tipo_incidente/registrarTipoIncidente';
$route['pqt_validar_id_tipo_incidente'] = 'cf_incidencias/C_gestion_tipo_incidente/validarTipoIncidente';

$route['pqt_gestion_modulo'] = 'cf_incidencias/C_gestion_modulo';
$route['pqt_registrar_modulo'] = 'cf_incidencias/C_gestion_modulo/registrarModulo';
$route['pqt_validar_id_modulo'] = 'cf_incidencias/C_gestion_modulo/validarModulo';

$route['pqt_asignacion_responsable_modulo'] = 'cf_incidencias/C_asignacion_modulo_responsable';
$route['pqt_arm_candidatos_a_responsables'] = 'cf_incidencias/C_asignacion_modulo_responsable/makeHtmlResponsablesCandidatos';
$route['pqt_arm_modulos'] = 'cf_incidencias/C_asignacion_modulo_responsable/makeHtmlModulos';
$route['pqt_arm_modulos_asignados'] = 'cf_incidencias/C_asignacion_modulo_responsable/makeHtmlTablaModulos';
$route['pqt_arm_asignar_modulos'] = 'cf_incidencias/C_asignacion_modulo_responsable/asignarModulos';
$route['pqt_arm_cambiar_estado_responsable_modulo'] = 'cf_incidencias/C_asignacion_modulo_responsable/cambiarEstadoModuloResponsable';


/*************BOLSA PEP NUEVO 14.10.2019 czavalacas **************/
$route['getSubProBolsa'] = 'cf_presupuesto/C_bolsa_pep/getHTMLChoiceSubProy';
$route['getBoPep']       = 'cf_presupuesto/C_bolsa_pep';
$route['savePepBol']     = 'cf_presupuesto/C_bolsa_pep/savePep1Pep2BolsaPep';
$route['filtroPepBolsa']     = 'cf_presupuesto/C_bolsa_pep/filtrarTabla';
$route['delPepBolsa']     = 'cf_presupuesto/C_bolsa_pep/deletePepbolsa';

$route['getControlDisenoEjec'] = 'cf_panel_control/c_control_diseno_ejec';

$route['getTablaDetalleTab1'] = 'cf_panel_control/c_control_diseno_ejec/getTablaDetalleTab1';

$route['getMantenimientoMotivoSiom'] = 'cf_mantenimiento/c_mantenimiento_motivo_siom';

$route['registrarMotivo'] = 'cf_mantenimiento/c_mantenimiento_motivo_siom/registrarMotivo';

$route['getEditMotivoSiom'] = 'cf_mantenimiento/c_mantenimiento_motivo_siom/getEditMotivoSiom';

$route['actualizarMotivoSiom'] = 'cf_mantenimiento/c_mantenimiento_motivo_siom/actualizarMotivoSiom';

$route['getControlSolicitudUsuario'] = 'cf_usuario_siom/c_control_solicitud_usuario';

$route['getDataUsuarioSiomAct'] = 'cf_usuario_siom/c_control_solicitud_usuario/getDataUsuarioSiomAct';

$route['getAlcanceRobotCoti'] = 'cf_cotizacion/c_cotizacion_alcance_robot';

$route['getDataSimulacion'] = 'cf_cotizacion/c_cotizacion_alcance_robot/getDataSimulacion';
$route['getDataByCodigoCotizacion'] = 'cf_cotizacion/c_cotizacion_alcance_robot/getDataByCodigoCotizacion';

$route['getDataInfoCotiCentral'] = 'cf_cotizacion/c_cotizacion_alcance_robot/getDataInfoCotiCentral';

$route['testSisegoPost'] = 'cf_servicios/C_integracion_sisego_web/testTramaSisegoNuevoServer';

$route['pqt_upd_cant_fase_subp'] = 'cf_pqt_mantenimiento/C_proyecto/updCantFaseDeItemplan';
$route["pqt_get_evidencias"] = "cf_pqt_gestion_obra_pre_liquidado/C_pre_liquidacion/zipItemPlan";
$route["modcap"] = "cf_bucle_2019/C_modulos_bucle/moduloCAP";
$route["modMantenimiento"] = "cf_bucle_2019/C_modulos_bucle/moduloMantenimiento";
$route["modAdministrativo"] = "cf_bucle_2019/C_modulos_bucle/moduloAdministrativo";

$route['banPiCer'] = 'cf_plantaInterna/C_bandeja_certificacion_2';
$route['getDetPiIp'] = 'cf_plantaInterna/C_bandeja_certificacion_2/getDetallePi';
$route['filCerti2'] = 'cf_plantaInterna/C_bandeja_certificacion_2/filtrarCertificacion';
$route['reSendSirope'] = 'cf_panel_control/C_control_tramas/reenviarTramaSirope';
$route['reSendSiropeFecPrev'] = 'cf_panel_control/C_control_tramas/reenviarTramaSiropeWitFechaPrevista';
$route['getControlCotiAten'] = 'cf_panel_control/C_control_atencion_cotizacion';
$route['getDataGrafCotizacion'] = 'cf_panel_control/C_control_atencion_cotizacion/getDataGrafCotizacion';
$route['getDetalleCotiAten'] = 'cf_panel_control/C_control_atencion_cotizacion/getDetalleCotiAten';


/**** INTEGRACION RPA BANDEJA HOJA GESTION 12.11.2019 CZAVALACAS****/

$route['gesHoGes'] 	        = 'cf_certificacion/C_gestionar_hoja_gestion';
$route['getPtrByHGestion'] 	= 'cf_certificacion/C_gestionar_hoja_gestion/getPtrsByHojaGestion';
$route['filtrarTablaHG'] 	= 'cf_certificacion/C_gestionar_hoja_gestion/filtrarTablaHG';
$route['updateHG'] 	        = 'cf_certificacion/C_gestionar_hoja_gestion/setHojaGestionEnProceso';
$route['certHojaGes'] 	    = 'cf_certificacion/C_gestionar_hoja_gestion/setHojaGestionCertificado';
$route['excelHojaGes']      = 'cf_certificacion/C_gestionar_hoja_gestion/makeCSVHojaGestionMO';
$route['delPtrFrHg']      = 'cf_certificacion/C_gestionar_hoja_gestion/removeOnePtrFromHojaGestion';

$route['getControlDistanciaMdf'] = 'cf_panel_control/C_control_distancia_mdf';
$route['getDetRangoRpaSap']        = 'cf_panel_control/C_control_tramas/getDetalleTramaRpaSap';

$route['getDetalleRpaCotizacion']        = 'cf_panel_control/C_control_tramas/getDetalleRpaCotizacion';

$route['enviarCotizacionIndRobot'] = 'cf_servicios/C_integracion_sisego_web/enviarCotizacionIndRobot';

$route['enviarSoporte']            = 'cf_incidencias/C_gestion_incidencias/enviarSoporte';

$route['updateCotiMdfByKmz'] = 'cf_servicios/C_integracion_sisego_web/updateCotiMdfByKmz';

/**simulador_mo**/
$route['getBaSimuMo'] 	       = 'cf_certificacion/C_bandeja_simulador_mo';
$route['filtrarTbSimu'] 	   = 'cf_certificacion/C_bandeja_simulador_mo/filtrarTablaSimuladorMO';

$route['updateFacilidadesRed'] = 'cf_servicios/C_integracion_sisego_web/updateFacilidadesRed';
#ws vr
$route['getVRMaterial'] = 'cf_servicios/C_integracion_rpa_bandeja_aprobacion/getVRListToRPA';
$route['getControlTramaSisego'] = 'cf_panel_control/c_control_tramas_sisego';
$route['filtrarTablaTramaSisego'] = 'cf_panel_control/c_control_tramas_sisego/filtrarTablaTramaSisego';


/**nuevo Control presupuestal**/
$route['regPoByCU'] = 'C_utils/validateRegPoByCostoUnitario';
$route['banConPre'] = 'cf_control_presupuestal/C_control_presupuestal';
$route['validSolCP'] = 'cf_control_presupuestal/C_control_presupuestal/validarControlPresupuestal';
$route['genSolExce'] = 'cf_control_presupuestal/C_control_presupuestal/generarSolicitud';
$route['filtrarTbConPre'] = 'cf_control_presupuestal/C_control_presupuestal/filtrarTablaCP';
//Consulta
$route['consBanje'] = 'cf_control_presupuestal/C_bandeja_presupuestal';
$route['filtrarTbConsBanje'] = 'cf_control_presupuestal/C_bandeja_presupuestal/filtrarTablaCP';

$route['regSolicitudPreMo']    = 'cf_liquidacion_mo/C_liquidar_mo/regSolicitudPreMo';
$route['getControlPreMo']    = 'cf_liquidacion_mo/C_control_presupuestal_mo';
$route['validarSolicitud'] = 'cf_liquidacion_mo/C_control_presupuestal_mo/validarSolicitud';
$route['filtrarTablaPre'] = 'cf_liquidacion_mo/C_control_presupuestal_mo/filtrarTablaPre';
//Consulta
$route['banPrepMo'] = 'cf_liquidacion_mo/C_bandeja_presupuestal_mo';
$route['filtrarTablaBanPrepMo'] = 'cf_liquidacion_mo/C_bandeja_presupuestal_mo/filtrarTablaPre';
//
/**creacion OC***/
$route['createOC'] 	        	= 'cf_certificacion/C_creacion_oc';
$route['getPtrByHGestionOC'] 	= 'cf_certificacion/C_creacion_oc/getPtrsByHojaGestion';
$route['reSolOc']  				= 'cf_certificacion/C_registro_oc_solicitud';
$route['exParMOOC'] 			= 'cf_certificacion/C_registro_oc_solicitud/getExcelPartidasMO';
$route['upFiPoMoOC'] 			= 'cf_certificacion/C_registro_oc_solicitud/uploadPOMO';
$route['filtrarTablaCOC'] 		= 'cf_certificacion/C_creacion_oc/filtrarTabaCOC';
$route['valSolEdiOC'] 	        = 'cf_certificacion/C_creacion_oc/validarSolicitudEdicionOC';
$route['MasivoOC'] 				= 'cf_certificacion/C_registro_oc_solicitud/MasivoSavePo';



$route['actualizarFlagRpa'] = 'cf_liquidacion/C_bandeja_solicitud_vr/actualizarFlagRpa';

$route['getDataUpdateRobotDevolucion'] = 'cf_liquidacion/C_solicitud_Vr/getDataUpdateRobotDevolucion';

$route['getDetalleTramaRpaVr']        = 'cf_panel_control/C_control_tramas/getDetalleTramaRpaVr';
$route['reloadPoReport']        = 'cf_tranferencias/C_tranferencia_wu/crearCSVPlanObra2_0';

/**********DIAGNOSTICO PEP SISEGOS**************************/
$route['banPepSise']    = 'cf_presupuesto/C_diagnostico_pep_sisego';
$route['filDPS']        = 'cf_presupuesto/C_diagnostico_pep_sisego/filtrarTabaDPS';
$route['repDiagPEPSisego']    = 'cf_tranferencias/C_tranferencia_wu/generarReporteDiagnosticoPep';
//***************** Ivan Joel More Flores  *****************//
// ROUTE DE REPORTE SLA //
$route['reporteSla'] = 'cf_reporte_sla/C_reporte_sla';
$route['ajaxReporteSla'] = 'cf_reporte_sla/C_reporte_sla/tablaSla';
$route['ajaxDetalleSla'] = 'cf_reporte_sla/C_reporte_sla/tablaDetalle';
$route['ajaxExcel'] = 'cf_reporte_sla/C_reporte_sla/exportarExcel';
$route['ajaxExcelDetalle'] = 'cf_reporte_sla/C_reporte_sla/exportarExcelDetalle';
// ROUTE DE REPORTE SUBPROYECTO //
$route['reporteSub'] = 'cf_reporte_sub/C_reporte_sub';
$route['ajaxReporteSub'] = 'cf_reporte_sub/C_reporte_sub/tablaSub';
$route['ajaxReporteDetalleSub'] = 'cf_reporte_sub/C_reporte_sub/tablaDetalleSub';
$route['ajaxExcelDetalleSub'] = 'cf_reporte_sub/C_reporte_sub/exportarExcelDetalle';
$route['ajaxExcelSub'] = 'cf_reporte_sub/C_reporte_sub/exportarExcel';
//  ROUTE REGISTRO ITEMFAULT
$route['regItemfault'] = 'cf_itemfault/C_registro';
$route['ajaxServicioElemento'] = 'cf_itemfault/C_registro/getServicoElemento';
$route['ajaxSubEvento'] = 'cf_itemfault/C_registro/getSubEvento';
$route['ajaxSave'] = 'cf_itemfault/C_registro/saveItemfault';
////
// Consulta ITEMFAULT
$route['consultaItemfault'] = 'cf_itemfault/C_consulta';
$route['ajaxConsulta'] = 'cf_itemfault/C_consulta/tablaConsulta';
$route['getDataTableItemfault'] = 'cf_itemfault/C_consulta/filtrarTabla';
$route['ajaxActualizar'] = 'cf_itemfault/C_consulta/actualizarPropuesta';
$route['gestionarItemfault'] = 'cf_itemfault/C_gestion_itemfault';
$route['ItemfaultDiseno'] = 'cf_itemfault/C_itemfault_diseno';
$route['detalleItemfault'] = 'cf_itemfault/C_detalle_itemfault';
$route['ajaxAprobarDiseno'] = 'cf_itemfault/C_consulta/AprobarDiseno';
$route['consultaPreaprobacion'] = 'cf_itemfault/C_bandeja_preaprobacion';
$route['ajaxBandeja'] = 'cf_itemfault/C_bandeja_preaprobacion/consultaPreAprobItemfault';
//
$route['consultaItemfaultCoti'] = 'cf_itemfault/C_cotizado';
$route['ajaxConsultaCoti'] = 'cf_itemfault/C_cotizado/tablaConsulta';
$route['ajaxActualizarCoti'] = 'cf_itemfault/C_cotizado/actualizarPropuesta';
$route['ajaxAprobarDisenoCoti'] = 'cf_itemfault/C_cotizado/AprobarDiseno';
//-----OPEX-----//
$route['mConfigOpex'] = 'cf_configOpex/C_configOpex';
$route['ajaxBuscarOpex'] = 'cf_configOpex/C_configOpex/consultaTablaOpex';
$route['ajaxGetAllOpex'] = 'cf_configOpex/C_configOpex/consultaTablaOpexNull';
$route['ajaxSaveOpex'] = 'cf_configOpex/C_configOpex/saveTableOpex';
$route['ajaxGetOpexId'] = 'cf_configOpex/C_configOpex/getTableOpexId';
$route['ajaxUpdateOpex'] = 'cf_configOpex/C_configOpex/updateTableOpex';
$route['ajaxDeleteOpex'] = 'cf_configOpex/C_configOpex/deleteTableOpex';
$route['historiaTrans'] = 'cf_configOpex/C_configOpex/historial';
///
//-----OPEX ITEM PLAM-----//
$route['mConfigOpexItem'] = 'cf_itemplan/C_configOpex';
$route['ajaxGetAll'] = 'cf_itemplan/C_configOpex/consultaTablaOpexNull';
$route['historiaTransItemplan'] = 'cf_itemplan/C_configOpex/historial';
$route['historiaLogOpex'] = 'cf_itemplan/C_configOpex/log';
$route['ajaxSaveOpexItemplan'] = 'cf_itemplan/C_configOpex/saveTableOpex';
$route['ajaxDeleteOpexItemplan'] = 'cf_itemplan/C_configOpex/deleteTableOpex';
$route['ajaxUpdateOpexItemplan'] = 'cf_itemplan/C_configOpex/updateTableOpex';
$route['ajaxGetOpexIdItemplan'] = 'cf_itemplan/C_configOpex/getTableOpexId';


///---------------------//
//-----O/C ITEM PLAM-----//
$route['mCreacionOCItemplan'] = 'cf_itemplan/C_itemplan_creacion_oc';
$route['filtrarTablaCOCItemPlan'] = 'cf_itemplan/C_itemplan_creacion_oc/filtrarTabaCOC';
$route['getPtrByHGestionOCItemPlan'] = 'cf_itemplan/C_itemplan_creacion_oc/getPtrsByHojaGestion';
$route['reSolOcMantItemplan'] = 'cf_itemplan/C_itemplan_registro_oc_solicitud';
$route['exParMOOCItemPlan'] = 'cf_itemplan/C_itemplan_registro_oc_solicitud/getExcelPartidasMO';
$route['upFiPoMoOCItemPlan'] = 'cf_itemplan/C_itemplan_registro_oc_solicitud/uploadPOMO';
$route['saPoMoOCItemPlan'] = 'cf_itemplan/C_itemplan_registro_oc_solicitud/savePoMo';
$route['getListarPxq'] = 'cf_itemplan/C_itemplan_creacion_oc/TablaListarPxq';
$route['certificarSolicitudOcOpex'] = 'cf_itemplan/C_itemplan_creacion_oc/certificarSolicitudOc';
$route['valSolEdiOCopex'] = 'cf_itemplan/C_itemplan_creacion_oc/validarSolicitudEdicionOC';

///---------------------///


$route['getDataDisenoItemfault'] = 'cf_itemfault/C_gestion_itemfault/getDataDisenoItemfault';
$route['getDataEnAprobacionItemfault'] = 'cf_itemfault/C_gestion_itemfault/getDataEnAprobacionItemFault';

$route['getRegItemfaultPo'] = 'cf_itemfault/C_registro_itemfault_po';
$route['getExcelPOMatItemfault'] = 'cf_itemfault/C_registro_itemfault_po/getExcelPOMatItemfault';

$route['cargarArchivoPOMatItemfault'] = 'cf_itemfault/C_registro_itemfault_po/cargarArchivoPOMatItemfault';

$route['registPOIteamfault'] = 'cf_itemfault/C_registro_itemfault_po/registPOIteamfault';

$route['ejecutarDisenoItemfault'] = 'cf_itemfault/C_gestion_itemfault/ejecutarDisenoItemfault';

$route['getExcelPOMoItemfault'] = 'cf_itemfault/C_registro_itemfault_po/getExcelPOMoItemfault';

$route['cargarArchivoPOMoItemfault'] = 'cf_itemfault/C_registro_itemfault_po/cargarArchivoPOMoItemfault';

$route['registPOMoIteamfault'] = 'cf_itemfault/C_registro_itemfault_po/registPOMoIteamfault';

$route['poDetalleItemfault'] = 'cf_itemfault/C_detalle_itemfault/poDetalleItemfault';

//$route['ajaxAprobarAporbacion'] = 'cf_itemfault/C_bandeja_preaprobacion/getExcelMatItemfault';

$route['getExcelMatItemfault'] = 'cf_itemfault/C_bandeja_preaprobacion/getExcelPOMatAprobItemfault';

$route['getExcelPOMatAprobItemfault'] = 'cf_itemfault/C_bandeja_preaprobacion/getExcelPOMatAprobItemfault';
$route['consultaPreAprobItemfault'] = 'cf_itemfault/C_bandeja_preaprobacion/consultaPreAprobItemfault';

$route['aprobarMatItemfault'] = 'cf_itemfault/C_bandeja_preaprobacion/aprobarMatItemfault';

//-----PEP - MOBILES-----//
/* * ******************************************** */
$route['mSapMoviles'] = 'cf_movilesPep/C_tranferencia_sap';
$route['upsf1v2Moviles'] = 'cf_movilesPep/C_tranferencia_sap/uploadSj1';
$route['upsf2v2Moviles'] = 'cf_movilesPep/C_tranferencia_sap/uploadSj2';
$route['upPFV2Moviles'] = 'cf_movilesPep/C_tranferencia_sap/execPresupuestoFuntions';
//$route['oracle'] = 'cf_movilesPep/C_tranferencia_sap/read_file';
//$route['reloadAllReportMoviles'] = 'cf_tranferencias/C_tranferencia_wu_reload_reports';
///* * ***************************************************** */
$route['mMovilesPep'] = 'cf_movilesPep/C_movilesPep';
$route['ajaxGetPep'] = 'cf_movilesPep/C_movilesPep/tablaPepVacia';
$route['ajaxSavePep'] = 'cf_movilesPep/C_movilesPepProyecto/saveTablePepMoviles';
$route['mMovilesPepProyecto'] = 'cf_movilesPep/C_movilesPepProyecto';
$route['ajaxGetPepProyecto'] = 'cf_movilesPep/C_movilesPepProyecto/consultaTablaPep';
$route['mMovilesPepDetalle'] = 'cf_movilesPep/C_movilesDetalle';
//-----item-----//
$route['mConsultaZip'] = 'cf_consultaZip/C_consultaZip';
$route['ajaxGetConsulta'] = 'cf_consultaZip/C_consultaZip/filtrarTabla';
$route['liquidacion'] = 'cf_consultaZip/C_consultaZip/liquidacion';
$route['disenho'] = 'cf_consultaZip/C_consultaZip/disenho';
$route['licencias'] = 'cf_consultaZip/C_consultaZip/licencias';
$route['cotizacion'] = 'cf_consultaZip/C_consultaZip/cotizacion';
//
$route['liquidacion_download'] = 'cf_consultaZip/C_consultaZip/liquidacion_download';
$route['disenho_download'] = 'cf_consultaZip/C_consultaZip/disenho_download';
$route['licencias_download'] = 'cf_consultaZip/C_consultaZip/licencias_download';
$route['cotizacion_download'] = 'cf_consultaZip/C_consultaZip/cotizacion_download';

/***itemfault creacion oc****/

$route['ItemFaultOC'] 	        		= 'cf_itemfault/C_itemfault_creacion_oc';
$route['filtrarTablaCOCItemFaul'] 		= 'cf_itemfault/C_itemfault_creacion_oc/filtrarTabaCOC';
$route['getPtrByHGestionOCItemFault'] 	= 'cf_itemfault/C_itemfault_creacion_oc/getPtrsByHojaGestion';
$route['reSolOcMant']  					= 'cf_itemfault/C_itemfault_registro_oc_solicitud';
$route['exParMOOCItemfault'] 			= 'cf_itemfault/C_itemfault_registro_oc_solicitud/getExcelPartidasMO';
$route['upFiPoMoOCItemfaul'] 			= 'cf_itemfault/C_itemfault_registro_oc_solicitud/uploadPOMO';
$route['saPoMoOCItemfaul'] 				= 'cf_itemfault/C_itemfault_registro_oc_solicitud/savePoMo';

$route['getSubProyectoByTipo']  = 'cf_crecimiento_vertical/C_bandeja_reg_cv_negocio/getSubProyectoByTipo';
$route['getEmpresaColabByMdf']  = 'cf_crecimiento_vertical/C_bandeja_reg_cv_negocio/getEmpresaColabByMdf';

$route['getPlanificacionAdmin'] = 'cf_planificacion/C_plan_admin';

$route['getObrasPlanificacion'] = 'cf_planificacion/C_plan_admin/getObrasPlanificacion';

$route['getItemplanAllBySubPlan'] = 'cf_planificacion/C_plan_admin/getItemplanAllBySubPlan';

$route['asignarItemPlani'] = 'cf_planificacion/C_plan_admin/asignarItemPlani';

$route['getDataCuotasPlan'] = 'cf_planificacion/C_plan_admin/getDataCuotasPlan';

$route['insertPlanifica']  = 'cf_planificacion/C_plan_admin/insertPlanifica';

$route['generarOcByPlan'] = 'cf_planificacion/C_plan_admin/generarOcByPlan';

$route['actualizarPlanAsig'] = 'cf_planificacion/C_plan_admin/actualizarPlanAsig';

$route['envioCotizacionMasivoManual'] =  'cf_servicios/C_integracion_sisego_web/envioCotizacionMasivoManual';

$route['openMdlDetSolPendPago'] = 'cf_liquidacion_mo/C_control_presupuestal_mo/openMdlDetSolPendPago';

//------* CONTROL DE EXCESO - Ivan Joel More Flores ------*//
$route['mControlExceso'] = 'cf_itemfault/C_control_exceso';
$route['ajaxTableData'] = 'cf_itemfault/C_control_exceso/filtrarTablaControlExceso';
$route['ajaxCreateExceso'] = 'cf_itemfault/C_control_exceso/CreateExceso';
$route['ajaxValidarExceso'] = 'cf_itemfault/C_control_exceso/validarControlPresupuestal';
//-----EVALUAR SISEGO-----//
$route['sisegoEvaluar'] = 'cf_plan_obra/C_evaluar_sisego';
$route['procesarSisego'] = 'cf_servicios/C_integracion_sisego_web/enviarItemPlanSisego';
$route['openMdlDetalleExceso'] = 'cf_control_presupuestal/C_control_presupuestal/openMdlDetalleExceso';
$route['certificarSolicitudOc'] 	        = 'cf_certificacion/C_creacion_oc/certificarSolicitudOc';


/*****************************ROUTE PAQUETIZADOS NO TOCAR 20.04.2020 *****************************/
$route['regMatEsta']   = 'cf_pqt_liquidacion_mat/C_pqt_reg_mat_x_esta';
$route['exParMat']     = 'cf_pqt_liquidacion_mat/C_pqt_reg_mat_x_esta/getExcelMateriales';
$route['upFiPoMat']    = 'cf_pqt_liquidacion_mat/C_pqt_reg_mat_x_esta/uploadMateriales';
$route['saMatXEsta']   = 'cf_pqt_liquidacion_mat/C_pqt_reg_mat_x_esta/saveMaterialesXEstacion';
$route['getMatpqt']     = 'cf_pqt_liquidacion_mat/C_pqt_reg_mat_x_esta/getMaterialesPqt';
/***************paquetizado*************/
$route['testCreatePoMOPqt']     = 'cf_pqt_gestion_obra_terminado/C_terminado_v2/savePoMo';
$route['sendValidatePartAdic']  = 'cf_pqt_gestion_obra_terminado/C_terminado_v2/sendValidatePartidasAdicionales';
$route['sendValidateRutas']     = 'cf_pqt_gestion_obra_terminado/C_terminado_v2/sendValidateRutas';
$route['getInfoRech']           = 'cf_pqt_gestion_obra_terminado/C_terminado_v2/getRechazadoByidSolicitud';
/*************** REGISTRO PO MO ************************************/
$route['poMoAdic']      = 'cf_pqt_gestion_obra_terminado/C_registro_partidas_adicionales_mo';
$route['exParMOPa']     = 'cf_pqt_gestion_obra_terminado/C_registro_partidas_adicionales_mo/getExcelPartidasMO';
$route['upFiPoMoPa']    = 'cf_pqt_gestion_obra_terminado/C_registro_partidas_adicionales_mo/uploadPOMO';
$route['saPoMoPa']      = 'cf_pqt_gestion_obra_terminado/C_registro_partidas_adicionales_mo/savePoMo';

$route['baVaPoPqt']             = 'cf_pqt_gestion_obra_terminado/C_bandeja_valida_po_pqt';
$route['getContPartPndtVal']    = 'cf_pqt_gestion_obra_terminado/C_bandeja_valida_po_pqt/getPartidasPdtValidacion';
$route['validarNivel1']         = 'cf_pqt_gestion_obra_terminado/C_bandeja_valida_po_pqt/validarPartidasNivel1';
$route['rejectSolAdPqt']        = 'cf_pqt_gestion_obra_terminado/C_bandeja_valida_po_pqt/rechazarSolicitud';
/****************************FIN PAQUETIZADOS NO TOCAR 20.04.2020 *****************************/
/****************************TRANFERENCIA SAP V2 *********************/
$route['sapfiv2'] = 'cf_tranferencias/C_tranferencia_sap_fija_v2';
$route['upsf1v2'] = 'cf_tranferencias/C_tranferencia_sap_fija_v2/uploadSj1';
$route['upsf2v2'] = 'cf_tranferencias/C_tranferencia_sap_fija_v2/uploadSj2';
$route['upPFV2']  = 'cf_tranferencias/C_tranferencia_sap_fija_v2/execPresupuestoFuntions';
$route['reloadAllReport'] = 'cf_tranferencias/C_tranferencia_wu_reload_reports';
/****************************FIN TRANFERENCIA SAP V2 21.04.2020 *****************************/

$route['openMdlDetConsultaPdtPago'] = 'cf_liquidacion_mo/C_bandeja_presupuestal_mo/openMdlDetConsultaPdtPago';
$route['validarAnulacionOc'] = 'cf_certificacion/C_creacion_oc/validarAnulacionOc';

$route['cambiarFlgRobotCoti'] = 'cf_cotizacion/C_bandeja_cotizacion_individual/cambiarFlgRobotCoti';

// Bandeja de Itemplan Madre
//$route['mBandejaPendiente'] = 'cf_itemplan/C_bandeja_oc_penditente';
//$route['getBandejaPer'] = 'cf_itemplan/C_bandeja_oc_penditente/consultaTablaBandejaOpex';
//$route['ocItemplanMadre'] = 'cf_itemplan_madre/C_creacion_oc';
//$route['filtrarTablaCOCmadre'] = 'cf_itemplan_madre/C_creacion_oc/filtrarTabaCOC';

// ITEMPLAN MADRE - BANDEJA OC
$route['mBandejaPendiente'] = 'cf_itemplan/C_bandeja_oc_penditente';
$route['getBandejaPer'] = 'cf_itemplan/C_bandeja_oc_penditente/consultaTablaBandejaOpex';
$route['ocItemplanMadre'] = 'cf_itemplan_madre/C_creacion_oc';
$route['validarAnulacionOcMadre'] = 'cf_itemplan_madre/C_creacion_oc/validarAnulacionOc';
$route['filtrarTablaCOCmadre'] = 'cf_itemplan_madre/C_creacion_oc/filtrarTabaCOC';
$route['getPtrByHGestionOCitemplamMadre'] = 'cf_itemplan_madre/C_creacion_oc/getPtrsByHojaGestion';
$route['valSolEdiOcItemplanMadre'] = 'cf_itemplan_madre/C_creacion_oc/validarSolicitudEdicionOC';
$route['certificarSolicitudOcItemplanMadre'] = 'cf_itemplan_madre/C_creacion_oc/certificarSolicitudOc';


$route['reSolOcItMadre'] = 'cf_itemplan_madre/C_registro_oc_solicitud';
$route['exParMOOCitMadre'] = 'cf_itemplan_madre/C_registro_oc_solicitud/getExcelPartidasMO';
$route['upFiPoMoOCitMadre'] = 'cf_itemplan_madre/C_registro_oc_solicitud/uploadPOMO';
$route['saPoMoOCitMadre'] = 'cf_itemplan_madre/C_registro_oc_solicitud/savePoMo';

// ITEMPLAN MADRE - BANDEJA Y CONSULTA
$route['banIMadre'] = 'cf_itemplan_madre/C_bandeja_registro';
$route['lstItemplanHijo'] = 'cf_itemplan_madre/C_bandeja_registro/hijosItemMadre';
$route['busqItemplaMadre'] = 'cf_itemplan_madre/C_bandeja_registro/consultaTablaItemplanMadre';
$route['updateCprio'] = 'cf_itemplan_madre/C_bandeja_registro/updateConPrioridad';
$route['getIP'] = 'cf_itemplan_madre/C_bandeja_registro/getEditItemplanMadre';
$route['updateSprio'] = 'cf_itemplan_madre/C_bandeja_registro/updateSinPrioridad';

// ITEMPLAN MADRE - SOLO CONSULTA
$route['banIMadreCon'] = 'cf_itemplan_madre/C_bandeja_consulta';
$route['busqItemplaMadreCon'] = 'cf_itemplan_madre/C_bandeja_consulta/consultaTablaItemplanMadre';
$route['gestionObraPublica'] = 'cf_itemplan_madre/C_gestion_obra_publica';
/** MODULO GESTION DE OBRAS PUBLICAS POR ITEMPLAN MADRE * *////
$route['gestionOPObraPublica'] = 'cf_itemplan_madre/C_gestion_obra_publica';
$route['saveCROPObraPublica'] = 'cf_itemplan_madre/C_gestion_obra_publica/saveCartaCotizacion';
$route['saveCROP2ObraPublica'] = 'cf_itemplan_madre/C_gestion_obra_publica/saveCartaRespuesta';
$route['saveCROP3ObraPublica'] = 'cf_itemplan_madre/C_gestion_obra_publica/saveDatosConvenio';
$route['saveKOffObraPublica'] = 'cf_itemplan_madre/C_gestion_obra_publica/ejecutarKickOff';
$route['saveCBObraPublica'] = 'cf_itemplan_madre/C_gestion_obra_publica/saveCartaBasica';

// ITEMPLAN MADRE - BANDEJA DE VALIDACION
$route['banIMadreVali'] = 'cf_itemplan_madre/C_bandeja_validacion';
$route['lstItemplanHijoVali'] = 'cf_itemplan_madre/C_bandeja_validacion/hijosItemMadre';
$route['itValidar'] = 'cf_itemplan_madre/C_bandeja_validacion/validarItemplan';
$route['busqItemplaMadreConVali'] = 'cf_itemplan_madre/C_bandeja_validacion/consultaTablaItemplanMadre';

// ITEMPLAN MADRE - BANDEJA DE OBSERVACIÓN
$route['banIMadreObs'] = 'cf_itemplan_madre/C_bandeja_observacion';
$route['lstItemplanHijoObs'] = 'cf_itemplan_madre/C_bandeja_observacion/hijosItemMadre';
$route['itObs'] = 'cf_itemplan_madre/C_bandeja_observacion/validarItemplan';
$route['busqItemplaMadreConObs'] = 'cf_itemplan_madre/C_bandeja_observacion/consultaTablaItemplanMadre';


/* * ************************** ITEMPLAN MADRE **************************** */
$route['getItemplanMadre'] = 'cf_plan_obra/C_reg_itemplan_madre';
$route['getSubProyectoItemMadre'] = 'cf_plan_obra/C_reg_itemplan_madre/getSubProyectoItemMadre';
$route['regItemPlanMadre'] = 'cf_plan_obra/C_reg_itemplan_madre/regItemPlanMadre';
$route['getPepItemplanMadre'] = 'cf_plan_obra/C_reg_itemplan_madre/getPEPitemplanMadre';
$route['getCmbItemplanMadre'] = 'cf_pqt_plan_obra/C_planobra/getCmbItemplanMadre';
$route['getRegItemOp'] = 'cf_pqt_plan_obra/C_registro_itemplan_op';
$route['createPlanobraOP'] = 'cf_pqt_plan_obra/C_registro_itemplan_op/createPlanobraOP';
$route['getItemplanMadreFactorMed'] = 'cf_pqt_plan_obra/C_registro_itemplan_op/getItemplanMadreFactorMed';

$route['createPlanObraFromSisegoForzarCreacion'] = 'cf_plan_obra/C_planobra/createPlanObraFromSisegoForzarCreacion';

$route['forzarITemSinCoti'] = 'cf_servicios/C_integracion_sisego_web/forzarITemSinCoti';
//------* CONTROL DE BLOQUEADOS - Ivan Joel More Flores ------*//
$route['tramaBloq'] = 'cf_panel_control/C_control_tramas_bloq';
$route['preciarioList'] = 'cf_mantenimiento/C_preciario_list';

$route['getCargaArchivoRobot'] = 'cf_cotizacion/C_carga_archivo_robot';

$route['insertCtoCotizacion'] = 'cf_cotizacion/C_carga_archivo_robot/insertCtoCotizacion';

$route['insertReservasCotizacion'] = 'cf_cotizacion/C_carga_archivo_robot/insertReservasCotizacion';

$route['insertEbcCotizacion'] = 'cf_cotizacion/C_carga_archivo_robot/insertEbcCotizacion';

$route['getFiltrarCostoPaquetizadoSimu'] = 'cf_cotizacion/c_cotizacion_alcance_robot/getFiltrarCostoPaquetizadoSimu';

$route['getPreciario2019'] = 'cf_mantenimiento/C_preciario_2019';

$route['filtrarTablaPreciario2019'] = 'cf_mantenimiento/C_preciario_2019/filtrarTablaPreciario2019';

$route['getSitiosArqueologicos'] = 'cf_cotizacion/c_cotizacion_alcance_robot/getSitiosArqueologicos';

$route['getMontoTemp'] = 'cf_servicios/C_integracion_sisego_web/getMontoTemp';

$route['getCtosNormalSimu'] = 'cf_cotizacion/c_cotizacion_alcance_robot/getCtosNormalSimu';
$route['getReservaSimu']    = 'cf_cotizacion/c_cotizacion_alcance_robot/getReservaSimu';
$route['getEbcSimu']        = 'cf_cotizacion/c_cotizacion_alcance_robot/getEbcSimu';

$route['reenviarCotizacion'] = 'cf_servicios/C_integracion_sisego_web/reenviarCotizacion';
$route['getTipoInc'] = 'cf_incidencias/C_gestion_incidencias/getTipoInc';

/**21.06.2020 czavala**/
$route['saPoMoOC'] 				= 'cf_certificacion/C_registro_oc_solicitud/createSOlOC';
$route['createPoPqtFull']    = 'cf_certificacion/C_registro_oc_solicitud/savePoMoTest';


$route['poMoAdicPqt']      = 'cf_control_presupuestal/C_registro_partidas_adicionales_mo_pqt';
$route['exParMOPaPqt']     = 'cf_control_presupuestal/C_registro_partidas_adicionales_mo_pqt/getExcelPartidasMO';
$route['upFiPoMoPaPqt']    = 'cf_control_presupuestal/C_registro_partidas_adicionales_mo_pqt/uploadPOMO';
$route['saPoMoPaPqt']      = 'cf_control_presupuestal/C_registro_partidas_adicionales_mo_pqt/savePoMo';

/**materiales nuevo flujo pq**/
$route['regMatEstaPqt']   = 'cf_control_presupuestal/C_pqt_reg_mat_x_esta_pqt';
$route['exParMatPqt']     = 'cf_control_presupuestal/C_pqt_reg_mat_x_esta_pqt/getExcelMateriales';
$route['upFiPoMatPqt']    = 'cf_control_presupuestal/C_pqt_reg_mat_x_esta_pqt/uploadMateriales';
$route['saMatXEstaPqt']   = 'cf_control_presupuestal/C_pqt_reg_mat_x_esta_pqt/saveMaterialesXEstacion';
$route['getMatpqtPqt']    = 'cf_control_presupuestal/C_pqt_reg_mat_x_esta_pqt/getMaterialesPqt';

$route['getPtrByItmPqt']    = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/getPtrsByItemPlanPqt';
$route['upMateriPqt'] 	    = 'cf_liquidacion/C_bandeja_pre_aprob_mo_2/updateMaterialesPqt';
$route['filTabValPqtAnc']   = 'cf_pqt_gestion_obra_terminado/C_bandeja_valida_po_pqt/filtraTabla';
$route['getExpeLiqui'] = 'cf_consultaZip/C_consultaZip/expediente_liquidacion';

$route['newTestCreateTerminado']    = 'cf_certificacion/C_registro_oc_solicitud/crearPoPqtAndPArtidaFerreteria';
$route['valProNiv2']  = 'cf_pqt_gestion_obra_terminado/C_terminado_v2/validarPropuestaNivel2';
$route['valProNiv2Ruta']  = 'cf_pqt_gestion_obra_terminado/C_terminado_v2/validarPropuestaNivel2Ruta';

/**nuevo czavala trama cancelacion sisego 26.06.2020***/
$route['cancelSisego'] = 'cf_servicios/C_integracion_sisego_web/canelarItemplanSisego';

$route['getActaCertificacion'] 	     = 'cf_certificacion/C_creacion_oc/getActaCertificacion';

/**masive adjudicacion**/
$route['masiveAdjuDica']  	= 'cf_certificacion/C_registro_oc_solicitud/masiveAdjudicacionByItemplaList';
$route['testValiPreliqui']     = 'cf_pqt_gestion_obra_pre_liquidado/C_pre_liquidacion/evaluarToPreliquidarObra';
$route['insertCtoCotizacionEdif'] = 'cf_cotizacion/C_carga_archivo_robot/insertCtoCotizacionEdif';
$route['generarExcelComparativaSisegoNodo'] = 'cf_extractor/C_extractor/generarExcelComparativaSisegoNodo';

/**bandeja de espera**/
$route['getBaEspe']  	= 'cf_pqt_plan_obra/C_bandeja_espera_itemplan';
$route['getBaValPqt']  	= 'cf_pqt_plan_obra/C_bandeja_validados_pqt';

$route['getEbcByDistritoByDistrito'] = 'cf_cotizacion/C_form_cotizacion_individual/getEbcByDistritoByDistrito';

$route['getBandejaEspera'] = 'cf_cotizacion/C_bandeja_espera';

$route['openModalLogOc'] = 'cf_pqt_plan_obra/C_consulta/openModalLogOc';

/**bandeja aprobacion opex**/
$route['liquiOpex'] = 'cf_liquidacion/C_liquidacion_opex';
$route['asigGrafoOpex'] = 'cf_liquidacion/C_liquidacion_opex/asignarGrafo';
/**fin bandeja aprobacion opex**/

$route['insertPartidasMasiva'] = 'cf_mantenimiento/C_bandeja_proy_est_partida/insertPartidasMasiva';

$route['getReporte2pdtValPqtCert']  = 'cf_extractor/C_extractor/generarReproteNum2CertificacionBanPdtValPqt';
$route['getReporte1LiquiCert']      = 'cf_extractor/C_extractor/generarReproteNum1Certificacion';
/**borrar ot actualizacion sirope**/
$route['delOTAC'] = 'cf_pqt_plan_obra/C_consulta/delOtActualizacion';
/**fin borrar ot actualizacion sirope**/
$route['sendValidateNoPqt']     = 'cf_pqt_gestion_obra_terminado/C_terminado_v2/sendValidateNoPqt';

$route['valProNiv2NoPqt']  = 'cf_pqt_gestion_obra_terminado/C_terminado_v2/validarPropuestaNivel2NoPqt';

$route['asignarDistanciaCotizacion'] = 'cf_servicios/C_integracion_sisego_web/asignarDistanciaCotizacion';

/**nuevo MASIVOS OC czavala 31.08.2020*/
$route['upMasiOc']      = 'cf_certificacion/C_registro_oc_solicitud_masivo';
$route['exParMOOCMas'] 	= 'cf_certificacion/C_registro_oc_solicitud_masivo/getExcelPartidasMO';
$route['upFiPoMoOCMas'] = 'cf_certificacion/C_registro_oc_solicitud_masivo/uploadPOMO';
$route['saPoMoOCMas'] 	= 'cf_certificacion/C_registro_oc_solicitud_masivo/regMasiveOcSol';

$route['upMasiOcDev']       = 'cf_certificacion/C_registro_oc_solicitud_masivo_dev';
$route['exParMOOCMasDev'] 	= 'cf_certificacion/C_registro_oc_solicitud_masivo_dev/getExcelPartidasMO';
$route['upFiPoMoOCMasDev']  = 'cf_certificacion/C_registro_oc_solicitud_masivo_dev/uploadPOMO';
$route['saPoMoOCMasDev'] 	= 'cf_certificacion/C_registro_oc_solicitud_masivo_dev/regMasiveOcSol';

$route['upMasiOcAnul']       = 'cf_certificacion/C_registro_oc_solicitud_masivo_anul';
$route['exParMOOCMasAnul'] 	 = 'cf_certificacion/C_registro_oc_solicitud_masivo_anul/getExcelPartidasMO';
$route['upFiPoMoOCMasAnul']  = 'cf_certificacion/C_registro_oc_solicitud_masivo_anul/uploadPOMO';
$route['saPoMoOCMasAnul'] 	 = 'cf_certificacion/C_registro_oc_solicitud_masivo_anul/regMasiveOcSol';

$route['upMasiOcCan']           = 'cf_certificacion/C_registro_oc_solicitud_masivo_cancela';
$route['exParMOOCMasCancela'] 	= 'cf_certificacion/C_registro_oc_solicitud_masivo_cancela/getExcelPartidasMO';
$route['upFiPoMoOCMasCancela']  = 'cf_certificacion/C_registro_oc_solicitud_masivo_cancela/uploadPOMO';
$route['saPoMoOCMasCancela'] 	= 'cf_certificacion/C_registro_oc_solicitud_masivo_cancela/regMasiveOcSol';

$route['upMasiOcCert']          = 'cf_certificacion/C_registro_oc_solicitud_masivo_cert';
$route['exParMOOCMasCert'] 	    = 'cf_certificacion/C_registro_oc_solicitud_masivo_cert/getExcelPartidasMO';
$route['upFiPoMoOCMasCert']     = 'cf_certificacion/C_registro_oc_solicitud_masivo_cert/uploadPOMO';
$route['saPoMoOCMasCert'] 	    = 'cf_certificacion/C_registro_oc_solicitud_masivo_cert/regMasiveOcSol';

$route['upMasiOcMen']           = 'cf_certificacion/C_registro_oc_solicitud_masivo_menu';

$route['getDescargaActasOcMasiva'] = 'cf_tranferencias/C_descarga_actas_oc_masiva';

$route['zipActasOc'] = 'cf_tranferencias/C_descarga_actas_oc_masiva/zipActasOc';

$route['getActaCertificacionMasivo'] = 'cf_tranferencias/C_descarga_actas_oc_masiva/getActaCertificacionMasivo';

$route['zipActasOcMasivo'] = 'cf_tranferencias/C_descarga_actas_oc_masiva/zipActasOcMasivo';
/************************************************************************/

/////////// IVAN MORE FLORES //////
$route['mPepOrigen'] = 'cf_presupuesto/C_pep_sap';
$route['ajaxPepOrigenBusqueda'] = 'cf_presupuesto/C_pep_sap/consulta_pep';
$route['getCountPartida'] = 'cf_pqt_obra_diseno/C_pqt_diseno/getCountPartida';
$route['updatePartidaOP'] = 'cf_pqt_obra_diseno/C_pqt_diseno/updateObra';
$route['genOcOpexOnline'] = 'cf_extractor/C_extractor/genOcOpexOnline';

/////////// IVAN MORE FLORES //////
$route['m_sisegos'] = 'cf_sisego/C_reporte_sisego';

//////////////////////////////////////////////////////////// FIN IVAN

$route['restaurarRobotVr'] = 'cf_servicios/C_integracion_rpa_bandeja_aprobacion/restaurarRobotVr';

/***validacion partidas integral 2020-09-07 czavala***/
$route['valInte']               = 'cf_crecimiento_vertical/C_validacion_integral';
$route['getDetEditPartCvInte']  = 'cf_crecimiento_vertical/C_validacion_integral/getMaterialesPartidasByItem';
$route['updatEPartInte'] 	    = 'cf_crecimiento_vertical/C_validacion_integral/updateMoPartidas';
$route['valObraInte']    	    = 'cf_crecimiento_vertical/C_validacion_integral/validarObrasIntegral';
/***********************************************************/

$route['getEditarCostosCotizacion'] = 'cf_cotizacion/C_consulta_cotizacion_individual/getEditarCostosCotizacion';
$route['actualizarCostosCotiPo'] = 'cf_cotizacion/C_consulta_cotizacion_individual/actualizarCostosCotiPo';

/****registro masivo ferreteri***/
$route['regMatFerrPqt'] = 'C_main_run/uploadMaterialesPqtMasivo';
$route['insertDataSimuladorCv'] = 'cf_cotizacion/c_cotizacion_alcance_robot/insertDataSimuladorCv';
$route['getDataSimulacionCv'] = 'cf_cotizacion/c_cotizacion_alcance_robot/getDataSimulacionCv';
$route['getDescargaRobotCv'] = 'cf_tranferencias/C_descarga_robot_cv';
$route['getInfoRobotCV'] = 'cf_tranferencias/C_descarga_robot_cv/getInfoRobotCV';

/****masivo de christian***/
$route['upMasiOcToPenCert']         = 'cf_certificacion/C_registro_oc_solicitud_masivo_cert_to_pndte';
$route['exParMOOCMasToPenCert'] 	= 'cf_certificacion/C_registro_oc_solicitud_masivo_cert_to_pndte/getExcelPartidasMO';
$route['upFiPoMoOCMasToPenCert']    = 'cf_certificacion/C_registro_oc_solicitud_masivo_cert_to_pndte/uploadPOMO';
$route['saPoMoOCMasToPenCert'] 	    = 'cf_certificacion/C_registro_oc_solicitud_masivo_cert_to_pndte/regMasiveOcSol';
/***********************************************************************************************************************/

$route['upMasiCreOcMen']           = 'cf_certificacion/C_creacion_solicitud_oc_menu';

$route['upMasiCreOcAnul']       = 'cf_certificacion/C_creacion_solicitud_anulacion_masivo';
$route['exParMOOCCreMasAnul'] 	= 'cf_certificacion/C_creacion_solicitud_anulacion_masivo/getExcelPartidasMO';
$route['upFiPoMoOCCreMasAnul']  = 'cf_certificacion/C_creacion_solicitud_anulacion_masivo/uploadPOMO';
$route['saPoMoOCCreMasAnul'] 	= 'cf_certificacion/C_creacion_solicitud_anulacion_masivo/regMasiveOcSol';

$route['upMasiOcCre']           = 'cf_certificacion/C_creacion_solicitud_creacion_masivo';
$route['exParMOOCMasCre'] 	    = 'cf_certificacion/C_creacion_solicitud_creacion_masivo/getExcelPartidasMO';
$route['upFiPoMoOCMasCre']      = 'cf_certificacion/C_creacion_solicitud_creacion_masivo/uploadPOMO';
$route['saPoMoOCMasCre'] 	    = 'cf_certificacion/C_creacion_solicitud_creacion_masivo/regMasiveOcSol';

$route['upMasiCreOcEdi']        = 'cf_certificacion/C_creacion_solicitud_edicion_masivo';
$route['exParMOOCCreMasEdi'] 	= 'cf_certificacion/C_creacion_solicitud_edicion_masivo/getExcelPartidasMO';
$route['upFiPoMoOCCreMasEdi']   = 'cf_certificacion/C_creacion_solicitud_edicion_masivo/uploadPOMO';
$route['saPoMoOCCreMasEdi']     = 'cf_certificacion/C_creacion_solicitud_edicion_masivo/regMasiveOcSol';

/***********************************************/
$route['getCvReportEdi'] = 'cf_extractor/C_extractor/generarReporteVerticarEdicion';
/************* reportes SISEGOS *************/
$route['repSisegosV']      = 'cf_reportes_v/C_reporte_sisego_ejecutados';
$route['makeExcelDetalle'] = 'cf_reportes_v/C_reporte_sisego_ejecutados/generarCsvDetalleReporte';
$route['getCvReportEdi'] = 'cf_extractor/C_extractor/generarReporteVerticarEdicion';
$route['getDetTermSise']      = 'cf_reportes_v/C_reporte_sisego_ejecutados/getDetalleTerminados';
$route['makeExcelDetallePie'] = 'cf_reportes_v/C_reporte_sisego_ejecutados/generarCsvDetallePie';
$route['makeExcelDetalleTerm'] = 'cf_reportes_v/C_reporte_sisego_ejecutados/generarCsvDetalleReporteDetalleTerminados';

/* MODULO TRANSPORTE */
$route["moduloTransporte"]           = "cf_modulo_transporte/C_inicio_transporte";
$route["regItemTransporte"] 		 = "cf_modulo_transporte/C_registro_itemplan_transporte";
$route['getCotizacionTransporte']    = "cf_modulo_transporte/C_cotizacion_transporte";
$route["registroItemplanTransporte"] = "cf_modulo_transporte/C_registro_itemplan_transporte/registroItemplanTransporte";
$route["getDetallePoTransporte"]     = 'cf_modulo_transporte/C_detalle_po_transporte';
$route["getRegMoTransporte"]      	 = 'cf_modulo_transporte/C_registro_mo_transporte';
$route["getAprobTransporte"]     	 = 'cf_modulo_transporte/C_aprobacion_transporte';
$route['aprobarTransporte']          = 'cf_modulo_transporte/C_aprobacion_transporte/aprobarTransporte';
$route['filtrarTransporteAprob'] 	 = 'cf_modulo_transporte/C_aprobacion_transporte/filtrarTransporteAprob';
$route['consultaCotiTransp'] 		 = 'cf_modulo_transporte/C_aprobacion_transporte/consultaCotiTransp';
$route['getBandejaSolOcTransp'] 	 = 'cf_modulo_transporte/C_bandeja_solicitud_oc_transporte';
$route['valSolEdiOCTransporte'] 	 = 'cf_modulo_transporte/C_bandeja_solicitud_oc_transporte/valSolEdiOCTransporte';
$route['removeOnePtrFromHojaGestionTransp'] = 'cf_modulo_transporte/C_bandeja_solicitud_oc_transporte/removeOnePtrFromHojaGestionTransp';
$route['getPtrByHGestionOCTransp'] 	 = 'cf_modulo_transporte/C_bandeja_solicitud_oc_transporte/getPtrByHGestionOCTransp';
$route['filtrarTabaCOCTransp'] 		 = 'cf_modulo_transporte/C_bandeja_solicitud_oc_transporte/filtrarTabaCOCTransp';
$route['certificarSolicitudOcTransp'] = 'cf_modulo_transporte/C_bandeja_solicitud_oc_transporte/certificarSolicitudOcTransp';
$route['validarAnulacionOcTransp']    = 'cf_modulo_transporte/C_bandeja_solicitud_oc_transporte/validarAnulacionOcTransp';
$route['getRegistroOcTransporte'] 	  = 'cf_modulo_transporte/C_registro_oc_transporte';
$route['getExcelOcTransporte'] 		  = 'cf_modulo_transporte/C_registro_oc_transporte/getExcelOcTransporte';
$route['cargarExcelOcTransp']  		  = 'cf_modulo_transporte/C_registro_oc_transporte/cargarExcelOcTransp';
$route['registrarOCTransporte'] 	  = 'cf_modulo_transporte/C_registro_oc_transporte/registrarOCTransporte';
$route['getLiquidacionOcTransp'] 	  = 'cf_modulo_transporte/C_liquidacion_obra_transporte';
$route['filtrarTablaLiquiTransp'] 	  = 'cf_modulo_transporte/C_liquidacion_obra_transporte/filtrarTablaLiquiTransp';
$route['getBandejaValidaTransp'] 	  = 'cf_modulo_transporte/C_bandeja_validacion_transporte';
$route['filtrarTablaValidTransp'] 	  = 'cf_modulo_transporte/C_bandeja_validacion_transporte/filtrarTablaValidTransp';
$route['ejecValidacionTransp'] 		  = 'cf_modulo_transporte/C_bandeja_validacion_transporte/ejecValidacionTransp';
$route['getBandejaCertificacionTransp'] = 'cf_modulo_transporte/C_bandeja_certificacion_transporte';
$route['filtrarCertificacionTransp']  = 'cf_modulo_transporte/C_bandeja_certificacion_transporte/filtrarCertificacionTransp';
$route['getDetTranspIp'] 			  = 'cf_modulo_transporte/C_bandeja_certificacion_transporte/getDetTranspIp';
$route['zipItemPlanTransp'] 		  = 'cf_modulo_transporte/C_bandeja_certificacion_transporte/zipItemPlanTransp';
$route["generarPOTransporte"]      	  = 'cf_modulo_transporte/C_registro_mo_transporte/generarPOTransporte';
$route["getDetallePoInfo"]      = 'cf_modulo_transporte/C_detalle_po_transporte/getDetallePoInfo';
/*******************************************************************************************************************/
/**cambio de eecc masivo**/
$route['changeecmas']      = 'cf_pqt_plan_obra/C_cambiar_eecc_subpro_itemplan_masivo';
$route['exChangeEecc'] 	= 'cf_pqt_plan_obra/C_cambiar_eecc_subpro_itemplan_masivo/getExcelPartidasMO';
$route['upChangueEecc']  = 'cf_pqt_plan_obra/C_cambiar_eecc_subpro_itemplan_masivo/uploadPOMO';
$route['saChangueEecc'] 	= 'cf_pqt_plan_obra/C_cambiar_eecc_subpro_itemplan_masivo/regMasiveOcSol';

$route['getPostesMap'] = 'cf_tranferencias/C_descarga_robot_cv/getPostesMap';

$route["getModuloTransporte"] = "cf_bucle_2019/C_modulos_bucle/moduloTransporte";

$route['getCmbEmpresaColabByContratoPadre'] = 'cf_plan_obra/C_planobra_pi/getCmbEmpresaColabByContratoPadre';

$route['getDataEstacionesLiquidacion'] = 'cf_pqt_gestion_obra_pre_liquidado/C_pre_liquidacion/getDataEstacionesLiquidacion';

$route["actualizarPo"] = 'cf_pqt_plan_obra/C_consulta/actualizarPo';

$route["getPoByItemplan"] = "cf_pqt_plan_obra/C_consulta/getPoByItemplan";

$route["ingresarEvidenciaLiquiTransp"] = "cf_pqt_plan_obra/C_consulta/ingresarEvidenciaLiquiTransp";

$route["getDetallePoEdit"] = "cf_pqt_plan_obra/C_consulta/getDetallePoEdit";

$route['rechazarItemplanValid'] = 'cf_plantaInterna/C_bandeja_validacion/rechazarItemplanValid';

$route["getNoEditPo"] = "cf_pqt_plan_obra/C_consulta/getNoEditPo";


$route['getCmbDatoConfigOpex'] = 'cf_plan_obra/C_planobra_pi/getCmbDatoConfigOpex';


$route['getCmbContratoHijoByCPEECC'] = 'cf_plan_obra/C_planobra_pi/getComboContrato';
$route['getCmbContratoPadreBySubProy'] = 'cf_plan_obra/C_planobra_pi/getCmbContratoPadre';

$route['regEvidenciaIP'] = 'cf_pqt_plan_obra/C_consulta/registrarEvidencias';
$route['habilitarCargaEvi'] = 'cf_plantaInterna/C_bandeja_validacion/habilitarCargaEvidencia';


$route['ajaxCombinatoriaOpex'] = 'cf_itemplan/C_configOpex/selectEventoOpexSub';
$route['ajaxBolsapepOpex'] = 'cf_itemplan/C_configOpex/selectEventoOpexBolsaPep';
$route['ajaxCorrelativoPEP'] = 'cf_itemplan/C_configOpex/selectEventoPepCorrelativo';

