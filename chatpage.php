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
    <title>Chat</title>
</head>
<body>
    <script src="chat.js"></script>
    <h4>Search User</h4>
    <div class="searchUsers" id="searchUsers">
        <textarea class="usernameSearch" id="usernameSearch"></textarea>
        <button class="searchButton" onclick="updateListen()">Search</button>
    </div>
    <h1 id="chatHeader">Chat</h1>
    <div class="chatWindow" id="chatWindow">
        <!--
        <div class="incomingMessage">
            <div class="othersMessage">Hi, how are you?</div>
            <div class="spacing"></div>
        </div>
        <div class="outgoingMessage">
            <div class="myMessage">I'm good, thanks!</div>
            <div class="spacing"></div>
        </div>
        <div class="outgoingMessage">
            <div class="myMessage">How about you?</div>
            <div class="spacing"></div>
        </div>-->
    </div>
    <div class="sendMessage" id="sendMessage">
        <textarea class="message" id="message"></textarea>
        <button class="sendButton" onclick="updateClient()">Send</button>
    </div>
    
</body>
</html>
