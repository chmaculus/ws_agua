<?php

function medidor_trae_residente($CONEXION, $id_medidor){


	$query1 = "SELECT COD_RES FROM AGUA_MEDIDOR WHERE ID = '".$id_medidor."'" ;
	$query1 = 'SELECT COD_RES FROM AGUA_MEDIDOR WHERE ID = \''.$id_medidor.'\'';



	log_this("log/sql".date("Y-m").".log", "function medidor_trae_residente  #".$query1."\n" );
	log_this("log/sql".date("Y-m").".log", "id medidor: |".$id_medidor."|\n" );
	$result = sqlsrv_query($CONEXION, $query1, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$residente=sqlsrv_fetch_array($result);
	return $residente[0];

}

?>

