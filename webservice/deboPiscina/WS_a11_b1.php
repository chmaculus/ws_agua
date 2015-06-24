<?php
//selección de operadores
$SQL_OPERADORES = "SELECT COD, NOM, PWD, EST AS HAB FROM USUARIOS";

//cantidad de operadores
$COUNT_OPERADORES = "SELECT COUNT(COD)AS CANTIDAD FROM USUARIOS";

file_put_contents("log.txt", "entro a operadores" . "\n", FILE_APPEND);

$OPERADORES = sqlsrv_query($CONEXION, $SQL_OPERADORES);

//procesamiento de la cantidad de usuarios
$count_OPERADORES = sqlsrv_query($CONEXION, $COUNT_OPERADORES);
$rowCountOperadores = sqlsrv_fetch_array($count_OPERADORES,SQLSRV_FETCH_ASSOC);

if ($OPERADORES) {
   echo "resulto operadores";
   echo '<br>';
} else {
   echo "no resulto operadores";
   echo '<br>';
}

file_put_contents("log.txt", $rowCountOperadores['CANTIDAD'] . "\n", FILE_APPEND);
if ($rowCountOperadores['CANTIDAD'] == 0) {
   $RETURN .= "<RES>No hay registro en la tabla.</RES>";
   exit;
}

$RETURN .= "<VISTA_OPERADORES>";
$RETURN .= "<CNT>" . $rowCountOperadores['CANTIDAD'] . " entradas</CNT>";

$tabla_PK = array();
while ($REG_OPERADORES = sqlsrv_fetch_array($OPERADORES,SQLSRV_FETCH_ASSOC)) {

   // Test:
   //$REG_OPERADORES = array("COD"=>"3589", "NOM"=> "Jean < Pierre & Josette", "CLA"=>3);
   // Control de los resultados con respecto a '&' y '<':
   $REG_OPERADORES = clean_bdd_response($REG_OPERADORES);

   // Control de PK:
   $valor_ID = trim($REG_OPERADORES['COD']);
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

$RETURN .= "</VISTA_OPERADORES>";

?>