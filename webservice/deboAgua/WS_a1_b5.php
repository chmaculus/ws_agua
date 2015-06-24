<?php // A1-B5 = CONTROL DE ACCESO DE LOS ADMINS Y OPERADORES [FIABILIZADO 16/12/2011]
        file_put_contents("log.txt", "9");

	if(!isset($_REQUEST['r'])){
		$RETURN .= "<ERR>Faltan parametros opcionales: 'R'</ERR>";
	}
	else {
		// Recuperación de los parametros:
			$param = $_REQUEST['r'];
			$tab = explode(",", $param);
			$log = $tab[0];
			$pas = $tab[1];
			
			if ($log == '' || $pas == '') {
				$RETURN .= "<ERR>Parametros de CONTROL de USUARIO incorrectos: ". $log ." // ". $pas ."</ERR>";
			}
			else {
		
				// Mostrar resultado:
					$RETURN .= "<CONTROL_OPE>";
					
				// Los GRUPOS ACEPTADOS SON: grupo1-todos Y grupo4-todosSalvoSGR1
					$SQL = "SELECT * FROM USUARIOS WHERE NOM='". $log ."' AND PWD='". $pas ."' AND GRP IN (1,3,4)";
                                        file_put_contents("logagua.txt", $SQL,8);
					$RES = sqlsrv_query($CONEXION, $SQL, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

					if(!isset($RES)){
						sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
					}

					if(sqlsrv_num_rows($RES) == 0){
						$RETURN .= "FALSE false False";
					} else {
						$RETURN .= "TRUE True true";
					}
					
				// Al final:
					sqlsrv_free_stmt($RES);
					if(!isset($RES)){
						sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
					}
				
					$RETURN .= "</CONTROL_OPE>";
			}
	}
?>