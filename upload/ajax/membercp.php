<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2012-06-14 17:31:26 +0100 (Thu, 14 Jun 2012) $
//      $LastChangedBy: torrenttrader $
//
//      http://www.torrenttrader.org
//
//
require_once("../backend/functions.php");
dbconn();
loggedinonly();

$action = $_REQUEST["action"];
$do = $_REQUEST["do"];

if ($action=="membercp"){
	
	if ($do=="personal_details"){
		$updateset = array();
	
		$age = $_GET["age"];
		$gender= $_GET["gender"];
		$country = $_GET["country"];
	
		if (is_valid_id($country))
			$updateset[] = "country = $country";
		if (is_valid_id($age))
			$updateset[] = "age = '$age'";

		$updateset[] = "gender = ".sqlesc($gender);
	
		if (!$message) {
			SQL_Query_exec("UPDATE users SET " . implode(",", $updateset) . " WHERE id = " . $CURUSER["id"]."");
		}
	}
}
?>