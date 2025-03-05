<?php
ob_start();
// Include database connection file
include($_SERVER['DOCUMENT_ROOT'] . "/booking-system/admin/layouts/header.php");

// Check if the user ID is set in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare the SQL delete statement
    $sql = "DELETE FROM users WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind the user ID to the statement
        $stmt->bind_param("i", $userId);

        // Attempt to execute the statement
        if ($stmt->execute()) {
            // Redirect to the users list page with a success message
            header('Location:'.$burl.'/admin/users/index.php?');
            exit();
   
        } else {
            echo "Error: Could not execute the delete statement.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: Could not prepare the delete statement.";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Error: User ID not set.";
}

ob_end_flush();
?>