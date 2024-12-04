<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the current user information
$query = "
    SELECT u.email, ui.fname, ui.lname, ui.profileimage, ui.gender, ui.contact, ui.address
    FROM userss u
    INNER JOIN user_info ui ON u.id = ui.user_id
    WHERE u.id = $user_id
";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    // Extract user information from the result
    $fname = $user['fname'];
    $lname = $user['lname'];
    $profileimage = !empty($user['profileimage']) ? "uploads/" . $user['profileimage'] : "default.png";
    $gender = $user['gender'];
    $contact = $user['contact'];
    $address = $user['address'];
    $user_email = $user['email'];
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
    <h2 class="text-center my-5">Edit Profile</h2>

    <div class="row justify-content-center">
        <!-- Edit Profile Form -->
        <div class="col-md-8">
            <div class="card shadow-lg p-4">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Edit Profile</h4>
                </div>
                <div class="card-body pt-4">
                    <form id="editProfileForm" method="POST" enctype="multipart/form-data">
                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="fname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="fname" name="fname"
                                value="<?= htmlspecialchars($fname); ?>" >
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="lname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lname" name="lname"
                                value="<?= htmlspecialchars($lname); ?>" >
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($user_email); ?>" >
                        </div>

                        <!-- Gender -->
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" id="gender" name="gender" >
                                <option value="male" <?= ($gender == 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?= ($gender == 'female') ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?= ($gender == 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <!-- Contact -->
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact" name="contact"
                                value="<?= htmlspecialchars($contact); ?>" >
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="4"
                                ><?= htmlspecialchars($address); ?></textarea>
                        </div>

                        <!-- Profile Image -->
                        <div class="mb-3">
                            <label for="profileimage" class="form-label">Profile Image</label>
                            <div class="mb-3">
                                <img src="<?= htmlspecialchars($profileimage); ?>" alt="Profile Picture"
                                    class="img-thumbnail" style="max-width: 150px;">
                            </div>
                            <input type="file" class="form-control" id="profileimage" name="profileimage">
                            <small class="form-text text-muted">Leave empty to keep the current profile image.</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                    <div id="responseMessage" class="mt-3 text-center"></div>
                    <!-- For displaying the response message -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer layout
include 'layout/footer.php';
?>
<script>
    $(document).ready(function () {
        // Validate form inputs
        function validateForm() {
            // Clear previous error messages
            $(".error-message").remove();

            const fname = $("#fname").val().trim();
            const lname = $("#lname").val().trim();
            const email = $("#email").val().trim();
            const gender = $("#gender").val();
            const contact = $("#contact").val().trim();
            const address = $("#address").val().trim();

            let isValid = true;

            // Validate first name
            if (fname === '') {
                $("#fname").after('<div class="error-message text-danger">First name is .</div>');
                isValid = false;
            }

            // Validate last name
            if (lname === '') {
                $("#lname").after('<div class="error-message text-danger">Last name is .</div>');
                isValid = false;
            }

            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                $("#email").after('<div class="error-message text-danger">Enter a valid email address.</div>');
                isValid = false;
            }

            // Validate gender selection
            if (gender === '') {
                $("#gender").after('<div class="error-message text-danger">Please select your gender.</div>');
                isValid = false;
            }

            // Validate contact number (at least 10 digits)
            const contactRegex = /^[0-9]{10,}$/;
            if (!contactRegex.test(contact)) {
                $("#contact").after('<div class="error-message text-danger">Contact number must be at least 10 digits.</div>');
                isValid = false;
            }

            // Validate address
            if (address === '') {
                $("#address").after('<div class="error-message text-danger">please enter youur address</div>');
                isValid = false;
            }

            return isValid;
        }

        // Handle form submission
        $("#editProfileForm").submit(function (e) {
            e.preventDefault(); // Prevent the form from submitting the traditional way

            if (validateForm()) {
                const formData = new FormData(this); // Get the form data, including the image

                $.ajax({
                    url: 'edit_profile_action.php', // The PHP script that will handle the update
                    type: 'POST',
                    data: formData,
                    processData: false, // Don't process the files
                    contentType: false, // Don't set content type
                    success: function (response) {
                        // If the update was successful, show a success message
                        $('#responseMessage').html('<div class="alert alert-success">Profile updated successfully!</div>');
                        setTimeout(function () {
                            window.location.href = 'profile1.php';
                        }, 2000);
                    },
                    error: function (xhr, status, error) {
                        // If an error occurred, show an error message
                        $('#responseMessage').html('<div class="alert alert-danger">Error: ' + xhr.responseText + '</div>');
                    }
                });
            }
        });
    });
</script>
