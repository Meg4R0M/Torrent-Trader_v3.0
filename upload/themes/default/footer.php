								<?php
								if ($site_config["MIDDLENAV"]){
									middleblocks();
								} //MIDDLENAV ON/OFF END

							echo '</div>
						</div>
						<div id="sidebar">';
						//RIGHTBLOCKS
						if ($site_config["RIGHTNAV"]){
							rightblocks();
						}

						echo '</div>
					</div>
					<div class="clear"></div>';
					//end #inner
				echo '</div>
			</div>';
			//end #wrapper
		
			//start #footer
			echo '<div id="footer">
				<div class="wrap">';
					// ***************************************************************************************************************************************
					//            PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
					// ***************************************************************************************************************************************
					print ("<p>Powered by <a href=\"http://www.torrenttrader.org\" target=\"_blank\">TorrentTrader v".$site_config["ttversion"]."</a> - ");
					$totaltime = array_sum(explode(" ", microtime())) - $GLOBALS['tstart'];
					printf("Page generated in %f", $totaltime);
					print (" - Modded By: <a href=\"http://meg4r0m.ovh\" target=\"_blank\">Meg4R0M</a></p>");
					//
					// ***************************************************************************************************************************************
					//            PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
					// ***************************************************************************************************************************************
				echo '</div>
			</div>';
			?><!-- end #footer -->

	
		<script type="text/javascript">
		//<![CDATA[
			var TSUEPhrases = 
{
	months: "January,February,March,April,May,June,July,August,September,October,November,December",
	shortMonths: "Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec",
	days: "Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday",
	shortDays: "Sun,Mon,Tue,Wed,Thu,Fri,Sat",

	relative_a_moment_ago: "A moment ago",
	relative_one_minute_ago: "One minute ago",
	relative_x_minutes_ago: "{1} minutes ago",
	relative_one_hour_ago: "One hour ago",
	relative_x_hours_ago: "{1} hours ago",
	relative_one_day_ago: "One day ago",
	relative_x_days_ago: "{1} days ago",

	button_save: "Save",
	button_update: "Update",
	button_cancel: "Cancel",
	button_okay: "Okay",
	button_preview: "Preview",

	message_saved: "Your changes have been saved.",
	message_posted: "Your message have been posted.",
	message_deleted: "Your message have been deleted.",
	message_required_fields_error: "Please complete all required fields.",

	website_active_admin_alert: "Alert! Website is currently closed!",
	ajax_error: "Ajax Error! Please try again later.",
	confirmation_required: "Confirmation Required",
	confirm_delete_message: "Are you sure that you want to delete this message(s)?",
	confirm_delete_message_global: "Are you sure that you want to delete this content?",
	login_required: "You must be logged-in to do that.",
	an_error_hash_occurded: "An error has occured!",
	loading: "Please wait, loading...",

	shoutbox_inacitivityWarning: "We have stopped running the Shoutbox due to your inactivity. If you are back again, please click <span id=\"imback\">here</span>.",
	you_have_new_alerts: "You have new alerts.",

	javascript_resized: "Resized from %1px * %2px to %3px * %4px. Click the image to view the full image.",
	javascript_current: "Showing image {current} of {total}"
	,show_more_torrents: "Show More Torrents"
};

var TSUESettings = 
{
	charset: "utf-8",
	memberid: "0",
	membername: "Guest",
	memberTimezone: 1,
	memberDST: 0,
	stKey: "0-1475755390-fa47ce9292f5ba0019c504f1cc77748cbc51b6e5",
	website_title: "Torrent Trader V3.0 Beta | TTV3",
	website_url: "http://ttv3.mavitrine.ovh/",
	theme_dir: "http://ttv3.mavitrine.ovh/themes/default/",
	website_active: "1",
	website_resize_images_max_width: "665",
	website_resize_images_max_height: "665",
	ajaxHolderID: 'ajaxloader',
	ajaxLoaderImage: '<img src="http://templateshares-ue.net/tsue/styles/default/ajax/fb_ajax-loader.gif" class="ajaxLoaderImage">',
	security_enable_captcha: "0",

	shoutbox_enabled: 1,
	irtm_enabled: 0,
	alerts_enabled: 0,

	pageid: 1,
	pagefile: 'home',

	isMobile: 0
};
		//]]>
		</script><?php
if (strpos($_SERVER['REQUEST_URI'], '?') !== false){
	$scripturl =  substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
}else{
	$scripturl = $_SERVER['REQUEST_URI'];
}
		?><script type="text/javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" src="../../js/tt.js"></script>
    <script type="text/javascript" src="../../js/login.js"></script><?php
if ($scripturl == "/index.php" || $scripturl == "/account-signup.php"){
		?><script type="text/javascript" src="../../js/signup.js"></script>
		<script type="text/javascript" src="../../js/forgot_password.js"></script>
		<script type="text/javascript" src="../../js/passwordstrength.js"></script><?php
}elseif ($scripturl == "/index.php"){
		?><script type="text/javascript" src="../../js/donate.js"></script>
		<script type="text/javascript" src="../../js/shoutbox.js"></script>
		<script type="text/javascript" src="../../js/news.js"></script>
		<script type="text/javascript" src="../../js/poll.js"></script>
		<script type="text/javascript" src="../../js/scrollable.js"></script><?php
}elseif ($scripturl == "/membercp.php" || $scripturl == "/account-details.php"){
		?><script type="text/javascript" src="../../js/passwordstrength.js"></script>
		<script type="text/javascript" src="../../js/membercp.js"></script>
        <script type="text/javascript" src="../../js/comments.js"></script>
        <script type="text/javascript" src="../../js/profile.js"></script>
		<script type="text/javascript" src="../../js/messages.js"></script><?php
}elseif ($scripturl == "/faq.php"){
	?><script type="text/javascript" src="../../js/faq.js"></script>
	<script type="text/javascript">function googleTranslateElementInit(){new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');}</script>
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script><?php
}

		?><script type="text/javascript" src="http://templateshares-ue.net/tsue/js/tiny_mce/tiny_mce_gzip.js?lv=2.3"></script>
		<script type="text/javascript">tinyMCE_GZ.init({plugins : '-inlinepopups,-smilies,-quote,-tsuecode,-autoresize,-autolink',themes:'tsue',languages:'',disk_cache:true,debug:false});</script>
		<script type="text/javascript">
		//<![CDATA[
			tinyMCE.addI18n('en.tsue',
{
	apply: "Apply",
	insert: "Insert",
	update: "Update",
	cancel: "Cancel",
	close: "Close",
	font_size: "Font Size",
	fontdefault: "Font Family",
	bold_desc: "Bold (CTRL+B)",
	italic_desc: "Italic (CTRL+I)",
	underline_desc: "Underline (CTRL+U)",
	striketrough_desc: "Strikethrough",
	justifyleft_desc: "Align left",
	justifycenter_desc: "Align center",
	justifyright_desc: "Align right",
	bullist_desc: "Unordered List",
	numlist_desc: "Ordered List",
	undo_desc: "Undo (CTRL+Z)",
	redo_desc: "Redo (CTRL+Y)",
	link_desc: "Insert/Edit Link",
	unlink_desc: "Unlink",
	image_desc: "Insert/Edit Image",
	removeformat_desc: "Remove formatting",
	cleanup_desc: "Cleanup messy code",
	forecolor_desc: "Select text color",
	quote: "Quote",
	code_desc: "Insert formatted code",
	smilies_desc: "Smilies"
});

tinyMCE.init({ mode:'none', theme:'tsue', language:'', plugins:'-inlinepopups,-smilies,-quote,-tsuecode,-autoresize,-autolink' });
		//]]>
		</script>

	</body>
</html>
<?php ob_end_flush(); ?> 
