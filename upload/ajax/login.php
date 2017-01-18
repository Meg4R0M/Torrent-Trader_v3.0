<?php
require_once("../backend/functions.php");
dbconn();
global $site_config;
$atbf_activate = $site_config['atbf_activate'];

if (!empty($_REQUEST["returnto"])) {
	if (!$_GET["nowarn"]) {    
		 $nowarn = T_("MEMBERS_ONLY");
	}
}

if ($_POST["loginbox_membername"] && $_POST["loginbox_password"]) {
	$password = passhash($_POST["loginbox_password"]);

	if (!empty($_POST["loginbox_membername"]) && !empty($_POST["loginbox_password"])) {
		$res = SQL_Query_exec("SELECT id, password, secret, status, enabled, atbf_islocked FROM users WHERE username = " . sqlesc($_POST["loginbox_membername"]) . "");
		$row = mysqli_fetch_assoc($res);

		if($atbf_activate === true){
			if (!$row){
				$message = T_("LOGIN_INCORRECT");
			}elseif ($row["atbf_islocked"] != 0 ){
				$message = verifyatbf(sqlesc($_POST["username"]), $_POST["password"]);
			}elseif ($row["password"] != $password ){
				$message = addatbf(sqlesc($_POST["username"]));
			}elseif ($row["status"] == "pending"){
				$message = T_("ACCOUNT_PENDING");
			}elseif ($row["enabled"] == "no"){
				$message = T_("ACCOUNT_DISABLED");
			}
		}else{
			if (!$row){
				$message = T_("LOGIN_INCORRECT");
			}elseif ($row["password"] != $password ){
				$message = T_("LOGIN_INCORRECT");
			}elseif ($row["status"] == "pending"){
				$message = T_("ACCOUNT_PENDING");
			}elseif ($row["enabled"] == "no"){
				$message = T_("ACCOUNT_DISABLED");
			}
		}
	} else{
		$message = "Don't let empty field !!";
	}

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