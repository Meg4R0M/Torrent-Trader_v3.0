<?php
//
//  TorrentTrader v3.0
//      $LastChangedDate: 2016-10-15 12:41:50 +0000 (Sat, 15 Oct 2016) $
//      $LastChangedBy: Meg4R0M $
//

if ($_SERVER['REQUEST_URI'] == "/index.php"){
	echo '<div class="widget">
        <h4>
            <span class="floatright">
                <img src="/themes/default/buttons/refresh.png" alt="Refresh" title="Refresh" rel="refreshlast24OnlineMembers" class="clickable middle" />
            </span>
            <img src="/themes/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="last24ActiveMembers" id="toggle" class="middle pointer" /> <a href="/membersonline.php?last24=1">Last 24 Hours Active Members</a>
        </h4>';

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

        echo '<div id="last24ActiveMembers" class="">
            <p id="last24onlineMembersList">';
    
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
            echo '</p>
            <p>
                <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #FF0000" title="Administrators">&nbsp;</span>
                <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #00FF00" title="Super Moderators">&nbsp;</span> 
                <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #009900" title="Moderators">&nbsp;</span> 
                <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #0000FF" title="Uploaders">&nbsp;</span> 
                <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #990099" title="VIP">&nbsp;</span> 
                <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #FF7519" title="Power user">&nbsp;</span> 
                <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #00FFFF" title="Registered Users">&nbsp;</span> 
                <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #cccccc" title="Members Awaiting Email Confirmation">&nbsp;</span> 
                <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #cccccc" title="Members Awaiting Moderation">&nbsp;</span>
            </p>
        </div>
    </div>';
}
?>