 <?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2012-01-07 14:57:37 +0000 (Sat, 07 Jan 2012) $
//      $LastChangedBy: dj-howarth1 $
//
//      http://www.torrenttrader.org
//
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
		echo '<div class="torrentCategory">
			<div class="torrentCategoryImage"><img src="/images/categories/'.$catsrow["image"].'" /></div>
			<div class="categories">
				<div class="torrentCategoryTitle">
					<a href="../browse/?cat='.$catrow["id"].'">'.htmlspecialchars($catsrow[parent_cat]).'</a>
				</div>
				<div class="torrentSubcategories">';
					$parentcat = $catsrow['parent_cat'];
					$cats = SQL_Query_exec("SELECT * FROM categories WHERE parent_cat=".sqlesc($parentcat)." ORDER BY name");
					$countcats = mysqli_num_rows($cats);
					$countcats = $countcats - 1;
					$i = 0;
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
	</form>

	<div id="Alfabe">
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=A"><div class="alfabe">A</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=B"><div class="alfabe">B</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=C"><div class="alfabe">C</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=D"><div class="alfabe">D</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=E"><div class="alfabe">E</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=F"><div class="alfabe">F</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=G"><div class="alfabe">G</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=H"><div class="alfabe">H</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=I"><div class="alfabe">I</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=J"><div class="alfabe">J</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=K"><div class="alfabe">K</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=L"><div class="alfabe">L</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=M"><div class="alfabe">M</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=N"><div class="alfabe">N</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=O"><div class="alfabe">O</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=P"><div class="alfabe">P</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=Q"><div class="alfabe">Q</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=R"><div class="alfabe">R</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=S"><div class="alfabe">S</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=T"><div class="alfabe">T</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=U"><div class="alfabe">U</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=V"><div class="alfabe">V</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=W"><div class="alfabe">W</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=X"><div class="alfabe">X</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=Y"><div class="alfabe">Y</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=Z"><div class="alfabe">Z</div></a>
			<a href="http://templateshares-ue.net/tsue/?p=torrents&amp;pid=32&amp;a=0-9"><div class="alfabe">0-9</div></a></div>

</div><?php

begin_frame(T_("BROWSE_TORRENTS"));

?>
<br />
<form method="get" action="../browse/">
<?php

if (is_valid_id($_GET["page"]))
    $thisurl .= "page=$_GET[page]&amp;";

// New code (TorrentialStorm)
    echo "<div align='right'><form id='sort' action=''>".T_("SORT_BY").": <select name='sort' onchange='window.location=\"{$thisurl}sort=\"+this.options[this.selectedIndex].value+\"&amp;order=\"+document.forms[\"sort\"].order.options[document.forms[\"sort\"].order.selectedIndex].value'>";
    echo "<option value='id'" . ($_GET["sort"] == "id" ? " selected='selected'" : "") . ">".T_("ADDED")."</option>";
    echo "<option value='name'" . ($_GET["sort"] == "name" ? " selected='selected'" : "") . ">".T_("NAME")."</option>";
    echo "<option value='comments'" . ($_GET["sort"] == "comments" ? " selected='selected'" : "") . ">".T_("COMMENTS")."</option>";
    echo "<option value='size'" . ($_GET["sort"] == "size" ? " selected='selected'" : "") . ">".T_("SIZE")."</option>";
    echo "<option value='times_completed'" . ($_GET["sort"] == "times_completed" ? " selected='selected'" : "") . ">".T_("COMPLETED")."</option>";
    echo "<option value='seeders'" . ($_GET["sort"] == "seeders" ? " selected='selected'" : "") . ">".T_("SEEDERS")."</option>";
    echo "<option value='leechers'" . ($_GET["sort"] == "leechers" ? " selected='selected'" : "") . ">".T_("LEECHERS")."</option>";
    echo "</select>&nbsp;";
    echo "<select name='order' onchange='window.location=\"{$thisurl}order=\"+this.options[this.selectedIndex].value+\"&amp;sort=\"+document.forms[\"sort\"].sort.options[document.forms[\"sort\"].sort.selectedIndex].value'>";
    echo "<option selected='selected' value='asc'" . ($_GET["order"] == "asc" ? " selected='selected'" : "") . ">".T_("ASCEND")."</option>";
    echo "<option value='desc'" . ($_GET["order"] == "desc" ? " selected='selected'" : "") . ">".T_("DESCEND")."</option>";
    echo "</select>";
    echo "</form></div>";

// End

if ($count) {
    torrenttable($res);
    print($pagerbottom);
}else {
	end_frame();
    show_error_msg(T_("ERROR"), T_("NO_UPLOADS"), 1);  
}

if ($CURUSER)
    SQL_Query_exec("UPDATE users SET last_browse=".gmtime()." WHERE id=$CURUSER[id]");

end_frame();
stdfoot();
?> 
