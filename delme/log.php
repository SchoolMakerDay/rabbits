<?php

use lib\Autoload;
use models\Log;

require_once 'lib/Autoload.php';
Autoload::autoload();

$key=$_GET['key'] ?? null;
$value=$_GET['value'] ?? null;

if ($key!==null && $value!==null){
    $log=new Log($key, $value);
    $log->insert();

    $response=[
        'status'=>'OK',
    ];
}
else {
    $response=[
        'status'=>'ERROR',
    ];
}
//output
header("Content-Type: application/json;charset=utf-8");
echo json_encode($response);