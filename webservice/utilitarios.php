<?php

// Archivo con la fonciones "herramientas"
// Saca los caracteres '&' y '<' de las respuestas de la BDD:
function clean_bdd_response($tabla) {
    foreach ($tabla as $key => $value) {
        $tabla[$key] = str_replace(array('&', '<', '>'), array(' Y ', '/', '\\'), $value);
    }
    return $tabla;
}

// Saca los caracteres '&' y '<' de los archivos XML en recepción:
// Sacar '&' = facil
// Sacar '<' = dificil: leer caracter por caracter, suprimir los '<' repetidos sin un '>'
//
// NB: esta fonción procesa correctamente los errores tipo:
//			<BAL>ABC<EF<<GHI<</BAL> ---- y dará ----> <BAL>ABCEFGHI</BAL>
// ¡¡¡PERO!!! no procesará los errores tipo:
//			<BAL>ABC<EF<><GHI<</BAL> ---- y dará ----> <BAL>ABCEF<>GHI</BAL> (que levantará un error) 
function clean_xml_file($uri) {
    $fstring = file_get_contents($uri);
    $fstring = str_replace("&", " Y ", $fstring);

    $string_retorno = "";
    $string_temp = "";
    $modo_temp = 0;
    for ($i = 0; $i < strlen($fstring); $i++) {
        $s = substr($fstring, $i, 1);

        // Si leo este caracter ('<'), entramos en la zona de peligro.
        // Guardamos en un string_temp a parte los caracteres que vienen despues.
        // Si viene despues un '>', guardamos el string_temp y el '<' ;
        // sino si viene un '<', reiteramos, pero ya podemos borrar el primer '<'.
        if ($s == '<') {
            if ($modo_temp == 0) {
                $string_temp = "";
                $modo_temp = 1;
            } else { // = if ($modo_temp == 1)
                $string_retorno .= $string_temp;
                $string_temp = "";
            }
        } else if ($s == '>') {
            if ($modo_temp == 0) {
                //No hacemos nada
            } else { // = if ($modo_temp == 1)
                $string_retorno .= '<' . $string_temp . $s;
                $modo_temp = 0;
            }
        } else {
            if ($modo_temp == 0) {
                $string_retorno .= $s;
            } else {
                $string_temp .= $s;
            }
        }
    }

    file_put_contents($uri, $string_retorno);
}

// Decripta los archivos que lo son:
function decrypt($string, $key) {
    $dec = "";
    $string = trim(base64_decode($string));
    global $iv;
    $dec = mcrypt_cbc(MCRYPT_TripleDES, $key, $string, MCRYPT_DECRYPT, $iv);
    return $dec;
}

class MyException extends Exception {

    public $file;
    public $line;

    public function errorHandler($errno, $errstr, $errfile, $errline) {
        $e = new self();
        $e->message = $errstr;
        $e->code = $errno;
        $e->file = $errfile;
        $e->line = $errline;
        throw $e;
    }

}

/**
 * Class para crear un log de archivos 
 */
class Log {

    function __construct() {
        
    }

    /**
     * <p> Función para craer un log.txt en la carpeta raíz donde se està instanciando la clase
     * Se puede sobreescribir el archivos sí $FILE_APPEND es 0, o continuar el archivo si es 1
     * @param string $data La cadena que se desea imprimir en el txt
     * @param type $FILE_APPEND 1 si va a seguir escribiendo en el archivos , 0 si lo va a sobreescribir
     */
    public static function log($data, $FILE_APPEND = 1) {
        if (is_string($data)) {
            if (isset($FILE_APPEND) && $FILE_APPEND == 1) {
                return file_put_contents("log/log.txt", "\n" . $data, FILE_APPEND);
            } else {
                return file_put_contents("log/log.txt", $data);
            }
        } else {
            if (isset($FILE_APPEND) && $FILE_APPEND == 1) {
                return file_put_contents("log/log.txt", "\n" . print_r($data, 1), FILE_APPEND);
            } else {
                return file_put_contents("log/log.txt", print_r($data, 1));
            }
        }
    }




}


    function log_this($file_name, $data) {
        $file = fopen($file_name, 'a+');
        fwrite($file, $data);
        fclose($file);
    }



?>