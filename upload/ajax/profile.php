<?php
require_once("../backend/functions.php");
dbconn();

$action = $_POST["action"];
if (isset($_POST["memberid"])){
    $id = (int)$_POST["memberid"];

    if (!is_valid_id($id))
        show_error_msg(T_("NO_SHOW_DETAILS"), "Bad ID.",1); 

    $r = @SQL_Query_exec("SELECT * FROM users WHERE id=$id"); 
    $user = mysqli_fetch_array($r) or show_error_msg(T_("NO_SHOW_DETAILS"), T_("NO_USER_WITH_ID")." $id.",1); 

    //add invites check here 

    if ($CURUSER["view_users"] == "no" && $CURUSER["id"] != $id) 
        show_error_msg(T_("ERROR"), T_("NO_USER_VIEW"), 1); 

    if (($user["enabled"] == "no" || ($user["status"] == "pending")) && $CURUSER["edit_users"] == "no") 
        show_error_msg(T_("ERROR"), T_("NO_ACCESS_ACCOUNT_DISABLED"), 1); 

    //===| Start Blocked Users 
    $blocked = SQL_Query_exec("SELECT id FROM blocked WHERE userid=$user[id] AND blockid=$CURUSER[id]"); 
    $show = mysqli_num_rows($blocked); 
    if ($show != 0 && $CURUSER["control_panel"] != "yes") 
        show_error_msg("Error", "<div style='margin-top:10px; margin-bottom:10px' align='center'><font size=2 color=#FF2000><b>You are blocked by this member and you can not view their profile!</b></font></div>", 1); 
    //===| End Blocked Users
}

if ($action == "recent_activity") {
    echo '<div class="cReply">
        <img src="http://templateshares-ue.net/tsue/data/avatars/s/611.png?1476689389" alt="" title="" class="clickable avatar" id="member_info" memberid="611" rel="resized_by_tsue">
        <div class="cMessage">
            <span style="color: #6d6c6c; font-weight: bold;" original-title="">07BRN91</span>
            replied to the thread "<a href="http://templateshares-ue.net/tsue/?p=forums&amp;pid=11&amp;fid=8&amp;tid=2268&amp;postid=12491" original-title="">Themes</a>" in forum 
            <a href="http://templateshares-ue.net/tsue/?p=forums&amp;pid=11&amp;fid=8" original-title="">General Discussion and Feedback</a>.
        </div>
        <div class="replyDate">01-06-2013 23:13</div>
        <div class="clear"></div>
    </div>
    <div class="cReply">
        <img src="http://templateshares-ue.net/tsue/data/avatars/s/611.png?1476689389" alt="" title="" class="clickable avatar" id="member_info" memberid="611" rel="resized_by_tsue">
        <div class="cMessage">
            <span style="color: #6d6c6c; font-weight: bold;" original-title="">07BRN91</span> 
            replied to the thread "<a href="http://templateshares-ue.net/tsue/?p=forums&amp;pid=11&amp;fid=8&amp;tid=2268&amp;postid=12489" original-title="">Themes</a>" in forum 
            <a href="http://templateshares-ue.net/tsue/?p=forums&amp;pid=11&amp;fid=8" original-title="">General Discussion and Feedback</a>.
        </div>
        <div class="replyDate">01-06-2013 23:05</div>
        <div class="clear"></div>
    </div>
    <div class="cReply">
        <img src="http://templateshares-ue.net/tsue/data/avatars/s/611.png?1476689389" alt="" title="" class="clickable avatar" id="member_info" memberid="611" rel="resized_by_tsue">
        <div class="cMessage">
            <span style="color: #6d6c6c; font-weight: bold;" original-title="">07BRN91</span> 
            created a thread "<a href="http://templateshares-ue.net/tsue/?p=forums&amp;pid=11&amp;fid=8&amp;tid=2268" original-title="">Themes</a>" in forum 
            <a href="http://templateshares-ue.net/tsue/?p=forums&amp;pid=11&amp;fid=8" original-title="">General Discussion and Feedback</a>.
        </div>
        <div class="replyDate">01-06-2013 23:05</div>
        <div class="clear"></div>
    </div>';
    echo "<div class='cReply'>";
        if ($user["privacy"] != "strong" || ($CURUSER["control_panel"] == "yes") || ($CURUSER["id"] == $user["id"])) { 

            $res = SQL_Query_exec("SELECT torrent, uploaded, downloaded FROM peers WHERE userid = '$id' AND seeder = 'yes'"); 
            if (mysqli_num_rows($res) > 0) 
                $seeding = peerstable($res); 

            $res = SQL_Query_exec("SELECT torrent, uploaded, downloaded FROM peers WHERE userid = '$id' AND seeder = 'no'"); 
            if (mysqli_num_rows($res) > 0) 
                $leeching = peerstable($res); 

            if ($seeding) 
                print("<b>" .T_("CURRENTLY_SEEDING"). ":</b><br />$seeding<br /><br />"); 

            if ($leeching) 
                print("<b>" .T_("CURRENTLY_LEECHING"). ":</b><br />$leeching<br /><br />"); 

            if (!$leeching && !$seeding) 
                print("<b>".T_("NO_ACTIVE_TRANSFERS")."</b><br />"); 
        }
    echo "</div>";
}elseif ($action == "following"){
    echo '<img src="http://templateshares-ue.net/tsue/data/avatars/s/1.png?1476689920" alt="xam" class="clickable avatar" id="member_info" memberid="1" rel="resized_by_tsue" original-title="xam">  
    <img src="http://templateshares-ue.net/tsue/data/avatars/s/10.jpg?1476689920" alt="keisko" class="clickable avatar" id="member_info" memberid="10" rel="resized_by_tsue" original-title="keisko">  
    <img src="http://templateshares-ue.net/tsue/data/avatars/s/146.png?1476689920" alt="mhmd1983" class="clickable avatar" id="member_info" memberid="146" rel="resized_by_tsue" original-title="mhmd1983"> ';
}elseif ($action == "followers"){
    echo '<img src="http://templateshares-ue.net/tsue/styles/default/avatars/avatar_m_s.png?1476690075" alt="Fuat44" class="clickable avatar" id="member_info" memberid="981" rel="resized_by_tsue" original-title="Fuat44"> ';
}elseif ($action == "uploadedtorrents"){
    if ($user["privacy"] != "strong" || ($CURUSER["control_panel"] == "yes") || ($CURUSER["id"] == $user["id"])) {
        //page numbers 
        $page = (int) $_GET["page"]; 
        $perpage = 25; 
        $where = ""; 
        if ($CURUSER['control_panel'] != "yes") 
            $where = "AND anon='no'"; 
        $res = SQL_Query_exec("SELECT COUNT(*) FROM torrents WHERE owner='$id' $where"); 
        $row = mysqli_fetch_array($res); 
        $count = $row[0]; 
        unset($where); 

        $orderby = "ORDER BY id DESC"; 

        //get sql info 
        if ($count) { 
            list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "../user/?id=$id&amp;"); 
            $query = "SELECT torrents.id, torrents.category, torrents.leechers, torrents.nfo, torrents.seeders, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.comments, torrents.numfiles, torrents.filename, torrents.owner, torrents.external, torrents.freeleech, categories.name AS cat_name, categories.parent_cat AS cat_parent, categories.image AS cat_pic, users.username, users.privacy, torrents.anon, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.announce FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id WHERE owner = $id $orderby $limit";
            $res = SQL_Query_exec($query); 
        }else{ 
            unset($res); 
        }

        if ($count) { 
            print($pagertop); 
            torrenttable($res); 
            print($pagerbottom); 
        }else{
            print("<b>".T_("UPLOADED_TORRENTS_ERROR")."</b><br />"); 
        }
    }
}elseif ($action == "staffinfo"){
    if($CURUSER["edit_users"]=="yes"){ 
        echo "<div>";
            // add you staff class
            if (get_user_class() >= 7){
                $r = SQL_Query_exec("SELECT id FROM bookmarkuser WHERE userid=$CURUSER[id] AND bkid=$id"); 
                $bookmarkuser = mysqli_num_rows($r); 

                if ($bookmarkuser)
                    print("<a target='blank' href='/watch/?action=delete&type=bookmarkuser&targetid=$id'><center><font style='margin-left:7px'><input type='submit' value='Remove ".$user["username"]." from your Personal Watchlist' class='btn btn-large btn-warning'/></font></center></a>\n"); 
                else{
                    print("<a target='blank' href='/watch/?action=add&type=bookmarkuser&targetid=$id'><center><font style='margin-left:7px'><input type='submit' value='Add ".$user["username"]." to your Personal Watchlist' class='btn btn-large btn-danger'/></font></center></a>&nbsp;<br />"); 
                }
            }
            $avatar = htmlspecialchars($user["avatar"]); 
            $signature = htmlspecialchars($user["signature"]); 
            $uploaded = $user["uploaded"]; 
            $downloaded = $user["downloaded"]; 
            $enabled = $user["enabled"] == 'yes'; 
            $warned = $user["warned"] == 'yes'; 
            $forumbanned = $user["forumbanned"] == 'yes'; 
            $modcomment = htmlspecialchars($user["modcomment"]); 

            print("<form method='post' action='admin-modtasks.php'>\n"); 
            print("<input type='hidden' name='action' value='edituser' />\n"); 
            print("<input type='hidden' name='userid' value='$id' />\n"); 
            print("<table border='0' cellspacing='0' cellpadding='3'>\n"); 
            print("<tr><td>".T_("TITLE").": </td><td align='left'><input type='text' size='67' name='title' value=\"$user[title]\" /></td></tr>\n"); 
            print("<tr><td>".T_("EMAIL")."</td><td align='left'><input type='text' size='67' name='email' value=\"$user[email]\" /></td></tr>\n"); 
            print("<tr><td>".T_("SIGNATURE").": </td><td align='left'><textarea cols='50' rows='10' name='signature'>".htmlspecialchars($user["signature"])."</textarea></td></tr>\n"); 
            print("<tr><td>".T_("UPLOADED").": </td><td align='left'><input type='text' size='30' name='uploaded' value=\"".mksize($user["uploaded"], 9)."\" /></td></tr>\n"); 
            print("<tr><td>".T_("DOWNLOADED").": </td><td align='left'><input type='text' size='30' name='downloaded' value=\"".mksize($user["downloaded"], 9)."\" /></td></tr>\n"); 
            print("<tr><td>".T_("AVATAR_URL")."</td><td align='left'><input type='text' size='67' name='avatar' value=\"$avatar\" /></td></tr>\n"); 
            print("<tr><td>".T_("IP_ADDRESS").": </td><td align='left'><input type='text' size='20' name='ip' value=\"$user[ip]\" /></td></tr>\n"); 
            print("<tr><td>".T_("INVITES").": </td><td align='left'><input type='text' size='4' name='invites' value='".$user["invites"]."' /></td></tr>\n"); 

            if ($CURUSER["class"] > $user["class"]){ 
                print("<tr><td>".T_("CLASS").": </td><td align='left'><select name='class'>\n"); 
                $maxclass = $CURUSER["class"]; 
                for ($i = 1; $i < $maxclass; ++$i) 
                    print("<option value='$i' " . ($user["class"] == $i ? " selected='selected'" : "") . ">$prefix" . get_user_class_name($i) . "\n"); 
                print("</select></td></tr>\n"); 
            }

            print("<tr><td>".T_("DONATED_US").": </td><td align='left'><input type='text' size='4' name='donated' value='$user[donated]' /></td></tr>\n"); 
            print("<tr><td>".T_("PASSWORD").": </td><td align='left'><input type='password' size='67' name='password' value=\"$user[password]\" /></td></tr>\n"); 
            print("<tr><td>".T_("CHANGE_PASS").": </td><td align='left'><input type='checkbox' name='chgpasswd' value='yes'/></td></tr>"); 
            print("<tr><td>".T_("MOD_COMMENT").": </td><td align='left'><textarea cols='50' rows='10' name='modcomment'>$modcomment</textarea></td></tr>\n"); 
            print("<tr><td>".T_("ACCOUNT_STATUS").": </td><td align='left'><input name='enabled' value='yes' type='radio' " . ($enabled ? " checked='checked'" : "") . " />Enabled <input name='enabled' value='no' type='radio' " . (!$enabled ? " checked='checked' " : "") . " />Disabled</td></tr>\n"); 
            print("<tr><td>".T_("WARNED").": </td><td align='left'><input name='warned' value='yes' type='radio' " . ($warned ? " checked='checked'" : "") . " />Yes <input name='warned' value='no' type='radio' " . (!$warned ? " checked='checked'" : "") . " />No</td></tr>\n"); 
            print("<tr><td>".T_("FORUM_BANNED").": </td><td align='left'><input name='forumbanned' value='yes' type='radio' " . ($forumbanned ? " checked='checked'" : "") . " />Yes <input name='forumbanned' value='no' type='radio' " . (!$forumbanned ? " checked='checked'" : "") . " />No</td></tr>\n"); 
            print("<tr><td>".T_("PASSKEY").": </td><td align='left'>$user[passkey]<br /><input name='resetpasskey' value='yes' type='checkbox' />".T_("RESET_PASSKEY")." (".T_("RESET_PASSKEY_MSG").")</td></tr>\n"); 
            print("<tr><td colspan='2' align='center'><input type='submit' value='".T_("SUBMIT")."' class='btn btn-success'/></td></tr>\n"); 
            print("</table>\n"); 
            print("</form>\n"); 
        echo "</div>";
    }
}elseif ($action == "banwarning"){
    if($CURUSER["edit_users"]=="yes"){ 
        echo "<div>";  

        print '<a name="warnings"></a>'; 

        $rqq = "SELECT * FROM warnings WHERE userid=$id ORDER BY id DESC"; 
        $res = SQL_Query_exec($rqq); 

        if (mysqli_num_rows($res) > 0){ 

            ?><b>Warnings:</b><br />
            <table border="1" cellpadding="3" cellspacing="0" width="80%" align="center" class="table_table"> 
                <tr>
                    <th class="table_head">Added</th> 
                    <th class="table_head"><?php echo T_("EXPIRE"); ?></th> 
                    <th class="table_head"><?php echo T_("REASON"); ?></th> 
                    <th class="table_head"><?php echo T_("WARNED_BY"); ?></th> 
                    <th class="table_head"><?php echo T_("TYPE"); ?></th>       
                </tr><?php

                while ($arr = mysqli_fetch_assoc($res)){ 
                    if ($arr["warnedby"] == 0) { 
                        $wusername = T_("SYSTEM"); 
                    }else{
                        $res2 = SQL_Query_exec("SELECT id,username FROM users WHERE id = ".$arr['warnedby'].""); 
                        $arr2 = mysqli_fetch_assoc($res2); 
                        $wusername = htmlspecialchars($arr2["username"]); 
                    }
                    $arr['added'] = utc_to_tz($arr['added']); 
                    $arr['expiry'] = utc_to_tz($arr['expiry']); 

                    $addeddate = substr($arr['added'], 0, strpos($arr['added'], " ")); 
                    $expirydate = substr($arr['expiry'], 0, strpos($arr['expiry'], " ")); 
                    print("<tr><td class='table_col1' align='center'>$addeddate</td><td class='table_col2' align='center'>$expirydate</td><td class='table_col1'>".format_comment($arr['reason'])."</td><td class='table_col2' align='center'><a href='../user/?id=".$arr2['id']."'>".$wusername."</a></td><td class='table_col1' align='center'>".$arr['type']."</td></tr>\n"); 
                }
            echo "</table>\n"; 
        }else{
            echo T_("NO_WARNINGS"); 
        }

        print("<form method='post' action='admin-modtasks.php'>\n"); 
        print("<input type='hidden' name='action' value='addwarning' />\n"); 
        print("<input type='hidden' name='userid' value='$id' />\n"); 
        echo "<br /><br /><center><table border='0'><tr><td align='right'><b>".T_("REASON").":</b> </td><td align='left'><textarea cols='40' rows='5' name='reason'></textarea></td></tr>"; 
        echo "<tr><td align='right'><b>".T_("EXPIRE").":</b> </td><td align='left'><input type='text' size='4' name='expiry' />(days)</td></tr>"; 
        echo "<tr><td align='right'><b>".T_("TYPE").":</b> </td><td align='left'><input type='text' size='10' name='type' /></td></tr>"; 
        echo "<tr><td colspan='2' align='center'><input type='submit' value='Add Warning' class='btn btn-large btn-warning'/></td></tr></table></center></form>"; 

        if($CURUSER["delete_users"] == "yes"){ 
            print("<hr /><center><form method='post' action='admin-modtasks.php'>\n"); 
            print("<input type='hidden' name='action' value='deleteaccount' />\n"); 
            print("<input type='hidden' name='userid' value='$id' />\n"); 
            print("<input type='hidden' name='username' value='".$user["username"]."' />\n"); 
            echo "<b>".T_("REASON").":</b><input type='text' size='30' name='delreason' />"; 
            echo "&nbsp;<input type='submit' value='Delete Account' class='btn btn-large btn-danger'/></form></center>"; 
        }

        echo "</div>"; 
    }
}
?>