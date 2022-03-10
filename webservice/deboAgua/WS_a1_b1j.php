<?php // A1-B1 = TODAS LAS RUTAS [FIABILIZADO 16/12/2011]

	global $CONEXION;
        file_put_contents("log.txt", "1");
	
	$SQL = "SELECT * FROM VI_AGUA_RUTAS ORDER BY ID";
	$VI_AGUA_RUTAS = sqlsrv_query($CONEXION, $SQL, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	
	if(!isset($VI_AGUA_RUTAS)){
		sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
	}
	
	if(sqlsrv_num_rows($VI_AGUA_RUTAS) == 0){
		$RETURN .= "<RES>No hay registro en la tabla.</RES>";
	} 
	else {

		$RETURN .= "<VISTA_AGUA_RUTAS>";
		$RETURN .= "<CNT>".sqlsrv_num_rows($VI_AGUA_RUTAS)." entradas</CNT>";
		$array_entradas=array('entradas' => sqlsrv_num_rows($VI_AGUA_RUTAS));
		//$array_entradas .= "<CNT>".sqlsrv_num_rows($VI_AGUA_RUTAS)." entradas</CNT>";

		$array_rutas=array();		
		$tabla_PK = array();
		while ($REG_VI_AGUA_RUTAS = sqlsrv_fetch_array($VI_AGUA_RUTAS,SQLSRV_FETCH_ASSOC)){
			array_push($array_rutas, $REG_VI_AGUA_RUTAS);
			log_this("log/ws_a1_b1j_array2.log",date("H:i:s")."\n".print_r($REG_VI_AGUA_RUTAS,true)."\n--------\n");
		
			// Test:
				//$REG_VI_AGUA_RUTAS = array("ID"=>3589, "DET"=> "Jean < Pierre & Josette", "ULTPER"=>"2011/09", "ID_TABLET"=>76130);
			
			// Control de los resultados con respecto a '&' y '<':
				$REG_VI_AGUA_RUTAS = clean_bdd_response($REG_VI_AGUA_RUTAS);
		
			// Control de PK:
				$valor_ID = trim($REG_VI_AGUA_RUTAS['ID']);
				if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
					if (in_array($valor_ID, $tabla_PK) == false) {
					
						$RETURN .= "<RUTA>";
						
							/*ID-ID*/
							$RETURN .= "<ID>".intval($valor_ID)."</ID>";
								$tabla_PK[] = intval($valor_ID);
							
							/*DET-DET*/						
							$RETURN .= "<DET>".trim($REG_VI_AGUA_RUTAS['DET'])."</DET>";
							
							/*ULTPER-ULTPER*/
							$RETURN .= "<ULTPER>".trim($REG_VI_AGUA_RUTAS['ULTPER'])."</ULTPER>";
							
							/*ID_TABLET-ID_TABLET*/
							$RETURN .= "<ID_TABLET>".trim($REG_VI_AGUA_RUTAS['ID_TABLET'])."</ID_TABLET>";
							
						$RETURN .= "</RUTA>";
					}
					else {
						$RETURN .= "<ERR>PK: ". intval($valor_ID) ." duplicada</ERR>";
						$ERROR .= "<br />CODIGO RUTA duplicado : ". intval($valor_ID);
					}
				}
				else {
					$RETURN .= "<ERR>Formato incorrecto: ". $valor_ID ."</ERR>";
					$ERROR .= "<br />ID RUTA mal-formado :". $valor_ID;
				}
		} // end while
		
		sqlsrv_free_stmt($VI_AGUA_RUTAS);
		if(isset($VI_AGUA_RUTAS)){
			sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
		}
		
		$RETURN .= "</VISTA_AGUA_RUTAS>";
		//$array_return=array('VISTA_AGUA_RUTAS' => array());
		$array_n=array_merge($array_entradas,$array_rutas);
		//array_push($array_return,$array_entradas);
		//array_push($array_return,$array_rutas);
		$RETURN=json_encode($array_n);
		log_this("log/ws_a1_b1j_return.log",date("H:i:s")."\n".print_r($RETURN,true)."\n\n");
		log_this("log/ws_a1_b1j_array.log",date("H:i:s")."\n".print_r($array_entradas,true)."\n\n");
		log_this("log/ws_a1_b1j_arrayn.log",date("H:i:s")."\n".print_r($array_n,true)."\n\n");
		log_this("log/ws_a1_b1j_array_rutas.log",date("H:i:s")."\n".print_r($array_rutas,true)."\n\n");



	}
?>