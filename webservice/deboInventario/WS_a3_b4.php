<?php // A3-B4 = IMPORTAR FOTO DE UN ARTICULO [FIABILIZADO 29/12/2011]
	if(!isset($_REQUEST['r'])) {
		$RETURN .= "<ERROR>Faltan parametros</ERROR>";
	}
	else {
		header('Content-Type: image/jpeg');
		
		$im = readfile("D:/articulos/".$_REQUEST['r']);
		imagejpeg($im);
		ImageDestroy($im);
		
		exit;
	}
?>