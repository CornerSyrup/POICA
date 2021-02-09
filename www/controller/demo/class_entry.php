<?php

header('Content-Type: application/json');

$response=[];

switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
    case 'GET':
        $response['status'] = 1;
        $response['list'] = json_decode(file_get_contents('class.json'));
        break;
    case 'POST':
        if (isset($_REQUEST['c'])) {
            
        } else {
        }
    default:
        # code...
        break;
}

echo json_encode($response);
