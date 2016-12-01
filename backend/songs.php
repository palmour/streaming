<?php
    session_start();
    require_once(db_connect);
    require_once(Library);

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

            $mysqli = db_connect::getMysqli("palmour", "CH@ngemenow99Please!palmour");

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

                $sqlrequest = "SELECT filename FROM playlists WHERE Title = \"".$playlist."\" AND Username = \"".$username."\"";
                $result = $mysqli->query($sqlrequest);
                if(!$result){
                    $response['number'] = 0;
                    $response['status'] = "Empty";
                    print(json_encode($response));
                }
                $filename_row = $result->fetch_assoc();
                $response['Filename'] = $filename_row['Filename'];
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
                $playlist_path = "Playlists/".$username."/".$playlist_title.".txt";

                $sqlrequest = "INSERT INTO playlists (Title, Username, Filename) VALUES (\"".$playlist_title."\", \"".$username."\", \"".$playlist_path."\")";
                $result = $mysqli->query($sqlrequest);
                if(!$result){
                    $response['status'] = "createPlaylist failed";
                    header("HTTP/1.1 500 Internal Server Error");
                    print(json_encode($response));
                    exit();
                }

                $response['status'] = "OK";
                $response['affected'] = $mysqli->affected_rows;
                header("Content-Type: application/json");
			    print(json_encode($response)); 
                $mysqli->close();
                exit();

            }
        }
    }
?>