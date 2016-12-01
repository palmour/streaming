<?php
    session_start();
    require_once('db_connect.php');
    require_once('Library.php');
    require_once('Playlist.php');

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $post = json_decode(file_get_contents("php://input"), true);

	    if(is_null($post['action'])||is_null($post['Username'])){

		    header("HTTP/1.1 400 Bad Request");
		    print("Format Not Recognized.");
	    }else{
            $action = $post['action'];
            $username = $post['username'];
            $playlist = $post['playlist'];
            $playlist_title = $post['playlist_title'];

            $mysqli = db_connect::getMysqli();

            $response = array();

            if($action=='getLibrary'){

                $lib_obj = Library::create($username);
                $songs = $lib_obj->getSongs();
                if(is_null($songs)){
                    $response['stats'] = "error";
                    header("HTTP/1.1 500 Internal Server Error");
                    print($response);
                }
        
                $response['songs'] = $songs;
                $response['number'] = sizeof($songs);
                $response['status'] = "OK";

                header("Content-type: application/json");
                print(json_encode($response));
                $mysqli->close();
                exit();
            }
            
            else if ($action=='getPlaylist'){
            
                if(is_null($playlist)){
                    header("HTTP/1.1 400 Bad Request");
                    $response['status'] = "Playlist title null for getPlaylist";
                    print(json_encode($response));
                    exit();
                }

                $result = Playlist::getPlaylist();
                if(!$result){
                    $response['number'] = 0;
                    $response['status'] = "Empty";
                    print(json_encode($response));
                }

                $response['Filename'] = $result.getPathname();
                $result->close();

                header("Content-type: application/json");
                print(json_encode($result));
                $mysqli->close();
                exit();
            }

            else if($action=='createPlaylist'){
                if(is_null($playlist_title)){
                    header("HTTP/1.1 400 Bad Request");
                    $response['status'] = "Playlist title null for createPlaylist";
                    print(json_encode($response));
                    exit();
                }

                $result = Playlist::create($username, $playlist_title);
                if(!$result){
                    $response['status'] = "createPlaylist failed";
                    header("HTTP/1.1 500 Internal Server Error");
                    print(json_encode($response));
                    exit();
                }

                $response['username'] = $result.getUsername();
                $response['title'] = $result.getTitle();
                $response['pathname'] = $result.getPathname();
                $response['id'] = $result.getID();
                header("Content-Type: application/json");
			    print(json_encode($response)); 
                $mysqli->close();
                exit();

            }
        }
    }
?>