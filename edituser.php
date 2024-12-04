<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'db.php';


if (isset($_GET['id'])) {
    $userId = (int) $_GET['id'];


    $query = "
        SELECT ui.*, u.email, u.role, u.created_at
        FROM user_info ui
        INNER JOIN userss u ON ui.user_id = u.id
        WHERE ui.uid = $userId
    ";


    $result = mysqli_query($conn, $query);


    if (!$result || mysqli_num_rows($result) == 0) {
        echo "ID not found or invalid.";
        exit();
    }


    $user = mysqli_fetch_assoc($result);



}
?>

<?php include 'layout/header.php'; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-9 mx-auto">
        <div class="card mb-6">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mx-auto">Edit User</h3>
            </div>
            <div class="card-body">
                <form id="edit-user-form" enctype="multipart/form-data">
                    <input type="hidden" name="uid" id="uid"
                        value="<?php echo isset($user['uid']) ? $user['uid'] : ''; ?>">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fname" class="input-group-text"><i
                                            class="ri-user-line ri-20px"></i></span>
                                    <input type="text" class="form-control" id="fname" name="fname"
                                        placeholder="First Name" aria-label="First Name"
                                        aria-describedby="basic-icon-default-fname"
                                        value="<?php echo isset($user['fname']) ? htmlspecialchars($user['fname']) : ''; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-lname" class="input-group-text"><i
                                            class="ri-user-line ri-20px"></i></span>
                                    <input type="text" class="form-control" id="lname" name="lname"
                                        placeholder="Last Name" aria-label="Last Name"
                                        aria-describedby="basic-icon-default-lname"
                                        value="<?php echo isset($user['lname']) ? htmlspecialchars($user['lname']) : ''; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gender (Radio Buttons) -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <div class="input-group input-group-merge">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender-male"
                                            value="male" <?php echo (isset($user['gender']) && $user['gender'] == 'male') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="gender-male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender-female"
                                            value="female" <?php echo (isset($user['gender']) && $user['gender'] == 'female') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="gender-female">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender-other"
                                            value="other" <?php echo (isset($user['gender']) && $user['gender'] == 'other') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="gender-other">Other</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label for="type" class="form-label">Role</label>
                        <select id="type" name="type" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="1" <?php echo (isset($user['role']) && $user['role'] == 1) ? 'selected' : ''; ?>>Admin</option>
                            <option value="2" <?php echo (isset($user['role']) && $user['role'] == 2) ? 'selected' : ''; ?>>Manager</option>
                            <option value="3" <?php echo (isset($user['role']) && $user['role'] == 3) ? 'selected' : ''; ?>>Staff</option>
                        </select>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-email" class="input-group-text"><i
                                    class="ri-mail-line ri-20px"></i></span>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email"
                                aria-label="Email" aria-describedby="basic-icon-default-email"
                                value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" />
                        </div>
                    </div>

                    <!-- Contact Number -->
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact Number</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-contact" class="input-group-text"><i
                                    class="ri-phone-fill ri-20px"></i></span>
                            <input type="text" id="contact" name="contact" class="form-control"
                                placeholder="Contact Number" aria-label="Contact Number"
                                aria-describedby="basic-icon-default-contact"
                                value="<?php echo isset($user['contact']) ? htmlspecialchars($user['contact']) : ''; ?>" />
                        </div>
                    </div>

                    <!-- Password Fields -->


                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <div class="input-group input-group-merge">
                            <span id="basic-icon-default-address" class="input-group-text"><i
                                    class="ri-map-pin-line ri-20px"></i></span>
                            <textarea id="address" name="address" class="form-control" placeholder="Enter your address"
                                rows="4" aria-label="Address" aria-describedby="basic-icon-default-address"
                                required><?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 mt-3">Update User</button>
                </form>

                <br><br>
                <div id="message"></div>
            </div>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>



<script>
    $(document).ready(function () {
        $('#edit-user-form').on('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(this);


            $.ajax({
                url: 'update.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {

                    $('#message').html('<div class="alert alert-success">User updated successfully!</div>');
                    setTimeout(function () {
                        window.location.href = 'alluser.php';
                    }, 2000);

                },
                error: function (xhr, status, error) {
                    $('#message').html('<div class="alert alert-danger">Error: ' + xhr.responseText + '</div>');
                }
            });
        });
    });
    

</script>