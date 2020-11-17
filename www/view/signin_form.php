<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
</head>

<style>
    form * {
        display: block;
    }
</style>

<body>
    <form action="/signin/" method="POST">
        <label for="sid">Student ID:</label>
        <input type="text" name="sid" id="sid">
        <label for="pwd">Password:</label>
        <input type="password" name="pwd" id="pwd">
        <input type="submit" value="sign in">
    </form>
    <?= $errmsg ?>
</body>

</html>