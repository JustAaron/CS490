<?php
	/*
	Functionality: Insert into Posts table and create 
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
		copy('postpage.php', $post_username . '/' . $max_post_id . '.php');
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
			echo(mysqli_error($conn));
		}
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
		insertDatabase($max_post_id+1);
		writeFile($max_post_id+1);
		echo('<br><a href="' . $_SESSION['username'] . '/' . strval($max_post_id+1) . '.php">Created page</a>');
	}
	main();
	mysqli_close($conn);
	return;
?>