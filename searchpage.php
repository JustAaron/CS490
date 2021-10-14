<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
</head>
<body>
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
    <script type="text/javascript" src="search.js"></script>
</body>
</html>
