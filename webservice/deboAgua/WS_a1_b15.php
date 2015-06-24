<?php // A1-B15 = LIBERACION DE LAS RUTAS [FIABILIZADO 16/12/2011]
	        file_put_contents("log.txt", "5");

	if(!isset($_REQUEST['r'])){	
		$RETURN .= "<ERR>Faltan parametros opcionales: 'R'</ERR>";
	}
	else {
		// Recuperamos la lista de todas las rutas exportadas que tienen que ser liberadas ahora (campo ID_TABLET de la tabla AGUA_RUTA)
			$RUTAS_QUE_LIBERAR = $_REQUEST['r'];
			$PERIODO = date("Y/m");
		
		// Update en la BDD:
			$SQL_UP = "UPDATE AGUA_RUTA SET ID_TABLET=0, ULTPER='". $PERIODO ."' WHERE ID IN(" . $RUTAS_QUE_LIBERAR . ")";
			$RES_SQL_UP = sqlsrv_query($CONEXION, $SQL_UP, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));	
			
			if(!isset($RES_SQL_UP)){
				sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
			}
			
		// Control del update exitoso con un select:
			$SQL_SELECT = "SELECT * FROM AGUA_RUTA WHERE ID IN(". $RUTAS_QUE_LIBERAR .") AND ID_TABLET=0 ";
			$RES_SQL_SELECT = sqlsrv_query($CONEXION, $SQL_SELECT, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
					
			if(!isset($RES_SQL_SELECT)){
				sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
			}
			
			if(sqlsrv_num_rows($RES_SQL_SELECT) != count(explode(",", $RUTAS_QUE_LIBERAR))){
				$RETURN .= "<ERROR>Error en la LIBERACIÓN de las RUTAS: 
							se liberaron ". sqlsrv_num_rows($RES_SQL_SELECT) ." rutas 
							de las ". count(explode(",", $RUTAS_QUE_LIBERAR)) ." programadas</ERROR>";
			}
			else {
				$RETURN .= "<PROCESO>EXITO Exito exito</PROCESO>";	
			}
			
			sqlsrv_free_stmt($RES_SQL_SELECT);	
	}
?>