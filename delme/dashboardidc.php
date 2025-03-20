<?php
$protocol=(isset($_SERVER['HTTPS'])?($_SERVER['HTTPS']?'https':'http'):'http');
$myPath=dirname($_SERVER['SCRIPT_NAME']);
$baseurl="$protocol://{$_SERVER['HTTP_HOST']}$myPath/";
$getvalueUrl="{$baseurl}getvaluemulti.php?json=";
$logUrl="{$baseurl}set.php?key=";

// OUTPUT
$title="Dashboard IdC";
include './views/firstSection.php';
?>
<p>Gestione comandi e stati per IdC</p>
<div id="keydiv">
            <p>Inserisci le chiavi per le variabili di comando/stato:</p>
            <form id="keyform" method="GET">
                Chiave comando on/off/auto: <input id='cmdonkeyin' name='cmdonkeyin'><br>
                Chiave comando temperatura (per auto): <input id='cmdtempkeyin' name='cmdtempkeyin'><br>
                Chiave stato on/off: <input id='staonkeyin' name='staonkeyin'><br>
                Chiave stato temperatura: <input id='statempkeyin' name='statempkeyin'><br>
                <input type="submit">
            </form>
</div>      
<div id="dashboard">
<div id="cmdondiv">      
    <form id="cmdonform" method="get" class="cmdform">
        <h2>Comando per key=<span id='cmdonkey' class="cmdkey"></span></h2>
        Value: <input id='cmdonvalue' name='cmdonvalue' value="" class="cmdvalue"><br>
        Ts: <span id='cmdonts' class="cmdts"></span><br>
        <input type="submit">
    </form>
</div>
<div id="cmdtempdiv">      
    <form id="cmdtempform" method="get" class="cmdform">
        <h2>Comando per key=<span id='cmdtempkey' class="cmdkey"></span></h2>
        Value: <input id='cmdtempvalue' name='cmdtempvalue' value="" class="cmdvalue"><br>
        Ts: <span id='cmdtempts' class="cmdts"></span><br>
        <input type="submit">
    </form>
</div>
<div id="staondiv"> 
    <h2>Stato per key=<span id='staonkey'></span></h2>
    <div id='staonstatus'></div>
    <div id='staondata'>
    <p>Value=<span id='staonvalue'></span></p>
    <p>Ts=<span id='staonts'></span></p>
    </div>
</div>
<div id="statempdiv"> 
    <h2>Stato per key=<span id='statempkey'></span></h2>
    <div id='statempstatus'></div>
    <div id='statempdata'>
    <p>Value=<span id='statempvalue'></span></p>
    <p>Ts=<span id='statempts'></span></p>
    </div>
</div>
<div>
    <p><span id='ora'></span>  <span id='updating'>Updating . . .</span>  </p>
</div>            
    </div>
<script>
var cmdonkey='';
var cmdtempkey='';
var staonkey='';
var statonkey='';

var refreshOnCmdEnable=true;
var refreshTempCmdEnable=true;

var interval=null;
var d;
var ora;

function refreshStart(){
//    console.log("refreshStart");
    $("#updating").show();
	var parm='{"k1":"'+staonkey+'","k2":"'+statempkey+'","k3":"'+cmdonkey+'","k4":"'+cmdtempkey+'"}';
    $.ajax("<?=$getvalueUrl?>"+parm).done(refreshCallback);
}

function refresh(which,sta){
    if (sta){
//        console.log(sta)
        thiskey=sta.key;
        $("#"+which+"data").show();
        $("#"+which+"status").hide();
        $("#"+which+"value").html(sta.value);
        $("#"+which+"ts").html(sta.ts);
    }
    else{
        keyNotFound(which);
    }
}

function keyNotFound(which){
//    console.log("KeyNotFound:"+which)
    $("#"+which+"data").hide();
    $("#"+which+"status").show();
    $("#"+which+"status").html('<p>Key not found</p>');
 }
 
 function callError(which){
     $("#"+which+"data").hide();
    $("#"+which+"status").show();
    $("#"+which+"sta").html('<p>Error in API call</p>');
}

function refreshCallback(data){
//    console.log("refresCallback");
    if (data.status=='OK'){
        if (data.data){
            refresh("staon",data.data[staonkey]);
            refresh("statemp",data.data[statempkey]);
            if (refreshOnCmdEnable){
//                console.log(data.data[cmdonkey].ts)
                $("#cmdonvalue").val(data.data[cmdonkey].value);
                $("#cmdonts").html(data.data[cmdonkey].ts);
            }
            if (refreshTempCmdEnable){ 
                $("#cmdtempvalue").val(data.data[cmdtempkey].value);
                $("#cmdtempts").html(data.data[cmdtempkey].ts);
            }
        }
        else {
            keyNotFound("staon");
            keyNotFound("statemp");
        }
    }
    else {
        callError("staon");
        callError("statemp");
    } 
    $("#updating").hide();
    d=new Date();
    ora=d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
    $("#ora").html(ora);  	
}

function cmdCallback(data){
    if (data.status=='OK'){
            mykey=data.data.key;
            $("form."+mykey+" input.cmdvalue").val(data.data.value);
            $("form."+mykey+" span.cmdts").html(data.data.ts);
//            refresh("staon",data.data[staonkey]);
//            refresh("statemp",data.data[statempkey]);
    }
}

function cmdSend(){
    formid=this.id;
    console.log($("#"+formid+" .cmdkey").html());
    console.log($("#"+formid+" .cmdvalue").val());
    keysel="#"+formid+" .cmdkey";
    valsel="#"+formid+" .cmdvalue";
    calladdr="<?=$logUrl?>"+$(keysel).html()+"&value="+$(valsel).val();
    $.ajax(calladdr).done(cmdCallback);
    $(valsel).val('sending. . .');
    return false;
}

function updateKey(){
//    console.log("updatekey");
    //status
    cmdonkey=$("#cmdonkeyin").val();
    $("#cmdonkey").html(cmdonkey);
    $("#cmdonform").addClass(cmdonkey);
    cmdtempkey=$("#cmdtempkeyin").val();
    $("#cmdtempkey").html(cmdtempkey);
    $("#cmdtempform").addClass(cmdtempkey);
    staonkey=$("#staonkeyin").val();
    $("#staonkey").html(staonkey);
    statempkey=$("#statempkeyin").val();
    $("#statempkey").html(statempkey);
    if (interval){
        clearInterval(interval);
    }
    interval=setInterval(refreshStart, 1000);
}
//script
$("#cmdonvalue").focusin(function(){refreshOnCmdEnable=false});
$("#cmdonvalue").focusout(function(){refreshOnCmdEnable=true});
$("#cmdtempvalue").focusin(function(){refreshTempCmdEnable=false});
$("#cmdtempvalue").focusout(function(){refreshTempCmdEnable=true});
$("#dashboard").hide();
$(".cmdform").submit(cmdSend)
$("#keyform").submit(function(){
    updateKey();
   $("#dashboard").show();
   return false;
});
</script>
<?php            
include './views/lastSection.php';