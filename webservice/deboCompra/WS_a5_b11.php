<?php // A5-B11 = EXPORT DE LOS DATOS DE LAS COMPRAS [FIABILIZADO 16/12/2011]
	
	if(isset($_FILES['p'])){

		// Recuperamos el número de la tablet:
			if (!isset($_REQUEST['c'])) {
				$TABLET = 0;
			} else {
				$TABLET = $_REQUEST['c'];
			}
				
		// Controlamos la existencia de los archivos y carpetas receptoras de los datos XML aue vamos a mandar:
			$FECHA_NOW = date("Y-m-d_H-i-s");
			$tmp_name = $_FILES['p']["tmp_name"];
			
			if (file_exists($C_DEBOCOMPRA) == false) {
				mkdir($C_DEBOCOMPRA, 0777);
			}
	
			if (file_exists($C_DEBOCOMPRA . "\\" . $C_XML) == false) {
				mkdir($C_DEBOCOMPRA . "\\" . $C_XML, 0777);
			}
	
			$name = $C_DEBOCOMPRA . "\\" . $C_XML . "\\COPYCONF_COMPRA_". $TABLET ."_". $FECHA_NOW .".xml";
				
		// Hacemos la copia-conforme del XML de los inventarios en la nueva carpeta:
			if(move_uploaded_file($tmp_name,$name)){
				
				// Cargamos el XML recién recibido (sacando los '&' y '<'):
					clean_xml_file($name);
					$xml = simplexml_load_file($name);
					
				// Empezamos a generar el XML:			
					$RETURN .= "<EXPORT_COMPRAS_FROM_TABLET>";
					$buffer  = '<?xml version="1.0" encoding="ISO-8859-1"?>
								<EXPORT_COMPRAS_FROM_TABLET>';
				
				// Leemos uno por uno los datos de las compras (CANASTO):
					$CANASTO_ID              = $xml->ID;
					$CANASTO_OPERADOR_ID     = $xml->O;
					$CANASTO_ESTADO	         = $xml->E;
					$FEC_TEMP			     = $xml->FEC;
					$CANASTO_TIP             = trim($xml->TIP);
					$CANASTO_TIPO_COMPROB    = trim($xml->TCO);
					$CANASTO_SUCURSAL        = $xml->S;
					$CANASTO_NUM_COMPROB     = $xml->NCO;
					$CANASTO_PROVEEDOR_CUIT  = $xml->PRO;
					$CANASTO_FORMA_PAGO      = $xml->PAG;
						
				// Cambiamos el formato de la fecha a yyyy-dd-mm:
					if ($FEC_TEMP == '') {
						$CANASTO_FECHA = '';
					} else {
						$CANASTO_FECHA = new DateTime($FEC_TEMP);
						$CANASTO_FECHA = $CANASTO_FECHA->format('Ymd H:i:s');
					}						
						
				// Buscamos el id del proveedor dado el CUIT:
					$SQL_PROVEED = "SELECT COD,NOM FROM PROVEED WHERE CUIT='".$CANASTO_PROVEEDOR_CUIT."'";
					$result = mssql_query($SQL_PROVEED);
					$result2 = mssql_fetch_array($result);
					mssql_free_result($result);
					$buffer .= "<BUSQ_PROVEEDOR>" . $SQL_PROVEED . "</BUSQ_PROVEEDOR>";
					
					$CANASTO_PROVEEDOR_COD = $result2['COD'];
					if ($CANASTO_PROVEEDOR_COD == null) {
						$CANASTO_PROVEEDOR_COD = 0;
					}
					
					$CANASTO_PROVEEDOR_NOM = $result2['NOM'];
					if ($CANASTO_PROVEEDOR_NOM == null) {
						$CANASTO_PROVEEDOR_NOM = "Proveedor desconocido";
					}
					
					
						
				// Actualizamos en la Base de Datos:
					//
					//
					//
					/************/
					/* PCOBYPAG */
					/************/
					$SQL_PRE_EXISTENCIA_1 = "SELECT * FROM PCOBYPAG 
											WHERE 
												COD=".$CANASTO_ID." AND
												FEP='".$CANASTO_FECHA."' AND
												TIP='".$CANASTO_TIP."' AND 
												TCO='".$CANASTO_TIPO_COMPROB."' AND 
												SUC=".$CANASTO_SUCURSAL." AND 
												NCO=".$CANASTO_NUM_COMPROB;
								
					$buffer .= "<PRE_REQ_CAN_1>" . $SQL_PRE_EXISTENCIA_1 . "</PRE_REQ_CAN_1>";
								
					$result_1 = mssql_query($SQL_PRE_EXISTENCIA_1);
					$count_1 = mssql_num_rows($result_1);
					mssql_free_result($result_1);
					
					if ($count_1 <= 0) {
						$SQL1 = "INSERT INTO PCOBYPAG (COD,FEP,TIP,TCO,SUC,NCO,FPA,FER,FEV,C_HD) 
								VALUES (
								".$CANASTO_ID.",
								'".$CANASTO_FECHA."',
								'".$CANASTO_TIP."',
								'".$CANASTO_TIPO_COMPROB."',
								".$CANASTO_SUCURSAL.",
								".$CANASTO_NUM_COMPROB.",
								'".$CANASTO_FORMA_PAGO."',
								'".date("Ymd H:i:s")."',
								'".date("Ymd H:i:s")."',
								'X')";
					} 
					else {
						$SQL1 = "UPDATE PCOBYPAG 
								SET 
									FPA='".$CANASTO_FORMA_PAGO."',
									FER='".$CANASTO_FECHA."',
									FEV='".date("Ymd H:i:s")."',
									C_HD='X' 
								WHERE 
									COD=".$CANASTO_ID." AND
									FEP='".$CANASTO_FECHA."' AND
									TIP='".$CANASTO_TIP."' AND 
									TCO='".$CANASTO_TIPO_COMPROB."' AND 
									SUC=".$CANASTO_SUCURSAL." AND 
									NCO=".$CANASTO_NUM_COMPROB;
					}
					
					$INSERT1 = mssql_query($SQL1);
						
					$buffer .= "<REQ_CAN_1>" . $SQL1 . "</REQ_CAN_1>";
					
					if(!isset($INSERT1)){
						mssql_query("ROLLBACK TRANSACTION");
					}
					
					//
					//
					//
					/************/
					/* PMAEFACT */
					/************/
					$SQL_PRE_EXISTENCIA_2 = "SELECT * FROM PMAEFACT 
										    WHERE 
											    TIP='".$CANASTO_TIP."' AND 
											    TCO='".$CANASTO_TIPO_COMPROB."' AND 
											    SUC=".$CANASTO_SUCURSAL." AND 
											    NCO=".$CANASTO_NUM_COMPROB." AND 
											    COD=".$CANASTO_PROVEEDOR_COD;
										   
					$buffer .= "<PRE_REQ_CAN_2>". $SQL_PRE_EXISTENCIA_2 ."</PRE_REQ_CAN_2>";
										  
					$result_2 = mssql_query($SQL_PRE_EXISTENCIA_2);
					$count_2 = mssql_num_rows($result_2);
					mssql_free_result($result_2);
					
					if ($count_2 <= 0) {
						$SQL2 = "INSERT INTO PMAEFACT (TIP,TCO,SUC,NCO,COD,FEC,FEV,FEP,NOM,CUI,FPA,C_HD) 
								VALUES (
								'".$CANASTO_TIP."',
								'".$CANASTO_TIPO_COMPROB."',
								".$CANASTO_SUCURSAL.",
								".$CANASTO_NUM_COMPROB.",
								".$CANASTO_PROVEEDOR_COD.",
								'".$CANASTO_FECHA."',
								'".date("Ymd H:i:s")."',
								'".date("Ymd H:i:s")."',
								'".$CANASTO_PROVEEDOR_NOM."', 
								'".$CANASTO_PROVEEDOR_CUIT."',
								'".$CANASTO_FORMA_PAGO."',
								'X');
								";
					} 
					else {
						$SQL2 = "UPDATE PMAEFACT 
								SET 
									FEC='".$CANASTO_FECHA."',
									FEV='".date("Ymd H:i:s")."',
									FEP='".date("Ymd H:i:s")."',
									NOM='".$CANASTO_PROVEEDOR_NOM."',
									CUI='".$CANASTO_PROVEEDOR_CUIT."',
									FPA='".$CANASTO_FORMA_PAGO."',
									C_HD='X'
								WHERE 
									TIP='".$CANASTO_TIP."' AND 
									TCO='".$CANASTO_TIPO_COMPROB."' AND 
									SUC=".$CANASTO_SUCURSAL." AND 
									NCO=".$CANASTO_NUM_COMPROB." AND 
									COD=".$CANASTO_PROVEEDOR_COD;
					}
							
					$INSERT2 = mssql_query($SQL2);
						
					$buffer .= "<REQ_CAN_2>" . $SQL2 . "</REQ_CAN_2>";
					
					if(!isset($INSERT2)){
						mssql_query("ROLLBACK TRANSACTION");
					}
					
					
				// Segundo parseamos las compras:
					foreach ($xml->CMPR as $COMPRA){
					
						// Leemos balizas desde el archivo XML:
							$COMPRA_CB        = $COMPRA->CB;
							$COMPRA_CANASTO   = $COMPRA->C;
							$COMPRA_OPERADOR  = $COMPRA->O;
							$COMPRA_CANTIDAD  = $COMPRA->Q;
							$FECHA_TEMP       = $COMPRA->FEC;
							$COMPRA_CYV 	  = 'N'; // arbitrariamente escogido
							$COMPRA_ORD       = 0;   // arbitrariamente escogido
							$COMPRA_CMF       = 0;   // arbitrariamente escogido
							$COMPRA_PVE		  = 0;   // arbitrariamente escogido
						
						// Cambiamos el formato de la fecha a yyyy-dd-mm:
							$COMPRA_FECHA = new DateTime($FECHA_TEMP);
							$COMPRA_FECHA = $COMPRA_FECHA->format('Ymd H:i:s');
						
						// Buscamos el articulo asociado:
							$SQL_ARTICULO = "SELECT A.CodArt AS ART, A.CodSec AS SEC, A.DetArt AS DET 
											FROM CODBAR C
											INNER JOIN ARTICULOS A
											ON C.CodArt=A.CodArt AND C.CodSec=A.CodSec
											WHERE C.CodBar='". $COMPRA_CB ."';";
							$SELECT_ARTICULO = mssql_query($SQL_ARTICULO);
							$ARTICULO = mssql_fetch_array($SELECT_ARTICULO);
							$buffer .= "<BUSQ_ARTICULO>" . $SQL_ARTICULO . "</BUSQ_ARTICULO>";
						
						
							if($ARTICULO['ART'] != '') {
								$ARTICULO_ART = $ARTICULO['ART'];
							} else {
								$ARTICULO_ART = 0;
							}
							
							if ($ARTICULO['SEC'] != '') {
								$ARTICULO_SEC = $ARTICULO['SEC'];
							} else {
								$ARTICULO_SEC = 0;
							}
							
						//
						//
						//
						/************/
						/* PMOVFACT */
						/************/
						//
						// Aca no entiendo bien cual es el tema, la PK va a ser siempre la misma para
						// todos los articulos
						//
						$SQL_PRE_EXISTENCIA_3 = "SELECT * FROM PMOVFACT 
											    WHERE 
												    TIP='".$CANASTO_TIP."' AND 
												    TCO='".$CANASTO_TIPO_COMPROB."' AND 
												    SUC=".$CANASTO_SUCURSAL." AND 
												    NCO=".$CANASTO_NUM_COMPROB." AND 
												    PRO=".$CANASTO_PROVEEDOR_COD." AND 
												    ORD=".$COMPRA_ORD;
											   
						$buffer .= "<PRE_REQ_COMP_3>". $SQL_PRE_EXISTENCIA_3 ."</PRE_REQ_COMP_3>";			  
											  
						$result_3 = mssql_query($SQL_PRE_EXISTENCIA_3);
						$count_3 = mssql_num_rows($result_3);
						mssql_free_result($result_3);
						
						// Si no existe la entrada todavía, insertamos:
						if ($count_3 <= 0) {
							$SQL3 = "INSERT INTO PMOVFACT (TIP,TCO,SUC,NCO,PRO,ORD,COD,ART,LI0,CAN,CMF,C_HD) 
									VALUES (
									'".$CANASTO_TIP."',
									'".$CANASTO_TIPO_COMPROB."',
									".$CANASTO_SUCURSAL.",
									".$CANASTO_NUM_COMPROB.",
									".$CANASTO_PROVEEDOR_COD.",
									".$COMPRA_ORD.",
									".$ARTICULO_SEC.", 
									".$ARTICULO_ART.",
									'".$COMPRA_CYV."',
									".$COMPRA_CANTIDAD.",
									".$COMPRA_CMF.",
									'X');";
						} 
						// ... sino ACTUALIZAMOS:
						else {
							$SQL3 = "UPDATE PMOVFACT 
									SET 
										COD='".$ARTICULO_SEC."',
										ART='".$ARTICULO_ART."',
										CAN='".$COMPRA_CANTIDAD."',
										C_HD='X'
									WHERE 
										TIP='".$CANASTO_TIP."' AND 
										TCO='".$CANASTO_TIPO_COMPROB."' AND 
										SUC=".$CANASTO_SUCURSAL." AND 
										NCO=".$CANASTO_NUM_COMPROB." AND 
										PRO=".$CANASTO_PROVEEDOR_COD." AND 
										ORD=".$COMPRA_ORD;
						}
						
						$INSERT3 = mssql_query($SQL3);
						
						$buffer .= "<REQ_COMP_3>" . $SQL3 . "</REQ_COMP_3>";

						if(!isset($INSERT3)){
							mssql_query("ROLLBACK TRANSACTION");
						}
						
						//
						//
						//
						/************/
						/* AMOVSTOC */
						/************/
						$SQL_PRE_EXISTENCIA_4 = "SELECT * FROM AMOVSTOC 
												WHERE 
													ART=".$ARTICULO_ART." and
													SEC=".$ARTICULO_SEC." and
													FEC='".$COMPRA_FECHA."' and
													CYV='".$COMPRA_CYV."' and 
													TIP='".$CANASTO_TIP."' and  
													TCO='".$CANASTO_TIPO_COMPROB."' and 
													NCO=".$CANASTO_NUM_COMPROB." and  
													ORD=".$COMPRA_ORD." ";
											
						$buffer .= "<PRE_REQ_COMP_4>". $SQL_PRE_EXISTENCIA_4 ."</PRE_REQ_COMP_4>";	
											
						$result_4 = mssql_query($SQL_PRE_EXISTENCIA_4);
						$count_4 = mssql_num_rows($result_4);
												
						// Si no existe la entrada todavía, insertamos:
						if ($count_4 <= 0) {
							$SQL4 = "INSERT INTO AMOVSTOC (SEC,ART,FEC,CYV,TIP,TCO,PVE,NCO,ORD,CAN,OPE,C_HD) 
									VALUES (
									".$ARTICULO_SEC.", 
									".$ARTICULO_ART.",
									'".$COMPRA_FECHA."',
									'".$COMPRA_CYV."',
									'".$CANASTO_TIP."',
									'".$CANASTO_TIPO_COMPROB."',
									".$COMPRA_PVE.",
									".$CANASTO_NUM_COMPROB.",
									".$COMPRA_ORD.",
									".$COMPRA_CANTIDAD.",
									".$CANASTO_OPERADOR_ID.",
									'X')";
						}
						// ... sino ACTUALIZAMOS:
						else {
							$SQL4 = "UPDATE AMOVSTOC 
									SET 
										OPE=".$CANASTO_OPERADOR_ID.", 
										CAN='".$COMPRA_CANTIDAD."', 
										C_HD='X' 
									WHERE 
										SEC=".$ARTICULO_SEC." AND 
										ART=".$ARTICULO_ART." AND 
										FEC='".$COMPRA_FECHA."' AND 
										CYV='".$COMPRA_CYV."' AND 
										TIP='".$CANASTO_TIP."' AND  
										TCO='".$CANASTO_TIPO_COMPROB."' AND  
										NCO=".$CANASTO_NUM_COMPROB." AND  
										ORD=".$COMPRA_ORD."";
									
						}
						
						$INSERT4 = mssql_query($SQL4);
						
						$buffer .= "<REQ_COMP_4>" . $SQL4 . "</REQ_COMP_4>";

						if(!isset($INSERT4)){
							mssql_query("ROLLBACK TRANSACTION");
						}
						
					} // end foreach
				
					$RETURN .= "<RESULTADO>EXITO Exito exito UPDATE (canastos y compras)</RESULTADO>";
					$buffer .= "<RESULTADO>EXITO Exito exito UPDATE (canastos y compras)</RESULTADO>";
			
				$RETURN .= "</EXPORT_COMPRAS_FROM_TABLET>";
				$buffer .= "</EXPORT_COMPRAS_FROM_TABLET>";
			
			} // end if(move_uploaded_file($tmp_name,$name))
			
			// ESPERAMOS UNOS 2 SEGUNDOS DE SEGURIDAD:
				sleep(1);
			
			// GUARDAMOS LAS OPERACIONES SOBRE LA BDD EN UN REGISTRO MAS:
				$name_file=$C_DEBOCOMPRA . "\\" . $C_XML . "\\SQLMVT_COMPRA_TAB". $TABLET ."_CAN". $CANASTO_ID ."_". date("Y-m-d_H-i") .".xml";
				$file=fopen($name_file,"w+");
				fwrite($file,$buffer);
				fclose($file);
				$buffer = "";

	} 
	else {
		$RETURN .= "<ERROR>Faltan parametros</ERROR>";
	} // end if(isset($_REQUEST['p']))
					
?>