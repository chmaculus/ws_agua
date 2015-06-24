<?php // A1-B13 = EXPORT DE LOS LOGS
        file_put_contents("log.txt", "4");

	if(!isset($_FILES['l'])){				
		$RETURN .= "<ERR>Faltan parametros opcionales: 'L'</ERR>";
	}
	else {
		$tmp_name = $_FILES["l"]["tmp_name"];
		$name = $_FILES["l"]["name"];
		
		if (file_exists($C_DEBOAGUA) == false) {
			mkdir($C_DEBOAGUA, 0777);
		}
		
		if (file_exists($C_DEBOAGUA ."\\". $C_LOG) == false) {
			mkdir($C_DEBOAGUA ."\\". $C_LOG, 0777);
		}
		
		$name = $C_DEBOAGUA . "\\" . $C_LOG . "\\log_" . date("d-m-Y_H-i") . ".txt";
		
		if(move_uploaded_file($tmp_name,$name)){
			$RETURN .= "<PROCESO>EXITO Exito exito</PROCESO>";
		}
		else {
			$RETURN .= "<PROCESO>FRACASO Fracaso fracaso</PROCESO>";
		}
	}
?>