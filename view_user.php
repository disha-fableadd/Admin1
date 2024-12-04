<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
include 'db.php';

// Check if the user ID is passed via GET request
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare the SQL query to avoid SQL injection
    $sql = "SELECT u.id, ui.fname, ui.lname, u.role, ui.email, ui.contact, ui.gender, ui.profileimage, ui.address 
            FROM userss u
            JOIN user_info ui ON u.id = ui.user_id
            WHERE u.id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter to the prepared statement
        $stmt->bind_param("i", $user_id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // Fetch the user data
            $user = $result->fetch_assoc();
        } else {
            // If no user found, show an error
            echo "User not found.";
            exit;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the SQL query.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

?>

<?php 

  include 'layout/header.php';
?>
 <div class="container-xxl flex-grow-1 container-p-y  ">
  <div class="row gy-6">
   

  <div class="container">
        <h2>User Details</h2>
        <table class="table table-bordered">
            <tr>
                <th>First Name</th>
                <td><?php echo htmlspecialchars($user['fname']); ?></td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td><?php echo htmlspecialchars($user['lname']); ?></td>
            </tr>
            <tr>
                <th>Role</th>
                <td>
                    <?php
                    if ($user['role'] == 1) {
                        echo "Admin";
                    } elseif ($user['role'] == 2) {
                        echo "Manager";
                    } else {
                        echo "Staff";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
                <th>Contact</th>
                <td><?php echo htmlspecialchars($user['contact']); ?></td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?php echo htmlspecialchars($user['gender']); ?></td>
            </tr>
            <tr>
                <th>Profile Image</th>
                <td><img src="<?php echo htmlspecialchars($user['profileimage']); ?>" alt="User Image" style="width:150px; height:150px;"></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo nl2br(htmlspecialchars($user['address'])); ?></td>
            </tr>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">Back to User List</a>
    </div>


    
   

  </div>
</div>

 <?php 
 
 include 'layout/footer.php';
 ?>

<?php
// Close database connection
$conn->close();
?>
