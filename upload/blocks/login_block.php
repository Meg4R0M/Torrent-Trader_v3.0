<?php
if (!$CURUSER) {
	begin_block(T_("LOGIN"));
?>
<form method="post" action="account-login.php">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr><td>
		<tr>
<td align="center"><a href="account-login.php"><button type="button" class='btn btn-inverse'/>Login</button></a><br /><a href="account-signup.php"><button type="button" class="btn btn-success">Signup</button></a><br /><a href="account-recover.php"><button type="button" class="btn btn-warning">Recover Account</button></a></td> </tr>
	</table>
    </form> 
<?php
end_block();

} else {

begin_block('Profile');

	$avatar = htmlspecialchars($CURUSER["avatar"]);
	if (!$avatar)
		$avatar = $site_config["SITEURL"]."/images/default_avatar.png";

	$userdownloaded = mksize($CURUSER["downloaded"]);
	$useruploaded = mksize($CURUSER["uploaded"]);
	$privacylevel = T_($CURUSER["privacy"]);

	if ($CURUSER["uploaded"] > 0 && $CURUSER["downloaded"] == 0)
		$userratio = "Inf.";
	elseif ($CURUSER["downloaded"] > 0)
		$userratio = number_format($CURUSER["uploaded"] / $CURUSER["downloaded"], 2);
	else
		$userratio = "---";
 $res = SQL_Query_exec("SELECT `moodspic` FROM `moods` WHERE `id` = '$CURUSER[moods]'");
	 $row = mysqli_fetch_assoc($res);
	 $moods = ( $row ) ? "<img src='../images/moods/$row[moodspic]' alt='$row[moodspic]' title='$row[moodspic]' />" : 'Unknown';
	print ("<center><img width='120' height='120' src='$avatar' alt='' /></center>");
  print ("<center><br>$moods<br></center>");
?>
<center><a href="../account/"><?php echo T_("Account Setting"); ?></a> <br />  
 
</center>
<?php
end_block();
}
?>
