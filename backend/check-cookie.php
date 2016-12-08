<?php
session_start();

$response = array();
if(isset($_COOKIE['LOGIN'])){
    $response['set'] = true;
    $response['value'] = $_COOKIE['LOGIN'];
}
else{
    $response['set'] = false;
}
header('Content-type: application/json');
print(json_encode($response));
?>