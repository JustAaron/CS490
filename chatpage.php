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
<body onload="getFriends()">
    <div id="banner">
        <form method="post">
          <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
        <ul id="tabs">
        <li><a href=" <?php echo('../' . $_SESSION['username'] . '/' .$_SESSION['username'] . '.php'); ?>">Home</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/favoritespage.php'); ?>">My Favorites</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/myrecipepage.php'); ?>">My Recipes</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/followingpage.php'); ?>">Following</a></li>
		<li><a href="<?php echo('../' . $_SESSION['username'] . '/friendspage.php'); ?>">Friends</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/searchpage.php'); ?>">Search</a></li>
        </ul>
    </div><br><br>
    <div class="chatElements">
        <div class="userlist" id="userlist">
            <h2>Friends</h2>
        </div>
        <div class="chatting">
            <div class="chatWindow" id="chatWindow">
            <h2 id="chatHeader">Chat</h2>
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