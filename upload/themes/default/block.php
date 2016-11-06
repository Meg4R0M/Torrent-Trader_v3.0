<?php
//BEGIN FRAME
function begin_frame($caption = "-", $align = "justify"){
    global $THEME, $site_config;
	$togglediv = str_replace(" ", "", $caption);
    print("<div class='widget'>
	<h4>
		<img src='/themes/default/forums/mix/bullet_toggle_minus.png' alt='' title='' rel='$togglediv' id='toggle' class='middle pointer' /> $caption
	</h4>
	<div id='$togglediv' class=''>");
    
}

//END FRAME
function end_frame() {
    global $THEME, $site_config;
    print("<div class='clear'></div>
	</div>
</div>");
}

//BEGIN BLOCK
function begin_block($caption = "-", $align = "justify"){
    global $THEME, $site_config;
	$togglediv = str_replace(" ", "", $caption);
    print("<div class='widget'>
	<h4>
		<span class='floatright'>
			<img src='/themes/default/buttons/refresh.png' alt='Refresh' title='Refresh' rel='refresh$togglediv' class='clickable middle' />
		</span>
		<img src='/themes/default/forums/mix/bullet_toggle_minus.png' alt='' title='' rel='$togglediv' id='toggle' class='middle pointer' /> $caption
	</h4>
	<div id='$togglediv' class=''>");
}

//END BLOCK
function end_block(){
    global $THEME, $site_config;
    print("</div>
	<div class='clear'></div>
</div>");
}

function begin_table(){
    print("<table align='center' cellpadding='0' cellspacing='0' class='ttable_headouter' width='100%'><tr><td><table align='center' cellpadding='0' cellspacing='0' class='ttable_headinner' width='100%'>\n");
}

function end_table()  {
    print("</table></td></tr></table>\n");
}

function tr($x,$y,$noesc=0) {
    if ($noesc)
        $a = $y;
    else {
        $a = htmlspecialchars($y);
        $a = str_replace("\n", "<br />\n", $a);
    }
    print("<tr><td class='heading' valign='top' align='right'>$x</td><td valign='top' align=left>$a</td></tr>\n");
}
?>
