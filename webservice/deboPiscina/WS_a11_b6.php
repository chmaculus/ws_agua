<?php
//
//// A11-B6 = RESIDENTES TESTTTTTTTTTTTTTTTTTTTTTTTTTT
//
///**
// * comienzo de version actual
// */
//$SQL_RESIDENTES = "SELECT COUNT(*) AS CANTIDAD FROM RESIDENTES";
//file_put_contents("log.txt", "entro a residentes", FILE_APPEND);
///**
// * fin de version actual
// */
///**
// * comienzo de version antigua
// */
////        $SQL_RESIDENTES =  "SELECT COUNT(*) AS CANTIDAD
////						FROM GROU_RESIDENTES GRESI";
///**
// * finj de version  antigua
// */
//$RESIDENTES = slqsrv_query($SQL_RESIDENTES);
//$REG_RESIDENTES = slqsrv_fetch_array($RESIDENTES);
//
//if (!isset($RESIDENTES)) {
//   slqsrv_query("ROLLBACK TRANSACTION");
//}
//
//if (slqsrv_num_rows($RESIDENTES) == 0) {
//   $RETURN .= "<RES>No hay registro en la tabla.</RES>";
//   exit;
//}
//
//$RETURN .= "<VISTA_RESIDENTES_CANTIDAD>";
//$RETURN .= "<CNT>" . mssql_num_rows($RESIDENTES) . " entradas</CNT>";
//
///* CANTIDAD-CAN */
//$RETURN .= "<CAN>" . trim($REG_RESIDENTES['CANTIDAD']) . "</CAN>";
//
//
//// AL FINAL: 
//slqsrv_free_result($RESIDENTES);
//if (!isset($RESIDENTES)) {
//   slqsrv_query("ROLLBACK TRANSACTION");
//}
//
//$RETURN .= "</VISTA_RESIDENTES_CANTIDAD>";









// A11-B6 = RESIDENTES TESTTTTTTTTTTTTTTTTTTTTTTTTTT

/**
 * comienzo de version actual
 */
$SQL_RESIDENTES = "SELECT COUNT(*) AS CANTIDAD FROM RESIDENTES";
file_put_contents("log.txt", "entro a residentes", FILE_APPEND);
/**
 * fin de version actual
 */
/**
 * comienzo de version antigua
 */
//        $SQL_RESIDENTES =  "SELECT COUNT(*) AS CANTIDAD
//						FROM GROU_RESIDENTES GRESI";
/**
 * finj de version  antigua
 */
$RESIDENTES = mysql_query($SQL_RESIDENTES);
$REG_RESIDENTES = mysql_fetch_assoc($RESIDENTES);
file_put_contents("log.txt",$REG_RESIDENTES['CANTIDAD'], FILE_APPEND);

if ($REG_RESIDENTES['CANTIDAD'] == 0) {
   $RETURN .= "<RES>No hay registro en la tabla.</RES>";
   exit;
}

$RETURN .= "<VISTA_RESIDENTES_CANTIDAD>";
$RETURN .= "<CNT>" . $REG_RESIDENTES['CANTIDAD'] . " entradas</CNT>";

/* CANTIDAD-CAN */
$RETURN .= "<CAN>" . $REG_RESIDENTES['CANTIDAD'] . "</CAN>";
$RETURN .= "</VISTA_RESIDENTES_CANTIDAD>";

?>