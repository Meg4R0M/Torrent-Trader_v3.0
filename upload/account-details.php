<?php 
// 
//  TorrentTrader v3.x 
//      $LastChangedDate: 2016-10-16 20:34:24 +0100 (Sun, 16 Oct 2016) $ 
//      $LastChangedBy: Meg4R0M $ 
// 

require_once("backend/functions.php"); 
dbconn(); 
loggedinonly(); 

stdhead("User CP"); 

    $id = (int)$_GET["id"]; 

    if (!is_valid_id($id)) 
        show_error_msg(T_("NO_SHOW_DETAILS"), "Bad ID.",1); 

    $r = @SQL_Query_exec("SELECT * FROM users WHERE id=$id"); 
    $user = mysqli_fetch_array($r) or  show_error_msg(T_("NO_SHOW_DETAILS"), T_("NO_USER_WITH_ID")." $id.",1); 

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

    //get all vars first 

    //$country 
    $res = SQL_Query_exec("SELECT name FROM countries WHERE id=$user[country] LIMIT 1"); 
    if (mysqli_num_rows($res) == 1){ 
        $arr = mysqli_fetch_assoc($res); 
        $country = "$arr[name]"; 
    }

    if (!$country) $country = "<b>Unknown</b>"; 

    //$moods 
    $res = SQL_Query_exec("SELECT name, moodspic FROM moods WHERE id=$CURUSER[moods]");
    $row = mysqli_fetch_assoc($res);
    $moods = ( $row ) ? "<img src='../images/moods/$row[moodspic]' alt='$row[name]' title='$row[name]' />" : 'Unknown';

    // Download / Upload / Ratio
    $userdownloaded = mksize($user["downloaded"]);
    $useruploaded = mksize($user["uploaded"]);
    if ($user["downloaded"] > 0) { 
        $ratio = $user["uploaded"] / $user["downloaded"]; 
    }else{ 
        $ratio = "0"; 
    }

    $numtorrents = get_row_count("torrents", "WHERE owner = $id"); 
    $numcomments = get_row_count("comments", "WHERE user = $id"); 
    $numforumposts = get_row_count("forum_posts", "WHERE userid = $id"); 

    $avatar = htmlspecialchars($user["avatar"]); 
    if (!$avatar) { 
        $avatar = "/images/default_avatar.png"; 
    } 

    function peerstable($res){ 
        $ret = "<table align='center' cellpadding=\"3\" cellspacing=\"0\" class=\"table_table\" width=\"100%\" border=\"1\"><tr><th class='table_head'>".T_("NAME")."</th><th class='table_head'>".T_("SIZE")."</th><th class='table_head'>" .T_("UPLOADED"). "</th>\n<th class='table_head'>" .T_("DOWNLOADED"). "</th><th class='table_head'>" .T_("RATIO"). "</th></tr>\n"; 

        while ($arr = mysqli_fetch_assoc($res)){ 
            $res2 = SQL_Query_exec("SELECT name,size FROM torrents WHERE id=$arr[torrent] ORDER BY name"); 
            $arr2 = mysqli_fetch_assoc($res2); 
            if ($arr["downloaded"] > 0){ 
                $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2); 
            }else{ 
                $ratio = "---"; 
            }
            $ret .= "<tr><td class='table_col1'><a href='torrents-details.php?id=$arr[torrent]&amp;hit=1'><b>" . htmlspecialchars($arr2["name"]) . "</b></a></td><td align='center' class='table_col2'>" . mksize($arr2["size"]) . "</td><td align='center' class='table_col1'>" . mksize($arr["uploaded"]) . "</td><td align='center' class='table_col2'>" . mksize($arr["downloaded"]) . "</td><td align='center' class='table_col1'>$ratio</td></tr>\n"; 
        }
        $ret .= "</table>\n"; 
        return $ret; 
    }

    //Layout 
    echo '<div id="memberCard">';

        if ($user["privacy"] != "strong" || ($CURUSER["control_panel"] == "yes") || ($CURUSER["id"] == $user["id"])) { 

            echo '<div class="memberCardAvatar">
                <img src="/images/countryFlags/noFlag.png" alt="" title="" class="countryFlag" />
                <img src="'.$avatar.'" alt="" title="" class="clickable avatar" id="member_info" memberid="'.$user["id"].'" /><br />
                '.$moods.'
            </div>

            <div class="memberCardDetails">
                <a href="/account-details.php?id='.$user["id"].'"><span style="color: #6d6c6c; font-weight: bold;">'.$user["username"].'</span></a>, '.$user["age"].' years old<br />
                <span style="color: #6d6c6c; font-weight: bold;">'.get_user_class_name($user["class"]).'</span><br />
                <b>Member Since:</b> '.$user["added"].'<br />
                <b>Last Activity:</b> '.$user["last_access"].'<br />
                <b>Location:</b> '.htmlspecialchars($user["page"]).'<br />

                <div id="memberinfoUpDownStats">';
                    if ($user["banned"] == "yes")
                        echo '<img src="/themes/default/status/banned.png" alt="Banned" title="Banned" class="middle" />';
                    echo '<img src="/themes/default/status/upload.png" alt="Uploaded" title="Uploaded" class="middle" /> '.$useruploaded.' 
                    <img src="/themes/default/status/download.png" alt="Downloaded" title="Downloaded" class="middle" /> '.$userdownloaded.'
                    <img src="/themes/default/status/ratio.png" alt="Ratio" title="Ratio" class="middle" /> <span class="ratioNull">'.$ratio.'</span> 
                    <img src="/themes/default/status/buffer.png" alt="Buffer" title="Buffer" class="middle" /> 1 GB 
                    '.T_("TORRENTS_POSTED").': '.number_format($numtorrents).'
                </div>

                <div>
                    <span class="clickable small"><a href="/account-details.php?id='.$user["id"].'">'.$user["username"].'\'s Profile</a></span>
                    <span class="clickable small" id="messages_new_message" receiver_membername="'.$user["username"].'">Send Message</span>
                    <span class="clickable small" id="follow_member" memberid="'.$user["id"].'" inOverlay="no">Follow </span>';
                    if ($CURUSER["id"] != $user["id"]){ 
                        $r = SQL_Query_exec("SELECT id FROM friends WHERE userid=$CURUSER[id] AND friendid=$id"); 
                        $friend = mysqli_num_rows($r); 
                        $r = SQL_Query_exec("SELECT id FROM blocked WHERE userid=$CURUSER[id] AND blockid=$id"); 
                        $block = mysqli_num_rows($r); 

                        echo '<span class="clickable small"><a href="/report.php?user='.$user["id"].'">Report</a> </span>';
                        if ($friend) 
                            echo '<span class="clickable small"><a href="/friends.php?action=delete&type=friend&targetid='.$id.'">Unfriend</a></span>'; 
                        elseif($block)
                            echo '<span class="clickable small"><a href="/friends.php?action=delete&type=block&targetid='.$id.'">Unblocked</a></span>'; 
                        else{
                            echo '<span class="clickable small"><a href="/friends.php?action=add&type=friend&targetid='.$id.'">+Friend</a></span>
                            <span class="clickable small"><a href="/friends.php?action=add&type=block&targetid='.$id.'">Block</a></span>'; 
                        }
                    }
                echo '</div>
            </div>';
    
        }else{
            echo sprintf(T_("REPORT_MEMBER_MSG"), $user["id"]); 
        }

        echo '<div class="clear"></div>
    </div>
    <ul class="tabs">
        <li><a href="#profile_posts">Profile Posts</a></li>
        <li class="recent_activity"><a href="#recent_activity">Recent Activity</a></li>
        <li class="following"><a href="#following">Following</a></li>
        <li class="followers"><a href="#followers">Followers</a></li>';
        if ($user["privacy"] != "strong" || ($CURUSER["control_panel"] == "yes") || ($CURUSER["id"] == $user["id"])) {
            echo '<li class="uploadedtorrents"><a href="#uploadedtorrents">'.T_("UPLOADED_TORRENTS").'</a></li>';
        }
        if($CURUSER["edit_users"]=="yes"){  
            echo '<li class="staffinfo"><a href="#staffinfo">'.T_("STAFF_ONLY_INFO").'</a></li>
            <li class="banwarning"><a href="#banwarning">'.T_("BANS_WARNINGS").'</a></li>';
        }
    echo '</ul>';

// COMMENTS
   $subres = SQL_Query_exec("SELECT COUNT(*) FROM comments WHERE userprofile = $id"); 
   $subrow = mysqli_fetch_array($subres); 
   $commcount = $subrow[0]; 
    
   if ($commcount) { 
     list($pagertop, $pagerbottom, $limit) = pager(10, $commcount, "../user/?id=$user[id]&amp;"); 
     $commquery = "SELECT comments.id, text, user, comments.added, avatar, signature, username, title, class, uploaded, downloaded, privacy, donated FROM comments LEFT JOIN users ON comments.user = users.id WHERE userprofile = $id ORDER BY comments.id $limit"; 
     $commres = SQL_Query_exec($commquery); 
   }else{ 
     unset($commres); 
   } 
   require_once("backend/bbcode.php");

    echo '<div class="tabItems">';
        if ($commcount) { 
            print($pagertop); 
            commenttable($commres, 'userprofile');
            print($pagerbottom); 
        }else{
            echo '<div class="comment-box" id="no_comments">There are no comments yet.</div>';
        }
        if ($CURUSER) {
            echo '<form method="post" id="comments_post_form">
                <input type="hidden" name="content_type" id="content_type" value="profile_comments" />
                <input type="hidden" name="content_id" id="content_id" value="'.$user["id"].'" />
                <textarea name="message" id="postAComment">'.T_("ADDCOMMENT").'</textarea>
                <div class="postACommentButtons">
                    <input type="submit" value="Save" class="submit" /> 
                    <input type="button" value="Preview" class="submit" id="tinymce_button_preview" /> 
                    <input type="reset" value="Clear" class="submit" />
                </div>
            </form>';
            
        }

        if ($CURUSER) { 
            echo "<center>"; 
            echo "<form name=\"comment\" method=\"post\" action=\"../user/?id=$user[id]&amp;takecomment=yes\">"; 
            echo textbbcode("comment","body")."<br />"; 
            echo "<input type=\"submit\"  value=\"".T_("ADDCOMMENT")."\" />"; 
            echo "</form></center>"; 
        }

    echo '</div>';

    echo '<div class="tabItems" id="recent_activity" rel="'.$user["id"].'"></div>
    <div class="tabItems" id="following" rel="'.$user["id"].'"></div>
    <div class="tabItems" id="followers" rel="'.$user["id"].'"></div>';
    if ($user["privacy"] != "strong" || ($CURUSER["control_panel"] == "yes") || ($CURUSER["id"] == $user["id"])) {
        echo '<div class="tabItems" id="uploadedtorrents" rel="'.$user["id"].'"></div>';
    }
    if($CURUSER["edit_users"]=="yes"){
        echo '<div class="tabItems" id="staffinfo" rel="'.$user["id"].'"></div>
        <div class="tabItems" id="banwarning" rel="'.$user["id"].'"></div>';
    }

    //take comment add 
    if ($_GET["takecomment"] == 'yes'){ 
        loggedinonly(); 
        $body = $_POST['body']; 

        if (!$body) 
            show_error_msg(T_("RATING_ERROR"), T_("YOU_DID_NOT_ENTER_ANYTHING"), 1);

        SQL_Query_exec("UPDATE users SET comments = comments + 1 WHERE id = $id"); 
        SQL_Query_exec("INSERT INTO comments (user, userprofile, added, text) VALUES (".$CURUSER["id"].", ".$id.", '" .get_date_time(). "', " . sqlesc($body).")"); 

        if (mysqli_affected_rows($GLOBALS["___mysqli_ston"]) == 1) 
            show_error_msg(T_("COMPLETED"), T_("COMMENT_ADDED"), 0); 
        else 
            show_error_msg(T_("ERROR"), T_("UNABLE_TO_ADD_COMMENT"), 0); 
    }

stdfoot();
?>