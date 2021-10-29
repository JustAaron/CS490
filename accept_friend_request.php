<?php
	/*
	Functionality: Accepts a friend request by updating the FriendRequests database table and Friends database table accordingly. 
	Input: 
	In $_SESSION[], "uid" should be the logged-in user
	In $_POST[], "other" should be the user whose friend request is accepted
	Output:
	If both databases were successfully updated: echo "successfully accepted"
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
	
	/*function deleteDatabase(){
		global $conn;
		$accepter_uid = $_SESSION['uid'];
		$accepted_username = $_POST['other'];
		$accepted_uid = getUserID($accepted_username);
		if($accepted_uid == -1){
			echo('user not found');
		}
		$query = 'DELETE FROM FriendRequests WHERE ReceiverID=' . $accepter_uid . ' AND SenderID=' . $accepted_uid . ';';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return;
		}
		echo('successfully accepted');
	}*/
	
	function updateFriendRequestsDatabase($accepter_uid, $accepted_uid){
		global $conn;
		//$accepter_uid = $_SESSION['uid'];
		//$accepted_username = $_POST['other'];
		//$accepted_uid = getUserID($accepted_username);
		if($accepted_uid == -1){
			echo('user not found');
		}
		$query = 'UPDATE FriendRequests SET isActive=0 WHERE ReceiverID=' . $accepter_uid . ' AND SenderID=' . $accepted_uid . ';';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return false;
		}
		//echo('successfully accepted');
		return true;
	}
	
	function getMaxFriendsID(){
		global $conn;
		$query = 'SELECT MAX(RelationshipID) as max_fid FROM Friends;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			if($row['max_fid'] == null){
				return 0;
			}
			$max = $row['max_fid'];
			return $max;
		}
		return -1;  // error value
	}
	
	function updateFriendsDatabase($max_friend_id, $accepter_uid, $accepted_uid){
		global $conn;
		$query = 'INSERT INTO Friends (RelationshipID, User1ID, User2ID) VALUES (' . $max_friend_id . ', ' . $accepter_uid . ', ' . $accepted_uid . ');';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return false;
		}
		echo('successfully accepted');
		return true;
	}
	
	function main(){
		//deleteDatabase();
		$accepter_uid = $_SESSION['uid'];
		$accepted_username = $_POST['other'];
		$accepted_uid = getUserID($accepted_username);
		$max_friend_id = getMaxFriendsID();
		if($max_friend_id == -1){
			echo('user not found');
			return;
		}
		if(updateFriendRequestsDatabase($accepter_uid, $accepted_uid)){
			updateFriendsDatabase($max_friend_id+1, $accepter_uid, $accepted_uid);
		}
	}
	main();
	mysqli_close($conn);
?>
