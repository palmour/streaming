<?php
    require_once('db_connect.php');
    require_once('Artist.php');
    require_once('Release.php');

    class Song{
        private $song_id;
        private $title;
        private $artist_id;
        private $release_id;
        private $pathname;

        private function __construct($id, $title, $artist_id, $release_id, $pathname){
            $this->$song_id = $id;
            $this->title = $title;
            $this->artist_id = $artist_id;
            $this->release_id = $release_id;
            $this->pathname = $pathname;
        }

        public static function create($release_date, $pathname){

            $exists = Song::getSongByPath($pathname);
            if(!is_null($exists)){return $exists;}
            
            $mysqli = db_connect::getMysqli();

            $path_array = explode("/", $pathname);
            $artist_name = $path_array[0];
            $release_title = $path_array[1];
            $song_title = $path_array[2];

            $artist_obj = Artist::getArtistByName($artist_name);
            if(is_null($artist_obj)){
                $artist_obj = Artist::create($artist_name);
                if(is_null($artist_obj)){return null;}
            }
            
            $artist_id = $artist_obj->getId();

            $release_obj = Release::getReleaseByTitle($release_title);
            if(is_null($release_obj)){
                $release_obj = Release::create($release_title, $release_date);
                if(is_null($release_obj)){return null;}
            }

            $release_id = $release_obj->getId();

            $insert_query = "INSERT INTO songs (ArtistID, Title, ReleaseID, Pathname) VALUES (".$artist_id.", \"".$song_title."\", ".$release_id.", \"".$pathname."\")";
            $result = $mysqli->query($insert_query);
            if(is_null($result)){return null;}

            return Song::getSongByPath($pathname);
        }

        public static function getSongByPath($path){
            $mysqli = db_connect::getMysqli();
            $query = "SELECT * FROM songs WHERE Pathname = \"".$path."\"";
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();

            return new Song($row['SongID'], $row['Title'], $row['ArtistID'], $row['ReleaseID'], $row['Pathname']);
        }

        public static function getSongById(){
            $mysqli = db_connect::getMysqli();
            $query = "SELECT * FROM songs WHERE SongID = ".$id;
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();

            return new Song($row['SongID'], $row['Title'], $row['ArtistID'], $row['ReleaseID'], $row['Pathname']);
        }

        public function getId(){return $this->song_id;}

        public function getTitle(){return $this->title;}

        public function getArtistId(){return $this->artist_id;}

        public function getReleaseId(){return $this->release_id;}

        public function getPathname(){return $this->pathname;}

    }
?>