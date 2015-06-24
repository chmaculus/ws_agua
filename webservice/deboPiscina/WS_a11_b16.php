<?php // A11-B16 = EXPORT DE LAS ENTRADAS A LA PISCINA [FIABILIZADO 06/01/2012]

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
			$name = $C_DEBOPISCINA . "\\" . $C_XML . "\\COPYCONF_PISCINA_ENTRADAS_".$TABLET."_".$FECHA_NOW.".xml";
				
				
		// Hacemos la copia-conforme del XML de los inventarios en la nueva carpeta:
			if(move_uploaded_file($tmp_name,$name)){
		
				// Cargamos el XML recién recibido (sacando los '&' y '<'):
					clean_xml_file($name);
					$xml = simplexml_load_file($name);
		
				// Empezamos a generar el XML:			
					$RETURN .= "<EXP_PISCINA_ENTRADAS_FROM_TABLET>";
					$buffer  = '<?xml version="1.0" encoding="ISO-8859-1"?>
								<EXP_PISCINA_ENTRADAS_FROM_TABLET>';
		
				// Leemos uno por uno los datos de los inventarios:
					foreach ($xml->ENT as $ENTRADA){
						
						// Lectura de los datos:
							$FECHA    = $ENTRADA->FE;
							$OPE_ID   = $ENTRADA->I;
							$OPE_NOM  = $ENTRADA->NO;
							$RES_COPE = $ENTRADA->CO;
							$RES_DOC  = $ENTRADA->DO;
							$RES_APE  = $ENTRADA->A;
							$RES_NOM  = $ENTRADA->NR;
							$RES_EST  = $ENTRADA->E;
							$RES_FEC  = $ENTRADA->FN;
							$RES_CON  = $ENTRADA->CN;
							$RES_CAL  = $ENTRADA->CA; 
							$RES_ALT  = $ENTRADA->AL;
							$RES_MZN  = $ENTRADA->M;
							$RES_LOT  = $ENTRADA->L;
							$RES_PIS  = $ENTRADA->P;
							$RES_DPT  = $ENTRADA->DP;
							$RES_TEL  = $ENTRADA->T;
							$RES_HAB  = $ENTRADA->H;
							$REVI     = $ENTRADA->R;

						// Modificamos la fecha:
							$FECHA = new DateTime($FECHA);
							$FECHA = $FECHA->format('Ymd H:i:s');
							
							//echo "<name>".$FECHA."</name>";
						
						// A ver si ya esta la entrada:
							$SQL_PRE_TEST = "SELECT * FROM GROU_ENTRADAS
											 WHERE 
												FECHA_ENTRADA='". $FECHA ."' AND
												RES_CODOPE='". $RES_COPE ."' AND
												RES_DOC='". $RES_DOC ."' AND
												RES_APELLIDO='". $RES_APE ."'
											";
							$SQL_RESP = mssql_query($SQL_PRE_TEST);
							
						
						// Insertamos la entrada en la base de datos:
							if (mssql_num_rows($SQL_RESP) <= 0) {
								$SQL_INSERT = 	"INSERT INTO GROU_ENTRADAS
												 VALUES (
													'". $FECHA                	."',
													'". date("Ymd H:i:s")     	."',
													 ". $OPE_ID               	." ,
													'". $OPE_NOM 				."',
													 ". $RES_COPE				." ,
													'". $RES_DOC				."',
													'". $RES_APE				."',
													'". $RES_NOM				."',
													'". $RES_EST 				."',
													'". $RES_FEC				."',
													'". $RES_CON  				."',
													'". $RES_CAL 				."',
													 ". $RES_ALT				." ,
													'". $RES_MZN 				."',
													'". $RES_LOT 				."',
													'". $RES_PIS 				."',
													'". $RES_DPT 				."',
													'". $RES_TEL				."',
													 ". $RES_HAB				." ,
													 ". $REVI    				."
													)";
								
								$buffer .= "<REQ_INSERT>". $SQL_INSERT ."</REQ_INSERT>";
								
								$INSERT_CAB = mssql_query($SQL_INSERT);
						
								if(!isset($INSERT_CAB)){
									mssql_query("ROLLBACK TRANSACTION");
								}
							}
							else {
								$buffer .= "<REQ_INSERT>Dato ya existente en la base de datos</REQ_INSERT>";
							}
							
					} // end foreach
				
				$RETURN .= "<RES>EXITO Exito exito</RES>";
				
				$RETURN .= "</EXP_PISCINA_ENTRADAS_FROM_TABLET>";
				$buffer .= "</EXP_PISCINA_ENTRADAS_FROM_TABLET>";
			
			} // end if(move_uploaded_file($tmp_name,$name))
			
			
		// GUARDAMOS LAS OPERACIONES SOBRE LA BDD EN UN REGISTRO MAS:
			$name_file=$C_DEBOPISCINA . "\\" . $C_XML . "\\SQLMVT_PISCINA_ENTRADAS_".$TABLET."_".date("Y-m-d_H-i").".xml";
			$file=fopen($name_file,"w+");
			fwrite($file,$buffer);
			fclose($file);

	} // end if(isset($_REQUEST['p']))				
?>