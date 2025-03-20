/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

var mybaseurl="https://www.schoolmakerday.it/broker/";

function smdBrokerTime(callback){
    $.ajax(mybaseurl+"time.php").done(callback);
}

function smdBrokerSet(key,value,callback){
	$.ajax(mybaseurl+"set.php?key="+key+"&value="+value).done(callback);
}

function smdBrokerGetKeys(keys,callback){
    $.ajax(mybaseurl+"getkeys.php?keys="+JSON.stringify(keys)).done(callback);
}

