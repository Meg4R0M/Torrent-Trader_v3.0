<?php

/**
 *
 * @package TorrentTrader
 *
 * @version v2.08
 *
 * @author  Lee Howarth
 *
 */

require_once('backend/functions.php');

dbconn();

/* Not Fool Proof... */

if (!isset($_GET['action'], $_SERVER['HTTP_TT'])) {

    autolink('index.php', 'The page you requested was not found.');

}

/* Require Login... */

if ($_GET['action'] != 'select' AND !$CURUSER) {

    return;

}

if (!empty($_GET['sid']) And $_GET['action'] == 'delete') {

    /* Delete Shout... */

    $res = SQL_Query_exec("SELECT `userid` FROM `shoutbox` WHERE `msgid` = " . sqlesc($_GET['sid']));

    if (!($row = mysql_fetch_object($res)) Or ($CURUSER['edit_users'] == 'no' And $CURUSER['id'] != $row->userid)) {

        return;

    }

    SQL_Query_exec("DELETE FROM `shoutbox` WHERE `msgid` = " . sqlesc($_GET['sid']));

}

if (!empty($_POST['shout']) And $_GET['action'] == 'update') {

    /* Prevent Flooding... */

    $res = SQL_Query_exec("SELECT `msgid` FROM `shoutbox` WHERE `userid` = " . $CURUSER['id'] . " AND UNIX_TIMESTAMP('" . get_date_time() . "') - UNIX_TIMESTAMP(`date`) < 60");

    if (mysql_num_rows($res) > 5) {

        return;

    }

    SQL_Query_exec("INSERT INTO `shoutbox` (`user`, `message`, `date`, `userid`) VALUES ('" . $CURUSER['username'] . "', " . sqlesc($_POST['shout']) . ", '" . get_date_time() . "', " . $CURUSER['id'] . ")");

}

if ($_GET['action'] == 'select' Or $_GET['action'] == 'history') {

    /* Fetch Previous Shouts... */

    $res = SQL_Query_exec('SELECT `shoutbox`.`msgid`, `shoutbox`.`userid`, `shoutbox`.`user`, `shoutbox`.`message`, `shoutbox`.`date`, users.avatar FROM shoutbox LEFT JOIN users ON shoutbox.user = users.username ORDER BY `msgid` DESC ' . (($_GET['action'] == 'history') ? null : 'LIMIT 20'));

    $i = 0;

    $data = array();

    while ($row = mysql_fetch_object($res)) {



        if ($row->avatar =='')

            $avatar = $site_config["SITEURL"] . "/images/default_avatar.gif";

        else

            $avatar = htmlspecialchars($row->avatar);

        if (!$avatar)

            $avatar = $site_config["SITEURL"] . "/images/default_avatar.gif";

        

        $data[] = ( object ) Array(

            'date' => date('dS M H:i', utc_to_tz_time($row->date)),

            'text' => format_comment($row->message),

            'user' => '<a href="/account-details.php?id=' . $row->userid . '" style="text-decoration: none;" onmouseover="return overlib(\'<img src=' . $avatar . ' width=120 height=120 border=0>\', CENTER)" onmouseout="return nd()">' . class_user($row->user) . ':</a>',

            'alt' => ($i % 2 == 0) ? 'shoutbox_alt' : 'shoutbox_noalt',

            'sid' => $row->msgid,

            'uid' => $row->userid

        );

        $i++;

    }

    mysql_free_result($res);

    mysql_close();

    /* Dynamic Content... */

    header('Cache-Control: no-cache, must-revalidate');

    header('Pragma: no-cache');

    /* Prepare Response... */

    echo '<div class="shout">';

    foreach ($data as $row) {

        echo '<div class="shoutAlt">

                   <span class="shoutDate">', $row->date, '</span>

                   <span class="shoutEdit">', (($CURUSER['edit_users'] == 'yes' Or $row->uid == $CURUSER['id']) ? '<a href="" onclick="deleteMessage(' . $row->sid . '); return false;" title="Delete">[D]</a>' : null), '</span>

                   <span class="shoutUser">', $row->user, '</span>

                   <span class="shoutText">', $row->text, '</span>

                 </div>';

    }

    echo '</div>';

}

?>