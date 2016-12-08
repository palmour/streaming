<?php
    session_start();
    require_once('Artist.php');
    require_once('Release.php');
    require_once('Song.php');

    $dir = "../master_library";

    scan(1, $dir);

    //all subdirectories of /master_library are assumed to be named according to artist

    function scan($depth, $dir){
        $files = scandir($dir);

        $i=0; 
        while($i<sizeof($files)){
            if($depth==1){
                Artist::create($files[$i]);
                scan(2, $dir.$files[$i]);
            }
            else if($depth==2){
                Release::create($files[$i]);
                scan(3, $dir.$files[$i]);
            }
            else if($depth==3){
                Song::create($files[$i]);
            }
            $i++;   
        }
    }

?>