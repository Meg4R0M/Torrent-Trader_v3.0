<?php
require_once("backend/functions.php");
dbconn(true);

stdhead(T_("HOME"));

//Check
if (file_exists("check.php") && $CURUSER["class"] == 7){
	show_error_msg("WARNING", "Check.php still exists, please delete or rename the file as it could pose a security risk<br /><br /><a href='check.php'>View Check.php</a> - Use to check your config!<br /><br />",0);
}

//Site Notice
if (!$CURUSER){
	if ($site_config['SITENOTICEON']){
		echo '<div style="background: #f8f8f8; color: #000; padding: 10px; text-align: center; border: 1px solid #ddd; margin-bottom: 10px;">
			'.$site_config['SITENOTICE'].'
		</div>';
	}
}

//Advertisements
echo '<div class="widget" id="advertisements">
	<h4>
		<img src="http://templateshares-ue.net/tsue/styles/default/forums/mix/bullet_toggle_minus.png" alt="" title="" rel="showAds" id="toggle" class="middle pointer" /> 
		<span rel="adsTitle">TTv3: Social Engagement</span>
	</h4>
	
	<div id="showAds" class="">
		<div class="ads" data-adNumber="1" style="padding: 5px 10px;">
			<div class="adsTitle hidden">TTv3: Social Engagement</div>
			<div class="adsContent">
				<div class="features">
					<img style="float: left; margin-right: 5px;" title="Social Engagement" src="http://templateshares-ue.net/images/social.png" alt="Social Engagement" /> Keep your members coming back by letting them earn points for posting comments, torrents... An intuitive "TTv3 like" system makes members feel appreciated for their contributions, while integration with Facebook, Twitter and Google +1 allows easy sharing.<br />
				</div>
			</div>
		</div>
		<div class="ads" data-adNumber="2" style="padding: 5px 10px;display: none;">
			<div class="adsTitle hidden">TTv3: Great Plugins</div>
			<div class="adsContent">
				<div class="features">
					<img style="float: left; margin-right: 5px;" title="Great Plugins" src="http://templateshares-ue.net/images/plugins.png" alt="Great Plugins" />TTv3 is built to be the most extensible and flexible CMS software ever. There are many build-in plugins available in the vanilla source, such as Shoutbox, Recent News, Recent Threads, Recent Torrents, Poll and much more..<br />
				</div>
			</div>
		</div>
		<div class="ads" data-adNumber="3" style="padding: 5px 10px;display: none;">
		<div class="adsTitle hidden">TTv3: Easy Styling</div>
			<div class="adsContent">
				<div class="features">
					<img style="float: left; margin-right: 5px;" title=" Easy Styling" src="http://templateshares-ue.net/images/styles.png" alt=" Easy Styling" /> You can make further changes through an extensive TTv3 style manager, or edit HTML and CSS in your favorite editor. No code changes necessary!<br />
				</div>
			</div>
		</div>
		<div class="ads" data-adNumber="4" style="padding: 5px 10px;display: none;">
			<div class="adsTitle hidden">TTv3: Alerts</div>
			<div class="adsContent">
				<div class="features">
					<img style="float: left; margin-right: 5px;" title="Alerts" src="http://templateshares-ue.net/images/alerts.png" alt="Alerts" /> Make it easy for members to stay up to date with updates that are applicable to them. They\'ll receive alerts when someone quotes their post or responds to a status update, when they receive a new message, and more.<br />
				</div>
			</div>
		</div>
		<div class="ads" data-adNumber="5" style="padding: 5px 10px;display: none;">
			<div class="adsTitle hidden">TTv3: Affordable</div>
			<div class="adsContent">
				<div class="featureText">Pay Once and use TTv3 Free for Lifetime! No recurring billing!</div>
			</div>
		</div>
		<div class="ads" data-adNumber="6" style="padding: 5px 10px;display: none;">
			<div class="adsTitle hidden">TTv3: Scalable</div>
			<div class="adsContent">TTv3 works on a small VPS server or a Dedicated server.<br /></div>
		</div>
		<div class="ads" data-adNumber="7" style="padding: 5px 10px;display: none;">
			<div class="adsTitle hidden">TTv3: Mobile Support</div>
			<div class="adsContent">TTv3 works with mobile devices. This includes IOS and Android.<br /></div>
		</div>
		<div class="ads" data-adNumber="8" style="padding: 5px 10px;display: none;">
			<div class="adsTitle hidden">TTv3: Technical support</div>
			<div class="adsContent">We make our customers happy by providing superior support.<br /></div>
		</div>
		<div class="ads" data-adNumber="9" style="padding: 5px 10px;display: none;">
			<div class="adsTitle hidden">TTv3: Development</div>
			<div class="adsContent">We\'re constantly updating TTv3 to improve features and performance.<br /></div>
		</div>
	</div>
	<br />
</div>';
?><script type="text/javascript">
	var totalAds = 9, rotate, currentAd = 1, nextAd = 2;
	function rotateAds(){
		if(nextAd > totalAds){
			nextAd = 1;
		}
		$('div[data-adNumber="'+currentAd+'"]').fadeOut("slow", function(){
			$('span[rel="adsTitle"]').html($('div[data-adNumber="'+nextAd+'"] .adsTitle').html());
			$('div[data-adNumber="'+nextAd+'"]').fadeIn("slow", function(){				
				currentAd = nextAd;
				nextAd++;
			});
		});
	}

	window.onload=function(){
		rotate = setInterval(function(){rotateAds()}, 5000);
		$("#advertisements").mouseover(function(){
			clearInterval(rotate);
		}).mouseout(function(){
			rotate = setInterval(function(){rotateAds()}, 5000);
		});
	};
</script>
<?php


//Site News
if ($site_config['NEWSON'] && $CURUSER['view_news'] == "yes"){
	begin_frame(T_("NEWS"));
	$res = SQL_Query_exec("SELECT news.id, news.title, news.added, news.body, users.username FROM news LEFT JOIN users ON news.userid = users.id ORDER BY added DESC LIMIT 10");
	if (mysqli_num_rows($res) > 0){
		while($array = mysqli_fetch_assoc($res)){
            if (!$array["username"])
                 $array["username"] = T_('UNKNOWN_USER');

			$numcomm = get_row_count("comments", "WHERE news='".$array['id']."'");
			
			echo '<p>
				<span class="clickable" id="news_item" rel="'.$array['id'].'">
					<span id="news_item_title_'.$array['id'].'">'.$array['title'].'</span>
				</span><br />
				<span>'.format_comment($array["body"]).'</span><br />
				<span class="small">'.date("d-M-y", utc_to_tz_time($array['added'])).'</span>
			</p>';
		}
	}else{
		echo "<p>".T_("NO_NEWS")."</p>";
	}
	end_frame();
}

//Polls
begin_frame(T_("POLL"));
if (!function_exists("srt")) {
	function srt($a,$b){
		if ($a[0] > $b[0]) return -1;
		if ($a[0] < $b[0]) return 1;
		return 0;
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $CURUSER && $_POST["act"] == "takepoll"){
	$choice = $_POST["choice"];
	if ($choice != "" && $choice < 256 && $choice == floor($choice)){
		$res = SQL_Query_exec("SELECT * FROM polls ORDER BY added DESC LIMIT 1");
		$arr = mysqli_fetch_assoc($res) or show_error_msg(T_("ERROR"), "No Poll", 1);

		$pollid = $arr["id"];
		$userid = $CURUSER["id"];

		$res = SQL_Query_exec("SELECT * FROM pollanswers WHERE pollid=$pollid && userid=$userid");
		$arr = mysqli_fetch_assoc($res);

		if ($arr){
			show_error_msg(T_("ERROR"), "You have already voted!", 0);
		}else{
			SQL_Query_exec("INSERT INTO pollanswers VALUES(0, $pollid, $userid, $choice)");
			if (mysqli_affected_rows($GLOBALS["___mysqli_ston"]) != 1)
				show_error_msg(T_("ERROR"), "An error occured. Your vote has not been counted.", 0);
		}
	}else{
		show_error_msg(T_("ERROR"), "Please select an option.", 0);
	}
}

// Get current poll
if ($CURUSER){
	$res = SQL_Query_exec("SELECT * FROM polls ORDER BY added DESC LIMIT 1");

	if($pollok=(mysqli_num_rows($res))) {
		$arr = mysqli_fetch_assoc($res);
		$pollid = $arr["id"];
		$userid = $CURUSER["id"];
		$question = $arr["question"];

		$o = array($arr["option0"], $arr["option1"], $arr["option2"], $arr["option3"], $arr["option4"],
    	$arr["option5"], $arr["option6"], $arr["option7"], $arr["option8"], $arr["option9"],
    	$arr["option10"], $arr["option11"], $arr["option12"], $arr["option13"], $arr["option14"],
    	$arr["option15"], $arr["option16"], $arr["option17"], $arr["option18"], $arr["option19"]);

		// Check if user has already voted
  		$res = SQL_Query_exec("SELECT * FROM pollanswers WHERE pollid=$pollid AND userid=$userid");
  		$arr2 = mysqli_fetch_assoc($res);
	}

	//Display Current Poll
	if($pollok) {
		echo '<div id="show_poll">
			<p class="clickable">'.$question.'</p>';
			$voted = $arr2;

			// If member has voted already show results
			if ($voted) {
				if ($arr["selection"])
					$uservote = $arr["selection"];
				else
					$uservote = -1;

				// we reserve 255 for blank vote.
				$res = SQL_Query_exec("SELECT selection FROM pollanswers WHERE pollid=$pollid AND selection < 20");

				$tvotes = mysqli_num_rows($res);

				$vs = array(); // array of
				$os = array();

				// Count votes
				while ($arr2 = mysqli_fetch_row($res))
				$vs[$arr2[0]] += 1;

				reset($o);
				for ($i = 0; $i < count($o); ++$i)
				if ($o[$i])
					$os[$i] = array($vs[$i], $o[$i]);

				// now os is an array like this: array(array(123, "Option 1"), array(45, "Option 2"))
				if ($arr["sort"] == "yes")
					usort($os, srt);

				echo '<table cellpadding="5" cellspacing="0" border="0">';
				$i = 0;

				while ($a = $os[$i]){
					if ($i == $uservote)
						$a[1] .= "&nbsp;*";
					if ($tvotes == 0)
						$p = 0;
					else
						$p = round($a[0] / $tvotes * 100);
					$bar = $i + 1;
					if ($a[0] == 0)
						$voters = 0;
					else
						$voters = number_format($a[0]);
					print("<tr>
					<td>".format_comment($a[1])."</td><td style='width: 110px;'><img src='/images/poll/bar".$bar."-1.gif' alt='' width='3' height='10' /><img src='/images/poll/bar".$bar.".gif' title='".$voters." ".T_("VOTES")."' alt='' width='".($p / 2)."' height='10' /><img src='/images/poll/bar".$bar."-r.gif' alt='' width='3' height='10' /></td><td style='width: 20px;'>$p%</td></tr>\n");
					++$i;
				}

				print("</table>\n");
				echo '</div></form>';

			}else{//User has not voted, show options

				echo '<form method="post" action="'.encodehtml($_SERVER["REQUEST_URI"]).'">
					<input type="hidden" name="act" value="takepoll" />
					<p>';
						$i = 0;

						while ($a = $o[$i]){
							echo '<input type="checkbox" name="choice" value="'.$i.'" id="pcount_'.$i.'" /><label for="pcount_'.$i.'">'.format_comment($a).'</label><br />';
							++$i;
						}
						print("<input type='checkbox' name='choice' value='255' id='pcount_255' /><label for='pcount_255'>".T_("BLANK_VOTE")."</label>");
					echo '</p>';
					echo '<p><input type="submit" value="'.T_("VOTE").' &rarr;" id="vote-button" class="submit" /></p>';
				print("</div></form>");
			}
	}else{
  		echo"<br /><br /><center>No Active Polls</center><br /><br />\n";
	}
}else{
	echo"<br /><br /><center>".T_("POLL_MUST_LOGIN")."</center><br /><br />\n";
}
end_frame();

//Latest Torrents
begin_frame(T_("LATEST_TORRENTS"));

if ($site_config["MEMBERSONLY"] && !$CURUSER) {
	echo "<br /><br /><center><b>".T_("BROWSE_MEMBERS_ONLY")."</b></center><br /><br />";
} else {
	$query = "SELECT torrents.id, torrents.anon, torrents.announce, torrents.category, torrents.leechers, torrents.nfo, torrents.seeders, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.comments, torrents.numfiles, torrents.filename, torrents.owner, torrents.external, torrents.freeleech, categories.name AS cat_name, categories.image AS cat_pic, categories.parent_cat AS cat_parent, users.username, users.privacy, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id WHERE visible = 'yes' AND users.banned = 'no' ORDER BY id DESC LIMIT 25";
	$res = SQL_Query_exec($query);
	if (mysqli_num_rows($res)) {
		torrenttable($res);
	}else {
		show_error_msg(T_("ERROR"), T_("NO_UPLOADS"), 0);
	}
	if ($CURUSER)
		SQL_Query_exec("UPDATE users SET last_browse=".gmtime()." WHERE id=$CURUSER[id]");
}

end_frame();

if ($site_config['DISCLAIMERON']){
	begin_frame(T_("DISCLAIMER"));
	echo '<p>'.T_("DISCLAIMERTXT").'</p>';
	end_frame();
}

stdfoot();
?>
