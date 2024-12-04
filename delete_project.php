<?php
require 'db.php';


if (isset($_POST['pid']) && is_numeric($_POST['pid'])) {
    $pid = (int)$_POST['pid'];  // Product ID to delete


    $sql = "DELETE FROM project WHERE pid = $pid";


    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Project deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete project.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid project ID.']);
}


mysqli_close($conn);
?>
