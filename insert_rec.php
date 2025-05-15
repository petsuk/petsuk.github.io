
<?php
// Your database connection code here (use PDO, MySQLi, or your preferred method)
include 'conn.php';
// Perform the update operation (replace this with your actual update query)

if ($_SERVER['REQUEST_METHOD'] == 'POST' ){
$name = $_POST['recipeName'];
$video = $_POST['recipeVideo']; 
// $ingredientIds = $_REQUEST['ingredientIds[]'];
// $quantities = $_REQUEST['quantities[]'];
$Steps = $_POST['recipeStep'];
$description = $_POST['recipeDescription'];

    try{
        $add_recipe = "INSERT INTO Recipes(name, userID, description, Time, Steps, video) VALUES (?, 1, ?, 30, ?)";
        $stmt = $conn->prepare($add_recipe);

        $stmt->execute([$name, $Steps, $description]);

        header("Location: profile.php");

    }

}
header("Location: profile.php");

?>