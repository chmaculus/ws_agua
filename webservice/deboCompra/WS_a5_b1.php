<?php // A5-B1 = OPERADORES (habilitados para generar actos de compra) [FIABILIZADO 29/12/2011]
	$SQL_VENDEDORES =  "SELECT V.CodVen AS COD, V.NomVen AS NOM, V.ClaVen AS CLA, A.TCO
						FROM VENDEDORES V
						INNER JOIN AVENTCO A 
						ON A.OPE=V.CodVen
						WHERE A.TCO='TA'";
	$VENDEDORES = mssql_query($SQL_VENDEDORES);

	if(!isset($VENDEDORES)){
		mssql_query("ROLLBACK TRANSACTION");
	}

	if(mssql_num_rows($VENDEDORES) == 0){
		$RETURN .= "<RES>No hay registro en la tabla.</RES>";
		exit;
	}
	
	$RETURN .= "<VISTA_VENDEDORES>";
	$RETURN .= "<CNT>".mssql_num_rows($VENDEDORES)." entradas</CNT>";
		
	$tabla_PK = array();
	while ($REG_VENDEDORES = mssql_fetch_array($VENDEDORES)){
		
		// Test:
			//$REG_VENDEDORES = array("COD"=>"3589", "NOM"=> "Jean < Pierre & Josette", "CLA"=>3);
		
		// Control de los resultados con respecto a '&' y '<':
			$REG_VENDEDORES = clean_bdd_response($REG_VENDEDORES);
	
		// Control de PK:
			$valor_ID = trim($REG_VENDEDORES['COD']);
			if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
				if (in_array($valor_ID, $tabla_PK) == false) {
					
					$RETURN .= "<OPE>";
					
						/*COD-ID*/
						$RETURN .= "<ID>".intval($valor_ID)."</ID>";
							$tabla_PK[] = intval($valor_ID);
						
						/*NOM-N*/
						if (is_numeric(trim($REG_VENDEDORES['NOM'])) == true) {
							$RETURN .= "<N>".intval(trim($REG_VENDEDORES['NOM']))."</N>";
						} else {
							$RETURN .= "<N>".trim($REG_VENDEDORES['NOM'])."</N>";
						}
						
						/*CLA-C*/
						$RETURN .= "<C>".trim($REG_VENDEDORES['CLA'])."</C>";
						
						/*D-D*/
						$RETURN .= "<D>1</D>";
						
					$RETURN .= "</OPE>";
				} 
				else {
					$RETURN .= "<ERR>PK: ". intval($valor_ID) ." duplicada</ERR>";
					$ERROR .= "<br />CODIGO VENDEDOR duplicado : ". intval($valor_ID);
				}
			}
			else {
				$RETURN .= "<ERR>Formato incorrecto: ". $valor_ID ."</ERR>";
				$ERROR .= "<br />CODIGO VENDEDOR mal-formado :". $valor_ID;
			}
	}
	
	// AL FINAL: 
		mssql_free_result($VENDEDORES);
		if(!isset($VENDEDORES)){
			mssql_query("ROLLBACK TRANSACTION");
		}
		
		$RETURN .= "</VISTA_VENDEDORES>";
?>