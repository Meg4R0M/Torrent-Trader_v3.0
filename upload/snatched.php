<?php
#================================#
#       TorrentTrader 2.08       #
#  http://www.torrenttrader.pw   #
#--------------------------------#
#       Modified by BigMax       #
#================================#

error_reporting(E_ALL ^ E_NOTICE);
error_reporting(1);
require_once('backend/functions.php');
dbconn();
loggedinonly();

$tid = ( int ) $_GET['tid'];
$uid = ( int ) $_GET['uid'];
$id = ( int ) $_GET['id'];

if ($id != 0)
	$uid = ( int ) $_GET['id'];
if ($tid == 0 && $uid == 0)
	$uid = ( int ) $CURUSER['id'];

if ($tid > 0) {
	$count_tid = get_row_count('snatched', 'WHERE `tid` = \'' . $tid . '\'');
	$type = "torrent";
	$count_uid = 0;
} else {
	$count_uid = get_row_count('snatched', 'WHERE `uid` = \'' . $uid . '\'');
	$type = "user";
	$count_tid = 0;
}

//===| for debug...
$debug = 0;
if ($debug)
{
	$title1 = sprintf(T_("SNATCHLIST_FOR"), " Debug");
	stdhead($title1);
	begin_frame($title1);

	$qry1 = "SELECT
            users.id,
            users.username,
            users.class,
            snatched.uid as uid,
            snatched.tid as tid,
            snatched.uload,
            snatched.dload,
            snatched.stime,
            snatched.utime,
            snatched.ltime,
            snatched.completed,
            completed.date,
            (
                SELECT seeder
                FROM peers
                WHERE torrent = tid AND userid = uid LIMIT 1
            ) AS seeding
            FROM
            snatched
            INNER JOIN users ON snatched.uid = users.id
            INNER JOIN torrents ON snatched.tid = torrents.id
            LEFT JOIN completed ON snatched.tid = completed.torrentid AND snatched.uid = completed.userid
            WHERE
            users.status = 'confirmed' AND
            torrents.banned = 'no' AND snatched.tid = '$tid' $limit";

	?>
	<div align="center"><b>Type: <?php echo $type;?></b></div>
	<div align="center"><b>Torrent ID: <?php echo $tid;?></b></div>
	<div align="center"><b>UserID: <?php echo $uid;?></b></div>
	<div align="center"><b>Torrent Count: <?php echo $count_tid;?></b></div>
	<div align="center"><b>User Count: <?php echo $count_uid;?></b></div>
	<div align="left"><b>Query: </b><?php echo $qry1;?></div>
	<?php
	end_frame();
}

if ($type == 'torrent')
{
	//===| Start Torrents Snatched
	if ($count_tid > 0)
	{
		$torrent = SQL_Query_exec("SELECT `name` FROM `torrents` WHERE `id` = '$tid'");
		$torrents = mysqli_fetch_row($torrent);
		$title = "".T_("REGISTERED_MEMBERS_TO_TORRENT")." ".htmlspecialchars($torrents[0])."";

		stdhead($title);
		begin_frame($title);

		$perpage = 50;
		list($pagertop, $pagerbottom, $limit) = pager($perpage, $count_tid, 'snatched.php?tid='.$tid.' &amp;');

		$qry = "SELECT
                users.id,
                users.username,
                users.class,
                snatched.uid as uid,
                snatched.tid as tid,
                snatched.uload,
                snatched.dload,
                snatched.stime,
                snatched.utime,
                snatched.ltime,
                snatched.completed,
                snatched.hnr,
                (
                    SELECT seeder
                    FROM peers
                    WHERE torrent = tid AND userid = uid LIMIT 1
                ) AS seeding
                FROM
                snatched
                INNER JOIN users ON snatched.uid = users.id
                INNER JOIN torrents ON snatched.tid = torrents.id
                WHERE
                users.status = 'confirmed' AND
                torrents.banned = 'no' AND snatched.tid = '$tid'
                ORDER BY stime DESC $limit";

		$res = SQL_Query_exec($qry);

		print("<div style='margin-top:10px; margin-bottom:5px'>[<a href=torrents-details.php?id=$tid><b>".T_("BACK_TO_TORRENT")."</b></a>]</div>");

		if ($count_tid > $perpage) { echo ($pagertop); }

		if (mysqli_num_rows($res) > 0):
			?>
			<table border="0" class="table_table" cellpadding="4" cellspacing="0" width="100%">
				<tr>
					<th class="table_head" align="left"><?php echo T_("USERNAME");?></th>
					<th class="table_head"><?php echo T_("UPLOADED");?></th>
					<th class="table_head"><?php echo T_("DOWNLOADED");?></th>
					<th class="table_head"><?php echo T_("RATIO");?></th>
					<th class="table_head"><?php echo T_("ADDED");?></th>
					<th class="table_head"><?php echo T_("LAST_ACTION");?></th>
					<th class="table_head"><img src="images/seedtime.png" border="0" title="<?php echo T_("SEED_TIME"); ?>"></th>
					<th class="table_head"><img src="images/check.png" border="0" title="<?php echo T_("COMPLETED");?>"></th>
					<th class="table_head"><img src="images/seed.gif" border="0" title="<?php echo T_("SEEDING");?>"></th>
					<th class="table_head"><?php echo T_("HNR");?></th>
				</tr>

				<?php
				while ($row = mysqli_fetch_row($res)):

					if ($row[6] > 0) { $ratio = number_format($row[5] / $row[6], 2); }
					else { $ratio = "---"; }
					$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";

					$startdate = utc_to_tz(get_date_time($row[7]));
					$lastaction = utc_to_tz(get_date_time($row[8]));

					if ($row[11] != "yes") { $hnr = "<font color=#27B500><b>".T_("NO")."</b></font>";  } else { $hnr = "<font color=#FF1200><b>".T_("YES")."</b></font>"; }
					if ($row[12] != "yes") { $seed = "<font color=#FF1200><b>".T_("NO")."</b></font>";  } else { $seed = "<font color=#27B500><b>".T_("YES")."</b></font>"; }
					?>

					<tr align="center">
						<td class="table_col1" align="left"><a href="account-details.php?id=<?php echo $row[0];?>"><?php echo "<b>".$row[1]."</b>";?></a></td>
						<td class="table_col2"><font color="#27B500"><?php echo mksize($row[5]);?></font></td>
						<td class="table_col1"><font color="#FF1200"><?php echo mksize($row[6]);?></font></td>
						<td class="table_col2"><?php echo $ratio;?></td>
						<td class="table_col2"><?php echo date('d.M.Y H:i', sql_timestamp_to_unix_timestamp($startdate));?></td>
						<td class="table_col1"><?php echo date('d.M.Y H:i', sql_timestamp_to_unix_timestamp($lastaction));?></td>
						<td class="table_col1"><?php echo ($row[9]) ? mkprettytime($row[9]) : '---';?></td>
						<td class="table_col2"><?php echo ($row[10]) ? "<font color=#0080FF><b>".T_("YES")."</b></font>" : "<b>".T_("NO")."</b>";?></td>
						<td class="table_col1"><?php echo $seed;?></td>
						<td class="table_col2"><?php echo $hnr;?></td>
					</tr>
					<?php
				endwhile;
				?>
			</table>
			<?php
			if ($count_tid > $perpage) { echo ($pagerbottom); }
			print("<div style='margin-top:5px; margin-bottom:10px' align='right'>[<a href=torrents-details.php?id=$tid><b>".T_("BACK_TO_TORRENT")."</b></a>]</div>");
		endif;
		end_frame();
		stdfoot();
		die;
	}
	else
	{
		if ($tid >= 1)
		{
			$torrent = SQL_Query_exec("SELECT `name` FROM `torrents` WHERE `id` = '$tid'");
			$torrents = mysqli_fetch_row($torrent);
			$ttitle = "".T_("SNATCHLIST_FOR")." ".htmlspecialchars($torrents[0])."";
			if ($torrents[0] == '') { $ttitle = "".T_("NO_TORRENT_WITH_ID")." $tid"; }

			stdhead($ttitle);
			begin_frame($ttitle);
			?>
			<div style="margin-top:10px; margin-bottom:10px" align="center"><font size="2"><?php echo T_("NOTHING_FOUND"); ?>.</font></div>
			<div style="margin-bottom:10px" align="center">[<?php echo "<a href=torrents-details.php?id=$tid>";?><b><?php echo T_("BACK_TO_TORRENT"); ?></b></a>]</div>
			<?php
			end_frame();
			stdfoot();
			die;
		}
	}
	//===| End Torrents Snatched
}
else
{
	//===| Start Users Snatched
	if ($count_uid > 0)
	{
		$user = SQL_Query_exec("SELECT `username` FROM `users` WHERE `id` = '$uid'");
		$users = mysqli_fetch_row($user);
		$title = "".T_("SNATCHLIST_FOR")." ".htmlspecialchars($users[0])."";
		$title2 = "".T_("SNATCHLIST_FOR")." ".$users[0]."";
		stdhead($title);
		begin_frame($title2);

		if (($CURUSER["control_panel"] == "no") && $CURUSER["id"] != $uid)
		{
			echo "<div style='margin-top:10px; margin-bottom:10px' align='center'><font size='2' color='red'><b>".T_("NO_PERMISSION")."</b></font></div>";
			end_frame();
			stdfoot();
			die;
		}

		$perpage = 50;
		list($pagertop, $pagerbottom, $limit) = pager($perpage, $count_uid, 'snatched.php?uid='.$uid.' &amp;');

		$qry = "SELECT
                snatched.tid as tid,
                torrents.name,                                      
                snatched.uload,
                snatched.dload,
                snatched.stime,
                snatched.utime,
                snatched.ltime,
                snatched.completed,
                snatched.hnr,
                (
                    SELECT seeder
                    FROM peers
                    WHERE torrent = tid AND userid = $uid LIMIT 1
                ) AS seeding
                FROM
                snatched
                INNER JOIN users ON snatched.uid = users.id
                INNER JOIN torrents ON snatched.tid = torrents.id
                WHERE
                users.status = 'confirmed' AND
                torrents.banned = 'no' AND snatched.uid = '$uid'
                ORDER BY stime DESC $limit";

		$res = SQL_Query_exec($qry);

		if ( $uid == $CURUSER[id] )
		{
			print("<div style='margin-top:20px; margin-bottom:20px' align='center'><font size='2'>".T_("SNATCHED_MESSAGE")."</font></div>");
		}

		if ( $uid != $CURUSER[id] )
			print("<div style='margin-top:10px; margin-bottom:5px'>[<a href=account-details.php?id=$uid><b>".T_("GO_TO_USER_ACCOUNT")."</b></a>]</div>");

		if ($count_uid > $perpage) { echo $pagertop; }

		if (mysqli_num_rows($res) > 0):
			?>
			<table border="0" class="table_table" cellpadding="4" cellspacing="0" width="100%">
				<tr>
					<th class="table_head" align="left"><?php echo T_("TORRENT_NAME"); ?></th>
					<?php if ($site_config["ALLOWEXTERNAL"]) { ?>
						<th class="table_head"><img src="images/t_le.png" border="0" title="<?php echo T_("T_L_OR_E"); ?>"></th>
					<?php } ?>
					<th class="table_head"><?php echo T_("UPLOADED"); ?></th>
					<th class="table_head"><?php echo T_("DOWNLOADED"); ?></th>
					<th class="table_head"><?php echo T_("RATIO"); ?></th>
					<th class="table_head"><?php echo T_("ADDED"); ?></th>
					<th class="table_head"><?php echo T_("LAST_ACTION"); ?></th>
					<th class="table_head"><img src="images/seedtime.png" border="0" title="<?php echo T_("SEED_TIME"); ?>"></th>
					<th class="table_head"><img src="images/check.png" border="0" title="<?php echo T_("COMPLETED"); ?>"></th>
					<th class="table_head"><img src="images/seed.gif" border="0" title="<?php echo T_("SEEDING"); ?>"></th>
					<th class="table_head"><?php echo T_("HNR"); ?></th>
				</tr>

				<?php
				while ($row = mysqli_fetch_row($res)):

					$startdate = utc_to_tz(get_date_time($row[4]));
					$lastaction = utc_to_tz(get_date_time($row[5]));

					$query = SQL_Query_exec("SELECT external, freeleech FROM torrents WHERE id = $row[0]");
					$result = mysqli_fetch_row($query);
					If ($result[0] == "yes") { $type = "<img src='images/t_extern.png' border='0' title='".T_("EXTERNAL_TORRENT")."'>"; }
					else { $type = "<img src='images/t_local.png' border='0' title='".T_("LOCAL_TORRENT")."'>"; }
					if ($result[1] == 1) { $freeleech = "<img src='images/free.gif' border='0' title='".T_("FREE")."'>"; }
					else { $freeleech = ""; }

					if ($row[3] > 0) { $ratio = number_format($row[2] / $row[3], 2); }
					else { $ratio = "---"; }
					$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";

					if ($row[8] != "yes") { $hnr = "<font color=#27B500><b>".T_("NO")."</b></font>";  } else { $hnr = "<font color=#FF1200><b>".T_("YES")."</b></font>"; }
					if ($row[9] != "yes") { $seed = "<font color=#FF1200><b>".T_("NO")."</b></font>";  } else { $seed = "<font color=#27B500><b>".T_("YES")."</b></font>"; }

					$maxchar = 30;    //===| cut name length
					$smallname = htmlspecialchars(CutName($row[1], $maxchar));
					?>
					<tr align="center">
						<?php echo("<td class='ttable_col1' align='left' nowrap='nowrap'>".(count($expandrows)?"<a href=\"javascript: klappe_torrent('t".$row['0']."')\"><img border=\"0\" src=\"".$site_config["SITEURL"]."/images/plus.gif\" id=\"pict".$row['0']."\" alt=\"Show/Hide\" class=\"showthecross\" /></a>":"")."<a title=\"".$row["1"]."\" href=\"torrents-details.php?id=".$row['0']."&amp;hit=1\"><b>$smallname</b></a> $freeleech</td>"); ?>
						<?php if ($site_config["ALLOWEXTERNAL"]) { ?>
							<td class="table_col2" align="center"><?php echo $type;?></td>
						<?php } ?>
						<td class="table_col1"><font color="#27B500"><?php echo mksize($row[2]);?></font></td>
						<td class="table_col2"><font color="#FF1200"><?php echo mksize($row[3]);?></font></td>
						<td class="table_col1"><?php echo $ratio;?></td>
						<td class="table_col2"><?php echo date('d.M.Y H:i', sql_timestamp_to_unix_timestamp($startdate));?></td>
						<td class="table_col1"><?php echo date('d.M.Y H:i', sql_timestamp_to_unix_timestamp($lastaction));?></td>
						<td class="table_col2"><?php echo ($row[6]) ? mkprettytime($row[6]) : '---';?></td>
						<td class="table_col1"><?php echo ($row[7]) ? "<font color=#0080FF><b>".T_("YES")."</b></font>" : "<b>".T_("NO")."</b>";?></td>
						<td class="table_col2"><?php echo $seed;?></td>
						<td class="table_col1"><?php echo $hnr;?></td>
					</tr>
					<?php
				endwhile;
				?>
			</table>
			<?php

			if ($count_uid > $perpage) { echo $pagerbottom; }

			if ( $uid != $CURUSER[id] )
				print("<div style='margin-top:5px; margin-bottom:10px' align='right'>[<a href=account-details.php?id=$uid><b>".T_("GO_TO_USER_ACCOUNT")."</b></a>]</div>");

		endif;
		end_frame();
		stdfoot();
		die;
	}
	else
	{
		$user = SQL_Query_exec("SELECT `username` FROM `users` WHERE `id` = '$uid'");
		$users = mysqli_fetch_row($user);

		$title = "".T_("SNATCHLIST_FOR")." ".htmlspecialchars($users[0])."";
		if ($users[0] == '') { $title = "".T_("NO_USER_WITH_ID")." $uid"; }

		$title2 = "".T_("SNATCHLIST_FOR")." ".$users[0]."";
		if ($users[0] == '') { $title2 = "".T_("NO_USER_WITH_ID")." $uid"; }

		stdhead($title);
		begin_frame($title2);

		if (($CURUSER["control_panel"] == "no") && $CURUSER["id"] != $uid)
		{
			echo "<div style='margin-top:10px; margin-bottom:10px' align='center'><font size='2' color='red'><b>".T_("NO_PERMISSION")."</b></font></div>";
			end_frame();
			stdfoot();
			die;
		}
		echo "<div style='margin-top:10px; margin-bottom:10px' align='center'><font size='2'>".T_("NOTHING_FOUND").".</font></div>";

		if (mysqli_num_rows($user) > 0)
		{
			if ( $uid != $CURUSER[id] ) {
				print("<div style='margin-bottom:10px' align='center'>[<a href=account-details.php?id=$uid><b>".T_("GO_TO_USER_ACCOUNT")."</b></a>]</div>");
			} else {
				print("<div style='margin-bottom:10px' align='center'>[<a href=account.php><b>".T_("GO_TO_YOUR_PROFILE")."</b></a>]</div>");
			}
		}
		end_frame();
		stdfoot();
	}
}
?> 