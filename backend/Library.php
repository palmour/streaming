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
            __construct($un);
        }

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
                    return null;
                }

                $artist_name_row = $artist_name_result->fetch_assoc();
                $songs[$i]['ArtistName']= $artist_name_row['ArtistName'];
                $sqlrequest = "SELECT Title FROM releases WHERE ReleaseID = ".$songs[$i]['ReleaseID'];
                $release_title_result = $mysqli->query($sqlrequest);

                if($release_title_result){
                    return null;
                }

                $release_title_row = $release_title_result->fetch_assoc();
                $songs[$i]['ReleaseTitle'] = $release_title_row['Title'];
                $i++;
            }

            return $this->$all_songs = $songs;
        }

        public function getSongs(){
            populateSongInfo();
            return $all_songs;
        }

    }
?>