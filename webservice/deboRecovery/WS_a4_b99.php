<?php // A4-B99 = FULL RECOVER

	if (isset($_FILES["x"])) {
					
		$tmp_name = $_FILES["x"]["tmp_name"];
		$name = $_FILES["x"]["name"];
		
		if ( file_exists($C_DEBORECOVERY) == false) {
			mkdir($C_DEBORECOVERY, 0777);
		}
		
		$name = $C_DEBORECOVERY ."\\". $name;
		
		if (file_exists($name) == false) {
			move_uploaded_file($tmp_name,$name);
		}
		
		echo "<PROCESO>EXITO Exito exito UPLOAD</PROCESO>";
		
	} // end if isset x
	
?>
