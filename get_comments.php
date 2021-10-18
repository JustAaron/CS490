<?php
	/*
	Functionality: Echo the data from the comments table as a json-encoded string, using given post_id. Should be used whenever a post webpage should be updated. 
	Input:
	In $_POST[], "post_id" should be the post_id of interest. 
	Output: 
	If the comments were successfully fetched from a valid post_id with at least 1 comment: echo the json-encoded string. 
	Example:
	[{"username":"JohnA","comment":"this is a test comment on post_id=1 and uid=1"},{"username":"JohnA","comment":"test comment 2"},{"username":"JohnA","comment":"test comment 2"}] 
	where username is the user who posted the comment on the post, and comment is the comment that was posted.
	If the post_id was valid but the post has no comments: echo "no comments"
	If the post_id was invalid or there was another database error: echo "database error"
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
	
	function main(){
		global $conn;
		$post_id = $_POST['post_id'];
		$query = 'SELECT c.comment, a.username FROM Comments c, alpha_users a WHERE c.post_id=' . $post_id . ' AND c.uid=a.uid ORDER BY comment_id ASC;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$comment_list = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($comment_list, array('username'=>$row['username'], 'comment'=>$row['comment']));
			}
			$str = json_encode($comment_list);
			echo($str);
		}
		elseif(mysqli_num_rows($result) == 0){
			echo('no comments');
		}
		else{
			echo('database error');
		}
	}
	main();
	mysqli_close($conn);
?>
