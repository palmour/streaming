<?php
require_once("User.php");
session_start();

function check_password($username, $password){
    $user_obj = User::find($username);
    if(is_null($user_obj)){return false;}
    $salthash = $user_obj.getSalthash();

    if(User::create_hash($password) == $salthash){
        return true;
    }
    return false;
}

$username = $_GET['username']; //this api should be accessed via an HTTP GET
$password = $_GET['password'];

if(check_password($username, $password)){
    header('Content-type: application/json');

    $_SESSION['username'] = $username;
    $_SESSION['authsalt'] = time();

    $auth_cookie_val = md5($_SESSION['username'] . $_SERVER['REMOTE_ADDR'] . $_SESSION['authsalt']);

    setcookie('LOGIN', $auth_cookie_val, 0, 
        "/afs/cs.unc.edu/proj/courses/comp426-f16/public_html/users/palmour/final_project", "wwwp.classroom.cs.unc.edu", 
        true);
    print(json_encode(true));
} else{
    unset($_SESSION['username']);
    unset($_SESSION['authsalt']);

    header('HTTP/1.1 401 Unauthorized');
    header('Content-type: application/json');
    print(json_encode(false));
}
?>