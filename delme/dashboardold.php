<?php
$protocol=(isset($_SERVER['HTTPS'])?($_SERVER['HTTPS']?'https':'http'):'http');
$myPath=dirname($_SERVER['SCRIPT_NAME']);
$baseurl="$protocol://{$_SERVER['HTTP_HOST']}$myPath/";
$getvalueUrl="{$baseurl}getvalue.php?key=";
$logUrl="{$baseurl}log.php?key=";

// OUTPUT
$title="Dashboard";
include './views/firstSection.php';
?>
<p>Gestione di:</p>
<ul>
    <li>coppia chiave-valore modificabile con questa interfaccia (esprime un comando verso il dispositivo IOT</li>
    <li>coppia chiave-valore read-only in questa interfaccia (esprime uno stato ricevuto dal dispositivo IOT</li>
</ul>
<div id="statuskeydiv">
            <p>Inserisci le chiavi per comando e stato:</p>
            <form id="statuskeyform" method="GET">
                CommandKey: <input id='cmdkey' name='cmdkey'><br>
                StatusKey: <input id='statuskey' name='statuskey'><br>
                <input type="submit">
            </form>
</div>      
<div id="command">      
    <h2>Comando per key=<span id='cmdkeyValue'></span></h2>
    <form id="cmdform" method="GET">
        Value: <input id='cmdvalue' name='cmdvalue' value=""><br>
        Ts: <span id='cmdtsValue'></span><br>
        <!--<input type='hidden' name='key' value="">-->
        <input type="submit">
    </form>
</div>
<div id="monitor">            
    <h2>Stato per key=<span id='statuskeyValue'></span></h2>
    <div id='statusstatus'></div>
    <div id='statusdata'>
    <p>Value=<span id='statusvalueValue'></span></p>
    <p>Ts=<span id='statustsValue'></span></p>
    </div>
    <p><span id='ora'></span>  <span id='updating'>Updating . . .</span>  </p>
</div>            
<script>
var statusKey='';
var cmdKey='';
var interval=null;
var d;
var ora;
function refreshStart(){
    $("#updating").show();
    $.ajax("<?=$getvalueUrl?>"+statusKey).done(refreshCallback);
}

function refreshCallback(data){
    if (data.status=='OK'){
        if (data.data){
            $("#statusdata").show();
            $("#statusstatus").hide();
            $("#statusvalueValue").html(data.data.value);
            $("#statustsValue").html(data.data.ts);
        }
        else {
            $("#statusdata").hide();
            $("#statusstatus").show();
            $("#statusstatus").html('<p>Key not found</p>');
        }
    }
    else {
        $("#statusdata").hide();
        $("#statusstatus").show();
        $("#statusstatus").html('<p>Error in API call</p>');
    }    
    $("#updating").hide();
    d=new Date();
    ora=d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
    $("#ora").html(ora);  
}

function cmdCallback(data){
    if (data.status=='OK'){
        if (data.data){
//            $("#statusdata").show();
//            $("#statusstatus").hide();
            $("#cmdvalue").val(data.data.value);
            $("#cmdtsValue").html(data.data.ts);
        }
        else {
//            $("#statusdata").hide();
//            $("#statusstatus").show();
//            $("#statusstatus").html('<p>Key not found</p>');
        }
    }
    else {
//        $("#statusdata").hide();
//        $("#statusstatus").show();
//        $("#statusstatus").html('<p>Error in API call</p>');
    }    
//    $("#updating").hide();
//    d=new Date();
//    ora=d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
//    $("#ora").html(ora);  
}

function logCallback(data){
    if (data.status=='OK'){
        $.ajax("<?=$getvalueUrl?>"+cmdKey).done(cmdCallback);
    }
}

function updateKey(){
    //status
    statusKey=$("#statuskey").val();
    $("#statuskeyValue").html(statusKey);
    if (interval){
        clearInterval(interval);
    }
    interval=setInterval(refreshStart, 1000);
    //cmd
    cmdKey=$("#cmdkey").val();
    $("#cmdkeyValue").html(cmdKey);
    $.ajax("<?=$getvalueUrl?>"+cmdKey).done(cmdCallback);
}

function cmdSend(){
    $.ajax("<?=$logUrl?>"+cmdKey+"&value="+$("#cmdvalue").val()).done(logCallback);
    $("#cmdvalue").val('sending. . .');
    return false;
}

//script
$("#monitor").hide();
$("#command").hide();
$("#cmdform").submit(cmdSend)
$("#statuskeyform").submit(function(){
    updateKey();
//   alert("ciao");
   $("#monitor").show();
   $("#command").show();
   return false;
});
//updateKey();
////   alert("ciao");
// $("#monitor").show();


</script>
<?php            
include './views/lastSection.php';