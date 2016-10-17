<?php
//
//  TorrentTrader v3.0
//      $LastChangedDate: 2016-10-15 12:42:50 +0000 (Sat, 15 Oct 2016) $
//      $LastChangedBy: Meg4R0M $
//

require_once("backend/functions.php");
dbconn();
 
$action = $_POST["action"];

// MEMBER STATS
if ($action == "refreshMemberStats"){

    $userdownloaded = mksize($CURUSER["downloaded"]);
    $useruploaded = mksize($CURUSER["uploaded"]);
    $privacylevel = T_($CURUSER["privacy"]);

    if ($CURUSER["uploaded"] > 0 && $CURUSER["downloaded"] == 0)
        $userratio = "Inf.";
    elseif ($CURUSER["downloaded"] > 0)
        $userratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);
    else
        $userratio = "---";

    echo '<div id="ul_dl_stats" class="small">
        <b>Uploaded:</b> '.$useruploaded.' <br />
        <b>Downloaded:</b> '.$userdownloaded.'<br />
        <b>Buffer:</b> 0<br />
        <b>Ratio:</b> <span class="ratioNull">'.$userratio.'</span><br />
        <b>Max.Slots:</b> 3<br />
        <b>Points:</b> <a href="#">0</a><br />
        <b>Total Posts:</b> 0<br />
        <b>Total Invites:</b> <a href="#">0</a><br />
        <b>Total Warns:</b> <span id="total_warns" class="clickable">0</span><br />
        <b>Hit & Run Warns:</b> <span id="hitrun_warns" class="clickable">0</span><br />
    </div>';

// WEBSITE STATS
}elseif ($action == "refreshWebsiteStats"){

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

		echo '<p id="websiteStatsList" class="">
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
        </p>';
	}else{
		echo '<p id="websiteStatsList" class="">
            '.T_("WE_HAVE").': '.$registered.'<br />
            '.T_("TRACKING").': '.$ntor.'<br />
            '.T_("SEEDERS").': '.$seeders.'<br />
            '.T_("LEECHERS").': '.$leechers.'<br />
            Unseeded Torrents: '.$inactiventor.'<br />';
            //Total Threads: 1,040<br />
            //Total Replies: 3,744<br />
            echo T_("NEW_TODAY").': '.$regtoday.'<br />
            Welcome: '.$latestuser.'
        </p>';
	}
// ONLINE LIST
}elseif ($action == "refreshOnlineList"){
    $monli = "SELECT * FROM mostonline";
	$result = SQL_Query_exec($monli);
	$details = mysqli_fetch_array($result);

	if ($totalonline > $details['amount']){
		SQL_Query_exec("UPDATE mostonline SET amount = $totalonline");
		SQL_Query_exec("UPDATE mostonline SET date = now()");
	}

	$date1=date("D, d M Y H:i:s", strtotime($details['date']));
	$guests = number_format(getguests());
	$members = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 900"));

	$usersonlinequery = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, username, class FROM users WHERE privacy !='strong' AND UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 900") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

	while ($usersonlinerecord = mysqli_fetch_array($usersonlinequery) ) {
		$usersonlinerecords[] = $usersonlinerecord;
	}

    if ($usersonlinerecords == ""){
		echo "No members OnLine"; 
	}else{

        $countusers = count($usersonlinerecords);
        foreach ($usersonlinerecords as $user) {
			switch($user["class"]){
				case 1:
					$color = "#00FFFF";// user
					break;
				case 2:
					$color = "#FF7519";// power user
					break;
				case 3:
					$color = "#990099";// VIP
					break;
				case 4:
					$color = "#0000FF";// uploader
					break;
				case 5:
					$color = "#009900";//moderator
					break;
				case 6:
					$color = "#00FF00";//super moderator
					break;
				case 7:
					$color = "#FF0000";// you and most trusted 
					break;
			}
			for ($i = 0, $cnt = $countusers, $n = $cnt - 1; $i < $cnt; $i++) { 
				$row = &$rows[$i];
				echo '<span id="member_info" memberid="'.$user[id].'" class="clickable"><span style="color: '.$color.'; font-weight: bold;">'.class_user($user["username"]).'</span></span>'.($i < $n ? ", " : "");
			}
		}
		echo '<br />Online now: '.count($rows).' (Members: '.$members.', Guests: '.$guests.')';
	}
// LAST 24H ONLINE LIST
}elseif ($action == "refreshlast24OnlineList"){
    $monli2 = "SELECT * FROM mostonline";
	$result2 = SQL_Query_exec($monli2);
	$details2 = mysqli_fetch_array($result2);

	if ($totalonline > $details2['amount']){
		SQL_Query_exec("UPDATE mostonline SET amount = $totalonline");
		SQL_Query_exec("UPDATE mostonline SET date = now()");
	}

	$guests2 = number_format(getguests());
	$members2 = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 84600"));
	$total24on = $guests2 + $members2;


	$usersonlinequery2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, username, class FROM users WHERE privacy !='strong' AND UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 84600") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

	while ($usersonlinerecord2 = mysqli_fetch_array($usersonlinequery2) ) {
		$usersonlinerecords2[] = $usersonlinerecord2;
	}
    
	if ($usersonlinerecords2 == ""){
		echo "No members OnLine"; 
	}else{
        echo 'Total members that have visited today: '.$total24on;
		echo '<br />(Members: '.$members2.', Guests: '.$guests2.')<br />';
		$countusers2 = count($usersonlinerecords2);
		foreach ($usersonlinerecords2 as $user2) {
			switch($user2["class"]){
				case 1:
					$color = "#00FFFF";// user
					break;
				case 2:
					$color = "#FF7519";// power user
					break;
				case 3:
					$color = "#990099";// VIP
					break;
				case 4:
					$color = "#0000FF";// uploader
					break;
				case 5:
					$color = "#009900";//moderator
					break;
				case 6:
					$color = "#00FF00";//super moderator
					break;
				case 7:
					$color = "#FF0000";// you and most trusted 
					break;
			}
			for ($i2 = 0, $cnt2 = $countusers2, $n2 = $cnt2 - 1; $i2 < $cnt2; $i2++) { 
				$row2 = &$rows2[$i2];
				echo '<span id="member_info" memberid="'.$user2[id].'" class="clickable"><span style="color: '.$color.'; font-weight: bold;">'.class_user($user2["username"]).'</span></span>'.($i2 < $n2 ? ", " : "");
			}
		}
	}
}elseif ($action == "member_info"){
    $userinfoid = (int)$_POST["memberid"];
    if (!is_valid_id($userinfoid))
        show_error_msg(T_("NO_SHOW_DETAILS"), "Bad ID.",1);
    
    $userinfo = "SELECT * FROM users WHERE id=$userinfoid";
	$resultui = @SQL_Query_exec($userinfo);
	$detailsui = mysqli_fetch_array($resultui) or  show_error_msg(T_("NO_SHOW_DETAILS"), T_("NO_USER_WITH_ID")." $userinfoid.",1);
    
    // CHECK SOME RIGHTS
    if ($CURUSER["view_users"] == "no" && $CURUSER["id"] != $userinfoid)
        show_error_msg(T_("ERROR"), T_("NO_USER_VIEW"), 1); 
      
    if (($detailsui["enabled"] == "no" || ($detailsui["status"] == "pending")) && $CURUSER["edit_users"] == "no") 
        show_error_msg(T_("ERROR"), T_("NO_ACCESS_ACCOUNT_DISABLED"), 1); 
    //===| Start Blocked Users 
    $blocked = SQL_Query_exec("SELECT id FROM blocked WHERE userid=$detailsui[id] AND blockid=$CURUSER[id]"); 
    $show = mysqli_num_rows($blocked); 
    if ($show != 0 && $CURUSER["control_panel"] != "yes") 
        show_error_msg("Error", "<div style='margin-top:10px; margin-bottom:10px' align='center'><font size=2 color=#FF2000><b>You are blocked by this member and you can not view their profile!</b></font></div>", 1); 
    //===| End Blocked Users
    
    //$country
    $res = SQL_Query_exec("SELECT name, flagpic FROM countries WHERE id=$detailsui[country] LIMIT 1"); 
    if (mysqli_num_rows($res) == 1){ 
        $arr = mysqli_fetch_assoc($res); 
        $country = "$arr[name]";
        $countrypic = "$arr[flagpic]";
    }

    if (!$country){
        $country = "Unknown";
        $countrypic = "nc.gif";
    }
    
    $avatar = htmlspecialchars($detailsui["avatar"]);
    if (!$avatar)
        $avatar = "/images/default_avatar.png";

    //$moods 
    $res = SQL_Query_exec("SELECT name, moodspic FROM moods WHERE id=$detailsui[moods] LIMIT 1") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))); 
    $row = mysqli_fetch_assoc($res);
    $moods = ( $row ) ? "<img src='/images/moods/$row[moodspic]' alt='$row[moodspic]' title='$row[moodspic]' />" : 'Unknown';
    
    $userdownloaded = mksize($detailsui["downloaded"]);
    $useruploaded = mksize($detailsui["uploaded"]);
    //$ratio 
    if ($detailsui["downloaded"] > 0) { 
        $ratio = $detailsui["uploaded"] / $detailsui["downloaded"]; 
    }else{ 
        $ratio = "---"; 
    }

    $privacylevel = T_($detailsui["privacy"]);
    $usergender = $detailsui["gender"];
    
    if ($user["privacy"] != "strong" || ($CURUSER["control_panel"] == "yes") || ($CURUSER["id"] == $user["id"])) {
        echo '<div id="showMemberCard" style="z-index: 9999; top: 48px; left: 545.5px; position: fixed; display: block;">
            <a class="close" original-title=""></a>
            <div class="showMemberCardDetails">
                <div class="showMemberCardAvatar"><img src="'.$avatar.'" alt="" title=""></div>
                <div class="showMemberCardMemberInfo">
                    <div class="showMemberCardIcons">
                        <span class="showMemberCardStaffLinks" original-title=""> </span>
                    </div>
                    <p class="showMemberCardCountryFlag"><img src="/images/countryFlags/'.$countrypic.'" alt="'.$country.'" class="" id="" rel="resized_by_tsue" original-title="'.$country.'"></p>
                    <p><a href="/account-details.php?id='.$userinfoid.'" original-title=""><span class="membernameAdmin" original-title="">'.$detailsui["username"].'</span></a>, '.$detailsui["age"].' years old, '.$usergender.'</p>
                    <p class="showMemberCardGroupname"><span class="membernameAdmin" original-title="">'.get_user_class_name($detailsui["class"]).'</span></p>
                    <p>'.$moods.'</p>
                    <p><b>Member Since:</b> '.$detailsui["added"].'</p>
                    <p><b>Last Activity:</b> '.$detailsui["last_access"].'</p>
                    <p></p>
                    <p>
				        Uploaded: <span class="showMemberCardTextHighlight" original-title="">'.$useruploaded.'</span> | 
				        Downloaded: <span class="showMemberCardTextHighlight" original-title="">'.$userdownloaded.'</span> |  
				        Ratio: <span class="showMemberCardTextHighlight" original-title=""><span class="ratioGood" original-title="">'.$ratio.'</span></span> | 
				        Buffer <span class="showMemberCardTextHighlight" original-title="">1.09 MB</span> | 
				        Points <span class="showMemberCardTextHighlight" original-title="">6.530</span>
                    </p>
                    <div class="showMemberCardLinks">
				        <a href="/account-details.php?id='.$detailsui["id"].'" original-title="">'.$detailsui["username"].'\'s Profile</a>
				        <span class="clickable small" id="messages_new_message" receiver_membername="'.$detailsui["username"].'" original-title="">Send Message</span>
				        <span class="clickable small" id="follow_member" memberid="'.$userinfoid.'" inoverlay="yes" original-title="">Follow</span>
                    </div>
                </div>
            </div>
        </div>';
    }else{
        echo '<div id="showMemberCard" style="z-index: 9999; top: 48px; left: 545.5px; position: fixed; display: block;">
            <a class="close" original-title=""></a>
            <div class="showMemberCardDetails">
                <div class="showMemberCardAvatar"><img src="'.$avatar.'" alt="" title=""></div>
                <div class="showMemberCardMemberInfo">
                    <div class="showMemberCardIcons">
                        <span class="showMemberCardStaffLinks" original-title=""> </span>
                    </div>
                    <p class="showMemberCardCountryFlag"><img src="/images/countryFlags/'.$countrypic.'" alt="'.$country.'" class="" id="" rel="resized_by_tsue" original-title="'.$country.'"></p>
                    <p><span style="color: #6d6c6c; font-weight: bold;" original-title="">'.$detailsui["username"].'</span></p>
                    <p class="showMemberCardGroupname"><span style="color: #6d6c6c; font-weight: bold;" original-title="">'.get_user_class_name($detailsui["class"]).'</span></p>
                    <div class="error">This member limits who may view their profile.</div>
                    <div class="showMemberCardLinks">
                        <span class="clickable small" id="messages_new_message" receiver_membername="'.$detailsui["username"].'" original-title="">Send Message</span>
                    </div>
                </div>
            </div>
        </div>';
    }
}
?>