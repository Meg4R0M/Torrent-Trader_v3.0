<?php
//MYSQL CONNECTION INFO, DONT PASS IT OUT!

//Access Security check
if (preg_match('/mysql.php/i',$_SERVER['PHP_SELF'])) {
	die;
}

//Change the settings below to match your MYSQL server connection settings
$mysql_host = "db_host";  //leave this as localhost if you are unsure
$mysql_user = "db_user";  //Username to connect
$mysql_pass = "db_pass";  //Password to connect
$mysql_db = "db_name";    //Database name
?>
