<?php
require_once("../backend/functions.php");
dbconn();

$action = $_POST["action"];

if ($action == "messages_get_reply"){
    echo 'ok';
    
}
?>