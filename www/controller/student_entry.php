<?php

$std = json_decode(file_get_contents('students.json'));

foreach ($std as $s) {
    $s->suica = hash('sha256', $s->suica);
}

echo json_encode($std);