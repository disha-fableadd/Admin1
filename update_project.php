<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $project_id = (int) $_POST['pid']; // Ensure project_id is an integer
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $category = (int) $_POST['category']; // Ensure category is an integer
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $timeline = mysqli_real_escape_string($conn, $_POST['timeline']);
    $manager = (int) $_POST['manager']; // Ensure manager is an integer
    $staff = (int) $_POST['staff']; // Ensure staff is an integer

    // Update the project in the database
    $updateQuery = "UPDATE project 
                    SET pname = '$project_name', 
                        category_id = '$category', 
                        description = '$description', 
                        timeline = '$timeline', 
                        manager_id = '$manager', 
                        staff_id = '$staff' 
                    WHERE pid = $project_id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<div class='alert alert-success'>Project updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>
