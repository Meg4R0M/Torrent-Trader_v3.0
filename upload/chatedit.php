<?php

require_once("backend/functions.php");
require_once("backend/smilies.php");
dbconn(false);

$action = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET["action"]) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

function smile() {
    print "<div align='center'>
        <table cellpadding='1' cellspacing='1'>
            <tr>";
                global $smilies, $count;
                reset($smilies);

                while ((list($code, $url) = each($smilies)) && $count<16) {
                    print("\n<td><a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."')\"><img border=\"0\" src=\"images/smilies/$url\" alt=\"$code\" /></a></td>");
                    $count++;
                }
            print "</tr>
        </table>
    </div>";
}

function redirect($redirecturl) {
    global $language;

    if (headers_sent()) {
        ?><script language="javascript">
            window.location.href='<?php echo $redirecturl; ?>';
        </script>
        <meta http-equiv="refresh" content="2" charset="UTF-8;<?php echo $redirecturl; ?>"><?php
        echo sprintf("Redirection", $redirecturl);
    }else
        header('Location: '.$redirecturl);
    die();
}

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

if (isset($_GET['del'])){

    if (is_numeric($_GET['del'])){
        $query = "SELECT * FROM shoutbox WHERE id=".$_GET['del'] ;
        $result = SQL_Query_exec($query);
    }else{
        echo "invalid msg id STOP TRYING TO INJECT SQL";
        exit;
    }

    $row = mysqli_fetch_row($result);

    if ($CURUSER["id"] != $row["uid"]){
        $query = "DELETE FROM shoutbox WHERE id=".$_GET['del'] ;
        write_log("<b><font color=orange>Supprimer Shout: </font> Supprimer par ".$CURUSER['username']."</b>");
        SQL_Query_exec($query);

        ?><html>
        <head>
            <link rel="stylesheet" type="text/css" href="<?php echo $site_config['SITEURL'];?>/themes/<?php echo $THEME;?>/theme.css" />
        </head><?php
        sleep(2);
        echo"<body onload=\"window.close();\" bgcolor=black>";
        echo"<center><h1>Succés<br>Message Sauvegardé!</center></h1> 
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
    </head><?php
    sleep(2);
    echo "<body onload=\"window.close();\" bgcolor=black>";
    echo"<center>Message Sauvegarder!</center> 
    </body> 
    </html>";
    exit();

}

if (substr($action, 0, 4)=="edit"){
    $msgid = (int)$_GET["msgid"];
    $res = SQL_Query_exec("SELECT * FROM shoutbox WHERE id=".$msgid);
    if (mysqli_num_rows($res) != 1) {
        print("No message with ID $msgid.");
        exit();
    }
    $arr = mysqli_fetch_assoc($res);
    if ($CURUSER["id"] != $arr["uid"] && $CURUSER["edit_users"]=="no") {
        print("Nope !");
        exit();
    }
    $save = (int)$_GET["save"];
    if ($save) {
        $message = $_POST['message'];
        if ($message == "")
            print("");
        $message = sqlesc($message);
        SQL_Query_exec("UPDATE shoutbox SET text=$message WHERE id=".$_GET['msgid']) or sqlerr();
        retour("index.php", "<h1>Success<br>Message edited !</h1>", 2);
    }else{
        exit();
    }
}

    ?><script>
    function SmileIT(smile){
        document.forms['chatForm'].elements['message'].value = document.forms['chatForm'].elements['message'].value+" "+smile+" ";  //this non standard attribute prevents firefox' autofill function to clash with this script
        document.forms['chatForm'].elements['message'].focus();
    }
</script><?php
?>