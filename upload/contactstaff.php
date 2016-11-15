<?php
require_once("backend/functions.php");
require_once("backend/bbcode.php");

dbconn(false);

stdhead("Contact Staff", false);

?>
<!-- Message d'erreur si champs pas rempli -->
<script>
    function valider_formulaire(thisForm){
        var regexp = /^\s/ ;
        if(thisForm.subject.value == ''){
            alert('Le champ "Sujet" doit être rempli');
            thisForm.subject.focus();
            return false;
        }
	    if (thisForm.subject.value.match(regexp)) {
		    alert('Le champ "Sujet" ne doit pas comporter d\'espace au début');
            thisForm.subject.focus();
            return false;
 	    }
        if(thisForm.msg.value == '') {
            alert('Le champ "Message" doit être rempli');
            thisForm.msg.focus();
            return false;
        }
        if (thisForm.msg.value.match(regexp)) {
		    alert('Le champ "Message" ne doit pas comporter d\'espace au début');
    	    thisForm.msg.focus();
    	    return false;
 	    }
        return true;
    }
    function bbshout(repdeb, repfin) {
        var input = document.forms['message'].elements['msg'];
        input.focus();

        if(typeof document.selection != 'undefined') {
            var range = document.selection.createRange();
            var insText = range.text;
            range.text = repdeb + insText + repfin;
            range = document.selection.createRange();
            if (insText.length == 0) {
                range.move('character', -repfin.length);
            }else{
                range.moveStart('character', repdeb.length + insText.length + repfin.length);
            }
            range.select();
        }else if(typeof input.selectionStart != 'undefined'){
            var start = input.selectionStart;
            var end = input.selectionEnd;
            var insText = input.value.substring(start, end);
            input.value = input.value.substr(0, start) + repdeb + insText + repfin + input.value.substr(end);
            var pos;
            if (insText.length == 0) {
                pos = start + repdeb.length;
            }else{
                pos = start + repdeb.length + insText.length + repfin.length;
            }
            input.selectionStart = pos;
            input.selectionEnd = pos;
        }else{
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
    function bbcolor(form,text) {
	    var colorvalue = document.forms['message'].elements['color'].value;
	    bbshout("[color="+colorvalue+"]", "[/color]");
    }
    function bbfont() {
	    var fontvalue = document.forms['message'].elements['font'].value;
	    bbshout("[font="+fontvalue+"]", "[/font]");
    }
    function bbsize() {
        var sizevalue = document.forms['message'].elements['size'].value;
        bbshout("[size="+sizevalue+"]", "[/size]");
    }
</script>
<!-- fin --><?php
echo '<form method="post" name="message" id="message" action="takecontact.php" onSubmit="return valider_formulaire(this)">

<div class="tableHeader">
	<div class="row">
		<div class="cell first">
			Contact Staff
		</div>
	</div>
</div>

<div class="table">

	<div class="row">
		<div class="cell">
			<div><label for="subject">Subject :</label></div>
			<input class="s" type="text" name="subject">
		</div>
	</div>

	<div class="row">
		<div class="cell">
			<div><label for="message">What you would like to contact us about?</label></div>
            <textarea name="msg" id="message" class="tabletinymce" /></textarea>
        </div>
	</div>

	<div class="row">
		<div class="cell">
			<input type="submit" value="Send" class="submit" /> 
			<input type="reset" value="Clear" class="submit" id="contactstaff-buttons" />
		</div>
	</div>

</div>

</form>';

stdfoot();
?>