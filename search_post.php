<?php
	/*
	Functionality: echoes a json-encoded array of posts that match the user-given search. 
	A match is defined by %str%, meaning that if the string appears anywhere in the post_subject, it will be returned.
	The string is not case-sensitive.
	Input:
	In $_POST[], "searchpost" should be the post subject to be searched.
	Output:
	If matches were found: echo a json-encoded string with n elements (n=number of matches). For each element, the keys are ("post_subject", "post_id", and "username") and the values are the corresponding values from the posts table and alpha_users table.
	If matches were not found: echo "no matches"
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
		$searchpost = $_POST['searchpost'];
		$query = 'SELECT p.*, a.username FROM posts p, alpha_users a WHERE p.post_subject LIKE "%' . $searchpost . '%" AND p.uid=a.uid ORDER BY p.post_id ASC;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$post_list = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($post_list, array('post_subject'=>$row['post_subject'], 'post_id'=>$row['post_id'], 'username'=>$row['username']));
			}
			$str = json_encode($post_list);
			echo($str);
		}
		else{
			echo('no matches');
		}
	}
	main();
	mysqli_close($conn);
	?>