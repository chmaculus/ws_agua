<?php //EXPORT DE LAS FOTOS [FIABILIZADO 29/12/2011]

	if(!isset($_FILES['i'])){				
		$RETURN .= "<ERR>Faltan parametros opcionales: 'I'</ERR>";
	}
	else {
		$tmp_name = $_FILES["i"]["tmp_name"];
		$name = $_FILES["i"]["name"];
		
		if (file_exists($C_DEBOINVENTARIO) == false) {
			mkdir($C_DEBOINVENTARIO, 0777);
		}
		
		if (file_exists($C_DEBOINVENTARIO . "\\" . $C_FOTO) == false) {
			mkdir($C_DEBOINVENTARIO . "\\" . $C_FOTO, 0777);
		}
		
		$name = $C_DEBOINVENTARIO . "\\" . $C_FOTO . "\\" . $name;
		
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