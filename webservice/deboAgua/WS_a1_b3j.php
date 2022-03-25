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
			$entradas=sqlsrv_num_rows($VI_AGUA_A_TABLET);
			$RETURN .= "<CNT>".$entradas." entradas</CNT>";
		
			$tabla_PK = array();
			$array_medicion = array();
			$array_error = array();
			
			while ($REG_VI_AGUA_A_TABLET = sqlsrv_fetch_array($VI_AGUA_A_TABLET, SQLSRV_FETCH_ASSOC)){
				
				// Test:
					//$REG_VI_AGUA_A_TABLET = array();
				
				// Control de los resultados con respecto a '&' y '<':
				$REG_VI_AGUA_A_TABLET = clean_bdd_response($REG_VI_AGUA_A_TABLET);
					
				// Control de PK:
				$valor_ID = trim($REG_VI_AGUA_A_TABLET['ID_MED']);
				if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
					if (in_array($valor_ID, $tabla_PK) == false) {
						$q="select * from agua_medidor where id='".$REG_VI_AGUA_A_TABLET['ID_MED']."'";
						//echo "q: ".$q."\n";
						$result = sqlsrv_query($CONEXION, $q);
						//$arr1=sqlsrv_fetch($result, 0);
						$arr1=sqlsrv_fetch_array( $result);

						//echo "--------------\n".print_r($arr1,true)."\n--------------\n\n";

						
						/*ID_MED-ID_MED*/
						
						$tabla_PK[] = intval($valor_ID);
						
						/*NUM_MED-NUM_MED*/
						$REG_VI_AGUA_A_TABLET['NUM_MED']=strval(trim($REG_VI_AGUA_A_TABLET['NUM_MED']));
						
						/*MOD_MED-MOD_MED*/
						$REG_VI_AGUA_A_TABLET['MOD_MED']=strval(trim($REG_VI_AGUA_A_TABLET['MOD_MED']));
						
						/*COD-COD*/ // Codigo cliente
						if (is_numeric(trim($REG_VI_AGUA_A_TABLET['COD'])) == false) {
							$REG_VI_AGUA_A_TABLET['COD'] = strval(0);
						}
						$REG_VI_AGUA_A_TABLET['MEDIDOR_NUMERO']=strval(trim($arr1['NUM']));
						$REG_VI_AGUA_A_TABLET['MEDIDOR_MODELO']=strval(trim($arr1['MOD']));
						$REG_VI_AGUA_A_TABLET['MEDIDOR_PULGADAS']=strval(trim($arr1['PUL']));

						/*DNI-DNI*/
						$REG_VI_AGUA_A_TABLET['DNI']=strval(trim($REG_VI_AGUA_A_TABLET['DNI']));
						
						/*NOM-NOM*/
						$REG_VI_AGUA_A_TABLET['NOM']=strval(trim($REG_VI_AGUA_A_TABLET['NOM']));
						
						/*TEL-TEL*/
						$REG_VI_AGUA_A_TABLET['TEL']=strval(trim($REG_VI_AGUA_A_TABLET['TEL']));
					
						/*CALLE-CALLE*/
						$REG_VI_AGUA_A_TABLET['CALLE']=strval(trim($REG_VI_AGUA_A_TABLET['CALLE']));
						$REG_VI_AGUA_A_TABLET['CALLE']=strval(utf8_encode($REG_VI_AGUA_A_TABLET['CALLE']));
						log_this("log/ws_a1_b3j_calle.log",date("H:i:s")."\n".$REG_VI_AGUA_A_TABLET['CALLE']."\n\n");
			
						/*NUMERO-NUMERO*/
						if (is_numeric(trim($REG_VI_AGUA_A_TABLET['NUMERO'])) == false) {
							$REG_VI_AGUA_A_TABLET['NUMERO'] = strval(0);
						}
			
						/*MZNA*/
						$REG_VI_AGUA_A_TABLET['MZNA']=strval(trim($REG_VI_AGUA_A_TABLET['MZNA']));
			
						/*CASA-CASA*/
						$REG_VI_AGUA_A_TABLET['CASA']=strval(trim($REG_VI_AGUA_A_TABLET['CASA']));
			
						/*PISO-PISO*/
						if (is_numeric(trim($REG_VI_AGUA_A_TABLET['PISO'])) == false) {
							$REG_VI_AGUA_A_TABLET['PISO'] = strval(0);
						}
			
						/*DEPTO-DEPTO*/
						$REG_VI_AGUA_A_TABLET['DEPTO']=strval(trim($REG_VI_AGUA_A_TABLET['DEPTO']));
			
						/*TORRE-TORRE*/
						$REG_VI_AGUA_A_TABLET['TORRE']=strval(trim($REG_VI_AGUA_A_TABLET['TORRE']));
			
						/*RUTA-RUTA*/
						if (is_numeric(trim($REG_VI_AGUA_A_TABLET['RUTA'])) == false) {
							$REG_VI_AGUA_A_TABLET['RUTA'] = strval(0);
						}
						$REG_VI_AGUA_A_TABLET['RUTA']=strval(trim($REG_VI_AGUA_A_TABLET['RUTA']));
			
						/*ORDEN-ORDEN*/
						if (is_numeric(trim($REG_VI_AGUA_A_TABLET['ORDEN'])) == false) {
							$REG_VI_AGUA_A_TABLET['ORDEN'] = strval(0);
						}
			
						/*PER-PER*/
						if (strlen(trim($REG_VI_AGUA_A_TABLET['PER'])) <= 0) {
							$REG_VI_AGUA_A_TABLET['PER'] = "1900/01";
						}
			
						/*LEAN-LEAN*/
						if (is_numeric(trim($REG_VI_AGUA_A_TABLET['LEAN'])) == false) {
							$REG_VI_AGUA_A_TABLET['LEAN'] = strval(0);
						}
						$REG_VI_AGUA_A_TABLET['LEAN']=strval(round($REG_VI_AGUA_A_TABLET['LEAN'],0));
			
						/*LEAC-LEAC*/
						if (is_numeric(trim($REG_VI_AGUA_A_TABLET['LEAC'])) == false) {
							$REG_VI_AGUA_A_TABLET['LEAC'] = strval(0);
						}
						$REG_VI_AGUA_A_TABLET['LEAC']=strval(round($REG_VI_AGUA_A_TABLET['LEAC'],0));
			
						/*PROMEDIO-PROMEDIO*/
						if (is_numeric(trim($REG_VI_AGUA_A_TABLET['PROMEDIO'])) == false) {
							$REG_VI_AGUA_A_TABLET['PROMEDIO'] = "";
						} else if (intval(trim($REG_VI_AGUA_A_TABLET['PROMEDIO'])) <= 0) {
							$REG_VI_AGUA_A_TABLET['PROMEDIO'] = strval(0);
						}
						$REG_VI_AGUA_A_TABLET['PROMEDIO']=strval(trim($REG_VI_AGUA_A_TABLET['PROMEDIO']));
						$REG_VI_AGUA_A_TABLET['PROMEDIO']=strval(round($REG_VI_AGUA_A_TABLET['PROMEDIO'],2));

						array_push($array_medicion, $REG_VI_AGUA_A_TABLET);
			
					}
					else {
						$ERROR .= "<br />CODIGO MEDIDOR duplicado : ". intval($valor_ID);
						//:::work 10/03/2022
						array_push($array_error, "PK: ". intval($valor_ID) ." duplicada");


					}
				}
				else {
					$ERROR .= "<br />CODIGO MEDIDOR mal-formado :". $valor_ID;
					array_push($array_error, "Formato incorrecto: ". $valor_ID);
				}
					} // end while
								
					sqlsrv_free_stmt($VI_AGUA_A_TABLET);
					$arr_rtn=array('CNT'=> $entradas, 'MEDICION' => $array_medicion, 'ERR' => $array_error);
					$RETURN=json_encode($arr_rtn);
					//$RETURN = utf8_encode($RETURN);
						
				// Recuperamos el numero de la tablet que hizo este pedido, asi se marca en la BDD que esta tablet se llevo estas rutas:
					if(isset($_REQUEST['c'])){
						$ID_TAB = $_REQUEST['c'];
						
						$SQL_UP = "UPDATE AGUA_RUTA SET ID_TABLET=". $ID_TAB ." WHERE ID IN(". $rutas .")";
						$RES_SQL_UP = sqlsrv_query($CONEXION, $SQL_UP);
						
						if(!isset($RES_SQL_UP)){
							sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
						}		
					}
				
			}//end while
			
			 // log_this("log/ws_a1_b3j_medicion.log",date("H:i:s")."\n".print_r($array_medicion,true)."\n\n");
			 // log_this("log/ws_a1_b3j_error.log",date("H:i:s")."\n".print_r($array_error,true)."\n\n");
			 // log_this("log/ws_a1_b3j_return.log",date("H:i:s")."\n".print_r($RETURN,true)."\n\n");

	}		
?>