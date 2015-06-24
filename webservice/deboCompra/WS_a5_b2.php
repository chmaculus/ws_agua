<?php // A5-B2 = TODOS LOS ARTICULOS CON CODIGOS DE BARRAS [FIABILIZADO 15/12/2011]

	// Recuperamos los parametros del post (lista de los inventarios seleccionados):
		$SQL_ARTICULO = "SELECT C.CodBar as CB, A.DetArt AS DET
						FROM CODBAR C
						INNER JOIN ARTICULOS A
						ON C.CodArt=A.CodArt AND C.CodSec=A.CodSec 
						ORDER BY C.CodBar ASC";
			
		$ARTICULOS = mssql_query($SQL_ARTICULO);

		if(!isset($ARTICULOS)){
			mssql_query("ROLLBACK TRANSACTION");
		}

		if(mssql_num_rows($ARTICULOS) == 0){
			$RETURN .= "<RES>No hay registro en la tabla.</RES>";
			exit;
		}
	
		$RETURN .= "<VISTA_ARTICULOS>";
		$RETURN .= "<CNT>".mssql_num_rows($ARTICULOS)." entradas</CNT>";
		
		$tabla_PK = array();
		while ($REG_ARTICULOS=mssql_fetch_array($ARTICULOS)){
		
			// Test:
				//..................
		
			// Control de los resultados con respecto a '&' y '<':
				$REG_ARTICULOS = clean_bdd_response($REG_ARTICULOS);
		
			// Control de PK:
				$cb_leido = end(explode("/",$REG_ARTICULOS['CB']));
				$cb_leido = trim(str_replace(" ","",$cb_leido));
				if (is_numeric($cb_leido) && trim($REG_ARTICULOS['DET']) != "") {
					if (in_array($cb_leido, $tabla_PK) == false) {
						
						$RETURN .= "<ART>";
						
							/*DET-D*/
							$RETURN .= "<D>".trim($REG_ARTICULOS['DET'])."</D>";
							
							/*CB-CB*/
							$RETURN .= "<CB>".$cb_leido."</CB>";
								$tabla_PK[] = $cb_leido;
							
							/*FO-FO*/
							$RETURN .= "<FO></FO>";
							
						$RETURN .= "</ART>";
					} 
					else {
						$RETURN .= "<ERR>PK: ". $cb_leido ." duplicada</ERR>";
						$ERROR .= "<br />CODIGO-BARRAS duplicado :". $cb_leido;
					}
				} 
				else {
					$RETURN .= "<ERR>Formato incorrecto: ". $REG_ARTICULOS['CB'] ." // " . $REG_ARTICULOS['DET'] ."</ERR>";
					$ERROR .= "<br />CODIGO-BARRAS mal-formado :". $cb_leido;
				}
		} // end while
		
	// AL FINAL:
		mssql_free_result($ARTICULOS);
		if(!isset($ARTICULOS)){
			mssql_query("ROLLBACK TRANSACTION");
		}
		
		$RETURN .= "</VISTA_ARTICULOS>";
?>