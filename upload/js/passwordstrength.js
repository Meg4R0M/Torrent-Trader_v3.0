
var intScore=0,strVerdict='',strLog='',passLength=0;var checkPassword=function(passwd)
{intScore=0,strVerdict='',strLog='',passLength=passwd.length;if(passLength<=2)
{strVerdict='In-secure',strLog='Password too short.';return;}
if(passLength<5)
{intScore=(intScore+3)
strLog=strLog+"3 points for length ("+passLength+")\n"}
else if(passLength>4&&passLength<8)
{intScore=(intScore+6)
strLog=strLog+"6 points for length ("+passLength+")\n"}
else if(passLength>7&&passLength<16)
{intScore=(intScore+12)
strLog=strLog+"12 points for length ("+passLength+")\n"}
else if(passLength>15)
{intScore=(intScore+18)
strLog=strLog+"18 point for length ("+passLength+")\n"}
if(passwd.match(/[a-z]/))
{intScore=(intScore+1)
strLog=strLog+"1 point for at least one lower case char\n"}
if(passwd.match(/[A-Z]/))
{intScore=(intScore+5)
strLog=strLog+"5 points for at least one upper case char\n"}
if(passwd.match(/\d+/))
{intScore=(intScore+5)
strLog=strLog+"5 points for at least one number\n"}
if(passwd.match(/(.*[0-9].*[0-9].*[0-9])/))
{intScore=(intScore+5)
strLog=strLog+"5 points for at least three numbers\n"}
if(passwd.match(/.[!,@,#,$,%,^,&,*,(,),?,_,~]/))
{intScore=(intScore+5)
strLog=strLog+"5 points for at least one special char\n"}
if(passwd.match(/(.*[!,@,#,$,%,^,&,*,(,),?,_,~].*[!,@,#,$,%,^,&,*,(,),?,_,~])/))
{intScore=(intScore+5)
strLog=strLog+"5 points for at least two special chars\n"}
if(passwd.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))
{intScore=(intScore+2)
strLog=strLog+"2 combo points for upper and lower letters\n"}
if(passwd.match(/([a-zA-Z])/)&&passwd.match(/([0-9])/))
{intScore=(intScore+2)
strLog=strLog+"2 combo points for letters and numbers\n"}
if(passwd.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,(,),?,_,~])|([!,@,#,$,%,^,&,*,(,),?,_,~].*[a-zA-Z0-9])/))
{intScore=(intScore+2)
strLog=strLog+"2 combo points for letters, numbers and special chars\n"}
if(intScore<16)
{strVerdict="very weak"}
else if(intScore>15&&intScore<25)
{strVerdict="weak"}
else if(intScore>24&&intScore<35)
{strVerdict="mediocre"}
else if(intScore>34&&intScore<45)
{strVerdict="strong"}
else
{strVerdict="stronger"}}
function showPasswordStrength(fieldID)
{$(fieldID).focusin(function()
{$('div.score').fadeIn('slow');}).keyup(function()
{checkPassword($(this).val());$('#passwordScore').remove();$('div.score span b').width(0).animate({width:intScore},100);$('<div id="passwordScore">'+intScore+'/50</div>').appendTo('div.tipsy-inner');});}
