<?php // A11-B8 = REVISACIONES
	

/**
 * comienzo de version actual
 */
	
        file_put_contents("log.txt", "entro a revisaciones", FILE_APPEND);
        /**
         * fin de version actual
         */
        $SQL_REVISIONES =  "SELECT * FROM REVISIONES ";
        /**
         * comienzo de version antigua 
         */
//        $SQL_REVISIONES =  "SELECT * FROM GROU_REVISIONES ";
        /**
         * fin de version antigua
         */
	$REVISIONES = sqlsrv_query($SQL_REVISIONES);

	if(!isset($REVISIONES)){
		sqlsrv_query("ROLLBACK TRANSACTION");
	}

	if(sqlsrv_query($REVISIONES) == 0){
		$RETURN .= "<RES>No hay registro en la tabla.</RES>";
		exit;
	}
	
	$RETURN .= "<VISTA_REVISIONES>";
	$RETURN .= "<CNT>". sqlsrv_num_rows($REVISIONES)." entradas</CNT>";
		
	$tabla_PK = array();
        
        file_put_contents("log.txt", sqlsrv_num_rows($REVISIONES) . "\n", FILE_APPEND);
	while ($REG_REVISIONES =  sqlsrv_num_rows($REVISIONES)){
           
		
		// Test:
			//$REG_REVISIONES = array("COD"=>"3589", "NOM"=> "Jean < Pierre & Josette", "CLA"=>3);
		
		// Control de los resultados con respecto a '&' y '<':
			$REG_REVISIONES = clean_bdd_response($REG_REVISIONES);
	
		// Control de PK:
			$valor_fec_crea = trim($REG_REVISIONES['FEC_CREA']);
			$valor_resi = trim($REG_REVISIONES['RESI']);
			$array_claves = array('fec'=>$valor_fec_crea, 'res'=>$valor_resi);
			
			if (is_numeric($valor_resi) == true && intval($valor_resi) >= 0 && $valor_fec_crea != '') {
				if (in_array($array_claves, $tabla_PK) == false) {
					
					$RETURN .= "<REV>";
					
						/*FEC_CREA-FEC*/
						$FECHA_TEMP = new DateTime($valor_fec_crea);
						$RETURN .= "<FEC>".($FECHA_TEMP->format('Y/m/d H:i'))."</FEC>";
							$tabla_PK[] = $array_claves;
						
						/*RESI-R_D*/
						$RETURN .= "<R_D>".$valor_resi."</R_D>";
						
						/*FEC_VENC-VEC*/
						$FECHA_TEMP = new DateTime(trim($REG_REVISIONES['FEC_VENC']));
						$RETURN .= "<VEC>".($FECHA_TEMP->format('Y/m/d H:i'))."</VEC>";
						
						/*MEDICO-MED*/
						$RETURN .= "<MED>".trim($REG_REVISIONES['MEDICO'])."</MED>";
												
						/*OPE-OPE*/
						$RETURN .= "<OPE>".trim($REG_REVISIONES['OPE'])."</OPE>";
						
						/*OBS-OBS*/
						$RETURN .= "<OBS>".trim($REG_REVISIONES['OBS'])."</OBS>";
												
					$RETURN .= "</REV>";
				} 
				else {
					$RETURN .= "<ERR>PK: ". intval($valor_ID) ." duplicada</ERR>";
					$ERROR .= "<br />CODIGO REVISIONES duplicado : ". intval($valor_ID);
				}
			}
	}
	
	// AL FINAL: 
		//mssql_free_result($REVISIONES);
		if(!isset($REVISIONES)){
//			mssql_query("ROLLBACK TRANSACTION");
		}
		
		$RETURN .= "</VISTA_REVISIONES>";
?>