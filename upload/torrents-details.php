<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2012-07-27 20:42:14 +0100 (Fri, 27 Jul 2012) $
//      $LastChangedBy: torrenttrader $
//
//      http://www.torrenttrader.org
//
//
require_once("backend/functions.php");
require_once("backend/BDecode.php");
dbconn();

$torrent_dir = $site_config["torrent_dir"];    
$nfo_dir = $site_config["nfo_dir"];    

//check permissions
if ($site_config["MEMBERSONLY"]){
    loggedinonly();

    if($CURUSER["view_torrents"]=="no")
        show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
}

//************ DO SOME "GET" STUFF BEFORE PAGE LAYOUT ***************

$id = (int) $_GET["id"];
$scrape = (int)$_GET["scrape"];
if (!is_valid_id($id))
    show_error_msg(T_("ERROR"), T_("THATS_NOT_A_VALID_ID"), 1);

//GET ALL MYSQL VALUES FOR THIS TORRENT
$res = SQL_Query_exec("SELECT torrents.anon, torrents.seeders, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, torrents.nfo, torrents.last_action, torrents.numratings, torrents.name, torrents.owner, torrents.save_as, torrents.descr, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.external, torrents.image1, torrents.image2, torrents.announce, torrents.numfiles, torrents.freeleech, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.numratings, categories.name AS cat_name, torrentlang.name AS lang_name, torrentlang.image AS lang_image, categories.parent_cat as cat_parent, users.username, users.privacy FROM torrents LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN torrentlang ON torrents.torrentlang = torrentlang.id LEFT JOIN users ON torrents.owner = users.id WHERE torrents.id = $id");
$row = mysqli_fetch_assoc($res);

//DECIDE IF TORRENT EXISTS
if (!$row || ($row["banned"] == "yes" && $CURUSER["edit_torrents"] == "no"))
    show_error_msg(T_("ERROR"), T_("TORRENT_NOT_FOUND"), 1);

//torrent is availiable so do some stuff

if ($_GET["hit"]) {
    SQL_Query_exec("UPDATE torrents SET views = views + 1 WHERE id = $id");
    header("Location: torrents-details.php?id=$id");
    die;
    }

    stdhead(T_("DETAILS_FOR_TORRENT")." \"" . $row["name"] . "\"");

    if ($CURUSER["id"] == $row["owner"] || $CURUSER["edit_torrents"] == "yes")
        $owned = 1;
    else
        $owned = 0;

//take rating
if ($_GET["takerating"] == 'yes'){
    $rating = (int)$_POST['rating'];

    if ($rating <= 0 || $rating > 5)
        show_error_msg(T_("RATING_ERROR"), T_("INVAILD_RATING"), 1);

    $res = SQL_Query_exec("INSERT INTO ratings (torrent, user, rating, added) VALUES ($id, " . $CURUSER["id"] . ", $rating, '".get_date_time()."')");

    if (!$res) {
        if (((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_errno($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_errno()) ? $___mysqli_res : false)) == 1062)
            show_error_msg(T_("RATING_ERROR"), T_("YOU_ALREADY_RATED_TORRENT"), 1);
        else
            show_error_msg(T_("RATING_ERROR"), T_("A_UNKNOWN_ERROR_CONTACT_STAFF"), 1);
    }

    SQL_Query_exec("UPDATE torrents SET numratings = numratings + 1, ratingsum = ratingsum + $rating WHERE id = $id");
    show_error_msg(T_("RATING_SUCCESS"), T_("RATING_THANK")."<br /><br /><a href='torrents-details.php?id=$id'>" .T_("BACK_TO_TORRENT"). "</a>");
}

//take comment add
if ($_GET["takecomment"] == 'yes'){
    loggedinonly();
    $body = $_POST['body'];
    
    if (!$body)
        show_error_msg(T_("RATING_ERROR"), T_("YOU_DID_NOT_ENTER_ANYTHING"), 1);

    SQL_Query_exec("UPDATE torrents SET comments = comments + 1 WHERE id = $id");

    SQL_Query_exec("INSERT INTO comments (user, torrent, added, text) VALUES (".$CURUSER["id"].", ".$id.", '" .get_date_time(). "', " . sqlesc($body).")");

    if (mysqli_affected_rows($GLOBALS["___mysqli_ston"]) == 1)
            show_error_msg(T_("COMPLETED"), T_("COMMENT_ADDED"), 0);
        else
            show_error_msg(T_("ERROR"), T_("UNABLE_TO_ADD_COMMENT"), 0);
}//end insert comment

//START OF PAGE LAYOUT HERE
$char1 = 50; //cut length
$shortname = CutName(htmlspecialchars($row["name"]), $char1);

print ("<font size='3' color='#CC0000'><b>" . $shortname . "</b></a></font><br />");
print ("<b>" .T_("SEEDERS"). " : <font color='green'>" . number_format($row["seeders"]) . "</font> - ".T_("LEECHERS").": <font color='#ff0000'>" .  number_format($row["leechers"]) . "</font></b><br />");
print ("<b>".T_("LAST_CHECKED").": </b>" . date("d-M-Y H:i:s", utc_to_tz_time($row["last_action"])) . "");
begin_frame(T_("TORRENT_DETAILS_FOR"));

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Un syst&egrave;me d'onglet en javascript</title>
<script type="text/javascript">
//<!--
function change_onglet(name)
{
document.getElementById('onglet_'+anc_onglet).className = 'onglet_0 onglet';
document.getElementById('onglet_'+name).className = 'onglet_1 onglet';
document.getElementById('contenu_onglet_'+anc_onglet).style.display = 'none';
document.getElementById('contenu_onglet_'+name).style.display = 'block';
anc_onglet = name;
}
//-->
</script>
<style type="text/css">
.onglet
{
display:inline-block;
margin-left:5px;
margin-right:1px;
padding:6px;
border:1px solid white;
cursor:pointer;
}
.onglet_0
{
background:#2E2E2E;
border-bottom:3px solid white;
}
.onglet_1
{
background:#000000;
border-bottom:0px solid white;
padding-bottom:9px;
}
.contenu_onglet
{
background-color: transparent;
border:3px solid white;
margin-top:-3px;
padding:5px;
display:none;
}
ul
{
margin-top:0px;
margin-bottom:0px;
margin-left:-10px
}
h1
{
margin:0px;
padding:0px;
}
</style>
</head>
<body>
<div class="systeme_onglets">
<div align='right'>
<div class="onglets">
<span class="onglet_0 onglet" id="onglet_description" onclick="javascript:change_onglet('description');"><b><font color=#CC0000 size=2>Main</font></b></span>
<span class="onglet_0 onglet" id="onglet_fichier" onclick="javascript:change_onglet('fichier');"><b><font color=#CC0000 size=2>Technical</font></b></span>
<span class="onglet_0 onglet" id="onglet_commentaire" onclick="javascript:change_onglet('commentaire');"><b><font color=#CC0000 size=2>Comments</font></b></span>
</div>

<div class="contenu_onglets">
<div class="contenu_onglet" id="contenu_onglet_description">

<?php
// Calculate local torrent speed test
if ($row["leechers"] >= 1 && $row["seeders"] >= 1 && $row["external"]!='yes'){
    $speedQ = SQL_Query_exec("SELECT (SUM(p.downloaded)) / (UNIX_TIMESTAMP('".get_date_time()."') - UNIX_TIMESTAMP(added)) AS totalspeed FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' AND p.torrent = '$id' GROUP BY t.id ORDER BY added ASC LIMIT 15");
    $a = mysqli_fetch_assoc($speedQ);
    $totalspeed = mksize($a["totalspeed"]) . "/s";
}else{
    $totalspeed = T_("NO_ACTIVITY"); 
}

// start description
// start presentation release
echo "<center><table border='0' width='100%'><tr><td><div id='downloadbox'>";
if ($row["banned"] == "yes"){
    print ("<center><b>" .T_("DOWNLOAD"). " : </b>BANNED!</center>");
}else{
//===| Start Bookmarks
    $bookt = SQL_Query_exec("SELECT torrentid FROM bookmarks WHERE torrentid = $row[id] AND userid = $CURUSER[id]");
    if (mysqli_num_rows($bookt) > 0)
        print("<div style='margin-top:3px'><font color='#0080FF'><b>Bookmark</b></font>: <font color='#FF2200'><b>This torrent is already into</b></font> <a href='bookmark.php'><b>Your Bookmarks</b></a>. &nbsp; [<a href='takedelbookmark.php?bookmarkid=".$row[id]."'><b>Delete it from the list</b></a>]</div>");
    else
        print("<div style='margin-top:3px'><font color='#0080FF'><b>Bookmark</b></font>: <a href='bookmarks.php?torrent=$row[id]'><b>Add this torrent</b></a> to <a href='bookmark.php'><b>Your Bookmarks</b></a></div>");
    //===| End Bookmarks
echo "<div align='right'><a href='report.php?torrent=$id'><button type='button' class='btn btn-large btn-danger'>Report</button></a>&nbsp;";
if ($owned)
    echo "<a href='torrents-edit.php?id=$row[id]&amp;returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "'><button type='button' class='btn btn-large btn-warning'>Edit</button></a>&nbsp;";
echo "</div><br />";
print ("<font size=2><b>" .T_("Uploaded By"). " :</b> <a href='account-details.php?id=" . $row["owner"] . "'>".$row["username"]."</a></font><br />");

        print ("<b>" .T_("COMPLETED"). " : <font color='#2E2EFE'>" . number_format($row["times_completed"]) . "</font></br>" .T_("TOTAL_SIZE"). " : " . mksize($row["size"]) . "</br>" .T_("VIEWS"). " : " . number_format($row["views"]) . "</br>" .T_("HITS"). " : " . number_format($row["hits"]) . "</b></center><br />");
   if ($row["external"] == 'yes'){
    print ("<a href=\"magnet:?xt=urn:btih:".$row["info_hash"]."&dn=".$row["filename"]."&tr=udp://tracker.openbittorrent.com&tr=udp://tracker.publicbt.com\"><button type='button' class='btn btn-large btn-primary'>Magnet Download</button></a>");
    }else{
    print ("<a href=\"magnet:?xt=urn:btih:".$row["info_hash"]."&dn=".$row["filename"]."&tr=".$site_config[TSITEURL]."../announce/?passkey=".$CURUSER["passkey"]."\"><button type='button' class='btn btn-large btn-primary'>Magnet Download</button></a>");
    }
    
    print ("<a href=\"../download/?id=$id&amp;name=" . rawurlencode($row["filename"]) . "\"><button type='button' class='btn btn-large btn-danger'>DOWNLOAD TORRENT</button></a>
    
    <a href=\"../download/?id=$id&amp;name=" . rawurlencode($row["filename"]) . "\"></a><br />");
        
if (empty($row["lang_name"])) $row["lang_name"] = "Unknown/NA";
if (isset($row["lang_image"]) && $row["lang_image"] != "")

    print("<center><font size=2><b>" .T_("DATE_ADDED"). " : " . date("d-m-Y H:i:s", utc_to_tz_time($row["added"])) . " / ".T_("LAST_CHECKED")." : " . date("d-M-Y H:i:s", utc_to_tz_time($row["last_action"])) . "</b></font></center><br />\n");

    
    
    
}

echo "</div></td></tr></table></center><br />";
print("<center><B>.::============================================================Description============================================================::.</B></center>");
print("<center><br />" . format_comment($row['descr']) . "<br /><br />\n");


// end description

?>
</div>
<div class="contenu_onglet" id="contenu_onglet_nfo">
<?php

//DISPLAY NFO BLOCK
function my_nfo_translate($nfo){
        $trans = array(
        "\x80" => "&#199;", "\x81" => "&#252;", "\x82" => "&#233;", "\x83" => "&#226;", "\x84" => "&#228;", "\x85" => "&#224;", "\x86" => "&#229;", "\x87" => "&#231;", "\x88" => "&#234;", "\x89" => "&#235;", "\x8a" => "&#232;", "\x8b" => "&#239;", "\x8c" => "&#238;", "\x8d" => "&#236;", "\x8e" => "&#196;", "\x8f" => "&#197;", "\x90" => "&#201;",
        "\x91" => "&#230;", "\x92" => "&#198;", "\x93" => "&#244;", "\x94" => "&#246;", "\x95" => "&#242;", "\x96" => "&#251;", "\x97" => "&#249;", "\x98" => "&#255;", "\x99" => "&#214;", "\x9a" => "&#220;", "\x9b" => "&#162;", "\x9c" => "&#163;", "\x9d" => "&#165;", "\x9e" => "&#8359;", "\x9f" => "&#402;", "\xa0" => "&#225;", "\xa1" => "&#237;",
        "\xa2" => "&#243;", "\xa3" => "&#250;", "\xa4" => "&#241;", "\xa5" => "&#209;", "\xa6" => "&#170;", "\xa7" => "&#186;", "\xa8" => "&#191;", "\xa9" => "&#8976;", "\xaa" => "&#172;", "\xab" => "&#189;", "\xac" => "&#188;", "\xad" => "&#161;", "\xae" => "&#171;", "\xaf" => "&#187;", "\xb0" => "&#9617;", "\xb1" => "&#9618;", "\xb2" => "&#9619;",
        "\xb3" => "&#9474;", "\xb4" => "&#9508;", "\xb5" => "&#9569;", "\xb6" => "&#9570;", "\xb7" => "&#9558;", "\xb8" => "&#9557;", "\xb9" => "&#9571;", "\xba" => "&#9553;", "\xbb" => "&#9559;", "\xbc" => "&#9565;", "\xbd" => "&#9564;", "\xbe" => "&#9563;", "\xbf" => "&#9488;", "\xc0" => "&#9492;", "\xc1" => "&#9524;", "\xc2" => "&#9516;", "\xc3" => "&#9500;",
        "\xc4" => "&#9472;", "\xc5" => "&#9532;", "\xc6" => "&#9566;", "\xc7" => "&#9567;", "\xc8" => "&#9562;", "\xc9" => "&#9556;", "\xca" => "&#9577;", "\xcb" => "&#9574;", "\xcc" => "&#9568;", "\xcd" => "&#9552;", "\xce" => "&#9580;", "\xcf" => "&#9575;", "\xd0" => "&#9576;", "\xd1" => "&#9572;", "\xd2" => "&#9573;", "\xd3" => "&#9561;", "\xd4" => "&#9560;",
        "\xd5" => "&#9554;", "\xd6" => "&#9555;", "\xd7" => "&#9579;", "\xd8" => "&#9578;", "\xd9" => "&#9496;", "\xda" => "&#9484;", "\xdb" => "&#9608;", "\xdc" => "&#9604;", "\xdd" => "&#9612;", "\xde" => "&#9616;", "\xdf" => "&#9600;", "\xe0" => "&#945;", "\xe1" => "&#223;", "\xe2" => "&#915;", "\xe3" => "&#960;", "\xe4" => "&#931;", "\xe5" => "&#963;",
        "\xe6" => "&#181;", "\xe7" => "&#964;", "\xe8" => "&#934;", "\xe9" => "&#920;", "\xea" => "&#937;", "\xeb" => "&#948;", "\xec" => "&#8734;", "\xed" => "&#966;", "\xee" => "&#949;", "\xef" => "&#8745;", "\xf0" => "&#8801;", "\xf1" => "&#177;", "\xf2" => "&#8805;", "\xf3" => "&#8804;", "\xf4" => "&#8992;", "\xf5" => "&#8993;", "\xf6" => "&#247;",
        "\xf7" => "&#8776;", "\xf8" => "&#176;", "\xf9" => "&#8729;", "\xfa" => "&#183;", "\xfb" => "&#8730;", "\xfc" => "&#8319;", "\xfd" => "&#178;", "\xfe" => "&#9632;", "\xff" => "&#160;",
        );
        $trans2 = array("\xe4" => "&auml;",        "\xF6" => "&ouml;",        "\xFC" => "&uuml;",        "\xC4" => "&Auml;",        "\xD6" => "&Ouml;",        "\xDC" => "&Uuml;",        "\xDF" => "&szlig;");
        $all_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $last_was_ascii = False;
        $tmp = "";
        $nfo = $nfo . "\00";
        for ($i = 0; $i < (strlen($nfo) - 1); $i++)
        {
                $char = $nfo[$i];
                if (isset($trans2[$char]) and ($last_was_ascii or strpos($all_chars, ($nfo[$i + 1]))))
                {
                        $tmp = $tmp . $trans2[$char];
                        $last_was_ascii = True;
                }
                else
                {
                        if (isset($trans[$char]))
                        {
                                $tmp = $tmp . $trans[$char];
                        }
                        else
                        {
                            $tmp = $tmp . $char;
                        }
                        $last_was_ascii = strpos($all_chars, $char);
                }
        }
        return $tmp;
}
//-----------------------------------------------

//DISPLAY NFO BLOCK
if($row["nfo"]== "yes"){
    $nfofilelocation = "$nfo_dir/$row[id].nfo";
    $filegetcontents = file_get_contents($nfofilelocation);
    $nfo = htmlspecialchars($filegetcontents);
        if ($nfo) {    
            $nfo = my_nfo_translate($nfo);
            echo "<br /><br /><b>NFO:</b><br />";
            print("<textarea class='nfo' style='width:98%;height:100%;' rows='20' cols='20' readonly='readonly'>".stripslashes($nfo)."</textarea>");
        }else{
            print(T_("ERROR")." reading .nfo file!");
        }
}

?>
</div>
<div class="contenu_onglet" id="contenu_onglet_fichier">
<?php

if ($row["external"]=='yes'){
    print ("<br /><b>Tracker : </b><br /> ".htmlspecialchars($row['announce'])."<br />");
}

$tres = SQL_Query_exec("SELECT * FROM `announce` WHERE `torrent` = $id");
if (mysqli_num_rows($tres) > 1){
    echo "<br /><b>".T_("THIS_TORRENT_HAS_BACKUP_TRACKERS")."</b><br />";
    echo '<table cellpadding="1" cellspacing="2" class="table_table">';
    echo '<tr><th class="table_head">URL</th><th class="table_head">'.T_("SEEDERS").'</th><th class="table_head">'.T_("LEECHERS").'</th><th class="table_head">'.T_("COMPLETED").'</th></tr>';
    $x = 1;
    while ($trow = mysqli_fetch_assoc($tres)) {
        $colour = $trow["online"] == "yes" ? "green" : "red";
        echo "<tr class=\"table_col$x\"><td><font color=\"$colour\"><b>".htmlspecialchars($trow['url'])."</b></font></td><td align=\"center\">".number_format($trow["seeders"])."</td><td align=\"center\">".number_format($trow["leechers"])."</td><td align=\"center\">".number_format($trow["times_completed"])."</td></tr>";
        $x = $x == 1 ? 2 : 1;
    }
    echo '</table>';
}

echo "<br /><br /><b>".T_("FILE_LIST").":</b>&nbsp;<table align='center' cellpadding='0' cellspacing='0' class='table_table' border='1' width='100%'><tr><th class='table_head' align='left'>&nbsp;".T_("FILE")."</th><th width='50' class='table_head'>&nbsp;".T_("SIZE")."</th></tr>";
$fres = SQL_Query_exec("SELECT * FROM `files` WHERE `torrent` = $id ORDER BY `path` ASC");
if (mysqli_num_rows($fres)) {
    while ($frow = mysqli_fetch_assoc($fres)) {
        echo "<tr><td class='table_col1'>".htmlspecialchars($frow['path'])."</td><td class='table_col2'>".mksize($frow['filesize'])."</td></tr>";
    }
}else{
    echo "<tr><td class='table_col1'>".htmlspecialchars($row["name"])."</td><td class='table_col2'>".mksize($row["size"])."</td></tr>";
}
echo "</table>";

if ($row["external"]!='yes'){
    echo "<br /><br /><b>".T_("PEERS_LIST").":</b><br />";
    $query = SQL_Query_exec("SELECT * FROM peers WHERE torrent = $id ORDER BY seeder DESC");

    $result = mysqli_num_rows($query);
        if($result == 0) {
            echo T_("NO_ACTIVE_PEERS")."\n";
        }else{
            ?>

            <table border="0" cellpadding="3" cellspacing="0" width="100%" class="table_table">
            <tr>
                <th class="table_head"><?php echo T_("PORT"); ?></th>
                <th class="table_head"><?php echo T_("UPLOADED"); ?></th>
                <th class="table_head"><?php echo T_("DOWNLOADED"); ?></th>
                <th class="table_head"><?php echo T_("RATIO"); ?></th>
                <th class="table_head"><?php echo T_("_LEFT_"); ?></th>
                <th class="table_head"><?php echo T_("FINISHED_SHORT"). "%"; ?></th>
                <th class="table_head"><?php echo T_("SEED"); ?></th>
                <th class="table_head"><?php echo T_("CONNECTED_SHORT"); ?></th>
                <th class="table_head"><?php echo T_("CLIENT"); ?></th>
                <th class="table_head"><?php echo T_("USER_SHORT"); ?></th>
            </tr>

            <?php
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

?>
</div>
<div class="contenu_onglet" id="contenu_onglet_commentaire">
<?php

    //echo "<p align=center><a class=index href=torrents-comment.php?id=$id>" .T_("ADDCOMMENT"). "</a></p>\n";

    $subres = SQL_Query_exec("SELECT COUNT(*) FROM comments WHERE torrent = $id");
    $subrow = mysqli_fetch_array($subres);
    $commcount = $subrow[0];

    if ($commcount) {
        list($pagertop, $pagerbottom, $limit) = pager(10, $commcount, "torrents-details.php?id=$id&amp;");
        $commquery = "SELECT comments.id, text, user, comments.added, avatar, signature, username, title, class, uploaded, downloaded, privacy, donated FROM comments LEFT JOIN users ON comments.user = users.id WHERE torrent = $id ORDER BY comments.id $limit";
        $commres = SQL_Query_exec($commquery);
    }else{
        unset($commres);
    }

    if ($commcount) {
        print($pagertop);
        commenttable($commres, 'torrent');
        print($pagerbottom);
    }else {
        print("<br /><b>" .T_("NOCOMMENTS"). "</b><br />\n");
    }

    require_once("backend/bbcode.php");

    if ($CURUSER) {
        echo "<center>";
        echo "<form name=\"comment\" method=\"post\" action=\"torrents-details.php?id=$row[id]&amp;takecomment=yes\">";
        echo textbbcode("comment","body")."<br />";
        echo "<input type=\"submit\"  value=\"".T_("ADDCOMMENT")."\" />";
        echo "</form></center>";
    }

?>
</div>
</div>
</div>
<script type="text/javascript">
//<!--
var anc_onglet = 'description';
change_onglet(anc_onglet);
//-->
</script>
</body>
</html>
