<?php
require_once("../backend/functions.php");
dbconn();
loggedinonly();

$action = $_POST["action"];
$do = $_POST["do"];

if ($action == "bookmarks" && $do == "add"){
    $gottorrent = (int)$_POST["tid"];
    if (!isset($gottorrent))
        ajax_show_error_msg("Error", " ... No torrent selected", 1);
    if ((get_row_count("bookmarks", "WHERE userid=$CURUSER[id] AND torrentid = $gottorrent")) > 0)
        ajax_show_error_msg("Error", "Already bookmarked torrent", 1);
    if ((get_row_count("torrents", "WHERE id = $gottorrent")) > 0){
        SQL_Query_exec("INSERT INTO bookmarks (userid, torrentid) VALUES ($CURUSER[id], $gottorrent)");
        echo "Torrent was successfully bookmarked.";
    }
    else
        ajax_show_error_msg("Error", "ID not found", 1);
}

if ($action == "bookmarks" && $do == "remove"){
    $delid = (int)$_POST['tid'];
    $res2 = SQL_Query_exec("SELECT id, userid FROM bookmarks WHERE torrentid = $delid AND userid = $CURUSER[id]");
    $arr = mysqli_fetch_assoc($res2);
    if (!$arr)
        show_error_msg("Error!", "ID not found in your bookmarks list...", 1);

    SQL_Query_exec("DELETE FROM bookmarks WHERE torrentid = $delid AND userid = $CURUSER[id]");
    echo "Torrent was successfully removed from your bookmarks.";
}elseif ($action == "torrent_seeders"){
    $id = $_POST["tid"];
    //GET ALL MYSQL VALUES FOR THIS TORRENT
    $res = SQL_Query_exec("SELECT torrents.anon, torrents.seeders, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, torrents.nfo, torrents.last_action, torrents.numratings, torrents.name, torrents.owner, torrents.save_as, torrents.descr, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.external, torrents.image1, torrents.image2, torrents.announce, torrents.numfiles, torrents.freeleech, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.numratings, categories.name AS cat_name, torrentlang.name AS lang_name, torrentlang.image AS lang_image, categories.parent_cat as cat_parent, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row["external"]!='yes'){
        $query = SQL_Query_exec("SELECT * FROM peers WHERE torrent=$id AND seeder='yes'");
        $result = mysqli_num_rows($query);
        if($result == 0) {
            ajax_show_infos("Seeder","This torrent has no any active seeders!");
        }else{
            echo '<table class="forumTable">
            <tr class="forumTableSubHeader">
                <th>'.T_("PORT").'</th>
                <th>'.T_("UPLOADED").'</th>
                <th>'.T_("DOWNLOADED").'</th>
                <th>'.T_("RATIO").'</th>
                <th>'.T_("_LEFT_").'</th>
                <th>'.T_("FINISHED_SHORT").'%</th>
                <th>'.T_("SEED").'</th>
                <th>'.T_("CONNECTED_SHORT").'</th>
                <th>'.T_("CLIENT").'</th>
                <th>'.T_("USER_SHORT").'</th>
            </tr>';
            while($row1 = mysqli_fetch_assoc($query))    {
                if ($row1["downloaded"] > 0){
                    $ratio = $row1["uploaded"] / $row1["downloaded"];
                    $ratio = number_format($ratio, 3);
                }else{
                    $ratio = "---";
                }
                $percentcomp = sprintf("%.2f", 100 * (1 - ($row1["to_go"] / $row["size"])));
                if ($site_config["MEMBERSONLY"]) {
                    $res = SQL_Query_exec("SELECT id, username, privacy FROM users WHERE id=".$row1["userid"]."");
                    $arr = mysqli_fetch_array($res);
                    $arr["username"] = "<a href='account-details.php?id=$arr[id]'>".class_user($arr[username])."</a>";
                }
                # With $site_config["MEMBERSONLY"] off this will be shown.
                if ( !$arr["username"] ) $arr["username"] = "Unknown User";
                if ($arr["privacy"] != "strong" || ($CURUSER["control_panel"] == "yes")) {
                    print("<tr><td class='table_col2'>".$row1["port"]."</td><td class='table_col1'>".mksize($row1["uploaded"])."</td><td class='table_col2'>".mksize($row1["downloaded"])."</td><td class='table_col1'>".$ratio."</td><td class='table_col2'>".mksize($row1["to_go"])."</td><td class='table_col1'>".$percentcomp."%</td><td class='table_col2'>$row1[seeder]</td><td class='table_col1'>$row1[connectable]</td><td class='table_col2'>".htmlspecialchars($row1["client"])."</td><td class='table_col1'>$arr[username]</td></tr>");
                }else{
                    print("<tr><td class='table_col2'>".$row1["port"]."</td><td class='table_col1'>".mksize($row1["uploaded"])."</td><td class='table_col2'>".mksize($row1["downloaded"])."</td><td class='table_col1'>".$ratio."</td><td class='table_col2'>".mksize($row1["to_go"])."</td><td class='table_col1'>".$percentcomp."%</td><td class='table_col2'>$row1[seeder]</td><td class='table_col1'>$row1[connectable]</td><td class='table_col2'>".htmlspecialchars($row1["client"])."</td><td class='table_col1'>Private</td></tr>");
                }
            }
            echo "</table>";
        }
    }
}elseif ($action == "torrent_leechers"){
    $id = $_POST["tid"];
    //GET ALL MYSQL VALUES FOR THIS TORRENT
    $res = SQL_Query_exec("SELECT torrents.anon, torrents.seeders, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, torrents.nfo, torrents.last_action, torrents.numratings, torrents.name, torrents.owner, torrents.save_as, torrents.descr, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.external, torrents.image1, torrents.image2, torrents.announce, torrents.numfiles, torrents.freeleech, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.numratings, categories.name AS cat_name, torrentlang.name AS lang_name, torrentlang.image AS lang_image, categories.parent_cat as cat_parent, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id");
    $row = mysqli_fetch_assoc($res);
    if ($row["external"]!='yes'){
        $query = SQL_Query_exec("SELECT * FROM peers WHERE torrent=$id AND seeder='no'");
        $result = mysqli_num_rows($query);
        if($result == 0) {
            ajax_show_infos("Leecher","This torrent has no any active leechers!");
        }else{
            echo '<table class="forumTable">
            <tr class="forumTableSubHeader">
                <th>'.T_("PORT").'</th>
                <th>'.T_("UPLOADED").'</th>
                <th>'.T_("DOWNLOADED").'</th>
                <th>'.T_("RATIO").'</th>
                <th>'.T_("_LEFT_").'</th>
                <th>'.T_("FINISHED_SHORT").'%</th>
                <th>'.T_("SEED").'</th>
                <th>'.T_("CONNECTED_SHORT").'</th>
                <th>'.T_("CLIENT").'</th>
                <th>'.T_("USER_SHORT").'</th>
            </tr>';
            while($row1 = mysqli_fetch_assoc($query))    {
                if ($row1["downloaded"] > 0){
                    $ratio = $row1["uploaded"] / $row1["downloaded"];
                    $ratio = number_format($ratio, 3);
                }else{
                    $ratio = "---";
                }
                $percentcomp = sprintf("%.2f", 100 * (1 - ($row1["to_go"] / $row["size"])));
                if ($site_config["MEMBERSONLY"]) {
                    $res = SQL_Query_exec("SELECT id, username, privacy FROM users WHERE id=".$row1["userid"]."");
                    $arr = mysqli_fetch_array($res);
                    $arr["username"] = "<a href='account-details.php?id=$arr[id]'>".class_user($arr[username])."</a>";
                }
                # With $site_config["MEMBERSONLY"] off this will be shown.
                if ( !$arr["username"] ) $arr["username"] = "Unknown User";
                if ($arr["privacy"] != "strong" || ($CURUSER["control_panel"] == "yes")) {
                    print("<tr><td class='table_col2'>".$row1["port"]."</td><td class='table_col1'>".mksize($row1["uploaded"])."</td><td class='table_col2'>".mksize($row1["downloaded"])."</td><td class='table_col1'>".$ratio."</td><td class='table_col2'>".mksize($row1["to_go"])."</td><td class='table_col1'>".$percentcomp."%</td><td class='table_col2'>$row1[seeder]</td><td class='table_col1'>$row1[connectable]</td><td class='table_col2'>".htmlspecialchars($row1["client"])."</td><td class='table_col1'>$arr[username]</td></tr>");
                }else{
                    print("<tr><td class='table_col2'>".$row1["port"]."</td><td class='table_col1'>".mksize($row1["uploaded"])."</td><td class='table_col2'>".mksize($row1["downloaded"])."</td><td class='table_col1'>".$ratio."</td><td class='table_col2'>".mksize($row1["to_go"])."</td><td class='table_col1'>".$percentcomp."%</td><td class='table_col2'>$row1[seeder]</td><td class='table_col1'>$row1[connectable]</td><td class='table_col2'>".htmlspecialchars($row1["client"])."</td><td class='table_col1'>Private</td></tr>");
                }
            }
            echo "</table>";
        }
    }
}elseif ($action == "torrent_size"){
    $id = $_POST["tid"];
    echo '<table id="tableInOverlay" cellspacing="0" cellpadding="0" border="0">
	    <tbody>
	        <tr>
		        <th scope="col" class="subHeader">'.T_("FILE").'</th>
		        <th scope="col" class="subHeader">'.T_("SIZE").'</th>
	        </tr>';
            $fres = SQL_Query_exec("SELECT * FROM `files` WHERE `torrent` = $id ORDER BY `path` ASC");
            if (mysqli_num_rows($fres)) {
                while ($frow = mysqli_fetch_assoc($fres)) {
                    echo '<tr class="trRow">
	                    <td class="secondRow">'.htmlspecialchars($frow["path"]).'</td>
	                    <td class="secondRow">'.mksize($frow["filesize"]).'</td>
                    </tr>';
                }
            }else{
                echo '<tr class="trRow">
        	        <td class="secondRow">'.htmlspecialchars($row["name"]).'</td>
	                <td class="secondRow">'.mksize($row["size"]).'</td>
                </tr>';
            }
        echo '</tbody>
    </table>';
}
?>