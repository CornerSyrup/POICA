<?php

session_status();
session_regenerate_id();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Welcome, <?= $_SESSION['user'] ?></h1>
    <button>Register Suica</button>
</body>

</html>