<?php
	/*
	Functionality: echoes a json-encoded string using given information. Should be used whenever a client wants to view their messages with another user.
	The client is the user who is currently logged in. The other is the user who the client wants to chat with.
	Input:
	In $_POST[], "other" should be the username of other.
	In $_SESSION[], "username" should be the username of the client. Note: the file does not check the $_SESSION[] "password". 
	Output:
	If the other is valid and there are >0 messages between client and other and index is within bounds: echo json-encoded string where there are n elements (n=number of messages). Each element has keys ("isIncoming", "Message") and values ([True/False], <message stored in database).
	Example: [{"isIncoming":true,"Message":"test message from 1 to 0"},{"isIncoming":false,"Message":"test message from 0 to 1"}] 
	If the other is valid but there are 0 messages between client and other: echo "no messages found"
	If the other is not valid: echo "user not found"
	
	Update 11/3/21: Mark messages as read if the message receiver is the logged-in user
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
	
	function usernameToUid($username){
		global $conn;
		$query = 'SELECT * FROM alpha_users WHERE username="' . $username . '";';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$uid = $row['uid'];
			return $uid;
		}
		return -1;  // error value
	}
	
	function markAsReadDatabase($sender_uid, $receiver_uid){
		global $conn;
		$query = 'UPDATE ChatMessages SET isRead=0 WHERE SenderID="' . $receiver_uid . '" AND ReceiverID="' . $sender_uid . '";';
		$result = mysqli_query($conn, $query);
		if($result){
			return 0;
		}
		return -1;  // error value
		
	}
	
	function getMessagesDatabase($sender_uid, $receiver_uid){
		global $conn;
		$query = 'SELECT * FROM ChatMessages WHERE (SenderID="' . $sender_uid . '" AND ReceiverID="' . $receiver_uid . '") OR (SenderID="' . $receiver_uid . '" AND ReceiverID="' . $sender_uid . '") ORDER BY MessageID ASC;';
		$result = mysqli_query($conn, $query);
		if($result && mysqli_num_rows($result) > 0){
			$messages = array();
			while($row = mysqli_fetch_assoc($result)){
				if($sender_uid == $row['SenderID']){
					array_push($messages, array('isIncoming'=>False, 'Message'=>$row['Message']));
				}
				else{
					array_push($messages, array('isIncoming'=>True, 'Message'=>$row['Message']));
				}
			}
			$str = json_encode($messages);
			echo($str);
		}
		else{
			echo('no messsages found');
		}
		return 0;
	}
	
	function main(){
		$sender_uid = $_SESSION['uid'];
		$receiver_username = $_POST['other'];
		$receiver_uid = usernameToUid($receiver_username);
		if($receiver_uid == -1){
			echo('user not found');
			return -1;
		}
		if(getMessagesDatabase($sender_uid, $receiver_uid) == 0){
			markAsReadDatabase($sender_uid, $receiver_uid);
		}
	}
	main();
	mysqli_close($conn);
?>
