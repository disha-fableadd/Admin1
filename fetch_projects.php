<?php
include 'db.php'; // Make sure the database connection is included

// Query to fetch the projects
$query = "SELECT p.pid, p.pname AS project_name, c.cname AS category_name, 
                 p.description, p.timeline, 
                 CONCAT(ui.fname, ' ', ui.lname) AS manager_name, 
                 GROUP_CONCAT(CONCAT(staff.fname, ' ', staff.lname) SEPARATOR ', ') AS staff_names
          FROM project p
          JOIN category c ON p.category_id = c.cid
          JOIN userss u ON p.manager_id = u.id
          JOIN user_info ui ON u.id = ui.user_id
          LEFT JOIN userss staff_u ON p.staff_id = staff_u.id
          LEFT JOIN user_info staff ON staff_u.id = staff.user_id
          GROUP BY p.pid"; // Use GROUP_CONCAT for staff members if multiple staff are assigned

$result = mysqli_query($conn, $query);

if ($result) {
    $projects = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $projects[] = $row; // Store each project in an array
    }

    echo json_encode([
        'status' => 'success',
        'data' => $projects
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch projects.'
    ]);
}

mysqli_close($conn); // Close the database connection
?>
