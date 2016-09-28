<?php
 //
 //  TorrentTrader v2.x
 //      $LastChangedDate: 2011-11-18 04:28:50 +0000 (Fri, 18 Nov 2011) $
 //      $LastChangedBy: dj-howarth1 $
 //
 //      http://www.torrenttrader.org
 //
 //
 
 # For Security Purposes.
 if ( $_SERVER['PHP_SELF'] != $_SERVER['REQUEST_URI'] ) die; 
 
 require_once("backend/functions.php");
 dbconn();
 
 logoutcookie();
 header("Location: index.php");
?>