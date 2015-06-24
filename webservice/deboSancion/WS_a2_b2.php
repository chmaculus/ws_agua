<?php // A2-B2 = TODOS OPERADORES (personas habilitadas para emitir sanciones)
	global $CONEXION;

        $SQL = "SELECT U.COD as ID, U.NOM AS NOMBRE, U.PWD AS CONTRASENA
			FROM USUARIOS AS U";
	$VI_DEBO_USUARIOS = sqlsrv_query($CONEXION, $SQL, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

	if(!isset($VI_DEBO_USUARIOS)){
		sqlsrv_query("ROLLBACK TRANSACTION");
	}

	if(sqlsrv_num_rows($VI_DEBO_USUARIOS) == 0){
		$RETURN .= "<err>No hay registro en la tabla.</err>";
		exit;
	}

	$RETURN .= "<VI_DEBO_USUARIOS>";
	while ($REG_VI_DEBO_USUARIOS=sqlsrv_fetch_array($VI_DEBO_USUARIOS, SQLSRV_FETCH_ASSOC)){
		$RETURN .= "<USUARIO>";
			$RETURN .= "<ID>".$REG_VI_DEBO_USUARIOS['ID']."</ID>";
			$RETURN .= "<DNI>".$REG_VI_DEBO_USUARIOS['ID']."</DNI>";
			$RETURN .= "<NOM>".$REG_VI_DEBO_USUARIOS['NOMBRE']."</NOM>";
			$RETURN .= "<PWD>".$REG_VI_DEBO_USUARIOS['CONTRASENA']."</PWD>";
		$RETURN .= "</USUARIO>";
	}
	$RETURN .= "</VI_DEBO_USUARIOS>";
	
	sqlsrv_free_stmt($VI_DEBO_USUARIOS);
	
	if(!isset($VI_DEBO_USUARIOS)){
		sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
	}				
?>