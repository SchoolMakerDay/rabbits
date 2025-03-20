<?php
/**
    @date 09 02 2023
    @author Giovanni Ragno
    @copyright https://creativecommons.org/licenses/by-sa/4.0/
*/

use lib\Autoload;
use models\Log;

require_once 'lib/Autoload.php';
Autoload::autoload();

$key=$_GET['key'] ?? null;
$value=$_GET['value'] ?? null;

if ($key!==null && $value!==null){
    $log=new Log($key, $value);
    $log->insert();

    //per verifica ricerco e restituisco i dati.    
    $l=Log::getLastKey($key);
    $response=[
        'status'=>'OK',
        'data'=> $l,
    ]; 
}
else {
    $response=[
        'status'=>'ERROR',
    ];
}
//output
header("Content-Type: application/json;charset=utf-8");
header("Access-Control-Allow-Origin: *");
echo json_encode($response);