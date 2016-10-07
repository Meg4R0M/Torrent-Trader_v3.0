<?php
// Most users online
$monli = "SELECT * FROM mostonline";
$result = SQL_Query_exec($monli);
$details = mysqli_fetch_array($result);

if ($totalonline > $details['amount'])
{
SQL_Query_exec("UPDATE mostonline SET amount = $totalonline");
SQL_Query_exec("UPDATE mostonline SET date = now()");
}

$date1=date("D, d M Y H:i:s", strtotime($details['date']));


// Most users online end
//CODE
    if ($site_config["LEFTNAV"]): # Changed.
    if (isset($_COOKIE["blockswitcher"]) && $_COOKIE["blockswitcher"] == "left") {  $blocks = leftblocks(); $block = "left"; }
    if (isset($_COOKIE["blockswitcher"]) && $_COOKIE["blockswitcher"] == "right"){ $blocks = rightblocks(); $block = "right"; }  
    if (!isset($_COOKIE["blockswitcher"])){
         leftblocks();
    }else{
        echo $blocks;
    }
        // echo $blocks;
    
    
    if ($block == "left")
        $link = "<a href='./themes/default/blockswitcher.php?switch=right'><img src='/themes/default/images/lcol.jpg' border='0'></a>";
    else
        $link = "<a href='./themes/default/blockswitcher.php?switch=left'><img src='/themes/default/images/rcol.jpg' border='0'></a>"; 
    ?>
            </td>
            <!-- END RIGHT COLUMN -->
          </tr>
        </table>
        
    <div id="col-switch">
    <?php echo $link; ?>
    
    <?php endif; # Changed. ?> 
    
    </div>
    <?php
$date_time = get_date_time(gmtime()-(3600*24)); // the 24hrs is the hours you want listed
$registered = number_format(get_row_count("users"));
$ncomments = number_format(get_row_count("comments"));
$nmessages = number_format(get_row_count("messages"));
$ntor = number_format(get_row_count("torrents"));
$totaltoday = number_format(get_row_count("users", "WHERE users.last_access>='$date_time'"));
$regtoday = number_format(get_row_count("users", "WHERE users.added>='$date_time'"));
$todaytor = number_format(get_row_count("torrents", "WHERE torrents.added>='$date_time'"));
$guests = number_format(getguests());
$seeders = get_row_count("peers", "WHERE seeder='yes'");
$leechers = get_row_count("peers", "WHERE seeder='no'");
$members = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 900"));
$totalonline = $members + $guests;

$result = SQL_Query_exec("SELECT SUM(downloaded) AS totaldl FROM users"); 
while ($row = mysqli_fetch_array($result)) { 
    $totaldownloaded = $row["totaldl"]; 
} 

$result = SQL_Query_exec("SELECT SUM(uploaded) AS totalul FROM users"); 
while ($row = mysqli_fetch_array($result)) { 
    $totaluploaded      = $row["totalul"]; 
}
$localpeers = $leechers+$seeders;

begin_frame("$members2 Users Online Last 24 Hours");

$resew = SQL_Query_exec("SELECT id, username, class, donated, warned FROM users WHERE UNIX_TIMESTAMP('".get_date_time()."') - UNIX_TIMESTAMP(users.last_access) <= 86400 ORDER BY username");
while ($arr = mysqli_fetch_assoc($resew))
{
if ($todayactive)
     $todayactive .= ", ";
switch ($arr["class"])
{
     case 7:
     $arr["username"] = "<font color=#FF0000>" . class_user($arr['username']) . "</font>";
     break;
     case 6:
     $arr["username"] = "<font color=#00FF00>" . class_user($arr['username']) . "</font>";
     break;
     case 5:
     $arr["username"] = "<font color=#009900>" . class_user($arr['username']) . "</font>";
     break;
     case 4:
     $arr["username"] = "<font color=#0000FF>" . class_user($arr['username']) . "</font>";
     break;
     case 3:
     $arr["username"] = "<font color=#990099>" . class_user($arr['username']) . "</font>";
     break;
     case 2:
     $arr["username"] = "<font color=#FF7519>" . class_user($arr['username']) . "</font>";
     break;
     case 1:
     $arr["username"] = "<font color=#00FFFF>" . class_user($arr['username']) . "</font>";
     break;                
         }
    
     $donator = $arr["donated"] > 0;
if ($CURUSER) {
     $todayactive .= "<b><a href=../user/?id=" . $arr["id"] . ">" . class_user($arr['username']) . "</b></a></a>";
} else {
     $todayactive .= "<b><a href=../user/?id=" . $arr["id"] . ">" . class_user($arr['username']) . "</b></a></a>";
}
if ($donator) {
     $todayactive .= "<img src=\"images/star.gif\" title=\"You have contributed to the success of the site!\">";
}
$warned = $arr["warned"] == "yes";
if ($warned) {
     $todayactive .= "<img src=\"images/warn.gif\" title=\"You had a warning from staff!!\">";
}
$usersactivetoday++;
         }

echo "<div align='left'>" . $todayactive . "</div>";
echo "<br />";
end_frame();
}
?>
