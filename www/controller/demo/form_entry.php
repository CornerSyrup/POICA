<?php

header('Content-Type: application/json');

$forms = @json_decode(file_get_contents('forms.json'));

switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
    case 'GET':
        // get one
        if (isset($_REQUEST['e'])) {
            echo json_encode(
                [
                    'typ' => $forms[$_REQUEST['e']]->typ,
                    'frm' => $forms[$_REQUEST['e']]->frm
                ]
            );
        }
        // get list
        else {
            $data = [];

            for ($i = 0; $i < count($forms); $i++) {
                $data[] = [
                    'id' => $i,
                    'type' => $forms[$i]->typ,
                    'status' => $forms[$i]->status,
                    'date' => $forms[$i]->date,
                ];
            }

            echo json_encode(['status' => 1, 'cat' => $data]);
        }
        break;
    case 'POST':
        $post = json_decode(file_get_contents('php://input'), true);
        $post['status'] = '00';
        $post['date'] = date('Y-m-d');
        $forms[] = $post;
        file_put_contents('forms.json', json_encode($forms));
        echo json_encode(['status' => 1]);
        break;
    default:
        echo json_encode(['status' => 33]);
}
