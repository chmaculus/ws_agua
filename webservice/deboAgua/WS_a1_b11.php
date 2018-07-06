<?php 
	// A1-B11 = EXPORT DATOS DE LAS MEDICIONES [FIABILIZADO 29/12/2011]
		// Modificacion MSSQL -> SQLSRV (06/03/2012)
			        file_put_contents("log.txt", "2");

	if(!isset($_FILES['p'])){
		$RETURN .= "<ERR>Faltan parametros opcionales: 'P'</ERR>";
	}
	else {
		// Valores �tiles:
			$tmp_name = $_FILES["p"]["tmp_name"];
			$FECHA_NOW = date("Y-m-d_H-i");
		
			if (!isset($_REQUEST['c'])) {
				$TABLET = 0;
			}
			else {
				$TABLET = $_REQUEST['c'];
			}
		
		// Test existencia CARPETA "deboagua":
			if (file_exists($C_DEBOAGUA) == false) {
				mkdir($C_DEBOAGUA, 0777);
			}
		
		// Test existencia CARPETA "informes":
			if (file_exists($C_DEBOAGUA . "\\" . $C_XML) == false) {
				mkdir($C_DEBOAGUA . "\\" . $C_XML, 0777);
			}
		
		// Creaci�n del archivo XML en el servidor distante:
			$name = $C_DEBOAGUA . "\\" . $C_XML . "\\COPYCONF_DALVIAN_".$TABLET."_".$FECHA_NOW.".xml";
		
		if(move_uploaded_file($tmp_name,$name)){
			
			// Cargamos el XML reci�n recibido (sacando los '&' y '<'):
				clean_xml_file($name);
				$xml = simplexml_load_file($name);
					
			// Empezamos a generar el XML:			
				$RETURN .= "<VI_AGUA_FROM_TABLET_P>";
				$buffer  = '<?xml version="1.0" encoding="ISO-8859-1"?>
							<VI_AGUA_FROM_TABLET_P>';	
			
			// Lectura iterada de las MEDICIONES recibidas:
				foreach ($xml->MEDICION as $MEDICION){
					
					// Lectura de los datos:
						$ID_MED = $MEDICION->ID_MED;
						$PER = trim($MEDICION->PER);
						$LEAN = trim($MEDICION->LEAN);
						$LEAC = trim($MEDICION->LEAC);
						$VAL = trim($MEDICION->VAL);
						$FECHA_TEMP = trim($MEDICION->FECHA_TOMA);
						$ID_ERROR = $MEDICION->ID_ERROR;
						$OBS = trim($MEDICION->OBSERVACION);
						$ID_OPE = $MEDICION->ID_OPE;
					
					// Modificamos la fecha:
						// ���������� ATENCION !!!!!!!!!!!!!
						// En esta version de PHP, una fecha del tipo xx/yy/zzzz ser� interpretada al formato ingles (mm/dd/yyyy)
						// mientras que una fecha del tipo aa-bb-cccc dar� el formato espa�ol dia "aa", mes "bb" y a�o "cccc"
						$FECHA_TEMP = str_replace("/","-",$FECHA_TEMP);
						$FECHA = date("Ymd H:i:s", strtotime($FECHA_TEMP));
					
					// Seguimos generando el XML:
						$RETURN .= "<MEDICION>";
						$buffer .= "<MEDICION>";

					// Test para saber si ya existe la entrada en la BDD:
						$PRE_EXISTENCIA = "SELECT * FROM AGUA_MEDICION WHERE ID_MED = ".$ID_MED." AND PER = '".$PER."'";
						$buffer .= "<REQ_PRE_EXIST>". $PRE_EXISTENCIA . "</REQ_PRE_EXIST>";
						
						$RESP_PRE_EXISTENCIA = sqlsrv_query($CONEXION, $PRE_EXISTENCIA, array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));
						$buffer .= "<RESP_REQ_PRE_EXIST>". sqlsrv_num_rows($RESP_PRE_EXISTENCIA) . "</RESP_REQ_PRE_EXIST>";
						
						if ((is_numeric($LEAC) == true && $LEAC > 0) || !empty($OBS) || $ID_ERROR != 0) {

							// Si no hay entrada:
							if(sqlsrv_num_rows($RESP_PRE_EXISTENCIA) <= 0) {
	                                                    
								$SQL = "INSERT INTO AGUA_MEDICION 
											(ID_MED, PER, LEAN, LEAC, VAL, FECHA_TOMA, ID_ERROR, OBSERVACION, ID_OPE) 
										VALUES 
											(".$ID_MED.", '".$PER."', ".$LEAN.", ".$LEAC.", -1, '".$FECHA."', ".$ID_ERROR.", '".$OBS."', ".$ID_OPE.");";
							
								$buffer .= "<REQ_INSERT>". $SQL . "</REQ_INSERT>";
								
								$RESP_INSERT = sqlsrv_query($CONEXION, $SQL);
								
								if(!isset($RESP_INSERT)){
									sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
								}
								
								$buffer .= "<RESP_REQ_INSERT>Successfull INSERT: ID_MED[". $ID_MED ."] ; PER[". $PER ."]</RESP_REQ_INSERT>";
								$RETURN .= "<RESP_REQ_INSERT>Successfull INSERT: ID_MED[". $ID_MED ."] ; PER[". $PER ."]</RESP_REQ_INSERT>";
	                                                    
							} else {
	                                                     
	                                $SQL = "UPDATE AGUA_MEDICION SET 
                                        LEAN = ".$LEAN.",
                                        LEAC = ".$LEAC.",
                                        VAL = -1, 
                                        FECHA_TOMA = '".$FECHA."',
                                        ID_ERROR = ".$ID_ERROR.",
                                        OBSERVACION = '".$OBS."',
                                        ID_OPE = ".$ID_OPE." 
                                		WHERE 
                                        ID_MED = ".$ID_MED." AND
                                        PER = '".$PER."';";

                                    $buffer .= "<REQ_UPDATE>". $SQL . "</REQ_UPDATE>";

                                    $RESP_UPDATE = sqlsrv_query($CONEXION,$SQL);

                                    if(!isset($RESP_UPDATE)){
                                            sqlsrv_query($CONEXION,"ROLLBACK TRANSACTION");
                                    }

                                    $buffer .= "<RESP_REQ_UPDATE>Successfull UPDATE: ID_MED[". $ID_MED ."] ; PER[". $PER ."]</RESP_REQ_UPDATE>";
                                    $RETURN .= "<RESP_REQ_UPDATE>Successfull UPDATE: ID_MED[". $ID_MED ."] ; PER[". $PER ."]</RESP_REQ_UPDATE>";
	                                                        
	                        }
						}
						
					// Cerramos las balizas XML:
						$RETURN .= "</MEDICION>";	
						$buffer .= "</MEDICION>";
					
					// Cerramos el recurso SQL:
						sqlsrv_free_stmt($RESP_PRE_EXISTENCIA);

				} // end foreach
			
			$buffer .= '</VI_AGUA_FROM_TABLET_P>';
			$RETURN .= "<final>EXITO Exito exito</final></VI_AGUA_FROM_TABLET_P>";
			
			////////////////////////////////////////////////////////////////////////////////
			$name_file=$C_DEBOAGUA . "\\" . $C_XML . "\\P_DALVIAN_".$TABLET."_".$FECHA_NOW.".xml";/////////////////////
			$file=fopen($name_file,"w+");///////////////////////////////////////////////////
			fwrite($file,$buffer);//////////////////////////////////////////////////////////
			fclose($file);//////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////
			
		}
		else {
			$RETURN .= "<ERR>Imposible copiar el archivo XML en el servidor [". $tmp_name ."]</ERR>";
		}
	}					
?>