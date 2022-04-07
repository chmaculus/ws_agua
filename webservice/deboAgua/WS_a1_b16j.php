<?php 

/*

inventar datos para prueba

ver si puedo recuperar exif

insert or update --- OK
verificar que no haya registros duplicados --- OK

ver config 
if exist folder ok
grabo archivo

construr consultas en base al array OK
ejecuto consultas OK
generar logs OK

crear carperas periodo
error al almacenar imagen
RESPUESTAS JSON

*/



$data = $dataa;
$path="\\\\10.231.45.108\imagenes\\";

 //log_this("log/dataaa.log",print_r($data,true));
// log_this("log/bb.log","periodo: ".$data["PERIODO"]."\n");

/* falta codigo_cliente*/


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
			echo json_encode($array);
			exit;
}else{
	fclose($gestor);
	$file_write=1;
}
$imagen="";
#-------------------------------------------------------------------


$residente=medidor_trae_residente($CONEXION, $data["ID_MED"]);



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



$nombre=genera_nombre($residente, $data["ID_MED"], $data["PERIODO"]);

//echo "path: ".$path.$nombre."\n";

//function estampar($imagen_origen, $imagen_destino, $fecha=0, $hora=0, $mzna=0, $casa=0){
$fecha_toma=date("d/m/Y",strtotime($data["FECHA_TOMA"]));
estampar($nom_temp, $path.$nombre, $fecha_toma, $data["FECHA_HORA"], $mzna=0, $casa=0);


//elimino temporal
unlink($nom_temp);

$array=array(	
	"MODULO" => "AGUA",
	"ACCION" => "EXPORT_DATA",
	"ID_MED" => '"'.$data["ID_MED"].'"',
	"PERIODO" => '"'.$data["PERIODO"].'"',
	"STATUS" => "Los datos se almacenaron correctamente"
	
);
	echo json_encode($array);
	exit;



#------------------------------------------------------------------
//verifica si el registro ya existe
if($data["ID_MED"]!="" and $data["PERIODO"]!=""){
	// Test para saber si ya existe la entrada en la BDD:
	$query1 = "SELECT * FROM AGUA_MEDICION WHERE ID_MED = ".$data["ID_MED"]." AND PER = '".$data["PERIODO"]."'";

	$result = sqlsrv_query($CONEXION, $query1, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
  log_this("log/errores.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));

	//$result = sqlsrv_query($CONEXION, $query1);
	$rows=sqlsrv_num_rows($result);
	log_this("log/sql".date("Y-m").".log",date("d H:i:s")." - query1: ".$query1."\n");
	log_this("log/sql".date("Y-m").".log",date("d H:i:s")." - rows: ".$rows."\n");
}
#------------------------------------------------------------------




$string_fecha=$data['FECHA_TOMA']." ".$data['HORA_TOMA'];


#------------------------------------------------------------------
//no existe registro
if($rows<1){
	$SQL = "INSERT INTO AGUA_MEDICION 
				(ID_MED, PER, LEAN, LEAC, VAL, FECHA_TOMA, ID_ERROR, OBSERVACION, ID_OPE, MODO, AUTORIZADO, PATH_FOTO) 
			VALUES 
				('".$data["ID_MED"]."', '".$data["PERIODO"]."', '".$data['LEAN']."', '".$data['LEAC']."', -1, '".$string_fecha."', 
					'".$data['ID_ERROR']."', '".$data['OBSERVACION']."', '".$data['ID_OPE']."', 'A', '0', '".$path."')";

	log_this("log/sql".date("Y-m").".log",date("d H:i:s")." - ".$_SERVER['HTTP_USER_AGENT']."\n");
	log_this("log/sql".date("Y-m").".log",$SQL."\n\n");
	$result = sqlsrv_query( $CONEXION, $SQL);
	sqlsrv_commit($CONEXION);

	
	$affected=sqlsrv_rows_affected($result);
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
			echo json_encode($array);
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
			echo json_encode($array);
			exit;

	}
}
#------------------------------------------------------------------



	
#------------------------------------------------------------------
//ya existe el registro. actualizo
if($rows>0){
	$SQL = "update AGUA_MEDICION set 
								LEAN='".$data['LEAN']."',
								LEAC='".$data['LEAC']."', 
								VAL='-1', 
								FECHA_TOMA='".$string_fecha."', 
								ID_ERROR='".$data['ID_ERROR']."', 
								OBSERVACION='".$data['OBSERVACION']."', 
								ID_OPE='".$data['ID_OPE']."', 
								MODO='A', 
								AUTORIZADO='0', 
								PATH_FOTO='".$path."'
									where ID_MED='".$data["ID_MED"]."' and 
											PER='".$data["PERIODO"]."' 

					";

	log_this("log/sql".date("Y-m").".log",date("d H:i:s")." - ".$_SERVER['HTTP_USER_AGENT']."\n");
	log_this("log/sql".date("Y-m").".log",$SQL."\n");
	
	$RESP_UPDATE = sqlsrv_query($CONEXION, $SQL);
	sqlsrv_commit($CONEXION);
	
	$affected=sqlsrv_rows_affected($RESP_UPDATE);

	log_this("log/sql".date("Y-m").".log",date("d H:i:s ").print_r($RESP_UPDATE,true)." \n");
	log_this("log/sql".date("Y-m").".log",date("d H:i:s ")." affected ".$affected." \n");

	if(!isset($RESP_UPDATE)){
				log_this("log/sql".date("Y-m").".log",date("d H:i:s").' - error actualizar ID_MED - '.$data["ID_MED"].' - periodo - '.$data["PERIODO"].'\n');
			$array=array(	
				"MODULO" => "AGUA",
				"ACCION" => "EXPORT_DATA",
				"ID_MED" => '"'.$data["ID_MED"].'"',
				"PERIODO" => '"'.$data["PERIODO"].'"',
				"ERROR" => "Error al actualizar registro"
			);
			echo json_encode($array);
			exit;
	}

	if(isset($RESP_UPDATE)){
		log_this("log/sql".date("Y-m").".log",date("d H:i:s").'update ok - ID_MED - '.$data["ID_MED"].' - periodo - '.$data["PERIODO"].'\n');
			$array=array(	
				"MODULO" => "AGUA",
				"ACCION" => "EXPORT_DATA",
				"ID_MED" => '"'.$data["ID_MED"].'"',
				"PERIODO" => '"'.$data["PERIODO"].'"',
				"MENSAJE" => "Se actualizo correctamente",
				"REGISTROS_AFECTADOS" => '"'.$affected.'"'
			);
			echo json_encode($array);
			exit;
	}

}
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
	$nombre="L".$cstr.$codigo_cliente.$istr.$id_medidor.$periodo.".jpg";
	//echo $nombre."\n";
	return $nombre;
}
#---------------------------------------------------------------------------


?>