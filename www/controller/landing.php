<?php

session_start();
session_regenerate_id();

if (!$_SESSION['user'] || !$_SESSION['log_in']) {
    header("Location: http://{$_SERVER['HTTP_HOST']}/signin/");
}
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
    <a href="/setting/">Setting</a>
    <a href="/signout/">Sign Out</a>
</body>

</html>