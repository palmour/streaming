<?php
    require_once('db_connect.php');
    require_once('Song.php');
    require_once('Artist.php');
    require_once('Release.php');

    class Library{
        private $username;
        private $all_songs;
        
        private function __construct($un){
            $this->$username = $un;
            Library::populateSongInfo();
        }

        public static function create($un){
            if(is_null($un)){return null;}
            return new Library($un);
        }

        //fills the $songs attribute of the Library object.
        //$songs is an array of arrays. Each array contains the SongID, ArtistID, Title, 
        //and ReleaseID for a given song in the library 
        //returns true if successful and false otherwise
        public function populateSongInfo(){ 
           $mysqli = db_connect::getMysqli();
            $sqlrequest = "SELECT SongID FROM library WHERE Username = \"".$this->$username."\"";
            $result = $mysqli->query($sqlrequest);
            $songs = array(); $i=0;
            while($row = $result->fetch_assoc()){

                $song_obj = Song::getSongById($row['SongID']);

                $songs[$i]= array();
                $song_row = $song_result->fetch_assoc();
                $songs[$i]['SongID'] = $song_obj->getId();
                $songs[$i]['ArtistID'] = $song_obj->getArtistId();
                $songs[$i]['Title'] = $song_obj->getTitle();
                $songs[$i]['ReleaseID'] = $song_obj->getReleaseId();
                $songs[$i]['Pathname'] = $song_obj->getPathname();

                $i++;
            }

            $i=0;
            while($i<sizeof($songs)){
                $artist_obj = Artist::getArtistById();

                if($artist_obj){
                    return false;
                }

                $songs[$i]['ArtistName']= $artist_obj->getName();

                $release_obj = Release::getReleaseById(); 

                if($release_obj){
                    return false;
                }

                $songs[$i]['ReleaseTitle'] = $release_obj->getTitle();
                $i++;
            }

            $this->$all_songs = $songs;
            return true;
        }

        public function getAllSongs(){
            Library::populateSongInfo();
            return $all_songs;
        }

        public function getSongByID($sid){
            Library::populateSongInfo();
            $i=0;
            while($i<sizeof($all_songs)){
                if($all_songs[$i]['SongID']==$sid){return $all_songs[$i];}
                $i++;
            }
            return null;
        }
    }
?>