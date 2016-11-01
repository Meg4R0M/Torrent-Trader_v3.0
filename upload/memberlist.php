<?php 
// 
//  TorrentTrader v3.x 
//      $LastChangedDate: 2016-10-05 18:54:42 +0100 (Wed, 05 Oct 2016) $ 
//      $LastChangedBy: Meg4R0M $ 
// 
require_once("backend/functions.php"); 
dbconn(); 
loggedinonly(); 

if ($CURUSER["view_users"]=="no") 
    show_error_msg(T_("ERROR"), T_("NO_USER_VIEW"), 1); 
     
$search = trim($_POST['search']);
$class = (int) $_POST['class'];
$letter = trim($_GET['letter']); 

if (!$class) 
    unset($class); 

$q = $query = null; 
if ($search) { 
    $query = "username LIKE " . sqlesc("%$search%") . " AND status='confirmed'"; 
    if ($search) { 
        $q = "search=" . htmlspecialchars($search); 
    } 
} elseif ($letter) { 
    if (strlen($letter) > 1) 
        unset($letter); 
    if ($letter == "" || strpos("abcdefghijklmnopqrstuvwxyz", $letter) === false) { 
        unset($letter); 
    } else { 
        $query = "username LIKE '$letter%' AND status='confirmed'"; 
    } 
    $q = "letter=$letter"; 
} 

if (!$query) { 
    $query = "status='confirmed'"; 
} 

if ($class) { 
    $query .= " AND class=$class"; 
    $q .= ($q ? "&amp;" : "") . "class=$class"; 
} 

stdhead(T_("USERS"));

echo '<div class="tableHeader">
   	<div class="row">
    	<div class="cell first">Search Member</div>
    </div>
</div>

<div class="torrent-box" id="search_member">

    <form method="post" action="memberlist.php" name="form_search_member" id="form_search_member">
	    <input type="hidden" name="action" value="search" />
	    '.T_("SEARCH").': <input type="text" name="search" id="keywords" class="s" accesskey="s" value="" />
        <select name="class">
            <option value="-">(any class)</option>';
            $res = SQL_Query_exec("SELECT group_id, level FROM groups");
            while ($row = mysqli_fetch_assoc($res)) {
                echo '<option value="'.$row[group_id].'" '.($class && $class == $row["group_id"] ? " selected='selected'" : "").'>'.htmlspecialchars($row["level"]).'</option>';
            }
        echo '</select>
        <input type="submit" value="Search" class="submit" />
    </form>
</div>';

echo '<div class="pagination">
    <ul>
        <li '.(!$letter ? " class='active'" : "").'><a href="/memberlist.php">'.T_("ALL").'</a></li>';
        foreach (range("a", "z") as $l) {
            $L = strtoupper($l);
            if ($l == $letter)
                echo '<li class="active"><a href="memberlist.php?letter='.$l.'">'.$L.'</a></li>';
            else
                echo '<li><a href="memberlist.php?letter='.$l.'">'.$L.'</a></li>';
        }
    echo '</ul>
</div>';

$page = (int) $_GET['page']; 
$perpage = 25; 

$res = SQL_Query_exec("SELECT COUNT(*) FROM users WHERE $query"); 
$arr = mysqli_fetch_row($res); 
$pages = floor($arr[0] / $perpage); 
if ($pages * $perpage < $arr[0])
    ++$pages;

if ($page < 1)
    $page = 1;
else
    if ($page > $pages)
        $page = $pages;

for ($i = 1; $i <= $pages; ++$i)
    if ($i == $page)
        $pagemenu .= "$i\n";
    else
        $pagemenu .= "<a href='?$q&amp;page=$i'>$i</a>\n";

if ($page == 1)
    $browsemenu .= "";
else
    $browsemenu .= "<a href='?$q&amp;page=" . ($page - 1) . "'>[Prev]</a>";

$browsemenu .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 

if ($page == $pages)
    $browsemenu .= "";
else
    $browsemenu .= "<a href='?$q&amp;page=" . ($page + 1) . "'>[Next]</a>";

$offset = max( 0, ( $page * $perpage ) - $perpage ); 

$res = SQL_Query_exec("SELECT users.*, groups.level FROM users INNER JOIN groups ON groups.group_id=users.class WHERE $query ORDER BY username LIMIT $offset,$perpage");

while ($arr = mysqli_fetch_assoc($res)) {
    $cres = SQL_Query_exec("SELECT name, flagpic FROM countries WHERE id=$arr[country]");
    $carr = mysqli_fetch_assoc($cres);

    if (!$carr){
        $country = '<img src="/images/countryFlags/noFlag.png" alt="" title="" class="countryFlag" />';
    } else {
        $country = '<img src="/images/countryFlags/'.$carr["flagpic"].'" alt="" title="" class="countryFlag" />';
    }

    if ($arr["gender"] == "m")
        $gender = "Male";
    elseif ($arr["gender"] == "f")
        $gender = "Female";
    else
        $gender = "Unspecified";

    $avatar = htmlspecialchars($arr["avatar"]);
    if (!$avatar) {
        if ($gender == "Male")
            $avatar = "/themes/default/avatars/avatar_m_m.png";
        elseif ($gender == "Female")
            $avatar = "/themes/default/avatars/avatar_f_m.png";
        else
            $avatar = "/themes/default/avatars/avatar_m.png";
    }

    $userdownloaded = mksize($arr["downloaded"]);
    $useruploaded = mksize($arr["uploaded"]);

    if ($arr["uploaded"] > 0 && $arr["downloaded"] == 0)
        $userratio = "Inf.";
    elseif ($arr["downloaded"] > 0)
        $userratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
    else
        $userratio = "0";

    $age = Age($arr["age"]);

    if ($arr["class"] == "7")
        $spanclass =  'class="membernameAdmin"';
    elseif ($arr["class"] == "3")
        $spanclass =  'class="membernameVIP"';
    else
        $spanclass =  '';

    $lres = SQL_Query_exec("SELECT Color FROM groups WHERE group_id=$arr[class]");
    $larr = mysqli_fetch_assoc($lres);

    echo '<div id="memberCard">
	
	    <div class="memberCardAvatar">
		    '.$country.'
		    <img src="'.$avatar.'" alt="" title="" class="clickable avatar" id="member_info" memberid="'.$arr["id"].'" />
	    </div>

	    <div class="memberCardDetails">';
            if ($arr["privacy"] == "strong" && $CURUSER["class"] <= "6" && $CURUSER["id"] != $arr["id"]){
                echo '<a href="/account-details.php?id='.$arr["id"].'"><span '.$spanclass.' style="color: '.$larr["Color"].'; font-weight: bold;">'.$arr["username"].'</span></a><br />
                This member limits who may view their profile.<br />
                <div>
			        <span class="clickable small" id = "messages_new_message" receiver_membername = "'.$arr["username"].'" > Send Message </span >
			    </div >';
            }else {
                echo '<a href="/account-details.php?id='.$arr["id"].'"><span '.$spanclass.' style="color: '.$larr["Color"].'; font-weight: bold;">'.$arr["username"].'</span></a>, '.$age.' years old, '.$gender.'<br />
		        <span '.$spanclass.' style="color: '.$larr["Color"].'; font-weight: bold;">'.T_($arr["level"]).'</span><br />
		        <b>Member Since:</b> '.$arr["added"].'<br />
		        <b>Last Activity:</b> '.$arr["last_access"].'<br />
		
		        <div id="memberinfoUpDownStats">';
                    if ($arr["banned"] == "yes")
                        echo '<span class="fa-stack fa-lg" title="Banned" ><i class="fa fa-user fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>';
                    elseif ($arr["forumbanned"] == "yes")
                        echo '<span class="fa-stack fa-lg" title="Muted in Comments<br />Muted in Forums<br />Muted in Shoutbox<br />Muted in Private Messages" ><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-microphone-slash fa-stack-1x fa-inverse"></i></span>';
			        echo '<span class="fa-stack fa-lg" title="Uploaded" ><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-upload fa-stack-1x fa-inverse"></i></span> '.$useruploaded.'
    			    <span class="fa-stack fa-lg" title="Downloaded" ><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-download fa-stack-1x fa-inverse"></i></span> '.$userdownloaded.'
	    		    <span class="fa-stack fa-lg" title="Ratio" ><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-balance-scale fa-stack-1x fa-inverse"></i></span> <span class="ratioNull">'.$userratio.'</span>';
		    	    //<img src="/themes/default/status/buffer.png" alt="Buffer" title="Buffer" class="middle" /> 0
		        echo '</div>

    		    <div>
	    		    <span class="clickable small"><a href="/account-details.php?id='.$arr["id"].'">'.$arr["username"].'\'s Profile</a></span>
			        <span class="clickable small" id="messages_new_message" receiver_membername="'.$arr["username"].'">Send Message</span>
			    </div>';
            }
    	echo '</div>

    	<div class="clear"></div>
    </div>';
}

print("<br /><p align='center'>$pagemenu<br />$browsemenu</p>");

stdfoot();

?>