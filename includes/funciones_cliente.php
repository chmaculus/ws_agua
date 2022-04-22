<?php

#--------------------------------------------------------------
function medidor_trae_residente($CONEXION, $id_medidor){
	$query1 = "SELECT COD_RES FROM AGUA_MEDIDOR WHERE ID = '".$id_medidor."'";
	$result = sqlsrv_query($CONEXION, $query1, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$rows=sqlsrv_num_rows($result);
	if($rows<1){
		log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."no se encontro medidor: $id_medidor \n");
	}
	

	if(sqlsrv_errors()){
		log_this("log/errores.log",date("H:i:s")."\n".$query1."\n");
		log_this("log/errores.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
	}
  
	$residente=sqlsrv_fetch_array($result);
	return $residente[0];
}
#--------------------------------------------------------------



#--------------------------------------------------------------
function trae_datos_residente($CONEXION, $id_residente){
	$query1 = "SELECT * FROM CLIENTES WHERE COD = '".$id_residente."'";
	$result = sqlsrv_query($CONEXION, $query1, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	$rows=sqlsrv_num_rows($result);
	if($rows<1){
		log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."no se encontro residente: $id_residente \n");
	}
	if(sqlsrv_errors()){
		log_this("log/errores.log",date("H:i:s")."\n".$query1."\n");
		log_this("log/errores.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
	}
	$residente=sqlsrv_fetch_array($result);
	return $residente;
}
#--------------------------------------------------------------

?>

