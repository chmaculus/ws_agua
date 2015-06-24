<?php

// A2-B11 = EXPORT DATOS XML SANCION
global $C_DEBOSANCION;
global $C_XML;
global $CONEXION;
global $buffer;
global $RETURN;

if (isset($_FILES['p'])) {
    $FECHA_NOW = date("d-m-Y_H-i");
    $TABLET = $_REQUEST['c'];
    $tmp_name = $_FILES["p"]["tmp_name"];

    if (file_exists($C_DEBOSANCION) == false) {
        mkdir($C_DEBOSANCION, 0777);
    }

    if (file_exists($C_DEBOSANCION . "\\" . $C_XML) == false) {
        mkdir($C_DEBOSANCION . "\\" . $C_XML, 0777);
    }

    $name = $C_DEBOSANCION . "\\" . $C_XML . "\\R_DALVIAN_" . $TABLET . "_" . $FECHA_NOW . ".xml";

    if (move_uploaded_file($tmp_name, $name)) {

        $fstring = file_get_contents($name);
        $fstring = str_replace("&", " y ", $fstring);
        file_put_contents($name, $fstring);

        $xml = simplexml_load_file($name);
        $RETURN .= "<VI_SANCION_FROM_TABLET_P>";
        $buffer = '<?xml version="1.0" encoding="ISO-8859-1"?><VI_SANCION_FROM_TABLET_P>';

        foreach ($xml->SANCION as $SANCION) {

            $COD_RES = $SANCION->ID_CLIENTE;
            $TIP = "C";
            $TCO = "SAN";
            $SUC = $TABLET;
            $NCO = $SANCION->ID;
            $FEC_TOMA = $SANCION->FECHA;
            include 'fecha.php';
            $FEC_CARGA = date("Ymd H:i:s");
            $FEC_VTO = date("Ymd H:i:s", strtotime(date("Ymd H:i:s", strtotime($FEC_CARGA)) . " +10 days")); // fecha + 10 dias
            $COD_INC = $SANCION->ID;
            $FOTO_PATH = $SANCION->FOTO;
            $ID_LV = $SANCION->ID_SANCION;
            $IMP = -1;
            $ID_INSP = $SANCION->ID_OPE;
            $LLAMA_PRES = "P";

            // PRIMERO chequeamos si la sancion ya figura en la BDD:
            $SQL_YA_EN_BDD = "SELECT * FROM SANCION_CAB WHERE FEC_TOMA='" . $FEC_TOMA . "' AND COD_RES=" . $COD_RES . "";
            $result = sqlsrv_query($CONEXION, $SQL_YA_EN_BDD, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
            $result2 = sqlsrv_num_rows($result);
            sqlsrv_free_stmt($result);


            // SEGUNDO, si no esta en la base, hacemos el upload:
            if ($result2 <= 0) {

                $RETURN .= "<SANCION>";
                $buffer = $buffer . '<SANCION>';

                // Buscar el ID mas grande usado:
                $SQL_ID_MAYOR = "SELECT ID FROM SANCION_CAB ORDER BY ID DESC";
                $result = sqlsrv_query($CONEXION, $SQL_ID_MAYOR, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                $result2 = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $indice_max = $result2['ID'];
                sqlsrv_free_stmt($result);

                $RETURN .= "<ID_SANCION>" . ($indice_max + 1) . "</ID_SANCION>";
                $RETURN .= "<TABLET>" . $TABLET . "</TABLET>";
                $RETURN .= "<ID_INSPECTOR>" . $ID_INSP . "</ID_INSPECTOR>";
                $RETURN .= "<ID_CLIENTE>" . $COD_RES . "</ID_CLIENTE>";
                $RETURN .= "<FECHA_TOMA>" . $FEC_TOMA . "</FECHA_TOMA>";
                $RETURN .= "<FECHA_CARGA>" . $FEC_CARGA . "</FECHA_CARGA>";
                $RETURN .= "<CATE_SANCION>" . $ID_LV . "</CATE_SANCION>";
                $RETURN .= "<URI_FOTO>" . $FOTO_PATH . "</URI_FOTO>";

                $SQL_CAB = "INSERT INTO SANCION_CAB (ID, COD_RES, TIP, TCO, SUC, NCO, FEC_TOMA, FEC_CARGA, FEC_VTO, COD_INC, FOTO_PATH, ID_LV, IMP, ID_INSP, LLAMA_PRES) 
					VALUES (" . ($indice_max + 1) . ", " . $COD_RES . ", '" . $TIP . "', '" . $TCO . "', " . $SUC . ", " . ($indice_max + 1) . ", '" . $FEC_TOMA . "', '" . $FEC_CARGA . "', '" . $FEC_VTO . "', '" . $COD_INC . "', '" . $FOTO_PATH . "', " . $ID_LV . ", " . $IMP . ", " . $ID_INSP . ", '" . $LLAMA_PRES . "');";

                $buffer .= "<REQ_INSERT_CAB>" . $SQL_CAB . "</REQ_INSERT_CAB>";

                $INSERT_CAB = sqlsrv_query($CONEXION, $SQL_CAB);

                if (!isset($INSERT_CAB)) {
                    sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
                }

                $ID_COMB = $indice_max + 1;
                $FEC = $FEC_CARGA;
                $ESTADO = 0;
                $OBS = utf8_decode($SANCION->OBSERVACION);


                $RETURN .= "<OBS>" . $OBS . "</OBS>";

                // Sacar los caracteres especiales de la OBSERVACION:
                $OBS = str_replace(array("'", '"', "<", ">"), "", $OBS);

                // Buscar el ID mas grande usado:
                $SQL_ID_MAYOR_2 = "SELECT ID FROM SANCION_HIS ORDER BY ID DESC";
                $result = sqlsrv_query($CONEXION, $SQL_ID_MAYOR_2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                $result2 = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $indice_max_2 = $result2['ID'];
                sqlsrv_free_stmt($result);

                $SQL_HIS = "INSERT INTO SANCION_HIS (ID, ID_COMP, FEC, ESTADO, OBS) VALUES (" . ($indice_max_2 + 1) . ", " . $ID_COMB . ", '" . $FEC_CARGA . "', " . $ESTADO . ", '" . $OBS . "');";

                $buffer .= "<REQ_INSERT_HIS>" . $SQL_HIS . "</REQ_INSERT_HIS>";

                $INSERT_HIS = sqlsrv_query($CONEXION, $SQL_HIS);

                if (!isset($INSERT_HIS)) {
                    sqlsrv_query($CONEXION, "ROLLBACK TRANSACTION");
                }

                $RETURN .= "</SANCION>";
                $buffer = $buffer . '</SANCION>';
            }
        }

        $RETURN .= "<exito>EXITO Exito exito</exito>";
        $buffer = $buffer . '</VI_SANCION_FROM_TABLET_P>';

        ////////////////////////////////////////////////////////////////////////////////
        $name_file = $C_DEBOSANCION . "\\" . $C_XML . "\\P_DALVIAN_" . $TABLET . "_" . date("Y-m-d_H-i") . ".xml"; /////////////////////
        $file = fopen($name_file, "w+"); ///////////////////////////////////////////////////
        fwrite($file, $buffer); //////////////////////////////////////////////////////////
        fclose($file); //////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////
    }
}
?>