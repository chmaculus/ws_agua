
<?php
echo date("Y-m-d H:i:s")."\n";



include("../includes/connect.php");

$path='\\\\10.231.45.108\\compartida\\fotos_agua\\AGUA\\';

echo "2\n";
$q="select top 200 * from agua_medicion where path_foto!='' and path_foto not like '%10.231.45.108%' order by PER desc";

echo $q."\n";

echo "3\n";
$result = sqlsrv_query($CONEXION, $q);

while ($data = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

	$aa=explode('\\',$data["PATH_FOTO"]);
	$imagen=end($aa);

	

	$file=fopen($path.$imagen,"r");
	if(!$file){
		echo "Nothing<br>";
	}else{
		echo "imagen: ".$path.$imagen."\n";
		$size=filesize($path.$imagen);
		echo "size: $size\n";
		echo "<br>";
	}

	$contenido=base64_encode(fread($file,$size));
	//echo "01<br>";
	

			$array=array(	
							"MODULO" => "AGUA",
							"ACCION" => "EXPORT_DATA",
							"ID_MED" => '"'.$data["ID_MED"].'"',
							"PERIODO" => '"04/2022"',
							"LEAN" => '"'.$data["LEAN"].'"',
							"LEAC" => '"'.$data["LEAC"].'"',
							"VAL" => "-1",
							"FECHA_TOMA" => '"'.date("Ymd").'"',
							"HORA_TOMA" => '"'.date("H:i").'"',
							"ID_ERROR" => "0",
							"OBSERVACION" => '"'.$data["OBSERVACION"].'"',
							"ID_OPE" => '"'.$data["ID_OPE"].'"',
							"ID_TABLET" => "55",
							"MANZANA" => "44",
							"CASA" => "11",
							"NSERIE_TABLET" => "123456987",
							"NOMBRE_IMAGEN" => '"'.$imagen.'"',
							"IMAGEN" => '"'.$contenido.'"',
			);
	//echo "02<br>";
	
	if($file){
		$json=json_encode($array);
		//$string=stripslashes();
		$string=str_replace('\\"','',$json);

		log_this("json/".$data["ID_MED"].".json",$string);
		//log_this("log/".$data["ID_MED"].".json",$array);
	}
	//echo "03<br>";

	fclose($file);
	//echo "04<br>";

	
}


function log_this($file_name, $data) {
	unlink($file);
    $file = fopen($file_name, 'w');
    fwrite($file, $data);
    fclose($file);
}



?>