function getTabs(){
    document.querySelectorAll(".selection").forEach(button => {
        button.addEventListener("click", () => {
            const tabs = button.parentElement;
            const tabContainer = tabs.parentElement;
            const tabNum = button.dataset.forTab;
            const selectedTab = tabContainer.querySelector(`.tabContent[data-tab="${tabNum}"]`);

            tabs.querySelectorAll(".selection").forEach(button => {
                button.classList.remove("selection-active");
            });
            tabContainer.querySelectorAll(".tabContent").forEach(tab => {
                tab.classList.remove("tab-active");
            });

            button.classList.add("selection-active");
            selectedTab.classList.add("tab-active");
        });
    });
}
document.addEventListener("DOMContentLoaded", () => {
    getTabs();
});

function getContent()
{
    getFriends();
    getFriendRequests();
    getFollowing();
    loadRequestNotifications();
    getFriendsNotifications();
}
function loadChatNotifications(friends){
    for (var i = 0; i < friends.length; i++)
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
                  if(parseInt(res) > 0 && document.getElementById("friendNoti") == null)
                  {
                      document.getElementById("messagesPage").innerHTML += '<span class="noti" id="chatNoti"></span>';
                  }
              }
        }
      }
        other = friends[i];
        xhttp.open("POST", "../get_num_unread_messages.php", true); 
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'other=' + other;
        xhttp.send(sendString);
    }
    
}
function getFriendsNotifications()
{
    var friendsList = [];
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes('database error'))
            {
                console.log("Friends Database error");
            }
            else if(!res)
            {
                console.log("No friends");
                return null;
            }
            else
            {
                res = JSON.parse(res);
                for (var i = 0; i < res.length;i++)
                {
                    friendsList.push(res[i]);
                }
                loadChatNotifications(friendsList);
            }
        }
    }
    xhttp.open("GET", "../get_friends.php", true); 
    xhttp.send();
}
function loadRequestNotifications(){
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes("true")) //User has pending friend requests
            {
                var friendsNoti = document.getElementById("requestNoti");
                if(friendsNoti == null)
                {
                    document.getElementById("requestNotiTab").innerHTML += '<span class="noti" id="requestNoti"></span>'
                }
            }
            else
            {
                var friendsNoti = document.getElementById("requestNoti");
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
function getFriends()
{
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
                    addFriendList(res[i]);
                }
            }
        }
    }
    xhttp.open("GET", "../get_friends.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}
function addFriendList(user)
{
    var friendHTML = '<div class="friendList" id="friend' + user + '">' + user + '<button class="unfriendButton" onclick="unfriend(\'' + user + '\')"><span class="unfollowSymbol">&#9746;</span> Remove Friend</button></div>';
    document.getElementById("friendsTab").innerHTML += friendHTML;
}
function unfriend(user)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes("user not found")) //If the user already follows the user, change the follow button to an unfollow
            {
                console.log("User not found");
            }
            else if(res.includes("database error")) //If the user doesn't follow this user, display the follow button
            {
                 console.log("Database error");
            }
            else if(res.includes("successfully deleted")) //If the user doesn't follow this user, display the follow button
            {
                console.log("Successfully removed friend");
            }
        }
    }
    xhttp.open("POST", "../remove_friend.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = 'other=' + user;
    xhttp.send(sendString);
    var friendClass = 'friend' + user;
    var friend = document.getElementById(friendClass);
    friend.remove();
}
function getFollowing()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes('user not found'))
            {
                alert("Error: Database");
            }
            else if(res.includes('database error'))
            {
                alert("Error: Database");
            }
            else
            {
                console.log(res);
                res = JSON.parse(res);
                for (var i = 0; i < res.length;i++)
                {
                    console.log(res[i]);
                    following = res[i];
                    addFollowing(following);
                }
            }
        }
    }
    xhttp.open("GET", "../get_followed.php", true); //CHANGE SOON call the function that will check if users are friends.
    xhttp.send();
}
function addFollowing(username)
{
    var friendRequestHTML = '<div class="followingUser" id="following' + username + '">' + username + '<button class="unfollowButton" onclick="unfollowUser(\'' + username + '\')"><span class="unfollowSymbol">&#9746;</span> Unfollow</button></div>';
    document.getElementById("followingTab").innerHTML += friendRequestHTML;
}
function unfollowUser(username)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes("user not found")) //If the user already follows the user, change the follow button to an unfollow
            {
                console.log("User not found");
            }
            else if(res.includes("database error")) //If the user doesn't follow this user, display the follow button
            {
                 console.log("Database error");
            }
            else if(res.includes("successfully unfollowed")) //If the user doesn't follow this user, display the follow button
            {
                console.log("Successfully unfollowed user");
            }
        }
    }
    xhttp.open("POST", "../cancel_follow.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = 'other=' + username;
    xhttp.send(sendString);
    var followClass = 'following' + username;
    var following = document.getElementById(followClass);
    following.remove();
}
function getFriendRequests()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes('user not found'))
            {
                alert("Error: Database");
            }
            else if(res.includes('database error'))
            {
                alert("Error: Database");
            }
            else
            {
                console.log(res);
                res = JSON.parse(res);
                for (var i = 0; i < res.length;i++)
                {
                    console.log(res[i]);
                    requestor = res[i];
                    addFriendRequest(requestor)
                }
            }
        }
    }
    xhttp.open("GET", "../get_friend_requests.php", true); //CHANGE SOON call the function that will check if users are friends.
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}

function addFriendRequest(requestor)
{
    var friendRequestHTML = '<div class="friendRequest" id="request' + requestor + '">' + requestor + '<button class="declineFriend" onclick="friendDecline(\'' + requestor + '\')">&#10060;</button><button class="acceptFriend" onclick="friendAccept(\'' + requestor + '\')">	&#10004;</button></div>';
    console.log(friendRequestHTML);
    document.getElementById("friendRequestTab").innerHTML += friendRequestHTML;
}
function friendAccept(requestor)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes("user not found")) //If the user already follows the user, change the follow button to an unfollow
            {
                console.log("User not found");
            }
            else if(res.includes("database error")) //If the user doesn't follow this user, display the follow button
            {
                 console.log("Database error");
            }
            else if(res.includes("successfully accepted")) //If the user doesn't follow this user, display the follow button
            {
                console.log("Successfully accepted friend request");
            }
        }
    }
    xhttp.open("POST", "../accept_friend_request.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = 'other=' + requestor;
    xhttp.send(sendString);
    var requestClass = 'request' + requestor;
    var request = document.getElementById(requestClass);
    request.remove();
}
function friendDecline(requestor)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes("user not found")) //If the user already follows the user, change the follow button to an unfollow
            {
                console.log("User not found");
            }
            else if(res.includes("database error")) //If the user doesn't follow this user, display the follow button
            {
                 console.log("Database error");
            }
            else if(res.includes("successfully rejected")) //If the user doesn't follow this user, display the follow button
            {
                console.log("Successfully declined friend request");
            }
        }
    }
    xhttp.open("POST", "../reject_friend_request.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = 'other=' + requestor;
    xhttp.send(sendString);
    var requestClass = 'request' + requestor;
    var request = document.getElementById(requestClass);
    request.remove();
}