<?php // A1-B3 = DETALLES DE TODOS LOS MEDIDORES DE LAS RUTAS SELECCIONADAS [FIABILIZADO 16/12/2011]
        file_put_contents("log.txt", "8");

	if(!isset($_REQUEST['r'])){
		$RETURN .= "<ERR>Faltan parametros opcionales: 'R'</ERR>";
	}
	else {
		// REcuperamos el parametro:
			$rutas = $_REQUEST['r'];

		// Buscamos las rutas que mostrar:
			$SQL = "SELECT * FROM VI_AGUA_A_TABLET WHERE RUTA IN(". $rutas .") ORDER BY RUTA,ORDEN";
			$VI_AGUA_A_TABLET = sqlsrv_query($CONEXION, $SQL, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

			if(!isset($VI_AGUA_A_TABLET)){
				sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
			}
			
			if(sqlsrv_num_rows($VI_AGUA_A_TABLET) == 0){
				$RETURN .= "<RES>No hay registro en la tabla.</RES>";
			}
			else {
				// Mostramos los resultados:
					$RETURN .= "<VI_AGUA_A_TABLET>";
					$RETURN .= "<CNT>".sqlsrv_num_rows($VI_AGUA_A_TABLET)." entradas</CNT>";
				
					$tabla_PK = array();
					while ($REG_VI_AGUA_A_TABLET = sqlsrv_fetch_array($VI_AGUA_A_TABLET, SQLSRV_FETCH_ASSOC)){
						
						// Test:
							//$REG_VI_AGUA_A_TABLET = array();
						
						// Control de los resultados con respecto a '&' y '<':
							$REG_VI_AGUA_A_TABLET = clean_bdd_response($REG_VI_AGUA_A_TABLET);
					
						// Control de PK:
							$valor_ID = trim($REG_VI_AGUA_A_TABLET['ID_MED']);
							if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
								if (in_array($valor_ID, $tabla_PK) == false) {
						
									$RETURN .= "<MEDICION>";
									
										/*ID_MED-ID_MED*/
										$RETURN .= "<ID_MED>". $valor_ID ."</ID_MED>";
											$tabla_PK[] = intval($valor_ID);
										
										/*NUM_MED-NUM_MED*/
										$RETURN .= "<NUM_MED>".trim($REG_VI_AGUA_A_TABLET['NUM_MED'])."</NUM_MED>";
										
										/*MOD_MED-MOD_MED*/
										$RETURN .= "<MOD_MED>".trim($REG_VI_AGUA_A_TABLET['MOD_MED'])."</MOD_MED>";
										
										/*COD-COD*/ // Codigo cliente
										if (is_numeric(trim($REG_VI_AGUA_A_TABLET['COD'])) == false) {
											$REG_VI_AGUA_A_TABLET['COD'] = 0;
										}
										$RETURN .= "<COD>".trim($REG_VI_AGUA_A_TABLET['COD'])."</COD>";
										
										/*DNI-DNI*/
										$RETURN .= "<DNI>".trim($REG_VI_AGUA_A_TABLET['DNI'])."</DNI>";	
										
										/*NOM-NOM*/
										$RETURN .= "<NOM>".trim($REG_VI_AGUA_A_TABLET['NOM'])."</NOM>";
										
										/*TEL-TEL*/
										$RETURN .= "<TEL>".trim($REG_VI_AGUA_A_TABLET['TEL'])."</TEL>";
									
										/*CALLE-CALLE*/
										$RETURN .= "<CALLE>".trim($REG_VI_AGUA_A_TABLET['CALLE'])."</CALLE>";
							
										/*NUMERO-NUMERO*/
										if (is_numeric(trim($REG_VI_AGUA_A_TABLET['NUMERO'])) == false) {
											$REG_VI_AGUA_A_TABLET['NUMERO'] = 0;
										}
										$RETURN .= "<NUMERO>".trim($REG_VI_AGUA_A_TABLET['NUMERO'])."</NUMERO>";
							
										/*MZNA*/
										$RETURN .= "<MZNA>".trim($REG_VI_AGUA_A_TABLET['MZNA'])."</MZNA>";
							
										/*CASA-CASA*/
										$RETURN .= "<CASA>".trim($REG_VI_AGUA_A_TABLET['CASA'])."</CASA>";	
							
										/*PISO-PISO*/
										if (is_numeric(trim($REG_VI_AGUA_A_TABLET['PISO'])) == false) {
											$REG_VI_AGUA_A_TABLET['PISO'] = 0;
										}
										$RETURN .= "<PISO>".trim($REG_VI_AGUA_A_TABLET['PISO'])."</PISO>";
							
										/*DEPTO-DEPTO*/
										$RETURN .= "<DEPTO>".trim($REG_VI_AGUA_A_TABLET['DEPTO'])."</DEPTO>";
							
										/*TORRE-TORRE*/
										$RETURN .= "<TORRE>".trim($REG_VI_AGUA_A_TABLET['TORRE'])."</TORRE>";
							
										/*RUTA-RUTA*/
										if (is_numeric(trim($REG_VI_AGUA_A_TABLET['RUTA'])) == false) {
											$REG_VI_AGUA_A_TABLET['RUTA'] = 0;
										}
										$RETURN .= "<RUTA>".trim($REG_VI_AGUA_A_TABLET['RUTA'])."</RUTA>";
							
										/*ORDEN-ORDEN*/
										if (is_numeric(trim($REG_VI_AGUA_A_TABLET['ORDEN'])) == false) {
											$REG_VI_AGUA_A_TABLET['ORDEN'] = 0;
										}
										$RETURN .= "<ORDEN>".trim($REG_VI_AGUA_A_TABLET['ORDEN'])."</ORDEN>";	
							
										/*PER-PER*/
										if (strlen(trim($REG_VI_AGUA_A_TABLET['PER'])) <= 0) {
											$REG_VI_AGUA_A_TABLET['PER'] = "1900/01";
										}
										$RETURN .= "<PER>".trim($REG_VI_AGUA_A_TABLET['PER'])."</PER>";
							
										/*LEAN-LEAN*/
										if (is_numeric(trim($REG_VI_AGUA_A_TABLET['LEAN'])) == false) {
											$REG_VI_AGUA_A_TABLET['LEAN'] = 0;
										}
										$RETURN .= "<LEAN>".trim($REG_VI_AGUA_A_TABLET['LEAN'])."</LEAN>";
							
										/*LEAC-LEAC*/
										if (is_numeric(trim($REG_VI_AGUA_A_TABLET['LEAC'])) == false) {
											$REG_VI_AGUA_A_TABLET['LEAC'] = 0;
										}
										$RETURN .= "<LEAC>".trim($REG_VI_AGUA_A_TABLET['LEAC'])."</LEAC>";
							
										/*PROMEDIO-PROMEDIO*/
										if (is_numeric(trim($REG_VI_AGUA_A_TABLET['PROMEDIO'])) == false) {
											$REG_VI_AGUA_A_TABLET['PROMEDIO'] = 0;
										} else if (intval(trim($REG_VI_AGUA_A_TABLET['PROMEDIO'])) <= 0) {
											$REG_VI_AGUA_A_TABLET['PROMEDIO'] = 0;
										}
										$RETURN .= "<PROMEDIO>".trim($REG_VI_AGUA_A_TABLET['PROMEDIO'])."</PROMEDIO>";
							
									$RETURN .= "</MEDICION>";
								}
								else {
									$RETURN .= "<ERR>PK: ". intval($valor_ID) ." duplicada</ERR>";
									$ERROR .= "<br />CODIGO MEDIDOR duplicado : ". intval($valor_ID);
								}
							}
							else {
								$RETURN .= "<ERR>Formato incorrecto: ". $valor_ID ."</ERR>";
								$ERROR .= "<br />CODIGO MEDIDOR mal-formado :". $valor_ID;
							}
					} // end while
								
					sqlsrv_free_stmt($VI_AGUA_A_TABLET);
					
					$RETURN .= "</VI_AGUA_A_TABLET>";
					
						
				// Recuperamos el numero de la tablet que hizo este pedido, asi se marca en la BDD que esta tablet se llevo estas rutas:
					if(isset($_REQUEST['c'])){
						$ID_TAB = $_REQUEST['c'];
						
						$SQL_UP = "UPDATE AGUA_RUTA SET ID_TABLET=". $ID_TAB ." WHERE ID IN(". $rutas .")";
						$RES_SQL_UP = sqlsrv_query($CONEXION, $SQL_UP);
						
						if(!isset($RES_SQL_UP)){
							sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
						}		
					}
			}
	}		
?>