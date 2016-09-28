<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2011-11-17 00:13:07 +0000 (Thu, 17 Nov 2011) $
//      $LastChangedBy: dj-howarth1 $
//
//      http://www.torrenttrader.org
//
//
require_once("backend/functions.php");
dbconn(true);

stdhead(T_("HOME"));

//check
if (file_exists("check.php") && $CURUSER["class"] == 7){
	show_error_msg("WARNING", "Check.php still exists, please delete or rename the file as it could pose a security risk<br /><br /><a href='check.php'>View Check.php</a> - Use to check your config!<br /><br />",0);
}

//Site Notice
if ($site_config['SITENOTICEON']){
	begin_frame(T_("NOTICE"));
	echo $site_config['SITENOTICE'];
	end_frame();
}




// latest torrents
begin_frame(T_("LATEST_TORRENTS"));



if ($site_config["MEMBERSONLY"] && !$CURUSER) {
	echo "<br /><br /><center><b>".T_("BROWSE_MEMBERS_ONLY")."</b></center><br /><br />";
} else {
	$query = "SELECT torrents.id, torrents.anon, torrents.announce, torrents.category, torrents.leechers, torrents.nfo, torrents.seeders, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.comments, torrents.numfiles, torrents.filename, torrents.owner, torrents.external, torrents.freeleech, categories.name AS cat_name, categories.image AS cat_pic, categories.parent_cat AS cat_parent, users.username, users.privacy, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id WHERE visible = 'yes' AND banned = 'no' ORDER BY id DESC LIMIT 25";
	$res = SQL_Query_exec($query);
	if (mysql_num_rows($res)) {
		torrenttable($res);
	}else {
        
     print("<div class='f-border'>");
     print("<div class='f-cat' width='100%'>".T_("NOTHING_FOUND")."</div>");
     print("<div>");
     print T_("NO_UPLOADS");
     print("</div>");
     print("</div>");

	}
	if ($CURUSER)
		SQL_Query_exec("UPDATE users SET last_browse=".gmtime()." WHERE id=$CURUSER[id]");

}
end_frame();





stdfoot();
?>