<?php // A1-B4 = TODAS LAS EXPLICACIONES POSIBLES A LOS ERRORES DURANTE LAS MEDICIONES [FIABILIZADO 16/12/2011]
        file_put_contents("log.txt", "8");

	$SQL_ERRORES = "SELECT ID, DET, GRAVEDAD FROM AGUA_ERRORES ORDER BY ID ASC";
	$RES_ERRORES = sqlsrv_query($CONEXION, $SQL_ERRORES, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

	if(!isset($RES_ERRORES)){
		sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
	}

	if(sqlsrv_num_rows($RES_ERRORES) == 0){
		$RETURN .= "<RES>No hay registro en la tabla.</RES>";
	}
	else {
		$RETURN .= "<AGUA_ERRORES>";
		$RETURN .= "<CNT>".sqlsrv_num_rows($RES_ERRORES)." entradas</CNT>";
		
		$tabla_PK = array();
		while ($REG_RES_ERRORES = sqlsrv_fetch_array($RES_ERRORES, SQLSRV_FETCH_ASSOC)){
		
			// Test:
				//$REG_RES_ERRORES = array();
				
			// Control de los resultados con respecto a '&' y '<':
				$REG_RES_ERRORES = clean_bdd_response($REG_RES_ERRORES);
		
			// Control de PK:
				$valor_ID = trim($REG_RES_ERRORES['ID']);
				if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
					if (in_array($valor_ID, $tabla_PK) == false) {
					
						$RETURN .= "<ERRORR>";
						
							/*ID-ID*/
							$RETURN .= "<ID>". intval($valor_ID) ."</ID>";
								$tabla_PK[] = intval($valor_ID);
							
							/*DET-DET*/
							if (trim($REG_RES_ERRORES['DET']) == '') {
								$REG_RES_ERRORES['DET'] = "Error no especificado";
							}
							$RETURN .= "<DET>".trim($REG_RES_ERRORES['DET'])."</DET>";
							
							/*GRAVEDAD-GRVD*/
							if (is_numeric(trim($REG_RES_ERRORES['GRAVEDAD'])) == false) {
								$REG_RES_ERRORES['GRAVEDAD'] = 0;
							}
							$RETURN .= "<GRVD>".intval(trim($REG_RES_ERRORES['GRAVEDAD']))."</GRVD>";
							
						$RETURN .= "</ERRORR>";
					}
					else {
						$RETURN .= "<ERR>PK: ". intval($valor_ID) ." duplicada</ERR>";
						$ERROR .= "<br />CODIGO ERROR duplicado : ". intval($valor_ID);
					}
				}
				else {
					$RETURN .= "<ERR>Formato incorrecto: ". $valor_ID ."</ERR>";
					$ERROR .= "<br />ID ERROR mal-formado :". $valor_ID;
				}
		} // end while
		
		
		sqlsrv_free_stmt($RES_ERRORES);
		if(!isset($RES_ERRORES)){
			sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
		}	

		$RETURN .= "</AGUA_ERRORES>";	
	}
?>