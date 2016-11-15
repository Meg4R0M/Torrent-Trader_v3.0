<?php
require_once("backend/functions.php");
dbconn();

$msg = trim($_POST["msg"]);
$msg = str_replace(array("%","+","&","{","}","@","$","frame","*"),array(" % "," + "," & "," { "," } "," @ "," $ "," frame "," * "),$msg);
$subject = trim($_POST["subject"]);
$subject = str_replace(array("%","+","&","{","}","@","$","frame","*"),array(" % "," + "," & "," { "," } "," @ "," $ "," frame "," * "),$subject);

if (!$msg)
    show_error_msg("Erreur","Merci d'indiquer votre message !");

if (!$subject)
    stderr("Erreur","Merci d'indiquer le sujet !");

$added = "'" . get_date_time() . "'";
$userid = $CURUSER['id'];
$message = sqlesc($msg);
$subject = sqlesc($subject);
$dt = sqlesc(get_date_time());
$res = SQL_Query_exec("SELECT id FROM users LEFT JOIN groups ON users.class=groups.group_id WHERE groups.control_panel='yes'");
while($arr=mysqli_fetch_row($res))
    SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, added, msg) VALUES(0, 0, $arr[0], $dt, '[url=".$site_config['SITEURL']."/mailbox.php?compose&id=".$CURUSER['id']."][color=red]" .$CURUSER['username']. "[/color][/url] a envoyé un message dans le [url=".$site_config["SITEURL"]."/staffbox.php][color=red]Staff Box[/color][/url]')");
SQL_Query_exec("INSERT INTO staffmessages (sender, added, msg, subject) VALUES($userid, $added, $message, $subject)") ;
$query = "INSERT INTO shoutbox (uid, date, name, text) VALUES (NULL, 'Le Staff au boulot', '[url=".$site_config['SITEURL']."/account-details.php?id=".$CURUSER['id']."][color=red]" .$CURUSER['username']. "[/color][/url] a envoyé un message dans le [url=".$site_config["SITEURL"]."/staffbox.php][color=red]Staff Box[/color][/url]', now(), '0')";
mysqli_query($GLOBALS["___mysqli_ston"], $query);
if ($_POST["returnto"])
{
    header("Location: index.php");
    die;
}

stdhead();
show_done_msg("Succ&#232;s", "Votre message a été envoyé avec succ&#232;s ! <br><br> Merci de patienter, le staff mets tout en oeuvre pour vous répondre dans les plus brefs délai");

stdfoot();
exit;
?>