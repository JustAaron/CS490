<?php
	/*
	Functionality: Insert into Posts table and create the post file using given information. Should be used when a user creates a post. 
	Input: 
	In $_POST[], "post_subject" should be the subject line, "post_text" should be the body of the post.
	In $_SESSION[], "username" should be the username of the poster, "uid" should be the uid of the poster.
	Output: 
	If the database insert and copying file was successful: echo "success"
	If the database insert failed: echo "database error"
	If the database insert succeeded but copying file failed: echo "copy file error"
	*/
	session_start();
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require('account.php');
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		//echo "<p>Failed to connect to MySQL: " . mysqli_connect_error(). "</p>";
		exit();
	}
	
	function writeFile($max_post_id){
		global $conn;
		$post_username = $_SESSION['username'];
		if(!copy('postpage.php', $post_username . '/' . $max_post_id . '.php')){
			echo('copy file error');
			return;
		}
	}
	
	function insertDatabase($max_post_id){
		global $conn;
		$post_subject = $_POST['post_subject'];
		$post_text = $_POST['post_text'];
		$post_uid = $_SESSION['uid'];
		//echo("<br>Successfully got values for SQL statement<br>");

		$add_post = 'insert into posts(post_id, post_subject, post_body, uid) values (' . $max_post_id . ',"' . $post_subject . '","' . $post_text . '",' . $post_uid . ');';
		$result = mysqli_query($conn, $add_post);
		if(!$result){
			echo('database error');
			return false;
		}
		return true;
		//echo("<br> Successfully added post.");
	}
	
	function getMaxPostID(){
		global $conn;
		$query = 'SELECT MAX(post_id) as max_pid FROM posts;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			if($row['max_pid'] == null){
				return 0;
			}
			$max = $row['max_pid'];
			return $max;
		}
		return -1;  // error value
	}

	function main(){
		$max_post_id = getMaxPostID();
		if($max_post_id == -1){
			echo('database error');
			return;
		}
		//echo($max_post_id+1);
		if(insertDatabase($max_post_id+1)){  // only write file if successfully inserted
			writeFile($max_post_id+1);
		}
		//echo('<br><a href="' . $_SESSION['username'] . '/' . strval($max_post_id+1) . '.php">Created page</a>');
	}
	main();
	mysqli_close($conn);
?>