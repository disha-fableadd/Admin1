<?php

session_start();
if (!isset($_SESSION['user_id'])) {

  header('Location: login.php');
  exit();
}


require 'db.php';

$user_id = $_SESSION['user_id'];


$roleQuery = "SELECT role FROM userss WHERE id = $user_id";
$roleResult = mysqli_query($conn, $roleQuery);
$role = mysqli_fetch_assoc($roleResult)['role'];

// Construct SQL query based on role
if ($role == 1) { // Admin: View all projects
  $sql1 = "SELECT 
              p.pname, 
              c.cname AS category, 
              p.description, 
              p.timeline, 
              CONCAT(ui_manager.fname, ' ', ui_manager.lname) AS manager_name,
              CONCAT(ui_staff.fname, ' ', ui_staff.lname) AS staff_name
           FROM project p
           JOIN category c ON p.category_id = c.cid
           JOIN userss u_manager ON p.manager_id = u_manager.id
           JOIN user_info ui_manager ON u_manager.id = ui_manager.user_id
           JOIN userss u_staff ON p.staff_id = u_staff.id
           JOIN user_info ui_staff ON u_staff.id = ui_staff.user_id";

  // Execute the query and fetch results
  $result1 = $conn->query($sql1);

  // Check if the query was successful
  if (!$result1) {
    die("Query failed: " . $conn->error);
  }
} elseif ($role == 2) { // Manager: View projects assigned to them
  $sql1 = "SELECT 
              p.pname, 
              c.cname AS category, 
              p.description, 
              p.timeline, 
              CONCAT(ui_manager.fname, ' ', ui_manager.lname) AS manager_name,
              CONCAT(ui_staff.fname, ' ', ui_staff.lname) AS staff_name
           FROM project p
           JOIN category c ON p.category_id = c.cid
           JOIN userss u_manager ON p.manager_id = u_manager.id
           JOIN user_info ui_manager ON u_manager.id = ui_manager.user_id
           JOIN userss u_staff ON p.staff_id = u_staff.id
           JOIN user_info ui_staff ON u_staff.id = ui_staff.user_id
           WHERE p.manager_id = $user_id";

  // Execute the query and fetch results
  $result1 = $conn->query($sql1);

  // Check if the query was successful
  if (!$result1) {
    die("Query failed: " . $conn->error);
  }
} elseif ($role == 3) { // Staff: View projects assigned to them
  $sql1 = "SELECT 
              p.pname, 
              c.cname AS category, 
              p.description, 
              p.timeline, 
              CONCAT(ui_manager.fname, ' ', ui_manager.lname) AS manager_name,
              CONCAT(ui_staff.fname, ' ', ui_staff.lname) AS staff_name
           FROM project p
           JOIN category c ON p.category_id = c.cid
           JOIN userss u_manager ON p.manager_id = u_manager.id
           JOIN user_info ui_manager ON u_manager.id = ui_manager.user_id
           JOIN userss u_staff ON p.staff_id = u_staff.id
           JOIN user_info ui_staff ON u_staff.id = ui_staff.user_id
           WHERE p.staff_id = $user_id";

  // Execute the query and fetch results
  $result1 = $conn->query($sql1);

  // Check if the query was successful
  if (!$result1) {
    die("Query failed: " . $conn->error);
  }
}


?>


<?php

include 'layout/header.php';

?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


<div class="container-xxl flex-grow-1 container-p-y  ">
  <div class="row gy-6">
  

    <!--/ Congratulations card -->





    <!-- Data Tables -->
  

    <div class="col-12">
      <div class="card">
        <h5 class="card-header">Projects Details</h5>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="projectTable">
              <thead>
                <tr>
                  <th>Project Name</th>
                  <th>Category</th>
                  <th>Description</th>
                  <th>Timeline</th>
                  <th>Manager</th>
                  <th>Staff</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result1->num_rows > 0) {
                  // Fetch and display each row
                  while ($row = $result1->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['pname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>" . nl2br(htmlspecialchars($row['description'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['timeline']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['manager_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['staff_name']) . "</td>";
                    echo "<td>";
                    echo "<a href='view_user.php ' class='btn btn-primary' title='View User'><i class='fas fa-eye'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='7'>No records found.</td></tr>";
                }
                ?>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>


  </div>
</div>


<?php

include 'layout/footer.php';

?>