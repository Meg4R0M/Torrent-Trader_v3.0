<?php
// Mega Scrape
// Version 2.x
//
// http://www.TorrentTrader.org
//
// Authors: FLASH & TorrentialStorm
// Last Updated: 11/march/2014 @ 14:02 by serph

require_once("backend/functions.php");
require_once("backend/BDecode.php");
require_once("backend/parse.php");
require_once"backend/udptscraper.php";
dbconn(false);

set_time_limit(5);

$interval = 172800; // Rescrape torrents every x seconds. (Default: 2 days)

$ts = gmtime() - $interval;
$res = mysql_query("SELECT id, info_hash FROM torrents WHERE external = 'yes'  AND torrents.seeders = '0'  ORDER BY torrents.id DESC LIMIT 10");
$count = get_row_count("torrents", "WHERE external = 'yes' AND last_action <= FROM_UNIXTIME($ts)");

while ($row = mysql_fetch_array($res)) {
echo "<B>ID: $row[id]</B><BR>";
//echo "<B>NAME: $row[name]</B><BR>";
    $TorrentInfo = ParseTorrent("$site_config[torrent_dir]/$row[id].torrent");
    $ann = $TorrentInfo[0];
    $annlist = array();
    if ($TorrentInfo[6]) {
        foreach ($TorrentInfo[6] as $ann) {
            $annlist[] = $ann[0];
        }
    } else
        $annlist = array($ann);
        
    $seeders = $leechers = $downloaded = null;
    //echo "Info_Hash: $row[info_hash]<BR>";
    foreach ($annlist as $ann) {
        $tracker = explode("/", $ann);
        $path = array_pop($tracker);
        $oldpath = $path;
        $path = str_replace("announce", "scrape", $path);
        $tracker = implode("/", $tracker)."/".$path;
        if ($oldpath == $path) {
            echo "<BR><B>$ann</B>: Scrape not supported.<BR>";
            continue;
        }

        // TPB's tracker is dead. Use openbittorrent instead
        if ($openbittorrent_done)
            continue;
        if (preg_match("/thepiratebay.org/i", $tracker) || preg_match("/prq.to/", $tracker)) {
            $tracker = "http://tracker.openbittorrent.com/scrape";
            $openbittorrent_done = 1;
        }

        
		
		 if(preg_match('%udp://([^:/]*)(?::([0-9]*))?(?:/)?%si', $tracker))
		  {
		  $udp=true;
		  
		  try{
			$timeout = 5;
			$udp= new udptscraper($timeout);
			$stats=$udp->scrape($tracker,$row["info_hash"]);
			foreach ($stats as $id=>$scrape){
			$seeders += $scrape['seeders'];
			$leechers += $scrape['leechers'];
			$downloaded	+= $scrape['completed'];
			}
			}catch(ScraperException $e){
			echo('Error: ' . $e->getMessage() . "\n");
			//echo('Connection error: ' . ($e->isConnectionError() ? 'yes' : 'no') . "\n");
		}
			}
			else{
        $stats = torrent_scrape_url($tracker, $row["info_hash"]);
		}
		
        if ($stats['seeds'] != -1) {
            $seeders += $stats['seeds'];
            $leechers += $stats['peers'];
            $downloaded += $stats['downloaded'];
            echo "<BR><B>$ann</B><BR>";
            echo "Seeders: ".($udp?$scrape['seeders']:$stats["seeds"])."<BR>";
            echo "Leechers: ".($udp?$scrape['leechers']:$stats["peers"])."<BR>";
            echo "Downloaded: ".($udp?$scrape['completed']:$stats["downloaded"])."<BR>";
        } else
            echo "<BR><B>$ann</B>: Tracker timeout.<BR>";
    }

    if ($seeders !== null){
        //echo "<BR><B>Totals:</B><BR>";
       // echo "Seeders: $seeders<BR>";
       // echo"Leechers: $leechers<BR>";
       // echo "Completed: $downloaded<BR><BR>";

        mysql_query("UPDATE torrents SET leechers='".$leechers."', seeders='".$seeders."',times_completed='".$downloaded."',last_action= '".get_date_time()."',visible='yes' WHERE id='".$row['id']."'");
    }else{
        echo "All trackers timed out.<BR>";
        mysql_query("UPDATE torrents SET last_action= '".get_date_time()."' WHERE id='".$row['id']."'");
    }
}
?>





<script language="JavaScript">

//Refresh page script- By Brett Taylor (glutnix@yahoo.com.au)
//Modified by Dynamic Drive for NS4, NS6+
//Visit http://www.dynamicdrive.com for this script

//configure refresh interval (in seconds)
<?php if ($count)
echo "var countDownInterval=3;";
else
echo "var countDownInterval=900;"; // No torrents to scrape, refresh in 15mins
?>
//configure width of displayed text, in px (applicable only in NS4)
var c_reloadwidth=200

</script>


<ilayer id="c_reload" width=&{c_reloadwidth}; ><layer id="c_reload2" width=&{c_reloadwidth}; left=0 top=0></layer></ilayer>

<script>

var countDownTime=countDownInterval+1;
function countDown(){
countDownTime--;
if (countDownTime <=0){
countDownTime=countDownInterval;
clearTimeout(counter)
window.location.reload()
return
}
if (document.all) //if IE 4+
document.all.countDownText.innerText = countDownTime+" ";
else if (document.getElementById) //else if NS6+
document.getElementById("countDownText").innerHTML=countDownTime+" "
else if (document.layers){ //CHANGE TEXT BELOW TO YOUR OWN
document.c_reload.document.c_reload2.document.write('Next <a href="java script:window.location.reload()">refresh</a> in <b id="countDownText">'+countDownTime+' </b> seconds')
document.c_reload.document.c_reload2.document.close()
}
counter=setTimeout("countDown()", 1000);
}

function startit(){
if (document.all||document.getElementById) //CHANGE TEXT BELOW TO YOUR OWN
document.write('Next <a href="javascript:window.location.reload()">refresh</a> in <b id="countDownText">'+countDownTime+' </b> seconds')
countDown()
}

if (document.all||document.getElementById)
startit()
else
window.onload=startit

</script>