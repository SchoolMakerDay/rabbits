<?php
$protocol=(isset($_SERVER['HTTPS'])?($_SERVER['HTTPS']?'https':'http'):'http');
$myPath=dirname($_SERVER['SCRIPT_NAME']);
$baseurl="$protocol://{$_SERVER['HTTP_HOST']}$myPath/";
$getUrl="{$baseurl}get.php?key=";
$getkeysUrl="{$baseurl}getkeys.php?keys=";
$setUrl="{$baseurl}set.php?key=";
$timeUrl="{$baseurl}time.php";

// OUTPUT
$title="Dashboard";
include './views/firstSection.php';
?>
<style>
    #newkeydiv{
        background: lightcyan;
        padding: 5px;
    }
    .keydiv{
        margin: 5px;
        padding: 5px;
        border: #333 solid thin;
    }
    .readonly{
        background: lightgray;
    }
</style>
<h3>Gestione multipla di chiavi</h3>
<p>
    Per la gestione di chiavi su broker inserire nel form qui sotto una o più chiavi di proprio interesse, tenendo presente che 
    per chiavi destinate a comando è necessario indicarle come editabili (senza spunta a ReadOnly), 
    mentre per chiavi destinate a stato è bene renderle non modificabili (spuntando ReadOnly)
</p>
<div id="newkeydiv">
            <p>Inserisci una chiave da aggiungere in dashboard:</p>
            <form id="newkeyform" method="GET">
                Chiave: <input id='newkey' name='newkey'> 
                <span id="rospan"> <input type="checkbox" id="newkeyro" name="newkeyro" > ReadOnly </span>
                <input type="submit" value="aggiungi">
            </form>
</div>      
<div id="dashboard">
    <!-- div id="sssdiv" class="keydiv">      
            <h3>Key <span id="sss" class="keyid">sss</span></h3>
            Value: <input id='sssvalue' name='sssvalue' value="" class="keyvalue"><br>
            Ts: <span id='keyts' class="keyts"></span><br>
            <input type="submit" value="aggiorna valore"> <button class="keydel">Elimina chiave</button>
    </div -->
</div>
<div>
    <div>Server time: <span id="time">hh:mm:ss</span> <span id="updating">updating . . .</span></div>
</div>
<script>
// variabili
var keys=[];
var interval=null;
// main script ----------------------    
console.log("Main start");
$("#newkeyform").submit(addNewKey);
//per comodità in sviluppo --
//addKeyDiv("idccmdon",false);
//addKeyDiv("idccmdtemp",false);
//addKeyDiv("idcstaon",true);
//addKeyDiv("idcstatemp",true);
//console.log(keys);
//per comodità in sviluppo --
$("#updating").hide();
interval=setInterval(eachsecond, 1000);
// main script end ------------------    

function addNewKey(){
    var key=$("#newkey").val();
    if ($("#"+key+"div").length){
        alert("Chiave già esistente, impossibile aggiungere.");
        return false;
    }
    var ro=$('#newkeyro').is(":checked");
    console.log("Add "+(ro?"readonly ":"")+"key : "+key);
    addKeyDiv(key,ro);
    return false;
}

function addKeyDiv(key,ro){
    $("#dashboard").append(generateKeyDiv(key));
    if(ro){
        $("#"+key+"value").attr("readonly", true);
        $("#"+key+"div").addClass("readonly");
        $("#updid"+key).hide();
    }
    keys.push(key);  
    $("#updid"+key).click(updateKey);
    $("#delid"+key).click(removeKey);
}

function generateKeyDiv(key){
  return '<div id="'+key+'div" class="keydiv">'+
          '<h3>Key <span id="'+key+'" class="keyid">'+key+'</span></h3>'+
          '<p>Value: <input id="'+key+'value" name="'+key+'value" value="" class="keyvalue"><br>'+
          'Ts: <span id="'+key+'ts" class="keyts"></span><br>'+
          '<button id="updid'+key+'" class="keyupd">aggiorna valore</button> <button id="delid'+key+'" class="keydel">Elimina chiave</button></p>'+
          '</div>';
}

function updateKey(){
    var key=$(this).attr("id").substring(5);
    console.log("update "+key);
    $.ajax("<?= $setUrl ?>"+key+"&value="+$("#"+key+"value").val()).done(setCallback);
}

function setCallback(data){
    if (data.status=='OK'){
            var all=data.data;
            var key=all.key;
            console.log(all);
            $("#"+key+"value").val(all.value);
            $("#"+key+"ts").html(all.ts);
    }
}

function removeKey(){
//    var key='';
    var key=$(this).attr("id").substring(5);
    if(confirm("Sei sicuro di rimuovere key "+key+" ?")){
        console.log("rimuovi "+key);
        $("#"+key+"div").remove();
        //rimuovi da keys
        var i = keys.indexOf(key);
        if (i>-1) { 
          keys.splice(i,1); 
        }
    }
    else {
        console.log("annulla rimuovi "+key);
    }
}

function eachsecond(){
    $("#updating").show();
    $.ajax("<?= $timeUrl ?>").done(timeCallback);
    $.ajax("<?= $getkeysUrl ?>"+JSON.stringify(keys)).done(refreshKeysCallback);
}

function timeCallback(data){
    if (data.status=='OK'){
            var t=data.data;
            var time=t.hour+':'+t.minute+':'+t.second;
            $("#time").html(time);
    }
    $("#updating").hide();
}

function refreshKeysCallback(data){
    if (data.status=='OK'){
            var all=data.data;
//            console.log(all);
            for (i=0;i<keys.length;i++){
                if (!$("#"+keys[i]+"value").is(":focus")){                    
                    $("#"+keys[i]+"value").val(all[keys[i]].value);
                    $("#"+keys[i]+"ts").html(all[keys[i]].ts);
                }
            }
    }
}
</script>
<h2 class="main"><a href="index.php">Documentazione API</a></h2>
<?php            
include './views/lastSection.php';