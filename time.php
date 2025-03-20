<?php
/**
    @date 09 02 2023
    @author Giovanni Ragno
    @copyright https://creativecommons.org/licenses/by-sa/4.0/
*/

$time=explode('-',date('Y-m-d-H-i-s'));
$t=[
    'year'=>$time[0],
    'month'=>$time[1],
    'day'=>$time[2],
    'hour'=>$time[3],
    'minute'=>$time[4],
    'second'=>$time[5],
];

$response=[
    'status'=>'OK',
    'data'=>$t,
];

//output
header("Content-Type: application/json;charset=utf-8");
header("Access-Control-Allow-Origin: *");
echo json_encode($response);