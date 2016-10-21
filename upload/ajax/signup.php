<?php
/*************************************************************************
*  Version: Unofficial v3.0 @ 19.10.2016								 *
*  Project Leaders: Meg4R0M 											 *
*  Website: http://support-tt-france.fr/								 *
*  Github: https://github.com/psykoterro/Torrent-Trader_v3.0             *
**************************************************************************/

require_once("../backend/functions.php");
dbconn();

$username_length = 15; // Max username length. You shouldn't set this higher without editing the database first
$password_minlength = 6;
$password_maxlength = 40;

$message == "";

function validusername($username) {
    $allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	for ($i = 0; $i < strlen($username); ++$i)
	   if (strpos($allowedchars, $username[$i]) === false)
           return false;
    return true;
}

// Disable checks if we're signing up with an invite
if (!is_valid_id($_POST["invite_hash"]) || strlen($_POST["a_hash"]) != 32){

    //invite only check
    if ($site_config["INVITEONLY"]) {
        ajax_show_error_msg(T_("INVITE_ONLY"), T_("INVITE_ONLY_MSG"),1);
    }

    //get max members, and check how many users there is
    $numsitemembers = get_row_count("users");
    if ($numsitemembers >= $site_config["maxusers"])
        ajax_show_error_msg(T_("SORRY")."...", T_("SITE_FULL_LIMIT_MSG") . number_format($site_config["maxusers"])." ".T_("SITE_FULL_LIMIT_REACHED_MSG")." ".number_format($numsitemembers)." members",1);
}else{
    $res = SQL_Query_exec("SELECT id FROM users WHERE id = $_REQUEST[invite] AND MD5(secret) = ".sqlesc($_REQUEST["secret"]));
    $invite_row = mysqli_fetch_assoc($res);
    if (!$invite_row) {
        ajax_show_error_msg(T_("ERROR"), T_("INVITE_ONLY_NOT_FOUND")." ".($site_config['signup_timeout']/86400)." days.", 1);
    }
}

$wantusername = $_POST["signupbox_membername"];
$email = $_POST["signupbox_email"];
$emailagain = $_POST["signupbox_email2"];
$wantpassword = $_POST["signupbox_password"];
$passagain = $_POST["signupbox_password2"];
$timezone = $_POST["signupbox_timezone"];
$country = $_POST["signupbox_country"];
$gender = $_POST["signupbox_gender"];
$client = $_POST["signupbox_prefbitclient"];
$age = $_POST["signupbox_date_of_birth"];

if (empty($wantpassword) || (empty($email) && !$invite_row) || empty($wantusername))
    $message = T_("DONT_LEAVE_ANY_FIELD_BLANK");
elseif (strlen($wantusername) > $username_length)
    $message = sprintf(T_("USERNAME_TOO_LONG"), $username_length);
elseif ($wantpassword != $passagain)
    $message = T_("PASSWORDS_NOT_MATCH");
elseif (strlen($wantpassword) < $password_minlength)
    $message = "Your password is too short ! (min: ".$password_minlength." chars.)";
elseif (strlen($wantpassword) > $password_maxlength)
    $message = "You password is too long ! (max: ".$password_maxlength." chars.)";
elseif ($wantpassword == $wantusername)
    $message = T_("PASS_CANT_MATCH_USERNAME");
elseif (!validusername($wantusername))
    $message = "Invalid username.";
elseif ($email != $emailagain)
    $message = "Your email address are not identical.";
elseif (!$invite_row && !validemail($email))
    $message = "That doesn't look like a valid email address.";

if (preg_match('#^([0-9]{2})([/-])([0-9]{2})\2([0-9]{4})$#', $age, $m) == 1 && checkdate($m[3], $m[1], $m[4])) {
    $age = strtotime($age);
    $age = date('Y-m-d', $age);
}else
    $message = "Invalid date of birth";


if ($message == "") { 
    // Certain checks must be skipped for invites 
    if (!$invite_row) { 
        //check email isnt banned 
        $maildomain = (substr($email, strpos($email, "@") + 1)); 
        $a = (@mysqli_fetch_row(@SQL_Query_exec("SELECT count(*) FROM email_bans WHERE mail_domain='$email'"))); 
        if ($a[0] != 0) 
            $message = sprintf(T_("EMAIL_ADDRESS_BANNED_S"), $email); 

        $a = (@mysqli_fetch_row(@SQL_Query_exec("SELECT count(*) FROM email_bans WHERE mail_domain LIKE '%$maildomain%'"))); 
        if ($a[0] != 0) 
            $message = sprintf(T_("EMAIL_ADDRESS_BANNED_S"), $email); 

        // check if email addy is already in use 
        $a = (@mysqli_fetch_row(@SQL_Query_exec("SELECT count(*) FROM users WHERE email='$email'"))); 
        if ($a[0] != 0) 
            $message = sprintf(T_("EMAIL_ADDRESS_INUSE_S"), $email); 
    }

    //check username isnt in use 
    $a = (@mysqli_fetch_row(@SQL_Query_exec("SELECT count(*) FROM users WHERE username='$wantusername'"))); 
    if ($a[0] != 0) 
        $message = sprintf(T_("USERNAME_INUSE_S"), $wantusername);  

    $secret = mksecret(); //generate secret field 
    $wantpassword = passhash($wantpassword);// hash the password 
}

if ($message != "") 
    ajax_show_error_msg(T_("SIGNUP_FAILED"), $message, 1);

if ($message == "") {
    if ($invite_row) {
        SQL_Query_exec("UPDATE users SET username=".sqlesc($wantusername).", password=".sqlesc($wantpassword).", secret=".sqlesc($secret).", status='confirmed', added='".get_date_time()."' WHERE id=$invite_row[id]");
        //send pm to new user
        if ($site_config["WELCOMEPMON"]){
            $dt = sqlesc(get_date_time());
            $msg = sqlesc($site_config["WELCOMEPMMSG"]);
            SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $invite_row[id], $dt, $msg, 0)");
        }
        if (isset($CURUSER)) {
            ajax_show_error_msg(T_("ACCOUNT_SUCCESS_CONFIRMED"), T_("ACCOUNT_ACTIVATED"). " <a href='". $site_config["SITEURL"] ."../'>" .T_("ACCOUNT_ACTIVATED_REST"). "<br />".T_("ACCOUNT_BEFOR_USING"). " " . $site_config["SITENAME"] . " " .T_("ACCOUNT_BEFOR_USING_REST"), 1);
        }
        else {
            ajax_show_error_msg(T_("ACCOUNT_SUCCESS_CONFIRMED"), T_("ACCOUNT_ACTIVATED"), 1);
        }
        die;
    }

    if ($site_config["CONFIRMEMAIL"]) { //req confirm email true/false
        $status = "pending";
    }else{
        $status = "confirmed";
    }

    //make first member admin
    if ($numsitemembers == '0')
        $signupclass = '7';
    else
        $signupclass = '1';

    SQL_Query_exec("INSERT INTO users (username, password, secret, email, status, added, last_access, age, country, gender, client, stylesheet, language, class, ip) VALUES (" .
        implode(",", array_map("sqlesc", array($wantusername, $wantpassword, $secret, $email, $status, get_date_time(), get_date_time(), $age, $country, $gender, $client, $site_config["default_theme"], $site_config["default_language"], $signupclass, getip()))).")");

    $id = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);

    $psecret = md5($secret);
    $thishost = $_SERVER["HTTP_HOST"];
    $thisdomain = preg_replace('/^www\./is', "", $thishost);

    //ADMIN CONFIRM
    if ($site_config["ACONFIRM"]) {
        $body = T_("YOUR_ACCOUNT_AT")." ".$site_config['SITENAME']." ".T_("HAS_BEEN_CREATED_YOU_WILL_HAVE_TO_WAIT")."\n\n".$site_config['SITENAME']." ".T_("ADMIN");
    }else{//NO ADMIN CONFIRM, BUT EMAIL CONFIRM
        $body = T_("YOUR_ACCOUNT_AT")." ".$site_config['SITENAME']." ".T_("HAS_BEEN_APPROVED_EMAIL")."\n\n    ".$site_config['SITEURL']."/account-confirm.php?id=$id&secret=$psecret\n\n".T_("HAS_BEEN_APPROVED_EMAIL_AFTER")."\n\n    ".T_("HAS_BEEN_APPROVED_EMAIL_DELETED")."\n\n".$site_config['SITENAME']." ".T_("ADMIN");
    }

    if ($site_config["CONFIRMEMAIL"]){ //email confirmation is on
        sendmail($email, "Your $site_config[SITENAME] User Account", $body, "", "-f$site_config[SITEEMAIL]");
        if (!$site_config["ACONFIRM"]){
            ajax_show_infos_msg(T_("A_CONFIRMATION_EMAIL_HAS_BEEN_SENT"), T_("A_CONFIRMATION_EMAIL_HAS_BEEN_SENT"). " (" . htmlspecialchars($email) . "). " .T_("ACCOUNT_CONFIRM_SENT_TO_ADDY_REST"),1);
        }else{
            ajax_show_infos_msg(T_("EMAIL_CHANGE_SEND"), T_("EMAIL_CHANGE_SEND"). " (" . htmlspecialchars($email) . "). " .T_("ACCOUNT_CONFIRM_SENT_TO_ADDY_ADMIN"),1);
        }
        
    }else{ //email confirmation is off
        ajax_show_infos_msg(T_("PLEASE_NOW_LOGIN"), T_("PLEASE_NOW_LOGIN_REST"),1);
    }
    //send pm to new user
    if ($site_config["WELCOMEPMON"]){
        $dt = sqlesc(get_date_time());
        $msg = sqlesc($site_config["WELCOMEPMMSG"]);
        SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $id, $dt, $msg, 0)");
    }

    die;
}
?>
