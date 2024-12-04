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


if ($role == 1) { // Admin: Show all user data
  $sql2 = "SELECT ui.fname, ui.lname, u.role, ui.gender, ui.email, ui.contact, ui.profileimage, ui.address 
          FROM userss u 
          JOIN user_info ui ON u.id = ui.user_id";
} elseif ($role == 2) { // Manager: Show their details and all staff
  $sql2 = "SELECT ui.fname, ui.lname, u.role, ui.gender, ui.email, ui.contact, ui.profileimage, ui.address 
          FROM userss u 
          JOIN user_info ui ON u.id = ui.user_id
          WHERE u.role = 3 OR u.id = $user_id"; // Staff or current manager
} elseif ($role == 3) { // Staff: Show their details and all managers
  $sql2 = "SELECT ui.fname, ui.lname, u.role, ui.gender, ui.email, ui.contact, ui.profileimage, ui.address 
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
                    echo "<a href='view_user.php ' class='btn btn-primary' title='View User'><i class='fas fa-eye'></i></a>";
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

   

  </div>
</div>


<?php

include 'layout/footer.php';

?>