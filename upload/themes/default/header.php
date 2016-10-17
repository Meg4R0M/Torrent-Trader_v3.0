<!DOCTYPE html>
<html id="TSUE" lang="en-us">
	<head>
		<!--<noscript><meta http-equiv="refresh" content="0; URL=<?php echo $site_config['SITEURL']; ?>" /></noscript>-->
		<base href="<?php echo $site_config['SITEURL']; ?>" />
		<title><?php echo $title; ?></title>
		<meta charset="<?php echo $site_config["CHARSET"]; ?>" />
		<meta name="generator" content="tt3 <?php echo $site_config['ttversion']; ?>" />
		<meta name="description" content="TTv3 | The most powerful CMS software. Fast. Secure. Social. Build your own website today." />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<meta name="MobileOptimized" content="width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<!-- CSS -->
		<!-- Theme css -->
		<link rel="stylesheet" href="/themes/default/css/style.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/jqueryTools.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/forums.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/fileuploader.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/tinymce_ui.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/thread_prefixes.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/comments.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/downloads.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/image_gallery.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/extra.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/menu.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/ajax.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/shoutbox.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/staffDropDownMenu.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/upcomingReleases.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/staffOnlineNow.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/subtitles.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/oneStepUpload.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/mixed.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/topDonors.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/fls.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/memberCard.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/plugin_torrent_categories.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/members.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/donations.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/themes/default/css/responsive.css" type="text/css" media="screen" />
		
		<link rel="Shortcut Icon" href="http://templateshares-ue.net/tsue/favicon.ico?lv=2.3" type="image/x-icon" />
		<!-- JS -->
		<script type="text/javascript" src="/backend/java_klappe.js"></script>
		<!--[if lte IE 6]>
			<script type="text/javascript" src="/themes/default/js/pngfix/supersleight-min.js"></script>
		<![endif]-->
		<script src="http://code.jquery.com/jquery-2.1.0.min.js" type="text/javascript"></script>
		<script src="/js/jquery.js" type="text/javascript"></script>
	</head>
	<?php
		$page = $_SERVER['REQUEST_URI'];
		$page = str_replace("/","",$page);
		$page = str_replace(".php","",$page);
		$page = str_replace("svn","",$page);  //-- name if tracker installed in a sub-dir 
		$page = str_replace("?search=","",$page);
		$page = $page ? $page : 'index'
	?>
	<body>
		<body>
			<!-- start info & alert boxes -->
			<!-- end info & alert boxes -->

			<!-- start #header -->
			<div id="header">
				<div class="logo">
					<a href="/index.php" alt="TorrentTrader v3 | TTv3" title="TorrentTrader v3 | TTv3"><img src="/themes/default/theme_images/logo.png" alt="TorrentTrader v3 | TTv3" title="TorrentTrader v3 | TTv3" /></a>
				</div>
				<div class="icons">				
					<ul><?php
						if (!$CURUSER){
							echo "";
						}else{
							?><li rel="globalSearchButton">
								<a href="javascript:void(0)" class="link"><img src="/themes/default/buttons/search-btn.png" class="middle image" alt="" title="" /></a>
								<div class="dropdown_column align_right">
									<div class="atext">
										<div id="boxContainer">
											<form id="globalSearchForm">
												<table cellpadding="1" cellspacing="0" border="0" width="100%">
													<tr>
														<td>
															<div>Keyword(s)</div>
															<input type="text" name="searchKeywords" value="" class="s" autocomplete="off" />
														</td>
													</tr>
													<tr>
														<td>
															<div>Search Type</div>
															<label><input type="radio" name="searchType" value="1"> Forums</label>
															<label><input type="radio" name="searchType" value="2"> Torrents</label>
															<label><input type="radio" name="searchType" value="3"> Downloads</label>
															<label><input type="radio" name="searchType" value="4"> FAQ</label>
															<label><input type="radio" name="searchType" value="5"> Members</label>
															<label><input type="radio" name="searchType" value="6"> Subtitles</label>
														</td>
													</tr>
													<tr>
														<td>
															<input type="submit" name="search" value="Search" class="submit" /> 
														</td>
													</tr>
												</table>
											</form>
										</div>
									</div>
								</div>
							</li>
							<li rel="adminCPLinks">
								<a href="javascript:void(0)" class="link"><img src="/themes/default/buttons/manage.png" class="middle image" alt="" title="" /></a>
								<div class="dropdown_column align_right">
									<div class="atext">
										<div class="memberlinks">
											<span>[<a href="/account.php">Profile</a>]</span>
											[<span id="history_link" class="clickable">History</span>]
											<span>[<a href="/account-logout.php" id="logout">Log out</a>]</span><?php
											if ($CURUSER["control_panel"]=="yes") {
												print("<span>[<a href='/admincp.php'>AdminCP</a>]</span>");
											}
										?></div>
									</div>
								</div>
							</li><?php
							//check for new pm's
							$res = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " and unread='yes' AND location IN ('in','both')") or print(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
							$arr = mysqli_fetch_row($res);
							$unreadmail = $arr[0];
							if ($unreadmail){
								echo '<li class="menu_right" rel="checkMessages">
									<strong class="alertBalloon hidden">
										<span class="count">'.$unreadmail.'</span>
										<span class="arrow"></span>
									</strong>';
							}else{
								echo '<li class="menu_right" rel="checkMessages">
									<strong class="alertBalloon hidden">
										<span class="count">0</span>
										<span class="arrow"></span>
									</strong>';
							}
								?><a href="javascript:void(0)" class="link"><img src="/themes/default/menu/messages.png" class="middle image" alt="" title="" /></a>
								<div class="dropdown_column align_right">
									<div class="atext">
										<img src="/themes/default/ajax/fb_ajax-loader.gif" alt="" title="" class="middle"/> Messages are loading... If this appears for more than 5 seconds, click <span id="reload_messages" class="clickable">here</span> to reload.
									</div>
								</div>
							</li>
							<li class="menu_right" rel="checkAlerts">
								<strong class="alertBalloon hidden">
									<span class="count">0</span>
									<span class="arrow"></span>
								</strong>
								<a href="javascript:void(0)" class="link"><img src="/themes/default/menu/alerts.png" class="middle image" alt="" title="" /></a>
								<div class="dropdown_column align_right">
									<div class="atext">
										<img src="/themes/default/ajax/fb_ajax-loader.gif" class="middle" alt="" title="" class="middle"/> Alerts are loading... If this appears for more than 5 seconds, click <span id="reload_alerts" class="clickable">here</span> to reload.
									</div>
								</div>
							</li><?php
						}
					?></ul>
				</div>
				<div class="clear"></div>
			</div>
			<!-- end #header -->

			<!-- start #wrapper -->
			<div id="wrapper">
				<div class="wrapperContent">
					<!-- start #menu -->
					<nav>
						<ul id="menu">
							<li class="nodrop"><a href="/index.php" class="link ">Home</a></li>
							<li class="nodrop"><a href="/lounge.php" class="link "><span style="color: yellow;">Shoutbox</span></a></li><?php
							if (!$CURUSER){
								echo '<li class="nodrop"><a href="/account-signup.php" class="link ">Sign-up</a></li>';
							}else{
								echo "";
							}
							?><li class="">
								<a href="javascript:void(0)" class="link drop">Forums</a>
								<div class="dropdown_column">
									<div class="column">
										<a href="/forums.php">Forums</a>
										<a href="/forums.php">What is new?</a>
										<a href="/forums.php">Search Forums</a>
										<a href="/forums.php">Today's Posts</a>
									</div>
								</div>
							</li>
							<li class="">
								<a href="/torrents.php" class="link drop">Torrents</a>
								<div class="dropdown_column">
									<div class="column">
										<a href="/torrents.php">View All Torrents</a>
										<a href="/torrents-today.php">What is New?</a>
										<a href="/torrents-upload.php">Upload a Torrent</a>
										<a href="#">Request a Torrent</a>
										<a href="#">Join Our Uploaders Team</a>
										<a href="#">Subtitles</a>
										<a href="#">Bookmarked Torrents</a>
										<a href="#">Upcoming Releases</a>
										<a href="#">Downloads</a>
									</div>
								</div>
							</li>
							<?php
							if (!$CURUSER){
								echo "";
							}else{
								?><li class="">
									<a href="/membercp.php" class="link drop">Member CP</a>
									<div class="dropdown_column">
										<div class="column">
											<a href="/membercp.php">Personal Details</a>
											<a href="/membercp.php?action=contact_details">Contact Details</a>
											<a href="/membercp.php?action=preferences">Preferences</a>
											<a href="/membercp.php?action=privacy">Privacy</a>
											<a href="/membercp.php?action=password">Password</a>
											<a href="/membercp.php?action=signature">Signature</a>
											<a href="/membercp.php?action=avatar">Avatar</a>
											<a href="/membercp.php?action=invite">Invite Friends</a>
											<a href="/membercp.php?action=upgrade">Upgrade Account</a>
											<a href="/membercp.php?action=following">People You Follow</a>
											<a href="/membercp.php?action=gallery">Image Gallery</a>
											<a href="/membercp.php?action=performance">Performance</a>
											<a href="/membercp.php?action=subscribed_threads">Subscribed Threads</a>
											<a href="/membercp.php?action=open_port_check_tool">Open Port Check Tool</a>
											<a href="/membercp.php?action=alerts">Recent Alerts</a>
											<a href="/membercp.php?action=messages">Messages</a>
										</div>
									</div>
								</li>
								<li class="nodrop"><a href="#" class="link ">Market</a></li>
								<li class="nodrop"><a href="/memberlist.php" class="link ">Members</a></li><?php
							}
							?>
							<li class="nodrop"><a href="/faq.php" class="link ">FAQ</a></li><?php
							if (!$CURUSER){
								echo "";
							}else{
								?><li class="">
									<a href="#" class="link drop">Contact</a>
									<div class="dropdown_column">
										<div class="column">
											<a href="#">Contact Staff</a>
											<a href="#">First Line Support</a>
											<a href="#">IRC</a>
										</div>
									</div>
								</li><?php
							}
							?><li class="nodrop"><a href="/staff.php" class="link ">Staff</a></li>
						</ul>
						<div class="clear"></div>
					</nav>
					<!-- end #menu -->

					<!-- start #menu -->
					<div id="responsiveMenuHider">
						<ul id="responsiveMenu" class="slimmenu">
							<li><a href="/index.php">Home</a></li>
							<li><a href="/lounge.php"><span style="color: yellow;">Shoutbox</span></a></li>
							<li><a href="/account-signup.php">Sign-up</a></li>
							<li>
								<a href="javascript:void(0)">Forums</a>
								<ul>
									<li><a href="/forums.php">Forums</a></li>
									<li><a href="/forums.php">What is new?</a></li>
									<li><a href="/forums.php">Search Forums</a></li>
									<li><a href="/forums.php">Today's Posts</a></li>
								</ul>
							</li>
							<li>
								<a href="/torrents.php">Torrents</a>
								<ul>
									<li><a href="/torrents.php">View All Torrents</a></li>
									<li><a href="/torrents-today.php">What is New?</a></li>
									<li><a href="/torrents-upload.php">Upload a Torrent</a></li>
									<li><a href="#">Request a Torrent</a></li>
									<li><a href="#">Join Our Uploaders Team</a></li>
									<li><a href="#">Subtitles</a></li>
									<li><a href="#">Bookmarked Torrents</a></li>
									<li><a href="#">Upcoming Releases</a></li>
									<li><a href="#">Downloads</a></li>
								</ul>
							</li><?php
							if (!$CURUSER){
								echo "";
							}else{
								?><li><a href="/membercp.php">Member CP</a>
									<ul>
										<li><a href="/membercp.php">Personal Details</a></li>
										<li><a href="/membercp.php?action=contact_details">Contact Details</a></li>
										<li><a href="/membercp.php?action=preferences">Preferences</a></li>
										<li><a href="/membercp.php?action=privacy">Privacy</a></li>
										<li><a href="/membercp.php?action=password">Password</a></li>
										<li><a href="/membercp.php?action=signature">Signature</a></li>
										<li><a href="/membercp.php?action=avatar">Avatar</a></li>
										<li><a href="/membercp.php?action=invite">Invite Friends</a></li>
										<li><a href="/membercp.php?action=upgrade">Upgrade Account</a></li>
										<li><a href="/membercp.php?action=following">People You Follow</a></li>
										<li><a href="/membercp.php?action=gallery">Image Gallery</a></li>
										<li><a href="/membercp.php?action=performance">Performance</a></li>
										<li><a href="/membercp.php?action=subscribed_threads">Subscribed Threads</a></li>
										<li><a href="/membercp.php?action=open_port_check_tool">Open Port Check Tool</a></li>
										<li><a href="/membercp.php?action=alerts">Recent Alerts</a></li>
										<li><a href="/membercp.php?action=messages">Messages</a></li>
									</ul>
								</li>
								<li><a href="#">Market</a></li>
								<li><a href="/memberlist.php">Members</a></li><?php
							}
							?><li><a href="/faq.php">FAQ</a></li><?php
							if (!$CURUSER){
								echo "";
							}else{
								?><li><a href="#">Top 10</a></li>
								<li><a href="#">Contact</a>
									<ul>
										<li><a href="#">Contact Staff</a></li>
										<li><a href="#">First Line Support</a></li>
										<li><a href="#">IRC</a></li>
									</ul>
								</li><?php
							}
							?><li><a href="/staff.php">Staff</a></li>
						</ul>
					</div>
					<!-- end #menu -->
				
					<!-- start #member_info_bar --><?php
					if (!$CURUSER){
						echo "";
					}else{
						$userdownloaded = mksize($CURUSER["downloaded"]);
						$useruploaded = mksize($CURUSER["uploaded"]);

						if ($CURUSER["uploaded"] > 0 && $CURUSER["downloaded"] == 0)
							$userratio = "Inf.";
						elseif ($CURUSER["downloaded"] > 0)
							$userratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);
						else
							$userratio = "---";
						$query_slots = @mysqli_fetch_row(@SQL_Query_exec("SELECT COUNT(DISTINCT torrent) FROM peers WHERE userid = $CURUSER[id] AND seeder='no'"));
						$maxslot = avail_slots($CURUSER["id"], $CURUSER["class"]);
						$slots = number_format($maxslot) . "/" . number_format($query_slots[0]);
						$invites = $CURUSER["invites"];
						$seedbonus = $CURUSER["seedbonus"];
						
						echo '<div id="member_info_bar">
							<div class="showStats">
								<img src="themes/default/info_bar/uploaded.png" alt="" title="Uploaded" /> '.$userdownloaded.'
								<img src="themes/default/info_bar/downloaded.png" alt="" title="Downloaded" /> '.$useruploaded.'
								<img src="themes/default/info_bar/buffer.png" alt="" title="Buffer" /> 0';
								// CHANGER LES SPAN POUR LE RATIO COLOR -->
								echo '<img src="themes/default/info_bar/ratio.png" alt="" title="Ratio" /> <span class="ratioNull">'.$userratio.'</span>
								<img src="themes/default/info_bar/slots.png" alt="" title="Max.Slots" /> '.$slots.'
								<img src="themes/default/info_bar/points.png" alt="" title="Points" /> <a href="#">0</a>
								<img src="themes/default/info_bar/posts.png" alt="" title="Total Posts" /> 0
								<img src="themes/default/info_bar/invites.png" alt="" title="Total Invites" /> <a href="/invite.php">'.$invites.'</a>
								<img src="themes/default/info_bar/warns.png" alt="" title="Total Warns" /> <span id="total_warns" class="clickable">0</span>
								<img src="themes/default/info_bar/hitrun.png" alt="" title="Hit & Run Warns" /> <span id="hitrun_warns" class="clickable">0</span>
							</div>
						</div>';
					}
					?><!-- end #member_info_bar -->

					<!-- start #breadcrumb -->
					<div class="rcrumbs" id="breadcrumbs">
						<ul><?php
                            if ($page == "forums")
                                echo '<li><a href="/index.php">Home</a><span class="divider">></span></li><li><a href="/forums.php">Forums</a></li>';
                            else
                                echo '<li><a href="/index.php">'.$page.'</a></li>';
						?></ul>
					</div>
					<!-- end #breadcrumb -->

					<!-- start #inner -->
					<div id="inner">
						<div id="container"><?php
                            if (strpos($_SERVER['REQUEST_URI'], '?') !== false){
                                $scripturl =  substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
                            }else{
                                $scripturl = $_SERVER['REQUEST_URI'];
                            }
                            
                            if ($scripturl != "/forums.php"){
                                echo '<div id="content" class="marginRightClass">';
                            }else{
                                echo '<div id="content" class="">';
                            }
    ?>
