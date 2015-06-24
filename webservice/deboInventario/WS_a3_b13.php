<?php // A3-B13 = EXPORT DEL LOG [FIABILIZADO 29/12/2011]

	if(!isset($_FILES['l'])){				
		$RETURN .= "<ERR>Faltan parametros opcionales: 'L'</ERR>";
	}
	else {
		$tmp_name = $_FILES["l"]["tmp_name"];
		$name = $_FILES["l"]["name"];
		
		if (file_exists($C_DEBOINVENTARIO) == false) {
			mkdir($C_DEBOINVENTARIO, 0777);
		}
		
		if (file_exists($C_DEBOINVENTARIO . "\\" . $C_LOG) == false) {
			mkdir($C_DEBOINVENTARIO . "\\" . $C_LOG, 0777);
		}
		
		$name = $C_DEBOINVENTARIO . "\\" . $C_LOG . "\\" . $name;
		
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