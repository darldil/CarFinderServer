<?php

$link = mysqli_connect('db678459548.db.1and1.com', 'dbo678459548', 'alpine123', 'db678459548');

if (mysqli_connect_errno()) {
	$data = array("errorno" => -1, "errorMessage" => mysqli_connect_errno());
    
	$json = json_encode($data);
	echo $json;
	
    exit;
}

if (!mysqli_set_charset($link, "utf8")) {
	$data = array("errorno" => -1, "errorMessage" => mysqli_error($link));
    printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
    
    $json = json_encode($data);
	echo $json;
	
    exit;
}

?>