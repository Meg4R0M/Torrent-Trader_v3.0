<?php
#================================#
#       TorrentTrader 2.08       #
#  http://www.torrenttrader.org  #
#--------------------------------#
#       Modified by BigMax       #
#================================#

require_once("backend/functions.php");
dbconn(false);
loggedinonly();

	stdhead("Your Bookmarks");
	begin_frame("Your Bookmarks");

	$page = (int) $_GET['page'];
	$perpage = 25;
	$res = SQL_Query_exec("SELECT COUNT(*) FROM bookmarks WHERE userid = " . $CURUSER["id"] ."");
	$row = mysql_fetch_array($res);
	$count = $row[0];
	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "bookmark.php?");

	$where = "WHERE bookmarks.userid = " . $CURUSER["id"] ."";
	$orderby = "ORDER BY added DESC";
	
	$query = SQL_Query_exec("SELECT bookmarks.id as bookmarkid,
	torrents.size,
	torrents.freeleech,
	torrents.external,
	torrents.id,
	torrents.category,
	torrents.name,
	torrents.filename,
	torrents.added,
	torrents.banned,
	torrents.comments,
	torrents.seeders,
	torrents.leechers,
	torrents.times_completed,
	categories.name AS cat_name,
	categories.parent_cat AS cat_parent,
	categories.image AS cat_pic
	FROM bookmarks
	LEFT JOIN torrents ON bookmarks.torrentid = torrents.id
	LEFT JOIN categories ON category = categories.id
	$where $orderby $limit");

	print("<div style='margin-top:5px; margin-bottom:15px' align='center'><font size='2'>You have bookmarked <font color='#0080FF'><b>".$count."</b></font> torrent".($count != 1 ? "s" : "")."</font></div>");

	$allcats = mysql_num_rows($query);
	if($allcats == 0) {
		echo '<table class="f-border" width="90%" cellpadding="10" align="center"><tr><td class="f-border comment" align="center"><b>Your Bookmarks list is empty!</b></td></tr></table><br />';
	} else {
		If ($count > $perpage) { print($pagertop); }
		?>
		<table align="center" cellpadding="4" cellspacing="0" class="table_table" width="90%">
			<tr>
				<th class="table_head">Type</th>
				<th class="table_head" align="left">Torrent Name</th>
				<th class="table_head" align="left">Size</th>
				<th class="table_head">Added</th>
				<th class="table_head"><img src="images/down.png" border="0" title="Download"></th>
				<th class="table_head"><img src="images/comment.png" border="0" title="Comments"></th>
				<th class="table_head"><img src="images/seed.gif" border="0" title="Seeders"></th>
				<th class="table_head"><img src="images/leech.gif" border="0" title="Leechers"></th>
				<th class="table_head"><img src="images/check.png" border="0" title="Completed"></th>
				<th class="table_head">L/E</th>
				<th class="table_head"><img src="images/trash.png" title="Delete Bookmarks" border="0"></th>
			</tr>
		<?php
		while($row = mysql_fetch_assoc($query))
		{
			$length = 40;	//===| Cut name length 
			$smallname = htmlspecialchars(CutName($row["name"], $length));
			$dispname = "<b>".$smallname."</b>";
			
			if ($row["freeleech"] == 1)
			{
				$freeleech = "<img src='images/free.gif' border='0' title='Free Leech'>";
			} else {
				$freeleech = "";
			}
			
			echo "<tr>";
				print ("<td class='table_col1' width='1%' align='center' valign='middle'>");
					if (!empty($row["cat_name"])) {
						print("<a href=\"torrents.php?cat=".$row["category"]."\">");
						if (!empty($row["cat_pic"]) && $row["cat_pic"] != "")
							print("<img border=\"0\" src=\"".$site_config['SITEURL']."/images/categories/".$row["cat_pic"]."\" title=\"".$row["cat_parent"].": ".$row["cat_name"]."\" />");
						else
							print($row["cat_parent"].": ".$row["cat_name"]);
						print("</a>");
					} else
						print("---");
				print("</td>\n");
				
				echo "<td class='table_col2' nowrap='nowrap'>".(count($expandrows)?"<a href=\"javascript: klappe_torrent('t".$row['id']."')\"><img border=\"0\" src=\"".$site_config["SITEURL"]."/images/plus.gif\" id=\"pict".$row['id']."\" alt=\"Show/Hide\" class=\"showthecross\" /></a>":"")."&nbsp;<a title=\"".$row["name"]."\" href=\"torrents-details.php?id=$id&amp;hit=1\">$dispname</a> $freeleech</td>
				<td class='table_col1'>".mksize($row["size"])."</td>
				<td class='table_col2' align='center'>".date("j.M.Y<\\B\\R>H:i", utc_to_tz_time($row["added"]))."</td>
				<td class='table_col1' align='center'><a href=\"download.php?id=".$row["id"]."&amp;name=".rawurlencode($row["filename"])."\"><img src='images/icon_down.png' border='0' title=\"Download Torrent\" /></a></td>
				<td class='table_col2' align='center'><a href='comments.php?type=torrent&amp;id=$row[id]'>".number_format($row["comments"])."</a></td>
				<td class='table_col1' align='center'><font color='limegreen'>".number_format($row["seeders"])."</font></td>
				<td class='table_col2' align='center'><font color='red'>".number_format($row["leechers"])."</font></td>
				<td class='table_col1' align='center'><font color='darkorange'>".number_format($row["times_completed"])."</font></td>\n";
				
				if ($site_config["ALLOWEXTERNAL"]){
					if ($row["external"]=='yes')
						print("<td class='table_col2' align='center'><img src='images/t_extern.png' border='0' title='External&nbsp;Torrent'></td>\n");
					else
						print("<td class='table_col2' align='center'><img src='images/t_local.png' border='0' title='Local&nbsp;Torrent'></td>\n");
				}
				
				echo "<td class='table_col1' align='center'><a href=\"takedelbookmark.php?bookmarkid=".$row[id]."\"><img src=\"images/delete.png\" title=\"Delete\" border=\"0\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>";
		If ($count > $perpage) { print($pagerbottom); } else { print("<br />"); }
	}
	end_frame();
	stdfoot();
?>