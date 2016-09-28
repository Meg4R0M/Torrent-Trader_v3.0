<?php
#================================#
#       TorrentTrader 2.08       #
#  http://www.torrenttrader.org  #
#--------------------------------#
#       Modified by BigMax       #
#================================#

require_once("backend/functions.php");
dbconn();
loggedinonly();

	$gottorrent = (int) $_GET["torrent"];

	if (!isset($gottorrent))
		show_error_msg("Error", " ... No torrent selected", 1);

	if ((get_row_count("bookmarks", "WHERE userid=$CURUSER[id] AND torrentid = $gottorrent")) > 0)
		show_error_msg("Error", "Already bookmarked torrent", 1);

	if ((get_row_count("torrents", "WHERE id = $gottorrent")) > 0)
	{
		SQL_Query_exec("INSERT INTO bookmarks (userid, torrentid) VALUES ($CURUSER[id], $gottorrent)");
		
		stdhead("Bookmarks");
		begin_frame("Successfully");
		echo "<div style='margin-top:10px; margin-bottom:10px' align='center'>
			Torrent was successfully bookmarked. &nbsp;
			[<a href=torrents-details.php?id=$gottorrent><b>Go to Torrent</b></a>] or
			[<a href=bookmark.php><b>See Your Bookmarks</b></a>]
		</div>";
		end_frame();
		stdfoot();
	}
	else
		show_error_msg("Error", "ID not found", 1);
?>