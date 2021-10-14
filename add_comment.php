<?php
	/*
	Functionality: adds comment to database using given information. 
	Input: 
	In $_POST[], "post_id" should be the post_id of the parent post. Example: For ~/George/1.php, the "post_id" should be 1. 
	In $_POST[], "comment" should be the comment to be created. 
	Output:
	If the comment was inserted successfully: nothing will be echoed. 
	If the comment failed due to a database error: echo "database error". 
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
	
	function getMaxCommentID(){
		global $conn;
		$query = 'SELECT MAX(comment_id) as max_comment_id FROM Comments;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$max = $row['max_comment_id'];
			return $max;
		}
		return -1;
	}
	
	function insertQuery($max_comment_id){
		global $conn;
		$parent_post_id = $_POST['post_id'];
		$comment_uid = $_SESSION['uid'];
		$comment = $_POST['comment'];
		$query = 'INSERT INTO Comments (comment_id, post_id, uid, comment) VALUES (' . $max_comment_id . ',' . $parent_post_id . ',' . $comment_uid . ',"' . $comment . '");';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return;
		}
		//echo('success');
	}
	
	function main(){
		global $conn;
		$max_comment_id = getMaxCommentID();
		if($max_comment_id == -1){
			echo('database error');
			return;
		}
		insertQuery($max_comment_id+1);
	}
	main();
	mysqli_close($conn);
?>
