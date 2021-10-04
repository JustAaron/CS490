<?php
    session_start();
?>
<?php
    require('session_check.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
</head>
<body>
    <h1>Messages</h1>
    <div class="searchUsers" id="searchUsers">
        <textarea class="usernameSearch" id="usernameSearch"></textarea>
        <button class="searchButton">Search</button>
    </div>
</body>
</html>
