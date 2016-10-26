<?php
//
//  TorrentTrader v3.0
//      $LastChangedDate: 2016-10-15 12:41:50 +0000 (Sat, 15 Oct 2016) $
//      $LastChangedBy: Meg4R0M $
//
if (strpos($_SERVER['REQUEST_URI'], '?') !== false){
    $scripturl =  substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
}else {
    $scripturl = $_SERVER['REQUEST_URI'];
}

if ($scripturl != "/membercp.php" && $scripturl != "/forums.php"){
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
        $rows = array();
        while ($row = mysqli_fetch_assoc($usersonlinequery2)) {
            $rows[] = $row;
        }

        $countusers2 = count(mysqli_fetch_array($usersonlinequery2));


        echo '<div id="last24ActiveMembers" class="">
            <p id="last24onlineMembersList">';
    
                if (!$rows){
                    echo "No members OnLine";
                }else{
                    echo 'Total members that have visited today: '.$total24on;
                    echo '<br />(Members: '.$members2.', Guests: '.$guests2.')<br />';

                    for ($i = 0, $cnt = count($rows), $n = $cnt - 1; $i < $cnt; $i++) {
                        $row = &$rows[$i];
                        switch($row["class"]){
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