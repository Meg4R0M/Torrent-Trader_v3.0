<?php
require_once("../backend/functions.php");
dbconn();

if (!empty($_REQUEST["returnto"])) {
	if (!$_GET["nowarn"]) {    
		 $nowarn = T_("MEMBERS_ONLY");
	}
}

if ($_POST["loginbox_membername"] && $_POST["loginbox_password"]) {
	$password = passhash($_POST["loginbox_password"]);

	if (!empty($_POST["loginbox_membername"]) && !empty($_POST["loginbox_password"])) {
		$res = SQL_Query_exec("SELECT id, password, secret, status, enabled FROM users WHERE username = " . sqlesc($_POST["loginbox_membername"]) . "");
		$row = mysqli_fetch_assoc($res);

		if ( ! $row || $row["password"] != $password )
			$message = T_("LOGIN_INCORRECT");
		elseif ($row["status"] == "pending")
			$message = T_("ACCOUNT_PENDING");
		elseif ($row["enabled"] == "no")
			$message = T_("ACCOUNT_DISABLED");
	} else
		$message = "Don't let empty field !!";

	if (!$message){
		logincookie($row["id"], $row["password"], $row["secret"]);
		echo '<div class="done" header="Nice ONE !!">Login successful... Please wait !</div>';
	}else{ 
        echo '<div class="error" header="An error has occured!">'.$message.'</div>';
	}
}else{
    echo '<div class="error" header="An error has occured!">Please enter something...</div>';
}
?>