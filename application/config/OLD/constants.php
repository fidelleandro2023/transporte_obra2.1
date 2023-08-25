<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this settingn
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
// CONSTANTES DE CONFIGURACION
define('ANIO_CREATE_ITEMPLAN' , '22');
define('ANIO_CREATE_PO'       , '2022');
define('ID_FASE_ANIO_CREATE_ITEMPLAN' , '7');



defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       https://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       https://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       https://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS_SISEGO') OR define('EXIT_SUCCESS_SISEGO', "0"); // no errors
defined('EXIT_ERROR_SISEGO')   OR define('EXIT_ERROR_SISEGO', "1"); // generic error
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//ESTADO GRAFOS
define('ESTADO_SIN_GRAFO'    , '0');
define('ESTADO_CON_GRAFO_TEMPORAL'    , '1');
define('ESTADO_CON_GRAFO_ULTILIZADO'    , '2');
define('ESTADO_PRE_APROB'    , '003');
define('ESTADO_PRE_APROB_EECC'    , '001');
define('ESTADO_PRE_APROB_002'    , '002');
define('ESTADO_APROB_01'    , '01');
define('FROM_BANDEJA_APROBACION'    , '2');
define('FROM_BANDEJA_PRE_APROBACION'    , '1');
define('FROM_BANDEJA_PRE_APROBACION_DISENO'    , '3');


//permisos
define('ID_PERMISO_PADRE_PLAN_DE_OBRA'    , '10');
define('ID_PERMISO_HIJO_BANDEJA_PRE_APROB'    , '12');
define('ID_PERMISO_HIJO_BANDEJA_APROB'    , '11');
define('ID_PERMISO_HIJO_EXTRACTOR'    , '28');
define('ID_PERMISO_HIJO_CONSULTAS'    , '13');
define('ID_PERMISO_HIJO_DETALLE_OBRA'    , '29');

define('ID_PERMISO_PADRE_BANDEJAS'    , '16');

define('ID_PERMISO_PADRE_REPORTES_V'    , '14');
define('ID_PERMISO_HIJO_REPORTE_V'    , '18');
define('ID_PERMISO_HIJO_MO_REPORTE_V'    , '23');
define('ID_PERMISO_HIJO_SEGUIMIENTO_PDO'    , '17');

define('ID_PERMISO_PADRE_TRANFERENCIAS'            ,'19');
define('ID_PERMISO_HIJO_TRANFERENCIA_WU'           ,'21');
define('ID_PERMISO_HIJO_CARGA_MASIVA_PO'           ,'22');
define('ID_PERMISO_HIJO_CARGA_MASIVA_DETALLE_PLAN' ,'27');
define('ID_PERMISO_HIJO_TRANSFERENCIA_SIOM'        ,'171');

define('ID_PERMISO_PADRE_MANTENIMIENTO'    ,'24');
define('ID_PERMISO_HIJO_SUBPROY_PEP_GRAFO' ,'25');
define('ID_PERMISO_HIJO_SUBPROY_PEP_GRAFO_LITE' ,'104');


define('ID_ESTACIONAREA_MAT_COAX'    , '3');
define('ID_ESTACIONAREA_MAT_COAX_OC' , '5');
define('ID_ESTACIONAREA_MAT_FUENTE'  , '7');
define('ID_ESTACIONAREA_MAT_FO'      , '9');
define('ID_ESTACIONAREA_MAT_FO_OC'   , '11');
define('ID_ESTACIONAREA_MAT_ENER'    , '13');

define('NUM_COLUM_TXT_WEB_UNI', '35');
define('WU_FILE_PATH' , './uploads/wu/');


define('NUM_COLUM_TXT_MASIVO_PLANOBRA', '18');
define('NUM_COLUM_TXT_MASIVO_DETALLEPLAN', '3');

define('PATH_FILE_UPLOAD_PO_SUCCESS', 'uploads/po/success.csv');
define('PATH_FILE_UPLOAD_PO_ERROR', 'uploads/po/error.csv');

define('DATA_FROM_ITEMPLAN_PEP2_GRAFO', '1');
define('DATA_FROM_SISEGO_PEP2_GRAFO', '2');
define('DATA_FROM_PEP2_GRAFO', '3');


define('NUM_COLUM_TXT_CARGA_PEP2_GRAFO', '2');
define('NUM_COLUM_TXT_CARGA_SISEGO_PEP2_GRAFO', '3');
define('NUM_COLUM_TXT_CARGA_ITEM_PEP2_GRAFO', '4');

define('ID_PERMISO_HIJO_TRANFERENCIA_SAP_FIJA'    , '32');
define('ID_PERMISO_HIJO_TRANFERENCIA_SAP_COAXIAL'    , '31');
define('ID_PERMISO_HIJO_BANDEJA_PRE_CERTIFICACION'    , '33');
define('ID_PERMISO_HIJO_BANDEJA_PRE_CERTIFICACION_II'    , '58');
define('ID_PERMISO_HIJO_CON_BANDEJA_PRE_CERTIFICACION'    , '36');
define('ID_PERMISO_HIJO_BANDEJA_CONSULTA_PRE_CERTIFICACION_II' , '61');


define('PATH_FILE_UPLOAD_SAP_FIJA_EDIT', 'uploads/sap/fija_edit.txt');
define('PATH_FILE_UPLOAD_SAP_COAXIAL_EDIT', 'uploads/sap/coaxial_edit.txt');
define('NUM_COLUM_TXT_SAP_FIJA'    , '19');
define('NUM_COLUM_TXT_SAP_COAXIAL'    , '23');
define('FROM_SAP_FIJA'    , '1');
define('FROM_SAP_COAXIAL'    , '2');


//
define('PENDIENTE_NO_TERM'    , '1');
define('APROBADO_NO_TERM'    , '2');
define('APROBADO_TERM'    , '3');
define('NO_TIENE_PTR'    , '4');
define('CON_PTR'    , '5');
define('TERMINADOS_ALL'    , '6');
define('ID_PERMISO_HIJO_CUADRO_DE_MANDO'    , '37');
define('ID_PERMISO_HIJO_CUADRO_DE_MANDO_I'    , '39');
define('ID_PERMISO_HIJO_MAN_CENTRAL'    , '40');

define('ID_PERMISO_HIJO_CARGA_UPDATE_FECH', '41');
define('NUM_COLUM_TXT_MASIVO_UPDATEFECH', '3');

define('ID_PERMISO_HIJO_GESTIONAR_PO', '42');
define('ID_PERMISO_HIJO_MANTE_PROYECTO', '43');
define('ID_PERMISO_HIJO_REPORTE_BANDEJA_APROB', '44');

define('ID_PERMISO_HIJO_LIQUIDADOR_MASIVO_CRI'    , '45');
define('ID_PERMISO_HIJO_SEGUIMIENTO_AVANCE_PO', '46');
define('NUM_COLUM_TXT_LIQUIDADOR_MASIVO', '2');
define('ID_PERMISO_HIJO_BANDEJA_PRE_APROB_DISENO', '48');
define('ID_PERMISO_HIJO_BANDEJA_PRE_CERTI_DISENO', '49');


define('ID_PERMISO_HIJO_AREA_ESTACION_CRI'    , '51');
define('ID_PERMISO_HIJO_GESTIONAR_PO_II'    , '53');


 define('ID_PERMISO_PADRE_GESTION_VR'      , '130');

////////////////////// es tod de planta interna ///////////
///
define ('ID_PERMISO_PADRE_DISENO','50');

define('ESTADO_01_TEXTO', '01 - APROBADA VALORIZADA');
define('ESTADO_02_TEXTO', '02 - VALORIZADA CON VALE DE RESERVA');

define ('ID_PERMISO_PADRE_PLANTA_INTERNA','54');
define('ID_PERMISO_HIJO_BANDEJA_REGISTRO_PTR_INTERNA', '55');
define('ID_PERMISO_HIJO_DETALLE_PLANTA_INTERNA', '56');
define('ID_PERMISO_HIJO_BANDEJA_APROBACION_PLANTA_INTERNA', '59');
define('ID_PERMISO_HIJO_BANDEJA_EDITAR_PTR', '60');
define('ID_PERMISO_HIJO_BANDEJA_COTIZACION_PLANTA_INTERNA', '96');
define('ID_PERMISO_HIJO_BANDEJA_PENDIENTE_PIN', '65');
define('ESTADO_PLAN_PRE_DISENO', '1');
define('ESTADO_PLAN_PRE_REGISTRO', '8');
define('ESTADO_PLAN_DISENO', '2');
define('ESTADO_PLAN_PDT_OC', '24');
define('ESTADO_PLAN_EN_OBRA', '3');
define('ID_PERMISO_HIJO_BANDEJA_RECHAZADAS', '103');
define('ID_PERMISO_HIJO_BANDEJA_CERTIFICACION', '105');
define('ID_PERMISO_HIJO_BANDEJA_CERTIFICACION_II', '227');

define('ID_PERMISO_HIJO_BANDEJA_VALIDACION', '99');

define('ID_PERMISO_HIJO_BANDEJA_PRE_CERTIFICACION_II_CONSULTA'    , '61');

////////////////////// es tod de planta interna ///////////
define('PATH_FILE_UPLOAD_EXTRACTOR_LIQUIDACION', 'download/liquidacion/certificacionCSV.csv');
define('PATH_FILE_UPLOAD_EXTRACTOR_TORO', 'download/toro/toroCSV.csv');
define('PATH_FILE_UPLOAD_DETALLE_PLAN', 'download/detalleplan/detalleplanCSV.csv');
/*****MIGUEL RIOS EXTRACTOR VALE RESERVA 18052018*****/
define('PATH_FILE_UPLOAD_VALE_RESERVA', 'download/valereserva/valereservaCSV.csv');


/************************NUEVO miguel rios 01052018****************************/
define('ID_PERMISO_HIJO_REGIND_OBRA'    , '30');
define('ID_PERMISO_HIJO_PERMISO_PERFIL' , '62');
/************************NUEVO MIGUEL RIOS 05052018**************************/
define('ID_PERMISO_HIJO_ITEM_PLAN_PTR_PRIMER_APROB','66');


/////////////////////
define('ID_PERMISO_HIJO_BANDEJA_ADJUDICACION'    , '63');
define('ID_PERMISO_HIJO_BANDEJA_EJECUCION'    , '64');
define('ESTADO_PLAN_DISENO_EJECUTADO', '7');



//@ESTADO_PLAN
define('ID_ESTADO_PRE_DISENIO'      , '1');
define('ID_ESTADO_DISENIO'          , '2');
define('ID_ESTADO_PLAN_EN_OBRA'     , '3');
define('ID_ESTADO_TERMINADO'        , '4');
define('ID_ESTADO_CERRADO'          , '5');
define('ID_ESTADO_CANCELADO'        , '6');
define('ID_ESTADO_DISENIO_EJECUTADO', '7');
define('ID_ESTADO_PRE_REGISTRO'     , '8');
define('ID_ESTADO_PRE_LIQUIDADO'    , '9');
define('ID_ESTADO_TRUNCO'           , '10');
define('ID_ESTADO_DISENIO_PARCIAL'  , '11');

//@ESTACION_TB
define('ID_ESTACION_DISENIO'          , '1');
define('ID_ESTACION_COAXIAL'          , '2');
define('ID_ESTACION_OC_COAXIAL'       , '3');
define('ID_ESTACION_FUENTE'           , '4');
define('ID_ESTACION_FO'               , '5');
define('ID_ESTACION_OC_FO'            , '6');
define('ID_ESTACION_ENERGIA'          , '7');
define('ID_ESTACION_MULTIPAR'         , '8');
define('ID_ESTACION_INS_TROBA'        , '9');
define('ID_ESTACION_INTEGRACION_TROBA', '10');
define('ID_ESTACION_PIN'              , '11');
define('ID_ESTACION_PART_OPTICA'      , '12');
define('ID_ESTACION_UM'               , '13');
define('ID_ESTACION_AC_CLIENTE'       , '14');
define('ID_ESTACION_RETIRO_CABLE'     , '18');
define('ID_ESTACION_RUTA'             , '19');
define('ID_ESTACION_TRANSPORTE'       , '24');

//@TIPO PLANTA
define('ID_TIPO_PLANTA_EXTERNA', '1');
define('ID_TIPO_PLANTA_INTERNA', '2');


//CRECIMIENTO VERTICAL
define('ID_PROYECTO_CRECIMIENTO_VERTICAL' , '21');
define('ID_PERMISO_HIJO_PRE_REGISTRO_CV'    , '69');//69
define('ID_PERMISO_BANDEJA_APROB_CV' , '70');//70
define('ID_PERMISO_PADRE_CV' , '71');//70
define('ESTADO_CV_APROBADO' , '1');
define('ESTADO_CV_RECHAZADO' , '2');
define('ID_PERMISO_HIJO_BANDEJA_DET_CERT_CV' , '131');
define('ID_PERMISO_HIJO_BANDEJA_CERT_CV' , '97');

//***23052018 restauracion***//
define('ID_PERMISO_PADRE_INSPECCIONES' , '68');
define('ID_PERMISO_HIJO_REGISTRO_FICHA' , '67');
/*********************************************/


define('ID_PERMISO_HIJO_EDIT_CV' , '75');
define('ID_PERMISO_HIJO_BANDEJA_EDIT' , '76');

//////////////////////////// ficha tecnica
define('FICHA_TECNICA_APROBADA', '1');
define('FICHA_TECNICA_RECHAZADA', '2');


/*******************miguel rios vale reserva 31052018******************/
define('ID_PERMISO_PADRE_VALERESERVA', '77');
define('ID_PERMISO_HIJO_VALERESERVA' , '78');
define('ID_PERMISO_HIJO_VALERESERVA_LOAD' , '79');
define('ID_PERMISO_HIJO_VALERESERVA_REPORTE' , '80');
define('NUM_COLUM_TXT_VALERESERVA'   , '5');
define('NUM_COLUM_TXT_VALERESERVA_LOAD'   , '15');
/********************MIGUEL RIOS VALE RESERVA 11062018*********/
define('PATH_FILE_UPLOAD_REWRITE_VR', 'uploads/valereservaload/valereservarewrite.txt');
define('PATH_FILE_UPLOAD_VR_CON_ESTADO', 'download/valereserva/extractor/VRconEstadosCSV.csv');
/******************************************************************/

///////////////////////////////////////////////////
define('ID_PERMISO_HIJO_TERMINO_FICHA' , '81');

///////////////////////////////////////////////////
define('TIPO_fICHA_COAXIAL_GENERICO' , '1');
define('TIPO_fICHA_FO_FTTH' , '2');
define('TIPO_fICHA_FO_SISEGOS_SMALLCELL_EBC' , '3');
define('CANTIDAD_COLUMNAS_TIPO_fICHA_FO_FTTH' , '12');

/////////////////////////////////////////////CONSTANTES FICHA TECNICA
define('FICHA_COAXIAL_GENERICA' , '1');
define('FICHA_FO_FTTH_Y_OP' , '2');
define('FICHA_FO_SISEGOS_SMALLCELL_EBC' , '3');

//ID_PROYECTOS
define('ID_PROYECTO_SISEGOS' , '3');
define('ID_PERMISO_HIJO_CONSULTAS_DISENO' , '84');
define('ID_PROYECTO_MOVILES' , '2');

define('ID_SUB_PROYECTO_ACELERACION_MOVIL' , '171');

//////////////////////////////////////////edicion lite de plan obra 
define('ID_PERMISO_HIJO_EDIT_OBRA'    , '85');

/////////////////////////GET INFO SISEGO PLAN OBRA//////////////////////////////
define('ID_TIPO_OBRA_CREACION_NAP', '1');
define('ID_TIPO_OBRA_FO_OSCURA', '2');
define('ID_TIPO_OBRA_TRASLADO', '3');
define('ID_TIPO_OBRA_FO_TRADICIONAL', '4');
define('ID_TIPO_OBRA_FROM_DISENIO', '1');
define('ID_TIPO_OBRA_FROM_EJECUCION', '2');

///////////////////////////////////////////////////////////////////////
define('PORCENTAJE_MINIMO_CV_TO_OBRA', '50');//PORCENTAJE PARA QUE EL ITEMPLAN PASE EN OBRA PARA CRECIMIENTO VERTICAL.

//EMPRESA ID EMPRESAS COLABORADORAS
define('ID_EECC_CAMPERU', '8');
define('ID_EECC_QUANTA', '7');
define('ID_EECC_HUAWEI', '9');
define('ID_EECC_CALATEL', '5');
define('ID_EECC_TDP'    , '6');

define('ID_SUB_PROYECTO_CV_RESIDENCIA_FTTH', '97');
define('ID_SUB_PROYECTO_CV_BUCLE', '96');
define('ID_SUB_PROYECTO_CV_INTEGRAL', '97');


//////gestion de requerimientos //////////////////////////////////
define('ID_PERMISO_PADRE_GESTIOREQ'  ,'88');
define('ID_PERMISO_HIJO_GESTIOREQ' , '89');
define('ID_PERMISO_HIJO_BANDEJA_GESTIOREQ' , '90');


define('ORIGEN_SINFIX', '2');
define('ORIGEN_WEB_PO', '1');

define('ID_PERMISO_HIJO_REGINDPI_OBRA','92');

define('TIPO_FICHA_CV_FTTH' , '4');
define('FICHA_FO_CV' , '4');

define('ID_FASE_2017' , '4');
define('ID_FASE_2018' , '2');
define('ID_FASE_2019' , '5');
define('ID_FASE_2020' , '6');
define('ID_FASE_2021' , '7');
define('ID_FASE_2022' , '8');
define('ID_FASE_2023' , '9');

define('NUMERO_HORAS_DECLARACION_JURADA_AUTOMATICA' , '24');
define('CREAR_REGISTRO' , '0');
define('EDITAR_REGISTRO' , '1');

define('FLG_TIPO_FORM_SISEGO','1');
define('FLG_TIPO_FORM_OB_PUBLICAS','2');

define('ID_CABLEADO_EDIFICIOS_CV','21');


/////////ayuda/////////////////////////////////
define('ID_PERMISO_PADRE_HELP','93');
define('ID_PERMISO_HIJO_HELPCARTILLA','94');

define('FICHA_FO_OBRAS_PUBLICAS', '5');

//////////////////////
define('ESTADO_ESTACION_DISENO_EJECUTADO' , '3');

//PARTIDAS CV
define('ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO' , '1');
define('ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_CABLE' , '2');
define('ITEM_PARTIDA_CABLEADO_EXTERNO_TENDIDO_EN_MICRO' , '3');
define('ITEM_PARTIDA_UNIDAD_SINGULAR_DE_OBRA_CTO' , '12');
define('ITEM_DISENO_ATENCION_EDIFICIOS' , '5');
define('ITEM_SOPORTE_Y_PRUEBAS_SERVICIOS' , '4');
define('ITEM_UNIDAD_SINGULAR_OBRA' , '6');
define('ITEM_CONSTRUIR_CAMARA_REGISTRO' , '11');
define('ITEM_PARTIDA_CABLEADO_INTERIOR_DE_EDIFICIO_ADICIONAL' , '13');
//////// EDICION ESTADO PLAON OBRA
define ('ID_PERMISO_HIJO_EDIT_ESTADO_OBRA','95');


define('FLG_RECHAZADO' , '1');
define('FLG_APROBADO'  , '0');

////////// reporte certificacion 2
define ('ID_PERMISO_HIJO_ITEM_PLAN_CERT2','98');

define('ID_PERMISO_HIJO_REPORTE_CERTI_CV','102');
define('DIAS_PRESUPUESTO_GRAFICO_LINEAS','7');

define('FLG_SOLICITUD_CANCELACION' , '0');
define('FLG_CANCELACION_CONFIRMADA', '1');
define('FLG_TRUNCA_CONFIRMADA'     , '2');

//////// ruta descarga archivo plan obra/////////////
define('PATH_FILE_UPLOAD_PLAN_OBRA','download/extractor/PlanObra.csv');
define ('PATH_FILE_UPLOAD_PO_DISENO','download/extractor/PlanObraDiseno.csv');

define('FLG_ACTIVO'   , '1');
define('FLG_INACTIVO' , '0');

// ID EECC
define('ID_EECC_COBRA', '1');
define('ID_EECC_LARI', '2');
define('ID_EECC_DOMINION', '3');
define('ID_EECC_EZENTIS', '4');
define('ID_EECC_COMFICA', '10');
define('ID_EECC_LITEYCA', '11');
//CERTIFICACION MO
define('CERTIFICACION_MO_CON_PRESUPUESTO', '1');
define('CERTIFICACION_MO_SIN_PRESUPUESTO', '2');
define('CERTIFICACION_MO_SIN_CONFIGURACION_SUBPROYECTO_PEP', '3');
define('CERTIFICACION_MO_SIN_ITEMPLAN_ASOCIADO', '4');
define('ID_PERMISO_HIJO_BANDEJA_RESUMEN_PO', '175');

define('CERTIFICACION_MO_CON_ORDEN_COMPRA', '5');
define('FROM_GESTION_VENTANILLA_UNICA', '2');
define('FROM_GESTION_INGENIERIA', '1');
define('FROM_CERTIFICADO_MO', '3');


define('ID_PERMISO_HIJO_BANDEJA_CERTI_MO'    , '108');
define('ID_PERMISO_PADRE_CERTIFICACION_MO'   , '109');
define('ID_PERMISO_HIJO_BANDEJA_ALARMA_MO'   , '110');
define('ID_PERMISO_HIJO_BANDEJA_LIBERAR_PTR' , '124');
define('ID_PERMISO_HIJO_SOLICITUD_VR'       , '125');
define('ID_PERMISO_HIJO_BANDEJA_ATENCION_SOLICITUD_VR', '129');
define('ID_PERMISO_HIJO_BANDEJA_SOLICITUD_VR'         , '128');
define('ID_PERMISO_HIJO_REPORTE_CERTI_CV_MO_PRESU' , '158');
define('ID_PERMISO_HIJO_REPORTE_CERTI_CV_MO_PRESU_SUBPRO' , '159');
define('ID_PERMISO_HIJO_BANDEJA_MANT_SUB_ACT', '111');
define('ID_PERMISO_HIJO_VALERESERVA_EECC'  , '112');
define('ID_PERMISO_HIJO_BANDEJA_REPORTE'   , '142');
define('ID_PERMISO_PADRE_COTIZACION', '113');
define('ID_PERMISO_HIJO_REGISTRO_COTIZACION'  , '114');
define('ID_PERMISO_HIJO_VALIDAR_COTIZACION'  , '115');


define ('ID_PERMISO_PADRE_GESTION_ERC','116');

define('ID_PERMISO_HIJO_BOLSA_PRESUPUESTO' , '117');
define('ID_PERMISO_HIJO_APROBACION_RETIRO' , '118');
define('ID_PERMISO_HIJO_SOLICITUD_RETIRO' , '119');
define('ID_PERMISO_HIJO_LIQUIDACION_RETIRO' , '121');
define('ID_PERMISO_HIJO_VALIDACION_RETIRO' , '122');


//////////////////////////////////////01102018////////////////////////////
define('ID_PERMISO_HIJO_EXTRACTOR_INTERNO'  , '120');
define('PATH_FILE_UPLOAD_EVIDENCIA_SIZE'  , 'download/extractor/ArchivosEvidencia.csv');
define('TAMANIO_MAX_EVIDENCIA_MB'  , '500');

define('ID_PERMISO_HIJO_ITEM_CV_ANIO_CONST'  , '123');
define('ID_PERMISO_HIJO_STATUS_CERTI'  , '126');
///////////////////////////////////////////////////////////////////////

define('CANTIDAD_COLUMNAS_ORDEN_COMPRA_TXT'  , '3');
define('ID_PERMISO_HIJO_CARGA_MASIVA_OC'  , '127');

define('FLG_TIPO_SOLICITUD_ADICIONAR' , '1');
define('FLG_TIPO_SOLICITUD_ANULAR'    , '2');
define('FLG_TIPO_SOLICITUD_MODIFICAR' , '3');
define('FLG_TIPO_SOLICITUD_DEVOLUCION', '4');

define('FLG_SELECCIONO_MATERIAL'   , 1);
define('FLG_NO_SELECCIONO_MATERIAL', 2);
define('FLG_MATERIAL_RECHAZADO'    , 0);

define('ID_PERMISO_HIJO_CARGA_MASIVA_SIROPE', 133);

define('ID_EECC_NA', '0');


define('ID_PERMISO_HIJO_EDITAR_SUBPROYECTO_CV' , '134');
define('ID_PERMISO_HIJO_EDITAR_SUBPROYECTO_MASIVO' , '141');
define('ID_PERMISO_HIJO_BANDEJA_ALARMA_CV' , '143');

define('ID_PERMISO_HIJO_REPORTE_BA_VR' , '135');

define('FLG_CONFIRMADO', 1);
define('FLG_CANCELADO', 2);

define('ID_PERMISO_PADRE_AGENDAMIENTO'   , '136');

define('ID_PERMISO_HIJO_BANDA_HORARIA'    , '137');
define('ID_PERMISO_HIJO_MATRIZ_CUOTAS'    , '138');
define('ID_PERMISO_HIJO_AGENDAR'          , '139');
define('ID_PERMISO_HIJO_CONFIRMAR_AGENDA' , '140');

define('FLG_PARCIALMENTE_NIVEL_ITEMPLAN_VR', 1);
define('FLG_PENDIENTE_NIVEL_ITEMPLAN_VR', 2);
define('FLG_RECHAZADO_NIVEL_ITEMPLAN_VR', 3);
define('FLG_VALIDACION_TOTAL_NIVEL_ITEMPLAN_VR', 4);


define('ID_PERMISO_PADRE_GESTION_SIOM', '144');
define('ID_PERMISO_HIJO_BANDEJA_SIOM' , '145');
define('ID_PERMISO_HIJO_MANTENIMIENTO_SIOM' , '146');


define('ID_PERMISO_HIJO_REPORTE_CERTI_CV_MO' , '147');

define('ID_PERFIL_EECC' , '5');
define('ID_PERFIL_EECC_DUO' , '17');
define('ID_USUARIO_ELSA_MEDINA' , '20');
define('ID_USUARIO_OWEN_SARAVIA' , '3');
define('ID_USUARIO_CYNTHIA_DIAZ' , '45');

define('FECHA_LIMITE_REPORTE_PRESUPUESTO_2018' , '2018-12-19');
define('FECHA_LIMITE_REPORTE_PRESUPUESTO_2019' , '2019-12-19');

define('ID_ESTADO_PO_REGISTRADO' , '1');

define('FROM_CONSULTA', '1');
define('FROM_DISENIO' , '2');

define('FLG_MATERIAL_NO_BUCLE' , '0');
define('FLG_MATERIAL_BUCLE'    , '1');

define('PATH_FILE_UPLOAD_DETALLE_MATPO', 'download/detalleMatPO/modelo_carga_registro_individual_po.xls');
define('PATH_FILE_UPLOAD_DETALLE_SOLICITUD_CO', 'download/detalleMatPO/solicitud_oc.xls');
define('PATH_FILE_UPLOAD_DETALLE_SAP_MAT_PO', 'download/detalleMatSAP/materiales_po_sap.csv');
define('ID_PERMISO_HIJO_REG_INDIVIDUAL_PO'    , '148');
define('ID_PERMISO_HIJO_BANDEJA_CANCEL_PO'    , '149');

define('ID_PERMISO_HIJO_GENERAR_PO_MASIVO' , '150');
define('ID_PERMISO_HIJO_MANTENIMIENTO_KIT_EXT' , '151');
define('ID_PERMISO_HIJO_MANTENIMIENTO_SUB_SIN_DISENO' , '164');
define('ID_PERMISO_HIJO_PRECIARIO' , '156');
define('ID_PERMISO_HIJO_REPORTE_GESTION_OC' , '257');

//ESTADOS PO
define('PO_REGISTRADO', 1);
define('PO_PREAPROBADO', 2);
define('PO_APROBADO', 3);
define('PO_LIQUIDADO', 4);
define('PO_VALIDADO', 5);
define('PO_CERTIFICADO', 6);
define('PO_PRECANCELADO', 7);
define('PO_CANCELADO', 8);

//BANDEJA MANTENIMIENTO CONFIG_AUTO_APRO_PO
define('ID_PERMISO_HIJO_SUBPROY_AUTO_APROB' , '152');

define('ID_PERMISO_HIJO_MANT_MATERIAL' , '153');

//
define('ID_PROYECTO_OBRA_PUBLICA' , '4');
define('ID_PERMISO_HIJO_FICHA_TECNICA_CV' , '155');
define('ID_PROYECTO_TRANSPORTE' , '8');
define('NUMERO_HORAS_EDITAR_MATERIALES_CV' , '24');
define('ID_PROYECTO_FTTH' , '5');

//ruta descarga reporte licencias
define ('PATH_FILE_UPLOAD_LICENCIAS','download/extractor/licencias.csv');

//MANTENIMIENTO ENTIDAD
define('ID_PERMISO_HIJO_MANT_ENTIDAD' , '157');
//MANTENIMIENTO PARTIDA
define('ID_PERMISO_HIJO_MANT_PARTIDA' , '160');

define('PATH_FILE_UPLOAD_DETALLE_PLAN_TEMP', 'download/detalleplan/detalleplan_temp.csv');

define('ID_COMPLEJIDAD_MEDIA', 1);
define('ID_COMPLEJIDAD_ALTA' , 2);

define('ID_PERMISO_HIJO_MANT_PROY_EST_PARTIDA' , '161');
define('ID_PERMISO_HIJO_MANT_PRECIARIOS' , '162');


define('ID_PERMISO_HIJO_REG_MASIVO_LIC'    , '163');

define ('PATH_FILE_UPLOAD_MO','download/extractor/planobra_mo.csv');

define ('PATH_FILE_UPLOAD_MAT','download/extractor/planobra_mat.csv');
define('ID_PERMISO_HIJO_CARGA_MO_MAT' ,'165');

define('ID_PERMISO_HIJO_PO_REG_GRAFICOS' ,'166');
define('ID_PERMISO_HIJO_PO_REG_COTIZACION' ,'180');


define ('PATH_FILE_UPLOAD_DET_LIC','download/extractor/detalle_lic.csv');

define('ID_SUB_PROYECTO_NEGOCIO', '14');
define('ID_SUB_PROYECTO_EMPRESAS', '13');
define('ID_SUB_PROYECTO_MAYORISTA', '15');

define('ID_PERMISO_PADRE_CLUSTER_SISEGO' , '167');
define('ID_PERMISO_HIJO_BANDEJA_COTIZACION_SISEGO' , '168');

define('ID_PERMISO_HIJO_REPORTE_OP_SUBPROY' , '169');

define('ID_PERMISO_HIJO_MANT_ALMACEN' , '170');

define('ID_SUBPROYECTO_CV_NEGOCIO_2_BUCLE', '395');
define('ID_PERMISO_HIJO_BANDEJA_EDIT_NEGOCIO_2' , '172');



//ESTADOS DEL PROCESO PILOTO

define('ID_ESTADO_ITEMPLAN_REGISTRADO'          , '12');
define('ID_ESTADO_ITEMPLAN_ASIGNADO'            , '13');
define('ID_ESTADO_ITEMPLAN_REPLANTEO'           , '14');
define('ID_ESTADO_ITEMPLAN_CON_EXPEDIENTE'      , '15');
define('ID_ESTADO_ITEMPLAN_EXPEDIENTE_ENTREGADO', '16');
define('ID_ESTADO_ITEMPLAN_PEX_EJECUTADO'       , '17');

define('ID_MOTIVO_EJECUCION_EN_PROCESO' , '44');
define('ID_MOTIVO_PROCESO_DE_ACCESO'    , '45');
define('ID_MOTIVO_ACTIVIDAD_NO_INICIADA', '46');
define('ID_MOTIVO_AGENDA'               , '47');

//estados de auto en obra
define('ESTADO_AUTO_OBRA_ACTIVO'    , 1);
define('ESTADO_AUTO_OBRA_FINALIZADO', 2);
define('ESTADO_AUTO_OBRA_CANCELADO' , 3);

//estados de matenimiento de auto
define('ESTADO_AUTO_ACTIVO'       , 1);
define('ESTADO_AUTO_MANTENIMIENTO', 2);
define('ESTADO_AUTO_MALOGRADO'    , 3);

define('FROM_PILOTO'  , '3');


define('ID_SUB_PROYECTO_CV_NEGOCIO_I_BUCLE' , '99');
define('ID_SUB_PROYECTO_CV_NEGOCIO_I_INTEGRAL' , '98');

define('ID_SUB_PROYECTO_CV_NEGOCIO_II_BUCLE' , '395');
define('ID_SUB_PROYECTO_CV_NEGOCIO_II_INTEGRAL' , '396');

define('ID_PERMISO_HIJO_REPORTE_CV' , '172');

define('ID_PERMISO_HIJO_MANT_PLACAS' , '174');
define('ID_SUBPROYECTO_CALIBRACION_PEXT', '106');

define('ID_PERMISO_HIJO_MANT_PLACA_X_VR' , '176');

define('ID_PERMISO_HIJO_EDITAR_VR_PO', '177');

define('ID_PERMISO_HIJO_BANDEJA_FIRMA_DIGITAL', '178');

define('ID_PERMISO_HIJO_MANT_JEFATURA' , '179');


define('ID_PERMISO_HIJO_MANT_PARTIDA_SUBPROY' , '181');
define('ID_PERMISO_HIJO_BANDEJA_COTIZACION_INDIVIDUAL' , '182');

define ('PATH_FILE_UPLOAD_REPORT_COTI','download/extractor/reporte_cotizacion.csv');

######################## DOMINIO N##################################
define ('PATH_FILE_UPLOAD_DOMINION','download/dominion/');
define ('NAME_REPORT_MAT_DOMINION','REPORTE_MAT.csv');
define ('NAME_REPORT_MO_DOMINION','REPORTE_MO.csv');
define ('NAME_REPORT_DET_PLAN_DOMINION','REPORTE_DETALLE_PLAN.csv');
define ('NAME_REPORT_PLAN_OBRA_DOMINION','REPORTE_PLAN_OBRA.csv');

####################CONSTANTES SIOM#######################
define('ID_CONTRATO_TELFONICA_SIOM' , '21');

define('ID_EECC_LARI_SIOM' , '23');
define('ID_EECC_COBRA_SIOM' , '32');
define('ID_EECC_DOMINION_SIOM' , '31');
define('ID_EECC_EZENTIS_SIOM' , '33');
define('ID_EECC_TELEFONICA_SIOM' , '16');
define('ID_EECC_LITEYCA_SIOM' , '58');
define('ID_EECC_COMFICA_SIOM' , '57');

define('ID_SUB_ESPECIALIDAD_FO_SIOM' , '48');
define('ID_SUB_ESPECIALIDAD_COAXIAL_SIOM' , '49');
define('ID_SUB_ESPECIALIDAD_OBRA_CIVIL_SIOM' , '50');
define('ID_SUB_ESPECIALIDAD_ULTIMA_MILLA' , '51');
define('ID_SUB_ESPECIALIDAD_ENERGIA' , '52');

define('ID_FORMULARIO_FO_SIOM' , '216');
define('ID_FORMULARIO_COAXIAL_SIOM' , '217');
define('ID_FORMULARIO_OBRA_CIVIL_SIOM' , '218');
define('ID_FORMULARIO_ULTIMA_MILLA' , '219');
define('ID_FORMULARIO_ENERGIA' , '220');

define('ID_ESTACION_FO_ALIM'       , '15');
define('ID_ESTACION_FO_DIST'       , '16');

define('ID_SUB_PROYECTO_CV_RESIDENCIAL_OVERLAY_BUCLE' , '464');
define('ID_SUB_PROYECTO_CV_RESIDENCIAL_OVERLAY_INTEGRAL' , '463');

define('ID_SUBPROYECTO_ACELERACION_MOVIL', '171');

define('FICHA_BASE_UM', '6');
define ('PATH_FILE_REPORTE_ACTIVACIONES','download/extractor/reporte_activaciones.csv');
define ('PATH_FILE_REPORTE_DIAGNOSTICO_PEP','download/diagnostico_pep/diagnostico_pep.csv');

define('ID_PERMISO_HIJO_REPORTE_TECNICO_1_SIOM' , '188');
define('ID_PERMISO_HIJO_REPORTE_TECNICO_2_SIOM' , '189');

define('FLG_MOTIVO_RECHAZO_COTIZACION' , '5');

define('ID_PERMISO_HIJO_TABLERO_COMANDO' , '190');
define('ID_PERMISO_HIJO_TAB_COMANDO_BA_1' , '191');
define('ID_PERMISO_HIJO_TAB_COMANDO_BA_2' , '192');
define('ID_PERMISO_HIJO_BANDEJA_CANCELACION_SIOM' , '193');
define('ID_USUARIO_SIOM_WEB' , '1653');

define('TIPO_PO_MATERIAL' , '1');
define('TIPO_PO_MANO_OBRA' , '2');


///////////////////////////////////////GUSTAVO SEDANO 2019 08 15
define('ID_MODULO_GESTION_OBRA' , '1');
define('ID_MODULO_PAQUETIZADO' , '2');
define('ID_MODULO_GESTION_MANTENIMIENTO' , '6');

///////////////////////////////////////GUSTAVO SEDANO 2019 08 15 paquetizado

define('ID_PERMISO_PADRE_PAQUETIZADO', '194');
define('ID_PERMISO_HIJO_PQT_BIENVENIDA', '197');

define('ID_PERMISO_PADRE_PQT_MANTENIMIENTO', '195');
define('ID_PERMISO_HIJO_PQT_MANTE_PROYECTO', '198');
define('ID_PERMISO_HIJO_PQT_MAN_CENTRAL', '199');
define('ID_PERMISO_PADRE_PQT_PLAN_DE_OBRA', '196');
define('ID_PERMISO_HIJO_PQT_REGIND_OBRA', '200');

define('ESTACION_PQT_DISENO','1');
define('ESTACION_PQT_COAXIAL','2');
define('ESTACION_PQT_OC_COAXCIAL','3');
define('ESTACION_PQT_FUENTE','4');
define('ESTACION_PQT_FO','5');
define('ESTACION_PQT_OC_FO','6');
define('ESTACION_PQT_ENERGIA','7');

define('ID_PERMISO_HIJO_PQT_CONSULTAS', '201');

define('ID_PERFIL_ADMINISTRADOR' , '4');
define('ID_PERFIL_DISENO' , '9');

// REGISTRADO POR GUSTAVO SEDANO 2019 09 09 NUEVOS ESTADOS PAQUETIZADO
define('ID_ESTADO_SUSPENDIDO', '18');
define('ID_ESTADO_EN_LICENCIA', '19');
define('ID_ESTADO_EN_APROBACION', '20');
define('ID_ESTADO_EN_VALIDACION', '21');
define('ID_ESTADO_EN_CERTIFICACION', '22');

define('ID_PERMISO_HIJO_PQT_PRE_DISENO', '203');
define('ID_PERMISO_HIJO_PQT_DISENO', '204');
define('ID_PERMISO_HIJO_PQT_EN_LICENCIA', '205');
define('ID_PERMISO_HIJO_PQT_EN_APROBACION', '206');
define('ID_PERMISO_HIJO_PQT_EN_OBRA', '207');
define('ID_PERMISO_HIJO_PQT_PRE_LIQUIDADO', '208');
define('ID_PERMISO_HIJO_PQT_EN_VALIDACION', '209');
define('ID_PERMISO_HIJO_PQT_TERMINADO', '210');
define('ID_PERMISO_HIJO_PQT_EN_CERTIFICACION', '211');
define('ID_PERMISO_HIJO_PQT_CERTIFICADO', '212');

define('ID_PERMISO_HIJO_BANDEJA_VALIDADOS_SIOM' , '213');
define('ID_PROYECTO_HFC' , '1');

define('ID_PERMISO_HIJO_BOLSA_PEP' , '224');

define('ID_USUARIO_RAP_SAP' , '1724');
define('TIPO_SUBPROYECTO_BUCLE' , '1');
define('MSJ_TRAMA_SIROPE_EXISTE_CODIGO', 'YA EXISTE UNA OT REGISTRADA CON EL CÓDIGO INFORMADO.');
define('ID_PERMISO_HIJO_GESTIONAR_HOJA_GESTION' , '243');
defined('NAME_WEB_PO')  OR define('NAME_WEB_PO', 'PlanObra | Movistar');
defined('COLOR_BARRA')  OR define('COLOR_BARRA',   '#E9E9E9');
defined('RUTA_PLUGINS') OR define('RUTA_PLUGINS', 'https://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null).'/obra2.1/public/plugins/');
defined('RUTA_FONTS')   OR define('RUTA_FONTS'  , 'https://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null).'/obra2.1/public/fonts/');
defined('RUTA_CSS')     OR define('RUTA_CSS'    , 'https://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null).'/obra2.1/public/css/');
defined('RUTA_JS')      OR define('RUTA_JS'     , 'https://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null).'/obra2.1/public/js/');
defined('RUTA_IMG')     OR define('RUTA_IMG'    , 'https://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null).'/obra2.1/public/img/');
defined('COLOR_BARRA_ANDROID_SIST_AVA')     OR define('COLOR_BARRA_ANDROID_SIST_AVA',   '#E9E9E9');
defined('MENU_LOGO_SIST_AV')    OR define('MENU_LOGO_SIST_AV',  RUTA_IMG.'header/sistema_avantgard.png');
defined('IMG_MOVISTAR_CABECERA')      OR define('IMG_MOVISTAR_CABECERA',    RUTA_IMG.'iconos/iconfinder_movistar.png');
defined('IMG_OPERACIONES')      OR define('IMG_OPERACIONES',    RUTA_IMG.'iconsSistem/operaciones3.jpg');
define('ID_MODULO_CAP' , '3');
define('ID_MODULO_MANTENIMIENTO' , '4');
define('ID_MODULO_ADMINISTRATIVO' , '5');
define('ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_INTEGRAL', '223');
define('ID_PERMISO_PADRE_CAP_CONFIGURACION', '224');
define('ID_PERMISO_PADRE_NUEVO_MODELO_GESTION_VR', '229');
define('ID_PERMISO_PADRE_NUEVO_MODELO_COTIZACION', '226');
define('ID_PERMISO_PADRE_NUEVO_MODELO_TRANSFERENCIAS', '227');
define('ID_PERMISO_PADRE_GESTION_OBRA_CARGAS', '228');
define('ID_PERMISO_PADRE_NUEVO_MODELO_CRECIMIENTO_VERTICAL', '233');
define('ID_PERMISO_PADRE_CAP_SIOM', '230');
define('ID_PERMISO_PADRE_CAP_WORKFLOW', '231');
define('ID_PERMISO_PADRE_MANTENIMIENTO_SIOM', '232');
define('ID_PERMISO_PADRE_ADMINISTRATIVO_PRESUPUESTO', '238');
define('ID_PERMISO_PADRE_NUEVO_MODELO_REPORTES', '239');
define('ID_PERMISO_PADRE_MANTENIMIENTO_NUEVO_MODELO', '237');
define('ID_PERMISO_PADRE_MANTENIMIENTO_GESTION_DE_OBRA', '242');
define('ID_PERMISO_HIJO_MANTENIMIENTO_USUARIO' , '34');
define('ID_PERMISO_PADRE_MANTENIMIENTO_CERTIFICACION', '244');
define('ID_PERMISO_HIJO_BANDEJA_SIN_FECHA_INICIO' , '154');
define('ID_PERMISO_PADRE_DISENO_ADMINISTRATIVO' , '249');
define('ID_PERMISO_HIJO_ESTATUS_PRESUPUESTO'    , '100');
define('ID_PERMISO_HIJO_GESTIONAR_ORDEN_COMPRA' , '256');

defined('RUTA_NOTICIAS') OR define('RUTA_NOTICIAS', 'https://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null).'/obra2.1/uploads/noticias/');

defined('RUTA_VIDEO') OR define('RUTA_VIDEO', 'https://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null).'/obra2.1/uploads/video_panel/');

define('FLG_CONTRATO_ZONA_USUARIO_SIOM', '1'); //1 : 2020 (me muestra todas las zonas del flg 1 del 2020 de la tabla "zona");

/**constantes modulo gestion mantenimiento***/
define('ID_PERMISO_PADRE_GESTION_MANTENIMIENTO', '260');
define('ID_PERMISO_HIJO_REGISTRO_ITEMFAULT', '261');
define('ID_PERMISO_HIJO_CONSULTA_ITEMFAULT', '262');
define('ID_PERMISO_PADRE_COTIZACION_MANTENIMIENTO', '263');
define('ID_PERMISO_PADRE_GESTION_VR_MANTENIMIENTO', '264');
define('ID_PERMISO_HIJO_BANDEJA_COTIZACION_ITEMFAULT', '265');
define('ID_PERMISO_HIJO_BANDEJA_APROBACION_ITEMFAULT', '266');
define('ID_PERMISO_PADRE_MODULO_OPEX', '268');
define('ID_PERMISO_HIJO_REGISTRO_OPEX', '269');

define('FLG_SUB_NO_OPEX', '1');
define('FLG_SUB_OPEX'   , '2');

define('FLG_SUB_SIN_DISENO', '1');

/***paquetizado***/
define('PATH_FILE_UPLOAD_MATERIALES_X_ESTACION', 'download/detalleMatPO/materiales.xls');
define('ID_PARTIDA_FERRETERIA', '389');

define('ANIO_ACTUAL', '2020');