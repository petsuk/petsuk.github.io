<?php
include 'conn.php';



$mpID = $_GET['mpID'];

$dayNames = [
    'M' => 'Mon',
    'T' => 'Tue',
    'W' => 'Wed',
    'Th' => 'Thu',
    'F' => 'Fri',
    'Sa' => 'Sat',
    'Su' => 'Sun',
];

$mp_rows = "SELECT * FROM SavedMP WHERE mpID = $mpID
ORDER BY 
CASE day
WHEN 'M' THEN 1
WHEN 'T' THEN 2
WHEN 'W' THEN 3
WHEN 'Th' THEN 4
WHEN 'F' THEN 5
WHEN 'Sa' THEN 6
WHEN 'Su' THEN 7
END";

$mp_result = mysqli_query($conn, $mp_rows);


$up_mp = "SELECT recID FROM SavedMP WHERE mpID = $mpID";


$upMP_result = mysqli_query($conn, $up_mp);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved MP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav class="navbar">
        <div class="nav-item">
            <button id="profile-btn" class="profile-btn" onclick="window.location.href='profile.php'"></button>
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
            <form method="post" action="search.php">
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

    <!-- SECTION FOR THE SAVED LIST -->
    <div class="content-wrapper-glist">
        <div class="add-weekly-wrapper">

            <div class="weekly-title">
                <div class="weekly-title-item">
                    <a href="saved.php">
                        <button class="back-btn">Back</button>
                    </a>
                </div>
                <div class="weekly-title-item">


                    <!-- Update form -->
                    <form action="preview.php" method="post">
                        <input type="submit" class="add-mp-btn" value="Add Meal Plan">
                    </form>

                    <?php

                    /*  while($row = $upMP_result->fetch_assoc()) {
                         if ($row['day'] === 'M'){
                              
                         }

                     } */

                    /* // Your database connection code here (use PDO, MySQLi, or your preferred method)
                    include 'conn.php';
                    // Perform the update operation (replace this with your actual update query)
                    
                    // Example: Update the 'your_table' by setting column1 to 'new_value'
                    $stmt = $conn->prepare("UPDATE your_table SET column1 = 'new_value'");
                    $result = $stmt->execute();

                    // Redirect back to the index.html page after the update
                    header("Location: saved.html");
                    exit(); */
                    ?>

                    <!-- <a href="saved.php">

                </a> -->
                </div>
            </div>
            <div class="weekly-mp-wrapper">
                <?php
                while ($mp_row = $mp_result->fetch_assoc()) {
                    echo '<div class="daily-recipe">';
                    $currentId = $mp_row["recID"];
                    $day = $mp_row["day"];


                    // Transform abbreviated day name to full name using the mapping array
                    $fullDayName = isset($dayNames[$day]) ? $dayNames[$day] : $day;
                    echo '<div class="daily-recipe-item">';
                    echo '<h1 class="day-name">' . $fullDayName . '</h1>';
                    echo '</div>';


                    //select recipe
                    $recipe = "SELECT r.name as name, r.video as video, u.username as username
                 FROM Recipes as r
                 JOIN UserRecipes AS ur ON ur.RecipeID = r.id
                 JOIN User AS u ON u.id = ur.UserID
                  WHERE r.id = ?";
                    $stmt = $conn->prepare($recipe);
                    $stmt->bind_param("i", $currentId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    //format the recipe info
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="daily-recipe-item">';
                        echo '<div class="s-video-container">';
                        echo '<video controls class="rounded-video">';
                        echo '<source id="videoPlayer" datacurrentid="" src="content/' . $row['video'] . '" type="video/mp4">';
                        echo '</video>';
                        echo '</div>';
                        echo '</div>';

                        echo '<div class="daily-recipe-item">';
                        echo '<h1 class="s-username">@' . $row['username'] . '</h1>';
                        echo '<h1 class="s-recipe">' . $row['name'] . '</h1>';
                    }

                    //select ingredients
                    $recipe_added = "SELECT ingredients
                                                FROM Recipes as r
                                                WHERE r.id = " . $currentId . "";

                    $result = $conn->query($recipe_added);

                    echo '<center><h1 class="s-ingredients-title">Ingredients</h1></center><hr><ul class="s-ingredients-list">';
                    while ($row = $result->fetch_assoc()) {
                        $ings = $row['ingredients'];
                        $ings = explode(';', $ings);
                        foreach ($ings as $ing) {
                            $ing = explode(',', $ing);
                            echo '<li>-' . $ing[0] . ' ' . $ing[1] . ' ' . $ing[2] . '</li>';
                        }
                    }

                    //format ingredients
                
                    /* while ($row = $result->fetch_assoc()) {
                        echo '<li>' . $row['quantity'] . ' ' . $row['unit'] . ' ' . $row['name'] . '</li>';
                    } */
                    echo '</ul>';
                    echo '</div>';
                    echo "<br>";
                    echo "</div>";
                }

                ?>
            </div>
        </div>
    </div>
</body>

</html>