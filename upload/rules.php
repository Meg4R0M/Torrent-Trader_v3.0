<?php
  //
  //  TorrentTrader v2.x
  //      $LastChangedDate: 2011-10-27 20:00:39 +0100 (Thu, 27 Oct 2011) $
  //      $LastChangedBy: dj-howarth1 $
  //
  //      http://www.torrenttrader.org
  //
  
  require_once("backend/functions.php");
  dbconn();
  
  stdhead( T_("SITE_RULES") );
  
  $res = SQL_Query_exec("SELECT * FROM `rules` ORDER BY `id`");
  while ($row = mysql_fetch_assoc($res))
  {
      if ($row["public"] == "yes")
      {
          begin_frame($row["title"]);
          echo format_comment($row["text"]); 
          end_frame();
      }
      else if ($row["public"] == "no" && $row["class"] <= $CURUSER["class"])
      {
          begin_frame($row["title"]);
          echo format_comment($row["text"]);
          end_frame();
      }
  }
  
  stdfoot();

?>