<?php 
// if (file_exists("log/ws_a1_b16j".date("Ym").".log")){
// 	unlink("log/ws_a1_b16j".date("Ym").".log");
// }

log_this("log/ws_a1_b16j".date("Ym").".log","\n\n".date("d H:i:s")." llega - ".$_SERVER['HTTP_USER_AGENT']."\n");

/*

inventar datos para prueba

ver si puedo recuperar exif

insert or update --- OK
verificar que no haya registros duplicados --- OK

ver config 
if exist folder ok
grabo archivo

construir consultas en base al array OK
ejecuto consultas OK
generar logs OK

crear carpetas periodo
error al almacenar imagen
RESPUESTAS JSON

*/



//$data = $dataa;

 //log_this("log/dataaa.log",print_r($data,true));
// log_this("log/bb.log","periodo: ".$data["PERIODO"]."\n");

/* falta codigo_cliente*/



$aa=split("/",$data["PERIODO"]);
$temp=$aa[1]."/".$aa[0];
$data["PERIODO"]=$temp;

$string_fecha=$fecha_toma." ".$data['HORA_TOMA'];



#-------------------------------------------------------------------
//verifica / crea  carpeta destino
$periodo=str_replace("/","",$data["PERIODO"]);
if(is_writable($path)){
		$path=$path.$periodo."\\";
		if (!file_exists($path)) {
		    mkdir($path, 0777, true);
		}
}else{
	$dest_folder=1;
}
#-------------------------------------------------------------------
log_this("log/ws_a1_b16j".date("Ym").".log", date("Y-m-d H:i:s")."pasa carpeta destino\n");




log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."genera nombre res: $residente  id_med: ".$data["ID_MED"]." periodo: ".$data["PERIODO"]." \n");
$nombre=genera_nombre($residente, $data["ID_MED"], $data["PERIODO"]);
//$nombre_png=str_replace(".jpg",".png",$nombre);
log_this("log/ws_a1_b16j".date("Ym").".log", date("Y-m-d H:i:s")."pasa genera nombre $nombre \n");

//echo "path: ".$path.$nombre."\n";

$tmp=split("/",$data["FECHA_TOMA"]);
$fecha_toma=$tmp[2].$tmp[1].$tmp[0];











#------------------------------------------------------------------
//verifica si el registro ya existe
if($data["ID_MED"]!="" and $data["PERIODO"]!=""){
	// Test para saber si ya existe la entrada en la BDD:
	$query1 = "SELECT * FROM AGUA_MEDICION WHERE ID_MED = '".$data["ID_MED"]."' AND PER = '".$data["PERIODO"]."'";

	$result = sqlsrv_query($CONEXION, $query1, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
	if(sqlsrv_errors()){
		log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."error sql 1\n");
		log_this("log/errores.log",date("H:i:s")."\n".$query1."\n");
		log_this("log/errores.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
	}
  

	//$result = sqlsrv_query($CONEXION, $query1);
	$rows=sqlsrv_num_rows($result);
	log_this("log/ws_a1_b16j".date("Ym").".log",date("d H:i:s")." - query1: ".$query1."\n");
	log_this("log/ws_a1_b16j".date("Ym").".log",date("d H:i:s")." - rows: ".$rows."\n");
}
#------------------------------------------------------------------
log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."pasa verifica registro\n");







if($rows>0){
			$array=array(	
				"MODULO" => "AGUA",
				"ACCION" => "EXPORT_DATA",
				"ID_MED" => '"'.$data["ID_MED"].'"',
				"PERIODO" => '"'.$data["PERIODO"].'"',
				"MENSAJE" => "YA EXISTE UNA MEDICION PARA ESTE PERIODO",
			);
		$json=json_encode($array);
		echo str_replace('\\"','',$json);
		exit;
}
























#------------------------------------------------------------------
//no existe registro
if($rows<1){
	log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."no existe registro inserta\n");
	$SQL = "INSERT INTO AGUA_MEDICION 
				(ID_MED, PER, LEAN, LEAC, VAL, FECHA_TOMA, ID_ERROR, OBSERVACION, ID_OPE, MODO, AUTORIZADO, PATH_FOTO) 
			VALUES 
				('".$data["ID_MED"]."', '".$data["PERIODO"]."', '".$data['LEAN']."', '".$data['LEAC']."', -1, '".$string_fecha."', 
					'".$data['ID_ERROR']."', '".$data['OBSERVACION']."', '".$data['ID_OPE']."', 'A', '0', '".$path.$nombre."')";

	log_this("log/sql".date("Y-m").".log",$SQL."\n\n");
	$result = sqlsrv_query( $CONEXION, $SQL);

	if(sqlsrv_errors()){
		log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."error sql 2\n");
		log_this("log/errores.log",date("H:i:s")."\n".$SQL."\n");
		log_this("log/errores.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
	}

	$affected=sqlsrv_rows_affected($result);
	sqlsrv_commit($CONEXION);

	
	log_this("log/sql".date("Y-m").".log",date("d H:i:s ")." affected ".$affected." -\n");

	if(!isset($result)){
		log_this("log/sql".date("Y-m").".log",date("d H:i:s").' - error insertar ID_MED - '.$data["ID_MED"].' - periodo - '.$data["PERIODO"].' \n');
		/* agregar id_table y N de serie tablet		*/
		$array=array(	
			"MODULO" => "AGUA",
			"ACCION" => "EXPORT_DATA",
			"ID_MED" => '"'.$data["ID_MED"].'"',
			"PERIODO" => '"'.$data["PERIODO"].'"',
			"ERROR" => "Error al insertar registro"
		);
		$json=json_encode($array);
		echo str_replace('\\"','',$json);
		exit;
	}


	if(isset($result)){
		log_this("log/sql".date("Y-m").".log",date("d H:i:s").'se inserto correctamente - ID_MED - '.$data["ID_MED"].' - periodo - '.$data["PERIODO"].'\n');
		/* agregar id_table y N de serie tablet		*/
			$array=array(	
				"MODULO" => "AGUA",
				"ACCION" => "EXPORT_DATA",
				"ID_MED" => '"'.$data["ID_MED"].'"',
				"PERIODO" => '"'.$data["PERIODO"].'"',
				"MENSAJE" => "Se inserto correctamente",
				"REGISTROS_AFECTADOS" => "$affected"
			);
		$json=json_encode($array);
	}
}
#------------------------------------------------------------------










#------------------------------------------------------------------------------------------
#------------------------------------------------------------------------------------------
#------------------------------------------------------------------------------------------
#------------------------------------------------------------------------------------------
#------------------------------------------------------------------------------------------
log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."inicio graba temp\n");
#-------------------------------------------------------------------
///graba temporal imagen
$imagen=base64_decode($data["IMAGEN"]);

$nom_temp="./tmp/temp".crc32(rand(100,10000000)).".jpg";

$gestor = fopen($nom_temp, 'w');
if (fwrite($gestor, $imagen) === FALSE) {
	fclose($gestor);
	$file_write=0;
		$array=array(	
			"MODULO" => "AGUA",
			"ACCION" => "EXPORT_DATA",
			"ID_MED" => '"'.$data["ID_MED"].'"',
			"PERIODO" => '"'.$data["PERIODO"].'"',
			"ERROR" => "Error al grabar archivo temporal"
		);
		log_this("log/ws_a1_b16j".date("Ym").".log",date("d H:i:s")."error al grabar temporal\n");
		echo json_encode($array);
			exit;
}else{
	log_this("log/ws_a1_b16j".date("Ym").".log",date("d H:i:s")."temporal almacenado OK\n");
	fclose($gestor);
	$file_write=1;
}
$imagen="";
#-------------------------------------------------------------------
log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."fin graba temp\n");

log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."estampa datos nom_temp: $nom_temp path: $path.$nombre ftoma: ".$data["FECHA_TOMA"]." htoma: ".$data["HORA_TOMA"]." mzna: ".$datos_residente["MZNA"]."  casa: ".$datos_residente["CASA"]." \n");
estampar($nom_temp, $path.$nombre, $data["FECHA_TOMA"], $data["HORA_TOMA"], $datos_residente["MZNA"], $datos_residente["CASA"]);
log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."pasa estampar\n");


log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."llama residente ".$data["ID_MED"]." \n");
$residente=medidor_trae_residente($CONEXION, $data["ID_MED"]);
log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."pasa trae residente\n");



log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."llama trae_datos_residente $residente\n");
$datos_residente=trae_datos_residente($CONEXION, $residente);
log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."pasa trae trae_datos_residente\n");





#elimino temporal
log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."elimina temporal\n");
unlink($nom_temp);
#------------------------------------------------------------------------------------------
#------------------------------------------------------------------------------------------
#------------------------------------------------------------------------------------------
#------------------------------------------------------------------------------------------
#------------------------------------------------------------------------------------------



echo str_replace('\\"','',$json);
exit;















	
// #------------------------------------------------------------------
// //ya existe el registro. actualizo
// if($rows>0){
// 	log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."existe registro actualiza\n");
// 	$SQL = "update AGUA_MEDICION set 
// 								LEAN='".$data['LEAN']."',
// 								LEAC='".$data['LEAC']."', 
// 								VAL='-1', 
// 								FECHA_TOMA='".$string_fecha."', 
// 								ID_ERROR='".$data['ID_ERROR']."', 
// 								OBSERVACION='".$data['OBSERVACION']."', 
// 								ID_OPE='".$data['ID_OPE']."', 
// 								MODO='A', 
// 								AUTORIZADO='0', 
// 								PATH_FOTO='".$path.$nombre."',
// 								ID_TABLET='".$data['ID_TABLET']."'
// 									where ID_MED='".$data["ID_MED"]."' and 
// 											PER='".$data["PERIODO"]."' 

// 					";


// 	log_this("log/sql".date("Y-m").".log",$SQL."\n");
	
// 	$RESP_UPDATE = sqlsrv_query($CONEXION, $SQL);

// 	if(sqlsrv_errors()){
// 		log_this("log/ws_a1_b16j".date("Ym").".log", date("d H:i:s")."error sql 3\n");
// 		log_this("log/errores.log",date("H:i:s")."\n".$SQL."\n");
// 		log_this("log/errores.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
// 	}

// 	$affected=sqlsrv_rows_affected($RESP_UPDATE);
// 	sqlsrv_commit($CONEXION);
	
// 	if(sqlsrv_errors()){
// 		log_this("log/errores.log",date("H:i:s")."\n".$query1."\n");
// 		log_this("log/errores.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
// 	}


// 	log_this("log/sql".date("Y-m").".log",date("d H:i:s ").print_r($RESP_UPDATE,true)." \n");
// 	log_this("log/sql".date("Y-m").".log",date("d H:i:s ")." affected ".$affected." \n");

// 	if(!isset($RESP_UPDATE)){
// 				log_this("log/sql".date("Y-m").".log",date("d H:i:s").' - error actualizar ID_MED - '.$data["ID_MED"].' - periodo - '.$data["PERIODO"].'\n');
// 			$array=array(	
// 				"MODULO" => "AGUA",
// 				"ACCION" => "EXPORT_DATA",
// 				"ID_MED" => '"'.$data["ID_MED"].'"',
// 				"PERIODO" => '"'.$data["PERIODO"].'"',
// 				"ERROR" => "Error al actualizar registro"
// 			);
// 		$json=json_encode($array);
// 		echo str_replace('\\"','',$json);
// 		exit;
// 	}

// 	if(isset($RESP_UPDATE)){
// 		log_this("log/sql".date("Y-m").".log",date("d H:i:s").'update ok - ID_MED - '.$data["ID_MED"].' - periodo - '.$data["PERIODO"].'\n');
// 			$array=array(	
// 				"MODULO" => "AGUA",
// 				"ACCION" => "EXPORT_DATA",
// 				"ID_MED" => '"'.$data["ID_MED"].'"',
// 				"PERIODO" => '"'.$data["PERIODO"].'"',
// 				"MENSAJE" => "Se actualizo correctamente",
// 				"REGISTROS_AFECTADOS" => '"'.$affected.'"'
// 			);
// 		$json=json_encode($array);
// 		echo str_replace('\\"','',$json);
// 			exit;
// 	}

// }
// #------------------------------------------------------------------



#------------------------------------------------------------------
$array=array(	
	"MODULO" => "AGUA",
	"ACCION" => "EXPORT_DATA",
	"ID_MED" => '"'.$data["ID_MED"].'"',
	"PERIODO" => '"'.$data["PERIODO"].'"',
	"STATUS" => "Los datos se almacenaron correctamente"
	
);
		$json=json_encode($array);
		echo str_replace('\\"','',$json);
	exit;
#------------------------------------------------------------------






/*
especificaciones del nombre de archivo a guardar

La FOTO debe almacenarse y transferirse a la base central con el siguiente formato de nombre:    L_CCCCC_DDDDDD_YYYYMM.jpg
L: prefijo indicativo de Lectura.  Longitud 1 Carácter.
CCCCC:  código de cliente. Longitud 5 caracteres. Se rellena con ceros a la izquierda.
DDDDDD: ID de medidor. Longitud 6 caracteres. Se rellena con ceros a la izquierda.
YYYY: año del periodo medido. Longitud 4 caracteres.
MM: mes del periodo medido. Longitud 2 caracteres. Se rellena con ceros a la izquierda
*/


#---------------------------------------------------------------------------
function genera_nombre($codigo_cliente, $id_medidor, $periodo=0){
	$periodo=str_replace("/","",$periodo);
	$len_cli=strlen($codigo_cliente);
		for($i=5;$i>$len_cli;$i--){
			$cstr=$cstr."0";
		}
	$len_med=strlen($id_medidor);
		for($i=6;$i>$len_cli;$i--){
			$istr=$istr."0";
		}
	$nombre="L_".$cstr.$codigo_cliente."_".$istr.$id_medidor."_".$periodo.".jpg";
	//echo $nombre."\n";
	return $nombre;
}
#---------------------------------------------------------------------------



?>