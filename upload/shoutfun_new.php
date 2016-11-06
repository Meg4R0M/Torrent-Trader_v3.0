<script type="text/javascript">
<!--
function bbshout(repdeb, repfin) {
  var input = document.forms['chatForm'].elements['chatbarText'];
  input.focus();

  if(typeof document.selection != 'undefined') {

    var range = document.selection.createRange();
    var insText = range.text;
    range.text = repdeb + insText + repfin;

    range = document.selection.createRange();
    if (insText.length == 0) {
      range.move('character', -repfin.length);
    } else {
      range.moveStart('character', repdeb.length + insText.length + repfin.length);
    }
    range.select();
  } 

  else if(typeof input.selectionStart != 'undefined') {

    var start = input.selectionStart;
    var end = input.selectionEnd;
    var insText = input.value.substring(start, end);
    input.value = input.value.substr(0, start) + repdeb + insText + repfin + input.value.substr(end);

    var pos;
    if (insText.length == 0) {
      pos = start + repdeb.length;
    } else {
      pos = start + repdeb.length + insText.length + repfin.length;
    }
    input.selectionStart = pos;
    input.selectionEnd = pos;
  }

  else
  {

    var pos;
    var re = new RegExp('^[0-9]{0,3}$');
    while(!re.test(pos)) {
      pos = prompt("Insertion à la position (0.." + input.value.length + "):", "0");
    }
    if(pos > input.value.length) {
      pos = input.value.length;
    }

    var insText = prompt("Veuillez entrer le texte à formater:");
    input.value = input.value.substr(0, pos) + repdeb + insText + repfin + input.value.substr(pos);
  }
}

function bbcolor() {
	var colorvalue = document.forms['chatForm'].elements['color'].value;
	bbshout("[color="+colorvalue+"]", "[/color]");
}

function bbfont() {
	var fontvalue = document.forms['chatForm'].elements['font'].value;
	bbshout("[font="+fontvalue+"]", "[/font]");
}
function bbsize() {
    var sizevalue = document.forms['chatForm'].elements['size'].value;
    bbshout("[size="+sizevalue+"]", "[/size]");
}
function bbsize() {
    var sizevalue = document.forms['chatForm'].elements['size'].value;
    bbshout("[size="+sizevalue+"]", "[/size]");
}
function initialise(select) {
select.selectedIndex = 0;
}

//-->
</script>

<?php
require_once("backend/functions.php");
dbconn();

$dossier = $CURUSER['bbcode'];
function shoutfun($dos,$doss,$dossier){
echo "<table style=\"display: flex;flex-direction: row;justify-content: space-around;\">";
echo "<tr style='display:flex; flex-wrap: wrap;'>";
echo "<td title='Gras'><button onclick=\"bbshout('[b]', '[/b]')\" class=\"submit\"><i class=\"fa fa-bold\" aria-hidden=\"true\"></i></button></td>";
echo "<td><button onclick=\"bbshout('[i]', '[/i]')\" class=\"submit\"><i class=\"fa fa-italic\" aria-hidden=\"true\" title='Italic'></i></button></td>";
echo "<td><button onclick=\"bbshout('[u]', '[/u]')\" class=\"submit\"><i class=\"fa fa-underline\" aria-hidden=\"true\" title='Souligner'></i></button></td>";
echo "<td><button onclick=\"bbshout('[blink]', '[/blink]')\" class=\"submit\"><i class=\"fa fa-lightbulb-o\" aria-hidden=\"true\" title='Clignotement'></i></button></td>";
echo "<td><button onclick=\"bbshout('[df]', '[/df]')\" class=\"submit\"><i class=\"fa fa-sliders\" aria-hidden=\"true\" title='Défiler'></i></button></td>";
echo "<td><button onclick=\"bbshout('[highlight]', '[/highlight]')\" class=\"submit\"><i class=\"fa fa-thumb-tack\" aria-hidden=\"true\" title='Marqueur'></i></button></td>";
echo "<td><button onclick=\"bbshout('[url]', '[/url]')\" class=\"submit\"><i class=\"fa fa-link\" aria-hidden=\"true\" title='Url'></i></button></td>";
echo "<td><button onclick=\"bbshout('[img]', '[/img]')\" class=\"submit\"><i class=\"fa fa-picture-o\" aria-hidden=\"true\" title='Img'></i></button></td>";
echo "<td><button onclick=\"bbshout('[swf]', '[/swf]')\" class=\"submit\"><i class=\"fa fa-video-camera\" aria-hidden=\"true\" title='SWF'></i></button></td>";
echo "<td><button onclick=\"bbshout('[code]', '[/code]')\" class=\"submit\"><i class=\"fa fa-code\" aria-hidden=\"true\" title='Code'></i></button></td>";
echo "<td><button onclick=\"bbshout('[quote]', '[/quote]')\" class=\"submit\"><i class=\"fa fa-quote-left\" aria-hidden=\"true\" title='Quote'></i></button></td>";
echo "<td><button onclick=\"bbshout('[audio]', '[/audio]')\" class=\"submit\"><i class=\"fa fa-headphones\" aria-hidden=\"true\" title='Audio'></i></button></td>";
echo "<td><button onclick=\"bbshout('[video]', '[/video]')\" class=\"submit\"><i class=\"fa fa-play\" aria-hidden=\"true\" title='Video'></i></button></td>";
//echo "<td width=22><a href=\"nforipper.php target=_blank \"><img src=images/bbcode/$dossier/bbcode_nfo.gif border=0 title='NF0 Riper'></a></td>";
echo "<td><button onclick=\"bbshout('[left]', '[/left]')\" class=\"submit\"><i class=\"fa fa-align-left\" aria-hidden=\"true\" title='Gauche'></i></button></td>";
echo "<td><button onclick=\"bbshout('[center]', '[/center]')\" class=\"submit\"><i class=\"fa fa-align-center\" aria-hidden=\"true\" title='Center'></i></button></td>";
echo "<td><button onclick=\"bbshout('[right]', '[/right]')\" class=\"submit\"><i class=\"fa fa-align-right\" aria-hidden=\"true\" title='Droite'></i></button></td>";
echo "<td><button onclick=\"bbshout('[googlemaps]', '[/googlemaps]')\" class=\"submit\"><i class=\"fa fa-map\" aria-hidden=\"true\" title='Google Maps'></i></button></td>";

echo "</tr></table>";

echo "<table><tr style='display:flex; flex-wrap: wrap;'>";
echo "<td>
<select name='color' onChange=\"javascript:bbcolor(),initialise(this)\" class=\"s\">
<option selected='selected'>Couleurs</option>
	        <option value='#000000' style='BACKGROUND-COLOR:#000000'>Noir</option>
            <option value='#686868' style='BACKGROUND-COLOR:#686868'>Gris</option>
            <option value='#708090' style='BACKGROUND-COLOR:#708090'>Gris Pierre</option>
            <option value='#C0C0C0' style='BACKGROUND-COLOR:#C0C0C0'>Gris Arent</option>
            <option value='#FFFFFF' style='BACKGROUND-COLOR:#FFFFFF'>Blanc</option>
            <option value='#FFFFE0' style='BACKGROUND-COLOR:#FFFFE0'>Blanc Ivoire</option>
            <option value='#880000' style='BACKGROUND-COLOR:#880000'>Rouge Foncer</option>
            <option value='#B82428' style='BACKGROUND-COLOR:#B82428'>Rouge Brick</option>
            <option value='#FF0000' style='BACKGROUND-COLOR:#FF0000'>Rouge</option>
            <option value='#FF1490' style='BACKGROUND-COLOR:#FF1490'>Rose Foncer</option>
            <option value='#FF68B0' style='BACKGROUND-COLOR:#FF68B0'>Rose Chaud</option>
            <option value='#FFC0C8' style='BACKGROUND-COLOR:#FFC0C8'>Rose</option>
            <option value='#FF4400' style='BACKGROUND-COLOR:#FF4400'>Orange Foncer</option>
            <option value='#FF6448' style='BACKGROUND-COLOR:#FF6448'>Orange Tomatte</option>
            <option value='#FFA800' style='BACKGROUND-COLOR:#FFA800'>Orange</option>
            <option value='#FFD800' style='BACKGROUND-COLOR:#FFD800'>Jaune Foncer</option>
            <option value='#FFFF00' style='BACKGROUND-COLOR:#FFFF00'>Jaune</option>             
            <option value='#FF00FF' style='BACKGROUND-COLOR:#FF00FF'>Mauve</option>
            <option value='#C01480' style='BACKGROUND-COLOR:#C01480'>Mauve Foncer</option>
            <option value='#B854D8' style='BACKGROUND-COLOR:#B854D8'>Mauve Indigo</option>
            <option value='#D8A0D8' style='BACKGROUND-COLOR:#D8A0D8'>Mauve Pale</option>
            <option value='#000080' style='BACKGROUND-COLOR:#000080'>Bleu foncer</option>           
            <option value='#0000FF' style='BACKGROUND-COLOR:#0000FF'>Bleu</option>            
            <option value='#2090FF' style='BACKGROUND-COLOR:#2090FF'>Bleu ciel</option> 
            <option value='#00BCFF' style='BACKGROUND-COLOR:#00BCFF'>Bleu Pale</option>
            <option value='#B0E0E8' style='BACKGROUND-COLOR:#B0E0E8'>Bleu Claire</option>
            <option value='#A02828' style='BACKGROUND-COLOR:#A02828'>Brun</option>
            <option value='#F0A460' style='BACKGROUND-COLOR:#F0A460'>Brun Creme</option>  
            <option value='#D0B488' style='BACKGROUND-COLOR:#D0B488'>Brun Pale</option>               
            <option value='#B8B868' style='BACKGROUND-COLOR:#B8B868'>Brun Vert</option>
            <option value='#008000' style='BACKGROUND-COLOR:#008000'>Vert Foncer</option>
            <option value='#30CC30' style='BACKGROUND-COLOR:#30CC30'>Vert</option>
            <option value='#00FF80' style='BACKGROUND-COLOR:#00FF80'>Vert Fluo</option>
            <option value='#98FC98' style='BACKGROUND-COLOR:#98FC98'>Vert Pale</option>
            <option value='#98CC30' style='BACKGROUND-COLOR:#98CC30'>Vert Jaune</option>
            <option value='#40E0D0' style='BACKGROUND-COLOR:#40E0D0'>Vert Turquois</option>
            <option value='#20B4A8' style='BACKGROUND-COLOR:#20B4A8'>Vert Aquarium</option>
            </select></td>";

echo "<td>
<select name='font' onChange=\"javascript:bbcolor(),initialise(this)\" class=\"s\">
          <option value='Arial'>Type de Police</option>
          <option value='Arial' style='font-family: Arial;'>Arial</option>
          <option value='Comic Sans MS' style='font-family: Comic Sans MS;'>Comic Sans MS</option>
          <option value='Trebuchet MS' style='font-family: Trebuchet MS;'>Trebuchet MS</option>
          <option value='Courier New' style='font-family: Courier New;'>Courier New</option>
          <option value='Georgia' style='font-family: Georgia;'>Georgia</option>
          <option value='Helvetica' style='font-family: Helvetica;'>Helvetica</option>
          <option value='Impact' style='font-family: Impact;'>Impact</option>
          <option value='Lucida Sans Unicode' style='font-family: Lucida Sans Unicode;'>Lucida Sans Unicode</option>
          <option value='Microsoft Sans Serif' style='font-family: Microsoft Sans Serif;'>Microsoft Sans Serif</option>
          <option value='Palatino Linotype' style='font-family: Palatino Linotype;'>Palatino Linotype</option>
          <option value='Tahoma' style='font-family: Tahoma;'>Tahoma</option>
          <option value='Times New Roman' style='font-family: Times New Roman;'>Times New Roman</option>
          <option value='Verdana' style='font-family: Verdana;'>Verdana</option>
          <option value='Palatino Linotype' style='font-family: Palatino Linotype;'>Palatino Linotype</option>
          <option value='WESTERN' style='font-family: WESTERN;'>WESTERN</option>
          <option value='Ravie' style='font-family: Ravie;'>Ravie</option>
          <option value='Amerika' style='font-family: Amerika;'>Amerika</option>
		  <option value='Goudy Old Style' style='font-family: Goudy Old Style;'>Goudy Old Style</option>
		  <option value='Papyrus' style='font-family: Papyrus;'>Papyrus</option>
		  <option value='Brush Script MT' style='font-family: Brush Script MT;'>Brush Script MT</option>
		  </select></td>";

echo "<td>
<select name='size' onChange=\"javascript:bbcolor(),initialise(this)\" class=\"s\">
<option selected='selected'>Taille</option>
			<option value='1'>xx-Petit</option>
			<option value='2'>x-Petit</option>
			<option value='3'>Petit</option>
			<option value='4'>Medium</option>
			<option value='5'>Grand</option>
			<option value='6'>x-Grand</option>
			<option value='7'>xx-Grand</option>
            </select></td>";

echo"<td>&nbsp;<button  onclick=\"show_hide('sextra1')\" class=\"submit\"><i class=\"fa fa-arrow-down\" aria-hidden=\"true\" title=\"More !\"></i></button>";
echo "</tr></table>";

}
?>