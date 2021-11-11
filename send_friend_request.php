<?php
	/*
	Functionality: Allows a user to send a friend request by inserting a row into the friend request table. If there is already a sent friend request from the "other" to the logged-in user, automatically accept it instead.
	Input:
	In $_SESSION[], "uid" should be the uid of the logged-in user
	In $_POST[], "other" should be the username of the user to send a friend request to
	Output:
	If the friend request is successfully sent: echo "successfully sent"
	If the friend request was accepted instead (because there was already a sent request from the "other": echo "successfully accepted"
	If the friend request failed because the "other" user was not found: echo "user not found"
	If the friend request failed because of a database error: echo "database error"
	
	Last updated 11/9/21
	Added auto-accept feature
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
	
	function getMaxRequestID(){
		global $conn;
		$query = 'SELECT MAX(FriendRequestID) as max_rid FROM FriendRequests;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			if($row['max_rid'] == null){
				return 0;
			}
			$max = $row['max_rid'];
			return $max;
		}
		return -1;  // error value
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

	function hasPendingFriendRequest($client_id, $other_id){
		global $conn;
		$query = 'SELECT * FROM FriendRequests WHERE SenderID=' . $other_id . ' AND ReceiverID=' . $client_id . ';';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			return true;
		}
		return false;
	}

	function deleteFriendRequestsDatabase($accepter_uid, $accepted_uid){
		global $conn;
		$query = 'DELETE FROM FriendRequests WHERE (ReceiverID=' . $accepter_uid . ' AND SenderID=' . $accepted_uid . ') OR (ReceiverID=' . $accepted_uid . ' AND SenderID=' . $accepter_uid . ');';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return false;
		}
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

	function insertFriendsDatabase($max_friend_id, $accepter_uid, $accepted_uid){
		global $conn;
		if($accepter_uid < $accepted_uid){
			$user1_id = $accepter_uid;
			$user2_id = $accepted_uid;
		}
		else{
			$user1_id = $accepted_uid;
			$user2_id = $accepter_uid;
		}
		$query = 'INSERT INTO Friends (RelationshipID, User1ID, User2ID) VALUES (' . $max_friend_id . ', ' . $user1_id . ', ' . $user2_id . ');';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return false;
		}
		return true;
	}

	function acceptFriendRequest($client_id, $other_id){
		/*$url = 'accept_friend_request.php';
		$post_data = array(
			'other'=>$_POST['other']
		);
		$options = array(
			'http'=>array(
				'header'=>'Content-type: application/x-www-form-urlencoded\r\n',
				'method'=>'POST',
				'content'=>http_build_query($post_data),
			),
		);
		$context = stream_context_create($options);
		$html = file_get_contents($url, false, $context);
		var_dump($html);*/
		$max_friend_id = getMaxFriendsID();
		if($max_friend_id == -1){
			echo('database error');
			return;
		}
		if(deleteFriendRequestsDatabase($client_id, $other_id)){
			if(insertFriendsDatabase($max_friend_id+1, $client_id, $other_id)){
				echo('successfully accepted');
			}
		}
	}

	function insertDatabase($request_id, $sender_id, $receiver_id){
		global $conn;
		$query = 'INSERT INTO FriendRequests (FriendRequestID, SenderID, ReceiverID) VALUES (' . $request_id . ', ' . $sender_id . ', ' . $receiver_id . ');';
		$result = mysqli_query($conn, $query);
		if(!$result){
			echo('database error');
			return;
		}
		echo('successfully sent');
	}

	function main(){
		$sender_id = $_SESSION['uid'];
		$receiver_username = $_POST['other'];
		$receiver_id = getUserID($receiver_username);
		if($receiver_id == -1){
			echo('user not found');
			return;
		}
		$max_request_id = getMaxRequestID();
		if($max_request_id == -1){
			echo('database error');
			return;
		}
		$hasPending = hasPendingFriendRequest($sender_id, $receiver_id);
		if($hasPending){
			acceptFriendRequest($sender_id, $receiver_id);
		}
		else{
			insertDatabase($max_request_id+1, $sender_id, $receiver_id);
		}
	}
	main();
	mysqli_close($conn);
?>
