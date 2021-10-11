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
function isJSON(testStr){
    try {
        var MyJSON = JSON.stringify(testStr);
        var json = JSON.parse(MyJSON);
        if(typeof(testStr) == 'string')
            if(testStr.length == 0)
                return false;
    }
    catch(e){
        return false;
    }
    return true;
}
function updateClient(){
    var message = document.getElementById("message").value;
    if(message[message.length - 1] !== '\n'){
   	 return;
    }
    //alert("last key is enter");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
   	 if(this.readyState == 4 && this.status == 200){
   		 document.getElementById("chatWindow").innerHTML = this.responseText;
   	 }
    };
    var other = document.getElementById("chatHeader").value; // other is the user you want to chat with
    xhttp.open("POST", "chat_client.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.onload = function(){ //make sure they search for a user before hitting send
        res = this.responseText;
        if(res = 'user not found')
        {
            alert("Please search for a valid user");
            document.getElementById("message").value = "";
        }
    }
    var sendString = "".concat("&other=", other, "&message=", message);
    xhttp.send(sendString);
    
    displayOwnMessage(message) //display the message onto the chat window
    document.getElementById("message").value = ""; //clear the text box
}

function updateListen(){
    var xhttp = new XMLHttpRequest();
    var res = "";
    var index = 0;
    console.log("HERE");
    var other = document.getElementById("usernameSearch").value; //store the username search
    var x = 0;    
    xhttp.onload = function(){
        if (xhttp.readyState === xhttp.DONE && this.status == 200)
        {
            res = this.responseText;
            console.log(res);
            if(isJSON(res)){
                res = json.parse(res);
                document.getElementById("chatHeader").innerHTML = other; //Set the chat header to who you're chatting with
                if(res.isIncoming == TRUE)
                {
                    displayIncomingMessage(res.Message); //display the incoming message onto the chat window
                }
                else
                {
                    displayOwnMessage(res.Message);////display the own message onto the chat window
                }
            }

        }
    }
    xhttp.open("POST", "chat_listen.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = "other=" + other + "&index=" + index;
    xhttp.send(sendString);
    index++;
    
    setTimeout(function(){ updateListen(); }, 1000);
}

