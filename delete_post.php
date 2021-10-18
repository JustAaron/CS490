<?php
	/*
	Functionality: Deletes from posts table using given information. Should be used whenever an admin wants to block (delete) a post.
	Input:
	In $_POST[], "postid" should be the post_id of the to-be deleted post.
	Output:
	If post is successfully deleted: Does not echo anything. The post with the matching post_id will be removed from the database. 
	If post failed to delete: echo("error while deleting post")
	*/
	session_start();
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('display_errors', 1);
	require('account.php');
	
	$conn = mysqli_connect($hostname, $username, $password, $project);
	if(mysqli_connect_errno()){
		exit();
	}
	
	function main(){
		global $conn;
		$postid = $_POST['postid'];
		$query = 'DELETE FROM posts WHERE post_id="' . $postid . '";';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('error while deleting post');
		}
		mysqli_free_result($result);
	}
	
	main();
	mysqli_close();
?>
