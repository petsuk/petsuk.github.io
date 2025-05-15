<?php
include "conn.php";

if (isset($_GET['videoId'])) {
    $recID = $_GET['videoId'];
    $remove = "DELETE FROM Recipes WHERE id = $recID";

    $remove_result = mysqli_query($conn, $remove);
    if (!$remove_result) {
        die('Query failed: ' . mysqli_error($conn));
    }
    header("Location: profile.php");
}

?>