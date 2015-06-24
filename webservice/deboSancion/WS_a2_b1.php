<?php // B2-A1 = TODOS LOS RESIDENTES
        global $CONEXION;

	$SQL = "SELECT A.COD as ID, B.NOM AS NOMBRE, A.CALLE, A.NUMERO ,A.MZNA AS MANZANA, A.CASA, A.TEL AS TELEFONO
			FROM CLIENTES AS A
			INNER JOIN RESIDENTES AS B ON A.COD=B.COD_RES AND COD_HAB = 1
			WHERE A.BLO = 0";
	$VI_DEBO_RESIDENTE = sqlsrv_query($CONEXION, $SQL, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

	if(!isset($VI_DEBO_RESIDENTE)){
		sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
	}

	if(sqlsrv_num_rows($VI_DEBO_RESIDENTE) == 0){
		$RETURN .= "<VI_DEBO_RESIDENTE>No hay registro en la tabla.</VI_DEBO_RESIDENTE>";
		exit;
        }

	$RETURN .= "<VI_DEBO_RESIDENTE>";
        $RETURN .= "<CNT>". sqlsrv_num_rows($VI_DEBO_RESIDENTE) ." respuestas</CNT>";
	while ($REG_VI_DEBO_RESIDENTE=sqlsrv_fetch_array($VI_DEBO_RESIDENTE, SQLSRV_FETCH_ASSOC)){
		$RETURN .= "<R>";
			$RETURN .= "<ID>".str_replace("&","Y",$REG_VI_DEBO_RESIDENTE['ID'])."</ID>";
			$RETURN .= "<NOM>".str_replace("&","Y",$REG_VI_DEBO_RESIDENTE['NOMBRE'])."</NOM>";
			$RETURN .= "<CALLE>".str_replace("&","Y",$REG_VI_DEBO_RESIDENTE['CALLE'])."</CALLE>";
			$RETURN .= "<NUM>".$REG_VI_DEBO_RESIDENTE['NUMERO']."</NUM>";
			$RETURN .= "<MZNA>".$REG_VI_DEBO_RESIDENTE['MANZANA']."</MZNA>";														
			$RETURN .= "<CASA>".$REG_VI_DEBO_RESIDENTE['CASA']."</CASA>";							
			$RETURN .= "<TEL>".str_replace("&","Y",$REG_VI_DEBO_RESIDENTE['TELEFONO'])."</TEL>";
		$RETURN .= "</R>";
	}
	$RETURN .= "</VI_DEBO_RESIDENTE>";
	
	sqlsrv_free_stmt($VI_DEBO_RESIDENTE);
	
	if(!isset($VI_DEBO_RESIDENTE)){
		sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
	}
					
?>