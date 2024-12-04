<?php
session_start();
require 'db.php';

// // Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Updated query based on new table structure
$query = "
    SELECT u.email, ui.fname, ui.lname, ui.profileimage, ui.gender, ui.contact, ui.email as user_email, ui.address 
    FROM userss u
    INNER JOIN user_info ui ON u.id = ui.user_id
    WHERE u.id = $user_id
";
$result = mysqli_query($conn, $query);

// Check if query was successful and user details were found
if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Extract user information from the result
    $user_email = $user['user_email']; // Changed to avoid conflict with $email from 'userss'
    $fname = $user['fname'];
    $lname = $user['lname'];
    $profileimage = !empty($user['profileimage']) ? "uploads/" . $user['profileimage'] : "default.png";
    $gender = $user['gender'];
    $contact = $user['contact'];
    $address = $user['address'];
} else {
    echo "Error: User details not found.";
    exit;
}
?>

<?php
// Include header layout
include 'layout/header.php';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<div class="container-xxl flex-grow-1 container-p-y">
    <h2 class="text-center my-5"> Profile</h2>

    <div class="row justify-content-center">
        <!-- Left side: User Details -->
        <div class="col-md-8">
            <div class="card shadow-lg p-4">
                <div class="card-header text-center bg-primary text-white">
                    <!-- Admin Details Header -->
                    <h4 class="text-white"> Profile</h4>
                    <p class="text-white"> User</p>
                </div>
                <div class="card-body pt-4">
                    <div class="row">
                        <!-- Admin Name -->
                        <div class="col-12 col-md-6 mb-3">
                            <p><i class="fas fa-user fa-lg"></i> <strong>User Name:</strong>
                                <?= htmlspecialchars($user['fname']) . ' ' . htmlspecialchars($user['lname']); ?></p>
                        </div>

                        <!-- Admin Email -->
                        <div class="col-12 col-md-6 mb-3">
                            <p><i class="fas fa-envelope fa-lg"></i> <strong>User Email:</strong>
                                <?= htmlspecialchars($user['user_email']); ?></p>
                        </div>

                        <!-- Gender -->
                        <div class="col-12 col-md-6 mb-3">
                            <p><i class="fas fa-venus-mars fa-lg"></i> <strong>Gender:</strong>
                                <?= ucfirst(htmlspecialchars($user['gender'])); ?></p>
                        </div>

                        <!-- Contact -->
                        <div class="col-12 col-md-6 mb-3">
                            <p><i class="fas fa-phone-alt fa-lg"></i> <strong>Contact:</strong>
                                <?= htmlspecialchars($user['contact']); ?></p>
                        </div>

                        <!-- Address -->
                        <div class="col-12 mb-3">
                            <p><i class="fas fa-map-marker-alt fa-lg"></i> <strong>Address:</strong>
                                <?= nl2br(htmlspecialchars($user['address'])); ?></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <!-- Right side: Profile Image -->
        <div class="col-md-4">
            <div class="card shadow-lg p-4">
                <div class="card-body text-center">
                    <!-- Profile Image -->
                    <img src="<?= $profileimage; ?>" alt="Profile Image" class="img-fluid rounded-circle mb-3"
                        width="150">

                    <!-- Contact Info -->
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['user_email']); ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($user['contact']); ?></p>
                    <br><br>
                    <!-- Edit Profile Button (only for Admin) -->
                    <?php if ($_SESSION['role'] == 1): ?>
                        <div class="text-center mt-4">
                            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a><br>
                            <a href="changepsw.php" class="btn btn-primary">Change Password</a>
                        </div>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] == 2): ?>
                        <div class="text-center mt-4">
                            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a><br>
                            <a href="changepsw.php" class="btn btn-primary">Change Password</a>
                        </div>
                    <?php endif; ?>
                    <?php if ($_SESSION['role'] == 3): ?>
                        <div class="text-center mt-4">
                            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a><br>
                            <a href="changepsw.php" class="btn btn-primary">Change Password</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
// Include footer layout
include 'layout/footer.php';
?>