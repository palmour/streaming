<?php
    require_once('db_connect');

    class Library{
        private $username;
        private $all_songs;
        
        private function __construct($un){
            $this->$username = $un;
            populateSongInfo();
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
                $sqlrequest = "SELECT * FROM songs WHERE SongID = ".$row['SongID'];
                $song_result = $mysqli->query($sqlrequest);
                $songs[$i]= array();
                $song_row = $song_result->fetch_assoc();
                $songs[$i]['SongID'] = $song_row['SongID'];
                $songs[$i]['ArtistID'] = $song_row['ArtistID'];
                $songs[$i]['Title'] = $song_row['Title'];
                $songs[$i]['ReleaseID'] = $song_row['ReleaseID'];
                $songs[$i]['Pathname'] = $song_row['Pathname'];

                $i++;
            }

            $i=0;
            while($i<sizeof($songs)){
                $sqlrequest = "SELECT ArtistName FROM artists WHERE ArtistID = ".$songs[$i]['ArtistID'];
                $artist_name_result = $mysqli->query($sqlrequest);

                if($artist_name_result){
                    return false;
                }

                $artist_name_row = $artist_name_result->fetch_assoc();
                $songs[$i]['ArtistName']= $artist_name_row['ArtistName'];
                $sqlrequest = "SELECT Title FROM releases WHERE ReleaseID = ".$songs[$i]['ReleaseID'];
                $release_title_result = $mysqli->query($sqlrequest);

                if($release_title_result){
                    return false;
                }

                $release_title_row = $release_title_result->fetch_assoc();
                $songs[$i]['ReleaseTitle'] = $release_title_row['Title'];
                $i++;
            }

            $this->$all_songs = $songs;
            return true;
        }

        public function getAllSongs(){
            populateSongInfo();
            return $all_songs;
        }

        public function getSongByID($sid){
            populateSongInfo();
            $i=0;
            while($i<sizeof($all_songs)){
                if($all_songs[$i]['SongID']==$sid){return $all_songs[$i];}
                $i++;
            }
            return null;
        }

    }
?>