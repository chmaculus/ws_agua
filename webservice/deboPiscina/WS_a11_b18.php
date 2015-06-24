<?php // A11-B18 = EXPORT DE LOS RESIDENTES AUE PUEDEN ACEDER A LA PISCINA [FIABILIZADO 06/01/2012]

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
			$name = $C_DEBOPISCINA . "\\" . $C_XML . "\\COPYCONF_PISCINA_RESIDENTES_".$TABLET."_".$FECHA_NOW.".xml";
				
				
		// Hacemos la copia-conforme del XML de los inventarios en la nueva carpeta:
			if(move_uploaded_file($tmp_name,$name)){
		
				// Cargamos el XML recién recibido (sacando los '&' y '<'):
					clean_xml_file($name);
					$xml = simplexml_load_file($name);
		
				// Empezamos a generar el XML:			
					$RETURN .= "<EXP_PISCINA_RESIDENTES_FROM_TABLET>";
					$buffer  = '<?xml version="1.0" encoding="ISO-8859-1"?>
								<EXP_PISCINA_RESIDENTES_FROM_TABLET>';
		
				// Leemos uno por uno los datos de los inventarios:
					foreach ($xml->RES as $RESIDENTE){
						
						// Lectura de los datos:
							$CODIGO_OPE  = $RESIDENTE->COPE;
							$DOCUMENTO   = $RESIDENTE->DOC;
							$APELLIDO    = $RESIDENTE->APE;
							$NOMBRE      = $RESIDENTE->NOM;
							$ESTATUS     = $RESIDENTE->EST;
							$FECHA_NAC   = $RESIDENTE->FEC;
							$TIPO_CONSTR = $RESIDENTE->CTR;
							$CALLE       = $RESIDENTE->CAL;
							$ALTURA      = $RESIDENTE->ALT;
							$MANZANA     = $RESIDENTE->MZN;
							$LOTE        = $RESIDENTE->LOT;
							$PISO        = $RESIDENTE->PIS;
							$DPTO        = $RESIDENTE->DPT;
							$TELEFONO    = $RESIDENTE->TEL;
							$HABILITADO  = $RESIDENTE->HAB;
							
							//$RETURN .= "DOC=" . $DOCUMENTO . "//";
							//$RETURN .= "APE=" . $APELLIDO . "//";
							//$RETURN .= "NOM=" . $NOMBRE . "//";
							
						
						// Buscamos el RESIDENTE...
							$SQL_SELECT_RES =  "SELECT * FROM GROU_RESIDENTES
												WHERE 
													DOCUMENTO='". $DOCUMENTO ."' AND
													APELLIDO='". $APELLIDO ."'"
												;
												
							$buffer .= "<REQ_SELECT>". $SQL_SELECT_RES ."</REQ_SELECT>";					
							
							$SQL_SELECT_RES_RESULT = mssql_query($SQL_SELECT_RES);
							
							$RETURN .= "CANTIDAD=" . mssql_num_rows($SQL_SELECT_RES_RESULT);
						
							// ... si no existe los insertamos:
								if (mssql_num_rows($SQL_SELECT_RES_RESULT) <= 0) {
									
									// Insertamos el residente en la base de datos:
										$SQL_INDICE = "SELECT ID_RES FROM GROU_RESIDENTES ORDER BY ID_RES DESC";
										$SQL_INDICE_RESP = mssql_query($SQL_INDICE);
										if (mssql_num_rows($SQL_INDICE_RESP) != 0) {
											$TAB_RESP = mssql_fetch_array($SQL_INDICE_RESP);
											$INDICE_NUEVO = ($TAB_RESP[0] + 1);
										}
										else {
											$INDICE_NUEVO = 1;
										}											
										
										//$RETURN .= "Indice nuevo=" . $INDICE_NUEVO;
										//$RETURN .= "Apellido=" . $APELLIDO;
										//$RETURN .= "Documento=" . $DOCUMENTO;
										//$RETURN .= "Altura=" . $ALTURA;
										//$buffer .= "Indice nuevo=" . $INDICE_NUEVO;
										//$buffer .= "Apellido=" . $APELLIDO;
										//$buffer .= "Documento=" . $DOCUMENTO;
										//$buffer .= "Altura=" . $ALTURA;
									
										$SQL_INSERT_RES =  "INSERT INTO GROU_RESIDENTES
															VALUES (
																 ". $INDICE_NUEVO  		." ,
																'". $DOCUMENTO     		."', 
																'". $APELLIDO	   		."',  
																'". $NOMBRE   			."',
																'". $ESTATUS     		."',
																'". $FECHA_NAC   		."',
																'". $TIPO_CONSTR 		."',
																'". $CALLE      		."',
																 ". $ALTURA      		." ,
																'". $MANZANA    		."',
																'". $LOTE       		."',
																'". $PISO       		."',
																'". $DPTO        		."',
																'". $TELEFONO    		."',
																 ". $HABILITADO  	  	." ,
																'". date("Ymd H:i:s")	."',
																 ". $CODIGO_OPE 		." 																
															)";
										
										$buffer .= "<REQ_INSERT>". $SQL_INSERT_RES ."</REQ_INSERT>";
										
										$INSERT_CAB = mssql_query($SQL_INSERT_RES);
								
										if(!isset($INSERT_CAB)){
											mssql_query("ROLLBACK TRANSACTION");
										}
									
								}
						
							// ... si existe, copiamos los viejo valores a GROU_HISTO + UPDATE sobre GROU_RESIDENTE
								else {
									// Buscamos el RESIDENTE TAL CUAL ESTA:
										$RESIDENTE_RECUP = mssql_fetch_array($SQL_SELECT_RES_RESULT);
										
										//$RETURN .= $RESIDENTE_RECUP["DOCUMENTO"];
										//$RETURN .= $RESIDENTE_RECUP["APELLIDO"];
										//$RETURN .= $RESIDENTE_RECUP["NOMBRE"];
										
									// Copiamos los datos en GROU_HISTO_RESIDENTES:
										/*$SQL_INDICE = "SELECT ID_RES FROM GROU_HISTO_RESIDENTES ORDER BY ID_RES DESC";
										$SQL_INDICE_RESP = mssql_query($SQL_INDICE);
										if (mssql_num_rows($SQL_INDICE_RESP) != 0) {
											$TAB_RESP = mssql_fetch_array($SQL_INDICE_RESP);
											$INDICE_NUEVO = ($TAB_RESP[0] + 1);
										}
										else {
											$INDICE_NUEVO = 1;
										}		
									
										$SQL_INSERT =  "INSERT INTO GROU_HISTO_RESIDENTES
														VALUES (
															 ". $RESIDENTE_RECUP['ID_RES']			." ,
															 ". $RESIDENTE_RECUP['CODIGO_OPE']		." ,
															'". $RESIDENTE_RECUP['DOCUMENTO'] 		."',								
															'". $RESIDENTE_RECUP['APELLIDO']  		."', 
															'". $RESIDENTE_RECUP['NOMBRE']	   		."',  
															'". $RESIDENTE_RECUP['ESTATUS']   		."',
															'". $RESIDENTE_RECUP['FEC_NAC']     	."',
															'". $RESIDENTE_RECUP['TIPO_CONSTR']  	."',
															'". $RESIDENTE_RECUP['CALLE'] 			."',
															 ". $RESIDENTE_RECUP['ALTURA']      	." ,
															'". $RESIDENTE_RECUP['MANZANA']      	."',
															'". $RESIDENTE_RECUP['LOTE']    		."',
															'". $RESIDENTE_RECUP['PISO']       		."',
															'". $RESIDENTE_RECUP['DPTO']       		."',
															'". $RESIDENTE_RECUP['TEL']        		."',
															 ". $RESIDENTE_RECUP['HABILITADO']   	." ,
															'". $RESIDENTE_RECUP['FEC_CARGA']  		."',
															'". date("Ymd H:i") 					."'
														)";
										$buffer .= "<REQ_INSERT>". $SQL_INSERT ."</REQ_INSERT>";
										$SQL_INSERT_RESP = mssql_query($SQL_INSERT);
										if(!isset($SQL_INSERT_RESP)){
											mssql_query("ROLLBACK TRANSACTION");
										}*/
											
									// Actualizamos los datos de la tabla GROU_RESIDENTES:
										$SQL_UPDATE =  "UPDATE GROU_RESIDENTES
														SET
															DOCUMENTO='". $DOCUMENTO ."', 
															APELLIDO='". $APELLIDO ."', 
															NOMBRE='". $NOMBRE ."',
															ESTATUS='". $ESTATUS ."',
															FEC_NAC='". $FECHA_NAC ."', 
															TIPO_CONSTR='". $TIPO_CONSTR . "',
															CALLE='". $CALLE ."',
															ALTURA=". $ALTURA .", 
															MANZANA='". $MANZANA ."', 
															LOTE='". $LOTE ."', 
															PISO='". $PISO ."', 
															DPTO='". $DPTO ."', 
															TEL='". $TELEFONO ."', 
															HABILITADO='". $HABILITADO ."', 
															FEC_CARGA='". date("Ymd h:i:s") ."', 
															CODIGO_OPE=". $CODIGO_OPE ." 
														WHERE
															DOCUMENTO='". $RESIDENTE_RECUP['DOCUMENTO'] ."' AND 
															APELLIDO='". $RESIDENTE_RECUP['APELLIDO'] ."'"
														;
										$buffer .= "<REQ_UPDATE>". $SQL_UPDATE ."</REQ_UPDATE>";
										$RETURN .= $SQL_UPDATE;
										$SQL_UPDATE_RESP = mssql_query($SQL_UPDATE);
										if(!isset($SQL_UPDATE_RESP)){
											mssql_query("ROLLBACK TRANSACTION");
										}				
								}
								
					} // end foreach
				
				$RETURN .= "<RES>EXITO Exito exito</RES>";
				
				$RETURN .= "</EXP_PISCINA_RESIDENTES_FROM_TABLET>";
				$buffer .= "</EXP_PISCINA_RESIDENTES_FROM_TABLET>";
			
			} // end if(move_uploaded_file($tmp_name,$name))
			
			
		// GUARDAMOS LAS OPERACIONES SOBRE LA BDD EN UN REGISTRO MAS:
			$name_file=$C_DEBOPISCINA . "\\" . $C_XML . "\\SQLMVT_PISCINA_RESIDENTES_".$TABLET."_".date("Y-m-d_H-i-s").".xml";
			$file=fopen($name_file,"w+");
			fwrite($file,$buffer);
			fclose($file);

	} // end if(isset($_REQUEST['p']))				
?>