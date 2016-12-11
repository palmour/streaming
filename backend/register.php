<?php
    require_once("User.php");
    session_start();

    function unique($un){
        if(is_null(User::find($un))){return true;}
        return false;
    }

    $response = array();
    $username = $_GET['username'];
    $password = $_GET['password'];

    header('Content-type: application/json');
    /*if(!unique($username)){
        $response['status'] = 'Error: username not unique';
        print(json_encode($response)); exit();
    } */
    $user= User::create($username, $password);

    $_SESSION['username'] = $username;
    $_SESSION['authsalt'] = $user->getSalthash();

    if(is_null($user)){
        $response['status'] = 'Error: User::create failed';
        print(json_encode($response)); exit();
    }

    $auth_cookie_val = md5($_SESSION['username'] . $_SERVER['REMOTE_ADDR'] . $_SESSION['authsalt']);

    setcookie('LOGIN', $auth_cookie_val, 0, 
        "wwwp.cs.unc.edu/Courses/comp426-f16/users/palmour/final_project", "wwwp.cs.unc.edu", 
        true);


    $response['status'] = 'OK';
    print(json_encode($response)); exit();
    
    
?>