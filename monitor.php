<?php
$protocol=(isset($_SERVER['HTTPS'])?($_SERVER['HTTPS']?'https':'http'):'http');
$myPath=dirname($_SERVER['SCRIPT_NAME']);
$baseurl="$protocol://{$_SERVER['HTTP_HOST']}$myPath/";
$getvalueUrl="{$baseurl}get.php?key=";

// OUTPUT
$title="Monitor";
include './views/firstSection.php';
?>
<p>Visualizza ed aggiorna in tempo reale (ogni secondo) il valore associato ad una chiave.</p>
<div id="keydiv">
            <p>Inserisci la chiave da ricercare:</p>
            <form id="keyform" method="GET">
                Key: <input id='key' name='key'><br>
                <input type="submit">
            </form>
</div>            
<div id="monitor">            
    <h2>Stato per key=<span id='keyValue'></span></h2>
    <div id='status'></div>
    <div id='data'>
    <p>Value=<span id='valueValue'></span></p>
    <p>Ts=<span id='tsValue'></span></p>
    </div>
    <p><span id='ora'></span>  <span id='updating'>Updating . . .</span>  </p>
</div>            
<script>
var key='';
var interval=null;
var d;
var ora;
function refreshStart(){
    $("#updating").show();
    $.ajax("<?=$getvalueUrl?>"+key).done(refreshCallback);
}

function refreshCallback(data){
    if (data.status=='OK'){
        if (data.data){
            $("#data").show();
            $("#status").hide();
            $("#valueValue").html(data.data.value);
            $("#tsValue").html(data.data.ts);
        }
        else {
            $("#data").hide();
            $("#status").show();
            $("#status").html('<p>Key not found</p>');
        }
    }
    else {
        $("#data").hide();
        $("#status").show();
        $("#status").html('<p>Error in API call</p>');
    }    
    $("#updating").hide();
    d=new Date();
    ora=d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
    $("#ora").html(ora);  
}

function updateKey(){
    key=$("#key").val();
    $("#keyValue").html(key);
    if (interval){
        clearInterval(interval);
    }
    interval=setInterval(refreshStart, 1000);
}



//script
$("#monitor").hide();
$("#keyform").submit(function(){
    updateKey();
//   alert("ciao");
   $("#monitor").show();
   return false;
});


</script>
<?php            
include './views/lastSection.php';