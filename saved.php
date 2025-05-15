<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved</title>
    <link rel="stylesheet" href="style.css">
</head>

<!-- <style>
    body {
        background-image: url(content/background2.jpg);
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding-top: 70px;
        display: flex;
        align-items: center;
        flex-direction: column;
    }
</style> -->

<body>
    <nav class="navbar">
        <div class="nav-item">
            <img src="icon/profile.png" alt="Profile Button" class="profile-btn" onclick="window.location.href='profile.php'">
        </div>
        <div class="nav-item selected">
            <button id="saved-btn" class="nav-btn" onclick="window.location.href='saved.php'">Saved</button>
            <div id="saved-indicator" class="nav-indicator"></div>
        </div>
        <div class="nav-item">
            <button id="forYouBtn" class="nav-btn" onclick="window.location.href='for-you.php'">For You</button>
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

    <!-- <div class="instructions-wrapper">
    <div class="background-instr">
        <div class="instructions">
            <div class="inst-wrapper">
                <div class="step-title">Step 1</div>
                <p class="noncurr-instruction">Select your meals for the week</p>
            </div>
            <div class="inst-wrapper-2">
                <div class="step-title-select">Steps 2&3</div>
                <p class="current-instruction">View and edit your meal plans & generate your grocery</p>
                <div class="title-underline"></div>
            </div>
        </div>
    </div>
</div> -->

    <?php // include 'conn.php';   ?>
    <div class="content-wrapper">
        <?php // include 'saved-content/week-plan.php';   ?>
        <!-- DIV FOR THE WEEKLY MEAL PLAN -->

        <div class="saved-mp-wrapper">
            <br>
            <h1 class="weekly-mp-title">Saved Meal Plans</h1>

            <?php
            include 'conn.php';
            $savedPlans = "SELECT mpID, MealDate FROM SavedMP";
            $SavedPlansResult = mysqli_query($conn, $savedPlans);

            // Check if the query was successful
            if (!$SavedPlansResult) {
                die('Query failed: ' . mysqli_error($conn));
            }

            $uniquePlans = []; // Array to hold unique mpIDs and their MealDate
            
            while ($row = mysqli_fetch_assoc($SavedPlansResult)) {
                $mpID = $row['mpID'];
                $MealDate = $row['MealDate'];

                // Use mpID as the key to ensure uniqueness
                if (!isset($uniquePlans[$mpID])) {
                    $uniquePlans[$mpID] = $MealDate;
                }
                
            }

            foreach ($uniquePlans as $mpID => $MealDate) {
                $dateTime = DateTime::createFromFormat('m-d-y', $MealDate);
                $friendlyDate = $dateTime->format('M jS Y');// Friendly format
            
                echo "<div class=\"flex-container\">
                    <div class=\"flex-item\">
                        <p class=\"weekly-date\">$friendlyDate's plan</p>
                    </div>
                    <div class=\"weekly-title-item\">
                        <form method='POST' action='saved.php'>
                        <button class='remove-btn' name='submit' type='submit'>Remove</button>
                        </form>
                        <button onclick=\"window.location.href='https://cgi.luddy.indiana.edu/~team30/preview.php?mpID={$mpID}'\" class=\"generate-btn\">View</button>
                        
                    </div>
                </div>";
            }
            if (isset($_POST['submit'])){
                $remove = "DELETE FROM SavedMP WHERE mpID = $mpID";

                $remove_result = mysqli_query($conn, $remove);
                if (!$remove_result) {
                    die('Query failed: ' . mysqli_error($conn));
                }
            }

            
            ?>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var xhrSaved = new XMLHttpRequest();
        xhrSaved.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/get-saved.php`, true);

        xhrSaved.onload = function () {
            if (xhrSaved.status >= 200 && xhrSaved.status < 300) {
                var dataSaved = JSON.parse(xhrSaved.responseText);
                const ingredientSaved = document.getElementById('ingredients-list-saved');
                const steps = document.getElementById('title-saved');
                const videoSaved = document.getElementById('videoPlayerSaved');

                console.log("onLoad");

                dataSaved.arrayOfDays.user.forEach(function (currentDay) {

                    console.log(currentDay);

                    // Create the main container div
                    const dailyRecipeDiv = document.createElement('div');
                    dailyRecipeDiv.classList.add('daily-recipe');

                    // Create the first item with day name and button
                    const dailyRecipeItem1 = document.createElement('div');
                    dailyRecipeItem1.classList.add('daily-recipe-item-day');

                    const dayNameH1 = document.createElement('h1');
                    dayNameH1.classList.add('day-name');
                    dayNameH1.textContent = currentDay.dayFull;

                    const removeButton = document.createElement('a');
                    removeButton.classList.add('rounded-button');
                    removeButton.id = 'removeBtn';
                    removeButton.textContent = 'Remove';

                    removeButton.addEventListener('click', function () {
                        var xhrRemove = new XMLHttpRequest();
                        xhrRemove.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/remove-day.php?currentId=${currentDay.id}&day=${currentDay.day}`, true);

                        xhrRemove.onload = function () {
                            if (xhrRemove.status >= 200 && xhrRemove.status < 300) {
                                window.location.href = 'https://cgi.luddy.indiana.edu/~team30/for-you.php';
                            } else {
                                // Handle request errors here
                                console.error('Error:', xhrRemove.statusText);
                            }
                        };
                        xhrRemove.send();
                    });

                    dailyRecipeItem1.appendChild(dayNameH1);
                    dailyRecipeItem1.appendChild(removeButton);

                    // Create the second item with video
                    const dailyRecipeItem2 = document.createElement('div');
                    dailyRecipeItem2.classList.add('daily-recipe-item');

                    const videoContainer = document.createElement('div');
                    videoContainer.classList.add('s-video-container');

                    const video = document.createElement('video');
                    video.controls = true;
                    video.classList.add('rounded-video');

                    const source = document.createElement('source');
                    source.id = 'videoPlayerSaved';
                    source.src = 'content/' + currentDay.url;
                    source.type = 'video/mp4';

                    video.appendChild(source);
                    videoContainer.appendChild(video);
                    dailyRecipeItem2.appendChild(videoContainer);

                    // Create the third item with recipe title and ingredients list
                    const dailyRecipeItem3 = document.createElement('div');
                    dailyRecipeItem3.classList.add('daily-recipe-item');

                    const titleSavedH1 = document.createElement('h1');
                    titleSavedH1.classList.add('s-recipe');
                    titleSavedH1.textContent = currentDay.title;

                    const ingredientsTitleH1 = document.createElement('strong');
                    ingredientsTitleH1.classList.add('s-ingredients-title');
                    ingredientsTitleH1.textContent = 'Ingredients';

                    const ingredientsListUl = document.createElement('ul');
                    ingredientsListUl.id = 'ingredients-list-saved';
                    ingredientsListUl.classList.add('s-ingredients-list');

                    dailyRecipeItem3.appendChild(titleSavedH1);
                    dailyRecipeItem3.appendChild(ingredientsTitleH1);
                    dailyRecipeItem3.appendChild(ingredientsListUl);

                    currentDay.ingrarray.forEach(function (element) {
                        const newIngredient = document.createElement('li');
                        newIngredient.textContent = element;
                        ingredientsListUl.appendChild(newIngredient);
                    });

                    // Append all items to the main container
                    dailyRecipeDiv.appendChild(dailyRecipeItem1);
                    dailyRecipeDiv.appendChild(dailyRecipeItem2);
                    dailyRecipeDiv.appendChild(dailyRecipeItem3);

                    // Finally, append the main container to the body or a specific element in your page
                    const weeklyWrapper = document.getElementById('weeklyMpWrapper');
                    weeklyWrapper.appendChild(dailyRecipeDiv);
                    // Or use document.getElementById('someElementId').appendChild(dailyRecipeDiv); to append it to a specific element

                });

                dataSaved.arrayOfDays.api.forEach(function (currentDay) {

                    console.log(currentDay);

                    // Create the main container div
                    const dailyRecipeDiv = document.createElement('div');
                    dailyRecipeDiv.classList.add('daily-recipe');

                    // Create the first item with day name and button
                    const dailyRecipeItem1 = document.createElement('div');
                    dailyRecipeItem1.classList.add('daily-recipe-item-day');

                    const dayNameH1 = document.createElement('h1');
                    dayNameH1.classList.add('day-name');
                    dayNameH1.textContent = currentDay.dayFull;

                    const removeButton = document.createElement('a');
                    removeButton.classList.add('rounded-button');
                    removeButton.id = 'removeBtn';
                    removeButton.textContent = 'Remove';

                    removeButton.addEventListener('click', function () {
                        var xhrRemove = new XMLHttpRequest();
                        xhrRemove.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/remove-day.php?currentId=${currentDay.id}&day=${currentDay.day}`, true);

                        xhrRemove.onload = function () {
                            if (xhrRemove.status >= 200 && xhrRemove.status < 300) {
                                window.location.href = 'https://cgi.luddy.indiana.edu/~team30/saved.php';
                            } else {
                                // Handle request errors here
                                console.error('Error:', xhrRemove.statusText);
                            }
                        };
                        xhrRemove.send();
                    });

                    dailyRecipeItem1.appendChild(dayNameH1);
                    dailyRecipeItem1.appendChild(removeButton);

                    // Create the second item with video
                    const dailyRecipeItem2 = document.createElement('div');
                    dailyRecipeItem2.classList.add('daily-recipe-item');

                    const videoContainer = document.createElement('div');
                    videoContainer.classList.add('s-video-container');

                    var image = document.createElement('img');
                    image.id = 'imagePlayer';
                    image.classList.add('rounded-image'); // Ensure you have CSS for this class
                    image.src = currentDay.url; // Concatenate the path with the image URL
                    image.alt = 'Description';

                    videoContainer.appendChild(image);
                    dailyRecipeItem2.appendChild(videoContainer);

                    // Create the third item with recipe title and ingredients list
                    const dailyRecipeItem3 = document.createElement('div');
                    dailyRecipeItem3.classList.add('daily-recipe-item');

                    const titleSavedH1 = document.createElement('h1');
                    titleSavedH1.classList.add('s-recipe');
                    titleSavedH1.textContent = currentDay.title;

                    const ingredientsTitleH1 = document.createElement('strong');
                    ingredientsTitleH1.classList.add('s-ingredients-title');
                    ingredientsTitleH1.textContent = 'Ingredients';

                    const ingredientsListUl = document.createElement('ul');
                    ingredientsListUl.id = 'ingredients-list-saved';
                    ingredientsListUl.classList.add('s-ingredients-list');

                    dailyRecipeItem3.appendChild(titleSavedH1);
                    dailyRecipeItem3.appendChild(ingredientsTitleH1);
                    dailyRecipeItem3.appendChild(ingredientsListUl);

                    currentDay.ingrarray.forEach(function (element) {
                        const newIngredient = document.createElement('li');
                        newIngredient.textContent = element;
                        ingredientsListUl.appendChild(newIngredient);
                    });

                    // Append all items to the main container
                    dailyRecipeDiv.appendChild(dailyRecipeItem1);
                    dailyRecipeDiv.appendChild(dailyRecipeItem2);
                    dailyRecipeDiv.appendChild(dailyRecipeItem3);

                    // Finally, append the main container to the body or a specific element in your page
                    const weeklyWrapper = document.getElementById('weeklyMpWrapper');
                    weeklyWrapper.appendChild(dailyRecipeDiv);
                    // Or use document.getElementById('someElementId').appendChild(dailyRecipeDiv); to append it to a specific element

                });


            } else {
                console.error('Error:', xhrSaved.statusText);
            }
        };

        xhrSaved.send()
        console.log("Clicked");

        document.getElementById("saveMpBtn").addEventListener('click', function () {
            if (this.getAttribute('data-saved') === "notsaved") {
                this.innerHTML = 'Meal Plan Saved';
                this.setAttribute('data-saved', 'saved');
                saveMP('saved');
            } else {
                this.innerHTML = 'Save Meal Plan'; // Plus sign
                this.setAttribute('data-saved', 'notsaved');
                saveMP('notsaved');
            }
        });


        function saveMP(saved) {
            var xhrSaveMP = new XMLHttpRequest();
            xhrSaveMP.open("GET", `https://cgi.luddy.indiana.edu/~team30/saved-content/save-meal-plan.php?saved=${saved}`, true);

            xhrSaveMP.onload = function () {
                if (xhrSaveMP.status >= 200 && xhrSaveMP.status < 300) {

                } else {
                    // Handle request errors here
                    console.error('Error:', xhrSaveMP.statusText);
                }
            };
            xhrSaveMP.send();
        }

        //open grocery list for current meal plan 
        document.getElementById('generateCurMP').addEventListener('click', function () {
            window.location.href = 'https://cgi.luddy.indiana.edu/~team30/glist.php';
        });

    });
</script>

</html>