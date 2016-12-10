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
    if(!is_null(User::create($username, $password))){
        $response['status'] = 'OK';
        print(json_encode($response)); exit();
    }
    $response['status'] = 'Error: User::create failed';
    print(json_encode($response)); exit();
?>