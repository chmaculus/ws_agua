<?php

#--------------------------------------------------------------
function medidor_trae_residente($CONEXION, $id_medidor){
	$query1 = "SELECT COD_RES FROM AGUA_MEDIDOR WHERE ID = ".$id_medidor ;
	$result = sqlsrv_query($CONEXION, $query1, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$residente=sqlsrv_fetch_array($result);
	return $residente[0];
}
#--------------------------------------------------------------



#--------------------------------------------------------------
function trae_datos_residente($CONEXION, $id_residente){
	$query1 = "SELECT * FROM CLIENTES WHERE COD = ".$id_residente ;
	$result = sqlsrv_query($CONEXION, $query1, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$residente=sqlsrv_fetch_array($result);
	return $residente;
}
#--------------------------------------------------------------

?>

