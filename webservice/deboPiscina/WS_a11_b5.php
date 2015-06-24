<?php // A11-B5 = RESIDENTES POR PARTES
	
	if (!isset($_REQUEST['r'])) {
		$RETURN .= "<ERR>Faltan parametros opcionales: 'R'</ERR>";
	}
	else{
		// Explotamos los parametros:
		$TAB_PARAM = explode(",", $_REQUEST['r']);
		$INDICE_INI = min($TAB_PARAM);
		$INDICE_FIN = max($TAB_PARAM);
	
		if ($INDICE_INI <= 1) {
			$SQL_RESIDENTES =  "SELECT TOP ". $INDICE_FIN ." *
								FROM GROU_DALVIAN_RESIDENTES";
		}
		else {
			$SQL_RESIDENTES =  "SELECT TOP ". ($INDICE_FIN - $INDICE_INI + 1) ." *
								FROM GROU_DALVIAN_RESIDENTES
								WHERE ID_RES NOT IN (
									SELECT TOP ". ($INDICE_INI - 1) ." ID_RES 
									FROM GROU_DALVIAN_RESIDENTES 
									ORDER BY ID_RES)
								ORDER BY ID_RES";
		}
		$ERROR .= $SQL_RESIDENTES;				
		$RESIDENTES = mssql_query($SQL_RESIDENTES);

		if(!isset($RESIDENTES)){
			mssql_query("ROLLBACK TRANSACTION");
		}

		if(mssql_num_rows($RESIDENTES) == 0){
			$RETURN .= "<RES>No hay registro en la tabla.</RES>";
			exit;
		}
		
		$RETURN .= "<VISTA_RESIDENTES>";
		$RETURN .= "<CNT>".mssql_num_rows($RESIDENTES)." entradas</CNT>";
			
		$tabla_PK = array();
		while ($REG_RESIDENTES = mssql_fetch_array($RESIDENTES)) {
		
			// Test:
				//$REG_RESIDENTES = array("COD"=>"3589", "NOM"=> "Jean < Pierre & Josette", "CLA"=>3);
			
			// Control de los resultados con respecto a '&' y '<':
				$REG_RESIDENTES = clean_bdd_response($REG_RESIDENTES);
		
			// Control de PK:
				$valor_ID = trim($REG_RESIDENTES['CODIGO_OPE']);
				if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
					if (in_array($valor_ID, $tabla_PK) == false) {
						
						$RETURN .= "<RES>";
						
							/*CODIGO_OPE-COPE*/
							$RETURN .= "<COPE>".intval($valor_ID)."</COPE>";
								$tabla_PK[] = intval($valor_ID);
							
							/*DOCUMENTO-DOC*/
							$RETURN .= "<DOC>".trim($REG_RESIDENTES['DOCUMENTO'])."</DOC>";
							
							/*APELLIDO-APE*/
							$RETURN .= "<APE>".trim($REG_RESIDENTES['APELLIDO'])."</APE>";
							
							/*NOMBRE-NOM*/
							$RETURN .= "<NOM>".trim($REG_RESIDENTES['NOMBRE'])."</NOM>";
													
							/*ESTATUS-EST*/
							$RETURN .= "<EST>".trim($REG_RESIDENTES['ESTATUS'])."</EST>";
							
							/*FEC_NAC-FEC*/
							$FECHA_TEMP = new DateTime($REG_RESIDENTES['FEC_NAC']);
							$FECHA = $FECHA_TEMP->format("Y/m/d h:i");
							$RETURN .= "<FEC>".$FECHA."</FEC>";
							
							/*TIPO_CONSTR-CTR*/
							$RETURN .= "<CTR>".trim($REG_RESIDENTES['TIPO_CONSTR'])."</CTR>";
							
							/*CALLE-CAL*/
							$RETURN .= "<CAL>".utf8_decode(trim($REG_RESIDENTES['CALLE']))."</CAL>";
							
							/*ALTURA-ALT*/
							if (is_numeric(trim($REG_RESIDENTES['ALTURA'])) == true) {
								$RETURN .= "<ALT>".intval(trim($REG_RESIDENTES['ALTURA']))."</ALT>";
							} else {
								$RETURN .= "<ALT>0</ALT>";
							}
							
							/*MANZANA-MZN*/
							$RETURN .= "<MZN>".trim($REG_RESIDENTES['MANZANA'])."</MZN>";
							
							/*LOTE-LOT*/
							$RETURN .= "<LOT>".trim($REG_RESIDENTES['LOTE'])."</LOT>";
							
							/*PISO-PIS*/
							$RETURN .= "<PIS>".trim($REG_RESIDENTES['PISO'])."</PIS>";
							
							/*DPTO-DPT*/
							$RETURN .= "<DPT>".trim($REG_RESIDENTES['DPTO'])."</DPT>";
							
							/*TEL-TEL*/
							$RETURN .= "<TEL>".trim($REG_RESIDENTES['TEL'])."</TEL>";
							
							/*HABILITADO-HAB*/
							if (is_numeric(trim($REG_RESIDENTES['HABILITADO'])) == true) {
								$RETURN .= "<HAB>".intval(trim($REG_RESIDENTES['HABILITADO']))."</HAB>";
							} else {
								$RETURN .= "<HAB>0</HAB>";
							}
							
						$RETURN .= "</RES>";
					} 
					else {
						$RETURN .= "<ERR>PK: ". intval($valor_ID) ." duplicada</ERR>";
						$ERROR .= "<br />CODIGO RESIDENTE duplicado : ". intval($valor_ID);
					}
				}
				else {
					$RETURN .= "<ERR>Formato incorrecto: ". $valor_ID ."</ERR>";
					$ERROR .= "<br />CODIGO RESIDENTE mal-formado :". $valor_ID;
				}
		} // end while
	
		// AL FINAL: 
			mssql_free_result($RESIDENTES);
			if(!isset($RESIDENTES)){
				mssql_query("ROLLBACK TRANSACTION");
			}
			
			$RETURN .= "</VISTA_RESIDENTES>";
	}
?>