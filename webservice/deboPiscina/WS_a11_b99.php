<?php //A11-B99 - FONCION DE RELLENO DE LA BASE DE DATOS CON LOS RESIDENTES

	// Control de existencia de los archivos y caretas fuentes:
		if (file_exists($C_DEBOPISCINA) == false) {
			mkdir($C_DEBOPISCINA, 0777);
		}

		if (file_exists($C_DEBOPISCINA . "\\" . $C_XML) == false) {
			mkdir($C_DEBOPISCINA . "\\" . $C_XML, 0777);
		}

		$name = $C_DEBOPISCINA . "\\actu.csv";
		if (file_exists($name) == true) {

			$CSV = file_get_contents($name);

			$TABLA_LINEAS = explode("\n", $CSV);
			
			for ($i = 0 ; $i < sizeOf($TABLA_LINEAS)-1 ; $i++) {
				$LINEA = $TABLA_LINEAS[$i];
				$TABLA_COLUMNAS = explode(";", $LINEA);
			
				if (is_numeric($TABLA_COLUMNAS[10]) == false) {
					$TABLA_COLUMNAS[10] = 0;
				}
			
				$SQL = "INSERT INTO GROU_DALVIAN_RESIDENTES
						VALUES (
							 ".($i+1).",
							 ".($i+1).",
							'".$TABLA_COLUMNAS[7]."',
							'".$TABLA_COLUMNAS[5]."', 
							'".$TABLA_COLUMNAS[6]."',
							'".$TABLA_COLUMNAS[8]."',
							'',
							'".$TABLA_COLUMNAS[0]."',
							'".$TABLA_COLUMNAS[9]."', 
							 ".$TABLA_COLUMNAS[10].",
							'".$TABLA_COLUMNAS[3]."',
							'".$TABLA_COLUMNAS[4]."',
							'".$TABLA_COLUMNAS[1]."',
							'".$TABLA_COLUMNAS[2]."',
							'',
							1,
							'".date("Ymd H:i:s")."'
						)";
				$RETURN .= "<SQL>".$SQL."</SQL>";
				$REQ_SQL = mssql_query($SQL);
			}
			$RETURN .= "<RES>Exito EXITO exito INSERTs</RES>";
		}
		else{
			$RETURN .= "<RES>No hay registro en la tabla.</RES>";
			exit;
		}









?>