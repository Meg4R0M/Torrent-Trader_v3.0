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
    show_error_msg("Error", "<div style='margin-top:10px; margin-bottom:10px' align='center'><font size=2 color=#FF2000><b> 
You are blocked by this member and you can not view their profile!</b></font></div>", 1); 
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

$userdownloaded = mksize($user["downloaded"]);
$useruploaded = mksize($user["uploaded"]);
//$ratio 
if ($user["downloaded"] > 0) { 
    $ratio = $user["uploaded"] / $user["downloaded"]; 
}else{ 
    $ratio = "---"; 
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
stdhead(sprintf(T_("USER_DETAILS_FOR"), $user["username"]));

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
		

		<div id="memberinfoUpDownStats">
			    
			<img src="/themes/default/status/upload.png" alt="Uploaded" title="Uploaded" class="middle" /> '.$useruploaded.' 
			<img src="/themes/default/status/download.png" alt="Downloaded" title="Downloaded" class="middle" /> '.$userdownloaded.'
			<img src="/themes/default/status/ratio.png" alt="Ratio" title="Ratio" class="middle" /> <span class="ratioNull">'.$ratio.'</span> 
			<img src="/themes/default/status/buffer.png" alt="Buffer" title="Buffer" class="middle" /> 1 GB 
            '.T_("TORRENTS_POSTED").': '.number_format($numtorrents).'
		</div>

		<div>
			<span class="clickable small">
				<a href="/account-details.php?id='.$user["id"].'">'.$user["username"].'\'s Profile</a>
			</span>
			<span class="clickable small" id="messages_new_message" receiver_membername="'.$user["username"].'">Send Message</span>
			<span class="clickable small" id="follow_member" memberid="'.$user["id"].'" inOverlay="no">	Follow</span>
            <span class="clickable small"><a href="/report.php?user='.$user["id"].' class="profile-signup-btn  a_signup-profile">Report</a></span>
		</div>
		
	</div>';

if ($CURUSER["id"] != $user["id"]){ 
    $r = SQL_Query_exec("SELECT id FROM friends WHERE userid=$CURUSER[id] AND friendid=$id"); 
    $friend = mysqli_num_rows($r); 
    $r = SQL_Query_exec("SELECT id FROM blocked WHERE userid=$CURUSER[id] AND blockid=$id"); 
    $block = mysqli_num_rows($r); 

    if ($friend) 
        print("<a href='/friends.php?action=delete&type=friend&targetid=$id' class=\"profile-signup-btn  a_signup-profile\"><font><font>Unfriend</font></font></a>"); 
    elseif($block) 
        print("<a href=/friends.php?action=delete&type=block&targetid=$id class=\"profile-signup-btn  a_signup-profile\"><font><font>Unblocked</font></font></a>"); 
    else { 
        print("<a href=/friends.php?action=add&type=friend&targetid=$id class=\"profile-signup-btn  a_signup-profile\"><font><font>+Friend</font></font></a>"); 
        print("<a href=/friends.php?action=add&type=block&targetid=$id class=\"profile-signup-btn  a_signup-profile\"><font><font>Block</font></font></a>"); 
    } 
} 

    
}else{ 
    echo sprintf(T_("REPORT_MEMBER_MSG"), $user["id"]); 
} 

	echo '<div class="clear"></div>
</div>
<ul class="tabs">
	<li><a href="#profile_posts">Profile Posts</a></li>
	<li class="recent_activity"><a href="#recent_activity">Recent Activity</a></li>
	<li class="following"><a href="#following">Following</a></li>
	<li class="followers"><a href="#followers">Followers</a></li>
</ul>';

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
    echo '</div>';
?> 
<div class="tabItems" id="recent_activity" rel="1205"></div>
<div class="tabItems" id="following" rel="1205"></div>
<div class="tabItems" id="followers" rel="1205"></div>


<div class="profile-promotion"> 
            <div class="profile-promotion-wrap"> 
            <?php 
            if ($CURUSER["class"]=="5" || $CURUSER["class"]=="6" || $CURUSER["class"]=="7") { 
            ?> 
                <a href="#ban-warnings"         onClick="switchPages(4);return false;"    class="profile-signup-btn  a_signup-profile"><b><?php echo T_("BANS_WARNINGS");?></b></a> 
                <a href="#staff-only-info"         onClick="switchPages(3);return false;"    class="profile-signup-btn  a_signup-profile"><b>Staff Only</b></a> 
                <?php } ?> 
                <a href="#uploaded-torrents"     onClick="switchPages(2);return false;"    class="profile-signup-btn  a_signup-profile"><b><?php echo T_("UPLOADED_TORRENTS");?></b></a> 
                 
                <a href="#Mywall"         onClick="switchPages(5);return false;"    class="profile-signup-btn  a_signup-profile"><b>Status</b></a> 
                <div class="clear"></div> 
            </div> 
        </div><br /> 

<?php 
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
 echo "<div class='hiddenframe' id='MyWall'>";   
begin_frame(T_("COMMENTS")); 
   if ($CURUSER) { 
     echo "<center>"; 
     echo "<form name=\"comment\" method=\"post\" action=\"../user/?id=$user[id]&amp;takecomment=yes\">"; 
     echo textbbcode("comment","body")."<br />"; 
     echo "<input type=\"submit\"  value=\"".T_("ADDCOMMENT")."\" />"; 
     echo "</form></center>"; 
   } 
 echo "</div>";   
end_frame(); 

echo "<div class='hiddenframe' id='localactiv'>"; 
if ($user["privacy"] != "strong" || ($CURUSER["control_panel"] == "yes") || ($CURUSER["id"] == $user["id"])) { 
    begin_frame(T_("LOCAL_ACTIVITY")); 

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

    end_frame(); 
echo "</div>"; 

echo "<div class='hiddenframe' id='uploadedtor'>"; 
    begin_frame(T_("UPLOADED_TORRENTS")); 
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
    }else { 
        print("<b>".T_("UPLOADED_TORRENTS_ERROR")."</b><br />"); 
    } 

    end_frame(); 
    echo "</div>"; 
} 



if($CURUSER["edit_users"]=="yes"){ 
    echo "<div class='hiddenframe' id='staffonlyinfo'>"; 
    begin_frame(T_("STAFF_ONLY_INFO")); 
if (get_user_class() >= 7) // add you staff class 
{ 
    $r = SQL_Query_exec("SELECT id FROM bookmarkuser WHERE userid=$CURUSER[id] AND bkid=$id"); 
    $bookmarkuser = mysqli_num_rows($r); 

    if ($bookmarkuser) 
        print("<a target='blank' href='/watch/?action=delete&type=bookmarkuser&targetid=$id'><center><font style='margin-left:7px'><input type='submit' value='Remove ".$user["username"]." from your Personal Watchlist' class='btn btn-large btn-warning'/></font></center></a>\n"); 
    else 
    { 
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
    end_frame(); 
} 

if($CURUSER["edit_users"]=="yes"){ 
    echo "<div class='hiddenframe' id='banwarnings'>"; 
    begin_frame(T_("BANS_WARNINGS")); 

    print '<a name="warnings"></a>'; 
     
    $rqq = "SELECT * FROM warnings WHERE userid=$id ORDER BY id DESC"; 
    $res = SQL_Query_exec($rqq); 

    if (mysqli_num_rows($res) > 0){ 

        ?> 
        <b>Warnings:</b><br /> 
        <table border="1" cellpadding="3" cellspacing="0" width="80%" align="center" class="table_table"> 
        <tr> 
            <th class="table_head">Added</th> 
            <th class="table_head"><?php echo T_("EXPIRE"); ?></th> 
            <th class="table_head"><?php echo T_("REASON"); ?></th> 
            <th class="table_head"><?php echo T_("WARNED_BY"); ?></th> 
            <th class="table_head"><?php echo T_("TYPE"); ?></th>       
        </tr> 
        <?php 

        while ($arr = mysqli_fetch_assoc($res)){ 
            if ($arr["warnedby"] == 0) { 
                $wusername = T_("SYSTEM"); 
            } else { 
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

    end_frame(); 
    echo "</div>"; 
} 

stdfoot(); 

?> 
<script> 
    function switchPages(pageId) 
    { 
        switch (pageId) 
        { 
        case 1: 
        hideAll(); 
          $('#localactiv').slideToggle(500); 
          break; 
        case 2: 
        hideAll(); 
          $('#uploadedtor').slideToggle(500); 
          break; 
        case 3: 
        hideAll(); 
          $('#staffonlyinfo').slideToggle(500); 
          break; 
        case 4: 
        hideAll(); 
          $('#banwarnings').slideToggle(500); 
          break; 
          case 5: 
        hideAll(); 
          $('#MyWall').slideToggle(500); 
          break; 
        } 
    } 

    function hideAll() 
    { 
        $('#localactiv').slideUp(250); 
        $('#uploadedtor').slideUp(250); 
        $('#staffonlyinfo').slideUp(250); 
        $('#banwarnings').slideUp(250); 
        $('#Mywall').slideUp(250); 
    } 

</script>