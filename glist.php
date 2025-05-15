<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery List</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
session_start();
$dayDetails = $_SESSION['dayDetails'];
include 'conn.php';

$mp_ID = $GET['mpID'];
?>


<body>

<nav class="navbar">
    <div class="nav-item">
        <img src="icon/profile.png" alt="Profile Button" class="profile-btn" onclick="window.location.href='profile.php'">
    </div>
    <div class="nav-item">
        <button id="saved-btn" class="nav-btn" onclick="window.location.href='saved.php'">Saved</button>
        <div id="saved-indicator" class="nav-indicator"></div>
    </div>
    <div class="nav-item">
        <button id="for-you-btn" class="nav-btn" onclick="window.location.href='for-you.php'">For You</button>
        <div class="nav-indicator"></div>
    </div>
    <div class="nav-item search-bar">
        <form method="post" action= "search.php">
        <input type="text" id="search-input" name="search-input" placeholder="Search">
        <input type="submit" style="display: none" />
        <!-- <input type="text" id="submit" placeholder="Submit">
        <label id="submit">Search</label> -->
        </form>
    </div>
    <div class="nav-item">
        <img src="images/logo_sauce.png" alt="Logo" class="logo">
        <!-- <button id="generate-list-btn" class="generate-btn" onclick="window.location.href='glist.php'">Generate Grocery List</button> -->
    </div>
</nav>

<div class="content-wrapper-glist">

<center>
<div class="clipb-top">
    <h1 class="weekly-mp-title"><i>Your Week's Grocery List</i></h1>
    <hr>
</div>
</center>
 

<div class="clipb-content">
    <!-- EACH FLEX CONTAINER IS A ROW FOR THE GROCERY LIST -->
    <!-- <div class="flex-container">
        <div class="flex-item">
            <label class="l-container">
                <input type="checkbox">
                <span class="checkmark"></span>
            </label>
        </div>
        <div class="flex-item">
            <p style="font-family: 'Futura', sans-serif;">3 cups</p>
        </div>
        <div class="flex-item">
            <p style="font-family: 'Futura', sans-serif;">Cheddar Cheese</p>
        </div>
        <div class="flex-item">
            <a style="color: red; font-family: 'Futura', sans-serif;">remove</a>
        </div>
    </div> -->
<?php
if ($mp_ID){
    $saved = "SELECT mpID,recID, day, MealDate FROM SavedMP WHERE mpID = $mp_ID";
    $saved_result = $conn -> query($saved);
    //select ingredients
    $recipe_added = "SELECT Ingredients FROM Recipes WHERE id = ?";

    $stmt = $conn->prepare($recipe_added);
    $stmt->bind_param("i", $currentId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $ing_row = explode(';', $row['Ingredients']);
        foreach ($ing_row as $ing) {
            $fix = str_replace(',', ' ', $ing);
            echo '<div class="flex-container">
            <div class="flex-item">
                <label class="l-container">
                    <input type="checkbox">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="flex-item">';
            echo $fix;
            echo '</div>
            </div>';

        }
    }

}

else{
    $mp_rows = "SELECT recID, day FROM MealPlan
                ORDER BY 
                CASE day
                    WHEN 'M' THEN 1
                    WHEN 'T' THEN 2
                    WHEN 'W' THEN 3
                    WHEN 'Th' THEN 4
                    WHEN 'F' THEN 5
                    WHEN 'Sa' THEN 6
                    WHEN 'Su' THEN 7
                END ASC";

    $mp_result = $conn -> query($mp_rows);
    while($mp_row = $mp_result->fetch_assoc()) {
        $currentId = $mp_row["recID"];
        $day = $mp_row["day"];


        //select ingredients
        $recipe_added = "SELECT Ingredients FROM Recipes WHERE id = ?";

        $stmt = $conn->prepare($recipe_added);
        $stmt->bind_param("i", $currentId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $ing_row = explode(';', $row['Ingredients']);
            foreach ($ing_row as $ing) {
                $fix = str_replace(',', ' ', $ing);
                echo '<div class="flex-container">
                <div class="flex-item">
                    <label class="l-container">
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="flex-item">';
                echo $fix;
                echo '</div>
                </div>';

            }
        }
    } 
    foreach ($dayDetails as $day) {
        $ingr = $day['ingrarray'];
        foreach ($ingr as $item) {
            echo '<div class="flex-container">';
            echo    '<div class="flex-item">';
            echo        '<label class="l-container">';
            echo            '<input type="checkbox">';
            echo            '<span class="checkmark"></span>';
            echo        '</label>';
            echo    '</div>';
            echo    '<div class="flex-item">';
            echo        '<p>' . $item . '</p>';
            echo    '</div>';
            echo '</div>';
        }
    }
}
?>
</div>
<br>
<center>
    <br>
    <br>
    <form action="Export.php" method="get">
        <input type="hidden" name="mpID" value="<?php echo $mp_ID; ?>">
        <button type="submit" class="export-btn">Export to CSV</button>
    </form>
</center>
<br>
<center>
    <!-- <form action="export-pdf.php" method="get">
        <input type="hidden" name="mpID" value="<?php echo $mp_ID; ?>">
        <button type="submit" class="export-btn">Export to DPF</button>
    </form> -->
</center>
<br>
<center>
    <br>
    <form action="for-you.php" method="post">
        <button type="submit" class="done-btn">Done</button>
    </form>
</center>
</div>

</body>
</html>