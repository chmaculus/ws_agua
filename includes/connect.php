<?php

// DECRIPTAR LOS DATOS DEL ARCHIVO PFS.INI:

$key = "Fs2goO0rcf1oat1U";

$name_file = "pfs" . $_REQUEST['a'] . ".ini";
//$file = fopen($name_file, "r");
//$flujo_leido = fread($file, 500);
//fclose($file);

log_this("log/connect.log",date("H:i:s")." pfs\n");

/*
$iv = mcrypt_create_iv(mcrypt_get_block_size(MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);
$cadena_decriptada = decrypt($flujo_leido, $key);
$tabla_string = explode(" ", $cadena_decriptada);

$servidor = $tabla_string[3];
$usuario = $tabla_string[4];
$pwd = $tabla_string[2];
$basededatos = $tabla_string[1];
*/

///// LOCAL
$server = "10.231.45.205\sql2019";
$username ="debo";
$password ="debo";
$database ="DOSSA_110322022";


$connectionInfo = array("Database"=>$database, "UID"=>$username, "PWD"=>$password);
$CONEXION = sqlsrv_connect( $server, $connectionInfo );

log_this("log/connect.log",date("H:i:s")."\n".print_r( $connectionInfo, true)."\n");

if (!$CONEXION) {
    log_this("log/connect.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
    $return=array("ERROR" => "No se pudo conectar con la base de datos");
    echo json_encode($return);
    log_this("log/connect.log"," exit\n");
    exit;
}
if( $CONEXION ) {
     log_this("log/connect.log","Conexión establecida\n");
}else{
     echo "Conexión no se pudo establecer.<br />";
     //log_this("log/connect.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
}

sqlsrv_begin_transaction($CONEXION);

//*****************************************************
//*****************************************************


?>

