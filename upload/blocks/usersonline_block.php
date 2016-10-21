<?php
//
//  TorrentTrader v3.x
//      $LastChangedDate: 2016-10-21 14:55:35 +0100 (Fri, 21 Oct 2016) $
//      $LastChangedBy: Meg4R0M $
//

if (strpos($_SERVER['REQUEST_URI'], '?') !== false){
    $scripturl =  substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
}else{
    $scripturl = $_SERVER['REQUEST_URI'];
}

if ($scripturl != "/membercp.php" || $scripturl != "/forums.php"){
    echo '<div class="widget">
        <h4>
            <span class="floatright">
                <img src="/themes/default/buttons/refresh.png" alt="Refresh" title="Refresh" rel="refreshOnlineMembers" class="clickable middle" />
            </span>
            <img src="/themes/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="membersOnlineNow" id="toggle" class="middle pointer" />  <a href="/membersonline.php">Members Online Now</a>
        </h4>'; 
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

        echo '<div id="membersOnlineNow" class="">
            <p id="onlineMembersList">';
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

                    echo '<br />Online now: '.count($rows).' (Members: '.$members.', Guests: '.$guests.')
                </p>
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
            </div>';
	       }

    echo '</div>';
}
?>