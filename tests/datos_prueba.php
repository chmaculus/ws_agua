
<?php
echo "1\n";


$path='\\\\10.231.45.108\\compartida\\fotos_agua\\AGUA\\';

include("../includes/connect.php");

echo "2\n";
$q="select top 200 * from agua_medicion where path_foto!='' order by PER desc";

echo $q."\n";

echo "3\n";
$result = sqlsrv_query($CONEXION, $q);

while ($data = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

$aa=explode('\\',$data["PATH_FOTO"]);
$imagen=end($aa);

echo "imagen: ".$path.$imagen."\n";

	$file=fopen($path.$imagen,"r");
	if(!$file){
		echo "Nothing\n";
	}
	$size=filesize($path.$imagen);
	echo "size: $size\n";
	$contenido=base64_encode(fread($file,$size));

			$array=array(	
							"MODULO" => "AGUA",
							"ACCION" => "EXPORT_DATA",
							"ID_MED" => '"'.$data["ID_MED"].'"',
							"PERIODO" => '"'.$data["PER"].'"',
							"LEAN" => '"'.$data["LEAN"].'"',
							"LEAC" => '"'.$data["LEAC"].'"',
							"VAL" => "-1",
							"FECHA_TOMA" => "20220311",
							"HORA_TOMA" => "11:22",
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
	
	log_this("log/".$data["ID_MED"].".json",json_encode($array));


}


function log_this($file_name, $data) {
    $file = fopen($file_name, 'w');
    fwrite($file, $data);
    fclose($file);
}



?>