<?php
    require_once('db_connect.php');

    class Release{
        private $release_id;
        private $release_title;
        private $release_date;

        private function __construct($id, $title, $date){
            $this->release_id = $id;
            $this->release_title = $title;
            $this->release_date = $date;
        }

        public static function create($title, $date){
            $exists = Release::getReleaseByTitle($title);
            if(!is_null($exists)){return $exists;}
            $mysqli = db_connect::getMysqli();
            $insert_query = "INSERT INTO releases (Title, ReleaseDate) VALUES (\"".$title."\", \"".$date."\")";
            $result = $mysqli->query($insert_query);
            return getReleaseByTitle($title);
        }

        public static function getReleaseByTitle($title){
            $mysqli = db_connect::getMysqli();
            $query = "SELECT * FROM releases WHERE Title = \"".$title."\"";
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();
            return new Release($row['ReleaseID'], $row['Title'], $row['ReleaseDate']);
        }

        public static function getReleaseById($id){
            $mysqli = db_connect::getMysqli();
            $query = "SELECT * FROM releases WHERE ReleaseID = ".$id;
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();
            return new Release($row['ReleaseID'], $row['Title'], $row['ReleaseDate']);
        }

        public function getId(){return $this->release_id;}

        public function getTitle(){return $this->release_title;}

        public function getDate(){return $this->date;}
    }
?>