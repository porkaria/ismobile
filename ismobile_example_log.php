<?php
require_once "ismobile.class.php";

class DataBase implements IsMobileLogger {

    public function log($msg) {

        $c = mysql_connect('localhost', 'root', 'porkaria');

        if (mysql_select_db('test', $c)) {

            if (mysql_query("INSERT INTO ismobile_log VALUES('', '$msg')")) {
                return true;
            } else {
                exit(mysql_error());
            }
        } else {
            exit('Database Error..');
        }    

    }

}

echo '<h1> Textbook example of how to use the option to log the IsMobile </h1>';

$log = new DataBase();
$ismob = new IsMobile(false, false, $log);

if ($ismob->CheckMobile()) {
    echo 'Logging... Mobile Device... =] ';
} else {
    echo 'Not logging..';
}

?>
