<?php 
begin_block(T_("ONLINE_USERS")); 
$monli = "SELECT * FROM mostonline";
$result = SQL_Query_exec($monli);
$details = mysqli_fetch_array($result);

if ($totalonline > $details['amount'])
{
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
?>