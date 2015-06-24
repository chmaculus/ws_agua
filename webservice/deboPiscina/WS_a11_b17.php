<?php // A11-B17 = EXPORT DE LAS REVISACIONES REALIZADAS EN LA PISCINA [FIABILIZADO 06/01/2012]

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
			if (file_exists($C_DEBOPISCINA) == false) {
				mkdir($C_DEBOPISCINA, 0777);
			}
			
		// Test existencia CARPETA "informes":
			if (file_exists($C_DEBOPISCINA . "\\" . $C_XML) == false) {
				mkdir($C_DEBOPISCINA . "\\" . $C_XML, 0777);
			}
			
		// Creación del archivo XML en el servidor distante:
			$name = $C_DEBOPISCINA . "\\" . $C_XML . "\\COPYCONF_PISCINA_REVISACIONES_".$TABLET."_".$FECHA_NOW.".xml";
				
				
		// Hacemos la copia-conforme del XML de los inventarios en la nueva carpeta:
			if(move_uploaded_file($tmp_name,$name)){
		
				// Cargamos el XML recién recibido (sacando los '&' y '<'):
					clean_xml_file($name);
					$xml = simplexml_load_file($name);
		
				// Empezamos a generar el XML:			
					$RETURN .= "<EXP_PISCINA_REVISACIONES_FROM_TABLET>";
					$buffer  = '<?xml version="1.0" encoding="ISO-8859-1"?>
								<EXP_PISCINA_REVISACIONES_FROM_TABLET>';
		
				// Leemos uno por uno los datos de los inventarios:
					foreach ($xml->REV as $REVISACION){
						
						// Lectura de los datos:
							$FECHA_REVISACION = $REVISACION->FEC;
							$RES_DOC   		  = $REVISACION->R_D;
							$VENCIMIENTO      = $REVISACION->VEC;
							$MEDICO           = $REVISACION->MED;
							$OPE_ID           = $REVISACION->OPE;
							$OBS              = $REVISACION->OBS;

						// Modificamos la fecha:
							$FECHA = new DateTime($FECHA_REVISACION);
							$FECHA = $FECHA->format('Ymd H:i:s');
							
							//echo "<name>".$FECHA."</name>";
						
						// Insertamos la entrada en la base de datos:
							$SQL_INSERT = 	"INSERT INTO GROU_REVISACIONES
											 VALUES (
												'". $FECHA                	."',
												'". date("Ymd H:i")     	."',
												'". $RES_DOC               	."',
												'". $VENCIMIENTO 			."',
												'". $MEDICO					."',
												 ". $OPE_ID					." ,
												'". $OBS					."'
												)";
							
							$buffer .= "<REQ_INSERT>". $SQL_INSERT ."</REQ_INSERT>";
							
							$INSERT_CAB = mssql_query($SQL_INSERT);
					
							if(!isset($INSERT_CAB)){
								mssql_query("ROLLBACK TRANSACTION");
							}
							
					} // end foreach
				
				$RETURN .= "<RES>EXITO Exito exito</RES>";
				
				$RETURN .= "</EXP_PISCINA_REVISACIONES_FROM_TABLET>";
				$buffer .= "</EXP_PISCINA_REVISACIONES_FROM_TABLET>";
			
			} // end if(move_uploaded_file($tmp_name,$name))
			
			
		// GUARDAMOS LAS OPERACIONES SOBRE LA BDD EN UN REGISTRO MAS:
			$name_file=$C_DEBOPISCINA . "\\" . $C_XML . "\\SQLMVT_PISCINA_REVISACIONES_".$TABLET."_".date("Y-m-d_H-i").".xml";
			$file=fopen($name_file,"w+");
			fwrite($file,$buffer);
			fclose($file);

	} // end if(isset($_REQUEST['p']))				
?>