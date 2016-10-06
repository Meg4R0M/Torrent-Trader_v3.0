<?php
if (!$CURUSER) {
	begin_block(T_("LOGIN"));
		echo '<form method="post" action="/account-login.php">
			<p>
				<label for="loginbox_membername">'.T_("USERNAME").'</label><br />
				<input type="text" name="username" id="loginbox_membername" class="s" accesskey="m" value="" />
			</p>
			<p>
				<label for="loginbox_password">'.T_("PASSWORD").'</label><br />
				<input type="password" name="password" id="loginbox_password" class="s" accesskey="p" value="" />
			</p>
			<p>
				<input type="checkbox" name="loginbox_remember" id="loginbox_remember" accesskey="r" value="1" /> 
				<label for="loginbox_remember">Stay logged in</label>
			</p>
			<p id="loginbox-buttons">
				<input type="submit" value="'.T_("LOGIN").'" class="submit" /> 
				<input type="reset" value="Clear" class="submit" />
			</p>
			<p>
				<a href="/account-signup.php" id="signup">'.T_("SIGNUP").'</a><br />
				<a href="/account-recover.php" rel="forgot-password">'.T_("RECOVER_ACCOUNT").'</a>
			</p>
		</form>';
	end_block();
}else{

begin_block('Profile');

	$avatar = htmlspecialchars($CURUSER["avatar"]);
	if (!$avatar)
		$avatar = "/images/default_avatar.png";

	$userdownloaded = mksize($CURUSER["downloaded"]);
	$useruploaded = mksize($CURUSER["uploaded"]);
	$privacylevel = T_($CURUSER["privacy"]);

	if ($CURUSER["uploaded"] > 0 && $CURUSER["downloaded"] == 0)
		$userratio = "Inf.";
	elseif ($CURUSER["downloaded"] > 0)
		$userratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);
	else
		$userratio = "---";
	$res = SQL_Query_exec("SELECT `moodspic` FROM `moods` WHERE `id` = '$CURUSER[moods]'");
	$row = mysqli_fetch_assoc($res);
	$moods = ( $row ) ? "<img src='../images/moods/$row[moodspic]' alt='$row[moodspic]' title='$row[moodspic]' />" : 'Unknown';

	echo'<p>
		<div class="floatright">
			<img src="'.$avatar.'" alt="" title="" class="clickable avatar" id="member_info" memberid="'.$CURUSER["id"].'" />
			<center>'.$moods.'</center>
			[<span id="history_link" class="clickable">History</span>]
			[<a href="/account-logout.php" id="logout">Log out</a>]
		</div>
		<div class="floatleft">
			<div id="ul_dl_stats" class="small">
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
			</div>
		</div>
		<div class="clear"></div>
	</p>';
end_block();
}
?>
