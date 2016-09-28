              <?php
				if ($site_config["MIDDLENAV"]){
					middleblocks();
				} //MIDDLENAV ON/OFF END
			  ?>
              <!-- END MAIN COLUM -->
            </td>
            <!-- START RIGHT COLUMN -->
            <td width='220' valign='top'>
    <?php
    // Most users online
$monli = "SELECT * FROM mostonline";
$result = SQL_Query_exec($monli);
$details = mysql_fetch_array($result);

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
        $link = "<a href='../themes/default/blockswitcher.php?switch=right'><img src='../images/right.png' height='24' width='24' border='0'></a>";
    else
        $link = "<a href='../themes/default/blockswitcher.php?switch=left'><img src='../images/left.png' height='24' width='24' border='0'></a>"; 
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
while ($row = mysql_fetch_array ($result)) { 
	$totaldownloaded = $row["totaldl"]; 
} 

$result = SQL_Query_exec("SELECT SUM(uploaded) AS totalul FROM users"); 
while ($row = mysql_fetch_array ($result)) { 
	$totaluploaded      = $row["totalul"]; 
}
$localpeers = $leechers+$seeders;
// Members OnLine - Mod by L3oncoder
begin_frame("Stats");
$monli = "SELECT * FROM mostonline";
$result = SQL_Query_exec($monli);
$details = mysql_fetch_array($result);

if ($totalonline > $details['amount'])
{
SQL_Query_exec("UPDATE mostonline SET amount = $totalonline");
SQL_Query_exec("UPDATE mostonline SET date = now()");
}

$date1=date("D, d M Y H:i:s", strtotime($details['date']));
$file = "".$site_config["cache_dir"]."/cache_usersonlineblock.txt";
$expire = 10; // time in seconds
$guests = number_format(getguests());
$members = number_format(get_row_count("users", "WHERE UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 900"));
echo "<B> " . $guests . "</b> Guests - <B> " . $members . "</b> Members<br>";
$a = @mysql_fetch_assoc(@mysql_query("SELECT id,username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1"));
if ($CURUSER)
$latestuser = "<a href=../user/?id=" . $a["id"] . ">" . $a["username"] . "</a>";
else
$latestuser = "<b>$a[username]</b>";
echo "Welcome to our newest members: $latestuser<br>OnLine:<br>";
if (file_exists($file) &&
filemtime($file) > (time() - $expire)) {
$usersonlinerecords = unserialize(file_get_contents($file));
}else{
$usersonlinequery = mysql_query("SELECT id, username, class FROM users WHERE privacy !='strong' AND UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(users.last_access) < 900") or die(mysql_error());

while ($usersonlinerecord = mysql_fetch_array($usersonlinequery) ) {
$usersonlinerecords[] = $usersonlinerecord;
}
$OUTPUT = serialize($usersonlinerecords);
$fp = fopen($file,"w");
fputs($fp, $OUTPUT);
fclose($fp);
} // end else
if ($usersonlinerecords == ""){
echo "No members OnLine";
}else{
foreach ($usersonlinerecords as $user) {
switch($user["class"]){
		case 1:
			$color = "#00FFFF";// user
			break;
		case 2:
			$color = "#FF7519";// power user
			break;
		case 3:
			$color = "#990099";// VIP
			break;
		case 4:
			$color = "#0000FF";// uploader
			break;
		case 5:
			$color = "#009900";//moderator
			break;
		case 6:
			$color = "#00FF00";//super moderator
			break;
		case 7:
			$color = "#FF0000";// you and most trusted 
			break;
}
print("<a href=\"../user/?id=".$user["id"]."\"><font style=\" color: ".$color."\">".class_user($user["username"])."</font></a>, ");
}


echo "<br /><b>".T_("TORRENTS")."</b>";
echo "<br /><small>".T_("TRACKING").":<small><b> $ntor ".P_("TORRENT", $ntor)."</b></small>";
echo "<br /><small>".T_("NEW_TODAY").":<small><b> " . $todaytor . "</b></small>";
echo "<br />".T_("SEEDERS").":<b> " . number_format($seeders) . "</b><br />".T_("LEECHERS").":<b> " . number_format($leechers) . "</b>";
echo "<br /><small>".T_("PEERS").":<b> " . number_format($localpeers) . "</b></small>";
echo "<br /><small>".T_("DOWNLOADED").":<b> " . mksize($totaldownloaded) . "</b></small>";
echo "<br /><small>".T_("UPLOADED").":<b> " . mksize($totaluploaded) . "</b></small>";

echo "<br /><b>Total Members</b>";
echo "<b> $registered ".P_("MEMBER", $registered)."</b>";
print("<center><font color='red' size='2'><b>Administrator</b></font> | <font color='#00FF00' size='2'><b>Super Moderator</b></font>  | <font color='#009900' size='2'><b>Moderator</b></font> | <font color='#0000FF' size='2'><b>Uploader</b></font> | <font color='#990099' size='2'><b>V.I.P</b></font> | <font color='#FF7519' size='2'><b>Power User</b></font> | <font color='#00FFFF' size='2'><b>User</b></font></center>, ");

end_frame();
begin_frame("$members2 Users Online Last 24 Hours");

$resew = SQL_Query_exec("SELECT id, username, class, donated, warned FROM users WHERE UNIX_TIMESTAMP('".get_date_time()."') - UNIX_TIMESTAMP(users.last_access) <= 86400 ORDER BY username");
while ($arr = mysql_fetch_assoc($resew))
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
echo "<BR>";
end_frame();
}
if ($site_config['DISCLAIMERON']){
	begin_frame(T_("DISCLAIMER"));
	echo T_("DISCLAIMERTXT");
	end_frame();
}
?>
  <!-- START FOOTER CODE -->
        <?php
        //
        // *************************************************************************************************************************************
        //			PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
        // *************************************************************************************************************************************
        print ("<CENTER>Powered by <a href=\"http://www.torrenttrader.org\" target=\"_blank\">TorrentTrader v".$site_config["ttversion"]."</a> - ");
        $totaltime = array_sum(explode(" ", microtime())) - $GLOBALS['tstart'];
        printf("Page generated in %f", $totaltime);
        print (" - Theme By: <a href=\"http://nikkbu.info\" target=\"_blank\">Nikkbu</a> & <a href=\"https://www.facebook.com/l3oncod3r\" target=\"_blank\">leoncoder</a></CENTER>");
        //
        // *************************************************************************************************************************************
        //			PLEASE DO NOT REMOVE THE POWERED BY LINE, SHOW SOME SUPPORT! WE WILL NOT SUPPORT ANYONE WHO HAS THIS LINE EDITED OR REMOVED!
        // *************************************************************************************************************************************
        //
        ?>
        <!-- END FOOTER CODE -->


</body>
</html>
<?php ob_end_flush(); ?>