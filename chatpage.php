<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../styles.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
</head>
<body onload="updateListen()">
    <h4>Search User</h4>
    <div class="searchUsers" id="searchUsers">
        <textarea class="usernameSearch" id="usernameSearch"></textarea>
    </div>
    <h1 id="chatHeader">Chat</h1>
    <div class="chatWindow" id="chatWindow">
    </div>
    <div class="sendMessage" id="sendMessage">
        <textarea class="message" id="message"></textarea>
        <button class="sendButton" id="sendButton">Send</button>
    </div>
    <script type="text/javascript" src="../chat.js"></script>
</body>
</html>