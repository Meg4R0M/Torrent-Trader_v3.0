<?php
require_once("backend/functions.php");

dbconn(false);

global $CURUSER;

//GET CURRENT USERS THEME AND LANGUAGE
if ($CURUSER){
	$ss_a = @mysqli_fetch_array(@SQL_Query_exec("select uri from stylesheets where id=" . $CURUSER["stylesheet"])) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if ($ss_a)
		$THEME = $ss_a[uri];
	$lng_a = @mysqli_fetch_array(@SQL_Query_exec("select uri from languages where id=" . $CURUSER["language"])) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if ($lng_a)
		$LANGUAGE =$lng_a[uri];
}else{//not logged in so get default theme/language
	$ss_a = mysqli_fetch_array(SQL_Query_exec("select uri from stylesheets where id='" . $site_config['default_theme'] . "'")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if ($ss_a)
		$THEME = $ss_a[uri];
	$lng_a = mysqli_fetch_array(SQL_Query_exec("select uri from languages where id='" . $site_config['default_language'] . "'")) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if ($lng_a)
		$LANGUAGE = $lng_a[uri];
}
@((mysqli_free_result($lng_a) || (is_object($lng_a) && (get_class($lng_a) == "mysqli_result"))) ? true : false);
@((mysqli_free_result($ss_a) || (is_object($ss_a) && (get_class($ss_a) == "mysqli_result"))) ? true : false);
if ($CURUSER){
	if(!isset($_GET['history'])){
		?>
		<HTML>
		<HEAD>
			<TITLE><?=$site_config['SITENAME']?>Shoutbox</TITLE>
			<META HTTP-EQUIV="refresh" content="100">
			<link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/css/shoutbox.css" />
		</HEAD>
		<body class="shoutbox_body">
		<?
		echo '<div class="shoutbox_contain"><table border="0" background="#ffffff" style="width: 99%; table-layout:fixed">';
	}else{
		?>
		<HTML>
		<HEAD>
			<TITLE><?=$site_config['SITENAME']?>Historique de la Shout</TITLE>
			<META HTTP-EQUIV="refresh" content="100">
			<link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/css/shoutbox.css" />
		</HEAD>
		<body class="shoutbox_body">
		<?php
		//stdhead("Shoutbox History",0);
		//begin_frame("Shoutbox History");
		echo '<div class="shoutbox_history">';

		$query = 'SELECT COUNT(shoutbox.id) FROM shoutbox';
		$result = SQL_Query_exec($query);
		$row = mysqli_fetch_row($result);
		echo '<div align="middle">Pages: ';
		$count = $row[0];
		$perpage = 20;
		//$i = 1;
		list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?history=1&");

		echo $pagertop;


		echo '</div></br><table border="0" background="#ffffff" style="width: 99%; table-layout:fixed">';
	}
	@((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false);



	$query = 'SELECT s.*, u.avatar FROM shoutbox s left join users u on s.uid=u.id left join groups g on g.group_id=u.class ORDER BY id DESC '.$limit;

	$result = SQL_Query_exec($query);
	$alt = false;

	while ($row = mysqli_fetch_assoc($result)) {

		$i = 0; $i < $num; ++$i;
		if ($alt){
			echo '<tr class="shoutbox_noalt">';
			$alt = false;
		}else{
			echo '<tr class="shoutbox_alt">';
			$alt = true;
		}
		# getting the data array
		$id   = $row[id];
		$uid  = $row[uid];
		$time = date(' G:i ', 3600 + utc_to_tz_time($row['date']));
		//$name = $row[name];
		$text = $row[text];
		echo '<td style="font-size: 9px; width: 118px;">';
		echo "<div align='left' style='float: left'>";

		if ( ($CURUSER["edit_users"]=="yes") || ($CURUSER['id'] == $row['uid']) ){
			$mid=$row['id'];
			$edit="<a href='javascript:editup($mid,$CURUSER[id]);' style='font-size: 9px'><img src=images/shout/edit.png title=edit alt=edit border=0></a>&nbsp;<a href='javascript:delup($mid);' style='font-size: 9px'><img src=images/shout/delchat.gif border=0 title=Sup alt=del></a>";
		}else{
			$edit="#";
		}
		$UClass=@mysqli_fetch_array(@SQL_Query_exec("SELECT Color, level,  username, gender, avatar, added, age, class, donated, warned FROM users JOIN groups ON users.class=groups.group_id WHERE users.id=".$row[uid].""));

		$don = $UClass["donated"] > 0 ? "<img src=".$site_config['SITEURL']."/images/users/money.png alt=''>" : "";
		$vip = $UClass["class"] == 6 ? "<img src=".$site_config['SITEURL']."/images/users/star.gif alt=''>" : "";
		$warn = $UClass["warned"] == "yes" ? "<img src=".$site_config['SITEURL']."/images/users/warn.gif alt=''>" : "";

		$av=$UClass['avatar'];
		if(!empty($av)){
			$av="<img src='".$UClass[avatar]."' alt='my_avatar' width='30 height='30'>";
		}
		else{
			$av="<img src='images/default_avatar.png' alt='my_avatar' width='30' height='30'>";
		}
		@((mysqli_free_result($UClass) || (is_object($UClass) && (get_class($UClass) == "mysqli_result"))) ? true : false);

//strong privacy we will gide status

		$name = "".$row['name']."";
		$mail = $CURUSER['username'] == $row['name'] ? "" : '<a href=mailbox.php?compose&id='.$row['uid'].' target="_parent"><img src="images/shout/pm.png" border="0" title="Envoyer un MP"></a>';
		#putting the chat together.
		$chatout = " 
                 <li><div class='chatoutput'><span class='name'>".$time."&nbsp;$av&nbsp;".$edit."&nbsp;$mail&nbsp;<a href=account-details.php?id=".$uid." ><font color=\"".$UClass['Color']."\"><b>".$name."</b></font></a>".$vip."".$don."".$warn." : <font size=3 color=\"".$UClass['Color']."\">".nl2br(format_comment($row['text']))."</font></span></div></li> 

                 ";

		echo $chatout; # echo as known handles arrays very fast...
	}
}
?>
	<script>
		function SmileIT(smile){
			document.forms['shoutboxform'].elements['message'].value = document.forms['shoutboxform'].elements['message'].value+" "+smile+" ";  //this non standard attribute prevents firefox' autofill function to clash with this script
			document.forms['shoutboxform'].elements['message'].focus();
		}
		function PopMoreSmiles(form,name) {
			link='moresmiles.php?form='+form+'&text='+name
			newWin=window.open(link,'moresmile','height=500,width=350,resizable=yes,scrollbars=yes');
			if (window.focus) {newWin.focus()}
		}
		function Pophistory() {
			link='shoutbox.php?history=1&page=0'
			newWin=window.open(link,'moresmile','height=500,width=500,resizable=yes,scrollbars=yes');
			if (window.focus) {newWin.focus()}
		}
		function windowunder(link)
		{
			window.opener.document.location=link;
			window.close();
		}
	</script>

	</table><br><br>
	<div valign=bottom style="margin-bottom:40px;"><center><a href='javascript:window.close();'>Fermer</a></center></div><br>
<?php echo $pagerbottom;?>
	</div>
	<br>
	<script language=javascript>

		function GiveMsgBoxFocus()
		{
			document.shoutboxform.message.focus();
		}
	</script>
</body>
	</html>
<?php

?>