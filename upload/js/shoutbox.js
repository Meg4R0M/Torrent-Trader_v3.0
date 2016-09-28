 /**
 * @package TorrentTrader
 * @version v2.08
 * @author  Lee Howarth
 */
 
 function httpObject()
 {
     var httpObject;
     
     if ( typeof XMLHttpRequest != 'undefined' )
     {
          httpObject = new XMLHttpRequest();
     }
     else
     {
          httpObject = new ActiveXObject( 'MSXML2.XMLHTTP.3.0' );
     }
     
     return httpObject;
 }
 
 function getMessages()
 {
     var httpObject = this.httpObject();               
              
     httpObject.open( 'GET', '/shout.php?action=select', true );
     
     httpObject.onreadystatechange = function()
     {
         if ( ( httpObject.status == 200 ) && ( httpObject.readyState == 4 ) )
         {           
              document.getElementById( 'shouts' ).innerHTML = httpObject.responseText;
                                                                                                                            
              document.getElementById( 'shout' ).focus();
              
              return;
         }
     }
       
     httpObject.setRequestHeader( 'TT', 1 );

     httpObject.send();
 }
 
 function getHistory()
 {
     var httpObject = this.httpObject();               
              
     httpObject.open( 'GET', '/shout.php?action=history', true );
     
     httpObject.onreadystatechange = function()
     {
         if ( ( httpObject.status == 200 ) && ( httpObject.readyState == 4 ) )
         {           
              document.getElementById( 'shoutHistory' ).innerHTML = '<a href="javascript:void(0);" onclick="closeHistory();">[Close History]</a>' + httpObject.responseText;
 
              return;
         }
     }
       
     httpObject.setRequestHeader( 'TT', 1 );

     httpObject.send();
 }
 
 function sendMessage()
 {
     var shout = document.getElementById( 'shout' ).value;
     
     var httpObject = this.httpObject();
     
     if ( shout.length == 0 )
     {
          alert( 'Please enter a message.' );
          
          return false;
     }
     
     if ( shout.length > 255 )
     {
          alert( 'Your shout cannot be longer than 255 characters.' );
          
          return false;
     }
     
     httpObject.open( 'POST', '/shout.php?action=update', true );
     
     httpObject.onreadystatechange = function()
     {
         if ( ( httpObject.status == 200 ) && ( httpObject.readyState == 4 ) )
         {           
              document.getElementById( 'shout' ).value = '';
              
              return;
         }
     }
     
     httpObject.setRequestHeader( 'TT', 1 );
       
     httpObject.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
       
     httpObject.send( 'shout=' + encodeURIComponent( shout ) );
 }
 
 function deleteMessage( sid )
 {
     var response = confirm( 'Delete this message?' );
     
     var httpObject = this.httpObject();
     
     if ( response == true )
     {
          httpObject.open( 'GET', '/shout.php?action=delete&sid=' + sid, true );
          
          httpObject.setRequestHeader( 'TT', 1 );
       
          httpObject.send();
     }
     
     return false;
 }
 
 function openHistory()
 {
     document.getElementById( 'shoutHistory' ).style.display = 'block';
     document.getElementById( 'shoutFade' ).style.display = 'block';
     
     this.getHistory();                    
 }
 
 function closeHistory()
 {
     document.getElementById( 'shoutHistory' ).style.display = 'none';
     document.getElementById( 'shoutFade' ).style.display = 'none';
 }
 
 function refresh()
 {
     var shoutForm = document.getElementById( 'shoutForm' );
 
     shoutForm.innerHTML = '<form name="shoutbox" method="post" action="" onsubmit="sendMessage(); return false;">' +
                            '<input type="text" name="shout" id="shout" style="width: 92%;" />&nbsp;&nbsp;' +
                            '<input type="submit" value="SEND" class="btn btn-danger" />' +
                           '</form>' +
                           '<a href="" onclick="PopMoreTags(); return false;">[Tags]</a> ' +
                           '<a href="javascript:PopMoreSmiles(' + "'" + 'shoutbox' + "'" + ', ' + "'" + 'shout' + "'" + ');">[Smiles]</a> ' +
                           '<a href="" onclick="openHistory(); return false;">[History]</a>';
     
     setInterval( 'getMessages()', 500 );
  }
  function ShowSmilies() {
  var SmiliesWindow = window.open("<?php echo $site_config['SITEURL']?>/mysmilies.php", "Smilies","width=300,height=600,resizable=yes,scrollbars=yes,toolbar=no,location=no,directories=no,status=no");
}