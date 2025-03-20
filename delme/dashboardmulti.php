<?php
$protocol=(isset($_SERVER['HTTPS'])?($_SERVER['HTTPS']?'https':'http'):'http');
$myPath=dirname($_SERVER['SCRIPT_NAME']);
$baseurl="$protocol://{$_SERVER['HTTP_HOST']}$myPath/";
$getvalueUrl="{$baseurl}getvalue.php?key=";
$logUrl="{$baseurl}log.php?key=";

// OUTPUT
$title="Status Dashboard Multi";
include './views/firstSection.php';
?>
<p>Gestione di: 5 variabili di stato</p>
<div id="statuskeydiv">
            <p>Inserisci le chiavi per le variabili di stato (max 5):</p>
            <form id="statuskeyform" method="GET">
                Key1: <input id='key1' name='key1'><br>
                Key2: <input id='key2' name='key2'><br>
                Key3: <input id='key3' name='key3'><br>
                Key4: <input id='key4' name='key4'><br>
                Key5: <input id='key5' name='key5'><br>
                <input type="submit">
            </form>
</div>      
<div id="monitor">            
    <div id='k1key'>Stato per key=<span id='k1name'></span></div>
    <div id='k1sta'></div>
    <div id='k1data'>
    <p>Value=<span id='k1val'></span></p>
    <p>Ts=<span id='k1ts'></span></p>
    </div>
	<hr>
    <div id='k2key'>Stato per key=<span id='k2name'></span></div>
    <div id='k2sta'></div>
    <div id='k2data'>
    <p>Value=<span id='k2val'></span></p>
    <p>Ts=<span id='k2ts'></span></p>
    </div>
	<hr>
    <div id='k3key'>Stato per key=<span id='k3name'></span></div>
    <div id='k3sta'></div>
    <div id='k3data'>
    <p>Value=<span id='k3val'></span></p>
    <p>Ts=<span id='k3ts'></span></p>
    </div>
	<hr>
    <div id='k4key'>Stato per key=<span id='k4name'></span></div>
    <div id='k4sta'></div>
    <div id='k4data'>
    <p>Value=<span id='k4val'></span></p>
    <p>Ts=<span id='k4ts'></span></p>
    </div>
	<hr>
    <div id='k5key'>Stato per key=<span id='k5name'></span></div>
    <div id='k5sta'></div>
    <div id='k5data'>
    <p>Value=<span id='k5val'></span></p>
    <p>Ts=<span id='k5ts'></span></p>
    </div>
	<hr>
    <p><span id='ora'></span>  <span id='updating'>Updating . . .</span>  </p>
</div>            
<script>
var k1key='';
var k2key='';
var k3key='';
var k4key='';
var k5key='';
var interval=null;
var d;
var ora;
function refreshStart(){
    $("#updating").show();
    $.ajax("<?=$getvalueUrl?>"+k1key).done(refreshCallback1);
    $.ajax("<?=$getvalueUrl?>"+k2key).done(refreshCallback2);
    $.ajax("<?=$getvalueUrl?>"+k3key).done(refreshCallback3);
    $.ajax("<?=$getvalueUrl?>"+k4key).done(refreshCallback4);
    $.ajax("<?=$getvalueUrl?>"+k5key).done(refreshCallback5);
}

function refreshCallback1(data){
    if (data.status=='OK'){
        if (data.data){
            $("#k1data").show();
            $("#k1sta").hide();
            $("#k1val").html(data.data.value);
            $("#k1ts").html(data.data.ts);
        }
        else {
            $("#k1data").hide();
            $("#k1sta").show();
            $("#k1sta").html('<p>Key not found</p>');
        }
    }
    else {
            $("#k1data").hide();
            $("#k1sta").show();
			$("#k1sta").html('<p>Error in API call</p>');
    }    
    $("#updating").hide();
    d=new Date();
    ora=d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
    $("#ora").html(ora);  
}

function refreshCallback2(data){
    if (data.status=='OK'){
        if (data.data){
            $("#k2data").show();
            $("#k2sta").hide();
            $("#k2val").html(data.data.value);
            $("#k2ts").html(data.data.ts);
        }
        else {
            $("#k2data").hide();
            $("#k2sta").show();
            $("#k2sta").html('<p>Key not found</p>');
        }
    }
    else {
            $("#k2data").hide();
            $("#k2sta").show();
			$("#k2sta").html('<p>Error in API call</p>');
    }    
    $("#updating").hide();
    d=new Date();
    ora=d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
    $("#ora").html(ora);  
}

function refreshCallback3(data){
    if (data.status=='OK'){
        if (data.data){
            $("#k3data").show();
            $("#k3sta").hide();
            $("#k3val").html(data.data.value);
            $("#k3ts").html(data.data.ts);
        }
        else {
            $("#k3data").hide();
            $("#k3sta").show();
            $("#k3sta").html('<p>Key not found</p>');
        }
    }
    else {
            $("#k3data").hide();
            $("#k3sta").show();
			$("#k3sta").html('<p>Error in API call</p>');
    }    
    $("#updating").hide();
    d=new Date();
    ora=d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
    $("#ora").html(ora);  
}

function refreshCallback4(data){
    if (data.status=='OK'){
        if (data.data){
            $("#k4data").show();
            $("#k4sta").hide();
            $("#k4val").html(data.data.value);
            $("#k4ts").html(data.data.ts);
        }
        else {
            $("#k4data").hide();
            $("#k4sta").show();
            $("#k4sta").html('<p>Key not found</p>');
        }
    }
    else {
            $("#k4data").hide();
            $("#k4sta").show();
			$("#k4sta").html('<p>Error in API call</p>');
    }    
    $("#updating").hide();
    d=new Date();
    ora=d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
    $("#ora").html(ora);  
}

function refreshCallback5(data){
    if (data.status=='OK'){
        if (data.data){
            $("#k5data").show();
            $("#k5sta").hide();
            $("#k5val").html(data.data.value);
            $("#k5ts").html(data.data.ts);
        }
        else {
            $("#k5data").hide();
            $("#k5sta").show();
            $("#k5sta").html('<p>Key not found</p>');
        }
    }
    else {
            $("#k5data").hide();
            $("#k5sta").show();
			$("#k5sta").html('<p>Error in API call</p>');
    }    
    $("#updating").hide();
    d=new Date();
    ora=d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
    $("#ora").html(ora);  
}

function updateKey(){
    //status
    k1key=$("#key1").val();
    $("#k1key").html(k1key);
    k2key=$("#key2").val();
    $("#k2key").html(k2key);
    k3key=$("#key3").val();
    $("#k3key").html(k3key);
    k4key=$("#key4").val();
    $("#k4key").html(k4key);
    k5key=$("#key5").val();
    $("#k5key").html(k5key);
    if (interval){
        clearInterval(interval);
    }
    interval=setInterval(refreshStart, 1000);
}
//script
$("#monitor").hide();
$("#statuskeyform").submit(function(){
    updateKey();
   $("#monitor").show();
   return false;
});
</script>
<?php            
include './views/lastSection.php';