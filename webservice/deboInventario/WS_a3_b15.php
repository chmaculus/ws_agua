<?php // A3-B15 = LIBERACIONES DE INVENTARIOS [FIABILIZADO 29/12/2011]

	if(!isset($_REQUEST['r'])){				
		$RETURN .= "<ERR>Faltan parametros opcionales: 'P'</ERR>";
	}
	else {				
		// Parametros: recuperamos los números de inventario y le estado que cambiar
			// EL parametro 'R' contiene 2 elementos: el numero de INV, y un valor "0" o "1"
			// que indica si hacemos que el inventario se cierra bien ("D") o si se cancela ("F").
			$TAB = explode(",", $_REQUEST['r']);
			
			$FECHA = new DateTime();
			$FECHA = $FECHA->format('Ymd H:i:s');
		
		// Construcción de las solicitudes "UPDATE":
			if ($TAB[1] == 1) {
				$SQL_ITOMINVC = "UPDATE ITOMINVC SET EST='D',FEC='". $FECHA ."' WHERE ID=".$TAB[0];
				$SQL_PALMREA = "UPDATE INV_PALM_REA SET C_HD='X' WHERE NUM_INV=".$TAB[0];
			} else {
				$SQL_ITOMINVC = "UPDATE ITOMINVC SET EST='F',FEC='". $FECHA ."' WHERE ID=".$TAB[0];
				$SQL_PALMREA = "UPDATE INV_PALM_REA SET C_HD='X' WHERE NUM_INV=".$TAB[0];
			}
		
			$buffer .= "<REQ_ITOMINVC>" . $SQL_ITOMINVC . "</REQ_ITOMINVC>";
			$buffer .= "<REQ_INV_PALM_REA>" . $SQL_PALMREA . "</REQ_INV_PALM_REA>";
		
		
		// UPDATE SOBRE ITOMINVC:
			$SQL_ITOMINVC_2 = mssql_query($SQL_ITOMINVC);
			
			if(!isset($SQL_ITOMINVC_2)){
				mssql_query("ROLLBACK TRANSACTION");
			}
			
			$buffer .= "<RESP_REQ_ITOMINVC>Successfull UPDATE: INVENTARIO[". $TAB[0] ."] ; ESTADO[". $TAB[1] ."]</RESP_REQ_ITOMINVC>";
		
		// UPDATE SOBRE INV_PALM_REA:
			// Al Walter no le sirve dice.
			// El Franco: decide al momento de generar el inventario si va a generar XML o TXT,
			// y asi sabe que hacer.	
			/*$SQL_PALMREA_2 = mssql_query($SQL_PALMREA);
		
			if(!isset($SQL_PALMREA_2)){
				mssql_query("ROLLBACK TRANSACTION");
			}*/
		
		// Resultado:
			$RETURN .= "<PROCESO>EXITO Exito exito</PROCESO>";
	}
?>