<?php
//
//  TorrentTrader v2.x
//      $LastChangedDate: 2011-11-17 00:13:07 +0000 (Thu, 17 Nov 2011) $
//      $LastChangedBy: dj-howarth1 $
//
//      http://www.torrenttrader.org
//
//
require_once("backend/functions.php");
dbconn(true);

stdhead(T_("HOME"));





if ( $site_config['SHOUTBOX'] && ! ( $CURUSER['hideshoutbox'] == 'yes' ) )
{
      begin_frame(T_("SHOUTBOX"));
      if($CURUSER["shoutboxbanned"]=="yes"){
      echo "<font size='2'><center><b><font color=#FF0000>Your shoutbox rights have been takin away. If you fill like it was an error then please PM staff</font> <br><br></b></center><br />";
      end_frame();
      }else{

      ?>
      <script src="<?php echo $site_config["SITEURL"]; ?>/js/shoutbox.js"></script>

      <!-- Shout Container -->
      <div id="shouts">
        <img src="<?php echo $site_config["SITEURL"]; ?>/images/ajax-loader.gif" alt="Loading..." title="Loading..." />
      </div>

      <!-- Shout Form -->
      <div id="shoutForm"></div>

      <!-- Refresh Shoutbox -->
      <script>
            var loggedIn = '<?php echo ( ( $CURUSER ) ? 1 : 0 ); ?>';

            refresh();
      </script>

      <!-- Javascript Disabled -->
      <noscript>Please enable javascript to view the shoutbox.</noscript>

      <!-- Shout History -->
      <div id="shoutFade" class="shoutFade"></div>
      <div id="shoutHistory" class="shoutHistory"></div>
      <?php
      }
      end_frame();
}


stdfoot();
?>
