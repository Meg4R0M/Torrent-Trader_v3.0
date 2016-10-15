<?php
if ($_SERVER['REQUEST_URI'] == "/index.php"){
	begin_block(T_("ONLINE_USERS")); 
	$monli = "SELECT * FROM mostonline";
	$result = SQL_Query_exec($monli);
	$details = mysqli_fetch_array($result);

	if ($totalonline > $details['amount']){
		SQL_Query_exec("UPDATE mostonline SET amount = $totalonline");
		SQL_Query_exec("UPDATE mostonline SET date = now()");
	}

	$date1=date("D, d M Y H:i:s", strtotime($details['date']));
	$file = "".$site_config["cache_dir"]."/cache_usersonlineblock.txt";
	$expire = 10; // time in seconds
	$guests = number_format(getguests());
	$members = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 900"));

	if (file_exists($file) && filemtime($file) > (time() - $expire)) {
		$usersonlinerecords = unserialize(file_get_contents($file));
	}else{
		$usersonlinequery = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, username, class FROM users WHERE privacy !='strong' AND UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 900") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

		while ($usersonlinerecord = mysqli_fetch_array($usersonlinequery) ) {
			$usersonlinerecords[] = $usersonlinerecord;
		}
		$OUTPUT = serialize($usersonlinerecords);
		$fp = fopen($file,"w");
		fputs($fp, $OUTPUT);
		fclose($fp);
	}

	if ($usersonlinerecords == ""){
		echo "No members OnLine"; 
	}else{
		echo '<div id="membersOnlineNow" class="">
			<p id="onlineMembersList">';
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

	end_block();

	begin_block("Last 24 Hours Active Members");
	$monli2 = "SELECT * FROM mostonline";
	$result2 = SQL_Query_exec($monli);
	$details2 = mysqli_fetch_array($result);

	if ($totalonline > $details['amount']){
		SQL_Query_exec("UPDATE mostonline SET amount = $totalonline");
		SQL_Query_exec("UPDATE mostonline SET date = now()");
	}

	$file2 = "".$site_config["cache_dir"]."/cache_usersonlineblock24.txt";
	$guests2 = number_format(getguests());
	$members2 = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 84600"));
	$total24on = $guests2 + $members2;

	if (file_exists($file2) && filemtime($file2) > (time() - $expire)) {
		$usersonlinerecords2 = unserialize(file_get_contents($file2));
	}else{
		$usersonlinequery2 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, username, class FROM users WHERE privacy !='strong' AND UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 84600") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

		while ($usersonlinerecord2 = mysqli_fetch_array($usersonlinequery2) ) {
			$usersonlinerecords2[] = $usersonlinerecord2;
		}
		$OUTPUT = serialize($usersonlinerecords2);
		$fp = fopen($file2,"w");
		fputs($fp, $OUTPUT);
		fclose($fp);
	}

	if ($usersonlinerecords2 == ""){
		echo "No members OnLine"; 
	}else{
		echo '<div id="membersOnlineNow" class="">
			<p id="onlineMembersList">';
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
						$row2 = &$rows2[$i];
						echo '<span id="member_info" memberid="'.$user2[id].'" class="clickable"><span style="color: '.$color.'; font-weight: bold;">'.class_user($user2["username"]).'</span></span>'.($i2 < $n2 ? ", " : "");
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
		</div>';
	}
	end_block();
}
?>