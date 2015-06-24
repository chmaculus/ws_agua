<?php // A3-B3 = DESCARGAR LOS ARTICULOS DE LOS INVENTARIOS SELECCIONADOS [FIABILIZADO 29/12/2011]

	if(!isset($_REQUEST['r'])) {
		$RETURN .= "<ERROR>Faltan parametros</ERROR>";
	}
	else {
		// Recuperamos los parametros del post (lista de los inventarios seleccionados):
		$INVENTARIOS_ELEGIDOS = $_REQUEST['r'];
		
		$SQL = "SELECT I.INV, I.SEC, I.ART, I.DET, I.PRE AS PRECIO, I.COS AS COSTO, C.CodBar AS CB
				FROM ITOMINVD I 
				FULL OUTER JOIN CODBAR C
				ON I.SEC=C.CodSec AND I.ART=C.CodArt
				WHERE I.INV IN (" . $INVENTARIOS_ELEGIDOS . ")
				ORDER BY I.INV ASC, I.SEC ASC, I.ART ASC";
			
		$ARTICULOS = mssql_query($SQL);

		if(!isset($ARTICULOS)){
			mssql_query("ROLLBACK TRANSACTION");
		}

		if(mssql_num_rows($ARTICULOS) == 0){
			$RETURN .= "<RES>No hay registro en la tabla.</RES>";	
		}
		else {
		
			$RETURN .= "<VI_ARTICULOS>";
			$RETURN .= "<CNT>".mssql_num_rows($ARTICULOS)." entradas</CNT>";
			
			// No vamos a hacer control de PK porque puede haber 2 o mas veces el mismo articulo
			// con 2 codigos de barras diferentes.
			$tabla_PK = array();
			while ($REG_ARTICULOS=mssql_fetch_array($ARTICULOS)){
			
				// Test:
					//$REG_ARTICULOS = array("ID"=>3589, "DET"=> "Jean < Pierre & Josette", "ULTPER"=>"2011/09", "ID_TABLET"=>76130);
					//$REG_ARTICULOS["SEC"] = "AVC";
				
				// Control de los resultados con respecto a '&' y '<':
					$REG_ARTICULOS = clean_bdd_response($REG_ARTICULOS);
			
				// Control de PK:
					if (is_numeric(trim($REG_ARTICULOS['SEC'])) == true && is_numeric(trim($REG_ARTICULOS['ART'])) == true) {
				
						$RETURN .= "<ART>";
							
							/*SEC-S*/
							$RETURN .= "<S>".trim($REG_ARTICULOS['SEC'])."</S>";
							
							/*ART-C*/
							$RETURN .= "<C>".trim($REG_ARTICULOS['ART'])."</C>";
							
							/*INV-I*/
							$RETURN .= "<I>".trim($REG_ARTICULOS['INV'])."</I>";
							
							/*DET-D*/
							$RETURN .= "<D>".trim($REG_ARTICULOS['DET'])."</D>";
							
							/*PRECIO-PV*/
							$RETURN .= "<PV>".trim($REG_ARTICULOS['PRECIO'])."</PV>";
							
							/*COSTO-PC*/
							$RETURN .= "<PC>".trim($REG_ARTICULOS['COSTO'])."</PC>";

							/*CB-CB*/
							if (is_numeric(trim($REG_ARTICULOS['CB']))) {
								$RETURN .= "<CB>".trim($REG_ARTICULOS['CB'])."</CB>";
							} else {
								$RETURN .= "<CB>0</CB>";
							}
					
						$RETURN .= "</ART>";
					}
					else {
						$RETURN .= "<ERR>Formato incorrecto:" .
								   "<br />SECTOR ARTICULO mal-formado :". trim($REG_ARTICULOS['SEC']) .
								   "<br />CODIGO ARTICULO mal-formado :". trim($REG_ARTICULOS['ART']) .
								   "</ERR>";
						$ERROR .= "<br />SECTOR ARTICULO mal-formado :". trim($REG_ARTICULOS['SEC']) .
								  "<br />CODIGO ARTICULO mal-formado :". trim($REG_ARTICULOS['ART']);
					}
			} // end while
			$RETURN .= "</VI_ARTICULOS>";
			
			mssql_free_result($ARTICULOS);
			
			if(!isset($ARTICULOS)){
				mssql_query("ROLLBACK TRANSACTION");
			}
		
			// Bloqueamos los inventarios usados (= poner el estado 'B'):
			if(!isset($_REQUEST['z'])) {
				$SQL = "UPDATE ITOMINVC SET EST='B' WHERE ID IN (". $INVENTARIOS_ELEGIDOS .")";
				$UPDATE_A_M = mssql_query($SQL);

				if(!isset($UPDATE_A_M)){
					mssql_query("ROLLBACK TRANSACTION");
				}
			}
		}
	}
?>