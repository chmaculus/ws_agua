<?php




#----------------------------------------------------------------------------------------------
function estampar($imagen_origen, $imagen_destino, $fecha=0, $hora=0, $mzna=0, $casa=0){

// $anio = substr($fecha, 0, 4);
// $mes = substr($fecha, 4, 2);
// $dia = substr($fecha, 6, 2);
// $fecha="$dia/$mes/$anio";

//	echo "imagen_origen $imagen_origen\n";
//	echo "imagen_dest $imagen_destino\n";
	// Load the stamp and the photo to apply the watermark to
	$im = imagecreatefromjpeg($imagen_origen);

	$width  = imagesx($im) ;
	$height = imagesy($im);

	// echo "w: ".$width."<br>";
	// echo "h: ".$height."<br>";

	// $fecha=date("d/m/Y");
	// $hora=date("H:i");


	imagesavealpha($im, true);

	//create a fully transparent background (127 means fully transparent)
	$trans_background = imagecolorallocatealpha($im, 0, 0, 0, 127);

	$white = imagecolorallocate($im, 255, 255, 255);
	//echo $white."<br>";

	// Set Path to Font File
	$font_path = './times.ttf';

		/*
		 imagestring(
		    resource $image,
		    int $font,
		    int $x,
		    int $y,
		    string $string,
		    int $color
		): bool

		 imagettftext(
		    resource $image,
		    float $size,
		    float $angle,
		    int $x,
		    int $y,
		    int $color,
		    string $fontfile,
		    string $text
		): array

		*/

		// First we create our stamp image manually from GD
		$stamp = imagecreatetruecolor(200, 100);
		$x1=10;
		$y1=30;
		$size=25;
		$despl=33;


		/* datos que se imprimen en el estampado de la imagen*/
		$string_direccion="M".$mzna." C".$casa;
		imagettftext($stamp, $size, 0, $x1, $y1, $white, $font_path, $fecha);
		imagettftext($stamp, $size, 0, $x1, $y1+$despl, $white, $font_path, $hora);
		imagettftext($stamp, $size, 0, $x1, $y1+($despl*2), $white, $font_path, $string_direccion);

		//imagefilledrectangle($stamp, 0, 0, 99, 69, 0x0000FF);
		//imagefilledrectangle($stamp, 9, 9, 90, 60, 0xFFFFFF);


		/*
		 imagestring(
		    resource $image,
		    int $font,
		    int $x,
		    int $y,
		    string $string,
		    int $color
		): bool
		*/

		//imagestring($stamp, 1, 20, 20, $fecha, 0x0000FF);
		//imagestring($stamp, 2, 20, 40, $hora, 0x0000FF);

		// Set the margins for the stamp and get the height/width of the stamp image
		$marge_right = 10;
		$marge_bottom = 10;
		$sx = imagesx($stamp);
		$sy = imagesy($stamp);

		// Merge the stamp onto our photo with an opacity of 50%
		imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 50);

		// Save the image to file and free memory
		//header('Content-Type: image/png');
		//$aa=str_replace(".jpg",".png",$imagen_destino);
		imagejpeg($im, $imagen_destino);
		imagedestroy($im);

}
#----------------------------------------------------------------------------------------------







#----------------------------------------------------------------------------------------------
function graba_imagen($CONEXION, $path, $id_med, $periodo, $fecha_toma, $hora_toma, $imagen_base64, $datos_residente, $nombre){
		//$nombre=genera_nombre($codigo_cliente, $id_medidor, $periodo=0);
					
		log_this("log/imagen".date("Ym").".log"," p: ".$path." id: ".$id_med." per: ".$periodo." f: ".$fecha_toma." h: ".$hora_toma."\n");

					log_this("log/ws_a1_b16j".date("Ym").".log"," ".date("d H:i:s")." inicio graba temp\n");
					///graba temporal imagen
					$imagen=base64_decode($imagen_base64);

					$nom_temp="./tmp/temp".crc32(rand(100,10000000)).".jpg";

					$gestor = fopen($nom_temp, 'w');
					if (fwrite($gestor, $imagen) === FALSE) {
						fclose($gestor);
						$file_write=0;
							$array=array(	
								"MODULO" => "AGUA",
								"ACCION" => "EXPORT_DATA",
								"ID_MED" => '"'.$id_med.'"',
								"PERIODO" => '"'.$periodo.'"',
								"ERROR" => "Error al grabar archivo temporal"
							);
							log_this("log/ws_a1_b16j".date("Ym").".log",date("d H:i:s")." error al grabar temporal\n");
							echo json_encode($array);
								exit;
					}else{
						log_this("log/ws_a1_b16j".date("Ym").".log",date("d H:i:s")." temporal almacenado OK\n");
						fclose($gestor);
						$file_write=1;
					}
					$imagen="";
					#-------------------------------------------------------------------
					log_this("log/ws_a1_b16j".date("Ym").".log"," ".date("d H:i:s")." fin graba temp\n");



					#-------------------------------------------------------------------
					//verifica / crea  carpeta destino
					$periodo=str_replace("/","",$periodo);
					if(is_writable($path)){
							$path=$path.$periodo."\\";
							if (!file_exists($path)) {
							    mkdir($path, 0777, true);
							}
					}else{
						$dest_folder=1;
					}
					#-------------------------------------------------------------------
					log_this("log/ws_a1_b16j".date("Ym").".log", date("Y-m-d H:i:s"). " pasa carpeta destino\n");


					//echo "path: ".$path.$nombre."\n";

					// $tmp=split("/",$fecha_toma);
					// $fecha_toma=$tmp[2].$tmp[1].$tmp[0];

					//echo "nombre: $path$nombre\n";
					log_this("log/ws_a1_b16j".date("Ym").".log"," ".date("d H:i:s")." estampa datos nom_temp: $nom_temp path: $path.$nombre ftoma: ".$fecha_toma." htoma: ".$hora_toma." mzna: ".$datos_residente["MZNA"]."  casa: ".$datos_residente["CASA"]." \n");
					estampar($nom_temp, $path.$nombre, $fecha_toma, $hora_toma, $datos_residente["MZNA"], $datos_residente["CASA"]);
					log_this("log/ws_a1_b16j".date("Ym").".log"," ".date("d H:i:s")." pasa estampar\n");


					#elimino temporal
					log_this("log/ws_a1_b16j".date("Ym").".log"," ".date("d H:i:s")." elimina temporal\n");
					unlink($nom_temp);
					#fin graba imagen
}
#----------------------------------------------------------------------------------------------




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