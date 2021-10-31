function clearSearchBox(){ //Clear the contents of the search box
    document.getElementById("searchField").innerHTML = "";
    
}
function clearResults(){ //Clear the results of the previous search
    document.getElementById("results").innerHTML = "";
}
function displayPostResults(postID, username, subject) //Display the posts that match the search
{
    var messageHTML = '<div class="res"><a href="../' + username + '/' + postID + '.php">' + subject + ' by '+ username + '</a></div>' + '<div class="searchSpace"></div>';
    document.getElementById("results").innerHTML += messageHTML;
}
function displayUserResults(username) //Display the users that match the search
{
    var messageHTML = '<div class="res"><a href="../' + username + '/' + username + '.php">' + username + '</a></div>' + '<div class="searchSpace"></div>';
    document.getElementById("results").innerHTML += messageHTML;
}
function clearForm() //Clear the search form on the page to get ready to display the newly selected option
{
    var table = document.getElementById("searchElements");
    while (table.rows.length > 0) 
    {
      table.deleteRow(0);
    }
}
function getForm(radio) //Display a different form for each selected option
{
    var selectedButton = radio.id; //Find out which radio button is selected
    console.log(selectedButton);
    if(selectedButton == 'userRadio') //Clear the existing form and dispay the user search form
    {
      clearForm();
      console.log("You are searching for a user");
      var searchHTML = '<tr id="search" class="search"><td><label for="searchField">Username:</label</td><td><input type="text" id="searchField" class="searchField" name="searchField"></td></tr><tr id="search" class="search"><td>                          <button type="submit" class="searchB">Search</button></td></tr>';
      document.getElementById("searchElements").innerHTML += searchHTML;
    }
    else if(selectedButton == 'spoonRadio') //Clear the existing form and dispay the recipe search form
    {
      clearForm();
      console.log("You are searching for a spoonacular recipe");
      var searchHTML = '<tr class="search"><td><label for="title">Title:</label></td><td><input type="text" id="title" class="searchField"></td></tr><tr class="search"><td><label for="ingredients">Ingredients:</label></td><td>                             <input type="text" id="ingredients" class="searchField"></td></tr><tr class="search"><td><label for="tagSelect">Tags:</label</td><td><select id="tagSelect" class="tagSelect" multiple=""><option>Vegan</option                         ><option>Vegetarian</option><option>Breakfast</option><option>Lunch</option><option>Dinner</option></select></td></tr><tr class="search"><td><button type="submit" class="searchB">Search</button></td></tr>';
      document.getElementById("searchElements").innerHTML += searchHTML;
      $('#tagSelect').chosen();
    }
    else if(selectedButton == 'recipeRadio') //Clear the existing form and dispay the user recipe search form
    {
      clearForm();
      console.log("You are searching for a user created recipe");
      var searchHTML = '<tr class="search"><td><label for="title">Title:</label></td><td><input type="text" id="title" class="searchField"></td></tr><tr class="search"><td><label for="tagSelect">Tags:</label</td><td>                                      <select id="tagSelect" class="tagSelect" multiple=""><option>Vegan</option><option>Vegetarian</option><option>Breakfast</option><option>Lunch</option><option>Dinner</option></select></td></tr><tr class=                              "search"><td><button type="submit" class="searchB">Search</button></td></tr>';
      document.getElementById("searchElements").innerHTML += searchHTML;
      $('#tagSelect').chosen();
    }
}
var searchForm = document.getElementById('searchOptions'); 

searchForm.addEventListener('submit', function(event){ //run everytime the search button is clicked
    event.preventDefault();
    clearResults();
    var search = document.getElementById('searchField').value; //What the user searched for
    var xhttp = new XMLHttpRequest();
    xhttp.onload = function(){ //Runs everytime a response returns after send()
        if(this.readyState == 4 && this.status == 200)
        {
            res=this.responseText;  //The text that returns from the server
            if(res.includes('no matches'))
            {
                console.log("No matches found");
                alert("No matches found");
                clearSearchBox();
                clearResults();
            }
            else
            {
                res = JSON.parse(res); //parse the json the contains the results
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
    if(document.getElementById('spoonRadio').checked) //call search_post if the user selected posts
    {
        xhttp.open("POST", "../search_post.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'searchpost=' + search;
        xhttp.send(sendString);
        console.log(search);
        console.log("Post");
    }
    else if(document.getElementById('userRadio').checked) //call search_user if the user selected users
    {
        xhttp.open("POST", "../search_user.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'searchuser=' + search;
        xhttp.send(sendString);
        console.log(search);
        console.log("Post");
    }
    else if(document.getElementById('spoonRadio').checked) //call search_post if the user selected posts
    {
        xhttp.open("POST", "../search_post.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'searchpost=' + search;
        xhttp.send(sendString);
        console.log(search);
        console.log("Post");
    }
    else
    {
        alert("Please select a user or post option");
    }
})