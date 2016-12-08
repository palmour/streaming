<?php
class db_connect{

    public static function getMysqli(){
        return new mysqli("classroom.cs.unc.edu", "palmour", "CH@ngemenow99Please!palmour", "palmourdb");
    }
}

?>