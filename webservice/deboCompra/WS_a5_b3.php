<?php // A5-B3 = TODOS LOS PROVEEDORES [FIABILIZADO 15/12/2011]

	// Recuperamos los parametros del post (lista de los inventarios seleccionados):
	$SQL_PROVEEDORES = "SELECT NOM, CUIT, TEL
						FROM PROVEED
						ORDER BY CUIT";
		
	$PROVEEDORES = mssql_query($SQL_PROVEEDORES);

	if(!isset($PROVEEDORES)){
		mssql_query("ROLLBACK TRANSACTION");
	}

	if(mssql_num_rows($PROVEEDORES) == 0){
		$RETURN .= "<RES>No hay registro en la tabla.</RES>";
	}
	else {
		$RETURN .= "<VISTA_PROVEEDORES>";
		$RETURN .= "<CNT>".mssql_num_rows($PROVEEDORES)." entradas</CNT>";
		
		$tabla_PK = array();
		while ($REG_PROVEEDORES=mssql_fetch_array($PROVEEDORES)){
		
			// Test:
				//..................
		
			// Control de los resultados con respecto a '&' y '<':
				$REG_PROVEEDORES = clean_bdd_response($REG_PROVEEDORES);
		
			// Control de PK:
				$cuit_leido = trim($REG_PROVEEDORES['CUIT']);
				if ($cuit_leido != "") {
					if (in_array($cuit_leido, $tabla_PK) == false) {	
						
						$RETURN .= "<PRO>";
						
							/*CUIT-C*/
							$RETURN .= "<C>" . $cuit_leido . "</C>";
								$tabla_PK[] = $cuit_leido;
							
							/*NOM-N*/
							$RETURN .= "<N>" . trim(str_replace("'", " ", $REG_PROVEEDORES['NOM'])) . "</N>";
							
							/*TEL-T*/
							$RETURN .= "<T>" . trim($REG_PROVEEDORES['TEL']) . "</T>";
						
						$RETURN .= "</PRO>";
					}
					else {
						$RETURN .= "<ERR>PK: ". $cuit_leido ." duplicada</ERR>";
						$ERROR .= "CUIT duplicado: ". $cuit_leido;
					}
				}
		} // end while
		
		// AL FINAL:
		mssql_free_result($PROVEEDORES);
		if(!isset($PROVEEDORES)){
			mssql_query("ROLLBACK TRANSACTION");
		}
		
		$RETURN .= "</VISTA_PROVEEDORES>";
	}
?>