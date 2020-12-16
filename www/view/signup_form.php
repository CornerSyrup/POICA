<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
</head>

<style>
    form * {
        display: block;
    }
</style>

<body>
    <form action="/signup/" method="post">
        <label for="sid">Student ID:</label>
        <input type="number" name="sid" id="sid">
        <label for="pwd">Password:</label>
        <input type="password" name="pwd" id="pwd" autocomplete="new-password">
        <label for="jfn">Japanese First Name</label>
        <input type="text" name="jfn" id="jfn" autocomplete="given-name">
        <label for="jln">Japanese Last Name</label>
        <input type="text" name="jln" id="jln" autocomplete="family-name">
        <label for="jfk">Japanese First name Kana</label>
        <input type="text" name="jfk" id="jfk">
        <label for="jlk">Japanese Last name Kana</label>
        <input type="text" name="jlk" id="jlk">
    </form>
</body>

</html>