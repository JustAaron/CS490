function displayFavoriteRecipes(title, creator, image, id)
{
    var recipeHTML = '<p class="feedPageRecipeTitle">' + title + '</p><p class="feedPageImage"><a href="../'+ creator + '/' + id + '.php"><img src="../' + image + '" class="feedPageImage"></a></p><p class="feedPageRecipeCreator">By: ' + creator + '</p>';
    document.getElementById('favoritePageDisplay').innerHTML += recipeHTML;
}
function loadFavoriteRecipes()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res = this.responseText;
            console.log(res);
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
                    var recipeTitle = res[i].Title;
                    var imagePath = res[i].ImagePath;
                    var recipeID = res[i].RecipeID;
                    var creator = res[i].Username;
                    displayFavoriteRecipes(recipeTitle, creator, imagePath, recipeID);
                }
            }
        }
    }
    xhttp.open("GET", "../get_favorites.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}