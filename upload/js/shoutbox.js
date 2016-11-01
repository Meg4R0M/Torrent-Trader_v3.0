
var GetChaturl = "shoutbox.php";
var SendChaturl = "sendshout.php";
var lastID = -1; //initial value will be replaced by the latest known id

// initiates the two objects for sending and receiving data
var httpReceiveChat = getHTTPObject();
var httpSendChat = getHTTPObject();

window.onload = initJavaScript;

function initJavaScript() {
window.status = "";
    document.forms['chatForm'].elements['chatbarText'].setAttribute('autocomplete','off'); //this non standard attribute prevents firefox' autofill function to clash with this script
    checkStatus(''); //sets the initial value and state of the input comment
    receiveChatText(); //initiates the first data query
}

//deletes main shout window
function DeleteShout() {
    document.forms['chatForm'].elements['chatbarText'].value = document.forms['chatForm'].elements['chatbarText'].value+" "+smile+" ";  //this non standard attribute prevents firefox' autofill function to clash with this script
    document.forms['chatForm'].elements['chatbarText'].focus();
}

//inserts smilies into form
function SmileIT(smile){
    document.forms['chatForm'].elements['chatbarText'].value = document.forms['chatForm'].elements['chatbarText'].value+" "+smile+" ";  //this non standard attribute prevents firefox' autofill function to clash with this script
    document.forms['chatForm'].elements['chatbarText'].focus();
}

//pops out history window
function Pophistory() {
         newWin=window.open('ajShoutHistory.php?history=1&page=0','shouthistory','height=500,width=490,resizable=yes,scrollbars=yes');
         if (window.focus) {newWin.focus()}
}

function purge(uid) {
         newWin=window.open('chatedit.php?purge='+uid,'delete','height=300,width=490,resizable=yes,scrollbars=yes');
         if (window.focus) {newWin.focus()}
}

function resize(img) {
    if (img.width>500) {
        img.height=parseInt(img.height*500/img.width);
        img.width=200;
        img.title='Click on image for full size view.';
        var foo=document.getElementById(img.name);
        foo.innerHTML='<strong>Cliquez sur l\'image pour voir en taille réelle.</strong><br /><a href="'+img.src+'" target="_blank">'+foo.innerHTML+'</a>';
    }
}

//initiates the first data query
function receiveChatText() {
    if (httpReceiveChat.readyState == 4 || httpReceiveChat.readyState == 0) {
    httpReceiveChat.open("GET",GetChaturl + '?lastID=' + lastID + '&rand='+Math.floor(Math.random() * 1000000), true);
      httpReceiveChat.onreadystatechange = handlehHttpReceiveChat; 
    httpReceiveChat.send(null);
    }
}

//deals with the servers' reply to requesting new content
function handlehHttpReceiveChat() {

  if (httpReceiveChat.readyState == 4) {

     var results = document.getElementById("outputList");
     results.innerHTML = httpReceiveChat.responseText;

     setTimeout('receiveChatText();',20000); //executes the next data query in 4 seconds

  }
}

//stores a new comment on the server
function sendComment() {
    if (httpSendChat.readyState == 4 || httpSendChat.readyState == 0) {
    currentChatText = encodeURIComponent(document.forms['chatForm'].elements['chatbarText'].value);
    if (currentChatText != '') {
        currentName = encodeURIComponent(document.forms['chatForm'].elements['name'].value);
        currentUid = document.forms['chatForm'].elements['uid'].value;
        param = 'n='+ currentName+'&c='+ currentChatText+'&u='+ currentUid; 
        httpSendChat.open("POST", SendChaturl, true);
        httpSendChat.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    httpSendChat.onreadystatechange = handlehHttpSendChat;
    httpSendChat.send(param);
    document.forms['chatForm'].elements['chatbarText'].value = '';
    }
    } else {
        setTimeout('sendComment();',1000);
    }
}

//deals with the servers' reply to sending a comment
function handlehHttpSendChat() {
  if (httpSendChat.readyState == 4) {
    receiveChatText(); //refreshes the chat after a new comment has been added (this makes it more responsive)
  }
}

//does clever things to the input and submit
function checkStatus(focusState) {
    currentChatText = document.forms['chatForm'].elements['chatbarText'];
    oSubmit = document.forms['chatForm'].elements['submit'];
    if (currentChatText.value != '' || focusState == 'active') {
        oSubmit.disabled = false;
    } else {
        oSubmit.disabled = true;
    }
}

//initiates the XMLHttpRequest object
//as found here: http://www.webpasties.com/xmlHttpRequest
function getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}

function show_hide(sextra1){
    if(document.getElementById(sextra1)){
        if(document.getElementById(sextra1).style.display == 'none'){
            document.getElementById(sextra1).style.display = 'inline';
        }else{
            document.getElementById(sextra1).style.display = 'none';
        }
    }
}

$(document).on('click','#edit_shout',function(e){
    e.preventDefault();
    var shoutID=parseInt($(this).attr('shoutid'));
    if(!shoutID||shoutID=='0'){
        return false;
    }
    $.ajax({
        data:'action=edit_shout&msgid='+parseInt(shoutID)+'&securitytoken='+TSUESettings['stKey'],
        success:function(serverResponse){
            $.TSUE.memberCard(serverResponse);
            $.TSUE.memberInfoCache[shoutID]=serverResponse;
        }
    });
    return false;
});

$(document).on('click','#del_shout',function(e){
    e.preventDefault();
    var shoutID=parseInt($(this).attr('shoutid'));
    if(!shoutID||shoutID=='0'){
        return false;
    }
    $.ajax({
        data:'action=del_shout&msgid='+parseInt(shoutID)+'&securitytoken='+TSUESettings['stKey'],
        success:function(serverResponse){
            $.TSUE.memberCard(serverResponse);
            $.TSUE.memberInfoCache[shoutID]=serverResponse;
        }
    });
    return false;
});

$(document).on('click','#more_smilies',function(e){
    e.preventDefault();
    $.ajax({
        data:'action=moresmilies&securitytoken='+TSUESettings['stKey'],
        success:function(serverResponse){
            $.TSUE.memberCard(serverResponse);
        }
    });
    return false;
});

//pops out more smilies window
function PopMoreSmiles(form,name) {
    newWin=window.open('moresmiles.php?form='+form+'&text='+name,'moresmile','height=500,width=450,resizable=yes,scrollbars=yes');
    if (window.focus) {newWin.focus()}
}