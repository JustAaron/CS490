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
    <title>Search Page</title>
</head>
<body>
    <div id="banner">
        <a href=" <?php echo('../' . $_SESSION['username'] . '/' .$_SESSION['username'] . '.php'); ?>"><button id="HomeButton">Home</button></a>
        <a href=" <?php echo('../' . $_SESSION['username'] . '/chatpage.php'); ?>"><button id="MessageButton">Messages</button></a>
        <form method="post">
            <input type="submit" name="LogoutButton" id="LogoutButton" value="Logout" /><br/>
        </form>
    </div><br>
    <h1>Search Page</h1>
    <form id="searchOptions">
        <table>
            <tr id="radioButtons">
                <td><input type="radio" id="postRadio" name="searchRadio"><br></td>
                <td><label for="postRadio">Posts</label></td>
                <td><input type="radio" id="userRadio" name="searchRadio"></td>
                <td><label for="userRadio">Users</label></td>
            </tr>
            <tr id="search">
                <td><input type="text" id="searchField" name="searchField"></td>
                <td><button type="submit" class="searchButton">Search</button></td>
            </tr>
        </table>
    </form>
    <div id=results></div>
    <script type="text/javascript" src="../search.js"></script>
</body>
</html>
