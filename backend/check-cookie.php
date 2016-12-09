<?php
session_start();

$response = array();
if(isset($_COOKIE['LOGIN'])){
    $cookie_val = $_COOKIE['LOGIN'];
    if(md5($_SESSION['username'].$_SERVER['REMOTE_ADDR'].$_SESSION['authsalt']) == $cookie_val){
        $response['cookie_val_correct'] = true;
        $response['username'] = $_SESSION['username'];
    }
    else{$response['cookie_val_correct'] = false;}
    $response['set'] = true;
    $response['value'] = $_COOKIE['LOGIN'];
}
else{
    $response['set'] = false;
}
header('Content-type: application/json');
print(json_encode($response));
?>