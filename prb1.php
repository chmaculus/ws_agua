<?php

$data = json_decode(file_get_contents('php://input'), true);

log_this("log/data.log",print_r($data,TRUE));
log_this("log/post.log",print_r($_POST,TRUE));
log_this("log/get.log",print_r($_GET,TRUE));
log_this("log/request.log",print_r($_REQUEST,TRUE));

$post=$_POST;

if(is_array($_POST)){
	log_this("log/request_ARRAY.log","array");	
}

$array=print_r($_REQUEST,true);
$strip=stripcslashes($array);
log_this("log/strip.log",$strip);
log_this("log/request0.log",$_REQUEST[0]);
log_this("log/request.log",print_r($_REQUEST,true));
log_this("log/json.log",json_decode($strip));




function log_this($file_name, $data) {
    $file = fopen($file_name, 'a+');
    fwrite($file, $data);
    fclose($file);
}

?>


