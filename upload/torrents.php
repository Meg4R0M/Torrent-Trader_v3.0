 <?php
//
//  TorrentTrader v3.x
//      $LastChangedDate: 2016-10-11 11:18:37 +0000 (Tue, 11 Oct 2016) $
//      $LastChangedBy: Meg4R0M $
//

require_once("backend/functions.php");
dbconn();

//check permissions
if ($site_config["MEMBERSONLY"]){
    loggedinonly();

    if($CURUSER["view_torrents"]=="no")
        show_error_msg(T_("ERROR"), T_("NO_TORRENT_VIEW"), 1);
}

//get http vars
$addparam = "";
$wherea = array();
$wherea[] = "visible = 'yes'";
$thisurl = "../browse/?";

if ($_GET["cat"]) {
    $wherea[] = "category = " . sqlesc($_GET["cat"]);
    $addparam .= "cat=" . urlencode($_GET["cat"]) . "&amp;";
    $thisurl .= "cat=".urlencode($_GET["cat"])."&amp;";
}

if ($_GET["parent_cat"]) {
    $addparam .= "parent_cat=" . urlencode($_GET["parent_cat"]) . "&amp;";
    $thisurl .= "parent_cat=".urlencode($_GET["parent_cat"])."&amp;";
    $wherea[] = "categories.parent_cat=".sqlesc($_GET["parent_cat"]);
}

$parent_cat = $_GET["parent_cat"];
$category = (int) $_GET["cat"];

$where = implode(" AND ", $wherea);
$wherecatina = array();
$wherecatin = "";
$res = SQL_Query_exec("SELECT id FROM categories");
while($row = mysqli_fetch_array($res)){
    if ($_GET["c$row[id]"]) {
        $wherecatina[] = $row["id"];
        $addparam .= "c$row[id]=1&amp;";
        $thisurl .= "c$row[id]=1&amp;";
    }
    $wherecatin = implode(", ", $wherecatina);
}

if ($wherecatin)
    $where .= ($where ? " AND " : "") . "category IN(" . $wherecatin . ")";

if ($where != "")
    $where = "WHERE $where";

if ($_GET["sort"] || $_GET["order"]) {

    switch ($_GET["sort"]) {
        case 'name': $sort = "torrents.name"; $addparam .= "sort=name&amp;"; break;
        case 'times_completed':    $sort = "torrents.times_completed"; $addparam .= "sort=times_completed&amp;"; break;
        case 'seeders':    $sort = "torrents.seeders"; $addparam .= "sort=seeders&amp;"; break;
        case 'leechers': $sort = "torrents.leechers"; $addparam .= "sort=leechers&amp;"; break;
        case 'comments': $sort = "torrents.comments"; $addparam .= "sort=comments&amp;"; break;
        case 'size': $sort = "torrents.size"; $addparam .= "sort=size&amp;"; break;
        default: $sort = "torrents.id";
    }

    if ($_GET["order"] == "asc" || ($_GET["sort"] != "id" && !$_GET["order"])) {
        $sort .= " ASC";
        $addparam .= "order=asc&amp;";
    } else {
        $sort .= " DESC";
        $addparam .= "order=desc&amp;";
    }

    $orderby = "ORDER BY $sort";

    }else{
        $orderby = "ORDER BY torrents.id DESC";
        $_GET["sort"] = "id";
        $_GET["order"] = "desc";
    }

//Get Total For Pager
$res = SQL_Query_exec("SELECT COUNT(*) FROM torrents LEFT JOIN categories ON category = categories.id $where");
$row = mysqli_fetch_row($res);
$count = $row[0];

//get sql info
if ($count) {
    list($pagertop, $pagerbottom, $limit) = pager(20, $count, "../browse/?" . $addparam);
    $query = "SELECT torrents.id, torrents.anon, torrents.announce, torrents.category, torrents.leechers, torrents.nfo, torrents.seeders, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.comments, torrents.numfiles, torrents.filename, torrents.owner, torrents.external, torrents.freeleech, categories.name AS cat_name, categories.parent_cat AS cat_parent, categories.image AS cat_pic, users.username, users.privacy, IF(torrents.numratings < 2, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id $where $orderby $limit";
    $res = SQL_Query_exec($query);
}else{
    unset($res);
}

stdhead(T_("BROWSE_TORRENTS"));

begin_frame("Torrent Categories");

	$catsquery = SQL_Query_exec("SELECT distinct parent_cat FROM categories ORDER BY parent_cat");
	while($catsrow = mysqli_fetch_assoc($catsquery)){
		$parentcat = $catsrow['parent_cat'];
		$imgcats = SQL_Query_exec("SELECT image FROM categories WHERE parent_cat=".sqlesc($parentcat)." ORDER BY name");
		$imgcat = mysqli_fetch_assoc($imgcats);
		echo '<div class="torrentCategory">
			<div class="torrentCategoryImage"><img src="/images/categories/'.$imgcat["image"].'" /></div>
			<div class="categories">
				<div class="torrentCategoryTitle">
					<a href="../browse/?cat='.$catrow["id"].'">'.htmlspecialchars($catsrow[parent_cat]).'</a>
				</div>
				<div class="torrentSubcategories">';
					$cats = SQL_Query_exec("SELECT * FROM categories WHERE parent_cat=".sqlesc($parentcat)." ORDER BY name");
					$countcats = mysqli_num_rows($cats);
					$countcats = $countcats - 1;
					$i = 0;
					// Changer pour une boucle For et utiliser next() pour l'ajout de virgule
					while ($cat = mysqli_fetch_assoc($cats)) {
						echo '<a href="../browse/?cat='.$cat["id"].'">'.htmlspecialchars($cat["name"]).'</a>';
						if ($i < $countcats){
							echo ', ';
							$i++;
						}
					}
				echo '</div>
			</div>
			<div class="clear">
		</div></div>';
	}

end_frame();

?><div class="torrent-box" id="search_torrent">

	<form method="post" action="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=10" name="form_search_torrent" id="form_search_torrent">
		
		<input type="text" name="keywords" id="keywords" class="s" accesskey="s" value="" placeholder="enter a search word" /> 
		
		<select name="search_type" id="cat_content_tiny">
			<option value="name">in Torrents name</option>
			<option value="description">in Torrents description</option>
			<option value="both">in Torrents name & description</option>
			<option value="uploader">by Uploader</option>
		</select>
		
		<input type="submit" value="Search" class="submit" /> 
		<input type="button" value="Categories" class="submit" id="torrents_select_category" />
		<input type="button" value="Tags" class="submit" rel="tags" />

		<div id="torrent_categories" class="hidden"><div class="text"></div></div>
	</form><?php

	echo '<div id="Alfabe">';
		foreach(range('A','Z') as $i) {
			echo '<a href="./torrents.php?lettre='.$i.'"><div class="alfabe">'.$i.'</div></a>';
		}
		echo '<a href="./torrents.php?lettre=0-9"><div class="alfabe">0-9</div></a>
	</div>
</div>';

begin_frame(T_("BROWSE_TORRENTS"));

?>
<br />
<form method="get" action="../browse/">
<?php

if (is_valid_id($_GET["page"]))
    $thisurl .= "page=$_GET[page]&amp;";

if ($count) {
    torrenttable($res);
    print($pagerbottom);
}else {
    echo T_("ERROR").', '.T_("NO_UPLOADS");  
}

if ($CURUSER)
    SQL_Query_exec("UPDATE users SET last_browse=".gmtime()." WHERE id=$CURUSER[id]");

end_frame();
stdfoot();
?> 
