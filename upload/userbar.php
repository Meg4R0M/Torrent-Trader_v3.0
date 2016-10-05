<? 
$template_file = "torrentbar/template.png"; 

$rating_x = 162; 
$rating_y = 7; 

$upload_x = 26; 
$upload_y = 7; 

$download_x = 290; 
$download_y = 7; 

$digits_template = "torrentbar/digits.png"; 
$digits_config = "torrentbar/digits.ini"; 

//=========================================================================== 
// Funtions 
//=========================================================================== 

function getParam() { 
    $res = preg_replace("#(.*)\/(.*)\.png#i", "$2", $_SERVER['REQUEST_URI']); 
    $res = trim(substr(trim($res), 0, 10)); 
    if (! is_numeric($res)) { die("Invalid user_id or hacking attempt."); } 
    return $res; 
} 

function mysql_init() { 
    global $mysql_host, $mysql_db, $mysql_user, $mysql_pass; 
    if ($mysql_pass!='') { 
        $link = @($GLOBALS["___mysqli_ston"] = mysqli_connect($mysql_host,  $mysql_user,  $mysql_pass)) or die("Cannot connect to database!");  
    } else { 
        $link = @($GLOBALS["___mysqli_ston"] = mysqli_connect($mysql_host,  $mysql_user)) or die("Cannot connect to database!");  
    } 
    ((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $mysql_db)) or die("Cannot select database!"); 
    return $link; 
} 

function ifthen($ifcondition, $iftrue, $iffalse) { 
    if ($ifcondition) { 
        return $iftrue; 
    } else { 
        return $iffalse; 
    } 
} 

function getPostfix($val) { 
    $postfix = "b"; 
    if ($val>=1024)             { $postfix = "kb"; } 
    if ($val>=1048576)          { $postfix = "mb"; } 
    if ($val>=1073741824)       { $postfix = "gb"; } 
    if ($val>=1099511627776)    { $postfix = "tb"; } 
    if ($val>=1125899906842624) { $postfix = "pb"; } 
    if ($val>=1152921504606846976)       { $postfix = "eb"; } 
    if ($val>=1180591620717411303424)    { $postfix = "zb"; } 
    if ($val>=1208925819614629174706176) { $postfix = "yb"; } 
     
    return $postfix; 
} 

function roundCounter($value, $postfix) { 
    $val=$value; 
    switch ($postfix) { 
    case "kb": $val=$val / 1024; 
        break; 
    case "mb": $val=$val / 1048576; 
        break; 
    case "gb": $val=$val / 1073741824; 
        break; 
    case "tb": $val=$val / 1099511627776; 
        break; 
    case "pb": $val=$val / 1125899906842624; 
        break; 
    case "eb": $val=$val / 1152921504606846976; 
        break; 
    case "zb": $val=$val / 1180591620717411303424; 
        break; 
    case "yb": $val=$val / 1208925819614629174706176; 
        break; 
         
    default: 
        break; 
    } 
    return $val; 
} 

//=========================================================================== 
// Main body 
//=========================================================================== 

// Digits initialization - begin 
$digits_ini = @parse_ini_file($digits_config) or die("Cannot load Digits Configuration file!"); 
$digits_img = @imagecreatefrompng($digits_template) or die("Cannot Initialize new GD image stream!"); 
// Digits initialization - end 

$download_counter = 0; 
$upload_counter = 0; 
$rating_counter = 0; 

$img = @imagecreatefrompng($template_file) or die ("Cannot Initialize new GD image stream!"); 

$userid = getParam(); 
if ($userid!="") { 
    include('backend/mysql.php'); 
    mysql_init(); 
     
    $query = "SELECT COUNT(id) FROM users WHERE id = '".$userid."'"; 
    $result = @mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Could not select data!"); 
    $counter = mysql_result($result, 0); 
    ((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false); 
     
    if ($counter>0) { 
        $query = "SELECT uploaded, downloaded FROM users WHERE id = ".$userid; 
        $result = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die("Could not select data!"); 
         
        while ($data = mysqli_fetch_array($result)) 
        { 
            $upload_counter = $data['uploaded']; 
            $download_counter = $data['downloaded']; 
            if ($download_counter>0) { 
                $rating_counter = $upload_counter / $download_counter; 
            } 
        } 
    } 
} 

$dot_pos = strpos((string) $rating_counter, "."); 
if ($dot_pos>0) { 
    $rating_counter = (string) round(substr((string) $rating_counter, 0, $dot_pos+1+2), 2); 
} else { 
    $rating_counter = (string) $rating_counter; 
} 
if($rating_counter > 10) 
$rating_counter = "10";  
$counter_x = $rating_x; 
for ($i=0; $i<strlen($rating_counter); $i++) { 
    $d_x=$digits_ini[ifthen($rating_counter[$i]==".", "dot", $rating_counter[$i])."_x"]; 
    $d_w=$digits_ini[ifthen($rating_counter[$i]==".", "dot", $rating_counter[$i])."_w"]; 
    imagecopy($img, $digits_img, $counter_x, $rating_y, $d_x, 0, $d_w, imagesy($digits_img)); 
    $counter_x=$counter_x+$d_w-1; 
} 


$postfix = getPostfix($upload_counter); 
$upload_counter = roundCounter($upload_counter, $postfix); 
$dot_pos = strpos((string) $upload_counter, "."); 
if ($dot_pos>0) { 
    $upload_counter = (string) round(substr((string) $upload_counter, 0, $dot_pos+1+2), 2); 
} else { 
    $upload_counter = (string) $upload_counter; 
} 
$counter_x = $upload_x; 
for ($i=0; $i<strlen($upload_counter); $i++) { 
    $d_x=$digits_ini[ifthen($upload_counter[$i]==".", "dot", $upload_counter[$i])."_x"]; 
    $d_w=$digits_ini[ifthen($upload_counter[$i]==".", "dot", $upload_counter[$i])."_w"]; 
    imagecopy($img, $digits_img, $counter_x, $upload_y, $d_x, 0, $d_w, imagesy($digits_img)); 
    $counter_x=$counter_x+$d_w-1; 
} 
$counter_x+=3; 
$d_x=$digits_ini[$postfix."_x"]; 
$d_w=$digits_ini[$postfix."_w"]; 
imagecopy($img, $digits_img, $counter_x, $upload_y, $d_x, 0, $d_w, imagesy($digits_img)); 


$postfix = getPostfix($download_counter); 
$download_counter = roundCounter($download_counter, $postfix); 
$dot_pos = strpos((string) $download_counter, "."); 
if ($dot_pos>0) { 
    $download_counter = (string) round(substr((string) $download_counter, 0, $dot_pos+1+2), 2); 
} else { 
    $download_counter = (string) $download_counter; 
} 
$counter_x = $download_x; 
for ($i=0; $i<strlen($download_counter); $i++) { 
    $d_x=$digits_ini[ifthen($download_counter[$i]==".", "dot", $download_counter[$i])."_x"]; 
    $d_w=$digits_ini[ifthen($download_counter[$i]==".", "dot", $download_counter[$i])."_w"]; 
    imagecopy($img, $digits_img, $counter_x, $download_y, $d_x, 0, $d_w, imagesy($digits_img)); 
    $counter_x=$counter_x+$d_w-1; 
} 
$counter_x+=3; 
$d_x=$digits_ini[$postfix."_x"]; 
$d_w=$digits_ini[$postfix."_w"]; 
imagecopy($img, $digits_img, $counter_x, $download_y, $d_x, 0, $d_w, imagesy($digits_img)); 
header("Content-type: image/png"); 
imagepng($img); 
imagedestroy($img); 
?>