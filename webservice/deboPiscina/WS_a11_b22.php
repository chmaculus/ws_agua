<?php

	$SQL_SEL = "SELECT * FROM GROU_RESIDENTES";
	$SQL_SEL_RES = mssql_query($SQL_SEL);
	
	$INSERT_POR_HACER[] = array();
	
	while ($R = mssql_fetch_array($SQL_SEL_RES)) {

		$SQL_INSERT =  "INSERT INTO GROU_DALVIAN_RESIDENTES
						VALUES (
							 ". $R['ID_RES'] 		." ,
							 ". $R['CODIGO_OPE']	." ,
							'". $R['DOCUMENTO'] 	."',								
							'". $R['APELLIDO']  	."', 
							'". $R['NOMBRE']	   	."',  
							'". $R['ESTATUS']   	."',
							'". $R['FEC_NAC']     	."',
							'". $R['TIPO_CONSTR']  	."',
							'". $R['CALLE'] 		."',
							 ". $R['ALTURA']      	." ,
							'". $R['MANZANA']      	."',
							'". $R['LOTE']    		."',
							'". $R['PISO']       	."',
							'". $R['DPTO']       	."',
							'". $R['TEL']        	."',
							 ". $R['HABILITADO']   	." ,
							'". $R['FEC_CARGA']  	."'				
						)";
		
		/*$SQL_INSERT_RESP = mssql_query($SQL_INSERT);
		if(!isset($SQL_INSERT_RESP)){
			mssql_query("ROLLBACK TRANSACTION");
		}*/
		
		$INSERT_POR_HACER[] = $SQL_INSERT;
	}
	
	mssql_free_result($SQL_SEL_RES);
	
	for ($i = 1 ; $i <= 5142 ; $i++) {
		$SQL_INSERT_RESP = mssql_query($INSERT_POR_HACER[$i]);
	}
	
	
	
	$RETURN .= "<RES>OKKKKKKKKKKKKKKKKKKKKKKKKKK</RES>";

?>