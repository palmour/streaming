<?php
class db_connect{

    public static function getMysqli($username, $password){
        return mysqli_connect("classroom.cs.unc.edu", $username, $password, "palmourdb");
    }
}

?>