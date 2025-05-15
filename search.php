<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Page</title>
    <link rel="stylesheet" href="style.css">
</head>



<body>

    <div class="instructions-wrapper">
        <div class="background-instr">
            <div class="instructions">
                <div class="inst-wrapper-2">
                    <p class="current-instruction">Search Results</p>
                    <div class="title-underline"></div>
                </div>
            </div>
        </div>
    </div>



    <?php include 'conn.php'; ?>

    <!-- <center>
        <h1>Search Page Test</h1>
    </center> -->

    <!-- **Replace 'your_table' and 'your_column' with actual table and column names, 'Recipes' table and 'name' column** -->
    <!-- <div class="search-results"> -->
    <div class="toolContentWrapper">
        <div class="content-wrapper">
            <div class="weekly-wrapper">

                <div id="weeklyMpWrapperFY" class="weekly-mp-wrapper">

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $searchTerm = $_POST['search-input'];


                        // original query 
                        // $sql = "SELECT * FROM Recipes WHERE name LIKE '%$searchTerm%' OR Description LIKE '%$searchTerm%'";
                    
                        $sql = "SELECT name, id, video
                            FROM Recipes
                            WHERE name LIKE '%$searchTerm%' OR Description LIKE '%$searchTerm%'";

                        $result = $conn->query($sql);


                        // echo "<div class='content-wrapper'>";
                        if ($result->num_rows > 0) {


                            while ($row = $result->fetch_assoc()) {

                                // Assuming $currentDay, $currentDay['dayFull'], $currentDay['id'], $currentDay['day'], and $currentDay['url'] are already defined
                                // and $currentDay['ingrarray'] is an array of ingredients
                                // Start building the HTML string for the daily recipe div
                                $dailyRecipeDiv = '<div class="daily-recipe">';


                                // Create the second item with video
                                $dailyRecipeItem2 = '<div class="daily-recipe-item">
                                                    <div class="s-video-container">
                                                        <video controls class="rounded-video">
                                                            <source id="videoPlayerSaved" src="content/' . $row['video'] . '" type="video/mp4">
                                                        </video>
                                                    </div>
                                                </div>';

                                // Append the second item to the daily recipe div
                                $dailyRecipeDiv .= $dailyRecipeItem2;
                                // Create the third item with recipe title and ingredients list
                                $dailyRecipeItem3 = '<div class="daily-recipe-item">
                                                    <h1 class="s-recipe">' . $row['name'] . '</h1>
                                                    <strong class="s-ingredients-title">Ingredients</strong>
                                                    <ul id="ingredients-list-saved" class="s-ingredients-list">';

                                $recipe_added = "SELECT Ingredients
                                                    FROM Recipes as r
                                                    WHERE r.id = " . $row['id'] . "";

                                $result = $conn->query($recipe_added);

                                $id = $row['id'];

                                while ($row = $result->fetch_assoc()) {
                                    $ings = $row['Ingredients'];
                                    $ings = explode(';', $ings);
                                    foreach ($ings as $ing) {
                                        $ing = explode(',', $ing);
                                        $dailyRecipeItem3 .= '<li>' . $ing[0] . ' ' . $ing[1] . ' ' . $ing[2] . '</li>';
                                    }
                                    /* $dailyRecipeItem3 .= '<li>' . $row['Ingredients'] . '</li>'; */


                                }


                                // Close the third item's div
                                $dailyRecipeItem3 .= '</ul><button dataApi=\'false\' datacurrentid=' . $id . ' id=\'addButton\' class="add-button">+</button></div>';
                                // Append the third item to the daily recipe div
                                $dailyRecipeDiv .= $dailyRecipeItem3;
                                // Close the daily recipe div
                                $dailyRecipeDiv .= '</div>';
                                // Output the entire daily recipe div
                                echo $dailyRecipeDiv;
                            }

                        } else {
                            echo "<p>No results found.</p>";
                        }

                        //API Search
                        function fetchRecipes($apiKey, $term)
                        {
                            $url = 'https://api.spoonacular.com/recipes/complexSearch?apiKey=' . $apiKey . '&query=' . $term . '&number=1';

                            //Initialized cURL session
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            //Executes cURL session
                            $response = curl_exec($ch);
                            curl_close($ch);


                            if ($response) {
                                $data = json_decode($response, true);
                                // print_r($data['recipes'][0]['title']);
                                if (isset($data['results'][0])) {
                                    return $data['results'][0];
                                } else {
                                    return false;
                                }
                            } else {
                                return false;
                            }
                        }

                        //Spoonacular api key
                        $apiKey = '26e95bab9957433bb1b46f09d73c3c43';
                        $recipes = fetchRecipes($apiKey, $searchTerm);
                        echo $reciipes;


                        //Initializes empty arrays for ingredients
                        $id = $recipes['id'];
                        $image = $recipes['image'];
                        $title = $recipes['title'];

                        $resultAPI = ['id' => $id, 'url' => $image, 'title' => $title];

                        // Assuming $currentDay, $currentDay['dayFull'], $currentDay['id'], $currentDay['day'], and $currentDay['url'] are already defined
                        // and $currentDay['ingrarray'] is an array of ingredients
                        // Start building the HTML string for the daily recipe div
                        $dailyRecipeDiv = '<div class="daily-recipe">';


                        // Create the second item with video
                        $dailyRecipeItem2 = '<div class="daily-recipe-item">
                                                <div class="s-video-container">
                                                    <img id="imageSaved" class="rounded-video" src=' . $resultAPI['url'] . '>
                                                </div>
                                            </div>';

                        // Append the second item to the daily recipe div
                        $dailyRecipeDiv .= $dailyRecipeItem2;
                        // Create the third item with recipe title and ingredients list
                        $dailyRecipeItem3 = '<div class="daily-recipe-item">
                                                <h1 class="s-recipe">' . $resultAPI['title'] . '</h1>';

                        // Close the third item's div
                        $dailyRecipeItem3 .= '<button dataApi=\'true\' datacurrentid=' . $resultAPI['id'] . ' id=\'addButton\' class="add-button">+</button></div>';
                        // Append the third item to the daily recipe div
                        $dailyRecipeDiv .= $dailyRecipeItem3;
                        // Close the daily recipe div
                        $dailyRecipeDiv .= '</div>';
                        // Output the entire daily recipe div
                        echo $dailyRecipeDiv;


                        $conn->close();
                    }
                    ?>

                </div>
            </div>
        </div>
        <div class="content-wrapper-tools" id="daysContainerBox" style="display:none;">
            <h2 class="video-title-main">select day</h2>
            <div class="title-underline-tools"></div>
            <div id="daysContainer" class="days-container"></div>
        </div>
    </div>


    <!-- </div> -->


    <!-- <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="search">Search:</label>
        <input type="text" name="search" id="search" placeholder="Enter recipe name">
        <button type="submit">Search</button>
    </form> -->

    <nav class="navbar">
        <div class="nav-item">
            <img src="icon/profile.png" alt="Profile Button" class="profile-btn"
                onclick="window.location.href='profile.php'">
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
        </div>
        <div class="nav-item">
            <img src="images/logo_sauce.png" alt="Logo" class="logo">
            <!-- <button id="generate-list-btn" class="generate-btn" onclick="window.location.href='glist.php'">Generate Grocery List</button> -->
        </div>
    </nav>



</body>

</html>


<script>
    var days = ['M', 'T', 'W', 'Th', 'F', 'Sa', 'Su'];
    function removeDayOption() {
        var xhrLoadDays = new XMLHttpRequest();
        xhrLoadDays.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/on-load.php`, true);
        xhrLoadDays.onload = function () {
            if (xhrLoadDays.status >= 200 && xhrLoadDays.status < 300) {
                var data = JSON.parse(xhrLoadDays.responseText);
                data.arrayOfDays.forEach(function (day) {
                    var dow = document.getElementById(`${day}-day-btn`);
                    if (dow) {
                        dow.className = 'dow-button-selected';
                    }
                    var index = days.findIndex(function (element) {
                        return element === day;
                    });
                    if (index !== -1) {
                        days.splice(index, 1);
                    }
                });
            } else {
                // Handle request errors here
                console.error('Error:', xhrLoadDays.statusText);
            }
        };
        xhrLoadDays.send();
    }
    removeDayOption();
    var buttons = document.getElementsByClassName('add-button');
    for (var i = 0; i < buttons.length; i++) {

        buttons[i].addEventListener('click', function () {
            const container = document.getElementById('daysContainer'); // The grey container's id should be 'daysContainer'
            const add = this;

            var contentDays = document.getElementById('daysContainerBox');
            contentDays.style.display = "flex";
            // Clear existing buttons
            //container.innerHTML = '';
            // Create and append buttons for each day
            if (container.hasChildNodes()) {
                add.textContent = '+';
                container.innerHTML = '';
            } else {
                add.textContent = '';
                var dataApi = add.getAttribute('dataApi');
                days.forEach(day => {
                    let button = document.createElement('button');
                    button.textContent = day;
                    button.className = 'day-button'; // Assign a class for styling
                    button.addEventListener('click', function () {
                        var index = days.findIndex(function (element) {
                            return element === day;
                        });
                        if (index !== -1) {
                            days.splice(index, 1);
                        }
                        var currentId = add.getAttribute('datacurrentid');
                        //Send information recieved from meal-plan to saved page
                        var xhrFive = new XMLHttpRequest();
                        xhrFive.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/meal-plan.php?currentId=${currentId}&day=${day}&api=${dataApi}&apiId=${currentId}`, true);
                        xhrFive.onload = function () {
                            if (xhrFive.status >= 200 && xhrFive.status < 300) {
                                //Update saved page with recipies
                                //window.location.href = 'https://cgi.luddy.indiana.edu/~team30/for-you.php';
                            } else {
                                // Handle request errors here
                                console.error('Error:', xhrFive.statusText);
                            }
                        };
                        xhrFive.send();
                        container.innerHTML = '';
                        add.textContent = '+';
                    });
                    container.appendChild(button);
                });
            }
        });
    }
</script>