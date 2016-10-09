<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2012-06-14 17:31:26 +0100 (Thu, 14 Jun 2012) $
//      $LastChangedBy: torrenttrader $
//
//      http://www.torrenttrader.org
//
//
require_once("backend/functions.php");
dbconn();
loggedinonly();

stdhead(T_("USERCP"));

function navmenu(){
?>
      <div class="profile-promotion">
            <div class="profile-promotion-wrap">
<?php print("<a href='../account/'class=\"profile-signup-btn  a_signup-profile\"><b>Profile</b></a>");?>
		<?php print("<a href='../account/?action=edit_settings&amp;do=edit'class=\"profile-signup-btn  a_signup-profile\"><b>Settings</b></a>");?>
		<?php print("<a href='../account/?action=mytorrents'class=\"profile-signup-btn  a_signup-profile\"><b>My Uploads</b></a>");?>
		

                <div class="clear"></div>
            </div>
        </div>
    <br />
	<?php
}//end func

$action = $_REQUEST["action"];
$do = $_REQUEST["do"];

if (!$action){
	begin_frame(T_("USER").": $CURUSER[username] (".T_("ACCOUNT_PROFILE").")");

	$usersignature = stripslashes($CURUSER["signature"]);

	navmenu();
	?>
	<div class="profile-stats">
            <ul>
            	<li><span><font><font><?php echo T_("USERNAME"); ?></font></font></span><font><font><?php echo $CURUSER["username"]; ?></font></font></a></li>
                <li><span><font><font><?php echo T_("CLASS"); ?></font></font></span><font><font><?php echo $CURUSER["level"]; ?></font></font></a></li>
                <li><span><font><font><?php echo T_("EMAIL"); ?></font></font></span><font><font><?php echo $CURUSER["email"]; ?><br /></font></font></a></li>
                
            </ul>
            <div class="clear"></div>
        </div>
        <div class="profile-stats">
            <ul>
            	<li><span><font><font><?php echo T_("JOINED"); ?></font></font></span><font><font><?php echo utc_to_tz($CURUSER["added"]); ?></font></font></a></li>
                
            </ul>
            <div class="clear"></div>
        </div>
        <div class="profile-stats">
            <ul>
            	<li><span><font><font><?php echo T_("PREFERRED_CLIENT"); ?></font></font></span><font><font><?php echo htmlspecialchars($CURUSER["client"]); ?></font></font></a></li>
                <li><span><font><font><?php echo T_("DONATED"); ?></font></font></span><font><font><?php echo $site_config['currency_symbol']; ?><?php echo number_format($CURUSER["donated"], 2); ?></font></font></a></li>
                <li><span><font><?php echo T_("CUSTOM_TITLE"); ?></font></font></span><font><font><?php echo format_comment($CURUSER["title"]); ?></font></font></a></li>
                
            </ul>
            <div class="clear"></div>
        </div>
        <div class="profile-stats">
            <ul>
            	<li><span><font><font><?php echo T_("JOINED"); ?></font></font></span><font><font><?php echo utc_to_tz($CURUSER["added"]); ?></font></font></a></li>
                <li><span><font><font><?php echo T_("PASSKEY"); ?></font></font></span><font><font><?php echo $CURUSER["passkey"]; ?><br /></font></font></a></li>
                
            </ul>
            <div class="clear"></div>
        </div>
        <center><?php print("<img src=../userbar.php/".$CURUSER["id"].".png border='0' width='350' height='19'></br><b>" . T_("USERBAR_DESCR") . ":</b><br><input type=\"text\" size='65' value=\"[url=".$site_config['SITEURL']."][img]".$site_config['SITEURL']."/userbar.php/".$CURUSER["id"].".png[/img][/url]\">");?></center>
	
	
	<?php
	end_frame();
}

/////////////// MY TORRENTS ///////////////////

if ($action=="mytorrents"){
begin_frame(T_("YOUR_TORRENTS"));
navmenu();
//page numbers
$page = (int) $_GET['page'];
$perpage = 200;

$res = SQL_Query_exec("SELECT COUNT(*) FROM torrents WHERE torrents.owner = " . $CURUSER["id"] ."");
$arr = mysqli_fetch_row($res);
$pages = floor($arr[0] / $perpage);
if ($pages * $perpage < $arr[0])
  ++$pages;

if ($page < 1)
  $page = 1;
else
  if ($page > $pages)
    $page = $pages;

for ($i = 1; $i <= $pages; ++$i)
  if ($i == $page)
    $pagemenu .= "$i\n";
  else
    $pagemenu .= "<a href='../account/?action=mytorrents&amp;page=$i'>$i</a>\n";

if ($page == 1)
  $browsemenu .= "";
else
  $browsemenu .= "<a href='../account/?action=mytorrents&amp;page=" . ($page - 1) . "'>[Prev]</a>";

$browsemenu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

if ($page == $pages)
  $browsemenu .= "";
else
  $browsemenu .= "<a href='../account/?action=mytorrents&amp;page=" . ($page + 1) . "'>[Next]</a>";

$offset = ($page * $perpage) - $perpage;
//end page numbers


$where = "WHERE torrents.owner = " . $CURUSER["id"] ."";
$orderby = "ORDER BY added DESC";

$query = SQL_Query_exec("SELECT torrents.id, torrents.category, torrents.name, torrents.added, torrents.hits, torrents.banned, torrents.comments, torrents.seeders, torrents.leechers, torrents.times_completed, categories.name AS cat_name, categories.parent_cat AS cat_parent FROM torrents LEFT JOIN categories ON category = categories.id $where $orderby LIMIT $offset,$perpage");

$allcats = mysqli_num_rows($query);
	if($allcats == 0) {
		echo '<div class="f-border comment"><br /><b>'.T_("NO_UPLOADS").'</b></div>';
	}else{
		print("<p align='center'>$pagemenu<br />$browsemenu</p>");
?>
    <table align="center" cellpadding="5" cellspacing="3" class="table_table" width="100%">
    <tr class="table_head">
        <th><font color='white'><?php echo T_("TYPE"); ?></font></th>
        <th><font color='white'><?php echo T_("NAME"); ?></font></th>
        <th><font color='white'><?php echo T_("COMMENTS"); ?></font></th>
        <th><font color='white'><?php echo T_("HITS"); ?></font></th>
        <th><font color='white'><?php echo T_("SEEDS"); ?></font></th>
        <th><font color='white'><?php echo T_("LEECHERS"); ?></font></th>
        <th><font color='white'><?php echo T_("COMPLETED"); ?></font></th>
        <th><font color='white'><?php echo T_("ADDED"); ?></font></th>
        <th><font color='white'><?php echo T_("EDIT"); ?></font></th>
    </tr>
    
<?php
  
		while($row = mysqli_fetch_assoc($query))
			{
			$char1 = 35; //cut length 
			$smallname = CutName(htmlspecialchars($row["name"]), $char1);
			echo "<tr><td class='table_col2' align='center'>$row[cat_parent]: $row[cat_name]</td><td class='table_col1' align='left'><a href='torrents-details.php?id=$row[id]'><font color='white'>$smallname</font></a></td><td class='table_col2' align='center'><a href='comments.php?type=torrent&amp;id=$row[id]'><font color='white'>".number_format($row["comments"])."</font></a></td><td class='table_col1' align='center'>".number_format($row["hits"])."</td><td class='table_col2' align='center'>".number_format($row["seeders"])."</td><td class='table_col1' align='center'>".number_format($row["leechers"])."</td><td class='table_col2' align='center'>".number_format($row["times_completed"])."</td><td class='table_col1' align='center'>".get_elapsed_time(sql_timestamp_to_unix_timestamp($row["added"]))."</td><td class='table_col2'><a href='torrents-edit.php?id=$row[id]'><font color='white'>EDIT</font></a></td></tr>\n";
			}
		echo "</table><br />";
		print("<p align='center'>$pagemenu<br />$browsemenu</p>");
	}

end_frame();
}


/////////////////////// EDIT SETTINGS ////////////////
if ($action=="edit_settings"){

	if ($do=="edit"){
	begin_frame(T_("EDIT_ACCOUNT_SETTINGS"));

	navmenu();
	?>
	<form enctype="multipart/form-data" method="post" action="../account/">
	<input type="hidden" name="action" value="edit_settings" />
	<input type="hidden" name="do" value="save_settings" />
</form>

	<?php
	end_frame();
	}


	if ($do == "save_settings"){
	begin_frame(T_("EDIT_ACCOUNT_SETTINGS"));

	navmenu();
		$set = array();
		  $updateset = array();
		  $changedemail = $newsecret = 0;

          $email = $_POST["email"];
		  if ($email != $CURUSER["email"]) {
				if (!validemail($email))
					$message = T_("NOT_VALID_EMAIL");
				$changedemail = 1;
		  }

		  $acceptpms = $_POST["acceptpms"];
		  $pmnotif = $_POST["pmnotif"];
		  $privacy = $_POST["privacy"];
		  $notifs = ($pmnotif == 'yes' ? "[pm]" : "");
		  $r = SQL_Query_exec("SELECT id FROM categories");
		  $rows = mysqli_num_rows($r);
		  for ($i = 0; $i < $rows; ++$i) {
				$a = mysqli_fetch_assoc($r);
				if ($_POST["cat$a[id]"] == 'yes')
				  $notifs .= "[cat$a[id]]";
		  }

		  if ($_POST['resetpasskey']) $updateset[] = "passkey=''";
          
          $avatar = strip_tags( $_POST["avatar"] );
          
          if ( $avatar != null )
          {    
               # Allowed Image Extenstions.
               $allowed_types = &$site_config["allowed_image_types"];    
              
               # We force http://
               if ( !preg_match( "#^\w+://#i", $avatar ) ) $avatar = "http://" . $avatar;

               # Clean Avatar Path.
               $avatar = cleanstr( $avatar );
               
               # Validate Image.
               $im = @getimagesize( $avatar );
               
               if ( !$im[ 2 ] || !@array_key_exists( $im['mime'], $allowed_types ) )
                     $message = "The avatar url was determined to be of a invalid nature.";
                     
               # Save New Avatar.
               $updateset[] = "avatar = " . sqlesc($avatar);
          }
          
		  $title = strip_tags($_POST["title"]);
		  $signature = $_POST["signature"];
		  $stylesheet = $_POST["stylesheet"];
		  $language = $_POST["language"];
		  $client = strip_tags($_POST["client"]);
		  $age = $_POST["age"];
		  $gender= $_POST["gender"];
		  $country = $_POST["country"];
		  $moods = $_POST["mood"];
		  $teams = $_POST["teams"];
		  $privacy = $_POST["privacy"];
		  $timezone = (int)$_POST['tzoffset'];

		  if (is_valid_id($stylesheet))
			$updateset[] = "stylesheet = '$stylesheet'";
		  if (is_valid_id($language))
			$updateset[] = "language = '$language'";
		  if (is_valid_id($teams))
			$updateset[] = "team = '$teams'";
		  if (is_valid_id($country))
			$updateset[] = "country = $country";
			if (is_valid_id($moods))
$updateset[] = "moods = $moods";
		  if ($acceptpms == "yes")
			$acceptpms = 'yes';
		  else
			$acceptpms = 'no';
		  if (is_valid_id($age))
				$updateset[] = "age = '$age'";
          
          $hideshoutbox = ($_POST["hideshoutbox"] == "yes") ? "yes" : "no";

            $updateset[] = "hideshoutbox = ".sqlesc($hideshoutbox);    
			$updateset[] = "acceptpms = ".sqlesc($acceptpms);
			$updateset[] = "commentpm = " . sqlesc($pmnotif == "yes" ? "yes" : "no");
			$updateset[] = "notifs = ".sqlesc($notifs);
			$updateset[] = "privacy = ".sqlesc($privacy);
			$updateset[] = "gender = ".sqlesc($gender);
			$updateset[] = "client = ".sqlesc($client);
			$updateset[] = "signature = ".sqlesc($signature);
			$updateset[] = "title = ".sqlesc($title);
			$updateset[] = "tzoffset = $timezone";

		  /* ****** */

		  if (!$message) {

			if ($changedemail) {
				$sec = mksecret();
				$hash = md5($sec . $email . $sec);
				$obemail = rawurlencode($email);
				$updateset[] = "editsecret = " . sqlesc($sec);
				$thishost = $_SERVER["HTTP_HOST"];
				$thisdomain = preg_replace('/^www\./is', "", $thishost);
$body = <<<EOD
You have requested that your user profile (username {$CURUSER["username"]})
on {$site_config["SITEURL"]} should be updated with this email address ($email) as
user contact.

If you did not do this, please ignore this email. The person who entered your
email address had the IP address {$_SERVER["REMOTE_ADDR"]}. Please do not reply.

To complete the update of your user profile, please follow this link:

{$site_config["SITEURL"]}/account-ce.php?id={$CURUSER["id"]}&secret=$hash&email=$obemail

Your new email address will appear in your profile after you do this. Otherwise
your profile will remain unchanged.
EOD;

				sendmail($email, "$site_config[SITENAME] profile update confirmation", $body, "From: $site_config[SITEEMAIL]", "-f$site_config[SITEEMAIL]");
				$mailsent = 1;
			} //changedemail

			SQL_Query_exec("UPDATE users SET " . implode(",", $updateset) . " WHERE id = " . $CURUSER["id"]."");
			$edited=1;
			echo "<br /><br /><center><b><font class='error'>Updated OK</font></b></center><br /><br />";
			if ($changedemail) {
				echo "<br /><center><b>".T_("EMAIL_CHANGE_SEND")."</b></center><br /><br />";
			}
		  }else{
			echo "<br /><br /><center><b><font class='error'>".T_("ERROR").": ".$message."</font></b></center><br /><br />";
		  }// message


		end_frame();
	}// end do

}//end action

if ($action=="changepw"){

	if ($do=="newpassword"){

        $chpassword = $_POST['chpassword'];
        $passagain = $_POST['passagain'];

        if ($chpassword != "") {

					if (strlen($chpassword) < 6)
						$message = T_("PASS_TOO_SHORT");
					if ($chpassword != $passagain)
						$message = T_("PASSWORDS_NOT_MATCH");
					$chpassword = passhash($chpassword);
                    $secret = mksecret();
		}

		if ((!$chpassword) || (!$passagain))
			$message = "You must enter something!";

		begin_frame();
		navmenu();

		if (!$message){
			SQL_Query_exec("UPDATE users SET password = " . sqlesc($chpassword) . ", secret = " . sqlesc($secret) . "  WHERE id = " . $CURUSER["id"]);
			echo "<br /><br /><center><b>".T_("PASSWORD_CHANGED_OK")."</b></center>";
			logoutcookie();
		}else{
			echo "<br /><br /><b><center>".$message."</center></b><br /><br />";
		}


		end_frame();
		stdfoot();
		die();
	}//do
}

stdfoot();
?>