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
    <title>Post</title>
</head>
<body onload="displayPost()">
    <div id="banner">
        <a href=" <?php echo('../' . $_SESSION['username'] . '/' .$_SESSION['username'] . '.php'); ?>"><button id="HomeButton">Home</button></a>
        <a href=" <?php echo('../' . $_SESSION['username'] . '/chatpage.php'); ?>"><button id="MessageButton">Messages</button></a>
        <a href="<?php echo('../' . $_SESSION['username'] . '/searchpage.php'); ?>"><button id="SearchButton" type="button">Search</button></a>
        <form method="post">
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
    </div><br>
    <h1>Post</h1>
    <div class="postComments">
        <div class="post" id="post">
        </div>
        <div class="comments" id="comments">
            <h5>Comments</h5>
        </div>
    </div>
    <div class="addComment">
        <textarea class="writeComment" id="writeComment"></textarea>
        <button class="postComment" id="postComment">Send</button>
    </div>
    <script type="text/javascript" src="../post.js"></script>
</body>
</html>