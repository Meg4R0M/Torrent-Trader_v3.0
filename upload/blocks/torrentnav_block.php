<?php
if (strpos($_SERVER['REQUEST_URI'], '?') !== false){
	$scripturl =  substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
}else{
	$scripturl = $_SERVER['REQUEST_URI'];
}
$pageurl = $_SERVER['REQUEST_URI'];

if ($scripturl == "/torrents.php" || $scripturl == "/torrents-today.php"){
	echo '<form method="post" action="">';

		begin_block("Display Options");
			echo '<div id="displayOptions" class="">
				<p>
					<table cellpadding="3" cellspacing="0">
						<tr>
							<td>'.T_("SORT_BY").':</td>
							<td>
								<select name="sortOptions[sortBy]" id="cat_content_right_column">
									<option value="id"'.($_GET["sort"] == "id" ? " selected='selected'" : "").'>'.T_("ADDED").'</option>
									<option value="name"'.($_GET["sort"] == "name" ? " selected='selected'" : "").'>'.T_("NAME").'</option>
									<option value="comments"'.($_GET["sort"] == "comments" ? " selected='selected'" : "").'>'.T_("COMMENTS").'</option>
									<option value="size"'.($_GET["sort"] == "size" ? " selected='selected'" : "").'>'.T_("SIZE").'</option>
									<option value="times_completed"'.($_GET["sort"] == "times_completed" ? " selected='selected'" : "").'>'.T_("COMPLETED").'</option>
									<option value="seeders"'.($_GET["sort"] == "seeders" ? " selected='selected'" : "").'>'.T_("SEEDERS").'</option>
									<option value="leechers"'.($_GET["sort"] == "leechers" ? " selected='selected'" : "").'>'.T_("LEECHERS").'</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Sort Order:</td>
							<td>
								<select name="sortOptions[sortOrder]" id="cat_content_right_column">
									<option selected="selected" value="asc"'.($_GET["order"] == "asc" ? " selected='selected'" : "").'>'.T_("ASCEND").'</option>
									<option value="desc"'.($_GET["order"] == "desc" ? " selected='selected'" : "").'>'.T_("DESCEND").'</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
								<input type="submit" value="Apply" class="submit" />
								<input type="reset" value="Reset" class="submit" />
							</td>
						</tr>
					</table>
				</p>
			</div>';

		end_block();
	
	echo '</form>';

	$res = SQL_Query_exec("SELECT COUNT(*) FROM torrents LEFT JOIN categories ON category = categories.id $where");
	$row = mysqli_fetch_row($res);
	$count = $row[0];

	if ($count) {
		echo '<div class="widget2">
			<h4>
				<div class="floatright">
					<img src="http://templateshares-ue.net/tsue/styles/default/jqueryTools/prev.png" alt="" title="" id="sItemPrev" class="middle pointer" />&nbsp;&nbsp;<img src="http://templateshares-ue.net/tsue/styles/default/jqueryTools/next.png" alt="" title="" id="sItemNext" class="middle pointer" />&nbsp;&nbsp;<img src="http://templateshares-ue.net/tsue/styles/default/buttons/list.png" alt="" title="" id="recentTorrentsSwitch" class="middle pointer" /> 
				</div>
				<img src="http://templateshares-ue.net/tsue/styles/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="recentTorrents" id="toggle" class="middle pointer" /> Recent Torrents
			</h4>
			<div id="recentTorrents" class="">
				<div class="scrollable vertical">
					<div class="items">
						<div class="widthSidebar">';
							?><a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;action=details&amp;tid=1" title=""><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_images/s/shopping.jpg" title="bitdefender_tsecurity.exe<br>Seeders: 0 / 
				Leechers: 0 / 
				Size: 6.7 MB /  
				Completed: 14<br>Category: Applications > PC Applications<br>Uploaded 28-04-2015 06:08 by System<br><img src='http://templateshares-ue.net/tsue/styles/default/torrents/sticky.png' alt='' title='Sticky Torrent' class='middle' id='' rel='resized_by_tsue' />" alt="bitdefender_tsecurity.exe<br>Seeders: 0 / 
				Leechers: 0 / 
				Size: 6.7 MB /  
				Completed: 14<br>Category: Applications > PC Applications<br>Uploaded 28-04-2015 06:08 by System<br><img src='http://templateshares-ue.net/tsue/styles/default/torrents/sticky.png' alt='' title='Sticky Torrent' class='middle' id='' rel='resized_by_tsue' />" rel="resized_by_tsue" /></a><?php
						echo '</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>';
	}
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
	?><div class="widget">
	<h4>
		<img src="http://templateshares-ue.net/tsue/styles/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="findTorrents" id="toggle" class="middle pointer" /> Find Torrents
	</h4>
	
	<div id="findTorrents" class="">
		<p>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&action=todays_torrents&pid=10">Today's Torrents</a>
		</p>

		<p>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&action=torrents_of_this_week&pid=10">Torrents of This Week</a>
		</p>

		<p>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&action=torrents_of_this_month&pid=10">Torrents of This Month</a>
		</p>
	</div>
</div><div class="widget">
	<h4>
		<img src="http://templateshares-ue.net/tsue/styles/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="weakTorrents" id="toggle" class="middle pointer" /> Weak Torrents
	</h4>
	
	<p id="weakTorrents" class="">
		The torrents shown below needing seeds.
		<span class="weakTorrents small">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;action=details&amp;tid=1">bitdefender_tsecurity.exe</a>
	<br />
	Uploaded 28-04-2015 06:08 by xam
	<br />
	Seeders: 0 / 
	Leechers: 0 / 
	Size: 6.7 MB
</span>
	</p>
</div><div class="widget">
	<h4>
		<img src="http://templateshares-ue.net/tsue/styles/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="recentTorrentGenres" id="toggle" class="middle pointer" />  Torrent Genres
	</h4>
	<div id="recentTorrentGenres" class=""><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Action"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/action.png" alt="Action" title="Action" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Adventures"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/adventures.png" alt="Adventures" title="Adventures" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Comedy"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/comedy.png" alt="Comedy" title="Comedy" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Comedy2"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/comedy2.png" alt="Comedy2" title="Comedy2" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Crime"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/crime.png" alt="Crime" title="Crime" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Detective"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/detective.png" alt="Detective" title="Detective" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Documentary"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/documentary.png" alt="Documentary" title="Documentary" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Drama"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/drama.png" alt="Drama" title="Drama" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Fantasy"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/fantasy.png" alt="Fantasy" title="Fantasy" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Historical"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/historical.png" alt="Historical" title="Historical" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Horror"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/horror.png" alt="Horror" title="Horror" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Music"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/music.png" alt="Music" title="Music" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Novel"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/novel.png" alt="Novel" title="Novel" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Sci_fi"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/sci_fi.png" alt="Sci_fi" title="Sci_fi" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Sport"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/sport.png" alt="Sport" title="Sport" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Triller"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/triller.png" alt="Triller" title="Triller" class="middle" /></a>
</div><div class="genreIcon">
	<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;genre=Western"><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_genres/western.png" alt="Western" title="Western" class="middle" /></a>
</div></div>
	<div class="clear"></div>
</div><?php


}
?>
