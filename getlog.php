<?php

use lib\Autoload;
use models\Log;

require_once 'lib/Autoload.php';
Autoload::autoload();

$key=$_GET['key'] ?? null;

if ($key===null){
    //chiave assente: errore
    $response=[
        'status'=>'ERROR',
    ];
}
else {
    //chiave presente: ricerco e restituisco i dati.    
    //$l=Log::getLastKey($key);
    $l=Log::getArrayAllKey($key);
    $response=[
        'status'=>'OK',
        'data'=> $l,
    ];    
}
//output
header("Content-Type: application/json;charset=utf-8");
echo json_encode($response);
