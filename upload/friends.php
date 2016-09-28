<?php
#================================#
#       TorrentTrader 2.08       #
#  http://www.torrenttrader.org  #
#--------------------------------#
#       Modified by BigMax       #
#================================#

require_once("backend/functions.php");
dbconn(false);
loggedinonly();

	$userid = (int) $_GET['id'];
	$action = $_GET['action'];

	if (!$userid)
		$userid = $CURUSER['id'];

	if (!is_valid_id($userid))
		show_error_msg("Error", "Invalid ID $userid.", 1);

	if ($userid != $CURUSER["id"])
		show_error_msg("Error", "Access denied", 1);

	$res = SQL_Query_exec("SELECT * FROM users WHERE id=$userid");
	$user = mysql_fetch_array($res) or show_error_msg("Error", "No user with this ID", 1);

	//===| Action: Add |================================================================//

	if ($action == 'add') {
		$targetid = (int) $_GET['targetid'];
		$type     = $_GET['type'];
		
		if (!is_valid_id($targetid))
			show_error_msg("Error", "Invalid ID $$targetid.", 1);
		
		if ($type == 'friend') {
			$table_is = $frag = 'friends';
			$field_is = 'friendid';
		} elseif ($type == 'block') {
			$table_is = $frag = 'blocked';
			$field_is = 'blockid';
		} else
			show_error_msg("Error", "Unknown type $type", 1);
		
		$r = SQL_Query_exec("SELECT id FROM $table_is WHERE userid=$userid AND $field_is=$targetid");
		if (mysql_num_rows($r) == 1)
			show_error_msg("Error", "User ID $targetid is already in your $table_is list.", 1);
		
		SQL_Query_exec("INSERT INTO $table_is VALUES (0,$userid, $targetid)");
		header("Location: ".$site_config['SITEURL']."/friends.php?id=$userid#$frag");
		die;
	}

	//===| Action: Delete |================================================================//

	if ($action == 'delete') {
		$targetid = (int) $_GET['targetid'];
		$sure = htmlentities($_GET['sure']);
		$type = htmlentities($_GET['type']);
		
		if ($type != "block") { $typ = "friend from list"; } else { $typ = "blocked user from list"; }
		
		if (!is_valid_id($targetid))
			show_error_msg("Error", "Invalid ID $userid.", 1);
		
		if (!$sure)
			show_error_msg("Delete $type", "<div style='margin-top:10px; margin-bottom:10px' align='center'>Do you really want to delete this $typ? &nbsp; \n"."<a href=?id=$userid&action=delete&type=$type&targetid=$targetid&sure=1>Yes</a> | <a href=friends.php>No</a></div>", 1);
		
		if ($type == 'friend') {
			SQL_Query_exec("DELETE FROM friends WHERE userid=$userid AND friendid=$targetid");
			if (mysql_affected_rows() == 0)
				show_error_msg("Error", "No friend found with ID $targetid", 1);
			$frag = "friends";
		} elseif ($type == 'block') {
			SQL_Query_exec("DELETE FROM blocked WHERE userid=$userid AND blockid=$targetid");
			if (mysql_affected_rows() == 0)
				show_error_msg("Error", "No block found with ID $targetid", 1);
			$frag = "blocked";
		} else
			show_error_msg("Error", "Unknown type $type", 1);
		
		header("Location: ".$site_config['SITEURL']."/friends.php?id=$userid#$frag");
		die;
	}

	//===| Main Body |================================================================//

	stdhead("Personal lists for ".$user['username']);
	begin_frame("Personal lists for ".class_user($user[username])."");
	
	print("<table class=table_table align=center width=90% border=0 cellspacing=0 cellpadding=0><tr><td>");
	print("<div style='margin-top:20px; margin-bottom:10px' align=left><font size=2><b>List of friends</b></font></div>\n");
	print("<table align=center width=100% border=1 cellspacing=0 cellpadding=5><tr><td class=table_col1>");

	$i = 0;

	$res = SQL_Query_exec("SELECT f.friendid as id, u.username AS name, u.class, u.avatar, u.title, u.enabled, u.last_access FROM friends AS f LEFT JOIN users as u ON f.friendid = u.id WHERE userid=$userid ORDER BY name");
	if (mysql_num_rows($res) == 0) {
		$friends = "Your friends list is empty!";
	} else {
		while ($friend = mysql_fetch_array($res)) {
			$title = $friend["title"];
			if (!$title)
				$title = get_user_class_name($friend["class"]);
			
			$body  = "<a href=account-details.php?id=".$friend['id']."><b>".class_user($friend['name'])."</b></a> &nbsp;
			<a href=mailbox.php?compose&amp;id=".$friend['id']."><img src=images/button_pm.gif title=Send&nbsp;PM border=0></a>&nbsp;
			<a href=friends.php?id=$userid&action=delete&type=friend&targetid=".$friend['id']."><img src=images/close.png title=Remove border=0></a>
			<div style='margin-top:10px; margin-bottom:2px'>Last seen: ".date("<\\b>d.M.Y<\\/\\b> H:i", utc_to_tz_time($friend['last_access']))."</div>
			[<b>".get_elapsed_time(sql_timestamp_to_unix_timestamp($friend[last_access]))." ago</b>]";
			
			$avatar = htmlspecialchars($friend["avatar"]);
			if (!$avatar)
				$avatar = "/images/default_avatar.png";
			if ($i % 2 == 0)
				print("<table width=100% style='padding: 0px'><tr><td style='padding: 5px' width=50% align=center>");
			else
				print("<td style='padding: 5px' width=50% align=center>");
				print("<table class=table_table width=100% height=75px>");
				print("<tr valign=top><td width=75 align=center style='padding: 0px'>" . ($avatar ? "<div style='width:150px; overflow: hidden'><img width=150px src=\"$avatar\"></div>" : "") . "</td><td>\n");
				print("<table class=table_table>");
				print("<tr><td style='padding: 5px' width=100%>$body</td></tr>\n");
				print("</table>");
				print("</td></tr>");
				print("</td></tr></table>\n");
			
			if ($i % 2 == 1)
				print("</td></tr></table>\n");
			else
				print("</td>\n");
			$i++;
		}
	}
	
	if ($i % 2 == 1)
		print("<td width=50%>&nbsp;</td></tr></table>\n");
		print($friends);
		print("</td></tr></table>\n");

	$res = SQL_Query_exec("SELECT b.blockid as id, u.username AS name, u.enabled, u.last_access FROM blocked AS b LEFT JOIN users as u ON b.blockid = u.id WHERE userid=$userid ORDER BY name");
	if (mysql_num_rows($res) == 0) {
		$blocked = "Your blocked users list is empty!";
	} else {
		$i = 0;
		$blocked = "<table width=100% cellspacing=0 cellpadding=0>";
		while ($block = mysql_fetch_array($res)) {
			if ($i % 6 == 0)
				$blocked .= "<tr>";
				$blocked .= "<td style='border:none; padding:4px; spacing:0px'><a href=account-details.php?id=".$block['id']."><b>".class_user($block['name'])."</b></a> <a href=friends.php?id=$userid&action=delete&type=block&targetid=".$block['id']."><img src=images/delete.png title=Remove border=0></a></td>";
			if ($i % 6 == 5)
				$blocked .= "</tr>";
			$i++;
		}
		print("</table>\n");
	}
	
	print("<table class=table_table align=center width=90% border=0 cellspacing=0 cellpadding=0><tr><td>");
	print("<div style='margin-top:30px; margin-bottom:10px' align=left><font size=2><b>List of blocked users</b></font></div>\n");
	print("<table class=table_table align=center width=100% border=0 cellspacing=0 cellpadding=0><tr><td>");
	print("<tr><td style='padding:10px; background-color:black; border: 1px solid grey'>");
	print("$blocked\n");
	print("</td></tr></table>\n");
	print("</td></tr></table>\n");
	print("<div style='margin-top:20px; margin-bottom:10px' align='center'>[<a href=memberlist.php><b>Browse Members List</b></a>]</div>");
	print("</td></tr></table>\n");
	
	end_frame();
	stdfoot();
?>