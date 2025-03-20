<?php
use lib\Autoload;
use models\Log;
require_once 'lib/Autoload.php';
Autoload::autoload();
$json=$_GET['json'] ?? null;
if ($json!==null){
	$vars=json_decode($json,true);
	if (json_last_error()==JSON_ERROR_NONE) {
		foreach($vars as $kn => $k) {
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
echo json_encode($response);