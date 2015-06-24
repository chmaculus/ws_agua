<?

$key="Fs2goO0rcf1oat1U"; // Foca + sOft + 2011 + groU
 
/*
$xml = simplexml_load_file("variables.xml");

$VARIABLES = $xml->VARIABLES;
$SERVIDOR = $VARIABLES->SERVIDOR;
$BASEDATOS = $VARIABLES->BASE;
$USUARIO = $VARIABLES->USUARIO;
$CONTRASENA = $VARIABLES->CONTRASENA;
*/

$USUARIO = strip_tags(trim(substr($_POST['log'],0,50)));
$CONTRASENA = strip_tags(trim(substr($_POST['pass'],0,22)));
$SERVIDOR = stripslashes(strip_tags(trim(substr($_POST['serv'],0,100))));
$BASEDATOS = strip_tags(trim(substr($_POST['base'],0,80)));
$PFSPFS = $_POST['pfs'];

echo "<br/><br/>" . $PFSPFS . "<br/><br/>";

$cadena = "FocaSoftware " . $BASEDATOS   . " " . $CONTRASENA . " " . $SERVIDOR . " " . $USUARIO  . " FocaSoftware";

echo $cadena . "<br/><br/>";

$iv = mcrypt_create_iv (mcrypt_get_block_size (MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);

// Encrypting
function encrypt($string, $key) {
    $enc = "";
    global $iv;
    $enc=mcrypt_cbc (MCRYPT_TripleDES, $key, $string, MCRYPT_ENCRYPT, $iv);

  return base64_encode($enc);
}


$cadena_criptada = encrypt($cadena, $key);


echo "<br/><br/> Los datos encriptados son: " . $cadena_criptada;
 
$name_file="pfs" . $PFSPFS . ".ini";
$file=fopen($name_file,"w+");
fwrite($file,$cadena_criptada);
fclose($file);

echo "<br/><br/> ¡Archivo encriptado con éxito!";
?>