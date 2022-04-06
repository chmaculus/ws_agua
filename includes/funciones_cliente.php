<?php

function medidor_trae_residente($id_medidor){

	$query1 = "SELECT COD_RES FROM AGUA_MEDIDOR WHERE ID = ".$id_medidor ;
	echo $query1."\n";
	$result = sqlsrv_query($CONEXION, $query1, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$residente=sqlsrv_fetch($result,0);
	return $residente;

}

?>

