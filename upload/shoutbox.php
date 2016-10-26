<?php

require_once("backend/functions.php");

dbconn();

global $CURUSER;

# if no id of the last known message id is set to 0
if (!$lastID) { $lastID = 0; }

# call to retrieve all messages with an id greater than $lastID
getData($lastID);

# function that do retrieve all messages with an id greater than $lastID
function getData($lastID) {
    global $CURUSER;
    if ($CURUSER){
        //check for new pm's
        $res = SQL_Query_exec("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " and unread='yes' AND location IN ('in','both')") or print(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
        $arr = mysqli_fetch_row($res);
        $unreadmail = $arr[0];
        if ($unreadmail)
            print("<font color=#FF0000 ><B><center>Vous avez reçu [<a href=mailbox.php?inbox class=link2 target=_blank><font color=#FF0000> $unreadmail </a>]  nouveaux messages ! Consultez les</center></b></a></font>&nbsp;&nbsp;");
    }

    $results = SQL_Query_exec("SELECT c.* FROM shoutbox c left join users u on c.uid=u.id left join groups g on g.group_id=u.class WHERE c.id > $lastID ORDER BY c.id DESC LIMIT 15");
    //$conn = getDBConnection(); # establishes the connection to the database

    # getting the data array
    while ($row = mysqli_fetch_array($results)) {

        # getting the data array
        $id   = $row[id];
        $uid  = $row[uid];
        $time = date(' G:i ', 3600 + utc_to_tz_time($row['date']));
        //$name = $row[name];
        $text = $row[text];

        if ( ($CURUSER["edit_users"]=="yes") || ($CURUSER['id'] == $row['uid']) ){
            $mid=$row['id'];
            $edit="<button onclick=\"editup($mid,$CURUSER[id])\" style='font-size: 10px' class=\"submit\"><i class=\"fa fa-pencil\" aria-hidden=\"true\" title=\"edit\"></i></button><button onclick=\"delup($mid)\" style='font-size: 10px' class=\"submit\"><i class=\"fa fa-times\" aria-hidden=\"true\" title=\"Sup\"></i></button>";
        }
        $UClass=@mysqli_fetch_array(@SQL_Query_exec("SELECT level,  username, gender, avatar,uploaded, downloaded, privacy, country, added, age, class, donated, warned FROM users JOIN groups ON users.class=groups.group_id WHERE users.id=".$row[uid].""));

        $don = $UClass["donated"] > 0 ? "<img src=".$site_config['SITEURL']."/images/users/money.png alt='' width='15' height='15' >" : "";

        $warn = $UClass["warned"] == "yes" ? "<img src=".$site_config['SITEURL']."/images/users/warn.gif alt=''>" : "";

        $av=$UClass['avatar'];
        if(!empty($av)){
            $av="<img src='".$UClass[avatar]."' alt='my_avatar' width='50 height='50'>";
        }
        else{
            $av="<img src='images/default_avatar.png' alt='my_avatar' width='50' height='50'>";
        }

        if (!$UClass[avatar])
            $avatar = '<img border=0 width=100 src=images/default_avatar.png>';
        else $avatar = "<img border=0 width=100 src=$UClass[avatar]>";


        if ($UClass['added'] == '0000-00-00 00:00:00')
            $UClass['added'] = '---';
        $added = date("d-M-Y", utc_to_tz_time($UClass['added']));

        if ($UClass['privacy'] == '')
            $UClass['privacy'] = '---';
        $privacylevel = ($UClass["privacy"]);

        if($UClass["downloaded"] != 0){
            $ratio = number_format($UClass["uploaded"] / $UClass["downloaded"], 2);
        } else {
            $ratio = "---";
        }
        $ratio = "<font color=white>$ratio</font>";
        switch($UClass["class"]){
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
        @((mysqli_free_result($UClass) || (is_object($UClass) && (get_class($UClass) == "mysqli_result"))) ? true : false);


        $name = "".$row['name']."";
        $mail = $CURUSER['username'] == $row['name'] ? "" : '<button onclick="window.location.href=\'mailbox.php?compose&id='.$row['uid'].'\'" style=\'font-size: 10px\' target="_parent" class="submit"><i class="fa fa-envelope" aria-hidden="true" title="Envoyer un MP"></i></button>';
        #putting the chat together.
        $chatout = "<tr id=\"shout_".$id."\" class=\"rowMemberShout\">
            <td class=\"sMemberDetails\" style=\"min-width:38px;\">
                ".$time."
            </td>
        	<td class=\"sMemberDetails\" style=\"min-width:90px;\" align=\"right\">
                <span id=\"member_info\" memberid=\"".$uid."\" class=\"clickable\"><span style=\"color: ".$color.";\">".$name."</span></span>&nbsp;".$warn."&nbsp;
            </td>
            <td class=\"sMemberShout\" style=\"width: 95%;\">
		        <div id=\"sMessageRow\">
			        <div id=\"smessage\">&nbsp;&nbsp;".format_comment($text)."</div>
		        </div>
	        </td>";
        if ( ($CURUSER["edit_users"]=="yes") || ($CURUSER['id'] == $row['uid']) ) {
            $chatout .= "<td class=\"sMemberShout\" style=\"min-width:85px; float: right;\">
                    <div class=\"sButtons\">" . $edit . "$mail&nbsp;</div>
	            </td>";
        }
        $chatout .= "</tr>";

        echo $chatout; # echo as known handles arrays very fast...
    }
}

function execcommand_message ($message = '<div style="background: #000000; border: 1px solid #EA5F00; padding-left: 5px; color:orangered;">Votre commande a été exécutée. (Les résultats peuvent apparaître dans le prochain refresh!)</div>', $forcemessage = false){
    if ((mysqli_affected_rows($GLOBALS["___mysqli_ston"]) OR $forcemessage)){
        echo $message;
    }
}

function execcommand_clean ($Data){
    $Data = trim ($Data[0][1]);
    if (empty ($Data)){

        (@SQL_Query_exec ("TRUNCATE ajshoutbox") OR sqlerr (__FILE__, 284));
        execcommand_message ();
    }else{

        $query = @SQL_Query_exec ("SELECT id FROM users WHERE username = " . sqlesc ($Data));
        if (0 < mysqli_num_rows($query)){
            $Userid = mysqli_result ($query, 0, 'id');

            (@SQL_Query_exec ("delete from ajshoutbox where uid = " . sqlesc ($Userid)) OR sqlerr (__FILE__, 293));
            execcommand_message ();
        }
    }

    return true;
}
function execcommand_noclean ($Data){
    (@SQL_Query_exec ("delete from ajshoutbox WHERE text='/clean'") OR sqlerr (__FILE__, 284));
}

?>
