



<?php

$serverName = "10.231.45.205\sql2019";
$username ="debo";
$password ="debo";
$database ="DOSSA_110322022";


#$serverName = "serverName\sqlexpress";
$connectionInfo = array( "Database"=>"DOSSA_110322022", "UID"=>"debo", "PWD"=>"debo" );
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false ) {
     die( print_r( sqlsrv_errors(), true));
}



$path="\\\\10.231.45.108\imagenes";
$string_fecha=$data['FECHA_TOMA']." ".$data['HORA_TOMA'];







$sql = "INSERT INTO temp1 (id, data) VALUES (?, ?)";
$params = array(1, "some data");
$stmt = sqlsrv_query( $conn, $sql, $params);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}




$array=array($data['ID_MED'], $data['PERIODO'], $data['LEAN'], $data['LEAC'], -1, $string_fecha, 
					$data['ID_ERROR'], $data['OBSERVACION'], $data['ID_OPE'], 'A', '0', $path);
					
$SQL = "INSERT INTO AGUA_MEDICION 
				(ID_MED, PER, LEAN, LEAC, VAL, FECHA_TOMA, ID_ERROR, OBSERVACION, ID_OPE, MODO, AUTORIZADO, PATH_FOTO) 
			VALUES 
				(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
	

$SQL="INSERT INTO AGUA_MEDICION 
				(ID_MED, PER, LEAN, LEAC, VAL, FECHA_TOMA, ID_ERROR, OBSERVACION, ID_OPE, MODO, AUTORIZADO, PATH_FOTO) 
			VALUES 
				('23', '2022/03', '1133', '1144', -1, '20220311 11:22', 
					'5', 'JEJEJEJE', '33', 'A', '0', '\\10.231.45.108\imagenes')";

$stmt = sqlsrv_query( $conn, $SQL, $array);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}













?>
