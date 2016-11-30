<?php
    session_start();

    function unique($un){
        if(is_null(User::find($un))){return true;}
        return false;
    }

    $username = $_GET['username'];
    $password = $_GET['password'];

    if(unique($username)){
        header('Content-type: application/json');
        User::create($username, $password);

        print(json_encode(true)); exit();
    }
    print(json_encode(false));
?>