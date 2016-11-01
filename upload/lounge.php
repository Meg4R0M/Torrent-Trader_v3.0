<?php
//
//  TorrentTrader v3.x
//      $LastChangedDate: 2016-10-05 15:30:07 +0000 (Web, 05 Oct 2016) $
//      $LastChangedBy: Meg4R0M $
//

require_once("backend/functions.php");
dbconn(true);

stdhead(T_("HOME"));

if ( $site_config['SHOUTBOX'] && ! ( $CURUSER['hideshoutbox'] == 'yes' ) ){
	echo '<div class="tableHeader">
		<div class="row">
			<div class="cell first">
				<div id="shoutboxUpdating">Updating...</div>Shoutbox 
			</div>
		</div>
	</div>';

	if($CURUSER["shoutboxbanned"]=="yes"){
		echo '<div class="widget">';
		echo "<font size='2'><center><b><font color=#FF0000>Your shoutbox rights have been takin away. If you fill like it was an error then please PM staff</font> <br><br></b></center><br />";
		echo '</div>';
	}else{

		require_once("backend/smilies.php");
		require_once("shoutfun_new.php");

		function smile()
		{
			print "<div align='center'>
			    <table cellpadding='1' cellspacing='1' align='center'>
				    <tr>";

			global $smilies, $count;
			reset($smilies);
			while ((list($code, $url) = each($smilies)) && $count < 13) {
				print("\n<td><button onclick=\"SmileIT('" . str_replace("'", "\'", $code) . "')\" class=\"submit\"><img border=\"0\" src=\"images/smilies/$url\" alt=\"$code\" /></button></td>");
				$count++;
			}
			print '<td>&nbsp<button onclick="show_hide(\'sextra\')" class="submit"><i class="fa fa-arrow-down" aria-hidden="true" title="More !"></i></button></td>
				    </tr>
			    </table>
		    </div>';
		}

		function smileextra()
		{
			global $smilies;
			reset($smilies);
			print "<div align='center'>
			    <table cellpadding='1' cellspacing='1' align='center'>
				    <tr>";
			# getting smilies
			while (list($code, $url) = each($smilies)) {
				print("\n<td><button onclick=\"SmileIT('" . str_replace("'", "\'", $code) . "')\" class=\"submit\"><img border=\"0\" src=\"images/smilies/$url\" alt=\"$code\" /></button></td>");
				$count++;
			}
			print '</tr>
			    </table>
		    </div>';
		}

		?>
		<div class="widget">
			<div id="shoutbox_list">
				<div class="shoutbox">
					<table cellpadding="0" cellspacing="0" border="0" class="shoutbox" id="outputList">
						<div class="loader"></div>
					</table>
				</div>
			</div>

			<div class="shoutboxButtons" id="shoutheader">
				<form id="chatForm" name="chatForm" onsubmit="return false;" action="">
					<input type="hidden" name="name" id="name" value="<?php echo $CURUSER["username"] ?>"/>
					<input type="hidden" name="uid" id="uid" value="<?php echo $CURUSER["id"] ?>"/>
					<input type="text" maxlength="800" name="chatbarText" class="s" id="chatbarText"
						   onblur="checkStatus('');" onfocus="checkStatus('active');"/>
					<button onclick="sendComment();" type="submit" id="submit" name="submit" class="submit">SHOUT !
					</button>
					<button onclick="PopMoreSmiles('chatForm','chatbarText')" class="submit"><i class="fa fa-smile-o"
																								aria-hidden="true"
																								title="smilies"></i>
					</button><?php

					if (get_user_class() >= 5) {
						echo '<button onclick="purge(0)" class="submit"><i class="fa fa-trash" aria-hidden="true" title="Empty Shoutbox"></i></button>';
					}

					?>
					<button onclick="Pophistory()" class="submit"><i class="fa fa-history" aria-hidden="true"
																	 title="History"></i></button>
					<br/>
					<div align=center><?php echo smile(); ?></div>
					<div style="display: none;"
						 id="sextra"><?php echo shoutfun('chatForm', 'chatbarText', $dossier); ?></div>
					<div style="display: none;" id="sextra1"><br/><?php echo smileextra(); ?></div>
				</form>
			</div>
		</div>

		<script language="Javascript">
			function show_hide(sextra) {
				if (document.getElementById(sextra)) {
					if (document.getElementById(sextra).style.display == 'none') {
						document.getElementById(sextra).style.display = 'inline';
					} else {
						document.getElementById(sextra).style.display = 'none';
					}
				}
			}
		</script><?php
	}
}

stdfoot();
?>
