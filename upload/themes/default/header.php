<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TorrentTrader v3</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $site_config["CHARSET"]; ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
<meta name="generator" content="tt3 <?php echo $site_config['ttversion']; ?>" />
<meta name="description" content="tt3" />
<!-- CSS -->
<!-- Theme css -->
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="<?php echo $site_config["SITEURL"]; ?>/themes/default/theme.css" />
<link rel="stylesheet" type="text/css" href="/themes/default/bootstrap.css">
<!--[if IE]>
    <link rel="stylesheet" type="text/css" href="<?php echo $site_config["SITEURL"]; ?>/themes/default/css/ie.css" />
<![endif]-->
<!-- JS -->
<script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/backend/java_klappe.js"></script>
<!--[if lte IE 6]>
    <script type="text/javascript" src="<?php echo $site_config["SITEURL"]; ?>/themes/default/js/pngfix/supersleight-min.js"></script>
<![endif]-->
<script src="//code.jquery.com/jquery-2.1.0.min.js" type="text/javascript"></script>
<script src="../jquery.js" type="text/javascript"></script>
</head>
<?php
	$page = $_SERVER['REQUEST_URI'];
	$page = str_replace("/","",$page);
	$page = str_replace(".php","",$page);
	$page = str_replace("svn","",$page);  //-- name if tracker installed in a sub-dir 
	$page = str_replace("?search=","",$page);
	$page = $page ? $page : 'index'
?>
<body>
<div id='wrapper'>
  <div id='header'>
    <div id='infobar'>
<!-- START INFOBAR -->
    <div class="fltLeft">
    <?php
        if ($CURUSER["control_panel"]=="yes") {
    
            print("<a class='admincp' href=".$site_config["SITEURL"]."/admincp.php>AdminCP</a> ");
    
        }
       
    ?>
    </div>
    <div class="fltRight">
    <?php
     if (!$CURUSER){
     echo "";
     }else{
   print (T_("<font color='white'>Howdy!</font>")."&nbsp;&nbsp;".class_user($CURUSER[username])."");
    $userdownloaded = mksize($CURUSER["downloaded"]);
    $useruploaded = mksize($CURUSER["uploaded"]);
    
    if ($CURUSER["uploaded"] > 0 && $CURUSER["downloaded"] == 0)
    $userratio = "Inf.";
    elseif ($CURUSER["downloaded"] > 0)
    $userratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);
    else
    $userratio = "---";
    $query_slots = @mysql_fetch_row(@SQL_Query_exec("SELECT COUNT(DISTINCT torrent) FROM peers WHERE userid = $CURUSER[id] AND seeder='no'"));
$maxslot = avail_slots($CURUSER["id"], $CURUSER["class"]);
$slots = number_format($maxslot) . "/" . number_format($query_slots[0]);
    $invites = $CURUSER["invites"];
    $seedbonus = $CURUSER["seedbonus"];
    print (",  &nbsp;&nbsp;<img src='../images/download.png' border='none' height='20' width='20' alt='Downloaded' title='Downloaded'> <font color='#CC0000'><b>$userdownloaded</b> </font>&nbsp;&nbsp; <img src='../images/upload.png' border='none' height='20' width='20' alt='Uploaded' title='Uploaded'> <font color='#009900'><b>$useruploaded</b></font>&nbsp;&nbsp; <img src='../images/ratio.png' border='none' height='20' width='20' alt='Ratio' title='Ratio'> <font color='blue'><b>$userratio</b></font> &nbsp;&nbsp;<img src='../images/invite.png' border='none' height='20' width='20' alt='Invites' title='invites'>&nbsp;&nbsp;<b><font color='white'>Invites</font></b>&nbsp;:&nbsp;<a href='".$site_config["SITEURL"]."../invite.php'><b><font color='purple'>$invites</font></b></a>&nbsp;&nbsp;&nbsp;&nbsp;<font color='white'><b>Download Slots:</b></font> <b><font color='yellow'>$slots</b></font>");
    
    echo " <a class='profile' href='".$site_config["SITEURL"]."/account/'><img src='/images/setting.png' border='none' height='20' width='20' alt='Account Setting' title='Account Setting'></a> <a class='account' href='../user/?id=$CURUSER[id]'><img src='../images/profile.png' border='none' height='20' width='20' alt='Profile' title='Profile'></a> <a class='logout' href=\"".$site_config["SITEURL"]."/account-logout.php\"><img src='../images/logout.png' border='none' height='20' width='20' alt='Logout' title='Logout'></a>";
        
    //check for new pm's
    
    $res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " and unread='yes' AND location IN ('in','both')") or print(mysql_error());
    
    $arr = mysql_fetch_row($res);
    
    $unreadmail = $arr[0];
    
    if ($unreadmail){
    
        print("<embed src='../mail.mp3' autostart='true' width='0' height='0' hidden='true'><a class='mail_n' href=".$site_config["SITEURL"]."/message/?inbox><img src='../images/mails.png' border='none' height='20' width='20' alt='New PM' title='($unreadmail) New PM'S'><font color='red'>($unreadmail)</font><p></a>&nbsp;&nbsp;");
    
    }else{
    
        print("<a class='mail' href=".$site_config["SITEURL"]."/message/><img src='../images/mail.png' border='none' height='20' width='20' alt='My Messages' title='My Messages'></a>&nbsp;");
    
    }
    
    //end check for pm's
    
    }
    
    ?>
    
    </div>
<!-- END INFOBAR -->
    </div>
    <div class='header'>
      <div id='logo'><a href='<?php echo $site_config["SITEURL"]; ?>/'><img src='../images/blank.gif' width='360' height='64' /></a></div>
    </div>
    <div id='menu'>
      <!-- START NAVIGATION -->
      <ul class='menu'>
        <li><a href='../'><span>Home</span></a></li>
        <li><a href='../lounge/'><span>Lounge</span></a></li>
        <li><a href='../upload/'><span>Upload</span></a></li>
        <li><a href='../browse/'><span>Browse</span></a></li>
        <li><a href='../search/'><span>Search</span></a></li>
        <li><a href='../community/'><span>Community</span></a></li>
   <li><a href='../members/'><span>Members</span></a></li>
   <li><a href='../latest/'><span>Latest</span></a></li>
        
       </ul>
      <!-- END NAVIGATION -->
    </div>
  </div>
  <div class='myTable'>
    <div class='myTrow'>
      <div class='shad-l'><img src='<?php echo $site_config["SITEURL"]; ?>../images/blank.gif' width='9px' height='9px' /></div>
      <div class='main'>
        <table width='100%' border='0' cellspacing='10' cellpadding='0'>
          <tr>
            <td valign='top'>
            <!-- START MAIN COLUM -->