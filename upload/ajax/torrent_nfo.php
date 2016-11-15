<?php
require_once("../backend/functions.php");
dbconn();
loggedinonly();

$id = $_POST["tid"];
$res = SQL_Query_exec("SELECT nfo FROM torrents WHERE id=".$id."");
$arr = mysqli_fetch_assoc($res);
//DISPLAY NFO BLOCK
if($arr["nfo"]== "yes"){
    $nfofilelocation = $nfo_dir.'/'.$id.'.nfo';
    $filegetcontents = file_get_contents($nfofilelocation);
    if ($filegetcontents) {
        echo '<textarea class="nfo" style="width:98%;height:300px;color: orange;background-color: black;" readonly="readonly">';
        echo iconv('CP850', 'UTF-8', stripslashes($filegetcontents)).PHP_EOL;
        echo '</textarea>';
    }else{
        echo '<div class="error" id="show_error">
            '.T_("ERROR").' reading .nfo file!
        </div>';
    }
}
echo 'coucou';