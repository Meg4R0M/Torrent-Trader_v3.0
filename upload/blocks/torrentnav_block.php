<?php
if (strpos($_SERVER['REQUEST_URI'], '?') !== false){
	$scripturl =  substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
}else{
	$scripturl = $_SERVER['REQUEST_URI'];
}
$pageurl = $_SERVER['REQUEST_URI'];

if ($scripturl == "/torrents.php"){
	?><form method="post" action=""><?php

	begin_block("Display Options");
?><div id="displayOptions" class="">
		<p>
			<table cellpadding="3" cellspacing="0">
				<tr>
					<td>Sort By:</td>
					<td>
						<select name="sortOptions[sortBy]" id="cat_content_right_column">
							<option value="added" selected="selected">Added</option>
							<option value="seeders">Seeders</option>
							<option value="leechers">Leechers</option>
							<option value="size">Size</option>
							<option value="times_completed">Completed</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Sort Order:</td>
					<td>
						<select name="sortOptions[sortOrder]" id="cat_content_right_column">
							<option value="desc" selected="selected">Descending</option>
							<option value="asc">Ascending</option>
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
	</div>
<?php

	end_block();	
?></form>
<div class="widget2">
<h4>
		<div class="floatright">
			<img src="http://templateshares-ue.net/tsue/styles/default/jqueryTools/prev.png" alt="" title="" id="sItemPrev" class="middle pointer" />&nbsp;&nbsp;<img src="http://templateshares-ue.net/tsue/styles/default/jqueryTools/next.png" alt="" title="" id="sItemNext" class="middle pointer" />&nbsp;&nbsp;<img src="http://templateshares-ue.net/tsue/styles/default/buttons/list.png" alt="" title="" id="recentTorrentsSwitch" class="middle pointer" /> 
		</div>
		<img src="http://templateshares-ue.net/tsue/styles/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="recentTorrents" id="toggle" class="middle pointer" /> Recent Torrents
	</h4>
	<div id="recentTorrents" class="">
		<div class="scrollable vertical">
			<div class="items">
				<div class="widthSidebar"><a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10&amp;action=details&amp;tid=1" title=""><img src="http://templateshares-ue.net/tsue/data/torrents/torrent_images/s/shopping.jpg" title="bitdefender_tsecurity.exe<br>Seeders: 0 / 
				Leechers: 0 / 
				Size: 6.7 MB /  
				Completed: 14<br>Category: Applications > PC Applications<br>Uploaded 28-04-2015 06:08 by System<br><img src='http://templateshares-ue.net/tsue/styles/default/torrents/sticky.png' alt='' title='Sticky Torrent' class='middle' id='' rel='resized_by_tsue' />" alt="bitdefender_tsecurity.exe<br>Seeders: 0 / 
				Leechers: 0 / 
				Size: 6.7 MB /  
				Completed: 14<br>Category: Applications > PC Applications<br>Uploaded 28-04-2015 06:08 by System<br><img src='http://templateshares-ue.net/tsue/styles/default/torrents/sticky.png' alt='' title='Sticky Torrent' class='middle' id='' rel='resized_by_tsue' />" rel="resized_by_tsue" /></a>
				</div>
			</div></div>
		<div class="clear"></div>
	</div>
</div><div class="widget">
	<h4>
		<span class="floatright">
			<img src="http://templateshares-ue.net/tsue/styles/default/buttons/refresh.png" alt="Refresh" title="Refresh" rel="refreshOnlineMembers" class="clickable middle" />
		</span>
		<img src="http://templateshares-ue.net/tsue/styles/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="membersOnlineNow" id="toggle" class="middle pointer" />  <a href="http://templateshares-ue.net/tsue/?p=online&amp;pid=17">Members Online Now</a>
	</h4>
	
	<div id="membersOnlineNow" class="">
		<p id="onlineMembersList"><br />Online now: 0 (Members: 0, Guests: 0)</p>
		<p><span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  color: rgb(15, 57, 178)" title="Super Moderators">&nbsp;</span> <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  rgb(106, 179, 240)" title="Moderators">&nbsp;</span> <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #6d6c6c" title="Registered Users">&nbsp;</span> <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #cccccc" title="Members Awaiting Email Confirmation">&nbsp;</span> <span style="padding: 0 7px; border: 1px solid #000; margin: 1px; background:  #cccccc" title="Members Awaiting Moderation">&nbsp;</span></p>
	</div>
</div><div class="widget">
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
