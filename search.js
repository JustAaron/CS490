var RecipeIdArray = [];
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
function create_spoon_recipes(id_array)
{
  console.log("Resolving the promise");
  var xhttp = new XMLHttpRequest();
  xhttp.onload = function(){
    clearResults();
    if(this.readyState == 4 && this.status == 200)
    {
      res = this.responseText;
      if(res.includes("error"))
      {
        alert("Error.");
        console.log(res);
      }
      else
      {
        res = JSON.parse(res);
        console.log(res);
        for(const key in res)
        {
          const link = res[key];
          var linkArray = link.split('/');
          var name = linkArray[linkArray.length-1];
          name = name.substring(0, name.indexOf('.'));
          name = name.replace(/-/g, ' ');
          const messageHTML = "<div class='res'><a href='../" + link + "'>" + name + "</a></div>";
        }
      }
    }
  }
  xhttp.open("POST", "../create_spoon_page.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  console.log(JSON.stringify(RecipeIdArray));
  var sendString = "";
  for(var i = 0; i < RecipeIdArray.length; ++i)
  {
    if(sendString.indexOf("?") === -1)
    {
      sendString = sendString + "?ids[]=" + RecipeIdArray[i];
    }
    else
    {
      sendString = sendString + "&ids[]=" + RecipeIdArray[i];
    }
  }
  console.log(sendString);
  xhttp.send(sendString);
}

function setRecipeId(id) //Sets the global variable to the Recipe ID so that we can bypass the asynchronous nature of AJAX
{
  console.log(JSON.stringify(id));
  RecipeIdArray = RecipeIdArray.concat(id);
  console.log(JSON.stringify(RecipeIdArray));
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
      var searchHTML = '<tr class="search"><td><label for="title">Title:</label></td><td><input type="text" id="title" name="title" class="searchField"></td></tr><tr class="search"><td><label for="tagSelect">Tags:</label</td><td><select id="tagSelect" name="tagSelect[]" class="tagSelect" multiple=""><option value=Gluten>Gluten</option><option></option><option value="Dairy">Dairy</option><option value="Peanut">Peanut</option><option value="Soy">Soy</option><option value="Egg">Egg</option><option value="Seafood">Seafood</option><option value="Sesame">Sesame</option><option value="TreeNut">Tree Nuts</option><option value="Grain">Grain</option><option value="Shellfish">Shellfish</option><option value="Wheat">Wheat</option></select></td></tr><tr class="search"><td><button type="submit" class="searchB">Search</button></td></tr>';
      document.getElementById("searchElements").innerHTML += searchHTML;
      $('#tagSelect').chosen();
    }
    else if(selectedButton == 'recipeRadio') //Clear the existing form and dispay the user recipe search form
    {
      clearForm();
      console.log("You are searching for a user created recipe");
      var searchHTML = '<tr class="search"><td><label for="title">Title:</label></td><td><input type="text" id="title" name="title" class="searchField"></td></tr><tr class="search"><td><label for="tagSelect">Tags:</label</td><td><select id="tagSelect" name="tagSelect[]" class="tagSelect" multiple=""><option value="Vegan">Vegan</option><option value="Vegetarian">Vegetarian</option><option value=GlutenFree>Gluten-free</option><option></option><option value="Dairy">Dairy</option><option value="Peanut">Peanut</option><option value="Soy">Soy</option><option value="Egg">Egg</option><option value="Seafood">Seafood</option><option value="Sesame">Sesame</option><option value="TreeNut">Tree Nuts</option><option value="Grain">Grain</option><option value="Shellfish">Shellfish</option><option value="Wheat">Wheat</option></select></td></tr><tr class="search"><td><button type="submit" class="searchB">Search</button></td></tr>';
      document.getElementById("searchElements").innerHTML += searchHTML;
      $('#tagSelect').chosen();
    }
}
function loadElements()
{
    loadRequestNotifications();
    getFriends();
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
function getFriends()
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
                var friendsNoti = document.getElementById("friendsNoti");
                if(friendsNoti == null)
                {
                    document.getElementById("friendsPage").innerHTML += '<span class="noti" id="friendsNoti"></span>'
                }
            }
            else
            {
                var friendsNoti = document.getElementById("friendsNoti");
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
var searchForm = document.getElementById('searchOptions');

searchForm.addEventListener('submit', function(event){ //run everytime the search button is clicked
    event.preventDefault();
    clearResults();
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
                console.log(res);
                res = JSON.parse(res); //parse the json the contains the results
                //console.log(res);
                for (var i = 0; i < res.length;i++)
                {
                    console.log(res[i].username);
                    if(res[i].RecipeID == null)
                    {
                        //Users
                        console.log("Searching for a user");
                        var user = res[i].username;
                        displayUserResults(user);
                    }
                    else
                    {
                      console.log("You are searching recipes");
                      var RecipeID = res[i].RecipeID;
                      var Title = res[i].Title;
                      var Username = res[i].Username;
                      console.log(RecipeID);
                      console.log(Title);
                      console.log(Username);
                      displayPostResults(RecipeID, Username, Title);
                    }
                }
            }
        }
    }
    if(document.getElementById('spoonRadio').checked) //create page for spoonacular recipe if user has selected spoonacular recipes
    {
      var query = document.getElementById("title").value;
      var options = document.getElementById("tagSelect").options;
      const promise = new Promise(function(resolve, reject){
        console.log("Entering the Promise");
        var xhttp = new XMLHttpRequest();
        xhttp.onload = function(){
          if(this.readyState == 4 && this.status == 200)
          {
            res = this.responseText;
            if(res.includes("error"))
            {
              reject("Error.");
            }
            else
            {
              console.log(res);
              setRecipeId(res);
              resolve(res);
            }
          }
        }
        xhttp.open("POST", "../get_recipe_id.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = "query=" + query;
        for(var i = 0; i < options.length; i++)
        {
          if(options[i].selected)
          {
            sendString = sendString + "&tags[]=" + options[i].value;
          }
        }
        console.log(sendString);
        xhttp.send(sendString);
      });
      promise.then(
        function(value){ create_spoon_recipes(RecipeIdArray); },
        function(error){ alert(error); }
      );
    }
    else if(document.getElementById('userRadio').checked) //call search_user if the user selected users
    {
        var search = document.getElementById("searchField").value;
        xhttp.open("POST", "../search_user.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var sendString = 'searchuser=' + search;
        xhttp.send(sendString);
        console.log(search);
        console.log("Post");
    }
    else if(document.getElementById('recipeRadio').checked) //call search_post if the user selected posts
    {
        xhttp.open("POST", "../search_recipe.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var options = document.getElementById("tagSelect").options;
        var sendString = "";
        sendString = sendString + "title=" + document.getElementById("title").value;
        for(var i = 0; i < options.length; i++)
        {
          if(options[i].selected)
          {
            sendString = sendString + "&tags[]=" + options[i].value;
          }
        }
        console.log(sendString);
        xhttp.send(sendString);
    }
    else
    {
        alert("Please select a user or post option");
    }
})
