<?php
    require_once('db_connect.php');

    class User{
        private $username;
        private $salthash;

        private function __construct($un, $sh){
            $this->username = $un;
            $this->salthash = $sh;
        }

        public static function create($un, $pw){
            $mysqli = db_connect::getMysqli();
            if($mysqli->connect_errno){return null;}

            $hash = User::create_hash($un, $pw);

            $insert_query = "INSERT IGNORE INTO users (Username, Salthash) VALUES (\"".$un."\", \"".$hash."\")";
            //query does nothing on duplicate. Make sure username is unique by calling find 
            $result = $mysqli->query($insert_query);
            if(!$result){return null;}

            return User::find($un);
        }

        public static function find($un){
            $mysqli = db_connect::getMysqli();
            if($mysqli->connect_errno){return null;}

            $result = $mysqli->query("SELECT * FROM users WHERE Username = \"".$un."\"");
            if($result){
                $user_info = $result->fetch_assoc();
                return new User(strval($user_info['Username']), strval($user_info['Salthash']));
            }
            else{return null;}
        }

        public static function create_hash($un, $pw){ //functions as the setter for $salthash 
            $new_salt = $un."-salt";
            return md5($un.$new_salt);
        }

        public function getSalthash(){
            return $this->salthash;
        }

        public function getUsername(){
            return $this->username;
        }

        public function setUsername($un){
            $this->username = $un;
        }

        public function getJSON(){
            $json_obj = array('username' => $this->username, 'salthash' => $this->salthash);
            return json_encode($json_obj);
        }
    }
?>