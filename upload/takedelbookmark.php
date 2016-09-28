<?php
#================================#
#       TorrentTrader 2.08       #
#  http://www.torrenttrader.org  #
#--------------------------------#
#       Modified by BigMax       #
#================================#

require_once("backend/functions.php");
dbconn(true);
loggedinonly();

	$delid = (int) $_GET['bookmarkid'];

	$res2 = SQL_Query_exec("SELECT id, userid FROM bookmarks WHERE torrentid = $delid AND userid = $CURUSER[id]");

	$arr = mysql_fetch_assoc($res2);
	if (!$arr)
		show_error_msg("Error!", "ID not found in your bookmarks list...", 1);

	SQL_Query_exec("DELETE FROM bookmarks WHERE torrentid = $delid AND userid = $CURUSER[id]");
	header("Refresh: 0;url=" . $_SERVER['HTTP_REFERER']);
?>