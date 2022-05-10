<?php

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




?>