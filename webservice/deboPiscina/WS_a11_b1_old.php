<?php

// A11-B1 = OPERADORES (habilitados para generar actos de compra) [FIABILIZADO 15/12/2011]
/**
 * comienzo de versiion actual
 */
$SQL_OPERADORES = "SELECT COD, NOM, PWD, EST AS HAB FROM USUARIOS";
file_put_contents("log.txt", "entro a operadores", FILE_APPEND);
/**
 * fin de version actual
 */
/**
 * comienzo de version antigua
 */
//        $SQL_OPERADORES =  "SELECT ID_OPE, NOM, PWD, HABILITADO AS HAB
//						FROM GROU_OPERADORES GO";
/**
 * fin de version natigua
 */
$OPERADORES = slqsrv_query($SQL_OPERADORES);

if (!isset($OPERADORES)) {
   slqsrv_query("ROLLBACK TRANSACTION");
}
file_put_contents("log.txt", sqlsrv_rows_affected($OPERADORES) . "\n",FILE_APPEND);
if (sqlsrv_rows_affected($OPERADORES) == 0) {
   $RETURN .= "<RES>No hay registro en la tabla.</RES>";
   exit;
}

$RETURN .= "<VISTA_OPERADORES>";
$RETURN .= "<CNT>" . slqsrv_num_afetch($OPERADORES) . " entradas</CNT>";

$tabla_PK = array();
while ($REG_OPERADORES = slqsrv_fetch_array($OPERADORES)) {

   // Test:
   //$REG_OPERADORES = array("COD"=>"3589", "NOM"=> "Jean < Pierre & Josette", "CLA"=>3);
   // Control de los resultados con respecto a '&' y '<':
   $REG_OPERADORES = clean_bdd_response($REG_OPERADORES);

   // Control de PK:
   $valor_ID = trim($REG_OPERADORES['ID_OPE']);
   if (is_numeric($valor_ID) == true && intval($valor_ID) >= 0) {
      if (in_array($valor_ID, $tabla_PK) == false) {

         $RETURN .= "<OPE>";

         /* ID_OPE-ID */
         $RETURN .= "<ID>" . intval($valor_ID) . "</ID>";
         $tabla_PK[] = intval($valor_ID);

         /* NOM-N */
         if (is_numeric(trim($REG_OPERADORES['NOM'])) == true) {
            $RETURN .= "<N>" . intval(trim($REG_OPERADORES['NOM'])) . "</N>";
         } else {
            $RETURN .= "<N>" . trim($REG_OPERADORES['NOM']) . "</N>";
         }

         /* PWD-C */
         $RETURN .= "<C>" . trim($REG_OPERADORES['PWD']) . "</C>";

         /* HAB-H */
         if (is_numeric(trim($REG_OPERADORES['HAB'])) == true) {
            $RETURN .= "<H>" . intval(trim($REG_OPERADORES['HAB'])) . "</H>";
         } else {
            $RETURN .= "<H>0</H>";
         }

         $RETURN .= "</OPE>";
      } else {
         $RETURN .= "<ERR>PK: " . intval($valor_ID) . " duplicada</ERR>";
         $ERROR .= "<br />CODIGO OPERADOR duplicado : " . intval($valor_ID);
      }
   }
}

// AL FINAL: 
slqsrv_free_result($OPERADORES);
if (!isset($OPERADORES)) {
   slqsrv_query("ROLLBACK TRANSACTION");
}

$RETURN .= "</VISTA_OPERADORES>";
?>