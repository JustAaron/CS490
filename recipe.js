var recipe = document.getElementById("recipeForm");
var formData = new FormData();
$(document).ready(function (e){
  $("#recipeForm").on("submit", function(e){
    e.preventDefault();
    var imageFile = $("#image-file")[0].files[0];
    formData.append('file', imageFile);
    formData.append('Title', $("#Title").val());
    formData.append('Ingredients', $("#Ingredients").val());
    formData.append('Steps', $("#Steps").val());
    var tags = $("#selectTag").val();
    for(var tag of tags)
    {
      formData.append('selectTags[]', tag);
    }
    $.ajax({
      url: "add_recipe.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function(data){
        if(data.includes("error"))
        {
          console.log(data);
          alert("error");
        }
        console.log(data);
        for(var values of formData.values())
        {
          console.log(values);
        }
      },
      error: function(data){
        console.log("Failure");
      }
    });
  });
});
