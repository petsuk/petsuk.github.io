<?php
// Include database connection
require_once 'conn.php';

// Start the session or make sure it's already started
session_start();

// Check if there is a file and a user session
if (isset($_FILES['profilePic'], $_SESSION['userId'])) {
    $userId = $_SESSION['userId']; // The user's ID should be stored in the session
    $uploadDir = 'images/'; // Directory where profile pictures will be stored
    $uploadFile = $uploadDir . basename($_FILES['profilePic']['name']);
    
    // Check if the file is an actual image
    $check = getimagesize($_FILES['profilePic']['tmp_name']);
    if($check !== false) {
        // Attempt to upload the file
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $uploadFile)) {
            // Prepare the SQL statement to update the user's profile image path
            $stmt = $conn->prepare("UPDATE User SET profileImage = ? WHERE id = ?");
            $stmt->bind_param("si", $uploadFile, $userId);
            
            // Execute the statement and check if it's successful
            if ($stmt->execute()) {
                echo "The file has been uploaded.";
                // Redirect back to the profile page or wherever appropriate
                header("Location: profile.php");
            } else {
                echo "Sorry, there was an error updating your profile.";
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
} else {
    echo "No file or user session found.";
}

?>
