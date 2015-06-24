<?php
	echo "Funcion desativada por razones de seguridad";

	/*
	$servidor = "localhost";
	$usuario = "debo_head";
	$pwd = "DEBO";
	$basededatos = "DEBO_HEAD";

	
	//$servidor = "192.167.1.189\SQL2008R2ADV";
	//$usuario = "sa";
	//$pwd = "xxzza";
	//$basededatos = "DALVIAN_06122011";
	

	$CONEXION = sqlsrv_connect($servidor, array("UID"=>$usuario, "PWD"=>$pwd, "Database"=>$basededatos));
	if( !$CONEXION ) {
		echo "<error>Connection could not be established.</error>";
		exit;
	}
	sqlsrv_begin_transaction($CONEXION);
	
	
	$myFile = "source_recup.txt";
	$stream = fopen($myFile, 'r');
	
	$array_temp_sql = array();
	
	while ( ($line = fgets($stream)) != false) {
		$al = explode(";", $line);
		
		$id_med = $al[1];
		$per = trim($al[2]);
		$fecha = trim($al[3]);
		$lean = $al[4];
		$leac = $al[5];
		$ope = $al[8];
		$error = $al[9];
		
		$array_temp_sql[] = "INSERT INTO AGUA_MEDICION VALUES(". $id_med .",'". $per ."',". $lean .",". $leac .",-1,'". str_replace("/","-",$fecha) ."',". $error .",'',". $ope .",'A')";
	}
	fclose($stream);
	
	
	$stream_w = fopen("final_recup.sql","w+");
	foreach ($array_temp_sql as $sql) {
		fwrite($stream_w, $sql . PHP_EOL);
		sqlsrv_query($CONEXION, $sql);
	}
	fclose($stream_w);
	
	sqlsrv_commit($CONEXION);
	
	sqlsrv_close($CONEXION);
	
	
	echo "TERMINADO";
	*/
?>