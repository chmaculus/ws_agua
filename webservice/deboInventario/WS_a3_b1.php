<?php // A3-B1 = INVENTARIOS DISPONIBLES [FIABILIZADO 29/12/2011]
	$SQL = "SELECT B.ID, B.DET, B.FET 
			FROM INV_PALM_REA A,ITOMINVC B 
			WHERE B.EST='T' AND B.ID=A.NUM_INV";
	$INVENTARIOS = mssql_query($SQL);

	if(!isset($INVENTARIOS)){
		mssql_query("ROLLBACK TRANSACTION");
	}

	if(mssql_num_rows($INVENTARIOS) == 0){
		$RETURN .= "<RES>No hay registro en la tabla.</RES>";
	}
	else {

		$RETURN .= "<VI_INVENTARIOS>";
		$RETURN .= "<CNT>".mssql_num_rows($INVENTARIOS)." entradas</CNT>";
			
		$tabla_PK = array();
		while ($REG_INVENTARIOS=mssql_fetch_array($INVENTARIOS)){
			
			// Test:
				//$REG_INVENTARIOS = array("COD"=>"3589", "NOM"=> "Jean < Pierre & Josette", "CLA"=>3);
			
			// Control de los resultados con respecto a '&' y '<':
				$REG_INVENTARIOS = clean_bdd_response($REG_INVENTARIOS);
		
			// Control de PK:
				$valor_ID = trim($REG_INVENTARIOS['ID']);
				if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
					if (in_array($valor_ID, $tabla_PK) == false) {
						
						$RETURN .= "<INV>";
							
							/*ID-N*/
							$RETURN .= "<N>".intval($valor_ID)."</N>";
								$tabla_PK[] = intval($valor_ID);
								
							/*DET-D*/
							$RETURN .= "<D>".trim($REG_INVENTARIOS['DET'])."</D>";
				
							/*FET-F*/
							if (trim($REG_INVENTARIOS['FET']) != "") {
								$RETURN .= "<F>".date("d/m/Y H:i:s",strtotime(trim($REG_INVENTARIOS['FET'])))."</F>";
							} else {
								$RETURN .= "<F></F>";
							}	
						
						$RETURN .= "</INV>";
					}
					else {
						$RETURN .= "<ERR>PK: ". intval($valor_ID) ." duplicada</ERR>";
						$ERROR .= "<br />CODIGO INVENTARIO duplicado : ". intval($valor_ID);
					}
				}
				else {
					$RETURN .= "<ERR>Formato incorrecto: ". $valor_ID ."</ERR>";
					$ERROR .= "<br />ID INVENTARIO mal-formado :". $valor_ID;
				}
		}
			
		// AL FINAL:
		mssql_free_result($INVENTARIOS);
		if(!isset($INVENTARIOS)){
			mssql_query("ROLLBACK TRANSACTION");
		}
		 
		$RETURN .= "</VI_INVENTARIOS>";
	}
?>