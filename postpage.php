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
    <title>Post</title>
</head>
<body onload="displayPost()">
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
    <script type="text/javascript" src="post.js"></script>
</body>
</html>