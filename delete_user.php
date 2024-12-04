<?php
require 'db.php';

// Check if the user ID is set and valid
if (isset($_POST['userid']) && is_numeric($_POST['userid'])) {
    $userid = (int)$_POST['userid'];

    // SQL query to delete the user
    $sql = "DELETE FROM userss WHERE id = $userid";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
}

// Close the database connection
mysqli_close($conn);
?>
