<?php
  //
  //  TorrentTrader v2.x
  //      $LastChangedDate: 2011-10-20 11:45:04 +0100 (Thu, 20 Oct 2011) $
  //      $LastChangedBy: x-cooly-btit $
  //
  //      http://www.torrenttrader.org
  //

require_once("backend/functions.php");
dbconn();

$id = (int) $_GET["id"];
$md5 = $_GET["secret"];

if (!$id || !$md5)
	show_error_msg(T_("ERROR"), T_("INVALID_ID"), 1);

$res = SQL_Query_exec("SELECT `password`, `secret`, `status` FROM `users` WHERE `id` = '$id'");
$row = mysql_fetch_assoc($res);

if (!$row)
	show_error_msg(T_("ERROR"), sprintf(T_("CONFIRM_EXPIRE"), $site_config['signup_timeout']/86400), 1);

if ($row["status"] != "pending") {
	header("Refresh: 0; url=account-confirm-ok.php?type=confirmed");
	die;
}

if ($md5 != md5($row["secret"]))
	show_error_msg(T_("ERROR"), T_("SIGNUP_ACTIVATE_LINK"), 1);

$secret = mksecret();

SQL_Query_exec("UPDATE `users` SET `secret` = ".sqlesc($secret).", `status` = 'confirmed' WHERE `id` = '$id' AND `secret` = ".sqlesc($row["secret"])." AND `status` = 'pending'");    
if (!mysql_affected_rows())
	show_error_msg(T_("ERROR"), T_("SIGNUP_UNABLE"), 1);

header("Refresh: 0; url=account-confirm-ok.php?type=confirm");
?>