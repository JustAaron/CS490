function clearSearchBox(){
    document.getElementById("searchField").innerHTML = "";
    
}
function clearResults(){
    document.getElementById("results").innerHTML = "";
}
function displayPostResults(postID, username, subject)
{
    var messageHTML = '<div class="res"><a href="postPage.php">' + subject + ' by '+ username + '</a></div>' + '<div class="searchSpace"></div>';
    document.getElementById("results").innerHTML += messageHTML;
}
function displayUserResults(username)
{
    var messageHTML = '<div class="res"><a href="username.php">' + username + '</a></div>' + '<div class="searchSpace"></div>';
    document.getElementById("results").innerHTML += messageHTML;
}

var searchForm = document.getElementById('searchOptions');

searchForm.addEventListener('submit', function(event){
    event.preventDefault();
    clearResults();
    var search = document.getElementById('searchField').value;
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;
            if(res.includes('no matches'))
            {
                console.log("No matches found");
                alert("No matches found");
                clearSearchBox();
                clearResults();
            }
            else
            {
                res = JSON.parse(res);
                for (var i = 0; i < res.length;i++)
                {
                    console.log(res[i].username);
                    if(res[i].post_id == null)
                    {
                        //Users
                        console.log("Searching for a user");
                        var user = res[i].username;
                        displayUserResults(user);
                    }
                    else
                    {
                        console.log("You are searching posts");
                        var postID = res[i].post_id;
                        var subject = res[i].post_subject;
                        var user = res[i].username;
                        displayPostResults(postID, user, subject);
                    }
                }
            }
        }
    }
    if(document.getElementById('postRadio').checked) 
    {
        xhttp.open("POST", "search_post.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'searchpost=' + search;
        xhttp.send(sendString);
        console.log(search);
        console.log("Post");
    }
    else if(document.getElementById('userRadio').checked) 
    {
        xhttp.open("POST", "search_user.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'searchuser=' + search;
        xhttp.send(sendString);
        console.log(search);
        console.log("Post");
    }
    else
    {
        alert("Please select a user or post option");
    }
})