<?php

require_once("backend/functions.php");
require_once("backend/smilies.php");
dbconn(false);


$action = mysql_real_escape_string($_GET["action"]);

function smile() {

  print "<div align='center'><table cellpadding='1' cellspacing='1'><tr>";

  global $smilies, $count;
  reset($smilies);

  while ((list($code, $url) = each($smilies)) && $count<16) {
        print("\n<td><a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."')\">
               <img border=\"0\" src=\"images/smilies/$url\" alt=\"$code\" /></a></td>");
               
        $count++;
  }

  print "</tr></table></div>";

}

function redirect($redirecturl) {
    global $language;

  if (headers_sent()) {
?>
<script language="javascript">
  window.location.href='<?php echo $redirecturl; ?>';
</script>
<meta http-equiv="refresh" content="2" charset="UTF-8;<?php echo $redirecturl; ?>">
<?php
        echo sprintf("Redirection", $redirecturl);
    } else
    header('Location: '.$redirecturl);
    die();
}
//GET CURRENT USERS THEME AND LANGUAGE
if ($CURUSER){
	$ss_a = @mysql_fetch_array(@SQL_Query_exec("select uri from stylesheets where id=" . $CURUSER["stylesheet"])) or die(mysql_error());
	if ($ss_a)
		$THEME = $ss_a[uri];
		$lng_a = @mysql_fetch_array(@SQL_Query_exec("select uri from languages where id=" . $CURUSER["language"])) or die(mysql_error());
	if ($lng_a)
		$LANGUAGE =$lng_a[uri];
}else{//not logged in so get default theme/language
	$ss_a = mysql_fetch_array(SQL_Query_exec("select uri from stylesheets where id='" . $site_config['default_theme'] . "'")) or die(mysql_error());
	if ($ss_a)
		$THEME = $ss_a[uri];
	$lng_a = mysql_fetch_array(SQL_Query_exec("select uri from languages where id='" . $site_config['default_language'] . "'")) or die(mysql_error());
	if ($lng_a)
		$LANGUAGE = $lng_a[uri];
}
@mysql_free_result($lng_a);
@mysql_free_result($ss_a);


if (isset($_GET['del'])){

	if (is_numeric($_GET['del'])){
		$query = "SELECT * FROM shoutbox WHERE id=".$_GET['del'] ;
		$result = SQL_Query_exec($query);
	}else{
		echo "invalid msg id STOP TRYING TO INJECT SQL";
		exit;
	}

	$row = mysql_fetch_row($result);
		
	if ($CURUSER["id"] != $row["uid"]){	
		$query = "DELETE FROM shoutbox WHERE id=".$_GET['del'] ;
		write_log("<B><font color=orange>Supprimer Shout: </font> Supprimer par ".$CURUSER['username']."</b>");
		SQL_Query_exec($query);  
    

                 ?><html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/theme.css" />
</head>
<?php 
 sleep(2);               
 echo"
<body onload=\"window.close();\" bgcolor=black>"
;

echo"<center><h1>Succès<br>Message Sauvegarder!</center></h1>
</body>
</html>";
exit();	
	}
}
 if (isset($_GET['purge'])){

	$local_time = get_date_time(time());
$res = SQL_Query_exec ("delete from `shoutbox` WHERE uid='0'");
$msg = "[color=red]Les infos systeme ont été purgés[/color]";
SQL_Query_exec("INSERT INTO `shoutbox` (`uid` ,`date` ,`name` ,`text`) VALUES ('0', '".$local_time."', '".$site_config['SITENAME']."', '[color=red]Les infos systeme ont été purgés[/color]')") ;

                 ?><html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/theme.css" />
</head>
<?php 
 sleep(2);               
 echo"
<body onload=\"window.close();\" bgcolor=black>"
;

echo"<center>Message Sauvegarder!</center>
</body>
</html>";
exit();	
	
}

if (substr($action, 0, 4)=="edit"){ 
	$msgid = $_GET["msgid"];
	
    $res = SQL_Query_exec("SELECT * FROM shoutbox WHERE id=".$_GET['msgid']) or sqlerr();
	if (mysql_num_rows($res) != 1)
		print("No message with ID $msgid.");
	$arr = mysql_fetch_assoc($res);
    if ($CURUSER["id"] != $arr["uid"])
		print("");
    $save = (int)$_GET["save"];
    if ($save) {
		$message = $_POST['message'];
			if ($message == "")
				print("");
		$message = sqlesc($message);
		SQL_Query_exec("UPDATE shoutbox SET text=$message WHERE id=".$_GET['msgid']) or sqlerr();
                ?><html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/theme.css" />
</head>
<?php

                echo"
<body onload=\"window.close();\" bgcolor=black>";

echo"<center><h1>Success<br>Message Sauvegardé!</center>
</body>
</html>";
sleep(2);
exit();
	}
	?><html>
<head>
<link rel="stylesheet" type="text/css" href="<?=$site_config['SITEURL']?>/themes/<?=$THEME?>/theme.css" />
</head>
<body bgcolor=black text=white>
<?php
    
    print("<center><font size=3><b>Editer votre  Message</b></font></center>\n");
    print("<form name=chatForm method=post action=chatedit.php?action=edit&save=1&msgid=$msgid>\n");
    print("<center><table border=0 cellspacing=0 cellpadding=5>\n");
    print("<tr><td>\n");
    print("</td><td charset='UTF-8' style='padding: 0px'><textarea name=message id=\"message\" cols=50 rows=10 >" .htmlspecialchars($arr["text"]) . "</textarea></td></tr>\n");
   
    smile();
    
    print("<br><tr><td align=center colspan=4><input type=submit value='Confirmer' class=btn></td></tr><br/>\n");
    
    print("</table></center>\n");
    print("</form>\n");
?>
</body></html>
<?php    
}
?>
<script>
function SmileIT(smile){
    document.forms['chatForm'].elements['message'].value = document.forms['chatForm'].elements['message'].value+" "+smile+" ";  //this non standard attribute prevents firefox' autofill function to clash with this script
    document.forms['chatForm'].elements['message'].focus();
}

</script>

<?php
?>
