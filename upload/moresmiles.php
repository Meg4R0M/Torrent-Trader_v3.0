<?php 
//********************************************************//
//  Mega-Tracker-Vidéo v2                                 //
//	This file was last updated: 27/02/2011 by HellsAngels //
//                                                        //	
//	http://mega-tracker-video.net-community.de            //
//                                                        //
//        Based on torrentTrader v2                       //
//                                                        //
//********************************************************//
require_once("backend/functions.php");
require_once("backend/smilies.php");
?>
<html><head>
<title>smilies clickable </title>
<link rel="stylesheet" type="text/css" href="<?=$site_config["SITEURL"]; ?>/themes/smilies.css">
</head>

<script language=javascript>

function SmileIT(smile,form,text){
window.opener.document.forms[form].elements[text].value = window.opener.document.forms[form].elements[text].value+" "+smile+" ";
window.opener.document.forms[form].elements[text].focus();
window.close();
}
</script>

<table class="smile_table" width="100%" cellpadding="0" cellspacing="1">
<tr>
<?

while ((list($code, $url) = each($smilies))) {
if ($count % 5==0)
print("\n<tr>");

print("\n\t<td class=\"smilies\" align=\"center\"><a href=\"javascript: SmileIT('".str_replace("'","\'",$code)."','".$_GET["form"]."','".$_GET["text"]."')\"><img border=0 src=images/smilies/".$url."></a></td>");
$count++;

if ($count % 5==0)
print("\n</tr>");
}


?>
</table>
<div class="smile_table" align="center">
<a href="javascript: window.close()"><font color=white><b>Fermer</b></font></a>
</div>