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
		$username = $_POST['username'];
		$query = 'SELECT p.post_subject, p.post_id FROM posts p, alpha_users a WHERE a.username="' . $username . '" AND p.uid=a.uid;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$post_list = array();
			while($row = mysqli_fetch_assoc($result)){
				array_push($post_list, array('post_subject'=>$row['post_subject'], 'post_id'=>$row['post_id']));
			}
			$str = json_encode($post_list);
			echo($str);
		}
		elseif(mysqli_num_rows($result) == 0){
			echo('no posts');
		}
		else{
			echo('database error');
		}
	}
	main();
	mysqli_close($conn);
?>
