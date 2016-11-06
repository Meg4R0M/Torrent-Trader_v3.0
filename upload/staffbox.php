<?php
require_once("backend/functions.php");

$mod_class = 5; // Change this to what ever class you want accessing this page.
$del_class = 6; // Change this to what ever class you want to be able to delete the staff messages.
$spam = 0;
dbconn(false);
loggedinonly();

if ($CURUSER["class"] < $mod_class)
    show_error_msg("Error", "Permission denied.", 1);

$action = $_GET["action"];

if (!$action) {
    stdhead("MP Staff");
    begin_frame("MP au Staff");

    $res = SQL_Query_exec("SELECT count(id) FROM staffmessages");
    $row = mysqli_fetch_array($res);
    $url = " .$_SERVER[PHP_SELF]?";
    $count = $row[0];
    $perpage = 20;

    list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $url);

    print("<h1 align=center><b>Message pour le STAFF</b></h1>");

    if ($count == 0) {
        print("<h2 style='color: red;font-weight: bold'>Aucun MP Staff !</h2>");
    } else {
        echo $pagertop;

        echo '<form method="post" action="staffbox.php?action=takecontactanswered">
            <table width="765" border="1" cellspacing="0" cellpadding="5" align="center">
                <tr>
                    <td class="ttable_head" align="center"> Sujet </td> 
                    <td class="ttable_head" align="center"> De </td>
                    <td class="ttable_head" align="center"> Ajouté le </td>
                    <td class="ttable_head" align="center"> Traité </td>';
                    if ($CURUSER["class"] >= $del_class) {
                        echo '<td class="ttable_head" align="center"> Marqué comme traité </td> 
                        <td class="ttable_head" align="center"> Supprimer </td>';
                    }
                echo '</tr>';
                $res = SQL_Query_exec("SELECT staffmessages.id, staffmessages.added, staffmessages.subject, staffmessages.answered, staffmessages.answeredby, staffmessages.sender, staffmessages.answer, users.username FROM staffmessages INNER JOIN users on staffmessages.sender = users.id ORDER BY id desc $limit");
                while ($arr = mysqli_fetch_assoc($res)) {
                    if ($arr["answered"]) {
                        $res3 = SQL_Query_exec("SELECT username FROM users WHERE id=$arr[answeredby]");
                        $arr3 = mysqli_fetch_assoc($res3);
                        $answered = '<span style="color: green; font-weight: bold">Oui - <a href="account-details.php?&id='.$arr["answeredby"].'">'.class_user($arr3["username"]).'</a> (<a href="staffbox.php?action=viewanswer&pmid='.$arr["id"].'">Voir la réponse</a>)<span/>';
                    } else
                        $answered = '<span style="color: red; font-weight: bold">Non</span>';
                    $pmid = $arr["id"];
                    echo '<tr>
                        <td><a href="'.$site_config["SITEURL"].'/staffbox.php?action=viewpm&pmid='.$pmid.'"><b>'.$arr["subject"].'</b></td>
                        <td><a href="'.$site_config["SITEURL"].'/account-details.php?&id='.$arr["sender"].'"><b>'.class_user($arr["username"]).'</b></a></td>
                        <td>'.$arr["added"].'</td>
                        <td align=left>'.$answered.'</td>';
                        if ($CURUSER["class"] >= $del_class) {
                            echo '<td><input type="checkbox" name="setanswered[]" value="' . $arr["id"] . '" />Marquer comme traité</td>
                            <td><a href="' . $site_config["SITEURL"] . '/staffbox.php?action=deletestaffmessage&id=' . $arr["id"] . '">Supprimer</a></td>';
                        }
                    echo '</tr>';
                }
            echo '</table>
            <p align="center"><input type="submit" value="Confirmer"></p>
        </form>';
        echo $pagerbottom;
    }
    end_frame();
    stdfoot();
}
//////////////////////////
// VIEW PM'S //
//////////////////////////
if ($action == "viewpm") {
    $pmid = (int)$_GET["pmid"];
    $ress4 = SQL_Query_exec("SELECT id, subject, sender, added, msg, answeredby, answered FROM staffmessages WHERE id=$pmid");
    $arr4 = mysqli_fetch_assoc($ress4);
    $answeredby = $arr4["answeredby"];
    $rast = SQL_Query_exec("SELECT username FROM users WHERE id=".$answeredby."");
    $arr5 = mysqli_fetch_assoc($rast);
    $senderr = $arr4["sender"];
    if (is_valid_id($arr4["sender"])) {
        $res2 = SQL_Query_exec("SELECT username FROM users WHERE id=" . $arr4["sender"]);
        $arr2 = mysqli_fetch_assoc($res2);
        $sender = '<a href="account-details.php?&id='.$senderr.'">'.class_user(($arr2["username"] ? $arr2["username"] : "Deleted User")).'</a>';
    } else
        $sender = "Syst&#232;me";
    $subject = $arr4["subject"];
    if ($arr4["answered"] == '0') {
        $answered = '<span style="color: red; font-weight: bold;">Non</span>';
    } else {
        $answered = '<span style="color: blue; font-weight: bold;">Oui</span> Par <a href="account-details.php?&id='.$answeredby.'">'.class_user($arr5["username"]).'</a> (<a href="'.$site_config["SITEURL"].'/staffbox.php?action=viewanswer&pmid='.$pmid.'">Voir le Message</a>)';
    }
    if ($arr4["answered"] == '0') {
        $setanswered = '[<a href="'.$site_config["SITEURL"].'/staffbox.php?action=setanswered&id='.$arr4["id"].'">Marquer comme trait&eacute;</a>]';
    } else {
        $setanswered = "";
    }
    $iidee = $arr4["id"];
    $elapsed = get_elapsed_time(sql_timestamp_to_unix_timestamp($arr4["added"]));
    stdhead("MP Staff");
    begin_frame("Staff Message");
    echo '<div class="table">
	    <div class="row">
		    <div class="cell">
			    <label>From:</label> <b>'.$sender.'</b> le '.$arr4["added"].' (il y a '.$elapsed.')
		    </div>
	    </div>
	    <div class="row">
		    <div class="cell">
			    <label>Subject:</label> <span style="color: darkred; font-weight: bold;">'.$subject.'</span>
		    </div>
	    </div>
	    <div class="row">
		    <div class="cell">
			    <label>Answered:</label> '.$answered.'&nbsp;&nbsp;'.$setanswered.'
		    </div>
	    </div>
	    <div class="row">
		    <div class="cell">
		        <div><label>Message:</label></div>
			    <div style="border: 1px solid black; padding: 10px;">'.format_comment($arr4["msg"]).'</div>
		    </div>
	    </div>
	    <div class="row">
		    <div class="cell">';
    if ($arr4["sender"])
        echo '<a href="'.$site_config["SITEURL"].'/staffbox.php?action=answermessage&receiver='.$arr4["sender"].'&answeringto='.$iidee.'"><span style="font-weight: bold">Answer</span></a> ';
    else
        echo '<span style="color: grey; font-weight: bold">Answer</span> ';
    echo '| <a href="'.$site_config["SITEURL"].'/staffbox.php?action=deletestaffmessage&id='.$arr4["id"].'"><b>Delete</b></a>
		    </div>
	    </div>
	</div>';
    end_frame();
    stdfoot();

}
//////////////////////////
// VIEW ANSWERS //
//////////////////////////
if ($action == "viewanswer") {
    $pmid = (int) $_GET["pmid"];
    $ress4 = SQL_Query_exec("SELECT id, subject, sender, added, msg, answeredby, answered, answer FROM staffmessages WHERE id=$pmid");
    $arr4 = mysqli_fetch_assoc($ress4);
    $answeredby = $arr4["answeredby"];
    $rast = SQL_Query_exec("SELECT username FROM users WHERE id=$answeredby");
    $arr5 = mysqli_fetch_assoc($rast);
    if (is_valid_id($arr4["sender"])) {
        $res2 = SQL_Query_exec("SELECT username FROM users WHERE id=" . $arr4["sender"]);
        $arr2 = mysqli_fetch_assoc($res2);
        $sender = "<a href='account-details.php?&id=$senderr'>" . class_user(($arr2["username"] ? $arr2["username"] : "Compte Supprimé")) . "</a>";
    } else
        $sender = "System";
    if ($arr4['subject'] == "") {
        $subject = "No subject";
    } else {
        $subject = "<a style='color: darkred' href=staffbox.php?action=viewpm&pmid=$pmid>".$arr4['subject']."</a>";
    }
    $iidee = $arr4["id"];
    if ($arr4[answer] == "") {
        $answer = "This message have no answer !";
    } else {
        $answer = $arr4["answer"];
    }
    stdhead("Staff PM's");
    begin_frame("Answer");

    echo '<div class="table">
	    <div class="row">
		    <div class="cell">
			    <b><a href="account-details.php?&id='.$answeredby.'">'.class_user($arr5["username"]).'</a></b> answered to '.$sender.'
		    </div>
	    </div>
	    <div class="row">
		    <div class="cell">
			    <label>Subject:</label> <span style="color: darkred; font-weight: bold;">'.$subject.'</span>
		    </div>
	    </div>
	    <div class="row">
		    <div class="cell">
		        <div><label>Answer:</label></div>
			    <div style="border: 1px solid black; padding: 10px;">'.format_comment($answer).'</div>
		    </div>
	    </div>
	</div>';

    end_frame();
    stdfoot();
}
//////////////////////////
// ANSWER MESSAGE //
//////////////////////////
if ($action == "answermessage") {
    $answeringto = (int)$_GET["answeringto"];
    $receiver = (int)$_GET["receiver"];
    if (!is_valid_id($receiver))
        die;
    elseif (!is_valid_id($answeringto))
        die;
    $res = SQL_Query_exec("SELECT * FROM users WHERE id=$receiver");
    $user = mysqli_fetch_assoc($res);
    if (!$user)
        show_error_msg("Error", "No user with that ID.");
    $res2 = SQL_Query_exec("SELECT * FROM staffmessages WHERE id=$answeringto");
    $array = mysqli_fetch_assoc($res2);
    stdhead("MP staff", false);
    begin_frame("Answer");
    echo '<table class="main" width="450" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="embedded">
                <div align="center">
                    <h2>Answiring to <a href="'.$site_config["SITEURL"].'/staffbox.php?action=viewpm&pmid='.$array['id'].'"><i>'.$array["subject"].'</i></a> sent by <i>'.class_user($user["username"]).'</i></h2>
                    <form method="post" name="message" action="staffbox.php?action=takeanswer">
                        <table class="message" cellspacing="0" cellpadding="5">
                            <tr>
                                <td colspan="2">
                                    <span style="color:red; font-weight: bold;">Message:</span>
                                    <textarea name="msg" cols="50" rows="5">'.htmlspecialchars($body).'</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center"><input type="submit" value="Submit" class="btn"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="receiver" value="'.$receiver.'">
                        <input type="hidden" name="answeringto" value="'.$answeringto.'">
                    </form>
                </div>
            </td>
        </tr>
    </table>';
    end_frame();
    stdfoot();
}
//////////////////////////
// TAKE ANSWER //
//////////////////////////
if ($action == "takeanswer") {
    if ($_SERVER["REQUEST_METHOD"] != "POST")
        show_error_msg("Error", "Method", 1);
    if ($CURUSER["class"] < $mod_class)
        show_error_msg("Error", "Permission denied.", 1);
    $receiver = (int) $_POST["receiver"];
    $answeringto = $_POST["answeringto"];
    if (!is_valid_id($receiver))
        show_error_msg("Error", "Invalid ID",1);
    $userid = $CURUSER["id"];
    $msg = trim($_POST["msg"]);
    $message = sqlesc($msg);
    $added = "'" . get_date_time() . "'";
    if (!$msg)
        show_error_msg("Error", "Please enter something!",1);
    SQL_Query_exec("INSERT INTO messages (poster, sender, receiver, added, msg) VALUES($userid, $userid, $receiver, $added, $message)");
    SQL_Query_exec("UPDATE staffmessages SET answer=$message WHERE id=$answeringto");
    SQL_Query_exec("UPDATE staffmessages SET answered='1', answeredby='$userid' WHERE id=$answeringto");
    $smsg = "Votre message au staff $answeringto a été répondu.";
    autolink('staffbox.php?action=viewpm&pmid='.$answeringto.'', $smsg);
    die;
}
//////////////////////////
// DELETE STAFF MESSAGE //
//////////////////////////
if ($action == "deletestaffmessage") {
    if ($CURUSER["class"] < $del_class)
        show_error_msg("Error", "Permission denied.", 1);

    $id = (int) $_GET["id"];
    if (!is_numeric($id) || $id < 1 || floor($id) != $id)
        die;
    SQL_Query_exec("DELETE FROM staffmessages WHERE id=" . sqlesc($id));
    $smsg = "Votre message au staff $id a été supprimée.";
    autolink($site_config["SITEURL"] . "/staffbox.php", $smsg);
    die;
}
//////////////////////////
// MARK AS ANSWERED //
//////////////////////////
if ($action == "setanswered") {
    if ($CURUSER["class"] < $del_class)
        show_error_msg("Error", "Permission denied.", 1);
    $id = (int) $_GET["id"];
    SQL_Query_exec("UPDATE staffmessages SET answered=1, answeredby = $CURUSER[id] WHERE id = $id");
    $smsg = "Votre message au staff $id a été défini comme  répondu.";
    autolink($site_config["SITEURL"] ."/staffbox.php?action=viewpm&pmid=$id", $smsg);
    die;
}
//////////////////////////
// MARK AS ANSWERED #2 //
//////////////////////////
if ($action == "takecontactanswered") {
    if (!$CURUSER || $CURUSER["control_panel"] != "yes" || $CURUSER["class"] < $del_class) {
        show_error_msg("Error", "Permission denied.", 1);
    }
    $res = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id FROM staffmessages WHERE answered=0 AND id IN (" . implode(", ", $_POST[setanswered]) . ")");
    while ($arr = mysqli_fetch_assoc($res))
        SQL_Query_exec("UPDATE staffmessages SET answered=1, answeredby = $CURUSER[id] WHERE id = $arr[id]");
    $smsg = "Les messages au staff ont été marqués comme répondu.";
    autolink($site_config["SITEURL"] . "/staffbox.php", $smsg);
    die;
}
?>
<?php
end_frame();
?> 