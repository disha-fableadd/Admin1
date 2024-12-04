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






if ($role == 1) {
  // Admin: Total users, projects, and categories
  $user_query = "SELECT COUNT(*) AS total_users FROM userss ";
  $user_result = mysqli_query($conn, $user_query);
  if ($user_result) {
    $user_data = mysqli_fetch_assoc($user_result);
    $total_users = $user_data['total_users'];
  }

  $project_query = "SELECT COUNT(*) AS total_projects FROM project ";
  $project_result = mysqli_query($conn, $project_query);
  if ($project_result) {
    $project_data = mysqli_fetch_assoc($project_result);
    $total_projects = $project_data['total_projects'];
  }

  $category_query = "SELECT COUNT(*) AS total_categories FROM category";
  $category_result = mysqli_query($conn, $category_query);
  if ($category_result) {
    $category_data = mysqli_fetch_assoc($category_result);
    $total_categories = $category_data['total_categories'];
  }

} elseif ($role == 2) {
  // Manager: Total projects they manage and total staff
  $project_query = "SELECT COUNT(*) AS total_projects FROM project WHERE manager_id = $user_id";
  $project_result = mysqli_query($conn, $project_query);
  if ($project_result) {
    $project_data = mysqli_fetch_assoc($project_result);
    $total_projects = $project_data['total_projects'];
  }

  $staff_query = "SELECT COUNT(*) AS total_staff FROM userss WHERE role = 3";
  $staff_result = mysqli_query($conn, $staff_query);
  if ($staff_result) {
    $staff_data = mysqli_fetch_assoc($staff_result);
    $total_staff = $staff_data['total_staff'];
  }

} elseif ($role == 3) {
  // Staff: Total projects they are assigned and total managers
  $project_query = "SELECT COUNT(*) AS total_projects FROM project WHERE staff_id = $user_id";
  $project_result = mysqli_query($conn, $project_query);
  if ($project_result) {
    $project_data = mysqli_fetch_assoc($project_result);
    $total_projects = $project_data['total_projects'];
  }

  $manager_query = "SELECT COUNT(*) AS total_managers FROM userss WHERE role = 2";
  $manager_result = mysqli_query($conn, $manager_query);
  if ($manager_result) {
    $manager_data = mysqli_fetch_assoc($manager_result);
    $total_managers = $manager_data['total_managers'];
  }
} else {
  die("Invalid role.");
}


if ($role == 1) { // Admin: Show all user data
  $sql2 = "SELECT u.id,ui.fname, ui.lname, u.role, ui.gender, ui.email, ui.contact, ui.profileimage, ui.address 
          FROM userss u 
          JOIN user_info ui ON u.id = ui.user_id ORDER BY user_id DESC";
} elseif ($role == 2) { // Manager: Show their details and all staff
  $sql2 = "SELECT u.id,ui.fname, ui.lname, u.role, ui.gender, ui.email, ui.contact, ui.profileimage, ui.address 
          FROM userss u 
          JOIN user_info ui ON u.id = ui.user_id
          WHERE u.role = 3 OR u.id = $user_id"; // Staff or current manager
} elseif ($role == 3) { // Staff: Show their details and all managers
  $sql2 = "SELECT u.id,ui.fname, ui.lname, u.role, ui.gender, ui.email, ui.contact, ui.profileimage, ui.address 
          FROM userss u 
          JOIN user_info ui ON u.id = ui.user_id
          WHERE u.role = 2 OR u.id = $user_id"; // Managers or current staff
}

// Execute query
$result2 = $conn->query($sql2);

// Check query execution
if (!$result2) {
  die("Query failed: " . $conn->error);
}




// Construct SQL query based on role
if ($role == 1) { // Admin: View all projects
  $sql1 = "SELECT 
  p.pid,
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
           JOIN user_info ui_staff ON u_staff.id = ui_staff.user_id ORDER BY pid DESC";

  // Execute the query and fetch results
  $result1 = $conn->query($sql1);

  // Check if the query was successful
  if (!$result1) {
    die("Query failed: " . $conn->error);
  }
} elseif ($role == 2) { // Manager: View projects assigned to them
  $sql1 = "SELECT 
  p.pid,
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
  p.pid,
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
    <?php if ($role == 1): ?>
      <!-- Congratulations card -->
      <div class="col-md-12 col-lg-4">
        <div class="card bg-label-warning">
          <div class="card-body text-nowrap">
            <h5 class="card-title mb-0 flex-wrap text-nowrap">Total Users</h5>
            <h4 class="text-primary mb-0"><?= number_format($total_users); ?></h4>
            <p class="mb-2">78% of target 🚀</p>
            <a href="alluser.php" class="btn btn-sm btn-primary rounded-pill">View users</a>
          </div>
          <img src="uploads/user.jpg" class="position-absolute bottom-0 end-0 me-5 mb-5" width="100" alt="view sales" />
        </div>
      </div>
      <div class="col-md-12 col-lg-4">
        <div class="card bg-label-success">
          <div class="card-body text-nowrap">
            <h5 class="card-title mb-0 flex-wrap text-nowrap">Total Projects</h5>
            <h4 class="text-primary mb-0"><?= number_format($total_projects); ?></h4>
            <p class="mb-2">78% of target 🚀</p>
            <a href="allproject.php" class="btn btn-sm btn-primary rounded-pill">View Projects</a>
          </div>
          <img src="uploads/pro.jpg" class="position-absolute bottom-0 end-0 me-5 mb-5 " width="100" alt="view sales" />
        </div>
      </div>
      <div class="col-md-12 col-lg-4">
        <div class="card bg-label-secondary">
          <div class="card-body text-nowrap">
            <h5 class="card-title mb-0 flex-wrap text-nowrap">Total Categories</h5>
            <h4 class="text-primary mb-0"><?= number_format($total_categories); ?></h4>
            <p class="mb-2">78% of target 🚀</p>
            <a href="category.php" class="btn btn-sm btn-primary rounded-pill">View Categories</a>
          </div>
          <img src="uploads/category.png" class="position-absolute bottom-0 end-0 me-5 mb-5" width="100"
            alt="view sales" />
        </div>
      </div>
    <?php endif; ?>
    <?php if ($role == 2): ?>
      <!-- Congratulations card -->
      <div class="col-md-12 col-lg-6">
        <div class="card bg-label-warning">
          <div class="card-body text-nowrap">
            <h5 class="card-title mb-0 flex-wrap text-nowrap">Total Staff</h5>
            <h4 class="text-primary mb-0"><?= number_format($total_staff); ?></h4>
            <p class="mb-2">78% of target 🚀</p>
            <a href="#" class="btn btn-sm btn-primary rounded-pill">View Staff</a>
          </div>
          <img src="uploads/user.jpg" class="position-absolute bottom-0 end-0 me-5 mb-5" width="100" alt="view sales" />
        </div>
      </div>
      <div class="col-md-12 col-lg-6">
        <div class="card bg-label-success">
          <div class="card-body text-nowrap">
            <h5 class="card-title mb-0 flex-wrap text-nowrap">Total Projects</h5>
            <h4 class="text-primary mb-0"><?= number_format($total_projects); ?></h4>
            <p class="mb-2">78% of target 🚀</p>
            <a href="allproject.php" class="btn btn-sm btn-primary rounded-pill">View Projects</a>
          </div>
          <img src="uploads/pro.jpg" class="position-absolute bottom-0 end-0 me-5 mb-5 " width="100" alt="view sales" />
        </div>
      </div>
    <?php endif; ?>
    <?php if ($role == 3): ?>
      <!-- Congratulations card -->
      <div class="col-md-12 col-lg-6">
        <div class="card bg-label-warning">
          <div class="card-body text-nowrap">
            <h5 class="card-title mb-0 flex-wrap text-nowrap">Total Managers</h5>
            <h4 class="text-primary mb-0"><?= number_format($total_managers); ?></h4>
            <p class="mb-2">78% of target 🚀</p>
            <a href="alluser.php" class="btn btn-sm btn-primary rounded-pill">View Managers</a>
          </div>
          <img src="uploads/user.jpg" class="position-absolute bottom-0 end-0 me-5 mb-5" width="100" alt="view sales" />
        </div>
      </div>
      <div class="col-md-12 col-lg-6">
        <div class="card bg-label-success">
          <div class="card-body text-nowrap">
            <h5 class="card-title mb-0 flex-wrap text-nowrap">Total Projects</h5>
            <h4 class="text-primary mb-0"><?= number_format($total_projects); ?></h4>
            <p class="mb-2">78% of target 🚀</p>
            <a href="#" class="btn btn-sm btn-primary rounded-pill">View Projects</a>
          </div>
          <img src="uploads/pro.jpg" class="position-absolute bottom-0 end-0 me-5 mb-5 " width="100" alt="view sales" />
        </div>
      </div>
    <?php endif; ?>

    <!--/ Congratulations card -->





    <!-- Data Tables -->
    <div class="col-12">
      <div class="card ">
        <h5 class="card-header">Users Details</h5>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="userTable">
              <thead>
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Role</th>
                  <th>Gender</th>
                  <th>Email</th>
                  <th>Contact</th>
                  <th>Image</th>
                  <th>Address</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($result2->num_rows > 0) {
                  // Fetch and display each row
                  while ($row = $result2->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lname']) . "</td>";
                    echo "<td>" . ($row['role'] == 1 ? 'Admin' : ($row['role'] == 2 ? 'Manager' : 'Staff')) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                    echo "<td><img src='" . htmlspecialchars($row['profileimage']) . "' alt='User Image' style='width:50px; height:50px; border-radius:50%;'></td>";
                    echo "<td>" . nl2br(htmlspecialchars($row['address'])) . "</td>";
                    echo "<td>";
                    echo "<a href='view_user.php?id=" . $row['id'] . " ' class='btn btn-primary' title='View User'><i class='fas fa-eye'></i></a>";
                    echo "</td>";
            
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='9'>No records found.</td></tr>";
                }
                ?>

              </tbody>
            </table>



          </div>
        </div>
      </div>
    </div>

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
                    echo "<a href='view_project.php?pid=" . $row['pid'] . " ' class='btn btn-primary' title='View User'><i class='fas fa-eye'></i></a>";
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