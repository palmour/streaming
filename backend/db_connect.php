<?php
class db_connect{

    public static function getMysqli(){
        return mysqli_connect("classroom.cs.unc.edu", "palmour", "CH@ngemenow99Please!palmour", "palmourdb");
    }
}

?>