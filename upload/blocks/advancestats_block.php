<?php
if ($_SERVER['REQUEST_URI'] == "/index.php"){
	$date_time = get_date_time(gmtime()-(3600*24)); // the 24hrs is the hours you want listed
	$registered = number_format(get_row_count("users"));
	$ntor = number_format(get_row_count("torrents"));
	$inactiventor = number_format(get_row_count("torrents", "WHERE torrents.seeders='0'"));
	$todaytor = number_format(get_row_count("torrents", "WHERE torrents.added>='$date_time'"));
	$seeders = get_row_count("peers", "WHERE seeder='yes'");
	$leechers = get_row_count("peers", "WHERE seeder='no'");

	$a = @mysqli_fetch_assoc(@mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id,username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1"));

	if ($CURUSER)
		$latestuser = '<span id="member_info" memberid="'.$a["id"].'" class="clickable"><span style="color: #6d6c6c; font-weight: bold;">'.$a["username"].'</span></span>';
	else
		$latestuser = '<span style="color: #6d6c6c; font-weight: bold;">'.$a["username"].'</span>';

	if($CURUSER["edit_users"]=="yes") {
		$ncomments = number_format(get_row_count("comments"));
		$nmessages = number_format(get_row_count("messages"));
		$totaltoday = number_format(get_row_count("users", "WHERE users.last_access>='$date_time'"));
		$regtoday = number_format(get_row_count("users", "WHERE users.added>='$date_time'"));

		$result = SQL_Query_exec("SELECT SUM(downloaded) AS totaldl FROM users"); 
		while ($row = mysqli_fetch_array($result)) { 
			$totaldownloaded = $row["totaldl"]; 
		} 

		$result = SQL_Query_exec("SELECT SUM(uploaded) AS totalul FROM users"); 
		while ($row = mysqli_fetch_array($result)) { 
			$totaluploaded      = $row["totalul"]; 
		}
		$localpeers = $leechers+$seeders;

		begin_block(T_("STATS"));
		echo "<div id='websiteStatsList' style='margin:10px;'>";
			echo '<b>---'.T_("TORRENTS").'---</b>
			<p class="small">
				<b>'.T_("TRACKING").':</b> '.$ntor.' '.P_("TORRENT", $ntor).'<br />
				<b>'.T_("NEW_TODAY").':</b> '.$todaytor.'<br />
				<b>Unseeded Torrents:</b> '.$inactiventor.'<br />
				<b>'.T_("SEEDERS").':</b> '.number_format($seeders).'<br />
				<b>'.T_("LEECHERS").':</b> '.number_format($leechers).'<br />
				<b>'.T_("PEERS").':</b> '.number_format($localpeers).'<br />
				<b>'.T_("DOWNLOADED").':</b> '.mksize($totaldownloaded).'<br />
				<b>'.T_("UPLOADED").':</b> '.mksize($totaluploaded).'
			</p>
			<b>---'.T_("MEMBERS").'---</b>
			<p class="small">
				<b>'.T_("WE_HAVE").':</b> '.$registered.' '.P_("MEMBER", $registered).'<br />
				<b>'.T_("NEW_TODAY").':</b> '.$regtoday.'<br />
				<b>'.T_("VISITORS_TODAY").':</b> '.$totaltoday.'<br />
				<b>Welcome:</b> '.$latestuser.'
			</p>
			<b>---Misc---</b>
			<p class="small">
				<b>'.T_("COMMENTS_POSTED").':</b> '.$ncomments.'<br />
				<b>'.T_("MESSAGES_SENT").':</b> '.$nmessages.'
			</p>
		</div>';
		end_block();
	}else{
		begin_block(T_("STATS"));
		echo '<div id="websiteStatsList" style="margin:10px;">
			<b>'.T_("WE_HAVE").':</b> '.$registered.'<br />
			<b>'.T_("TRACKING").':</b> '.$ntor.'<br />
			<b>'.T_("SEEDERS").':</b> '.$seeders.'<br />
			<b>'.T_("LEECHERS").':</b> '.$leechers.'<br />
			<b>Unseeded Torrents:</b> '.$inactiventor.'<br />';
			//Total Threads: 1,040<br />
			//Total Replies: 3,744<br />
			echo '<b>'.T_("NEW_TODAY").':</b> '.$regtoday.'';
			echo "<b>Welcome:</b> $latestuser";
		echo '</div>';
		end_block();
	}
}
?> 
