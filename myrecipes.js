var pathArray = window.location.pathname.split('/'); //Get the username of the userpage the user is on
var username = pathArray[pathArray.length-2];
function displayMyRecipe(title, image, id)
{
    var recipeHTML = '<p class="feedPageRecipeTitle">' + title + '</p><p class="feedPageImage"><a href="../'+ username + '/' + id + '.php"><img src="../' + image + '" class="feedPageImage"></a></p><p class="feedPageRecipeCreator">By: ' + username + '</p>';
    document.getElementById('myRecipePageFeed').innerHTML += recipeHTML;
}
function loadMyRecipes()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res = this.responseText;
            if(res.includes('no recipes'))
            {
                console.log("There are no recipes");
            }
            else if(res.includes('database error'))
            {
                alert("Error: Database");
            }
            else
            {
                res = JSON.parse(res);
                for (var i =0;i<res.length;i++)    //Go through all the comments one at a time and add it to the comments section
                {
                    var recipeTitle = res[i].recipe_title;
                    var imagePath = res[i].image_path;
                    var recipeID = res[i].recipe_id;
                    displayMyRecipe(recipeTitle, imagePath, recipeID);
                }
            }
        }
    }
    xhttp.open("GET", "../get_all_my_recipes.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}