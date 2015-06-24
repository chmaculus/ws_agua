<?php // A11-B11 = EXPORT DE LOS DATOS DE PISCINA [FIABILIZADO 05/01/2012]

	if(!isset($_FILES['p'])){
		$RETURN .= "<ERR>Faltan parametros opcionales: 'P'</ERR>";
	}
	else {
		// Valores útiles:
			$FECHA_NOW = date("Y-m-d_H-i");
			$tmp_name = $_FILES["p"]["tmp_name"];
		
			if (!isset($_REQUEST['c'])) {
				$TABLET = 0;
			} else {
				$TABLET = $_REQUEST['c'];
			}
		
		// Test existencia CARPETA "deboinventario":
			if (file_exists($C_DEBOINVENTARIO) == false) {
				mkdir($C_DEBOINVENTARIO, 0777);
			}
			
		// Test existencia CARPETA "informes":
			if (file_exists($C_DEBOINVENTARIO . "\\" . $C_XML) == false) {
				mkdir($C_DEBOINVENTARIO . "\\" . $C_XML, 0777);
			}
			
		// Creación del archivo XML en el servidor distante:
			$name = $C_DEBOINVENTARIO . "\\" . $C_XML . "\\COPYCONF_INVENTARIO_".$TABLET."_".$FECHA_NOW.".xml";
				
				
		// Hacemos la copia-conforme del XML de los inventarios en la nueva carpeta:
			if(move_uploaded_file($tmp_name,$name)){
		
				// Cargamos el XML recién recibido (sacando los '&' y '<'):
					clean_xml_file($name);
					$xml = simplexml_load_file($name);
		
				// Empezamos a generar el XML:			
					$RETURN .= "<EXP_INVENT_FROM_TABLET>";
					$buffer  = '<?xml version="1.0" encoding="ISO-8859-1"?>
								<EXP_INVENT_FROM_TABLET>';
		
				// Vamos a establecer la lista de todos los inventarios asignados a la tablet (PALM) en estado 'B':
					$SQL_SELECT = "SELECT C.ID 
								   FROM ITOMINVC C, INV_PALM_REA A 
								   WHERE C.EST='B' AND A.NUM_INV=C.ID";
					$SELECT_RES = mssql_query($SQL_SELECT);
			
					$ind = 0;
					while ($TAB_RES = mssql_fetch_array($SELECT_RES)){
						$LISTA_INV_DESCARGAR[$ind] = $TAB_RES['ID'];
						$RETURN .= "<PARA_DESC>".$TAB_RES['ID']."</PARA_DESC>";
						$buffer .= "<PARA_DESC>".$TAB_RES['ID']."</PARA_DESC>";
						$ind++;
					}

				// Leemos uno por uno los datos de los inventarios:
					foreach ($xml->ART as $ARTICULO){
						
						// Lectura de los datos:
							$SEC     = $ARTICULO->S;
							$COD      = $ARTICULO->C;
							$INV      = $ARTICULO->I;
							$Q        = $ARTICULO->Q;
							$FEC_TEMP = $ARTICULO->FE;
							$FOTO     = $ARTICULO->FO;

						// Modificamos la fecha:
							if ($FEC_TEMP == '') {
								$FECHA = '';
							} else {
								$FECHA = new DateTime($FEC_TEMP);
								$FECHA = $FECHA->format('Ymd H:i:s');
							}
							//echo "<name>".$FECHA."</name>";
						
						// Actualizamos la cantidad de cada articulo en la BDD (si el inventario no a sido cancelado):
							if (in_array($INV , $LISTA_INV_DESCARGAR , false) == true) {
								$SQL = "UPDATE ITOMINVD SET CON=". $Q .", FECTOM='". $FECHA ."' WHERE INV=". $INV ." AND SEC=". $SEC ." AND ART=". $COD;
								$buffer .= "<REQ_UPDATE>". $SQL . "</REQ_UPDATE>";
								
								$UPDATE_INV = mssql_query($SQL);
								
								if(!isset($UPDATE_INV)){
									mssql_query("ROLLBACK TRANSACTION");
								}
								
								$buffer .= "<RESP_REQ_UPDATE>Successfull UPDATE: INVENTARIO[". $INV ."] ; SECTOR[". $SEC ."] ; ARTICULO[". $COD ."]</RESP_REQ_UPDATE>";
								$RETURN .= "<RESP_REQ_UPDATE>Successfull UPDATE: INVENTARIO[". $INV ."] ; SECTOR[". $SEC ."] ; ARTICULO[". $COD ."]</RESP_REQ_UPDATE>";
							}
					} // end foreach
				
				$RETURN .= "<RES>EXITO Exito exito</RES>";
				
				$RETURN .= "</EXP_INVENT_FROM_TABLET>";
				$buffer .= "</EXP_INVENT_FROM_TABLET>";
			
			} // end if(move_uploaded_file($tmp_name,$name))
			
			
		// GUARDAMOS LAS OPERACIONES SOBRE LA BDD EN UN REGISTRO MAS:
			$name_file=$C_DEBOINVENTARIO . "\\" . $C_XML . "\\SQLMVT_INVENTARIO_".$TABLET."_".date("Y-m-d_H-i").".xml";
			$file=fopen($name_file,"w+");
			fwrite($file,$buffer);
			fclose($file);

	} // end if(isset($_REQUEST['p']))				
?>