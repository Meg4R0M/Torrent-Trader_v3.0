<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2012-07-07 16:40:08 +0100 (Sat, 07 Jul 2012) $
//      $LastChangedBy: torrenttrader $
//
//      http://www.torrenttrader.org
//
//
// Confirm account OK!
require_once("backend/functions.php");
dbconn();

$type = $_GET["type"];
$email = $_GET["email"];

if (!$type)
	die;

if ($type == "confirmed") {
	stdhead(T_("ACCOUNT_ALREADY_CONFIRMED"));
        begin_frame(T_("ACCOUNT_ALREADY_CONFIRMED"));
	print(T_("ACCOUNT_ALREADY_CONFIRMED"). "\n");
	end_frame();
}

//invite code
elseif ($type == "invite" && $_GET["email"]) {
stdhead(T_("INVITE_USER"));
     begin_frame();
		Print("<center>".T_("INVITE_SUCCESSFUL")."!</center><br /><br />".T_("A_CONFIRMATION_EMAIL_HAS_BEEN_SENT")." (" . htmlspecialchars($email) . "). ".T_("THEY_NEED_TO_READ_AND_RESPOND_TO_THIS_EMAIL")."");
	end_frame();
stdfoot();
die;
}//end invite code

else
	die();

stdfoot();
?>
