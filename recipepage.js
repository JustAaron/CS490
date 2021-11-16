var pathArray = window.location.pathname.split('/'); //Extract the postID from the URL
var postID = pathArray[pathArray.length-1];
postID = postID.substring(0, postID.indexOf('.'));
var i = 0;
function addComment(username, comment) //Display the given comment in the comments section
{
    var commentHTML = '<p class="commentor">' + username + '</p><p class="commentContent">' + comment + '</p>';
    document.getElementById("recipeComments").innerHTML += commentHTML;
}
function displayRecipeContent(title, username, ingredients, steps, image)
{
    var titleUser = '<p id="userRecipeTitle">' + title + '</p><p id="recipeByUser">By: ' + username + '</p>';
    document.getElementById('recipeTitle').innerHTML += titleUser;
    var imageHTML = '<img src=../' + image + ' class="recipeImage">';
    document.getElementById('recipePicture').innerHTML += imageHTML;
    var ingredientHTML = '<p class="recipeInfo">' + ingredients + '</p>';
    document.getElementById('userRecipeIngredients').innerHTML += ingredientHTML;
    var stepsHTML = '<p class="recipeInfo">' + steps + '</p>';
    document.getElementById('userRecipeSteps').innerHTML += stepsHTML;
}
function displayRecipe()
{
    var xhttp = new XMLHttpRequest();
    res = "";
    //postID = 2; //Temporary for testing purposes
    xhttp.onload = function(){     //runs whenever there is a response from get_posts
        if(this.readyState == 4 && this.status == 200)
        {
            res = this.responseText;    //response from get_posts
            if(res.includes('no posts found'))
            {
                alert('Error: No post found');
            }
            else if(res.includes('database error'))
            {
                alert('Error: database');
            }
            else //a post was returned
            {
                res = JSON.parse(res);
                var recipeTitle = res[0].recipe_title;
                var recipeIngredients = res[0].recipe_ingredients;
                var recipeSteps = res[0].recipe_steps;
                var poster = res[0].username;
                var image_path = res[0].image_path;
                displayRecipeContent(recipeTitle, poster, recipeIngredients, recipeSteps, image_path);
            }
        }
    }
    xhttp.open("POST", "../get_recipe.php", true);    //send postID to get_posts
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = "post_id=" + postID;
    xhttp.send(sendString);
    displayComments(); //call this function to activate the listening for comments
}
function displayComments(){
    res = "";
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){     //runs whenever there is a response from get_comments
        if(this.readyState == 4 && this.status == 200)
        {
            res = this.responseText; //response from get_comments
            console.log(res);
            if(res.includes('no comments'))
            {
                //No comments yet
            }
            else if(res.includes('database error'))
            {
                alert("Error: database");
            }
            else
            {
                res = JSON.parse(res);
                for (i;i<res.length;i++)    //Go through all the comments one at a time and add it to the comments section
                {
                    var username = res[i].username;
                    var com = res[i].comment;
                    addComment(username, com);
                }
            }
        }
    }
    xhttp.open("POST", "../get_comments.php", true);    //send the postID to get_comments
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var sendString = "post_id=" + postID; 
    xhttp.send(sendString);
    setTimeout(function(){ displayComments(); }, 1000); // Run the function again to listen for new comments
}
function sendComment()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){     //runs whenever there is a response from add_comment
        if(this.readyState == 4 && this.status == 200)
        {
            res = this.responseText;
            if(res.includes('database error'))
            {
                alert("Error: database");
            }
        }
    }
    xhttp.open("POST", "../add_comment.php", true);    //send the postID and comment to add_comment
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var comment = document.getElementById('writeComment').value;
    var sendString = "post_id=" + postID + "&comment=" + comment; 
    xhttp.send(sendString);
    document.getElementById('writeComment').value = ""; //clear the text box
}