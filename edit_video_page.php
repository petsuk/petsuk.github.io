<?php
// Include your database connection file
require_once 'conn.php';

// Initialize variables to hold video details
$videoTitle = '';
$videoLikes = '';
$videoFile = '';
$videoId = '';

// Check if the video ID is set in the query string
if (isset($_GET['videoId'])) {
    $videoId = $_GET['videoId'];

    // Prepare a statement to select the video details
    $stmt = $conn->prepare("SELECT id, name, likes, video, ingredients FROM Recipes WHERE id = ?");
    $stmt->bind_param("i", $videoId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the video details
    if ($row = mysqli_fetch_assoc($result)) {
        $videoTitle = $row['name'];
        $videoLikes = $row['likes'];
        $videoFile = $row['video'];
        $ingredients = $row['ingredients'];
    } else {
        echo "Video not found.";
    }

    $stmt->close();
} else {
    echo "No video ID provided.";
}
// start_session();
// //On page 1
// $_SESSION['evidId'] = $videoId;
?>

<!-- new  -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
    <!-- Link to your external CSS file -->
    <link rel="stylesheet" href="profile_styles.css">

</head>
<nav class="navbar">
    <div class="nav-item">
        <img src="icon/profile.png" alt="Profile Button" class="profile-btn"
            onclick="window.location.href='profile.php'">
    </div>
    <div class="nav-item">
        <button id="saved-btn" class="nav-btn" onclick="window.location.href='saved.php'">Saved</button>
        <div id="saved-indicator" class="nav-indicator"></div>
    </div>
    <div class="nav-item selected">
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

<style>
    .clipb-top-edit {
        background-color: white;
        border-radius: 10px;
        width: fit-content;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 50px;
        padding: 70px;
        padding-top: 50px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
</style>

<body>
    <div class="content-wrapper">
        <div class="clipb-top-edit">

            <div class="flex-container-edit-profile">
                <div class="flex-item-edit">
                    <a href="profile.php"><img src="images/BawiThawng.JPG" alt="profile image" class="userProfile"></a>
                </div>
                <div class="flex-item-edit">
                    <?php
                    $user = "SELECT id, email FROM Users ORDER BY modified DESC";
                    $stmt = $conn->prepare($user);
                    $stmt->execute();
                    $user_result = $stmt->get_result();
                    if ($user_result && $user_result->num_rows > 0) {
                        while ($row = $user_result->fetch_assoc()) {
                            $username = explode('@',$row['email']);

                            $userID = $row['id'];
                            echo "@" . $username[0];
                            echo $userID;
                    
                            break;
                        }
                    }

                    /* $currentId = isset($_GET['currentId']) ? (int) $_GET['currentId'] : 0;
                    $UserQuery = "SELECT username FROM User WHERE email = 'jhl@gmail.com'";
                    $StatementQuery = $conn->prepare($UserQuery);
                    $StatementQuery->execute();
                    $result1 = $StatementQuery->get_result();
                    while ($row = mysqli_fetch_assoc($result1)) {
                        // echo "<img src='" . getUserProfileImage($conn, $currentId) . "' alt='Profile Image' class='profile-image'>";
                        // echo "<a href='profile.php?userId=" . $currentId . "'>@" . $row['username'] . "</a>";
                        echo "@" . $row['username'];
                        // echo "@" . $row['username'];
                    } */
                    ?>
                </div>
            </div>

            <div class="flex-container-edit">
                <!-- <div class="flex-item">
                <center>
                <--?php
                $currentId = isset($_GET['currentId']) ? (int) $_GET['currentId'] : 0;
                $UserQuery = "SELECT username FROM User WHERE email = 'jhl@gmail.com'";
                $StatementQuery = $conn->prepare($UserQuery);
                $StatementQuery->execute();
                $result1 = $StatementQuery->get_result();
                while($row = mysqli_fetch_assoc($result1)){
                    // echo "<img src='" . getUserProfileImage($conn, $currentId) . "' alt='Profile Image' class='profile-image'>";

                    // echo "@" . $row['username'];
                }
                ?>
                </center>
            </div> -->
                <div class="flex-item-edit">
                    <center>
                        <?php if ($videoId): ?>
                            <div class='video-item-edit'>
                                <h2 class='video-title'>
                                    <?php echo htmlspecialchars($videoTitle); ?>
                                </h2>
                                <br>
                                <div class='video-thumbnail'>
                                    <video controls class='rounded-video'>
                                        <source src='content/<?php echo htmlspecialchars($videoFile); ?>' type='video/mp4'>
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                <!-- <h4 class='video-title'><--?php echo htmlspecialchars($videoTitle); ?></h4> -->
                            </div>
                        <?php endif; ?>
                    </center>
                </div>
            </div>

            <!-- LIKES VIDEOS -->
            <div class="flex-container-edit">
                <div class="flex-item-edit">
                    <center>

                        <?php if ($videoId): ?>
                            <div class='video-item-edit'>
                                <!-- <div class='video-thumbnail'>
                            <video controls class='rounded-video'>
                                <source src='content/<--?php echo htmlspecialchars($videoFile); ?->' type='video/mp4'>
                                Your browser does not support the video tag.
                            </video>
                        </div> -->
                                <!-- <h4 class='video-title'><--?php echo htmlspecialchars($videoTitle); ?-></h4> -->
                                <!-- Display Ingredients -->
                                <div class='ingredients-list'>
                                    <h2>Ingredients:</h2>
                                    <br>
                                    <ul>
                                        <?php

                                        // Prepare a statement to select the ingredients for the recipe
                                        /* $ingredientStmt = $conn->prepare("
                                    SELECT Ingredients.name, RecipeIngredients.quantity, Ingredients.unit
                                    FROM RecipeIngredients
                                    JOIN Ingredients ON RecipeIngredients.IngredientID = Ingredients.id
                                    WHERE RecipeIngredients.RecipeID = ?
                                ");
                                        $ingredientStmt->bind_param("i", $videoId);
                                        $ingredientStmt->execute();
                                        $ingredientResult = $ingredientStmt->get_result(); */

                                        $recipe_added = "SELECT Ingredients
                                        FROM Recipes as r
                                        WHERE r.id = " . $_GET['videoId'] . "";

                                        $result = $conn->query($recipe_added);


                                        while ($row = $result->fetch_assoc()) {
                                            $ings = $row['Ingredients'];
                                            $ings = explode(';', $ings);
                                            foreach ($ings as $ing) {
                                                $ing = explode(',', $ing);
                                                $ing_str .= '<li>' . $ing[0] . ' ' . $ing[1] . ' ' . $ing[2] . '</li>';
                                            }
                                        }

                                        // Check if there are any ingredients and display them
                                        /* if ($ingredientResult->num_rows > 0) {
                                            while ($ingredientRow = mysqli_fetch_assoc($ingredientResult)) {
                                                echo "<li>" . htmlspecialchars($ingredientRow['quantity']) . " " . htmlspecialchars($ingredientRow['unit']) . " " . htmlspecialchars($ingredientRow['name']) . "</li>";
                                            }
                                        } else {
                                            echo "<li>No ingredients found for this recipe.</li>";
                                        }

                                        $ingredientStmt->close(); */
                                        echo $ing_str;
                                        ?>

                                    </ul>

                                    <!-- <p style="font-family: 'Futura', sans-serif;">
                                        <?php /* echo $ing_str; */ ?>
                                    </p> -->
                                </div>
                            </div>
                        <?php endif; ?>








                    </center>
                </div>
                <div class="flex-item-edit">
                    <center>
                        <a href="upload_video_page.php?videoId=<?php echo $videoId; ?>" class="edit-button">Edit</a>
                        <!-- <--?php $videoId; ?> -->
                        <!-- echo "<a href='edit_video_page.php?videoId=" . $videoId . "' class='edit-video-link'>Edit</a>"; -->
                    </center>
                </div>
            </div>
        </div>
    </div>
</body>

</html>