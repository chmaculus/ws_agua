<?php // A2-B13 = EXPORT DEL LOG

        global $C_DEBOSANCION;
        global $C_LOG;
        global $RETURN;

	if(isset($_FILES['l'])){				

		$tmp_name = $_FILES["l"]["tmp_name"];
		$name = $_FILES["l"]["name"];
		//$name = "imgs/".$name;
		
		if (file_exists($C_DEBOSANCION) == false) {
			mkdir($C_DEBOSANCION, 0777);
		}
		
		if (file_exists($C_DEBOSANCION . "\\" . $C_LOG) == false) {
			mkdir($C_DEBOSANCION . "\\" . $C_LOG, 0777);
		}
		
		$name = $C_DEBOSANCION . "\\" . $C_LOG . "\\log_" . date("d-m-Y_H-i") . ".txt";
		
		if(move_uploaded_file($tmp_name,$name)){
			$RETURN .= "<PROCESO>EXITO UPLOAD</PROCESO>";
		}
		
	}
	$RETURN = "<fin>EXITO Exito exito</fin>";
?>