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
            '.T_("WE_HAVE").': '.$registered.' '.P_("MEMBER", $registered).'<br />
            '.T_("TRACKING").': '.$ntor.' '.P_("TORRENT", $ntor).'<br />
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

	$rows = array();
	while ($row = mysqli_fetch_assoc($usersonlinequery)) {
		$rows[] = $row;
	}

	if (!$rows){
		echo "No members OnLine";
	}else{
		$countusers = count($rows);
		for ($i = 0, $cnt = $countusers, $n = $cnt - 1; $i < $cnt; $i++) {
			$row = &$rows[$i];
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
			echo '<span id="member_info" memberid="'.$row[id].'" class="clickable"><span style="color: '.$color.'; font-weight: bold;">'.class_user($row["username"]).'</span></span>'.($i < $n ? ", " : "");
		}
		echo '<br />Online now: '.$countusers.' (Members: '.$members.', Guests: '.$guests.')';
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

	while ($row = mysqli_fetch_assoc($usersonlinequery2)) {
		$rows[] = $row;
	}

	$countusers2 = count(mysqli_fetch_array($usersonlinequery2));

	if (!$rows){
		echo "No members OnLine";
	}else {
		echo 'Total members that have visited today: ' . $total24on;
		echo '<br />(Members: ' . $members2 . ', Guests: ' . $guests2 . ')<br />';

		for ($i = 0, $cnt = count($rows), $n = $cnt - 1; $i < $cnt; $i++) {
			$row = &$rows[$i];
			switch ($row["class"]) {
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
			echo '<span id="member_info" memberid="' . $row["id"] . '" class="clickable"><span style="color: ' . $color . '; font-weight: bold;">' . class_user($row["username"]) . '</span></span>' . ($i < $n ? ", " : "");
		}
	}
}elseif ($action == "member_info") {
    $userinfoid = (int)$_POST["memberid"];
    if (!is_valid_id($userinfoid))
        show_error_msg(T_("NO_SHOW_DETAILS"), "Bad ID.", 1);

    $userinfo = "SELECT * FROM users WHERE id=$userinfoid";
    $resultui = @SQL_Query_exec($userinfo);
    $detailsui = mysqli_fetch_array($resultui) or show_error_msg(T_("NO_SHOW_DETAILS"), T_("NO_USER_WITH_ID") . " $userinfoid.", 1);

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
    if (mysqli_num_rows($res) == 1) {
        $arr = mysqli_fetch_assoc($res);
        $country = "$arr[name]";
        $countrypic = "$arr[flagpic]";
    }

    if (!$country) {
        $country = "Unknown";
        $countrypic = "nc.gif";
    }

    if ($detailsui["gender"] == "m")
        $gender = "Male";
    elseif ($detailsui["gender"] == "f")
        $gender = "Female";
    else
        $gender = "Unspecified";

    $avatar = htmlspecialchars($detailsui["avatar"]);
    if (!$avatar) {
        if ($gender == "Male")
            $avatar = "/themes/default/avatars/avatar_m_l.png";
        elseif ($gender == "Female")
            $avatar = "/themes/default/avatars/avatar_f_l.png";
        else
            $avatar = "/themes/default/avatars/avatar_l.png";
    }

    $userdownloaded = mksize($detailsui["downloaded"]);
    $useruploaded = mksize($detailsui["uploaded"]);
    //$ratio 
    if ($detailsui["downloaded"] > 0) {
        $ratio = $detailsui["uploaded"] / $detailsui["downloaded"];
    } else {
        $ratio = "---";
    }

    $privacylevel = T_($detailsui["privacy"]);
    $age = Age($detailsui["age"]);

    if ($detailsui["class"] == "7")
        $spanclass =  'class="membernameAdmin"';
    elseif ($detailsui["class"] == "3")
        $spanclass = 'class="membernameVIP"';
    else
        $spanclass =  '';

    $lres = SQL_Query_exec("SELECT Color FROM groups WHERE group_id=$detailsui[class]");
    $larr = mysqli_fetch_assoc($lres);

    if ($detailsui["privacy"] == "strong" && $CURUSER["class"] <= "6" && $CURUSER["id"] != $detailsui["id"]) {
        echo '<div id="showMemberCard" style="z-index: 9999; top: 48px; left: 545.5px; position: fixed; display: block;">
            <a class="close" original-title=""></a>
            <div class="showMemberCardDetails">
                <div class="showMemberCardAvatar"><img src="' . $avatar . '" alt="" title=""></div>
                <div class="showMemberCardMemberInfo">
                    <div class="showMemberCardIcons">
                        <span class="showMemberCardStaffLinks" original-title=""> </span>
                    </div>
                    <p class="showMemberCardCountryFlag"><img src="/images/countryFlags/' . $countrypic . '" alt="' . $country . '" class="" id="" rel="resized_by_tsue" original-title="' . $country . '"></p>
                    <p><span '.$spanclass.' original-title="" style="color: '.$larr["Color"].'; font-weight: bold;">' . $detailsui["username"] . '</span></p>
                    <p class="showMemberCardGroupname"><span '.$spanclass.' original-title="" style="color: '.$larr["Color"].'; font-weight: bold;">' . get_user_class_name($detailsui["class"]) . '</span></p>
                    <div class="error">This member limits who may view their profile.</div>
                    <div class="showMemberCardLinks">
                        <span class="clickable small" id="messages_new_message" receiver_membername="' . $detailsui["username"] . '" original-title="">Send Message</span>
                    </div>
                </div>
            </div>
        </div>';
    } else {
        echo '<div id="showMemberCard" style="z-index: 9999; top: 48px; left: 545.5px; position: fixed; display: block;">
            <a class="close" original-title=""></a>
            <div class="showMemberCardDetails">
                <div class="showMemberCardAvatar"><img src="' . $avatar . '" alt="" title=""></div>
                <div class="showMemberCardMemberInfo">
                    <div class="showMemberCardIcons">
                        <span class="showMemberCardStaffLinks" original-title=""> </span>';
                        if ($detailsui["banned"] == "yes") {
                            echo '<img src="/themes/default/member_profile/banned.png" alt="Banned" class="middle" original-title="Banned" rel="resized_by_tsue">';
                        }elseif ($detailsui["forumbanned"] == "yes"){
                            echo '<img src="/themes/default/member_profile/muted.png" alt="Muted in Comments<br />Muted in Forums<br />Muted in Shoutbox<br />Muted in Private Messages" title="Muted in Comments<br />Muted in Forums<br />Muted in Shoutbox<br />Muted in Private Messages" class="middle">';
                        }
                    echo '</div>
                    <p class="showMemberCardCountryFlag"><img src="/images/countryFlags/' . $countrypic . '" alt="' . $country . '" class="" id="" rel="resized_by_tsue" original-title="' . $country . '"></p>
                    <p><a href="/account-details.php?id=' . $userinfoid . '" original-title=""><span '.$spanclass.' original-title="" style="color: '.$larr["Color"].'; font-weight: bold;">' . $detailsui["username"] . '</span></a>, ' . $age . ' years old, ' . $gender . '</p>
                    <p class="showMemberCardGroupname"><span '.$spanclass.' original-title="" style="color: '.$larr["Color"].'; font-weight: bold;">' . get_user_class_name($detailsui["class"]) . '</span></p>
                    <p><b>Member Since:</b> ' . $detailsui["added"] . '</p>
                    <p><b>Last Activity:</b> ' . $detailsui["last_access"] . '</p>
                    <p></p>
                    <p>
				        Uploaded: <span class="showMemberCardTextHighlight" original-title="">' . $useruploaded . '</span> | 
				        Downloaded: <span class="showMemberCardTextHighlight" original-title="">' . $userdownloaded . '</span> |  
				        Ratio: <span class="showMemberCardTextHighlight" original-title=""><span class="ratioGood" original-title="">' . $ratio . '</span></span> | 
				        Buffer <span class="showMemberCardTextHighlight" original-title="">1.09 MB</span> | 
				        Points <span class="showMemberCardTextHighlight" original-title="">6.530</span>
                    </p>
                    <div class="showMemberCardLinks">
				        <a href="/account-details.php?id=' . $detailsui["id"] . '" original-title="">' . $detailsui["username"] . '\'s Profile</a>
				        <span class="clickable small" id="messages_new_message" receiver_membername="' . $detailsui["username"] . '" original-title="">Send Message</span>
				        <span class="clickable small" id="follow_member" memberid="' . $userinfoid . '" inoverlay="yes" original-title="">Follow</span>
                    </div>
                </div>
            </div>
        </div>';
    }
}elseif ($action == "edit_shout"){

    $msgid = (int)$_POST["msgid"];
    $res = SQL_Query_exec("SELECT * FROM shoutbox WHERE id=".$msgid);
    if (mysqli_num_rows($res) != 1) {
        print("No message with ID $msgid.");
        exit;
    }
    $arr = mysqli_fetch_assoc($res);
    if ($CURUSER["id"] != $arr["uid"] && $CURUSER["edit_users"]=="no") {
        print("Nope !");
        exit;
    }
    echo '<div id="showMemberCard" style="z-index: 9999; top: 48px; left: 545.5px; position: fixed; display: block;">
        <a class="close" original-title=""></a>
        <div class="showMemberCardDetails" align="center">';
            $save = (int)$_POST["save"];
            echo '<span style="font-weight: bold; color: red;">..:: Shout Edit ::..</span>
            <form name="chatForm" method="post" action="chatedit.php?action=edit&save=1&msgid='.$msgid.'">
                <table border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td></td>
                        <td charset="UTF-8" style="padding: 0px"><textarea name="message" id="message" cols="50" rows="5" >'.htmlspecialchars($arr["text"]).'</textarea></td>
                    </tr>
                    <br />
                    <tr>
                        <td align="center" colspan="4"><input type="submit" value="Confirmer" class="btn"></td>
                    </tr>
                    <br/>
                </table>
            </form>';
        echo '</div>
    </div>';
}elseif ($action == "del_shout"){

    if (is_numeric($_POST['msgid'])){
        $query = "SELECT * FROM shoutbox WHERE id=".$_POST['msgid'] ;
        $result = SQL_Query_exec($query);
    }else{
        echo "invalid msg id STOP TRYING TO INJECT SQL";
        exit;
    }

    $row = mysqli_fetch_row($result);

    if ($CURUSER["id"] != $row["uid"]) {
        $query = "DELETE FROM shoutbox WHERE id=" . $_POST['msgid'];

        write_log("<b><font color=orange>Shout Delete: </font> " . $row["text"] . " by " . $row["name"] . " - Deleted by " . $CURUSER['username'] . "</b>");
        SQL_Query_exec($query);
    }

}elseif ($action == "moresmilies"){

    echo '<div id="showMemberCard" style="z-index: 9999; top: 48px; left: 545.5px; position: fixed; display: block;">
        <a class="close" original-title=""></a>
        <div class="showMemberCardDetails" align="center">';

            require_once("backend/smilies.php");

            echo '<table class="smile_table" width="100%" cellpadding="0" cellspacing="1">
                <tr>';

                    while ((list($code, $url) = each($smilies))) {
                        if ($count % 5==0)
                            echo '<tr>';

                        echo '<td class="smilies" align="center"><img border="0" src="images/smilies/'.$url.'"></td>
                         <td class="smilies" align="center" style="color: white;">'.$code.'</td>';
                        $count++;

                        if ($count % 5==0)
                            echo '</tr>';
                    }

        echo '</table>';
    echo '</div>
    </div>';

}elseif ($action == "view_unread_messages"){
    require_once("mailbox-functions.php");

    echo '<div class="atext">
        <div class="" header="Messages">
	        <div class="message" id="show_all_messages">
        	    <span class="floatright" original-title="">
		            <input name="messages_delete_messages" value="Delete Selected Messages" id="messages_delete_messages" class="submit" type="button">
		            <input name="messages_select_all" value="Select All" id="messages_select_all" class="submit" type="button">
	            </span>

            	<input name="messages_new_message" value="New Message" id="messages_new_message" class="submit" type="button">
	            <input name="messages_view_all" value="See All Messages" id="messages_view_all" class="submit" type="button">';

                $where = "`receiver` = $CURUSER[id] AND `location` IN ('in','both')";
                $order = order("added,sender,sendto,subject", "added", true);
                $res = SQL_Query_exec("SELECT * FROM messages WHERE $where $order LIMIT 10");
                while ($arr = mysqli_fetch_assoc($res)) {
                    if ($arr["sender"] == $CURUSER['id']) {
                        $sender = "Yourself";
                        $avatar = htmlspecialchars($CURUSER["avatar"]);
                    }elseif (is_valid_id($arr["sender"])) {
                        $res2 = SQL_Query_exec("SELECT username, class, avatar FROM users WHERE `id` = $arr[sender]");
                        $arr2 = mysqli_fetch_assoc($res2);
                        $avatar = htmlspecialchars($arr2["avatar"]);
                        switch($arr2["class"]){
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
                        if ($arr2["username"])
                            $sender = '<span id="member_info" memberid="'.$arr[sender].'" class="clickable" original-title=""><span style="color: '.$color.'; font-weight: bold;" original-title="">'.$arr2["username"].'</span></span>';
                        else
                            $sender = '<span id="member_info" original-title=""><span style="color: #6d6c6c; font-weight: bold;" original-title="">[Deleted]</span></span>';
                    }else
                        $sender = '<span id="member_info" original-title=""><span style="color: #6d6c6c; font-weight: bold;" original-title="">'.T_("SYSTEM").'</span></span>';

                    if (!$avatar)
                        $avatar = "/images/default_avatar.png";

                    if ($arr["receiver"] == $CURUSER['id'])
                        $sentto = "Yourself";
                    elseif (is_valid_id($arr["receiver"])){
                        $res2 = SQL_Query_exec("SELECT username FROM users WHERE `id` = $arr[receiver]");
                        $arr2 = mysqli_fetch_assoc($res2);
                        $sentto = "<a href=\"account-details.php?id=$arr[receiver]\">" . ($arr2["username"] ? $arr2["username"] : "[Deleted]") . "</a>";
                    } else
                        $sentto = T_("SYSTEM");

                    $subject = ($arr['subject'] ? htmlspecialchars($arr['subject']) : "no subject");

                    SQL_Query_exec("UPDATE messages SET `unread` = 'no' WHERE `id` = $arr[id] AND `receiver` = $CURUSER[id]");

                    if ($arr["unread"] == "yes") {
                        $format = "font-weight:bold;";
                        $unread = true;
                    }
                    echo '<div id="show_member_messages">
                        <div id="show_message_'.$arr[id].'" class="comment-box">
	                        <img src="'.$avatar.'" alt="" title="" class="clickable avatar" id="member_info" memberid="'.$arr2["id"].'">
                            <div class="floatright textAlignCenter">
                                <label><input name="deleteMessages[]" value="'.$arr[id].'" id="deleteMessages" type="checkbox"></label>
                            </div>
                        	<div>'.$sender.'</div>
	                        <div><a href="http://ttv3.mavitrine.ovh/mailbox.php?inbox&amp;message_id='.$arr[id].'" original-title="">'.$subject.'</a></div>
	                        <div class="smalldate">'.utc_to_tz($arr["added"]).'</div>
	                    </div>
	                </div>';
                }
            echo '</div>
        </div>
    </div>';
}
?>