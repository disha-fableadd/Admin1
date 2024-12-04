<?php
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $category_id = (int) $_POST['category'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $timeline = mysqli_real_escape_string($conn, $_POST['timeline']);
    $manager_id = (int) $_POST['manager'];
    $staff_id = (int) $_POST['staff'];

    $query = "INSERT INTO project (pname, category_id, description, timeline, manager_id, staff_id)
              VALUES ('$project_name', '$category_id', '$description', '$timeline', '$manager_id', '$staff_id')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success', 'message' => 'Project added successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add project.']);
    }

    mysqli_close($conn); 
}
?>
