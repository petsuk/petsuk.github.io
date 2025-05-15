<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile page</title>
    <link rel="stylesheet" href="css/profile_styles.css">
</head>

<style>
    .content-wrapper {
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
        flex-direction: column;
    }
</style>

<body>
    <?php
    include 'conn.php';

    ?>
    <br><br><br>
    <div class="content-wrapper">
        <div class="clipb-content">
            <?php include 'user_profile.php' ?>

            <!-- Logout Link -->
            <br>
            <div class="logout">
                <a href="login_with_google/logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </div>

</body>

</html>