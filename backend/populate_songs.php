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
        echo "New directory: ".$dir.PHP_EOL;
        $i=0; 
        while($i<sizeof($files)){
            $name = $files[$i];
            
            if(($name!=".")&&($name!="..")){
                
                if($depth==1){
                    $new_artist = Artist::create($name);
                    echo "Artist: ";
                    if(is_null($new_artist)){echo " (null)".PHP_EOL;}
                    else{echo " ".$new_artist->getName().PHP_EOL;}
                    scan(2, $dir."/".$name);
                }
                else if($depth==2){
                    $new_release = Release::create($name);
                    echo "Release: ";
                    if(is_null($new_release)){echo " (null)".PHP_EOL;}
                    else{echo " ".$new_release->getTitle().PHP_EOL;}
                    scan(3, $dir."/".$name);
                }
                else if($depth==3){
                    $new_song = Song::create($dir."/".$name);
                    echo "Song: ";
                    if(is_null($new_song)){echo " (null)"." ".$dir."/".$name.PHP_EOL;}
                    else{echo " ".$new_song->getTitle().PHP_EOL;}
                }
            }
            $i++;   
        }
    }

?>