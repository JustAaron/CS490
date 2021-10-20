<?php
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
		$query = 'SELECT p.post_subject, p.post_body, p.image_path, a.username FROM posts p, alpha_users a WHERE p.post_id=' . $post_id . ' AND p.uid=a.uid;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$post_list = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($post_list, array('post_subject'=>$row['post_subject'], 'post_body'=>$row['post_body'], 'username'=>$row['username'], 'image_path'=>$row['image_path']));
			}
			$str = json_encode($post_list);
			echo($str);
		}
		elseif(mysqli_num_rows($result) == 0){
			echo('no posts found');
		}
		else{
			echo('database error');
		}
	}
	main();
	mysqli_close($conn);
?>
