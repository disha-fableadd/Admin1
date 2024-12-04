<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
include 'db.php';

// Check if the project ID (pid) is passed via POST request
if (isset($_GET['pid'])) {
    $pid =$_GET['pid'];

    // Ensure the project ID is an integer (to prevent SQL injection)
    // $project_id = (int)$project_id;

    // Prepare the SQL query to avoid SQL injection
    $sql1 = "SELECT p.pid, p.pname, c.cname, p.description, p.timeline, 
                   um.fname AS manager_fname, um.lname AS manager_lname, 
                   us.fname AS staff_fname, us.lname AS staff_lname
            FROM project p
            JOIN category c ON p.category_id = c.cid
            INNER JOIN user_info um ON p.manager_id = um.user_id
            INNER JOIN user_info us ON p.staff_id = us.user_id
            WHERE p.pid = $pid";
        

    // Execute the query
    $result1 = mysqli_query($conn, $sql1);

    if ($result1 && mysqli_num_rows($result1) > 0) {
        // Fetch the project data
        $project = mysqli_fetch_assoc($result1);
    } else {
        // If no project found, show an error
        echo "Project not found.";
        exit;
    }
} else {
    echo "Error: Project ID (pid) is missing.";
    exit;
}
?>

<?php 

  include 'layout/header.php';
?>
 <div class="container-xxl flex-grow-1 container-p-y  ">
  <div class="row gy-6">
   

    <div class="container">
        <h2>Project Details</h2>
        <table class="table table-bordered">
            <tr>
                <th>Project Name</th>
                <td><?php echo htmlspecialchars($project['pname']); ?></td>
            </tr>
            <tr>
                <th>Category</th>
                <td><?php echo htmlspecialchars($project['cname']); ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo nl2br(htmlspecialchars($project['description'])); ?></td>
            </tr>
            <tr>
                <th>Timeline</th>
                <td><?php echo htmlspecialchars($project['timeline']); ?></td>
            </tr>
            <tr>
                <th>Manager</th>
                <td><?php echo htmlspecialchars($project['manager_fname']) . ' ' . htmlspecialchars($project['manager_lname']); ?></td>
            </tr>
            <tr>
                <th>Staff</th>
                <td><?php echo htmlspecialchars($project['staff_fname']) . ' ' . htmlspecialchars($project['staff_lname']); ?></td>
            </tr>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">Back to Project List</a>
    </div>
</div>
</div>
    <?php 
 
 include 'layout/footer.php';
 ?>


