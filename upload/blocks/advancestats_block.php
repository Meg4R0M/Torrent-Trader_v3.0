<?php
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
	$guests = number_format(getguests());
	$members = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 900"));
	$totalonline = $members + $guests;

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
	echo "<p id='websiteStatsList' class=''>";
		echo '---'.T_("TORRENTS").'---<br />
		'.T_("TRACKING").': '.$ntor.' '.P_("TORRENT", $ntor).'<br />
		'.T_("NEW_TODAY").': '.$todaytor.'<br />
		Unseeded Torrents: '.$inactiventor.'<br />
		'.T_("SEEDERS").': '.number_format($seeders).'<br />
		'.T_("LEECHERS").': '.number_format($leechers).'<br />
		'.T_("PEERS").': '.number_format($localpeers).'<br />
		'.T_("DOWNLOADED").': '.mksize($totaldownloaded).'<br />
		'.T_("UPLOADED").': '.mksize($totaluploaded).'<br /><br />
		---'.T_("MEMBERS").'---<br />
		'.T_("WE_HAVE").': '.$registered.' '.P_("MEMBER", $registered).'<br />
		'.T_("NEW_TODAY").': '.$regtoday.'<br />
		'.T_("VISITORS_TODAY").': '.$totaltoday.'<br />
		Welcome: '.$latestuser.'<br /><br />
		---'.T_("ONLINE").'---<br />
		'.T_("TOTAL_ONLINE").': '.$totalonline.'<br />
		'.T_("MEMBERS").': '.$members.'<br />
		'.T_("GUESTS_ONLINE").': '.$guests.'<br />
		'.T_("COMMENTS_POSTED").': '.$ncomments.'<br />
		'.T_("MESSAGES_SENT").': '.$nmessages.'
	</p>';
	end_block();
}else{
	begin_block(T_("STATS"));
	echo '<p id="websiteStatsList" class="">
		'.T_("WE_HAVE").': '.$registered.'<br />
		'.T_("TRACKING").': '.$ntor.'<br />
		'.T_("SEEDERS").': '.$seeders.'<br />
		'.T_("LEECHERS").': '.$leechers.'<br />
		Unseeded Torrents: '.$inactiventor.'<br />';
		//Total Threads: 1,040<br />
		//Total Replies: 3,744<br />
		echo T_("NEW_TODAY").': '.$regtoday.'';
		echo "Welcome to our newest members: $latestuser";
	echo '</p>';
	end_block();
}
?> 
