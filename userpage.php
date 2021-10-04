<?php
    session_start();
?>
<?php
    require('session_check.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Userpage</title>
</head>
<body>
    <div id="banner">
            <a href="messages.php"><button id="MessageButton">Messages</button></a>
            <a href="searchpage.html"><button id="SearchButton" type="button">Search</button></a>
    </div>
    <div id="pageHeader">
        <h1>Username</h1>
    </div>
</body>
</html>