<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2012-06-12 18:11:36 +0100 (Tue, 12 Jun 2012) $
//      $LastChangedBy: torrenttrader $
//
//      http://www.torrenttrader.org
//
//
error_reporting(0); //disable error reporting

// check if client can handle gzip
if (stristr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip") && extension_loaded('zlib') && ini_get("zlib.output_compression") == 0) {
    if (ini_get('output_handler')!='ob_gzhandler') {
        ob_start("ob_gzhandler");
    } else {
        ob_start();
    }
}else{
     ob_start();
}
// end gzip controll

require_once("backend/mysql.php");
require_once("backend/mysql.class.php");

function dbconn() {
    global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;

    if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
    {
      die('DATABASE: mysql_connect: ' . mysql_error());
    }
     mysql_select_db($mysql_db)
        or die('DATABASE: mysql_select_db: ' + mysql_error());

    unset($mysql_pass); //security
}

function sqlesc($x) {
    return "'".mysql_real_escape_string($x)."'";
}

dbconn();

$infohash = array();

foreach (explode("&", $_SERVER["QUERY_STRING"]) as $item) {
    if (preg_match("#^info_hash=(.+)\$#", $item, $m)) {
        $hash = urldecode($m[1]);

        if (get_magic_quotes_gpc())
            $info_hash = stripslashes($hash);
        else
            $info_hash = $hash;
        if (strlen($info_hash) == 20)
            $info_hash = bin2hex($info_hash);
        else if (strlen($info_hash) != 40)
            continue;
        $infohash[] = sqlesc(strtolower($info_hash));
    }
}

if (!count($infohash)) die("Invalid infohash.");
    $query = SQL_Query_exec("SELECT info_hash, seeders, leechers, times_completed, filename FROM torrents WHERE info_hash IN (".join(",", $infohash).")");

$result="d5:filesd";

while ($row = mysql_fetch_row($query))
{
    $hash = pack("H*", $row[0]);
    $result.="20:".$hash."d";
    $result.="8:completei".$row[1]."e";
    $result.="10:downloadedi".$row[3]."e";
    $result.="10:incompletei".$row[2]."e";
    $result.="4:name".strlen($row[4]).":".$row[4]."e";
    $result.="e";
}

$result.="ee";

echo $result;
ob_end_flush();
mysql_close();
?>