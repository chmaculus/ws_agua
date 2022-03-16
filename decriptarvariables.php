<?php

$key="Fs2goO0rcf1oat1U";

$name_file="pfs.ini";
$file=fopen($name_file,"r");
$flujo_leido = fread($file,500);
fclose($file);

$iv = mcrypt_create_iv (mcrypt_get_block_size (MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);


// Decrypting
function decrypt($string, $key) {
    $dec = "";
    $string = trim(base64_decode($string));
    global $iv;
    $dec = mcrypt_cbc (MCRYPT_TripleDES, $key, $string, MCRYPT_DECRYPT, $iv);
  return $dec;
  }
  
  $cadena_decriptada = decrypt($flujo_leido, $key);
  
  //$tabla_string = explode(" " , $cadena_decriptada);

  $tabla_string=(str_replace(" ","<br>",$cadena_decriptada));
 
 
 echo  $tabla_string;

 
 
?>