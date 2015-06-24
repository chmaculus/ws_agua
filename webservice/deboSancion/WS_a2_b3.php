<?php // A2-B3 = TODAS LAS SANCIONES(categorias que corresponden a sanciones)

	$SQL = "SELECT S.ID, S.TIT AS TITULO
			FROM SANCIONES_LV AS S";
	$VI_DEBO_SANCIONES = sqlsrv_query($CONEXION, $SQL, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

	if(!isset($VI_DEBO_SANCIONES)){
		sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
	}

	if(sqlsrv_num_rows($VI_DEBO_SANCIONES) == 0){
		$RETURN .= "<err>No hay registro en la tabla.</err>";
		exit;
	}

	$RETURN .= "<VI_DEBO_SANCIONES>";
        $RETURN .= "<CNT>". sqlsrv_num_rows($VI_DEBO_SANCIONES) ." respuestas</CNT>";
	while ($REG_VI_DEBO_SANCIONES=sqlsrv_fetch_array($VI_DEBO_SANCIONES, SQLSRV_FETCH_ASSOC)){
		$RETURN .= "<SANCION>";
			$RETURN .= "<ID>".$REG_VI_DEBO_SANCIONES['ID']."</ID>";
			$RETURN .= "<TIT>".$REG_VI_DEBO_SANCIONES['TITULO']."</TIT>";
		$RETURN .= "</SANCION>";
	}
	$RETURN .= "</VI_DEBO_SANCIONES>";
	
	sqlsrv_free_stmt($VI_DEBO_SANCIONES);
	
	if(!isset($VI_DEBO_SANCIONES)){
		sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
	}
	
?>