var searchForm = document.getElementById('postForm');
var pathArray = window.location.pathname.split('/');
var username = pathArray[pathArray.length-1];
username = username.substring(0, username.indexOf('.'));
var i = 0;
function displayPostLink(post_subject, post_id)
{
    postHTML = '<div class="userPostLink"><a href="../' + username + '/' + post_id + '.php">' + post_subject + '</a></div>' + '<div class="searchSpace"></div>';
    document.getElementById('userPosts').innerHTML += postHTML;
}
searchForm.addEventListener('submit', function(event){ //run everytime the search button is clicked
    event.preventDefault();
    var xhttp = new XMLHttpRequest();
    var post_subject = document.getElementById('post_subject').value;
    var post_text = document.getElementById('post_text').value;
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res = this.responseText;
            console.log("Did it come in here?");
            if(res.includes('database error'))
            {
                alert('Error adding post');
            }
            document.getElementById('post_subject').value = "";
            document.getElementById('post_text').value = "";
        }
    }
    xhttp.open("POST", "../add_post.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = 'post_subject=' + post_subject + '&post_text=' + post_text;
    xhttp.send(sendString);
} )

function loadPosts()
{
    var xhttp = new XMLHttpRequest();
    console.log("running");
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            console.log("I got a res");
            res = this.responseText;
            console.log(res);
            if(res.includes('no posts'))
            {
            }
            else if(res.includes('database error'))
            {
                alert("Error: Database");
            }
            else
            {
                res = JSON.parse(res);
                for (i;i<res.length;i++)    //Go through all the comments one at a time and add it to the comments section
                {
                    var postSubject = res[i].post_subject;
                    var postID = res[i].post_id;
                    displayPostLink(postSubject, postID);
                }
            }
        }
    }
    xhttp.open("POST", "../get_user_posts.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = 'username=' + username;
    console.log(sendString);
    xhttp.send(sendString);

    setTimeout(function(){ loadPosts(); }, 1000);
}