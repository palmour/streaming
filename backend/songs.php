<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    require_once('db_connect.php');
    require_once('Library.php');
    require_once('Playlist.php');
    require_once('Artist.php');
    require_once('Release.php');

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $post = json_decode(file_get_contents("php://input"), true);
	    if(is_null($post['action'])){
		    header("HTTP/1.1 400 Bad Request");
		    print("Format Not Recognized.");
            exit();
	    }else if(!isset($_COOKIE['LOGIN'])){
            header("HTTP/1.1 401 Acess Denied");
            print("User not logged in");
            exit();
        }
        else if($_COOKIE['LOGIN']!= md5($_SESSION['username'].$_SERVER['REMOTE_ADDR'].$_SESSION['authsalt'])){
            header("HTTP/1.1 401 Acess Denied");
            print("User not logged in");
            exit();
        }
        else{
            $action = $post['action'];
            $username = $_SESSION['username'];
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

                $playlist = $post['playlist'];
                $playlist_title = $post['playlist_title'];
            
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

                $playlist = $post['playlist'];
                $playlist_title = $post['playlist_title'];

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
                header("Content-type: application/json");
			    print(json_encode($response)); 
                $mysqli->close();
                exit();
            }

            else if($action=='getMaster'){
                
                $result = $mysqli->query("SELECT * FROM songs");
                if($result->num_rows < 1){
                    $response['status'] = "No songs";
                    header("Content-type: application/json");
                    print(json_encode($response)); exit();
                }
                $i=0;
                while($row = $result->fetch_assoc()){
                    $response[$i] = array();
                    $artist_obj = Artist::getArtistById($row['ArtistID']);
                    $release_obj = Release::getReleaseById($row['ReleaseID']);
                    $response[$i]['SongID'] = $row['SongID'];
                    $response[$i]['ArtistID'] = $row['ArtistID'];
                    $response[$i]['Artist'] = $artist_obj->getName();
                    $response[$i]['Title'] = $row['Title'];
                    $response[$i]['ReleaseID'] = $row['ReleaseID'];
                    $response[$i]['Release'] = $release_obj->getTitle();
                    $response[$i]['Pathname'] = $row['Pathname'];
                    $i++;
                }
                
                header('Content-type: application/json');
                print(json_encode($response));
            }

            else if($action=='addToLibrary'){
                $id = $post['songid'];
                $response['songid'] = $id; 
                $response['username'] = $username;
                
                $result = Library::addSong($username, $id);
                if(!$result){
                    header('Content-type: application/json');
                    header('HTTP/1.1 505 you suck');
                    print(json_encode($result));
                }
                header("Content-type: application/json");
                print(json_encode($result));
            }
        }
    }
?>