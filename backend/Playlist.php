<?php
    require_once('db_connect.php');

    class Playlist{
        private $username;
        private $title;
        private $pathname; 
        private $id;

        private function __construct($un, $tl, $pn, $id){
            $this->username = $un;
            $this->title = $tl;
            $this->pathname = $pn;
            $this->id = $id;
        }

        public static function create($un, $tl){
            if((is_null($un))||is_null($tl)){return null;}

            $mysqli = db_connect::getMysqli();

            if(!is_null(Playlist::getPlaylist($un, $tl))){return null;}

            //replace all spaces in title with "-" to get filename
            $title_array = explode(" ", $tl);
            $i=0; $filename = "";
            while($i<sizeof($title_array)){
                $filename = $filename.$title_array[$i];
                if($i<sizeof($title_array)-1){$filename = $filename."-";}
                $i++;
            }
            $pn = "../playlists/".$un."/".$filename.".txt";

            $file = fopen($pn, "w");
            fclose($file);

            $insert_query = "INSERT INTO playlists (Title, Username, Filename) VALUES (\"".$playlist_title."\", \"".$username."\", \"".$playlist_path."\")";
            $result = $mysqli->query($insert_query);
            if(!$result){return null;}

            $retrieve_id = "SELECT PlaylistID FROM playlists WHERE Title = \"".$tl."\" AND Username = \"".$un."\"";
            $id_result = $mysqli->query($retrieve_id);
            if(!$id_result){return null;}
            $id_assoc = $id_result->fetch_assoc();
            $id = $id_assoc['PlaylistID'];

            return getPlaylist($un, $tl);
        }

        public static function getPlaylist($un, $tl){
            if((is_null($un))||is_null($tl)){return null;}

            $mysqli = db_connect::getMysqli();

            $get_query = "SELECT * FROM playlists WHERE Username = \"".$un."\" AND Title = \"".$tl."\"";
            $get_result = $mysqli->query($get_query);
            if(!$get_result){return null;}
            $get_assoc = $get_result->fetch_assoc();
            return new Playlist($get_assoc['Username'], $get_assoc['Title'], $get_assoc['Pathname'], $get_assoc['PlaylistID']);
        }

        public static function getPlaylistById($id){
            $mysqli = db_connect::getMysqli();

            $query = "SELECT * FROM playlists WHERE PlaylistID = ".$id;
            $result = $mysqli->query($query);
            if($result->num_rows != 1){return null;}
            $assoc = $result->fetch_assoc();
            return new Playlist($assoc['Username'], $assoc['Title'], $assoc['Pathname'], $assoc['PlaylistID']);
        }

        public function addSong($sid){
            $file = fopen($this->$pathname, "a");
            fwrite($file, strval($sid));
            fclose($file);
        }

        public function getUsername(){return $this->$username;}

        public function getTitle(){return $this->$title;}

        public function getPathname(){return $this->$pathname;}

        public function getID(){return $this->$id;}

    }
?>
