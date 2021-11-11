var i = 0;
var other = "";
function displayOwnMessage(message)
{
    var messageHTML = '<div class="outgoingMessage">' + //Format the message to appear on the chat window
    '<div class="myMessage">'+ message + '</div>' + 
    '<div class="spacing"></div>' + '</div>';
    document.getElementById("chatWindow").innerHTML += messageHTML; //add the formatted message to the chat window
}

function displayIncomingMessage(message)
{
    var messageHTML = '<div class="incomingMessage">' + //format the message that was received
    '<div class="othersMessage">' + message + '</div>' +
    '<div class="spacing"></div>' + '</div>';
    document.getElementById("chatWindow").innerHTML += messageHTML; // add the formatted message to the chat window
}
function addUser(username)
{
    var messageHTML = '<div class="userChatNoti" id="chat' + username + '"><button class="chatusers" value="' + username + '"onclick="getOther(this.value)">' + username + '</button></div><br>'; //Format each user
    document.getElementById("userlist").innerHTML += messageHTML; //Add the username to the users list
}
function addUserWithUnread(username, unread)
{
    var messageHTML = '<div class="userChatNoti" id="chat' + username + '"><button class="chatusers" value="' + username + '"onclick="getOther(this.value)">' + username + '<span class="chatNoti" id="unread' + username + '">' + unread + '</span></button></div><br>'; //Format each user
    document.getElementById("userlist").innerHTML += messageHTML; //Add the username to the users list
}
function getOther(user){
    i = 0;
    document.getElementById("chatWindow").innerHTML = '<h2 id="chatHeader">Chat</h2>';
    other = user; //Each time the user button is clicked, the other user is set to that user
    document.getElementById("chatHeader").innerHTML = other; //Set the chat header to who you're chatting with
    var unreadID = "unread" + user;
    var messageNoti = document.getElementById(unreadID);
    if(messageNoti != null)
    {
        messageNoti.parentNode.removeChild(messageNoti);
    }
}
function getFriends()
{
    loadRequestNotifications();
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            console.log(res);
            if(res.includes('database error'))
            {
                console.log("Friends Database error");
            }
            else if(!res)
            {
                console.log("Not Friends");
            }
            else
            {
                res = JSON.parse(res);
                for (var i = 0; i < res.length;i++)
                {
                    var user = res[i];
                    getNumUnread(user);
                }
            }
        }
    }
    xhttp.open("GET", "../get_friends.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
    updateListen();
}
function loadRequestNotifications(){
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes("true")) //User has pending friend requests
            {
                var friendsNoti = document.getElementById("friendsNoti");
                if(friendsNoti == null)
                {
                    document.getElementById("friendsPage").innerHTML += '<span class="noti" id="friendsNoti"></span>'
                }
            }
            else
            {
                var friendsNoti = document.getElementById("friendsNoti");
                if(friendsNoti != null)
                {
                    friendsNoti.parentNode.removeChild(friendsNoti);
                }
            }
        }
    }
    xhttp.open("GET", "../has_friend_requests.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}
function getNumUnread(username)
{
      var xhttp = new XMLHttpRequest();
        xhttp.onload = function(){ //Runs everytime a response returns after send()
          if(this.readyState == 4 && this.status == 200)
          {
              res=this.responseText;
              if(res.includes("database error"))
              {
                  alert("Error: Database");
              }
              else
              {
                  var unread = parseInt(res);
                  if(unread == 0)
                  {
                      addUser(username);
                  }
                  else
                  {
                      addUserWithUnread(username, unread);
                  }
              }
        }
      }
        xhttp.open("POST", "../get_num_unread_messages.php", true); 
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'other=' + username;
        xhttp.send(sendString);
}
function updateClient(){
    var message = document.getElementById("message").value; //store the message to a variable
    if(message.length === 0){ //check to make sure a message was entered
      console.log("No message");
      alert("Please write a message");
   	 return;
    }
    var xhttp = new XMLHttpRequest();
    var res = "";
    xhttp.onload = function(){     //runs whenever there is a response from chat_client
      if(this.readyState == 4 && this.status == 200)
      {
 	    res = this.responseText;
        if(res.includes('user not found'))
        {
            alert("Please search for a valid user");
        }
        else if(res.includes("error while inserting values"))
        {
            alert("Error while inserting values");
        }
        else if(res.includes("values inserted"))
        {
          //displayOwnMessage(message); //display the message onto the chat window
        }
      }
    };
    xhttp.open("POST", "../chat_client.php", true);    //send the other username and message to chat_client
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = "".concat("other=", other, "&message=", message);
    xhttp.send(sendString); 
    document.getElementById("message").value = ""; //clear the text box
}

function updateListen(){
    var xhttp = new XMLHttpRequest();
    var res = "";
    xhttp.onload = function(){
        if (xhttp.readyState === xhttp.DONE && this.status == 200)
        {
            res = this.responseText;    //response from chat_listen
            if(res.includes('user not found')) //username in the text field is not a valid user
            {
                console.log("user not found");  //Clear the chat window and reset the chat header, and reset the index
                document.getElementById("chatHeader").innerHTML = "Chat";
                i = 0;
            }
            else if(res.includes("no messsages found"))
            {
                console.log("no messages");
            }
			else if(res.includes("error while getting max_message_id"))
			{
				console.log("error while getting max_message_id");
			}
			else if(res.includes("empty message")){
				console.log("empty message");
			}
            else
            {
                res = JSON.parse(res);  //returns an array of existing messages
                for (i;i<res.length;i++) //go through each item in the array
                {
                  if(res[i].isIncoming)
                  {
                      displayIncomingMessage(res[i].Message); //display the incoming message onto the chat window
                  }
                  else
                  {
                      displayOwnMessage(res[i].Message);////display the own message onto the chat window
                  }
                }
            }
        }
    }
    xhttp.open("POST", "../chat_listen.php", true);    //send the other username to chat_listen
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = "other=" + other; 
    xhttp.send(sendString);
    
    setTimeout(function(){ updateListen(); }, 1000); //listen for more messages every second
}
document.getElementById("sendButton").addEventListener('click', function(){updateClient()}); 
