<?php
    require_once("db_connect.php");

    class Artist{
        private $artist_name;
        private $artist_id;

        private function __construct($name, $id){
            $this->artist_name = $name;
            $this->artist_id = $id;
        }

        public static function create($name){
            $exists = Artist::getArtistByName($name);
            if(!is_null($exists)){return $exists;}
            $mysqli = db_connect::getMysqli();
            $id;
            $insert_query = "INSERT INTO artists (ArtistName) VALUES(\"".$name."\")";
            $result = $mysqli->query($insert_query);
            $mysqli->close();
            if($result){
                return Artist::getArtistByName($name);
            }
            return null;
        }

        public static function getArtistById($id){
            $mysqli = db_connect::getMysqli();
            $get_artist = "SELECT * FROM artists WHERE ArtistID = ".$id;
            $result = $mysqli->query($get_artist);
            $mysqli->close();
            if($result->num_rows < 1){return null;}
            
            $row = $result->fetch_assoc();
            return new Artist($row['ArtistName'], $row['ArtistID']);
            
        }

        public static function getArtistByName($name){
            $mysqli = db_connect::getMysqli();
            $get_artist = "SELECT * FROM artists WHERE ArtistName =\"".$name."\"";
            $result = $mysqli->query($get_artist);
            $mysqli->close();
            if($result->num_rows < 1){return null;}
            
            $row = $result->fetch_assoc();
            return new Artist($row['ArtistName'], $row['ArtistID']);
           
        }

        public function getName(){return $this->artist_name;}

        public function getId(){return $this->artist_id;}
    }
?>