<?php 
	// A1-B12 = EXPORT DE LAS FOTOS [FIABILIZADO 16/12/2011]
        file_put_contents("log.txt", "3");

	if(!isset($_FILES['i'])){				
		$RETURN .= "<ERR>Faltan parametros opcionales: 'I'</ERR>";
	}
	else {
		$tmp_name = $_FILES["i"]["tmp_name"];
		$name = $_FILES["i"]["name"];
		
		if (file_exists($C_DEBOAGUA) == false) {
			mkdir($C_DEBOAGUA, 0777);
		}
		
		if (file_exists($C_DEBOAGUA . "\\" . $C_FOTO) == false) {
			mkdir($C_DEBOAGUA . "\\" . $C_FOTO, 0777);
		}

		$name = $C_DEBOAGUA . "\\" . $C_FOTO . "\\" . $name;
		
		if (file_exists($name) == false) {
			if(move_uploaded_file($tmp_name,$name)){
				$RETURN .= "<PROCESO>EXITO Exito exito</PROCESO>";
			}
			else {
				$RETURN .= "<PROCESO>FRACASO Fracaso fracaso</PROCESO>";
			}
		}
		else {
			$RETURN .= "<PROCESO>EXITO Exito exito</PROCESO>";
		}
	}
?>