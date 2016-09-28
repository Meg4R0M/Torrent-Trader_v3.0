<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2012-06-14 17:31:26 +0100 (Thu, 14 Jun 2012) $
//      $LastChangedBy: torrenttrader $
//
//      http://www.torrenttrader.org
//
//
require_once("backend/functions.php");
dbconn();

//check permissions
if ($site_config["MEMBERSONLY"]){
	loggedinonly();

	if($CURUSER["view_torrents"]=="no")
		show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
}

stdhead(T_("TODAYS_TORRENTS"));

begin_frame(T_("TODAYS_TORRENTS"));

$date_time=get_date_time(gmtime()-(3600*24)); // the 24 is the hours you want listed

	$catresult = SQL_Query_exec("SELECT id, name FROM categories ORDER BY sort_index");

		while($cat = mysql_fetch_assoc($catresult))
		{
			$orderby = "ORDER BY torrents.id DESC"; //Order
			$where = "WHERE banned = 'no' AND category='$cat[id]' AND visible='yes'";
			$limit = "LIMIT 10"; //Limit

			$query = "SELECT torrents.id, torrents.anon, torrents.category, torrents.leechers, torrents.nfo, torrents.seeders, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.comments, torrents.numfiles, torrents.filename, torrents.owner, torrents.external, torrents.freeleech, categories.name AS cat_name, categories.parent_cat AS cat_parent, categories.image AS cat_pic, users.username, users.privacy FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id $where AND torrents.added>='$date_time' $orderby $limit";

			$res = SQL_Query_exec($query);
			$numtor = mysql_num_rows($res);

			if ($numtor != 0) {
					echo "<b><a href='../browse/?cat=".$cat["id"]."'>$cat[name]</a></b>";
					# Got to think of a nice way to display this.
                    #list($pagertop, $pagerbottom, $limit) = pager(1000, $count, "../browse/"); //adjust pager to match LIMIT
					torrenttable($res);
					echo "<br />";
			}
		

		}
end_frame();
stdfoot();
?>