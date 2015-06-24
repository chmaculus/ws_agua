<?php // A1-B2 = TODOS LOS OPERADORES [FIABILIZADO 16/12/2011]
	
	global $CONEXION;
                file_put_contents("log.txt", "6");


	// RECUPERAMOS LOS USUARIOS DE LA TABLA AGUA_TOMA_OPE:
		$SQL = "SELECT 
					ID,NOM,DNI,ID_RUTA,PWD
				FROM 
					AGUA_TOMA_OPE
				";
		$AGUA_TOMA_OPE = sqlsrv_query($CONEXION, $SQL, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		if(!isset($AGUA_TOMA_OPE)){
			sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
		}
		
		
	// RECUPERAMOS LOS USUARIOS DE LA TABLA DE USUARIOS:
		$SQL = "SELECT 
					COD,NOM,GRP,SGR,PWD 
				FROM 
					USUARIOS
				";
		$USUARIOS = sqlsrv_query($CONEXION, $SQL, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
		
		if(!isset($USUARIOS)){
			sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
		}
		
		
	// CONTROL:
		$cantidad_operadores = sqlsrv_num_rows($AGUA_TOMA_OPE);
		$cantidad_usuarios = sqlsrv_num_rows($USUARIOS);
		if( $cantidad_operadores <= 0 && $cantidad_usuarios <= 0 ){
			$RETURN .= "<RES>No hay registro en la tabla.</RES>";
		}
		else {
			// MOSTRAMOS LOS RESULTADOS:
				$RETURN .= "<AGUA_TOMA_OPE>";
				$RETURN .= "<CNT>". ($cantidad_operadores + $cantidad_usuarios) ." entradas</CNT>";
			
				$tabla_PK = array();
				while ($REG_USUARIOS = sqlsrv_fetch_array($USUARIOS, SQLSRV_FETCH_ASSOC)){
				
					// Test:
						//$REG_USUARIOS = array();
					
					// Control de los resultados con respecto a '&' y '<':
						$REG_USUARIOS = clean_bdd_response($REG_USUARIOS);
				
					// Control de PK:
						$valor_ID = trim($REG_USUARIOS['COD']);
						if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
							if (in_array($valor_ID, $tabla_PK) == false) {

								$RETURN .= "<LOGIN>";
								
									/*COD-ID*/
									$RETURN .= "<ID>".intval($valor_ID)."</ID>";
										$tabla_PK[] = intval($valor_ID);
									
									/*NOM-NOM*/
									$RETURN .= "<NOM>".trim($REG_USUARIOS['NOM'])."</NOM>";
									
									/*GRP+SGR-DNI*/
									$RETURN .= "<DNI>".trim($REG_USUARIOS['GRP'])."-".trim($REG_USUARIOS['SGR'])."</DNI>";
									
									/* -ID_RUTA*/
									$RETURN .= "<ID_RUTA>0</ID_RUTA>";
									
									/*PWD-PWD*/
									$RETURN .= "<PWD>".trim($REG_USUARIOS['PWD'])."</PWD>";
									 
								$RETURN .= "</LOGIN>";
							}
							else {
								$RETURN .= "<ERR>PK: ". intval($valor_ID) ." duplicada</ERR>";
								$ERROR .= "<br />ID USUARIO/OPERADOR duplicado : ". intval($valor_ID);
							}
						}
						else {
							$RETURN .= "<ERR>Formato incorrecto: ". $valor_ID ."</ERR>";
							$ERROR .= "<br />ID mal-formado :". $valor_ID;
						}
					} // end while
					
					
					
					$tabla_PK = array();
					while ($REG_AGUA_TOMA_OPE = sqlsrv_fetch_array($AGUA_TOMA_OPE, SQLSRV_FETCH_ASSOC)){
					
						// Test:
							//$REG_AGUA_TOMA_OPE = array();
						
						// Control de los resultados con respecto a '&' y '<':
							$REG_AGUA_TOMA_OPE = clean_bdd_response($REG_AGUA_TOMA_OPE);
					
						// Control de PK:
							$valor_ID = trim($REG_AGUA_TOMA_OPE['ID']);
							if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
								if (in_array(intval($valor_ID), $tabla_PK) == false) {

									$RETURN .= "<LOGIN>";
										
										/*ID-ID*/
										$RETURN .= "<ID>". (intval($valor_ID) + 10000) ."</ID>";
											$tabla_PK[] = intval($valor_ID);
										
										/*NOM-NOM*/
										$RETURN .= "<NOM>".trim($REG_AGUA_TOMA_OPE['NOM'])."</NOM>";
										
										/*DNI-DNI*/
										$RETURN .= "<DNI>".trim($REG_AGUA_TOMA_OPE['DNI'])."</DNI>";
										
										/*ID_RUTA-ID_RUTA*/
										$RETURN .= "<ID_RUTA>".trim($REG_AGUA_TOMA_OPE['ID_RUTA'])."</ID_RUTA>";
										
										/*PWD-PWD*/
										$RETURN .= "<PWD>".trim($REG_AGUA_TOMA_OPE['PWD'])."</PWD>";
									
									$RETURN .= "</LOGIN>";
								}
								else {
									$RETURN .= "<ERR>PK: ". intval($valor_ID) ." duplicada</ERR>";
									$ERROR .= "<br />ID USUARIO/OPERADOR duplicado : ". intval($valor_ID);
								}
							}
							else {
								$RETURN .= "<ERR>Formato incorrecto: ". $valor_ID ."</ERR>";
								$ERROR .= "<br />ID USUARIO mal-formado :". $valor_ID;
							}
					} // end while
					
			// AL FINAL:
				sqlsrv_free_stmt($AGUA_TOMA_OPE);
				if(!isset($AGUA_TOMA_OPE)){
					sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
				}
				
				sqlsrv_free_stmt($USUARIOS);
				if(!isset($USUARIOS)){
					sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
				}
					
				$RETURN .= "</AGUA_TOMA_OPE>";
		}
?>