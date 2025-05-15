<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>For You</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php 
include 'includes.php';
include 'conn.php'; 
?>

<div class="instructions-wrapper">
    <div class="background-instr">
        <div class="instructions">
            <div class="inst-wrapper">
                <div class="step-title-select">Step 1</div>
                <p class="current-instruction">Select your meals for the week</p>
            </div>
            <div class="inst-wrapper-3">
                <div class="step-title-select">Steps 2&3</div>
                <p class="current-instruction">View and edit your meal plans & generate your grocery list</p>
            </div>
        </div>
    </div>
</div>

<div class="toolContentWrapper">
    <div class="content-wrapper">

        <div class="weekly-wrapper" id="weekly-wrapper">
            <div class="weekly-title">
                <div class="weekly-title-item" id="weekly-title-item">
                </div>
            </div>
            <div id="weeklyMpWrapperFY" class="weekly-mp-wrapper"></div>
        </div>

        <?php include 'for-you-content/video-main.php'?>

        <?php include 'for-you-content/ingredients-steps.php'?>
    </div>

    <div class="content-wrapper-tools">
        <h2 class="video-title-main">tools</h2>
        <div class="title-underline-tools"></div>
        <br>
        <button id="generate-list-btn" class="generate-btn-tools" onclick="window.location.href='glist.php'">generate grocery list</button>
        <br>
        <button id="showMpBtn" class="generate-btn-fy" style="display: flex; justify-content: center;">view meal plan</button>
        <button id="contentBtn" class="generate-btn-fy" style="display: none;justify-content: center;background-color: red;color: white;">back to recipes</button>
        <br>
        <button id="saveMpBtn" data-saved="notsaved" class="generate-btn-fy" style="display: flex; justify-content: center;">save meal plan</button>
    </div>
</div>

</body>
<script src="java.js"></script>
</html>
