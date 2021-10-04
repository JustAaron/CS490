function send()
{
    var myMessage = document.getElementById("message").value;
    var messageHTML = '<div class="outgoingMessage">' +
    '<div class="myMessage">'+ myMessage + '</div>' + 
    '<div class="spacing"></div>' + '</div>';
    
    document.getElementById("chatWindow").innerHTML += messageHTML;
    document.getElementById("message").value = "";

}

function updateClient(){
    var message = document.getElementById("chat").value;
    if(message[message.length - 1] !== '\n'){
   	 return;
    }
    //alert("last key is enter");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
   	 if(this.readyState == 4 && this.status == 200){
   		 document.getElementById("clientoutput").innerHTML = this.responseText;
   	 }
    };
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var chat = document.getElementById("chat").value;
    xhttp.open("POST", "chat_client.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = "".concat("username=", username, "&password=", password, "&chat=", chat);
    xhttp.send(sendString);
    document.getElementById("chat").value = "";
}

function updateListen(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
   	 if(this.readyState == 4 && this.status == 200){
   		 console.log(this.responseText);
   		 document.getElementById("listenchat").value = this.responseText;
   	 }
    };
    var username = document.getElementById("listenname").value;
    xhttp.open("POST", "chat_listen.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = "".concat("username=", username);
    xhttp.send(sendString);
    setTimeout(function(){ updateListen(); }, 1000);
}
const json = '{"result":"Hello\\"Hi\\" World", "count":42}';
 var data = JSON.parse(json);
 console.log(data.result);
