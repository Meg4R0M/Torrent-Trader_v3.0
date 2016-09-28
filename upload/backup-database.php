<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2012-06-13 22:58:42 +0100 (Wed, 13 Jun 2012) $
//      $LastChangedBy: torrenttrader $
//
//      http://www.torrenttrader.org
//
//

$backupdir = getcwd() . '/backups'; //Ensure this folder exists and is chmod 777

require_once("backend/mysql.php");

 $today = getdate();
 $day = $today["mday"];
 if ($day < 10) {
    $day = "0$day";
 }
 $month = $today["mon"];
 if ($month < 10) {
    $month = "0$month";
 }
 $year = $today["year"];
 $hour = $today["hours"];
 $min = $today["minutes"];
 $sec = "00";

 // Execute mysqldump command.
 // It will produce a file named $db-$year$month$day-$hour$min.sql.gz
 // under $backupdir
 system(sprintf(
 //'mysqldump --opt -h %s -u %s -p%s %s > %s/%s/%s-%s-%s-%s.sql',    
 'mysqldump --opt -h %s -u %s -p%s %s | gzip > %s/%s-%s-%s-%s.sql.gz',                                    
  
  $mysql_host,
  $mysql_user,
  $mysql_pass,
  $mysql_db,
  $backupdir,
  $mysql_db,
  $day,
  $month,
  $year
 )); 

?>