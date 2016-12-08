<?php
require_once("User.php");
session_start();

function check_password($user_obj, $pw){
    $salthash = $user_obj->getSalthash();
    $un = $user_obj->getUsername();
    if(User::create_hash($un, $pw) == $salthash){
        return true;
    }
    return false;
}

function fail($resp){
    unset($_SESSION['username']);
    unset($_SESSION['authsalt']);

    header('HTTP/1.1 401 Unauthorized');
    header('Content-type: application/json');
    print(json_encode($resp));
    exit();
}

$username = $_GET['username']; //this api should be accessed via an HTTP GET
$password = $_GET['password'];

$response = array();

$user = User::find($username);
if(is_null($user)){
    $response['status']='Error: User not found'; fail($response);
    
}
else if(!check_password($user, $password)){
    $response['db_username'] = $user->getUsername();
    $response['db_password'] = $user->getSalthash();
    $response['status'] = 'Error: password incorrect'; fail($response);
}

else {
    $response['status'] = 'OK';
    
    $response['db_username'] = $user->getUsername();
    $response['db_password'] = $user->getSalthash();

    header('Content-type: application/json');

    $_SESSION['username'] = $username;
    $_SESSION['authsalt'] = $user->getSalthash();

    $auth_cookie_val = md5($_SESSION['username'] . $_SERVER['REMOTE_ADDR'] . $_SESSION['authsalt']);

    setcookie('LOGIN', $auth_cookie_val, 0, 
        "www.cs.unc.edu/Courses/comp426-f16/users/palmour/final_project", "wwwp.classroom.cs.unc.edu", 
        true);
    print(json_encode($response));
} 

?>