<?php
/**
    @date 30 01 2025
    @author Duilio Peroni
    @copyright https://creativecommons.org/licenses/by-sa/4.0/
	use: https://www.schoolmakerday.it/rabbits/getkeyslogs.php?keys=["K1","K2",...]&logs=["K1","K2",...]
*/
use lib\Autoload;
use models\Log;
require_once 'lib/Autoload.php';
Autoload::autoload();
$lasts=$_GET['lasts'] ?? null;
$logs=$_GET['logs'] ?? null;
if (($lasts!==null)&&($logs!==null)){
	$lastsArr=json_decode($lasts,true);
	if (json_last_error()==JSON_ERROR_NONE) {
            $v=[];
            foreach($lastsArr as $k) {
                    $v[$k]=Log::getLastKey($k);
            }		  
	}
	else {
            $response=[
                    'status'=>'ERROR',
            ];
	}
	$logsArr=json_decode($logs,true);	
	if (json_last_error()==JSON_ERROR_NONE) {
			$log=[];
			foreach($logsArr as $l) {
						$log[$l]=Log::getArrayAllKey($l);
			}	
			$response=[
					'status'=>'OK',
					'lasts'=> $v,
					'logs'=> $log,
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