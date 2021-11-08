var pathArray = window.location.pathname.split('/'); //Get the username of the userpage the user is on
var other = pathArray[pathArray.length-1];
other = other.substring(0, other.indexOf('.'));

function checkFollow() //Check if the user already follows or not
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes("true")) //If the user already follows the user, change the follow button to an unfollow
            {
                document.getElementById("follow").innerHTML = '-Unfollow';
            }
            else if(res.includes("false")) //If the user doesn't follow this user, display the follow button
            {
                document.getElementById("follow").innerHTML = '+Follow';
            }
        }
    }
    xhttp.open("POST", "../has_user_followed.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = 'other=' + other;
    xhttp.send(sendString);
}
function isFriends()
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
                return false;
            }
            else if(!res)
            {
                console.log("Not Friends");
                return false;
            }
            else
            {
                res = JSON.parse(res);
                for (var i = 0; i < res.length;i++)
                {
                    if(res[i] == other)
                    {
                        return true;
                    }
                }
                return false;
            }
        }
    }
    xhttp.open("GET", "../get_friends.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}
function checkFriend() //Check if the user is already friends with another user
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            console.log("Friend Request");
            console.log(res);
            if(res.includes("false")) //If the user is already friends, give the option to remove the friend.
            {
                if(isFriends())
                {
                    document.getElementById("addFriend").innerHTML = 'Remove Friend';
                }
                else
                {
                    document.getElementById("addFriend").innerHTML = 'Add Friend';
                }
            }
            else if(res.includes("true")) //If a friend request is pending, give the option to cancel the request
            {
                document.getElementById("addFriend").innerHTML = 'Cancel Friend Request';
            }
        }
    }
    xhttp.open("POST", "../has_user_friend_requests.php", true); 
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = 'other=' + other;
    xhttp.send(sendString);
}
function loadPage() //Calls whenever the page loads
{
    var isOtherUser = !!document.getElementById("otherUserOptions");
    if(isOtherUser) //If you are on another user's page, display the correct buttons corresponding to each user relationship (Follower/Friend)
    {
        console.log("HERE");
        checkFollow();
        checkFriend();
    }
}
function followUser() //Called whenever the Follow/Unfollow button is pressed
{
    var followValue = document.getElementById("follow").innerHTML; //The value of the button that was pressed (Follow or Unfollow)
    console.log(followValue);
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes('database error'))
            {
                alert("Error: database");
            }
            else if(res.includes('successfully followed'))
            {
                console.log("Now following");
            }
            else if(res.includes('successfully unfollowed'))
            {
                console.log("No longer following");
            }
        }
    }
    if(followValue == '+Follow') //If user pressed follow button, call the function send a follow to the database
    {
        xhttp.open("POST", "../send_follow.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'other=' + other;
        xhttp.send(sendString);
        document.getElementById("follow").innerHTML = '-Unfollow';
    }
    else if(followValue == '-Unfollow') //If user pressed the unfolow button, call the function to unfollow a user
    {
        xhttp.open("POST", "../cancel_follow.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'other=' + other;
        xhttp.send(sendString);
        document.getElementById("follow").innerHTML = '+Follow';
    }
}

function sendFriendRequest() //Called whenver the user presses the Add Friend/Cancel Friend Request/Remove Friend button
{
    var friendButton = document.getElementById("addFriend").innerHTML; //The value of the button that was pressed
    console.log(friendButton);
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes('database error'))
            {
                alert("Error: database");
            }
            else if(res.includes('successfully sent'))
            {
                console.log("Friend Request Sent");
            }
            else if(res.includes('successfully cancelled'))
            {
                console.log("Friend Request Cancelled");
            }
            else if(res.includes('successfully deleted'))
            {
                console.log("Friend Removed");
            }
        }
    }
    if(friendButton == 'Add Friend') //If the Add Friend button was pressed, send a friend request
    {
        xhttp.open("POST", "../send_friend_request.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'other=' + other;
        xhttp.send(sendString);
        document.getElementById("addFriend").innerHTML = 'Cancel Friend Request';
    }
    else if(friendButton == 'Cancel Friend Request') //If the Cancel Friend Request button is pressed, cancel the friend request
    {
        xhttp.open("POST", "../cancel_friend_request.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'other=' + other;
        xhttp.send(sendString);
        document.getElementById("addFriend").innerHTML = 'Add Friend';
    }
    else if(friendButton == 'Remove Friend') //If the remove friend button is pressed, remove the friend relationship
    {
        xhttp.open("POST", "../remove_friend.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'other=' + other;
        xhttp.send(sendString);
        document.getElementById("addFriend").innerHTML = 'Add Friend';
    }
}