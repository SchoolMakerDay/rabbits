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
$keys=$_GET['keys'] ?? null;
if ($keys!==null){
	$keyArr=json_decode($keys,true);
	if (json_last_error()==JSON_ERROR_NONE) {
            $v=[];
            foreach($keyArr as $k) {
                    $v[$k]=Log::getLastKey($k);
            }		
            $response=[
                    'status'=>'OK',
                    'data'=> $v,
            ];    
	}
	else {
            $response=[
                    'status'=>'ERROR',
            ];
	}	
}
else {
    $response=[
        'status'=>'ERROR',
    ];
}
header("Content-Type: application/json;charset=utf-8");
header("Access-Control-Allow-Origin: *");
echo json_encode($response);