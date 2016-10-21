<?php 
// 
//  TorrentTrader v2.x 
//    $LastChangedDate: 2012-09-27 22:15:34 +0100 (Thu, 27 Sep 2012) $ 
//      $LastChangedBy: torrenttrader $ 
//     
//    http://www.torrenttrader.org 
// 
// 
require_once("backend/functions.php"); 
dbconn(); 

// Disable checks if we're signing up with an invite 
if (!is_valid_id($_REQUEST["invite"]) || strlen($_REQUEST["secret"]) != 32){ 
     
    //invite only check 
    if ($site_config["INVITEONLY"]) { 
        show_error_msg(T_("INVITE_ONLY"), "<br /><br /><center>".T_("INVITE_ONLY_MSG")."<br /><br /></center>",1); 
    } 

    //get max members, and check how many users there is 
    $numsitemembers = get_row_count("users"); 
    if ($numsitemembers >= $site_config["maxusers"]) 
        show_error_msg(T_("SORRY")."...", T_("SITE_FULL_LIMIT_MSG") . number_format($site_config["maxusers"])." ".T_("SITE_FULL_LIMIT_REACHED_MSG")." ".number_format($numsitemembers)." members",1); 
}else{ 
    $res = SQL_Query_exec("SELECT id FROM users WHERE id = $_REQUEST[invite] AND MD5(secret) = ".sqlesc($_REQUEST["secret"])); 
    $invite_row = mysqli_fetch_assoc($res); 
    if (!$invite_row) { 
       show_error_msg(T_("ERROR"), T_("INVITE_ONLY_NOT_FOUND")." ".($site_config['signup_timeout']/86400)." days.", 1); 
    } 
} 


if ($_GET["takesignup"] == "2") {

  if ($message == "") { 
        if ($invite_row) { 
            SQL_Query_exec("UPDATE users SET username=".sqlesc($wantusername).", password=".sqlesc($wantpassword).", secret=".sqlesc($secret).", status='confirmed', added='".get_date_time()."' WHERE id=$invite_row[id]"); 
            //send pm to new user 
            if ($site_config["WELCOMEPMON"]){ 
                $dt = sqlesc(get_date_time()); 
                $msg = sqlesc($site_config["WELCOMEPMMSG"]); 
                SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $invite_row[id], $dt, $msg, 0)"); 
            } 
            header("Refresh: 0; url=account-confirm-ok.php?type=confirm"); 
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
        header("Refresh: 0; url=account-confirm-ok.php?type=signup&email=" . urlencode($email)); 
    }else{ //email confirmation is off 
        header("Refresh: 0; url=account-confirm-ok.php?type=noconf"); 
    } 
    //send pm to new user 
    if ($site_config["WELCOMEPMON"]){ 
        $dt = sqlesc(get_date_time()); 
        $msg = sqlesc($site_config["WELCOMEPMMSG"]); 
        SQL_Query_exec("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $id, $dt, $msg, 0)"); 
    } 

    die; 
  } 

}//end takesignup 


if ($_GET["takesignup"] == "1") { 
stdhead(T_("SIGNUP")); 

echo '<form id="signupbox_form" action="">'; 
    if ($invite_row) { 
       echo '<input type="hidden" name="invite_hash" value="'.$_GET["invite"].'" /> 
       <input type="hidden" name="a_hash" value="'.htmlspecialchars($_GET["secret"]).'" />'; 
    } 

    echo '<div class="tableHeader"> 
        <div class="row"> 
            <div class="cell first"> 
                <span class="floatright"> 
                    <img src="/themes/default/social/facebook.png" alt="Signing up through facebook will make the signup process very easy! It will give us access to you email, profile picture and other information that will help us to help you!" title="Signing up through facebook will make the signup process very easy! It will give us access to you email, profile picture and other information that will help us to help you!" class="middle" /> <a href="#" title="Signing up through facebook will make the signup process very easy! It will give us access to you email, profile picture and other information that will help us to help you!">Sign up through Facebook</a>
                </span> 
                Sign up Form 
            </div> 
        </div> 
    </div>'; 
     
    echo '<div class="table"> 

        <div class="row"> 
            <div class="cell"><label for="signupbox_membername">'.T_("USERNAME").'</label></div> 
            <div class="cell"><label for="signupbox_date_of_birth">Date of Birth</label></div> 
        </div> 

        <div class="row"> 
            <div class="cell"> 
                <input type="text" name="signupbox_membername" id="signupbox_membername" class="s" accesskey="m" value="" title="Must be between 3-20 characters. Allowed characters: a-z A-Z 0-9" /> 
            </div> 
            <div class="cell"> 
                <input type="date" name="signupbox_date_of_birth" id="signupbox_date_of_birth" class="s" accesskey="d" value="" title="Please select your birth of day. Example: 22/03/1980" /> 
            </div> 
        </div> 

        <div class="row"> 
            <div class="cell"><label for="signupbox_email">'.T_("EMAIL").'</label></div> 
            <div class="cell"><label for="signupbox_email2">Confirm Email Address</label></div> 
        </div> 

        <div class="row"> 
            <div class="cell"> 
                <input type="text" name="signupbox_email" id="signupbox_email" class="s" accesskey="e" value="" title="Please enter a valid Email Address. We won\'t send you any marketing material." /> 
            </div> 
            <div class="cell"> 
                <input type="text" name="signupbox_email2" id="signupbox_email2" class="s" accesskey="f" value="" title="Enter your Email Address again." /> 
            </div> 
        </div> 

        <div class="row"> 
            <div class="cell"><label for="signupbox_password">'.T_("PASSWORD").'</label></div> 
            <div class="cell"><label for="signupbox_password2">'.T_("CONFIRM").'</label></div> 
        </div> 

        <div class="row"> 
            <div class="cell"> 
                <div class="passwordStrength"> 
                    <div class="score"><span><b></b></span></div> 
                    <input type="password" name="signupbox_password" id="signupbox_password" class="s" accesskey="p" value="" title="Try to make it hard to guess. Must be at least 5 characters." /> 
                </div> 
            </div> 
            <div class="cell"> 
                <input type="password" name="signupbox_password2" id="signupbox_password2" class="s" accesskey="c" value="" title="Enter your Password again." /> 
            </div> 
        </div> 

        <div class="row"> 
            <div class="cell"><label for="signupbox_country">'.T_("COUNTRY").'</label></div> 
            <div class="cell"><label for="signupbox_prefbitclient">'.T_("PREF_BITTORRENT_CLIENT").'</label></div> 
        </div> 

        <div class="row"> 
            <div class="cell"> 
                <select name="signupbox_country" class="s" id="signupbox_country">';
                    $countries = "<option value=\"0\">---- ".T_("NONE_SELECTED")." ----</option>\n"; 
                    $ct_r = SQL_Query_exec("SELECT id,name,domain from countries ORDER BY name"); 
                    while ($ct_a = mysqli_fetch_assoc($ct_r)) { 
                        $countries .= "<option value=\"$ct_a[id]\">$ct_a[name]</option>\n"; 
                    } 
                    echo $countries; 
                echo '</select> 
            </div> 
            <div class="cell"> 
                <input type="text" name="signupbox_prefbitclient" id="signupbox_prefbitclient" class="s" accesskey="c" value="" title="Enter your Prefered Bittorrent Client." /> 
            </div> 
        </div> 

        <div class="row"> 
            <div class="cell">Gender</div> 
            <div class="cell"><label for="signupbox_timezone">Time zone</label></div> 
        </div> 

        <div class="row"> 
            <div class="cell"> 
                <input type="radio" name="signupbox_gender" id="memberinfo_gender_female" value="f" /> <label for="memberinfo_gender_female">'.T_("FEMALE").'</label> 
                <input type="radio" name="signupbox_gender" id="memberinfo_gender_male" value="m" /> <label for="memberinfo_gender_male">'.T_("MALE").'</label> 
                <input type="radio" name="signupbox_gender" id="memberinfo_gender_unspecified" value="" checked="checked" /> <label for="memberinfo_gender_unspecified">(unspecified)</label> 
            </div> 
            <div class="cell"> 
                <select name="signupbox_timezone" class="s" id="signupbox_timezone"> 
                    <option value="-12">(GMT -12:00) Eniwetok, Kwajalein</option> 
                    <option value="-11">(GMT -11:00) Midway Island, Samoa</option> 
                    <option value="-10">(GMT -10:00) Hawaii</option> 
                    <option value="-9">(GMT -9:00) Alaska</option> 
                    <option value="-8">(GMT -8:00) Pacific Time (US & Canada)</option> 
                    <option value="-7">(GMT -7:00) Mountain Time (US & Canada)</option> 
                    <option value="-6">(GMT -6:00) Central Time (US & Canada), Mexico City</option> 
                    <option value="-5">(GMT -5:00) Eastern Time (US & Canada), Bogota, Lima</option> 
                    <option value="-4.5">(GMT -4:30) Caracas</option> 
                    <option value="-4">(GMT -4:00) Atlantic Time (Canada), La Paz, Santiago</option> 
                    <option value="-3.5">(GMT -3:30) Newfoundland</option> 
                    <option value="-3">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option> 
                    <option value="-2">(GMT -2:00) Mid-Atlantic</option> 
                    <option value="-1">(GMT -1:00 hour) Azores, Cape Verde Islands</option> 
                    <option value="0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option> 
                    <option value="1" selected="selected">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option> 
                    <option value="2">(GMT +2:00) Kaliningrad, South Africa, Cairo</option> 
                    <option value="3">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option> 
                    <option value="3.5">(GMT +3:30) Tehran</option> 
                    <option value="4">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option> 
                    <option value="4.5">(GMT +4:30) Kabul</option> 
                    <option value="5">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option> 
                    <option value="5.5">(GMT +5:30) Mumbai, Kolkata, Chennai, New Delhi</option> 
                    <option value="5.75">(GMT +5:45) Kathmandu</option> 
                    <option value="6">(GMT +6:00) Almaty, Dhaka, Colombo</option> 
                    <option value="6.5">(GMT +6:30) Yangon, Cocos Islands</option> 
                    <option value="7">(GMT +7:00) Bangkok, Hanoi, Jakarta</option> 
                    <option value="8">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option> 
                    <option value="9">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option> 
                    <option value="9.5">(GMT +9:30) Adelaide, Darwin</option> 
                    <option value="10">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option> 
                    <option value="11">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option> 
                    <option value="12">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option> 
                </select> 
            </div> 
        </div> 

    </div>'; 

    if ($recaptcha == "1"){ 
        echo '<div class="tableFooter"> 
            <div class="row"> 
                <div class="cell"> 
                    <div id="recaptcha_widget"> 
                        <div class="captcha_field1"> 
                            <div>Verification:</div> 
                            <div id="recaptcha_image"></div> 
                            <div class="recaptcha_only_if_incorrect_sol"></div> 
                        </div> 

                        <span class="recaptcha_only_if_image"></span> 
                        <span class="recaptcha_only_if_audio"></span> 
     
                        <div class="captcha_field2"> 
                            <div>Enter both words</div> 
                            <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" class="s" /> 

                            <div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.reload()">Reload</a> or <a href="javascript:Recaptcha.switch_type(\'audio\')">listen to audio</a>. By reCAPTCHA&#8482;</div> 
                            <div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.reload()">Reload</a> or <a href="javascript:Recaptcha.switch_type(\'image\')">go back to text</a>. By reCAPTCHA&#8482;</div> 
                        </div> 

                        <div class="clear"></div> 
                    </div> 

                    <div id="recaptcha_loading"> 
                        <img src="http://templateshares-ue.net/tsue/styles/default/ajax/fb_ajax-loader.gif" alt="" title="" class="middle" /> ReCAPTCHA verification is loading. Please refresh the page if it does not load. 
                    </div> 
                </div> 
            </div> 
        </div>'; 
    } 

    echo '<div class="tableFooter"> 
        <div class="row"> 
            <div class="cell"> 
                <input type="submit" value="Sign up" class="submit" />  
                <input type="reset" value="Clear" class="submit" id="signup-buttons" /> 
            </div> 
        </div> 
    </div> 

</form>'; 

stdfoot(); 
die; 
}//end takesignup 

stdhead(T_("SIGNUP")); 

echo '<div class="tableHeader"> 
    <div class="row"> 
        <div class="cell first"> 
            You must agree to the Terms of Service and Rules 
        </div> 
    </div> 
</div> 

<div class="whiteBox"> 
    The providers ("we", "us", "our") of the service provided by this web site ("Service") are not responsible for any user-generated content and accounts ("Content"). Content submitted express the views of their author only.<br /><br />You agree to not use the Service to submit or link to any Content which is defamatory, abusive, hateful, threatening, spam or spam-like, likely to offend, contains adult or objectionable content, contains personal information of others, risks copyright infringement, encourages unlawful activity, or otherwise violates any laws.<br /><br />All Content you submit or upload may be reviewed by staff members. All Content you submit or upload may be sent to third-party verification services (including, but not limited to, spam prevention services). Do not submit any Content that you consider to be private or confidential.<br /><br />We reserve the rights to remove or modify any Content submitted for any reason without explanation. Requests for Content to be removed or modified will be undertaken only at our discretion. We reserve the right to take action against any account with the Service at any time.<br /><br />You are granting us with a non-exclusive, permanent, irrevocable, unlimited license to use, publish, or re-publish your Content in connection with the Service. You retain copyright over the Content.<br /><br />These terms may be changed at any time without notice.<br /><br />If you do not agree with these terms, please do not register or use this Service. 
</div> 

<div class="whiteBox"> 
    <input type="button" name="agree_terms_of_service_and_rules" value="I Agree to the Terms of Service and Rules" /> 
</div>'; 

stdfoot(); 
?>