<?php
include("../includes/connect.php");

$q='select * from agua';

$result = sqlsrv_query($CONEXION, $SQL);

while ($REG_VI_AGUA_RUTAS = sqlsrv_fetch_array($VI_AGUA_RUTAS,SQLSRV_FETCH_ASSOC)){
}





?>