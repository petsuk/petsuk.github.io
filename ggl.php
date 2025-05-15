<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Gorcery List</title>
    <link rel="stylesheet" href="style.css">
</head>

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
        <button id="generate-list-btn" class="generate-btn" onclick="window.location.href='grocery-list.php'">Generate Grocery List</button>
    </div>
</nav>
<center>
    <i><h1 class="weekly-mp-title selected">Press the "Generate List" button</h1></i>
    <i><h1 class="weekly-mp-title selected">to generate your grocery list!</h1></i>
</center>
<br>
<div class="ggl-wrapper">
    <div class="weekly-mp-wrapper">
        <div class="weekly-title">
            <div class="weekly-title-item">
                <center>
                <h2 class="weekly-mp-title">This Week's Meal Plan</h2>
                </center>
            </div>
            <div class="weekly-title-item">
                <center>
                <h3 class="weekly-date">Oct 8th 2023</h3>
                </center>
            </div>
        </div>
        <center>
        <a href="glist.php">
        <button class="generate-btn"><i>Generate List</i></button>
        </a>
        </center>
        <div class="flex-container">
            <!-- ONE DAY THAT HAS AN EXAMPLE -->
            <div class="flex-item">
                <h1 class="day-name">Mon</h1>
            </div>
            <div class="flex-item">
            <h3 class="s-recipe">Mexican Street Tacos</h3>
            </div>
        </div>
        <div class="flex-container">
            <!-- FULL EXAMPLE -->
            <div class="flex-item">
                <h1 class="day-name">Tue</h1>
            </div>
            <div class="flex-item">
            <h3 class="s-recipe">Chicken Stirfry</h3>
            </div>
        </div>
        <!-- IF day is empty then it will not show-->
    </div>
    <div class="saved-mp-wrapper">
        <br>
        <h1 class="weekly-mp-title">Saved Meal Plans</h1>
        <div class="flex-container">
            <div class="flex-item">
                <h3 class="weekly-date">Oct 8th 2023</h3>
            </div>
            <div class="flex-item">
                <center>
                <button class="generate-btn">add to meal plan</button>
                </center>
            </div>
        </div>
        
    </div>
</div>
</body>
</html>