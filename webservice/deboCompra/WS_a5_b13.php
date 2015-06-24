<?php // A5-B13 = EXPORT DEL LOG [FIABILIZADO 16/12/2011]
	if(isset($_FILES['l'])){	
		$tmp_name = $_FILES["l"]["tmp_name"];
		$name = $_FILES["l"]["name"];
		
		if (file_exists($C_DEBOCOMPRA) == false) {
			mkdir($C_DEBOCOMPRA, 0777);
		}
		
		if (file_exists($C_DEBOCOMPRA . "\\" . $C_LOG) == false) {
			mkdir($C_DEBOCOMPRA . "\\" . $C_LOG, 0777);
		}
		
		$name = $C_DEBOCOMPRA . "\\" . $C_LOG . "\\" . $name;
		
		if(move_uploaded_file($tmp_name,$name)){
			$RESULT .= "<PROCESO>EXITO Exito exito UPLOAD</PROCESO>";
		}
	}
?>