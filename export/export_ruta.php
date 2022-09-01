<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="iso-8859-1">
</head>
<?php 

if(!$_POST["ruta"]){
	echo '<center>';
	echo "Ingrese ruta: ";
	echo '<form action="'.$_SERVER["SCRIPT_NAME"].'" method="post">';
	echo '<input type="text" name="ruta" size="5">';
	echo '<input type="submit" name="ACEPTAR" value="ACEPTAR">';
	echo '</form>';
	exit;
}


$server = "10.231.45.205\sql2019";
$username ="debo";
$password ="debo";
$database ="DOSSA_17-08-2022";


$connectionInfo = array("Database"=>$database, "UID"=>$username, "PWD"=>$password);
$CONEXION = sqlsrv_connect( $server, $connectionInfo );

/*
ID_MED|NUM_MED|CALLE|NUMERO|MZNA|CASA|PISO|DEPTO|TORRE|RUTA
*/

$q='select * from VI_AGUA_A_TABLET where RUTA in('.$_POST["ruta"].')';
$result = sqlsrv_query($CONEXION, $q, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
//$result = sqlsrv_query($CONEXION, $q, array(), array());
if(sqlsrv_errors()){
	echo $q."<br>";
	echo print_r(sqlsrv_errors(),true);
	}

echo "ID_MED|NUM_MED|RUTA||NUM_MED||CALLE||NUMERO||MZNA||CASA||PISO||DEPTO||TORRE||RUTA<br>\n";
// 	echo "rows: ".sqlsrv_num_rows($result)."<br>";
// echo print_r(sqlsrv_fetch_array($result),true);
while ($row= sqlsrv_fetch_array($result)){
	echo $row["ID_MED"]."|".trim($row["NUM_MED"])."|".trim($row["RUTA"])."||".trim($row["NUM_MED"])."||".trim($row["CALLE"])."||".trim($row["NUMERO"])."||".trim($row["MZNA"])."||".trim($row["CASA"])."||".trim($row["PISO"])."||".trim($row["DEPTO"])."||".trim($row["TORRE"])."||".trim($row["RUTA"])."<br>\n";
}



?>