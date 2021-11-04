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
    <title>Friends</title>
</head>
<body>
    <div id="banner">
        <form method="post">
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
        <ul id="tabs">
        <li><a href=" <?php echo('../' . $_SESSION['username'] . '/' .$_SESSION['username'] . '.php'); ?>">Home</a></li>
        <li><a href=" <?php echo('../' . $_SESSION['username'] . '/chatpage.php'); ?>">Messages</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/favoritespage.php'); ?>">My Favorites</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/myrecipepage.php'); ?>">My Recipes</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/followingpage.php'); ?>">Following</a></li>
        <li><a href="<?php echo('../' . $_SESSION['username'] . '/searchpage.php'); ?>">Search</a></li>
        </ul>
    </div><br>
    <div class="tabs">
        <div class="tabOptions">
            <button class="selection" data-for-tab="1">Friends</button>
            <button class="selection" data-for-tab="2">Friend Requests</button>
            <button class="selection" data-for-tab="3">Following</button>
        </div>
        <div class="tabContent" id="friendsTab" data-tab="1">
        <h1>Friends</h1>
        These are your friends
        </div>
        <div class="tabContent" id="friendRequestTab" data-tab="2">
        <h1>Active Friend Requests</h1>
        There are your pending friend requests
        </div>
        <div class="tabContent" id="followingTab" data-tab="3">
        <h1>Following</h1>
        These are the people you follow
        </div>
    </div>
    <script type="text/javascript" src="../friends.js"></script>
</body>
</html>