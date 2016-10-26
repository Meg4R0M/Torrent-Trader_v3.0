<?php

if (strpos($_SERVER['REQUEST_URI'], '?') !== false){
	$scripturl =  substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
}else{
	$scripturl = $_SERVER['REQUEST_URI'];
}
$pageurl = $_SERVER['REQUEST_URI'];

if ($scripturl == "/membercp.php"){
	begin_block("Member CP Navigation");
		echo '<ul id="memberCPNavi" class="">
			<li><a href="/membercp.php"'; if ($pageurl == "/membercp.php"){echo 'class="active"';} echo '>Personal Details</a></li>
			<li><a href="/membercp.php?action=contact_details"'; if ($pageurl == "/membercp.php?action=contact_details"){echo 'class="active"';} echo '>Contact Details</a></li>
			<li><a href="/membercp.php?action=preferences"'; if ($pageurl == "/membercp.php?action=preferences"){echo 'class="active"';} echo '>Preferences</a></li>
			<li><a href="/membercp.php?action=privacy"'; if ($pageurl == "/membercp.php?action=privacy"){echo 'class="active"';} echo '>Privacy</a></li>
			<li><a href="/membercp.php?action=password"'; if ($pageurl == "/membercp.php?action=password"){echo 'class="active"';} echo '>Password</a></li>
			<li><a href="/membercp.php?action=signature"'; if ($pageurl == "/membercp.php?action=signature"){echo 'class="active"';} echo '>Signature</a></li>
			<li><a href="/membercp.php?action=avatar"'; if ($pageurl == "/membercp.php?action=avatar"){echo 'class="active"';} echo '>Avatar</a></li>
			<li><a href="/membercp.php?action=invite"'; if ($pageurl == "/membercp.php?action=invite"){echo 'class="active"';} echo '>Invite Friends</a></li>
			<li><a href="/membercp.php?action=upgrade"'; if ($pageurl == "/membercp.php?action=upgrade"){echo 'class="active"';} echo '>Upgrade Account</a></li>
			<li><a href="/membercp.php?action=following"'; if ($pageurl == "/membercp.php?action=following"){echo 'class="active"';} echo '>People You Follow</a></li>
			<li><a href="/membercp.php?action=gallery"'; if ($pageurl == "/membercp.php?action=gallery"){echo 'class="active"';} echo '>Image Gallery</a></li>
			<li><a href="/membercp.php?action=performance"'; if ($pageurl == "/membercp.php?action=performance"){echo 'class="active"';} echo '>Performance</a></li>
			<li><a href="/membercp.php?action=subscribed_threads"'; if ($pageurl == "/membercp.php?action=subscribed_threads"){echo 'class="active"';} echo '>Subscribed Threads</a></li>
			<li><a href="/membercp.php?action=open_port_check_tool"'; if ($pageurl == "/membercp.php?action=open_port_check_tool"){echo 'class="active"';} echo '>Open Port Check Tool</a></li>
			<li><a href="/membercp.php?action=alerts"'; if ($pageurl == "/membercp.php?action=alerts"){echo 'class="active"';} echo '>Recent Alerts</a></li>
			<li><a href="/mailbox.php"'; if ($pageurl == "/membercp.php?action=messages"){echo 'class="active"';} echo '>Messages</a></li>
		</ul>';
	end_block();
}
?>
