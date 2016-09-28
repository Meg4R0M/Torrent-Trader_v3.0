<?php
#================================#
#      TorrentTrader unofficial 3.0.0 BETA      #
#  http://www.torrenttrader.org  #
#--------------------------------#
#      Modified by EZEL      #
#================================#

require_once("backend/functions.php");
dbconn(false);
loggedinonly();

    if (get_user_class() < 7) //ADD YOUR STAFF CLASS HERE
        show_error_msg(T_("ERROR"), T_("SORRY_NO_RIGHTS_TO_ACCESS"), 1);

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
        $type    = $_GET['type'];
       
        if (!is_valid_id($targetid))
            show_error_msg("Error", "Invalid ID $$targetid.", 1);
       
        if ($type == 'bookmarkuser') {
            $table_is = $frag = 'bookmarkuser';
            $field_is = 'bkid';
        } else
            show_error_msg("Error", "Unknown type $type", 1);
       
        $r = SQL_Query_exec("SELECT id FROM $table_is WHERE userid=$userid AND $field_is=$targetid");
        if (mysql_num_rows($r) == 1)
            show_error_msg("Error", "User ID $targetid is already in your $table_is list.", 1);
       
        SQL_Query_exec("INSERT INTO $table_is VALUES (0,$userid, $targetid)");
        header("Location: ".$site_config['SITEURL']."/watch/?id=$userid#$frag");
        die;
    }

    //===| Action: Delete |================================================================//

    if ($action == 'delete') {
        $targetid = (int) $_GET['targetid'];
        $sure = htmlentities($_GET['sure']);
        $type = htmlentities($_GET['type']);
       
       
        if (!is_valid_id($targetid))
            show_error_msg("Error", "Invalid ID $userid.", 1);
       
        if (!$sure)
            show_error_msg("Delete $type", "<div style='margin-top:10px; margin-bottom:10px' align='center'>Do you really want to delete this $typ? &nbsp; \n"."<a href=?id=$userid&action=delete&type=$type&targetid=$targetid&sure=1>Yes</a> | <a href=/watch/>No</a></div>", 1);
       
        if ($type == 'bookmarkuser') {
            SQL_Query_exec("DELETE FROM bookmarkuser WHERE userid=$userid AND bkid=$targetid");
            if (mysql_affected_rows() == 0)
                show_error_msg("Error", "No bookmarkuser found with ID $targetid", 1);
            $frag = "bookmarkuser";
        } else
            show_error_msg("Error", "Unknown type $type", 1);
       
        header("Location: ".$site_config['SITEURL']."/watch/?id=$userid#$frag");
        die;
    }

    //===| Main Body |================================================================//

    stdhead("Personal lists for ".$user['username']);
    begin_frame("Personal lists for ".class_user($user[username])."");

   
    print("<div style='margin-top:10px; margin-bottom:20px' align='center'><font size=2><font color=#0080FF><b>List of Personal watched users</b></font></div>");
   
    ?>
    <table class="table_table" border="0" width="100%">
        <tr>
            <th class="table_head"><b>User Name</b></td></th>
            <th class="table_head"><b>Account enabled?</b></td></th>
            <th class="table_head"><b>Contact user</td></th>
            <th class="table_head"><b>Last Seen</b></td></th>
            <th class="table_head"><b>REMOVE</b></td></th>
        </tr>
    <?php

    $res = SQL_Query_exec("SELECT b.bkid as id, u.username AS name, u.class, u.avatar, u.title, u.enabled, u.last_access FROM bookmarkuser AS b LEFT JOIN users as u ON b.bkid = u.id WHERE userid=$userid ORDER BY name");
    if (mysql_num_rows($res) == 0) {
        $bookmarkuser = "Your watch list is empty!";
    } else {
        while ($bookmarkuser = mysql_fetch_array($res)) {
            $title = $bookmarkuser["title"];
            if (!$title)
                $title = get_user_class_name($bookmarkuser["class"]);
                $banned = $bookmarkuser["enabled"];
           

        echo "<tr>
            <td class='table_col1' align='center'><a href='../user/?id=".$bookmarkuser['id']."'><b>".class_user($bookmarkuser['name'])."</b></a></td>
            <td class='table_col2' align='center'>$banned</td>
            <td class='table_col1' align='center'><a href='../message/?compose&amp;id=".$bookmarkuser['id']."'><img src='../images/button_pm.gif' title='Send&nbsp;PM'></a></td>
            <td class='table_col2' align='center'><div style='margin-top:10px; margin-bottom:2px'>Last seen: ".date("<\\b>d.M.Y<\\/\\b> H:i", utc_to_tz_time($bookmarkuser['last_access']))."</div>[<b>".get_elapsed_time(sql_timestamp_to_unix_timestamp($bookmarkuser[last_access]))." ago</b>]</td>
            <td class='table_col1' align='center'><b><a href='../watch/?id=$userid&action=delete&type=bookmarkuser&targetid=".$bookmarkuser['id']."'><font style='margin-left:7px'><input type='submit' value='Remove' class='btn btn-success'/></font></a></b></td>
        </tr>";
            }
            }
    ?>
    </table>
    <?php
   
   
    end_frame();
    stdfoot();
?>