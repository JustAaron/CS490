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
    var messageHTML = '<input type=submit name="' + username + '" value="' + username +
     '" class="chatusers" onclick="getOther(this.value)"><br>'; //Format each user
    document.getElementById("userlist").innerHTML += messageHTML; //Add the username to the users list
}
function getOther(user){
    i = 0; //Display all the messages from the beginning
    document.getElementById("chatWindow").innerHTML = ""; //Clear the chat window from the previous user
    other = user; //Each time the user button is clicked, the other user is set to that user
    document.getElementById("chatHeader").innerHTML = other; //Set the chat header to who you're chatting with
}

function loadUsers(){
    var xhttp = new XMLHttpRequest();
    var res = "";
    xhttp.onload = function(){
        if (xhttp.readyState === xhttp.DONE && this.status == 200)
        {
            res=this.responseText; //Response should be the list of users
            if(res.includes('no other users')){
                console.log("There were no other users");
            }
            else if(res.includes('database error'))
            {
                alert("Error: Database");
            }
            else{
                res = JSON.parse(res);
                console.log(res);
                for(var j = 0; j < res.length; j++) //Each username is in an array
                {
                    var user = res[j];
                    addUser(user); //Add each user in the array to the users list
                }
            }
        }
    }
    xhttp.open('GET', '../get_users.php', true); //Get the array of users from get_users.php
    xhttp.send(null);
    updateListen(); //Call update listen to listen for incoming messages in real-time
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
            console.log(res);
            if(res.includes('user not found')) //username in the text field is not a valid user
            {
                console.log("user not found");  //Clear the chat window and reset the chat header, and reset the index
                document.getElementById("chatHeader").innerHTML = "Chat";
                i = 0;
            }
            else if(res.includes("no messages found"))
            {
                console.log("no messages found");
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
                console.log(res);
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
