<?php

$path=dirname(__FILE__);
# emulate register_globals on
if (!ini_get('register_globals')) {
        extract($_POST, EXTR_SKIP);
}
$name = $n; # name from the form
$text = $c; # comment from the form
$uid = (int)$u;  # userid from the form

# some weird conversion of the data inputed
$name = str_replace("\'","'",$name);
$name = str_replace("'","\'",$name);
$text = str_replace("\'","'",$text);
$text = str_replace("'","\'",$text);
$text = str_replace("---"," - - ",$text);

$name = str_replace("---"," - - ",$name);

# the message is cut of after 500 letters
if (strlen($text) > 500) { $text = substr($text,0,500); }

# to allow for linebreaks a space is inserted every 50 letters
//$text = preg_replace("/([^\s]{50})/","$1 ",$text);


/*
# the name is shortened to 30 letters
if (strlen($name) > 30) {
    $name = substr($name, 0,30); 
}
*/

require_once($path."/backend/ping.php");

# only if a name and a message have been provided the information is added to the db
if ($name != '' && $text != '' && $uid !='') {
    addData($name,$text,$uid); # adds new data to the database
    getID(50); # some database maintenance
}

# adds new data to the database
function addData($name,$text,$uid) {
  require_once("backend/functions.php");   # getting table prefix
  $now = get_date_time();
    $sql = "INSERT INTO shoutbox (date,name,text,uid) VALUES ('".$now."','".$name."','".$text."','".$uid."')";
    $conn = getDBConnection();
    $results = SQL_Query_exec($sql, $conn);
    if (!$results || empty($results)) {
        # echo 'There was an error creating the entry';
        end;
    }
}

# returns the id of a message at a certain position
function getID($position) {
  
    $sql =  "SELECT * FROM shoutbox ORDER BY id DESC LIMIT ".$position.",1";
    $conn = getDBConnection(); 
    $results = SQL_Query_exec($sql, $conn);
    if (!$results || empty($results)) {
        # echo 'There was an error creating the entry';
        end;
    }
    while ($row = mysqli_fetch_array($results)) {
        $id = $row[0]; # the result is converted from the db setup (see conn.php)
    }
    if ($id) {
        deleteEntries($id); # deletes all message prior to a certain id
    }
}

# deletes all message prior to a certain id
function deleteEntries($id) {
  
  
    $sql =  "DELETE FROM shoutbox WHERE id < ".$id;
    $conn = getDBConnection();
    $results = SQL_Query_exec($sql, $conn);
    if (!$results || empty($results)) {
        # echo 'There was an error deletig the entries';
        end;
    }
}
exit; # exits the script
?>