var recipe = document.getElementById("recipeForm");
var formData = new FormData();
$k(document).ready(function (e){
$j('#selectTag').chosen();
$k("#recipeForm").on("submit", function(e){
e.preventDefault();
var imageFile = $k("#image-file")[0].files[0];
formData.append('file', imageFile);
formData.append('Title', $k("#Title").val());
formData.append('Ingredients', $k("#Ingredients").val());
formData.append('Steps', $k("#Steps").val());
var tags = $k("#selectTag").val();
for(var tag of tags)
{
  formData.append('selectTags[]', tag);
}
$k.ajax({
  url: "add_recipe.php",
  type: "POST",
  data: formData,
  contentType: false,
  processData: false,
  success: function(data){
    console.log("Success");
    for(var values of formData.values())
    {
      console.log(values);
    }
    alert("New Recipe Created");
    document.getElementById("recipeForm").reset();
  },
  error: function(data){
    console.log("Failure");
  }
});
});
});

