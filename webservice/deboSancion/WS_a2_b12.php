<?php

// A2-B12 = EXPORT DE LAS FOTOS (FIABILIZADO 19/01/2012)
global $C_DEBOSANCION;
global $C_FOTO;
global $RETURN;

if (!isset($_FILES['i'])) {
    $RETURN .= "<ERR>Faltan parametros opcionales: 'I'</ERR>";
} else {
    $tmp_name = $_FILES["i"]["tmp_name"];
    $name = $_FILES["i"]["name"];

    if (file_exists($C_DEBOSANCION) == false) {
        mkdir($C_DEBOSANCION, 0777);
    }

    if (file_exists($C_DEBOSANCION . "\\" . $C_FOTO) == false) {
        mkdir($C_DEBOSANCION . "\\" . $C_FOTO, 0777);
    }

    $name = $C_DEBOSANCION . "\\" . $C_FOTO . "\\" . "s" . $name;

    if (file_exists($name) == false) {
        if (move_uploaded_file($tmp_name, $name)) {
            $RETURN .= "<PROCESO>EXITO Exito exito</PROCESO>";
        } else {
            $RETURN .= "<PROCESO>FRACASO Fracaso fracaso</PROCESO>";
        }
    } else {
        $RETURN .= "<PROCESO>EXITO Exito exito</PROCESO>";
    }
}
?>