<?php // A5-B12 = EXPORT DE LAS FOTOS [FIABILIZADO 16/12/2011]
	if(isset($_FILES['i'])){
		$tmp_name = $_FILES["i"]["tmp_name"];
		$name = $_FILES["i"]["name"];
		
		if (file_exists($C_DEBOCOMPRA) == false) {
			mkdir($C_DEBOCOMPRA, 0777);
		}
		
		if (file_exists($C_DEBOCOMPRA . "\\" . $C_FOTO) == false) {
			mkdir($C_DEBOCOMPRA . "\\" . $C_FOTO, 0777);
		}
		
		$name = $C_DEBOCOMPRA . "\\" . $C_FOTO . "\\" . $name;
		
		if(move_uploaded_file($tmp_name,$name)){
			$RESULT .= "<PROCESO>EXITO Exito exito UPLOAD</PROCESO>";
		}
	}
?>