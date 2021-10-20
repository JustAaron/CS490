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

$(document).ready(function (e){
  $("#postForm").on("submit", function (e){
    e.preventDefault();
    $.ajax({
      url: "../add_post.php",
      type: "POST",
      data: new FormData(searchForm),
      contentType: false,
      cache: false,
      processData: false,
      success: function(data){
        if(data == 0){
          alert("Empty Fields");
          console.log(data);
        }
        else{
          console.log("Success");
        }
      }
    });
  });
});

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
