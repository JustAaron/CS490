<?php
    session_start();

    function returnToLogin(){
        session_unset();
        session_destroy();
        header("refresh:0, url=../login.html");
        exit();
    }
    if(array_key_exists('LogoutButton',$_POST)){
        returntoLogin();
    }
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
<body onload="loadUsers()">
    <div id="banner">
        <a href=" <?php echo('../' . $_SESSION['username'] . '/' .$_SESSION['username'] . '.php'); ?>"><button id="HomeButton">Home</button></a>
        <a href="<?php echo('../' . $_SESSION['username'] . '/searchpage.php'); ?>"><button id="SearchButton" type="button">Search</button></a>
        <form method="post">
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
    </div>
    <h2 id="chatHeader">Chat</h2>
    <div class="chatElements">
        <div class="userlist" id="userlist">
            <h1>Users</h1>
        </div>
        <div class="chatting">
            <div class="chatWindow" id="chatWindow">
            </div>
            <div class="sendMessage" id="sendMessage">
                <textarea class="message" id="message"></textarea>
                <button class="sendButton" id="sendButton">Send</button>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="../chat.js"></script>
</body>
</html>