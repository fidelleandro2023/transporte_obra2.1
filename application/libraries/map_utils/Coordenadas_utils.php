<?php ini_set("memory_limit","200M"); ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Coordenadas_utils {

    function getJsonCoordenadas(){
        $stringFile = "";
        $myfile = fopen(__DIR__."/mapa.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        
        fclose($myfile);
        return $stringFile;
    }
    /*
    function getBuscarArea($arrayCordenadas) {
    
        $stringFile = "";
        $myfile = fopen(__DIR__."/mapa.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
    
        $JSON_COORDENADAS  = json_decode($stringFile, true);
    
        // RECARGA
        $i_selec = null;
        for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
            $result = $this->matBuscarArea($arrayCordenadas, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates'][0][0]);
    
            if($result == true) {
                $i_selec = $i;
                break;
            }
        }
    
        if($i_selec != null) {
            $cod_nod = $JSON_COORDENADAS['features'][$i_selec]['properties']['COD_NOD'];
        } else {
            $cod_nod = null;
        }
    
        return $cod_nod;
    }
    
    
    function matBuscarArea($point, $vs) {
        $x = $point[0];
        $y = $point[1];
    
        $inside = false;
        for ($i = 0, $j = count($vs) - 1; $i < count($vs); $j = $i++) {
            $xi = $vs[$i][0]; $yi = $vs[$i][1];
            $xj = $vs[$j][0]; $yj = $vs[$j][1];
            	
            $intersect = (($yi > $y) != ($yj > $y))&& ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
            if ($intersect) $inside = !$inside;
        }
    
        return $inside;
    }
    */
    /////////
    function getBuscarArea($arrayCordenadas) {
		ini_set('max_execution_time', 300);
        $stringFile = "";
        $myfile = fopen(__DIR__."/mapa.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
        $JSON_COORDENADAS  = json_decode(utf8_decode($stringFile), true);
        $i_selec = null;
         
        for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
            $result = $this->matBuscarArea($arrayCordenadas, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates'][0][0]);
            if($result == true) {
                $i_selec = $i;
                break;
            }
        }
        if($i_selec != null) {
			$nodo = $JSON_COORDENADAS['features'][$i_selec]['properties']['MDF'];
            /*$arrayCod = explode('|',  $JSON_COORDENADAS['features'][$i_selec]['properties']['COD_NOD']);
            
            $cod_nod = explode('0', $arrayCod[2]); */
        } else {
			$nodo = null;
                //$cod_nod = null;
        }
        //return $cod_nod[0];
		return trim($nodo);
    }
    
	//BUSCAMOS EL ARCHIVO DE SITIOS ARQUEOLOGICOS
	function getBuscarAreaSitiosArqueo($arrayCordenadas) {
		ini_set('max_execution_time', 300);
        $stringFile = "";
        $myfile = fopen(__DIR__."/sitios_historicos.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
		ini_set('memory_limit', '1000M');
        $JSON_COORDENADAS  = json_decode($stringFile, JSON_UNESCAPED_UNICODE);//PARA CARACTERES ESPECIALES
        $i_selec = null;

        for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
            $result = $this->matBuscarArea($arrayCordenadas, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates'][0]);
            if($result == true) {
                $i_selec = $i;
                break;
            }
        }
        if($i_selec != null) {
			$nomb = $JSON_COORDENADAS['features'][$i_selec]['properties']['name'];
        } else {
			$nomb = null;
        }
		return trim($i_selec);
    }
	
	function getCoordSitiosArqueoFull() {
		ini_set('max_execution_time', 300);
        $stringFile = "";
		$arrayData  = array(); 
        $myfile = fopen(__DIR__."/kmz_arqueologico.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
		ini_set('memory_limit', '1000M');
        $JSON_COORDENADAS  = json_decode($stringFile, JSON_UNESCAPED_UNICODE);//PARA CARACTERES ESPECIALES
        $i_selec = null;
		
        for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
			array_push($arrayData, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates'][0][0]);
        }
		return $arrayData;
    }
	
	function getCoordSitiosHistoricosFull() {
		ini_set('max_execution_time', 300);
        $stringFile = "";
		$arrayData  = array(); 
        $myfile = fopen(__DIR__."/sitios_historicos.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
		ini_set('memory_limit', '1000M');
        $JSON_COORDENADAS  = json_decode($stringFile, JSON_UNESCAPED_UNICODE);//PARA CARACTERES ESPECIALES
        $i_selec = null;
		
        for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
			array_push($arrayData, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates'][0]);
        }
		return $arrayData;
    }
	
	function getCoordSViasMetroFull() {
        $stringFile = "";
		$arrayData  = array(); 
        $myfile = fopen(__DIR__."/vias_metopolitanas.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
        $JSON_COORDENADAS  = json_decode($stringFile, JSON_UNESCAPED_UNICODE);//PARA CARACTERES ESPECIALES
        $i_selec = null;
		
        for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
			if($JSON_COORDENADAS['features'][$i]['geometry']['type'] == 'LineString') {
				array_push($arrayData, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates']);
			}
			
        }
		return $JSON_COORDENADAS;
    }
	
    function matBuscarArea($point, $vs) {
        $x = floatval($point[0]);
        $y = floatval($point[1]);
		
        $inside = false;
        
        for ($i = 0, $j = count($vs) - 1; $i < count($vs); $j = $i++) {
			$xi = floatval($vs[$i][0]);
			$yi = floatval($vs[$i][1]);
			$xj = floatval($vs[$j][0]);
			$yj = floatval($vs[$j][1]);
			$intersect = (($yi > $y) != ($yj > $y))&& ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
			if ($intersect) $inside = !$inside;
        }
        return $inside;
    }
	
	function getJsonKmz() {
        $stringFile = "";
        $myfile = fopen(__DIR__."/mapa.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
        $JSON_COORDENADAS  = json_decode(utf8_decode($stringFile), true);

		return $JSON_COORDENADAS;
    }
	
	//BUSCAMOS EL ARCHIVO DE SITIOS ARQUEOLOGICOS
	function getBuscarAreaNodoHfc($arrayCordenadas) {
		ini_set('max_execution_time', 300);
        $stringFile = "";
        $myfile = fopen(__DIR__."/nodos_hfc.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file

        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }

        fclose($myfile);
		ini_set('memory_limit', '1000M');
        $JSON_COORDENADAS  = json_decode($stringFile, JSON_UNESCAPED_UNICODE);//PARA CARACTERES ESPECIALES
        $i_selec = null;

        for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
			// _log("LINEA : ".$i);
			// _log(print_r($JSON_COORDENADAS['features'][$i]['geometry'], true));
			if(isset($JSON_COORDENADAS['features'][$i]['geometry']['geometries'])) {
				for($j=0; $j < count($JSON_COORDENADAS['features'][$i]['geometry']['geometries']); $j++) {
					$result = $this->matBuscarArea($arrayCordenadas, $JSON_COORDENADAS['features'][$i]['geometry']['geometries'][$j]['coordinates'][0]);
					if($result == true) {
						$i_selec = $i;
						break;
					}
				}
			} else {
				$result = $this->matBuscarArea($arrayCordenadas, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates'][0]);
				if($result == true) {
					$i_selec = $i;
					break;
				}
			}


        }
        if($i_selec != null) {
			$nomb = $JSON_COORDENADAS['features'][$i_selec]['properties']['name'];
			$arrayCod = explode(' ',$nomb);
			$cod_nod = $arrayCod[0];
			$cod_nod = trim($cod_nod);
        } else {
			$nomb = null;
			$cod_nod = null;
        }
		return $cod_nod;
    }
	
	function getCoordNodosHfc() {
        $stringFile = "";
		$arrayData  = array(); 
        $myfile = fopen(__DIR__."/nodos_hfc.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
        $JSON_COORDENADAS  = json_decode($stringFile, JSON_UNESCAPED_UNICODE);//PARA CARACTERES ESPECIALES
		return $JSON_COORDENADAS;
    }
	
	function getCoordSitiosHistoricosGeoJsonFull() {
		ini_set('max_execution_time', 300);
        $stringFile = "";
		$arrayData  = array(); 
        $myfile = fopen(__DIR__."/sitios_historicos.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
		ini_set('memory_limit', '1000M');
        $JSON_COORDENADAS  = json_decode($stringFile, JSON_UNESCAPED_UNICODE);//PARA CARACTERES ESPECIALES
        
		return $JSON_COORDENADAS;
    }
	
	function getCoordAreaNaturalFull() {
		// ini_set('max_execution_time', 300);
        $stringFile = "";
		$arrayData  = array(); 
        $myfile = fopen(__DIR__."/area_natural_protegida.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
		// ini_set('memory_limit', '1000M');
        $JSON_COORDENADAS  = $stringFile;//PARA CARACTERES ESPECIALES

        // $i_selec = null;
		
        // for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
			// if($JSON_COORDENADAS['features'][$i]['geometry']['type'] == 'LineString') {
				// array_push($arrayData, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates']);
			// }
			
        // }
		return $stringFile;
    }
	
	function getCoordPuntaHermosa() {
		// ini_set('max_execution_time', 300);
        $stringFile = "";
		$arrayData  = array(); 
        $myfile = fopen(__DIR__."/punta_hermosa_no_aereo.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
		// ini_set('memory_limit', '1000M');
        $JSON_COORDENADAS  = json_decode($stringFile, JSON_UNESCAPED_UNICODE);//PARA CARACTERES ESPECIALES

        // $i_selec = null;
		
        // for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
			// if($JSON_COORDENADAS['features'][$i]['geometry']['type'] == 'LineString') {
				// array_push($arrayData, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates']);
			// }
			
        // }
		return $stringFile;
    }
	
	function getCoordMiraflores() {
		// ini_set('max_execution_time', 300);
        $stringFile = "";
		$arrayData  = array(); 
        $myfile = fopen(__DIR__."/miraflores_no_aereo.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
       
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
		// ini_set('memory_limit', '1000M');
        $JSON_COORDENADAS  = json_decode($stringFile, JSON_UNESCAPED_UNICODE);//PARA CARACTERES ESPECIALES
		
        // $i_selec = null;
		
        // for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
			// if($JSON_COORDENADAS['features'][$i]['geometry']['type'] == 'LineString') {
				// array_push($arrayData, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates']);
			// }
			
        // }
		return $stringFile;
    }
	
	function getCoordAreaNatural_2Full() {
		ini_set('max_execution_time', 300);
        $stringFile = "";
		$arrayData  = array(); 
        $myfile = fopen(__DIR__."/area_natural_protegida_2.txt", "r") or die("Unable to open file!");
        // Output one line until end-of-file
        
        while(!feof($myfile)) {
            $stringFile .= fgets($myfile);
        }
        fclose($myfile);
		// ini_set('memory_limit', '1000M');
        $JSON_COORDENADAS  = $stringFile;//PARA CARACTERES ESPECIALES
        // $i_selec = null;
		
        // for($i=0; $i < count($JSON_COORDENADAS['features']); $i++) {
			// if($JSON_COORDENADAS['features'][$i]['geometry']['type'] == 'LineString') {
				// array_push($arrayData, $JSON_COORDENADAS['features'][$i]['geometry']['coordinates']);
			// }
			
        // }
		return $stringFile;
    }
}
