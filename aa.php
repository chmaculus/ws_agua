<?php
            $key = "Fs2goO0rcf1oat1U";

            $name_file = "pfs.ini";
            $file = fopen($name_file, "r");
            $flujo_leido = fread($file, 500);
            fclose($file);

            //log_this("log/bb.log",date("H:i:s")." pfs\n");

            $iv = mcrypt_create_iv(mcrypt_get_block_size(MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);
            $cadena_decriptada = decrypt($flujo_leido, $key);
            $tabla_string = explode(" ", $cadena_decriptada);
echo $cadena_decrriptada."<br>";

?>
