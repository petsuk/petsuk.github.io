<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nav Bar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar">
    <div class="nav-item">
        <!-- <button id="profile-btn" class="profile-btn" onclick="window.location.href='profile.php'"></button> -->
        <img src="icon/profile.png" alt="Profile Button" class="profile-btn" onclick="window.location.href='profile.php'">
    </div>
    <div class="nav-item">
        <button id="savedBtn" class="nav-btn">Saved</button>
        <div id="saved-indicator" class="nav-indicator"></div>
    </div>
    <div class="nav-item selected">
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
</body>
</html>