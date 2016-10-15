<?php
//
//  TorrentTrader v3.0
//      $LastChangedDate: 2016-10-14 22:19:50 +0000 (Fri, 14 Oct 2016) $
//      $LastChangedBy: Meg4R0M $
//

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

		echo '<div class="widget">
            <h4>
                <span class="floatright">
                    <img src="/themes/default/buttons/refresh.png" alt="Refresh" title="Refresh" rel="refreshWebsiteStats" class="clickable middle" />
                </span>
                <img src="/themes/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="websiteStatsList" id="toggle" class="middle pointer" /> Website Statistics
            </h4>
            <p id="websiteStatsList" class="">
                <b>---'.T_("TORRENTS").'---</b><br />
				'.T_("TRACKING").': '.$ntor.' '.P_("TORRENT", $ntor).'<br />
				'.T_("NEW_TODAY").': '.$todaytor.'<br />
				Unseeded Torrents: '.$inactiventor.'<br />
				'.T_("SEEDERS").': '.number_format($seeders).'<br />
				'.T_("LEECHERS").': '.number_format($leechers).'<br />
				'.T_("PEERS").': '.number_format($localpeers).'<br />
				'.T_("DOWNLOADED").': '.mksize($totaldownloaded).'<br />
				'.T_("UPLOADED").': '.mksize($totaluploaded).'<br />
                <b>---'.T_("MEMBERS").'---</b><br />
				'.T_("WE_HAVE").': '.$registered.' '.P_("MEMBER", $registered).'<br />
				'.T_("NEW_TODAY").': '.$regtoday.'<br />
				'.T_("VISITORS_TODAY").': '.$totaltoday.'<br />
				Welcome: '.$latestuser.'<br />
                <b>---Misc---</b><br />
				'.T_("COMMENTS_POSTED").': '.$ncomments.'<br />
				'.T_("MESSAGES_SENT").': '.$nmessages.'
            </p>
        </div>';
	}else{
		echo '<div class="widget">
            <h4>
                <span class="floatright">
                    <img src="/themes/default/buttons/refresh.png" alt="Refresh" title="Refresh" rel="refreshWebsiteStats" class="clickable middle" />
                </span>
                <img src="/themes/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="websiteStatsList" id="toggle" class="middle pointer" /> Website Statistics
            </h4>
            <p id="websiteStatsList" class="">
                '.T_("WE_HAVE").': '.$registered.'<br />
                '.T_("TRACKING").': '.$ntor.'<br />
                '.T_("SEEDERS").': '.$seeders.'<br />
                '.T_("LEECHERS").': '.$leechers.'<br />
                Unseeded Torrents: '.$inactiventor.'<br />';
                //Total Threads: 1,040<br />
                //Total Replies: 3,744<br />
                echo T_("NEW_TODAY").': '.$regtoday.'<br />
                Welcome: '.$latestuser.'
            </p>
        </div>';
	}
}
?> 
