<?php



log_this("log/post.log",print_r($_POST,TRUE));
log_this("log/get.log",print_r($_GET,TRUE));
log_this("log/request.log",print_r($_REQUEST,TRUE));

$post=$_POST;

if(is_array($_POST)){
	log_this("log/request_ARRAY.log","array");	
}




function log_this($file_name, $data) {
    $file = fopen($file_name, 'a+');
    fwrite($file, $data);
    fclose($file);
}

?>


