<?php

// INCLUDES:
include_once './webservice/utilitarios.php';

// PARAMETROS DEL ARCHIVO DE RETORNO:
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
header('Content-Type: text/xml');
date_default_timezone_set("America/Argentina/Mendoza");
//set_error_handler(array('MyException', 'errorHandler'), E_ALL);
// VARIABLES GLOBALES DE RETURN (datos y errores):
$RETURN = "";
$ERROR = "";
$buffer = "";



/*
$name_file = "log\\log_" . date("Y-m") . ".txt";

$file = fopen($name_file, "a+");
fwrite($file, "request: ".print_r($_REQUEST,true));
fclose($file);
*/

log_this("log/aa.log",date("H:i:s")."\n".print_r($_REQUEST,true));

try {
log_this("log/bb.log",date("H:i:s")." try\n");
    /* $RETURN .= '<?xml version="1.0" encoding="ISO-8859-1"?>'; */

    // VARIABLES:
    //$DEBO_URL_CARPETA_DEBOAGUA = "\\deboagua";
    //$DEBOAGUA_URL_CARPETA_XML = "\\informes_medicion";
    //$DEBOAGUA_URL_CARPETA_FOTOS = "\\fotos_medicion";

    $C_DEBOAGUA = "";
    $C_DEBOSANCION = "";
    $C_DEBOINVENTARIO = "";
    $C_DEBORECOVERY = "";
    $C_DEBOCOMPRA = "";
    $C_DEBOPISCINA = "";
    $C_DEBOFOTOSARTICULOS = "";
    $C_XML = "";
    $C_FOTO = "";
    $C_LOG = "";

    $handle = @fopen("config.ini", "r");
    log_this("log/bb.log",date("H:i:s")." config\n");
    if ($handle) {
        while (($buffer = fgets($handle, 4096)) !== false) {
            $tab = explode("=", $buffer);

            switch ($tab[0]) {
                case "c_deboagua":
                    $C_DEBOAGUA = trim($tab[1]);
                case "c_debosancion":
                    $C_DEBOSANCION = trim($tab[1]);
                case "c_deboinventario":
                    $C_DEBOINVENTARIO = trim($tab[1]);
                case "c_deborecovery":
                    $C_DEBORECOVERY = trim($tab[1]);
                case "c_debocompra":
                    $C_DEBOCOMPRA = trim($tab[1]);
                case "c_debopiscina":
                    $C_DEBOPISCINA = trim($tab[1]);
                case "c_xml":
                    $C_XML = trim($tab[1]);
                case "c_log":
                    $C_LOG = trim($tab[1]);
                case "c_fotos":
                    $C_FOTO = trim($tab[1]);
                case "c_debofotosarticulos":
                    $C_DEBOFOTOSARTICULOS = trim($tab[1]);
            }
        }
        if (!feof($handle)) {
            $ERROR .= "Error: unexpected fgets() fail\n";
        }
        fclose($handle);
    }

log_this("log/bb.log",date("H:i:s")." end while\n");



    if (isset($_REQUEST['a'])) {


/////////////////////////////////////////////////////////////////////////////////////////////////////		
/////////////////////////////////////////////////////////////////////////////////////////////////////		
        ////////////////////////////////////
        ////////////////////////////////////
        ////////        AGUA        ////////
        ////////////////////////////////////
        ////////////////////////////////////
        /* A == 1  <=> DEBO_AGUA */
        if ($_REQUEST['a'] == 1) {


            // DECRIPTAR LOS DATOS DEL ARCHIVO PFS.INI:

            $key = "Fs2goO0rcf1oat1U";

            $name_file = "pfs" . $_REQUEST['a'] . ".ini";
            //$file = fopen($name_file, "r");
            //$flujo_leido = fread($file, 500);
            //fclose($file);

            log_this("log/bb.log",date("H:i:s")." pfs\n");

            $iv = mcrypt_create_iv(mcrypt_get_block_size(MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);
            $cadena_decriptada = decrypt($flujo_leido, $key);
            $tabla_string = explode(" ", $cadena_decriptada);

            $servidor = $tabla_string[3];
            $usuario = $tabla_string[4];
            $pwd = $tabla_string[2];
            $basededatos = $tabla_string[1];


            ///// LOCAL
            /*
              $servidor = "192.167.1.189\SQL2008R2ADV";
              $usuario = "sa";
              $pwd = "xxzza";
              $basededatos = "HO_DALVIAN_P";
             */
            /*
              $servidor = "SERVER";
              $usuario = "debo_head";
              $pwd = "DEBO";
              $basededatos = "DEBO_HEAD";
             */
            /* Vieja version de compilacion de PHP:
              $CONEXION = mssql_connect($servidor,$usuario,$pwd);
              mssql_select_db($basededatos,$conexion);

              mssql_query($CONEXION, "BEGIN TRANSACTION");
             */

          $servidor = "10.231.45.205";
          $usuario = "debo";
          $pwd = "debo";
          $basededatos = "DOSSA_07022022";

            //echo $servidor."<br/>";
            //echo $usuario."<br/>";
            //echo $pwd."<br/>";
            //echo $basededatos."<br/>";

            $server = "10.231.45.205";
            $username ="debo";
            $password ="debo";
            $database ="DOSSA_08112021";

            $connectionInfo = array("Database"=>$database, "UID"=>$username, "PWD"=>$password);

            //$connectionInfo = array( "Database"=>"DOSSA_08112021", "UID"=>"debo", "PWD"=>"debo");
            $CONEXION = sqlsrv_connect( $server, $connectionInfo );


            //$CONEXION = sqlsrv_connect($servidor, array("UID" => $usuario, "PWD" => $pwd, "Database" => $basededatos));
            if (!$CONEXION) {
                echo "<error>Connection could not be established.</error>";
                log_this("log/bb.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
                log_this("log/bb.log"," exit\n");
                exit;
            }
            if( $CONEXION ) {
                 //echo "Conexión establecida.<br />";
            }else{
                 echo "Conexión no se pudo establecer.<br />";
                 log_this("log/bb.log",date("H:i:s")."\n".print_r( sqlsrv_errors(), true));
            }

            sqlsrv_begin_transaction($CONEXION);

            //*****************************************************
            //*****************************************************

            if (isset($_REQUEST['b'])) {
                $var1=".\\webservice\\deboAgua\\WS_a1_b" . $_REQUEST['b'] . ".php";
                log_this("log/bb.log", date("H:i:s")."\nvar1: ".$var1);
                require($var1);
            } else {
                // Si B no está:
                $RETURN .= "<PARAMETROS>";
                $RETURN .= "<A>1</A>";
                $RETURN .= "<B1>TODAS LAS RUTAS</B1>";
                $RETURN .= "<B2>TODOS LOS OPERADORES</B2>";
                $RETURN .= "<B3>DETALLES DE TODOS LOS MEDIDORES DE LAS RUTAS SELECCIONADAS</B3>";
                $RETURN .= "<B4>TODAS LAS EXPLICACIONES POSIBLES A LOS ERRORES DURANTE LAS MEDICIONES</B4>";
                $RETURN .= "<B5>CONTROL DE ACCESO DE LOS ADMINS Y OPERADORES</B5>";
                $RETURN .= "<B11>EXPORT DATOS DE LAS MEDICIONES</B11>";
                $RETURN .= "<B12>EXPORT DE LAS FOTOS</B12>";
                $RETURN .= "<B13>EXPORT DE LOS LOGS</B13>";
                $RETURN .= "<B15>LIBERACION DE LAS RUTAS</B15>";
                $RETURN .= "</PARAMETROS>";
            }
        } // END A == 1 --- DEBO AGUA
/////////////////////////////////////////////////////////////////////////////////////////////////////		
/////////////////////////////////////////////////////////////////////////////////////////////////////		
        ///////////////////////////////////////
        ///////////////////////////////////////
        ////////        SANCION        ////////
        ///////////////////////////////////////
        ///////////////////////////////////////
        /* A == 2  <=> DEBO_SANCION */
        if ($_REQUEST['a'] == 2) {

            // DECRIPTAR LOS DATOS DEL ARCHIVO PFS.INI:

            $key = "Fs2goO0rcf1oat1U";

            $name_file = "pfs" . $_REQUEST['a'] . ".ini";
            $file = fopen($name_file, "r");
            $flujo_leido = fread($file, 500);
            fclose($file);

            $iv = mcrypt_create_iv(mcrypt_get_block_size(MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);
            $cadena_decriptada = decrypt($flujo_leido, $key);
            $tabla_string = explode(" ", $cadena_decriptada);

            $servidor = $tabla_string[3];
            $usuario = $tabla_string[4];
            $pwd = $tabla_string[2];
            $basededatos = $tabla_string[1];


            /*
              $conexion = mssql_connect($servidor,$usuario,$pwd);
              mssql_select_db($basededatos,$conexion);
              mssql_query("BEGIN TRANSACTION");
             */

            $CONEXION = sqlsrv_connect($servidor, array("UID" => $usuario, "PWD" => $pwd, "Database" => $basededatos));
            if (!$CONEXION) {
                echo "<error>Connection could not be established.</error>";
                exit;
            }
            sqlsrv_begin_transaction($CONEXION);

            //*****************************************************
            //*****************************************************

            if (isset($_REQUEST['b'])) {
                require(".\\webservice\\deboSancion\\WS_a2_b" . $_REQUEST['b'] . ".php");
            } else {
                // Si B no está:
                $RETURN .= "<PARAMETROS>";
                $RETURN .= "<A>2</A>";
                $RETURN .= "<B1>TODOS LOS RESIDENTES</B1>";
                $RETURN .= "<B2>TODOS OPERADORES (personas habilitadas para emitir sanciones)</B2>";
                $RETURN .= "<B3>TODAS LAS SANCIONES(categorias que corresponden a sanciones)</B3>";
                $RETURN .= "<B11>EXPORT DATOS XML SANCION</B11>";
                $RETURN .= "<B12>EXPORT DE LAS FOTOS</B12>";
                $RETURN .= "<B13>EXPORT DEL LOG</B13>";
                $RETURN .= "</PARAMETROS>";
            }
        } // END A == 2 --- DEBO SANCION
/////////////////////////////////////////////////////////////////////////////////////////////////////		
/////////////////////////////////////////////////////////////////////////////////////////////////////		
        //////////////////////////////////////////
        //////////////////////////////////////////
        ////////        INVENTARIO        ////////
        //////////////////////////////////////////
        //////////////////////////////////////////
        /* A == 3  <=> DEBO_INVENTARIO */
        if ($_REQUEST['a'] == 3) {

            // DECRIPTAR LOS DATOS DEL ARCHIVO PFS.INI:

            $key = "Fs2goO0rcf1oat1U";

            $name_file = "pfs" . $_REQUEST['a'] . ".ini";
            $file = fopen($name_file, "r");
            $flujo_leido = fread($file, 500);
            fclose($file);

            $iv = mcrypt_create_iv(mcrypt_get_block_size(MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);
            $cadena_decriptada = decrypt($flujo_leido, $key);
            $tabla_string = explode(" ", $cadena_decriptada);

            $servidor = $tabla_string[3];
            $usuario = $tabla_string[4];
            $pwd = $tabla_string[2];
            $basededatos = $tabla_string[1];

            /*
              $conexion = mssql_connect($servidor,$usuario,$pwd);
              mssql_select_db($basededatos,$conexion);
              mssql_query("BEGIN TRANSACTION");
             */

            $CONEXION = sqlsrv_connect($servidor, array("UID" => $usuario, "PWD" => $pwd, "Database" => $basededatos));
            sqlsrv_query($CONEXION, "BEGIN TRANSACTION");

            //*****************************************************
            //*****************************************************

            if (isset($_REQUEST['b'])) {
                require(".\\webservice\\deboInventario\\WS_a3_b" . $_REQUEST['b'] . ".php");
            } else {
                // Si B no está:
                $RETURN .= "<PARAMETROS>";
                $RETURN .= "<A>3</A>";
                $RETURN .= "<B1>INVENTARIOS DISPONIBLES</B1>";
                $RETURN .= "<B3>DESCARGAR LOS ARTICULOS DE LOS INVENTARIOS SELECCIONADOS</B3>";
                $RETURN .= "<B4>IMPORTAR FOTO DE UN ARTICULO</B4>";
                $RETURN .= "<B11>EXPORT DE LOS DATOS DEL INVENTARIO</B11>";
                $RETURN .= "<B12>EXPORT DE LAS FOTOS</B12>";
                $RETURN .= "<B13>EXPORT DEL LOG</B13>";
                $RETURN .= "<B15>LIBERACIONES DE INVENTARIOS</B15>";
                $RETURN .= "</PARAMETROS>";
            }
        } // END A == 3 --- DEBO INVENTARIO
/////////////////////////////////////////////////////////////////////////////////////////////////////		
/////////////////////////////////////////////////////////////////////////////////////////////////////		
        ////////////////////////////////////////
        ////////////////////////////////////////
        ////////        RECOVERY        ////////
        ////////////////////////////////////////
        ////////////////////////////////////////
        /* A == 4  <=> DEBO RECOVERY */
        if ($_REQUEST['a'] == 4) {

            // DECRIPTAR LOS DATOS DEL ARCHIVO PFS.INI:

            $key = "Fs2goO0rcf1oat1U";

            $name_file = "pfs" . $_REQUEST['a'] . ".ini";
            $file = fopen($name_file, "r");
            $flujo_leido = fread($file, 500);
            fclose($file);

            $iv = mcrypt_create_iv(mcrypt_get_block_size(MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);
            $cadena_decriptada = decrypt($flujo_leido, $key);
            $tabla_string = explode(" ", $cadena_decriptada);

            $servidor = $tabla_string[3];
            $usuario = $tabla_string[4];
            $pwd = $tabla_string[2];
            $basededatos = $tabla_string[1];

            /*
              $conexion = mssql_connect($servidor,$usuario,$pwd);
              mssql_select_db($basededatos,$conexion);
              mssql_query("BEGIN TRANSACTION");
             */

            $CONEXION = sqlsrv_connect($servidor, array("UID" => $usuario, "PWD" => $pwd, "Database" => $basededatos));
            sqlsrv_query($CONEXION, "BEGIN TRANSACTION");

            //*****************************************************
            //*****************************************************

            if (isset($_REQUEST['b'])) {
                require(".\\webservice\\deboRecovery\\WS_a4_b" . $_REQUEST['b'] . ".php");
            } else {
                // Si B no está:
                $RETURN .= "<PARAMETROS>";
                $RETURN .= "<A>4</A>";
                $RETURN .= "<B99>FULL RECOVER</B99>";
                $RETURN .= "</PARAMETROS>";
            }
        } // END A == 4 --- DEBO RECOVERY
/////////////////////////////////////////////////////////////////////////////////////////////////////		
/////////////////////////////////////////////////////////////////////////////////////////////////////		
        //////////////////////////////////////
        //////////////////////////////////////
        ////////        COMPRA        ////////
        //////////////////////////////////////
        //////////////////////////////////////
        /* A == 5  <=> DEBO COMPRA */
        if ($_REQUEST['a'] == 5) {

            // DECRIPTAR LOS DATOS DEL ARCHIVO PFS5.INI:

            $key = "Fs2goO0rcf1oat1U";

            $name_file = "pfs" . $_REQUEST['a'] . ".ini";
            $file = fopen($name_file, "r");
            $flujo_leido = fread($file, 500);
            fclose($file);

            $iv = mcrypt_create_iv(mcrypt_get_block_size(MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);
            $cadena_decriptada = decrypt($flujo_leido, $key);
            $tabla_string = explode(" ", $cadena_decriptada);

            $servidor = $tabla_string[3];
            $usuario = $tabla_string[4];
            $pwd = $tabla_string[2];
            $basededatos = $tabla_string[1];

            /*
              $conexion = mssql_connect($servidor,$usuario,$pwd);
              mssql_select_db($basededatos,$conexion);
              mssql_query("BEGIN TRANSACTION");
             */

            $CONEXION = sqlsrv_connect($servidor, array("UID" => $usuario, "PWD" => $pwd, "Database" => $basededatos));
            sqlsrv_query($CONEXION, "BEGIN TRANSACTION");

            //*****************************************************
            //*****************************************************

            if (isset($_REQUEST['b'])) {
                require(".\\webservice\\deboCompra\\WS_a5_b" . $_REQUEST['b'] . ".php");
            } else {
                // Si B no está:
                $RETURN .= "<PARAMETROS>";
                $RETURN .= "<A>5</A>";
                $RETURN .= "<B1>OPERADORES (habilitados para generar actos de compra)</B1>";
                $RETURN .= "<B2>TODOS LOS ARTICULOS CON CODIGOS DE BARRAS</B2>";
                $RETURN .= "<B3>TODOS LOS PROVEEDORES</B3>";
                $RETURN .= "<B11>EXPORT DE LOS DATOS DE LAS COMPRAS</B11>";
                $RETURN .= "<B12>EXPORT DE LAS FOTOS</B12>";
                $RETURN .= "<B13>EXPORT DEL LOG</B13>";
                $RETURN .= "</PARAMETROS>";
            }
        } // END A == 5 --- DEBO COMPRA
/////////////////////////////////////////////////////////////////////////////////////////////////////		
/////////////////////////////////////////////////////////////////////////////////////////////////////		
        ///////////////////////////////////////
        ///////////////////////////////////////
        ////////        PISCINA        ////////
        ///////////////////////////////////////
        ///////////////////////////////////////
        /* A == 11  <=> DEBO PISCINA */
        if ($_REQUEST['a'] == 11) {

            // DECRIPTAR LOS DATOS DEL ARCHIVO PFS11.INI:

            $key = "Fs2goO0rcf1oat1U";

            $name_file = "pfs" . $_REQUEST['a'] . ".ini";
            $file = fopen($name_file, "r");
            $flujo_leido = fread($file, 500);
            fclose($file);

            $iv = mcrypt_create_iv(mcrypt_get_block_size(MCRYPT_TripleDES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM);
            $cadena_decriptada = decrypt($flujo_leido, $key);
            $tabla_string = explode(" ", $cadena_decriptada);

            $servidor = $tabla_string[3];
            $usuario = $tabla_string[4];
            $pwd = $tabla_string[2];
            $basededatos = $tabla_string[1];

            /*
              $conexion = mssql_connect($servidor,$usuario,$pwd);
              mssql_select_db($basededatos,$conexion);
              mssql_query("BEGIN TRANSACTION");
             */

            $CONEXION = sqlsrv_connect($servidor, array("UID" => $usuario, "PWD" => $pwd, "Database" => $basededatos));
            sqlsrv_query($CONEXION, "BEGIN TRANSACTION");

            //*****************************************************
            //*****************************************************

            if (isset($_REQUEST['b'])) {
                require(".\\webservice\\deboPiscina\\WS_a" . $_REQUEST['a'] . "_b" . $_REQUEST['b'] . ".php");
            } else {
                // Si B no está:
                $RETURN .= "<PARAMETROS>";
                $RETURN .= "<A>" . $_REQUEST['a'] . "</A>";
                $RETURN .= "<B1>OPERADORES (habilitados para generar actos de compra)</B1>";
                $RETURN .= "<B4>TODOS LOS RESIDENTES EN 1 SOLA PARTE</B4>";
                $RETURN .= "<B5>TODOS LOS RESIDENTES POR PARTES</B5>";
                $RETURN .= "<B6>CANTIDAD DE RESIDENTES</B6>";
                $RETURN .= "<B8>REVISACIONES</B8>";
                $RETURN .= "<B11>EXPORT DE LOS DATOS DE LAS COMPRAS</B11>";
                $RETURN .= "<B12>EXPORT DE LAS FOTOS</B12>";
                $RETURN .= "<B13>EXPORT DEL LOG</B13>";
                $RETURN .= "<B16>EXPORT DE LAS ENTRADAS A LA PISCINA</B16>";
                $RETURN .= "<B17>EXPORT DE LAS REVISACIONES DE LA PISCINA</B17>";
                $RETURN .= "<B18>EXPORT DE LOS RESIDENTES DE LA PISCINA</B18>";
                $RETURN .= "</PARAMETROS>";
            }
        } // END A == 5 --- DEBO COMPRA
        // AL FINAL:

        sqlsrv_commit($CONEXION);
        sqlsrv_close($CONEXION);

        if($_POST['b']!='1j'){
            echo trim('<?xml version="1.0" encoding="ISO-8859-1"?>' . $RETURN);
        }else{
            echo $RETURN;
        }
        
    } else {
        echo trim('<?xml version="1.0" encoding="ISO-8859-1"?><e>WEBSERVICE ONLINE</e>');
    }

    $name_file = "log\\log_" . date("Y-m") . ".txt";

    if (file_exists("log\\") == false) {
        mkdir("log\\", 0777);
    }

    $file = fopen($name_file, "a+");
    fwrite($file, $RETURN);
    fclose($file);
} catch (Exception $e) {

    $name_file = ".\\errors\\error_report_" . date("Y-m") . ".txt";
    $file = fopen($name_file, "w+");
    fwrite($file, "PHP says: " . $e . "<br /><br />" . $ERROR);
    fclose($file);

    header('Content-Type: text/html');
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">";
    echo "<html><body>";
    echo $e . "<br /><br />";
    echo $ERROR . "<br /><br />";
    echo $RETURN . "<br /><br />";
    echo $buffer . "<br /><br />";
    echo "</body></html>";


    die();
}
?>