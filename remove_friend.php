<?php
	/*
	Functionality: Removes a friend by deleting the row from the Friends table
	Input:
	In $_SESSION[], "uid" should be the uid of the logged-in user
	In $_POST[], "other" should be the username of the friend to be removed
	Output:
	If the friend is successfully removed: echo "successfully removed"
	If the "other" user was not found: echo "user not found"
	If there was a database error: echo "database error"
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

	function getUserID($username){
		global $conn;
		$query = 'SELECT uid FROM alpha_users WHERE username="' . $username . '";';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			return $row['uid'];
		}
		return -1;  // error value
	}
	
	function deleteDatabase($remover_uid, $removed_uid){
		global $conn;
		if($remover_uid < $removed_uid){
			$user1_id = $remover_uid;
			$user2_id = $removed_uid;
		}
		else{
			$user1_id = $removed_uid;
			$user2_id = $remover_uid;
		}
		$query = 'DELETE FROM Friends WHERE User1ID=' . $user1_id . ' AND User2ID=' . $user2_id . ';';
		$result = mysqli_query($conn, $query);
		if(!$result || mysqli_affected_rows($conn) == 0){
			echo('database error');
			return false;
		}
		return true;
	}

	function main(){
		$remover_uid = $_SESSION['uid'];
		$removed_username = $_POST['other'];
		$removed_uid = getUserID($removed_username);
		if($removed_uid == -1){
			echo('user not found');
			return;
		}
		if(deleteDatabase($remover_uid, $removed_uid)){
			echo('successfully deleted');
		}
	}
	main();
	mysqli_close($conn);
?>
